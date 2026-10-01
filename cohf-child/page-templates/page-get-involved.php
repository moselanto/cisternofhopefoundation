<?php
/**
 * Template Name: Get Involved
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();

$contact = cohf_page_url( 'page-templates/page-contact.php' );
$cta     = cohf_cta_links();
?>
<main id="main-content" tabindex="-1">

	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'image'   => 'hero-get-involved',
		'eyebrow' => __( 'Join the work', 'cohf-child' ),
		'title'   => __( 'Get Involved', 'cohf-child' ),
		'text'    => __( 'There are many ways to contribute to stronger pathways for communities.', 'cohf-child' ),
	) );
	?>

	<?php if ( cohf_page_body_is_blocks() ) : cohf_the_page_body(); else : ?>

	<section>
		<div class="container">
			<div class="grid">
				<?php
				$routes = array(
					array( '01', __( 'Partner With Us', 'cohf-child' ),    __( 'Explore programme, technical, market, research and institutional partnerships.', 'cohf-child' ), $cta['partner'], __( 'Explore partnerships', 'cohf-child' ) ),
					array( '02', __( 'Support Our Work', 'cohf-child' ),   __( 'Support programmes and strengthen pathways toward self-reliance.', 'cohf-child' ), $cta['support'], __( 'Give now', 'cohf-child' ) ),
					array( '03', __( 'Volunteer &amp; Mentor', 'cohf-child' ), __( 'Bring your time, skills, relationships or professional expertise.', 'cohf-child' ), $contact . '#enquire', __( 'Volunteer with us', 'cohf-child' ) ),
				);
				foreach ( $routes as $route ) :
					?>
					<article class="card">
						<div class="card-body">
							<div class="kicker"><?php echo esc_html( $route[0] ); ?></div>
							<h3><?php echo esc_html( wp_strip_all_tags( $route[1] ) ); ?></h3>
							<p><?php echo esc_html( $route[2] ); ?></p>
							<a class="arrow" href="<?php echo esc_url( $route[3] ); ?>"><?php echo esc_html( $route[4] ); ?></a>
						</div>
					</article>
					<?php
				endforeach;
				?>
			</div>
		</div>
	</section>

	<!-- Other ways -->
	<section class="cream help-ways">
		<div class="container">
			<div class="section-head help-ways__head">
				<div>
					<div class="kicker"><?php esc_html_e( 'Other ways to help', 'cohf-child' ); ?></div>
					<h2><?php esc_html_e( 'Practical contributions that go further.', 'cohf-child' ); ?></h2>
					<p><?php esc_html_e( 'Not every contribution is money. Organisations, professionals and businesses can strengthen our work in ways that last.', 'cohf-child' ); ?></p>
				</div>
			</div>
			<div class="help-ways__grid">
				<?php
				$cohf_icons = array(
					'sponsor'   => '<path d="M12 21s-7-4.4-9.3-8.6A5.3 5.3 0 0 1 12 6.6a5.3 5.3 0 0 1 9.3 5.8C19 16.6 12 21 12 21Z"/>',
					'technical' => '<path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-2.6 2.6-2.4-.6-.6-2.4 2.6-2.6Z"/>',
					'inkind'    => '<path d="M21 8l-9-5-9 5v8l9 5 9-5V8Z"/><path d="M3 8l9 5 9-5M12 13v8"/>',
					'market'    => '<path d="M3 9l2-5h14l2 5"/><path d="M4 9v11h16V9"/><path d="M9 20v-6h6v6"/><path d="M3 9h18"/>',
				);
				$cohf_others = array(
					array( 'sponsor', __( 'Sponsor a programme', 'cohf-child' ), __( 'Fund a defined programme area for a defined period, with agreed indicators and regular reporting.', 'cohf-child' ), __( 'Discuss a sponsorship', 'cohf-child' ) ),
					array( 'technical', __( 'Provide technical support', 'cohf-child' ), __( 'Share expertise in health, agriculture, WASH, digital skills, monitoring and evaluation or safeguarding.', 'cohf-child' ), __( 'Offer your expertise', 'cohf-child' ) ),
					array( 'inkind', __( 'Give in-kind support', 'cohf-child' ), __( 'Contribute equipment, learning materials, sanitary products, food or other practical resources.', 'cohf-child' ), __( 'Donate items', 'cohf-child' ) ),
					array( 'market', __( 'Offer market linkages', 'cohf-child' ), __( 'Connect the women and young people we support to buyers, jobs and business networks.', 'cohf-child' ), __( 'Make a connection', 'cohf-child' ) ),
				);
				foreach ( $cohf_others as $i => $o ) :
					?>
					<article class="help-way">
						<span class="help-way__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<span class="help-way__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><?php echo $cohf_icons[ $o[0] ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed SVG paths. ?></svg></span>
						<h3><?php echo esc_html( $o[1] ); ?></h3>
						<p><?php echo esc_html( $o[2] ); ?></p>
						<a class="help-way__link" href="<?php echo esc_url( $contact . '#enquire' ); ?>"><?php echo esc_html( $o[3] ); ?> <span aria-hidden="true">&rarr;</span></a>
					</article>
				<?php endforeach; ?>
			</div>
			<div class="help-ways__band">
				<div>
					<strong><?php esc_html_e( 'Not sure where you fit?', 'cohf-child' ); ?></strong>
					<span><?php esc_html_e( 'Tell us what you can offer and we will suggest the best way to work together.', 'cohf-child' ); ?></span>
				</div>
				<a class="btn dark" href="#enquire"><?php esc_html_e( 'Start a conversation', 'cohf-child' ); ?></a>
			</div>
		</div>
	</section>

	<!-- Volunteering -->
	<section>
		<div class="container feature">
			<?php cohf_the_image( 'programme-04', array( 'sizes' => '(max-width: 60em) 100vw, 50vw' ) ); ?>
			<div>
				<div class="kicker"><?php esc_html_e( 'Volunteering', 'cohf-child' ); ?></div>
				<h2><?php esc_html_e( 'Volunteers are part of our journey.', 'cohf-child' ); ?></h2>
				<p><?php esc_html_e( 'We value volunteers not simply as extra hands, but as people who bring skills, relationships, ideas and community knowledge.', 'cohf-child' ); ?></p>
				<p><?php esc_html_e( 'We create clear roles, appropriate supervision, ethical standards and safeguarding expectations for everyone working on behalf of the Foundation. Volunteers working with children or vulnerable adults are subject to our Child Safeguarding and Protection Policy and our Code of Conduct.', 'cohf-child' ); ?></p>
			</div>
		</div>
	</section>

	<!-- Enquiry -->
	<section class="sage" id="enquire">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="kicker"><?php esc_html_e( 'Start a conversation', 'cohf-child' ); ?></div>
					<h2><?php esc_html_e( 'Tell us how you would like to take part.', 'cohf-child' ); ?></h2>
				</div>
			</div>
			<?php get_template_part( 'template-parts/enquiry-form', null, array( 'default_type' => 'volunteer' ) ); ?>
		</div>
	</section>

	<?php endif; ?>
</main>
<?php get_footer();
