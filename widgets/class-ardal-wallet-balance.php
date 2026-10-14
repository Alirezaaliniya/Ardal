<?php
/**
 * ویجت موجودی کیف پول — عدد موجودی با آیکون، برای هدر یا هر جای صفحه.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Wallet_Balance extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-wallet-balance';
	}

	public function get_title() {
		return 'اردال — موجودی کیف پول';
	}

	public function get_icon() {
		return 'eicon-price-list';
	}

	public function get_keywords() {
		return array( 'اردال', 'کیف پول', 'موجودی', 'هدر', 'wallet', 'balance' );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-wallet' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_content', array( 'label' => 'موجودی' ) );

		$this->add_control( 'before', array( 'label' => 'متن پیش از مبلغ', 'type' => Controls_Manager::TEXT, 'default' => 'کیف پول:' ) );
		$this->add_control( 'after', array( 'label' => 'متن پس از مبلغ', 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$this->add_control( 'icon', array( 'label' => 'آیکون', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ) );
		$this->add_control( 'link', array( 'label' => 'لینک به صفحهٔ کیف پول', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ) );
		$this->add_control(
			'guest',
			array(
				'label'       => 'متن برای مهمان',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => 'خالی بماند، برای مهمان چیزی نمایش داده نمی‌شود.',
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

		$this->start_controls_section( 'section_style', array( 'label' => 'ظاهر', 'tab' => Controls_Manager::TAB_STYLE ) );

		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'typo', 'selector' => '{{WRAPPER}} .ard-wl-balance' ) );
		$this->add_control( 'ink', array( 'label' => 'رنگ مبلغ', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-balance' => '--ard-wl-balance-ink:{{VALUE}};' ) ) );
		$this->add_control( 'hover', array( 'label' => 'رنگ هاور', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-balance' => '--ard-wl-balance-hover:{{VALUE}};' ) ) );
		$this->add_control( 'label_color', array( 'label' => 'رنگ متن کنار مبلغ', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-balance' => '--ard-wl-balance-label:{{VALUE}};' ) ) );
		$this->add_control( 'icon_color', array( 'label' => 'رنگ آیکون', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-balance' => '--ard-wl-balance-icon:{{VALUE}};' ), 'condition' => array( 'icon' => 'yes' ) ) );
		$this->add_control( 'bg', array( 'label' => 'زمینه', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-balance' => 'background:{{VALUE}};' ), 'separator' => 'before' ) );
		$this->add_group_control( Group_Control_Border::get_type(), array( 'name' => 'border', 'selector' => '{{WRAPPER}} .ard-wl-balance' ) );

		$this->add_responsive_control(
			'pad',
			array(
				'label'      => 'فاصله داخلی',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array( '{{WRAPPER}} .ard-wl-balance' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'radius',
			array(
				'label'      => 'گردی گوشه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-wl-balance' => 'border-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		if ( ! class_exists( 'Ardal_Wallet' ) || ! Ardal_Wallet::is_enabled() ) {
			$this->render_empty( 'کیف پول در تنظیمات اردال خاموش است.' );

			return;
		}

		$s = $this->get_settings_for_display();

		echo Ardal_Wallet_Render::balance( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			array(
				'before' => (string) $s['before'],
				'after'  => (string) $s['after'],
				'icon'   => 'yes' === $s['icon'] ? 'yes' : 'no',
				'link'   => 'yes' === $s['link'] ? 'yes' : 'no',
				'guest'  => (string) $s['guest'],
			)
		);
	}
}
