<?php
/**
 * Donation verification, records and notifications.
 *
 * The browser is never trusted here. Paystack Inline calls its JavaScript
 * callback in the donor's own browser, so that callback proves nothing: the
 * thank-you URL can be typed by hand with any reference on it. Every claim
 * of payment in this file is therefore checked by asking Paystack directly,
 * server to server, and the amount that gets recorded is the amount Paystack
 * reports rather than the amount the page asked for.
 *
 * Two independent routes reach the same recorder, because each fails in a
 * different way:
 *
 *   1. The thank-you page verifies on arrival. Immediate, and gives the donor
 *      an answer, but only happens if the browser comes back.
 *   2. The webhook records the charge whether or not the donor ever returns.
 *      A closed tab, a dead battery or a cancelled redirect loses nothing.
 *
 * Both are idempotent and keyed on the Paystack reference, so a donation
 * handled twice is still one record.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * The Paystack secret key.
 *
 * wp-config.php is the preferred home, because a key in wp_options is a key
 * in a database backup. inc/giving.php deliberately warns against pasting a
 * secret key into WordPress, and that warning stands: the option below is a
 * fallback for sites where editing wp-config is not practical, not the
 * recommendation.
 *
 * Add to wp-config.php, above the "stop editing" line:
 *     define( 'COHF_PAYSTACK_SECRET_KEY', 'sk_live_...' );
 *
 * @return string
 */
function cohf_paystack_secret() {
	if ( defined( 'COHF_PAYSTACK_SECRET_KEY' ) ) {
		$constant = trim( (string) COHF_PAYSTACK_SECRET_KEY );
		if ( '' !== $constant ) {
			return $constant;
		}
	}

	return trim( (string) get_option( 'cohf_paystack_secret_key', '' ) );
}

/**
 * Whether a payment can actually be verified.
 *
 * @return bool
 */
function cohf_giving_can_verify() {
	return ( strpos( cohf_paystack_secret(), 'sk_' ) === 0 );
}

/* -------------------------------------------------------------------------
   The donation record
   ------------------------------------------------------------------------- */

/**
 * Register the donation record type.
 *
 * Not public and not queryable: a donation is never a web page. Creation
 * through the admin is disabled, because a donation record that nobody paid
 * for would corrupt the Foundation's own figures.
 */
function cohf_register_donation_type() {
	register_post_type(
		'cohf_donation',
		array(
			'labels'              => array(
				'name'          => __( 'Donations', 'cohf-child' ),
				'singular_name' => __( 'Donation', 'cohf-child' ),
				'menu_name'     => __( 'Donations', 'cohf-child' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => 'cohf-home',
			'show_in_rest'        => false,
			'menu_icon'           => 'dashicons-heart',
			'supports'            => array( 'title' ),
			'map_meta_cap'        => true,
			'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
		)
	);
}
add_action( 'init', 'cohf_register_donation_type' );

/**
 * Find an existing donation by its Paystack reference.
 *
 * @param string $reference Paystack transaction reference.
 * @return int Post ID, or 0.
 */
function cohf_donation_by_reference( $reference ) {
	$reference = sanitize_text_field( (string) $reference );

	if ( '' === $reference ) {
		return 0;
	}

	$found = get_posts(
		array(
			'post_type'              => 'cohf_donation',
			'post_status'            => 'any',
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'meta_key'               => '_cohf_reference',
			'meta_value'             => $reference,
		)
	);

	return $found ? (int) $found[0] : 0;
}

/**
 * Create or update a donation record from a verified Paystack transaction.
 *
 * Only ever called with data returned by Paystack, never with data posted by
 * a browser.
 *
 * @param array $txn The "data" object from a Paystack verify or webhook call.
 * @return array{id:int,created:bool}
 */
function cohf_record_donation( $txn ) {
	$reference = isset( $txn['reference'] ) ? sanitize_text_field( (string) $txn['reference'] ) : '';

	if ( '' === $reference ) {
		return array(
			'id'      => 0,
			'created' => false,
		);
	}

	// Paystack works in the minor unit; KES 1,000 arrives as 100000.
	$minor    = isset( $txn['amount'] ) ? absint( $txn['amount'] ) : 0;
	$amount   = $minor / 100;
	$currency = isset( $txn['currency'] ) ? sanitize_text_field( (string) $txn['currency'] ) : 'KES';
	$status   = isset( $txn['status'] ) ? sanitize_text_field( (string) $txn['status'] ) : 'unknown';
	$channel  = isset( $txn['channel'] ) ? sanitize_text_field( (string) $txn['channel'] ) : '';
	$paid_at  = isset( $txn['paid_at'] ) ? sanitize_text_field( (string) $txn['paid_at'] ) : '';

	$email = '';
	if ( isset( $txn['customer']['email'] ) ) {
		$email = sanitize_email( (string) $txn['customer']['email'] );
	}

	// The donor's name, phone and chosen area travel as Paystack custom
	// fields, set by assets/js/giving.js.
	$name  = '';
	$phone = '';
	$area  = '';

	if ( isset( $txn['metadata']['custom_fields'] ) && is_array( $txn['metadata']['custom_fields'] ) ) {
		foreach ( $txn['metadata']['custom_fields'] as $field ) {
			$var = isset( $field['variable_name'] ) ? (string) $field['variable_name'] : '';
			$val = isset( $field['value'] ) ? sanitize_text_field( (string) $field['value'] ) : '';

			if ( 'donor_name' === $var ) {
				$name = $val;
			} elseif ( 'donor_phone' === $var ) {
				$phone = $val;
			} elseif ( 'support_area' === $var ) {
				$area = $val;
			}
		}
	}

	$existing = cohf_donation_by_reference( $reference );
	$created  = false;

	if ( $existing ) {
		$donation_id = $existing;
	} else {
		$donation_id = wp_insert_post(
			array(
				'post_type'   => 'cohf_donation',
				'post_status' => 'private',
				'post_title'  => $reference,
			)
		);

		if ( is_wp_error( $donation_id ) || 0 === (int) $donation_id ) {
			return array(
				'id'      => 0,
				'created' => false,
			);
		}

		$created = true;
	}

	$donation_id = (int) $donation_id;

	update_post_meta( $donation_id, '_cohf_reference', $reference );
	update_post_meta( $donation_id, '_cohf_amount', $amount );
	update_post_meta( $donation_id, '_cohf_currency', $currency );
	update_post_meta( $donation_id, '_cohf_status', $status );
	update_post_meta( $donation_id, '_cohf_donor_name', $name );
	update_post_meta( $donation_id, '_cohf_donor_email', $email );
	update_post_meta( $donation_id, '_cohf_donor_phone', $phone );
	update_post_meta( $donation_id, '_cohf_area', $area );
	update_post_meta( $donation_id, '_cohf_channel', $channel );
	update_post_meta( $donation_id, '_cohf_paid_at', $paid_at );

	return array(
		'id'      => $donation_id,
		'created' => $created,
	);
}

/* -------------------------------------------------------------------------
   Verification
   ------------------------------------------------------------------------- */

/**
 * Ask Paystack whether a reference was really paid.
 *
 * @param string $reference Paystack transaction reference.
 * @return array{ok:bool,status:string,message:string,donation_id:int,amount:float,currency:string,name:string}
 */
function cohf_giving_verify( $reference ) {
	$out = array(
		'ok'          => false,
		'status'      => 'unverified',
		'message'     => '',
		'donation_id' => 0,
		'amount'      => 0,
		'currency'    => 'KES',
		'name'        => '',
	);

	$reference = sanitize_text_field( (string) $reference );

	if ( '' === $reference ) {
		$out['message'] = __( 'No payment reference was supplied.', 'cohf-child' );
		return $out;
	}

	if ( cohf_giving_can_verify() === false ) {
		// The gift may well be genuine; we simply cannot confirm it here.
		$out['status']  = 'unconfirmed';
		$out['message'] = __( 'This gift cannot be confirmed automatically yet. The Foundation will reconcile it directly.', 'cohf-child' );
		return $out;
	}

	$response = wp_remote_get(
		'https://api.paystack.co/transaction/verify/' . rawurlencode( $reference ),
		array(
			'timeout' => 20,
			'headers' => array(
				'Authorization' => 'Bearer ' . cohf_paystack_secret(),
				'Accept'        => 'application/json',
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		$out['status']  = 'unconfirmed';
		$out['message'] = __( 'We could not reach the payment provider to confirm this gift. If it was taken from your account, it is safe and the Foundation will confirm it.', 'cohf-child' );
		return $out;
	}

	$body = json_decode( (string) wp_remote_retrieve_body( $response ), true );

	if ( is_array( $body ) === false || isset( $body['data'] ) === false ) {
		$out['status']  = 'unconfirmed';
		$out['message'] = __( 'The payment provider returned an unexpected response.', 'cohf-child' );
		return $out;
	}

	$txn = $body['data'];
	$out['status'] = isset( $txn['status'] ) ? (string) $txn['status'] : 'unknown';

	if ( 'success' !== $out['status'] ) {
		$out['message'] = __( 'This payment did not complete. Nothing has been charged.', 'cohf-child' );
		return $out;
	}

	/*
	 * Compare what Paystack actually took against what this site authorised
	 * in inc/giving-checkout.php. Recording the difference matters more than
	 * rejecting it: the money has already moved, so the useful action is to
	 * flag the gift for a human rather than to pretend it did not happen.
	 */
	$paid     = isset( $txn['amount'] ) ? absint( $txn['amount'] ) / 100 : 0;
	$expected = function_exists( 'cohf_giving_expected' ) ? cohf_giving_expected( $reference ) : false;
	$mismatch = ( is_array( $expected ) && isset( $expected['amount'] ) && abs( (float) $expected['amount'] - (float) $paid ) > 0.009 );

	$record = cohf_record_donation( $txn );

	if ( $record['id'] ) {
		if ( $mismatch ) {
			update_post_meta( $record['id'], '_cohf_amount_expected', (float) $expected['amount'] );
			update_post_meta( $record['id'], '_cohf_amount_mismatch', '1' );
		}

		// The donor's message and anonymity preference are known to this
		// site but are not worth sending through Paystack metadata twice.
		if ( is_array( $expected ) ) {
			if ( isset( $expected['note'] ) && '' !== $expected['note'] ) {
				update_post_meta( $record['id'], '_cohf_message', $expected['note'] );
			}
			if ( isset( $expected['anon'] ) && $expected['anon'] ) {
				update_post_meta( $record['id'], '_cohf_anonymous', '1' );
			}
		}
	}

	$out['ok']          = true;
	$out['donation_id'] = $record['id'];
	$out['amount']      = isset( $txn['amount'] ) ? absint( $txn['amount'] ) / 100 : 0;
	$out['currency']    = isset( $txn['currency'] ) ? (string) $txn['currency'] : 'KES';

	if ( $record['id'] ) {
		$out['name'] = (string) get_post_meta( $record['id'], '_cohf_donor_name', true );
	}

	// Notify once, on first record, whichever route got here first.
	if ( $record['created'] && $record['id'] ) {
		cohf_donation_notify( $record['id'] );
	}

	return $out;
}

/* -------------------------------------------------------------------------
   Webhook - the authoritative route
   ------------------------------------------------------------------------- */

/**
 * Register the Paystack webhook endpoint.
 */
function cohf_giving_rest_routes() {
	register_rest_route(
		'cohf/v1',
		'/paystack',
		array(
			'methods'             => 'POST',
			'callback'            => 'cohf_giving_webhook',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'cohf_giving_rest_routes' );

/**
 * Handle a Paystack webhook.
 *
 * Authenticated by the signature Paystack sends, not by a nonce or a login:
 * the caller is Paystack's server, which has neither. The raw body is hashed
 * with the secret key and compared against the x-paystack-signature header,
 * so a forged post cannot be accepted.
 *
 * @param WP_REST_Request $request The request.
 * @return WP_REST_Response
 */
function cohf_giving_webhook( $request ) {
	if ( cohf_giving_can_verify() === false ) {
		return new WP_REST_Response( array( 'ignored' => 'not configured' ), 200 );
	}

	$raw       = $request->get_body();
	$signature = (string) $request->get_header( 'x-paystack-signature' );
	$expected  = hash_hmac( 'sha512', $raw, cohf_paystack_secret() );

	if ( hash_equals( $expected, $signature ) === false ) {
		return new WP_REST_Response( array( 'error' => 'bad signature' ), 401 );
	}

	$payload = json_decode( $raw, true );
	$event   = isset( $payload['event'] ) ? (string) $payload['event'] : '';

	if ( 'charge.success' !== $event || isset( $payload['data'] ) === false ) {
		return new WP_REST_Response( array( 'ignored' => $event ), 200 );
	}

	$record = cohf_record_donation( $payload['data'] );

	if ( $record['created'] && $record['id'] ) {
		cohf_donation_notify( $record['id'] );
	}

	return new WP_REST_Response( array( 'recorded' => (bool) $record['id'] ), 200 );
}

/* -------------------------------------------------------------------------
   Notifications
   ------------------------------------------------------------------------- */

/**
 * Tell the Foundation about a gift, and thank the donor.
 *
 * Called once per donation, on the first successful record.
 *
 * @param int $donation_id Donation post ID.
 */
function cohf_donation_notify( $donation_id ) {
	$donation_id = (int) $donation_id;

	if ( 0 === $donation_id ) {
		return;
	}

	// Belt and braces: never send twice for the same gift, whichever route
	// recorded it first.
	if ( get_post_meta( $donation_id, '_cohf_notified', true ) ) {
		return;
	}

	update_post_meta( $donation_id, '_cohf_notified', '1' );

	$org      = function_exists( 'cohf_org' ) ? cohf_org() : array();
	$site     = get_bloginfo( 'name' );
	$to_org   = isset( $org['email'] ) && is_email( $org['email'] ) ? $org['email'] : get_option( 'admin_email' );
	$ref      = (string) get_post_meta( $donation_id, '_cohf_reference', true );
	$amount   = (float) get_post_meta( $donation_id, '_cohf_amount', true );
	$currency = (string) get_post_meta( $donation_id, '_cohf_currency', true );
	$name     = (string) get_post_meta( $donation_id, '_cohf_donor_name', true );
	$email    = (string) get_post_meta( $donation_id, '_cohf_donor_email', true );
	$phone    = (string) get_post_meta( $donation_id, '_cohf_donor_phone', true );
	$area     = (string) get_post_meta( $donation_id, '_cohf_area', true );
	$channel  = (string) get_post_meta( $donation_id, '_cohf_channel', true );

	$money = $currency . ' ' . number_format_i18n( $amount, 0 );

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );

	/* ---- To the Foundation ---- */
	$org_lines = array(
		sprintf( __( 'A donation of %s has been received.', 'cohf-child' ), $money ),
		'',
		sprintf( __( 'Donor: %s', 'cohf-child' ), '' !== $name ? $name : __( 'not given', 'cohf-child' ) ),
		sprintf( __( 'Email: %s', 'cohf-child' ), '' !== $email ? $email : __( 'not given', 'cohf-child' ) ),
		sprintf( __( 'Phone: %s', 'cohf-child' ), '' !== $phone ? $phone : __( 'not given', 'cohf-child' ) ),
		sprintf( __( 'Support area: %s', 'cohf-child' ), '' !== $area ? $area : __( 'Where needed most', 'cohf-child' ) ),
		sprintf( __( 'Paid by: %s', 'cohf-child' ), '' !== $channel ? $channel : __( 'not reported', 'cohf-child' ) ),
		sprintf( __( 'Reference: %s', 'cohf-child' ), $ref ),
		'',
		__( 'This gift is recorded under Foundation > Donations.', 'cohf-child' ),
	);

	wp_mail(
		$to_org,
		sprintf( __( '[%1$s] Donation received: %2$s', 'cohf-child' ), $site, $money ),
		implode( "\n", $org_lines ),
		$headers
	);

	/* ---- To the donor ---- */
	if ( is_email( $email ) === false ) {
		return;
	}

	$donor_lines = array(
		'' !== $name ? sprintf( __( 'Dear %s,', 'cohf-child' ), $name ) : __( 'Hello,', 'cohf-child' ),
		'',
		sprintf( __( 'Thank you for your gift of %s to Cistern of Hope Foundation.', 'cohf-child' ), $money ),
		'',
		__( 'Your generosity goes directly into the work of restoring dignity, promoting hope and strengthening communities in Kenya.', 'cohf-child' ),
		'',
		sprintf( __( 'Your payment reference is %s. Please keep this email as your receipt.', 'cohf-child' ), $ref ),
		'',
		'' !== $area ? sprintf( __( 'You chose to support: %s', 'cohf-child' ), $area ) : '',
		'',
		__( 'With gratitude,', 'cohf-child' ),
		$site,
	);

	wp_mail(
		$email,
		sprintf( __( 'Thank you for your gift to %s', 'cohf-child' ), $site ),
		implode( "\n", array_filter( $donor_lines, 'strlen' ) ),
		$headers
	);
}

/* -------------------------------------------------------------------------
   Admin: a readable donations list
   ------------------------------------------------------------------------- */

/**
 * Columns for the donations list.
 *
 * @param array $columns Existing columns.
 * @return array
 */
function cohf_donation_columns( $columns ) {
	return array(
		'cb'            => isset( $columns['cb'] ) ? $columns['cb'] : '',
		'title'         => __( 'Reference', 'cohf-child' ),
		'cohf_amount'   => __( 'Amount', 'cohf-child' ),
		'cohf_donor'    => __( 'Donor', 'cohf-child' ),
		'cohf_area'     => __( 'Support area', 'cohf-child' ),
		'cohf_dstatus'  => __( 'Status', 'cohf-child' ),
		'date'          => __( 'Recorded', 'cohf-child' ),
	);
}
add_filter( 'manage_cohf_donation_posts_columns', 'cohf_donation_columns' );

/**
 * Content for the donation columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function cohf_donation_column_content( $column, $post_id ) {
	if ( 'cohf_amount' === $column ) {
		$amount   = (float) get_post_meta( $post_id, '_cohf_amount', true );
		$currency = (string) get_post_meta( $post_id, '_cohf_currency', true );
		echo esc_html( $currency . ' ' . number_format_i18n( $amount, 0 ) );
		return;
	}

	if ( 'cohf_donor' === $column ) {
		$name  = (string) get_post_meta( $post_id, '_cohf_donor_name', true );
		$email = (string) get_post_meta( $post_id, '_cohf_donor_email', true );
		echo esc_html( '' !== $name ? $name : $email );
		return;
	}

	if ( 'cohf_area' === $column ) {
		echo esc_html( (string) get_post_meta( $post_id, '_cohf_area', true ) );
		return;
	}

	if ( 'cohf_dstatus' === $column ) {
		$status = (string) get_post_meta( $post_id, '_cohf_status', true );
		echo esc_html( 'success' === $status ? __( 'Paid', 'cohf-child' ) : $status );
	}
}
add_action( 'manage_cohf_donation_posts_custom_column', 'cohf_donation_column_content', 10, 2 );
