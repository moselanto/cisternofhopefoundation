<?php
/**
 * Content structures: post types and taxonomies.
 *
 * Everything the Foundation publishes is a first-class content type with a
 * plain-English admin label, so staff never have to think in developer terms.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Build a standard labels array.
 *
 * @param string $singular Singular name.
 * @param string $plural   Plural name.
 * @return array
 */
function cohf_cpt_labels( $singular, $plural ) {
	return array(
		'name'                  => $plural,
		'singular_name'         => $singular,
		'menu_name'             => $plural,
		'add_new'               => __( 'Add New', 'cohf-child' ),
		/* translators: %s: singular content type name. */
		'add_new_item'          => sprintf( __( 'Add New %s', 'cohf-child' ), $singular ),
		'edit_item'             => sprintf( __( 'Edit %s', 'cohf-child' ), $singular ),
		'new_item'              => sprintf( __( 'New %s', 'cohf-child' ), $singular ),
		'view_item'             => sprintf( __( 'View %s', 'cohf-child' ), $singular ),
		'view_items'            => sprintf( __( 'View %s', 'cohf-child' ), $plural ),
		'search_items'          => sprintf( __( 'Search %s', 'cohf-child' ), $plural ),
		'not_found'             => sprintf( __( 'No %s yet', 'cohf-child' ), strtolower( $plural ) ),
		'not_found_in_trash'    => sprintf( __( 'No %s in the bin', 'cohf-child' ), strtolower( $plural ) ),
		'all_items'             => sprintf( __( 'All %s', 'cohf-child' ), $plural ),
		'archives'              => sprintf( __( '%s Archive', 'cohf-child' ), $singular ),
		'featured_image'        => __( 'Main image', 'cohf-child' ),
		'set_featured_image'    => __( 'Choose main image', 'cohf-child' ),
		'remove_featured_image' => __( 'Remove main image', 'cohf-child' ),
		'use_featured_image'    => __( 'Use as main image', 'cohf-child' ),
		'item_published'        => sprintf( __( '%s published.', 'cohf-child' ), $singular ),
		'item_updated'          => sprintf( __( '%s updated.', 'cohf-child' ), $singular ),
	);
}

/**
 * Register all Foundation post types.
 */
function cohf_register_post_types() {

	$types = array(

		'cohf_programme' => array(
			'labels'      => cohf_cpt_labels( __( 'Programme', 'cohf-child' ), __( 'Programmes', 'cohf-child' ) ),
			'description' => __( 'The Foundation\'s programme areas.', 'cohf-child' ),
			'menu_icon'   => 'dashicons-networking',
			'rewrite'     => array( 'slug' => 'programmes', 'with_front' => false ),
			'has_archive' => 'programmes',
			'supports'    => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'revisions', 'custom-fields' ),
			'menu_pos'    => 21,
		),

		'cohf_story' => array(
			'labels'      => cohf_cpt_labels( __( 'Impact Story', 'cohf-child' ), __( 'Impact Stories', 'cohf-child' ) ),
			'description' => __( 'Human stories from the Foundation\'s work, published with consent.', 'cohf-child' ),
			'menu_icon'   => 'dashicons-format-quote',
			'rewrite'     => array( 'slug' => 'stories', 'with_front' => false ),
			'has_archive' => 'stories',
			'supports'    => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'menu_pos'    => 22,
		),

		'cohf_video' => array(
			'labels'      => cohf_cpt_labels( __( 'Video Story', 'cohf-child' ), __( 'Video Stories', 'cohf-child' ) ),
			'description' => __( 'Video stories from the Foundation\'s work, published with consent.', 'cohf-child' ),
			'menu_icon'   => 'dashicons-video-alt3',
			'rewrite'     => array( 'slug' => 'videos', 'with_front' => false ),
			'has_archive' => 'videos',
			'supports'    => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'menu_pos'    => 22,
		),

		'cohf_news' => array(
			'labels'      => cohf_cpt_labels( __( 'News Item', 'cohf-child' ), __( 'News', 'cohf-child' ) ),
			'description' => __( 'Announcements and updates from the Foundation.', 'cohf-child' ),
			'menu_icon'   => 'dashicons-megaphone',
			'rewrite'     => array( 'slug' => 'news', 'with_front' => false ),
			'has_archive' => 'news',
			'supports'    => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'menu_pos'    => 23,
		),

		'cohf_event' => array(
			'labels'      => cohf_cpt_labels( __( 'Event', 'cohf-child' ), __( 'Events', 'cohf-child' ) ),
			'description' => __( 'Community programmes, forums and outreach activities.', 'cohf-child' ),
			'menu_icon'   => 'dashicons-calendar-alt',
			'rewrite'     => array( 'slug' => 'events', 'with_front' => false ),
			'has_archive' => 'events',
			'supports'    => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'menu_pos'    => 24,
		),

		'cohf_report' => array(
			'labels'      => cohf_cpt_labels( __( 'Report', 'cohf-child' ), __( 'Reports', 'cohf-child' ) ),
			'description' => __( 'Annual reports, programme reports and strategic documents.', 'cohf-child' ),
			'menu_icon'   => 'dashicons-analytics',
			'rewrite'     => array( 'slug' => 'reports', 'with_front' => false ),
			'has_archive' => 'reports',
			'supports'    => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'menu_pos'    => 25,
		),

		'cohf_resource' => array(
			'labels'      => cohf_cpt_labels( __( 'Resource', 'cohf-child' ), __( 'Resources', 'cohf-child' ) ),
			'description' => __( 'Policies, publications, media and downloadable documents.', 'cohf-child' ),
			'menu_icon'   => 'dashicons-media-document',
			'rewrite'     => array( 'slug' => 'resources', 'with_front' => false ),
			'has_archive' => 'resources',
			'supports'    => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
			'menu_pos'    => 26,
		),

		'cohf_leader' => array(
			'labels'      => cohf_cpt_labels( __( 'Leader', 'cohf-child' ), __( 'Leadership', 'cohf-child' ) ),
			'description' => __( 'Executive leadership, board and management team profiles.', 'cohf-child' ),
			'menu_icon'   => 'dashicons-groups',
			'rewrite'     => array( 'slug' => 'leadership', 'with_front' => false ),
			'has_archive' => false,
			'supports'    => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'revisions' ),
			'menu_pos'    => 27,
		),

		'cohf_partner' => array(
			'labels'      => cohf_cpt_labels( __( 'Partner', 'cohf-child' ), __( 'Partners', 'cohf-child' ) ),
			'description' => __( 'Confirmed partners only. Never add an organisation that has not agreed in writing.', 'cohf-child' ),
			'menu_icon'   => 'dashicons-admin-links',
			'rewrite'     => array( 'slug' => 'partners', 'with_front' => false ),
			'has_archive' => false,
			'supports'    => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'menu_pos'    => 28,
		),
	);

	foreach ( $types as $slug => $args ) {
		register_post_type( $slug, array(
			'labels'             => $args['labels'],
			'description'        => $args['description'],
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_in_rest'       => true, // Gutenberg + headless readiness.
			'menu_icon'          => $args['menu_icon'],
			'menu_position'      => $args['menu_pos'],
			'hierarchical'       => false,
			'has_archive'        => $args['has_archive'],
			'rewrite'            => $args['rewrite'],
			'capability_type'    => 'post',
			'supports'           => $args['supports'],
		) );
	}
}
add_action( 'init', 'cohf_register_post_types', 5 );

/**
 * Register shared taxonomies.
 */
function cohf_register_taxonomies() {

	$taxonomies = array(

		'cohf_programme_area' => array(
			'singular'   => __( 'Programme Area', 'cohf-child' ),
			'plural'     => __( 'Programme Areas', 'cohf-child' ),
			'post_types' => array( 'cohf_story', 'cohf_news', 'cohf_event', 'cohf_report', 'cohf_resource' ),
			'slug'       => 'programme-area',
			'hierarchical' => true,
		),

		'cohf_audience' => array(
			'singular'   => __( 'Audience', 'cohf-child' ),
			'plural'     => __( 'Audiences', 'cohf-child' ),
			'post_types' => array( 'cohf_programme', 'cohf_story', 'cohf_event' ),
			'slug'       => 'audience',
			'hierarchical' => true,
		),

		'cohf_location' => array(
			'singular'   => __( 'Location', 'cohf-child' ),
			'plural'     => __( 'Locations', 'cohf-child' ),
			'post_types' => array( 'cohf_story', 'cohf_event', 'cohf_programme', 'cohf_news' ),
			'slug'       => 'location',
			'hierarchical' => true,
		),

		'cohf_year' => array(
			'singular'   => __( 'Year', 'cohf-child' ),
			'plural'     => __( 'Years', 'cohf-child' ),
			'post_types' => array( 'cohf_story', 'cohf_news', 'cohf_event', 'cohf_report', 'cohf_resource' ),
			'slug'       => 'year',
			'hierarchical' => false,
		),

		'cohf_content_type' => array(
			'singular'   => __( 'Content Type', 'cohf-child' ),
			'plural'     => __( 'Content Types', 'cohf-child' ),
			'post_types' => array( 'cohf_resource', 'cohf_report' ),
			'slug'       => 'content-type',
			'hierarchical' => true,
		),

		'cohf_team_group' => array(
			'singular'   => __( 'Team Group', 'cohf-child' ),
			'plural'     => __( 'Team Groups', 'cohf-child' ),
			'post_types' => array( 'cohf_leader' ),
			'slug'       => 'team-group',
			'hierarchical' => true,
		),
	);

	foreach ( $taxonomies as $slug => $tax ) {
		register_taxonomy( $slug, $tax['post_types'], array(
			'labels'            => array(
				'name'          => $tax['plural'],
				'singular_name' => $tax['singular'],
				'search_items'  => sprintf( __( 'Search %s', 'cohf-child' ), $tax['plural'] ),
				'all_items'     => sprintf( __( 'All %s', 'cohf-child' ), $tax['plural'] ),
				'edit_item'     => sprintf( __( 'Edit %s', 'cohf-child' ), $tax['singular'] ),
				'add_new_item'  => sprintf( __( 'Add New %s', 'cohf-child' ), $tax['singular'] ),
				'menu_name'     => $tax['plural'],
			),
			'public'            => true,
			'hierarchical'      => $tax['hierarchical'],
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => $tax['slug'], 'with_front' => false ),
		) );
	}
}
add_action( 'init', 'cohf_register_taxonomies', 6 );

/**
 * Flush rewrite rules once after activation so the new URLs work immediately.
 */
function cohf_maybe_flush_rewrites() {
	if ( get_option( 'cohf_rewrites_version' ) === COHF_CHILD_VERSION ) {
		return;
	}
	cohf_register_post_types();
	cohf_register_taxonomies();
	flush_rewrite_rules( false );
	update_option( 'cohf_rewrites_version', COHF_CHILD_VERSION );
}
add_action( 'init', 'cohf_maybe_flush_rewrites', 20 );

/**
 * Order programmes and leadership by their manual menu order by default.
 *
 * @param WP_Query $query Query object.
 */
function cohf_default_ordering( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->is_post_type_archive( 'cohf_programme' ) ) {
		$query->set( 'orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
		$query->set( 'posts_per_page', 24 );
	}
	if ( $query->is_post_type_archive( array( 'cohf_story', 'cohf_news', 'cohf_video' ) ) ) {
		$query->set( 'posts_per_page', 9 );
	}
}
add_action( 'pre_get_posts', 'cohf_default_ordering' );
