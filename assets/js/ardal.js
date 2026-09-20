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

		/*
		 * کلاس نقطه‌ها از خود عنصر خوانده می‌شود تا کروسل مشترک بماند؛ قبلاً
		 * ثابت و مال «پیشنهاد ویژه» بود و هر ویجت تازه‌ای نقطه‌هایش استایل
		 * غریبه می‌گرفت.
		 */
		var dotClass = ( dotsBox && dotsBox.getAttribute( 'data-ard-dots' ) ) || 'ard-deal__dot';
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
	   تایمر شمارش معکوس
	   -------------------------------------------------------- */

	function initCountdown( root ) {
		var sec = parseInt( root.getAttribute( 'data-seconds' ), 10 ) || 0;
		var hEl = root.querySelector( '[data-ard-clock-h]' );
		var mEl = root.querySelector( '[data-ard-clock-m]' );
		var sEl = root.querySelector( '[data-ard-clock-s]' );

		if ( sec <= 0 || ( ! hEl && ! mEl && ! sEl ) ) {
			return;
		}

		function render() {
			var h = Math.floor( sec / 3600 );
			var m = Math.floor( ( sec % 3600 ) / 60 );
			var s = sec % 60;

			if ( hEl ) {
				hEl.textContent = toFa( pad( h ) );
			}
			if ( mEl ) {
				mEl.textContent = toFa( pad( m ) );
			}
			if ( sEl ) {
				sEl.textContent = toFa( pad( s ) );
			}
		}

		render();

		var timer = window.setInterval( function () {
			if ( sec > 0 ) {
				sec--;
				render();
			} else {
				window.clearInterval( timer );
			}
		}, 1000 );
	}

	/* --------------------------------------------------------
	   علاقه‌مندی و مقایسه
	   -------------------------------------------------------- */

	var STORE = {
		wishlist: 'ardal_wishlist',
		compare: 'ardal_compare'
	};

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
			return [];
		}
	}

	function listSet( kind, ids ) {
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

	function listToggle( kind, id ) {
		var ids = listGet( kind );
		var at = ids.indexOf( String( id ) );

		if ( at === -1 ) {
			ids.push( String( id ) );
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
				el.hidden = count < 1;
			}
		);
	}

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
			btn.classList.toggle( 'is-active', on );
			btn.setAttribute( 'aria-pressed', on ? 'true' : 'false' );

			var label = btn.getAttribute( on ? 'data-label-on' : 'data-label-off' );

			if ( label ) {
				btn.setAttribute( 'aria-label', label );
				btn.setAttribute( 'title', label );

				// دکمه‌های متنی (مثل لینک علاقه‌مندی در باکس خرید) خودِ متن را
				// هم عوض می‌کنند، نه فقط aria-label را.
				if ( btn.hasAttribute( 'data-ard-list-text' ) ) {
					btn.textContent = label;
				}
			}
		}

		paint( listHas( kind, id ) );

		btn.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			paint( listToggle( kind, id ) );
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

		function step() {
			return Math.max( 60, track.clientWidth * 0.8 );
		}

		function paintArrows() {
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

	// رنگ و سایز حالا ویجت‌های جدا هستند، پس انتخاب کاربر در یک وضعیت مشترکِ
	// سطح صفحه نگه داشته می‌شود تا نوار چسبان و هر مصرف‌کنندهٔ دیگری ببیندش.
	var variantState = { color: '', size: '' };

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

	function initColorGroup( group ) {
		var buttons = Array.prototype.slice.call( group.querySelectorAll( '.ard-prod-color:not(.is-disabled)' ) );
		var target = group.querySelector( '[data-ard-color-target]' );
		var active = group.querySelector( '.ard-prod-color.is-active' );

		if ( active ) {
			variantState.color = active.getAttribute( 'data-color-name' ) || '';
		}

		buttons.forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				buttons.forEach( function ( c ) {
					c.classList.remove( 'is-active' );
				} );

				btn.classList.add( 'is-active' );
				variantState.color = btn.getAttribute( 'data-color-name' ) || '';

				if ( target ) {
					target.textContent = variantState.color;
				}

				paintVariantTargets();
			} );
		} );

		paintVariantTargets();
	}

	function initSizeGroup( group ) {
		var buttons = Array.prototype.slice.call( group.querySelectorAll( '.ard-prod-size:not(.is-disabled)' ) );
		var active = group.querySelector( '.ard-prod-size.is-active' );

		// مارک‌آپ `data-size-label` می‌دهد؛ قبلاً اینجا `data-size-val` خوانده
		// می‌شد و برای همین سایز انتخاب‌شده همیشه خالی می‌ماند.
		if ( active ) {
			variantState.size = active.getAttribute( 'data-size-label' ) || '';
		}

		buttons.forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				buttons.forEach( function ( b ) {
					b.classList.remove( 'is-active' );
				} );

				btn.classList.add( 'is-active' );
				variantState.size = btn.getAttribute( 'data-size-label' ) || '';
				paintVariantTargets();
			} );
		} );

		paintVariantTargets();
	}

	/* --------------------------------------------------------
	   شمارنده تعداد (+ / -) و دکمه خرید
	   -------------------------------------------------------- */

	function initProductQty( root ) {
		var qtyVal = 1;
		var display = root.querySelector( '[data-ard-qty-display]' );
		var btnInc = root.querySelector( '[data-ard-qty-inc]' );
		var btnDec = root.querySelector( '[data-ard-qty-dec]' );
		var btnAdd = root.querySelector( '[data-ard-add-to-cart]' );

		var max = display ? parseInt( display.getAttribute( 'data-max' ), 10 ) : 0;

		if ( ! max || max < 1 ) {
			max = 99;
		}

		function paint() {
			if ( display ) {
				display.textContent = toFa( qtyVal );
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

		// وقتی دکمه واقعاً دکمهٔ ایجکس ووکامرس است، بازخورد را خود ووکامرس
		// می‌دهد و دست‌کاری متن باعث دوباره‌کاری می‌شود.
		if ( btnAdd && ! btnAdd.classList.contains( 'ajax_add_to_cart' ) ) {
			btnAdd.addEventListener( 'click', function () {
				var original = btnAdd.textContent;

				btnAdd.textContent = 'به سبد خرید اضافه شد ✓';
				window.setTimeout( function () {
					btnAdd.textContent = original;
				}, 1800 );
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
			'ardal-product-sticky-bar'
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

