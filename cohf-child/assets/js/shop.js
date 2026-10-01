/**
 * Hope Market - visible scrolling for the category tiles and category chips.
 *
 * On small screens both rows scroll sideways. A swipe is not obvious to
 * everyone, so each row gets left/right arrow buttons and an always-visible
 * scroll bar showing how much more there is. Nothing is added when a row
 * fits on screen, and without JavaScript the rows still scroll by swipe.
 */
(function () {
	'use strict';

	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function setup(list) {
		if (list.getAttribute('data-hscroll') === '1') {
			return;
		}
		list.setAttribute('data-hscroll', '1');

		var wrap = document.createElement('div');
		wrap.className = 'hscroll';
		list.parentNode.insertBefore(wrap, list);
		wrap.appendChild(list);

		function makeButton(dir, label) {
			var b = document.createElement('button');
			b.type = 'button';
			b.className = 'hscroll__btn hscroll__btn--' + dir;
			b.setAttribute('aria-label', label);
			b.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="' +
				(dir === 'prev' ? 'M15 5l-7 7 7 7' : 'M9 5l7 7-7 7') + '"/></svg>';
			b.addEventListener('click', function () {
				var step = Math.max(list.clientWidth * 0.8, 160) * (dir === 'prev' ? -1 : 1);
				if (typeof list.scrollBy === 'function') {
					list.scrollBy({ left: step, behavior: reduce ? 'auto' : 'smooth' });
				} else {
					list.scrollLeft += step;
				}
			});
			wrap.appendChild(b);
			return b;
		}

		var prev = makeButton('prev', 'Scroll left');
		var next = makeButton('next', 'Scroll right');

		var bar = document.createElement('div');
		bar.className = 'hscroll__bar';
		bar.setAttribute('aria-hidden', 'true');
		var thumb = document.createElement('span');
		bar.appendChild(thumb);
		wrap.appendChild(bar);

		function update() {
			var max = list.scrollWidth - list.clientWidth;
			var over = max > 4;
			var atStart = list.scrollLeft <= 4;
			var atEnd = list.scrollLeft >= max - 4;

			wrap.classList.toggle('is-overflowing', over);
			wrap.classList.toggle('at-start', atStart);
			wrap.classList.toggle('at-end', atEnd);
			prev.disabled = over === false || atStart;
			next.disabled = over === false || atEnd;

			if (over) {
				var size = (list.clientWidth / list.scrollWidth) * 100;
				var pos = (list.scrollLeft / list.scrollWidth) * 100;
				thumb.style.width = size + '%';
				thumb.style.left = Math.min(pos, 100 - size) + '%';
			}
		}

		list.addEventListener('scroll', update, { passive: true });
		window.addEventListener('resize', update);
		window.addEventListener('load', update);
		update();
	}

	function init() {
		var lists = document.querySelectorAll('.shop-cat-tiles__grid, .shop-cats');
		Array.prototype.forEach.call(lists, setup);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
}());

/**
 * Hope Market premium layer (12.0.0): Filter & sort sheet on phones and
 * "Load more" on listing pages.
 */
(function () {
	'use strict';

	/* ---- Filter & sort sheet ---- */
	var sheet = document.getElementById('cohf-shop-sheet');
	var opener = document.querySelector('[data-sheet-open]');
	var lastFocus = null;

	function onKey(e) {
		if (e.key === 'Escape') { closeSheet(); }
	}

	function openSheet() {
		if (sheet === null) { return; }
		lastFocus = document.activeElement;
		sheet.hidden = false;
		window.requestAnimationFrame(function () { sheet.classList.add('is-open'); });
		document.documentElement.classList.add('shop-sheet-open');
		if (opener) { opener.setAttribute('aria-expanded', 'true'); }
		document.addEventListener('keydown', onKey);
		var panel = sheet.querySelector('.shop-sheet__panel');
		if (panel) { panel.focus({ preventScroll: true }); }
	}

	function closeSheet() {
		if (sheet === null || sheet.hidden) { return; }
		sheet.classList.remove('is-open');
		document.documentElement.classList.remove('shop-sheet-open');
		if (opener) { opener.setAttribute('aria-expanded', 'false'); }
		document.removeEventListener('keydown', onKey);
		window.setTimeout(function () { sheet.hidden = true; }, 260);
		if (lastFocus && typeof lastFocus.focus === 'function') { lastFocus.focus({ preventScroll: true }); }
	}

	document.addEventListener('click', function (e) {
		if (e.target.closest('[data-sheet-open]')) {
			e.preventDefault();
			openSheet();
			return;
		}
		if (e.target.closest('[data-sheet-close]')) {
			e.preventDefault();
			closeSheet();
		}
	});

	/* ---- Load more ---- */
	var box = document.querySelector('[data-shop-loadmore]');
	if (box === null || typeof window.fetch !== 'function' || typeof window.DOMParser !== 'function') {
		return;
	}
	var grid = document.querySelector('ul.products');
	var pagination = document.querySelector('.woocommerce-pagination');
	var btn = box.querySelector('.shop-loadmore__btn');
	if (btn && pagination) { pagination.hidden = true; }
	if (btn === null || grid === null) { return; }

	var total = parseInt(box.getAttribute('data-total'), 10) || 0;
	var shownEl = box.querySelector('[data-shown]');
	var bar = box.querySelector('.shop-loadmore__bar span');

	btn.addEventListener('click', function (e) {
		e.preventDefault();
		var next = btn.getAttribute('data-next');
		if (next === null || btn.classList.contains('is-loading')) { return; }
		btn.classList.add('is-loading');
		btn.setAttribute('aria-busy', 'true');
		fetch(next, { credentials: 'same-origin' })
			.then(function (r) { return r.text(); })
			.then(function (html) {
				var doc = new DOMParser().parseFromString(html, 'text/html');
				var items = doc.querySelectorAll('ul.products > li');
				var first = null;
				Array.prototype.forEach.call(items, function (li) {
					var node = document.importNode(li, true);
					node.classList.add('is-new-load');
					grid.appendChild(node);
					if (first === null) { first = node; }
				});
				var count = grid.querySelectorAll(':scope > li').length;
				if (shownEl) { shownEl.textContent = count.toLocaleString(); }
				if (bar && total) { bar.style.width = Math.min(100, (count / total) * 100) + '%'; }
				var nextBtn = doc.querySelector('[data-shop-loadmore] .shop-loadmore__btn');
				if (nextBtn) {
					btn.setAttribute('data-next', nextBtn.getAttribute('data-next'));
					btn.href = nextBtn.getAttribute('data-next');
				} else {
					btn.remove();
				}
				if (window.history && typeof window.history.replaceState === 'function') {
					window.history.replaceState(null, '', next);
				}
				if (first) {
					var link = first.querySelector('a');
					if (link) { link.focus({ preventScroll: true }); }
				}
			})
			.catch(function () { window.location.href = next; })
			.then(function () {
				btn.classList.remove('is-loading');
				btn.removeAttribute('aria-busy');
			});
	});
}());
