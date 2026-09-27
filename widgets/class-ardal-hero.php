<?php
/**
 * ویجت اسلایدر هیرو.
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

class Ardal_Widget_Hero extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-hero';
	}

	public function get_title() {
		return 'اردال — اسلایدر هیرو';
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_keywords() {
		return array( 'اردال', 'اسلایدر', 'هیرو', 'بنر', 'hero', 'slider' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_slides',
			array( 'label' => 'اسلایدها' )
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'tag',
			array(
				'label'       => 'برچسب بالای عنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'کالکشن پاییز ۱۴۰۵',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'       => 'عنوان',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'default'     => 'کفش دویدن حرفه‌ای، با ضمانت اصالت',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'subtitle',
			array(
				'label'       => 'توضیح',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => 'بیش از ۴۰۰ مدل کفش ریسینگ و ترینینگ از برندهای معتبر، با امکان تست ۷ روزه و بازگشت رایگان.',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'image',
			array(
				'label' => 'تصویر اسلاید',
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$repeater->add_control(
			'btn_text',
			array(
				'label'   => 'متن دکمه اصلی',
				'type'    => Controls_Manager::TEXT,
				'default' => 'مشاهده محصولات',
			)
		);

		$repeater->add_control(
			'btn_link',
			array(
				'label'   => 'لینک دکمه اصلی',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '' ),
			)
		);

		$repeater->add_control(
			'btn2_text',
			array(
				'label'   => 'متن دکمه دوم',
				'type'    => Controls_Manager::TEXT,
				'default' => 'راهنمای خرید',
			)
		);

		$repeater->add_control(
			'btn2_link',
			array(
				'label'   => 'لینک دکمه دوم',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '' ),
			)
		);

		$this->add_control(
			'slides',
			array(
				'label'       => 'اسلایدها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'tag'      => 'کالکشن پاییز ۱۴۰۵',
						'title'    => 'کفش دویدن حرفه‌ای، با ضمانت اصالت',
						'subtitle' => 'بیش از ۴۰۰ مدل کفش ریسینگ و ترینینگ از برندهای معتبر، با امکان تست ۷ روزه و بازگشت رایگان.',
					),
					array(
						'tag'      => 'تجهیزات باشگاه خانگی',
						'title'    => 'باشگاه‌تان را در خانه بسازید',
						'subtitle' => 'تردمیل، دوچرخه ثابت، دمبل و بنچ — با نصب رایگان در تهران و پرداخت اقساطی.',
					),
					array(
						'tag'      => 'فصل شنا',
						'title'    => 'لوازم شنا و تفریحات آبی',
						'subtitle' => 'عینک، کلاه، مایو و تجهیزات نجات غریق با تخفیف ویژه شروع فصل.',
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_behaviour',
			array( 'label' => 'رفتار' )
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => 'چرخش خودکار',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'interval',
			array(
				'label'       => 'فاصله چرخش (ثانیه)',
				'type'        => Controls_Manager::NUMBER,
				'min'         => 2,
				'max'         => 30,
				'default'     => 5,
				'condition'   => array( 'autoplay' => 'yes' ),
			)
		);

		$this->add_tag_control( 'title_tag', 'تگ عنوان اسلایدها', 'h2' );

		$this->add_control(
			'show_arrows',
			array(
				'label'        => 'نمایش فلش‌ها',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		$this->add_box_style_controls();
		$this->add_image_style_controls();
		$this->add_text_style_controls();
		$this->add_button_style_controls();
		$this->add_nav_style_controls();
	}

	/* --------------------------------------------------------------------- */
	/* ظاهر — جعبهٔ هیرو                                                       */
	/* --------------------------------------------------------------------- */

	private function add_box_style_controls() {
		$this->start_controls_section(
			'section_style',
			array(
				'label' => 'جعبه هیرو',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'variant',
			array(
				'label'   => 'سبک',
				'type'    => Controls_Manager::SELECT,
				'default' => 'gradient',
				'options' => array(
					'gradient' => 'گرادیان سرمه‌ای با متن سفید (دیزاین ۱a)',
					'soft'     => 'زمینه روشن با عنوان سرمه‌ای (دیزاین ۱b)',
				),
			)
		);

		$this->add_control(
			'hero_bg',
			array(
				'label'       => 'زمینه دلخواه',
				'type'        => Controls_Manager::COLOR,
				'description' => 'جای گرادیان سبک انتخاب‌شده می‌نشیند. خالی = همان سبک.',
				'selectors'   => array(
					'{{WRAPPER}} .ard-hero' => 'background:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'pattern',
			array(
				'label'     => 'نقش‌ونگار روی زمینه',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'block',
				'options'   => array(
					'block' => 'نمایش بده',
					'none'  => 'بدون نقش',
				),
				'selectors' => array(
					'{{WRAPPER}} .ard-hero::before' => 'display:{{VALUE}};',
				),
				'condition' => array( 'variant' => 'gradient' ),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'          => 'ارتفاع',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 240, 'max' => 720 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 392 ),
				'tablet_default' => array( 'unit' => 'px', 'size' => 340 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 0 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-hero__stage' => 'min-height:{{SIZE}}{{UNIT}};',
				),
				'separator'      => 'before',
			)
		);

		$this->add_responsive_control(
			'hero_radius',
			array(
				'label'      => 'گردی گوشه‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 48 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-hero' => 'border-radius:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'slide_padding',
			array(
				'label'      => 'فاصله داخلی اسلاید',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-hero__slide' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'slide_gap',
			array(
				'label'      => 'فاصله متن تا تصویر',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 80 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-hero__slide' => 'gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'hero_shadow',
				'label'    => 'سایه',
				'selector' => '{{WRAPPER}} .ard-hero',
			)
		);

		$this->end_controls_section();
	}

	/* --------------------------------------------------------------------- */
	/* ظاهر — تصویر                                                            */
	/* --------------------------------------------------------------------- */

	private function add_image_style_controls() {
		$this->start_controls_section(
			'section_style_image',
			array(
				'label' => 'تصویر اسلاید',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'image_size',
			array(
				'label'       => 'اندازه فایل تصویر',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'large',
				'options'     => $this->image_size_options(),
				'description' => 'کدام نسخهٔ ساخته‌شدهٔ وردپرس بارگذاری شود. برای هیروی تمام‌عرض «کامل» بهتر است.',
				'label_block' => true,
			)
		);

		$this->add_control(
			'img_fit',
			array(
				'label'       => 'نحوهٔ جا شدن تصویر',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'cover',
				'options'     => array(
					'cover'      => 'پوشاندن کادر (cover) — برش می‌خورد',
					'contain'    => 'کامل دیده شود (contain) — کنارش جا می‌ماند',
					'fill'       => 'کشیده شود (fill) — نسبت به‌هم می‌ریزد',
					'scale-down' => 'کوچک‌تر از اندازهٔ اصلی نشود (scale-down)',
					'none'       => 'اندازهٔ اصلی (none)',
				),
				'label_block' => true,
				'selectors'   => array(
					'{{WRAPPER}} .ard-hero__media img' => 'object-fit:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'img_position',
			array(
				'label'       => 'کدام قسمت تصویر دیده شود',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'center center',
				'options'     => array(
					'center center' => 'وسط',
					'center top'    => 'بالا',
					'center bottom' => 'پایین',
					'right center'  => 'راست',
					'left center'   => 'چپ',
					'right top'     => 'بالا راست',
					'left top'      => 'بالا چپ',
					'right bottom'  => 'پایین راست',
					'left bottom'   => 'پایین چپ',
				),
				'label_block' => true,
				'description'  => 'وقتی تصویر برش می‌خورد (cover) تعیین می‌کند کدام لبه حفظ شود.',
				'selectors'   => array(
					'{{WRAPPER}} .ard-hero__media img' => 'object-position:{{VALUE}};',
				),
				'condition'   => array( 'img_fit' => array( 'cover', 'none', 'scale-down', 'contain' ) ),
			)
		);

		$this->add_control(
			'media_side',
			array(
				'label'       => 'تصویر در کدام سمت',
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array(
					''      => 'چپ (پیش‌فرض)',
					'start' => 'راست',
				),
				'description' => 'در موبایل در هر حالت بالای متن می‌نشیند.',
				'separator'   => 'before',
			)
		);

		$this->add_responsive_control(
			'media_w',
			array(
				'label'      => 'عرض کادر تصویر',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array( 'min' => 120, 'max' => 900 ),
					'%'  => array( 'min' => 10, 'max' => 100 ),
				),
				/*
				 * پیش‌فرض عمداً خالی است. با پیش‌فرض، المنتور یک قانونِ بدون
				 * مدیا کوئری چاپ می‌کرد که وزنش از قانون موبایلِ ardal.css
				 * بیشتر بود و کادر تصویر در موبایل باریک می‌ماند.
				 */
				'description' => 'خالی = ۴۳۰ پیکسل (و در موبایل تمام عرض).',
				'selectors'  => array(
					'{{WRAPPER}} .ard-hero__media' => 'inline-size:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'media_max_w',
			array(
				'label'       => 'حداکثر سهم از عرض اسلاید',
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '%' ),
				'range'       => array( '%' => array( 'min' => 20, 'max' => 100 ) ),
				'description' => 'در عرض‌های کم، کادر تصویر از این بیشتر نمی‌شود. خالی = ۴۵٪.',
				'selectors'   => array(
					'{{WRAPPER}} .ard-hero__media' => 'max-inline-size:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'media_h',
			array(
				'label'      => 'ارتفاع کادر تصویر',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 120, 'max' => 640 ) ),
				'description' => 'خالی = ۳۳۰ پیکسل (و در موبایل ۱۸۰).',
				'selectors'  => array(
					'{{WRAPPER}} .ard-hero__media' => 'block-size:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'media_bg',
			array(
				'label'       => 'زمینه کادر تصویر',
				'type'        => Controls_Manager::COLOR,
				'description' => 'وقتی حالت «کامل دیده شود» را انتخاب می‌کنید، همین رنگ در دو طرف تصویر دیده می‌شود.',
				'selectors'   => array(
					'{{WRAPPER}} .ard-hero__media' => 'background:{{VALUE}};',
				),
				'separator'   => 'before',
			)
		);

		$this->add_responsive_control(
			'media_pad',
			array(
				'label'      => 'فاصله داخلی کادر تصویر',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-hero__media' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'media_radius',
			array(
				'label'      => 'گردی گوشه‌های تصویر',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 48 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-hero__media'     => 'border-radius:{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ard-hero__media img' => 'border-radius:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'media_border',
				'label'    => 'حاشیه کادر تصویر',
				'selector' => '{{WRAPPER}} .ard-hero__media',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'media_shadow',
				'label'    => 'سایه کادر تصویر',
				'selector' => '{{WRAPPER}} .ard-hero__media',
			)
		);

		$this->end_controls_section();
	}

	/* --------------------------------------------------------------------- */
	/* ظاهر — متن‌ها                                                           */
	/* --------------------------------------------------------------------- */

	private function add_text_style_controls() {
		$this->start_controls_section(
			'section_style_text',
			array(
				'label' => 'متن‌ها',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'tag_heading',
			array(
				'label' => 'برچسب بالای عنوان',
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'tag_color',
			array(
				'label'     => 'رنگ متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-hero__tag' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tag_bg',
			array(
				'label'     => 'رنگ زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-hero__tag' => 'background:{{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tag_typo',
				'selector' => '{{WRAPPER}} .ard-hero__tag',
			)
		);

		$this->add_control(
			'title_heading',
			array(
				'label'     => 'عنوان',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => 'رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-hero__title' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typo',
				'selector' => '{{WRAPPER}} .ard-hero__title',
			)
		);

		$this->add_control(
			'sub_heading',
			array(
				'label'     => 'توضیح',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sub_color',
			array(
				'label'     => 'رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-hero__sub' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sub_typo',
				'selector' => '{{WRAPPER}} .ard-hero__sub',
			)
		);

		$this->add_responsive_control(
			'sub_max_w',
			array(
				'label'      => 'حداکثر عرض توضیح',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array( 'min' => 200, 'max' => 900 ),
					'%'  => array( 'min' => 20, 'max' => 100 ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .ard-hero__sub' => 'max-inline-size:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/* --------------------------------------------------------------------- */
	/* ظاهر — دکمه‌ها                                                          */
	/* --------------------------------------------------------------------- */

	private function add_button_style_controls() {
		$this->start_controls_section(
			'section_style_buttons',
			array(
				'label' => 'دکمه‌ها',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'btn_typo',
				'label'    => 'قلم هر دو دکمه',
				'selector' => '{{WRAPPER}} .ard-hero__btns .ard-btn',
			)
		);

		$this->add_responsive_control(
			'btn_padding',
			array(
				'label'      => 'فاصله داخلی',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-hero__btns .ard-btn' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'btn_radius',
			array(
				'label'      => 'گردی گوشه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-hero__btns .ard-btn' => 'border-radius:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'btn1_heading',
			array(
				'label'     => 'دکمه اصلی',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'btn1_tabs' );

		$this->start_controls_tab( 'btn1_normal', array( 'label' => 'عادی' ) );

		$this->add_control(
			'btn1_color',
			array(
				'label'     => 'رنگ متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-hero__btns .ard-btn--accent' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'btn1_bg',
			array(
				'label'     => 'رنگ زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-hero__btns .ard-btn--accent' => 'background:{{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'btn1_hover', array( 'label' => 'هاور' ) );

		$this->add_control(
			'btn1_color_h',
			array(
				'label'     => 'رنگ متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-hero__btns .ard-btn--accent:hover' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'btn1_bg_h',
			array(
				'label'     => 'رنگ زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-hero__btns .ard-btn--accent:hover' => 'background:{{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'btn2_heading',
			array(
				'label'     => 'دکمه دوم',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'btn2_tabs' );

		$this->start_controls_tab( 'btn2_normal', array( 'label' => 'عادی' ) );

		$this->add_control(
			'btn2_color',
			array(
				'label'     => 'رنگ متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-hero__btns .ard-btn--ghost' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'btn2_bg',
			array(
				'label'     => 'رنگ زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-hero__btns .ard-btn--ghost' => 'background:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'btn2_border',
			array(
				'label'     => 'رنگ حاشیه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-hero__btns .ard-btn--ghost' => 'border-color:{{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'btn2_hover', array( 'label' => 'هاور' ) );

		$this->add_control(
			'btn2_color_h',
			array(
				'label'     => 'رنگ متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-hero__btns .ard-btn--ghost:hover' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'btn2_bg_h',
			array(
				'label'     => 'رنگ زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-hero__btns .ard-btn--ghost:hover' => 'background:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'btn2_border_h',
			array(
				'label'     => 'رنگ حاشیه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-hero__btns .ard-btn--ghost:hover' => 'border-color:{{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/* --------------------------------------------------------------------- */
	/* ظاهر — فلش‌ها و نقطه‌ها                                                  */
	/* --------------------------------------------------------------------- */

	private function add_nav_style_controls() {
		$this->start_controls_section(
			'section_style_nav',
			array(
				'label' => 'فلش‌ها و نقطه‌ها',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'arrow_heading',
			array(
				'label'     => 'فلش‌ها',
				'type'      => Controls_Manager::HEADING,
				'condition' => array( 'show_arrows' => 'yes' ),
			)
		);

		$this->add_control(
			'arrow_color',
			array(
				'label'     => 'رنگ آیکون',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-hero__arrow' => 'color:{{VALUE}};',
				),
				'condition' => array( 'show_arrows' => 'yes' ),
			)
		);

		$this->add_control(
			'arrow_bg',
			array(
				'label'     => 'رنگ زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-hero__arrow' => 'background:{{VALUE}};',
				),
				'condition' => array( 'show_arrows' => 'yes' ),
			)
		);

		$this->add_control(
			'arrow_bg_h',
			array(
				'label'     => 'رنگ زمینه در هاور',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-hero__arrow:hover' => 'background:{{VALUE}};',
				),
				'condition' => array( 'show_arrows' => 'yes' ),
			)
		);

		$this->add_control(
			'arrow_size',
			array(
				'label'      => 'اندازه دایره',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 26, 'max' => 64 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-hero__arrow' => 'inline-size:{{SIZE}}{{UNIT}};block-size:{{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'show_arrows' => 'yes' ),
			)
		);

		$this->add_control(
			'dot_heading',
			array(
				'label'     => 'نقطه‌ها',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'dot_color',
			array(
				'label'     => 'رنگ نقطه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-hero__dot' => 'background:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dot_active',
			array(
				'label'     => 'رنگ نقطه فعال',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-hero__dot.is-active' => 'background:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dot_size',
			array(
				'label'      => 'قطر نقطه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 5, 'max' => 16 ) ),
				/*
				 * عرض فقط روی نقطه‌های غیرفعال: نقطهٔ فعال کشیده است و اگر
				 * همین‌جا هم عرض بگیرد، آن کشیدگی از بین می‌رود (وزن دو
				 * سلکتور برابر است و آخری برنده می‌شد).
				 */
				'selectors'  => array(
					'{{WRAPPER}} .ard-hero__dot'                => 'block-size:{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ard-hero__dot:not(.is-active)' => 'inline-size:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'dot_active_w',
			array(
				'label'      => 'طول نقطه فعال',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 8, 'max' => 60 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-hero__dot.is-active' => 'inline-size:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * اندازه‌های تصویر ثبت‌شدهٔ وردپرس، با نام خوانا.
	 *
	 * @return array
	 */
	private function image_size_options() {
		$names = array(
			'thumbnail'    => 'بندانگشتی',
			'medium'       => 'متوسط',
			'medium_large' => 'متوسط بزرگ',
			'large'        => 'بزرگ',
			'full'         => 'کامل (فایل اصلی)',
		);

		$out = array();

		foreach ( get_intermediate_image_sizes() as $size ) {
			$out[ $size ] = isset( $names[ $size ] ) ? $names[ $size ] : $size;
		}

		$out['full'] = $names['full'];

		return $out;
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$slides = is_array( $s['slides'] ) ? $s['slides'] : array();

		if ( empty( $slides ) ) {
			$this->render_empty( 'حداقل یک اسلاید اضافه کنید.' );

			return;
		}

		$interval = 'yes' === $s['autoplay'] ? max( 2, (int) $s['interval'] ) * 1000 : 0;
		$size     = '' !== $s['image_size'] ? $s['image_size'] : 'large';
		$ard_tag  = self::tag( $s['title_tag'], 'h2' );

		$classes = array( 'ard-hero', 'ard-hero--' . $s['variant'] );

		/*
		 * جای تصویر با کلاس عوض می‌شود نه با flex-direction روی خود ویجت:
		 * قانون تک‌ستونی موبایل در ardal.css داخل مدیا کوئری است و استایل
		 * المنتور — که وزن بیشتری دارد — آن را می‌شکست.
		 */
		if ( 'start' === $s['media_side'] ) {
			$classes[] = 'ard-hero--media-start';
		}
		?>
		<section
			class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
			data-ard-slider
			data-interval="<?php echo esc_attr( $interval ); ?>"
			aria-roledescription="carousel"
		>
			<div class="ard-hero__stage">
				<?php foreach ( $slides as $i => $slide ) : ?>
					<div
						class="ard-hero__slide<?php echo 0 === $i ? ' is-active' : ''; ?>"
						role="group"
						aria-roledescription="slide"
						aria-label="<?php echo esc_attr( sprintf( 'اسلاید %s از %s', Ardal_Helpers::fa_digits( $i + 1 ), Ardal_Helpers::fa_digits( count( $slides ) ) ) ); ?>"
						<?php echo 0 === $i ? '' : ' aria-hidden="true"'; ?>
					>
						<div class="ard-hero__copy">
							<?php if ( '' !== trim( (string) $slide['tag'] ) ) : ?>
								<span class="ard-hero__tag"><?php echo esc_html( $slide['tag'] ); ?></span>
							<?php endif; ?>

							<<?php echo esc_html( $ard_tag ); ?> class="ard-hero__title"><?php echo esc_html( $slide['title'] ); ?></<?php echo esc_html( $ard_tag ); ?>>

							<?php if ( '' !== trim( (string) $slide['subtitle'] ) ) : ?>
								<p class="ard-hero__sub"><?php echo esc_html( $slide['subtitle'] ); ?></p>
							<?php endif; ?>

							<div class="ard-hero__btns">
								<?php
								if ( '' !== trim( (string) $slide['btn_text'] ) ) :
									$tag = Ardal_Helpers::link_tag( $slide['btn_link'] );
									?>
									<<?php echo esc_html( $tag ); ?> class="ard-btn ard-btn--accent"<?php echo Ardal_Helpers::link_attrs( $slide['btn_link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $slide['btn_text'] ); ?></<?php echo esc_html( $tag ); ?>>
								<?php endif; ?>

								<?php
								if ( '' !== trim( (string) $slide['btn2_text'] ) ) :
									$tag2 = Ardal_Helpers::link_tag( $slide['btn2_link'] );
									?>
									<<?php echo esc_html( $tag2 ); ?> class="ard-btn ard-btn--ghost"<?php echo Ardal_Helpers::link_attrs( $slide['btn2_link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $slide['btn2_text'] ); ?></<?php echo esc_html( $tag2 ); ?>>
								<?php endif; ?>
							</div>
						</div>

						<div class="ard-hero__media">
							<?php echo Ardal_Helpers::image( $slide['image'], '[ تصویر شاخص اسلاید ۱۲۸۰×۹۶۰ ]', $slide['title'], $size ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( count( $slides ) > 1 ) : ?>
				<div class="ard-hero__dots" role="tablist" aria-label="انتخاب اسلاید">
					<?php foreach ( $slides as $i => $slide ) : ?>
						<button
							type="button"
							class="ard-hero__dot<?php echo 0 === $i ? ' is-active' : ''; ?>"
							data-ard-goto="<?php echo esc_attr( $i ); ?>"
							role="tab"
							aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
							aria-label="<?php echo esc_attr( 'اسلاید ' . Ardal_Helpers::fa_digits( $i + 1 ) ); ?>"
						></button>
					<?php endforeach; ?>
				</div>

				<?php if ( 'yes' === $s['show_arrows'] ) : ?>
					<button type="button" class="ard-hero__arrow ard-hero__arrow--prev" data-ard-prev aria-label="اسلاید قبلی">
						<?php echo Ardal_Helpers::icon( 'chevron-right', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
					<button type="button" class="ard-hero__arrow ard-hero__arrow--next" data-ard-next aria-label="اسلاید بعدی">
						<?php echo Ardal_Helpers::icon( 'chevron-left', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
				<?php endif; ?>
			<?php endif; ?>
		</section>
		<?php
	}
}
