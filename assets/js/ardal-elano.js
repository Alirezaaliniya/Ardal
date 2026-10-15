/**
 * Ardal — قالب «الانو» پنل کاربری: منوی موبایل و چاپ.
 *
 * ذخیره/علاقه‌مندی با ardal.js و شارژ کیف پول با ardal-wallet.js کار می‌کنند؛
 * اینجا فقط رفتارهای خود پوسته است.
 */
( function () {
	'use strict';

	/* ---------------- منوی کشویی موبایل (offcanvas) ---------------- */

	var MOBILE = window.matchMedia( '(max-width: 767px)' );
	var lastToggle = null;

	function rootOf( el ) {
		return el ? el.closest( '[data-ard-elano]' ) : null;
	}

	function setMenu( root, open, toggle ) {
		var nav = root && root.querySelector( '[data-ard-el-nav]' );

		if ( ! nav ) {
			return;
		}

		toggle = toggle || root.querySelector( '[data-ard-el-toggle]' );

		root.classList.toggle( 'is-menu-open', open );
		nav.classList.toggle( 'is-open', open );
		document.documentElement.classList.toggle( 'ard-el-lock', open );

		if ( toggle ) {
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		}

		if ( open ) {
			lastToggle = toggle;
			nav.setAttribute( 'role', 'dialog' );
			nav.setAttribute( 'aria-modal', 'true' );

			var close = nav.querySelector( '[data-ard-el-close]' );

			window.setTimeout( function () {
				( close || nav ).focus();
			}, 50 );
		} else {
			nav.removeAttribute( 'role' );
			nav.removeAttribute( 'aria-modal' );

			// فوکوس به دکمهٔ منو برمی‌گردد تا صفحه‌خوان و کیبورد گم نشوند.
			if ( lastToggle && nav.contains( document.activeElement ) ) {
				lastToggle.focus();
			}
		}
	}

	function closeAll() {
		Array.prototype.forEach.call( document.querySelectorAll( '[data-ard-elano].is-menu-open' ), function ( root ) {
			setMenu( root, false );
		} );
	}

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key ) {
			closeAll();

			return;
		}

		// تب داخل پنل باز می‌چرخد و بیرون نمی‌رود.
		if ( 'Tab' !== event.key ) {
			return;
		}

		var nav = document.querySelector( '[data-ard-elano].is-menu-open [data-ard-el-nav]' );

		if ( ! nav ) {
			return;
		}

		var items = nav.querySelectorAll( 'a[href], button:not([disabled])' );
		var first = items[ 0 ];
		var last = items[ items.length - 1 ];

		if ( event.shiftKey && document.activeElement === first ) {
			event.preventDefault();
			last.focus();
		} else if ( ! event.shiftKey && document.activeElement === last ) {
			event.preventDefault();
			first.focus();
		}
	} );

	// برگشت به عرض دسکتاپ با منوی باز، قفل اسکرول را جا نگذارد.
	var onChange = function ( query ) {
		if ( ! query.matches ) {
			closeAll();
		}
	};

	if ( MOBILE.addEventListener ) {
		MOBILE.addEventListener( 'change', onChange );
	} else if ( MOBILE.addListener ) {
		MOBILE.addListener( onChange );
	}

	document.addEventListener( 'click', function ( event ) {
		var toggle = event.target.closest( '[data-ard-el-toggle]' );

		if ( toggle ) {
			var root = rootOf( toggle );

			setMenu( root, ! root.classList.contains( 'is-menu-open' ), toggle );

			return;
		}

		if ( event.target.closest( '[data-ard-el-close]' ) ) {
			setMenu( rootOf( event.target ), false );

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
