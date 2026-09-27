<?php
/**
 * Security hardening and safe defaults.
 *
 * The theme never stores credentials, never calls a remote API with a
 * hard-coded key, and escapes all output at the point of printing.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Remove version fingerprinting from the front end.
 */
function cohf_remove_version_strings( $src ) {
	if ( is_admin() ) {
		return $src;
	}
	if ( strpos( $src, 'ver=' ) !== false && strpos( $src, home_url() ) !== false ) {
		// Keep cache-busting for theme assets (they use filemtime), drop core version leaks.
		if ( strpos( $src, '/wp-includes/' ) !== false || strpos( $src, '/wp-admin/' ) !== false ) {
			$src = remove_query_arg( 'ver', $src );
		}
	}
	return $src;
}
add_filter( 'style_loader_src', 'cohf_remove_version_strings', 9999 );
add_filter( 'script_loader_src', 'cohf_remove_version_strings', 9999 );

/**
 * Disable XML-RPC pingbacks (a common amplification vector for small NGOs).
 */
add_filter( 'xmlrpc_methods', function ( $methods ) {
	unset( $methods['pingback.ping'], $methods['pingback.extensions.getPingbacks'] );
	return $methods;
} );

/**
 * Do not disclose whether a username exists on failed login.
 */
add_filter( 'login_errors', function () {
	return __( 'The details entered were not correct.', 'cohf-child' );
} );

/**
 * Disable the file editor in wp-admin unless the host has already defined it.
 * Prevents theme code being edited live without version control.
 */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}

/**
 * Security headers.
 *
 * Content-Security-Policy is intentionally NOT forced here: plugins such as
 * Google Site Kit, Analytics and embedded maps need per-site allowances, and a
 * wrong CSP silently breaks them. Set CSP at the server or via a security
 * plugin once the final third-party list is known.
 *
 * @param array $headers Existing headers.
 * @return array
 */
function cohf_security_headers( $headers ) {
	$headers['X-Content-Type-Options'] = 'nosniff';
	$headers['Referrer-Policy']        = 'strict-origin-when-cross-origin';
	$headers['X-Frame-Options']        = 'SAMEORIGIN';
	$headers['Permissions-Policy']     = 'geolocation=(), microphone=(), camera=(), payment=()';
	return $headers;
}
add_filter( 'wp_headers', 'cohf_security_headers' );

/**
 * Escaping helper for repeated attribute output in templates.
 *
 * @param string $value Raw value.
 * @return string
 */
function cohf_attr( $value ) {
	return esc_attr( wp_strip_all_tags( (string) $value ) );
}

/**
 * Sanitise a multiline admin text field into safe paragraph output.
 *
 * @param string $value Raw field value.
 * @return string
 */
function cohf_sanitize_multiline( $value ) {
	return sanitize_textarea_field( (string) $value );
}

/**
 * Restrict who can manage Foundation content structures.
 * Editors manage content; only administrators touch settings.
 *
 * @return bool
 */
function cohf_user_can_manage_content() {
	return current_user_can( 'edit_others_posts' );
}
