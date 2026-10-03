<?php
/**
 * Template Name: Privacy Policy
 *
 * Written for what this site actually does: an enquiry form that emails the
 * Foundation, online giving through Paystack, and photographs and stories
 * shared with consent. Framed around Kenya's Data Protection Act, 2019.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();

$email   = cohf_org_get( 'email' );
$phone   = cohf_org_get( 'phone' );
$address = cohf_org_get( 'address' );
$updated = '30 September 2026';
?>
<main id="main-content" tabindex="-1">
	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'eyebrow' => __( 'Privacy and data protection', 'cohf-child' ),
		'title'   => __( 'Privacy Policy', 'cohf-child' ),
		'text'    => __( 'How Cistern of Hope Foundation collects, uses and protects personal information.', 'cohf-child' ),
	) );
	?>
	<section>
		<div class="container prose privacy">
			<?php
			$cohf_priv = trim( (string) get_post_field( 'post_content', get_the_ID() ) );
			// 14.4.0: WordPress's unedited sample policy ("Suggested text:") is replaced by the Foundation's own policy.
			if ( '' !== $cohf_priv && false === strpos( $cohf_priv, 'Suggested text:' ) ) {
				while ( have_posts() ) {
					the_post();
					the_content();
				}
			} else {
				get_template_part( 'template-parts/legal-privacy' );
			}
			?>
		</div>
	</section>
</main>
<?php
get_footer();
