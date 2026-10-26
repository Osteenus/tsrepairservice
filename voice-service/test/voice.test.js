import assert from 'node:assert/strict';
import { after, before, describe, test } from 'node:test';
import twilio from 'twilio';
import WebSocket from 'ws';
import { createApp, pickVariant, RateLimiter } from '../src/app.js';
import { loadConfig } from '../src/config.js';
import { CallSession } from '../src/session.js';
import { RECORDING_NOTICE } from '../src/twiml.js';

process.env.NODE_ENV = 'test';

const config = loadConfig({
  PUBLIC_URL: 'https://voice.example.test', TWILIO_AUTH_TOKEN: 'test-token', VOICE_API_KEY: 'voice-key',
  OWNER_PHONE: '+18055550199', RECORD_CALLS: 'false', CALLS_PER_HOUR: '3',
});

class FakeLaravel {
  constructor() { this.leads = []; this.reports = []; this.calls = []; this.mode = { mode: 'ai', ai_percent: 50 }; }
  async settings() { return this.mode; }
  recordCall(fields) { this.calls.push(fields); }
  async availability() { return true; }
  async customer() { return null; }
  async submitLead(lead) { this.leads.push(lead); return true; }
  async report(uri, body) { this.reports.push({ uri, body }); }
}

let server;
let base;
const laravel = new FakeLaravel();
const calls = [];

before(async () => {
  ({ server } = createApp(config, {
    laravel,
    claude: { respond: async () => { throw new Error('not used'); } },
    twilioClient: { calls: { create: async (options) => { calls.push(options); return { sid: 'CAfollow' }; } } },
  }));
  await new Promise((resolve) => server.listen(0, '127.0.0.1', resolve));
  base = `http://127.0.0.1:${server.address().port}`;
});
after(() => server.close());

function twilioPost(path, fields, { sign = true } = {}) {
  const signature = twilio.getExpectedTwilioSignature('test-token', config.publicUrl + path, fields);
  return fetch(base + path, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded', ...(sign ? { 'X-Twilio-Signature': signature } : {}) },
    body: new URLSearchParams(fields),
  });
}

describe('POST /voice', () => {
  test('answers with the greeting, the exact recording notice and ConversationRelay', async () => {
    const res = await twilioPost('/voice', { CallSid: 'CA1', From: '+18055550101' });
    assert.equal(res.status, 200);
    const body = await res.text();
    assert.match(body, /<Say[^>]*>Hello, you have reached Tech Solutions Repair Service\.<\/Say>/);
    assert.ok(body.includes(`>${RECORDING_NOTICE}</Say>`));
    assert.equal(RECORDING_NOTICE, 'This call may be recorded to help us improve our service.');
    assert.ok(body.indexOf(RECORDING_NOTICE) < body.indexOf('<Connect'));
    assert.match(body, /<ConversationRelay url="wss:\/\/voice\.example\.test\/relay" welcomeGreeting="[^"]+"/);
  });

  test('rejects a missing or wrong signature with 403', async () => {
    assert.equal((await twilioPost('/voice', { CallSid: 'CA1' }, { sign: false })).status, 403);
    const res = await fetch(base + '/voice', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Twilio-Signature': 'forged' },
      body: 'CallSid=CA1',
    });
    assert.equal(res.status, 403);
  });

  test('the fourth call from one number within an hour is turned away', async () => {
    for (let i = 0; i < 3; i++) {
      assert.match(await (await twilioPost('/voice', { CallSid: `CA${i}`, From: '+18055550777' })).text(), /ConversationRelay/);
    }
    const body = await (await twilioPost('/voice', { CallSid: 'CA9', From: '+18055550777' })).text();
    assert.doesNotMatch(body, /ConversationRelay/);
    assert.match(body, /<Hangup\/>/);
  });

  test('the relay WebSocket refuses an unsigned handshake', async () => {
    const ws = new WebSocket(base.replace('http', 'ws') + '/relay');
    const status = await new Promise((resolve) => ws.on('unexpected-response', (req, res) => resolve(res.statusCode)));
    assert.equal(status, 403);
  });

  test('the relay WebSocket accepts a handshake signed for the public wss:// URL', async () => {
    const signature = twilio.getExpectedTwilioSignature('test-token', 'wss://voice.example.test/relay', {});
    const ws = new WebSocket(base.replace('http', 'ws') + '/relay', { headers: { 'X-Twilio-Signature': signature } });
    await new Promise((resolve, reject) => { ws.on('open', resolve); ws.on('unexpected-response', () => reject(new Error('refused'))); });
    ws.close();
  });
});

describe('phone line modes (A/B)', () => {
  test('transfer mode greets, plays the notice and rings the technician; the variant is recorded', async () => {
    laravel.mode = { mode: 'transfer', ai_percent: 50 };
    const body = await (await twilioPost('/voice', { CallSid: 'CAb1', From: '+18055550301' })).text();
    laravel.mode = { mode: 'ai', ai_percent: 50 };
    assert.ok(body.includes(`>${RECORDING_NOTICE}</Say>`));
    assert.match(body, /<Dial callerId="\+18053348375" timeout="20"[^>]*action="https:\/\/voice\.example\.test\/voice\/direct-dial"><Number>\+18055550199<\/Number><\/Dial>/);
    assert.doesNotMatch(body, /ConversationRelay/);
    assert.deepEqual(laravel.calls.at(-1), { call_sid: 'CAb1', caller_phone: '+18055550301', variant: 'transfer' });
  });

  test('a missed transfer hands the caller to the AI; an answered one just ends', async () => {
    const missed = await (await twilioPost('/voice/direct-dial', { CallSid: 'CAb1', CallStatus: 'in-progress', DialCallStatus: 'no-answer' })).text();
    assert.match(missed, /<ConversationRelay [^>]*welcomeGreeting="Sorry, our technician cannot pick up/);
    assert.match(missed, /<Parameter name="missedTransfer" value="yes"\/>/);
    assert.deepEqual(laravel.calls.at(-1), { call_sid: 'CAb1', transfer_status: 'missed' });

    const answered = await (await twilioPost('/voice/direct-dial', { CallSid: 'CAb2', CallStatus: 'completed', DialCallStatus: 'completed' })).text();
    assert.match(answered, /<Hangup\/>/);
    assert.deepEqual(laravel.calls.at(-1), { call_sid: 'CAb2', transfer_status: 'answered' });
  });

  test('A/B split follows the AI share; no technician number means AI only', () => {
    const ab = { mode: 'ab', ai_percent: 30 };
    assert.equal(pickVariant(ab, '+1805', () => 0.29), 'ai');
    assert.equal(pickVariant(ab, '+1805', () => 0.30), 'transfer');
    assert.equal(pickVariant({ mode: 'transfer' }, '', () => 0.9), 'ai');
    assert.equal(pickVariant({ mode: 'ai' }, '+1805', () => 0.9), 'ai');
  });
});

describe('transfer and follow-up', () => {
  test('a transfer handoff dials the technician from the business number', async () => {
    const body = await (await twilioPost('/voice/after', {
      CallSid: 'CA5', CallStatus: 'in-progress', SessionStatus: 'ended', HandoffData: JSON.stringify({ reason: 'transfer' }),
    })).text();
    assert.match(body, /<Dial callerId="\+18053348375"[^>]*><Number>\+18055550199<\/Number><\/Dial>/);
  });

  test('follow-up needs the API key and is placed only once per lead', async () => {
    const post = (headers) => fetch(base + '/followup/42', {
      method: 'POST', headers: { 'Content-Type': 'application/json', ...headers },
      body: JSON.stringify({ phone: '(805) 555-0123', name: 'Jane', appliance_type: 'dishwasher' }),
    });
    assert.equal((await post({ Authorization: 'Bearer wrong' })).status, 401);
    assert.equal((await post({ Authorization: 'Bearer voice-key' })).status, 202);
    assert.equal((await post({ Authorization: 'Bearer voice-key' })).status, 409);
    assert.equal(calls.length, 1);
    assert.equal(calls[0].to, '+18055550123');
    assert.ok(calls[0].twiml.includes(RECORDING_NOTICE));
    assert.match(calls[0].twiml, /<Parameter name="mode" value="followup"\/>/);
  });
});

describe('call session (end)', () => {
  test('a visit without agreement to the service call fee is refused', async () => {
    const leads = new FakeLaravel();
    const session = new CallSession({ readyState: 1, send() {} }, config, { laravel: leads, claude: {} });
    await session.onMessage(JSON.stringify({ type: 'setup', callSid: 'CAfee', from: '+18055550123', customParameters: {} }));
    const refused = await session.runTool('submit_lead', { reported_problem: 'Leaks', consent_to_callback: true, fee_accepted: false, outcome: 'visit' });
    assert.equal(refused.saved, false);
    assert.equal(leads.leads.length, 0);
    // A call-back does not need the fee agreement.
    assert.equal((await session.runTool('submit_lead', { reported_problem: 'Leaks', consent_to_callback: true, outcome: 'callback' })).saved, true);
    assert.equal(leads.leads[0].fee_accepted, false);
    clearTimeout(session.timer);
  });

  test('after end_call the assistant is not asked for another reply', async () => {
    const ws = { readyState: 1, sent: [], send(data) { this.sent.push(JSON.parse(data)); } };
    let requests = 0;
    const session = new CallSession(ws, config, {
      laravel: new FakeLaravel(),
      claude: { respond: async ({ onText }) => {
        requests++;
        onText('Thank you for calling, goodbye.');
        return { content: [{ type: 'text', text: 'Thank you for calling, goodbye.' }, { type: 'tool_use', id: 'e1', name: 'end_call', input: {} }], stop_reason: 'tool_use', usage: {} };
      } },
    });
    await session.onMessage(JSON.stringify({ type: 'setup', callSid: 'CAbye', from: '+18055550123', customParameters: {} }));
    await session.onMessage(JSON.stringify({ type: 'prompt', voicePrompt: 'No, thank you', last: true }));
    clearTimeout(session.endTimer);
    clearTimeout(session.timer);
    assert.equal(requests, 1);
    assert.equal(ws.sent.filter((m) => m.token === 'Thank you for calling, goodbye.').length, 1);
  });
});

describe('call session', () => {
  const fakeWs = () => ({ readyState: 1, sent: [], send(data) { this.sent.push(JSON.parse(data)); } });

  test('the call time limit says a closing line, saves the lead for a call-back and ends', async () => {
    const ws = fakeWs();
    const leads = new FakeLaravel();
    const session = new CallSession(ws, { ...config, callLimitMs: 30 }, {
      laravel: leads,
      // A model that is still "thinking" when time runs out.
      claude: { respond: ({ signal }) => new Promise((resolve, reject) => signal.addEventListener('abort', () => reject(new Error('aborted')))) },
    });
    await session.onMessage(JSON.stringify({ type: 'setup', callSid: 'CAlong', from: '+18055550123', customParameters: { mode: 'intake' } }));
    session.onMessage(JSON.stringify({ type: 'prompt', voicePrompt: 'My fridge is not cooling', last: true }));

    await new Promise((resolve) => setTimeout(resolve, 60));
    const closing = ws.sent.find((m) => m.type === 'text' && /out of time/.test(m.token));
    assert.ok(closing, 'closing line spoken');
    assert.equal(closing.last, true);
    assert.equal(leads.leads.length, 1);
    assert.equal(leads.leads[0].outcome, 'timeout');
    assert.equal(leads.leads[0].call_sid, 'CAlong');
    assert.equal(leads.leads[0].reported_problem, 'My fridge is not cooling');

    clearTimeout(session.endTimer);
    session.end('end', '');
    await new Promise((resolve) => setTimeout(resolve, 1300));
    assert.deepEqual(ws.sent.at(-1), { type: 'end', handoffData: JSON.stringify({ reason: 'end' }) });
    await session.close();
    assert.equal(leads.leads.length, 1, 'no second lead on hang-up');
  });

  test('submit_lead from the model reaches Laravel once with the caller number', async () => {
    const ws = fakeWs();
    const leads = new FakeLaravel();
    const replies = [
      { content: [{ type: 'tool_use', id: 't1', name: 'submit_lead', input: { caller_name: 'Jane', reported_problem: 'Leaks', agent_summary: 'Washer leaks.', consent_to_callback: true, fee_accepted: true, outcome: 'visit' } }], stop_reason: 'tool_use', usage: { input_tokens: 10, output_tokens: 5 } },
      { content: [{ type: 'text', text: 'Our closest technician will call you back in 20 minutes.' }], stop_reason: 'end_turn', usage: { input_tokens: 12, output_tokens: 9 } },
    ];
    const session = new CallSession(ws, config, {
      laravel: leads,
      claude: { respond: async ({ onText }) => { const reply = replies.shift(); reply.content.filter((b) => b.type === 'text').forEach((b) => onText(b.text)); return reply; } },
    });
    await session.onMessage(JSON.stringify({ type: 'setup', callSid: 'CAok', from: '+18055550123', customParameters: {} }));
    await session.onMessage(JSON.stringify({ type: 'prompt', voicePrompt: 'Yes, send someone', last: true }));
    await session.close();

    assert.equal(leads.leads.length, 1);
    assert.equal(leads.leads[0].caller_phone, '+18055550123');
    assert.equal(leads.leads[0].recording_notice_played, true);
    assert.equal(leads.leads[0].fee_accepted, true);
    assert.ok(ws.sent.some((m) => m.token === 'Our closest technician will call you back in 20 minutes.'));
    assert.deepEqual(leads.calls, [
      { call_sid: 'CAok', reached_ai: true },
      { call_sid: 'CAok', ai_turns: 2, input_tokens: 22, output_tokens: 14, timed_out: false },
    ]);
  });
});

test('rate limiter forgets calls older than an hour', () => {
  let now = 0;
  const limiter = new RateLimiter(1, 3600_000, () => now);
  assert.equal(limiter.allow('+1'), true);
  assert.equal(limiter.allow('+1'), false);
  now = 3600_001;
  assert.equal(limiter.allow('+1'), true);
});
