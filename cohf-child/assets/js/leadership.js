/**
 * Leadership profile panel.
 *
 * Opens a leader's full biography in a modal panel. Written for keyboard and
 * screen-reader users first: focus moves into the panel, is trapped while it
 * is open, Escape closes it, and focus returns to the button that opened it.
 */
(function () {
	'use strict';

	var panel = document.getElementById('cohf-leader-panel');
	if (panel === null) {
		return;
	}

	var dialog    = panel.querySelector('.leader-panel__dialog');
	var nameEl    = panel.querySelector('.leader-panel__name');
	var roleEl    = panel.querySelector('.leader-panel__role');
	var bioEl     = panel.querySelector('.leader-panel__bio');
	var imgEl     = panel.querySelector('.leader-panel__img');
	var monoEl    = panel.querySelector('.leader-panel__monogram');
	var lastFocus = null;

	function initials(name) {
		var skip = ['mr', 'mrs', 'ms', 'dr', 'prof', 'rev', 'pastor', 'eng'];
		var out = '';
		String(name).split(/\s+/).forEach(function (part) {
			var clean = part.replace(/[^A-Za-z]/g, '');
			if (clean === '') {
				return;
			}
			if (skip.indexOf(clean.toLowerCase()) > -1) {
				return;
			}
			if (out.length < 2) {
				out += clean.charAt(0).toUpperCase();
			}
		});
		return out;
	}

	function focusables() {
		return Array.prototype.slice.call(
			dialog.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])')
		).filter(function (el) {
			return el.offsetParent !== null;
		});
	}

	function open(btn) {
		lastFocus = btn;

		var name  = btn.getAttribute('data-leader-name') || '';
		var role  = btn.getAttribute('data-leader-role') || '';
		var bio   = btn.getAttribute('data-leader-bio') || '';
		var photo = btn.getAttribute('data-leader-photo') || '';

		nameEl.textContent = name;
		roleEl.textContent = role;
		roleEl.hidden = (role === '');
		bioEl.textContent = bio;

		if (photo === '') {
			imgEl.hidden = true;
			imgEl.removeAttribute('src');
			monoEl.textContent = initials(name);
			monoEl.hidden = false;
		} else {
			imgEl.src = photo;
			imgEl.alt = name;
			imgEl.hidden = false;
			monoEl.hidden = true;
		}

		panel.hidden = false;
		document.body.classList.add('cohf-panel-open');

		var f = focusables();
		if (f.length > 0) {
			f[0].focus();
		} else {
			dialog.focus();
		}
	}

	function close() {
		panel.hidden = true;
		document.body.classList.remove('cohf-panel-open');
		if (lastFocus !== null && typeof lastFocus.focus === 'function') {
			lastFocus.focus();
		}
		lastFocus = null;
	}

	document.addEventListener('click', function (e) {
		var btn = e.target.closest('.leader-card__more');
		if (btn !== null) {
			e.preventDefault();
			open(btn);
			return;
		}
		if (e.target.closest('[data-leader-close]') !== null) {
			e.preventDefault();
			close();
		}
	});

	document.addEventListener('keydown', function (e) {
		if (panel.hidden === true) {
			return;
		}
		if (e.key === 'Escape') {
			e.preventDefault();
			close();
			return;
		}
		if (e.key === 'Tab') {
			var f = focusables();
			if (f.length === 0) {
				return;
			}
			var first = f[0];
			var last  = f[f.length - 1];
			if (e.shiftKey === true && document.activeElement === first) {
				e.preventDefault();
				last.focus();
			} else if (e.shiftKey === false && document.activeElement === last) {
				e.preventDefault();
				first.focus();
			}
		}
	});
}());
