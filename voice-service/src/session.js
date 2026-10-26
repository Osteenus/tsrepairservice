import { log } from './log.js';
import { systemPrompt, toolsFor } from './agent.js';

const TIMEOUT_LINE = 'I am sorry, we are out of time for this call. A technician will call you back as soon as possible '
  + 'at this number. Thank you for calling, goodbye.';

/**
 * One ConversationRelay WebSocket = one phone call. Holds the conversation only while the call
 * lasts; leads and follow-up answers go to Laravel.
 */
export class CallSession {
  constructor(ws, config, { claude, laravel, twilio, now = () => Date.now() }) {
    Object.assign(this, { ws, config, claude, laravel, twilio, now });
    this.messages = [];
    this.callerTexts = [];
    this.usage = { input: 0, output: 0, cacheRead: 0 };
    this.turns = 0;
    this.queue = Promise.resolve();
    this.leadSent = false;
    this.ending = false;
  }

  async onMessage(raw) {
    let message;
    try {
      message = JSON.parse(raw);
    } catch {
      return;
    }
    switch (message.type) {
      case 'setup': return this.setup(message);
      case 'prompt': return this.enqueue(message.voicePrompt);
      case 'interrupt': return this.interrupt(message.utteranceUntilInterrupt);
      case 'error': return log('relay_error', { callSid: this.callSid, description: message.description });
      default: return undefined;
    }
  }

  async setup(message) {
    const params = message.customParameters || {};
    this.callSid = message.callSid;
    this.mode = params.mode === 'followup' ? 'followup' : 'intake';
    // Outbound follow-up: the customer is "to"; incoming call: "from".
    this.callerPhone = this.mode === 'followup' ? message.to : message.from;
    this.lead = this.mode === 'followup' ? { id: params.leadId, name: params.name, applianceType: params.applianceType } : null;
    this.missedTransfer = params.missedTransfer === 'yes';
    if (this.mode === 'intake') this.laravel.recordCall?.({ call_sid: this.callSid, reached_ai: true });
    this.startedAt = this.now();
    this.timer = setTimeout(() => this.timeUp(), this.config.callLimitMs);

    if (this.config.twilio.recordCalls && this.twilio) {
      this.twilio.calls(this.callSid).recordings.create({ recordingChannels: 'dual' })
        .catch((error) => log('recording_failed', { callSid: this.callSid, reason: error.message }));
    }

    const known = /^\+?\d{7,}$/.test(this.callerPhone || '');
    this.ready = Promise.all([
      this.laravel.availability(),
      this.mode === 'intake' && known ? this.laravel.customer(this.callerPhone) : null,
    ]).then(([technicianAvailable, customer]) => {
      this.technicianAvailable = technicianAvailable;
      this.system = systemPrompt({
        mode: this.mode, technicianAvailable, callerPhoneKnown: known, customer, lead: this.lead, missedTransfer: this.missedTransfer,
      });
    });
    // The welcome greeting is spoken by Twilio; keep it in the history so the model knows it was said.
    this.messages.push({ role: 'user', content: '(The call has connected; the greeting was played.)' });
  }

  enqueue(text) {
    if (!text || this.ending) return this.queue;
    this.queue = this.queue.then(() => this.turn(text)).catch((error) => this.failed(error));
    return this.queue;
  }

  async turn(text) {
    await this.ready;
    if (this.ending) return;
    this.callerTexts.push(text);
    this.messages.push({ role: 'user', content: text });

    // Model turns until it stops calling tools (submit_lead, then the closing sentence, ...).
    for (let step = 0; step < 4 && !this.ending; step++) {
      this.abort = new AbortController();
      let spoken = '';
      const message = await this.claude.respond({
        system: this.system,
        tools: toolsFor(this.mode),
        messages: this.messages,
        signal: this.abort.signal,
        onText: (token) => {
          spoken += token;
          this.send({ type: 'text', token, last: false });
        },
      }).catch((error) => {
        if (this.abort.signal.aborted) return null;
        throw error;
      });
      this.send({ type: 'text', token: '', last: true });
      if (!message) return; // Interrupted by the caller; interrupt() fixed the history.

      this.turns++;
      this.usage.input += message.usage.input_tokens || 0;
      this.usage.output += message.usage.output_tokens || 0;
      this.usage.cacheRead += message.usage.cache_read_input_tokens || 0;
      this.messages.push({ role: 'assistant', content: message.content });
      this.lastSpoken = spoken;

      const calls = message.content.filter((block) => block.type === 'tool_use');
      if (message.stop_reason !== 'tool_use' || !calls.length) break;
      const results = [];
      for (const call of calls) {
        results.push({ type: 'tool_result', tool_use_id: call.id, content: JSON.stringify(await this.runTool(call.name, call.input || {})) });
      }
      this.messages.push({ role: 'user', content: results });
      // Goodbye or transfer sentence was already spoken: no further reply.
      if (this.pendingEnd) break;
    }

    if (this.pendingEnd) this.finish(this.pendingEnd);
  }

  async runTool(name, input) {
    switch (name) {
      case 'submit_lead':
        await this.sendLead({ ...input, outcome: input.outcome === 'callback' ? 'callback' : 'visit' });
        return { saved: true, technician_available: this.technicianAvailable };
      case 'transfer_to_technician':
        if (!this.config.twilio.ownerPhone) return { transferred: false, note: 'No technician line is configured. Offer a call-back instead.' };
        this.pendingEnd = 'transfer';
        return { transferring: true, note: 'The transfer starts when you finish this sentence. Do not say anything else.' };
      case 'end_call':
        this.pendingEnd = 'end';
        return { ending: true };
      case 'followup_result':
        if (this.lead?.id) {
          await this.laravel.report(`/api/voice/leads/${encodeURIComponent(this.lead.id)}/followup`, {
            still_needs_help: Boolean(input.still_needs_help), notes: input.notes || null,
          });
        }
        return { saved: true };
      default:
        return { error: `Unknown tool ${name}` };
    }
  }

  /** Lead for Laravel; missing contact fields fall back to what the call itself tells us. */
  async sendLead(fields) {
    if (this.leadSent || this.mode !== 'intake') return;
    this.leadSent = true;
    await this.laravel.submitLead({
      call_sid: this.callSid,
      caller_phone: fields.caller_phone || this.callerPhone || 'Unknown',
      caller_name: fields.caller_name || null,
      service_address: fields.service_address || null,
      appliance_type: fields.appliance_type || null,
      appliance_brand: fields.appliance_brand || null,
      reported_problem: fields.reported_problem || this.callerTexts.join(' ').slice(0, 4000) || null,
      agent_summary: fields.agent_summary || null,
      consent_to_callback: Boolean(fields.consent_to_callback),
      preferred_callback_time: fields.preferred_callback_time || null,
      outcome: fields.outcome,
      transferred: fields.outcome === 'transfer',
      recording_notice_played: true,
      started_at: new Date(this.startedAt).toISOString(),
      ended_at: new Date(this.now()).toISOString(),
    });
  }

  interrupt(heard) {
    this.abort?.abort();
    // Keep what the caller actually heard as the assistant's last words.
    const last = this.messages.at(-1);
    if (last?.role === 'user' && typeof last.content === 'string') {
      this.messages.push({ role: 'assistant', content: heard?.trim() || '(interrupted)' });
    }
  }

  /** Hard limit per call: short closing line, lead saved for a call-back, hang up. */
  async timeUp() {
    if (this.ending) return;
    this.abort?.abort();
    this.ending = true;
    this.timedOut = true;
    this.send({ type: 'text', token: TIMEOUT_LINE, last: true });
    await this.sendLead({ outcome: 'timeout' });
    this.end('end', TIMEOUT_LINE);
  }

  async finish(reason) {
    if (this.ending) return;
    this.ending = true;
    if (reason === 'transfer') await this.sendLead({ outcome: 'transfer' });
    this.end(reason, this.lastSpoken || '');
  }

  /** Let text-to-speech finish the last sentence before handing the call back to Twilio. */
  end(reason, lastSentence) {
    const delay = Math.min(12000, 1200 + lastSentence.length * 65);
    this.endTimer = setTimeout(() => this.send({ type: 'end', handoffData: JSON.stringify({ reason }) }), delay);
  }

  failed(error) {
    log('agent_failed', { callSid: this.callSid, reason: error?.message });
    // Hand the call to a person rather than leave the caller in silence.
    this.ending = true;
    this.send({ type: 'end', handoffData: JSON.stringify({ reason: 'error' }) });
  }

  async close() {
    clearTimeout(this.timer);
    // Caller hung up mid-conversation: keep what they said (the owner gets an email, no follow-up call).
    if (this.mode === 'intake' && !this.leadSent && this.callerTexts.length) {
      await this.sendLead({ outcome: 'hangup' });
    }
    if (this.mode === 'intake' && this.callSid) {
      this.laravel.recordCall?.({
        call_sid: this.callSid, ai_turns: this.turns, input_tokens: this.usage.input, output_tokens: this.usage.output,
        timed_out: Boolean(this.timedOut),
      });
    }
    log('call_end', {
      callSid: this.callSid,
      mode: this.mode,
      durationSec: this.startedAt ? Math.round((this.now() - this.startedAt) / 1000) : 0,
      turns: this.turns,
      inputTokens: this.usage.input,
      outputTokens: this.usage.output,
      cacheReadTokens: this.usage.cacheRead,
      timedOut: Boolean(this.timedOut),
      leadSent: this.leadSent,
    });
  }

  send(message) {
    if (this.ws.readyState === 1) this.ws.send(JSON.stringify(message));
  }
}
