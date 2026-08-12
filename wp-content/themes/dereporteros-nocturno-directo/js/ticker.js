/**
 * Efecto de "máquina de escribir" para el cintillo de última hora: el
 * texto visible (sin JS) se toma como base y se vuelve a revelar letra
 * por letra — primero el título y, al terminar, el cuerpo — para cada
 * nota del cintillo (arrancando una después de otra si hubiera varias).
 * Se respeta prefers-reduced-motion dejando el texto completo tal cual.
 */
( function () {
	'use strict';

	function typeText( el, full, speed, onDone ) {
		el.textContent = '';
		var i = 0;
		( function step() {
			el.textContent = full.slice( 0, i );
			if ( i < full.length ) {
				i++;
				setTimeout( step, speed );
			} else if ( onDone ) {
				onDone();
			}
		} )();
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		var items = document.querySelectorAll( '.ticker-item' );
		if ( ! items.length ) {
			return;
		}
		if ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
			return;
		}
		items.forEach( function ( itemEl, index ) {
			var titleEl = itemEl.querySelector( '.ticker-title' );
			var bodyEl  = itemEl.querySelector( '.ticker-body' );
			if ( ! titleEl ) {
				return;
			}
			var fullTitle = titleEl.textContent;
			var fullBody  = bodyEl ? bodyEl.textContent : '';
			setTimeout( function () {
				typeText( titleEl, fullTitle, 28, function () {
					if ( ! bodyEl ) {
						return;
					}
					setTimeout( function () {
						typeText( bodyEl, fullBody, 14, null );
					}, 200 );
				} );
			}, index * 220 );
		} );
	} );
} )();
