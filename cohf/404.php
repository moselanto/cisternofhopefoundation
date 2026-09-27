<?php
/**
 * Fallback 404.
 *
 * @package COHF
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main-content" tabindex="-1">
	<section class="page-hero">
		<div class="container">
			<h1><?php esc_html_e( 'Page not found', 'cohf' ); ?></h1>
			<p><?php esc_html_e( 'The page you were looking for is not here.', 'cohf' ); ?></p>
		</div>
	</section>
	<section>
		<div class="container">
			<a class="btn dark" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to homepage', 'cohf' ); ?></a>
		</div>
	</section>
</main>
<?php get_footer();
