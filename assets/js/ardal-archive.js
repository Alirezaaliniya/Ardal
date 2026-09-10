/**
 * Ardal — تعامل‌های آرشیو فروشگاه.
 *
 * هیچ درخواستی به سرور فرستاده نمی‌شود؛ فیلترها با ارسال همان فرم GET اعمال
 * می‌شوند و همه چیز در آدرس صفحه می‌نشیند. این فایل فقط کارهای سمت مرورگر را
 * انجام می‌دهد: کشو و شیت، اسلایدر قیمت، شمارنده‌ها و ریل دسته‌ها.
 */
( function () {
	'use strict';

	/**
	 * تبدیل عدد به ارقام فارسی، با عقب‌نشینی امن.
	 */
	function toFa( value ) {
		try {
			return Number( value ).toLocaleString( 'fa-IR' );
		} catch ( e ) {
			return String( value );
		}
	}

	/* ==================================================================
	   کشوی فیلتر و شیت مرتب‌سازی
	   ================================================================== */

	function setPanel( name ) {
		var body = document.body;

		body.classList.toggle( 'ard-arch-filters', 'filters' === name );
		body.classList.toggle( 'ard-arch-sort', 'sort' === name );
		body.classList.toggle( 'ard-arch-locked', !! name );

		Array.prototype.forEach.call(
			document.querySelectorAll( '[data-ard-arch-open]' ),
			function ( button ) {
				var mine = button.getAttribute( 'data-ard-arch-open' );

				button.setAttribute( 'aria-expanded', mine === name ? 'true' : 'false' );
			}
		);
	}

	document.addEventListener( 'click', function ( event ) {
		var open = event.target.closest( '[data-ard-arch-open]' );

		if ( open ) {
			event.preventDefault();
			setPanel( open.getAttribute( 'data-ard-arch-open' ) );

			return;
		}

		if ( event.target.closest( '[data-ard-arch-close]' ) ) {
			event.preventDefault();
			setPanel( '' );
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key ) {
			setPanel( '' );
		}
	} );

	// با بزرگ شدن صفحه، کشو باید بسته شود تا چیدمان دسکتاپ درست بماند.
	if ( window.matchMedia ) {
		var wide = window.matchMedia( '(min-width: 1025px)' );

		var onWide = function ( event ) {
			if ( event.matches ) {
				setPanel( '' );
			}
		};

		if ( wide.addEventListener ) {
			wide.addEventListener( 'change', onWide );
		} else if ( wide.addListener ) {
			wide.addListener( onWide );
		}
	}

	/* ==================================================================
	   فرم فیلتر
	   ================================================================== */

	/**
	 * اسلایدر دوسر قیمت.
	 */
	function initRange( range, form ) {
		var minInput = range.querySelector( '[data-ard-range-min]' );
		var maxInput = range.querySelector( '[data-ard-range-max]' );
		var fill = range.querySelector( '[data-ard-range-fill]' );

		if ( ! minInput || ! maxInput || ! fill ) {
			return;
		}

		var box = range.closest( '.ard-fil__body' ) || range.parentElement;
		var outMin = box.querySelector( '[data-ard-range-out="min"]' );
		var outMax = box.querySelector( '[data-ard-range-out="max"]' );

		var bound = {
			min: parseInt( range.getAttribute( 'data-bound-min' ), 10 ) || 0,
			max: parseInt( range.getAttribute( 'data-bound-max' ), 10 ) || 0
		};

		var step = parseInt( minInput.getAttribute( 'step' ), 10 ) || 1;

		function sync() {
			var low = parseInt( minInput.value, 10 );
			var high = parseInt( maxInput.value, 10 );

			// دو دسته نباید از هم رد شوند؛ هرکدام دست کاربر است جا می‌ماند.
			if ( low > high - step ) {
				if ( document.activeElement === minInput ) {
					low = Math.max( bound.min, high - step );
					minInput.value = low;
				} else {
					high = Math.min( bound.max, low + step );
					maxInput.value = high;
				}
			}

			var span = Math.max( 1, bound.max - bound.min );

			// در چیدمان راست‌چین، ابتدای نوار سمت راست است.
			fill.style.right = ( ( low - bound.min ) / span * 100 ) + '%';
			fill.style.left = ( ( bound.max - high ) / span * 100 ) + '%';

			if ( outMin ) {
				outMin.textContent = toFa( low );
			}

			if ( outMax ) {
				outMax.textContent = toFa( high );
			}
		}

		minInput.addEventListener( 'input', sync );
		maxInput.addEventListener( 'input', sync );
		sync();

		if ( form ) {
			// اگر بازه دست‌نخورده است، اصلاً به آدرس اضافه نشود.
			form.addEventListener( 'submit', function () {
				var untouched = parseInt( minInput.value, 10 ) === bound.min
					&& parseInt( maxInput.value, 10 ) === bound.max;

				minInput.disabled = untouched;
				maxInput.disabled = untouched;
			} );
		}
	}

	/**
	 * جست‌وجوی درون‌باکسی روی گزینه‌ها.
	 */
	function initFind( input ) {
		var list = ( input.closest( '.ard-fil__body' ) || document ).querySelector( '.ard-fil__opts' );

		if ( ! list ) {
			return;
		}

		input.addEventListener( 'input', function () {
			var needle = input.value.trim().toLowerCase();

			Array.prototype.forEach.call( list.children, function ( li ) {
				var label = li.querySelector( '[data-ard-fil-text]' );
				var text = label ? label.getAttribute( 'data-ard-fil-text' ).toLowerCase() : '';

				li.hidden = '' !== needle && -1 === text.indexOf( needle );
			} );
		} );

		// اینتر داخل این فیلد نباید فرم را بفرستد؛ فقط فهرست را غربال می‌کند.
		input.addEventListener( 'keydown', function ( event ) {
			if ( 'Enter' === event.key ) {
				event.preventDefault();
			}
		} );
	}

	/**
	 * ارسال فرم با اجرای هندلرهای submit (پاکسازی فیلدهای خالی).
	 */
	function submitForm( form ) {
		if ( form.requestSubmit ) {
			form.requestSubmit();

			return;
		}

		var fake = document.createEvent( 'Event' );

		fake.initEvent( 'submit', true, true );

		if ( form.dispatchEvent( fake ) ) {
			form.submit();
		}
	}

	function initFilters( root ) {
		var form = root.querySelector( '[data-ard-fil-form]' );

		if ( ! form ) {
			return;
		}

		var auto = '1' === root.getAttribute( 'data-ard-auto' );
		var actions = root.querySelector( '[data-ard-fil-actions]' );
		var counter = root.querySelector( '[data-ard-fil-count]' );

		Array.prototype.forEach.call( root.querySelectorAll( '[data-ard-range]' ), function ( range ) {
			initRange( range, form );
		} );

		Array.prototype.forEach.call( root.querySelectorAll( '[data-ard-fil-find]' ), initFind );

		// وضعیت اولیه تیک‌ها، تا «چند تا عوض شده» را بشود شمرد.
		var boxes = form.querySelectorAll( 'input[type="checkbox"]' );
		var initial = [];

		Array.prototype.forEach.call( boxes, function ( input, i ) {
			initial[ i ] = input.checked;
		} );

		function refresh() {
			var changed = 0;

			Array.prototype.forEach.call( boxes, function ( input, i ) {
				if ( input.checked !== initial[ i ] ) {
					changed++;
				}

				// حالت دیداری «انتخاب‌شده» باید بی‌درنگ دیده شود.
				var label = input.closest( '.ard-fil__opt' );

				if ( label ) {
					label.classList.toggle( 'is-on', input.checked );
				}
			} );

			// شمارنده کنار عنوان هر آکاردئون.
			Array.prototype.forEach.call( root.querySelectorAll( '.ard-fil__sec' ), function ( section ) {
				var pill = section.querySelector( '[data-ard-fil-pill]' );

				if ( ! pill ) {
					return;
				}

				var on = section.querySelectorAll( 'input[type="checkbox"]:checked' ).length;

				pill.textContent = on ? toFa( on ) : '';
				pill.hidden = ! on;
			} );

			return changed;
		}

		function markDirty() {
			var changed = refresh();

			if ( auto ) {
				submitForm( form );

				return;
			}

			if ( actions ) {
				actions.classList.add( 'is-dirty' );
			}

			if ( counter ) {
				counter.hidden = ! changed;
				counter.textContent = changed ? toFa( changed ) : '';
			}
		}

		// change روی تیک‌ها بی‌درنگ و روی اسلایدر بعد از رها شدن شلیک می‌شود؛
		// در حالت خودکار دقیقاً همین رفتار لازم است.
		form.addEventListener( 'change', markDirty );

		// در حالت دستی، تایپ و کشیدن هم باید بی‌درنگ «اعمال نشده» را نشان دهد.
		form.addEventListener( 'input', function ( event ) {
			var type = event.target.type;

			if ( ! auto && ( 'range' === type || 'search' === type || 'text' === type ) ) {
				markDirty();
			}
		} );

		// نوار هشدار بعد از ارسال دیگر معنایی ندارد.
		form.addEventListener( 'submit', function () {
			if ( actions ) {
				actions.classList.remove( 'is-dirty' );
			}

			// فیلدهای خالی نباید به آدرس اضافه شوند.
			Array.prototype.forEach.call(
				form.querySelectorAll( 'input[type="search"], input[type="text"]' ),
				function ( input ) {
					if ( '' === input.value.trim() || input.hasAttribute( 'data-ard-fil-find' ) ) {
						input.disabled = true;
					}
				}
			);
		} );

		refresh();
	}

	/* ==================================================================
	   ریل دسته‌بندی
	   ================================================================== */

	function initRail( track ) {
		var box = track.closest( '.ard-rail' ) || track.parentElement;

		Array.prototype.forEach.call( box.querySelectorAll( '[data-ard-rail]' ), function ( button ) {
			button.addEventListener( 'click', function () {
				var dir = parseInt( button.getAttribute( 'data-ard-rail' ), 10 ) || 1;

				track.scrollBy( {
					left: dir * Math.max( 200, track.clientWidth * 0.7 ),
					behavior: 'smooth'
				} );
			} );
		} );

		/*
		 * تا وقتی همه دسته‌ها جا می‌شوند ریل وسط‌چین می‌ماند؛ به‌محض سرریز باید از
		 * ابتدا بچیند، وگرنه بخش اولِ بیرون‌زده با اسکرول هم در دسترس نیست.
		 */
		function measure() {
			track.classList.toggle( 'is-scrollable', track.scrollWidth - track.clientWidth > 2 );
		}

		measure();
		window.addEventListener( 'resize', measure );

		if ( window.ResizeObserver ) {
			new window.ResizeObserver( measure ).observe( track );
		}

		/* کشیدن با ماوس؛ در لمسی، اسکرول خود مرورگر کافی است. */
		var drag = null;

		function stop() {
			if ( ! drag ) {
				return;
			}

			if ( track.releasePointerCapture && track.hasPointerCapture && track.hasPointerCapture( drag.id ) ) {
				track.releasePointerCapture( drag.id );
			}

			track.classList.remove( 'is-dragging' );

			// پرچم می‌ماند تا کلیکِ بعدِ کشیدن، لینک را باز نکند.
			drag = drag.moved ? { moved: true, done: true } : null;
		}

		track.addEventListener( 'pointerdown', function ( event ) {
			if ( 'touch' === event.pointerType || ( event.button && 0 !== event.button ) ) {
				return;
			}

			if ( track.scrollWidth - track.clientWidth <= 2 ) {
				return;
			}

			drag = { id: event.pointerId, x: event.clientX, left: track.scrollLeft, moved: false };

			track.classList.add( 'is-dragging' );

			if ( track.setPointerCapture ) {
				track.setPointerCapture( event.pointerId );
			}
		} );

		track.addEventListener( 'pointermove', function ( event ) {
			if ( ! drag || drag.done ) {
				return;
			}

			var delta = event.clientX - drag.x;

			if ( ! drag.moved && Math.abs( delta ) < 4 ) {
				return;
			}

			drag.moved = true;
			track.scrollLeft = drag.left - delta;
			event.preventDefault();
		} );

		[ 'pointerup', 'pointercancel' ].forEach( function ( name ) {
			window.addEventListener( name, stop );
		} );

		track.addEventListener( 'dragstart', function ( event ) {
			if ( drag ) {
				event.preventDefault();
			}
		} );

		track.addEventListener( 'click', function ( event ) {
			if ( drag && drag.moved ) {
				event.preventDefault();
				event.stopPropagation();
			}

			drag = null;
		}, true );
	}

	/* ==================================================================
	   منوی کشویی مرتب‌سازی
	   ================================================================== */

	function initSortSelect( select ) {
		select.addEventListener( 'change', function () {
			if ( select.value ) {
				window.location.href = select.value;
			}
		} );
	}

	/* ==================================================================
	   توضیحات جمع‌شونده
	   ================================================================== */

	function initDesc( root ) {
		var body = root.querySelector( '[data-ard-desc-body]' );
		var toggle = root.querySelector( '[data-ard-desc-toggle]' );

		if ( ! body || ! toggle ) {
			return;
		}

		var limit = parseInt( window.getComputedStyle( body ).getPropertyValue( '--ard-desc-h' ), 10 ) || 180;

		// متن کوتاه‌تر از سقف اصلاً نیازی به دکمه ندارد.
		if ( body.scrollHeight <= limit + 8 ) {
			return;
		}

		body.classList.add( 'is-clamped' );
		toggle.hidden = false;

		toggle.addEventListener( 'click', function () {
			var open = body.classList.toggle( 'is-clamped' );

			root.classList.toggle( 'is-open', ! open );

			var label = toggle.getAttribute( open ? 'data-more' : 'data-less' );
			var text = toggle.firstChild;

			if ( label && text && 3 === text.nodeType ) {
				text.nodeValue = label;
			}
		} );
	}

	/* ==================================================================
	   بارگذاری بدون رفرش (ایجکس)

	   به‌جای ساختن یک اندپوینت اختصاصی، همان آدرس مقصد گرفته می‌شود و فقط
	   ناحیه‌های نشان‌دار از پاسخ جایگزین می‌شوند. دلیلش این است که سرور همان
	   صفحهٔ واقعی را با کوئری اصلی رندر می‌کند، پس شمارش نتایج، صفحه‌بندی،
	   مرتب‌سازی و حتی ویجت‌های غیراردالی مثل «لوپ گرید» المنتور بدون یک خط کد
	   اضافه درست درمی‌آیند و هیچ منطقی دو جا تکرار نمی‌شود.
	   ================================================================== */

	var ajaxOn = false;
	var ajaxBusy = false;
	var ajaxScroll = false;
	var extraSelectors = [];

	var AJAX_LINKS = [
		'.ard-tool__sort',
		'.ard-sheet__list a',
		'.ard-chips__item',
		'.ard-chips__clear',
		'.ard-fil__clear',
		'.ard-pager a.page-numbers',
		'.ard-arch-empty__btn',
		'.ard-rail__item > a',
		// صفحه‌بندی خود المنتور (لوپ گرید و آرشیو پست‌ها).
		'.elementor-pagination a'
	].join( ',' );

	/**
	 * ناحیه‌هایی که باید تازه شوند: ویجت‌های اردال به‌علاوهٔ سلکتورهای دلخواه.
	 */
	function regionRoots( scope ) {
		var sels = [ '[data-ard-region]' ].concat( extraSelectors );
		var found = [];

		sels.forEach( function ( sel ) {
			Array.prototype.forEach.call( scope.querySelectorAll( sel ), function ( el ) {
				var root = el.closest ? el.closest( '.elementor-element[data-id]' ) : null;
				var node = root || el;

				if ( -1 === found.indexOf( node ) ) {
					found.push( node );
				}
			} );
		} );

		return found;
	}

	/**
	 * کلید تطبیق ناحیهٔ قدیمی با تازه. شناسهٔ المنتور یکتا و پایدار است؛ اگر
	 * نبود، نام ناحیه جایش می‌نشیند.
	 */
	function regionKey( node, index ) {
		var id = node.getAttribute( 'data-id' );

		if ( id ) {
			return 'id:' + id;
		}

		var region = node.getAttribute( 'data-ard-region' );

		return region ? 'region:' + region : 'index:' + index;
	}

	/**
	 * چه چیزی داخل این ناحیه عوض شود.
	 *
	 * ستون فیلتر استثناست: فقط فرمش عوض می‌شود نه کل کشو، وگرنه کشوی باز موبایل
	 * وسط کار بسته می‌شد.
	 */
	function swapTarget( node ) {
		var form = node.querySelector( '[data-ard-region="filters"]' );

		if ( form ) {
			return form;
		}

		return node.querySelector( '.elementor-widget-container' ) || node;
	}

	/**
	 * جای‌گذاری محتوای تازه، با نگه‌داشتن وضعیت آکاردئون‌های باز فرم.
	 */
	/**
	 * برداشتن نشانهٔ «راه‌اندازی شده».
	 *
	 * ریشهٔ ویجت سر جایش می‌ماند و فقط محتوایش عوض می‌شود، پس اسکریپت اصلی اردال
	 * آن را «قبلاً راه‌اندازی‌شده» می‌بیند و تب‌های شبکهٔ محصولات بعد از اولین
	 * بارگذاری ایجکسی از کار می‌افتادند.
	 */
	function clearReady( node ) {
		node.ardReady = false;
		node.ardArchReady = false;

		Array.prototype.forEach.call( node.querySelectorAll( '*' ), function ( el ) {
			if ( el.ardReady || el.ardArchReady ) {
				el.ardReady = false;
				el.ardArchReady = false;
			}
		} );
	}

	function swapNode( oldNode, newNode ) {
		if ( 'FORM' !== oldNode.tagName ) {
			oldNode.innerHTML = newNode.innerHTML;

			return;
		}

		/*
		 * فرم فیلتر با کل عنصر عوض می‌شود نه با innerHTML: شنونده‌های رویداد روی
		 * خودِ فرم نشسته‌اند و با جایگزینی innerHTML سر جایشان می‌ماندند و بار
		 * دوم دوتایی اجرا می‌شدند.
		 */
		var open = [];
		var scroll = oldNode.scrollTop;

		Array.prototype.forEach.call( oldNode.querySelectorAll( 'details' ), function ( d, i ) {
			open[ i ] = d.open;
		} );

		var fresh = document.importNode( newNode, true );

		oldNode.replaceWith( fresh );

		// آکاردئونی که کاربر خودش باز کرده بود، باز می‌ماند.
		Array.prototype.forEach.call( fresh.querySelectorAll( 'details' ), function ( d, i ) {
			if ( open[ i ] ) {
				d.open = true;
			}
		} );

		fresh.scrollTop = scroll;

		// ریشهٔ ستون فیلتر عوض نشده، پس نشانهٔ «راه‌اندازی شده» باید برداشته شود
		// تا init دوباره روی فرم تازه اجرا شود.
		var root = fresh.closest( '[data-ard-filters]' );

		if ( root ) {
			root.ardArchReady = false;
		}
	}

	/**
	 * دوباره راه‌اندازی: هم کنترل‌های خودمان، هم هندلرهای ویجت‌های المنتور که
	 * محتوایشان عوض شده (صفحه‌بندی و «بارگذاری بیشتر» لوپ گرید).
	 */
	function reinitAjax( nodes ) {
		init();

		if ( ! window.elementorFrontend || ! window.elementorFrontend.elementsHandler || ! window.jQuery ) {
			return;
		}

		nodes.forEach( function ( node ) {
			try {
				window.elementorFrontend.elementsHandler.runReadyTrigger( window.jQuery( node ) );
			} catch ( e ) {
				// هندلر یک ویجت نباید بقیهٔ صفحه را زمین بزند.
			}
		} );
	}

	function setBusy( nodes, on ) {
		document.body.classList.toggle( 'ard-arch-busy', on );

		nodes.forEach( function ( node ) {
			node.classList.toggle( 'is-ard-busy', on );

			if ( on ) {
				node.setAttribute( 'aria-busy', 'true' );
			} else {
				node.removeAttribute( 'aria-busy' );
			}
		} );
	}

	function reduceMotionAjax() {
		return window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	}

	/**
	 * اگر بالای فهرست از دید کاربر بیرون رفته، برگردانش.
	 */
	function scrollToResults() {
		if ( ! ajaxScroll ) {
			return;
		}

		var first = document.querySelector( '[data-ard-region="products"]' )
			|| document.querySelector( '[data-ard-region="toolbar"]' );

		if ( ! first ) {
			return;
		}

		var top = first.getBoundingClientRect().top;

		if ( top < 0 || top > window.innerHeight ) {
			window.scrollTo( {
				top: window.pageYOffset + top - 20,
				behavior: reduceMotionAjax() ? 'auto' : 'smooth'
			} );
		}
	}

	/**
	 * رفتن به یک آدرس بدون رفرش. هر شکستی به بارگذاری معمولی برمی‌گردد، پس
	 * بدترین حالتِ ایجکس همان رفتار بدون ایجکس است.
	 *
	 * @param {string}  url
	 * @param {boolean} push آیا آدرس به تاریخچه اضافه شود (در popstate نه).
	 */
	function ajaxGo( url, push ) {
		if ( ajaxBusy ) {
			return;
		}

		var nodes = regionRoots( document );

		if ( ! nodes.length ) {
			window.location.href = url;

			return;
		}

		ajaxBusy = true;
		setBusy( nodes, true );

		window.fetch( url, {
			credentials: 'same-origin',
			headers: { 'X-Requested-With': 'XMLHttpRequest' }
		} ).then( function ( res ) {
			if ( ! res.ok ) {
				throw new Error( 'bad status' );
			}

			return res.text();
		} ).then( function ( html ) {
			var doc = new DOMParser().parseFromString( html, 'text/html' );
			var fresh = regionRoots( doc );
			var used = [];
			var swapped = [];

			nodes.forEach( function ( node, i ) {
				var key = regionKey( node, i );
				var match = null;

				fresh.forEach( function ( candidate, j ) {
					if ( match || -1 !== used.indexOf( j ) ) {
						return;
					}

					if ( regionKey( candidate, j ) === key ) {
						match = candidate;
						used.push( j );
					}
				} );

				// تطبیق بر پایهٔ جایگاه، برای صفحه‌هایی که شناسهٔ المنتور ندارند.
				if ( ! match && fresh[ i ] && -1 === used.indexOf( i ) ) {
					match = fresh[ i ];
					used.push( i );
				}

				if ( ! match ) {
					return;
				}

				var target = swapTarget( node );
				var source = swapTarget( match );

				if ( target && source ) {
					swapNode( target, source );
					clearReady( node );
					swapped.push( node );
				}
			} );

			var title = doc.querySelector( 'title' );

			if ( title ) {
				document.title = title.textContent;
			}

			if ( false !== push ) {
				window.history.pushState( { ardArchive: true }, '', url );
			}

			setBusy( nodes, false );
			ajaxBusy = false;
			reinitAjax( swapped );
			scrollToResults();

			document.dispatchEvent( new CustomEvent( 'ardal:archive-updated', { detail: { url: url } } ) );
		} ).catch( function () {
			// شبکه یا پاسخ خراب: همان بارگذاری معمولی، تا کاربر گیر نکند.
			window.location.href = url;
		} );
	}

	/**
	 * آدرسی که با ایجکس قابل رفتن است: همین صفحه، فقط با پارامترهای دیگر.
	 * لینک ریل دسته‌بندی در حالت «رفتن به آرشیو ترم» مسیر دیگری دارد، پس
	 * خودبه‌خود از این تور بیرون می‌ماند و مثل قبل صفحه را عوض می‌کند.
	 */
	function sameDocument( url ) {
		return url.origin === window.location.origin && url.pathname === window.location.pathname;
	}

	function initAjax() {
		if ( ajaxOn || ! window.fetch || ! window.history || ! window.history.pushState || ! window.DOMParser ) {
			return;
		}

		if ( ! document.querySelector( '[data-ard-ajax]' ) ) {
			return;
		}

		ajaxOn = true;

		Array.prototype.forEach.call( document.querySelectorAll( '[data-ard-ajax-regions]' ), function ( el ) {
			el.getAttribute( 'data-ard-ajax-regions' ).split( ',' ).forEach( function ( sel ) {
				sel = sel.trim();

				if ( sel && -1 === extraSelectors.indexOf( sel ) ) {
					extraSelectors.push( sel );
				}
			} );
		} );

		ajaxScroll = !! document.querySelector( '[data-ard-ajax-scroll]' );

		document.addEventListener( 'click', function ( event ) {
			if ( event.defaultPrevented || event.metaKey || event.ctrlKey || event.shiftKey || event.button ) {
				return;
			}

			var link = event.target.closest( AJAX_LINKS );

			if ( ! link || ! link.href || link.target ) {
				return;
			}

			var url = new URL( link.href, window.location.href );

			if ( ! sameDocument( url ) ) {
				return;
			}

			event.preventDefault();

			// شیت مرتب‌سازی موبایل بعد از انتخاب باید بسته شود.
			setPanel( '' );
			ajaxGo( url.href, true );
		} );

		document.addEventListener( 'submit', function ( event ) {
			var form = event.target;

			if ( ! form.matches || ! form.matches( '[data-ard-fil-form]' ) ) {
				return;
			}

			var url = new URL( form.getAttribute( 'action' ) || window.location.href, window.location.href );

			if ( ! sameDocument( url ) ) {
				return;
			}

			/*
			 * هندلر خود فرم پیش از این یکی اجرا شده و فیلدهای خالی را غیرفعال
			 * کرده، پس FormData همان چیزی را می‌دهد که باید در آدرس بنشیند.
			 * بعدش دوباره فعالشان می‌کنیم تا فرم قابل استفاده بماند.
			 */
			var disabled = form.querySelectorAll( '[disabled]' );

			url.search = new URLSearchParams( new FormData( form ) ).toString();

			event.preventDefault();

			Array.prototype.forEach.call( disabled, function ( input ) {
				input.disabled = false;
			} );

			setPanel( '' );
			ajaxGo( url.href, true );
		} );

		window.addEventListener( 'popstate', function () {
			ajaxGo( window.location.href, false );
		} );
	}

	/* ==================================================================
	   راه‌اندازی
	   ================================================================== */

	function each( selector, fn ) {
		Array.prototype.forEach.call( document.querySelectorAll( selector ), function ( el ) {
			if ( el.ardArchReady ) {
				return;
			}

			el.ardArchReady = true;
			fn( el );
		} );
	}

	function init() {
		each( '[data-ard-filters]', initFilters );
		each( '[data-ard-rail-track]', initRail );
		each( '[data-ard-sort-select]', initSortSelect );
		each( '[data-ard-desc]', initDesc );
		initAjax();
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}

	// المنتور محتوای ویجت را در ویرایشگر دوباره می‌سازد.
	if ( window.jQuery ) {
		window.jQuery( window ).on( 'elementor/frontend/init', function () {
			if ( window.elementorFrontend && window.elementorFrontend.hooks ) {
				window.elementorFrontend.hooks.addAction( 'frontend/element_ready/global', init );
			}
		} );
	}
} )();
