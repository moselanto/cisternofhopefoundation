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

	// Simple rate limit, keyed by hashed IP. No personal data is retained.
	$ip_raw = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key    = 'cohf_rl_' . md5( $ip_raw . wp_salt() );
	if ( get_transient( $key ) ) {
		cohf_set_form_result( 'error', __( 'Please wait a moment before sending another message.', 'cohf-child' ) );
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
		'<div class="form-notice form-notice--%1$s" role="status" tabindex="-1">%2$s</div>',
		esc_attr( $result['status'] ),
		esc_html( $result['message'] )
	);
}
