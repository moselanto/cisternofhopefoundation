<?php
/**
 * Single: cohf_video (one Video Story).
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main-content" tabindex="-1">
	<?php
	while ( have_posts() ) :
		the_post();
		$cohf_player = cohf_video_player( get_the_ID() );
		?>
		<section class="page-hero">
			<div class="container">
				<div class="eyebrow"><a href="<?php echo esc_url( get_post_type_archive_link( 'cohf_video' ) ); ?>"><?php esc_html_e( 'Video stories', 'cohf-child' ); ?></a></div>
				<h1><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</div>
		</section>

		<section>
			<div class="container video-story">
				<?php if ( $cohf_player ) : ?>
					<div class="video-story__player"><?php echo $cohf_player; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core oEmbed / video shortcode output. ?></div>
				<?php endif; ?>
				<?php if ( '' !== trim( wp_strip_all_tags( get_the_content() ) ) ) : ?>
					<div class="video-story__content entry-content"><?php the_content(); ?></div>
				<?php endif; ?>
				<p><a class="btn outline" href="<?php echo esc_url( get_post_type_archive_link( 'cohf_video' ) ); ?>"><?php esc_html_e( 'All video stories', 'cohf-child' ); ?></a></p>
			</div>
		</section>
	<?php endwhile; ?>

	<?php get_template_part( 'template-parts/cta' ); ?>
</main>
<?php get_footer();
