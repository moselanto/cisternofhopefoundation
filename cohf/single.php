<?php
/**
 * Fallback single template.
 *
 * @package COHF
 */
defined( 'ABSPATH' ) || exit;
get_header();
while ( have_posts() ) :
	the_post();
	?>
	<main id="main-content" tabindex="-1">
		<section class="page-hero">
			<div class="container">
				<div class="eyebrow"><?php echo esc_html( get_the_date() ); ?></div>
				<h1><?php the_title(); ?></h1>
			</div>
		</section>
		<section>
			<div class="container prose"><?php the_content(); ?></div>
		</section>
	</main>
	<?php
endwhile;
get_footer();
