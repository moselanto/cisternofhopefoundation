<?php
/**
 * Custom fields — native metaboxes, no plugin dependency.
 *
 * Advanced Custom Fields is optional: if ACF is active the theme still works,
 * but the Foundation is never locked into a paid plugin to edit its own site.
 *
 * Every field is sanitised on save, escaped on output, capability-checked and
 * nonce-protected.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field schema for each post type.
 *
 * Supported types: text, textarea, richtext, url, date, number, select, repeater_lines, checkbox.
 *
 * @return array
 */
function cohf_field_schema() {
	return array(

		'cohf_programme' => array(
			'title'  => __( 'Programme details', 'cohf-child' ),
			'fields' => array(
				'number'      => array( 'label' => __( 'Programme number', 'cohf-child' ), 'type' => 'number', 'hint' => __( 'Used for the 01–12 ordering shown on the programmes page.', 'cohf-child' ) ),
				'purpose'     => array( 'label' => __( 'Strategic purpose', 'cohf-child' ), 'type' => 'textarea', 'hint' => __( 'One or two sentences taken from the 2026–2030 strategic framework.', 'cohf-child' ) ),
				'what_we_do'  => array( 'label' => __( 'What we do', 'cohf-child' ), 'type' => 'repeater_lines', 'hint' => __( 'One activity per line.', 'cohf-child' ) ),
				'who_serves'  => array( 'label' => __( 'Who it serves', 'cohf-child' ), 'type' => 'textarea' ),
				'indicators'  => array( 'label' => __( 'Impact indicators (reported)', 'cohf-child' ), 'type' => 'repeater_lines', 'hint' => __( 'One indicator per line, e.g. "6 women supported to establish small businesses currently running". Leave empty if there is no verified figure. Never estimate.', 'cohf-child' ) ),
				'cta_label'   => array( 'label' => __( 'Call-to-action label', 'cohf-child' ), 'type' => 'text', 'hint' => __( 'Defaults to "Partner with us" when left empty.', 'cohf-child' ) ),
				'cta_url'     => array( 'label' => __( 'Call-to-action link', 'cohf-child' ), 'type' => 'url' ),
			),
		),

		'cohf_story' => array(
			'title'  => __( 'Story details', 'cohf-child' ),
			'fields' => array(
				'consent'      => array( 'label' => __( 'Consent confirmed', 'cohf-child' ), 'type' => 'checkbox', 'hint' => __( 'Tick only when written, informed consent is held for this story and any image. Stories without consent must not be published.', 'cohf-child' ) ),
				'anonymised'   => array( 'label' => __( 'Identity protected', 'cohf-child' ), 'type' => 'checkbox', 'hint' => __( 'Tick when names have been changed or withheld. A notice is shown to readers.', 'cohf-child' ) ),
				'location'     => array( 'label' => __( 'Location', 'cohf-child' ), 'type' => 'text' ),
				'story_date'   => array( 'label' => __( 'Date of the work described', 'cohf-child' ), 'type' => 'date' ),
				'programme_id' => array( 'label' => __( 'Related programme', 'cohf-child' ), 'type' => 'select_post', 'post_type' => 'cohf_programme' ),
				'challenge'    => array( 'label' => __( 'The challenge', 'cohf-child' ), 'type' => 'textarea' ),
				'intervention' => array( 'label' => __( 'What we did', 'cohf-child' ), 'type' => 'textarea' ),
				'change'       => array( 'label' => __( 'The change', 'cohf-child' ), 'type' => 'textarea' ),
				'quote'        => array( 'label' => __( 'Quote', 'cohf-child' ), 'type' => 'textarea', 'hint' => __( 'Use only words actually spoken and cleared for publication.', 'cohf-child' ) ),
				'quote_attr'   => array( 'label' => __( 'Quote attribution', 'cohf-child' ), 'type' => 'text', 'hint' => __( 'A role or first name only, unless full attribution is consented.', 'cohf-child' ) ),
			),
		),

		'cohf_event' => array(
			'title'  => __( 'Event details', 'cohf-child' ),
			'fields' => array(
				'start_date' => array( 'label' => __( 'Date', 'cohf-child' ), 'type' => 'date' ),
				'start_time' => array( 'label' => __( 'Start time', 'cohf-child' ), 'type' => 'text' ),
				'end_time'   => array( 'label' => __( 'End time', 'cohf-child' ), 'type' => 'text' ),
				'venue'      => array( 'label' => __( 'Venue', 'cohf-child' ), 'type' => 'text' ),
				'reached'    => array( 'label' => __( 'People reached (recorded)', 'cohf-child' ), 'type' => 'number', 'hint' => __( 'Enter only the number recorded in attendance records.', 'cohf-child' ) ),
				'activities' => array( 'label' => __( 'Activities', 'cohf-child' ), 'type' => 'repeater_lines' ),
			),
		),

		'cohf_report' => array(
			'title'  => __( 'Report details', 'cohf-child' ),
			'fields' => array(
				'file_url'    => array( 'label' => __( 'Document file', 'cohf-child' ), 'type' => 'file', 'hint' => __( 'Upload the PDF to the Media Library and paste its URL here.', 'cohf-child' ) ),
				'file_size'   => array( 'label' => __( 'File size (shown to visitors)', 'cohf-child' ), 'type' => 'text' ),
				'period'      => array( 'label' => __( 'Reporting period', 'cohf-child' ), 'type' => 'text' ),
				'published_on'=> array( 'label' => __( 'Publication date', 'cohf-child' ), 'type' => 'date' ),
			),
		),

		'cohf_resource' => array(
			'title'  => __( 'Resource details', 'cohf-child' ),
			'fields' => array(
				'file_url'  => array( 'label' => __( 'Document file', 'cohf-child' ), 'type' => 'file' ),
				'file_size' => array( 'label' => __( 'File size (shown to visitors)', 'cohf-child' ), 'type' => 'text' ),
				'external'  => array( 'label' => __( 'External link (optional)', 'cohf-child' ), 'type' => 'url' ),
			),
		),

		'cohf_leader' => array(
			'title'  => __( 'Leadership profile', 'cohf-child' ),
			'fields' => array(
				'role'        => array( 'label' => __( 'Role title', 'cohf-child' ), 'type' => 'text' ),
				'group'       => array( 'label' => __( 'Group', 'cohf-child' ), 'type' => 'select', 'options' => array(
					'executive'  => __( 'Executive Leadership', 'cohf-child' ),
					'board'      => __( 'Board of Directors', 'cohf-child' ),
					'management' => __( 'Management & Operations', 'cohf-child' ),
				) ),
				'short_bio'   => array( 'label' => __( 'Role description', 'cohf-child' ), 'type' => 'textarea' ),
			),
		),

		'cohf_partner' => array(
			'title'  => __( 'Partner details', 'cohf-child' ),
			'fields' => array(
				'confirmed'     => array( 'label' => __( 'Partnership confirmed in writing', 'cohf-child' ), 'type' => 'checkbox', 'hint' => __( 'Only confirmed partners appear on the website. Unconfirmed entries stay hidden, and their page returns "not found".', 'cohf-child' ) ),
				'status'        => array( 'label' => __( 'Current or past partner', 'cohf-child' ), 'type' => 'select', 'options' => array(
					'current' => __( 'Current partner', 'cohf-child' ),
					'past'    => __( 'Past partner', 'cohf-child' ),
				), 'hint' => __( 'Decides whether the partner is listed under "Current partners" or "Past partners". Left empty, the partner is treated as current.', 'cohf-child' ) ),
				'tagline'       => array( 'label' => __( 'Headline', 'cohf-child' ), 'type' => 'text', 'hint' => __( 'A short line about the partnership, e.g. "Our First Believer in Hope".', 'cohf-child' ) ),
				'partner_type'  => array( 'label' => __( 'Partner type', 'cohf-child' ), 'type' => 'text', 'hint' => __( 'e.g. Faith-based organisation, Foundation, Private company.', 'cohf-child' ) ),
				'period'        => array( 'label' => __( 'Partnership period', 'cohf-child' ), 'type' => 'text', 'hint' => __( 'e.g. "Since 2021" or "2022 - 2024". Leave empty if the Foundation has not confirmed the dates.', 'cohf-child' ) ),
				'achievements'  => array( 'label' => __( 'What we accomplished together', 'cohf-child' ), 'type' => 'repeater_lines', 'hint' => __( 'One accomplishment per line. Shown as a list on the partner card and the partner page. Use only what the Foundation has confirmed.', 'cohf-child' ) ),
				'figure'        => array( 'label' => __( 'Headline figure (optional)', 'cohf-child' ), 'type' => 'text', 'hint' => __( 'A single reported number from the partnership, e.g. "297". Never estimate; leave empty if there is no recorded figure.', 'cohf-child' ) ),
				'figure_label'  => array( 'label' => __( 'What the figure counts', 'cohf-child' ), 'type' => 'text', 'hint' => __( 'e.g. "children reached through this partnership".', 'cohf-child' ) ),
				'programme_id'  => array( 'label' => __( 'Related programme (optional)', 'cohf-child' ), 'type' => 'select_post', 'post_type' => 'cohf_programme' ),
				'website'       => array( 'label' => __( 'Website', 'cohf-child' ), 'type' => 'url' ),
			),
		),
	);
}

/**
 * Register metaboxes.
 */
function cohf_add_meta_boxes() {
	foreach ( cohf_field_schema() as $post_type => $box ) {
		add_meta_box(
			'cohf_fields_' . $post_type,
			$box['title'],
			'cohf_render_meta_box',
			$post_type,
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'cohf_add_meta_boxes' );

/**
 * Render a metabox.
 *
 * @param WP_Post $post Current post.
 */
function cohf_render_meta_box( $post ) {
	$schema = cohf_field_schema();
	$type   = get_post_type( $post );
	if ( empty( $schema[ $type ] ) ) {
		return;
	}

	wp_nonce_field( 'cohf_save_fields_' . $post->ID, 'cohf_fields_nonce' );

	echo '<div class="cohf-fields">';
	foreach ( $schema[ $type ]['fields'] as $key => $field ) {
		$id    = 'cohf_' . $key;
		$value = get_post_meta( $post->ID, '_cohf_' . $key, true );

		echo '<p class="cohf-field" style="margin:0 0 1.25rem">';
		printf(
			'<label for="%1$s" style="display:block;font-weight:600;margin-bottom:.35rem">%2$s</label>',
			esc_attr( $id ),
			esc_html( $field['label'] )
		);

		switch ( $field['type'] ) {
			case 'textarea':
			case 'repeater_lines':
				printf(
					'<textarea id="%1$s" name="%1$s" rows="%2$d" class="widefat">%3$s</textarea>',
					esc_attr( $id ),
					'repeater_lines' === $field['type'] ? 6 : 4,
					esc_textarea( (string) $value )
				);
				break;

			case 'checkbox':
				printf(
					'<input type="checkbox" id="%1$s" name="%1$s" value="1" %2$s> <span>%3$s</span>',
					esc_attr( $id ),
					checked( $value, '1', false ),
					esc_html__( 'Yes', 'cohf-child' )
				);
				break;

			case 'select':
				printf( '<select id="%1$s" name="%1$s" class="widefat">', esc_attr( $id ) );
				echo '<option value="">' . esc_html__( '— Select —', 'cohf-child' ) . '</option>';
				foreach ( $field['options'] as $opt_value => $opt_label ) {
					printf(
						'<option value="%1$s" %2$s>%3$s</option>',
						esc_attr( $opt_value ),
						selected( $value, $opt_value, false ),
						esc_html( $opt_label )
					);
				}
				echo '</select>';
				break;

			case 'select_post':
				$posts = get_posts( array(
					'post_type'      => $field['post_type'],
					'posts_per_page' => 50,
					'orderby'        => 'menu_order title',
					'order'          => 'ASC',
				) );
				printf( '<select id="%1$s" name="%1$s" class="widefat">', esc_attr( $id ) );
				echo '<option value="">' . esc_html__( '— None —', 'cohf-child' ) . '</option>';
				foreach ( $posts as $option ) {
					printf(
						'<option value="%1$d" %2$s>%3$s</option>',
						(int) $option->ID,
						selected( (int) $value, (int) $option->ID, false ),
						esc_html( $option->post_title )
					);
				}
				echo '</select>';
				break;

			case 'date':
				printf(
					'<input type="date" id="%1$s" name="%1$s" value="%2$s" class="widefat">',
					esc_attr( $id ),
					esc_attr( (string) $value )
				);
				break;

			case 'number':
				printf(
					'<input type="number" id="%1$s" name="%1$s" value="%2$s" class="widefat" step="1" min="0">',
					esc_attr( $id ),
					esc_attr( (string) $value )
				);
				break;

			case 'url':
			case 'file':
				printf(
					'<input type="url" id="%1$s" name="%1$s" value="%2$s" class="widefat" placeholder="https://">',
					esc_attr( $id ),
					esc_url( (string) $value )
				);
				break;

			default:
				printf(
					'<input type="text" id="%1$s" name="%1$s" value="%2$s" class="widefat">',
					esc_attr( $id ),
					esc_attr( (string) $value )
				);
		}

		if ( ! empty( $field['hint'] ) ) {
			printf( '<span class="description" style="display:block;margin-top:.35rem">%s</span>', esc_html( $field['hint'] ) );
		}
		echo '</p>';
	}
	echo '</div>';
}

/**
 * Save metabox values.
 *
 * @param int $post_id Post ID.
 */
function cohf_save_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	$nonce = isset( $_POST['cohf_fields_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['cohf_fields_nonce'] ) ) : '';
	if ( ! $nonce || ! wp_verify_nonce( $nonce, 'cohf_save_fields_' . $post_id ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$schema = cohf_field_schema();
	$type   = get_post_type( $post_id );
	if ( empty( $schema[ $type ] ) ) {
		return;
	}

	foreach ( $schema[ $type ]['fields'] as $key => $field ) {
		$input = 'cohf_' . $key;
		$meta  = '_cohf_' . $key;

		if ( 'checkbox' === $field['type'] ) {
			$value = isset( $_POST[ $input ] ) ? '1' : '';
		} elseif ( ! isset( $_POST[ $input ] ) ) {
			continue;
		} else {
			$raw = wp_unslash( $_POST[ $input ] );
			switch ( $field['type'] ) {
				case 'textarea':
				case 'repeater_lines':
					$value = sanitize_textarea_field( $raw );
					break;
				case 'url':
				case 'file':
					$value = esc_url_raw( $raw );
					break;
				case 'number':
				case 'select_post':
					$value = '' === $raw ? '' : (string) absint( $raw );
					break;
				default:
					$value = sanitize_text_field( $raw );
			}
		}

		if ( '' === $value ) {
			delete_post_meta( $post_id, $meta );
		} else {
			update_post_meta( $post_id, $meta, $value );
		}
	}
}
add_action( 'save_post', 'cohf_save_meta' );

/**
 * Read a field safely.
 *
 * @param string   $key     Field key without prefix.
 * @param int|null $post_id Post ID.
 * @return string
 */
function cohf_field( $key, $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();
	return (string) get_post_meta( $post_id, '_cohf_' . $key, true );
}

/**
 * Read a newline-separated field as an array of clean lines.
 *
 * @param string   $key     Field key.
 * @param int|null $post_id Post ID.
 * @return string[]
 */
function cohf_field_lines( $key, $post_id = null ) {
	$raw = cohf_field( $key, $post_id );
	if ( '' === $raw ) {
		return array();
	}
	$lines = preg_split( '/\r\n|\r|\n/', $raw );
	$lines = array_map( 'trim', (array) $lines );
	return array_values( array_filter( $lines, 'strlen' ) );
}
