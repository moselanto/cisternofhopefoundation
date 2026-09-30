<?php
/**
 * Template Name: Donation and Refund Policy
 *
 * Donation, receipting and refund terms for online giving through Paystack.
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
		'eyebrow' => __( 'Giving with confidence', 'cohf-child' ),
		'title'   => __( 'Donation and Refund Policy', 'cohf-child' ),
		'text'    => __( 'How we receive, use, receipt and, where appropriate, refund your gifts.', 'cohf-child' ),
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
				get_template_part( 'template-parts/legal-donation-policy' );
			}
			?>
		</div>
	</section>
	<?php get_template_part( 'template-parts/cta' ); ?>
</main>
<?php
get_footer();
