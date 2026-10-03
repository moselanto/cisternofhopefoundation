<?php
/**
 * Hope Market promotion and entrepreneur services (14.0.0).
 *
 * 1. A slim Hope Market bar at the top of every page.
 * 2. A "Shop with purpose" product section on the home page.
 * 3. "Services you can book today" cards for Dan and Mr Owino.
 * 4. Order buttons at the end of Dan's and Mr Owino's stories.
 * 5. A "Every purchase is support" strip on the home page and programme pages.
 *
 * Orders for services go to the Foundation's WhatsApp with a ready-made message.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/** WhatsApp link with a pre-filled message. */
function cohf_hm_wa( $text ) {
	$digits = function_exists( 'cohf_shop_wa_digits' ) ? cohf_shop_wa_digits() : '';
	return $digits ? 'https://wa.me/' . $digits . '?text=' . rawurlencode( $text ) : cohf_page_url( 'page-templates/page-contact.php' );
}

/** Services offered by the entrepreneurs the Foundation supports. */
function cohf_hm_services() {
	return array(
		'dan'    => array(
			'image' => 'story-07-dan-mounting',
			'who'   => __( 'Dan - Art City', 'cohf-child' ),
			'title' => __( 'Photo editing, mounting and framing', 'cohf-child' ),
			'text'  => __( 'Photography, photo editing, photo mounting and framing for graduations, portraits and gifts.', 'cohf-child' ),
			'price' => __( 'Price on request', 'cohf-child' ),
			'wa'    => __( 'Hello Cistern of Hope Foundation, I would like to order photo editing, mounting or framing from Dan.', 'cohf-child' ),
			'story' => 'enterprise-photography-dan',
			'cta'   => __( 'Order photo framing from Dan', 'cohf-child' ),
		),
		'owino'  => array(
			'image' => 'story-06-tailoring-workshop',
			'who'   => __( 'Mr Owino - Tailor', 'cohf-child' ),
			'title' => __( 'Custom tailoring', 'cohf-child' ),
			'text'  => __( 'Shirts, African wear, suits and repairs, made to measure in his workshop.', 'cohf-child' ),
			'price' => __( 'Price on request', 'cohf-child' ),
			'wa'    => __( 'Hello Cistern of Hope Foundation, I would like to order tailoring from Mr Owino.', 'cohf-child' ),
			'story' => 'enterprise-tailoring-mr-owino',
			'cta'   => __( 'Order tailoring from Mr Owino', 'cohf-child' ),
		),
	);
}

/* 1. Top bar on every page. */
add_action( 'wp_body_open', function () {
	if ( is_admin() || ! function_exists( 'cohf_shop_url' ) || '' === cohf_shop_url() ) {
		return;
	}
	if ( function_exists( 'is_woocommerce' ) && ( is_cart() || is_checkout() ) ) {
		return;
	}
	printf(
		'<div class="hm-bar" role="region" aria-label="%1$s"><div class="container hm-bar__in"><span class="hm-bar__text">%2$s</span><a class="hm-bar__btn" href="%3$s">%4$s</a></div></div>',
		esc_attr__( 'Hope Market', 'cohf-child' ),
		esc_html__( 'Shop Hope Market: handmade Kenyan clothes, crafts and decor. Every purchase supports our work.', 'cohf-child' ),
		esc_url( cohf_shop_url() ),
		esc_html__( 'Shop now', 'cohf-child' )
	);
}, 5 );

/* 2. Home page product section. */
function cohf_hm_market_section() {
	if ( ! function_exists( 'cohf_shop_url' ) || '' === cohf_shop_url() ) {
		return;
	}
	?>
	<section class="cream hm-market" aria-labelledby="hm-market-title">
		<div class="container">
			<div class="hm-head">
				<div class="sec-label"><span class="sec-label__rule"></span><span class="sec-label__text"><?php esc_html_e( 'Hope Market', 'cohf-child' ); ?></span></div>
				<h2 id="hm-market-title" class="sec-statement sec-statement--wide"><?php esc_html_e( 'Shop with purpose.', 'cohf-child' ); ?></h2>
				<p><?php esc_html_e( 'Handmade in Kenya by the women and youth we support. Every purchase funds our programmes.', 'cohf-child' ); ?></p>
			</div>
			<?php echo do_shortcode( '[products limit="4" columns="4" orderby="rand" visibility="visible"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<p class="hm-center"><a class="btn hm-btn-gold" href="<?php echo esc_url( cohf_shop_url() ); ?>"><?php esc_html_e( 'Visit Hope Market', 'cohf-child' ); ?></a></p>
		</div>
	</section>
	<?php
}

/* 3. Services cards. */
function cohf_hm_services_section() {
	?>
	<section class="sage hm-services" aria-labelledby="hm-services-title">
		<div class="container">
			<div class="hm-head">
				<div class="sec-label"><span class="sec-label__rule"></span><span class="sec-label__text"><?php esc_html_e( 'Hire our entrepreneurs', 'cohf-child' ); ?></span></div>
				<h2 id="hm-services-title" class="sec-statement sec-statement--wide"><?php esc_html_e( 'Services you can book today.', 'cohf-child' ); ?></h2>
				<p><?php esc_html_e( 'Order directly from the small businesses we walk alongside. Your order is their growth.', 'cohf-child' ); ?></p>
			</div>
			<div class="hm-svc-grid">
				<?php foreach ( cohf_hm_services() as $svc ) : ?>
					<article class="hm-svc">
						<div class="hm-svc__img"><?php cohf_the_image( $svc['image'], array( 'sizes' => '(max-width: 48em) 100vw, 260px' ) ); ?></div>
						<div class="hm-svc__body">
							<span class="hm-svc__who"><?php echo esc_html( $svc['who'] ); ?></span>
							<h3><?php echo esc_html( $svc['title'] ); ?></h3>
							<p><?php echo esc_html( $svc['text'] ); ?></p>
							<strong class="hm-svc__price"><?php echo esc_html( $svc['price'] ); ?></strong>
							<a class="btn hm-btn-wa" href="<?php echo esc_url( cohf_hm_wa( $svc['wa'] ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Order on WhatsApp', 'cohf-child' ); ?></a>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/* 5. Shop-with-purpose strip. */
function cohf_hm_purpose_strip() {
	if ( ! function_exists( 'cohf_shop_url' ) || '' === cohf_shop_url() ) {
		return;
	}
	?>
	<section class="hm-strip">
		<div class="container hm-strip__in">
			<div>
				<h2><?php esc_html_e( 'Every purchase is support.', 'cohf-child' ); ?></h2>
				<p><?php esc_html_e( 'Hope Market sales fund women\'s and youth enterprise, school fees and monthly sanitary pads.', 'cohf-child' ); ?></p>
			</div>
			<a class="btn hm-btn-gold" href="<?php echo esc_url( cohf_shop_url() ); ?>"><?php esc_html_e( 'Shop Hope Market', 'cohf-child' ); ?></a>
		</div>
	</section>
	<?php
}

/* 4. Order box at the end of an entrepreneur's story. */
function cohf_hm_story_cta() {
	$slug = get_post_field( 'post_name', get_the_ID() );
	foreach ( cohf_hm_services() as $key => $svc ) {
		if ( $svc['story'] !== $slug ) {
			continue;
		}
		$give = cohf_page_url( 'page-templates/page-support.php' );
		?>
		<aside class="hm-story-cta">
			<h2><?php echo esc_html( 'dan' === $key ? __( 'Support Dan\'s business', 'cohf-child' ) : __( 'Support Mr Owino\'s business', 'cohf-child' ) ); ?></h2>
			<p><?php esc_html_e( 'The best way to help a small business grow is to become a customer. Place an order, or give towards the next step of the business.', 'cohf-child' ); ?></p>
			<div class="hm-story-cta__btns">
				<a class="btn hm-btn-wa" href="<?php echo esc_url( cohf_hm_wa( $svc['wa'] ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $svc['cta'] ); ?></a>
				<?php if ( $give ) : ?>
					<a class="btn hm-btn-gold" href="<?php echo esc_url( $give ); ?>"><?php echo esc_html( 'dan' === $key ? __( 'Give towards his studio', 'cohf-child' ) : __( 'Give to our enterprise work', 'cohf-child' ) ); ?></a>
				<?php endif; ?>
			</div>
		</aside>
		<?php
	}
}

/* Styles. */
add_action( 'wp_enqueue_scripts', function () {
	$css = '.hm-bar{background:#d9a441;color:#17231e;font-size:14px;font-weight:700}'
		. '.hm-bar__in{display:flex;align-items:center;justify-content:center;gap:14px;padding:8px 0;flex-wrap:wrap;text-align:center}'
		. '.hm-bar__btn{background:#163f32;color:#fff;border-radius:999px;padding:6px 14px;font-weight:800;white-space:nowrap}'
		. '.hm-bar__btn:hover{background:#0f2c23;color:#fff}'
		. '.hm-head{text-align:center;max-width:760px;margin:0 auto 34px}.hm-head .sec-label{justify-content:center}.hm-head p{color:#66736d}'
		. '.hm-center{text-align:center;margin-top:28px}'
		. '.hm-btn-gold{background:#d9a441;color:#17231e}.hm-btn-gold:hover{background:#c8932f;color:#17231e}'
		. '.hm-btn-wa{background:#25a05a;color:#fff}.hm-btn-wa:hover{background:#1e8a4c;color:#fff}'
		. '.hm-svc-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:28px}'
		. '.hm-svc{display:grid;grid-template-columns:240px 1fr;background:#fff;border-radius:16px;overflow:hidden}'
		. '.hm-svc__img img{width:100%;height:100%;object-fit:cover;display:block;min-height:300px}'
		. '.hm-svc__body{padding:24px 24px 26px;display:flex;flex-direction:column;gap:8px;align-items:flex-start}'
		. '.hm-svc__who{color:#b86f52;font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}'
		. '.hm-svc h3{margin:0;font-size:22px}.hm-svc p{color:#66736d;margin:0}.hm-svc__price{color:#163f32;font-size:18px;margin-top:auto}'
		. '.hm-strip{background:#163f32;color:#fff;padding:44px 0}'
		. '.hm-strip__in{display:flex;align-items:center;justify-content:space-between;gap:24px;flex-wrap:wrap}'
		. '.hm-strip h2{color:#fff;margin:0 0 6px;font-size:clamp(26px,3vw,34px)}.hm-strip p{color:#d2e1d7;margin:0}'
		. '.hm-story-cta{background:#f7f3ea;border:1px solid #dfe6e1;border-radius:16px;padding:28px;margin:36px 0}'
		. '.hm-story-cta h2{margin:0 0 8px;font-size:26px}.hm-story-cta p{color:#66736d}'
		. '.hm-story-cta__btns{display:flex;gap:12px;flex-wrap:wrap;margin-top:14px}'
		. '@media (max-width:60em){.hm-svc-grid{grid-template-columns:1fr}}'
		. '@media (max-width:36em){.hm-svc{grid-template-columns:1fr}.hm-svc__img img{min-height:220px;max-height:280px}.hm-bar{font-size:13px}}';
	wp_add_inline_style( 'cohf-child-style', $css );
}, 30 );

/* -------------------------------------------------------------------------
   14.1.0: fresh products on every visit.
   The shop and category pages show products in a new random order each time
   page 1 is opened (when the visitor has not picked a sort order). A short-
   lived seed keeps the order steady while they move to page 2, 3 and so on,
   so no product repeats or goes missing between pages.
   ------------------------------------------------------------------------- */

/** True for the main shop / category listing with the default sort. */
function cohf_hm_is_shuffle_query( $q ) {
	if ( is_admin() || ! ( $q instanceof WP_Query ) || ! $q->is_main_query() || $q->is_search() ) {
		return false;
	}
	if ( isset( $_GET['orderby'] ) && '' !== $_GET['orderby'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return false; // Visitor chose a sort order.
	}
	return $q->is_post_type_archive( 'product' ) || $q->is_tax( array( 'product_cat', 'product_tag' ) );
}

/** Seed for this browsing session: new on page 1, kept for later pages. */
function cohf_hm_shuffle_seed( $q ) {
	static $seed = null;
	if ( null !== $seed ) {
		return $seed;
	}
	$paged  = max( 1, (int) $q->get( 'paged' ) );
	$cookie = isset( $_COOKIE['cohf_shuffle'] ) ? (int) $_COOKIE['cohf_shuffle'] : 0;
	if ( 1 === $paged || $cookie <= 0 ) {
		$seed = wp_rand( 1, 999999 );
		if ( ! headers_sent() ) {
			setcookie( 'cohf_shuffle', (string) $seed, time() + 30 * MINUTE_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), true );
		}
	} else {
		$seed = $cookie;
	}
	return $seed;
}

add_filter( 'posts_orderby', function ( $orderby, $q ) {
	if ( ! cohf_hm_is_shuffle_query( $q ) ) {
		return $orderby;
	}
	return 'RAND(' . (int) cohf_hm_shuffle_seed( $q ) . ')';
}, 99, 2 );

/* Shuffled pages must not be served from a stale page cache. */
add_action( 'template_redirect', function () {
	global $wp_query;
	if ( cohf_hm_is_shuffle_query( $wp_query ) && ! headers_sent() ) {
		header( 'Cache-Control: no-cache, must-revalidate, max-age=0' );
	}
}, 1 );
