/**
 * Ardal — حلقهٔ برندها: شبکه، اسلایدر و مارکیِ بی‌پایان.
 *
 * حالت فعال در هر عرض از متغیر CSS --ard-br-mode خوانده می‌شود؛ المنتور
 * مقدار هر بریک‌پوینت را در مدیاکوئری خودش می‌نویسد، پس با تغییر اندازهٔ
 * پنجره حالت هم عوض می‌شود. بدون jQuery.
 */
( function () {
	'use strict';

	var FA = [ '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' ];
	var MODES = { grid: 1, slider: 1, marquee: 1 };

	function toFa( value ) {
		return String( value ).replace( /\d/g, function ( d ) {
			return FA[ d ];
		} );
	}

	function reducedMotion() {
		return !! ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches );
	}

	function cssNumber( el, name, fallback ) {
		var value = parseFloat( window.getComputedStyle( el ).getPropertyValue( name ) );

		return isFinite( value ) ? value : fallback;
	}

	function initBrandRings( root ) {
		var viewport = root.querySelector( '[data-br-viewport]' );
		var list = root.querySelector( '[data-br-list]' );

		if ( ! viewport || ! list ) {
			return;
		}

		var originals = Array.prototype.slice.call( list.children );

		if ( ! originals.length ) {
			return;
		}

		var dotsBox = root.querySelector( '[data-br-dots]' );
		var prevBtn = root.querySelector( '[data-br-prev]' );
		var nextBtn = root.querySelector( '[data-br-next]' );
		var stage = root.querySelector( '.ard-br__stage' ) || root;

		var cfg = {
			step: root.getAttribute( 'data-step' ) || 'page',
			loop: '0' !== root.getAttribute( 'data-loop' ),
			drag: '1' === root.getAttribute( 'data-drag' ),
			interval: parseInt( root.getAttribute( 'data-interval' ), 10 ) || 0,
			pauseHover: '0' !== root.getAttribute( 'data-pause-hover' ),
			mqDir: 'left' === root.getAttribute( 'data-mq-dir' ) ? 1 : -1,
			mqHover: parseFloat( root.getAttribute( 'data-mq-hover' ) ),
			mqDrag: '1' === root.getAttribute( 'data-mq-drag' )
		};

		if ( ! isFinite( cfg.mqHover ) ) {
			cfg.mqHover = 1;
		}

		var mode = '';
		var visible = true;
		var hovered = false;
		var focused = false;
		var suppressClick = false;

		// همهٔ حالت‌ها اشاره‌گرها را همین‌جا می‌گیرند و بسته به حالت فعال رفتار
		// می‌کنند؛ عوض شدن حالت یعنی فقط عوض شدن شاخه، نه باز و بسته کردن شنونده.
		var drag = null;

		function readMode() {
			var raw = window.getComputedStyle( root ).getPropertyValue( '--ard-br-mode' );

			raw = String( raw || '' ).replace( /["'\s]/g, '' );

			if ( ! MODES[ raw ] ) {
				raw = root.getAttribute( 'data-mode' ) || 'grid';
			}

			return MODES[ raw ] ? raw : 'grid';
		}

		/* ====================================================
		   اسلایدر
		   ==================================================== */

		var timer = null;
		var dots = [];
		var scrollRaf = 0;

		function isRtl() {
			return 'rtl' === window.getComputedStyle( viewport ).direction;
		}

		function gapPx() {
			var style = window.getComputedStyle( list );
			var gap = parseFloat( style.columnGap || style.gap );

			return isFinite( gap ) ? gap : 0;
		}

		function perView() {
			var n = Math.round( cssNumber( root, '--ard-br-per', 1 ) );

			return n > 0 ? n : 1;
		}

		function itemStep() {
			return originals[ 0 ].getBoundingClientRect().width + gapPx();
		}

		function stepSize() {
			return 'item' === cfg.step ? itemStep() : itemStep() * perView();
		}

		function maxScroll() {
			return Math.max( 0, viewport.scrollWidth - viewport.clientWidth );
		}

		// scrollLeft در RTL منفی است (استاندارد فعلی همهٔ مرورگرها)؛ با قدرمطلق
		// هر دو جهت یک‌جور حساب می‌شوند.
		function position() {
			return Math.abs( viewport.scrollLeft );
		}

		function scrollToPos( x, smooth ) {
			x = Math.max( 0, Math.min( maxScroll(), x ) );

			viewport.scrollTo( {
				left: isRtl() ? -x : x,
				behavior: smooth && ! reducedMotion() ? 'smooth' : 'auto'
			} );
		}

		function pageCount() {
			if ( maxScroll() <= 2 ) {
				return 1;
			}

			var step = stepSize();

			return step > 0 ? Math.ceil( maxScroll() / step - 0.01 ) + 1 : 1;
		}

		function atStart() {
			return position() <= 2;
		}

		function atEnd() {
			return position() >= maxScroll() - 2;
		}

		function currentPage() {
			var total = pageCount();

			if ( atEnd() ) {
				return total - 1;
			}

			var step = stepSize();

			return Math.max( 0, Math.min( total - 1, Math.round( position() / ( step || 1 ) ) ) );
		}

		function goTo( page, smooth ) {
			var total = pageCount();
			var target = cfg.loop ? ( page + total ) % total : Math.max( 0, Math.min( total - 1, page ) );

			scrollToPos( target * stepSize(), false !== smooth );
			paint( target );
		}

		function next() {
			if ( atEnd() ) {
				if ( cfg.loop ) {
					goTo( 0 );
				}

				return;
			}

			goTo( currentPage() + 1 );
		}

		function prev() {
			if ( atStart() ) {
				if ( cfg.loop ) {
					goTo( pageCount() - 1 );
				}

				return;
			}

			// اگر وسط دو صفحه ایستاده‌ایم، اول به ابتدای همین صفحه برمی‌گردیم.
			var page = currentPage();
			var exact = Math.abs( position() - page * stepSize() ) < 3;

			goTo( exact || atEnd() ? page - 1 : page );
		}

		function buildDots() {
			if ( ! dotsBox ) {
				return;
			}

			var total = pageCount();

			if ( dots.length === total || ( total < 2 && ! dots.length ) ) {
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
					dot.className = 'ard-br__dot';
					dot.setAttribute( 'role', 'tab' );
					dot.setAttribute( 'aria-label', 'صفحه ' + toFa( page + 1 ) );
					dot.addEventListener( 'click', function () {
						goTo( page );
						restartAutoplay();
					} );

					dotsBox.appendChild( dot );
					dots.push( dot );
				}( i ) );
			}
		}

		function paint( page ) {
			var active = 'number' === typeof page ? page : currentPage();

			dots.forEach( function ( dot, i ) {
				dot.classList.toggle( 'is-active', i === active );
				dot.setAttribute( 'aria-selected', i === active ? 'true' : 'false' );
			} );

			if ( ! cfg.loop ) {
				if ( prevBtn ) {
					prevBtn.disabled = atStart();
				}

				if ( nextBtn ) {
					nextBtn.disabled = atEnd();
				}
			}
		}

		/*
		 * فلش‌ها روی مرکز حلقه‌ها می‌نشینند. حلقه ممکن است از اندازهٔ تنظیمی
		 * کوچک‌تر شده باشد (max-inline-size:100%) یا کارت پدینگ داشته باشد، پس
		 * موقعیت واقعی اندازه گرفته می‌شود، نه اینکه از روی متغیرها حدس زده شود.
		 */
		function placeArrows() {
			var ring = originals[ 0 ].querySelector( '.ard-br__ring' );

			if ( ! ring ) {
				return;
			}

			var top = ring.offsetTop + ring.offsetHeight / 2;
			var node = ring.offsetParent;

			while ( node && node !== stage && stage.contains( node ) ) {
				top += node.offsetTop;
				node = node.offsetParent;
			}

			stage.style.setProperty( '--ard-br-arrow-auto', Math.round( top ) + 'px' );
		}

		function sliderRefresh() {
			root.classList.toggle( 'is-fit', maxScroll() <= 2 );
			buildDots();
			paint();
			placeArrows();
		}

		function stopAutoplay() {
			if ( timer ) {
				window.clearInterval( timer );
				timer = null;
			}
		}

		function startAutoplay() {
			stopAutoplay();

			if ( 'slider' !== mode || ! cfg.interval || reducedMotion() || ! visible || document.hidden ) {
				return;
			}

			if ( ( cfg.pauseHover && hovered ) || focused ) {
				return;
			}

			timer = window.setInterval( function () {
				if ( ! root.isConnected ) {
					stopAutoplay();

					return;
				}

				if ( atEnd() && ! cfg.loop ) {
					stopAutoplay();

					return;
				}

				next();
			}, cfg.interval );
		}

		function restartAutoplay() {
			if ( timer ) {
				startAutoplay();
			}
		}

		function sliderSetup() {
			root.classList.toggle( 'is-draggable', cfg.drag );
			sliderRefresh();
			startAutoplay();
		}

		function sliderTeardown() {
			stopAutoplay();
			root.classList.remove( 'is-fit', 'is-draggable' );

			if ( dotsBox ) {
				dotsBox.innerHTML = '';
			}

			dots = [];
			viewport.scrollLeft = 0;
		}

		if ( prevBtn ) {
			prevBtn.addEventListener( 'click', function () {
				if ( 'slider' === mode ) {
					prev();
					restartAutoplay();
				}
			} );
		}

		if ( nextBtn ) {
			nextBtn.addEventListener( 'click', function () {
				if ( 'slider' === mode ) {
					next();
					restartAutoplay();
				}
			} );
		}

		viewport.addEventListener( 'scroll', function () {
			if ( 'slider' !== mode || scrollRaf ) {
				return;
			}

			scrollRaf = window.requestAnimationFrame( function () {
				scrollRaf = 0;
				paint();
			} );
		}, { passive: true } );

		/* ====================================================
		   مارکی
		   ==================================================== */

		/*
		 * چرا با requestAnimationFrame و نه keyframes.
		 *
		 * طول هر دور به پیکسل است و با تغییر عرض عوض می‌شود. انیمیشن CSS با
		 * عوض شدن مدتش از جای دیگری ادامه می‌دهد و پرش دیده می‌شود. اینجا فقط
		 * یک «جابه‌جایی» داریم که همیشه به پیمانهٔ عرض یک دور گرفته می‌شود؛
		 * چون نوار حداقل یک دور بیشتر از عرض قاب نسخهٔ تکراری دارد، لحظهٔ
		 * برگشت به صفر دقیقاً همان تصویر قبلی است و هیچ درز یا پرشی نیست.
		 */
		var mq = {
			clones: [],
			period: 0,
			offset: 0,
			velocity: 0,
			speed: 40,
			raf: 0,
			last: 0,
			lastSync: 0,
			static: false
		};

		function mqClear() {
			mq.clones.forEach( function ( node ) {
				if ( node.parentNode ) {
					node.parentNode.removeChild( node );
				}
			} );

			mq.clones = [];
		}

		function mqMeasure() {
			if ( mq.clones.length ) {
				// فاصلهٔ اولین نسخهٔ تکراری تا اولین اصلی، دقیق‌ترین عرض یک دور است
				// (ترنسفورم هر دو را یکسان جابه‌جا کرده، پس در تفاضل حذف می‌شود).
				return mq.clones[ 0 ].getBoundingClientRect().left - originals[ 0 ].getBoundingClientRect().left;
			}

			return list.getBoundingClientRect().width;
		}

		function mqBuild() {
			mqClear();

			mq.static = reducedMotion();
			root.classList.toggle( 'is-static', mq.static );

			if ( mq.static ) {
				list.style.transform = '';

				return;
			}

			var period = list.getBoundingClientRect().width;
			var view = viewport.clientWidth;

			if ( period < 1 || view < 1 ) {
				mq.period = 0;

				return;
			}

			// آن‌قدر تکرار که همیشه یک دور کامل بیرون از قاب آماده باشد.
			var copies = Math.min( 60, Math.ceil( view / period ) + 1 );
			var frag = document.createDocumentFragment();

			for ( var c = 0; c < copies; c++ ) {
				originals.forEach( function ( item ) {
					var clone = item.cloneNode( true );

					clone.classList.add( 'is-clone' );
					clone.setAttribute( 'aria-hidden', 'true' );

					Array.prototype.forEach.call( clone.querySelectorAll( 'a,button,[tabindex]' ), function ( el ) {
						el.setAttribute( 'tabindex', '-1' );
					} );

					// نسخهٔ تکراری که دیر بار شود، لحظه‌ای خالی از قاب رد می‌شود.
					Array.prototype.forEach.call( clone.querySelectorAll( 'img' ), function ( img ) {
						img.loading = 'eager';
					} );

					frag.appendChild( clone );
					mq.clones.push( clone );
				} );
			}

			list.appendChild( frag );

			Array.prototype.forEach.call( list.querySelectorAll( 'img' ), function ( img ) {
				img.loading = 'eager';
			} );

			mq.period = mqMeasure() || period;
			mq.offset = wrap( mq.offset );
			mq.speed = cssNumber( root, '--ard-br-speed', 40 );
			mqRender();
		}

		function wrap( value ) {
			if ( ! mq.period ) {
				return 0;
			}

			return ( ( value % mq.period ) + mq.period ) % mq.period;
		}

		function mqRender() {
			list.style.transform = 'translate3d(' + ( -mq.offset ).toFixed( 3 ) + 'px,0,0)';
		}

		function mqTarget() {
			if ( drag && drag.active ) {
				return 0;
			}

			var factor = hovered || focused ? cfg.mqHover : 1;

			return cfg.mqDir * mq.speed * factor;
		}

		/*
		 * هر نیم ثانیه اندازه‌ها دوباره خوانده می‌شوند.
		 *
		 * در ویرایشگر المنتور فاصله، عرض و سرعت فقط متغیر CSS را عوض می‌کنند و
		 * ویجت دوباره ساخته نمی‌شود؛ ResizeObserver هم تغییر margin را نمی‌بیند.
		 * این بررسی ارزان است و همان را پوشش می‌دهد.
		 */
		function mqSync( now ) {
			if ( now - mq.lastSync < 500 ) {
				return;
			}

			mq.lastSync = now;
			mq.speed = cssNumber( root, '--ard-br-speed', 40 );

			var period = mqMeasure();
			var view = viewport.clientWidth;
			var enough = mq.period * ( mq.clones.length / originals.length ) >= view;

			if ( Math.abs( period - mq.period ) > 0.5 || ! enough ) {
				mqBuild();
			}
		}

		function mqTick( now ) {
			mq.raf = 0;

			if ( 'marquee' !== mode || ! root.isConnected || mq.static ) {
				mq.last = 0;

				return;
			}

			var dt = mq.last ? Math.min( 0.064, ( now - mq.last ) / 1000 ) : 0;

			mq.last = now;
			mqSync( now );

			if ( mq.period ) {
				// رسیدن نرم به سرعت هدف: توقف در هاور و برگشت بعد از کشیدن بی‌تکان است.
				var ease = drag && drag.active ? 1 : Math.min( 1, dt * 4 );

				mq.velocity += ( mqTarget() - mq.velocity ) * ease;

				if ( ! ( drag && drag.active ) ) {
					mq.offset = wrap( mq.offset + mq.velocity * dt );
				}

				mqRender();
			}

			mqSchedule();
		}

		function mqSchedule() {
			if ( mq.raf || 'marquee' !== mode || mq.static || ! visible || document.hidden ) {
				return;
			}

			mq.raf = window.requestAnimationFrame( mqTick );
		}

		function mqStop() {
			if ( mq.raf ) {
				window.cancelAnimationFrame( mq.raf );
				mq.raf = 0;
			}

			mq.last = 0;
		}

		function marqueeSetup() {
			mq.velocity = mqTarget();
			mqBuild();
			mqSchedule();
		}

		function marqueeTeardown() {
			mqStop();
			mqClear();
			root.classList.remove( 'is-static' );
			list.style.transform = '';
			mq.offset = 0;
		}

		/* ====================================================
		   کشیدن (اسلایدر: فقط ماوس، چون لمس بومی است؛ مارکی: همه)
		   ==================================================== */

		function canDrag( e ) {
			if ( 0 !== e.button && 'mouse' === e.pointerType ) {
				return false;
			}

			if ( 'slider' === mode ) {
				return cfg.drag && 'mouse' === e.pointerType && maxScroll() > 2;
			}

			return 'marquee' === mode && cfg.mqDrag && ! mq.static;
		}

		viewport.addEventListener( 'pointerdown', function ( e ) {
			if ( ! canDrag( e ) ) {
				return;
			}

			drag = {
				id: e.pointerId,
				x: e.clientX,
				y: e.clientY,
				lastX: e.clientX,
				lastT: e.timeStamp,
				speed: 0,
				startPos: position(),
				startOffset: mq.offset,
				active: false,
				captured: false
			};
		} );

		viewport.addEventListener( 'pointermove', function ( e ) {
			if ( ! drag || e.pointerId !== drag.id ) {
				return;
			}

			var dx = e.clientX - drag.x;

			if ( ! drag.active ) {
				// کشیدن عمودی مال اسکرول صفحه است.
				if ( Math.abs( dx ) < 5 || Math.abs( dx ) < Math.abs( e.clientY - drag.y ) ) {
					if ( Math.abs( e.clientY - drag.y ) > 8 ) {
						drag = null;
					}

					return;
				}

				drag.active = true;
				root.classList.add( 'is-dragging' );

				try {
					viewport.setPointerCapture( e.pointerId );
					drag.captured = true;
				} catch ( err ) {
					drag.captured = false;
				}
			}

			e.preventDefault();

			if ( 'slider' === mode ) {
				scrollToPos( drag.startPos + ( isRtl() ? dx : -dx ), false );
			} else {
				var dt = Math.max( 1, e.timeStamp - drag.lastT ) / 1000;
				var inst = -( e.clientX - drag.lastX ) / dt;

				// سرعت لحظه‌ای برای اینرسی بعد از رها کردن، کمی هموار شده.
				drag.speed = drag.speed * 0.6 + inst * 0.4;
				drag.lastX = e.clientX;
				drag.lastT = e.timeStamp;
				mq.offset = wrap( drag.startOffset - dx );
				mqRender();
			}
		} );

		function endDrag( e ) {
			if ( ! drag || ( e && e.pointerId !== drag.id ) ) {
				return;
			}

			var was = drag;

			drag = null;

			if ( ! was.active ) {
				return;
			}

			suppressClick = true;
			root.classList.remove( 'is-dragging' );

			if ( was.captured ) {
				try {
					viewport.releasePointerCapture( was.id );
				} catch ( err ) {
					// رها شده بوده.
				}
			}

			if ( 'slider' === mode ) {
				var step = itemStep() || 1;

				scrollToPos( Math.round( position() / step ) * step, true );
				restartAutoplay();
			} else {
				// اگر مکث طولانی قبل از رها کردن بوده، اینرسی ندارد.
				var idle = e ? e.timeStamp - was.lastT : 999;
				var fling = idle > 80 ? 0 : was.speed;

				mq.velocity = Math.max( -2500, Math.min( 2500, fling ) );
				mqSchedule();
			}

			// کلیک بعد از کشیدن نباید لینک را باز کند؛ اگر کلیکی نیامد، پرچم پاک شود.
			window.setTimeout( function () {
				suppressClick = false;
			}, 0 );
		}

		viewport.addEventListener( 'pointerup', endDrag );
		viewport.addEventListener( 'pointercancel', endDrag );

		viewport.addEventListener( 'click', function ( e ) {
			if ( suppressClick ) {
				e.preventDefault();
				e.stopPropagation();
				suppressClick = false;
			}
		}, true );

		viewport.addEventListener( 'dragstart', function ( e ) {
			e.preventDefault();
		} );

		/* ====================================================
		   هاور، فوکوس، دیده شدن
		   ==================================================== */

		root.addEventListener( 'pointerenter', function ( e ) {
			if ( 'mouse' !== e.pointerType ) {
				return;
			}

			hovered = true;

			if ( 'slider' === mode && cfg.pauseHover ) {
				stopAutoplay();
			}
		} );

		root.addEventListener( 'pointerleave', function ( e ) {
			if ( 'mouse' !== e.pointerType ) {
				return;
			}

			hovered = false;

			if ( 'slider' === mode ) {
				startAutoplay();
			}
		} );

		root.addEventListener( 'focusin', function () {
			focused = true;

			if ( 'slider' === mode ) {
				stopAutoplay();
			}
		} );

		root.addEventListener( 'focusout', function ( e ) {
			if ( e.relatedTarget && root.contains( e.relatedTarget ) ) {
				return;
			}

			focused = false;

			if ( 'slider' === mode ) {
				startAutoplay();
			}
		} );

		function onVisibility() {
			if ( document.hidden || ! visible ) {
				stopAutoplay();
				mqStop();

				return;
			}

			if ( 'slider' === mode ) {
				startAutoplay();
			} else if ( 'marquee' === mode ) {
				mqSchedule();
			}
		}

		document.addEventListener( 'visibilitychange', onVisibility );

		if ( window.IntersectionObserver ) {
			new window.IntersectionObserver( function ( entries ) {
				visible = entries[ entries.length - 1 ].isIntersecting;
				onVisibility();
			}, { rootMargin: '100px 0px' } ).observe( root );
		}

		/* ====================================================
		   تعویض حالت
		   ==================================================== */

		function teardown( old ) {
			if ( 'slider' === old ) {
				sliderTeardown();
			} else if ( 'marquee' === old ) {
				marqueeTeardown();
			}
		}

		function setup( now ) {
			if ( 'slider' === now ) {
				sliderSetup();
			} else if ( 'marquee' === now ) {
				marqueeSetup();
			}
		}

		var applyRaf = 0;

		function apply() {
			applyRaf = 0;

			if ( ! root.isConnected ) {
				return;
			}

			var next = readMode();

			if ( next !== mode ) {
				teardown( mode );
				mode = next;
				root.setAttribute( 'data-active-mode', mode );
				setup( mode );

				return;
			}

			if ( 'slider' === mode ) {
				sliderRefresh();
			} else if ( 'marquee' === mode ) {
				mqBuild();
			}
		}

		function scheduleApply() {
			if ( ! applyRaf ) {
				applyRaf = window.requestAnimationFrame( apply );
			}
		}

		apply();

		window.addEventListener( 'resize', scheduleApply );

		if ( window.ResizeObserver ) {
			// فقط قاب و اولین برند؛ مشاهدهٔ خود نوار با هر افزودن نسخهٔ تکراری
			// دوباره فعال می‌شد.
			var ro = new window.ResizeObserver( scheduleApply );

			ro.observe( viewport );
			ro.observe( originals[ 0 ] );
		}

		// لوگوهایی که دیرتر بار می‌شوند ممکن است ارتفاع را عوض کنند.
		Array.prototype.forEach.call( list.querySelectorAll( 'img' ), function ( img ) {
			if ( ! img.complete ) {
				img.addEventListener( 'load', scheduleApply, { once: true } );
			}
		} );

		if ( window.matchMedia ) {
			var motion = window.matchMedia( '(prefers-reduced-motion: reduce)' );
			var onMotion = function () {
				if ( 'marquee' === mode ) {
					mqBuild();
					mqSchedule();
				}
			};

			if ( motion.addEventListener ) {
				motion.addEventListener( 'change', onMotion );
			} else if ( motion.addListener ) {
				motion.addListener( onMotion );
			}
		}
	}

	function init( scope ) {
		var root = scope || document;
		var nodes = root.querySelectorAll ? root.querySelectorAll( '[data-ard-brands]' ) : [];

		Array.prototype.forEach.call( nodes, function ( el ) {
			if ( ! el.ardBrandsReady ) {
				el.ardBrandsReady = true;
				initBrandRings( el );
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

	// ویرایشگر المنتور هر بار ویجت را از نو می‌سازد.
	window.addEventListener( 'elementor/frontend/init', function () {
		if ( ! window.elementorFrontend || ! window.elementorFrontend.hooks ) {
			return;
		}

		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/ardal-brand-rings.default',
			function ( $scope ) {
				init( $scope && $scope[ 0 ] ? $scope[ 0 ] : null );
			}
		);
	} );
}() );
