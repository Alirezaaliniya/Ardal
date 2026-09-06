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
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue' ) );
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'enqueue' ) );
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue' ) );
	}

	/**
	 * ثبت هندل‌ها. چند بار صدا زدنش بی‌ضرر است.
	 */
	public function register() {
		if ( $this->registered ) {
			return;
		}

		$this->registered = true;

		$palette = Ardal_Palette::get_all();

		if ( '1' === $palette['load_google_font'] && 'Vazirmatn' === trim( $palette['font_family'] ) ) {
			wp_register_style(
				'ardal-font',
				'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700;800;900&display=swap',
				array(),
				null // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- نسخه‌گذاری روی URL گوگل معنا ندارد.
			);
		}

		wp_register_style( 'ardal', ARDAL_URL . 'assets/css/ardal.css', array(), ARDAL_VERSION );
		wp_register_script( 'ardal', ARDAL_URL . 'assets/js/ardal.js', array(), ARDAL_VERSION, true );

		wp_add_inline_style( 'ardal', Ardal_Palette::css_vars() );
	}

	public function enqueue() {
		$this->register();

		if ( wp_style_is( 'ardal-font', 'registered' ) ) {
			wp_enqueue_style( 'ardal-font' );
		}

		wp_enqueue_style( 'ardal' );
		wp_enqueue_script( 'ardal' );
	}
}
