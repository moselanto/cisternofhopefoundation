# Security audit - Cistern of Hope Foundation theme

Reviewed: 30 September 2026, against `main` at 9.80.0 (`cohf-child` and `cohf`).
Scope: theme code only. Hosting, WordPress core, plugins and wp-admin settings were not inspected.

## Summary

The theme is in good shape for a small NGO site. Output is escaped, inputs are sanitised, and the giving flow is designed correctly: the amount is fixed on the server (`inc/giving-checkout.php`), checked again on verification, and the Paystack webhook checks the HMAC-SHA512 signature with `hash_equals` (`inc/giving-records.php`). Login throttling, username-enumeration blocking, XML-RPC shutdown and security headers are all in place (`inc/security.php`).

The findings below are the gaps that remain, most important first.

## Findings

### 1. High - Page caching will break the giving form after about a day

`template-parts/giving-form.php` prints `wp_create_nonce( 'cohf_giving' )` into the page, and `/wp-json/cohf/v1/initialize` rejects an expired nonce with "This form has expired". WordPress nonces live 12-24 hours. The README recommends full page caching, so once a caching plugin or CDN is added, every donor who gets a cached copy older than a day is refused at checkout.

Fix: exclude the Support Our Work page from page caching (every caching plugin has a URL exclusion list), or fetch the nonce with a small uncached request when the form loads.

### 2. High - Behind a proxy or CDN, all rate limits collapse into one shared bucket

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
