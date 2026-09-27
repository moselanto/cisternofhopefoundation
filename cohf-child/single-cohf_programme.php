<?php
/**
 * Single cohf_programme.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();

while ( have_posts() ) :
	the_post();
	$number    = cohf_field( 'number' );
	$purpose   = cohf_field( 'purpose' );
	$serves    = cohf_field( 'who_serves' );
	$cta_label = cohf_field( 'cta_label' );
	$cta_url   = cohf_field( 'cta_url' );
	$image     = cohf_field( 'image_key' );
	?>
	<main id="main-content" tabindex="-1">

		<section class="page-hero">
			<div class="container">
				<div class="eyebrow">
					<?php
					echo $number
						? esc_html( sprintf( /* translators: %s: programme number. */ __( 'Programme %s', 'cohf-child' ), $number ) )
						: esc_html__( 'Programme', 'cohf-child' );
					?>
				</div>
				<h1><?php the_title(); ?></h1>
				<?php if ( $purpose ) : ?>
					<p><?php echo esc_html( $purpose ); ?></p>
				<?php endif; ?>
			</div>
		</section>

		<section>
			<div class="container feature">
				<div>
					<div class="kicker"><?php esc_html_e( 'About this programme', 'cohf-child' ); ?></div>
					<h2><?php esc_html_e( 'What this work involves.', 'cohf-child' ); ?></h2>
					<div class="prose"><?php the_content(); ?></div>
				</div>
				<?php
				if ( $image ) {
					cohf_the_image( $image, array( 'sizes' => '(max-width: 60em) 100vw, 50vw' ) );
				} elseif ( has_post_thumbnail() ) {
					the_post_thumbnail( 'large' );
				}
				?>
			</div>
		</section>

		<?php if ( $serves ) : ?>
			<section class="cream">
				<div class="container">
					<div class="section-head">
						<div>
							<div class="kicker"><?php esc_html_e( 'Who this serves', 'cohf-child' ); ?></div>
							<h2><?php esc_html_e( 'The people this programme is for.', 'cohf-child' ); ?></h2>
						</div>
					</div>
					<ul class="list-check list-check--2col">
						<?php
						foreach ( cohf_field_lines( 'who_serves' ) as $line ) {
							printf( '<li>%s</li>', esc_html( $line ) );
						}
						?>
					</ul>
				</div>
			</section>
		<?php endif; ?>

		<?php
		get_template_part( 'template-parts/cta', null, array(
			'title'         => __( 'Support this programme.', 'cohf-child' ),
			'text'          => __( 'We welcome programme grants, technical assistance, equipment, market linkages and co-funding.', 'cohf-child' ),
			'primary_label' => $cta_label ? $cta_label : __( 'Partner With Us', 'cohf-child' ),
			'primary_url'   => $cta_url ? $cta_url : cohf_page_url( 'page-templates/page-partners.php' ),
		) );
		?>
	</main>
	<?php
endwhile;
get_footer();
