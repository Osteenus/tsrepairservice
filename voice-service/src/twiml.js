const esc = (value) => String(value ?? '')
  .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

const response = (body) => `<?xml version="1.0" encoding="UTF-8"?><Response>${body}</Response>`;
const say = (config, text) => `<Say voice="${esc(config.tts.sayVoice)}">${esc(text)}</Say>`;

// Legal notice: keep this wording exactly (California two-party consent).
export const RECORDING_NOTICE = 'This call may be recorded to help us improve our service.';

const HINTS = 'refrigerator, fridge, freezer, washer, dryer, oven, stove, range, dishwasher, microwave, range hood, '
  + 'trash compactor, Whirlpool, Samsung, LG, GE, Bosch, KitchenAid, Maytag, Frigidaire, Sub-Zero, Thermador, '
  + 'Moorpark, Simi Valley, Thousand Oaks, Camarillo, Newbury Park, Oxnard, Ventura';

function relay(config, greeting, params = {}) {
  const parameters = Object.entries(params)
    .filter(([, value]) => value !== undefined && value !== null && value !== '')
    .map(([name, value]) => `<Parameter name="${esc(name)}" value="${esc(value)}"/>`).join('');
  return `<Connect action="${esc(config.publicUrl)}/voice/after">`
    + `<ConversationRelay url="${esc(config.relayUrl)}" welcomeGreeting="${esc(greeting)}"`
    + ` ttsProvider="${esc(config.tts.provider)}" voice="${esc(config.tts.voice)}" language="en-US"`
    + ` interruptible="any" hints="${esc(HINTS)}">${parameters}</ConversationRelay></Connect>`;
}

export function intakeTwiml(config) {
  return response(
    say(config, 'Hello, you have reached Tech Solutions Repair Service.')
    + say(config, RECORDING_NOTICE)
    + relay(config, 'How can I help you with your appliance today?', { mode: 'intake' }),
  );
}

/** Variant B: greeting and notice, then straight to the technician; /voice/direct-dial handles no answer. */
export function directTransferTwiml(config) {
  const record = config.twilio.recordCalls ? ' record="record-from-answer-dual"' : '';
  return response(
    say(config, 'Hello, you have reached Tech Solutions Repair Service.')
    + say(config, RECORDING_NOTICE)
    + `<Dial callerId="${esc(config.twilio.number)}" timeout="20"${record} action="${esc(config.publicUrl)}/voice/direct-dial">`
    + `<Number>${esc(config.twilio.ownerPhone)}</Number></Dial>`,
  );
}

/** The technician did not pick up in variant B: the AI assistant takes the request. */
export function missedTransferTwiml(config) {
  return response(relay(config,
    'Sorry, our technician cannot pick up right now. I can help you and arrange a call-back. What is going on with your appliance?',
    { mode: 'intake', missedTransfer: 'yes' }));
}

export function followupTwiml(config, lead) {
  const appliance = lead.applianceType ? `your ${lead.applianceType}` : 'your appliance';
  return response(
    say(config, 'Hello, this is Tech Solutions Repair Service calling about your repair request.')
    + say(config, RECORDING_NOTICE)
    + relay(config,
      'Sorry for the wait. Our technician is finishing up a job and will call you shortly. '
      + `Thank you for your patience. Do you still need help with ${appliance}?`,
      { mode: 'followup', leadId: lead.id, name: lead.name, applianceType: lead.applianceType }),
  );
}

export function rateLimitedTwiml(config) {
  return response(say(config, 'Thank you for calling Tech Solutions Repair Service. We already have your recent calls, '
    + 'and a technician will call you back as soon as possible. Goodbye.') + '<Hangup/>');
}

/** Transfer to the technician; with an apology first when the assistant itself failed. */
export function transferTwiml(config, { apologize = false } = {}) {
  return response(
    (apologize ? say(config, 'Sorry, we are having technical trouble. Let me connect you with a technician.') : '')
    + `<Dial callerId="${esc(config.twilio.number)}" timeout="25" action="${esc(config.publicUrl)}/voice/after-dial">`
    + `<Number>${esc(config.twilio.ownerPhone)}</Number></Dial>`,
  );
}

export function technicianBusyTwiml(config) {
  return response(say(config, 'Our technician could not pick up right now. '
    + 'They will call you back as soon as possible at this number. Thank you, goodbye.') + '<Hangup/>');
}

export const hangupTwiml = () => response('<Hangup/>');
