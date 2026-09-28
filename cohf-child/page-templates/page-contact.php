<?php
/**
 * Template Name: Contact
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();
$org = cohf_org();
?>
<main id="main-content" tabindex="-1">

	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'image'   => 'story-community',
		'eyebrow' => __( 'Start a conversation', 'cohf-child' ),
		'title'   => __( 'Contact Cistern of Hope Foundation', 'cohf-child' ),
		'text'    => __( 'Whether you want to partner, support a programme, volunteer or learn more, we would be glad to hear from you.', 'cohf-child' ),
	) );
	?>

	<section id="enquire">
		<div class="container story">
			<div>
				<div class="kicker"><?php esc_html_e( 'Contact details', 'cohf-child' ); ?></div>
				<h2><?php esc_html_e( 'Let\'s build lasting change together.', 'cohf-child' ); ?></h2>

				<p><b><?php echo esc_html( strtoupper( $org['name'] ) ); ?></b></p>

				<p>
					<b><?php esc_html_e( 'Postal address', 'cohf-child' ); ?></b><br>
					<?php echo esc_html( $org['address'] ); ?>
				</p>

				<p>
					<b><?php esc_html_e( 'Phone', 'cohf-child' ); ?></b><br>
					<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $org['phone'] ) ); ?>"><?php echo esc_html( $org['phone'] ); ?></a>
				</p>

				<p>
					<b><?php esc_html_e( 'Email', 'cohf-child' ); ?></b><br>
					<a href="mailto:<?php echo esc_attr( $org['email'] ); ?>"><?php echo esc_html( $org['email'] ); ?></a>
				</p>

				<p>
					<b><?php esc_html_e( 'Where we work', 'cohf-child' ); ?></b><br>
					<?php esc_html_e( 'Uthiru, Nairobi, and communities across Kenya.', 'cohf-child' ); ?>
				</p>

				<p class="callout stack-md">
					<b><?php esc_html_e( 'Raising a concern', 'cohf-child' ); ?></b><br>
					<?php esc_html_e( 'To raise a safeguarding concern or make a complaint, select "Complaint or feedback" in the form. Concerns are treated seriously and confidentially.', 'cohf-child' ); ?>
				</p>
			</div>

			<?php get_template_part( 'template-parts/enquiry-form', null, array( 'default_type' => 'general' ) ); ?>
		</div>
	</section>

	<?php if ( trim( get_the_content() ) ) : ?>
		<section class="cream"><div class="container prose"><?php while ( have_posts() ) { the_post(); the_content(); } ?></div></section>
	<?php endif; ?>

</main>
<?php get_footer();
