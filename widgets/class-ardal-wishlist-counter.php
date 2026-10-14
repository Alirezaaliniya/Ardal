<?php
/**
 * ویجت شمارندهٔ علاقه‌مندی — آیکون قلب با عدد، برای هدر.
 *
 * عدد با هر کلیک قلب در همان صفحه زنده عوض می‌شود.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Wishlist_Counter extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-wishlist-counter';
	}

	public function get_title() {
		return 'اردال — شمارنده علاقه‌مندی';
	}

	public function get_icon() {
		return 'eicon-counter';
	}

	public function get_keywords() {
		return array( 'اردال', 'علاقه‌مندی', 'شمارنده', 'هدر', 'wishlist', 'counter' );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-wishlist' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_content', array( 'label' => 'شمارنده' ) );

		$this->add_control(
			'label',
			array(
				'label'   => 'متن کنار آیکون',
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'link',
			array(
				'label'        => 'لینک به صفحهٔ علاقه‌مندی‌ها',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'hide_zero',
			array(
				'label'        => 'عدد صفر نمایش داده نشود',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => 'چیدمان',
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array( 'title' => 'راست', 'icon' => 'eicon-h-align-right' ),
					'center'     => array( 'title' => 'وسط', 'icon' => 'eicon-h-align-center' ),
					'flex-end'   => array( 'title' => 'چپ', 'icon' => 'eicon-h-align-left' ),
				),
				'selectors' => array( '{{WRAPPER}} .elementor-widget-container' => 'display:flex;justify-content:{{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => 'ظاهر',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => 'اندازه آیکون',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 14, 'max' => 48 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-wish-count' => '--ard-wish-count-icon:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'color',
			array(
				'label'     => 'رنگ آیکون و متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-wish-count' => '--ard-wish-count-color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'hover',
			array(
				'label'     => 'رنگ هاور',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-wish-count' => '--ard-wish-count-hover:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'badge_bg',
			array(
				'label'     => 'زمینه عدد',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-wish-count' => '--ard-wish-count-badge:{{VALUE}};' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'badge_ink',
			array(
				'label'     => 'رنگ عدد',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-wish-count' => '--ard-wish-count-badge-ink:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'label_typo',
				'selector'  => '{{WRAPPER}} .ard-wish-count__label',
				'condition' => array( 'label!' => '' ),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		if ( ! Ardal_Helpers::has_woo() || ! Ardal_Wishlist::enabled() ) {
			$this->render_empty( 'علاقه‌مندی در تنظیمات اردال خاموش است.' );

			return;
		}

		$s = $this->get_settings_for_display();

		echo Ardal_Wishlist::counter( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			array(
				'label'     => trim( (string) $s['label'] ),
				'link'      => 'yes' === $s['link'] ? 'yes' : 'no',
				'hide_zero' => 'yes' === $s['hide_zero'] ? 'yes' : 'no',
			)
		);
	}
}
