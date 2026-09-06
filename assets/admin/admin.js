/**
 * Ardal — صفحه تنظیمات: انتخابگر رنگ، پالت‌های آماده و پیش‌نمایش زنده.
 */
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
