<?php
/**
 * Accessibility: WCAG 2.1 AA-conscious defaults.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add explicit "current page" semantics to menu items for screen readers.
 *
 * @param array    $atts Anchor attributes.
 * @param WP_Post  $item Menu item.
 * @return array
 */
function cohf_nav_aria_current( $atts, $item ) {
	if ( ! empty( $item->current ) ) {
		$atts['aria-current'] = 'page';
	} elseif ( ! empty( $item->current_item_ancestor ) || ! empty( $item->current_item_parent ) ) {
		$atts['aria-current'] = 'true';
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'cohf_nav_aria_current', 10, 2 );

/**
 * Warn editors in the admin when an uploaded image has no alternative text.
 * Alt text is a safeguarding and accessibility requirement, not an extra.
 */
function cohf_alt_text_notice() {
	$screen = get_current_screen();
	if ( ! $screen || 'upload' !== $screen->id ) {
		return;
	}
	printf(
		'<div class="notice notice-info"><p><strong>%s</strong> %s</p></div>',
		esc_html__( 'Alternative text:', 'cohf-child' ),
		esc_html__( 'Every photograph published on this site must have alternative text that describes the image respectfully and does not identify a child or vulnerable person without consent.', 'cohf-child' )
	);
}
add_action( 'admin_notices', 'cohf_alt_text_notice' );

/**
 * Give every content image an alt attribute, even if empty, so screen readers
 * skip decorative images rather than announcing a filename.
 *
 * @param string $content Post content.
 * @return string
 */
function cohf_ensure_alt_attribute( $content ) {
	if ( ! $content || strpos( $content, '<img' ) === false ) {
		return $content;
	}
	return preg_replace( '/<img((?![^>]*\balt=)[^>]*)>/i', '<img$1 alt="">', $content );
}
add_filter( 'the_content', 'cohf_ensure_alt_attribute', 20 );

/**
 * Accessible "read more" links: append the post title for screen readers.
 *
 * @param string $more Read more markup.
 * @return string
 */
function cohf_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'cohf_excerpt_more' );

/**
 * Print a screen-reader-only suffix naming the target of a generic link.
 *
 * @param string $label Visible label.
 * @param string $context Item title.
 */
function cohf_link_context( $label, $context ) {
	printf(
		'%s<span class="screen-reader-text"> %s</span>',
		esc_html( $label ),
		esc_html( $context )
	);
}

/**
 * Landmark-friendly comment/search defaults: the theme uses no comments,
 * so remove the UI entirely rather than leaving empty landmarks behind.
 */
add_action( 'init', function () {
	remove_post_type_support( 'page', 'comments' );
	remove_post_type_support( 'page', 'trackbacks' );
} );

/**
 * Ensure iframes (maps, embedded video) carry a title for assistive tech.
 *
 * @param string $html Embed markup.
 * @return string
 */
function cohf_iframe_title( $html ) {
	if ( strpos( $html, '<iframe' ) === false || strpos( $html, 'title=' ) !== false ) {
		return $html;
	}
	return str_replace( '<iframe', '<iframe title="' . esc_attr__( 'Embedded content', 'cohf-child' ) . '"', $html );
}
add_filter( 'embed_oembed_html', 'cohf_iframe_title' );
add_filter( 'video_embed_html', 'cohf_iframe_title' );
