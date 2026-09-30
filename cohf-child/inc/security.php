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


/* --------------------------------------------------------------------------
   Hardening added in 9.78.0
   -------------------------------------------------------------------------- */

/**
 * HSTS, clickjacking and cross-origin isolation headers.
 *
 * HSTS is only sent over HTTPS so a misconfigured host can never lock
 * visitors out. COOP allows popups so the Paystack checkout still works.
 */
add_filter( 'wp_headers', function ( $headers ) {
	if ( is_ssl() ) {
		$headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains';
	}
	$headers['Cross-Origin-Opener-Policy'] = 'same-origin-allow-popups';
	$headers['Content-Security-Policy']    = "frame-ancestors 'self'; base-uri 'self'; object-src 'none'";
	return $headers;
}, 20 );

/**
 * XML-RPC is not used by this site and is the most common route for
 * password-guessing and pingback abuse. Turn it off entirely.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'wp_headers', function ( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
}, 30 );

/**
 * Remove the WordPress version and other fingerprints from page source.
 */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
add_filter( 'the_generator', '__return_empty_string' );

/**
 * Stop username discovery.
 *
 * ?author=1 redirects to /author/username/, and the public REST users
 * endpoint lists every account. Both hand an attacker half of a login.
 */
add_action( 'template_redirect', function () {
	if ( is_user_logged_in() ) {
		return;
	}
	if ( is_author() || isset( $_GET['author'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only check.
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}, 1 );
add_filter( 'rest_endpoints', function ( $endpoints ) {
	if ( is_user_logged_in() ) {
		return $endpoints;
	}
	foreach ( array_keys( $endpoints ) as $route ) {
		if ( 0 === strpos( $route, '/wp/v2/users' ) ) {
			unset( $endpoints[ $route ] );
		}
	}
	return $endpoints;
} );
add_filter( 'oembed_response_data', function ( $data ) {
	unset( $data['author_name'], $data['author_url'] );
	return $data;
} );

/**
 * Comments are not part of this site. Closing them everywhere removes the
 * single biggest source of spam on small WordPress sites.
 */
add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open', '__return_false', 20 );
add_filter( 'comments_array', '__return_empty_array', 10 );
add_action( 'init', function () {
	foreach ( get_post_types() as $type ) {
		if ( post_type_supports( $type, 'comments' ) ) {
			remove_post_type_support( $type, 'comments' );
			remove_post_type_support( $type, 'trackbacks' );
		}
	}
}, 100 );
add_action( 'admin_menu', function () {
	remove_menu_page( 'edit-comments.php' );
} );

/**
 * Login throttling.
 *
 * Five failed attempts from one address locks that address out for fifteen
 * minutes. Keyed on a hashed IP; nothing personal is stored. A security
 * plugin can replace this, but the site is no longer defenceless without one.
 */
function cohf_login_ip_key() {
	$ip = function_exists( 'cohf_giving_client_ip' ) ? cohf_giving_client_ip() : ( isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '' );
	return 'cohf_login_' . md5( $ip . wp_salt() );
}
add_filter( 'authenticate', function ( $user ) {
	if ( (int) get_transient( cohf_login_ip_key() ) >= 5 ) {
		return new WP_Error( 'cohf_locked', __( 'Too many failed login attempts. Please try again in 15 minutes.', 'cohf-child' ) );
	}
	return $user;
}, 1 );
add_action( 'wp_login_failed', function () {
	$key = cohf_login_ip_key();
	set_transient( $key, (int) get_transient( $key ) + 1, 15 * MINUTE_IN_SECONDS );
} );
add_action( 'wp_login', function () {
	delete_transient( cohf_login_ip_key() );
} );
