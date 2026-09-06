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

		$this->add_source_controls( 5 );

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

		printf(
			'<div class="ard-pgrid ard-pgrid--%s"%s>',
			esc_attr( $s['variant'] ),
			! empty( $tabs ) ? ' data-ard-filterable' : ''
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
					'variant'     => $s['variant'],
					'show_rating' => 'yes' === $s['show_rating'],
					'show_fab'    => $framed,
					'show_button' => ! $framed,
					'button_text' => $s['button_text'],
					'placeholder' => '۸۰۰×۸۰۰',
				)
			);

			echo '</div>';
		}

		echo '</div>';
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
