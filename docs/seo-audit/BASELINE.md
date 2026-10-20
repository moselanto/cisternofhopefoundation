# SEO baseline - cisternofhopefoundation.org

Captured 20 October 2026, 13:30 EAT, with `curl` (desktop user agent) from the build sandbox. Release live at capture: 14.17.x. Rank Math is **active** (comments in page source, `sitemap_index.xml`).

## robots.txt (live, before 14.18.0)

```
User-agent: *
Disallow: /wp-content/uploads/wc-logs/
Disallow: /wp-content/uploads/woocommerce_transient_files/
Disallow: /wp-content/uploads/woocommerce_uploads/
Disallow: /*?add-to-cart=
Disallow: /*?*add-to-cart=
Disallow: /wp-admin/
Allow: /wp-admin/admin-ajax.php
Sitemap: https://cisternofhopefoundation.org/sitemap_index.xml
```

No AI crawler is blocked. Cart, checkout and account are not disallowed. `/llms.txt` returned 404.

## Sitemaps

`sitemap_index.xml` lists: page, product, cohf_programme (13 programmes + archive), cohf_story, cohf_leader, cohf_partner, product_cat. All 13 programme slugs match the keys in `inc/seo-keywords.php` (including `widows-care-food-support`), so the slug mismatch flagged in the initial audit is **not present**.

## Representative pages

| URL | Title | Canonical | Robots | JSON-LD nodes |
|---|---|---|---|---|
| `/` | Cistern of Hope Foundation: NGO in Nairobi, Kenya | self | index, follow, max-image-preview:large | NGO+NonprofitOrganization, WebSite, WebPage |
| `/about/` | About Our Charity Foundation in Kenya \| Cistern of Hope | self | index | Org, WebSite, WebPage |
| `/programmes-overview/` | Our Programmes: Education, Pads, Youth and Women | self | index | Org, WebSite, WebPage |
| `/partners-overview/` | NGO Partnerships and CSR in Kenya \| Cistern of Hope | self | index | Org, WebSite, WebPage |
| `/support-our-work/` | Donate to Charity in Kenya by M-Pesa or Card \| Cistern of Hope | self | index | Org, WebSite, WebPage, DonateAction |
| `/shop/` | Hope Market: Kenya Handmade Crafts Online \| Cistern of Hope | self | index | Org, WebSite, CollectionPage, ItemList |

All JSON-LD parsed as valid JSON; one graph per page (Rank Math graph enriched by the theme), no duplicate Organization/WebSite nodes.

## Head tags on every page (before 14.18.0)

- `hreflang="en-KE"` and `hreflang="x-default"` both pointing at the same URL (single-language site).
- `geo.region=KE-30`, `geo.placename=Nairobi, Kenya`.

## Not verified (needs access we do not have)

- Google Search Console performance, indexing and the generative-AI inclusion setting.
- Bing Webmaster Tools, GA4 conversions.
- Hosting/WAF rules for crawlers (only public responses were tested).
- Core Web Vitals field data (CrUX).
- Facebook page ownership, exact office map pin, legal registration number.
