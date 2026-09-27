<?php
/**
 * Archive: cohf_story.
 *
 * Prototype markup: section.page-hero, then section > .container > .grid of .card
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main-content" tabindex="-1">

	<section class="page-hero">
		<div class="container">
			<div class="eyebrow"><?php esc_html_e( 'Our impact', 'cohf-child' ); ?></div>
			<h1><?php esc_html_e( 'Impact stories', 'cohf-child' ); ?></h1>
			<p><?php esc_html_e( 'Stories shared with consent, protecting the dignity of the people involved.', 'cohf-child' ); ?></p>
		</div>
	</section>

	<section>
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<div class="grid">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<article class="card">
							<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large' ); } ?>
							<div class="card-body">
								<div class="kicker"><?php echo esc_html( get_the_date() ); ?></div>
								<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
								<span class="arrow"><?php esc_html_e( 'Read', 'cohf-child' ); ?></span>
							</div>
						</article>
						<?php
					endwhile;
					?>
				</div>
				<div class="pagination"><?php the_posts_pagination( array( 'mid_size' => 1, 'type' => 'list' ) ); ?></div>
			<?php else : ?>
				<p class="partner-empty"><?php esc_html_e( 'Nothing has been published here yet.', 'cohf-child' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<?php get_template_part( 'template-parts/cta' ); ?>
</main>
<?php get_footer();
