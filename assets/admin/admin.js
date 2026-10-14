/**
 * Ardal — صفحه تنظیمات: انتخابگر رنگ، پالت‌های آماده و پیش‌نمایش زنده.
 */
/**
 * Ardal — تب‌های صفحه تنظیمات.
 *
 * هر دو تب داخل یک فرم‌اند و با یک «ذخیره تغییرات» ثبت می‌شوند؛ اینجا فقط
 * نمایش عوض می‌شود. تب انتخاب‌شده در آدرس (#hash) می‌ماند تا بعد از ذخیره،
 * وردپرس که به همین صفحه برمی‌گردد، کاربر را سر جای قبلی بگذارد.
 */
( function () {
	'use strict';

	var tabs = document.querySelectorAll( '[data-ardal-tab]' );

	if ( ! tabs.length ) {
		return;
	}

	function show( name ) {
		Array.prototype.forEach.call( tabs, function ( tab ) {
			var mine = tab.getAttribute( 'data-ardal-tab' ) === name;

			tab.classList.toggle( 'is-active', mine );
			tab.setAttribute( 'aria-selected', mine ? 'true' : 'false' );
		} );

		Array.prototype.forEach.call(
			document.querySelectorAll( '[data-ardal-panel]' ),
			function ( panel ) {
				panel.hidden = panel.getAttribute( 'data-ardal-panel' ) !== name;
			}
		);
	}

	Array.prototype.forEach.call( tabs, function ( tab ) {
		tab.addEventListener( 'click', function () {
			var name = tab.getAttribute( 'data-ardal-tab' );

			show( name );

			// بدون پرش صفحه: hash را دستی می‌نویسیم نه با تغییر location.
			if ( window.history && window.history.replaceState ) {
				window.history.replaceState( null, '', '#' + name );
			}
		} );
	} );

	var start = ( window.location.hash || '' ).replace( '#', '' );

	if ( start && document.querySelector( '[data-ardal-panel="' + start + '"]' ) ) {
		show( start );
	}
}() );

/**
 * پیش‌نمایش زندهٔ سلکت‌های آیکون.
 *
 * فقط مسیر SVG عوض می‌شود، نه کل عنصر؛ این‌طوری لازم نیست مارک‌آپ آیکون در
 * جاوااسکریپت هم تکرار شود و همان چیزی می‌ماند که PHP چاپ کرده.
 */
( function () {
	'use strict';

	var paths = ( window.ardalAdmin || {} ).iconPaths || {};
	var rows = document.querySelectorAll( '.ardal-icon-row' );

	Array.prototype.forEach.call( rows, function ( row ) {
		var select = row.querySelector( 'select' );
		var path = row.querySelector( '.ardal-icon-preview path' );

		if ( ! select || ! path ) {
			return;
		}

		select.addEventListener( 'change', function () {
			if ( paths[ select.value ] ) {
				path.setAttribute( 'd', paths[ select.value ] );
			}
		} );
	} );
}() );

( function ( $ ) {
	'use strict';

	var data = window.ardalAdmin || {};
	var defaults = data.defaults || {};
	var presets = data.presets || [];

	var $preview = $( '#ardal-preview' );
	var $colors = $( '.ardal-color' );

	function hexToRgb( hex ) {
		var value = String( hex || '' ).replace( '#', '' );

		if ( 3 === value.length ) {
			value = value[ 0 ] + value[ 0 ] + value[ 1 ] + value[ 1 ] + value[ 2 ] + value[ 2 ];
		}

		if ( 6 !== value.length || ! /^[0-9a-f]{6}$/i.test( value ) ) {
			return '0, 0, 0';
		}

		return [
			parseInt( value.slice( 0, 2 ), 16 ),
			parseInt( value.slice( 2, 4 ), 16 ),
			parseInt( value.slice( 4, 6 ), 16 )
		].join( ', ' );
	}

	/**
	 * روشن یا تیره کردن رنگ — باید با Ardal_Palette::shade() در PHP یکی باشد.
	 */
	function shade( hex, percent ) {
		var rgb = hexToRgb( hex ).split( ',' ).map( function ( n ) {
			return parseInt( n, 10 );
		} );

		var target = percent > 0 ? 255 : 0;
		var ratio = Math.abs( percent ) / 100;

		var out = rgb.map( function ( channel ) {
			var next = Math.round( channel + ( target - channel ) * ratio );

			return Math.max( 0, Math.min( 255, next ) );
		} );

		return '#' + out.map( function ( n ) {
			return ( '0' + n.toString( 16 ) ).slice( -2 );
		} ).join( '' ).toUpperCase();
	}

	function currentValues() {
		var values = {};

		$colors.each( function () {
			var $field = $( this );

			values[ $field.data( 'key' ) ] = $field.val();
		} );

		return values;
	}

	/**
	 * همان متغیرهایی که PHP تولید می‌کند، روی جعبه پیش‌نمایش می‌نشیند.
	 */
	function repaint() {
		if ( ! $preview.length ) {
			return;
		}

		var values = currentValues();
		var node = $preview[ 0 ];

		Object.keys( values ).forEach( function ( key ) {
			var name = key.replace( /_/g, '-' );

			node.style.setProperty( '--ard-' + name, values[ key ] );
			node.style.setProperty( '--ard-' + name + '-rgb', hexToRgb( values[ key ] ) );
		} );

		if ( values.primary ) {
			node.style.setProperty( '--ard-primary-hover', shade( values.primary, -22 ) );
		}

		if ( values.accent ) {
			node.style.setProperty( '--ard-accent-hover', shade( values.accent, 14 ) );
		}

		if ( values.accent_dark ) {
			node.style.setProperty( '--ard-accent-ink', shade( values.accent_dark, -62 ) );
		}

		if ( values.primary_dark && values.primary && values.primary_light ) {
			node.style.setProperty(
				'--ard-hero-gradient',
				'linear-gradient(135deg,' + values.primary_dark + ',' + shade( values.primary, 8 ) + ' 60%,' + values.primary_light + ')'
			);
		}

		// واحد پول: متن یا نماد، بر اساس همان چک‌باکس تنظیمات.
		var toggle = document.getElementById( 'ardal-field-toman_symbol' );
		var useIcon = !! ( toggle && toggle.checked );

		Array.prototype.forEach.call( node.querySelectorAll( '[data-toman]' ), function ( el ) {
			el.hidden = ( 'icon' === el.getAttribute( 'data-toman' ) ) !== useIcon;
		} );

		var sizeField = document.getElementById( 'ardal-field-toman_size' );
		var size = sizeField ? parseFloat( sizeField.value ) : 170;

		if ( ! isNaN( size ) && size > 0 ) {
			node.style.setProperty( '--ard-toman-size', ( size / 100 ) + 'em' );
		}

		// نشان بالای صفحه هم با رنگ برند هماهنگ می‌شود.
		var mark = document.querySelector( '.ardal-title__mark' );

		if ( mark && values.primary_dark && values.accent ) {
			mark.style.background = 'linear-gradient(140deg,' + values.primary_dark + ',' + values.accent + ')';
		}
	}

	/**
	 * ریختن مجموعه‌ای از رنگ‌ها داخل فیلدها.
	 */
	function applyValues( values ) {
		$colors.each( function () {
			var $field = $( this );
			var key = $field.data( 'key' );

			if ( ! Object.prototype.hasOwnProperty.call( values, key ) ) {
				return;
			}

			// wpColorPicker مقدار را از طریق متد خودش می‌گیرد تا سواچ هم به‌روز شود.
			$field.wpColorPicker( 'color', values[ key ] );
		} );

		repaint();
	}

	$( function () {
		$colors.wpColorPicker( {
			change: function () {
				// change قبل از نوشتن مقدار در input صدا زده می‌شود.
				window.setTimeout( repaint, 0 );
			},
			clear: repaint
		} );

		$colors.on( 'input change', repaint );

		// چک‌باکس نماد تومان و اندازه‌اش هم روی پیش‌نمایش اثر دارند.
		$( '#ardal-field-toman_symbol, #ardal-field-toman_size' ).on( 'input change', repaint );

		$( '.ardal-preset' ).on( 'click', function () {
			var id = $( this ).data( 'preset' );

			var preset = presets.filter( function ( item ) {
				return item.id === id;
			} )[ 0 ];

			if ( ! preset ) {
				return;
			}

			// پالت‌های آماده فقط رنگ‌های اصلی را دارند؛ بقیه از پیش‌فرض دیزاین پر می‌شوند.
			applyValues( $.extend( {}, defaults, preset.values ) );
		} );

		$( '#ardal-reset' ).on( 'click', function () {
			if ( ! window.confirm( data.confirm ) ) {
				return;
			}

			applyValues( defaults );
		} );

		repaint();
	} );
}( window.jQuery ) );

/**
 * فیلدهای وابسته: فیلدی که data-ardal-show دارد فقط وقتی دیده می‌شود که
 * چک‌باکسِ هم‌نامش روشن باشد (مثلاً متن پیامک فقط با پیامک روشن).
 */
( function () {
	'use strict';

	var fields = document.querySelectorAll( '[data-ardal-show]' );

	if ( ! fields.length ) {
		return;
	}

	function control( name ) {
		return document.querySelector( 'input[type="checkbox"][name="' + name.replace( /"/g, '' ) + '"]' );
	}

	function sync() {
		Array.prototype.forEach.call( fields, function ( field ) {
			var box = control( field.getAttribute( 'data-ardal-show' ) );

			field.hidden = !! box && ! box.checked;
		} );
	}

	document.addEventListener( 'change', function ( event ) {
		if ( event.target && 'checkbox' === event.target.type ) {
			sync();
		}
	} );

	sync();
}() );

/**
 * انتخاب تصویر از کتابخانهٔ رسانه برای فیلدهای تصویر (مثل تصویر کیف پول).
 */
( function () {
	'use strict';

	document.addEventListener( 'click', function ( event ) {
		var pick = event.target.closest( '[data-ardal-media-pick]' );
		var clear = event.target.closest( '[data-ardal-media-clear]' );
		var wrap = ( pick || clear ) && ( pick || clear ).closest( '.ardal-media' );
		var input = wrap && wrap.querySelector( '[data-ardal-media-input]' );

		if ( ! input ) {
			return;
		}

		event.preventDefault();

		if ( clear ) {
			input.value = '';

			return;
		}

		if ( ! window.wp || ! window.wp.media ) {
			return;
		}

		var frame = window.wp.media( {
			title: 'انتخاب تصویر',
			button: { text: 'استفاده از این تصویر' },
			library: { type: 'image' },
			multiple: false
		} );

		frame.on( 'select', function () {
			var file = frame.state().get( 'selection' ).first().toJSON();

			input.value = file.url || '';
		} );

		frame.open();
	} );
}() );
