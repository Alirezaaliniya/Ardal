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
		return array( 'اردال', 'محصول', 'محصولات', 'شبکه', 'آرشیو', 'products', 'grid', 'archive' );
	}

	/**
	 * سبک کارت آرشیو و صفحه‌بندی، استایل و اسکریپت آرشیو را لازم دارند.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'ardal', 'ardal-archive' );
	}

	/**
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'ardal', 'ardal-archive' );
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
			'card_style',
			array(
				'label'       => 'طرح کارت',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'ardal',
				'options'     => array(
					'ardal'   => 'کارت اردال (صفحه اصلی)',
					'archive' => 'کارت آرشیو فروشگاه',
				),
				'label_block' => true,
				'description' => 'طرح آرشیو همان کارت سفید فهرست فروشگاه است: نشان تخفیف، برچسب ناموجود، علاقه‌مندی و دکمهٔ تمام‌عرض.',
			)
		);

		$this->add_control(
			'variant',
			array(
				'label'     => 'سبک کارت',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'framed',
				'options'   => array(
					'framed' => 'کارت حاشیه‌دار با دکمه گرد سبز (دیزاین ۱a)',
					'plain'  => 'بدون حاشیه با دکمه «افزودن به سبد» (دیزاین ۱b)',
				),
				'condition' => array( 'card_style' => 'ardal' ),
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
				'condition'   => array( 'card_style' => 'ardal' ),
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
				'label'      => 'متن دکمه',
				'type'       => Controls_Manager::TEXT,
				'default'    => 'افزودن به سبد',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'card_style',
							'operator' => '===',
							'value'    => 'archive',
						),
						array(
							'name'     => 'variant',
							'operator' => '===',
							'value'    => 'plain',
						),
					),
				),
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
				'condition'    => array( 'card_style' => 'ardal', 'variant' => 'framed' ),
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
					'card_style' => 'ardal',
					'variant'    => 'framed',
					'notch'      => 'yes',
				),
			)
		);

		/* --------------------------------- گزینه‌های ویژهٔ کارت آرشیو */

		$this->add_control(
			'acard_heading',
			array(
				'label'     => 'کارت آرشیو',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'card_style' => 'archive' ),
			)
		);

		$this->add_control(
			'image_ratio',
			array(
				'label'     => 'نسبت تصویر',
				'type'      => Controls_Manager::SELECT,
				'default'   => '1',
				'options'   => array(
					'1'    => 'مربع (۱:۱)',
					'0.8'  => 'افقی ملایم (۵:۴)',
					'1.25' => 'عمودی (۴:۵)',
					'1.4'  => 'عمودی بلند (۵:۷)',
				),
				'condition' => array( 'card_style' => 'archive' ),
			)
		);

		$this->add_control(
			'show_badge',
			array(
				'label'        => 'نشان تخفیف',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => 'بله',
				'label_off'    => 'خیر',
				'return_value' => 'yes',
				'condition'    => array( 'card_style' => 'archive' ),
			)
		);

		$this->add_control(
			'badge_type',
			array(
				'label'     => 'متن نشان',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'percent',
				'options'   => array(
					'percent' => 'درصد تخفیف',
					'text'    => 'متن ثابت',
				),
				'condition' => array( 'card_style' => 'archive', 'show_badge' => 'yes' ),
			)
		);

		$this->add_control(
			'badge_text',
			array(
				'label'     => 'متن ثابت نشان',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'حراج',
				'condition' => array( 'card_style' => 'archive', 'show_badge' => 'yes', 'badge_type' => 'text' ),
			)
		);

		$this->add_control(
			'show_stock',
			array(
				'label'        => 'برچسب «ناموجود»',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'card_style' => 'archive' ),
			)
		);

		$this->add_control(
			'show_wishlist',
			array(
				'label'        => 'دکمه علاقه‌مندی',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => Ardal_Product::wishlist_on() ? '' : 'علاقه‌مندی در تنظیمات اردال خاموش است.',
				'condition'    => array( 'card_style' => 'archive' ),
			)
		);

		$this->add_control(
			'rating_style',
			array(
				'label'     => 'شکل امتیاز',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'both',
				'options'   => array(
					'both'  => 'ستاره‌ها و عدد',
					'stars' => 'فقط ستاره‌ها',
					'num'   => 'فقط عدد',
				),
				'condition' => array( 'card_style' => 'archive', 'show_rating' => 'yes' ),
			)
		);

		$this->add_control(
			'hide_empty_rating',
			array(
				'label'        => 'پنهان‌کردن امتیاز صفر',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'card_style' => 'archive', 'show_rating' => 'yes' ),
			)
		);

		$this->add_control(
			'show_cat',
			array(
				'label'        => 'نام دسته بالای عنوان',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'condition'    => array( 'card_style' => 'archive' ),
			)
		);

		$this->add_control(
			'show_subtitle',
			array(
				'label'        => 'عنوان انگلیسی زیر نام',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'condition'    => array( 'card_style' => 'archive' ),
			)
		);

		// فهرست تگ‌ها همان فهرست مشترک افزونه است و به سبک کارت ربطی ندارد؛
		// هر دو سبک عنوان چاپ می‌کنند، پس هر دو باید قابل انتخاب باشند.
		$this->add_tag_control( 'card_title_tag', 'تگ عنوان کارت', 'h3' );

		$this->add_control(
			'title_lines',
			array(
				'label'       => 'حداکثر خط عنوان',
				'type'        => Controls_Manager::SLIDER,
				'range'       => array( 'px' => array( 'min' => 1, 'max' => 4, 'step' => 1 ) ),
				'default'     => array( 'size' => 2 ),
				'description' => 'یک یعنی عنوان‌ها یک‌خطی شوند و بقیه‌اش با «…» کوتاه شود.',
				// همین عدد هم سقفِ خطوط را می‌سازد هم ارتفاعِ رزروشده را، تا
				// عنوانِ یک‌خطی و دوخطی قیمت را جابه‌جا نکنند. هر دو سبک کارت
				// یک کنترل دارند، چون از دید کاربر یک تنظیم است.
				'selectors'   => array(
					'{{WRAPPER}} .ard-acard' => '--ard-acard-lines:{{SIZE}};',
					'{{WRAPPER}} .ard-pcard' => '--ard-pcard-lines:{{SIZE}};',
				),
			)
		);

		$this->add_control(
			'show_price',
			array(
				'label'        => 'نمایش قیمت',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'card_style' => 'archive' ),
			)
		);

		$this->add_control(
			'show_old_price',
			array(
				'label'        => 'قیمت خط‌خورده',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'card_style' => 'archive', 'show_price' => 'yes' ),
			)
		);

		$this->add_control(
			'show_button',
			array(
				'label'        => 'دکمه خرید',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'card_style' => 'archive' ),
			)
		);

		$this->add_control(
			'button_style',
			array(
				'label'     => 'شکل دکمه',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'   => 'دکمه تمام‌عرض',
					'outline' => 'دکمه خط‌دار',
					'fab'     => 'دکمه گرد روی تصویر',
				),
				'condition' => array( 'card_style' => 'archive', 'show_button' => 'yes' ),
			)
		);

		$this->add_control(
			'hover',
			array(
				'label'     => 'افکت هاور کارت',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'lift',
				'options'   => array(
					'lift' => 'بالا آمدن کارت',
					'zoom' => 'بزرگ‌نمایی تصویر',
					'none' => 'بدون افکت',
				),
				'condition' => array( 'card_style' => 'archive' ),
			)
		);

		$this->add_control(
			'align',
			array(
				'label'     => 'چیدمان محتوای کارت',
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => array(
					'start'  => array( 'title' => 'راست', 'icon' => 'eicon-text-align-right' ),
					'center' => array( 'title' => 'وسط', 'icon' => 'eicon-text-align-center' ),
				),
				'condition' => array( 'card_style' => 'archive' ),
			)
		);

		/* --------------------------------- شبکه (مشترک بین دو طرح) */

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
				'separator'      => 'before',
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

		$this->add_control(
			'even_rows',
			array(
				'label'        => 'هم‌ارتفاع کردن کارت‌ها',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => 'بله',
				'label_off'    => 'خیر',
				'return_value' => 'yes',
				'description'  => 'همهٔ ردیف‌ها هم‌قد می‌شوند، پس کارتی که امتیاز یا قیمتِ قبل ندارد کوتاه‌تر از بغل‌دستی‌اش نمی‌افتد.',
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
				// کارت آرشیو به‌جای ارتفاع ثابت، «نسبت تصویر» دارد.
				'condition'      => array( 'card_style' => 'ardal' ),
			)
		);

		$this->end_controls_section();

		$this->add_pager_controls();
		$this->add_card_style_controls();
	}

	/**
	 * صفحه‌بندی و حالت «نتیجه‌ای نیست».
	 *
	 * هر دو فقط روی کوئری آرشیو معنا دارند؛ کوئری دلخواه صفحه‌بندی ندارد و
	 * وقتی محصولی نباشد، ویجت در حالت‌های دیگر پیام ویرایشگر خودش را می‌دهد.
	 */
	private function add_pager_controls() {
		$this->start_controls_section(
			'section_pager',
			array(
				'label'     => 'صفحه‌بندی',
				'condition' => array( 'source' => 'archive' ),
			)
		);

		$this->add_control(
			'show_pager',
			array(
				'label'        => 'نمایش صفحه‌بندی',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => 'بله',
				'label_off'    => 'خیر',
				'return_value' => 'yes',
				'description'  => 'فقط وقتی ویجت روی کوئری واقعی آرشیو نشسته باشد چاپ می‌شود.',
			)
		);

		$this->add_control(
			'pager_mid',
			array(
				'label'     => 'تعداد صفحه اطراف صفحه جاری',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 5,
				'default'   => 2,
				'condition' => array( 'show_pager' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'pager_align',
			array(
				'label'     => 'چیدمان',
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => array(
					'flex-start' => array( 'title' => 'راست', 'icon' => 'eicon-text-align-right' ),
					'center'     => array( 'title' => 'وسط', 'icon' => 'eicon-text-align-center' ),
					'flex-end'   => array( 'title' => 'چپ', 'icon' => 'eicon-text-align-left' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .ard-pager ul.page-numbers' => 'justify-content:{{VALUE}};',
				),
				'condition' => array( 'show_pager' => 'yes' ),
			)
		);

		$this->add_control(
			'pager_size',
			array(
				'label'      => 'اندازه دکمه‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 28, 'max' => 64 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pager' => '--ard-pager-size:{{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'show_pager' => 'yes' ),
			)
		);

		$this->add_control(
			'pager_bg',
			array(
				'label'     => 'زمینه شماره‌ها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pager .page-numbers' => 'background:{{VALUE}};',
				),
				'condition' => array( 'show_pager' => 'yes' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'pager_color',
			array(
				'label'     => 'رنگ شماره‌ها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pager .page-numbers' => 'color:{{VALUE}};',
				),
				'condition' => array( 'show_pager' => 'yes' ),
			)
		);

		$this->add_control(
			'pager_active_bg',
			array(
				'label'     => 'زمینه صفحه جاری',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pager .page-numbers.current' => 'background:{{VALUE}};',
				),
				'condition' => array( 'show_pager' => 'yes' ),
			)
		);

		$this->add_control(
			'pager_active_color',
			array(
				'label'     => 'متن صفحه جاری',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pager .page-numbers.current' => 'color:{{VALUE}};',
				),
				'condition' => array( 'show_pager' => 'yes' ),
			)
		);

		$this->add_control(
			'empty_heading',
			array(
				'label'     => 'وقتی نتیجه‌ای نیست',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'empty_title',
			array(
				'label'       => 'عنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'محصولی با این فیلترها پیدا نشد.',
				'label_block' => true,
			)
		);

		$this->add_control(
			'empty_text',
			array(
				'label'       => 'توضیح',
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => 'یکی از فیلترها را بردارید یا بازه قیمت را بازتر کنید.',
				'label_block' => true,
			)
		);

		$this->add_control(
			'empty_btn',
			array(
				'label'       => 'متن دکمه پاک‌کردن فیلترها',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'پاک کردن همه فیلترها',
				'description' => 'فقط وقتی فیلتری فعال باشد نمایش داده می‌شود.',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * استایل کارت.
	 *
	 * هر کنترل هم‌زمان کارت اردال و کارت آرشیو را هدف می‌گیرد، پس با عوض‌کردن
	 * «طرح کارت» تنظیمات رنگی از دست نمی‌رود.
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
					'{{WRAPPER}} .ard-pcard__box, {{WRAPPER}} .ard-acard' => 'background:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_border',
			array(
				'label'     => 'رنگ حاشیه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__box, {{WRAPPER}} .ard-acard' => 'border-color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_border_hover',
			array(
				'label'     => 'رنگ حاشیه در هاور',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard:hover .ard-pcard__box, {{WRAPPER}} .ard-acard:hover' => 'border-color:{{VALUE}};',
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
					'{{WRAPPER}} .ard-pcard, {{WRAPPER}} .ard-pcard__box, {{WRAPPER}} .ard-acard' => 'border-radius:{{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .ard-pcard__box, {{WRAPPER}} .ard-acard' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .ard-pcard__box, {{WRAPPER}} .ard-acard' => 'gap:{{SIZE}}{{UNIT}};',
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
				'selector'   => '{{WRAPPER}} .ard-pcard__box, {{WRAPPER}} .ard-acard',
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
					'{{WRAPPER}} .ard-pcard__media, {{WRAPPER}} .ard-acard__media' => 'background:{{VALUE}};',
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
					'{{WRAPPER}} .ard-pcard__media, {{WRAPPER}} .ard-acard__media' => 'border-radius:{{SIZE}}{{UNIT}};',
				),
			)
		);

		// هر دو سبک کارت یک تصویر دارند، پس یک کنترل برای هر دو.
		$this->add_media_fit_control( array( '.ard-pcard__media img', '.ard-acard__media img' ) );

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
					'{{WRAPPER}} .ard-pcard__title, {{WRAPPER}} .ard-acard__name' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_hover',
			array(
				'label'     => 'رنگ عنوان در هاور',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__title a:hover, {{WRAPPER}} .ard-acard__name a:hover' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typo',
				'label'    => 'قلم عنوان',
				'selector' => '{{WRAPPER}} .ard-pcard__title, {{WRAPPER}} .ard-acard__name',
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
					'{{WRAPPER}} .ard-pcard__title, {{WRAPPER}} .ard-acard__name' => 'min-block-size:{{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .ard-pcard__price, {{WRAPPER}} .ard-acard__price' => 'color:{{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typo',
				'label'    => 'قلم قیمت',
				'selector' => '{{WRAPPER}} .ard-pcard__price, {{WRAPPER}} .ard-acard__price',
			)
		);

		$this->add_control(
			'old_price_color',
			array(
				'label'     => 'رنگ قیمت خط‌خورده',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__old, {{WRAPPER}} .ard-acard__old' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rate_color',
			array(
				'label'     => 'رنگ متن امتیاز',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__rate, {{WRAPPER}} .ard-acard__rating' => 'color:{{VALUE}};',
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
					'{{WRAPPER}} .ard-pcard__rate .ard-stars, {{WRAPPER}} .ard-acard__stars' => 'color:{{VALUE}};',
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
					'{{WRAPPER}} .ard-pcard .ard-badge--sale, {{WRAPPER}} .ard-acard__badge' => 'background:{{VALUE}};',
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
					'{{WRAPPER}} .ard-pcard .ard-badge--sale, {{WRAPPER}} .ard-acard__badge' => 'color:{{VALUE}};',
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
					'{{WRAPPER}} .ard-pcard__fab, {{WRAPPER}} .ard-pcard__btn, {{WRAPPER}} .ard-acard__fab, {{WRAPPER}} .ard-acard__btn' => 'background:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'btn_color',
			array(
				'label'     => 'رنگ متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__fab, {{WRAPPER}} .ard-pcard__btn, {{WRAPPER}} .ard-acard__fab, {{WRAPPER}} .ard-acard__btn' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'btn_border',
			array(
				'label'     => 'رنگ حاشیه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__btn, {{WRAPPER}} .ard-acard__btn' => 'border-color:{{VALUE}};',
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
					'{{WRAPPER}} .ard-pcard__fab:hover, {{WRAPPER}} .ard-pcard__btn:hover, {{WRAPPER}} .ard-acard__fab:hover, {{WRAPPER}} .ard-acard__btn:hover' => 'background:{{VALUE}};',
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
					'{{WRAPPER}} .ard-pcard__btn, {{WRAPPER}} .ard-acard__btn' => 'border-radius:{{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'variant' => 'plain' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'btn_typo',
				'selector'  => '{{WRAPPER}} .ard-pcard__btn, {{WRAPPER}} .ard-acard__btn',
				'condition' => array( 'variant' => 'plain' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$archive = 'archive' === $s['source'];
		$items   = $this->get_product_items( $s );

		if ( empty( $items ) ) {
			$this->render_heading();

			/*
			 * روی آرشیو، «چیزی پیدا نشد» یک حالت واقعیِ کاربر است نه خطای
			 * پیکربندی، پس همان کارتِ خالیِ آرشیو چاپ می‌شود — در سایت زنده هم،
			 * نه فقط در ویرایشگر.
			 */
			if ( $archive ) {
				printf(
					'<div class="ard-arch-products"%s>',
					$this->is_filtered( $s ) ? ' data-ard-region="products"' : ''
				);

				$this->render_grid_empty(
					array(
						'title'  => $s['empty_title'],
						'text'   => $s['empty_text'],
						'button' => $s['empty_btn'],
					)
				);

				echo '</div>';

				return;
			}

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

		$acard    = 'archive' === $s['card_style'];
		$filtered = $this->is_filtered( $s );

		// در حالت آرشیو، شبکه داخل یک قاب می‌نشیند تا صفحه‌بندی و حالت خالی هم
		// بخشی از همان ناحیه‌ی قابل تازه‌سازی باشند.
		if ( $archive ) {
			printf( '<div class="ard-arch-products"%s>', $filtered ? ' data-ard-region="products"' : '' );
		}

		$classes = array( 'ard-pgrid' );

		if ( 'yes' === $s['even_rows'] ) {
			$classes[] = 'ard-pgrid--even';
		}

		if ( $acard ) {
			// کلاس‌های .ard-grid همان مودیفایرهای کارت آرشیو را می‌آورند؛
			// ستون و فاصله را کنترل‌های خود ویجت روی .ard-pgrid می‌نویسند.
			$classes[] = 'ard-grid';
			$classes[] = 'ard-grid--' . $s['hover'];
			$classes[] = 'ard-grid--fit-' . ( 'cover' === $s['media_fit'] ? 'cover' : 'contain' );
			$classes[] = 'ard-grid--align-' . ( 'start' === $s['align'] ? 'start' : 'center' );
		} else {
			$classes[] = 'ard-pgrid--' . $s['variant'];
		}

		printf(
			'<div class="%s"%s%s>',
			esc_attr( implode( ' ', $classes ) ),
			! empty( $tabs ) ? ' data-ard-filterable' : '',
			( $filtered && ! $archive ) ? ' data-ard-region="products"' : ''
		);

		/*
		 * اگر هیچ محصولی قیمت قبل ندارد، سطرِ نامرئیِ رزرو هم لازم نیست؛ آن سطر
		 * فقط برای هم‌تراز ماندن با کارت‌های تخفیف‌دارِ همان ردیف است.
		 */
		$reserve_old  = false;
		$reserve_rate = false;

		foreach ( $items as $item ) {
			if ( ! $reserve_old && isset( $item['old_price'] ) && '' !== trim( (string) $item['old_price'] ) ) {
				$reserve_old = true;
			}

			$row_rating = isset( $item['rating'] ) ? $item['rating'] : 0;

			if ( is_array( $row_rating ) ) {
				$row_rating = isset( $row_rating['size'] ) ? $row_rating['size'] : 0;
			}

			if ( ! $reserve_rate && (float) $row_rating > 0 ) {
				$reserve_rate = true;
			}

			if ( $reserve_old && $reserve_rate ) {
				break;
			}
		}

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

			if ( $acard ) {
				$this->render_archive_card(
					$item,
					array(
						'tag'               => 'div',
						'image_ratio'       => $s['image_ratio'],
						'show_badge'        => 'yes' === $s['show_badge'],
						'badge_type'        => $s['badge_type'],
						'badge_text'        => $s['badge_text'],
						'show_stock'        => 'yes' === $s['show_stock'],
						'show_wishlist'     => 'yes' === $s['show_wishlist'],
						'show_rating'       => 'yes' === $s['show_rating'],
						'rating_style'      => $s['rating_style'],
						'hide_empty_rating' => 'yes' === $s['hide_empty_rating'],
						'show_cat'          => 'yes' === $s['show_cat'],
						'show_subtitle'     => 'yes' === $s['show_subtitle'],
						'title_tag'         => $s['card_title_tag'],
						'show_price'        => 'yes' === $s['show_price'],
						'show_old_price'    => 'yes' === $s['show_old_price'],
						'show_button'       => 'yes' === $s['show_button'],
						'button_style'      => $s['button_style'],
						'button_text'       => $s['button_text'],
						'button_action'     => $s['button_action'],
						'placeholder'       => '۸۰۰×۸۰۰',
					)
				);
			} else {
				$this->render_product_card(
					$item,
					array(
						'variant'       => $s['variant'],
						'link_mode'     => $s['link_mode'],
						'button_action' => $s['button_action'],
						'show_rating'   => 'yes' === $s['show_rating'],
						'show_fab'      => $framed,
						'show_button'   => ! $framed,
						'button_text'   => $s['button_text'],
						'notch'         => $framed && 'yes' === $s['notch'],
						'placeholder'   => '۸۰۰×۸۰۰',
						'reserve_old'   => $reserve_old,
						'reserve_rate'  => $reserve_rate,
						'title_tag'     => $s['card_title_tag'],
					)
				);
			}

			echo '</div>';
		}

		echo '</div>';

		if ( $archive && 'yes' === $s['show_pager'] && $this->archive_query instanceof WP_Query ) {
			$this->render_grid_pager( $this->archive_query, array( 'mid' => $s['pager_mid'] ) );
		}

		if ( $archive ) {
			echo '</div>';
		}
	}

	/**
	 * آیا محتوای این شبکه به فیلترهای آدرس وابسته است؟
	 *
	 * فقط در این حالت ناحیه برای بارگذاری ایجکسی نشان‌دار می‌شود.
	 *
	 * @param array $s
	 * @return bool
	 */
	private function is_filtered( $s ) {
		return 'archive' === $s['source'] || 'yes' === ( $s['respect_filters'] ?? '' );
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
