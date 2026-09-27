<?php
/**
 * Programme card. Prototype markup: article.card > img + .card-body
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;

$a       = wp_parse_args( $args ?? array(), array( 'style' => 'tile' ) );
$terms   = get_the_terms( get_the_ID(), 'cohf_audience' );
$slugs   = ( $terms && ! is_wp_error( $terms ) ) ? implode( ' ', wp_list_pluck( $terms, 'slug' ) ) : '';
$image   = cohf_field( 'image_key' );
$excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 22 );
?>
<article class="card" data-filter-value="<?php echo cohf_attr( $slugs ); ?>">
	<?php
	if ( $image ) {
		cohf_the_image( $image, array( 'sizes' => '(max-width: 60em) 100vw, 33vw' ) );
	} elseif ( has_post_thumbnail() ) {
		the_post_thumbnail( 'large' );
	}
	?>
	<div class="card-body">
		<h3><?php the_title(); ?></h3>
		<p><?php echo esc_html( $excerpt ); ?></p>
		<a class="arrow" href="<?php the_permalink(); ?>">
			<?php cohf_link_context( __( 'Explore', 'cohf-child' ), get_the_title() ); ?>
		</a>
	</div>
</article>
