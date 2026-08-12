/**
 * Efecto de "máquina de escribir" para los títulos del cintillo de
 * última hora: el texto visible (sin JS) se toma como base y se vuelve a
 * revelar letra por letra, con cada nota arrancando después de la
 * anterior. Se respeta prefers-reduced-motion dejando el texto completo
 * tal cual.
 */
( function () {
	'use strict';

	function typeText( el, full, speed ) {
		el.textContent = '';
		var i = 0;
		( function step() {
			el.textContent = full.slice( 0, i );
			if ( i < full.length ) {
				i++;
				setTimeout( step, speed );
			}
		} )();
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		var titles = document.querySelectorAll( '.ticker-title' );
		if ( ! titles.length ) {
			return;
		}
		if ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
			return;
		}
		titles.forEach( function ( el, index ) {
			var full = el.textContent;
			setTimeout( function () {
				typeText( el, full, 28 );
			}, index * 220 );
		} );
	} );
} )();
