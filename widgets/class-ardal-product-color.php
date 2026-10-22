<?php
/**
 * ویجت انتخاب رنگ محصول.
 *
 * چون این ویجت از باکس خرید و ویجت سایز جداست، انتخاب کاربر روی خودِ صفحه
 * منتشر می‌شود تا بقیهٔ ویجت‌ها هم از آن باخبر شوند.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

class Ardal_Widget_Product_Color extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-product-color';
	}

	public function get_title() {
		return 'اردال — انتخاب رنگ محصول';
	}

	public function get_icon() {
		return 'eicon-paint-brush';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'رنگ', 'متغیر', 'color', 'variation' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'محتوا' )
		);

		$this->add_control(
			'source',
			array(
				'label'       => 'منبع',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'auto',
				'options'     => array(
					'auto'   => 'خودکار از محصول جاری',
					'manual' => 'دستی',
				),
				'description' => 'حالت خودکار گزینه‌ها را از ویژگی رنگِ محصول می‌خواند و گزینه‌های ناموجود را غیرفعال می‌کند.',
			)
		);

		$this->add_control(
			'color_attribute',
			array(
				'label'       => 'نام ویژگی رنگ',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'رنگ',
				'description' => 'نام یا اسلاگ ویژگی محصول. مثلاً «رنگ» یا «pa_color».',
				'condition'   => array( 'source' => 'auto' ),
			)
		);

		$this->add_control(
			'hide_wc',
			array(
				'label'        => 'پنهان کردن انتخابگر ووکامرس',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'سلکت همین ویژگی بالای دکمهٔ «افزودن به سبد» ووکامرس پنهان می‌شود (انتخاب این ویجت خودکار به آن منتقل می‌شود). خاموش کنید تا هر دو دیده شوند.',
				'condition'    => array( 'source' => 'auto' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'        => 'حالت نمایش',
				'type'         => Controls_Manager::SELECT,
				'default'      => 'chip',
				'options'      => array(
					'chip'   => 'کاشی (نمونه‌رنگ + نام)',
					'dot'    => 'دایره‌ای (فقط رنگ، با تولتیپ)',
					'square' => 'مربعی (فقط رنگ، با تولتیپ)',
				),
				'prefix_class' => 'ard-pcolor--',
				'render_type'  => 'template',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'tooltip',
			array(
				'label'        => 'تولتیپ نام رنگ',
				'type'         => Controls_Manager::SELECT,
				'default'      => 'hover',
				'options'      => array(
					'hover'  => 'با رفتن ماوس روی رنگ',
					'active' => 'همیشه روی رنگ انتخاب‌شده (+ هاور)',
					'none'   => 'بدون تولتیپ',
				),
				'prefix_class' => 'ard-pcolor-tip--',
				'description'  => 'در موبایل با لمس رنگ نمایش داده می‌شود.',
			)
		);

		$this->add_control(
			'label',
			array(
				'label'     => 'برچسب',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'رنگ',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_selected',
			array(
				'label'        => 'نمایش نام رنگ انتخاب‌شده کنار برچسب',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'color_name',
			array(
				'label'   => 'نام رنگ',
				'type'    => Controls_Manager::TEXT,
				'default' => 'مشکی / لیمویی',
			)
		);

		$repeater->add_control(
			'color_hex',
			array(
				'label'   => 'کد رنگ',
				'type'    => Controls_Manager::COLOR,
				'default' => '#20303F',
			)
		);

		$this->add_control(
			'colors',
			array(
				'label'       => 'رنگ‌ها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ color_name }}}',
				'condition'   => array( 'source' => 'manual' ),
				'default'     => array(
					array( 'color_name' => 'مشکی / لیمویی', 'color_hex' => '#20303F' ),
					array( 'color_name' => 'سفید / آبی', 'color_hex' => '#E8EEF5' ),
					array( 'color_name' => 'سرمه‌ای', 'color_hex' => '#0B3C8C' ),
					array( 'color_name' => 'خاکستری', 'color_hex' => '#8C98A4' ),
				),
			)
		);

		$this->end_controls_section();

		$this->register_style_controls();
	}

	/**
	 * تب استایل: برچسب، گزینه‌ها (با حالت‌ها) و تولتیپ.
	 */
	private function register_style_controls() {
		$wrap = '{{WRAPPER}} .ard-prod-variants--colors';

		/* ------------------------------------------------ برچسب */

		$this->start_controls_section(
			'section_style_label',
			array(
				'label' => 'برچسب',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'header_align',
			array(
				'label'     => 'چیدمان',
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array( 'title' => 'راست', 'icon' => 'eicon-h-align-right' ),
					'center'     => array( 'title' => 'وسط', 'icon' => 'eicon-h-align-center' ),
					'flex-end'   => array( 'title' => 'چپ', 'icon' => 'eicon-h-align-left' ),
				),
				'selectors' => array( '{{WRAPPER}} .ard-prod-variants__header' => 'justify-content:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'header_space',
			array(
				'label'      => 'فاصله تا گزینه‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-prod-variants__header' => 'margin-bottom:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => 'رنگ برچسب',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-prod-variants__label' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typo',
				'selector' => '{{WRAPPER}} .ard-prod-variants__label',
			)
		);

		$this->add_control(
			'selected_color',
			array(
				'label'     => 'رنگ نام انتخاب‌شده',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-prod-variants__selected' => 'color:{{VALUE}};' ),
				'separator' => 'before',
				'condition' => array( 'show_selected' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'selected_typo',
				'selector'  => '{{WRAPPER}} .ard-prod-variants__selected',
				'condition' => array( 'show_selected' => 'yes' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ گزینه‌ها */

		$this->start_controls_section(
			'section_style',
			array(
				'label' => 'گزینه‌ها',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'items_align',
			array(
				'label'     => 'چیدمان گزینه‌ها',
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array( 'title' => 'راست', 'icon' => 'eicon-h-align-right' ),
					'center'     => array( 'title' => 'وسط', 'icon' => 'eicon-h-align-center' ),
					'flex-end'   => array( 'title' => 'چپ', 'icon' => 'eicon-h-align-left' ),
				),
				'selectors' => array( '{{WRAPPER}} .ard-prod-colors' => 'justify-content:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'          => 'فاصله بین گزینه‌ها',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 10 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 8 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-colors' => 'gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'swatch_size',
			array(
				'label'      => 'اندازه نمونه‌رنگ',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'       => array( 'px' => array( 'min' => 10, 'max' => 72 ) ),
				'selectors'   => array( $wrap => '--ard-pc-size:{{SIZE}}{{UNIT}};' ),
				'description' => 'خالی بماند: کاشی ۱۸ پیکسل، دایره‌ای و مربعی ۳۲ پیکسل.',
			)
		);

		$this->add_responsive_control(
			'swatch_radius',
			array(
				'label'      => 'گردی نمونه‌رنگ',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-prod-color__swatch' => 'border-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'swatch_border_w',
			array(
				'label'      => 'ضخامت حاشیهٔ نمونه‌رنگ',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 6 ) ),
				'selectors'  => array( $wrap => '--ard-pc-sw-bw:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'swatch_border_c',
			array(
				'label'     => 'رنگ حاشیهٔ نمونه‌رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $wrap => '--ard-pc-sw-bc:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'names_visible',
			array(
				'label'          => 'نمایش نام رنگ روی گزینه',
				'type'           => Controls_Manager::SELECT,
				'default'        => 'inline',
				'mobile_default' => 'none',
				'options'        => array(
					'inline' => 'نمایش',
					'none'   => 'فقط نمونه‌رنگ',
				),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-color__name' => 'display:{{VALUE}};',
				),
				'condition'      => array( 'layout' => 'chip' ),
				'separator'      => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'name_typo',
				'label'     => 'تایپوگرافی نام',
				'selector'  => '{{WRAPPER}} .ard-prod-color__name',
				'condition' => array( 'layout' => 'chip' ),
			)
		);

		$this->add_responsive_control(
			'item_pad',
			array(
				'label'      => 'فاصلهٔ داخلی گزینه',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array( '{{WRAPPER}} .ard-prod-color' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
				'condition'  => array( 'layout' => 'chip' ),
			)
		);

		$this->add_responsive_control(
			'item_radius',
			array(
				'label'      => 'گردی گزینه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-prod-color' => 'border-radius:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'layout' => 'chip' ),
			)
		);

		$this->add_control(
			'item_border_w',
			array(
				'label'      => 'ضخامت حاشیهٔ گزینه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 6 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-prod-color' => 'border-width:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'layout' => 'chip' ),
			)
		);

		$this->start_controls_tabs( 'item_state_tabs', array( 'separator' => 'before' ) );

		foreach ( array(
			'normal' => 'عادی',
			'hover'  => 'هاور',
			'active' => 'انتخاب‌شده',
		) as $state => $title ) {
			$this->start_controls_tab( 'item_tab_' . $state, array( 'label' => $title ) );

			// اسم کنترل رنگِ «انتخاب‌شده» همان قبلی است تا تنظیمات ذخیره‌شده بماند.
			$this->add_control(
				'active' === $state ? 'active_color' : 'item_ring_' . $state,
				array(
					'label'       => 'رنگ حاشیه / حلقه',
					'type'        => Controls_Manager::COLOR,
					'selectors'   => array( $wrap => '--ard-pc-ring-' . $state . ':{{VALUE}};' ),
					'description' => 'normal' === $state ? '' : 'در حالت دایره‌ای و مربعی، حلقهٔ دور رنگ.',
				)
			);

			$this->add_control(
				'item_bg_' . $state,
				array(
					'label'     => 'زمینهٔ گزینه',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( $wrap => '--ard-pc-bg-' . $state . ':{{VALUE}};' ),
					'condition' => array( 'layout' => 'chip' ),
				)
			);

			$this->add_control(
				'item_ink_' . $state,
				array(
					'label'     => 'رنگ نام',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( $wrap => '--ard-pc-ink-' . $state . ':{{VALUE}};' ),
					'condition' => array( 'layout' => 'chip' ),
				)
			);

			if ( 'normal' !== $state ) {
				$this->add_control(
					'item_scale_' . $state,
					array(
						'label'     => 'بزرگ‌نمایی',
						'type'      => Controls_Manager::SLIDER,
						'range'     => array( 'px' => array( 'min' => 1, 'max' => 1.3, 'step' => 0.01 ) ),
						'selectors' => array( $wrap => '--ard-pc-scale-' . $state . ':{{SIZE}};' ),
					)
				);
			}

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'item_shadow_' . $state,
					'selector' => 'normal' === $state
						? '{{WRAPPER}} .ard-prod-color'
						: ( 'hover' === $state ? '{{WRAPPER}} .ard-prod-color:hover' : '{{WRAPPER}} .ard-prod-color.is-active' ),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->add_control(
			'ring_width',
			array(
				'label'      => 'ضخامت حلقه (دایره‌ای / مربعی)',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 6 ) ),
				'selectors'  => array( $wrap => '--ard-pc-ring-w:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'layout!' => 'chip' ),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'ring_gap',
			array(
				'label'      => 'فاصلهٔ حلقه تا رنگ',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 8 ) ),
				'selectors'  => array( $wrap => '--ard-pc-ring-gap:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'layout!' => 'chip' ),
			)
		);

		$this->add_control(
			'ring_gap_color',
			array(
				'label'       => 'رنگ فاصلهٔ حلقه',
				'type'        => Controls_Manager::COLOR,
				'description' => 'معمولاً همان رنگ زمینهٔ صفحه.',
				'selectors'   => array( $wrap => '--ard-pc-ring-gap-c:{{VALUE}};' ),
				'condition'   => array( 'layout!' => 'chip' ),
			)
		);

		$this->add_control(
			'disabled_opacity',
			array(
				'label'     => 'شفافیت رنگ ناموجود',
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0.1, 'max' => 1, 'step' => 0.05 ) ),
				'selectors' => array( $wrap => '--ard-pc-disabled:{{SIZE}};' ),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ تولتیپ */

		$this->start_controls_section(
			'section_style_tip',
			array(
				'label'     => 'تولتیپ',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'tooltip!' => 'none' ),
			)
		);

		$this->add_control(
			'tip_bg',
			array(
				'label'     => 'زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $wrap => '--ard-pc-tip-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'tip_ink',
			array(
				'label'     => 'رنگ متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $wrap => '--ard-pc-tip-ink:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tip_typo',
				'selector' => '{{WRAPPER}} .ard-prod-color__tip',
			)
		);

		$this->add_responsive_control(
			'tip_pad',
			array(
				'label'      => 'فاصلهٔ داخلی',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array( '{{WRAPPER}} .ard-prod-color__tip' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'tip_radius',
			array(
				'label'      => 'گردی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 20 ) ),
				'selectors'  => array( $wrap => '--ard-pc-tip-r:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'tip_offset',
			array(
				'label'      => 'فاصله از رنگ',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 30 ) ),
				'selectors'  => array( $wrap => '--ard-pc-tip-off:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'tip_arrow',
			array(
				'label'        => 'فلش زیر تولتیپ',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'ard-pcolor-arrow-',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tip_shadow',
				'selector' => '{{WRAPPER}} .ard-prod-color__tip',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( 'manual' === $s['source'] ) {
			$options = array();

			foreach ( (array) $s['colors'] as $c ) {
				if ( '' === trim( (string) $c['color_name'] ) ) {
					continue;
				}

				$options[] = array(
					'label'     => $c['color_name'],
					'hex'       => $c['color_hex'],
					'available' => true,
				);
			}
		} else {
			$product = Ardal_Product::current();

			if ( ! $product ) {
				$this->render_empty( 'محصولی پیدا نشد. این ویجت را روی قالب تک‌محصول بگذارید یا حالت دستی را انتخاب کنید.' );

				return;
			}

			$variants = Ardal_Product::variation_options( $product, $s['color_attribute'] );
			$options  = $variants['options'];
			$attr_key = isset( $variants['key'] ) ? $variants['key'] : '';
			$preset   = Ardal_Product::default_variation( $product );
			$chosen   = ( $attr_key && isset( $preset[ $attr_key ] ) ) ? $preset[ $attr_key ] : '';

			foreach ( $options as $i => $option ) {
				$options[ $i ]['hex'] = Ardal_Helpers::guess_color( $option['label'] );
			}
		}

		if ( empty( $options ) ) {
			$this->render_empty( sprintf( 'این محصول ویژگی «%s» ندارد. ویژگی را به محصول اضافه کنید یا حالت دستی را انتخاب کنید.', $s['color_attribute'] ) );

			return;
		}

		$chosen = isset( $chosen ) ? $chosen : '';
		$first  = '';

		// گزینهٔ از پیش انتخاب‌شده، وگرنه اولین گزینهٔ موجود.
		foreach ( $options as $option ) {
			if ( '' !== $chosen && isset( $option['slug'] ) && $option['slug'] === $chosen ) {
				$first = $option['label'];

				break;
			}
		}

		if ( '' === $first ) {
			foreach ( $options as $option ) {
				if ( ! empty( $option['available'] ) ) {
					$first  = $option['label'];
					$chosen = isset( $option['slug'] ) ? $option['slug'] : '';

					break;
				}
			}
		}

		// کلید ویژگی به جاوااسکریپت می‌رسد تا تطبیق واریاسیون به نام ثابت
		// attribute_pa_color گره نخورد؛ فروشگاه‌ها ویژگی‌شان را هرجور می‌نامند.
		$attr_key = isset( $attr_key ) ? $attr_key : '';
		$hide_wc  = '' !== $attr_key && 'auto' === $s['source'] && 'yes' === $s['hide_wc'];
		?>
		<div
			class="ard-prod-variants ard-prod-variants--colors"
			data-ard-color-group
			data-attr="<?php echo esc_attr( $attr_key ); ?>"
			<?php echo $hide_wc ? 'data-hide-wc' : ''; ?>
		>
		<?php if ( $hide_wc ) : ?>
			<?php // پیش از اجرای جاوااسکریپت هم پنهان باشد تا سلکت یک لحظه دیده نشود. ?>
			<style>form.variations_form .variations tr:has(select[name="<?php echo esc_attr( $attr_key ); ?>"]){display:none}</style>
		<?php endif; ?>
			<div class="ard-prod-variants__header">
				<span class="ard-prod-variants__label"><?php echo esc_html( $s['label'] ); ?>:</span>
				<?php if ( 'yes' === $s['show_selected'] ) : ?>
					<span class="ard-prod-variants__selected" data-ard-color-target><?php echo esc_html( $first ); ?></span>
				<?php endif; ?>
			</div>
			<div class="ard-prod-colors">
				<?php
				$active_set = false;

				// همان تور ایمنیِ ویجت سایز: اسلاگِ نایافته به «اولین موجود» برمی‌گردد.
				$found = false;

				foreach ( $options as $c ) {
					if ( '' !== $chosen && isset( $c['slug'] ) && $c['slug'] === $chosen && ! empty( $c['available'] ) ) {
						$found = true;

						break;
					}
				}

				if ( ! $found ) {
					$chosen = '';
				}

				foreach ( $options as $c ) :
					$available = ! empty( $c['available'] );
					$slug      = isset( $c['slug'] ) ? $c['slug'] : '';
					$active    = ! $active_set && $available && ( '' === $chosen || $slug === $chosen );

					if ( $active ) {
						$active_set = true;
					}
					?>
					<button
						type="button"
						class="ard-prod-color<?php echo $active ? ' is-active' : ''; ?><?php echo $available ? '' : ' is-disabled'; ?>"
						data-color-name="<?php echo esc_attr( $c['label'] ); ?>"
						data-color-slug="<?php echo esc_attr( isset( $c['slug'] ) ? $c['slug'] : '' ); ?>"
						style="--swatch-color:<?php echo esc_attr( $c['hex'] ); ?>"
						aria-label="<?php echo esc_attr( $c['label'] . ( $available ? '' : ' (ناموجود)' ) ); ?>"
						<?php echo $available ? '' : ' disabled aria-disabled="true"'; ?>
					>
						<span class="ard-prod-color__swatch" aria-hidden="true"></span>
						<span class="ard-prod-color__name"><?php echo esc_html( $c['label'] ); ?></span>
						<span class="ard-prod-color__tip" aria-hidden="true"><?php echo esc_html( $c['label'] . ( $available ? '' : ' (ناموجود)' ) ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
