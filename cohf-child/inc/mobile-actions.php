<?php
/**
 * Persistent mobile action bar.
 *
 * On a phone every way of reaching the Foundation lived behind the menu
 * button: "Support Our Work" sat at the bottom of the drawer, and the phone
 * number and address were in the footer. A visitor who wanted to give, or
 * simply to call, had to open the menu and scroll to find out how.
 *
 * This puts the four actions that matter on screen permanently: call, email,
 * partner, and give. It is the mobile equivalent of the header's Support Our
 * Work button, which is hidden below 900px to make room for the toggle.
 *
 * Rendered through wp_footer so no parent template has to be overridden.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * The actions, in order of increasing commitment.
 *
 * Any action whose destination cannot be resolved is dropped rather than
 * rendered as a dead link, so the bar adapts if a page is missing.
 *
 * @return array<int,array<string,string>>
 */
function cohf_mobile_actions() {
	$org   = function_exists( 'cohf_org' ) ? cohf_org() : array();
	$links = function_exists( 'cohf_cta_links' ) ? cohf_cta_links() : array();

	$phone = isset( $org['phone'] ) ? preg_replace( '/[^0-9+]/', '', $org['phone'] ) : '';
	$email = isset( $org['email'] ) ? $org['email'] : '';

	$partner = isset( $links['partner'] ) ? $links['partner'] : '';
	$support = isset( $links['support'] ) ? $links['support'] : '';

	if ( ! $support && function_exists( 'cohf_page_url' ) ) {
		$support = cohf_page_url( 'page-templates/page-support.php' );
	}

	if ( ! $partner && function_exists( 'cohf_page_url' ) ) {
		$partner = cohf_page_url( 'page-templates/page-partners.php' );
	}

	$actions = array(
		array(
			'key'   => 'call',
			'url'   => $phone ? 'tel:' . $phone : '',
			'label' => __( 'Call', 'cohf-child' ),
			'icon'  => '<path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11.4 11.4 0 0 0 3.6.58 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1 11.4 11.4 0 0 0 .57 3.6 1 1 0 0 1-.25 1z"/>',
		),
		array(
			'key'   => 'email',
			'url'   => $email ? 'mailto:' . $email : '',
			'label' => __( 'Email', 'cohf-child' ),
			'icon'  => '<path d="M3 5h18a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1z"/><path d="m3 7 9 6 9-6"/>',
		),
		array(
			'key'   => 'partner',
			'url'   => $partner,
			'label' => __( 'Partner', 'cohf-child' ),
			'icon'  => '<path d="M16 19v-1a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v1"/><circle cx="9" cy="7" r="3.2"/><path d="M22 19v-1a4 4 0 0 0-3-3.8"/><path d="M16.5 3.9a4 4 0 0 1 0 6.2"/>',
		),
		array(
			'key'   => 'support',
			'url'   => $support,
			'label' => __( 'Give', 'cohf-child' ),
			'icon'  => '<path d="M20.3 5.6a5 5 0 0 0-7.1 0L12 6.8l-1.2-1.2a5 5 0 1 0-7.1 7.1l1.2 1.2L12 21l7.1-7.1 1.2-1.2a5 5 0 0 0 0-7.1z"/>',
		),
	);

	return array_values( array_filter( $actions, static function ( $action ) {
		return '' !== $action['url'];
	} ) );
}

/**
 * Render the bar.
 */
function cohf_mobile_action_bar() {
	// Nothing useful to offer inside the admin frame or a feed.
	if ( is_feed() || is_embed() ) {
		return;
	}

	$actions = cohf_mobile_actions();

	if ( count( $actions ) < 2 ) {
		return;
	}

	$current = untrailingslashit( home_url( add_query_arg( array() ) ) );
	?>
	<nav class="cohf-actionbar" aria-label="<?php esc_attr_e( 'Quick actions', 'cohf-child' ); ?>">
		<?php
		foreach ( $actions as $action ) {
			$is_page    = ! preg_match( '/^(tel|mailto):/', $action['url'] );
			$is_current = $is_page && untrailingslashit( $action['url'] ) === $current;

			printf(
				'<a class="cohf-actionbar__item cohf-actionbar__item--%1$s"%5$s href="%2$s">'
					. '<svg class="cohf-actionbar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>'
					. '<span>%4$s</span>'
					. '</a>',
				esc_attr( $action['key'] ),
				esc_url( $action['url'] ),
				$action['icon'], // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static developer-authored SVG paths.
				esc_html( $action['label'] ),
				$is_current ? ' aria-current="page"' : ''
			);
		}
		?>
	</nav>
	<?php
}
add_action( 'wp_footer', 'cohf_mobile_action_bar' );
