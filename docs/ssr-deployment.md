# Server-rendered public pages

Public pages render using the same Vue components on the server and browser. Their content, title, description, canonical and existing JSON-LD are present in the initial HTML. No user-agent-specific content is served. The browser hydrates this HTML; client rendering remains a fallback if SSR is unavailable.

## Build and run

- `npm run build:ssr` builds browser assets and `bootstrap/ssr/ssr.js` plus its chunks.
- `php artisan inertia:start-ssr` (or `node bootstrap/ssr/ssr.js`) starts the renderer.
- The renderer binds only to `127.0.0.1:13714`, using Inertia's `/render` and `/health` protocol.
- Do not import browser-only bootstrap code into `resources/js/ssr.js`.
- Generated SSR files are not committed. Rebuild both bundles for every release and restart the renderer.

Production uses `deploy/tsrepair-ssr.service` as an osteen user systemd service, with linger enabled so it runs after logout and reboot. Update the Node binary path in the unit when upgrading Node.

```
systemctl --user restart tsrepair-ssr
systemctl --user status tsrepair-ssr
journalctl --user -u tsrepair-ssr -n 30
```

Keep the old public build and SSR bundle before releases. Copy new hashed browser assets without deleting old ones, switch the manifest, replace the SSR bundle and restart the service. To roll back, restore both previous bundles and restart. No database migration is required for SSR.

## Checks

`python3 scripts/check-seo.py https://tsrepairservice.com` checks every sitemap page without JavaScript: HTTP status, H1, unique title, description, canonical and JSON-LD syntax. Use the local URL to check before deployment. Browser checks must additionally cover hydration, mobile navigation, AOS, FAQs and form state. Form regression tests use fake mail; never send production test requests implicitly.

Google Search Console URL Inspection and Core Web Vitals still require separate checks. SSR and valid JSON-LD do not guarantee ranking, indexing or eligibility for rich results. The generic Service schema describes the business service; it does not claim a Google rich-result feature. No unverified address, ratings or hours were added.

References:
- https://developers.google.com/search/docs/crawling-indexing/javascript/javascript-seo-basics
- https://inertiajs.com/docs/v2/advanced/server-side-rendering
