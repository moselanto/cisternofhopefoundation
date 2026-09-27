<?php
/**
 * Archive: cohf_programme.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main-content" tabindex="-1">

	<section class="page-hero">
		<div class="container">
			<div class="eyebrow"><?php esc_html_e( 'Programme portfolio', 'cohf-child' ); ?></div>
			<h1><?php esc_html_e( 'Our Programmes', 'cohf-child' ); ?></h1>
			<p><?php esc_html_e( 'Integrated work anchored in poverty eradication.', 'cohf-child' ); ?></p>
		</div>
	</section>

	<section>
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<div class="grid">
					<?php
					while ( have_posts() ) {
						the_post();
						get_template_part( 'template-parts/programme-card' );
					}
					?>
				</div>
				<div class="pagination"><?php the_posts_pagination( array( 'mid_size' => 1, 'type' => 'list' ) ); ?></div>
			<?php else : ?>
				<p class="partner-empty"><?php esc_html_e( 'No programmes have been published yet.', 'cohf-child' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<?php get_template_part( 'template-parts/cta' ); ?>
</main>
<?php get_footer();
