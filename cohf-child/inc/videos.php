<?php
/**
 * Video stories.
 *
 * Each Video Story holds one video: a YouTube, Vimeo or Facebook link, or a
 * file uploaded to the Media Library. Editors paste the link (or choose the
 * file) in the "Video" box on the edit screen. Nothing here hard-codes a
 * video, so the page fills only with what the Foundation publishes.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the Video box on the Video Story edit screen.
 */
function cohf_video_meta_box() {
	add_meta_box(
		'cohf_video_source',
		__( 'Video', 'cohf-child' ),
		'cohf_video_meta_box_render',
		'cohf_video',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'cohf_video_meta_box' );

/**
 * Render the Video box.
 *
 * @param WP_Post $post Current post.
 */
function cohf_video_meta_box_render( $post ) {
	wp_nonce_field( 'cohf_video_save', 'cohf_video_nonce' );
	$url = (string) get_post_meta( $post->ID, '_cohf_video_url', true );
	?>
	<p>
		<label for="cohf_video_url"><strong><?php esc_html_e( 'Video link', 'cohf-child' ); ?></strong></label><br>
		<input type="url" id="cohf_video_url" name="cohf_video_url" class="widefat" value="<?php echo esc_attr( $url ); ?>" placeholder="https://www.youtube.com/watch?v=...">
	</p>
	<p class="description">
		<?php esc_html_e( 'Paste a YouTube, Vimeo or Facebook video link. To use a video file instead, upload it under Media > Add New, copy its File URL and paste it here (MP4 works best). Use the excerpt for a one-line summary and the featured image as the cover picture.', 'cohf-child' ); ?>
	</p>
	<?php
}

/**
 * Save the video link.
 *
 * @param int $post_id Post ID.
 */
function cohf_video_save( $post_id ) {
	if ( ! isset( $_POST['cohf_video_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cohf_video_nonce'] ) ), 'cohf_video_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$url = isset( $_POST['cohf_video_url'] ) ? esc_url_raw( trim( wp_unslash( $_POST['cohf_video_url'] ) ) ) : '';
	if ( '' === $url ) {
		delete_post_meta( $post_id, '_cohf_video_url' );
	} else {
		update_post_meta( $post_id, '_cohf_video_url', $url );
	}
}
add_action( 'save_post_cohf_video', 'cohf_video_save' );

/**
 * Whether a URL points straight at a video file.
 *
 * @param string $url URL.
 * @return bool
 */
function cohf_video_is_file( $url ) {
	$path = (string) wp_parse_url( $url, PHP_URL_PATH );
	return (bool) preg_match( '/\.(mp4|m4v|webm|ogv|mov)$/i', $path );
}

/**
 * Player markup for a Video Story.
 *
 * @param int $post_id Post ID.
 * @return string Safe HTML, or '' when no playable video is set.
 */
function cohf_video_player( $post_id ) {
	$url = (string) get_post_meta( $post_id, '_cohf_video_url', true );
	if ( '' === $url ) {
		return '';
	}

	if ( cohf_video_is_file( $url ) ) {
		$poster = get_the_post_thumbnail_url( $post_id, 'large' );
		return wp_video_shortcode( array(
			'src'     => $url,
			'poster'  => $poster ? $poster : '',
			'preload' => 'none',
			'width'   => 1280,
			'height'  => 720,
		) );
	}

	$html = wp_oembed_get( $url, array( 'width' => 1280 ) );
	if ( $html ) {
		return $html;
	}

	// The provider would not embed it. Link out rather than show nothing.
	return sprintf(
		'<p class="video-story__fallback"><a class="btn dark" href="%1$s" target="_blank" rel="noopener">%2$s</a></p>',
		esc_url( $url ),
		esc_html__( 'Watch the video', 'cohf-child' )
	);
}
