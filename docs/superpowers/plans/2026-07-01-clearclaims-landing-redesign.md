# ClearClaims Warm Human-Centered Redesign Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Re-theme the existing ClearClaims Laravel site (all 5 pages, shared layout) from its current generic Tailwind-template look to the "Warm Human-Centered Healthcare" direction: organic blob shapes, a cream/peach canvas on hero and CTA sections, pill-shaped buttons sitewide, and a larger logo, while keeping dense content sections (service grid, pricing table, FAQ, contact form) clean and unchanged in structure.

**Architecture:** Presentation-layer only. Add warm-neutral theme tokens to the existing Tailwind v4 `@theme` block, build four small reusable Blade components (`warm-hero`, `warm-cta`, `warm-chart-card`, `warm-floating-chip`) that encapsulate the decorative chrome, and swap each page's hero/CTA sections to use them. No controllers, routes, events, notifications, or tests covering backend behavior change.

**Tech Stack:** Laravel 13 Blade components, Tailwind CSS v4 (CSS-first `@theme`), GSAP (existing `resources/js/animations.js`), PHPUnit feature tests asserting on rendered HTML.

## Global Constraints

- New theme tokens (exact hex values): `--color-warm-bg: #FBF3E6`, `--color-warm-surface: #FFFDF8`, `--color-warm-border: #EDDFC4`, `--color-warm-text: #5B4C3F`, `--color-peach-300: #F6D9A8`, `--color-peach-500: #EFC48A`. Existing `brand` and `growth` palettes are unchanged.
- All buttons sitewide change from `rounded-lg` to `rounded-full` (pill shape). This includes nav CTA, mobile menu CTA, every page's hero/CTA buttons, and the contact form's submit button.
- The warm/organic treatment (blobs, cream backgrounds, wave dividers) applies ONLY to hero sections and closing CTA sections. Dense content sections (6-service grid, pricing comparison table, FAQ, how-it-works steps, About commitment list, the contact form fields) stay on white/light backgrounds with the existing `slate` body text, unchanged in structure.
- Logo size increases from `h-16` to `h-20` in both the nav and the footer (`resources/views/layouts/app.blade.php`).
- Home's pricing-teaser band changes from a full-bleed `bg-brand-900` rectangle to a rounded island card (`rounded-[2.5rem]`) on a cream background, keeping the navy fill and its arrow-motif chart, but no longer edge-to-edge.
- The existing GSAP arrow-motif component (`resources/views/components/arrow-motif.blade.php`) and its animation logic (`resources/js/animations.js`) are NOT modified in structure, only re-housed inside the new `warm-chart-card` wrapper where it appears (Home hero, Home pricing island, Pricing page's "how it works" section).
- No em dashes, no semicolons, no fabricated testimonials or stats anywhere — carried over from the original site spec, still binding. Any new copy (e.g. the floating chip) must use only claims already established elsewhere on the site.
- No changes to `app/Http/Controllers/*`, `routes/web.php`, `app/Events/*`, `app/Listeners/*`, `app/Notifications/*`, `app/Http/Middleware/*`, or `config/*`.

---

### Task 1: Theme tokens, nav/footer re-theme, logo size increase

**Files:**
- Modify: `resources/css/app.css`
- Modify: `resources/views/layouts/app.blade.php`
- Test: `tests/Feature/LayoutRedesignTest.php`

**Interfaces:**
- Produces: Tailwind utility classes `bg-warm-bg`, `bg-warm-surface`, `border-warm-border`, `text-warm-text`, `bg-peach-300`, `bg-peach-500` (and their opacity-modifier variants like `bg-peach-500/40`), usable by every later task.

- [ ] **Step 1: Write the failing test**

Create `tests/Feature/LayoutRedesignTest.php`:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class LayoutRedesignTest extends TestCase
{
    public function test_nav_and_footer_use_the_warm_theme_and_larger_logo(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('bg-warm-surface/90', false);
        $response->assertSee('border-warm-border', false);
        $response->assertSee('h-20 w-auto', false);
        $response->assertSee('bg-warm-bg', false);
    }

    public function test_nav_cta_is_a_pill_button(): void
    {
        $response = $this->get('/');

        $response->assertSee('rounded-full', false);
    }
}
```

- [ ] **Step 2: Run the test to verify it fails**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=LayoutRedesignTest
```

Expected: FAIL (none of the warm classes exist yet, nav still uses `bg-white/90`, logo still `h-16`).

- [ ] **Step 3: Add the warm theme tokens**

Edit `resources/css/app.css`, adding these lines inside the existing `@theme { ... }` block, after the `--color-growth-900` line:

```css
    --color-warm-bg: #FBF3E6;
    --color-warm-surface: #FFFDF8;
    --color-warm-border: #EDDFC4;
    --color-warm-text: #5B4C3F;

    --color-peach-300: #F6D9A8;
    --color-peach-500: #EFC48A;
```

- [ ] **Step 4: Re-theme the nav**

Edit `resources/views/layouts/app.blade.php`. Replace this line:

```blade
    <nav class="fixed top-0 z-50 w-full border-b border-slate-200 bg-white/90 backdrop-blur-xl" x-data="{ open: false }">
```

with:

```blade
    <nav class="fixed top-0 z-50 w-full border-b border-warm-border bg-warm-surface/90 backdrop-blur-xl" x-data="{ open: false }">
```

- [ ] **Step 5: Increase the nav logo size**

Replace this line (inside the nav, the first `<img>` tag):

```blade
                    <img src="{{ asset('images/logo.png') }}" alt="ClearClaims Health Accounts" class="h-16 w-auto">
```

with:

```blade
                    <img src="{{ asset('images/logo.png') }}" alt="ClearClaims Health Accounts" class="h-20 w-auto">
```

- [ ] **Step 6: Make the nav CTA and mobile menu CTA pill-shaped**

Replace this line:

```blade
                    <a href="{{ route('contact') }}" class="inline-flex items-center rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-700">Contact Us</a>
```

with:

```blade
                    <a href="{{ route('contact') }}" class="inline-flex items-center rounded-full bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-700">Contact Us</a>
```

Replace this line (in the mobile menu panel):

```blade
                <a href="{{ route('contact') }}" class="block rounded-lg bg-brand-600 px-3 py-2 text-center text-base font-semibold text-white">Contact Us</a>
```

with:

```blade
                <a href="{{ route('contact') }}" class="block rounded-full bg-brand-600 px-3 py-2 text-center text-base font-semibold text-white">Contact Us</a>
```

- [ ] **Step 7: Re-theme the footer and increase its logo size**

Replace this line:

```blade
    <footer class="border-t border-slate-200 bg-slate-50">
```

with:

```blade
    <footer class="border-t border-warm-border bg-warm-bg">
```

Replace the footer's logo line:

```blade
                        <img src="{{ asset('images/logo.png') }}" alt="ClearClaims Health Accounts" class="h-16 w-auto">
```

with:

```blade
                        <img src="{{ asset('images/logo.png') }}" alt="ClearClaims Health Accounts" class="h-20 w-auto">
```

- [ ] **Step 8: Run the test to verify it passes**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=LayoutRedesignTest
```

Expected: PASS (2 tests).

- [ ] **Step 9: Run the full suite to check for regressions**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test
```

Expected: all existing tests still pass (they assert on copy/content, not on the `rounded-lg`/`rounded-full` or color classes that just changed).

- [ ] **Step 10: Commit**

```bash
git add -A
git commit -m "Re-theme nav and footer with warm palette and larger logo"
```

---

### Task 2: Home hero — warm-hero, warm-chart-card, warm-floating-chip components

**Files:**
- Create: `resources/views/components/warm-hero.blade.php`
- Create: `resources/views/components/warm-chart-card.blade.php`
- Create: `resources/views/components/warm-floating-chip.blade.php`
- Modify: `resources/views/pages/home.blade.php` (hero section only)
- Test: `tests/Feature/HomePageTest.php` (add assertions, do not remove existing ones)

**Interfaces:**
- Produces: `<x-warm-hero>` — full-width decorative hero wrapper (blobs + wave divider), takes a default slot rendered inside a `max-w-7xl` container.
- Produces: `<x-warm-chart-card>` — rounded warm-surface card wrapper for light (non-dark) contexts, takes a default slot, accepts extra classes via normal Blade attribute merging (e.g. `<x-warm-chart-card class="mt-4">`).
- Produces: `<x-warm-floating-chip icon="check">` — small pill-shaped floating card, takes a default slot for its label text, accepts extra classes via attribute merging (used for positioning).
- Consumes: existing `<x-arrow-motif>` component (unchanged), Task 1's warm theme tokens.

- [ ] **Step 1: Write the failing test**

Edit `tests/Feature/HomePageTest.php`, adding a new test method inside the class (keep the two existing test methods as-is):

```php
    public function test_home_hero_uses_warm_components_and_honest_floating_chip(): void
    {
        $response = $this->get('/');

        $response->assertSee('data-blob-drift', false);
        $response->assertSee('You only pay when we collect', false);
        $response->assertSee('rounded-3xl border border-warm-border bg-warm-surface', false);
    }
```

- [ ] **Step 2: Run the test to verify it fails**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=HomePageTest
```

Expected: FAIL on the new test (none of these components/classes exist yet). The two pre-existing tests still pass.

- [ ] **Step 3: Create the warm-hero component**

Create `resources/views/components/warm-hero.blade.php`:

```blade
<section class="relative overflow-hidden bg-gradient-to-b from-warm-bg to-white">
    <div class="pointer-events-none absolute -left-32 -top-40 h-96 w-96 rounded-full bg-brand-400/30 blur-3xl" data-blob-drift></div>
    <div class="pointer-events-none absolute -right-24 -top-52 h-[28rem] w-[28rem] rounded-full bg-peach-500/40 blur-3xl" data-blob-drift></div>
    <div class="pointer-events-none absolute bottom-10 left-1/3 h-64 w-64 rounded-full bg-growth-400/20 blur-3xl" data-blob-drift></div>

    <div class="relative mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
        {{ $slot }}
    </div>

    <svg class="relative block w-full text-white" viewBox="0 0 1440 60" preserveAspectRatio="none" style="height: 48px;" aria-hidden="true">
        <path d="M0 24 C 240 54, 480 0, 720 18 C 960 36, 1200 6, 1440 30 L 1440 60 L 0 60 Z" fill="currentColor"/>
    </svg>
</section>
```

- [ ] **Step 4: Create the warm-chart-card component**

Create `resources/views/components/warm-chart-card.blade.php`:

```blade
<div {{ $attributes->merge(['class' => 'rounded-3xl border border-warm-border bg-warm-surface p-6 shadow-xl shadow-brand-900/10']) }}>
    {{ $slot }}
</div>
```

- [ ] **Step 5: Create the warm-floating-chip component**

Create `resources/views/components/warm-floating-chip.blade.php`:

```blade
@props(['icon' => 'check'])

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-3 rounded-2xl border border-warm-border bg-warm-surface px-4 py-3 shadow-lg shadow-brand-900/10']) }} data-float-chip>
    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-growth-500/15 text-growth-600">
        @if($icon === 'check')
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        @endif
    </span>
    <span class="text-sm font-semibold text-warm-text">{{ $slot }}</span>
</div>
```

- [ ] **Step 6: Replace the Home hero section**

Edit `resources/views/pages/home.blade.php`. Replace the entire Hero section (from `{{-- Hero --}}` through its closing `</section>`, i.e. everything currently between the `@section('content')` line and the `{{-- Services overview --}}` comment):

```blade
    {{-- Hero --}}
    <section class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-b from-brand-50 to-white">
        <div class="relative mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <h1 class="max-w-3xl text-4xl font-bold text-brand-900 sm:text-5xl">Get Paid Faster. Do Less Admin. Stay Focused on Patients.</h1>
            <p class="mt-6 max-w-2xl text-lg text-slate-600">ClearClaims Health Accounts handles medical billing and practice support so your team can spend less time chasing medical aids and more time treating patients.</p>
            <div class="mt-10 flex flex-wrap gap-4">
                <a href="{{ route('contact') }}" class="inline-flex items-center rounded-lg bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">Book a Free Consultation</a>
                <a href="{{ route('pricing') }}" class="inline-flex items-center rounded-lg border border-slate-300 px-8 py-3.5 text-base font-semibold text-slate-700 transition hover:border-brand-400 hover:text-brand-700">See Our Pricing Model</a>
            </div>
            <div class="mt-16">
                <x-arrow-motif class="h-24 w-full max-w-2xl" />
            </div>
        </div>
    </section>
```

with:

```blade
    {{-- Hero --}}
    <x-warm-hero>
        <div class="grid items-center gap-12 lg:grid-cols-2">
            <div>
                <h1 class="max-w-xl text-4xl font-bold text-brand-900 sm:text-5xl">Get Paid Faster. Do Less Admin. Stay Focused on Patients.</h1>
                <p class="mt-6 max-w-xl text-lg text-slate-600">ClearClaims Health Accounts handles medical billing and practice support so your team can spend less time chasing medical aids and more time treating patients.</p>
                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ route('contact') }}" class="inline-flex items-center rounded-full bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">Book a Free Consultation</a>
                    <a href="{{ route('pricing') }}" class="inline-flex items-center rounded-full border border-warm-border bg-warm-surface px-8 py-3.5 text-base font-semibold text-slate-700 transition hover:border-brand-400 hover:text-brand-700">See Our Pricing Model</a>
                </div>
            </div>
            <div class="relative">
                <x-warm-chart-card>
                    <p class="text-xs font-semibold uppercase tracking-wider text-warm-text/70">Our Pricing Model</p>
                    <p class="mt-1 text-lg font-semibold text-brand-900">Percentage of Collections</p>
                    <x-arrow-motif class="mt-4 h-20 w-full" />
                </x-warm-chart-card>
                <x-warm-floating-chip class="absolute -bottom-6 -left-6 hidden sm:inline-flex">
                    You only pay when we collect
                </x-warm-floating-chip>
            </div>
        </div>
    </x-warm-hero>
```

- [ ] **Step 7: Run the test to verify it passes**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=HomePageTest
```

Expected: PASS (3 tests).

- [ ] **Step 8: Commit**

```bash
git add -A
git commit -m "Redesign Home hero with warm-hero, warm-chart-card, and floating chip components"
```

---

### Task 3: Home pricing-teaser island card and warm-cta component

**Files:**
- Create: `resources/views/components/warm-cta.blade.php`
- Modify: `resources/views/pages/home.blade.php` (pricing teaser and closing CTA sections)
- Test: `tests/Feature/HomePageTest.php`

**Interfaces:**
- Produces: `<x-warm-cta>` — cream/organic closing-CTA section wrapper, takes a default slot for heading/paragraph/button.
- Consumes: Task 2's `<x-warm-chart-card>` is intentionally NOT reused here (dark navy background needs a translucent card treatment, not the light warm-surface card), Task 1's theme tokens.

- [ ] **Step 1: Write the failing test**

Edit `tests/Feature/HomePageTest.php`, adding a new test method:

```php
    public function test_pricing_teaser_is_a_rounded_island_and_closing_cta_uses_warm_treatment(): void
    {
        $response = $this->get('/');

        $response->assertSee('rounded-[2.5rem] bg-brand-900', false);
        $response->assertSee('bg-warm-bg py-24', false);
    }
```

- [ ] **Step 2: Run the test to verify it fails**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=HomePageTest
```

Expected: FAIL on the new test.

- [ ] **Step 3: Create the warm-cta component**

Create `resources/views/components/warm-cta.blade.php`:

```blade
<section class="bg-white py-24">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-warm-bg to-peach-300/40 px-8 py-16 text-center sm:px-16">
            <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full bg-brand-400/20 blur-3xl" data-blob-drift></div>
            <div class="pointer-events-none absolute -left-10 bottom-0 h-40 w-40 rounded-full bg-growth-400/25 blur-3xl" data-blob-drift></div>
            <div class="relative">
                {{ $slot }}
            </div>
        </div>
    </div>
</section>
```

- [ ] **Step 4: Replace the pricing-teaser section**

Edit `resources/views/pages/home.blade.php`. Replace the Pricing teaser section:

```blade
    {{-- Pricing teaser --}}
    <section class="border-y border-slate-200 bg-brand-900 py-24 text-white">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold">We Only Get Paid When You Get Paid</h2>
            <p class="mt-6 text-lg text-brand-100">ClearClaims works on a percentage of collections model. Our fee is calculated on the money medical aids actually pay out to your practice, not on the claims we submit. If a claim is rejected or never paid, we do not charge for it. That keeps our incentives lined up with yours from the first submission to the final reconciled payment.</p>
            <a href="{{ route('pricing') }}" class="mt-8 inline-flex items-center rounded-lg bg-growth-500 px-8 py-3.5 text-base font-semibold text-white transition hover:bg-growth-600">See How Our Pricing Works</a>
            <div class="mt-10 flex justify-center">
                <x-arrow-motif class="h-20 w-full max-w-md" />
            </div>
        </div>
    </section>
```

with:

```blade
    {{-- Pricing teaser --}}
    <section class="bg-warm-bg py-24">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2.5rem] bg-brand-900 px-8 py-16 text-center text-white sm:px-16">
                <h2 class="text-3xl font-bold">We Only Get Paid When You Get Paid</h2>
                <p class="mt-6 text-lg text-brand-100">ClearClaims works on a percentage of collections model. Our fee is calculated on the money medical aids actually pay out to your practice, not on the claims we submit. If a claim is rejected or never paid, we do not charge for it. That keeps our incentives lined up with yours from the first submission to the final reconciled payment.</p>
                <a href="{{ route('pricing') }}" class="mt-8 inline-flex items-center rounded-full bg-growth-500 px-8 py-3.5 text-base font-semibold text-white transition hover:bg-growth-600">See How Our Pricing Works</a>
                <div class="mt-10 flex justify-center">
                    <div class="rounded-3xl border border-white/15 bg-white/10 p-6 backdrop-blur-sm">
                        <x-arrow-motif class="h-20 w-full max-w-md" />
                    </div>
                </div>
            </div>
        </div>
    </section>
```

- [ ] **Step 5: Replace the closing CTA section**

Replace:

```blade
    {{-- Closing CTA --}}
    <section class="py-24">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-brand-900">Ready to Spend Less Time on Billing?</h2>
            <p class="mt-4 text-slate-600">Tell us about your practice and we will show you what ClearClaims can take off your plate.</p>
            <a href="{{ route('contact') }}" class="mt-8 inline-flex items-center rounded-lg bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">Book a Free Consultation</a>
        </div>
    </section>
```

with:

```blade
    {{-- Closing CTA --}}
    <x-warm-cta>
        <h2 class="text-3xl font-bold text-brand-900">Ready to Spend Less Time on Billing?</h2>
        <p class="mt-4 text-slate-700">Tell us about your practice and we will show you what ClearClaims can take off your plate.</p>
        <a href="{{ route('contact') }}" class="mt-8 inline-flex items-center rounded-full bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">Book a Free Consultation</a>
    </x-warm-cta>
```

- [ ] **Step 6: Run the test to verify it passes**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=HomePageTest
```

Expected: PASS (4 tests).

- [ ] **Step 7: Run the full suite**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test
```

Expected: all tests pass.

- [ ] **Step 8: Commit**

```bash
git add -A
git commit -m "Convert Home pricing teaser to a rounded island card and add warm-cta component"
```

---

### Task 4: Services page hero and closing CTA

**Files:**
- Modify: `resources/views/pages/services.blade.php`
- Test: `tests/Feature/ServicesPageTest.php`

**Interfaces:**
- Consumes: `<x-warm-hero>` and `<x-warm-cta>` from Tasks 2 and 3.

- [ ] **Step 1: Write the failing test**

Edit `tests/Feature/ServicesPageTest.php`, adding a new test method:

```php
    public function test_services_hero_and_cta_use_warm_treatment(): void
    {
        $response = $this->get('/services');

        $response->assertSee('data-blob-drift', false);
        $response->assertSee('rounded-full', false);
    }
```

- [ ] **Step 2: Run the test to verify it fails**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=ServicesPageTest
```

Expected: FAIL.

- [ ] **Step 3: Replace the Services hero section**

Edit `resources/views/pages/services.blade.php`. Replace:

```blade
    <section class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-b from-brand-50 to-white">
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-brand-900 sm:text-5xl">Our Services</h1>
            <p class="mt-4 max-w-2xl text-lg text-slate-600">Everything your practice needs to submit, chase, reconcile, and report on medical claims, handled by one team.</p>
        </div>
    </section>
```

with:

```blade
    <x-warm-hero>
        <h1 class="text-4xl font-bold text-brand-900 sm:text-5xl">Our Services</h1>
        <p class="mt-4 max-w-2xl text-lg text-slate-600">Everything your practice needs to submit, chase, reconcile, and report on medical claims, handled by one team.</p>
    </x-warm-hero>
```

- [ ] **Step 4: Replace the closing CTA section**

Replace:

```blade
    <section class="border-t border-slate-200 bg-slate-50 py-24">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-brand-900">Curious What This Costs?</h2>
            <p class="mt-4 text-slate-600">Our pricing is built so we only earn when your practice actually gets paid.</p>
            <a href="{{ route('pricing') }}" class="mt-8 inline-flex items-center rounded-lg bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">See Our Pricing Model</a>
        </div>
    </section>
```

with:

```blade
    <x-warm-cta>
        <h2 class="text-3xl font-bold text-brand-900">Curious What This Costs?</h2>
        <p class="mt-4 text-slate-700">Our pricing is built so we only earn when your practice actually gets paid.</p>
        <a href="{{ route('pricing') }}" class="mt-8 inline-flex items-center rounded-full bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">See Our Pricing Model</a>
    </x-warm-cta>
```

- [ ] **Step 5: Run the test to verify it passes**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=ServicesPageTest
```

Expected: PASS (2 tests).

- [ ] **Step 6: Commit**

```bash
git add -A
git commit -m "Apply warm-hero and warm-cta to the Services page"
```

---

### Task 5: Pricing page hero, how-it-works chart card, and button pill shape

**Files:**
- Modify: `resources/views/pages/pricing.blade.php`
- Test: `tests/Feature/PricingPageTest.php`

**Interfaces:**
- Consumes: `<x-warm-hero>` (Task 2), `<x-warm-chart-card>` (Task 2).

- [ ] **Step 1: Write the failing test**

Edit `tests/Feature/PricingPageTest.php`, adding a new test method:

```php
    public function test_pricing_hero_and_model_chart_use_warm_treatment(): void
    {
        $response = $this->get('/pricing');

        $response->assertSee('data-blob-drift', false);
        $response->assertSee('rounded-3xl border border-warm-border bg-warm-surface', false);
        $response->assertSee('rounded-full', false);
    }
```

- [ ] **Step 2: Run the test to verify it fails**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=PricingPageTest
```

Expected: FAIL.

- [ ] **Step 3: Replace the Pricing hero section**

Edit `resources/views/pages/pricing.blade.php`. Replace:

```blade
    <section class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-b from-brand-50 to-white">
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-brand-900 sm:text-5xl">A Pricing Model Built Around Getting You Paid</h1>
            <p class="mt-4 max-w-2xl text-lg text-slate-600">Most medical billing services charge a flat monthly fee or a percentage of the claims they submit, whether or not those claims are ever paid. ClearClaims works differently.</p>
        </div>
    </section>
```

with:

```blade
    <x-warm-hero>
        <h1 class="text-4xl font-bold text-brand-900 sm:text-5xl">A Pricing Model Built Around Getting You Paid</h1>
        <p class="mt-4 max-w-2xl text-lg text-slate-600">Most medical billing services charge a flat monthly fee or a percentage of the claims they submit, whether or not those claims are ever paid. ClearClaims works differently.</p>
    </x-warm-hero>
```

- [ ] **Step 4: Wrap the "how the model works" arrow motif in a warm-chart-card**

The "How the Percentage of Collections Model Works" section contains an arrow motif at `resources/views/pages/pricing.blade.php:16` (inside a `<div class="mt-10">` immediately after the section's second paragraph). Replace:

```blade
            <div class="mt-10">
                <x-arrow-motif class="h-24 w-full" />
            </div>
```

with:

```blade
            <div class="mt-10">
                <x-warm-chart-card>
                    <x-arrow-motif class="h-24 w-full" />
                </x-warm-chart-card>
            </div>
```

- [ ] **Step 5: Make the "Get a Quote" button pill-shaped**

Replace:

```blade
            <a href="{{ route('contact') }}" class="mt-12 inline-flex items-center rounded-lg bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">Get a Quote for Your Practice</a>
```

with:

```blade
            <a href="{{ route('contact') }}" class="mt-12 inline-flex items-center rounded-full bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">Get a Quote for Your Practice</a>
```

- [ ] **Step 6: Run the test to verify it passes**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=PricingPageTest
```

Expected: PASS (2 tests).

- [ ] **Step 7: Commit**

```bash
git add -A
git commit -m "Apply warm-hero and warm-chart-card to the Pricing page"
```

---

### Task 6: About page hero and closing CTA

**Files:**
- Modify: `resources/views/pages/about.blade.php`
- Test: `tests/Feature/AboutPageTest.php`

**Interfaces:**
- Consumes: `<x-warm-hero>` (Task 2), `<x-warm-cta>` (Task 3).

- [ ] **Step 1: Write the failing test**

Edit `tests/Feature/AboutPageTest.php`, adding a new test method:

```php
    public function test_about_hero_and_cta_use_warm_treatment(): void
    {
        $response = $this->get('/about');

        $response->assertSee('data-blob-drift', false);
        $response->assertSee('rounded-full', false);
    }
```

- [ ] **Step 2: Run the test to verify it fails**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=AboutPageTest
```

Expected: FAIL.

- [ ] **Step 3: Replace the About hero section**

Edit `resources/views/pages/about.blade.php`. Replace:

```blade
    <section class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-b from-brand-50 to-white">
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-brand-900 sm:text-5xl">Medical Billing Support That Lets You Focus on Patients</h1>
        </div>
    </section>
```

with:

```blade
    <x-warm-hero>
        <h1 class="text-4xl font-bold text-brand-900 sm:text-5xl">Medical Billing Support That Lets You Focus on Patients</h1>
    </x-warm-hero>
```

- [ ] **Step 4: Replace the closing CTA section**

Replace:

```blade
    <section class="border-t border-slate-200 bg-slate-50 py-24">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-brand-900">Want to Talk Through Your Practice's Billing?</h2>
            <a href="{{ route('contact') }}" class="mt-8 inline-flex items-center rounded-lg bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">Get in Touch</a>
        </div>
    </section>
```

with:

```blade
    <x-warm-cta>
        <h2 class="text-3xl font-bold text-brand-900">Want to Talk Through Your Practice's Billing?</h2>
        <a href="{{ route('contact') }}" class="mt-8 inline-flex items-center rounded-full bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">Get in Touch</a>
    </x-warm-cta>
```

- [ ] **Step 5: Run the test to verify it passes**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=AboutPageTest
```

Expected: PASS (2 tests).

- [ ] **Step 6: Commit**

```bash
git add -A
git commit -m "Apply warm-hero and warm-cta to the About page"
```

---

### Task 7: Contact page hero and pill-shaped submit button

**Files:**
- Modify: `resources/views/pages/contact.blade.php`
- Test: `tests/Feature/ContactPageTest.php`

**Interfaces:**
- Consumes: `<x-warm-hero>` (Task 2). No CTA section exists on this page (form + info panel only), so `<x-warm-cta>` is not used here.

- [ ] **Step 1: Write the failing test**

Edit `tests/Feature/ContactPageTest.php`, adding a new test method:

```php
    public function test_contact_hero_uses_warm_treatment_and_submit_button_is_a_pill(): void
    {
        $response = $this->get('/contact');

        $response->assertSee('data-blob-drift', false);
        $response->assertSee('rounded-full', false);
    }
```

- [ ] **Step 2: Run the test to verify it fails**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=ContactPageTest
```

Expected: FAIL.

- [ ] **Step 3: Replace the Contact hero section**

Edit `resources/views/pages/contact.blade.php`. Replace:

```blade
    <section class="relative overflow-hidden border-b border-slate-200 bg-gradient-to-b from-brand-50 to-white">
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-brand-900 sm:text-5xl">Let's Talk About Your Practice's Billing</h1>
            <p class="mt-4 max-w-2xl text-lg text-slate-600">Tell us a bit about your practice and we will get back to you to discuss how ClearClaims can help.</p>
        </div>
    </section>
```

with:

```blade
    <x-warm-hero>
        <h1 class="text-4xl font-bold text-brand-900 sm:text-5xl">Let's Talk About Your Practice's Billing</h1>
        <p class="mt-4 max-w-2xl text-lg text-slate-600">Tell us a bit about your practice and we will get back to you to discuss how ClearClaims can help.</p>
    </x-warm-hero>
```

- [ ] **Step 4: Make the form submit button pill-shaped**

Replace:

```blade
                        <button type="submit"
                            class="inline-flex w-full items-center justify-center rounded-lg bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700 sm:w-auto">
                            Send Message
                        </button>
```

with:

```blade
                        <button type="submit"
                            class="inline-flex w-full items-center justify-center rounded-full bg-brand-600 px-8 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700 sm:w-auto">
                            Send Message
                        </button>
```

- [ ] **Step 5: Run the test to verify it passes**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test --filter=ContactPageTest
```

Expected: PASS (2 tests).

- [ ] **Step 6: Run the full suite**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test
```

Expected: all tests pass (including `ContactFormSubmissionTest`, which asserts on form submission behavior, not button styling, so it is unaffected).

- [ ] **Step 7: Commit**

```bash
git add -A
git commit -m "Apply warm-hero to the Contact page and make the submit button a pill"
```

---

### Task 8: Blob drift and floating chip animations

**Files:**
- Modify: `resources/js/animations.js`

**Interfaces:**
- Consumes: `[data-blob-drift]` elements (produced by `warm-hero` and `warm-cta` in Tasks 2 and 3), `[data-float-chip]` elements (produced by `warm-floating-chip` in Task 2).

- [ ] **Step 1: Add the blob drift and chip float animations**

Edit `resources/js/animations.js`. Add this block at the end of the existing `document.addEventListener('DOMContentLoaded', () => { ... })` callback, just before its closing `});`:

```javascript
    document.querySelectorAll('[data-blob-drift]').forEach((blob, index) => {
        gsap.to(blob, {
            x: index % 2 === 0 ? 18 : -14,
            y: index % 2 === 0 ? -14 : 16,
            scale: 1.06,
            duration: 6 + index,
            ease: 'sine.inOut',
            repeat: -1,
            yoyo: true,
        });
    });

    document.querySelectorAll('[data-float-chip]').forEach((chip, index) => {
        gsap.to(chip, {
            y: -10,
            duration: 3.4 + index * 0.4,
            ease: 'sine.inOut',
            repeat: -1,
            yoyo: true,
            delay: index * 0.3,
        });
    });
```

- [ ] **Step 2: Build assets and verify the build succeeds**

```bash
cd /Users/morgz/Code/clear-claims
npm run build
```

Expected: build completes with no errors.

- [ ] **Step 3: Run the full test suite**

```bash
cd /Users/morgz/Code/clear-claims
php artisan test
```

Expected: all tests pass (animation script changes don't affect server-rendered HTML content that tests assert on).

- [ ] **Step 4: Manually verify in a browser**

```bash
cd /Users/morgz/Code/clear-claims
npm run dev
```

Open `https://clear-claims.test` and check: the hero and CTA sections have softly drifting blurred blob shapes in the background, the Home hero's floating chip gently bobs up and down, the arrow motif still draws in on scroll as before, and nav/footer show the larger logo and pill-shaped CTA button. Stop the dev server (Ctrl+C) once confirmed.

- [ ] **Step 5: Commit**

```bash
git add -A
git commit -m "Add ambient blob drift and floating chip animations"
```

---

## Final verification

```bash
cd /Users/morgz/Code/clear-claims
php artisan test
npm run build
curl -sk -o /dev/null -w "%{http_code}\n" https://clear-claims.test
curl -sk -o /dev/null -w "%{http_code}\n" https://clear-claims.test/services
curl -sk -o /dev/null -w "%{http_code}\n" https://clear-claims.test/pricing
curl -sk -o /dev/null -w "%{http_code}\n" https://clear-claims.test/about
curl -sk -o /dev/null -w "%{http_code}\n" https://clear-claims.test/contact
```

Expected: all tests pass, build succeeds, every route returns `200`.
