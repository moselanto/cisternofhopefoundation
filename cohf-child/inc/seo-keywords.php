<?php
/**
 * Keyword-focused SEO (13.88.0).
 *
 * Short, search-friendly titles (about 60 characters, so Google shows them in
 * full), keyword meta descriptions for every page, programme, category and
 * product, and richer structured data. Everything here steps aside when an
 * SEO plugin (Yoast, Rank Math, etc.) is active.
 *
 * @package cohf-child
 */

defined( 'ABSPATH' ) || exit;

/** Short brand used in titles. */
function cohf_kw_brand() {
	return 'Cistern of Hope';
}

/**
 * Programme keywords, by programme slug.
 * title: search title, desc: meta description, kw: target search phrases.
 */
function cohf_kw_programmes() {
	return array(
		'education-scholarship-child-development' => array(
			'title' => 'School Fees Support for Needy Children in Kenya',
			'desc'  => 'We help orphans and needy children in Nairobi stay in school with school fees, uniforms and learning materials. Sponsor a child\'s education in Kenya.',
			'kw'    => 'school fees support Kenya, sponsor a child in Kenya, education for orphans Kenya, scholarships for needy students Kenya, help street children go to school',
		),
		'menstrual-health-hygiene-dignity' => array(
			'title' => 'Sanitary Pads for Schoolgirls in Kenya',
			'desc'  => 'Around 200 girls receive sanitary pads from us every month, so no girl misses school because of her period. Help end period poverty in Kenya.',
			'kw'    => 'sanitary pads for girls Kenya, donate sanitary pads Kenya, period poverty Kenya, menstrual hygiene Kenya, keep girls in school',
		),
		'widows-care-food-support' => array(
			'title' => 'Support Widows in Kenya: Monthly Food Aid',
			'desc'  => 'We give widows in our Nairobi community monthly food support and care, so no widow is forgotten. Help us reach more widows in Kenya.',
			'kw'    => 'help widows in Kenya, food donation Kenya, support widows Nairobi, charity for widows Kenya, feed a family Kenya',
		),
		'youth-skills-enterprise-employability' => array(
			'title' => 'Youth Empowerment and Skills Training in Kenya',
			'desc'  => 'Skills training, mentorship and business start-up support that help young people in Nairobi find work or build their own business.',
			'kw'    => 'youth empowerment Kenya, skills training for youth Nairobi, youth employment Kenya, entrepreneurship for young people Kenya',
		),
		'womens-enterprise-economic-empowerment' => array(
			'title' => 'Women Empowerment and Small Business in Kenya',
			'desc'  => 'We help women in Nairobi start and grow small businesses with start-up support, training and mentorship, so families earn steady income.',
			'kw'    => 'women empowerment Kenya, small business support for women Kenya, women entrepreneurs Nairobi, economic empowerment Kenya',
		),
		'humanitarian-assistance-household-resilience' => array(
			'title' => 'Food Aid and Emergency Help for Families in Kenya',
			'desc'  => 'Food, essentials and emergency support for vulnerable households in Nairobi, with a path from crisis to recovery and self-reliance.',
			'kw'    => 'food aid Kenya, emergency relief Kenya, help needy families Nairobi, humanitarian assistance Kenya',
		),
		'counselling-mentorship-life-skills' => array(
			'title' => 'Counselling and Mentorship for Youth in Nairobi',
			'desc'  => 'Counselling, mentorship and life skills that build confidence, wellbeing and good decision-making in children and young people in Kenya.',
			'kw'    => 'free counselling Nairobi, youth mentorship Kenya, life skills for children Kenya, psychosocial support Kenya',
		),
		'health-nutrition-community-wellbeing' => array(
			'title' => 'Community Health and Nutrition in Kenya',
			'desc'  => 'Health awareness, nutrition and referrals that help children and families in our Nairobi community stay healthy.',
			'kw'    => 'community health Kenya, child nutrition Kenya, health outreach Nairobi',
		),
		'sustainable-livelihoods-agriculture-food-security' => array(
			'title' => 'Food Security and Farming Livelihoods in Kenya',
			'desc'  => 'Support that strengthens household food security, income and resilience for vulnerable families in Nairobi and across Kenya.',
			'kw'    => 'food security Kenya, sustainable livelihoods Kenya, small-scale farming support Kenya',
		),
		'environment-climate-conservation' => array(
			'title' => 'Environment and Climate Action in Kenya',
			'desc'  => 'Work that promotes environmental stewardship, climate resilience and green livelihoods in our Nairobi community and across Kenya.',
			'kw'    => 'environmental conservation Kenya, climate action Kenya, environmental conservation Nairobi',
		),
		'water-sanitation-hygiene' => array(
			'title' => 'Clean Water, Sanitation and Hygiene in Kenya',
			'desc'  => 'Hygiene education, sanitation and safe-water awareness (WASH) for schools and households in our Nairobi community.',
			'kw'    => 'WASH Kenya, clean water Kenya, sanitation and hygiene Kenya',
		),
		'digital-inclusion-innovation' => array(
			'title' => 'Digital Skills Training for Youth in Kenya',
			'desc'  => 'Computer skills, online safety and digital work opportunities for young people and women in Nairobi.',
			'kw'    => 'digital skills training Kenya, computer classes for youth Nairobi, digital inclusion Kenya',
		),
		'community-development-partnerships' => array(
			'title' => 'Community Development NGO in Nairobi',
			'desc'  => 'We work with volunteers, schools and partners to strengthen our community in Uthiru, Kabete and across Nairobi.',
			'kw'    => 'community development Kenya, NGO partnerships Kenya, community based organisation Nairobi',
		),
	);
}

/** Main page keywords, by page slug. */
function cohf_kw_pages() {
	return array(
		'about'                 => array( 'About Our NGO in Uthiru, Nairobi, Kenya', 'NGO in Nairobi, NGO in Kenya, Uthiru, Kabete' ),
		'programmes-overview'   => array( 'Our Programmes: Education, Pads, Youth and Women', 'charity programmes Kenya, school fees support, sanitary pads for girls' ),
		'impact'                => array( 'Our Impact: Children, Women and Youth in Kenya', 'NGO impact Kenya, charity results Kenya' ),
		'approach'              => array( 'Our Approach: From Support to Self-Reliance', 'community development Kenya, sustainable charity' ),
		'get-involved'          => array( 'Volunteer in Nairobi, Kenya or Partner With Us', 'volunteer in Kenya, volunteer in Nairobi, volunteer opportunities Kenya' ),
		'partners-overview'     => array( 'Partner With a Registered NGO in Kenya', 'NGO partnership Kenya, corporate social responsibility Kenya, CSR partners Kenya' ),
		'resources-overview'    => array( 'NGO Reports, Policies and Resources', 'NGO annual report Kenya, NGO policies' ),
		'contact'               => array( 'Contact Us: NGO in Kabete, Nairobi', 'NGO contacts Nairobi, charity near me Nairobi' ),
		'leadership-governance' => array( 'Leadership and Board of Our Kenyan NGO', 'NGO board Kenya, NGO leadership' ),
		'accountability'        => array( 'Accountability and Child Safeguarding', 'child safeguarding policy Kenya, NGO accountability' ),
		'support-our-work'      => array( 'Donate to Charity in Kenya by M-Pesa or Card', 'donate to charity Kenya, donate via M-Pesa, donate to children in Kenya, give to an NGO in Kenya' ),
		'strategic-journey'     => array( 'Strategic Plan 2026-2030', 'NGO strategic plan Kenya' ),
		'gallery'               => array( 'Photo Gallery: Charity Work in Nairobi, Kenya', 'charity photos Kenya, NGO gallery' ),
		'delivery'              => array( 'Delivery and Shipping Across Kenya', 'Hope Market delivery Kenya', 'Hope Market delivers handmade crafts to addresses across Kenya. See delivery fees, delivery times and what to do if your order arrives damaged.' ),
		'refund-returns'        => array( 'Refund and Returns Policy', 'Hope Market returns', 'Return Hope Market items within 7 days of delivery. Damaged or wrong items are replaced or refunded in full, including the delivery fee.' ),
		'privacy-policy'        => array( 'Privacy Policy', 'privacy policy' ),
		'terms-of-use'          => array( 'Terms of Use', 'terms of use' ),
		'donation-policy'       => array( 'Donation and Fundraising Policy', 'donation policy Kenya NGO' ),
	);
}

/** Hope Market category keywords, by category slug: title, description. */
function cohf_kw_categories() {
	return array(
		'jewellery'        => array( 'Maasai Beaded Jewellery, Handmade in Kenya', 'Buy handmade Maasai beaded jewellery in Kenya: necklaces, chokers, earrings, bangles and bracelets. Delivered across Kenya; supports children and women.' ),
		'sandals'          => array( 'Beaded Leather Sandals, Handmade in Kenya', 'Buy handmade Maasai beaded leather sandals in Kenya. Comfortable, colourful and delivered across Kenya. Every pair supports Cistern of Hope Foundation.' ),
		'clothing'         => array( 'Maasai Dresses and African Fashion, Kenya', 'Shop Maasai dresses, kitenge print dresses, kaftans, agbada and senator suits in Kenya. Delivered across Kenya; every purchase supports our charity.' ),
		'bags-and-baskets' => array( 'Handmade African Bags and Baskets, Kenya', 'Buy handmade sisal baskets, kiondo and Ankara bags and beaded clutches from Kenya. Delivered across Kenya; every purchase supports children and women.' ),
		'accessories'      => array( 'African Beaded Accessories from Kenya', 'Shop handmade African accessories from Kenya: beaded hats, caps, belts, keychains and purses. Delivered across Kenya; every purchase supports our charity work.' ),
		'home-and-kitchen' => array( 'Handmade Kitchenware and Wooden Gifts, Kenya', 'Buy handmade kitchenware from Kenya: salad servers, ebony bowls, clay pots and coasters. Delivery across Kenya; every purchase supports our charity work.' ),
		'home-decor'       => array( 'African Home Decor and Wood Carvings, Kenya', 'Shop African home decor from Kenya: copper wall clocks, carvings, soapstone and wall art. Delivery across Kenya; every purchase supports our charity work.' ),
		'flip-flop-art'    => array( 'Flip-Flop Art Animals, Handmade in Kenya', 'Colourful animals carved from recycled flip-flops in Kenya. Unique eco-friendly gifts, delivered across Kenya; every purchase supports our charity work.' ),
	);
}

/** Impact story keywords, by story slug: title, description. */
function cohf_kw_stories() {
	return array(
		'door-to-door-distribution'             => array( 'Door-to-Door Sanitary Pad Distribution, Nairobi', 'Our door-to-door sanitary pad distribution reaches less fortunate girls in our Nairobi community, restoring dignity and helping them stay in school.' ),
		'enterprise-roadside-egg-business'      => array( 'A Roadside Egg Business Finds Its Feet, Kenya', 'A small food cart and a steady trade in eggs: one of the small businesses Cistern of Hope Foundation supports so families in Nairobi earn an income.' ),
		'enterprise-roadside-potato-trade'      => array( 'Growing a Roadside Potato Trade in Nairobi', 'Buckets of potatoes and a roadside stall: how small business support from Cistern of Hope Foundation helps a trader in Nairobi build a livelihood.' ),
		'enterprise-shoe-business'              => array( 'Youth Enterprise: A Young Man\'s Shoe Business', 'A young man in Nairobi takes his first steps in business selling shoes, with youth enterprise support from Cistern of Hope Foundation.' ),
		'enterprise-photography-dan'            => array( 'Support Dan\'s Photography Business in Nairobi', __( 'Dan runs a home photography and photo-mounting business in Nairobi. Help him get a camera, printer and studio space to grow his youth enterprise.', 'cohf-child' ) ),
		'enterprise-tailoring-mr-owino'         => array( 'Mr Owino\'s Tailoring Business, Nairobi', 'Mr Owino began with one sewing machine at home. With tailoring support from Cistern of Hope Foundation, his business in Nairobi is growing.' ),
		'enterprise-womens-vegetable-stall'     => array( 'Women Empowerment: Her Fruit and Vegetable Stall', 'A woman in Nairobi builds income, confidence and dignity through her fruit and vegetable stall, with women\'s enterprise support from our Foundation.' ),
		'fellowship-with-orphans'               => array( 'Supporting Orphans in Our Nairobi Community', 'Sanitary pads, encouragement and shared joy with orphaned children in Nairobi, and our commitment to return every month.' ),
		'monthly-school-sanitary-pad-donations' => array( 'Monthly Sanitary Pad Donations in Kenyan Schools', 'Every month we visit schools to give sanitary pads to girls, so a period is never the reason a girl in Kenya misses class.' ),
		'three-boys-enrolled-in-school'         => array( 'Three Street Boys Enrolled in School, Kenya', 'In June 2026 we took three boys off the streets of Nairobi and enrolled them in school, and we continue to follow their progress.' ),
		'women-empowerment-seminar'             => array( 'Women Empowerment Seminar in Nairobi, Kenya', 'A women empowerment seminar in Nairobi equipping women with knowledge, confidence and practical tools to transform their families and community.' ),
	);
}

/** Current programme / page / category keyword entry, if any. */
function cohf_kw_current() {
	if ( is_singular( 'cohf_programme' ) ) {
		$map  = cohf_kw_programmes();
		$slug = get_post_field( 'post_name', get_queried_object_id() );
		return isset( $map[ $slug ] ) ? $map[ $slug ] : null;
	}
	if ( is_page() ) {
		$map  = cohf_kw_pages();
		$slug = get_post_field( 'post_name', get_queried_object_id() );
		if ( ! isset( $map[ $slug ] ) ) {
			return null;
		}
		$out = array( 'title' => $map[ $slug ][0], 'kw' => $map[ $slug ][1] );
		if ( ! empty( $map[ $slug ][2] ) ) {
			$out['desc'] = $map[ $slug ][2];
		}
		return $out;
	}
	if ( is_singular( 'cohf_story' ) ) {
		$map  = cohf_kw_stories();
		$slug = get_post_field( 'post_name', get_queried_object_id() );
		if ( isset( $map[ $slug ] ) ) {
			return array( 'title' => $map[ $slug ][0], 'desc' => $map[ $slug ][1], 'kw' => 'charity in Kenya, NGO in Nairobi, ' . strtolower( $map[ $slug ][0] ) );
		}
		return null;
	}
	if ( function_exists( 'is_product_category' ) && is_product_category() ) {
		$map  = cohf_kw_categories();
		$term = get_queried_object();
		if ( $term && isset( $map[ $term->slug ] ) ) {
			return array( 'title' => $map[ $term->slug ][0], 'desc' => $map[ $term->slug ][1] );
		}
	}
	return null;
}

/* -------------------------------------------------------------------------
   Titles.
   ------------------------------------------------------------------------- */
add_filter( 'document_title_parts', function ( $parts ) {
	if ( cohf_seo_plugin_active() ) {
		return $parts;
	}
	if ( is_front_page() ) {
		return array( 'title' => 'Cistern of Hope Foundation: NGO in Nairobi, Kenya' );
	}
	if ( function_exists( 'is_shop' ) && is_shop() ) {
		$parts['title'] = 'Hope Market: Handmade Kenyan Crafts Online';
	}
	if ( function_exists( 'is_product' ) && is_product() ) {
		$p = wc_get_product( get_the_ID() );
		if ( $p ) {
			$price          = html_entity_decode( wp_strip_all_tags( wc_price( (float) wc_get_price_to_display( $p ) ) ) );
			$name           = html_entity_decode( wp_strip_all_tags( get_the_title() ) );
			$parts['title'] = $name . ', ' . $price . ' | Hope Market Kenya';
			unset( $parts['site'] );
			return $parts;
		}
	}
	$kw = cohf_kw_current();
	if ( $kw && ! empty( $kw['title'] ) ) {
		$parts['title'] = $kw['title'];
	}
	if ( is_post_type_archive( 'cohf_story' ) && ! is_paged() ) {
		$parts['title'] = 'Impact Stories from Our Work in Kenya';
	}
	return $parts;
}, 20 );

/** Keep titles about 60 characters: short brand, or no brand on long titles. */
add_filter( 'document_title_parts', function ( $parts ) {
	if ( cohf_seo_plugin_active() || empty( $parts['title'] ) ) {
		return $parts;
	}
	unset( $parts['tagline'] );
	if ( isset( $parts['site'] ) ) {
		$len = function_exists( 'mb_strlen' ) ? mb_strlen( html_entity_decode( $parts['title'] ) ) : strlen( $parts['title'] );
		if ( $len + 31 <= 62 ) {
			return $parts; // Full name fits.
		}
		if ( $len + 18 <= 64 ) {
			$parts['site'] = cohf_kw_brand();
		} else {
			unset( $parts['site'] );
		}
	}
	return $parts;
}, 99 );

/* -------------------------------------------------------------------------
   Meta descriptions.
   ------------------------------------------------------------------------- */
add_filter( 'cohf_seo_description', function ( $text ) {
	$kw = cohf_kw_current();
	if ( $kw && ! empty( $kw['desc'] ) ) {
		return $kw['desc'];
	}
	if ( is_front_page() ) {
		return 'Registered NGO in Nairobi, Kenya helping orphans, needy children, widows, women and youth with school fees, sanitary pads, food and small businesses.';
	}
	if ( is_post_type_archive( 'cohf_story' ) ) {
		return 'Real stories from our work in Nairobi, Kenya: children back in school, girls receiving sanitary pads, and women and youth building small businesses.';
	}
	if ( function_exists( 'is_product' ) && is_product() ) {
		$p = wc_get_product( get_the_ID() );
		if ( $p ) {
			$price = html_entity_decode( wp_strip_all_tags( wc_price( (float) wc_get_price_to_display( $p ) ) ) );
			$text  = trim( (string) $text );
			if ( '' === $text || strlen( $text ) < 100 ) {
				$base = '' === $text ? 'Buy ' . html_entity_decode( wp_strip_all_tags( get_the_title() ) ) . ', handmade in Kenya.' : rtrim( $text, ' .' ) . '.';
				$text = $base . ' ' . $price . ' with delivery across Kenya. Every purchase supports Cistern of Hope Foundation.';
				if ( strlen( $text ) > 158 ) {
					$text = $base . ' ' . $price . ', delivered across Kenya.';
				}
			}
		}
	}
	return $text;
} );

/* -------------------------------------------------------------------------
   Structured data.
   ------------------------------------------------------------------------- */
add_filter( 'cohf_schema_graph_nodes', function ( $graph ) {
	$kw = cohf_kw_current();
	foreach ( $graph as &$node ) {
		$type = isset( $node['@type'] ) ? (array) $node['@type'] : array();
		if ( in_array( 'NGO', $type, true ) ) {
			$node['alternateName'] = array( 'Cistern of Hope', 'COHF', 'Cistern of Hope Foundation Kenya' );
			$node['slogan']        = 'Restoring hope, dignity and opportunity in Kenya';
			$node['knowsAbout']    = array( 'Education support and school fees for needy children', 'Sanitary pads and menstrual hygiene for girls', 'Support for widows and food aid', 'Women\'s economic empowerment', 'Youth skills and employment', 'Street children and orphans', 'Counselling and mentorship', 'Community development in Nairobi, Kenya' );
			$node['keywords']      = 'NGO in Kenya, NGO in Nairobi, charity in Kenya, donate to charity Kenya, donate via M-Pesa, sponsor a child in Kenya, school fees support Kenya, sanitary pads for girls Kenya, help widows in Kenya, women empowerment Kenya, youth empowerment Kenya, street children Kenya, orphans in Kenya, volunteer in Nairobi';
		} elseif ( in_array( 'WebSite', $type, true ) ) {
			$node['alternateName'] = array( 'Cistern of Hope', 'Hope Market' );
			$node['inLanguage']    = 'en-KE';
		} elseif ( in_array( 'Service', $type, true ) && $kw ) {
			$node['serviceType'] = $kw['title'];
			$node['category']    = 'Charity programme';
			if ( ! empty( $kw['kw'] ) ) {
				$node['keywords'] = $kw['kw'];
			}
			$node['isAccessibleForFree'] = true;
			$node['areaServed']          = array(
				array( '@type' => 'City', 'name' => 'Nairobi' ),
				array( '@type' => 'AdministrativeArea', 'name' => 'Kiambu County' ),
				array( '@type' => 'Country', 'name' => 'Kenya' ),
			);
		} elseif ( in_array( 'Article', $type, true ) ) {
			$node['about']    = array( '@id' => cohf_schema_id( 'organization' ) );
			$node['keywords'] = 'charity in Kenya, NGO in Nairobi, impact story, ' . wp_strip_all_tags( get_the_title() );
			$node['locationCreated'] = array( '@type' => 'Place', 'name' => 'Nairobi, Kenya' );
		} elseif ( isset( $node['@id'] ) && false !== strpos( (string) $node['@id'], '#webpage' ) && $kw && ! empty( $kw['kw'] ) ) {
			$node['keywords'] = $kw['kw'];
		}
	}
	unset( $node );

	// Shop and category pages: list the products on the page.
	$is_listing = ( function_exists( 'is_shop' ) && is_shop() ) || ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() );
	if ( $is_listing ) {
		global $wp_query;
		$items = array();
		$pos   = 1;
		foreach ( (array) $wp_query->posts as $post ) {
			$items[] = array( '@type' => 'ListItem', 'position' => $pos++, 'url' => get_permalink( $post ), 'name' => wp_strip_all_tags( get_the_title( $post ) ) );
		}
		if ( $items ) {
			$graph[] = array( '@type' => 'ItemList', '@id' => cohf_schema_url() . '#products', 'name' => wp_strip_all_tags( wp_get_document_title() ), 'numberOfItems' => count( $items ), 'itemListElement' => $items );
		}
	}
	return $graph;
} );

/** Products: return policy (7 days, Kenya) for Google merchant listings. */
add_filter( 'woocommerce_structured_data_product', function ( $markup, $product ) {
	if ( ! empty( $markup['offers'] ) && is_array( $markup['offers'] ) ) {
		foreach ( $markup['offers'] as &$offer ) {
			$offer['hasMerchantReturnPolicy'] = array(
				'@type'                => 'MerchantReturnPolicy',
				'applicableCountry'    => 'KE',
				'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
				'merchantReturnDays'   => 7,
				'returnMethod'         => 'https://schema.org/ReturnByMail',
				'returnFees'           => 'https://schema.org/ReturnFeesCustomerResponsibility',
				'merchantReturnLink'   => home_url( '/refund-returns/' ),
			);
		}
		unset( $offer );
	}
	$markup['countryOfOrigin'] = array( '@type' => 'Country', 'name' => 'Kenya' );
	$markup['audience']        = array( '@type' => 'PeopleAudience', 'geographicArea' => array( '@type' => 'Country', 'name' => 'Kenya' ) );
	return $markup;
}, 30, 2 );
