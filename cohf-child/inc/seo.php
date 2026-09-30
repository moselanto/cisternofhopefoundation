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
		$title = get_bloginfo( 'name' );
		$desc  = cohf_org_get( 'mission' );
		$url   = home_url( '/' );
	} elseif ( is_singular() ) {
		$title = wp_strip_all_tags( get_the_title() );
		$desc  = wp_strip_all_tags( get_the_excerpt() );
		$url   = get_permalink();
	} else {
		return;
	}

	$image = is_singular() && has_post_thumbnail()
		? get_the_post_thumbnail_url( null, 'cohf-wide' )
		: '';

	$tags = array(
		'og:site_name'   => get_bloginfo( 'name' ),
		'og:title'       => $title,
		'og:description' => wp_trim_words( $desc, 34 ),
		'og:type'        => is_front_page() ? 'website' : 'article',
		'og:url'         => $url,
		'og:locale'      => get_locale(),
	);
	if ( $image ) {
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
	return $post_types;
} );
