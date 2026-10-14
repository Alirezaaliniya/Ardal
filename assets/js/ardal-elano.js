/**
 * Ardal — قالب «الانو» پنل کاربری: منوی موبایل و چاپ.
 *
 * ذخیره/علاقه‌مندی با ardal.js و شارژ کیف پول با ardal-wallet.js کار می‌کنند؛
 * اینجا فقط رفتارهای خود پوسته است.
 */
( function () {
	'use strict';

	document.addEventListener( 'click', function ( event ) {
		var toggle = event.target.closest( '[data-ard-el-toggle]' );

		if ( toggle ) {
			var nav = document.getElementById( toggle.getAttribute( 'aria-controls' ) );

			if ( nav ) {
				var open = ! nav.classList.contains( 'is-open' );

				nav.classList.toggle( 'is-open', open );
				toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			}

			return;
		}

		if ( event.target.closest( '[data-ard-el-print]' ) ) {
			event.preventDefault();
			window.print();
		}
	} );

	// دکمهٔ پرینت روی کارت سفارش به صفحهٔ جزئیات با ?ard_print=1 می‌آید.
	function autoPrint() {
		if ( /[?&]ard_print=1(&|$)/.test( window.location.search ) && document.querySelector( '[data-ard-elano]' ) ) {
			window.setTimeout( function () {
				window.print();
			}, 400 );
		}
	}

	if ( 'complete' === document.readyState ) {
		autoPrint();
	} else {
		window.addEventListener( 'load', autoPrint );
	}
}() );
