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
		'donation-policy' => array( __( 'Donation and Fundraising Policy', 'cohf-child' ), 'page-templates/page-donation-policy.php' ),
		'refund-returns'  => array( __( 'Refund and Returns Policy', 'cohf-child' ), 'page-templates/page-returns.php' ),
		'delivery'        => array( __( 'Delivery and Shipping', 'cohf-child' ), 'page-templates/page-delivery.php' ),
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


/**
 * Copy the default wording into each legal page that has no content yet,
 * so every word can be edited in the WordPress editor. Existing content is
 * never overwritten.
 */
function cohf_legal_fill_content() {
	if ( is_admin() === false || current_user_can( 'manage_options' ) === false || get_option( 'cohf_legal_content_done' ) ) {
		return;
	}
	$map = array(
		'privacy-policy'  => 'privacy',
		'terms-of-use'    => 'terms',
		'donation-policy' => 'donation-policy',
	);
	foreach ( $map as $slug => $part ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( empty( $page ) || '' !== trim( (string) $page->post_content ) ) {
			continue;
		}
		$file = locate_template( 'template-parts/legal-' . $part . '.php' );
		if ( '' === $file ) {
			continue;
		}
		ob_start();
		include $file;
		$html = trim( (string) ob_get_clean() );
		if ( '' !== $html ) {
			wp_update_post( array( 'ID' => $page->ID, 'post_content' => $html ) );
		}
	}
	update_option( 'cohf_legal_content_done', COHF_CHILD_VERSION );
}
add_action( 'admin_init', 'cohf_legal_fill_content', 30 );


/**
 * 13.24.0: create the shop policy pages, fill them with their default
 * wording, and add the new shop and fundraising sections to the Terms and
 * Donation pages that already exist. Runs once; never overwrites edits.
 */
function cohf_legal_update_v2() {
	if ( is_admin() === false || current_user_can( 'manage_options' ) === false || get_option( 'cohf_legal_v2_done' ) ) {
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
	$render = function ( $part ) {
		$file = locate_template( 'template-parts/legal-' . $part . '.php' );
		if ( '' === $file ) {
			return '';
		}
		ob_start();
		include $file;
		return trim( (string) ob_get_clean() );
	};
	foreach ( array( 'refund-returns' => 'returns', 'delivery' => 'delivery' ) as $slug => $part ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( $page && '' === trim( (string) $page->post_content ) ) {
			$html = $render( $part );
			if ( '' !== $html ) {
				wp_update_post( array( 'ID' => $page->ID, 'post_content' => $html ) );
			}
		}
	}
	// Append the new sections to pages whose wording was already copied in.
	$appends = array(
		'terms-of-use'    => array( 'Buying from Hope Market', 'terms', 'Buying from Hope Market', 'Donations' ),
		'donation-policy' => array( 'Fundraising on our behalf', 'donation-policy', 'Fundraising on our behalf', 'Anonymous gifts' ),
	);
	foreach ( $appends as $slug => $a ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( empty( $page ) || '' === trim( (string) $page->post_content ) || false !== strpos( $page->post_content, $a[0] ) ) {
			continue;
		}
		$full  = $render( $a[1] );
		$start = strpos( $full, '<h2>' . esc_html__( $a[2], 'cohf-child' ) . '</h2>' );
		$end   = strpos( $full, '<h2>' . esc_html__( $a[3], 'cohf-child' ) . '</h2>' );
		if ( false === $start || false === $end || $end <= $start ) {
			continue;
		}
		$section = substr( $full, $start, $end - $start );
		wp_update_post( array( 'ID' => $page->ID, 'post_content' => rtrim( $page->post_content ) . "\n" . $section ) );
	}
	$donation = get_page_by_path( 'donation-policy', OBJECT, 'page' );
	if ( $donation && 'Donation and Refund Policy' === $donation->post_title ) {
		wp_update_post( array( 'ID' => $donation->ID, 'post_title' => __( 'Donation and Fundraising Policy', 'cohf-child' ) ) );
	}
	update_option( 'cohf_legal_v2_done', COHF_CHILD_VERSION );
}
add_action( 'admin_init', 'cohf_legal_update_v2', 35 );
