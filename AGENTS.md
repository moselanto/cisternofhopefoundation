# AGENTS.md - working on this repository

WordPress site for Cistern of Hope Foundation (cisternofhopefoundation.org).

## Layout

- `cohf/` - minimal parent theme. Rarely changes. Never activated directly.
- `cohf-child/` - the actual site. All work happens here.
- `cohf-child/README.md` - install, editing map and file map. Keep the file map current when adding modules.
- `cohf-child/docs/SECURITY-AUDIT.md` - open security findings.

## Rules

1. **Never invent facts.** Figures, dates, partners, testimonials and names come only from the Foundation. Statistics live in `inc/template-tags.php` (and are editable in the Customizer); do not hard-code new ones in templates.
2. **Content should be editable in WordPress.** Prefer options, Customizer settings, page content or post types over literals in PHP. Existing content is never overwritten by seed functions.
3. **Escape on output, sanitise on input.** `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`; nonces and `current_user_can()` on every save.
4. **Giving is separate from the shop.** Donations go through Paystack (`inc/giving*.php`); the Hope Market (`inc/shop.php`) is WooCommerce. Never route a donation through the cart. Never trust an amount from the browser.
5. **CSS load order:** `prototype.css` -> `wp-adapt.css` -> `design-system.css` -> `ux-refinements.css` -> `style.css`. Put fixes in the latest layer that applies; do not reintroduce the old token/base/component files.
6. **Bump `COHF_CHILD_VERSION`** in `functions.php` and the `Version:` header in `style.css` for each release, and describe the change in the commit message.
7. **Accessibility is enforced:** one `h1` per page, visible focus rings, 44px touch targets, reduced-motion support, alt text on every image.
8. **Every `inc/` file loads on every request.** A PHP fatal in any of them takes the site down. Run `php -l` on changed files before committing.

## Before going live with a change

- `php -l` every changed PHP file.
- Check the homepage, Support Our Work, and one programme page on a phone width.
- If giving code changed, run a test donation in Paystack test mode.
