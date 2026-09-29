<?php
/**
 * Cistern of Hope Foundation — child theme bootstrap.
 *
 * Load order matters: performance.php registers assets, security.php hardens
 * output and form handling, custom-post-types.php registers content structures.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

define( 'COHF_CHILD_VERSION', '9.45.0' );
define( 'COHF_CHILD_DIR', get_stylesheet_directory() );
define( 'COHF_CHILD_URI', get_stylesheet_directory_uri() );

/**
 * Include a module from /inc.
 *
 * @param string $file Filename without extension.
 */
function cohf_require( $file ) {
	$path = COHF_CHILD_DIR . '/inc/' . $file . '.php';
	if ( is_readable( $path ) ) {
		require_once $path;
	}
}

foreach ( array(
	'theme-functions',
	'nav-walker',
	'nav-structure',
	'mobile-actions',
	'leader-photos',
	'media',
	'performance',
	'security',
	'accessibility',
	'seo',
	'redirects',
	'page-body',
	'block-patterns',
	'shortcodes',
	'page-seeds',
	'custom-post-types',
	'custom-fields',
	'admin-experience',
	'content-defaults',
	'forms',
	'giving',
	'shop',
	'template-tags',
) as $cohf_module ) {
	cohf_require( $cohf_module );
}
unset( $cohf_module );
