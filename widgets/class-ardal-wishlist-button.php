<?php
/**
 * ویجت دکمهٔ علاقه‌مندی — برای صفحهٔ محصول یا لوپ المنتور.
 *
 * رفتار (ذخیره، پیام، مهمان/ورود) از تب «علاقه‌مندی» تنظیمات اردال می‌آید؛
 * اینجا فقط ظاهر و متن همین دکمه تعیین می‌شود.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Wishlist_Button extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-wishlist-button';
	}

	public function get_title() {
		return 'اردال — دکمه علاقه‌مندی';
	}

	public function get_icon() {
		return 'eicon-heart-o';
	}

	public function get_keywords() {
		return array( 'اردال', 'علاقه', 'علاقه‌مندی', 'قلب', 'wishlist', 'heart', 'favorite' );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-wishlist' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_content', array( 'label' => 'دکمه' ) );

		$this->add_control(
			'source',
			array(
				'label'   => 'محصول',
				'type'    => Controls_Manager::SELECT,
				'default' => 'current',
				'options' => array(
					'current' => 'محصول جاری (صفحه محصول یا لوپ)',
					'custom'  => 'محصول مشخص',
				),
			)
		);

		$this->add_control(
			'product_id',
			array(
				'label'     => 'شناسه محصول',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'condition' => array( 'source' => 'custom' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => 'نمایش',
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => array(
					'icon' => 'فقط آیکون',
					'text' => 'آیکون و متن',
				),
			)
		);

		$this->add_control(
			'frame',
			array(
				'label'   => 'قاب',
				'type'    => Controls_Manager::SELECT,
				'default' => 'boxed',
				'options' => array(
					'boxed'  => 'با زمینه و حاشیه',
					'plain'  => 'بدون قاب (فقط قلب)',
					'inline' => 'درون‌خطی (هم‌شکل امتیاز و تعداد نظرات)',
				),
			)
		);

		$this->add_control(
			'label_off',
			array(
				'label'       => 'متن (افزودن)',
				'type'        => Controls_Manager::TEXT,
				'placeholder' => Ardal_Wishlist::option( 'text_add' ),
				'description' => 'خالی بماند، متن تنظیمات اردال استفاده می‌شود.',
			)
		);

		$this->add_control(
			'label_on',
			array(
				'label'       => 'متن (افزوده‌شده)',
				'type'        => Controls_Manager::TEXT,
				'placeholder' => Ardal_Wishlist::option( 'text_remove' ),
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
				'selectors' => array( '{{WRAPPER}} .ard-wish-wrap' => 'justify-content:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'full',
			array(
				'label'        => 'تمام عرض',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'selectors'    => array( '{{WRAPPER}} .ard-wish' => 'inline-size:100%;' ),
				'condition'    => array( 'layout' => 'text' ),
			)
		);

		$this->end_controls_section();

		/* ---------------- استایل ---------------- */

		$this->start_controls_section(
			'section_style',
			array(
				'label' => 'دکمه',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'size',
			array(
				'label'      => 'ارتفاع دکمه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 24, 'max' => 80 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-wish' => '--ard-wish-size:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => 'اندازه آیکون',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 10, 'max' => 48 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-wish' => '--ard-wish-icon:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'pad',
			array(
				'label'      => 'فاصله افقی داخلی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-wish' => 'padding-inline:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'layout' => 'text' ),
			)
		);

		$this->add_responsive_control(
			'radius',
			array(
				'label'      => 'گردی گوشه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ), '%' => array( 'min' => 0, 'max' => 50 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-wish' => '--ard-wish-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'border_w',
			array(
				'label'      => 'ضخامت حاشیه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 6 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-wish' => 'border-width:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'typo',
				'selector'  => '{{WRAPPER}} .ard-wish__text',
				'condition' => array( 'layout' => 'text' ),
			)
		);

		$this->start_controls_tabs( 'tabs_state' );

		foreach ( array(
			'normal' => array( 'عادی', '--ard-wish-color', '--ard-wish-bg', '--ard-wish-border' ),
			'hover'  => array( 'هاور', '--ard-wish-hover', '--ard-wish-hover-bg', '--ard-wish-hover-border' ),
			'active' => array( 'افزوده‌شده', '--ard-wish-on', '--ard-wish-on-bg', '--ard-wish-on-border' ),
		) as $state => $row ) {
			$this->start_controls_tab( 'tab_' . $state, array( 'label' => $row[0] ) );

			$this->add_control(
				'color_' . $state,
				array(
					'label'     => 'رنگ آیکون و متن',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( '{{WRAPPER}} .ard-wish' => $row[1] . ':{{VALUE}};' ),
				)
			);

			$this->add_control(
				'bg_' . $state,
				array(
					'label'     => 'زمینه',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( '{{WRAPPER}} .ard-wish' => $row[2] . ':{{VALUE}};' ),
					'condition' => array( 'frame' => 'boxed' ),
				)
			);

			$this->add_control(
				'border_' . $state,
				array(
					'label'     => 'حاشیه',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( '{{WRAPPER}} .ard-wish' => $row[3] . ':{{VALUE}};' ),
					'condition' => array( 'frame' => 'boxed' ),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'shadow',
				'selector'  => '{{WRAPPER}} .ard-wish',
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

		$s  = $this->get_settings_for_display();
		$id = 'custom' === $s['source'] ? absint( $s['product_id'] ) : 0;

		// در ویرایشگر قالب محصول، «محصول جاری» وجود ندارد؛ یک محصول نمونه کافی است.
		if ( ! $id && ! Ardal_Wishlist::resolve_product( 0 ) && \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$sample = wc_get_products( array( 'limit' => 1, 'return' => 'ids', 'status' => 'publish' ) );
			$id     = $sample ? (int) $sample[0] : 0;
		}

		$html = Ardal_Wishlist::button(
			array(
				'product'   => $id,
				'label'     => trim( (string) $s['label_off'] ),
				'label_on'  => trim( (string) $s['label_on'] ),
				'show_text' => 'text' === $s['layout'],
				'class'     => 'boxed' === $s['frame'] ? '' : 'ard-wish--plain' . ( 'inline' === $s['frame'] ? ' ard-wish--inline' : '' ),
			)
		);

		if ( '' === $html ) {
			$this->render_empty( 'محصولی برای این دکمه پیدا نشد.' );

			return;
		}

		echo '<span class="ard-wish-wrap">' . $html . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خروجی button() escape شده.
	}
}
