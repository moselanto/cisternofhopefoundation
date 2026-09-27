<?php
/**
 * WooCommerce wrapper.
 *
 * Every WooCommerce page renders inside the theme's header, main landmark
 * and footer rather than WooCommerce's own shell.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main-content" tabindex="-1" class="shop">
	<?php woocommerce_content(); ?>
</main>
<?php
get_footer();
