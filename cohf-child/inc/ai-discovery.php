<?php
/**
 * AI and search discovery (GEO / AEO readiness).
 *
 * 1. robots.txt: names the search and AI-answer crawlers explicitly so the
 *    Foundation's choice is visible and auditable. Public pages are open to
 *    search/answer crawlers (Googlebot, Bingbot, OAI-SearchBot, ChatGPT-User,
 *    PerplexityBot, Applebot). Model-training crawlers (GPTBot, Google-Extended,
 *    CCBot, ClaudeBot) follow the default rules unless the Foundation opts out
 *    with add_filter( 'cohf_block_ai_training', '__return_true' ).
 *    Private areas (wp-admin, cart, checkout, account, WooCommerce files)
 *    stay disallowed for every crawler.
 *
 * 2. /llms.txt: a plain-text, always-current summary of the organisation and
 *    its key public pages, built from Organisation details and the published
 *    programmes. It helps AI systems find the right pages; it does not, and
 *    is not claimed to, guarantee inclusion or citation anywhere.
 *
 * Nothing here invents facts: every value comes from WordPress data.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/** Paths no crawler should fetch. */
function cohf_crawler_private_paths() {
	return array(
		'/wp-admin/',
		'/wp-login.php',
		'/cart/',
		'/checkout/',
		'/my-account/',
		'/*?add-to-cart=',
		'/*?*add-to-cart=',
		'/*?s=',
		'/wp-content/uploads/wc-logs/',
		'/wp-content/uploads/woocommerce_transient_files/',
		'/wp-content/uploads/woocommerce_uploads/',
	);
}

add_filter( 'robots_txt', function ( $output, $public ) {
	if ( ! $public ) {
		return $output; // "Discourage search engines" is on: leave WordPress's block alone.
	}

	$private = cohf_crawler_private_paths();
	$block   = '';
	foreach ( $private as $path ) {
		$block .= 'Disallow: ' . $path . "\n";
	}
	$block .= "Allow: /wp-admin/admin-ajax.php\nAllow: /\n";

	$search_bots = array( 'Googlebot', 'Bingbot', 'OAI-SearchBot', 'ChatGPT-User', 'PerplexityBot', 'Applebot' );

	$out  = "\n# Cistern of Hope Foundation - search and AI-answer crawlers (theme inc/ai-discovery.php)\n";
	$out .= '# A crawler named in its own group ignores the * group, so private paths are repeated.' . "\n";
	foreach ( $search_bots as $bot ) {
		$out .= "\nUser-agent: " . $bot . "\n" . $block;
	}

	if ( apply_filters( 'cohf_block_ai_training', false ) ) {
		foreach ( array( 'GPTBot', 'Google-Extended', 'CCBot', 'ClaudeBot', 'anthropic-ai', 'Applebot-Extended' ) as $bot ) {
			$out .= "\nUser-agent: " . $bot . "\nDisallow: /\n";
		}
	}

	$out .= "\n# Organisation summary for AI systems\n# " . home_url( '/llms.txt' ) . "\n";

	return rtrim( $output ) . "\n" . $out;
}, 99, 2 );

/**
 * Build the llms.txt body.
 *
 * @return string
 */
function cohf_llms_txt() {
	$cached = get_transient( 'cohf_llms_txt' );
	if ( is_string( $cached ) && '' !== $cached ) {
		return $cached;
	}

	$org  = function_exists( 'cohf_org' ) ? cohf_org() : array();
	$name = ! empty( $org['name'] ) ? $org['name'] : get_bloginfo( 'name' );
	$get  = static function ( $key ) use ( $org ) {
		return isset( $org[ $key ] ) ? trim( wp_strip_all_tags( (string) $org[ $key ] ) ) : '';
	};

	$lines   = array();
	$lines[] = '# ' . $name;
	$lines[] = '';
	if ( $get( 'descriptor' ) ) {
		$lines[] = '> ' . $get( 'descriptor' );
		$lines[] = '';
	}
	if ( $get( 'mission' ) ) {
		$lines[] = 'Mission: ' . $get( 'mission' );
	}
	if ( $get( 'origin' ) || $get( 'founded' ) ) {
		$lines[] = 'Founded: ' . trim( $get( 'founded' ) . ' in ' . $get( 'origin' ) . ', Kenya', ' in' );
	}
	if ( $get( 'phone' ) ) {
		$lines[] = 'Phone and WhatsApp: ' . $get( 'phone' );
	}
	if ( $get( 'email' ) ) {
		$lines[] = 'Email: ' . $get( 'email' );
	}
	if ( $get( 'address' ) ) {
		$lines[] = 'Postal address: ' . $get( 'address' );
	}
	$lines[] = 'Website: ' . home_url( '/' );
	$lines[] = '';

	$pages = array(
		'About the Foundation'        => 'page-templates/page-about.php',
		'Programmes'                  => 'page-templates/page-programmes.php',
		'Impact'                      => 'page-templates/page-impact.php',
		'Leadership and governance'   => 'page-templates/page-leadership.php',
		'Accountability and safeguarding' => 'page-templates/page-accountability.php',
		'Partners and CSR'            => 'page-templates/page-partners.php',
		'Reports and resources'       => 'page-templates/page-resources.php',
		'Volunteer and get involved'  => 'page-templates/page-get-involved.php',
		'Donate (M-Pesa or card)'     => 'page-templates/page-support.php',
		'Contact'                     => 'page-templates/page-contact.php',
		'Donation policy'             => 'page-templates/page-donation-policy.php',
	);
	$lines[] = '## Key pages';
	foreach ( $pages as $label => $template ) {
		$url = function_exists( 'cohf_page_url' ) ? cohf_page_url( $template ) : '';
		if ( $url ) {
			$lines[] = '- [' . $label . '](' . $url . ')';
		}
	}
	if ( function_exists( 'cohf_shop_url' ) && cohf_shop_url() ) {
		$lines[] = '- [Hope Market shop (handmade in Kenya)](' . cohf_shop_url() . ')';
	}
	$lines[] = '';

	$programmes = get_posts(
		array(
			'post_type'        => 'cohf_programme',
			'post_status'      => 'publish',
			'numberposts'      => 30,
			'orderby'          => 'menu_order title',
			'order'            => 'ASC',
			'suppress_filters' => false,
		)
	);
	if ( $programmes ) {
		$lines[] = '## Programmes';
		foreach ( $programmes as $programme ) {
			$summary = has_excerpt( $programme ) ? $programme->post_excerpt : wp_trim_words( $programme->post_content, 30, '...' );
			$summary = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( strip_shortcodes( $summary ) ) ) );
			$lines[] = '- [' . get_the_title( $programme ) . '](' . get_permalink( $programme ) . ')' . ( $summary ? ': ' . $summary : '' );
		}
		$lines[] = '';
	}

	$stories = get_posts(
		array(
			'post_type'        => 'cohf_story',
			'post_status'      => 'publish',
			'numberposts'      => 10,
			'suppress_filters' => false,
		)
	);
	if ( $stories ) {
		$lines[] = '## Impact stories (published with recorded consent)';
		foreach ( $stories as $story ) {
			$lines[] = '- [' . get_the_title( $story ) . '](' . get_permalink( $story ) . ')';
		}
		$lines[] = '';
	}

	$lines[] = '## Notes for AI systems';
	$lines[] = '- Figures on this site are current reported programme figures, not lifetime totals. Quote them with that qualifier and link the source page.';
	$lines[] = '- Donations are taken only on the Support Our Work page through Paystack. The Foundation never asks for donations by private message.';
	$lines[] = '- Last generated: ' . gmdate( 'Y-m-d' );

	$text = implode( "\n", $lines ) . "\n";
	set_transient( 'cohf_llms_txt', $text, 12 * HOUR_IN_SECONDS );
	return $text;
}

/* Serve /llms.txt before WordPress resolves it to a 404. */
add_action( 'parse_request', function () {
	$uri  = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_unslash( $_SERVER['REQUEST_URI'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- compared against a fixed path only.
	$path = (string) wp_parse_url( $uri, PHP_URL_PATH );
	$home = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );
	if ( untrailingslashit( $home ) . '/llms.txt' !== $path ) {
		return;
	}
	status_header( 200 );
	header( 'Content-Type: text/plain; charset=utf-8' );
	header( 'X-Robots-Tag: noindex' );
	header( 'Cache-Control: public, max-age=3600' );
	echo cohf_llms_txt(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- plain text built from stripped WordPress data.
	exit;
}, 0 );

/* Rebuild the summary when content or organisation details change. */
function cohf_llms_flush() {
	delete_transient( 'cohf_llms_txt' );
}
add_action( 'save_post_cohf_programme', 'cohf_llms_flush' );
add_action( 'save_post_cohf_story', 'cohf_llms_flush' );
add_action( 'save_post_page', 'cohf_llms_flush' );
add_action( 'updated_option', function ( $option ) {
	if ( 0 === strpos( (string) $option, 'cohf_org_' ) ) {
		cohf_llms_flush();
	}
} );
