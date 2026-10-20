/*!
 * COHF staff login: Caps Lock hint, busy state on submit, 2FA code helpers.
 * Progressive enhancement only - the form works fully without this file.
 */
(function () {
  'use strict';
  var t = window.cohfLogin || {};

  /* Caps Lock warning under any password field. */
  Array.prototype.forEach.call(document.querySelectorAll('input[type="password"]'), function (input) {
    var hint = document.createElement('p');
    hint.className = 'cohf-lg-caps';
    hint.setAttribute('role', 'status');
    hint.hidden = true;
    hint.textContent = t.caps || 'Caps Lock is on';
    var wrap = input.closest('.wp-pwd') || input;
    wrap.parentNode.insertBefore(hint, wrap.nextSibling);
    function check(e) {
      if (e.getModifierState) { hint.hidden = !e.getModifierState('CapsLock'); }
    }
    input.addEventListener('keyup', check);
    input.addEventListener('keydown', check);
    input.addEventListener('blur', function () { hint.hidden = true; });
  });

  /* Busy state so a slow network never invites a second click. */
  Array.prototype.forEach.call(document.querySelectorAll('#loginform, #lostpasswordform, #resetpassform'), function (form) {
    form.addEventListener('submit', function () {
      var btn = form.querySelector('[type="submit"]');
      if (!btn || btn.getAttribute('aria-busy') === 'true') { return; }
      btn.setAttribute('aria-busy', 'true');
      var label = form.id === 'lostpasswordform' ? t.sending : (document.getElementById('authcode') ? t.verify : t.signing);
      if (label) { btn.value = label; }
      /* Re-enable if the browser stays on the page (validation error). */
      setTimeout(function () { btn.removeAttribute('aria-busy'); }, 8000);
    });
  });

  /* 2FA code field: digits only, one-time-code autofill, submit when complete. */
  var code = document.getElementById('authcode');
  if (code) {
    code.setAttribute('inputmode', 'numeric');
    code.setAttribute('autocomplete', 'one-time-code');
    code.setAttribute('autocapitalize', 'off');
    code.setAttribute('spellcheck', 'false');
    code.focus();
    code.addEventListener('input', function () {
      var digits = code.value.replace(/\D/g, '');
      if (/^\d+$/.test(code.value.replace(/\s/g, '')) || digits.length === code.value.length) {
        code.value = digits.slice(0, 8);
      }
      if (/^\d{6}$/.test(code.value) && code.form && !code.form.dataset.sent) {
        code.form.dataset.sent = '1';
        if (code.form.requestSubmit) { code.form.requestSubmit(); } else { code.form.submit(); }
      }
    });
  }
})();
