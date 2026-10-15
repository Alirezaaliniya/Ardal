<?php
/**
 * ویجت سبد خرید با قالب الانو.
 *
 * خودِ سبد ووکامرس است (همان شورت‌کد)، با تمپلیت‌های الانو؛ روی صفحهٔ «سبد
 * خرید» ووکامرس گذاشته شود تا اسکریپت‌های سبد بارگذاری شوند.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ARDAL_PATH . 'includes/trait-ardal-elano-shop-widget.php';

use Elementor\Controls_Manager;

class Ardal_Widget_Elano_Cart extends Ardal_Widget_Base {

	use Ardal_Elano_Shop_Widget;

	public function get_name() {
		return 'ardal-elano-cart';
	}

	public function get_title() {
		return 'اردال — سبد خرید (الانو)';
	}

	public function get_icon() {
		return 'eicon-cart';
	}

	public function get_keywords() {
		return array( 'اردال', 'الانو', 'سبد', 'سبد خرید', 'cart', 'elano' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_elc_content', array( 'label' => 'محتوا' ) );

		$this->add_control(
			'elc_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => 'این ویجت را روی همان برگه‌ای بگذارید که در «ووکامرس ← تنظیمات ← پیشرفته» به‌عنوان صفحهٔ سبد خرید انتخاب شده است.',
				'content_classes' => 'elementor-descriptor',
			)
		);

		$this->elano_texts(
			array(
				'cart_title'  => array( 'عنوان سربرگ' ),
				'cart_sub'    => array( 'زیرعنوان سربرگ' ),
				'coupon_ph'   => array( 'متن داخل فیلد کد تخفیف' ),
				'coupon_btn'  => array( 'دکمهٔ کد تخفیف' ),
				'sub_label'   => array( 'برچسب مبلغ جز', Controls_Manager::TEXT, 'مجموع قیمت کالاها پیش از تخفیف.' ),
				'off_label'   => array( 'برچسب تخفیف', Controls_Manager::TEXT, 'تخفیف حراج کالاها به‌اضافهٔ کد تخفیف.' ),
				'total_label' => array( 'برچسب جمع کل' ),
				'pay_btn'     => array( 'دکمهٔ اقدام به پرداخت' ),
				'back_btn'    => array( 'دکمهٔ بازگشت به فروشگاه' ),
				'back_url'    => array( 'نشانی بازگشت به فروشگاه', Controls_Manager::TEXT, 'خالی بماند، صفحهٔ فروشگاه ووکامرس.' ),
			)
		);

		$this->elano_switches(
			array(
				'show_off_badge' => 'برچسب درصد تخفیف روی کالا',
				'show_free'      => 'نوار «تا ارسال رایگان»',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section( 'section_elc_empty', array( 'label' => 'سبد خالی' ) );

		$this->elano_texts(
			array(
				'empty_title' => array( 'عنوان' ),
				'empty_text'  => array( 'توضیح', Controls_Manager::TEXTAREA ),
				'empty_btn'   => array( 'متن دکمه' ),
			)
		);

		$this->end_controls_section();

		$this->elano_style_controls();
	}

	protected function render() {
		if ( ! $this->elano_cart_ready() ) {
			$this->render_empty( 'ووکامرس فعال نیست.' );

			return;
		}

		Ardal_Shop_Elano::begin( $this->elano_settings( $this->get_settings_for_display() ) );

		echo Ardal_Shop_Elano::root_open( 'cart' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo do_shortcode( '[woocommerce_cart]' );
		echo '</div>';

		Ardal_Shop_Elano::end();
	}
}
