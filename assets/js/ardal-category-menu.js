/**
 * Ardal — منوی دسته‌بندی.
 *
 * دسکتاپ: آیتم فعال پنل زیردسته‌هایش را نشان می‌دهد (با هاور یا کلیک).
 * موبایل (زیر ۱۰۲۴): آکاردئون؛ لمس آیتم دارای زیرمنو بازش می‌کند.
 * حالت پنجره: با لینک «#شناسه» یا کلاس ard-open-catmenu باز می‌شود.
 */
( function () {
	'use strict';

	var MOBILE = window.matchMedia ? window.matchMedia( '(max-width: 1024px)' ) : null;

	function isMobile() {
		return !! ( MOBILE && MOBILE.matches );
	}

	function items( root ) {
		return Array.prototype.slice.call( root.querySelectorAll( '.ard-cm__item' ) );
	}

	function rowOf( item ) {
		return item.querySelector( ':scope > .ard-cm__row' );
	}

	/**
	 * یک آیتم را فعال می‌کند (دسکتاپ: پنلش؛ موبایل: کارت‌هایش).
	 *
	 * @param {Element}      root
	 * @param {Element|null} item  null یعنی هیچ‌کدام.
	 * @param {boolean}      keepOthers در موبایل با «چند آیتم باز» بقیه بسته نشوند.
	 */
	function activate( root, item, keepOthers ) {
		items( root ).forEach( function ( el ) {
			if ( el === item || ( keepOthers && el.classList.contains( 'is-active' ) ) ) {
				return;
			}

			el.classList.remove( 'is-active' );

			var row = rowOf( el );

			if ( row && row.hasAttribute( 'aria-expanded' ) ) {
				row.setAttribute( 'aria-expanded', 'false' );
			}
		} );

		if ( item ) {
			item.classList.add( 'is-active' );

			var row = rowOf( item );

			if ( row && row.hasAttribute( 'aria-expanded' ) ) {
				row.setAttribute( 'aria-expanded', 'true' );
			}
		}

		// پنل دسکتاپ: فقط صفحهٔ همان آیتم.
		var id = item ? item.getAttribute( 'data-ard-cm-item' ) : '';

		Array.prototype.forEach.call( root.querySelectorAll( '[data-ard-cm-pane]' ), function ( pane ) {
			pane.hidden = pane.getAttribute( 'data-ard-cm-pane' ) !== id;
		} );
	}

	/**
	 * حالت شروع بر اساس اندازهٔ صفحه.
	 */
	function reset( root ) {
		var first = root.querySelector( '.ard-cm__item.is-default' );

		if ( isMobile() ) {
			activate( root, '1' === root.getAttribute( 'data-mobile-open' ) ? first : null );

			return;
		}

		activate( root, first );
	}

	function setup( root ) {
		if ( root.ardCmReady ) {
			return;
		}

		root.ardCmReady = true;

		var hover = 'hover' === root.getAttribute( 'data-open' );
		var single = '0' !== root.getAttribute( 'data-single' );
		var timer = null;

		reset( root );

		items( root ).forEach( function ( item ) {
			if ( ! item.classList.contains( 'has-children' ) ) {
				return;
			}

			var row = rowOf( item );

			if ( ! row ) {
				return;
			}

			/*
			 * هاور با تأخیر کوتاه: وقتی ماوس به‌طور مورب از روی آیتم‌ها به
			 * پنل می‌رود، آیتم‌های سر راه نباید پنل را عوض کنند.
			 */
			item.addEventListener( 'mouseenter', function () {
				if ( ! hover || isMobile() ) {
					return;
				}

				window.clearTimeout( timer );
				timer = window.setTimeout( function () {
					activate( root, item );
				}, 90 );
			} );

			item.addEventListener( 'mouseleave', function () {
				window.clearTimeout( timer );
			} );

			// فوکوس با صفحه‌کلید همان کار هاور را می‌کند.
			row.addEventListener( 'focus', function () {
				if ( hover && ! isMobile() ) {
					activate( root, item );
				}
			} );

			row.addEventListener( 'click', function ( event ) {
				if ( isMobile() ) {
					event.preventDefault();

					if ( item.classList.contains( 'is-active' ) ) {
						item.classList.remove( 'is-active' );
						row.setAttribute( 'aria-expanded', 'false' );

						return;
					}

					activate( root, item, ! single );

					// آیتم بازشده اگر از دید بیرون رفته، به دید بیاید.
					window.requestAnimationFrame( function () {
						var top = item.getBoundingClientRect().top;

						if ( top < 0 ) {
							item.scrollIntoView( { block: 'start', behavior: 'smooth' } );
						}
					} );

					return;
				}

				// دسکتاپ در حالت کلیک: کلیک اول پنل را باز می‌کند، دوم لینک را.
				if ( ! hover && ! item.classList.contains( 'is-active' ) ) {
					event.preventDefault();
					activate( root, item );
				}

				// دکمهٔ بی‌لینک در حالت هاور هم باید با کلیک کار کند.
				if ( 'BUTTON' === row.tagName ) {
					activate( root, item );
				}
			} );
		} );

		if ( MOBILE ) {
			var onChange = function () {
				reset( root );
			};

			if ( MOBILE.addEventListener ) {
				MOBILE.addEventListener( 'change', onChange );
			} else if ( MOBILE.addListener ) {
				MOBILE.addListener( onChange );
			}
		}
	}

	/* ==================================================================
	   حالت پنجره
	   ================================================================== */

	/*
	 * پنجره position: fixed است و fixed زیر والدِ دارای transform (انیمیشن،
	 * بخش چسبان المنتور…) نسبت به همان والد جا می‌گیرد. پس موقع باز شدن به
	 * body منتقل می‌شود، با لایه‌هایی که کلاس سند و ویجت المنتور را دارند تا
	 * استایل‌های {{WRAPPER}} همچنان بچسبند؛ با بستن به جای خودش برمی‌گردد.
	 */
	function portal( root ) {
		if ( root.ardHost ) {
			return;
		}

		var source = root.closest( '.elementor-element' );
		var page = root.closest( '[data-elementor-id]' );
		var outer = document.createElement( 'div' );
		var host = document.createElement( 'div' );
		var keep = [ 'ard-cm-portal' ];
		var wrap = [];

		if ( source ) {
			keep.push( 'elementor-element' );

			Array.prototype.forEach.call( source.classList, function ( name ) {
				if ( 0 === name.indexOf( 'elementor-element-' ) || 0 === name.indexOf( 'elementor-widget-' ) ) {
					keep.push( name );
				}
			} );
		}

		if ( page ) {
			Array.prototype.forEach.call( page.classList, function ( name ) {
				if ( /^elementor-\d+$/.test( name ) ) {
					wrap.push( name );
				}
			} );
		}

		host.className = keep.join( ' ' );
		outer.className = wrap.join( ' ' );

		var mark = document.createComment( 'ard-cm' );

		root.parentNode.insertBefore( mark, root );
		host.appendChild( root );
		outer.appendChild( host );
		document.body.appendChild( outer );

		root.ardHost = outer;
		root.ardMark = mark;
	}

	function unportal( root ) {
		if ( ! root.ardHost ) {
			return;
		}

		if ( root.ardMark && root.ardMark.parentNode ) {
			root.ardMark.parentNode.insertBefore( root, root.ardMark );
			root.ardMark.parentNode.removeChild( root.ardMark );
		}

		if ( root.ardHost.parentNode ) {
			root.ardHost.parentNode.removeChild( root.ardHost );
		}

		root.ardHost = null;
		root.ardMark = null;
	}

	var openRoot = null;
	var lastTrigger = null;

	function openPopup( root, trigger ) {
		if ( openRoot && openRoot !== root ) {
			closePopup();
		}

		lastTrigger = trigger || null;
		portal( root );

		// حالت بسته پیش از افزودن کلاس ثبت شود، وگرنه گذار اجرا نمی‌شود.
		void root.offsetHeight;

		reset( root );
		root.classList.add( 'is-open' );
		document.documentElement.classList.add( 'ard-cm-locked' );
		openRoot = root;

		var close = root.querySelector( '.ard-cm__close' );

		if ( close && isMobile() ) {
			close.focus( { preventScroll: true } );
		} else {
			var first = root.querySelector( '.ard-cm__row' );

			if ( first ) {
				first.focus( { preventScroll: true } );
			}
		}
	}

	function closePopup() {
		if ( ! openRoot ) {
			return;
		}

		var root = openRoot;

		openRoot = null;
		root.classList.remove( 'is-open' );
		document.documentElement.classList.remove( 'ard-cm-locked' );

		// پس از پایان گذار به جای خودش برگردد.
		window.setTimeout( function () {
			if ( openRoot !== root ) {
				unportal( root );
			}
		}, 300 );

		if ( lastTrigger && lastTrigger.focus ) {
			lastTrigger.focus( { preventScroll: true } );
		}
	}

	/**
	 * پنجره‌ای که یک کلیک باید باز کند، یا null.
	 */
	function popupFor( target ) {
		var link = target.closest( 'a[href*="#"]' );

		if ( link ) {
			var hash = link.getAttribute( 'href' ).split( '#' )[ 1 ] || '';

			if ( hash ) {
				var byId = document.getElementById( hash );

				if ( byId && byId.matches( '.ard-cm--popup:not(.ard-cm--preview)' ) ) {
					return byId;
				}
			}
		}

		if ( target.closest( '.ard-open-catmenu' ) ) {
			return document.querySelector( '.ard-cm--popup:not(.ard-cm--preview)' );
		}

		return null;
	}

	// در مرحلهٔ capture، پیش از اسکرول نرم المنتور روی لینک‌های «#».
	document.addEventListener( 'click', function ( event ) {
		if ( event.target.closest( '[data-ard-cm-close]' ) ) {
			event.preventDefault();
			closePopup();

			return;
		}

		var root = popupFor( event.target );

		if ( ! root || root.contains( event.target ) ) {
			return;
		}

		event.preventDefault();
		event.stopPropagation();

		if ( openRoot === root ) {
			closePopup();
		} else {
			openPopup( root, event.target.closest( 'a, button, .ard-open-catmenu' ) );
		}
	}, true );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key && openRoot ) {
			closePopup();
		}
	} );

	/* ==================================================================
	   راه‌اندازی
	   ================================================================== */

	function init( scope ) {
		Array.prototype.forEach.call( ( scope || document ).querySelectorAll( '[data-ard-cm]' ), setup );
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
			'frontend/element_ready/ardal-category-menu.default',
			function ( $scope ) {
				init( $scope && $scope[ 0 ] ? $scope[ 0 ] : undefined );
			}
		);
	} );
}() );
