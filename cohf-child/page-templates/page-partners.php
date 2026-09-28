<?php
/**
 * Template Name: Partners
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main-content" tabindex="-1">

	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'image'   => 'hero-partners',
		'eyebrow' => __( 'Partnership', 'cohf-child' ),
		'title'   => __( 'Together, we can create lasting change.', 'cohf-child' ),
		'text'    => __( 'Cistern of Hope Foundation cannot eradicate poverty alone. We welcome strategic relationships with organisations and individuals who share our commitment to lasting change.', 'cohf-child' ),
	) );

	// This page is a long single scroll; without this the only route to the
	// enquiry form is to read past six sections.
	get_template_part( 'template-parts/section-nav', null, array(
		'sections' => array(
			'#message'             => __( 'Our message', 'cohf-child' ),
			'#who-we-partner-with' => __( 'Who we work with', 'cohf-child' ),
			'#opportunities'       => __( 'Opportunities', 'cohf-child' ),
			'#priority-areas'      => __( 'Priority areas', 'cohf-child' ),
			'#snapshot'            => __( 'Snapshot', 'cohf-child' ),
			'#enquire'             => __( 'Enquire', 'cohf-child' ),
		),
	) );
	?>

	<?php if ( cohf_page_body_is_blocks() ) : cohf_the_page_body(); else : ?>

	<section id="message">
		<div class="container feature">
			<div>
				<div class="kicker"><?php esc_html_e( 'Our message to partners', 'cohf-child' ); ?></div>
				<h2><?php esc_html_e( 'A young but determined Kenyan organisation.', 'cohf-child' ); ?></h2>
				<p><?php esc_html_e( 'We are not presenting ourselves as an organisation that has already solved the problems we seek to address. We are presenting ourselves as an organisation that has started, has learned from the communities we serve, has demonstrated the willingness to act, and is now building the systems and partnerships required to increase our impact responsibly.', 'cohf-child' ); ?></p>
				<p><?php esc_html_e( 'Our early work with women, youth and children has given us practical experience. Our Constitution gives us an institutional foundation. Our 2026-2030 strategy gives us direction. Our partnerships will give us the opportunity to take solutions further.', 'cohf-child' ); ?></p>
				<div class="quote"><?php esc_html_e( 'We invite partners to walk with us: not simply to fund activities, but to help build lasting pathways.', 'cohf-child' ); ?></div>
			</div>
			<?php cohf_the_image( 'programme-09', array( 'sizes' => '(max-width: 60em) 100vw, 50vw' ) ); ?>
		</div>
	</section>

	<section class="cream" id="who-we-partner-with">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="kicker"><?php esc_html_e( 'Who we work with', 'cohf-child' ); ?></div>
					<h2><?php esc_html_e( 'The partners we welcome.', 'cohf-child' ); ?></h2>
				</div>
			</div>
			<ul class="list-check list-check--2col">
				<?php foreach ( cohf_partner_types() as $type ) { printf( '<li>%s</li>', esc_html( $type ) ); } ?>
			</ul>
		</div>
	</section>

	<section id="opportunities">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="kicker"><?php esc_html_e( 'Partnership opportunities', 'cohf-child' ); ?></div>
					<h2><?php esc_html_e( 'What we invite partners to contribute.', 'cohf-child' ); ?></h2>
				</div>
			</div>
			<ul class="list-check list-check--2col">
				<?php foreach ( cohf_partnership_offers() as $offer ) { printf( '<li>%s</li>', esc_html( $offer ) ); } ?>
			</ul>

			<div class="section-head section-head--stacked" id="priority-areas">
				<div>
					<div class="kicker"><?php esc_html_e( 'Priority areas', 'cohf-child' ); ?></div>
					<h2><?php esc_html_e( 'Areas where we seek partnerships.', 'cohf-child' ); ?></h2>
				</div>
			</div>
			<ul class="list-check list-check--2col">
				<?php
				foreach ( array(
					__( 'Women and girls\' economic empowerment', 'cohf-child' ),
					__( 'Youth skills, employment and entrepreneurship', 'cohf-child' ),
					__( 'Education and child development', 'cohf-child' ),
					__( 'Health, nutrition and community wellbeing', 'cohf-child' ),
					__( 'Agriculture, food security and livelihoods', 'cohf-child' ),
					__( 'Environment, climate and conservation', 'cohf-child' ),
					__( 'Water, sanitation and hygiene', 'cohf-child' ),
					__( 'Digital inclusion and innovation', 'cohf-child' ),
					__( 'Community development and resilience', 'cohf-child' ),
					__( 'Institutional strengthening, safeguarding, M&E and organisational development', 'cohf-child' ),
				) as $area ) {
					printf( '<li>%s</li>', esc_html( $area ) );
				}
				?>
			</ul>
		</div>
	</section>

	<section class="sage" id="snapshot">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="kicker"><?php esc_html_e( 'Partnership snapshot', 'cohf-child' ); ?></div>
					<h2><?php esc_html_e( 'The questions partners ask us.', 'cohf-child' ); ?></h2>
				</div>
			</div>
			<div class="table-wrap">
				<table class="cohf-table">
					<thead><tr><th scope="col"><?php esc_html_e( 'Question', 'cohf-child' ); ?></th><th scope="col"><?php esc_html_e( 'Our answer', 'cohf-child' ); ?></th></tr></thead>
					<tbody>
					<?php
					$snapshot = array(
						__( 'Who are you?', 'cohf-child' )                         => __( 'Cistern of Hope Foundation (COHF), a Kenyan organisation focused on poverty eradication through community empowerment.', 'cohf-child' ),
						__( 'What is your mission?', 'cohf-child' )                => cohf_org_get( 'mission' ),
						__( 'Who do you serve?', 'cohf-child' )                    => __( 'Vulnerable children, adolescents, youth, women, people with disabilities, households and communities in Kenya.', 'cohf-child' ),
						__( 'What are your key programme areas?', 'cohf-child' )   => __( 'Education, youth empowerment, women\'s economic empowerment, health and wellbeing, livelihoods, agriculture and food security, environment, WASH, digital inclusion and community development.', 'cohf-child' ),
						__( 'What experience do you have?', 'cohf-child' )         => __( 'Women and youth enterprise support, monthly sanitary-pad support, education support, feeding programmes, youth counselling and mentorship, and community outreach.', 'cohf-child' ),
						__( 'What do you seek from partners?', 'cohf-child' )      => __( 'Funding, technical expertise, training, equipment, market linkages, mentorship, research and evaluation, co-funding and institutional strengthening.', 'cohf-child' ),
						__( 'What makes your approach distinctive?', 'cohf-child' ) => __( 'We connect immediate support with empowerment, opportunity, resilience and self-reliance under one poverty-eradication mission.', 'cohf-child' ),
					);
					foreach ( $snapshot as $question => $answer ) {
						printf( '<tr><th scope="row">%s</th><td>%s</td></tr>', esc_html( $question ), esc_html( $answer ) );
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/partner-section' ); ?>

	<section id="enquire">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="kicker"><?php esc_html_e( 'Start a conversation', 'cohf-child' ); ?></div>
					<h2><?php esc_html_e( 'Partnership enquiry.', 'cohf-child' ); ?></h2>
				</div>
				<p><?php esc_html_e( 'Tell us about your organisation and the kind of partnership you are considering. A member of the team will respond.', 'cohf-child' ); ?></p>
			</div>
			<?php get_template_part( 'template-parts/enquiry-form', null, array( 'default_type' => 'partnership' ) ); ?>
		</div>
	</section>

	<?php endif; ?>
</main>
<?php get_footer();
