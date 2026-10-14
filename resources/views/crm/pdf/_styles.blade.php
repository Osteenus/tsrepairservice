<style>
  @page { size: Letter; margin: 0; }
  :root { --red: #c8000a; --ink: #151515; --muted: #6b6b6b; --line: #e3e3e3; --soft: #f6f6f6; }
  * { box-sizing: border-box; }
  html, body { margin: 0; padding: 0; }
  body { font-family: Inter, "Helvetica Neue", Arial, sans-serif; color: var(--ink); font-size: 10pt; line-height: 1.4; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
  .page { width: 8.5in; height: 11in; padding: 0.4in 0.55in 0.3in; position: relative; display: flex; flex-direction: column; }
  .topbar { position: absolute; left: 0; right: 0; top: 0; height: 0.14in; background: linear-gradient(90deg, var(--red) 0 50%, var(--ink) 50% 100%); }

  header { display: flex; justify-content: space-between; align-items: center; margin-top: 0.1in; }
  .logo { height: 0.95in; width: auto; display: block; margin-left: -0.04in; }
  .company { text-align: right; font-size: 9pt; color: var(--muted); line-height: 1.5; }
  .company strong { color: var(--ink); font-size: 10pt; }

  .title-row { display: flex; justify-content: space-between; align-items: flex-end; margin: 0.16in 0 0.12in; border-bottom: 2px solid var(--ink); padding-bottom: 0.06in; }
  h1 { margin: 0; font-size: 22pt; letter-spacing: 0.08em; font-weight: 800; }
  h1 span { color: var(--red); }
  .meta { display: flex; gap: 0.3in; text-align: right; }
  .meta div small { display: block; font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); }
  .meta div b { font-size: 10.5pt; }

  .cols { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.2in; margin-bottom: 0.14in; }
  .box h3 { margin: 0 0 0.05in; font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.1em; color: var(--red); }
  .box p { margin: 0; }
  .box .name { font-weight: 700; }
  .box .muted { color: var(--muted); }

  table.items { width: 100%; border-collapse: collapse; }
  table.items thead th { background: var(--ink); color: #fff; font-size: 8pt; text-transform: uppercase; letter-spacing: 0.08em; text-align: left; padding: 0.07in 0.1in; }
  table.items th.num, table.items td.num { text-align: right; white-space: nowrap; }
  table.items td { padding: 0.05in 0.1in; border-bottom: 1px solid var(--line); vertical-align: top; }
  table.items tr.group td { background: var(--soft); font-weight: 700; font-size: 8pt; text-transform: uppercase; letter-spacing: 0.08em; color: var(--red); padding: 0.05in 0.1in; }
  table.items td .sub { display: block; color: var(--muted); font-size: 8.5pt; }

  .bottom { display: grid; grid-template-columns: 1fr 2.9in; gap: 0.3in; margin-top: 0.14in; }
  .notes h3 { margin: 0 0 0.05in; font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.1em; color: var(--red); }
  .notes p { margin: 0 0 0.07in; font-size: 8.5pt; color: #333; }
  .totals table { width: 100%; border-collapse: collapse; }
  .totals td { padding: 0.04in 0.1in; }
  .totals td:last-child { text-align: right; white-space: nowrap; }
  .totals tr.grand td { background: var(--red); color: #fff; font-weight: 800; font-size: 12pt; padding: 0.09in 0.1in; }
  .totals tr.balance td { font-weight: 700; border-top: 2px solid var(--ink); }
  .status { display: inline-block; margin-top: 0.1in; padding: 0.03in 0.14in; border: 2px solid; border-radius: 4px; font-weight: 800; letter-spacing: 0.12em; font-size: 11pt; transform: rotate(-4deg); }
  .status.paid { color: #1a7f37; border-color: #1a7f37; }
  .status.due { color: var(--red); border-color: var(--red); }

  .sign { display: flex; gap: 0.3in; margin-top: 0.2in; }
  .sign div { flex: 1; border-top: 1px solid var(--ink); padding-top: 0.04in; font-size: 8pt; color: var(--muted); }

  .return { margin: 0.08in 0 0.06in; font-size: 8.5pt; color: #333; }
  .legal { border: 1.5px solid var(--ink); padding: 0.05in 0.1in; font-size: 7.8pt; font-weight: 700; line-height: 1.35; }
  footer { margin-top: auto; border-top: 1px solid var(--line); padding-top: 0.06in; text-align: center; font-size: 8.5pt; color: var(--muted); }
  footer b { color: var(--ink); }
  .sample { position: absolute; top: 4.2in; left: 1.2in; font-size: 90pt; font-weight: 900; color: rgba(200,0,10,0.07); transform: rotate(-28deg); pointer-events: none; letter-spacing: 0.1em; }
  /* CRM additions: values written above the signature lines, and the revision record. */
  .sign .val { height: 0.28in; margin-top: -0.3in; margin-bottom: 0.02in; color: var(--ink); font-size: 9pt; display: flex; align-items: flex-end; gap: 0.08in; white-space: nowrap; overflow: hidden; }
  .sign .val img { height: 0.28in; width: auto; }
  .sign.filled { margin-top: 0.34in; }
</style>
