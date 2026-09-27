<?php
/**
 * Single cohf_news - also used by cohf_report, cohf_resource and cohf_event,
 * which require this file. All labels are derived from the post type object,
 * so the shared layout stays correct for each.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();

while ( have_posts() ) :
	the_post();
	$pt_obj    = get_post_type_object( get_post_type() );
	$pt_label  = $pt_obj ? $pt_obj->labels->singular_name : __( 'Update', 'cohf-child' );
	$file      = cohf_field( 'file_url' );
	$size      = cohf_field( 'file_size' );
	$start     = cohf_field( 'start_date' );
	$venue     = cohf_field( 'venue' );
	$reached   = cohf_field( 'reached' );
	$archive   = get_post_type_archive_link( get_post_type() );
	?>
	<main id="main-content" tabindex="-1">

		<section class="page-hero">
			<div class="container">
				<div class="eyebrow"><?php echo esc_html( $pt_label ); ?></div>
				<h1><?php the_title(); ?></h1>
				<p>
					<?php
					$meta = array_filter( array(
						$start ? $start : get_the_date(),
						$venue,
					) );
					echo esc_html( implode( ' - ', $meta ) );
					?>
				</p>
			</div>
		</section>

		<section>
			<div class="container story">
				<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large' ); } ?>
				<div class="story-copy">
					<div class="prose"><?php the_content(); ?></div>

					<?php if ( $reached ) : ?>
						<div class="facts">
							<div class="fact">
								<strong><?php echo esc_html( $reached ); ?></strong>
								<span><?php esc_html_e( 'People reached', 'cohf-child' ); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<div class="buttons">
						<?php if ( $file ) : ?>
							<a class="btn dark" href="<?php echo esc_url( $file ); ?>" download>
								<?php
								echo esc_html(
									$size
										? sprintf( /* translators: %s: file size. */ __( 'Download (%s)', 'cohf-child' ), $size )
										: __( 'Download', 'cohf-child' )
								);
								?>
							</a>
						<?php endif; ?>
						<?php if ( $archive ) : ?>
							<a class="btn outline" href="<?php echo esc_url( $archive ); ?>">
								<?php
								printf(
									/* translators: %s: post type plural name. */
									esc_html__( 'All %s', 'cohf-child' ),
									esc_html( $pt_obj ? strtolower( $pt_obj->labels->name ) : __( 'updates', 'cohf-child' ) )
								);
								?>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>

		<?php get_template_part( 'template-parts/cta' ); ?>
	</main>
	<?php
endwhile;
get_footer();
