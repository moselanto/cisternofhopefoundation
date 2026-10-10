/**
 * Homepage partner logo strip: previous / next paging (14.10.0).
 *
 * The row is a native horizontal scroller with scroll-snap, so it already
 * works by swipe, trackpad and keyboard. This only adds the arrow buttons:
 * shown when the row overflows, disabled at each end, paging by the visible
 * width. Honours prefers-reduced-motion.
 */
( function () {
	'use strict';

	var frames = document.querySelectorAll( '[data-partner-logos]' );
	if ( ! frames.length ) {
		return;
	}

	var reduce = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	Array.prototype.forEach.call( frames, function ( frame ) {
		var track = frame.querySelector( '.partner-logos__track' );
		var prev  = frame.querySelector( '.partner-logos__nav--prev' );
		var next  = frame.querySelector( '.partner-logos__nav--next' );
		if ( ! track || ! prev || ! next ) {
			return;
		}

		function update() {
			var max       = track.scrollWidth - track.clientWidth;
			var overflows = max > 2;
			prev.hidden = ! overflows;
			next.hidden = ! overflows;
			frame.classList.toggle( 'is-scrollable', overflows );
			if ( ! overflows ) {
				frame.classList.remove( 'at-start', 'at-end' );
				return;
			}
			var atStart = track.scrollLeft <= 2;
			var atEnd   = track.scrollLeft >= max - 2;
			prev.disabled = atStart;
			next.disabled = atEnd;
			frame.classList.toggle( 'at-start', atStart );
			frame.classList.toggle( 'at-end', atEnd );
		}

		function page( dir ) {
			var step = Math.max( track.clientWidth * 0.85, 160 );
			track.scrollBy( { left: dir * step, behavior: reduce ? 'auto' : 'smooth' } );
		}

		prev.addEventListener( 'click', function () { page( -1 ); } );
		next.addEventListener( 'click', function () { page( 1 ); } );

		var ticking = false;
		track.addEventListener( 'scroll', function () {
			if ( ticking ) {
				return;
			}
			ticking = true;
			window.requestAnimationFrame( function () {
				ticking = false;
				update();
			} );
		}, { passive: true } );

		if ( 'ResizeObserver' in window ) {
			new window.ResizeObserver( update ).observe( track );
		} else {
			window.addEventListener( 'resize', update );
		}
		update();
	} );
}() );
