/*!
 * Ardal — جمع/باز شدن «مشاهده بیشتر» در باکس توضیحات و مشخصات محصول.
 *
 * - حالت ارتفاع (توضیحات): ارتفاع بسته از متغیر --ard-pi-collapsed (کنترل المنتور).
 * - حالت ردیف (مشخصات): ارتفاع بسته = پایین ردیف Nام + کمی از ردیف بعد، که
 *   در همان متغیر نوشته می‌شود.
 *
 * وقتی محتوا از قبل جا شود، باکس .is-fit می‌گیرد (نه برش، نه دکمه). اندازه‌گیری
 * صبر می‌کند تا باکس دیده شود (ویجت داخل تب پنهان اندازهٔ صفر دارد)؛
 * ResizeObserver با نمایان شدن، بارگذاری فونت و تغییر عرض دوباره اندازه می‌گیرد.
 */
( function () {
	'use strict';

	var TOLERANCE = 6;

	function ready( fn ) {
		if ( document.readyState !== 'loading' ) {
			fn();
		} else {
			document.addEventListener( 'DOMContentLoaded', fn );
		}
	}

	function durationMs( el ) {
		var d = parseFloat( getComputedStyle( el ).transitionDuration ) || 0;
		return d * 1000;
	}

	function init( root ) {
		if ( ! root || root.ardPi ) {
			return;
		}

		var clip   = root.querySelector( '.ard-pi__clip' );
		var inner  = root.querySelector( '.ard-pi__inner' );
		var toggle = root.querySelector( '.ard-pi__toggle' );
		var label  = toggle && toggle.querySelector( '.ard-pi__toggle-text' );

		if ( ! clip || ! inner || ! toggle ) {
			return;
		}

		var state = root.ardPi = { open: false, timer: 0 };

		function collapsedHeight() {
			if ( root.getAttribute( 'data-ard-pi-mode' ) === 'rows' ) {
				var rows = inner.querySelectorAll( '.ard-pspec__row' );
				var n    = parseInt( root.getAttribute( 'data-ard-pi-rows' ), 10 ) || 5;
				var peek = parseInt( root.getAttribute( 'data-ard-pi-peek' ), 10 ) || 0;
				var last = rows[ Math.min( n, rows.length ) - 1 ];

				if ( ! last ) {
					return 0;
				}

				var h = last.offsetTop + last.offsetHeight + ( rows.length > n ? peek : 0 );
				root.style.setProperty( '--ard-pi-collapsed', h + 'px' );
				return h;
			}

			return parseFloat( getComputedStyle( root ).getPropertyValue( '--ard-pi-collapsed' ) ) || 0;
		}

		function measure() {
			// پنهان است (مثلاً تب غیرفعال)؛ وقتی دیده شد دوباره.
			if ( ! root.offsetWidth ) {
				return;
			}

			var limit = collapsedHeight();
			var fits  = inner.offsetHeight <= limit + TOLERANCE;

			root.classList.toggle( 'is-fit', fits );

			if ( fits && state.open ) {
				state.open = false;
				root.classList.remove( 'is-open' );
				clip.style.maxHeight = '';
			}
		}

		function setOpen( open ) {
			state.open = open;
			clearTimeout( state.timer );

			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			if ( label ) {
				label.textContent = toggle.getAttribute( open ? 'data-less' : 'data-more' ) || label.textContent;
			}

			if ( open ) {
				root.classList.add( 'is-open' );
				clip.style.maxHeight = inner.offsetHeight + 'px';
				// بعد از باز شدن سقف برداشته می‌شود تا تغییرات بعدی محتوا بریده نشود.
				state.timer = setTimeout( function () {
					if ( state.open ) {
						clip.style.maxHeight = 'none';
					}
				}, durationMs( clip ) + 30 );
				return;
			}

			// از «none» به عدد، تا انیمیشن نقطهٔ شروع داشته باشد.
			clip.style.maxHeight = inner.offsetHeight + 'px';
			void clip.offsetHeight; // eslint-disable-line no-void
			root.classList.remove( 'is-open' );
			clip.style.maxHeight = '';

			// اگر باکس بلند بالای صفحه جمع شد، دکمه از دید خارج نشود.
			var top = root.getBoundingClientRect().top;
			if ( top < 0 ) {
				root.scrollIntoView( { behavior: 'smooth', block: 'start' } );
			}
		}

		toggle.addEventListener( 'click', function () {
			setOpen( ! state.open );
		} );

		measure();

		if ( 'ResizeObserver' in window ) {
			new ResizeObserver( function () {
				if ( ! state.open ) {
					measure();
				}
			} ).observe( inner );
		} else {
			window.addEventListener( 'resize', measure );
			window.addEventListener( 'load', measure );
		}
	}

	function initIn( scope ) {
		var list = ( scope || document ).querySelectorAll( '[data-ard-pi]' );
		for ( var i = 0; i < list.length; i++ ) {
			init( list[ i ] );
		}
	}

	ready( function () {
		initIn( document );
	} );

	// ویرایشگر المنتور: ویجت با هر تغییر کنترل دوباره رندر می‌شود.
	function hookElementor() {
		var hooks = window.elementorFrontend && window.elementorFrontend.hooks;
		if ( ! hooks ) {
			return false;
		}
		[ 'ardal-product-description', 'ardal-product-specs-box' ].forEach( function ( name ) {
			hooks.addAction( 'frontend/element_ready/' + name + '.default', function ( $scope ) {
				initIn( $scope && $scope[ 0 ] );
			} );
		} );
		return true;
	}

	if ( ! hookElementor() ) {
		window.addEventListener( 'elementor/frontend/init', hookElementor );
	}
}() );
