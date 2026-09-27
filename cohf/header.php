<?php
/**
 * Parent document shell.
 *
 * The child theme ships its own header.php, which overrides this file
 * entirely. This remains only so the parent theme is valid on its own.
 * Markup uses the prototype's class vocabulary.
 *
 * @package COHF
 */
defined( 'ABSPATH' ) || exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main-content"><?php esc_html_e( 'Skip to main content', 'cohf' ); ?></a>

<header>
	<div class="container nav">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php
			$cohf_lockup = has_custom_logo()
				&& function_exists( 'cohf_custom_logo_has_wordmark' )
				&& cohf_custom_logo_has_wordmark();
			if ( has_custom_logo() ) { if ( function_exists( 'cohf_custom_logo_image' ) ) { cohf_custom_logo_image(); } else { the_custom_logo(); } } else { ?>
				<?php if ( function_exists( 'cohf_logo_mark' ) ) { cohf_logo_mark( 40 ); } else { ?>
					<span class="mark" aria-hidden="true">C</span>
				<?php } ?>
			<?php } ?>
			<?php if ( $cohf_lockup ) : ?>
				<span class="screen-reader-text"><?php bloginfo( 'name' ); ?></span>
			<?php else : ?>
				<span><?php bloginfo( 'name' ); ?></span>
			<?php endif; ?>
		</a>
		<nav class="links" aria-label="<?php esc_attr_e( 'Primary', 'cohf' ); ?>">
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'items_wrap' => '%3$s', 'depth' => 1, 'fallback_cb' => false ) ); ?>
		</nav>
	</div>
</header>
