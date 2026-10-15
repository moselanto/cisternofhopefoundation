/**
 * WhatsApp chat card (14.14.0). Markup: inc/whatsapp-chat.php.
 * No dependencies. Without this script the button is a plain wa.me link.
 */
( function () {
	'use strict';

	function init() {
		var root = document.querySelector( '[data-cohf-chat]' );
		if ( ! root ) { return; }

		var fab = root.querySelector( '.cohf-chat__fab' );
		var panel = root.querySelector( '.cohf-chat__panel' );
		var form = root.querySelector( '.cohf-chat__form' );
		var text = root.querySelector( 'textarea' );
		var teaser = root.querySelector( '.cohf-chat__teaser' );
		var dot = root.querySelector( '.cohf-chat__dot' );
		var share = root.querySelector( '.cohf-chat__share input' );
		var typing = root.querySelector( '.cohf-chat__typing' );
		var bubble = root.querySelector( '.cohf-chat__bubble' );
		var chips = root.querySelectorAll( '.cohf-chat__chip' );
		var number = root.getAttribute( 'data-number' );
		var reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
		var wide = window.matchMedia( '(min-width: 768px)' );
		var greeted = false;
		var lastFocus = null;

		var store = {
			get: function ( k ) { try { return window.sessionStorage.getItem( k ); } catch ( e ) { return null; } },
			set: function ( k, v ) { try { window.sessionStorage.setItem( k, v ); } catch ( e ) {} }
		};

		// Nairobi time for the bubble timestamp and optional reply hours.
		var now = null;
		try {
			var parts = new Intl.DateTimeFormat( 'en-GB', { weekday: 'short', hour: 'numeric', minute: '2-digit', hourCycle: 'h23', timeZone: 'Africa/Nairobi' } ).formatToParts( new Date() );
			now = {};
			parts.forEach( function ( x ) { now[ x.type ] = x.value; } );
		} catch ( e ) {}

		if ( now ) {
			var tm = root.querySelector( '.cohf-chat__time' );
			if ( tm ) { tm.textContent = now.hour + ':' + now.minute; }

			var openH = parseInt( root.getAttribute( 'data-open-hour' ), 10 );
			var closeH = parseInt( root.getAttribute( 'data-close-hour' ), 10 );
			if ( ! isNaN( openH ) && ! isNaN( closeH ) ) {
				var dayIdx = [ 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' ].indexOf( now.weekday );
				var days = ( root.getAttribute( 'data-days' ) || '1,2,3,4,5' ).split( ',' ).map( function ( d ) { return parseInt( d, 10 ); } );
				var h = parseInt( now.hour, 10 );
				var online = days.indexOf( dayIdx ) > -1 && h >= openH && h < closeH;
				root.classList.add( online ? 'is-online' : 'is-away' );
				var st = root.querySelector( '.cohf-chat__status' );
				var label = root.getAttribute( online ? 'data-online-text' : 'data-offline-text' );
				if ( st && label ) { st.textContent = label; }
			}
		}

		// Unread dot until the visitor has opened the chat once this visit.
		if ( dot && ! store.get( 'cohfChatSeen' ) ) { dot.hidden = false; }

		function grow() {
			text.style.height = 'auto';
			text.style.height = Math.min( text.scrollHeight, 132 ) + 'px';
		}

		function greet() {
			if ( greeted ) { return; }
			greeted = true;
			if ( reduce || ! typing ) {
				root.classList.add( 'is-greeted' );
				return;
			}
			root.classList.add( 'is-typing' );
			window.setTimeout( function () {
				root.classList.remove( 'is-typing' );
				root.classList.add( 'is-greeted' );
			}, 900 );
		}

		function hideTeaser() {
			if ( teaser ) { teaser.hidden = true; }
			store.set( 'cohfChatTeaser', '1' );
		}

		function open() {
			lastFocus = document.activeElement;
			hideTeaser();
			store.set( 'cohfChatSeen', '1' );
			if ( dot ) { dot.hidden = true; }
			panel.hidden = false;
			root.classList.add( 'is-open' );
			document.documentElement.classList.add( 'cohf-chat-open' );
			fab.setAttribute( 'aria-expanded', 'true' );
			greet();
			if ( wide.matches ) {
				text.focus( { preventScroll: true } );
			} else {
				var c = root.querySelector( '.cohf-chat__close' );
				if ( c ) { c.focus( { preventScroll: true } ); }
			}
		}

		function close( refocus ) {
			panel.hidden = true;
			root.classList.remove( 'is-open' );
			document.documentElement.classList.remove( 'cohf-chat-open' );
			fab.setAttribute( 'aria-expanded', 'false' );
			if ( refocus ) { ( lastFocus && lastFocus.focus ? lastFocus : fab ).focus( { preventScroll: true } ); }
		}

		// The button is a real wa.me link for no-JS visitors; with JS it opens the card.
		fab.setAttribute( 'role', 'button' );
		fab.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			if ( panel.hidden ) { open(); } else { close( true ); }
		} );
		fab.addEventListener( 'keydown', function ( e ) {
			if ( e.key === ' ' ) { e.preventDefault(); fab.click(); }
		} );

		root.querySelector( '.cohf-chat__close' ).addEventListener( 'click', function () { close( true ); } );
		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' && ! panel.hidden ) { close( true ); }
		} );
		document.addEventListener( 'click', function ( e ) {
			if ( ! panel.hidden && ! root.contains( e.target ) ) { close( false ); }
		} );

		// Keep Tab inside the card on phones, where it covers the page.
		panel.addEventListener( 'keydown', function ( e ) {
			if ( e.key !== 'Tab' || wide.matches ) { return; }
			var f = panel.querySelectorAll( 'button, textarea, input, a[href]' );
			if ( ! f.length ) { return; }
			var first = f[ 0 ], last = f[ f.length - 1 ];
			if ( e.shiftKey && document.activeElement === first ) { e.preventDefault(); last.focus(); }
			else if ( ! e.shiftKey && document.activeElement === last ) { e.preventDefault(); first.focus(); }
		} );

		chips.forEach( function ( chip ) {
			chip.addEventListener( 'click', function () {
				chips.forEach( function ( c ) {
					var on = c === chip;
					c.classList.toggle( 'is-picked', on );
					c.setAttribute( 'aria-pressed', on ? 'true' : 'false' );
				} );
				text.value = chip.textContent.trim() + '. ';
				grow();
				text.focus();
				try { text.setSelectionRange( text.value.length, text.value.length ); } catch ( e ) {}
			} );
		} );

		text.addEventListener( 'input', function () {
			grow();
			form.classList.toggle( 'has-text', text.value.trim() !== '' );
		} );
		text.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Enter' && ! e.shiftKey && wide.matches ) {
				e.preventDefault();
				if ( form.requestSubmit ) { form.requestSubmit(); } else { form.dispatchEvent( new Event( 'submit', { cancelable: true } ) ); }
			}
		} );

		form.addEventListener( 'submit', function ( e ) {
			e.preventDefault();
			var msg = text.value.trim();
			if ( ! msg ) {
				var picked = root.querySelector( '.cohf-chat__chip.is-picked' );
				msg = picked ? picked.textContent.trim() : '';
			}
			var pageUrl = root.getAttribute( 'data-page-url' );
			if ( share && share.checked && pageUrl ) {
				msg += ( msg ? '\n\n' : '' ) + ( root.getAttribute( 'data-page-label' ) || 'Page' ) + ': ' + pageUrl;
			}
			var url = 'https://wa.me/' + number + ( msg ? '?text=' + encodeURIComponent( msg ) : '' );
			// window.open with "noopener" returns null, which used to open WhatsApp twice.
			var w = window.open( url, '_blank' );
			if ( w ) { try { w.opener = null; } catch ( err ) {} } else { window.location.href = url; }
			text.value = '';
			form.classList.remove( 'has-text' );
			grow();
			close( false );
		} );

		// One friendly nudge per visit, after 15 seconds, never on quiet pages.
		if ( teaser && ! store.get( 'cohfChatTeaser' ) && root.getAttribute( 'data-quiet' ) !== '1' ) {
			window.setTimeout( function () {
				var busy = document.documentElement.classList.contains( 'cart-drawer-open' ) || document.querySelector( '.links.is-open' );
				if ( panel.hidden && ! busy && ! store.get( 'cohfChatTeaser' ) ) { teaser.hidden = false; }
			}, 15000 );
			teaser.querySelector( '.cohf-chat__teaser-close' ).addEventListener( 'click', function ( e ) { e.stopPropagation(); hideTeaser(); } );
			teaser.querySelector( '.cohf-chat__teaser-open' ).addEventListener( 'click', function () { open(); } );
		}
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
