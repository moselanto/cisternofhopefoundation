<?php
/**
 * Page not found.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main-content" tabindex="-1">
	<section class="page-hero">
		<div class="container">
			<div class="eyebrow"><?php esc_html_e( 'Page not found', 'cohf-child' ); ?></div>
			<h1><?php esc_html_e( 'This page could not be found.', 'cohf-child' ); ?></h1>
			<p><?php esc_html_e( 'It may have moved, or the link may be out of date. Here are some good places to continue.', 'cohf-child' ); ?></p>
		</div>
	</section>
	<section>
		<div class="container">
			<div class="grid">
				<?php
				$cohf_404_links = array(
					array( __( 'Home', 'cohf-child' ), home_url( '/' ), __( 'Start again from our homepage.', 'cohf-child' ) ),
					array( __( 'Our Programmes', 'cohf-child' ), cohf_page_url( 'page-templates/page-programmes.php' ), __( 'See the work we do across Kenya.', 'cohf-child' ) ),
					array( __( 'Impact Stories', 'cohf-child' ), get_post_type_archive_link( 'cohf_story' ), __( 'Real accounts of change in our communities.', 'cohf-child' ) ),
					array( __( 'Photo Gallery', 'cohf-child' ), cohf_page_url( 'page-templates/page-gallery.php' ), __( 'Our work, in pictures.', 'cohf-child' ) ),
					array( __( 'Support Our Work', 'cohf-child' ), cohf_page_url( 'page-templates/page-support.php' ), __( 'Give, partner or volunteer.', 'cohf-child' ) ),
					array( __( 'Contact', 'cohf-child' ), cohf_page_url( 'page-templates/page-contact.php' ), __( 'Tell us what you were looking for.', 'cohf-child' ) ),
				);
				foreach ( $cohf_404_links as $cohf_link ) :
					if ( empty( $cohf_link[1] ) ) {
						continue;
					}
					?>
					<article class="card"><div class="card-body">
						<h3><?php echo esc_html( $cohf_link[0] ); ?></h3>
						<p><?php echo esc_html( $cohf_link[2] ); ?></p>
						<a class="arrow" href="<?php echo esc_url( $cohf_link[1] ); ?>"><?php esc_html_e( 'Go', 'cohf-child' ); ?></a>
					</div></article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
