# Validation report

| Check | 14.18.0 result |
|---|---|
| PHP lint | Not run in build environment (no PHP available). Run `php -l` on `inc/ai-discovery.php`, `inc/schema.php`, `inc/seo.php` before upload. |
| JSON-LD parse (live, pre-release) | 6/6 representative pages valid, one graph each |
| robots.txt after release | To check: https://cisternofhopefoundation.org/robots.txt shows the crawler groups and `Sitemap:` line |
| llms.txt after release | To check: https://cisternofhopefoundation.org/llms.txt returns 200, text/plain |
| Schema validator | To run after release on home, programme, story, Support, product |
| Payments / shop untouched | No giving, checkout or form code changed |
