<?php
/**
 * Imagery: bundled art direction, media-library import, and art-directed output.
 *
 * The theme ships a complete image set so the site is never shown with empty
 * placeholders. Those files are used directly by default, and the one-time
 * setup also imports them into the Media Library and attaches them to the
 * twelve programmes so editors can replace any of them without touching code.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * The bundled image set, with the alternative text each image should carry.
 *
 * Alt text is written here rather than left to chance, because it is an
 * accessibility and safeguarding requirement, not a nicety.
 *
 * @return array<string,array<string,string>>
 */
function cohf_image_library() {
	return array(
		'hero-home' => array(
			'file' => 'hero-home.jpg',
			'alt'  => __( 'The Executive Director with three boys in their new school uniforms and their grandmother outside the family home.', 'cohf-child' ),
		),
		'story-community' => array(
			'file' => 'story-community.jpg',
			'alt'  => __( 'A boy in a worn and torn school uniform photographed from behind before the Foundation stepped in.', 'cohf-child' ),
		),
		'hero-about' => array(
			'file' => 'hero-about.jpg',
			'alt'  => __( 'Women seated together in discussion at a Cistern of Hope Foundation women empowerment seminar.', 'cohf-child' ),
		),
		'hero-impact' => array(
			'file' => 'hero-impact.jpg',
			'alt'  => __( 'Schoolchildren receiving sanitary pads and food supplies at a Foundation distribution.', 'cohf-child' ),
		),
		'hero-partners' => array(
			'file' => 'hero-partners.jpg',
			'alt'  => __( 'Colleagues and institutional partners in discussion around a table in a bright meeting room.', 'cohf-child' ),
		),
		'hero-get-involved' => array(
			'file' => 'hero-get-involved.jpg',
			'alt'  => __( 'Young people seated in a wide circle with a facilitator during a Foundation youth session.', 'cohf-child' ),
		),
		'hero-support' => array(
			'file' => 'hero-support.jpg',
			'alt'  => __( 'A child in school uniform walking to school along a tree-lined path in morning light.', 'cohf-child' ),
		),
		'hero-accountability' => array(
			'file' => 'hero-accountability.jpg',
			'alt'  => __( 'An orderly desk with record books and files, hands carefully filing a document.', 'cohf-child' ),
		),
		'programme-01' => array( 'file' => 'programme-01.jpg', 'alt' => __( 'Three boys in new school uniforms standing with the Executive Director outside their school.', 'cohf-child' ) ),
		'programme-02' => array( 'file' => 'programme-02.jpg', 'alt' => __( 'Young adults collaborating in a practical training workshop, one explaining an idea.', 'cohf-child' ) ),
		'programme-03' => array( 'file' => 'programme-03.jpg', 'alt' => __( 'A woman entrepreneur arranging goods in her market stall, reviewing a notebook with a colleague.', 'cohf-child' ) ),
		'programme-04' => array( 'file' => 'programme-04.jpg', 'alt' => __( 'Household food and essential supplies delivered to a family at their home.', 'cohf-child' ) ),
		'programme-05' => array( 'file' => 'programme-05.jpg', 'alt' => __( 'A mentor and a teenager in quiet conversation on a bench in a calm courtyard.', 'cohf-child' ) ),
		'programme-06' => array( 'file' => 'programme-06.jpg', 'alt' => __( 'Girls receiving packs of sanitary pads from Foundation staff during a door-to-door distribution.', 'cohf-child' ) ),
		'programme-07' => array( 'file' => 'programme-07.jpg', 'alt' => __( 'Children sharing a hot meal together at a Foundation feeding session.', 'cohf-child' ) ),
		'programme-08' => array( 'file' => 'programme-08.jpg', 'alt' => __( 'A Foundation representative collecting sacks of food staples from a wholesaler.', 'cohf-child' ) ),
		'programme-09' => array( 'file' => 'programme-09.jpg', 'alt' => __( 'Young people planting tree seedlings together on a green hillside.', 'cohf-child' ) ),
		'programme-10' => array( 'file' => 'programme-10.jpg', 'alt' => __( 'A facilitator demonstrating handwashing to schoolchildren at a clean water point.', 'cohf-child' ) ),
		'programme-11' => array( 'file' => 'programme-11.jpg', 'alt' => __( 'Young people learning at laptops in a community digital learning space with a trainer.', 'cohf-child' ) ),
		'programme-12' => array( 'file' => 'programme-12.jpg', 'alt' => __( 'A community planning meeting with elders and young people around a shared table.', 'cohf-child' ) ),

		// Impact story lead images, supplied by the Foundation with consent.
		'story-01-women-seminar' => array( 'file' => 'story-01-women-seminar.jpg', 'alt' => __( 'Women seated together in discussion at the Foundation women empowerment seminar.', 'cohf-child' ) ),
		'story-02-dignity-packs' => array( 'file' => 'story-02-dignity-packs.jpg', 'alt' => __( 'Children and Foundation staff together after a sanitary pad distribution at a home for orphaned children.', 'cohf-child' ) ),
		'story-02-fellowship-tshirts' => array( 'file' => 'story-02-fellowship-tshirts.jpg', 'alt' => __( 'Foundation team members in Cistern of Hope Foundation branded T-shirts handing out sanitary pads to children at a children\'s home.', 'cohf-child' ) ),
		'story-05-school-pads' => array( 'file' => 'story-05-school-pads.jpg', 'alt' => __( 'A Cistern of Hope Foundation team member handing packs of sanitary pads to schoolgirls in uniform during a monthly school donation.', 'cohf-child' ) ),
		'story-04-before' => array( 'file' => 'story-04-before.jpg', 'alt' => __( 'Before: one of the boys, photographed from behind, barefoot in a torn school shirt and ripped shorts.', 'cohf-child' ) ),
		'story-04-after' => array( 'file' => 'story-04-after.jpg', 'alt' => __( 'After: the three boys in new school uniforms, shoes and school bags, standing with a Foundation representative outside their primary school.', 'cohf-child' ) ),
		// Photo gallery, supplied by the Foundation.
		'gallery-shoe-donation' => array( 'file' => 'gallery-shoe-donation.jpg', 'alt' => __( 'A young man crouching beside rows of donated children\'s shoes laid out for distribution.', 'cohf-child' ) ),
		'gallery-enterprise-visit-eggs' => array( 'file' => 'gallery-enterprise-visit-eggs.jpg', 'alt' => __( 'Foundation team members visiting a roadside egg vendor and his food cart.', 'cohf-child' ) ),
		'gallery-enterprise-visit-potatoes' => array( 'file' => 'gallery-enterprise-visit-potatoes.jpg', 'alt' => __( 'Foundation team members with a trader at his roadside potato stall.', 'cohf-child' ) ),
		'gallery-womens-enterprise-stall' => array( 'file' => 'gallery-womens-enterprise-stall.jpg', 'alt' => __( 'A woman at her fruit and vegetable stall during a Foundation visit.', 'cohf-child' ) ),
		'gallery-womens-seminar' => array( 'file' => 'gallery-womens-seminar.jpg', 'alt' => __( 'Women seated in a large circle during a Foundation women empowerment seminar.', 'cohf-child' ) ),
		'gallery-childrens-home-group' => array( 'file' => 'gallery-childrens-home-group.jpg', 'alt' => __( 'Children at a children\'s home holding packs of sanitary pads, gathered with the Foundation team for a group photograph.', 'cohf-child' ) ),
		'gallery-childrens-home-welcome' => array( 'file' => 'gallery-childrens-home-welcome.jpg', 'alt' => __( 'A Foundation team member in a branded shirt warmly holding hands with a girl at a children\'s home.', 'cohf-child' ) ),
		'gallery-door-to-door-girls' => array( 'file' => 'gallery-door-to-door-girls.jpg', 'alt' => __( 'A Foundation team member in a Cistern of Hope Foundation T-shirt speaking with girls holding sanitary pad packs outside a home.', 'cohf-child' ) ),
		'gallery-door-to-door-pads' => array( 'file' => 'gallery-door-to-door-pads.jpg', 'alt' => __( 'A Foundation team member handing a bundle of sanitary pads to a young woman at her doorstep.', 'cohf-child' ) ),
		'story-03-door-to-door' => array( 'file' => 'story-03-door-to-door.jpg', 'alt' => __( 'A Foundation staff member handing a pack of sanitary pads to a girl at her home.', 'cohf-child' ) ),
		'story-04-back-to-school' => array( 'file' => 'story-04-back-to-school.jpg', 'alt' => __( 'Three boys in school uniform standing with their family outside their home.', 'cohf-child' ) ),

		// Leadership portraits, supplied by the Foundation.
		'leader-justus-kubai' => array( 'file' => 'leader-justus-kubai.jpg', 'alt' => __( 'Mr. Justus Kubai, Founder and Executive Director.', 'cohf-child' ) ),
		'leader-henry-onzere' => array( 'file' => 'leader-henry-onzere.jpg', 'alt' => __( 'Henry Onzere, Chairperson of the Board of Directors.', 'cohf-child' ) ),
		'leader-victor-luhambo' => array( 'file' => 'leader-victor-luhambo.jpg', 'alt' => __( 'Victor Luhambo, Operations Manager.', 'cohf-child' ) ),
		'leader-kevin-bosire' => array( 'file' => 'leader-kevin-bosire.jpg', 'alt' => __( 'Kevin Bosire, Communications, Media and Digital Engagement Manager.', 'cohf-child' ) ),
		'leader-christabel-sagali' => array( 'file' => 'leader-christabel-sagali.jpg', 'alt' => __( 'Christabel Sagali, Community Engagement and Partnerships Manager.', 'cohf-child' ) ),
		'leader-claire-auma' => array( 'file' => 'leader-claire-auma.jpg', 'alt' => __( 'Claire Auma, member of the leadership and governance team.', 'cohf-child' ) ),
		'leader-daria-lumati' => array( 'file' => 'leader-daria-lumati.jpg', 'alt' => __( 'Daria Lumati, member of the leadership and governance team.', 'cohf-child' ) ),
	);
}

/**
 * URL for a bundled image.
 *
 * @param string $key Image key.
 * @return string URL, or empty string when the file is absent.
 */
function cohf_img_url( $key ) {
	$library = cohf_image_library();
	if ( empty( $library[ $key ] ) ) {
		return '';
	}
	$file = $library[ $key ]['file'];
	if ( ! file_exists( COHF_CHILD_DIR . '/assets/images/' . $file ) ) {
		return '';
	}
	return COHF_CHILD_URI . '/assets/images/' . $file;
}

/**
 * Alternative text for a bundled image.
 *
 * @param string $key Image key.
 * @return string
 */
function cohf_img_alt( $key ) {
	$library = cohf_image_library();
	return isset( $library[ $key ]['alt'] ) ? $library[ $key ]['alt'] : '';
}

/**
 * Print a bundled image as a responsive, art-directed tag.
 *
 * A Customizer override, if set, always wins over the bundled default, so the
 * Foundation can replace any image from the admin without editing a file.
 *
 * @param string $key  Image key.
 * @param array  $args eager (bool), class (string), sizes (string), alt (string).
 */
function cohf_the_image( $key, $args = array() ) {
	$defaults = array(
		'eager' => false,
		'class' => '',
		'sizes' => '100vw',
		'alt'   => null,
	);
	$args = array_merge( $defaults, $args );

	// A Customizer override takes precedence over the bundled image.
	$override = (int) get_theme_mod( 'cohf_image_' . str_replace( '-', '_', $key ) );
	if ( $override ) {
		echo wp_get_attachment_image(
			$override,
			'full',
			false,
			array(
				'class'         => $args['class'],
				'sizes'         => $args['sizes'],
				'loading'       => $args['eager'] ? 'eager' : 'lazy',
				'decoding'      => 'async',
				'fetchpriority' => $args['eager'] ? 'high' : 'auto',
			)
		);
		return;
	}

	$url = cohf_img_url( $key );
	if ( ! $url ) {
		printf(
			'<div class="media-placeholder"><span>%s</span></div>',
			esc_html__( 'Image placeholder', 'cohf-child' )
		);
		return;
	}

	$alt = null === $args['alt'] ? cohf_img_alt( $key ) : $args['alt'];

	printf(
		'<img src="%1$s" alt="%2$s" class="%3$s" sizes="%4$s" loading="%5$s" decoding="async" fetchpriority="%6$s">',
		esc_url( $url ),
		esc_attr( $alt ),
		esc_attr( $args['class'] ),
		esc_attr( $args['sizes'] ),
		$args['eager'] ? 'eager' : 'lazy',
		$args['eager'] ? 'high' : 'auto'
	);
}

/**
 * Import one bundled image into the Media Library.
 *
 * @param string $key Image key.
 * @return int Attachment ID, or 0 on failure.
 */
function cohf_import_image( $key ) {
	$library = cohf_image_library();
	if ( empty( $library[ $key ] ) ) {
		return 0;
	}

	$file = $library[ $key ]['file'];
	$path = COHF_CHILD_DIR . '/assets/images/' . $file;
	if ( file_exists( $path ) === false ) {
		return 0;
	}
	$hash = md5_file( $path );

	// Already imported? Reuse it, but only while the theme still ships the
	// same artwork. When a release replaces the file, the stale copy in the
	// Media Library has to go or the site keeps showing the old picture.
	$existing = get_posts( array(
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		'posts_per_page' => 1,
		'fields'         => 'ids',
		'meta_key'       => '_cohf_image_key',
		'meta_value'     => $key,
		'no_found_rows'  => true,
	) );
	if ( $existing ) {
		$old_id   = (int) $existing[0];
		$old_hash = get_post_meta( $old_id, '_cohf_image_hash', true );
		if ( $old_hash === $hash ) {
			return $old_id;
		}
		wp_delete_attachment( $old_id, true );
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$upload = wp_upload_bits( $file, null, file_get_contents( $path ) );
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}

	$attachment_id = wp_insert_attachment( array(
		'post_mime_type' => 'image/jpeg',
		'post_title'     => sanitize_file_name( pathinfo( $file, PATHINFO_FILENAME ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	), $upload['file'] );

	if ( ! $attachment_id || is_wp_error( $attachment_id ) ) {
		return 0;
	}

	wp_update_attachment_metadata(
		$attachment_id,
		wp_generate_attachment_metadata( $attachment_id, $upload['file'] )
	);

	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $library[ $key ]['alt'] );
	update_post_meta( $attachment_id, '_cohf_image_key', $key );
	update_post_meta( $attachment_id, '_cohf_image_hash', $hash );

	return (int) $attachment_id;
}

/**
 * Initials for a person's name, used for the monogram portraits.
 *
 * "Mr. Justus Kubai" becomes "JK". Honorifics are ignored so a title never
 * becomes an initial.
 *
 * @param string $name Full name.
 * @return string Two letters, uppercase.
 */
function cohf_initials( $name ) {
	$name = wp_strip_all_tags( (string) $name );
	$name = preg_replace( '/\b(Mr|Mrs|Ms|Miss|Dr|Prof|Rev|Hon|Eng)\.?\s+/i', '', $name );

	$parts = preg_split( '/\s+/', trim( $name ) );
	$parts = array_values( array_filter( (array) $parts, 'strlen' ) );

	if ( empty( $parts ) ) {
		return '';
	}
	if ( count( $parts ) === 1 ) {
		return strtoupper( mb_substr( $parts[0], 0, 2 ) );
	}

	$first = mb_substr( $parts[0], 0, 1 );
	$last  = mb_substr( $parts[ count( $parts ) - 1 ], 0, 1 );

	return strtoupper( $first . $last );
}
