<?php
/**
 * ویجت جست‌وجوی زندهٔ محصول.
 *
 * کادر جست‌وجو با پنل پیشنهاد که همان‌جا نتیجه‌ها را نشان می‌دهد. مارک‌آپ
 * نتیجه‌ها را سرور می‌سازد (Ardal_Search) تا کارت‌ها یک‌جا نگهداری شوند.
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

class Ardal_Widget_Search extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-search';
	}

	public function get_title() {
		return 'اردال — جست‌وجوی زنده';
	}

	public function get_icon() {
		return 'eicon-site-search';
	}

	public function get_keywords() {
		return array( 'اردال', 'جستجو', 'جست‌وجو', 'زنده', 'ایجکس', 'search', 'live', 'ajax' );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-search' );
	}

	public function get_script_depends() {
		return array( 'ardal-search' );
	}

	protected function register_controls() {

		/* ------------------------------------------------ کادر */

		$this->start_controls_section(
			'section_box',
			array( 'label' => 'کادر جست‌وجو' )
		);

		$this->add_control(
			'template',
			array(
				'label'       => 'قالب ظاهری',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'classic',
				'options'     => array(
					'classic' => 'کلاسیک',
					'modern'  => 'مدرن (کادر قرصی، تاریخچه و جست‌وجوی پرمخاطب)',
				),
				'label_block' => true,
			)
		);

		$this->add_control(
			'placeholder',
			array(
				'label'       => 'متن راهنمای داخل کادر',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'نام کالا یا برند را بنویسید…',
				'label_block' => true,
			)
		);

		$this->add_control(
			'show_icon',
			array(
				'label'        => 'ذره‌بین داخل کادر',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_button',
			array(
				'label'        => 'دکمه جست‌وجو',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'       => 'متن دکمه',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'جست‌وجو',
				'description' => 'خالی = فقط آیکون ذره‌بین.',
				'condition'   => array( 'show_button' => 'yes' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ رفتار */

		$this->start_controls_section(
			'section_behavior',
			array( 'label' => 'رفتار' )
		);

		$this->add_control(
			'min_chars',
			array(
				'label'       => 'کمینه تعداد حرف',
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'max'         => 10,
				'default'     => 3,
				'description' => 'زیر این تعداد، درخواستی به سرور نمی‌رود.',
			)
		);

		$this->add_control(
			'delay',
			array(
				'label'       => 'مکث پیش از جست‌وجو',
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 2000,
				'step'        => 50,
				'default'     => 300,
				'description' => 'به میلی‌ثانیه. تایپِ پشت‌سرهم را یک درخواست می‌کند.',
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'   => 'تعداد نتیجه در پنل',
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 30,
				'default' => 8,
			)
		);

		$this->add_control(
			'hint_text',
			array(
				'label'       => 'راهنمای پیش از تایپ',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => 'خالی = از روی «کمینه تعداد حرف» ساخته می‌شود.',
			)
		);

		$this->add_control(
			'empty_text',
			array(
				'label'       => 'متن وقتی چیزی پیدا نشد',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'چیزی پیدا نشد.',
				'label_block' => true,
			)
		);

		$this->add_control(
			'show_more',
			array(
				'label'        => 'لینک «دیدن همه»',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'more_text',
			array(
				'label'     => 'متن لینک',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'دیدن همهٔ نتیجه‌ها',
				'condition' => array( 'show_more' => 'yes' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ تاریخچه و پرمخاطب */

		$this->start_controls_section(
			'section_home',
			array(
				'label'     => 'تاریخچه و جست‌وجوی پرمخاطب',
				'condition' => array( 'template' => 'modern' ),
			)
		);

		$this->add_control(
			'show_history',
			array(
				'label'        => 'تاریخچه جست‌وجوی کاربر',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'در مرورگر همان کاربر ذخیره می‌شود و هر مورد با ضربدر کنارش حذف می‌شود.',
			)
		);

		$this->add_control(
			'history_title',
			array(
				'label'     => 'عنوان تاریخچه',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'تاریخچه جستجوی شما',
				'condition' => array( 'show_history' => 'yes' ),
			)
		);

		$this->add_control(
			'history_limit',
			array(
				'label'     => 'تعداد موارد تاریخچه',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 20,
				'default'   => 5,
				'condition' => array( 'show_history' => 'yes' ),
			)
		);

		$this->add_control(
			'history_clear',
			array(
				'label'        => 'دکمه «پاک کردن همه»',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'condition'    => array( 'show_history' => 'yes' ),
			)
		);

		$this->add_control(
			'history_clear_text',
			array(
				'label'     => 'متن پاک کردن همه',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'پاک کردن همه',
				'condition' => array( 'show_history' => 'yes', 'history_clear' => 'yes' ),
			)
		);

		$this->add_control(
			'show_popular',
			array(
				'label'        => 'جست‌وجوی پرمخاطب',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'popular_title',
			array(
				'label'     => 'عنوان پرمخاطب',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'جستجوی پر مخاطب',
				'condition' => array( 'show_popular' => 'yes' ),
			)
		);

		$this->add_control(
			'popular_source',
			array(
				'label'       => 'منبع عبارت‌ها',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'mixed',
				'options'     => array(
					'mixed'  => 'دستی، و بقیه خودکار',
					'auto'   => 'خودکار (پرتکرارترین جست‌وجوهای سایت)',
					'manual' => 'فقط دستی',
				),
				'label_block' => true,
				'description' => 'حالت خودکار از جست‌وجوهای واقعی بازدیدکننده‌ها شمرده می‌شود؛ فقط عبارت‌هایی که محصولی پیدا کرده‌اند.',
				'condition'   => array( 'show_popular' => 'yes' ),
			)
		);

		$this->add_control(
			'popular_manual',
			array(
				'label'       => 'عبارت‌های دستی',
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'rows'        => 5,
				'placeholder' => "هایلایتر\nرژگونه\nکانسیلر",
				'description' => 'هر عبارت در یک خط.',
				'condition'   => array( 'show_popular' => 'yes', 'popular_source!' => 'auto' ),
			)
		);

		$this->add_control(
			'popular_limit',
			array(
				'label'     => 'تعداد عبارت‌ها',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 30,
				'default'   => 8,
				'condition' => array( 'show_popular' => 'yes' ),
			)
		);

		$this->add_control(
			'popular_min',
			array(
				'label'       => 'کمترین دفعهٔ جست‌وجو',
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'max'         => 1000,
				'default'     => 2,
				'description' => 'عبارت خودکار باید دست‌کم این تعداد بار جست‌وجو شده باشد.',
				'condition'   => array( 'show_popular' => 'yes', 'popular_source!' => 'manual' ),
			)
		);

		$this->add_control(
			'home_click',
			array(
				'label'       => 'با زدن روی یک عبارت',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'go',
				'options'     => array(
					'go'   => 'برود به صفحهٔ نتیجه‌ها',
					'fill' => 'در کادر بنشیند و نتیجهٔ زنده نشان دهد',
				),
				'label_block' => true,
				'separator'   => 'before',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ بخش‌های نتیجه */

		$this->start_controls_section(
			'section_card',
			array( 'label' => 'بخش‌های هر نتیجه' )
		);

		foreach ( $this->parts() as $key => $part ) {
			$this->add_control(
				'card_' . $key,
				array(
					'label'        => $part['label'],
					'type'         => Controls_Manager::SWITCHER,
					'default'      => $part['on'] ? 'yes' : '',
					'return_value' => 'yes',
				)
			);
		}

		$this->end_controls_section();

		$this->add_box_style_controls();
		$this->add_button_style_controls();
		$this->add_panel_style_controls();
		$this->add_modern_style_controls();
	}

	/*
	 * سلکتورهای استایل عمداً سنگین‌اند. ریست قالب Hello روی input[type=search]
	 * حاشیهٔ تیره، گردی و پدینگ می‌گذارد (۰،۱،۱) و «فیلدهای فرم» کیت المنتور
	 * تا (۰،۳،۱) می‌رسد؛ هر دو از سلکتورهای ساده‌ی ویجت قوی‌تر بودند و یک کادر
	 * دوم با حاشیهٔ سیاه داخل کادر جست‌وجو می‌ساختند.
	 */
	const SEL_BOX   = '{{WRAPPER}} .ard-se .ard-se__box';
	const SEL_INPUT = '{{WRAPPER}} .ard-se .ard-se__box input.ard-se__input';
	const SEL_BTN   = '{{WRAPPER}} .ard-se .ard-se__btn[class]';
	const SEL_PANEL = '{{WRAPPER}} .ard-se .ard-se__panel';

	/**
	 * کادر جست‌وجو و فیلد متنی.
	 */
	protected function add_box_style_controls() {
		$this->start_controls_section(
			'section_style',
			array(
				'label' => 'کادر و فیلد جست‌وجو',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'box_height',
			array(
				'label'      => 'ارتفاع کادر',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 30, 'max' => 90 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-se' => '--ard-se-h:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'box_radius',
			array(
				'label'      => 'گردی کادر',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-se' => '--ard-se-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'box_border_w',
			array(
				'label'      => 'ضخامت حاشیه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 6 ) ),
				'selectors'  => array( self::SEL_BOX => 'border-width:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'              => 'فاصله داخلی افقی',
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px' ),
				'allowed_dimensions' => 'horizontal',
				'selectors'          => array(
					self::SEL_BOX => 'padding-right:{{RIGHT}}{{UNIT}};padding-left:{{LEFT}}{{UNIT}};',
				),
				'description'        => 'در قالب مدرن، سمتی که دکمه است دست‌کم به اندازهٔ دکمه فاصله بگذارید.',
			)
		);

		$this->start_controls_tabs( 'box_tabs' );

		$this->start_controls_tab( 'box_tab_normal', array( 'label' => 'عادی' ) );

		$this->add_control(
			'box_bg',
			array(
				'label'     => 'رنگ زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se' => '--ard-se-box-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'box_line',
			array(
				'label'     => 'رنگ حاشیه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se' => '--ard-se-line:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => self::SEL_BOX,
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'box_tab_focus', array( 'label' => 'فوکوس / باز' ) );

		$focus = '{{WRAPPER}} .ard-se.is-open .ard-se__box, {{WRAPPER}} .ard-se .ard-se__box:focus-within';

		$this->add_control(
			'box_bg_focus',
			array(
				'label'     => 'رنگ زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $focus => 'background:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'box_line_focus',
			array(
				'label'     => 'رنگ حاشیه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $focus => 'border-color:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow_focus',
				'selector' => $focus,
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		/* ---------------------------------------- فیلد متن */

		$this->add_control(
			'input_heading',
			array(
				'label'     => 'متن داخل کادر',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'm_input_typo',
				'label'    => 'قلم',
				'selector' => self::SEL_INPUT,
			)
		);

		$this->add_control(
			'input_color',
			array(
				'label'     => 'رنگ متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( self::SEL_INPUT => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'm_placeholder',
			array(
				'label'     => 'رنگ متن راهنما',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( self::SEL_INPUT . '::placeholder' => 'color:{{VALUE}};opacity:1;' ),
			)
		);

		$this->add_control(
			'icon_heading',
			array(
				'label'     => 'ذره‌بین داخل کادر و دکمهٔ پاک کردن',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => 'رنگ ذره‌بین',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se .ard-se__icon' => 'color:{{VALUE}};' ),
				'condition' => array( 'show_icon' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => 'اندازه ذره‌بین',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 10, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-se .ard-se__icon svg' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'show_icon' => 'yes' ),
			)
		);

		$this->add_control(
			'clear_color',
			array(
				'label'     => 'رنگ دکمهٔ پاک کردن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se .ard-se__clear[class]' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'clear_bg',
			array(
				'label'     => 'زمینه دکمهٔ پاک کردن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se .ard-se__clear[class]' => 'background:{{VALUE}};' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * دکمهٔ جست‌وجو.
	 */
	protected function add_button_style_controls() {
		$this->start_controls_section(
			'section_style_btn',
			array(
				'label'     => 'دکمه جست‌وجو',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_button' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'm_btn_size',
			array(
				'label'      => 'اندازه دکمه گرد',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 24, 'max' => 90 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-se' => '--ard-se-btn-size:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'template' => 'modern' ),
			)
		);

		$this->add_responsive_control(
			'btn_offset',
			array(
				'label'       => 'فاصله دکمه از لبهٔ کادر',
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array( 'px' => array( 'min' => -20, 'max' => 40 ) ),
				'selectors'   => array( '{{WRAPPER}} .ard-se.ard-se--tpl-modern .ard-se__btn[class]' => 'inset-inline-end:{{SIZE}}{{UNIT}};' ),
				'description' => 'صفر = چسبیده به لبه؛ عدد مثبت دکمه را داخل کادر می‌برد.',
				'condition'   => array( 'template' => 'modern' ),
			)
		);

		$this->add_responsive_control(
			'btn_padding',
			array(
				'label'              => 'فاصله داخلی افقی',
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px' ),
				'allowed_dimensions' => 'horizontal',
				'selectors'          => array( self::SEL_BTN => 'padding-right:{{RIGHT}}{{UNIT}};padding-left:{{LEFT}}{{UNIT}};' ),
				'condition'          => array( 'template' => 'classic' ),
			)
		);

		$this->add_responsive_control(
			'btn_gap',
			array(
				'label'      => 'فاصله دکمه از کادر',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 30 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-se .ard-se__form' => 'gap:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'template' => 'classic' ),
			)
		);

		$this->add_responsive_control(
			'm_btn_icon',
			array(
				'label'      => 'اندازه آیکون',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 10, 'max' => 48 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-se' => '--ard-se-btn-icon:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'btn_radius',
			array(
				'label'      => 'گردی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array( 'min' => 0, 'max' => 60 ),
					'%'  => array( 'min' => 0, 'max' => 50 ),
				),
				'selectors'  => array( self::SEL_BTN => 'border-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'btn_typo',
				'label'    => 'قلم متن دکمه',
				'selector' => self::SEL_BTN,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'btn_border',
				'selector' => self::SEL_BTN,
			)
		);

		$this->start_controls_tabs( 'btn_tabs' );

		$this->start_controls_tab( 'btn_tab_normal', array( 'label' => 'عادی' ) );

		$this->add_control(
			'btn_bg',
			array(
				'label'     => 'رنگ زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se' => '--ard-se-btn-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'btn_ink',
			array(
				'label'     => 'رنگ متن و آیکون',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se' => '--ard-se-btn-ink:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'btn_shadow',
				'selector' => self::SEL_BTN,
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'btn_tab_hover', array( 'label' => 'هاور' ) );

		$hover = self::SEL_BTN . ':hover, ' . self::SEL_BTN . ':focus-visible';

		$this->add_control(
			'btn_bg_hover',
			array(
				'label'     => 'رنگ زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $hover => 'background:{{VALUE}};filter:none;' ),
			)
		);

		$this->add_control(
			'btn_ink_hover',
			array(
				'label'     => 'رنگ متن و آیکون',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $hover => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'btn_border_hover',
			array(
				'label'     => 'رنگ حاشیه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( $hover => 'border-color:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'btn_shadow_hover',
				'selector' => $hover,
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * پنل نتیجه‌ها (و پنل تاریخچه در قالب مدرن).
	 */
	protected function add_panel_style_controls() {
		$this->start_controls_section(
			'section_style_panel',
			array(
				'label' => 'پنل نتیجه‌ها',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'panel_width',
			array(
				'label'   => 'پهنای پنل',
				'type'    => Controls_Manager::SELECT,
				'default' => 'input',
				'options' => array(
					'input'  => 'به اندازهٔ کادر',
					'wide'   => 'عریض‌تر از کادر',
					'custom' => 'اندازهٔ دلخواه',
				),
			)
		);

		$this->add_responsive_control(
			'panel_width_custom',
			array(
				'label'      => 'اندازه دلخواه پنل',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vw' ),
				'range'      => array(
					'px' => array( 'min' => 240, 'max' => 900 ),
					'vw' => array( 'min' => 30, 'max' => 100 ),
				),
				'condition'  => array( 'panel_width' => 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-se' => '--ard-se-panel-w:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'panel_offset',
			array(
				'label'      => 'فاصله پنل از کادر',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( self::SEL_PANEL => 'inset-block-start:calc(100% + {{SIZE}}{{UNIT}});' ),
			)
		);

		$this->add_responsive_control(
			'rows',
			array(
				'label'       => 'ردیف‌های دیده‌شده پیش از اسکرول',
				'type'        => Controls_Manager::SLIDER,
				'range'       => array( 'px' => array( 'min' => 2, 'max' => 12, 'step' => 1 ) ),
				'default'     => array( 'size' => 5 ),
				'selectors'   => array(
					'{{WRAPPER}} .ard-se' => '--ard-se-rows:{{SIZE}};',
				),
				'description' => 'بیش از این تعداد، پنل اسکرول می‌شود.',
			)
		);

		$this->add_control(
			'panel_bg',
			array(
				'label'     => 'رنگ زمینه',
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array( '{{WRAPPER}} .ard-se' => '--ard-se-panel-bg:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'panel_border',
				'selector' => self::SEL_PANEL,
			)
		);

		$this->add_responsive_control(
			'm_panel_radius',
			array(
				'label'      => 'گردی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( self::SEL_PANEL => 'border-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'm_panel_padding',
			array(
				'label'      => 'فاصله داخلی',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					self::SEL_PANEL => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'panel_shadow',
				'selector' => self::SEL_PANEL,
			)
		);

		/* ---------------------------------------- متن راهنما */

		$this->add_control(
			'hint_heading',
			array(
				'label'     => 'متن راهنما و «پیدا نشد»',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'hint_color',
			array(
				'label'     => 'رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se .ard-se__hint, {{WRAPPER}} .ard-se .ard-se__empty' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'hint_typo',
				'selector' => '{{WRAPPER}} .ard-se .ard-se__hint, {{WRAPPER}} .ard-se .ard-se__empty',
			)
		);

		$this->add_responsive_control(
			'hint_padding',
			array(
				'label'      => 'فاصله داخلی',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-se .ard-se__hint, {{WRAPPER}} .ard-se .ard-se__empty' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		/* ---------------------------------------- نتیجه‌ها */

		$this->add_control(
			'result_heading',
			array(
				'label'     => 'ردیف نتیجه‌ها',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typo',
				'label'    => 'قلم عنوان',
				'selector' => '{{WRAPPER}} .ard-se .ard-se__title',
			)
		);

		$this->add_control(
			'result_title_color',
			array(
				'label'     => 'رنگ عنوان',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se .ard-se__card' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'result_price_color',
			array(
				'label'     => 'رنگ قیمت',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se .ard-se__now' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'result_hover_bg',
			array(
				'label'     => 'زمینه ردیف هاور/انتخاب',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se .ard-se__card:hover, {{WRAPPER}} .ard-se .ard-se__card.is-active' => 'background:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'more_color',
			array(
				'label'     => 'رنگ لینک «دیدن همه»',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se .ard-se__more' => 'color:{{VALUE}};' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * استایل قالب مدرن: دکمهٔ گرد، تیترها، تاریخچه و چیپ‌ها.
	 */
	protected function add_modern_style_controls() {
		$this->start_controls_section(
			'section_style_modern',
			array(
				'label'     => 'قالب مدرن',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'template' => 'modern' ),
			)
		);

		$this->add_control(
			'm_divider',
			array(
				'label'     => 'رنگ خط جداکننده',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se' => '--ard-se-divider:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'm_head_heading',
			array(
				'label'     => 'عنوان بخش‌ها',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'm_head_color',
			array(
				'label'     => 'رنگ عنوان',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se__sec-title' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'm_head_icon',
			array(
				'label'     => 'رنگ آیکون عنوان',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se__sec-icon' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'm_head_typo',
				'selector' => '{{WRAPPER}} .ard-se__sec-title',
			)
		);

		$this->add_control(
			'm_hist_heading',
			array(
				'label'     => 'تاریخچه',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'm_hist_color',
			array(
				'label'     => 'رنگ متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se__hist-link' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'm_hist_hover',
			array(
				'label'     => 'رنگ متن (هاور)',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se__hist-link:hover' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'm_hist_icon',
			array(
				'label'     => 'رنگ آیکون',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se__hist-icon' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'm_hist_del',
			array(
				'label'     => 'رنگ دکمه حذف',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se__hist-del' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'm_hist_typo',
				'selector' => '{{WRAPPER}} .ard-se__hist-text',
			)
		);

		$this->add_responsive_control(
			'm_hist_gap',
			array(
				'label'      => 'فاصله ردیف‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-se__hist-item' => 'padding-block:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'm_chip_heading',
			array(
				'label'     => 'چیپ‌های پرمخاطب',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'm_chip_typo',
				'selector' => '{{WRAPPER}} .ard-se__chip',
			)
		);

		$this->add_responsive_control(
			'm_chip_padding',
			array(
				'label'      => 'فاصله داخلی',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-se__chip' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'm_chip_radius',
			array(
				'label'      => 'گردی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-se__chip' => 'border-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'm_chip_gap',
			array(
				'label'      => 'فاصله چیپ‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 30 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-se__chips' => 'gap:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->start_controls_tabs( 'm_chip_tabs' );

		foreach ( array( 'normal' => 'عادی', 'hover' => 'هاور' ) as $state => $state_label ) {
			$sel = '{{WRAPPER}} .ard-se__chip' . ( 'hover' === $state ? ':hover' : '' );

			$this->start_controls_tab( 'm_chip_tab_' . $state, array( 'label' => $state_label ) );

			$this->add_control(
				'm_chip_color_' . $state,
				array(
					'label'     => 'رنگ متن',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( $sel => 'color:{{VALUE}};' ),
				)
			);

			$this->add_control(
				'm_chip_bg_' . $state,
				array(
					'label'     => 'رنگ زمینه',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( $sel => 'background:{{VALUE}};' ),
				)
			);

			$this->add_control(
				'm_chip_border_' . $state,
				array(
					'label'     => 'رنگ حاشیه',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( $sel => 'border-color:{{VALUE}};' ),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		if ( $this->require_woo() ) {
			return;
		}

		$s   = $this->get_settings_for_display();
		$uid = 'ard-se-' . $this->get_id();
		$min = max( 1, min( 10, (int) $s['min_chars'] ) );

		$hint = trim( (string) $s['hint_text'] );

		if ( '' === $hint ) {
			$hint = sprintf( 'برای جست‌وجو دست‌کم %s حرف بنویسید.', Ardal_Helpers::fa_digits( $min ) );
		}

		// همان چیزهایی که ایجکس برای ساختن کارت لازم دارد.
		$payload = array(
			'limit'      => max( 1, min( 30, (int) $s['limit'] ) ),
			'more'       => 'yes' === $s['show_more'] ? 'yes' : 'no',
			'more_text'  => $s['more_text'],
			'empty_text' => $s['empty_text'],
		);

		foreach ( array_keys( $this->parts() ) as $key ) {
			$payload[ $key ] = 'yes' === $s[ 'card_' . $key ] ? 'yes' : 'no';
		}

		$modern = 'modern' === ( $s['template'] ?? 'classic' );

		$classes = array_filter(
			array(
				'ard-se',
				'ard-se--tpl-' . ( $modern ? 'modern' : 'classic' ),
				'ard-se--w-' . $s['panel_width'],
				'yes' === $s['show_button'] ? 'ard-se--has-btn' : '',
			)
		);

		$home = '';

		if ( $modern ) {
			$home = sprintf(
				' data-home="1" data-history="%d" data-home-click="%s"',
				'yes' === $s['show_history'] ? max( 1, min( 20, (int) $s['history_limit'] ) ) : 0,
				'fill' === $s['home_click'] ? 'fill' : 'go'
			);
		}
		?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
			data-ard-search
			data-min="<?php echo esc_attr( $min ); ?>"
			data-delay="<?php echo esc_attr( max( 0, min( 2000, (int) $s['delay'] ) ) ); ?>"
			data-args="<?php echo esc_attr( wp_json_encode( $payload ) ); ?>"<?php echo $home; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- بالا با %d و مقدار ثابت ساخته شده. ?>>

			<form class="ard-se__form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="hidden" name="post_type" value="product" />

				<div class="ard-se__box">
					<?php if ( 'yes' === $s['show_icon'] ) : ?>
						<span class="ard-se__icon" aria-hidden="true"><?php echo Ardal_Helpers::icon( 'search', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php endif; ?>

					<input type="search" class="ard-se__input" name="s"
						id="<?php echo esc_attr( $uid ); ?>"
						placeholder="<?php echo esc_attr( $s['placeholder'] ); ?>"
						autocomplete="off" role="combobox" aria-expanded="false"
						aria-controls="<?php echo esc_attr( $uid ); ?>-panel"
						aria-label="جست‌وجوی محصول" />

					<button type="button" class="ard-se__clear" data-ard-se-clear hidden aria-label="پاک کردن">&times;</button>
				</div>

				<?php if ( 'yes' === $s['show_button'] ) : ?>
					<button type="submit" class="ard-se__btn">
						<?php if ( '' !== trim( (string) $s['button_text'] ) ) : ?>
							<span class="ard-se__btn-text"><?php echo esc_html( $s['button_text'] ); ?></span>
						<?php else : ?>
							<span class="ard-se__btn-icon" aria-hidden="true"><?php echo Ardal_Helpers::icon( 'search', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span class="ard-sr-only">جست‌وجو</span>
						<?php endif; ?>
					</button>
				<?php endif; ?>
			</form>

			<div class="ard-se__panel" id="<?php echo esc_attr( $uid ); ?>-panel" role="listbox" hidden aria-live="polite">
				<div class="ard-se__hint"><?php echo esc_html( $hint ); ?></div>
			</div>

			<?php
			if ( $modern ) {
				$this->render_home( $s );
			}
			?>
		</div>
		<?php
	}

	/**
	 * قالب پنلِ پیش از تایپ: تاریخچه و جست‌وجوی پرمخاطب.
	 *
	 * داخل <template> می‌نشیند و جاوااسکریپت هر بار از رویش یک نسخهٔ تازه
	 * می‌سازد؛ تاریخچه در مرورگر است، پس ردیف‌هایش را همان‌جا پر می‌کند.
	 *
	 * @param array $s
	 */
	protected function render_home( $s ) {
		$popular = 'yes' === $s['show_popular'] ? $this->popular_terms( $s ) : array();
		?>
		<template data-ard-se-home>
			<div class="ard-se__home">
				<?php if ( 'yes' === $s['show_history'] ) : ?>
					<section class="ard-se__sec ard-se__sec--history" data-ard-se-history hidden>
						<div class="ard-se__sec-head">
							<span class="ard-se__sec-icon" aria-hidden="true"><?php echo $this->svg( 'clock', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span class="ard-se__sec-title"><?php echo esc_html( $s['history_title'] ); ?></span>
							<?php if ( 'yes' === $s['history_clear'] ) : ?>
								<button type="button" class="ard-se__clear-all" data-ard-se-clear-all><?php echo esc_html( $s['history_clear_text'] ); ?></button>
							<?php endif; ?>
						</div>
						<ul class="ard-se__hist" data-ard-se-hist-list></ul>
					</section>
				<?php endif; ?>

				<?php if ( $popular ) : ?>
					<section class="ard-se__sec ard-se__sec--popular">
						<div class="ard-se__sec-head">
							<span class="ard-se__sec-icon" aria-hidden="true"><?php echo $this->svg( 'bolt', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span class="ard-se__sec-title"><?php echo esc_html( $s['popular_title'] ); ?></span>
						</div>
						<div class="ard-se__chips">
							<?php foreach ( $popular as $term ) : ?>
								<a class="ard-se__chip" href="<?php echo esc_url( Ardal_Search::results_url( $term ) ); ?>" data-ard-se-term="<?php echo esc_attr( $term ); ?>"><?php echo esc_html( $term ); ?></a>
							<?php endforeach; ?>
						</div>
					</section>
				<?php endif; ?>
			</div>
		</template>

		<template data-ard-se-hist-item>
			<li class="ard-se__hist-item">
				<a class="ard-se__hist-link" href="#" data-ard-se-term="">
					<span class="ard-se__hist-icon" aria-hidden="true"><?php echo $this->svg( 'history', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="ard-se__hist-text"></span>
				</a>
				<button type="button" class="ard-se__hist-del" data-ard-se-del aria-label="حذف از تاریخچه"><?php echo $this->svg( 'close-circle', 26 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
			</li>
		</template>
		<?php
	}

	/**
	 * عبارت‌های پرمخاطب: دستی، خودکار یا هر دو.
	 *
	 * @param array $s
	 * @return string[]
	 */
	protected function popular_terms( $s ) {
		$limit  = max( 1, min( 30, (int) $s['popular_limit'] ) );
		$source = $s['popular_source'];
		$terms  = array();

		if ( 'auto' !== $source ) {
			foreach ( preg_split( '/\r\n|\r|\n/', (string) $s['popular_manual'] ) as $line ) {
				$line = trim( $line );

				if ( '' !== $line ) {
					$terms[] = $line;
				}
			}
		}

		if ( 'manual' !== $source && count( $terms ) < $limit ) {
			$terms = array_merge( $terms, Ardal_Search::popular( $limit * 2, max( 1, (int) $s['popular_min'] ) ) );
		}

		// تکراری‌ها با شکل یکدست‌شده شناخته می‌شوند؛ «كرم» و «کرم» یکی‌اند.
		$out  = array();
		$seen = array();

		foreach ( $terms as $term ) {
			$key = Ardal_Search::normalize( $term );

			if ( isset( $seen[ $key ] ) ) {
				continue;
			}

			$seen[ $key ] = true;
			$out[]        = $term;

			if ( count( $out ) >= $limit ) {
				break;
			}
		}

		return $out;
	}

	/**
	 * آیکون‌های قالب مدرن.
	 *
	 * @param string $name
	 * @param int    $size
	 * @return string
	 */
	private function svg( $name, $size ) {
		$paths = array(
			'clock'        => '<circle cx="12" cy="12" r="9"/><path d="M12 7.5V12l3 2"/>',
			'bolt'         => '<path d="M13 3L5.5 13.5H12L11 21l7.5-10.5H12z"/>',
			'history'      => '<path d="M19 12a7 7 0 1 1-4-6.3"/><path d="M14.5 8.5h4"/><path d="M15.5 11h3"/><path d="M17 17l3 3"/>',
			'close-circle' => '<circle cx="12" cy="12" r="9"/><path d="M9.3 9.3l5.4 5.4"/><path d="M14.7 9.3l-5.4 5.4"/>',
		);

		return sprintf(
			'<svg class="ard-i ard-i--%1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
			esc_attr( $name ),
			(int) $size,
			$paths[ $name ]
		);
	}

	/**
	 * بخش‌های اختیاری هر نتیجه.
	 *
	 * @return array
	 */
	private function parts() {
		return array(
			'image'    => array( 'label' => 'تصویر', 'on' => true ),
			'price'    => array( 'label' => 'قیمت', 'on' => true ),
			'discount' => array( 'label' => 'درصد تخفیف', 'on' => true ),
			'subtitle' => array( 'label' => 'زیرعنوان', 'on' => false ),
			'rating'   => array( 'label' => 'امتیاز', 'on' => false ),
			'stock'    => array( 'label' => 'وضعیت موجودی', 'on' => false ),
		);
	}
}
