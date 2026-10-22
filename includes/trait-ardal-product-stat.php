<?php
/**
 * بخش‌های مشترک ویجت‌های «آمار کوتاه محصول» (امتیاز، تعداد نظر).
 *
 * هر دو ویجت یک ساختار دارند: آیکون، عدد و برچسب، با لینکی اختیاری که به بخش
 * نظرات می‌برد. ساختار و کنترل‌های استایل یکسان‌اند تا کنار هم (و کنار دکمهٔ
 * علاقه‌مندی در حالت «درون‌خطی») یکدست دیده شوند.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;

trait Ardal_Product_Stat {

	/**
	 * آیکون‌های پیش‌فرض؛ خطی و هم‌وزن با قلب علاقه‌مندی.
	 *
	 * @param string $name
	 * @return string
	 */
	protected static function stat_svg( $name ) {
		$paths = array(
			'star' => '<path d="M12 3.6l2.55 5.17 5.7.83-4.13 4.02.98 5.68L12 16.62l-5.1 2.68.98-5.68L3.75 9.6l5.7-.83z"/>',
			'chat' => '<path d="M12 3.5a8.5 8.5 0 0 0-7.43 12.63L3.5 20.5l4.5-1.1A8.5 8.5 0 1 0 12 3.5z"/><path d="M8.5 10.3h7M8.5 13.7h4.5"/>',
		);

		return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $paths[ $name ] . '</svg>';
	}

	/**
	 * کنترل آیکون (خالی = آیکون پیش‌فرض) و لینک.
	 *
	 * @param string $link_default reviews|none|custom
	 */
	protected function add_stat_common_controls( $link_default = 'reviews' ) {
		$this->add_control(
			'icon',
			array(
				'label'       => 'آیکون',
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'description' => 'خالی بماند، آیکون پیش‌فرض اردال نمایش داده می‌شود.',
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'hide_icon',
			array(
				'label'        => 'بدون آیکون',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'link_to',
			array(
				'label'       => 'کلیک',
				'type'        => Controls_Manager::SELECT,
				'default'     => $link_default,
				'separator'   => 'before',
				'options'     => array(
					'reviews' => 'رفتن به بخش نظرات',
					'custom'  => 'لینک دلخواه',
					'none'    => 'بدون لینک',
				),
				'description' => '«بخش نظرات» تب نظرات ویجت تب‌های اردال (یا تب‌های خود ووکامرس) را باز می‌کند و صفحه را تا آنجا می‌برد.',
			)
		);

		$this->add_control(
			'link',
			array(
				'label'     => 'لینک',
				'type'      => Controls_Manager::URL,
				'condition' => array( 'link_to' => 'custom' ),
			)
		);

		$this->add_control(
			'jump_offset',
			array(
				'label'       => 'فاصله از بالای صفحه',
				'type'        => Controls_Manager::NUMBER,
				'default'     => 100,
				'min'         => 0,
				'max'         => 400,
				'description' => 'برای هدر چسبان؛ به پیکسل.',
				'condition'   => array( 'link_to' => 'reviews' ),
			)
		);
	}

	/**
	 * تب استایل مشترک.
	 */
	protected function add_stat_style_controls() {
		// ---------------------------------------------------------------- آیتم
		$this->start_controls_section(
			'section_style_item',
			array(
				'label' => 'آیتم',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'justify',
			array(
				'label'     => 'جای افقی',
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array( 'title' => 'راست', 'icon' => 'eicon-h-align-right' ),
					'center'     => array( 'title' => 'وسط', 'icon' => 'eicon-h-align-center' ),
					'flex-end'   => array( 'title' => 'چپ', 'icon' => 'eicon-h-align-left' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .ard-pstat-wrap' => 'justify-content:{{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typo',
				'selector' => '{{WRAPPER}} .ard-pstat',
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => 'فاصلهٔ آیکون و متن',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 30 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pstat' => '--ard-pstat-gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_item' );

		foreach ( array(
			''       => 'عادی',
			'-hover' => 'هاور',
		) as $state => $label ) {
			$key = '' === $state ? 'normal' : 'hover';

			$this->start_controls_tab( 'tab_item_' . $key, array( 'label' => $label ) );

			$this->add_control(
				'ink_' . $key,
				array(
					'label'     => 'رنگ متن',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .ard-pstat' => '--ard-pstat-ink' . $state . ':{{VALUE}};',
					),
				)
			);

			$this->add_control(
				'icon_ink_' . $key,
				array(
					'label'     => 'رنگ آیکون',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .ard-pstat' => '--ard-pstat-icon' . $state . ':{{VALUE}};',
					),
				)
			);

			$this->add_control(
				'bg_' . $key,
				array(
					'label'     => 'پس‌زمینه',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .ard-pstat' => '--ard-pstat-bg' . $state . ':{{VALUE}};',
					),
				)
			);

			$this->add_control(
				'border_c_' . $key,
				array(
					'label'     => 'رنگ حاشیه',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .ard-pstat' => '--ard-pstat-line' . $state . ':{{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'pad',
			array(
				'label'      => 'فاصلهٔ داخلی',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .ard-pstat' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'border',
				'selector' => '{{WRAPPER}} .ard-pstat',
				// رنگ از تب‌های عادی/هاور می‌آید.
				'exclude'  => array( 'color' ),
			)
		);

		$this->add_responsive_control(
			'radius',
			array(
				'label'      => 'گردی گوشه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pstat' => 'border-radius:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// ---------------------------------------------------------------- آیکون و عدد
		$this->start_controls_section(
			'section_style_parts',
			array(
				'label' => 'آیکون و عدد',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => 'اندازهٔ آیکون',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 10, 'max' => 48 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pstat' => '--ard-pstat-icon-size:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_stroke',
			array(
				'label'       => 'ضخامت خط آیکون',
				'type'        => Controls_Manager::SLIDER,
				'range'       => array( 'px' => array( 'min' => 0.8, 'max' => 3, 'step' => 0.1 ) ),
				'description' => 'فقط برای آیکون‌های پیش‌فرض.',
				'selectors'   => array(
					'{{WRAPPER}} .ard-pstat__icon svg' => 'stroke-width:{{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'num_typo',
				'label'    => 'تایپوگرافی عدد',
				'selector' => '{{WRAPPER}} .ard-pstat__num',
			)
		);

		$this->add_control(
			'num_ink',
			array(
				'label'     => 'رنگ عدد',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pstat__num' => 'color:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * خروجی یک آیتم.
	 *
	 * @param array  $s        تنظیمات ویجت.
	 * @param string $modifier rating|reviews
	 * @param string $default  نام آیکون پیش‌فرض.
	 * @param string $text     قالب متن با {n}؛ escape نشده.
	 * @param string $num      عدد آماده؛ escape نشده.
	 * @param array  $opts     extra (HTML آماده قبل از متن)، label (aria-label)، class.
	 */
	protected function render_stat( $s, $modifier, $default, $text, $num, $opts = array() ) {
		$opts = wp_parse_args( $opts, array( 'extra' => '', 'label' => '', 'class' => '' ) );

		$icon = '';

		if ( 'yes' !== $s['hide_icon'] ) {
			if ( ! empty( $s['icon']['value'] ) ) {
				ob_start();
				Icons_Manager::render_icon( $s['icon'], array( 'aria-hidden' => 'true' ) );
				$icon = ob_get_clean();
			} else {
				$icon = self::stat_svg( $default );
			}
		}

		// عدد داخل span خودش می‌رود تا جدا استایل بگیرد؛ بقیهٔ قالب متن ساده است.
		$parts = explode( '{n}', (string) $text, 2 );
		$body  = esc_html( $parts[0] );

		if ( isset( $parts[1] ) ) {
			$body .= '<span class="ard-pstat__num">' . esc_html( $num ) . '</span>' . esc_html( $parts[1] );
		}

		$tag   = 'span';
		$attrs = '';

		if ( 'reviews' === $s['link_to'] ) {
			$tag   = 'a';
			$attrs = ' href="#reviews" data-ard-jump="reviews" data-offset="' . esc_attr( (int) $s['jump_offset'] ) . '"';
		} elseif ( 'custom' === $s['link_to'] && ! empty( $s['link']['url'] ) ) {
			$tag   = 'a';
			$attrs = ' ' . Ardal_Helpers::link_attrs( $s['link'] );
		}

		// برچسب فقط روی لینک معنا دارد؛ span ساده نقشی ندارد که صفحه‌خوان بخواندش.
		if ( '' !== $opts['label'] && 'a' === $tag ) {
			$attrs .= ' aria-label="' . esc_attr( $opts['label'] ) . '"';
		}

		$class = trim( 'ard-pstat ard-pstat--' . $modifier . ' ' . $opts['class'] );

		printf(
			'<div class="ard-pstat-wrap"><%1$s class="%2$s"%3$s>%4$s%5$s<span class="ard-pstat__text">%6$s</span></%1$s></div>',
			$tag, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- ثابت.
			esc_attr( $class ),
			$attrs, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- بالا escape شده.
			'' !== $icon ? '<span class="ard-pstat__icon">' . $icon . '</span>' : '', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$opts['extra'], // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- ورودی آماده و امن.
			$body // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- تکه‌ها escape شده‌اند.
		);
	}
}
