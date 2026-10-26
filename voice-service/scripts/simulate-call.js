// Text-only test call against a running voice-service, without Twilio:
//   npm run simulate            (in another terminal: npm start)
// You type what the caller says; the assistant's replies are printed. Leads go to LARAVEL_URL,
// so point it at the local site, never production.
import readline from 'node:readline/promises';
import twilio from 'twilio';
import WebSocket from 'ws';
import { loadConfig } from '../src/config.js';

const config = loadConfig();
if (!process.env.LARAVEL_URL || /tsrepairservice\.com/.test(config.laravel.url)) {
  console.error('Set LARAVEL_URL in voice-service/.env to the local site (e.g. http://192.168.1.96:8000) and restart npm start.');
  process.exit(1);
}
const local = `http://127.0.0.1:${config.port}`;
const callSid = `CAsim${Date.now()}`;
const from = process.argv[2] || '+18055550123';
const sign = (url, params = {}) => twilio.getExpectedTwilioSignature(config.twilio.authToken, url, params);

const fields = { CallSid: callSid, From: from, To: config.twilio.number };
const twiml = await (await fetch(`${local}/voice`, {
  method: 'POST',
  headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Twilio-Signature': sign(`${config.publicUrl}/voice`, fields) },
  body: new URLSearchParams(fields),
})).text();

for (const [, text] of twiml.matchAll(/<Say[^>]*>([^<]*)<\/Say>/g)) console.log(`🔊 ${text}`);
if (!twiml.includes('<ConversationRelay')) {
  console.log(`\n(No AI on this call: ${twiml.includes('<Dial') ? 'the phone line mode transferred it to the technician' : twiml})`);
  process.exit(0);
}
const greeting = twiml.match(/welcomeGreeting="([^"]*)"/)?.[1];
const params = Object.fromEntries([...twiml.matchAll(/<Parameter name="([^"]+)" value="([^"]*)"\/>/g)].map((m) => [m[1], m[2]]));

const ws = new WebSocket(`ws://127.0.0.1:${config.port}/relay`, { headers: { 'X-Twilio-Signature': sign(config.relayUrl) } });
const rl = readline.createInterface({ input: process.stdin, output: process.stdout });
let reply = '';
let waiting;
// The assistant may answer in several pieces (e.g. after saving a lead); ask once it has been quiet.
const askSoon = () => { clearTimeout(waiting); waiting = setTimeout(ask, 1500); };

ws.on('open', async () => {
  ws.send(JSON.stringify({ type: 'setup', callSid, from, to: config.twilio.number, customParameters: params }));
  console.log(`🔊 ${greeting}\n(Type what the caller says. Empty line or Ctrl+C hangs up.)\n`);
  askSoon();
});
ws.on('message', (data) => {
  const message = JSON.parse(data);
  if (message.type === 'text') {
    reply += message.token;
    if (message.last && reply) {
      console.log(`🤖 ${reply}`);
      reply = '';
    }
    askSoon();
  } else if (message.type === 'end') {
    clearTimeout(waiting);
    console.log(`\n(Assistant ended the call: ${message.handoffData})`);
    ws.close();
  }
});
ws.on('close', () => {
  console.log('(Call closed. Check CRM → Requests and Phone.)');
  rl.close();
  process.exit(0);
});
ws.on('unexpected-response', (req, res) => {
  console.error(`WebSocket refused (${res.statusCode}): signature mismatch.`);
  process.exit(1);
});

async function ask() {
  const text = (await rl.question('🧑 ').catch(() => '')).trim();
  if (!text || ws.readyState !== WebSocket.OPEN) return ws.close();
  ws.send(JSON.stringify({ type: 'prompt', voicePrompt: text, lang: 'en-US', last: true }));
}
