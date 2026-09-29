<?php
/**
 * Page loader: "ripples of hope".
 *
 * A cistern holds water; a single drop sends rings outward far beyond where
 * it landed. That is the Foundation's own argument about its work, so the
 * loader is concentric ripples expanding from a centre drop rather than a
 * generic spinner.
 *
 * Two rules governed the build, because a loader that fails to leave is
 * worse than no loader at all:
 *
 *   1. It removes itself with CSS alone. The fade-out is a keyframe
 *      animation with a delay, so the overlay disappears even if the script
 *      below never runs, is blocked, or throws.
 *   2. The script only ever makes it leave sooner, never later.
 *
 * It is also shown once per browsing session, not on every page view, so
 * moving between pages is never gated behind an animation.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="cohf-loader" id="cohf-loader" role="status" aria-label="<?php esc_attr_e( 'Loading', 'cohf-child' ); ?>">
	<div class="cohf-loader__stage" aria-hidden="true">
		<span class="cohf-loader__ripple"></span>
		<span class="cohf-loader__ripple"></span>
		<span class="cohf-loader__ripple"></span>
		<span class="cohf-loader__drop"></span>
	</div>
	<p class="cohf-loader__name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
	<p class="cohf-loader__strap"><?php esc_html_e( 'Together for a lasting change', 'cohf-child' ); ?></p>
</div>
<script>
/* Inline and tiny, because an external file would itself have to load
   before the loader could be dismissed. */
(function () {
	var el = document.getElementById('cohf-loader');
	if (el === null) { return; }

	var done = false;
	function dismiss() {
		if (done === true) { return; }
		done = true;
		el.className += ' is-done';
	}

	/* Already seen this session: remove it immediately rather than making
	   somebody watch the same animation on every page. */
	try {
		if (window.sessionStorage.getItem('cohfLoaderSeen') === '1') {
			el.className += ' is-instant';
			dismiss();
			return;
		}
		window.sessionStorage.setItem('cohfLoaderSeen', '1');
	} catch (e) {
		/* Private mode or storage disabled: fall through and just show it. */
	}

	if (document.readyState === 'complete') {
		dismiss();
	} else {
		window.addEventListener('load', dismiss);
	}

	/* Backstop, in case a slow third-party asset delays the load event. */
	window.setTimeout(dismiss, 2400);
}());
</script>
