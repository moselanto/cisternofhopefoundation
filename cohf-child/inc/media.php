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
		'programme-02' => array( 'file' => 'programme-02.jpg', 'alt' => __( 'Foundation team members visiting a young entrepreneur at his roadside food cart.', 'cohf-child' ) ),
		'programme-03' => array( 'file' => 'programme-03.jpg', 'alt' => __( 'A woman at her fruit and vegetable stall with a Foundation team member beside her.', 'cohf-child' ) ),
		'programme-04' => array( 'file' => 'programme-04.jpg', 'alt' => __( 'Household food and essential supplies delivered to a family at their home.', 'cohf-child' ) ),
		'programme-05' => array( 'file' => 'programme-05.jpg', 'alt' => __( 'A mentor and a teenager in quiet conversation on a bench in a calm courtyard.', 'cohf-child' ) ),
		'programme-06' => array( 'file' => 'programme-06.jpg', 'alt' => __( 'Girls receiving packs of sanitary pads from Foundation staff during a door-to-door distribution.', 'cohf-child' ) ),
		'programme-07' => array( 'file' => 'programme-07.jpg', 'alt' => __( 'Children sharing a hot meal together at a Foundation feeding session.', 'cohf-child' ) ),
		'programme-08' => array( 'file' => 'programme-08.jpg', 'alt' => __( 'A Foundation representative collecting sacks of food staples from a wholesaler.', 'cohf-child' ) ),
		'programme-09' => array( 'file' => 'programme-09.jpg', 'alt' => __( 'Young people planting tree seedlings together on a green hillside.', 'cohf-child' ) ),
		'programme-10' => array( 'file' => 'programme-10.jpg', 'alt' => __( 'A facilitator demonstrating handwashing to schoolchildren at a clean water point.', 'cohf-child' ) ),
		'programme-11' => array( 'file' => 'programme-11.jpg', 'alt' => __( 'Young people learning at laptops in a community digital learning space with a trainer.', 'cohf-child' ) ),
		'programme-13' => array( 'file' => 'programme-13.jpg', 'alt' => __( 'A widow smiling as she receives her monthly food support of flour, bread, milk and cooking fat at home.', 'cohf-child' ) ),
		'programme-12' => array( 'file' => 'programme-12.jpg', 'alt' => __( 'A community planning meeting with elders and young people around a shared table.', 'cohf-child' ) ),

		// Impact story lead images, supplied by the Foundation with consent.
		'story-01-women-seminar' => array( 'file' => 'story-01-women-seminar.jpg', 'alt' => __( 'Women seated together in discussion at the Foundation women empowerment seminar.', 'cohf-child' ) ),
		'story-02-dignity-packs' => array( 'file' => 'story-02-dignity-packs.jpg', 'alt' => __( 'Children and Foundation staff together after a sanitary pad distribution at a home for orphaned children.', 'cohf-child' ) ),
		'story-02-fellowship-tshirts' => array( 'file' => 'story-02-fellowship-tshirts.jpg', 'alt' => __( 'Foundation team members in Cistern of Hope Foundation branded T-shirts handing out sanitary pads to children at a children\'s home.', 'cohf-child' ) ),
		'story-05-school-pads' => array( 'file' => 'story-05-school-pads.jpg', 'alt' => __( 'A Cistern of Hope Foundation team member handing packs of sanitary pads to schoolgirls in uniform during a monthly school donation.', 'cohf-child' ) ),
		'story-04-before' => array( 'file' => 'story-04-before.jpg', 'alt' => __( 'Before: one of the boys, photographed from behind, barefoot in a torn school shirt and ripped shorts.', 'cohf-child' ) ),
		'story-04-after' => array( 'file' => 'story-04-after.jpg', 'alt' => __( 'After: the three boys in new school uniforms, shoes and school bags, standing with a Foundation representative outside their primary school.', 'cohf-child' ) ),
		'story-06-tailoring-before' => array( 'file' => 'story-06-tailoring-before.jpg', 'alt' => __( 'Before: Mr Owino\'s small tailoring corner at home, with a single sewing machine and a few finished shirts hanging on the wall.', 'cohf-child' ) ),
		'story-06-tailoring-workshop' => array( 'file' => 'story-06-tailoring-workshop.jpg', 'alt' => __( 'Mr Owino at work at his sewing machine in his tailoring workshop, with finished robes and shirts on display behind him.', 'cohf-child' ) ),
		'story-07-dan-laptop' => array( 'file' => 'story-07-dan-laptop.jpg', 'alt' => __( 'Dan smiling as he works on the laptop the Foundation helped him acquire for his photography business.', 'cohf-child' ) ),
		'story-07-dan-mounting' => array( 'file' => 'story-07-dan-mounting.jpg', 'alt' => __( 'Dan finishing a mounted graduation photo in his home photography and photo-mounting business.', 'cohf-child' ) ),
		'story-07-dan-portrait' => array( 'file' => 'story-07-dan-portrait.jpg', 'alt' => __( 'Dan holding a finished mounted graduation portrait he produced for a client.', 'cohf-child' ) ),
		'story-07-dan-measuring' => array( 'file' => 'story-07-dan-measuring.jpg', 'alt' => __( 'Dan measuring a mounting board at his workbench, with framed portraits he has produced on display.', 'cohf-child' ) ),
		// Photo gallery, supplied by the Foundation.
		'gallery-widows-food-support' => array( 'file' => 'gallery-widows-food-support.jpg', 'alt' => __( 'A widow smiling as she receives her monthly food support of flour, bread, milk and cooking fat at home.', 'cohf-child' ) ),
		'gallery-widows-home-visit' => array( 'file' => 'gallery-widows-home-visit.jpg', 'alt' => __( 'A widow supported by the Foundation standing outside her mud-walled home.', 'cohf-child' ) ),
		'gallery-shoe-donation' => array( 'file' => 'gallery-shoe-donation.jpg', 'alt' => __( 'A young entrepreneur crouching beside rows of shoes laid out for sale.', 'cohf-child' ) ),
		'gallery-enterprise-visit-eggs' => array( 'file' => 'gallery-enterprise-visit-eggs.jpg', 'alt' => __( 'Foundation team members visiting a roadside egg vendor and his food cart.', 'cohf-child' ) ),
		'gallery-enterprise-visit-potatoes' => array( 'file' => 'gallery-enterprise-visit-potatoes.jpg', 'alt' => __( 'Foundation team members with a trader at his roadside potato stall.', 'cohf-child' ) ),
		'gallery-womens-enterprise-stall' => array( 'file' => 'gallery-womens-enterprise-stall.jpg', 'alt' => __( 'A woman at her fruit and vegetable stall during a Foundation visit.', 'cohf-child' ) ),
		'gallery-womens-seminar' => array( 'file' => 'gallery-womens-seminar.jpg', 'alt' => __( 'Women seated in a large circle during a Foundation women empowerment seminar.', 'cohf-child' ) ),
		'gallery-childrens-home-group' => array( 'file' => 'gallery-childrens-home-group.jpg', 'alt' => __( 'Children at a children\'s home holding packs of sanitary pads, gathered with the Foundation team for a group photograph.', 'cohf-child' ) ),
		'gallery-childrens-home-welcome' => array( 'file' => 'gallery-childrens-home-welcome.jpg', 'alt' => __( 'A Foundation team member in a branded shirt warmly holding hands with a girl at a children\'s home.', 'cohf-child' ) ),
		'gallery-door-to-door-girls' => array( 'file' => 'gallery-door-to-door-girls.jpg', 'alt' => __( 'A Foundation team member in a Cistern of Hope Foundation T-shirt speaking with girls holding sanitary pad packs outside a home.', 'cohf-child' ) ),
		'gallery-door-to-door-pads' => array( 'file' => 'gallery-door-to-door-pads.jpg', 'alt' => __( 'A Foundation team member handing a bundle of sanitary pads to a young woman at her doorstep.', 'cohf-child' ) ),
		'gallery-three-boys-at-school' => array( 'file' => 'gallery-three-boys-at-school.jpg', 'alt' => __( 'Three boys in new blue school uniforms, woolly hats, shoes and school bags standing in front of their primary school.', 'cohf-child' ) ),
		'gallery-home-visit-child' => array( 'file' => 'gallery-home-visit-child.jpg', 'alt' => __( 'A Foundation representative standing with a young boy in a red school sweater outside his family home.', 'cohf-child' ) ),
		'gallery-before-school-meeting' => array( 'file' => 'gallery-before-school-meeting.jpg', 'alt' => __( 'A Foundation representative crouching beside a barefoot boy in a torn green school uniform outside his home.', 'cohf-child' ) ),
		'gallery-school-pads-celebration' => array( 'file' => 'gallery-school-pads-celebration.jpg', 'alt' => __( 'Schoolgirls in uniform raising packs of sanitary pads in celebration with Foundation team members during a school visit.', 'cohf-child' ) ),
		'gallery-food-staples-purchase' => array( 'file' => 'gallery-food-staples-purchase.jpg', 'alt' => __( 'A Foundation representative at a market with sacks of rice bought for distribution.', 'cohf-child' ) ),
		'gallery-sanitary-pads-stock' => array( 'file' => 'gallery-sanitary-pads-stock.jpg', 'alt' => __( 'A Foundation representative standing beside a tall stack of sanitary pad boxes on a city street.', 'cohf-child' ) ),
		'gallery-food-supplies' => array( 'file' => 'gallery-food-supplies.jpg', 'alt' => __( 'Food supplies ready for distribution: cooking oil, bread, flour, drinking water and tissue.', 'cohf-child' ) ),
		'gallery-shared-meal' => array( 'file' => 'gallery-shared-meal.jpg', 'alt' => __( 'Children seated in a circle sharing a hot meal together with a young woman.', 'cohf-child' ) ),
		'impact-women-livelihoods' => array( 'file' => 'impact-women-livelihoods.jpg', 'alt' => __( 'A woman at her fruit and vegetable stall of avocados, tomatoes, greens and eggs, with a Foundation team member beside her.', 'cohf-child' ) ),
		'shop-hero' => array( 'file' => 'shop-hero.jpg', 'alt' => __( 'Handmade pieces from Hope Market: a Maasai beaded collar, a sisal tote, beaded sandals, a copper Africa clock, a clay mural and beaded placemats.', 'cohf-child' ) ),
		'story-03-door-to-door' => array( 'file' => 'story-03-door-to-door.jpg', 'alt' => __( 'A Foundation staff member handing a pack of sanitary pads to a girl at her home.', 'cohf-child' ) ),
		'story-04-back-to-school' => array( 'file' => 'story-04-back-to-school.jpg', 'alt' => __( 'Three boys in school uniform standing with their family outside their home.', 'cohf-child' ) ),

		// Partner images, supplied by the Foundation (14.9.0). Used by
		// cohf_partner_bundled_images() until a main image is set in wp-admin.
		'partner-deliverance-church-logo' => array( 'file' => 'partner-deliverance-church-logo.jpg', 'alt' => __( 'Deliverance Church, Cistern of Hope Center logo: a red cross above a blue triangle, with the words The Church of Choice and Luke 4:18.', 'cohf-child' ) ),
		'partner-deliverance-church-feeding' => array( 'file' => 'partner-deliverance-church-feeding.jpg', 'alt' => __( 'Children seated on plastic chairs in a church compound being served porridge in cups at a Sunday-morning feeding session.', 'cohf-child' ) ),

		'partner-australia-christmas-meal' => array( 'file' => 'partner-australia-christmas-meal.jpg', 'alt' => __( 'Rows of plates of samosas, watermelon, bananas, boiled eggs and snacks laid out for a Christmas 2024 meal, with a Foundation member holding a hand-written Merry Christmas thank-you sign for the supporters from Australia.', 'cohf-child' ) ),
		'partner-australia-supporter-1' => array( 'file' => 'partner-australia-supporter-1.jpg', 'alt' => __( 'Portrait of one of the Foundation\'s supporters from Australia, smiling.', 'cohf-child' ) ),
		'partner-australia-supporter-2' => array( 'file' => 'partner-australia-supporter-2.jpg', 'alt' => __( 'Portrait of one of the Foundation\'s supporters from Australia, smiling, wearing glasses and a pearl necklace.', 'cohf-child' ) ),

		'partner-suivera-community-logo' => array( 'file' => 'partner-suivera-community-logo.jpg', 'alt' => __( 'Suivera Community logo: a light green interwoven heart on a dark green background.', 'cohf-child' ) ),

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
	// A photograph chosen in Appearance > Customize > Site photographs wins.
	$override = (int) get_theme_mod( 'cohf_image_' . str_replace( '-', '_', $key ) );
	if ( $override ) {
		$src = wp_get_attachment_image_url( $override, 'full' );
		if ( $src ) {
			return $src;
		}
	}
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
	$override = (int) get_theme_mod( 'cohf_image_' . str_replace( '-', '_', $key ) );
	if ( $override ) {
		$alt = (string) get_post_meta( $override, '_wp_attachment_image_alt', true );
		if ( '' !== $alt ) {
			return $alt;
		}
	}
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
