<?php
/**
 * Branded, international and secure staff login (wp-login.php).
 *
 * Design: split screen. A photograph of the Foundation's own work with a
 * short welcome on the left, a clean sign-in card on the right. On phones
 * the photo becomes a slim banner above the card.
 *
 * International: every string is translatable (text domain cohf-child),
 * the core language switcher is kept, and layout uses logical properties
 * so right-to-left languages mirror correctly.
 *
 * Security (works with inc/security.php and inc/two-factor.php):
 * - honeypot on login and lost-password forms (bots are refused silently);
 * - the same answer whether or not an account exists (no enumeration);
 * - login lockout (5 fails / 15 min) and reset rate limits live in
 *   security.php and anti-spam.php;
 * - two-step verification screen styled to match.
 *
 * Wording can be changed with the filters cohf_login_* without editing code.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Login-page wording, filterable.
 *
 * @return array<string,mixed>
 */
function cohf_login_text() {
	$org = function_exists( 'cohf_org' ) ? cohf_org() : array();
	return apply_filters(
		'cohf_login_text',
		array(
			'tag'    => __( 'Staff & partner portal', 'cohf-child' ),
			'title'  => isset( $org['motto'] ) && $org['motto'] ? $org['motto'] : __( 'Together for a lasting change.', 'cohf-child' ),
			'intro'  => __( 'Sign in to look after the programmes, stories, giving records and Hope Market of Cistern of Hope Foundation.', 'cohf-child' ),
			'points' => array(
				__( 'Publish programmes, impact stories and news', 'cohf-child' ),
				__( 'Review verified donations and shop orders', 'cohf-child' ),
				__( 'Protected by two-step verification', 'cohf-child' ),
			),
		)
	);
}

/** Current wp-login.php action. */
function cohf_login_action() {
	return isset( $_REQUEST['action'] ) ? sanitize_key( wp_unslash( $_REQUEST['action'] ) ) : 'login'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only.
}

/* ---------- Assets ---------- */
add_action( 'login_enqueue_scripts', function () {
	$dir = COHF_CHILD_DIR . '/assets/';
	$uri = COHF_CHILD_URI . '/assets/';

	wp_enqueue_style( 'cohf-login', $uri . 'css/login.css', array( 'login' ), file_exists( $dir . 'css/login.css' ) ? (string) filemtime( $dir . 'css/login.css' ) : COHF_CHILD_VERSION );

	$css = '';
	foreach ( array(
		array( 'Inter', 'inter-variable.woff2', '100 900' ),
		array( 'Playfair Display', 'playfair-display-variable.woff2', '400 900' ),
	) as $font ) {
		if ( file_exists( $dir . 'fonts/' . $font[1] ) ) {
			$css .= sprintf( '@font-face{font-family:"%1$s";src:url("%2$s") format("woff2");font-weight:%3$s;font-display:swap;}', esc_attr( $font[0] ), esc_url( $uri . 'fonts/' . $font[1] ), esc_attr( $font[2] ) );
		}
	}
	$photo = (string) apply_filters( 'cohf_login_photo', file_exists( $dir . 'images/hero-home.jpg' ) ? $uri . 'images/hero-home.jpg' : '' );
	if ( $photo ) {
		$css .= 'body.cohf-login{--cohf-login-photo:url("' . esc_url( $photo ) . '")}';
	}
	if ( file_exists( $dir . 'images/brand/logo-mark@2x.png' ) ) {
		$css .= 'body.cohf-login{--cohf-login-mark:url("' . esc_url( $uri . 'images/brand/logo-mark@2x.png' ) . '")}';
	}
	wp_add_inline_style( 'cohf-login', $css );

	wp_enqueue_script( 'cohf-login', $uri . 'js/login.js', array(), file_exists( $dir . 'js/login.js' ) ? (string) filemtime( $dir . 'js/login.js' ) : COHF_CHILD_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_localize_script( 'cohf-login', 'cohfLogin', array(
		'caps'    => __( 'Caps Lock is on', 'cohf-child' ),
		'signing' => __( 'Signing in…', 'cohf-child' ),
		'sending' => __( 'Sending…', 'cohf-child' ),
		'verify'  => __( 'Verifying…', 'cohf-child' ),
	) );
} );

add_filter( 'login_body_class', function ( $classes ) {
	$classes[] = 'cohf-login';
	$classes[] = 'cohf-login--' . sanitize_html_class( cohf_login_action() );
	return $classes;
} );
add_filter( 'login_headerurl', function () {
	return home_url( '/' );
} );
add_filter( 'login_headertext', function () {
	return get_bloginfo( 'name' );
} );
add_filter( 'login_title', function ( $title ) {
	/* translators: %s: site name. */
	return sprintf( __( 'Staff sign in | %s', 'cohf-child' ), get_bloginfo( 'name' ) );
} );

/* ---------- Left panel ---------- */
add_action( 'login_header', function () {
	$t     = cohf_login_text();
	$org   = function_exists( 'cohf_org' ) ? cohf_org() : array();
	$name  = get_bloginfo( 'name' );
	$mark  = COHF_CHILD_DIR . '/assets/images/brand/logo-mark@2x.png';
	$phone = isset( $org['phone'] ) ? (string) $org['phone'] : '';
	$email = isset( $org['email'] ) ? (string) $org['email'] : '';
	?>
	<aside class="cohf-lg-aside">
		<a class="cohf-lg-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php if ( file_exists( $mark ) ) : ?>
				<img src="<?php echo esc_url( COHF_CHILD_URI . '/assets/images/brand/logo-mark@2x.png' ); ?>" alt="" width="44" height="44">
			<?php endif; ?>
			<span><?php echo esc_html( $name ); ?></span>
		</a>
		<div class="cohf-lg-aside__body">
			<span class="cohf-lg-tag"><?php echo esc_html( $t['tag'] ); ?></span>
			<p class="cohf-lg-title"><?php echo esc_html( $t['title'] ); ?></p>
			<p class="cohf-lg-intro"><?php echo esc_html( $t['intro'] ); ?></p>
			<?php if ( ! empty( $t['points'] ) ) : ?>
				<ul class="cohf-lg-points">
					<?php foreach ( (array) $t['points'] as $point ) : ?>
						<li><?php echo esc_html( $point ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<?php if ( $phone || $email ) : ?>
			<p class="cohf-lg-help">
				<span><?php esc_html_e( 'Need access or help?', 'cohf-child' ); ?></span>
				<?php if ( $phone ) : ?>
					<a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
				<?php endif; ?>
				<?php if ( $email && is_email( $email ) ) : ?>
					<a href="<?php echo esc_url( 'mailto:' . $email ); ?>"><?php echo esc_html( $email ); ?></a>
				<?php endif; ?>
			</p>
		<?php endif; ?>
	</aside>
	<?php
} );

/* ---------- Heading above each form ---------- */
add_filter( 'login_message', function ( $message ) {
	$action = cohf_login_action();
	$head   = '';

	if ( in_array( $action, array( 'validate_2fa', 'backup_2fa', 'revalidate_2fa' ), true ) || did_action( 'wp_login' ) ) {
		$head = array( __( 'Two-step verification', 'cohf-child' ), __( 'Enter the 6-digit code from your authenticator app, or use a recovery code.', 'cohf-child' ) );
	} elseif ( 'lostpassword' === $action || 'retrievepassword' === $action ) {
		$head = array( __( 'Reset your password', 'cohf-child' ), __( 'Enter your username or email address. If an account exists, we will email a secure reset link.', 'cohf-child' ) );
	} elseif ( 'rp' === $action || 'resetpass' === $action ) {
		$head = array( __( 'Choose a new password', 'cohf-child' ), __( 'Use at least 12 characters. A short sentence you will remember works well.', 'cohf-child' ) );
	} elseif ( in_array( $action, array( 'login', '' ), true ) && ! isset( $_GET['checkemail'] ) && ! isset( $_GET['loggedout'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only.
		$head = array( __( 'Welcome back', 'cohf-child' ), __( 'Sign in to manage the Foundation website.', 'cohf-child' ) );
	} elseif ( isset( $_GET['loggedout'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only.
		$head = array( __( 'You are signed out', 'cohf-child' ), __( 'Thank you for your work today. Sign in again any time.', 'cohf-child' ) );
	}

	if ( $head ) {
		$message = '<div class="cohf-lg-head"><h2>' . esc_html( $head[0] ) . '</h2><p>' . esc_html( $head[1] ) . '</p></div>' . $message;
	}
	return $message;
} );

/* ---------- Footer under the card ---------- */
add_action( 'login_footer', function () {
	$privacy = get_privacy_policy_url();
	echo '<div class="cohf-lg-foot">';
	echo '<p class="cohf-lg-secure"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>'
		. esc_html__( 'Encrypted connection. Two-step verification protects every staff account. Repeated failed attempts are paused for 15 minutes.', 'cohf-child' ) . '</p>';
	echo '<p class="cohf-lg-links"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Back to the website', 'cohf-child' ) . '</a>';
	if ( $privacy ) {
		echo '<a href="' . esc_url( $privacy ) . '">' . esc_html__( 'Privacy', 'cohf-child' ) . '</a>';
	}
	echo '</p><p class="cohf-lg-copy">&copy; ' . esc_html( wp_date( 'Y' ) ) . ' ' . esc_html( get_bloginfo( 'name' ) ) . '</p></div>';
} );

/* ---------- Honeypot ---------- */
function cohf_login_honeypot_field() {
	echo '<p class="cohf-lg-hp" aria-hidden="true"><label>' . esc_html__( 'Leave this field empty', 'cohf-child' ) . '<input type="text" name="cohf_lg_website" value="" tabindex="-1" autocomplete="off"></label></p>';
}
add_action( 'login_form', 'cohf_login_honeypot_field' );
add_action( 'lostpassword_form', 'cohf_login_honeypot_field' );

function cohf_login_honeypot_tripped() {
	return isset( $_POST['cohf_lg_website'] ) && '' !== trim( (string) wp_unslash( $_POST['cohf_lg_website'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- only checked for emptiness.
}

add_filter( 'authenticate', function ( $user ) {
	if ( cohf_login_honeypot_tripped() ) {
		return new WP_Error( 'cohf_bot', __( 'The details entered were not correct.', 'cohf-child' ) );
	}
	return $user;
}, 25 );

add_action( 'lostpassword_post', function ( $errors ) {
	if ( cohf_login_honeypot_tripped() && is_wp_error( $errors ) ) {
		$errors->add( 'cohf_bot', __( 'Request blocked.', 'cohf-child' ) );
	}
} );

/* ---------- No account enumeration on lost password ---------- */
add_filter( 'lostpassword_errors', function ( $errors ) {
	if ( is_wp_error( $errors ) && ( $errors->get_error_message( 'invalid_email' ) || $errors->get_error_message( 'invalidcombo' ) ) ) {
		wp_safe_redirect( add_query_arg( 'checkemail', 'confirm', wp_login_url() ) );
		exit;
	}
	return $errors;
} );

/* ---------- Never cache the login screen ---------- */
add_action( 'login_init', function () {
	nocache_headers();
	header( 'X-LiteSpeed-Cache-Control: no-cache' );
} );
