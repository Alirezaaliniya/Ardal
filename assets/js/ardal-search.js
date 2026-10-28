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

	/* ------------------------------------------------------------------
	   تاریخچهٔ جست‌وجو — در مرورگر خودِ کاربر.

	   همهٔ ویجت‌های جست‌وجوی صفحه یک فهرست مشترک دارند؛ کاربر تفاوتی بین
	   جست‌وجوی هدر و جست‌وجوی صفحه نمی‌بیند. دسترسی به localStorage ممکن است
	   در حالت خصوصی یا با تنظیمات سخت‌گیرانه خطا بدهد، پس همه‌جا try دارد.
	   ------------------------------------------------------------------ */

	var HISTORY_KEY = 'ardal_search_history';
	var HISTORY_MAX = 20;

	function readHistory() {
		try {
			var list = JSON.parse( window.localStorage.getItem( HISTORY_KEY ) || '[]' );

			return Array.isArray( list ) ? list.filter( function ( t ) {
				return 'string' === typeof t && t.trim();
			} ) : [];
		} catch ( e ) {
			return [];
		}
	}

	function writeHistory( list ) {
		try {
			window.localStorage.setItem( HISTORY_KEY, JSON.stringify( list.slice( 0, HISTORY_MAX ) ) );
		} catch ( e ) {
			// ذخیره نشد؛ فقط تاریخچه از دست می‌رود، جست‌وجو کار می‌کند.
		}
	}

	function sameTerm( a, b ) {
		return a.trim().toLowerCase() === b.trim().toLowerCase();
	}

	function rememberTerm( term ) {
		term = ( term || '' ).trim().replace( /\s+/g, ' ' );

		if ( ! term ) {
			return;
		}

		var list = readHistory().filter( function ( t ) {
			return ! sameTerm( t, term );
		} );

		list.unshift( term );
		writeHistory( list );
	}

	function forgetTerm( term ) {
		writeHistory( readHistory().filter( function ( t ) {
			return ! sameTerm( t, term );
		} ) );
	}

	/**
	 * شمردن عبارتی که با کلیک روی نتیجهٔ زنده تمام شد (برای «پرمخاطب»).
	 * sendBeacon حتی وقتی صفحه در حال رفتن است هم ارسال می‌شود.
	 */
	function logTerm( term ) {
		if ( ! term || ! cfg.ajaxUrl ) {
			return;
		}

		var body = new FormData();

		body.append( 'action', 'ardal_search_log' );
		body.append( 'nonce', cfg.nonce || '' );
		body.append( 'term', term );

		if ( navigator.sendBeacon ) {
			navigator.sendBeacon( cfg.ajaxUrl, body );
		} else if ( window.fetch ) {
			window.fetch( cfg.ajaxUrl, { method: 'POST', credentials: 'same-origin', body: body, keepalive: true } );
		}
	}

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

		// قالب مدرن: پنلِ پیش از تایپ (تاریخچه و پرمخاطب).
		var homeTpl = root.querySelector( 'template[data-ard-se-home]' );
		var itemTpl = root.querySelector( 'template[data-ard-se-hist-item]' );
		var historyLimit = parseInt( root.getAttribute( 'data-history' ), 10 ) || 0;
		var homeClick = root.getAttribute( 'data-home-click' ) || 'go';

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
			root.classList.remove( 'is-home' );
			open();
		}

		/**
		 * نشانی صفحهٔ نتیجه‌ها برای یک عبارت؛ همان چیزی که فرم می‌فرستد.
		 */
		function resultsUrl( term ) {
			var url = new URL( ( form && form.getAttribute( 'action' ) ) || '/', window.location.href );

			url.searchParams.set( 's', term );
			url.searchParams.set( 'post_type', 'product' );

			return url.href;
		}

		/**
		 * یک نسخهٔ تازه از پنلِ پیش از تایپ؛ اگر چیزی برای نشان دادن نباشد null.
		 */
		function buildHome() {
			if ( ! homeTpl ) {
				return null;
			}

			var node = homeTpl.content.firstElementChild.cloneNode( true );
			var section = node.querySelector( '[data-ard-se-history]' );
			var list = node.querySelector( '[data-ard-se-hist-list]' );
			var terms = historyLimit ? readHistory().slice( 0, historyLimit ) : [];

			if ( section && list && itemTpl && terms.length ) {
				terms.forEach( function ( term ) {
					var row = itemTpl.content.firstElementChild.cloneNode( true );
					var link = row.querySelector( '.ard-se__hist-link' );

					link.href = resultsUrl( term );
					link.setAttribute( 'data-ard-se-term', term );
					row.querySelector( '.ard-se__hist-text' ).textContent = term;
					row.querySelector( '[data-ard-se-del]' ).setAttribute( 'data-term', term );
					list.appendChild( row );
				} );

				section.hidden = false;
			} else if ( section ) {
				section.parentNode.removeChild( section );
			}

			return node.children.length ? node : null;
		}

		/**
		 * پنل پیش از تایپ را نشان می‌دهد؛ اگر خالی بود، پنل بسته می‌شود.
		 *
		 * @return {boolean} آیا چیزی نشان داده شد.
		 */
		function showHome() {
			var node = buildHome();

			if ( ! node ) {
				return false;
			}

			panel.innerHTML = '';
			panel.appendChild( node );
			root.classList.add( 'is-home' );
			open();

			return true;
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
				} else if ( ! showHome() ) {
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
			// کادر خالی در قالب مدرن: تاریخچه و پرمخاطب.
			if ( '' === input.value.trim() && showHome() ) {
				return;
			}

			if ( input.value.trim().length >= min || panel.innerHTML.trim() ) {
				open();
			}
		} );

		/*
		 * کلیک‌های داخل پنل.
		 *
		 * - ضربدر تاریخچه: همان مورد حذف و پنل دوباره ساخته می‌شود.
		 * - عبارت تاریخچه یا چیپ: به تاریخچه می‌رود (بالای فهرست)؛ بعد یا
		 *   صفحهٔ نتیجه باز می‌شود (لینک کار خودش را می‌کند) یا در کادر می‌نشیند.
		 * - نتیجهٔ زنده: عبارت تایپ‌شده در تاریخچه می‌نشیند و شمرده می‌شود.
		 */
		panel.addEventListener( 'click', function ( event ) {
			var del = event.target.closest( '[data-ard-se-del]' );

			if ( del ) {
				event.preventDefault();
				forgetTerm( del.getAttribute( 'data-term' ) || '' );

				if ( ! showHome() ) {
					close();
				}

				input.focus( { preventScroll: true } );

				return;
			}

			if ( event.target.closest( '[data-ard-se-clear-all]' ) ) {
				event.preventDefault();
				writeHistory( [] );

				if ( ! showHome() ) {
					close();
				}

				return;
			}

			var pick = event.target.closest( '[data-ard-se-term]' );

			if ( pick ) {
				var term = pick.getAttribute( 'data-ard-se-term' ) || '';

				rememberTerm( term );

				if ( 'fill' === homeClick ) {
					event.preventDefault();
					input.value = term;
					input.focus();
					onType();

					// زیر حد نصاب، جست‌وجوی زنده‌ای در کار نیست؛ مستقیم برو.
					if ( term.length < min ) {
						window.location.href = resultsUrl( term );
					}
				}

				return;
			}

			if ( event.target.closest( '[data-ard-se-item]' ) ) {
				rememberTerm( input.value );
				logTerm( input.value.trim() );
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

					if ( active.hasAttribute( 'data-ard-se-item' ) ) {
						rememberTerm( input.value );
						logTerm( input.value.trim() );
					}

					window.location.href = active.href;
				}
			}
		} );

		if ( clear ) {
			clear.addEventListener( 'click', function () {
				input.value = '';
				lastTerm = '';
				clear.hidden = true;
				input.focus();

				if ( ! showHome() ) {
					panel.innerHTML = hint;
					close();
				}
			} );
		}

		if ( form ) {
			form.addEventListener( 'submit', function ( event ) {
				if ( input.value.trim().length < min ) {
					event.preventDefault();

					return;
				}

				rememberTerm( input.value );
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
			// ردیفی که همین حالا حذف شد دیگر در DOM نیست؛ کلیکِ بیرون حساب نشود.
			if ( false === event.target.isConnected ) {
				return;
			}

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
