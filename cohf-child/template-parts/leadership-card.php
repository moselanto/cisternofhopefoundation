<?php
/**
 * Leadership card.
 *
 * A photograph when one exists, an elegant monogram plate when it does not.
 * Never a stock portrait. The full biography opens in an accessible panel
 * rather than expanding the card and shifting the grid.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;

$name  = get_the_title();
$role  = cohf_field( 'role' );
$bio   = cohf_field( 'short_bio' );
$photo = '';

if ( has_post_thumbnail() ) {
	$src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium_large' );
	if ( empty( $src ) === false ) {
		$photo = $src[0];
	}
}
?>
<article class="leader-card<?php echo empty( $photo ) ? ' leader-card--nophoto' : ''; ?>">
	<div class="leader-card__media">
		<?php if ( empty( $photo ) === false ) : ?>
			<?php
			the_post_thumbnail(
				'medium_large',
				array(
					'class'   => 'leader-card__img',
					'alt'     => esc_attr( $name ),
					'loading' => 'lazy',
					'decoding'=> 'async',
				)
			);
			?>
		<?php else : ?>
			<span class="leader-card__monogram" aria-hidden="true"><?php echo esc_html( cohf_initials( $name ) ); ?></span>
			<span class="leader-card__pending"><?php esc_html_e( 'Photograph pending', 'cohf-child' ); ?></span>
		<?php endif; ?>
	</div>

	<div class="leader-card__body">
		<h3 class="leader-card__name"><?php echo esc_html( $name ); ?></h3>
		<?php if ( $role ) : ?>
			<p class="leader-card__role"><?php echo esc_html( $role ); ?></p>
		<?php endif; ?>

		<?php if ( empty( $bio ) ) : ?>
			<p class="leader-card__bio leader-card__bio--pending"><?php esc_html_e( 'Full profile to follow.', 'cohf-child' ); ?></p>
		<?php else : ?>
			<p class="leader-card__bio"><?php echo esc_html( $bio ); ?></p>
			<button type="button"
				class="leader-card__more"
				data-leader-name="<?php echo esc_attr( $name ); ?>"
				data-leader-role="<?php echo esc_attr( (string) $role ); ?>"
				data-leader-bio="<?php echo esc_attr( $bio ); ?>"
				data-leader-photo="<?php echo esc_url( $photo ); ?>">
				<span><?php esc_html_e( 'View profile', 'cohf-child' ); ?></span>
				<span class="leader-card__more-sr screen-reader-text"><?php echo esc_html( $name ); ?></span>
			</button>
		<?php endif; ?>
	</div>
</article>
