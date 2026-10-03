/*
 * COHF - giving form.
 *
 * This script no longer decides what a gift costs.
 *
 * It used to hand an amount straight to Paystack Inline, which meant the
 * price of a donation was set in the donor's own browser. Now it posts the
 * donor's choices to the site's own endpoint, the server fixes the amount and
 * initialises the transaction with Paystack, and only an access code comes
 * back. The browser never states a price again, so there is nothing here for
 * anyone to tamper with.
 *
 * No card or M-Pesa detail is ever handled by this site: Paystack collects it
 * inside its own secure iframe.
 */
(function () {
  'use strict';

  var form = document.getElementById('cohf-give');
  if (form === null) { return; }

  var custom = form.querySelector('#cohf-give-custom');
  var errBox = form.querySelector('.give__error');
  var submit = form.querySelector('.give__submit');
  var restLabel = submit ? submit.textContent : 'Donate now';

  function presets() {
    return Array.prototype.slice.call(form.querySelectorAll('input[name="cohf_amount"]'));
  }

  // Typing a custom amount clears the preset choice, and vice versa.
  if (custom) {
    custom.addEventListener('input', function () {
      if (custom.value.length > 0) {
        presets().forEach(function (r) { r.checked = false; });
      }
    });
  }

  presets().forEach(function (r) {
    r.addEventListener('change', function () {
      if (r.checked && custom) { custom.value = ''; }
    });
  });

  function chosenAmount() {
    if (custom && custom.value.length > 0) { return parseInt(custom.value, 10); }
    var sel = form.querySelector('input[name="cohf_amount"]:checked');
    return sel ? parseInt(sel.value, 10) : 0;
  }

  function idle() {
    submit.disabled = false;
    submit.textContent = restLabel;
  }

  function fail(msg) {
    errBox.textContent = msg;
    errBox.hidden = false;
    idle();
  }

  function val(selector) {
    var el = form.querySelector(selector);
    return el ? el.value.trim() : '';
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    errBox.hidden = true;

    var amount = chosenAmount();
    var name = val('#cohf-give-name');
    var email = val('#cohf-give-email');

    // Checked here only to save a round trip and give an instant answer.
    // The server validates all of it again and trusts none of it.
    if (isNaN(amount) || amount < 50) {
      fail('Please choose or enter an amount of at least KES 50.');
      return;
    }
    if (name.length === 0) {
      fail('Please enter your name so we can receipt your gift.');
      return;
    }
    if (email.indexOf('@') < 1) {
      fail('Please enter a valid email address for your receipt.');
      return;
    }

    var areaEl = form.querySelector('#cohf-give-area');
    var anonEl = form.querySelector('#cohf-give-anon');

    submit.disabled = true;
    submit.textContent = 'Preparing secure payment...';

    // 14.3.0: always use a fresh nonce so a cached page never blocks a gift.
    var freshNonce = function () {
      if (typeof form.dataset.nonceUrl === 'undefined') { return Promise.resolve(form.dataset.nonce); }
      return window.fetch(form.dataset.nonceUrl, { cache: 'no-store', credentials: 'same-origin' })
        .then(function (r) { return r.ok ? r.json() : null; })
        .then(function (b) { return b && b.nonce ? b.nonce : form.dataset.nonce; })
        .catch(function () { return form.dataset.nonce; });
    };

    freshNonce().then(function (nonce) { return window.fetch(form.dataset.endpoint, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        nonce: nonce,
        amount: amount,
        name: name,
        email: email,
        phone: val('#cohf-give-phone'),
        note: val('#cohf-give-note'),
        area: areaEl ? areaEl.options[areaEl.selectedIndex].text : '',
        anonymous: anonEl ? anonEl.checked : false
      })
    }); }).then(function (res) {
      return res.json().then(function (body) {
        return { ok: res.ok, body: body };
      });
    }).then(function (r) {
      if (r.ok === false) {
        fail(r.body && r.body.message ? r.body.message : 'We could not start this payment. Please try again.');
        return;
      }

      if (typeof window.PaystackPop === 'undefined') {
        fail('The secure payment library could not be loaded. Please check your connection and try again.');
        return;
      }

      submit.textContent = 'Opening secure payment...';

      /*
       * Inline v2, resumed from the access code the server obtained.
       * Paystack redirects to the callback_url set during initialisation
       * when payment finishes, so no success callback is relied on here:
       * an M-Pesa STK push often takes the donor out of the browser
       * entirely, and a callback that never fires would lose the gift.
       */
      var popup = new window.PaystackPop();
      popup.resumeTransaction(r.body.access_code);
    }).catch(function () {
      fail('We could not reach the payment service. Please try again in a moment.');
    });
  });
})();
