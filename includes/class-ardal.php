<?php
/**
 * بوت‌استرپ افزونه.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Ardal {

	/**
	 * @var Ardal|null
	 */
	private static $instance = null;

	/**
	 * @return Ardal
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		new Ardal_Settings();
		new Ardal_Assets();

		Ardal_Currency::init();

		add_action( 'plugins_loaded', array( $this, 'boot_elementor' ) );
	}

	/**
	 * راه‌اندازی بخش المنتور فقط در صورت وجود و سازگاری آن.
	 */
	public function boot_elementor() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'notice_missing_elementor' ) );

			return;
		}

		if ( ! version_compare( ELEMENTOR_VERSION, ARDAL_MIN_ELEMENTOR, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'notice_old_elementor' ) );

			return;
		}

		// کلاس پایه ویجت اینجا لود نمی‌شود: المنتور Widget_Base را فقط هنگام
		// راه‌اندازی ویجت‌ها require می‌کند، پس بارگذاری‌اش به زمان ثبت موکول شده است.
		require_once ARDAL_PATH . 'includes/class-ardal-helpers.php';
		require_once ARDAL_PATH . 'includes/class-ardal-elementor.php';

		new Ardal_Elementor();
	}

	public function notice_missing_elementor() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		echo '<div class="notice notice-warning"><p>'
			. 'افزونه <strong>اردال</strong> برای کار کردن به <strong>المنتور</strong> نیاز دارد. لطفاً المنتور را نصب و فعال کنید.'
			. '</p></div>';
	}

	public function notice_old_elementor() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		printf(
			'<div class="notice notice-warning"><p>افزونه <strong>اردال</strong> به المنتور نسخه %s یا بالاتر نیاز دارد.</p></div>',
			esc_html( ARDAL_MIN_ELEMENTOR )
		);
	}
}
