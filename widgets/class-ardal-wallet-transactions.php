<?php
/**
 * ویجت تراکنش‌های کیف پول — فهرست یا جدول تراکنش‌ها، یا تاریخچهٔ کش‌بگ.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Wallet_Transactions extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-wallet-transactions';
	}

	public function get_title() {
		return 'اردال — تراکنش‌های کیف پول';
	}

	public function get_icon() {
		return 'eicon-table';
	}

	public function get_keywords() {
		return array( 'اردال', 'کیف پول', 'تراکنش', 'کش‌بگ', 'wallet', 'transactions', 'cashback' );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-wallet' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_content', array( 'label' => 'تراکنش‌ها' ) );

		$this->add_control(
			'mode',
			array(
				'label'   => 'محتوا',
				'type'    => Controls_Manager::SELECT,
				'default' => 'transactions',
				'options' => array(
					'transactions' => 'همهٔ تراکنش‌ها',
					'cashback'     => 'تاریخچهٔ کش‌بگ (با وضعیت و انقضا)',
				),
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => 'نمایش',
				'type'    => Controls_Manager::SELECT,
				'default' => 'list',
				'options' => array(
					'list'  => 'فهرست با آیکون',
					'table' => 'جدول (در موبایل کارت)',
				),
			)
		);

		$this->add_control(
			'types',
			array(
				'label'       => 'فقط این نوع‌ها',
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => Ardal_Wallet::type_names(),
				'description' => 'خالی بماند همه نمایش داده می‌شوند.',
				'condition'   => array( 'mode' => 'transactions' ),
			)
		);

		$this->add_control(
			'hide_cashback',
			array(
				'label'        => 'کش‌بگ‌ها جدا نمایش داده شوند (در این فهرست نباشند)',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition'    => array( 'mode' => 'transactions' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => 'عنوان',
				'type'    => Controls_Manager::TEXT,
				'default' => 'تراکنش‌های کیف پول',
			)
		);

		$this->add_control(
			'per_page',
			array(
				'label'   => 'تعداد در هر صفحه',
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 100,
				'default' => 10,
			)
		);

		$this->add_control(
			'pager',
			array(
				'label'        => 'صفحه‌بندی',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'empty',
			array(
				'label'   => 'متن حالت خالی',
				'type'    => Controls_Manager::TEXT,
				'default' => 'هنوز تراکنشی ثبت نشده است.',
			)
		);

		$this->add_control(
			'guest',
			array(
				'label'       => 'پیام برای مهمان',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => 'خالی بماند، برای مهمان چیزی نمایش داده نمی‌شود.',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section( 'section_style', array( 'label' => 'ظاهر', 'tab' => Controls_Manager::TAB_STYLE ) );

		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'title_typo', 'label' => 'تایپوگرافی عنوان', 'selector' => '{{WRAPPER}} .ard-wl-tx__title' ) );
		$this->add_control( 'title_color', array( 'label' => 'رنگ عنوان', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-tx__title' => 'color:{{VALUE}};' ) ) );
		$this->add_control( 'in_color', array( 'label' => 'رنگ واریز', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-tx' => '--ard-wl-in:{{VALUE}};' ), 'separator' => 'before' ) );
		$this->add_control( 'out_color', array( 'label' => 'رنگ برداشت', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-tx' => '--ard-wl-out:{{VALUE}};' ) ) );
		$this->add_control( 'divider', array( 'label' => 'رنگ خط جداکننده', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-tx' => '--ard-wl-divider:{{VALUE}};' ) ) );
		$this->add_control( 'head_bg', array( 'label' => 'زمینه سرستون جدول', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-wl-tx' => '--ard-wl-head-bg:{{VALUE}};' ), 'condition' => array( 'style' => 'table' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'row_typo', 'label' => 'تایپوگرافی ردیف', 'selector' => '{{WRAPPER}} .ard-wl-tx__label, {{WRAPPER}} .ard-wl-tx__table td', 'separator' => 'before' ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'amount_typo', 'label' => 'تایپوگرافی مبلغ', 'selector' => '{{WRAPPER}} .ard-wl-tx__amount' ) );

		$this->end_controls_section();
	}

	protected function render() {
		if ( ! class_exists( 'Ardal_Wallet' ) || ! Ardal_Wallet::is_enabled() ) {
			$this->render_empty( 'کیف پول در تنظیمات اردال خاموش است.' );

			return;
		}

		$s = $this->get_settings_for_display();

		if ( ! is_user_logged_in() ) {
			if ( '' !== trim( (string) $s['guest'] ) ) {
				echo '<p class="ard-wl-tx__empty">' . esc_html( $s['guest'] ) . '</p>';
			}

			return;
		}

		echo Ardal_Wallet_Render::transactions( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			array(
				'mode'          => 'cashback' === $s['mode'] ? 'cashback' : 'transactions',
				'style'         => 'table' === $s['style'] ? 'table' : 'list',
				'title'         => (string) $s['title'],
				'per_page'      => max( 1, (int) $s['per_page'] ),
				'hide_cashback' => 'yes' === $s['hide_cashback'] ? 'yes' : 'no',
				'types'         => array_filter( (array) $s['types'] ),
				'empty'         => (string) $s['empty'],
				'pager'         => 'yes' === $s['pager'] ? 'yes' : 'no',
				// هر ویجت پارامتر صفحه‌بندی خودش را دارد تا دو ویجت در یک صفحه تداخل نکنند.
				'page_key'      => 'ard_tx_' . $this->get_id(),
				'anchor'        => 'ard-wl-tx-' . $this->get_id(),
			)
		);
	}
}
