<?php
/**
 * Inner page hero. Prototype markup: section.page-hero > .container > .eyebrow + h1 + p
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;

$a       = wp_parse_args( $args ?? array(), array(
	'image'   => '',
	'eyebrow' => '',
	'title'   => '',
	'text'    => '',
) );
$title   = $a['title'] ? $a['title'] : get_the_title();
$style   = '';
if ( $a['image'] ) {
	$url = cohf_img_url( $a['image'] );
	if ( $url ) {
		$style = sprintf(
			' style="background:linear-gradient(90deg,#0e2d23f0,#0e2d23b8),url(%s) center/cover"',
			esc_url( $url )
		);
	}
}
?>
<section class="page-hero"<?php echo $style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from esc_url above. ?>>
	<div class="container">
		<?php
		/*
		 * cohf_breadcrumbs() has existed in inc/seo.php since the theme was
		 * built but was never called, so inner pages gave no route back up.
		 * It returns early on the front page and defers to Rank Math or Yoast
		 * when either is active.
		 */
		if ( function_exists( 'cohf_breadcrumbs' ) ) {
			cohf_breadcrumbs();
		}
		?>
		<?php if ( $a['eyebrow'] ) : ?>
			<div class="eyebrow"><?php echo esc_html( $a['eyebrow'] ); ?></div>
		<?php endif; ?>
		<h1><?php echo esc_html( $title ); ?></h1>
		<?php if ( $a['text'] ) : ?>
			<p><?php echo esc_html( $a['text'] ); ?></p>
		<?php endif; ?>
	</div>
</section>
