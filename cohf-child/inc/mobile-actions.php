<?php
/**
 * Persistent mobile action bar.
 *
 * On a phone every way of reaching the Foundation lived behind the menu
 * button: "Support Our Work" sat at the bottom of the drawer, and the phone
 * number and address were in the footer. A visitor who wanted to give, or
 * simply to call, had to open the menu and scroll to find out how.
 *
 * This puts the four actions that matter on screen permanently: home, call,
 * WhatsApp, and give. (Partner left the bar in 13.29.0; it stays in the
 * menu and on Support Our Work.) It is the mobile equivalent of the header's Support Our
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

	// wa.me takes digits only - no plus, no spaces, country code required.
	// Falls back to the telephone number when the dedicated field is blank,
	// which is the common case since most Kenyan mobile numbers carry both.
	$wa_source = ! empty( $org['whatsapp'] ) ? $org['whatsapp'] : ( isset( $org['phone'] ) ? $org['phone'] : '' );
	$wa_number = preg_replace( '/[^0-9]/', '', $wa_source );

	// A prefilled opening line. It saves the visitor composing one, and it
	// tells whoever answers which channel the enquiry came from.
	$wa_message = sprintf(
		/* translators: %s: organisation name. */
		__( 'Hello %s, I would like to know more about your work.', 'cohf-child' ),
		isset( $org['name'] ) ? $org['name'] : 'Cistern of Hope Foundation'
	);

	$whatsapp = $wa_number
		? 'https://wa.me/' . $wa_number . '?text=' . rawurlencode( $wa_message )
		: '';

	$support = isset( $links['support'] ) ? $links['support'] : '';

	if ( ! $support && function_exists( 'cohf_page_url' ) ) {
		$support = cohf_page_url( 'page-templates/page-support.php' );
	}

	$actions = array(
		// Home comes first, where thumbs and habit expect it. Visitors who land
		// deep in the site from search or WhatsApp had no obvious way back.
		array(
			'key'   => 'home',
			'url'   => home_url( '/' ),
			'label' => __( 'Home', 'cohf-child' ),
			'icon'  => '<path d="M3 10.5 12 3l9 7.5"/><path d="M5.5 9v11h13V9"/><path d="M10 20v-5.5h4V20"/>',
		),
		array(
			'key'   => 'call',
			'url'   => $phone ? 'tel:' . $phone : '',
			'label' => __( 'Call', 'cohf-child' ),
			'icon'  => '<path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11.4 11.4 0 0 0 3.6.58 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1 11.4 11.4 0 0 0 .57 3.6 1 1 0 0 1-.25 1z"/>',
		),
		array(
			'key'   => 'whatsapp',
			'url'   => $whatsapp,
			'label' => __( 'WhatsApp', 'cohf-child' ),
			// The official mark, which people scan for rather than read. It is
			// a solid glyph, so it renders filled rather than stroked.
			'fill'  => true,
			'icon'  => '<path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.87 9.87 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm0 1.67c2.2 0 4.27.86 5.83 2.42a8.2 8.2 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.25 8.24a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.18 8.18 0 0 1-1.26-4.38c0-4.54 3.7-8.25 8.25-8.25zm-3.6 4.1c-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1 0 1.23.9 2.42 1.02 2.59.13.16 1.76 2.7 4.28 3.78.6.26 1.06.41 1.42.53.6.19 1.14.16 1.57.1.48-.07 1.48-.6 1.69-1.19.2-.59.2-1.09.15-1.2-.06-.1-.23-.16-.48-.29-.25-.12-1.48-.73-1.71-.81-.23-.09-.4-.13-.56.12-.17.25-.65.81-.79.98-.15.16-.29.19-.54.06-.25-.12-1.06-.39-2.01-1.24-.74-.66-1.25-1.48-1.39-1.73-.15-.25-.02-.38.1-.51.12-.11.25-.29.38-.44.12-.14.16-.25.25-.41.08-.17.04-.31-.02-.44-.06-.12-.56-1.35-.77-1.85-.2-.48-.4-.42-.56-.42h-.2z"/>',
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
			$is_link     = ! preg_match( '/^(tel|mailto):/', $action['url'] );
			$is_internal = $is_link && 0 === strpos( $action['url'], home_url() );
			$is_current  = $is_internal && untrailingslashit( $action['url'] ) === $current;
			$is_external = $is_link && ! $is_internal;
			$filled      = ! empty( $action['fill'] );

			$attrs = $is_current ? ' aria-current="page"' : '';

			// WhatsApp hands off to the app or to web.whatsapp.com, so it
			// should not replace the page the visitor is reading.
			if ( $is_external ) {
				$attrs .= ' target="_blank" rel="noopener noreferrer"';
			}

			printf(
				'<a class="cohf-actionbar__item cohf-actionbar__item--%1$s"%5$s href="%2$s">'
					. '<span class="cohf-actionbar__ico">'
					. '<svg viewBox="0 0 24 24" %6$s aria-hidden="true" focusable="false">%3$s</svg>'
					. '</span>'
					. '<span class="cohf-actionbar__label">%4$s</span>'
					. '</a>',
				esc_attr( $action['key'] ),
				esc_url( $action['url'] ),
				$action['icon'], // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static developer-authored SVG paths.
				esc_html( $action['label'] ),
				$attrs, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- composed from fixed attribute strings above.
				$filled
					? 'fill="currentColor"'
					: 'fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"'
			);
		}
		?>
	</nav>
	<?php
}
add_action( 'wp_footer', 'cohf_mobile_action_bar' );
