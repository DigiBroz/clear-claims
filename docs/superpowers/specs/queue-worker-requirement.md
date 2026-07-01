# Queue worker requirement

`app/Notifications/ContactFormNotification.php` implements `ShouldQueue` (uses the
`Queueable` trait), so every contact-form submission dispatches a queued job rather
than sending mail synchronously. This is correct behaviour, but it only works if a
queue worker process is running continuously. **Without a running worker, queued
notifications sit in the `jobs` database table forever and are never sent.**

## Current configuration

- `QUEUE_CONNECTION=database` in `.env` — queued jobs are stored in the `jobs` table
  (see `database/migrations/0001_01_01_000002_create_jobs_table.php`).
- `MAIL_MAILER=log` in `.env` locally — sent mail is written to `storage/logs/laravel.log`
  instead of actually being emailed. Production must set a real mailer (SMTP/Postmark/etc).

## Local development (Herd)

Herd 1.28.0's CLI (`herd services:*`) only manages infrastructure services — the
available service types are limited to `mariadb`, `meilisearch`, `minio`, `mongodb`,
`mysql`, `postgresql`, `redis`, `reverb`, `rustfs`, `typesense`, `valkey`
(`herd services:available`). There is **no CLI-exposed way to register an arbitrary
supervised process** such as `php artisan queue:work` for a specific site — that
capability (if present at all) lives only in the Herd GUI app's "Services"/process
panel, not in the `herd` CLI surface used here.

To process queued jobs locally, run a worker manually in a dedicated terminal/tab
whenever contact-form testing is needed:

```bash
cd /Users/morgz/Code/clear-claims
php artisan queue:work
```

Or process one batch and exit (useful for quick manual verification):

```bash
php artisan queue:work --once --stop-when-empty
```

This was verified end-to-end during the final review pass:

1. Dispatched `App\Events\ContactFormSubmitted` via `php artisan tinker` — confirmed
   a row appeared in the `jobs` table (`DB::table('jobs')->count()` went from 0 to 1).
2. Ran `php artisan queue:work --once --stop-when-empty` — output showed
   `App\Notifications\ContactFormNotification ... DONE`.
3. Confirmed the `jobs` table row cleared (`DB::table('jobs')->count()` back to 0).
4. Confirmed the rendered notification email appeared in `storage/logs/laravel.log`
   (since `MAIL_MAILER=log`).

## Production

Production must run a long-lived, auto-restarting queue worker — a one-off
`queue:work` in a terminal is not sufficient because it does not survive
deploys, crashes, or server reboots. Standard options:

- **Supervisor** (most common for traditional VPS/Forge-style deployments):
  a program entry running
  `php artisan queue:work --daemon --tries=3 --max-time=3600`
  with `autorestart=true`, monitored so it restarts after deploys
  (`php artisan queue:restart` after each deploy to pick up new code).
- **systemd** service unit with `Restart=always` running the same command.
- Managed platforms (e.g. Laravel Cloud, Forge's built-in queue worker manager)
  typically expose a first-class "queue worker" configuration — use that instead
  of hand-rolling Supervisor/systemd if the hosting platform provides it.

Whichever mechanism is used, monitor the `failed_jobs` table and configure
`queue:work`'s `--tries`/`--backoff` so a broken mail transport doesn't silently
swallow contact-form submissions.
