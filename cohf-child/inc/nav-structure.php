<?php
/**
 * Keeping the grouped navigation grouped.
 *
 * The theme ships a two-level primary navigation: About, Programmes and
 * Explore each open a dropdown. When a menu is assigned in Appearance >
 * Menus, that menu takes over rendering - which is correct, and is what
 * gives editors control.
 *
 * The problem this module solves: a menu assigned as a flat list of links
 * silently replaces the grouped structure with a flat row. Nothing warns
 * anyone, and the dropdowns simply disappear.
 *
 * Two changes address that:
 *
 * 1. A flat assigned menu no longer outranks the theme's grouped structure.
 *    Nobody builds a deliberately flat menu in order to lose their
 *    dropdowns, so a menu with no sub-items reads as "not yet structured"
 *    rather than as an instruction. As soon as a menu has one sub-item, it
 *    is clearly deliberate and it wins outright.
 *
 * 2. A one-click import writes the theme's grouped structure into a real
 *    WordPress menu, so editors get the dropdowns AND full control of them.
 *    It builds a new menu rather than rewriting the existing one, so
 *    nothing anyone has already built is destroyed.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Does the menu assigned to the primary location have any sub-items?
 *
 * @return bool
 */
function cohf_primary_menu_has_children() {
	static $cached = null;

	if ( null !== $cached ) {
		return $cached;
	}

	$cached    = false;
	$locations = get_nav_menu_locations();

	if ( empty( $locations['primary'] ) ) {
		return $cached;
	}

	$items = wp_get_nav_menu_items( $locations['primary'] );

	if ( empty( $items ) ) {
		return $cached;
	}

	foreach ( $items as $item ) {
		if ( ! empty( $item->menu_item_parent ) ) {
			$cached = true;
			break;
		}
	}

	return $cached;
}

/**
 * Flatten the theme's navigation into rows ready for menu insertion.
 *
 * @return array<int,array<string,mixed>>
 */
function cohf_nav_structure_rows() {
	$rows = array();

	foreach ( cohf_default_nav_items() as $item ) {
		$children = isset( $item['children'] ) ? $item['children'] : array();

		$rows[] = array(
			'label'    => $item['label'],
			// A parent that opens a panel has no page of its own; "#" keeps
			// it a valid custom link without pretending to be a destination.
			'url'      => ! empty( $item['url'] ) ? $item['url'] : '#',
			'desc'     => '',
			'children' => array(),
		);

		$last = count( $rows ) - 1;

		foreach ( $children as $child ) {
			if ( empty( $child['url'] ) ) {
				continue;
			}

			$rows[ $last ]['children'][] = array(
				'label' => $child['label'],
				'url'   => $child['url'],
				'desc'  => isset( $child['desc'] ) ? $child['desc'] : '',
			);
		}
	}

	return $rows;
}

/**
 * URL for the import action.
 *
 * @return string
 */
function cohf_nav_import_url() {
	return wp_nonce_url(
		admin_url( 'admin-post.php?action=cohf_import_nav' ),
		'cohf_import_nav'
	);
}

/**
 * Build a WordPress menu from the theme's grouped structure.
 *
 * Creates a new menu and assigns it to the primary location. Any existing
 * menu is left untouched in Appearance > Menus, so this is reversible by
 * reassigning the old one.
 */
function cohf_import_nav_handler() {
	check_admin_referer( 'cohf_import_nav' );

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to manage menus.', 'cohf-child' ) );
	}

	$name = __( 'Primary navigation (grouped)', 'cohf-child' );

	// A repeat import should not pile up identical menus.
	$existing = wp_get_nav_menu_object( $name );

	if ( $existing ) {
		$name .= ' ' . gmdate( 'Y-m-d H:i' );
	}

	$menu_id = wp_create_nav_menu( $name );

	if ( is_wp_error( $menu_id ) ) {
		wp_safe_redirect( add_query_arg( 'cohf_nav', 'failed', admin_url( 'nav-menus.php' ) ) );
		exit;
	}

	$count = 0;

	foreach ( cohf_nav_structure_rows() as $row ) {
		$parent_id = wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'  => $row['label'],
			'menu-item-url'    => $row['url'],
			'menu-item-type'   => 'custom',
			'menu-item-status' => 'publish',
		) );

		if ( is_wp_error( $parent_id ) ) {
			continue;
		}

		++$count;

		foreach ( $row['children'] as $child ) {
			$child_id = wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'       => $child['label'],
				'menu-item-url'         => $child['url'],
				'menu-item-description' => $child['desc'],
				'menu-item-type'        => 'custom',
				'menu-item-status'      => 'publish',
				'menu-item-parent-id'   => $parent_id,
			) );

			if ( ! is_wp_error( $child_id ) ) {
				++$count;
			}
		}
	}

	$locations             = get_nav_menu_locations();
	$locations['primary']  = (int) $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );

	// The theme's own list was only ever the fallback; an explicit import
	// means the WordPress menu should now be in charge.
	update_option( 'cohf_nav_source', 'wordpress' );

	wp_safe_redirect( add_query_arg(
		array(
			'cohf_nav'   => 'imported',
			'cohf_count' => $count,
			'menu'       => (int) $menu_id,
		),
		admin_url( 'nav-menus.php' )
	) );
	exit;
}
add_action( 'admin_post_cohf_import_nav', 'cohf_import_nav_handler' );

/**
 * Explain, on the menus screen, which navigation is actually rendering.
 *
 * Without this the theme's fallback looks like the menu editor being
 * broken, which is the confusion that started all of this.
 */
function cohf_nav_admin_notice() {
	$screen = get_current_screen();

	if ( ! $screen || 'nav-menus' !== $screen->id ) {
		return;
	}

	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only notices.
	if ( isset( $_GET['cohf_nav'] ) && 'imported' === $_GET['cohf_nav'] ) {
		$count = isset( $_GET['cohf_count'] ) ? absint( $_GET['cohf_count'] ) : 0;

		printf(
			'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
			esc_html( sprintf(
				/* translators: %d: number of menu items created. */
				__( 'Grouped navigation imported: %d items, with sub-items under About, Programmes and Explore. It is now assigned to the primary location and the dropdowns are live. Edit it below like any other menu. Your previous menu has not been deleted.', 'cohf-child' ),
				$count
			) )
		);

		return;
	}

	if ( isset( $_GET['cohf_nav'] ) && 'failed' === $_GET['cohf_nav'] ) {
		printf(
			'<div class="notice notice-error is-dismissible"><p>%s</p></div>',
			esc_html__( 'The grouped navigation could not be created. Please try again.', 'cohf-child' )
		);

		return;
	}
	// phpcs:enable WordPress.Security.NonceVerification.Recommended

	if ( ! has_nav_menu( 'primary' ) ) {
		return;
	}

	if ( cohf_primary_menu_has_children() ) {
		return;
	}

	// A flat menu is assigned, so the theme's grouped list is rendering.
	printf(
		'<div class="notice notice-warning"><p><strong>%1$s</strong> %2$s</p><p><a class="button button-primary" href="%3$s">%4$s</a></p></div>',
		esc_html__( 'Your assigned menu is a flat list, so the header is showing the theme\'s grouped navigation instead.', 'cohf-child' ),
		esc_html__( 'The designed header groups pages under About, Programmes and Explore as dropdowns. A flat menu would replace those with a single long row, so the theme keeps the grouped version until a menu with sub-items exists. Import the grouped structure to get the dropdowns as an ordinary menu you can edit. Your current menu is kept.', 'cohf-child' ),
		esc_url( cohf_nav_import_url() ),
		esc_html__( 'Import the grouped navigation', 'cohf-child' )
	);
}
add_action( 'admin_notices', 'cohf_nav_admin_notice' );
