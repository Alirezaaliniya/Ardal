<?php
/**
 * ویجت عنوان محصول به‌همراه زیرعنوان لاتین.
 *
 * زیرعنوان انگلیسی با یک کلید سراسری در تنظیمات افزونه روشن/خاموش می‌شود، پس
 * لازم نیست در تک‌تک صفحه‌ها دستی مدیریت شود.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Product_Title extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-product-title';
	}

	public function get_title() {
		return 'اردال — عنوان محصول';
	}

	public function get_icon() {
		return 'eicon-t-letter';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'عنوان', 'نام محصول', 'title', 'product' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'محتوا' )
		);

		$this->add_control(
			'source',
			array(
				'label'       => 'منبع',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'auto',
				'options'     => array(
					'auto'   => 'خودکار از محصول جاری',
					'manual' => 'دستی',
				),
				'description' => 'حالت خودکار نام محصول جاری را می‌خواند.',
			)
		);

		$this->add_control(
			'title',
			array(
				'label'     => 'عنوان',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'کفش راحتی پرو-فیت مدل Aero X2 مردانه',
				'condition' => array( 'source' => 'manual' ),
			)
		);

		// همان فهرست مشترک افزونه؛ پیش‌فرض h1 چون عنوان اصلی صفحهٔ محصول است.
		$this->add_tag_control( 'title_tag', 'تگ عنوان', 'h1' );

		$this->add_control(
			'subtitle',
			array(
				'label'       => 'عنوان انگلیسی',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Pro-Fit Aero X2 Running Shoes — Men',
				'description' => $this->subtitle_note(),
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
			'title_size',
			array(
				'label'          => 'اندازه عنوان',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 14, 'max' => 44 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 24 ),
				'tablet_default' => array( 'unit' => 'px', 'size' => 21 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 19 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-hero__title' => 'font-size:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => 'رنگ عنوان',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-prod-hero__title' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'subtitle_size',
			array(
				'label'          => 'اندازه عنوان انگلیسی',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 9, 'max' => 20 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 13 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 12 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-hero__subtitle' => 'font-size:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'subtitle_color',
			array(
				'label'     => 'رنگ عنوان انگلیسی',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-prod-hero__subtitle' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => 'چینش',
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'right'  => array( 'title' => 'راست', 'icon' => 'eicon-text-align-right' ),
					'center' => array( 'title' => 'وسط', 'icon' => 'eicon-text-align-center' ),
					'left'   => array( 'title' => 'چپ', 'icon' => 'eicon-text-align-left' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .ard-prod-title-block' => 'text-align:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$product = 'manual' === $s['source'] ? null : Ardal_Product::current();

		if ( 'manual' !== $s['source'] && ! $product ) {
			$this->render_empty( 'محصولی پیدا نشد. این ویجت را روی قالب تک‌محصول بگذارید یا حالت دستی را انتخاب کنید.' );

			return;
		}

		$title = $product ? $product->get_name() : (string) $s['title'];

		if ( '' === trim( $title ) ) {
			$this->render_empty( 'عنوانی برای نمایش نیست.' );

			return;
		}

		// زیرعنوان فقط وقتی می‌آید که در تنظیمات افزونه روشن باشد.
		$subtitle = Ardal_Product::subtitle( $product, $s['subtitle'] );
		$tag      = self::tag( $s['title_tag'], 'h1' );
		?>
		<div class="ard-prod-title-block">
			<<?php echo esc_html( $tag ); ?> class="ard-prod-hero__title"><?php echo esc_html( $title ); ?></<?php echo esc_html( $tag ); ?>>

			<?php if ( '' !== $subtitle ) : ?>
				<div class="ard-prod-hero__subtitle"><?php echo esc_html( $subtitle ); ?></div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * توضیح فیلد زیرعنوان، بسته به وضعیت کلید سراسری.
	 *
	 * @return string
	 */
	private function subtitle_note() {
		if ( Ardal_Product::subtitle_on() ) {
			return 'نمایش عنوان انگلیسی در تنظیمات اردال روشن است. اگر محصول فیلد سفارشی داشته باشد، مقدار آن جای این متن را می‌گیرد.';
		}

		return 'برای دیده‌شدن، گزینهٔ «نمایش عنوان انگلیسی محصول» را در تنظیمات اردال روشن کنید.';
	}
}
