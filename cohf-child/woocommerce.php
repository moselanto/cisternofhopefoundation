<?php
/**
 * WooCommerce wrapper.
 *
 * Every WooCommerce page renders inside the theme's header, main landmark
 * and footer rather than WooCommerce's own shell.
 *
 * The two do_action calls below are not decoration. woocommerce_content()
 * renders the loop but does NOT fire the content wrapper hooks - those live
 * in WooCommerce's archive-product.php, which this file replaces. Without
 * them nothing in inc/shop.php ran: not the Hope Market hero, not the
 * "how this works" note, not even the .shop-main container the storefront
 * is laid out in. The shop rendered as a bare WooCommerce loop on a blank
 * page, which is exactly what it was doing before 9.44.0.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main-content" tabindex="-1" class="shop">
	<?php
	/**
	 * Fires the theme's storefront framing.
	 *
	 * inc/shop.php attaches: the page hero at 5, the .shop-main wrapper at
	 * 10, and the introduction at 15 - so the hero lands full width above
	 * the container and the introduction inside it.
	 */
	do_action( 'woocommerce_before_main_content' );

	woocommerce_content();

	do_action( 'woocommerce_after_main_content' );
	?>
</main>
<?php
get_footer();
