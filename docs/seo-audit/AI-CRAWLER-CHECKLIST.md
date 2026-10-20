# AI and search crawler checklist

Implemented in `cohf-child/inc/ai-discovery.php` (14.18.0).

## robots.txt policy

| Crawler | Purpose | Policy |
|---|---|---|
| Googlebot, Bingbot | Search | Allowed on public pages |
| OAI-SearchBot, ChatGPT-User | ChatGPT search and user-requested fetches | Allowed on public pages |
| PerplexityBot, Applebot | Answer engines / Siri & Spotlight | Allowed on public pages |
| GPTBot, Google-Extended, CCBot, ClaudeBot, Applebot-Extended | Model training | Follow default rules. Opt out with `add_filter( 'cohf_block_ai_training', '__return_true' );` |
| All | - | Never: wp-admin, wp-login, cart, checkout, my-account, search results, add-to-cart, WooCommerce private uploads |

## /llms.txt

Generated from Organisation details, key pages, published programmes and consented stories; cached 12 h, rebuilt when programmes, stories, pages or organisation details change. It aids discovery; it is not a guarantee of inclusion or citation.

## Manual checks (owner)

1. Search Console > Settings: confirm the site is **not** excluded from generative AI features; record date and value here.
2. Bing Webmaster Tools: add the site, submit `sitemap_index.xml`, enable IndexNow (Rank Math > Instant Indexing).
3. Hosting firewall / LiteSpeed: confirm no rule blocks the bots above (check access logs for 403s).
4. Google Business Profile: one verified profile with the same name, phone, address and website as the site.
