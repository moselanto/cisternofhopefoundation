<?php
/**
 * Hope Market - slide-out cart, AJAX add to cart and Order on WhatsApp.
 *
 * - Adding to cart (shop grid or product page) no longer reloads the page.
 *   The item is added in the background and a cart panel slides in from the
 *   right with Checkout, Order on WhatsApp and Continue shopping.
 * - The panel lets shoppers change quantities and remove items in place.
 * - A cart icon with a live count sits in the header.
 * - "Order on WhatsApp" opens a chat with the Foundation's number and a
 *   ready-written order: items, quantities, prices and total. It goes
 *   through a small redirect on this site, so the message is always built
 *   from the live cart, even on cached pages.
 *
 * @package cohf-child
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
   Helpers
   ------------------------------------------------------------------------- */

/**
 * WhatsApp number as wa.me digits (country code, no plus or spaces).
 *
 * @return string
 */
function cohf_shop_wa_digits() {
	$org    = function_exists( 'cohf_org' ) ? cohf_org() : array();
	$source = isset( $org['whatsapp'] ) && '' !== $org['whatsapp'] ? $org['whatsapp'] : ( isset( $org['phone'] ) ? $org['phone'] : '' );
	$digits = preg_replace( '/[^0-9]/', '', (string) $source );
	// A local Kenyan number (07..., 01...) needs the 254 country code.
	if ( 10 === strlen( $digits ) && '0' === $digits[0] ) {
		$digits = '254' . substr( $digits, 1 );
	}
	return $digits;
}

/**
 * Plain-text shilling amount for WhatsApp messages.
 *
 * @param float $amount Amount.
 * @return string
 */
function cohf_shop_plain_price( $amount ) {
	return 'KSh ' . number_format( (float) $amount, 0, '.', ',' );
}

/**
 * Link that opens a WhatsApp order. Built on this site at click time.
 *
 * @param int $product_id Optional single product; 0 means the whole cart.
 * @return string
 */
function cohf_shop_wa_order_url( $product_id = 0 ) {
	$args = array( 'cohf-wa-order' => $product_id ? 'product' : 'cart' );
	if ( $product_id ) {
		$args['product_id'] = (int) $product_id;
		$args['qty']        = 1;
	}
	return add_query_arg( $args, home_url( '/' ) );
}

/**
 * WhatsApp order message for a list of lines.
 *
 * Laid out as a short, professional order form. WhatsApp renders *text* as
 * bold, so headings and the total stand out in the chat. Each order carries a
 * reference (HM-YYMMDD-XXXX) so the team can quote it when replying.
 *
 * @param array $lines Each: array( name, qty, unit, url ).
 * @return string
 */
function cohf_shop_wa_message( $lines, $details = array() ) {
	$ref    = ! empty( $details['ref'] ) ? $details['ref'] : 'HM-' . wp_date( 'ymd' ) . '-' . strtoupper( substr( wp_generate_password( 8, false, false ), 0, 4 ) );
	$pickup = isset( $details['mode'] ) && 'pickup' === $details['mode'];

	/*
	 * 14.7.2: one idea per line with a blank line between sections, so the
	 * order reads cleanly in WhatsApp. Bold markers (*...*) always open and
	 * close on the same line, otherwise WhatsApp shows the asterisks.
	 */
	$out   = array();
	$out[] = __( 'Hello Hope Market team,', 'cohf-child' );
	$out[] = __( 'I would like to place an order.', 'cohf-child' );
	$out[] = '';
	/* translators: %s: order reference. */
	$out[] = sprintf( __( '*Order: %s*', 'cohf-child' ), $ref );

	$total = 0;
	$count = 0;
	$n     = 0;
	foreach ( $lines as $line ) {
		++$n;
		$sub    = $line['unit'] * $line['qty'];
		$total += $sub;
		$count += $line['qty'];
		$out[]  = '';
		$out[]  = sprintf( '*%1$d. %2$s*', $n, $line['name'] );
		$out[]  = sprintf(
			/* translators: 1: quantity, 2: unit price, 3: line total. */
			__( 'Qty %1$d x %2$s = %3$s', 'cohf-child' ),
			$line['qty'],
			cohf_shop_plain_price( $line['unit'] ),
			cohf_shop_plain_price( $sub )
		);
		if ( '' !== $line['url'] ) {
			$out[] = $line['url'];
		}
	}

	$out[] = '';
	/* translators: 1: number of items, 2: total. */
	$out[] = sprintf( _n( '*Items total (%1$d item): %2$s*', '*Items total (%1$d items): %2$s*', $count, 'cohf-child' ), $count, cohf_shop_plain_price( $total ) );
	if ( ! $pickup ) {
		$out[] = __( 'Delivery fee: to be confirmed', 'cohf-child' );
	}
	$out[] = '';
	$out[] = $pickup ? __( '*Pickup details*', 'cohf-child' ) : __( '*Delivery details*', 'cohf-child' );

	$rows = array(
		'name'  => __( 'Name', 'cohf-child' ),
		'phone' => __( 'Phone', 'cohf-child' ),
		'area'  => $pickup ? __( 'Pickup', 'cohf-child' ) : __( 'Location', 'cohf-child' ),
		'date'  => $pickup ? __( 'Pickup date', 'cohf-child' ) : __( 'Delivery date', 'cohf-child' ),
		'pay'   => __( 'Payment', 'cohf-child' ),
	);
	foreach ( $rows as $key => $label ) {
		$val = isset( $details[ $key ] ) ? $details[ $key ] : '';
		if ( '' === $val && 'pay' === $key ) {
			$val = __( 'M-Pesa / Cash on delivery', 'cohf-child' );
		}
		if ( 'area' === $key && $pickup ) {
			$val = __( 'Our Kabete office', 'cohf-child' );
		}
		if ( '' === $val && 'date' === $key && ! empty( $details['name'] ) ) {
			$val = __( 'Any day', 'cohf-child' );
		}
		$out[] = $label . ': ' . $val;
	}
	if ( ! empty( $details['note'] ) ) {
		$out[] = '';
		$out[] = '*' . __( 'Note', 'cohf-child' ) . '*';
		$out[] = $details['note'];
	}

	$out[] = '';
	$out[] = $pickup ? __( 'Please confirm availability and when I can collect.', 'cohf-child' ) : __( 'Please confirm availability and the delivery fee.', 'cohf-child' );
	$out[] = __( 'Thank you.', 'cohf-child' );

	return implode( "\n", $out );
}

/**
 * Customer details passed from the "Order on WhatsApp" form (all optional).
 *
 * @return array
 */
function cohf_shop_wa_details() {
	$keys = array( 'name' => 60, 'phone' => 20, 'area' => 80, 'date' => 40, 'pay' => 30, 'note' => 300, 'mode' => 10, 'ref' => 20 );
	$out  = array();
	foreach ( $keys as $key => $max ) {
		$raw = isset( $_GET[ 'wa_' . $key ] ) ? sanitize_text_field( wp_unslash( $_GET[ 'wa_' . $key ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- builds a chat message only.
		$raw = trim( preg_replace( '/[*_~`]+/', '', $raw ) ); // No WhatsApp formatting tricks.
		$out[ $key ] = function_exists( 'mb_substr' ) ? mb_substr( $raw, 0, $max ) : substr( $raw, 0, $max );
	}
	if ( '' !== $out['phone'] ) {
		$out['phone'] = preg_replace( '/[^0-9+ ]/', '', $out['phone'] );
	}
	// 14.8.0: delivery or pickup, and the reference shown in the pop-up.
	$out['mode'] = 'pickup' === strtolower( $out['mode'] ) ? 'pickup' : 'delivery';
	$out['ref']  = preg_match( '/^HM-\d{6}-[A-Z0-9]{4}$/', strtoupper( $out['ref'] ) ) ? strtoupper( $out['ref'] ) : '';
	return $out;
}

/**
 * 14.8.0: the order lines for a single product or the whole cart. Shared by
 * the no-JavaScript redirect and the pop-up preview so both send the same.
 *
 * @param string $mode       'product' or 'cart'.
 * @param int    $product_id Product for 'product' mode.
 * @param int    $qty        Quantity for 'product' mode.
 * @return array<int,array{name:string,qty:int,unit:float,url:string,img:string}>
 */
function cohf_shop_wa_lines( $mode, $product_id = 0, $qty = 1 ) {
	$lines = array();
	$thumb = function ( $product ) {
		$id = $product->get_image_id();
		if ( ! $id && $product->get_parent_id() ) {
			$parent = wc_get_product( $product->get_parent_id() );
			$id     = $parent ? $parent->get_image_id() : 0;
		}
		return $id ? (string) wp_get_attachment_image_url( $id, 'woocommerce_gallery_thumbnail' ) : '';
	};

	if ( 'product' === $mode ) {
		$product = $product_id ? wc_get_product( $product_id ) : null;
		$qty     = max( 1, min( 99, (int) $qty ) );
		if ( $product && 'publish' === $product->get_status() ) {
			$lines[] = array(
				'name' => $product->get_name(),
				'qty'  => $qty,
				'unit' => (float) wc_get_price_to_display( $product ),
				'url'  => home_url( '/?p=' . $product->get_id() ),
				'img'  => $thumb( $product ),
			);
		}
		return $lines;
	}

	if ( function_exists( 'WC' ) && WC()->cart ) {
		foreach ( WC()->cart->get_cart() as $item ) {
			$product = isset( $item['data'] ) ? $item['data'] : null;
			if ( empty( $product ) ) {
				continue;
			}
			$lines[] = array(
				'name' => $product->get_name(),
				'qty'  => (int) $item['quantity'],
				'unit' => (float) wc_get_price_to_display( $product ),
				'url'  => $product->is_visible() ? home_url( '/?p=' . ( $product->get_parent_id() ? $product->get_parent_id() : $product->get_id() ) ) : '',
				'img'  => $thumb( $product ),
			);
		}
	}
	return $lines;
}

/**
 * 14.8.0: live order preview for the WhatsApp pop-up. Returns the items,
 * total, the exact message and the wa.me link, so the shopper sees what
 * will be sent and the link is ready the moment they tap Send.
 */
function cohf_shop_ajax_wa_preview() {
	nocache_headers();
	$digits = cohf_shop_wa_digits();
	$mode   = ( isset( $_GET['cohf-wa-order'] ) && 'product' === $_GET['cohf-wa-order'] ) ? 'product' : 'cart'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$lines  = cohf_shop_wa_lines(
		$mode,
		isset( $_GET['product_id'] ) ? absint( $_GET['product_id'] ) : 0, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		isset( $_GET['qty'] ) ? absint( $_GET['qty'] ) : 1 // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	);
	if ( '' === $digits || empty( $lines ) ) {
		wp_send_json_error( array( 'empty' => empty( $lines ) ) );
	}
	$total = 0;
	$count = 0;
	$items = array();
	foreach ( $lines as $line ) {
		$sub     = $line['unit'] * $line['qty'];
		$total  += $sub;
		$count  += $line['qty'];
		$items[] = array(
			'name' => $line['name'],
			'qty'  => $line['qty'],
			'unit' => cohf_shop_plain_price( $line['unit'] ),
			'sub'  => cohf_shop_plain_price( $sub ),
			'img'  => $line['img'],
		);
	}
	$message = cohf_shop_wa_message( $lines, cohf_shop_wa_details() );
	wp_send_json_success( array(
		'items'   => $items,
		'count'   => $count,
		'total'   => cohf_shop_plain_price( $total ),
		'message' => $message,
		'url'     => 'https://wa.me/' . $digits . '?text=' . rawurlencode( $message ),
	) );
}
add_action( 'wc_ajax_cohf_wa_preview', 'cohf_shop_ajax_wa_preview' );

/**
 * Redirect ?cohf-wa-order=cart|product to WhatsApp with the order written out.
 */
function cohf_shop_wa_order_redirect() {
	if ( empty( $_GET['cohf-wa-order'] ) || cohf_has_shop() === false ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only redirect.
		return;
	}
	nocache_headers();

	$digits = cohf_shop_wa_digits();
	$mode   = sanitize_key( wp_unslash( $_GET['cohf-wa-order'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$product_id = isset( $_GET['product_id'] ) ? absint( $_GET['product_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$qty        = isset( $_GET['qty'] ) ? absint( $_GET['qty'] ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$lines      = cohf_shop_wa_lines( $mode, $product_id, $qty );

	if ( '' === $digits ) {
		wp_safe_redirect( empty( $lines ) ? cohf_shop_url() : wc_get_cart_url() );
		exit;
	}

	$text = empty( $lines )
		? __( "Hello Hope Market team,\n\nI would like to place an order from your website. Kindly share the available items and payment details. Thank you.", 'cohf-child' )
		: cohf_shop_wa_message( $lines, cohf_shop_wa_details() );

	/*
	 * 14.7.2: wp_redirect() runs wp_sanitize_redirect(), which deletes every
	 * %0A to block header injection - and with it every line break in the
	 * order, so WhatsApp showed one run-on paragraph. The URL is built only
	 * from digits and rawurlencode() output (no raw CR or LF can be present),
	 * so the Location header is sent directly to keep the line breaks.
	 */
	$location = 'https://wa.me/' . $digits . '?text=' . rawurlencode( $text );
	if ( ! headers_sent() ) {
		nocache_headers();
		header( 'X-Redirect-By: COHF' );
		header( 'Location: ' . $location, true, 302 );
	}
	exit;
}
add_action( 'template_redirect', 'cohf_shop_wa_order_redirect', 1 );

/**
 * WhatsApp glyph.
 *
 * @return string
 */
function cohf_shop_wa_icon() {
	return '<svg class="wa-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21c5.46 0 9.91-4.45 9.91-9.91S17.5 2 12.04 2Zm0 18.15c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 4.54 0 8.24 3.7 8.24 8.24 0 4.55-3.7 8.24-8.24 8.24Zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.16.25-.64.81-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.13-.15.17-.25.25-.42.08-.16.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07s.89 2.4 1.01 2.56c.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.14-1.18-.06-.1-.22-.16-.47-.28Z"/></svg>';
}

/* -------------------------------------------------------------------------
   Header cart icon
   ------------------------------------------------------------------------- */

/**
 * Count bubble. Replaced live through cart fragments.
 *
 * @return string
 */
function cohf_shop_cart_count_html() {
	$count = ( function_exists( 'WC' ) && WC()->cart ) ? (int) WC()->cart->get_cart_contents_count() : 0;
	return sprintf(
		'<span class="header-cart__count%1$s" data-cart-count="%2$d">%2$d</span>',
		$count ? '' : ' is-empty',
		$count
	);
}

/**
 * Cart icon in the header. Opens the cart panel; plain link without JS.
 */
function cohf_shop_header_cart() {
	if ( cohf_has_shop() === false || function_exists( 'wc_get_cart_url' ) === false ) {
		return;
	}
	printf(
		'<a class="header-cart" href="%1$s" data-cart-open aria-label="%2$s"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 7h12l-1.2 11.2a2 2 0 0 1-2 1.8H9.2a2 2 0 0 1-2-1.8L6 7Z"/><path d="M9 7V6a3 3 0 0 1 6 0v1"/></svg>%3$s</a>',
		esc_url( wc_get_cart_url() ),
		esc_attr__( 'Your cart', 'cohf-child' ),
		cohf_shop_cart_count_html() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above with escaped values.
	);
}

/* -------------------------------------------------------------------------
   Cart panel
   ------------------------------------------------------------------------- */

/**
 * Inner part of the cart panel: items and footer. Replaced live through
 * cart fragments whenever the cart changes.
 *
 * @return string
 */
function cohf_shop_drawer_inner() {
	$cart = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart : null;

	ob_start();
	printf( '<div class="cart-drawer__inner" data-nonce="%s">', esc_attr( wp_create_nonce( 'cohf-cart' ) ) );

	if ( empty( $cart ) || $cart->is_empty() ) {
		echo '<div class="cart-drawer__empty">';
		echo '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 7h12l-1.2 11.2a2 2 0 0 1-2 1.8H9.2a2 2 0 0 1-2-1.8L6 7Z"/><path d="M9 7V6a3 3 0 0 1 6 0v1"/></svg>';
		echo '<p>' . esc_html__( 'Your cart is empty.', 'cohf-child' ) . '</p>';
		printf( '<a class="cart-btn cart-btn--primary" href="%s">%s</a>', esc_url( cohf_shop_url() ), esc_html__( 'Browse Hope Market', 'cohf-child' ) );
		echo '</div></div>';
		return ob_get_clean();
	}

	echo '<ul class="cart-drawer__items">';
	foreach ( $cart->get_cart() as $key => $item ) {
		$product = isset( $item['data'] ) ? $item['data'] : null;
		if ( empty( $product ) || $product->exists() === false || (int) $item['quantity'] < 1 ) {
			continue;
		}
		$qty  = (int) $item['quantity'];
		$max  = (int) $product->get_max_purchase_quantity();
		$link = $product->is_visible() ? $product->get_permalink( $item ) : '';
		$name = $product->get_name();

		echo '<li class="cart-item">';
		printf( '<a class="cart-item__img" href="%1$s" tabindex="-1" aria-hidden="true">%2$s</a>', esc_url( $link ? $link : '#' ), $product->get_image( 'woocommerce_thumbnail' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup.
		echo '<div class="cart-item__body">';
		printf( '<a class="cart-item__name" href="%1$s">%2$s</a>', esc_url( $link ? $link : '#' ), esc_html( $name ) );
		echo '<span class="cart-item__unit">' . wp_kses_post( wc_price( wc_get_price_to_display( $product ) ) ) . '</span>';
		echo '<div class="cart-item__row">';
		echo '<div class="cart-qty" role="group" aria-label="' . esc_attr( sprintf( /* translators: %s: product. */ __( 'Quantity of %s', 'cohf-child' ), $name ) ) . '">';
		printf( '<button type="button" class="cart-qty__btn" data-cart-qty data-key="%1$s" data-qty="%2$d" aria-label="%3$s">&minus;</button>', esc_attr( $key ), $qty - 1, esc_attr__( 'One fewer', 'cohf-child' ) );
		printf( '<span class="cart-qty__val" aria-live="polite">%d</span>', $qty ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- integer.
		printf( '<button type="button" class="cart-qty__btn" data-cart-qty data-key="%1$s" data-qty="%2$d" aria-label="%3$s"%4$s>+</button>', esc_attr( $key ), $qty + 1, esc_attr__( 'One more', 'cohf-child' ), ( $max > 0 && $qty >= $max ) ? ' disabled' : '' );
		echo '</div>';
		echo '<span class="cart-item__total">' . wp_kses_post( $cart->get_product_subtotal( $product, $qty ) ) . '</span>';
		echo '</div>';
		printf( '<button type="button" class="cart-item__remove" data-cart-qty data-key="%1$s" data-qty="0">%2$s</button>', esc_attr( $key ), esc_html__( 'Remove', 'cohf-child' ) );
		echo '</div></li>';
	}
	echo '</ul>';

	echo '<div class="cart-drawer__foot">';
	echo '<div class="cart-drawer__subtotal"><span>' . esc_html__( 'Subtotal', 'cohf-child' ) . '</span><strong>' . wp_kses_post( $cart->get_cart_subtotal() ) . '</strong></div>';
	echo '<p class="cart-drawer__note">' . esc_html__( 'Delivery is confirmed at checkout. Shop purchases are receipted separately from donations.', 'cohf-child' ) . '</p>';
	printf( '<a class="cart-btn cart-btn--primary" href="%1$s">%2$s</a>', esc_url( wc_get_checkout_url() ), esc_html__( 'Checkout', 'cohf-child' ) );
	if ( cohf_shop_wa_digits() ) {
		printf( '<a class="cart-btn cart-btn--wa" href="%1$s" target="_blank" rel="noopener nofollow">%2$s<span>%3$s</span></a>', esc_url( cohf_shop_wa_order_url() ), cohf_shop_wa_icon(), esc_html__( 'Order on WhatsApp', 'cohf-child' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG.
	}
	printf( '<button type="button" class="cart-btn cart-btn--ghost" data-cart-close>%s</button>', esc_html__( 'Continue shopping', 'cohf-child' ) );
	printf( '<a class="cart-drawer__viewcart" href="%1$s">%2$s</a>', esc_url( wc_get_cart_url() ), esc_html__( 'View full cart', 'cohf-child' ) );
	echo '</div></div>';

	return ob_get_clean();
}

/**
 * Panel shell, printed once per page.
 */
function cohf_shop_drawer() {
	if ( cohf_has_shop() === false || function_exists( 'WC' ) === false ) {
		return;
	}
	if ( is_cart() || is_checkout() ) {
		return;
	}
	?>
	<div class="cart-drawer" id="cohf-cart-drawer" aria-hidden="true">
		<div class="cart-drawer__overlay" data-cart-close></div>
		<div class="cart-drawer__panel" role="dialog" aria-modal="true" aria-labelledby="cohf-cart-title" tabindex="-1">
			<div class="cart-drawer__head">
				<h2 id="cohf-cart-title"><?php esc_html_e( 'Your cart', 'cohf-child' ); ?></h2>
				<button type="button" class="cart-drawer__close" data-cart-close aria-label="<?php esc_attr_e( 'Close cart', 'cohf-child' ); ?>">
					<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6 6 18"/></svg>
				</button>
			</div>
			<div class="cart-drawer__notice" role="status" aria-live="polite" hidden></div>
			<?php echo cohf_shop_drawer_inner(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside. ?>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'cohf_shop_drawer', 5 );

/**
 * Live pieces returned after every cart change.
 *
 * @param array $fragments Fragments.
 * @return array
 */
function cohf_shop_cart_fragments( $fragments ) {
	$fragments['div.cart-drawer__inner']   = cohf_shop_drawer_inner();
	$fragments['span.header-cart__count'] = cohf_shop_cart_count_html();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'cohf_shop_cart_fragments' );

/**
 * Change a quantity (0 removes) from the panel. Returns fresh fragments.
 */
function cohf_shop_ajax_cart_qty() {
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	if ( wp_verify_nonce( $nonce, 'cohf-cart' ) === false ) {
		wp_send_json_error( array( 'reason' => 'nonce' ), 403 );
	}
	$key = isset( $_POST['key'] ) ? wc_clean( wp_unslash( $_POST['key'] ) ) : '';
	$qty = isset( $_POST['qty'] ) ? absint( $_POST['qty'] ) : 0;

	if ( $key && WC()->cart && WC()->cart->get_cart_item( $key ) ) {
		if ( 0 === $qty ) {
			WC()->cart->remove_cart_item( $key );
		} else {
			WC()->cart->set_quantity( $key, min( $qty, 99 ), true );
		}
	}
	WC_AJAX::get_refreshed_fragments();
}
add_action( 'wc_ajax_cohf_cart_qty', 'cohf_shop_ajax_cart_qty' );

/**
 * Shop-grid buttons: take them away from WooCommerce's own AJAX script so
 * ours handles them (one request, then the panel). Only simple, purchasable
 * products; anything with options still goes to its product page.
 *
 * @param string     $html    Button markup.
 * @param WC_Product $product Product.
 * @return string
 */
function cohf_shop_loop_button_ajax( $html, $product ) {
	if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) {
		$html = str_replace( 'ajax_add_to_cart', 'js-cohf-add', $html );
		if ( false === strpos( $html, 'js-cohf-add' ) ) {
			$html = str_replace( 'class="', 'class="js-cohf-add ', $html );
		}
	}
	return $html;
}
add_filter( 'woocommerce_loop_add_to_cart_link', 'cohf_shop_loop_button_ajax', 20, 2 );

/* -------------------------------------------------------------------------
   Order on WhatsApp buttons
   ------------------------------------------------------------------------- */

/**
 * Product page: "Order on WhatsApp" under Add to cart. The script copies the
 * chosen quantity into the link before it opens.
 */
function cohf_shop_single_wa_button() {
	global $product;
	if ( empty( $product ) || cohf_shop_wa_digits() === '' || $product->is_purchasable() === false ) {
		return;
	}
	printf(
		'<a class="cart-btn cart-btn--wa single-wa" href="%1$s" target="_blank" rel="noopener nofollow" data-wa-product>%2$s<span>%3$s</span></a>',
		esc_url( cohf_shop_wa_order_url( $product->get_id() ) ),
		cohf_shop_wa_icon(), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG.
		esc_html__( 'Order on WhatsApp', 'cohf-child' )
	);
}
add_action( 'woocommerce_after_add_to_cart_form', 'cohf_shop_single_wa_button', 5 );

/**
 * Cart and checkout pages (block based): WhatsApp alternative below the block.
 *
 * @param string $content Block HTML.
 * @return string
 */
function cohf_shop_block_wa_option( $content ) {
	if ( cohf_shop_wa_digits() === '' ) {
		return $content;
	}
	$box = sprintf(
		'<div class="cart-wa-alt"><p>%1$s</p><a class="cart-btn cart-btn--wa" href="%2$s" target="_blank" rel="noopener nofollow">%3$s<span>%4$s</span></a></div>',
		esc_html__( 'Prefer to arrange payment and delivery by chat? Send your order to us on WhatsApp.', 'cohf-child' ),
		esc_url( cohf_shop_wa_order_url() ),
		cohf_shop_wa_icon(),
		esc_html__( 'Order on WhatsApp', 'cohf-child' )
	);
	// 14.4.0: the cart page had no way to pay online; give it a clear checkout button.
	if ( 'render_block_woocommerce/cart' === current_filter() && function_exists( 'WC' ) && WC()->cart && WC()->cart->get_cart_contents_count() > 0 ) {
		$box = sprintf(
			'<div class="cart-proceed"><a class="cart-btn cart-btn--primary" href="%1$s">%2$s</a></div>',
			esc_url( wc_get_checkout_url() ),
			esc_html__( 'Proceed to checkout', 'cohf-child' )
		) . $box;
	}
	return $content . $box;
}
add_filter( 'render_block_woocommerce/cart', 'cohf_shop_block_wa_option' );
add_filter( 'render_block_woocommerce/checkout', 'cohf_shop_block_wa_option' );

/* -------------------------------------------------------------------------
   Assets
   ------------------------------------------------------------------------- */

/**
 * Panel styles and script on every page (the header icon is site-wide).
 */
function cohf_shop_cart_assets() {
	if ( cohf_has_shop() === false || class_exists( 'WC_AJAX' ) === false ) {
		return;
	}
	$css = COHF_CHILD_DIR . '/assets/css/cart-drawer.css';
	$js  = COHF_CHILD_DIR . '/assets/js/cart-drawer.js';
	wp_enqueue_style( 'cohf-cart-drawer', COHF_CHILD_URI . '/assets/css/cart-drawer.css', array(), file_exists( $css ) ? (string) filemtime( $css ) : COHF_CHILD_VERSION );
	wp_enqueue_script( 'cohf-cart-drawer', COHF_CHILD_URI . '/assets/js/cart-drawer.js', array(), file_exists( $js ) ? (string) filemtime( $js ) : COHF_CHILD_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_localize_script( 'cohf-cart-drawer', 'cohfCart', array(
		'ajaxUrl'  => WC_AJAX::get_endpoint( '%%endpoint%%' ),
		'cartUrl'  => wc_get_cart_url(),
		/* translators: %s: product name. */
		'added'    => __( '%s added to your cart', 'cohf-child' ),
		'addedAny' => __( 'Added to your cart', 'cohf-child' ),
		'error'    => __( 'Sorry, that did not work. Please try again.', 'cohf-child' ),
		'office'   => __( 'Kabete, behind N Market', 'cohf-child' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'cohf_shop_cart_assets', 45 );

/* -------------------------------------------------------------------------
   Checkout: always show what is being bought
   ------------------------------------------------------------------------- */

/**
 * On phones the checkout block folds the order summary away behind a small
 * toggle, so shoppers cannot see their items. Print a clear "Your order"
 * list above the checkout form: photo, name, quantity and price of every
 * item, the total, and a link back to edit the cart.
 *
 * @param string $content Block HTML.
 * @return string
 */
function cohf_shop_checkout_items( $content ) {
	if ( function_exists( 'WC' ) === false || empty( WC()->cart ) || WC()->cart->is_empty() ) {
		return $content;
	}
	$cart  = WC()->cart;
	$count = (int) $cart->get_cart_contents_count();

	ob_start();
	echo '<section class="checkout-items" aria-labelledby="cohf-checkout-items-title">';
	echo '<div class="checkout-items__head">';
	printf(
		'<h2 id="cohf-checkout-items-title">%1$s <span>(%2$s)</span></h2>',
		esc_html__( 'Your order', 'cohf-child' ),
		esc_html( sprintf( /* translators: %d: number of items. */ _n( '%d item', '%d items', $count, 'cohf-child' ), $count ) )
	);
	printf( '<a href="%1$s">%2$s</a>', esc_url( wc_get_cart_url() ), esc_html__( 'Edit cart', 'cohf-child' ) );
	echo '</div><ul>';
	foreach ( $cart->get_cart() as $item ) {
		$product = isset( $item['data'] ) ? $item['data'] : null;
		if ( empty( $product ) ) {
			continue;
		}
		$qty = (int) $item['quantity'];
		echo '<li>';
		echo '<span class="checkout-items__img">' . $product->get_image( 'woocommerce_thumbnail' ) . '<b>' . (int) $qty . '</b></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup, integer.
		echo '<span class="checkout-items__name">' . esc_html( $product->get_name() ) . '<small>' . esc_html( sprintf( /* translators: 1: quantity, 2: unit price. */ __( '%1$d x %2$s', 'cohf-child' ), $qty, wp_strip_all_tags( wc_price( wc_get_price_to_display( $product ) ) ) ) ) . '</small></span>';
		echo '<span class="checkout-items__price">' . wp_kses_post( $cart->get_product_subtotal( $product, $qty ) ) . '</span>';
		echo '</li>';
	}
	echo '</ul>';
	echo '<div class="checkout-items__total"><span>' . esc_html__( 'Subtotal', 'cohf-child' ) . '</span><strong>' . wp_kses_post( $cart->get_cart_subtotal() ) . '</strong></div>';
	echo '</section>';

	return ob_get_clean() . $content;
}
add_filter( 'render_block_woocommerce/checkout', 'cohf_shop_checkout_items', 5 );

/* -------------------------------------------------------------------------
   Cart and checkout: progress steps and reassurance (12.3.0)
   ------------------------------------------------------------------------- */

/**
 * Three-step progress bar: Cart, Details and payment, Confirmation.
 *
 * @param int $current Active step (1-3).
 * @return string
 */
function cohf_shop_steps( $current ) {
	$steps = array(
		1 => array( __( 'Cart', 'cohf-child' ), function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '' ),
		2 => array( __( 'Details & payment', 'cohf-child' ), '' ),
		3 => array( __( 'Confirmation', 'cohf-child' ), '' ),
	);
	$out = '<nav class="checkout-steps" aria-label="' . esc_attr__( 'Checkout progress', 'cohf-child' ) . '"><ol>';
	foreach ( $steps as $n => $step ) {
		$state = $n < $current ? 'is-done' : ( $n === $current ? 'is-current' : '' );
		$label = '<span class="checkout-steps__num" aria-hidden="true">' . (int) $n . '</span><span class="checkout-steps__label">' . esc_html( $step[0] ) . '</span>';
		if ( $n < $current && '' !== $step[1] ) {
			$label = '<a href="' . esc_url( $step[1] ) . '">' . $label . '</a>';
		}
		$out .= '<li class="' . esc_attr( $state ) . '"' . ( $n === $current ? ' aria-current="step"' : '' ) . '>' . $label . '</li>';
	}
	return $out . '</ol></nav>';
}

/**
 * Steps above the cart block.
 *
 * @param string $content Block HTML.
 * @return string
 */
function cohf_shop_cart_steps( $content ) {
	return cohf_shop_steps( 1 ) . $content;
}
add_filter( 'render_block_woocommerce/cart', 'cohf_shop_cart_steps', 1 );

/**
 * Steps above the checkout block (runs last so it sits above "Your order").
 *
 * @param string $content Block HTML.
 * @return string
 */
function cohf_shop_checkout_steps( $content ) {
	return cohf_shop_steps( 2 ) . $content;
}
add_filter( 'render_block_woocommerce/checkout', 'cohf_shop_checkout_steps', 20 );

/**
 * Reassurance row under the checkout and cart blocks.
 *
 * @param string $content Block HTML.
 * @return string
 */
function cohf_shop_checkout_trust( $content ) {
	$items = array(
		array( '<rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>', __( 'Secure payment', 'cohf-child' ), __( 'Card payments are processed by Paystack.', 'cohf-child' ) ),
		array( '<path d="M12 21s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 11c0 5.6-7 10-7 10Z"/>', __( 'Handmade in Kenya', 'cohf-child' ), __( 'Each piece is made by hand.', 'cohf-child' ) ),
		array( '<path d="M21 12a9 9 0 0 1-13.4 7.8L3 21l1.2-4.4A9 9 0 1 1 21 12Z"/>', __( 'Here to help', 'cohf-child' ), __( 'Questions? Chat with us on WhatsApp.', 'cohf-child' ) ),
	);
	$out = '<ul class="checkout-trust">';
	foreach ( $items as $it ) {
		$out .= '<li><span class="checkout-trust__icon" aria-hidden="true"><svg viewBox="0 0 24 24">' . $it[0] . '</svg></span><span><strong>' . esc_html( $it[1] ) . '</strong><small>' . esc_html( $it[2] ) . '</small></span></li>';
	}
	return $content . $out . '</ul>';
}
add_filter( 'render_block_woocommerce/checkout', 'cohf_shop_checkout_trust', 30 );
add_filter( 'render_block_woocommerce/cart', 'cohf_shop_checkout_trust', 30 );

/* -------------------------------------------------------------------------
   No postcode at checkout (12.3.2)
   Kenyan deliveries are arranged by town and street, so the Postcode / ZIP
   field is hidden, never required and never validated, for every country.
   Works for both the block checkout and the classic checkout.
   ------------------------------------------------------------------------- */

/**
 * Default address fields: postcode optional and hidden.
 *
 * @param array $fields Address fields.
 * @return array
 */
function cohf_shop_no_postcode_default( $fields ) {
	if ( isset( $fields['postcode'] ) ) {
		$fields['postcode']['required'] = false;
		$fields['postcode']['hidden']   = true;
		$fields['postcode']['class']    = array( 'form-row-wide', 'cohf-hidden-postcode' );
	}
	return $fields;
}
add_filter( 'woocommerce_default_address_fields', 'cohf_shop_no_postcode_default', 99 );

/**
 * Country locales override the defaults, so hide the postcode there too.
 *
 * @param array $locale Country locale settings.
 * @return array
 */
function cohf_shop_no_postcode_locale( $locale ) {
	if ( ! isset( $locale['KE'] ) ) {
		$locale['KE'] = array();
	}
	foreach ( $locale as $country => $fields ) {
		$locale[ $country ]['postcode'] = array(
			'required' => false,
			'hidden'   => true,
		);
	}
	return $locale;
}
add_filter( 'woocommerce_get_country_locale', 'cohf_shop_no_postcode_locale', 99 );

/**
 * Never reject an order over the postcode format.
 *
 * @return bool
 */
function cohf_shop_postcode_always_valid() {
	return true;
}
add_filter( 'woocommerce_validate_postcode', 'cohf_shop_postcode_always_valid', 99 );

/**
 * Classic checkout fallback: drop the field entirely.
 *
 * @param array $fields Checkout fields.
 * @return array
 */
function cohf_shop_no_postcode_checkout( $fields ) {
	unset( $fields['billing']['billing_postcode'], $fields['shipping']['shipping_postcode'] );
	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'cohf_shop_no_postcode_checkout', 99 );
