<?php
/**
 * Template Name: Support Our Work
 *
 * Giving is deliberately separate from the Hope Market shop. A donation is
 * not a purchase: different flow, different receipt, different accounting
 * line. Payment is handled by Paystack, which offers M-Pesa and card on its
 * own secure step. No account number or key is hard-coded here.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();
$org = cohf_org();
$thanks = isset( $_GET['giving'] ) && 'thank-you' === $_GET['giving'];
?>
<main id="main-content" tabindex="-1">

	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'image'   => 'hero-support',
		'eyebrow' => __( 'Support our work', 'cohf-child' ),
		'title'   => __( 'Help turn hope into opportunity.', 'cohf-child' ),
		'text'    => __( 'We respond where the need is urgent, and we connect that response to education, skills, resilience and self-reliance.', 'cohf-child' ),
	) );
	?>

	<?php if ( cohf_page_body_is_blocks() ) : cohf_the_page_body(); else : ?>

	<?php if ( $thanks ) : ?>
		<section class="sage">
			<div class="container">
				<div class="give-thanks" role="status">
					<h2 class="sec-statement"><?php esc_html_e( 'Thank you. Your gift has been received.', 'cohf-child' ); ?></h2>
					<p class="sec-lede"><?php esc_html_e( 'A receipt is on its way to the email address you gave. If anything looks wrong, contact us and we will put it right.', 'cohf-child' ); ?></p>
					<?php if ( isset( $_GET['ref'] ) ) : ?>
						<p class="give-thanks__ref">
							<?php esc_html_e( 'Reference:', 'cohf-child' ); ?>
							<code><?php echo esc_html( sanitize_text_field( wp_unslash( $_GET['ref'] ) ) ); ?></code>
						</p>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<section class="warm" id="give">
		<div class="container">
			<div class="give-layout">

				<div class="give-layout__copy">
					<div class="sec-label">
						<span class="sec-label__rule"></span>
						<span class="sec-label__text"><?php esc_html_e( 'Give', 'cohf-child' ); ?></span>
					</div>
					<h2 class="sec-statement"><?php esc_html_e( 'Every contribution should have a clear purpose.', 'cohf-child' ); ?></h2>
					<p class="sec-lede"><?php esc_html_e( 'Choose an amount and the area of work you want it to strengthen. You can give once or, if you prefer, every month.', 'cohf-child' ); ?></p>

					<ul class="give-assure">
						<li><?php esc_html_e( 'M-Pesa and card, handled on Paystack\'s secure step.', 'cohf-child' ); ?></li>
						<li><?php esc_html_e( 'No card or M-Pesa details are stored on this website.', 'cohf-child' ); ?></li>
						<li><?php esc_html_e( 'Receipted separately from any Hope Market purchase.', 'cohf-child' ); ?></li>
						<li><?php esc_html_e( 'Spent against approved budgets and documented.', 'cohf-child' ); ?></li>
					</ul>

					<p class="give-assure__link">
						<a class="arrow" href="<?php echo esc_url( cohf_page_url( 'page-templates/page-accountability.php' ) ); ?>">
							<?php esc_html_e( 'How we account for what we receive', 'cohf-child' ); ?> &rarr;
						</a>
					</p>
				</div>

				<div class="give-layout__form">
					<?php get_template_part( 'template-parts/giving-form' ); ?>
				</div>

			</div>
		</div>
	</section>

	<section>
		<div class="container">
			<div class="section-head">
				<div>
					<div class="sec-label">
						<span class="sec-label__rule"></span>
						<span class="sec-label__text"><?php esc_html_e( 'Where support goes', 'cohf-child' ); ?></span>
					</div>
					<h2 class="sec-statement sec-statement--wide"><?php esc_html_e( 'What your support makes possible.', 'cohf-child' ); ?></h2>
				</div>
				<p class="sec-lede"><?php esc_html_e( 'These are the areas where additional resources have the most direct effect today. We publish no cost-per-beneficiary figures, because we will not present an estimate as if it were a verified number.', 'cohf-child' ); ?></p>
			</div>

			<div class="grid">
				<?php
				$areas = array(
					array( '01', 'programme-01', __( 'Keep a child in school', 'cohf-child' ),            __( 'School fees, exercise books, stationery, textbooks, uniforms, learning materials and school-related food support for vulnerable children.', 'cohf-child' ) ),
					array( '02', 'programme-06', __( 'Protect menstrual dignity', 'cohf-child' ),        __( 'Sanitary pads and menstrual-health education, supporting the monthly distribution that currently reaches more than 200 girls.', 'cohf-child' ) ),
					array( '03', 'programme-03', __( 'Start or strengthen an enterprise', 'cohf-child' ), __( 'Start-up support, business-management and financial-literacy training, mentorship and market linkages for women and young people.', 'cohf-child' ) ),
				);
				foreach ( $areas as $area ) :
					?>
					<article class="card">
						<?php cohf_the_image( $area[1], array( 'sizes' => '(max-width: 60em) 100vw, 33vw' ) ); ?>
						<div class="card-body">
							<div class="kicker"><?php echo esc_html( $area[0] ); ?></div>
							<h3><?php echo esc_html( $area[2] ); ?></h3>
							<p><?php echo esc_html( $area[3] ); ?></p>
						</div>
					</article>
					<?php
				endforeach;
				?>
			</div>
		</div>
	</section>

	<section class="cream">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="sec-label">
						<span class="sec-label__rule"></span>
						<span class="sec-label__text"><?php esc_html_e( 'Other ways', 'cohf-child' ); ?></span>
					</div>
					<h2 class="sec-statement sec-statement--wide"><?php esc_html_e( 'Giving money is not the only way to help.', 'cohf-child' ); ?></h2>
				</div>
			</div>

			<div class="purpose">
				<article>
					<h3><?php esc_html_e( 'Partner with us', 'cohf-child' ); ?></h3>
					<p><?php esc_html_e( 'Programme grants, multi-year partnerships, technical assistance, equipment, market linkages and co-funding.', 'cohf-child' ); ?></p>
					<a class="arrow" href="<?php echo esc_url( cohf_page_url( 'page-templates/page-partners.php' ) ); ?>"><?php esc_html_e( 'Partnership options', 'cohf-child' ); ?> &rarr;</a>
				</article>
				<article>
					<h3><?php esc_html_e( 'Volunteer your skills', 'cohf-child' ); ?></h3>
					<p><?php esc_html_e( 'Mentorship, training, professional expertise and time given to programmes and to the young people in them.', 'cohf-child' ); ?></p>
					<a class="arrow" href="<?php echo esc_url( cohf_page_url( 'page-templates/page-get-involved.php' ) ); ?>"><?php esc_html_e( 'Ways to get involved', 'cohf-child' ); ?> &rarr;</a>
				</article>
				<article>
					<h3><?php esc_html_e( 'Buy from Hope Market', 'cohf-child' ); ?></h3>
					<p><?php esc_html_e( 'Crafts made by the people in our enterprise programmes. Buying supports the maker and the mission at once.', 'cohf-child' ); ?></p>
					<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
						<a class="arrow" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Visit Hope Market', 'cohf-child' ); ?> &rarr;</a>
					<?php endif; ?>
				</article>
			</div>
		</div>
	</section>

	<section class="impact on-dark">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="sec-label">
						<span class="sec-label__rule"></span>
						<span class="sec-label__text"><?php esc_html_e( 'Our promise', 'cohf-child' ); ?></span>
					</div>
					<h2 class="sec-statement sec-statement--wide"><?php esc_html_e( 'What we owe anyone who gives.', 'cohf-child' ); ?></h2>
				</div>
				<p class="sec-lede"><?php esc_html_e( 'Trust is one of our most important institutional assets. These are the commitments we hold ourselves to.', 'cohf-child' ); ?></p>
			</div>

			<ul class="list-check list-check--2col">
				<?php
				foreach ( array(
					__( 'We prepare and work from approved budgets.', 'cohf-child' ),
					__( 'We maintain appropriate financial records and supporting documentation.', 'cohf-child' ),
					__( 'We monitor expenditure against approved programme budgets.', 'cohf-child' ),
					__( 'We maintain appropriate records for donor-funded activities.', 'cohf-child' ),
					__( 'We support transparent reporting and independent audit or review.', 'cohf-child' ),
					__( 'We protect the dignity and confidentiality of beneficiaries when documenting our work.', 'cohf-child' ),
				) as $promise ) {
					printf( '<li>%s</li>', esc_html( $promise ) );
				}
				?>
			</ul>

			<p class="stack-lg">
				<a class="btn light" href="<?php echo esc_url( cohf_page_url( 'page-templates/page-accountability.php' ) ); ?>"><?php esc_html_e( 'Read Our Accountability Commitments', 'cohf-child' ); ?></a>
			</p>
		</div>
	</section>

	<?php
	get_template_part( 'template-parts/cta', null, array(
		'title'           => __( 'Prefer to partner rather than give?', 'cohf-child' ),
		'text'            => __( 'We welcome programme grants, multi-year partnerships, technical assistance, equipment, market linkages and co-funding.', 'cohf-child' ),
		'primary_label'   => __( 'Partner With Us', 'cohf-child' ),
		'primary_url'     => cohf_page_url( 'page-templates/page-partners.php' ),
		'secondary_label' => __( 'Contact Us', 'cohf-child' ),
		'secondary_url'   => cohf_page_url( 'page-templates/page-contact.php' ),
	) );
	?>

	<?php endif; ?>
</main>
<?php get_footer();
