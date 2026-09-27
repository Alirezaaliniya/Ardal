/**
 * «موارد بیشتر» در صفحهٔ نتیجه‌های جست‌وجو.
 *
 * دکمه در واقع پیوندِ صفحهٔ بعد است. اینجا همان نشانی fetch می‌شود و فقط شبکهٔ
 * داخلش به پایینِ شبکهٔ فعلی می‌چسبد؛ پس مارک‌آپ کارت‌ها همان چیزی است که
 * سرور می‌سازد و نسخهٔ دومی برای ایجکس وجود ندارد که از آن عقب بیفتد.
 *
 * بدون جاوااسکریپت هم پیوند کار خودش را می‌کند و کاربر به صفحهٔ بعد می‌رود.
 */
( function () {
	'use strict';

	function setup( root ) {
		if ( root.ardReady ) {
			return;
		}

		root.ardReady = true;

		var more = root.querySelector( '[data-ard-sr-more]' );
		var grid = root.querySelector( '[data-ard-sr-grid]' );

		if ( ! more || ! grid ) {
			return;
		}

		var broken = false;

		more.addEventListener( 'click', function ( event ) {
			// کلیک با کنترل یا وسط موس یعنی «در زبانهٔ تازه»؛ دستش نمی‌زنیم.
			if ( event.metaKey || event.ctrlKey || event.shiftKey || ( event.button && 0 !== event.button ) ) {
				return;
			}

			// یک‌بار شکست خورد: از این به بعد بگذار مرورگر خودش برود.
			if ( broken ) {
				return;
			}

			event.preventDefault();

			if ( more.classList.contains( 'is-loading' ) ) {
				return;
			}

			var url = more.getAttribute( 'href' );

			if ( ! url ) {
				return;
			}

			more.classList.add( 'is-loading' );

			window.fetch( url, { credentials: 'same-origin' } )
				.then( function ( res ) {
					return res.text();
				} )
				.then( function ( html ) {
					var doc = new DOMParser().parseFromString( html, 'text/html' );
					var next = doc.querySelector( '[data-ard-search-results] [data-ard-sr-grid]' );
					var nextMore = doc.querySelector( '[data-ard-search-results] [data-ard-sr-more]' );

					more.classList.remove( 'is-loading' );

					if ( ! next || ! next.children.length ) {
						more.classList.add( 'is-done' );

						return;
					}

					while ( next.firstElementChild ) {
						grid.appendChild( next.firstElementChild );
					}

					// صفحهٔ بعدی هست یا همین‌جا تمام شد؟
					if ( nextMore && nextMore.getAttribute( 'href' ) ) {
						more.setAttribute( 'href', nextMore.getAttribute( 'href' ) );
					} else {
						more.classList.add( 'is-done' );
					}
				} )
				.catch( function () {
					// شکست خورد: دکمه پیوند می‌ماند و کلیک بعدی صفحه را عوض می‌کند.
					more.classList.remove( 'is-loading' );
					broken = true;
				} );
		} );
	}

	function init( scope ) {
		var container = scope || document;

		Array.prototype.forEach.call( container.querySelectorAll( '[data-ard-search-results]' ), setup );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', function () {
			init();
		} );
	} else {
		init();
	}

	window.addEventListener( 'elementor/frontend/init', function () {
		if ( ! window.elementorFrontend || ! window.elementorFrontend.hooks ) {
			return;
		}

		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/ardal-search-results.default',
			function ( $scope ) {
				init( $scope && $scope[ 0 ] ? $scope[ 0 ] : undefined );
			}
		);
	} );
}() );
