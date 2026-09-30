<?php
/**
 * Customizer: every bundled photograph and every reported figure can be
 * changed from Appearance > Customize, with a live preview, without code.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the Foundation's Customizer panels.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function cohf_customize_register( $wp_customize ) {
	$wp_customize->add_panel( 'cohf_panel', array(
		'title'    => __( 'Cistern of Hope content', 'cohf-child' ),
		'priority' => 30,
	) );

	// Site photographs.
	$wp_customize->add_section( 'cohf_photos', array(
		'title'       => __( 'Site photographs', 'cohf-child' ),
		'panel'       => 'cohf_panel',
		'description' => __( 'Replace any photograph used on the website. Choose an image from the Media Library, or remove it to go back to the original.', 'cohf-child' ),
	) );
	foreach ( cohf_image_library() as $key => $image ) {
		$id = 'cohf_image_' . str_replace( '-', '_', $key );
		$wp_customize->add_setting( $id, array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'capability'        => 'edit_theme_options',
		) );
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $id, array(
			'label'       => ucwords( str_replace( '-', ' ', $key ) ),
			'description' => isset( $image['alt'] ) ? $image['alt'] : '',
			'section'     => 'cohf_photos',
			'mime_type'   => 'image',
		) ) );
	}

	// Impact figures.
	$wp_customize->add_section( 'cohf_figures', array(
		'title'       => __( 'Impact figures', 'cohf-child' ),
		'panel'       => 'cohf_panel',
		'description' => __( 'The numbers shown on the homepage and Impact page. Leave a field empty to keep the current wording. Only publish verified figures.', 'cohf-child' ),
	) );
	$groups = array(
		'impact'   => array( __( 'Reported reach', 'cohf-child' ), cohf_impact_figures_default() ),
		'outreach' => array( __( 'Community programme', 'cohf-child' ), cohf_outreach_figures_default() ),
	);
	foreach ( $groups as $group => $info ) {
		foreach ( $info[1] as $i => $figure ) {
			$fields = array(
				'value'  => __( 'Number', 'cohf-child' ),
				'prefix' => __( 'Before the number (e.g. ~ or +)', 'cohf-child' ),
				'label'  => __( 'Label', 'cohf-child' ),
				'note'   => __( 'Note', 'cohf-child' ),
			);
			foreach ( $fields as $field => $label ) {
				$id = 'cohf_fig_' . $group . '_' . $i . '_' . $field;
				$wp_customize->add_setting( $id, array(
					'default'           => '',
					'sanitize_callback' => ( 'value' === $field ) ? 'cohf_sanitize_number_text' : 'sanitize_text_field',
					'capability'        => 'edit_theme_options',
				) );
				$current = isset( $figure[ $field ] ) ? (string) $figure[ $field ] : '';
				$wp_customize->add_control( $id, array(
					'label'       => sprintf( '%1$s %2$d: %3$s', $info[0], $i + 1, $label ),
					'description' => '' === $current ? '' : sprintf( /* translators: %s: current value. */ __( 'Now: %s', 'cohf-child' ), $current ),
					'section'     => 'cohf_figures',
					'type'        => 'text',
				) );
			}
		}
	}
}
add_action( 'customize_register', 'cohf_customize_register' );

/**
 * Keep only a number (digits and one decimal point).
 *
 * @param string $value Raw value.
 * @return string
 */
function cohf_sanitize_number_text( $value ) {
	$value = preg_replace( '/[^0-9.]/', '', (string) $value );
	return is_numeric( $value ) ? $value : '';
}
