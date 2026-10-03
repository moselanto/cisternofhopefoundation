<?php
/**
 * Extra form protection (13.89.0).
 *
 * - Spam scoring: spam phrases, throwaway email addresses, foreign-script
 *   floods, shouting and missing human interaction add points. High scores
 *   are told "thank you" (so bots learn nothing) but nothing is sent.
 * - Repeat offenders: an address that keeps failing the security checks is
 *   blocked for an hour.
 * - Fresh tokens: forms collect a fresh security token when a visitor starts
 *   typing, so a page served from a cache days ago still sends correctly.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/** Hashed client address (no raw IP is ever stored). */
function cohf_guard_ip_hash() {
	$ip = function_exists( 'cohf_giving_client_ip' ) ? cohf_giving_client_ip() : ( isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '' );
	return md5( 'guard|' . $ip . wp_salt() );
}

/** Record a failed or spammy attempt from this address. */
function cohf_guard_record_fail() {
	$key = 'cohf_gf_' . cohf_guard_ip_hash();
	set_transient( $key, (int) get_transient( $key ) + 1, HOUR_IN_SECONDS );
}

/** True when this address has failed too often in the last hour. */
function cohf_guard_blocked() {
	return (int) get_transient( 'cohf_gf_' . cohf_guard_ip_hash() ) >= 12;
}

/** Throwaway email domains commonly used by spam tools. */
function cohf_guard_disposable_domains() {
	return array( 'mailinator.com', 'guerrillamail.com', 'guerrillamail.net', '10minutemail.com', 'tempmail.com', 'temp-mail.org', 'yopmail.com', 'trashmail.com', 'sharklasers.com', 'getnada.com', 'dispostable.com', 'maildrop.cc', 'throwawaymail.com', 'fakeinbox.com', 'mailnesia.com', 'tempail.com', 'emailondeck.com', 'mintemail.com', 'spamgourmet.com', 'mohmal.com', 'tempr.email', 'discard.email', 'mailcatch.com', 'moakt.com' );
}

/**
 * Spam score for a submission. 5 or more is treated as spam.
 *
 * @param array $f name, email, org, message, js.
 * @return int
 */
function cohf_guard_spam_score( $f ) {
	$score = 0;
	$all   = strtolower( $f['name'] . ' ' . $f['org'] . ' ' . $f['message'] );

	// No sign of a person typing (no JavaScript interaction token).
	if ( empty( $f['js'] ) ) {
		$score += 2;
	}

	// Common spam phrases.
	$phrases = array( 'seo ', 'backlink', 'rank your website', 'first page of google', 'guest post', 'casino', 'betting tips', 'bitcoin', 'crypto', 'forex', 'binary option', 'viagra', 'cialis', 'porn', 'escort', 'sex dating', 'loan offer', 'investment opportunity', 'web design services', 'website redesign', 'increase your traffic', 'lead generation', 'outsourcing services', 'unsubscribe', 'click here', 'whatsapp me at +1', 'telegram @' );
	$hits    = 0;
	foreach ( $phrases as $p ) {
		if ( false !== strpos( $all, $p ) ) {
			$hits++;
		}
	}
	$score += min( 6, $hits * 3 );

	// Throwaway email address.
	$domain = strtolower( (string) substr( strrchr( $f['email'], '@' ), 1 ) );
	if ( $domain && in_array( $domain, cohf_guard_disposable_domains(), true ) ) {
		$score += 5;
	}

	// Mostly Cyrillic, Chinese, Japanese or Korean text (the site is English and Swahili).
	$letters = preg_match_all( '/\p{L}/u', $f['message'] );
	$foreign = preg_match_all( '/[\p{Cyrillic}\p{Han}\p{Hiragana}\p{Katakana}\p{Hangul}]/u', $f['message'] );
	if ( $letters > 10 && $foreign / $letters > 0.3 ) {
		$score += 5;
	}

	// Shouting.
	$upper = preg_match_all( '/\p{Lu}/u', $f['message'] );
	if ( $letters > 25 && $upper / $letters > 0.7 ) {
		$score += 2;
	}

	// Names with several digits, or a name that is the same as the message.
	if ( preg_match_all( '/\d/', $f['name'] ) >= 3 ) {
		$score += 2;
	}
	if ( '' !== trim( $f['message'] ) && 0 === strcasecmp( trim( $f['name'] ), trim( $f['message'] ) ) ) {
		$score += 3;
	}

	// Very short message.
	if ( mb_strlen( trim( $f['message'] ) ) < 15 ) {
		$score += 1;
	}

	return (int) apply_filters( 'cohf_form_spam_score', $score, $f );
}

/* -------------------------------------------------------------------------
   Fresh security tokens for cached pages.
   ------------------------------------------------------------------------- */
add_action( 'rest_api_init', function () {
	register_rest_route(
		'cohf/v1',
		'/form-token',
		array(
			'methods'             => 'GET',
			'callback'            => 'cohf_guard_form_token',
			'permission_callback' => '__return_true',
		)
	);
} );

/** Return a fresh timestamp signature and nonces (never cached). */
function cohf_guard_form_token() {
	$key  = 'cohf_gt_' . cohf_guard_ip_hash();
	$hits = (int) get_transient( $key );
	if ( $hits >= 60 ) {
		return new WP_REST_Response( array( 'message' => 'Too many requests.' ), 429 );
	}
	set_transient( $key, $hits + 1, HOUR_IN_SECONDS );
	$ts       = time();
	$response = new WP_REST_Response(
		array(
			'ts'      => $ts . '.' . cohf_form_ts_sig( $ts ),
			'enquiry' => wp_create_nonce( 'cohf_enquiry' ),
			'giving'  => wp_create_nonce( 'cohf_giving' ),
		)
	);
	$response->header( 'Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0' );
	return $response;
}
