<?php
/**
 * ویجت «فیلترهای فعال» — چیپ‌های قابل حذف بالای فهرست محصولات.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Archive_Chips extends Ardal_Archive_Widget_Base {

	public function get_name() {
		return 'ardal-archive-chips';
	}

	public function get_title() {
		return 'اردال — فیلترهای فعال';
	}

	public function get_icon() {
		return 'eicon-tags';
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_chips',
			array( 'label' => 'محتوا' )
		);

		$this->add_control(
			'label',
			array(
				'label'       => 'برچسب کنار چیپ‌ها',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'فیلترهای فعال:',
				'label_block' => true,
			)
		);

		$this->add_control(
			'show_group',
			array(
				'label'        => 'نمایش نام فیلتر داخل چیپ',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => 'بله',
				'label_off'    => 'خیر',
				'return_value' => 'yes',
				'description'  => 'مثلاً «دسته: کفش» به‌جای «کفش».',
			)
		);

		$this->add_control(
			'show_clear',
			array(
				'label'        => 'دکمه پاک‌کردن همه',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'clear_text',
			array(
				'label'     => 'متن دکمه پاک‌کردن',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'پاک کردن همه',
				'condition' => array( 'show_clear' => 'yes' ),
			)
		);

		$this->add_control(
			'boxed',
			array(
				'label'        => 'قاب سفید',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'editor_preview',
			array(
				'label'        => 'نمایش نمونه در ویرایشگر',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'در سایت واقعی، وقتی فیلتری فعال نباشد ویجت چیزی چاپ نمی‌کند.',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ استایل */

		$this->start_controls_section(
			'section_style_chips',
			array(
				'label' => 'چیپ‌ها',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'chip_bg',
			array(
				'label'     => 'زمینه چیپ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-chips__item' => 'background:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'chip_color',
			array(
				'label'     => 'رنگ متن چیپ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-chips__item' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'chip_border',
			array(
				'label'     => 'رنگ حاشیه چیپ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-chips__item' => 'border-color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'chip_radius',
			array(
				'label'      => 'گردی چیپ',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 30 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-chips__item' => 'border-radius:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => 'رنگ برچسب',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-chips__label' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'clear_color',
			array(
				'label'     => 'رنگ دکمه پاک‌کردن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-chips__clear' => 'color:{{VALUE}};',
				),
				'condition' => array( 'show_clear' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'chip_typo',
				'selector' => '{{WRAPPER}} .ard-chips__item',
			)
		);

		$this->add_responsive_control(
			'chip_gap',
			array(
				'label'      => 'فاصله بین چیپ‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 2, 'max' => 24 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-chips' => 'gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->add_box_controls( '.ard-chips--boxed', 'chips_box', 'قاب', array( 'top' => 12, 'right' => 16, 'bottom' => 12, 'left' => 16 ) );
	}

	protected function render() {
		if ( $this->require_woo() ) {
			return;
		}

		$s     = $this->get_settings_for_display();
		$items = Ardal_Archive::active_filters();

		if ( ! $items ) {
			if ( 'yes' === $s['editor_preview'] && \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				$items = array(
					array( 'group' => 'دسته', 'label' => 'نمونه فیلتر', 'url' => '#' ),
					array( 'group' => 'قیمت', 'label' => '۵۰۰٬۰۰۰ تا ۲٬۰۰۰٬۰۰۰', 'url' => '#' ),
				);
			} else {
				return;
			}
		}

		$classes = array( 'ard-chips' );

		if ( 'yes' === $s['boxed'] ) {
			$classes[] = 'ard-chips--boxed';
		}

		printf( '<div class="%s">', esc_attr( implode( ' ', $classes ) ) );

		if ( '' !== trim( (string) $s['label'] ) ) {
			printf( '<span class="ard-chips__label">%s</span>', esc_html( $s['label'] ) );
		}

		foreach ( $items as $item ) {
			$text = ( 'yes' === $s['show_group'] && '' !== $item['group'] )
				? $item['group'] . ': ' . $item['label']
				: $item['label'];

			printf(
				'<a class="ard-chips__item" href="%s" aria-label="%s">%s%s</a>',
				esc_url( $item['url'] ),
				esc_attr( 'حذف فیلتر ' . $text ),
				esc_html( $text ),
				Ardal_Archive::icon( 'close', 13, 2.2 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		if ( 'yes' === $s['show_clear'] && '' !== trim( (string) $s['clear_text'] ) ) {
			printf(
				'<a class="ard-chips__clear" href="%s">%s</a>',
				esc_url( Ardal_Archive::base_url() ),
				esc_html( $s['clear_text'] )
			);
		}

		echo '</div>';
	}
}
