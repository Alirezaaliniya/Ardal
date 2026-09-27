/**
 * جست‌وجوی زندهٔ محصول.
 *
 * مارک‌آپ نتیجه‌ها را سرور می‌سازد؛ اینجا فقط تایپ کاربر مدیریت می‌شود: مکث
 * پیش از درخواست، لغو درخواست قبلی، و ناوبری با صفحه‌کلید.
 *
 * هر درخواست تازه، قبلی را abort می‌کند. بدون این، پاسخِ کندِ «کف» ممکن بود
 * بعد از پاسخِ سریعِ «کفش» برسد و نتیجهٔ اشتباه را روی صفحه بنشاند.
 */
( function () {
	'use strict';

	var cfg = window.ardalSearch || {};

	function setup( root ) {
		if ( root.ardReady ) {
			return;
		}

		root.ardReady = true;

		var input = root.querySelector( '.ard-se__input' );
		var panel = root.querySelector( '.ard-se__panel' );
		var clear = root.querySelector( '[data-ard-se-clear]' );
		var form = root.querySelector( '.ard-se__form' );

		if ( ! input || ! panel ) {
			return;
		}

		var min = parseInt( root.getAttribute( 'data-min' ), 10 ) || 3;
		var delay = parseInt( root.getAttribute( 'data-delay' ), 10 );
		var args = {};

		try {
			args = JSON.parse( root.getAttribute( 'data-args' ) || '{}' );
		} catch ( e ) {
			args = {};
		}

		if ( isNaN( delay ) ) {
			delay = 300;
		}

		var hint = panel.innerHTML;
		var timer = null;
		var controller = null;
		var lastTerm = '';

		/* -------------------------------------------------------------- */

		function open() {
			panel.hidden = false;
			root.classList.add( 'is-open' );
			input.setAttribute( 'aria-expanded', 'true' );
		}

		function close() {
			panel.hidden = true;
			root.classList.remove( 'is-open' );
			input.setAttribute( 'aria-expanded', 'false' );
			clearActive();
		}

		function show( html ) {
			panel.innerHTML = html;
			open();
		}

		function items() {
			return Array.prototype.slice.call( panel.querySelectorAll( '[data-ard-se-item]' ) );
		}

		function clearActive() {
			items().forEach( function ( el ) {
				el.classList.remove( 'is-active' );
			} );
		}

		/**
		 * جابه‌جایی بین نتیجه‌ها با کلیدهای بالا و پایین.
		 *
		 * @param {number} dir ۱ پایین، ‎-۱ بالا.
		 */
		function move( dir ) {
			var list = items();

			if ( ! list.length ) {
				return;
			}

			var index = -1;

			list.forEach( function ( el, i ) {
				if ( el.classList.contains( 'is-active' ) ) {
					index = i;
				}
			} );

			clearActive();

			index += dir;

			if ( index < 0 ) {
				index = list.length - 1;
			} else if ( index >= list.length ) {
				index = 0;
			}

			list[ index ].classList.add( 'is-active' );
			list[ index ].scrollIntoView( { block: 'nearest' } );
		}

		/* -------------------------------------------------------------- */

		function search( term ) {
			if ( controller ) {
				controller.abort();
			}

			controller = 'AbortController' in window ? new AbortController() : null;

			var body = new FormData();

			body.append( 'action', 'ardal_search' );
			body.append( 'nonce', cfg.nonce || '' );
			body.append( 'term', term );

			Object.keys( args ).forEach( function ( key ) {
				body.append( 'args[' + key + ']', args[ key ] );
			} );

			root.classList.add( 'is-loading' );

			window.fetch( cfg.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				body: body,
				signal: controller ? controller.signal : undefined
			} )
				.then( function ( res ) {
					return res.json();
				} )
				.then( function ( res ) {
					root.classList.remove( 'is-loading' );

					// پاسخِ یک عبارت قدیمی نباید روی عبارت فعلی بنشیند.
					if ( term !== lastTerm ) {
						return;
					}

					if ( ! res || ! res.success ) {
						show( '<div class="ard-se__empty">جست‌وجو انجام نشد؛ دوباره تلاش کنید.</div>' );

						return;
					}

					show( res.data.html || '' );
				} )
				.catch( function ( err ) {
					if ( err && 'AbortError' === err.name ) {
						return;
					}

					root.classList.remove( 'is-loading' );
					show( '<div class="ard-se__empty">جست‌وجو انجام نشد؛ دوباره تلاش کنید.</div>' );
				} );
		}

		function onType() {
			var term = input.value.trim();

			lastTerm = term;

			if ( clear ) {
				clear.hidden = '' === term;
			}

			window.clearTimeout( timer );

			if ( term.length < min ) {
				if ( controller ) {
					controller.abort();
					controller = null;
				}

				root.classList.remove( 'is-loading' );

				// زیر حد نصاب: همان راهنمای اولیه، نه نتیجهٔ قبلی.
				if ( term.length > 0 ) {
					show( hint );
				} else {
					panel.innerHTML = hint;
					close();
				}

				return;
			}

			timer = window.setTimeout( function () {
				search( term );
			}, delay );
		}

		/* -------------------------------------------------------------- */

		input.addEventListener( 'input', onType );

		input.addEventListener( 'focus', function () {
			if ( input.value.trim().length >= min || panel.innerHTML.trim() ) {
				open();
			}
		} );

		input.addEventListener( 'keydown', function ( event ) {
			if ( 'ArrowDown' === event.key ) {
				event.preventDefault();
				move( 1 );

				return;
			}

			if ( 'ArrowUp' === event.key ) {
				event.preventDefault();
				move( -1 );

				return;
			}

			if ( 'Escape' === event.key ) {
				close();

				return;
			}

			if ( 'Enter' === event.key ) {
				var active = panel.querySelector( '[data-ard-se-item].is-active' );

				// اگر نتیجه‌ای انتخاب شده مستقیم برو، وگرنه فرم کار خودش را بکند.
				if ( active ) {
					event.preventDefault();
					window.location.href = active.href;
				}
			}
		} );

		if ( clear ) {
			clear.addEventListener( 'click', function () {
				input.value = '';
				lastTerm = '';
				clear.hidden = true;
				panel.innerHTML = hint;
				close();
				input.focus();
			} );
		}

		if ( form ) {
			form.addEventListener( 'submit', function ( event ) {
				if ( input.value.trim().length < min ) {
					event.preventDefault();
				}
			} );
		}

		/*
		 * بستن با کلیک یا لمسِ بیرون.
		 *
		 * فقط click کافی نیست: در سافاری موبایل، click روی عنصرهای غیرتعاملی
		 * همیشه شلیک نمی‌شود و پنل باز می‌ماند. pointerdown هم موس را می‌گیرد
		 * هم لمس را، و click پشتیبانِ مرورگرهای بدون Pointer Events است.
		 * close() ایدمپوتنت است، پس اجرای دوباره‌اش ضرری ندارد.
		 */
		function closeIfOutside( event ) {
			if ( ! root.contains( event.target ) ) {
				close();
			}
		}

		document.addEventListener( 'pointerdown', closeIfOutside );
		document.addEventListener( 'click', closeIfOutside );
	}

	function init( scope ) {
		var container = scope || document;

		Array.prototype.forEach.call( container.querySelectorAll( '[data-ard-search]' ), setup );
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
			'frontend/element_ready/ardal-search.default',
			function ( $scope ) {
				init( $scope && $scope[ 0 ] ? $scope[ 0 ] : undefined );
			}
		);
	} );
}() );
