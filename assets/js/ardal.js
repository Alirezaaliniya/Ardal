/**
 * Ardal — رفتار سمت کاربر ویجت‌ها.
 * بدون وابستگی به jQuery؛ در ویرایشگر المنتور هم دوباره راه‌اندازی می‌شود.
 */
( function () {
	'use strict';

	var FA = [ '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' ];

	function toFa( value ) {
		return String( value ).replace( /\d/g, function ( d ) {
			return FA[ d ];
		} );
	}

	function pad( n ) {
		return n < 10 ? '0' + n : String( n );
	}

	function prefersReducedMotion() {
		return window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	}

	/* --------------------------------------------------------
	   اسلایدر هیرو
	   -------------------------------------------------------- */

	function initSlider( root ) {
		var slides = Array.prototype.slice.call( root.querySelectorAll( '.ard-hero__slide' ) );
		var dots = Array.prototype.slice.call( root.querySelectorAll( '.ard-hero__dot' ) );

		if ( slides.length < 2 ) {
			return;
		}

		var index = 0;
		var interval = parseInt( root.getAttribute( 'data-interval' ), 10 ) || 0;
		var timer = null;

		function show( next ) {
			index = ( next + slides.length ) % slides.length;

			slides.forEach( function ( slide, i ) {
				var active = i === index;

				slide.classList.toggle( 'is-active', active );

				if ( active ) {
					slide.removeAttribute( 'aria-hidden' );
				} else {
					slide.setAttribute( 'aria-hidden', 'true' );
				}
			} );

			dots.forEach( function ( dot, i ) {
				dot.classList.toggle( 'is-active', i === index );
				dot.setAttribute( 'aria-selected', i === index ? 'true' : 'false' );
			} );
		}

		function start() {
			if ( ! interval || prefersReducedMotion() ) {
				return;
			}

			stop();
			timer = window.setInterval( function () {
				show( index + 1 );
			}, interval );
		}

		function stop() {
			if ( timer ) {
				window.clearInterval( timer );
				timer = null;
			}
		}

		dots.forEach( function ( dot ) {
			dot.addEventListener( 'click', function () {
				show( parseInt( dot.getAttribute( 'data-ard-goto' ), 10 ) || 0 );
				start();
			} );
		} );

		var prev = root.querySelector( '[data-ard-prev]' );
		var next = root.querySelector( '[data-ard-next]' );

		if ( prev ) {
			prev.addEventListener( 'click', function () {
				show( index - 1 );
				start();
			} );
		}

		if ( next ) {
			next.addEventListener( 'click', function () {
				show( index + 1 );
				start();
			} );
		}

		root.addEventListener( 'mouseenter', stop );
		root.addEventListener( 'mouseleave', start );
		root.addEventListener( 'focusin', stop );

		// در RTL کشیدن انگشت به چپ یعنی اسلاید بعدی.
		var touchX = null;

		root.addEventListener( 'touchstart', function ( e ) {
			touchX = e.changedTouches[ 0 ].clientX;
			stop();
		}, { passive: true } );

		root.addEventListener( 'touchend', function ( e ) {
			if ( null === touchX ) {
				return;
			}

			var delta = e.changedTouches[ 0 ].clientX - touchX;

			if ( Math.abs( delta ) > 40 ) {
				show( delta < 0 ? index + 1 : index - 1 );
			}

			touchX = null;
			start();
		}, { passive: true } );

		document.addEventListener( 'visibilitychange', function () {
			if ( document.hidden ) {
				stop();
			} else {
				start();
			}
		} );

		show( 0 );
		start();
	}

	/* --------------------------------------------------------
	   شمارش معکوس
	   -------------------------------------------------------- */

	function initCountdown( root ) {
		var cells = {};

		Array.prototype.forEach.call( root.querySelectorAll( '[data-unit]' ), function ( el ) {
			cells[ el.getAttribute( 'data-unit' ) ] = el;
		} );

		var withDays = '1' === root.getAttribute( 'data-days' );
		var daily = 'daily' === root.getAttribute( 'data-mode' );
		var remaining = parseInt( root.getAttribute( 'data-remaining' ), 10 );

		if ( isNaN( remaining ) ) {
			return;
		}

		// زمان پایان را نسبت به لحظه بارگذاری صفحه می‌سنجیم تا کش صفحه شمارنده را خراب نکند.
		var endsAt = Date.now() + remaining * 1000;

		function paint() {
			var left = Math.max( 0, Math.round( ( endsAt - Date.now() ) / 1000 ) );

			if ( 0 === left && daily ) {
				// شمارنده روزانه: دوباره تا نیمه‌شب بعدی.
				endsAt = Date.now() + 24 * 3600 * 1000;
				left = 24 * 3600;
			}

			var days = withDays ? Math.floor( left / 86400 ) : 0;
			var hours = Math.floor( ( left - days * 86400 ) / 3600 );

			var values = {
				days: pad( days ),
				hours: pad( hours ),
				minutes: pad( Math.floor( left / 60 ) % 60 ),
				seconds: pad( left % 60 )
			};

			Object.keys( cells ).forEach( function ( unit ) {
				var text = toFa( values[ unit ] );

				if ( cells[ unit ].textContent !== text ) {
					cells[ unit ].textContent = text;
				}
			} );
		}

		paint();

		var id = window.setInterval( paint, 1000 );

		root.ardStop = function () {
			window.clearInterval( id );
		};
	}

	/* --------------------------------------------------------
	   کروسل افقی (اسکرول‌محور)
	   -------------------------------------------------------- */

	function initCarousel( root ) {
		var viewport = root.querySelector( '[data-ard-viewport]' );

		if ( ! viewport ) {
			return;
		}

		var slides = Array.prototype.slice.call( viewport.children );

		if ( ! slides.length ) {
			return;
		}

		var dotsBox = root.querySelector( '[data-ard-dots]' );
		var prev = root.parentNode ? root.parentNode.querySelector( '[data-ard-prev]' ) : null;
		var next = root.parentNode ? root.parentNode.querySelector( '[data-ard-next]' ) : null;
		var interval = parseInt( root.getAttribute( 'data-interval' ), 10 ) || 0;
		var timer = null;
		var dots = [];

		function perView() {
			var raw = window.getComputedStyle( viewport ).getPropertyValue( '--ard-per' );
			var n = Math.round( parseFloat( raw ) );

			return n > 0 ? n : 1;
		}

		function pageCount() {
			return Math.max( 1, Math.ceil( slides.length / perView() ) );
		}

		// موقعیت با getBoundingClientRect سنجیده می‌شود تا در RTL هم درست باشد؛
		// مقدار scrollLeft در حالت راست‌چین بین مرورگرها یکسان نیست.
		function isRtl() {
			return 'rtl' === window.getComputedStyle( viewport ).direction;
		}

		function currentPage() {
			var box = viewport.getBoundingClientRect();
			var rtl = isRtl();
			var best = 0;
			var bestDist = Infinity;

			slides.forEach( function ( slide, i ) {
				var r = slide.getBoundingClientRect();
				var dist = Math.abs( rtl ? box.right - r.right : r.left - box.left );

				if ( dist < bestDist - 1 ) {
					bestDist = dist;
					best = i;
				}
			} );

			return Math.floor( best / perView() );
		}

		function goTo( page ) {
			var per = perView();
			var total = pageCount();
			var wrapped = ( page + total ) % total;
			var index = Math.min( slides.length - 1, wrapped * per );

			slides[ index ].scrollIntoView( {
				behavior: prefersReducedMotion() ? 'auto' : 'smooth',
				block: 'nearest',
				inline: 'start'
			} );
		}

		function paint() {
			var active = currentPage();

			dots.forEach( function ( dot, i ) {
				dot.classList.toggle( 'is-active', i === active );
				dot.setAttribute( 'aria-selected', i === active ? 'true' : 'false' );
			} );
		}

		function buildDots() {
			if ( ! dotsBox ) {
				return;
			}

			var total = pageCount();

			if ( dots.length === total ) {
				paint();

				return;
			}

			dotsBox.innerHTML = '';
			dots = [];

			if ( total < 2 ) {
				return;
			}

			for ( var i = 0; i < total; i++ ) {
				( function ( page ) {
					var dot = document.createElement( 'button' );

					dot.type = 'button';
					dot.className = 'ard-deal__dot';
					dot.setAttribute( 'role', 'tab' );
					dot.setAttribute( 'aria-label', 'صفحه ' + toFa( page + 1 ) );
					dot.addEventListener( 'click', function () {
						goTo( page );
						start();
					} );

					dotsBox.appendChild( dot );
					dots.push( dot );
				}( i ) );
			}

			paint();
		}

		function start() {
			stop();

			if ( ! interval || prefersReducedMotion() || pageCount() < 2 ) {
				return;
			}

			timer = window.setInterval( function () {
				goTo( currentPage() + 1 );
			}, interval );
		}

		function stop() {
			if ( timer ) {
				window.clearInterval( timer );
				timer = null;
			}
		}

		if ( prev ) {
			prev.addEventListener( 'click', function () {
				goTo( currentPage() - 1 );
				start();
			} );
		}

		if ( next ) {
			next.addEventListener( 'click', function () {
				goTo( currentPage() + 1 );
				start();
			} );
		}

		var raf = null;

		viewport.addEventListener( 'scroll', function () {
			if ( raf ) {
				return;
			}

			raf = window.requestAnimationFrame( function () {
				raf = null;
				paint();
			} );
		}, { passive: true } );

		viewport.addEventListener( 'mouseenter', stop );
		viewport.addEventListener( 'mouseleave', start );
		viewport.addEventListener( 'focusin', stop );

		var resizeTimer = null;

		window.addEventListener( 'resize', function () {
			window.clearTimeout( resizeTimer );
			resizeTimer = window.setTimeout( buildDots, 150 );
		} );

		buildDots();
		start();
	}

	/* --------------------------------------------------------
	   فیلتر تب‌دار محصولات
	   -------------------------------------------------------- */

	function initFilters( widget ) {
		var buttons = Array.prototype.slice.call( widget.querySelectorAll( '.ard-tabs__btn' ) );
		var grid = widget.querySelector( '[data-ard-filterable]' );

		if ( ! buttons.length || ! grid ) {
			return;
		}

		var cells = Array.prototype.slice.call( grid.querySelectorAll( '.ard-pgrid__cell' ) );

		buttons.forEach( function ( button ) {
			button.addEventListener( 'click', function () {
				var filter = button.getAttribute( 'data-ard-filter' );

				buttons.forEach( function ( other ) {
					var active = other === button;

					other.classList.toggle( 'is-active', active );
					other.setAttribute( 'aria-selected', active ? 'true' : 'false' );
				} );

				cells.forEach( function ( cell ) {
					var match = '*' === filter || cell.getAttribute( 'data-tab' ) === filter;

					cell.classList.toggle( 'is-hidden', ! match );
				} );
			} );
		} );
	}

	/* --------------------------------------------------------
	   دکمه همبرگری هدر
	   -------------------------------------------------------- */

	function initBurger( button ) {
		var targetId = button.getAttribute( 'data-ard-toggle' );
		var target = targetId ? document.getElementById( targetId ) : null;

		if ( ! target ) {
			button.setAttribute( 'aria-hidden', 'true' );

			return;
		}

		var mq = window.matchMedia( '(max-width: 860px)' );

		function sync() {
			if ( mq.matches ) {
				target.classList.add( 'is-collapsed' );
				button.setAttribute( 'aria-expanded', 'false' );
			} else {
				target.classList.remove( 'is-collapsed' );
				button.removeAttribute( 'aria-expanded' );
			}
		}

		button.setAttribute( 'aria-controls', targetId );

		button.addEventListener( 'click', function () {
			var collapsed = target.classList.toggle( 'is-collapsed' );

			button.setAttribute( 'aria-expanded', collapsed ? 'false' : 'true' );
		} );

		if ( mq.addEventListener ) {
			mq.addEventListener( 'change', sync );
		} else if ( mq.addListener ) {
			mq.addListener( sync );
		}

		sync();
	}

	/* --------------------------------------------------------
	   راه‌اندازی
	   -------------------------------------------------------- */

	function init( scope ) {
		var root = scope || document;

		Array.prototype.forEach.call( root.querySelectorAll( '[data-ard-slider]' ), function ( el ) {
			if ( ! el.ardReady ) {
				el.ardReady = true;
				initSlider( el );
			}
		} );

		Array.prototype.forEach.call( root.querySelectorAll( '[data-ard-carousel]' ), function ( el ) {
			if ( ! el.ardReady ) {
				el.ardReady = true;
				initCarousel( el );
			}
		} );

		Array.prototype.forEach.call( root.querySelectorAll( '[data-ard-countdown]' ), function ( el ) {
			if ( ! el.ardReady ) {
				el.ardReady = true;
				initCountdown( el );
			}
		} );

		Array.prototype.forEach.call( root.querySelectorAll( '.elementor-widget-ardal-products' ), function ( el ) {
			if ( ! el.ardReady ) {
				el.ardReady = true;
				initFilters( el );
			}
		} );

		Array.prototype.forEach.call( root.querySelectorAll( '[data-ard-toggle]' ), function ( el ) {
			if ( ! el.ardReady ) {
				el.ardReady = true;
				initBurger( el );
			}
		} );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', function () {
			init();
		} );
	} else {
		init();
	}

	// راه‌اندازی دوباره داخل ویرایشگر المنتور، بعد از رندر هر ویجت.
	window.addEventListener( 'elementor/frontend/init', function () {
		if ( ! window.elementorFrontend || ! window.elementorFrontend.hooks ) {
			return;
		}

		[
			'ardal-hero',
			'ardal-deal',
			'ardal-sale-festival',
			'ardal-products',
			'ardal-header'
		].forEach( function ( name ) {
			window.elementorFrontend.hooks.addAction(
				'frontend/element_ready/' + name + '.default',
				function ( $scope ) {
					init( $scope && $scope[ 0 ] ? $scope[ 0 ] : null );
				}
			);
		} );
	} );
}() );
