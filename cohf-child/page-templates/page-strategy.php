<?php
/**
 * Template Name: Strategic Journey
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main-content" tabindex="-1">

	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'image'   => 'programme-09',
		'eyebrow' => __( 'Strategic framework', 'cohf-child' ),
		'title'   => __( 'Our Strategic Journey, 2026-2030', 'cohf-child' ),
		'text'    => __( 'Our five-year direction: what we will build, in what order, and what success means to us.', 'cohf-child' ),
	) );

	get_template_part( 'template-parts/section-nav', null, array(
		'sections' => array(
			'#journey'     => __( 'Five-year journey', 'cohf-child' ),
			'#objectives'  => __( 'Objectives', 'cohf-child' ),
			'#aspirations' => __( 'Aspirations', 'cohf-child' ),
		),
	) );
	?>

	<?php if ( cohf_page_body_is_blocks() ) : cohf_the_page_body(); else : ?>

	<section id="journey">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="kicker"><?php esc_html_e( 'Five-year journey', 'cohf-child' ); ?></div>
					<h2><?php esc_html_e( 'Establish, consolidate, scale, deepen, sustain.', 'cohf-child' ); ?></h2>
				</div>
				<p><?php esc_html_e( 'A deliberate journey to become stronger as we grow.', 'cohf-child' ); ?></p>
			</div>
			<?php get_template_part( 'template-parts/timeline' ); ?>
		</div>
	</section>

	<section class="cream" id="objectives">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="kicker"><?php esc_html_e( 'Strategic objectives', 'cohf-child' ); ?></div>
					<h2><?php esc_html_e( 'Eight interconnected objectives.', 'cohf-child' ); ?></h2>
				</div>
			</div>
			<div class="table-wrap">
				<table class="cohf-table">
					<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'Code', 'cohf-child' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Objective', 'cohf-child' ); ?></th>
							<th scope="col"><?php esc_html_e( 'What success means to us', 'cohf-child' ); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php
					$objectives = array(
						array( 'SO1', __( 'Education and child development', 'cohf-child' ),             __( 'More vulnerable children access, remain in and benefit from education, mentorship, protection and holistic support.', 'cohf-child' ) ),
						array( 'SO2', __( 'Youth empowerment', 'cohf-child' ),                          __( 'Young people gain skills, confidence, employability, entrepreneurship and livelihood pathways.', 'cohf-child' ) ),
						array( 'SO3', __( 'Women\'s economic empowerment', 'cohf-child' ),              __( 'Women strengthen enterprise capacity, income opportunities, financial resilience and market access.', 'cohf-child' ) ),
						array( 'SO4', __( 'Poverty reduction and household resilience', 'cohf-child' ), __( 'Vulnerable households receive appropriate support and develop pathways toward recovery and resilience.', 'cohf-child' ) ),
						array( 'SO5', __( 'Counselling, mentorship and wellbeing', 'cohf-child' ),      __( 'Children, adolescents, youth and adults have improved access to counselling, mentorship and life skills.', 'cohf-child' ) ),
						array( 'SO6', __( 'Health, hygiene and dignity', 'cohf-child' ),                __( 'Communities experience improved awareness and access related to health, nutrition, menstrual dignity and hygiene.', 'cohf-child' ) ),
						array( 'SO7', __( 'Community development and partnerships', 'cohf-child' ),     __( 'Communities participate actively and the Foundation builds meaningful, strategic partnerships.', 'cohf-child' ) ),
						array( 'SO8', __( 'Institutional sustainability', 'cohf-child' ),               __( 'We strengthen governance, finance, safeguarding, monitoring and evaluation, human resources, fundraising and communications.', 'cohf-child' ) ),
					);
					foreach ( $objectives as $objective ) {
						printf(
							'<tr><th scope="row">%s</th><td><strong>%s</strong></td><td>%s</td></tr>',
							esc_html( $objective[0] ),
							esc_html( $objective[1] ),
							esc_html( $objective[2] )
						);
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/theory-of-change' ); ?>

	<section class="sage" id="aspirations">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="kicker"><?php esc_html_e( '2030 aspirations', 'cohf-child' ); ?></div>
					<h2><?php esc_html_e( 'The direction in which we are working.', 'cohf-child' ); ?></h2>
				</div>
				<p><?php esc_html_e( 'These aspirations guide our annual plans and project-specific targets. They are directions of travel, not numerical promises.', 'cohf-child' ); ?></p>
			</div>
			<div class="table-wrap">
				<table class="cohf-table">
					<thead><tr><th scope="col"><?php esc_html_e( 'Area', 'cohf-child' ); ?></th><th scope="col"><?php esc_html_e( '2030 aspiration', 'cohf-child' ); ?></th></tr></thead>
					<tbody>
					<?php
					$aspirations = array(
						__( 'Education', 'cohf-child' )                  => __( 'Expand structured support for vulnerable learners and strengthen retention, mentorship and holistic support.', 'cohf-child' ),
						__( 'Youth', 'cohf-child' )                      => __( 'Support a growing number of young people through skills, enterprise, employability, mentorship and opportunity pathways.', 'cohf-child' ),
						__( 'Women', 'cohf-child' )                      => __( 'Expand women\'s enterprise support and strengthen the number and sustainability of women-led businesses.', 'cohf-child' ),
						__( 'Counselling and mentorship', 'cohf-child' ) => __( 'Increase structured counselling, mentorship and life-skills engagement for children, adolescents and youth.', 'cohf-child' ),
						__( 'Menstrual dignity', 'cohf-child' )          => __( 'Expand reliable access to sanitary products and menstrual-health information.', 'cohf-child' ),
						__( 'Health and wellbeing', 'cohf-child' )       => __( 'Develop stronger community-health and referral partnerships.', 'cohf-child' ),
						__( 'Environment', 'cohf-child' )                => __( 'Establish practical community and youth-led environmental initiatives.', 'cohf-child' ),
						__( 'WASH', 'cohf-child' )                       => __( 'Expand hygiene, sanitation, safe-water and menstrual-dignity programming.', 'cohf-child' ),
						__( 'Partnerships', 'cohf-child' )               => __( 'Build a diversified network of strategic programme, technical and funding partners.', 'cohf-child' ),
						__( 'Institution', 'cohf-child' )                => __( 'Become a credible, accountable, well-governed and evidence-driven Kenyan organisation capable of managing larger partnerships responsibly.', 'cohf-child' ),
					);
					foreach ( $aspirations as $area => $aspiration ) {
						printf( '<tr><th scope="row">%s</th><td>%s</td></tr>', esc_html( $area ), esc_html( $aspiration ) );
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/cta' ); ?>

	<?php endif; ?>
</main>
<?php get_footer();
