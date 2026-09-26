/**
 * فهرست ناحیه‌های صفحه: اسکرول نرم به هر بخش و فعال‌شدن خودکار آیتم.
 *
 * بدون این فایل هم فهرست کار می‌کند (لینک‌های #id کار خودشان را می‌کنند)؛ این
 * اسکریپت دو چیز اضافه می‌کند: جبران ارتفاع هدر چسبان هنگام پرش، و مشخص کردن
 * اینکه کاربر همین حالا داخل کدام ناحیه است.
 *
 * چون فهرست ممکن است با المنتور دوباره ساخته شود (ویرایشگر، بارگذاری تنبل)،
 * هیچ چیزی به تک‌تک عنصرها bind نمی‌شود: کلیک واگذار شده و فهرست ناوها با
 * MutationObserver تازه می‌ماند.
 */
( function () {
	'use strict';

	var boxes = [];
	var frame = null;
	var rescan = null;

	/* ------------------------------------------------------------------ */
	/* کمکی                                                                */
	/* ------------------------------------------------------------------ */

	/**
	 * شناسهٔ ناحیه‌ای که لینک به آن اشاره دارد، فقط اگر لینکِ همین صفحه باشد.
	 *
	 * @param {HTMLAnchorElement} link
	 * @return {string}
	 */
	function anchorId( link ) {
		var href = link.getAttribute( 'href' ) || '';
		var at = href.indexOf( '#' );

		if ( at < 0 ) {
			return '';
		}

		// «#» وسط نشانی فقط وقتی مقصدش همین صفحه است که پیش از آن چیزی نباشد
		// یا همان مسیر صفحهٔ جاری باشد.
		if ( at > 0 ) {
			var path = href.slice( 0, at );

			if ( path !== window.location.pathname && path !== window.location.href.split( '#' )[ 0 ] ) {
				return '';
			}
		}

		return decodeURIComponent( href.slice( at + 1 ) );
	}

	/**
	 * ناحیهٔ مقصد یک لینک، اگر واقعاً در همین صفحه باشد.
	 *
	 * @param {HTMLAnchorElement} link
	 * @return {HTMLElement|null}
	 */
	function targetOf( link ) {
		var id = anchorId( link );

		return id ? document.getElementById( id ) : null;
	}

	/**
	 * رقم‌های فارسی، برای شماره‌گذاری دوبارهٔ آیتم‌ها.
	 *
	 * @param {number|string} value
	 * @return {string}
	 */
	function fa( value ) {
		return String( value ).replace( /\d/g, function ( d ) {
			return String.fromCharCode( 0x06f0 + Number( d ) );
		} );
	}

	/**
	 * داخل ویرایشگر المنتور هستیم؟
	 *
	 * آنجا ممکن است هنوز شناسهٔ بخش‌ها گذاشته نشده باشد؛ اگر آیتم‌ها را پنهان
	 * کنیم، کاربر ویجتی می‌بیند که خودش را ناپدید کرده و دیگر نمی‌تواند
	 * انتخاب، جابه‌جا یا حذفش کند. تشخیص اصلی از سمت سرور می‌آید (data-editor)
	 * چون پیش‌نمایش المنتور iframe جداست و روی body نشانه‌ای نمی‌گذارد.
	 *
	 * @param {HTMLElement} box
	 * @return {boolean}
	 */
	function inEditor( box ) {
		if ( box && '1' === box.getAttribute( 'data-editor' ) ) {
			return true;
		}

		try {
			return !! ( window.elementorFrontend && window.elementorFrontend.isEditMode &&
				window.elementorFrontend.isEditMode() );
		} catch ( e ) {
			return false;
		}
	}

	/**
	 * فاصله‌ای که باید بالای ناحیه خالی بماند: عدد تنظیمات + نوار مدیریت وردپرس.
	 *
	 * @param {HTMLElement} box
	 * @return {number}
	 */
	function offsetOf( box ) {
		var offset = parseInt( box.getAttribute( 'data-offset' ), 10 );
		var bar = document.getElementById( 'wpadminbar' );

		if ( isNaN( offset ) || offset < 0 ) {
			offset = 0;
		}

		if ( bar && 'fixed' === window.getComputedStyle( bar ).position ) {
			offset += bar.offsetHeight;
		}

		return offset;
	}

	/**
	 * موقعیت بالای یک عنصر نسبت به سند.
	 *
	 * @param {HTMLElement} el
	 * @return {number}
	 */
	function topOf( el ) {
		return el.getBoundingClientRect().top + window.pageYOffset;
	}

	function reduceMotion() {
		return !! ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches );
	}

	/* ------------------------------------------------------------------ */
	/* جمع‌آوری فهرست‌ها                                                    */
	/* ------------------------------------------------------------------ */

	/**
	 * پیدا کردن دوبارهٔ همهٔ فهرست‌های صفحه و لینک‌هایشان.
	 */
	function collect() {
		boxes = [];

		Array.prototype.forEach.call( document.querySelectorAll( '[data-ard-toc]' ), function ( box ) {
			var links = Array.prototype.slice.call( box.querySelectorAll( '[data-ard-toc-link]' ) );

			if ( ! links.length ) {
				return;
			}

			var entry = {
				box: box,
				list: box.querySelector( '[data-ard-toc-list]' ) || box,
				links: links
			};

			prune( entry );
			boxes.push( entry );
		} );

		schedule();
	}

	/**
	 * آیتمی که ناحیه‌اش در این صفحه نیست، نباید دیده شود.
	 *
	 * فقط لینک‌های همین صفحه («#شناسه») بررسی می‌شوند؛ لینک بیرونی یا لینک
	 * صفحهٔ دیگر مقصدش اینجا نیست و همیشه می‌ماند. پنهان‌کردن با hidden انجام
	 * می‌شود نه حذف، تا اگر ناحیه بعداً ساخته شد (بارگذاری تنبل، ویرایشگر)
	 * آیتم خودش برگردد.
	 *
	 * @param {Object} entry
	 */
	function prune( entry ) {
		if ( inEditor( entry.box ) ) {
			return;
		}

		var visible = 0;

		entry.links.forEach( function ( link ) {
			var item = link.closest( '.ard-toc__item' ) || link;
			var id = anchorId( link );
			var gone = '' !== id && ! document.getElementById( id );

			if ( item.hidden !== gone ) {
				item.hidden = gone;
			}

			if ( ! gone ) {
				visible++;
			}
		} );

		// همه رفتند: عنوانِ تنهای فهرست هم نماند.
		if ( entry.box.hidden !== ( 0 === visible ) ) {
			entry.box.hidden = 0 === visible;
		}

		renumber( entry );
	}

	/**
	 * شماره‌ها بعد از پنهان‌شدن چند آیتم نباید جا بیندازند.
	 *
	 * @param {Object} entry
	 */
	function renumber( entry ) {
		var n = 0;

		entry.links.forEach( function ( link ) {
			var num = link.querySelector( '.ard-toc__num' );
			var item = link.closest( '.ard-toc__item' ) || link;

			if ( ! num || item.hidden ) {
				return;
			}

			n++;

			/*
			 * فقط وقتی عدد واقعاً عوض شده می‌نویسیم. نوشتن همان مقدار هم گرهٔ
			 * متن را عوض می‌کند و MutationObserver را بیدار می‌کند؛ آن هم دوباره
			 * به همین‌جا می‌رسد و حلقه‌ای بی‌پایان می‌سازد که مرورگر را می‌خواباند.
			 */
			var next = fa( n );

			if ( num.textContent !== next ) {
				num.textContent = next;
			}
		} );
	}

	/**
	 * جمع‌آوری دوباره با تأخیر کوتاه، تا تغییرهای پشت‌سرهم DOM یک‌بار جمع شوند.
	 */
	function collectSoon() {
		window.clearTimeout( rescan );
		rescan = window.setTimeout( collect, 200 );
	}

	/* ------------------------------------------------------------------ */
	/* حالت فعال                                                           */
	/* ------------------------------------------------------------------ */

	/**
	 * فعال‌کردن یک آیتم و خاموش‌کردن بقیه.
	 *
	 * @param {Object}  entry
	 * @param {number}  index  شمارهٔ آیتم فعال (‎-1 یعنی هیچ‌کدام).
	 * @param {boolean} scroll آیتم فعال داخل خود فهرست دیده شود؟
	 */
	function setActive( entry, index, scroll ) {
		var changed = false;

		entry.links.forEach( function ( link, i ) {
			var on = i === index;

			if ( link.classList.contains( 'is-active' ) === on ) {
				return;
			}

			changed = true;
			link.classList.toggle( 'is-active', on );

			var item = link.closest( '.ard-toc__item' );

			if ( item ) {
				item.classList.toggle( 'is-active', on );
			}

			if ( on ) {
				link.setAttribute( 'aria-current', 'true' );
			} else {
				link.removeAttribute( 'aria-current' );
			}
		} );

		if ( changed && scroll && index >= 0 ) {
			keepVisible( entry.list, entry.links[ index ] );
		}
	}

	/**
	 * وقتی خود فهرست اسکرول دارد (افقیِ چسبان یا عمودیِ بلند)، آیتم فعال نباید
	 * از دید بیرون بماند. فقط خود فهرست جابه‌جا می‌شود، نه صفحه.
	 *
	 * @param {HTMLElement} list
	 * @param {HTMLElement} link
	 */
	function keepVisible( list, link ) {
		if ( ! list || ! link || ! list.scrollBy ) {
			return;
		}

		var lr = link.getBoundingClientRect();
		var br = list.getBoundingClientRect();
		var behavior = reduceMotion() ? 'auto' : 'smooth';

		if ( list.scrollWidth > list.clientWidth + 2 ) {
			list.scrollBy( {
				left: ( lr.left + lr.width / 2 ) - ( br.left + br.width / 2 ),
				behavior: behavior
			} );
		}

		if ( list.scrollHeight > list.clientHeight + 2 ) {
			list.scrollBy( {
				top: ( lr.top + lr.height / 2 ) - ( br.top + br.height / 2 ),
				behavior: behavior
			} );
		}
	}

	/**
	 * محاسبهٔ آیتم فعالِ هر فهرست بر اساس موقعیت اسکرول.
	 */
	function update() {
		frame = null;

		var scrolled = window.pageYOffset;
		var atBottom = ( window.innerHeight + scrolled ) >= ( document.documentElement.scrollHeight - 2 );

		boxes.forEach( function ( entry ) {
			if ( '0' === entry.box.getAttribute( 'data-spy' ) ) {
				return;
			}

			var offset = offsetOf( entry.box );
			var active = -1;
			var first = -1;
			var last = -1;

			entry.links.forEach( function ( link, i ) {
				var target = targetOf( link );

				if ( ! target ) {
					return;
				}

				if ( first < 0 ) {
					first = i;
				}

				last = i;

				// ناحیه‌ای فعال است که بالایش از خط دید گذشته باشد؛ آخرین ناحیهٔ
				// گذشته همان جایی است که کاربر داخلش ایستاده.
				if ( topOf( target ) - offset - 4 <= scrolled ) {
					active = i;
				}
			} );

			// تهِ صفحه: ناحیهٔ آخر ممکن است کوتاه‌تر از آن باشد که به خط دید
			// برسد، ولی کاربر واقعاً همان‌جاست.
			if ( atBottom && last >= 0 ) {
				active = last;
			}

			// هنوز به هیچ ناحیه‌ای نرسیده: نخستین آیتمِ دیده‌شده به‌عنوان «بعدی»
			// روشن می‌ماند (نه لزوماً آیتم اول؛ ممکن است پنهان شده باشد).
			if ( active < 0 ) {
				active = first;
			}

			setActive( entry, active, true );
		} );
	}

	/**
	 * محاسبهٔ حالت فعال در فریم بعدی، تا اسکرول کند نشود.
	 */
	function schedule() {
		if ( null === frame ) {
			frame = window.requestAnimationFrame( update );
		}
	}

	/* ------------------------------------------------------------------ */
	/* پرش به ناحیه                                                        */
	/* ------------------------------------------------------------------ */

	/**
	 * اسکرول به یک ناحیه، با در نظر گرفتن فاصلهٔ بالا.
	 *
	 * @param {HTMLElement} target
	 * @param {number}      offset
	 * @param {boolean}     smooth
	 */
	function scrollToTarget( target, offset, smooth ) {
		window.scrollTo( {
			top: Math.max( 0, topOf( target ) - offset ),
			behavior: ( smooth && ! reduceMotion() ) ? 'smooth' : 'auto'
		} );
	}

	document.addEventListener( 'click', function ( event ) {
		if ( event.defaultPrevented || event.metaKey || event.ctrlKey || event.shiftKey ||
			( event.button && 0 !== event.button ) ) {
			return;
		}

		var link = event.target.closest ? event.target.closest( '[data-ard-toc-link]' ) : null;

		if ( ! link ) {
			return;
		}

		var box = link.closest( '[data-ard-toc]' );
		var target = box ? targetOf( link ) : null;

		// لینک بیرونی یا ناحیه‌ای که در این صفحه نیست: رفتار عادی مرورگر.
		if ( ! target ) {
			return;
		}

		event.preventDefault();

		scrollToTarget( target, offsetOf( box ), '0' !== box.getAttribute( 'data-smooth' ) );

		// نشانی صفحه به‌روز شود، ولی مرورگر خودش دوباره پرش نکند.
		if ( target.id && window.history && window.history.replaceState ) {
			window.history.replaceState( null, '', '#' + target.id );
		}

		// صفحه‌خوان و کیبورد باید تمرکز را همان‌جا پیدا کنند.
		if ( ! target.hasAttribute( 'tabindex' ) ) {
			target.setAttribute( 'tabindex', '-1' );
		}

		target.focus( { preventScroll: true } );
	} );

	/**
	 * ورود با نشانیِ ‎#id: مرورگر بدون در نظر گرفتن هدر چسبان پرش می‌کند، پس
	 * همان اول تصحیحش می‌کنیم.
	 */
	function fixHashLanding() {
		if ( ! window.location.hash ) {
			return;
		}

		for ( var i = 0; i < boxes.length; i++ ) {
			for ( var j = 0; j < boxes[ i ].links.length; j++ ) {
				var target = targetOf( boxes[ i ].links[ j ] );

				if ( target && '#' + target.id === window.location.hash ) {
					scrollToTarget( target, offsetOf( boxes[ i ].box ), false );

					return;
				}
			}
		}
	}

	/* ------------------------------------------------------------------ */
	/* راه‌اندازی                                                           */
	/* ------------------------------------------------------------------ */

	function start() {
		collect();
		fixHashLanding();

		window.addEventListener( 'scroll', schedule, { passive: true } );
		window.addEventListener( 'resize', schedule );
		window.addEventListener( 'load', schedule );

		// المنتور (ویرایشگر و بارگذاری تنبل) فهرست را دوباره می‌سازد.
		if ( window.MutationObserver ) {
			new MutationObserver( collectSoon ).observe(
				document.body,
				{ childList: true, subtree: true }
			);
		}
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', start );
	} else {
		start();
	}
}() );
