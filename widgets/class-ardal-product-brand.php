<?php
/**
 * ویجت باکس برند محصول: لوگو، نام برند و لینک «همه محصولات این برند».
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Ardal_Widget_Product_Brand extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-product-brand';
	}

	public function get_title() {
		return 'اردال — برند محصول';
	}

	public function get_icon() {
		return 'eicon-globe';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'برند', 'سازنده', 'brand', 'product' );
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
				'description' => 'حالت خودکار برند را از تاکسونومی برند محصول می‌خواند.',
			)
		);

		$this->add_control(
			'brand_name',
			array(
				'label'     => 'نام برند',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'برند پرو-فیت',
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'brand_logo',
			array(
				'label'     => 'لوگوی برند',
				'type'      => Controls_Manager::MEDIA,
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'brand_link',
			array(
				'label'       => 'لینک برند',
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://',
				'condition'   => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'brand_link_text',
			array(
				'label'   => 'متن لینک',
				'type'    => Controls_Manager::TEXT,
				'default' => 'همه محصولات این برند',
			)
		);

		$this->add_control(
			'logo_placeholder',
			array(
				'label'       => 'متن جای‌گزین لوگو',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'لوگوی برند',
				'description' => 'اگر برند لوگو نداشته باشد، این متن در کادر لوگو نوشته می‌شود.',
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
			'logo_width',
			array(
				'label'          => 'عرض کادر لوگو',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 40, 'max' => 160 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 70 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 58 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-brand__logo' => 'width:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'logo_height',
			array(
				'label'          => 'ارتفاع کادر لوگو',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 24, 'max' => 100 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 36 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 32 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-brand__logo' => 'height:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'border_color',
			array(
				'label'     => 'رنگ کادر',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-prod-brand' => 'border-color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => 'رنگ نام برند',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-prod-brand__name' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => 'رنگ لینک',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-prod-brand__link' => 'color:{{VALUE}};',
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
					'{{WRAPPER}} .ard-prod-brand' => 'width:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( 'manual' === $s['source'] ) {
			$brand = array(
				'name' => (string) $s['brand_name'],
				'link' => isset( $s['brand_link']['url'] ) ? $s['brand_link']['url'] : '',
				'logo' => isset( $s['brand_logo']['url'] ) ? $s['brand_logo']['url'] : '',
			);
		} else {
			$product = Ardal_Product::current();

			if ( ! $product ) {
				$this->render_empty( 'محصولی پیدا نشد. این ویجت را روی قالب تک‌محصول بگذارید یا حالت دستی را انتخاب کنید.' );

				return;
			}

			$brand = Ardal_Product::brand( $product );
		}

		if ( '' === trim( (string) $brand['name'] ) ) {
			$this->render_empty( 'این محصول برند ندارد. برندی به محصول بدهید یا حالت دستی را انتخاب کنید.' );

			return;
		}
		?>
		<div class="ard-prod-brand">
			<div class="ard-prod-brand__logo">
				<?php if ( '' !== $brand['logo'] ) : ?>
					<img src="<?php echo esc_url( $brand['logo'] ); ?>" alt="<?php echo esc_attr( $brand['name'] ); ?>" loading="lazy" decoding="async" />
				<?php else : ?>
					<span><?php echo esc_html( $s['logo_placeholder'] ); ?></span>
				<?php endif; ?>
			</div>
			<div class="ard-prod-brand__info">
				<div class="ard-prod-brand__name"><?php echo esc_html( $brand['name'] ); ?></div>
				<?php if ( '' !== $brand['link'] && '' !== trim( (string) $s['brand_link_text'] ) ) : ?>
					<a class="ard-prod-brand__link" href="<?php echo esc_url( $brand['link'] ); ?>">
						<?php echo esc_html( $s['brand_link_text'] ); ?>
						<?php echo Ardal_Helpers::icon( 'chevron-left', 13 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
