<?php
/**
 * Privacy Policy page: create it once and register it with WordPress.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * URL of the Privacy Policy page, falling back to the data-protection
 * section of the accountability page.
 *
 * @return string
 */
function cohf_privacy_url() {
	$page = get_page_by_path( 'privacy-policy', OBJECT, 'page' );
	if ( $page && 'publish' === $page->post_status ) {
		return get_permalink( $page );
	}
	return cohf_page_url( 'page-templates/page-accountability.php' ) . '#data-protection';
}

/**
 * Create the page and set it as the site's privacy page. Runs once.
 */
function cohf_privacy_ensure_page() {
	if ( is_admin() === false || current_user_can( 'manage_options' ) === false ) {
		return;
	}
	if ( get_option( 'cohf_privacy_page_done' ) ) {
		return;
	}
	$page = get_page_by_path( 'privacy-policy', OBJECT, 'page' );
	if ( $page ) {
		$page_id = (int) $page->ID;
		if ( 'publish' !== $page->post_status ) {
			wp_update_post( array( 'ID' => $page_id, 'post_status' => 'publish' ) );
		}
	} else {
		$page_id = (int) wp_insert_post( array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_title'  => __( 'Privacy Policy', 'cohf-child' ),
			'post_name'   => 'privacy-policy',
		) );
	}
	if ( $page_id ) {
		update_post_meta( $page_id, '_wp_page_template', 'page-templates/page-privacy.php' );
		update_option( 'wp_page_for_privacy_policy', $page_id );
		update_option( 'cohf_privacy_page_done', COHF_CHILD_VERSION );
	}
}
add_action( 'admin_init', 'cohf_privacy_ensure_page' );
