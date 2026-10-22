<?php
/**
 * ویجت امتیاز محصول: آیکون ستاره (یا پنج ستاره) و میانگین امتیاز.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ARDAL_PATH . 'includes/trait-ardal-product-stat.php';

use Elementor\Controls_Manager;

class Ardal_Widget_Product_Rating extends Ardal_Widget_Base {

	use Ardal_Product_Stat;

	public function get_name() {
		return 'ardal-product-rating';
	}

	public function get_title() {
		return 'اردال — امتیاز محصول';
	}

	public function get_icon() {
		return 'eicon-rating';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'امتیاز', 'ستاره', 'rating', 'stars', 'review' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'محتوا' )
		);

		$this->add_control(
			'source',
			array(
				'label'   => 'منبع',
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => array(
					'auto'   => 'خودکار از محصول جاری',
					'manual' => 'دستی',
				),
			)
		);

		$this->add_control(
			'rating',
			array(
				'label'     => 'امتیاز',
				'type'      => Controls_Manager::NUMBER,
				'default'   => 4,
				'min'       => 0,
				'max'       => 5,
				'step'      => 0.1,
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'display',
			array(
				'label'   => 'نمایش',
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => array(
					'icon'  => 'یک آیکون و عدد',
					'stars' => 'پنج ستاره و عدد',
				),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'       => 'متن',
				'type'        => Controls_Manager::TEXT,
				'default'     => '{n} امتیاز',
				'description' => '{n} جای عدد امتیاز می‌نشیند؛ مثلاً «{n} از ۵» یا فقط «{n}».',
			)
		);

		$this->add_control(
			'decimals',
			array(
				'label'   => 'رقم اعشار',
				'type'    => Controls_Manager::SELECT,
				'default' => '2',
				'options' => array(
					'0' => 'بدون اعشار (۴)',
					'1' => 'یک رقم (۴.۰)',
					'2' => 'دو رقم (۴.۰۰)',
				),
			)
		);

		$this->add_control(
			'empty',
			array(
				'label'     => 'محصول بدون امتیاز',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'text',
				'separator' => 'before',
				'options'   => array(
					'text' => 'نمایش متن جایگزین',
					'zero' => 'نمایش صفر',
					'hide' => 'پنهان شود',
				),
			)
		);

		$this->add_control(
			'empty_text',
			array(
				'label'     => 'متن جایگزین',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'بدون امتیاز',
				'condition' => array( 'empty' => 'text' ),
			)
		);

		$this->add_stat_common_controls();

		$this->end_controls_section();

		$this->add_stat_style_controls();

		$this->start_controls_section(
			'section_style_stars',
			array(
				'label'     => 'ستاره‌ها',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'display' => 'stars' ),
			)
		);

		$this->add_control(
			'stars_color',
			array(
				'label'     => 'رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pstat__stars .ard-stars' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'stars_size',
			array(
				'label'      => 'اندازه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 8, 'max' => 32 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pstat__stars' => 'font-size:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( 'manual' === $s['source'] ) {
			$rating = (float) $s['rating'];
		} else {
			$product = Ardal_Product::current();

			if ( ! $product ) {
				$this->render_empty( 'محصولی پیدا نشد. این ویجت را روی قالب تک‌محصول بگذارید یا حالت دستی را انتخاب کنید.' );

				return;
			}

			$rating = (float) $product->get_average_rating();
		}

		$rating = max( 0, min( 5, $rating ) );
		$num    = Ardal_Helpers::fa_digits( number_format( $rating, (int) $s['decimals'], '.', '' ) );
		$text   = (string) $s['text'];
		$class  = '';

		if ( $rating <= 0 ) {
			if ( 'hide' === $s['empty'] ) {
				$this->render_empty( 'این محصول هنوز امتیازی ندارد و ویجت پنهان است.' );

				return;
			}

			$class = 'is-empty';

			if ( 'text' === $s['empty'] ) {
				$text = (string) $s['empty_text'];
			}
		}

		$extra = 'stars' === $s['display']
			? '<span class="ard-pstat__stars">' . Ardal_Helpers::stars( $rating ) . '</span>'
			: '';

		if ( 'stars' === $s['display'] ) {
			// پنج ستاره خودش نشانهٔ امتیاز است؛ آیکون جدا اضافه نمی‌شود.
			$s['hide_icon'] = 'yes';
		}

		$this->render_stat(
			$s,
			'rating',
			'star',
			$text,
			$num,
			array(
				'extra' => $extra,
				'label' => $rating > 0 ? sprintf( 'امتیاز %s از ۵', Ardal_Helpers::fa_digits( round( $rating, 1 ) ) ) : '',
				'class' => $class,
			)
		);
	}
}
