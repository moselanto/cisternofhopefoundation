<?php
/**
 * Document head and site header.
 *
 * Markup mirrors the approved prototype exactly: a .top utility bar, a sticky
 * <header> containing .container.nav with .brand / nav.links / .cta.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;

$org      = cohf_org();
$cta      = cohf_cta_links();
$home     = home_url( '/' );
$support  = cohf_page_url( 'page-templates/page-support.php' );
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php get_template_part( 'template-parts/loader' ); ?>

<a class="skip-link screen-reader-text" href="#main-content"><?php esc_html_e( 'Skip to main content', 'cohf-child' ); ?></a>

<div class="top">
	<div class="container">
		<span class="top__strapline"><?php echo esc_html( $org['strapline'] ); ?></span>
		<span class="top__contact">
			<a class="top__link" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $org['phone'] ) ); ?>">
				<svg class="top__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2Z"/></svg>
				<span><?php echo esc_html( $org['phone'] ); ?></span>
			</a>
			<span class="top__sep" aria-hidden="true"></span>
			<a class="top__link" href="mailto:<?php echo esc_attr( $org['email'] ); ?>">
				<svg class="top__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2.5 6.5 9.5 6.5 9.5-6.5"/></svg>
				<span><?php echo esc_html( $org['email'] ); ?></span>
			</a>
		</span>
	</div>
</div>

<header>
	<div class="container nav">
		<a class="brand" href="<?php echo esc_url( $home ); ?>">
			<?php
			$cohf_lockup = has_custom_logo() && cohf_custom_logo_has_wordmark();
			if ( has_custom_logo() ) {
				cohf_custom_logo_image();
			} else {
				cohf_logo_mark( 40 );
			}
			?>
			<?php if ( $cohf_lockup ) : ?>
				<span class="brand-mark-mobile" aria-hidden="true"><?php cohf_logo_mark( 40 ); ?></span>
				<span class="screen-reader-text"><?php echo esc_html( $org['name'] ); ?></span>
			<?php else : ?>
				<span><?php echo esc_html( strtoupper( $org['name'] ) ); ?><small><?php echo esc_html( strtoupper( rtrim( $org['motto'], '.' ) ) ); ?></small></span>
			<?php endif; ?>
		</a>

		<button class="nav-toggle" type="button" data-nav-toggle
			aria-expanded="false"
			aria-controls="cohf-primary-nav"
			aria-label="<?php esc_attr_e( 'Menu', 'cohf-child' ); ?>">
			<span class="nav-toggle__bars" aria-hidden="true"></span>
		</button>

		<?php get_template_part( 'template-parts/site-nav' ); ?>

		<a class="cta" href="<?php echo esc_url( $support ); ?>"><?php esc_html_e( 'Support Our Work', 'cohf-child' ); ?></a>

		<?php if ( function_exists( 'cohf_shop_header_cart' ) ) { cohf_shop_header_cart(); } ?>
	</div>
</header>
