<?php
/**
 * Hope Market - first products.
 *
 * Products photographed by the Foundation, bundled with the theme in
 * assets/images/shop/ as 1200x1200 squares so they sit evenly in the
 * WooCommerce grid (WooCommerce crops thumbnails to 1:1).
 *
 * Each product is created once, the first time an administrator opens
 * wp-admin with WooCommerce active. After that the product belongs to the
 * shop: edit the name, price, description or photo under Products and this
 * file never touches it again. Deleting a product is also permanent - it is
 * not recreated.
 *
 * Prices are the selling prices the Foundation supplied (KES). Names and
 * descriptions describe only what is visible in the photographs.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Seed definitions, keyed by a stable id.
 *
 * @return array<string,array<string,mixed>>
 */
function cohf_shop_seed_products() {
	$design_note = __( 'Pictured is a selection of the designs available. Each pair is handmade, so beadwork and colours vary. Add your preferred design and shoe size in the order notes and we will confirm with you before dispatch.', 'cohf-child' );

	return array(
		'beaded-leather-sandals' => array(
			'name'     => __( 'Beaded Leather Sandals', 'cohf-child' ),
			'price'    => '1000',
			'category' => __( 'Sandals', 'cohf-child' ),
			'image'    => 'beaded-leather-sandals.jpg',
			'alt'      => __( 'Rows of flat tan leather sandals decorated with colourful handmade beadwork.', 'cohf-child' ),
			'short'    => __( 'Flat tan leather sandals with hand-stitched beadwork on the straps.', 'cohf-child' ),
			'long'     => $design_note,
			'order'    => 1,
		),
		'beaded-wedge-sandals'   => array(
			'name'     => __( 'Beaded Wedge Sandals', 'cohf-child' ),
			'price'    => '3000',
			'category' => __( 'Sandals', 'cohf-child' ),
			'image'    => 'beaded-wedge-sandals.jpg',
			'alt'      => __( 'Wedge sandals with cushioned soles and beaded straps in gold, white, blue and multicolour designs.', 'cohf-child' ),
			'short'    => __( 'Cushioned wedge-sole sandals with beaded straps.', 'cohf-child' ),
			'long'     => $design_note,
			'order'    => 2,
		),
		'beaded-leather-clutch'  => array(
			'name'     => __( 'Beaded Leather Clutch', 'cohf-child' ),
			'price'    => '2200',
			'category' => __( 'Bags and baskets', 'cohf-child' ),
			'image'    => 'beaded-leather-clutch.jpg',
			'alt'      => __( 'Leather clutch bags with curved, fully beaded flaps in white and gold, multicolour, and brown, black and white bands.', 'cohf-child' ),
			'short'    => __( 'Leather clutch with a curved flap covered in handmade beadwork.', 'cohf-child' ),
			'long'     => __( 'Available in several bead patterns and in black or brown leather. Tell us the pattern you would like in the order notes and we will confirm availability before dispatch.', 'cohf-child' ),
			'order'    => 3,
		),
		'sisal-basket-red-stripe' => array(
			'name'     => __( 'Sisal Basket Bag - Red Stripe', 'cohf-child' ),
			'price'    => '1850',
			'category' => __( 'Bags and baskets', 'cohf-child' ),
			'image'    => 'sisal-basket-red-stripe.jpg',
			'alt'      => __( 'Woven natural sisal basket bag with a red stripe, leather-wrapped handles and a leather button fastening.', 'cohf-child' ),
			'short'    => __( 'Hand-woven sisal basket with a red stripe, leather handles and a leather button closure.', 'cohf-child' ),
			'long'     => __( 'Hand-woven, so each basket differs slightly in weave and shade.', 'cohf-child' ),
			'order'    => 4,
		),
		'sisal-tote-beaded-flap' => array(
			'name'     => __( 'Sisal Tote with Beaded Leather Flap', 'cohf-child' ),
			'price'    => '3000',
			'category' => __( 'Bags and baskets', 'cohf-child' ),
			'image'    => 'sisal-tote-beaded-flap.jpg',
			'alt'      => __( 'Natural sisal tote with brown leather trim and shoulder straps, and a leather flap set with a round beaded disc.', 'cohf-child' ),
			'short'    => __( 'Hand-woven sisal tote with leather trim, shoulder straps and a beaded leather flap.', 'cohf-child' ),
			'long'     => __( 'Hand-woven, so each tote differs slightly in weave and shade.', 'cohf-child' ),
			'order'    => 5,
			'gallery'  => array(
				array(
					'image' => 'sisal-tote-beaded-flap-2.jpg',
					'alt'   => __( 'The sisal tote hanging by its brown leather shoulder straps, showing the beaded disc on the leather flap.', 'cohf-child' ),
				),
			),
		),
		'wooden-salad-servers'   => array(
			'name'     => __( 'Wooden Salad Servers with Beaded Handles (Pair)', 'cohf-child' ),
			'price'    => '800',
			'category' => __( 'Home and kitchen', 'cohf-child' ),
			'image'    => 'wooden-salad-servers.jpg',
			'alt'      => __( 'Hand-carved wooden salad spoons and forks tied in pairs, with beaded and patterned handle bands.', 'cohf-child' ),
			'short'    => __( 'A pair of hand-carved wooden salad servers with a beaded band on each handle. Price is per pair.', 'cohf-child' ),
			'long'     => __( 'Each pair is carved by hand, so grain, shade and beadwork vary. Add a colour preference for the beadwork in the order notes and we will do our best to match it.', 'cohf-child' ),
			'order'    => 6,
		),
		'coconut-wood-coasters'  => array(
			'name'     => __( 'Coconut Wood Coasters (Set of 4)', 'cohf-child' ),
			'price'    => '2000',
			'category' => __( 'Home and kitchen', 'cohf-child' ),
			'image'    => 'coconut-wood-coasters.jpg',
			'alt'      => __( 'A tied stack of square coconut wood coasters with a cream inlaid band decorated with black lines and circles.', 'cohf-child' ),
			'short'    => __( 'Set of four square coconut wood coasters with a cream inlaid band, tied with raffia.', 'cohf-child' ),
			'long'     => __( 'Natural coconut wood, so the grain pattern differs on every coaster. Wipe clean with a dry or slightly damp cloth.', 'cohf-child' ),
			'order'    => 7,
		),
		'sisal-storage-basket-natural' => array(
			'name'     => __( 'Sisal Storage Basket - Natural', 'cohf-child' ),
			'price'    => '1650',
			'category' => __( 'Bags and baskets', 'cohf-child' ),
			'image'    => 'sisal-storage-basket-natural.jpg',
			'alt'      => __( 'A round, open hand-woven sisal basket in natural golden fibre.', 'cohf-child' ),
			'short'    => __( 'Round, open hand-woven sisal basket in natural fibre. Works as a plant cover, laundry or storage basket.', 'cohf-child' ),
			'long'     => __( 'Hand-woven, so each basket differs slightly in weave and shade.', 'cohf-child' ),
			'order'    => 8,
		),
		'sisal-storage-basket-two-tone' => array(
			'name'     => __( 'Sisal Storage Basket - Two-Tone', 'cohf-child' ),
			'price'    => '1850',
			'category' => __( 'Bags and baskets', 'cohf-child' ),
			'image'    => 'sisal-storage-basket-two-tone.jpg',
			'alt'      => __( 'A round, open hand-woven sisal basket, natural at the top and dark brown at the base.', 'cohf-child' ),
			'short'    => __( 'Round, open hand-woven sisal basket, natural at the top and dark brown at the base.', 'cohf-child' ),
			'long'     => __( 'Hand-woven, so each basket differs slightly in weave and shade.', 'cohf-child' ),
			'order'    => 9,
		),
	);
}

/**
 * Import a bundled shop photo into the Media Library.
 *
 * @param string $file Filename in assets/images/shop/.
 * @param string $alt  Alternative text.
 * @return int Attachment ID, or 0.
 */
function cohf_shop_import_image( $file, $alt ) {
	$path = COHF_CHILD_DIR . '/assets/images/shop/' . $file;
	if ( file_exists( $path ) === false ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$upload = wp_upload_bits( $file, null, file_get_contents( $path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme file.
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

	wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $upload['file'] ) );
	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt );

	return (int) $attachment_id;
}

/**
 * Create any seed product that has never been created before.
 */
function cohf_shop_seed() {
	if ( cohf_has_shop() === false || class_exists( 'WC_Product_Simple' ) === false ) {
		return;
	}
	if ( current_user_can( 'manage_woocommerce' ) === false ) {
		return;
	}

	$done  = (array) get_option( 'cohf_shop_seeded', array() );
	$seeds = cohf_shop_seed_products();

	if ( count( array_intersect( array_keys( $seeds ), $done ) ) === count( $seeds ) ) {
		return;
	}

	foreach ( $seeds as $key => $seed ) {
		if ( in_array( $key, $done, true ) ) {
			continue;
		}

		// Recorded first, so a failure part-way can never create duplicates.
		$done[] = $key;
		update_option( 'cohf_shop_seeded', $done, false );

		$term    = term_exists( $seed['category'], 'product_cat' );
		$term    = $term ? $term : wp_insert_term( $seed['category'], 'product_cat' );
		$term_id = is_array( $term ) ? (int) $term['term_id'] : 0;

		$product = new WC_Product_Simple();
		$product->set_name( $seed['name'] );
		$product->set_slug( $key );
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_regular_price( $seed['price'] );
		$product->set_short_description( $seed['short'] );
		$product->set_description( $seed['long'] );
		$product->set_menu_order( (int) $seed['order'] );
		$product->set_manage_stock( false );
		$product->set_stock_status( 'instock' );
		if ( $term_id ) {
			$product->set_category_ids( array( $term_id ) );
		}

		$image_id = cohf_shop_import_image( $seed['image'], $seed['alt'] );
		if ( $image_id ) {
			$product->set_image_id( $image_id );
		}

		$gallery_ids = cohf_shop_import_gallery( $seed );
		if ( $gallery_ids ) {
			$product->set_gallery_image_ids( $gallery_ids );
		}

		$product_id = $product->save();
		if ( $product_id ) {
			update_post_meta( $product_id, '_cohf_seed_key', $key );
		}
	}
}
add_action( 'admin_init', 'cohf_shop_seed', 30 );

/**
 * Import the extra photos listed for a seed product.
 *
 * @param array $seed Seed definition.
 * @return int[] Attachment IDs.
 */
function cohf_shop_import_gallery( $seed ) {
	$ids = array();
	if ( empty( $seed['gallery'] ) || is_array( $seed['gallery'] ) === false ) {
		return $ids;
	}
	foreach ( $seed['gallery'] as $photo ) {
		$id = cohf_shop_import_image( $photo['image'], $photo['alt'] );
		if ( $id ) {
			$ids[] = $id;
		}
	}
	return $ids;
}

/**
 * Add extra photos to seed products that were created before those photos
 * shipped. Runs once per product, and only when the product still has no
 * gallery, so photos the shop has chosen are never replaced.
 */
function cohf_shop_seed_galleries() {
	if ( cohf_has_shop() === false || function_exists( 'wc_get_product' ) === false ) {
		return;
	}
	if ( current_user_can( 'manage_woocommerce' ) === false ) {
		return;
	}

	$done = (array) get_option( 'cohf_shop_gallery_seeded', array() );

	foreach ( cohf_shop_seed_products() as $key => $seed ) {
		if ( empty( $seed['gallery'] ) || in_array( $key, $done, true ) ) {
			continue;
		}

		$found = get_posts( array(
			'post_type'      => 'product',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_cohf_seed_key',
			'meta_value'     => $key,
			'no_found_rows'  => true,
		) );
		if ( empty( $found ) ) {
			continue;
		}

		$done[] = $key;
		update_option( 'cohf_shop_gallery_seeded', $done, false );

		$product = wc_get_product( (int) $found[0] );
		if ( $product && empty( $product->get_gallery_image_ids() ) ) {
			$ids = cohf_shop_import_gallery( $seed );
			if ( $ids ) {
				$product->set_gallery_image_ids( $ids );
				$product->save();
			}
		}
	}
}
add_action( 'admin_init', 'cohf_shop_seed_galleries', 31 );
