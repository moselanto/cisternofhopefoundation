# Architecture essentials

One-page map for anyone (human or AI) picking up the codebase. Full detail: `ARCHITECTURE.md`.

## Stack

| Layer | Technology |
| --- | --- |
| CMS | WordPress 6.2+ (PHP 8.0+), classic theme, no page builder |
| Themes | `cohf/` parent (never active) + `cohf-child/` (the site) |
| Shop | WooCommerce (the "Hope Market") |
| Giving | Paystack Inline + server-side initialise/verify + signed webhook |
| SEO | Theme schema/OG, steps aside when Rank Math or Yoast is active |
| Hosting | LiteSpeed server, not behind a CDN (as of October 2026) |
| Front end | Vanilla deferred JS, layered CSS, self-hosted fonts, zero external requests except Paystack on the giving page |

## Boot sequence

`cohf-child/functions.php` defines `COHF_CHILD_VERSION`, `COHF_CHILD_DIR`, `COHF_CHILD_URI`, then `require_once`s every module in `cohf-child/inc/` in a fixed order. **All modules load on every request**, so a syntax error anywhere takes the site down.

## Where things live

| Concern | Files |
| --- | --- |
| Security headers, login throttle, HTTPS, sessions, passwords, CORS, uploads | `inc/security.php` |
| Staff login screen and two-step verification | `inc/login.php`, `inc/two-factor.php`, `assets/css/login.css`, `assets/js/login.js` |
| Anti-spam, mail circuit breaker, shop/registration/reset rate limits | `inc/anti-spam.php`, `inc/form-guard.php` |
| Contact/enquiry form | `inc/forms.php`, `template-parts/enquiry-form.php`, `assets/js/forms.js` |
| Giving (Paystack) | `inc/giving.php` (settings, enqueue), `inc/giving-checkout.php` (REST initialise, nonce, client IP), `inc/giving-records.php` (verify, webhook, donation records), `template-parts/giving-form.php`, `assets/js/giving.js` |
| Hope Market | `inc/shop*.php`, `inc/home-market.php`, `woocommerce.php`, `assets/js/shop*.js`, `assets/js/cart-drawer.js` |
| Content types and fields | `inc/custom-post-types.php`, `inc/custom-fields.php`, `inc/partners.php`, `inc/photos.php`, `inc/videos.php` |
| Seeds / one-time setup | `inc/content-defaults.php`, `inc/page-seeds.php`, `inc/story-seeds.php`, `inc/shop-seeds.php` |
| Editable wording and figures | `inc/site-text.php`, `inc/template-tags.php`, `inc/customizer.php` |
| SEO / schema | `inc/seo.php`, `inc/seo-keywords.php`, `inc/schema.php`, `inc/rank-math*.php`, `inc/redirects.php` |
| Performance / assets | `inc/performance.php`, `.htaccess` |
| Templates | `page-templates/`, `single-cohf_*.php`, `archive-cohf_*.php`, `template-parts/` |

## Public entry points (attack surface)

| Entry point | Guard |
| --- | --- |
| `POST /wp-json/cohf/v1/initialize` | nonce, 5/5 min per visitor, server-side amount 50-1,000,000 KES |
| `GET /wp-json/cohf/v1/giving-nonce` | 30/10 min per visitor, no-store |
| `GET /wp-json/cohf/v1/form-token` | 60/hour per visitor, no-store |
| `POST /wp-json/cohf/v1/paystack` | HMAC-SHA512 signature (`hash_equals`) |
| Enquiry form (`template_redirect`) | nonce, honeypot, signed time trap, rate limits, duplicate drop, site-wide cap |
| `wc-ajax=cohf_search`, `cohf_cart_qty`, `cohf_wa_preview`, `?cohf-wa-order=` | length caps, nonces, rate limits, fixed WhatsApp destination |
| Login / registration / password reset | lockout, honeypot, time trap, rate limits, password policy |
| `admin_post_cohf_*` handlers | `check_admin_referer` + `current_user_can` |

## Secrets and configuration (wp-config.php)

```php
define( 'COHF_PAYSTACK_SECRET_KEY', 'sk_live_...' ); // or env var COHF_PAYSTACK_SECRET_KEY
define( 'COHF_BEHIND_PROXY', true );      // only if behind a proxy that sets X-Forwarded-For
define( 'COHF_BEHIND_CLOUDFLARE', true ); // only if behind Cloudflare
define( 'FORCE_SSL_ADMIN', true );
define( 'DISALLOW_FILE_EDIT', true );     // theme also sets this
define( 'COHF_REQUIRE_2FA', false );      // EMERGENCY ONLY: stop forcing 2FA if locked out
```

The Paystack **public** key, plan code and suggested amounts are normal options in Foundation > Giving.
