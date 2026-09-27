<?php
/**
 * ویجت کارت محصول تکی — برای استفاده داخل حلقه‌های المنتور (Loop Item).
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Ardal_Widget_Product_Card extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-product-card';
	}

	public function get_title() {
		return 'اردال — کارت محصول (حلقه)';
	}

	public function get_icon() {
		return 'eicon-product-related';
	}

	public function get_keywords() {
		return array( 'اردال', 'کارت', 'محصول', 'حلقه', 'لوپ', 'loop', 'product', 'card' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'محتوا' )
		);

		$this->add_control(
			'source',
			array(
				'label'       => 'منبع داده',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'loop',
				'options'     => array(
					'loop'   => 'خودکار از حلقه (نوشتهٔ جاری)',
					'manual' => 'دستی',
				),
				'description' => 'حالت «حلقه» برای قرار گرفتن داخل قالب Loop Item المنتور است؛ اطلاعات از همان محصولی که در حال نمایش است خوانده می‌شود.',
			)
		);

		$this->add_control(
			'loop_notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => '<div style="font-size:12px;line-height:1.9">این ویجت را داخل قالب <b>Loop Item</b> بگذارید و آن قالب را به ویجت <b>Loop Grid</b> بدهید. بیرون از حلقه، اطلاعات نوشتهٔ همان صفحه نمایش داده می‌شود.</div>',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => array( 'source' => 'loop' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => 'نام محصول',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'default'     => 'کفش دویدن ران‌فلکس مدل ایر پرو ۲۰۲۵',
				'label_block' => true,
				'condition'   => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'     => 'تصویر',
				'type'      => Controls_Manager::MEDIA,
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'price',
			array(
				'label'     => 'قیمت',
				'type'      => Controls_Manager::TEXT,
				'default'   => '۴٫۸۵۰٫۰۰۰',
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'old_price',
			array(
				'label'     => 'قیمت قبل',
				'type'      => Controls_Manager::TEXT,
				'default'   => '۵٫۹۰۰٫۰۰۰',
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'off',
			array(
				'label'       => 'درصد تخفیف',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'خالی = محاسبه خودکار',
				'condition'   => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'rating',
			array(
				'label'     => 'امتیاز',
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 5, 'step' => 0.5 ) ),
				'default'   => array( 'size' => 4 ),
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'count',
			array(
				'label'     => 'تعداد نظر',
				'type'      => Controls_Manager::NUMBER,
				'default'   => 241,
				'min'       => 0,
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'     => 'لینک محصول',
				'type'      => Controls_Manager::URL,
				'default'   => array( 'url' => '' ),
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'add_url',
			array(
				'label'     => 'لینک افزودن به سبد',
				'type'      => Controls_Manager::URL,
				'default'   => array( 'url' => '' ),
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_display',
			array( 'label' => 'نمایش' )
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

		$this->add_tag_control( 'card_title_tag', 'تگ نام محصول', 'h3' );

		$this->add_control(
			'show_off',
			array(
				'label'        => 'نمایش نشان تخفیف',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_old_price',
			array(
				'label'        => 'نمایش قیمت قبل',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'placeholder',
			array(
				'label'       => 'متن جای‌گاه تصویر',
				'type'        => Controls_Manager::TEXT,
				'default'     => '۸۰۰×۸۰۰',
				'description' => 'وقتی محصول تصویر شاخص ندارد نمایش داده می‌شود.',
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
					'reveal' => 'دکمه آشکارشونده با هاور (دیزاین ۱c)',
				),
			)
		);

		$this->add_media_fit_control( '.ard-pcard__media img' );

		$this->add_control(
			'reveal_btn_bg',
			array(
				'label'     => 'زمینه دکمه آشکارشونده',
				'type'      => Controls_Manager::COLOR,
				'condition' => array( 'variant' => 'reveal' ),
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-btn-bg:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'reveal_btn_ink',
			array(
				'label'     => 'رنگ متن دکمه آشکارشونده',
				'type'      => Controls_Manager::COLOR,
				'condition' => array( 'variant' => 'reveal' ),
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-btn-ink:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'reveal_foot_h',
			array(
				'label'      => 'ارتفاع نوار قیمت و دکمه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 32, 'max' => 64 ) ),
				'condition'  => array( 'variant' => 'reveal' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-foot:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'     => 'متن دکمه',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'افزودن به سبد',
				'condition' => array( 'variant' => array( 'plain', 'reveal' ) ),
			)
		);

		$this->add_control(
			'notch',
			array(
				'label'        => 'بریدگی گوشه پایین-چپ',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'گوشه کارت را دور دکمهٔ گرد می‌بُرد.',
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
				'condition'  => array(
					'variant' => 'framed',
					'notch'   => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pcard--notch' => '--ard-notch:{{SIZE}}{{UNIT}};',
				),
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
				'condition'  => array( 'variant' => 'framed' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pcard' => '--ard-fab:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_height',
			array(
				'label'          => 'ارتفاع تصویر',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 80, 'max' => 400 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 168 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 120 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-pcard__media' => 'height:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_lines',
			array(
				'label'     => 'حداکثر خطوط عنوان',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 5,
				'default'   => 2,
				'selectors' => array(
					'{{WRAPPER}} .ard-pcard__title' => 'display:-webkit-box;-webkit-line-clamp:{{VALUE}};-webkit-box-orient:vertical;overflow:hidden;',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$item = 'manual' === $s['source'] ? $this->manual_item( $s ) : $this->loop_item();

		if ( null === $item ) {
			$this->render_empty( 'این ویجت باید داخل یک حلقه (Loop Item) یا روی یک نوشته قرار بگیرد.' );

			return;
		}

		// درصد تخفیف پیش از حذف قیمت قبل حساب می‌شود، وگرنه مبنای محاسبه از دست می‌رود.
		if ( '' === trim( (string) $item['off'] ) ) {
			$item['off'] = Ardal_Helpers::discount(
				Ardal_Helpers::price( $item['old_price'] ),
				Ardal_Helpers::price( $item['price'] )
			);
		}

		if ( 'yes' !== $s['show_old_price'] ) {
			$item['old_price'] = '';
		}

		$framed = 'framed' === $s['variant'];

		/*
		 * سبک آشکارشونده دکمهٔ متنی دارد (که با هاور بالا می‌آید)، نه دکمهٔ
		 * گرد؛ و درصد تخفیفش کنار قیمت می‌نشیند نه گوشهٔ تصویر.
		 */
		$reveal = 'reveal' === $s['variant'];

		echo '<div class="ard-pcard-solo">';

		$this->render_product_card(
			$item,
			array(
				'variant'       => $s['variant'],
				'link_mode'     => $s['link_mode'],
				'button_action' => $s['button_action'],
				'show_rating'   => 'yes' === $s['show_rating'],
				'show_off'      => 'yes' === $s['show_off'],
				'show_fab'    => $framed,
				'show_button' => ! $framed,
				'off_inline'  => $reveal,
				'button_text' => $s['button_text'],
				'placeholder' => $s['placeholder'],
				'notch'       => $framed && 'yes' === $s['notch'],
				'title_tag'   => $s['card_title_tag'],
				// کارت تکی همسایه‌ای ندارد که بخواهد با آن هم‌تراز بماند.
				'reserve_old'  => false,
				'reserve_rate' => false,
			)
		);

		echo '</div>';
	}

	/**
	 * ساخت آیتم از مقادیر دستی پنل.
	 *
	 * @param array $s
	 * @return array
	 */
	private function manual_item( $s ) {
		return array(
			'title'     => $s['title'],
			'image'     => $s['image'],
			'price'     => $s['price'],
			'old_price' => $s['old_price'],
			'off'       => $s['off'],
			'rating'    => isset( $s['rating']['size'] ) ? (float) $s['rating']['size'] : 0,
			'count'     => (int) $s['count'],
			'link'      => $s['link'],
			'add_url'   => $s['add_url'],
		);
	}

	/**
	 * ساخت آیتم از نوشتهٔ جاری حلقه.
	 * محصول ووکامرس همهٔ فیلدها را پر می‌کند؛ نوشتهٔ معمولی فقط عنوان و تصویر و لینک.
	 *
	 * @return array|null
	 */
	private function loop_item() {
		$id = get_the_ID();

		if ( ! $id ) {
			return null;
		}

		if ( Ardal_Helpers::has_woo() && function_exists( 'wc_get_product' ) && 'product' === get_post_type( $id ) ) {
			$product = wc_get_product( $id );

			if ( $product ) {
				return Ardal_Helpers::product_to_card( $product );
			}
		}

		$thumb = get_the_post_thumbnail_url( $id, 'medium_large' );

		return array(
			'title'     => get_the_title( $id ),
			'image'     => array( 'url' => $thumb ? $thumb : '' ),
			'price'     => '',
			'old_price' => '',
			'off'       => '',
			'rating'    => 0,
			'count'     => 0,
			'link'      => array( 'url' => get_permalink( $id ) ),
			'add_url'   => array( 'url' => '' ),
		);
	}
}
