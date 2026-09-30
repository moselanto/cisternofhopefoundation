<?php
/**
 * Template Name: Gallery
 *
 * An interactive photo gallery: category filters, a masonry grid and a
 * full-screen lightbox with keyboard, swipe and caption support. Works
 * without JavaScript as a plain grid of linked photographs.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();

$items      = function_exists( 'cohf_gallery_items' ) ? cohf_gallery_items() : array();
$categories = function_exists( 'cohf_gallery_categories' ) ? cohf_gallery_categories() : array();
$counts     = array_count_values( wp_list_pluck( $items, 'cat' ) );
?>
<main id="main-content" tabindex="-1">

	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'image'   => 'story-02-fellowship-tshirts',
		'eyebrow' => __( 'Photo gallery', 'cohf-child' ),
		// Editable: set an Excerpt on the Gallery page to change this line.
		'title'   => __( 'Hope, captured in the moment.', 'cohf-child' ),
		'text'    => has_excerpt() ? get_the_excerpt() : __( 'Real people, real places, real change. A look at the work of Cistern of Hope Foundation across our communities in Kenya.', 'cohf-child' ),
	) );
	?>

	<section class="gallery-section">
		<div class="container">
			<?php if ( '' !== trim( (string) get_post_field( 'post_content', get_the_ID() ) ) ) : ?>
				<div class="prose gallery-intro"><?php echo apply_filters( 'the_content', get_post_field( 'post_content', get_the_ID() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content filter. ?></div>
			<?php endif; ?>

			<div class="gallery-toolbar-wrap">
			<div class="gallery-toolbar" role="toolbar" aria-label="<?php esc_attr_e( 'Filter photographs', 'cohf-child' ); ?>">
				<button type="button" class="gallery-filter is-active" data-filter="all" aria-pressed="true">
					<?php esc_html_e( 'All', 'cohf-child' ); ?> <span class="gallery-filter__count"><?php echo (int) count( $items ); ?></span>
				</button>
				<?php foreach ( $categories as $slug => $label ) : ?>
					<?php if ( empty( $counts[ $slug ] ) ) { continue; } ?>
					<button type="button" class="gallery-filter" data-filter="<?php echo esc_attr( $slug ); ?>" aria-pressed="false">
						<?php echo esc_html( $label ); ?> <span class="gallery-filter__count"><?php echo (int) $counts[ $slug ]; ?></span>
					</button>
				<?php endforeach; ?>
			</div>
			</div>

			<p class="gallery-status screen-reader-text" aria-live="polite"></p>

			<div class="gallery-grid">
				<?php foreach ( $items as $i => $item ) : ?>
					<?php
					$url   = $item['url'];
					$alt   = $item['alt'];
					$label = isset( $categories[ $item['cat'] ] ) ? $categories[ $item['cat'] ] : '';
					?>
					<?php
					$shape   = isset( $item['shape'] ) ? $item['shape'] : 'square';
					$classes = 'gallery-item gallery-item--' . $shape . ( 0 === $i ? ' gallery-item--featured' : '' );
					?>
					<figure class="<?php echo esc_attr( $classes ); ?>" data-cat="<?php echo esc_attr( $item['cat'] ); ?>">
						<a class="gallery-item__link"
							href="<?php echo esc_url( $url ); ?>"
							data-index="<?php echo (int) $i; ?>"
							data-title="<?php echo esc_attr( $item['title'] ); ?>"
							data-caption="<?php echo esc_attr( $item['caption'] ); ?>"
							data-category="<?php echo esc_attr( $label ); ?>"
							aria-label="<?php echo esc_attr( sprintf( /* translators: %s: photo title. */ __( 'Open photo: %s', 'cohf-child' ), $item['title'] ) ); ?>">
							<span class="gallery-item__media">
								<img src="<?php echo esc_url( $url ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="<?php echo $i < 4 ? 'eager' : 'lazy'; ?>" decoding="async">
								<span class="gallery-item__zoom" aria-hidden="true">
									<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
								</span>
							</span>
							<span class="gallery-item__meta" aria-hidden="true">
								<span class="gallery-item__cat"><?php echo esc_html( $label ); ?></span>
								<span class="gallery-item__title"><?php echo esc_html( $item['title'] ); ?></span>
							</span>
						</a>
					</figure>
				<?php endforeach; ?>
			</div>

			<?php if ( empty( $items ) ) : ?>
				<p class="gallery-empty"><?php esc_html_e( 'Photographs are on their way.', 'cohf-child' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<div class="lightbox" id="cohf-lightbox" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Photo viewer', 'cohf-child' ); ?>" hidden>
		<div class="lightbox__backdrop" data-close></div>
		<div class="lightbox__top">
			<span class="lightbox__counter" aria-live="polite"></span>
			<button type="button" class="lightbox__btn lightbox__close" data-close aria-label="<?php esc_attr_e( 'Close', 'cohf-child' ); ?>">
				<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
			</button>
		</div>
		<button type="button" class="lightbox__btn lightbox__nav lightbox__prev" aria-label="<?php esc_attr_e( 'Previous photo', 'cohf-child' ); ?>">
			<svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
		</button>
		<figure class="lightbox__stage">
			<img class="lightbox__img" src="" alt="">
			<figcaption class="lightbox__caption">
				<span class="lightbox__cat"></span>
				<strong class="lightbox__title"></strong>
				<span class="lightbox__text"></span>
			</figcaption>
		</figure>
		<button type="button" class="lightbox__btn lightbox__nav lightbox__next" aria-label="<?php esc_attr_e( 'Next photo', 'cohf-child' ); ?>">
			<svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
		</button>
	</div>

	<?php
	get_template_part( 'template-parts/cta', null, array(
		'title' => __( 'Be part of the next picture.', 'cohf-child' ),
		'text'  => __( 'Every photograph here was made possible by people who chose to care, serve and act together. Partner with us or give today.', 'cohf-child' ),
	) );
	?>
</main>
<?php
get_footer();
