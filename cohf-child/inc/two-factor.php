<?php
/**
 * Two-step verification (2FA) for every staff account.
 *
 * The codes come from the official "Two Factor" plugin
 * (wordpress.org/plugins/two-factor, maintained by WordPress core
 * contributors). We deliberately do not write our own cryptography.
 * Supported: authenticator apps (TOTP: Google/Microsoft Authenticator,
 * Authy, 1Password), security keys where the plugin supports them, email
 * codes, and single-use recovery codes.
 *
 * This module:
 * - tells administrators to install the plugin until it is active;
 * - gives every staff member (anyone who can edit posts) a grace period
 *   (7 days, filter cohf_2fa_grace_days) and then requires 2FA before the
 *   rest of wp-admin opens;
 * - shows a 2FA column on the Users screen so administrators can check;
 * - hides the plugin's test-only "Dummy" method.
 *
 * Emergency switch: define( 'COHF_REQUIRE_2FA', false ); in wp-config.php.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

function cohf_2fa_available() {
	return class_exists( 'Two_Factor_Core' );
}

function cohf_2fa_user_enabled( $user_id ) {
	return cohf_2fa_available() && Two_Factor_Core::is_user_using_two_factor( $user_id );
}

function cohf_2fa_required() {
	return ! defined( 'COHF_REQUIRE_2FA' ) || COHF_REQUIRE_2FA;
}

/** Whether this user is staff who must use 2FA. */
function cohf_2fa_applies_to( $user ) {
	return $user instanceof WP_User && $user->exists() && user_can( $user, 'edit_posts' );
}

/** Days of grace left for a user (0 = must set up now). */
function cohf_2fa_days_left( $user_id ) {
	$start = (int) get_user_meta( $user_id, 'cohf_2fa_grace_start', true );
	if ( ! $start ) {
		$start = time();
		update_user_meta( $user_id, 'cohf_2fa_grace_start', $start );
	}
	$days = max( 0, (int) apply_filters( 'cohf_2fa_grace_days', 7 ) );
	$left = (int) ceil( ( $start + $days * DAY_IN_SECONDS - time() ) / DAY_IN_SECONDS );
	return max( 0, $left );
}

/* Plugin missing: show administrators how to install it. */
add_action( 'admin_notices', function () {
	if ( cohf_2fa_available() || ! current_user_can( 'install_plugins' ) ) {
		return;
	}
	$url = admin_url( 'plugin-install.php?s=two-factor&tab=search&type=term' );
	echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'Protect staff logins with two-step verification:', 'cohf-child' ) . '</strong> '
		. esc_html__( 'install and activate the free "Two Factor" plugin (by WordPress.org contributors). The theme will then guide every staff member through setting it up.', 'cohf-child' )
		. ' <a class="button button-primary" href="' . esc_url( $url ) . '">' . esc_html__( 'Install Two Factor', 'cohf-child' ) . '</a></p></div>';
} );

/* Grace period over and still no 2FA: only the profile screen opens. */
add_action( 'admin_init', function () {
	if ( ! cohf_2fa_available() || ! cohf_2fa_required() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
		return;
	}
	$user = wp_get_current_user();
	if ( ! cohf_2fa_applies_to( $user ) || cohf_2fa_user_enabled( $user->ID ) ) {
		return;
	}
	if ( cohf_2fa_days_left( $user->ID ) > 0 ) {
		return;
	}
	global $pagenow;
	if ( in_array( $pagenow, array( 'profile.php', 'admin-post.php' ), true ) ) {
		return;
	}
	wp_safe_redirect( admin_url( 'profile.php?cohf_2fa=1#two-factor-options' ) );
	exit;
} );

/* Step-by-step guidance on every admin screen until it is done. */
add_action( 'admin_notices', function () {
	if ( ! cohf_2fa_available() ) {
		return;
	}
	$user = wp_get_current_user();
	if ( ! cohf_2fa_applies_to( $user ) || cohf_2fa_user_enabled( $user->ID ) ) {
		return;
	}
	$left = cohf_2fa_required() ? cohf_2fa_days_left( $user->ID ) : -1;
	$cls  = 0 === $left ? 'notice-error' : 'notice-warning';
	echo '<div class="notice ' . esc_attr( $cls ) . '"><p><strong>' . esc_html__( 'Set up two-step verification', 'cohf-child' ) . '</strong>';
	if ( $left > 0 ) {
		/* translators: %d: number of days. */
		echo ' - ' . esc_html( sprintf( _n( '%d day left before it is required.', '%d days left before it is required.', $left, 'cohf-child' ), $left ) );
	}
	echo '</p><ol style="margin-left:1.4em">';
	echo '<li>' . esc_html__( 'Open your profile and find "Two-Factor Options".', 'cohf-child' ) . '</li>';
	echo '<li>' . esc_html__( 'Enable "Authenticator App", scan the QR code with Google Authenticator, Microsoft Authenticator, Authy or 1Password, and enter the 6-digit code.', 'cohf-child' ) . '</li>';
	echo '<li>' . esc_html__( 'Enable "Recovery Codes", generate them and store them somewhere safe. They get you in if you lose your phone.', 'cohf-child' ) . '</li>';
	echo '<li>' . esc_html__( 'Set the primary method to "Authenticator App" and click Update Profile.', 'cohf-child' ) . '</li>';
	echo '</ol><p><a class="button button-primary" href="' . esc_url( admin_url( 'profile.php#two-factor-options' ) ) . '">' . esc_html__( 'Set it up now', 'cohf-child' ) . '</a>';
	if ( 0 === $left ) {
		echo ' <em>' . esc_html__( 'The rest of the dashboard opens once this is done.', 'cohf-child' ) . '</em>';
	}
	echo '</p></div>';
} );

/* Hide the plugin's test-only method on a live site. */
add_filter( 'two_factor_providers', function ( $providers ) {
	unset( $providers['Two_Factor_Dummy'] );
	return $providers;
} );

/* Users screen: a 2FA column so administrators can see who is protected. */
add_filter( 'manage_users_columns', function ( $columns ) {
	if ( cohf_2fa_available() && current_user_can( 'list_users' ) ) {
		$columns['cohf_2fa'] = __( '2-step', 'cohf-child' );
	}
	return $columns;
} );
add_filter( 'manage_users_custom_column', function ( $output, $column, $user_id ) {
	if ( 'cohf_2fa' !== $column ) {
		return $output;
	}
	return cohf_2fa_user_enabled( $user_id )
		? '<span style="color:#1a7f37;font-weight:600">' . esc_html__( 'On', 'cohf-child' ) . '</span>'
		: '<span style="color:#b32d2e;font-weight:600">' . esc_html__( 'Off', 'cohf-child' ) . '</span>';
}, 10, 3 );
