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
	$date_raw   = cohf_field( 'story_date' );
	// Stored as Y-m-d by the date field; shown in the site's date format so
	// the page reads '25 August 2025' rather than '2025-08-25'.
	$date       = $date_raw ? date_i18n( get_option( 'date_format' ), strtotime( $date_raw ) ) : '';
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
				<a class="back-link" href="<?php echo esc_url( get_post_type_archive_link( 'cohf_story' ) ? get_post_type_archive_link( 'cohf_story' ) : home_url( '/impact/' ) ); ?>">&larr; <?php esc_html_e( 'All impact stories', 'cohf-child' ); ?></a>
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

		<?php
		$gallery = function_exists( 'cohf_story_gallery' ) ? cohf_story_gallery( get_post_field( 'post_name' ) ) : array();
		if ( $gallery ) :
			?>
			<section class="story-impact">
				<div class="container">
					<div class="section-head">
						<div>
							<div class="kicker"><?php esc_html_e( 'The real impact', 'cohf-child' ); ?></div>
							<h2><?php esc_html_e( 'Before and after.', 'cohf-child' ); ?></h2>
						</div>
					</div>
					<div class="story-impact__grid">
						<?php foreach ( $gallery as $item ) : ?>
							<figure class="story-impact__item">
								<span class="story-impact__label"><?php echo esc_html( $item['label'] ); ?></span>
								<?php cohf_the_image( $item['key'], array( 'class' => 'story-impact__img', 'sizes' => '(max-width: 700px) 100vw, 50vw' ) ); ?>
								<?php if ( ! empty( $item['caption'] ) ) : ?>
									<figcaption><?php echo esc_html( $item['caption'] ); ?></figcaption>
								<?php endif; ?>
							</figure>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

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

		<?php
		// Routes between stories. Until now a reader who finished one had
		// nowhere to go except the global call to action, so every story was
		// a cul-de-sac.
		$cohf_stories_url = get_post_type_archive_link( 'cohf_story' );
		$cohf_prev_story  = get_previous_post();
		$cohf_next_story  = get_next_post();
		?>
		<?php if ( $cohf_stories_url || $cohf_prev_story || $cohf_next_story ) : ?>
			<section class="story-nav-section">
				<div class="container">
					<nav class="story-nav" aria-label="<?php esc_attr_e( 'More impact stories', 'cohf-child' ); ?>">
						<?php if ( $cohf_prev_story ) : ?>
							<a class="story-nav__item story-nav__item--prev" href="<?php echo esc_url( get_permalink( $cohf_prev_story ) ); ?>">
								<span class="story-nav__label"><?php esc_html_e( 'Previous story', 'cohf-child' ); ?></span>
								<span class="story-nav__title"><?php echo esc_html( get_the_title( $cohf_prev_story ) ); ?></span>
							</a>
						<?php endif; ?>

						<?php if ( $cohf_stories_url ) : ?>
							<a class="btn outline story-nav__all" href="<?php echo esc_url( $cohf_stories_url ); ?>"><?php esc_html_e( 'All impact stories', 'cohf-child' ); ?></a>
						<?php endif; ?>

						<?php if ( $cohf_next_story ) : ?>
							<a class="story-nav__item story-nav__item--next" href="<?php echo esc_url( get_permalink( $cohf_next_story ) ); ?>">
								<span class="story-nav__label"><?php esc_html_e( 'Next story', 'cohf-child' ); ?></span>
								<span class="story-nav__title"><?php echo esc_html( get_the_title( $cohf_next_story ) ); ?></span>
							</a>
						<?php endif; ?>
					</nav>
				</div>
			</section>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/cta' ); ?>
	</main>
	<?php
endwhile;
get_footer();
