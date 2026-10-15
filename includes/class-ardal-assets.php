<?php
/**
 * ثبت و بارگذاری استایل و اسکریپت‌های سمت کاربر.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Assets {

	/**
	 * @var bool
	 */
	private $registered = false;

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register' ), 5 );
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_enqueue_for_woo' ), 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'disable_selected_woocommerce_assets' ), 999 );
		add_action( 'wp_print_styles', array( $this, 'disable_selected_woocommerce_assets' ), 1 );
		add_action( 'wp_print_scripts', array( $this, 'disable_selected_woocommerce_assets' ), 1 );
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue' ) );
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'enqueue' ) );
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue' ) );
	}

	/**
	 * Remove only the WooCommerce front-end assets explicitly disabled in settings.
	 * This runs again immediately before output because Elementor can enqueue assets
	 * while rendering a widget, after the usual wp_enqueue_scripts hook.
	 */
	public function disable_selected_woocommerce_assets() {
		if ( is_admin() ) {
			return;
		}

		$assets = array(
			'disable_wc_general_css'      => array( 'style', 'woocommerce-general' ),
			'disable_wc_layout_css'       => array( 'style', 'woocommerce-layout' ),
			'disable_wc_smallscreen_css'  => array( 'style', 'woocommerce-smallscreen' ),
			'disable_wc_add_to_cart_js'   => array( 'script', 'wc-add-to-cart' ),
			'disable_wc_cart_fragments_js'=> array( 'script', 'wc-cart-fragments' ),
			'disable_wc_single_product_js'=> array( 'script', 'wc-single-product' ),
			'disable_wc_variation_js'     => array( 'script', 'wc-add-to-cart-variation' ),
		);

		foreach ( $assets as $setting => $asset ) {
			if ( '1' !== Ardal_Palette::get( $setting ) ) {
				continue;
			}

			if ( 'style' === $asset[0] ) {
				wp_dequeue_style( $asset[1] );
			} else {
				wp_dequeue_script( $asset[1] );
			}
		}
	}

	/**
	 * @param string $setting Palette setting key.
	 * @return bool
	 */
	public static function is_woocommerce_asset_disabled( $setting ) {
		return '1' === Ardal_Palette::get( $setting );
	}

	/** Enqueue the self-contained animated review-rating component. */
	public static function enqueue_monster_rating() {
		wp_enqueue_style( 'ardal-monster-rating' );
		wp_enqueue_script( 'ardal-monster-rating' );
	}

	/**
	 * نسخهٔ هر فایل = زمان آخرین تغییرش.
	 *
	 * قبلاً همه با ARDAL_VERSION ثبت می‌شدند و چون آن عدد با هر تغییر بالا
	 * نمی‌رفت، مرورگر و کش‌ها همان CSS قدیمی را نگه می‌داشتند و تنظیمات تازهٔ
	 * ویجت‌ها (مثل «برداشتن زمینه حلقه») بی‌اثر به نظر می‌رسید.
	 *
	 * @param string $rel مسیر نسبی فایل داخل افزونه.
	 * @return string
	 */
	private static function ver( $rel ) {
		$time = @filemtime( ARDAL_PATH . $rel ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged

		return $time ? ARDAL_VERSION . '.' . $time : ARDAL_VERSION;
	}

	/**
	 * ثبت هندل‌ها. چند بار صدا زدنش بی‌ضرر است.
	 */
	public function register() {
		if ( $this->registered ) {
			return;
		}

		$this->registered = true;

		wp_register_style( 'ardal', ARDAL_URL . 'assets/css/ardal.css', array(), self::ver( 'assets/css/ardal.css' ) );
		wp_register_script( 'ardal', ARDAL_URL . 'assets/js/ardal.js', array(), self::ver( 'assets/js/ardal.js' ), true );
		wp_register_style( 'ardal-monster-rating', ARDAL_URL . 'assets/css/ardal-monster-rating.css', array(), self::ver( 'assets/css/ardal-monster-rating.css' ) );
		wp_register_style( 'ardal-mega-menu', ARDAL_URL . 'assets/css/ardal-mega-menu.css', array( 'ardal' ), self::ver( 'assets/css/ardal-mega-menu.css' ) );
		wp_register_script( 'ardal-mega-menu', ARDAL_URL . 'assets/js/ardal-mega-menu.js', array(), self::ver( 'assets/js/ardal-mega-menu.js' ), true );
		wp_register_style( 'ardal-account', ARDAL_URL . 'assets/css/ardal-account.css', array( 'ardal' ), self::ver( 'assets/css/ardal-account.css' ) );
		wp_register_script( 'ardal-account', ARDAL_URL . 'assets/js/ardal-account.js', array(), self::ver( 'assets/js/ardal-account.js' ), true );
		wp_register_style( 'ardal-shop', ARDAL_URL . 'assets/css/ardal-shop.css', array( 'ardal' ), self::ver( 'assets/css/ardal-shop.css' ) );
		wp_register_script( 'ardal-shop', ARDAL_URL . 'assets/js/ardal-shop.js', array(), self::ver( 'assets/js/ardal-shop.js' ), true );
		wp_register_style( 'ardal-posts-slider', ARDAL_URL . 'assets/css/ardal-posts-slider.css', array( 'ardal' ), self::ver( 'assets/css/ardal-posts-slider.css' ) );
		wp_register_style( 'ardal-elano', ARDAL_URL . 'assets/css/ardal-elano.css', array( 'ardal' ), self::ver( 'assets/css/ardal-elano.css' ) );
		wp_register_style( 'ardal-elano-shop', ARDAL_URL . 'assets/css/ardal-elano-shop.css', array( 'ardal' ), self::ver( 'assets/css/ardal-elano-shop.css' ) );
		wp_register_script( 'ardal-elano', ARDAL_URL . 'assets/js/ardal-elano.js', array(), self::ver( 'assets/js/ardal-elano.js' ), true );
		wp_register_style( 'ardal-wishlist', ARDAL_URL . 'assets/css/ardal-wishlist.css', array( 'ardal' ), self::ver( 'assets/css/ardal-wishlist.css' ) );
		wp_register_style( 'ardal-wallet', ARDAL_URL . 'assets/css/ardal-wallet.css', array( 'ardal' ), self::ver( 'assets/css/ardal-wallet.css' ) );
		wp_register_script( 'ardal-wallet', ARDAL_URL . 'assets/js/ardal-wallet.js', array( 'ardal' ), self::ver( 'assets/js/ardal-wallet.js' ), true );
		wp_register_style( 'ardal-brand-rings', ARDAL_URL . 'assets/css/ardal-brand-rings.css', array( 'ardal' ), self::ver( 'assets/css/ardal-brand-rings.css' ) );
		wp_register_script( 'ardal-brand-rings', ARDAL_URL . 'assets/js/ardal-brand-rings.js', array(), self::ver( 'assets/js/ardal-brand-rings.js' ), true );
		wp_register_style( 'ardal-search', ARDAL_URL . 'assets/css/ardal-search.css', array( 'ardal' ), self::ver( 'assets/css/ardal-search.css' ) );
		wp_register_script( 'ardal-search', ARDAL_URL . 'assets/js/ardal-search.js', array(), self::ver( 'assets/js/ardal-search.js' ), true );
		wp_register_script( 'ardal-search-results', ARDAL_URL . 'assets/js/ardal-search-results.js', array(), self::ver( 'assets/js/ardal-search-results.js' ), true );
		wp_register_script( 'ardal-post-feed-tabs', ARDAL_URL . 'assets/js/ardal-post-feed-tabs.js', array(), self::ver( 'assets/js/ardal-post-feed-tabs.js' ), true );
		wp_register_script( 'ardal-post-comments', ARDAL_URL . 'assets/js/ardal-post-comments.js', array(), self::ver( 'assets/js/ardal-post-comments.js' ), true );

		/*
		 * نانس و نشانی ایجکس فقط برای جست‌وجوی زنده. روی هندل خودش می‌نشیند تا
		 * صفحه‌هایی که ویجت جست‌وجو ندارند این را هم بار نکنند.
		 */
		wp_localize_script(
			'ardal-search',
			'ardalSearch',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( Ardal_Search::ACTION ),
			)
		);

		wp_register_style( 'ardal-support', ARDAL_URL . 'assets/css/ardal-support.css', array( 'ardal' ), self::ver( 'assets/css/ardal-support.css' ) );
		wp_register_script( 'ardal-support', ARDAL_URL . 'assets/js/ardal-support.js', array(), self::ver( 'assets/js/ardal-support.js' ), true );
		wp_register_style( 'ardal-toc', ARDAL_URL . 'assets/css/ardal-toc.css', array( 'ardal' ), self::ver( 'assets/css/ardal-toc.css' ) );
		wp_register_script( 'ardal-toc', ARDAL_URL . 'assets/js/ardal-toc.js', array(), self::ver( 'assets/js/ardal-toc.js' ), true );
		wp_register_style( 'ardal-archive', ARDAL_URL . 'assets/css/ardal-archive.css', array( 'ardal' ), self::ver( 'assets/css/ardal-archive.css' ) );
		wp_register_script( 'ardal-archive', ARDAL_URL . 'assets/js/ardal-archive.js', array(), self::ver( 'assets/js/ardal-archive.js' ), true );
		wp_register_script( 'ardal-gsap', ARDAL_URL . 'assets/vendor/gsap/gsap.min.js', array(), self::ver( 'assets/vendor/gsap/gsap.min.js' ), true );
		wp_register_script( 'ardal-morphsvg', ARDAL_URL . 'assets/vendor/gsap/morphsvg.min.js', array( 'ardal-gsap' ), self::ver( 'assets/vendor/gsap/morphsvg.min.js' ), true );
		wp_register_script( 'ardal-monster-rating', ARDAL_URL . 'assets/js/ardal-monster-rating.js', array( 'ardal-morphsvg' ), self::ver( 'assets/js/ardal-monster-rating.js' ), true );

		wp_add_inline_style( 'ardal', Ardal_Palette::css_vars() );

		// دکمه‌های قلب در همهٔ ویجت‌ها هستند و ardal.js همه‌جا بار می‌شود؛
		// پس دادهٔ علاقه‌مندی روی همین هندل می‌نشیند.
		if ( class_exists( 'Ardal_Wishlist' ) ) {
			wp_localize_script( 'ardal', 'ardalWishlist', Ardal_Wishlist::js_config() );
		}
	}

	/**
	 * وقتی نماد تومان روی قیمت‌های خود ووکامرس اعمال می‌شود، استایل باید روی آن
	 * صفحات هم باشد؛ حتی اگر هیچ ویجت اردالی در صفحه نباشد و المنتور استایلش را
	 * لود نکند.
	 */
	public function maybe_enqueue_for_woo() {
		if ( Ardal_Currency::use_symbol() && '1' === Ardal_Palette::get( 'toman_woo' ) ) {
			$this->enqueue();

			return;
		}

		/*
		 * صفحه‌های ووکامرس حتی بدون ویجت اردال هم به این فایل‌ها نیاز دارند:
		 * پیام‌های ووکامرس آنجا چاپ می‌شوند و توست باید جایشان بنشیند. بدون
		 * این، در سبد و تسویه و پنل کاربری همان نوارهای پیش‌فرض می‌ماندند.
		 */
		if ( $this->is_woo_page() ) {
			$this->enqueue();
		}
	}

	/**
	 * آیا این صفحه از صفحه‌های ووکامرس است.
	 *
	 * @return bool
	 */
	private function is_woo_page() {
		if ( ! function_exists( 'is_woocommerce' ) ) {
			return false;
		}

		return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
	}

	public function enqueue() {
		$this->register();

		wp_enqueue_style( 'ardal' );
		wp_enqueue_script( 'ardal' );
	}
}
