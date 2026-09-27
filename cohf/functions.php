<?php
/**
 * COHF Base Theme - bootstrap.
 *
 * @package COHF
 */

defined( 'ABSPATH' ) || exit;

define( 'COHF_VERSION', '9.7.0' );

/**
 * Theme supports and registered menus.
 */
function cohf_setup() {
	load_theme_textdomain( 'cohf', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'custom-logo', array(
		'height'      => 120,
		'width'       => 420,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
		'navigation-widgets',
	) );

	register_nav_menus( array(
		'primary'     => __( 'Primary Navigation', 'cohf' ),
		'footer_site' => __( 'Footer: Quick Links', 'cohf' ),
		'footer_get'  => __( 'Footer: Get Involved', 'cohf' ),
		'footer_legal'=> __( 'Footer: Policies', 'cohf' ),
	) );

	// Image sizes used across the design system.
	add_image_size( 'cohf-hero', 2000, 1125, true );
	add_image_size( 'cohf-card', 900, 675, true );
	add_image_size( 'cohf-portrait', 720, 900, true );
	add_image_size( 'cohf-wide', 1600, 700, true );
}
add_action( 'after_setup_theme', 'cohf_setup' );

/**
 * Content width for embeds.
 */
function cohf_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'cohf_content_width', 760 );
}
add_action( 'after_setup_theme', 'cohf_content_width', 0 );

/**
 * Base stylesheet. The child theme enqueues its own assets on top of this.
 */
function cohf_base_assets() {
	wp_enqueue_style( 'cohf-base', get_template_directory_uri() . '/style.css', array(), COHF_VERSION );
}
add_action( 'wp_enqueue_scripts', 'cohf_base_assets', 5 );

/**
 * Widget areas (footer only - the design does not use sidebars).
 */
function cohf_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Footer Utility', 'cohf' ),
		'id'            => 'cohf-footer-utility',
		'description'   => __( 'Optional small widget area in the footer.', 'cohf' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'cohf_widgets_init' );
