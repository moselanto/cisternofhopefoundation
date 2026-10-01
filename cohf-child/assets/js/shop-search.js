/**
 * Hope Market - live search.
 *
 * Results appear as the shopper types (after 2 letters, 180ms pause):
 * matching categories, then up to six products with photo, category and
 * price, then "See all results". Arrow keys move through suggestions, Enter
 * opens one, Escape closes. With an empty box it offers recent searches
 * (kept on this device only) and popular searches. Without JavaScript the
 * form still submits a normal search.
 */
(function () {
	'use strict';

	var cfg = window.cohfSearch || {};
	if (typeof cfg.endpoint !== 'string') {
		return;
	}
	var t = cfg.i18n || {};
	var RECENT_KEY = 'cohfRecentSearches';

	function esc(s) {
		return String(s).replace(/[&<>"']/g, function (c) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
		});
	}

	function highlight(text, q) {
		var safe = esc(text);
		var words = q.toLowerCase().split(/\s+/).filter(function (w) { return w.length > 1; });
		words.forEach(function (w) {
			var stem = w.replace(/(es|s)$/, '');
			if (stem.length < 2) { return; }
			var re = new RegExp('(' + stem.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'ig');
			safe = safe.replace(re, '<mark>$1</mark>');
		});
		return safe;
	}

	function getRecent() {
		try {
			var v = JSON.parse(window.localStorage.getItem(RECENT_KEY) || '[]');
			return Array.isArray(v) ? v.slice(0, 5) : [];
		} catch (e) {
			return [];
		}
	}

	function saveRecent(q) {
		q = q.trim();
		if (q.length < 2) { return; }
		try {
			var list = getRecent().filter(function (x) { return x.toLowerCase() !== q.toLowerCase(); });
			list.unshift(q);
			window.localStorage.setItem(RECENT_KEY, JSON.stringify(list.slice(0, 5)));
		} catch (e) { /* storage unavailable */ }
	}

	function searchUrl(q) {
		var form = document.querySelector('.shop-search__form');
		var base = form ? form.getAttribute('action') : '/';
		return base + (base.indexOf('?') > -1 ? '&' : '?') + 's=' + encodeURIComponent(q) + '&post_type=product';
	}

	function setup(root) {
		var form = root.querySelector('form');
		var input = root.querySelector('.shop-search__input');
		var panel = root.querySelector('.shop-search__panel');
		var clearBtn = root.querySelector('.shop-search__clear');
		var timer = null;
		var controller = null;
		var active = -1;
		var cache = {};

		function options() {
			return panel.querySelectorAll('[role="option"]');
		}

		function openPanel() {
			panel.hidden = false;
			input.setAttribute('aria-expanded', 'true');
			root.classList.add('is-open');
		}

		function closePanel() {
			panel.hidden = true;
			input.setAttribute('aria-expanded', 'false');
			input.removeAttribute('aria-activedescendant');
			root.classList.remove('is-open');
			active = -1;
		}

		function setActive(i) {
			var opts = options();
			if (opts.length === 0) { return; }
			if (i < 0) { i = opts.length - 1; }
			if (i >= opts.length) { i = 0; }
			Array.prototype.forEach.call(opts, function (o) { o.setAttribute('aria-selected', 'false'); });
			opts[i].setAttribute('aria-selected', 'true');
			input.setAttribute('aria-activedescendant', opts[i].id);
			opts[i].scrollIntoView({ block: 'nearest' });
			active = i;
		}

		function idFor(n) {
			return input.id + '-opt-' + n;
		}

		function renderStart() {
			var recent = getRecent();
			var html = '';
			var n = 0;
			if (recent.length) {
				html += '<div class="ss-group"><div class="ss-head"><span>' + esc(t.recent) + '</span><button type="button" class="ss-clear-recent">' + esc(t.clear) + '</button></div><ul class="ss-chips">';
				recent.forEach(function (q) {
					html += '<li><a role="option" id="' + idFor(n++) + '" class="ss-chip ss-chip--recent" href="' + esc(searchUrl(q)) + '" data-q="' + esc(q) + '">' + esc(q) + '</a></li>';
				});
				html += '</ul></div>';
			}
			if (cfg.popular && cfg.popular.length) {
				html += '<div class="ss-group"><div class="ss-head"><span>' + esc(t.popular) + '</span></div><ul class="ss-chips">';
				cfg.popular.forEach(function (q) {
					html += '<li><a role="option" id="' + idFor(n++) + '" class="ss-chip" href="' + esc(searchUrl(q)) + '" data-q="' + esc(q) + '">' + esc(q) + '</a></li>';
				});
				html += '</ul></div>';
			}
			if (html === '') {
				closePanel();
				return;
			}
			panel.innerHTML = html;
			openPanel();
		}

		function renderResults(data) {
			var q = data.q;
			var used = data.suggest || q;
			var html = '';
			var n = 0;
			if (data.suggest) {
				html += '<p class="ss-note">' + esc(t.showing.replace('%s', data.suggest)) + '</p>';
			}
			if (data.categories && data.categories.length) {
				html += '<div class="ss-group"><div class="ss-head"><span>' + esc(t.cats) + '</span></div><ul class="ss-cats">';
				data.categories.forEach(function (c) {
					html += '<li><a role="option" id="' + idFor(n++) + '" class="ss-cat" href="' + esc(c.url) + '"><span>' + highlight(c.name, used) + '</span><small>' + c.count + ' ' + esc(t.items) + '</small></a></li>';
				});
				html += '</ul></div>';
			}
			if (data.products && data.products.length) {
				html += '<div class="ss-group"><div class="ss-head"><span>' + esc(t.products) + '</span></div><ul class="ss-products">';
				data.products.forEach(function (p) {
					html += '<li><a role="option" id="' + idFor(n++) + '" class="ss-product" href="' + esc(p.url) + '">' +
						'<img src="' + esc(p.image) + '" alt="" width="56" height="56" loading="lazy">' +
						'<span class="ss-product__body"><span class="ss-product__name">' + highlight(p.name, used) + '</span>' +
						(p.category ? '<span class="ss-product__cat">' + esc(p.category) + '</span>' : '') + '</span>' +
						'<span class="ss-product__price">' + (p.stock ? esc(p.price) : '<em>' + esc(t.soldOut) + '</em>') + '</span></a></li>';
				});
				html += '</ul></div>';
				html += '<a role="option" id="' + idFor(n++) + '" class="ss-all" href="' + esc(data.url) + '" data-q="' + esc(used) + '">' +
					esc(t.seeAll.replace('%1$s', data.total).replace('%2$s', used)) + ' <span aria-hidden="true">&rarr;</span></a>';
			} else {
				html += '<p class="ss-empty">' + esc(t.none.replace('%s', q)) + '</p>';
			}
			panel.innerHTML = html;
			openPanel();
		}

		function run(q) {
			if (cache[q]) {
				renderResults(cache[q]);
				return;
			}
			if (controller) { controller.abort(); }
			controller = typeof AbortController === 'function' ? new AbortController() : null;
			root.classList.add('is-loading');
			var url = cfg.endpoint + (cfg.endpoint.indexOf('?') > -1 ? '&' : '?') + 'q=' + encodeURIComponent(q);
			fetch(url, { credentials: 'same-origin', signal: controller ? controller.signal : undefined })
				.then(function (r) { return r.json(); })
				.then(function (data) {
					root.classList.remove('is-loading');
					if (input.value.trim() !== q) { return; }
					cache[q] = data;
					renderResults(data);
				})
				.catch(function (err) {
					if (err && err.name === 'AbortError') { return; }
					root.classList.remove('is-loading');
				});
		}

		function onInput() {
			var q = input.value.trim();
			clearBtn.hidden = input.value === '';
			window.clearTimeout(timer);
			if (q.length < 2) {
				root.classList.remove('is-loading');
				renderStart();
				return;
			}
			timer = window.setTimeout(function () { run(q); }, 180);
		}

		input.addEventListener('input', onInput);
		input.addEventListener('focus', function () {
			if (input.value.trim().length < 2) {
				renderStart();
			} else {
				onInput();
			}
		});

		input.addEventListener('keydown', function (e) {
			if (panel.hidden && (e.key === 'ArrowDown' || e.key === 'ArrowUp')) {
				onInput();
				return;
			}
			if (e.key === 'ArrowDown') {
				e.preventDefault();
				setActive(active + 1);
			} else if (e.key === 'ArrowUp') {
				e.preventDefault();
				setActive(active - 1);
			} else if (e.key === 'Enter' && active > -1) {
				var opt = options()[active];
				if (opt) {
					e.preventDefault();
					if (opt.getAttribute('data-q')) { saveRecent(opt.getAttribute('data-q')); }
					window.location.href = opt.href;
				}
			} else if (e.key === 'Escape') {
				if (panel.hidden === false) {
					e.preventDefault();
					closePanel();
				}
			}
		});

		clearBtn.addEventListener('click', function () {
			input.value = '';
			clearBtn.hidden = true;
			input.focus();
			renderStart();
		});

		form.addEventListener('submit', function (e) {
			var q = input.value.trim();
			if (q === '') {
				e.preventDefault();
				input.focus();
				return;
			}
			saveRecent(q);
		});

		panel.addEventListener('click', function (e) {
			if (e.target.closest('.ss-clear-recent')) {
				e.preventDefault();
				try { window.localStorage.removeItem(RECENT_KEY); } catch (err) { /* ignore */ }
				renderStart();
				input.focus();
				return;
			}
			var link = e.target.closest('a[data-q]');
			if (link) {
				saveRecent(link.getAttribute('data-q'));
			}
		});

		panel.addEventListener('mousedown', function (e) {
			// Keep focus in the box so the panel does not close before the click lands.
			if (e.target.closest('a, button')) { e.preventDefault(); }
		});

		document.addEventListener('click', function (e) {
			if (root.contains(e.target) === false) { closePanel(); }
		});

		clearBtn.hidden = input.value === '';
	}

	function init() {
		Array.prototype.forEach.call(document.querySelectorAll('[data-shop-search]'), setup);

		// Sorting: apply as soon as a new option is picked.
		Array.prototype.forEach.call(document.querySelectorAll('.woocommerce-ordering select.orderby'), function (sel) {
			sel.addEventListener('change', function () {
				if (sel.form) { sel.form.submit(); }
			});
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
}());
