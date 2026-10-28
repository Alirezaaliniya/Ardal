<?php
/**
 * ویجت منوی دسته‌بندی اردال.
 *
 * دسکتاپ: ستون آیتم‌های سطح اول، و کنارش پنلی که زیردسته‌های آیتم فعال را
 * ستون‌به‌ستون نشان می‌دهد (سطح دوم عنوان ستون، سطح سوم فهرست زیرش).
 * موبایل: آکاردئون؛ زیر آیتم بازشده، کارت‌های سطح دوم و کارت «همه».
 *
 * ساختار، آیکون‌ها و زیردسته‌ها همه از «نمایش ← فهرست‌ها» می‌آیند؛ همان
 * فیلدهای اردال روی هر آیتم منو (آیکون آماده، آیکون آپلودی، تزریق خودکار
 * زیردسته‌ها) اینجا هم کار می‌کنند.
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

class Ardal_Widget_Category_Menu extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-category-menu';
	}

	public function get_title() {
		return 'اردال — منوی دسته‌بندی';
	}

	public function get_icon() {
		return 'eicon-menu-toggle';
	}

	public function get_keywords() {
		return array( 'اردال', 'منو', 'دسته', 'دسته‌بندی', 'category', 'menu', 'mega' );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-category-menu' );
	}

	public function get_script_depends() {
		return array( 'ardal-category-menu' );
	}

	protected function register_controls() {
		$menus = Ardal_Mega_Menu::menu_options();

		/* ------------------------------------------------ منو */

		$this->start_controls_section(
			'section_menu',
			array( 'label' => 'منو' )
		);

		if ( empty( $menus ) ) {
			$this->add_control(
				'no_menu',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf(
						'هنوز هیچ منویی ساخته نشده است. از <a href="%s" target="_blank">نمایش ← فهرست‌ها</a> یک منو بسازید، سپس همین‌جا انتخابش کنید.',
						esc_url( admin_url( 'nav-menus.php' ) )
					),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				)
			);
		} else {
			$this->add_control(
				'menu',
				array(
					'label'       => 'انتخاب منو',
					'type'        => Controls_Manager::SELECT,
					'options'     => $menus,
					'default'     => array_key_first( $menus ),
					'label_block' => true,
					'description' => sprintf(
						'سطح اول = دسته‌های اصلی، سطح دوم = زیردسته‌ها (عنوان ستون در دسکتاپ و کارت در موبایل)، سطح سوم = فهرست زیر هر ستون. آیکون هر آیتم در <a href="%s" target="_blank">نمایش ← فهرست‌ها</a>، بخش «تنظیمات اردال» آن آیتم انتخاب یا آپلود می‌شود.',
						esc_url( admin_url( 'nav-menus.php' ) )
					),
				)
			);
		}

		$this->add_control(
			'aria_label',
			array(
				'label'   => 'نام منو برای صفحه‌خوان',
				'type'    => Controls_Manager::TEXT,
				'default' => 'دسته‌بندی محصولات',
			)
		);

		$this->add_control(
			'show_arrow',
			array(
				'label'        => 'فلش کنار آیتم‌ها',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'mono_icons',
			array(
				'label'        => 'آیکون‌های آپلودی هم‌رنگ متن شوند',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'برای آیکون‌های تک‌رنگ (SVG/PNG شفاف). آیکون رنگی را خاموش کنید تا همان‌طور که هست نمایش داده شود.',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ دسکتاپ */

		$this->start_controls_section(
			'section_desktop',
			array( 'label' => 'دسکتاپ' )
		);

		$this->add_control(
			'desktop_open',
			array(
				'label'   => 'نمایش زیردسته‌ها با',
				'type'    => Controls_Manager::SELECT,
				'default' => 'hover',
				'options' => array(
					'hover' => 'رفتن ماوس روی آیتم (کلیک: رفتن به دسته)',
					'click' => 'کلیک روی آیتم',
				),
			)
		);

		$this->add_control(
			'desktop_active',
			array(
				'label'       => 'آیتم فعال در شروع',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'current',
				'options'     => array(
					'current' => 'دستهٔ صفحهٔ فعلی، وگرنه اولی',
					'first'   => 'همیشه اولین آیتم',
				),
				'label_block' => true,
			)
		);

		$this->add_control(
			'panel_all',
			array(
				'label'        => 'لینک «مشاهده همه» در پنل',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'panel_all_text',
			array(
				'label'       => 'متن لینک',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'مشاهده همه {title}',
				'description' => '{title} = نام دستهٔ اصلی.',
				'condition'   => array( 'panel_all' => 'yes' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ موبایل */

		$this->start_controls_section(
			'section_mobile',
			array( 'label' => 'موبایل و تبلت' )
		);

		$this->add_control(
			'mobile_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => 'زیر ۱۰۲۴ پیکسل، فهرست آکاردئونی می‌شود و زیردسته‌ها کارت‌هایی زیر آیتم بازشده‌اند.',
				'content_classes' => 'elementor-descriptor',
			)
		);

		$this->add_control(
			'mobile_single',
			array(
				'label'        => 'فقط یک آیتم باز بماند',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'mobile_first_open',
			array(
				'label'        => 'آیتم فعال در شروع باز باشد',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'all_card',
			array(
				'label'        => 'کارت «همه»',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'all_card_text',
			array(
				'label'       => 'متن کارت «همه»',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'همه {title}',
				'description' => '{title} = نام دستهٔ اصلی. لینکش همان لینک دستهٔ اصلی است.',
				'condition'   => array( 'all_card' => 'yes' ),
			)
		);

		$this->add_control(
			'all_card_icon',
			array(
				'label'     => 'آیکون کارت «همه»',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'grid',
				'options'   => Ardal_Helpers::icon_options(),
				'condition' => array( 'all_card' => 'yes' ),
			)
		);

		$this->add_control(
			'card_fallback',
			array(
				'label'       => 'کارت بدون آیکون',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'parent',
				'options'     => array(
					'parent' => 'آیکون دستهٔ اصلی را بگیرد',
					'none'   => 'بدون آیکون',
				),
				'label_block' => true,
				'condition'   => array(),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ نمایش */

		$this->start_controls_section(
			'section_display',
			array( 'label' => 'حالت نمایش و بازکننده' )
		);

		$this->add_control(
			'display_mode',
			array(
				'label'       => 'نمایش',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'inline',
				'options'     => array(
					'inline' => 'همیشه در صفحه',
					'popup'  => 'پنهان؛ با کلیک روی لینک یا کلاس باز شود',
				),
				'label_block' => true,
			)
		);

		$this->add_control(
			'popup_id',
			array(
				'label'       => 'شناسهٔ ناحیه',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'ard-catmenu',
				'condition'   => array( 'display_mode' => 'popup' ),
			)
		);

		$this->add_control(
			'popup_help',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => 'برای باز کردن، یکی از این‌ها کافی است:<br>• لینکی با نشانی <code>#ard-catmenu</code> (یا هر شناسه‌ای که بالا نوشته‌اید، با # جلویش)، مثلاً یک آیتم «پیوند دلخواه» در فهرست وردپرس یا لینک یک دکمهٔ المنتور.<br>• کلاس <code>ard-open-catmenu</code> روی هر ویجتی (تب «پیشرفته» ← «کلاس‌های CSS»).',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => array( 'display_mode' => 'popup' ),
			)
		);

		$this->add_control(
			'popup_title',
			array(
				'label'     => 'عنوان بالای پنجره (موبایل)',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'دسته‌بندی‌ها',
				'condition' => array( 'display_mode' => 'popup' ),
			)
		);

		$this->add_responsive_control(
			'popup_top',
			array(
				'label'      => 'فاصله پنجره از بالای صفحه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 300 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-cm' => '--ard-cm-top:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'display_mode' => 'popup' ),
			)
		);

		$this->add_responsive_control(
			'popup_width',
			array(
				'label'      => 'بیشترین پهنای پنجره',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vw' ),
				'range'      => array( 'px' => array( 'min' => 480, 'max' => 1600 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-cm' => '--ard-cm-popup-w:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'display_mode' => 'popup' ),
			)
		);

		$this->end_controls_section();

		$this->add_list_style_controls();
		$this->add_panel_style_controls();
		$this->add_card_style_controls();
		$this->add_popup_style_controls();
	}

	/* --------------------------------------------------------------------- */
	/* استایل                                                                  */
	/* --------------------------------------------------------------------- */

	/**
	 * آیتم‌های سطح اول.
	 */
	protected function add_list_style_controls() {
		$row = '{{WRAPPER}} .ard-cm__row';

		$this->start_controls_section(
			'section_style_list',
			array(
				'label' => 'آیتم‌های اصلی',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'list_width',
			array(
				'label'      => 'پهنای ستون آیتم‌ها (دسکتاپ)',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array( 'min' => 160, 'max' => 600 ),
					'%'  => array( 'min' => 15, 'max' => 60 ),
				),
				'selectors'  => array( '{{WRAPPER}} .ard-cm' => '--ard-cm-list-w:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'list_gap',
			array(
				'label'      => 'فاصله آیتم‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-cm' => '--ard-cm-gap:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'row_height',
			array(
				'label'      => 'ارتفاع آیتم',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 32, 'max' => 100 ) ),
				'selectors'  => array( $row => 'min-height:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'row_padding',
			array(
				'label'      => 'فاصله داخلی',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					$row => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'row_radius',
			array(
				'label'      => 'گردی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( $row => 'border-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'row_align',
			array(
				'label'     => 'جای متن',
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => array(
					'start'  => array( 'title' => 'کنار آیکون', 'icon' => 'eicon-text-align-right' ),
					'center' => array( 'title' => 'وسط', 'icon' => 'eicon-text-align-center' ),
				),
				'selectors' => array( '{{WRAPPER}} .ard-cm__label' => 'text-align:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'row_typo',
				'selector' => '{{WRAPPER}} .ard-cm__label',
			)
		);

		$this->add_responsive_control(
			'row_icon_size',
			array(
				'label'      => 'اندازه آیکون',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 10, 'max' => 48 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-cm__row .ard-cm__icon' => 'font-size:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'row_border_w',
			array(
				'label'      => 'ضخامت حاشیه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 4 ) ),
				'selectors'  => array( $row => 'border-width:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->start_controls_tabs( 'row_tabs' );

		$states = array(
			'normal' => array( 'عادی', '{{WRAPPER}} .ard-cm__item:not(.is-active) > .ard-cm__row' ),
			'hover'  => array( 'هاور', '{{WRAPPER}} .ard-cm__item:not(.is-active) > .ard-cm__row:hover' ),
			'active' => array( 'فعال', '{{WRAPPER}} .ard-cm__item.is-active > .ard-cm__row' ),
		);

		foreach ( $states as $state => $info ) {
			$this->start_controls_tab( 'row_tab_' . $state, array( 'label' => $info[0] ) );

			$this->add_control(
				'row_color_' . $state,
				array(
					'label'     => 'رنگ متن',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( $info[1] => 'color:{{VALUE}};' ),
				)
			);

			$this->add_control(
				'row_icon_color_' . $state,
				array(
					'label'     => 'رنگ آیکون',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( $info[1] . ' .ard-cm__icon' => 'color:{{VALUE}};' ),
				)
			);

			$this->add_control(
				'row_arrow_color_' . $state,
				array(
					'label'     => 'رنگ فلش',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( $info[1] . ' .ard-cm__arrow' => 'color:{{VALUE}};' ),
				)
			);

			if ( 'active' === $state ) {
				$this->add_control(
					'row_active_from',
					array(
						'label'     => 'رنگ زمینه (شروع گرادیان)',
						'type'      => Controls_Manager::COLOR,
						'selectors' => array( '{{WRAPPER}} .ard-cm' => '--ard-cm-active-from:{{VALUE}};' ),
					)
				);

				$this->add_control(
					'row_active_to',
					array(
						'label'     => 'رنگ زمینه (پایان گرادیان)',
						'type'      => Controls_Manager::COLOR,
						'selectors' => array( '{{WRAPPER}} .ard-cm' => '--ard-cm-active-to:{{VALUE}};' ),
					)
				);
			} else {
				$this->add_control(
					'row_bg_' . $state,
					array(
						'label'     => 'رنگ زمینه',
						'type'      => Controls_Manager::COLOR,
						'selectors' => array( $info[1] => 'background:{{VALUE}};' ),
					)
				);
			}

			$this->add_control(
				'row_border_color_' . $state,
				array(
					'label'     => 'رنگ حاشیه',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( $info[1] => 'border-color:{{VALUE}};' ),
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'row_shadow_' . $state,
					'selector' => $info[1],
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * پنل زیردسته‌های دسکتاپ.
	 */
	protected function add_panel_style_controls() {
		$this->start_controls_section(
			'section_style_panel',
			array(
				'label' => 'پنل زیردسته‌ها (دسکتاپ)',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'panel_bg',
			array(
				'label'     => 'رنگ زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-cm__panel' => 'background:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'panel_radius',
			array(
				'label'      => 'گردی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-cm__panel' => 'border-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'panel_padding',
			array(
				'label'      => 'فاصله داخلی',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-cm__panel' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'panel_cols',
			array(
				'label'     => 'تعداد ستون',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 6,
				'default'   => 3,
				'selectors' => array( '{{WRAPPER}} .ard-cm' => '--ard-cm-cols:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'panel_col_gap',
			array(
				'label'      => 'فاصله ستون‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 80 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-cm__cols' => 'column-gap:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'head_heading',
			array(
				'label'     => 'عنوان ستون (سطح دوم)',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'head_typo',
				'selector' => '{{WRAPPER}} .ard-cm__head',
			)
		);

		$this->add_control(
			'head_color',
			array(
				'label'     => 'رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-cm__head' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'head_hover',
			array(
				'label'     => 'رنگ هاور',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} a.ard-cm__head:hover' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'head_dot',
			array(
				'label'     => 'رنگ نقطه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-cm' => '--ard-cm-dot:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'sub_heading',
			array(
				'label'     => 'فهرست زیر ستون (سطح سوم)',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sub_typo',
				'selector' => '{{WRAPPER}} .ard-cm__sub',
			)
		);

		$this->add_control(
			'sub_color',
			array(
				'label'     => 'رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-cm__sub' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'sub_hover',
			array(
				'label'     => 'رنگ هاور',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-cm__sub:hover' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'sub_dot',
			array(
				'label'     => 'رنگ نقطه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-cm' => '--ard-cm-sub-dot:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'sub_gap',
			array(
				'label'      => 'فاصله ردیف‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 48 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-cm__subs' => 'gap:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * کارت‌های زیردسته در موبایل.
	 */
	protected function add_card_style_controls() {
		$card = '{{WRAPPER}} .ard-cm__card';

		$this->start_controls_section(
			'section_style_cards',
			array(
				'label' => 'کارت‌های زیردسته (موبایل)',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'card_cols',
			array(
				'label'          => 'تعداد ستون',
				'type'           => Controls_Manager::NUMBER,
				'min'            => 1,
				'max'            => 6,
				'default'        => 3,
				'mobile_default' => 3,
				'selectors'      => array( '{{WRAPPER}} .ard-cm' => '--ard-cm-card-cols:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'card_gap',
			array(
				'label'      => 'فاصله کارت‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 30 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-cm__grid' => 'gap:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'card_height',
			array(
				'label'      => 'ارتفاع کارت',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 60, 'max' => 200 ) ),
				'selectors'  => array( $card => 'min-height:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'card_radius',
			array(
				'label'      => 'گردی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( $card => 'border-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'card_typo',
				'selector' => '{{WRAPPER}} .ard-cm__card-label',
			)
		);

		$this->add_responsive_control(
			'card_icon_size',
			array(
				'label'      => 'اندازه آیکون',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 12, 'max' => 64 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-cm__card .ard-cm__icon' => 'font-size:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->start_controls_tabs( 'card_tabs' );

		foreach ( array( 'normal' => 'عادی', 'hover' => 'هاور' ) as $state => $state_label ) {
			$sel = $card . ( 'hover' === $state ? ':hover' : '' );

			$this->start_controls_tab( 'card_tab_' . $state, array( 'label' => $state_label ) );

			$this->add_control(
				'card_color_' . $state,
				array(
					'label'     => 'رنگ متن',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( $sel => 'color:{{VALUE}};' ),
				)
			);

			$this->add_control(
				'card_icon_color_' . $state,
				array(
					'label'     => 'رنگ آیکون',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( $sel . ' .ard-cm__icon' => 'color:{{VALUE}};' ),
				)
			);

			$this->add_control(
				'card_bg_' . $state,
				array(
					'label'     => 'رنگ زمینه',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( $sel => 'background:{{VALUE}};' ),
				)
			);

			$this->add_control(
				'card_border_' . $state,
				array(
					'label'     => 'رنگ حاشیه',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( $sel => 'border-color:{{VALUE}};' ),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * پنجره در حالت «پنهان؛ با کلیک باز شود».
	 */
	protected function add_popup_style_controls() {
		$this->start_controls_section(
			'section_style_popup',
			array(
				'label'     => 'پنجره',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'display_mode' => 'popup' ),
			)
		);

		$this->add_control(
			'popup_bg',
			array(
				'label'     => 'رنگ زمینه پنجره',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-cm__dialog' => 'background:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'popup_scrim',
			array(
				'label'     => 'رنگ پرده پشت پنجره',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-cm__scrim' => 'background:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'popup_radius',
			array(
				'label'      => 'گردی پنجره (دسکتاپ)',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 48 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-cm' => '--ard-cm-popup-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'popup_padding',
			array(
				'label'      => 'فاصله داخلی پنجره',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-cm__body' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'popup_shadow',
				'selector' => '{{WRAPPER}} .ard-cm__dialog',
			)
		);

		$this->add_control(
			'popup_close_color',
			array(
				'label'     => 'رنگ دکمه بستن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-cm__close' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'popup_title_typo',
				'label'    => 'قلم عنوان (موبایل)',
				'selector' => '{{WRAPPER}} .ard-cm__bar-title',
			)
		);

		$this->end_controls_section();
	}

	/* --------------------------------------------------------------------- */
	/* داده                                                                    */
	/* --------------------------------------------------------------------- */

	/**
	 * آیتم‌های منو، گروه‌بندی‌شده بر اساس والد.
	 *
	 * از همان فیلتر wp_nav_menu_objects رد می‌شوند تا «تزریق خودکار
	 * زیردسته‌ها» و هر افزونهٔ دیگری که روی منو کار می‌کند، اینجا هم اثر کند.
	 *
	 * @param string $menu
	 * @return array parent_id => item[]
	 */
	protected function tree( $menu ) {
		$items = wp_get_nav_menu_items( $menu, array( 'update_post_term_cache' => false ) );

		if ( ! $items || is_wp_error( $items ) ) {
			return array();
		}

		_wp_menu_item_classes_by_context( $items );

		$items = apply_filters( 'wp_nav_menu_objects', $items, (object) array( 'menu' => $menu, 'ardal_category_menu' => true ) );

		$tree = array();

		foreach ( (array) $items as $item ) {
			$tree[ (int) $item->menu_item_parent ][] = $item;
		}

		return $tree;
	}

	/**
	 * آیا این آیتم یا یکی از نوادگانش صفحهٔ فعلی است؟
	 *
	 * @param object $item
	 * @param array  $tree
	 * @return bool
	 */
	protected function is_current_branch( $item, $tree ) {
		if ( ! empty( $item->current ) || ! empty( $item->current_item_ancestor ) || ! empty( $item->current_item_parent ) ) {
			return true;
		}

		foreach ( isset( $tree[ (int) $item->ID ] ) ? $tree[ (int) $item->ID ] : array() as $child ) {
			if ( $this->is_current_branch( $child, $tree ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * آیکون یک آیتم: آپلودی، وگرنه آیکون آماده، وگرنه ''.
	 *
	 * آیکون آپلودی به‌صورت ماسک رسم می‌شود تا رنگ متن را بگیرد؛ این‌طوری
	 * آیکون آیتم فعال (روی زمینهٔ صورتی) خودبه‌خود سفید می‌شود.
	 *
	 * @param object $item
	 * @param bool   $mono
	 * @return string
	 */
	protected function item_icon( $item, $mono ) {
		$img = (int) Ardal_Mega_Menu::get( $item->ID, 'icon_img' );

		if ( $img ) {
			$url = wp_get_attachment_url( $img );

			if ( $url ) {
				if ( $mono ) {
					return sprintf(
						'<span class="ard-cm__mask" style="--ard-cm-mask:url(&quot;%s&quot;)" aria-hidden="true"></span>',
						esc_url( $url )
					);
				}

				return sprintf( '<img class="ard-cm__img" src="%s" alt="" loading="lazy">', esc_url( $url ) );
			}
		}

		$key = Ardal_Mega_Menu::get( $item->ID, 'icon' );

		return '' !== $key ? Ardal_Helpers::icon( $key, 24 ) : '';
	}

	/**
	 * نشانی آیتم؛ '' برای آیتم‌های بی‌مقصد («#»).
	 *
	 * @param object $item
	 * @return string
	 */
	protected function item_url( $item ) {
		$url = isset( $item->url ) ? (string) $item->url : '';

		return ( '' === $url || '#' === $url ) ? '' : $url;
	}

	/**
	 * ویژگی‌های لینک (target و rel) از تنظیمات خود آیتم منو.
	 *
	 * @param object $item
	 * @return string
	 */
	protected function link_extra( $item ) {
		$out = '';

		if ( ! empty( $item->target ) ) {
			$out .= ' target="' . esc_attr( $item->target ) . '"';
		}

		if ( ! empty( $item->xfn ) ) {
			$out .= ' rel="' . esc_attr( $item->xfn ) . '"';
		}

		return $out;
	}

	/* --------------------------------------------------------------------- */
	/* رندر                                                                    */
	/* --------------------------------------------------------------------- */

	protected function render() {
		$s    = $this->get_settings_for_display();
		$menu = isset( $s['menu'] ) ? (string) $s['menu'] : '';

		if ( ! Ardal_Mega_Menu::menu_exists( $menu ) ) {
			$this->render_empty( 'ابتدا از «نمایش ← فهرست‌ها» یک منو بسازید و سپس در تنظیمات این ویجت انتخابش کنید.' );

			return;
		}

		$tree = $this->tree( $menu );
		$top  = isset( $tree[0] ) ? $tree[0] : array();

		if ( ! $top ) {
			$this->render_empty( 'منوی انتخاب‌شده آیتمی ندارد.' );

			return;
		}

		$mono   = 'yes' === $s['mono_icons'];
		$popup  = 'popup' === $s['display_mode'];
		$uid    = 'ard-cm-' . $this->get_id();
		$editor = class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->editor && \Elementor\Plugin::$instance->editor->is_edit_mode();

		// آیتم فعال پیش‌فرض: شاخهٔ صفحهٔ فعلی، وگرنه اولین آیتمِ دارای زیرمنو.
		$active = 0;

		if ( 'current' === $s['desktop_active'] ) {
			foreach ( $top as $item ) {
				if ( ! empty( $tree[ (int) $item->ID ] ) && $this->is_current_branch( $item, $tree ) ) {
					$active = (int) $item->ID;
					break;
				}
			}
		}

		if ( ! $active ) {
			foreach ( $top as $item ) {
				if ( ! empty( $tree[ (int) $item->ID ] ) ) {
					$active = (int) $item->ID;
					break;
				}
			}
		}

		$classes = array( 'ard-cm', $popup ? 'ard-cm--popup' : 'ard-cm--inline' );

		if ( $popup && $editor ) {
			// در ویرایشگر، پنجره باید دیده و ویرایش شود.
			$classes[] = 'ard-cm--preview';
		}

		$popup_id = $popup ? sanitize_html_class( (string) $s['popup_id'] ) : '';
		$popup_id = '' !== $popup_id ? $popup_id : ( $popup ? 'ard-catmenu' : '' );

		printf(
			'<div class="%s"%s data-ard-cm data-open="%s" data-active="%d" data-single="%s" data-mobile-open="%s">',
			esc_attr( implode( ' ', $classes ) ),
			$popup ? ' id="' . esc_attr( $popup_id ) . '"' : '',
			'click' === $s['desktop_open'] ? 'click' : 'hover',
			(int) $active,
			'yes' === $s['mobile_single'] ? '1' : '0',
			'yes' === $s['mobile_first_open'] ? '1' : '0'
		);

		if ( $popup ) {
			echo '<div class="ard-cm__scrim" data-ard-cm-close></div>';
			printf(
				'<div class="ard-cm__dialog" role="dialog" aria-modal="true" aria-label="%s">',
				esc_attr( '' !== trim( (string) $s['popup_title'] ) ? $s['popup_title'] : $s['aria_label'] )
			);
			echo '<div class="ard-cm__bar">';
			printf( '<span class="ard-cm__bar-title">%s</span>', esc_html( $s['popup_title'] ) );
			printf(
				'<button type="button" class="ard-cm__close" data-ard-cm-close aria-label="بستن">%s</button>',
				Ardal_Archive::icon( 'close-circle', 30 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			echo '</div>';
		}

		printf( '<nav class="ard-cm__body" aria-label="%s">', esc_attr( $s['aria_label'] ) );

		echo '<ul class="ard-cm__list">';

		foreach ( $top as $item ) {
			$this->render_top_item( $item, $tree, $s, $uid, $mono, $active );
		}

		echo '</ul>';

		$this->render_panel( $top, $tree, $s, $uid, $active );

		echo '</nav>';

		if ( $popup ) {
			echo '</div>';
		}

		echo '</div>';
	}

	/**
	 * یک آیتم سطح اول: ردیف، و کارت‌های زیردستهٔ موبایل زیرش.
	 *
	 * @param object $item
	 * @param array  $tree
	 * @param array  $s
	 * @param string $uid
	 * @param bool   $mono
	 * @param int    $active
	 */
	protected function render_top_item( $item, $tree, $s, $uid, $mono, $active ) {
		$id       = (int) $item->ID;
		$children = isset( $tree[ $id ] ) ? $tree[ $id ] : array();
		$url      = $this->item_url( $item );
		$title    = apply_filters( 'the_title', $item->title, $item->ID );
		$icon     = $this->item_icon( $item, $mono );

		printf(
			'<li class="ard-cm__item%s%s" data-ard-cm-item="%d">',
			$children ? ' has-children' : '',
			$id === $active ? ' is-default' : '',
			$id
		);

		$inner = sprintf(
			'<span class="ard-cm__icon" aria-hidden="true">%s</span><span class="ard-cm__label">%s</span>%s',
			$icon, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_html( $title ),
			'yes' === $s['show_arrow']
				? '<span class="ard-cm__arrow" aria-hidden="true">' . Ardal_Archive::icon( 'chev-l', 20, 2 ) . '</span>'
				: ''
		);

		$aria = $children
			? sprintf( ' aria-expanded="false" aria-controls="%1$s-g%2$d %1$s-p%2$d"', esc_attr( $uid ), $id )
			: '';

		if ( '' !== $url ) {
			printf(
				'<a class="ard-cm__row" href="%s"%s%s>%s</a>',
				esc_url( $url ),
				$this->link_extra( $item ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$aria, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$inner // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		} else {
			printf(
				'<button type="button" class="ard-cm__row"%s>%s</button>',
				$aria, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$inner // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		if ( $children ) {
			printf( '<div class="ard-cm__grid" id="%s-g%d">', esc_attr( $uid ), $id );

			foreach ( $children as $child ) {
				$child_icon = $this->item_icon( $child, $mono );

				if ( '' === $child_icon && 'parent' === $s['card_fallback'] ) {
					$child_icon = $icon;
				}

				$this->render_card(
					$this->item_url( $child ),
					apply_filters( 'the_title', $child->title, $child->ID ),
					$child_icon,
					$this->link_extra( $child )
				);
			}

			if ( 'yes' === $s['all_card'] && '' !== $url ) {
				$this->render_card(
					$url,
					str_replace( '{title}', $title, (string) $s['all_card_text'] ),
					Ardal_Helpers::icon( '' !== $s['all_card_icon'] ? $s['all_card_icon'] : 'grid', 24 ),
					'',
					true
				);
			}

			echo '</div>';
		}

		echo '</li>';
	}

	/**
	 * یک کارت زیردسته در موبایل.
	 *
	 * @param string $url
	 * @param string $title
	 * @param string $icon
	 * @param string $extra
	 * @param bool   $all
	 */
	protected function render_card( $url, $title, $icon, $extra = '', $all = false ) {
		$inner = sprintf(
			'%s<span class="ard-cm__card-label">%s</span>',
			'' !== $icon ? '<span class="ard-cm__icon" aria-hidden="true">' . $icon . '</span>' : '',
			esc_html( $title )
		);

		$class = 'ard-cm__card' . ( $all ? ' ard-cm__card--all' : '' );

		if ( '' !== $url ) {
			printf(
				'<a class="%s" href="%s"%s>%s</a>',
				esc_attr( $class ),
				esc_url( $url ),
				$extra, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$inner // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);

			return;
		}

		printf( '<span class="%s">%s</span>', esc_attr( $class ), $inner ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * پنل دسکتاپ: برای هر آیتم دارای زیرمنو یک صفحه، فقط یکی دیده می‌شود.
	 *
	 * @param array  $top
	 * @param array  $tree
	 * @param array  $s
	 * @param string $uid
	 * @param int    $active
	 */
	protected function render_panel( $top, $tree, $s, $uid, $active ) {
		echo '<div class="ard-cm__panel">';

		foreach ( $top as $item ) {
			$id       = (int) $item->ID;
			$children = isset( $tree[ $id ] ) ? $tree[ $id ] : array();

			if ( ! $children ) {
				continue;
			}

			printf(
				'<div class="ard-cm__pane" id="%s-p%d" data-ard-cm-pane="%d"%s>',
				esc_attr( $uid ),
				$id,
				$id,
				$id === $active ? '' : ' hidden'
			);

			echo '<div class="ard-cm__cols">';

			foreach ( $children as $child ) {
				$url   = $this->item_url( $child );
				$title = apply_filters( 'the_title', $child->title, $child->ID );

				echo '<div class="ard-cm__col">';

				if ( '' !== $url ) {
					printf(
						'<a class="ard-cm__head" href="%s"%s>%s</a>',
						esc_url( $url ),
						$this->link_extra( $child ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						esc_html( $title )
					);
				} else {
					printf( '<span class="ard-cm__head">%s</span>', esc_html( $title ) );
				}

				$grand = isset( $tree[ (int) $child->ID ] ) ? $tree[ (int) $child->ID ] : array();

				if ( $grand ) {
					echo '<ul class="ard-cm__subs">';

					foreach ( $grand as $leaf ) {
						$leaf_url = $this->item_url( $leaf );

						if ( '' !== $leaf_url ) {
							printf(
								'<li><a class="ard-cm__sub%s" href="%s"%s>%s</a></li>',
								! empty( $leaf->current ) ? ' is-current' : '',
								esc_url( $leaf_url ),
								$this->link_extra( $leaf ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								esc_html( apply_filters( 'the_title', $leaf->title, $leaf->ID ) )
							);
						} else {
							printf(
								'<li><span class="ard-cm__sub">%s</span></li>',
								esc_html( apply_filters( 'the_title', $leaf->title, $leaf->ID ) )
							);
						}
					}

					echo '</ul>';
				}

				echo '</div>';
			}

			echo '</div>';

			$parent_url = $this->item_url( $item );

			if ( 'yes' === $s['panel_all'] && '' !== $parent_url ) {
				printf(
					'<a class="ard-cm__all" href="%s">%s%s</a>',
					esc_url( $parent_url ),
					esc_html( str_replace( '{title}', apply_filters( 'the_title', $item->title, $item->ID ), (string) $s['panel_all_text'] ) ),
					Ardal_Archive::icon( 'chev-l', 16, 2 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}

			echo '</div>';
		}

		echo '</div>';
	}
}
