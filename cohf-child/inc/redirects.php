<?php
/**
 * Legacy URL redirects.
 *
 * Pages on this site have been reorganised since launch, and at least one old
 * address was captured and shared externally before the move:
 * /partners/overview now 404s, while the page itself lives at
 * /partners-overview/. A funder or partner following an old link, a bookmark
 * or a PDF reaches a dead end.
 *
 * Rather than hard-code destination URLs - which would break again the next
 * time a slug changes - each legacy path is mapped to the page template that
 * owns the content, and the destination is resolved at request time from
 * whichever page currently uses that template.
 *
 * Only runs on a 404, so it can never shadow a real page.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Legacy path to page-template map.
 *
 * Keys are paths relative to the site root, without leading or trailing
 * slashes, lower-cased. Add to this whenever a page slug changes.
 *
 * @return array<string,string>
 */
function cohf_legacy_paths() {
	return array(
		'partners/overview'      => 'page-templates/page-partners.php',
		'partners/partner'       => 'page-templates/page-partners.php',
		'about/overview'         => 'page-templates/page-about.php',
		'impact/overview'        => 'page-templates/page-impact.php',
		'programmes/overview'    => 'page-templates/page-programmes.php',
		'get-involved/overview'  => 'page-templates/page-get-involved.php',
		'support/overview'       => 'page-templates/page-support.php',
		'accountability/overview' => 'page-templates/page-accountability.php',
		'strategy/overview'      => 'page-templates/page-strategy.php',
		'leadership/overview'    => 'page-templates/page-leadership.php',
		'resources/overview'     => 'page-templates/page-resources.php',
		'contact/overview'       => 'page-templates/page-contact.php',
	);
}

/**
 * Send a permanent redirect when a known legacy path 404s.
 */
function cohf_redirect_legacy_paths() {
	if ( ! is_404() || is_admin() || wp_doing_ajax() ) {
		return;
	}

	$path = wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '', PHP_URL_PATH );

	if ( ! $path ) {
		return;
	}

	// Normalise: strip the site's subdirectory, any index.php segment, and
	// surrounding slashes, so /index.php/partners/overview/ and
	// /partners/overview both match the same key.
	$home_path = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
	if ( $home_path && 0 === strpos( $path, $home_path ) ) {
		$path = substr( $path, strlen( $home_path ) );
	}

	$path = preg_replace( '#^index\.php/#', '', trim( (string) $path, '/' ) );
	$path = strtolower( trim( (string) $path, '/' ) );

	if ( '' === $path ) {
		return;
	}

	$map = cohf_legacy_paths();

	if ( ! isset( $map[ $path ] ) ) {
		return;
	}

	$target = function_exists( 'cohf_page_url' ) ? cohf_page_url( $map[ $path ] ) : '';

	// cohf_page_url() falls back to the home URL when no page uses the
	// template. Redirecting a 404 to the home page is worse than showing the
	// 404, because it hides the fact that the content is missing.
	if ( ! $target || untrailingslashit( $target ) === untrailingslashit( home_url( '/' ) ) ) {
		return;
	}

	wp_safe_redirect( $target, 301 );
	exit;
}
add_action( 'template_redirect', 'cohf_redirect_legacy_paths' );
