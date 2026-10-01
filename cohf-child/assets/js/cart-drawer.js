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

/**
 * "Order on WhatsApp" details form (12.5.0).
 * Before opening WhatsApp, ask for name, phone, location, date and payment
 * so the team receives a complete order instead of blank fields. Details are
 * remembered on this device for next time. "Skip" still sends the order.
 */
(function () {
	'use strict';
	var KEY = 'cohfWaDetails';
	var modal = null;
	var pendingHref = '';

	function saved() {
		try { return JSON.parse(window.localStorage.getItem(KEY) || '{}') || {}; } catch (e) { return {}; }
	}
	function save(d) {
		try { window.localStorage.setItem(KEY, JSON.stringify({ name: d.name, phone: d.phone, area: d.area, pay: d.pay })); } catch (e) {}
	}
	function el(html) {
		var w = document.createElement('div');
		w.innerHTML = html;
		return w.firstElementChild;
	}
	function build() {
		modal = el(
			'<div class="wa-form" role="dialog" aria-modal="true" aria-labelledby="wa-form-title" hidden>' +
			'<div class="wa-form__backdrop" data-wa-close></div>' +
			'<form class="wa-form__panel" novalidate>' +
			'<button type="button" class="wa-form__x" data-wa-close aria-label="Close">&times;</button>' +
			'<h2 id="wa-form-title">Your delivery details</h2>' +
			'<p class="wa-form__lead">We add these to your WhatsApp message so we can confirm your order quickly.</p>' +
			'<label>Full name<input name="name" autocomplete="name" maxlength="60" required></label>' +
			'<label>Phone number<input name="phone" type="tel" inputmode="tel" autocomplete="tel" maxlength="20" placeholder="07XX XXX XXX" required></label>' +
			'<label>Delivery location<input name="area" autocomplete="address-level2" maxlength="80" placeholder="e.g. Kilimani, Nairobi" required></label>' +
			'<div class="wa-form__row">' +
			'<label>Delivery date <span>(optional)</span><input name="date" type="date"></label>' +
			'<label>Payment<select name="pay"><option>M-Pesa</option><option>Cash on delivery</option><option>Not sure yet</option></select></label>' +
			'</div>' +
			'<label>Note <span>(optional)</span><input name="note" maxlength="160" placeholder="Colour, gift wrap, landmark..."></label>' +
			'<p class="wa-form__err" role="alert" hidden>Please add your name, phone number and delivery location.</p>' +
			'<button type="submit" class="cart-btn cart-btn--wa wa-form__send">Continue to WhatsApp</button>' +
			'<button type="button" class="wa-form__skip" data-wa-skip>Skip and send order only</button>' +
			'</form></div>'
		);
		document.body.appendChild(modal);
		var form = modal.querySelector('form');
		modal.addEventListener('click', function (e) {
			if (e.target.closest('[data-wa-close]')) { close(); }
			if (e.target.closest('[data-wa-skip]')) { go({}); }
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && modal && !modal.hidden) { close(); }
		});
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var d = {};
			['name', 'phone', 'area', 'date', 'pay', 'note'].forEach(function (k) { d[k] = (form.elements[k].value || '').trim(); });
			var phoneOk = d.phone.replace(/[^0-9]/g, '').length >= 9;
			var err = modal.querySelector('.wa-form__err');
			if (!d.name || !phoneOk || !d.area) {
				err.hidden = false;
				(!d.name ? form.elements.name : (!phoneOk ? form.elements.phone : form.elements.area)).focus();
				return;
			}
			err.hidden = true;
			if (d.date) {
				var dt = new Date(d.date + 'T12:00:00');
				if (!isNaN(dt)) { d.date = dt.toLocaleDateString('en-GB', { weekday: 'short', day: 'numeric', month: 'short' }); }
			}
			save(d);
			go(d);
		});
	}
	function open(href) {
		if (!modal) { build(); }
		pendingHref = href;
		var form = modal.querySelector('form');
		var s = saved();
		['name', 'phone', 'area', 'pay'].forEach(function (k) { if (s[k] && !form.elements[k].value) { form.elements[k].value = s[k]; } });
		var dateIn = form.elements.date;
		dateIn.min = new Date().toISOString().slice(0, 10);
		modal.hidden = false;
		document.documentElement.classList.add('wa-form-open');
		setTimeout(function () { (form.elements.name.value ? form.elements.area : form.elements.name).focus(); }, 30);
	}
	function close() {
		modal.hidden = true;
		document.documentElement.classList.remove('wa-form-open');
	}
	function go(d) {
		var url = new URL(pendingHref, window.location.href);
		Object.keys(d).forEach(function (k) { if (d[k]) { url.searchParams.set('wa_' + k, d[k]); } });
		close();
		var w = window.open(url.toString(), '_blank', 'noopener');
		if (!w) { window.location.href = url.toString(); }
	}
	document.addEventListener('click', function (e) {
		var a = e.target.closest && e.target.closest('a[href*="cohf-wa-order="]');
		if (!a || e.ctrlKey || e.metaKey || e.shiftKey) { return; }
		e.preventDefault();
		open(a.href);
	}, true);
})();
