<div align="center">

<img src="cohf-child/assets/images/brand/logo-full@2x.png" alt="Cistern of Hope Foundation" width="300">

# Cistern of Hope Foundation

### WordPress platform for [cisternofhopefoundation.org](https://cisternofhopefoundation.org/)

**Together for a lasting change.** A Nairobi-based NGO restoring hope, promoting dignity, empowering people and strengthening communities in Kenya, with online giving and a social-enterprise shop, the **Hope Market**.

![WordPress](https://img.shields.io/badge/WordPress-6.2%2B-21759B?logo=wordpress&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?logo=php&logoColor=white)
![WooCommerce](https://img.shields.io/badge/Hope%20Market-WooCommerce-96588A?logo=woocommerce&logoColor=white)
![Paystack](https://img.shields.io/badge/Giving-Paystack-0BA4DB)
![Child theme](https://img.shields.io/badge/Child%20theme-v14.2.0-1f5e3b)
![Base theme](https://img.shields.io/badge/Base%20theme-v9.7.0-2f4f3a)
![WCAG](https://img.shields.io/badge/Accessibility-WCAG%202.1%20AA-2e7d32)
![No page builder](https://img.shields.io/badge/Build-no%20page%20builder%2C%20zero%20external%20requests-111111)
![License](https://img.shields.io/badge/License-GPLv2%2B-blue)
![Status](https://img.shields.io/badge/Status-Live-brightgreen)

[Live site](https://cisternofhopefoundation.org/) · [Programmes](https://cisternofhopefoundation.org/programmes-overview/) · [Impact](https://cisternofhopefoundation.org/impact/) · [Impact Stories](https://cisternofhopefoundation.org/stories/) · [Hope Market](https://cisternofhopefoundation.org/shop/) · [Support Our Work](https://cisternofhopefoundation.org/support-our-work/) · [Theme guide](cohf-child/README.md) · [Security audit](cohf-child/docs/SECURITY-AUDIT.md)

</div>

---

![Cistern of Hope Foundation homepage](docs/screenshots/home-desktop.jpg)

## Contents

- [Overview](#overview)
- [Screenshots](#screenshots)
- [What the site delivers](#what-the-site-delivers)
- [Architecture](#architecture)
- [Content model](#content-model)
- [Giving and the Hope Market](#giving-and-the-hope-market)
- [Content integrity rules](#content-integrity-rules)
- [SEO, accessibility, performance and security](#seo-accessibility-performance-and-security)
- [Project structure](#project-structure)
- [Requirements and installation](#requirements-and-installation)
- [Editing the site](#editing-the-site)
- [Development rules](#development-rules)
- [Organisation](#organisation)

## Overview

Cistern of Hope Foundation (COHF) began in 2021 in Uthiru, Nairobi, during the COVID-19 pandemic. What started with food and essential supplies has grown into a wider movement focused on dignity, empowerment, opportunity and lasting change: education and scholarships, menstrual health, women's and youth enterprise, widows' care, counselling and humanitarian support.

This repository holds the two themes that power the live site. No page builder and no premium plugin are required, and the front end makes **zero external font or script requests**.

| Package | Folder | Version | Role |
| --- | --- | --- | --- |
| **COHF Base** (parent theme) | [`cohf/`](cohf) | 9.7.0 | Lightweight, dependency-free document structure, menus and template fallbacks. Never activated directly |
| **Cistern of Hope Foundation** (child theme) | [`cohf-child/`](cohf-child) | 14.2.0 | **The site.** Design system, content types, templates, giving engine, Hope Market, SEO, accessibility, performance, security and admin experience |

> **Giving is kept separate from the shop.** Donations go through Paystack on their own server-side flow; the Hope Market runs on WooCommerce. A donation never passes through the cart, and the server never trusts an amount sent from the browser.

## Screenshots

### Desktop

| Programmes | Programme page: Menstrual Health, Hygiene & Dignity |
| --- | --- |
| ![Programmes](docs/screenshots/programmes.jpg) | ![Menstrual health programme](docs/screenshots/programme-menstrual-health.jpg) |
| **Impact** | **Impact Stories** |
| ![Impact](docs/screenshots/impact.jpg) | ![Impact stories](docs/screenshots/stories.jpg) |
| **Story: from one machine to a growing tailoring business** | **Photo gallery** |
| ![Tailoring story](docs/screenshots/story-tailoring.jpg) | ![Gallery](docs/screenshots/gallery.jpg) |
| **Hope Market shop** | **Support Our Work (Paystack giving)** |
| ![Hope Market](docs/screenshots/hope-market-shop.jpg) | ![Support our work](docs/screenshots/support-our-work.jpg) |
| **Leadership & Governance** | **Accountability** |
| ![Leadership](docs/screenshots/leadership.jpg) | ![Accountability](docs/screenshots/accountability.jpg) |
| **About** | **Get Involved** |
| ![About](docs/screenshots/about.jpg) | ![Get involved](docs/screenshots/get-involved.jpg) |

### Homepage sections

| Shop with purpose | Services you can book today |
| --- | --- |
| ![Hope Market on the homepage](docs/screenshots/home-hope-market.jpg) | ![Services and our story](docs/screenshots/home-services-story.jpg) |
| **From support to self-reliance** | **Built around real community needs** |
| ![Our approach](docs/screenshots/home-approach.jpg) | ![Programmes on the homepage](docs/screenshots/home-programmes.jpg) |
| **Turning hope into meaningful change** | **Impact stories** |
| ![Impact figures](docs/screenshots/home-impact-figures.jpg) | ![Stories on the homepage](docs/screenshots/home-stories.jpg) |

### Mobile

<p align="center">
  <img src="docs/screenshots/home-mobile.jpg" alt="Mobile homepage" width="240">
  &nbsp;&nbsp;
  <img src="docs/screenshots/shop-mobile.jpg" alt="Mobile Hope Market" width="240">
  &nbsp;&nbsp;
  <img src="docs/screenshots/support-mobile.jpg" alt="Mobile giving page" width="240">
  <br>
  <sub>Mobile-first layout with a quick-actions bar, floating WhatsApp button and two-per-row shop cards</sub>
</p>

<sub>Screenshots captured from the live site on 5 October 2026.</sub>

## What the site delivers

### Programme areas

<table>
  <tr>
    <td align="center" width="25%"><img src="cohf-child/assets/images/gallery-three-boys-at-school.jpg" width="170" alt="Education"><br><sub><b>Education, Scholarship & Child Development</b></sub></td>
    <td align="center" width="25%"><img src="cohf-child/assets/images/gallery-school-pads-celebration.jpg" width="170" alt="Menstrual health"><br><sub><b>Menstrual Health, Hygiene & Dignity</b></sub></td>
    <td align="center" width="25%"><img src="cohf-child/assets/images/gallery-womens-enterprise-stall.jpg" width="170" alt="Women's enterprise"><br><sub><b>Women's Enterprise & Economic Empowerment</b></sub></td>
    <td align="center" width="25%"><img src="cohf-child/assets/images/gallery-enterprise-visit-eggs.jpg" width="170" alt="Youth skills"><br><sub><b>Youth Skills, Enterprise & Employability</b></sub></td>
  </tr>
  <tr>
    <td align="center"><img src="cohf-child/assets/images/gallery-widows-food-support.jpg" width="170" alt="Widows' care"><br><sub><b>Widows' Care & Food Support</b></sub></td>
    <td align="center"><img src="cohf-child/assets/images/gallery-womens-seminar.jpg" width="170" alt="Counselling"><br><sub><b>Counselling, Mentorship & Life Skills</b></sub></td>
    <td align="center"><img src="cohf-child/assets/images/gallery-food-supplies.jpg" width="170" alt="Humanitarian assistance"><br><sub><b>Humanitarian Assistance & Household Resilience</b></sub></td>
    <td align="center"><img src="cohf-child/assets/images/gallery-childrens-home-group.jpg" width="170" alt="Children's homes"><br><sub><b>...and more of the twelve programme areas</b></sub></td>
  </tr>
</table>

### Key features

**Public site**
- **Editorial design system** in Hope Green, Sunlight gold and warm ivory, with self-hosted Inter and Playfair Display variable fonts
- **Twelve programme areas**, each with strategic purpose, activities, indicators and related stories
- **Impact** page and homepage figures shown with a visible "current reported programme figure" qualifier
- **Impact Stories** from the field, published only with recorded consent
- **Photo gallery** of the Foundation's real work, with categories and before-and-after labelling
- **Leadership & Governance**, **Partners**, **Accountability** (safeguarding, complaints and feedback, finance), **Strategic Journey 2026-2030** and **Resources** (reports, policies, publications)
- **Support Our Work**: one-off and recurring giving through Paystack
- **Hope Market**: a WooCommerce shop for Kenyan crafts and fashion, with a cart drawer, live search, random product rotation, a closable promo bar, and bookable services from the entrepreneurs the Foundation supports
- Mobile quick-actions bar, floating WhatsApp button and a full set of legal pages: privacy, terms, refunds and returns, delivery, and donation policy

**Admin experience**
- **Foundation > Site guide**: plain-English editing map for staff
- **Foundation > Organisation details**: phone, email, address and social links in one place
- **Foundation > Site Text**: every theme wording editable from wp-admin
- **Foundation > Giving**: Paystack keys, plans and donation records
- **Run one-time setup**: creates every page, the twelve programmes, seven leadership profiles, menus and the front page, and never overwrites existing content
- Customizer controls for photos and impact figures, and warnings about missing alt text

## Architecture

### Theme layers

```mermaid
flowchart TB
    subgraph BASE["cohf/ (COHF Base, never activated)"]
        B["Document structure · menus · template fallbacks"]
    end
    subgraph CHILD["cohf-child/ (active theme)"]
        T["Templates<br/>page-templates · single-* · archive-* · template-parts"]
        CSS["CSS layers<br/>prototype → wp-adapt → design-system → ux-refinements → style"]
        INC["inc/ modules (load on every request)"]
    end
    CHILD -- "Template: cohf" --> BASE
    INC --> CT["Content<br/>post types · fields · seeds · site text"]
    INC --> GV["Giving<br/>giving · giving-checkout · giving-records"]
    INC --> SH["Hope Market<br/>shop · cart · checkout · search · categories"]
    INC --> QA["Quality<br/>seo · schema · rank-math · accessibility<br/>performance · security · anti-spam"]
    GV --> PS["Paystack"]
    SH --> WC["WooCommerce"]
    CT --> DB[("WordPress database")]
```

### Donation flow (kept apart from the shop)

```mermaid
sequenceDiagram
    autonumber
    participant D as Donor
    participant P as Support Our Work page
    participant R as REST: giving-checkout
    participant PS as Paystack
    participant W as Webhook: giving-records
    participant DB as Donation records
    D->>P: Choose amount, one-off or monthly
    P->>R: Fresh nonce (no-cache endpoint) + request
    R->>R: Validate and set amount server-side
    R->>PS: Initialise transaction
    PS-->>D: Secure Paystack checkout
    PS->>W: Signed webhook
    W->>PS: Verify transaction
    W->>DB: Record verified donation
```

### Hope Market order flow

```mermaid
flowchart LR
    V["Visitor"] --> H["Homepage: Shop with purpose<br/>random four per load"]
    V --> S["/shop/<br/>random order · live search · categories"]
    H --> CD["Cart drawer"]
    S --> CD
    CD --> CO["WooCommerce checkout"]
    CO --> F["Order to the Foundation"]
    F --> E["Proceeds fund women's and youth enterprise,<br/>school fees and monthly sanitary pads"]
    V -. "Order on WhatsApp" .-> F
```

### Release pipeline

```mermaid
flowchart LR
    A["Edit in cohf-child/"] --> L["php -l every changed file"]
    L --> V["Bump COHF_CHILD_VERSION<br/>and style.css Version"]
    V --> G["Commit to main"]
    G --> U["Upload theme to hosting"]
    U --> C["Check home, Support Our Work<br/>and a programme page on a phone"]
    C --> LIVE["Live on cisternofhopefoundation.org"]
```

> **Deployment note:** commits do **not** deploy automatically. Upload the changed theme folder to hosting for updates to go live. If giving code changed, run a test donation in Paystack test mode first.

## Content model

```mermaid
erDiagram
    PROGRAMME_AREA ||--o{ PROGRAMME : groups
    PROGRAMME ||--o{ STORY : "told through"
    PROGRAMME ||--o{ REPORT : "reported in"
    LOCATION ||--o{ STORY : where
    TEAM_GROUP ||--o{ LEADER : groups
    PHOTO_CAT ||--o{ PHOTO : groups
    YEAR ||--o{ REPORT : year
```

| Post type | Purpose |
| --- | --- |
| `cohf_programme` | Programme area: purpose, activities and indicators |
| `cohf_story` | Impact Story; **cannot be published until consent is confirmed** |
| `cohf_news` / `cohf_event` | Announcements, community programmes and forums |
| `cohf_report` / `cohf_resource` | Annual and programme reports (PDF), policies and publications |
| `cohf_leader` | Leadership and governance profiles |
| `cohf_partner` | Partner; **hidden until the partnership is confirmed in writing** |
| `cohf_photo` / `cohf_video` | Gallery photographs and videos |
| WooCommerce `product` | Hope Market items |

Taxonomies: `cohf_programme_area`, `cohf_location`, `cohf_audience`, `cohf_content_type`, `cohf_team_group`, `cohf_year`, `cohf_photo_cat`.

## Giving and the Hope Market

| | Giving | Hope Market |
| --- | --- | --- |
| Purpose | Donations to the Foundation | Buying crafts and booking services |
| Engine | Paystack, server-side initialisation and verification | WooCommerce |
| Code | `inc/giving.php`, `giving-checkout.php`, `giving-records.php` | `inc/shop*.php`, `home-market.php` |
| Amount | Set and checked on the server | WooCommerce prices |
| Records | Verified donation records in wp-admin | WooCommerce orders |
| Caching | Fresh nonce from a no-cache REST endpoint, so full-page caching stays safe | Shop listings excluded from page cache so the random order can change |

## Content integrity rules

Enforced in code, not just documented:

- An **Impact Story cannot be published** until *Consent confirmed* is ticked; it reverts to draft with an explanation.
- A **Partner stays hidden** until *Partnership confirmed in writing* is ticked.
- **No statistic is hard-coded** except in `inc/template-tags.php`, where each figure names its source document; figures are editable in the Customizer.
- Every reported figure carries a visible qualifier that it is a **current reported programme figure, not a lifetime total**.
- Seed functions **never overwrite** existing content.

## SEO, accessibility, performance and security

**SEO**
- Rank Math integration: the theme's own schema, Open Graph and breadcrumbs stand down when Rank Math or Yoast is active, and auto-filled Rank Math fields are refreshed without touching hand edits
- Keywords matched to what people in Kenya search
- Structured data: `NGO`, `WebSite` + `SearchAction`, `BreadcrumbList`, `DonateAction`, `Service`, `OfferCatalog`, `Offer` with `OfferShippingDetails` and `MerchantReturnPolicy`, `ImageGallery`, `Person`, `Article` and `PeopleAudience`

**Accessibility (WCAG 2.1 AA intent)**
- Skip link, one `h1` per page, ordered headings, 3px gold focus rings and 44px touch targets
- Keyboard-operable menus that close on `Escape`, `aria-pressed` filters with live-region announcements
- Contrast: body text 12.9:1, white on Hope Green 9.8:1; `prefers-reduced-motion` honoured; nothing hidden without JavaScript

**Performance**
- Small layered stylesheets and deferred vanilla JavaScript, no jQuery, no framework, no icon font
- Self-hosted variable WOFF2 fonts with preload; zero external requests
- Hero image with `fetchpriority="high"`, everything else lazy-loaded; WebP and AVIF uploads; `filemtime` cache-busting; safe for full-page caching (LiteSpeed)

**Security** (see [`SECURITY-AUDIT.md`](cohf-child/docs/SECURITY-AUDIT.md))
- Escaped output, sanitised input, nonces and capability checks on every save
- Forms: nonce, honeypot, time trap, rate limit, anti-spam scoring and an outgoing-mail circuit breaker
- `DISALLOW_FILE_EDIT`, XML-RPC pingbacks off, non-enumerable login errors, `X-Powered-By` hidden, security headers, and `.htaccess` rules blocking direct access to theme PHP

## Project structure

```text
.
├── README.md
├── AGENTS.md                      # Rules for anyone (or any AI agent) changing code
├── docs/screenshots/              # README images captured from the live site
├── cohf/                          # COHF Base parent theme (rarely changes)
└── cohf-child/                    # The site
    ├── README.md                  # Install, editing map and file map
    ├── docs/SECURITY-AUDIT.md     # Findings and fixes
    ├── functions.php  style.css   # Loads inc/ modules; version header
    ├── page-templates/            # Home, About, Programmes, Impact, Approach, Leadership,
    │                              # Partners, Resources, Contact, Get Involved, Support,
    │                              # Accountability, Strategy, Gallery and legal pages
    ├── single-cohf_*.php          # Programme, story, news, event, report, resource,
    ├── archive-cohf_*.php         # leader and video views
    ├── woocommerce.php            # Hope Market wrapper
    ├── template-parts/            # Hero, cards, CTA, numbers, theory of change,
    │                              # timeline, giving and enquiry forms, legal text
    ├── inc/                       # 40+ modules: content, giving, shop, SEO, schema,
    │                              # accessibility, performance, security, anti-spam
    └── assets/
        ├── css/                   # prototype, wp-adapt, design-system, ux-refinements,
        │                          # shop, cart-drawer, gallery, editor
        ├── js/                    # main, navigation, giving, shop, cart-drawer,
        │                          # shop-search, gallery, leadership, forms
        ├── fonts/                 # Inter + Playfair Display variable WOFF2
        └── images/                # brand, leaders, hero, programme, story and gallery photos
```

## Requirements and installation

| Component | Version |
| --- | --- |
| WordPress | 6.2 or later |
| PHP | 8.0 or later |
| WooCommerce | For the Hope Market |
| Paystack account | For online giving |
| Recommended | Rank Math, LiteSpeed Cache, SMTP plugin |

1. Upload **both** `cohf/` and `cohf-child/` to `wp-content/themes/`.
2. Activate **Cistern of Hope Foundation** (the child). The parent must be present but is never activated.
3. On the dashboard, click **Run one-time setup**.
4. **Settings > Permalinks** > Save once.
5. **Appearance > Customise > Site Identity**: upload the logo.
6. Enter the Paystack keys and webhook URL under **Foundation > Giving**.

## Editing the site

| To change | Go to |
| --- | --- |
| A programme's description, activities, indicators | **Programmes** |
| A story from the field | **Impact Stories** |
| Announcements and events | **News** / **Events** |
| Reports, policies and publications | **Reports** / **Resources** |
| Team members and partners | **Leadership** / **Partners** |
| Phone, email, address, social links | **Foundation > Organisation details** |
| Any theme wording | **Foundation > Site Text** |
| Shop products and orders | **Products** / **WooCommerce > Orders** |

Full guide: [`cohf-child/README.md`](cohf-child/README.md).

## Development rules

From [`AGENTS.md`](AGENTS.md):

1. **Never invent facts.** Figures, dates, partners, testimonials and names come only from the Foundation.
2. **Content should be editable in WordPress**, not hard-coded in PHP.
3. **Escape on output, sanitise on input**, with nonces and capability checks on every save.
4. **Giving is separate from the shop.** Never route a donation through the cart or trust a browser amount.
5. **Respect the CSS load order** and put fixes in the latest layer that applies.
6. **Bump the version** in `functions.php` and `style.css` for every release.
7. **Accessibility is enforced.**
8. **Every `inc/` file loads on every request**: run `php -l` before committing.

## Organisation

**Cistern of Hope Foundation**
Office: Kabete, behind N Market, Nairobi, Kenya
Postal: P.O. Box 23524-00625, Nairobi, Kenya
Phone and WhatsApp: [+254 110 304 521](tel:+254110304521) · Email: [info@cisternofhopefoundation.org](mailto:info@cisternofhopefoundation.org)

[Support Our Work](https://cisternofhopefoundation.org/support-our-work/) · [Partner With Us](https://cisternofhopefoundation.org/partners-overview/) · [Volunteer](https://cisternofhopefoundation.org/get-involved/) · [Shop the Hope Market](https://cisternofhopefoundation.org/shop/)

## Credits

Designed, developed and maintained by **[Pimofy Digital](https://github.com/moselanto)**, Nairobi.
Built only from the Foundation's own documents: no statistic, partner, testimonial or financial figure has been invented. Photographs belong to Cistern of Hope Foundation. Fonts: Inter and Playfair Display (SIL Open Font License).

## License

GNU General Public License v2 or later. See the [license text](http://www.gnu.org/licenses/gpl-2.0.html).

<div align="center"><sub><i>Together for a lasting change.</i></sub></div>
