<?php
/**
 * ویجت پنل کاربری — همان پوستهٔ حساب کاربری ووکامرس، داخل المنتور.
 *
 * مارک‌آپ را از Ardal_Account::render_panel() می‌گیرد، پس هرچه در تمپلیت
 * myaccount/my-account.php دیده می‌شود همین‌جا هم هست؛ فقط بخش‌هایش
 * خاموش‌شدنی و استایلش تنظیم‌شدنی است.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Account extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-account';
	}

	public function get_title() {
		return 'اردال — پنل کاربری';
	}

	public function get_icon() {
		return 'eicon-my-account';
	}

	public function get_keywords() {
		return array( 'اردال', 'پنل', 'حساب', 'کاربر', 'account', 'dashboard', 'my account' );
	}

	/**
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'ardal', 'ardal-account' );
	}

	/**
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'ardal', 'ardal-account' );
	}

	protected function register_controls() {

		/* ------------------------------------------------ بخش‌ها */

		$this->start_controls_section(
			'section_parts',
			array( 'label' => 'بخش‌های پنل' )
		);

		$this->add_control(
			'where_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => 'این ویجت همان پنل حساب کاربری را چاپ می‌کند. اگر صفحهٔ «حساب کاربری» را با المنتور می‌سازید، شورت‌کد <code>[woocommerce_my_account]</code> را از محتوای صفحه بردارید تا پنل دوبار چاپ نشود.',
				'content_classes' => 'elementor-descriptor',
			)
		);

		$this->add_control(
			'show_topbar',
			array(
				'label'        => 'نوار بالای دسکتاپ',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => 'بله',
				'label_off'    => 'خیر',
				'return_value' => 'yes',
				'description'  => 'لوگو، زنگ اعلان و چیپ کاربر. اگر هدر اردال را بالای صفحه دارید، معمولاً این اضافه است.',
			)
		);

		$this->add_control(
			'show_bell',
			array(
				'label'        => 'آیکون زنگ اعلان',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'شمارندهٔ سفارش‌های در جریان رویش می‌نشیند.',
			)
		);

		$this->add_control(
			'show_chip',
			array(
				'label'        => 'چیپ نام کاربر در نوار بالا',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'show_topbar' => 'yes' ),
			)
		);

		$this->add_control(
			'brand_sub',
			array(
				'label'       => 'متن زیر نام سایت',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'پنل کاربری',
				'label_block' => true,
				'condition'   => array( 'show_topbar' => 'yes' ),
			)
		);

		$this->add_control(
			'show_mhead',
			array(
				'label'        => 'سربرگ موبایل',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'نوار سرمه‌ای بالای صفحه در موبایل، با دکمهٔ منو و نام کاربر.',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'show_sidebar',
			array(
				'label'        => 'ستون کناری (منوی پنل)',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'در موبایل به کشو تبدیل می‌شود. فهرست و ترتیب آیتم‌هایش در «تنظیمات اردال ← پنل کاربری» است.',
			)
		);

		$this->add_control(
			'show_tabbar',
			array(
				'label'        => 'نوار پایین موبایل',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'اگر ویجت «نوار پایین موبایل» اردال را جداگانه در صفحه دارید، این را خاموش کنید.',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ چیدمان */

		$this->start_controls_section(
			'section_layout',
			array( 'label' => 'چیدمان' )
		);

		$this->add_control(
			'side',
			array(
				'label'     => 'ستون کناری در کدام سمت',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'right',
				'options'   => array(
					'right' => 'راست (شروع متن)',
					'left'  => 'چپ (پایان متن)',
				),
				'condition' => array( 'show_sidebar' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'side_width',
			array(
				'label'      => 'عرض ستون کناری',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 180, 'max' => 420 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 250 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-ac' => '--ac-side-w:{{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'show_sidebar' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'body_gap',
			array(
				'label'      => 'فاصله ستون تا محتوا',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 22 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-ac__body' => 'gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'max_width',
			array(
				'label'      => 'حداکثر عرض محتوا',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array( 'min' => 800, 'max' => 1920 ),
					'%'  => array( 'min' => 50, 'max' => 100 ),
				),
				'default'    => array( 'unit' => 'px', 'size' => 1440 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-ac__body' => 'max-inline-size:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'body_padding',
			array(
				'label'      => 'فاصله داخلی ناحیه',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'default'    => array( 'top' => 24, 'right' => 28, 'bottom' => 24, 'left' => 28, 'unit' => 'px', 'isLinked' => false ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-ac__body' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ مهمان */

		$this->start_controls_section(
			'section_guest',
			array( 'label' => 'کاربر مهمان' )
		);

		$this->add_control(
			'guest_mode',
			array(
				'label'       => 'وقتی کاربر وارد نشده',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'form',
				'options'     => array(
					'form'    => 'فرم ورود ووکامرس',
					'message' => 'پیام و دکمه ورود',
					'hide'    => 'هیچ‌چیز نمایش داده نشود',
				),
				'label_block' => true,
			)
		);

		$this->add_control(
			'guest_title',
			array(
				'label'       => 'عنوان پیام',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'برای دیدن پنل، وارد حساب شوید',
				'label_block' => true,
				'condition'   => array( 'guest_mode' => 'message' ),
			)
		);

		$this->add_control(
			'guest_text',
			array(
				'label'       => 'توضیح',
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => 'سفارش‌ها، علاقه‌مندی‌ها و اطلاعات حسابت اینجا جمع است.',
				'label_block' => true,
				'condition'   => array( 'guest_mode' => 'message' ),
			)
		);

		$this->add_tag_control(
			'guest_title_tag',
			'تگ عنوان پیام',
			'h3',
			array( 'condition' => array( 'guest_mode' => 'message' ) )
		);

		$this->add_control(
			'guest_btn',
			array(
				'label'     => 'متن دکمه',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'ورود / ثبت‌نام',
				'condition' => array( 'guest_mode' => 'message' ),
			)
		);

		$this->add_control(
			'guest_link',
			array(
				'label'       => 'لینک دکمه',
				'type'        => Controls_Manager::URL,
				'default'     => array( 'url' => '' ),
				'description' => 'خالی = همان صفحهٔ حساب کاربری ووکامرس.',
				'condition'   => array( 'guest_mode' => 'message' ),
			)
		);

		$this->end_controls_section();

		$this->add_dashboard_controls();
		$this->add_panel_style_controls();
	}

	/**
	 * یک بخش کنترل به ازای هر باکس پیشخوان.
	 *
	 * کنترل‌ها از همان اسکیمای تنظیمات ساخته می‌شوند تا اگر فردا متنی اضافه
	 * شد، خودبه‌خود اینجا هم پیدایش شود و دو فهرست از هم عقب نمانند.
	 */
	private function add_dashboard_controls() {
		$sections = Ardal_Account_Settings::dashboard_sections();
		$texts    = Ardal_Account_Settings::dashboard_texts();
		$icons    = Ardal_Account::icon_options();

		foreach ( $sections as $key => $section ) {
			$this->start_controls_section(
				'section_dash_' . $key,
				array( 'label' => 'پیشخوان: ' . $section['label'] )
			);

			$this->add_control(
				'dash_' . $key . '_note',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => esc_html( $section['desc'] ),
					'content_classes' => 'elementor-descriptor',
				)
			);

			$this->add_control(
				'dash_' . $key,
				array(
					'label'       => 'نمایش این باکس',
					'type'        => Controls_Manager::SELECT,
					'default'     => '',
					'options'     => array(
						''  => 'طبق تنظیمات افزونه',
						'1' => 'نمایش بده',
						'0' => 'نشان نده',
					),
					'label_block' => true,
				)
			);

			$this->add_control(
				'ovr_' . $key,
				array(
					'label'        => 'متن‌ها و آیکون‌های این باکس',
					'type'         => Controls_Manager::SWITCHER,
					'default'      => '',
					'label_on'     => 'همین‌جا',
					'label_off'    => 'از تنظیمات',
					'return_value' => 'yes',
					'description'  => 'روشن که باشد، هرچه اینجا بنویسید جای متن‌های «تنظیمات اردال ← پنل کاربری» می‌نشیند — فقط برای همین صفحه. فیلدی که خالی بگذارید اصلاً چاپ نمی‌شود.',
					'separator'    => 'before',
				)
			);

			foreach ( $texts as $t_key => $field ) {
				if ( $key !== $field['section'] ) {
					continue;
				}

				$args = array(
					'label'     => $field['label'],
					'default'   => $field['default'],
					'condition' => array( 'ovr_' . $key => 'yes' ),
				);

				$desc = isset( $field['desc'] ) ? $field['desc'] : '';

				if ( isset( $field['tokens'] ) ) {
					$desc = trim( $desc . ' جای‌گذار: ' . $field['tokens'] );
				}

				if ( '' !== $desc ) {
					$args['description'] = $desc;
				}

				if ( 'icon' === $field['type'] ) {
					$args['type']        = Controls_Manager::SELECT;
					$args['options']     = $icons;
					$args['label_block'] = true;
				} elseif ( 'textarea' === $field['type'] ) {
					$args['type']        = Controls_Manager::TEXTAREA;
					$args['label_block'] = true;
				} else {
					$args['type']        = Controls_Manager::TEXT;
					$args['label_block'] = true;
				}

				$this->add_control( $t_key, $args );
			}

			$this->end_controls_section();
		}
	}

	/**
	 * تنظیم‌های پیشخوانِ این نمونه را روی تنظیمات سایت می‌نشاند.
	 *
	 * @param array $s
	 */
	private function apply_dashboard_overrides( $s ) {
		$sections = Ardal_Account_Settings::dashboard_sections();
		$texts    = Ardal_Account_Settings::dashboard_texts();
		$show     = array();

		foreach ( $sections as $key => $section ) {
			if ( isset( $s[ 'dash_' . $key ] ) && '' !== $s[ 'dash_' . $key ] ) {
				$show[ 'dash_' . $key ] = $s[ 'dash_' . $key ];
			}
		}

		Ardal_Account_Settings::set_overrides( $show );

		foreach ( $sections as $key => $section ) {
			if ( 'yes' !== ( $s[ 'ovr_' . $key ] ?? '' ) ) {
				continue;
			}

			$values = array();

			foreach ( $texts as $t_key => $field ) {
				if ( $key === $field['section'] && isset( $s[ $t_key ] ) ) {
					$values[ $t_key ] = $s[ $t_key ];
				}
			}

			// خالی اینجا یعنی «نشانش نده»، چون کاربر خودش کلید را زده.
			Ardal_Account_Settings::set_overrides( $values, true );
		}
	}

	/**
	 * استایل پنل — همهٔ رنگ‌ها روی متغیرهای --ac-* می‌نشینند، پس یک کنترل
	 * هم‌زمان همه‌ی جاهایی را که از آن رنگ استفاده می‌کنند می‌گرداند.
	 */
	private function add_panel_style_controls() {

		/* ------------------------------------------------ سطوح */

		$this->start_controls_section(
			'section_style_surface',
			array(
				'label' => 'سطوح و حاشیه‌ها',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'bg',
			array(
				'label'     => 'زمینه پنل',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-ac' => '--ac-bg:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_bg',
			array(
				'label'     => 'زمینه کارت‌ها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-ac' => '--ac-card:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'border',
			array(
				'label'     => 'رنگ حاشیه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-ac' => '--ac-border:{{VALUE}};--ac-border-2:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'divider',
			array(
				'label'     => 'رنگ خط جداکننده',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-ac' => '--ac-divider:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tint',
			array(
				'label'     => 'زمینه ملایم (ردیف‌ها و چیپ‌ها)',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-ac' => '--ac-tint:{{VALUE}};--ac-tint-2:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'radius',
			array(
				'label'      => 'گردی کارت‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 32 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 20 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-ac' => '--ac-r:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'radius_sm',
			array(
				'label'      => 'گردی عناصر کوچک',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 24 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 12 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-ac' => '--ac-r-sm:{{SIZE}}{{UNIT}};--ac-r-xs:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ برند و متن */

		$this->start_controls_section(
			'section_style_brand',
			array(
				'label' => 'رنگ برند و متن',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'navy',
			array(
				'label'       => 'رنگ اصلی (سرمه‌ای)',
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'{{WRAPPER}} .ard-ac' => '--ac-navy:{{VALUE}};',
				),
				'description' => 'سربرگ موبایل، آیتم فعال منو و دکمه‌های اصلی.',
			)
		);

		$this->add_control(
			'navy_hover',
			array(
				'label'     => 'رنگ اصلی در هاور',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-ac' => '--ac-navy-hover:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'green',
			array(
				'label'       => 'رنگ تاکیدی (سبز)',
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'{{WRAPPER}} .ard-ac' => '--ac-green:{{VALUE}};',
				),
				'description' => 'دکمه‌های کنشی مثل «افزودن همه به سبد».',
			)
		);

		$this->add_control(
			'ink',
			array(
				'label'     => 'رنگ عنوان‌ها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-ac' => '--ac-ink:{{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'body_color',
			array(
				'label'     => 'رنگ متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-ac' => '--ac-body:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'soft',
			array(
				'label'     => 'رنگ متن ثانویه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-ac' => '--ac-soft:{{VALUE}};--ac-soft-2:{{VALUE}};--ac-mute:{{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'panel_typo',
				'label'    => 'قلم پنل',
				'selector' => '{{WRAPPER}} .ard-ac',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ نوارها */

		$this->start_controls_section(
			'section_style_bars',
			array(
				'label' => 'نوارها',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'topbar_bg',
			array(
				'label'     => 'زمینه نوار بالا',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-ac__topbar' => 'background:{{VALUE}};',
				),
				'condition' => array( 'show_topbar' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'topbar_padding',
			array(
				'label'      => 'فاصله داخلی نوار بالا',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-ac__topbar-inner' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array( 'show_topbar' => 'yes' ),
			)
		);

		$this->add_control(
			'mhead_bg',
			array(
				'label'     => 'زمینه سربرگ موبایل',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-ac__mhead' => 'background:{{VALUE}};',
				),
				'condition' => array( 'show_mhead' => 'yes' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'tabbar_bg',
			array(
				'label'     => 'زمینه نوار پایین موبایل',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-ac__tabbar' => 'background:{{VALUE}};',
				),
				'condition' => array( 'show_tabbar' => 'yes' ),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();
	}

	/* --------------------------------------------------------------------- */
	/* رندر                                                                    */
	/* --------------------------------------------------------------------- */

	protected function render() {
		if ( ! Ardal_Helpers::has_woo() ) {
			$this->render_empty( 'این ویجت به ووکامرس نیاز دارد.' );

			return;
		}

		/*
		 * دادهٔ جاوااسکریپت پنل (نشانی ایجکس و نانس) معمولاً فقط در صفحهٔ حساب
		 * کاربری چاپ می‌شود؛ این ویجت ممکن است هر جای دیگری باشد، پس خودمان
		 * صدایش می‌زنیم. نگهبانِ داخل متد جلوی تکرار را می‌گیرد.
		 */
		Ardal_Account::enqueue_assets();

		if ( ! is_user_logged_in() ) {
			$this->render_guest( $this->get_settings_for_display() );

			return;
		}

		$s = $this->get_settings_for_display();

		$this->apply_dashboard_overrides( $s );

		/*
		 * finally لازم است: اگر رندر پنل وسط کار خطا بدهد، بازنویسی‌ها نباید
		 * روی بقیهٔ ویجت‌های همان صفحه باقی بمانند.
		 */
		try {
			Ardal_Account::render_panel(
				array(
					'topbar'    => 'yes' === $s['show_topbar'],
					'mhead'     => 'yes' === $s['show_mhead'],
					'sidebar'   => 'yes' === $s['show_sidebar'],
					'tabbar'    => 'yes' === $s['show_tabbar'],
					'bell'      => 'yes' === $s['show_bell'],
					'chip'      => 'yes' === $s['show_chip'],
					'brand_sub' => $s['brand_sub'],
					'side'      => $s['side'],
				)
			);
		} finally {
			Ardal_Account_Settings::clear_overrides();
		}
	}

	/**
	 * حالت مهمان.
	 *
	 * @param array $s
	 */
	private function render_guest( $s ) {
		if ( 'hide' === $s['guest_mode'] ) {
			$this->render_empty( 'کاربر وارد نشده است؛ در سایت واقعی اینجا چیزی چاپ نمی‌شود.' );

			return;
		}

		if ( 'form' === $s['guest_mode'] ) {
			/*
			 * تمپلیت ورود اردال خودش پوستهٔ کامل (.ard-ac--auth) و اعلان‌های
			 * ووکامرس را چاپ می‌کند، پس دورش چیزی نمی‌پیچیم تا زمینه و فاصله
			 * دوبار روی هم نیفتد.
			 */
			wc_get_template( 'myaccount/form-login.php' );

			return;
		}

		echo '<div class="ard-ac ard-ac--guest">';

		$link = ! empty( $s['guest_link']['url'] )
			? $s['guest_link']['url']
			: wc_get_page_permalink( 'myaccount' );

		echo '<div class="ard-ac__body"><div class="ard-ac__content">';
		echo '<div class="ard-ac__card ard-ac__empty">';
		printf(
			'<span class="ard-ac__empty-icon">%s</span>',
			Ardal_Account::icon( 'user', 22 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);

		if ( '' !== trim( (string) $s['guest_title'] ) ) {
			printf(
				'<%1$s class="ard-ac__ctitle">%2$s</%1$s>',
				esc_html( self::tag( $s['guest_title_tag'], 'h3' ) ),
				esc_html( $s['guest_title'] )
			);
		}

		if ( '' !== trim( (string) $s['guest_text'] ) ) {
			printf( '<p class="ard-ac__csub">%s</p>', esc_html( $s['guest_text'] ) );
		}

		if ( '' !== trim( (string) $s['guest_btn'] ) ) {
			printf(
				'<a class="ard-ac__btn ard-ac__btn--primary" href="%s">%s</a>',
				esc_url( $link ),
				esc_html( $s['guest_btn'] )
			);
		}

		echo '</div></div></div></div>';
	}
}
