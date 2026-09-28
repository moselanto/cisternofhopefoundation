<?php
/**
 * Confirmed partners. Prototype markup: section.cream > .container > .section-head + .grid
 *
 * Only partners explicitly confirmed by the Foundation are ever rendered; no
 * placeholder logos are invented.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;

$partners = function_exists( 'cohf_confirmed_partners' ) ? cohf_confirmed_partners() : array();
?>
<section class="cream" id="our-partners">
	<div class="container">
		<div class="section-head">
			<div>
				<div class="kicker"><?php esc_html_e( 'Our partners', 'cohf-child' ); ?></div>
				<h2><?php esc_html_e( 'Working alongside others.', 'cohf-child' ); ?></h2>
			</div>
			<p><?php esc_html_e( 'Partners are listed here once a relationship is confirmed in writing.', 'cohf-child' ); ?></p>
		</div>

		<?php if ( ! empty( $partners ) ) : ?>
			<div class="grid">
				<?php foreach ( $partners as $partner ) : ?>
					<article class="card">
						<div class="card-body">
							<h3><?php echo esc_html( $partner['name'] ); ?></h3>
							<?php if ( ! empty( $partner['text'] ) ) : ?>
								<p><?php echo esc_html( $partner['text'] ); ?></p>
							<?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p class="partner-empty">
				<?php esc_html_e( 'The Foundation has not yet confirmed partner organisations for publication. Confirmed partners will be listed here with their agreement.', 'cohf-child' ); ?>
			</p>
		<?php endif; ?>
	</div>
</section>
