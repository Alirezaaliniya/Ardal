<?php
/**
 * ویجت توضیحات دسته/فروشگاه — معمولاً زیر صفحه‌بندی می‌نشیند.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Archive_Description extends Ardal_Archive_Widget_Base {

	public function get_name() {
		return 'ardal-archive-description';
	}

	public function get_title() {
		return 'اردال — توضیحات دسته';
	}

	public function get_icon() {
		return 'eicon-post-content';
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_desc',
			array( 'label' => 'محتوا' )
		);

		$this->add_control(
			'source',
			array(
				'label'       => 'منبع متن',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'auto',
				'options'     => array(
					'auto'   => 'توضیح دسته جاری (و در فروشگاه، متن صفحه فروشگاه)',
					'term'   => 'فقط توضیح دسته',
					'custom' => 'متن دلخواه',
				),
				'label_block' => true,
			)
		);

		$this->add_control(
			'custom_text',
			array(
				'label'     => 'متن',
				'type'      => Controls_Manager::WYSIWYG,
				'default'   => '',
				'condition' => array( 'source' => 'custom' ),
			)
		);

		$this->add_archive_title_controls( 'درباره این دسته‌بندی' );

		$this->add_control(
			'collapse',
			array(
				'label'        => 'جمع‌کردن متن‌های بلند',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => 'بله',
				'label_off'    => 'خیر',
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'collapse_height',
			array(
				'label'      => 'ارتفاع پیش از باز شدن',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 80, 'max' => 600 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 180 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-desc__body' => '--ard-desc-h:{{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'collapse' => 'yes' ),
			)
		);

		$this->add_control(
			'more_text',
			array(
				'label'     => 'متن «بیشتر»',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'ادامه مطلب',
				'condition' => array( 'collapse' => 'yes' ),
			)
		);

		$this->add_control(
			'less_text',
			array(
				'label'     => 'متن «کمتر»',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'بستن',
				'condition' => array( 'collapse' => 'yes' ),
			)
		);

		$this->add_control(
			'boxed',
			array(
				'label'        => 'قاب سفید',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ استایل */

		$this->add_archive_title_style();

		$this->start_controls_section(
			'section_style_desc',
			array(
				'label' => 'متن',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => 'رنگ متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-desc__body' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typo',
				'selector' => '{{WRAPPER}} .ard-desc__body',
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => 'رنگ تیترهای داخل متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-desc__body h1, {{WRAPPER}} .ard-desc__body h2, {{WRAPPER}} .ard-desc__body h3, {{WRAPPER}} .ard-desc__body h4' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => 'رنگ لینک‌ها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-desc__body a' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'align',
			array(
				'label'     => 'چیدمان متن',
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'justify',
				'options'   => array(
					'right'   => array( 'title' => 'راست', 'icon' => 'eicon-text-align-right' ),
					'justify' => array( 'title' => 'هم‌تراز', 'icon' => 'eicon-text-align-justify' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .ard-desc__body' => 'text-align:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'more_color',
			array(
				'label'     => 'رنگ دکمه ادامه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-desc__more' => 'color:{{VALUE}};',
				),
				'condition' => array( 'collapse' => 'yes' ),
			)
		);

		$this->end_controls_section();

		$this->add_box_controls( '.ard-desc--boxed', 'desc_box', 'قاب', array( 'top' => 22, 'right' => 24, 'bottom' => 22, 'left' => 24 ) );
	}

	/**
	 * متن نهایی.
	 *
	 * @param array $s
	 * @return string HTML.
	 */
	protected function content( $s ) {
		if ( 'custom' === $s['source'] ) {
			return (string) $s['custom_text'];
		}

		$term = get_queried_object();

		if ( $term instanceof WP_Term && '' !== trim( (string) $term->description ) ) {
			return function_exists( 'wc_format_content' )
				? wc_format_content( $term->description )
				: wpautop( $term->description );
		}

		if ( 'term' === $s['source'] ) {
			return '';
		}

		$shop_id = Ardal_Helpers::has_woo() ? wc_get_page_id( 'shop' ) : 0;

		if ( $shop_id > 0 ) {
			$page = get_post( $shop_id );

			if ( $page && '' !== trim( (string) $page->post_content ) ) {
				return apply_filters( 'the_content', $page->post_content );
			}
		}

		return '';
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$html = $this->content( $s );

		if ( '' === trim( wp_strip_all_tags( $html ) ) ) {
			$this->render_empty( 'برای این آرشیو توضیحی ثبت نشده است.' );

			return;
		}

		$classes = array( 'ard-desc' );

		if ( 'yes' === $s['boxed'] ) {
			$classes[] = 'ard-desc--boxed';
		}

		if ( 'yes' === $s['collapse'] ) {
			$classes[] = 'ard-desc--collapse';
		}

		printf( '<div class="%s" data-ard-desc>', esc_attr( implode( ' ', $classes ) ) );

		$this->render_archive_title( $s );

		echo '<div class="ard-desc__body" data-ard-desc-body>';
		echo wp_kses_post( $html );
		echo '</div>';

		if ( 'yes' === $s['collapse'] ) {
			printf(
				'<button type="button" class="ard-desc__more" data-ard-desc-toggle data-more="%s" data-less="%s" hidden>%s%s</button>',
				esc_attr( $s['more_text'] ),
				esc_attr( $s['less_text'] ),
				esc_html( $s['more_text'] ),
				Ardal_Archive::icon( 'chev-d', 16, 2 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		echo '</div>';
	}
}
