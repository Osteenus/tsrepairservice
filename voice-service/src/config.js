// All settings come from the environment (voice-service/.env). Values are never logged.
export function loadConfig(env = process.env) {
  const publicUrl = (env.PUBLIC_URL || 'https://voice.tsrepairservice.com').replace(/\/$/, '');
  return {
    port: Number(env.PORT || 3001),
    publicUrl,
    // Twilio signs the WebSocket handshake against this exact wss:// URL.
    relayUrl: publicUrl.replace(/^http/, 'ws') + '/relay',
    twilio: {
      accountSid: env.TWILIO_ACCOUNT_SID || '',
      authToken: env.TWILIO_AUTH_TOKEN || '',
      number: env.TWILIO_NUMBER || '+18053348375',
      ownerPhone: env.OWNER_PHONE || '',
      recordCalls: env.RECORD_CALLS !== 'false',
    },
    tts: {
      provider: env.TTS_PROVIDER || 'Amazon',
      voice: env.TTS_VOICE || 'Joanna-Neural',
      sayVoice: env.SAY_VOICE || 'Polly.Joanna-Neural',
    },
    laravel: {
      url: (env.LARAVEL_URL || 'https://tsrepairservice.com').replace(/\/$/, ''),
      apiKey: env.VOICE_API_KEY || '',
    },
    claude: {
      model: env.CLAUDE_MODEL || 'claude-haiku-5-5',
      // Phone calls need fast answers; raise only if answers get worse.
      effort: env.CLAUDE_EFFORT || 'low',
    },
    callLimitMs: Number(env.CALL_LIMIT_SECONDS || 300) * 1000,
    callsPerHour: Number(env.CALLS_PER_HOUR || 3),
    outboxDir: env.OUTBOX_DIR || new URL('../outbox/', import.meta.url).pathname,
  };
}
