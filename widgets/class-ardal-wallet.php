<?php
/**
 * ویجت کیف پول — کارت موجودی و افزایش اعتبار.
 *
 * مبلغ‌ها، حداقل/حداکثر و فعال‌بودن شارژ از تب «کیف پول» تنظیمات اردال
 * می‌آیند؛ اینجا متن‌ها و ظاهر کارت تعیین می‌شود.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Wallet extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-wallet';
	}

	public function get_title() {
		return 'اردال — کیف پول (شارژ)';
	}

	public function get_icon() {
		return 'eicon-wallet';
	}

	public function get_keywords() {
		return array( 'اردال', 'کیف پول', 'شارژ', 'اعتبار', 'wallet', 'credit', 'topup' );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-wallet' );
	}

	public function get_script_depends() {
		return array( 'ardal', 'ardal-wallet' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_content', array( 'label' => 'کارت کیف پول' ) );

		$this->add_control(
			'title',
			array(
				'label'   => 'عنوان موجودی',
				'type'    => Controls_Manager::TEXT,
				'default' => 'موجودی کیف پول',
			)
		);

		foreach ( array(
			'show_art'     => 'تصویر کیف پول',
			'show_balance' => 'موجودی',
			'show_quick'   => 'مبلغ‌های پیشنهادی',
			'show_limits'  => 'حداقل و حداکثر مبلغ',
			'show_note'    => 'متن راهنما',
		) as $key => $label ) {
			$this->add_control(
				$key,
				array(
					'label'        => $label,
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'return_value' => 'yes',
				)
			);
		}

		$this->add_control(
			'quick',
			array(
				'label'       => 'مبلغ‌های پیشنهادی همین کارت',
				'type'        => Controls_Manager::TEXT,
				'placeholder' => Ardal_Wallet::option( 'quick_amounts' ),
				'description' => 'با کاما جدا کنید. خالی بماند، مبلغ‌های تنظیمات اردال.',
				'condition'   => array( 'show_quick' => 'yes' ),
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'placeholder',
			array(
				'label'   => 'متن داخل ورودی مبلغ',
				'type'    => Controls_Manager::TEXT,
				'default' => 'مبلغ دلخواه را وارد کن',
			)
		);

		$this->add_control(
			'button',
			array(
				'label'   => 'متن دکمه',
				'type'    => Controls_Manager::TEXT,
				'default' => 'افزایش اعتبار',
			)
		);

		$this->add_control(
			'note',
			array(
				'label'       => 'متن راهنما',
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => Ardal_Wallet::option( 'topup_note' ),
				'description' => 'خالی بماند، متن تنظیمات اردال.',
				'condition'   => array( 'show_note' => 'yes' ),
			)
		);

		$this->add_control(
			'login_text',
			array(
				'label'     => 'پیام برای مهمان',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'برای دیدن و شارژ کیف پول وارد حساب کاربری شو.',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'login_button',
			array(
				'label'   => 'متن دکمهٔ ورود',
				'type'    => Controls_Manager::TEXT,
				'default' => 'ورود به حساب',
			)
		);

		$this->end_controls_section();

		/* ---------------- استایل ---------------- */

		$this->start_controls_section( 'section_style_card', array( 'label' => 'کارت', 'tab' => Controls_Manager::TAB_STYLE ) );

		$this->add_control( 'card_bg', array( 'label' => 'زمینه', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-card' => '--ard-wl-card-bg:{{VALUE}};' ) ) );

		$this->add_group_control( Group_Control_Border::get_type(), array( 'name' => 'card_border', 'selector' => '{{WRAPPER}} .ard-wl-card' ) );

		$this->add_responsive_control(
			'card_radius',
			array(
				'label'      => 'گردی گوشه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 48 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-wl-card' => '--ard-wl-card-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'card_pad',
			array(
				'label'      => 'فاصله داخلی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-wl-card' => '--ard-wl-card-pad:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'card_gap',
			array(
				'label'      => 'فاصله بخش‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-wl-card' => 'gap:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), array( 'name' => 'card_shadow', 'selector' => '{{WRAPPER}} .ard-wl-card' ) );

		$this->add_responsive_control(
			'art_size',
			array(
				'label'      => 'اندازه تصویر',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 40, 'max' => 220 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-wl-card' => '--ard-wl-art:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'show_art' => 'yes' ),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section( 'section_style_balance', array( 'label' => 'موجودی', 'tab' => Controls_Manager::TAB_STYLE, 'condition' => array( 'show_balance' => 'yes' ) ) );

		$this->add_control( 'cap_color', array( 'label' => 'رنگ عنوان', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-card__cap' => 'color:{{VALUE}};' ) ) );
		$this->add_control( 'amount_color', array( 'label' => 'رنگ مبلغ', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-card' => '--ard-wl-amount:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'amount_typo', 'label' => 'تایپوگرافی مبلغ', 'selector' => '{{WRAPPER}} .ard-wl-card__amount' ) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_style_chips', array( 'label' => 'مبلغ‌های پیشنهادی', 'tab' => Controls_Manager::TAB_STYLE, 'condition' => array( 'show_quick' => 'yes' ) ) );

		$this->add_control( 'chip_bg', array( 'label' => 'زمینه', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-card' => '--ard-wl-chip-bg:{{VALUE}};' ) ) );
		$this->add_control( 'chip_border', array( 'label' => 'حاشیه', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-card' => '--ard-wl-chip-border:{{VALUE}};' ) ) );
		$this->add_control( 'chip_ink', array( 'label' => 'رنگ متن', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-card' => '--ard-wl-chip-ink:{{VALUE}};' ) ) );
		$this->add_control( 'chip_on', array( 'label' => 'رنگ انتخاب‌شده', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-card' => '--ard-wl-chip-on:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'chip_typo', 'selector' => '{{WRAPPER}} .ard-wl-chip' ) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_style_form', array( 'label' => 'ورودی و دکمه', 'tab' => Controls_Manager::TAB_STYLE ) );

		$this->add_responsive_control(
			'field_h',
			array(
				'label'      => 'ارتفاع ورودی و دکمه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 36, 'max' => 72 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-wl-card' => '--ard-wl-btn-h:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control( 'input_bg', array( 'label' => 'زمینه ورودی', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-card' => '--ard-wl-input-bg:{{VALUE}};' ) ) );
		$this->add_control( 'input_border', array( 'label' => 'حاشیه ورودی', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-card' => '--ard-wl-input-border:{{VALUE}};' ) ) );

		$this->add_responsive_control(
			'field_radius',
			array(
				'label'      => 'گردی ورودی و دکمه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 36 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-wl-card' => '--ard-wl-input-radius:{{SIZE}}{{UNIT}};--ard-wl-btn-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control( 'btn_bg', array( 'label' => 'زمینه دکمه', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-card' => '--ard-wl-btn-bg:{{VALUE}};' ), 'separator' => 'before' ) );
		$this->add_control( 'btn_bg_hover', array( 'label' => 'زمینه دکمه در هاور', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-card' => '--ard-wl-btn-bg-hover:{{VALUE}};' ) ) );
		$this->add_control( 'btn_ink', array( 'label' => 'رنگ متن دکمه', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-card' => '--ard-wl-btn-ink:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'btn_typo', 'selector' => '{{WRAPPER}} .ard-wl-card__submit' ) );

		$this->end_controls_section();

		$this->start_controls_section( 'section_style_note', array( 'label' => 'متن راهنما', 'tab' => Controls_Manager::TAB_STYLE, 'condition' => array( 'show_note' => 'yes' ) ) );

		$this->add_control( 'note_bg', array( 'label' => 'زمینه', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-card' => '--ard-wl-note-bg:{{VALUE}};' ) ) );
		$this->add_control( 'note_ink', array( 'label' => 'رنگ متن', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-card' => '--ard-wl-note-ink:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'note_typo', 'selector' => '{{WRAPPER}} .ard-wl-card__note' ) );

		$this->end_controls_section();
	}

	protected function render() {
		if ( ! class_exists( 'Ardal_Wallet' ) || ! Ardal_Wallet::is_enabled() ) {
			$this->render_empty( 'کیف پول در تنظیمات اردال خاموش است.' );

			return;
		}

		$s    = $this->get_settings_for_display();
		$args = array(
			'anchor' => 'ard-wl-' . $this->get_id(),
			'quick'  => (string) $s['quick'],
			'note'   => (string) $s['note'],
		);

		foreach ( array( 'show_art', 'show_balance', 'show_quick', 'show_limits', 'show_note' ) as $key ) {
			$args[ $key ] = 'yes' === $s[ $key ] ? 'yes' : 'no';
		}

		foreach ( array( 'title', 'placeholder', 'button', 'login_text', 'login_button' ) as $key ) {
			if ( '' !== trim( (string) $s[ $key ] ) ) {
				$args[ $key ] = $s[ $key ];
			}
		}

		echo Ardal_Wallet_Render::card( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
