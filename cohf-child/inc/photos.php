<?php
/**
 * Gallery photos as editable WordPress content.
 *
 * Each gallery photo is a "Gallery photo" post: the title is the photo's
 * title, the excerpt is its caption, the featured image is the photo, the
 * Gallery category sets its filter, and Order (Page Attributes) sets its
 * position. Add, edit, reorder or remove photos under Gallery photos in the
 * admin; no code is needed.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the post type and its category.
 */
function cohf_photos_register() {
	register_post_type( 'cohf_photo', array(
		'labels'        => array(
			'name'               => __( 'Gallery photos', 'cohf-child' ),
			'singular_name'      => __( 'Gallery photo', 'cohf-child' ),
			'add_new_item'       => __( 'Add gallery photo', 'cohf-child' ),
			'edit_item'          => __( 'Edit gallery photo', 'cohf-child' ),
			'all_items'          => __( 'All gallery photos', 'cohf-child' ),
			'featured_image'     => __( 'Photo', 'cohf-child' ),
			'set_featured_image' => __( 'Choose photo', 'cohf-child' ),
		),
		'public'        => false,
		'show_ui'       => true,
		'show_in_rest'  => true,
		'menu_icon'     => 'dashicons-format-gallery',
		'menu_position' => 22,
		'supports'      => array( 'title', 'excerpt', 'thumbnail', 'page-attributes' ),
	) );
	register_taxonomy( 'cohf_photo_cat', 'cohf_photo', array(
		'labels'            => array(
			'name'          => __( 'Gallery categories', 'cohf-child' ),
			'singular_name' => __( 'Gallery category', 'cohf-child' ),
		),
		'public'            => false,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'hierarchical'      => true,
	) );
}
add_action( 'init', 'cohf_photos_register' );

/**
 * Move the photos that shipped with the theme into Gallery photos, once.
 */
function cohf_photos_seed() {
	if ( is_admin() === false || current_user_can( 'manage_options' ) === false || get_option( 'cohf_photos_seeded' ) ) {
		return;
	}
	if ( function_exists( 'cohf_gallery_static_items' ) === false || function_exists( 'cohf_import_image' ) === false ) {
		return;
	}
	$cats = cohf_gallery_categories();
	foreach ( $cats as $slug => $name ) {
		if ( term_exists( $slug, 'cohf_photo_cat' ) === null ) {
			wp_insert_term( $name, 'cohf_photo_cat', array( 'slug' => $slug ) );
		}
	}
	foreach ( cohf_gallery_static_items() as $i => $item ) {
		$exists = get_posts( array(
			'post_type'   => 'cohf_photo',
			'post_status' => 'any',
			'meta_key'    => '_cohf_photo_key',
			'meta_value'  => $item['key'],
			'fields'      => 'ids',
			'numberposts' => 1,
		) );
		if ( $exists ) {
			continue;
		}
		$attachment = cohf_import_image( $item['key'] );
		if ( empty( $attachment ) ) {
			continue;
		}
		$post_id = wp_insert_post( array(
			'post_type'    => 'cohf_photo',
			'post_status'  => 'publish',
			'post_title'   => $item['title'],
			'post_excerpt' => $item['caption'],
			'menu_order'   => $i,
		) );
		if ( empty( $post_id ) || is_wp_error( $post_id ) ) {
			continue;
		}
		set_post_thumbnail( $post_id, $attachment );
		update_post_meta( $post_id, '_cohf_photo_key', $item['key'] );
		if ( empty( $item['video'] ) === false ) {
			update_post_meta( $post_id, '_cohf_video_url', esc_url_raw( $item['video'] ) );
		}
		wp_set_object_terms( $post_id, $item['cat'], 'cohf_photo_cat' );
	}
	update_option( 'cohf_photos_seeded', COHF_CHILD_VERSION );
}
add_action( 'admin_init', 'cohf_photos_seed', 40 );

/**
 * Published gallery photos, ready for the gallery template.
 *
 * @return array
 */
function cohf_photo_items() {
	$posts = get_posts( array(
		'post_type'        => 'cohf_photo',
		'post_status'      => 'publish',
		'numberposts'      => -1,
		'orderby'          => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
		'suppress_filters' => false,
	) );
	$items = array();
	foreach ( $posts as $post ) {
		$thumb = (int) get_post_thumbnail_id( $post );
		if ( empty( $thumb ) ) {
			continue;
		}
		$url = wp_get_attachment_image_url( $thumb, 'full' );
		if ( empty( $url ) ) {
			continue;
		}
		$terms = get_the_terms( $post, 'cohf_photo_cat' );
		$meta  = wp_get_attachment_metadata( $thumb );
		$shape = 'square';
		if ( empty( $meta['width'] ) === false && empty( $meta['height'] ) === false ) {
			$ratio = $meta['width'] / $meta['height'];
			$shape = $ratio >= 1.3 ? 'wide' : ( $ratio <= 0.85 ? 'tall' : 'square' );
		}
		$alt     = (string) get_post_meta( $thumb, '_wp_attachment_image_alt', true );
		$items[] = array(
			'url'     => $url,
			'alt'     => '' === $alt ? get_the_title( $post ) : $alt,
			'cat'     => ( $terms && is_wp_error( $terms ) === false ) ? $terms[0]->slug : 'other',
			'title'   => get_the_title( $post ),
			'caption' => (string) $post->post_excerpt,
			'shape'   => $shape,
			'video'   => (string) get_post_meta( $post->ID, '_cohf_video_url', true ),
		);
	}
	return $items;
}

add_action( 'init', function () {
	add_post_type_support( 'page', 'excerpt' );
} );

/**
 * One-time correction (12.8.1): the donated-shoes photo was captioned
 * "A shoe business". Gallery photos live in the database once seeded, so the
 * theme text change alone did not reach the live page. Only touches the
 * photo if its title is still the old one, so admin edits are respected.
 */
function cohf_photos_fix_shoe_caption() {
	if ( get_option( 'cohf_photo_fix_shoes' ) ) {
		return;
	}
	$ids = get_posts( array(
		'post_type'   => 'cohf_photo',
		'post_status' => 'any',
		'meta_key'    => '_cohf_photo_key',
		'meta_value'  => 'gallery-shoe-donation',
		'fields'      => 'ids',
		'numberposts' => 1,
	) );
	if ( $ids && 'A shoe business' === get_the_title( $ids[0] ) ) {
		wp_update_post( array(
			'ID'           => $ids[0],
			'post_title'   => __( 'Shoes for children', 'cohf-child' ),
			'post_excerpt' => __( 'Donated shoes laid out and ready to be given to children in the community.', 'cohf-child' ),
		) );
	}
	update_option( 'cohf_photo_fix_shoes', 1, false );
}
add_action( 'admin_init', 'cohf_photos_fix_shoe_caption', 41 );

/**
 * Add the newest gallery items (cohf_gallery_new_items) to a gallery that was
 * already seeded, once, at the top of the gallery. Skips any item that already
 * exists, so it is safe to run on fresh installs too.
 */
function cohf_photos_seed_new() {
	if ( get_option( 'cohf_photos_new_2026_10' ) || current_user_can( 'manage_options' ) === false ) {
		return;
	}
	if ( get_option( 'cohf_photos_seeded' ) === false || function_exists( 'cohf_gallery_new_items' ) === false || function_exists( 'cohf_import_image' ) === false ) {
		return;
	}
	if ( term_exists( 'dignity', 'cohf_photo_cat' ) === null ) {
		wp_insert_term( __( 'Menstrual dignity', 'cohf-child' ), 'cohf_photo_cat', array( 'slug' => 'dignity' ) );
	}
	$items = cohf_gallery_new_items();
	$order = -10 * count( $items );
	foreach ( $items as $item ) {
		$order += 10;
		$exists = get_posts( array(
			'post_type'   => 'cohf_photo',
			'post_status' => 'any',
			'meta_key'    => '_cohf_photo_key',
			'meta_value'  => $item['key'],
			'fields'      => 'ids',
			'numberposts' => 1,
		) );
		if ( $exists ) {
			continue;
		}
		$attachment = cohf_import_image( $item['key'] );
		if ( empty( $attachment ) ) {
			return; // Try again on the next admin page load.
		}
		$post_id = wp_insert_post( array(
			'post_type'    => 'cohf_photo',
			'post_status'  => 'publish',
			'post_title'   => $item['title'],
			'post_excerpt' => $item['caption'],
			'menu_order'   => $order - 10,
		) );
		if ( empty( $post_id ) || is_wp_error( $post_id ) ) {
			return;
		}
		set_post_thumbnail( $post_id, $attachment );
		update_post_meta( $post_id, '_cohf_photo_key', $item['key'] );
		if ( empty( $item['video'] ) === false ) {
			update_post_meta( $post_id, '_cohf_video_url', esc_url_raw( $item['video'] ) );
		}
		wp_set_object_terms( $post_id, $item['cat'], 'cohf_photo_cat' );
	}
	update_option( 'cohf_photos_new_2026_10', COHF_CHILD_VERSION, false );
}
add_action( 'admin_init', 'cohf_photos_seed_new', 42 );

/**
 * "Video (optional)" box on Gallery photos: when filled, the gallery shows the
 * photo as the video's cover with a play button and plays the video in the
 * viewer. Upload the video to the Media Library and paste its file URL.
 */
function cohf_photo_video_box() {
	add_meta_box( 'cohf-photo-video', __( 'Video (optional)', 'cohf-child' ), 'cohf_photo_video_box_render', 'cohf_photo', 'side' );
}
add_action( 'add_meta_boxes', 'cohf_photo_video_box' );

/**
 * Render the video box.
 *
 * @param WP_Post $post Gallery photo.
 */
function cohf_photo_video_box_render( $post ) {
	wp_nonce_field( 'cohf_photo_video', 'cohf_photo_video_nonce' );
	$value = (string) get_post_meta( $post->ID, '_cohf_video_url', true );
	echo '<p><label for="cohf-photo-video-url">' . esc_html__( 'Video file URL (MP4 from the Media Library). The photo becomes the video cover.', 'cohf-child' ) . '</label></p>';
	echo '<input type="url" class="widefat" id="cohf-photo-video-url" name="cohf_photo_video_url" value="' . esc_attr( $value ) . '" placeholder="https://">';
}

/**
 * Save the video box.
 *
 * @param int $post_id Gallery photo ID.
 */
function cohf_photo_video_box_save( $post_id ) {
	if ( ! isset( $_POST['cohf_photo_video_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cohf_photo_video_nonce'] ) ), 'cohf_photo_video' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$url = isset( $_POST['cohf_photo_video_url'] ) ? esc_url_raw( wp_unslash( $_POST['cohf_photo_video_url'] ) ) : '';
	if ( '' === $url ) {
		delete_post_meta( $post_id, '_cohf_video_url' );
	} else {
		update_post_meta( $post_id, '_cohf_video_url', $url );
	}
}
add_action( 'save_post_cohf_photo', 'cohf_photo_video_box_save' );
