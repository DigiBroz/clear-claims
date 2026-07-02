# Contact form email delivery — design

## Context

The contact-form pipeline (`ContactController` → `ContactFormSubmitted` event →
`SendContactFormNotification` listener → `ContactFormNotification` →
`resources/views/mail/contact-form.blade.php`) already exists and targets
`info@clearclaims.health`. The route already has honeypot protection
(`ProtectAgainstSpam`) and rate limiting (`throttle:5,1` — 5 requests/minute per IP).
`resend/resend-php` is installed and `config/mail.php` / `config/services.php`
already support `RESEND_API_KEY`. A Resend account and the `clearclaims.health`
domain are already set up and verified.

What's missing: the mailer isn't configured for a real transport
(`MAIL_MAILER=log` everywhere), the email template has no logo, and there's no
way to preview the rendered email locally without a real send.

## Goals

1. Wire up Resend as the production mail transport.
2. Add the ClearClaims logo to the email template, sized to match the site nav
   (`h-20` / 80px height).
3. Confirm existing rate limiting is sufficient (it is — no change).
4. Add a local-only route to preview the rendered email template in a browser.
5. Deploy the config change to production via Forge, including a queue worker
   (see `docs/superpowers/specs/queue-worker-requirement.md` — `ContactFormNotification`
   is `ShouldQueue`, so production needs a running worker or queued jobs never send).

## Design

### 1. Mail transport

- `.env.example` gets placeholder entries (no real values committed):
  ```
  MAIL_MAILER=resend
  RESEND_API_KEY=
  MAIL_FROM_ADDRESS="info@clearclaims.health"
  MAIL_FROM_NAME="${APP_NAME}"
  ```
- Local `.env` is left on `MAIL_MAILER=log` — no need to send real mail from a dev
  machine.
- Production `.env` (on Forge) gets the real `MAIL_MAILER=resend`,
  `RESEND_API_KEY`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`, set via the Forge API
  using a Forge API token (not committed anywhere) — not via this repo.

### 2. Logo in the email template

- Publish Laravel's markdown mail views: `php artisan vendor:publish --tag=laravel-mail`,
  producing an editable copy at `resources/views/vendor/mail/`.
- Edit `resources/views/vendor/mail/html/message.blade.php`'s header slot to
  render the logo image instead of plain app-name text:
  ```blade
  <x-slot:header>
  <x-mail::header :url="config('app.url')">
  <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}"
       width="216" height="80" style="height:80px;width:auto;">
  </x-mail::header>
  </x-slot:header>
  ```
- `width`/`height` are set as HTML attributes (not just CSS) because Outlook and
  some other mail clients ignore CSS sizing on images but respect the attributes.
  216×80 preserves the source logo's aspect ratio (1536×568) at the same 80px
  height used by the site nav (`h-20`).
- No other visual changes to the mail theme — scope is limited to the logo.

### 3. Rate limiting

No code change. `throttle:5,1` + honeypot on `POST /contact` already covers this
and is already tested (`test_honeypot_field_blocks_bot_submissions`,
`test_contact_route_is_rate_limited`).

### 4. Local-only email preview route

- Added directly in `routes/web.php`, registered only when
  `app()->environment('local')` so it doesn't exist in production's route table
  at all (not just a 404 guard):
  ```php
  if (app()->environment('local')) {
      Route::get('/dev/preview/contact-form', function () {
          return view('mail.contact-form', [
              'firstName' => 'Jane',
              'lastName' => 'Doe',
              'email' => 'jane@example.com',
              'company' => 'Acme Family Practice',
              'service' => 'submission',
              'body' => "We'd like to discuss moving our billing to ClearClaims.",
          ]);
      })->name('dev.preview.contact-form');
  }
  ```
- Renders the markdown Blade view directly (not through `Mail`/`Notification`),
  since `mail.contact-form` is itself a `<x-mail::message>` component tree that
  Blade resolves normally — no real send is triggered.

### 5. Production queue worker

- Per `docs/superpowers/specs/queue-worker-requirement.md`, production needs a
  long-lived, auto-restarting worker running
  `php artisan queue:work --tries=3 --max-time=3600`.
- Check via the Forge API whether the site already has a queue worker configured;
  if not, create one using Forge's built-in queue worker manager (the doc's
  recommended option over hand-rolled Supervisor/systemd).
- After deploying the code changes, run `php artisan queue:restart` (or let
  Forge's deploy script do it) so the worker picks up the new code.

### 6. Deployment

- Use the `laravel-forge` skill with a Forge API token (scoped to the user's
  account, provided out of band) to:
  1. Set the production env vars (§1).
  2. Confirm/create the queue worker (§5).
  3. Deploy the branch containing these changes.
- The Resend API key passes through this conversation at the user's explicit
  request; recommended follow-up (not blocking) is to rotate it in the Resend
  dashboard once confirmed working in production.

## Testing

- Existing `tests/Feature/ContactFormSubmissionTest.php` already covers
  dispatch, validation, honeypot, and rate-limit behavior — no changes needed.
- Add one assertion (or a small new test) that `GET /dev/preview/contact-form`
  returns 200 in the `local` environment and that the route does not resolve
  (404) when the environment is not `local`.
- Manual verification: run the local preview route in a browser to visually
  confirm the logo renders at the correct size before deploying.

## Out of scope

- No changes to the contact form's fields, validation rules, or the visual
  design of the page itself.
- No broader re-theming of the email (colors, fonts) beyond adding the logo.
- No auto-reply/confirmation email to the person who submitted the form.
