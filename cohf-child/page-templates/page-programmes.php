<?php
/**
 * Template Name: Programmes
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();

$audiences = get_terms( array( 'taxonomy' => 'cohf_audience', 'hide_empty' => true ) );
?>
<main id="main-content" tabindex="-1">

	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'image'   => 'programme-08',
		'eyebrow' => __( 'Programme portfolio', 'cohf-child' ),
		'title'   => __( 'Our Programmes', 'cohf-child' ),
		'text'    => __( 'Integrated work anchored in poverty eradication.', 'cohf-child' ),
	) );
	?>

	<section>
		<div class="container">
			<?php if ( ! empty( $audiences ) && ! is_wp_error( $audiences ) ) : ?>
				<div class="filter-bar" data-filter-group data-filter-target="#programme-list" data-filter-status="#programme-filter-status" role="group" aria-label="<?php esc_attr_e( 'Filter programmes by audience', 'cohf-child' ); ?>">
					<button type="button" data-filter="all" aria-pressed="true"><?php esc_html_e( 'All programmes', 'cohf-child' ); ?></button>
					<?php foreach ( $audiences as $audience ) : ?>
						<button type="button" data-filter="<?php echo esc_attr( $audience->slug ); ?>" aria-pressed="false"><?php echo esc_html( $audience->name ); ?></button>
					<?php endforeach; ?>
				</div>
				<p id="programme-filter-status" class="screen-reader-text" role="status"></p>
			<?php endif; ?>

			<div class="grid" id="programme-list">
				<?php
				$programmes = new WP_Query( array(
					'post_type'      => 'cohf_programme',
					'posts_per_page' => 24,
					'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
					'no_found_rows'  => true,
				) );
				if ( $programmes->have_posts() ) {
					while ( $programmes->have_posts() ) {
						$programmes->the_post();
						get_template_part( 'template-parts/programme-card' );
					}
					wp_reset_postdata();
				} else {
					printf(
						'<p class="partner-empty">%s</p>',
						esc_html__( 'Programme pages are created from the Programmes menu in the WordPress admin. Run the one-time setup to add all twelve programme areas automatically.', 'cohf-child' )
					);
				}
				?>
			</div>
		</div>
	</section>

	<!-- How programmes connect -->
	<section class="cream">
		<div class="container feature">
			<?php cohf_the_image( 'programme-01', array( 'sizes' => '(max-width: 60em) 100vw, 50vw' ) ); ?>
			<div>
				<div class="kicker"><?php esc_html_e( 'How our programmes connect', 'cohf-child' ); ?></div>
				<h2><?php esc_html_e( 'One poverty-eradication mission.', 'cohf-child' ); ?></h2>
				<p><?php esc_html_e( 'A child who receives school support needs more than fees alone; a young person needs more than a training certificate; a woman starting a business needs more than start-up capital.', 'cohf-child' ); ?></p>
				<p><?php esc_html_e( 'We work with qualified professionals and appropriate institutions wherever services require clinical expertise, diagnosis, treatment or other regulated practice.', 'cohf-child' ); ?></p>
			</div>
		</div>
	</section>

	<?php
	get_template_part( 'template-parts/cta', null, array(
		'title' => __( 'Sponsor a programme area.', 'cohf-child' ),
		'text'  => __( 'Fund a defined programme for a defined period, with agreed indicators and reporting. We welcome programme grants, multi-year partnerships, technical assistance and co-funding.', 'cohf-child' ),
	) );
	?>

</main>
<?php get_footer();
