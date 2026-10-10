<?php
/**
 * Single cohf_partner: the story of one partnership.
 *
 * Added in 14.9.0. Unconfirmed partners never reach this template for
 * visitors; inc/partners.php returns a 404 first.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();

while ( have_posts() ) :
	the_post();
	$cohf_status       = 'past' === cohf_field( 'status' ) ? 'past' : 'current';
	$cohf_tagline      = cohf_field( 'tagline' );
	$cohf_type         = cohf_field( 'partner_type' );
	$cohf_period       = cohf_field( 'period' );
	$cohf_figure       = cohf_field( 'figure' );
	$cohf_figure_label = cohf_field( 'figure_label' );
	$cohf_achievements = cohf_field_lines( 'achievements' );
	$cohf_website      = cohf_field( 'website' );
	$cohf_programme_id = (int) cohf_field( 'programme_id' );
	$cohf_partners_url = function_exists( 'cohf_page_url' ) ? cohf_page_url( 'page-templates/page-partners.php' ) : home_url( '/partners-overview/' );
	$cohf_bundled      = function_exists( 'cohf_partner_bundled_images' ) ? cohf_partner_bundled_images( get_post_field( 'post_name' ) ) : array( 'logo' => '', 'photo' => '', 'people' => array() );
	$cohf_logo_url     = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'medium' ) : ( $cohf_bundled['logo'] ? cohf_img_url( $cohf_bundled['logo'] ) : '' );
	$cohf_has_photo    = $cohf_bundled['photo'] && cohf_img_url( $cohf_bundled['photo'] );
	$cohf_eyebrow      = 'past' === $cohf_status ? __( 'Past partner', 'cohf-child' ) : __( 'Current partner', 'cohf-child' );
	?>
	<main id="main-content" class="partner-single" tabindex="-1">

		<section class="page-hero">
			<div class="container">
				<a class="back-link" href="<?php echo esc_url( $cohf_partners_url . '#our-partners' ); ?>">&larr; <?php esc_html_e( 'All partners', 'cohf-child' ); ?></a>
				<div class="eyebrow"><?php echo esc_html( implode( ' · ', array_filter( array( $cohf_eyebrow, $cohf_period ) ) ) ); ?></div>
				<h1><?php the_title(); ?></h1>
				<?php if ( $cohf_tagline ) : ?>
					<p><?php echo esc_html( $cohf_tagline ); ?></p>
				<?php endif; ?>
			</div>
		</section>

		<section>
			<div class="container story">
				<?php if ( $cohf_has_photo ) : ?>
					<div class="partner-single__photo"><?php cohf_the_image( $cohf_bundled['photo'], array( 'sizes' => '(max-width: 60em) 100vw, 50vw' ) ); ?></div>
				<?php elseif ( $cohf_logo_url ) : ?>
					<div class="partner-single__logo"><img src="<?php echo esc_url( $cohf_logo_url ); ?>" alt="<?php echo esc_attr( sprintf( /* translators: %s: partner name. */ __( '%s logo', 'cohf-child' ), get_the_title() ) ); ?>" loading="lazy" decoding="async"></div>
				<?php else : ?>
					<div class="portrait partner-single__mono" aria-hidden="true"><?php echo esc_html( cohf_initials( get_the_title() ) ); ?></div>
				<?php endif; ?>
				<div class="story-copy">
					<?php if ( $cohf_type || ( $cohf_has_photo && $cohf_logo_url ) ) : ?>
						<div class="partner-single__id">
							<?php if ( $cohf_has_photo && $cohf_logo_url ) : ?>
								<span class="partner-card__logo"><img src="<?php echo esc_url( $cohf_logo_url ); ?>" alt="<?php echo esc_attr( sprintf( /* translators: %s: partner name. */ __( '%s logo', 'cohf-child' ), get_the_title() ) ); ?>" width="64" height="64" loading="lazy" decoding="async"></span>
							<?php endif; ?>
							<?php if ( $cohf_type ) : ?>
								<p class="partner-card__meta"><?php echo esc_html( $cohf_type ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<div class="prose"><?php the_content(); ?></div>
					<?php
					$cohf_people = array_filter( (array) $cohf_bundled['people'], 'cohf_img_url' );
					if ( $cohf_people ) :
						?>
						<div class="partner-single__people">
							<?php foreach ( $cohf_people as $cohf_person_key ) : ?>
								<figure class="partner-single__person"><?php cohf_the_image( $cohf_person_key, array( 'sizes' => '160px' ) ); ?></figure>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<?php if ( $cohf_website ) : ?>
						<p><a class="btn outline" href="<?php echo esc_url( $cohf_website ); ?>" rel="noopener" target="_blank"><?php esc_html_e( 'Visit their website', 'cohf-child' ); ?><span class="screen-reader-text"> <?php esc_html_e( '(opens in a new tab)', 'cohf-child' ); ?></span></a></p>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<?php if ( $cohf_achievements || $cohf_figure ) : ?>
			<section class="cream">
				<div class="container">
					<div class="section-head">
						<div>
							<div class="sec-label"><span class="sec-label__rule"></span><span class="sec-label__text"><?php esc_html_e( 'Together', 'cohf-child' ); ?></span></div>
							<h2><?php esc_html_e( 'What we have accomplished together.', 'cohf-child' ); ?></h2>
						</div>
					</div>
					<div class="partner-single__together">
						<?php if ( $cohf_figure ) : ?>
							<p class="partner-single__figure"><strong><?php echo esc_html( $cohf_figure ); ?></strong> <span><?php echo esc_html( $cohf_figure_label ); ?></span></p>
						<?php endif; ?>
						<?php if ( $cohf_achievements ) : ?>
							<ul class="offer-grid">
								<?php
								$cohf_n = 0;
								foreach ( $cohf_achievements as $cohf_a ) {
									++$cohf_n;
									printf(
										'<li class="offer"><span class="offer__n" aria-hidden="true">%1$s</span><span class="offer__t">%2$s</span></li>',
										esc_html( sprintf( '%02d', $cohf_n ) ),
										esc_html( $cohf_a )
									);
								}
								?>
							</ul>
						<?php endif; ?>
					</div>
					<?php if ( $cohf_programme_id && 'publish' === get_post_status( $cohf_programme_id ) ) : ?>
						<p class="partner-single__programme"><?php esc_html_e( 'Related programme:', 'cohf-child' ); ?> <a href="<?php echo esc_url( get_permalink( $cohf_programme_id ) ); ?>"><?php echo esc_html( get_the_title( $cohf_programme_id ) ); ?></a></p>
					<?php endif; ?>
				</div>
			</section>
		<?php endif; ?>

		<section class="story-nav-section">
			<div class="container">
				<p class="partner-single__back">
					<a class="btn outline" href="<?php echo esc_url( $cohf_partners_url . '#our-partners' ); ?>"><?php esc_html_e( 'All our partners', 'cohf-child' ); ?></a>
					<a class="btn" href="<?php echo esc_url( $cohf_partners_url . '#enquire' ); ?>"><?php esc_html_e( 'Become a partner', 'cohf-child' ); ?></a>
				</p>
			</div>
		</section>

		<?php get_template_part( 'template-parts/cta' ); ?>
	</main>
	<?php
endwhile;
get_footer();
