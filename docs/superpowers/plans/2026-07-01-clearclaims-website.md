# ClearClaims Website Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a Laravel 13 marketing website for ClearClaims Health Accounts (Pty) Ltd: five content pages, a ported contact form workflow, SEO parity with the sibling Phungo site, security hardening, and a signature GSAP-driven animation motif, served locally via Herd at `clear-claims.test`.

**Architecture:** Server-rendered Blade views under a single shared layout, Alpine.js for small interactive bits (nav toggle), GSAP + ScrollTrigger for scroll-driven animation, Tailwind CSS v4 CSS-first theme. No database-backed features. The contact form uses Laravel's Event → Listener → Notification pattern, ported from `/Users/morgz/Code/phungo-web`.

**Tech Stack:** Laravel 13, PHP 8.4 (Herd), Blade, Alpine.js, GSAP + ScrollTrigger, Tailwind CSS v4, Vite, `artesaos/seotools`, `spatie/laravel-honeypot`, PHPUnit (Feature tests).

## Global Constraints

- Laravel version: latest available via Composer at scaffold time (Laravel 13), not pinned to an assumed older version.
- No em dashes anywhere in on-site copy (headings, body text, form labels, mail subject/body).
- No semicolons anywhere in on-site copy.
- No fabricated testimonials, client names, client logos, or unverifiable statistics anywhere on the site.
- Contact form notifications route to `info@clearclaims.health`.
- Local domain: `clear-claims.test`, served via Herd with `herd secure` (local TLS).
- Colour palette: `brand` (blue, #2f7abf mid / #14305c navy dark) and `growth` (green, #29a467), both derived from the ClearClaims logo. Neutral tones use Tailwind's built-in `slate` palette.
- Fonts: Space Grotesk for headings, Inter for body copy.
- SEO: per-page `SEOTools::setTitle()`/`setDescription()`, OpenGraph + Twitter image, JSON-LD `MedicalBusiness` type, `robots.txt` allowing all crawlers, plus an XML sitemap.
- Security: rate-limited contact POST route, honeypot field, security headers middleware (CSP, X-Content-Type-Options, X-Frame-Options, Referrer-Policy, Permissions-Policy), forced HTTPS outside `local`.

---

### Task 1: Scaffold the Laravel 13 project and local environment

**Files:**
- Create: entire Laravel 13 application tree in `/Users/morgz/Code/clear-claims` (preserving the existing `.git` and `docs/` directory)
- Modify: `/Users/morgz/Code/clear-claims/.env`

**Interfaces:**
- Produces: a runnable Laravel 13 app at `/Users/morgz/Code/clear-claims`, reachable at `https://clear-claims.test`, with `artesaos/seotools`, `spatie/laravel-honeypot`, `tailwindcss`, `@tailwindcss/vite`, `alpinejs`, and `gsap` installed and ready for later tasks to build on.

- [ ] **Step 1: Scaffold Laravel into a temp directory (current directory already has `.git`/`docs/`, so `composer create-project` can't target it directly)**

```bash
cd /Users/morgz/Code
composer create-project laravel/laravel clear-claims-scaffold-tmp
```

Expected: completes with "Application key set successfully." and no errors.

- [ ] **Step 2: Merge the scaffold into the existing repo, preserving `.git` and `docs/`**

```bash
cd /Users/morgz/Code
rsync -a --exclude='.git' clear-claims-scaffold-tmp/ clear-claims/
rm -rf clear-claims-scaffold-tmp
cd clear-claims
git status
```

Expected: `git status` shows the new Laravel app files as untracked, and `docs/superpowers/` is still present and unmodified.

- [ ] **Step 3: Confirm the Laravel version resolved is the current latest major**

```bash
cd /Users/morgz/Code/clear-claims
php artisan --version
```

Expected: prints `Laravel Framework 13.x.x` (or higher if a newer major has shipped since this plan was written — if so, that is correct, this project should always track the latest major).

- [ ] **Step 4: Install backend Composer packages**

```bash
cd /Users/morgz/Code/clear-claims
composer require artesaos/seotools spatie/laravel-honeypot
php artisan vendor:publish --tag=seotools-config
php artisan vendor:publish --tag="honeypot-config"
```

Expected: `config/seotools.php` and `config/honeypot.php` now exist.

- [ ] **Step 5: Install frontend packages**

```bash
cd /Users/morgz/Code/clear-claims
npm install tailwindcss @tailwindcss/vite alpinejs gsap
```

Expected: `package.json` now lists all four under `dependencies`/`devDependencies`.

- [ ] **Step 6: Configure `.env` for this project**

Edit `/Users/morgz/Code/clear-claims/.env`, updating these keys:

```
APP_NAME=ClearClaims
APP_URL=https://clear-claims.test
```

- [ ] **Step 7: Link and secure the site with Herd**

```bash
cd /Users/morgz/Code/clear-claims
herd link clear-claims
herd secure clear-claims
```

Expected: `herd link` reports the site linked as `clear-claims.test`. `herd secure` reports a valid local TLS certificate installed.

- [ ] **Step 8: Verify the site loads over local HTTPS**

```bash
curl -sk -o /dev/null -w "%{http_code}\n" https://clear-claims.test
```

Expected: `200`.

- [ ] **Step 9: Commit**

```bash
cd /Users/morgz/Code/clear-claims
git add -A
git commit -m "Scaffold Laravel 13 app with SEO, honeypot, Tailwind, Alpine, and GSAP dependencies"
```

---

### Task 2: Design system, shared layout, and Home page

**Files:**
- Create: `resources/css/app.css` (rewrite the default with the ClearClaims theme)
- Create: `resources/views/layouts/app.blade.php`
- Create: `resources/views/pages/home.blade.php`
- Create: `app/Http/Controllers/PageController.php`
- Copy: `/Users/morgz/Downloads/ClearClaims Image Jun 4, 2026, 12_14_42 PM.png` → `public/images/logo.png`
- Modify: `routes/web.php`
- Modify: `tests/TestCase.php` (disable Vite manifest lookups in tests)
- Test: `tests/Feature/HomePageTest.php`

**Interfaces:**
- Produces: `PageController` class with a `home()` method and a private `setSeoImage(): void` helper that later tasks (Task 3-6) will add sibling methods to and reuse.
- Produces: `layouts.app` Blade layout with `@yield('content')`, used by every page view.

- [ ] **Step 1: Copy the logo asset**

```bash
mkdir -p /Users/morgz/Code/clear-claims/public/images
cp "/Users/morgz/Downloads/ClearClaims Image Jun 4, 2026, 12_14_42 PM.png" /Users/morgz/Code/clear-claims/public/images/logo.png
cp "/Users/morgz/Downloads/ClearClaims Image Jun 4, 2026, 12_14_42 PM.png" /Users/morgz/Code/clear-claims/public/images/og-image.png
```

- [ ] **Step 2: Make Vite a no-op during tests**

Edit `tests/TestCase.php`:

```php
<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }
}
```

- [ ] **Step 3: Write the failing test for the Home page**

Create `tests/Feature/HomePageTest.php`:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_home_page_loads_with_expected_content(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('ClearClaims', false);
        $response->assertSee('percentage of collections', false);
        $response->assertSee('Book a Free Consultation', false);
        $response->assertSee('Medical Claims Submission', false);
    }

    public function test_home_page_sets_seo_title_and_description(): void
    {
        $response = $this->get('/');

        $response->assertSee('Get Paid Faster', false);
        $response->assertSee('medical billing', false);
    }
}
```

- [ ] **Step 4: Run the test to verify it fails**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=HomePageTest
```

Expected: FAIL (route `/` still returns the default Laravel welcome page, or `home()` doesn't exist).

- [ ] **Step 5: Write the Tailwind v4 theme**

Replace the contents of `resources/css/app.css`:

```css
@import 'tailwindcss';

@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';
@source '../**/*.blade.php';
@source '../**/*.js';

@theme {
    --font-sans: 'Inter', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji',
        'Segoe UI Symbol', 'Noto Color Emoji';
    --font-heading: 'Space Grotesk', ui-sans-serif, system-ui, sans-serif;

    --color-brand-50: #eef5fc;
    --color-brand-100: #d9e9f7;
    --color-brand-200: #b3d3ef;
    --color-brand-300: #82b7e3;
    --color-brand-400: #4f96d3;
    --color-brand-500: #2f7abf;
    --color-brand-600: #22609e;
    --color-brand-700: #1c4c7e;
    --color-brand-800: #193f66;
    --color-brand-900: #14305c;
    --color-brand-950: #0b1c38;

    --color-growth-50: #eafbf2;
    --color-growth-100: #cdf4dd;
    --color-growth-200: #9de8bf;
    --color-growth-300: #7fdba8;
    --color-growth-400: #4ec98a;
    --color-growth-500: #29a467;
    --color-growth-600: #1f8552;
    --color-growth-700: #1a6b44;
    --color-growth-800: #165536;
    --color-growth-900: #123a26;
}

@layer base {
    html {
        scroll-behavior: smooth;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: var(--font-heading);
    }
}
```

- [ ] **Step 6: Configure Vite to use the Tailwind plugin**

Edit `vite.config.js` to add the Tailwind plugin:

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
```

- [ ] **Step 7: Write the shared layout**

Create `resources/views/layouts/app.blade.php`:

```blade
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! SEO::generate() !!}

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|space-grotesk:500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-700 antialiased">
    {{-- Navigation --}}
    <nav class="fixed top-0 z-50 w-full border-b border-slate-200 bg-white/90 backdrop-blur-xl" x-data="{ open: false }">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="py-2">
                    <img src="{{ asset('images/logo.png') }}" alt="ClearClaims Health Accounts" class="h-16 w-auto">
                </a>

                <div class="hidden items-center gap-8 md:flex">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-slate-600 transition hover:text-brand-600 {{ request()->routeIs('home') ? 'text-brand-600' : '' }}">Home</a>
                    <a href="{{ route('services') }}" class="text-sm font-medium text-slate-600 transition hover:text-brand-600 {{ request()->routeIs('services') ? 'text-brand-600' : '' }}">Services</a>
                    <a href="{{ route('pricing') }}" class="text-sm font-medium text-slate-600 transition hover:text-brand-600 {{ request()->routeIs('pricing') ? 'text-brand-600' : '' }}">Pricing Model</a>
                    <a href="{{ route('about') }}" class="text-sm font-medium text-slate-600 transition hover:text-brand-600 {{ request()->routeIs('about') ? 'text-brand-600' : '' }}">About</a>
                    <a href="{{ route('contact') }}" class="inline-flex items-center rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-700">Contact Us</a>
                </div>

                <button @click="open = !open" class="md:hidden text-slate-600 hover:text-brand-600">
                    <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <div x-show="open" x-cloak x-transition class="border-t border-slate-200 bg-white md:hidden">
            <div class="space-y-1 px-4 py-4">
                <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2 text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-brand-600">Home</a>
                <a href="{{ route('services') }}" class="block rounded-lg px-3 py-2 text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-brand-600">Services</a>
                <a href="{{ route('pricing') }}" class="block rounded-lg px-3 py-2 text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-brand-600">Pricing Model</a>
                <a href="{{ route('about') }}" class="block rounded-lg px-3 py-2 text-base font-medium text-slate-600 hover:bg-slate-50 hover:text-brand-600">About</a>
                <a href="{{ route('contact') }}" class="block rounded-lg bg-brand-600 px-3 py-2 text-center text-base font-semibold text-white">Contact Us</a>
            </div>
        </div>
    </nav>

    <main class="pt-24">
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="grid gap-12 md:grid-cols-4">
                <div class="md:col-span-1">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="ClearClaims Health Accounts" class="h-16 w-auto">
                    </a>
                    <p class="mt-4 text-sm leading-relaxed text-slate-500">Medical billing and practice support solutions that help South African healthcare providers get paid faster and spend less time on admin.</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-800">Services</h3>
                    <ul class="mt-4 space-y-3">
                        <li><a href="{{ route('services') }}#submission" class="text-sm text-slate-500 transition hover:text-brand-600">Claims Submission</a></li>
                        <li><a href="{{ route('services') }}#followups" class="text-sm text-slate-500 transition hover:text-brand-600">Follow-Ups and Collections</a></li>
                        <li><a href="{{ route('services') }}#reconciliation" class="text-sm text-slate-500 transition hover:text-brand-600">Payment Reconciliation</a></li>
                        <li><a href="{{ route('services') }}#reporting" class="text-sm text-slate-500 transition hover:text-brand-600">Financial Reporting</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-800">Company</h3>
                    <ul class="mt-4 space-y-3">
                        <li><a href="{{ route('pricing') }}" class="text-sm text-slate-500 transition hover:text-brand-600">Pricing Model</a></li>
                        <li><a href="{{ route('about') }}" class="text-sm text-slate-500 transition hover:text-brand-600">About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="text-sm text-slate-500 transition hover:text-brand-600">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-800">Get in Touch</h3>
                    <ul class="mt-4 space-y-3">
                        <li class="text-sm text-slate-500">071 339 5866</li>
                        <li class="text-sm text-slate-500">info@clearclaims.health</li>
                        <li class="text-sm text-slate-500">South Africa</li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 border-t border-slate-200 pt-8 text-center">
                <p class="text-sm text-slate-400">&copy; {{ date('Y') }} ClearClaims Health Accounts (Pty) Ltd. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
```

- [ ] **Step 8: Write the `PageController` with the Home page action**

Create `app/Http/Controllers/PageController.php`:

```php
<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOTools;

class PageController extends Controller
{
    private function setSeoImage(): void
    {
        $image = asset('images/og-image.png').'?v='.filemtime(public_path('images/og-image.png'));

        SEOTools::opengraph()->addImage($image);
        SEOTools::twitter()->setImage($image);
        SEOTools::jsonLd()->addImage($image);
    }

    private function setMedicalBusinessJsonLd(): void
    {
        SEOTools::jsonLd()->setType('MedicalBusiness');
        SEOTools::jsonLd()->addValue('telephone', '+27713395866');
        SEOTools::jsonLd()->addValue('email', 'info@clearclaims.health');
        SEOTools::jsonLd()->addValue('address', [
            '@type' => 'PostalAddress',
            'addressCountry' => 'ZA',
        ]);
    }

    public function home()
    {
        SEOTools::setTitle('Medical Billing and Practice Support for South African Healthcare Providers');
        SEOTools::setDescription('ClearClaims Health Accounts handles medical claims submission, medical aid follow-ups, payment reconciliation, and practice reporting so your practice gets paid faster with less administrative burden.');
        $this->setMedicalBusinessJsonLd();
        $this->setSeoImage();

        return view('pages.home');
    }
}
```

- [ ] **Step 9: Wire the Home route**

Edit `routes/web.php`:

```php
<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
```

(Additional page routes are added to this file in Tasks 3 to 7.)

- [ ] **Step 10: Write the Home page view with full copy**

Create `resources/views/pages/home.blade.php`:

```blade
@extends('layouts.app')

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-b from-brand-50 to-white">
        <div class="relative mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <h1 class="max-w-3xl text-4xl font-bold text-brand-900 sm:text-5xl">Get Paid Faster. Do Less Admin. Stay Focused on Patients.</h1>
            <p class="mt-6 max-w-2xl text-lg text-slate-600">ClearClaims Health Accounts handles medical billing and practice support so your team can spend less time chasing medical aids and more time treating patients.</p>
            <div class="mt-10 flex flex-wrap gap-4">
                <a href="{{ route('contact') }}" class="inline-flex items-center rounded-lg bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">Book a Free Consultation</a>
                <a href="{{ route('pricing') }}" class="inline-flex items-center rounded-lg border border-slate-300 px-8 py-3.5 text-base font-semibold text-slate-700 transition hover:border-brand-400 hover:text-brand-700">See Our Pricing Model</a>
            </div>
        </div>
    </section>

    {{-- Services overview --}}
    <section class="py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-brand-900">Everything Your Practice Needs to Get Paid Properly</h2>
            <p class="mt-4 max-w-2xl text-slate-600">From claims submission to payment reconciliation, we manage the full billing cycle for your practice.</p>

            <div class="mt-12 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-xl border border-slate-200 p-8">
                    <h3 class="font-semibold text-brand-900">Medical Claims Submission and Processing</h3>
                    <p class="mt-2 text-sm text-slate-600">We prepare and submit claims accurately and on time, checking coding and documentation before anything reaches a medical aid.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-8">
                    <h3 class="font-semibold text-brand-900">Medical Aid Follow-Ups and Collections</h3>
                    <p class="mt-2 text-sm text-slate-600">We follow up directly with medical aids on outstanding and disputed claims until they are resolved.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-8">
                    <h3 class="font-semibold text-brand-900">Payment Reconciliation and Allocation</h3>
                    <p class="mt-2 text-sm text-slate-600">Every payment gets matched against the original claim and allocated to the correct patient account.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-8">
                    <h3 class="font-semibold text-brand-900">Patient Account Management</h3>
                    <p class="mt-2 text-sm text-slate-600">We keep patient billing accounts up to date, so your front desk is not stuck managing billing queries.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-8">
                    <h3 class="font-semibold text-brand-900">Practice Financial Reporting</h3>
                    <p class="mt-2 text-sm text-slate-600">Regular, easy to understand reports on submissions, payments, and outstanding claims.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-8">
                    <h3 class="font-semibold text-brand-900">Onboarding Support for New Practices</h3>
                    <p class="mt-2 text-sm text-slate-600">We handle the setup, from mapping existing patient data to getting your team comfortable with the new process.</p>
                </div>
            </div>

            <a href="{{ route('services') }}" class="mt-10 inline-flex items-center font-semibold text-brand-600 hover:text-brand-700">See every service in detail &rarr;</a>
        </div>
    </section>

    {{-- Pricing teaser --}}
    <section class="border-y border-slate-200 bg-brand-900 py-24 text-white">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold">We Only Get Paid When You Get Paid</h2>
            <p class="mt-6 text-lg text-brand-100">ClearClaims works on a percentage of collections model. Our fee is calculated on the money medical aids actually pay out to your practice, not on the claims we submit. If a claim is rejected or never paid, we do not charge for it. That keeps our incentives lined up with yours from the first submission to the final reconciled payment.</p>
            <a href="{{ route('pricing') }}" class="mt-8 inline-flex items-center rounded-lg bg-growth-500 px-8 py-3.5 text-base font-semibold text-white transition hover:bg-growth-600">See How Our Pricing Works</a>
        </div>
    </section>

    {{-- How it works --}}
    <section class="py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-brand-900">How It Works</h2>
            <div class="mt-12 space-y-8">
                <div class="flex gap-6">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-growth-500 font-semibold text-white">1</div>
                    <div>
                        <h3 class="font-semibold text-brand-900">Submit Patient and Treatment Details</h3>
                        <p class="mt-1 text-slate-600">Your practice sends us the information we need for each consultation or procedure, in whatever format works for your existing systems.</p>
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-growth-500 font-semibold text-white">2</div>
                    <div>
                        <h3 class="font-semibold text-brand-900">We Process and Submit Claims Accurately</h3>
                        <p class="mt-1 text-slate-600">Our team checks every claim for coding and documentation issues before it goes to the medical aid, reducing the chance of a rejection.</p>
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-growth-500 font-semibold text-white">3</div>
                    <div>
                        <h3 class="font-semibold text-brand-900">We Follow Up With Medical Aids</h3>
                        <p class="mt-1 text-slate-600">Outstanding and disputed claims get followed up directly with the relevant medical aid until they are resolved, not left to sit in a queue.</p>
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-growth-500 font-semibold text-white">4</div>
                    <div>
                        <h3 class="font-semibold text-brand-900">Payments Are Tracked and Reconciled</h3>
                        <p class="mt-1 text-slate-600">Every payment that comes in gets matched against the original claim and allocated to the correct patient account.</p>
                    </div>
                </div>
                <div class="flex gap-6">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-growth-500 font-semibold text-white">5</div>
                    <div>
                        <h3 class="font-semibold text-brand-900">You Receive Regular Updates and Reports</h3>
                        <p class="mt-1 text-slate-600">Clear, regular reporting on what has been submitted, what has been paid, and what still needs attention, so you always know where your practice's income stands.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Commitment values --}}
    <section class="border-t border-slate-200 bg-slate-50 py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-brand-900">Our Commitment</h2>
            <div class="mt-12 grid gap-8 md:grid-cols-4">
                <div>
                    <h3 class="font-semibold text-brand-900">Accuracy</h3>
                    <p class="mt-2 text-sm text-slate-600">Every claim is checked before submission, so your practice sees fewer rejections and less rework.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-brand-900">Efficiency</h3>
                    <p class="mt-2 text-sm text-slate-600">Claims move through submission, follow-up, and reconciliation without unnecessary delay.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-brand-900">Confidentiality</h3>
                    <p class="mt-2 text-sm text-slate-600">Patient and practice information is handled with the care and discretion healthcare data demands.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-brand-900">Professional Service</h3>
                    <p class="mt-2 text-sm text-slate-600">Your practice and your patients are represented professionally in every interaction with medical aids.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Closing CTA --}}
    <section class="py-24">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-brand-900">Ready to Spend Less Time on Billing?</h2>
            <p class="mt-4 text-slate-600">Tell us about your practice and we will show you what ClearClaims can take off your plate.</p>
            <a href="{{ route('contact') }}" class="mt-8 inline-flex items-center rounded-lg bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">Book a Free Consultation</a>
        </div>
    </section>
@endsection
```

- [ ] **Step 11: Run the test to verify it passes**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=HomePageTest
```

Expected: PASS (2 tests).

- [ ] **Step 12: Commit**

```bash
git add -A
git commit -m "Add design system, shared layout, and Home page"
```

---

### Task 3: Services page

**Files:**
- Modify: `app/Http/Controllers/PageController.php` (add `services()` method)
- Modify: `routes/web.php` (add services route)
- Create: `resources/views/pages/services.blade.php`
- Test: `tests/Feature/ServicesPageTest.php`

**Interfaces:**
- Consumes: `PageController::setSeoImage()`, `PageController::setMedicalBusinessJsonLd()` (private helpers from Task 2)

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/ServicesPageTest.php`:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class ServicesPageTest extends TestCase
{
    public function test_services_page_lists_all_six_services(): void
    {
        $response = $this->get('/services');

        $response->assertOk();
        $response->assertSee('Medical Claims Submission and Processing', false);
        $response->assertSee('Medical Aid Follow-Ups and Collections', false);
        $response->assertSee('Payment Reconciliation and Allocation', false);
        $response->assertSee('Patient Account Management', false);
        $response->assertSee('Practice Financial Reporting', false);
        $response->assertSee('Onboarding Support for New Practices', false);
    }
}
```

- [ ] **Step 2: Run the test to verify it fails**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=ServicesPageTest
```

Expected: FAIL (route not defined).

- [ ] **Step 3: Add the `services()` action to `PageController`**

Add this method inside `App\Http\Controllers\PageController` (in `app/Http/Controllers/PageController.php`), alongside `home()`:

```php
    public function services()
    {
        SEOTools::setTitle('Medical Billing Services');
        SEOTools::setDescription('Explore ClearClaims full medical billing service, from claims submission and medical aid follow-ups to payment reconciliation, patient account management, financial reporting, and onboarding support.');
        $this->setMedicalBusinessJsonLd();
        $this->setSeoImage();

        return view('pages.services');
    }
```

- [ ] **Step 4: Add the services route**

Edit `routes/web.php`, adding below the home route:

```php
Route::get('/services', [PageController::class, 'services'])->name('services');
```

- [ ] **Step 5: Write the Services page view**

Create `resources/views/pages/services.blade.php`:

```blade
@extends('layouts.app')

@section('content')
    <section class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-b from-brand-50 to-white">
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-brand-900 sm:text-5xl">Our Services</h1>
            <p class="mt-4 max-w-2xl text-lg text-slate-600">Everything your practice needs to submit, chase, reconcile, and report on medical claims, handled by one team.</p>
        </div>
    </section>

    <section class="py-24">
        <div class="mx-auto max-w-5xl space-y-16 px-4 sm:px-6 lg:px-8">
            <div id="submission">
                <h2 class="text-2xl font-bold text-brand-900">Medical Claims Submission and Processing</h2>
                <p class="mt-4 text-slate-600">We prepare and submit your medical claims accurately and on time, checking coding and documentation before anything reaches a medical aid. That reduces the number of claims that come back rejected or queried, which means your practice gets paid faster and with less back and forth.</p>
            </div>
            <div id="followups">
                <h2 class="text-2xl font-bold text-brand-900">Medical Aid Follow-Ups and Collections</h2>
                <p class="mt-4 text-slate-600">Submitting a claim is only half the job. We follow up directly with medical aids on outstanding, delayed, or disputed claims until they are resolved, so unpaid claims do not quietly disappear into an inbox.</p>
            </div>
            <div id="reconciliation">
                <h2 class="text-2xl font-bold text-brand-900">Payment Reconciliation and Allocation</h2>
                <p class="mt-4 text-slate-600">When a medical aid pays out, we match that payment against the original claim and allocate it correctly to the patient's account. This keeps your practice's financial records accurate and gives you a true picture of what has actually been collected.</p>
            </div>
            <div id="accounts">
                <h2 class="text-2xl font-bold text-brand-900">Patient Account Management</h2>
                <p class="mt-4 text-slate-600">We keep patient billing accounts up to date, tracking balances, co-payments, and outstanding amounts, so your front desk team is not stuck managing billing queries on top of everything else.</p>
            </div>
            <div id="reporting">
                <h2 class="text-2xl font-bold text-brand-900">Practice Financial Reporting</h2>
                <p class="mt-4 text-slate-600">You receive regular, easy to understand reports on submissions, payments, and outstanding claims, giving you visibility into your practice's income without having to dig through statements yourself.</p>
            </div>
            <div id="onboarding">
                <h2 class="text-2xl font-bold text-brand-900">Onboarding Support for New Practices</h2>
                <p class="mt-4 text-slate-600">Moving your billing to ClearClaims does not mean starting from scratch. We handle the setup, from mapping your existing patient and claims data to getting your team comfortable with the new process, with minimal disruption to your practice.</p>
            </div>
        </div>
    </section>

    <section class="border-t border-slate-200 bg-slate-50 py-24">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-brand-900">Curious What This Costs?</h2>
            <p class="mt-4 text-slate-600">Our pricing is built so we only earn when your practice actually gets paid.</p>
            <a href="{{ route('pricing') }}" class="mt-8 inline-flex items-center rounded-lg bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">See Our Pricing Model</a>
        </div>
    </section>
@endsection
```

- [ ] **Step 6: Run the test to verify it passes**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=ServicesPageTest
```

Expected: PASS.

- [ ] **Step 7: Commit**

```bash
git add -A
git commit -m "Add Services page"
```

---

### Task 4: Pricing Model page

**Files:**
- Modify: `app/Http/Controllers/PageController.php` (add `pricing()` method)
- Modify: `routes/web.php` (add pricing route)
- Create: `resources/views/pages/pricing.blade.php`
- Test: `tests/Feature/PricingPageTest.php`

**Interfaces:**
- Consumes: `PageController::setSeoImage()`, `PageController::setMedicalBusinessJsonLd()`

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/PricingPageTest.php`:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class PricingPageTest extends TestCase
{
    public function test_pricing_page_explains_the_collections_model(): void
    {
        $response = $this->get('/pricing');

        $response->assertOk();
        $response->assertSee('A Pricing Model Built Around Getting You Paid', false);
        $response->assertSee('percentage of the money that is successfully paid out', false);
        $response->assertSee('never charged on the value of claims submitted', false);
        $response->assertSee('What percentage do you charge', false);
    }
}
```

- [ ] **Step 2: Run the test to verify it fails**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=PricingPageTest
```

Expected: FAIL.

- [ ] **Step 3: Add the `pricing()` action to `PageController`**

Add this method inside `App\Http\Controllers\PageController`:

```php
    public function pricing()
    {
        SEOTools::setTitle('Our Pricing Model');
        SEOTools::setDescription('ClearClaims charges a percentage of collections, calculated only on money medical aids actually pay to your practice, not on submitted claims. See how our pricing model compares to flat fee billing.');
        $this->setMedicalBusinessJsonLd();
        $this->setSeoImage();

        return view('pages.pricing');
    }
```

- [ ] **Step 4: Add the pricing route**

Edit `routes/web.php`:

```php
Route::get('/pricing', [PageController::class, 'pricing'])->name('pricing');
```

- [ ] **Step 5: Write the Pricing page view**

Create `resources/views/pages/pricing.blade.php`:

```blade
@extends('layouts.app')

@section('content')
    <section class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-b from-brand-50 to-white">
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-brand-900 sm:text-5xl">A Pricing Model Built Around Getting You Paid</h1>
            <p class="mt-4 max-w-2xl text-lg text-slate-600">Most medical billing services charge a flat monthly fee or a percentage of the claims they submit, whether or not those claims are ever paid. ClearClaims works differently.</p>
        </div>
    </section>

    <section class="py-24">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-brand-900">How the Percentage of Collections Model Works</h2>
            <p class="mt-6 text-slate-600">Our fee is a percentage of the money that is successfully paid out to your practice by the medical aid. It is never charged on the value of claims submitted, and it is never charged on claims that are rejected, disputed, or never paid. If a medical aid does not pay, we do not get paid either.</p>
            <p class="mt-4 text-slate-600">This means our incentives are aligned with yours from the first claim we submit. We are not paid for volume of paperwork. We are paid for money that actually lands in your practice's account, which is why we follow up on outstanding claims as hard as we do.</p>
        </div>
    </section>

    <section class="border-t border-slate-200 bg-slate-50 py-24">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-brand-900">Flat Fee Billing vs Percentage of Collections</h2>
            <div class="mt-10 overflow-hidden rounded-xl border border-slate-200 bg-white">
                <table class="w-full text-left text-sm">
                    <thead class="bg-brand-900 text-white">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Traditional Flat Fee Billing</th>
                            <th class="px-6 py-4 font-semibold">ClearClaims Percentage of Collections</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <tr>
                            <td class="px-6 py-4 text-slate-600">Charged every month regardless of collections</td>
                            <td class="px-6 py-4 text-slate-600">Charged only on money actually paid to your practice</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-slate-600">No direct incentive to chase rejected claims</td>
                            <td class="px-6 py-4 text-slate-600">Direct incentive to resolve every outstanding claim</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-slate-600">Cost is fixed even in a slow month</td>
                            <td class="px-6 py-4 text-slate-600">Cost scales naturally with your practice's income</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-slate-600">Risk sits mostly with the practice</td>
                            <td class="px-6 py-4 text-slate-600">Risk is shared with ClearClaims</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="py-24">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-brand-900">Pricing Model Questions</h2>
            <div class="mt-10 space-y-8">
                <div>
                    <h3 class="font-semibold text-brand-900">What percentage do you charge?</h3>
                    <p class="mt-2 text-slate-600">Our percentage is quoted after a short, no-obligation consultation, based on your practice's size, specialty, and claims volume. We would rather understand your practice properly than quote a generic number that does not reflect your situation.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-brand-900">Do you charge on claims that are submitted but not yet paid?</h3>
                    <p class="mt-2 text-slate-600">No. Our fee is calculated only on money the medical aid has actually paid to your practice, not on claims that are pending or under review.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-brand-900">What happens if a claim is rejected and never resolved?</h3>
                    <p class="mt-2 text-slate-600">If a claim is never paid, we never charge a fee on it. There is no cost to your practice for claims that do not result in payment.</p>
                </div>
                <div>
                    <h3 class="font-semibold text-brand-900">Is there a setup fee or minimum contract period?</h3>
                    <p class="mt-2 text-slate-600">We can discuss contract terms during your consultation. Our aim is a straightforward working relationship, not one built around lock-in fees.</p>
                </div>
            </div>
            <a href="{{ route('contact') }}" class="mt-12 inline-flex items-center rounded-lg bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">Get a Quote for Your Practice</a>
        </div>
    </section>
@endsection
```

- [ ] **Step 6: Run the test to verify it passes**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=PricingPageTest
```

Expected: PASS.

- [ ] **Step 7: Commit**

```bash
git add -A
git commit -m "Add Pricing Model page"
```

---

### Task 5: About page

**Files:**
- Modify: `app/Http/Controllers/PageController.php` (add `about()` method)
- Modify: `routes/web.php` (add about route)
- Create: `resources/views/pages/about.blade.php`
- Test: `tests/Feature/AboutPageTest.php`

**Interfaces:**
- Consumes: `PageController::setSeoImage()`, `PageController::setMedicalBusinessJsonLd()`

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/AboutPageTest.php`:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class AboutPageTest extends TestCase
{
    public function test_about_page_states_mission_and_who_is_served(): void
    {
        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee('accurate, efficient, and transparent medical billing solutions', false);
        $response->assertSee('general practitioners, specialists, and allied health practices', false);
    }
}
```

- [ ] **Step 2: Run the test to verify it fails**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=AboutPageTest
```

Expected: FAIL.

- [ ] **Step 3: Add the `about()` action to `PageController`**

```php
    public function about()
    {
        SEOTools::setTitle('About ClearClaims Health Accounts');
        SEOTools::setDescription('ClearClaims Health Accounts is a South African medical billing and practice support company built around accuracy, efficiency, confidentiality, and professional service.');
        $this->setMedicalBusinessJsonLd();
        $this->setSeoImage();

        return view('pages.about');
    }
```

- [ ] **Step 4: Add the about route**

Edit `routes/web.php`:

```php
Route::get('/about', [PageController::class, 'about'])->name('about');
```

- [ ] **Step 5: Write the About page view**

Create `resources/views/pages/about.blade.php`:

```blade
@extends('layouts.app')

@section('content')
    <section class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-b from-brand-50 to-white">
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-brand-900 sm:text-5xl">Medical Billing Support That Lets You Focus on Patients</h1>
        </div>
    </section>

    <section class="py-24">
        <div class="mx-auto max-w-4xl space-y-12 px-4 sm:px-6 lg:px-8">
            <div>
                <h2 class="text-2xl font-bold text-brand-900">Who We Are</h2>
                <p class="mt-4 text-slate-600">ClearClaims Health Accounts (Pty) Ltd is a medical billing and practice support company based in South Africa. We work with healthcare providers to improve cash flow, reduce administrative burden, and streamline the day to day work of managing medical claims.</p>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-brand-900">Our Mission</h2>
                <p class="mt-4 text-slate-600">To provide accurate, efficient, and transparent medical billing solutions that allow healthcare professionals to focus on patient care.</p>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-brand-900">Who We Serve</h2>
                <p class="mt-4 text-slate-600">We support general practitioners, specialists, and allied health practices who want their billing handled properly without hiring and managing an in-house billing team. Whether you are a solo practitioner or a multi-doctor practice, we adapt our process to fit how your practice already runs.</p>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-brand-900">Our Commitment</h2>
                <ul class="mt-4 space-y-3 text-slate-600">
                    <li><strong class="text-brand-900">Accuracy.</strong> We check every claim before it reaches a medical aid, so your practice sees fewer rejections.</li>
                    <li><strong class="text-brand-900">Efficiency.</strong> Claims move through submission, follow-up, and reconciliation without unnecessary delay.</li>
                    <li><strong class="text-brand-900">Confidentiality.</strong> Patient and practice information is handled with the discretion healthcare data demands.</li>
                    <li><strong class="text-brand-900">Professional service.</strong> Your practice is represented professionally in every interaction with medical aids.</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="border-t border-slate-200 bg-slate-50 py-24">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-brand-900">Want to Talk Through Your Practice's Billing?</h2>
            <a href="{{ route('contact') }}" class="mt-8 inline-flex items-center rounded-lg bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">Get in Touch</a>
        </div>
    </section>
@endsection
```

- [ ] **Step 6: Run the test to verify it passes**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=AboutPageTest
```

Expected: PASS.

- [ ] **Step 7: Commit**

```bash
git add -A
git commit -m "Add About page"
```

---

### Task 6: Contact page (view only, no form submission yet)

**Files:**
- Modify: `app/Http/Controllers/PageController.php` (add `contact()` method)
- Modify: `routes/web.php` (add GET contact route)
- Create: `resources/views/pages/contact.blade.php`
- Test: `tests/Feature/ContactPageTest.php`

**Interfaces:**
- Consumes: `PageController::setSeoImage()`, `PageController::setMedicalBusinessJsonLd()`
- Produces: the `<form action="{{ route('contact.submit') }}">` markup that Task 7 wires up to a real POST route.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/ContactPageTest.php`:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContactPageTest extends TestCase
{
    public function test_contact_page_shows_form_and_details(): void
    {
        $response = $this->get('/contact');

        $response->assertOk();
        $response->assertSee('071 339 5866', false);
        $response->assertSee('info@clearclaims.health', false);
        $response->assertSee('Fewer rejected claims', false);
    }
}
```

- [ ] **Step 2: Run the test to verify it fails**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=ContactPageTest
```

Expected: FAIL.

- [ ] **Step 3: Add the `contact()` action to `PageController`**

```php
    public function contact()
    {
        SEOTools::setTitle('Contact Us');
        SEOTools::setDescription('Get in touch with ClearClaims Health Accounts. Tell us about your practice and we will show you how our medical billing and practice support services can help.');
        $this->setMedicalBusinessJsonLd();
        $this->setSeoImage();

        return view('pages.contact');
    }
```

- [ ] **Step 4: Add the GET contact route**

Edit `routes/web.php`:

```php
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
```

Note: this route references `route('contact.submit')` in the view below, which does not exist until Task 7. The page will still render correctly because Blade only evaluates `route()` lazily when the template runs, and Task 7 adds that named route before this page is exercised again in the full test suite.

- [ ] **Step 5: Write the Contact page view**

Create `resources/views/pages/contact.blade.php`:

```blade
@extends('layouts.app')

@section('content')
    <section class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-b from-brand-50 to-white">
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-brand-900 sm:text-5xl">Let's Talk About Your Practice's Billing</h1>
            <p class="mt-4 max-w-2xl text-lg text-slate-600">Tell us a bit about your practice and we will get back to you to discuss how ClearClaims can help.</p>
        </div>
    </section>

    <section class="py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-16 lg:grid-cols-2">
                <div>
                    <h2 class="text-2xl font-bold text-brand-900">Send Us a Message</h2>
                    <p class="mt-2 text-slate-600">Fill out the form below and we will get back to you within 24 hours.</p>

                    @if(session('success'))
                        <div class="mt-6 rounded-lg border border-growth-200 bg-growth-50 p-4 text-sm text-growth-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                            <ul class="list-disc space-y-1 pl-4">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="mt-8 space-y-6">
                        @csrf
                        <x-honeypot />
                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-slate-700">First Name</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                                    class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 transition focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500">
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-slate-700">Last Name</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required
                                    class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 transition focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500">
                            </div>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 transition focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500">
                        </div>
                        <div>
                            <label for="company" class="block text-sm font-medium text-slate-700">Practice or Company Name</label>
                            <input type="text" id="company" name="company" value="{{ old('company') }}"
                                class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 transition focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500">
                        </div>
                        <div>
                            <label for="service" class="block text-sm font-medium text-slate-700">Service of Interest</label>
                            <select id="service" name="service"
                                class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 transition focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500">
                                <option value="">Select a service...</option>
                                <option value="submission">Medical Claims Submission and Processing</option>
                                <option value="followups">Medical Aid Follow-Ups and Collections</option>
                                <option value="reconciliation">Payment Reconciliation and Allocation</option>
                                <option value="accounts">Patient Account Management</option>
                                <option value="reporting">Practice Financial Reporting</option>
                                <option value="onboarding">Onboarding Support for New Practices</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-slate-700">Message</label>
                            <textarea id="message" name="message" rows="4" required
                                class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-slate-900 placeholder-slate-400 transition focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500"
                                placeholder="Tell us about your practice's billing needs...">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit"
                            class="inline-flex w-full items-center justify-center rounded-lg bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700 sm:w-auto">
                            Send Message
                        </button>
                    </form>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-brand-900">Get in Touch</h2>
                    <p class="mt-2 text-slate-600">Prefer to reach out directly? Here is how you can contact us.</p>

                    <div class="mt-8 space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-brand-50">
                                <svg class="h-6 w-6 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-brand-900">Email</h3>
                                <p class="mt-1 text-slate-600">info@clearclaims.health</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-growth-50">
                                <svg class="h-6 w-6 text-growth-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-brand-900">Phone</h3>
                                <p class="mt-1 text-slate-600">071 339 5866</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-brand-50">
                                <svg class="h-6 w-6 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-brand-900">Location</h3>
                                <p class="mt-1 text-slate-600">South Africa</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 rounded-xl border border-slate-200 bg-slate-50 p-8">
                        <h3 class="text-lg font-semibold text-brand-900">Why Choose ClearClaims?</h3>
                        <ul class="mt-4 space-y-3">
                            <li class="flex items-start gap-3">
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-growth-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-sm text-slate-600">Faster payments and improved cash flow</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-growth-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-sm text-slate-600">Reduced administrative workload</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-growth-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-sm text-slate-600">Fewer rejected claims</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-growth-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-sm text-slate-600">Professional handling of accounts</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-growth-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-sm text-slate-600">Transparent reporting and communication</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
```

- [ ] **Step 6: Run the test to verify it passes**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=ContactPageTest
```

Expected: PASS.

- [ ] **Step 7: Commit**

```bash
git add -A
git commit -m "Add Contact page view with form markup"
```

---

### Task 7: Contact form backend workflow (Event, Listener, Notification, honeypot, rate limiting)

**Files:**
- Create: `app/Http/Requests/ContactRequest.php`
- Create: `app/Events/ContactFormSubmitted.php`
- Create: `app/Listeners/SendContactFormNotification.php`
- Create: `app/Notifications/ContactFormNotification.php`
- Create: `resources/views/mail/contact-form.blade.php`
- Create: `app/Http/Controllers/ContactController.php`
- Modify: `routes/web.php` (add POST contact route)
- Modify: `app/Providers/AppServiceProvider.php` or `app/Providers/EventServiceProvider.php` as appropriate for Laravel 13's event discovery (see Step 5)
- Test: `tests/Feature/ContactFormSubmissionTest.php`

**Interfaces:**
- Produces: `ContactFormSubmitted` event with public readonly `firstName`, `lastName`, `email`, `company`, `service`, `message` properties (mirrors Phungo's event shape exactly, so the pattern stays recognizable).
- Produces: named route `contact.submit` referenced by Task 6's form.

- [ ] **Step 1: Write the failing tests**

Create `tests/Feature/ContactFormSubmissionTest.php`:

```php
<?php

namespace Tests\Feature;

use App\Events\ContactFormSubmitted;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ContactFormSubmissionTest extends TestCase
{
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'company' => 'Smith Family Practice',
            'service' => 'submission',
            'message' => 'We would like to discuss moving our billing to ClearClaims.',
        ], $overrides);
    }

    public function test_valid_submission_dispatches_event_and_redirects_with_success(): void
    {
        Event::fake([ContactFormSubmitted::class]);

        $response = $this->post('/contact', $this->validPayload());

        $response->assertRedirect();
        $response->assertSessionHas('success');
        Event::assertDispatched(ContactFormSubmitted::class, function (ContactFormSubmitted $event) {
            return $event->firstName === 'Jane'
                && $event->lastName === 'Smith'
                && $event->email === 'jane@example.com';
        });
    }

    public function test_missing_required_fields_fail_validation(): void
    {
        $response = $this->post('/contact', $this->validPayload(['first_name' => '']));

        $response->assertSessionHasErrors('first_name');
    }

    public function test_submission_notifies_clearclaims_inbox(): void
    {
        Notification::fake();

        $this->post('/contact', $this->validPayload());

        Notification::assertSentOnDemand(
            \App\Notifications\ContactFormNotification::class,
            function ($notification, $channels, $notifiable) {
                return in_array('info@clearclaims.health', $notifiable->routes['mail']);
            }
        );
    }

    public function test_honeypot_field_blocks_bot_submissions(): void
    {
        Event::fake([ContactFormSubmitted::class]);

        $payload = $this->validPayload();
        $payload['my_name'] = 'a spam bot filled this in';

        $this->post('/contact', $payload);

        Event::assertNotDispatched(ContactFormSubmitted::class);
    }

    public function test_contact_route_is_rate_limited(): void
    {
        Event::fake([ContactFormSubmitted::class]);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/contact', $this->validPayload());
        }

        $response = $this->post('/contact', $this->validPayload());

        $response->assertStatus(429);
    }
}
```

- [ ] **Step 2: Run the tests to verify they fail**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=ContactFormSubmissionTest
```

Expected: FAIL (route `/contact` POST doesn't exist yet).

- [ ] **Step 3: Create the form request**

Create `app/Http/Requests/ContactRequest.php`:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'service' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }
}
```

- [ ] **Step 4: Create the event**

Create `app/Events/ContactFormSubmitted.php`:

```php
<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContactFormSubmitted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly ?string $company,
        public readonly ?string $service,
        public readonly string $message,
    ) {}
}
```

- [ ] **Step 5: Create the listener and register it**

Create `app/Listeners/SendContactFormNotification.php`:

```php
<?php

namespace App\Listeners;

use App\Events\ContactFormSubmitted;
use App\Notifications\ContactFormNotification;
use Illuminate\Support\Facades\Notification;

class SendContactFormNotification
{
    public function handle(ContactFormSubmitted $event): void
    {
        Notification::route('mail', 'info@clearclaims.health')
            ->notify(new ContactFormNotification($event));
    }
}
```

Laravel 13 auto-discovers listeners whose `handle()` method is type-hinted with the event, via the `AppServiceProvider::boot()` event auto-discovery that ships with the framework's default skeleton. Verify this is active by checking `bootstrap/app.php` and `app/Providers/AppServiceProvider.php` don't disable it. No manual registration should be required, but confirm in Step 8 by running the test and checking the listener fires.

- [ ] **Step 6: Create the mail notification**

Create `app/Notifications/ContactFormNotification.php`:

```php
<?php

namespace App\Notifications;

use App\Events\ContactFormSubmitted;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactFormNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly ContactFormSubmitted $event,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Contact Form Submission from '.$this->event->firstName.' '.$this->event->lastName)
            ->markdown('mail.contact-form', [
                'firstName' => $this->event->firstName,
                'lastName' => $this->event->lastName,
                'email' => $this->event->email,
                'company' => $this->event->company,
                'service' => $this->event->service,
                'body' => $this->event->message,
            ]);
    }
}
```

- [ ] **Step 7: Create the mail view**

Create `resources/views/mail/contact-form.blade.php`:

```blade
<x-mail::message>
# New Enquiry Received

A new contact form submission has been received from the ClearClaims website.

<x-mail::panel>
**{{ $firstName }} {{ $lastName }}** |
{{ $email }} |
@if($company)
{{ $company }}
@endif
</x-mail::panel>

@if($service)
**Service of Interest:** {{ $service }}

@endif
**Message:**

{{ $body }}

<x-mail::button :url="'mailto:'.$email">
Reply to {{ $firstName }}
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
```

- [ ] **Step 8: Create the contact controller**

Create `app/Http/Controllers/ContactController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Events\ContactFormSubmitted;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    public function submit(ContactRequest $request)
    {
        ContactFormSubmitted::dispatch(
            firstName: $request->validated('first_name'),
            lastName: $request->validated('last_name'),
            email: $request->validated('email'),
            company: $request->validated('company'),
            service: $request->validated('service'),
            message: $request->validated('message'),
        );

        return back()->with('success', 'Thank you for your message. We will be in touch shortly.');
    }
}
```

- [ ] **Step 9: Add the POST contact route with honeypot and rate limiting**

Edit `routes/web.php`, adding the import and route:

```php
use App\Http\Controllers\ContactController;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::post('/contact', [ContactController::class, 'submit'])
    ->middleware([ProtectAgainstSpam::class, 'throttle:5,1'])
    ->name('contact.submit');
```

- [ ] **Step 10: Run the tests to verify they pass**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=ContactFormSubmissionTest
```

Expected: PASS (5 tests). If the honeypot test fails because the field name differs from `my_name`, check `config/honeypot.php`'s `nameFieldName` value and adjust the test payload key to match.

- [ ] **Step 11: Run the full test suite so far**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test
```

Expected: all tests pass, including Tasks 2 through 6's page tests (the Contact page's `route('contact.submit')` reference now resolves).

- [ ] **Step 12: Commit**

```bash
git add -A
git commit -m "Add contact form backend workflow with honeypot and rate limiting"
```

---

### Task 8: Security headers and forced HTTPS

**Files:**
- Create: `app/Http/Middleware/SecurityHeaders.php`
- Modify: `bootstrap/app.php` (register the middleware globally)
- Modify: `app/Providers/AppServiceProvider.php` (force HTTPS outside `local`)
- Test: `tests/Feature/SecurityHeadersTest.php`

**Interfaces:**
- Produces: every HTTP response carries the headers listed in the Global Constraints section.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/SecurityHeadersTest.php`:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    public function test_responses_include_security_headers(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->assertHeader('Content-Security-Policy');
    }
}
```

- [ ] **Step 2: Run the test to verify it fails**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=SecurityHeadersTest
```

Expected: FAIL (headers not present).

- [ ] **Step 3: Create the middleware**

Create `app/Http/Middleware/SecurityHeaders.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.bunny.net; font-src 'self' https://fonts.bunny.net; img-src 'self' data:; connect-src 'self';"
        );

        return $response;
    }
}
```

- [ ] **Step 4: Register the middleware globally**

Edit `bootstrap/app.php`, inside the `->withMiddleware(function (Middleware $middleware) { ... })` callback (add the import for `App\Http\Middleware\SecurityHeaders` at the top of the file):

```php
$middleware->append(\App\Http\Middleware\SecurityHeaders::class);
```

- [ ] **Step 5: Force HTTPS outside local**

Edit `app/Providers/AppServiceProvider.php`, inside the `boot()` method:

```php
use Illuminate\Support\Facades\URL;

public function boot(): void
{
    if (! $this->app->environment('local')) {
        URL::forceScheme('https');
    }
}
```

- [ ] **Step 6: Run the test to verify it passes**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=SecurityHeadersTest
```

Expected: PASS.

- [ ] **Step 7: Commit**

```bash
git add -A
git commit -m "Add security headers middleware and forced HTTPS outside local"
```

---

### Task 9: Sitemap and robots.txt

**Files:**
- Create: `public/sitemap.xml`
- Modify: `public/robots.txt`
- Test: `tests/Feature/SeoFilesTest.php`

**Interfaces:**
- Produces: `https://clear-claims.test/sitemap.xml` and `https://clear-claims.test/robots.txt`, both served as static files by Laravel's default public-directory routing (no controller needed).

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/SeoFilesTest.php`:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class SeoFilesTest extends TestCase
{
    public function test_sitemap_lists_all_pages(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertSee('https://clearclaims.health/', false);
        $response->assertSee('https://clearclaims.health/services', false);
        $response->assertSee('https://clearclaims.health/pricing', false);
        $response->assertSee('https://clearclaims.health/about', false);
        $response->assertSee('https://clearclaims.health/contact', false);
    }

    public function test_robots_txt_allows_all_crawlers(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk();
        $response->assertSee('User-agent: *', false);
        $response->assertSee('Sitemap: https://clearclaims.health/sitemap.xml', false);
    }
}
```

- [ ] **Step 2: Run the test to verify it fails**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=SeoFilesTest
```

Expected: FAIL (`public/sitemap.xml` doesn't exist yet, `robots.txt` doesn't have the Sitemap line).

- [ ] **Step 3: Create the sitemap**

Create `public/sitemap.xml`:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>https://clearclaims.health/</loc>
        <changefreq>monthly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>https://clearclaims.health/services</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>https://clearclaims.health/pricing</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>https://clearclaims.health/about</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>https://clearclaims.health/contact</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
</urlset>
```

- [ ] **Step 4: Update robots.txt**

Replace the contents of `public/robots.txt`:

```
User-agent: *
Disallow:
Sitemap: https://clearclaims.health/sitemap.xml
```

- [ ] **Step 5: Run the test to verify it passes**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=SeoFilesTest
```

Expected: PASS.

- [ ] **Step 6: Commit**

```bash
git add -A
git commit -m "Add sitemap.xml and update robots.txt"
```

---

### Task 10: Signature GSAP arrow motif and scroll animations

**Files:**
- Create: `resources/views/components/arrow-motif.blade.php`
- Create: `resources/js/animations.js`
- Modify: `resources/js/app.js` (import GSAP setup)
- Modify: `resources/views/pages/home.blade.php` (place the arrow motif in the hero and pricing sections)
- Modify: `resources/views/pages/pricing.blade.php` (place the arrow motif in the "How the model works" section)

**Interfaces:**
- Produces: `<x-arrow-motif class="..." />` Blade component, an inline SVG with `data-arrow-path` used by `resources/js/animations.js`.

- [ ] **Step 1: Create the arrow motif SVG component**

Create `resources/views/components/arrow-motif.blade.php`:

```blade
@props(['class' => 'h-32 w-full'])

<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 400 120" fill="none" xmlns="http://www.w3.org/2000/svg" data-arrow-motif>
    <path
        data-arrow-path
        d="M10 100 C 80 100, 100 60, 160 60 S 240 20, 300 20 L 370 20"
        stroke="url(#arrow-gradient)"
        stroke-width="4"
        stroke-linecap="round"
        fill="none"
    />
    <path d="M355 8 L 380 20 L 355 32 Z" fill="#29a467" data-arrow-head />
    <defs>
        <linearGradient id="arrow-gradient" x1="0" y1="0" x2="400" y2="0" gradientUnits="userSpaceOnUse">
            <stop offset="0%" stop-color="#2f7abf" />
            <stop offset="100%" stop-color="#29a467" />
        </linearGradient>
    </defs>
</svg>
```

- [ ] **Step 2: Write the GSAP scroll animation script**

Create `resources/js/animations.js`:

```javascript
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-arrow-motif]').forEach((svg) => {
        const path = svg.querySelector('[data-arrow-path]');
        const head = svg.querySelector('[data-arrow-head]');
        const length = path.getTotalLength();

        gsap.set(path, { strokeDasharray: length, strokeDashoffset: length });
        gsap.set(head, { opacity: 0 });

        gsap.timeline({
            scrollTrigger: {
                trigger: svg,
                start: 'top 80%',
                once: true,
            },
        })
            .to(path, { strokeDashoffset: 0, duration: 1.4, ease: 'power2.out' })
            .to(head, { opacity: 1, duration: 0.3 }, '-=0.2');
    });

    document.querySelectorAll('main section').forEach((section) => {
        gsap.from(section.querySelectorAll('h1, h2, h3, p, .rounded-xl, form'), {
            scrollTrigger: {
                trigger: section,
                start: 'top 85%',
                once: true,
            },
            opacity: 0,
            y: 24,
            duration: 0.6,
            stagger: 0.08,
            ease: 'power2.out',
        });
    });
});
```

- [ ] **Step 3: Import the animation script in the main JS entry point**

Edit `resources/js/app.js`, appending:

```javascript
import './animations.js';
```

- [ ] **Step 4: Place the arrow motif in the Home page hero and pricing teaser**

Edit `resources/views/pages/home.blade.php`, adding the component just below the hero's CTA buttons `</div>` (inside the hero `<section>`):

```blade
            <div class="mt-16">
                <x-arrow-motif class="h-24 w-full max-w-2xl" />
            </div>
```

And inside the pricing teaser section, just above the "See How Our Pricing Works" link's closing `</div>`:

```blade
            <div class="mt-10 flex justify-center">
                <x-arrow-motif class="h-20 w-full max-w-md" />
            </div>
```

- [ ] **Step 5: Place the arrow motif on the Pricing page**

Edit `resources/views/pages/pricing.blade.php`, adding the component just below the "How the Percentage of Collections Model Works" section's second paragraph:

```blade
            <div class="mt-10">
                <x-arrow-motif class="h-24 w-full" />
            </div>
```

- [ ] **Step 6: Build assets and verify the build succeeds**

```bash
cd /Users/morgz/Code/clear-claims
npm run build
```

Expected: build completes with no errors, and `public/build/manifest.json` is created.

- [ ] **Step 7: Run the full test suite**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test
```

Expected: all tests still pass (the animation script doesn't change server-rendered HTML content that tests assert on).

- [ ] **Step 8: Manually verify the animation in a browser**

```bash
cd /Users/morgz/Code/clear-claims
npm run dev
```

Open `https://clear-claims.test` in a browser, scroll through the hero and pricing teaser, and confirm the arrow line draws itself in on scroll and section content fades/slides in. Stop the dev server (Ctrl+C) once confirmed.

- [ ] **Step 9: Commit**

```bash
git add -A
git commit -m "Add signature GSAP arrow motif and scroll-triggered section animations"
```

---

## Final verification

After Task 10, run the complete suite once more and confirm the site is reachable:

```bash
cd /Users/morgz/Code/clear-claims
php artisan test
curl -sk -o /dev/null -w "%{http_code}\n" https://clear-claims.test
curl -sk -o /dev/null -w "%{http_code}\n" https://clear-claims.test/services
curl -sk -o /dev/null -w "%{http_code}\n" https://clear-claims.test/pricing
curl -sk -o /dev/null -w "%{http_code}\n" https://clear-claims.test/about
curl -sk -o /dev/null -w "%{http_code}\n" https://clear-claims.test/contact
```

Expected: all tests pass, and every curl call returns `200`.
