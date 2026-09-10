<?php
/**
 * ویجت شبکه محصولات آرشیو — حلقه، کارت، صفحه‌بندی و حالت «نتیجه‌ای نیست».
 *
 * کارت اینجا کاملاً از خودِ ویجت می‌آید و هوک‌های حلقه‌ی ووکامرس اجرا نمی‌شوند؛
 * اگر اجرا می‌شدند، مارک‌آپ پیش‌فرض (تصویر، عنوان، قیمت) دوباره چاپ می‌شد.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Archive_Products extends Ardal_Archive_Widget_Base {

	public function get_name() {
		return 'ardal-archive-products';
	}

	public function get_title() {
		return 'اردال — شبکه محصولات آرشیو';
	}

	public function get_icon() {
		return 'eicon-products';
	}

	protected function register_controls() {

		/* ------------------------------------------------ منبع */

		$this->start_controls_section(
			'section_query',
			array( 'label' => 'منبع محصولات' )
		);

		$this->add_control(
			'query_source',
			array(
				'label'       => 'کوئری',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'archive',
				'options'     => array(
					'archive' => 'آرشیو جاری (فیلترها و صفحه‌بندی فعال)',
					'custom'  => 'کوئری دلخواه (بدون فیلتر و صفحه‌بندی)',
				),
				'label_block' => true,
			)
		);

		$this->add_control(
			'per_page_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => 'تعداد محصول در هر صفحه از «تنظیمات اردال ← آرشیو فروشگاه» خوانده می‌شود، چون کوئری آرشیو پیش از رندر ویجت ساخته می‌شود.',
				'content_classes' => 'elementor-descriptor',
				'condition'       => array( 'query_source' => 'archive' ),
			)
		);

		$this->add_control(
			'custom_cats',
			array(
				'label'       => 'دسته‌بندی‌ها',
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => Ardal_Helpers::product_category_options(),
				'default'     => array(),
				'label_block' => true,
				'condition'   => array( 'query_source' => 'custom' ),
			)
		);

		$this->add_control(
			'custom_source',
			array(
				'label'     => 'چیدمان',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'recent',
				'options'   => array(
					'recent'   => 'جدیدترین',
					'best'     => 'پرفروش‌ترین',
					'sale'     => 'تخفیف‌دار',
					'featured' => 'ویژه',
					'rating'   => 'پرامتیازترین',
				),
				'condition' => array( 'query_source' => 'custom' ),
			)
		);

		$this->add_control(
			'custom_limit',
			array(
				'label'     => 'تعداد',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 48,
				'default'   => 12,
				'condition' => array( 'query_source' => 'custom' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ شبکه */

		$this->start_controls_section(
			'section_grid',
			array( 'label' => 'شبکه' )
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => 'تعداد ستون',
				'type'           => Controls_Manager::SELECT,
				'default'        => '4',
				'tablet_default' => '3',
				'mobile_default' => '2',
				'options'        => array(
					'1' => '۱',
					'2' => '۲',
					'3' => '۳',
					'4' => '۴',
					'5' => '۵',
					'6' => '۶',
				),
				'selectors'      => array(
					'{{WRAPPER}} .ard-grid' => '--ard-grid-cols:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => 'فاصله بین کارت‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 4, 'max' => 48 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 20 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 12 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-grid' => 'gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		// چیدمان با کلاس اعمال می‌شود نه selector، چون text-align و justify-content
		// دو واژه‌ی متفاوت می‌خواهند و یک {{VALUE}} برای هر دو جواب نمی‌دهد.
		$this->add_control(
			'align',
			array(
				'label'   => 'چیدمان محتوای کارت',
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'start'  => array( 'title' => 'راست', 'icon' => 'eicon-text-align-right' ),
					'center' => array( 'title' => 'وسط', 'icon' => 'eicon-text-align-center' ),
				),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ کارت */

		$this->start_controls_section(
			'section_card',
			array( 'label' => 'کارت محصول' )
		);

		$this->add_control(
			'image_ratio',
			array(
				'label'   => 'نسبت تصویر',
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => array(
					'1'    => 'مربع (۱:۱)',
					'0.8'  => 'افقی ملایم (۵:۴)',
					'1.25' => 'عمودی (۴:۵)',
					'1.4'  => 'عمودی بلند (۵:۷)',
				),
			)
		);

		$this->add_control(
			'image_fit',
			array(
				'label'   => 'نحوه جاگرفتن تصویر',
				'type'    => Controls_Manager::SELECT,
				'default' => 'contain',
				'options' => array(
					'contain' => 'کامل دیده شود (contain)',
					'cover'   => 'قاب را پر کند (cover)',
				),
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
				'condition' => array( 'show_badge' => 'yes' ),
			)
		);

		$this->add_control(
			'badge_text',
			array(
				'label'     => 'متن ثابت نشان',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'حراج',
				'condition' => array( 'show_badge' => 'yes', 'badge_type' => 'text' ),
			)
		);

		$this->add_control(
			'show_stock',
			array(
				'label'        => 'برچسب «ناموجود»',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
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
			)
		);

		$this->add_control(
			'show_rating',
			array(
				'label'        => 'امتیاز',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
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
				'condition' => array( 'show_rating' => 'yes' ),
			)
		);

		$this->add_control(
			'hide_empty_rating',
			array(
				'label'        => 'پنهان‌کردن امتیاز صفر',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'show_rating' => 'yes' ),
			)
		);

		$this->add_control(
			'show_cat',
			array(
				'label'        => 'نام دسته بالای عنوان',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_subtitle',
			array(
				'label'        => 'عنوان انگلیسی زیر نام',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'   => 'تگ عنوان کارت',
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => array(
					'h2'  => 'H2',
					'h3'  => 'H3',
					'h4'  => 'H4',
					'div' => 'DIV',
				),
			)
		);

		$this->add_control(
			'title_lines',
			array(
				'label'      => 'حداکثر خط عنوان',
				'type'       => Controls_Manager::SLIDER,
				'range'      => array( 'px' => array( 'min' => 1, 'max' => 4, 'step' => 1 ) ),
				'default'    => array( 'size' => 2 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-acard__name-fa' => '-webkit-line-clamp:{{SIZE}};',
				),
			)
		);

		$this->add_control(
			'show_price',
			array(
				'label'        => 'قیمت',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_old_price',
			array(
				'label'        => 'قیمت خط‌خورده',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'show_price' => 'yes' ),
			)
		);

		$this->add_control(
			'show_button',
			array(
				'label'        => 'دکمه خرید',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
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
				'condition' => array( 'show_button' => 'yes' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'     => 'متن دکمه',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'افزودن به سبد',
				'condition' => array( 'show_button' => 'yes', 'button_style!' => 'fab' ),
			)
		);

		$this->add_control(
			'hover',
			array(
				'label'   => 'افکت هاور کارت',
				'type'    => Controls_Manager::SELECT,
				'default' => 'lift',
				'options' => array(
					'lift' => 'بالا آمدن کارت',
					'zoom' => 'بزرگ‌نمایی تصویر',
					'none' => 'بدون افکت',
				),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ صفحه‌بندی */

		$this->start_controls_section(
			'section_pager',
			array(
				'label'     => 'صفحه‌بندی',
				'condition' => array( 'query_source' => 'archive' ),
			)
		);

		$this->add_control(
			'show_pager',
			array(
				'label'        => 'نمایش صفحه‌بندی',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
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

		$this->add_control(
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

		$this->end_controls_section();

		/* ------------------------------------------------ حالت خالی */

		$this->start_controls_section(
			'section_empty',
			array( 'label' => 'وقتی نتیجه‌ای نیست' )
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
				'label'   => 'متن دکمه پاک‌کردن فیلترها',
				'type'    => Controls_Manager::TEXT,
				'default' => 'پاک کردن همه فیلترها',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ استایل */

		$this->start_controls_section(
			'section_style_card',
			array(
				'label' => 'کارت',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'card_bg',
			array(
				'label'     => 'زمینه کارت',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-acard' => 'background:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_radius',
			array(
				'label'      => 'گردی کارت',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-acard' => 'border-radius:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => 'فاصله داخلی کارت',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-acard' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'card_border',
			array(
				'label'     => 'رنگ حاشیه کارت',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-acard' => 'border-color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'media_bg',
			array(
				'label'     => 'زمینه قاب تصویر',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-acard__media' => 'background:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_text',
			array(
				'label' => 'متن‌های کارت',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => 'رنگ عنوان',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-acard__name' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'name_typo',
				'label'    => 'قلم عنوان',
				'selector' => '{{WRAPPER}} .ard-acard__name',
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => 'رنگ قیمت',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-acard__price' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typo',
				'label'    => 'قلم قیمت',
				'selector' => '{{WRAPPER}} .ard-acard__price',
			)
		);

		$this->add_control(
			'old_color',
			array(
				'label'     => 'رنگ قیمت خط‌خورده',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-acard__old' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'badge_bg',
			array(
				'label'     => 'زمینه نشان تخفیف',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-acard__badge' => 'background:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'star_color',
			array(
				'label'     => 'رنگ ستاره‌ها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-acard__stars' => 'color:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_btn',
			array(
				'label'     => 'دکمه خرید',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_button' => 'yes' ),
			)
		);

		$this->add_control(
			'btn_bg',
			array(
				'label'     => 'زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-acard__btn, {{WRAPPER}} .ard-acard__fab' => 'background:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'btn_color',
			array(
				'label'     => 'متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-acard__btn, {{WRAPPER}} .ard-acard__fab' => 'color:{{VALUE}};',
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
					'{{WRAPPER}} .ard-acard__btn' => 'border-radius:{{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'button_style!' => 'fab' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'btn_typo',
				'selector' => '{{WRAPPER}} .ard-acard__btn',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_pager',
			array(
				'label'     => 'صفحه‌بندی',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_pager' => 'yes', 'query_source' => 'archive' ),
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
			)
		);

		$this->end_controls_section();
	}

	/* --------------------------------------------------------------------- */
	/* کوئری                                                                  */
	/* --------------------------------------------------------------------- */

	/**
	 * کوئری‌ای که باید پیمایش شود.
	 *
	 * @param array $s
	 * @return WP_Query
	 */
	protected function build_query( $s ) {
		if ( 'custom' !== $s['query_source'] ) {
			$main = isset( $GLOBALS['wp_query'] ) ? $GLOBALS['wp_query'] : null;

			// در آرشیو واقعی، همان کوئری اصلی؛ ویجت نباید کوئری دوباره بزند.
			if ( $main instanceof WP_Query && ( $main->have_posts() || ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) ) {
				if ( 'product' === $main->get( 'post_type' ) || $main->is_post_type_archive( 'product' ) || $main->is_tax( get_object_taxonomies( 'product' ) ) ) {
					return $main;
				}
			}

			// بیرون از آرشیو (مثلاً پیش‌نمایش ویرایشگر) یک حلقه‌ی نمونه ساخته می‌شود.
			return new WP_Query(
				array(
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'posts_per_page' => 12,
					'no_found_rows'  => true,
				)
			);
		}

		$args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => max( 1, (int) $s['custom_limit'] ),
			'no_found_rows'  => true,
			'tax_query'      => array(),
			'meta_query'     => array(),
		);

		$cats = array_filter( (array) $s['custom_cats'] );

		if ( $cats ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $cats,
			);
		}

		switch ( $s['custom_source'] ) {
			case 'best':
				$args['meta_key'] = 'total_sales';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'DESC';
				break;

			case 'sale':
				$ids            = wc_get_product_ids_on_sale();
				$args['post__in'] = $ids ? $ids : array( 0 );
				break;

			case 'featured':
				$args['tax_query'][] = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
				);
				break;

			case 'rating':
				$args['meta_key'] = '_wc_average_rating';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'DESC';
				break;

			default:
				$args['orderby'] = 'date';
				$args['order']   = 'DESC';
		}

		return new WP_Query( $args );
	}

	/* --------------------------------------------------------------------- */
	/* رندر                                                                    */
	/* --------------------------------------------------------------------- */

	protected function render() {
		if ( $this->require_woo() ) {
			return;
		}

		$s     = $this->get_settings_for_display();
		$query = $this->build_query( $s );
		$main  = isset( $GLOBALS['wp_query'] ) && $query === $GLOBALS['wp_query'];

		echo '<div class="ard-arch-products">';

		if ( ! $query->have_posts() ) {
			$this->render_no_results( $s );
			echo '</div>';

			return;
		}

		printf(
			'<ul class="ard-grid products ard-grid--%s ard-grid--fit-%s ard-grid--align-%s">',
			esc_attr( $s['hover'] ),
			esc_attr( $s['image_fit'] ),
			esc_attr( 'start' === $s['align'] ? 'start' : 'center' )
		);

		while ( $query->have_posts() ) {
			$query->the_post();

			$product = wc_get_product( get_the_ID() );

			if ( $product && $product->is_visible() ) {
				$this->render_card( $product, $s );
			}
		}

		echo '</ul>';

		// کوئری اصلی نباید با wp_reset_postdata دست‌کاری شود؛ فقط اشاره‌گرش
		// برمی‌گردد تا ویجت‌های بعدی صفحه سر جای خودشان باشند.
		if ( $main ) {
			$query->rewind_posts();
			wp_reset_postdata();
		} else {
			wp_reset_postdata();
		}

		if ( $main && 'yes' === $s['show_pager'] && 'custom' !== $s['query_source'] ) {
			$this->render_pager( $s, $query );
		}

		echo '</div>';
	}

	/**
	 * یک کارت محصول.
	 *
	 * @param WC_Product $product
	 * @param array      $s
	 */
	protected function render_card( $product, $s ) {
		$id      = $product->get_id();
		$link    = get_permalink( $id );
		$regular = (float) $product->get_regular_price();
		$active  = (float) $product->get_price();
		$rating  = (float) $product->get_average_rating();
		$tag     = in_array( $s['title_tag'], array( 'h2', 'h3', 'h4', 'div' ), true ) ? $s['title_tag'] : 'h3';

		$classes = array( 'ard-acard' );

		if ( ! $product->is_in_stock() ) {
			$classes[] = 'is-out';
		}

		printf(
			'<li class="%s" style="--ard-acard-ratio:%s">',
			esc_attr( implode( ' ', wc_get_product_class( $classes, $product ) ) ),
			esc_attr( $s['image_ratio'] )
		);

		/* ------------------------------------------- تصویر */
		echo '<div class="ard-acard__media">';
		printf(
			'<a class="ard-acard__media-link" href="%s" aria-label="%s">%s</a>',
			esc_url( $link ),
			esc_attr( $product->get_name() ),
			$product->get_image( 'woocommerce_thumbnail', array( 'loading' => 'lazy' ) ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);

		if ( 'yes' === $s['show_badge'] && $product->is_on_sale() ) {
			$text = 'text' === $s['badge_type']
				? $s['badge_text']
				: Ardal_Helpers::discount( $regular, $active );

			if ( '' !== $text ) {
				printf( '<span class="ard-acard__badge">%s</span>', esc_html( $text ) );
			}
		}

		if ( 'yes' === $s['show_stock'] && ! $product->is_in_stock() ) {
			echo '<span class="ard-acard__flag">ناموجود</span>';
		}

		if ( 'yes' === $s['show_wishlist'] && Ardal_Product::wishlist_on() ) {
			printf(
				'<button type="button" class="ard-acard__wish" data-ard-wishlist data-product-id="%d" data-label-off="افزودن به علاقه‌مندی‌ها" data-label-on="حذف از علاقه‌مندی‌ها" aria-pressed="false" aria-label="افزودن به علاقه‌مندی‌ها">%s</button>',
				(int) $id,
				Ardal_Archive::icon( 'heart', 17 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		if ( 'yes' === $s['show_button'] && 'fab' === $s['button_style'] ) {
			$this->render_buy( $product, $s, 'ard-acard__fab', '<span aria-hidden="true">+</span>' );
		}

		echo '</div>';

		/* ------------------------------------------- امتیاز و دسته */
		$show_rating = 'yes' === $s['show_rating'] && ( $rating > 0 || 'yes' !== $s['hide_empty_rating'] );

		if ( $show_rating || 'yes' === $s['show_cat'] ) {
			echo '<div class="ard-acard__meta">';

			if ( 'yes' === $s['show_cat'] ) {
				$terms = get_the_terms( $id, 'product_cat' );

				if ( $terms && ! is_wp_error( $terms ) ) {
					printf( '<span class="ard-acard__cat">%s</span>', esc_html( $terms[0]->name ) );
				}
			}

			if ( $show_rating ) {
				echo '<span class="ard-acard__rating" dir="ltr">';

				if ( 'num' !== $s['rating_style'] ) {
					echo '<span class="ard-acard__stars">';

					for ( $i = 1; $i <= 5; $i++ ) {
						echo Ardal_Archive::star( $i <= round( $rating ), 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}

					echo '</span>';
				}

				if ( 'stars' !== $s['rating_style'] ) {
					printf(
						'<span class="ard-acard__rate-num">%s</span>',
						esc_html( Ardal_Helpers::fa_digits( number_format_i18n( $rating, 1 ) ) )
					);
				}

				printf(
					'<span class="screen-reader-text">بر پایه %s نظر</span>',
					esc_html( Ardal_Helpers::fa_digits( number_format_i18n( $product->get_rating_count() ) ) )
				);

				echo '</span>';
			}

			echo '</div>';
		}

		/* ------------------------------------------- عنوان */
		printf( '<%s class="ard-acard__name woocommerce-loop-product__title">', esc_html( $tag ) );
		printf( '<a href="%s">', esc_url( $link ) );
		printf( '<span class="ard-acard__name-fa">%s</span>', esc_html( $product->get_name() ) );

		if ( 'yes' === $s['show_subtitle'] ) {
			$subtitle = Ardal_Product::subtitle( $product );

			if ( '' !== $subtitle ) {
				printf( '<span class="ard-acard__name-en">%s</span>', esc_html( $subtitle ) );
			}
		}

		printf( '</a></%s>', esc_html( $tag ) );

		/* ------------------------------------------- قیمت */
		if ( 'yes' === $s['show_price'] ) {
			echo '<div class="ard-acard__prices">';

			if ( 'yes' === $s['show_old_price'] && $product->is_on_sale() && $regular > $active ) {
				printf(
					'<del class="ard-acard__old">%s</del>',
					esc_html( Ardal_Helpers::price( $regular ) )
				);
			}

			if ( '' !== $product->get_price() ) {
				printf(
					'<span class="ard-acard__price">%s %s</span>',
					esc_html( Ardal_Helpers::price( $active ) ),
					Ardal_Currency::unit() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			} else {
				echo '<span class="ard-acard__price ard-acard__price--call">تماس بگیرید</span>';
			}

			echo '</div>';
		}

		/* ------------------------------------------- دکمه */
		if ( 'yes' === $s['show_button'] && 'fab' !== $s['button_style'] ) {
			$this->render_buy(
				$product,
				$s,
				'ard-acard__btn ard-acard__btn--' . $s['button_style'],
				esc_html( $s['button_text'] )
			);
		}

		echo '</li>';
	}

	/**
	 * دکمه خرید؛ برای محصول ساده‌ی موجود، ایجکس ووکامرس هم فعال می‌شود.
	 *
	 * @param WC_Product $product
	 * @param array      $s
	 * @param string     $class
	 * @param string     $inner از قبل escape شده.
	 */
	protected function render_buy( $product, $s, $class, $inner ) {
		echo Ardal_Helpers::woocommerce_add_to_cart_link( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$product,
			explode( ' ', $class ),
			wp_strip_all_tags( $inner ),
			array( 'data-ard-add-to-cart' => 'true' )
		);
	}

	/**
	 * صفحه‌بندی.
	 *
	 * @param array    $s
	 * @param WP_Query $query
	 */
	protected function render_pager( $s, $query ) {
		$stats = Ardal_Archive::loop_stats( $query );

		if ( $stats['pages'] <= 1 ) {
			return;
		}

		$base = esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );

		echo '<nav class="ard-pager woocommerce-pagination" aria-label="صفحه‌بندی محصولات">';

		echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			array(
				'base'      => $base,
				'format'    => '',
				'add_args'  => false,
				'current'   => max( 1, $stats['current'] ),
				'total'     => $stats['pages'],
				'prev_text' => Ardal_Archive::icon( 'chev-r', 18, 2 ),
				'next_text' => Ardal_Archive::icon( 'chev-l', 18, 2 ),
				'type'      => 'list',
				'end_size'  => 1,
				'mid_size'  => max( 0, (int) $s['pager_mid'] ),
			)
		);

		echo '</nav>';
	}

	/**
	 * حالت «نتیجه‌ای نیست».
	 *
	 * @param array $s
	 */
	protected function render_no_results( $s ) {
		$active = Ardal_Archive::active_count();

		echo '<div class="ard-arch-empty woocommerce-info">';
		printf(
			'<span class="ard-arch-empty__art" aria-hidden="true">%s</span>',
			Ardal_Archive::icon( 'empty', 54, 1.2 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);

		if ( '' !== trim( (string) $s['empty_title'] ) ) {
			printf( '<p class="ard-arch-empty__title">%s</p>', esc_html( $s['empty_title'] ) );
		}

		if ( '' !== trim( (string) $s['empty_text'] ) ) {
			printf( '<p class="ard-arch-empty__text">%s</p>', esc_html( $s['empty_text'] ) );
		}

		if ( $active && '' !== trim( (string) $s['empty_btn'] ) ) {
			printf(
				'<a class="ard-arch-empty__btn" href="%s">%s</a>',
				esc_url( Ardal_Archive::base_url() ),
				esc_html( $s['empty_btn'] )
			);
		}

		echo '</div>';
	}
}
