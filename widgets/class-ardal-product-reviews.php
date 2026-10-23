<?php
/**
 * ویجت نظرات و امتیازدهی با طرح کارتی (کارت مورب، زبانهٔ نام کاربر و فرم ثبت).
 *
 * ثبت دیدگاه و پسند/نپسند از همان Ardal_Comments و ardal-post-comments.js
 * می‌آید که ویجت «نظرات نوشته/محصول» هم استفاده می‌کند؛ این ویجت فقط ظاهر
 * دیگری است. شکل‌ها با CSS و SVG ساخته شده‌اند و تصویر یا ماسکی لازم ندارند.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Product_Reviews extends Ardal_Widget_Base {

	/** مسیر ستاره در کادر ۲۴×۲۴. */
	const STAR = 'M12 2.8l2.75 5.6 6.15.9-4.45 4.33 1.05 6.12L12 16.86 6.5 19.75l1.05-6.12L3.1 9.3l6.15-.9z';

	/** زبانهٔ کاربر — عیناً از طرح (۱۹۴×۱۰۹). */
	const TAB = 'M193.192 63.4295C193.192 75.3463 184.448 85.4583 172.656 87.1782L27.5355 108.345C6.1011 111.471 -8.17967 86.8443 5.18004 69.7937L52.6588 9.19778C57.2081 3.39172 64.1744 0 71.5505 0L169.192 0C182.447 0 193.192 10.7452 193.192 24V63.4295Z';

	/*
	 * سر مورب کارت — ۸۳ واحد انتهای راستِ مسیر کارت طرح (۷۸۳×۱۰۵). بدنهٔ کارت
	 * عرض متغیر دارد و با CSS کشیده می‌شود؛ فقط این سر از SVG می‌آید تا انحنا و
	 * زاویه‌اش با عریض‌شدن کارت تغییر نکند.
	 */
	const CAP      = 'M0 0H58.821C78.056 0 89.476 21.4969 78.707 37.436L40.196 94.436C35.733 101.042 28.282 105 20.31 105H0Z';
	const CAP_LINE = 'M-4 0H58.821C78.056 0 89.476 21.4969 78.707 37.436L40.196 94.436C35.733 101.042 28.282 105 20.31 105H-4';

	public function get_name() {
		return 'ardal-product-reviews';
	}

	public function get_title() {
		return 'اردال — نظرات و امتیازدهی (طرح کارتی)';
	}

	public function get_icon() {
		return 'eicon-review';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT, Ardal_Elementor::CATEGORY );
	}

	public function get_keywords() {
		return array( 'اردال', 'نظرات', 'دیدگاه', 'امتیاز', 'ستاره', 'reviews', 'comments', 'rating' );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-product-reviews' );
	}

	public function get_script_depends() {
		return array( 'ardal', 'ardal-post-comments' );
	}

	protected function register_controls() {
		$this->register_list_controls();
		$this->register_rating_controls();
		$this->register_form_controls();
		$this->register_style_controls();
	}

	private function register_list_controls() {
		$this->start_controls_section(
			'section_list',
			array( 'label' => 'فهرست نظرات' )
		);

		$this->add_control(
			'heading',
			array(
				'label'       => 'عنوان بخش',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'نظرات',
				'label_block' => true,
			)
		);

		$this->add_tag_control( 'title_tag', 'تگ عنوان', 'h3' );

		$this->add_control(
			'show_count',
			array(
				'label'   => 'نمایش تعداد نظرات کنار عنوان',
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'per_page',
			array(
				'label'       => 'تعداد نمایش اولیه',
				'type'        => Controls_Manager::NUMBER,
				'default'     => 3,
				'min'         => 1,
				'max'         => 50,
				'separator'   => 'before',
				'description' => 'بقیه با دکمهٔ «نظرات بیشتر» باز می‌شوند.',
			)
		);

		$this->add_control(
			'max',
			array(
				'label'   => 'حداکثر نظرات',
				'type'    => Controls_Manager::NUMBER,
				'default' => 30,
				'min'     => 1,
				'max'     => 100,
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => 'ترتیب',
				'type'    => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => array(
					'DESC' => 'جدیدترین اول',
					'ASC'  => 'قدیمی‌ترین اول',
				),
			)
		);

		$this->add_control(
			'more_text',
			array(
				'label'   => 'متن دکمهٔ نظرات بیشتر',
				'type'    => Controls_Manager::TEXT,
				'default' => 'مشاهده نظرات بیشتر',
			)
		);

		$this->add_control(
			'show_avatar',
			array(
				'label'     => 'تصویر کاربر',
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_verified',
			array(
				'label'   => 'نشان «خریدار تأییدشده»',
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'quote_marks',
			array(
				'label'   => 'گیومه دور متن نظر',
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'show_date',
			array(
				'label'   => 'تاریخ نظر',
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'date_format',
			array(
				'label'     => 'قالب تاریخ',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'relative',
				'options'   => array(
					'relative' => 'زمان گذشته (۳ روز پیش)',
					'date'     => 'تاریخ شمسی',
				),
				'condition' => array( 'show_date' => 'yes' ),
			)
		);

		$this->add_control(
			'show_replies',
			array(
				'label'       => 'نمایش پاسخ‌ها',
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => 'پاسخ‌های مدیر یا کاربران زیر هر نظر (یک سطح).',
			)
		);

		$this->add_control(
			'enable_reactions',
			array(
				'label'     => 'پسند / نپسند',
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_vote_counts',
			array(
				'label'     => 'نمایش تعداد پسندها',
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array( 'enable_reactions' => 'yes' ),
			)
		);

		$this->add_control(
			'enable_share',
			array(
				'label'   => 'اشتراک‌گذاری نظر',
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'enable_reply',
			array(
				'label'   => 'دکمهٔ پاسخ به نظر',
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'empty_text',
			array(
				'label'       => 'متن بدون نظر',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'هنوز نظری ثبت نشده است. اولین نفر باشید!',
				'label_block' => true,
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'show_deco',
			array(
				'label'   => 'شکل تزئینی پشت نظرات',
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->end_controls_section();
	}

	private function register_rating_controls() {
		$this->start_controls_section(
			'section_rating',
			array( 'label' => 'امتیاز ستاره‌ای' )
		);

		$this->add_control(
			'enable_rating',
			array(
				'label'       => 'امتیازدهی ستاره‌ای',
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => 'انتخاب ستاره در فرم و نمایش آن روی نظرها. اگر در تنظیمات ووکامرس «امتیاز اجباری» روشن باشد، انتخاب ستاره در فرم محصول همیشه نمایش داده می‌شود.',
			)
		);

		$this->add_control(
			'show_card_stars',
			array(
				'label'     => 'ستاره‌ها روی هر نظر',
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array( 'enable_rating' => 'yes' ),
			)
		);

		$this->add_control(
			'show_summary',
			array(
				'label'     => 'میانگین امتیاز در سربرگ',
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array( 'enable_rating' => 'yes' ),
			)
		);

		$this->add_control(
			'rating_label',
			array(
				'label'     => 'برچسب امتیاز در فرم',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'امتیاز شما',
				'condition' => array( 'enable_rating' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	private function register_form_controls() {
		$this->start_controls_section(
			'section_form',
			array( 'label' => 'فرم ثبت نظر' )
		);

		$this->add_control(
			'show_form',
			array(
				'label'   => 'نمایش فرم',
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'form_heading',
			array(
				'label'       => 'عنوان فرم',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'ثبت نظرات',
				'label_block' => true,
				'condition'   => array( 'show_form' => 'yes' ),
			)
		);

		$this->add_control(
			'allow_guest',
			array(
				'label'       => 'ارسال برای مهمان',
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => 'اگر وردپرس ثبت‌نام را برای دیدگاه الزامی کرده باشد، این گزینه بی‌اثر است.',
				'condition'   => array( 'show_form' => 'yes' ),
			)
		);

		$this->add_control(
			'name_label',
			array(
				'label'     => 'برچسب نام',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'نام خانوادگی',
				'separator' => 'before',
				'condition' => array( 'show_form' => 'yes' ),
			)
		);

		$this->add_control(
			'show_phone',
			array(
				'label'     => 'فیلد شماره تماس',
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array( 'show_form' => 'yes' ),
			)
		);

		$this->add_control(
			'phone_label',
			array(
				'label'     => 'برچسب شماره تماس',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'شماره تماس',
				'condition' => array(
					'show_form'  => 'yes',
					'show_phone' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_email',
			array(
				'label'       => 'فیلد ایمیل',
				'type'        => Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => 'اگر در «تنظیمات › گفت‌وگو» نام و ایمیل الزامی باشد، این فیلد خودکار نمایش داده می‌شود.',
				'condition'   => array( 'show_form' => 'yes' ),
			)
		);

		$this->add_control(
			'message_label',
			array(
				'label'     => 'برچسب پیام',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'پیام',
				'condition' => array( 'show_form' => 'yes' ),
			)
		);

		$this->add_control(
			'message_placeholder',
			array(
				'label'     => 'متن راهنمای پیام',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'پیام خود را بنویسید...',
				'condition' => array( 'show_form' => 'yes' ),
			)
		);

		$this->add_control(
			'submit_text',
			array(
				'label'     => 'متن دکمه',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'ارسال',
				'condition' => array( 'show_form' => 'yes' ),
			)
		);

		$this->add_control(
			'closed_text',
			array(
				'label'       => 'متن بسته بودن نظرات',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'ثبت نظر برای این مورد بسته است.',
				'label_block' => true,
				'separator'   => 'before',
				'condition'   => array( 'show_form' => 'yes' ),
			)
		);

		$this->add_control(
			'login_text',
			array(
				'label'       => 'متن نیاز به ورود',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'برای ثبت نظر وارد حساب کاربری خود شوید.',
				'label_block' => true,
				'condition'   => array( 'show_form' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	private function register_style_controls() {
		$this->start_controls_section(
			'style_head',
			array(
				'label' => 'سربرگ‌ها',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'head_line',
			array(
				'label'     => 'رنگ خط سربرگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-line:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'head_title_color',
			array(
				'label'     => 'رنگ عنوان',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-head-title:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'head_title_typo',
				'selector' => '{{WRAPPER}} .ard-rv__title',
			)
		);

		$this->add_control(
			'head_icon_color',
			array(
				'label'     => 'رنگ آیکون عنوان نظرات',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-head-icon:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'badge_bg',
			array(
				'label'     => 'زمینهٔ آیکون فرم',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-badge-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'badge_color',
			array(
				'label'     => 'رنگ آیکون فرم',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-badge-color:{{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_deco',
			array(
				'label'     => 'شکل تزئینی',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_deco' => 'yes' ),
			)
		);

		$this->add_control(
			'deco_from',
			array(
				'label'     => 'رنگ آغاز',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-deco-from:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'deco_to',
			array(
				'label'     => 'رنگ پایان',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-deco-to:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'deco_hide_mobile',
			array(
				'label'        => 'پنهان در موبایل',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'prefix_class' => 'ard-rv-deco-mobile-',
				'return_value' => 'hide',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_rows',
			array(
				'label' => 'کارت نظر',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'row_gap',
			array(
				'label'      => 'فاصلهٔ نظرها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 80 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-rv' => '--rv-row-gap:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'skew',
			array(
				'label'       => 'زاویهٔ برش مورب',
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'deg' ),
				'range'       => array( 'deg' => array( 'min' => 0, 'max' => 35 ) ),
				'description' => 'صفر یعنی لبهٔ صاف.',
				'selectors'   => array( '{{WRAPPER}} .ard-rv' => '--rv-skew:{{SIZE}}deg;' ),
			)
		);

		$this->add_control(
			'card_bg',
			array(
				'label'     => 'زمینهٔ کارت',
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-card-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'card_shadow',
			array(
				'label'     => 'رنگ سایهٔ کارت',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-card-shadow:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'card_radius',
			array(
				'label'      => 'گردی گوشه‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 48 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-rv' => '--rv-card-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => 'رنگ متن نظر',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-text:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typo',
				'selector' => '{{WRAPPER}} .ard-rv__text',
			)
		);

		$this->add_control(
			'author_heading',
			array(
				'label'     => 'زبانهٔ کاربر',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'author_width',
			array(
				'label'      => 'عرض زبانه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 140, 'max' => 320 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-rv' => '--rv-author-w:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'author_bg',
			array(
				'label'     => 'زمینهٔ زبانه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-author-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'author_color',
			array(
				'label'     => 'رنگ نام',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-author:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'author_typo',
				'selector' => '{{WRAPPER}} .ard-rv__name',
			)
		);

		$this->add_responsive_control(
			'avatar_size',
			array(
				'label'      => 'اندازهٔ تصویر کاربر',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 32, 'max' => 90 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-rv' => '--rv-avatar:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'actions_heading',
			array(
				'label'     => 'آیکون‌های پسند و اشتراک',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'action_color',
			array(
				'label'     => 'رنگ آیکون‌ها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-action:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'like_color',
			array(
				'label'       => 'رنگ پسند و حالت فعال',
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array( '{{WRAPPER}} .ard-rv' => '--rv-accent:{{VALUE}};' ),
				'description' => 'رنگ اصلی ویجت؛ دکمهٔ ارسال و ستاره‌ها هم اگر جداگانه تعیین نشوند همین را می‌گیرند.',
			)
		);

		$this->add_responsive_control(
			'action_size',
			array(
				'label'      => 'اندازهٔ آیکون‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 16, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-rv' => '--rv-action-size:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_stars',
			array(
				'label'     => 'ستاره‌ها',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'enable_rating' => 'yes' ),
			)
		);

		$this->add_control(
			'star_color',
			array(
				'label'     => 'رنگ ستارهٔ پر',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-star:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'star_empty',
			array(
				'label'     => 'رنگ ستارهٔ خالی',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-star-empty:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'star_input_size',
			array(
				'label'      => 'اندازهٔ ستاره در فرم',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 16, 'max' => 48 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-rv' => '--rv-star-input:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_form',
			array(
				'label'     => 'فرم',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_form' => 'yes' ),
			)
		);

		$this->add_control(
			'form_bg',
			array(
				'label'     => 'زمینهٔ فرم',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-form-bg:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'form_padding',
			array(
				'label'      => 'فاصلهٔ داخلی فرم',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array( '{{WRAPPER}} .ard-rv__form-wrap' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => 'رنگ برچسب‌ها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-label:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typo',
				'label'    => 'تایپوگرافی برچسب',
				'selector' => '{{WRAPPER}} .ard-rv__field > span, {{WRAPPER}} .ard-rv__rate legend',
			)
		);

		$this->add_control(
			'input_bg',
			array(
				'label'     => 'زمینهٔ فیلدها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-input-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'input_color',
			array(
				'label'     => 'رنگ متن فیلدها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-input:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'button_bg',
			array(
				'label'     => 'زمینهٔ دکمه',
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-button-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => 'رنگ متن دکمه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-button:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'button_width',
			array(
				'label'      => 'عرض دکمه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array( 'min' => 100, 'max' => 400 ),
					'%'  => array( 'min' => 20, 'max' => 100 ),
				),
				'selectors'  => array( '{{WRAPPER}} .ard-rv__submit' => 'width:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$post_id = $this->post_id();

		if ( ! $post_id ) {
			$this->render_empty( 'این ویجت را در قالب نوشته یا محصول تکی قرار دهید.' );

			return;
		}

		$is_product = 'product' === get_post_type( $post_id );
		$product    = $is_product && function_exists( 'wc_get_product' ) ? wc_get_product( $post_id ) : null;

		/*
		 * امتیاز محصول تابع تنظیم ووکامرس است: اگر آنجا خاموش باشد سرور ستاره را
		 * ذخیره نمی‌کند و نمایش فرمش گمراه‌کننده است؛ اگر اجباری باشد، پنهان کردن
		 * ستاره‌ها فرم را غیرقابل ارسال می‌کند.
		 */
		$rating_supported = ! $is_product || ( function_exists( 'wc_review_ratings_enabled' ) && wc_review_ratings_enabled() );
		$rating_required  = $is_product && $rating_supported && function_exists( 'wc_review_ratings_required' ) && wc_review_ratings_required();
		$rating_on        = $rating_supported && 'yes' === $s['enable_rating'];

		$max      = max( 1, min( 100, (int) $s['max'] ) );
		$per_page = max( 1, min( $max, (int) $s['per_page'] ) );
		$roots    = $this->query_roots( $post_id, $is_product, $max, 'ASC' === $s['order'] ? 'ASC' : 'DESC' );
		$children = 'yes' === $s['show_replies'] && $roots ? $this->query_children( $roots ) : array();
		$demo     = ! $roots && Ardal_Product::is_editor();

		if ( $demo ) {
			$roots = self::demo_items();
		}

		$total   = $product ? (int) $product->get_review_count() : (int) get_comments_number( $post_id );
		$summary = $rating_on && 'yes' === $s['show_summary'] ? $this->summary( $product, $roots, $demo ) : null;
		$tag     = self::tag( $s['title_tag'], 'h3' );
		$uid     = 'ard-rv-' . $this->get_id();

		$classes = array( 'ard-rv' );
		if ( 'yes' !== $s['show_vote_counts'] ) {
			$classes[] = 'ard-rv--no-counts';
		}
		if ( 'yes' === $s['quote_marks'] ) {
			$classes[] = 'ard-rv--quotes';
		}
		?>
		<section id="<?php echo esc_attr( $uid ); ?>" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-ard-comments data-ajax="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( Ardal_Comments::NONCE_ACTION ) ); ?>" data-post-id="<?php echo esc_attr( $post_id ); ?>">
			<?php // تعریف‌های مشترک همهٔ کارت‌ها و زبانه‌های این ویجت. ?>
			<svg class="ard-rv__defs" width="0" height="0" aria-hidden="true" focusable="false">
				<defs>
					<linearGradient id="<?php echo esc_attr( $uid ); ?>-tab" x1="0.442744" y1="108.439" x2="144.107" y2="-41.0945" gradientUnits="userSpaceOnUse">
						<stop class="ard-rv__tab-stop"/>
						<stop offset="0.5" class="ard-rv__tab-stop" stop-opacity="0"/>
						<stop offset="1" class="ard-rv__tab-stop"/>
					</linearGradient>
					<clipPath id="<?php echo esc_attr( $uid ); ?>-cap"><path d="<?php echo esc_attr( self::CAP ); ?>"/></clipPath>
				</defs>
			</svg>

			<div class="ard-rv__stage">
				<?php if ( 'yes' === $s['show_deco'] ) : ?>
					<svg class="ard-rv__deco" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true" focusable="false">
						<defs>
							<linearGradient id="<?php echo esc_attr( $uid ); ?>-deco" x1="0" y1="0" x2="1" y2="1">
								<stop offset="0" class="ard-rv__deco-from"/>
								<stop offset="1" class="ard-rv__deco-to"/>
							</linearGradient>
						</defs>
						<path fill="url(#<?php echo esc_attr( $uid ); ?>-deco)" d="M0 2.6Q0 0 2.4 0H83.4Q85.6 0 85.6 2.6V6.4Q85.6 8.3 83.8 8.9L66 15.6Q64.2 16.3 63.2 17.9L5.6 98.6Q4.6 100 3 100Q0 100 0 96.4Z"/>
					</svg>
				<?php endif; ?>

				<header class="ard-rv__head">
					<span class="ard-rv__head-icon" aria-hidden="true"><?php echo self::chat_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<<?php echo esc_html( $tag ); ?> class="ard-rv__title">
						<?php echo esc_html( $s['heading'] ); ?>
						<?php if ( 'yes' === $s['show_count'] && $total > 0 ) : ?>
							<span class="ard-rv__count">(<?php echo esc_html( number_format_i18n( $total ) ); ?>)</span>
						<?php endif; ?>
					</<?php echo esc_html( $tag ); ?>>

					<?php if ( $summary ) : ?>
						<div class="ard-rv__summary">
							<?php echo self::stars( $summary['average'], 'ard-rv__stars ard-rv__stars--summary' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<strong><?php echo esc_html( number_format_i18n( $summary['average'], 1 ) ); ?></strong>
							<span>از ۵ (<?php echo esc_html( number_format_i18n( $summary['count'] ) ); ?> امتیاز)</span>
						</div>
					<?php endif; ?>
				</header>

				<?php if ( $roots ) : ?>
					<ol class="ard-rv__list">
						<?php
						foreach ( $roots as $i => $comment ) {
							$replies = isset( $children[ (int) $comment->comment_ID ] ) ? $children[ (int) $comment->comment_ID ] : array();
							$this->render_item( $comment, $replies, $s, $rating_on, $i >= $per_page );
						}
						?>
					</ol>

					<?php if ( count( $roots ) > $per_page ) : ?>
						<div class="ard-rv__more-wrap">
							<button type="button" class="ard-rv__more" data-rv-more data-step="<?php echo esc_attr( $per_page ); ?>"><?php echo esc_html( $s['more_text'] ); ?></button>
						</div>
					<?php endif; ?>
				<?php else : ?>
					<p class="ard-rv__empty"><?php echo esc_html( $s['empty_text'] ); ?></p>
				<?php endif; ?>
			</div>

			<?php
			if ( 'yes' === $s['show_form'] ) {
				$this->render_form( $s, $post_id, $is_product, $rating_supported && ( $rating_on || $rating_required ), $rating_required );
			}
			?>
		</section>
		<?php
	}

	/**
	 * شناسهٔ نوشته یا محصولی که نظرهایش نمایش داده می‌شود.
	 *
	 * @return int
	 */
	private function post_id() {
		$id = is_singular() ? (int) get_queried_object_id() : (int) get_the_ID();

		if ( $id && in_array( get_post_type( $id ), array( 'post', 'product' ), true ) ) {
			return $id;
		}

		// در ویرایشگر قالب تک‌محصول، آخرین محصول به‌جای محصول واقعی.
		$product = Ardal_Product::is_editor() ? Ardal_Product::current() : null;

		return $product ? (int) $product->get_id() : 0;
	}

	/**
	 * @param int    $post_id
	 * @param bool   $is_product
	 * @param int    $max
	 * @param string $order
	 * @return WP_Comment[]
	 */
	private function query_roots( $post_id, $is_product, $max, $order ) {
		return get_comments(
			array(
				'post_id'  => $post_id,
				'status'   => 'approve',
				'parent'   => 0,
				'type__in' => $is_product ? array( 'review', 'comment' ) : array( 'comment' ),
				'number'   => $max,
				'orderby'  => 'comment_date_gmt',
				'order'    => $order,
			)
		);
	}

	/**
	 * پاسخ‌های مستقیم نظرهای اصلی، گروه‌بندی‌شده با شناسهٔ والد.
	 *
	 * @param WP_Comment[] $roots
	 * @return array<int, WP_Comment[]>
	 */
	private function query_children( $roots ) {
		$replies = get_comments(
			array(
				'parent__in' => wp_list_pluck( $roots, 'comment_ID' ),
				'status'     => 'approve',
				'orderby'    => 'comment_date_gmt',
				'order'      => 'ASC',
			)
		);

		$out = array();
		foreach ( $replies as $reply ) {
			$out[ (int) $reply->comment_parent ][] = $reply;
		}

		return $out;
	}

	/**
	 * @param WC_Product|null $product
	 * @param array           $roots
	 * @param bool            $demo
	 * @return array|null array{average: float, count: int}
	 */
	private function summary( $product, $roots, $demo ) {
		if ( $product && ! $demo ) {
			$count = (int) $product->get_rating_count();

			return $count ? array( 'average' => (float) $product->get_average_rating(), 'count' => $count ) : null;
		}

		// برای نوشته‌ها ووکامرس میانگینی نگه نمی‌دارد؛ از نظرهای همین صفحه.
		$ratings = array();
		foreach ( $roots as $comment ) {
			$rating = $this->rating_of( $comment );
			if ( $rating > 0 ) {
				$ratings[] = $rating;
			}
		}

		return $ratings ? array( 'average' => array_sum( $ratings ) / count( $ratings ), 'count' => count( $ratings ) ) : null;
	}

	/**
	 * @param WP_Comment|object $comment
	 * @return int
	 */
	private function rating_of( $comment ) {
		if ( isset( $comment->ardal_demo ) ) {
			return (int) $comment->ardal_rating;
		}

		$rating = (int) get_comment_meta( $comment->comment_ID, 'rating', true );

		return $rating >= 1 && $rating <= 5 ? $rating : 0;
	}

	/**
	 * @param WP_Comment|object $comment
	 * @param WP_Comment[]      $replies
	 * @param array             $s
	 * @param bool              $rating_on
	 * @param bool              $hidden    بعد از «نظرات بیشتر» نمایش داده می‌شود.
	 */
	private function render_item( $comment, $replies, $s, $rating_on, $hidden ) {
		?>
		<li class="ard-rv__item"<?php echo $hidden ? ' data-rv-hidden hidden' : ''; ?>>
			<?php $this->render_row( $comment, $s, $rating_on, false ); ?>

			<?php if ( $replies ) : ?>
				<ol class="ard-rv__replies">
					<?php foreach ( $replies as $reply ) : ?>
						<li class="ard-rv__item ard-rv__item--reply"><?php $this->render_row( $reply, $s, false, true ); ?></li>
					<?php endforeach; ?>
				</ol>
			<?php endif; ?>
		</li>
		<?php
	}

	/**
	 * @param WP_Comment|object $comment
	 * @param array             $s
	 * @param bool              $rating_on
	 * @param bool              $is_reply
	 */
	private function render_row( $comment, $s, $rating_on, $is_reply ) {
		$demo   = isset( $comment->ardal_demo );
		$id     = (int) $comment->comment_ID;
		$author = $demo ? $comment->comment_author : get_comment_author( $comment );
		$rating = $rating_on && 'yes' === $s['show_card_stars'] ? $this->rating_of( $comment ) : 0;
		$text   = $demo ? wpautop( esc_html( $comment->comment_content ) ) : apply_filters( 'comment_text', get_comment_text( $comment ), $comment, array() );

		$verified = ! $demo && 'yes' === $s['show_verified'] && 'product' === get_post_type( $comment->comment_post_ID )
			&& function_exists( 'wc_review_is_from_verified_owner' ) && wc_review_is_from_verified_owner( $id );

		$likes    = 0;
		$dislikes = 0;
		if ( ! $demo && 'yes' === $s['enable_reactions'] ) {
			$votes    = get_comment_meta( $id, '_ardal_comment_votes', true );
			$votes    = is_array( $votes ) ? array_count_values( $votes ) : array();
			$likes    = isset( $votes['like'] ) ? $votes['like'] : 0;
			$dislikes = isset( $votes['dislike'] ) ? $votes['dislike'] : 0;
		}
		?>
		<article class="ard-rv__row<?php echo $is_reply ? ' ard-rv__row--reply' : ''; ?>"<?php echo $demo ? '' : ' id="comment-' . esc_attr( $id ) . '" data-comment-id="' . esc_attr( $id ) . '"'; ?>>
			<div class="ard-rv__author">
				<svg class="ard-rv__author-shape" viewBox="0 0 190 106" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path d="M76 0H168Q190 0 190 22V66Q190 84 172.1 85.9L19.9 102.1Q2 104 10.8 88.3L52.2 14Q60 0 76 0Z"/></svg>

				<?php if ( 'yes' === $s['show_avatar'] ) : ?>
					<span class="ard-rv__avatar">
						<?php
						if ( $demo ) {
							echo '<span class="ard-rv__initial">' . esc_html( mb_substr( $author, 0, 1 ) ) . '</span>';
						} else {
							echo get_avatar( $comment, 120, '', $author, array( 'class' => 'ard-rv__avatar-img' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
					</span>
				<?php endif; ?>

				<span class="ard-rv__who">
					<strong class="ard-rv__name"><?php echo esc_html( $author ); ?></strong>
					<?php if ( $rating ) : ?>
						<?php echo self::stars( $rating, 'ard-rv__stars' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php endif; ?>
					<?php if ( $verified ) : ?>
						<span class="ard-rv__verified">خریدار تأییدشده</span>
					<?php endif; ?>
				</span>
			</div>

			<div class="ard-rv__card">
				<div class="ard-rv__text"><?php echo wp_kses_post( $text ); ?></div>

				<div class="ard-rv__actions">
					<?php if ( 'yes' === $s['show_date'] ) : ?>
						<span class="ard-rv__date"><?php echo esc_html( $this->date_of( $comment, $s['date_format'] ) ); ?></span>
					<?php endif; ?>

					<?php if ( ! $demo && ! $is_reply && 'yes' === $s['enable_reply'] && comments_open( $comment->comment_post_ID ) ) : ?>
						<button class="ard-rv__reply" type="button" data-reply-to="<?php echo esc_attr( $id ); ?>" data-author="<?php echo esc_attr( $author ); ?>">پاسخ</button>
					<?php endif; ?>

					<span class="ard-rv__buttons">
						<?php if ( 'yes' === $s['enable_share'] ) : ?>
							<button class="ard-rv__btn" type="button" aria-label="اشتراک‌گذاری نظر"<?php echo $demo ? '' : ' data-share-url="' . esc_url( get_comment_link( $comment ) ) . '" data-share-title="' . esc_attr( $author . ' — ' . get_the_title( $comment->comment_post_ID ) ) . '"'; ?>>
								<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.6 3.4 3.7 9.5c-.8.3-.8 1.4 0 1.7l6.6 2.5 2.5 6.6c.3.8 1.4.8 1.7 0l6.1-16.9ZM10.3 13.7l4.2-4.2"/></svg>
							</button>
						<?php endif; ?>

						<?php if ( 'yes' === $s['enable_reactions'] ) : ?>
							<button class="ard-rv__btn ard-rv__btn--dislike" type="button" data-reaction="dislike" aria-label="نپسندیدم">
								<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.5 13V4H5a1.5 1.5 0 0 0-1.5 1.5v6A1.5 1.5 0 0 0 5 13h2.5Zm0 0 3.6 7.2a1.9 1.9 0 0 0 3.4-1.2v-4h4.6a2 2 0 0 0 2-2.3l-1.2-7A2 2 0 0 0 17.9 4H7.5"/></svg>
								<b data-vote-count="dislikes"><?php echo esc_html( $dislikes ); ?></b>
							</button>
							<button class="ard-rv__btn ard-rv__btn--like" type="button" data-reaction="like" aria-label="پسندیدم">
								<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.5 11v9H5a1.5 1.5 0 0 1-1.5-1.5v-6A1.5 1.5 0 0 1 5 11h2.5Zm0 0 3.6-7.2A1.9 1.9 0 0 1 14.5 5v4h4.6a2 2 0 0 1 2 2.3l-1.2 7a2 2 0 0 1-2 1.7H7.5"/></svg>
								<b data-vote-count="likes"><?php echo esc_html( $likes ); ?></b>
							</button>
						<?php endif; ?>
					</span>
				</div>
			</div>
		</article>
		<?php
	}

	/**
	 * @param array $s
	 * @param int   $post_id
	 * @param bool  $is_product
	 * @param bool  $show_rating
	 * @param bool  $rating_required
	 */
	private function render_form( $s, $post_id, $is_product, $show_rating, $rating_required ) {
		$verified_only = $is_product && 'yes' === get_option( 'woocommerce_review_rating_verification_required' );
		$guest_allowed = 'yes' === $s['allow_guest'] && ! get_option( 'comment_registration' ) && ! $verified_only;
		$logged_in     = is_user_logged_in();
		$can_review    = ! $verified_only || ( $logged_in && function_exists( 'wc_customer_bought_product' ) && wc_customer_bought_product( wp_get_current_user()->user_email, get_current_user_id(), $post_id ) );
		$email_needed  = (bool) get_option( 'require_name_email' );

		if ( ! comments_open( $post_id ) ) {
			$notice = $s['closed_text'];
		} elseif ( $logged_in ? ! $can_review : ! $guest_allowed ) {
			$notice = $verified_only ? 'برای ثبت نظر باید با حساب خریدارِ تأییدشدهٔ این محصول وارد شوید.' : $s['login_text'];
		} else {
			$notice = '';
		}
		?>
		<div class="ard-rv__form-head">
			<span class="ard-rv__badge" aria-hidden="true"><?php echo self::chat_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<h3 class="ard-rv__title"><?php echo esc_html( $s['form_heading'] ); ?></h3>
		</div>

		<div class="ard-rv__form-wrap">
			<?php if ( '' !== $notice ) : ?>
				<p class="ard-rv__notice">
					<?php echo esc_html( $notice ); ?>
					<?php if ( ! $logged_in && comments_open( $post_id ) ) : ?>
						<a href="<?php echo esc_url( wp_login_url( get_permalink( $post_id ) ) ); ?>">ورود</a>
					<?php endif; ?>
				</p>
			<?php else : ?>
				<div class="ard-rv__replying" data-replying hidden><span></span><button type="button" data-cancel-reply>لغو پاسخ</button></div>

				<form class="ard-rv__form" data-comment-form>
					<?php if ( $logged_in ) : ?>
						<p class="ard-rv__as">ارسال با نام <strong><?php echo esc_html( wp_get_current_user()->display_name ); ?></strong></p>
					<?php endif; ?>

					<div class="ard-rv__fields">
						<?php if ( ! $logged_in ) : ?>
							<label class="ard-rv__field">
								<span><?php echo esc_html( $s['name_label'] ); ?></span>
								<input name="first_name" type="text" autocomplete="name" maxlength="80" placeholder="<?php echo esc_attr( $s['name_label'] ); ?>" required>
							</label>
						<?php endif; ?>

						<?php if ( 'yes' === $s['show_phone'] ) : ?>
							<label class="ard-rv__field">
								<span><?php echo esc_html( $s['phone_label'] ); ?></span>
								<input name="phone" type="tel" inputmode="tel" autocomplete="tel" maxlength="20" dir="ltr" placeholder="<?php echo esc_attr( $s['phone_label'] ); ?>">
							</label>
						<?php endif; ?>

						<?php if ( ! $logged_in && ( 'yes' === $s['show_email'] || $email_needed ) ) : ?>
							<label class="ard-rv__field">
								<span>ایمیل</span>
								<input name="email" type="email" autocomplete="email" dir="ltr" placeholder="ایمیل"<?php echo $email_needed ? ' required' : ''; ?>>
							</label>
						<?php endif; ?>
					</div>

					<?php if ( $show_rating ) : ?>
						<fieldset class="ard-rv__rate">
							<legend><?php echo esc_html( $s['rating_label'] ); ?><?php echo $rating_required ? ' <span class="ard-rv__req" aria-hidden="true">*</span>' : ''; ?></legend>
							<div class="ard-rv__rate-stars">
								<?php for ( $star = 5; $star >= 1; $star-- ) : ?>
									<label>
										<input type="radio" name="rating" value="<?php echo esc_attr( $star ); ?>"<?php echo $rating_required ? ' required' : ''; ?>>
										<svg viewBox="0 0 24 24" aria-hidden="true"><path d="<?php echo esc_attr( self::STAR ); ?>"/></svg>
										<span class="screen-reader-text"><?php echo esc_html( number_format_i18n( $star ) . ' از ۵ ستاره' ); ?></span>
									</label>
								<?php endfor; ?>
							</div>
						</fieldset>
					<?php endif; ?>

					<label class="ard-rv__field ard-rv__field--message">
						<span><?php echo esc_html( $s['message_label'] ); ?></span>
						<textarea name="content" rows="4" maxlength="5000" placeholder="<?php echo esc_attr( $s['message_placeholder'] ); ?>" required></textarea>
					</label>

					<div class="ard-rv__honeypot" aria-hidden="true"><label>Website<input name="website" type="text" tabindex="-1" autocomplete="off"></label></div>

					<div class="ard-rv__footer">
						<button class="ard-rv__submit" type="submit">
							<span><?php echo esc_html( $s['submit_text'] ); ?></span>
							<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 12H5m0 0 6-6m-6 6 6 6"/></svg>
						</button>
						<span class="ard-rv__status" data-form-status role="status" aria-live="polite"></span>
					</div>
				</form>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * @param WP_Comment|object $comment
	 * @param string            $format
	 * @return string
	 */
	private function date_of( $comment, $format ) {
		$time = isset( $comment->ardal_demo ) ? time() - DAY_IN_SECONDS * 3 : (int) get_comment_time( 'U', true, false, $comment );

		if ( 'date' === $format ) {
			return Ardal_Jalali::format( $time, 'Y/m/d' );
		}

		return sprintf( '%s پیش', human_time_diff( $time, time() ) );
	}

	/**
	 * پنج ستاره با پرشدگی کسری (برای میانگین) — لایهٔ رنگی روی لایهٔ خاکستری.
	 *
	 * @param float  $value
	 * @param string $class
	 * @return string
	 */
	private static function stars( $value, $class ) {
		$value = max( 0, min( 5, (float) $value ) );
		$row   = str_repeat( '<svg viewBox="0 0 24 24"><path d="' . self::STAR . '"/></svg>', 5 );

		return sprintf(
			'<span class="%1$s" style="--rv-fill:%2$s%%" role="img" aria-label="%3$s"><span class="ard-rv__stars-base">%4$s</span><span class="ard-rv__stars-fill">%4$s</span></span>',
			esc_attr( $class ),
			esc_attr( round( $value * 20, 2 ) ),
			esc_attr( sprintf( 'امتیاز %s از ۵', number_format_i18n( $value, 1 ) ) ),
			$row
		);
	}

	/**
	 * @return string
	 */
	private static function chat_icon() {
		return '<svg viewBox="0 0 24 24"><path d="M12 21a9 9 0 1 0-7.9-4.7c.2.4.3.9.1 1.4L3.5 20.5l2.8-.6c.5-.1 1 0 1.4.2A9 9 0 0 0 12 21Z"/><path d="M8.5 10h7M8.5 14h4.5"/></svg>';
	}

	/**
	 * نظرهای نمونه برای ویرایشگر وقتی هنوز نظری ثبت نشده — تا طراح شکل واقعی را ببیند.
	 *
	 * @return object[]
	 */
	private static function demo_items() {
		$rows = array(
			array( 'الناز حسینی', 'کیفیت محصول واقعاً عالی بود و بعد از چند بار استفاده تغییرش رو روی پوستم حس کردم. از خریدم خیلی راضی‌ام و حتماً دوباره سفارش می‌دم.', 5 ),
			array( 'کیمیا مرادی', 'محصول خیلی باکیفیت بود و روی پوست حس سنگینی ایجاد نمی‌کرد. از نتیجه خیلی راضی بودم و حتماً دوباره تهیه‌اش می‌کنم.', 4 ),
			array( 'زیبا مظاهری', 'هم کیفیت محصول عالی بود و هم بسته‌بندی خیلی تمیز و زیبا انجام شده بود. خرید خیلی خوبی بود و از انتخابم کاملاً راضی هستم.', 5 ),
		);

		$out = array();
		foreach ( $rows as $i => $row ) {
			$out[] = (object) array(
				'ardal_demo'      => true,
				'comment_ID'      => 0 - ( $i + 1 ),
				'comment_post_ID' => 0,
				'comment_author'  => $row[0],
				'comment_content' => $row[1],
				'ardal_rating'    => $row[2],
			);
		}

		return $out;
	}
}
