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

/**
 * Legal pages beyond the privacy policy: slug => [ title, template ].
 *
 * @return array
 */
function cohf_legal_pages() {
	return array(
		'terms-of-use'    => array( __( 'Terms of Use', 'cohf-child' ), 'page-templates/page-terms.php' ),
		'donation-policy' => array( __( 'Donation and Refund Policy', 'cohf-child' ), 'page-templates/page-donation-policy.php' ),
	);
}

/**
 * URL of a legal page by slug, or '' when it does not exist yet.
 *
 * @param string $slug Page slug.
 * @return string
 */
function cohf_legal_url( $slug ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );
	return ( $page && 'publish' === $page->post_status ) ? get_permalink( $page ) : '';
}

/**
 * Create the legal pages once.
 */
function cohf_legal_ensure_pages() {
	if ( is_admin() === false || current_user_can( 'manage_options' ) === false || get_option( 'cohf_legal_pages_done' ) ) {
		return;
	}
	foreach ( cohf_legal_pages() as $slug => $info ) {
		$page    = get_page_by_path( $slug, OBJECT, 'page' );
		$page_id = $page ? (int) $page->ID : (int) wp_insert_post( array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_title'  => $info[0],
			'post_name'   => $slug,
		) );
		if ( $page_id ) {
			update_post_meta( $page_id, '_wp_page_template', $info[1] );
		}
	}
	update_option( 'cohf_legal_pages_done', COHF_CHILD_VERSION );
}
add_action( 'admin_init', 'cohf_legal_ensure_pages' );
