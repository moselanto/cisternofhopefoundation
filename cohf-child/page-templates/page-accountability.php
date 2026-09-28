<?php
/**
 * Template Name: Accountability & Safeguarding
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();

$contact   = cohf_page_url( 'page-templates/page-contact.php' );
$resources = cohf_page_url( 'page-templates/page-resources.php' );
?>
<main id="main-content" tabindex="-1">

	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'image'   => 'hero-accountability',
		'eyebrow' => __( 'Accountability &amp; safeguarding', 'cohf-child' ),
		'title'   => __( 'Trust is one of our most important institutional assets.', 'cohf-child' ),
		'text'    => __( 'We accept responsibility for the resources entrusted to us, the people we serve, and the way we document, communicate and learn from our work.', 'cohf-child' ),
	) );

	/*
	 * Someone arriving to raise a safeguarding concern or read the complaints
	 * procedure should not have to scroll through four sections to find it.
	 */
	get_template_part( 'template-parts/section-nav', null, array(
		'sections' => array(
			'#financial'       => __( 'Financial', 'cohf-child' ),
			'#safeguarding'    => __( 'Safeguarding', 'cohf-child' ),
			'#data-protection' => __( 'Data protection', 'cohf-child' ),
			'#complaints'      => __( 'Complaints', 'cohf-child' ),
			'#policies'        => __( 'Policies', 'cohf-child' ),
		),
	) );
	?>

	<!-- Four pillars -->
	<section>
		<div class="container">
			<div class="purpose">
				<?php
				$pillars = array(
					array( '01', __( 'Financial accountability', 'cohf-child' ), __( 'Budgets, financial oversight, supporting documentation and responsible reporting.', 'cohf-child' ), '#financial' ),
					array( '02', __( 'Safeguarding', 'cohf-child' ),            __( 'Protecting children and vulnerable people and maintaining safe programme environments.', 'cohf-child' ), '#safeguarding' ),
					array( '03', __( 'Data protection', 'cohf-child' ),         __( 'Protecting confidential beneficiary information and using stories and photographs responsibly.', 'cohf-child' ), '#data-protection' ),
					array( '04', __( 'Complaints &amp; feedback', 'cohf-child' ), __( 'A clear route for communities, beneficiaries, partners and the public to raise concerns.', 'cohf-child' ), '#complaints' ),
				);
				foreach ( $pillars as $pillar ) :
					?>
					<a class="card" href="<?php echo esc_attr( $pillar[3] ); ?>">
						<div class="card-body">
							<div class="kicker"><?php echo esc_html( $pillar[0] ); ?></div>
							<h3><?php echo esc_html( wp_strip_all_tags( $pillar[1] ) ); ?></h3>
							<p><?php echo esc_html( $pillar[2] ); ?></p>
						</div>
					</a>
					<?php
				endforeach;
				?>
			</div>
		</div>
	</section>

	<!-- Financial -->
	<section class="cream anchor-offset" id="financial">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="kicker"><?php esc_html_e( 'Financial accountability', 'cohf-child' ); ?></div>
					<h2><?php esc_html_e( 'Responsible stewardship.', 'cohf-child' ); ?></h2>
				</div>
				<p><?php esc_html_e( 'Our Constitution provides for annual budgeting, financial oversight, annual audit by a certified auditor and financial reporting to donors, stakeholders and the public.', 'cohf-child' ); ?></p>
			</div>

			<ul class="list-check list-check--2col">
				<?php
				foreach ( array(
					__( 'We prepare and work from approved budgets.', 'cohf-child' ),
					__( 'We maintain appropriate financial records and supporting documentation.', 'cohf-child' ),
					__( 'We separate authorisation and accountability responsibilities as our systems develop.', 'cohf-child' ),
					__( 'We monitor expenditure against approved programme budgets.', 'cohf-child' ),
					__( 'We maintain appropriate records for donor-funded activities.', 'cohf-child' ),
					__( 'We support transparent reporting and independent audit or review.', 'cohf-child' ),
				) as $item ) {
					printf( '<li>%s</li>', esc_html( $item ) );
				}
				?>
			</ul>
		</div>
	</section>

	<!-- Safeguarding -->
	<section class="anchor-offset" id="safeguarding">
		<div class="container feature">
			<div>
				<div class="kicker"><?php esc_html_e( 'Safeguarding &amp; child protection', 'cohf-child' ); ?></div>
				<h2><?php esc_html_e( 'People deserve to feel safe, respected and protected.', 'cohf-child' ); ?></h2>
				<p><?php esc_html_e( 'Because we work with children, women, youth and vulnerable communities, safeguarding is central to who we are.', 'cohf-child' ); ?></p>
				<div class="purpose purpose--2col stack-md">
					<article>
						<h3><?php esc_html_e( 'Protect', 'cohf-child' ); ?></h3>
						<p><?php esc_html_e( 'Protect children and vulnerable people from abuse, exploitation, discrimination and avoidable harm.', 'cohf-child' ); ?></p>
					</article>
					<article>
						<h3><?php esc_html_e( 'Respond', 'cohf-child' ); ?></h3>
						<p><?php esc_html_e( 'Maintain appropriate reporting and referral mechanisms and respond appropriately to concerns and allegations.', 'cohf-child' ); ?></p>
					</article>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<h3><?php esc_html_e( 'Our safeguarding commitments', 'cohf-child' ); ?></h3>
					<ul class="list-check stack-xs">
						<?php
						foreach ( array(
							__( 'Safe and respectful programme environments', 'cohf-child' ),
							__( 'Confidential beneficiary information', 'cohf-child' ),
							__( 'Responsible photography and storytelling', 'cohf-child' ),
							__( 'Qualified professionals for specialist services', 'cohf-child' ),
							__( 'Safeguarding expectations for staff and volunteers', 'cohf-child' ),
						) as $item ) {
							printf( '<li>%s</li>', esc_html( $item ) );
						}
						?>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<!-- Data protection -->
	<section class="sage anchor-offset" id="data-protection">
		<div class="container feature">
			<div class="card">
				<div class="card-body">
					<div class="kicker"><?php esc_html_e( 'Privacy &amp; data protection', 'cohf-child' ); ?></div>
					<h3><?php esc_html_e( 'Respecting the information entrusted to us.', 'cohf-child' ); ?></h3>
					<p><?php esc_html_e( 'We protect confidential beneficiary information and promote responsible use of photographs, stories and personal information.', 'cohf-child' ); ?></p>
					<p><?php esc_html_e( 'Our communication should protect the dignity and confidentiality of the people whose experiences we share.', 'cohf-child' ); ?></p>
				</div>
			</div>
			<div>
				<h2><?php esc_html_e( 'Privacy is part of dignity.', 'cohf-child' ); ?></h2>
				<p><?php esc_html_e( 'Data protection is integrated into our safeguarding and accountability approach. Approved policies are published through the Resources section as they are finalised.', 'cohf-child' ); ?></p>
				<a class="btn dark" href="<?php echo esc_url( $resources ); ?>"><?php esc_html_e( 'View Resources &amp; Policies', 'cohf-child' ); ?></a>
			</div>
		</div>
	</section>

	<!-- Complaints -->
	<section class="anchor-offset" id="complaints">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="kicker"><?php esc_html_e( 'Complaints &amp; feedback', 'cohf-child' ); ?></div>
					<h2><?php esc_html_e( 'We want to hear when something is not right.', 'cohf-child' ); ?></h2>
				</div>
				<p><?php esc_html_e( 'Communities, beneficiaries, partners and members of the public can raise concerns or give feedback on our work. Concerns are treated seriously and confidentially.', 'cohf-child' ); ?></p>
			</div>
			<div class="cta-band">
				<div>
					<h2><?php esc_html_e( 'Raise a concern.', 'cohf-child' ); ?></h2>
					<p><?php esc_html_e( 'Use the contact form and select "Complaint or feedback", or contact the Foundation directly.', 'cohf-child' ); ?></p>
				</div>
				<a class="btn gold" href="<?php echo esc_url( $contact ); ?>#enquire"><?php esc_html_e( 'Raise a Concern', 'cohf-child' ); ?></a>
			</div>
		</div>
	</section>

	<!-- Policies -->
	<section class="cream" id="policies">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="kicker"><?php esc_html_e( 'Institutional policies', 'cohf-child' ); ?></div>
					<h2><?php esc_html_e( 'Policies that govern our work.', 'cohf-child' ); ?></h2>
				</div>
				<p><?php esc_html_e( 'Documents are published through the Resources section as each policy is finalised and approved.', 'cohf-child' ); ?></p>
			</div>
			<div class="grid">
				<article class="card">
					<div class="card-body">
						<h3><?php esc_html_e( 'Safeguarding &amp; protection', 'cohf-child' ); ?></h3>
						<p><?php esc_html_e( 'Child Safeguarding and Protection Policy. PSEA and Safeguarding Policy.', 'cohf-child' ); ?></p>
					</div>
				</article>
				<article class="card">
					<div class="card-body">
						<h3><?php esc_html_e( 'Integrity &amp; finance', 'cohf-child' ); ?></h3>
						<p><?php esc_html_e( 'Code of Conduct. Financial Management. Procurement. Anti-Fraud and Anti-Corruption.', 'cohf-child' ); ?></p>
					</div>
				</article>
				<article class="card">
					<div class="card-body">
						<h3><?php esc_html_e( 'People &amp; accountability', 'cohf-child' ); ?></h3>
						<p><?php esc_html_e( 'Conflict of Interest. Whistleblowing. Data Protection and Privacy. Complaints and Feedback. Monitoring, Evaluation and Learning.', 'cohf-child' ); ?></p>
					</div>
				</article>
			</div>
		</div>
	</section>

</main>
<?php get_footer();
