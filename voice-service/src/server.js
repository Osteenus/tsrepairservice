import twilio from 'twilio';
import { createClaude } from './agent.js';
import { createApp } from './app.js';
import { loadConfig } from './config.js';
import { Laravel } from './laravel.js';
import { log } from './log.js';

const config = loadConfig();
const missing = ['TWILIO_ACCOUNT_SID', 'TWILIO_AUTH_TOKEN', 'VOICE_API_KEY', 'ANTHROPIC_API_KEY']
  .filter((name) => !process.env[name]);
if (missing.length) {
  // Names only, never values.
  console.error(`Missing settings in voice-service/.env: ${missing.join(', ')}`);
  process.exit(1);
}

if (!config.twilio.ownerPhone) console.warn('OWNER_PHONE is not set: transfers are off and every call goes to the AI assistant.');

const laravel = new Laravel(config);
const { server } = createApp(config, {
  laravel,
  claude: createClaude(config),
  twilioClient: twilio(config.twilio.accountSid, config.twilio.authToken),
});

// Leads saved while Laravel was unreachable.
setInterval(() => laravel.flushOutbox(), 60_000).unref();
laravel.flushOutbox();

server.listen(config.port, '127.0.0.1', () => log('listening', { port: config.port, model: config.claude.model }));
