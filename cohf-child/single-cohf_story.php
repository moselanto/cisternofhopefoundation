<?php
/**
 * Single cohf_story.
 *
 * Stories are published only with appropriate consent; the anonymised flag
 * suppresses identifying detail.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();

while ( have_posts() ) :
	the_post();
	$location   = cohf_field( 'location' );
	$date       = cohf_field( 'story_date' );
	$challenge  = cohf_field( 'challenge' );
	$action     = cohf_field( 'intervention' );
	$change     = cohf_field( 'change' );
	$quote      = cohf_field( 'quote' );
	$quote_attr = cohf_field( 'quote_attr' );
	$anon       = cohf_field( 'anonymised' );
	?>
	<main id="main-content" tabindex="-1">

		<section class="page-hero">
			<div class="container">
				<div class="eyebrow"><?php esc_html_e( 'Impact story', 'cohf-child' ); ?></div>
				<h1><?php the_title(); ?></h1>
				<?php if ( $location || $date ) : ?>
					<p><?php echo esc_html( trim( $location . ( $location && $date ? ' - ' : '' ) . $date ) ); ?></p>
				<?php endif; ?>
			</div>
		</section>

		<section>
			<div class="container story">
				<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large' ); } ?>
				<div class="story-copy">
					<div class="prose"><?php the_content(); ?></div>
					<?php if ( $quote ) : ?>
						<div class="quote"><?php echo esc_html( $quote ); ?></div>
						<?php if ( $quote_attr ) : ?>
							<p class="field__hint"><?php echo esc_html( $quote_attr ); ?></p>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<?php if ( $challenge || $action || $change ) : ?>
			<section class="cream">
				<div class="container">
					<div class="purpose">
						<?php if ( $challenge ) : ?>
							<article>
								<h3><?php esc_html_e( 'The challenge', 'cohf-child' ); ?></h3>
								<p><?php echo esc_html( $challenge ); ?></p>
							</article>
						<?php endif; ?>
						<?php if ( $action ) : ?>
							<article>
								<h3><?php esc_html_e( 'What we did', 'cohf-child' ); ?></h3>
								<p><?php echo esc_html( $action ); ?></p>
							</article>
						<?php endif; ?>
						<?php if ( $change ) : ?>
							<article>
								<h3><?php esc_html_e( 'The change', 'cohf-child' ); ?></h3>
								<p><?php echo esc_html( $change ); ?></p>
							</article>
						<?php endif; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $anon ) : ?>
			<section>
				<div class="container">
					<p class="payment-placeholder"><?php esc_html_e( 'Names and identifying details in this story have been changed to protect the dignity and privacy of the people involved.', 'cohf-child' ); ?></p>
				</div>
			</section>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/cta' ); ?>
	</main>
	<?php
endwhile;
get_footer();
