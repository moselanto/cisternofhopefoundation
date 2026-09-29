/*
 * COHF - giving form.
 *
 * Hands off to Paystack Inline. No card or M-Pesa detail is ever handled by
 * this site: Paystack collects it inside its own secure iframe.
 */
(function () {
  'use strict';

  var form = document.getElementById('cohf-give');
  if (form === null) return;

  var custom = form.querySelector('#cohf-give-custom');
  var errBox = form.querySelector('.give__error');
  var submit = form.querySelector('.give__submit');

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
      if (r.checked && custom) custom.value = '';
    });
  });

  function chosenAmount() {
    if (custom && custom.value.length > 0) return parseInt(custom.value, 10);
    var sel = form.querySelector('input[name="cohf_amount"]:checked');
    return sel ? parseInt(sel.value, 10) : 0;
  }

  function fail(msg) {
    errBox.textContent = msg;
    errBox.hidden = false;
    errBox.focus();
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    errBox.hidden = true;

    var amount = chosenAmount();
    var name = form.querySelector('#cohf-give-name').value.trim();
    var email = form.querySelector('#cohf-give-email').value.trim();
    var area = form.querySelector('#cohf-give-area');
    var phoneEl = form.querySelector('#cohf-give-phone');
    var phone = phoneEl ? phoneEl.value.trim() : '';
    var freqEl = form.querySelector('input[name="cohf_freq"]:checked');
    var freq = freqEl ? freqEl.value : 'once';

    if (isNaN(amount) || amount < 50) { fail('Please choose or enter an amount of at least 50 KES.'); return; }
    if (name.length === 0) { fail('Please enter your name so we can receipt your gift.'); return; }
    if (email.indexOf('@') < 1) { fail('Please enter a valid email address for your receipt.'); return; }
    if (typeof window.PaystackPop === 'undefined') {
      fail('The secure payment library could not be loaded. Please check your connection and try again.');
      return;
    }

    submit.disabled = true;
    submit.textContent = 'Opening secure payment...';

    var opts = {
      key: form.dataset.key,
      email: email,
      amount: amount * 100,               // Paystack expects the minor unit
      currency: form.dataset.currency || 'KES',
      metadata: {
        custom_fields: [
          { display_name: 'Donor name', variable_name: 'donor_name', value: name },
          { display_name: 'Donor phone', variable_name: 'donor_phone', value: phone },
          { display_name: 'Support area', variable_name: 'support_area', value: area ? area.options[area.selectedIndex].text : 'Where needed most' },
          { display_name: 'Gift type', variable_name: 'gift_type', value: freq === 'monthly' ? 'Monthly' : 'One-off' },
          { display_name: 'Source', variable_name: 'source', value: 'Website - Support Our Work' }
        ]
      },
      onClose: function () {
        submit.disabled = false;
        submit.textContent = 'Continue to secure giving';
      },
      callback: function (response) {
        window.location.href = form.dataset.thanks
          ? form.dataset.thanks + '?ref=' + encodeURIComponent(response.reference)
          : window.location.pathname + '?giving=thank-you&ref=' + encodeURIComponent(response.reference);
      }
    };

    if (freq === 'monthly' && form.dataset.plan) opts.plan = form.dataset.plan;

    try {
      window.PaystackPop.setup(opts).openIframe();
    } catch (err) {
      submit.disabled = false;
      submit.textContent = 'Continue to secure giving';
      fail('We could not open the secure payment window. Please try again or contact the Foundation.');
    }
  });
})();
