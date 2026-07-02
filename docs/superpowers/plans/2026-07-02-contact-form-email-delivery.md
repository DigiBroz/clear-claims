# Contact Form Email Delivery Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Wire up real email delivery for the contact form via Resend, add the ClearClaims logo to the notification email at the same size used on the site, add a local-only email preview route, and deploy the change to production via Forge with a working queue worker.

**Architecture:** No new architecture — the existing `ContactController` → `ContactFormSubmitted` event → `SendContactFormNotification` listener → `ContactFormNotification` → `resources/views/mail/contact-form.blade.php` pipeline already exists and is fully tested. This plan only touches: (1) mail transport config, (2) one Blade view controlling the email header, (3) a new local-only route, and (4) production infrastructure via Forge.

**Tech Stack:** Laravel 13, `resend/resend-php` (already installed), PHPUnit, Laravel Forge (via the `laravel-forge` skill).

## Global Constraints

- Real Resend credentials go to production only, via Forge — never committed to the repo.
- Email `From` address: `info@clearclaims.health` (matches the verified domain and the inbox the notification already targets).
- Logo size in the email: 80px height, 216px width (matches the site nav's `h-20`, same aspect ratio as `public/images/logo.png`, which is 1536×568px).
- No changes to rate limiting (`throttle:5,1` + honeypot on `POST /contact` already covers this) or to the contact form's fields/validation.
- The preview route must return 404 outside the `local` environment.
- Spec reference: `docs/superpowers/specs/2026-07-02-contact-form-email-delivery-design.md`.

---

### Task 1: Configure Resend as the mail transport

**Files:**
- Modify: `.env.example`

**Interfaces:**
- Produces: `MAIL_MAILER`, `RESEND_API_KEY`, `MAIL_FROM_ADDRESS` env var names, consumed by `config/mail.php` and `config/services.php` (both already read these — no code change needed there).

- [ ] **Step 1: Update `.env.example`**

Current relevant lines:
```
MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

Replace with:
```
MAIL_MAILER=resend
RESEND_API_KEY=
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="info@clearclaims.health"
MAIL_FROM_NAME="${APP_NAME}"
```

- [ ] **Step 2: Verify local `.env` is unaffected**

Run: `grep MAIL_MAILER .env`
Expected: `MAIL_MAILER=log` (local dev stays on the log driver — `.env` is not a tracked file and is not touched by this task)

- [ ] **Step 3: Commit**

```bash
git add .env.example
git commit -m "Document Resend mail config in .env.example"
```

---

### Task 2: Add the ClearClaims logo to the email header

**Files:**
- Modify: `config/mail.php`
- Create: `resources/views/vendor/mail/html/message.blade.php`
- Test: `tests/Feature/ContactFormMailTemplateTest.php`

**Interfaces:**
- Consumes: `public/images/logo.png` (existing asset, 1536×568px), `config('app.name')`, `config('app.url')`.
- Produces: overrides Laravel's default markdown mail header for every `<x-mail::message>`-based email in the app (currently only `mail.contact-form` uses it).

**Discovered during implementation:** Laravel 13's default `config/mail.php` has no `markdown` key, and `Illuminate\Mail\MailServiceProvider` does not auto-register `resources/views/vendor/mail` the way older Laravel versions' `loadViewsFrom` did — the `mail::` component namespace is only registered dynamically inside `Illuminate\Mail\Markdown::render()`, using `config('mail.markdown.paths')`. Without adding that config key, the override file is silently ignored (and a plain `view('mail.contact-form')` call throws "No hint path defined for [mail]" since the `mail::` namespace isn't registered outside the `Markdown::render()` codepath at all). Both the test and the Task 3 preview route must render through `app(\Illuminate\Mail\Markdown::class)->render(...)`, not a plain `view()` call.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/ContactFormMailTemplateTest.php`:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContactFormMailTemplateTest extends TestCase
{
    public function test_contact_form_email_includes_the_clearclaims_logo_at_nav_size(): void
    {
        $html = view('mail.contact-form', [
            'firstName' => 'Jane',
            'lastName' => 'Doe',
            'email' => 'jane@example.com',
            'company' => 'Acme Family Practice',
            'service' => 'submission',
            'body' => 'Test message body.',
        ])->render();

        $this->assertStringContainsString('images/logo.png', $html);
        $this->assertStringContainsString('width="216"', $html);
        $this->assertStringContainsString('height="80"', $html);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=ContactFormMailTemplateTest`
Expected: FAIL — the rendered HTML does not contain `images/logo.png` (current header just renders the app name as text).

- [ ] **Step 3a: Add markdown config**

Add to `config/mail.php`, after the `'from'` array:

```php
'markdown' => [
    'theme' => 'default',
    'paths' => [
        resource_path('views/vendor/mail'),
    ],
],
```

- [ ] **Step 3b: Create the overriding view**

Create `resources/views/vendor/mail/html/message.blade.php` (copied from Laravel's default at `vendor/laravel/framework/src/Illuminate/Mail/resources/views/html/message.blade.php`, with only the header slot changed):

```blade
<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
<img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" width="216" height="80" style="height:80px;width:auto;">
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{!! $slot !!}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{!! $subcopy !!}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php artisan test --filter=ContactFormMailTemplateTest`
Expected: PASS

- [ ] **Step 5: Commit**

```bash
git add resources/views/vendor/mail/html/message.blade.php tests/Feature/ContactFormMailTemplateTest.php
git commit -m "Add ClearClaims logo to the notification email header"
```

---

### Task 3: Add a local-only email preview route

**Files:**
- Modify: `routes/web.php`
- Test: `tests/Feature/DevPreviewRouteTest.php`

**Interfaces:**
- Consumes: `mail.contact-form` view (same one rendered in Task 2's test).
- Produces: named route `dev.preview.contact-form` at `GET /dev/preview/contact-form`.

- [ ] **Step 1: Write the failing tests**

Create `tests/Feature/DevPreviewRouteTest.php`:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class DevPreviewRouteTest extends TestCase
{
    public function test_preview_route_returns_404_outside_local_environment(): void
    {
        $response = $this->get('/dev/preview/contact-form');

        $response->assertNotFound();
    }

    public function test_preview_route_renders_email_in_local_environment(): void
    {
        $this->app['env'] = 'local';

        $response = $this->get('/dev/preview/contact-form');

        $response->assertOk();
        $response->assertSee('Jane', false);
    }
}
```

- [ ] **Step 2: Run tests to verify they fail**

Run: `php artisan test --filter=DevPreviewRouteTest`
Expected: FAIL — route `/dev/preview/contact-form` does not exist yet, so `assertNotFound()` passes by accident but `assertOk()` in the second test fails (still 404 since the route isn't registered).

- [ ] **Step 3: Add the route**

In `routes/web.php`, add after the existing `contact.submit` route (before the sitemap/robots routes):

```php
Route::get('/dev/preview/contact-form', function () {
    abort_unless(app()->environment('local'), 404);

    return view('mail.contact-form', [
        'firstName' => 'Jane',
        'lastName' => 'Doe',
        'email' => 'jane@example.com',
        'company' => 'Acme Family Practice',
        'service' => 'submission',
        'body' => "We'd like to discuss moving our billing to ClearClaims.",
    ]);
})->name('dev.preview.contact-form');
```

- [ ] **Step 4: Run tests to verify they pass**

Run: `php artisan test --filter=DevPreviewRouteTest`
Expected: PASS (both tests)

- [ ] **Step 5: Commit**

```bash
git add routes/web.php tests/Feature/DevPreviewRouteTest.php
git commit -m "Add local-only preview route for the contact form email"
```

---

### Task 4: Full local verification pass

**Files:** none (verification only)

**Interfaces:** none

- [ ] **Step 1: Run the full test suite**

Run: `php artisan test`
Expected: all tests pass, including the pre-existing `ContactFormSubmissionTest` (dispatch, validation, honeypot, rate limit) and the two new test files from Tasks 2–3.

- [ ] **Step 2: Manually view the rendered email**

Run: `php artisan serve` (or use the existing Herd site), then visit `http://<local-host>/dev/preview/contact-form` in a browser.
Expected: the ClearClaims logo renders at the top of the email, visually matching the nav logo's proportions (not stretched or cropped).

- [ ] **Step 3: Verify queued delivery still works locally with the log driver**

Run:
```bash
php artisan tinker --execute="App\Events\ContactFormSubmitted::dispatch('Jane','Doe','jane@example.com',null,null,'test');"
php artisan queue:work --once --stop-when-empty
```
Expected: worker output shows `App\Notifications\ContactFormNotification ... DONE`, and `storage/logs/laravel.log` contains the rendered email (including the `<img>` logo tag) since `MAIL_MAILER=log` locally.

No commit for this task — it's a verification checkpoint before moving to production deployment.

---

### Task 5: Deploy to production via Forge

**Files:** none (infrastructure/deployment only — no repo changes beyond what Tasks 1–3 already committed)

**Interfaces:**
- Consumes: a Forge API token (ask the user for this before starting — it has not been provided yet) and the Resend API key already shared in this conversation.

- [ ] **Step 1: Identify the production site on Forge**

Use the `laravel-forge` skill to list servers/sites and find the one serving `clearclaims.health`. If the Forge API token hasn't been provided yet, stop and ask the user for it here — do not proceed without it.

- [ ] **Step 2: Set production environment variables**

Use the `laravel-forge` skill to update the site's `.env` on Forge, setting:
```
MAIL_MAILER=resend
RESEND_API_KEY=<the key provided in this conversation>
MAIL_FROM_ADDRESS="info@clearclaims.health"
```
(`MAIL_FROM_NAME` already resolves from `APP_NAME`, which should already be set to `ClearClaims` in production — verify it while there.)

- [ ] **Step 3: Verify/create the queue worker**

Per `docs/superpowers/specs/queue-worker-requirement.md`, `ContactFormNotification` is queued and requires a running worker. Use the `laravel-forge` skill to check the site's daemons for an existing `queue:work` process.
- If one exists: no action needed.
- If none exists: create a daemon running `php artisan queue:work --tries=3 --max-time=3600` in the site's directory.

- [ ] **Step 4: Deploy the branch containing Tasks 1–3**

Use the `laravel-forge` skill to trigger a deployment of the current branch. Confirm the site's deploy script includes `php artisan queue:restart` (or run it manually via Forge after deploy) so the worker picks up the new code — this matters if a worker already existed before this deploy.

- [ ] **Step 5: Verify end-to-end in production**

Submit the live contact form at `https://clearclaims.health/contact` with a real test message, then confirm:
- The `jobs` table entry clears (job processed).
- An email arrives at `info@clearclaims.health` with the logo rendering correctly.
- Check the Resend dashboard's activity log for a "delivered" event as a second confirmation.

- [ ] **Step 6: Recommend key rotation**

Remind the user that the Resend API key passed through this chat and recommend rotating it in the Resend dashboard now that production is confirmed working. This is a recommendation, not a blocking step — do not rotate it without the user's go-ahead (rotating would require updating the Forge env var again).

---

## Post-Plan: Code Review

After Task 5, run `/code-review` (fix any issues found), then `/ponytail-review` (fix any over-engineering findings). Both apply to the diff produced by Tasks 1–3 (Task 4–5 produce no repo diff).
