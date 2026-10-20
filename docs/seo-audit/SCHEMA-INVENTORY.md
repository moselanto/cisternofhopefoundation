# Schema inventory and ownership

**Ownership rule:** Rank Math owns base metadata and the base JSON-LD graph. The theme enriches that graph (`inc/rank-math.php`) and only prints its own graph (`inc/schema.php`) when no SEO plugin is active (`cohf_seo_plugin_active()`). Never add a second, parallel graph.

| Template | Nodes | Source |
|---|---|---|
| All pages | `#organization` (NGO + NonprofitOrganization), `#website` | Rank Math + theme enrichment |
| Pages | WebPage / AboutPage / ContactPage / CollectionPage / FAQPage (Partners, visible FAQ only) | Rank Math + theme |
| Support Our Work | DonateAction (`/support-our-work/#donate`) | theme |
| Programme | Service, linked to `#organization` | theme |
| Story | Article (fallback only when Rank Math emits none) | theme |
| Leader | Person | theme |
| Shop / product | CollectionPage + ItemList; Product + Offer + shipping + return policy | Rank Math / WooCommerce + `cohf_schema_product` |

## Organisation node facts and their source

| Property | Value source | Status |
|---|---|---|
| name, alternateName, slogan, description, foundingDate, foundingLocation, email, telephone | Foundation > Organisation details (`cohf_org()`) | editable |
| address (office) | "Behind N Market", Kabete, Kiambu (filter `cohf_office_street`) | **14.18.0: church name removed** |
| hasMap | option `cohf_org_map_url` only | **14.18.0: no longer a search for another venue; omitted until a verified pin is saved** |
| sameAs | saved social links only | **14.18.0: guessed Facebook fallback removed** |
| keywords | - | **14.18.0: removed (keyword list, included "street children")** |
| founder | Justus Kubai, Founder and Executive Director | needs Foundation confirmation |
| areaServed | Kenya, Nairobi, Kiambu County | confirm Kiambu delivery evidence |

## Validation

After each release: run the homepage, one programme, one story, Support Our Work and one product through https://validator.schema.org and https://search.google.com/test/rich-results. Valid markup does not guarantee a rich result.
