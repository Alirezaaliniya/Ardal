<?php
/**
 * ویجت شبکه محصولات با فیلتر تب‌دار.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

class Ardal_Widget_Products extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-products';
	}

	public function get_title() {
		return 'اردال — شبکه محصولات';
	}

	public function get_icon() {
		return 'eicon-products';
	}

	public function get_keywords() {
		return array( 'اردال', 'محصول', 'محصولات', 'شبکه', 'products', 'grid' );
	}

	protected function register_controls() {
		$this->add_heading_controls( 'جدیدترین محصولات', 'accent' );

		$this->start_controls_section(
			'section_content',
			array( 'label' => 'محصولات' )
		);

		$this->add_source_controls( 10, true );

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			array(
				'label'       => 'نام محصول',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'default'     => 'کفش دویدن ران‌فلکس مدل ایر پرو ۲۰۲۵',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'image',
			array(
				'label' => 'تصویر',
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$repeater->add_control(
			'price',
			array(
				'label'   => 'قیمت',
				'type'    => Controls_Manager::TEXT,
				'default' => '۴٫۸۵۰٫۰۰۰',
			)
		);

		$repeater->add_control(
			'old_price',
			array(
				'label'   => 'قیمت قبل',
				'type'    => Controls_Manager::TEXT,
				'default' => '۵٫۹۰۰٫۰۰۰',
			)
		);

		$repeater->add_control(
			'off',
			array(
				'label'       => 'درصد تخفیف',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'خالی = محاسبه خودکار',
			)
		);

		$repeater->add_control(
			'rating',
			array(
				'label'   => 'امتیاز',
				'type'    => Controls_Manager::SLIDER,
				'range'   => array( 'px' => array( 'min' => 0, 'max' => 5, 'step' => 0.5 ) ),
				'default' => array( 'size' => 4 ),
			)
		);

		$repeater->add_control(
			'count',
			array(
				'label'   => 'تعداد نظر',
				'type'    => Controls_Manager::NUMBER,
				'default' => 241,
				'min'     => 0,
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'   => 'لینک محصول',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '' ),
			)
		);

		$repeater->add_control(
			'add_url',
			array(
				'label'   => 'لینک افزودن به سبد',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '' ),
			)
		);

		$repeater->add_control(
			'tab',
			array(
				'label'       => 'برچسب تب',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => 'اگر تب‌ها را فعال کرده‌اید، همان متن تب را اینجا بنویسید.',
				'label_block' => true,
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => 'محصولات',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'condition'   => array( 'source' => 'manual' ),
				'default'     => array(
					array(
						'title'     => 'کفش دویدن ران‌فلکس مدل ایر پرو ۲۰۲۵',
						'price'     => '۴٫۸۵۰٫۰۰۰',
						'old_price' => '۵٫۹۰۰٫۰۰۰',
						'count'     => 241,
						'tab'       => 'کفش',
					),
					array(
						'title'     => 'دمبل هالتر ست ۲۰ کیلوگرمی روکش‌دار',
						'price'     => '۲٫۳۹۰٫۰۰۰',
						'old_price' => '۲٫۹۵۰٫۰۰۰',
						'count'     => 138,
						'tab'       => 'بدنسازی',
					),
					array(
						'title'     => 'عینک شنا ضدبخار حرفه‌ای آکوا-ویژن',
						'price'     => '۶۹۰٫۰۰۰',
						'old_price' => '۸۹۰٫۰۰۰',
						'count'     => 503,
						'tab'       => 'شنا',
					),
					array(
						'title'     => 'ساعت ورزشی هوشمند با GPS و پالس‌متر',
						'price'     => '۷٫۲۰۰٫۰۰۰',
						'old_price' => '۸٫۴۰۰٫۰۰۰',
						'count'     => 97,
						'tab'       => 'لوازم جانبی',
					),
					array(
						'title'     => 'توپ فوتبال سایز ۵ دوخت ماشینی',
						'price'     => '۱٫۱۵۰٫۰۰۰',
						'old_price' => '۱٫۴۵۰٫۰۰۰',
						'count'     => 312,
						'tab'       => 'ورزش تیمی',
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tabs',
			array( 'label' => 'تب‌ها' )
		);

		$this->add_control(
			'show_tabs',
			array(
				'label'        => 'نمایش تب‌های فیلتر',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'description'  => 'در حالت دستی، بر اساس «برچسب تب» هر محصول فیلتر می‌کند.',
			)
		);

		$this->add_control(
			'all_label',
			array(
				'label'     => 'متن تب «همه»',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'همه',
				'condition' => array( 'show_tabs' => 'yes' ),
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

		$this->add_control(
			'variant',
			array(
				'label'   => 'سبک کارت',
				'type'    => Controls_Manager::SELECT,
				'default' => 'framed',
				'options' => array(
					'framed' => 'کارت حاشیه‌دار با دکمه گرد سبز (دیزاین ۱a)',
					'plain'  => 'بدون حاشیه با دکمه «افزودن به سبد» (دیزاین ۱b)',
				),
			)
		);

		$this->add_control(
			'link_mode',
			array(
				'label'       => 'ناحیه قابل کلیک',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'button',
				'options'     => array(
					'button' => 'فقط عنوان و دکمه',
					'box'    => 'کل کارت به محصول لینک شود',
				),
				'description' => 'در حالت «کل کارت»، دکمه همچنان کار خودش را انجام می‌دهد و کلیک روی آن به محصول نمی‌رود.',
			)
		);

		$this->add_control(
			'button_action',
			array(
				'label'   => 'کلیک روی دکمه',
				'type'    => Controls_Manager::SELECT,
				'default' => 'add',
				'options' => array(
					'add'  => 'افزودن به سبد خرید',
					'link' => 'رفتن به صفحه محصول',
				),
			)
		);

		$this->add_control(
			'show_rating',
			array(
				'label'        => 'نمایش امتیاز',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'     => 'متن دکمه',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'افزودن به سبد',
				'condition' => array( 'variant' => 'plain' ),
			)
		);

		$this->add_control(
			'notch',
			array(
				'label'        => 'بریدگی گوشه پایین-چپ',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => 'بله',
				'label_off'    => 'خیر',
				'return_value' => 'yes',
				'description'  => 'گوشه کارت را دور دکمهٔ گرد می‌بُرد. با خاموش‌کردنش، کارت گوشهٔ معمولی می‌گیرد.',
				'condition'    => array( 'variant' => 'framed' ),
			)
		);

		$this->add_responsive_control(
			'notch_size',
			array(
				'label'      => 'اندازه بریدگی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 80, 'max' => 320 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 190 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pcard--notch' => '--ard-notch:{{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'variant' => 'framed',
					'notch'   => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => 'تعداد ستون',
				'type'           => Controls_Manager::NUMBER,
				'min'            => 1,
				'max'            => 6,
				'default'        => 5,
				'tablet_default' => 3,
				'mobile_default' => 2,
				'selectors'      => array(
					'{{WRAPPER}} .ard-pgrid' => 'grid-template-columns:repeat({{VALUE}},minmax(0,1fr));',
				),
			)
		);

		$this->add_responsive_control(
			'rows',
			array(
				'label'          => 'تعداد ردیف',
				'type'           => Controls_Manager::NUMBER,
				'min'            => 0,
				'max'            => 12,
				'default'        => 0,
				'tablet_default' => 0,
				'mobile_default' => 0,
				'description'    => 'صفر یعنی همهٔ محصولات نمایش داده شوند. با عددی بزرگ‌تر از صفر، در همان اندازهٔ صفحه فقط «ردیف × ستون» کارتِ اول دیده می‌شود.',
			)
		);

		$this->add_responsive_control(
			'grid_gap',
			array(
				'label'      => 'فاصله بین کارت‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 48 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 16 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pgrid' => 'gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_height',
			array(
				'label'          => 'ارتفاع تصویر',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 80, 'max' => 320 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 168 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 120 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-pcard__media' => 'height:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->add_card_style_controls();
	}

	/**
	 * استایل کارت — همان چیزهایی که در «شبکه محصولات آرشیو» تنظیم‌شدنی است،
	 * اینجا هم در دسترس باشد.
	 */
	private function add_card_style_controls() {

		/* ------------------------------------------------ قاب کارت */

		$this->start_controls_section(
			'section_style_card',
			array(
				'label' => 'کارت محصول',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'card_bg',
			array(
				'label'     => 'رنگ زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__box' => 'background:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_border',
			array(
				'label'     => 'رنگ حاشیه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__box' => 'border-color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_border_hover',
			array(
				'label'     => 'رنگ حاشیه در هاور',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard:hover .ard-pcard__box' => 'border-color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_radius',
			array(
				'label'      => 'گردی گوشه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pcard, {{WRAPPER}} .ard-pcard__box' => 'border-radius:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => 'فاصله داخلی',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pcard__box' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'card_content_gap',
			array(
				'label'      => 'فاصله بین بخش‌های داخل کارت',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 30 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pcard__box' => 'gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		/*
		 * با بریدگی گوشه، کارت ماسک می‌شود و ترتیب رندر (filter → clip → mask)
		 * یعنی box-shadow خودِ عنصرِ ماسک‌شده بریده می‌شود. پس این کنترل فقط جایی
		 * نشان داده می‌شود که واقعاً کار می‌کند؛ سایهٔ حالت بریده را خود CSS با
		 * drop-shadow روی والد می‌سازد.
		 */
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'       => 'card_shadow',
				'selector'   => '{{WRAPPER}} .ard-pcard__box',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'variant',
							'operator' => '!==',
							'value'    => 'framed',
						),
						array(
							'name'     => 'notch',
							'operator' => '!==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$this->add_control(
			'media_heading',
			array(
				'label'     => 'قاب تصویر',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'media_bg',
			array(
				'label'     => 'رنگ زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__media' => 'background:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'media_radius',
			array(
				'label'      => 'گردی گوشه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pcard__media' => 'border-radius:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'media_fit',
			array(
				'label'     => 'نحوه جاگرفتن تصویر',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => array(
					'cover'   => 'قاب را پر کند (cover)',
					'contain' => 'کامل دیده شود (contain)',
				),
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__media img' => 'object-fit:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ متن‌ها */

		$this->start_controls_section(
			'section_style_card_text',
			array(
				'label' => 'متن‌های کارت',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => 'رنگ عنوان',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__title' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_hover',
			array(
				'label'     => 'رنگ عنوان در هاور',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__title a:hover' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typo',
				'label'    => 'قلم عنوان',
				'selector' => '{{WRAPPER}} .ard-pcard__title',
			)
		);

		$this->add_responsive_control(
			'title_min_height',
			array(
				'label'       => 'حداقل ارتفاع عنوان',
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array( 'px' => array( 'min' => 0, 'max' => 90 ) ),
				'selectors'   => array(
					'{{WRAPPER}} .ard-pcard__title' => 'min-block-size:{{SIZE}}{{UNIT}};',
				),
				'description' => 'با یک عدد ثابت، قیمت‌ها در همهٔ کارت‌ها هم‌تراز می‌مانند.',
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => 'رنگ قیمت',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__price' => 'color:{{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typo',
				'label'    => 'قلم قیمت',
				'selector' => '{{WRAPPER}} .ard-pcard__price',
			)
		);

		$this->add_control(
			'old_price_color',
			array(
				'label'     => 'رنگ قیمت خط‌خورده',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__old' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rate_color',
			array(
				'label'     => 'رنگ متن امتیاز',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__rate' => 'color:{{VALUE}};',
				),
				'separator' => 'before',
				'condition' => array( 'show_rating' => 'yes' ),
			)
		);

		$this->add_control(
			'star_color',
			array(
				'label'     => 'رنگ ستاره‌ها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__rate .ard-stars' => 'color:{{VALUE}};',
				),
				'condition' => array( 'show_rating' => 'yes' ),
			)
		);

		$this->add_control(
			'badge_bg',
			array(
				'label'     => 'زمینه نشان تخفیف',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard .ard-badge--sale' => 'background:{{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'badge_color',
			array(
				'label'     => 'متن نشان تخفیف',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard .ard-badge--sale' => 'color:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ دکمه */

		$this->start_controls_section(
			'section_style_card_btn',
			array(
				'label' => 'دکمه کارت',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'fab_size',
			array(
				'label'      => 'اندازه دکمه گرد',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 32, 'max' => 80 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 46 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pcard' => '--ard-fab:{{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'variant' => 'framed' ),
			)
		);

		$this->add_control(
			'btn_bg',
			array(
				'label'     => 'رنگ زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__fab, {{WRAPPER}} .ard-pcard__btn' => 'background:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'btn_color',
			array(
				'label'     => 'رنگ متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__fab, {{WRAPPER}} .ard-pcard__btn' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'btn_border',
			array(
				'label'     => 'رنگ حاشیه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__btn' => 'border-color:{{VALUE}};',
				),
				'condition' => array( 'variant' => 'plain' ),
			)
		);

		$this->add_control(
			'btn_bg_hover',
			array(
				'label'     => 'زمینه در هاور',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__fab:hover, {{WRAPPER}} .ard-pcard__btn:hover' => 'background:{{VALUE}};',
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
					'{{WRAPPER}} .ard-pcard__btn' => 'border-radius:{{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'variant' => 'plain' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'btn_typo',
				'selector'  => '{{WRAPPER}} .ard-pcard__btn',
				'condition' => array( 'variant' => 'plain' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = $this->get_product_items( $s );

		if ( empty( $items ) ) {
			$this->render_empty( 'محصولی برای نمایش وجود ندارد. یک محصول اضافه کنید یا منبع ووکامرس را انتخاب کنید.' );

			return;
		}

		$tabs   = $this->collect_tabs( $s, $items );
		$framed = 'framed' === $s['variant'];

		$this->render_heading();

		if ( ! empty( $tabs ) ) {
			echo '<div class="ard-tabs" role="tablist">';
			printf(
				'<button type="button" class="ard-tabs__btn is-active" data-ard-filter="*" role="tab" aria-selected="true">%s</button>',
				esc_html( $s['all_label'] )
			);

			foreach ( $tabs as $tab ) {
				printf(
					'<button type="button" class="ard-tabs__btn" data-ard-filter="%s" role="tab" aria-selected="false">%s</button>',
					esc_attr( $tab ),
					esc_html( $tab )
				);
			}

			echo '</div>';
		}

		$this->render_row_cap_css( $s );

		/*
		 * وقتی محتوای شبکه به فیلترهای آدرس وابسته است، ناحیه نشان‌دار می‌شود تا
		 * بارگذاری ایجکسی آرشیو بتواند همین‌جا را هم تازه کند.
		 */
		$filtered = 'archive' === $s['source'] || 'yes' === ( $s['respect_filters'] ?? '' );

		printf(
			'<div class="ard-pgrid ard-pgrid--%s"%s%s>',
			esc_attr( $s['variant'] ),
			! empty( $tabs ) ? ' data-ard-filterable' : '',
			$filtered ? ' data-ard-region="products"' : ''
		);

		foreach ( $items as $item ) {
			$tab = isset( $item['tab'] ) ? (string) $item['tab'] : '';

			if ( '' !== $tab ) {
				printf( '<div class="ard-pgrid__cell" data-tab="%s">', esc_attr( $tab ) );
			} else {
				echo '<div class="ard-pgrid__cell">';
			}

			$rating = isset( $item['rating'] ) ? $item['rating'] : 0;

			// امتیاز از repeater به‌صورت آرایه SLIDER می‌آید.
			if ( is_array( $rating ) ) {
				$rating = isset( $rating['size'] ) ? $rating['size'] : 0;
			}

			$item['rating'] = $rating;

			$this->render_product_card(
				$item,
				array(
					'variant'       => $s['variant'],
					'link_mode'     => $s['link_mode'],
					'button_action' => $s['button_action'],
					'show_rating'   => 'yes' === $s['show_rating'],
					'show_fab'    => $framed,
					'show_button' => ! $framed,
					'button_text' => $s['button_text'],
					'notch'       => $framed && 'yes' === $s['notch'],
					'placeholder' => '۸۰۰×۸۰۰',
				)
			);

			echo '</div>';
		}

		echo '</div>';
	}

	/**
	 * سقف ردیف در هر اندازهٔ صفحه.
	 *
	 * تعداد کارتِ قابل نمایش، «ردیف × ستون» همان اندازه است. چون این عدد از دو
	 * کنترل ساخته می‌شود، با selectors المنتور درنمی‌آید و همین‌جا به‌شکل قاعدهٔ
	 * nth-child چاپ می‌شود. هر مدیاکوئری بازهٔ بسته دارد تا لازم نباشد قاعدهٔ
	 * اندازهٔ بزرگ‌تر را خنثی کنیم.
	 *
	 * @param array $s تنظیمات.
	 */
	private function render_row_cap_css( $s ) {
		$mobile = 767;
		$tablet = 1024;

		if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->breakpoints ) ) {
			$points = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();

			if ( isset( $points['mobile'] ) ) {
				$mobile = (int) $points['mobile']->get_value();
			}

			if ( isset( $points['tablet'] ) ) {
				$tablet = (int) $points['tablet']->get_value();
			}
		}

		$devices = array(
			array( 'suffix' => '', 'media' => sprintf( '@media (min-width:%dpx)', $tablet + 1 ) ),
			array( 'suffix' => '_tablet', 'media' => sprintf( '@media (min-width:%dpx) and (max-width:%dpx)', $mobile + 1, $tablet ) ),
			array( 'suffix' => '_mobile', 'media' => sprintf( '@media (max-width:%dpx)', $mobile ) ),
		);

		$scope = '.elementor-element-' . $this->get_id() . ' .ard-pgrid__cell';
		$rules = '';

		// مقدار خالیِ کنترل واکنش‌گرا یعنی «از اندازهٔ بزرگ‌تر ارث ببر».
		$rows = 0;
		$cols = 0;

		foreach ( $devices as $device ) {
			$row_key = 'rows' . $device['suffix'];
			$col_key = 'columns' . $device['suffix'];

			if ( isset( $s[ $row_key ] ) && '' !== $s[ $row_key ] ) {
				$rows = (int) $s[ $row_key ];
			}

			if ( ! empty( $s[ $col_key ] ) ) {
				$cols = (int) $s[ $col_key ];
			}

			if ( $rows < 1 || $cols < 1 ) {
				continue;
			}

			$rules .= sprintf(
				'%s{%s:nth-child(n+%d){display:none}}',
				$device['media'],
				$scope,
				( $rows * $cols ) + 1
			);
		}

		if ( '' === $rules ) {
			return;
		}

		printf( '<style>%s</style>', $rules ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- همه اجزا عدد صحیح‌اند.
	}

	/**
	 * برچسب‌های یکتای تب.
	 *
	 * @param array $s
	 * @param array $items
	 * @return array
	 */
	private function collect_tabs( $s, $items ) {
		if ( 'yes' !== $s['show_tabs'] ) {
			return array();
		}

		$tabs = array();

		foreach ( $items as $item ) {
			$tab = isset( $item['tab'] ) ? trim( (string) $item['tab'] ) : '';

			if ( '' !== $tab && ! in_array( $tab, $tabs, true ) ) {
				$tabs[] = $tab;
			}
		}

		return $tabs;
	}
}
