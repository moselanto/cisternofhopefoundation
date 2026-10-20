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

## 14.18.1 - 20 October 2026 (Foundation confirmations)

- Facebook page confirmed; set as default `facebook` in Organisation details so `sameAs` lists it.
- Office map pin confirmed (https://share.google/IaisvdIi4ZaCKUFms); default for `cohf_org_map_url`, used as `hasMap`.
- Founder confirmed: Justus Kubai, Founder and Executive Director.
- Registration details: Foundation does **not** want them displayed. Rule for all agents: never add a registration number, authority or certificate details to pages, schema or llms.txt.
- Kiambu County confirmed as an area served (ministry visits); `areaServed` kept.

## 14.19.0 - 20 October 2026 (Phase C start: AEO answer blocks)

- New programme fields: Quick answer, Where it runs, How it is delivered, Current status, Facts last checked (`inc/custom-fields.php`).
- New `template-parts/programme-glance.php` printed under the programme hero: plain answer + key facts + checked date. Falls back to the existing strategic purpose and "Who it serves"; empty fields are hidden, nothing is invented.
- Staff fill the fields per programme in wp-admin > Programmes.

## 14.20.0 - 20 October 2026 (live verification of 14.19.0)

Verified live at 14:20 EAT: robots.txt crawler groups present, `/llms.txt` 200 text/plain, hreflang gone, organisation `sameAs` = confirmed Facebook page, `hasMap` = confirmed pin, no `keywords`, At a glance block rendering on programme pages.

Found and fixed:
- Programme pages printed only a `Service` node whose `provider` pointed at an organisation not present on the page (Rank Math schema type for programmes is None). Added organisation, WebPage and BreadcrumbList to the same graph, only when missing (`inc/rank-math.php`). Service description now uses the Quick answer when filled.
- `/llms.txt` showed HTML entities (`&#038;`, `&#8217;`) in titles. Now decoded to plain text (`inc/ai-discovery.php`); cache key bumped so the fix shows immediately.

## Next phases (need Foundation input)

- **B:** de-duplicate "NGO in Kenya" across page keyword lists after Search Console export.
- **C:** "At a glance" answer blocks on programme pages (beneficiaries, verified areas, model, status, source date), dated reports, more consented stories.
- **D:** Core Web Vitals once LiteSpeed Cache is on.
