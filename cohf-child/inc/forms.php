<?php
/**
 * Native contact and enquiry form handling.
 *
 * WPForms or Fluent Forms can replace this at any time by placing their
 * shortcode in the page content; this fallback exists so the site is fully
 * functional on day one with no plugin licence.
 *
 * Security: nonce, capability-free but rate-limited, honeypot, full
 * sanitisation, and no data stored in the database beyond the transient
 * used for rate limiting.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enquiry types offered on the contact page.
 *
 * @return array<string,string>
 */
function cohf_enquiry_types() {
	return array(
		'general'     => __( 'General enquiry', 'cohf-child' ),
		'partnership' => __( 'Partnership enquiry', 'cohf-child' ),
		'volunteer'   => __( 'Volunteer enquiry', 'cohf-child' ),
		'support'     => __( 'Supporting our work', 'cohf-child' ),
		'media'       => __( 'Media enquiry', 'cohf-child' ),
		'complaint'   => __( 'Complaint or feedback', 'cohf-child' ),
	);
}

/**
 * Handle a submitted enquiry.
 */
function cohf_handle_enquiry() {
	if ( ! isset( $_POST['cohf_enquiry_submit'] ) ) {
		return;
	}

	// Addresses that keep failing the checks are paused for an hour.
	if ( function_exists( 'cohf_guard_blocked' ) && cohf_guard_blocked() ) {
		cohf_set_form_result( 'error', sprintf(
			/* translators: %s: email address. */
			__( 'Too many attempts from your connection. Please try again later or email us at %s.', 'cohf-child' ),
			cohf_org_get( 'email' )
		) );
		return;
	}

	$nonce = isset( $_POST['cohf_enquiry_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['cohf_enquiry_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'cohf_enquiry' ) ) {
		cohf_set_form_result( 'error', __( 'Your session expired. Please try sending the message again.', 'cohf-child' ) );
		return;
	}

	// Honeypot: real people never fill this field.
	if ( ! empty( $_POST['cohf_website'] ) ) {
		cohf_set_form_result( 'success', __( 'Thank you. Your message has been received.', 'cohf-child' ) );
		return;
	}

	// Time trap: the form carries a signed timestamp. Bots post instantly or
	// replay an old page; people take a few seconds and send within a day.
	$ts_raw = isset( $_POST['cohf_ts'] ) ? sanitize_text_field( wp_unslash( $_POST['cohf_ts'] ) ) : '';
	$parts  = explode( '.', $ts_raw );
	$ts     = isset( $parts[0] ) ? (int) $parts[0] : 0;
	$sig    = isset( $parts[1] ) ? $parts[1] : '';
	$age    = time() - $ts;
	if ( ( $ts > 0 && hash_equals( cohf_form_ts_sig( $ts ), $sig ) ) === false || $age < 3 || $age > DAY_IN_SECONDS ) {
		if ( function_exists( 'cohf_guard_record_fail' ) ) {
			cohf_guard_record_fail();
		}
		cohf_set_form_result( 'error', __( 'Please take a moment to complete the form, then send it again.', 'cohf-child' ) );
		return;
	}

	// Rate limits, keyed by a hashed IP. No personal data is retained:
	// one message per 45 seconds and at most five per hour.
	$ip_raw   = function_exists( 'cohf_giving_client_ip' ) ? cohf_giving_client_ip() : ( isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '' );
	$ip_hash  = md5( $ip_raw . wp_salt() );
	$key      = 'cohf_rl_' . $ip_hash;
	$hour_key = 'cohf_rlh_' . $ip_hash;
	$hourly   = (int) get_transient( $hour_key );
	if ( get_transient( $key ) || $hourly >= 5 ) {
		cohf_set_form_result( 'error', __( 'Please wait a little while before sending another message.', 'cohf-child' ) );
		return;
	}

	$name    = isset( $_POST['cohf_name'] ) ? sanitize_text_field( wp_unslash( $_POST['cohf_name'] ) ) : '';
	$email   = isset( $_POST['cohf_email'] ) ? sanitize_email( wp_unslash( $_POST['cohf_email'] ) ) : '';
	$org     = isset( $_POST['cohf_organisation'] ) ? sanitize_text_field( wp_unslash( $_POST['cohf_organisation'] ) ) : '';
	$phone   = isset( $_POST['cohf_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['cohf_phone'] ) ) : '';
	$type    = isset( $_POST['cohf_type'] ) ? sanitize_key( wp_unslash( $_POST['cohf_type'] ) ) : 'general';
	$message = isset( $_POST['cohf_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['cohf_message'] ) ) : '';
	$consent = ! empty( $_POST['cohf_consent'] );

	$types = cohf_enquiry_types();
	if ( ! isset( $types[ $type ] ) ) {
		$type = 'general';
	}

	if ( '' === $name || ! is_email( $email ) || '' === $message ) {
		cohf_set_form_result( 'error', __( 'Please provide your name, a valid email address and a message.', 'cohf-child' ) );
		return;
	}
	// Length limits and content checks against link spam and injection.
	if ( mb_strlen( $name ) > 100 || mb_strlen( $email ) > 150 || mb_strlen( $org ) > 150 || mb_strlen( $phone ) > 30 || mb_strlen( $message ) > 5000 ) {
		cohf_set_form_result( 'error', __( 'One of the fields is too long. Please shorten your message and try again.', 'cohf-child' ) );
		return;
	}
	if ( '' !== $phone && preg_match( '/^[0-9+()\s.-]{6,30}$/', $phone ) === 0 ) {
		cohf_set_form_result( 'error', __( 'Please enter a valid phone number, or leave it blank.', 'cohf-child' ) );
		return;
	}
	$links = preg_match_all( '#(https?://|www\.|\[url|<a\s)#i', $message . ' ' . $name . ' ' . $org );
	if ( $links > 2 || preg_match( '#https?://|www\.#i', $name ) ) {
		cohf_set_form_result( 'error', __( 'Please remove the web links from your message and try again, or email us directly.', 'cohf-child' ) );
		return;
	}

	// Spam scoring: thank the sender, send nothing.
	if ( function_exists( 'cohf_guard_spam_score' ) ) {
		$js    = isset( $_POST['cohf_js'] ) ? sanitize_text_field( wp_unslash( $_POST['cohf_js'] ) ) : '';
		$score = cohf_guard_spam_score( array( 'name' => $name, 'email' => $email, 'org' => $org, 'message' => $message, 'js' => $js ) );
		if ( $score >= 5 ) {
			cohf_guard_record_fail();
			cohf_set_form_result( 'success', __( 'Thank you. Your message has been received.', 'cohf-child' ) );
			return;
		}
	}

	// The same message sent again within a day is a replay or a bulk run:
	// report success so a bot learns nothing, but send nothing.
	$fingerprint = 'cohf_msg_' . md5( strtolower( preg_replace( '/\s+/', ' ', $message ) ) );
	if ( get_transient( $fingerprint ) ) {
		cohf_set_form_result( 'success', __( 'Thank you. Your message has been received.', 'cohf-child' ) );
		return;
	}

	// Site-wide ceiling, so a botnet using many addresses still cannot turn
	// the form into a mail cannon.
	if ( function_exists( 'cohf_spam_over_limit' ) && cohf_spam_over_limit( 'enquiry_site', 30, HOUR_IN_SECONDS, false ) ) {
		cohf_set_form_result( 'error', sprintf(
			/* translators: %s: email address. */
			__( 'The form is very busy right now. Please email us directly at %s.', 'cohf-child' ),
			cohf_org_get( 'email' )
		) );
		return;
	}

	if ( ! $consent ) {
		cohf_set_form_result( 'error', __( 'Please confirm you are happy for us to reply to your message.', 'cohf-child' ) );
		return;
	}

	$to      = cohf_org_get( 'email' );
	$subject = sprintf( '[%s] %s', $types[ $type ], $name );

	$body_lines = array(
		sprintf( '%s: %s', __( 'Enquiry type', 'cohf-child' ), $types[ $type ] ),
		sprintf( '%s: %s', __( 'Name', 'cohf-child' ), $name ),
		sprintf( '%s: %s', __( 'Email', 'cohf-child' ), $email ),
	);
	if ( $org ) {
		$body_lines[] = sprintf( '%s: %s', __( 'Organisation', 'cohf-child' ), $org );
	}
	if ( $phone ) {
		$body_lines[] = sprintf( '%s: %s', __( 'Telephone', 'cohf-child' ), $phone );
	}
	$body_lines[] = '';
	$body_lines[] = $message;
	$body_lines[] = '';
	$body_lines[] = sprintf( '%s: %s', __( 'Sent from', 'cohf-child' ), home_url( '/' ) );

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		sprintf( 'Reply-To: %s <%s>', $name, $email ),
	);

	$sent = wp_mail( $to, $subject, implode( "\n", $body_lines ), $headers );

	set_transient( $key, 1, 45 );
	set_transient( $hour_key, $hourly + 1, HOUR_IN_SECONDS );
	set_transient( $fingerprint, 1, DAY_IN_SECONDS );
	if ( function_exists( 'cohf_spam_over_limit' ) ) {
		cohf_spam_over_limit( 'enquiry_site', 30, HOUR_IN_SECONDS );
	}

	if ( $sent ) {
		cohf_set_form_result( 'success', __( 'Thank you. Your message has been sent and a member of the team will respond.', 'cohf-child' ) );
	} else {
		cohf_set_form_result(
			'error',
			sprintf(
				/* translators: %s: email address. */
				__( 'The message could not be sent from the website. Please email us directly at %s.', 'cohf-child' ),
				cohf_org_get( 'email' )
			)
		);
	}
}
add_action( 'template_redirect', 'cohf_handle_enquiry' );

/**
 * Signature for the form timestamp, so it cannot be forged.
 *
 * @param int $ts Unix timestamp.
 * @return string
 */
function cohf_form_ts_sig( $ts ) {
	return substr( hash_hmac( 'sha256', 'cohf_form|' . (int) $ts, wp_salt( 'nonce' ) ), 0, 20 );
}

/**
 * Hidden signed timestamp field for public forms.
 */
function cohf_form_ts_field() {
	$ts = time();
	printf( '<input type="hidden" name="cohf_ts" value="%s">', esc_attr( $ts . '.' . cohf_form_ts_sig( $ts ) ) );
}

/**
 * Store and read the form result for this request.
 *
 * @param string $status  success|error.
 * @param string $message Message.
 * @return array|null
 */
function cohf_set_form_result( $status = null, $message = null ) {
	static $result = null;
	if ( null !== $status ) {
		$result = array( 'status' => $status, 'message' => $message );
	}
	return $result;
}

/**
 * Render the result notice.
 */
function cohf_form_result_notice() {
	$result = cohf_set_form_result();
	if ( ! $result ) {
		return;
	}
	printf(
		'<div class="form-notice form-notice--%1$s" role="%3$s" tabindex="-1"><span class="form-notice__icon" aria-hidden="true"></span><span>%2$s</span></div>',
		esc_attr( $result['status'] ),
		esc_html( $result['message'] ),
		'error' === $result['status'] ? 'alert' : 'status'
	);
}
