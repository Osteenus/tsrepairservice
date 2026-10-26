import fs from 'node:fs';
import Anthropic from '@anthropic-ai/sdk';

const PROMPT = fs.readFileSync(new URL('../prompts/agent.md', import.meta.url), 'utf8');

// How the OUTPUT section of the prompt is delivered: as tool calls, not as text the caller hears.
const TOOLING = `
SPEAKING AND TOOLS
- Everything you write is spoken aloud by text-to-speech. Use plain sentences: no lists, markdown, emojis or abbreviations. Say phone numbers digit by digit.
- Do not repeat or paraphrase what the caller just said ("I'm sorry to hear your washer is shaking..."). Acknowledge in two or three words at most ("Sorry about that." or "Got it.") and go straight to your question or advice.
- Ask one question per reply.
- Every reply: at most two short sentences plus one question, about 30 words. Give one tip at a time, never a list of tips.
- Give your first recommendation in its own reply. Offer the visit in a separate reply, worded like this: "A technician can take a look. The $95 service call fee is included in the total repair cost. Is that okay with you?" A yes to that question is the caller's agreement to the visit and the fee; do not ask "would you like to schedule a visit" without the fee question.
- Produce the structured lead by calling submit_lead (once, as soon as the caller agrees to a visit or a call-back and you have their details), then say the call-back sentence.
- To transfer, say one short sentence that you are connecting them, then call transfer_to_technician.
- When the conversation is finished, say goodbye and call end_call in the same reply. Nothing you write after end_call or transfer_to_technician is spoken.`;

export const TOOLS = {
  submit_lead: {
    name: 'submit_lead',
    description: 'Save the caller as a lead for a technician visit or call-back. Call once, when the caller has agreed to a visit or a call-back.',
    input_schema: {
      type: 'object',
      properties: {
        caller_name: { type: 'string' },
        caller_phone: { type: 'string', description: 'Best number to call back; leave empty to use the number they are calling from.' },
        service_address: { type: 'string', description: 'Street address and city of the appliance.' },
        appliance_type: { type: 'string', description: 'For example refrigerator, washer, dryer, dishwasher, oven.' },
        appliance_brand: { type: 'string' },
        reported_problem: { type: 'string', description: 'The problem in the caller\'s own words.' },
        agent_summary: { type: 'string', description: 'One or two sentences for the technician: symptoms, what was tried, your first recommendation.' },
        consent_to_callback: { type: 'boolean', description: 'True when the caller agreed to be called back.' },
        fee_accepted: { type: 'boolean', description: 'True only when the caller clearly agreed to the $95 service call fee. Required for a visit.' },
        preferred_callback_time: { type: 'string' },
        outcome: { type: 'string', enum: ['visit', 'callback'] },
      },
      required: ['reported_problem', 'agent_summary', 'consent_to_callback', 'fee_accepted', 'outcome'],
    },
  },
  transfer_to_technician: {
    name: 'transfer_to_technician',
    description: 'Connect the caller to a technician by phone. Use when they ask for a person or you cannot understand the request.',
    input_schema: { type: 'object', properties: { reason: { type: 'string' } }, required: ['reason'] },
  },
  end_call: {
    name: 'end_call',
    description: 'Hang up after you have said goodbye.',
    input_schema: { type: 'object', properties: {} },
  },
  followup_result: {
    name: 'followup_result',
    description: 'Record the answer on this follow-up call: does the caller still need help? Call once before ending the call.',
    input_schema: {
      type: 'object',
      properties: { still_needs_help: { type: 'boolean' }, notes: { type: 'string' } },
      required: ['still_needs_help'],
    },
  },
};

/** Static prompt first (cacheable), call facts after it. */
export function systemPrompt(context) {
  const facts = [
    `Mode: ${context.mode === 'followup' ? 'FOLLOW-UP call placed by the system to a caller who is waiting for a call-back' : 'incoming call'}.`,
    `A technician is available now: ${context.technicianAvailable ? 'yes' : 'no'}.`,
    `The caller's phone number is known: ${context.callerPhoneKnown ? 'yes, you do not need to ask for it unless they want another number' : 'no'}.`,
    context.customer ? `Returning customer on file: ${context.customer.name}${context.customer.address ? `, ${context.customer.address}` : ''}. Confirm rather than ask again.` : null,
    context.missedTransfer ? 'The caller was just transferred to a technician who did not pick up, and has heard a short apology. Do not offer another transfer unless they insist; take the request for a call-back.' : null,
    context.mode === 'followup' && context.lead ? `This follow-up is about: ${[context.lead.name, context.lead.applianceType].filter(Boolean).join(', ')}. The greeting with the apology was already spoken.` : null,
  ].filter(Boolean).join('\n');
  return [
    { type: 'text', text: PROMPT + TOOLING, cache_control: { type: 'ephemeral' } },
    { type: 'text', text: `CALL FACTS\n${facts}` },
  ];
}

export function toolsFor(mode) {
  return mode === 'followup'
    ? [TOOLS.followup_result, TOOLS.transfer_to_technician, TOOLS.end_call]
    : [TOOLS.submit_lead, TOOLS.transfer_to_technician, TOOLS.end_call];
}

/** One model turn, streaming text as it is generated. */
export function createClaude(config) {
  const client = new Anthropic();
  return {
    async respond({ system, tools, messages, onText, signal }) {
      const stream = client.messages.stream({
        model: config.claude.model,
        max_tokens: 1024,
        system,
        tools,
        messages,
        output_config: { effort: config.claude.effort },
      }, { signal });
      stream.on('text', onText);
      return stream.finalMessage();
    },
  };
}
