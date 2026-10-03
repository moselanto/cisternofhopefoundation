<?php
/**
 * Hope Market - search, sorting and price filters.
 *
 * - Live search box on the shop and category pages: results appear as you
 *   type (photo, name, category, price), with matching categories, keyboard
 *   control, recent and popular searches, and "See all results".
 * - Smarter matching for every shop search: product names, descriptions,
 *   categories, tags and SKU; plurals ("baskets" finds "basket"); everyday
 *   words ("bag" also finds clutches, purses and totes; "jewelry" finds
 *   "jewellery"); and "Did you mean" for typos ("baskit" -> "basket").
 * - Sorting labelled in plain words: Featured, Newest, Price low to high,
 *   Price high to low, Name A to Z, and Best match when searching.
 * - Price chips (Under KSh 1,000 ... Over KSh 15,000) and an active-filter
 *   row with one-tap clear.
 *
 * @package cohf-child
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
   Query understanding
   ------------------------------------------------------------------------- */

/**
 * Everyday words mapped to the words used in product names. Keys and values
 * are singular stems.
 *
 * @return array
 */
function cohf_search_synonyms() {
	return array(
		'bag'       => array( 'clutch', 'purse', 'tote', 'crossbody', 'handbag', 'kiondo' ),
		'handbag'   => array( 'bag', 'clutch', 'purse' ),
		'purse'     => array( 'clutch', 'bag' ),
		'clutch'    => array( 'purse' ),
		'basket'    => array( 'kiondo', 'sisal' ),
		'kiondo'    => array( 'basket', 'sisal' ),
		'jewelry'   => array( 'jewellery', 'necklace', 'earring', 'bangle', 'wristband' ),
		'jewellery' => array( 'necklace', 'earring', 'bangle', 'wristband' ),
		'jewel'     => array( 'jewellery', 'necklace', 'earring', 'bangle' ),
		'bracelet'  => array( 'bangle', 'wristband' ),
		'bangle'    => array( 'wristband' ),
		'shoe'      => array( 'sandal' ),
		'slipper'   => array( 'sandal' ),
		'sandal'    => array( 'shoe' ),
		'carving'   => array( 'carved' ),
		'sculpture' => array( 'carved', 'soapstone', 'clay' ),
		'wood'      => array( 'wooden', 'ebony', 'coconut' ),
		'wooden'    => array( 'wood', 'ebony' ),
		'cup'       => array( 'mug' ),
		'mug'       => array( 'cup' ),
		'painting'  => array( 'art', 'canvas' ),
		'art'       => array( 'wall art', 'painting' ),
		'decor'     => array( 'decoration' ),
		'kitchen'   => array( 'servers', 'bowl', 'mug', 'coaster', 'creamer', 'sugar' ),
		'animal'    => array( 'lion', 'rhino', 'elephant', 'buffalo', 'gazelle', 'warthog', 'turtle', 'mouse', 'toad' ),
		'maasai'    => array( 'masai' ),
		'masai'     => array( 'maasai' ),
		'beaded'    => array( 'bead' ),
		'bead'      => array( 'beaded' ),
		'kitenge'   => array( 'ankara' ),
		'ankara'    => array( 'kitenge' ),
		'gift'      => array(),
		'hat'       => array( 'fedora' ),
		'keyring'   => array( 'key chain' ),
		'keychain'  => array( 'key chain' ),
	);
}

/**
 * Singular stem of one word.
 *
 * @param string $w Lower-case word.
 * @return string
 */
function cohf_search_stem( $w ) {
	$len = strlen( $w );
	if ( $len > 4 && substr( $w, -3 ) === 'ies' ) {
		return substr( $w, 0, -3 ) . 'y';
	}
	if ( $len > 4 && preg_match( '/(ches|shes|xes|sses)$/', $w ) ) {
		return substr( $w, 0, -2 );
	}
	if ( $len > 3 && substr( $w, -1 ) === 's' && substr( $w, -2 ) !== 'ss' ) {
		return substr( $w, 0, -1 );
	}
	return $w;
}

/**
 * Words of a query, each with its alternatives.
 *
 * @param string $q Raw query.
 * @return array[] One array of alternatives per word.
 */
function cohf_search_terms( $q ) {
	$q     = strtolower( remove_accents( wp_strip_all_tags( (string) $q ) ) );
	$q     = str_replace( array( 'key chain', 'key-chain', 'flip flop', 'flip-flop' ), array( 'keychain', 'keychain', 'flipflop', 'flipflop' ), $q );
	$words = preg_split( '/[^a-z0-9]+/', $q, -1, PREG_SPLIT_NO_EMPTY );
	$stop  = array( 'the', 'a', 'an', 'and', 'or', 'for', 'of', 'with', 'in', 'on', 'to', 'my', 'some', 'ksh', 'kes' );
	$syn   = cohf_search_synonyms();
	$out   = array();

	foreach ( array_slice( $words, 0, 6 ) as $w ) {
		if ( in_array( $w, $stop, true ) || strlen( $w ) < 2 ) {
			continue;
		}
		if ( 'flipflop' === $w ) {
			$out[] = array( 'flip-flop', 'flip flop' );
			continue;
		}
		if ( 'keychain' === $w ) {
			$out[] = array( 'key chain', 'keychain', 'keyring' );
			continue;
		}
		$stem = cohf_search_stem( $w );
		$alts = array( $stem );
		if ( isset( $syn[ $stem ] ) ) {
			$alts = array_merge( $alts, $syn[ $stem ] );
		}
		$out[] = array_values( array_unique( $alts ) );
	}
	return $out;
}

/**
 * Is this a shop product search?
 *
 * @param WP_Query $query Query.
 * @return bool
 */
function cohf_is_product_search( $query ) {
	if ( $query->get( 'cohf_search' ) ) {
		return true;
	}
	if ( is_admin() || $query->is_search() === false ) {
		return false;
	}
	$pt = $query->get( 'post_type' );
	return 'product' === $pt || ( is_array( $pt ) && array( 'product' ) === $pt );
}

/**
 * Replace the WHERE part of product searches with the smarter match.
 *
 * Every word must match (AND); a word matches if it or any of its
 * alternatives appears in the name, short or long description, a category
 * or tag name, or the SKU.
 *
 * @param string   $search SQL.
 * @param WP_Query $query  Query.
 * @return string
 */
function cohf_search_where( $search, $query ) {
	if ( cohf_is_product_search( $query ) === false ) {
		return $search;
	}
	$terms = cohf_search_terms( $query->get( 's' ) );
	if ( empty( $terms ) ) {
		return $search;
	}
	global $wpdb;
	$and = array();
	foreach ( $terms as $alts ) {
		$or = array();
		foreach ( $alts as $alt ) {
			$like = '%' . $wpdb->esc_like( $alt ) . '%';
			$or[] = $wpdb->prepare( "{$wpdb->posts}.post_title LIKE %s OR {$wpdb->posts}.post_excerpt LIKE %s OR {$wpdb->posts}.post_content LIKE %s", $like, $like, $like );
			$or[] = $wpdb->prepare(
				"EXISTS ( SELECT 1 FROM {$wpdb->term_relationships} cohf_tr INNER JOIN {$wpdb->term_taxonomy} cohf_tt ON cohf_tt.term_taxonomy_id = cohf_tr.term_taxonomy_id INNER JOIN {$wpdb->terms} cohf_t ON cohf_t.term_id = cohf_tt.term_id WHERE cohf_tr.object_id = {$wpdb->posts}.ID AND cohf_tt.taxonomy IN ( 'product_cat', 'product_tag' ) AND cohf_t.name LIKE %s )",
				$like
			);
			$or[] = $wpdb->prepare( "EXISTS ( SELECT 1 FROM {$wpdb->postmeta} cohf_pm WHERE cohf_pm.post_id = {$wpdb->posts}.ID AND cohf_pm.meta_key = '_sku' AND cohf_pm.meta_value LIKE %s )", $like );
		}
		$and[] = '( ' . implode( ' OR ', $or ) . ' )';
	}
	return ' AND ( ' . implode( ' AND ', $and ) . ' ) ';
}
add_filter( 'posts_search', 'cohf_search_where', 20, 2 );

/**
 * Best match first: name matches above description-only matches.
 *
 * @param string   $orderby SQL.
 * @param WP_Query $query   Query.
 * @return string
 */
function cohf_search_orderby( $orderby, $query ) {
	if ( cohf_is_product_search( $query ) === false ) {
		return $orderby;
	}
	$requested = isset( $_GET['orderby'] ) ? sanitize_key( wp_unslash( $_GET['orderby'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( '' !== $requested && 'relevance' !== $requested && $query->get( 'cohf_search' ) === '' ) {
		return $orderby;
	}
	global $wpdb;
	$q    = trim( (string) $query->get( 's' ) );
	$full = '%' . $wpdb->esc_like( $q ) . '%';
	$case = array( $wpdb->prepare( "WHEN {$wpdb->posts}.post_title LIKE %s THEN 0", $full ) );
	$n    = 1;
	foreach ( cohf_search_terms( $q ) as $alts ) {
		$like   = '%' . $wpdb->esc_like( $alts[0] ) . '%';
		$case[] = $wpdb->prepare( "WHEN {$wpdb->posts}.post_title LIKE %s THEN %d", $like, $n );
		++$n;
	}
	$rank = '( CASE ' . implode( ' ', $case ) . ' ELSE 9 END )';
	return $rank . ' ASC, ' . "{$wpdb->posts}.menu_order ASC, {$wpdb->posts}.post_title ASC";
}
add_filter( 'posts_orderby', 'cohf_search_orderby', 20, 2 );

/* -------------------------------------------------------------------------
   Did you mean
   ------------------------------------------------------------------------- */

/**
 * Words used in product names and categories (cached, cleared on change).
 *
 * @return string[]
 */
function cohf_search_vocabulary() {
	$vocab = get_transient( 'cohf_search_vocab' );
	if ( is_array( $vocab ) ) {
		return $vocab;
	}
	$text  = array();
	$ids   = get_posts( array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 500, 'fields' => 'ids', 'no_found_rows' => true ) );
	foreach ( $ids as $id ) {
		$text[] = get_the_title( $id );
	}
	$terms = get_terms( array( 'taxonomy' => array( 'product_cat', 'product_tag' ), 'hide_empty' => true, 'fields' => 'names' ) );
	if ( is_array( $terms ) ) {
		$text = array_merge( $text, $terms );
	}
	$words = preg_split( '/[^a-z]+/', strtolower( remove_accents( implode( ' ', $text ) ) ), -1, PREG_SPLIT_NO_EMPTY );
	$vocab = array();
	foreach ( $words as $w ) {
		if ( strlen( $w ) > 2 ) {
			$vocab[ $w ] = true;
		}
	}
	$vocab = array_keys( $vocab );
	set_transient( 'cohf_search_vocab', $vocab, DAY_IN_SECONDS );
	return $vocab;
}

/**
 * Clear the vocabulary when products or categories change.
 */
function cohf_search_vocabulary_flush() {
	delete_transient( 'cohf_search_vocab' );
}
add_action( 'save_post_product', 'cohf_search_vocabulary_flush' );
add_action( 'edited_product_cat', 'cohf_search_vocabulary_flush' );
add_action( 'created_product_cat', 'cohf_search_vocabulary_flush' );

/**
 * Closest spelling of a query using product words, or '' when the query
 * already looks right.
 *
 * @param string $q Query.
 * @return string
 */
function cohf_search_suggest( $q ) {
	$vocab = cohf_search_vocabulary();
	$syn   = array_keys( cohf_search_synonyms() );
	$known = array_merge( $vocab, $syn );
	$words = preg_split( '/[^a-z0-9]+/', strtolower( remove_accents( (string) $q ) ), -1, PREG_SPLIT_NO_EMPTY );
	$out   = array();
	$fixed = false;
	foreach ( $words as $w ) {
		$stem = cohf_search_stem( $w );
		if ( strlen( $w ) < 3 || in_array( $w, $known, true ) || in_array( $stem, $known, true ) ) {
			$out[] = $w;
			continue;
		}
		$best  = '';
		$score = 99;
		foreach ( $known as $k ) {
			if ( abs( strlen( $k ) - strlen( $w ) ) > 2 ) {
				continue;
			}
			$d = levenshtein( $w, $k );
			if ( $d < $score ) {
				$score = $d;
				$best  = $k;
			}
		}
		$limit = strlen( $w ) > 6 ? 2 : 1;
		if ( '' !== $best && $score <= $limit ) {
			$out[] = $best;
			$fixed = true;
		} else {
			$out[] = $w;
		}
	}
	return $fixed ? implode( ' ', $out ) : '';
}

/* -------------------------------------------------------------------------
   Live search endpoint: ?wc-ajax=cohf_search&q=...
   ------------------------------------------------------------------------- */

/**
 * Run a product search for the live box.
 *
 * @param string $q     Query.
 * @param int    $limit Max products.
 * @return WP_Query
 */
function cohf_search_query( $q, $limit ) {
	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		's'              => $q,
		'posts_per_page' => $limit,
		'cohf_search'    => 1,
		'orderby'        => 'relevance',
	);
	if ( function_exists( 'wc_get_product_visibility_term_ids' ) ) {
		$vis     = wc_get_product_visibility_term_ids();
		$exclude = array();
		if ( ! empty( $vis['exclude-from-search'] ) ) {
			$exclude[] = $vis['exclude-from-search'];
		}
		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) && ! empty( $vis['outofstock'] ) ) {
			$exclude[] = $vis['outofstock'];
		}
		if ( $exclude ) {
			$args['tax_query'] = array( array( 'taxonomy' => 'product_visibility', 'field' => 'term_taxonomy_id', 'terms' => $exclude, 'operator' => 'NOT IN' ) ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		}
	}
	return new WP_Query( $args );
}

/**
 * JSON for the live search box.
 */
function cohf_search_ajax() {
	$q = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- public read-only search.
	$q = trim( mb_substr( $q, 0, 80 ) );

	$data = array( 'q' => $q, 'products' => array(), 'categories' => array(), 'total' => 0, 'suggest' => '', 'url' => '' );
	if ( mb_strlen( $q ) < 2 ) {
		wp_send_json( $data );
	}

	$query = cohf_search_query( $q, 6 );
	if ( 0 === (int) $query->found_posts ) {
		$suggest = cohf_search_suggest( $q );
		if ( '' !== $suggest ) {
			$data['suggest'] = $suggest;
			$query           = cohf_search_query( $suggest, 6 );
		}
	}
	$used = '' !== $data['suggest'] ? $data['suggest'] : $q;

	foreach ( $query->posts as $post ) {
		$product = wc_get_product( $post );
		if ( empty( $product ) ) {
			continue;
		}
		$cat   = '';
		$terms = get_the_terms( $product->get_id(), 'product_cat' );
		if ( is_array( $terms ) ) {
			$default = (int) get_option( 'default_product_cat', 0 );
			foreach ( $terms as $t ) {
				if ( (int) $t->term_id !== $default ) {
					$cat = $t->name;
					break;
				}
			}
		}
		$img_id             = $product->get_image_id();
		$data['products'][] = array(
			'name'     => html_entity_decode( $product->get_name(), ENT_QUOTES, 'UTF-8' ),
			'url'      => $product->get_permalink(),
			'price'    => wp_strip_all_tags( wc_price( wc_get_price_to_display( $product ) ) ),
			'image'    => $img_id ? wp_get_attachment_image_url( $img_id, 'woocommerce_gallery_thumbnail' ) : wc_placeholder_img_src( 'woocommerce_gallery_thumbnail' ),
			'category' => html_entity_decode( $cat, ENT_QUOTES, 'UTF-8' ),
			'stock'    => $product->is_in_stock(),
		);
	}
	$data['total'] = (int) $query->found_posts;

	// Categories whose name matches any word.
	$cats = function_exists( 'cohf_shop_visible_categories' ) ? cohf_shop_visible_categories() : array();
	foreach ( $cats as $term ) {
		$name = strtolower( $term->name );
		foreach ( cohf_search_terms( $used ) as $alts ) {
			foreach ( $alts as $alt ) {
				if ( false !== strpos( $name, $alt ) ) {
					$link = get_term_link( $term );
					if ( is_wp_error( $link ) === false ) {
						$data['categories'][ $term->term_id ] = array( 'name' => html_entity_decode( $term->name, ENT_QUOTES, 'UTF-8' ), 'url' => $link, 'count' => (int) $term->count );
					}
					break 2;
				}
			}
		}
	}
	$data['categories'] = array_slice( array_values( $data['categories'] ), 0, 3 );
	$data['url']        = add_query_arg( array( 's' => rawurlencode( $used ), 'post_type' => 'product' ), home_url( '/' ) );

	nocache_headers();
	wp_send_json( $data );
}
add_action( 'wc_ajax_cohf_search', 'cohf_search_ajax' );

/* -------------------------------------------------------------------------
   Sorting
   ------------------------------------------------------------------------- */

/**
 * Plain-word sort options. Popularity and rating are dropped until the shop
 * has sales and reviews to base them on.
 *
 * @param array $options Options.
 * @return array
 */
function cohf_search_orderby_options( $options ) {
	$new = array();
	if ( isset( $options['relevance'] ) ) {
		$new['relevance'] = __( 'Best match', 'cohf-child' );
	}
	$new['menu_order'] = __( 'Featured', 'cohf-child' );
	$new['date']       = __( 'Newest arrivals', 'cohf-child' );
	$new['price']      = __( 'Price: low to high', 'cohf-child' );
	$new['price-desc'] = __( 'Price: high to low', 'cohf-child' );
	$new['title']      = __( 'Name: A to Z', 'cohf-child' );
	return $new;
}
add_filter( 'woocommerce_catalog_orderby', 'cohf_search_orderby_options', 20 );

/**
 * "Name: A to Z" sorts ascending.
 *
 * @param array $args Ordering args.
 * @return array
 */
function cohf_search_title_order( $args ) {
	$requested = isset( $_GET['orderby'] ) ? sanitize_key( wp_unslash( $_GET['orderby'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( 'title' === $requested ) {
		$args['orderby'] = 'title';
		$args['order']   = 'ASC';
	}
	return $args;
}
add_filter( 'woocommerce_get_catalog_ordering_args', 'cohf_search_title_order', 20 );

/* -------------------------------------------------------------------------
   Search bar, price chips and active filters
   ------------------------------------------------------------------------- */

/**
 * Price bands for the chips.
 *
 * @return array[]
 */
function cohf_search_price_bands() {
	return array(
		array( 'min' => '', 'max' => '999', 'label' => __( 'Under KSh 1,000', 'cohf-child' ) ),
		array( 'min' => '1000', 'max' => '4999', 'label' => __( 'KSh 1,000 - 4,999', 'cohf-child' ) ),
		array( 'min' => '5000', 'max' => '14999', 'label' => __( 'KSh 5,000 - 14,999', 'cohf-child' ) ),
		array( 'min' => '15000', 'max' => '', 'label' => __( 'KSh 15,000 and over', 'cohf-child' ) ),
	);
}

/**
 * Current listing URL without paging, for building filter links.
 *
 * @return string
 */
function cohf_search_base_url() {
	$url = remove_query_arg( array( 'paged', 'product-page', 'add-to-cart' ) );
	return preg_replace( '#/page/[0-9]+/?#', '/', $url );
}

/**
 * Search bar.
 *
 * @param string $context 'toolbar' or 'empty'.
 */
function cohf_search_form( $context = 'toolbar' ) {
	$q = get_search_query();
	?>
	<div class="shop-search" data-shop-search>
		<form class="shop-search__form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="screen-reader-text" for="cohf-shop-search-<?php echo esc_attr( $context ); ?>"><?php esc_html_e( 'Search Hope Market', 'cohf-child' ); ?></label>
			<svg class="shop-search__icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
			<input id="cohf-shop-search-<?php echo esc_attr( $context ); ?>" class="shop-search__input" type="search" name="s" value="<?php echo esc_attr( $q ); ?>"
				placeholder="<?php esc_attr_e( 'Search baskets, jewellery, sandals, gifts...', 'cohf-child' ); ?>"
				autocomplete="off" autocapitalize="off" spellcheck="false" enterkeyhint="search"
				role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="cohf-search-list-<?php echo esc_attr( $context ); ?>">
			<input type="hidden" name="post_type" value="product">
			<button type="button" class="shop-search__clear" aria-label="<?php esc_attr_e( 'Clear search', 'cohf-child' ); ?>" hidden>
				<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6 6 18"/></svg>
			</button>
			<button type="submit" class="shop-search__submit"><?php esc_html_e( 'Search', 'cohf-child' ); ?></button>
		</form>
		<div class="shop-search__panel" id="cohf-search-list-<?php echo esc_attr( $context ); ?>" role="listbox" aria-label="<?php esc_attr_e( 'Search suggestions', 'cohf-child' ); ?>" hidden></div>
	</div>
	<?php
}

/**
 * Search bar above the category tiles / chips.
 */
function cohf_search_toolbar() {
	if ( is_shop() === false && is_product_taxonomy() === false ) {
		return;
	}
	cohf_search_form( 'toolbar' );
}
add_action( 'woocommerce_before_shop_loop', 'cohf_search_toolbar', 11 );

/**
 * Results heading, price chips and active filters, above the grid.
 */
function cohf_search_filters() {
	if ( is_shop() === false && is_product_taxonomy() === false ) {
		return;
	}
	global $wp_query;
	$q     = get_search_query();
	$min   = isset( $_GET['min_price'] ) ? absint( $_GET['min_price'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$max   = isset( $_GET['max_price'] ) ? absint( $_GET['max_price'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$base  = cohf_search_base_url();
	$total = (int) $wp_query->found_posts;

	if ( '' !== $q ) {
		printf(
			'<p class="shop-results-head">%s</p>',
			wp_kses_post( sprintf(
				/* translators: 1: number of results, 2: search words. */
				_n( '%1$s result for <strong>&ldquo;%2$s&rdquo;</strong>', '%1$s results for <strong>&ldquo;%2$s&rdquo;</strong>', $total, 'cohf-child' ),
				number_format_i18n( $total ),
				esc_html( $q )
			) )
		);
	}

	echo '<div class="shop-prices" role="group" aria-label="' . esc_attr__( 'Filter by price', 'cohf-child' ) . '">';
	echo '<span class="shop-prices__label">' . esc_html__( 'Price', 'cohf-child' ) . '</span>';
	printf(
		'<a class="shop-price-chip" href="%1$s"%2$s>%3$s</a>',
		esc_url( remove_query_arg( array( 'min_price', 'max_price' ), $base ) ),
		( '' === $min && '' === $max ) ? ' aria-current="true"' : '',
		esc_html__( 'Any', 'cohf-child' )
	);
	foreach ( cohf_search_price_bands() as $band ) {
		$url = remove_query_arg( array( 'min_price', 'max_price' ), $base );
		if ( '' !== $band['min'] ) {
			$url = add_query_arg( 'min_price', $band['min'], $url );
		}
		if ( '' !== $band['max'] ) {
			$url = add_query_arg( 'max_price', $band['max'], $url );
		}
		$on = ( (string) $min === $band['min'] || ( '' === $band['min'] && '' === $min ) ) && ( (string) $max === $band['max'] || ( '' === $band['max'] && '' === $max ) ) && ( '' !== $min || '' !== $max );
		printf( '<a class="shop-price-chip" href="%1$s"%2$s>%3$s</a>', esc_url( $url ), $on ? ' aria-current="true"' : '', esc_html( $band['label'] ) );
	}
	echo '</div>';

	// Active filters.
	$active = array();
	if ( '' !== $q ) {
		/* translators: %s: search words. */
		$active[] = array( sprintf( __( 'Search: %s', 'cohf-child' ), $q ), remove_query_arg( array( 's', 'post_type' ), $base ) );
	}
	if ( '' !== $min || '' !== $max ) {
		$label    = ( '' !== $min && '' !== $max ) ? cohf_shop_plain_price( $min ) . ' - ' . cohf_shop_plain_price( $max + 1 ) : ( '' !== $min ? sprintf( /* translators: %s: price. */ __( 'Over %s', 'cohf-child' ), cohf_shop_plain_price( $min ) ) : sprintf( /* translators: %s: price. */ __( 'Under %s', 'cohf-child' ), cohf_shop_plain_price( $max + 1 ) ) );
		$active[] = array( $label, remove_query_arg( array( 'min_price', 'max_price' ), $base ) );
	}
	if ( is_product_category() ) {
		$active[] = array( single_term_title( '', false ), add_query_arg( array_filter( array( 's' => $q, 'post_type' => $q ? 'product' : '', 'min_price' => $min, 'max_price' => $max ) ), $q ? home_url( '/' ) : cohf_shop_url() ) );
	}
	if ( $active ) {
		echo '<div class="shop-active">';
		foreach ( $active as $a ) {
			printf( '<a class="shop-active__chip" href="%1$s" aria-label="%2$s">%3$s<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M7 7l10 10M17 7 7 17"/></svg></a>', esc_url( $a[1] ), esc_attr( sprintf( /* translators: %s: filter. */ __( 'Remove filter: %s', 'cohf-child' ), $a[0] ) ), esc_html( $a[0] ) );
		}
		if ( count( $active ) > 1 ) {
			printf( '<a class="shop-active__clear" href="%1$s">%2$s</a>', esc_url( cohf_shop_url() ), esc_html__( 'Clear all', 'cohf-child' ) );
		}
		echo '</div>';
	}
}
add_action( 'woocommerce_before_shop_loop', 'cohf_search_filters', 16 );

/**
 * No results for a search or filter: say so plainly and offer a way on.
 * Returns true when it handled the empty state.
 *
 * @return bool
 */
function cohf_search_no_results() {
	$q   = get_search_query();
	$has = '' !== $q || isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) || is_product_taxonomy(); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( $has === false ) {
		return false;
	}
	cohf_search_form( 'empty' );
	echo '<div class="shop-noresults">';
	if ( '' !== $q ) {
		/* translators: %s: search words. */
		echo '<h2>' . esc_html( sprintf( __( 'No results for "%s"', 'cohf-child' ), $q ) ) . '</h2>';
		$suggest = cohf_search_suggest( $q );
		if ( '' !== $suggest ) {
			printf(
				'<p class="shop-noresults__suggest">%1$s <a href="%2$s">%3$s</a>?</p>',
				esc_html__( 'Did you mean', 'cohf-child' ),
				esc_url( add_query_arg( array( 's' => rawurlencode( $suggest ), 'post_type' => 'product' ), home_url( '/' ) ) ),
				esc_html( $suggest )
			);
		}
		echo '<p>' . esc_html__( 'Try a simpler word, such as "basket", "bag" or "earrings", or browse a category below.', 'cohf-child' ) . '</p>';
	} else {
		echo '<h2>' . esc_html__( 'Nothing matches these filters yet', 'cohf-child' ) . '</h2>';
		printf( '<p><a href="%1$s">%2$s</a></p>', esc_url( cohf_shop_url() ), esc_html__( 'See all of Hope Market', 'cohf-child' ) );
	}
	$cats = function_exists( 'cohf_shop_visible_categories' ) ? cohf_shop_visible_categories() : array();
	if ( $cats ) {
		echo '<ul class="shop-noresults__cats">';
		foreach ( $cats as $term ) {
			$link = get_term_link( $term );
			if ( is_wp_error( $link ) === false ) {
				printf( '<li><a href="%1$s">%2$s <span>%3$d</span></a></li>', esc_url( $link ), esc_html( $term->name ), (int) $term->count );
			}
		}
		echo '</ul>';
	}
	printf(
		'<p class="shop-noresults__help">%1$s <a href="%2$s" target="_blank" rel="noopener nofollow">%3$s</a></p>',
		esc_html__( 'Looking for something specific?', 'cohf-child' ),
		esc_url( function_exists( 'cohf_shop_wa_digits' ) && cohf_shop_wa_digits() ? 'https://wa.me/' . cohf_shop_wa_digits() . '?text=' . rawurlencode( 'Hello Hope Market team, I am looking for: ' . $q ) : cohf_shop_url() ),
		esc_html__( 'Ask us on WhatsApp', 'cohf-child' )
	);
	echo '</div>';
	return true;
}

/**
 * Live search script on shop, category and search pages.
 */
function cohf_search_assets() {
	if ( function_exists( 'is_shop' ) === false || ( is_shop() === false && is_product_taxonomy() === false ) ) {
		return;
	}
	$js = COHF_CHILD_DIR . '/assets/js/shop-search.js';
	wp_enqueue_script( 'cohf-shop-search', COHF_CHILD_URI . '/assets/js/shop-search.js', array(), file_exists( $js ) ? (string) filemtime( $js ) : COHF_CHILD_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_localize_script( 'cohf-shop-search', 'cohfSearch', array(
		'endpoint' => WC_AJAX::get_endpoint( 'cohf_search' ),
		'popular'  => array( 'Baskets', 'Jewellery', 'Sandals', 'Bags', 'Wall clock', 'Carvings' ),
		'i18n'     => array(
			'products'  => __( 'Products', 'cohf-child' ),
			'cats'      => __( 'Categories', 'cohf-child' ),
			'recent'    => __( 'Recent searches', 'cohf-child' ),
			'popular'   => __( 'Popular searches', 'cohf-child' ),
			'clear'     => __( 'Clear', 'cohf-child' ),
			/* translators: %1$s: number, %2$s: search words. */
			'seeAll'    => __( 'See all %1$s results for "%2$s"', 'cohf-child' ),
			/* translators: %s: corrected search words. */
			'showing'   => __( 'Showing results for "%s"', 'cohf-child' ),
			/* translators: %s: search words. */
			'none'      => __( 'No matches for "%s". Try another word or browse the categories.', 'cohf-child' ),
			'searching' => __( 'Searching...', 'cohf-child' ),
			'soldOut'   => __( 'Sold out', 'cohf-child' ),
			'items'     => __( 'items', 'cohf-child' ),
		),
	) );
}
add_action( 'wp_enqueue_scripts', 'cohf_search_assets', 46 );
