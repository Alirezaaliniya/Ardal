<?php
/**
 * ویجت مشخصات کوتاه محصول: شبکه‌ای از کلید/مقدار با نقطهٔ رنگی.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Product_Specs extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-product-specs';
	}

	public function get_title() {
		return 'اردال — مشخصات کوتاه محصول';
	}

	public function get_icon() {
		return 'eicon-bullet-list';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'مشخصات', 'ویژگی', 'specs', 'attributes' );
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
				'description' => 'حالت خودکار ویژگی‌های محصول را به‌ترتیب می‌خواند.',
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'     => 'حداکثر تعداد',
				'type'      => Controls_Manager::NUMBER,
				'default'   => 6,
				'min'       => 2,
				'max'       => 20,
				'condition' => array( 'source' => 'auto' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'spec_key',
			array(
				'label'   => 'عنوان',
				'type'    => Controls_Manager::TEXT,
				'default' => 'برند',
			)
		);

		$repeater->add_control(
			'spec_value',
			array(
				'label'   => 'مقدار',
				'type'    => Controls_Manager::TEXT,
				'default' => 'پرو-فیت',
			)
		);

		$this->add_control(
			'quick_specs',
			array(
				'label'       => 'مشخصات',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ spec_key }}}',
				'condition'   => array( 'source' => 'manual' ),
				'default'     => array(
					array( 'spec_key' => 'برند', 'spec_value' => 'پرو-فیت' ),
					array( 'spec_key' => 'جنس رویه', 'spec_value' => 'مش مهندسی‌شده' ),
					array( 'spec_key' => 'زیره', 'spec_value' => 'لاستیک ضدسایش' ),
					array( 'spec_key' => 'وزن', 'spec_value' => '۲۴۵ گرم (سایز ۴۲)' ),
					array( 'spec_key' => 'کاربری', 'spec_value' => 'دویدن و تردمیل' ),
					array( 'spec_key' => 'گارانتی', 'spec_value' => '۱۸ ماه اصالت کالا' ),
				),
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
			'min_column',
			array(
				'label'          => 'حداقل عرض هر ستون',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 140, 'max' => 420 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 230 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 150 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-quick' => 'grid-template-columns:repeat(auto-fit,minmax({{SIZE}}{{UNIT}},1fr));',
				),
			)
		);

		$this->add_responsive_control(
			'row_gap',
			array(
				'label'      => 'فاصله سطرها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 2, 'max' => 30 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 10 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-prod-quick' => 'row-gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'key_width',
			array(
				'label'          => 'عرض ستون عنوان',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 50, 'max' => 180 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 82 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 70 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-quick__key' => 'min-width:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'dot_color',
			array(
				'label'     => 'رنگ نقطه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-prod-quick__dot' => 'background:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'value_color',
			array(
				'label'     => 'رنگ مقدار',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-prod-quick__val' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'divider',
			array(
				'label'        => 'خط جداکننده بالا',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'prefix_class' => 'ard-specs-divider-',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( 'manual' === $s['source'] ) {
			$specs = array();

			foreach ( (array) $s['quick_specs'] as $spec ) {
				if ( '' === trim( (string) $spec['spec_key'] ) ) {
					continue;
				}

				$specs[] = array(
					'key'   => $spec['spec_key'],
					'value' => $spec['spec_value'],
				);
			}
		} else {
			$product = Ardal_Product::current();

			if ( ! $product ) {
				$this->render_empty( 'محصولی پیدا نشد. این ویجت را روی قالب تک‌محصول بگذارید یا حالت دستی را انتخاب کنید.' );

				return;
			}

			$specs = Ardal_Product::attributes( $product, max( 2, (int) $s['limit'] ) );
		}

		if ( empty( $specs ) ) {
			$this->render_empty( 'این محصول ویژگی‌ای برای نمایش ندارد. ویژگی اضافه کنید یا حالت دستی را انتخاب کنید.' );

			return;
		}
		?>
		<div class="ard-prod-quick">
			<?php foreach ( $specs as $spec ) : ?>
				<div class="ard-prod-quick__item">
					<span class="ard-prod-quick__dot"></span>
					<span class="ard-prod-quick__key"><?php echo esc_html( $spec['key'] ); ?></span>
					<span class="ard-prod-quick__val"><?php echo esc_html( $spec['value'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
