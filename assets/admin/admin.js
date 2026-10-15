/**
 * Ardal — صفحهٔ تنظیمات: تب‌ها، ردیابی تغییرات و نوار ذخیره، جست‌وجو،
 * وابستگی فیلدها، سوییچ کارت‌ها، منوی کشیدنی پنل، پیامک آزمایشی، تصویر
 * رسانه، پالت‌های آماده و پیش‌نمایش زنده.
 *
 * فرم همان options.php است؛ اینجا فقط ظاهر و رفتار است و هیچ نام فیلدی
 * عوض نمی‌شود. اعداد برای نمایش فارسی می‌شوند و پیش از ارسال لاتین.
 */
( function () {
	'use strict';

	var root = document.querySelector( '[data-ards]' );

	if ( ! root ) {
		return;
	}

	var CFG = window.ardalAdmin || {};
	var form = root.querySelector( '[data-ards-form]' );
	var $ = function ( sel, ctx ) {
		return ( ctx || root ).querySelector( sel );
	};
	var $$ = function ( sel, ctx ) {
		return Array.prototype.slice.call( ( ctx || root ).querySelectorAll( sel ) );
	};

	/* ---------------------------------------------------------------- اعداد */

	var FA = '۰۱۲۳۴۵۶۷۸۹';

	function fa( s ) {
		return String( s == null ? '' : s ).replace( /\d/g, function ( d ) {
			return FA[ d ];
		} );
	}

	function en( s ) {
		return String( s == null ? '' : s )
			.replace( /[۰-۹]/g, function ( d ) {
				return FA.indexOf( d );
			} )
			.replace( /[٠-٩]/g, function ( d ) {
				return '٠١٢٣٤٥٦٧٨٩'.indexOf( d );
			} );
	}

	function group( n ) {
		return String( n ).replace( /\B(?=(\d{3})+(?!\d))/g, ',' );
	}

	function moneyShow( raw ) {
		var n = en( raw ).replace( /[^\d]/g, '' );

		return n ? fa( group( Number( n ) ) ).replace( /,/g, '٬' ) : '';
	}

	function numRaw( v ) {
		return en( v ).replace( /٫/g, '.' ).replace( /[^\d.]/g, '' );
	}

	function moneyRaw( v ) {
		return en( v ).replace( /[^\d]/g, '' );
	}

	function shortAmt( x ) {
		var n = Number( x ) || 0;

		if ( n >= 1e6 ) {
			return fa( +( n / 1e6 ).toFixed( 1 ) ).replace( '.', '٫' ) + ' میلیون تومان';
		}

		if ( n >= 1e3 ) {
			return fa( Math.round( n / 1e3 ) ) + ' هزار تومان';
		}

		return fa( n ) + ' تومان';
	}

	/* ---------------------------------------------------------------- مقدار و ردیابی تغییر */

	/**
	 * مقدار منطقی هر نام فیلد، همان چیزی که فرستاده می‌شود.
	 *
	 * @param {string} name
	 * @return {string}
	 */
	function readName( name ) {
		var els = form.querySelectorAll( '[name="' + name.replace( /"/g, '\\"' ) + '"]' );
		var el = els[ 0 ];

		if ( ! el ) {
			return '';
		}

		if ( 'checkbox' === el.type ) {
			return el.checked ? '1' : '0';
		}

		if ( 'radio' === el.type ) {
			var out = '';

			Array.prototype.forEach.call( els, function ( r ) {
				if ( r.checked ) {
					out = r.value;
				}
			} );

			return out;
		}

		if ( el.hasAttribute( 'data-ards-num' ) ) {
			return numRaw( el.value );
		}

		if ( el.hasAttribute( 'data-ards-money' ) ) {
			return moneyRaw( el.value );
		}

		if ( el.hasAttribute( 'data-ards-hex' ) ) {
			return el.value.toUpperCase();
		}

		return el.value;
	}

	function writeName( name, value ) {
		var els = form.querySelectorAll( '[name="' + name.replace( /"/g, '\\"' ) + '"]' );

		Array.prototype.forEach.call( els, function ( el ) {
			if ( 'checkbox' === el.type ) {
				el.checked = '1' === value;
			} else if ( 'radio' === el.type ) {
				el.checked = el.value === value;
			} else if ( el.hasAttribute( 'data-ards-num' ) ) {
				el.value = fa( value ).replace( '.', '٫' );
			} else if ( el.hasAttribute( 'data-ards-money' ) ) {
				el.value = moneyShow( value );
			} else {
				el.value = value;
			}
		} );
	}

	var names = [];
	var saved = {};

	$$( '[name]', form ).forEach( function ( el ) {
		var n = el.name;

		if ( n && n.indexOf( '[' ) > 0 && names.indexOf( n ) < 0 ) {
			names.push( n );
		}
	} );

	function snapshot() {
		names.forEach( function ( n ) {
			saved[ n ] = readName( n );
		} );
	}

	function tabOf( el ) {
		var p = el.closest( '[data-ards-panel]' );

		return p ? p.getAttribute( 'data-ards-panel' ) : '';
	}

	function isDirty( n ) {
		return readName( n ) !== saved[ n ];
	}

	var refreshQueued = false;

	function refresh() {
		if ( refreshQueued ) {
			return;
		}

		refreshQueued = true;
		window.requestAnimationFrame( function () {
			refreshQueued = false;
			doRefresh();
		} );
	}

	function doRefresh() {
		var dirtyNames = names.filter( isDirty );
		var perTab = {};

		dirtyNames.forEach( function ( n ) {
			var el = form.querySelector( '[name="' + n.replace( /"/g, '\\"' ) + '"]' );
			var t = el ? tabOf( el ) : '';

			perTab[ t ] = ( perTab[ t ] || 0 ) + 1;
		} );

		// نقطهٔ نارنجی کنار فیلد، کارت و آیتم ناوبری
		$$( '[data-ards-field]' ).forEach( function ( w ) {
			var dirty = $$( '[name]', w ).some( function ( el ) {
				return dirtyNames.indexOf( el.name ) >= 0;
			} );
			var dot = w.querySelector( '.ards-dot' );

			if ( dot ) {
				dot.hidden = ! dirty;
			}
		} );

		$$( '[data-ards-card]' ).forEach( function ( card ) {
			var dirty = $$( '[name]', card ).some( function ( el ) {
				return dirtyNames.indexOf( el.name ) >= 0;
			} );
			var dot = card.querySelector( '.ards-card__tl > .ards-dot' );
			var nav = root.querySelector( '[data-ards-jump="' + card.id + '"] .ards-dot' );

			if ( dot ) {
				dot.hidden = ! dirty;
			}

			if ( nav ) {
				nav.hidden = ! dirty;
			}
		} );

		$$( '[data-ards-tab]' ).forEach( function ( tab ) {
			var n = perTab[ tab.getAttribute( 'data-ards-tab' ) ] || 0;
			var c = tab.querySelector( '.ards-tab__count' );

			c.hidden = ! n;
			c.textContent = fa( n );
		} );

		var status = $( '[data-ards-status]' );
		var count = dirtyNames.length;

		if ( ! saving ) {
			status.classList.toggle( 'is-dirty', count > 0 );
			$( '[data-ards-status-text]' ).textContent = count ? fa( count ) + ' تغییر ذخیره نشده' : 'همهٔ تغییرات ذخیره شده';
		}

		var bar = $( '[data-ards-bar]' );
		var tabs = $$( '[data-ards-tab]' )
			.filter( function ( t ) {
				return perTab[ t.getAttribute( 'data-ards-tab' ) ];
			} )
			.map( function ( t ) {
				return t.querySelector( 'span' ).textContent;
			} );

		bar.classList.toggle( 'is-on', count > 0 || saving );
		$( '[data-ards-bar-text]' ).textContent = saving ? 'در حال ذخیره…' : fa( count ) + ' تغییر ذخیره نشده';
		$( '[data-ards-bar-tabs]' ).textContent = tabs.length ? 'در ' + tabs.join( '، ' ) : '';

		syncDependents();
	}

	/* ---------------------------------------------------------------- وابستگی‌ها و سوییچ کارت */

	function syncDependents() {
		$$( '[data-show-when]' ).forEach( function ( w ) {
			w.classList.toggle( 'is-hidden', '1' !== readName( w.getAttribute( 'data-show-when' ) ) );
		} );

		$$( '[data-ards-master]' ).forEach( function ( m ) {
			var card = m.closest( '[data-ards-card]' );

			card.classList.toggle( 'is-off', ! m.checked );
		} );

		$$( '[data-ards-state]' ).forEach( function ( s ) {
			s.classList.toggle( 'is-on', '1' === readName( s.getAttribute( 'data-ards-state' ) ) );
			s.title = '1' === readName( s.getAttribute( 'data-ards-state' ) ) ? 'روشن' : 'خاموش';
		} );

		$$( '[data-ards-pill]' ).forEach( function ( p ) {
			var on = '1' === readName( p.getAttribute( 'data-ards-pill' ) );

			p.classList.toggle( 'is-on', on );
			p.querySelector( '.ards-pill__text' ).textContent = p.getAttribute( 'data-label' ) + ( on ? ' · روشن' : ' · خاموش' );
		} );

		$$( '[data-ards-adv-card]' ).forEach( function ( card ) {
			var on = $$( '.ards-sw__in', card ).filter( function ( i ) {
				return i.checked;
			} ).length;
			var b = card.querySelector( '[data-ards-adv-on]' );

			b.hidden = ! on;
			b.textContent = fa( on ) + ' مورد روشن';
		} );

		$$( '[data-ards-empty-hide]' ).forEach( function ( i ) {
			var b = i.closest( '[data-ards-field]' ).querySelector( '[data-ards-empty-badge]' );

			if ( b ) {
				b.hidden = '' !== i.value.trim();
			}
		} );

		$$( '[data-ards-menu-label]' ).forEach( function ( i ) {
			var row = i.closest( '[data-ards-menu-row]' );

			row.querySelector( '.ards-menu__def' ).hidden = i.value === row.getAttribute( 'data-default' );
		} );

		$$( '[data-ards-ev]' ).forEach( syncEvent );
		syncPresets();
	}

	/* ---------------------------------------------------------------- تب‌ها و ناوبری */

	var KEY = 'ardalSettingsTab';

	function showTab( id, focus ) {
		$$( '[data-ards-tab]' ).forEach( function ( t ) {
			var on = t.getAttribute( 'data-ards-tab' ) === id;

			t.setAttribute( 'aria-selected', on ? 'true' : 'false' );
			t.tabIndex = on ? 0 : -1;

			if ( on && focus ) {
				t.focus();
			}
		} );

		$$( '[data-ards-panel]' ).forEach( function ( p ) {
			p.hidden = p.getAttribute( 'data-ards-panel' ) !== id;
		} );

		try {
			window.sessionStorage.setItem( KEY, id );
		} catch ( e ) {}

		if ( window.history && window.history.replaceState ) {
			window.history.replaceState( null, '', '#' + id );
		}

		onScroll();
	}

	root.addEventListener( 'click', function ( e ) {
		var tab = e.target.closest( '[data-ards-tab]' );

		if ( tab ) {
			showTab( tab.getAttribute( 'data-ards-tab' ) );
			window.scrollTo( 0, 0 );
		}
	} );

	$( '[role="tablist"]' ).addEventListener( 'keydown', function ( e ) {
		var tabs = $$( '[data-ards-tab]' );
		var i = tabs.findIndex( function ( t ) {
			return 'true' === t.getAttribute( 'aria-selected' );
		} );
		var j = -1;

		// راست‌به‌چپ: فلش چپ یعنی تب بعدی.
		if ( 'ArrowLeft' === e.key ) {
			j = ( i + 1 ) % tabs.length;
		} else if ( 'ArrowRight' === e.key ) {
			j = ( i - 1 + tabs.length ) % tabs.length;
		}

		if ( j >= 0 ) {
			e.preventDefault();
			showTab( tabs[ j ].getAttribute( 'data-ards-tab' ), true );
		}
	} );

	function headerOffset() {
		var head = $( '.ards-head' );

		return ( head ? head.getBoundingClientRect().bottom : 140 ) + 16;
	}

	function scrollToId( id ) {
		var el = document.getElementById( id );

		if ( ! el ) {
			return;
		}

		var card = el.closest( '[data-ards-adv-card]' );

		if ( card ) {
			openAdv( card, true );
		}

		var y = el.getBoundingClientRect().top + window.scrollY - headerOffset();

		window.scrollTo( { top: y, behavior: 'smooth' } );
	}

	root.addEventListener( 'click', function ( e ) {
		var j = e.target.closest( '[data-ards-jump]' );

		if ( j ) {
			e.preventDefault();
			scrollToId( j.getAttribute( 'data-ards-jump' ) );
		}
	} );

	root.addEventListener( 'change', function ( e ) {
		if ( e.target.matches( '[data-ards-jump-select]' ) ) {
			scrollToId( e.target.value );
		}
	} );

	var scrollQueued = false;

	function onScroll() {
		if ( scrollQueued ) {
			return;
		}

		scrollQueued = true;
		window.requestAnimationFrame( function () {
			scrollQueued = false;

			var panel = $( '[data-ards-panel]:not([hidden])' );

			if ( ! panel ) {
				return;
			}

			var cards = $$( '[data-ards-card]', panel );
			var cur = cards[ 0 ];
			var limit = headerOffset() + 40;

			cards.forEach( function ( c ) {
				if ( c.getBoundingClientRect().top < limit ) {
					cur = c;
				}
			} );

			$$( '.ards-subnav__item', panel ).forEach( function ( b ) {
				var on = cur && b.getAttribute( 'data-ards-jump' ) === cur.id;

				b.classList.toggle( 'is-on', on );

				if ( on ) {
					b.setAttribute( 'aria-current', 'true' );
				} else {
					b.removeAttribute( 'aria-current' );
				}
			} );

			var sel = $( '[data-ards-jump-select]', panel );

			if ( sel && cur ) {
				sel.value = cur.id;
			}
		} );
	}

	window.addEventListener( 'scroll', onScroll, { passive: true } );

	/* ---------------------------------------------------------------- بخش پیشرفته */

	function openAdv( card, open ) {
		var btn = card.querySelector( '[data-ards-adv]' );
		var body = card.querySelector( '[data-ards-body]' );

		body.hidden = ! open;
		btn.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		btn.textContent = open ? 'بستن' : 'نمایش ' + fa( btn.getAttribute( 'data-count' ) ) + ' گزینه';
	}

	root.addEventListener( 'click', function ( e ) {
		var b = e.target.closest( '[data-ards-adv]' );

		if ( b ) {
			var card = b.closest( '[data-ards-card]' );

			openAdv( card, card.querySelector( '[data-ards-body]' ).hidden );
		}
	} );

	/* ---------------------------------------------------------------- عدد، مبلغ، رنگ */

	function validateNum( input ) {
		var v = numRaw( input.value );
		var wrap = input.closest( '.ards-step' );
		var err = input.closest( '[data-ards-field]' ).querySelector( '.ards-err' );
		var msg = '';

		if ( '' !== v ) {
			var n = Number( v );
			var min = input.getAttribute( 'data-min' );
			var max = input.getAttribute( 'data-max' );

			if ( isNaN( n ) ) {
				msg = 'فقط عدد وارد کنید.';
			} else if ( null !== min && n < Number( min ) ) {
				msg = 'کمترین مقدار مجاز ' + fa( min ) + ' است.';
			} else if ( null !== max && n > Number( max ) ) {
				msg = 'بیشترین مقدار مجاز ' + fa( max ) + ' است.';
			}
		}

		wrap.classList.toggle( 'is-bad', !! msg );
		input.setAttribute( 'aria-invalid', msg ? 'true' : 'false' );
		err.hidden = ! msg;
		err.textContent = msg;
	}

	function moneyWords( input ) {
		var w = input.closest( '[data-ards-field]' ).querySelector( '[data-ards-words]' );
		var v = moneyRaw( input.value );

		w.textContent = Number( v ) ? 'معادل ' + shortAmt( v ) : ( input.getAttribute( 'data-zero' ) ? 'صفر' : '' );
	}

	function setColor( wrap, hex, fromPicker ) {
		var text = wrap.querySelector( '[data-ards-hex]' );
		var ok = /^#[0-9A-F]{6}$/i.test( hex );

		if ( fromPicker ) {
			text.value = hex.toUpperCase();
		}

		wrap.classList.toggle( 'is-bad', ! ok );

		if ( ok ) {
			wrap.querySelector( '[data-ards-swatch]' ).style.background = hex;
			wrap.querySelector( '[data-ards-picker]' ).value = hex.toLowerCase();
			document.documentElement.style.setProperty( '--ard-' + text.getAttribute( 'data-key' ).replace( /_/g, '-' ), hex );
		}

		wrap.querySelector( '[data-ards-color-reset]' ).hidden = text.value.toUpperCase() === wrap.getAttribute( 'data-default' );
	}

	root.addEventListener( 'input', function ( e ) {
		var t = e.target;

		if ( t.matches( '[data-ards-num]' ) ) {
			var raw = numRaw( t.value );

			t.value = fa( raw ).replace( '.', '٫' );
			validateNum( t );
		}

		if ( t.matches( '[data-ards-money]' ) ) {
			t.value = moneyShow( t.value );
			moneyWords( t );
		}

		if ( t.matches( '[data-ards-hex]' ) ) {
			var v = t.value.toUpperCase().replace( /[^#0-9A-F]/g, '' );

			if ( v && '#' !== v[ 0 ] ) {
				v = '#' + v;
			}

			t.value = v;
			setColor( t.closest( '[data-ards-field]' ), v, false );
		}

		if ( t.matches( '[data-ards-picker]' ) ) {
			setColor( t.closest( '[data-ards-field]' ), t.value, true );
		}

		if ( t.matches( '[data-ards-ev-text]' ) ) {
			syncEvent( t.closest( '[data-ards-ev]' ) );
		}

		livePreview( t );
		refresh();
	} );

	root.addEventListener( 'change', function ( e ) {
		var t = e.target;

		if ( t.matches( '[data-ards-icon]' ) ) {
			iconPreview( t );
		}

		livePreview( t );
		refresh();
	} );

	root.addEventListener( 'click', function ( e ) {
		var b = e.target.closest( '[data-ards-step]' );

		if ( b ) {
			var input = b.parentNode.querySelector( '[data-ards-num]' );
			var step = Number( input.getAttribute( 'data-step' ) ) || 1;
			var min = input.getAttribute( 'data-min' );
			var max = input.getAttribute( 'data-max' );
			var n = ( Number( numRaw( input.value ) ) || 0 ) + Number( b.getAttribute( 'data-ards-step' ) ) * step;

			if ( null !== min ) {
				n = Math.max( Number( min ), n );
			} else if ( n < 0 ) {
				n = 0;
			}

			if ( null !== max ) {
				n = Math.min( Number( max ), n );
			}

			n = Math.round( n * 100 ) / 100;
			input.value = fa( n ).replace( '.', '٫' );
			validateNum( input );
			livePreview( input );
			refresh();
		}

		var r = e.target.closest( '[data-ards-color-reset]' );

		if ( r ) {
			var wrap = r.closest( '[data-ards-field]' );

			wrap.querySelector( '[data-ards-hex]' ).value = wrap.getAttribute( 'data-default' );
			setColor( wrap, wrap.getAttribute( 'data-default' ), false );
			refresh();
		}
	} );

	function iconPreview( select ) {
		var pv = select.closest( '[data-ards-field]' ).querySelector( '[data-ards-icon-pv] path' );
		var paths = CFG.iconPaths || {};

		if ( pv && paths[ select.value ] ) {
			pv.setAttribute( 'd', paths[ select.value ] );
		}
	}

	/* ---------------------------------------------------------------- پیش‌نمایش زنده */

	function paletteName( key ) {
		return ( CFG.palette || 'ardal_palette' ) + '[' + key + ']';
	}

	function livePreview( t ) {
		if ( ! t || ! t.name || 0 !== t.name.indexOf( CFG.palette || 'ardal_palette' ) ) {
			return;
		}

		var key = t.name.slice( t.name.indexOf( '[' ) + 1, -1 );
		var rootStyle = document.documentElement.style;

		if ( 'radius' === key ) {
			rootStyle.setProperty( '--ard-radius', ( Number( numRaw( t.value ) ) || 0 ) + 'px' );
		}

		if ( 'toman_size' === key ) {
			var pv = $( '[data-ards-preview]' );

			if ( pv ) {
				pv.style.setProperty( '--pv-sym', ( Number( numRaw( t.value ) ) || 170 ) / 100 );
			}
		}

		if ( 'toman_symbol' === key || 'persian_digits' === key ) {
			previewText();
		}
	}

	function previewText() {
		var pv = $( '[data-ards-preview]' );

		if ( ! pv ) {
			return;
		}

		var digits = '1' === readName( paletteName( 'persian_digits' ) );
		var sym = '1' === readName( paletteName( 'toman_symbol' ) );

		$$( '[data-pv-num]', pv ).forEach( function ( s ) {
			var v = s.getAttribute( 'data-pv-num' );

			s.textContent = digits ? fa( v ).replace( /,/g, '٬' ) : v;
		} );

		pv.querySelector( '[data-pv-word]' ).hidden = sym;
		pv.querySelector( '[data-pv-sym]' ).hidden = ! sym;
		pv.style.setProperty( '--pv-sym', ( Number( readName( paletteName( 'toman_size' ) ) ) || 170 ) / 100 );
	}

	/* ---------------------------------------------------------------- پالت‌های آماده */

	function syncPresets() {
		var presets = CFG.presets || {};

		$$( '[data-ards-preset]' ).forEach( function ( b ) {
			var vals = presets[ b.getAttribute( 'data-ards-preset' ) ] || {};
			var keys = Object.keys( vals ).filter( function ( k ) {
				return /^#/.test( String( vals[ k ] ) );
			} );
			var on = keys.length > 0 && keys.every( function ( k ) {
				return readName( paletteName( k ) ) === String( vals[ k ] ).toUpperCase();
			} );

			b.classList.toggle( 'is-active', on );
			b.setAttribute( 'aria-pressed', on ? 'true' : 'false' );
		} );
	}

	function applyColors( vals ) {
		Object.keys( vals ).forEach( function ( k ) {
			var input = form.querySelector( '[name="' + paletteName( k ) + '"][data-ards-hex]' );

			if ( input && /^#[0-9A-F]{6}$/i.test( vals[ k ] ) ) {
				input.value = String( vals[ k ] ).toUpperCase();
				setColor( input.closest( '[data-ards-field]' ), input.value, false );
			}
		} );

		refresh();
	}

	root.addEventListener( 'click', function ( e ) {
		var b = e.target.closest( '[data-ards-preset]' );

		if ( b ) {
			applyColors( ( CFG.presets || {} )[ b.getAttribute( 'data-ards-preset' ) ] || {} );
			toast( 'پالت «' + b.querySelector( '.ards-preset__name' ).firstChild.textContent.trim() + '» اعمال شد؛ برای ثبت ذخیره کنید', 'info' );
		}

		if ( e.target.closest( '[data-ards-reset-palette]' ) ) {
			var m = $( '[data-ards-confirm]' );

			m.hidden = false;
			m.querySelector( '[data-ards-confirm-no]' ).focus();
		}

		if ( e.target.closest( '[data-ards-confirm-no]' ) || e.target.matches( '[data-ards-confirm]' ) ) {
			$( '[data-ards-confirm]' ).hidden = true;
		}

		if ( e.target.closest( '[data-ards-confirm-yes]' ) ) {
			$( '[data-ards-confirm]' ).hidden = true;
			applyColors( CFG.defaults || {} );
			toast( 'رنگ‌ها به پالت دیزاین برگشت؛ برای ثبت ذخیره کنید', 'info' );
		}
	} );

	/* ---------------------------------------------------------------- منوی پنل */

	function menuApply( list ) {
		var rows = $$( '[data-ards-menu-row]', list );

		rows.forEach( function ( row, i ) {
			var v = String( ( i + 1 ) * 10 );

			row.querySelector( '[data-ards-menu-order]' ).value = v;
			row.querySelector( '[data-ards-menu-ord]' ).textContent = fa( v );

			var mv = row.querySelectorAll( '[data-ards-menu-move]' );

			mv[ 0 ].disabled = 0 === i;
			mv[ 1 ].disabled = rows.length - 1 === i;
		} );

		refresh();
	}

	root.addEventListener( 'click', function ( e ) {
		var b = e.target.closest( '[data-ards-menu-move]' );

		if ( ! b ) {
			return;
		}

		var row = b.closest( '[data-ards-menu-row]' );
		var list = row.parentNode;

		if ( '-1' === b.getAttribute( 'data-ards-menu-move' ) ) {
			var prev = row.previousElementSibling;

			if ( prev && prev.hasAttribute( 'data-ards-menu-row' ) ) {
				list.insertBefore( row, prev );
			}
		} else if ( row.nextElementSibling ) {
			list.insertBefore( row.nextElementSibling, row );
		}

		menuApply( list );
		b.focus();
	} );

	var dragRow = null;

	root.addEventListener( 'dragstart', function ( e ) {
		var row = e.target.closest && e.target.closest( '[data-ards-menu-row]' );

		// کشیدن از داخل فیلد متن، متن را جابه‌جا می‌کند نه ردیف را.
		if ( ! row || e.target.matches( 'input' ) ) {
			return;
		}

		dragRow = row;
		row.classList.add( 'is-drag' );

		if ( e.dataTransfer ) {
			e.dataTransfer.effectAllowed = 'move';

			try {
				e.dataTransfer.setData( 'text/plain', '' );
			} catch ( err ) {}
		}
	} );

	root.addEventListener( 'dragover', function ( e ) {
		var row = dragRow && e.target.closest && e.target.closest( '[data-ards-menu-row]' );

		if ( ! row ) {
			return;
		}

		e.preventDefault();
		$$( '.ards-menu__row.is-over' ).forEach( function ( r ) {
			r.classList.remove( 'is-over' );
		} );

		if ( row !== dragRow ) {
			row.classList.add( 'is-over' );
		}
	} );

	root.addEventListener( 'drop', function ( e ) {
		var row = dragRow && e.target.closest && e.target.closest( '[data-ards-menu-row]' );

		if ( ! row ) {
			return;
		}

		e.preventDefault();

		if ( row !== dragRow ) {
			var list = row.parentNode;
			var rows = $$( '[data-ards-menu-row]', list );

			if ( rows.indexOf( dragRow ) < rows.indexOf( row ) ) {
				list.insertBefore( dragRow, row.nextElementSibling );
			} else {
				list.insertBefore( dragRow, row );
			}

			menuApply( list );
		}
	} );

	root.addEventListener( 'dragend', function () {
		if ( dragRow ) {
			dragRow.classList.remove( 'is-drag' );
		}

		$$( '.ards-menu__row.is-over' ).forEach( function ( r ) {
			r.classList.remove( 'is-over' );
		} );
		dragRow = null;
	} );

	/* ---------------------------------------------------------------- پیامک */

	function syncEvent( ev ) {
		var on = ev.querySelector( '[data-ards-ev-on]' ).checked;
		var pattern = ev.querySelector( '[data-ards-ev-pattern]' ).value.trim();
		var text = ev.querySelector( '[data-ards-ev-text]' ).value;
		var mode = ev.querySelector( '[data-ards-ev-mode]' );
		var len = text.length;
		var parts = len <= 70 ? 1 : Math.ceil( len / 67 );

		mode.className = 'ards-ev__mode' + ( on ? ( pattern ? ' is-pattern' : ' is-text' ) : '' );
		mode.textContent = ! on ? 'خاموش' : ( pattern ? 'پترن' : 'متن آزاد' );
		ev.querySelector( '[data-ards-uses="pattern"]' ).hidden = ! pattern;
		ev.querySelector( '[data-ards-uses="text"]' ).hidden = !! pattern;
		ev.querySelector( '[data-ards-ev-count]' ).textContent = fa( len ) + ' نویسه · ' + fa( parts ) + ' پیامک';
	}

	root.addEventListener( 'click', function ( e ) {
		var b = e.target.closest( '[data-ards-ev-open]' );

		if ( b ) {
			var ev = b.closest( '[data-ards-ev]' );
			var open = ! ev.classList.contains( 'is-open' );

			ev.classList.toggle( 'is-open', open );
			ev.querySelector( '[data-ards-ev-body]' ).hidden = ! open;
			b.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		}

		var send = e.target.closest( '[data-ards-sms-send]' );

		if ( send ) {
			smsTest( send );
		}
	} );

	function smsTest( button ) {
		var wrap = button.closest( '[data-ards-sms-test]' );
		var ev = wrap.getAttribute( 'data-ards-sms-test' );
		var out = wrap.querySelector( '[data-ards-sms-out]' );
		var phone = en( wrap.querySelector( '[data-ards-sms-phone]' ).value ).replace( /[\s-]/g, '' );
		var label = button.querySelector( '[data-ards-sms-label]' );
		var spin = button.querySelector( '.ards-spin' );
		var base = ( CFG.wallet || 'ardal_wallet' ) + '[sms_' + ev;

		out.className = 'ards-smstest__out';

		if ( ! /^09\d{9}$/.test( phone ) ) {
			out.classList.add( 'is-bad' );
			out.textContent = 'شمارهٔ موبایل معتبر نیست؛ مثل ۰۹۱۲۱۲۳۴۵۶۷';

			return;
		}

		var body = new window.FormData();

		body.append( 'action', 'ardal_wallet_sms_test' );
		body.append( 'nonce', CFG.smsNonce || '' );
		body.append( 'event', ev );
		body.append( 'phone', phone );
		body.append( '_pattern', readName( base + '_pattern]' ) );
		body.append( '_vars', readName( base + '_vars]' ) );
		body.append( '_text', readName( base + '_text]' ) );

		button.disabled = true;
		spin.hidden = false;
		label.textContent = 'در حال ارسال…';
		out.textContent = '';

		window.fetch( CFG.ajax, { method: 'POST', body: body, credentials: 'same-origin' } )
			.then( function ( r ) {
				return r.json();
			} )
			.then( function ( res ) {
				var ok = res && res.success;

				out.classList.add( ok ? 'is-ok' : 'is-bad' );
				out.textContent = ( ok ? '✓ ' : '' ) + ( ( res && res.data && res.data.message ) || 'پاسخ نامعتبر' );
			} )
			.catch( function () {
				out.classList.add( 'is-bad' );
				out.textContent = 'ارتباط با سرور ممکن نشد.';
			} )
			.then( function () {
				button.disabled = false;
				spin.hidden = true;
				label.textContent = 'ارسال پیامک آزمایشی';
			} );
	}

	/* ---------------------------------------------------------------- جای‌گذارها */

	var lastFocus = null;

	root.addEventListener( 'focusin', function ( e ) {
		if ( e.target.matches( 'input[type="text"], textarea' ) ) {
			lastFocus = e.target;
		}
	} );

	root.addEventListener( 'mousedown', function ( e ) {
		// فوکوس روی فیلد بماند تا جای‌گذار سر جای مکان‌نما برود.
		if ( e.target.closest( '[data-ards-token]' ) ) {
			e.preventDefault();
		}
	} );

	root.addEventListener( 'click', function ( e ) {
		var b = e.target.closest( '[data-ards-token]' );

		if ( ! b ) {
			return;
		}

		var target = document.getElementById( b.getAttribute( 'data-target' ) );
		var alt = b.getAttribute( 'data-alt' ) ? document.getElementById( b.getAttribute( 'data-alt' ) ) : null;
		var el = lastFocus && ( lastFocus === target || lastFocus === alt ) ? lastFocus : target;
		var tk = b.getAttribute( 'data-ards-token' );
		var a = 'number' === typeof el.selectionStart ? el.selectionStart : el.value.length;
		var z = 'number' === typeof el.selectionEnd ? el.selectionEnd : el.value.length;

		el.value = el.value.slice( 0, a ) + tk + el.value.slice( z );
		el.focus();

		try {
			el.setSelectionRange( a + tk.length, a + tk.length );
		} catch ( err ) {}

		el.dispatchEvent( new Event( 'input', { bubbles: true } ) );
	} );

	/* ---------------------------------------------------------------- کپی شورت‌کد */

	root.addEventListener( 'click', function ( e ) {
		var b = e.target.closest( '[data-ards-copy]' );

		if ( ! b ) {
			return;
		}

		var code = b.getAttribute( 'data-ards-copy' );
		var done = function () {
			b.classList.add( 'is-copied' );
			b.textContent = 'کپی شد ✓';
			window.setTimeout( function () {
				b.classList.remove( 'is-copied' );
				b.textContent = 'کپی';
			}, 1800 );
		};

		if ( window.navigator.clipboard ) {
			window.navigator.clipboard.writeText( code ).then( done, done );
		} else {
			done();
		}
	} );

	/* ---------------------------------------------------------------- تصویر رسانه */

	root.addEventListener( 'click', function ( e ) {
		var pick = e.target.closest( '[data-ards-media-pick]' );
		var clear = e.target.closest( '[data-ards-media-clear]' );
		var wrap = ( pick || clear ) && ( pick || clear ).closest( '[data-ards-media]' );

		if ( ! wrap ) {
			return;
		}

		e.preventDefault();

		var set = function ( url ) {
			wrap.querySelector( '[data-ards-media-input]' ).value = url;
			wrap.querySelector( '[data-ards-media-img]' ).src = url;
			wrap.querySelector( '[data-ards-media-url]' ).textContent = url;
			wrap.querySelector( '.ards-media__empty' ).hidden = !! url;
			wrap.querySelector( '.ards-media__has' ).hidden = ! url;
			refresh();
		};

		if ( clear ) {
			set( '' );

			return;
		}

		if ( ! window.wp || ! window.wp.media ) {
			return;
		}

		var frame = window.wp.media( { title: 'انتخاب تصویر', button: { text: 'استفاده از این تصویر' }, library: { type: 'image' }, multiple: false } );

		frame.on( 'select', function () {
			var a = frame.state().get( 'selection' ).first().toJSON();

			set( a.url || '' );
		} );
		frame.open();
	} );

	/* ---------------------------------------------------------------- فیلتر ویجت‌ها */

	root.addEventListener( 'input', function ( e ) {
		if ( ! e.target.matches( '[data-ards-wfilter]' ) ) {
			return;
		}

		var q = e.target.value.trim();
		var shown = 0;

		$$( '[data-ards-wlist] span' ).forEach( function ( s ) {
			var on = ! q || s.textContent.indexOf( q ) >= 0;

			s.hidden = ! on;
			shown += on ? 1 : 0;
		} );

		$( '[data-ards-wcount]' ).textContent = fa( shown ) + ' ویجت';
	} );

	/* ---------------------------------------------------------------- جست‌وجو */

	var index = [];

	function buildIndex() {
		$$( '[data-ards-panel]' ).forEach( function ( panel ) {
			var tab = panel.getAttribute( 'data-ards-panel' );
			var tabTitle = panel.getAttribute( 'data-title' );

			$$( '[data-ards-card]', panel ).forEach( function ( card ) {
				var path = tabTitle + ' › ' + card.getAttribute( 'data-short' );

				index.push( { label: card.getAttribute( 'data-title' ), path: tabTitle, tab: tab, id: card.id } );

				$$( '[data-ards-field]', card ).forEach( function ( f ) {
					index.push( { label: f.getAttribute( 'data-label' ), path: path, tab: tab, id: f.id, field: true } );
				} );

				$$( '[data-ards-ev]', card ).forEach( function ( ev ) {
					index.push( { label: ev.getAttribute( 'data-label' ), path: path, tab: tab, id: ev.id, ev: true } );
				} );

				$$( '[data-ards-menu-row]', card ).forEach( function ( row ) {
					index.push( { label: 'منو: ' + row.getAttribute( 'data-default' ), path: path, tab: tab, id: card.id } );
				} );
			} );
		} );
	}

	buildIndex();

	var search = $( '[data-ards-search]' );
	var results = $( '[data-ards-results]' );
	var found = [];
	var hlTimer;

	function goTo( r ) {
		search.value = '';
		results.hidden = true;
		showTab( r.tab );

		window.setTimeout( function () {
			var el = document.getElementById( r.id );

			if ( ! el ) {
				return;
			}

			var card = el.closest( '[data-ards-card]' );
			var master = card && card.querySelector( '[data-ards-master]' );

			// فیلد داخل کارتِ خاموش یا فیلد وابستهٔ پنهان دیده نمی‌شود؛ خود کارت نشان داده می‌شود.
			if ( ( master && ! master.checked && ! el.matches( '[data-ards-card]' ) ) || el.classList.contains( 'is-hidden' ) ) {
				el = card;
			}

			if ( r.ev ) {
				el.classList.add( 'is-open' );
				el.querySelector( '[data-ards-ev-body]' ).hidden = false;
				el.querySelector( '[data-ards-ev-open]' ).setAttribute( 'aria-expanded', 'true' );
			}

			scrollToId( el.id );

			if ( r.field || r.ev ) {
				$$( '.is-hl' ).forEach( function ( x ) {
					x.classList.remove( 'is-hl' );
				} );
				el.classList.add( 'is-hl' );
				window.clearTimeout( hlTimer );
				hlTimer = window.setTimeout( function () {
					el.classList.remove( 'is-hl' );
				}, 2600 );

				var input = el.querySelector( 'input:not([type="hidden"]), textarea, select' );

				if ( input ) {
					window.setTimeout( function () {
						input.focus( { preventScroll: true } );
					}, 400 );
				}
			}
		}, 40 );
	}

	search.addEventListener( 'input', function () {
		var q = search.value.trim();

		results.innerHTML = '';
		found = [];

		if ( ! q ) {
			results.hidden = true;

			return;
		}

		found = index.filter( function ( r ) {
			return r.label.indexOf( q ) >= 0 || r.path.indexOf( q ) >= 0;
		} ).slice( 0, 8 );

		if ( ! found.length ) {
			results.innerHTML = '<div class="ards-search__none">تنظیمی با این عبارت پیدا نشد.</div>';
		}

		found.forEach( function ( r, i ) {
			var b = document.createElement( 'button' );

			b.type = 'button';
			b.className = 'ards-search__item' + ( 0 === i ? ' is-on' : '' );
			b.setAttribute( 'role', 'option' );
			b.innerHTML = '<b></b><span></span>';
			b.firstChild.textContent = r.label;
			b.lastChild.textContent = r.path;
			b.addEventListener( 'click', function () {
				goTo( r );
			} );
			results.appendChild( b );
		} );

		results.hidden = false;
	} );

	search.addEventListener( 'keydown', function ( e ) {
		if ( 'Enter' === e.key ) {
			e.preventDefault();

			if ( found[ 0 ] ) {
				goTo( found[ 0 ] );
			}
		}

		if ( 'Escape' === e.key ) {
			search.value = '';
			results.hidden = true;
		}
	} );

	document.addEventListener( 'click', function ( e ) {
		if ( ! e.target.closest( '.ards-search' ) ) {
			results.hidden = true;
		}
	} );

	document.addEventListener( 'keydown', function ( e ) {
		var tag = e.target.tagName;

		if ( '/' === e.key && ! /INPUT|TEXTAREA|SELECT/.test( tag ) ) {
			e.preventDefault();
			search.focus();
		}

		if ( ( e.ctrlKey || e.metaKey ) && 's' === e.key.toLowerCase() ) {
			e.preventDefault();
			submit();
		}

		if ( 'Escape' === e.key && ! $( '[data-ards-confirm]' ).hidden ) {
			$( '[data-ards-confirm]' ).hidden = true;
		}
	} );

	/* ---------------------------------------------------------------- ذخیره و بازگردانی */

	var saving = false;

	function toast( text, kind ) {
		var t = $( '[data-ards-toast]' );

		t.classList.toggle( 'is-info', 'info' === kind );
		t.querySelector( '.ards-toast__icon' ).textContent = 'info' === kind ? 'i' : '✓';
		$( '[data-ards-toast-text]' ).textContent = text;
		t.hidden = false;
		window.clearTimeout( toast.timer );
		toast.timer = window.setTimeout( function () {
			t.hidden = true;
		}, 2600 );
	}

	function submit() {
		if ( saving || ! names.some( isDirty ) ) {
			return;
		}

		if ( form.requestSubmit ) {
			form.requestSubmit();
		} else {
			form.submit();
		}
	}

	form.addEventListener( 'submit', function () {
		saving = true;

		// اعداد فارسیِ نمایشی پیش از ارسال به همان مقدار خام برمی‌گردند.
		$$( '[data-ards-num]', form ).forEach( function ( i ) {
			i.value = numRaw( i.value );
		} );

		$$( '[data-ards-money]', form ).forEach( function ( i ) {
			i.value = moneyRaw( i.value );
		} );

		var save = $( '[data-ards-save]' );

		save.disabled = true;
		save.innerHTML = '<span class="ards-spin"></span>در حال ذخیره';
		$( '[data-ards-status-text]' ).textContent = 'در حال ذخیره…';
		window.removeEventListener( 'beforeunload', guard );
		refresh();
	} );

	$( '[data-ards-revert]' ).addEventListener( 'click', function () {
		names.forEach( function ( n ) {
			if ( isDirty( n ) ) {
				writeName( n, saved[ n ] );
			}
		} );

		// رنگ‌ها، منو و پیش‌نمایش از روی مقدارهای برگشته دوباره ساخته می‌شوند.
		$$( '[data-ards-hex]' ).forEach( function ( i ) {
			setColor( i.closest( '[data-ards-field]' ), i.value, false );
		} );

		$$( '[data-ards-menu]' ).forEach( function ( list ) {
			$$( '[data-ards-menu-row]', list )
				.sort( function ( a, b ) {
					return Number( a.querySelector( '[data-ards-menu-order]' ).value ) - Number( b.querySelector( '[data-ards-menu-order]' ).value );
				} )
				.forEach( function ( row ) {
					list.appendChild( row );
				} );

			$$( '[data-ards-menu-row]', list ).forEach( function ( row ) {
				row.querySelector( '[data-ards-menu-ord]' ).textContent = fa( row.querySelector( '[data-ards-menu-order]' ).value );
			} );
		} );

		$$( '[data-ards-num]' ).forEach( validateNum );
		$$( '[data-ards-money]' ).forEach( moneyWords );
		$$( '[data-ards-icon]' ).forEach( iconPreview );
		$$( '[data-ards-media]' ).forEach( function ( w ) {
			var url = w.querySelector( '[data-ards-media-input]' ).value;

			w.querySelector( '[data-ards-media-img]' ).src = url;
			w.querySelector( '[data-ards-media-url]' ).textContent = url;
			w.querySelector( '.ards-media__empty' ).hidden = !! url;
			w.querySelector( '.ards-media__has' ).hidden = ! url;
		} );
		previewText();
		refresh();
		toast( 'تغییرات به آخرین حالت ذخیره‌شده برگشت', 'info' );
	} );

	function guard( e ) {
		if ( names.some( isDirty ) ) {
			e.preventDefault();
			e.returnValue = '';
		}
	}

	window.addEventListener( 'beforeunload', guard );

	/* ---------------------------------------------------------------- راه‌اندازی */

	// نمایش فارسی اعداد و مبلغ‌ها؛ مقدار ذخیره‌شده (خام) پیش از این خوانده شده.
	$$( '[data-ards-num]' ).forEach( function ( i ) {
		i.value = fa( numRaw( i.value ) ).replace( '.', '٫' );
	} );

	$$( '[data-ards-money]' ).forEach( function ( i ) {
		i.value = moneyShow( i.value );
		moneyWords( i );
	} );

	$$( '[data-ards-hex]' ).forEach( function ( i ) {
		var w = i.closest( '[data-ards-field]' );

		w.querySelector( '[data-ards-color-reset]' ).hidden = i.value.toUpperCase() === w.getAttribute( 'data-default' );
	} );

	snapshot();
	previewText();

	var start = ( window.location.hash || '' ).replace( '#', '' );

	if ( ! root.querySelector( '[data-ards-tab="' + start + '"]' ) ) {
		try {
			start = window.sessionStorage.getItem( KEY ) || '';
		} catch ( e ) {
			start = '';
		}
	}

	if ( root.querySelector( '[data-ards-tab="' + start + '"]' ) ) {
		showTab( start );
	}

	doRefresh();
	onScroll();

	if ( root.hasAttribute( 'data-ards-saved' ) ) {
		toast( 'تنظیمات ذخیره شد' );
	}
}() );
