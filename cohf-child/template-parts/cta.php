<?php
/**
 * Closing call to action. Prototype markup: section.sage > .container > .cta-band
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;

$links = cohf_cta_links();
$a     = wp_parse_args( $args ?? array(), array(
	'title'           => __( 'Let\'s build lasting change together.', 'cohf-child' ),
	'text'            => __( 'Partner with Cistern of Hope Foundation to strengthen pathways for children, young people, women, families and communities.', 'cohf-child' ),
	'primary_label'   => __( 'Partner With Us', 'cohf-child' ),
	'primary_url'     => $links['partner'],
	'secondary_label' => '',
	'secondary_url'   => '',
) );
?>
<section class="sage">
	<div class="container">
		<div class="cta-band">
			<div>
				<h2><?php echo esc_html( $a['title'] ); ?></h2>
				<p><?php echo esc_html( $a['text'] ); ?></p>
			</div>
			<div class="buttons">
				<a class="btn gold" href="<?php echo esc_url( $a['primary_url'] ); ?>"><?php echo esc_html( $a['primary_label'] ); ?></a>
				<?php if ( $a['secondary_label'] ) : ?>
					<a class="btn light" href="<?php echo esc_url( $a['secondary_url'] ); ?>"><?php echo esc_html( $a['secondary_label'] ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
