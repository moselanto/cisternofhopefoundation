<?php
/**
 * Short, fast, secure checkout (12.4.0).
 *
 * Short:  only the fields needed to deliver an order in Kenya. Company and
 *         "Apartment, suite" are hidden, postcode is gone (12.3.2), phone is
 *         required so the courier can call, and the country is fixed to
 *         Kenya. Guests can buy without creating an account.
 * Fast:   the cart drawer goes straight to checkout; the browser opens its
 *         connection to Paystack while the customer is still typing.
 * Secure: cart, checkout and order pages are never page-cached or stored by
 *         the browser, so one customer's details or a stale security token
 *         can never be served to someone else. Card details are entered on
 *         Paystack, never on this site. Order rate limits live in anti-spam.php.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'COHF_SHOP_KENYA_ONLY' ) ) {
	/* Set to false in wp-config.php to accept orders from other countries. */
	define( 'COHF_SHOP_KENYA_ONLY', true );
}

/* ---- Short: field settings that the block checkout reads ---- */
add_filter( 'pre_option_woocommerce_checkout_company_field', function () { return 'hidden'; } );
add_filter( 'pre_option_woocommerce_checkout_address_2_field', function () { return 'hidden'; } );
add_filter( 'pre_option_woocommerce_checkout_phone_field', function () { return 'required'; } );

/* Guests can check out; no account is forced on anyone. */
add_filter( 'pre_option_woocommerce_enable_guest_checkout', function () { return 'yes'; } );
add_filter( 'woocommerce_checkout_registration_required', '__return_false' );

/**
 * Classic checkout fallback: same short field set.
 *
 * @param array $fields Checkout fields.
 * @return array
 */
function cohf_checkout_short_fields( $fields ) {
	foreach ( array( 'billing', 'shipping' ) as $group ) {
		unset( $fields[ $group ][ $group . '_company' ], $fields[ $group ][ $group . '_address_2' ] );
	}
	if ( isset( $fields['billing']['billing_phone'] ) ) {
		$fields['billing']['billing_phone']['required'] = true;
	}
	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'cohf_checkout_short_fields', 98 );

/* ---- Kenya only: the country box disappears from the form ---- */
if ( COHF_SHOP_KENYA_ONLY ) {
	$cohf_ke_only = function () {
		return array( 'KE' => __( 'Kenya', 'woocommerce' ) );
	};
	add_filter( 'woocommerce_countries_allowed_countries', $cohf_ke_only, 99 );
	add_filter( 'woocommerce_countries_shipping_countries', $cohf_ke_only, 99 );
	add_filter( 'default_checkout_billing_country', function () { return 'KE'; } );
	add_filter( 'default_checkout_shipping_country', function () { return 'KE'; } );
}

/* ---- Secure: never cache cart, checkout or order pages ---- */
/**
 * Tell caching plugins and browsers to keep these pages private.
 */
function cohf_checkout_no_cache() {
	if ( ! function_exists( 'is_checkout' ) ) {
		return;
	}
	if ( is_cart() || is_checkout() || is_account_page() ) {
		if ( ! defined( 'DONOTCACHEPAGE' ) ) {
			define( 'DONOTCACHEPAGE', true ); // WP Super Cache, W3TC, WP Rocket, LiteSpeed.
		}
		if ( ! headers_sent() ) {
			header( 'Cache-Control: no-store, no-cache, must-revalidate, private, max-age=0' );
			header( 'X-LiteSpeed-Cache-Control: no-cache' );
		}
	}
}
add_action( 'template_redirect', 'cohf_checkout_no_cache', 0 );

/* ---- Fast: open the Paystack connection early on checkout ---- */
/**
 * Resource hints for the payment provider.
 *
 * @param array  $urls          URLs.
 * @param string $relation_type Hint type.
 * @return array
 */
function cohf_checkout_preconnect( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type && function_exists( 'is_checkout' ) && is_checkout() ) {
		$urls[] = array( 'href' => 'https://js.paystack.co', 'crossorigin' => 'anonymous' );
		$urls[] = array( 'href' => 'https://checkout.paystack.com', 'crossorigin' => 'anonymous' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'cohf_checkout_preconnect', 10, 2 );
