<?php
/**
 * Template Name: Leadership & Governance
 *
 * Leadership is presented as a governance structure, not a flat list:
 * Executive Leadership, then the Board, then Management & Operations.
 * Donors and institutional partners read this page to judge whether the
 * Foundation is properly governed, so the hierarchy has to be legible.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();

$org  = cohf_org();
$acct = cohf_page_url( 'page-templates/page-accountability.php' );

$any = new WP_Query(
	array(
		'post_type'      => 'cohf_leader',
		'posts_per_page' => 1,
		'fields'         => 'ids',
		'no_found_rows'  => true,
	)
);
$has_leaders = $any->have_posts();
wp_reset_postdata();
?>
<main id="main-content" tabindex="-1">

	<?php
	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'image'   => 'programme-10',
			'eyebrow' => __( 'People and accountability', 'cohf-child' ),
			'title'   => __( 'Leadership &amp; Governance', 'cohf-child' ),
			'text'    => __( 'The people accountable for the Foundation\'s work, and the structure they are accountable through.', 'cohf-child' ),
		)
	);
	?>

	<?php if ( cohf_page_body_is_blocks() ) : cohf_the_page_body(); else : ?>

	<section class="leadership">
		<div class="container">

			<?php if ( $has_leaders ) : ?>

				<?php
				get_template_part(
					'template-parts/leadership-tier',
					null,
					array(
						'group'     => 'executive',
						'number'    => '01',
						'title'     => __( 'Executive Leadership', 'cohf-child' ),
						'statement' => __( 'Direction and stewardship.', 'cohf-child' ),
						'intro'    => __( 'Overall direction, strategy and day-to-day leadership of the Foundation.', 'cohf-child' ),
						'modifier' => 'feature',
					)
				);

				get_template_part(
					'template-parts/leadership-tier',
					null,
					array(
						'group'     => 'board',
						'number'    => '02',
						'title'     => __( 'Board of Directors', 'cohf-child' ),
						'statement' => __( 'Governance and oversight.', 'cohf-child' ),
						'intro'    => __( 'Independent governance, oversight and accountability, meeting quarterly.', 'cohf-child' ),
						'modifier' => 'board',
					)
				);

				get_template_part(
					'template-parts/leadership-tier',
					null,
					array(
						'group'     => 'management',
						'number'    => '03',
						'title'     => __( 'Management and Operations', 'cohf-child' ),
						'statement' => __( 'The people moving the work forward.', 'cohf-child' ),
						'intro'    => __( 'The team delivering programmes and running the Foundation day to day.', 'cohf-child' ),
						'modifier' => '',
					)
				);
				?>

			<?php else : ?>
				<p class="partner-empty">
					<?php esc_html_e( 'Leadership records are created from the Leadership menu in the WordPress admin. Run the one-time setup to add the current team.', 'cohf-child' ); ?>
				</p>
			<?php endif; ?>

		</div>
	</section>

	<!-- Governance structure -->
	<section class="sage">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="kicker"><?php esc_html_e( 'Governance', 'cohf-child' ); ?></div>
					<h2><?php esc_html_e( 'How the Foundation is governed.', 'cohf-child' ); ?></h2>
				</div>
				<p>
					<?php
					printf(
						/* translators: %s: constitution adoption date. */
						esc_html__( 'Our Constitution, adopted on %s, provides for quarterly Board meetings, annual general meetings and monthly staff meetings.', 'cohf-child' ),
						esc_html( $org['constitution'] )
					);
					?>
				</p>
			</div>

			<ol class="gov-chain">
				<li class="gov-chain__item">
					<span class="gov-chain__num">01</span>
					<h3><?php esc_html_e( 'Members', 'cohf-child' ); ?></h3>
					<p><?php esc_html_e( 'Receive reports at the annual general meeting and hold leadership accountable.', 'cohf-child' ); ?></p>
				</li>
				<li class="gov-chain__item">
					<span class="gov-chain__num">02</span>
					<h3><?php esc_html_e( 'Board of Directors', 'cohf-child' ); ?></h3>
					<p><?php esc_html_e( 'Provides oversight and strategic direction, meeting quarterly.', 'cohf-child' ); ?></p>
				</li>
				<li class="gov-chain__item">
					<span class="gov-chain__num">03</span>
					<h3><?php esc_html_e( 'Executive Director', 'cohf-child' ); ?></h3>
					<p><?php esc_html_e( 'Accountable to the Board for operations and programme delivery.', 'cohf-child' ); ?></p>
				</li>
				<li class="gov-chain__item">
					<span class="gov-chain__num">04</span>
					<h3><?php esc_html_e( 'Management &amp; Operations', 'cohf-child' ); ?></h3>
					<p><?php esc_html_e( 'Deliver programmes and meet monthly to review progress.', 'cohf-child' ); ?></p>
				</li>
			</ol>
		</div>
	</section>

	<!-- Accountability trio -->
	<section class="cream">
		<div class="container">
			<div class="purpose">
				<article>
					<h3><?php esc_html_e( 'Safeguarding', 'cohf-child' ); ?></h3>
					<p><?php esc_html_e( 'Protecting children and vulnerable people and promoting safe programme environments.', 'cohf-child' ); ?></p>
				</article>
				<article>
					<h3><?php esc_html_e( 'Financial accountability', 'cohf-child' ); ?></h3>
					<p><?php esc_html_e( 'Approved budgets, appropriate records and responsible reporting.', 'cohf-child' ); ?></p>
				</article>
				<article>
					<h3><?php esc_html_e( 'Responsible communication', 'cohf-child' ); ?></h3>
					<p><?php esc_html_e( 'Protecting beneficiary dignity, confidentiality and responsible use of stories.', 'cohf-child' ); ?></p>
				</article>
			</div>
			<p class="stack-md">
				<a class="btn dark" href="<?php echo esc_url( $acct ); ?>"><?php esc_html_e( 'Accountability &amp; Safeguarding', 'cohf-child' ); ?></a>
			</p>
		</div>
	</section>

	<?php get_template_part( 'template-parts/cta' ); ?>

	<!-- Profile panel -->
	<div class="leader-panel" id="cohf-leader-panel" hidden>
		<div class="leader-panel__scrim" data-leader-close></div>
		<div class="leader-panel__dialog" role="dialog" aria-modal="true" aria-labelledby="cohf-leader-panel-name">
			<button type="button" class="leader-panel__close" data-leader-close>
				<span aria-hidden="true">&times;</span>
				<span class="screen-reader-text"><?php esc_html_e( 'Close profile', 'cohf-child' ); ?></span>
			</button>
			<div class="leader-panel__grid">
				<div class="leader-panel__media">
					<img class="leader-panel__img" src="" alt="" hidden>
					<span class="leader-panel__monogram" aria-hidden="true"></span>
				</div>
				<div class="leader-panel__body">
					<h2 class="leader-panel__name" id="cohf-leader-panel-name"></h2>
					<p class="leader-panel__role"></p>
					<div class="leader-panel__bio"></div>
				</div>
			</div>
		</div>
	</div>

	<?php endif; ?>
</main>
<?php get_footer();
