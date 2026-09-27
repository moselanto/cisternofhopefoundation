<?php
/**
 * Impact story card. Prototype markup: article.card > img + .card-body
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;

$excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 24 );
?>
<article class="card">
	<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large' ); } ?>
	<div class="card-body">
		<h3><?php the_title(); ?></h3>
		<p><?php echo esc_html( $excerpt ); ?></p>
		<a class="arrow" href="<?php the_permalink(); ?>">
			<?php cohf_link_context( __( 'Read', 'cohf-child' ), get_the_title() ); ?>
		</a>
	</div>
</article>
