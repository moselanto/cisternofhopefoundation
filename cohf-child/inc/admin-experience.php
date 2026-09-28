<?php
/**
 * Admin experience — the Foundation manages this site without a developer.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * A single "Foundation" dashboard page with plain-English guidance.
 */
function cohf_admin_menu() {
	add_menu_page(
		__( 'Foundation', 'cohf-child' ),
		__( 'Foundation', 'cohf-child' ),
		'edit_posts',
		'cohf-home',
		'cohf_admin_home',
		'dashicons-heart',
		20
	);

	add_submenu_page(
		'cohf-home',
		__( 'Organisation details', 'cohf-child' ),
		__( 'Organisation details', 'cohf-child' ),
		'manage_options',
		'cohf-settings',
		'cohf_admin_settings'
	);
}
add_action( 'admin_menu', 'cohf_admin_menu' );

/**
 * Dashboard guidance page.
 */
function cohf_admin_home() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_die( esc_html__( 'You do not have permission to view this page.', 'cohf-child' ) );
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Cistern of Hope Foundation — site guide', 'cohf-child' ); ?></h1>
		<p style="max-width:70ch"><?php esc_html_e( 'Everything on the website is edited from the menus on the left. You do not need a developer for day-to-day updates.', 'cohf-child' ); ?></p>

		<?php if ( current_user_can( 'manage_options' ) ) : ?>
		<div class="card" style="max-width:60rem;padding:1rem 1.25rem">
			<h2 style="margin-top:0"><?php esc_html_e( 'After installing a theme update', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'Use these after uploading a new version of the theme. Neither one overwrites anything you have edited.', 'cohf-child' ); ?></p>
			<p>
				<a class="button button-primary" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=cohf_sync_media' ), 'cohf_sync_media' ) ); ?>"><?php esc_html_e( 'Add new photographs and menu links', 'cohf-child' ); ?></a>
				<a class="button" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=cohf_run_setup' ), 'cohf_run_setup' ) ); ?>"><?php esc_html_e( 'Run site setup', 'cohf-child' ); ?></a>
			</p>
			<p class="description"><?php esc_html_e( 'Adds photographs to empty image slots and any missing links to the main menu. Run site setup creates any pages, programmes, profiles or menu items that are missing.', 'cohf-child' ); ?></p>
		</div>
		<?php endif; ?>

		<h2><?php esc_html_e( 'Where things live', 'cohf-child' ); ?></h2>
		<table class="widefat striped" style="max-width:60rem">
			<thead><tr><th><?php esc_html_e( 'To change this', 'cohf-child' ); ?></th><th><?php esc_html_e( 'Go here', 'cohf-child' ); ?></th></tr></thead>
			<tbody>
			<?php
			$rows = array(
				__( 'A programme description, activities or indicators', 'cohf-child' ) => __( 'Programmes', 'cohf-child' ),
				__( 'A new story from the field', 'cohf-child' )                        => __( 'Impact Stories', 'cohf-child' ),
				__( 'An announcement or update', 'cohf-child' )                         => __( 'News', 'cohf-child' ),
				__( 'A community programme or forum', 'cohf-child' )                    => __( 'Events', 'cohf-child' ),
				__( 'An annual or programme report (PDF)', 'cohf-child' )               => __( 'Reports', 'cohf-child' ),
				__( 'A policy or publication to download', 'cohf-child' )               => __( 'Resources', 'cohf-child' ),
				__( 'A team member profile', 'cohf-child' )                             => __( 'Leadership', 'cohf-child' ),
				__( 'A confirmed partner', 'cohf-child' )                               => __( 'Partners', 'cohf-child' ),
				__( 'Page wording (About, Impact, Contact and so on)', 'cohf-child' )   => __( 'Pages', 'cohf-child' ),
				__( 'Phone, email or postal address', 'cohf-child' )                    => __( 'Foundation → Organisation details', 'cohf-child' ),
				__( 'The menu at the top of the site', 'cohf-child' )                   => __( 'Appearance → Menus', 'cohf-child' ),
				__( 'The logo', 'cohf-child' )                                          => __( 'Appearance → Customise → Site Identity', 'cohf-child' ),
			);
			foreach ( $rows as $what => $where ) {
				printf( '<tr><td>%s</td><td><strong>%s</strong></td></tr>', esc_html( $what ), esc_html( $where ) );
			}
			?>
			</tbody>
		</table>

		<h2><?php esc_html_e( 'Publishing rules the Foundation has set for itself', 'cohf-child' ); ?></h2>
		<ul style="max-width:70ch;list-style:disc;padding-left:1.4rem">
			<li><?php esc_html_e( 'Publish only figures that appear in the Foundation\'s own records. Never estimate or round upward.', 'cohf-child' ); ?></li>
			<li><?php esc_html_e( 'Label reported figures as current programme figures. Do not present them as lifetime totals.', 'cohf-child' ); ?></li>
			<li><?php esc_html_e( 'A story or photograph of a child or vulnerable adult is published only when written, informed consent is held.', 'cohf-child' ); ?></li>
			<li><?php esc_html_e( 'Do not add a partner, donor or award that has not been confirmed in writing.', 'cohf-child' ); ?></li>
			<li><?php esc_html_e( 'Give every photograph alternative text that describes it respectfully.', 'cohf-child' ); ?></li>
		</ul>
	</div>
	<?php
}

/**
 * Organisation details settings page.
 */
function cohf_admin_settings() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to view this page.', 'cohf-child' ) );
	}

	/*
	 * Field definitions are keyed by the same keys as cohf_org_defaults(), so
	 * the screen and the data layer cannot drift apart. Previously only four
	 * of the sixteen organisation details were editable here; the rest -
	 * including the mission and vision statements - were hardcoded in PHP.
	 */
	$groups = cohf_org_field_groups();
	$fields = array();

	foreach ( $groups as $group ) {
		foreach ( $group['fields'] as $key => $field ) {
			$fields[ $key ] = $field;
		}
	}

	if ( isset( $_POST['cohf_settings_nonce'] ) ) {
		$nonce = sanitize_text_field( wp_unslash( $_POST['cohf_settings_nonce'] ) );
		if ( wp_verify_nonce( $nonce, 'cohf_save_settings' ) ) {
			$defaults = cohf_org_defaults();

			foreach ( $fields as $key => $field ) {
				$name = 'cohf_org_' . $key;

				if ( ! isset( $_POST[ $name ] ) ) {
					continue;
				}

				$raw = wp_unslash( $_POST[ $name ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised per type below.

				switch ( $field['type'] ) {
					case 'email':
						$value = sanitize_email( $raw );
						break;
					case 'textarea':
						$value = sanitize_textarea_field( $raw );
						break;
					default:
						$value = sanitize_text_field( $raw );
				}

				$value = trim( (string) $value );

				/*
				 * An emptied field means "use the theme default" rather than
				 * "publish nothing". Deleting the option restores the default
				 * instead of blanking the mission statement site-wide.
				 */
				if ( '' === $value || ( isset( $defaults[ $key ] ) && $value === $defaults[ $key ] ) ) {
					delete_option( $name );
					continue;
				}

				update_option( $name, $value );
			}

			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Organisation details saved.', 'cohf-child' ) . '</p></div>';
		}
	}

	$org = cohf_org();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Organisation details', 'cohf-child' ); ?></h1>
		<p><?php esc_html_e( 'These details appear across the site - in the header and footer, on the About, Contact and Partners pages, and in the structured data search engines read. Change them once here and every page follows.', 'cohf-child' ); ?></p>
		<p><?php esc_html_e( 'Leave a field empty to restore the original wording.', 'cohf-child' ); ?></p>
		<form method="post">
			<?php wp_nonce_field( 'cohf_save_settings', 'cohf_settings_nonce' ); ?>

			<?php foreach ( $groups as $group ) : ?>
				<h2><?php echo esc_html( $group['title'] ); ?></h2>
				<table class="form-table" role="presentation">
					<?php foreach ( $group['fields'] as $key => $field ) : ?>
						<?php $name = 'cohf_org_' . $key; ?>
						<tr>
							<th scope="row">
								<label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
							</th>
							<td>
								<?php if ( 'textarea' === $field['type'] ) : ?>
									<textarea class="large-text" rows="3" id="<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>"><?php echo esc_textarea( isset( $org[ $key ] ) ? $org[ $key ] : '' ); ?></textarea>
								<?php else : ?>
									<input type="<?php echo esc_attr( 'email' === $field['type'] ? 'email' : 'text' ); ?>" class="regular-text" id="<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( isset( $org[ $key ] ) ? $org[ $key ] : '' ); ?>">
								<?php endif; ?>
								<?php if ( ! empty( $field['hint'] ) ) : ?>
									<p class="description"><?php echo esc_html( $field['hint'] ); ?></p>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php endforeach; ?>

			<?php submit_button( __( 'Save details', 'cohf-child' ) ); ?>
		</form>
	</div>
	<?php
}

/**
 * Editable organisation fields, grouped for the settings screen.
 *
 * Keys match cohf_org_defaults(). Each option is stored as 'cohf_org_' . key,
 * which keeps the four original option names (name, address, phone, email)
 * exactly as they were, so nothing already saved is lost.
 *
 * @return array<int,array{title:string,fields:array<string,array{label:string,type:string,hint?:string}>}>
 */
function cohf_org_field_groups() {
	return array(
		array(
			'title'  => __( 'Identity', 'cohf-child' ),
			'fields' => array(
				'name'       => array(
					'label' => __( 'Organisation name', 'cohf-child' ),
					'type'  => 'text',
				),
				'abbr'       => array(
					'label' => __( 'Short name', 'cohf-child' ),
					'type'  => 'text',
					'hint'  => __( 'Used where the full name will not fit, for example COHF.', 'cohf-child' ),
				),
				'motto'      => array(
					'label' => __( 'Motto', 'cohf-child' ),
					'type'  => 'text',
					'hint'  => __( 'Appears under the logo in the header and footer.', 'cohf-child' ),
				),
				'strapline'  => array(
					'label' => __( 'Strapline', 'cohf-child' ),
					'type'  => 'text',
					'hint'  => __( 'The line in the dark utility bar at the very top of every page.', 'cohf-child' ),
				),
				'descriptor' => array(
					'label' => __( 'Short description', 'cohf-child' ),
					'type'  => 'textarea',
					'hint'  => __( 'One sentence describing the Foundation. Used in the footer and shared-link previews.', 'cohf-child' ),
				),
			),
		),
		array(
			'title'  => __( 'Vision and mission', 'cohf-child' ),
			'fields' => array(
				'vision'  => array(
					'label' => __( 'Vision statement', 'cohf-child' ),
					'type'  => 'textarea',
				),
				'mission' => array(
					'label' => __( 'Mission statement', 'cohf-child' ),
					'type'  => 'textarea',
					'hint'  => __( 'Published on the About page and in the Partners snapshot table, and read by search engines.', 'cohf-child' ),
				),
			),
		),
		array(
			'title'  => __( 'Founding and registration', 'cohf-child' ),
			'fields' => array(
				'founded'      => array(
					'label' => __( 'Year founded', 'cohf-child' ),
					'type'  => 'text',
				),
				'origin'       => array(
					'label' => __( 'Where we began', 'cohf-child' ),
					'type'  => 'text',
				),
				'registered'   => array(
					'label' => __( 'Year registered', 'cohf-child' ),
					'type'  => 'text',
				),
				'constitution' => array(
					'label' => __( 'Constitution date', 'cohf-child' ),
					'type'  => 'text',
				),
				'country'      => array(
					'label' => __( 'Country', 'cohf-child' ),
					'type'  => 'text',
				),
			),
		),
		array(
			'title'  => __( 'Contact', 'cohf-child' ),
			'fields' => array(
				'address' => array(
					'label' => __( 'Postal address', 'cohf-child' ),
					'type'  => 'text',
				),
				'phone'   => array(
					'label' => __( 'Telephone', 'cohf-child' ),
					'type'  => 'text',
				),
				'email'   => array(
					'label' => __( 'Email address', 'cohf-child' ),
					'type'  => 'email',
				),
			),
		),
	);
}

/**
 * Helpful admin columns.
 */
function cohf_programme_columns( $columns ) {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['cohf_number'] = __( 'No.', 'cohf-child' );
		}
	}
	return $new;
}
add_filter( 'manage_cohf_programme_posts_columns', 'cohf_programme_columns' );

function cohf_programme_column_content( $column, $post_id ) {
	if ( 'cohf_number' === $column ) {
		echo esc_html( cohf_field( 'number', $post_id ) );
	}
}
add_action( 'manage_cohf_programme_posts_custom_column', 'cohf_programme_column_content', 10, 2 );

/**
 * Warn if a story is published without recorded consent.
 */
function cohf_story_columns( $columns ) {
	$columns['cohf_consent'] = __( 'Consent', 'cohf-child' );
	return $columns;
}
add_filter( 'manage_cohf_story_posts_columns', 'cohf_story_columns' );

function cohf_story_column_content( $column, $post_id ) {
	if ( 'cohf_consent' !== $column ) {
		return;
	}
	if ( '1' === cohf_field( 'consent', $post_id ) ) {
		echo '<span style="color:#1d4634;font-weight:600">' . esc_html__( 'Recorded', 'cohf-child' ) . '</span>';
	} else {
		echo '<span style="color:#a9502c;font-weight:600">' . esc_html__( 'Not recorded', 'cohf-child' ) . '</span>';
	}
}
add_action( 'manage_cohf_story_posts_custom_column', 'cohf_story_column_content', 10, 2 );

/**
 * Block publishing a story when consent has not been recorded.
 *
 * @param array $data    Post data.
 * @param array $postarr Raw post array.
 * @return array
 */
function cohf_guard_story_consent( $data, $postarr ) {
	if ( 'cohf_story' !== $data['post_type'] || 'publish' !== $data['post_status'] ) {
		return $data;
	}
	$consent = isset( $postarr['cohf_consent'] ) ? '1' : cohf_field( 'consent', (int) ( $postarr['ID'] ?? 0 ) );
	if ( '1' !== $consent ) {
		$data['post_status'] = 'draft';
	}
	return $data;
}
add_filter( 'wp_insert_post_data', 'cohf_guard_story_consent', 10, 2 );

/**
 * Explain to the editor why the story stayed a draft.
 */
function cohf_consent_notice() {
	$screen = get_current_screen();
	if ( ! $screen || 'cohf_story' !== $screen->post_type || 'post' !== $screen->base ) {
		return;
	}
	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
	if ( $post_id && '1' !== cohf_field( 'consent', $post_id ) ) {
		printf(
			'<div class="notice notice-warning"><p><strong>%s</strong> %s</p></div>',
			esc_html__( 'Consent not recorded.', 'cohf-child' ),
			esc_html__( 'This story will stay as a draft until "Consent confirmed" is ticked. This protects the dignity and confidentiality of the people we serve.', 'cohf-child' )
		);
	}
}
add_action( 'admin_notices', 'cohf_consent_notice' );

/**
 * Only show confirmed partners on the front end.
 *
 * Named cohf_filter_confirmed_partners, not cohf_only_confirmed_partners.
 * The old name was also being called as a data getter from
 * template-parts/partner-section.php. Because this is a pre_get_posts
 * callback with a required $query argument, that zero-argument call raised an
 * ArgumentCountError on PHP 8 and brought the whole Partners page down with a
 * WordPress critical error. The getter now lives in inc/template-tags.php as
 * cohf_confirmed_partners().
 *
 * @param WP_Query $query Query.
 */
function cohf_filter_confirmed_partners( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->is_post_type_archive( 'cohf_partner' ) ) {
		$query->set( 'meta_key', '_cohf_confirmed' );
		$query->set( 'meta_value', '1' );
	}
}
add_action( 'pre_get_posts', 'cohf_filter_confirmed_partners' );
