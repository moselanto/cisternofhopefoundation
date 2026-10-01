<?php
/**
 * Hope Market - premium storefront layer (12.0.0).
 *
 * - Trust strip in place of the "How this works" paragraph.
 * - Product cards: studio-style image well (white photo backgrounds blend
 *   into one warm tone), second photo on hover, Sold out / Sale badges.
 * - Phones: sticky "Filter & sort" bar with a bottom sheet (sort, category,
 *   price) instead of three rows of chips and a dropdown.
 * - "Load more" with progress instead of page numbers (numbers remain for
 *   search engines and when JavaScript is off).
 * - A closing help band: can't find it? Ask on WhatsApp.
 *
 * @package cohf-child
 */

defined( 'ABSPATH' ) || exit;

/** Listing pages only. */
function cohf_premium_is_listing() {
	return function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() );
}

/* -------------------------------------------------------------------------
   Trust strip
   ------------------------------------------------------------------------- */

remove_action( 'woocommerce_before_main_content', 'cohf_shop_intro', 15 );

/**
 * Four reasons to buy, under the hero on the shop landing page.
 */
function cohf_premium_trust() {
	if ( is_shop() === false || is_search() || is_paged() ) {
		return;
	}
	$items = array(
		array( '<path d="M12 21s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 11c0 5.6-7 10-7 10Z"/>', __( 'Handmade in Kenya', 'cohf-child' ), __( 'Made by hand, so no two pieces are alike.', 'cohf-child' ) ),
		array( '<path d="M3 11l9-7 9 7"/><path d="M5 10v10h14V10"/><path d="M10 20v-6h4v6"/>', __( 'Supports our work', 'cohf-child' ), __( 'Part of the Foundation\'s enterprise programme.', 'cohf-child' ) ),
		array( '<path d="M21 12a9 9 0 0 1-13.4 7.8L3 21l1.2-4.4A9 9 0 1 1 21 12Z"/>', __( 'Order your way', 'cohf-child' ), __( 'Secure checkout, or order on WhatsApp.', 'cohf-child' ) ),
		array( '<rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>', __( 'Receipted separately', 'cohf-child' ), __( 'Purchases are kept apart from donations.', 'cohf-child' ) ),
	);
	echo '<ul class="shop-trust" aria-label="' . esc_attr__( 'Why shop Hope Market', 'cohf-child' ) . '">';
	foreach ( $items as $it ) {
		printf(
			'<li><span class="shop-trust__icon" aria-hidden="true"><svg viewBox="0 0 24 24">%1$s</svg></span><span><strong>%2$s</strong><small>%3$s</small></span></li>',
			$it[0], // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG.
			esc_html( $it[1] ),
			esc_html( $it[2] )
		);
	}
	echo '</ul>';
}
add_action( 'woocommerce_before_main_content', 'cohf_premium_trust', 15 );

/* -------------------------------------------------------------------------
   Product card image well
   ------------------------------------------------------------------------- */

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

/**
 * Main photo, second photo for hover, and status badges.
 */
function cohf_premium_card_media() {
	global $product;
	if ( empty( $product ) ) {
		return;
	}
	$main    = woocommerce_get_product_thumbnail( 'woocommerce_thumbnail' );
	$alt     = '';
	$gallery = $product->get_gallery_image_ids();
	if ( $gallery ) {
		$alt = wp_get_attachment_image( (int) $gallery[0], 'woocommerce_thumbnail', false, array( 'class' => 'product-media__alt', 'alt' => '', 'loading' => 'lazy', 'aria-hidden' => 'true' ) );
	}
	$badges = '';
	if ( $product->is_in_stock() === false ) {
		$badges .= '<span class="product-badge product-badge--sold">' . esc_html__( 'Sold out', 'cohf-child' ) . '</span>';
	} elseif ( $product->is_on_sale() ) {
		$badges .= '<span class="product-badge product-badge--sale">' . esc_html__( 'Sale', 'cohf-child' ) . '</span>';
	}
	if ( $gallery ) {
		/* translators: %d: number of photos. */
		$badges .= '<span class="product-badge product-badge--photos">' . esc_html( sprintf( _n( '%d photo', '%d photos', count( $gallery ) + 1, 'cohf-child' ), count( $gallery ) + 1 ) ) . '</span>';
	}
	echo '<span class="product-media' . ( $alt ? ' has-alt' : '' ) . '">' . $main . $alt . $badges . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup and escaped badges.
}
add_action( 'woocommerce_before_shop_loop_item_title', 'cohf_premium_card_media', 10 );

/* -------------------------------------------------------------------------
   Phones: sticky Filter & sort bar and bottom sheet
   ------------------------------------------------------------------------- */

/**
 * Bar and sheet markup.
 */
function cohf_premium_mobile_filters() {
	if ( cohf_premium_is_listing() === false ) {
		return;
	}
	global $wp_query;
	$base    = function_exists( 'cohf_search_base_url' ) ? cohf_search_base_url() : remove_query_arg( 'paged' );
	$min     = isset( $_GET['min_price'] ) ? absint( $_GET['min_price'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$max     = isset( $_GET['max_price'] ) ? absint( $_GET['max_price'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$orderby = isset( $_GET['orderby'] ) ? sanitize_key( wp_unslash( $_GET['orderby'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$total   = (int) $wp_query->found_posts;
	$active  = 0;
	if ( is_product_category() ) {
		++$active;
	}
	if ( '' !== $min || '' !== $max ) {
		++$active;
	}
	if ( '' !== $orderby && 'menu_order' !== $orderby && 'relevance' !== $orderby ) {
		++$active;
	}

	$sorts   = apply_filters( 'woocommerce_catalog_orderby', array( 'menu_order' => __( 'Featured', 'cohf-child' ) ) );
	$default = is_search() ? 'relevance' : 'menu_order';
	$current = '' !== $orderby ? $orderby : $default;
	$cur_cat = is_product_category() ? (int) get_queried_object_id() : 0;
	$q       = get_search_query();

	echo '<div class="shop-mbar">';
	printf(
		'<button type="button" class="shop-mbar__btn" data-sheet-open aria-controls="cohf-shop-sheet" aria-expanded="false"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M4 6h16M7 12h10M10 18h4"/></svg>%1$s%2$s</button>',
		esc_html__( 'Filter & sort', 'cohf-child' ),
		$active ? '<span class="shop-mbar__count">' . (int) $active . '</span>' : ''
	);
	/* translators: %s: number of products. */
	echo '<span class="shop-mbar__total">' . esc_html( sprintf( _n( '%s item', '%s items', $total, 'cohf-child' ), number_format_i18n( $total ) ) ) . '</span>';
	echo '</div>';

	echo '<div class="shop-sheet" id="cohf-shop-sheet" hidden>';
	echo '<div class="shop-sheet__overlay" data-sheet-close></div>';
	echo '<div class="shop-sheet__panel" role="dialog" aria-modal="true" aria-labelledby="cohf-shop-sheet-title" tabindex="-1">';
	echo '<div class="shop-sheet__grab" aria-hidden="true"></div>';
	echo '<div class="shop-sheet__head"><h2 id="cohf-shop-sheet-title">' . esc_html__( 'Filter & sort', 'cohf-child' ) . '</h2><button type="button" class="shop-sheet__close" data-sheet-close aria-label="' . esc_attr__( 'Close', 'cohf-child' ) . '"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6 6 18"/></svg></button></div>';
	echo '<div class="shop-sheet__body">';

	// Sort.
	echo '<section><h3>' . esc_html__( 'Sort by', 'cohf-child' ) . '</h3><ul class="shop-sheet__list">';
	foreach ( $sorts as $key => $label ) {
		$url = 'menu_order' === $key ? remove_query_arg( 'orderby', $base ) : add_query_arg( 'orderby', $key, $base );
		printf( '<li><a href="%1$s"%2$s>%3$s</a></li>', esc_url( $url ), $current === $key ? ' aria-current="true"' : '', esc_html( $label ) );
	}
	echo '</ul></section>';

	// Category.
	$cats = function_exists( 'cohf_shop_visible_categories' ) ? cohf_shop_visible_categories() : array();
	if ( $cats ) {
		echo '<section><h3>' . esc_html__( 'Category', 'cohf-child' ) . '</h3><ul class="shop-sheet__chips">';
		$keep = array_filter( array( 'min_price' => $min, 'max_price' => $max, 'orderby' => $orderby ) );
		printf( '<li><a href="%1$s"%2$s>%3$s</a></li>', esc_url( add_query_arg( $keep, cohf_shop_url() ) ), $cur_cat ? '' : ' aria-current="true"', esc_html__( 'All', 'cohf-child' ) );
		foreach ( $cats as $term ) {
			$link = get_term_link( $term );
			if ( is_wp_error( $link ) ) {
				continue;
			}
			printf( '<li><a href="%1$s"%2$s>%3$s <span>%4$d</span></a></li>', esc_url( add_query_arg( $keep, $link ) ), $cur_cat === (int) $term->term_id ? ' aria-current="true"' : '', esc_html( $term->name ), (int) $term->count );
		}
		echo '</ul></section>';
	}

	// Price.
	if ( function_exists( 'cohf_search_price_bands' ) ) {
		echo '<section><h3>' . esc_html__( 'Price', 'cohf-child' ) . '</h3><ul class="shop-sheet__chips">';
		printf( '<li><a href="%1$s"%2$s>%3$s</a></li>', esc_url( remove_query_arg( array( 'min_price', 'max_price' ), $base ) ), ( '' === $min && '' === $max ) ? ' aria-current="true"' : '', esc_html__( 'Any price', 'cohf-child' ) );
		foreach ( cohf_search_price_bands() as $band ) {
			$url = remove_query_arg( array( 'min_price', 'max_price' ), $base );
			if ( '' !== $band['min'] ) {
				$url = add_query_arg( 'min_price', $band['min'], $url );
			}
			if ( '' !== $band['max'] ) {
				$url = add_query_arg( 'max_price', $band['max'], $url );
			}
			$on = ( '' !== $min || '' !== $max ) && (string) $min === $band['min'] && (string) $max === $band['max'];
			printf( '<li><a href="%1$s"%2$s>%3$s</a></li>', esc_url( $url ), $on ? ' aria-current="true"' : '', esc_html( $band['label'] ) );
		}
		echo '</ul></section>';
	}

	echo '</div>';
	echo '<div class="shop-sheet__foot">';
	printf( '<a class="shop-sheet__clear" href="%1$s">%2$s</a>', esc_url( '' !== $q ? add_query_arg( array( 's' => rawurlencode( $q ), 'post_type' => 'product' ), home_url( '/' ) ) : cohf_shop_url() ), esc_html__( 'Clear all', 'cohf-child' ) );
	/* translators: %s: number of products. */
	printf( '<button type="button" class="shop-sheet__done" data-sheet-close>%s</button>', esc_html( sprintf( _n( 'Show %s item', 'Show %s items', $total, 'cohf-child' ), number_format_i18n( $total ) ) ) );
	echo '</div></div></div>';
}
add_action( 'woocommerce_before_shop_loop', 'cohf_premium_mobile_filters', 13 );

/* -------------------------------------------------------------------------
   Load more
   ------------------------------------------------------------------------- */

/**
 * "Load more" button with progress, above the page numbers.
 */
function cohf_premium_load_more() {
	if ( cohf_premium_is_listing() === false ) {
		return;
	}
	global $wp_query;
	$total = (int) $wp_query->found_posts;
	$pages = (int) $wp_query->max_num_pages;
	$paged = max( 1, (int) get_query_var( 'paged' ) );
	$per   = max( 1, (int) $wp_query->get( 'posts_per_page' ) );
	$shown = min( $total, $paged * $per );
	if ( $total < 1 ) {
		return;
	}
	echo '<div class="shop-loadmore" data-shop-loadmore data-per="' . (int) $per . '" data-total="' . (int) $total . '">';
	/* translators: 1: shown, 2: total. */
	echo '<p class="shop-loadmore__text" aria-live="polite">' . wp_kses_post( sprintf( __( 'You have seen <strong data-shown>%1$s</strong> of <strong>%2$s</strong> pieces', 'cohf-child' ), number_format_i18n( $shown ), number_format_i18n( $total ) ) ) . '</p>';
	echo '<div class="shop-loadmore__bar" aria-hidden="true"><span style="width:' . esc_attr( (string) round( $shown / $total * 100, 1 ) ) . '%"></span></div>';
	if ( $paged < $pages ) {
		printf( '<a class="shop-loadmore__btn" href="%1$s" data-next="%1$s">%2$s</a>', esc_url( get_pagenum_link( $paged + 1 ) ), esc_html__( 'Load more pieces', 'cohf-child' ) );
	}
	echo '</div>';
}
add_action( 'woocommerce_after_shop_loop', 'cohf_premium_load_more', 5 );

/* -------------------------------------------------------------------------
   Closing help band
   ------------------------------------------------------------------------- */

/**
 * Can't find it? Ask on WhatsApp or contact the Foundation.
 */
function cohf_premium_help_band() {
	if ( cohf_premium_is_listing() === false ) {
		return;
	}
	$digits  = function_exists( 'cohf_shop_wa_digits' ) ? cohf_shop_wa_digits() : '';
	$contact = function_exists( 'cohf_page_url' ) ? cohf_page_url( 'page-templates/page-contact.php' ) : '';
	echo '<section class="shop-help">';
	echo '<div class="shop-help__text"><h2>' . esc_html__( 'Looking for something particular?', 'cohf-child' ) . '</h2>';
	echo '<p>' . esc_html__( 'Ask about sizes, colours, gifts or larger orders. We reply quickly on WhatsApp.', 'cohf-child' ) . '</p></div>';
	echo '<div class="shop-help__actions">';
	if ( $digits ) {
		printf(
			'<a class="cart-btn cart-btn--wa" href="%1$s" target="_blank" rel="noopener nofollow">%2$s<span>%3$s</span></a>',
			esc_url( 'https://wa.me/' . $digits . '?text=' . rawurlencode( __( 'Hello Hope Market team, I have a question about your products.', 'cohf-child' ) ) ),
			function_exists( 'cohf_shop_wa_icon' ) ? cohf_shop_wa_icon() : '', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG.
			esc_html__( 'Chat on WhatsApp', 'cohf-child' )
		);
	}
	if ( $contact ) {
		printf( '<a class="shop-help__link" href="%1$s">%2$s</a>', esc_url( $contact ), esc_html__( 'Contact the Foundation', 'cohf-child' ) );
	}
	echo '</div></section>';
}
add_action( 'woocommerce_after_shop_loop', 'cohf_premium_help_band', 30 );
