# Implementation log

## 14.18.0 - 20 October 2026 (Phase A)

| Change | Evidence | Files | Rollback |
|---|---|---|---|
| Removed guessed Facebook `sameAs` fallback | Audit P0: unverified identity | `inc/schema.php` | Save the profile in Organisation details |
| Office address no longer names Deliverance Church; map only from verified option | Audit P0: blended another organisation's venue | `inc/schema.php` | Set option `cohf_org_map_url` |
| Removed `keywords` from organisation node | Keyword list incl. "street children" (not a programme) | `inc/schema.php` | git revert |
| Removed self-referencing hreflang | Single-language site; tags were redundant | `inc/seo.php` | filter `cohf_hreflang` |
| Explicit search/AI crawler groups in robots.txt; cart/checkout/account/search disallowed | Baseline robots | `inc/ai-discovery.php` | remove module from `functions.php` |
| `/llms.txt` organisation summary | Was 404 | `inc/ai-discovery.php` | same |

## Next phases (need Foundation input)

- **B:** de-duplicate "NGO in Kenya" across page keyword lists after Search Console export.
- **C:** "At a glance" answer blocks on programme pages (beneficiaries, verified areas, model, status, source date), dated reports, more consented stories.
- **D:** Core Web Vitals once LiteSpeed Cache is on.
