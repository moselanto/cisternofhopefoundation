<?php
/**
 * Fallback search results.
 *
 * @package COHF
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main-content" tabindex="-1">
	<section class="page-hero">
		<div class="container">
			<h1>
				<?php
				printf(
					/* translators: %s: search term. */
					esc_html__( 'Search results for %s', 'cohf' ),
					esc_html( get_search_query() )
				);
				?>
			</h1>
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
							<div class="card-body">
								<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_content() ), 24 ) ); ?></p>
							</div>
						</article>
						<?php
					endwhile;
					?>
				</div>
			<?php else : ?>
				<p><?php esc_html_e( 'No results found.', 'cohf' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php get_footer();
