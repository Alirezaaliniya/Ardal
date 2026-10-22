/**
 * Ardal — منوی کشویی (offcanvas).
 *
 * باز شدن به هیچ دکمه‌ای بسته نیست؛ هر المانی در صفحه با یکی از این‌ها
 * کشو را کنترل می‌کند:
 *   .ard-oc-open                اولین کشوی صفحه را باز می‌کند
 *   .ard-oc-open-{شناسه}        کشوی همان شناسه را باز می‌کند
 *   .ard-oc-toggle-{شناسه}      باز/بسته
 *   .ard-oc-close               هر کشوی باز را می‌بندد
 *   <a href="#ard-oc-{شناسه}">  باز کردن با لینک
 *
 * کشو موقع راه‌اندازی به انتهای body منتقل می‌شود تا transform یا overflow
 * والدهای المنتور position:fixed را خراب نکند. پوستهٔ کلاس‌های سند و ویجت
 * همراهش می‌رود تا استایل‌های تب «استایل» المنتور همچنان اعمال شوند.
 */
( function () {
	'use strict';

	var drawers = {};
	var order = [];
	var openEl = null;
	var lastTrigger = null;

	var CHEVRON = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>';

	function editing() {
		return !! ( window.elementorFrontend && window.elementorFrontend.isEditMode && window.elementorFrontend.isEditMode() );
	}

	/* ---------------------------------------------------------------- راه‌اندازی */

	function buildSubmenus( el ) {
		var openCurrent = '1' === el.getAttribute( 'data-open-current' );

		Array.prototype.forEach.call( el.querySelectorAll( '.ard-oc__menu li.menu-item-has-children' ), function ( li ) {
			var link = li.querySelector( ':scope > a' );
			var sub = li.querySelector( ':scope > .sub-menu' );

			if ( ! link || ! sub || li.querySelector( ':scope > .ard-oc__row' ) ) {
				return;
			}

			var row = document.createElement( 'div' );
			var btn = document.createElement( 'button' );
			var id = 'ard-oc-sub-' + Math.random().toString( 36 ).slice( 2, 8 );

			row.className = 'ard-oc__row';
			btn.type = 'button';
			btn.className = 'ard-oc__sub';
			btn.setAttribute( 'aria-expanded', 'false' );
			btn.setAttribute( 'aria-controls', id );
			btn.setAttribute( 'aria-label', 'زیرمنوی ' + link.textContent.trim() );
			btn.innerHTML = CHEVRON;
			sub.id = id;

			li.insertBefore( row, link );
			row.appendChild( link );
			row.appendChild( btn );

			if ( openCurrent && ( li.classList.contains( 'current-menu-ancestor' ) || li.classList.contains( 'current-menu-parent' ) ) ) {
				setSub( li, true );
			}
		} );
	}

	function setSub( li, open ) {
		var btn = li.querySelector( ':scope > .ard-oc__row > .ard-oc__sub' );

		li.classList.toggle( 'is-sub-open', open );

		if ( btn ) {
			btn.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		}
	}

	/**
	 * انتقال به body با پوستهٔ کلاس‌ها؛ فقط در سایت، نه در ویرایشگر.
	 *
	 * @param {HTMLElement} el
	 */
	function portal( el ) {
		if ( el.parentNode && el.parentNode.hasAttribute && el.parentNode.hasAttribute( 'data-ard-oc-shell' ) ) {
			return;
		}

		var widget = el.closest( '.elementor-element' );
		var doc = el.closest( '[data-elementor-id]' );
		var outer = document.createElement( 'div' );
		var inner = document.createElement( 'div' );

		outer.className = doc ? 'elementor elementor-' + doc.getAttribute( 'data-elementor-id' ) : '';
		outer.setAttribute( 'data-ard-oc-shell', '' );
		inner.className = widget ? widget.className : '';
		// پوسته فقط برای کلاس‌هاست؛ خودش فضایی نمی‌گیرد.
		outer.style.cssText = 'position:static;margin:0;padding:0;';
		inner.style.cssText = 'margin:0;padding:0;';

		outer.appendChild( inner );
		inner.appendChild( el );
		document.body.appendChild( outer );
	}

	function register( el ) {
		if ( el.ardOcReady || el.classList.contains( 'ard-oc--preview' ) ) {
			return;
		}

		el.ardOcReady = true;
		el.classList.add( 'is-ready' );
		buildSubmenus( el );

		var id = el.getAttribute( 'data-ard-oc' );

		if ( ! editing() ) {
			// شناسهٔ تکراری: دومی نادیده گرفته می‌شود تا کشوی دوگانه باز نشود.
			if ( drawers[ id ] && drawers[ id ] !== el && document.body.contains( drawers[ id ] ) ) {
				return;
			}

			portal( el );
		}

		if ( ! drawers[ id ] || ! document.body.contains( drawers[ id ] ) ) {
			drawers[ id ] = el;

			if ( order.indexOf( id ) < 0 ) {
				order.push( id );
			}
		}

		bindSwipe( el );
	}

	function init( scope ) {
		Array.prototype.forEach.call( ( scope || document ).querySelectorAll( '.ard-oc[data-ard-oc]' ), register );
	}

	/* ---------------------------------------------------------------- باز و بسته */

	function triggers( id ) {
		return document.querySelectorAll( '.ard-oc-toggle-' + id + ', .ard-oc-open-' + id + ', [aria-controls="ard-oc-' + id + '"]' );
	}

	function setExpanded( id, on ) {
		Array.prototype.forEach.call( triggers( id ), function ( t ) {
			t.setAttribute( 'aria-expanded', on ? 'true' : 'false' );
		} );
	}

	function open( id, trigger ) {
		var el = drawers[ id ];

		if ( ! el ) {
			return;
		}

		if ( openEl && openEl !== el ) {
			close( openEl, true );
		}

		lastTrigger = trigger || document.activeElement;
		window.clearTimeout( el.ardOcTimer );
		el.hidden = false;

		// یک فریم صبر تا ترنزیشن از حالت بسته شروع شود.
		void el.offsetWidth;
		el.classList.add( 'is-open' );
		openEl = el;

		if ( '1' === el.getAttribute( 'data-lock' ) ) {
			document.documentElement.classList.add( 'ard-oc-lock' );
		}

		setExpanded( id, true );

		var focus = el.querySelector( '.ard-oc__close' ) || el.querySelector( '.ard-oc__panel' );

		window.setTimeout( function () {
			focus.focus( { preventScroll: true } );
		}, 40 );
	}

	function close( el, keepLock ) {
		if ( ! el || ! el.classList.contains( 'is-open' ) ) {
			return;
		}

		var id = el.getAttribute( 'data-ard-oc' );
		var speed = parseFloat( window.getComputedStyle( el ).getPropertyValue( '--ard-oc-speed' ) ) || 320;

		el.classList.remove( 'is-open' );
		setExpanded( id, false );

		if ( openEl === el ) {
			openEl = null;
		}

		if ( ! keepLock ) {
			document.documentElement.classList.remove( 'ard-oc-lock' );
		}

		el.ardOcTimer = window.setTimeout( function () {
			if ( ! el.classList.contains( 'is-open' ) ) {
				el.hidden = true;
			}
		}, speed + 30 );

		if ( lastTrigger && lastTrigger.focus && document.body.contains( lastTrigger ) && el.contains( document.activeElement ) ) {
			lastTrigger.focus( { preventScroll: true } );
		}
	}

	/**
	 * شناسهٔ کشو از روی المان کلیک‌شده.
	 *
	 * @param {HTMLElement} t
	 * @return {{id: string, mode: string}|null}
	 */
	function parseTrigger( t ) {
		var m;

		for ( var i = 0; i < t.classList.length; i++ ) {
			m = /^ard-oc-(open|toggle)-([a-z0-9-]+)$/.exec( t.classList[ i ] );

			if ( m ) {
				return { id: m[ 2 ], mode: m[ 1 ] };
			}
		}

		if ( t.classList.contains( 'ard-oc-open' ) || t.classList.contains( 'ard-oc-toggle' ) ) {
			return { id: order[ 0 ], mode: t.classList.contains( 'ard-oc-toggle' ) ? 'toggle' : 'open' };
		}

		var href = t.getAttribute( 'href' ) || '';

		m = /^#ard-oc(?:-([a-z0-9-]+))?$/.exec( href );

		if ( m ) {
			return { id: m[ 1 ] || order[ 0 ], mode: 'toggle' };
		}

		return null;
	}

	var SELECTOR = '.ard-oc-open, [class*="ard-oc-open-"], .ard-oc-toggle, [class*="ard-oc-toggle-"], a[href^="#ard-oc"]';

	document.addEventListener( 'click', function ( e ) {
		var t = e.target.closest ? e.target.closest( SELECTOR ) : null;

		// کلیک داخل خودِ کشو روی دکمهٔ «باز» نباید دوباره بازش کند.
		if ( t && ! t.closest( '.ard-oc' ) ) {
			var info = parseTrigger( t );

			if ( info && info.id && drawers[ info.id ] ) {
				e.preventDefault();

				if ( 'toggle' === info.mode && drawers[ info.id ].classList.contains( 'is-open' ) ) {
					close( drawers[ info.id ] );
				} else {
					open( info.id, t );
				}

				return;
			}
		}

		if ( e.target.closest && e.target.closest( '.ard-oc-close' ) && openEl ) {
			e.preventDefault();
			close( openEl );

			return;
		}

		var el = e.target.closest ? e.target.closest( '.ard-oc.is-open' ) : null;

		if ( ! el ) {
			return;
		}

		var dismiss = e.target.closest( '[data-ard-oc-dismiss]' );

		if ( dismiss ) {
			if ( 'overlay' !== dismiss.getAttribute( 'data-ard-oc-dismiss' ) || '1' === el.getAttribute( 'data-close-overlay' ) ) {
				close( el );
			}

			return;
		}

		var sub = e.target.closest( '.ard-oc__sub' );

		if ( sub ) {
			var li = sub.closest( 'li' );

			setSub( li, ! li.classList.contains( 'is-sub-open' ) );

			return;
		}

		var link = e.target.closest( '.ard-oc__menu a' );

		if ( link ) {
			var href = link.getAttribute( 'href' ) || '';
			var item = link.closest( 'li' );

			// والدِ بی‌لینک (#) فقط زیرمنو را باز و بسته می‌کند.
			if ( ( '' === href || '#' === href ) && item.classList.contains( 'menu-item-has-children' ) ) {
				e.preventDefault();
				setSub( item, ! item.classList.contains( 'is-sub-open' ) );

				return;
			}

			if ( '1' === el.getAttribute( 'data-close-link' ) ) {
				close( el );
			}
		}
	} );

	document.addEventListener( 'keydown', function ( e ) {
		if ( ! openEl ) {
			return;
		}

		if ( 'Escape' === e.key ) {
			close( openEl );

			return;
		}

		// فوکوس داخل کشوی باز می‌چرخد.
		if ( 'Tab' === e.key ) {
			var items = Array.prototype.filter.call(
				openEl.querySelectorAll( 'a[href], button:not([disabled]), input:not([disabled]), select, textarea, [tabindex]:not([tabindex="-1"])' ),
				function ( x ) {
					return x.offsetParent !== null;
				}
			);

			if ( ! items.length ) {
				return;
			}

			var first = items[ 0 ];
			var last = items[ items.length - 1 ];

			if ( e.shiftKey && document.activeElement === first ) {
				e.preventDefault();
				last.focus();
			} else if ( ! e.shiftKey && document.activeElement === last ) {
				e.preventDefault();
				first.focus();
			}
		}
	} );

	/* ---------------------------------------------------------------- کشیدن برای بستن */

	function bindSwipe( el ) {
		var panel = el.querySelector( '.ard-oc__panel' );
		var startX = null;
		var startY = 0;
		var dx = 0;
		var right = el.classList.contains( 'ard-oc--right' );

		panel.addEventListener( 'touchstart', function ( e ) {
			startX = e.touches[ 0 ].clientX;
			startY = e.touches[ 0 ].clientY;
			dx = 0;
		}, { passive: true } );

		panel.addEventListener( 'touchmove', function ( e ) {
			if ( null === startX ) {
				return;
			}

			dx = e.touches[ 0 ].clientX - startX;

			// حرکت عمودی (اسکرول منو) کشو را جابه‌جا نکند.
			if ( Math.abs( e.touches[ 0 ].clientY - startY ) > Math.abs( dx ) ) {
				startX = null;
				panel.style.transform = '';

				return;
			}

			var shift = right ? Math.max( 0, dx ) : Math.min( 0, dx );

			panel.style.transition = 'none';
			panel.style.transform = 'translateX(' + shift + 'px)';
		}, { passive: true } );

		panel.addEventListener( 'touchend', function () {
			panel.style.transition = '';
			panel.style.transform = '';

			if ( null !== startX && ( right ? dx > 70 : dx < -70 ) ) {
				close( el );
			}

			startX = null;
		} );
	}

	/* ---------------------------------------------------------------- شروع */

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', function () {
			init();
		} );
	} else {
		init();
	}

	if ( window.jQuery ) {
		window.jQuery( window ).on( 'elementor/frontend/init', function () {
			if ( window.elementorFrontend && window.elementorFrontend.hooks ) {
				window.elementorFrontend.hooks.addAction( 'frontend/element_ready/ardal-offcanvas.default', function ( $scope ) {
					init( $scope[ 0 ] );
				} );
			}
		} );
	}

	// برای استفاده در کد دیگر: ArdalOffcanvas.open('menu')
	window.ArdalOffcanvas = {
		open: function ( id ) {
			open( id || order[ 0 ] );
		},
		close: function () {
			close( openEl );
		},
	};
}() );
