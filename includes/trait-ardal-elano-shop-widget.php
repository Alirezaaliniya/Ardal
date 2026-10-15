<?php
/**
 * کنترل‌ها و رندر مشترک ویجت‌های سبد، تسویه و تشکر الانو.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

trait Ardal_Elano_Shop_Widget {

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-wallet', 'ardal-elano-shop' );
	}

	public function get_script_depends() {
		return array( 'ardal', 'ardal-wallet', 'ardal-shop' );
	}

	/**
	 * کنترل‌های متنی از روی آرایهٔ کلید => [برچسب، نوع].
	 *
	 * @param array $texts
	 */
	protected function elano_texts( $texts ) {
		$d = Ardal_Shop_Elano::defaults();

		foreach ( $texts as $key => $row ) {
			$this->add_control(
				'elc_' . $key,
				array(
					'label'       => $row[0],
					'type'        => isset( $row[1] ) ? $row[1] : Controls_Manager::TEXT,
					'default'     => isset( $d[ $key ] ) && is_string( $d[ $key ] ) ? $d[ $key ] : '',
					'label_block' => true,
					'description' => isset( $row[2] ) ? $row[2] : '',
				)
			);
		}
	}

	/**
	 * @param array $keys
	 */
	protected function elano_switches( $keys ) {
		$d = Ardal_Shop_Elano::defaults();

		foreach ( $keys as $key => $label ) {
			$this->add_control(
				'elc_' . $key,
				array(
					'label'        => $label,
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'default'      => isset( $d[ $key ] ) ? $d[ $key ] : '',
				)
			);
		}
	}

	/**
	 * بخش رنگ، فونت و اندازه (همان متغیرهای --el-* پنل الانو).
	 */
	protected function elano_style_controls() {
		$this->start_controls_section(
			'section_elc_style',
			array(
				'label' => 'رنگ و فونت',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'elc_palette',
			array(
				'label'        => 'رنگ اصلی از پالت اردال',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'description'  => 'روشن باشد، به‌جای صورتی الانو رنگ اصلی تنظیمات اردال استفاده می‌شود.',
				'selectors'    => array(
					'{{WRAPPER}} .ard-elc' => '--el-primary:var(--ard-primary);--el-primary-2:var(--ard-primary-light, var(--ard-primary));--el-tint:color-mix(in srgb, var(--ard-primary) 12%, #fff);--el-glow:color-mix(in srgb, var(--ard-primary) 30%, transparent);',
				),
			)
		);

		$colors = array(
			'primary'   => 'رنگ اصلی',
			'primary-2' => 'رنگ دوم گرادیان',
			'danger'    => 'رنگ برچسب تخفیف',
			'bg'        => 'زمینهٔ صفحه',
			'card'      => 'زمینهٔ کارت‌ها',
			'text'      => 'رنگ متن',
			'border'    => 'رنگ خطوط',
			'soft'      => 'زمینهٔ ملایم',
			'tint'      => 'زمینهٔ آیکون سربرگ',
		);

		foreach ( $colors as $var => $label ) {
			$this->add_control(
				'elc_c_' . str_replace( '-', '_', $var ),
				array(
					'label'     => $label,
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( '{{WRAPPER}} .ard-elc' => '--el-' . $var . ':{{VALUE}};' ),
				)
			);
		}

		$this->add_control(
			'elc_font',
			array(
				'label'       => 'فونت',
				'type'        => Controls_Manager::FONT,
				'description' => 'خالی بماند، فونت سراسری اردال.',
				'selectors'   => array( '{{WRAPPER}} .ard-elc' => '--el-font:"{{VALUE}}", var(--ard-font, sans-serif);' ),
				'separator'   => 'before',
			)
		);

		$this->add_responsive_control(
			'elc_pad',
			array(
				'label'      => 'فاصلهٔ داخلی',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array( '{{WRAPPER}} .ard-elc' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'elc_max',
			array(
				'label'      => 'حداکثر عرض',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'       => array( 'px' => array( 'min' => 600, 'max' => 1920 ) ),
				'description' => 'خالی بماند، تمام عرض کانتینر.',
				'selectors'   => array( '{{WRAPPER}} .ard-elc' => '--elc-max:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'elc_side',
			array(
				'label'      => 'عرض ستون کناری',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 260, 'max' => 460 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-elc' => '--elc-side:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * تنظیمات ویجت به کلیدهای Ardal_Shop_Elano.
	 *
	 * @param array $s
	 * @return array
	 */
	protected function elano_settings( $s ) {
		$out = array();

		foreach ( array_keys( Ardal_Shop_Elano::defaults() ) as $key ) {
			if ( ! array_key_exists( 'elc_' . $key, $s ) ) {
				continue;
			}

			$value = $s[ 'elc_' . $key ];

			// سوییچ خاموش المنتور رشتهٔ خالی است؛ باید «خاموش» بماند، نه پیش‌فرض.
			if ( in_array( $key, array( 'show_free', 'show_off_badge', 'delivery', 'delivery_req', 'thanks_track', 'thanks_details' ), true ) ) {
				$value = 'yes' === $value ? 'yes' : 'no';
			}

			if ( is_array( $value ) && isset( $value['url'] ) ) {
				$value = $value['url'];
			}

			$out[ $key ] = $value;
		}

		return $out;
	}

	/**
	 * سبد ووکامرس در درخواست رندر ویرایشگر هم بارگذاری شود.
	 *
	 * @return bool
	 */
	protected function elano_cart_ready() {
		if ( ! Ardal_Helpers::has_woo() ) {
			return false;
		}

		if ( is_null( WC()->cart ) && function_exists( 'wc_load_cart' ) ) {
			wc_load_cart();
		}

		return ! is_null( WC()->cart );
	}

	/**
	 * @return bool
	 */
	protected function elano_editing() {
		return class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->editor->is_edit_mode();
	}
}
