import fs from 'node:fs/promises';
import path from 'node:path';
import { log } from './log.js';

/**
 * Talks to the Laravel CRM. Leads that cannot be delivered are kept in outbox/ (one JSON file per
 * call) and retried every minute; Laravel deduplicates by call_sid, so retries are safe.
 */
export class Laravel {
  constructor(config, { fetchImpl = fetch } = {}) {
    this.url = config.laravel.url;
    this.apiKey = config.laravel.apiKey;
    this.outboxDir = config.outboxDir;
    this.fetch = fetchImpl;
  }

  async request(method, uri, body, timeoutMs = 5000) {
    const response = await this.fetch(this.url + uri, {
      method,
      headers: { Authorization: `Bearer ${this.apiKey}`, Accept: 'application/json', 'Content-Type': 'application/json' },
      body: body ? JSON.stringify(body) : undefined,
      signal: AbortSignal.timeout(timeoutMs),
    });
    return { status: response.status, json: response.status < 300 ? await response.json() : null };
  }

  /** Phone line mode from the CRM, cached briefly; the AI assistant when Laravel is unreachable. */
  async settings() {
    if (this.cachedSettings && Date.now() - this.cachedAt < 30_000) return this.cachedSettings;
    try {
      const { json } = await this.request('GET', '/api/voice/settings', null, 1500);
      if (json?.mode) {
        this.cachedSettings = json;
        this.cachedAt = Date.now();
        return json;
      }
    } catch { /* fall through */ }
    return this.cachedSettings || { mode: 'ai', ai_percent: 100 };
  }

  /** A piece of the call statistics (fire and forget). */
  recordCall(fields) {
    return this.report('/api/voice/calls', fields);
  }

  async availability() {
    try {
      const { json } = await this.request('GET', '/api/voice/availability', null, 2000);
      return json?.technician_available ?? false;
    } catch {
      return false;
    }
  }

  async customer(phone) {
    try {
      const { json } = await this.request('GET', '/api/voice/customers?phone=' + encodeURIComponent(phone), null, 2000);
      return json;
    } catch {
      return null;
    }
  }

  /** Never throws: an undeliverable lead goes to the outbox. */
  async submitLead(lead) {
    try {
      const { status } = await this.request('POST', '/api/voice/leads', lead);
      if (status === 200 || status === 201) return true;
      if (status >= 400 && status < 500) {
        // Rejected payload: keep it for a person to look at, do not retry.
        log('lead_rejected', { callSid: lead.call_sid, status });
        await this.save(lead, 'rejected');
        return false;
      }
      throw new Error(`HTTP ${status}`);
    } catch (error) {
      log('lead_queued', { callSid: lead.call_sid, reason: error.message });
      await this.save(lead);
      return false;
    }
  }

  async report(uri, body) {
    try {
      await this.request('POST', uri, body);
    } catch (error) {
      log('report_failed', { uri, reason: error.message });
    }
  }

  async save(lead, folder = '') {
    const dir = path.join(this.outboxDir, folder);
    await fs.mkdir(dir, { recursive: true });
    await fs.writeFile(path.join(dir, `${lead.call_sid.replace(/[^\w-]/g, '')}.json`), JSON.stringify(lead));
  }

  async flushOutbox() {
    let files = [];
    try {
      files = (await fs.readdir(this.outboxDir)).filter((file) => file.endsWith('.json'));
    } catch {
      return;
    }
    for (const file of files) {
      const full = path.join(this.outboxDir, file);
      const lead = JSON.parse(await fs.readFile(full, 'utf8'));
      try {
        const { status } = await this.request('POST', '/api/voice/leads', lead);
        if (status === 200 || status === 201) {
          await fs.unlink(full);
          log('lead_delivered_from_outbox', { callSid: lead.call_sid });
        }
      } catch {
        return; // Laravel still down; try again next round.
      }
    }
  }
}
