<?php
/**
 * Core theme configuration, header and footer output.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Child theme supports.
 */
function cohf_child_setup() {
	load_child_theme_textdomain( 'cohf-child', COHF_CHILD_DIR . '/languages' );

	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );

	// Gutenberg palette mirrors the design tokens so editors cannot drift off-brand.
	add_theme_support( 'editor-color-palette', array(
		array( 'name' => __( 'Hope Green', 'cohf-child' ),  'slug' => 'hope-green',  'color' => '#1d4634' ),
		array( 'name' => __( 'Deep Forest', 'cohf-child' ), 'slug' => 'deep-forest', 'color' => '#16342a' ),
		array( 'name' => __( 'Terracotta', 'cohf-child' ),  'slug' => 'terracotta',  'color' => '#a9502c' ),
		array( 'name' => __( 'Sunlight', 'cohf-child' ),    'slug' => 'sunlight',    'color' => '#d9a227' ),
		array( 'name' => __( 'Muted Sage', 'cohf-child' ),  'slug' => 'muted-sage',  'color' => '#9fb8a6' ),
		array( 'name' => __( 'Soft Cream', 'cohf-child' ),  'slug' => 'soft-cream',  'color' => '#f7f3ea' ),
		array( 'name' => __( 'Warm White', 'cohf-child' ),  'slug' => 'warm-white',  'color' => '#fcfaf6' ),
		array( 'name' => __( 'Charcoal', 'cohf-child' ),    'slug' => 'charcoal',    'color' => '#1c1b19' ),
	) );
	add_theme_support( 'disable-custom-colors' );
	add_theme_support( 'editor-font-sizes', array(
		array( 'name' => __( 'Small', 'cohf-child' ),  'slug' => 'small',  'size' => 15 ),
		array( 'name' => __( 'Body', 'cohf-child' ),   'slug' => 'body',   'size' => 17 ),
		array( 'name' => __( 'Lead', 'cohf-child' ),   'slug' => 'lead',   'size' => 21 ),
		array( 'name' => __( 'Heading', 'cohf-child' ),'slug' => 'heading','size' => 30 ),
	) );

	// WooCommerce is optional; declare support so the theme stays compatible
	// if the Foundation later sells merchandise or issues ticketed events.
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-zoom' );
}
add_action( 'after_setup_theme', 'cohf_child_setup', 11 );

/**
 * Single source of truth for organisation details.
 * Every template and the schema output read from here, so the Foundation
 * changes a detail once and it updates everywhere.
 *
 * Values come only from the Foundation's own documents.
 *
 * @return array<string,string>
 */
function cohf_org() {
	$defaults = cohf_org_defaults();
	$values   = array();

	foreach ( $defaults as $key => $default ) {
		$values[ $key ] = get_option( 'cohf_org_' . $key, $default );
	}

	return apply_filters( 'cohf_org', $values );
}

/**
 * Factory-default organisation details.
 *
 * Every one of these used to be a hardcoded literal except name, address,
 * phone and email. That meant the Foundation could not change its own mission
 * statement, vision, motto or founding details without a developer editing
 * PHP - and the mission in particular is published in several places,
 * including the Partners snapshot table and the structured data search
 * engines read. They are all options now, editable under Foundation >
 * Organisation details, and these remain the fallbacks.
 *
 * @return array<string,string>
 */
function cohf_org_defaults() {
	return array(
		'name'         => 'Cistern of Hope Foundation',
		'abbr'         => 'COHF',
		'motto'        => 'Together for a lasting change.',
		'strapline'    => 'Kenya • Community-led poverty eradication',
		'descriptor'   => 'Restoring hope, promoting dignity, empowering people and strengthening communities in Kenya.',
		'vision'       => 'To create poverty-free communities, built on sustainable solutions and empowered individuals.',
		'mission'      => 'To eradicate poverty by empowering communities through sustainable initiatives that promote self-reliance, economic development, and social welfare.',
		'founded'      => '2021',
		'origin'       => 'Uthiru, Nairobi',
		'registered'   => '2025',
		'constitution' => '21 June 2024',
		'country'      => 'Kenya',
		'address'      => 'P.O. Box 23524–00625, Nairobi, Kenya',
		'phone'        => '+254 110 304 521',
		'whatsapp'     => '+254 110 304 521',
		'email'        => 'info@cisternofhopefoundation.org',
		// Social accounts. Empty until the Foundation confirms each one; an
		// empty field hides that icon everywhere.
		'facebook'     => '',
		'instagram'    => '',
		'x'            => '',
		'linkedin'     => '',
		'youtube'      => '',
		'tiktok'       => '',
	);
}

/**
 * Convenience accessor.
 *
 * @param string $key Organisation key.
 * @return string
 */
function cohf_org_get( $key ) {
	$org = cohf_org();
	return isset( $org[ $key ] ) ? (string) $org[ $key ] : '';
}

/**
 * Resolve a page URL by its template file, falling back to home.
 *
 * @param string $template Page template filename.
 * @return string
 */
function cohf_page_url( $template ) {
	$cache_key = 'cohf_page_url_' . md5( $template );
	$cached    = wp_cache_get( $cache_key, 'cohf' );
	if ( false !== $cached ) {
		return $cached;
	}

	$pages = get_pages( array(
		'meta_key'   => '_wp_page_template',
		'meta_value' => $template,
		'number'     => 1,
	) );

	$url = ! empty( $pages ) ? get_permalink( $pages[0]->ID ) : home_url( '/' );
	wp_cache_set( $cache_key, $url, 'cohf', HOUR_IN_SECONDS );

	return $url;
}

/**
 * Primary and secondary CTA destinations.
 *
 * @return array<string,string>
 */
function cohf_cta_links() {
	return array(
		'support' => cohf_page_url( 'page-templates/page-support.php' ),
		'partner' => cohf_page_url( 'page-templates/page-partners.php' ),
	);
}

/**
 * Base URL for the brand asset directory.
 *
 * @return string Trailing-slashed URL.
 */
function cohf_brand_uri() {
	return COHF_CHILD_URI . '/assets/images/brand/';
}

/**
 * Render the Foundation's logo mark - the four interlocking hands.
 *
 * The mark is used rather than the full horizontal lockup anywhere the
 * available width is constrained. The supplied lockup is roughly 3:1, so at a
 * header-sized height its tagline is illegible; the mark stays crisp at any
 * size and reads on both light and dark surfaces.
 *
 * alt is intentionally empty: the organisation name sits next to it as live
 * text, and captioning both makes screen readers announce it twice.
 *
 * @param int $size Rendered height in CSS pixels.
 */
function cohf_logo_mark( $size = 40 ) {
	$base = cohf_brand_uri();
	printf(
		'<img class="mark mark--logo" src="%1$s" srcset="%1$s 1x, %2$s 2x" width="%3$d" height="%3$d" alt="" decoding="async" fetchpriority="high">',
		esc_url( $base . 'logo-mark.png' ),
		esc_url( $base . 'logo-mark@2x.png' ),
		(int) $size
	);
}

/**
 * Render the full horizontal logo lockup.
 *
 * Only suitable where there is real width and a light background. The wordmark
 * is dark green and red, so it must not be placed on the Foundation's dark
 * green surfaces - use cohf_logo_mark() with live text there instead.
 *
 * @param string $classes Extra class names.
 */
function cohf_logo_full( $classes = '' ) {
	$base = cohf_brand_uri();
	printf(
		'<img class="brand-lockup %4$s" src="%1$s" srcset="%1$s 1x, %2$s 2x" width="960" height="248" alt="%3$s" decoding="async">',
		esc_url( $base . 'logo-full.png' ),
		esc_url( $base . 'logo-full@2x.png' ),
		esc_attr( cohf_org_get( 'name' ) . ' - ' . cohf_org_get( 'motto' ) ),
		esc_attr( $classes )
	);
}

/**
 * Render the stacked logo lockup - mark above the wordmark.
 *
 * The supplied artwork is a roughly 3:1 rectangle, which wastes height and
 * crowds narrow columns. This stacked arrangement is close to square, so it
 * suits sidebars, cards, share images and print without shrinking the type.
 *
 * Like the horizontal lockup the wordmark is dark, so keep it on light
 * surfaces only.
 *
 * @param string $classes Extra class names.
 */
function cohf_logo_stacked( $classes = '' ) {
	$base = cohf_brand_uri();
	printf(
		'<img class="brand-lockup brand-lockup--stacked %4$s" src="%1$s" srcset="%1$s 1x, %2$s 2x" width="460" height="506" alt="%3$s" decoding="async">',
		esc_url( $base . 'logo-stacked.png' ),
		esc_url( $base . 'logo-stacked@2x.png' ),
		esc_attr( cohf_org_get( 'name' ) . ' - ' . cohf_org_get( 'motto' ) ),
		esc_attr( $classes )
	);
}

/**
 * Print the uploaded Custom Logo as a bare image.
 *
 * the_custom_logo() wraps the image in its own <a class="custom-logo-link">.
 * The header already wraps the whole brand in an anchor, and nesting anchors
 * is invalid HTML - browsers close the outer link early, which breaks the
 * header layout. So the image is emitted directly instead.
 *
 * @return bool True when an image was printed.
 */
function cohf_custom_logo_image() {
	$id = (int) get_theme_mod( 'custom_logo' );
	if ( 0 === $id ) {
		return false;
	}
	echo wp_get_attachment_image(
		$id,
		'full',
		false,
		array(
			'class'    => 'custom-logo',
			'alt'      => '',
			'decoding' => 'async',
		)
	);
	return true;
}

/**
 * Does the uploaded Custom Logo already contain the organisation's name?
 *
 * The Foundation's supplied artwork is a roughly 2.5:1 lockup with the name
 * and tagline built in. When that is uploaded as the Custom Logo the header
 * would otherwise print the name a second time as live text, which both
 * duplicates the branding and squeezes the navigation onto a second line.
 *
 * A square-ish upload is treated as a mark alone, so the live text is kept.
 * Override with the cohf_custom_logo_has_wordmark filter.
 *
 * @return bool True when the image is wide enough to be a full lockup.
 */
function cohf_custom_logo_has_wordmark() {
	$verdict = null;
	$id      = (int) get_theme_mod( 'custom_logo' );

	if ( $id ) {
		$meta = wp_get_attachment_metadata( $id );
		if ( is_array( $meta ) && ( 0 < (int) ( isset( $meta['height'] ) ? $meta['height'] : 0 ) ) ) {
			$ratio   = (int) $meta['width'] / (int) $meta['height'];
			$verdict = ( $ratio >= 2.0 );
		}
	}

	if ( null === $verdict ) {
		$verdict = false;
	}

	return (bool) apply_filters( 'cohf_custom_logo_has_wordmark', $verdict, $id );
}

/**
 * Use the logo mark as the browser/site icon when the admin has not set one.
 *
 * Only fires when no Site Icon is configured, so an explicit choice in the
 * Customizer always wins.
 */
function cohf_default_site_icon() {
	if ( has_site_icon() ) {
		return;
	}
	$icon = cohf_brand_uri() . 'site-icon.png';
	printf( '<link rel="icon" href="%s" sizes="512x512">' . "\n", esc_url( $icon ) );
	printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( $icon ) );
}
add_action( 'wp_head', 'cohf_default_site_icon', 5 );

/**
 * Menu fallback so a fresh install is never navigation-less.
 */
function cohf_fallback_menu() {
	echo '<ul>';
	foreach ( cohf_default_nav_items() as $item ) {
		printf(
			'<li><a href="%s">%s</a></li>',
			esc_url( $item['url'] ),
			esc_html( $item['label'] )
		);
	}
	echo '</ul>';
}

/**
 * Homepage hero slides.
 *
 * Four slides, each with its own image, headline and calls to action. Keep
 * headlines short: the hero is deliberately height-capped so that every slide
 * fits inside the first viewport without scrolling, and long headlines are the
 * one thing that breaks that guarantee.
 *
 * Filterable via 'cohf_hero_slides' so slides can be changed without editing
 * the template.
 *
 * @return array List of slide definitions.
 */
function cohf_hero_slides() {
	$cta = cohf_cta_links();

	return apply_filters( 'cohf_hero_slides', array(
		array(
			'image'           => 'hero-home',
			'eyebrow'         => __( 'Restoring hope, building opportunity', 'cohf-child' ),
			'title'           => __( 'Creating pathways to hope and self-reliance.', 'cohf-child' ),
			'text'            => __( 'We work with vulnerable people and communities in Kenya to reduce poverty, strengthen resilience and create sustainable opportunity.', 'cohf-child' ),
			'primary_label'   => __( 'Support Our Work', 'cohf-child' ),
			'primary_url'     => $cta['support'],
			'secondary_label' => __( 'Our Story', 'cohf-child' ),
			'secondary_url'   => cohf_page_url( 'page-templates/page-about.php' ),
		),
		array(
			'image'           => 'programme-01',
			'eyebrow'         => __( 'Education and child development', 'cohf-child' ),
			'title'           => __( 'Helping vulnerable children stay in school.', 'cohf-child' ),
			'text'            => __( 'School fees, learning materials and the stability children need to remain in the classroom.', 'cohf-child' ),
			'primary_label'   => __( 'Our Programmes', 'cohf-child' ),
			'primary_url'     => cohf_page_url( 'page-templates/page-programmes.php' ),
			'secondary_label' => __( 'See Our Impact', 'cohf-child' ),
			'secondary_url'   => cohf_page_url( 'page-templates/page-impact.php' ),
		),
		array(
			'image'           => 'programme-03',
			'eyebrow'         => __( "Women's economic empowerment", 'cohf-child' ),
			'title'           => __( 'Enterprise that outlasts the funding.', 'cohf-child' ),
			'text'            => __( 'Training, mentorship and market linkages so supported women can sustain and grow viable businesses.', 'cohf-child' ),
			'primary_label'   => __( 'Partner With Us', 'cohf-child' ),
			'primary_url'     => $cta['partner'],
			'secondary_label' => __( 'Our Approach', 'cohf-child' ),
			'secondary_url'   => cohf_page_url( 'page-templates/page-approach.php' ),
		),
		array(
			'image'           => 'hero-get-involved',
			'eyebrow'         => __( 'Together for a lasting change', 'cohf-child' ),
			'title'           => __( 'Communities as partners, not recipients.', 'cohf-child' ),
			'text'            => __( 'Lasting change begins when people choose to care, serve and act together. There are many ways to take part.', 'cohf-child' ),
			'primary_label'   => __( 'Get Involved', 'cohf-child' ),
			'primary_url'     => cohf_page_url( 'page-templates/page-get-involved.php' ),
			'secondary_label' => __( 'Contact Us', 'cohf-child' ),
			'secondary_url'   => cohf_page_url( 'page-templates/page-contact.php' ),
		),
	) );
}

/**
 * Canonical primary navigation.
 *
 * @return array<int,array<string,string>>
 */
function cohf_default_nav_items() {
	$items = array(
		array(
			'label' => __( 'Home', 'cohf-child' ),
			'url'   => home_url( '/' ),
		),
		array(
			'label'    => __( 'About', 'cohf-child' ),
			'url'      => cohf_page_url( 'page-templates/page-about.php' ),
			'children' => array(
				array( 'label' => __( 'Who We Are', 'cohf-child' ),              'url' => cohf_page_url( 'page-templates/page-about.php' ),          'desc' => __( 'Our story, vision and mission', 'cohf-child' ) ),
				array( 'label' => __( 'Leadership & Governance', 'cohf-child' ), 'url' => cohf_page_url( 'page-templates/page-leadership.php' ),     'desc' => __( 'The people entrusted with the work', 'cohf-child' ) ),
				array( 'label' => __( 'Our Strategic Journey', 'cohf-child' ),   'url' => cohf_page_url( 'page-templates/page-strategy.php' ),       'desc' => __( 'Where we are going, 2026 to 2030', 'cohf-child' ) ),
				array( 'label' => __( 'Partners', 'cohf-child' ),                'url' => cohf_page_url( 'page-templates/page-partners.php' ),       'desc' => __( 'Who we work alongside', 'cohf-child' ) ),
				array( 'label' => __( 'Accountability', 'cohf-child' ),          'url' => cohf_page_url( 'page-templates/page-accountability.php' ), 'desc' => __( 'Safeguarding, finance and complaints', 'cohf-child' ) ),
			),
		),
		array(
			'label'    => __( 'Programmes', 'cohf-child' ),
			'url'      => cohf_page_url( 'page-templates/page-programmes.php' ),
			'children' => cohf_programme_nav_children(),
		),
		array(
			'label'    => __( 'Impact', 'cohf-child' ),
			'url'      => cohf_page_url( 'page-templates/page-impact.php' ),
			'children' => cohf_impact_nav_children(),
		),
		array( 'label' => __( 'Our Approach', 'cohf-child' ),   'url' => cohf_page_url( 'page-templates/page-approach.php' ) ),
		array(
			'label'    => __( 'Explore', 'cohf-child' ),
			'url'      => '',
			'children' => cohf_explore_nav_children(),
		),
	);

	return $items;
}

/**
 * Programme entries for the Programmes dropdown.
 *
 * Reads live programme records so the menu follows the content rather than a
 * second hard-coded list that can drift out of step.
 *
 * @return array
 */
function cohf_programme_nav_children() {
	$children = array(
		array(
			'label' => __( 'All Programmes', 'cohf-child' ),
			'url'   => cohf_page_url( 'page-templates/page-programmes.php' ),
			'desc'  => __( 'The full portfolio of our work', 'cohf-child' ),
		),
	);

	$programmes = get_posts( array(
		'post_type'        => 'cohf_programme',
		'post_status'      => 'publish',
		'numberposts'      => 6,
		'orderby'          => 'menu_order title',
		'order'            => 'ASC',
		'suppress_filters' => false,
	) );

	foreach ( $programmes as $programme ) {
		$children[] = array(
			'label' => get_the_title( $programme ),
			'url'   => get_permalink( $programme ),
			'desc'  => '',
		);
	}

	return $children;
}


/**
 * Children of the Impact dropdown.
 *
 * The Impact page reports the figures; the stories archive carries the human
 * accounts behind them. Both belong under one heading. Before this the
 * archive had no route into the navigation at all, so the only way to reach
 * it was to know the URL.
 *
 * @return array
 */
function cohf_impact_nav_children() {
	$children = array(
		array(
			'label' => __( 'Our Impact', 'cohf-child' ),
			'url'   => cohf_page_url( 'page-templates/page-impact.php' ),
			'desc'  => __( 'Reported results and how we measure them', 'cohf-child' ),
		),
	);

	// Only offered when the post type is registered and has an archive, so
	// disabling stories can never leave a dead item in the menu.
	$stories = get_post_type_archive_link( 'cohf_story' );

	if ( $stories ) {
		$children[] = array(
			'label' => __( 'Impact Stories', 'cohf-child' ),
			'url'   => $stories,
			'desc'  => __( 'Accounts from the people we work alongside', 'cohf-child' ),
		);
	}

	$gallery = cohf_page_url( 'page-templates/page-gallery.php' );
	if ( $gallery ) {
		$children[] = array(
			'label' => __( 'Photo Gallery', 'cohf-child' ),
			'url'   => $gallery,
			'desc'  => __( 'Our work, in pictures', 'cohf-child' ),
		);
	}

	return $children;
}

/**
 * Children of the Explore dropdown.
 *
 * Hope Market only appears once WooCommerce is active, so the menu never
 * links to a storefront that does not exist.
 *
 * @return array
 */
function cohf_explore_nav_children() {
	$children = array(
		array( 'label' => __( 'Resources', 'cohf-child' ),    'url' => cohf_page_url( 'page-templates/page-resources.php' ),    'desc' => __( 'Reports, policies and publications', 'cohf-child' ) ),
		array( 'label' => __( 'Get Involved', 'cohf-child' ), 'url' => cohf_page_url( 'page-templates/page-get-involved.php' ), 'desc' => __( 'Partner, volunteer or give', 'cohf-child' ) ),
	);

	if ( function_exists( 'cohf_shop_url' ) ) {
		$shop = cohf_shop_url();
		if ( '' !== $shop ) {
			$children[] = array(
				'label' => cohf_shop_name(),
				'url'   => $shop,
				'desc'  => __( 'Buy a craft. Support the mission.', 'cohf-child' ),
			);
		}
	}

	$children[] = array( 'label' => __( 'Contact', 'cohf-child' ), 'url' => cohf_page_url( 'page-templates/page-contact.php' ), 'desc' => __( 'Reach the Foundation directly', 'cohf-child' ) );

	return $children;
}

/**
 * Resolve the primary navigation.
 *
 * A menu assigned to the 'primary' location in the WordPress admin now wins.
 * The theme's own list is the fallback for when no menu has been assigned.
 *
 * History, because this reverses an earlier decision: the theme used to
 * override the WordPress menu unconditionally. The reason given was that the
 * live site had nine items assigned in Appearance > Menus ("Home" and
 * "Partners" on top of the intended seven), which overflowed the container
 * and broke the header onto two lines.
 *
 * That treated a layout bug as a content problem. The consequence was that
 * the menu editor silently did nothing: an administrator could add, reorder
 * or remove items in Appearance > Menus or the Customizer and the front end
 * would never change, with nothing on screen explaining why. Adding "Home"
 * and having it not appear is exactly that failure.
 *
 * The overflow is fixed where it belongs, in the stylesheet - see the header
 * navigation section of assets/css/ux-refinements.css, which tightens the
 * row progressively so a longer menu stays on one line. Editors get their
 * menu back.
 *
 * Setting the 'cohf_nav_source' option, or filtering it, still forces either
 * source explicitly. Individual fallback items remain filterable through
 * 'cohf_nav_items'.
 *
 * @return array{source:string,items:array} Navigation source and items.
 */
function cohf_nav() {
	/*
	 * An assigned menu is an explicit act by an administrator, so it is the
	 * default source - but only once it has the structure to replace what it
	 * is replacing. The designed header groups pages under About, Programmes
	 * and Explore as dropdowns, and a flat menu assigned to this location
	 * silently flattens the whole header. Nobody builds a flat menu in order
	 * to lose their dropdowns, so a menu with no sub-items reads as "not yet
	 * structured" rather than as an instruction. One sub-item is enough to
	 * show intent, and then the menu wins outright.
	 *
	 * See inc/nav-structure.php, which explains this on the menus screen and
	 * offers a one-click import of the grouped structure.
	 */
	$structured = ! function_exists( 'cohf_primary_menu_has_children' ) || cohf_primary_menu_has_children();
	$default    = ( has_nav_menu( 'primary' ) && $structured ) ? 'wordpress' : 'theme';

	$source = get_option( 'cohf_nav_source', $default );
	$source = apply_filters( 'cohf_nav_source', $source );

	$items = apply_filters( 'cohf_nav_items', cohf_default_nav_items() );

	return array(
		'source' => ( 'wordpress' === $source ) ? 'wordpress' : 'theme',
		'items'  => $items,
	);
}



/**
 * Confirmed social media accounts, in display order.
 *
 * Only networks with a saved URL are returned, so the footer and structured
 * data show nothing until the Foundation adds a link under
 * Foundation > Organisation details > Social media.
 *
 * @return array<string,array{label:string,url:string}>
 */
function cohf_social_links() {
	$org      = cohf_org();
	$networks = array(
		'facebook'  => __( 'Facebook', 'cohf-child' ),
		'instagram' => __( 'Instagram', 'cohf-child' ),
		'x'         => __( 'X (Twitter)', 'cohf-child' ),
		'linkedin'  => __( 'LinkedIn', 'cohf-child' ),
		'youtube'   => __( 'YouTube', 'cohf-child' ),
		'tiktok'    => __( 'TikTok', 'cohf-child' ),
	);
	$links = array();

	foreach ( $networks as $key => $label ) {
		$url = isset( $org[ $key ] ) ? esc_url_raw( trim( (string) $org[ $key ] ) ) : '';
		if ( '' !== $url ) {
			$links[ $key ] = array( 'label' => $label, 'url' => $url );
		}
	}

	return apply_filters( 'cohf_social_links', $links );
}
