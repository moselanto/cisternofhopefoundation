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
					array( '01', __( 'Partner With Us', 'cohf-child' ),    __( 'Explore programme, technical, market, research and institutional partnerships.', 'cohf-child' ), $cta['partner'] ),
					array( '02', __( 'Support Our Work', 'cohf-child' ),   __( 'Support programmes and strengthen pathways toward self-reliance.', 'cohf-child' ), $cta['support'] ),
					array( '03', __( 'Volunteer &amp; Mentor', 'cohf-child' ), __( 'Bring your time, skills, relationships or professional expertise.', 'cohf-child' ), $contact . '#enquire' ),
				);
				foreach ( $routes as $route ) :
					?>
					<article class="card">
						<div class="card-body">
							<div class="kicker"><?php echo esc_html( $route[0] ); ?></div>
							<h3><?php echo esc_html( wp_strip_all_tags( $route[1] ) ); ?></h3>
							<p><?php echo esc_html( $route[2] ); ?></p>
							<a class="arrow" href="<?php echo esc_url( $route[3] ); ?>"><?php esc_html_e( 'Start a conversation', 'cohf-child' ); ?></a>
						</div>
					</article>
					<?php
				endforeach;
				?>
			</div>
		</div>
	</section>

	<!-- Other ways -->
	<section class="cream">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="kicker"><?php esc_html_e( 'Other ways to help', 'cohf-child' ); ?></div>
					<h2><?php esc_html_e( 'Practical contributions that go further.', 'cohf-child' ); ?></h2>
				</div>
			</div>
			<div class="grid">
				<?php
				$others = array(
					array( __( 'Sponsor a programme', 'cohf-child' ),       __( 'Fund a defined programme area for a defined period, with agreed indicators and reporting.', 'cohf-child' ) ),
					array( __( 'Provide technical support', 'cohf-child' ), __( 'Offer expertise in health, agriculture, WASH, digital skills, monitoring and evaluation or safeguarding.', 'cohf-child' ) ),
					array( __( 'Provide in-kind support', 'cohf-child' ),   __( 'Contribute equipment, learning materials, sanitary products, food support or other practical resources.', 'cohf-child' ) ),
					array( __( 'Offer market linkages', 'cohf-child' ),     __( 'Connect supported enterprises to buyers, employment opportunities and business networks.', 'cohf-child' ) ),
				);
				foreach ( $others as $o ) {
					printf(
						'<article class="card"><div class="card-body"><h3>%1$s</h3><p>%2$s</p></div></article>',
						esc_html( $o[0] ),
						esc_html( $o[1] )
					);
				}
				?>
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
