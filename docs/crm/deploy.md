# CRM deployment (Hostinger VPS)

Server: Ubuntu 24.04, nginx, PHP 8.4-FPM running as `www-data`, PostgreSQL 16, app in
`/var/www/html/tsrepairservice` (checkout owned by `osteen`). The public site release steps in
`docs/ssr-deployment.md` still apply; this file adds what the CRM (`/crm`) needs.

Checked on the server on 2026-10-14 (steps 2 and 3 done on 2026-10-15): `upload_max_filesize=2M`, `post_max_size=8M`, nginx has no
`client_max_body_size` (default 1 MB), no system Node or Chromium, no queue worker, no cron jobs.
The one-time steps below fix each of these.

## One-time setup

### 1. Environment (`.env`)

Add:

```
CRM_PDF_NODE_BINARY=/usr/bin/node
CRM_PDF_NO_SANDBOX=true
```

- PHP runs as `www-data`, which cannot read `/home/osteen/.nvm`, so PDFs use the system Node (step 3).
- Ubuntu 24.04 blocks the Chromium sandbox for unprivileged users through AppArmor. The CRM renders
  only its own HTML (no outside URLs), so running without the sandbox is acceptable here.
- `SESSION_LIFETIME=120` is already set: a CRM session ends after 2 idle hours ("remember me" is
  removed from the login form). Session cookies are `Secure` in production by default.

Security follow-up: the production `.env` (APP_KEY, database and mail passwords) was pasted into a
chat on 2026-10-14. Change the mail password in Hostinger and the database password (and use a
dedicated database user instead of `postgres`, see "Database user" below). Rotating `APP_KEY` logs
everyone out and makes encrypted values unreadable, including users' 2FA secrets. If you rotate it,
set up 2FA again afterwards.

### 2. Upload limits for job photos

Phone photos are 3–8 MB and up to 10 can be uploaded at once.

`/etc/php/8.4/fpm/conf.d/99-tsrepair.ini`:

```
upload_max_filesize = 20M
post_max_size = 100M
max_file_uploads = 20
```

In the site's nginx `server { }` block, add `client_max_body_size 100M;`.

```
sudo systemctl reload php8.4-fpm
sudo nginx -t && sudo systemctl reload nginx
```

### 3. Node and Chromium for PDFs (spatie/browsershot + Puppeteer)

```
sudo apt update
sudo apt install -y nodejs fonts-liberation fonts-inter \
  libasound2t64 libatk1.0-0t64 libatk-bridge2.0-0t64 libcups2t64 libdrm2 libgbm1 \
  libgtk-3-0t64 libnss3 libxcomposite1 libxdamage1 libxrandr2 libxkbcommon0 libpango-1.0-0
```

`npm ci` (run as `osteen`, see the release steps) downloads Chrome into `.cache/puppeteer/` inside the
project (`.puppeteerrc.cjs`). The app finds it there, so the path does not have to be configured.
If Chrome lives somewhere else, set `CRM_PDF_CHROME_PATH`.

Check the setup as the PHP user:

```
cd /var/www/html/tsrepairservice
sudo -u www-data php artisan crm:pdf-check
```

It prints `OK: rendered a … byte PDF` or the Chromium error. Memory: one render needs about
200–300 MB for a few seconds. The 4 GB VPS handles this, but avoid generating many PDFs at once.

If rendering fails in the app, the user sees "The PDF could not be created…" and the error is in
`storage/logs/laravel.log`. Estimates and invoices are still saved; only the PDF is missing.

### 4. Queue worker (customer emails)

`QUEUE_CONNECTION=database`, so emails with PDFs are sent by a worker:

```
sudo cp deploy/tsrepair-queue.service /etc/systemd/system/
sudo systemctl daemon-reload
sudo systemctl enable --now tsrepair-queue
systemctl status tsrepair-queue
journalctl -u tsrepair-queue -n 50
```

The worker runs as `www-data`, the same user as PHP-FPM, so it can read signatures and write logs.
Failed sends: `php artisan queue:failed`; retry them with `php artisan queue:retry all`.

### 5. Database and first user

```
php artisan migrate --force
php artisan crm:user you@example.com --name="Oleg Ustinov"
```

Passwords need at least 12 characters with letters and numbers. Public registration is off. At the
first login the CRM opens Account & security: enable two-factor authentication, scan the QR code
with an authenticator app (Google Authenticator, Authy, 1Password…), confirm with a code and save
the recovery codes offline. Without confirmed 2FA no `/crm` page opens. Lost phone: log in with a
recovery code. Lost codes too: `php artisan crm:user you@example.com --reset` sets a new password,
but 2FA must be cleared in the database (`two_factor_secret`, `two_factor_recovery_codes`,
`two_factor_confirmed_at` set to NULL for the user).

### 6. Backups

Hostinger makes weekly VPS backups only; that can lose up to a week of invoices and signatures.
BHGS requires keeping invoices at least 3 years.

```
sudo cp deploy/crm-backup.sh /usr/local/bin/tsrepair-backup
sudo chmod 700 /usr/local/bin/tsrepair-backup
sudo crontab -e     # add: 30 3 * * * /usr/local/bin/tsrepair-backup >> /var/log/tsrepair-backup.log 2>&1
sudo /usr/local/bin/tsrepair-backup   # first run by hand
```

It keeps 30 days of nightly `pg_dump` files and archives of `storage/app/private` (signatures and
photos) in `/var/backups/tsrepair`. Backups on the same server do not survive losing the server.
At least weekly, copy them elsewhere (for example `rclone` to Google Drive, or `scp` to your Mac).
You can also buy Hostinger daily backups ($3/mo).

Restore test (do it once): `pg_restore --clean --dbname=<test database> db_<date>.dump`.

### 7. Firewall

The Hostinger panel firewall currently has no rules. Allow only SSH, HTTP and HTTPS:

```
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
sudo ufw status verbose
```

Keep the current SSH session open while enabling ufw and test a new SSH login before closing it.
PostgreSQL was found listening on all addresses (`listen_addresses = '*'`) with port 5432 reachable
from the internet (2026-10-15). ufw closes the port; also set `listen_addresses = 'localhost'` in
`/etc/postgresql/16/main/postgresql.conf` and run `sudo systemctl restart postgresql`, so the database
stays private even if the firewall is ever disabled. Add the same three rules in the Hostinger
panel firewall as a second layer.

### Database user (recommended)

The app connects as `postgres` (superuser). Use a dedicated owner instead. Do this right after a
backup (step 6), at a quiet time:

```
sudo -u postgres psql -c "CREATE ROLE tsrepair LOGIN PASSWORD '<new strong password>';"
sudo -u postgres psql -c "ALTER DATABASE tsrepairservice OWNER TO tsrepair;"
sudo -u postgres psql -d tsrepairservice -c "ALTER SCHEMA public OWNER TO tsrepair;"
sudo -u postgres psql -d tsrepairservice -c "DO \$\$ DECLARE r record; BEGIN
  FOR r IN SELECT tablename FROM pg_tables WHERE schemaname = 'public' LOOP
    EXECUTE format('ALTER TABLE public.%I OWNER TO tsrepair', r.tablename);
  END LOOP; END \$\$;"
```

(Changing a table's owner also moves its id sequences.) Then set `DB_USERNAME=tsrepair` and the new
`DB_PASSWORD` in `.env`, run `php artisan config:clear`, and check the site, `/crm` and
`php artisan migrate:status` right away. To undo, put the `postgres` credentials back in `.env`.

## Every release

On the VPS, as `osteen`, in `/var/www/html/tsrepairservice`:

```
git pull
composer install --no-dev --optimize-autoloader
npm ci                     # also downloads Chrome for PDFs on first run or Puppeteer upgrade
npm run build:ssr
php artisan migrate --force
php artisan optimize:clear
systemctl --user restart tsrepair-ssr
sudo systemctl restart tsrepair-queue     # or: php artisan queue:restart
```

Then open `/crm`, open a job and download any estimate PDF.

## What the CRM stores and where

| Data | Where | Deleted? |
| --- | --- | --- |
| Customers, appliances, jobs, estimates, invoices, payments, photos | PostgreSQL | Soft delete only (`deleted_at`) |
| Customer signatures (PNG) | `storage/app/private/signatures/{document}/` | Never |
| Job photos | `storage/app/private/photos/{job}/` | File kept when a photo is removed |
| Customer photos from website requests | `storage/app/private/requests/{request}/` | Linked to the job when the request becomes one |
| Issued document details | `documents.snapshot` (JSON) | Frozen at issue; PDFs are re-rendered from it |

Private files are served only through signed-in routes (`/crm/photos/{id}`, `/crm/signatures/{id}`),
never from `public/`. `/crm`, `/login` and `/user/*` send `X-Robots-Tag: noindex, nofollow` and
`/crm` is disallowed in `robots.txt` and absent from the sitemap.

## Business settings

`config/crm.php`: company details printed on documents (including the home address, which never
appears on public pages), the $95 service call, the 5-business-day estimate validity, the guarantee
text and payment methods. Changing them affects only documents issued afterwards; issued documents
keep their snapshot.

Sales tax: `tax.enabled` is `false` until the CDTFA seller's permit. When it is turned on, new
invoices charge `tax.default_rate` (7.25% Ventura County base; check the city's district rate) on
parts only and print a tax line. Also add the permit number to the company details then.
