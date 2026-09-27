<?php
/**
 * Parent footer.
 *
 * The child theme ships its own footer.php, which overrides this file.
 *
 * @package COHF
 */
defined( 'ABSPATH' ) || exit;
?>
<footer>
	<div class="container copyright">
		<?php
		printf(
			/* translators: 1: year, 2: site name. */
			esc_html__( '%1$s %2$s', 'cohf' ),
			esc_html( html_entity_decode( '&copy;', ENT_QUOTES, 'UTF-8' ) . ' ' . gmdate( 'Y' ) ),
			esc_html( get_bloginfo( 'name' ) )
		);
		?>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
