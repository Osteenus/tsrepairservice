# TS Repair CRM — spec for the first version (MVP)

Context: Tech Solutions Repair Service Inc. (Moorpark, CA), one-man appliance repair business (owner: Oleg Ustinov, BHGS registration A 50537). This CRM is built **inside the existing Laravel site** (tsrepairservice.com). It is used mainly **from a phone** by the owner, in the field. Start small, then add features as requirements appear.

## Existing stack (do not replace)
- Laravel 12, PHP 8.2+, Jetstream 5 (Inertia + Vue 3), Sanctum, Tailwind 4, Vite 7. The public site renders on the server (Inertia SSR, `deploy/tsrepair-ssr.service`).
- Hosting: Hostinger VPS KVM 1 (Ubuntu 24.04, 1 vCPU, 4 GB RAM, nginx, systemd). Site path: `/var/www/html/tsrepairservice`.
- Already in the repo: `repair_requests` (service requests from the public contact form), users with Jetstream 2FA columns, `config/repair.php` (phone, registration, services, areas).
- Do not open or modify `.env`. Do not change the public pages, SEO metadata, sitemap or design unless a task below says so.

## Rules for the work
- Mobile first: every CRM screen must be comfortable on a phone (large tap targets, one-column forms).
- CRM lives under `/crm/*`, requires login, is `noindex` (headers + robots), and is excluded from the sitemap.
- No public registration. Jetstream registration must be disabled; users are created by an artisan command or seeder.
- **2FA required** for every CRM user (Jetstream/Fortify TOTP authenticator app + recovery codes). Force setup on first login. SMS 2FA is NOT in the MVP (it needs a paid SMS provider such as Twilio and is weaker against SIM swap); keep the design open so it can be added later.
- Never hard-delete customers, estimates, invoices or photos (soft deletes only). BHGS requires keeping invoices at least 3 years.
- Money: store as integer cents. Dates in America/Los_Angeles.
- Write feature tests for the money math, document numbering, the BHGS-required fields and the auth/2FA gate. `php artisan test` and `npm run build` must pass.
- Work in small commits on a branch; do not push to the server, do not run destructive commands on production.

## Data model (suggested; adjust if you find a cleaner design)
- `customers`: name, phone, email, address_line1, city, state, zip, notes. Phone search must work.
- `appliances`: customer_id, type, brand, model, serial, notes.
- `jobs` (work orders): customer_id, appliance_id, repair_request_id (nullable, link to the public contact form request), reported_problem, findings, status (`new`, `estimate_sent`, `approved`, `in_progress`, `completed`, `paid`, `cancelled`), scheduled_at, service_address (default: customer address).
- `documents`: job_id, type (`estimate` | `invoice`), number, issued_on, valid_until (estimates), status (`draft`, `issued`, `approved`, `void`), totals in cents, plus snapshot fields needed to reproduce the PDF later (customer, appliance, company details at issue time).
- `document_items`: document_id, kind (`service_call`, `labor`, `part`), description, part_number, qty, unit_cents, is_warranty (bool), is_used_or_exchanged (bool).
- `signatures`: document_id, role (`customer`, `technician`), signature_png path, signed_name, signed_at, ip/user agent. Customer signs on the phone screen (canvas).
- `photos`: job_id, path (private disk, not public), caption, kind (`serial_label`, `before`, `after`, `other`).
- `payments`: job_id/document_id, amount_cents, method (cash, check, zelle, card, other), paid_on, note.
- `settings` or config: company details, service call fee, guarantee text, estimate validity, tax settings.
- Numbering: estimates `TS-E-YYYY-0001`, invoices `TS-YYYY-0001`, gapless per year, generated inside a DB transaction.

## MVP screens (Inertia + Vue, under /crm)
1. **Dashboard**: today's and upcoming jobs, new service requests from the website, unpaid invoices.
2. **Requests** (from the existing `repair_requests`): list, open, "Create customer + job from this request".
3. **Customers**: list with search by name or phone, detail page with appliances and job history, create/edit.
4. **Job**: all info, status buttons, photos (camera upload from the phone), notes, and buttons "Create estimate", "Create invoice".
5. **Estimate builder** (mobile form): items with live totals, Service call fee prefilled at $95.00. "Present to customer" screen shows the estimate and a signature pad; on signature the estimate becomes `approved`, the job becomes `approved`. "Revised estimate" is a new version with customer consent recorded (see BHGS rules).
6. **Invoice builder**: created from an approved estimate (items copied, editable), marks each part warranty/non-warranty and used/exchanged, includes "invoice completed by" and "repair performed by" fields, and records payments.
7. **PDF**: generate estimate and invoice PDFs using the HTML templates in `docs/crm/pdf-templates/` (see below). Download, and "send by email" to the customer (mail config already exists; queue the send).

## Business rules (BHGS, California, Electronic and Appliance Repair)
- A **written estimate is required before any repair work**, with the customer's authorization (signature). Estimate shows all costs (service call, labor, parts), **no minimum/maximum or ranges**, taxes not included. Estimate valid **5 business days**.
- **Service call / diagnostic fee: $95.00**, shown in the estimate in writing; it is **included in the estimate total** (not extra). If the customer declines the repair, only the $95 is due.
- Charges may not exceed the estimate without the customer's prior consent. Block issuing an invoice whose total is above the approved estimate unless a revised estimate with recorded consent exists (written signature, or oral consent with date, time, who authorized, phone number, description of added work and new total).
- Invoice must contain: business name, address, phone; the registration number labeled as such (A 50537); invoice date; customer name and address; appliance make, model, serial number; summary of the customer's description of the problem; itemized parts (warranty status or charge), itemized labor, other charges; disclosure of any used or exchanged parts; signature or employee number of the person who completed the invoice and of the person who performed the repair, labeled as such; total; **the printed BHGS statement in prominent type** (already in the templates, copied verbatim from 16 CCR § 2723). One copy to the customer, one kept by us (3 years minimum).
- Guarantee (standard): labor 30 days, parts 90 days, from the service date (text in the templates).
- Replaced parts are returned to the customer (the invoice has a line for initials).
- **Sales tax is OFF** until the business gets a CDTFA seller's permit. Build it as a setting: when enabled, tax applies to parts only (not repair labor); the rate is configurable per job; show tax lines on documents only when enabled. Do not add tax calculation to the UI until the setting is on.
- Company address on documents: `51 Majestic Ct, Apt 904, Moorpark, CA 93021` (matches BHGS registration; it is home address, so it appears only on customer documents and never on public pages). Phone: (805) 991-2874 (`config/repair.php`).

## PDF templates
- Reference templates (Jinja2 + Chromium via Playwright, already tested): `docs/crm/pdf-templates/invoice.html`, `estimate.html`, `logo.png`, the generator scripts `generate_invoice.py` / `generate_estimate.py`, sample JSON and sample PDFs. Note: the python script loads `template.html`; in the repo the invoice template was renamed to `invoice.html`.
- Port them to Blade (or another approach that works in Laravel) **keeping the design and the exact wording**: Letter size, one page each, logo top-left, red/black style, signature lines, the BHGS box.
- Preferred renderer: `spatie/browsershot` with headless Chromium on the VPS (templates use CSS grid/flex, so dompdf is not suitable). Document the server setup (Chromium + Node/Puppeteer, 4 GB RAM) and add a safe fallback/error message if rendering fails.
- Estimate and invoice must each still fit on one page with typical content; verify with the sample data.

## Security and deployment notes
- Customer data is private: photos and signatures on a private disk, served only through authenticated routes.
- Add rate limiting on login, strong password rules, session timeout. Log in only over HTTPS.
- Backups: the VPS has weekly Hostinger backups only; document a nightly `mysqldump`/sqlite backup plus storage backup (the owner may also buy Hostinger daily backups, $3/mo). Hostinger firewall currently has 0 rules: document the recommended ufw rules (22, 80, 443).
- Keep the deployment instructions in `docs/crm/deploy.md`.

## Out of scope for the MVP (later)
Online payments (Stripe), SMS notifications and SMS 2FA (Twilio), Google Calendar sync, review requests after a job, parts catalog, reports/analytics, multiple technicians, customer portal, tax filing support, import of the existing Google Sheet of work orders.

## Suggested order of work
1. Auth hardening (no registration, forced 2FA, `/crm` shell, noindex), customers.
2. Jobs and requests, link from the contact form.
3. Estimate builder, numbering, signature pad, estimate PDF.
4. Invoice builder, payments, invoice PDF, email send.
5. Photos, dashboard, tests, deploy notes.

Before coding, read `docs/crm/pdf-templates/*`, `config/repair.php`, `routes/web.php`, the `repair_requests` migrations and `RepairRequestController`, then post a short implementation plan and wait for approval.
