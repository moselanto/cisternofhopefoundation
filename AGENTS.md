# AGENTS.md - rules for any person or AI agent changing this repository

WordPress site for **Cistern of Hope Foundation** (https://cisternofhopefoundation.org/).
Read this file first, then `ARCHITECTURE-ESSENTIALS.md`. Read `SECURITY-AUDIT.md`
before touching forms, giving, the shop, REST routes, login or headers.

## Read order for a new agent

1. `AGENTS.md` (this file) - rules and the release checklist.
2. `ARCHITECTURE-ESSENTIALS.md` - one-page map: where things live, what loads when.
3. `ARCHITECTURE.md` - full module-by-module reference and data flows.
4. `PRD.md` - what the site must do and for whom (do not break any listed feature).
5. `SECURITY-AUDIT.md` - security controls, findings, open items and history.
6. `cohf-child/README.md` - staff editing guide and install steps.
7. `docs/seo-audit/` - SEO baseline, schema ownership rule, crawler policy, keyword map and implementation log. Read before touching `seo*.php`, `schema.php`, `rank-math*.php` or `ai-discovery.php`.

## Layout

- `cohf/` - minimal parent theme (COHF Base). Rarely changes. Never activated directly.
- `cohf-child/` - the actual site. All work happens here.
- `docs/screenshots/` - README images only.
- `.github/` - CI (PHP lint) and Dependabot.

## Rules

1. **Never invent facts.** Figures, dates, partners, testimonials and names come only from the Foundation. Statistics live in `inc/template-tags.php` (editable in the Customizer); do not hard-code new ones in templates.
2. **Content must be editable in WordPress.** Prefer options, Customizer settings, page content or post types over literals in PHP. Seed functions never overwrite existing content.
3. **Escape on output, sanitise on input.** `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post` on output; `sanitize_*`, `absint` on input; nonce + `current_user_can()` on every save and every `admin_post_*` handler.
4. **SQL only through `$wpdb->prepare()`** (and `$wpdb->esc_like()` for LIKE). Never concatenate request data into SQL.
5. **JavaScript never inserts untrusted text with `innerHTML`** unless it went through the local `esc()` helper. Prefer `textContent`.
6. **No secrets in the repository.** The Paystack secret key comes from `wp-config.php` (`COHF_PAYSTACK_SECRET_KEY`) or the server environment variable of the same name. The database option is a legacy fallback only.
7. **Giving is separate from the shop.** Donations go through Paystack (`inc/giving*.php`); the Hope Market (`inc/shop*.php`) is WooCommerce. Never route a donation through the cart. Never trust an amount from the browser.
8. **Every public endpoint needs a rate limit** (transient keyed on a salted hash of `cohf_giving_client_ip()`), input length limits and, where a browser calls it, a nonce.
9. **CSS load order:** `prototype.css` -> `wp-adapt.css` -> `design-system.css` -> `ux-refinements.css` -> `style.css`. Put fixes in the latest layer that applies.
10. **Bump `COHF_CHILD_VERSION`** in `cohf-child/functions.php` and `Version:` in `cohf-child/style.css` for each release; describe the change in the commit message and add a line to the changelog in `ARCHITECTURE.md`.
11. **Accessibility is enforced:** one `h1` per page, visible focus rings, 44px touch targets, reduced-motion support, alt text on every image.
12. **Every `inc/` file loads on every request.** A PHP fatal in any of them takes the site down. CI runs `php -l`; run it locally too.
13. **Do not remove existing functionality.** If something looks unused, check `PRD.md` and grep for its hook or function name first.
14. **Record what you did.** Any security change goes into `SECURITY-AUDIT.md`; any new module goes into the module table in `ARCHITECTURE.md` and the `functions.php` load list.

## Release checklist

- `php -l` every changed PHP file (CI does this on push and pull request).
- Bump both version numbers.
- Upload `cohf-child/` to `wp-content/themes/` (commits do not deploy automatically).
- Purge LiteSpeed / any page cache.
- Check the homepage, Support Our Work, one programme page and the shop on a phone width.
- If giving code changed, run a test donation in Paystack test mode.
- If security headers changed, check https://securityheaders.com and a Paystack checkout on a phone.
