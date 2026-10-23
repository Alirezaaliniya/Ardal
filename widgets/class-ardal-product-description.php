<?php
/**
 * ویجت توضیحات محصول در باکس جمع‌شونده با «مشاهده بیشتر».
 *
 * متن با قالب‌بند خود ووکامرس (wc_format_content) ساخته می‌شود، نه the_content:
 * داخل قالب المنتور آن فیلتر خروجی صفحه‌ساز همین پست را جایگزین می‌کند و ممکن
 * است صفحه را داخل خودش رندر کند.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ARDAL_PATH . 'includes/class-ardal-product-info-widget.php';

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Product_Description extends Ardal_Product_Info_Widget {

	public function get_name() {
		return 'ardal-product-description';
	}

	public function get_title() {
		return 'اردال — توضیحات محصول';
	}

	public function get_icon() {
		return 'eicon-product-description';
	}

	public function get_keywords() {
		return array( 'اردال', 'توضیحات', 'محصول', 'product', 'description', 'content' );
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
				'raw'             => 'توضیحات محصول جاری را نمایش می‌دهد — در قالب تک‌محصول استفاده کنید. در ویرایشگر، آخرین محصول برای پیش‌نمایش استفاده می‌شود.',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'source',
			array(
				'label'   => 'منبع متن',
				'type'    => Controls_Manager::SELECT,
				'default' => 'description',
				'options' => array(
					'description' => 'توضیحات کامل',
					'short'       => 'توضیحات کوتاه',
				),
			)
		);

		$this->add_title_control();
		$this->add_empty_control( 'توضیحاتی برای این محصول ثبت نشده است.' );

		$this->end_controls_section();

		$this->add_collapse_section(
			function () {
				$this->add_responsive_control(
					'collapsed_height',
					array(
						'label'      => 'ارتفاع در حالت بسته',
						'type'       => Controls_Manager::SLIDER,
						'size_units' => array( 'px' ),
						'range'      => array( 'px' => array( 'min' => 40, 'max' => 600 ) ),
						'default'    => array( 'unit' => 'px', 'size' => 96 ),
						'selectors'  => array( '{{WRAPPER}} .ard-pi' => '--ard-pi-collapsed: {{SIZE}}{{UNIT}};' ),
						'condition'  => array( 'collapse' => 'yes' ),
					)
				);
			}
		);

		$this->add_box_style_section();
		$this->add_title_style_section();

		$this->start_controls_section(
			'section_style_text',
			array(
				'label' => 'متن',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typo',
				'selector' => '{{WRAPPER}} .ard-pdesc',
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => 'رنگ متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pi' => '--ard-pi-text: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => 'رنگ تیترهای داخل متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pdesc :is(h1,h2,h3,h4,h5,h6,strong,b)' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => 'رنگ لینک‌ها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pdesc a' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'paragraph_gap',
			array(
				'label'      => 'فاصله پاراگراف‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pdesc > * + *' => 'margin-top: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'text_align',
			array(
				'label'     => 'چینش',
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'right'   => array( 'title' => 'راست', 'icon' => 'eicon-text-align-right' ),
					'center'  => array( 'title' => 'وسط', 'icon' => 'eicon-text-align-center' ),
					'left'    => array( 'title' => 'چپ', 'icon' => 'eicon-text-align-left' ),
					'justify' => array( 'title' => 'هم‌تراز', 'icon' => 'eicon-text-align-justify' ),
				),
				'selectors' => array( '{{WRAPPER}} .ard-pdesc' => 'text-align: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'image_radius',
			array(
				'label'      => 'گردی گوشه تصاویر',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pdesc img' => 'border-radius: {{SIZE}}{{UNIT}};' ),
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
				$this->render_empty_box( $s, 'desc' );
			}

			return;
		}

		$raw = 'short' === $s['source'] ? $product->get_short_description() : $product->get_description();

		// «خالی» شامل توضیحاتی هم می‌شود که فقط تگ سرگردان یا &nbsp; دارند.
		$has_media = (bool) preg_match( '/<(img|video|iframe|embed)\b/i', (string) $raw );
		$text      = trim( html_entity_decode( wp_strip_all_tags( strip_shortcodes( (string) $raw ) ), ENT_QUOTES, 'UTF-8' ), " \t\n\r\0\x0B\xC2\xA0" );

		if ( '' === $text && ! $has_media ) {
			$this->render_empty_box( $s, 'desc' );

			return;
		}

		$html = '<div class="ard-pdesc">' . wc_format_content( $raw ) . '</div>';

		$this->render_shell( $s, 'desc', $html, array( 'ard-pi-mode' => 'height' ) );
	}
}
