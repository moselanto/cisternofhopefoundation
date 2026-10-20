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
| hasMap | option `cohf_org_map_url`, default = office pin confirmed by the Foundation (https://share.google/IaisvdIi4ZaCKUFms) | **confirmed 20 Oct 2026** |
| sameAs | saved social links; Facebook page confirmed by the Foundation and set as the default | **confirmed 20 Oct 2026** |
| keywords | - | **14.18.0: removed (keyword list, included "street children")** |
| founder | Justus Kubai, Founder and Executive Director | **confirmed 20 Oct 2026** |
| areaServed | Kenya, Nairobi, Kiambu County | **confirmed 20 Oct 2026: the Foundation ministers in Kiambu** |
| Legal registration number / authority | not published | **Foundation decision 20 Oct 2026: do not display registration details anywhere (site, schema, llms.txt)** |

## Validation

After each release: run the homepage, one programme, one story, Support Our Work and one product through https://validator.schema.org and https://search.google.com/test/rich-results. Valid markup does not guarantee a rich result.
