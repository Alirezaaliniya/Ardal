<?php
/**
 * ویجت «منوی کشویی» — یک ناحیهٔ کشویی مستقل (offcanvas) که از راست یا چپ
 * باز می‌شود.
 *
 * محتوا: منوی وردپرس (با زیرمنوهای آکاردئونی) و/یا یک قالب ذخیره‌شدهٔ
 * المنتور. باز شدنش به هیچ دکمهٔ خاصی بسته نیست: هر المانی در هر جای سایت
 * که کلاس ard-oc-open (یا ard-oc-open-{شناسه}) داشته باشد، یا لینکی با
 * نشانی #ard-oc-{شناسه}، کشو را باز می‌کند. رفتارها در ardal-offcanvas.js است.
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

class Ardal_Widget_Offcanvas extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-offcanvas';
	}

	public function get_title() {
		return 'اردال — منوی کشویی (Offcanvas)';
	}

	public function get_icon() {
		return 'eicon-menu-bar';
	}

	public function get_keywords() {
		return array( 'اردال', 'منو', 'کشو', 'کشویی', 'موبایل', 'همبرگری', 'offcanvas', 'drawer', 'menu', 'mobile' );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-offcanvas' );
	}

	public function get_script_depends() {
		return array( 'ardal-offcanvas' );
	}

	/**
	 * فهرست قالب‌های ذخیره‌شدهٔ المنتور.
	 *
	 * @return array
	 */
	private static function template_options() {
		$out   = array( '' => '— هیچ —' );
		$posts = get_posts(
			array(
				'post_type'   => 'elementor_library',
				'post_status' => 'publish',
				'numberposts' => 200,
				'orderby'     => 'title',
				'order'       => 'ASC',
			)
		);

		foreach ( $posts as $p ) {
			$type             = get_post_meta( $p->ID, '_elementor_template_type', true );
			$out[ $p->ID ] = $p->post_title . ( $type ? ' (' . $type . ')' : '' );
		}

		return $out;
	}

	/**
	 * شناسهٔ تمیز کشو (فقط حروف لاتین، عدد و خط تیره).
	 *
	 * @param string $raw
	 * @return string
	 */
	private static function slug( $raw ) {
		$slug = strtolower( preg_replace( '/[^a-zA-Z0-9-]+/', '-', (string) $raw ) );
		$slug = trim( $slug, '-' );

		return '' !== $slug ? $slug : 'menu';
	}

	protected function register_controls() {

		/* ------------------------------------------------ باز شدن */

		$this->start_controls_section( 'section_trigger', array( 'label' => 'باز شدن کشو' ) );

		$this->add_control(
			'drawer_id',
			array(
				'label'       => 'شناسهٔ کشو',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'menu',
				'description' => 'فقط حروف لاتین، عدد و خط تیره. اگر چند کشو در یک صفحه داری، هرکدام شناسهٔ جدا بگیرد.',
			)
		);

		$this->add_control(
			'trigger_help',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => '<div style="line-height:1.9">برای باز کردن، به هر المانی (آیکون، دکمه، تصویر، متن) در تب «پیشرفته ← کلاس‌های CSS» یکی از این کلاس‌ها را بده:<br><code>ard-oc-open</code> باز کردن (اولین کشوی صفحه)<br><code>ard-oc-open-<b>شناسه</b></code> باز کردن همین کشو، مثلاً <code>ard-oc-open-menu</code><br><code>ard-oc-toggle-<b>شناسه</b></code> باز و بسته کردن<br><code>ard-oc-close</code> بستن<br>یا به هر لینکی نشانی <code>#ard-oc-<b>شناسه</b></code> بده.</div>',
				'content_classes' => 'elementor-descriptor',
			)
		);

		$this->add_control(
			'show_button',
			array(
				'label'        => 'دکمهٔ همبرگری در محل ویجت',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'description'  => 'خاموش باشد، ویجت در صفحه دیده نمی‌شود و فقط با کلاس‌های بالا باز می‌شود.',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'button_label',
			array(
				'label'     => 'متن کنار دکمه',
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array( 'show_button' => 'yes' ),
			)
		);

		$this->add_control(
			'editor_open',
			array(
				'label'        => 'باز در ویرایشگر (برای طراحی)',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => 'فقط داخل المنتور؛ کشو کنار ویجت باز نمایش داده می‌شود تا راحت ظاهرش را تنظیم کنی.',
				'separator'    => 'before',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ محتوا */

		$this->start_controls_section( 'section_content', array( 'label' => 'محتوا' ) );

		$this->add_control(
			'title',
			array(
				'label'       => 'عنوان بالای کشو',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'منو',
				'label_block' => true,
			)
		);

		$this->add_control(
			'logo',
			array(
				'label'       => 'لوگو (به‌جای عنوان)',
				'type'        => Controls_Manager::MEDIA,
				'description' => 'خالی بماند، عنوان نمایش داده می‌شود.',
			)
		);

		$this->add_control(
			'logo_link',
			array(
				'label'       => 'لینک لوگو',
				'type'        => Controls_Manager::URL,
				'placeholder' => home_url( '/' ),
				'condition'   => array( 'logo[url]!' => '' ),
			)
		);

		$this->add_control(
			'menu',
			array(
				'label'       => 'منوی وردپرس',
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array( '' => '— هیچ —' ) + Ardal_Mega_Menu::menu_options(),
				'description' => 'از «نمایش ← فهرست‌ها». زیرمنوها آکاردئونی باز می‌شوند.',
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'depth',
			array(
				'label'     => 'عمق منو',
				'type'      => Controls_Manager::SELECT,
				'default'   => '0',
				'options'   => array(
					'0' => 'همهٔ سطح‌ها',
					'1' => 'فقط سطح اول',
					'2' => 'تا دو سطح',
					'3' => 'تا سه سطح',
				),
				'condition' => array( 'menu!' => '' ),
			)
		);

		$this->add_control(
			'template',
			array(
				'label'       => 'قالب المنتور',
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => self::template_options(),
				'description' => 'هر چیزی که با المنتور بسازی (جست‌وجو، دکمه‌ها، شبکه‌های اجتماعی، بنر) و در «قالب‌ها» ذخیره کنی.',
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'template_pos',
			array(
				'label'     => 'جای قالب',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'after',
				'options'   => array(
					'before' => 'بالای منو',
					'after'  => 'پایین منو',
				),
				'condition' => array( 'template!' => '', 'menu!' => '' ),
			)
		);

		$this->add_control(
			'footer_text',
			array(
				'label'     => 'متن پایین کشو',
				'type'      => Controls_Manager::TEXTAREA,
				'default'   => '',
				'rows'      => 2,
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ رفتار */

		$this->start_controls_section( 'section_behavior', array( 'label' => 'رفتار' ) );

		$this->add_control(
			'side',
			array(
				'label'   => 'باز شدن از',
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'right',
				'toggle'  => false,
				'options' => array(
					'right' => array( 'title' => 'راست', 'icon' => 'eicon-h-align-right' ),
					'left'  => array( 'title' => 'چپ', 'icon' => 'eicon-h-align-left' ),
				),
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'          => 'عرض کشو',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', 'vw', '%' ),
				'range'          => array(
					'px' => array( 'min' => 200, 'max' => 700 ),
					'vw' => array( 'min' => 30, 'max' => 100 ),
					'%'  => array( 'min' => 30, 'max' => 100 ),
				),
				'default'        => array( 'unit' => 'px', 'size' => 340 ),
				'mobile_default' => array( 'unit' => 'vw', 'size' => 86 ),
				'selectors'      => array( '{{WRAPPER}} .ard-oc' => '--ard-oc-w: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'     => 'سرعت باز شدن (میلی‌ثانیه)',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 1500,
				'step'      => 50,
				'default'   => 320,
				'selectors' => array( '{{WRAPPER}} .ard-oc' => '--ard-oc-speed: {{VALUE}}ms;' ),
			)
		);

		foreach ( array(
			'close_on_link'    => array( 'بستن با کلیک روی لینک‌ها', 'yes', 'برای لینک‌های داخل صفحه (#) لازم است.' ),
			'close_on_overlay' => array( 'بستن با کلیک روی پس‌زمینه', 'yes', '' ),
			'lock_scroll'      => array( 'قفل اسکرول صفحه وقتی باز است', 'yes', '' ),
			'open_current'     => array( 'زیرمنوی صفحهٔ فعلی از اول باز باشد', 'yes', '' ),
		) as $key => $row ) {
			$this->add_control(
				$key,
				array(
					'label'        => $row[0],
					'type'         => Controls_Manager::SWITCHER,
					'return_value' => 'yes',
					'default'      => $row[1],
					'description'  => $row[2],
				)
			);
		}

		$this->end_controls_section();

		$this->register_style_controls();
	}

	private function register_style_controls() {

		/* ------------------------------------------------ کشو */

		$this->start_controls_section(
			'section_style_panel',
			array(
				'label' => 'کشو و پس‌زمینه',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'panel_bg',
			array(
				'label'     => 'زمینهٔ کشو',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-oc' => '--ard-oc-bg: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'overlay_bg',
			array(
				'label'     => 'رنگ پس‌زمینهٔ تیره',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-oc' => '--ard-oc-overlay: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'overlay_blur',
			array(
				'label'     => 'محوی پس‌زمینه',
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0, 'max' => 20 ) ),
				'selectors' => array( '{{WRAPPER}} .ard-oc' => '--ard-oc-blur: {{SIZE}}px;' ),
			)
		);

		$this->add_responsive_control(
			'panel_pad',
			array(
				'label'      => 'فاصلهٔ داخلی',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array( '{{WRAPPER}} .ard-oc__body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'panel_radius',
			array(
				'label'      => 'گردی گوشهٔ کشو',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 48 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-oc' => '--ard-oc-radius: {{SIZE}}px;' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'panel_shadow',
				'selector' => '{{WRAPPER}} .ard-oc__panel',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ سربرگ */

		$this->start_controls_section(
			'section_style_head',
			array(
				'label' => 'سربرگ و دکمهٔ بستن',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'head_bg',
			array(
				'label'     => 'زمینهٔ سربرگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-oc' => '--ard-oc-head-bg: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'head_ink',
			array(
				'label'     => 'رنگ عنوان',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-oc' => '--ard-oc-head-ink: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'head_typo',
				'selector' => '{{WRAPPER}} .ard-oc__title',
			)
		);

		$this->add_control(
			'logo_h',
			array(
				'label'      => 'ارتفاع لوگو',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 16, 'max' => 120 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-oc' => '--ard-oc-logo-h: {{SIZE}}px;' ),
			)
		);

		$this->add_control(
			'close_ink',
			array(
				'label'     => 'رنگ دکمهٔ بستن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-oc' => '--ard-oc-close-ink: {{VALUE}};' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'close_bg',
			array(
				'label'     => 'زمینهٔ دکمهٔ بستن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-oc' => '--ard-oc-close-bg: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'head_line',
			array(
				'label'        => 'خط زیر سربرگ',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'ard-oc-headline-',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ آیتم‌ها */

		$this->start_controls_section(
			'section_style_items',
			array(
				'label' => 'آیتم‌های منو',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'item_typo',
				'selector' => '{{WRAPPER}} .ard-oc__menu > li > a',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sub_typo',
				'label'    => 'تایپوگرافی زیرمنو',
				'selector' => '{{WRAPPER}} .ard-oc__menu .sub-menu a',
			)
		);

		$this->start_controls_tabs( 'item_tabs' );

		foreach ( array(
			'normal' => array( 'عادی', '' ),
			'hover'  => array( 'هاور', '-hover' ),
			'active' => array( 'صفحهٔ فعلی', '-active' ),
		) as $state => $row ) {
			$this->start_controls_tab( 'item_tab_' . $state, array( 'label' => $row[0] ) );

			$this->add_control(
				'item_ink_' . $state,
				array(
					'label'     => 'رنگ متن',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( '{{WRAPPER}} .ard-oc' => '--ard-oc-item-ink' . $row[1] . ': {{VALUE}};' ),
				)
			);

			$this->add_control(
				'item_bg_' . $state,
				array(
					'label'     => 'زمینه',
					'type'      => Controls_Manager::COLOR,
					'selectors' => array( '{{WRAPPER}} .ard-oc' => '--ard-oc-item-bg' . $row[1] . ': {{VALUE}};' ),
				)
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'item_pad',
			array(
				'label'      => 'فاصلهٔ داخلی آیتم',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array( '{{WRAPPER}} .ard-oc__menu a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'item_gap',
			array(
				'label'      => 'فاصلهٔ بین آیتم‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 24 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-oc' => '--ard-oc-gap: {{SIZE}}px;' ),
			)
		);

		$this->add_control(
			'item_radius',
			array(
				'label'      => 'گردی آیتم',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 24 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-oc' => '--ard-oc-item-radius: {{SIZE}}px;' ),
			)
		);

		$this->add_control(
			'item_divider',
			array(
				'label'        => 'خط جداکننده بین آیتم‌ها',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'ard-oc-divider-',
			)
		);

		$this->add_control(
			'divider_color',
			array(
				'label'     => 'رنگ خط',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-oc' => '--ard-oc-line: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'sub_bg',
			array(
				'label'     => 'زمینهٔ زیرمنو',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-oc' => '--ard-oc-sub-bg: {{VALUE}};' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sub_indent',
			array(
				'label'      => 'تورفتگی زیرمنو',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-oc' => '--ard-oc-indent: {{SIZE}}px;' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ دکمهٔ همبرگری */

		$this->start_controls_section(
			'section_style_button',
			array(
				'label'     => 'دکمهٔ همبرگری',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_button' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'btn_align',
			array(
				'label'     => 'چیدمان',
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array( 'title' => 'راست', 'icon' => 'eicon-h-align-right' ),
					'center'     => array( 'title' => 'وسط', 'icon' => 'eicon-h-align-center' ),
					'flex-end'   => array( 'title' => 'چپ', 'icon' => 'eicon-h-align-left' ),
				),
				'selectors' => array( '{{WRAPPER}} .ard-oc-btnwrap' => 'justify-content: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'btn_size',
			array(
				'label'      => 'اندازه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 28, 'max' => 72 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-oc-btn' => '--ard-oc-btn: {{SIZE}}px;' ),
			)
		);

		$this->add_control(
			'btn_ink',
			array(
				'label'     => 'رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-oc-btn' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'btn_bg',
			array(
				'label'     => 'زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-oc-btn' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'btn_border',
				'selector' => '{{WRAPPER}} .ard-oc-btn',
			)
		);

		$this->add_control(
			'btn_radius',
			array(
				'label'      => 'گردی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array( '{{WRAPPER}} .ard-oc-btn' => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * منوی وردپرس با کلاس‌های کشو.
	 *
	 * @param array $s
	 * @return string
	 */
	private function menu_html( $s ) {
		if ( empty( $s['menu'] ) || ! is_nav_menu( $s['menu'] ) ) {
			return '';
		}

		return (string) wp_nav_menu(
			array(
				'menu'        => $s['menu'],
				'container'   => false,
				'menu_class'  => 'ard-oc__menu',
				'menu_id'     => '',
				'depth'       => (int) $s['depth'],
				'fallback_cb' => false,
				'echo'        => false,
			)
		);
	}

	/**
	 * محتوای قالب المنتور.
	 *
	 * @param int $id
	 * @return string
	 */
	private function template_html( $id ) {
		$id = absint( $id );

		// قالب نباید خودش را (یا این کشو را) دوباره رندر کند.
		if ( ! $id || get_the_ID() === $id || 'publish' !== get_post_status( $id ) ) {
			return '';
		}

		return (string) \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id, true );
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$slug    = self::slug( $s['drawer_id'] );
		$editing = \Elementor\Plugin::$instance->editor->is_edit_mode();
		$side    = 'left' === $s['side'] ? 'left' : 'right';
		$menu    = $this->menu_html( $s );
		$tpl     = ! empty( $s['template'] ) ? $this->template_html( $s['template'] ) : '';
		$title   = trim( (string) $s['title'] );
		$logo    = ! empty( $s['logo']['url'] ) ? $s['logo']['url'] : '';
		$panel   = 'ard-oc-' . $slug;

		$classes = array( 'ard-oc', 'ard-oc--' . $side );

		if ( $editing && 'yes' === $s['editor_open'] ) {
			$classes[] = 'ard-oc--preview';
		}

		$attrs = array(
			'class'               => implode( ' ', $classes ),
			'id'                  => $panel,
			'data-ard-oc'         => $slug,
			'data-close-link'     => 'yes' === $s['close_on_link'] ? '1' : '0',
			'data-close-overlay'  => 'yes' === $s['close_on_overlay'] ? '1' : '0',
			'data-lock'           => 'yes' === $s['lock_scroll'] ? '1' : '0',
			'data-open-current'   => 'yes' === $s['open_current'] ? '1' : '0',
		);

		if ( 'yes' === $s['show_button'] ) :
			?>
			<div class="ard-oc-btnwrap">
				<button type="button" class="ard-oc-btn ard-oc-toggle-<?php echo esc_attr( $slug ); ?>" aria-controls="<?php echo esc_attr( $panel ); ?>" aria-expanded="false" aria-label="<?php echo esc_attr( '' !== $title ? $title : 'منو' ); ?>">
					<span class="ard-oc-btn__bars" aria-hidden="true"><i></i><i></i><i></i></span>
					<?php if ( '' !== trim( (string) $s['button_label'] ) ) : ?>
						<span class="ard-oc-btn__label"><?php echo esc_html( $s['button_label'] ); ?></span>
					<?php endif; ?>
				</button>
			</div>
			<?php
		elseif ( $editing && 'yes' !== $s['editor_open'] ) :
			printf(
				'<div class="ard-empty">%s</div>',
				esc_html( Ardal_Brand::text( 'منوی کشویی «' . $slug . '» — در سایت با کلاس ard-oc-open-' . $slug . ' باز می‌شود.' ) )
			);
		endif;
		?>
		<div<?php foreach ( $attrs as $k => $v ) { printf( ' %s="%s"', esc_attr( $k ), esc_attr( $v ) ); } ?><?php echo in_array( 'ard-oc--preview', $classes, true ) ? '' : ' hidden'; ?>>
			<div class="ard-oc__overlay" data-ard-oc-dismiss="overlay" aria-hidden="true"></div>
			<div class="ard-oc__panel" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr( '' !== $title ? $title : 'منو' ); ?>" tabindex="-1">
				<div class="ard-oc__head">
					<?php if ( '' !== $logo ) : ?>
						<?php
						$link = ! empty( $s['logo_link']['url'] ) ? $s['logo_link']['url'] : home_url( '/' );
						?>
						<a class="ard-oc__logo" href="<?php echo esc_url( $link ); ?>"><img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" /></a>
					<?php elseif ( '' !== $title ) : ?>
						<span class="ard-oc__title"><?php echo esc_html( $title ); ?></span>
					<?php endif; ?>
					<button type="button" class="ard-oc__close" data-ard-oc-dismiss="button" aria-label="بستن منو">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
					</button>
				</div>

				<div class="ard-oc__body">
					<?php
					if ( '' !== $tpl && 'before' === $s['template_pos'] ) {
						echo '<div class="ard-oc__tpl">' . $tpl . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خروجی المنتور.
					}

					if ( '' !== $menu ) {
						echo '<nav class="ard-oc__nav" aria-label="' . esc_attr( '' !== $title ? $title : 'منو' ) . '">' . $menu . '</nav>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_nav_menu.
					}

					if ( '' !== $tpl && ( 'before' !== $s['template_pos'] || '' === $menu ) ) {
						echo '<div class="ard-oc__tpl">' . $tpl . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خروجی المنتور.
					}

					if ( '' === $menu && '' === $tpl && $editing ) {
						echo '<p class="ard-oc__hint">یک منوی وردپرس یا قالب المنتور در تب «محتوا» انتخاب کن.</p>';
					}
					?>
				</div>

				<?php if ( '' !== trim( (string) $s['footer_text'] ) ) : ?>
					<div class="ard-oc__foot"><?php echo wp_kses_post( nl2br( $s['footer_text'] ) ); ?></div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
