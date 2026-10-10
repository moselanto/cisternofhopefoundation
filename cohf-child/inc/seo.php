<?php
/**
 * SEO support.
 *
 * The theme provides semantic structure, Open Graph, Twitter/X cards and
 * Organization schema ONLY when a dedicated SEO plugin is not already doing
 * the job. Rank Math and Yoast both take precedence automatically, so nothing
 * is ever duplicated.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is a dedicated SEO plugin active?
 *
 * @return bool
 */
function cohf_seo_plugin_active() {
	return defined( 'RANK_MATH_VERSION' ) || defined( 'WPSEO_VERSION' ) || defined( 'AIOSEO_VERSION' ) || class_exists( 'SEOPress' );
}

/**
 * Organization / NGO schema.
 *
 * Emitted as JSON-LD from the Foundation's own verified details only.
 * Suppressed when an SEO plugin is managing schema.
 */
function cohf_organization_schema() {
	if ( ! is_front_page() || cohf_seo_plugin_active() ) {
		return;
	}

	$org = cohf_org();

	$data = array(
		'@context'      => 'https://schema.org',
		'@type'         => 'NGO',
		'name'          => $org['name'],
		'alternateName' => $org['abbr'],
		'slogan'        => $org['motto'],
		'url'           => home_url( '/' ),
		'foundingDate'  => $org['founded'],
		'description'   => $org['mission'],
		'address'       => array(
			'@type'           => 'PostalAddress',
			'postOfficeBoxNumber' => '23524–00625',
			'addressLocality' => 'Nairobi',
			'addressCountry'  => 'KE',
		),
		'areaServed'    => array(
			'@type' => 'Country',
			'name'  => 'Kenya',
		),
		'contactPoint'  => array(
			'@type'       => 'ContactPoint',
			'contactType' => 'general enquiries',
			'telephone'   => $org['phone'],
			'email'       => $org['email'],
			'areaServed'  => 'KE',
		),
		'nonprofitStatus' => 'NonprofitType',
	);

	if ( function_exists( 'cohf_social_links' ) ) {
		$same_as = array_values( wp_list_pluck( cohf_social_links(), 'url' ) );
		if ( $same_as ) {
			$data['sameAs'] = $same_as;
		}
	}

	$logo = get_theme_mod( 'custom_logo' );
	if ( $logo ) {
		$src = wp_get_attachment_image_src( $logo, 'full' );
		if ( $src ) {
			$data['logo'] = $src[0];
		}
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'cohf_organization_schema', 20 );

/**
 * Article schema for impact stories and news.
 */
function cohf_article_schema() {
	if ( cohf_seo_plugin_active() || ! is_singular( array( 'cohf_story', 'cohf_news' ) ) ) {
		return;
	}

	$data = array(
		'@context'         => 'https://schema.org',
		'@type'            => 'Article',
		'headline'         => wp_strip_all_tags( get_the_title() ),
		'datePublished'    => get_the_date( 'c' ),
		'dateModified'     => get_the_modified_date( 'c' ),
		'mainEntityOfPage' => get_permalink(),
		'publisher'        => array(
			'@type' => 'NGO',
			'name'  => cohf_org_get( 'name' ),
		),
	);

	if ( has_post_thumbnail() ) {
		$data['image'] = get_the_post_thumbnail_url( null, 'cohf-wide' );
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'cohf_article_schema', 21 );

/**
 * Open Graph and Twitter/X cards (skipped when an SEO plugin handles them).
 */
function cohf_social_meta() {
	if ( cohf_seo_plugin_active() ) {
		return;
	}

	if ( is_front_page() ) {
		$title = __( 'Cistern of Hope Foundation | NGO in Nairobi, Kenya', 'cohf-child' );
		$url   = home_url( '/' );
	} elseif ( is_singular() ) {
		$title = wp_strip_all_tags( get_the_title() );
		$url   = get_permalink();
	} else {
		return;
	}
	$desc = cohf_seo_description();

	$image = is_singular() && has_post_thumbnail()
		? get_the_post_thumbnail_url( null, 'cohf-wide' )
		: COHF_CHILD_URI . '/assets/images/hero-home.jpg';

	$tags = array(
		'og:site_name'   => get_bloginfo( 'name' ),
		'og:title'       => $title,
		'og:description' => wp_trim_words( $desc, 34 ),
		'og:type'        => is_front_page() ? 'website' : 'article',
		'og:url'         => $url,
		'og:locale'      => 'en_KE',
	);
	if ( $image && is_singular() && has_post_thumbnail() ) {
		$tags['og:image'] = $image;
	}

	foreach ( $tags as $property => $content ) {
		printf( '<meta property="%s" content="%s">' . "\n", esc_attr( $property ), esc_attr( $content ) );
	}

	printf( '<meta name="twitter:card" content="%s">' . "\n", $image ? 'summary_large_image' : 'summary' );
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( wp_trim_words( $desc, 34 ) ) );
}
add_action( 'wp_head', 'cohf_social_meta', 5 );

/**
 * Tell search engines the site is written for Kenya and based in Nairobi.
 */
function cohf_geo_meta() {
	if ( is_404() ) {
		return;
	}
	$url = is_singular() ? get_permalink() : home_url( add_query_arg( array() ) );
	if ( is_front_page() ) {
		$url = home_url( '/' );
	}
	printf( '<link rel="alternate" hreflang="en-KE" href="%s">' . "\n", esc_url( $url ) );
	printf( '<link rel="alternate" hreflang="x-default" href="%s">' . "\n", esc_url( $url ) );
	echo '<meta name="geo.region" content="KE-30">' . "\n";
	echo '<meta name="geo.placename" content="Nairobi, Kenya">' . "\n";
}
add_action( 'wp_head', 'cohf_geo_meta', 4 );

/**
 * Breadcrumbs.
 *
 * Uses Rank Math's or Yoast's breadcrumb when present so the site inherits
 * the plugin's schema; otherwise renders an accessible native trail.
 */
function cohf_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
		rank_math_the_breadcrumbs();
		return;
	}
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'cohf-child' ) . '">', '</nav>' );
		return;
	}

	$items = array( array( 'label' => __( 'Home', 'cohf-child' ), 'url' => home_url( '/' ) ) );

	if ( is_singular( array( 'cohf_programme', 'cohf_story', 'cohf_news', 'cohf_event', 'cohf_report', 'cohf_resource' ) ) ) {
		$obj     = get_post_type_object( get_post_type() );
		$archive = get_post_type_archive_link( get_post_type() );
		if ( $obj && $archive ) {
			$items[] = array( 'label' => $obj->labels->name, 'url' => $archive );
		}
		$items[] = array( 'label' => wp_strip_all_tags( get_the_title() ), 'url' => '' );
	} elseif ( is_page() ) {
		foreach ( array_reverse( get_post_ancestors( get_the_ID() ) ) as $ancestor ) {
			$items[] = array( 'label' => get_the_title( $ancestor ), 'url' => get_permalink( $ancestor ) );
		}
		$items[] = array( 'label' => wp_strip_all_tags( get_the_title() ), 'url' => '' );
	} elseif ( is_archive() ) {
		$items[] = array( 'label' => wp_strip_all_tags( get_the_archive_title() ), 'url' => '' );
	} else {
		return;
	}

	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'cohf-child' ) . '"><ol>';
	foreach ( $items as $item ) {
		if ( $item['url'] ) {
			printf( '<li><a href="%s">%s</a></li>', esc_url( $item['url'] ), esc_html( $item['label'] ) );
		} else {
			printf( '<li><span aria-current="page">%s</span></li>', esc_html( $item['label'] ) );
		}
	}
	echo '</ol></nav>';
}

/**
 * Keep custom post types out of the sitemap only when they are unpublished
 * placeholders; otherwise WordPress core sitemaps include them automatically,
 * and Rank Math/Yoast sitemaps pick them up because they are public.
 */
add_filter( 'wp_sitemaps_post_types', function ( $post_types ) {
	// MailPoet's subscription and captcha screens are not content.
	unset( $post_types['mailpoet_page'] );
	return $post_types;
} );

/* -------------------------------------------------------------------------
   Site audit fixes (12.6.0)
   ------------------------------------------------------------------------- */

/**
 * One meta description for every page: the excerpt or product summary,
 * then the page's own opening text, then the term description, then the
 * Foundation's mission. 155 characters, cut on a word.
 *
 * @return string
 */
function cohf_seo_description() {
	$text = '';
	if ( is_front_page() ) {
		$text = __( 'Nairobi-based charity supporting vulnerable children, widows, women and youth in Kenya with school fees, sanitary pads, food and small-business support.', 'cohf-child' );
	} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
		$text = __( 'Hope Market: handmade African crafts, jewellery, baskets, sandals and home decor from Kenya. Every purchase supports the Cistern of Hope Foundation.', 'cohf-child' );
	} elseif ( is_singular() ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			$pages = cohf_seo_page_descriptions();
			if ( 'page' === $post->post_type && isset( $pages[ $post->post_name ] ) && ! has_excerpt( $post ) ) {
				return apply_filters( 'cohf_seo_description', $pages[ $post->post_name ] );
			}
			if ( 'cohf_leader' === $post->post_type && ! has_excerpt( $post ) ) {
				$role = (string) get_post_meta( $post->ID, '_cohf_role', true );
				/* translators: 1: person, 2: role. */
				return $role
					? sprintf( __( 'Meet %1$s, %2$s at Cistern of Hope Foundation, a Kenyan organisation working to eradicate poverty through community-led programmes.', 'cohf-child' ), get_the_title( $post ), $role )
					: sprintf( __( 'Meet %s of the Cistern of Hope Foundation leadership team, working to eradicate poverty through community-led programmes in Kenya.', 'cohf-child' ), get_the_title( $post ) );
			}
			if ( has_excerpt( $post ) ) {
				$text = $post->post_excerpt;
			}
			if ( '' === trim( $text ) && 'product' === $post->post_type && function_exists( 'wc_get_product' ) ) {
				$product = wc_get_product( $post->ID );
				if ( $product ) {
					$text = $product->get_short_description() ? $product->get_short_description() : $product->get_description();
				}
			}
			if ( '' === trim( wp_strip_all_tags( $text ) ) ) {
				$text = strip_shortcodes( excerpt_remove_blocks( $post->post_content ) );
			}
			if ( '' === trim( wp_strip_all_tags( $text ) ) && function_exists( 'cohf_page_intro' ) ) {
				$text = (string) cohf_page_intro( $post->ID );
			}
		}
	} elseif ( is_category() || is_tax() || is_tag() ) {
		$text = term_description();
		if ( '' === trim( wp_strip_all_tags( (string) $text ) ) && is_tax( 'product_cat' ) ) {
			/* translators: %s: category name. */
			$text = sprintf( __( 'Shop handmade %s at Hope Market. Every purchase supports the Cistern of Hope Foundation in Kenya.', 'cohf-child' ), strtolower( single_term_title( '', false ) ) );
		}
	} elseif ( is_post_type_archive() ) {
		$type = get_queried_object();
		$text = ( $type && ! empty( $type->description ) ) ? $type->description : '';
	}
	$text = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $text ) ) );
	if ( '' === $text ) {
		$text = (string) cohf_org_get( 'mission' );
	}
	if ( strlen( $text ) > 155 ) {
		$text = rtrim( substr( $text, 0, 155 ) );
		$text = preg_replace( '/\s+\S*$/', '', $text ) . '...';
	}
	return (string) apply_filters( 'cohf_seo_description', $text );
}

/**
 * Print the meta description (an SEO plugin takes over when active).
 */
function cohf_meta_description() {
	if ( cohf_seo_plugin_active() || is_404() || is_search() ) {
		return;
	}
	$desc = cohf_seo_description();
	if ( '' !== $desc ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
	}
}
add_action( 'wp_head', 'cohf_meta_description', 1 );

/**
 * Correct capitalisation of the organisation name wherever WordPress prints
 * the site title (Settings > General has "Cistern of hope Foundation").
 *
 * @param string $name Site title.
 * @return string
 */
function cohf_fix_site_name( $name ) {
	return ( 0 === strcasecmp( trim( (string) $name ), 'Cistern of Hope Foundation' ) ) ? 'Cistern of Hope Foundation' : $name;
}
add_filter( 'option_blogname', 'cohf_fix_site_name' );

/**
 * Author archives showed a copy of the homepage and revealed the admin
 * login name in the URL (/author/moses/). Send them to the homepage and
 * keep users out of the sitemap.
 */
function cohf_no_author_archives() {
	if ( is_author() ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'cohf_no_author_archives', 2 );
add_filter( 'wp_sitemaps_add_provider', function ( $provider, $name ) {
	return 'users' === $name ? false : $provider;
}, 10, 2 );

/**
 * WordPress starter content (Hello world, Sample Page, Uncategorized) is
 * kept out of search engines until it is deleted in wp-admin.
 */
function cohf_noindex_starter_content( $robots ) {
	if ( is_single( 'hello-world' ) || is_page( 'sample-page' ) || is_category( 'uncategorized' ) ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'cohf_noindex_starter_content' );
add_filter( 'wp_sitemaps_posts_query_args', function ( $args, $post_type ) {
	if ( in_array( $post_type, array( 'post', 'page' ), true ) ) {
		$exclude = array();
		foreach ( array( 'hello-world' => 'post', 'sample-page' => 'page' ) as $slug => $type ) {
			if ( $type === $post_type ) {
				$p = get_page_by_path( $slug, OBJECT, $type );
				if ( $p ) {
					$exclude[] = $p->ID;
				}
			}
		}
		if ( $exclude ) {
			$args['post__not_in'] = array_merge( isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array(), $exclude );
		}
	}
	return $args;
}, 10, 2 );
add_filter( 'wp_sitemaps_taxonomies', function ( $taxonomies ) {
	unset( $taxonomies['category'] ); // Only "Uncategorized" exists; the site uses Stories instead of posts.
	return $taxonomies;
} );

/**
 * Hand-written descriptions for the main pages (keyed by page slug).
 * A page excerpt set in wp-admin overrides these.
 *
 * @return array
 */
function cohf_seo_page_descriptions() {
	return array(
		'about'                 => __( 'About Cistern of Hope Foundation, a Kenyan charity founded in Uthiru, Nairobi in 2021. Our story, vision, mission, values and leadership.', 'cohf-child' ),
		'programmes-overview'   => __( 'Our programmes in Kenya: school fees and scholarships, sanitary pads for girls, youth skills, women\'s enterprise, food support, health and WASH.', 'cohf-child' ),
		'impact'                => __( 'Our impact in Kenya: children back in school, around 200 girls receiving sanitary pads every month, and women and youth running small businesses.', 'cohf-child' ),
		'approach'              => __( 'From support to self-reliance: how we combine compassion with practical action and treat communities as partners, not recipients.', 'cohf-child' ),
		'get-involved'          => __( 'Volunteer in Nairobi, partner with us or donate to a Kenyan charity. Find the way to support vulnerable children, women and youth that suits you.', 'cohf-child' ),
		'partners-overview'     => __( 'Partner with a young, determined Kenyan organisation. The partners we welcome, what we ask partners to contribute and where we need support.', 'cohf-child' ),
		'resources-overview'    => __( 'Reports, strategies, policies and updates from Cistern of Hope Foundation. Search the library or request a document.', 'cohf-child' ),
		'contact'               => sprintf( /* translators: %s: Foundation phone number. */ __( 'Contact Cistern of Hope Foundation, an NGO in Nairobi, Kenya. Visit our Kabete office, call or WhatsApp %s, or send us a message.', 'cohf-child' ), cohf_org_get( 'phone' ) ),
		'leadership-governance' => __( 'Meet the board and team accountable for Cistern of Hope Foundation, and how the Foundation is governed and overseen.', 'cohf-child' ),
		'accountability'        => __( 'How we steward resources, safeguard the people we serve, protect privacy and handle concerns. Raise a concern safely.', 'cohf-child' ),
		'support-our-work'      => __( 'Donate to a Kenyan NGO by M-Pesa or card. Your gift pays school fees, sanitary pads and food for vulnerable children and families in Nairobi.', 'cohf-child' ),
		'strategic-journey'     => __( 'Our Strategic Journey 2026-2030: a five-year plan to establish, consolidate, scale, deepen and sustain our work, with eight connected objectives.', 'cohf-child' ),
		'gallery'               => __( 'Photos of our work in Kenya: sanitary pad donations in schools, children\'s home visits, street children back in school and women\'s small businesses.', 'cohf-child' ),
		'privacy-policy'        => __( 'How Cistern of Hope Foundation collects, uses and protects your personal information, and the choices you have.', 'cohf-child' ),
		'terms-of-use'          => __( 'The terms that apply when you use the Cistern of Hope Foundation website, Hope Market shop and online giving.', 'cohf-child' ),
		'donation-policy'       => __( 'How we receive, use and acknowledge donations, and how to ask for a refund if a gift was made in error.', 'cohf-child' ),
		'cart'                  => __( 'Your Hope Market cart. Review your handmade items, then check out securely or send your order on WhatsApp.', 'cohf-child' ),
		'checkout'              => __( 'Secure checkout for Hope Market. Pay by card through Paystack or arrange payment on delivery.', 'cohf-child' ),
		'my-account'            => __( 'Sign in to your Hope Market account to see your orders and saved details.', 'cohf-child' ),
	);
}
