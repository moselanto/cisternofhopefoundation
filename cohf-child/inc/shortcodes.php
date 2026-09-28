<?php
/**
 * Shortcodes for the dynamic parts of a page.
 *
 * Most page copy is prose and converts cleanly to blocks. Some sections are
 * not copy at all - the enquiry form carries a nonce and a honeypot, the
 * partner list is a live query, the programme grid follows the Programmes
 * content type. Converting those to static blocks would silently break them.
 *
 * These shortcodes let a block-built page keep the working section. An editor
 * places it in the editor like any other block and the theme renders the real
 * thing on the front end.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render a template part and return it as a string.
 *
 * @param string $slug Template part slug, relative to the theme.
 * @param array  $args Arguments passed to the part.
 * @return string
 */
function cohf_shortcode_part( $slug, $args = array() ) {
	ob_start();
	get_template_part( $slug, null, $args );
	return (string) ob_get_clean();
}

/**
 * [cohf_enquiry_form type="partnership"]
 *
 * @param array $atts Attributes.
 * @return string
 */
function cohf_sc_enquiry_form( $atts ) {
	$atts = shortcode_atts( array( 'type' => 'general' ), $atts, 'cohf_enquiry_form' );

	$types = function_exists( 'cohf_enquiry_types' ) ? cohf_enquiry_types() : array();
	$type  = sanitize_key( $atts['type'] );

	// An unknown type would render a form with nothing preselected.
	if ( $types && ! isset( $types[ $type ] ) ) {
		$type = 'general';
	}

	return cohf_shortcode_part( 'template-parts/enquiry-form', array( 'default_type' => $type ) );
}
add_shortcode( 'cohf_enquiry_form', 'cohf_sc_enquiry_form' );

/**
 * [cohf_partners] - confirmed partners, or the honest empty state.
 *
 * @return string
 */
function cohf_sc_partners() {
	return cohf_shortcode_part( 'template-parts/partner-section' );
}
add_shortcode( 'cohf_partners', 'cohf_sc_partners' );

/**
 * [cohf_image key="programme-09"] - an image from the theme's own library.
 *
 * @param array $atts Attributes.
 * @return string
 */
function cohf_sc_image( $atts ) {
	$atts = shortcode_atts( array(
		'key'   => '',
		'sizes' => '(max-width: 60em) 100vw, 50vw',
	), $atts, 'cohf_image' );

	if ( ! $atts['key'] || ! function_exists( 'cohf_the_image' ) ) {
		return '';
	}

	ob_start();
	cohf_the_image( sanitize_key( $atts['key'] ), array( 'sizes' => $atts['sizes'] ) );
	return (string) ob_get_clean();
}
add_shortcode( 'cohf_image', 'cohf_sc_image' );

/**
 * Simple template-part shortcodes with no arguments.
 *
 * Registered in a loop because they differ only by which part they render.
 *
 * @return array<string,string> Shortcode tag => template part slug.
 */
function cohf_simple_part_shortcodes() {
	return array(
		'cohf_impact_numbers'   => 'template-parts/numbers',
		'cohf_timeline'         => 'template-parts/timeline',
		'cohf_approach'         => 'template-parts/approach',
		'cohf_purpose'          => 'template-parts/purpose',
		'cohf_theory_of_change' => 'template-parts/theory-of-change',
		'cohf_cta'              => 'template-parts/cta',
		'cohf_giving_form'      => 'template-parts/giving-form',
	);
}

foreach ( cohf_simple_part_shortcodes() as $cohf_tag => $cohf_slug ) {
	add_shortcode(
		$cohf_tag,
		static function () use ( $cohf_slug ) {
			return cohf_shortcode_part( $cohf_slug );
		}
	);
}
unset( $cohf_tag, $cohf_slug );

/**
 * [cohf_programmes count="12"] - the programme card grid.
 *
 * @param array $atts Attributes.
 * @return string
 */
function cohf_sc_programmes( $atts ) {
	$atts = shortcode_atts( array( 'count' => 12 ), $atts, 'cohf_programmes' );

	$query = new WP_Query( array(
		'post_type'      => 'cohf_programme',
		'posts_per_page' => max( 1, (int) $atts['count'] ),
		'orderby'        => array(
			'menu_order' => 'ASC',
			'title'      => 'ASC',
		),
		'no_found_rows'  => true,
	) );

	if ( ! $query->have_posts() ) {
		return '';
	}

	ob_start();
	echo '<div class="grid">';
	while ( $query->have_posts() ) {
		$query->the_post();
		get_template_part( 'template-parts/programme-card' );
	}
	echo '</div>';
	wp_reset_postdata();

	return (string) ob_get_clean();
}
add_shortcode( 'cohf_programmes', 'cohf_sc_programmes' );
