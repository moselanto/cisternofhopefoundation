<?php
/**
 * Template Name: Home
 *
 * Section order follows the approved prototype, with two later removals.
 *
 * Community stories and the 2026-2030 strategic journey both used to sit
 * between "The change we seek" and the closing call to action. They were
 * cut because the page had started repeating itself:
 *
 * - The strategic journey listed five sequential phases immediately after
 *   Our Approach had already walked the reader through seven sequential
 *   steps. Two numbered journeys in one page is one too many, and the
 *   homepage already names the plan in the Our Story facts ("2026-30,
 *   five-year strategic journey"). The full timeline still lives on the
 *   Strategy page, which is where someone looking for it will go.
 * - Community stories had no published stories behind it, so it rendered
 *   as an editor-only placeholder occupying a full section band.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();

$org     = cohf_org();
$cta     = cohf_cta_links();
$about   = cohf_page_url( 'page-templates/page-about.php' );
$progs   = cohf_page_url( 'page-templates/page-programmes.php' );
$impact  = cohf_page_url( 'page-templates/page-impact.php' );
$contact = cohf_page_url( 'page-templates/page-contact.php' );
?>
<main id="main-content" tabindex="-1">

	<?php
	// Slides are defined in cohf_hero_slides(). Passing hero copy here would
	// put the slider into single-slide mode and show only one static slide.
	get_template_part( 'template-parts/hero' );
	?>

	<!-- Story -->
	<section>
		<div class="container story">
			<?php cohf_the_image( 'story-community', array( 'sizes' => '(max-width: 60em) 100vw, 50vw' ) ); ?>
			<div class="story-copy">
				<div class="sec-label"><span class="sec-label__rule"></span><span class="sec-label__text"><?php esc_html_e( 'Our story', 'cohf-child' ); ?></span></div>
				<h2 class="sec-statement sec-statement--wide"><?php esc_html_e( 'From a local response to a growing movement of hope.', 'cohf-child' ); ?></h2>
				<p><?php esc_html_e( 'Cistern of Hope Foundation began in 2021 in Uthiru, Nairobi, during the COVID-19 pandemic. What began with food and essential supplies has grown into a wider movement focused on dignity, empowerment, opportunity and lasting change.', 'cohf-child' ); ?></p>
				<div class="facts">
					<div class="fact">
						<strong><?php echo esc_html( $org['founded'] ); ?></strong>
						<span><?php esc_html_e( 'Founded in Uthiru, Nairobi', 'cohf-child' ); ?></span>
					</div>
					<div class="fact">
						<strong><?php echo esc_html( $org['registered'] ); ?></strong>
						<span><?php esc_html_e( 'Formally registered in Kenya', 'cohf-child' ); ?></span>
					</div>
					<div class="fact">
						<strong><?php esc_html_e( '2026-30', 'cohf-child' ); ?></strong>
						<span><?php esc_html_e( 'Five-year strategic journey', 'cohf-child' ); ?></span>
					</div>
				</div>
				<a class="btn outline" href="<?php echo esc_url( $about ); ?>"><?php esc_html_e( 'Read Our Story', 'cohf-child' ); ?></a>
			</div>
		</div>
	</section>

	<!-- Purpose -->
	<section class="cream">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="sec-label"><span class="sec-label__rule"></span><span class="sec-label__text"><?php esc_html_e( 'Our purpose', 'cohf-child' ); ?></span></div>
					<h2 class="sec-statement sec-statement--wide"><?php esc_html_e( 'People first. Progress that lasts.', 'cohf-child' ); ?></h2>
				</div>
				<p><?php esc_html_e( 'We respond to immediate needs while building pathways toward sustainable livelihoods, education, wellbeing, resilience and self-reliance.', 'cohf-child' ); ?></p>
			</div>
			<?php get_template_part( 'template-parts/purpose' ); ?>
		</div>
	</section>

	<!-- Approach -->
	<section class="sage">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="sec-label"><span class="sec-label__rule"></span><span class="sec-label__text"><?php esc_html_e( 'Our approach', 'cohf-child' ); ?></span></div>
					<h2 class="sec-statement sec-statement--wide"><?php esc_html_e( 'From support to self-reliance.', 'cohf-child' ); ?></h2>
				</div>
				<p><?php esc_html_e( 'A connected pathway that links compassion with empowerment, opportunity, resilience and community ownership.', 'cohf-child' ); ?></p>
			</div>
			<?php get_template_part( 'template-parts/approach' ); ?>
		</div>
	</section>

	<!-- Programmes -->
	<section>
		<div class="container">
			<div class="section-head">
				<div>
					<div class="sec-label"><span class="sec-label__rule"></span><span class="sec-label__text"><?php esc_html_e( 'Programmes', 'cohf-child' ); ?></span></div>
					<h2 class="sec-statement sec-statement--wide"><?php esc_html_e( 'Built around real community needs.', 'cohf-child' ); ?></h2>
				</div>
				<a class="arrow" href="<?php echo esc_url( $progs ); ?>"><?php esc_html_e( 'Explore all', 'cohf-child' ); ?></a>
			</div>
			<div class="grid">
				<?php
				$featured = new WP_Query( array(
					'post_type'      => 'cohf_programme',
					'posts_per_page' => 3,
					'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
					'no_found_rows'  => true,
				) );
				if ( $featured->have_posts() ) {
					while ( $featured->have_posts() ) {
						$featured->the_post();
						get_template_part( 'template-parts/programme-card' );
					}
					wp_reset_postdata();
				} else {
					printf(
						'<p class="partner-empty">%s</p>',
						esc_html__( 'Run the one-time setup to add all the programme areas.', 'cohf-child' )
					);
				}
				?>
			</div>
		</div>
	</section>

	<!-- Impact -->
	<section class="impact">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="sec-label"><span class="sec-label__rule"></span><span class="sec-label__text"><?php esc_html_e( 'Our impact', 'cohf-child' ); ?></span></div>
					<h2 class="sec-statement sec-statement--wide"><?php esc_html_e( 'Turning hope into meaningful change.', 'cohf-child' ); ?></h2>
				</div>
				<p><?php esc_html_e( 'Impact is measured by the difference our work makes in people\'s lives.', 'cohf-child' ); ?></p>
			</div>
			<?php get_template_part( 'template-parts/numbers' ); ?>
			<?php // Spacing below the figures is set in CSS; a <br> cannot be adjusted per breakpoint. ?>
			<div class="impact__more">
				<a class="btn light" href="<?php echo esc_url( $impact ); ?>"><?php esc_html_e( 'Explore Impact', 'cohf-child' ); ?></a>
			</div>
		</div>
	</section>

	<?php
	// Impact stories, immediately after the figures they belong to. The
	// homepage had no route to them at all, so the Foundation's most
	// specific evidence - a dated account with a named outcome - sat behind
	// the Impact menu where most visitors never look.
	//
	// The section is skipped entirely when nothing is published, rather than
	// rendering a heading above an empty band.
	$cohf_stories_archive = get_post_type_archive_link( 'cohf_story' );
	$cohf_home_stories    = new WP_Query( array(
		'post_type'      => 'cohf_story',
		'post_status'    => 'publish',
		'posts_per_page' => 3,
		'no_found_rows'  => true,
	) );
	?>
	<?php if ( $cohf_home_stories->have_posts() ) : ?>
		<!-- Stories -->
		<section>
			<div class="container">
				<div class="section-head">
					<div>
						<div class="sec-label"><span class="sec-label__rule"></span><span class="sec-label__text"><?php esc_html_e( 'Impact stories', 'cohf-child' ); ?></span></div>
						<h2 class="sec-statement sec-statement--wide"><?php esc_html_e( 'The people behind the figures.', 'cohf-child' ); ?></h2>
					</div>
					<?php if ( $cohf_stories_archive ) : ?>
						<a class="arrow" href="<?php echo esc_url( $cohf_stories_archive ); ?>"><?php esc_html_e( 'Read all stories', 'cohf-child' ); ?></a>
					<?php endif; ?>
				</div>
				<div class="grid story-teaser-grid">
					<?php
					while ( $cohf_home_stories->have_posts() ) {
						$cohf_home_stories->the_post();
						get_template_part( 'template-parts/story-card' );
					}
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<!-- The change we seek -->
	<section class="cream">
		<div class="container feature">
			<div>
				<div class="sec-label"><span class="sec-label__rule"></span><span class="sec-label__text"><?php esc_html_e( 'The change we seek', 'cohf-child' ); ?></span></div>
				<h2 class="sec-statement sec-statement--wide"><?php esc_html_e( 'Every person deserves dignity and an opportunity to improve their life.', 'cohf-child' ); ?></h2>
				<p><?php esc_html_e( 'Assistance becomes more powerful when it creates a pathway toward self-reliance. Communities are active partners in shaping their own future.', 'cohf-child' ); ?></p>
				<div class="quote"><?php esc_html_e( 'We do not simply want to give people hope for today. We want to help create pathways to a better tomorrow.', 'cohf-child' ); ?></div>
			</div>
			<?php cohf_the_image( 'programme-01', array( 'sizes' => '(max-width: 60em) 100vw, 50vw' ) ); ?>
		</div>
	</section>

	<?php
	get_template_part( 'template-parts/cta', null, array(
		'title'         => __( 'Let\'s build lasting change together.', 'cohf-child' ),
		'text'          => __( 'Partner with Cistern of Hope Foundation to strengthen pathways for children, young people, women, families and communities.', 'cohf-child' ),
		'primary_label' => __( 'Partner With Us', 'cohf-child' ),
		// Was $contact. The label promises the partnership page, so sending
		// people to the general contact form was a broken promise and lost
		// the partnership context the enquiry form would otherwise preselect.
		'primary_url'   => $cta['partner'],
		// Was Contact Us. The homepage is where most donors arrive, and the
		// closing band was the only full-width ask on the page, yet it
		// offered no way to give. Contact stays one tap away in the top bar
		// and footer.
		'secondary_label' => __( 'Support Our Work', 'cohf-child' ),
		'secondary_url'   => $cta['support'],
	) );
	?>

</main>
<?php get_footer();
