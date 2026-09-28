<?php
/**
 * Template Name: Impact
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main-content" class="impact-page" tabindex="-1">

	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'image'   => 'hero-impact',
		'eyebrow' => __( 'Our impact', 'cohf-child' ),
		'title'   => __( 'Turning Hope into Meaningful Change', 'cohf-child' ),
		'text'    => __( 'Impact is measured by the difference our work makes in people\'s lives.', 'cohf-child' ),
	) );
	?>

	<section class="impact" id="reported">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="sec-label">
						<span class="sec-label__rule"></span>
						<span class="sec-label__text"><?php esc_html_e( 'Current reported position', 'cohf-child' ); ?></span>
					</div>
					<h2 class="sec-statement sec-statement--wide"><?php esc_html_e( 'What we have already started achieving.', 'cohf-child' ); ?></h2>
				</div>
				<p class="sec-lede"><?php esc_html_e( 'Our strategic framework grows from work we have already started. These are the figures recorded in our own programme records.', 'cohf-child' ); ?></p>
			</div>

			<?php get_template_part( 'template-parts/numbers' ); ?>

			<p class="impact-disclaimer stack-lg">
				<?php esc_html_e( 'These figures represent the Foundation\'s reported programme experience and current starting point. They are not lifetime totals. Individual projects and donor submissions contain the detailed evidence, dates, locations, budgets and beneficiary records relevant to each intervention.', 'cohf-child' ); ?>
			</p>
		</div>
	</section>

	<!-- Education -->
	<section id="children">
		<div class="container feature">
			<div>
				<div class="sec-label">
					<span class="sec-label__rule"></span>
					<span class="sec-label__text"><?php esc_html_e( 'Education', 'cohf-child' ); ?></span>
				</div>
				<h2><?php esc_html_e( 'Helping vulnerable children stay in school.', 'cohf-child' ); ?></h2>
				<p><?php esc_html_e( 'In June 2026, three children who had been living on the streets were supported to return to school, with ongoing responsibility for their educational needs including school fees, books, learning materials and food support.', 'cohf-child' ); ?></p>
				<div class="quote"><?php esc_html_e( 'Reach people. Restore hope. Create opportunity. Build resilience. Sustain change.', 'cohf-child' ); ?></div>
			</div>
			<?php cohf_the_image( 'programme-01', array( 'sizes' => '(max-width: 60em) 100vw, 50vw' ) ); ?>
		</div>
	</section>

	<!-- Women and livelihoods -->
	<section class="cream" id="women">
		<div class="container feature">
			<?php cohf_the_image( 'programme-03', array( 'sizes' => '(max-width: 60em) 100vw, 50vw' ) ); ?>
			<div>
				<div class="sec-label">
					<span class="sec-label__rule"></span>
					<span class="sec-label__text"><?php esc_html_e( 'Women and livelihoods', 'cohf-child' ); ?></span>
				</div>
				<h2><?php esc_html_e( 'Empowerment creates pathways beyond short-term relief.', 'cohf-child' ); ?></h2>
				<p><?php esc_html_e( 'We measure progress not simply by the number of women trained, but by the extent to which supported women are able to sustain and grow viable economic activities.', 'cohf-child' ); ?></p>
				<ul class="list-check stack-sm">
					<?php
					foreach ( array(
						__( 'Small-business start-up and strengthening support', 'cohf-child' ),
						__( 'Entrepreneurship and business-management training', 'cohf-child' ),
						__( 'Financial literacy and savings linkages', 'cohf-child' ),
						__( 'Market access and business linkages', 'cohf-child' ),
					) as $item ) {
						printf( '<li>%s</li>', esc_html( $item ) );
					}
					?>
				</ul>
			</div>
		</div>
	</section>

	<!-- Across our work -->
	<section class="sage" id="across">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="sec-label">
						<span class="sec-label__rule"></span>
						<span class="sec-label__text"><?php esc_html_e( 'Across our work', 'cohf-child' ); ?></span>
					</div>
					<h2 class="sec-statement sec-statement--wide"><?php esc_html_e( 'Where else change is taking hold.', 'cohf-child' ); ?></h2>
				</div>
			</div>
			<div class="purpose">
				<article>
					<h3><?php esc_html_e( 'Youth empowerment', 'cohf-child' ); ?></h3>
					<p><?php esc_html_e( 'Through mentorship, counselling, career guidance and life-skills activities we help young people make informed choices and identify pathways towards education, employment and entrepreneurship. These programmes run quarterly.', 'cohf-child' ); ?></p>
				</article>
				<article>
					<h3><?php esc_html_e( 'Menstrual dignity', 'cohf-child' ); ?></h3>
					<p><?php esc_html_e( 'Every month we provide sanitary pads to more than 200 girls, reducing absenteeism, discomfort and stigma, and supporting continued participation in school and community life.', 'cohf-child' ); ?></p>
				</article>
				<article>
					<h3><?php esc_html_e( 'Community outreach', 'cohf-child' ); ?></h3>
					<p><?php esc_html_e( 'Our documented 19 August 2026 programme at Kabete "N" reached 82 children, including 26 teenagers and 56 children below the teenage years, combining feeding with counselling, mentorship and recreation.', 'cohf-child' ); ?></p>
				</article>
			</div>
		</div>
	</section>

	<!-- How we measure -->
	<section id="how-we-measure">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="sec-label">
						<span class="sec-label__rule"></span>
						<span class="sec-label__text"><?php esc_html_e( 'Monitoring, evaluation and learning', 'cohf-child' ); ?></span>
					</div>
					<h2 class="sec-statement sec-statement--wide"><?php esc_html_e( 'Beyond counting activities.', 'cohf-child' ); ?></h2>
				</div>
				<p class="sec-lede"><?php esc_html_e( 'Our approach moves beyond counting activities to understanding change.', 'cohf-child' ); ?></p>
			</div>

			<div class="table-wrap">
				<table class="cohf-table">
					<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'What we measure', 'cohf-child' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Examples', 'cohf-child' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$measures = array(
							__( 'Reach', 'cohf-child' )          => __( 'Women, youth, children, households and communities reached.', 'cohf-child' ),
							__( 'Activities', 'cohf-child' )     => __( 'Trainings, counselling forums, feeding activities, distributions, enterprise support.', 'cohf-child' ),
							__( 'Outputs', 'cohf-child' )        => __( 'People trained, children supported, businesses established, outreach delivered.', 'cohf-child' ),
							__( 'Outcomes', 'cohf-child' )       => __( 'School participation, enterprise continuation, skills gained, referrals completed.', 'cohf-child' ),
							__( 'Quality', 'cohf-child' )        => __( 'Participant feedback, safeguarding performance, complaints resolution.', 'cohf-child' ),
							__( 'Sustainability', 'cohf-child' ) => __( 'Continued operation of supported enterprises and continuation of benefits after funding.', 'cohf-child' ),
						);
						foreach ( $measures as $what => $example ) {
							printf( '<tr><th scope="row">%s</th><td>%s</td></tr>', esc_html( $what ), esc_html( $example ) );
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</section>

	<?php
	get_template_part( 'template-parts/cta', null, array(
		'title' => __( 'Help us close the gap between need and capacity.', 'cohf-child' ),
		'text'  => __( 'The demand for support is greater than the resources currently available to us. Responsible partnerships allow us to reach more children, young people and women.', 'cohf-child' ),
	) );
	?>

</main>
<?php get_footer();
