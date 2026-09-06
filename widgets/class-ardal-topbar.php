<?php
/**
 * ویجت نوار اعلان بالای صفحه.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Topbar extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-topbar';
	}

	public function get_title() {
		return 'اردال — نوار اعلان بالا';
	}

	public function get_icon() {
		return 'eicon-menu-bar';
	}

	public function get_keywords() {
		return array( 'اردال', 'نوار', 'اعلان', 'topbar', 'announcement' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'محتوا' )
		);

		$this->add_control(
			'style',
			array(
				'label'   => 'سبک',
				'type'    => Controls_Manager::SELECT,
				'default' => 'dark',
				'options' => array(
					'dark'  => 'سرمه‌ای پررنگ (دیزاین ۱a)',
					'light' => 'روشن با لینک‌های چپ و راست (دیزاین ۱b)',
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			array(
				'label'       => 'متن',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'ارسال رایگان برای خرید بالای ۲٫۵۰۰٫۰۰۰ تومان',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'   => 'لینک',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => 'آیتم‌ها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ text }}}',
				'default'     => array(
					array( 'text' => 'ارسال رایگان برای خرید بالای ۲٫۵۰۰٫۰۰۰ تومان' ),
					array( 'text' => '۷ روز ضمانت بازگشت کالا' ),
					array( 'text' => 'پشتیبانی ۰۲۱-۹۱۰۰۲۲۳۳' ),
				),
			)
		);

		$this->add_control(
			'highlight',
			array(
				'label'       => 'متن برجسته (سمت چپ)',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'ارسال رایگان تهران — سفارش تا ساعت ۱۴',
				'label_block' => true,
				'condition'   => array( 'style' => 'light' ),
			)
		);

		$this->add_responsive_control(
			'hide_on',
			array(
				'label'        => 'مخفی کردن در این دستگاه',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'ard-hidden',
				'label_on'     => 'مخفی',
				'label_off'    => 'نمایش',
				'selectors'    => array(
					'{{WRAPPER}} .ard-topbar' => 'display:none;',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = is_array( $s['items'] ) ? $s['items'] : array();

		if ( empty( $items ) && 'light' !== $s['style'] ) {
			return;
		}

		printf( '<div class="ard-topbar ard-topbar--%s">', esc_attr( $s['style'] ) );
		echo '<div class="ard-wrap ard-topbar__inner">';

		echo '<div class="ard-topbar__list">';

		$first = true;

		foreach ( $items as $item ) {
			if ( '' === trim( (string) $item['text'] ) ) {
				continue;
			}

			if ( ! $first && 'dark' === $s['style'] ) {
				echo '<span class="ard-topbar__sep" aria-hidden="true">|</span>';
			}

			$first = false;

			if ( ! empty( $item['link']['url'] ) ) {
				printf(
					'<a class="ard-topbar__item"%s>%s</a>',
					Ardal_Helpers::link_attrs( $item['link'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					esc_html( $item['text'] )
				);
			} else {
				printf( '<span class="ard-topbar__item">%s</span>', esc_html( $item['text'] ) );
			}
		}

		echo '</div>';

		if ( 'light' === $s['style'] && '' !== trim( (string) $s['highlight'] ) ) {
			printf( '<div class="ard-topbar__hl">%s</div>', esc_html( $s['highlight'] ) );
		}

		echo '</div></div>';
	}
}
