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
 * 14.0.1: no prices are shown for services; customers enquire on WhatsApp.
 * 14.2.0: Shop with purpose shows four random products on every load.
 * 14.1.0: the top notice can be closed; Shop with purpose is one row of
 *         custom product cards (swipeable on tablet and phone).
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
			'price' => __( 'Enquire for a price', 'cohf-child' ),
			'wa'    => __( 'Hello Cistern of Hope Foundation, I would like to enquire about photo editing, mounting or framing from Dan, and the price.', 'cohf-child' ),
			'story' => 'enterprise-photography-dan',
			'cta'   => __( 'Order photo framing from Dan', 'cohf-child' ),
		),
		'owino'  => array(
			'image' => 'story-06-tailoring-workshop',
			'who'   => __( 'Mr Owino - Tailor', 'cohf-child' ),
			'title' => __( 'Custom tailoring', 'cohf-child' ),
			'text'  => __( 'Shirts, African wear, suits and repairs, made to measure in his workshop.', 'cohf-child' ),
			'price' => __( 'Enquire for a price', 'cohf-child' ),
			'wa'    => __( 'Hello Cistern of Hope Foundation, I would like to enquire about tailoring from Mr Owino, and the price.', 'cohf-child' ),
			'story' => 'enterprise-tailoring-mr-owino',
			'cta'   => __( 'Order tailoring from Mr Owino', 'cohf-child' ),
		),
	);
}

/* 1. Dismissible Hope Market notice on every page (14.1.0). */
add_action( 'wp_body_open', function () {
	if ( is_admin() || ! function_exists( 'cohf_shop_url' ) || '' === cohf_shop_url() ) {
		return;
	}
	if ( function_exists( 'is_woocommerce' ) && ( is_cart() || is_checkout() ) ) {
		return;
	}
	printf(
		'<div class="hm-bar" id="cohf-hm-bar" role="region" aria-label="%1$s"><div class="container hm-bar__in"><p class="hm-bar__text">%2$s</p><a class="hm-bar__btn" href="%3$s">%4$s</a></div><button type="button" class="hm-bar__close" data-hm-close aria-label="%5$s"><svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg></button></div>',
		esc_attr__( 'Hope Market notice', 'cohf-child' ),
		esc_html__( 'Shop Hope Market: handmade Kenyan clothes, crafts and decor. Every purchase supports our work.', 'cohf-child' ),
		esc_url( cohf_shop_url() ),
		esc_html__( 'Shop now', 'cohf-child' ),
		esc_attr__( 'Close this notice', 'cohf-child' )
	);
	// Runs straight after the bar so a dismissed notice never flashes. The
	// choice is remembered on this device for 30 days.
	?>
	<script>
	(function () {
		var bar = document.getElementById('cohf-hm-bar'), key = 'cohfHmBarClosed', days = 30;
		if (!bar) { return; }
		try {
			var t = parseInt(localStorage.getItem(key) || '0', 10);
			if (t && (Date.now() - t) < days * 864e5) { bar.parentNode.removeChild(bar); return; }
		} catch (e) {}
		bar.querySelector('[data-hm-close]').addEventListener('click', function () {
			try { localStorage.setItem(key, String(Date.now())); } catch (e) {}
			bar.classList.add('is-closing');
			setTimeout(function () { if (bar.parentNode) { bar.parentNode.removeChild(bar); } }, 220);
			var main = document.getElementById('main-content');
			if (main) { main.focus({ preventScroll: true }); }
		});
	}());
	</script>
	<?php
}, 5 );

/**
 * A random pick of in-stock Hope Market products for the home page (14.2.0).
 * A wider pool is printed and the browser shuffles it on every load, so the
 * row changes on each visit even when the page is served from a cache.
 */
function cohf_hm_products( $limit = 4 ) {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array();
	}
	return wc_get_products( array(
		'status'     => 'publish',
		'limit'        => $limit,
		'orderby'      => 'rand',
		'visibility'   => 'catalog',
		'stock_status' => 'instock',
	) );
}

/** One product card. */
function cohf_hm_product_card( $product ) {
	$link  = $product->get_permalink();
	$terms = get_the_terms( $product->get_id(), 'product_cat' );
	$cat   = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
	$name  = $product->get_name();
	?>
	<li class="hm-card">
		<a class="hm-card__media" href="<?php echo esc_url( $link ); ?>" tabindex="-1" aria-hidden="true">
			<?php echo $product->get_image( 'woocommerce_thumbnail', array( 'loading' => 'lazy', 'sizes' => '(max-width: 48em) 72vw, 280px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image markup. ?>
			<?php if ( $product->is_on_sale() ) : ?>
				<span class="hm-card__flag"><?php esc_html_e( 'Sale', 'cohf-child' ); ?></span>
			<?php endif; ?>
		</a>
		<div class="hm-card__body">
			<?php if ( $cat ) : ?>
				<span class="hm-card__cat"><?php echo esc_html( $cat ); ?></span>
			<?php endif; ?>
			<h3 class="hm-card__title"><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $name ); ?></a></h3>
			<div class="hm-card__foot">
				<span class="hm-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
				<?php
				if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) {
					printf(
						'<a class="hm-card__add js-cohf-add" href="%1$s" data-product_id="%2$d" data-quantity="1" rel="nofollow" aria-label="%3$s">%4$s</a>',
						esc_url( $product->add_to_cart_url() ),
						(int) $product->get_id(),
						/* translators: %s: product name. */
						esc_attr( sprintf( __( 'Add %s to cart', 'cohf-child' ), $name ) ),
						esc_html__( 'Add to cart', 'cohf-child' )
					);
				} else {
					printf(
						'<a class="hm-card__add hm-card__add--view" href="%1$s" aria-label="%2$s">%3$s</a>',
						esc_url( $link ),
						/* translators: %s: product name. */
						esc_attr( sprintf( __( 'View %s', 'cohf-child' ), $name ) ),
						esc_html__( 'View', 'cohf-child' )
					);
				}
				?>
			</div>
		</div>
	</li>
	<?php
}

/* 2. Home page product section: one row of the four newest products. */
function cohf_hm_market_section() {
	if ( ! function_exists( 'cohf_shop_url' ) || '' === cohf_shop_url() ) {
		return;
	}
	$products = cohf_hm_products( 16 );
	if ( empty( $products ) ) {
		return;
	}
	?>
	<section class="cream hm-market" aria-labelledby="hm-market-title">
		<div class="container">
			<div class="hm-market__head">
				<div>
					<div class="sec-label"><span class="sec-label__rule"></span><span class="sec-label__text"><?php esc_html_e( 'Hope Market', 'cohf-child' ); ?></span></div>
					<h2 id="hm-market-title" class="sec-statement sec-statement--wide"><?php esc_html_e( 'Shop with purpose.', 'cohf-child' ); ?></h2>
					<p><?php esc_html_e( 'Handmade in Kenya by the women and youth we support. Every purchase funds our programmes.', 'cohf-child' ); ?></p>
				</div>
				<a class="hm-market__all" href="<?php echo esc_url( cohf_shop_url() ); ?>"><?php esc_html_e( 'Visit Hope Market', 'cohf-child' ); ?><span aria-hidden="true">&rarr;</span></a>
			</div>
			<ul class="hm-row" id="cohf-hm-row" role="list">
				<?php
				foreach ( $products as $product ) {
					cohf_hm_product_card( $product );
				}
				?>
			</ul>
			<script>
			(function () {
				var row = document.getElementById('cohf-hm-row');
				if (!row) { return; }
				var items = Array.prototype.slice.call(row.children);
				for (var i = items.length - 1; i > 0; i--) {
					var j = Math.floor(Math.random() * (i + 1)), t = items[i];
					items[i] = items[j]; items[j] = t;
				}
				items.forEach(function (el) { row.appendChild(el); });
			}());
			</script>
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
	$css = ''
		/* Notice bar */
		. '.hm-bar{position:relative;background:#d9a441;color:#17231e;font-size:14px;font-weight:700;transition:opacity .2s,transform .2s}'
		. '.hm-bar.is-closing{opacity:0;transform:translateY(-100%)}'
		. '.hm-bar__in{display:flex;align-items:center;justify-content:center;gap:14px;padding:8px 56px;flex-wrap:wrap;text-align:center}'
		. '.hm-bar__text{margin:0}'
		. '.hm-bar__btn{background:#163f32;color:#fff;border-radius:999px;padding:6px 16px;font-weight:800;white-space:nowrap;text-decoration:none}'
		. '.hm-bar__btn:hover,.hm-bar__btn:focus-visible{background:#0f2c23;color:#fff}'
		. '.hm-bar__close{position:absolute;top:50%;right:8px;transform:translateY(-50%);display:grid;place-items:center;width:44px;height:44px;padding:0;border:0;border-radius:50%;background:transparent;color:#17231e;cursor:pointer}'
		. '.hm-bar__close:hover{background:rgba(23,35,30,.12)}'
		/* Section heads */
		. '.hm-head{text-align:center;max-width:760px;margin:0 auto 34px}.hm-head .sec-label{justify-content:center}.hm-head p{color:#66736d}'
		. '.hm-btn-gold{background:#d9a441;color:#17231e}.hm-btn-gold:hover{background:#c8932f;color:#17231e}'
		. '.hm-btn-wa{background:#25a05a;color:#fff}.hm-btn-wa:hover{background:#1e8a4c;color:#fff}'
		/* Shop with purpose */
		. '.hm-market__head{display:flex;align-items:flex-end;justify-content:space-between;gap:24px;margin-bottom:32px}'
		. '.hm-market__head p{color:#66736d;margin:8px 0 0;max-width:560px}'
		. '.hm-market__all{display:inline-flex;align-items:center;gap:8px;min-height:44px;padding:0 22px;border-radius:999px;background:#d9a441;color:#17231e;font-weight:800;white-space:nowrap;text-decoration:none;transition:background .2s}'
		. '.hm-market__all:hover,.hm-market__all:focus-visible{background:#c8932f;color:#17231e}'
		. '.hm-market__all span{transition:transform .2s}.hm-market__all:hover span{transform:translateX(3px)}'
		. '.hm-row{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:22px;list-style:none;margin:0;padding:0}'
		. '.hm-row>.hm-card:nth-child(n+5){display:none}'
		. '.hm-card{display:flex;flex-direction:column;margin:0;background:#fff;border:1px solid #e7e2d6;border-radius:18px;overflow:hidden;transition:transform .25s,box-shadow .25s}'
		. '.hm-card:hover,.hm-card:focus-within{transform:translateY(-4px);box-shadow:0 18px 40px -22px rgba(22,63,50,.45)}'
		. '.hm-card__media{position:relative;display:block;aspect-ratio:1/1;overflow:hidden;background:#efe9dc}'
		. '.hm-card__media img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .5s}'
		. '.hm-card:hover .hm-card__media img{transform:scale(1.05)}'
		. '.hm-card__flag{position:absolute;top:12px;left:12px;padding:4px 10px;border-radius:999px;background:#b86f52;color:#fff;font-size:12px;font-weight:800}'
		. '.hm-card__body{display:flex;flex-direction:column;flex:1;gap:6px;padding:16px 18px 18px}'
		. '.hm-card__cat{color:#b86f52;font-size:11px;font-weight:800;letter-spacing:.1em;text-transform:uppercase}'
		. '.hm-card__title{margin:0;font-size:18px;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:2.6em}'
		. '.hm-card__title a{color:#17231e;text-decoration:none}.hm-card__title a:hover{color:#163f32;text-decoration:underline}'
		. '.hm-card__foot{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-top:auto;padding-top:10px}'
		. '.hm-card__price{color:#163f32;font-weight:800;font-size:16px}.hm-card__price del{color:#8a948f;font-weight:500;margin-right:4px}.hm-card__price ins{text-decoration:none}'
		. '.hm-card__add{display:inline-flex;align-items:center;justify-content:center;min-height:44px;padding:0 16px;border-radius:999px;border:1.5px solid #163f32;color:#163f32;background:#fff;font-size:14px;font-weight:800;white-space:nowrap;text-decoration:none;transition:background .2s,color .2s}'
		. '.hm-card__add:hover,.hm-card__add:focus-visible{background:#163f32;color:#fff}'
		. '.hm-card__add[aria-busy="true"]{opacity:.6;pointer-events:none}'
		/* Services */
		. '.hm-svc-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:28px}'
		. '.hm-svc{display:grid;grid-template-columns:240px 1fr;background:#fff;border-radius:18px;overflow:hidden;transition:box-shadow .25s}'
		. '.hm-svc:hover{box-shadow:0 18px 40px -24px rgba(22,63,50,.45)}'
		. '.hm-svc__img img{width:100%;height:100%;object-fit:cover;display:block;min-height:300px}'
		. '.hm-svc__body{padding:26px;display:flex;flex-direction:column;gap:8px;align-items:flex-start}'
		. '.hm-svc__who{color:#b86f52;font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}'
		. '.hm-svc h3{margin:0;font-size:22px}.hm-svc p{color:#66736d;margin:0}'
		. '.hm-svc__price{color:#66736d;font-size:14px;font-weight:600;margin-top:auto;padding-top:8px}'
		/* Strip */
		. '.hm-strip{background:#163f32;color:#fff;padding:44px 0}'
		. '.hm-strip__in{display:flex;align-items:center;justify-content:space-between;gap:24px;flex-wrap:wrap}'
		. '.hm-strip h2{color:#fff;margin:0 0 6px;font-size:clamp(26px,3vw,34px)}.hm-strip p{color:#d2e1d7;margin:0}'
		/* Story CTA */
		. '.hm-story-cta{background:#f7f3ea;border:1px solid #dfe6e1;border-radius:16px;padding:28px;margin:36px 0}'
		. '.hm-story-cta h2{margin:0 0 8px;font-size:26px}.hm-story-cta p{color:#66736d}'
		. '.hm-story-cta__btns{display:flex;gap:12px;flex-wrap:wrap;margin-top:14px}'
		/* Tablet and phone: the product row stays one row and swipes sideways. */
		. '@media (max-width:64em){.hm-row{grid-template-columns:none;grid-auto-flow:column;grid-auto-columns:minmax(240px,32%);overflow-x:auto;scroll-snap-type:x mandatory;padding:4px 4px 14px;margin:0 -4px;scrollbar-width:thin;-webkit-overflow-scrolling:touch}.hm-card{scroll-snap-align:start}}'
		. '@media (max-width:60em){.hm-svc-grid{grid-template-columns:1fr}}'
		. '@media (max-width:48em){.hm-market__head{flex-direction:column;align-items:flex-start}.hm-row{grid-auto-columns:72%}.hm-bar__in{padding:8px 52px 8px 16px;gap:10px}}'
		. '@media (max-width:36em){.hm-svc{grid-template-columns:1fr}.hm-svc__img img{min-height:220px;max-height:280px}.hm-bar{font-size:13px}}'
		. '@media (prefers-reduced-motion:reduce){.hm-card,.hm-card__media img,.hm-bar{transition:none}.hm-card:hover,.hm-card:focus-within{transform:none}.hm-card:hover .hm-card__media img{transform:none}}';
	wp_add_inline_style( 'cohf-child-style', $css );
}, 30 );
