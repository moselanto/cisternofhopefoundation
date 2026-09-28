<?php
/**
 * Template Name: Our Approach
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main-content" tabindex="-1">

	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'image'   => 'programme-07',
		'eyebrow' => __( 'Our approach', 'cohf-child' ),
		'title'   => __( 'From Support to Self-Reliance', 'cohf-child' ),
		'text'    => __( 'Compassion combined with practical action and community participation.', 'cohf-child' ),
	) );
	?>

	<?php if ( cohf_page_body_is_blocks() ) : cohf_the_page_body(); else : ?>

	<section class="sage">
		<div class="container">
			<?php get_template_part( 'template-parts/approach' ); ?>
		</div>
	</section>

	<?php get_template_part( 'template-parts/theory-of-change' ); ?>

	<!-- How we work -->
	<section class="cream">
		<div class="container feature">
			<div>
				<div class="kicker"><?php esc_html_e( 'How we work', 'cohf-child' ); ?></div>
				<h2><?php esc_html_e( 'Communities are partners, not recipients.', 'cohf-child' ); ?></h2>
				<p><?php esc_html_e( 'We work with qualified professionals and appropriate institutions wherever services require clinical expertise, diagnosis, treatment or other regulated practice. Our role includes community mobilisation, awareness, outreach coordination, referral and follow-up.', 'cohf-child' ); ?></p>
				<div class="quote"><?php esc_html_e( 'Sustainable change requires knowledge, mentorship, supportive relationships, access to opportunity and follow-up.', 'cohf-child' ); ?></div>
			</div>
			<?php cohf_the_image( 'programme-11', array( 'sizes' => '(max-width: 60em) 100vw, 50vw' ) ); ?>
		</div>
	</section>

	<?php if ( trim( get_the_content() ) ) : ?>
		<section><div class="container prose"><?php while ( have_posts() ) { the_post(); the_content(); } ?></div></section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/cta' ); ?>

	<?php endif; ?>
</main>
<?php get_footer();
