import crypto from 'node:crypto';
import http from 'node:http';
import express from 'express';
import twilio from 'twilio';
import { WebSocketServer } from 'ws';
import { log } from './log.js';
import { CallSession } from './session.js';
import {
  directTransferTwiml, followupTwiml, hangupTwiml, intakeTwiml, missedTransferTwiml, rateLimitedTwiml,
  technicianBusyTwiml, transferTwiml,
} from './twiml.js';

/** A/B: which variant answers this call. Without a technician number everything goes to the AI. */
export function pickVariant(settings, ownerPhone, random = Math.random) {
  if (!ownerPhone || settings.mode === 'ai') return 'ai';
  if (settings.mode === 'transfer') return 'transfer';
  return random() * 100 < Number(settings.ai_percent ?? 50) ? 'ai' : 'transfer';
}

/** Calls per caller number in the last hour (in memory; resets on restart). */
export class RateLimiter {
  constructor(limit, windowMs = 3600_000, now = () => Date.now()) {
    Object.assign(this, { limit, windowMs, now, hits: new Map() });
  }

  allow(key) {
    if (!key || key === 'anonymous') return true;
    const since = this.now() - this.windowMs;
    const recent = (this.hits.get(key) || []).filter((time) => time > since);
    if (recent.length >= this.limit) {
      this.hits.set(key, recent);
      return false;
    }
    recent.push(this.now());
    this.hits.set(key, recent);
    return true;
  }
}

const e164 = (phone) => {
  const digits = String(phone || '').replace(/\D/g, '');
  if (String(phone).trim().startsWith('+') && digits.length >= 8) return `+${digits}`;
  if (digits.length === 10) return `+1${digits}`;
  if (digits.length === 11 && digits.startsWith('1')) return `+${digits}`;
  return null;
};

const safeEqual = (a, b) => {
  const left = Buffer.from(String(a));
  const right = Buffer.from(String(b));
  return left.length === right.length && crypto.timingSafeEqual(left, right);
};

export function createApp(config, deps) {
  const { laravel, twilioClient } = deps;
  const limiter = deps.limiter || new RateLimiter(config.callsPerHour);
  const followedUp = new Set();
  const app = express();
  app.disable('x-powered-by');
  app.set('trust proxy', 'loopback');

  // Twilio webhooks: reject anything not signed with our auth token.
  const twilioOnly = (req, res, next) => {
    const url = config.publicUrl + req.originalUrl;
    const valid = config.twilio.authToken
      && twilio.validateRequest(config.twilio.authToken, req.get('X-Twilio-Signature') || '', url, req.body || {});
    if (!valid) {
      log('bad_signature', { path: req.path });
      return res.status(403).send('Forbidden');
    }
    return next();
  };
  const xml = (res, body) => res.type('text/xml').send(body);
  const form = express.urlencoded({ extended: false });

  app.get('/health', (req, res) => res.json({ ok: true }));

  app.post('/voice', form, twilioOnly, async (req, res) => {
    const { CallSid: callSid, From: from } = req.body;
    if (!limiter.allow(from)) {
      log('rate_limited', { callSid });
      laravel.recordCall({ call_sid: callSid, caller_phone: from, rate_limited: true });
      return xml(res, rateLimitedTwiml(config));
    }
    const variant = pickVariant(await laravel.settings(), config.twilio.ownerPhone, deps.random);
    laravel.recordCall({ call_sid: callSid, caller_phone: from, variant });
    return xml(res, variant === 'transfer' ? directTransferTwiml(config) : intakeTwiml(config));
  });

  // Variant B: did the technician pick up? If not, the AI assistant takes over.
  app.post('/voice/direct-dial', form, twilioOnly, (req, res) => {
    const answered = req.body.DialCallStatus === 'completed';
    laravel.recordCall({ call_sid: req.body.CallSid, transfer_status: answered ? 'answered' : 'missed' });
    if (answered || req.body.CallStatus === 'completed') return xml(res, hangupTwiml());
    return xml(res, missedTransferTwiml(config));
  });

  // <Connect action>: the AI session ended. Transfer on request, or when the assistant failed.
  app.post('/voice/after', form, twilioOnly, (req, res) => {
    let reason = null;
    try {
      reason = JSON.parse(req.body.HandoffData || '{}').reason;
    } catch { /* no handoff data */ }
    const failed = req.body.SessionStatus === 'failed' || reason === 'error';
    if (failed) log('session_failed', { callSid: req.body.CallSid, errorCode: req.body.ErrorCode });
    if ((reason === 'transfer' || failed) && req.body.CallStatus !== 'completed' && config.twilio.ownerPhone) {
      return xml(res, transferTwiml(config, { apologize: failed }));
    }
    return xml(res, hangupTwiml());
  });

  app.post('/voice/after-dial', form, twilioOnly, (req, res) => {
    const answered = req.body.DialCallStatus === 'completed';
    // The parent call is the caller's call, whose lead was saved under its CallSid.
    laravel.report(`/api/voice/leads/${encodeURIComponent(req.body.CallSid)}/transfer`, { answered });
    laravel.recordCall({ call_sid: req.body.CallSid, transfer_status: answered ? 'answered' : 'missed' });
    return xml(res, answered ? hangupTwiml() : technicianBusyTwiml(config));
  });

  // Laravel scheduler asks for the single follow-up call of a lead.
  app.post('/followup/:leadId', express.json(), async (req, res) => {
    const token = (req.get('Authorization') || '').replace(/^Bearer /, '');
    if (!config.laravel.apiKey || !safeEqual(token, config.laravel.apiKey)) return res.status(401).json({ error: 'unauthorized' });

    const { leadId } = req.params;
    const to = e164(req.body?.phone);
    if (!/^\d+$/.test(leadId) || !to) return res.status(422).json({ error: 'invalid lead or phone' });
    if (followedUp.has(leadId)) return res.status(409).json({ error: 'already followed up' });
    followedUp.add(leadId);

    try {
      const call = await twilioClient.calls.create({
        to,
        from: config.twilio.number,
        twiml: followupTwiml(config, { id: leadId, name: req.body.name, applianceType: req.body.appliance_type }),
      });
      log('followup_started', { leadId, callSid: call.sid });
      return res.status(202).json({ call_sid: call.sid });
    } catch (error) {
      log('followup_failed', { leadId, reason: error.message });
      return res.status(502).json({ error: 'twilio call failed' });
    }
  });

  const server = http.createServer(app);
  const wss = new WebSocketServer({ noServer: true });

  server.on('upgrade', (req, socket, head) => {
    const valid = new URL(req.url, 'http://localhost').pathname === '/relay'
      && config.twilio.authToken
      && twilio.validateRequest(config.twilio.authToken, req.headers['x-twilio-signature'] || '', config.relayUrl, {});
    if (!valid) {
      log('bad_signature', { path: '/relay' });
      socket.write('HTTP/1.1 403 Forbidden\r\nConnection: close\r\n\r\n');
      return socket.destroy();
    }
    return wss.handleUpgrade(req, socket, head, (ws) => {
      const session = (deps.createSession || ((socketWs) => new CallSession(socketWs, config, {
        claude: deps.claude, laravel, twilio: twilioClient,
      })))(ws);
      ws.on('message', (data) => session.onMessage(data.toString()));
      ws.on('close', () => session.close());
    });
  });

  return { app, server };
}
