<?php
/**
 * Single cohf_leader.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();

while ( have_posts() ) :
	the_post();
	$role = cohf_field( 'role' );
	$bio  = cohf_field( 'short_bio' );
	?>
	<main id="main-content" tabindex="-1">

		<section class="page-hero">
			<div class="container">
				<div class="eyebrow"><?php esc_html_e( 'Leadership &amp; governance', 'cohf-child' ); ?></div>
				<h1><?php the_title(); ?></h1>
				<?php if ( $role ) : ?>
					<p><?php echo esc_html( $role ); ?></p>
				<?php endif; ?>
			</div>
		</section>

		<section>
			<div class="container story">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'large' ); ?>
				<?php else : ?>
					<div class="portrait" aria-hidden="true"><?php echo esc_html( cohf_initials( get_the_title() ) ); ?></div>
				<?php endif; ?>
				<div class="story-copy">
					<?php if ( $bio ) : ?>
						<p><?php echo esc_html( $bio ); ?></p>
					<?php endif; ?>
					<div class="prose"><?php the_content(); ?></div>
					<a class="btn outline" href="<?php echo esc_url( cohf_page_url( 'page-templates/page-leadership.php' ) ); ?>"><?php esc_html_e( 'All leadership', 'cohf-child' ); ?></a>
				</div>
			</div>
		</section>

		<?php get_template_part( 'template-parts/cta' ); ?>
	</main>
	<?php
endwhile;
get_footer();
