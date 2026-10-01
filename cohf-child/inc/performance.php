<?php
/**
 * Performance: asset strategy, critical CSS, font loading, image handling.
 *
 * Goals: minimal JavaScript, no render-blocking third-party requests,
 * responsive and lazily loaded imagery, and full compatibility with
 * caching plugins and CDNs (no cookie- or session-dependent output).
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue the design system. Stylesheets are split so that only what a page
 * needs is loaded, and the JS bundle is deferred.
 */
function cohf_enqueue_assets() {
	$dir = COHF_CHILD_DIR . '/assets/';
	$uri = COHF_CHILD_URI . '/assets/';

	/*
	 * prototype.css is the approved prototype stylesheet, ported verbatim.
	 * It is the single authoritative design layer - do not reintroduce the
	 * earlier token/base/component cascade, which competed with it.
	 * wp-adapt.css adds only what WordPress itself needs (core block
	 * alignment classes, screen-reader utilities, admin-bar offset).
	 */
	$styles = array(
		'cohf-prototype' => 'css/prototype.css',
		'cohf-wp-adapt'  => 'css/wp-adapt.css',
		'cohf-system'    => 'css/design-system.css',
		// Interaction, accessibility and readability corrections. Loads last
		// so it can override the design layer without editing it.
		'cohf-ux'        => 'css/ux-refinements.css',
	);

	$deps = array();
	foreach ( $styles as $handle => $rel ) {
		$path = $dir . $rel;
		wp_enqueue_style(
			$handle,
			$uri . $rel,
			$deps,
			file_exists( $path ) ? (string) filemtime( $path ) : COHF_CHILD_VERSION
		);
		$deps = array( $handle );
	}

	// The child style.css itself carries only project overrides.
	wp_enqueue_style(
		'cohf-child-style',
		get_stylesheet_uri(),
		$deps,
		COHF_CHILD_VERSION
	);

	$nav_js = $dir . 'js/navigation.js';
	wp_enqueue_script(
		'cohf-navigation',
		$uri . 'js/navigation.js',
		array(),
		file_exists( $nav_js ) ? (string) filemtime( $nav_js ) : COHF_CHILD_VERSION,
		array( 'in_footer' => true, 'strategy' => 'defer' )
	);

	$js = $dir . 'js/main.js';
	wp_enqueue_script(
		'cohf-main',
		$uri . 'js/main.js',
		array(),
		file_exists( $js ) ? (string) filemtime( $js ) : COHF_CHILD_VERSION,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	$forms_js = $dir . 'js/forms.js';
	wp_enqueue_script(
		'cohf-forms',
		$uri . 'js/forms.js',
		array(),
		file_exists( $forms_js ) ? (string) filemtime( $forms_js ) : COHF_CHILD_VERSION,
		array( 'strategy' => 'defer', 'in_footer' => true )
	);

	// The leadership profile panel is only needed on that one page.
	if ( is_page_template( 'page-templates/page-leadership.php' ) ) {
		$lead = $dir . 'js/leadership.js';
		if ( file_exists( $lead ) ) {
			wp_enqueue_script(
				'cohf-leadership',
				$uri . 'js/leadership.js',
				array(),
				(string) filemtime( $lead ),
				array(
					'strategy'  => 'defer',
					'in_footer' => true,
				)
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'cohf_enqueue_assets', 20 );

/**
 * Self-hosted variable fonts.
 *
 * Drop the font files into /assets/fonts/ and they are used automatically.
 * If they are absent the theme falls back to a high-quality system stack,
 * so no external request is ever made to Google Fonts. This keeps the site
 * fast, GDPR/Kenya DPA-friendly and independent of third-party uptime.
 */
function cohf_font_face_css() {
	$fonts = array(
		array(
			'family' => 'Playfair Display',
			'file'   => 'playfair-display-variable.woff2',
			'weight' => '400 900',
			'style'  => 'normal',
		),
		array(
			'family' => 'Playfair Display',
			'file'   => 'playfair-display-italic-variable.woff2',
			'weight' => '400 700',
			'style'  => 'italic',
		),
		array(
			'family' => 'Inter',
			'file'   => 'inter-variable.woff2',
			'weight' => '100 900',
			'style'  => 'normal',
		),
		array(
			'family' => 'Inter',
			'file'   => 'inter-italic-variable.woff2',
			'weight' => '100 900',
			'style'  => 'italic',
		),
	);

	$css = '';
	foreach ( $fonts as $font ) {
		$path = COHF_CHILD_DIR . '/assets/fonts/' . $font['file'];
		if ( ! file_exists( $path ) ) {
			continue;
		}
		$css .= sprintf(
			'@font-face{font-family:"%1$s";src:url("%2$s") format("woff2");font-weight:%3$s;font-style:%4$s;font-display:swap;}',
			esc_attr( $font['family'] ),
			esc_url( COHF_CHILD_URI . '/assets/fonts/' . $font['file'] ),
			esc_attr( $font['weight'] ),
			esc_attr( $font['style'] )
		);
	}

	if ( $css ) {
		wp_add_inline_style( 'cohf-tokens', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'cohf_font_face_css', 21 );

/**
 * Preload the variable fonts actually present, and preconnect nothing.
 */
function cohf_resource_hints() {
	foreach ( array( 'playfair-display-variable.woff2', 'inter-variable.woff2' ) as $file ) {
		if ( ! file_exists( COHF_CHILD_DIR . '/assets/fonts/' . $file ) ) {
			continue;
		}
		printf(
			'<link rel="preload" as="font" type="font/woff2" href="%s" crossorigin>' . "\n",
			esc_url( COHF_CHILD_URI . '/assets/fonts/' . $file )
		);
	}
}
add_action( 'wp_head', 'cohf_resource_hints', 1 );

/**
 * Preload the hero photograph on the front page.
 *
 * The hero is a CSS background applied through an inline --hero-img custom
 * property, which the browser's preload scanner cannot see: it has to fetch
 * and parse the stylesheet, build the element, resolve the property and only
 * then start downloading the largest image on the page. Naming the file in
 * the head lets that download begin immediately.
 *
 * Restricted to the front page because it is the only template whose hero
 * image key is known here without guessing.
 */
function cohf_preload_hero() {
	if ( is_front_page() === false ) {
		return;
	}

	if ( function_exists( 'cohf_img_url' ) === false ) {
		return;
	}

	$hero = cohf_img_url( 'hero-home' );

	if ( $hero ) {
		printf(
			'<link rel="preload" as="image" href="%s" fetchpriority="high">' . "\n",
			esc_url( $hero )
		);
	}
}
add_action( 'wp_head', 'cohf_preload_hero', 1 );

/**
 * A tiny critical-CSS shim so the first paint is never unstyled.
 * Full stylesheets still load normally; this only covers above-the-fold shell.
 */
function cohf_critical_css() {
	$critical = 'html{background:#fcfaf6}body{margin:0;font-family:system-ui,sans-serif;color:#24231f}'
		. '.site-header{min-height:5rem}.hero{min-height:70vh;background:#10261d}'
		. '.no-js [data-reveal]{opacity:1!important;transform:none!important}';
	printf( '<style id="cohf-critical">%s</style>' . "\n", $critical ); // Static, developer-authored CSS.
}
add_action( 'wp_head', 'cohf_critical_css', 2 );

/**
 * Mark the document as JS-less until main.js removes the class.
 * Prevents reveal animations from hiding content for non-JS users.
 */
function cohf_no_js_class( $classes ) {
	$classes[] = 'no-js';
	return $classes;
}
add_filter( 'body_class', 'cohf_no_js_class' );

/**
 * Hero and other LCP images must not be lazy-loaded.
 *
 * @param string|bool $value   Current loading attribute.
 * @param string      $image   Image markup.
 * @param string      $context Context.
 * @return string|bool
 */
function cohf_skip_lazy_for_lcp( $value, $image, $context ) {
	if ( 'the_post_thumbnail' === $context && is_front_page() ) {
		return false;
	}
	return $value;
}
add_filter( 'wp_img_tag_add_loading_attr', 'cohf_skip_lazy_for_lcp', 10, 3 );

/**
 * Allow modern image formats to be uploaded and served.
 *
 * @param array $mimes Allowed mime types.
 * @return array
 */
function cohf_allow_modern_images( $mimes ) {
	$mimes['webp'] = 'image/webp';
	$mimes['avif'] = 'image/avif';
	return $mimes;
}
add_filter( 'upload_mimes', 'cohf_allow_modern_images' );

/**
 * Sensible default sizes attribute for full-width editorial images.
 *
 * @param string $sizes Sizes attribute.
 * @return string
 */
function cohf_default_sizes( $sizes ) {
	return '(max-width: 47.9375em) 100vw, (max-width: 74.9375em) 50vw, 760px';
}
add_filter( 'wp_calculate_image_sizes', 'cohf_default_sizes', 10, 1 );

/**
 * Trim front-end weight WordPress adds by default and that this theme does
 * not use. Nothing here affects the admin or the block editor.
 */
function cohf_dequeue_unused() {
	if ( is_admin() ) {
		return;
	}
	// Classic block theme styles are unnecessary; the design system covers them.
	wp_dequeue_style( 'classic-theme-styles' );

	// Emoji script/styles: the theme's content guidelines exclude emoji.
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );

	// Legacy feed and shortlink markup.
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
}
add_action( 'init', 'cohf_dequeue_unused' );

/**
 * Remove the inline global-styles SVG filter duotone block WP prints when unused.
 */
add_action( 'wp_enqueue_scripts', function () {
	if ( ! is_admin() ) {
		remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
		remove_action( 'wp_footer', 'wp_enqueue_global_styles_custom_css' );
	}
}, 100 );

/**
 * Keep heartbeat light on the front end (CDN and shared-hosting friendly).
 *
 * @param array $settings Heartbeat settings.
 * @return array
 */
function cohf_heartbeat( $settings ) {
	$settings['interval'] = 120;
	return $settings;
}
add_filter( 'heartbeat_settings', 'cohf_heartbeat' );


/* --------------------------------------------------------------------------
   13.21.0 Speed: load only what each page needs
   -------------------------------------------------------------------------- */

/**
 * Is this a shop page that needs WooCommerce's own scripts and styles?
 *
 * @return bool
 */
function cohf_is_shop_context() {
	if ( function_exists( 'is_woocommerce' ) === false ) {
		return false;
	}
	return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
}

/**
 * Drop WooCommerce assets from the foundation pages (About, Impact, Gallery,
 * stories and so on). Those pages never show a product, so the shop's
 * jQuery-based scripts and three stylesheets were pure dead weight. The
 * header cart and cart drawer use the theme's own lightweight code and keep
 * working everywhere.
 */
function cohf_trim_shop_assets() {
	if ( is_admin() || function_exists( 'is_woocommerce' ) === false ) {
		return;
	}
	// Marketing attribution is only useful at checkout.
	if ( is_checkout() === false ) {
		wp_dequeue_script( 'wc-order-attribution' );
		wp_dequeue_script( 'sourcebuster-js' );
	}
	if ( cohf_is_shop_context() || is_front_page() ) {
		return;
	}
	foreach ( array( 'woocommerce-layout', 'woocommerce-smallscreen', 'woocommerce-general', 'wc-blocks-style', 'wc-blocks-vendors-style' ) as $style ) {
		wp_dequeue_style( $style );
	}
	foreach ( array( 'wc-add-to-cart', 'woocommerce', 'wc-cart-fragments', 'jquery-blockui', 'js-cookie' ) as $script ) {
		wp_dequeue_script( $script );
	}
}
add_action( 'wp_enqueue_scripts', 'cohf_trim_shop_assets', 99 );


/**
 * Lazy-load and decode images off the main thread by default.
 */
add_filter( 'wp_get_attachment_image_attributes', function ( $attr ) {
	if ( empty( $attr['decoding'] ) ) {
		$attr['decoding'] = 'async';
	}
	return $attr;
} );
