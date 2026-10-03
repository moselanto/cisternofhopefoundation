/**
 * Enquiry forms: friendly inline validation, message counter and a sending
 * state on the button. The server re-checks everything (inc/forms.php).
 */
(function () {
	'use strict';

	var MSG = {
		required: 'Please fill in this field.',
		email: 'Please enter a valid email address, like name@email.com.',
		phone: 'Please enter a valid phone number, or leave it blank.',
		consent: 'Please tick this box so we can reply to you.'
	};

	function errorEl(field) {
		var wrap = field.closest('.field');
		if (wrap === null) { return null; }
		var el = wrap.querySelector('.field__error');
		if (el === null) {
			el = document.createElement('span');
			el.className = 'field__error';
			el.id = (field.id || 'f') + '-error';
			el.setAttribute('role', 'alert');
			wrap.appendChild(el);
		}
		return el;
	}

	function setError(field, text) {
		var el = errorEl(field);
		var wrap = field.closest('.field');
		if (text) {
			field.setAttribute('aria-invalid', 'true');
			if (el) {
				el.textContent = text;
				field.setAttribute('aria-describedby', el.id);
			}
			if (wrap) { wrap.classList.add('has-error'); }
		} else {
			field.removeAttribute('aria-invalid');
			if (el) { el.textContent = ''; }
			if (wrap) { wrap.classList.remove('has-error'); }
		}
	}

	function check(field) {
		var v = (field.value || '').trim();
		if (field.type === 'checkbox') {
			return field.required && field.checked === false ? MSG.consent : '';
		}
		if (field.required && v === '') { return MSG.required; }
		if (field.type === 'email' && v !== '' && /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v) === false) { return MSG.email; }
		if (field.type === 'tel' && v !== '' && /^[0-9+()\s.-]{6,30}$/.test(v) === false) { return MSG.phone; }
		return '';
	}

	function setup(form) {
		var fields = form.querySelectorAll('input:not([type="hidden"]):not([tabindex="-1"]), textarea, select');
		var btn = form.querySelector('.cohf-submit');
		var msg = form.querySelector('textarea[maxlength]');
		var counter = form.querySelector('[data-count]');

		Array.prototype.forEach.call(fields, function (f) {
			f.addEventListener('blur', function () {
				if (f.value !== '' || f.getAttribute('aria-invalid') === 'true') { setError(f, check(f)); }
			});
			f.addEventListener('input', function () {
				if (f.getAttribute('aria-invalid') === 'true') { setError(f, check(f)); }
			});
			f.addEventListener('change', function () {
				if (f.type === 'checkbox') { setError(f, check(f)); }
			});
		});

		if (msg && counter) {
			var update = function () {
				counter.textContent = msg.value.length.toLocaleString();
				counter.parentNode.classList.toggle('is-near', msg.value.length > 4500);
			};
			msg.addEventListener('input', update);
			update();
		}

		form.addEventListener('submit', function (e) {
			var first = null;
			Array.prototype.forEach.call(fields, function (f) {
				var wrap = f.closest('.field');
				if (wrap && wrap.hidden) { return; }
				var err = check(f);
				setError(f, err);
				if (err && first === null) { first = f; }
			});
			if (first) {
				e.preventDefault();
				first.focus();
				return;
			}
			if (btn) {
				if (btn.getAttribute('aria-busy') === 'true') {
					e.preventDefault();
					return;
				}
				btn.setAttribute('aria-busy', 'true');
				btn.classList.add('is-loading');
				var label = btn.querySelector('span');
				if (label) { label.textContent = 'Sending...'; }
			}
		});
	}

	/* Human check and fresh security tokens (13.89.0): the first time a
	   visitor interacts with a form, mark it as touched by a person and
	   collect a fresh token, so cached pages never send an expired one. */
	var started = Date.now();
	var fetched = false;

	function refreshTokens(url) {
		if (fetched || typeof window.fetch === 'undefined' || url === null || url === '') { return; }
		fetched = true;
		window.fetch(url, { credentials: 'same-origin', cache: 'no-store' })
			.then(function (r) { return r.ok ? r.json() : null; })
			.then(function (d) {
				if (d === null || typeof d.ts === 'undefined') { return; }
				Array.prototype.forEach.call(document.querySelectorAll('input[name="cohf_ts"]'), function (i) { i.value = d.ts; });
				Array.prototype.forEach.call(document.querySelectorAll('input[name="cohf_enquiry_nonce"]'), function (i) { i.value = d.enquiry; });
				var give = document.getElementById('cohf-give');
				if (give && d.giving) { give.dataset.nonce = d.giving; }
			})
			.catch(function () { fetched = false; });
	}

	function guard(form) {
		var token = form.querySelector('input[name="cohf_js"]');
		var mark = function () {
			if (token && token.value === '') { token.value = 'h' + Math.round((Date.now() - started) / 1000); }
			refreshTokens(form.getAttribute('data-token-url'));
		};
		['focusin', 'pointerdown', 'keydown', 'touchstart'].forEach(function (ev) {
			form.addEventListener(ev, mark, { passive: true });
		});
	}

	function init() {
		Array.prototype.forEach.call(document.querySelectorAll('[data-cohf-form]'), setup);
		Array.prototype.forEach.call(document.querySelectorAll('[data-cohf-form]'), guard);
		var notice = document.querySelector('.form-notice');
		if (notice) {
			notice.scrollIntoView({ block: 'center' });
			notice.focus({ preventScroll: true });
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
}());
