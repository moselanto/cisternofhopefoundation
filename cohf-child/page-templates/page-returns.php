<?php
/**
 * Template Name: Refund and Returns Policy
 *
 * How returns, exchanges and refunds work for Hope Market purchases.
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
		'eyebrow' => __( 'Shopping with confidence', 'cohf-child' ),
		'title'   => __( 'Refund and Returns Policy', 'cohf-child' ),
		'text'    => __( 'How returns, exchanges and refunds work for Hope Market purchases.', 'cohf-child' ),
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
				get_template_part( 'template-parts/legal-returns' );
			}
			?>
		</div>
	</section>
	<?php get_template_part( 'template-parts/cta' ); ?>
</main>
<?php
get_footer();
