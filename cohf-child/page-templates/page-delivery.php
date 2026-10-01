<?php
/**
 * Template Name: Delivery and Shipping
 *
 * Where we deliver, what it costs, how long it takes and how to collect.
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
		'eyebrow' => __( 'Getting your order to you', 'cohf-child' ),
		'title'   => __( 'Delivery and Shipping Information', 'cohf-child' ),
		'text'    => __( 'Where we deliver, what it costs, how long it takes and how to collect.', 'cohf-child' ),
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
				get_template_part( 'template-parts/legal-delivery' );
			}
			?>
		</div>
	</section>
	<?php get_template_part( 'template-parts/cta' ); ?>
</main>
<?php
get_footer();
