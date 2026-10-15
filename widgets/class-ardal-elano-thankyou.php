<?php
/**
 * ویجت صفحهٔ تشکر با قالب الانو.
 *
 * سفارش را از اندپوینت order-received (یا ?order-received=…&key=…) با کلید
 * سفارش می‌خواند. ویجت تسویهٔ الانو خودش هم صفحهٔ تشکر دارد؛ این ویجت برای
 * وقتی است که صفحهٔ تشکر جدا طراحی می‌شود (مثلاً با قالب‌ساز المنتور).
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ARDAL_PATH . 'includes/trait-ardal-elano-shop-widget.php';

use Elementor\Controls_Manager;

class Ardal_Widget_Elano_Thankyou extends Ardal_Widget_Base {

	use Ardal_Elano_Shop_Widget;

	public function get_name() {
		return 'ardal-elano-thankyou';
	}

	public function get_title() {
		return 'اردال — تشکر از خرید (الانو)';
	}

	public function get_icon() {
		return 'eicon-check-circle';
	}

	public function get_keywords() {
		return array( 'اردال', 'الانو', 'تشکر', 'سفارش', 'thank you', 'order received', 'elano' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_elc_content', array( 'label' => 'محتوا' ) );

		$this->add_control(
			'elc_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => 'فقط وقتی سفارشی در نشانی صفحه باشد (بعد از پرداخت) چیزی نشان می‌دهد؛ در ویرایشگر، آخرین سفارش سایت برای پیش‌نمایش.',
				'content_classes' => 'elementor-descriptor',
			)
		);

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
		if ( ! Ardal_Helpers::has_woo() ) {
			$this->render_empty( 'ووکامرس فعال نیست.' );

			return;
		}

		$editing = $this->elano_editing();
		$order   = Ardal_Shop_Elano::thanks_order( $editing );

		if ( ! $order && ! $editing && ! is_wc_endpoint_url( 'order-received' ) ) {
			return;
		}

		Ardal_Shop_Elano::begin( $this->elano_settings( $this->get_settings_for_display() ) );

		echo Ardal_Shop_Elano::root_open( 'thanks' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		Ardal_Shop_Elano::render_thanks( $order, ! $editing );
		echo '</div>';

		Ardal_Shop_Elano::end();
	}
}
