/**
 * Ardal — صفحهٔ گزارش کیف پول: جمع زندهٔ کش‌بگ انتخاب‌شده، انتخاب همه،
 * تأیید پیش از ثبت گروهی و نمایش فیلدهای مربوط به نوع مخاطب.
 */
( function () {
	'use strict';

	function digits( value ) {
		return String( value || '' )
			.replace( /[۰-۹]/g, function ( d ) {
				return String( d.charCodeAt( 0 ) - 0x06f0 );
			} )
			.replace( /[^\d.]/g, '' );
	}

	function format( value ) {
		return Math.round( value ).toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' );
	}

	/* جمع کش‌بگ ردیف‌های تیک‌خورده */
	function updateSum( form ) {
		var target = document.querySelector( '[data-ardal-sum-target]' );

		if ( ! target || ! form ) {
			return;
		}

		var sum = 0;

		Array.prototype.forEach.call( form.querySelectorAll( '[data-ardal-sum]' ), function ( input ) {
			var row = input.closest( 'tr' );
			var check = row && row.querySelector( '[data-ardal-check]' );

			if ( ! check || check.checked ) {
				sum += parseFloat( digits( input.value ) ) || 0;
			}
		} );

		target.textContent = format( sum );
	}

	/* دکمه‌های بررسی برداشت: تأیید پیش از ارسال؛ رد بدون دلیل پذیرفته نمی‌شود */
	document.addEventListener( 'click', function ( event ) {
		var button = event.target.closest( '[data-ardal-confirm-click]' );

		if ( ! button ) {
			return;
		}

		var form = button.closest( 'form' );
		var note = form && form.querySelector( '[name="note"]' );

		if ( 'reject' === button.value && note && ! note.value.trim() ) {
			event.preventDefault();
			note.focus();
			note.setAttribute( 'placeholder', 'دلیل رد را بنویسید' );
			note.classList.add( 'is-bad' );

			return;
		}

		if ( ! window.confirm( button.getAttribute( 'data-ardal-confirm-click' ) ) ) {
			event.preventDefault();
		}
	} );

	document.addEventListener( 'change', function ( event ) {
		var all = event.target.closest( '[data-ardal-check-all]' );
		var form = event.target.closest( 'form' );

		if ( all && form ) {
			Array.prototype.forEach.call( form.querySelectorAll( '[data-ardal-check]' ), function ( box ) {
				box.checked = all.checked;
			} );
		}

		if ( event.target.closest( '[data-ardal-check], [data-ardal-check-all], [data-ardal-sum]' ) ) {
			updateSum( form );
		}

		var target = event.target.closest( '[data-ardal-bulk-target]' );

		if ( target ) {
			syncBulk( target );
		}
	} );

	document.addEventListener( 'input', function ( event ) {
		if ( event.target.closest( '[data-ardal-sum]' ) ) {
			updateSum( event.target.closest( 'form' ) );
		}
	} );

	/* تأیید پیش از ارسال فرم‌های برگشت‌ناپذیر */
	document.addEventListener( 'submit', function ( event ) {
		var form = event.target;
		var message = form.getAttribute( 'data-ardal-confirm' );

		if ( message && ! window.confirm( message ) ) {
			event.preventDefault();
		}

		if ( form.hasAttribute( 'data-ardal-bulk' ) ) {
			var confirmBox = form.querySelector( 'input[name="confirm"]' );

			if ( confirmBox && confirmBox.checked && ! window.confirm( 'شارژ گروهی انجام شود؟ این کار برگشت‌پذیر نیست.' ) ) {
				event.preventDefault();
			}
		}
	} );

	/* فیلد نقش فقط برای «نقش»، فهرست فقط برای «فهرست دستی» */
	function syncBulk( select ) {
		var form = select.closest( 'form' );

		Array.prototype.forEach.call( form.querySelectorAll( '[data-ardal-bulk-show]' ), function ( field ) {
			field.hidden = field.getAttribute( 'data-ardal-bulk-show' ) !== select.value;
		} );
	}

	Array.prototype.forEach.call( document.querySelectorAll( '[data-ardal-bulk-target]' ), syncBulk );
}() );
