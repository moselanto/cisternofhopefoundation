<?php
/**
 * Photo gallery.
 *
 * The gallery is built from the bundled image library, so every photograph
 * ships with the theme, carries proper alt text and can still be replaced
 * from the Customizer like any other bundled image. To add a photograph:
 * drop the file into assets/images/, register it in cohf_image_library()
 * and add a line below.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Gallery categories, in display order. Keys are used as filter values.
 *
 * @return array<string,string>
 */
function cohf_gallery_categories() {
	return array(
		'education'  => __( 'Education', 'cohf-child' ),
		'dignity'    => __( 'Menstrual dignity', 'cohf-child' ),
		'women'      => __( 'Women\'s empowerment', 'cohf-child' ),
		'enterprise' => __( 'Enterprise & livelihoods', 'cohf-child' ),
		'community'  => __( 'Community outreach', 'cohf-child' ),
	);
}

/**
 * Gallery photographs, newest and strongest first.
 *
 * @return array<int,array<string,string>>
 */
function cohf_gallery_items() {
	$items = array(
		array( 'key' => 'story-02-fellowship-tshirts',       'cat' => 'dignity',    'title' => __( 'Children\'s home visit', 'cohf-child' ),              'caption' => __( 'Our team, in Cistern of Hope Foundation T-shirts, sharing sanitary pads and encouragement with children at a children\'s home.', 'cohf-child' ) ),
		array( 'key' => 'gallery-childrens-home-group',      'cat' => 'dignity',    'title' => __( 'Together at the children\'s home', 'cohf-child' ),   'caption' => __( 'Children and our team together after sharing sanitary pads, encouragement and a lot of laughter.', 'cohf-child' ) ),
		array( 'key' => 'story-04-after',                    'cat' => 'education',  'title' => __( 'Back in school', 'cohf-child' ),                      'caption' => __( 'Three boys we took off the streets, now in new uniforms, shoes and school bags outside their primary school.', 'cohf-child' ) ),
		array( 'key' => 'gallery-three-boys-at-school',      'cat' => 'education',  'title' => __( 'Ready to learn', 'cohf-child' ),                      'caption' => __( 'The three boys we took off the streets, standing proud in new uniforms at their primary school.', 'cohf-child' ) ),
		array( 'key' => 'story-05-school-pads',              'cat' => 'dignity',    'title' => __( 'Monthly school pad donations', 'cohf-child' ),        'caption' => __( 'Every month we visit schools to give sanitary pads to girls, so a period is never the reason a girl misses class.', 'cohf-child' ) ),
		array( 'key' => 'gallery-school-pads-celebration',   'cat' => 'dignity',    'title' => __( 'Celebrating dignity', 'cohf-child' ),                 'caption' => __( 'Schoolgirls raising their sanitary pads in celebration during one of our school visits.', 'cohf-child' ) ),
		array( 'key' => 'gallery-womens-seminar',            'cat' => 'women',      'title' => __( 'Women empowerment seminar', 'cohf-child' ),           'caption' => __( 'Women gathered in a circle to discuss identity, confidence and the practical tools of transformation.', 'cohf-child' ) ),
		array( 'key' => 'gallery-before-school-meeting',     'cat' => 'education',  'title' => __( 'Where the journey began', 'cohf-child' ),             'caption' => __( 'Our team with a boy, barefoot and in a torn uniform, before the Foundation helped him back into school.', 'cohf-child' ) ),
		array( 'key' => 'story-04-before',                   'cat' => 'education',  'title' => __( 'Before school', 'cohf-child' ),                       'caption' => __( 'Barefoot and in a torn uniform: the reality for one of the boys before the Foundation stepped in.', 'cohf-child' ) ),
		array( 'key' => 'gallery-shared-meal',               'cat' => 'community',  'title' => __( 'A meal shared', 'cohf-child' ),                       'caption' => __( 'Children sitting down together to a hot, nourishing meal.', 'cohf-child' ) ),
		array( 'key' => 'gallery-food-supplies',             'cat' => 'community',  'title' => __( 'Food for families', 'cohf-child' ),                   'caption' => __( 'Cooking oil, bread, flour, water and other essentials packed and ready for distribution.', 'cohf-child' ) ),
		array( 'key' => 'gallery-food-staples-purchase',     'cat' => 'community',  'title' => __( 'Buying for the community', 'cohf-child' ),            'caption' => __( 'Sourcing sacks of rice at the market ahead of a food distribution.', 'cohf-child' ) ),
		array( 'key' => 'gallery-shoe-donation',             'cat' => 'enterprise', 'title' => __( 'A shoe business', 'cohf-child' ),                     'caption' => __( 'Empowering youth and women to start and sustain small businesses for improved livelihoods and self-reliance.', 'cohf-child' ) ),
		array( 'key' => 'gallery-womens-enterprise-stall',   'cat' => 'enterprise', 'title' => __( 'Women in enterprise', 'cohf-child' ),                 'caption' => __( 'Empowering youth and women to start and sustain small businesses for improved livelihoods and self-reliance.', 'cohf-child' ) ),
		array( 'key' => 'gallery-enterprise-visit-potatoes', 'cat' => 'enterprise', 'title' => __( 'Enterprise visit', 'cohf-child' ),                    'caption' => __( 'Empowering youth and women to start and sustain small businesses for improved livelihoods and self-reliance.', 'cohf-child' ) ),
		array( 'key' => 'gallery-enterprise-visit-eggs',     'cat' => 'enterprise', 'title' => __( 'Small business, real livelihood', 'cohf-child' ),     'caption' => __( 'Empowering youth and women to start and sustain small businesses for improved livelihoods and self-reliance.', 'cohf-child' ) ),
		array( 'key' => 'gallery-childrens-home-welcome',    'cat' => 'dignity',    'title' => __( 'A warm welcome', 'cohf-child' ),                      'caption' => __( 'Care and connection matter as much as supplies: a moment of joy during our children\'s home visit.', 'cohf-child' ) ),
		array( 'key' => 'gallery-door-to-door-girls',        'cat' => 'dignity',    'title' => __( 'Door to door with girls', 'cohf-child' ),             'caption' => __( 'Our team speaking with girls in the community during a door-to-door sanitary pad distribution.', 'cohf-child' ) ),
		array( 'key' => 'gallery-door-to-door-pads',         'cat' => 'dignity',    'title' => __( 'Dignity delivered', 'cohf-child' ),                   'caption' => __( 'Handing sanitary pads directly to a young woman at her home.', 'cohf-child' ) ),
		array( 'key' => 'gallery-sanitary-pads-stock',       'cat' => 'dignity',    'title' => __( 'Stocking up on dignity', 'cohf-child' ),              'caption' => __( 'Boxes of sanitary pads collected for our monthly distributions to girls.', 'cohf-child' ) ),
		array( 'key' => 'story-02-dignity-packs',            'cat' => 'dignity',    'title' => __( 'Fellowship with orphans', 'cohf-child' ),             'caption' => __( 'Children and our team together after a sanitary pad distribution.', 'cohf-child' ) ),
		array( 'key' => 'story-03-door-to-door',             'cat' => 'dignity',    'title' => __( 'Door to door', 'cohf-child' ),                        'caption' => __( 'Reaching girls in their own homes with packs of sanitary pads.', 'cohf-child' ) ),
		array( 'key' => 'story-01-women-seminar',            'cat' => 'women',      'title' => __( 'Discovering identity and potential', 'cohf-child' ), 'caption' => __( 'Women in discussion at a Foundation women empowerment seminar.', 'cohf-child' ) ),
		array( 'key' => 'hero-home',                         'cat' => 'education',  'title' => __( 'Home, in new uniforms', 'cohf-child' ),               'caption' => __( 'The three boys with our Executive Director and their grandmother outside the family home.', 'cohf-child' ) ),
		array( 'key' => 'gallery-home-visit-child',          'cat' => 'education',  'title' => __( 'A home visit', 'cohf-child' ),                        'caption' => __( 'Our team visiting a young boy and his family at home.', 'cohf-child' ) ),
		array( 'key' => 'story-04-back-to-school',           'cat' => 'education',  'title' => __( 'A family\'s new start', 'cohf-child' ),               'caption' => __( 'Three boys in school uniform standing with their family outside their home.', 'cohf-child' ) ),
	);

	/**
	 * Filter the gallery photographs.
	 *
	 * @param array $items Gallery items.
	 */
	$items = apply_filters( 'cohf_gallery_items', $items );

	// Only show photographs that actually exist.
	return array_values( array_filter( $items, function ( $item ) {
		return function_exists( 'cohf_img_url' ) && cohf_img_url( $item['key'] );
	} ) );
}

/**
 * Layout shape for a gallery photograph, from its real proportions.
 *
 * Wide photographs span two columns and tall ones two rows, so group shots
 * are not squeezed into slivers and portraits are not cropped at the head.
 *
 * @param string $key Image key.
 * @return string 'wide', 'tall' or 'square'.
 */
function cohf_gallery_shape( $key ) {
	$library = cohf_image_library();
	if ( empty( $library[ $key ]['file'] ) ) {
		return 'square';
	}
	$path = COHF_CHILD_DIR . '/assets/images/' . $library[ $key ]['file'];
	$size = file_exists( $path ) ? getimagesize( $path ) : false;
	if ( empty( $size[0] ) || empty( $size[1] ) ) {
		return 'square';
	}
	$ratio = $size[0] / $size[1];
	if ( $ratio >= 1.3 ) {
		return 'wide';
	}
	if ( $ratio <= 0.85 ) {
		return 'tall';
	}
	return 'square';
}

/**
 * Load the gallery script and styles on the gallery page only.
 */
function cohf_gallery_assets() {
	if ( ! is_page_template( 'page-templates/page-gallery.php' ) ) {
		return;
	}
	$dir = COHF_CHILD_DIR . '/assets/';
	$uri = COHF_CHILD_URI . '/assets/';

	if ( file_exists( $dir . 'css/gallery.css' ) ) {
		wp_enqueue_style( 'cohf-gallery', $uri . 'css/gallery.css', array( 'cohf-ux' ), (string) filemtime( $dir . 'css/gallery.css' ) );
	}
	if ( file_exists( $dir . 'js/gallery.js' ) ) {
		wp_enqueue_script( 'cohf-gallery', $uri . 'js/gallery.js', array(), (string) filemtime( $dir . 'js/gallery.js' ), array( 'strategy' => 'defer', 'in_footer' => true ) );
	}
}
add_action( 'wp_enqueue_scripts', 'cohf_gallery_assets', 30 );

/**
 * Create the Gallery page and add it to the menu on existing sites.
 *
 * Runs once per theme version in the admin. An existing page with the same
 * slug is reused, and a menu an editor has arranged only gains the link if
 * it is not there already.
 */
function cohf_gallery_ensure_page() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( get_option( 'cohf_gallery_page_version' ) === COHF_CHILD_VERSION ) {
		return;
	}

	$page = get_page_by_path( 'gallery', OBJECT, 'page' );
	if ( $page ) {
		$page_id = (int) $page->ID;
	} else {
		$page_id = (int) wp_insert_post( array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_title'  => __( 'Gallery', 'cohf-child' ),
			'post_name'   => 'gallery',
		) );
	}
	if ( ! $page_id ) {
		return;
	}
	update_post_meta( $page_id, '_wp_page_template', 'page-templates/page-gallery.php' );

	// The header's grouped dropdowns (About, Programmes, Impact, Explore) come
	// from the theme, and the theme only steps aside when the WordPress menu
	// has sub-items. Version 9.67.0 nested a Gallery item inside the
	// WordPress menu, which gave it a sub-item and switched the whole header
	// to that flat menu. Gallery now lives in the theme's Impact dropdown, so
	// any Gallery item the theme added to the WordPress menu is removed and
	// the designed header comes back.
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( empty( $locations['primary'] ) === false ) {
		foreach ( (array) wp_get_nav_menu_items( (int) $locations['primary'] ) as $item ) {
			if ( (int) $item->object_id === $page_id && 'page' === $item->object ) {
				wp_delete_post( (int) $item->ID, true );
			}
		}
	}

	update_option( 'cohf_gallery_page_version', COHF_CHILD_VERSION );
}
add_action( 'admin_init', 'cohf_gallery_ensure_page' );
