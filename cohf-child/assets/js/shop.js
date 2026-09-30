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
