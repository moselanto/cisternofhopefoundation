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
 * "Order on WhatsApp" pop-up (14.8.0).
 *
 * A three-part order sheet that opens from every Order on WhatsApp button:
 * 1. Your order  - photo, name, quantity stepper (single product) and total.
 * 2. Details     - delivery or pickup, name, Kenyan phone check, location,
 *                  quick date choice, payment choice and an optional note.
 * 3. Send        - a live preview of the exact WhatsApp message, then a
 *                  confirmation screen with the order reference, "open again"
 *                  and "copy message" in case WhatsApp did not open.
 *
 * The message and link come from the server (wc-ajax=cohf_wa_preview) so the
 * pop-up shows exactly what the team will receive. Without JavaScript, or if
 * the preview cannot load, the button still works through the old redirect.
 * Details are remembered on this device only when the shopper agrees.
 */
(function () {
	'use strict';

	var KEY = 'cohfWaDetails';
	var CFG = window.cohfCart || {};
	var modal = null;
	var form = null;
	var state = { href: '', mode: 'cart', productId: 0, qty: 1, ref: '', url: '', message: '', total: '', reqId: 0, lastFocus: null };
	var timer = null;

	/* ---------- helpers ---------- */

	function el(html) {
		var w = document.createElement('div');
		w.innerHTML = html.trim();
		return w.firstElementChild;
	}
	function esc(t) {
		return String(t == null ? '' : t).replace(/[&<>"']/g, function (c) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
		});
	}
	function saved() {
		try { return JSON.parse(window.localStorage.getItem(KEY) || '{}') || {}; } catch (e) { return {}; }
	}
	function save(d) {
		try { window.localStorage.setItem(KEY, JSON.stringify({ name: d.name, phone: d.phone, area: d.area, pay: d.pay, mode: d.mode })); } catch (e) {}
	}
	function forget() {
		try { window.localStorage.removeItem(KEY); } catch (e) {}
	}
	function makeRef() {
		var n = new Date();
		var p = function (x) { return (x < 10 ? '0' : '') + x; };
		var chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
		var r = '';
		for (var i = 0; i < 4; i++) { r += chars.charAt(Math.floor(Math.random() * chars.length)); }
		return 'HM-' + String(n.getFullYear()).slice(2) + p(n.getMonth() + 1) + p(n.getDate()) + '-' + r;
	}
	/* Kenyan mobile: 07XX / 01XX / 7XX / +254... -> +254 7XX XXX XXX, or '' if invalid. */
	function normPhone(raw) {
		var d = String(raw || '').replace(/\D/g, '');
		if (d.length === 10 && d.charAt(0) === '0') { d = '254' + d.slice(1); }
		else if (d.length === 9 && (d.charAt(0) === '7' || d.charAt(0) === '1')) { d = '254' + d; }
		if (!/^254[17]\d{8}$/.test(d)) { return ''; }
		return '+254 ' + d.slice(3, 6) + ' ' + d.slice(6, 9) + ' ' + d.slice(9);
	}
	function iso(d) {
		var p = function (x) { return (x < 10 ? '0' : '') + x; };
		return d.getFullYear() + '-' + p(d.getMonth() + 1) + '-' + p(d.getDate());
	}
	function niceDate(isoStr) {
		var dt = new Date(isoStr + 'T12:00:00');
		return isNaN(dt) ? '' : dt.toLocaleDateString('en-GB', { weekday: 'short', day: 'numeric', month: 'short' });
	}
	function radio(name) {
		var r = form.querySelector('input[name="' + name + '"]:checked');
		return r ? r.value : '';
	}

	/* ---------- markup ---------- */

	var WA = '<svg class="wa-icon" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 2a8 8 0 1 1-4.1 14.9l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 0 1 12 4Zm-3.2 4c-.2 0-.5 0-.7.3-.2.3-.9.9-.9 2.2s.9 2.5 1 2.7c.1.2 1.8 2.8 4.4 3.9 2.2.9 2.6.7 3.1.7.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.2-1.2-.1-.1-.2-.2-.5-.3l-1.7-.8c-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1-.3-.1-1.1-.4-2-1.3-.8-.7-1.3-1.5-1.4-1.8-.2-.3 0-.4.1-.5l.4-.4.3-.4v-.4l-.8-1.9c-.2-.5-.4-.5-.6-.5h-.5Z"/></svg>';

	function build() {
		var office = CFG.office || 'our office';
		modal = el(
			'<div class="wao" hidden>' +
			'<div class="wao__backdrop" data-wao-close></div>' +
			'<div class="wao__sheet" role="dialog" aria-modal="true" aria-labelledby="wao-title" aria-describedby="wao-lead">' +
				'<header class="wao__head">' +
					'<span class="wao__badge">' + WA + '</span>' +
					'<div><h2 id="wao-title">Order on WhatsApp</h2>' +
					'<p id="wao-lead" class="wao__lead">Fill in a few details and we will send a ready-made order to the Hope Market team.</p></div>' +
					'<button type="button" class="wao__x" data-wao-close aria-label="Close">&times;</button>' +
				'</header>' +
				'<ol class="wao__steps" aria-hidden="true"><li class="is-on">Your order</li><li>Details</li><li>Send</li></ol>' +

				'<form class="wao__body" novalidate>' +
					'<section class="wao__card wao__order" aria-label="Your order">' +
						'<ul class="wao__items" data-wao-items><li class="wao__skel"></li></ul>' +
						'<div class="wao__qty" data-wao-qty hidden>' +
							'<span>Quantity</span>' +
							'<div class="wao__stepper">' +
								'<button type="button" data-wao-step="-1" aria-label="One less">&minus;</button>' +
								'<input name="qty" type="number" inputmode="numeric" min="1" max="99" value="1" aria-label="Quantity">' +
								'<button type="button" data-wao-step="1" aria-label="One more">+</button>' +
							'</div>' +
						'</div>' +
						'<p class="wao__total"><span>Items total</span><strong data-wao-total>&nbsp;</strong></p>' +
						'<p class="wao__fee" data-wao-fee>Delivery fee confirmed on WhatsApp before you pay.</p>' +
					'</section>' +

					'<fieldset class="wao__seg wao__seg--2">' +
						'<legend>How would you like to receive it?</legend>' +
						'<label><input type="radio" name="mode" value="delivery" checked><span><b>Delivery</b><small>To your location</small></span></label>' +
						'<label><input type="radio" name="mode" value="pickup"><span><b>Pick up</b><small>' + esc(office) + '</small></span></label>' +
					'</fieldset>' +

					'<div class="wao__grid">' +
						'<div class="wao__f"><label for="wao-name">Full name</label>' +
							'<input id="wao-name" name="name" autocomplete="name" maxlength="60" required>' +
							'<p class="wao__err" aria-live="polite"></p></div>' +
						'<div class="wao__f"><label for="wao-phone">Phone number</label>' +
							'<input id="wao-phone" name="phone" type="tel" inputmode="tel" autocomplete="tel" maxlength="20" placeholder="07XX XXX XXX" required>' +
							'<p class="wao__err" aria-live="polite"></p></div>' +
					'</div>' +
					'<div class="wao__f" data-wao-area><label for="wao-area">Delivery location</label>' +
						'<input id="wao-area" name="area" autocomplete="address-level2" maxlength="80" placeholder="Estate, town and a landmark" required>' +
						'<p class="wao__err" aria-live="polite"></p></div>' +

					'<fieldset class="wao__chips">' +
						'<legend data-wao-when>When do you need it?</legend>' +
						'<label><input type="radio" name="when" value="any" checked><span>Any day</span></label>' +
						'<label><input type="radio" name="when" value="today"><span>Today</span></label>' +
						'<label><input type="radio" name="when" value="tomorrow"><span>Tomorrow</span></label>' +
						'<label><input type="radio" name="when" value="pick"><span>Pick a date</span></label>' +
						'<input class="wao__date" name="date" type="date" aria-label="Choose a date" hidden>' +
					'</fieldset>' +

					'<fieldset class="wao__chips">' +
						'<legend>How will you pay?</legend>' +
						'<label><input type="radio" name="pay" value="M-Pesa" checked><span>M-Pesa</span></label>' +
						'<label><input type="radio" name="pay" value="Cash on delivery"><span data-wao-cash>Cash on delivery</span></label>' +
						'<label><input type="radio" name="pay" value="Not sure yet"><span>Not sure yet</span></label>' +
					'</fieldset>' +

					'<div class="wao__f"><label for="wao-note">Note <span>(optional)</span></label>' +
						'<textarea id="wao-note" name="note" rows="2" maxlength="300" placeholder="Colour, size, gift message, best time to call..."></textarea>' +
						'<p class="wao__count" aria-live="off"><span data-wao-count>0</span>/300</p></div>' +

					'<details class="wao__preview">' +
						'<summary>Preview your WhatsApp message</summary>' +
						'<pre data-wao-msg>Loading...</pre>' +
					'</details>' +

					'<label class="wao__remember"><input type="checkbox" name="remember" checked> Remember my details on this device</label>' +
					'<button type="button" class="wao__forget" data-wao-forget hidden>Not you? Clear saved details</button>' +

					'<p class="wao__alert" role="alert" hidden></p>' +
					'<div class="wao__foot">' +
						'<button type="submit" class="cart-btn cart-btn--wa wao__send">' + WA + '<span>Send order on WhatsApp</span><em data-wao-btn-total></em></button>' +
						'<p class="wao__secure">Nothing is paid here. We confirm availability' + ' and payment with you on WhatsApp.</p>' +
					'</div>' +
				'</form>' +

				'<section class="wao__done" hidden tabindex="-1" aria-labelledby="wao-done-title">' +
					'<div class="wao__tick" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12.5l4.2 4.2L19 7" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></svg></div>' +
					'<h2 id="wao-done-title">Almost done - tap Send in WhatsApp</h2>' +
					'<p>Your order is written out in WhatsApp. Tap <b>Send</b> there and the Hope Market team will confirm availability and delivery.</p>' +
					'<p class="wao__ref">Order reference <strong data-wao-ref></strong></p>' +
					'<div class="wao__done-actions">' +
						'<a class="cart-btn cart-btn--wa" data-wao-again href="#" target="_blank" rel="noopener">' + WA + '<span>WhatsApp did not open? Try again</span></a>' +
						'<button type="button" class="cart-btn cart-btn--ghost" data-wao-copy>Copy order message</button>' +
						'<button type="button" class="wao__link" data-wao-close>Continue shopping</button>' +
					'</div>' +
				'</section>' +
			'</div></div>'
		);
		document.body.appendChild(modal);
		form = modal.querySelector('form');

		modal.addEventListener('click', onClick);
		form.addEventListener('input', onInput);
		form.addEventListener('change', onChange);
		form.addEventListener('submit', onSubmit);
		form.elements.phone.addEventListener('blur', function () {
			var n = normPhone(form.elements.phone.value);
			if (n) { form.elements.phone.value = n; setErr(form.elements.phone, ''); }
		});
		document.addEventListener('keydown', onKey);
	}

	/* ---------- behaviour ---------- */

	function setErr(input, msg) {
		var f = input.closest('.wao__f');
		if (!f) { return; }
		f.classList.toggle('has-error', !!msg);
		input.setAttribute('aria-invalid', msg ? 'true' : 'false');
		f.querySelector('.wao__err').textContent = msg || '';
	}

	function setStep(n) {
		modal.querySelectorAll('.wao__steps li').forEach(function (li, i) { li.classList.toggle('is-on', i < n); });
	}

	function details() {
		var mode = radio('mode') || 'delivery';
		var when = radio('when');
		var date = '';
		var today = new Date();
		if (when === 'today') { date = niceDate(iso(today)); }
		else if (when === 'tomorrow') { var t = new Date(today); t.setDate(t.getDate() + 1); date = niceDate(iso(t)); }
		else if (when === 'pick' && form.elements.date.value) { date = niceDate(form.elements.date.value); }
		var phone = form.elements.phone.value.trim();
		return {
			mode: mode,
			name: form.elements.name.value.trim(),
			phone: normPhone(phone) || phone,
			area: mode === 'pickup' ? '' : form.elements.area.value.trim(),
			date: date,
			pay: radio('pay'),
			note: form.elements.note.value.trim(),
			ref: state.ref
		};
	}

	function previewUrl(d) {
		var base = (CFG.ajaxUrl || '/?wc-ajax=%%endpoint%%').replace('%%endpoint%%', 'cohf_wa_preview');
		var u = new URL(base, window.location.href);
		u.searchParams.set('cohf-wa-order', state.mode);
		if (state.mode === 'product') {
			u.searchParams.set('product_id', String(state.productId));
			u.searchParams.set('qty', String(state.qty));
		}
		Object.keys(d).forEach(function (k) { if (d[k]) { u.searchParams.set('wa_' + k, d[k]); } });
		return u.toString();
	}

	function fallbackUrl(d) {
		var u = new URL(state.href, window.location.href);
		if (state.mode === 'product') { u.searchParams.set('qty', String(state.qty)); }
		Object.keys(d).forEach(function (k) { if (d[k]) { u.searchParams.set('wa_' + k, d[k]); } });
		return u.toString();
	}

	function renderItems(data) {
		var ul = modal.querySelector('[data-wao-items]');
		ul.innerHTML = data.items.map(function (it) {
			return '<li>' +
				(it.img ? '<img src="' + esc(it.img) + '" alt="" width="56" height="56" loading="lazy">' : '<span class="wao__noimg" aria-hidden="true"></span>') +
				'<div><b>' + esc(it.name) + '</b><small>' + esc(it.qty) + ' &times; ' + esc(it.unit) + '</small></div>' +
				'<span class="wao__sub">' + esc(it.sub) + '</span></li>';
		}).join('');
		var label = data.count === 1 ? 'Items total (1 item)' : 'Items total (' + data.count + ' items)';
		modal.querySelector('.wao__total span').textContent = label;
		modal.querySelector('[data-wao-total]').textContent = data.total;
		modal.querySelector('[data-wao-btn-total]').textContent = data.total;
	}

	function refresh(immediate) {
		clearTimeout(timer);
		var run = function () {
			var id = ++state.reqId;
			var d = details();
			state.url = fallbackUrl(d);
			fetch(previewUrl(d), { credentials: 'same-origin', headers: { Accept: 'application/json' } })
				.then(function (r) { return r.json(); })
				.then(function (res) {
					if (id !== state.reqId) { return; }
					if (!res || !res.success) {
						if (res && res.data && res.data.empty) { showAlert('Your cart is empty. Add something first, then order on WhatsApp.'); }
						return;
					}
					state.url = res.data.url;
					state.message = res.data.message;
					state.total = res.data.total;
					renderItems(res.data);
					modal.querySelector('[data-wao-msg]').textContent = res.data.message;
				})
				.catch(function () {
					if (id === state.reqId) { modal.querySelector('[data-wao-msg]').textContent = 'The preview could not load, but your order will still be sent.'; }
				});
		};
		if (immediate) { run(); } else { timer = setTimeout(run, 350); }
	}

	function showAlert(msg) {
		var a = modal.querySelector('.wao__alert');
		a.textContent = msg || '';
		a.hidden = !msg;
	}

	function applyMode() {
		var pickup = radio('mode') === 'pickup';
		modal.querySelector('[data-wao-area]').hidden = pickup;
		modal.querySelector('[data-wao-fee]').textContent = pickup
			? 'Collect from ' + (CFG.office || 'our office') + '. No delivery fee.'
			: 'Delivery fee confirmed on WhatsApp before you pay.';
		modal.querySelector('[data-wao-when]').textContent = pickup ? 'When will you collect?' : 'When do you need it?';
		modal.querySelector('[data-wao-cash]').textContent = pickup ? 'Cash on pickup' : 'Cash on delivery';
		if (pickup) { setErr(form.elements.area, ''); }
	}

	function onInput(e) {
		var t = e.target;
		if (t.name === 'note') { modal.querySelector('[data-wao-count]').textContent = String(t.value.length); }
		if (t.closest('.wao__f.has-error')) { setErr(t, ''); }
		if (t.name === 'qty') {
			state.qty = Math.max(1, Math.min(99, parseInt(t.value, 10) || 1));
		}
		if (t.name === 'name' || t.name === 'phone' || t.name === 'area') { setStep(2); }
		refresh(false);
	}

	function onChange(e) {
		var t = e.target;
		if (t.name === 'mode') { applyMode(); }
		if (t.name === 'when') {
			var picker = form.elements.date;
			picker.hidden = t.value !== 'pick';
			if (t.value === 'pick') { picker.min = iso(new Date()); picker.focus(); if (picker.showPicker) { try { picker.showPicker(); } catch (err) {} } }
		}
		if (t.name === 'qty') { t.value = String(state.qty); }
		refresh(true);
	}

	function onClick(e) {
		var t = e.target;
		if (t.closest('[data-wao-close]')) { e.preventDefault(); close(); return; }
		var step = t.closest('[data-wao-step]');
		if (step) {
			state.qty = Math.max(1, Math.min(99, state.qty + parseInt(step.getAttribute('data-wao-step'), 10)));
			form.elements.qty.value = String(state.qty);
			refresh(true);
			return;
		}
		if (t.closest('[data-wao-forget]')) {
			forget();
			['name', 'phone', 'area'].forEach(function (k) { form.elements[k].value = ''; });
			t.closest('[data-wao-forget]').hidden = true;
			form.elements.name.focus();
			refresh(true);
			return;
		}
		if (t.closest('[data-wao-copy]')) {
			var btn = t.closest('[data-wao-copy]');
			var text = state.message || '';
			var done = function () { btn.textContent = 'Copied'; setTimeout(function () { btn.textContent = 'Copy order message'; }, 2000); };
			if (navigator.clipboard && text) { navigator.clipboard.writeText(text).then(done, function () {}); }
			return;
		}
	}

	function validate() {
		var first = null;
		var name = form.elements.name;
		var phone = form.elements.phone;
		var area = form.elements.area;
		if (name.value.trim().length < 2) { setErr(name, 'Please enter your name.'); first = first || name; } else { setErr(name, ''); }
		if (!normPhone(phone.value)) { setErr(phone, 'Enter a Kenyan mobile number, e.g. 0712 345 678.'); first = first || phone; } else { setErr(phone, ''); }
		if (radio('mode') !== 'pickup' && area.value.trim().length < 2) { setErr(area, 'Tell us where to deliver.'); first = first || area; } else { setErr(area, ''); }
		if (radio('when') === 'pick' && !form.elements.date.value) { showAlert('Choose a date, or pick "Any day".'); first = first || form.elements.date; }
		if (first) { first.focus(); return false; }
		showAlert('');
		return true;
	}

	function onSubmit(e) {
		e.preventDefault();
		if (!validate()) { return; }
		var d = details();
		form.elements.phone.value = d.phone;
		if (form.elements.remember.checked) { save(d); } else { forget(); }
		setStep(3);
		/* Prefer the server-built link (exact preview); fall back to the redirect link. */
		var url = state.message ? state.url : fallbackUrl(d);
		var w = window.open(url, '_blank', 'noopener');
		if (!w) { window.location.href = url; }
		showDone(url);
	}

	function showDone(url) {
		form.hidden = true;
		var done = modal.querySelector('.wao__done');
		done.hidden = false;
		modal.querySelector('[data-wao-ref]').textContent = state.ref;
		modal.querySelector('[data-wao-again]').href = url;
		modal.querySelector('[data-wao-copy]').hidden = !(state.message && navigator.clipboard);
		modal.querySelector('.wao__steps').hidden = true;
		done.focus();
	}

	function trap(e) {
		var nodes = modal.querySelectorAll('button, [href], input, select, textarea, summary, [tabindex]:not([tabindex="-1"])');
		var list = Array.prototype.filter.call(nodes, function (n) { return !n.disabled && n.offsetParent !== null; });
		if (!list.length) { return; }
		var first = list[0];
		var last = list[list.length - 1];
		if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
		else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
	}

	function onKey(e) {
		if (!modal || modal.hidden) { return; }
		if (e.key === 'Escape') { close(); }
		if (e.key === 'Tab') { trap(e); }
	}

	function open(a) {
		if (!modal) { build(); }
		state.lastFocus = document.activeElement;
		state.href = a.href;
		state.ref = makeRef();
		state.message = '';
		state.url = '';

		var u;
		try { u = new URL(a.href, window.location.href); } catch (err) { window.location.href = a.href; return; }
		state.mode = u.searchParams.get('cohf-wa-order') === 'product' ? 'product' : 'cart';
		state.productId = parseInt(u.searchParams.get('product_id'), 10) || 0;
		var q = parseInt(u.searchParams.get('qty'), 10) || 1;
		if (a.hasAttribute('data-wa-product')) {
			var qf = document.querySelector('form.cart input.qty');
			if (qf) { q = parseInt(qf.value, 10) || q; }
		}
		state.qty = Math.max(1, Math.min(99, q));

		/* reset */
		form.hidden = false;
		modal.querySelector('.wao__done').hidden = true;
		modal.querySelector('.wao__steps').hidden = false;
		modal.querySelector('[data-wao-items]').innerHTML = '<li class="wao__skel"></li>';
		modal.querySelector('[data-wao-total]').innerHTML = '&nbsp;';
		modal.querySelector('[data-wao-btn-total]').textContent = '';
		modal.querySelector('[data-wao-msg]').textContent = 'Loading...';
		modal.querySelector('[data-wao-qty]').hidden = state.mode !== 'product';
		form.elements.qty.value = String(state.qty);
		form.elements.note.value = '';
		modal.querySelector('[data-wao-count]').textContent = '0';
		form.querySelector('input[name="when"][value="any"]').checked = true;
		form.elements.date.hidden = true;
		form.elements.date.value = '';
		showAlert('');
		['name', 'phone', 'area'].forEach(function (k) { setErr(form.elements[k], ''); });

		var s = saved();
		var hasSaved = !!(s.name || s.phone);
		['name', 'phone', 'area'].forEach(function (k) { if (s[k] && !form.elements[k].value) { form.elements[k].value = s[k]; } });
		if (s.pay) { var p = form.querySelector('input[name="pay"][value="' + s.pay + '"]'); if (p) { p.checked = true; } }
		if (s.mode) { var m = form.querySelector('input[name="mode"][value="' + s.mode + '"]'); if (m) { m.checked = true; } }
		modal.querySelector('[data-wao-forget]').hidden = !hasSaved;
		applyMode();
		setStep(1);

		modal.hidden = false;
		document.documentElement.classList.add('wao-open');
		requestAnimationFrame(function () { modal.classList.add('is-open'); });
		refresh(true);

		var mobile = window.matchMedia && window.matchMedia('(max-width: 600px)').matches;
		setTimeout(function () {
			var target = form.elements.name.value ? (radio('mode') === 'pickup' ? modal.querySelector('.wao__send') : form.elements.area) : form.elements.name;
			if (mobile) { modal.querySelector('.wao__x').focus(); } else { target.focus(); }
		}, 60);
	}

	function close() {
		if (!modal || modal.hidden) { return; }
		modal.classList.remove('is-open');
		document.documentElement.classList.remove('wao-open');
		var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		setTimeout(function () { modal.hidden = true; }, reduce ? 0 : 200);
		if (state.lastFocus && state.lastFocus.focus) { state.lastFocus.focus(); }
	}

	/* Every Order on WhatsApp button points at ?cohf-wa-order=... */
	document.addEventListener('click', function (e) {
		var a = e.target.closest && e.target.closest('a[href*="cohf-wa-order="]');
		if (!a || e.ctrlKey || e.metaKey || e.shiftKey || e.button > 0) { return; }
		if (!window.fetch || !window.URL) { return; }
		e.preventDefault();
		open(a);
	}, true);
})();
