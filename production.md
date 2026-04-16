# Production Readiness and cPanel Deployment Guide

Last reviewed: 2026-04-15

## Short answer

Yes, this app can be deployed on cPanel.

It is not "production ready" in the sense of "upload it exactly as-is and go live safely" yet, but it is close. The codebase is deployable and the application behavior is covered well enough to move forward once you finish the production setup items below.

## What I verified

- Laravel `12.55.1`
- PHP `8.5.5` locally during verification
- Test suite status: `109 passed`
- Public entrypoint exists: `public/index.php`
- Apache rewrite file exists: `public/.htaccess`
- Built frontend assets already exist in `public/build`
- CI already runs tests and a frontend build in `.github/workflows/tests.yml`

## Current state: green, yellow, red

### Green

- Core Laravel structure is correct for deployment.
- Full automated test suite passes.
- Frontend build artifacts already exist, which helps on cPanel if Node is unavailable on the server.
- The app already has MySQL/MariaDB config support, so it is not locked to SQLite.
- Authentication, permissions, invoices, payments, reports, and business profile flows are covered by the existing app structure and tests.

### Yellow

- The current environment is still local-style:
  - `APP_ENV=local`
  - `APP_DEBUG=true`
  - `DB_CONNECTION=sqlite`
  - `MAIL_MAILER=log`
- Laravel caches are not enabled yet:
  - config cache: not cached
  - route cache: not cached
- `public/storage` is not linked right now.
- Business profile logos and signatures are stored on the `public` disk, so the storage link is required before go-live.
- Test runtime is long enough to matter operationally: about `541.59s` locally. This is not a blocker, but it means release verification is not instant.

### Red before production

- Do not go live with the current `.env` values.
- Do not go live without a real SMTP mailer if you want password reset or email verification links to work.
- Do not go live without linking `public/storage`, or uploaded logo/signature files will break.
- Do not go live on SQLite unless that is an intentional single-tenant, low-write setup. For cPanel production, MySQL/MariaDB is the safer default.
- Do not run the full production seed flow unchanged, because the main seeder creates sample users and sample business data.

## Practical verdict

My recommendation is:

- Code readiness: yes, good enough to prepare for production
- Operational readiness right now: not yet
- cPanel compatibility: yes, with the normal Laravel shared-hosting setup

## cPanel deployment fit

This project is a good cPanel candidate because:

- it uses Laravel + Blade rather than a separate SPA deployment target
- it already has Apache rewrite support
- it can run with prebuilt assets
- it does not currently appear to depend on a long-running queue worker for core features
- it does not define an app scheduler that must already be running to keep the app usable

Important caveat:

- invoice overdue status updates are request-driven right now, not scheduler-driven, so some status changes happen when invoice/report pages are visited rather than from a background cron

## Production requirements

Your cPanel host should provide:

- PHP `8.2+` minimum
- MySQL or MariaDB
- required PHP extensions typically needed by Laravel:
  - `bcmath`
  - `ctype`
  - `fileinfo`
  - `json`
  - `mbstring`
  - `openssl`
  - `pdo`
  - `pdo_mysql`
  - `tokenizer`
  - `xml`
- SSH access is strongly preferred
- Composer on the server is preferred

Nice to have:

- ability to change the document root to the app's `public` directory
- cron access

## Recommended production `.env`

Use production values similar to this:

```env
APP_NAME="Suits Invoice App"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_cpanel_db
DB_USERNAME=your_cpanel_user
DB_PASSWORD=your_strong_password

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true

CACHE_STORE=database
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-smtp-user
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"
```

Notes:

- `QUEUE_CONNECTION=sync` is the safest shared-hosting default unless you intentionally set up a database queue worker.
- If you later add real background jobs, switch back to `database` and add a cron-driven worker strategy.

## Best cPanel deployment approach

### Option A: best setup

- keep the Laravel project outside `public_html`
- point the domain or subdomain document root to the project's `public` directory

This is the cleanest and safest setup.

### Option B: fallback setup

If cPanel cannot point the document root to `public`:

- place the Laravel app above `public_html`
- copy the contents of the app's `public` folder into `public_html`
- update `public_html/index.php` so its `../vendor` and `../bootstrap` paths point to the real app location

Only use this when document-root mapping is not available.

## Deployment steps on cPanel

### 1. Upload the project

Upload the repository to the server.

If the server has no Node:

- keep the already-built `public/build` directory

If the server has no Composer:

- you may need to upload the `vendor` directory from a clean local production build

### 2. Create the database

In cPanel:

- create a MySQL database
- create a DB user
- assign the user to the database with all privileges

### 3. Configure environment

- create a production `.env`
- set `APP_KEY` with `php artisan key:generate`
- make sure `APP_URL` uses your real HTTPS domain

### 4. Install dependencies

Preferred:

```bash
composer install --no-dev --optimize-autoloader
```

### 5. Run database setup

```bash
php artisan migrate --force
```

Do not run the full `php artisan db:seed --force` blindly on production unless you intentionally want sample data and default users.

This repository's main seeder creates example records and default accounts.

Safer production approach:

- run migrations only
- seed only the minimum required permissions/roles
- create your real admin user manually or through a controlled setup step

Example:

```bash
php artisan db:seed --class=RoleAndPermissionSeeder --force
```

### 6. Link storage

```bash
php artisan storage:link
```

This is required for business profile images and signatures.

### 7. Cache Laravel for production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 8. Verify file permissions

Make sure Laravel can write to:

- `storage`
- `bootstrap/cache`

### 9. Smoke test after deploy

Verify:

- login works
- dashboard loads
- invoice create/edit/show works
- payment creation works
- report pages load
- uploaded business logo/signature display correctly
- password reset email can be sent

## cPanel-specific advice

### If the host has SSH and Composer

You are in good shape. This app should deploy normally.

### If the host has no Node

That is fine as long as you deploy the built assets already present in `public/build`.

### If the host has no Composer

Deployment is still possible, but less ideal. Build locally first and upload:

- `vendor`
- `public/build`

Be careful to build from the same PHP major version family as production when possible.

### If the host restricts symlinks

`php artisan storage:link` may fail or be blocked. In that case, you will need a hosting-specific workaround for serving files from `storage/app/public`. Check this before go-live because the app uses public storage for brand assets.

## Recommended pre-launch checklist

- switch `.env` to production values
- move from SQLite to MySQL/MariaDB
- configure real SMTP
- run migrations on production
- seed only required permissions/roles
- create the real admin user with a strong password
- run `storage:link`
- cache config/routes/views
- confirm HTTPS works
- confirm uploaded assets render
- confirm backup plan for database and uploaded files
- create an admin account and disable any unused access paths

## Operational improvements I recommend next

- add a real deployment checklist to your release process
- add backup and restore instructions for database plus uploaded files
- add uptime monitoring against `/up`
- decide whether invoice status syncing should remain request-driven or move to a scheduled command
- add a dedicated production/staging environment document for your actual host values

## Final recommendation

You can deploy this app to cPanel.

I would classify it as:

- application code: ready enough
- hosting setup: needs production configuration work
- go-live readiness today: almost, but not yet until the environment and deployment checklist are completed

If you want, the next best step is to turn this into a host-specific checklist for your exact cPanel account, domain, and database details.
