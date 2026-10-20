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
	// 14.15.0: payment is allowed for this origin and the Paystack checkout
	// frame only, so Apple Pay / Google Pay inside Paystack can still appear
	// while every other embedded frame stays blocked.
	$headers['Permissions-Policy']     = 'geolocation=(), microphone=(), camera=(), usb=(), interest-cohort=(), payment=(self "https://checkout.paystack.com")';
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
	$csp = "frame-ancestors 'self'; base-uri 'self'; object-src 'none'; form-action 'self' https://checkout.paystack.com https://wa.me https://api.whatsapp.com";
	if ( is_ssl() ) {
		// HTTPS everywhere: any stray http:// asset is upgraded by the browser.
		$csp .= '; upgrade-insecure-requests';
	}
	$headers['Content-Security-Policy']    = $csp;
	$headers['X-Permitted-Cross-Domain-Policies'] = 'none';
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

/**
 * 14.3.0: do not advertise the PHP version to scanners.
 */
add_action( 'send_headers', function () {
	if ( function_exists( 'header_remove' ) ) {
		header_remove( 'X-Powered-By' );
	}
} );

/* --------------------------------------------------------------------------
   Hardening added in 14.15.0 (security review, 20 October 2026)
   See docs/SECURITY-AUDIT.md at the repository root for the reasoning.
   -------------------------------------------------------------------------- */

/**
 * HTTPS everywhere.
 *
 * When the site address is https://, any front-end request that arrives over
 * plain http is permanently redirected to the same URL over https. HSTS
 * (above) then keeps the browser on https for a year. WP-CLI, cron and
 * local development (http site address) are untouched.
 */
add_action( 'template_redirect', function () {
	if ( is_ssl() || ( defined( 'WP_CLI' ) && WP_CLI ) || wp_doing_cron() || ! apply_filters( 'cohf_force_https', true ) ) {
		return;
	}
	// Behind a TLS-terminating proxy is_ssl() can be false although the
	// visitor is on https; trust the proxy header so we never loop.
	$proto = isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ? strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ) ) : '';
	if ( 'https' === $proto ) {
		return;
	}
	if ( 0 !== strpos( home_url( '/' ), 'https://' ) ) {
		return;
	}
	$host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );
	$uri  = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- validated below and only used as a same-host path.
	if ( ! is_string( $uri ) || '' === $uri || '/' !== $uri[0] || 0 === strpos( $uri, '//' ) ) {
		$uri = '/';
	}
	wp_safe_redirect( 'https://' . $host . $uri, 301 );
	exit;
}, 0 );

/**
 * Login cookies are always Secure on an https site, and HttpOnly (core default).
 */
add_filter( 'secure_auth_cookie', function ( $secure ) {
	return $secure || is_ssl() || 0 === strpos( home_url( '/' ), 'https://' );
} );
add_filter( 'secure_logged_in_cookie', function ( $secure ) {
	return $secure || is_ssl() || 0 === strpos( home_url( '/' ), 'https://' );
} );

/**
 * Session expiry.
 *
 * WordPress keeps people logged in for 2 days (14 with "Remember me").
 * Staff log in from shared and mobile devices, so sessions are shortened:
 * 8 hours normally, 7 days with "Remember me". Filter
 * `cohf_session_hours` / `cohf_session_remember_days` to change.
 *
 * @param int  $length   Default length in seconds.
 * @param int  $user_id  User ID.
 * @param bool $remember Whether "Remember me" was ticked.
 * @return int
 */
function cohf_session_length( $length, $user_id, $remember ) {
	if ( $remember ) {
		return (int) apply_filters( 'cohf_session_remember_days', 7 ) * DAY_IN_SECONDS;
	}
	return (int) apply_filters( 'cohf_session_hours', 8 ) * HOUR_IN_SECONDS;
}
add_filter( 'auth_cookie_expiration', 'cohf_session_length', 10, 3 );

/**
 * Password strength.
 *
 * Hashing is done by WordPress core (bcrypt from WordPress 6.8, phpass
 * before that); the theme never stores or hashes passwords itself. What the
 * theme adds is a minimum quality for any password a person chooses:
 * at least 12 characters, not purely digits, and not the username or email.
 *
 * @param string  $pass Password chosen.
 * @param WP_User $user User (may be partially filled on registration).
 * @return string Error message, or '' when acceptable.
 */
function cohf_password_problem( $pass, $user = null ) {
	$pass = (string) $pass;
	if ( '' === $pass ) {
		return '';
	}
	$min = (int) apply_filters( 'cohf_password_min_length', 12 );
	if ( strlen( $pass ) < $min ) {
		/* translators: %d: minimum number of characters. */
		return sprintf( __( 'Please choose a password of at least %d characters.', 'cohf-child' ), $min );
	}
	if ( ctype_digit( $pass ) ) {
		return __( 'Please do not use a password made only of numbers.', 'cohf-child' );
	}
	if ( $user instanceof WP_User || is_object( $user ) ) {
		foreach ( array( 'user_login', 'user_email' ) as $field ) {
			if ( ! empty( $user->$field ) && strlen( (string) $user->$field ) >= 4 && false !== stripos( $pass, (string) $user->$field ) ) {
				return __( 'Your password must not contain your username or email address.', 'cohf-child' );
			}
		}
	}
	return '';
}

// Profile screen (wp-admin) - own profile and admins editing others.
add_action( 'user_profile_update_errors', function ( $errors, $update, $user ) {
	$pass = isset( $_POST['pass1'] ) ? (string) wp_unslash( $_POST['pass1'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- core verifies the nonce; passwords are never sanitised.
	$msg  = cohf_password_problem( $pass, $user );
	if ( '' !== $msg ) {
		$errors->add( 'cohf_weak_password', $msg );
	}
}, 10, 3 );

// "Reset password" screen.
add_action( 'validate_password_reset', function ( $errors, $user ) {
	$pass = isset( $_POST['pass1'] ) ? (string) wp_unslash( $_POST['pass1'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- core verifies the reset key; passwords are never sanitised.
	$msg  = cohf_password_problem( $pass, $user instanceof WP_User ? $user : null );
	if ( '' !== $msg && is_wp_error( $errors ) ) {
		$errors->add( 'cohf_weak_password', $msg );
	}
}, 10, 2 );

// WooCommerce "My account > Account details" and registration with a password field.
add_action( 'woocommerce_save_account_details_errors', function ( $errors, $user ) {
	$pass = isset( $_POST['password_1'] ) ? (string) wp_unslash( $_POST['password_1'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- WooCommerce verifies the nonce.
	$msg  = cohf_password_problem( $pass, $user );
	if ( '' !== $msg ) {
		$errors->add( 'cohf_weak_password', $msg );
	}
}, 10, 2 );
add_filter( 'woocommerce_registration_errors', function ( $errors, $username, $email ) {
	$pass = isset( $_POST['password'] ) ? (string) wp_unslash( $_POST['password'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- WooCommerce verifies the nonce.
	$msg  = cohf_password_problem( $pass, (object) array( 'user_login' => $username, 'user_email' => $email ) );
	if ( '' !== $msg ) {
		$errors->add( 'cohf_weak_password', $msg );
	}
	return $errors;
}, 10, 3 );

/**
 * CORS for the theme's REST routes.
 *
 * WordPress core echoes any Origin back in Access-Control-Allow-Origin with
 * credentials allowed. For the cohf/v1 routes (giving, form tokens, Paystack
 * webhook) only this site's own origin is allowed; the Paystack webhook is
 * server-to-server and sends no Origin, so it is unaffected.
 */
add_filter( 'rest_pre_serve_request', function ( $served, $result, $request ) {
	$route  = $request instanceof WP_REST_Request ? (string) $request->get_route() : '';
	$origin = get_http_origin();
	if ( 0 !== strpos( $route, '/cohf/v1' ) || ! $origin ) {
		return $served;
	}
	$allowed = array(
		untrailingslashit( home_url() ),
		untrailingslashit( site_url() ),
		untrailingslashit( set_url_scheme( home_url(), 'https' ) ),
	);
	if ( ! in_array( untrailingslashit( $origin ), $allowed, true ) && ! headers_sent() ) {
		header_remove( 'Access-Control-Allow-Origin' );
		header_remove( 'Access-Control-Allow-Credentials' );
	}
	return $served;
}, 20, 3 );

/**
 * Never cache personal or token-bearing REST responses from cohf/v1.
 */
add_filter( 'rest_post_dispatch', function ( $response, $server, $request ) {
	if ( $response instanceof WP_REST_Response && $request instanceof WP_REST_Request && 0 === strpos( (string) $request->get_route(), '/cohf/v1' ) ) {
		$response->header( 'Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0' );
		$response->header( 'X-Content-Type-Options', 'nosniff' );
	}
	return $response;
}, 10, 3 );

/**
 * Uploads: only administrators may upload SVG or other script-capable types.
 *
 * Core already blocks SVG; this guard makes sure a plugin cannot quietly
 * re-enable SVG, HTML or XML uploads for non-administrators (stored XSS).
 */
add_filter( 'upload_mimes', function ( $mimes ) {
	if ( current_user_can( 'manage_options' ) ) {
		return $mimes;
	}
	foreach ( array_keys( $mimes ) as $ext ) {
		if ( preg_match( '/(^|\|)(svg|svgz|html?|xml|xhtml|js|php\d?|phtml|swf)(\||$)/i', $ext ) ) {
			unset( $mimes[ $ext ] );
		}
	}
	return $mimes;
}, 99 );
add_filter( 'wp_handle_upload_prefilter', function ( $file ) {
	$name = isset( $file['name'] ) ? (string) $file['name'] : '';
	// Block double extensions such as photo.php.jpg that some servers execute.
	if ( preg_match( '/\.(php\d?|phtml|phar|pl|py|cgi|asp|aspx|jsp|sh|exe)(\.|$)/i', $name ) ) {
		$file['error'] = __( 'This file type is not allowed.', 'cohf-child' );
	}
	return $file;
} );

/**
 * Application passwords are not used by this site; turn them off so a leaked
 * one can never be used to call the REST API as a staff member.
 * Filter `cohf_allow_application_passwords` to true to re-enable.
 */
add_filter( 'wp_is_application_passwords_available', function ( $available ) {
	return (bool) apply_filters( 'cohf_allow_application_passwords', false ) && $available;
} );
