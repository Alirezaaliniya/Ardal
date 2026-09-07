/**
 * Ardal — رفتار فیلدهای مگا منو در صفحه «نمایش ← فهرست‌ها».
 * انتخاب بنر با کتابخانه رسانه وردپرس و پنهان/نمایش گزینه‌های پنل.
 */
( function ( $ ) {
	'use strict';

	var frames = {};

	function syncMega( $box ) {
		var $check = $box.find( '[data-ardal-mm-mega]' );

		$box.toggleClass( 'is-mega', $check.length > 0 && $check.is( ':checked' ) );
	}

	function syncAll() {
		$( '[data-ardal-mm-fields]' ).each( function () {
			syncMega( $( this ) );
		} );
	}

	$( document )
		.on( 'change', '[data-ardal-mm-mega]', function () {
			syncMega( $( this ).closest( '[data-ardal-mm-fields]' ) );
		} )
		.on( 'click', '[data-ardal-mm-pick]', function ( event ) {
			event.preventDefault();

			var $media = $( this ).closest( '[data-ardal-mm-media]' );
			var $input = $media.find( '[data-ardal-mm-image]' );
			var key = $input.attr( 'name' );

			if ( ! frames[ key ] ) {
				frames[ key ] = wp.media( {
					title: 'انتخاب بنر مگا منو',
					button: { text: 'استفاده از این تصویر' },
					library: { type: 'image' },
					multiple: false
				} );

				frames[ key ].on( 'select', function () {
					var image = frames[ key ].state().get( 'selection' ).first().toJSON();
					var src = image.sizes && image.sizes.medium ? image.sizes.medium.url : image.url;

					$input.val( image.id );
					$media.find( '[data-ardal-mm-preview]' ).html( $( '<img>' ).attr( 'src', src ) );

					// وردپرس دکمه ذخیره را فقط با تغییر فیلدهای فرم فعال می‌کند.
					$input.trigger( 'change' );
				} );
			}

			frames[ key ].open();
		} )
		.on( 'click', '[data-ardal-mm-clear]', function ( event ) {
			event.preventDefault();

			var $media = $( this ).closest( '[data-ardal-mm-media]' );

			$media.find( '[data-ardal-mm-image]' ).val( '' ).trigger( 'change' );
			$media.find( '[data-ardal-mm-preview]' ).empty();
		} );

	$( syncAll );

	// آیتم‌هایی که با ایجکس به منو اضافه می‌شوند هم باید تنظیم شوند.
	$( document ).on( 'menu-item-added menu-item-added-multiple', syncAll );
	$( document ).ajaxComplete( function () {
		window.setTimeout( syncAll, 0 );
	} );
}( jQuery ) );
