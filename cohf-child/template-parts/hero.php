<?php
/**
 * Homepage hero slider.
 *
 * Four slides, crossfaded. Built on the prototype's .hero / .container.inner
 * markup so the type scale and gradient stay consistent; the slide layers,
 * dots and arrows are additions styled in wp-adapt.css.
 *
 * Height is capped in CSS so the tallest slide always fits inside the first
 * viewport - no scrolling required to read a headline or reach its buttons.
 *
 * Degrades safely: with JavaScript off the first slide is visible and the
 * controls are hidden, so the hero still reads as a normal static hero.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;

$slides = function_exists( 'cohf_hero_slides' ) ? cohf_hero_slides() : array();

/*
 * Single-slide mode must be requested explicitly with 'single' => true.
 *
 * This previously triggered on the mere presence of a 'title' argument, so
 * any caller passing hero copy silently collapsed the slider to one static
 * slide - which is exactly what happened to the homepage in v5.
 */
if ( ! empty( $args ) && ! empty( $args['single'] ) && ! empty( $args['title'] ) ) {
	$slides = array( wp_parse_args( $args, array(
		'image'           => 'hero-home',
		'eyebrow'         => '',
		'title'           => '',
		'text'            => '',
		'primary_label'   => '',
		'primary_url'     => '',
		'secondary_label' => '',
		'secondary_url'   => '',
	) ) );
}

if ( empty( $slides ) ) {
	return;
}

$first    = reset( $slides );
$first_bg = cohf_img_url( isset( $first['image'] ) ? $first['image'] : 'hero-home' );
$multi    = count( $slides ) > 1;
?>
<section class="hero hero--slider<?php echo $multi ? ' has-slides' : ''; ?>"
	<?php echo $first_bg ? ' style="--hero-img:url(' . esc_url( $first_bg ) . ')"' : ''; ?>
	<?php echo $multi ? ' data-hero-slider' : ''; ?>
	aria-roledescription="<?php esc_attr_e( 'carousel', 'cohf-child' ); ?>"
	aria-label="<?php esc_attr_e( 'Our work', 'cohf-child' ); ?>">

	<?php if ( $multi ) : ?>
		<div class="hero__layers" aria-hidden="true">
			<?php
			foreach ( $slides as $i => $slide ) {
				$bg = cohf_img_url( isset( $slide['image'] ) ? $slide['image'] : '' );
				if ( ! $bg ) {
					continue;
				}
				printf(
					'<div class="hero__layer%1$s" style="background-image:url(%2$s)"></div>',
					0 === $i ? ' is-active' : '',
					esc_url( $bg )
				);
			}
			?>
		</div>
	<?php endif; ?>

	<div class="container inner">
		<?php foreach ( $slides as $i => $slide ) : ?>
			<div class="hero__pane<?php echo 0 === $i ? ' is-active' : ''; ?>"
				data-hero-pane
				role="group"
				aria-roledescription="<?php esc_attr_e( 'slide', 'cohf-child' ); ?>"
				aria-label="<?php echo esc_attr( sprintf( /* translators: 1: slide number, 2: total slides. */ __( '%1$d of %2$d', 'cohf-child' ), $i + 1, count( $slides ) ) ); ?>"
				<?php echo 0 === $i ? '' : ' hidden'; ?>>

				<?php if ( ! empty( $slide['eyebrow'] ) ) : ?>
					<div class="eyebrow"><?php echo esc_html( $slide['eyebrow'] ); ?></div>
				<?php endif; ?>

				<?php /* One H1 per page: the first slide is the page heading, the others are H2s styled the same. */ ?>
				<?php if ( 0 === $i ) : ?>
					<h1 class="hero__h"><?php echo esc_html( $slide['title'] ); ?></h1>
				<?php else : ?>
					<h2 class="hero__h"><?php echo esc_html( $slide['title'] ); ?></h2>
				<?php endif; ?>

				<?php if ( ! empty( $slide['text'] ) ) : ?>
					<p><?php echo esc_html( $slide['text'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $slide['primary_label'] ) || ! empty( $slide['secondary_label'] ) ) : ?>
					<div class="buttons">
						<?php if ( ! empty( $slide['primary_label'] ) ) : ?>
							<a class="btn gold" href="<?php echo esc_url( $slide['primary_url'] ); ?>"><?php echo esc_html( $slide['primary_label'] ); ?></a>
						<?php endif; ?>
						<?php if ( ! empty( $slide['secondary_label'] ) ) : ?>
							<a class="btn light" href="<?php echo esc_url( $slide['secondary_url'] ); ?>"><?php echo esc_html( $slide['secondary_label'] ); ?></a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if ( $multi ) : ?>
		<div class="hero__controls container">
			<button class="hero__arrow" type="button" data-hero-prev aria-label="<?php esc_attr_e( 'Previous slide', 'cohf-child' ); ?>">
				<span aria-hidden="true">&#8249;</span>
			</button>

			<div class="hero__dots" role="tablist" aria-label="<?php esc_attr_e( 'Choose slide', 'cohf-child' ); ?>">
				<?php foreach ( $slides as $i => $slide ) : ?>
					<button class="hero__dot<?php echo 0 === $i ? ' is-active' : ''; ?>"
						type="button"
						role="tab"
						data-hero-dot="<?php echo esc_attr( (string) $i ); ?>"
						aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
						aria-label="<?php echo esc_attr( wp_strip_all_tags( $slide['title'] ) ); ?>">
						<span class="screen-reader-text"><?php echo esc_html( wp_strip_all_tags( $slide['title'] ) ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>

			<button class="hero__arrow" type="button" data-hero-next aria-label="<?php esc_attr_e( 'Next slide', 'cohf-child' ); ?>">
				<span aria-hidden="true">&#8250;</span>
			</button>
		</div>

		<p class="screen-reader-text" role="status" data-hero-status></p>
	<?php endif; ?>
</section>
