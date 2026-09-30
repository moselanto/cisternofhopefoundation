<?php
/**
 * Template Name: Terms of Use
 *
 * General terms for visitors to the website.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();

$email = cohf_org_get( 'email' );
$phone = cohf_org_get( 'phone' );
?>
<main id="main-content" tabindex="-1">
	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'eyebrow' => __( 'Using this website', 'cohf-child' ),
		'title'   => __( 'Terms of Use', 'cohf-child' ),
		'text'    => __( 'The terms that apply when you use the Cistern of Hope Foundation website.', 'cohf-child' ),
	) );
	?>
	<section>
		<div class="container prose privacy">
			<?php
			if ( '' !== trim( (string) get_post_field( 'post_content', get_the_ID() ) ) ) {
				while ( have_posts() ) {
					the_post();
					the_content();
				}
			} else {
				get_template_part( 'template-parts/legal-terms' );
			}
			?>
		</div>
	</section>
	<?php get_template_part( 'template-parts/cta' ); ?>
</main>
<?php
get_footer();
