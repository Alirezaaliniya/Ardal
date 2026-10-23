/*!
 * Ardal — گالری محصول: انتخاب تصویر کوچک، دکمه‌های قبلی/بعدی، کشیدن انگشت و
 * لایت‌باکس المنتور.
 *
 * - کلیک روی تصویر کوچک (یا دکمه) src/srcset تصویر اصلی را عوض می‌کند و ستون را
 *   طوری اسکرول می‌کند که تصویر فعال دیده شود؛ جهت اسکرول از --ard-pg-axis می‌آید.
 * - کلیک روی تصویر اصلی لایت‌باکس المنتور را روی همان تصویر باز می‌کند.
 */
( function () {
	'use strict';

	function ready( fn ) {
		if ( document.readyState !== 'loading' ) {
			fn();
		} else {
			document.addEventListener( 'DOMContentLoaded', fn );
		}
	}

	function init( root ) {
		if ( ! root || root.ardPg ) {
			return;
		}
		root.ardPg = true;

		var img    = root.querySelector( '.ard-pg__main-img' );
		var link   = root.querySelector( '.ard-pg__main-link' );
		var track  = root.querySelector( '.ard-pg__track' );
		var thumbs = Array.prototype.slice.call( root.querySelectorAll( '[data-ard-pg-thumb]' ) );
		var prev   = root.querySelector( '[data-ard-pg-prev]' );
		var next   = root.querySelector( '[data-ard-pg-next]' );
		var lb     = root.querySelectorAll( '.ard-pg__lb a' );
		var loop   = root.getAttribute( 'data-loop' ) === '1';
		var index  = 0;

		function axis() {
			return getComputedStyle( root ).getPropertyValue( '--ard-pg-axis' ).trim() === 'x' ? 'x' : 'y';
		}

		// فقط داخل ستون اسکرول شود، نه کل صفحه.
		function reveal( thumb ) {
			if ( ! track || ! thumb ) {
				return;
			}
			var t = thumb.getBoundingClientRect();
			var r = track.getBoundingClientRect();

			if ( axis() === 'x' ) {
				if ( t.left < r.left ) {
					track.scrollBy( { left: t.left - r.left, behavior: 'smooth' } );
				} else if ( t.right > r.right ) {
					track.scrollBy( { left: t.right - r.right, behavior: 'smooth' } );
				}
			} else if ( t.top < r.top ) {
				track.scrollBy( { top: t.top - r.top, behavior: 'smooth' } );
			} else if ( t.bottom > r.bottom ) {
				track.scrollBy( { top: t.bottom - r.bottom, behavior: 'smooth' } );
			}
		}

		function syncArrows() {
			if ( loop ) {
				return;
			}
			if ( prev ) {
				prev.disabled = index <= 0;
			}
			if ( next ) {
				next.disabled = index >= thumbs.length - 1;
			}
		}

		function select( i ) {
			var count = thumbs.length;
			if ( ! count ) {
				return;
			}
			i = loop ? ( i + count ) % count : Math.max( 0, Math.min( count - 1, i ) );

			var thumb = thumbs[ i ];
			if ( i !== index && img ) {
				root.classList.add( 'is-swapping' );
				img.onload = function () {
					root.classList.remove( 'is-swapping' );
				};
				// اول srcset: وقتی هست، مرورگر برای انتخاب به src نگاه نمی‌کند.
				if ( thumb.getAttribute( 'data-srcset' ) ) {
					img.setAttribute( 'sizes', thumb.getAttribute( 'data-sizes' ) || '' );
					img.setAttribute( 'srcset', thumb.getAttribute( 'data-srcset' ) );
				} else {
					img.removeAttribute( 'srcset' );
				}
				img.src = thumb.getAttribute( 'data-src' );
				img.alt = thumb.getAttribute( 'data-alt' ) || '';
				if ( img.complete ) {
					root.classList.remove( 'is-swapping' );
				}
				if ( link ) {
					link.href = thumb.getAttribute( 'data-full' );
				}
			}

			thumbs.forEach( function ( t, n ) {
				t.classList.toggle( 'is-active', n === i );
				if ( n === i ) {
					t.setAttribute( 'aria-current', 'true' );
				} else {
					t.removeAttribute( 'aria-current' );
				}
			} );

			index = i;
			syncArrows();
			reveal( thumb );
		}

		thumbs.forEach( function ( t, n ) {
			t.addEventListener( 'click', function () {
				select( n );
			} );
		} );

		if ( prev ) {
			prev.addEventListener( 'click', function () {
				select( index - 1 );
			} );
		}
		if ( next ) {
			next.addEventListener( 'click', function () {
				select( index + 1 );
			} );
		}

		// لایت‌باکس: کلیک به لینک اسلایدشوی المنتورِ همین تصویر سپرده می‌شود.
		if ( link ) {
			link.addEventListener( 'click', function ( e ) {
				if ( root.dataset.swiped ) {
					e.preventDefault();
					delete root.dataset.swiped;
					return;
				}
				if ( link.hasAttribute( 'data-ard-pg-zoom' ) && lb[ index ] && window.elementorFrontend ) {
					e.preventDefault();
					lb[ index ].click();
				} else if ( ! link.hasAttribute( 'data-ard-pg-zoom' ) ) {
					e.preventDefault();
				}
			} );
		}

		// کشیدن روی تصویر اصلی (راست‌به‌چپ: کشیدن به راست = بعدی).
		if ( root.getAttribute( 'data-swipe' ) === '1' && thumbs.length > 1 ) {
			var main = root.querySelector( '.ard-pg__main' );
			var x0 = null;
			var y0 = null;

			main.addEventListener( 'touchstart', function ( e ) {
				x0 = e.touches[ 0 ].clientX;
				y0 = e.touches[ 0 ].clientY;
			}, { passive: true } );

			main.addEventListener( 'touchend', function ( e ) {
				if ( x0 === null ) {
					return;
				}
				var dx = e.changedTouches[ 0 ].clientX - x0;
				var dy = e.changedTouches[ 0 ].clientY - y0;
				x0 = null;

				if ( Math.abs( dx ) > 40 && Math.abs( dx ) > Math.abs( dy ) ) {
					root.dataset.swiped = '1';
					setTimeout( function () {
						delete root.dataset.swiped;
					}, 400 );
					select( index + ( dx > 0 ? 1 : -1 ) );
				}
			}, { passive: true } );
		}

		syncArrows();
	}

	function initIn( scope ) {
		var list = ( scope || document ).querySelectorAll( '[data-ard-pg]' );
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
		hooks.addAction( 'frontend/element_ready/ardal-product-gallery-rail.default', function ( $scope ) {
			initIn( $scope && $scope[ 0 ] );
		} );
		return true;
	}

	if ( ! hookElementor() ) {
		window.addEventListener( 'elementor/frontend/init', hookElementor );
	}
}() );
