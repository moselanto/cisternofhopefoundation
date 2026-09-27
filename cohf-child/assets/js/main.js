/*!
 * COHF — progressive enhancement.
 * No framework, no dependencies. ~4 KB unminified.
 * Every behaviour degrades gracefully without JavaScript.
 */
(function () {
  'use strict';

  document.documentElement.classList.remove('no-js');

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------- Mobile navigation ----------
     Toggles the .links panel on small screens. Deliberately class-only: the
     panel is the same <nav> used on desktop, so setting the hidden attribute
     would remove the desktop navigation as well. */
  function initNav() {
    var toggle = document.querySelector('[data-nav-toggle]');
    var panel = document.getElementById('cohf-primary-nav');
    if (!toggle || !panel) return;

    function setOpen(open) {
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      panel.classList.toggle('is-open', open);
      document.body.classList.toggle('nav-open', open);
    }

    toggle.addEventListener('click', function () {
      setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    panel.addEventListener('click', function (e) {
      if (e.target.closest('a')) setOpen(false);
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
        setOpen(false);
        toggle.focus();
      }
    });

    document.addEventListener('click', function (e) {
      if (toggle.getAttribute('aria-expanded') !== 'true') return;
      if (!panel.contains(e.target) && !toggle.contains(e.target)) setOpen(false);
    });

    // Leaving the mobile breakpoint must clear the open state.
    var mq = window.matchMedia('(min-width: 901px)');
    var onChange = function () { if (mq.matches) setOpen(false); };
    if (mq.addEventListener) mq.addEventListener('change', onChange);
    else if (mq.addListener) mq.addListener(onChange);
  }

  /* ---------- Sticky header state + mobile CTA bar ---------- */
  function initScrollState() {
    var header = document.querySelector('.site-header');
    var ctaBar = document.querySelector('.mobile-cta-bar');
    var ticking = false;

    function update() {
      var y = window.scrollY || 0;
      if (header) header.classList.toggle('is-stuck', y > 8);
      if (ctaBar) ctaBar.classList.toggle('is-visible', y > 600);
      ticking = false;
    }
    window.addEventListener('scroll', function () {
      if (!ticking) { window.requestAnimationFrame(update); ticking = true; }
    }, { passive: true });
    update();
  }

  /* ---------- Reveal on scroll ---------- */
  function initReveal() {
    var items = document.querySelectorAll('[data-reveal], .journey__step, .timeline__item');
    if (!items.length) return;
    if (reduceMotion || !('IntersectionObserver' in window)) {
      items.forEach(function (el) { el.classList.add('is-inview'); });
      return;
    }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        var el = entry.target;
        var delay = parseInt(el.getAttribute('data-reveal-delay') || '0', 10);
        window.setTimeout(function () { el.classList.add('is-inview'); }, delay);
        io.unobserve(el);
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.12 });
    items.forEach(function (el) { io.observe(el); });
  }

  /* ---------- Number counters ---------- */
  function initCounters() {
    var counters = document.querySelectorAll('[data-count-to]');
    if (!counters.length) return;

    function run(el) {
      var target = parseFloat(el.getAttribute('data-count-to'));
      if (isNaN(target)) return;
      if (reduceMotion) { el.textContent = String(target); return; }
      var duration = 900;
      var start = null;
      function step(ts) {
        if (start === null) start = ts;
        var p = Math.min((ts - start) / duration, 1);
        var eased = 1 - Math.pow(1 - p, 3);
        el.textContent = String(Math.round(target * eased));
        if (p < 1) window.requestAnimationFrame(step);
      }
      window.requestAnimationFrame(step);
    }

    if (!('IntersectionObserver' in window)) {
      counters.forEach(run);
      return;
    }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) { run(entry.target); io.unobserve(entry.target); }
      });
    }, { threshold: 0.5 });
    counters.forEach(function (el) { io.observe(el); });
  }

  /* ---------- Programme / resource filtering ---------- */
  function initFilters() {
    document.querySelectorAll('[data-filter-group]').forEach(function (group) {
      var targetSel = group.getAttribute('data-filter-target');
      var items = document.querySelectorAll(targetSel + ' [data-filter-value]');
      var status = document.querySelector(group.getAttribute('data-filter-status') || '');
      var buttons = group.querySelectorAll('button[data-filter]');

      group.addEventListener('click', function (e) {
        var btn = e.target.closest('button[data-filter]');
        if (!btn) return;
        var value = btn.getAttribute('data-filter');
        var shown = 0;
        buttons.forEach(function (b) { b.setAttribute('aria-pressed', b === btn ? 'true' : 'false'); });
        items.forEach(function (item) {
          var values = (item.getAttribute('data-filter-value') || '').split(' ');
          var match = value === 'all' || values.indexOf(value) !== -1;
          item.hidden = !match;
          if (match) shown++;
        });
        if (status) status.textContent = shown + ' item' + (shown === 1 ? '' : 's') + ' shown.';
      });
    });
  }

  /* ---------- Sticky section navigation highlight ---------- */
  function initSectionNav() {
    var nav = document.querySelector('.section-nav');
    if (!nav || !('IntersectionObserver' in window)) return;
    var links = Array.prototype.slice.call(nav.querySelectorAll('a[href^="#"]'));
    var sections = links.map(function (a) { return document.querySelector(a.getAttribute('href')); }).filter(Boolean);
    if (!sections.length) return;

    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        links.forEach(function (a) {
          a.classList.toggle('is-active', a.getAttribute('href') === '#' + entry.target.id);
        });
      });
    }, { rootMargin: '-45% 0px -50% 0px' });
    sections.forEach(function (s) { io.observe(s); });
  }

  /* ---------- Contact form: show the right fields per enquiry type ---------- */
  function initEnquiryForm() {
    var select = document.getElementById('cohf-enquiry-type');
    if (!select) return;
    function sync() {
      document.querySelectorAll('[data-enquiry-for]').forEach(function (block) {
        var types = block.getAttribute('data-enquiry-for').split(' ');
        block.hidden = types.indexOf(select.value) === -1;
      });
    }
    select.addEventListener('change', sync);
    sync();
  }


  /* ---------- Hero slider ----------
     Crossfades background layers and swaps the text pane. Autoplay pauses on
     hover, focus and tab-hide, is disabled outright under reduced-motion, and
     stops for good once the visitor takes manual control. */
  function initHeroRotator() {
    var hero = document.querySelector('[data-hero-slider]');
    if (!hero) return;

    var layers = Array.prototype.slice.call(hero.querySelectorAll('.hero__layer'));
    var panes = Array.prototype.slice.call(hero.querySelectorAll('[data-hero-pane]'));
    var dots = Array.prototype.slice.call(hero.querySelectorAll('[data-hero-dot]'));
    var prev = hero.querySelector('[data-hero-prev]');
    var next = hero.querySelector('[data-hero-next]');
    var status = hero.querySelector('[data-hero-status]');
    var total = panes.length;
    if (total < 2) return;

    var index = 0;
    var timer = null;
    var userEngaged = false;
    var INTERVAL = 6500;

    function show(n) {
      index = (n + total) % total;

      layers.forEach(function (l, i) { l.classList.toggle('is-active', i === index); });

      panes.forEach(function (pane, i) {
        var active = i === index;
        pane.hidden = !active;
        pane.classList.toggle('is-active', active);
        if (active) {
          // Restart the entry animation.
          pane.style.animation = 'none';
          void pane.offsetWidth;
          pane.style.animation = '';
        }
      });

      dots.forEach(function (d, i) {
        var active = i === index;
        d.classList.toggle('is-active', active);
        d.setAttribute('aria-selected', active ? 'true' : 'false');
      });

      if (status) {
        status.textContent = 'Slide ' + (index + 1) + ' of ' + total;
      }
    }

    function start() {
      if (reduceMotion || userEngaged) return;
      stop();
      timer = window.setInterval(function () { show(index + 1); }, INTERVAL);
    }
    function stop() {
      if (timer) { window.clearInterval(timer); timer = null; }
    }
    function engage(n) {
      userEngaged = true;
      stop();
      show(n);
    }

    if (prev) prev.addEventListener('click', function () { engage(index - 1); });
    if (next) next.addEventListener('click', function () { engage(index + 1); });
    dots.forEach(function (dot, i) {
      dot.addEventListener('click', function () { engage(i); });
    });

    hero.addEventListener('mouseenter', stop);
    hero.addEventListener('mouseleave', start);
    hero.addEventListener('focusin', stop);
    hero.addEventListener('focusout', start);

    document.addEventListener('visibilitychange', function () {
      if (document.hidden) { stop(); } else { start(); }
    });

    // Keyboard: arrow keys move between slides when focus is inside the hero.
    hero.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowLeft') { engage(index - 1); }
      else if (e.key === 'ArrowRight') { engage(index + 1); }
    });

    // Touch: horizontal swipe.
    var startX = null;
    hero.addEventListener('touchstart', function (e) {
      startX = e.touches[0].clientX;
    }, { passive: true });
    hero.addEventListener('touchend', function (e) {
      if (startX === null) return;
      var dx = e.changedTouches[0].clientX - startX;
      if (Math.abs(dx) > 45) { engage(index + (dx < 0 ? 1 : -1)); }
      startX = null;
    }, { passive: true });

    show(0);
    start();
  }

  function ready(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  ready(function () {
    initNav();
    initScrollState();
    initReveal();
    initCounters();
    initFilters();
    initSectionNav();
    initEnquiryForm();
    initHeroRotator();
  });
})();
