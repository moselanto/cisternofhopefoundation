<?php
/**
 * Server-side transaction initialisation.
 *
 * Why this file exists, and what it fixes.
 *
 * Until now the donation amount was decided in the browser: giving.js read
 * the chosen amount and handed it straight to Paystack Inline. Verification
 * then recorded whatever Paystack reported, so the Foundation could never be
 * short-changed - but there was no record of what the gift was *meant* to be,
 * so a manipulated amount was undetectable rather than impossible.
 *
 * The amount is now fixed on the server before checkout opens. The browser
 * asks this endpoint for a transaction; the endpoint decides the amount,
 * calls Paystack to initialise it, remembers what it authorised, and returns
 * only an access code. The browser never states a price again.
 *
 * On return, cohf_giving_verify() compares the amount Paystack actually took
 * against the amount this file authorised, and flags any mismatch rather than
 * silently accepting it.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Smallest and largest gift the form will initialise.
 *
 * The ceiling is not distrust of generosity: it is a guard against a typo or
 * a scripted request creating an absurd authorisation. A larger gift is very
 * welcome and is arranged with the Foundation directly.
 */
const COHF_GIVING_MIN = 50;
const COHF_GIVING_MAX = 1000000;

/**
 * Register the checkout endpoint.
 */
function cohf_giving_checkout_routes() {
	register_rest_route(
		'cohf/v1',
		'/initialize',
		array(
			'methods'             => 'POST',
			'callback'            => 'cohf_giving_initialize',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'cohf_giving_checkout_routes' );

/**
 * Initialise a Paystack transaction.
 *
 * @param WP_REST_Request $request The request.
 * @return WP_REST_Response
 */
function cohf_giving_initialize( $request ) {

	// A donation form is public, so this cannot require a login. The nonce
	// establishes that the request came from a page this site rendered
	// rather than from a script hitting the endpoint directly.
	$nonce = (string) $request->get_param( 'nonce' );

	if ( wp_verify_nonce( $nonce, 'cohf_giving' ) === false ) {
		return new WP_REST_Response(
			array( 'message' => __( 'This form has expired. Please reload the page and try again.', 'cohf-child' ) ),
			403
		);
	}

	if ( cohf_giving_can_verify() === false ) {
		return new WP_REST_Response(
			array( 'message' => __( 'Online giving is not fully configured yet. Please contact the Foundation directly.', 'cohf-child' ) ),
			503
		);
	}

	// A crude but effective brake on scripted abuse: five attempts per IP
	// per five minutes. A genuine donor never approaches this.
	$bucket = 'cohf_give_' . md5( (string) cohf_giving_client_ip() );
	$hits   = (int) get_transient( $bucket );

	if ( $hits >= 5 ) {
		return new WP_REST_Response(
			array( 'message' => __( 'Too many attempts. Please wait a few minutes and try again.', 'cohf-child' ) ),
			429
		);
	}

	set_transient( $bucket, $hits + 1, 5 * MINUTE_IN_SECONDS );

	/* ---- Validate, server-side, every value the browser sent ---- */

	$amount = absint( $request->get_param( 'amount' ) );
	$email  = sanitize_email( (string) $request->get_param( 'email' ) );
	$name   = sanitize_text_field( (string) $request->get_param( 'name' ) );
	$phone  = sanitize_text_field( (string) $request->get_param( 'phone' ) );
	$area   = sanitize_text_field( (string) $request->get_param( 'area' ) );
	$note   = sanitize_textarea_field( (string) $request->get_param( 'note' ) );
	$anon   = (bool) $request->get_param( 'anonymous' );

	if ( $amount < COHF_GIVING_MIN ) {
		return new WP_REST_Response(
			array(
				/* translators: %s: minimum amount with currency. */
				'message' => sprintf( __( 'The smallest gift this form can take is KES %s.', 'cohf-child' ), number_format_i18n( COHF_GIVING_MIN ) ),
			),
			400
		);
	}

	if ( $amount > COHF_GIVING_MAX ) {
		return new WP_REST_Response(
			array( 'message' => __( 'For a gift of this size, please contact the Foundation directly so we can receipt it properly.', 'cohf-child' ) ),
			400
		);
	}

	if ( is_email( $email ) === false ) {
		return new WP_REST_Response(
			array( 'message' => __( 'Please enter a valid email address for your receipt.', 'cohf-child' ) ),
			400
		);
	}

	$cfg = cohf_giving_config();

	// Our own reference, so the Foundation has a readable handle on every
	// gift rather than only Paystack's opaque string.
	$reference = cohf_giving_next_reference();

	$payload = array(
		'email'     => $email,
		'amount'    => $amount * 100, // Paystack works in the minor unit.
		'currency'  => $cfg['currency'],
		'reference' => $reference,
		/*
		 * Paystack redirects here itself once payment finishes. That matters:
		 * a JavaScript success callback only fires if the popup is still
		 * open and the tab still alive, whereas this is driven by Paystack's
		 * own server and survives a closed popup or a switched app - which
		 * is exactly what happens during an M-Pesa STK push.
		 */
		'callback_url' => add_query_arg( 'giving', 'thank-you', cohf_page_url( 'page-templates/page-support.php' ) ),
		'metadata'  => array(
			'custom_fields' => array(
				array(
					'display_name'  => 'Donor name',
					'variable_name' => 'donor_name',
					'value'         => $anon ? __( 'Anonymous', 'cohf-child' ) : $name,
				),
				array(
					'display_name'  => 'Donor phone',
					'variable_name' => 'donor_phone',
					'value'         => $phone,
				),
				array(
					'display_name'  => 'Support area',
					'variable_name' => 'support_area',
					'value'         => $area,
				),
				array(
					'display_name'  => 'Message',
					'variable_name' => 'donor_message',
					'value'         => $note,
				),
				array(
					'display_name'  => 'Anonymous',
					'variable_name' => 'anonymous',
					'value'         => $anon ? 'yes' : 'no',
				),
				array(
					'display_name'  => 'Source',
					'variable_name' => 'source',
					'value'         => 'Website - Support Our Work',
				),
			),
		),
	);

	$response = wp_remote_post(
		'https://api.paystack.co/transaction/initialize',
		array(
			'timeout' => 20,
			'headers' => array(
				'Authorization' => 'Bearer ' . cohf_paystack_secret(),
				'Content-Type'  => 'application/json',
				'Accept'        => 'application/json',
			),
			'body'    => wp_json_encode( $payload ),
		)
	);

	if ( is_wp_error( $response ) ) {
		return new WP_REST_Response(
			array( 'message' => __( 'We could not reach the payment provider. Please try again in a moment.', 'cohf-child' ) ),
			502
		);
	}

	$body = json_decode( (string) wp_remote_retrieve_body( $response ), true );

	if ( is_array( $body ) === false || empty( $body['data']['access_code'] ) ) {
		return new WP_REST_Response(
			array( 'message' => __( 'The payment provider could not start this transaction. Please try again or contact the Foundation.', 'cohf-child' ) ),
			502
		);
	}

	/*
	 * Remember what was authorised. This is the whole point of the file:
	 * verification later compares the amount actually taken against this,
	 * and the browser has no way to influence it. Two hours is far longer
	 * than any checkout and short enough not to accumulate.
	 */
	set_transient(
		'cohf_give_exp_' . $reference,
		array(
			'amount' => $amount,
			'email'  => $email,
			'name'   => $anon ? '' : $name,
			'anon'   => $anon,
			'note'   => $note,
		),
		2 * HOUR_IN_SECONDS
	);

	return new WP_REST_Response(
		array(
			'access_code' => (string) $body['data']['access_code'],
			'reference'   => $reference,
		),
		200
	);
}

/**
 * The next readable donation reference: COH-0001, COH-0002, and so on.
 *
 * Paystack accepts our reference and echoes it back, so the Foundation's own
 * numbering survives into the dashboard, the receipt and the bank narration.
 *
 * @return string
 */
function cohf_giving_next_reference() {
	$next = (int) get_option( 'cohf_donation_seq', 0 ) + 1;
	update_option( 'cohf_donation_seq', $next, false );

	$reference = sprintf( 'COH-%04d', $next );

	// A reference must be unique to Paystack. If this number has somehow
	// been used, walk forward rather than colliding.
	while ( cohf_donation_by_reference( $reference ) ) {
		++$next;
		update_option( 'cohf_donation_seq', $next, false );
		$reference = sprintf( 'COH-%04d', $next );
	}

	return $reference;
}

/**
 * The client IP, for rate limiting only.
 *
 * Deliberately conservative: a proxy header is only trusted when the site is
 * actually behind a proxy that sets it, so it cannot be spoofed to evade the
 * limit on a direct-served site.
 *
 * @return string
 */
function cohf_giving_client_ip() {
	$remote = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';

	if ( defined( 'COHF_BEHIND_PROXY' ) && COHF_BEHIND_PROXY && isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$forwarded = sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
		$parts     = explode( ',', $forwarded );
		$candidate = trim( $parts[0] );

		if ( filter_var( $candidate, FILTER_VALIDATE_IP ) ) {
			return $candidate;
		}
	}

	return $remote;
}

/**
 * What was authorised for a reference, if it is still known.
 *
 * @param string $reference Donation reference.
 * @return array|false
 */
function cohf_giving_expected( $reference ) {
	$expected = get_transient( 'cohf_give_exp_' . sanitize_text_field( $reference ) );

	return is_array( $expected ) ? $expected : false;
}
