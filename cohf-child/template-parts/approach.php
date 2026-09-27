<?php
/**
 * Pathway steps. Prototype markup: .steps > .step > .num + h3 + p
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="steps">
	<?php
	foreach ( cohf_approach_steps() as $step ) {
		printf(
			'<div class="step"><span class="num">%1$s</span><h3>%2$s</h3><p>%3$s</p></div>',
			esc_html( $step['num'] ),
			esc_html( $step['title'] ),
			esc_html( $step['text'] )
		);
	}
	?>
</div>
