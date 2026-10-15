<?php
/**
 * ویجت تسویه حساب با قالب الانو.
 *
 * خودِ تسویه ووکامرس است (همان شورت‌کد) با تمپلیت‌های الانو؛ روی صفحهٔ «تسویه
 * حساب» ووکامرس گذاشته شود. روی اندپوینت order-received صفحهٔ تشکر الانو را
 * نشان می‌دهد، مگر اینکه برای آن ویجت تشکر جدا گذاشته شده باشد.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ARDAL_PATH . 'includes/trait-ardal-elano-shop-widget.php';

use Elementor\Controls_Manager;

class Ardal_Widget_Elano_Checkout extends Ardal_Widget_Base {

	use Ardal_Elano_Shop_Widget;

	public function get_name() {
		return 'ardal-elano-checkout';
	}

	public function get_title() {
		return 'اردال — تسویه حساب (الانو)';
	}

	public function get_icon() {
		return 'eicon-checkout';
	}

	public function get_keywords() {
		return array( 'اردال', 'الانو', 'تسویه', 'پرداخت', 'checkout', 'elano' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_elc_content', array( 'label' => 'محتوا' ) );

		$this->add_control(
			'elc_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => 'این ویجت را روی همان برگه‌ای بگذارید که به‌عنوان صفحهٔ تسویه حساب ووکامرس انتخاب شده است. فیلدهای فرم همان فیلدهای ووکامرس‌اند.',
				'content_classes' => 'elementor-descriptor',
			)
		);

		$this->elano_texts(
			array(
				'billing_title' => array( 'عنوان بخش اطلاعات' ),
				'ship_title'    => array( 'عنوان بخش شیوهٔ ارسال' ),
				'pay_title'     => array( 'عنوان بخش پرداخت' ),
				'notes_title'   => array( 'عنوان نکات پیش از پرداخت', Controls_Manager::TEXT, 'خالی بماند، بخش نکات نمایش داده نمی‌شود.' ),
				'notes_text'    => array( 'متن نکات پیش از پرداخت', Controls_Manager::TEXTAREA ),
				'cart_title'    => array( 'عنوان سربرگ ستون کناری' ),
				'cart_sub'      => array( 'زیرعنوان سربرگ' ),
				'sub_label'     => array( 'برچسب مبلغ جز' ),
				'off_label'     => array( 'برچسب تخفیف' ),
				'total_label'   => array( 'برچسب جمع کل' ),
				'place_btn'     => array( 'دکمهٔ پرداخت' ),
				'back_btn'      => array( 'دکمهٔ بازگشت به فروشگاه' ),
				'back_url'      => array( 'نشانی بازگشت به فروشگاه', Controls_Manager::TEXT, 'خالی بماند، صفحهٔ فروشگاه ووکامرس.' ),
			)
		);

		$this->elano_switches( array( 'show_off_badge' => 'برچسب درصد تخفیف روی کالا' ) );

		$this->add_control(
			'elc_mobile_summary',
			array(
				'label'       => 'جای خلاصهٔ سفارش در موبایل',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'last',
				'options'     => array(
					'last'  => 'بعد از فرم (دکمهٔ پرداخت پایین صفحه)',
					'first' => 'قبل از فرم (مثل دیزاین)',
				),
				'description' => 'در دیزاین خلاصه بالای فرم است؛ پیش‌فرض پایین است تا دکمهٔ پرداخت بعد از پر کردن فرم در دسترس باشد.',
			)
		);

		$this->add_control(
			'elc_thanks_mode',
			array(
				'label'       => 'در صفحهٔ «سفارش دریافت شد»',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'elano',
				'options'     => array(
					'elano' => 'نمایش صفحهٔ تشکر الانو',
					'hide'  => 'هیچ (ویجت تشکر جداگانه دارم)',
				),
				'separator'   => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section( 'section_elc_delivery', array( 'label' => 'زمان تحویل' ) );

		$this->elano_switches(
			array(
				'delivery'     => 'انتخاب روز تحویل',
				'delivery_req' => 'انتخاب روز اجباری باشد',
			)
		);

		$this->elano_texts( array( 'delivery_title' => array( 'عنوان بخش' ) ) );

		$this->add_control(
			'elc_delivery_days',
			array(
				'label'   => 'تعداد روزهای قابل انتخاب',
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 30,
				'default' => 7,
			)
		);

		$this->add_control(
			'elc_delivery_start',
			array(
				'label'       => 'اولین روز قابل انتخاب',
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 30,
				'default'     => 1,
				'description' => 'چند روز بعد از امروز. صفر یعنی از همین امروز.',
			)
		);

		$this->add_control(
			'elc_delivery_skip',
			array(
				'label'       => 'روزهای تعطیل',
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'default'     => array( '5' ),
				'options'     => array(
					'6' => 'شنبه',
					'0' => 'یکشنبه',
					'1' => 'دوشنبه',
					'2' => 'سه‌شنبه',
					'3' => 'چهارشنبه',
					'4' => 'پنجشنبه',
					'5' => 'جمعه',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section( 'section_elc_thanks', array( 'label' => 'صفحهٔ تشکر' ) );

		$this->elano_texts(
			array(
				'thanks_title' => array( 'عنوان' ),
				'thanks_sub'   => array( 'زیرعنوان' ),
				'thanks_btn'   => array( 'متن دکمه' ),
				'thanks_url'   => array( 'نشانی دکمه', Controls_Manager::TEXT, 'خالی بماند، صفحهٔ اصلی سایت.' ),
			)
		);

		$this->elano_switches(
			array(
				'thanks_track'   => 'لینک پیگیری سفارش در پنل کاربری',
				'thanks_details' => 'جدول جزئیات سفارش ووکامرس زیر کارت',
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

		$s = $this->get_settings_for_display();

		if ( 'hide' === ( $s['elc_thanks_mode'] ?? 'elano' ) && is_wc_endpoint_url( 'order-received' ) ) {
			return;
		}

		if ( $this->elano_editing() && WC()->cart->is_empty() ) {
			echo '<div class="ard-empty">برای پیش‌نمایش تسویه حساب، یک کالا به سبد خرید اضافه کنید.</div>';

			return;
		}

		Ardal_Shop_Elano::begin( $this->elano_settings( $s ) );

		echo Ardal_Shop_Elano::root_open( is_wc_endpoint_url( 'order-received' ) ? 'thanks' : 'checkout' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo do_shortcode( '[woocommerce_checkout]' );
		echo '</div>';

		Ardal_Shop_Elano::end();
	}
}
