<?php
/**
 * Block-editable page bodies.
 *
 * The page templates in /page-templates render their sections from hardcoded
 * PHP. That makes the design consistent but leaves roughly 3,200 words of copy
 * unreachable from the WordPress admin.
 *
 * This module lets any page opt into building its body with blocks instead,
 * without putting the existing pages at risk. The rule is deliberately strict:
 *
 *   A page renders blocks only when an editor has ticked the box AND the page
 *   actually has content. Any other state falls back to the template sections.
 *
 * That ordering matters. If the switch were content-only, every page that
 * already has a stray paragraph would silently lose its designed sections. If
 * it were checkbox-only, ticking the box on an empty page would publish a
 * blank page to the public. Requiring both means the fallback is always there
 * until someone has deliberately built a replacement.
 *
 * Existing pages are untouched: none has the meta set, so every one of them
 * renders exactly as it does today.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

const COHF_BLOCK_BODY_META = '_cohf_block_body';

/**
 * Should this page render its body from block content?
 *
 * @param int|null $post_id Page ID. Defaults to the current post.
 * @return bool
 */
function cohf_page_body_is_blocks( $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : (int) get_the_ID();

	if ( ! $post_id ) {
		return false;
	}

	if ( '1' !== (string) get_post_meta( $post_id, COHF_BLOCK_BODY_META, true ) ) {
		return false;
	}

	$post = get_post( $post_id );

	// Empty content with the box ticked would publish a blank page. Fall back.
	if ( ! $post || '' === trim( (string) $post->post_content ) ) {
		return false;
	}

	return (bool) apply_filters( 'cohf_page_body_is_blocks', true, $post_id );
}

/**
 * Render the page's block content inside the theme's section rhythm.
 *
 * Wrapped in .page-blocks so block output inherits the container width and
 * vertical spacing the hardcoded sections use, rather than running full-bleed
 * against the viewport edge.
 */
function cohf_the_page_body() {
	$post = get_post();

	if ( ! $post ) {
		return;
	}

	echo '<div class="page-blocks">';

	/*
	 * Page templates have not started the loop, so the_content() would have
	 * no post to read. Run the loop, then rewind so anything later in the
	 * template still sees the original query state.
	 */
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			the_content();
		}
		rewind_posts();
	} else {
		echo apply_filters( 'the_content', $post->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content filter escapes.
	}

	echo '</div>';
}

/**
 * Add the opt-in control to the page editor.
 */
function cohf_block_body_meta_box() {
	add_meta_box(
		'cohf-block-body',
		__( 'Page body', 'cohf-child' ),
		'cohf_block_body_meta_box_render',
		'page',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'cohf_block_body_meta_box' );

/**
 * Render the opt-in control.
 *
 * @param WP_Post $post Current page.
 */
function cohf_block_body_meta_box_render( $post ) {
	wp_nonce_field( 'cohf_block_body_save', 'cohf_block_body_nonce' );

	$on       = '1' === (string) get_post_meta( $post->ID, COHF_BLOCK_BODY_META, true );
	$template = get_page_template_slug( $post->ID );
	$has_copy = '' !== trim( (string) $post->post_content );
	?>
	<p>
		<label>
			<input type="checkbox" name="cohf_block_body" value="1" <?php checked( $on ); ?>>
			<strong><?php esc_html_e( 'Build this page with blocks', 'cohf-child' ); ?></strong>
		</label>
	</p>

	<?php if ( $template ) : ?>
		<p class="description">
			<?php esc_html_e( 'Leave this unticked to keep the designed layout that ships with the theme. Tick it to replace that layout with whatever you build in the editor below.', 'cohf-child' ); ?>
		</p>
		<?php if ( $on && ! $has_copy ) : ?>
			<p class="description" style="color:#b32d2e">
				<strong><?php esc_html_e( 'This page has no content yet.', 'cohf-child' ); ?></strong>
				<?php esc_html_e( 'The designed layout is still being shown, so the page is not blank. Add blocks below to replace it.', 'cohf-child' ); ?>
			</p>
		<?php endif; ?>

		<?php
		/*
		 * The whole point of opting in is to edit the page's real copy. Asking
		 * an editor to retype 400 words that already exist in the template
		 * would be a poor trade, so where starter content is available we hand
		 * the existing page over as blocks in one click.
		 */
		$seed = function_exists( 'cohf_page_seed_for' ) ? cohf_page_seed_for( $template ) : '';
		?>

		<?php if ( $seed && ! $has_copy ) : ?>
			<hr>
			<p>
				<a class="button button-primary" href="<?php echo esc_url( cohf_seed_page_url( $post->ID ) ); ?>">
					<?php esc_html_e( 'Copy the designed layout into blocks', 'cohf-child' ); ?>
				</a>
			</p>
			<p class="description">
				<?php esc_html_e( 'Fills the editor with this page exactly as it appears now, as editable blocks, and ticks the box above. The live partner list and enquiry form stay dynamic. Nothing is published until you update the page.', 'cohf-child' ); ?>
			</p>
		<?php elseif ( $seed && $has_copy ) : ?>
			<hr>
			<p class="description">
				<?php esc_html_e( 'Starter content is available for this template, but this page already has content, so it will not be overwritten. Empty the editor first if you want to start again from the designed layout.', 'cohf-child' ); ?>
			</p>
		<?php endif; ?>
	<?php else : ?>
		<p class="description">
			<?php esc_html_e( 'This page has no designed template, so it already renders the editor content.', 'cohf-child' ); ?>
		</p>
	<?php endif; ?>
	<?php
}

/**
 * URL for the "copy the designed layout into blocks" action.
 *
 * @param int $post_id Page ID.
 * @return string
 */
function cohf_seed_page_url( $post_id ) {
	return wp_nonce_url(
		admin_url( 'admin-post.php?action=cohf_seed_page&post=' . (int) $post_id ),
		'cohf_seed_page_' . (int) $post_id
	);
}

/**
 * Write the template's starter content into the page, then return to the editor.
 *
 * Deliberately refuses to run when the page already has content. Overwriting
 * an editor's work from a link they might click twice, or reach from browser
 * history, is not a risk worth taking for a convenience action.
 */
function cohf_seed_page_handler() {
	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;

	if ( ! $post_id ) {
		wp_die( esc_html__( 'No page specified.', 'cohf-child' ) );
	}

	check_admin_referer( 'cohf_seed_page_' . $post_id );

	if ( ! current_user_can( 'edit_page', $post_id ) ) {
		wp_die( esc_html__( 'You do not have permission to edit this page.', 'cohf-child' ) );
	}

	$post = get_post( $post_id );

	if ( ! $post || 'page' !== $post->post_type ) {
		wp_die( esc_html__( 'That is not a page.', 'cohf-child' ) );
	}

	// Never overwrite existing content.
	if ( '' !== trim( (string) $post->post_content ) ) {
		wp_safe_redirect( add_query_arg( 'cohf_seeded', 'exists', get_edit_post_link( $post_id, 'url' ) ) );
		exit;
	}

	$seed = cohf_page_seed_for( (string) get_page_template_slug( $post_id ) );

	if ( '' === $seed ) {
		wp_safe_redirect( add_query_arg( 'cohf_seeded', 'none', get_edit_post_link( $post_id, 'url' ) ) );
		exit;
	}

	wp_update_post( array(
		'ID'           => $post_id,
		'post_content' => $seed,
	) );

	update_post_meta( $post_id, COHF_BLOCK_BODY_META, '1' );

	wp_safe_redirect( add_query_arg( 'cohf_seeded', 'done', get_edit_post_link( $post_id, 'url' ) ) );
	exit;
}
add_action( 'admin_post_cohf_seed_page', 'cohf_seed_page_handler' );

/**
 * Report the outcome of the seeding action.
 */
function cohf_seed_page_notice() {
	if ( ! isset( $_GET['cohf_seeded'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only notice.
		return;
	}

	$state = sanitize_key( wp_unslash( $_GET['cohf_seeded'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only notice.

	$messages = array(
		'done'   => array( 'success', __( 'The designed layout has been copied into blocks and this page is now set to use them. Review it below, then Update to publish. Nothing has changed on the live site yet.', 'cohf-child' ) ),
		'exists' => array( 'warning', __( 'This page already has content, so nothing was copied. Empty the editor first if you want to start again from the designed layout.', 'cohf-child' ) ),
		'none'   => array( 'warning', __( 'There is no starter content for this page template yet.', 'cohf-child' ) ),
	);

	if ( ! isset( $messages[ $state ] ) ) {
		return;
	}

	printf(
		'<div class="notice notice-%1$s is-dismissible"><p>%2$s</p></div>',
		esc_attr( $messages[ $state ][0] ),
		esc_html( $messages[ $state ][1] )
	);
}
add_action( 'admin_notices', 'cohf_seed_page_notice' );

/**
 * Save the opt-in control.
 *
 * @param int $post_id Page ID.
 */
function cohf_block_body_save( $post_id ) {
	if ( ! isset( $_POST['cohf_block_body_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['cohf_block_body_nonce'] ) );

	if ( ! wp_verify_nonce( $nonce, 'cohf_block_body_save' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['cohf_block_body'] ) ) {
		update_post_meta( $post_id, COHF_BLOCK_BODY_META, '1' );
	} else {
		delete_post_meta( $post_id, COHF_BLOCK_BODY_META );
	}
}
add_action( 'save_post_page', 'cohf_block_body_save' );
