<?php
/**
 * Fallback index.
 *
 * @package COHF
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main-content" tabindex="-1">
	<section class="page-hero">
		<div class="container">
			<h1><?php bloginfo( 'name' ); ?></h1>
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
								<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_content() ), 24 ) ); ?></p>
							</div>
						</article>
						<?php
					endwhile;
					?>
				</div>
				<div class="pagination"><?php echo wp_kses_post( paginate_links( array( 'type' => 'list' ) ) ); ?></div>
			<?php else : ?>
				<p><?php esc_html_e( 'Nothing found.', 'cohf' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php get_footer();
