<?php
/**
 * Anti-spam and abuse protection (12.1.0).
 *
 * Layers that apply site-wide, on top of the per-form checks in forms.php
 * and giving-checkout.php:
 *
 * 1. Outgoing mail circuit breaker. Every email WordPress, WooCommerce or a
 *    plugin tries to send from a public (non-admin) request is counted. Past
 *    a safe hourly ceiling further mail is held back and the site owner is
 *    told once. This stops the site ever being used to send bulk email,
 *    whatever the entry point. Recipient lists are capped per message.
 * 2. Shop checkout: at most a few orders per visitor per hour (classic and
 *    block checkout), so fake orders cannot be used to fire order emails at
 *    strangers' inboxes.
 * 3. Account registration (WordPress and WooCommerce): honeypot, time trap
 *    and an hourly cap.
 * 4. Password reset: throttled per visitor and per account, so nobody can
 *    flood an inbox with reset emails.
 * 5. Public search endpoint: per-visitor rate limit.
 *
 * Counters are kept in short-lived transients keyed by a salted hash of the
 * visitor's IP. No personal data is stored.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
   Helpers
   ------------------------------------------------------------------------- */

/**
 * Salted hash of the visitor's IP, for counters only.
 *
 * @return string
 */
function cohf_spam_ip_hash() {
	$ip = function_exists( 'cohf_giving_client_ip' ) ? cohf_giving_client_ip() : ( isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '' );
	return substr( hash_hmac( 'sha256', (string) $ip, wp_salt( 'auth' ) ), 0, 24 );
}

/**
 * Count one hit in a window and report whether the limit is exceeded.
 *
 * @param string $name   Counter name.
 * @param int    $limit  Allowed hits per window.
 * @param int    $window Window in seconds.
 * @param bool   $count  Whether to record this hit.
 * @return bool True when over the limit.
 */
function cohf_spam_over_limit( $name, $limit, $window, $count = true ) {
	$key  = 'cohf_sl_' . md5( $name );
	$hits = (int) get_transient( $key );
	if ( $hits >= $limit ) {
		return true;
	}
	if ( $count ) {
		set_transient( $key, $hits + 1, $window );
	}
	return false;
}

/**
 * Trusted context: a logged-in administrator or shop manager in wp-admin,
 * WP-CLI, or a cron run started by WordPress itself.
 *
 * @return bool
 */
function cohf_spam_trusted_context() {
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		return true;
	}
	if ( is_user_logged_in() && ( current_user_can( 'manage_options' ) || current_user_can( 'manage_woocommerce' ) ) ) {
		return true;
	}
	return false;
}

/* -------------------------------------------------------------------------
   1. Outgoing mail circuit breaker
   ------------------------------------------------------------------------- */

/**
 * Maximum emails per hour from public requests. Filterable for a site that
 * genuinely grows past it.
 *
 * @return int
 */
function cohf_spam_mail_hourly_limit() {
	return (int) apply_filters( 'cohf_mail_hourly_limit', 60 );
}

/**
 * Hold back mail that would turn the site into a bulk sender.
 *
 * @param null|bool $return Short-circuit value.
 * @param array     $atts   wp_mail() arguments.
 * @return null|bool
 */
function cohf_spam_mail_guard( $return, $atts ) {
	if ( null !== $return || cohf_spam_trusted_context() ) {
		return $return;
	}

	// One message may not fan out to a crowd.
	$to = isset( $atts['to'] ) ? $atts['to'] : array();
	if ( is_string( $to ) ) {
		$to = array_filter( array_map( 'trim', explode( ',', $to ) ) );
	}
	$bcc = 0;
	if ( ! empty( $atts['headers'] ) ) {
		$headers = is_array( $atts['headers'] ) ? implode( "\n", $atts['headers'] ) : (string) $atts['headers'];
		$bcc     = preg_match_all( '/^(cc|bcc):/im', $headers );
	}
	if ( count( (array) $to ) > 5 || $bcc > 2 ) {
		return false;
	}

	// Site-wide hourly ceiling.
	$limit = cohf_spam_mail_hourly_limit();
	if ( cohf_spam_over_limit( 'mail_hour', $limit, HOUR_IN_SECONDS ) ) {
		cohf_spam_mail_alert( $limit );
		return false;
	}
	return $return;
}
add_filter( 'pre_wp_mail', 'cohf_spam_mail_guard', 5, 2 );

/**
 * Tell the site owner, once per day, that the breaker tripped.
 *
 * @param int $limit Limit reached.
 */
function cohf_spam_mail_alert( $limit ) {
	if ( get_transient( 'cohf_mail_alerted' ) ) {
		return;
	}
	set_transient( 'cohf_mail_alerted', 1, DAY_IN_SECONDS );
	update_option( 'cohf_mail_breaker_tripped', time(), false );
	$to = get_option( 'admin_email' );
	if ( is_email( $to ) === false ) {
		return;
	}
	remove_filter( 'pre_wp_mail', 'cohf_spam_mail_guard', 5 );
	wp_mail(
		$to,
		sprintf( '[%s] Outgoing email paused', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) ),
		sprintf(
			"The website tried to send more than %d emails in an hour from public visitors, so further email has been paused for the rest of the hour.\n\nThis usually means a bot is abusing a form, checkout or password reset. Nothing needs doing if it was a genuine busy hour; otherwise check WooCommerce > Orders and the contact form for junk entries.\n\n%s",
			(int) $limit,
			home_url( '/' )
		)
	);
	add_filter( 'pre_wp_mail', 'cohf_spam_mail_guard', 5, 2 );
}

/**
 * Admin notice when the breaker tripped in the last 24 hours.
 */
function cohf_spam_mail_notice() {
	if ( current_user_can( 'manage_options' ) === false ) {
		return;
	}
	$when = (int) get_option( 'cohf_mail_breaker_tripped', 0 );
	if ( $when < 1 || ( time() - $when ) > DAY_IN_SECONDS ) {
		return;
	}
	printf(
		'<div class="notice notice-warning"><p>%s</p></div>',
		esc_html( sprintf(
			/* translators: %s: time. */
			__( 'Outgoing email was paused at %s because public visitors triggered an unusual number of emails in one hour. Check recent orders and enquiries for junk.', 'cohf-child' ),
			wp_date( get_option( 'time_format' ), $when )
		) )
	);
}
add_action( 'admin_notices', 'cohf_spam_mail_notice' );

/* -------------------------------------------------------------------------
   2. Shop checkout
   ------------------------------------------------------------------------- */

/**
 * Orders allowed per visitor per hour.
 *
 * @return int
 */
function cohf_spam_order_limit() {
	return (int) apply_filters( 'cohf_order_hourly_limit', 5 );
}

/**
 * Classic checkout.
 *
 * @param array    $data   Posted data.
 * @param WP_Error $errors Errors.
 */
function cohf_spam_classic_checkout( $data, $errors ) {
	if ( cohf_spam_trusted_context() ) {
		return;
	}
	if ( cohf_spam_over_limit( 'order_' . cohf_spam_ip_hash(), cohf_spam_order_limit(), HOUR_IN_SECONDS ) ) {
		$errors->add( 'cohf_rate', __( 'Too many orders have been placed from this connection. Please wait a while, or order on WhatsApp.', 'cohf-child' ) );
	}
}
add_action( 'woocommerce_after_checkout_validation', 'cohf_spam_classic_checkout', 10, 2 );

/**
 * Block checkout (Store API) and other public Store API writes.
 *
 * @param mixed           $result  Response to short-circuit with.
 * @param WP_REST_Server  $server  Server.
 * @param WP_REST_Request $request Request.
 * @return mixed
 */
function cohf_spam_store_api( $result, $server, $request ) {
	if ( null !== $result || 'POST' !== $request->get_method() || cohf_spam_trusted_context() ) {
		return $result;
	}
	$route = $request->get_route();
	if ( 0 === strpos( $route, '/wc/store/v1/checkout' ) || 0 === strpos( $route, '/wc/store/checkout' ) ) {
		if ( cohf_spam_over_limit( 'order_' . cohf_spam_ip_hash(), cohf_spam_order_limit(), HOUR_IN_SECONDS ) ) {
			return new WP_Error( 'cohf_rate', __( 'Too many orders have been placed from this connection. Please wait a while, or order on WhatsApp.', 'cohf-child' ), array( 'status' => 429 ) );
		}
	}
	// Cart-changing calls: generous, but stops scripted hammering.
	if ( 0 === strpos( $route, '/wc/store' ) && cohf_spam_over_limit( 'store_' . cohf_spam_ip_hash(), 120, 5 * MINUTE_IN_SECONDS ) ) {
		return new WP_Error( 'cohf_rate', __( 'Too many requests. Please slow down and try again shortly.', 'cohf-child' ), array( 'status' => 429 ) );
	}
	return $result;
}
add_filter( 'rest_pre_dispatch', 'cohf_spam_store_api', 10, 3 );

/* -------------------------------------------------------------------------
   3. Registration
   ------------------------------------------------------------------------- */

/**
 * Honeypot and signed timestamp on registration forms.
 */
function cohf_spam_register_fields() {
	echo '<p class="cohf-hp" aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden"><label>' . esc_html__( 'Leave this empty', 'cohf-child' ) . '<input type="text" name="cohf_hp_site" value="" tabindex="-1" autocomplete="off"></label></p>';
	if ( function_exists( 'cohf_form_ts_field' ) ) {
		cohf_form_ts_field();
	}
}
add_action( 'register_form', 'cohf_spam_register_fields' );
add_action( 'woocommerce_register_form', 'cohf_spam_register_fields' );

/**
 * Shared registration checks.
 *
 * @return string Error message, or '' when fine.
 */
function cohf_spam_registration_problem() {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- the core/WooCommerce forms verify their own nonce.
	if ( ! empty( $_POST['cohf_hp_site'] ) ) {
		return __( 'Registration could not be completed.', 'cohf-child' );
	}
	$raw   = isset( $_POST['cohf_ts'] ) ? sanitize_text_field( wp_unslash( $_POST['cohf_ts'] ) ) : '';
	// phpcs:enable
	$parts = explode( '.', $raw );
	$ts    = isset( $parts[0] ) ? (int) $parts[0] : 0;
	$sig   = isset( $parts[1] ) ? $parts[1] : '';
	$age   = time() - $ts;
	if ( function_exists( 'cohf_form_ts_sig' ) && ( $ts < 1 || hash_equals( cohf_form_ts_sig( $ts ), $sig ) === false || $age < 3 || $age > DAY_IN_SECONDS ) ) {
		return __( 'Please take a moment to complete the form, then try again.', 'cohf-child' );
	}
	if ( cohf_spam_over_limit( 'reg_' . cohf_spam_ip_hash(), 3, HOUR_IN_SECONDS ) ) {
		return __( 'Too many accounts have been created from this connection. Please try again later.', 'cohf-child' );
	}
	return '';
}

/**
 * WordPress registration.
 *
 * @param WP_Error $errors Errors.
 * @return WP_Error
 */
function cohf_spam_wp_registration( $errors ) {
	$problem = cohf_spam_registration_problem();
	if ( '' !== $problem ) {
		$errors->add( 'cohf_spam', $problem );
	}
	return $errors;
}
add_filter( 'registration_errors', 'cohf_spam_wp_registration', 10 );

/**
 * WooCommerce My Account registration (checkout account creation is covered
 * by the checkout limit instead, because that form carries no honeypot).
 *
 * @param WP_Error $errors Errors.
 * @return WP_Error
 */
function cohf_spam_wc_registration( $errors ) {
	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		return $errors;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- WooCommerce verifies the register nonce.
	if ( isset( $_POST['woocommerce-register-nonce'] ) === false ) {
		return $errors;
	}
	$problem = cohf_spam_registration_problem();
	if ( '' !== $problem ) {
		$errors->add( 'cohf_spam', $problem );
	}
	return $errors;
}
add_filter( 'woocommerce_registration_errors', 'cohf_spam_wc_registration', 10 );

/* -------------------------------------------------------------------------
   4. Password reset
   ------------------------------------------------------------------------- */

/**
 * Three reset requests per visitor per 15 minutes, and three per account
 * per hour.
 *
 * @param WP_Error      $errors    Errors.
 * @param WP_User|false $user_data Account, when found.
 */
function cohf_spam_lostpassword( $errors, $user_data = false ) {
	if ( cohf_spam_over_limit( 'lp_ip_' . cohf_spam_ip_hash(), 3, 15 * MINUTE_IN_SECONDS ) ) {
		$errors->add( 'cohf_rate', __( 'Too many password reset requests. Please wait 15 minutes and try again.', 'cohf-child' ) );
		return;
	}
	if ( $user_data instanceof WP_User && cohf_spam_over_limit( 'lp_user_' . $user_data->ID, 3, HOUR_IN_SECONDS ) ) {
		$errors->add( 'cohf_rate', __( 'Too many password reset requests for this account. Please check your inbox, or try again in an hour.', 'cohf-child' ) );
	}
}
add_action( 'lostpassword_post', 'cohf_spam_lostpassword', 10, 2 );

/* -------------------------------------------------------------------------
   5. Public search endpoint
   ------------------------------------------------------------------------- */

/**
 * 90 live searches per visitor per minute; a person typing never gets close.
 */
function cohf_spam_search_limit() {
	if ( cohf_spam_over_limit( 'search_' . cohf_spam_ip_hash(), 90, MINUTE_IN_SECONDS ) ) {
		status_header( 429 );
		wp_send_json( array( 'q' => '', 'products' => array(), 'categories' => array(), 'total' => 0, 'suggest' => '', 'url' => '' ), 429 );
	}
}
add_action( 'wc_ajax_cohf_search', 'cohf_spam_search_limit', 1 );
