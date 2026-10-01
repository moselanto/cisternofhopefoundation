<?php
/**
 * Structured data (schema.org JSON-LD) and search-result polish (13.0.0).
 *
 * One connected @graph on every page, the format Google recommends:
 *   NGO (with @id)  <-  WebSite (+ site search)  <-  WebPage (typed)
 *   + BreadcrumbList, and per page type: Person (leaders), Service
 *   (programmes), Article (stories), FAQPage (Partners), ImageGallery
 *   (Gallery), DonateAction (Support Our Work).
 * Products keep WooCommerce's Product schema, extended with brand, seller
 * and Kenyan shipping details.
 * Also: keyword-led page titles, a default share image, and noindex on
 * shop filter / sort URLs so Google indexes one clean copy of each page.
 * Everything steps aside when an SEO plugin (Yoast, Rank Math...) is active.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/** Stable node IDs. */
function cohf_schema_id( $part ) {
	return home_url( '/#' . $part );
}

/**
 * Programme areas the Foundation works in (used as knowsAbout).
 *
 * @return string[]
 */
function cohf_schema_topics() {
	return array( 'Poverty eradication', 'Education and scholarships', 'Youth skills and employability', 'Women\'s economic empowerment', 'Menstrual health and hygiene', 'Humanitarian assistance', 'Counselling and mentorship', 'Health and nutrition', 'Sustainable livelihoods and agriculture', 'Environment and climate', 'Water, sanitation and hygiene', 'Digital inclusion', 'Community development' );
}

/** Default share/brand image. */
function cohf_schema_image() {
	$logo = get_theme_mod( 'custom_logo' );
	if ( $logo ) {
		$src = wp_get_attachment_image_src( $logo, 'full' );
		if ( $src ) {
			return $src[0];
		}
	}
	return COHF_CHILD_URI . '/assets/images/hero-home.jpg';
}

/** The NGO node. */
function cohf_schema_org_node() {
	$org  = cohf_org();
	$node = array(
		'@type'           => array( 'NGO', 'NonprofitOrganization' ),
		'@id'             => cohf_schema_id( 'organization' ),
		'name'            => $org['name'],
		'alternateName'   => $org['abbr'],
		'url'             => home_url( '/' ),
		'logo'            => array( '@type' => 'ImageObject', '@id' => cohf_schema_id( 'logo' ), 'url' => cohf_schema_image() ),
		'image'           => COHF_CHILD_URI . '/assets/images/hero-home.jpg',
		'slogan'          => $org['motto'],
		'description'     => $org['descriptor'] . ' ' . $org['mission'],
		'foundingDate'    => $org['founded'],
		'foundingLocation' => array( '@type' => 'Place', 'name' => $org['origin'] . ', Kenya' ),
		'email'           => $org['email'],
		'telephone'       => $org['phone'],
		'address'         => array(
			array(
				'@type'           => 'PostalAddress',
				'name'            => 'Office',
				'streetAddress'   => 'Behind N Market (Deliverance Church Kabete N Market)',
				'addressLocality' => 'Kabete',
				'addressRegion'   => 'Kiambu',
				'addressCountry'  => 'KE',
			),
			array(
				'@type'               => 'PostalAddress',
				'name'                => 'Postal address',
				'postOfficeBoxNumber' => '23524',
				'postalCode'          => '00625',
				'addressLocality'     => 'Nairobi',
				'addressCountry'      => 'KE',
			),
		),
		'areaServed'      => array( '@type' => 'Country', 'name' => 'Kenya' ),
		'knowsAbout'      => cohf_schema_topics(),
		'nonprofitStatus' => 'NonprofitType',
		'contactPoint'    => array(
			array( '@type' => 'ContactPoint', 'contactType' => 'customer support', 'telephone' => $org['phone'], 'email' => $org['email'], 'areaServed' => 'KE', 'availableLanguage' => array( 'English', 'Swahili' ) ),
			array( '@type' => 'ContactPoint', 'contactType' => 'donations', 'url' => home_url( '/support-our-work/' ), 'email' => $org['email'] ),
		),
	);
	$map_url             = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( 'Deliverance Church Kabete N Market' );
	$node['hasMap']      = $map_url;
	$node['location']    = array(
		'@type'   => 'Place',
		'name'    => $org['name'] . ' office, Kabete',
		'hasMap'  => $map_url,
		'address' => $node['address'][0],
	);
	$node['areaServed']  = array(
		array( '@type' => 'Country', 'name' => 'Kenya' ),
		array( '@type' => 'City', 'name' => 'Nairobi' ),
		array( '@type' => 'AdministrativeArea', 'name' => 'Kiambu County' ),
	);
	$node['founder']     = array( '@type' => 'Person', 'name' => 'Justus Kubai', 'jobTitle' => 'Founder and Executive Director' );
	$node['keywords']    = 'NGO in Kenya, NGO in Nairobi, charity in Kenya, donate to children in Kenya, sanitary pads for girls Kenya, school fees support Kenya, women empowerment Kenya, youth empowerment Kenya, street children Kenya';
	$acct                = cohf_page_url( 'page-templates/page-accountability.php' );
	if ( $acct ) {
		$node['ethicsPolicy']             = $acct;
		$node['actionableFeedbackPolicy'] = $acct . '#complaints';
	}
	$node['potentialAction'] = array(
		'@type'  => 'DonateAction',
		'name'   => 'Donate to Cistern of Hope Foundation',
		'target' => home_url( '/support-our-work/' ),
	);
	$same = function_exists( 'cohf_social_links' ) ? array_values( wp_list_pluck( cohf_social_links(), 'url' ) ) : array();
	if ( empty( $same ) ) {
		$same = array( 'https://www.facebook.com/people/Cistern-of-Hope-Foundation/61571155324672/' );
	}
	$node['sameAs'] = $same;
	return $node;
}

/** Breadcrumb trail as name/url pairs. */
function cohf_schema_trail() {
	$trail = array( array( __( 'Home', 'cohf-child' ), home_url( '/' ) ) );
	if ( is_front_page() ) {
		return $trail;
	}
	$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
	if ( function_exists( 'is_product' ) && is_product() ) {
		$trail[] = array( __( 'Hope Market', 'cohf-child' ), $shop );
		$terms   = get_the_terms( get_the_ID(), 'product_cat' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$trail[] = array( $terms[0]->name, get_term_link( $terms[0] ) );
		}
		$trail[] = array( wp_strip_all_tags( get_the_title() ), get_permalink() );
	} elseif ( function_exists( 'is_product_category' ) && is_product_category() ) {
		$trail[] = array( __( 'Hope Market', 'cohf-child' ), $shop );
		$trail[] = array( single_term_title( '', false ), get_term_link( get_queried_object() ) );
	} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
		$trail[] = array( __( 'Hope Market', 'cohf-child' ), $shop );
	} elseif ( is_singular( 'cohf_programme' ) ) {
		$trail[] = array( __( 'Our Programmes', 'cohf-child' ), home_url( '/programmes-overview/' ) );
		$trail[] = array( wp_strip_all_tags( get_the_title() ), get_permalink() );
	} elseif ( is_singular( 'cohf_story' ) ) {
		$trail[] = array( __( 'Our Impact', 'cohf-child' ), home_url( '/impact/' ) );
		$trail[] = array( wp_strip_all_tags( get_the_title() ), get_permalink() );
	} elseif ( is_singular( 'cohf_leader' ) ) {
		$trail[] = array( __( 'Leadership & Governance', 'cohf-child' ), home_url( '/leadership-governance/' ) );
		$trail[] = array( wp_strip_all_tags( get_the_title() ), get_permalink() );
	} elseif ( is_singular() ) {
		$trail[] = array( wp_strip_all_tags( get_the_title() ), get_permalink() );
	} elseif ( is_post_type_archive() ) {
		$trail[] = array( post_type_archive_title( '', false ), get_post_type_archive_link( get_query_var( 'post_type' ) ) );
	}
	return $trail;
}

/** Current canonical URL. */
function cohf_schema_url() {
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	if ( is_singular() ) {
		return get_permalink();
	}
	if ( is_tax() || is_category() ) {
		$l = get_term_link( get_queried_object() );
		return is_wp_error( $l ) ? home_url( add_query_arg( array() ) ) : $l;
	}
	if ( function_exists( 'is_shop' ) && is_shop() ) {
		return wc_get_page_permalink( 'shop' );
	}
	if ( is_post_type_archive() ) {
		return get_post_type_archive_link( get_query_var( 'post_type' ) );
	}
	return home_url( add_query_arg( array() ) );
}

/** WebPage subtype for the current page. */
function cohf_schema_page_type() {
	$slug = is_page() ? get_post_field( 'post_name', get_queried_object_id() ) : '';
	$map  = array( 'about' => 'AboutPage', 'contact' => 'ContactPage', 'gallery' => 'CollectionPage', 'programmes-overview' => 'CollectionPage', 'resources-overview' => 'CollectionPage', 'partners-overview' => 'FAQPage' );
	if ( isset( $map[ $slug ] ) ) {
		return $map[ $slug ];
	}
	if ( is_singular( 'cohf_leader' ) ) {
		return 'ProfilePage';
	}
	if ( ( function_exists( 'is_product' ) && is_product() ) || is_singular( 'cohf_programme' ) ) {
		return 'ItemPage';
	}
	if ( is_archive() || ( function_exists( 'is_shop' ) && is_shop() ) ) {
		return 'CollectionPage';
	}
	return 'WebPage';
}

/** Partner FAQ (same answers as on the Partners page). */
function cohf_schema_partner_faq() {
	return array(
		__( 'Who is Cistern of Hope Foundation?', 'cohf-child' ) => __( 'Cistern of Hope Foundation (COHF) is a Kenyan organisation focused on poverty eradication through community empowerment.', 'cohf-child' ),
		__( 'What is the Foundation\'s mission?', 'cohf-child' ) => cohf_org_get( 'mission' ),
		__( 'Who does the Foundation serve?', 'cohf-child' )     => __( 'Vulnerable children, adolescents, youth, women, people with disabilities, households and communities in Kenya.', 'cohf-child' ),
		__( 'What are the Foundation\'s programme areas?', 'cohf-child' ) => __( 'Education, youth empowerment, women\'s economic empowerment, health and wellbeing, livelihoods, agriculture and food security, environment, WASH, digital inclusion and community development.', 'cohf-child' ),
		__( 'What does the Foundation seek from partners?', 'cohf-child' ) => __( 'Funding, technical expertise, training, equipment, market linkages, mentorship, research and evaluation, co-funding and institutional strengthening.', 'cohf-child' ),
		__( 'How can I support Cistern of Hope Foundation?', 'cohf-child' ) => __( 'You can give securely online by card or M-Pesa, partner with the Foundation, volunteer your skills, or buy handmade crafts from Hope Market.', 'cohf-child' ),
	);
}

/** Print the graph. */
function cohf_schema_graph() {
	if ( cohf_seo_plugin_active() || is_404() || is_search() ) {
		return;
	}
	$url   = cohf_schema_url();
	$graph = array( cohf_schema_org_node() );

	$graph[] = array(
		'@type'           => 'WebSite',
		'@id'             => cohf_schema_id( 'website' ),
		'url'             => home_url( '/' ),
		'name'            => cohf_org_get( 'name' ),
		'alternateName'   => cohf_org_get( 'abbr' ),
		'inLanguage'      => 'en-KE',
		'publisher'       => array( '@id' => cohf_schema_id( 'organization' ) ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => array( '@type' => 'EntryPoint', 'urlTemplate' => home_url( '/?s={search_term_string}' ) ),
			'query-input' => 'required name=search_term_string',
		),
	);

	$page = array(
		'@type'       => cohf_schema_page_type(),
		'@id'         => $url . '#webpage',
		'url'         => $url,
		'name'        => wp_get_document_title(),
		'description' => function_exists( 'cohf_seo_description' ) ? cohf_seo_description() : '',
		'isPartOf'    => array( '@id' => cohf_schema_id( 'website' ) ),
		'about'       => array( '@id' => cohf_schema_id( 'organization' ) ),
		'inLanguage'  => 'en-KE',
	);
	if ( is_singular() && has_post_thumbnail() ) {
		$page['primaryImageOfPage'] = array( '@type' => 'ImageObject', 'url' => get_the_post_thumbnail_url( null, 'full' ) );
	}
	if ( is_singular() ) {
		$page['datePublished'] = get_the_date( 'c' );
		$page['dateModified']  = get_the_modified_date( 'c' );
	}

	$trail = cohf_schema_trail();
	if ( count( $trail ) > 1 ) {
		$items = array();
		foreach ( $trail as $i => $crumb ) {
			$items[] = array( '@type' => 'ListItem', 'position' => $i + 1, 'name' => $crumb[0], 'item' => is_wp_error( $crumb[1] ) ? $url : $crumb[1] );
		}
		$graph[]            = array( '@type' => 'BreadcrumbList', '@id' => $url . '#breadcrumb', 'itemListElement' => $items );
		$page['breadcrumb'] = array( '@id' => $url . '#breadcrumb' );
	}

	// FAQ on Partners.
	if ( 'FAQPage' === $page['@type'] ) {
		$qa = array();
		foreach ( cohf_schema_partner_faq() as $q => $a ) {
			$qa[] = array( '@type' => 'Question', 'name' => $q, 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $a ) );
		}
		$page['mainEntity'] = $qa;
	}

	// Donation action on Support Our Work.
	if ( is_page( 'support-our-work' ) ) {
		$page['potentialAction'] = array(
			'@type'     => 'DonateAction',
			'name'      => __( 'Donate to Cistern of Hope Foundation', 'cohf-child' ),
			'recipient' => array( '@id' => cohf_schema_id( 'organization' ) ),
			'target'    => home_url( '/support-our-work/#give' ),
		);
	}

	// Leader profile.
	if ( is_singular( 'cohf_leader' ) ) {
		$person = array(
			'@type'    => 'Person',
			'@id'      => get_permalink() . '#person',
			'name'     => wp_strip_all_tags( get_the_title() ),
			'url'      => get_permalink(),
			'worksFor' => array( '@id' => cohf_schema_id( 'organization' ) ),
		);
		$role = (string) get_post_meta( get_the_ID(), '_cohf_role', true );
		if ( $role ) {
			$person['jobTitle'] = $role;
		}
		if ( has_post_thumbnail() ) {
			$person['image'] = get_the_post_thumbnail_url( null, 'cohf-portrait' );
		}
		$graph[]            = $person;
		$page['mainEntity'] = array( '@id' => get_permalink() . '#person' );
	}

	// Programme as a service the NGO provides.
	if ( is_singular( 'cohf_programme' ) ) {
		$desc    = function_exists( 'cohf_seo_description' ) ? cohf_seo_description() : '';
		$graph[] = array(
			'@type'       => 'Service',
			'@id'         => get_permalink() . '#programme',
			'name'        => wp_strip_all_tags( get_the_title() ),
			'serviceType' => wp_strip_all_tags( get_the_title() ),
			'description' => $desc,
			'url'         => get_permalink(),
			'provider'    => array( '@id' => cohf_schema_id( 'organization' ) ),
			'areaServed'  => array( '@type' => 'Country', 'name' => 'Kenya' ),
			'audience'    => array( '@type' => 'Audience', 'audienceType' => __( 'Vulnerable children, youth, women and households in Kenya', 'cohf-child' ) ),
		);
		$page['mainEntity'] = array( '@id' => get_permalink() . '#programme' );
	}

	// Impact stories and news as articles.
	if ( is_singular( array( 'cohf_story', 'cohf_news' ) ) ) {
		$article = array(
			'@type'            => 'Article',
			'@id'              => get_permalink() . '#article',
			'headline'         => wp_strip_all_tags( get_the_title() ),
			'description'      => function_exists( 'cohf_seo_description' ) ? cohf_seo_description() : '',
			'datePublished'    => get_the_date( 'c' ),
			'dateModified'     => get_the_modified_date( 'c' ),
			'mainEntityOfPage' => array( '@id' => $url . '#webpage' ),
			'author'           => array( '@id' => cohf_schema_id( 'organization' ) ),
			'publisher'        => array( '@id' => cohf_schema_id( 'organization' ) ),
			'inLanguage'       => 'en-KE',
			'articleSection'   => __( 'Impact stories', 'cohf-child' ),
		);
		$article['image'] = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'cohf-wide' ) : cohf_schema_image();
		$graph[]          = $article;
	}

	// Gallery.
	if ( is_page( 'gallery' ) && function_exists( 'cohf_gallery_items' ) ) {
		$imgs = array();
		foreach ( array_slice( cohf_gallery_items(), 0, 30 ) as $it ) {
			if ( empty( $it['url'] ) ) { continue; }
			$imgs[] = array( '@type' => 'ImageObject', 'contentUrl' => $it['url'], 'name' => isset( $it['title'] ) ? $it['title'] : '', 'caption' => isset( $it['caption'] ) ? $it['caption'] : '' );
		}
		$graph[] = array( '@type' => 'ImageGallery', '@id' => $url . '#gallery', 'name' => __( 'Cistern of Hope Foundation photo gallery', 'cohf-child' ), 'image' => $imgs, 'author' => array( '@id' => cohf_schema_id( 'organization' ) ) );
	}

	// Programmes overview: list of the 12 programmes.
	if ( is_page( 'programmes-overview' ) ) {
		$progs = get_posts( array( 'post_type' => 'cohf_programme', 'numberposts' => 20, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
		if ( $progs ) {
			$list = array();
			foreach ( $progs as $i => $pp ) {
				$list[] = array( '@type' => 'ListItem', 'position' => $i + 1, 'url' => get_permalink( $pp ), 'name' => get_the_title( $pp ) );
			}
			$page['mainEntity'] = array( '@type' => 'ItemList', 'itemListElement' => $list );
		}
	}

	$graph[] = $page;
	echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'cohf_schema_graph', 20 );

/* The old single-node schema is replaced by the graph above. */
add_action( 'init', function () {
	remove_action( 'wp_head', 'cohf_organization_schema', 20 );
	remove_action( 'wp_head', 'cohf_article_schema', 21 );
} );

/**
 * Products: extend WooCommerce's Product schema.
 *
 * @param array      $markup  Product markup.
 * @param WC_Product $product Product.
 * @return array
 */
function cohf_schema_product( $markup, $product ) {
	$markup['brand']    = array( '@type' => 'Brand', 'name' => 'Hope Market' );
	$markup['category'] = wp_strip_all_tags( wc_get_product_category_list( $product->get_id(), ', ' ) );
	if ( ! empty( $markup['offers'] ) && is_array( $markup['offers'] ) ) {
		foreach ( $markup['offers'] as &$offer ) {
			$offer['seller']        = array( '@id' => cohf_schema_id( 'organization' ) );
			$offer['itemCondition'] = 'https://schema.org/NewCondition';
			$offer['shippingDetails'] = array(
				'@type'               => 'OfferShippingDetails',
				'shippingRate'        => array( '@type' => 'MonetaryAmount', 'value' => 300, 'currency' => 'KES' ),
				'shippingDestination' => array( '@type' => 'DefinedRegion', 'addressCountry' => 'KE' ),
				'deliveryTime'        => array(
					'@type'        => 'ShippingDeliveryTime',
					'handlingTime' => array( '@type' => 'QuantitativeValue', 'minValue' => 0, 'maxValue' => 2, 'unitCode' => 'DAY' ),
					'transitTime'  => array( '@type' => 'QuantitativeValue', 'minValue' => 1, 'maxValue' => 3, 'unitCode' => 'DAY' ),
				),
			);
		}
		unset( $offer );
	}
	return $markup;
}
add_filter( 'woocommerce_structured_data_product', 'cohf_schema_product', 20, 2 );

/* -------------------------------------------------------------------------
   Titles: say what the page is and where, the way people search.
   ------------------------------------------------------------------------- */
function cohf_seo_titles( $parts ) {
	if ( cohf_seo_plugin_active() ) {
		return $parts;
	}
	$name = 'Cistern of Hope Foundation';
	if ( is_front_page() ) {
		return array( 'title' => $name . ' | NGO in Nairobi, Kenya for Children, Women and Youth' );
	}
	$map = array(
		'about'                 => 'About Us: Our Story, Vision and Mission',
		'programmes-overview'   => 'Our Programmes in Kenya: Education, Sanitary Pads, Youth and Women',
		'impact'                => 'Our Impact in Kenya: Children, Women and Communities',
		'approach'              => 'Our Approach: From Support to Self-Reliance',
		'get-involved'          => 'Volunteer, Partner or Donate in Nairobi, Kenya',
		'partners-overview'     => 'Partner With an NGO in Kenya',
		'resources-overview'    => 'Reports, Policies and Resources',
		'contact'               => 'Contact Us: NGO Office in Kabete, Nairobi',
		'leadership-governance' => 'Leadership and Governance',
		'accountability'        => 'Accountability and Safeguarding',
		'support-our-work'      => 'Donate to a Kenyan Charity by M-Pesa or Card',
		'strategic-journey'     => 'Strategic Plan 2026-2030',
		'gallery'               => 'Photo Gallery: Our Work in Kenya',
	);
	if ( is_page() ) {
		$slug = get_post_field( 'post_name', get_queried_object_id() );
		if ( isset( $map[ $slug ] ) ) {
			return array( 'title' => $map[ $slug ], 'site' => $name );
		}
	}
	if ( function_exists( 'is_shop' ) && is_shop() ) {
		return array( 'title' => 'Hope Market: Handmade African Crafts from Kenya', 'site' => $name );
	}
	if ( function_exists( 'is_product_category' ) && is_product_category() ) {
		return array( 'title' => 'Handmade ' . single_term_title( '', false ) . ' from Kenya | Hope Market', 'site' => $name );
	}
	if ( function_exists( 'is_product' ) && is_product() ) {
		$p = wc_get_product( get_the_ID() );
		if ( $p ) {
			$price = html_entity_decode( wp_strip_all_tags( wc_price( (float) wc_get_price_to_display( $p ) ) ) );
			return array( 'title' => get_the_title() . ' - Handmade in Kenya, ' . $price . ' | Hope Market', 'site' => $name );
		}
	}
	if ( is_singular( 'cohf_programme' ) ) {
		return array( 'title' => get_the_title() . ' Programme in Kenya', 'site' => $name );
	}
	if ( is_singular( 'cohf_leader' ) ) {
		$role = (string) get_post_meta( get_the_ID(), '_cohf_role', true );
		return array( 'title' => get_the_title() . ( $role ? ', ' . $role : '' ), 'site' => $name );
	}
	return $parts;
}
add_filter( 'document_title_parts', 'cohf_seo_titles', 20 );
add_filter( 'document_title_separator', function () { return '|'; } );

/* -------------------------------------------------------------------------
   Default share image, and one clean indexed copy of shop pages.
   ------------------------------------------------------------------------- */
add_action( 'wp_head', function () {
	if ( cohf_seo_plugin_active() ) {
		return;
	}
	$has = is_singular() && has_post_thumbnail();
	if ( function_exists( 'is_product' ) && is_product() ) {
		$has = true; // WooCommerce product image is used.
		$img = get_the_post_thumbnail_url( null, 'full' );
		if ( $img ) {
			printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $img ) );
			echo '<meta property="og:type" content="product">' . "\n";
		}
		return;
	}
	if ( ! $has ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( COHF_CHILD_URI . '/assets/images/hero-home.jpg' ) );
		echo '<meta property="og:image:width" content="1376"><meta property="og:image:height" content="768">' . "\n";
	}
}, 6 );

add_filter( 'wp_robots', function ( $robots ) {
	$params = array( 'orderby', 'min_price', 'max_price', 'filter', 'rating_filter', 'add-to-cart', 'cohf-wa-order', 'rq' );
	foreach ( $params as $p ) {
		if ( isset( $_GET[ $p ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$robots['noindex'] = true;
			$robots['follow']  = true;
			unset( $robots['max-image-preview'] );
			break;
		}
	}
	return $robots;
}, 20 );

/** Canonical for shop, categories and archives (WordPress only adds it on single pages). */
add_action( 'wp_head', function () {
	if ( cohf_seo_plugin_active() || is_singular() || is_404() || is_search() ) {
		return;
	}
	$url = cohf_schema_url();
	$paged = (int) get_query_var( 'paged' );
	if ( $paged > 1 ) {
		$url = trailingslashit( $url ) . 'page/' . $paged . '/';
	}
	printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $url ) );
}, 2 );
