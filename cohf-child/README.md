# Cistern of Hope Foundation — WordPress theme

**Together for a lasting change.**

Two themes ship together:

| Theme | Folder | Role |
|---|---|---|
| COHF Base | `cohf/` | Lightweight parent. Document structure, menus, template fallbacks. No design opinions, no dependencies. |
| Cistern of Hope Foundation | `cohf-child/` | The site. Design system, content structures, templates, accessibility, SEO, performance and admin experience. |

No page builder is required. No premium plugin is required. No external font or script request is made.

---

## 1. Install

1. Upload **both** folders to `/wp-content/themes/` (or install both ZIPs via **Appearance → Themes → Add New → Upload Theme**).
2. Activate **Cistern of Hope Foundation** (the child). The parent must be present but is never activated.
3. On the dashboard, click **Run one-time setup**. This creates:
   - all pages with the correct templates assigned,
   - the twelve programme areas with their strategic purpose,
   - the seven leadership profiles,
   - the primary navigation menu,
   - the homepage as the front page.
   Nothing that already exists is overwritten.
4. Go to **Settings → Permalinks** and click Save once.
5. **Appearance → Customise → Site Identity** — upload the logo.
6. Add photography (see `assets/images/README.txt`).

## 2. What the Foundation edits, and where

| To change | Go to |
|---|---|
| A programme's description, activities, indicators | **Programmes** |
| A story from the field | **Impact Stories** |
| An announcement | **News** |
| A community programme or forum | **Events** |
| An annual or programme report (PDF) | **Reports** |
| A policy or publication | **Resources** |
| A team member | **Leadership** |
| A current or past partner, and what you achieved together | **Partners** (tick *Partnership confirmed in writing*; set *Current or past partner*, headline, accomplishments, optional figure; the main image is the logo) |
| Page wording | **Pages** |
| Phone, email, postal address, social media links | **Foundation → Organisation details** |
| Top menu | **Appearance → Menus** |

**Foundation → Site guide** in the admin repeats this, in plain English, for staff.

## 3. Content integrity rules built into the theme

These are not documentation; they are enforced in code.

- An **Impact Story cannot be published** until *Consent confirmed* is ticked. Attempting to publish reverts it to draft, with an explanation.
- A **Partner is hidden** from the site until *Partnership confirmed in writing* is ticked.
- **No statistic is hard-coded anywhere except** `inc/template-tags.php`, where every figure carries a comment naming the source document and section.
- Every reported figure on the site is displayed with a visible qualifier that it is a **current reported programme figure, not a lifetime total**.
- The Support page contains **no payment provider, bank account, M-Pesa number or donation link**. The integration point is marked with a developer comment.
- The footer contains **no social media accounts** until real ones are confirmed.

## 4. File map

```
cohf-child/
  style.css                      Theme header + project overrides only
  functions.php                  Loads /inc modules (order matters)
  .htaccess                      Denies direct access to PHP internals and docs

  assets/
    css/  prototype.css          Approved prototype design layer (loads first)
          wp-adapt.css           WordPress core classes, admin bar, a11y utilities
          design-system.css      Shared components
          ux-refinements.css     Interaction/readability overrides (loads last)
          gallery.css            Gallery page only
          editor.css             Block editor styles
    js/   main.js  navigation.js  leadership.js  gallery.js  giving.js
    fonts/  Inter + Playfair Display variable WOFF2 (self-hosted)
    images/ brand/, leaders/ (4:5 portraits used by leader-photos.php),
            leader-*.jpg (same portraits, used by the media.php library),
            hero-, programme-, story-, gallery- photographs

  inc/
    theme-functions.php          Setup, organisation data, CTA links, logo
    nav-structure.php, nav-walker.php, mobile-actions.php   Navigation
    media.php, photos.php, leader-photos.php   Bundled photo library + overrides
    custom-post-types.php        Post types and taxonomies
    custom-fields.php            Native metaboxes (no ACF dependency)
    partners.php                 Partner visibility rules (404 + sitemap) and Foundation-supplied partner seeds
    customizer.php               Photos and impact figures editable in the Customizer
    gallery.php                  Gallery post type, import and assets
    page-body.php, page-seeds.php, story-seeds.php   Editable page and story content
    block-patterns.php, shortcodes.php   Editor building blocks
    content-defaults.php         One-click setup and seeding
    admin-experience.php         Site guide, settings, content guards
    forms.php                    Enquiry form (nonce, honeypot, time-trap, rate limit)
    giving.php                   Paystack settings and asset loading
    giving-checkout.php          Server-side transaction initialisation (REST)
    giving-records.php           Verification, webhook, donation records
    shop.php                     Hope Market (WooCommerce), kept apart from giving
    accessibility.php, performance.php, security.php, seo.php, privacy.php, redirects.php
    template-tags.php            Document-sourced figures and content data

  template-parts/                Hero, cards, CTA band, forms, legal text, nav
  page-templates/                One template per page (Home, About, Programmes,
                                 Impact, Approach, Leadership, Partners, Resources,
                                 Contact, Get Involved, Support, Accountability,
                                 Strategy, Gallery, Privacy, Terms, Donation Policy)
  single-*.php, archive-*.php    Custom post type views
  docs/SECURITY-AUDIT.md         Findings and fixes, September 2026
```

Read `AGENTS.md` at the repository root before changing code.

## 5. Plugin compatibility

| Plugin | Status |
|---|---|
| **Rank Math / Yoast** | Detected automatically. The theme's own schema, Open Graph and breadcrumbs stand down so nothing is duplicated. |
| **Gutenberg** | Full support. Editor palette and font sizes mirror the design tokens, so editors cannot drift off-brand. |
| **Elementor** | Compatible. The site does **not** depend on it — every component is a PHP template part. |
| **WPForms / Fluent Forms** | Compatible. Drop a shortcode into any page; the native enquiry form is the fallback so the site works on day one. |
| **Google Site Kit / Analytics** | Compatible. No Content-Security-Policy is forced, so tag injection is not blocked. |
| **WooCommerce** | Declared and supported, for future merchandise or ticketed events. |
| **Caching / CDN** | No cookie- or session-dependent output on the front end. Safe to page-cache fully. |

## 6. Accessibility

Built to WCAG 2.1 AA intent:

- Skip-to-content link; one `<h1>` per page; ordered heading hierarchy.
- Every interactive element reachable and operable by keyboard, with a 3 px gold focus ring that is never removed.
- `aria-current` on the active nav item; ARIA used only where native semantics fall short.
- Mobile menu traps nothing, closes on `Escape`, and returns focus to the toggle.
- Filter buttons use `aria-pressed` and announce results through a live region.
- Colour contrast: body text 12.9:1, white on Hope Green 9.8:1, charcoal on Sunlight 8.1:1.
- `prefers-reduced-motion` is honoured globally — all animation collapses to 1 ms.
- Without JavaScript, nothing is hidden: `.no-js` forces all reveal elements visible.
- The admin warns editors about missing alternative text.

## 7. Performance

- Four small stylesheets, one deferred 4 KB script. No jQuery, no framework, no icon font.
- Inline critical CSS shim so first paint is never unstyled.
- Self-hosted variable fonts with `font-display: swap` and preload; **zero external requests**.
- Hero image marked `fetchpriority="high"` and excluded from lazy loading; everything else lazy-loads.
- WebP and AVIF uploads enabled; four purpose-built image sizes registered.
- Emoji scripts, classic block styles, generator tags and shortlinks removed from the front end.
- Cache-busting uses `filemtime`, so CDN invalidation is automatic on deploy.

## 8. Security

- Every output escaped at the point of printing (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`).
- Every input sanitised by type on save.
- Nonces and `current_user_can()` checks on all metabox saves, the settings page and the setup action.
- Contact form: nonce, honeypot, rate limit, validated email, no database storage of personal data.
- `DISALLOW_FILE_EDIT` set; XML-RPC pingbacks disabled; login errors made non-enumerable.
- Security headers set via `wp_headers`. CSP deliberately left to the server — see `inc/security.php` for why.
- No hard-coded API key, credential or third-party endpoint anywhere in the theme.

## 9. Before going live

- [x] Replace placeholder images with the Foundation's own photographs.
- [x] Add the WOFF2 font files.
- [x] Privacy Policy, Terms of Use and Donation Policy pages.
- [x] Enter the Paystack keys and webhook URL under **Foundation → Giving**.
- [ ] Exclude the Support page from page caching (its giving form carries a nonce that expires).
- [ ] Publish the policies listed on the Accountability page into **Resources** as they are approved.
- [ ] Confirm the date of the documented community programme (see note below).
- [ ] Add social media links under **Foundation → Organisation details → Social media**. Icons appear in the footer only for fields that are filled in.
- [ ] Set up SMTP so the enquiry form delivers reliably.
- [ ] Install an SEO plugin and a caching plugin.

### One discrepancy to resolve

The supplied documents give two different dates for the same documented community programme at Kabete "N":

- *Strategic Framework 2026–2030* (section 6 and Annex B): **19 August 2026**
- *Impact of Cistern* document: **19 July 2026**

The theme uses **19 August 2026** throughout, matching the strategic framework and the brief. If the correct date is July, change it in `inc/template-tags.php` (`cohf_outreach_figures`) and on the Impact page template.

---

Built only from the Foundation's own documents. No statistic, partner, testimonial, award, certification, office or financial figure has been invented.
