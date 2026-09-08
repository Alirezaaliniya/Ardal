/**
 * Ardal — رفتار پنل کاربری.
 *
 * ناوبری ایجکسی، کشوی موبایل، کپی کد تخفیف، چاپ فاکتور و علاقه‌مندی‌ها.
 *
 * ناوبری ایجکسی هیچ فهرست ثابتی از اندپوینت‌ها ندارد: هر لینکی که به صفحهٔ
 * حساب کاربری اشاره کند گرفته می‌شود و سرور خودش تصمیم می‌گیرد. پس آیتمی که
 * افزونهٔ دیگری به منو اضافه کند هم بدون تغییر این فایل ایجکسی بارگذاری
 * می‌شود. اگر سرور نشانی را نشناسد، مرورگر مثل قبل صفحه را کامل باز می‌کند.
 *
 * بدون وابستگی به jQuery (اگر باشد فقط برای اطلاع دادن به افزونه‌ها استفاده
 * می‌شود).
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
	 * درخواست به admin-ajax با بدنهٔ فرم.
	 *
	 * @param {Object} data
	 * @return {Promise}
	 */
	function post( data ) {
		if ( ! CONFIG.ajax || ! CONFIG.nonce ) {
			return window.Promise.reject( new Error( 'no-config' ) );
		}

		var body = new window.FormData();

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

		return window.fetch( CONFIG.ajax, { method: 'POST', body: body, credentials: 'same-origin' } )
			.then( function ( response ) {
				return response.json();
			} );
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
	 * ویجت‌های محصول علاقه‌مندی را در localStorage نگه می‌دارند. یک بار در هر
	 * نشست، آن فهرست با حساب کاربر ادغام می‌شود تا صفحه‌ای که سمت سرور رندر
	 * می‌شود چیزی را جا نیندازد.
	 */
	function syncWishlist() {
		if ( ! CONFIG.loggedIn ) {
			return;
		}

		var already = false;

		try {
			already = '1' === window.sessionStorage.getItem( SYNCED );
		} catch ( e ) {}

		if ( already ) {
			return;
		}

		post( { action: 'ardal_wishlist', op: 'sync', ids: localIds() } )
			.then( function ( result ) {
				if ( ! result || ! result.success ) {
					return;
				}

				saveLocal( result.data.ids || [] );

				try {
					window.sessionStorage.setItem( SYNCED, '1' );
				} catch ( e ) {}

				var page = document.querySelector( '[data-ard-ac-wishlist]' );

				if ( page && ( result.data.ids || [] ).length !== page.querySelectorAll( '[data-ard-ac-fav]' ).length ) {
					window.location.reload();
				}
			} )
			.catch( function () {} );
	}

	function removeFavourite( button ) {
		var id = parseInt( button.getAttribute( 'data-ard-ac-unfav' ), 10 );
		var card = button.closest( '[data-ard-ac-fav]' );

		if ( ! id || ! card ) {
			return;
		}

		card.classList.add( 'is-removing' );
		saveLocal( localIds().filter( function ( item ) {
			return parseInt( item, 10 ) !== id;
		} ) );

		post( { action: 'ardal_wishlist', op: 'remove', product: id } )
			.then( function () {
				if ( card.parentNode ) {
					card.parentNode.removeChild( card );
				}
			} )
			.catch( function () {
				card.classList.remove( 'is-removing' );
			} );
	}

	/* ---------------- ناوبری ایجکسی ---------------- */

	/**
	 * آیا این لینک باید ایجکسی باز شود؟ تنها شرط اصلی، زیر نشانی صفحهٔ حساب
	 * کاربری بودن است — نه فهرستی از اندپوینت‌های شناخته‌شده.
	 *
	 * @param {HTMLAnchorElement} link
	 * @param {MouseEvent}        event
	 * @return {boolean}
	 */
	function isAjaxLink( link, event ) {
		if ( ! CONFIG.accountUrl || ! link || ! link.href ) {
			return false;
		}

		if ( event && ( event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || 0 !== event.button ) ) {
			return false;
		}

		if ( link.target && '_self' !== link.target ) {
			return false;
		}

		if ( link.hasAttribute( 'download' ) || link.getAttribute( 'rel' ) === 'external' ) {
			return false;
		}

		// راه فرار برای هر لینکی که نباید ایجکسی شود.
		if ( link.closest( '[data-ard-no-ajax]' ) ) {
			return false;
		}

		var href = link.href.split( '#' )[ 0 ];

		if ( 0 !== href.indexOf( CONFIG.accountUrl ) ) {
			return false;
		}

		// خروج از حساب باید درخواست واقعی باشد تا کوکی پاک شود.
		if ( CONFIG.logoutUrl && 0 === href.indexOf( CONFIG.logoutUrl.split( '?' )[ 0 ] ) ) {
			return false;
		}

		// لینک دانلود فایل، حتی اگر زیر همین نشانی باشد.
		if ( href.indexOf( 'download_file=' ) > -1 ) {
			return false;
		}

		return true;
	}

	/**
	 * تزریق استایل‌ها و اسکریپت‌هایی که اندپوینت تازه لازم دارد.
	 * هندلی که از قبل در صفحه باشد رد می‌شود (وردپرس روی هر تگ id می‌گذارد).
	 *
	 * @param {Object} assets
	 * @return {Promise}
	 */
	function injectAssets( assets ) {
		if ( ! assets ) {
			return window.Promise.resolve();
		}

		var holder = document.createElement( 'div' );

		holder.innerHTML = ( assets.styles || '' ) + ( assets.scripts || '' );

		var nodes = Array.prototype.slice.call( holder.childNodes );

		return nodes.reduce( function ( chain, node ) {
			if ( 1 !== node.nodeType ) {
				return chain;
			}

			var id = node.getAttribute( 'id' );

			if ( id && document.getElementById( id ) ) {
				return chain;
			}

			if ( 'LINK' === node.tagName || 'STYLE' === node.tagName ) {
				document.head.appendChild( node );

				return chain;
			}

			if ( 'SCRIPT' !== node.tagName ) {
				return chain;
			}

			return chain.then( function () {
				return new window.Promise( function ( resolve ) {
					var script = document.createElement( 'script' );

					Array.prototype.forEach.call( node.attributes, function ( attr ) {
						script.setAttribute( attr.name, attr.value );
					} );

					if ( node.src ) {
						// اسکریپت بعدی باید بعد از اجرای این یکی بیاید.
						script.onload = resolve;
						script.onerror = resolve;
						document.head.appendChild( script );

						return;
					}

					script.textContent = node.textContent;
					document.head.appendChild( script );
					resolve();
				} );
			} );
		}, window.Promise.resolve() );
	}

	function initNavigation( panel ) {
		var content = panel.querySelector( '.ard-ac__content' );

		if ( ! content || ! CONFIG.accountUrl || ! window.fetch || ! window.Promise ) {
			return null;
		}

		var busy = false;

		content.setAttribute( 'tabindex', '-1' );

		function menuLinks() {
			return document.querySelectorAll( '[data-ard-endpoint]' );
		}

		/**
		 * کدام آیتم منو فعال است را سرور می‌گوید، چون ووکامرس حالت‌های ویژه
		 * دارد (جزئیات سفارش زیر «سفارش‌های من» می‌نشیند).
		 *
		 * @param {Array} active
		 */
		function markActive( active ) {
			var keys = active || [];

			Array.prototype.forEach.call( menuLinks(), function ( link ) {
				var on = keys.indexOf( link.getAttribute( 'data-ard-endpoint' ) ) > -1;

				link.classList.toggle( 'is-active', on );

				if ( on ) {
					link.setAttribute( 'aria-current', 'page' );
				} else {
					link.removeAttribute( 'aria-current' );
				}
			} );
		}

		function updateBadges( badges ) {
			if ( ! badges ) {
				return;
			}

			Array.prototype.forEach.call( menuLinks(), function ( link ) {
				var key = link.getAttribute( 'data-ard-endpoint' );

				if ( ! badges.hasOwnProperty( key ) ) {
					return;
				}

				var badge = link.querySelector( '.ard-ac__nav-badge, .ard-ac__drawer-badge' );

				if ( ! badge ) {
					return;
				}

				badge.textContent = badges[ key ];
				badge.hidden = '' === badges[ key ];
			} );
		}

		/**
		 * به بقیه خبر می‌دهد محتوا عوض شده تا خودشان را دوباره راه بیندازند.
		 *
		 * @param {string} endpoint
		 */
		function announce( endpoint ) {
			panel.dispatchEvent( new window.CustomEvent( 'ardal-account:loaded', {
				bubbles: true,
				detail: { endpoint: endpoint, content: content }
			} ) );

			if ( ! window.jQuery ) {
				return;
			}

			var $ = window.jQuery;

			// قلاب‌های خود ووکامرس برای راه‌اندازی دوبارهٔ فیلدها.
			$( document.body ).trigger( 'ardal_account_loaded', [ endpoint, content ] );
			$( document.body ).trigger( 'wc-enhanced-select-init' );
			$( content ).find( 'select.country_to_state, input.country_to_state' ).trigger( 'change' );
		}

		function finish( data ) {
			content.innerHTML = data.html;
			content.removeAttribute( 'aria-busy' );
			panel.classList.remove( 'is-loading' );

			markActive( data.active );
			updateBadges( data.badges );
			animateBars( content );

			if ( data.title ) {
				document.title = CONFIG.siteName ? data.title + ' – ' + CONFIG.siteName : data.title;
			}

			var top = panel.getBoundingClientRect().top + window.pageYOffset - 20;

			window.scrollTo( { top: top > 0 ? top : 0, behavior: reduceMotion() ? 'auto' : 'smooth' } );
			content.focus( { preventScroll: true } );

			announce( data.endpoint );
		}

		/**
		 * @param {string}  url
		 * @param {boolean} push آیا در تاریخچهٔ مرورگر ثبت شود.
		 */
		function load( url, push ) {
			if ( busy ) {
				return;
			}

			busy = true;
			panel.classList.add( 'is-loading' );
			content.setAttribute( 'aria-busy', 'true' );

			post( { action: 'ardal_account_page', url: url } )
				.then( function ( result ) {
					if ( ! result || ! result.success || ! result.data ) {
						throw new Error( 'bad-response' );
					}

					return injectAssets( result.data.assets ).then( function () {
						if ( false !== push ) {
							window.history.pushState( { ardalAccount: true }, '', result.data.url || url );
						}

						finish( result.data );
						busy = false;
					} );
				} )
				.catch( function () {
					// هر مشکلی پیش بیاید، مرورگر خودش صفحه را باز می‌کند.
					window.location.href = url;
				} );
		}

		document.addEventListener( 'click', function ( event ) {
			var link = event.target.closest ? event.target.closest( 'a[href]' ) : null;

			if ( ! link ) {
				return;
			}

			// لینک‌های داخل پنل و داخل کشو (که ممکن است به body منتقل شده باشد).
			if ( ! panel.contains( link ) && ! link.closest( '[data-ard-ac-drawer]' ) ) {
				return;
			}

			if ( ! isAjaxLink( link, event ) ) {
				return;
			}

			event.preventDefault();

			var url = link.href;

			if ( url.split( '#' )[ 0 ] === window.location.href.split( '#' )[ 0 ] ) {
				return;
			}

			panel.dispatchEvent( new window.CustomEvent( 'ardal-account:navigate', { bubbles: true } ) );
			load( url, true );
		} );

		window.addEventListener( 'popstate', function ( event ) {
			if ( ! event.state || ! event.state.ardalAccount ) {
				return;
			}

			load( window.location.href, false );
		} );

		// وضعیت اولیه، تا برگشت به صفحهٔ اول هم ایجکسی باشد.
		window.history.replaceState( { ardalAccount: true }, '', window.location.href );

		return load;
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

		// بعد از رفتن به صفحهٔ تازه، کشو بسته می‌شود.
		panel.addEventListener( 'ardal-account:navigate', function () {
			close( false );
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

	/**
	 * میله‌های نمودار از صفر بالا می‌آیند.
	 *
	 * @param {HTMLElement} scope
	 */
	function animateBars( scope ) {
		if ( reduceMotion() ) {
			return;
		}

		Array.prototype.forEach.call( scope.querySelectorAll( '.ard-ac__bar' ), function ( bar ) {
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

	function copyCode( button ) {
		var code = button.getAttribute( 'data-ard-ac-copy' );
		var label = button.textContent;

		var done = function () {
			button.textContent = 'کپی شد';
			window.setTimeout( function () {
				button.textContent = label;
			}, 1800 );
		};

		if ( window.navigator.clipboard && window.navigator.clipboard.writeText ) {
			window.navigator.clipboard.writeText( code ).then( done ).catch( function () {} );

			return;
		}

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

	/**
	 * همهٔ کنش‌های داخل محتوا واگذارشده‌اند، چون محتوا با هر بارگذاری ایجکسی
	 * عوض می‌شود و شنونده‌های مستقیم از بین می‌روند.
	 *
	 * @param {HTMLElement} panel
	 */
	function initActions( panel ) {
		panel.addEventListener( 'click', function ( event ) {
			var target = event.target;

			if ( ! target.closest ) {
				return;
			}

			var copy = target.closest( '[data-ard-ac-copy]' );

			if ( copy ) {
				event.preventDefault();
				copyCode( copy );

				return;
			}

			if ( target.closest( '[data-ard-ac-print]' ) ) {
				event.preventDefault();
				window.print();

				return;
			}

			var unfav = target.closest( '[data-ard-ac-unfav]' );

			if ( unfav ) {
				event.preventDefault();
				removeFavourite( unfav );

				return;
			}

			if ( target.closest( '[data-ard-ac-add-all]' ) ) {
				var buttons = panel.querySelectorAll( '.ard-ac__fav-foot a.add_to_cart_button, .ard-ac__fav-foot a.ajax_add_to_cart' );

				Array.prototype.forEach.call( buttons, function ( button, index ) {
					window.setTimeout( function () {
						button.click();
					}, index * 350 );
				} );
			}
		} );
	}

	function init() {
		var panel = document.querySelector( '[data-ard-account]' );

		if ( ! panel || panel.ardReady ) {
			return;
		}

		panel.ardReady = true;

		initDrawer( panel );
		initActions( panel );
		initNavigation( panel );
		animateBars( panel );
		syncWishlist();
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
