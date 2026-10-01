/**
 * Hope Market - slide-out cart and AJAX add to cart.
 *
 * Add to cart (shop grid buttons marked .js-cohf-add, and the simple-product
 * form on product pages) posts in the background, swaps in the fresh cart
 * markup and slides the cart panel in from the right. Quantity buttons and
 * Remove in the panel update in place. Every failure falls back to the normal
 * page load, so a shopper can always complete the purchase.
 */
(function () {
	'use strict';

	var cfg = window.cohfCart || {};
	if (typeof cfg.ajaxUrl !== 'string') {
		return;
	}

	var drawer = document.getElementById('cohf-cart-drawer');
	var lastFocus = null;

	function endpoint(name) {
		return cfg.ajaxUrl.replace('%%endpoint%%', name);
	}

	function post(name, data) {
		var body = new URLSearchParams();
		Object.keys(data || {}).forEach(function (k) { body.append(k, data[k]); });
		return fetch(endpoint(name), {
			method: 'POST',
			credentials: 'same-origin',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
			body: body
		}).then(function (r) {
			return r.json().then(function (j) { return { ok: r.ok, status: r.status, data: j }; });
		});
	}

	function applyFragments(fragments) {
		if (fragments === null || typeof fragments !== 'object') {
			return;
		}
		Object.keys(fragments).forEach(function (sel) {
			var tmp = document.createElement('div');
			tmp.innerHTML = fragments[sel];
			var fresh = tmp.firstElementChild;
			if (fresh === null) {
				return;
			}
			Array.prototype.forEach.call(document.querySelectorAll(sel), function (el) {
				el.replaceWith(fresh.cloneNode(true));
			});
		});
		var badge = document.querySelector('.header-cart__count');
		if (badge) {
			badge.classList.remove('is-bump');
			void badge.offsetWidth;
			badge.classList.add('is-bump');
		}
	}

	function refresh() {
		return post('get_refreshed_fragments', {}).then(function (res) {
			if (res.data && res.data.fragments) {
				applyFragments(res.data.fragments);
			}
		});
	}

	/* ---- Panel open / close ---- */

	function focusables() {
		return drawer.querySelectorAll('a[href], button:not([disabled]), [tabindex="0"]');
	}

	function onKey(e) {
		if (e.key === 'Escape') {
			close();
			return;
		}
		if (e.key === 'Tab') {
			var f = focusables();
			if (f.length === 0) { return; }
			var first = f[0];
			var last = f[f.length - 1];
			if (e.shiftKey && document.activeElement === first) {
				e.preventDefault();
				last.focus();
			} else if (e.shiftKey === false && document.activeElement === last) {
				e.preventDefault();
				first.focus();
			}
		}
	}

	function open() {
		if (drawer === null) {
			window.location.href = cfg.cartUrl;
			return;
		}
		lastFocus = document.activeElement;
		drawer.setAttribute('aria-hidden', 'false');
		drawer.classList.add('is-open');
		document.documentElement.classList.add('cart-drawer-open');
		document.addEventListener('keydown', onKey);
		var panel = drawer.querySelector('.cart-drawer__panel');
		window.setTimeout(function () {
			var primary = drawer.querySelector('.cart-btn--primary');
			(primary || panel).focus({ preventScroll: true });
		}, 60);
	}

	function close() {
		if (drawer === null) { return; }
		drawer.classList.remove('is-open');
		drawer.setAttribute('aria-hidden', 'true');
		document.documentElement.classList.remove('cart-drawer-open');
		document.removeEventListener('keydown', onKey);
		var notice = drawer.querySelector('.cart-drawer__notice');
		if (notice) { notice.hidden = true; }
		if (lastFocus && typeof lastFocus.focus === 'function') {
			lastFocus.focus({ preventScroll: true });
		}
	}

	function notify(text, isError) {
		if (drawer === null) { return; }
		var notice = drawer.querySelector('.cart-drawer__notice');
		if (notice === null) { return; }
		notice.textContent = text;
		notice.classList.toggle('is-error', isError === true);
		notice.hidden = false;
	}

	/* ---- Add to cart ---- */

	function busy(el, on) {
		if (el === null) { return; }
		el.classList.toggle('is-loading', on);
		if (on) {
			el.setAttribute('aria-busy', 'true');
		} else {
			el.removeAttribute('aria-busy');
		}
	}

	function addToCart(productId, qty, name, trigger, fallback) {
		busy(trigger, true);
		post('add_to_cart', { product_id: productId, quantity: qty })
			.then(function (res) {
				busy(trigger, false);
				if (res.data === null || res.data.error) {
					if (res.data && res.data.product_url) {
						window.location.href = res.data.product_url;
					} else {
						fallback();
					}
					return;
				}
				applyFragments(res.data.fragments);
				var msg = name ? cfg.added.replace('%s', name) : cfg.addedAny;
				notify(msg, false);
				if (trigger) {
					trigger.classList.add('is-added');
					window.setTimeout(function () { trigger.classList.remove('is-added'); }, 1800);
				}
				open();
			})
			.catch(function () {
				busy(trigger, false);
				fallback();
			});
	}

	document.addEventListener('click', function (e) {
		var btn = e.target.closest('.js-cohf-add');
		if (btn === null || btn.getAttribute('data-product_id') === null) {
			return;
		}
		e.preventDefault();
		if (btn.getAttribute('aria-busy') === 'true') { return; }
		var card = btn.closest('.product');
		var title = card ? card.querySelector('.woocommerce-loop-product__title') : null;
		addToCart(
			btn.getAttribute('data-product_id'),
			btn.getAttribute('data-quantity') || 1,
			title ? title.textContent.trim() : '',
			btn,
			function () { window.location.href = btn.href; }
		);
	});

	document.addEventListener('submit', function (e) {
		var form = e.target;
		if (form.matches('form.cart') === false || form.classList.contains('variations_form') || form.classList.contains('grouped_form')) {
			return;
		}
		var idField = form.querySelector('[name="add-to-cart"]');
		if (idField === null || idField.value === '') {
			return;
		}
		e.preventDefault();
		var qtyField = form.querySelector('input.qty');
		var titleEl = document.querySelector('.product_title');
		var submit = form.querySelector('[type="submit"]');
		if (submit && submit.getAttribute('aria-busy') === 'true') { return; }
		addToCart(
			idField.value,
			qtyField ? (parseInt(qtyField.value, 10) || 1) : 1,
			titleEl ? titleEl.textContent.trim() : '',
			submit,
			function () { HTMLFormElement.prototype.submit.call(form); }
		);
	});

	/* ---- Panel controls ---- */

	function changeQty(btn, retried) {
		var inner = drawer ? drawer.querySelector('.cart-drawer__inner') : null;
		if (inner) { inner.setAttribute('aria-busy', 'true'); }
		post('cohf_cart_qty', {
			key: btn.getAttribute('data-key'),
			qty: btn.getAttribute('data-qty'),
			nonce: inner ? inner.getAttribute('data-nonce') : ''
		}).then(function (res) {
			if (res.status === 403 && retried === false) {
				// Stale security token (cached page): fetch fresh markup and retry once.
				return refresh().then(function () {
					var fresh = drawer.querySelector('[data-cart-qty][data-key="' + btn.getAttribute('data-key') + '"][data-qty="' + btn.getAttribute('data-qty') + '"]');
					changeQty(fresh || btn, true);
				});
			}
			if (res.data && res.data.fragments) {
				applyFragments(res.data.fragments);
			} else {
				notify(cfg.error, true);
			}
		}).catch(function () {
			notify(cfg.error, true);
		}).then(function () {
			var cur = drawer ? drawer.querySelector('.cart-drawer__inner') : null;
			if (cur) { cur.removeAttribute('aria-busy'); }
		});
	}

	document.addEventListener('click', function (e) {
		var opener = e.target.closest('[data-cart-open]');
		if (opener && drawer) {
			e.preventDefault();
			var notice = drawer.querySelector('.cart-drawer__notice');
			if (notice) { notice.hidden = true; }
			open();
			return;
		}
		if (e.target.closest('[data-cart-close]')) {
			e.preventDefault();
			close();
			return;
		}
		var q = e.target.closest('[data-cart-qty]');
		if (q && drawer && drawer.contains(q)) {
			e.preventDefault();
			changeQty(q, false);
		}
	});

	/* ---- Order on WhatsApp from a product page: carry the chosen quantity ---- */

	document.addEventListener('click', function (e) {
		var wa = e.target.closest('[data-wa-product]');
		if (wa === null) { return; }
		var qtyField = document.querySelector('form.cart input.qty');
		var qty = qtyField ? (parseInt(qtyField.value, 10) || 1) : 1;
		try {
			var url = new URL(wa.href, window.location.href);
			url.searchParams.set('qty', String(qty));
			wa.href = url.toString();
		} catch (err) {
			/* keep the default link */
		}
	});

	/* ---- Cached pages: bring the cart count and panel up to date ---- */

	if (document.cookie.indexOf('woocommerce_items_in_cart=') > -1) {
		refresh().catch(function () {});
	}
}());
