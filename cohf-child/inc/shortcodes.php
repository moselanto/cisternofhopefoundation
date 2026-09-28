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
 * [cohf_cta title="" text=""] - the closing call to action.
 *
 * Takes attributes because Programmes overrides the wording. Only non-empty
 * attributes are passed through, so a bare [cohf_cta] keeps the template
 * part's own defaults rather than blanking the heading.
 *
 * @param array $atts Attributes.
 * @return string
 */
function cohf_sc_cta( $atts ) {
	$atts = shortcode_atts( array(
		'title' => '',
		'text'  => '',
	), $atts, 'cohf_cta' );

	$args = array_filter( $atts, static function ( $value ) {
		return '' !== trim( (string) $value );
	} );

	return cohf_shortcode_part( 'template-parts/cta', $args );
}
add_shortcode( 'cohf_cta', 'cohf_sc_cta' );

/**
 * [cohf_contact_details] - postal address, phone, email and location.
 *
 * Seeded into the Contact page rather than flattened into text on purpose.
 * These values already have one home, Foundation > Organisation details, and
 * copying them into block content would mean a phone number change silently
 * failing to reach the Contact page.
 *
 * @return string
 */
function cohf_sc_contact_details() {
	if ( ! function_exists( 'cohf_org' ) ) {
		return '';
	}

	$org  = cohf_org();
	$tel  = preg_replace( '/[^0-9+]/', '', $org['phone'] );
	$rows = array();

	$rows[] = '<p><b>' . esc_html( strtoupper( $org['name'] ) ) . '</b></p>';

	$rows[] = '<p><b>' . esc_html__( 'Postal address', 'cohf-child' ) . '</b><br>'
		. esc_html( $org['address'] ) . '</p>';

	$rows[] = '<p><b>' . esc_html__( 'Phone', 'cohf-child' ) . '</b><br>'
		. '<a href="tel:' . esc_attr( $tel ) . '">' . esc_html( $org['phone'] ) . '</a></p>';

	$rows[] = '<p><b>' . esc_html__( 'Email', 'cohf-child' ) . '</b><br>'
		. '<a href="mailto:' . esc_attr( $org['email'] ) . '">' . esc_html( $org['email'] ) . '</a></p>';

	$rows[] = '<p><b>' . esc_html__( 'Where we work', 'cohf-child' ) . '</b><br>'
		. esc_html__( 'Uthiru, Nairobi, and communities across Kenya.', 'cohf-child' ) . '</p>';

	return implode( "\n", $rows );
}
add_shortcode( 'cohf_contact_details', 'cohf_sc_contact_details' );

/**
 * [cohf_programmes count="12"] - the programme card grid.
 *
 * @param array $atts Attributes.
 * @return string
 */
function cohf_sc_programmes( $atts ) {
	$atts = shortcode_atts( array(
		'count'  => 12,
		'filter' => 'no',
	), $atts, 'cohf_programmes' );

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

	// Unique ids so two grids on one page cannot cross-wire their filters.
	static $instance = 0;
	++$instance;

	$list_id   = 'programme-list-' . $instance;
	$status_id = 'programme-filter-status-' . $instance;
	$want_bar  = in_array( strtolower( (string) $atts['filter'] ), array( 'yes', 'true', '1' ), true );

	ob_start();

	if ( $want_bar ) {
		$audiences = get_terms( array(
			'taxonomy'   => 'cohf_audience',
			'hide_empty' => true,
		) );

		if ( ! empty( $audiences ) && ! is_wp_error( $audiences ) ) {
			printf(
				'<div class="filter-bar" data-filter-group data-filter-target="#%1$s" data-filter-status="#%2$s" role="group" aria-label="%3$s">',
				esc_attr( $list_id ),
				esc_attr( $status_id ),
				esc_attr__( 'Filter programmes by audience', 'cohf-child' )
			);

			printf(
				'<button type="button" data-filter="all" aria-pressed="true">%s</button>',
				esc_html__( 'All programmes', 'cohf-child' )
			);

			foreach ( $audiences as $audience ) {
				printf(
					'<button type="button" data-filter="%1$s" aria-pressed="false">%2$s</button>',
					esc_attr( $audience->slug ),
					esc_html( $audience->name )
				);
			}

			echo '</div>';

			printf(
				'<p id="%s" class="screen-reader-text" role="status"></p>',
				esc_attr( $status_id )
			);
		}
	}

	printf( '<div class="grid" id="%s">', esc_attr( $list_id ) );
	while ( $query->have_posts() ) {
		$query->the_post();
		get_template_part( 'template-parts/programme-card' );
	}
	echo '</div>';
	wp_reset_postdata();

	return (string) ob_get_clean();
}
add_shortcode( 'cohf_programmes', 'cohf_sc_programmes' );
