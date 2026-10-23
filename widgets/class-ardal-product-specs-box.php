<?php
/**
 * ویجت مشخصات محصول در باکس جمع‌شونده: ویژگی‌های قابل‌نمایش محصول به‌صورت
 * ردیف‌های عنوان/مقدار.
 *
 * اندازهٔ حالت بسته «تعداد ردیف» است نه ارتفاع پیکسلی، تا «پنج ردیف اول»
 * با هر فاصله و قلمی درست بماند.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ARDAL_PATH . 'includes/class-ardal-product-info-widget.php';

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Product_Specs_Box extends Ardal_Product_Info_Widget {

	public function get_name() {
		return 'ardal-product-specs-box';
	}

	public function get_title() {
		return 'اردال — مشخصات محصول (باکس جمع‌شونده)';
	}

	public function get_icon() {
		return 'eicon-product-info';
	}

	public function get_keywords() {
		return array( 'اردال', 'مشخصات', 'ویژگی', 'محصول', 'product', 'specs', 'attributes' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'محتوا' )
		);

		$this->add_control(
			'notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => 'ویژگی‌های «قابل مشاهده در صفحه محصول» محصول جاری را نمایش می‌دهد. در ویرایشگر، آخرین محصول برای پیش‌نمایش استفاده می‌شود.',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'show_sku',
			array(
				'label'   => 'نمایش شناسه محصول (SKU)',
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'sku_label',
			array(
				'label'     => 'عنوان ردیف شناسه',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'شناسه محصول',
				'condition' => array( 'show_sku' => 'yes' ),
			)
		);

		$this->add_title_control();
		$this->add_empty_control( 'مشخصاتی برای این محصول ثبت نشده است.' );

		$this->end_controls_section();

		$this->add_collapse_section(
			function () {
				$this->add_control(
					'visible_rows',
					array(
						'label'     => 'تعداد ردیف در حالت بسته',
						'type'      => Controls_Manager::NUMBER,
						'min'       => 1,
						'max'       => 30,
						'default'   => 5,
						'condition' => array( 'collapse' => 'yes' ),
					)
				);

				$this->add_control(
					'peek',
					array(
						'label'       => 'نمایش بخشی از ردیف بعدی',
						'type'        => Controls_Manager::SLIDER,
						'size_units'  => array( 'px' ),
						'range'       => array( 'px' => array( 'min' => 0, 'max' => 80 ) ),
						'default'     => array( 'unit' => 'px', 'size' => 32 ),
						'description' => 'ارتفاعی از ردیف‌های پنهان که زیر لایه محو دیده می‌شود.',
						'condition'   => array( 'collapse' => 'yes' ),
					)
				);
			}
		);

		$this->add_box_style_section();
		$this->add_title_style_section();

		// ---------------------------------------------------------------- ردیف‌ها
		$this->start_controls_section(
			'section_style_rows',
			array(
				'label' => 'ردیف‌ها',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'row_layout',
			array(
				'label'                => 'چیدمان',
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'row'    => array( 'title' => 'کنار هم', 'icon' => 'eicon-h-align-stretch' ),
					'column' => array( 'title' => 'زیر هم', 'icon' => 'eicon-v-align-stretch' ),
				),
				'selectors_dictionary' => array(
					'row'    => 'flex-direction: row; align-items: center;',
					// زیر هم: عرض ستون عنوان دیگر معنا ندارد.
					'column' => 'flex-direction: column; align-items: stretch; --ard-pspec-kw: auto;',
				),
				'selectors'            => array( '{{WRAPPER}} .ard-pspec__row' => '{{VALUE}}' ),
			)
		);

		$this->add_responsive_control(
			'row_gap',
			array(
				'label'      => 'فاصله بین ردیف‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pi' => '--ard-pspec-gap: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'row_min_height',
			array(
				'label'      => 'حداقل ارتفاع ردیف',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 120 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pspec__row' => 'min-height: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'row_padding',
			array(
				'label'      => 'فاصله داخلی ردیف',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array( '{{WRAPPER}} .ard-pspec__row' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'row_radius',
			array(
				'label'      => 'گردی گوشه ردیف',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 30 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pspec__row' => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'row_bg_heading',
			array(
				'label'     => 'پس‌زمینه ردیف (گرادیان)',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'row_bg_from',
			array(
				'label'       => 'رنگ سمت راست',
				'type'        => Controls_Manager::COLOR,
				'description' => 'برای رنگ یکدست، هر دو رنگ را یکی بگذارید.',
				'selectors'   => array( '{{WRAPPER}} .ard-pi' => '--ard-pspec-from: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'row_bg_to',
			array(
				'label'     => 'رنگ سمت چپ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pi' => '--ard-pspec-to: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'row_bg_hover',
			array(
				'label'     => 'رنگ هاور ردیف',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pspec__row:hover' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'row_border',
				'selector'  => '{{WRAPPER}} .ard-pspec__row',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		// ---------------------------------------------------------------- عنوان ویژگی
		$this->start_controls_section(
			'section_style_label',
			array(
				'label' => 'عنوان ویژگی',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typo',
				'selector' => '{{WRAPPER}} .ard-pspec__k',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => 'رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pi' => '--ard-pspec-k: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'label_width',
			array(
				'label'      => 'عرض ستون عنوان',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array( 'min' => 60, 'max' => 400 ),
					'%'  => array( 'min' => 10, 'max' => 70 ),
				),
				'selectors'  => array( '{{WRAPPER}} .ard-pi' => '--ard-pspec-kw: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		// ---------------------------------------------------------------- مقدار ویژگی
		$this->start_controls_section(
			'section_style_value',
			array(
				'label' => 'مقدار ویژگی',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'value_typo',
				'selector' => '{{WRAPPER}} .ard-pspec__v',
			)
		);

		$this->add_control(
			'value_color',
			array(
				'label'     => 'رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pi' => '--ard-pspec-v: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		$this->add_toggle_style_section();
		$this->add_empty_style_section();
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$product = Ardal_Product::current();

		if ( ! $product ) {
			if ( Ardal_Product::is_editor() ) {
				$this->render_empty_box( $s, 'specs' );
			}

			return;
		}

		$rows = Ardal_Product::attributes( $product, 0, true );
		$sku  = 'yes' === $s['show_sku'] ? (string) $product->get_sku() : '';

		if ( '' !== $sku ) {
			$rows[] = array(
				'key'   => (string) $s['sku_label'],
				'value' => $sku,
			);
		}

		if ( ! $rows ) {
			$this->render_empty_box( $s, 'specs' );

			return;
		}

		$html = '<dl class="ard-pspec">';

		foreach ( $rows as $row ) {
			$html .= '<div class="ard-pspec__row">'
				. '<dt class="ard-pspec__k">' . esc_html( $row['key'] ) . '</dt>'
				. '<dd class="ard-pspec__v">' . esc_html( Ardal_Helpers::fa_digits( $row['value'] ) ) . '</dd>'
				. '</div>';
		}

		$html .= '</dl>';

		$visible = max( 1, (int) $s['visible_rows'] );

		// چیزی برای جمع شدن نیست؛ دکمه اصلاً ساخته نشود.
		if ( count( $rows ) <= $visible ) {
			$s['collapse'] = '';
		}

		$this->render_shell(
			$s,
			'specs',
			$html,
			array(
				'ard-pi-mode' => 'rows',
				'ard-pi-rows' => $visible,
				'ard-pi-peek' => isset( $s['peek']['size'] ) ? (int) $s['peek']['size'] : 32,
			)
		);
	}
}
