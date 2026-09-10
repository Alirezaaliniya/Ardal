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
		add_filter( 'preprocess_comment', array( $this, 'allow_ardal_unrated_reviews' ), -1 );

		Ardal_Currency::init();
		Ardal_Product_Fields::init();
		Ardal_Mega_Menu::init();
		Ardal_Account::init();
		Ardal_Shop::init();
		Ardal_Archive::init();

		add_action( 'plugins_loaded', array( $this, 'boot_elementor' ) );
	}

	/**
	 * The animated rating input intentionally offers an empty rating. WooCommerce
	 * rejects an empty `rating` only when that POST key is present, so remove it
	 * for this explicitly marked Ardal form before WooCommerce validates reviews.
	 *
	 * @param array $comment_data Pending comment data.
	 * @return array
	 */
	public function allow_ardal_unrated_reviews( $comment_data ) {
		if (
			is_admin()
			|| empty( $_POST['ardal_allow_empty_rating'] )
			|| empty( $_POST['comment_post_ID'] )
			|| 'product' !== get_post_type( absint( $_POST['comment_post_ID'] ) )
			|| ! isset( $_POST['rating'] )
			|| '' !== trim( (string) wp_unslash( $_POST['rating'] ) )
		) {
			return $comment_data;
		}

		unset( $_POST['rating'] );

		return $comment_data;
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
