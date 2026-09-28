<?php
/**
 * In-page section navigation.
 *
 * Several pages (Partners, Impact, Accountability, Strategy, Support) are long
 * single-scroll documents. Without a way in, the only route to a section near
 * the bottom - the enquiry form, the complaints procedure, the giving options -
 * is to read past everything above it. This renders a horizontally scrollable
 * row of anchor chips directly under the hero.
 *
 * It is a real <nav> with an accessible name, and the links are ordinary
 * same-page anchors, so it degrades to a plain list of links without CSS or JS.
 * assets/js/main.js upgrades it with scroll-spy where JS is available.
 *
 * Usage:
 *   get_template_part( 'template-parts/section-nav', null, array(
 *       'sections' => array( '#give' => __( 'Ways to give', 'cohf-child' ) ),
 *   ) );
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

$cohf_nav_args = wp_parse_args( $args ?? array(), array(
	'sections' => array(),
	'label'    => __( 'On this page', 'cohf-child' ),
) );

if ( empty( $cohf_nav_args['sections'] ) ) {
	return;
}
?>
<nav class="section-nav" data-section-nav aria-label="<?php echo esc_attr( $cohf_nav_args['label'] ); ?>">
	<div class="container">
		<ul class="section-nav__list">
			<?php foreach ( $cohf_nav_args['sections'] as $cohf_href => $cohf_label ) : ?>
				<li>
					<a href="<?php echo esc_attr( $cohf_href ); ?>"><?php echo esc_html( $cohf_label ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</nav>
