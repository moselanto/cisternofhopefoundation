/*
 * COHF - primary navigation dropdowns.
 *
 * Interaction model (desktop): hovering a group opens it; moving away closes
 * it again. Clicking PINS the group open so it survives the pointer leaving,
 * and clicking again closes it.
 *
 * Why pinning exists: without it, hover opens the panel and the subsequent
 * click is read as "already open, so close" - the menu shuts the instant the
 * user clicks it. Tracking the pin separates the two intents.
 *
 * Mobile: no hover, so the same groups behave as a plain accordion.
 * The trigger is a <button> with aria-expanded so keyboard and screen-reader
 * users get the same behaviour.
 */
(function () {
  'use strict';

  var groups = Array.prototype.slice.call(document.querySelectorAll('[data-navgroup]'));
  if (groups.length === 0) return;

  var desktop = window.matchMedia('(min-width: 901px)');
  var hoverable = window.matchMedia('(hover: hover) and (pointer: fine)');
  var pinned = null;

  function btnOf(group) { return group.querySelector('.navgroup__btn'); }

  function isOpen(group) { return group.classList.contains('is-open'); }

  function setOpen(group, open) {
    var btn = btnOf(group);
    if (btn === null) return;
    group.classList.toggle('is-open', open);
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    if (open === false && pinned === group) pinned = null;
  }

  function closeAll(except) {
    groups.forEach(function (g) { if (g !== except) setOpen(g, false); });
  }

  function useHover() { return desktop.matches && hoverable.matches; }

  groups.forEach(function (group) {
    var btn = btnOf(group);
    if (btn === null) return;

    btn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();

      // Already pinned by an earlier click - this click closes it.
      if (pinned === group) {
        setOpen(group, false);
        return;
      }

      // Either closed, or opened by hover and now being pinned.
      closeAll(group);
      setOpen(group, true);
      pinned = group;
    });

    group.addEventListener('mouseenter', function () {
      if (useHover() === false) return;
      closeAll(group);
      setOpen(group, true);
    });

    group.addEventListener('mouseleave', function () {
      if (useHover() === false) return;
      if (pinned === group) return;   // a pinned panel stays put
      setOpen(group, false);
    });

    group.addEventListener('focusout', function (e) {
      if (desktop.matches === false) return;
      if (e.relatedTarget && group.contains(e.relatedTarget)) return;
      setOpen(group, false);
    });
  });

  // Escape closes the dropdown before the mobile drawer handler sees the key.
  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    var open = groups.filter(isOpen);
    if (open.length === 0) return;
    e.stopPropagation();
    open.forEach(function (g) {
      setOpen(g, false);
      var btn = btnOf(g);
      if (btn) btn.focus();
    });
  }, true);

  document.addEventListener('click', function (e) {
    var inside = groups.some(function (g) { return g.contains(e.target); });
    if (inside === false) closeAll(null);
  });

  var onChange = function () { closeAll(null); };
  if (desktop.addEventListener) desktop.addEventListener('change', onChange);
  else if (desktop.addListener) desktop.addListener(onChange);
})();
