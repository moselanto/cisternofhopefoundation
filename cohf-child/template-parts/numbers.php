<?php
/**
 * Impact figures. Prototype markup: .numbers > .number > strong + span
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;

$figures = cohf_impact_figures();
$outreach = function_exists( 'cohf_outreach_figures' ) ? cohf_outreach_figures() : array();
if ( ! empty( $outreach ) ) {
	$figures = array_merge( $figures, array_slice( $outreach, 0, 1 ) );
}
$count = count( $figures );
?>
<div class="numbers<?php echo ( 4 === $count || 6 === $count ) ? ' numbers--' . (int) $count : ''; ?>">
	<?php
	foreach ( $figures as $figure ) {
		$prefix = isset( $figure['prefix'] ) ? $figure['prefix'] : '';
		printf(
			'<div class="number"><strong>%1$s</strong><span>%2$s</span></div>',
			esc_html( $prefix . number_format_i18n( (float) $figure['value'] ) ),
			esc_html( $figure['label'] )
		);
	}
	?>
</div>
