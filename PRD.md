# Product requirements - cisternofhopefoundation.org

## Purpose

Give Cistern of Hope Foundation (Nairobi, Kenya; founded 2021 in Uthiru) a trustworthy, fast, accessible website that explains its work, proves its impact honestly, raises donations and sells Hope Market products whose proceeds fund its programmes.

## Users

| User | Needs |
| --- | --- |
| Donors (Kenya and abroad) | Understand the work, trust it, give once or monthly by card or M-Pesa |
| Partners and funders | Programmes, governance, accountability, reports |
| Community members / beneficiaries | Contact, programmes, events, WhatsApp |
| Shoppers | Browse and buy crafts and services, or order on WhatsApp |
| Volunteers | Ways to get involved, enquiry form |
| Foundation staff | Edit everything in wp-admin without code; see donations and orders |

## Functional requirements (must not regress)

1. Pages: Home, About, Programmes (12 areas), Impact, Impact Stories, Approach, Leadership & Governance, Partners, Resources, Accountability, Strategy 2026-2030, Gallery, Get Involved, Contact, Support Our Work, legal pages (privacy, terms, returns, delivery, donation policy).
2. Giving: one-off and monthly via Paystack; amount set and checked on the server; webhook records verified donations; donor and Foundation notified.
3. Hope Market: WooCommerce shop, cart drawer, live search, categories, random rotation, WhatsApp ordering, homepage "Shop with purpose".
4. Content integrity: stories need recorded consent; partners need written confirmation; figures carry a "current reported programme figure" qualifier; no invented data.
5. Editability: Organisation details, Site Text, Customizer figures/photos, Giving settings, one-time setup that never overwrites content.
6. Contact/enquiry form with spam protection and email delivery.
7. SEO and structured data; steps aside for Rank Math/Yoast.

## Non-functional requirements

- **Security:** OWASP Top 10 coverage as recorded in `SECURITY-AUDIT.md`; no secrets in code; HTTPS only; rate limits on every public endpoint.
- **Accessibility:** WCAG 2.1 AA intent.
- **Performance:** no external fonts/scripts (except Paystack on the giving page), page-cache safe, lazy images.
- **Maintainability:** every change documented; CI lint; version bump per release.

## Out of scope

Page builders, premium plugins, storing card data (Paystack handles it), user-generated comments.
