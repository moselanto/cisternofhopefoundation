<?php
/**
 * Hope Market - WooCommerce integration.
 *
 * The shop is a social enterprise storefront for goods made by people in the
 * Foundation's enterprise programmes. It is deliberately separate from
 * donations: a purchase is a purchase and is receipted as one.
 *
 * This module only styles and frames WooCommerce. It never handles payment
 * itself - that is the Paystack WooCommerce gateway's job.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether WooCommerce is active.
 *
 * @return bool
 */
function cohf_has_shop() {
	return class_exists( 'WooCommerce' );
}

/**
 * The public name of the shop.
 *
 * @return string
 */
function cohf_shop_name() {
	return apply_filters( 'cohf_shop_name', __( 'Hope Market', 'cohf-child' ) );
}

/**
 * Shop URL, or an empty string when WooCommerce is not active.
 *
 * @return string
 */
function cohf_shop_url() {
	if ( cohf_has_shop() === false || function_exists( 'wc_get_page_permalink' ) === false ) {
		return '';
	}
	return (string) wc_get_page_permalink( 'shop' );
}

/**
 * Declare support. Without this WooCommerce renders its own unstyled shell.
 */
function cohf_shop_support() {
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 600,
		'single_image_width'    => 1200,
		'product_grid'          => array(
			'default_rows'    => 3,
			'min_rows'        => 1,
			'default_columns' => 3,
			'min_columns'     => 2,
			'max_columns'     => 4,
		),
	) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'cohf_shop_support' );

/* -------------------------------------------------------------------------
   Layout - swap WooCommerce's wrappers for the theme's own
   ------------------------------------------------------------------------- */

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

function cohf_shop_wrapper_open() {
	echo '<section class="shop-main"><div class="container">';
}
add_action( 'woocommerce_before_main_content', 'cohf_shop_wrapper_open', 10 );

function cohf_shop_wrapper_close() {
	echo '</div></section>';
}
add_action( 'woocommerce_after_main_content', 'cohf_shop_wrapper_close', 10 );

// The default sidebar does not belong in this layout.
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Three products per row, nine per page.
 *
 * @return int
 */
function cohf_shop_columns() {
	return 4;
}
add_filter( 'loop_shop_columns', 'cohf_shop_columns', 20 );

function cohf_shop_per_page() {
	return 24;
}
add_filter( 'loop_shop_per_page', 'cohf_shop_per_page', 20 );

/**
 * Brand hero above the shop archive, using the theme's page-hero part so the
 * storefront does not look bolted on.
 */
function cohf_shop_hero() {
	$is_cat = function_exists( 'is_product_category' ) && is_product_category();
	if ( ( is_shop() === false && $is_cat === false ) || is_search() ) {
		return;
	}
	get_template_part( 'template-parts/page-hero', null, array(
		'image'   => 'programme-08',
		'eyebrow' => $is_cat ? cohf_shop_name() : __( 'Social enterprise', 'cohf-child' ),
		'title'   => $is_cat ? single_term_title( '', false ) : cohf_shop_name(),
		'text'    => ( $is_cat && function_exists( 'cohf_shop_category_intro' ) && '' !== cohf_shop_category_intro() )
			? cohf_shop_category_intro()
			: __( 'Buy a craft. Support the mission. Every item is made by people in the Foundation\'s enterprise programmes, and every purchase strengthens the livelihood behind it.', 'cohf-child' ),
	) );
}
add_action( 'woocommerce_before_main_content', 'cohf_shop_hero', 5 );

/**
 * A short, honest note under the shop hero explaining where the money goes.
 */
function cohf_shop_intro() {
	if ( is_shop() === false || is_search() || is_paged() ) {
		return;
	}
	echo '<div class="shop-intro">';
	echo '<div class="sec-label"><span class="sec-label__rule"></span><span class="sec-label__text">' .
		esc_html__( 'How this works', 'cohf-child' ) . '</span></div>';
	echo '<p class="sec-lede">' . esc_html__(
		'Hope Market is part of the Foundation\'s enterprise work, not a separate business. Purchases are receipted as purchases and are kept separate from donations.',
		'cohf-child'
	) . '</p>';
	echo '</div>';
}
add_action( 'woocommerce_before_main_content', 'cohf_shop_intro', 15 );

/**
 * Replace the default "Shop" archive title with the Hope Market name.
 *
 * @param string $title Existing title.
 * @return string
 */
function cohf_shop_page_title( $title ) {
	if ( is_shop() ) {
		return cohf_shop_name();
	}
	return $title;
}
add_filter( 'woocommerce_page_title', 'cohf_shop_page_title' );

/**
 * The theme already prints its own hero, so suppress WooCommerce's duplicate
 * archive heading on the shop landing page.
 */
function cohf_shop_hide_default_title() {
	if ( is_shop() || ( function_exists( 'is_product_category' ) && is_product_category() ) ) {
		remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
		add_filter( 'woocommerce_show_page_title', '__return_false' );
	}
}
add_action( 'template_redirect', 'cohf_shop_hide_default_title' );

/**
 * Give the add-to-cart buttons the theme's button shape.
 *
 * @param string $html Button markup.
 * @return string
 */
function cohf_shop_button_class( $html ) {
	return str_replace( 'class="', 'class="btn dark ', $html );
}
add_filter( 'woocommerce_loop_add_to_cart_link', 'cohf_shop_button_class' );

/**
 * A storefront with nothing in it yet.
 *
 * WooCommerce's default is a single grey line - "No products were found
 * matching your selection." - which on an empty shop reads as a broken page
 * rather than one that has not opened yet. A visitor who arrived wanting to
 * support the makers should still leave with somewhere to go.
 */
remove_action( 'woocommerce_no_products_found', 'wc_no_products_found', 10 );

function cohf_shop_no_products() {
	if ( function_exists( 'cohf_search_no_results' ) && cohf_search_no_results() ) {
		return;
	}
	$support = function_exists( 'cohf_page_url' ) ? cohf_page_url( 'page-templates/page-support.php' ) : '';
	$contact = function_exists( 'cohf_page_url' ) ? cohf_page_url( 'page-templates/page-contact.php' ) : '';

	echo '<div class="shop-empty">';
	echo '<h2>' . esc_html__( 'Hope Market is being stocked.', 'cohf-child' ) . '</h2>';
	echo '<p>' . esc_html__(
		'The first crafts from our enterprise programmes are being photographed and listed. Until they are here, you can support the same makers directly.',
		'cohf-child'
	) . '</p>';

	if ( '' !== $support || '' !== $contact ) {
		echo '<div class="buttons">';
		if ( '' !== $support ) {
			printf(
				'<a class="btn cta" href="%1$s">%2$s</a>',
				esc_url( $support ),
				esc_html__( 'Support Our Work', 'cohf-child' )
			);
		}
		if ( '' !== $contact ) {
			printf(
				'<a class="btn outline" href="%1$s">%2$s</a>',
				esc_url( $contact ),
				esc_html__( 'Contact the Foundation', 'cohf-child' )
			);
		}
		echo '</div>';
	}

	echo '</div>';
}
add_action( 'woocommerce_no_products_found', 'cohf_shop_no_products', 10 );


/* -------------------------------------------------------------------------
   Product provenance
   "Buy a craft. Support the mission." is only credible if a buyer can see
   which programme and which maker a purchase supports. These fields are
   optional and are never invented - a product with nothing entered simply
   shows nothing.
   ------------------------------------------------------------------------- */

/**
 * Add maker and programme fields to the product data panel.
 */
function cohf_product_fields() {
	global $post;

	echo '<div class="options_group">';

	woocommerce_wp_text_input( array(
		'id'          => '_cohf_maker',
		'label'       => __( 'Maker or group', 'cohf-child' ),
		'description' => __( 'Who made this item. A first name or a group name is enough. Leave blank if the maker has not consented to being named.', 'cohf-child' ),
		'desc_tip'    => true,
	) );

	$programmes = get_posts( array(
		'post_type'        => 'cohf_programme',
		'post_status'      => 'publish',
		'numberposts'      => 20,
		'orderby'          => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		'suppress_filters' => false,
	) );

	$options = array( '' => __( 'No programme linked', 'cohf-child' ) );
	foreach ( $programmes as $programme ) {
		$options[ (string) $programme->ID ] = get_the_title( $programme );
	}

	woocommerce_wp_select( array(
		'id'          => '_cohf_programme',
		'label'       => __( 'Programme supported', 'cohf-child' ),
		'options'     => $options,
		'description' => __( 'Which enterprise programme this purchase strengthens.', 'cohf-child' ),
		'desc_tip'    => true,
	) );

	echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'cohf_product_fields' );

/**
 * Save the provenance fields.
 *
 * @param int $post_id Product ID.
 */
function cohf_product_fields_save( $post_id ) {
	if ( current_user_can( 'edit_post', $post_id ) === false ) {
		return;
	}

	$maker = isset( $_POST['_cohf_maker'] ) ? sanitize_text_field( wp_unslash( $_POST['_cohf_maker'] ) ) : '';
	update_post_meta( $post_id, '_cohf_maker', $maker );

	$programme = isset( $_POST['_cohf_programme'] ) ? absint( $_POST['_cohf_programme'] ) : 0;
	update_post_meta( $post_id, '_cohf_programme', $programme ? $programme : '' );
}
add_action( 'woocommerce_process_product_meta', 'cohf_product_fields_save' );

/**
 * Show provenance on the single product page, under the add-to-cart area.
 */
function cohf_product_provenance() {
	global $post;
	if ( empty( $post ) ) {
		return;
	}

	$maker     = (string) get_post_meta( $post->ID, '_cohf_maker', true );
	$programme = absint( get_post_meta( $post->ID, '_cohf_programme', true ) );

	if ( '' === $maker && 0 === $programme ) {
		return;
	}

	echo '<div class="prov">';
	echo '<div class="sec-label"><span class="sec-label__rule"></span><span class="sec-label__text">' .
		esc_html__( 'Behind this item', 'cohf-child' ) . '</span></div>';
	echo '<dl class="prov__list">';

	if ( '' !== $maker ) {
		echo '<div class="prov__row"><dt>' . esc_html__( 'Made by', 'cohf-child' ) . '</dt><dd>' . esc_html( $maker ) . '</dd></div>';
	}

	if ( $programme > 0 ) {
		printf(
			'<div class="prov__row"><dt>%1$s</dt><dd><a href="%2$s">%3$s</a></dd></div>',
			esc_html__( 'Supports', 'cohf-child' ),
			esc_url( (string) get_permalink( $programme ) ),
			esc_html( (string) get_the_title( $programme ) )
		);
	}

	echo '</dl>';
	echo '<p class="prov__note">' . esc_html__(
		'This is a purchase, not a donation, and is receipted as one. The proceeds strengthen the livelihood behind the item.',
		'cohf-child'
	) . '</p>';
	echo '</div>';
}
add_action( 'woocommerce_single_product_summary', 'cohf_product_provenance', 45 );


/* -------------------------------------------------------------------------
   Storefront presentation (9.85.0)
   ------------------------------------------------------------------------- */

/**
 * Storefront stylesheet, on WooCommerce pages only. Loads after the theme's
 * last layer and after WooCommerce's layout sheet so its grid rules win.
 */
function cohf_shop_styles() {
	if ( cohf_has_shop() === false ) {
		return;
	}
	if ( is_woocommerce() === false && is_cart() === false && is_checkout() === false && is_account_page() === false ) {
		return;
	}
	$path = COHF_CHILD_DIR . '/assets/css/shop.css';
	$deps = array( 'cohf-ux' );
	if ( wp_style_is( 'woocommerce-layout', 'registered' ) ) {
		$deps[] = 'woocommerce-layout';
	}
	wp_enqueue_style(
		'cohf-shop',
		COHF_CHILD_URI . '/assets/css/shop.css',
		$deps,
		file_exists( $path ) ? (string) filemtime( $path ) : COHF_CHILD_VERSION
	);

	// Arrow buttons and scroll bar for the sideways-scrolling category rows.
	if ( is_shop() || is_product_taxonomy() ) {
		$js = COHF_CHILD_DIR . '/assets/js/shop.js';
		wp_enqueue_script(
			'cohf-shop',
			COHF_CHILD_URI . '/assets/js/shop.js',
			array(),
			file_exists( $js ) ? (string) filemtime( $js ) : COHF_CHILD_VERSION,
			array( 'in_footer' => true, 'strategy' => 'defer' )
		);
	}
}
add_action( 'wp_enqueue_scripts', 'cohf_shop_styles', 40 );

/**
 * Whole shillings. Every price is a round KES figure, and "KSh 1,000.00"
 * reads as clutter on a small card.
 *
 * @return int
 */
function cohf_shop_price_decimals() {
	return 0;
}
add_filter( 'wc_get_price_decimals', 'cohf_shop_price_decimals' );

/**
 * The page hero already carries a breadcrumb, so drop WooCommerce's second
 * "Home / Shop" line above the grid.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/**
 * Hero breadcrumb read "Archives: Shop". Name the storefront instead.
 *
 * @param string $title Archive title.
 * @return string
 */
function cohf_shop_archive_title( $title ) {
	if ( function_exists( 'is_shop' ) && is_shop() ) {
		return cohf_shop_name();
	}
	if ( function_exists( 'is_product_category' ) && is_product_category() ) {
		return single_term_title( '', false );
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'cohf_shop_archive_title', 20 );

/**
 * Category filter chips and a single toolbar row for the count and sorting.
 */
function cohf_shop_toolbar_open() {
	if ( is_shop() === false && is_product_taxonomy() === false ) {
		return;
	}

	$terms = function_exists( 'cohf_shop_visible_categories' ) ? cohf_shop_visible_categories() : array();

	if ( count( $terms ) > 1 && ! is_shop() ) { // Shop page already has category tiles.
		$current = is_product_category() ? (int) get_queried_object_id() : 0;
		echo '<nav class="shop-cats-nav" aria-label="' . esc_attr__( 'Shop categories', 'cohf-child' ) . '"><ul class="shop-cats">';
		printf(
			'<li><a href="%1$s"%2$s>%3$s</a></li>',
			esc_url( cohf_shop_url() ),
			$current ? '' : ' aria-current="page"',
			esc_html__( 'All', 'cohf-child' )
		);
		foreach ( $terms as $term ) {
			$link = get_term_link( $term );
			if ( is_wp_error( $link ) ) {
				continue;
			}
			printf(
				'<li><a href="%1$s"%2$s>%3$s <span class="count">%4$s</span></a></li>',
				esc_url( $link ),
				$current === (int) $term->term_id ? ' aria-current="page"' : '',
				esc_html( $term->name ),
				esc_html( number_format_i18n( (int) $term->count ) )
			);
		}
		echo '</ul></nav>';
	}

	echo '<div class="shop-toolbar">';
}
add_action( 'woocommerce_before_shop_loop', 'cohf_shop_toolbar_open', 15 );

function cohf_shop_toolbar_close() {
	if ( is_shop() === false && is_product_taxonomy() === false ) {
		return;
	}
	echo '</div>';
}
add_action( 'woocommerce_before_shop_loop', 'cohf_shop_toolbar_close', 35 );

/**
 * Category label above each product title in the grid.
 */
function cohf_shop_loop_category() {
	global $product;
	if ( empty( $product ) ) {
		return;
	}
	$terms = get_the_terms( $product->get_id(), 'product_cat' );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return;
	}
	$default = (int) get_option( 'default_product_cat', 0 );
	foreach ( $terms as $term ) {
		if ( (int) $term->term_id !== $default ) {
			echo '<span class="product-cat">' . esc_html( $term->name ) . '</span>';
			return;
		}
	}
}
add_action( 'woocommerce_shop_loop_item_title', 'cohf_shop_loop_category', 5 );
