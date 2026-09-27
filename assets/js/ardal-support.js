/**
 * دکمهٔ شناور پشتیبانی.
 *
 * باز و بسته کردن پنل، بستن با Escape و کلیک بیرون، باز شدن خودکار پس از چند
 * ثانیه (با به‌خاطر سپردن بستنِ کاربر) و قفل اسکرول در حالت موبایل.
 */
( function () {
	'use strict';

	var STORE_PREFIX = 'ardal-sp-closed:';
	var MOBILE_QUERY = '(max-width: 600px)';

	var instances = [];

	/**
	 * خواندن پیکربندی از data-attribute.
	 *
	 * @param {HTMLElement} root
	 * @return {Object}
	 */
	function readConfig( root ) {
		var raw = root.getAttribute( 'data-ard-support' );

		if ( ! raw ) {
			return {};
		}

		try {
			return JSON.parse( raw ) || {};
		} catch ( e ) {
			return {};
		}
	}

	function isMobile() {
		return !! ( window.matchMedia && window.matchMedia( MOBILE_QUERY ).matches );
	}

	/**
	 * خواندن و نوشتن امن در sessionStorage (در حالت ناشناس خطا می‌دهد).
	 *
	 * @param {string} key
	 * @param {string} [value] ندادنش یعنی فقط خواندن.
	 * @return {string|null}
	 */
	function store( key, value ) {
		try {
			if ( undefined === value ) {
				return window.sessionStorage.getItem( key );
			}

			window.sessionStorage.setItem( key, value );
		} catch ( e ) {
			return null;
		}

		return value;
	}

	/**
	 * یک نمونهٔ پشتیبانی.
	 *
	 * @param {HTMLElement} root
	 */
	function Support( root ) {
		this.root = root;
		this.config = readConfig( root );
		this.launcher = root.querySelector( '.ard-sp__launcher' );
		this.panel = root.querySelector( '.ard-sp__panel' );
		this.isOpen = false;
		this.autoTimer = null;

		if ( ! this.launcher || ! this.panel ) {
			return;
		}

		this.bind();
		this.scheduleAutoOpen();
	}

	Support.prototype.bind = function () {
		var self = this;

		this.launcher.addEventListener( 'click', function ( event ) {
			event.preventDefault();
			self.toggle();
		} );

		// دکمهٔ بستن و پردهٔ پشت پنل.
		Array.prototype.forEach.call( this.root.querySelectorAll( '[data-ard-sp-close]' ), function ( el ) {
			el.addEventListener( 'click', function ( event ) {
				event.preventDefault();
				self.close( true );
			} );
		} );

		// انتخاب یک راه ارتباطی پنل را می‌بندد. ردیف‌های بدون پیوند مستثنا هستند.
		Array.prototype.forEach.call( this.panel.querySelectorAll( 'a.ard-sp__item' ), function ( link ) {
			link.addEventListener( 'click', function () {
				self.close();
			} );
		} );

		this.onKeydown = function ( event ) {
			if ( ! self.isOpen ) {
				return;
			}

			if ( 'Escape' === event.key || 'Esc' === event.key || 27 === event.keyCode ) {
				self.close( true );
				self.launcher.focus();
			}
		};

		document.addEventListener( 'keydown', this.onKeydown );

		this.onDocClick = function ( event ) {
			if ( ! self.isOpen || false === self.config.closeOutside ) {
				return;
			}

			if ( ! self.root.contains( event.target ) ) {
				self.close( true );
			}
		};

		document.addEventListener( 'click', this.onDocClick );

		// نخستین اسکرول کاربر، باز شدن خودکار را لغو می‌کند تا مزاحم نشود.
		this.cancelAuto = function () {
			self.clearAutoTimer();
		};

		window.addEventListener( 'scroll', this.cancelAuto, { passive: true, once: true } );
	};

	Support.prototype.scheduleAutoOpen = function () {
		var self = this;
		var delay = parseInt( this.config.autoOpen, 10 );

		if ( ! delay || delay <= 0 ) {
			return;
		}

		// اگر کاربر در همین نشست یک‌بار بسته باشد، دیگر خودکار باز نمی‌شود.
		if ( false !== this.config.rememberClose && '1' === store( STORE_PREFIX + this.storeKey() ) ) {
			return;
		}

		this.autoTimer = window.setTimeout( function () {
			if ( ! self.isOpen ) {
				self.open();
			}
		}, delay * 1000 );
	};

	/**
	 * کلید ذخیره‌سازی. مسیر صفحه در آن نیست، چون خواستِ کاربر «دیگر نشانم نده»
	 * برای کل نشست است، نه فقط همین صفحه.
	 *
	 * @return {string}
	 */
	Support.prototype.storeKey = function () {
		return this.config.key || this.root.id || 'default';
	};

	Support.prototype.clearAutoTimer = function () {
		if ( this.autoTimer ) {
			window.clearTimeout( this.autoTimer );
			this.autoTimer = null;
		}
	};

	Support.prototype.toggle = function () {
		if ( this.isOpen ) {
			this.close( true );
		} else {
			this.open();
		}
	};

	Support.prototype.open = function () {
		var self = this;

		if ( this.isOpen ) {
			return;
		}

		this.clearAutoTimer();

		// هم‌زمان بیش از یک پنل باز نماند.
		closeAll( this );

		this.isOpen = true;
		this.root.classList.add( 'is-open' );
		this.panel.setAttribute( 'aria-hidden', 'false' );
		this.launcher.setAttribute( 'aria-expanded', 'true' );

		if ( isMobile() ) {
			this.lockScroll();
		}

		// فوکوس روی نخستین راه ارتباطی، پس از پایان انیمیشن ورود.
		window.setTimeout( function () {
			if ( ! self.isOpen ) {
				return;
			}

			/*
			 * span های بدون پیوند فوکوس‌پذیر نیستند؛ اگر هیچ پیوندی نبود،
			 * دکمهٔ بستن جای امنی برای فوکوس است تا Tab داخل پنل بماند.
			 */
			var first = self.panel.querySelector( 'a.ard-sp__item' ) ||
				self.panel.querySelector( '.ard-sp__close' );

			if ( first ) {
				first.focus( { preventScroll: true } );
			}
		}, 320 );
	};

	/**
	 * @param {boolean} [byUser] اگر کاربر خودش بسته باشد، در نشست به یاد می‌ماند.
	 */
	Support.prototype.close = function ( byUser ) {
		if ( ! this.isOpen ) {
			return;
		}

		this.isOpen = false;
		this.root.classList.remove( 'is-open' );
		this.panel.setAttribute( 'aria-hidden', 'true' );
		this.launcher.setAttribute( 'aria-expanded', 'false' );
		this.unlockScroll();

		if ( byUser && false !== this.config.rememberClose ) {
			store( STORE_PREFIX + this.storeKey(), '1' );
		}
	};

	Support.prototype.lockScroll = function () {
		if ( document.body.classList.contains( 'ard-sp-locked' ) ) {
			return;
		}

		document.body.classList.add( 'ard-sp-locked' );
	};

	Support.prototype.unlockScroll = function () {
		document.body.classList.remove( 'ard-sp-locked' );
	};

	Support.prototype.destroy = function () {
		this.clearAutoTimer();
		this.unlockScroll();

		if ( this.onKeydown ) {
			document.removeEventListener( 'keydown', this.onKeydown );
		}

		if ( this.onDocClick ) {
			document.removeEventListener( 'click', this.onDocClick );
		}

		if ( this.cancelAuto ) {
			window.removeEventListener( 'scroll', this.cancelAuto );
		}

		var index = instances.indexOf( this );

		if ( index > -1 ) {
			instances.splice( index, 1 );
		}
	};

	/**
	 * بستن همهٔ پنل‌ها به‌جز یکی.
	 *
	 * @param {Support} [except]
	 */
	function closeAll( except ) {
		instances.slice().forEach( function ( instance ) {
			if ( instance !== except ) {
				instance.close();
			}
		} );
	}

	/**
	 * راه‌اندازی همهٔ ویجت‌های داخل یک محدوده.
	 *
	 * @param {HTMLElement|Document} [scope]
	 */
	function init( scope ) {
		var container = scope || document;

		Array.prototype.forEach.call( container.querySelectorAll( '[data-ard-support]' ), function ( root ) {
			if ( root.ardSupport ) {
				root.ardSupport.destroy();
			}

			root.ardSupport = new Support( root );
			instances.push( root.ardSupport );
		} );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', function () {
			init();
		} );
	} else {
		init();
	}

	// ویرایشگر المنتور ویجت را دوباره می‌سازد.
	window.addEventListener( 'elementor/frontend/init', function () {
		if ( ! window.elementorFrontend || ! window.elementorFrontend.hooks ) {
			return;
		}

		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/ardal-support.default',
			function ( $scope ) {
				init( $scope && $scope[ 0 ] ? $scope[ 0 ] : undefined );
			}
		);
	} );
}() );
