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

	/*
	 * تنها پیاده‌سازی شمارنده.
	 *
	 * یک نسخهٔ دوم با همین نام پایین‌تر در همین فایل بود که چون اعلان تابع
	 * دیرتر بر اولی سایه می‌انداخت، عملاً همین یکی هیچ‌وقت اجرا نمی‌شد و
	 * شمارندهٔ «پیشنهاد ویژه»، «جشنواره حراج» و «باکس خرید» هر سه مرده بودند:
	 * آن نسخه دنبال data-seconds و data-ard-clock-h می‌گشت که هیچ ویجتی
	 * نمی‌نویسد، پس همان اول return می‌کرد.
	 */
	function initCountdown( root ) {
		var cells = {};

		Array.prototype.forEach.call( root.querySelectorAll( '[data-unit]' ), function ( el ) {
			cells[ el.getAttribute( 'data-unit' ) ] = el;
		} );

		var withDays = '1' === root.getAttribute( 'data-days' );
		var daily = 'daily' === root.getAttribute( 'data-mode' );
		var remaining = parseInt( root.getAttribute( 'data-remaining' ), 10 );

		// مدت شروع دوبارهٔ دلخواه (ثانیه)؛ نبودش یعنی شمارنده یک‌بار است.
		var restart = parseInt( root.getAttribute( 'data-restart' ), 10 );
		var keepKey = root.getAttribute( 'data-keep' ) || '';

		if ( isNaN( remaining ) ) {
			return;
		}

		if ( isNaN( restart ) || restart < 1 ) {
			restart = 0;
		}

		// زمان پایان را نسبت به لحظه بارگذاری صفحه می‌سنجیم تا کش صفحه شمارنده را خراب نکند.
		var endsAt = Date.now() + remaining * 1000;

		/*
		 * مهلت ماندگار.
		 *
		 * «مدت ثابت از لحظهٔ بازدید» بدون این، با هر تازه‌کردن صفحه از اول
		 * شروع می‌شد و کاربر می‌فهمید که مهلتی در کار نیست. مهلت یک‌بار در
		 * مرورگر خودِ او نوشته می‌شود و تا سررسید همان می‌ماند.
		 */
		if ( keepKey ) {
			try {
				var saved = parseInt( window.localStorage.getItem( keepKey ), 10 );

				if ( saved > Date.now() ) {
					endsAt = saved;
				} else {
					window.localStorage.setItem( keepKey, String( endsAt ) );
				}
			} catch ( e ) {
				// حالت ناشناس یا ذخیره‌سازی خاموش: همان مهلتِ این بازدید.
			}
		}

		function remember() {
			if ( ! keepKey ) {
				return;
			}

			try {
				window.localStorage.setItem( keepKey, String( endsAt ) );
			} catch ( e ) {}
		}

		function paint() {
			var left = Math.max( 0, Math.round( ( endsAt - Date.now() ) / 1000 ) );

			if ( 0 === left && daily ) {
				// شمارنده روزانه: دوباره تا نیمه‌شب بعدی.
				endsAt = Date.now() + 24 * 3600 * 1000;
				left = 24 * 3600;
			} else if ( 0 === left && restart ) {
				endsAt = Date.now() + restart * 1000;
				left = restart;
				remember();
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

			// یک‌بار، همان لحظه‌ای که به صفر رسید. بعدش دیگر خبری نیست.
			if ( 0 === left && ! root.ardEnded ) {
				root.ardEnded = true;
				root.dispatchEvent( new CustomEvent( 'ardal:countdown-end', { bubbles: true } ) );
			}
		}

		paint();

		var id = window.setInterval( paint, 1000 );

		root.ardStop = function () {
			window.clearInterval( id );
		};
	}

	/**
	 * فیلتر موجودی ماتریس واریاسیون‌ها.
	 *
	 * فیلترِ ردیفی است نه خانه‌ای: «فقط موجود» ردیف‌هایی را می‌گذارد که دست‌کم
	 * یک ترکیب موجود دارند. خانه‌ها سر جایشان می‌مانند، وگرنه ستون‌ها به هم
	 * می‌ریختند و ماتریس دیگر ماتریس نبود.
	 */
	function initMatrix( root ) {
		var buttons = Array.prototype.slice.call( root.querySelectorAll( '[data-ard-matrix-filter]' ) );
		var rows = Array.prototype.slice.call( root.querySelectorAll( '.ard-mtx__row' ) );
		var none = root.querySelector( '[data-ard-matrix-empty]' );

		if ( ! buttons.length || ! rows.length ) {
			return;
		}

		function apply( mode ) {
			var shown = 0;

			rows.forEach( function ( row ) {
				var keep = 'all' === mode
					|| ( 'in' === mode && '1' === row.getAttribute( 'data-has-in' ) )
					|| ( 'out' === mode && '1' === row.getAttribute( 'data-has-out' ) );

				row.hidden = ! keep;

				if ( keep ) {
					shown++;
				}
			} );

			if ( none ) {
				none.hidden = shown > 0;
			}
		}

		buttons.forEach( function ( button ) {
			button.addEventListener( 'click', function () {
				buttons.forEach( function ( other ) {
					var on = other === button;

					other.classList.toggle( 'is-active', on );
					other.setAttribute( 'aria-pressed', on ? 'true' : 'false' );
				} );

				apply( button.getAttribute( 'data-ard-matrix-filter' ) || 'all' );
			} );
		} );
	}

	/**
	 * رفتار کادر شمارش معکوس وقتی زمان تمام می‌شود.
	 *
	 * خودِ شمارنده فقط عدد می‌شمارد و یک رویداد می‌فرستد؛ اینکه بعدش پیام
	 * بیاید یا کادر برود، تصمیم ویجت است و همین‌جا اجرا می‌شود.
	 */
	function initCountdownBox( root ) {
		var action = root.getAttribute( 'data-expire' ) || 'keep';

		if ( 'keep' === action ) {
			return;
		}

		root.addEventListener( 'ardal:countdown-end', function () {
			if ( 'hide' === action ) {
				root.hidden = true;

				return;
			}

			var body = root.querySelector( '.ard-cdown__body' );
			var msg = root.querySelector( '.ard-cdown__msg' );

			if ( body ) {
				body.hidden = true;
			}

			if ( msg ) {
				msg.hidden = false;
			}

			root.classList.add( 'ard-cdown--done' );
		} );
	}

	/* --------------------------------------------------------
	   توست

	   جای alert مرورگر و جای نوارهای پیام ووکامرس. «ژله‌ای» است: چند توست که
	   کنار هم می‌نشینند با فیلتر SVG (بلور + کنتراست) به هم می‌چسبند و مثل
	   قطره از هم جدا می‌شوند.
	   -------------------------------------------------------- */

	var TOAST_LIFE = 5000;

	/**
	 * ظرف توست‌ها را می‌سازد (یک بار) و برمی‌گرداند.
	 */
	function toastHost() {
		var host = document.querySelector( '.ard-toasts' );

		if ( host ) {
			return host;
		}

		/*
		 * فیلتر ژله‌ای یک بار ساخته می‌شود و همان‌جا کنار ظرف می‌ماند.
		 * چسبیدن قطره‌ها کارِ همین است: alpha را تار می‌کند و بعد کنتراستش را
		 * بالا می‌برد، پس لبه‌های نزدیک به هم یکی می‌شوند.
		 */
		var svg = document.createElementNS( 'http://www.w3.org/2000/svg', 'svg' );

		svg.setAttribute( 'class', 'ard-toasts__filter' );
		svg.setAttribute( 'aria-hidden', 'true' );
		svg.innerHTML =
			'<defs><filter id="ard-gooey">' +
			'<feGaussianBlur in="SourceGraphic" stdDeviation="7" result="b"/>' +
			'<feColorMatrix in="b" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 20 -9" result="g"/>' +
			'<feComposite in="SourceGraphic" in2="g" operator="atop"/>' +
			'</filter></defs>';

		host = document.createElement( 'div' );
		host.className = 'ard-toasts';
		// نقش status: صفحه‌خوان پیام تازه را می‌خواند بدون اینکه تمرکز را بدزدد.
		host.setAttribute( 'role', 'status' );
		host.setAttribute( 'aria-live', 'polite' );

		document.body.appendChild( svg );
		document.body.appendChild( host );

		return host;
	}

	/**
	 * یک توست نشان می‌دهد.
	 *
	 * @param {string} text متن پیام.
	 * @param {string} kind success | error | info
	 * @param {number} [life] مدت ماندن به میلی‌ثانیه.
	 * @param {Object} [action] دکمهٔ لینک داخل توست: { url, text }.
	 */
	function ardToast( text, kind, life, action ) {
		text = String( text || '' ).trim();

		if ( ! text ) {
			return null;
		}

		var host = toastHost();

		// پیام تکراریِ پشت‌سرهم دوباره ساخته نمی‌شود؛ فقط عمرش تازه می‌شود.
		var twin = Array.prototype.filter.call( host.children, function ( el ) {
			return el.getAttribute( 'data-text' ) === text;
		} )[ 0 ];

		if ( twin ) {
			window.clearTimeout( twin.ardTimer );
			twin.ardTimer = window.setTimeout( function () {
				closeToast( twin );
			}, life || TOAST_LIFE );

			return twin;
		}

		var toast = document.createElement( 'div' );

		toast.className = 'ard-toast ard-toast--' + ( kind || 'info' );
		toast.setAttribute( 'data-text', text );

		var icon = document.createElement( 'span' );

		icon.className = 'ard-toast__icon';
		icon.setAttribute( 'aria-hidden', 'true' );
		icon.innerHTML = toastIcon( kind );

		var body = document.createElement( 'span' );

		body.className = 'ard-toast__text';
		body.textContent = text;

		var close = document.createElement( 'button' );

		close.type = 'button';
		close.className = 'ard-toast__close';
		close.setAttribute( 'aria-label', 'بستن پیام' );
		close.innerHTML = '&times;';
		close.addEventListener( 'click', function () {
			closeToast( toast );
		} );

		toast.appendChild( icon );
		toast.appendChild( body );

		if ( action && action.url && action.text ) {
			var link = document.createElement( 'a' );

			link.className = 'ard-toast__action';
			link.href = action.url;
			link.textContent = action.text;
			toast.appendChild( link );
		}

		toast.appendChild( close );
		host.appendChild( toast );

		// یک فریم بعد، تا گذارِ ورود واقعاً اجرا شود.
		window.requestAnimationFrame( function () {
			toast.classList.add( 'is-in' );
		} );

		toast.ardTimer = window.setTimeout( function () {
			closeToast( toast );
		}, life || TOAST_LIFE );

		// روی توست که باشی، نباید زیر دستت برود.
		toast.addEventListener( 'mouseenter', function () {
			window.clearTimeout( toast.ardTimer );
		} );

		toast.addEventListener( 'mouseleave', function () {
			toast.ardTimer = window.setTimeout( function () {
				closeToast( toast );
			}, 1500 );
		} );

		return toast;
	}

	function closeToast( toast ) {
		if ( ! toast || toast.ardClosing ) {
			return;
		}

		toast.ardClosing = true;
		window.clearTimeout( toast.ardTimer );
		toast.classList.remove( 'is-in' );
		toast.classList.add( 'is-out' );

		var done = function () {
			if ( toast.parentNode ) {
				toast.parentNode.removeChild( toast );
			}
		};

		toast.addEventListener( 'transitionend', done, { once: true } );

		// تور ایمنی: اگر گذار اجرا نشد (تب پنهان، reduced motion) هم برود.
		window.setTimeout( done, 500 );
	}

	function toastIcon( kind ) {
		var paths = {
			success: '<path d="M5 13l4 4L19 7"></path>',
			error: '<path d="M12 8v5"></path><path d="M12 16.5v.01"></path><circle cx="12" cy="12" r="9"></circle>',
			info: '<path d="M12 11v5"></path><path d="M12 7.5v.01"></path><circle cx="12" cy="12" r="9"></circle>'
		};

		return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">' +
			( paths[ kind ] || paths.info ) + '</svg>';
	}

	/**
	 * نوارهای پیام ووکامرس را برمی‌دارد و به‌جایشان توست نشان می‌دهد.
	 *
	 * چرا حذف و نه پنهان‌کردن: این نوارها بالای محتوا می‌نشینند و چیدمان صفحه
	 * را جابه‌جا می‌کنند؛ پنهان‌کردنشان با CSS هم فضای خالی به جا می‌گذاشت.
	 *
	 * @param {Element} scope
	 */
	function collectWooNotices( scope ) {
		var root = scope || document;
		var map = [
			[ '.woocommerce-error li, .woocommerce-error', 'error' ],
			[ '.woocommerce-message', 'success' ],
			[ '.woocommerce-info, .woocommerce-notice--info', 'info' ]
		];

		map.forEach( function ( pair ) {
			Array.prototype.forEach.call( root.querySelectorAll( pair[ 0 ] ), function ( el ) {
				if ( el.ardTaken || el.closest( '.ard-toasts' ) ) {
					return;
				}

				// «کد تخفیف دارید؟» پیام نیست، دکمهٔ باز کردن فرم است؛ کد تخفیف
				// در مرحلهٔ سبد خرید فیلد خودش را دارد، پس بی‌صدا کنار می‌رود.
				var couponToggle = el.closest( '.woocommerce-form-coupon-toggle' );

				if ( couponToggle ) {
					el.ardTaken = true;
					couponToggle.parentNode.removeChild( couponToggle );

					return;
				}

				// <li> داخل .woocommerce-error جداگانه خوانده می‌شود؛ خودِ ظرف نه.
				if ( 'UL' === el.tagName && el.querySelector( 'li' ) ) {
					return;
				}

				el.ardTaken = true;

				var clone = el.cloneNode( true );

				Array.prototype.forEach.call( clone.querySelectorAll( 'a, button' ), function ( n ) {
					n.parentNode.removeChild( n );
				} );

				ardToast( ( clone.textContent || '' ).replace( /\s+/g, ' ' ).trim(), pair[ 1 ] );

				var host = ( 'LI' === el.tagName ) ? ( el.closest( '.woocommerce-error' ) || el ) : el;

				if ( host.parentNode ) {
					host.parentNode.removeChild( host );
				}
			} );
		} );
	}

	/**
	 * پیام‌های ووکامرس را — چه در بارگذاری صفحه و چه بعد از ایجکس — می‌گیرد.
	 */
	function initWooNotices() {
		collectWooNotices( document );

		if ( window.jQuery ) {
			var $ = window.jQuery;

			// ووکامرس بعد از هر عملیات ایجکسی این رویدادها را می‌زند.
			$( document.body ).on(
				'added_to_cart removed_from_cart updated_cart_totals updated_checkout applied_coupon removed_coupon wc_fragments_refreshed',
				function () {
					window.setTimeout( function () {
						collectWooNotices( document );
					}, 50 );
				}
			);

			$( document.body ).on( 'wc_add_to_cart_message', function () {
				window.setTimeout( function () {
					collectWooNotices( document );
				}, 50 );
			} );
		}

		/*
		 * بعضی افزونه‌ها نوار پیام را مستقیم داخل DOM می‌گذارند بدون هیچ
		 * رویدادی؛ یک ناظر سبک همان‌ها را هم می‌گیرد.
		 */
		if ( window.MutationObserver ) {
			var pending = null;

			new window.MutationObserver( function () {
				window.clearTimeout( pending );
				pending = window.setTimeout( function () {
					collectWooNotices( document );
				}, 80 );
			} ).observe( document.body, { childList: true, subtree: true } );
		}
	}

	// در دسترسِ بقیهٔ اسکریپت‌های افزونه (سبد، پنل کاربری) هم باشد.
	window.ardToast = ardToast;

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

		/*
		 * کلاس نقطه‌ها از خود عنصر خوانده می‌شود تا کروسل مشترک بماند؛ قبلاً
		 * ثابت و مال «پیشنهاد ویژه» بود و هر ویجت تازه‌ای نقطه‌هایش استایل
		 * غریبه می‌گرفت.
		 */
		var dotClass = ( dotsBox && dotsBox.getAttribute( 'data-ard-dots' ) ) || 'ard-deal__dot';
		/*
		 * دکمه‌ها لازم نیست همسایهٔ کروسل باشند.
		 *
		 * قبلاً فقط در پدرِ مستقیم دنبالشان می‌گشتیم؛ ویجتی که دکمه‌ها را بالای
		 * عنوان یا پایین کنار نقطه‌ها می‌گذارد، بیرون از آن پدر می‌افتاد و
		 * دکمه‌هایش بی‌کار می‌ماندند. حالا اگر ویجت دامنه‌اش را با
		 * data-ard-slider اعلام کند، همان‌جا جست‌وجو می‌شود.
		 */
		var scope = root.closest ? root.closest( '[data-ard-slider]' ) : null;

		if ( ! scope ) {
			scope = root.parentNode;
		}

		var prev = scope ? scope.querySelector( '[data-ard-prev]' ) : null;
		var next = scope ? scope.querySelector( '[data-ard-next]' ) : null;
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

		/*
		 * آیا اسکرول به ته رسیده؟
		 *
		 * وقتی تعداد اسلایدها مضرب «تعداد در هر نما» نباشد، صفحهٔ آخر تا ته
		 * اسکرول نمی‌شود؛ مثلاً ۱۰ تا با نمای ۷تایی فقط ۳ تا جا دارد جلو برود.
		 * بدون این بررسی، currentPage هنوز صفر گزارش می‌کرد و دکمهٔ «بعدی»
		 * بار دوم به همان جایی می‌رفت که بودیم — یعنی فقط یک بار کار می‌کرد.
		 */
		function atEnd() {
			var box = viewport.getBoundingClientRect();
			var last = slides[ slides.length - 1 ].getBoundingClientRect();

			return isRtl() ? last.left >= box.left - 2 : last.right <= box.right + 2;
		}

		function currentPage() {
			var box = viewport.getBoundingClientRect();
			var rtl = isRtl();
			var best = 0;
			var bestDist = Infinity;

			if ( atEnd() ) {
				return pageCount() - 1;
			}

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

			/*
			 * نقطهٔ فعال همین‌جا عوض می‌شود، نه بعد از رسیدن اسکرول.
			 * اسکرول نرم چند قاب طول می‌کشد و تا آن موقع currentPage هنوز
			 * صفحهٔ قبلی را می‌خواند؛ نتیجه‌اش این بود که نقطه‌ها یک کلیک عقب
			 * می‌ماندند یا اصلاً تکان نمی‌خوردند.
			 */
			paint( wrapped );
		}

		/**
		 * @param {number} [page] صفحهٔ دلخواه؛ نیامده باشد از روی اسکرول حساب می‌شود.
		 */
		function paint( page ) {
			var active = 'number' === typeof page ? page : currentPage();

			dots.forEach( function ( dot, i ) {
				dot.classList.toggle( 'is-active', i === active );
				dot.setAttribute( 'aria-selected', i === active ? 'true' : 'false' );
			} );

			markActiveSlide();
		}

		/**
		 * علامت‌زدن اسلایدی که وسط نما ایستاده.
		 *
		 * اختیاری است و فقط وقتی اجرا می‌شود که ریل data-ard-active-slide
		 * داشته باشد؛ ویجتی که کارت وسط را بزرگ‌تر نشان می‌دهد با همین کلاس
		 * تصمیم می‌گیرد کدام بزرگ شود.
		 */
		function markActiveSlide() {
			if ( ! viewport.hasAttribute( 'data-ard-active-slide' ) ) {
				return;
			}

			var box = viewport.getBoundingClientRect();
			var mid = box.left + box.width / 2;
			var best = 0;
			var bestDist = Infinity;

			slides.forEach( function ( slide, i ) {
				var r = slide.getBoundingClientRect();
				var dist = Math.abs( ( r.left + r.width / 2 ) - mid );

				if ( dist < bestDist ) {
					bestDist = dist;
					best = i;
				}
			} );

			slides.forEach( function ( slide, i ) {
				slide.classList.toggle( 'is-active', i === best );
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
					dot.className = dotClass;
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

		initDragScroll( viewport );

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
		markActiveSlide();
		start();
	}

	/**
	 * کشیدن افقی با ماوس.
	 *
	 * لمس دست‌نخورده می‌ماند: مرورگر خودش اسکرول لمسی را بهتر از هر کدی که
	 * اینجا بنویسیم انجام می‌دهد (اینرسی، لبه، snap). این فقط برای ماوس و قلم
	 * است، که بدون آن کاربر دسکتاپ راهی جز دکمه‌ها ندارد.
	 */
	function initDragScroll( viewport ) {
		// کمتر از این مقدار، حرکت را کشیدن حساب نمی‌کنیم تا کلیک ساده نسوزد.
		var SLOP = 5;
		var drag = null;
		var snap = '';

		viewport.classList.add( 'ard-drag' );

		viewport.addEventListener( 'pointerdown', function ( event ) {
			if ( 'touch' === event.pointerType || ( event.button && 0 !== event.button ) ) {
				return;
			}

			drag = {
				id: event.pointerId,
				x: event.clientX,
				left: viewport.scrollLeft,
				moved: false
			};
		} );

		viewport.addEventListener( 'pointermove', function ( event ) {
			if ( ! drag ) {
				return;
			}

			var dx = event.clientX - drag.x;

			if ( ! drag.moved ) {
				if ( Math.abs( dx ) < SLOP ) {
					return;
				}

				drag.moved = true;
				viewport.classList.add( 'is-dragging' );

				/*
				 * snap باید موقع کشیدن خاموش باشد، وگرنه مرورگر هر قاب،
				 * اسکرول را به نزدیک‌ترین اسلاید برمی‌گرداند و کشیدن می‌لرزد.
				 */
				snap = viewport.style.scrollSnapType;
				viewport.style.scrollSnapType = 'none';

				if ( viewport.setPointerCapture ) {
					viewport.setPointerCapture( drag.id );
				}
			}

			viewport.scrollLeft = drag.left - dx;
			event.preventDefault();
		} );

		function release() {
			if ( ! drag ) {
				return;
			}

			var moved = drag.moved;

			if ( viewport.releasePointerCapture && viewport.hasPointerCapture && viewport.hasPointerCapture( drag.id ) ) {
				viewport.releasePointerCapture( drag.id );
			}

			drag = null;
			viewport.classList.remove( 'is-dragging' );

			if ( moved ) {
				viewport.style.scrollSnapType = snap;

				/*
				 * کلیکی که ته یک کشیدن می‌آید نباید لینک دسته را باز کند.
				 * شنونده در فاز capture می‌نشیند تا پیش از لینک برسد، و در
				 * همان تیک بعدی برداشته می‌شود — کلیکِ بعد از pointerup
				 * هم‌زمان می‌آید، پس این مهلت کافی است و شنونده هم نمی‌ماند.
				 */
				var swallow = function ( e ) {
					e.preventDefault();
					e.stopPropagation();
				};

				viewport.addEventListener( 'click', swallow, true );

				window.setTimeout( function () {
					viewport.removeEventListener( 'click', swallow, true );
				}, 0 );
			}
		}

		viewport.addEventListener( 'pointerup', release );
		viewport.addEventListener( 'pointercancel', release );
		viewport.addEventListener( 'pointerleave', release );

		// کشیدن نباید تصویر یا متن را «بردارد».
		viewport.addEventListener( 'dragstart', function ( event ) {
			if ( drag && drag.moved ) {
				event.preventDefault();
			}
		} );
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
	   تایمر شمارش معکوس
	   -------------------------------------------------------- */

	/* --------------------------------------------------------
	   علاقه‌مندی و مقایسه
	   -------------------------------------------------------- */

	var STORE = {
		wishlist: 'ardal_wishlist',
		compare: 'ardal_compare'
	};

	// پرچم «این فهرست را مهمان ساخته»؛ فقط چنین فهرستی بعد از ورود ادغام می‌شود.
	var GUEST_FLAG = 'ardal_wishlist_guest';

	var WISH = window.ardalWishlist || {};
	var WISH_I18N = WISH.i18n || {};

	// اگر localStorage کار نکند، فهرست دست‌کم تا پایان صفحه در حافظه می‌ماند.
	var listMemory = {};

	/**
	 * خواندن فهرست ذخیره‌شده. اگر localStorage در دسترس نباشد (مرورگر خصوصی،
	 * کوکی خاموش) آرایه خالی برمی‌گردد و دکمه‌ها همچنان بدون خطا کار می‌کنند.
	 */
	function listGet( kind ) {
		try {
			var raw = window.localStorage.getItem( STORE[ kind ] );
			var arr = raw ? JSON.parse( raw ) : [];

			return Array.isArray( arr ) ? arr.map( String ) : [];
		} catch ( e ) {
			return listMemory[ kind ] ? listMemory[ kind ].slice() : [];
		}
	}

	function listSet( kind, ids ) {
		listMemory[ kind ] = ids.slice();

		try {
			window.localStorage.setItem( STORE[ kind ], JSON.stringify( ids ) );
		} catch ( e ) {
			// فضای ذخیره‌سازی در دسترس نیست؛ وضعیت فقط تا پایان همین صفحه می‌ماند.
		}

		document.dispatchEvent( new CustomEvent( 'ardal:' + kind, { detail: { ids: ids } } ) );
		paintListCounts( kind, ids.length );
	}

	function listHas( kind, id ) {
		return listGet( kind ).indexOf( String( id ) ) !== -1;
	}

	/**
	 * تغییر محلی؛ برای مقایسه و علاقه‌مندیِ مهمان.
	 *
	 * @return {boolean} وضعیت تازه.
	 */
	function listToggleLocal( kind, id ) {
		var ids = listGet( kind );
		var at = ids.indexOf( String( id ) );

		if ( at === -1 ) {
			// تازه‌ترین اول، مثل فهرست سرور.
			ids.unshift( String( id ) );
		} else {
			ids.splice( at, 1 );
		}

		listSet( kind, ids );

		return at === -1;
	}

	function paintListCounts( kind, count ) {
		Array.prototype.forEach.call(
			document.querySelectorAll( '[data-ard-' + kind + '-count]' ),
			function ( el ) {
				el.textContent = toFa( count );
				el.hidden = count < 1 && ! el.hasAttribute( 'data-show-zero' );
			}
		);
	}

	function wishToast( text, kind, action ) {
		if ( text && false !== WISH.toast ) {
			ardToast( text, kind, null, action );
		}
	}

	function wishLoginToast( message, url ) {
		ardToast( message || WISH_I18N.login, 'info', 7000, {
			url: url || WISH.loginUrl,
			text: WISH_I18N.loginBtn || 'ورود'
		} );
	}

	function wishPost( data ) {
		var body = new window.FormData();

		body.append( 'nonce', WISH.nonce || '' );

		Object.keys( data ).forEach( function ( key ) {
			if ( Array.isArray( data[ key ] ) ) {
				data[ key ].forEach( function ( value ) {
					body.append( key + '[]', value );
				} );

				return;
			}

			body.append( key, data[ key ] );
		} );

		return window.fetch( WISH.ajax, { method: 'POST', body: body, credentials: 'same-origin' } )
			.then( function ( response ) {
				return response.json();
			} );
	}

	/**
	 * کارت‌های فهرست علاقه‌مندی که محصولشان از فهرست بیرون رفته حذف می‌شوند.
	 */
	function dropWishlistCards( id ) {
		Array.prototype.forEach.call(
			document.querySelectorAll( '[data-ard-wishlist-item="' + id + '"]' ),
			function ( card ) {
				var list = card.closest( '[data-ard-wishlist-list]' );

				card.classList.add( 'is-leaving' );

				window.setTimeout( function () {
					if ( card.parentNode ) {
						card.parentNode.removeChild( card );
					}

					if ( list && ! list.querySelector( '[data-ard-wishlist-item]' ) ) {
						var empty = list.querySelector( '[data-ard-wishlist-empty]' );
						var grid = list.querySelector( '[data-ard-wishlist-grid]' );

						if ( grid ) {
							grid.hidden = true;
						}

						if ( empty ) {
							empty.hidden = false;
						}
					}

					if ( list ) {
						var counter = list.querySelector( '[data-ard-wishlist-total]' );

						if ( counter ) {
							counter.textContent = toFa( list.querySelectorAll( '[data-ard-wishlist-item]' ).length );
						}
					}
				}, 280 );
			}
		);
	}

	/**
	 * کلیک قلب.
	 *
	 * کاربر واردشده: همان لحظه به سرور می‌رود و فهرست سرور جای فهرست محلی
	 * می‌نشیند. مهمان: بسته به تنظیمات، یا محلی ذخیره می‌شود یا پیام ورود.
	 *
	 * @param {string}   id
	 * @param {Function} busy
	 */
	function wishlistClick( id, busy ) {
		if ( ! WISH.loggedIn ) {
			if ( 'login' === WISH.guest ) {
				wishLoginToast();

				return;
			}

			var on = listToggleLocal( 'wishlist', id );

			try {
				window.localStorage.setItem( GUEST_FLAG, '1' );
			} catch ( e ) {}

			wishToast( on ? WISH_I18N.added : WISH_I18N.removed, on ? 'success' : 'info' );

			if ( ! on ) {
				dropWishlistCards( id );
			}

			return;
		}

		var was = listHas( 'wishlist', id );

		// رنگ همان لحظه عوض می‌شود؛ اگر سرور نپذیرفت برمی‌گردد.
		listToggleLocal( 'wishlist', id );
		busy( true );

		wishPost( { action: 'ardal_wishlist_toggle', product: id, want: was ? 'remove' : 'add' } )
			.then( function ( result ) {
				var data = ( result && result.data ) || {};

				if ( ! result || ! result.success ) {
					if ( 'logged_out' === data.code ) {
						WISH.loggedIn = false;
						listToggleLocal( 'wishlist', id );
						wishLoginToast( data.message, data.login );

						return;
					}

					var error = new Error( data.message || '' );

					error.fromServer = !! data.message;
					throw error;
				}

				listSet( 'wishlist', ( data.ids || [] ).map( String ) );

				wishToast(
					data.active ? WISH_I18N.added : WISH_I18N.removed,
					data.active ? 'success' : 'info',
					data.active && WISH.pageUrl ? { url: WISH.pageUrl, text: WISH_I18N.view } : null
				);

				if ( ! data.active ) {
					dropWishlistCards( id );
				}
			} )
			.catch( function ( error ) {
				// به وضعیت پیش از کلیک برگرد.
				if ( listHas( 'wishlist', id ) !== was ) {
					listToggleLocal( 'wishlist', id );
				}

				ardToast( error && error.fromServer ? error.message : WISH_I18N.error, 'error' );
			} )
			.then( function () {
				busy( false );
			} );
	}

	/**
	 * یک بار در هر صفحه: فهرست محلی با حساب هماهنگ می‌شود.
	 *
	 * فهرستی که مهمان ساخته بعد از ورود یک بار ادغام می‌شود؛ در بقیهٔ موارد
	 * فهرست سرور جای فهرست محلی را می‌گیرد. همین جلوی «برگشتن محصول
	 * حذف‌شده» را می‌گیرد.
	 */
	function syncWishlistOnce() {
		if ( document.body.ardWishSynced || ! WISH.enabled ) {
			return;
		}

		document.body.ardWishSynced = true;

		if ( ! WISH.loggedIn || ! Array.isArray( WISH.ids ) ) {
			paintListCounts( 'wishlist', listGet( 'wishlist' ).length );

			return;
		}

		var guest = false;

		try {
			guest = '1' === window.localStorage.getItem( GUEST_FLAG );
		} catch ( e ) {}

		var missing = listGet( 'wishlist' ).filter( function ( item ) {
			return WISH.ids.indexOf( item ) === -1;
		} );

		listSet( 'wishlist', WISH.ids.slice() );

		if ( guest && WISH.merge && missing.length ) {
			wishPost( { action: 'ardal_wishlist_merge', ids: missing } )
				.then( function ( result ) {
					if ( result && result.success ) {
						listSet( 'wishlist', ( result.data.ids || [] ).map( String ) );
					}
				} )
				.catch( function () {} );
		}

		try {
			window.localStorage.removeItem( GUEST_FLAG );
		} catch ( e ) {}
	}

	// پنل کاربری محصولی را خودش حذف کرده؛ فهرست محلی هم به‌روز شود.
	document.addEventListener( 'ardal:wishlist-sync', function ( event ) {
		var removed = event.detail && event.detail.removed;

		if ( removed ) {
			listSet( 'wishlist', listGet( 'wishlist' ).filter( function ( item ) {
				return item !== removed;
			} ) );
		}
	} );

	function initListButton( btn ) {
		var kind = btn.hasAttribute( 'data-ard-wishlist' ) ? 'wishlist' : 'compare';
		var id = btn.getAttribute( 'data-product-id' );

		if ( ! id ) {
			// بدون شناسه محصول کاری نمی‌شود کرد؛ دکمه را پنهان می‌کنیم تا
			// کاربر روی چیزی که کار نمی‌کند کلیک نکند.
			btn.hidden = true;

			return;
		}

		function paint( on ) {
			var was = btn.classList.contains( 'is-active' );

			btn.classList.toggle( 'is-active', on );
			btn.setAttribute( 'aria-pressed', on ? 'true' : 'false' );

			// تپش کوچک فقط وقتی تازه روشن شده، نه موقع بارگذاری صفحه.
			if ( on && ! was && btn.ardPainted ) {
				btn.classList.remove( 'is-pop' );
				void btn.offsetWidth;
				btn.classList.add( 'is-pop' );
			}

			btn.ardPainted = true;

			var label = btn.getAttribute( on ? 'data-label-on' : 'data-label-off' );

			if ( label ) {
				btn.setAttribute( 'aria-label', label );
				btn.setAttribute( 'title', label );

				// دکمه‌های متنی (مثل لینک علاقه‌مندی در باکس خرید) خودِ متن را
				// هم عوض می‌کنند، نه فقط aria-label را.
				if ( btn.hasAttribute( 'data-ard-list-text' ) ) {
					btn.textContent = label;
				}

				var text = btn.querySelector( '[data-ard-wish-text]' );

				if ( text ) {
					text.textContent = label;
				}
			}
		}

		paint( listHas( kind, id ) );

		btn.addEventListener( 'click', function ( e ) {
			e.preventDefault();

			if ( btn.classList.contains( 'is-busy' ) ) {
				return;
			}

			if ( 'wishlist' === kind ) {
				wishlistClick( id, function ( state ) {
					btn.classList.toggle( 'is-busy', state );
				} );

				return;
			}

			paint( listToggleLocal( kind, id ) );
		} );

		// اگر همین محصول در جای دیگری از صفحه هم دکمه دارد، هماهنگ بماند.
		document.addEventListener( 'ardal:' + kind, function () {
			paint( listHas( kind, id ) );
		} );
	}

	/* --------------------------------------------------------
	   گالری محصول: اسلایدر + ذره‌بین + لایت‌باکس
	   -------------------------------------------------------- */

	function canHover() {
		return window.matchMedia && window.matchMedia( '(hover: hover) and (pointer: fine)' ).matches;
	}

	function initProductGallery( root ) {
		var track = root.querySelector( '[data-ard-gallery-track]' );
		var slides = track ? Array.prototype.slice.call( track.children ) : [];
		var thumbs = Array.prototype.slice.call( root.querySelectorAll( '.ard-prod-gallery__thumb' ) );
		var thumbsTrack = root.querySelector( '[data-ard-gallery-thumbs]' );
		var dotsBox = root.querySelector( '[data-ard-gallery-dots]' );
		var index = 0;

		if ( ! track || ! slides.length ) {
			return;
		}

		var rtl = 'rtl' === window.getComputedStyle( track ).direction;

		function currentIndex() {
			var box = track.getBoundingClientRect();
			var best = 0;
			var bestDist = Infinity;

			slides.forEach( function ( slide, i ) {
				var r = slide.getBoundingClientRect();
				var d = Math.abs( rtl ? box.right - r.right : r.left - box.left );

				if ( d < bestDist - 1 ) {
					bestDist = d;
					best = i;
				}
			} );

			return best;
		}

		function setActive( i ) {
			index = Math.max( 0, Math.min( slides.length - 1, i ) );

			thumbs.forEach( function ( t, n ) {
				var on = n === index;

				t.classList.toggle( 'is-active', on );
				t.setAttribute( 'aria-selected', on ? 'true' : 'false' );

				// نوار بندانگشتی حالا اسلایدر است، پس بندانگشتیِ فعال ممکن است
				// بیرون از نما باشد و باید خودش را نشان بدهد.
				if ( on && thumbsTrack ) {
					revealThumb( thumbsTrack, t );
				}
			} );

			if ( dotsBox ) {
				Array.prototype.forEach.call( dotsBox.children, function ( d, n ) {
					d.classList.toggle( 'is-active', n === index );
				} );
			}
		}

		function paint() {
			setActive( currentIndex() );
		}

		function goTo( i ) {
			var n = Math.max( 0, Math.min( slides.length - 1, i ) );

			// وضعیت را همان لحظه به‌روز می‌کنیم و منتظر رویداد scroll نمی‌مانیم:
			// انیمیشن نرم زمان می‌برد و کاربر باید بلافاصله بازخورد ببیند.
			setActive( n );

			slides[ n ].scrollIntoView( {
				behavior: prefersReducedMotion() ? 'auto' : 'smooth',
				block: 'nearest',
				inline: 'start'
			} );
		}

		thumbs.forEach( function ( thumb, i ) {
			thumb.addEventListener( 'click', function () {
				goTo( i );
			} );
		} );

		if ( dotsBox && slides.length > 1 ) {
			slides.forEach( function ( slide, i ) {
				var dot = document.createElement( 'button' );

				dot.type = 'button';
				dot.className = 'ard-prod-gallery__dot';
				dot.setAttribute( 'aria-label', 'تصویر ' + toFa( i + 1 ) );
				dot.addEventListener( 'click', function () {
					goTo( i );
				} );
				dotsBox.appendChild( dot );
			} );
		}

		var prev = root.querySelector( '[data-ard-gallery-prev]' );
		var next = root.querySelector( '[data-ard-gallery-next]' );

		if ( prev ) {
			prev.addEventListener( 'click', function () {
				goTo( currentIndex() - 1 );
			} );
		}

		if ( next ) {
			next.addEventListener( 'click', function () {
				goTo( currentIndex() + 1 );
			} );
		}

		var raf = null;

		track.addEventListener( 'scroll', function () {
			if ( raf ) {
				return;
			}

			raf = window.requestAnimationFrame( function () {
				raf = null;
				paint();
			} );
		}, { passive: true } );

		paint();

		initThumbsSlider( root );
		initGalleryZoom( root, track, slides );
		initGalleryLightbox( root, slides, goTo );
	}

	/**
	 * بندانگشتیِ فعال را داخل نمای تِرَک می‌آورد، بدون اسکرول‌کردن کل صفحه
	 * (`scrollIntoView` صفحه را هم جابه‌جا می‌کرد).
	 */
	function revealThumb( track, thumb ) {
		var t = track.getBoundingClientRect();
		var r = thumb.getBoundingClientRect();
		var gallery = track.closest( '[data-ard-gallery]' );
		var vertical = gallery && 'vertical' === gallery.getAttribute( 'data-thumbs-position' );

		if ( vertical ) {
			if ( r.top >= t.top - 1 && r.bottom <= t.bottom + 1 ) {
				return;
			}

			track.scrollTop += r.top < t.top ? r.top - t.top : r.bottom - t.bottom;
			return;
		}

		if ( r.left >= t.left - 1 && r.right <= t.right + 1 ) {
			return;
		}

		var delta = r.left < t.left ? r.left - t.left : r.right - t.right;

		track.scrollLeft += delta;
	}

	/**
	 * فلش‌های نوار بندانگشتی: یک «صفحه» جابه‌جا می‌شوند و در دو انتهای مسیر
	 * پنهان می‌شوند تا کاربر روی دکمهٔ بی‌اثر کلیک نکند.
	 */
	function initThumbsSlider( root ) {
		var track = root.querySelector( '[data-ard-gallery-thumbs]' );

		if ( ! track ) {
			return;
		}

		var prev = root.querySelector( '[data-ard-thumbs-prev]' );
		var next = root.querySelector( '[data-ard-thumbs-next]' );
		var vertical = 'vertical' === root.getAttribute( 'data-thumbs-position' );

		function step() {
			return Math.max( 60, ( vertical ? track.clientHeight : track.clientWidth ) * 0.8 );
		}

		function paintArrows() {
			if ( vertical ) {
				var verticalMax = track.scrollHeight - track.clientHeight;
				var verticalPos = track.scrollTop;
				var verticalScrollable = verticalMax > 2;

				if ( prev ) {
					prev.hidden = ! verticalScrollable || verticalPos <= 1;
				}

				if ( next ) {
					next.hidden = ! verticalScrollable || verticalPos >= verticalMax - 1;
				}
				return;
			}

			// در RTL کروم scrollLeft منفی است، پس با قدر مطلق کار می‌کنیم.
			var pos = Math.abs( track.scrollLeft );
			var max = track.scrollWidth - track.clientWidth;
			var scrollable = max > 2;

			if ( prev ) {
				prev.hidden = ! scrollable || pos <= 1;
			}

			if ( next ) {
				next.hidden = ! scrollable || pos >= max - 1;
			}
		}

		function nudge( dir ) {
			if ( vertical ) {
				track.scrollTop += dir * step();
				return;
			}

			// در RTL کروم scrollLeft از صفر شروع می‌شود و منفی می‌رود، پس جهت را
			// از خودِ direction می‌گیریم نه از مقدار فعلی اسکرول (که در ابتدای
			// مسیر در هر دو جهت صفر است و علامت را اشتباه می‌داد).
			var rtl = 'rtl' === getComputedStyle( track ).direction;

			track.scrollLeft += dir * step() * ( rtl ? -1 : 1 );
		}

		if ( prev ) {
			prev.addEventListener( 'click', function () {
				nudge( -1 );
			} );
		}

		if ( next ) {
			next.addEventListener( 'click', function () {
				nudge( 1 );
			} );
		}

		track.addEventListener( 'scroll', paintArrows, { passive: true } );
		window.addEventListener( 'resize', paintArrows );
		paintArrows();
	}

	/**
	 * ذره‌بین: یک عدسی گرد که همان نقطهٔ زیر نشانگر را بزرگ‌شده نشان می‌دهد.
	 * فقط روی دستگاه‌هایی که واقعاً hover دارند فعال می‌شود.
	 */
	function initGalleryZoom( root, track, slides ) {
		if ( 'yes' !== root.getAttribute( 'data-zoom' ) || ! canHover() ) {
			return;
		}

		var factor = parseFloat( root.getAttribute( 'data-zoom-factor' ) ) || 2.5;
		var lens = document.createElement( 'div' );

		lens.className = 'ard-prod-gallery__lens';
		lens.setAttribute( 'aria-hidden', 'true' );
		root.querySelector( '.ard-prod-gallery__main' ).appendChild( lens );

		function activeImage() {
			var box = track.getBoundingClientRect();

			for ( var i = 0; i < slides.length; i++ ) {
				var r = slides[ i ].getBoundingClientRect();

				if ( r.left < box.right - 5 && r.right > box.left + 5 ) {
					return slides[ i ].querySelector( 'img' );
				}
			}

			return null;
		}

		function move( e ) {
			var img = activeImage();

			if ( ! img || ! img.src ) {
				lens.classList.remove( 'is-on' );

				return;
			}

			// روی دکمه‌ها و فلش‌ها عدسی نباید بیاید؛ هم دیدشان را می‌گرفت و هم
			// کاربر نمی‌فهمید دارد روی چه چیزی کلیک می‌کند.
			if ( e.target && e.target.closest && e.target.closest( 'button, a, [role="button"]' ) ) {
				lens.classList.remove( 'is-on' );

				return;
			}

			var r = img.getBoundingClientRect();

			// بیرون از خود تصویر (نوارهای خالی کنارش) عدسی نباید ظاهر شود.
			if ( e.clientX < r.left || e.clientX > r.right || e.clientY < r.top || e.clientY > r.bottom ) {
				lens.classList.remove( 'is-on' );

				return;
			}

			var host = lens.parentNode.getBoundingClientRect();
			var x = e.clientX - r.left;
			var y = e.clientY - r.top;

			// اگر نسخهٔ اصلی موجود باشد ذره‌بین از آن بزرگ‌نمایی می‌کند تا
			// تصویرِ بزرگ‌شده تار نشود.
			lens.style.backgroundImage = 'url("' + ( img.getAttribute( 'data-full' ) || img.currentSrc ) + '")';
			lens.style.backgroundSize = ( r.width * factor ) + 'px ' + ( r.height * factor ) + 'px';
			lens.style.backgroundPosition =
				( -( x * factor - lens.offsetWidth / 2 ) ) + 'px ' +
				( -( y * factor - lens.offsetHeight / 2 ) ) + 'px';
			lens.style.left = ( e.clientX - host.left - lens.offsetWidth / 2 ) + 'px';
			lens.style.top = ( e.clientY - host.top - lens.offsetHeight / 2 ) + 'px';

			lens.classList.add( 'is-on' );
		}

		var main = root.querySelector( '.ard-prod-gallery__main' );

		main.addEventListener( 'mousemove', move );
		main.addEventListener( 'mouseleave', function () {
			lens.classList.remove( 'is-on' );
		} );
	}

	/**
	 * لایت‌باکس: با کلیک روی تصویر باز می‌شود، با کشیدن انگشت یا فلش‌ها
	 * بین تصاویر جابه‌جا می‌شود و با Esc یا کلیک روی زمینه بسته می‌شود.
	 */
	function initGalleryLightbox( root, slides, goToSlide ) {
		if ( 'yes' !== root.getAttribute( 'data-lightbox' ) ) {
			return;
		}

		var urls = slides.map( function ( s ) {
			var img = s.querySelector( 'img' );

			return img ? ( img.getAttribute( 'data-full' ) || img.src ) : '';
		} ).filter( Boolean );

		if ( ! urls.length ) {
			return;
		}

		var box = null;
		var pic = null;
		var at = 0;

		function show( i ) {
			at = ( i + urls.length ) % urls.length;

			if ( pic ) {
				pic.src = urls[ at ];
				pic.alt = 'تصویر ' + toFa( at + 1 );
			}
		}

		function close() {
			if ( ! box ) {
				return;
			}

			box.classList.remove( 'is-open' );
			document.body.style.overflow = '';
			document.removeEventListener( 'keydown', onKey );

			// اسلایدر پشت لایت‌باکس روی همان تصویری بماند که کاربر می‌دید.
			goToSlide( at );
		}

		function onKey( e ) {
			if ( 'Escape' === e.key ) {
				close();
			} else if ( 'ArrowLeft' === e.key ) {
				show( at + 1 );
			} else if ( 'ArrowRight' === e.key ) {
				show( at - 1 );
			}
		}

		function build() {
			box = document.createElement( 'div' );
			box.className = 'ard-lightbox';
			box.setAttribute( 'role', 'dialog' );
			box.setAttribute( 'aria-modal', 'true' );
			box.setAttribute( 'aria-label', 'نمایش بزرگ تصویر محصول' );

			box.innerHTML =
				'<button type="button" class="ard-lightbox__close" aria-label="بستن">&times;</button>' +
				'<button type="button" class="ard-lightbox__nav ard-lightbox__nav--prev" aria-label="تصویر قبلی"></button>' +
				'<button type="button" class="ard-lightbox__nav ard-lightbox__nav--next" aria-label="تصویر بعدی"></button>' +
				'<div class="ard-lightbox__stage"><img class="ard-lightbox__img" src="" alt=""></div>';

			document.body.appendChild( box );
			pic = box.querySelector( '.ard-lightbox__img' );

			box.querySelector( '.ard-lightbox__close' ).addEventListener( 'click', close );
			box.querySelector( '.ard-lightbox__nav--prev' ).addEventListener( 'click', function () {
				show( at - 1 );
			} );
			box.querySelector( '.ard-lightbox__nav--next' ).addEventListener( 'click', function () {
				show( at + 1 );
			} );

			box.addEventListener( 'click', function ( e ) {
				if ( e.target === box || e.target.classList.contains( 'ard-lightbox__stage' ) ) {
					close();
				}
			} );

			var startX = null;

			box.addEventListener( 'touchstart', function ( e ) {
				startX = e.changedTouches[ 0 ].clientX;
			}, { passive: true } );

			box.addEventListener( 'touchend', function ( e ) {
				if ( null === startX ) {
					return;
				}

				var delta = e.changedTouches[ 0 ].clientX - startX;

				if ( Math.abs( delta ) > 45 ) {
					show( delta < 0 ? at + 1 : at - 1 );
				}

				startX = null;
			}, { passive: true } );
		}

		function open( i ) {
			if ( ! box ) {
				build();
			}

			show( i );
			box.classList.add( 'is-open' );
			document.body.style.overflow = 'hidden';
			document.addEventListener( 'keydown', onKey );
			box.querySelector( '.ard-lightbox__close' ).focus();
		}

		// اگر کاربر در حال سوایپ روی اسلایدر باشد، کلیکِ ناخواسته نباید لایت‌باکس را باز کند.
		var downAt = null;

		slides.forEach( function ( slide, i ) {
			slide.addEventListener( 'pointerdown', function ( e ) {
				downAt = { x: e.clientX, y: e.clientY };
			} );

			slide.addEventListener( 'click', function ( e ) {
				if ( downAt && Math.abs( e.clientX - downAt.x ) + Math.abs( e.clientY - downAt.y ) > 12 ) {
					downAt = null;

					return;
				}

				downAt = null;
				open( i );
			} );
		} );

		if ( urls.length < 2 ) {
			// با یک تصویر، فلش‌ها بی‌معنا هستند.
			root.setAttribute( 'data-single', 'yes' );
		}
	}

	/* --------------------------------------------------------
	   انتخاب رنگ و سایز و متغیرهای محصول
	   -------------------------------------------------------- */

	var variantState = { color: '', size: '', colorSlug: '', sizeSlug: '', currentVariation: null, map: null };

	function formatPriceNumber( n ) {
		if ( ! n && n !== 0 ) {
			return '';
		}
		var parts = String( n ).split( '.' );
		parts[0] = parts[0].replace( /\B(?=(\d{3})+(?!\d))/g, '٫' );
		return toFa( parts[0] );
	}

	function paintVariantTargets() {
		var parts = [];

		if ( variantState.color ) {
			parts.push( variantState.color );
		}

		if ( variantState.size ) {
			parts.push( 'سایز ' + variantState.size );
		}

		if ( ! parts.length ) {
			return;
		}

		var text = parts.join( ' · ' );

		Array.prototype.forEach.call(
			document.querySelectorAll( '[data-ard-sticky-variant]' ),
			function ( el ) {
				el.textContent = text;
			}
		);
	}

	/* --------------------------------------------------------
	   واریاسیون‌ها

	   یک منبع حقیقت: نقشهٔ سبکی که ویجت باکس خرید در data-variations
	   می‌گذارد. همهٔ ویجت‌های صفحه (رنگ، سایز، قیمت، نوار چسبان) از همین
	   می‌خوانند تا دو جا دو جور حساب نکنند.
	   -------------------------------------------------------- */

	/**
	 * نقشهٔ واریاسیون‌های صفحه، یک‌بار خوانده و نگه داشته می‌شود.
	 */
	function variationMap() {
		if ( variantState.map ) {
			return variantState.map;
		}

		// باکس خرید اولویت دارد؛ اگر در صفحه نبود، نقشه از ویجت قیمت خوانده می‌شود.
		var box = document.querySelector( '[data-ard-buybox][data-is-variable="true"]' ) ||
			document.querySelector( '[data-ard-price][data-is-variable="true"]' );
		var raw = box ? box.getAttribute( 'data-variations' ) : '';

		try {
			variantState.map = raw ? JSON.parse( raw ) : [];
		} catch ( e ) {
			variantState.map = [];
		}

		return variantState.map;
	}

	/**
	 * ویژگی‌هایی که کاربر تا الان انتخاب کرده: کلید ویژگی → اسلاگ.
	 *
	 * کلید از خود گروه خوانده می‌شود (data-attr)، نه از نام ثابت
	 * attribute_pa_color؛ هر فروشگاهی ویژگی‌اش را جور دیگری نامیده است.
	 */
	function chosenAttributes( skipGroup ) {
		var out = {};

		Array.prototype.forEach.call(
			document.querySelectorAll( '[data-ard-color-group],[data-ard-size-group]' ),
			function ( group ) {
				if ( group === skipGroup ) {
					return;
				}

				var key = group.getAttribute( 'data-attr' );
				var active = group.querySelector( '.is-active' );

				if ( ! key || ! active ) {
					return;
				}

				var slug = active.getAttribute( 'data-color-slug' ) || active.getAttribute( 'data-size-slug' ) || '';

				if ( slug ) {
					out[ key ] = slug;
				}
			}
		);

		return out;
	}

	/**
	 * آیا این واریاسیون با انتخاب‌های داده‌شده می‌خواند؟
	 *
	 * ویژگیِ خالی در واریاسیون یعنی «هر مقداری»، پس رد نمی‌شود.
	 */
	function variationMatches( variation, wanted ) {
		for ( var key in wanted ) {
			if ( ! Object.prototype.hasOwnProperty.call( wanted, key ) ) {
				continue;
			}

			var have = variation.attrs[ key ];

			if ( have && have !== wanted[ key ] ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * باریک‌شدن دوطرفه: با انتخاب یک رنگ، سایزهایی که در آن رنگ موجود نیستند
	 * خط می‌خورند و برعکس.
	 *
	 * بدون این، کاربر سایزی را انتخاب می‌کرد که برای رنگ انتخابی‌اش وجود
	 * نداشت و فقط با خاکستری شدن دکمهٔ خرید می‌فهمید — آن هم بعد از دو کلیک.
	 */
	function narrowVariantOptions() {
		var map = variationMap();

		if ( ! map.length ) {
			return;
		}

		Array.prototype.forEach.call(
			document.querySelectorAll( '[data-ard-color-group],[data-ard-size-group]' ),
			function ( group ) {
				var key = group.getAttribute( 'data-attr' );

				if ( ! key ) {
					return;
				}

				// انتخاب‌های بقیهٔ گروه‌ها، بدون خودِ این گروه.
				var others = chosenAttributes( group );

				Array.prototype.forEach.call(
					group.querySelectorAll( '.ard-prod-color, .ard-prod-size' ),
					function ( btn ) {
						var slug = btn.getAttribute( 'data-color-slug' ) || btn.getAttribute( 'data-size-slug' ) || '';

						if ( ! slug ) {
							return;
						}

						var wanted = {};
						var k;

						for ( k in others ) {
							if ( Object.prototype.hasOwnProperty.call( others, k ) ) {
								wanted[ k ] = others[ k ];
							}
						}

						wanted[ key ] = slug;

						// با انتخاب‌های فعلی می‌خواند؟
						var usable = map.some( function ( v ) {
							return v.stock && v.buy && variationMatches( v, wanted );
						} );

						// اصلاً در هیچ ترکیبی موجود است؟
						var only = {};
						only[ key ] = slug;

						var anywhere = map.some( function ( v ) {
							return v.stock && v.buy && variationMatches( v, only );
						} );

						/*
						 * دو نوع «نمی‌شود»:
						 *
						 *   بن‌بست  — این رنگ/سایز در هیچ ترکیبی موجود نیست.
						 *             خط‌خورده و واقعاً غیرفعال.
						 *   ناسازگار — خودش موجود است، ولی با انتخاب فعلیِ
						 *             آن یکی ویژگی جور نیست. این هم خط‌خورده
						 *             است ولی کلیک‌شدنی می‌ماند: کلیک که شد،
						 *             خودش انتخاب می‌شود و آن یکی ویژگی به
						 *             نزدیک‌ترین گزینهٔ موجود می‌رود.
						 *
						 * بدون این تفکیک، کاربر روی «مشکی + M» گیر می‌کرد و
						 * برای رسیدن به قرمز باید حدس می‌زد که اول باید سایز
						 * را عوض کند.
						 */
						btn.classList.toggle( 'is-disabled', ! usable );
						btn.classList.toggle( 'is-conflict', ! usable && anywhere );
						btn.disabled = ! anywhere;

						if ( usable ) {
							btn.removeAttribute( 'aria-disabled' );
							btn.removeAttribute( 'title' );
						} else {
							btn.setAttribute( 'aria-disabled', anywhere ? 'false' : 'true' );

							if ( anywhere ) {
								btn.setAttribute( 'title', 'با انتخاب فعلی موجود نیست؛ بزنید تا نزدیک‌ترین ترکیب موجود انتخاب شود.' );
							} else {
								btn.setAttribute( 'title', 'ناموجود' );
							}
						}
					}
				);
			}
		);
	}

	/**
	 * واریاسیونی که با انتخاب فعلی می‌خواند.
	 */
	/**
	 * وقتی کاربر گزینه‌ای را می‌زند که با انتخاب فعلی نمی‌خواند، بقیهٔ
	 * ویژگی‌ها به نزدیک‌ترین ترکیبِ موجود منتقل می‌شوند.
	 *
	 * @param {string} key  کلید ویژگی‌ای که کاربر زده.
	 * @param {string} slug مقدارش.
	 */
	function resolveConflict( key, slug ) {
		var map = variationMap();
		var wanted = {};

		wanted[ key ] = slug;

		var target = null;

		map.forEach( function ( v ) {
			if ( target || ! v.stock || ! v.buy || ! variationMatches( v, wanted ) ) {
				return;
			}

			target = v;
		} );

		if ( ! target ) {
			return;
		}

		// بقیهٔ گروه‌ها روی مقدارهای همین واریاسیون تنظیم می‌شوند.
		Array.prototype.forEach.call(
			document.querySelectorAll( '[data-ard-color-group],[data-ard-size-group]' ),
			function ( group ) {
				var groupKey = group.getAttribute( 'data-attr' );

				if ( ! groupKey || groupKey === key || ! target.attrs[ groupKey ] ) {
					return;
				}

				var want = target.attrs[ groupKey ];

				Array.prototype.forEach.call(
					group.querySelectorAll( '.ard-prod-color, .ard-prod-size' ),
					function ( b ) {
						var bs = b.getAttribute( 'data-color-slug' ) || b.getAttribute( 'data-size-slug' ) || '';
						var on = bs === want;

						b.classList.toggle( 'is-active', on );

						if ( ! on ) {
							return;
						}

						// وضعیتِ نمایشی هم باید با انتخاب تازه جلو برود.
						if ( b.getAttribute( 'data-color-name' ) ) {
							variantState.color = b.getAttribute( 'data-color-name' ) || '';
							variantState.colorSlug = bs;

							var t = group.querySelector( '[data-ard-color-target]' );

							if ( t ) {
								t.textContent = variantState.color;
							}
						} else {
							variantState.size = b.getAttribute( 'data-size-label' ) || '';
							variantState.sizeSlug = bs;
						}
					}
				);
			}
		);
	}

	function currentVariation() {
		var map = variationMap();
		var wanted = chosenAttributes( null );
		var match = null;

		map.forEach( function ( v ) {
			if ( match || ! variationMatches( v, wanted ) ) {
				return;
			}

			match = v;
		} );

		return match;
	}

	/**
	 * فیلدهای استاندارد فرم ووکامرس را با انتخاب فعلی پر می‌کند.
	 *
	 * افزونه‌های دیگر (و خود ووکامرس، اگر جاوااسکریپت ما غیرفعال شود) همین
	 * `variation_id` و `attribute_*` را می‌خوانند؛ بدون پر کردنشان، فرم با
	 * واریاسیون اشتباه یا خالی ارسال می‌شد.
	 *
	 * @param {Object|null} match واریاسیون جاری.
	 */
	function syncCartForm( match ) {
		var form = document.querySelector( '.ard-prod-buybox__form' );

		if ( ! form ) {
			return;
		}

		var idField = form.querySelector( '[data-ard-variation-id]' );

		if ( idField ) {
			idField.value = match ? match.id : 0;
		}

		var chosen = chosenAttributes( null );
		var key;

		for ( key in chosen ) {
			if ( ! Object.prototype.hasOwnProperty.call( chosen, key ) ) {
				continue;
			}

			var field = form.querySelector( '[data-ard-variation-attr="' + key + '"]' );

			if ( ! field ) {
				// ویژگی‌ای که سمت سرور پیش‌فرضی نداشت، همین‌جا ساخته می‌شود.
				field = document.createElement( 'input' );
				field.type = 'hidden';
				field.name = key;
				field.setAttribute( 'data-ard-variation-attr', key );
				form.appendChild( field );
			}

			field.value = chosen[ key ];
		}

		/*
		 * jQuery ووکامرس روی همین رویداد می‌نشیند؛ بدون آن، افزونه‌هایی که
		 * قیمت یا موجودی را دنبال می‌کنند از تغییر باخبر نمی‌شوند.
		 */
		if ( window.jQuery && match ) {
			window.jQuery( form ).trigger( 'ardal_variation_changed', [ match ] );
		}
	}

	function syncVariationState() {
		paintVariantTargets();
		narrowVariantOptions();

		if ( document.querySelector( '[data-ard-price][data-is-variable="true"]' ) ) {
			var priceMatch = currentVariation();

			if ( priceMatch ) {
				paintPriceWidgets( priceMatch.price, priceMatch.reg, priceMatch.stock && priceMatch.buy );
			}
		}

		var buyboxes = document.querySelectorAll( '[data-ard-buybox]' );
		Array.prototype.forEach.call( buyboxes, function ( buybox ) {
			if ( 'true' !== buybox.getAttribute( 'data-is-variable' ) ) {
				return;
			}

			var match = currentVariation();

			variantState.currentVariation = match;
			syncCartForm( match );

			var priceMain = buybox.querySelector( '.ard-prod-buybox__price-main' );
			var oldPriceEl = buybox.querySelector( '.ard-prod-buybox__old' );
			var badgeEl = buybox.querySelector( '.ard-prod-buybox__badge' );
			var priceRow = buybox.querySelector( '.ard-prod-buybox__price-row' );
			var btnAdd = buybox.querySelector( '[data-ard-add-to-cart]' );

			var stickyPrice = document.querySelector( '.ard-prod-sticky-bar__price-main' );
			var stickyOldPrice = document.querySelector( '.ard-prod-sticky-bar__old' );

			if ( match ) {
				if ( priceMain ) {
					var unit = priceMain.querySelector( '.ard-prod-buybox__unit' );
					var unitHtml = unit ? unit.outerHTML : '';
					priceMain.innerHTML = formatPriceNumber( match.price ) + ' ' + unitHtml;
				}

				if ( stickyPrice ) {
					var sUnit = stickyPrice.querySelector( '.ard-prod-sticky-bar__unit' );
					var sUnitHtml = sUnit ? sUnit.outerHTML : '';
					stickyPrice.innerHTML = formatPriceNumber( match.price ) + ' ' + sUnitHtml;
				}

				if ( match.off > 0 ) {
					var discountPercent = match.off;
					if ( oldPriceEl ) {
						oldPriceEl.textContent = formatPriceNumber( match.reg );
						oldPriceEl.style.display = '';
					}
					if ( badgeEl ) {
						badgeEl.textContent = toFa( discountPercent ) + '٪';
						badgeEl.style.display = '';
					}
					if ( priceRow ) {
						priceRow.style.display = '';
					}
					if ( stickyOldPrice ) {
						stickyOldPrice.textContent = formatPriceNumber( match.reg );
						stickyOldPrice.style.display = '';
					}
				} else {
					if ( oldPriceEl ) {
						oldPriceEl.style.display = 'none';
					}
					if ( badgeEl ) {
						badgeEl.style.display = 'none';
					}
					if ( stickyOldPrice ) {
						stickyOldPrice.style.display = 'none';
					}
				}

				setAddState( buybox, btnAdd, match.stock && match.buy );
			} else {
				/*
				 * هیچ واریاسیونی با این ترکیب نمی‌خواند (مثلاً رنگی که در آن
				 * سایز ساخته نشده). دکمه باید خاموش باشد، وگرنه کاربر روی
				 * چیزی کلیک می‌کند که وجود ندارد.
				 */
				setAddState( buybox, btnAdd, false );
			}
		} );
	}

	/**
	 * به‌روزرسانی ویجت‌های قیمت با قیمت یک واریاسیون.
	 *
	 * فقط متن‌ها عوض می‌شوند تا واحد پول (نماد، آیکون یا تصویر دلخواه)
	 * دست نخورد.
	 *
	 * @param {number}  price
	 * @param {number}  reg
	 * @param {boolean} available
	 */
	function paintPriceWidgets( price, reg, available ) {
		var off = reg > price && reg > 0 ? Math.round( ( ( reg - price ) / reg ) * 100 ) : 0;

		Array.prototype.forEach.call( document.querySelectorAll( '[data-ard-price][data-is-variable="true"]' ), function ( box ) {
			var num = box.querySelector( '.ard-price__num' );
			var deal = box.querySelector( '.ard-price__deal' );
			var old = box.querySelector( '.ard-price__old' );
			var badge = box.querySelector( '.ard-price__badge' );

			if ( num ) {
				num.textContent = formatPriceNumber( price );
			}

			if ( old ) {
				old.textContent = off > 0 ? formatPriceNumber( reg ) : '';
			}

			if ( badge ) {
				badge.textContent = ( box.getAttribute( 'data-badge' ) || '{p}%' ).replace( '{p}', toFa( off ) );
			}

			if ( deal ) {
				deal.hidden = off < 1;
			}

			box.classList.toggle( 'has-deal', off > 0 );
			box.classList.toggle( 'is-out', ! available );
		} );
	}

	/*
	 * فرم واریاسیون خودِ ووکامرس (وقتی به‌جای ویجت‌های رنگ و سایز اردال
	 * استفاده شده) رویدادهایش را با jQuery می‌فرستد.
	 */
	if ( window.jQuery ) {
		window.jQuery( document ).on( 'found_variation', 'form.variations_form', function ( e, v ) {
			if ( v && '' !== v.display_price ) {
				paintPriceWidgets( parseFloat( v.display_price ), parseFloat( v.display_regular_price ), !! ( v.is_in_stock && v.is_purchasable ) );
			}
		} );
	}

	/**
	 * روشن/خاموش کردن دکمهٔ خرید و کل باکس.
	 *
	 * @param {Element} buybox
	 * @param {Element} btn
	 * @param {boolean} on
	 */
	function setAddState( buybox, btn, on ) {
		if ( buybox ) {
			buybox.classList.toggle( 'is-out', ! on );
		}

		if ( ! btn ) {
			return;
		}

		btn.classList.toggle( 'is-disabled', ! on );

		if ( on ) {
			btn.removeAttribute( 'aria-disabled' );
		} else {
			btn.setAttribute( 'aria-disabled', 'true' );
		}
	}

	function initColorGroup( group ) {
		/*
		 * شنونده روی همهٔ دکمه‌ها می‌نشیند، نه فقط فعال‌ها: با باریک‌شدن
		 * دوطرفه یک دکمه ممکن است بعداً فعال شود و اگر آن موقع شنونده نداشته
		 * باشد، کلیکش بی‌اثر می‌ماند. جلوی کلیکِ غیرفعال را خودِ handler
		 * می‌گیرد.
		 */
		var buttons = Array.prototype.slice.call( group.querySelectorAll( '.ard-prod-color' ) );
		var target = group.querySelector( '[data-ard-color-target]' );
		var active = group.querySelector( '.ard-prod-color.is-active' );

		if ( active ) {
			variantState.color = active.getAttribute( 'data-color-name' ) || '';
			variantState.colorSlug = active.getAttribute( 'data-color-slug' ) || '';
		}

		buttons.forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				// بن‌بست واقعی: هیچ کاری نمی‌شود کرد.
				if ( btn.classList.contains( 'is-disabled' ) && ! btn.classList.contains( 'is-conflict' ) ) {
					return;
				}

				buttons.forEach( function ( c ) {
					c.classList.remove( 'is-active' );
				} );

				btn.classList.add( 'is-active' );
				variantState.color = btn.getAttribute( 'data-color-name' ) || '';
				variantState.colorSlug = btn.getAttribute( 'data-color-slug' ) || '';

				if ( target ) {
					target.textContent = variantState.color;
				}

				if ( btn.classList.contains( 'is-conflict' ) ) {
					resolveConflict( group.getAttribute( 'data-attr' ), variantState.colorSlug );
				}

				syncVariationState();
			} );
		} );

		syncVariationState();
	}

	function initSizeGroup( group ) {
		// توضیحش پای initColorGroup است.
		var buttons = Array.prototype.slice.call( group.querySelectorAll( '.ard-prod-size' ) );
		var active = group.querySelector( '.ard-prod-size.is-active' );

		if ( active ) {
			variantState.size = active.getAttribute( 'data-size-label' ) || '';
			variantState.sizeSlug = active.getAttribute( 'data-size-slug' ) || '';
		}

		buttons.forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				// بن‌بست واقعی؛ توضیحش پای initColorGroup است.
				if ( btn.classList.contains( 'is-disabled' ) && ! btn.classList.contains( 'is-conflict' ) ) {
					return;
				}

				buttons.forEach( function ( b ) {
					b.classList.remove( 'is-active' );
				} );

				btn.classList.add( 'is-active' );
				variantState.size = btn.getAttribute( 'data-size-label' ) || '';
				variantState.sizeSlug = btn.getAttribute( 'data-size-slug' ) || '';

				if ( btn.classList.contains( 'is-conflict' ) ) {
					resolveConflict( group.getAttribute( 'data-attr' ), variantState.sizeSlug );
				}

				syncVariationState();
			} );
		} );

		syncVariationState();
	}

	/* --------------------------------------------------------
	   شمارنده تعداد (+ / -) و دکمه خرید
	   -------------------------------------------------------- */

	function initProductQty( root ) {
		var qtyVal = 1;
		var display = root.querySelector( '[data-ard-qty-display]' );
		var input = root.querySelector( '[data-ard-qty-input]' );
		var btnInc = root.querySelector( '[data-ard-qty-inc]' );
		var btnDec = root.querySelector( '[data-ard-qty-dec]' );
		var btnAdd = root.querySelector( '[data-ard-add-to-cart]' );

		var max = display ? parseInt( display.getAttribute( 'data-max' ), 10 ) : 0;

		if ( ! max || max < 1 ) {
			max = 99;
		}

		function paint() {
			// شمارندهٔ دیدنی فارسی می‌ماند؛ ورودیِ ووکامرس لاتین، چون
			// افزونه‌های دیگر رویش parseInt می‌زنند.
			if ( display ) {
				display.textContent = toFa( qtyVal );
			}

			if ( input && String( qtyVal ) !== input.value ) {
				input.value = qtyVal;

				if ( window.jQuery ) {
					window.jQuery( input ).trigger( 'change' );
				}
			}

			// تعداد باید به دکمهٔ واقعی هم برسد، وگرنه ووکامرس همیشه یکی
			// به سبد اضافه می‌کند هرچقدر هم کاربر عدد را بالا ببرد.
			if ( btnAdd ) {
				btnAdd.setAttribute( 'data-quantity', qtyVal );

				var href = btnAdd.getAttribute( 'href' );

				if ( href && href.indexOf( 'add-to-cart=' ) !== -1 ) {
					href = href.replace( /([?&])quantity=\d+/, '$1quantity=' + qtyVal );

					if ( href.indexOf( 'quantity=' ) === -1 ) {
						href += ( href.indexOf( '?' ) === -1 ? '?' : '&' ) + 'quantity=' + qtyVal;
					}

					btnAdd.setAttribute( 'href', href );
				}
			}
		}

		/*
		 * اگر افزونهٔ دیگری مقدار را مستقیم روی ورودی بنویسد، شمارندهٔ دیدنی
		 * هم باید جلو برود؛ وگرنه کاربر عددی می‌بیند که با سبد نمی‌خواند.
		 */
		if ( input ) {
			input.addEventListener( 'change', function () {
				var outside = parseInt( input.value, 10 );

				if ( outside && outside !== qtyVal ) {
					qtyVal = Math.min( Math.max( 1, outside ), max );
					paint();
				}
			} );
		}

		if ( btnInc ) {
			btnInc.addEventListener( 'click', function () {
				if ( qtyVal < max ) {
					qtyVal++;
					paint();
				}
			} );
		}

		if ( btnDec ) {
			btnDec.addEventListener( 'click', function () {
				if ( qtyVal > 1 ) {
					qtyVal--;
					paint();
				}
			} );
		}

		if ( btnAdd ) {
			/*
			 * افزودن به سبد دست خودِ ووکامرس است.
			 *
			 * دکمه یک submit استاندارد داخل form.cart است و ایجکس کردنش کار
			 * افزونهٔ اختصاصیِ سایت است؛ اگر ما هم این وسط فرم را می‌فرستادیم،
			 * دو درخواست هم‌زمان می‌رفت و کالا دوبار به سبد اضافه می‌شد.
			 *
			 * تنها کاری که اینجا می‌کنیم جلوگیری از ارسالِ بی‌معناست: دکمهٔ
			 * خاموش (ترکیب ناموجود) نباید فرم را بفرستد.
			 */
			btnAdd.addEventListener( 'click', function ( e ) {
				if ( ! btnAdd.classList.contains( 'is-disabled' ) ) {
					return;
				}

				e.preventDefault();
				e.stopPropagation();

				var buybox = root.closest( '[data-ard-buybox]' ) || root;

				if ( 'true' === buybox.getAttribute( 'data-is-variable' ) ) {
					ardToast( 'این ترکیب موجود نیست؛ رنگ یا سایز دیگری را انتخاب کنید.', 'error' );
				}
			} );
		}

		paint();
	}

	/* --------------------------------------------------------
	   تب‌های صفحه محصول
	   -------------------------------------------------------- */

	function initProductTabs( root ) {
		var navBtns = Array.prototype.slice.call( root.querySelectorAll( '.ard-ptabs__btn' ) );
		var panels = Array.prototype.slice.call( root.querySelectorAll( '.ard-ptabs__panel' ) );

		if ( 'sections' === root.getAttribute( 'data-layout' ) ) {
			initTabsAsSections( root, navBtns, panels );

			return;
		}

		navBtns.forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				// کلید کوتاه تب با data-tab-panel مطابقت داده می‌شود، نه با id؛
				// id شامل شناسهٔ یکتای ویجت است و هیچ‌وقت با کلید برابر نمی‌شود.
				var target = btn.getAttribute( 'data-tab-target' );

				navBtns.forEach( function ( b ) {
					var isSelected = b === btn;
					b.classList.toggle( 'is-active', isSelected );
					b.setAttribute( 'aria-selected', isSelected ? 'true' : 'false' );
				} );

				panels.forEach( function ( panel ) {
					var isMatch = panel.getAttribute( 'data-tab-panel' ) === target;

					panel.classList.toggle( 'is-active', isMatch );
					panel.hidden = ! isMatch;
				} );
			} );
		} );
	}

	/**
	 * حالت «همه زیر هم»: نوار عنوان‌ها ناوبری است، نه تب. کلیک به بخش می‌برد و
	 * اسکرول، بخش جاری را در نوار پررنگ می‌کند.
	 */
	function initTabsAsSections( root, navBtns, panels ) {
		function offset() {
			var nav = root.querySelector( '.ard-ptabs__nav' );
			var declared = parseInt( getComputedStyle( root ).getPropertyValue( '--ard-ptabs-offset' ), 10 ) || 0;

			// خودِ نوارِ چسبان هم روی بخش می‌افتد، پس ارتفاعش هم کم می‌شود.
			return declared + ( root.classList.contains( 'ard-ptabs--sticky-nav' ) && nav ? nav.offsetHeight : 0 );
		}

		function paint( key ) {
			navBtns.forEach( function ( b ) {
				var on = b.getAttribute( 'data-tab-target' ) === key;

				b.classList.toggle( 'is-active', on );
				b.setAttribute( 'aria-current', on ? 'true' : 'false' );
			} );
		}

		navBtns.forEach( function ( btn ) {
			btn.addEventListener( 'click', function ( e ) {
				e.preventDefault();

				var key = btn.getAttribute( 'data-tab-target' );
				var panel = root.querySelector( '[data-tab-panel="' + key + '"]' );

				if ( ! panel ) {
					return;
				}

				var top = panel.getBoundingClientRect().top + window.pageYOffset - offset() - 8;

				// وضعیت را همان لحظه می‌زنیم و منتظر رویداد scroll نمی‌مانیم.
				paint( key );
				window.scrollTo( {
					top: Math.max( 0, top ),
					behavior: prefersReducedMotion() ? 'auto' : 'smooth'
				} );
			} );
		} );

		function spy() {
			var line = offset() + 24;
			var current = panels.length ? panels[ 0 ].getAttribute( 'data-tab-panel' ) : '';

			panels.forEach( function ( panel ) {
				if ( panel.getBoundingClientRect().top <= line ) {
					current = panel.getAttribute( 'data-tab-panel' );
				}
			} );

			paint( current );
		}

		window.addEventListener( 'scroll', spy, { passive: true } );
		window.addEventListener( 'resize', spy );
		spy();
	}

	/* --------------------------------------------------------
	   نوار چسبان خرید (Sticky Bar)
	   -------------------------------------------------------- */

	function initProductStickyBar( root ) {
		relocateStickyBar( root );

		var stickyBtn = root.querySelector( '[data-ard-add-to-cart]' );
		if ( stickyBtn ) {
			stickyBtn.addEventListener( 'click', function ( e ) {
				var mainBtn = document.querySelector( '[data-ard-buybox] [data-ard-add-to-cart]' );
				if ( mainBtn ) {
					e.preventDefault();
					e.stopPropagation();
					mainBtn.click();
				}
			} );
		}

		if ( ! root.classList.contains( 'ard-prod-sticky-bar--scroll' ) ) {
			// نوار همیشه دیدنی است، پس انتهای صفحه نباید زیرش گم شود.
			reserveStickySpace( root );

			return;
		}

		// مرجع آستانه: هر کدام از ویجت‌های بالای صفحه که وجود داشته باشد.
		var hero = document.querySelector( '[data-ard-buybox], [data-ard-gallery], .ard-prod-title-block' );

		function checkScroll() {
			var threshold = 350;

			if ( hero ) {
				var rect = hero.getBoundingClientRect();
				threshold = rect.top + window.pageYOffset + ( rect.height * 0.6 );
			}

			root.classList.toggle( 'is-visible', window.pageYOffset > threshold );
		}

		window.addEventListener( 'scroll', checkScroll, { passive: true } );
		window.addEventListener( 'resize', checkScroll );
		checkScroll();
	}

	/**
	 * نوار با `position: fixed` کار می‌کند، ولی هر جدِ دارای transform/filter
	 * بلوک دربرگیرندهٔ جدید می‌سازد و fixed را می‌شکند (انیمیشن‌های المنتور همین
	 * کار را می‌کنند). برای همین کل رَپِر ویجت به body منتقل می‌شود؛ چون خودِ
	 * رَپِر با کلاس‌هایش جابه‌جا می‌شود، استایل‌های `{{WRAPPER}}` المنتور
	 * دست‌نخورده می‌مانند.
	 */
	function relocateStickyBar( root ) {
		// در ویرایشگر جابه‌جا نمی‌کنیم، وگرنه انتخاب و پیش‌نمایش ویجت می‌شکند.
		if ( document.body.classList.contains( 'elementor-editor-active' ) ) {
			return;
		}

		var host = root.closest( '.elementor-widget' ) || root;

		if ( host.parentNode === document.body ) {
			return;
		}

		document.body.appendChild( host );
	}

	/**
	 * فضای پایین صفحه را به اندازهٔ ارتفاع نوار باز می‌کند.
	 */
	function reserveStickySpace( root ) {
		function apply() {
			var h = root.offsetHeight;

			if ( h ) {
				document.body.style.paddingBottom = h + 'px';
			}
		}

		apply();
		window.addEventListener( 'resize', apply );

		if ( window.ResizeObserver ) {
			new window.ResizeObserver( apply ).observe( root );
		}
	}

	/* --------------------------------------------------------
	   راه‌اندازی
	   -------------------------------------------------------- */

	function init( scope ) {
		var root = scope || document;

		// پیام‌های ووکامرس یک بار در هر صفحه به توست تبدیل می‌شوند.
		if ( ! document.body.ardNoticesReady ) {
			document.body.ardNoticesReady = true;
			initWooNotices();
		}

		// شمارنده‌های علاقه‌مندی حتی در صفحه‌ای بدون دکمهٔ قلب هم درست شوند.
		syncWishlistOnce();

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

		Array.prototype.forEach.call( root.querySelectorAll( '[data-ard-matrix]' ), function ( el ) {
			if ( ! el.ardReady ) {
				el.ardReady = true;
				initMatrix( el );
			}
		} );

		// کادر پیش از خودِ شمارنده راه بیفتد، وگرنه شمارندهٔ از قبل تمام‌شده
		// رویدادش را می‌فرستد و هنوز کسی گوش نمی‌دهد.
		Array.prototype.forEach.call( root.querySelectorAll( '[data-ard-cdown]' ), function ( el ) {
			if ( ! el.ardReady ) {
				el.ardReady = true;
				initCountdownBox( el );
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

		// گالری، رنگ/سایز و تعداد هرکدام ویجت جداگانه‌اند، پس هرکدام روی ریشهٔ
		// خودش راه می‌افتد. قبلاً همه به یک بلوک واحد hero وصل بودند و بعد از
		// جداسازی به ویجت، هیچ‌کدام اجرا نمی‌شدند.
		Array.prototype.forEach.call(
			root.querySelectorAll( '[data-ard-wishlist],[data-ard-compare]' ),
			function ( el ) {
				if ( ! el.ardReady ) {
					el.ardReady = true;
					initListButton( el );
				}
			}
		);

		Array.prototype.forEach.call( root.querySelectorAll( '[data-ard-gallery]' ), function ( el ) {
			if ( ! el.ardReady ) {
				el.ardReady = true;
				initProductGallery( el );
			}
		} );

		Array.prototype.forEach.call( root.querySelectorAll( '[data-ard-color-group]' ), function ( el ) {
			if ( ! el.ardReady ) {
				el.ardReady = true;
				initColorGroup( el );
			}
		} );

		Array.prototype.forEach.call( root.querySelectorAll( '[data-ard-size-group]' ), function ( el ) {
			if ( ! el.ardReady ) {
				el.ardReady = true;
				initSizeGroup( el );
			}
		} );

		Array.prototype.forEach.call( root.querySelectorAll( '[data-ard-buybox]' ), function ( el ) {
			if ( ! el.ardReady ) {
				el.ardReady = true;
				initProductQty( el );
			}
		} );

		Array.prototype.forEach.call( root.querySelectorAll( '[data-ard-product-tabs]' ), function ( el ) {
			if ( ! el.ardReady ) {
				el.ardReady = true;
				initProductTabs( el );
			}
		} );

		Array.prototype.forEach.call( root.querySelectorAll( '[data-ard-sticky-bar]' ), function ( el ) {
			if ( ! el.ardReady ) {
				el.ardReady = true;
				initProductStickyBar( el );
			}
		} );
	}

	/*
	 * لینک «رفتن به نظرات» (ویجت‌های امتیاز و تعداد نظر). اول تب نظرات ویجت
	 * تب‌های اردال، بعد تب‌های خود ووکامرس، وگرنه همان #reviews معمولی.
	 */
	document.addEventListener( 'click', function ( e ) {
		var link = e.target.closest ? e.target.closest( '[data-ard-jump]' ) : null;

		if ( ! link ) {
			return;
		}

		var key = link.getAttribute( 'data-ard-jump' );
		var gap = parseInt( link.getAttribute( 'data-offset' ), 10 ) || 0;
		var tab = document.querySelector( '[data-ard-product-tabs] .ard-ptabs__btn[data-tab-target="' + key + '"]' );
		var target = null;

		if ( tab ) {
			var tabsRoot = tab.closest( '[data-ard-product-tabs]' );

			tab.click();
			target = 'sections' === tabsRoot.getAttribute( 'data-layout' )
				? tabsRoot.querySelector( '[data-tab-panel="' + key + '"]' )
				: tabsRoot;
		} else {
			var wcTab = document.querySelector( '#tab-title-' + key + ' a' );

			if ( wcTab ) {
				wcTab.click();
				target = document.querySelector( '.woocommerce-tabs' );
			} else {
				target = document.getElementById( key );
			}
		}

		if ( ! target ) {
			return;
		}

		e.preventDefault();
		window.scrollTo( {
			top: Math.max( 0, target.getBoundingClientRect().top + window.pageYOffset - gap ),
			behavior: prefersReducedMotion() ? 'auto' : 'smooth'
		} );
	} );

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
			'ardal-header',
			'ardal-product-hero',
			'ardal-product-tabs',
			'ardal-product-sticky-bar',
			'ardal-brand-rings',
			'ardal-wishlist-button',
			'ardal-wishlist',
			'ardal-wishlist-counter'
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

