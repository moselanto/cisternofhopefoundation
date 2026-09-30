/**
 * Photo gallery: filters and lightbox.
 * Keyboard (Esc, arrows), swipe, focus trap and focus return.
 */
(function () {
	'use strict';

	var grid = document.querySelector('.gallery-grid');
	var box = document.getElementById('cohf-lightbox');
	if (!grid || !box) { return; }

	var links = Array.prototype.slice.call(grid.querySelectorAll('.gallery-item__link'));
	var filters = Array.prototype.slice.call(document.querySelectorAll('.gallery-filter'));
	var status = document.querySelector('.gallery-status');
	var img = box.querySelector('.lightbox__img');
	var cat = box.querySelector('.lightbox__cat');
	var title = box.querySelector('.lightbox__title');
	var text = box.querySelector('.lightbox__text');
	var counter = box.querySelector('.lightbox__counter');
	var prevBtn = box.querySelector('.lightbox__prev');
	var nextBtn = box.querySelector('.lightbox__next');
	var visible = links.slice();
	var current = 0;
	var opener = null;

	/* ---- Filters ---- */
	filters.forEach(function (btn) {
		btn.addEventListener('click', function () {
			var f = btn.getAttribute('data-filter');
			filters.forEach(function (b) {
				var on = b === btn;
				b.classList.toggle('is-active', on);
				b.setAttribute('aria-pressed', on ? 'true' : 'false');
			});
			visible = [];
			links.forEach(function (a) {
				var item = a.parentNode;
				var show = f === 'all' || item.getAttribute('data-cat') === f;
				item.classList.toggle('is-hidden', !show);
				if (show) { visible.push(a); }
			});
			if (status) { status.textContent = visible.length + ' photographs shown'; }
		});
	});

	/* ---- Reveal on scroll ---- */
	if ('IntersectionObserver' in window) {
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) {
				if (e.isIntersecting) { e.target.classList.add('is-in'); io.unobserve(e.target); }
			});
		}, { rootMargin: '0px 0px -40px 0px' });
		links.forEach(function (a) { a.parentNode.classList.add('will-reveal'); io.observe(a.parentNode); });
	}

	/* ---- Lightbox ---- */
	function show(i) {
		if (!visible.length) { return; }
		current = (i + visible.length) % visible.length;
		var a = visible[current];
		var thumb = a.querySelector('img');
		box.classList.add('is-loading');
		img.onload = function () { box.classList.remove('is-loading'); };
		img.src = a.getAttribute('href');
		img.alt = thumb ? thumb.alt : '';
		cat.textContent = a.getAttribute('data-category') || '';
		title.textContent = a.getAttribute('data-title') || '';
		text.textContent = a.getAttribute('data-caption') || '';
		counter.textContent = (current + 1) + ' / ' + visible.length;
		var single = visible.length < 2;
		prevBtn.hidden = single;
		nextBtn.hidden = single;
		// Preload neighbours.
		[current + 1, current - 1].forEach(function (n) {
			var l = visible[(n + visible.length) % visible.length];
			if (l) { (new Image()).src = l.getAttribute('href'); }
		});
	}

	function open(a) {
		opener = a;
		var idx = visible.indexOf(a);
		box.hidden = false;
		document.body.classList.add('cohf-lightbox-open');
		requestAnimationFrame(function () { box.classList.add('is-open'); });
		show(idx < 0 ? 0 : idx);
		box.querySelector('.lightbox__close').focus();
	}

	function close() {
		box.classList.remove('is-open');
		document.body.classList.remove('cohf-lightbox-open');
		setTimeout(function () { box.hidden = true; img.src = ''; }, 250);
		if (opener) { opener.focus(); }
	}

	links.forEach(function (a) {
		a.addEventListener('click', function (e) {
			if (e.metaKey || e.ctrlKey || e.shiftKey) { return; }
			e.preventDefault();
			open(a);
		});
	});

	prevBtn.addEventListener('click', function () { show(current - 1); });
	nextBtn.addEventListener('click', function () { show(current + 1); });
	Array.prototype.forEach.call(box.querySelectorAll('[data-close]'), function (el) {
		el.addEventListener('click', close);
	});

	document.addEventListener('keydown', function (e) {
		if (box.hidden) { return; }
		if (e.key === 'Escape') { close(); }
		else if (e.key === 'ArrowLeft') { show(current - 1); }
		else if (e.key === 'ArrowRight') { show(current + 1); }
		else if (e.key === 'Tab') {
			var f = Array.prototype.filter.call(box.querySelectorAll('button'), function (b) { return !b.hidden; });
			if (!f.length) { return; }
			var first = f[0], last = f[f.length - 1];
			if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
			else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
		}
	});

	/* ---- Swipe ---- */
	var x0 = null, y0 = null;
	box.addEventListener('touchstart', function (e) {
		x0 = e.touches[0].clientX; y0 = e.touches[0].clientY;
	}, { passive: true });
	box.addEventListener('touchend', function (e) {
		if (x0 === null) { return; }
		var dx = e.changedTouches[0].clientX - x0;
		var dy = e.changedTouches[0].clientY - y0;
		if (Math.abs(dx) > 45 && Math.abs(dx) > Math.abs(dy)) { show(current + (dx < 0 ? 1 : -1)); }
		else if (dy > 90 && Math.abs(dy) > Math.abs(dx)) { close(); }
		x0 = y0 = null;
	}, { passive: true });
})();
