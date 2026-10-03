<?php
/**
 * Rank Math integration (13.90.0).
 *
 * When Rank Math is active the theme's own SEO output switches off, so the
 * keyword work is fed into Rank Math instead of being lost:
 * - Keyword titles and meta descriptions are used wherever no custom Rank
 *   Math title/description has been typed for that page (yours always win).
 * - Rank Math's schema is enriched with the Foundation's NGO details,
 *   programme services, story keywords, shop product lists and the product
 *   return policy, without adding duplicate entities.
 * - One-time safe defaults: Organization knowledge graph, site name, logo,
 *   Facebook profile, "|" separator and breadcrumbs (only fills settings that
 *   are empty or set to Person).
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'RANK_MATH_VERSION' ) ) {
	return;
}

/** True when the editor typed a custom Rank Math value for this object. */
function cohf_rm_has_custom( $key ) {
	if ( is_singular() || ( function_exists( 'is_shop' ) && is_shop() ) ) {
		$id = ( function_exists( 'is_shop' ) && is_shop() ) ? (int) wc_get_page_id( 'shop' ) : get_queried_object_id();
		return '' !== trim( (string) get_post_meta( $id, $key, true ) );
	}
	if ( is_category() || is_tag() || is_tax() ) {
		return '' !== trim( (string) get_term_meta( get_queried_object_id(), $key, true ) );
	}
	return false;
}

/** Keyword title for the current page, or '' to keep Rank Math's. */
function cohf_rm_keyword_title() {
	if ( is_front_page() ) {
		return 'Cistern of Hope Foundation: NGO in Nairobi, Kenya';
	}
	$title = '';
	$brand = true;
	if ( function_exists( 'is_product' ) && is_product() ) {
		$p = wc_get_product( get_the_ID() );
		if ( $p ) {
			$price = html_entity_decode( wp_strip_all_tags( wc_price( (float) wc_get_price_to_display( $p ) ) ) );
			return html_entity_decode( wp_strip_all_tags( get_the_title() ) ) . ', ' . $price . ' | Hope Market Kenya';
		}
	} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
		$title = 'Hope Market: Kenya Handmade Crafts Online';
	} elseif ( is_post_type_archive( 'cohf_story' ) ) {
		$title = 'Impact Stories from Our Work in Kenya';
	} elseif ( function_exists( 'cohf_kw_current' ) ) {
		$kw = cohf_kw_current();
		if ( $kw && ! empty( $kw['title'] ) ) {
			$title = $kw['title'];
		}
	}
	if ( '' === $title ) {
		return '';
	}
	if ( is_paged() ) {
		$title .= ' | Page ' . max( 1, (int) get_query_var( 'paged' ) );
	}
	$len = mb_strlen( $title );
	if ( $brand && $len + 31 <= 62 ) {
		return $title . ' | Cistern of Hope Foundation';
	}
	if ( $brand && $len + 18 <= 64 ) {
		return $title . ' | Cistern of Hope';
	}
	return $title;
}

add_filter( 'rank_math/frontend/title', function ( $title ) {
	if ( cohf_rm_has_custom( 'rank_math_title' ) ) {
		return $title;
	}
	$kw = cohf_rm_keyword_title();
	return '' !== $kw ? $kw : $title;
}, 20 );

add_filter( 'rank_math/frontend/description', function ( $desc ) {
	if ( cohf_rm_has_custom( 'rank_math_description' ) || ! function_exists( 'cohf_seo_description' ) ) {
		return $desc;
	}
	$ours = trim( (string) cohf_seo_description() );
	return '' !== $ours ? $ours : $desc;
}, 20 );

/* Open Graph and Twitter follow the same keyword title/description. */
add_filter( 'rank_math/opengraph/facebook/og_title', function ( $t ) {
	$kw = cohf_rm_has_custom( 'rank_math_facebook_title' ) || cohf_rm_has_custom( 'rank_math_title' ) ? '' : cohf_rm_keyword_title();
	return '' !== $kw ? $kw : $t;
}, 20 );
add_filter( 'rank_math/opengraph/facebook/og_description', function ( $d ) {
	if ( cohf_rm_has_custom( 'rank_math_facebook_description' ) || cohf_rm_has_custom( 'rank_math_description' ) || ! function_exists( 'cohf_seo_description' ) ) {
		return $d;
	}
	$ours = trim( (string) cohf_seo_description() );
	return '' !== $ours ? $ours : $d;
}, 20 );

/* -------------------------------------------------------------------------
   Schema.
   ------------------------------------------------------------------------- */

/** Add return policy and origin to a Product entity. */
function cohf_rm_enrich_product( $node ) {
	$policy = array(
		'@type'                => 'MerchantReturnPolicy',
		'applicableCountry'    => 'KE',
		'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
		'merchantReturnDays'   => 7,
		'returnMethod'         => 'https://schema.org/ReturnByMail',
		'returnFees'           => 'https://schema.org/ReturnFeesCustomerResponsibility',
		'merchantReturnLink'   => home_url( '/refund-returns/' ),
	);
	$ship = array(
		'@type'               => 'OfferShippingDetails',
		'shippingRate'        => array( '@type' => 'MonetaryAmount', 'value' => 300, 'currency' => 'KES' ),
		'shippingDestination' => array( '@type' => 'DefinedRegion', 'addressCountry' => 'KE' ),
		'deliveryTime'        => array(
			'@type'        => 'ShippingDeliveryTime',
			'handlingTime' => array( '@type' => 'QuantitativeValue', 'minValue' => 0, 'maxValue' => 2, 'unitCode' => 'DAY' ),
			'transitTime'  => array( '@type' => 'QuantitativeValue', 'minValue' => 1, 'maxValue' => 3, 'unitCode' => 'DAY' ),
		),
	);
	if ( isset( $node['offers'] ) && is_array( $node['offers'] ) ) {
		$single = isset( $node['offers']['@type'] );
		$offers = $single ? array( $node['offers'] ) : $node['offers'];
		foreach ( $offers as &$o ) {
			if ( is_array( $o ) ) {
				$o['hasMerchantReturnPolicy'] = $policy;
				if ( empty( $o['shippingDetails'] ) ) {
					$o['shippingDetails'] = $ship;
				}
				if ( empty( $o['itemCondition'] ) ) {
					$o['itemCondition'] = 'https://schema.org/NewCondition';
				}
			}
		}
		unset( $o );
		$node['offers'] = $single ? $offers[0] : $offers;
	}
	if ( empty( $node['brand'] ) ) {
		$node['brand'] = array( '@type' => 'Brand', 'name' => 'Hope Market' );
	}
	$node['countryOfOrigin'] = array( '@type' => 'Country', 'name' => 'Kenya' );
	return $node;
}

add_filter( 'rank_math/json_ld', function ( $data, $jsonld = null ) {
	if ( ! is_array( $data ) ) {
		return $data;
	}
	$ngo = function_exists( 'cohf_schema_org_node' ) ? cohf_schema_org_node() : array();
	$kw  = function_exists( 'cohf_kw_current' ) ? cohf_kw_current() : null;

	foreach ( $data as $key => $node ) {
		if ( ! is_array( $node ) || empty( $node['@type'] ) ) {
			continue;
		}
		$types = (array) $node['@type'];

		// The organisation: keep Rank Math's @id (other nodes point to it), add the NGO details.
		if ( 'publisher' === $key || array_intersect( $types, array( 'Organization', 'NGO', 'NonprofitOrganization' ) ) ) {
			if ( $ngo ) {
				$id   = isset( $node['@id'] ) ? $node['@id'] : $ngo['@id'];
				$logo = isset( $node['logo'] ) ? $node['logo'] : ( isset( $ngo['logo'] ) ? $ngo['logo'] : null );
				$node = array_merge( $node, $ngo );
				$node['@id'] = $id;
				if ( $logo ) {
					$node['logo'] = $logo;
				}
			}
			$node['@type']         = array( 'NGO', 'NonprofitOrganization' );
			$node['alternateName'] = array( 'Cistern of Hope', 'COHF', 'Cistern of Hope Foundation Kenya' );
			$node['knowsAbout']    = function_exists( 'cohf_schema_topics' ) ? cohf_schema_topics() : array();
			$catalog = array();
			foreach ( get_posts( array( 'post_type' => 'cohf_programme', 'post_status' => 'publish', 'numberposts' => 50, 'orderby' => 'menu_order title', 'order' => 'ASC' ) ) as $prog ) {
				$catalog[] = array( '@type' => 'Offer', 'price' => 0, 'priceCurrency' => 'KES', 'itemOffered' => array( '@type' => 'Service', 'name' => wp_strip_all_tags( get_the_title( $prog ) ), 'url' => get_permalink( $prog ) ) );
			}
			if ( $catalog ) {
				$node['hasOfferCatalog'] = array( '@type' => 'OfferCatalog', 'name' => 'Cistern of Hope Foundation programmes', 'itemListElement' => $catalog );
			}
			$node['seeks'] = array( '@type' => 'Demand', 'name' => 'Donations, volunteers and partners to support vulnerable children, widows, women and youth in Kenya' );
			$node['keywords']      = 'NGO in Kenya, NGO in Nairobi, charity in Kenya, donate to charity Kenya, donate via M-Pesa, sponsor a child in Kenya, school fees support Kenya, sanitary pads for girls Kenya, help widows in Kenya, women empowerment Kenya, youth empowerment Kenya, street children Kenya, orphans in Kenya, volunteer in Nairobi';
			$data[ $key ] = $node;
			continue;
		}
		if ( in_array( 'WebSite', $types, true ) ) {
			$node['alternateName'] = array( 'Cistern of Hope', 'Hope Market' );
			$node['inLanguage']    = 'en-KE';
			$data[ $key ]          = $node;
			continue;
		}
		if ( in_array( 'Product', $types, true ) ) {
			$data[ $key ] = cohf_rm_enrich_product( $node );
			continue;
		}
		if ( array_intersect( $types, array( 'Article', 'BlogPosting', 'NewsArticle' ) ) && is_singular( array( 'cohf_story', 'cohf_news' ) ) ) {
			$node['keywords']        = 'charity in Kenya, NGO in Nairobi, impact story, ' . wp_strip_all_tags( get_the_title() );
			$node['locationCreated'] = array( '@type' => 'Place', 'name' => 'Nairobi, Kenya' );
			$node['inLanguage']      = 'en-KE';
			$data[ $key ]            = $node;
			continue;
		}
		if ( in_array( 'WebPage', $types, true ) || array_intersect( $types, array( 'AboutPage', 'ContactPage', 'CollectionPage', 'ItemPage' ) ) ) {
			if ( $kw && ! empty( $kw['kw'] ) ) {
				$node['keywords'] = $kw['kw'];
			}
			$node['inLanguage'] = 'en-KE';
			$data[ $key ]       = $node;
		}
	}

	$org_ref = array( '@id' => isset( $data['publisher']['@id'] ) ? $data['publisher']['@id'] : home_url( '/#organization' ) );

	// Programme pages: the programme as a free service of the NGO.
	if ( is_singular( 'cohf_programme' ) && empty( $data['cohfProgramme'] ) ) {
		$service = array(
			'@type'               => 'Service',
			'@id'                 => get_permalink() . '#programme',
			'name'                => wp_strip_all_tags( get_the_title() ),
			'serviceType'         => $kw && ! empty( $kw['title'] ) ? $kw['title'] : wp_strip_all_tags( get_the_title() ),
			'description'         => function_exists( 'cohf_seo_description' ) ? cohf_seo_description() : '',
			'url'                 => get_permalink(),
			'provider'            => $org_ref,
			'category'            => 'Charity programme',
			'isAccessibleForFree' => true,
			'areaServed'          => array(
				array( '@type' => 'City', 'name' => 'Nairobi' ),
				array( '@type' => 'AdministrativeArea', 'name' => 'Kiambu County' ),
				array( '@type' => 'Country', 'name' => 'Kenya' ),
			),
			'audience'            => array( '@type' => 'Audience', 'audienceType' => 'Vulnerable children, youth, women, widows and households in Kenya' ),
		);
		if ( $kw && ! empty( $kw['kw'] ) ) {
			$service['keywords'] = $kw['kw'];
		}
		if ( has_post_thumbnail() ) {
			$service['image'] = get_the_post_thumbnail_url( null, 'full' );
		}
		$data['cohfProgramme'] = $service;
	}

	// Donate page: a DonateAction that search engines can show.
	if ( is_page( 'support-our-work' ) && empty( $data['cohfDonate'] ) ) {
		$data['cohfDonate'] = array(
			'@type'     => 'DonateAction',
			'@id'       => home_url( '/support-our-work/#donate' ),
			'name'      => 'Donate to Cistern of Hope Foundation',
			'recipient' => $org_ref,
			'target'    => array( '@type' => 'EntryPoint', 'urlTemplate' => home_url( '/support-our-work/' ), 'actionPlatform' => array( 'https://schema.org/DesktopWebPlatform', 'https://schema.org/MobileWebPlatform' ) ),
		);
	}

	// Shop and category pages: list the products shown.
	$is_listing = ( function_exists( 'is_shop' ) && is_shop() ) || ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() );
	if ( $is_listing && empty( $data['cohfProducts'] ) ) {
		global $wp_query;
		$items = array();
		$pos   = 1;
		foreach ( (array) $wp_query->posts as $post ) {
			$items[] = array( '@type' => 'ListItem', 'position' => $pos++, 'url' => get_permalink( $post ), 'name' => wp_strip_all_tags( get_the_title( $post ) ) );
		}
		if ( $items ) {
			$data['cohfProducts'] = array( '@type' => 'ItemList', 'name' => 'Hope Market products', 'numberOfItems' => count( $items ), 'itemListElement' => $items );
		}
	}
	return $data;
}, 99, 2 );

/* -------------------------------------------------------------------------
   One-time safe defaults for Rank Math settings.
   ------------------------------------------------------------------------- */
add_action( 'admin_init', function () {
	if ( get_option( 'cohf_rm_defaults_1' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$titles = get_option( 'rank-math-options-titles', array() );
	if ( is_array( $titles ) ) {
		if ( empty( $titles['knowledgegraph_type'] ) || 'person' === $titles['knowledgegraph_type'] ) {
			$titles['knowledgegraph_type'] = 'company';
		}
		$fill = array(
			'knowledgegraph_name'    => 'Cistern of Hope Foundation',
			'website_name'           => 'Cistern of Hope Foundation',
			'website_alternate_name' => 'Cistern of Hope',
			'social_url_facebook'    => 'https://www.facebook.com/people/Cistern-of-Hope-Foundation/61571155324672/',
			'title_separator'        => '|',
		);
		foreach ( $fill as $k => $v ) {
			if ( empty( $titles[ $k ] ) || ( 'title_separator' === $k && '-' === $titles[ $k ] ) ) {
				$titles[ $k ] = $v;
			}
		}
		if ( empty( $titles['knowledgegraph_logo'] ) && function_exists( 'cohf_schema_image' ) ) {
			$titles['knowledgegraph_logo'] = cohf_schema_image();
		}
		update_option( 'rank-math-options-titles', $titles );
	}
	$general = get_option( 'rank-math-options-general', array() );
	if ( is_array( $general ) && empty( $general['breadcrumbs'] ) ) {
		$general['breadcrumbs'] = 'on';
		update_option( 'rank-math-options-general', $general );
	}
	update_option( 'cohf_rm_defaults_1', 1, false );
} );
