// One JSON line per event. Never pass phone numbers, transcripts or secrets here.
export function log(event, fields = {}) {
  if (process.env.NODE_ENV === 'test') return;
  console.log(JSON.stringify({ time: new Date().toISOString(), event, ...fields }));
}
