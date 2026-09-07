<?php
/**
 * ویجت «قیمت محصول به تاریخ ... به‌روز است» با تاریخ شمسی.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Ardal_Widget_Product_Price_Date extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-product-price-date';
	}

	public function get_title() {
		return 'اردال — تاریخ به‌روزرسانی قیمت';
	}

	public function get_icon() {
		return 'eicon-calendar';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'تاریخ', 'شمسی', 'قیمت', 'به‌روزرسانی', 'jalali', 'price', 'date' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'محتوا' )
		);

		$this->add_control(
			'date_source',
			array(
				'label'       => 'مبنای تاریخ',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'today',
				'options'     => array(
					'today'    => 'امروز',
					'modified' => 'آخرین ویرایش محصول',
					'custom'   => 'تاریخ ثابت',
				),
				'description' => '«امروز» هر روز خودش به‌روز می‌شود. «آخرین ویرایش محصول» تاریخ واقعی آخرین تغییر محصول را نشان می‌دهد.',
			)
		);

		$this->add_control(
			'custom_date',
			array(
				'label'       => 'تاریخ ثابت (میلادی)',
				'type'        => Controls_Manager::DATE_TIME,
				'picker_options' => array( 'enableTime' => false ),
				'condition'   => array( 'date_source' => 'custom' ),
			)
		);

		$this->add_control(
			'date_format',
			array(
				'label'   => 'نحوه نمایش تاریخ',
				'type'    => Controls_Manager::SELECT,
				'default' => 'long',
				'options' => Ardal_Jalali::formats(),
			)
		);

		$this->add_control(
			'text_before',
			array(
				'label'   => 'متن قبل از تاریخ',
				'type'    => Controls_Manager::TEXT,
				'default' => 'قیمت محصول به تاریخ',
			)
		);

		$this->add_control(
			'text_after',
			array(
				'label'   => 'متن بعد از تاریخ',
				'type'    => Controls_Manager::TEXT,
				'default' => 'به‌روز است',
			)
		);

		$this->add_control(
			'show_icon',
			array(
				'label'        => 'نمایش آیکون',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'boxed',
			array(
				'label'        => 'داخل کادر',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
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

		$this->add_responsive_control(
			'font_size',
			array(
				'label'          => 'اندازه متن',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 9, 'max' => 20 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 12.5 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 12 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-price-date' => 'font-size:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => 'رنگ متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-price-date' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'date_color',
			array(
				'label'     => 'رنگ تاریخ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-price-date__value' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bg_color',
			array(
				'label'     => 'رنگ کادر',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-price-date--boxed' => 'background:{{VALUE}};',
				),
				'condition' => array( 'boxed' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => 'چینش',
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array( 'title' => 'راست', 'icon' => 'eicon-text-align-right' ),
					'center'     => array( 'title' => 'وسط', 'icon' => 'eicon-text-align-center' ),
					'flex-end'   => array( 'title' => 'چپ', 'icon' => 'eicon-text-align-left' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .ard-price-date' => 'justify-content:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'full_width',
			array(
				'label'          => 'عرض کادر',
				'type'           => Controls_Manager::SELECT,
				'default'        => 'fit-content',
				'mobile_default' => '100%',
				'options'        => array(
					'fit-content' => 'به اندازه محتوا',
					'100%'        => 'تمام عرض',
				),
				'selectors'      => array(
					'{{WRAPPER}} .ard-price-date' => 'width:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s         = $this->get_settings_for_display();
		$timestamp = $this->resolve_timestamp( $s );

		if ( ! $timestamp ) {
			$this->render_empty( 'تاریخی برای نمایش نیست. مبنای تاریخ را انتخاب کنید یا این ویجت را روی قالب تک‌محصول بگذارید.' );

			return;
		}

		$date    = Ardal_Jalali::format( $timestamp, $s['date_format'] );
		$classes = array( 'ard-price-date' );

		if ( 'yes' === $s['boxed'] ) {
			$classes[] = 'ard-price-date--boxed';
		}
		?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<?php if ( 'yes' === $s['show_icon'] ) : ?>
				<span class="ard-price-date__icon" aria-hidden="true">
					<?php echo Ardal_Helpers::icon( 'clock', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
			<?php endif; ?>

			<?php if ( '' !== trim( (string) $s['text_before'] ) ) : ?>
				<span><?php echo esc_html( $s['text_before'] ); ?></span>
			<?php endif; ?>

			<time class="ard-price-date__value" datetime="<?php echo esc_attr( gmdate( 'Y-m-d', $timestamp ) ); ?>"><?php echo esc_html( $date ); ?></time>

			<?php if ( '' !== trim( (string) $s['text_after'] ) ) : ?>
				<span><?php echo esc_html( $s['text_after'] ); ?></span>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * زمانی که باید نمایش داده شود.
	 *
	 * @param array $s
	 * @return int صفر یعنی چیزی برای نمایش نیست.
	 */
	private function resolve_timestamp( $s ) {
		if ( 'custom' === $s['date_source'] ) {
			$raw = trim( (string) $s['custom_date'] );

			return '' !== $raw ? (int) strtotime( $raw ) : 0;
		}

		if ( 'modified' === $s['date_source'] ) {
			$product = Ardal_Product::current();

			if ( ! $product ) {
				return 0;
			}

			$modified = $product->get_date_modified();

			if ( ! $modified ) {
				$modified = $product->get_date_created();
			}

			// getOffsetTimestamp زمان را به وقت محلی سایت می‌دهد، هم‌جنس با
			// چیزی که Ardal_Jalali انتظار دارد.
			return $modified ? (int) $modified->getOffsetTimestamp() : 0;
		}

		return Ardal_Jalali::now();
	}
}
