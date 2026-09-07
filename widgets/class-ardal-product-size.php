<?php
/**
 * ویجت انتخاب سایز محصول به‌همراه لینک راهنمای سایز.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Product_Size extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-product-size';
	}

	public function get_title() {
		return 'اردال — انتخاب سایز محصول';
	}

	public function get_icon() {
		return 'eicon-resize';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'سایز', 'اندازه', 'متغیر', 'size', 'variation' );
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
				'description' => 'حالت خودکار گزینه‌ها را از ویژگی سایزِ محصول می‌خواند و سایزهای ناموجود را خط‌خورده و غیرفعال نشان می‌دهد.',
			)
		);

		$this->add_control(
			'size_attribute',
			array(
				'label'       => 'نام ویژگی سایز',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'سایز',
				'description' => 'نام یا اسلاگ ویژگی محصول. مثلاً «سایز» یا «pa_size».',
				'condition'   => array( 'source' => 'auto' ),
			)
		);

		$this->add_control(
			'label',
			array(
				'label'   => 'برچسب',
				'type'    => Controls_Manager::TEXT,
				'default' => 'سایز',
			)
		);

		$this->add_control(
			'size_guide_text',
			array(
				'label'   => 'متن راهنمای سایز',
				'type'    => Controls_Manager::TEXT,
				'default' => 'راهنمای انتخاب سایز',
			)
		);

		$this->add_control(
			'size_guide_link',
			array(
				'label'       => 'لینک راهنمای سایز',
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://',
				'condition'   => array( 'size_guide_text!' => '' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'size_label',
			array(
				'label'   => 'سایز',
				'type'    => Controls_Manager::TEXT,
				'default' => '۴۲',
			)
		);

		$repeater->add_control(
			'is_available',
			array(
				'label'        => 'موجود',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'sizes',
			array(
				'label'       => 'سایزها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ size_label }}}',
				'condition'   => array( 'source' => 'manual' ),
				'default'     => array(
					array( 'size_label' => '۴۰', 'is_available' => 'yes' ),
					array( 'size_label' => '۴۱', 'is_available' => 'yes' ),
					array( 'size_label' => '۴۲', 'is_available' => 'yes' ),
					array( 'size_label' => '۴۳', 'is_available' => 'yes' ),
					array( 'size_label' => '۴۴', 'is_available' => '' ),
					array( 'size_label' => '۴۵', 'is_available' => 'yes' ),
					array( 'size_label' => '۴۶', 'is_available' => '' ),
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
			'chip_min_width',
			array(
				'label'          => 'حداقل عرض هر گزینه',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 32, 'max' => 110 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 56 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 48 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-size' => 'min-width:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'          => 'فاصله بین گزینه‌ها',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 4, 'max' => 24 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 9 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 7 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-sizes' => 'gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'active_bg',
			array(
				'label'     => 'رنگ گزینه انتخاب‌شده',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-prod-size.is-active' => 'background:{{VALUE}};border-color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'guide_color',
			array(
				'label'     => 'رنگ لینک راهنما',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-prod-variants__guide' => 'color:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( 'manual' === $s['source'] ) {
			$options = array();

			foreach ( (array) $s['sizes'] as $sz ) {
				if ( '' === trim( (string) $sz['size_label'] ) ) {
					continue;
				}

				$options[] = array(
					'label'     => $sz['size_label'],
					'available' => 'yes' === ( isset( $sz['is_available'] ) ? $sz['is_available'] : 'yes' ),
				);
			}
		} else {
			$product = Ardal_Product::current();

			if ( ! $product ) {
				$this->render_empty( 'محصولی پیدا نشد. این ویجت را روی قالب تک‌محصول بگذارید یا حالت دستی را انتخاب کنید.' );

				return;
			}

			$variants = Ardal_Product::variation_options( $product, $s['size_attribute'] );
			$options  = $variants['options'];
		}

		if ( empty( $options ) ) {
			$this->render_empty( sprintf( 'این محصول ویژگی «%s» ندارد. ویژگی را به محصول اضافه کنید یا حالت دستی را انتخاب کنید.', $s['size_attribute'] ) );

			return;
		}

		$has_guide = '' !== trim( (string) $s['size_guide_text'] ) && ! empty( $s['size_guide_link']['url'] );
		?>
		<div class="ard-prod-variants ard-prod-variants--sizes" data-ard-size-group>
			<div class="ard-prod-variants__header">
				<span class="ard-prod-variants__label"><?php echo esc_html( $s['label'] ); ?>:</span>
				<?php if ( $has_guide ) : ?>
					<a class="ard-prod-variants__guide"<?php echo Ardal_Helpers::link_attrs( $s['size_guide_link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $s['size_guide_text'] ); ?></a>
				<?php endif; ?>
			</div>
			<div class="ard-prod-sizes">
				<?php
				$active_set = false;

				foreach ( $options as $sz ) :
					$active = ! $active_set && $sz['available'];

					if ( $active ) {
						$active_set = true;
					}
					?>
					<button
						type="button"
						class="ard-prod-size<?php echo $active ? ' is-active' : ''; ?><?php echo $sz['available'] ? '' : ' is-disabled'; ?>"
						data-size-label="<?php echo esc_attr( $sz['label'] ); ?>"
						<?php echo $sz['available'] ? '' : ' disabled aria-disabled="true"'; ?>
					><?php echo esc_html( $sz['label'] ); ?></button>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
