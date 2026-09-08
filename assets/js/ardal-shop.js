/**
 * Ardal — رفتار سبد خرید و تسویه حساب.
 *
 * شمارندهٔ تعداد، انتقال کالا به علاقه‌مندی‌ها و همگام نگه داشتن ظاهر
 * ردیف‌های انتخابی با ورودی رادیویی‌شان. بدون وابستگی به jQuery.
 */
( function () {
	'use strict';

	var CONFIG = window.ardalShop || {};

	/* ---------------- شمارندهٔ تعداد ---------------- */

	/**
	 * تغییر تعداد و فرستادن فرم. دکمهٔ «به‌روزرسانی سبد» خود ووکامرس زده
	 * می‌شود تا همان مسیر همیشگی طی شود.
	 *
	 * @param {HTMLElement} box
	 * @param {number}      step
	 */
	function bumpQty( box, step ) {
		var input = box.querySelector( 'input.qty' );

		if ( ! input ) {
			return;
		}

		var min = parseFloat( input.getAttribute( 'min' ) );
		var max = parseFloat( input.getAttribute( 'max' ) );
		var current = parseFloat( input.value );

		if ( isNaN( current ) ) {
			current = 0;
		}

		var next = current + step;

		if ( ! isNaN( min ) && next < min ) {
			next = min;
		}

		if ( ! isNaN( max ) && max > 0 && next > max ) {
			next = max;
		}

		if ( next === current ) {
			return;
		}

		input.value = next;
		input.dispatchEvent( new Event( 'change', { bubbles: true } ) );

		submitCart( box );
	}

	function submitCart( from ) {
		var form = from.closest( 'form.woocommerce-cart-form' );

		if ( ! form ) {
			return;
		}

		var update = form.querySelector( '[name="update_cart"]' );

		if ( ! update ) {
			return;
		}

		// ووکامرس دکمه را تا وقتی چیزی عوض نشده غیرفعال می‌کند.
		update.disabled = false;
		update.click();
	}

	/* ---------------- انتقال به علاقه‌مندی‌ها ---------------- */

	function saveForLater( button ) {
		var id = parseInt( button.getAttribute( 'data-ard-save-later' ), 10 );
		var remove = button.getAttribute( 'data-remove' );

		if ( ! id || ! remove ) {
			return;
		}

		button.disabled = true;
		button.textContent = 'در حال انتقال…';

		var go = function () {
			window.location.href = remove;
		};

		if ( ! CONFIG.ajax || ! CONFIG.nonce ) {
			go();

			return;
		}

		var body = new window.FormData();

		body.append( 'action', 'ardal_wishlist' );
		body.append( 'nonce', CONFIG.nonce );
		body.append( 'op', 'add' );
		body.append( 'product', id );

		window.fetch( CONFIG.ajax, { method: 'POST', body: body, credentials: 'same-origin' } )
			.then( go )
			.catch( go );
	}

	/* ---------------- ردیف‌های انتخابی ---------------- */

	/**
	 * کلاس is-on را با وضعیت واقعی رادیوها هماهنگ می‌کند. مرورگرهای قدیمی‌تر
	 * :has() ندارند و بدون این، ردیف انتخاب‌شده دیده نمی‌شود.
	 *
	 * @param {HTMLInputElement} input
	 */
	function syncOptions( input ) {
		if ( ! input || ! input.name ) {
			return;
		}

		var group = document.querySelectorAll( 'input[name="' + input.name.replace( /"/g, '' ) + '"]' );

		Array.prototype.forEach.call( group, function ( radio ) {
			var row = radio.closest( '.ard-ck__opt' );

			if ( row ) {
				row.classList.toggle( 'is-on', radio.checked );
			}
		} );
	}

	function syncAll( root ) {
		Array.prototype.forEach.call(
			root.querySelectorAll( '.ard-ck__opt input[type="radio"]' ),
			function ( radio ) {
				var row = radio.closest( '.ard-ck__opt' );

				if ( row ) {
					row.classList.toggle( 'is-on', radio.checked );
				}
			}
		);
	}

	/* ---------------- راه‌اندازی ---------------- */

	function init() {
		var root = document.querySelector( '[data-ard-shop]' );

		if ( ! root || root.ardShopReady ) {
			return;
		}

		root.ardShopReady = true;
		root.classList.add( 'ard-shop--js' );

		root.addEventListener( 'click', function ( event ) {
			var target = event.target;

			if ( ! target.closest ) {
				return;
			}

			var down = target.closest( '[data-ard-qty-down]' );
			var up = target.closest( '[data-ard-qty-up]' );

			if ( down || up ) {
				event.preventDefault();

				var box = ( down || up ).closest( '[data-ard-qty]' );

				if ( box ) {
					bumpQty( box, down ? -1 : 1 );
				}

				return;
			}

			var save = target.closest( '[data-ard-save-later]' );

			if ( save ) {
				event.preventDefault();
				saveForLater( save );
			}
		} );

		// تغییر دستی عدد تعداد هم سبد را به‌روز می‌کند.
		root.addEventListener( 'change', function ( event ) {
			if ( event.target.matches && event.target.matches( '.ard-cart__qty input.qty' ) ) {
				submitCart( event.target );
			}
		} );

		syncAll( root );

		// ردیف‌های ارسال و پرداخت روی کل صفحه شنیده می‌شوند، چون ووکامرس بعد
		// از هر به‌روزرسانی ایجکسی، این بخش‌ها را دوباره می‌سازد.
		document.addEventListener( 'change', function ( event ) {
			if ( event.target.matches && event.target.matches( 'input[name^="shipping_method"], input[name="payment_method"]' ) ) {
				syncOptions( event.target );
			}
		} );

		document.body.addEventListener( 'updated_checkout', function () {
			syncAll( document );
		} );

		document.body.addEventListener( 'updated_wc_div', function () {
			syncAll( document );
		} );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
