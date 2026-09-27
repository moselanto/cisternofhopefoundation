<?php
/**
 * Fallback page template.
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
			<div class="container"><h1><?php the_title(); ?></h1></div>
		</section>
		<section>
			<div class="container prose"><?php the_content(); ?></div>
		</section>
	</main>
	<?php
endwhile;
get_footer();
