<?php
/**
 * ویجت اسلایدر مطالب بلاگ.
 *
 * حلقه‌ای از کارت‌های نوشته که افقی اسلاید می‌شوند: تصویر با نشان‌های زمان
 * مطالعه و تاریخ رویش، بعد عنوان و خلاصه. دکمه‌های قبلی/بعدی، نقطه‌های
 * صفحه‌بندی و تعداد نمایش در هر نما همه از تنظیمات می‌آیند.
 *
 * موتور اسلایدش همان کروسل مشترک اردال است (data-ard-carousel در ardal.js)،
 * پس درگ با ماوس، لمس، چرخش خودکار و صفحه‌بندی از قبل حاضرند.
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

class Ardal_Widget_Posts_Slider extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-posts-slider';
	}

	public function get_title() {
		return 'اردال — اسلایدر مطالب';
	}

	public function get_icon() {
		return 'eicon-post-slider';
	}

	public function get_keywords() {
		return array( 'اردال', 'بلاگ', 'مطالب', 'اسلایدر', 'حلقه', 'posts', 'blog', 'slider', 'carousel' );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-posts-slider' );
	}

	protected function register_controls() {

		/* ------------------------------------------------ محتوا */

		$this->start_controls_section(
			'section_content',
			array( 'label' => 'مطالب' )
		);

		$this->add_control(
			'source',
			array(
				'label'   => 'منبع',
				'type'    => Controls_Manager::SELECT,
				'default' => 'wp',
				'options' => array(
					'wp'     => 'نوشته‌های وردپرس',
					'manual' => 'دستی',
				),
			)
		);

		$this->add_control(
			'post_type',
			array(
				'label'     => 'نوع محتوا',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'post',
				'options'   => $this->post_type_options(),
				'condition' => array( 'source' => 'wp' ),
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'       => 'تعداد',
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'max'         => 24,
				'default'     => 6,
				'condition'   => array( 'source' => 'wp' ),
				'description' => 'چند نوشته وارد اسلایدر شود. «تعداد در هر نما» جداگانه تنظیم می‌شود.',
			)
		);

		$this->add_control(
			'categories',
			array(
				'label'       => 'فقط این دسته‌ها',
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->category_options(),
				'label_block' => true,
				'condition'   => array( 'source' => 'wp', 'post_type' => 'post' ),
				'description' => 'خالی = همهٔ دسته‌ها.',
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'     => 'ترتیب',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'date',
				'options'   => array(
					'date'          => 'تازه‌ترین',
					'title'         => 'الفبا',
					'comment_count' => 'پرگفت‌وگوترین',
					'rand'          => 'تصادفی',
				),
				'condition' => array( 'source' => 'wp' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			array(
				'label'       => 'عنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'عنوان مطلب',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'excerpt',
			array(
				'label'       => 'خلاصه',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => '',
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
			'date',
			array(
				'label'   => 'تاریخ',
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$repeater->add_control(
			'time',
			array(
				'label'       => 'زمان مطالعه',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => '۵ دقیقه',
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
				'title_field' => '{{{ title }}}',
				'condition'   => array( 'source' => 'manual' ),
				'default'     => array(
					array( 'title' => 'عنوان مطلب اول' ),
					array( 'title' => 'عنوان مطلب دوم' ),
					array( 'title' => 'عنوان مطلب سوم' ),
				),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ بخش‌های کارت */

		$this->start_controls_section(
			'section_parts',
			array( 'label' => 'بخش‌های کارت' )
		);

		$this->add_tag_control( 'item_tag', 'تگ عنوان کارت', 'h3' );

		$this->add_control(
			'show_excerpt',
			array(
				'label'        => 'نمایش خلاصه',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'excerpt_words',
			array(
				'label'     => 'تعداد واژه خلاصه',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 5,
				'max'       => 60,
				'default'   => 18,
				'condition' => array( 'show_excerpt' => 'yes', 'source' => 'wp' ),
			)
		);

		$this->add_control(
			'show_time',
			array(
				'label'        => 'نشان زمان مطالعه',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_date',
			array(
				'label'        => 'نشان تاریخ',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'chip_text',
			array(
				'label'        => 'متن کنار آیکون نشان‌ها',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'description'  => 'خاموش: فقط آیکون (مقدار روی tooltip می‌ماند). روشن: عدد کنار آیکون هم نوشته می‌شود.',
			)
		);

		$this->add_control(
			'date_format',
			array(
				'label'       => 'قالب تاریخ',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'j F',
				'condition'   => array( 'show_date' => 'yes', 'source' => 'wp' ),
				'description' => 'مثل قالب تاریخ وردپرس. اگر افزونهٔ تاریخ شمسی دارید، شمسی می‌شود.',
			)
		);

		$this->add_control(
			'time_suffix',
			array(
				'label'     => 'واحد زمان مطالعه',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'دقیقه',
				'condition' => array( 'show_time' => 'yes', 'source' => 'wp' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ اسلایدر */

		$this->start_controls_section(
			'section_slider',
			array( 'label' => 'اسلایدر' )
		);

		$this->add_responsive_control(
			'per_view',
			array(
				'label'          => 'تعداد در هر نما',
				'type'           => Controls_Manager::SLIDER,
				'range'          => array( 'px' => array( 'min' => 1, 'max' => 6, 'step' => 1 ) ),
				'default'        => array( 'size' => 3 ),
				'tablet_default' => array( 'size' => 2 ),
				'mobile_default' => array( 'size' => 1 ),
				'selectors'      => array(
					// اسلایدر پهنای هر کارت را از همین عدد می‌سازد و جاوااسکریپت
					// هم برای شمردن صفحه‌ها همین را می‌خواند.
					'{{WRAPPER}} .ard-pslider__track' => '--ard-per:{{SIZE}};',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => 'فاصله کارت‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 48 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 16 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pslider' => '--ard-ps-gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'featured',
			array(
				'label'        => 'کارت وسط عریض‌تر باشد',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'کارتی که وسط نما ایستاده بزرگ‌تر می‌شود و با اسلاید، بزرگی به کارت بعدی می‌رسد.',
			)
		);

		$this->add_control(
			'featured_ratio',
			array(
				'label'     => 'چند برابر بقیه',
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 1.1, 'max' => 3, 'step' => 0.05 ) ),
				'default'   => array( 'size' => 2 ),
				'condition' => array( 'featured' => 'yes' ),
				'selectors' => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-active-ratio:{{SIZE}};' ),
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'       => 'چرخش خودکار',
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 20000,
				'step'        => 500,
				'default'     => 0,
				'description' => 'به میلی‌ثانیه. صفر = خاموش. با اشارهٔ ماوس یا فوکوس متوقف می‌شود.',
			)
		);

		$this->add_control(
			'show_arrows',
			array(
				'label'        => 'دکمه‌های قبلی و بعدی',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'arrow_prev',
			array(
				'label'       => 'آیکون «قبلی»',
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'condition'   => array( 'show_arrows' => 'yes' ),
				'description' => 'خالی = فلش پیش‌فرض اردال.',
			)
		);

		$this->add_control(
			'arrow_next',
			array(
				'label'       => 'آیکون «بعدی»',
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'condition'   => array( 'show_arrows' => 'yes' ),
			)
		);

		$this->add_control(
			'arrow_place',
			array(
				'label'       => 'جای دکمه‌ها',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'outside',
				'options'     => array(
					'outside' => 'بیرون کارت‌ها',
					'inside'  => 'روی کارت‌ها',
				),
				'description' => 'نقطهٔ شروع است؛ جای دقیق هر دکمه را در تب «ظاهر» می‌توانید آزادانه جابه‌جا کنید.',
				'condition'   => array( 'show_arrows' => 'yes' ),
			)
		);

		$this->add_control(
			'show_dots',
			array(
				'label'        => 'نقطه‌های صفحه‌بندی',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ عنوان بخش */

		$this->add_heading_controls( 'تازه‌ترین مطالب', 'accent' );

		/* ------------------------------------------------ ظاهر کارت */

		$this->start_controls_section(
			'section_card_style',
			array(
				'label' => 'ظاهر کارت',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'card_bg',
			array(
				'label'     => 'زمینه کارت',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-card-bg:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} .ard-pcardp__box',
			)
		);

		$this->add_control(
			'card_radius',
			array(
				'label'      => 'گردی کارت',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'card_pad',
			array(
				'label'      => 'فاصله داخلی کارت',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 32 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-pad:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_shadow',
				'selector' => '{{WRAPPER}} .ard-pcardp__box',
			)
		);

		$this->add_responsive_control(
			'media_inset',
			array(
				'label'       => 'تورفتگی تصویر از دو طرف',
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '%', 'px' ),
				'range'       => array(
					'%'  => array( 'min' => 0, 'max' => 12, 'step' => 0.5 ),
					'px' => array( 'min' => 0, 'max' => 40 ),
				),
				'default'     => array( 'unit' => '%', 'size' => 3.8 ),
				'description' => 'تصویر بالای قاب متن می‌نشیند و کمی باریک‌تر از آن است؛ این عدد همان اختلاف است.',
				'selectors'   => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-media-inset:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'image_h',
			array(
				'label'      => 'ارتفاع تصویر',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 100, 'max' => 420 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 200 ),
				'selectors'  => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-img-h:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_media_fit_control( '{{WRAPPER}} .ard-pcardp__media img' );

		$this->add_control(
			'title_color',
			array(
				'label'     => 'رنگ عنوان',
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-title:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typo',
				'label'    => 'قلم عنوان',
				'selector' => '{{WRAPPER}} .ard-pcardp__title',
			)
		);

		$this->add_control(
			'title_lines',
			array(
				'label'     => 'حداکثر خط عنوان',
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 1, 'max' => 4, 'step' => 1 ) ),
				'default'   => array( 'size' => 1 ),
				'selectors' => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-title-lines:{{SIZE}};' ),
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => 'رنگ خلاصه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-excerpt:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'excerpt_typo',
				'label'    => 'قلم خلاصه',
				'selector' => '{{WRAPPER}} .ard-pcardp__excerpt',
			)
		);

		$this->add_control(
			'excerpt_lines',
			array(
				'label'     => 'حداکثر خط خلاصه',
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 1, 'max' => 6, 'step' => 1 ) ),
				'default'   => array( 'size' => 2 ),
				'selectors' => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-excerpt-lines:{{SIZE}};' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ ظاهر نشان‌ها */

		$this->start_controls_section(
			'section_chip_style',
			array(
				'label' => 'ظاهر نشان‌های روی تصویر',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'chip_bg',
			array(
				'label'     => 'زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-chip-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'chip_ink',
			array(
				'label'     => 'رنگ آیکون',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-chip-ink:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'chip_size',
			array(
				'label'      => 'اندازه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 24, 'max' => 56 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-chip:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'chip_place',
			array(
				'label'     => 'گوشه',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'start',
				'options'   => array(
					'start' => 'بالا، سمت شروع (راست)',
					'end'   => 'بالا، سمت پایان (چپ)',
				),
				// کلاس روی ریشه، نه سلکتور؛ دو اعلان inset-inline روی یک قانون
				// همدیگر را خنثی می‌کردند.
				'prefix_class' => 'ard-ps-chips-',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ ظاهر دکمه‌ها */

		$this->start_controls_section(
			'section_arrow_style',
			array(
				'label'     => 'ظاهر دکمه‌های قبلی و بعدی',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_arrows' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'arrow_size',
			array(
				'label'       => 'پهنای دکمه',
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', 'em' ),
				'range'       => array(
					'px' => array( 'min' => 20, 'max' => 120 ),
					'em' => array( 'min' => 1, 'max' => 8, 'step' => 0.1 ),
				),
				'description' => 'اگر ارتفاع را خالی بگذارید، دکمه مربع می‌ماند.',
				'selectors'   => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-arrow:{{SIZE}}{{UNIT}};--ard-ps-arrow-w:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'arrow_h',
			array(
				'label'      => 'ارتفاع دکمه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array( 'min' => 20, 'max' => 120 ),
					'em' => array( 'min' => 1, 'max' => 8, 'step' => 0.1 ),
				),
				'selectors'  => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-arrow-h:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'arrow_pad',
			array(
				'label'       => 'فاصله داخلی دکمه',
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', 'em', '%' ),
				'description' => 'آیکون را داخل دکمه جابه‌جا می‌کند؛ خودِ دکمه بزرگ‌تر نمی‌شود.',
				'selectors'   => array(
					'{{WRAPPER}} .ard-pslider__arrow' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'arrow_bw',
			array(
				'label'      => 'ضخامت حاشیه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 10 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-arrow-bw:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'arrow_icon_size',
			array(
				'label'      => 'اندازه آیکون',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 8, 'max' => 32 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-arrow-icon:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'arrow_radius',
			array(
				'label'      => 'گردی دکمه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array( 'min' => 0, 'max' => 40 ),
					'%'  => array( 'min' => 0, 'max' => 50 ),
				),
				'selectors'  => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-arrow-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		/* ------------------------------------------- جایگاه */

		$this->add_control(
			'arrow_pos_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => 'هر دکمه یک لنگر افقی و یک لنگر عمودی دارد، به‌علاوهٔ عددی برای جابه‌جایی از همان لنگر. لنگرها نسبت به خودِ ناحیهٔ اسلایدر سنجیده می‌شوند.',
				'content_classes' => 'elementor-descriptor',
			)
		);

		$this->add_control(
			'arrow_prev_heading',
			array(
				'label'     => 'دکمه قبلی',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'arrow_prev_hx',
			array(
				'label'                => 'لنگر افقی',
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => 'start',
				'options'              => array(
					'start'  => array( 'title' => 'شروع', 'icon' => 'eicon-h-align-right' ),
					'center' => array( 'title' => 'وسط', 'icon' => 'eicon-h-align-center' ),
					'end'    => array( 'title' => 'پایان', 'icon' => 'eicon-h-align-left' ),
				),
				'selectors'            => array( '{{WRAPPER}} .ard-pslider' => '{{VALUE}}' ),
				'selectors_dictionary' => array(
					'start'  => '--ard-ps-prev-ax:0%;--ard-ps-prev-sx:0px;',
					'center' => '--ard-ps-prev-ax:50%;--ard-ps-prev-sx:calc(var(--ard-ps-arrow-w) / -2);',
					'end'    => '--ard-ps-prev-ax:100%;--ard-ps-prev-sx:calc(var(--ard-ps-arrow-w) * -1);',
				),
			)
		);

		$this->add_responsive_control(
			'arrow_prev_x',
			array(
				'label'      => 'جابه‌جایی افقی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw' ),
				'range'      => array(
					'px'  => array( 'min' => -500, 'max' => 500 ),
					'%'   => array( 'min' => -100, 'max' => 100 ),
					'em'  => array( 'min' => -30, 'max' => 30 ),
					'rem' => array( 'min' => -30, 'max' => 30 ),
					'vw'  => array( 'min' => -50, 'max' => 50 ),
				),
				'default'    => array( 'unit' => 'px', 'size' => 0 ),
				'selectors'  => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-prev-x:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'arrow_prev_hy',
			array(
				'label'                => 'لنگر عمودی',
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => 'center',
				'options'              => array(
					'start'  => array( 'title' => 'بالا', 'icon' => 'eicon-v-align-top' ),
					'center' => array( 'title' => 'وسط', 'icon' => 'eicon-v-align-middle' ),
					'end'    => array( 'title' => 'پایین', 'icon' => 'eicon-v-align-bottom' ),
				),
				'selectors'            => array( '{{WRAPPER}} .ard-pslider' => '{{VALUE}}' ),
				'selectors_dictionary' => array(
					'start'  => '--ard-ps-prev-ay:0%;--ard-ps-prev-sy:0px;',
					'center' => '--ard-ps-prev-ay:50%;--ard-ps-prev-sy:calc(var(--ard-ps-arrow-h) / -2);',
					'end'    => '--ard-ps-prev-ay:100%;--ard-ps-prev-sy:calc(var(--ard-ps-arrow-h) * -1);',
				),
			)
		);

		$this->add_responsive_control(
			'arrow_prev_y',
			array(
				'label'      => 'جابه‌جایی عمودی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vh' ),
				'range'      => array(
					'px'  => array( 'min' => -500, 'max' => 500 ),
					'%'   => array( 'min' => -100, 'max' => 100 ),
					'em'  => array( 'min' => -30, 'max' => 30 ),
					'rem' => array( 'min' => -30, 'max' => 30 ),
					'vh'  => array( 'min' => -50, 'max' => 50 ),
				),
				'default'    => array( 'unit' => 'px', 'size' => 0 ),
				'selectors'  => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-prev-y:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'arrow_next_heading',
			array(
				'label'     => 'دکمه بعدی',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'arrow_next_hx',
			array(
				'label'                => 'لنگر افقی',
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => 'end',
				'options'              => array(
					'start'  => array( 'title' => 'شروع', 'icon' => 'eicon-h-align-right' ),
					'center' => array( 'title' => 'وسط', 'icon' => 'eicon-h-align-center' ),
					'end'    => array( 'title' => 'پایان', 'icon' => 'eicon-h-align-left' ),
				),
				'selectors'            => array( '{{WRAPPER}} .ard-pslider' => '{{VALUE}}' ),
				'selectors_dictionary' => array(
					'start'  => '--ard-ps-next-ax:0%;--ard-ps-next-sx:0px;',
					'center' => '--ard-ps-next-ax:50%;--ard-ps-next-sx:calc(var(--ard-ps-arrow-w) / -2);',
					'end'    => '--ard-ps-next-ax:100%;--ard-ps-next-sx:calc(var(--ard-ps-arrow-w) * -1);',
				),
			)
		);

		$this->add_responsive_control(
			'arrow_next_x',
			array(
				'label'      => 'جابه‌جایی افقی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw' ),
				'range'      => array(
					'px'  => array( 'min' => -500, 'max' => 500 ),
					'%'   => array( 'min' => -100, 'max' => 100 ),
					'em'  => array( 'min' => -30, 'max' => 30 ),
					'rem' => array( 'min' => -30, 'max' => 30 ),
					'vw'  => array( 'min' => -50, 'max' => 50 ),
				),
				'default'    => array( 'unit' => 'px', 'size' => 0 ),
				'selectors'  => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-next-x:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'arrow_next_hy',
			array(
				'label'                => 'لنگر عمودی',
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => 'center',
				'options'              => array(
					'start'  => array( 'title' => 'بالا', 'icon' => 'eicon-v-align-top' ),
					'center' => array( 'title' => 'وسط', 'icon' => 'eicon-v-align-middle' ),
					'end'    => array( 'title' => 'پایین', 'icon' => 'eicon-v-align-bottom' ),
				),
				'selectors'            => array( '{{WRAPPER}} .ard-pslider' => '{{VALUE}}' ),
				'selectors_dictionary' => array(
					'start'  => '--ard-ps-next-ay:0%;--ard-ps-next-sy:0px;',
					'center' => '--ard-ps-next-ay:50%;--ard-ps-next-sy:calc(var(--ard-ps-arrow-h) / -2);',
					'end'    => '--ard-ps-next-ay:100%;--ard-ps-next-sy:calc(var(--ard-ps-arrow-h) * -1);',
				),
			)
		);

		$this->add_responsive_control(
			'arrow_next_y',
			array(
				'label'      => 'جابه‌جایی عمودی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vh' ),
				'range'      => array(
					'px'  => array( 'min' => -500, 'max' => 500 ),
					'%'   => array( 'min' => -100, 'max' => 100 ),
					'em'  => array( 'min' => -30, 'max' => 30 ),
					'rem' => array( 'min' => -30, 'max' => 30 ),
					'vh'  => array( 'min' => -50, 'max' => 50 ),
				),
				'default'    => array( 'unit' => 'px', 'size' => 0 ),
				'selectors'  => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-next-y:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'arrow_gap',
			array(
				'label'       => 'فاصله دکمه از ریل',
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array( 'px' => array( 'min' => 0, 'max' => 48 ) ),
				'separator'   => 'before',
				'description' => 'جایی که کنار ریل برای دکمه‌ها باز می‌شود.',
				'selectors'   => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-arrow-gap:{{SIZE}}{{UNIT}};' ),
				'condition'   => array( 'arrow_place' => 'outside' ),
			)
		);

		$this->add_responsive_control(
			'arrow_inset',
			array(
				'label'       => 'فاصله از لبه',
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array( 'px' => array( 'min' => -60, 'max' => 60 ) ),
				'description' => 'دکمه‌ها را به داخل یا بیرونِ لبهٔ کارت‌ها می‌برد.',
				'selectors'   => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-arrow-inset:{{SIZE}}{{UNIT}};' ),
				'condition'   => array( 'arrow_place' => 'inside' ),
			)
		);

		$this->start_controls_tabs( 'arrow_tabs' );

		$this->start_controls_tab( 'arrow_normal', array( 'label' => 'عادی' ) );

		$this->add_control(
			'arrow_bg',
			array(
				'label'     => 'زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-arrow-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'arrow_ink',
			array(
				'label'     => 'رنگ آیکون',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-arrow-ink:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'arrow_line',
			array(
				'label'     => 'رنگ حاشیه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-arrow-line:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'arrow_shadow',
				'label'    => 'سایه',
				'selector' => '{{WRAPPER}} .ard-pslider__arrow',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'arrow_hover', array( 'label' => 'شناور' ) );

		$this->add_control(
			'arrow_bg_hover',
			array(
				'label'     => 'زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-arrow-bg-hover:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'arrow_ink_hover',
			array(
				'label'     => 'رنگ آیکون',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-arrow-ink-hover:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'arrow_line_hover',
			array(
				'label'     => 'رنگ حاشیه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-arrow-line-hover:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'arrow_shadow_hover',
				'label'    => 'سایه',
				'selector' => '{{WRAPPER}} .ard-pslider__arrow:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/* ------------------------------------------------ ظاهر نقطه‌ها */

		$this->start_controls_section(
			'section_dot_style',
			array(
				'label'     => 'ظاهر نقطه‌ها',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_dots' => 'yes' ),
			)
		);

		$this->add_control(
			'dot_color',
			array(
				'label'     => 'رنگ نقطه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-dot:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'dot_active',
			array(
				'label'     => 'رنگ نقطه فعال',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-dot-active:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'dot_size',
			array(
				'label'      => 'اندازه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 4, 'max' => 20 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-dot-size:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'dot_active_w',
			array(
				'label'      => 'پهنای نقطه فعال',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 4, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-dot-active-w:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'dot_gap',
			array(
				'label'      => 'فاصله نقطه‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 2, 'max' => 24 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pslider' => '--ard-ps-dot-gap:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = $this->entries( $s );

		if ( ! $items ) {
			$this->render_empty( 'مطلبی برای نمایش نیست. منبع را بررسی کنید یا در حالت دستی آیتم اضافه کنید.' );

			return;
		}

		$arrows = 'yes' === $s['show_arrows'];
		$dots   = 'yes' === $s['show_dots'] && count( $items ) > 1;
		$tag    = self::tag( $s['item_tag'], 'h3' );

		$featured = 'yes' === $s['featured'];

		$classes = array_filter(
			array(
				'ard-pslider',
				'ard-pslider--arrows-' . ( $arrows ? $s['arrow_place'] : 'none' ),
				$featured ? 'ard-pslider--featured' : '',
			)
		);
		?>
		<section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-ard-slider>

			<?php $this->render_heading(); ?>

			<div class="ard-pslider__stage">
				<?php if ( $arrows ) : ?>
					<?php $this->arrows( $s, 'prev' ); ?>
				<?php endif; ?>

				<div class="ard-pslider__car" data-ard-carousel data-interval="<?php echo esc_attr( max( 0, (int) $s['autoplay'] ) ); ?>">
					<div class="ard-pslider__track" data-ard-viewport<?php echo $featured ? ' data-ard-active-slide' : ''; ?>>
						<?php foreach ( $items as $item ) : ?>
							<article class="ard-pcardp">
								<div class="ard-pcardp__media">
									<?php echo Ardal_Helpers::image( $item['image'], '۶۴۰×۴۲۰', $item['title'], 'medium_large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

									<?php $chips = $this->chips( $item, $s ); ?>
									<?php if ( $chips ) : ?>
										<div class="ard-pcardp__chips">
											<?php foreach ( $chips as $chip ) : ?>
												<span class="ard-pcardp__chip" title="<?php echo esc_attr( $chip['label'] ); ?>">
													<?php echo Ardal_Helpers::icon( $chip['icon'], 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

													<?php if ( 'yes' === $s['chip_text'] ) : ?>
														<span class="ard-pcardp__chip-text"><?php echo esc_html( $chip['text'] ); ?></span>
													<?php else : ?>
														<span class="ard-sr-only"><?php echo esc_html( $chip['label'] ); ?></span>
													<?php endif; ?>
												</span>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>
								</div>

								<div class="ard-pcardp__box">
									<<?php echo esc_html( $tag ); ?> class="ard-pcardp__title">
										<?php if ( ! empty( $item['link']['url'] ) ) : ?>
											<a<?php echo Ardal_Helpers::link_attrs( $item['link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $item['title'] ); ?></a>
										<?php else : ?>
											<?php echo esc_html( $item['title'] ); ?>
										<?php endif; ?>
									</<?php echo esc_html( $tag ); ?>>

									<?php if ( 'yes' === $s['show_excerpt'] && '' !== trim( (string) $item['excerpt'] ) ) : ?>
										<p class="ard-pcardp__excerpt"><?php echo esc_html( $item['excerpt'] ); ?></p>
									<?php endif; ?>
								</div>
							</article>
						<?php endforeach; ?>
					</div>

					<?php if ( $dots ) : ?>
						<div class="ard-pslider__dots" data-ard-dots="ard-pslider__dot" role="tablist" aria-label="انتخاب صفحه"></div>
					<?php endif; ?>
				</div>

				<?php if ( $arrows ) : ?>
					<?php $this->arrows( $s, 'next' ); ?>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}

	/**
	 * یکی از دکمه‌های ناوبری.
	 *
	 * هر دو دکمه داخل .ard-pslider__stage چاپ می‌شوند و با position: absolute
	 * سر جایشان می‌نشینند؛ جایشان را متغیرهای CSS تعیین می‌کنند نه مارک‌آپ،
	 * تا کنترل‌های تب ظاهر بتوانند آزادانه جابه‌جایشان کنند.
	 *
	 * @param array  $s
	 * @param string $only prev | next.
	 */
	private function arrows( $s, $only = 'prev' ) {
		$show_prev = 'next' !== $only;
		$show_next = 'prev' !== $only;

		if ( $show_prev ) {
			?>
			<button type="button" class="ard-pslider__arrow ard-pslider__arrow--prev" data-ard-prev aria-label="قبلی">
				<?php $this->arrow_icon( $s['arrow_prev'], 'chevron-right' ); ?>
			</button>
			<?php
		}

		if ( $show_next ) {
			?>
			<button type="button" class="ard-pslider__arrow ard-pslider__arrow--next" data-ard-next aria-label="بعدی">
				<?php $this->arrow_icon( $s['arrow_next'], 'chevron' ); ?>
			</button>
			<?php
		}
	}

	/**
	 * آیکون یک دکمه: انتخاب کاربر، وگرنه فلش پیش‌فرض اردال.
	 *
	 * @param array  $icon     مقدار کنترل ICONS.
	 * @param string $fallback نام آیکون داخلی.
	 */
	private function arrow_icon( $icon, $fallback ) {
		if ( ! empty( $icon['value'] ) && class_exists( '\Elementor\Icons_Manager' ) ) {
			Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );

			return;
		}

		echo Ardal_Helpers::icon( $fallback, 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * نشان‌های روی تصویر.
	 *
	 * @param array $item
	 * @param array $s
	 * @return array
	 */
	private function chips( $item, $s ) {
		$out = array();

		if ( 'yes' === $s['show_time'] && '' !== trim( (string) $item['time'] ) ) {
			$out[] = array(
				'icon'  => 'clock',
				'text'  => $item['time'],
				'label' => 'زمان مطالعه: ' . $item['time'],
			);
		}

		if ( 'yes' === $s['show_date'] && '' !== trim( (string) $item['date'] ) ) {
			$out[] = array(
				'icon'  => 'calendar',
				'text'  => $item['date'],
				'label' => 'تاریخ انتشار: ' . $item['date'],
			);
		}

		return $out;
	}

	/**
	 * آیتم‌ها از وردپرس یا از تکرارکننده.
	 *
	 * @param array $s
	 * @return array
	 */
	private function entries( $s ) {
		if ( 'manual' === $s['source'] ) {
			$out = array();

			foreach ( (array) $s['items'] as $row ) {
				$title = isset( $row['title'] ) ? trim( (string) $row['title'] ) : '';

				if ( '' === $title ) {
					continue;
				}

				$out[] = array(
					'title'   => $title,
					'excerpt' => isset( $row['excerpt'] ) ? (string) $row['excerpt'] : '',
					'image'   => isset( $row['image'] ) ? $row['image'] : array(),
					'date'    => isset( $row['date'] ) ? (string) $row['date'] : '',
					'time'    => isset( $row['time'] ) ? (string) $row['time'] : '',
					'link'    => isset( $row['link'] ) ? $row['link'] : array( 'url' => '' ),
				);
			}

			return $out;
		}

		$args = array(
			'post_type'           => sanitize_key( (string) $s['post_type'] ),
			'post_status'         => 'publish',
			'posts_per_page'      => max( 1, min( 24, (int) $s['limit'] ) ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'orderby'             => in_array( $s['orderby'], array( 'title', 'comment_count', 'rand' ), true ) ? $s['orderby'] : 'date',
		);

		$cats = array_filter( array_map( 'absint', (array) $s['categories'] ) );

		if ( $cats && 'post' === $args['post_type'] ) {
			$args['category__in'] = $cats;
		}

		$query = new WP_Query( $args );
		$out   = array();
		$words = max( 5, (int) $s['excerpt_words'] );

		foreach ( $query->posts as $post ) {
			$thumb = get_the_post_thumbnail_url( $post, 'medium_large' );

			$out[] = array(
				'title'   => get_the_title( $post ),
				'excerpt' => 'yes' === $s['show_excerpt'] ? $this->excerpt( $post, $words ) : '',
				'image'   => array( 'url' => $thumb ? $thumb : '' ),
				'date'    => 'yes' === $s['show_date'] ? Ardal_Helpers::fa_digits( get_the_date( (string) $s['date_format'], $post ) ) : '',
				'time'    => 'yes' === $s['show_time'] ? $this->read_time( $post, (string) $s['time_suffix'] ) : '',
				'link'    => array( 'url' => get_permalink( $post ) ),
			);
		}

		wp_reset_postdata();

		return $out;
	}

	/**
	 * خلاصهٔ نوشته، به تعداد واژهٔ خواسته‌شده.
	 *
	 * @param WP_Post $post
	 * @param int     $words
	 * @return string
	 */
	private function excerpt( $post, $words ) {
		$text = has_excerpt( $post ) ? $post->post_excerpt : $post->post_content;

		// شورت‌کدها و بلوک‌ها نباید داخل خلاصه چاپ شوند.
		$text = strip_shortcodes( (string) $text );
		$text = excerpt_remove_blocks( $text );
		$text = wp_strip_all_tags( $text );

		return wp_trim_words( $text, $words, '…' );
	}

	/**
	 * زمان تقریبی مطالعه.
	 *
	 * پایه ۲۰۰ واژه در دقیقه است که برای متن فارسی هم سرانگشتی جواب می‌دهد؛
	 * دست‌کم یک دقیقه، چون «۰ دقیقه» چیزی به کاربر نمی‌گوید.
	 *
	 * @param WP_Post $post
	 * @param string  $suffix
	 * @return string
	 */
	private function read_time( $post, $suffix ) {
		$text = wp_strip_all_tags( strip_shortcodes( (string) $post->post_content ) );

		/*
		 * شمارش با شکستن روی فاصله، نه str_word_count.
		 *
		 * آن تابع بایتی کار می‌کند و برای فارسی هر حرفِ دوبایتی را جدا
		 * می‌شمارد؛ نتیجه‌اش زمان مطالعه‌ای چند برابر واقعی بود. نیم‌فاصله هم
		 * جداکننده حساب می‌شود، وگرنه «می‌رود» یک واژه دیده نمی‌شد.
		 */
		$words = preg_split( '/[\s\x{200c}]+/u', trim( $text ), -1, PREG_SPLIT_NO_EMPTY );
		$count = max( 1, (int) ceil( count( (array) $words ) / 200 ) );
		$out   = Ardal_Helpers::fa_digits( $count );

		return '' !== trim( $suffix ) ? $out . ' ' . trim( $suffix ) : $out;
	}

	/**
	 * نوع‌های محتوای عمومی.
	 *
	 * @return array
	 */
	private function post_type_options() {
		$out = array();

		foreach ( get_post_types( array( 'public' => true ), 'objects' ) as $type ) {
			if ( in_array( $type->name, array( 'attachment', 'product' ), true ) ) {
				continue;
			}

			$out[ $type->name ] = $type->labels->singular_name ? $type->labels->singular_name : $type->name;
		}

		return $out ? $out : array( 'post' => 'نوشته' );
	}

	/**
	 * دسته‌های نوشته، بدون «دسته‌بندی نشده».
	 *
	 * @return array
	 */
	private function category_options() {
		$terms = get_terms(
			array(
				'taxonomy'   => 'category',
				'hide_empty' => false,
				'number'     => 200,
				'exclude'    => class_exists( 'Ardal_Archive' ) ? Ardal_Archive::excluded_terms( 'category' ) : array(),
			)
		);

		$out = array();

		if ( is_wp_error( $terms ) ) {
			return $out;
		}

		foreach ( $terms as $term ) {
			$out[ $term->term_id ] = $term->name;
		}

		return $out;
	}
}
