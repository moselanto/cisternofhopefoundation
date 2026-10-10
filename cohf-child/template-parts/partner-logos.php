<?php
/**
 * Homepage partner logo strip (14.10.0).
 *
 * Every confirmed partner as a same-size logo tile in a horizontally
 * scrolling row. Built from cohf_confirmed_partners(), so a partner added
 * in wp-admin (Partners > Add New, "Partnership confirmed in writing"
 * ticked) appears here automatically - no code change, no limit.
 *
 * UX:
 * - Native horizontal scroll with scroll-snap: swipe on phones, trackpad
 *   or shift+wheel on desktop, and Tab through the tiles on a keyboard.
 * - Previous / next buttons (assets/js/partner-logos.js) page through the
 *   row; they disable at each end and hide when everything already fits.
 * - Soft edge fades show there is more to scroll.
 * - No autoplay: moving content is a WCAG 2.2.2 problem and logos that slide
 *   away while being read are frustrating.
 * - Without JavaScript it is still a plain scrollable list of links.
 *
 * Logo source, in order: the partner's main image in wp-admin, the logo
 * bundled with the theme (cohf_partner_bundled_images()), then a monogram.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;

$cohf_logo_partners = function_exists( 'cohf_confirmed_partners' ) ? cohf_confirmed_partners() : array();
if ( empty( $cohf_logo_partners ) ) {
	return;
}

$cohf_partners_page = function_exists( 'cohf_page_url' ) ? cohf_page_url( 'page-templates/page-partners.php' ) : '';
$cohf_partners_page = $cohf_partners_page ? $cohf_partners_page : home_url( '/partners-overview/' );
?>
<section class="partner-logos" aria-labelledby="partner-logos-title">
	<div class="container">
		<div class="section-head">
			<div>
				<div class="sec-label"><span class="sec-label__rule"></span><span class="sec-label__text"><?php esc_html_e( 'Our partners', 'cohf-child' ); ?></span></div>
				<h2 class="sec-statement sec-statement--wide" id="partner-logos-title"><?php esc_html_e( 'Walking alongside us.', 'cohf-child' ); ?></h2>
			</div>
			<a class="arrow" href="<?php echo esc_url( $cohf_partners_page . '#our-partners' ); ?>"><?php esc_html_e( 'What we have achieved together', 'cohf-child' ); ?></a>
		</div>

		<div class="partner-logos__frame" data-partner-logos>
			<button type="button" class="partner-logos__nav partner-logos__nav--prev" data-dir="-1" aria-controls="partner-logos-track" hidden>
				<span aria-hidden="true">&larr;</span><span class="screen-reader-text"><?php esc_html_e( 'Previous partners', 'cohf-child' ); ?></span>
			</button>

			<ul class="partner-logos__track" id="partner-logos-track" aria-label="<?php esc_attr_e( 'Partner logos', 'cohf-child' ); ?>">
				<?php foreach ( $cohf_logo_partners as $cohf_lp ) : ?>
					<?php
					$cohf_lp_alt  = sprintf( /* translators: %s: partner name. */ __( '%s logo', 'cohf-child' ), $cohf_lp['name'] );
					$cohf_lp_logo = '';
					if ( $cohf_lp['logo_id'] ) {
						$cohf_lp_logo = wp_get_attachment_image( $cohf_lp['logo_id'], 'medium', false, array( 'alt' => '', 'loading' => 'lazy', 'decoding' => 'async' ) );
					} elseif ( ! empty( $cohf_lp['bundled']['logo'] ) && cohf_img_url( $cohf_lp['bundled']['logo'] ) ) {
						$cohf_lp_logo = sprintf( '<img src="%s" alt="" width="200" height="200" loading="lazy" decoding="async">', esc_url( cohf_img_url( $cohf_lp['bundled']['logo'] ) ) );
					}
					$cohf_lp_mono = function_exists( 'cohf_initials' ) ? cohf_initials( trim( preg_replace( '/\s*\([^)]*\)/', '', $cohf_lp['name'] ) ) ) : mb_substr( $cohf_lp['name'], 0, 1 );
					$cohf_lp_href = $cohf_lp['has_story'] ? $cohf_lp['link'] : $cohf_partners_page . '#our-partners';
					?>
					<li class="partner-logos__item">
						<a class="partner-logos__tile" href="<?php echo esc_url( $cohf_lp_href ); ?>">
							<span class="partner-logos__mark<?php echo $cohf_lp_logo ? '' : ' partner-logos__mark--mono'; ?>">
								<?php if ( $cohf_lp_logo ) : ?>
									<?php echo $cohf_lp_logo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts; alt is empty because the name follows in text. ?>
								<?php else : ?>
									<span aria-hidden="true"><?php echo esc_html( $cohf_lp_mono ); ?></span>
								<?php endif; ?>
							</span>
							<span class="partner-logos__name"><?php echo esc_html( $cohf_lp['name'] ); ?></span>
							<?php if ( 'past' === $cohf_lp['status'] ) : ?>
								<span class="partner-logos__meta"><?php esc_html_e( 'Past partner', 'cohf-child' ); ?></span>
							<?php elseif ( $cohf_lp['period'] ) : ?>
								<span class="partner-logos__meta"><?php echo esc_html( $cohf_lp['period'] ); ?></span>
							<?php endif; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>

			<button type="button" class="partner-logos__nav partner-logos__nav--next" data-dir="1" aria-controls="partner-logos-track" hidden>
				<span aria-hidden="true">&rarr;</span><span class="screen-reader-text"><?php esc_html_e( 'Next partners', 'cohf-child' ); ?></span>
			</button>
		</div>

		<p class="partner-logos__join">
			<?php esc_html_e( 'Want to see your organisation here?', 'cohf-child' ); ?>
			<a href="<?php echo esc_url( $cohf_partners_page . '#enquire' ); ?>"><?php esc_html_e( 'Become a partner', 'cohf-child' ); ?></a>
		</p>
	</div>
</section>
