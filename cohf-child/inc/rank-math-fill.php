<?php
/**
 * Fill every Rank Math SEO title, description and focus keyword (13.98.0).
 *
 * Runs in WP Admin after each theme update and only fills fields that are
 * still empty, so anything typed by hand in the Rank Math box is never
 * overwritten. New products and stories added later are filled on the next
 * update. Product titles carry the price and stay in step when the price
 * changes (unless the title was edited by hand).
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'RANK_MATH_VERSION' ) ) {
	return;
}

/** Title with the brand when it fits in about 60 characters. */
function cohf_rmf_title( $title ) {
	$title = trim( html_entity_decode( wp_strip_all_tags( (string) $title ), ENT_QUOTES ) );
	$len   = mb_strlen( $title );
	if ( $len + 31 <= 62 ) {
		return $title . ' | Cistern of Hope Foundation';
	}
	if ( $len + 18 <= 64 ) {
		return $title . ' | Cistern of Hope';
	}
	return $title;
}

/** Plain text trimmed to a search-friendly length. */
function cohf_rmf_trim( $text, $max = 158 ) {
	$text = trim( preg_replace( '/\s+/', ' ', html_entity_decode( wp_strip_all_tags( strip_shortcodes( (string) $text ) ), ENT_QUOTES ) ) );
	if ( mb_strlen( $text ) > $max ) {
		$text = rtrim( mb_substr( $text, 0, $max - 3 ) );
		$text = preg_replace( '/\s+\S*$/u', '', $text ) . '...';
	}
	return $text;
}

/** Fill empty Rank Math fields on a post. */
function cohf_rmf_post( $id, $title, $desc, $kw = '' ) {
	if ( '' !== $title && '' === trim( (string) get_post_meta( $id, 'rank_math_title', true ) ) ) {
		update_post_meta( $id, 'rank_math_title', $title );
		update_post_meta( $id, '_cohf_rm_auto_title', $title );
	}
	if ( '' !== $desc && '' === trim( (string) get_post_meta( $id, 'rank_math_description', true ) ) ) {
		update_post_meta( $id, 'rank_math_description', cohf_rmf_trim( $desc ) );
	}
	if ( '' !== $kw && '' === trim( (string) get_post_meta( $id, 'rank_math_focus_keyword', true ) ) ) {
		update_post_meta( $id, 'rank_math_focus_keyword', strtolower( $kw ) );
	}
}

/** Fill empty Rank Math fields on a term. */
function cohf_rmf_term( $term_id, $title, $desc, $kw = '' ) {
	if ( '' !== $title && '' === trim( (string) get_term_meta( $term_id, 'rank_math_title', true ) ) ) {
		update_term_meta( $term_id, 'rank_math_title', $title );
	}
	if ( '' !== $desc && '' === trim( (string) get_term_meta( $term_id, 'rank_math_description', true ) ) ) {
		update_term_meta( $term_id, 'rank_math_description', cohf_rmf_trim( $desc ) );
	}
	if ( '' !== $kw && '' === trim( (string) get_term_meta( $term_id, 'rank_math_focus_keyword', true ) ) ) {
		update_term_meta( $term_id, 'rank_math_focus_keyword', strtolower( $kw ) );
	}
}

/** First phrase of a comma-separated keyword list. */
function cohf_rmf_first_kw( $list ) {
	$parts = array_map( 'trim', explode( ',', (string) $list ) );
	return isset( $parts[0] ) ? $parts[0] : '';
}

/** Product title with price. */
function cohf_rmf_product_title( $product ) {
	$price = (float) $product->get_price();
	$name  = html_entity_decode( wp_strip_all_tags( $product->get_name() ), ENT_QUOTES );
	return $price > 0 ? $name . ', KSh ' . number_format( $price ) . ' | Hope Market Kenya' : $name . ' | Hope Market Kenya';
}

/** Product description. */
function cohf_rmf_product_desc( $product ) {
	$text  = cohf_rmf_trim( $product->get_short_description() ? $product->get_short_description() : $product->get_description(), 200 );
	$price = (float) $product->get_price();
	$p     = $price > 0 ? 'KSh ' . number_format( $price ) : '';
	if ( '' === $text ) {
		$text = 'Buy ' . html_entity_decode( $product->get_name(), ENT_QUOTES ) . ', handmade in Kenya.';
	}
	if ( mb_strlen( $text ) < 100 ) {
		$long = rtrim( $text, ' .' ) . '. ' . $p . ' with delivery across Kenya. Every purchase supports Cistern of Hope Foundation.';
		$text = mb_strlen( $long ) <= 158 ? $long : rtrim( $text, ' .' ) . '. ' . $p . ', delivered across Kenya.';
	}
	return $text;
}

/** Product focus keyword: the name without size or detail in brackets. */
function cohf_rmf_product_kw( $product ) {
	$name = html_entity_decode( $product->get_name(), ENT_QUOTES );
	$name = preg_replace( '/\s*\(.*?\)\s*/', ' ', $name );
	$name = preg_replace( '/\s+-\s+.*$/', '', $name );
	return trim( $name );
}

/** Story focus keywords, by slug. */
function cohf_rmf_story_kw() {
	return array(
		'door-to-door-distribution'             => 'sanitary pads for girls',
		'enterprise-roadside-egg-business'      => 'roadside egg business',
		'enterprise-roadside-potato-trade'      => 'roadside potato trade',
		'enterprise-shoe-business'              => 'youth enterprise',
		'enterprise-tailoring-mr-owino'         => 'tailoring business',
		'enterprise-womens-vegetable-stall'     => 'women empowerment',
		'fellowship-with-orphans'               => 'supporting orphans',
		'monthly-school-sanitary-pad-donations' => 'sanitary pad donations',
		'three-boys-enrolled-in-school'         => 'street boys enrolled in school',
		'women-empowerment-seminar'             => 'women empowerment seminar',
		'enterprise-photography-dan'            => 'photography business',
	);
}

/** Category focus keywords, by slug. */
function cohf_rmf_category_kw() {
	return array(
		'jewellery'        => 'Maasai beaded jewellery',
		'sandals'          => 'beaded leather sandals',
		'clothing'         => 'Maasai dresses',
		'bags-and-baskets' => 'handmade African bags',
		'accessories'      => 'African beaded accessories',
		'home-and-kitchen' => 'handmade kitchenware',
		'home-decor'       => 'African home decor',
		'flip-flop-art'    => 'flip-flop art',
	);
}

/** Fill everything. */
function cohf_rm_fill_all() {
	$page_desc = function_exists( 'cohf_seo_page_descriptions' ) ? cohf_seo_page_descriptions() : array();
	$kw_pages  = function_exists( 'cohf_kw_pages' ) ? cohf_kw_pages() : array();

	// Home page.
	$front = (int) get_option( 'page_on_front' );
	if ( $front ) {
		cohf_rmf_post( $front, 'Cistern of Hope Foundation: NGO in Nairobi, Kenya', 'Registered NGO in Nairobi, Kenya helping orphans, needy children, widows, women and youth with school fees, sanitary pads, food and small businesses.', 'NGO in Nairobi' );
	}

	// Shop page.
	$shop = function_exists( 'wc_get_page_id' ) ? (int) wc_get_page_id( 'shop' ) : 0;
	if ( $shop > 0 ) {
		cohf_rmf_post( $shop, cohf_rmf_title( 'Hope Market: Handmade Kenyan Crafts Online' ), 'Hope Market: handmade African crafts, jewellery, baskets, sandals and home decor from Kenya. Every purchase supports the Cistern of Hope Foundation.', 'handmade Kenyan crafts' );
	}

	// Pages.
	foreach ( get_posts( array( 'post_type' => 'page', 'post_status' => 'publish', 'numberposts' => -1 ) ) as $page ) {
		if ( (int) $page->ID === $front || (int) $page->ID === $shop ) {
			continue;
		}
		$slug  = $page->post_name;
		$entry = isset( $kw_pages[ $slug ] ) ? $kw_pages[ $slug ] : null;
		$title = cohf_rmf_title( $entry ? $entry[0] : get_the_title( $page ) );
		$desc  = ( $entry && ! empty( $entry[2] ) ) ? $entry[2] : ( isset( $page_desc[ $slug ] ) ? $page_desc[ $slug ] : ( $page->post_excerpt ? $page->post_excerpt : $page->post_content ) );
		if ( '' === cohf_rmf_trim( $desc ) ) {
			$desc = get_the_title( $page ) . ' - Cistern of Hope Foundation, a registered NGO in Nairobi, Kenya.';
		}
		cohf_rmf_post( $page->ID, $title, $desc, $entry ? cohf_rmf_first_kw( $entry[1] ) : '' );
	}

	// Programmes.
	$progs = function_exists( 'cohf_kw_programmes' ) ? cohf_kw_programmes() : array();
	foreach ( get_posts( array( 'post_type' => 'cohf_programme', 'post_status' => 'publish', 'numberposts' => -1 ) ) as $p ) {
		$e = isset( $progs[ $p->post_name ] ) ? $progs[ $p->post_name ] : null;
		cohf_rmf_post(
			$p->ID,
			cohf_rmf_title( $e ? $e['title'] : get_the_title( $p ) . ' Programme in Kenya' ),
			$e ? $e['desc'] : ( $p->post_excerpt ? $p->post_excerpt : $p->post_content ),
			$e ? cohf_rmf_first_kw( $e['kw'] ) : ''
		);
	}

	// Impact stories.
	$stories = function_exists( 'cohf_kw_stories' ) ? cohf_kw_stories() : array();
	$skw     = cohf_rmf_story_kw();
	foreach ( get_posts( array( 'post_type' => 'cohf_story', 'post_status' => 'publish', 'numberposts' => -1 ) ) as $s ) {
		$e = isset( $stories[ $s->post_name ] ) ? $stories[ $s->post_name ] : null;
		cohf_rmf_post(
			$s->ID,
			cohf_rmf_title( $e ? $e[0] : get_the_title( $s ) ),
			$e ? $e[1] : ( $s->post_excerpt ? $s->post_excerpt : $s->post_content ),
			isset( $skw[ $s->post_name ] ) ? $skw[ $s->post_name ] : ''
		);
	}

	// Products.
	if ( function_exists( 'wc_get_product' ) ) {
		foreach ( get_posts( array( 'post_type' => 'product', 'post_status' => 'publish', 'numberposts' => -1, 'fields' => 'ids' ) ) as $pid ) {
			$product = wc_get_product( $pid );
			if ( $product ) {
				cohf_rmf_post( $pid, cohf_rmf_product_title( $product ), cohf_rmf_product_desc( $product ), cohf_rmf_product_kw( $product ) );
			}
		}
	}

	// Product categories.
	$cats = function_exists( 'cohf_kw_categories' ) ? cohf_kw_categories() : array();
	$ckw  = cohf_rmf_category_kw();
	$terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false ) );
	if ( is_array( $terms ) ) {
		foreach ( $terms as $t ) {
			$e = isset( $cats[ $t->slug ] ) ? $cats[ $t->slug ] : null;
			cohf_rmf_term(
				$t->term_id,
				cohf_rmf_title( $e ? $e[0] : 'Handmade ' . $t->name . ' from Kenya' ),
				$e ? $e[1] : ( $t->description ? $t->description : 'Shop handmade ' . strtolower( $t->name ) . ' at Hope Market. Delivered across Kenya; every purchase supports the Cistern of Hope Foundation.' ),
				isset( $ckw[ $t->slug ] ) ? $ckw[ $t->slug ] : ''
			);
		}
	}

	// Everything else that is public (leaders, news, events, resources, videos).
	$skip  = array( 'page', 'post', 'product', 'cohf_programme', 'cohf_story', 'attachment' );
	$types = array_diff( get_post_types( array( 'public' => true ) ), $skip );
	$types[] = 'post';
	foreach ( $types as $type ) {
		foreach ( get_posts( array( 'post_type' => $type, 'post_status' => 'publish', 'numberposts' => -1 ) ) as $item ) {
			$name = get_the_title( $item );
			if ( 'cohf_leader' === $type ) {
				$role = (string) get_post_meta( $item->ID, '_cohf_role', true );
				$name = $name . ( $role ? ', ' . $role : '' );
			}
			$desc = $item->post_excerpt ? $item->post_excerpt : $item->post_content;
			if ( '' === cohf_rmf_trim( $desc ) ) {
				$desc = $name . ' - Cistern of Hope Foundation, a registered NGO in Nairobi, Kenya serving children, women and youth.';
			}
			cohf_rmf_post( $item->ID, cohf_rmf_title( $name ), $desc );
		}
	}
}

/* Run once per theme update, in WP Admin, for administrators. */
add_action( 'admin_init', function () {
	if ( get_option( 'cohf_rm_fill_version' ) === COHF_CHILD_VERSION || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	update_option( 'cohf_rm_fill_version', COHF_CHILD_VERSION, false );
	cohf_rm_fill_all();
}, 40 );

/* Keep auto-filled product titles in step with price changes. */
add_action( 'woocommerce_update_product', function ( $product_id ) {
	static $busy = false;
	if ( $busy ) {
		return;
	}
	$current = (string) get_post_meta( $product_id, 'rank_math_title', true );
	$auto    = (string) get_post_meta( $product_id, '_cohf_rm_auto_title', true );
	if ( '' === $auto || $current !== $auto ) {
		return; // Edited by hand, or never auto-filled.
	}
	$product = wc_get_product( $product_id );
	if ( $product ) {
		$busy  = true;
		$title = cohf_rmf_product_title( $product );
		update_post_meta( $product_id, 'rank_math_title', $title );
		update_post_meta( $product_id, '_cohf_rm_auto_title', $title );
		$busy = false;
	}
} );
