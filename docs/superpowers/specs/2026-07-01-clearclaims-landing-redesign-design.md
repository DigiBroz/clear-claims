# ClearClaims Website — Warm Human-Centered Redesign

## Overview

A visual/template-layer redesign of the existing ClearClaims Health Accounts site (built in the earlier `2026-07-01-clearclaims-website-design.md` spec). The current site reads as a generic, safe Tailwind-template layout — uniform bordered card grids, flat alternating white/slate backgrounds, thin type, minimal motion. This redesign replaces that with a "Warm Human-Centered Healthcare" direction: organic shapes, a warm cream/peach canvas, rounded pill buttons, and floating card motifs, applied across all 5 pages. No backend, routing, SEO, security, or contact-form logic changes — this is presentation-layer only.

Chosen after a visual comparison of 4 distinct directions (Bold Editorial, Depth & Motion, Bento Grid, Warm Human-Centered) presented as working mockups in brand colors/copy.

## Visual identity additions

New warm neutral tokens, layered on top of the existing `brand` (blue/navy) and `growth` (green) palettes, which are unchanged:

- `--color-warm-bg: #FBF3E6` — cream page background, used behind hero and CTA sections
- `--color-warm-surface: #FFFDF8` — warm-white surface for nav, cards, floating chips
- `--color-warm-border: #EDDFC4` — border tone for warm-surface elements
- `--color-warm-text: #5B4C3F` — muted warm body text, used within warm-toned sections only (dense content sections keep the existing `slate` body text)
- `--color-peach-300: #F6D9A8` and `--color-peach-500: #EFC48A` — peach accent used in blob background shapes

Typography stays Space Grotesk (headings) / Inter (body), no new fonts. Hero headings move from the current 4xl/5xl scale to a bolder, larger scale (approx. 45-48px desktop, weight 700).

Buttons site-wide switch from `rounded-lg` (8px) to fully rounded pill buttons (`rounded-full`): primary as a blue gradient fill, secondary as a warm-bordered ghost button.

## Scope of the warm/organic treatment (restrained)

- **Every page's hero band** (Home, Services, Pricing, About, Contact) gets the full treatment: blurred organic blob shapes in blue/green/peach, a cream gradient backdrop, and a wave-shaped SVG divider transitioning into the next section.
- **Closing CTA sections** (present on Home, Services, Pricing, About) also get the cream/organic treatment.
- **Everything else stays clean**: the 6-service grid, the pricing comparison table, the FAQ, the "how it works" steps, the About commitment list, and the Contact form all remain on white/light surfaces with the existing `slate` body text — only the buttons, headings, and small accents pick up the warm palette. No blobs or waves compete with dense content.
- **Exception — Home's pricing teaser band**: currently a full-bleed dark-navy rectangle (`bg-brand-900`). It keeps its navy fill and visual weight (this is the site's core differentiator statement) but changes shape: instead of a hard-edged full-width band, it becomes a rounded "island" card with generous corner radius, floating on the cream page background, consistent with the new softer aesthetic.

## Nav and footer

Re-themed in place, no structural rebuild:
- Nav background shifts from pure white to warm-white (`--color-warm-surface`), border to `--color-warm-border`
- "Contact Us" nav CTA becomes a pill button (`rounded-full`)
- Footer picks up the same warm-white/warm-border treatment
- **Logo size increase**: the nav and footer logo (`resources/views/layouts/app.blade.php`, currently `h-16` = 64px tall in both locations) increases to `h-20` (80px) in the nav and stays proportionally sized in the footer, giving the brand mark more presence
- Sticky behavior, mobile menu toggle logic, and all routing/links are unchanged

## Arrow motif (from the original build)

The existing GSAP-animated ascending arrow (`resources/views/components/arrow-motif.blade.php` + `resources/js/animations.js`) is kept as the signature brand element with its draw-in scroll animation unchanged. Its presentation changes: instead of sitting bare on the page, it's now housed inside a warm-white "chart card" (rounded corners, soft shadow, `--color-warm-border` outline) matching the floating-card style from the chosen mockup direction.

## Animation additions

On top of the existing GSAP ScrollTrigger section reveals and the arrow motif draw-in:
- A slow, continuous ambient drift animation on hero blob shapes (subtle scale/translate loop, matching the mockup)
- A gentle float animation (small vertical bob loop) on floating notification-style cards near hero art

No fabricated numbers appear in any floating card or chart element — where the mockup used placeholder stats (e.g. "98.4% collections rate"), the real implementation uses honest content already established in the site (e.g. the 5-step process, the 6 services, "you only pay when we collect").

## What stays unaffected

Contact form backend (Event/Listener/Notification, honeypot, rate limiting), SEO wiring (SEOTools, JSON-LD, sitemap), security headers, forced HTTPS, all routes, and all existing PHPUnit tests. This redesign touches Blade views, `resources/css/app.css` (theme tokens), and `resources/js/animations.js` only — no controller, route, or backend logic changes are expected. If any existing test asserts on now-changed CSS classes rather than content/text, that test needs updating to match, but no test's underlying behavioral assertion (page loads, contains expected copy, form submits) should need to change.

## Copy rules (carried over, unchanged)

No em dashes, no semicolons, no fabricated testimonials/stats — same rules as the original site spec, still binding.
