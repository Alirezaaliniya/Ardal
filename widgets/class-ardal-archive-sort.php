<?php
/**
 * ویجت مرتب‌سازی آرشیو — افقی در دسکتاپ، کشوی پایینی در موبایل.
 *
 * دو نسخه از گزینه‌ها رندر می‌شود: نوار افقی و کشوی موبایل. این‌طوری استایل
 * هرکدام جدا از دیگری در المنتور تنظیم می‌شود و یکی روی دیگری نشت نمی‌کند.
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
use Elementor\Icons_Manager;
use Elementor\Repeater;

class Ardal_Widget_Archive_Sort extends Ardal_Archive_Widget_Base {

	public function get_name() {
		return 'ardal-archive-sort';
	}

	public function get_title() {
		return 'اردال — مرتب‌سازی';
	}

	public function get_icon() {
		return 'eicon-sort-amount-desc';
	}

	protected function register_controls() {

		/* ------------------------------------------------ گزینه‌ها */

		$this->start_controls_section(
			'section_items',
			array( 'label' => 'گزینه‌های مرتب‌سازی' )
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'key',
			array(
				'label'   => 'نوع مرتب‌سازی',
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => Ardal_Archive::sort_labels() + array( 'custom' => 'مقدار دلخواه' ),
			)
		);

		$repeater->add_control(
			'custom_value',
			array(
				'label'       => 'مقدار orderby',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => 'مقداری که در آدرس به‌شکل ?orderby= می‌نشیند؛ کد سایت یا افزونهٔ دیگری باید آن را پشتیبانی کند.',
				'condition'   => array( 'key' => 'custom' ),
			)
		);

		$repeater->add_control(
			'label',
			array(
				'label'       => 'عنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'خالی = نام پیش‌فرض',
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => 'گزینه‌ها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ label || ( "custom" === key ? custom_value : key ) }}}',
				'default'     => array(
					array( 'key' => 'date', 'label' => 'جدیدترین' ),
					array( 'key' => 'popularity', 'label' => 'پرفروش‌ترین' ),
					array( 'key' => 'price', 'label' => 'ارزان‌ترین' ),
					array( 'key' => 'price-desc', 'label' => 'گران‌ترین' ),
					array( 'key' => 'rating', 'label' => 'بیشترین امتیاز' ),
				),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ دسکتاپ */

		$this->start_controls_section(
			'section_desktop',
			array( 'label' => 'نوار دسکتاپ' )
		);

		$this->add_control(
			'bar_style',
			array(
				'label'   => 'شکل نمایش',
				'type'    => Controls_Manager::SELECT,
				'default' => 'links',
				'options' => array(
					'links' => 'لینک‌های کنارهم با خط زیر',
					'pills' => 'دکمه‌های قرصی',
				),
			)
		);

		$this->add_control(
			'show_cap',
			array(
				'label'        => 'نمایش برچسب',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'cap_text',
			array(
				'label'     => 'متن برچسب',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'مرتب‌سازی:',
				'condition' => array( 'show_cap' => 'yes' ),
			)
		);

		$this->add_control(
			'cap_icon',
			array(
				'label'       => 'آیکون برچسب',
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'description' => 'خالی = آیکون مرتب‌سازی پیش‌فرض.',
				'condition'   => array( 'show_cap' => 'yes' ),
			)
		);

		$this->add_control(
			'show_cap_icon',
			array(
				'label'        => 'نمایش آیکون برچسب',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'show_cap' => 'yes' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ موبایل */

		$this->start_controls_section(
			'section_mobile',
			array( 'label' => 'موبایل' )
		);

		$this->add_control(
			'mobile_mode',
			array(
				'label'       => 'نمایش در موبایل',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'drawer',
				'options'     => array(
					'drawer' => 'دکمه + کشوی پایین‌آمدنی',
					'inline' => 'همان نوار افقی (اسکرول‌شونده)',
				),
				'label_block' => true,
			)
		);

		$this->add_control(
			'trigger_text',
			array(
				'label'     => 'متن دکمه',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'مرتب‌سازی',
				'condition' => array( 'mobile_mode' => 'drawer' ),
			)
		);

		$this->add_control(
			'trigger_current',
			array(
				'label'        => 'نمایش گزینهٔ انتخاب‌شده روی دکمه',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'mobile_mode' => 'drawer' ),
			)
		);

		$this->add_control(
			'trigger_icon',
			array(
				'label'       => 'آیکون دکمه',
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'description' => 'خالی = آیکون مرتب‌سازی پیش‌فرض.',
				'condition'   => array( 'mobile_mode' => 'drawer' ),
			)
		);

		$this->add_control(
			'sheet_title',
			array(
				'label'     => 'عنوان کشو',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'مرتب‌سازی بر اساس',
				'separator' => 'before',
				'condition' => array( 'mobile_mode' => 'drawer' ),
			)
		);

		$this->add_control(
			'sheet_grip',
			array(
				'label'        => 'دستگیرهٔ بالای کشو',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'condition'    => array( 'mobile_mode' => 'drawer' ),
			)
		);

		$this->add_control(
			'close_icon',
			array(
				'label'       => 'آیکون بستن',
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'description' => 'خالی = ضربدر داخل دایره.',
				'condition'   => array( 'mobile_mode' => 'drawer' ),
			)
		);

		$this->add_control(
			'check_icon',
			array(
				'label'       => 'آیکون گزینهٔ انتخاب‌شده',
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'description' => 'خالی = تیک داخل دایره.',
				'condition'   => array( 'mobile_mode' => 'drawer' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ ایجکس */

		$this->start_controls_section(
			'section_ajax',
			array( 'label' => 'بارگذاری' )
		);

		$this->add_control(
			'ajax',
			array(
				'label'        => 'بارگذاری بدون رفرش صفحه (ایجکس)',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => 'بله',
				'label_off'    => 'خیر',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'ajax_regions',
			array(
				'label'       => 'سلکتور ناحیه‌های اضافه',
				'type'        => Controls_Manager::TEXT,
				'default'     => '.elementor-widget-loop-grid',
				'label_block' => true,
				'description' => 'ناحیه‌هایی بیرون از ویجت‌های اردال که باید تازه شوند؛ مثلاً «لوپ گرید» المنتور. با ویرگول جدا کنید.',
				'condition'   => array( 'ajax' => 'yes' ),
			)
		);

		$this->add_control(
			'ajax_scroll',
			array(
				'label'        => 'اسکرول به بالای نتایج',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'ajax' => 'yes' ),
			)
		);

		$this->end_controls_section();

		$this->add_bar_style_controls();
		$this->add_trigger_style_controls();
		$this->add_sheet_style_controls();
	}

	/**
	 * استایل نوار افقی دسکتاپ.
	 */
	protected function add_bar_style_controls() {
		$item = '{{WRAPPER}} .ard-sort__item';

		$this->start_controls_section(
			'section_style_bar',
			array(
				'label' => 'نوار دسکتاپ',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'bar_justify',
			array(
				'label'     => 'چیدمان',
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start'    => array( 'title' => 'راست', 'icon' => 'eicon-justify-start-h' ),
					'center'        => array( 'title' => 'وسط', 'icon' => 'eicon-justify-center-h' ),
					'flex-end'      => array( 'title' => 'چپ', 'icon' => 'eicon-justify-end-h' ),
					'space-between' => array( 'title' => 'پخش', 'icon' => 'eicon-justify-space-between-h' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .ard-sort__bar' => 'justify-content:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'bar_gap',
			array(
				'label'      => 'فاصله گزینه‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-sort__items' => 'gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'cap_heading',
			array(
				'label'     => 'برچسب',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'cap_color',
			array(
				'label'     => 'رنگ متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-sort__cap' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'cap_icon_color',
			array(
				'label'     => 'رنگ آیکون',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-sort__cap-icon'                    => 'color:{{VALUE}};',
					'{{WRAPPER}} .ard-sort__cap-icon svg.e-font-icon-svg' => 'fill:{{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cap_typo',
				'selector' => '{{WRAPPER}} .ard-sort__cap',
			)
		);

		$this->add_control(
			'item_heading',
			array(
				'label'     => 'گزینه‌ها',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'item_typo',
				'selector' => $item,
			)
		);

		$this->add_responsive_control(
			'item_padding',
			array(
				'label'      => 'فاصله داخلی',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					$item => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'item_radius',
			array(
				'label'      => 'گردی گوشه',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					$item => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'item_border',
				'selector' => $item,
			)
		);

		$this->start_controls_tabs( 'item_tabs' );

		$states = array(
			'normal' => array( 'عادی', $item ),
			'hover'  => array( 'هاور', $item . ':hover' ),
			'active' => array( 'فعال', $item . '.is-on' ),
		);

		foreach ( $states as $state => $info ) {
			$this->start_controls_tab( 'item_tab_' . $state, array( 'label' => $info[0] ) );

			$this->add_control(
				'item_color_' . $state,
				array(
					'label'     => 'رنگ متن',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( $info[1] => 'color:{{VALUE}};' ),
				)
			);

			$this->add_control(
				'item_bg_' . $state,
				array(
					'label'     => 'رنگ زمینه',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( $info[1] => 'background:{{VALUE}};' ),
				)
			);

			$this->add_control(
				'item_border_color_' . $state,
				array(
					'label'     => 'رنگ حاشیه',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( $info[1] => 'border-color:{{VALUE}};' ),
				)
			);

			if ( 'active' === $state ) {
				$this->add_control(
					'item_line_color',
					array(
						'label'     => 'رنگ خط زیر',
						'type'      => Controls_Manager::COLOR,
						'selectors' => array( '{{WRAPPER}} .ard-sort__bar' => '--ard-sort-line:{{VALUE}};' ),
						'condition' => array( 'bar_style' => 'links' ),
					)
				);

				$this->add_control(
					'item_line_size',
					array(
						'label'      => 'ضخامت خط زیر',
						'type'       => Controls_Manager::SLIDER,
						'size_units' => array( 'px' ),
						'range'      => array( 'px' => array( 'min' => 0, 'max' => 8 ) ),
						'selectors'  => array( '{{WRAPPER}} .ard-sort__bar' => '--ard-sort-line-h:{{SIZE}}{{UNIT}};' ),
						'condition'  => array( 'bar_style' => 'links' ),
					)
				);
			}

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * استایل دکمهٔ بازکردن کشو در موبایل.
	 */
	protected function add_trigger_style_controls() {
		$btn = '{{WRAPPER}} .ard-sort__trigger';

		$this->start_controls_section(
			'section_style_trigger',
			array(
				'label'     => 'دکمه موبایل',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'mobile_mode' => 'drawer' ),
			)
		);

		$this->add_responsive_control(
			'trigger_align',
			array(
				'label'                => 'چیدمان',
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'start'   => array( 'title' => 'راست', 'icon' => 'eicon-h-align-right' ),
					'center'  => array( 'title' => 'وسط', 'icon' => 'eicon-h-align-center' ),
					'end'     => array( 'title' => 'چپ', 'icon' => 'eicon-h-align-left' ),
					'stretch' => array( 'title' => 'تمام‌عرض', 'icon' => 'eicon-h-align-stretch' ),
				),
				'selectors_dictionary' => array(
					'start'   => 'width:fit-content;margin-inline-start:0;margin-inline-end:auto;',
					'center'  => 'width:fit-content;margin-inline:auto;',
					'end'     => 'width:fit-content;margin-inline-start:auto;margin-inline-end:0;',
					'stretch' => 'width:100%;margin-inline:0;justify-content:center;',
				),
				'selectors'            => array( $btn => '{{VALUE}}' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'trigger_typo',
				'selector' => $btn,
			)
		);

		$this->add_responsive_control(
			'trigger_height',
			array(
				'label'      => 'ارتفاع',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 24, 'max' => 100 ) ),
				'selectors'  => array( $btn => 'height:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'trigger_padding',
			array(
				'label'      => 'فاصله داخلی',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					$btn => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'trigger_radius',
			array(
				'label'      => 'گردی گوشه',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					$btn => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'trigger_gap',
			array(
				'label'      => 'فاصله آیکون و متن',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 30 ) ),
				'selectors'  => array( $btn => 'gap:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'trigger_icon_size',
			array(
				'label'      => 'اندازه آیکون',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 8, 'max' => 48 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-sort__trigger-icon'                                              => 'font-size:{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ard-sort__trigger-icon svg, {{WRAPPER}} .ard-sort__trigger-icon img' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'trigger_border',
				'selector' => $btn,
			)
		);

		$this->start_controls_tabs( 'trigger_tabs' );

		foreach ( array( 'normal' => 'عادی', 'hover' => 'هاور' ) as $state => $state_label ) {
			$sel = 'hover' === $state ? $btn . ':hover' : $btn;

			$this->start_controls_tab( 'trigger_tab_' . $state, array( 'label' => $state_label ) );

			$this->add_control(
				'trigger_color_' . $state,
				array(
					'label'     => 'رنگ متن',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( $sel => 'color:{{VALUE}};' ),
				)
			);

			$this->add_control(
				'trigger_icon_color_' . $state,
				array(
					'label'     => 'رنگ آیکون',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						$sel . ' .ard-sort__trigger-icon'                    => 'color:{{VALUE}};',
						$sel . ' .ard-sort__trigger-icon svg.e-font-icon-svg' => 'fill:{{VALUE}};',
					),
				)
			);

			$this->add_control(
				'trigger_current_color_' . $state,
				array(
					'label'     => 'رنگ گزینهٔ انتخاب‌شده',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( $sel . ' .ard-sort__trigger-current' => 'color:{{VALUE}};' ),
					'condition' => array( 'trigger_current' => 'yes' ),
				)
			);

			$this->add_control(
				'trigger_bg_' . $state,
				array(
					'label'     => 'رنگ زمینه',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( $sel => 'background:{{VALUE}};' ),
				)
			);

			$this->add_control(
				'trigger_border_color_' . $state,
				array(
					'label'     => 'رنگ حاشیه',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( $sel => 'border-color:{{VALUE}};' ),
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'trigger_shadow_' . $state,
					'selector' => $sel,
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * استایل کشوی موبایل.
	 */
	protected function add_sheet_style_controls() {
		$sheet = '{{WRAPPER}} .ard-sort-sheet';

		$this->start_controls_section(
			'section_style_sheet',
			array(
				'label'     => 'کشوی موبایل',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'mobile_mode' => 'drawer' ),
			)
		);

		$this->add_control(
			'sheet_bg',
			array(
				'label'     => 'رنگ زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $sheet => 'background:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'sheet_radius',
			array(
				'label'      => 'گردی گوشه‌های بالا',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 48 ) ),
				'selectors'  => array( $sheet => 'border-radius:{{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0;' ),
			)
		);

		$this->add_control(
			'sheet_side',
			array(
				'label'      => 'فاصله از کناره‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( $sheet => '--ard-sort-sheet-x:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'sheet_max',
			array(
				'label'      => 'بیشترین ارتفاع',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'vh' ),
				'range'      => array( 'vh' => array( 'min' => 30, 'max' => 96 ) ),
				'selectors'  => array( $sheet => '--ard-sheet-max:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'sheet_shadow',
				'selector' => $sheet,
			)
		);

		/* ---------------------------------------- سربرگ */

		$this->add_control(
			'sheet_head_heading',
			array(
				'label'     => 'سربرگ',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sheet_title_color',
			array(
				'label'     => 'رنگ عنوان',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-sort-sheet__title' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sheet_title_typo',
				'label'    => 'قلم عنوان',
				'selector' => '{{WRAPPER}} .ard-sort-sheet__title',
			)
		);

		$this->add_control(
			'sheet_close_color',
			array(
				'label'     => 'رنگ دکمه بستن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-sort-sheet__close'                    => 'color:{{VALUE}};',
					'{{WRAPPER}} .ard-sort-sheet__close svg.e-font-icon-svg' => 'fill:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sheet_close_size',
			array(
				'label'      => 'اندازه دکمه بستن',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 12, 'max' => 48 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-sort-sheet__close'                                               => 'font-size:{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ard-sort-sheet__close svg, {{WRAPPER}} .ard-sort-sheet__close img' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'sheet_head_padding',
			array(
				'label'      => 'فاصله داخلی سربرگ',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-sort-sheet__head' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		/* ---------------------------------------- گزینه‌ها */

		$this->add_control(
			'sheet_items_heading',
			array(
				'label'     => 'گزینه‌ها',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sheet_item_typo',
				'selector' => '{{WRAPPER}} .ard-sort-sheet__item',
			)
		);

		$this->add_control(
			'sheet_item_color',
			array(
				'label'     => 'رنگ متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-sort-sheet__item' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'sheet_item_active_color',
			array(
				'label'     => 'رنگ متن گزینهٔ فعال',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-sort-sheet__item.is-on' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'sheet_item_active_bg',
			array(
				'label'     => 'زمینه گزینهٔ فعال',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-sort-sheet__item.is-on' => 'background:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'sheet_item_active_weight',
			array(
				'label'     => 'ضخامت قلم گزینهٔ فعال',
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''    => 'پیش‌فرض',
					'400' => 'معمولی',
					'500' => 'متوسط',
					'700' => 'ضخیم',
				),
				'selectors' => array( '{{WRAPPER}} .ard-sort-sheet__item.is-on' => 'font-weight:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'sheet_item_padding',
			array(
				'label'      => 'فاصله داخلی هر گزینه',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-sort-sheet__item' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'sheet_divider',
			array(
				'label'        => 'خط جداکننده',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'selectors'    => array(
					'{{WRAPPER}} .ard-sort-sheet__list > li + li' => 'border-top-style:solid;',
				),
			)
		);

		$this->add_control(
			'sheet_divider_color',
			array(
				'label'     => 'رنگ خط جداکننده',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-sort-sheet__list > li + li' => 'border-top-color:{{VALUE}};' ),
				'condition' => array( 'sheet_divider' => 'yes' ),
			)
		);

		$this->add_control(
			'sheet_check_color',
			array(
				'label'     => 'رنگ آیکون انتخاب',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-sort-sheet__check'                    => 'color:{{VALUE}};',
					'{{WRAPPER}} .ard-sort-sheet__check svg.e-font-icon-svg' => 'fill:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sheet_check_size',
			array(
				'label'      => 'اندازه آیکون انتخاب',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 10, 'max' => 48 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-sort-sheet__check'                                               => 'font-size:{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ard-sort-sheet__check svg, {{WRAPPER}} .ard-sort-sheet__check img' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/* --------------------------------------------------------------------- */
	/* رندر                                                                    */
	/* --------------------------------------------------------------------- */

	protected function render() {
		if ( $this->require_woo() ) {
			return;
		}

		$s       = $this->get_settings_for_display();
		$options = $this->options( $s );

		if ( ! $options ) {
			return;
		}

		$drawer  = 'inline' !== $s['mobile_mode'];
		$sheet   = 'ard-sort-sheet-' . $this->get_id();
		$current = '';

		foreach ( $options as $option ) {
			if ( $option['current'] ) {
				$current = $option['label'];
			}
		}

		printf(
			'<div class="ard-sort ard-sort--%s" data-ard-region="sortbar"%s>',
			$drawer ? 'drawer' : 'inline',
			$this->ajax_attrs( $s ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- در همان تابع escape شده.
		);

		$this->render_bar( $s, $options );

		if ( $drawer ) {
			printf(
				'<button type="button" class="ard-sort__trigger" data-ard-arch-open="sortbar" aria-controls="%s" aria-expanded="false"><span class="ard-sort__trigger-icon" aria-hidden="true">%s</span><span class="ard-sort__trigger-text">%s</span>%s</button>',
				esc_attr( $sheet ),
				$this->pick_icon( $s['trigger_icon'] ?? array(), 'sort', 18 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				esc_html( $s['trigger_text'] ),
				'yes' === $s['trigger_current'] && '' !== $current
					? '<span class="ard-sort__trigger-current">' . esc_html( $current ) . '</span>'
					: ''
			);
		}

		echo '</div>';

		if ( $drawer ) {
			$this->render_sheet( $s, $options, $sheet );
			Ardal_Archive::render_scrim();
		}
	}

	/**
	 * گزینه‌های نهایی: کلید، عنوان، آدرس و وضعیت انتخاب.
	 *
	 * @param array $s
	 * @return array
	 */
	protected function options( $s ) {
		$labels  = Ardal_Archive::sort_labels();
		$current = Ardal_Archive::current_orderby();
		$out     = array();

		foreach ( (array) $s['items'] as $item ) {
			$value = 'custom' === $item['key']
				? sanitize_key( (string) $item['custom_value'] )
				: (string) $item['key'];

			if ( '' === $value || isset( $out[ $value ] ) ) {
				continue;
			}

			$label = trim( (string) $item['label'] );

			if ( '' === $label ) {
				$label = isset( $labels[ $value ] ) ? $labels[ $value ] : $value;
			}

			$out[ $value ] = array(
				'label'   => $label,
				'current' => $value === $current,
				'url'     => Ardal_Archive::url_with( array( 'orderby' => $value ) ),
			);
		}

		return $out;
	}

	/**
	 * آیکون انتخابی المنتور، وگرنه آیکون آمادهٔ اردال.
	 *
	 * @param array  $pick
	 * @param string $fallback
	 * @param int    $size
	 * @return string
	 */
	protected function pick_icon( $pick, $fallback, $size = 18 ) {
		if ( ! empty( $pick['value'] ) ) {
			ob_start();
			Icons_Manager::render_icon( $pick, array( 'aria-hidden' => 'true' ) );

			return (string) ob_get_clean();
		}

		return Ardal_Archive::icon( $fallback, $size );
	}

	/**
	 * نوار افقی.
	 *
	 * @param array $s
	 * @param array $options
	 */
	protected function render_bar( $s, $options ) {
		printf( '<div class="ard-sort__bar ard-sort__bar--%s">', 'pills' === $s['bar_style'] ? 'pills' : 'links' );

		if ( 'yes' === $s['show_cap'] && '' !== trim( (string) $s['cap_text'] ) ) {
			printf(
				'<span class="ard-sort__cap">%s%s</span>',
				'yes' === $s['show_cap_icon']
					? '<span class="ard-sort__cap-icon" aria-hidden="true">' . $this->pick_icon( $s['cap_icon'] ?? array(), 'sort', 17 ) . '</span>'
					: '', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				esc_html( $s['cap_text'] )
			);
		}

		echo '<div class="ard-sort__items">';

		foreach ( $options as $option ) {
			printf(
				'<a class="ard-sort__item%s" href="%s"%s>%s</a>',
				$option['current'] ? ' is-on' : '',
				esc_url( $option['url'] ),
				$option['current'] ? ' aria-current="true"' : '',
				esc_html( $option['label'] )
			);
		}

		echo '</div></div>';
	}

	/**
	 * کشوی موبایل.
	 *
	 * @param array  $s
	 * @param array  $options
	 * @param string $id
	 */
	protected function render_sheet( $s, $options, $id ) {
		$check = $this->pick_icon( $s['check_icon'] ?? array(), 'check-circle', 26 );

		printf(
			'<div class="ard-sort-sheet" id="%s" role="dialog" aria-modal="true" aria-label="%s">',
			esc_attr( $id ),
			esc_attr( $s['sheet_title'] )
		);

		if ( 'yes' === $s['sheet_grip'] ) {
			echo '<span class="ard-sort-sheet__grip" aria-hidden="true"></span>';
		}

		echo '<div class="ard-sort-sheet__head">';
		printf( '<span class="ard-sort-sheet__title">%s</span>', esc_html( $s['sheet_title'] ) );
		printf(
			'<button type="button" class="ard-sort-sheet__close" data-ard-arch-close aria-label="بستن">%s</button>',
			$this->pick_icon( $s['close_icon'] ?? array(), 'close-circle', 30 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
		echo '</div>';

		echo '<ul class="ard-sort-sheet__list">';

		foreach ( $options as $option ) {
			printf(
				'<li><a class="ard-sort-sheet__item%s" href="%s"%s><span>%s</span>%s</a></li>',
				$option['current'] ? ' is-on' : '',
				esc_url( $option['url'] ),
				$option['current'] ? ' aria-current="true"' : '',
				esc_html( $option['label'] ),
				$option['current'] ? '<span class="ard-sort-sheet__check" aria-hidden="true">' . $check . '</span>' : '' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		echo '</ul></div>';
	}
}
