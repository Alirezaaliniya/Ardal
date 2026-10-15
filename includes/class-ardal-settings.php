<?php
/**
 * صفحه تنظیمات پالت رنگی در پیشخوان وردپرس.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Settings {

	const PAGE  = 'ardal-settings';
	const GROUP = 'ardal_settings_group';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'register' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( ARDAL_FILE ), array( $this, 'action_links' ) );
	}

	/**
	 * @param array $links
	 * @return array
	 */
	public function action_links( $links ) {
		$url = admin_url( 'admin.php?page=' . self::PAGE );

		array_unshift( $links, '<a href="' . esc_url( $url ) . '">تنظیمات</a>' );

		return $links;
	}

	public function add_menu() {
		add_menu_page(
			'تنظیمات ' . Ardal_Brand::name(),
			Ardal_Brand::name(),
			'manage_options',
			self::PAGE,
			array( $this, 'render_page' ),
			'dashicons-art',
			59
		);
	}

	public function register() {
		register_setting(
			self::GROUP,
			Ardal_Palette::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( 'Ardal_Palette', 'sanitize' ),
				'default'           => Ardal_Palette::defaults(),
			)
		);

		// همان گروه، تا هر دو تب با یک «ذخیره تغییرات» ثبت شوند.
		register_setting(
			self::GROUP,
			Ardal_Account_Settings::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( 'Ardal_Account_Settings', 'sanitize' ),
				'default'           => Ardal_Account_Settings::defaults(),
			)
		);

		register_setting(
			self::GROUP,
			Ardal_Wishlist::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( 'Ardal_Wishlist', 'sanitize' ),
				'default'           => Ardal_Wishlist::defaults(),
			)
		);

		register_setting(
			self::GROUP,
			Ardal_Wallet::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( 'Ardal_Wallet', 'sanitize' ),
				'default'           => Ardal_Wallet::defaults(),
			)
		);
	}

	/**
	 * @param string $hook
	 */
	public function assets( $hook ) {
		if ( 'toplevel_page_' . self::PAGE !== $hook ) {
			return;
		}

		$ver = static function ( $rel ) {
			return ARDAL_VERSION . '.' . (int) @filemtime( ARDAL_PATH . $rel ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		};

		// انتخاب تصویر کیف پول از کتابخانهٔ رسانه.
		wp_enqueue_media();

		wp_enqueue_style( 'ardal-admin', ARDAL_URL . 'assets/admin/admin.css', array(), $ver( 'assets/admin/admin.css' ) );

		// همان متغیرهای سمت کاربر، تا پیش‌نمایش پیش از اجرای جاوااسکریپت هم درست باشد.
		wp_add_inline_style( 'ardal-admin', Ardal_Palette::css_vars() );

		wp_enqueue_script( 'ardal-admin', ARDAL_URL . 'assets/admin/admin.js', array(), $ver( 'assets/admin/admin.js' ), true );

		$presets = array();

		foreach ( $this->presets() as $preset ) {
			$presets[ $preset['id'] ] = $preset['values'];
		}

		wp_localize_script(
			'ardal-admin',
			'ardalAdmin',
			array(
				'palette'   => Ardal_Palette::OPTION,
				'defaults'  => Ardal_Palette::defaults(),
				'presets'   => $presets,
				// پیش‌نمایش زندهٔ سلکت‌های آیکون در تب پنل کاربری.
				'iconPaths' => Ardal_Account::icon_paths(),
				'ajax'      => admin_url( 'admin-ajax.php' ),
				'smsNonce'  => wp_create_nonce( 'ardal-wallet-sms-test' ),
				'wallet'    => Ardal_Wallet::OPTION,
			)
		);
	}

	/**
	 * پالت‌های آماده. اولی دقیقاً همان دیزاین اصلی است.
	 *
	 * @return array
	 */
	public function presets() {
		$design = Ardal_Palette::defaults();

		return array(
			array(
				'id'     => 'design',
				'name'   => 'پیش‌فرض دیزاین (اسپرت‌لند)',
				'swatch' => array( $design['primary_dark'], $design['primary'], $design['accent'], $design['sale'] ),
				'values' => $design,
			),
			array(
				'id'     => 'ember',
				'name'   => 'نارنجی پرانرژی',
				'swatch' => array( '#1F2A44', '#E8590C', '#FFB020', '#D6336C' ),
				'values' => array(
					'primary_dark'      => '#1F2A44',
					'primary'           => '#E8590C',
					'primary_light'     => '#FF922B',
					'accent'            => '#FFB020',
					'accent_dark'       => '#E09000',
					'accent_deep'       => '#8A5700',
					'sale'              => '#D6336C',
					'star'              => '#F0A500',
					'tint_blue'         => '#FFF1E6',
					'tint_blue_border'  => '#FFD8BC',
					'tint_green'        => '#FFF7E0',
					'tint_green_border' => '#FFE39B',
					'on_dark'           => '#FFE0CC',
				),
			),
			array(
				'id'     => 'teal',
				'name'   => 'فیروزه‌ای آرام',
				'swatch' => array( '#0B3B3C', '#0E7C86', '#4CC9A7', '#E4572E' ),
				'values' => array(
					'primary_dark'      => '#0B3B3C',
					'primary'           => '#0E7C86',
					'primary_light'     => '#2BB3C0',
					'accent'            => '#4CC9A7',
					'accent_dark'       => '#2FA184',
					'accent_deep'       => '#1B6B57',
					'sale'              => '#E4572E',
					'star'              => '#F2B705',
					'tint_blue'         => '#E4F5F7',
					'tint_blue_border'  => '#BFE6EB',
					'tint_green'        => '#E6F8F1',
					'tint_green_border' => '#BEE9DA',
					'on_dark'           => '#C8ECEF',
				),
			),
			array(
				'id'     => 'ink',
				'name'   => 'مشکی و طلایی',
				'swatch' => array( '#111418', '#2B2F36', '#C9A227', '#B3261E' ),
				'values' => array(
					'primary_dark'      => '#111418',
					'primary'           => '#2B2F36',
					'primary_light'     => '#4A515C',
					'accent'            => '#C9A227',
					'accent_dark'       => '#A8871B',
					'accent_deep'       => '#6E5A12',
					'sale'              => '#B3261E',
					'star'              => '#C9A227',
					'tint_blue'         => '#F1F2F4',
					'tint_blue_border'  => '#DCDEE3',
					'tint_green'        => '#FAF4E0',
					'tint_green_border' => '#EADFBB',
					'on_dark'           => '#D9DCE1',
				),
			),
		);
	}

	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		Ardal_Settings_UI::render( $this->presets() );
	}
}
