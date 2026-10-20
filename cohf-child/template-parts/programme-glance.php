<?php
/**
 * "At a glance" answer block for a programme (AEO).
 *
 * A short, plain answer near the top of the page, followed by key facts.
 * Search engines and AI assistants quote this kind of block; visitors get
 * the essentials without scrolling. Every value comes from the programme's
 * own fields; empty fields are simply not shown, so nothing is invented.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

$cohf_answer = cohf_field( 'answer' );
if ( '' === $cohf_answer ) {
	$cohf_answer = cohf_field( 'purpose' );
}

$cohf_reviewed = cohf_field( 'reviewed' );
$cohf_rows     = array_filter(
	array(
		__( 'Who it serves', 'cohf-child' )       => cohf_field( 'who_serves' ),
		__( 'Where it runs', 'cohf-child' )       => cohf_field( 'areas' ),
		__( 'How it is delivered', 'cohf-child' ) => cohf_field( 'model' ),
		__( 'Status', 'cohf-child' )              => cohf_field( 'status' ),
	),
	static function ( $value ) {
		return '' !== trim( (string) $value );
	}
);

if ( '' === trim( $cohf_answer ) && ! $cohf_rows ) {
	return;
}
?>
<section class="programme-glance" aria-labelledby="programme-glance-title">
	<div class="container">
		<div class="programme-glance__card">
			<h2 id="programme-glance-title" class="programme-glance__title"><?php esc_html_e( 'At a glance', 'cohf-child' ); ?></h2>
			<?php if ( '' !== trim( $cohf_answer ) ) : ?>
				<p class="programme-glance__answer"><?php echo esc_html( $cohf_answer ); ?></p>
			<?php endif; ?>
			<?php if ( $cohf_rows ) : ?>
				<dl class="programme-glance__facts">
					<?php foreach ( $cohf_rows as $cohf_label => $cohf_value ) : ?>
						<div>
							<dt><?php echo esc_html( $cohf_label ); ?></dt>
							<dd><?php echo esc_html( $cohf_value ); ?></dd>
						</div>
					<?php endforeach; ?>
				</dl>
			<?php endif; ?>
			<?php if ( $cohf_reviewed && strtotime( $cohf_reviewed ) ) : ?>
				<p class="programme-glance__reviewed">
					<?php
					printf(
						/* translators: %s: date. */
						esc_html__( 'Facts checked by the Foundation on %s.', 'cohf-child' ),
						'<time datetime="' . esc_attr( gmdate( 'Y-m-d', strtotime( $cohf_reviewed ) ) ) . '">' . esc_html( date_i18n( get_option( 'date_format' ), strtotime( $cohf_reviewed ) ) ) . '</time>'
					);
					?>
				</p>
			<?php endif; ?>
		</div>
	</div>
</section>
