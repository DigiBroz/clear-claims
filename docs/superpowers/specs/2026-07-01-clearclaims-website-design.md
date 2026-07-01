# ClearClaims Health Accounts — Marketing Website Design

## Overview

A Laravel-powered marketing website for ClearClaims Health Accounts (Pty) Ltd, a South African medical billing and practice support company. The site introduces the company, explains its services and its percentage-of-collections pricing model, and lets prospective medical practices get in touch.

## Stack

- Laravel 13 (latest, resolved by Composer at scaffold time, not pinned to an assumed version)
- Blade templates, no SPA framework, matching the sibling Phungo site
- Alpine.js for lightweight interactivity (nav toggle, form states)
- GSAP + ScrollTrigger for scroll-driven animation
- Tailwind CSS v4, CSS-first `@theme` configuration (same pattern as Phungo's `resources/css/app.css`)
- Vite for asset bundling
- `artesaos/seotools` for per-page SEO metadata, matching Phungo's implementation
- `spatie/laravel-honeypot` for contact form spam protection (addition beyond Phungo's baseline)
- No database-backed features and no auth are required, so no starter kit (Breeze/Jetstream) is installed

## Visual identity

Colours are derived directly from the ClearClaims logo (navy cross wordmark, blue gradient cross icon, green growth arrow):

- Navy `#14305C` — headings, nav text, primary dark colour
- Blue gradient `#3D8FD1` → `#14305C` — primary brand colour, buttons, links, echoes the logo's cross icon
- Green `#29A467` — accent colour tied to the logo's upward arrow, used for growth/success cues, checkmarks, highlights
- Neutral background `#F7F9FB`, borders `#E7ECF1`, secondary text `#5B6670`

Typography: Space Grotesk for headings (keeps a family resemblance to the Phungo sibling site), Inter for body copy (more legible at paragraph length than Space Grotesk).

Theme: light, clean, and trustworthy — a deliberate contrast to Phungo's dark cyber-security aesthetic, appropriate for a healthcare-services audience.

**Signature motion element:** the logo's ascending arrow becomes a recurring animated SVG motif, an ascending line-chart path that draws itself via GSAP ScrollTrigger as the user scrolls through key sections (hero, pricing model, how-it-works). This ties the brand mark into the site's motion language instead of relying on generic fade/slide reveals, and is the main "make this unique" hook.

Other animation touches: staggered hero text reveal on load, scroll-triggered card/section reveals, sticky nav that shrinks on scroll, animated mobile menu, button hover micro-interactions, animated form field focus states.

## Site structure

1. **Home** (`/`) — hero with signature arrow motif, services overview (card grid), pricing-model teaser linking to `/pricing`, 5-step "how it works" process (from the company profile PDF), commitment values (accuracy, efficiency, confidentiality, professional service), closing CTA
2. **Services** (`/services`) — the six services from the company profile, each expanded with real explanatory copy (not just a one-line bullet):
   - Medical claims submission and processing
   - Medical aid follow-ups and collections
   - Payment reconciliation and allocation
   - Patient account management
   - Practice financial reporting
   - Onboarding support for new medical practices
3. **Pricing Model** (`/pricing`) — full explanation of the percentage-of-collections model: the fee is charged only on money successfully paid out to the practice by the medical aid, never on submitted or outstanding claims, so ClearClaims only earns when the practice actually gets paid. Includes a comparison against flat-fee/retainer billing services, and an FAQ section (including "what percentage do you charge?", answered honestly as "quoted after a no-obligation consultation, based on practice size and specialty" since no fixed rate was provided)
4. **About** (`/about`) — mission statement, commitment values, who the company serves (GPs, specialists, allied health practices across South Africa)
5. **Contact** (`/contact`) — ported contact form, phone and location details, and a "why choose ClearClaims" trust panel built from the PDF's "Why Choose Us" list

No fabricated testimonials, client logos, or unverifiable statistics anywhere on the site. Trust is built through process transparency, the commitment list, and the pricing model's aligned incentives.

## Copy rules

- No em dashes anywhere in on-site copy (headings, body text, form labels, alt text)
- No semicolons anywhere in on-site copy
- All service and pricing-model copy expands meaningfully on the PDF's bullet points rather than just repeating them verbatim, so the site doesn't read as thin

## Contact form (ported from Phungo)

Same architecture as Phungo's implementation:

- `ContactRequest` — form request validating first name, last name, email, practice/company name (optional), service of interest (optional), message
- `ContactFormSubmitted` event, dispatched from `ContactController::submit`
- `SendContactFormNotification` listener, routes to a `Notification::route('mail', 'info@clearclaims.health')`
- `ContactFormNotification` — queued markdown mail notification, subject and body written without em dashes or semicolons
- Service dropdown options swapped to ClearClaims' six services instead of Phungo's cybersecurity services
- A honeypot field (via `spatie/laravel-honeypot`) added to the form for spam protection, which Phungo's form does not have

## SEO (matching Phungo's approach, with one addition)

- `PageController` sets `SEOTools::setTitle()` / `setDescription()` per page, same pattern as Phungo's `PageController`
- OpenGraph image and Twitter card image set per page
- JSON-LD structured data using schema.org `MedicalBusiness` type (more precise than Phungo's generic `Organization`, appropriate for a healthcare-adjacent service)
- `robots.txt` allowing all crawlers, matching Phungo
- Addition beyond Phungo's baseline: an XML sitemap (Phungo's repo has none)

## Security

- Rate limiting (`throttle`) on the contact form's POST route
- Honeypot spam field on the contact form
- Security headers middleware: Content-Security-Policy, X-Content-Type-Options, X-Frame-Options, Referrer-Policy, Permissions-Policy
- Forced HTTPS outside the local environment
- `.env` never committed, `APP_DEBUG=false` outside local

## Local environment (Herd)

- `herd link` in the project directory to serve the site at `clear-claims.test`
- `herd secure clear-claims` for local TLS via mkcert, so the local site runs under HTTPS like production will
