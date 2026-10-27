# voice-service

AI phone intake for Tech Solutions Repair Service. Twilio ConversationRelay does speech-to-text and
text-to-speech; this service sends the caller's words to Claude (`claude-haiku-5-5`) and speaks the
replies back. It stores nothing except the call in progress: leads go to the Laravel CRM over HTTP.

```
caller ──> Twilio (805) 334-8375 ──POST /voice──> voice-service ──> TwiML: greeting, recording notice, <ConversationRelay>
                │                                     │
                └──── wss://…/relay (text) ──────────>│──> Claude API
                                                      └──> Laravel /api/voice/* (leads, customer lookup, hours)
Laravel scheduler (voice:follow-up) ──POST /followup/{id}──> voice-service ──> one outbound call
```

## Endpoints

| Path | Caller | Purpose |
|---|---|---|
| `POST /voice` | Twilio (signed) | Incoming call. Max 3 calls per number per hour. Asks Laravel which mode is on (CRM → Phone: AI, simple transfer, or A/B split; cached 30 s) and records the variant. |
| `WS /relay` | Twilio (signed handshake) | Conversation. 5-minute limit per call. |
| `POST /voice/direct-dial` | Twilio (signed) | Variant B (simple transfer): no answer → the AI assistant takes the request. |
| `POST /voice/after` | Twilio (signed) | Session ended: transfer to `OWNER_PHONE`, or hang up. If the assistant fails, the caller is transferred. |
| `POST /voice/after-dial` | Twilio (signed) | Transfer result, reported to Laravel. |
| `POST /followup/:leadId` | Laravel (Bearer `VOICE_API_KEY`) | One follow-up call per lead. |
| `GET /health` | anyone | `{"ok":true}` |

Leads Laravel cannot accept right now are kept in `outbox/` and retried every minute (Laravel
deduplicates by `call_sid`). Rejected payloads land in `outbox/rejected/` for a person to check.
Logs are one JSON line per event with call SID, duration and token usage; no numbers, transcripts or secrets.

The agent's instructions are in `prompts/agent.md` (loaded at start; restart after editing).

## Local

```
cp .env.example .env   # fill in
npm install
npm test
npm start
```

## Server setup (once)

1. **DNS** (Hostinger): A record `voice` → `31.220.60.234`.
2. **App**: on the VPS the code arrives with `git pull` of the main repo.
   ```
   cd /var/www/html/tsrepairservice/voice-service
   npm ci --omit=dev
   cp .env.example .env && nano .env && chmod 600 .env
   ```
3. **nginx** `/etc/nginx/sites-available/voice.tsrepairservice.com`, then enable it and run
   `sudo certbot --nginx -d voice.tsrepairservice.com`:
   ```nginx
   server {
       server_name voice.tsrepairservice.com;
       location / {
           proxy_pass http://127.0.0.1:3001;
           proxy_http_version 1.1;
           proxy_set_header Upgrade $http_upgrade;
           proxy_set_header Connection "upgrade";
           proxy_set_header Host $host;
           proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
           proxy_read_timeout 600s;
       }
       listen 80;
   }
   ```
4. **systemd** `/etc/systemd/system/tsrepair-voice.service` (Node 20+: use the nvm Node 24, not the system Node 18;
   check the path with `which node` as osteen):
   ```ini
   [Unit]
   Description=TS Repair AI voice service
   After=network.target

   [Service]
   User=osteen
   WorkingDirectory=/var/www/html/tsrepairservice/voice-service
   ExecStart=/home/osteen/.nvm/versions/node/v24.6.0/bin/node --env-file=.env src/server.js
   Restart=always
   Environment=NODE_ENV=production

   [Install]
   WantedBy=multi-user.target
   ```
   `sudo systemctl daemon-reload && sudo systemctl enable --now tsrepair-voice`
5. **Laravel** `.env` (then `php artisan config:cache`):
   ```
   VOICE_API_KEY=<same as voice-service>
   VOICE_SERVICE_URL=http://127.0.0.1:3001
   ```
   Generate the key once with `openssl rand -hex 32`.
6. **Laravel scheduler** — not set up on the server yet; the follow-up calls and overdue alerts need it.
   `sudo crontab -u www-data -e`:
   ```
   * * * * * cd /var/www/html/tsrepairservice && php artisan schedule:run >> /dev/null 2>&1
   ```
7. **Twilio**:
   - Console → Voice → Settings: accept the AI/ConversationRelay terms if asked.
   - Phone number (805) 334-8375 → Voice configuration:
     - A call comes in: Webhook `https://voice.tsrepairservice.com/voice`, HTTP POST
     - Primary handler fails: `https://tsrepairservice.com/api/twilio/voice` (the simple Laravel bot keeps taking requests if this service is down)
     - Call status changes: `https://tsrepairservice.com/api/twilio/voice/status` (call length for the CRM statistics)
   - Call recording is off (`RECORD_CALLS=false`). With `true`, recordings appear under Monitor → Call recordings.

## Release

```
cd /var/www/html/tsrepairservice && git pull
cd voice-service && npm ci --omit=dev && sudo systemctl restart tsrepair-voice
journalctl -u tsrepair-voice -f    # watch a test call
```
