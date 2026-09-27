<?php
/**
 * Five-year journey. Prototype markup: .timeline > .year > h3 + b + p
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;

$years = array(
	array( '2026', __( 'Establish', 'cohf-child' ),      __( 'Institutional systems, safeguarding, documentation and donor readiness.', 'cohf-child' ) ),
	array( '2027', __( 'Consolidate', 'cohf-child' ),    __( 'Priority programmes, partnerships and reporting.', 'cohf-child' ) ),
	array( '2028', __( 'Scale and review', 'cohf-child' ), __( 'Evidence, technical capacity and expansion.', 'cohf-child' ) ),
	array( '2029', __( 'Deepen', 'cohf-child' ),         __( 'Market linkages, partnerships and sustainability.', 'cohf-child' ) ),
	array( '2030', __( 'Sustain', 'cohf-child' ),        __( 'Evaluate results and prepare the next direction.', 'cohf-child' ) ),
);
?>
<div class="timeline">
	<?php
	foreach ( $years as $year ) {
		printf(
			'<div class="year"><h3>%1$s</h3><b>%2$s</b><p>%3$s</p></div>',
			esc_html( $year[0] ),
			esc_html( $year[1] ),
			esc_html( $year[2] )
		);
	}
	?>
</div>
