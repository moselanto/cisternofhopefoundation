<?php
/**
 * Hope Market - category structure and storefront navigation.
 *
 * - Defines the shop's top-level categories, their order, short descriptions
 *   and cover photos in one place.
 * - Runs a one-time, versioned sync on admin load: creates missing
 *   categories, fills empty descriptions, sets the display order, sets a
 *   cover image where none is chosen, and moves the jewellery pieces out of
 *   Accessories into their own Jewellery category. Anything an editor has
 *   already set (a description, a cover image) is left alone.
 * - Renders the "Shop by category" tiles on the shop landing page and a
 *   help strip on single product pages.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Categories in display order.
 *
 * cover: the seed product whose photo is used as the category image.
 *
 * @return array<string,array{name:string,description:string,cover:string}>
 */
function cohf_shop_category_map() {
	return array(
		'jewellery'        => array(
			'name'        => __( 'Jewellery', 'cohf-child' ),
			'description' => __( 'Maasai beaded bangles, wristbands, necklaces and earrings, strung by hand in bold colour.', 'cohf-child' ),
			'cover'       => 'maasai-bead-bangle-multicolour',
		),
		'bags-and-baskets' => array(
			'name'        => __( 'Bags and baskets', 'cohf-child' ),
			'description' => __( 'Woven sisal baskets, kiondo and Ankara bags, beaded clutches and everyday totes.', 'cohf-child' ),
			'cover'       => 'leather-sisal-kiondo-crossbody-bag',
		),
		'sandals'          => array(
			'name'        => __( 'Sandals', 'cohf-child' ),
			'description' => __( 'Leather sandals with hand-stitched beadwork, from flat Maasai styles to cushioned wedges.', 'cohf-child' ),
			'cover'       => 'beaded-leather-sandals',
		),
		'accessories'      => array(
			'name'        => __( 'Accessories', 'cohf-child' ),
			'description' => __( 'Beaded belts, fedoras, painted leather purses, Ankara fans, key chains and fridge magnets.', 'cohf-child' ),
			'cover'       => 'fedora-hat-beadwrap',
		),
		'home-and-kitchen' => array(
			'name'        => __( 'Home and kitchen', 'cohf-child' ),
			'description' => __( 'Carved ebony bowls, ceramic mugs and jugs, salad servers and woven coasters for the table.', 'cohf-child' ),
			'cover'       => 'ceramic-creamer-green',
		),
		'home-decor'       => array(
			'name'        => __( 'Home decor', 'cohf-child' ),
			'description' => __( 'Copper clocks, soapstone, carvings, masks and banana fibre art for walls and shelves.', 'cohf-child' ),
			'cover'       => 'copper-africa-wall-clock-large',
		),
		'flip-flop-art'    => array(
			'name'        => __( 'Flip-flop art', 'cohf-child' ),
			'description' => __( 'Colourful animal sculptures carved from layered flip-flop rubber.', 'cohf-child' ),
			'cover'       => 'flip-flop-lion-large',
		),
	);
}

/**
 * Seed products that belong in Jewellery. Kept here so existing shops can
 * be corrected without touching products an editor has re-categorised.
 *
 * @return string[]
 */
function cohf_shop_jewellery_keys() {
	return array(
		'maasai-bead-bangle-multicolour',
		'maasai-beaded-flag-wristband',
		'maasai-bead-bangle-clasp',
		'maasai-bead-long-necklace',
		'maasai-bead-earrings',
	);
}

/**
 * Find a product created by the seeder.
 *
 * @param string $key Seed key.
 * @return int Product ID or 0.
 */
function cohf_shop_product_by_seed( $key ) {
	$found = get_posts( array(
		'post_type'      => 'product',
		'post_status'    => 'any',
		'posts_per_page' => 1,
		'fields'         => 'ids',
		'meta_key'       => '_cohf_seed_key',
		'meta_value'     => $key,
		'no_found_rows'  => true,
	) );
	return empty( $found ) ? 0 : (int) $found[0];
}

/**
 * Get or create a category term by name.
 *
 * @param string $name Term name.
 * @return int Term ID or 0.
 */
function cohf_shop_ensure_term( $name ) {
	$term = term_exists( $name, 'product_cat' );
	if ( empty( $term ) ) {
		$term = wp_insert_term( $name, 'product_cat' );
	}
	if ( is_wp_error( $term ) || empty( $term ) ) {
		return 0;
	}
	return (int) ( is_array( $term ) ? $term['term_id'] : $term );
}

/**
 * One-time category sync. Bump COHF_SHOP_CATS_VERSION to run it again.
 */
const COHF_SHOP_CATS_VERSION = 1;

function cohf_shop_categories_sync() {
	if ( cohf_has_shop() === false || current_user_can( 'manage_woocommerce' ) === false ) {
		return;
	}
	if ( (int) get_option( 'cohf_shop_cats_version', 0 ) >= COHF_SHOP_CATS_VERSION ) {
		return;
	}
	// Wait until the seeder has created the products the covers point at.
	if ( cohf_shop_product_by_seed( 'beaded-leather-sandals' ) === 0 ) {
		return;
	}
	update_option( 'cohf_shop_cats_version', COHF_SHOP_CATS_VERSION, false );

	$order = 0;
	$ids   = array();
	foreach ( cohf_shop_category_map() as $slug => $cat ) {
		$term_id = cohf_shop_ensure_term( $cat['name'] );
		if ( 0 === $term_id ) {
			continue;
		}
		$ids[ $slug ] = $term_id;

		$term = get_term( $term_id, 'product_cat' );
		if ( $term && '' === trim( (string) $term->description ) ) {
			wp_update_term( $term_id, 'product_cat', array( 'description' => $cat['description'] ) );
		}

		update_term_meta( $term_id, 'order', $order );
		++$order;

		if ( empty( get_term_meta( $term_id, 'thumbnail_id', true ) ) ) {
			$product_id = cohf_shop_product_by_seed( $cat['cover'] );
			$thumb      = $product_id ? (int) get_post_thumbnail_id( $product_id ) : 0;
			if ( $thumb ) {
				update_term_meta( $term_id, 'thumbnail_id', $thumb );
			}
		}
	}

	// Move seeded jewellery from Accessories to Jewellery, but only where the
	// product is still in Accessories alone - a product an editor has moved is
	// left where they put it.
	if ( isset( $ids['jewellery'], $ids['accessories'] ) ) {
		foreach ( cohf_shop_jewellery_keys() as $key ) {
			$product_id = cohf_shop_product_by_seed( $key );
			if ( 0 === $product_id ) {
				continue;
			}
			$current = wp_get_object_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );
			if ( is_wp_error( $current ) ) {
				continue;
			}
			$current = array_map( 'intval', $current );
			if ( array( (int) $ids['accessories'] ) === $current ) {
				wp_set_object_terms( $product_id, array( (int) $ids['jewellery'] ), 'product_cat' );
			}
		}
	}

	if ( function_exists( 'wc_recount_after_stock_change' ) === false ) {
		foreach ( $ids as $term_id ) {
			clean_term_cache( $term_id, 'product_cat' );
		}
	}
	if ( function_exists( '_wc_term_recount' ) ) {
		$terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false ) );
		if ( is_array( $terms ) ) {
			_wc_term_recount( $terms, get_taxonomy( 'product_cat' ), true, false );
		}
	}
}
add_action( 'admin_init', 'cohf_shop_categories_sync', 40 );

/**
 * Top-level categories with products, in shop order.
 *
 * @return WP_Term[]
 */
function cohf_shop_visible_categories() {
	$terms = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'parent'     => 0,
		'exclude'    => array( (int) get_option( 'default_product_cat', 0 ) ),
		'menu_order' => 'ASC',
	) );
	return is_array( $terms ) ? $terms : array();
}

/**
 * "Shop by category" tiles on the shop landing page (first page, no sort or
 * search applied).
 */
function cohf_shop_category_tiles() {
	if ( is_shop() === false || is_paged() || is_search() || isset( $_GET['orderby'] ) || isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only.
		return;
	}
	$terms = cohf_shop_visible_categories();
	if ( count( $terms ) < 2 ) {
		return;
	}

	echo '<section class="shop-cat-tiles" aria-labelledby="shop-cat-tiles-title">';
	echo '<div class="shop-cat-tiles__head"><h2 id="shop-cat-tiles-title">' . esc_html__( 'Shop by category', 'cohf-child' ) . '</h2>';
	echo '<p>' . esc_html__( 'Handmade pieces, grouped so you can find the right one quickly.', 'cohf-child' ) . '</p></div>';
	echo '<ul class="shop-cat-tiles__grid">';
	foreach ( $terms as $term ) {
		$link = get_term_link( $term );
		if ( is_wp_error( $link ) ) {
			continue;
		}
		$thumb = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
		$img   = $thumb ? wp_get_attachment_image( $thumb, 'woocommerce_thumbnail', false, array( 'class' => 'shop-cat-tile__img', 'alt' => '', 'loading' => 'lazy' ) ) : '';
		printf(
			'<li><a class="shop-cat-tile" href="%1$s">%2$s<span class="shop-cat-tile__body"><span class="shop-cat-tile__name">%3$s</span><span class="shop-cat-tile__count">%4$s</span></span></a></li>',
			esc_url( $link ),
			$img ? $img : '<span class="shop-cat-tile__img shop-cat-tile__img--empty" aria-hidden="true"></span>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup.
			esc_html( $term->name ),
			esc_html( sprintf(
				/* translators: %s: number of products. */
				_n( '%s item', '%s items', (int) $term->count, 'cohf-child' ),
				number_format_i18n( (int) $term->count )
			) )
		);
	}
	echo '</ul></section>';
}
add_action( 'woocommerce_before_shop_loop', 'cohf_shop_category_tiles', 12 );

/**
 * Hero line on category pages: the category description, when there is one.
 *
 * @return string
 */
function cohf_shop_category_intro() {
	if ( function_exists( 'is_product_category' ) === false || is_product_category() === false ) {
		return '';
	}
	$term = get_queried_object();
	return ( $term && isset( $term->description ) ) ? wp_strip_all_tags( (string) $term->description ) : '';
}

/**
 * Reassurance strip under the add-to-cart button.
 */
function cohf_shop_product_help() {
	global $product;
	if ( empty( $product ) ) {
		return;
	}
	$org      = function_exists( 'cohf_org' ) ? cohf_org() : array();
	$whatsapp = isset( $org['whatsapp'] ) && '' !== $org['whatsapp'] ? $org['whatsapp'] : ( isset( $org['phone'] ) ? $org['phone'] : '' );
	$digits   = preg_replace( '/[^0-9]/', '', (string) $whatsapp );
	$wa_url   = $digits ? add_query_arg( 'text', rawurlencode( sprintf(
		/* translators: %s: product name. */
		__( 'Hello, I am interested in the %s from Hope Market.', 'cohf-child' ),
		$product->get_name()
	) ), 'https://wa.me/' . $digits ) : '';

	echo '<ul class="product-help">';
	echo '<li><span class="product-help__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 11c0 5.6-7 10-7 10Z"/></svg></span><span><strong>' . esc_html__( 'Handmade', 'cohf-child' ) . '</strong> ' . esc_html__( 'Every piece is made by hand, so each one is slightly unique.', 'cohf-child' ) . '</span></li>';
	echo '<li><span class="product-help__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg></span><span><strong>' . esc_html__( 'Secure checkout', 'cohf-child' ) . '</strong> ' . esc_html__( 'Purchases are receipted separately from donations.', 'cohf-child' ) . '</span></li>';
	if ( $wa_url ) {
		printf(
			'<li><span class="product-help__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 1-13.4 7.8L3 21l1.2-4.4A9 9 0 1 1 21 12Z"/></svg></span><span><strong>%1$s</strong> <a href="%2$s" target="_blank" rel="noopener noreferrer">%3$s</a></span></li>',
			esc_html__( 'Questions?', 'cohf-child' ),
			esc_url( $wa_url ),
			esc_html__( 'Ask about sizes, colours or delivery on WhatsApp', 'cohf-child' )
		);
	}
	echo '</ul>';
}
add_action( 'woocommerce_single_product_summary', 'cohf_shop_product_help', 35 );

/**
 * Show the category above the title on the single product page too.
 */
function cohf_shop_single_category() {
	if ( function_exists( 'cohf_shop_loop_category' ) ) {
		echo '<div class="product-cat-wrap">';
		cohf_shop_loop_category();
		echo '</div>';
	}
}
add_action( 'woocommerce_single_product_summary', 'cohf_shop_single_category', 4 );
