<?php
/**
 * ویجت حلقهٔ برندها — شبکه، اسلایدر و مارکیِ بی‌پایان.
 *
 * برندها یا از تاکسونومی برند ووکامرس (و افزونه‌های رایج برند) خوانده
 * می‌شوند یا دستی وارد می‌شوند. نوع چیدمان خودش ریسپانسیو است: مقدارش به
 * صورت متغیر CSS (--ard-br-mode) در هر بریک‌پوینت نوشته می‌شود و اسکریپت
 * ویجت از روی همان تصمیم می‌گیرد کدام حالت را راه بیندازد.
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

class Ardal_Widget_Brand_Rings extends Ardal_Widget_Base {

	/**
	 * تاکسونومی‌های برند که به ترتیب امتحان می‌شوند؛ همان فهرست
	 * Ardal_Product::brand() تا هر دو یک برند را ببینند.
	 */
	const TAXONOMIES = array( 'product_brand', 'pwb-brand', 'yith_product_brand', 'brand' );

	public function get_name() {
		return 'ardal-brand-rings';
	}

	public function get_title() {
		return 'اردال — حلقه برندها';
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_keywords() {
		return array( 'اردال', 'برند', 'حلقه', 'مارکی', 'اسلایدر', 'brands', 'marquee', 'logo', 'carousel' );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-brand-rings' );
	}

	public function get_script_depends() {
		return array( 'ardal', 'ardal-brand-rings' );
	}

	protected function register_controls() {
		$this->add_heading_controls( 'برندهای محبوب', 'accent' );
		$this->register_source_controls();
		$this->register_display_controls();
		$this->register_layout_controls();
		$this->register_grid_controls();
		$this->register_slider_controls();
		$this->register_marquee_controls();

		$this->register_ring_style_controls();
		$this->register_logo_style_controls();
		$this->register_card_style_controls();
		$this->register_text_style_controls();
		$this->register_track_style_controls();
		$this->register_nav_style_controls();
	}

	/* --------------------------------------------------------------------- */
	/* محتوا                                                                   */
	/* --------------------------------------------------------------------- */

	private function register_source_controls() {
		$this->start_controls_section(
			'section_source',
			array( 'label' => 'برندها' )
		);

		$taxonomies = $this->available_taxonomies();

		$this->add_control(
			'source',
			array(
				'label'   => 'منبع',
				'type'    => Controls_Manager::SELECT,
				'default' => $taxonomies ? 'woo' : 'manual',
				'options' => array(
					'woo'    => $taxonomies ? 'برندهای ووکامرس' : 'برندهای ووکامرس (تاکسونومی برند پیدا نشد)',
					'manual' => 'دستی',
				),
			)
		);

		$tax_options = array( 'auto' => 'خودکار' );

		foreach ( $taxonomies as $taxonomy ) {
			$object                    = get_taxonomy( $taxonomy );
			$tax_options[ $taxonomy ] = ( $object ? $object->labels->name : $taxonomy ) . ' (' . $taxonomy . ')';
		}

		$this->add_control(
			'woo_taxonomy',
			array(
				'label'       => 'تاکسونومی برند',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'auto',
				'options'     => $tax_options,
				'description' => 'در حالت خودکار، برند خود ووکامرس و بعد افزونه‌های Perfect Brands و YITH امتحان می‌شوند.',
				'condition'   => array( 'source' => 'woo' ),
			)
		);

		$this->add_control(
			'woo_limit',
			array(
				'label'     => 'حداکثر تعداد',
				'type'      => Controls_Manager::NUMBER,
				'default'   => 12,
				'min'       => 1,
				'max'       => 100,
				'condition' => array( 'source' => 'woo' ),
			)
		);

		$this->add_control(
			'woo_include',
			array(
				'label'       => 'فقط این برندها',
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->brand_options(),
				'default'     => array(),
				'label_block' => true,
				'description' => 'خالی بماند همه برندها می‌آیند. اگر انتخاب کنید، به همین ترتیب انتخاب نمایش داده می‌شوند.',
				'condition'   => array( 'source' => 'woo' ),
			)
		);

		$this->add_control(
			'woo_exclude',
			array(
				'label'       => 'برندهایی که نمایش داده نشوند',
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->brand_options(),
				'default'     => array(),
				'label_block' => true,
				'condition'   => array( 'source' => 'woo' ),
			)
		);

		$this->add_control(
			'woo_orderby',
			array(
				'label'     => 'مرتب‌سازی',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'count',
				'options'   => array(
					'count'      => 'تعداد محصول',
					'name'       => 'نام',
					'menu_order' => 'ترتیب دستی ووکامرس',
					'term_id'    => 'تاریخ ایجاد',
					'rand'       => 'تصادفی',
				),
				'condition' => array( 'source' => 'woo' ),
			)
		);

		$this->add_control(
			'woo_order',
			array(
				'label'     => 'جهت مرتب‌سازی',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'DESC',
				'options'   => array(
					'DESC' => 'نزولی',
					'ASC'  => 'صعودی',
				),
				'condition' => array(
					'source'       => 'woo',
					'woo_orderby!' => 'rand',
				),
			)
		);

		$this->add_control(
			'woo_hide_empty',
			array(
				'label'        => 'برندهای بدون محصول نمایش داده نشوند',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'source' => 'woo' ),
			)
		);

		$this->add_control(
			'woo_only_logo',
			array(
				'label'        => 'فقط برندهای دارای لوگو',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'condition'    => array( 'source' => 'woo' ),
			)
		);

		$this->add_control(
			'woo_link',
			array(
				'label'        => 'لینک به صفحه برند',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'source' => 'woo' ),
			)
		);

		$this->add_control(
			'woo_new_tab',
			array(
				'label'        => 'باز شدن در تب جدید',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'condition'    => array(
					'source'   => 'woo',
					'woo_link' => 'yes',
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			array(
				'label'       => 'نام برند',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'برند',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'logo',
			array(
				'label' => 'لوگو',
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$repeater->add_control(
			'count',
			array(
				'label'       => 'تعداد کالا',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'مثلاً ۲۴۰',
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
				'label'       => 'برندها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'condition'   => array( 'source' => 'manual' ),
				'default'     => array(
					array( 'title' => 'نایک' ),
					array( 'title' => 'آدیداس' ),
					array( 'title' => 'پوما' ),
					array( 'title' => 'ریباک' ),
					array( 'title' => 'آندر آرمور' ),
					array( 'title' => 'نیوبالانس' ),
					array( 'title' => 'اسیکس' ),
					array( 'title' => 'فیلا' ),
				),
			)
		);

		$this->add_control(
			'image_size',
			array(
				'label'     => 'اندازه تصویر لوگو',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'medium',
				'options'   => array(
					'thumbnail'    => 'بندانگشتی',
					'medium'       => 'متوسط',
					'medium_large' => 'متوسط بزرگ',
					'large'        => 'بزرگ',
					'full'         => 'اصلی',
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();
	}

	private function register_display_controls() {
		$this->start_controls_section(
			'section_display',
			array( 'label' => 'اجزای هر برند' )
		);

		$this->add_control(
			'show_name',
			array(
				'label'        => 'نمایش نام برند',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_tag_control(
			'name_tag',
			'تگ نام برند',
			'span',
			array( 'condition' => array( 'show_name' => 'yes' ) )
		);

		$this->add_control(
			'show_count',
			array(
				'label'        => 'نمایش تعداد کالا',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'count_suffix',
			array(
				'label'     => 'پسوند تعداد',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'کالا',
				'condition' => array( 'show_count' => 'yes' ),
			)
		);

		$this->add_control(
			'fallback_name',
			array(
				'label'        => 'نام برند به‌جای لوگوی خالی',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'برندی که لوگو ندارد، نامش داخل حلقه نوشته می‌شود.',
			)
		);

		$this->end_controls_section();
	}

	private function register_layout_controls() {
		$this->start_controls_section(
			'section_layout',
			array( 'label' => 'چیدمان' )
		);

		/*
		 * نوع چیدمان ریسپانسیو است. به‌جای کلاس، متغیر CSS نوشته می‌شود تا
		 * المنتور خودش آن را در مدیاکوئری هر بریک‌پوینت بگذارد و اسکریپت با
		 * خواندن همان مقدار بفهمد در این عرض کدام حالت فعال است.
		 * render_type=template لازم است تا ویرایشگر ویجت را از نو بسازد.
		 */
		$this->add_responsive_control(
			'layout',
			array(
				'label'       => 'نوع چیدمان',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'grid',
				'options'     => array(
					'grid'    => 'شبکه (چند ردیف)',
					'slider'  => 'اسلایدر (فلش، نقطه، کشیدن)',
					'marquee' => 'مارکی (حرکت پیوسته و بی‌پایان)',
				),
				'label_block' => true,
				'render_type' => 'template',
				'selectors'   => array(
					'{{WRAPPER}} .ard-br' => '--ard-br-mode:{{VALUE}};',
				),
				'description' => 'برای هر دستگاه جداگانه قابل انتخاب است؛ مثلاً شبکه در دسکتاپ و مارکی در موبایل.',
			)
		);

		$this->end_controls_section();
	}

	private function register_grid_controls() {
		$this->start_controls_section(
			'section_grid',
			array(
				'label'      => 'تنظیمات شبکه',
				'conditions' => $this->mode_conditions( 'grid' ),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => 'تعداد ستون',
				'type'           => Controls_Manager::NUMBER,
				'min'            => 1,
				'max'            => 12,
				'default'        => 6,
				'tablet_default' => 4,
				'mobile_default' => 3,
				'selectors'      => array(
					'{{WRAPPER}} .ard-br' => '--ard-br-cols:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'grid_gap_x',
			array(
				'label'      => 'فاصله افقی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 80 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-br' => '--ard-br-gap-x:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'grid_gap_y',
			array(
				'label'      => 'فاصله عمودی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 80 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-br' => '--ard-br-gap-y:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'grid_justify',
			array(
				'label'     => 'چیدمان ردیف ناقص',
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'start'  => array(
						'title' => 'ابتدا',
						'icon'  => 'eicon-h-align-right',
					),
					'center' => array(
						'title' => 'وسط',
						'icon'  => 'eicon-h-align-center',
					),
					'end'    => array(
						'title' => 'انتها',
						'icon'  => 'eicon-h-align-left',
					),
				),
				'description' => 'وقتی ردیف آخر کامل نیست، برندها کجای ردیف بنشینند.',
				'selectors'   => array(
					'{{WRAPPER}} .ard-br' => '--ard-br-grid-justify:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	private function register_slider_controls() {
		$this->start_controls_section(
			'section_slider',
			array(
				'label'      => 'تنظیمات اسلایدر',
				'conditions' => $this->mode_conditions( 'slider' ),
			)
		);

		$this->add_responsive_control(
			'per_view',
			array(
				'label'          => 'تعداد در هر نما',
				'type'           => Controls_Manager::NUMBER,
				'min'            => 1,
				'max'            => 12,
				'step'           => 1,
				'default'        => 6,
				'tablet_default' => 4,
				'mobile_default' => 3,
				'selectors'      => array(
					'{{WRAPPER}} .ard-br' => '--ard-br-per:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'slider_gap',
			array(
				'label'      => 'فاصله بین برندها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 80 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-br' => '--ard-br-gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'slider_step',
			array(
				'label'   => 'حرکت با هر کلیک',
				'type'    => Controls_Manager::SELECT,
				'default' => 'page',
				'options' => array(
					'page' => 'یک نمای کامل',
					'item' => 'یک برند',
				),
			)
		);

		$this->add_control(
			'slider_loop',
			array(
				'label'        => 'بازگشت به ابتدا در انتها',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'slider_drag',
			array(
				'label'        => 'کشیدن با ماوس',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'کشیدن با انگشت در موبایل همیشه فعال است.',
			)
		);

		$this->add_control(
			'slider_arrows',
			array(
				'label'        => 'فلش‌ها',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_responsive_control(
			'slider_arrows_show',
			array(
				'label'                => 'نمایش فلش در این دستگاه',
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'show',
				'mobile_default'       => 'hide',
				'options'              => array(
					'show' => 'نمایش',
					'hide' => 'پنهان',
				),
				'selectors_dictionary' => array(
					'show' => 'flex',
					'hide' => 'none',
				),
				'selectors'            => array(
					'{{WRAPPER}} .ard-br' => '--ard-br-arrows:{{VALUE}};',
				),
				'condition'            => array( 'slider_arrows' => 'yes' ),
			)
		);

		$this->add_control(
			'slider_dots',
			array(
				'label'        => 'نقطه‌ها',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_responsive_control(
			'slider_dots_show',
			array(
				'label'                => 'نمایش نقطه در این دستگاه',
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'show',
				'options'              => array(
					'show' => 'نمایش',
					'hide' => 'پنهان',
				),
				'selectors_dictionary' => array(
					'show' => 'flex',
					'hide' => 'none',
				),
				'selectors'            => array(
					'{{WRAPPER}} .ard-br' => '--ard-br-dots:{{VALUE}};',
				),
				'condition'            => array( 'slider_dots' => 'yes' ),
			)
		);

		$this->add_control(
			'slider_autoplay',
			array(
				'label'        => 'چرخش خودکار',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'slider_interval',
			array(
				'label'     => 'فاصله چرخش (ثانیه)',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 30,
				'step'      => 0.5,
				'default'   => 4,
				'condition' => array( 'slider_autoplay' => 'yes' ),
			)
		);

		$this->add_control(
			'slider_pause_hover',
			array(
				'label'        => 'توقف با رفتن ماوس روی اسلایدر',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'slider_autoplay' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	private function register_marquee_controls() {
		$this->start_controls_section(
			'section_marquee',
			array(
				'label'      => 'تنظیمات مارکی',
				'conditions' => $this->mode_conditions( 'marquee' ),
			)
		);

		$this->add_responsive_control(
			'mq_speed',
			array(
				'label'          => 'سرعت (پیکسل در ثانیه)',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 5, 'max' => 400 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 45 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 35 ),
				// بدون واحد نوشته می‌شود؛ اسکریپت آن را به‌عنوان عدد می‌خواند.
				'selectors'      => array(
					'{{WRAPPER}} .ard-br' => '--ard-br-speed:{{SIZE}};',
				),
			)
		);

		$this->add_control(
			'mq_direction',
			array(
				'label'   => 'جهت حرکت',
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'right',
				'toggle'  => false,
				'options' => array(
					'left'  => array(
						'title' => 'به سمت چپ',
						'icon'  => 'eicon-arrow-left',
					),
					'right' => array(
						'title' => 'به سمت راست',
						'icon'  => 'eicon-arrow-right',
					),
				),
			)
		);

		$this->add_control(
			'mq_hover',
			array(
				'label'   => 'رفتار با رفتن ماوس روی نوار',
				'type'    => Controls_Manager::SELECT,
				'default' => 'slow',
				'options' => array(
					'none'  => 'بدون تغییر',
					'slow'  => 'آهسته شود',
					'pause' => 'متوقف شود',
				),
			)
		);

		$this->add_control(
			'mq_hover_factor',
			array(
				'label'      => 'سرعت در هاور (درصد)',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array( '%' => array( 'min' => 5, 'max' => 90 ) ),
				'default'    => array( 'unit' => '%', 'size' => 25 ),
				'condition'  => array( 'mq_hover' => 'slow' ),
			)
		);

		$this->add_control(
			'mq_drag',
			array(
				'label'        => 'کشیدن با ماوس و انگشت',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'بعد از رها کردن، نوار با اینرسی به حرکت عادی‌اش برمی‌گردد.',
			)
		);

		$this->add_responsive_control(
			'mq_gap',
			array(
				'label'      => 'فاصله بین برندها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 120 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-br' => '--ard-br-mq-gap:{{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'mq_item',
			array(
				'label'       => 'عرض هر برند',
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array( 'px' => array( 'min' => 40, 'max' => 400 ) ),
				'description' => 'خالی بماند، کمی پهن‌تر از قطر حلقه در نظر گرفته می‌شود.',
				'selectors'   => array(
					'{{WRAPPER}} .ard-br' => '--ard-br-mq-item:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'mq_fade',
			array(
				'label'        => 'محو شدن لبه‌ها',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'prefix_class' => 'ard-br-fade-',
				'separator'    => 'before',
			)
		);

		$this->add_responsive_control(
			'mq_fade_size',
			array(
				'label'      => 'پهنای محوشدگی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array( 'min' => 0, 'max' => 300 ),
					'%'  => array( 'min' => 0, 'max' => 40 ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .ard-br' => '--ard-br-fade:{{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'mq_fade' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	/* --------------------------------------------------------------------- */
	/* استایل                                                                  */
	/* --------------------------------------------------------------------- */

	private function register_ring_style_controls() {
		$this->start_controls_section(
			'section_style_ring',
			array(
				'label' => 'حلقه',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'variant',
			array(
				'label'   => 'سبک حلقه',
				'type'    => Controls_Manager::SELECT,
				'default' => 'tint',
				'options' => array(
					'tint'     => 'زمینه ملایم با حاشیه',
					'outline'  => 'فقط حاشیه',
					'gradient' => 'حلقه گرادیانی خطی',
					'conic'    => 'حلقه گرادیانی چرخشی (استوری)',
					'plain'    => 'بدون قاب (فقط لوگو)',
				),
			)
		);

		$this->add_control(
			'ring_alt',
			array(
				'label'        => 'رنگ‌بندی یک‌درمیان',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'condition'    => array( 'variant' => array( 'tint', 'outline' ) ),
			)
		);

		$this->add_responsive_control(
			'ring_size',
			array(
				'label'          => 'اندازه حلقه',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', 'em', 'rem', 'vw' ),
				'range'          => array(
					'px' => array( 'min' => 32, 'max' => 320 ),
					'vw' => array( 'min' => 5, 'max' => 50 ),
				),
				'default'        => array( 'unit' => 'px', 'size' => 110 ),
				'tablet_default' => array( 'unit' => 'px', 'size' => 96 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 76 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-br' => '--ard-br-ring:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'ring_ratio',
			array(
				'label'       => 'نسبت عرض به ارتفاع',
				'type'        => Controls_Manager::SLIDER,
				'range'       => array( 'px' => array( 'min' => 0.5, 'max' => 3, 'step' => 0.05 ) ),
				'description' => '۱ یعنی دایره یا مربع؛ برای لوگوهای کشیده عدد بزرگ‌تر بدهید.',
				'selectors'   => array(
					'{{WRAPPER}} .ard-br' => '--ard-br-ratio:{{SIZE}};',
				),
			)
		);

		$this->add_responsive_control(
			'ring_radius',
			array(
				'label'      => 'گردی گوشه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'%'  => array( 'min' => 0, 'max' => 50 ),
					'px' => array( 'min' => 0, 'max' => 160 ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .ard-br' => '--ard-br-radius:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'ring_bw',
			array(
				'label'      => 'ضخامت حاشیه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 12 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-br' => '--ard-br-bw:{{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'variant!' => 'plain' ),
			)
		);

		$this->add_control(
			'ring_bstyle',
			array(
				'label'     => 'نوع خط حاشیه',
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''       => 'پیش‌فرض',
					'solid'  => 'ساده',
					'dashed' => 'خط‌چین',
					'dotted' => 'نقطه‌چین',
					'double' => 'دوخطی',
				),
				'selectors' => array(
					'{{WRAPPER}} .ard-br' => '--ard-br-bstyle:{{VALUE}};',
				),
				'condition' => array( 'variant!' => 'plain' ),
			)
		);

		$this->add_responsive_control(
			'ring_pad',
			array(
				'label'       => 'ضخامت حلقه (فاصله تا لوگو)',
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array( 'px' => array( 'min' => 0, 'max' => 30 ) ),
				'selectors'   => array(
					'{{WRAPPER}} .ard-br' => '--ard-br-pad:{{SIZE}}{{UNIT}};',
				),
				'condition'   => array( 'variant!' => 'plain' ),
			)
		);

		$this->start_controls_tabs( 'tabs_ring' );

		$this->start_controls_tab( 'tab_ring_normal', array( 'label' => 'عادی' ) );

		$this->add_control(
			'ring_line',
			array(
				'label'     => 'رنگ حاشیه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br' => '--ard-br-line:{{VALUE}};' ),
				'condition' => array( 'variant!' => 'plain' ),
			)
		);

		$this->add_control(
			'ring_bg',
			array(
				'label'     => 'رنگ زمینه حلقه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br' => '--ard-br-bg:{{VALUE}};' ),
				'condition' => array( 'variant' => array( 'tint', 'outline' ) ),
			)
		);

		$this->add_control(
			'ring_line_alt',
			array(
				'label'     => 'رنگ حاشیه (یک‌درمیان)',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br' => '--ard-br-line-alt:{{VALUE}};' ),
				'condition' => array(
					'variant'  => array( 'tint', 'outline' ),
					'ring_alt' => 'yes',
				),
			)
		);

		$this->add_control(
			'ring_bg_alt',
			array(
				'label'     => 'رنگ زمینه (یک‌درمیان)',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br' => '--ard-br-bg-alt:{{VALUE}};' ),
				'condition' => array(
					'variant'  => array( 'tint', 'outline' ),
					'ring_alt' => 'yes',
				),
			)
		);

		$this->add_control(
			'grad_1',
			array(
				'label'     => 'رنگ اول گرادیان',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br' => '--ard-br-g1:{{VALUE}};' ),
				'condition' => array( 'variant' => array( 'gradient', 'conic' ) ),
			)
		);

		$this->add_control(
			'grad_2',
			array(
				'label'     => 'رنگ دوم گرادیان',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br' => '--ard-br-g2:{{VALUE}};' ),
				'condition' => array( 'variant' => array( 'gradient', 'conic' ) ),
			)
		);

		$this->add_control(
			'grad_3',
			array(
				'label'     => 'رنگ سوم گرادیان',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br' => '--ard-br-g3:{{VALUE}};' ),
				'condition' => array( 'variant' => 'conic' ),
			)
		);

		$this->add_control(
			'grad_angle',
			array(
				'label'      => 'زاویه گرادیان',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'deg' ),
				'range'      => array( 'deg' => array( 'min' => 0, 'max' => 360 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-br' => '--ard-br-gangle:{{SIZE}}deg;' ),
				'condition'  => array( 'variant' => array( 'gradient', 'conic' ) ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'ring_shadow',
				'label'    => 'سایه حلقه',
				'selector' => '{{WRAPPER}} .ard-br__ring',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_ring_hover', array( 'label' => 'هاور' ) );

		$this->add_control(
			'hover_fx',
			array(
				'label'        => 'افکت هاور',
				'type'         => Controls_Manager::SELECT,
				'default'      => 'lift',
				'options'      => array(
					'none' => 'بدون افکت',
					'lift' => 'بالا آمدن حلقه',
					'zoom' => 'بزرگ شدن لوگو',
					'spin' => 'چرخش رنگ حلقه',
					'glow' => 'هاله رنگی دور حلقه',
				),
				'prefix_class' => 'ard-br-fx-',
			)
		);

		$this->add_responsive_control(
			'hover_lift',
			array(
				'label'      => 'میزان بالا آمدن',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 20 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-br' => '--ard-br-lift:-{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'hover_fx' => 'lift' ),
			)
		);

		$this->add_control(
			'hover_zoom',
			array(
				'label'     => 'ضریب بزرگ‌نمایی',
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 1, 'max' => 1.5, 'step' => 0.01 ) ),
				'selectors' => array( '{{WRAPPER}} .ard-br' => '--ard-br-zoom:{{SIZE}};' ),
				'condition' => array( 'hover_fx' => 'zoom' ),
			)
		);

		$this->add_control(
			'ring_line_hover',
			array(
				'label'     => 'رنگ حاشیه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br' => '--ard-br-line-hover:{{VALUE}};' ),
				'condition' => array( 'variant!' => 'plain' ),
			)
		);

		$this->add_control(
			'ring_bg_hover',
			array(
				'label'     => 'رنگ زمینه حلقه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br' => '--ard-br-bg-hover:{{VALUE}};' ),
				'condition' => array( 'variant' => array( 'tint', 'outline' ) ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'ring_shadow_hover',
				'label'    => 'سایه حلقه',
				'selector' => '{{WRAPPER}} .ard-br__card:hover .ard-br__ring, {{WRAPPER}} .ard-br__card:focus-visible .ard-br__ring',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'spin_always',
			array(
				'label'        => 'چرخش دائمی رنگ حلقه',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'prefix_class' => 'ard-br-spin-',
				'separator'    => 'before',
				'condition'    => array( 'variant' => array( 'gradient', 'conic' ) ),
			)
		);

		$this->add_control(
			'spin_duration',
			array(
				'label'     => 'مدت هر دور چرخش (ثانیه)',
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0.5, 'max' => 20, 'step' => 0.5 ) ),
				'selectors' => array( '{{WRAPPER}} .ard-br' => '--ard-br-spin-dur:{{SIZE}}s;' ),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'  => 'spin_always',
							'value' => 'yes',
						),
						array(
							'name'  => 'hover_fx',
							'value' => 'spin',
						),
					),
				),
			)
		);

		$this->add_control(
			'transition',
			array(
				'label'     => 'مدت انیمیشن هاور (ثانیه)',
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 2, 'step' => 0.05 ) ),
				'selectors' => array( '{{WRAPPER}} .ard-br' => '--ard-br-dur:{{SIZE}}s;' ),
			)
		);

		$this->end_controls_section();
	}

	private function register_logo_style_controls() {
		$this->start_controls_section(
			'section_style_logo',
			array(
				'label' => 'لوگو',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'logo_size',
			array(
				'label'      => 'اندازه لوگو داخل حلقه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array( '%' => array( 'min' => 20, 'max' => 100 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-br' => '--ard-br-logo:{{SIZE}}%;' ),
			)
		);

		$this->add_control(
			'logo_fit',
			array(
				'label'     => 'نحوه قرارگیری',
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''        => 'پیش‌فرض (کامل دیده شود)',
					'contain' => 'کامل دیده شود',
					'cover'   => 'حلقه را پر کند',
				),
				'selectors' => array( '{{WRAPPER}} .ard-br' => '--ard-br-fit:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'disc_bg',
			array(
				'label'     => 'زمینه پشت لوگو',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br' => '--ard-br-disc:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'disc_bg_hover',
			array(
				'label'     => 'زمینه پشت لوگو در هاور',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br' => '--ard-br-disc-hover:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'disc_radius',
			array(
				'label'       => 'گردی زمینه پشت لوگو',
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '%', 'px' ),
				'range'       => array(
					'%'  => array( 'min' => 0, 'max' => 50 ),
					'px' => array( 'min' => 0, 'max' => 160 ),
				),
				'description' => 'خالی بماند، از گردی حلقه پیروی می‌کند.',
				'selectors'   => array( '{{WRAPPER}} .ard-br' => '--ard-br-disc-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'logo_gray',
			array(
				'label'        => 'لوگوها سیاه‌وسفید',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'prefix_class' => 'ard-br-gray-',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'logo_gray_hover',
			array(
				'label'        => 'رنگی شدن در هاور',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'prefix_class' => 'ard-br-grayhover-',
				'condition'    => array( 'logo_gray' => 'yes' ),
			)
		);

		$this->add_control(
			'logo_opacity',
			array(
				'label'     => 'شفافیت لوگو',
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0.1, 'max' => 1, 'step' => 0.05 ) ),
				'selectors' => array( '{{WRAPPER}} .ard-br' => '--ard-br-op:{{SIZE}};' ),
			)
		);

		$this->add_control(
			'logo_opacity_hover',
			array(
				'label'     => 'شفافیت لوگو در هاور',
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0.1, 'max' => 1, 'step' => 0.05 ) ),
				'selectors' => array( '{{WRAPPER}} .ard-br' => '--ard-br-op-hover:{{SIZE}};' ),
			)
		);

		$this->add_control(
			'fallback_heading',
			array(
				'label'     => 'نام به‌جای لوگو',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'fallback_name' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'fallback_typo',
				'selector'  => '{{WRAPPER}} .ard-br__initial',
				'condition' => array( 'fallback_name' => 'yes' ),
			)
		);

		$this->add_control(
			'fallback_color',
			array(
				'label'     => 'رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br__initial' => 'color:{{VALUE}};' ),
				'condition' => array( 'fallback_name' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	private function register_card_style_controls() {
		$this->start_controls_section(
			'section_style_card',
			array(
				'label' => 'کارت هر برند',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'card_align',
			array(
				'label'                => 'چیدمان',
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'start'  => array(
						'title' => 'راست',
						'icon'  => 'eicon-text-align-right',
					),
					'center' => array(
						'title' => 'وسط',
						'icon'  => 'eicon-text-align-center',
					),
					'end'    => array(
						'title' => 'چپ',
						'icon'  => 'eicon-text-align-left',
					),
				),
				'selectors_dictionary' => array(
					'start'  => '--ard-br-align:flex-start;--ard-br-text-align:start;',
					'center' => '--ard-br-align:center;--ard-br-text-align:center;',
					'end'    => '--ard-br-align:flex-end;--ard-br-text-align:end;',
				),
				'selectors'            => array(
					'{{WRAPPER}} .ard-br' => '{{VALUE}}',
				),
			)
		);

		$this->add_control(
			'card_direction',
			array(
				'label'        => 'چیدمان حلقه و نام',
				'type'         => Controls_Manager::SELECT,
				'default'      => 'column',
				'options'      => array(
					'column' => 'نام زیر حلقه',
					'row'    => 'نام کنار حلقه',
				),
				'prefix_class' => 'ard-br-dir-',
			)
		);

		$this->add_responsive_control(
			'card_gap',
			array(
				'label'      => 'فاصله حلقه تا نام',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-br' => '--ard-br-inner-gap:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'card_pad',
			array(
				'label'      => 'فاصله داخلی کارت',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-br__card' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_radius',
			array(
				'label'      => 'گردی گوشه کارت',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-br__card' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_card' );

		$this->start_controls_tab( 'tab_card_normal', array( 'label' => 'عادی' ) );

		$this->add_control(
			'card_bg',
			array(
				'label'     => 'زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br__card' => 'background-color:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} .ard-br__card',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_shadow',
				'selector' => '{{WRAPPER}} .ard-br__card',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_card_hover', array( 'label' => 'هاور' ) );

		$this->add_control(
			'card_bg_hover',
			array(
				'label'     => 'زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br__card:hover, {{WRAPPER}} .ard-br__card:focus-visible' => 'background-color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'card_border_hover',
			array(
				'label'     => 'رنگ حاشیه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br__card:hover, {{WRAPPER}} .ard-br__card:focus-visible' => 'border-color:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_shadow_hover',
				'selector' => '{{WRAPPER}} .ard-br__card:hover, {{WRAPPER}} .ard-br__card:focus-visible',
			)
		);

		$this->add_responsive_control(
			'card_lift',
			array(
				'label'      => 'بالا آمدن کارت',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 20 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-br__card:hover, {{WRAPPER}} .ard-br__card:focus-visible' => 'transform:translateY(-{{SIZE}}{{UNIT}});',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function register_text_style_controls() {
		$this->start_controls_section(
			'section_style_name',
			array(
				'label'     => 'نام برند',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_name' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'name_typo',
				'selector' => '{{WRAPPER}} .ard-br__name',
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => 'رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br' => '--ard-br-name:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'name_color_hover',
			array(
				'label'     => 'رنگ در هاور',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br' => '--ard-br-name-hover:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'name_lines',
			array(
				'label'     => 'حداکثر خط',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 4,
				'selectors' => array( '{{WRAPPER}} .ard-br' => '--ard-br-lines:{{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_count',
			array(
				'label'     => 'تعداد کالا',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_count' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'count_typo',
				'selector' => '{{WRAPPER}} .ard-br__count',
			)
		);

		$this->add_control(
			'count_color',
			array(
				'label'     => 'رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br__count' => 'color:{{VALUE}};' ),
			)
		);

		$this->end_controls_section();
	}

	private function register_track_style_controls() {
		$this->start_controls_section(
			'section_style_track',
			array(
				'label' => 'قاب نمایش',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'track_vpad',
			array(
				'label'       => 'فاصله عمودی نوار',
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'description' => 'در اسلایدر و مارکی جا برای سایه و بالا آمدن حلقه در هاور باز می‌کند تا بریده نشود.',
				'selectors'   => array( '{{WRAPPER}} .ard-br' => '--ard-br-vpad:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'track_bg',
			array(
				'label'     => 'زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br__viewport' => 'background-color:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'track_radius',
			array(
				'label'      => 'گردی گوشه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-br__viewport' => 'border-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'track_pad_x',
			array(
				'label'      => 'فاصله افقی داخل قاب',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-br' => '--ard-br-hpad:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();
	}

	private function register_nav_style_controls() {
		$this->start_controls_section(
			'section_style_nav',
			array(
				'label'      => 'فلش‌ها و نقطه‌ها',
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => $this->mode_conditions( 'slider' ),
			)
		);

		$this->add_responsive_control(
			'arrow_size',
			array(
				'label'      => 'اندازه فلش',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 20, 'max' => 72 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-br' => '--ard-br-arrow:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'slider_arrows' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'arrow_icon',
			array(
				'label'      => 'اندازه آیکون',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 8, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-br' => '--ard-br-arrow-icon:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'slider_arrows' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'arrow_radius',
			array(
				'label'      => 'گردی فلش',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'%'  => array( 'min' => 0, 'max' => 50 ),
					'px' => array( 'min' => 0, 'max' => 36 ),
				),
				'selectors'  => array( '{{WRAPPER}} .ard-br__arrow[class]' => 'border-radius:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'slider_arrows' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'arrow_x',
			array(
				'label'      => 'فاصله از لبه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => -60, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-br' => '--ard-br-arrow-x:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'slider_arrows' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'arrow_y',
			array(
				'label'       => 'موقعیت عمودی',
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', '%' ),
				'range'       => array(
					'px' => array( 'min' => 0, 'max' => 300 ),
					'%'  => array( 'min' => 0, 'max' => 100 ),
				),
				'description' => 'خالی بماند، روی مرکز حلقه‌ها قرار می‌گیرد.',
				'selectors'   => array( '{{WRAPPER}} .ard-br' => '--ard-br-arrow-y:{{SIZE}}{{UNIT}};' ),
				'condition'   => array( 'slider_arrows' => 'yes' ),
			)
		);

		$this->start_controls_tabs(
			'tabs_arrow',
			array( 'condition' => array( 'slider_arrows' => 'yes' ) )
		);

		$this->start_controls_tab( 'tab_arrow_normal', array( 'label' => 'عادی' ) );

		$this->add_control(
			'arrow_color',
			array(
				'label'     => 'رنگ آیکون',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br__arrow[class]' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'arrow_bg',
			array(
				'label'     => 'زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br__arrow[class]' => 'background:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'arrow_border',
			array(
				'label'     => 'حاشیه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br__arrow[class]' => 'border-color:{{VALUE}};' ),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_arrow_hover', array( 'label' => 'هاور' ) );

		$this->add_control(
			'arrow_color_hover',
			array(
				'label'     => 'رنگ آیکون',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br__arrow[class]:hover' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'arrow_bg_hover',
			array(
				'label'     => 'زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br__arrow[class]:hover' => 'background:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'arrow_border_hover',
			array(
				'label'     => 'حاشیه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br__arrow[class]:hover' => 'border-color:{{VALUE}};' ),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'dots_heading',
			array(
				'label'     => 'نقطه‌ها',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'slider_dots' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'dot_size',
			array(
				'label'      => 'اندازه نقطه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 3, 'max' => 20 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-br' => '--ard-br-dot:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'slider_dots' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'dot_active_w',
			array(
				'label'      => 'پهنای نقطه فعال',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 3, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-br' => '--ard-br-dot-w:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'slider_dots' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'dot_gap',
			array(
				'label'      => 'فاصله نقطه‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 24 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-br__dots[class]' => 'gap:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'slider_dots' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'dots_offset',
			array(
				'label'      => 'فاصله از اسلایدر',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-br__dots[class]' => 'margin-block-start:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'slider_dots' => 'yes' ),
			)
		);

		$this->add_control(
			'dot_color',
			array(
				'label'     => 'رنگ نقطه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br__dot[class]' => 'background:{{VALUE}};' ),
				'condition' => array( 'slider_dots' => 'yes' ),
			)
		);

		$this->add_control(
			'dot_active',
			array(
				'label'     => 'رنگ نقطه فعال',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-br__dot.is-active[class]' => 'background:{{VALUE}};' ),
				'condition' => array( 'slider_dots' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	/* --------------------------------------------------------------------- */
	/* رندر                                                                    */
	/* --------------------------------------------------------------------- */

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = $this->get_entries( $s );

		if ( empty( $items ) ) {
			$this->render_empty( 'هیچ برندی برای نمایش نیست.' );

			return;
		}

		$modes   = array( 'grid', 'slider', 'marquee' );
		$mode    = in_array( $s['layout'], $modes, true ) ? $s['layout'] : 'grid';
		$variant = in_array( $s['variant'], array( 'tint', 'outline', 'gradient', 'conic', 'plain' ), true ) ? $s['variant'] : 'tint';
		$classes = array( 'ard-br', 'ard-br--' . $variant );

		if ( 'yes' === $s['ring_alt'] && in_array( $variant, array( 'tint', 'outline' ), true ) ) {
			$classes[] = 'ard-br--alt';
		}

		$hover_factor = 1;

		if ( 'pause' === $s['mq_hover'] ) {
			$hover_factor = 0;
		} elseif ( 'slow' === $s['mq_hover'] ) {
			$raw          = $s['mq_hover_factor']['size'] ?? '';
			$hover_factor = ( '' === $raw ? 25 : (float) $raw ) / 100;
		}

		$attrs = array(
			'class'              => implode( ' ', $classes ),
			'data-ard-brands'    => '',
			'data-mode'          => $mode,
			'data-active-mode'   => $mode,
			'data-step'          => 'item' === $s['slider_step'] ? 'item' : 'page',
			'data-loop'          => 'yes' === $s['slider_loop'] ? '1' : '0',
			'data-drag'          => 'yes' === $s['slider_drag'] ? '1' : '0',
			'data-interval'      => 'yes' === $s['slider_autoplay'] ? (string) (int) round( max( 1, (float) $s['slider_interval'] ) * 1000 ) : '0',
			'data-pause-hover'   => 'yes' === $s['slider_pause_hover'] ? '1' : '0',
			'data-mq-dir'        => 'left' === $s['mq_direction'] ? 'left' : 'right',
			'data-mq-hover'      => (string) max( 0, min( 1, $hover_factor ) ),
			'data-mq-drag'       => 'yes' === $s['mq_drag'] ? '1' : '0',
		);

		$show_arrows = 'yes' === $s['slider_arrows'] && count( $items ) > 1;
		$show_dots   = 'yes' === $s['slider_dots'] && count( $items ) > 1;
		$name_tag    = self::tag( $s['name_tag'], 'span' );
		$new_tab     = 'woo' === $s['source'] && 'yes' === $s['woo_new_tab'];

		$this->render_heading();
		?>
		<div<?php echo $this->attrs_html( $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<div class="ard-br__stage">
				<?php if ( $show_arrows ) : ?>
					<button type="button" class="ard-br__arrow ard-br__arrow--prev" data-br-prev aria-label="قبلی">
						<?php echo Ardal_Helpers::icon( is_rtl() ? 'chevron-right' : 'chevron-left', 17 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
					<button type="button" class="ard-br__arrow ard-br__arrow--next" data-br-next aria-label="بعدی">
						<?php echo Ardal_Helpers::icon( is_rtl() ? 'chevron-left' : 'chevron-right', 17 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
				<?php endif; ?>

				<div class="ard-br__viewport" data-br-viewport>
					<ul class="ard-br__list" data-br-list>
						<?php foreach ( $items as $i => $item ) : ?>
							<?php $this->render_item( $item, $i, $s, $name_tag, $new_tab ); ?>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>

			<?php if ( $show_dots ) : ?>
				<div class="ard-br__dots" data-br-dots role="tablist" aria-label="انتخاب صفحه"></div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * @param array  $item
	 * @param int    $i
	 * @param array  $s
	 * @param string $name_tag
	 * @param bool   $new_tab
	 */
	private function render_item( $item, $i, $s, $name_tag, $new_tab ) {
		$title = (string) $item['title'];
		$url   = (string) ( $item['link']['url'] ?? '' );
		$tag   = '' !== $url ? 'a' : 'div';
		$logo  = $this->logo_url( $item['logo'] ?? array(), $s['image_size'] );
		$count = trim( (string) ( $item['count'] ?? '' ) );

		// کلاس یک‌درمیان از روی ایندکس اصلی می‌آید، نه nth-child؛ در مارکی
		// نسخه‌های تکراری هم همین کلاس را دارند و الگو سر درز به هم نمی‌ریزد.
		$li_class = 'ard-br__item' . ( 1 === $i % 2 ? ' is-alt' : '' );

		$link_attrs = '';

		if ( 'a' === $tag ) {
			$link                = $item['link'];
			$link['is_external'] = ! empty( $link['is_external'] ) || $new_tab;
			$link_attrs          = Ardal_Helpers::link_attrs( $link );
		}

		$label = 'yes' !== $s['show_name'] ? ' aria-label="' . esc_attr( $title ) . '"' : '';
		?>
		<li class="<?php echo esc_attr( $li_class ); ?>">
			<<?php echo esc_html( $tag ); ?> class="ard-br__card"<?php echo $link_attrs . $label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> draggable="false">
				<span class="ard-br__ring">
					<span class="ard-br__disc">
						<?php if ( '' !== $logo ) : ?>
							<img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" decoding="async" draggable="false" />
						<?php elseif ( 'yes' === $s['fallback_name'] ) : ?>
							<span class="ard-br__initial"><?php echo esc_html( $title ); ?></span>
						<?php endif; ?>
					</span>
				</span>
				<?php if ( 'yes' === $s['show_name'] || ( 'yes' === $s['show_count'] && '' !== $count ) ) : ?>
					<span class="ard-br__text">
						<?php if ( 'yes' === $s['show_name'] ) : ?>
							<<?php echo esc_html( $name_tag ); ?> class="ard-br__name"><?php echo esc_html( $title ); ?></<?php echo esc_html( $name_tag ); ?>>
						<?php endif; ?>
						<?php if ( 'yes' === $s['show_count'] && '' !== $count ) : ?>
							<span class="ard-br__count"><?php echo esc_html( trim( Ardal_Helpers::fa_digits( $count ) . ' ' . $s['count_suffix'] ) ); ?></span>
						<?php endif; ?>
					</span>
				<?php endif; ?>
			</<?php echo esc_html( $tag ); ?>>
		</li>
		<?php
	}

	/**
	 * @param array $attrs
	 * @return string
	 */
	private function attrs_html( $attrs ) {
		$out = '';

		foreach ( $attrs as $name => $value ) {
			$out .= '' === $value
				? ' ' . esc_attr( $name )
				: sprintf( ' %s="%s"', esc_attr( $name ), esc_attr( $value ) );
		}

		return $out;
	}

	/**
	 * آدرس لوگو؛ placeholder خود المنتور لوگو حساب نمی‌شود.
	 *
	 * @param array  $image
	 * @param string $size
	 * @return string
	 */
	private function logo_url( $image, $size ) {
		$url = '';

		if ( ! empty( $image['id'] ) ) {
			$src = wp_get_attachment_image_src( (int) $image['id'], $size ? $size : 'medium' );
			$url = $src ? $src[0] : '';
		}

		if ( '' === $url && ! empty( $image['url'] ) ) {
			$url = $image['url'];
		}

		if ( '' !== $url && false !== strpos( $url, 'elementor/assets/images/placeholder' ) ) {
			$url = '';
		}

		return $url;
	}

	/* --------------------------------------------------------------------- */
	/* داده                                                                    */
	/* --------------------------------------------------------------------- */

	/**
	 * شرطی که یک بخش را وقتی نشان می‌دهد که حالت داده‌شده در دست‌کم یک
	 * دستگاه انتخاب شده باشد؛ چون چیدمان ریسپانسیو است، شرط سادهٔ
	 * layout=slider فقط دسکتاپ را می‌دید.
	 *
	 * @param string $mode
	 * @return array
	 */
	private function mode_conditions( $mode ) {
		$names = array( 'layout' );
		$added = false;

		if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->breakpoints ) ) {
			$breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();

			foreach ( array_keys( (array) $breakpoints ) as $device ) {
				$names[] = 'layout_' . $device;
				$added   = true;
			}
		}

		if ( ! $added ) {
			$names[] = 'layout_tablet';
			$names[] = 'layout_mobile';
		}

		$terms = array();

		foreach ( $names as $name ) {
			$terms[] = array(
				'name'  => $name,
				'value' => $mode,
			);
		}

		return array(
			'relation' => 'or',
			'terms'    => $terms,
		);
	}

	/**
	 * تاکسونومی‌های برندی که روی این سایت ثبت شده‌اند.
	 *
	 * @return array
	 */
	private function available_taxonomies() {
		return array_values( array_filter( self::TAXONOMIES, 'taxonomy_exists' ) );
	}

	/**
	 * @param array $s
	 * @return string
	 */
	private function brand_taxonomy( $s ) {
		$pick = (string) ( $s['woo_taxonomy'] ?? 'auto' );

		if ( 'auto' !== $pick && taxonomy_exists( $pick ) ) {
			return $pick;
		}

		$found = $this->available_taxonomies();

		return $found ? $found[0] : '';
	}

	/**
	 * فهرست برندها برای سلکت تنظیمات. شناسهٔ ترم در وردپرس یکتاست، پس
	 * برندهای چند تاکسونومی کنار هم بی‌تداخل می‌نشینند.
	 *
	 * @return array
	 */
	private function brand_options() {
		static $cache = null;

		if ( null !== $cache ) {
			return $cache;
		}

		$cache      = array();
		$taxonomies = $this->available_taxonomies();

		if ( ! $taxonomies ) {
			return $cache;
		}

		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomies,
				'hide_empty' => false,
				'number'     => 300,
			)
		);

		if ( is_wp_error( $terms ) ) {
			return $cache;
		}

		$many = count( $taxonomies ) > 1;

		foreach ( $terms as $term ) {
			$cache[ $term->term_id ] = $many ? $term->name . ' (' . $term->taxonomy . ')' : $term->name;
		}

		return $cache;
	}

	/**
	 * شناسهٔ پیوست لوگوی یک برند؛ کلیدهای متای ووکامرس، Perfect Brands و YITH.
	 *
	 * @param int $term_id
	 * @return int
	 */
	private function term_logo_id( $term_id ) {
		foreach ( array( 'thumbnail_id', 'brand_image', 'pwb_brand_image' ) as $meta ) {
			$id = (int) get_term_meta( $term_id, $meta, true );

			if ( $id ) {
				return $id;
			}
		}

		return 0;
	}

	/**
	 * @param array $s
	 * @return array
	 */
	private function get_entries( $s ) {
		if ( 'woo' !== $s['source'] ) {
			return is_array( $s['items'] ) ? $s['items'] : array();
		}

		$taxonomy = $this->brand_taxonomy( $s );

		if ( '' === $taxonomy ) {
			return array();
		}

		$limit   = max( 1, (int) $s['woo_limit'] );
		$include = array_values( array_filter( array_map( 'intval', (array) ( $s['woo_include'] ?? array() ) ) ) );
		$exclude = array_values( array_filter( array_map( 'intval', (array) ( $s['woo_exclude'] ?? array() ) ) ) );
		$orderby = (string) $s['woo_orderby'];

		$args = array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => 'yes' === $s['woo_hide_empty'],
			'order'      => 'ASC' === $s['woo_order'] ? 'ASC' : 'DESC',
		);

		if ( $include ) {
			$args['include'] = $include;
			$args['orderby'] = 'include';
			$args['order']   = 'ASC';
		} else {
			$args['exclude'] = $exclude;
			$args['orderby'] = in_array( $orderby, array( 'count', 'name', 'menu_order', 'term_id' ), true ) ? $orderby : 'count';
		}

		// تصادفی و «فقط دارای لوگو» باید از میان همه انتخاب کنند، نه از چند تای اول.
		$filter_after = 'rand' === $orderby || 'yes' === $s['woo_only_logo'];

		if ( ! $filter_after ) {
			$args['number'] = $limit;
		}

		$terms = get_terms( $args );

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return array();
		}

		if ( 'rand' === $orderby && ! $include ) {
			shuffle( $terms );
		}

		$out = array();

		foreach ( $terms as $term ) {
			$logo_id = $this->term_logo_id( $term->term_id );

			if ( ! $logo_id && 'yes' === $s['woo_only_logo'] ) {
				continue;
			}

			$link = 'yes' === $s['woo_link'] ? get_term_link( $term ) : '';

			$out[] = array(
				'title' => $term->name,
				'count' => (string) $term->count,
				'logo'  => array( 'id' => $logo_id ),
				'link'  => array( 'url' => is_wp_error( $link ) ? '' : (string) $link ),
			);

			if ( count( $out ) >= $limit ) {
				break;
			}
		}

		return $out;
	}
}
