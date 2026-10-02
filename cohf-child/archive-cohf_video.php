<?php
/**
 * Archive: cohf_video (Video Stories).
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
			<h1><?php esc_html_e( 'Video stories', 'cohf-child' ); ?></h1>
			<p><?php esc_html_e( 'Our work and the people behind it, on film. Every video is shared with consent, protecting the dignity of the people involved.', 'cohf-child' ); ?></p>
		</div>
	</section>

	<section>
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<div class="video-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						$cohf_player = cohf_video_player( get_the_ID() );
						?>
						<article class="video-card">
							<?php if ( $cohf_player ) : ?>
								<div class="video-card__player"><?php echo $cohf_player; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core oEmbed / video shortcode output. ?></div>
							<?php elseif ( has_post_thumbnail() ) : ?>
								<div class="video-card__player"><?php the_post_thumbnail( 'large' ); ?></div>
							<?php endif; ?>
							<div class="video-card__body">
								<h2 class="video-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<?php if ( has_excerpt() ) : ?>
									<p><?php echo esc_html( get_the_excerpt() ); ?></p>
								<?php endif; ?>
							</div>
						</article>
					<?php endwhile; ?>
				</div>
				<div class="pagination"><?php the_posts_pagination( array( 'mid_size' => 1, 'type' => 'list' ) ); ?></div>
			<?php else : ?>
				<div class="stories-empty">
					<div class="sec-label"><span class="sec-label__rule"></span><span class="sec-label__text"><?php esc_html_e( 'Coming soon', 'cohf-child' ); ?></span></div>
					<h2><?php esc_html_e( 'The first video stories are on their way.', 'cohf-child' ); ?></h2>
					<p><?php esc_html_e( 'In the meantime you can read our impact stories or browse the photo gallery.', 'cohf-child' ); ?></p>
					<div class="buttons">
						<a class="btn dark" href="<?php echo esc_url( get_post_type_archive_link( 'cohf_story' ) ); ?>"><?php esc_html_e( 'Read impact stories', 'cohf-child' ); ?></a>
						<a class="btn outline" href="<?php echo esc_url( cohf_page_url( 'page-templates/page-gallery.php' ) ); ?>"><?php esc_html_e( 'See the photo gallery', 'cohf-child' ); ?></a>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php get_template_part( 'template-parts/cta' ); ?>
</main>
<?php get_footer();
