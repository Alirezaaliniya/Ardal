<?php
/**
 * کلاس پایه‌ی ویجت‌های آرشیو فروشگاه.
 *
 * فقط چیزهایی که بین چند ویجت آرشیو مشترک‌اند اینجاست: دسته‌ی المنتور،
 * دارایی‌ها، کنترل‌های «قاب» و پیام‌های حالت ویرایش.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

abstract class Ardal_Archive_Widget_Base extends Ardal_Widget_Base {

	/**
	 * @return array
	 */
	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_ARCHIVE );
	}

	/**
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'ardal', 'ardal-archive' );
	}

	/**
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'ardal', 'ardal-archive' );
	}

	/**
	 * @return array
	 */
	public function get_keywords() {
		return array( 'اردال', 'آرشیو', 'فروشگاه', 'ardal', 'archive', 'shop' );
	}

	/**
	 * کنترل‌های قاب سفید (زمینه، گردی، سایه، فاصله داخلی).
	 *
	 * @param string $selector سلکتور عنصر قاب، نسبت به WRAPPER.
	 * @param string $tab_id   شناسه یکتای بخش.
	 * @param string $label    عنوان بخش.
	 * @param array  $padding  مقدار پیش‌فرض فاصله داخلی.
	 */
	protected function add_box_controls( $selector, $tab_id = 'box', $label = 'قاب', $padding = array( 'top' => 20, 'right' => 20, 'bottom' => 20, 'left' => 20 ) ) {
		$this->start_controls_section(
			'section_style_' . $tab_id,
			array(
				'label' => $label,
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			$tab_id . '_bg',
			array(
				'label'     => 'رنگ زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $selector => 'background:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			$tab_id . '_radius',
			array(
				'label'      => 'گردی گوشه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $selector => 'border-radius:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			$tab_id . '_padding',
			array(
				'label'      => 'فاصله داخلی',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'default'    => array_merge( $padding, array( 'unit' => 'px', 'isLinked' => false ) ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $selector => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => $tab_id . '_shadow',
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * کنترل‌های عنوان کوچک بالای ویجت‌های آرشیو.
	 *
	 * برخلاف عنوان بخش‌های صفحه اصلی، اینجا عنوان وسط‌چین و ساده است.
	 *
	 * @param string $default متن پیش‌فرض.
	 */
	protected function add_archive_title_controls( $default ) {
		$this->add_control(
			'show_title',
			array(
				'label'        => 'نمایش عنوان',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => 'بله',
				'label_off'    => 'خیر',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => 'عنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => $default,
				'label_block' => true,
				'condition'   => array( 'show_title' => 'yes' ),
			)
		);

		// همان فهرست تگِ بقیهٔ ویجت‌ها، تا انتخاب‌ها در کل افزونه یکی باشد.
		$this->add_tag_control(
			'title_tag',
			'تگ عنوان',
			'h2',
			array( 'condition' => array( 'show_title' => 'yes' ) )
		);

		$this->add_control(
			'title_icon',
			array(
				'label'        => 'آیکون کنار عنوان',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'show_title' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'title_align',
			array(
				'label'     => 'چیدمان عنوان',
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => array(
					'flex-start' => array( 'title' => 'راست', 'icon' => 'eicon-text-align-right' ),
					'center'     => array( 'title' => 'وسط', 'icon' => 'eicon-text-align-center' ),
					'flex-end'   => array( 'title' => 'چپ', 'icon' => 'eicon-text-align-left' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .ard-arch__title' => 'justify-content:{{VALUE}};',
				),
				'condition' => array( 'show_title' => 'yes' ),
			)
		);
	}

	/**
	 * کنترل‌های استایل عنوان.
	 */
	protected function add_archive_title_style() {
		$this->start_controls_section(
			'section_style_title',
			array(
				'label'     => 'عنوان',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_title' => 'yes' ),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => 'رنگ متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-arch__title' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_icon_color',
			array(
				'label'     => 'رنگ آیکون',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-arch__title-icon' => 'color:{{VALUE}};',
				),
				'condition' => array( 'title_icon' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typo',
				'selector' => '{{WRAPPER}} .ard-arch__title',
			)
		);

		$this->add_responsive_control(
			'title_gap',
			array(
				'label'      => 'فاصله تا محتوا',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-arch__title' => 'margin-bottom:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * چاپ عنوان.
	 *
	 * @param array  $s    تنظیمات.
	 * @param string $icon نام آیکون.
	 */
	protected function render_archive_title( $s, $icon = 'spark' ) {
		if ( 'yes' !== ( $s['show_title'] ?? '' ) || '' === trim( (string) ( $s['title'] ?? '' ) ) ) {
			return;
		}

		$tag = self::tag( $s['title_tag'] ?? 'h2', 'h2' );

		printf( '<%s class="ard-arch__title">', esc_html( $tag ) );

		if ( 'yes' === ( $s['title_icon'] ?? '' ) ) {
			printf(
				'<span class="ard-arch__title-icon" aria-hidden="true">%s</span>',
				Ardal_Archive::icon( $icon, 20, 1.6 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		printf( '<span>%s</span>', esc_html( $s['title'] ) );
		printf( '</%s>', esc_html( $tag ) );
	}

	/**
	 * ویژگی‌های ایجکس روی ریشهٔ ویجت.
	 *
	 * جاوااسکریپت با دیدن data-ard-ajax روی هر ویجتی، کنترل‌کنندهٔ سراسری را
	 * روشن می‌کند؛ پس فرقی ندارد کدام ویجت آن را اعلام کرده باشد.
	 *
	 * @param array $s تنظیمات ویجت.
	 * @return string رشتهٔ ویژگی‌ها، از قبل escape شده.
	 */
	protected function ajax_attrs( $s ) {
		if ( 'yes' !== ( $s['ajax'] ?? '' ) ) {
			return '';
		}

		$out = ' data-ard-ajax="1"';

		$extra = trim( (string) ( $s['ajax_regions'] ?? '' ) );

		if ( '' !== $extra ) {
			$out .= ' data-ard-ajax-regions="' . esc_attr( $extra ) . '"';
		}

		if ( 'yes' === ( $s['ajax_scroll'] ?? '' ) ) {
			$out .= ' data-ard-ajax-scroll="1"';
		}

		return $out;
	}

	/**
	 * پیام حالت ویرایش وقتی ووکامرس نیست.
	 *
	 * @return bool آیا پیام چاپ شد.
	 */
	protected function require_woo() {
		if ( Ardal_Helpers::has_woo() ) {
			return false;
		}

		$this->render_empty( 'این ویجت به ووکامرس نیاز دارد.' );

		return true;
	}
}
