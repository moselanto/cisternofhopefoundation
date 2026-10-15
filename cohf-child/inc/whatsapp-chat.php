<?php
/**
 * WhatsApp chat card (14.14.0).
 *
 * Replaces the plain floating WhatsApp link (14.5.0) with a branded chat
 * card: a greeting from the Foundation, topic shortcuts, a message box, and
 * a hand-off to WhatsApp with the message ready to send. Modelled on the
 * Smile Community Centre widget, with these additions:
 *
 * - Page-aware shortcut: on a story, programme, partner or Hope Market
 *   product page the first shortcut names that page ("I'd like to support
 *   Sam's journey..."), and the visitor can choose to include the page link
 *   so the team knows what they were reading.
 * - Typing indicator before the greeting (skipped with reduced motion).
 * - Unread dot and a one-per-visit teaser, never on giving, contact,
 *   cart or checkout pages.
 * - Optional reply hours (filter 'cohf_whatsapp_hours'). With none set the
 *   status line makes no promise about reply times.
 *
 * No third-party scripts, no cookies: the teaser and unread state use
 * sessionStorage only. Without JavaScript the button is a plain wa.me link.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

// The chat card supersedes the plain floating button from inc/mobile-actions.php.
remove_action( 'wp_footer', 'cohf_whatsapp_float' );

/**
 * WhatsApp number as wa.me expects it: digits only, country code included.
 *
 * @return string
 */
function cohf_whatsapp_number() {
	$org    = function_exists( 'cohf_org' ) ? cohf_org() : array();
	$source = ! empty( $org['whatsapp'] ) ? $org['whatsapp'] : ( isset( $org['phone'] ) ? $org['phone'] : '' );
	return preg_replace( '/[^0-9]/', '', (string) $source );
}

/**
 * Optional reply hours in Nairobi time.
 *
 * Empty by default because the Foundation has not published WhatsApp hours.
 * To show "Online now" / "Back at ..." add, for example:
 *
 *   add_filter( 'cohf_whatsapp_hours', function () {
 *       return array( 'open' => 8, 'close' => 17, 'days' => array( 1, 2, 3, 4, 5 ) );
 *   } );
 *
 * days: 0 = Sunday ... 6 = Saturday.
 *
 * @return array{open?:int,close?:int,days?:int[]}
 */
function cohf_whatsapp_hours() {
	$hours = apply_filters( 'cohf_whatsapp_hours', array() );
	if ( ! is_array( $hours ) || ! isset( $hours['open'], $hours['close'] ) ) {
		return array();
	}
	return array(
		'open'  => max( 0, min( 23, (int) $hours['open'] ) ),
		'close' => max( 1, min( 24, (int) $hours['close'] ) ),
		'days'  => isset( $hours['days'] ) ? array_map( 'intval', (array) $hours['days'] ) : array( 1, 2, 3, 4, 5 ),
	);
}

/**
 * Should the chat appear on this request?
 *
 * @return bool
 */
function cohf_whatsapp_chat_enabled() {
	if ( is_admin() || is_feed() || is_embed() || is_customize_preview() ) {
		return false;
	}
	if ( '' === cohf_whatsapp_number() ) {
		return false;
	}
	return (bool) apply_filters( 'cohf_whatsapp_chat_enabled', true );
}

/**
 * A shortcut and page reference for the page being viewed, if it has one.
 *
 * @return array{chip:string,title:string,url:string}
 */
function cohf_whatsapp_page_context() {
	$none = array( 'chip' => '', 'title' => '', 'url' => '' );
	if ( ! is_singular() ) {
		return $none;
	}
	$id    = (int) get_queried_object_id();
	$title = wp_strip_all_tags( get_the_title( $id ) );
	$url   = get_permalink( $id );
	if ( '' === $title || ! $url ) {
		return $none;
	}
	switch ( get_post_type( $id ) ) {
		case 'cohf_story':
			/* translators: %s: story title. */
			$chip = sprintf( __( 'I would like to help with "%s"', 'cohf-child' ), $title );
			break;
		case 'cohf_programme':
			/* translators: %s: programme name. */
			$chip = sprintf( __( 'I would like to support %s', 'cohf-child' ), $title );
			break;
		case 'cohf_partner':
			$chip = __( 'I would like to partner with you too', 'cohf-child' );
			break;
		case 'product':
			/* translators: %s: product name. */
			$chip = sprintf( __( 'I would like to order: %s', 'cohf-child' ), $title );
			break;
		default:
			return array( 'chip' => '', 'title' => $title, 'url' => $url );
	}
	return array( 'chip' => $chip, 'title' => $title, 'url' => $url );
}

/**
 * Enqueue the script only where the chat renders.
 */
function cohf_whatsapp_chat_assets() {
	if ( ! cohf_whatsapp_chat_enabled() ) {
		return;
	}
	$file = COHF_CHILD_DIR . '/assets/js/whatsapp-chat.js';
	if ( ! file_exists( $file ) ) {
		return;
	}
	wp_enqueue_script(
		'cohf-whatsapp-chat',
		COHF_CHILD_URI . '/assets/js/whatsapp-chat.js',
		array(),
		(string) filemtime( $file ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'cohf_whatsapp_chat_assets', 30 );

/**
 * Print the chat card.
 */
function cohf_whatsapp_chat() {
	if ( ! cohf_whatsapp_chat_enabled() ) {
		return;
	}

	$number  = cohf_whatsapp_number();
	$org     = function_exists( 'cohf_org' ) ? cohf_org() : array();
	$name    = ! empty( $org['name'] ) ? $org['name'] : 'Cistern of Hope Foundation';
	$hours   = cohf_whatsapp_hours();
	$context = cohf_whatsapp_page_context();
	$avatar  = COHF_CHILD_URI . '/assets/images/brand/logo-mark@2x.png';

	/* translators: %s: organisation name. */
	$default_message = sprintf( __( 'Hello %s, I would like to know more about your work.', 'cohf-child' ), $name );
	$fallback_url    = 'https://wa.me/' . $number . '?text=' . rawurlencode( $default_message );

	$topics = array(
		__( 'I would like to give a donation', 'cohf-child' ),
		__( 'I would like to partner with you', 'cohf-child' ),
		__( 'I would like to volunteer', 'cohf-child' ),
		__( 'I have a question about the Hope Market', 'cohf-child' ),
		__( 'I have a general question', 'cohf-child' ),
	);
	if ( $context['chip'] ) {
		array_unshift( $topics, $context['chip'] );
	}

	// Quiet pages: no teaser where the visitor is already giving or buying.
	$quiet = ( function_exists( 'is_cart' ) && is_cart() )
		|| ( function_exists( 'is_checkout' ) && is_checkout() )
		|| ( function_exists( 'cohf_page_url' ) && is_page() && in_array(
			get_permalink(),
			array_filter( array(
				cohf_page_url( 'page-templates/page-support.php' ),
				cohf_page_url( 'page-templates/page-contact.php' ),
			) ),
			true
		) );

	$wa_icon = '<svg viewBox="0 0 32 32" aria-hidden="true" focusable="false"><path fill="currentColor" d="M16 3.2A12.8 12.8 0 0 0 5 22.5L3.2 28.8l6.5-1.7A12.8 12.8 0 1 0 16 3.2zm0 23.3c-2 0-3.9-.5-5.5-1.5l-.4-.2-3.9 1 1-3.8-.2-.4A10.5 10.5 0 1 1 16 26.5zm5.8-7.9c-.3-.2-1.9-.9-2.2-1-.3-.1-.5-.2-.7.2l-1 1.2c-.2.2-.4.2-.7.1-.3-.2-1.4-.5-2.6-1.6-1-.9-1.6-1.9-1.8-2.2-.2-.3 0-.5.1-.7l.5-.6.3-.5c.1-.2 0-.4 0-.6l-1-2.4c-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.6.1-.9.4-.3.3-1.2 1.1-1.2 2.8s1.2 3.2 1.4 3.5c.2.2 2.4 3.6 5.7 5 .8.4 1.4.6 1.9.7.8.2 1.5.2 2.1.1.6-.1 1.9-.8 2.2-1.5.3-.7.3-1.4.2-1.5-.1-.2-.3-.3-.6-.4z"/></svg>';
	?>
	<div class="cohf-chat" data-cohf-chat
		data-number="<?php echo esc_attr( $number ); ?>"
		data-quiet="<?php echo $quiet ? '1' : '0'; ?>"
		<?php if ( $hours ) : ?>
			data-open-hour="<?php echo esc_attr( $hours['open'] ); ?>"
			data-close-hour="<?php echo esc_attr( $hours['close'] ); ?>"
			data-days="<?php echo esc_attr( implode( ',', $hours['days'] ) ); ?>"
			data-online-text="<?php esc_attr_e( 'Online now', 'cohf-child' ); ?>"
			data-offline-text="<?php esc_attr_e( 'Away now. Leave a message and we will reply when we are back.', 'cohf-child' ); ?>"
		<?php endif; ?>
		data-page-title="<?php echo esc_attr( $context['title'] ); ?>"
		data-page-url="<?php echo esc_url( $context['url'] ); ?>"
		data-page-label="<?php esc_attr_e( 'Page', 'cohf-child' ); ?>">

		<div class="cohf-chat__teaser" hidden>
			<button type="button" class="cohf-chat__teaser-close" aria-label="<?php esc_attr_e( 'Dismiss message', 'cohf-child' ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg></button>
			<button type="button" class="cohf-chat__teaser-open">
				<img src="<?php echo esc_url( $avatar ); ?>" alt="" width="36" height="36" loading="lazy" decoding="async">
				<span><strong><?php esc_html_e( 'Hello there', 'cohf-child' ); ?></strong> <?php esc_html_e( 'Questions about giving, partnering or the Hope Market? Chat with us on WhatsApp.', 'cohf-child' ); ?></span>
			</button>
		</div>

		<section class="cohf-chat__panel" id="cohf-chat-panel" role="dialog" aria-modal="false" aria-labelledby="cohf-chat-title" hidden>
			<header class="cohf-chat__head">
				<span class="cohf-chat__avatar"><img src="<?php echo esc_url( $avatar ); ?>" alt="" width="46" height="46" loading="lazy" decoding="async"><i class="cohf-chat__presence" aria-hidden="true"></i></span>
				<span class="cohf-chat__who">
					<strong id="cohf-chat-title"><?php echo esc_html( $name ); ?></strong>
					<span class="cohf-chat__status"><?php esc_html_e( 'We reply on WhatsApp as soon as we can', 'cohf-child' ); ?></span>
				</span>
				<button type="button" class="cohf-chat__close" aria-label="<?php esc_attr_e( 'Close chat', 'cohf-child' ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg></button>
			</header>

			<div class="cohf-chat__body">
				<p class="cohf-chat__day" aria-hidden="true"><span><?php esc_html_e( 'Today', 'cohf-child' ); ?></span></p>
				<div class="cohf-chat__typing" aria-hidden="true"><i></i><i></i><i></i></div>
				<div class="cohf-chat__bubble" role="status">
					<p><?php esc_html_e( 'Hello and welcome. Thank you for standing with vulnerable children, widows, women and young people in Kenya.', 'cohf-child' ); ?></p>
					<p><?php esc_html_e( 'How can we help you today?', 'cohf-child' ); ?></p>
					<span class="cohf-chat__time" aria-hidden="true"></span>
				</div>

				<p class="cohf-chat__label" id="cohf-chat-topics"><?php esc_html_e( 'Choose a topic or type your own message', 'cohf-child' ); ?></p>
				<div class="cohf-chat__chips" role="group" aria-labelledby="cohf-chat-topics">
					<?php foreach ( $topics as $i => $topic ) : ?>
						<button type="button" class="cohf-chat__chip<?php echo ( 0 === $i && $context['chip'] ) ? ' cohf-chat__chip--page' : ''; ?>" aria-pressed="false"><?php echo esc_html( $topic ); ?></button>
					<?php endforeach; ?>
				</div>
			</div>

			<form class="cohf-chat__form" action="<?php echo esc_url( 'https://wa.me/' . $number ); ?>" method="get" target="_blank" rel="noopener noreferrer">
				<?php if ( $context['url'] ) : ?>
					<label class="cohf-chat__share">
						<input type="checkbox" checked>
						<span>
							<?php
							/* translators: %s: title of the page being viewed. */
							printf( esc_html__( 'Include a link to "%s"', 'cohf-child' ), esc_html( wp_html_excerpt( $context['title'], 48, '...' ) ) );
							?>
						</span>
					</label>
				<?php endif; ?>
				<div class="cohf-chat__compose">
					<label class="screen-reader-text" for="cohf-chat-text"><?php esc_html_e( 'Your message', 'cohf-child' ); ?></label>
					<textarea id="cohf-chat-text" name="text" rows="1" maxlength="800" placeholder="<?php esc_attr_e( 'Type a message', 'cohf-child' ); ?>"></textarea>
					<button type="submit" class="cohf-chat__send" aria-label="<?php esc_attr_e( 'Send on WhatsApp', 'cohf-child' ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M3.4 20.4l17.5-7.5a1 1 0 000-1.8L3.4 3.6a.9.9 0 00-1.3 1l1.8 6.1 9 1.3-9 1.3-1.8 6.1a.9.9 0 001.3 1z"/></svg></button>
				</div>
			</form>
			<p class="cohf-chat__note"><?php esc_html_e( 'Opens WhatsApp with your message ready to send. Please do not share card or M-Pesa PINs, or private details about a child.', 'cohf-child' ); ?></p>
		</section>

		<a class="cohf-chat__fab" href="<?php echo esc_url( $fallback_url ); ?>" target="_blank" rel="noopener noreferrer" aria-controls="cohf-chat-panel" aria-expanded="false">
			<span class="cohf-chat__fab-icon"><?php echo $wa_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static developer-authored SVG. ?></span>
			<span class="cohf-chat__fab-label"><?php esc_html_e( 'Chat with us', 'cohf-child' ); ?></span>
			<span class="cohf-chat__dot" aria-hidden="true" hidden></span>
		</a>
	</div>
	<?php
}
add_action( 'wp_footer', 'cohf_whatsapp_chat', 6 );
