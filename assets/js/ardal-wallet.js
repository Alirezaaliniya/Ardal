/**
 * Ardal — کیف پول: مبلغ‌های میان‌بر، قالب‌بندی زندهٔ مبلغ، انتقال به درگاه و
 * به‌روزرسانی تسویه با تیک «پرداخت از کیف پول».
 *
 * همهٔ شنونده‌ها روی document هستند تا کارتی که پنل کاربری ایجکسی بار می‌کند
 * یا ویرایشگر المنتور از نو می‌سازد، بدون راه‌اندازی دوباره کار کند.
 */
( function () {
	'use strict';

	var CFG = window.ardalWallet || {};
	var I18N = CFG.i18n || {};

	function toast( text, kind, action ) {
		if ( window.ardToast ) {
			window.ardToast( text, kind, null, action );
		}
	}

	/**
	 * فقط رقم لاتین؛ ارقام فارسی و عربی هم پذیرفته می‌شوند.
	 *
	 * @param {string} value
	 * @return {string}
	 */
	function digits( value ) {
		return String( value || '' )
			.replace( /[۰-۹]/g, function ( d ) {
				return String( d.charCodeAt( 0 ) - 0x06f0 );
			} )
			.replace( /[٠-٩]/g, function ( d ) {
				return String( d.charCodeAt( 0 ) - 0x0660 );
			} )
			.replace( /\D/g, '' )
			.replace( /^0+(?=\d)/, '' );
	}

	/**
	 * جداکنندهٔ هزارگان، با ارقام فارسی اگر تنظیمات بخواهد.
	 *
	 * @param {string} value
	 * @return {string}
	 */
	function pretty( value ) {
		var grouped = String( value ).replace( /\B(?=(\d{3})+(?!\d))/g, ',' );

		if ( ! CFG.faDigits ) {
			return grouped;
		}

		return grouped
			.replace( /\d/g, function ( d ) {
				return String.fromCharCode( 0x06f0 + Number( d ) );
			} )
			.replace( /,/g, '٬' );
	}

	function card( el ) {
		return el.closest( '[data-ard-wallet]' );
	}

	function input( scope ) {
		return scope ? scope.querySelector( '[data-ard-wallet-input]' ) : null;
	}

	function markChips( scope, value ) {
		Array.prototype.forEach.call( scope.querySelectorAll( '[data-ard-wallet-quick]' ), function ( chip ) {
			var on = '' !== value && digits( chip.getAttribute( 'data-ard-wallet-quick' ) ) === value;

			chip.classList.toggle( 'is-on', on );
			chip.setAttribute( 'aria-pressed', on ? 'true' : 'false' );
		} );
	}

	/* ---------------- مبلغ‌های میان‌بر ---------------- */

	document.addEventListener( 'click', function ( event ) {
		var chip = event.target.closest( '[data-ard-wallet-quick]' );

		if ( ! chip ) {
			return;
		}

		event.preventDefault();

		var scope = card( chip );
		var box = input( scope );
		var value = digits( chip.getAttribute( 'data-ard-wallet-quick' ) );

		if ( ! box ) {
			return;
		}

		box.value = pretty( value );
		box.classList.remove( 'is-bad' );
		markChips( scope, value );
		box.focus();
	} );

	/* ---------------- قالب‌بندی زنده ---------------- */

	document.addEventListener( 'input', function ( event ) {
		var box = event.target.closest( '[data-ard-wallet-input]' );

		if ( ! box ) {
			return;
		}

		var clean = digits( box.value );

		box.value = clean ? pretty( clean ) : '';
		box.classList.remove( 'is-bad' );

		var scope = card( box );

		if ( scope ) {
			markChips( scope, clean );
		}
	} );

	/* ---------------- انتقال به درگاه ---------------- */

	function fail( box, message ) {
		if ( box ) {
			box.classList.add( 'is-bad' );
			box.focus();
		}

		toast( message, 'error' );
	}

	function topup( button ) {
		var scope = card( button );
		var box = input( scope );
		var amount = box ? digits( box.value ) : '';
		var value = Number( amount );

		if ( ! CFG.loggedIn ) {
			toast( I18N.loggedOut, 'info', { url: CFG.loginUrl, text: I18N.login } );

			return;
		}

		if ( ! amount || ! value ) {
			fail( box, I18N.empty );

			return;
		}

		if ( CFG.min > 0 && value < CFG.min ) {
			fail( box, String( I18N.min ).replace( '%s', pretty( String( CFG.min ) ) ) );

			return;
		}

		if ( CFG.max > 0 && value > CFG.max ) {
			fail( box, String( I18N.max ).replace( '%s', pretty( String( CFG.max ) ) ) );

			return;
		}

		button.disabled = true;
		button.classList.add( 'is-busy' );

		var body = new window.FormData();

		body.append( 'action', 'ardal_wallet_topup' );
		body.append( 'nonce', CFG.nonce || '' );
		body.append( 'amount', amount );

		window.fetch( CFG.ajax, { method: 'POST', credentials: 'same-origin', body: body } )
			.then( function ( response ) {
				return response.json();
			} )
			.then( function ( payload ) {
				var data = ( payload && payload.data ) || {};

				if ( ! payload || ! payload.success ) {
					if ( 'logged_out' === data.code ) {
						CFG.loggedIn = false;
						toast( data.message || I18N.loggedOut, 'info', { url: data.login || CFG.loginUrl, text: I18N.login } );
						throw null;
					}

					var error = new Error( data.message || '' );

					error.fromServer = !! data.message;
					throw error;
				}

				toast( I18N.sending, 'success' );
				window.location.href = data.redirect;
			} )
			.catch( function ( error ) {
				if ( error ) {
					// خطای شبکه پیام فنی دارد؛ فقط پیام سرور عیناً نشان داده می‌شود.
					fail( box, error.fromServer ? error.message : I18N.error );
				}

				button.disabled = false;
				button.classList.remove( 'is-busy' );
			} );
	}

	document.addEventListener( 'click', function ( event ) {
		var button = event.target.closest( '[data-ard-wallet-submit]' );

		if ( button && ! button.disabled ) {
			event.preventDefault();
			topup( button );
		}
	} );

	// اینتر داخل ورودی مبلغ = زدن دکمه.
	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Enter' !== event.key ) {
			return;
		}

		var box = event.target.closest( '[data-ard-wallet-input]' );
		var scope = box && card( box );
		var button = scope && scope.querySelector( '[data-ard-wallet-submit]' );

		if ( button ) {
			event.preventDefault();

			if ( ! button.disabled ) {
				topup( button );
			}
		}
	} );

	/* ---------------- برداشت ---------------- */

	/**
	 * شبا به شکل استاندارد: حروف بزرگ، ارقام لاتین، بدون فاصله؛ ۲۴ رقم خالی
	 * یعنی «IR» جا افتاده.
	 *
	 * @param {string} value
	 * @return {string}
	 */
	function ibanClean( value ) {
		var latin = String( value || '' )
			.replace( /[۰-۹]/g, function ( d ) {
				return String( d.charCodeAt( 0 ) - 0x06f0 );
			} )
			.replace( /[٠-٩]/g, function ( d ) {
				return String( d.charCodeAt( 0 ) - 0x0660 );
			} )
			.toUpperCase()
			.replace( /[^0-9A-Z]/g, '' );

		return /^\d{24}$/.test( latin ) ? 'IR' + latin : latin;
	}

	/* همان بررسی رقم کنترل سمت سرور (ISO 13616، مود ۹۷) */
	function ibanValid( iban ) {
		if ( ! /^IR\d{24}$/.test( iban ) ) {
			return false;
		}

		var moved = iban.slice( 4 ) + '1827' + iban.slice( 2, 4 );
		var rest = 0;

		for ( var i = 0; i < moved.length; i += 7 ) {
			rest = Number( String( rest ) + moved.slice( i, i + 7 ) ) % 97;
		}

		return 1 === rest;
	}

	function ibanPretty( iban ) {
		return iban.replace( /(.{4})(?=.)/g, '$1 ' );
	}

	function post( data ) {
		var body = new window.FormData();

		Object.keys( data ).forEach( function ( key ) {
			body.append( key, data[ key ] );
		} );
		body.append( 'nonce', CFG.nonce || '' );

		return window.fetch( CFG.ajax, { method: 'POST', credentials: 'same-origin', body: body } )
			.then( function ( response ) {
				return response.json();
			} )
			.then( function ( payload ) {
				var result = ( payload && payload.data ) || {};

				if ( ! payload || ! payload.success ) {
					var error = new Error( result.message || '' );

					error.fromServer = !! result.message;
					error.code = result.code || '';
					throw error;
				}

				return result;
			} );
	}

	document.addEventListener( 'input', function ( event ) {
		var amount = event.target.closest( '[data-ard-withdraw-amount]' );
		var iban = event.target.closest( '[data-ard-withdraw-iban]' );

		if ( amount ) {
			var clean = digits( amount.value );

			amount.value = clean ? pretty( clean ) : '';
			amount.classList.remove( 'is-bad' );
		}

		if ( iban ) {
			iban.classList.remove( 'is-bad' );
		}
	} );

	// شبا هنگام خروج از فیلد مرتب می‌شود، نه حین تایپ که مکان‌نما نپرد.
	document.addEventListener( 'focusout', function ( event ) {
		var iban = event.target.closest( '[data-ard-withdraw-iban]' );

		if ( iban && iban.value ) {
			iban.value = ibanPretty( ibanClean( iban.value ) );
		}
	} );

	function withdraw( button ) {
		var scope = button.closest( '[data-ard-withdraw]' );
		var amountBox = scope && scope.querySelector( '[data-ard-withdraw-amount]' );
		var ibanBox = scope && scope.querySelector( '[data-ard-withdraw-iban]' );
		var amount = amountBox ? digits( amountBox.value ) : '';
		var iban = ibanBox ? ibanClean( ibanBox.value ) : '';

		if ( ! amount || ! Number( amount ) ) {
			fail( amountBox, I18N.wdAmount );

			return;
		}

		if ( CFG.minWithdraw > 0 && Number( amount ) < CFG.minWithdraw ) {
			fail( amountBox, String( I18N.wdMin ).replace( '%s', pretty( String( CFG.minWithdraw ) ) ) );

			return;
		}

		if ( ! ibanValid( iban ) ) {
			fail( ibanBox, I18N.wdIban );

			return;
		}

		if ( ! window.confirm( String( I18N.wdConfirm ).replace( '%s', pretty( amount ) ) ) ) {
			return;
		}

		button.disabled = true;
		button.classList.add( 'is-busy' );

		post( { action: 'ardal_wallet_withdraw', amount: amount, iban: iban } )
			.then( function ( data ) {
				toast( data.message, 'success' );
				window.setTimeout( function () {
					window.location.reload();
				}, 1200 );
			} )
			.catch( function ( error ) {
				fail( 'iban' === error.code ? ibanBox : amountBox, error.fromServer ? error.message : I18N.wdError );
				button.disabled = false;
				button.classList.remove( 'is-busy' );
			} );
	}

	document.addEventListener( 'click', function ( event ) {
		var button = event.target.closest( '[data-ard-withdraw-submit]' );

		if ( button && ! button.disabled ) {
			event.preventDefault();
			withdraw( button );
		}

		var cancel = event.target.closest( '[data-ard-withdraw-cancel]' );

		if ( ! cancel || cancel.disabled ) {
			return;
		}

		event.preventDefault();

		if ( ! window.confirm( I18N.wdCancel ) ) {
			return;
		}

		cancel.disabled = true;

		post( { action: 'ardal_wallet_withdraw_cancel', id: cancel.getAttribute( 'data-ard-withdraw-cancel' ) } )
			.then( function ( data ) {
				toast( data.message, 'success' );
				window.setTimeout( function () {
					window.location.reload();
				}, 1200 );
			} )
			.catch( function ( error ) {
				toast( error.fromServer ? error.message : I18N.wdError, 'error' );
				cancel.disabled = false;
			} );
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Enter' !== event.key ) {
			return;
		}

		var box = event.target.closest( '[data-ard-withdraw-amount], [data-ard-withdraw-iban]' );
		var scope = box && box.closest( '[data-ard-withdraw]' );
		var button = scope && scope.querySelector( '[data-ard-withdraw-submit]' );

		if ( button ) {
			event.preventDefault();

			if ( ! button.disabled ) {
				withdraw( button );
			}
		}
	} );

	/* ---------------- پنل کاربری: باز و بسته کردن فرم شارژ ---------------- */

	document.addEventListener( 'click', function ( event ) {
		var toggle = event.target.closest( '[data-ard-wallet-toggle]' );

		if ( ! toggle ) {
			return;
		}

		var target = document.getElementById( toggle.getAttribute( 'aria-controls' ) );

		if ( ! target ) {
			return;
		}

		event.preventDefault();

		var open = target.hidden;

		target.hidden = ! open;
		toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );

		if ( open ) {
			var box = input( target );

			if ( box ) {
				box.focus();
			}
		}
	} );

	/* ---------------- تسویه حساب ---------------- */

	/**
	 * تیک «پرداخت از کیف پول» فاکتور را از نو حساب می‌کند. چک‌باکس داخل فرم
	 * تسویه است، پس ووکامرس مقدارش را خودش در post_data می‌فرستد.
	 */
	document.addEventListener( 'change', function ( event ) {
		var box = event.target.closest( '[data-ard-wallet-pay] input[type="checkbox"]' );

		if ( ! box ) {
			return;
		}

		var wrap = box.closest( '[data-ard-wallet-pay]' );

		if ( wrap ) {
			wrap.classList.toggle( 'is-on', box.checked );
		}

		if ( window.jQuery ) {
			window.jQuery( document.body ).trigger( 'update_checkout' );
		}
	} );
}() );
