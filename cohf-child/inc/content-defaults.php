<?php
/**
 * One-click site setup.
 *
 * Creates the page structure, seeds the twelve programme areas and the
 * leadership profiles from the Foundation's own documents, and builds the
 * primary menu. Runs only when an administrator asks for it, and never
 * overwrites content that already exists.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Find a post of a given type by exact title.
 *
 * Replaces get_page_by_title(), which is deprecated from WordPress 6.2.
 *
 * @param string $title     Exact post title.
 * @param string $post_type Post type.
 * @return int Post ID, or 0 when not found.
 */
function cohf_find_by_title( $title, $post_type ) {
	$found = get_posts( array(
		'post_type'              => $post_type,
		'post_status'            => 'any',
		'posts_per_page'         => 1,
		'title'                  => $title,
		'fields'                 => 'ids',
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	) );

	if ( $found ) {
		return (int) $found[0];
	}

	// Exact matching fails when WordPress has stored the title with encoded
	// entities (an ampersand or a curly apostrophe), which is exactly what
	// happens to titles like "Women's Enterprise & Economic Empowerment".
	// Fall back to comparing normalised titles.
	$want = cohf_normalise_title( $title );
	$all  = get_posts( array(
		'post_type'              => $post_type,
		'post_status'            => 'any',
		'posts_per_page'         => 200,
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	) );
	foreach ( $all as $candidate ) {
		if ( cohf_normalise_title( $candidate->post_title ) === $want ) {
			return (int) $candidate->ID;
		}
	}

	return 0;
}

/**
 * Normalise a title for comparison: decode entities, flatten punctuation
 * and whitespace, lowercase.
 *
 * @param string $title Title.
 * @return string
 */
function cohf_normalise_title( $title ) {
	$t = html_entity_decode( (string) $title, ENT_QUOTES, 'UTF-8' );
	$t = str_replace(
		array( chr(226).chr(128).chr(153), chr(226).chr(128).chr(152), chr(226).chr(128).chr(147), chr(226).chr(128).chr(148) ),
		array( "'", "'", '-', '-' ),
		$t
	);
	$t = str_replace( '&', 'and', $t );
	$t = preg_replace( '/[^a-z0-9]+/', ' ', strtolower( $t ) );
	return trim( preg_replace( '/\s+/', ' ', $t ) );
}

/**
 * The page structure of the site.
 *
 * @return array<int,array<string,string>>
 */
function cohf_page_blueprint() {
	return array(
		array( 'title' => __( 'Home', 'cohf-child' ),                      'slug' => 'home',                      'tpl' => 'page-templates/page-home.php',           'nav' => __( 'Home', 'cohf-child' ) ),
		array( 'title' => __( 'About', 'cohf-child' ),                     'slug' => 'about',                     'tpl' => 'page-templates/page-about.php',          'nav' => __( 'About', 'cohf-child' ) ),
		array( 'title' => __( 'Leadership & Governance', 'cohf-child' ),   'slug' => 'leadership-governance',     'tpl' => 'page-templates/page-leadership.php',     'nav' => __( 'Leadership & Governance', 'cohf-child' ) ),
		array( 'title' => __( 'Our Programmes', 'cohf-child' ),            'slug' => 'programmes-overview',       'tpl' => 'page-templates/page-programmes.php',     'nav' => __( 'Our Programmes', 'cohf-child' ) ),
		array( 'title' => __( 'Our Impact', 'cohf-child' ),                'slug' => 'impact',                    'tpl' => 'page-templates/page-impact.php',         'nav' => __( 'Our Impact', 'cohf-child' ) ),
		array( 'title' => __( 'Gallery', 'cohf-child' ),                   'slug' => 'gallery',                   'tpl' => 'page-templates/page-gallery.php',        'nav' => '' ),
		array( 'title' => __( 'Our Approach', 'cohf-child' ),              'slug' => 'approach',                  'tpl' => 'page-templates/page-approach.php',       'nav' => __( 'Our Approach', 'cohf-child' ) ),
		array( 'title' => __( 'Get Involved', 'cohf-child' ),              'slug' => 'get-involved',              'tpl' => 'page-templates/page-get-involved.php',   'nav' => __( 'Get Involved', 'cohf-child' ) ),
		array( 'title' => __( 'Partners', 'cohf-child' ),                  'slug' => 'partners-overview',         'tpl' => 'page-templates/page-partners.php',       'nav' => __( 'Partners', 'cohf-child' ) ),
		array( 'title' => __( 'Resources', 'cohf-child' ),                 'slug' => 'resources-overview',        'tpl' => 'page-templates/page-resources.php',      'nav' => __( 'Resources', 'cohf-child' ) ),
		array( 'title' => __( 'Contact', 'cohf-child' ),                   'slug' => 'contact',                   'tpl' => 'page-templates/page-contact.php',        'nav' => __( 'Contact', 'cohf-child' ) ),
		array( 'title' => __( 'Accountability & Safeguarding', 'cohf-child' ), 'slug' => 'accountability',        'tpl' => 'page-templates/page-accountability.php', 'nav' => '' ),
		array( 'title' => __( 'Support Our Work', 'cohf-child' ),          'slug' => 'support-our-work',          'tpl' => 'page-templates/page-support.php',        'nav' => '' ),
		array( 'title' => __( 'Our Strategic Journey 2026–2030', 'cohf-child' ), 'slug' => 'strategic-journey',   'tpl' => 'page-templates/page-strategy.php',       'nav' => '' ),
	);
}

/**
 * Add the setup action to the Foundation dashboard.
 */
function cohf_setup_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$done = get_option( 'cohf_setup_complete' );
	if ( $done && version_compare( (string) $done, COHF_CHILD_VERSION, '>=' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->id, array( 'dashboard', 'toplevel_page_cohf-home', 'themes' ), true ) ) {
		return;
	}

	$url = wp_nonce_url( admin_url( 'admin-post.php?action=cohf_run_setup' ), 'cohf_run_setup' );
	?>
	<div class="notice notice-info">
		<p><strong><?php esc_html_e( 'Cistern of Hope Foundation theme', 'cohf-child' ); ?></strong></p>
		<p><?php esc_html_e( 'Set up the site structure: create the pages, add the twelve programme areas and the leadership profiles from the Foundation\'s documents, and build the main menu. Nothing existing is overwritten.', 'cohf-child' ); ?></p>
		<p><a class="button button-primary" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Run one-time setup', 'cohf-child' ); ?></a></p>
	</div>
	<?php
}
add_action( 'admin_notices', 'cohf_setup_notice' );

/**
 * Run the setup.
 */
function cohf_run_setup() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'cohf-child' ) );
	}
	check_admin_referer( 'cohf_run_setup' );

	$pages = array();

	foreach ( cohf_page_blueprint() as $blueprint ) {
		$existing = get_page_by_path( $blueprint['slug'] );
		if ( $existing ) {
			$pages[ $blueprint['slug'] ] = $existing->ID;
			continue;
		}
		$page_id = wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $blueprint['title'],
			'post_name'    => $blueprint['slug'],
			'post_content' => '',
		) );
		if ( $page_id && ! is_wp_error( $page_id ) ) {
			update_post_meta( $page_id, '_wp_page_template', $blueprint['tpl'] );
			$pages[ $blueprint['slug'] ] = $page_id;
		}
	}

	// Front page.
	if ( ! empty( $pages['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', (int) $pages['home'] );
	}

	// Programmes.
	foreach ( cohf_programme_seed() as $programme ) {
		if ( cohf_find_by_title( $programme['title'], 'cohf_programme' ) ) {
			continue;
		}
		$id = wp_insert_post( array(
			'post_type'   => 'cohf_programme',
			'post_status' => 'publish',
			'post_title'  => $programme['title'],
			'menu_order'  => (int) $programme['num'],
			'post_excerpt'=> $programme['purpose'],
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_cohf_number', $programme['num'] );
			update_post_meta( $id, '_cohf_purpose', $programme['purpose'] );

			// Import the matching bundled photograph and set it as the main
			// image, so a programme never appears with an empty image slot.
			$attachment_id = cohf_import_image( 'programme-' . $programme['num'] );
			if ( $attachment_id ) {
				set_post_thumbnail( $id, $attachment_id );
			}
		}
	}

	// Leadership.
	$order = 0;
	foreach ( cohf_leadership_seed() as $leader ) {
		$order += 10;
		if ( cohf_find_by_title( $leader['name'], 'cohf_leader' ) ) {
			continue;
		}
		$id = wp_insert_post( array(
			'post_type'   => 'cohf_leader',
			'post_status' => 'publish',
			'post_title'  => $leader['name'],
			'menu_order'  => $order,
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_cohf_role', $leader['role'] );
			update_post_meta( $id, '_cohf_group', $leader['group'] );
			if ( $leader['bio'] ) {
				update_post_meta( $id, '_cohf_short_bio', $leader['bio'] );
			}

			// Attach the supplied portrait. Leaders whose photograph has not
			// arrived yet fall back to the monogram tile, so the grid never
			// shows an empty slot.
			if ( empty( $leader['photo'] ) === false ) {
				$portrait_id = cohf_import_image( $leader['photo'] );
				if ( $portrait_id ) {
					set_post_thumbnail( $id, $portrait_id );
				}
			}
		}
	}

	// Impact stories supplied by the Foundation, with consent confirmed for
	// the photographs. Existing stories are never overwritten.
	if ( function_exists( 'cohf_seed_stories' ) ) {
		cohf_seed_stories();
	}

	// Make the whole bundled image set available in the Media Library so the
	// Foundation can swap any of it from the admin without editing files.
	foreach ( array_keys( cohf_image_library() ) as $image_key ) {
		cohf_import_image( $image_key );
	}

	cohf_build_menus( $pages );

	// Attach any newly bundled photographs to records that an earlier
	// version created, so upgrading brings in new imagery.
	cohf_sync_media();

	update_option( 'cohf_setup_complete', COHF_CHILD_VERSION );
	flush_rewrite_rules( false );

	wp_safe_redirect( admin_url( 'admin.php?page=cohf-home&cohf_setup=done' ) );
	exit;
}
add_action( 'admin_post_cohf_run_setup', 'cohf_run_setup' );

/**
 * Build the primary and footer menus.
 *
 * @param array<string,int> $pages Slug => page ID.
 */
function cohf_build_menus( $pages ) {
	$menu_name = __( 'Primary Navigation', 'cohf-child' );
	$menu      = wp_get_nav_menu_object( $menu_name );
	$menu_id   = $menu ? (int) $menu->term_id : (int) wp_create_nav_menu( $menu_name );

	if ( ! $menu_id || is_wp_error( $menu_id ) ) {
		return;
	}

	// Only populate an empty menu; never disturb one an editor has arranged.
	if ( wp_get_nav_menu_items( $menu_id ) ) {
		return;
	}

	foreach ( cohf_page_blueprint() as $blueprint ) {
		if ( empty( $blueprint['nav'] ) || empty( $pages[ $blueprint['slug'] ] ) ) {
			continue;
		}
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => $blueprint['nav'],
			'menu-item-object'    => 'page',
			'menu-item-object-id' => (int) $pages[ $blueprint['slug'] ],
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
		) );
	}

	$locations             = get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary']  = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Confirmation after setup.
 */
add_action( 'admin_notices', function () {
	if ( isset( $_GET['cohf_setup'] ) && 'done' === $_GET['cohf_setup'] ) {
		printf(
			'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
			esc_html__( 'Setup complete. Pages, programmes, leadership profiles and the main menu have been created. Add photography and publish.', 'cohf-child' )
		);
	}
} );

/**
 * Attach bundled photographs to records created by an earlier theme version.
 *
 * Only empty image slots are filled. Any image chosen in the admin is kept.
 *
 * @return int Number of records given a photograph.
 */
function cohf_sync_media( &$diag = null ) {
	$updated = 0;
	$diag    = array( 'leaders_found' => 0, 'leaders_total' => 0,
	                  'progs_found' => 0, 'progs_total' => 0, 'imports_failed' => 0 );

	foreach ( cohf_leadership_seed() as $leader ) {
		if ( empty( $leader['photo'] ) ) {
			continue;
		}
		$diag['leaders_total'] += 1;
		$post_id = cohf_find_by_title( $leader['name'], 'cohf_leader' );
		if ( empty( $post_id ) ) {
			continue;
		}
		$diag['leaders_found'] += 1;
		// Replace an image this theme supplied, but never one chosen in the
		// admin: a hand-picked photograph outranks anything we ship.
		$current = (int) get_post_thumbnail_id( $post_id );
		if ( $current ) {
			if ( get_post_meta( $current, '_cohf_image_key', true ) === '' ) {
				continue;
			}
		}
		$portrait_id = cohf_import_image( $leader['photo'] );
		if ( $portrait_id ) {
			if ( ( $portrait_id === $current ) === false ) {
				set_post_thumbnail( $post_id, $portrait_id );
				$updated += 1;
			}
		}
	}

	foreach ( cohf_programme_seed() as $programme ) {
		$diag['progs_total'] += 1;
		$post_id = cohf_find_by_title( $programme['title'], 'cohf_programme' );
		if ( empty( $post_id ) ) {
			continue;
		}
		$diag['progs_found'] += 1;
		$current = (int) get_post_thumbnail_id( $post_id );
		if ( $current ) {
			if ( get_post_meta( $current, '_cohf_image_key', true ) === '' ) {
				continue;
			}
		}
		$image_id = cohf_import_image( 'programme-' . $programme['num'] );
		if ( $image_id ) {
			if ( ( $image_id === $current ) === false ) {
				set_post_thumbnail( $post_id, $image_id );
				$updated += 1;
			}
		}
	}

	// Impact stories: move a story onto its current lead photograph when the
	// theme changes it, but never replace an image chosen in the admin.
	if ( function_exists( 'cohf_story_seed' ) ) {
		foreach ( cohf_story_seed() as $story ) {
			if ( empty( $story['image'] ) ) {
				continue;
			}
			$existing = get_page_by_path( $story['slug'], OBJECT, 'cohf_story' );
			if ( empty( $existing ) ) {
				continue;
			}
			$post_id = (int) $existing->ID;
			$current = (int) get_post_thumbnail_id( $post_id );
			if ( $current ) {
				if ( get_post_meta( $current, '_cohf_image_key', true ) === '' ) {
					continue;
				}
			}
			$image_id = cohf_import_image( $story['image'] );
			if ( $image_id ) {
				if ( ( $image_id === $current ) === false ) {
					set_post_thumbnail( $post_id, $image_id );
					$updated += 1;
				}
			}
		}
	}

	// Keep the whole bundled set available in the Media Library.
	foreach ( array_keys( cohf_image_library() ) as $image_key ) {
		cohf_import_image( $image_key );
	}

	return $updated;
}

/**
 * Refresh photographs on demand, without recreating any content.
 */
function cohf_run_sync_media() {
	if ( current_user_can( 'manage_options' ) === false ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'cohf-child' ) );
	}
	check_admin_referer( 'cohf_sync_media' );

	$diag    = array();
	$updated = cohf_sync_media( $diag );
	$links   = cohf_sync_menu();
	update_option( 'cohf_setup_complete', COHF_CHILD_VERSION );

	$dbg = sprintf( 'L%d/%d P%d/%d',
		(int) $diag['leaders_found'], (int) $diag['leaders_total'],
		(int) $diag['progs_found'], (int) $diag['progs_total'] );

	wp_safe_redirect( admin_url( 'admin.php?page=cohf-home&cohf_media=' . (int) $updated
		. '&cohf_links=' . (int) $links ) );
	exit;
}
add_action( 'admin_post_cohf_sync_media', 'cohf_run_sync_media' );

/**
 * Confirmation after a photograph refresh.
 */
add_action( 'admin_notices', function () {
	if ( isset( $_GET['cohf_media'] ) === false ) {
		return;
	}
	$count = (int) $_GET['cohf_media'];
	printf(
		'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
		esc_html(
			sprintf(
				/* translators: %d: number of records updated. */
				_n( '%d record updated with its photograph.', '%d records updated with their photographs.', $count, 'cohf-child' ),
				$count
			)
		)
	);

	$links = isset( $_GET['cohf_links'] ) ? (int) $_GET['cohf_links'] : 0;
	if ( $links ) {
		printf(
			'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
			esc_html(
				sprintf(
					/* translators: %d: number of menu links added. */
					_n( '%d link added to the main menu.', '%d links added to the main menu.', $links, 'cohf-child' ),
					$links
				)
			)
		);
	}
} );

/**
 * Add any missing blueprint links to the primary menu.
 *
 * Existing items are never removed. Only links the menu does not already
 * contain are added, then the blueprint order is reapplied.
 *
 * @return int Number of links added.
 */
function cohf_sync_menu() {
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$menu_id   = 0;
	if ( empty( $locations['primary'] ) === false ) {
		$menu_id = (int) $locations['primary'];
	}
	if ( empty( $menu_id ) ) {
		$menu = wp_get_nav_menu_object( __( 'Primary Navigation', 'cohf-child' ) );
		if ( $menu ) {
			$menu_id = (int) $menu->term_id;
		}
	}
	if ( empty( $menu_id ) ) {
		return 0;
	}

	$items = wp_get_nav_menu_items( $menu_id );
	if ( empty( $items ) ) {
		$items = array();
	}

	$have = array();
	foreach ( $items as $item ) {
		if ( 'post_type' === $item->type ) {
			$have[ (int) $item->object_id ] = true;
		}
	}

	$added    = 0;
	$position = array();
	$order    = 0;

	foreach ( cohf_page_blueprint() as $blueprint ) {
		if ( empty( $blueprint['nav'] ) ) {
			continue;
		}
		$page = get_page_by_path( $blueprint['slug'] );
		if ( empty( $page ) ) {
			continue;
		}
		$order += 1;
		$position[ (int) $page->ID ] = $order;

		if ( isset( $have[ (int) $page->ID ] ) ) {
			continue;
		}
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => $blueprint['nav'],
			'menu-item-object'    => 'page',
			'menu-item-object-id' => (int) $page->ID,
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-position'  => $order,
		) );
		$added += 1;
	}

	// Reapply the intended order so a new link does not land at the end.
	if ( $added ) {
		$fresh = wp_get_nav_menu_items( $menu_id );
		if ( empty( $fresh ) === false ) {
			foreach ( $fresh as $item ) {
				$oid = (int) $item->object_id;
				if ( 'post_type' === $item->type && isset( $position[ $oid ] ) ) {
					wp_update_nav_menu_item( $menu_id, (int) $item->ID, array(
						'menu-item-title'     => $item->title,
						'menu-item-object'    => $item->object,
						'menu-item-object-id' => $oid,
						'menu-item-type'      => $item->type,
						'menu-item-status'    => 'publish',
						'menu-item-parent-id' => (int) $item->menu_item_parent,
						'menu-item-position'  => $position[ $oid ],
					) );
				}
			}
		}
	}

	return $added;
}
