# Security audit - Cistern of Hope Foundation theme

Reviewed: 30 September 2026, against `main` at 9.80.0 (`cohf-child` and `cohf`).
Scope: theme code only. Hosting, WordPress core, plugins and wp-admin settings were not inspected.

## Summary

The theme is in good shape for a small NGO site. Output is escaped, inputs are sanitised, and the giving flow is designed correctly: the amount is fixed on the server (`inc/giving-checkout.php`), checked again on verification, and the Paystack webhook checks the HMAC-SHA512 signature with `hash_equals` (`inc/giving-records.php`). Login throttling, username-enumeration blocking, XML-RPC shutdown and security headers are all in place (`inc/security.php`).

The findings below are the gaps that remain, most important first.

## Findings

### 1. High - Page caching will break the giving form after about a day

Status (30 Sep 2026): Paystack keys are configured, so giving is live and this applies as soon as any page cache is enabled.

`template-parts/giving-form.php` prints `wp_create_nonce( 'cohf_giving' )` into the page, and `/wp-json/cohf/v1/initialize` rejects an expired nonce with "This form has expired". WordPress nonces live 12-24 hours. The README recommends full page caching, so once a caching plugin or CDN is added, every donor who gets a cached copy older than a day is refused at checkout.

Fix: exclude the Support Our Work page from page caching (every caching plugin has a URL exclusion list), or fetch the nonce with a small uncached request when the form loads.

### 2. Not applicable today - Behind a proxy or CDN, all rate limits collapse into one shared bucket

Status (30 Sep 2026): the site is not behind Cloudflare or another proxy, so `REMOTE_ADDR` is the real visitor address and no change is needed. Revisit this if a CDN is added later.

`cohf_giving_client_ip()` only trusts `X-Forwarded-For` when `COHF_BEHIND_PROXY` is defined. It is not defined anywhere in the theme. The same function feeds three limits:

- donation attempts (5 per 5 minutes),
- enquiry form (45 seconds / 5 per hour),
- **login lockout (5 failures per 15 minutes)**.

If the site sits behind Cloudflare or a host-level proxy, `REMOTE_ADDR` is the proxy for every visitor. Five failed logins by anyone would lock every administrator out for 15 minutes, and a handful of donors in the same window would block each other.

Fix: if the site is behind a proxy, add `define( 'COHF_BEHIND_PROXY', true );` to `wp-config.php`. For Cloudflare, prefer reading `CF-Connecting-IP` and only when the request comes from a Cloudflare IP range.

### 3. Medium - Paystack secret key can be stored in the database

`Foundation > Giving` accepts the secret key as a normal option if `COHF_PAYSTACK_SECRET_KEY` is not defined. The screen already recommends `wp-config.php`. The risk is that database backups and exports then carry a live payment key.

Fix: put the key in `wp-config.php` and leave the field empty. Consider removing the field entirely once live.

### 4. Medium - `Permissions-Policy: payment=()` may block wallet payments inside Paystack

`inc/security.php` disables the Payment Request API for the page and every frame. Paystack's checkout runs in a frame, so Apple Pay / Google Pay inside it may not appear. Card and M-Pesa are unaffected.

Fix: test a live checkout on a phone. If wallets are wanted, allow the Paystack checkout origin for `payment` rather than removing the header.

### 5. Low - `cohf_giving_csp()` does nothing

`inc/giving.php` registers a `cohf_csp` filter that returns the policy unchanged on both branches, and nothing in the theme applies `cohf_csp`. Harmless, but it reads as if Paystack were allow-listed in a CSP when no script CSP exists. Remove it or finish it when a full CSP is added at the server.

### 6. Low - No automated check before code reaches the live site

There is no CI. A PHP syntax error in any `inc/` file takes the whole site down, because every module is loaded on every request.

Fix: add a GitHub Action that runs `php -l` over all `.php` files on every push and pull request.

## What was checked and is fine

- REST endpoints `initialize` and `paystack` are public by design; the first is protected by nonce + rate limit + server-side amount bounds (KES 50 - 1,000,000), the second by signature verification.
- Donation references (`COH-0001` ...) are generated server-side and checked for collisions.
- `.htaccess` denies direct access to `inc/`, `template-parts/`, `page-templates/`, `docs/` and non-web file types.
- Comments and pingbacks are closed site-wide; `DISALLOW_FILE_EDIT` is set.
- No API key, credential or hard-coded third-party secret was found in the repository.

---

## Update 12.1.0 (1 October 2026): anti-spam and abuse hardening

Re-audit of every public entry point after the shop, cart, search and
WhatsApp ordering work (11.x-12.0). New module: `inc/anti-spam.php`.

### Entry points reviewed

| Entry point | Protection now in place |
|---|---|
| Contact form (`inc/forms.php`) | Nonce, honeypot, signed time trap (3 s to 24 h), 1 per 45 s and 5 per hour per visitor, length limits, phone format check, max 2 links, **new:** identical message within 24 h silently dropped, **new:** site-wide ceiling of 30 enquiries per hour |
| Giving `cohf/v1/giving/initialize` | Nonce, 5 per 5 min per visitor, server-side amount validation; donor receipts only after Paystack verification (costs money to abuse) |
| Paystack webhook | HMAC-SHA512 signature check against the secret key |
| Shop checkout (classic and block / Store API) | **New:** 5 orders per visitor per hour; Store API write calls capped at 120 per 5 min per visitor |
| Cart quantity endpoint `wc-ajax=cohf_cart_qty` | Nonce (fresh via fragments), quantity capped at 99 |
| Live search `wc-ajax=cohf_search` | Input capped at 80 chars, **new:** 90 requests per minute per visitor |
| WhatsApp order redirect `?cohf-wa-order=` | Destination fixed to `wa.me/<site number>`; not an open redirect; quantity capped at 99 |
| WordPress and WooCommerce registration | **New:** honeypot, signed time trap, 3 accounts per visitor per hour |
| Password reset | **New:** 3 requests per visitor per 15 min and 3 per account per hour (stops inbox flooding) |
| Login | 5 failures lock the visitor out for 15 min; generic error message |
| Comments, pingbacks, XML-RPC | Disabled |
| User enumeration | `?author=` and public REST users endpoint blocked |

### Outgoing mail circuit breaker

`pre_wp_mail` guard on every email sent from a public (non-admin) request,
whichever plugin or form triggers it:

- at most 5 recipients and 2 Cc/Bcc headers per message;
- at most 60 emails per hour site-wide (filter `cohf_mail_hourly_limit`);
- when tripped, further mail is held for the rest of the hour, the admin
  email is told once a day, and wp-admin shows a warning for 24 hours.

Administrators, shop managers and WP-CLI are exempt.

### Clean-up

- Removed the image-sync debug notice (`cohf_dbg` query parameter) from
  `inc/content-defaults.php`.
- Placeholder sweep across `cohf/` and `cohf-child/`: no lorem ipsum, TODO,
  FIXME, dummy contact details, `console.log`, `var_dump` or `error_log`
  left in code. The only placeholder is the intended phone-format hint
  `07xx xxx xxx` on the giving form.

### Still open (server or admin side)

1. Exclude the Support page from page caching so the giving nonce never
   goes stale.
2. Move the Paystack secret key into `wp-config.php` as
   `COHF_PAYSTACK_SECRET_KEY`, then clear the field in Foundation > Giving.
3. If the site is ever put behind Cloudflare or another proxy, define
   `COHF_BEHIND_PROXY` so the per-visitor limits see real visitor IPs.
4. Set up SPF, DKIM and DMARC for cisternofhopefoundation.org so mail sent
   through WP Mail SMTP is trusted and spoofing is harder.
5. Use strong unique passwords and two-factor login for every
   administrator account (for example with the Two Factor plugin).

---

## Update 14.3.0 (3 October 2026)

Fixed in code:

- **Finding 1 (page caching vs giving nonce) - fixed.** The giving form now asks `GET /wp-json/cohf/v1/giving-nonce` (sent with no-store / LiteSpeed no-cache headers) for a fresh nonce just before payment, falling back to the printed one. Full page caching is now safe for every page, including Support Our Work.
- **Broken `.htaccess` rule - fixed.** The rule meant to block direct requests to theme PHP files contained a stray backslash (`(?\\!index)`), so it never matched. It now reads `^(?\!index\.php$)[^/]+\.php$`.
- **Finding 5 - fixed.** Removed the no-op `cohf_giving_csp` filter.
- **Finding 6 - fixed.** Added `.github/workflows/php-lint.yml`, which runs `php -l` on every PHP file for each push and pull request.
- **New:** the `X-Powered-By` header (PHP version) is removed from responses.

Measured on the live site before this release: about 1 second server response on every request and no page-cache header, so every visit rebuilds the page in PHP. Turning on LiteSpeed Cache (the server already runs LiteSpeed) is the largest remaining speed gain and is now safe for giving.

Still open (admin side): Paystack secret key in `wp-config.php`, SPF/DKIM/DMARC, two-factor login for administrators.
