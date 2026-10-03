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

	// When this programme carries a before-and-after story, the feature
	// photo must not be one of those images: an "after" shown above the
	// pair put the end of the story before its beginning. The feature slot
	// then uses the photo of where the journey began, so the page reads in
	// order: the start, then Before, then After.
	$cohf_ba_keys = array();
	if ( function_exists( 'cohf_programme_seeded_stories' ) ) {
		foreach ( cohf_programme_seeded_stories( get_the_title() ) as $cohf_s ) {
			if ( empty( $cohf_s['gallery'] ) ) {
				continue;
			}
			foreach ( (array) $cohf_s['gallery'] as $cohf_item ) {
				$cohf_ba_keys[] = $cohf_item['key'];
			}
			$cohf_ba_keys[] = 'story-04-back-to-school';
			$cohf_ba_keys[] = 'gallery-three-boys-at-school';
			$cohf_ba_keys[] = 'hero-home';
		}
	}
	if ( $cohf_ba_keys ) {
		$cohf_current_key = (string) $image;
		if ( '' === $cohf_current_key && has_post_thumbnail() ) {
			$cohf_current_key = (string) get_post_meta( (int) get_post_thumbnail_id(), '_cohf_image_key', true );
		}
		if ( in_array( $cohf_current_key, $cohf_ba_keys, true ) ) {
			$image = 'gallery-before-school-meeting';
		}
	}
	?>
	<main id="main-content" tabindex="-1">

		<section class="page-hero">
			<div class="container">
				<a class="back-link" href="<?php echo esc_url( home_url( '/programmes-overview/' ) ); ?>">&larr; <?php esc_html_e( 'All programmes', 'cohf-child' ); ?></a>
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
					<div class="prose">
						<?php
						if ( '' !== trim( wp_strip_all_tags( get_the_content() ) ) ) {
							the_content();
						} elseif ( has_excerpt() ) {
							echo '<p>' . esc_html( get_the_excerpt() ) . '</p>';
						} elseif ( $purpose ) {
							echo '<p>' . esc_html( $purpose ) . '</p>';
						}
						?>
					</div>
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

		<?php
		// 13.85.0: extra photographs for a programme, keyed by slug.
		$cohf_programme_photos = array(
			'widows-care-food-support' => array(
				array( 'key' => 'gallery-widows-food-support', 'caption' => __( 'Monthly food support delivered to a widow at her home.', 'cohf-child' ) ),
				array( 'key' => 'gallery-widows-home-visit', 'caption' => __( 'A home visit to one of the widows we support.', 'cohf-child' ) ),
			),
		);
		$cohf_slug = get_post_field( 'post_name', get_the_ID() );
		if ( ! empty( $cohf_programme_photos[ $cohf_slug ] ) ) :
			?>
			<section class="cream programme-photos">
				<div class="container">
					<div class="section-head">
						<div>
							<div class="kicker"><?php esc_html_e( 'In pictures', 'cohf-child' ); ?></div>
							<h2><?php esc_html_e( 'This programme in action.', 'cohf-child' ); ?></h2>
						</div>
					</div>
					<div class="story-impact__grid">
						<?php foreach ( $cohf_programme_photos[ $cohf_slug ] as $cohf_photo ) : ?>
							<figure class="story-impact__item">
								<?php cohf_the_image( $cohf_photo['key'], array( 'sizes' => '(max-width: 60em) 100vw, 450px' ) ); ?>
								<figcaption><?php echo esc_html( $cohf_photo['caption'] ); ?></figcaption>
							</figure>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

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
		// Stories for this programme: those linked through the story's
		// "Related programme" field, plus seeded stories assigned to it.
		$seeded     = function_exists( 'cohf_programme_seeded_stories' ) ? cohf_programme_seeded_stories( get_the_title() ) : array();
		$linked_ids = get_posts( array(
			'post_type'      => 'cohf_story',
			'post_status'    => 'publish',
			'posts_per_page' => 6,
			'fields'         => 'ids',
			'meta_key'       => '_cohf_programme_id',
			'meta_value'     => (string) get_the_ID(),
		) );
		$story_ids  = array_values( array_unique( array_merge( wp_list_pluck( $seeded, 'id' ), array_map( 'intval', $linked_ids ) ) ) );

		// Before-and-after photographs from this programme's stories.
		foreach ( $seeded as $seeded_story ) :
			if ( empty( $seeded_story['gallery'] ) ) {
				continue;
			}
			?>
			<section class="story-impact cream">
				<div class="container">
					<div class="section-head">
						<div>
							<div class="kicker"><?php esc_html_e( 'The real impact', 'cohf-child' ); ?></div>
							<h2><?php echo esc_html( get_the_title( $seeded_story['id'] ) ); ?></h2>
						</div>
					</div>
					<div class="story-impact__grid">
						<?php foreach ( $seeded_story['gallery'] as $item ) : ?>
							<figure class="story-impact__item">
								<span class="story-impact__label"><?php echo esc_html( $item['label'] ); ?></span>
								<?php cohf_the_image( $item['key'], array( 'class' => 'story-impact__img', 'sizes' => '(max-width: 700px) 100vw, 50vw' ) ); ?>
								<?php if ( empty( $item['caption'] ) === false ) : ?>
									<figcaption><?php echo esc_html( $item['caption'] ); ?></figcaption>
								<?php endif; ?>
							</figure>
						<?php endforeach; ?>
					</div>
					<p class="story-impact__more"><a class="btn dark" href="<?php echo esc_url( get_permalink( $seeded_story['id'] ) ); ?>"><?php esc_html_e( 'Read their story', 'cohf-child' ); ?></a></p>
				</div>
			</section>
			<?php
		endforeach;

		$related = new WP_Query( array(
			'post_type'      => 'cohf_story',
			'post_status'    => 'publish',
			'posts_per_page' => 3,
			'post__in'       => $story_ids ? $story_ids : array( 0 ),
			'orderby'        => 'date',
			'no_found_rows'  => true,
		) );
		if ( $story_ids && $related->have_posts() ) :
			?>
			<section>
				<div class="container">
					<div class="section-head">
						<div>
							<div class="kicker"><?php esc_html_e( 'Stories from this programme', 'cohf-child' ); ?></div>
							<h2><?php esc_html_e( 'This work in action.', 'cohf-child' ); ?></h2>
						</div>
					</div>
					<div class="grid story-grid">
						<?php
						while ( $related->have_posts() ) {
							$related->the_post();
							get_template_part( 'template-parts/story-card' );
						}
						?>
					</div>
				</div>
			</section>
			<?php
			wp_reset_postdata();
		endif;

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
