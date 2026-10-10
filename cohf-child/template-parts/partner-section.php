<?php
/**
 * Our partners: current and past partners, and what was achieved together.
 *
 * Rebuilt in 14.9.0. Partners are grouped into "Current partners" and "Past
 * partners". Each card shows the partner's logo (main image) or a monogram,
 * its headline, the partnership period, an optional reported figure, a short
 * summary and the list of what was accomplished together, then links to the
 * partner's own page for the full story.
 *
 * Only partners explicitly confirmed by the Foundation are ever rendered; no
 * placeholder logos are invented. With nothing confirmed the section is not
 * output at all.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;

$cohf_partners = function_exists( 'cohf_confirmed_partners' ) ? cohf_confirmed_partners() : array();
if ( empty( $cohf_partners ) ) {
	return; // Nothing to showcase yet: no empty section in the middle of the page.
}

$cohf_groups = array(
	'current' => array(
		'title' => __( 'Current partners', 'cohf-child' ),
		'items' => array(),
	),
	'past'    => array(
		'title' => __( 'Past partners', 'cohf-child' ),
		'items' => array(),
	),
);
foreach ( $cohf_partners as $cohf_partner ) {
	$cohf_groups[ $cohf_partner['status'] ]['items'][] = $cohf_partner;
}
$cohf_group_count = count( array_filter( wp_list_pluck( $cohf_groups, 'items' ) ) );
?>
<section class="cream partner-showcase" id="our-partners">
	<div class="container">
		<div class="section-head">
			<div>
				<div class="sec-label"><span class="sec-label__rule"></span><span class="sec-label__text"><?php esc_html_e( 'Our partners', 'cohf-child' ); ?></span></div>
				<h2><?php esc_html_e( 'What we have achieved together.', 'cohf-child' ); ?></h2>
			</div>
			<p><?php esc_html_e( 'None of our work happens alone. These are the partners who have walked with us, and what we have accomplished side by side.', 'cohf-child' ); ?></p>
		</div>

		<?php foreach ( $cohf_groups as $cohf_group_key => $cohf_group ) : ?>
			<?php
			if ( empty( $cohf_group['items'] ) ) {
				continue;
			}
			$cohf_group_id = 'partners-' . $cohf_group_key;
			?>
			<div class="partner-group" aria-labelledby="<?php echo esc_attr( $cohf_group_id ); ?>">
				<?php if ( $cohf_group_count > 1 || 'past' === $cohf_group_key ) : ?>
					<h3 class="partner-group__title" id="<?php echo esc_attr( $cohf_group_id ); ?>"><?php echo esc_html( $cohf_group['title'] ); ?></h3>
				<?php else : ?>
					<h3 class="screen-reader-text" id="<?php echo esc_attr( $cohf_group_id ); ?>"><?php echo esc_html( $cohf_group['title'] ); ?></h3>
				<?php endif; ?>

				<ul class="partner-grid">
					<?php foreach ( $cohf_group['items'] as $cohf_p ) : ?>
						<li class="partner-card partner-card--<?php echo esc_attr( $cohf_p['status'] ); ?>">
							<div class="partner-card__head">
								<?php if ( $cohf_p['logo_id'] ) : ?>
									<span class="partner-card__logo">
										<?php
										echo wp_get_attachment_image( $cohf_p['logo_id'], 'medium', false, array(
											'alt'     => sprintf( /* translators: %s: partner name. */ __( '%s logo', 'cohf-child' ), $cohf_p['name'] ),
											'loading' => 'lazy',
										) );
										?>
									</span>
								<?php else : ?>
									<span class="partner-card__logo partner-card__logo--mono" aria-hidden="true"><?php echo esc_html( function_exists( 'cohf_initials' ) ? cohf_initials( $cohf_p['name'] ) : mb_substr( $cohf_p['name'], 0, 1 ) ); ?></span>
								<?php endif; ?>
								<div class="partner-card__id">
									<?php if ( $cohf_p['type'] || $cohf_p['period'] ) : ?>
										<p class="partner-card__meta"><?php echo esc_html( implode( ' · ', array_filter( array( $cohf_p['type'], $cohf_p['period'] ) ) ) ); ?></p>
									<?php endif; ?>
									<h4 class="partner-card__name"><?php echo esc_html( $cohf_p['name'] ); ?></h4>
								</div>
							</div>

							<?php if ( $cohf_p['tagline'] ) : ?>
								<p class="partner-card__tagline"><?php echo esc_html( $cohf_p['tagline'] ); ?></p>
							<?php endif; ?>

							<?php if ( $cohf_p['text'] ) : ?>
								<p class="partner-card__text"><?php echo wp_kses_post( $cohf_p['text'] ); ?></p>
							<?php endif; ?>

							<?php if ( $cohf_p['figure'] ) : ?>
								<p class="partner-card__figure"><strong><?php echo esc_html( $cohf_p['figure'] ); ?></strong> <span><?php echo esc_html( $cohf_p['figure_label'] ); ?></span></p>
							<?php endif; ?>

							<?php if ( $cohf_p['achievements'] ) : ?>
								<div class="partner-card__together">
									<p class="partner-card__label"><?php esc_html_e( 'Together we achieved', 'cohf-child' ); ?></p>
									<ul class="partner-card__list">
										<?php foreach ( $cohf_p['achievements'] as $cohf_a ) : ?>
											<li><?php echo esc_html( $cohf_a ); ?></li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>

							<?php if ( $cohf_p['has_story'] ) : ?>
								<a class="partner-card__more" href="<?php echo esc_url( $cohf_p['link'] ); ?>">
									<?php esc_html_e( 'Read our story together', 'cohf-child' ); ?><span class="screen-reader-text">: <?php echo esc_html( $cohf_p['name'] ); ?></span> <span aria-hidden="true">&rarr;</span>
								</a>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endforeach; ?>
	</div>
</section>
