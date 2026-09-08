/**
 * Ardal — رفتار پنل کاربری.
 *
 * کشوی موبایل، کپی کد تخفیف، چاپ فاکتور، حذف از علاقه‌مندی‌ها و همگام‌سازی
 * فهرست محلی علاقه‌مندی با حساب کاربر. بدون وابستگی به jQuery.
 */
( function () {
	'use strict';

	var CONFIG = window.ardalAccount || {};
	var STORE = 'ardal_wishlist';
	var SYNCED = 'ardal_wishlist_synced';

	function reduceMotion() {
		return window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	}

	/**
	 * درخواست به admin-ajax. خطاها بی‌صدا رد می‌شوند چون هیچ‌کدام از این
	 * کارها نباید صفحه را از کار بیندازد.
	 *
	 * @param {Object}   data
	 * @param {Function} done
	 */
	function post( data, done ) {
		if ( ! CONFIG.ajax || ! CONFIG.nonce ) {
			return;
		}

		var body = new window.FormData();

		body.append( 'action', 'ardal_wishlist' );
		body.append( 'nonce', CONFIG.nonce );

		Object.keys( data ).forEach( function ( key ) {
			if ( Array.isArray( data[ key ] ) ) {
				data[ key ].forEach( function ( value ) {
					body.append( key + '[]', value );
				} );

				return;
			}

			body.append( key, data[ key ] );
		} );

		window.fetch( CONFIG.ajax, { method: 'POST', body: body, credentials: 'same-origin' } )
			.then( function ( response ) {
				return response.json();
			} )
			.then( function ( result ) {
				if ( done && result && result.success ) {
					done( result.data );
				}
			} )
			.catch( function () {} );
	}

	/* ---------------- علاقه‌مندی‌ها ---------------- */

	function localIds() {
		try {
			var raw = window.localStorage.getItem( STORE );
			var ids = raw ? JSON.parse( raw ) : [];

			return Array.isArray( ids ) ? ids : [];
		} catch ( e ) {
			return [];
		}
	}

	function saveLocal( ids ) {
		try {
			window.localStorage.setItem( STORE, JSON.stringify( ids ) );
		} catch ( e ) {}
	}

	/**
	 * ویجت‌های محصول، علاقه‌مندی را در localStorage نگه می‌دارند. یک بار در هر
	 * نشست، آن فهرست با حساب کاربر ادغام می‌شود تا صفحهٔ علاقه‌مندی‌ها که سمت
	 * سرور رندر می‌شود چیزی را جا نیندازد.
	 */
	function syncWishlist() {
		if ( ! CONFIG.loggedIn ) {
			return;
		}

		var ids = localIds();
		var already = false;

		try {
			already = '1' === window.sessionStorage.getItem( SYNCED );
		} catch ( e ) {}

		if ( already ) {
			return;
		}

		post( { op: 'sync', ids: ids }, function ( data ) {
			saveLocal( data.ids || [] );

			try {
				window.sessionStorage.setItem( SYNCED, '1' );
			} catch ( e ) {}

			// اگر فهرست سرور با چیزی که صفحه نشان می‌دهد فرق دارد، یک بار تازه
			// می‌شود تا کاربر همان چیزی را ببیند که ذخیره شده است.
			var page = document.querySelector( '[data-ard-ac-wishlist]' );

			if ( page && ( data.ids || [] ).length !== page.querySelectorAll( '[data-ard-ac-fav]' ).length ) {
				window.location.reload();
			}
		} );
	}

	function initWishlist( root ) {
		root.addEventListener( 'click', function ( event ) {
			var button = event.target.closest ? event.target.closest( '[data-ard-ac-unfav]' ) : null;

			if ( ! button ) {
				return;
			}

			event.preventDefault();

			var id = parseInt( button.getAttribute( 'data-ard-ac-unfav' ), 10 );
			var card = button.closest( '[data-ard-ac-fav]' );

			if ( ! id || ! card ) {
				return;
			}

			card.classList.add( 'is-removing' );
			saveLocal( localIds().filter( function ( item ) {
				return parseInt( item, 10 ) !== id;
			} ) );

			post( { op: 'remove', product: id }, function () {
				if ( card.parentNode ) {
					card.parentNode.removeChild( card );
				}
			} );
		} );

		// «افزودن همه به سبد» دکمه‌های موجود کارت‌ها را یکی‌یکی می‌زند تا از
		// همان مسیر ایجکس ووکامرس رد شود.
		var addAll = root.querySelector( '[data-ard-ac-add-all]' );

		if ( addAll ) {
			addAll.addEventListener( 'click', function () {
				var buttons = root.querySelectorAll( '.ard-ac__fav-foot a.add_to_cart_button, .ard-ac__fav-foot a.ajax_add_to_cart' );

				Array.prototype.forEach.call( buttons, function ( button, index ) {
					window.setTimeout( function () {
						button.click();
					}, index * 350 );
				} );
			} );
		}
	}

	/* ---------------- کشوی موبایل ---------------- */

	function initDrawer( panel ) {
		var drawer = document.querySelector( '[data-ard-ac-drawer]' );
		var scrim = document.querySelector( '[data-ard-ac-scrim]' );
		var burger = panel.querySelector( '[data-ard-ac-burger]' );

		if ( ! drawer || ! scrim ) {
			return;
		}

		var mq = window.matchMedia( '(max-width: 1023.98px)' );
		var placeholder = document.createComment( 'ard-ac-drawer' );
		var moved = false;
		var lastFocus = null;

		scrim.removeAttribute( 'hidden' );

		// کشو position:fixed است؛ اگر والدی transform داشته باشد (کاری که
		// المنتور می‌کند) نسبت به صفحه نمایش قرار نمی‌گیرد، پس به body می‌رود.
		function moveOut() {
			if ( moved || ! drawer.parentNode ) {
				return;
			}

			drawer.parentNode.insertBefore( placeholder, drawer );
			document.body.appendChild( scrim );
			document.body.appendChild( drawer );
			moved = true;
		}

		function moveBack() {
			if ( ! moved || ! placeholder.parentNode ) {
				return;
			}

			placeholder.parentNode.insertBefore( drawer, placeholder );
			placeholder.parentNode.insertBefore( scrim, placeholder );
			placeholder.parentNode.removeChild( placeholder );
			moved = false;
		}

		function open() {
			lastFocus = document.activeElement;

			drawer.classList.add( 'is-open' );
			scrim.classList.add( 'is-open' );
			document.documentElement.classList.add( 'ard-ac-lock' );

			if ( burger ) {
				burger.setAttribute( 'aria-expanded', 'true' );
			}

			var close = drawer.querySelector( '[data-ard-ac-close]' );

			if ( close ) {
				close.focus();
			}
		}

		function close( restore ) {
			if ( ! drawer.classList.contains( 'is-open' ) ) {
				return;
			}

			drawer.classList.remove( 'is-open' );
			scrim.classList.remove( 'is-open' );
			document.documentElement.classList.remove( 'ard-ac-lock' );

			if ( burger ) {
				burger.setAttribute( 'aria-expanded', 'false' );
			}

			if ( false !== restore && lastFocus && lastFocus.focus ) {
				lastFocus.focus();
			}
		}

		function applyMode( mobile ) {
			close( false );

			drawer.classList.toggle( 'is-mobile', mobile );
			scrim.classList.toggle( 'is-mobile', mobile );

			if ( mobile ) {
				moveOut();
			} else {
				moveBack();
			}
		}

		if ( burger ) {
			burger.addEventListener( 'click', open );
		}

		// آیکون «پنل من» در نوار پایین هم همین کشو را باز می‌کند.
		var tab = panel.querySelector( '.ard-ac__tab.is-active' );

		if ( tab ) {
			tab.addEventListener( 'click', function ( event ) {
				if ( ! mq.matches ) {
					return;
				}

				event.preventDefault();
				open();
			} );
		}

		scrim.addEventListener( 'click', function () {
			close( true );
		} );

		drawer.addEventListener( 'click', function ( event ) {
			if ( event.target.closest && event.target.closest( '[data-ard-ac-close]' ) ) {
				event.preventDefault();
				close( true );
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key || 'Esc' === event.key ) {
				close( true );
			}
		} );

		// تله فوکوس تا وقتی کشو باز است.
		drawer.addEventListener( 'keydown', function ( event ) {
			if ( 'Tab' !== event.key || ! drawer.classList.contains( 'is-open' ) ) {
				return;
			}

			var items = Array.prototype.filter.call(
				drawer.querySelectorAll( 'a[href], button:not([disabled])' ),
				function ( el ) {
					return null !== el.offsetParent;
				}
			);

			if ( ! items.length ) {
				return;
			}

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

		if ( mq.addEventListener ) {
			mq.addEventListener( 'change', function ( event ) {
				applyMode( event.matches );
			} );
		} else if ( mq.addListener ) {
			mq.addListener( function ( event ) {
				applyMode( event.matches );
			} );
		}

		applyMode( mq.matches );
	}

	/* ---------------- کارهای کوچک ---------------- */

	function initSmall( panel ) {
		panel.addEventListener( 'click', function ( event ) {
			var copy = event.target.closest ? event.target.closest( '[data-ard-ac-copy]' ) : null;

			if ( copy ) {
				event.preventDefault();

				var code = copy.getAttribute( 'data-ard-ac-copy' );
				var label = copy.textContent;

				var done = function () {
					copy.textContent = 'کپی شد';
					window.setTimeout( function () {
						copy.textContent = label;
					}, 1800 );
				};

				if ( window.navigator.clipboard && window.navigator.clipboard.writeText ) {
					window.navigator.clipboard.writeText( code ).then( done ).catch( function () {} );
				} else {
					// مسیر جایگزین برای مرورگرهایی که clipboard API ندارند.
					var field = document.createElement( 'textarea' );

					field.value = code;
					field.setAttribute( 'readonly', 'readonly' );
					field.style.position = 'fixed';
					field.style.opacity = '0';
					document.body.appendChild( field );
					field.select();

					try {
						document.execCommand( 'copy' );
						done();
					} catch ( e ) {}

					document.body.removeChild( field );
				}

				return;
			}

			if ( event.target.closest && event.target.closest( '[data-ard-ac-print]' ) ) {
				event.preventDefault();
				window.print();
			}
		} );

		// میله‌های نمودار از صفر بالا می‌آیند.
		if ( reduceMotion() ) {
			return;
		}

		var bars = panel.querySelectorAll( '.ard-ac__bar' );

		Array.prototype.forEach.call( bars, function ( bar ) {
			var target = bar.style.height;

			bar.style.height = '0%';
			bar.style.transition = 'height .6s cubic-bezier(.3,.8,.3,1)';

			window.requestAnimationFrame( function () {
				window.requestAnimationFrame( function () {
					bar.style.height = target;
				} );
			} );
		} );
	}

	function init() {
		var panel = document.querySelector( '[data-ard-account]' );

		if ( ! panel || panel.ardReady ) {
			return;
		}

		panel.ardReady = true;

		initDrawer( panel );
		initSmall( panel );

		var wishlist = panel.querySelector( '[data-ard-ac-wishlist]' );

		if ( wishlist ) {
			initWishlist( wishlist );
		}

		syncWishlist();
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
