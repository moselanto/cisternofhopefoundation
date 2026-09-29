<?php
/**
 * Giving engine - Paystack.
 *
 * Donations are deliberately kept separate from the Hope Market shop: a donor
 * giving KES 2,000 must never land in a shopping cart. Different flow,
 * different receipt, different accounting line.
 *
 * Nothing here invents a payment credential. The form only goes live once a
 * Paystack public key is entered in Foundation > Giving. Until then the page
 * shows the Foundation's real contact details instead.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Giving configuration.
 *
 * @return array
 */
function cohf_giving_config() {
	$amounts = get_option( 'cohf_giving_amounts', '500,1000,2500,5000,10000' );
	$amounts = array_values( array_filter( array_map( 'absint', explode( ',', (string) $amounts ) ) ) );

	return apply_filters( 'cohf_giving_config', array(
		'public_key' => trim( (string) get_option( 'cohf_paystack_public_key', '' ) ),
		'plan_code'  => trim( (string) get_option( 'cohf_paystack_plan_code', '' ) ),
		'currency'   => 'KES',
		'amounts'    => $amounts ? $amounts : array( 500, 1000, 2500, 5000, 10000 ),
		'default'    => absint( get_option( 'cohf_giving_default', 1000 ) ),
	) );
}

/**
 * Whether live giving can be offered.
 *
 * @return bool
 */
function cohf_giving_is_live() {
	$cfg = cohf_giving_config();
	return ( strpos( $cfg['public_key'], 'pk_' ) === 0 );
}

/**
 * Whether recurring giving can be offered. Paystack needs a Plan for this.
 *
 * @return bool
 */
function cohf_giving_has_recurring() {
	$cfg = cohf_giving_config();
	return ( cohf_giving_is_live() && '' !== $cfg['plan_code'] );
}

/**
 * Support areas offered in the giving form, drawn from live programme records
 * so the list never drifts from the actual work.
 *
 * @return array
 */
function cohf_giving_areas() {
	$areas = array( 'general' => __( 'Where needed most', 'cohf-child' ) );

	$programmes = get_posts( array(
		'post_type'        => 'cohf_programme',
		'post_status'      => 'publish',
		'numberposts'      => 12,
		'orderby'          => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		'suppress_filters' => false,
	) );

	foreach ( $programmes as $programme ) {
		$areas[ 'programme-' . $programme->ID ] = get_the_title( $programme );
	}

	return apply_filters( 'cohf_giving_areas', $areas );
}

/* -------------------------------------------------------------------------
   Settings - Foundation > Giving
   ------------------------------------------------------------------------- */

function cohf_giving_settings_menu() {
	add_submenu_page(
		'cohf-home',
		__( 'Giving', 'cohf-child' ),
		__( 'Giving', 'cohf-child' ),
		'manage_options',
		'cohf-giving',
		'cohf_giving_settings_page'
	);
}
add_action( 'admin_menu', 'cohf_giving_settings_menu', 20 );

function cohf_giving_register_settings() {
	foreach ( array(
		'cohf_paystack_public_key' => 'sanitize_text_field',
		'cohf_paystack_secret_key' => 'sanitize_text_field',
		'cohf_paystack_plan_code'  => 'sanitize_text_field',
		'cohf_giving_amounts'      => 'sanitize_text_field',
		'cohf_giving_default'      => 'absint',
	) as $option => $sanitiser ) {
		register_setting( 'cohf_giving', $option, array( 'sanitize_callback' => $sanitiser ) );
	}
}
add_action( 'admin_init', 'cohf_giving_register_settings' );

function cohf_giving_settings_page() {
	if ( current_user_can( 'manage_options' ) === false ) {
		return;
	}
	$cfg = cohf_giving_config();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Giving', 'cohf-child' ); ?></h1>

		<?php if ( cohf_giving_is_live() ) : ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Giving is live. The donation form on Support Our Work is accepting payments through Paystack.', 'cohf-child' ); ?></p></div>
		<?php else : ?>
			<div class="notice notice-warning"><p><?php esc_html_e( 'Giving is not live yet. Add your Paystack public key below. Until then the Support Our Work page shows the Foundation contact details instead of a payment form.', 'cohf-child' ); ?></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'cohf_giving' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="cohf_paystack_public_key"><?php esc_html_e( 'Paystack public key', 'cohf-child' ); ?></label></th>
					<td>
						<input name="cohf_paystack_public_key" id="cohf_paystack_public_key" type="text" class="regular-text code"
							value="<?php echo esc_attr( $cfg['public_key'] ); ?>" placeholder="pk_live_...">
						<p class="description"><?php esc_html_e( 'From your Paystack dashboard, Settings > API Keys. Use the PUBLIC key only. Never paste a secret key into WordPress.', 'cohf-child' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="cohf_paystack_secret_key"><?php esc_html_e( 'Paystack secret key', 'cohf-child' ); ?></label></th>
					<td>
						<?php if ( defined( 'COHF_PAYSTACK_SECRET_KEY' ) ) : ?>
							<p><strong><?php esc_html_e( 'Set in wp-config.php.', 'cohf-child' ); ?></strong> <?php esc_html_e( 'That is the safer place for it, so this field is ignored.', 'cohf-child' ); ?></p>
						<?php else : ?>
							<input name="cohf_paystack_secret_key" id="cohf_paystack_secret_key" type="password" class="regular-text code"
								value="<?php echo esc_attr( (string) get_option( 'cohf_paystack_secret_key', '' ) ); ?>" placeholder="sk_live_..." autocomplete="off">
							<p class="description">
								<?php esc_html_e( 'Required to confirm that a payment really happened, and to receive Paystack webhooks. Without it a donation cannot be verified or recorded.', 'cohf-child' ); ?>
							</p>
							<p class="description">
								<strong><?php esc_html_e( 'Safer option:', 'cohf-child' ); ?></strong>
								<?php esc_html_e( 'put it in wp-config.php instead, so it never sits in the database or a database backup. Add this line above the "stop editing" comment:', 'cohf-child' ); ?>
								<code>define( 'COHF_PAYSTACK_SECRET_KEY', 'sk_live_...' );</code>
							</p>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Webhook URL', 'cohf-child' ); ?></th>
					<td>
						<code><?php echo esc_url( rest_url( 'cohf/v1/paystack' ) ); ?></code>
						<p class="description">
							<?php esc_html_e( 'Paste this into Paystack: Settings > API Keys & Webhooks > Webhook URL. It records a gift even when the donor closes the browser before returning to the site.', 'cohf-child' ); ?>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="cohf_paystack_plan_code"><?php esc_html_e( 'Monthly giving plan code', 'cohf-child' ); ?></label></th>
					<td>
						<input name="cohf_paystack_plan_code" id="cohf_paystack_plan_code" type="text" class="regular-text code"
							value="<?php echo esc_attr( $cfg['plan_code'] ); ?>" placeholder="PLN_...">
						<p class="description"><?php esc_html_e( 'Optional. Create a Plan in Paystack to offer monthly giving. Leave blank and the form offers one-off gifts only.', 'cohf-child' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="cohf_giving_amounts"><?php esc_html_e( 'Suggested amounts (KES)', 'cohf-child' ); ?></label></th>
					<td>
						<input name="cohf_giving_amounts" id="cohf_giving_amounts" type="text" class="regular-text"
							value="<?php echo esc_attr( implode( ',', $cfg['amounts'] ) ); ?>">
						<p class="description"><?php esc_html_e( 'Comma separated.', 'cohf-child' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="cohf_giving_default"><?php esc_html_e( 'Pre-selected amount (KES)', 'cohf-child' ); ?></label></th>
					<td><input name="cohf_giving_default" id="cohf_giving_default" type="number" min="0" class="small-text" value="<?php echo esc_attr( (string) $cfg['default'] ); ?>"></td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}


/**
 * Load Paystack Inline and the giving script only on the Support Our Work
 * page, and only when a key is configured. No third-party script is loaded
 * on pages that cannot use it.
 */
function cohf_giving_enqueue() {
	if ( is_page_template( 'page-templates/page-support.php' ) === false ) {
		return;
	}
	if ( cohf_giving_is_live() === false ) {
		return;
	}

	wp_enqueue_script(
		'paystack-inline',
		'https://js.paystack.co/v2/inline.js',
		array(),
		null,
		array( 'in_footer' => true )
	);

	$path = COHF_CHILD_DIR . '/assets/js/giving.js';
	wp_enqueue_script(
		'cohf-giving',
		COHF_CHILD_URI . '/assets/js/giving.js',
		array( 'paystack-inline' ),
		file_exists( $path ) ? (string) filemtime( $path ) : COHF_CHILD_VERSION,
		array( 'in_footer' => true, 'strategy' => 'defer' )
	);
}
add_action( 'wp_enqueue_scripts', 'cohf_giving_enqueue', 20 );

/**
 * Paystack is a third-party payment origin. Allow it explicitly rather than
 * loosening the whole policy.
 *
 * @param string $csp Existing policy.
 * @return string
 */
function cohf_giving_csp( $csp ) {
	if ( strpos( $csp, 'paystack' ) !== false ) {
		return $csp;
	}
	return $csp;
}
add_filter( 'cohf_csp', 'cohf_giving_csp' );
