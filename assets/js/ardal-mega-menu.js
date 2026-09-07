/**
 * Ardal — رفتار مگا منو.
 *
 * دسکتاپ: پنل با هاور/فوکوس باز می‌شود (CSS) و اینجا فقط کلیک، صفحه‌کلید و
 * لمس مدیریت می‌شود. موبایل: کشوی آف‌کنواس با آکاردئون، قفل اسکرول و تله فوکوس.
 *
 * حالت «پردهٔ موجی» از الگوی Shape Overlays گرفته شده: چند مسیر SVG که با
 * منحنی بزیه و توابع ایزینگ نمایی، کادر به کادر از پایین صفحه بالا می‌آیند.
 * بدون وابستگی به jQuery.
 */
( function () {
	'use strict';

	var FOCUSABLE = 'a[href], button:not([disabled]), input, select, textarea, [tabindex]:not([tabindex="-1"])';

	// متغیرهایی که کنترل‌های المنتور روی ریشهٔ ویجت می‌نویسند. کشو و پرده که به
	// body می‌روند دیگر آن‌ها را به ارث نمی‌برند، پس دستی کپی می‌شوند.
	var VARS = [
		'--ard-mm-gap',
		'--ard-mm-panel-pad',
		'--ard-mm-drawer-w',
		'--ard-mm-panel-bg',
		'--ard-mm-link',
		'--ard-mm-link-active',
		'--ard-mm-wave-1',
		'--ard-mm-wave-2',
		'--ard-mm-wave-3',
		'--ard-mm-burger-size',
		'--ard-mm-burger-line',
		'--ard-mm-burger-bg',
		'--ard-mm-burger-ink'
	];

	function reduceMotion() {
		return window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	}

	/* ------------------------------------------------------------
	   پردهٔ موجی
	   ------------------------------------------------------------ */

	// همان ایزینگ‌های glsl-easings که الگوی اصلی استفاده می‌کند.
	function cubicOut( t ) {
		var f = t - 1;

		return f * f * f + 1;
	}

	function cubicInOut( t ) {
		return t < 0.5 ? 4 * t * t * t : 0.5 * Math.pow( 2 * t - 2, 3 ) + 1;
	}

	function ShapeOverlays( el ) {
		this.el = el;
		this.paths = el.querySelectorAll( 'path' );
		this.numPoints = 2;
		this.duration = 600;
		this.delayPerPath = 200;
		this.timeStart = Date.now();
		this.isOpened = false;
		this.isAnimating = false;
	}

	ShapeOverlays.prototype.total = function () {
		return this.duration + this.delayPerPath * ( this.paths.length - 1 );
	};

	ShapeOverlays.prototype.open = function () {
		this.isOpened = true;
		this.el.classList.add( 'is-opened' );
		this.start();
	};

	ShapeOverlays.prototype.close = function () {
		this.isOpened = false;
		this.el.classList.remove( 'is-opened' );
		this.start();
	};

	ShapeOverlays.prototype.start = function () {
		// با کاهش انیمیشن، پرده بدون حرکت کامل باز یا بسته می‌شود.
		if ( reduceMotion() ) {
			this.isAnimating = false;
			this.fill( this.isOpened ? 'M 0 0 H 100 V 100 H 0 Z' : 'M 0 100 H 100 V 100 H 0 Z' );

			return;
		}

		this.isAnimating = true;
		this.timeStart = Date.now();
		this.render();
	};

	ShapeOverlays.prototype.fill = function ( d ) {
		for ( var i = 0; i < this.paths.length; i++ ) {
			this.paths[ i ].setAttribute( 'd', d );
		}
	};

	/**
	 * مسیر یک لایه در لحظهٔ داده‌شده: نقطهٔ ابتدا و انتها با دو ایزینگ متفاوت
	 * حرکت می‌کنند و اختلاف‌شان همان قوس موج را می‌سازد.
	 */
	ShapeOverlays.prototype.path = function ( time ) {
		var points = [];
		var i;

		for ( i = 0; i < this.numPoints; i++ ) {
			var ease = this.isOpened
				? ( 1 === i ? cubicOut : cubicInOut )
				: ( 1 === i ? cubicInOut : cubicOut );

			points[ i ] = ease( Math.min( Math.max( time, 0 ) / this.duration, 1 ) ) * 100;
		}

		var d = this.isOpened ? 'M 0 0 V ' + points[ 0 ] + ' ' : 'M 0 ' + points[ 0 ] + ' ';

		for ( i = 0; i < this.numPoints - 1; i++ ) {
			var p = ( i + 1 ) / ( this.numPoints - 1 ) * 100;
			var cp = p - ( 1 / ( this.numPoints - 1 ) * 100 ) / 2;

			d += 'C ' + cp + ' ' + points[ i ] + ' ' + cp + ' ' + points[ i + 1 ] + ' ' + p + ' ' + points[ i + 1 ] + ' ';
		}

		return d + ( this.isOpened ? 'V 0 H 0' : 'V 100 H 0' );
	};

	ShapeOverlays.prototype.render = function () {
		var elapsed = Date.now() - this.timeStart;
		var count = this.paths.length;
		var i;

		for ( i = 0; i < count; i++ ) {
			// هنگام بستن، ترتیب تأخیر لایه‌ها برعکس می‌شود.
			var order = this.isOpened ? i : count - i - 1;

			this.paths[ i ].setAttribute( 'd', this.path( elapsed - this.delayPerPath * order ) );
		}

		if ( elapsed < this.total() ) {
			var self = this;

			window.requestAnimationFrame( function () {
				self.render();
			} );

			return;
		}

		this.isAnimating = false;
	};

	/**
	 * پنل مستقیم زیر یک آیتم (نه پنل‌های تودرتو).
	 */
	function childPanel( item ) {
		for ( var i = 0; i < item.children.length; i++ ) {
			if ( item.children[ i ].hasAttribute( 'data-ard-mm-panel' ) ) {
				return item.children[ i ];
			}
		}

		return null;
	}

	function childToggle( item ) {
		var row = item.querySelector( '.ard-mm__row' );

		return row ? row.querySelector( '[data-ard-mm-toggle]' ) : null;
	}

	/**
	 * پاک کردن کشوی جامانده در body — در ویرایشگر المنتور ویجت دوباره رندر می‌شود.
	 */
	function dropStale( owner ) {
		var stale = document.querySelectorAll( '[data-ard-mm-owner="' + owner + '"]' );

		Array.prototype.forEach.call( stale, function ( el ) {
			if ( el.parentNode ) {
				el.parentNode.removeChild( el );
			}
		} );

		if ( stale.length ) {
			document.documentElement.classList.remove( 'ard-mm-lock' );
		}
	}

	function setup( root ) {
		var nav = root.querySelector( '[data-ard-mm-nav]' );
		var burger = root.querySelector( '[data-ard-mm-burger]' );

		if ( ! nav || ! burger ) {
			return;
		}

		// پردهٔ موجی و پردهٔ تیرهٔ کشو هرکدام فقط در حالت خودشان رندر می‌شوند.
		var waveEl = root.querySelector( '[data-ard-mm-wave]' );
		var scrim = root.querySelector( '[data-ard-mm-scrim]' );
		var closeBtn = nav.querySelector( '[data-ard-mm-close]' );
		var overlay = waveEl ? new ShapeOverlays( waveEl ) : null;
		var floating = waveEl ? [ nav, waveEl ] : [ nav, scrim ];
		var morphTimer = null;

		var owner = nav.id || String( Math.random() ).slice( 2 );
		var placeholder = document.createComment( 'ard-mm' );
		var breakpoint = parseInt( root.getAttribute( 'data-breakpoint' ), 10 ) || 1024;
		var mq = window.matchMedia( '(max-width: ' + ( breakpoint - 0.02 ) + 'px)' );
		var moved = false;
		var isMobile = false;
		var lastFocus = null;

		dropStale( owner );

		if ( scrim ) {
			scrim.removeAttribute( 'hidden' );
		}

		/* ---------------- جابه‌جایی کشو به body ----------------
		   کشو position:fixed است و اگر یکی از والدهایش transform داشته باشد
		   (کاری که المنتور در انیمیشن‌ها و بخش‌های چسبان می‌کند) دیگر نسبت به
		   صفحه نمایش قرار نمی‌گیرد؛ پس در حالت موبایل به body منتقل می‌شود. */

		function eachFloating( fn ) {
			floating.forEach( function ( el ) {
				if ( el ) {
					fn( el );
				}
			} );
		}

		/**
		 * مقدار محاسبه‌شدهٔ متغیرها را از ریشهٔ ویجت روی عناصر منتقل‌شده می‌نشاند.
		 * موقع هر بار باز شدن دوباره اجرا می‌شود تا تغییر رنگ در ویرایشگر
		 * المنتور هم بلافاصله دیده شود.
		 */
		function syncVars() {
			if ( ! moved || ! window.getComputedStyle ) {
				return;
			}

			var computed = window.getComputedStyle( root );

			VARS.forEach( function ( name ) {
				var value = ( computed.getPropertyValue( name ) || '' ).trim();

				eachFloating( function ( el ) {
					if ( '' === value ) {
						el.style.removeProperty( name );
					} else {
						el.style.setProperty( name, value );
					}
				} );
			} );
		}

		function moveOut() {
			if ( moved || ! nav.parentNode ) {
				return;
			}

			nav.parentNode.insertBefore( placeholder, nav );

			eachFloating( function ( el ) {
				el.setAttribute( 'data-ard-mm-owner', owner );
				document.body.appendChild( el );
			} );

			moved = true;
			syncVars();
		}

		function moveBack() {
			if ( ! moved || ! placeholder.parentNode ) {
				return;
			}

			eachFloating( function ( el ) {
				placeholder.parentNode.insertBefore( el, placeholder );
				el.removeAttribute( 'data-ard-mm-owner' );

				VARS.forEach( function ( name ) {
					el.style.removeProperty( name );
				} );
			} );

			placeholder.parentNode.removeChild( placeholder );
			moved = false;
		}

		/* ---------------- آکاردئون موبایل ---------------- */

		function setExpanded( item, open ) {
			var toggle = childToggle( item );

			item.classList.toggle( 'is-open', open );

			if ( toggle ) {
				toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			}
		}

		function slideOpen( panel ) {
			if ( reduceMotion() ) {
				panel.style.height = 'auto';

				return;
			}

			panel.style.height = panel.scrollHeight + 'px';

			// بعد از پایان انیمیشن، ارتفاع آزاد می‌شود تا باز شدن زیرمنوها
			// ارتفاع والد را هم بزرگ کند.
			panel.addEventListener( 'transitionend', function done( e ) {
				if ( e.target !== panel || 'height' !== e.propertyName ) {
					return;
				}

				panel.removeEventListener( 'transitionend', done );

				if ( '0px' !== panel.style.height ) {
					panel.style.height = 'auto';
				}
			} );
		}

		function slideClose( panel ) {
			if ( reduceMotion() ) {
				panel.style.height = '';

				return;
			}

			panel.style.height = panel.scrollHeight + 'px';
			void panel.offsetHeight; // اجبار به بازچینش تا انتقال از مقدار واقعی شروع شود.
			panel.style.height = '0px';
		}

		function closeAccordion( item ) {
			var panel = childPanel( item );

			setExpanded( item, false );

			if ( panel ) {
				slideClose( panel );
			}

			Array.prototype.forEach.call( item.querySelectorAll( '.is-open' ), function ( child ) {
				var sub = childPanel( child );

				setExpanded( child, false );

				if ( sub ) {
					sub.style.height = '';
				}
			} );
		}

		function toggleAccordion( item ) {
			var panel = childPanel( item );

			if ( ! panel ) {
				return;
			}

			if ( item.classList.contains( 'is-open' ) ) {
				closeAccordion( item );

				return;
			}

			// هم‌سطح‌های باز بسته می‌شوند تا کشو کوتاه بماند.
			var siblings = item.parentNode ? item.parentNode.children : [];

			Array.prototype.forEach.call( siblings, function ( sibling ) {
				if ( sibling !== item && sibling.classList.contains( 'is-open' ) ) {
					closeAccordion( sibling );
				}
			} );

			setExpanded( item, true );
			slideOpen( panel );
		}

		function resetAccordion() {
			Array.prototype.forEach.call( nav.querySelectorAll( '.is-open' ), function ( item ) {
				setExpanded( item, false );
			} );

			Array.prototype.forEach.call( nav.querySelectorAll( '[data-ard-mm-panel]' ), function ( panel ) {
				panel.style.height = '';
			} );
		}

		/* ---------------- پنل‌های دسکتاپ ---------------- */

		function closeDesktopPanels() {
			Array.prototype.forEach.call( nav.querySelectorAll( '.is-open' ), function ( item ) {
				setExpanded( item, false );
			} );
		}

		function toggleDesktopPanel( item ) {
			var open = ! item.classList.contains( 'is-open' );

			closeDesktopPanels();
			setExpanded( item, open );
		}

		/* ---------------- کشو ---------------- */

		function focusables() {
			return Array.prototype.filter.call( nav.querySelectorAll( FOCUSABLE ), function ( el ) {
				return null !== el.offsetParent;
			} );
		}

		function openDrawer() {
			lastFocus = document.activeElement;
			syncVars();

			if ( scrim ) {
				scrim.classList.add( 'is-open' );
			}

			nav.classList.add( 'is-open' );
			burger.classList.add( 'is-opened-navi' );
			burger.setAttribute( 'aria-expanded', 'true' );
			document.documentElement.classList.add( 'ard-mm-lock' );

			if ( overlay ) {
				overlay.open();

				// وقتی پرده بالا آمد، دکمهٔ بستن جلوی چشم کاربر از سه خط به
				// ضربدر تبدیل می‌شود.
				window.clearTimeout( morphTimer );
				morphTimer = window.setTimeout( function () {
					if ( closeBtn ) {
						closeBtn.classList.add( 'is-opened-navi' );
					}
				}, reduceMotion() ? 0 : 900 );
			}

			if ( closeBtn ) {
				closeBtn.focus();
			}
		}

		function closeDrawer( restoreFocus ) {
			if ( ! nav.classList.contains( 'is-open' ) ) {
				return;
			}

			if ( scrim ) {
				scrim.classList.remove( 'is-open' );
			}

			nav.classList.remove( 'is-open' );
			burger.classList.remove( 'is-opened-navi' );
			burger.setAttribute( 'aria-expanded', 'false' );
			document.documentElement.classList.remove( 'ard-mm-lock' );
			window.clearTimeout( morphTimer );

			if ( closeBtn ) {
				closeBtn.classList.remove( 'is-opened-navi' );
			}

			if ( overlay && overlay.isOpened ) {
				overlay.close();
			}

			if ( false !== restoreFocus ) {
				( lastFocus && lastFocus.focus ? lastFocus : burger ).focus();
			}
		}

		/* ---------------- تغییر حالت دسکتاپ/موبایل ---------------- */

		function applyMode( mobile ) {
			isMobile = mobile;

			closeDrawer( false );
			closeDesktopPanels();
			resetAccordion();

			root.classList.toggle( 'is-mobile', mobile );

			eachFloating( function ( el ) {
				el.classList.toggle( 'is-mobile', mobile );
			} );

			if ( mobile ) {
				moveOut();
			} else {
				moveBack();
			}
		}

		/* ---------------- رویدادها ---------------- */

		burger.addEventListener( 'click', function () {
			if ( overlay && overlay.isAnimating ) {
				return;
			}

			if ( nav.classList.contains( 'is-open' ) ) {
				closeDrawer( true );
			} else {
				openDrawer();
			}
		} );

		if ( scrim ) {
			scrim.addEventListener( 'click', function () {
				closeDrawer( true );
			} );
		}

		nav.addEventListener( 'click', function ( event ) {
			var toggle = event.target.closest ? event.target.closest( '[data-ard-mm-toggle]' ) : null;

			if ( toggle && nav.contains( toggle ) ) {
				var item = toggle.closest( '[data-ard-mm-item]' );

				if ( ! item ) {
					return;
				}

				event.preventDefault();

				if ( isMobile ) {
					toggleAccordion( item );
				} else {
					toggleDesktopPanel( item );
				}

				return;
			}

			if ( event.target.closest && event.target.closest( '[data-ard-mm-close]' ) ) {
				event.preventDefault();

				if ( ! overlay || ! overlay.isAnimating ) {
					closeDrawer( true );
				}

				return;
			}

			// رفتن به لینک در حالت موبایل، کشو را می‌بندد (لینک‌های داخل صفحه هم).
			var link = event.target.closest ? event.target.closest( 'a[href]' ) : null;

			if ( isMobile && link ) {
				closeDrawer( false );
			}
		} );

		// روی دستگاه لمسی، اولین ضربه روی آیتم دارای پنل فقط پنل را باز می‌کند.
		nav.addEventListener( 'click', function ( event ) {
			if ( isMobile || ! window.matchMedia( '(hover: none)' ).matches ) {
				return;
			}

			var link = event.target.closest ? event.target.closest( '.ard-mm__link, .ard-mm__sublink' ) : null;

			if ( ! link ) {
				return;
			}

			var item = link.closest( '[data-ard-mm-item]' );

			if ( ! item || item.classList.contains( 'is-open' ) ) {
				return;
			}

			event.preventDefault();
			toggleDesktopPanel( item );
		}, true );

		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' !== event.key && 'Esc' !== event.key ) {
				return;
			}

			if ( isMobile ) {
				closeDrawer( true );
			} else {
				closeDesktopPanels();
			}
		} );

		// تله فوکوس تا وقتی کشو باز است.
		nav.addEventListener( 'keydown', function ( event ) {
			if ( 'Tab' !== event.key || ! isMobile || ! nav.classList.contains( 'is-open' ) ) {
				return;
			}

			var items = focusables();

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

		document.addEventListener( 'click', function ( event ) {
			if ( isMobile || nav.contains( event.target ) || root.contains( event.target ) ) {
				return;
			}

			closeDesktopPanels();
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

	function init( scope ) {
		var root = scope || document;

		Array.prototype.forEach.call( root.querySelectorAll( '[data-ard-mega-menu]' ), function ( el ) {
			if ( el.ardMegaReady ) {
				return;
			}

			el.ardMegaReady = true;
			setup( el );
		} );
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
			'frontend/element_ready/ardal-mega-menu.default',
			function ( $scope ) {
				init( $scope && $scope[ 0 ] ? $scope[ 0 ] : null );
			}
		);
	} );
}() );
