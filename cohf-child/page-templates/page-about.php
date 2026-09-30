<?php
/**
 * Template Name: About
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();

$approach   = cohf_page_url( 'page-templates/page-approach.php' );
$leadership = cohf_page_url( 'page-templates/page-leadership.php' );
$org        = cohf_org();
?>
<main id="main-content" tabindex="-1">

	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'image'   => 'hero-about',
		'eyebrow' => __( 'Our story', 'cohf-child' ),
		'title'   => __( 'About Cistern of Hope Foundation', 'cohf-child' ),
		'text'    => __( 'From a heart for people to a growing movement of hope.', 'cohf-child' ),
	) );
	?>

	<?php if ( cohf_page_body_is_blocks() ) : cohf_the_page_body(); else : ?>

	<!-- Why we exist -->
	<section>
		<div class="container story">
			<?php cohf_the_image( 'gallery-childrens-home-group', array( 'sizes' => '(max-width: 60em) 100vw, 50vw' ) ); ?>
			<div class="story-copy">
				<div class="kicker"><?php esc_html_e( 'Why we exist', 'cohf-child' ); ?></div>
				<h2><?php esc_html_e( 'Poverty should not define a person\'s future.', 'cohf-child' ); ?></h2>
				<p><?php esc_html_e( 'We work with vulnerable children, young people, women and communities, responding to immediate needs while creating pathways toward sustainable livelihoods, education, wellbeing, resilience and self-reliance.', 'cohf-child' ); ?></p>
				<a class="btn dark" href="<?php echo esc_url( $approach ); ?>"><?php esc_html_e( 'See Our Approach', 'cohf-child' ); ?></a>
			</div>
		</div>
	</section>

	<!-- Purpose -->
	<section class="sage">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="kicker"><?php esc_html_e( 'Our purpose', 'cohf-child' ); ?></div>
					<h2><?php esc_html_e( 'Vision, mission and motto.', 'cohf-child' ); ?></h2>
				</div>
			</div>
			<?php get_template_part( 'template-parts/purpose' ); ?>
		</div>
	</section>

	<!-- Values -->
	<section class="cream">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="kicker"><?php esc_html_e( 'Our values', 'cohf-child' ); ?></div>
					<h2><?php esc_html_e( 'How we want to work.', 'cohf-child' ); ?></h2>
				</div>
			</div>
			<div class="grid">
				<?php
				$values = array(
					array( __( 'Empathy', 'cohf-child' ),        __( 'Understanding people\'s circumstances and responding with compassion, dignity and humanity.', 'cohf-child' ) ),
					array( __( 'Integrity', 'cohf-child' ),      __( 'Honesty, transparency, responsible use of resources and accountability.', 'cohf-child' ) ),
					array( __( 'Sustainability', 'cohf-child' ), __( 'Solutions that build capacity, self-reliance and long-term community development.', 'cohf-child' ) ),
					array( __( 'Collaboration', 'cohf-child' ),  __( 'Working with communities, government, donors and partners to increase impact.', 'cohf-child' ) ),
					array( __( 'Respect', 'cohf-child' ),        __( 'Upholding dignity, rights, inclusion and the equal worth of every person.', 'cohf-child' ) ),
					array( __( 'Innovation', 'cohf-child' ),     __( 'Remaining open to practical and creative ways of addressing changing community challenges.', 'cohf-child' ) ),
				);
				foreach ( $values as $value ) {
					printf(
						'<article class="card"><div class="card-body"><h3>%1$s</h3><p>%2$s</p></div></article>',
						esc_html( $value[0] ),
						esc_html( $value[1] )
					);
				}
				?>
			</div>
		</div>
	</section>

	<!-- Journey facts -->
	<section>
		<div class="container feature">
			<div>
				<div class="kicker"><?php esc_html_e( 'Our journey', 'cohf-child' ); ?></div>
				<h2><?php esc_html_e( 'A young organisation with a clear direction.', 'cohf-child' ); ?></h2>
				<p><?php esc_html_e( 'Our Constitution gives us an institutional foundation, and our 2026-2030 strategy gives us direction. We are building the systems and partnerships required to increase our impact responsibly.', 'cohf-child' ); ?></p>
				<div class="facts">
					<div class="fact">
						<strong><?php echo esc_html( $org['founded'] ); ?></strong>
						<span><?php esc_html_e( 'Founded in Uthiru, Nairobi', 'cohf-child' ); ?></span>
					</div>
					<div class="fact">
						<strong><?php echo esc_html( $org['registered'] ); ?></strong>
						<span><?php esc_html_e( 'Registered under the Registrar of Societies', 'cohf-child' ); ?></span>
					</div>
					<div class="fact">
						<strong><?php esc_html_e( '12', 'cohf-child' ); ?></strong>
						<span><?php esc_html_e( 'Connected programme areas', 'cohf-child' ); ?></span>
					</div>
				</div>
				<a class="btn outline" href="<?php echo esc_url( $leadership ); ?>"><?php esc_html_e( 'Leadership &amp; Governance', 'cohf-child' ); ?></a>
			</div>
			<?php cohf_the_image( 'gallery-womens-seminar', array( 'sizes' => '(max-width: 60em) 100vw, 50vw' ) ); ?>
		</div>
	</section>

	<?php if ( trim( get_the_content() ) ) : ?>
		<section class="cream"><div class="container prose"><?php while ( have_posts() ) { the_post(); the_content(); } ?></div></section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/cta' ); ?>

	<?php endif; ?>
</main>
<?php get_footer();
