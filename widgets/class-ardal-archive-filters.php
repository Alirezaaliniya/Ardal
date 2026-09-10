<?php
/**
 * ویجت ستون فیلتر آرشیو فروشگاه.
 *
 * یک فرم GET ساده؛ هیچ درخواست ایجکسی در کار نیست. هر باکس فیلتر یک ردیف در
 * ریپیتر است، پس ترتیب و نوعشان کاملاً در المنتور تعیین می‌شود.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

class Ardal_Widget_Archive_Filters extends Ardal_Archive_Widget_Base {

	public function get_name() {
		return 'ardal-archive-filters';
	}

	public function get_title() {
		return 'اردال — فیلتر محصولات';
	}

	public function get_icon() {
		return 'eicon-filter';
	}

	protected function register_controls() {

		/* ------------------------------------------------ باکس‌های فیلتر */

		$this->start_controls_section(
			'section_boxes',
			array( 'label' => 'باکس‌های فیلتر' )
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'type',
			array(
				'label'       => 'نوع باکس',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'tax',
				'options'     => array(
					'search' => 'جست‌وجو در نتایج',
					'tax'    => 'تاکسونومی (دسته، برچسب، ویژگی…)',
					'price'  => 'بازه قیمت',
					'switch' => 'کلیدهای سریع (موجود، تخفیف‌دار، ویژه)',
					'rating' => 'امتیاز',
				),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'taxonomy',
			array(
				'label'       => 'تاکسونومی',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'product_cat',
				'options'     => Ardal_Archive::taxonomy_options(),
				'label_block' => true,
				'condition'   => array( 'type' => 'tax' ),
			)
		);

		$repeater->add_control(
			'label',
			array(
				'label'       => 'عنوان باکس',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'خالی = نام پیش‌فرض',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'open',
			array(
				'label'        => 'باز باشد',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => 'بله',
				'label_off'    => 'خیر',
				'return_value' => 'yes',
			)
		);

		$repeater->add_control(
			'style',
			array(
				'label'     => 'نمایش گزینه‌ها',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'list',
				'options'   => array(
					'list'   => 'فهرست تیک‌دار',
					'chip'   => 'چیپ‌های کنارهم',
					'swatch' => 'نمونه‌رنگ',
				),
				'condition' => array( 'type' => 'tax' ),
			)
		);

		$repeater->add_control(
			'counts',
			array(
				'label'        => 'نمایش تعداد محصول',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'type' => 'tax' ),
			)
		);

		$repeater->add_control(
			'searchable',
			array(
				'label'        => 'جعبه جست‌وجو داخل باکس',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'description'  => 'برای باکس‌هایی با گزینه‌های زیاد مثل برند.',
				'condition'    => array( 'type' => 'tax', 'style' => 'list' ),
			)
		);

		$repeater->add_control(
			'hide_empty',
			array(
				'label'        => 'پنهان‌کردن ترم‌های بدون محصول',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'type' => 'tax' ),
			)
		);

		$repeater->add_control(
			'limit',
			array(
				'label'       => 'حداکثر گزینه',
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 200,
				'default'     => 0,
				'description' => 'صفر یعنی بدون محدودیت.',
				'condition'   => array( 'type' => 'tax' ),
			)
		);

		$repeater->add_control(
			'terms_order',
			array(
				'label'     => 'ترتیب گزینه‌ها',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'name',
				'options'   => array(
					'name'  => 'الفبا',
					'count' => 'تعداد محصول',
					'order' => 'ترتیب دلخواه',
					'id'    => 'شناسه',
				),
				'condition' => array( 'type' => 'tax' ),
			)
		);

		$repeater->add_control(
			'only_children',
			array(
				'label'        => 'فقط ترم‌های سطح اول',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'condition'    => array( 'type' => 'tax' ),
			)
		);

		$repeater->add_control(
			'list_height',
			array(
				'label'       => 'حداکثر ارتفاع فهرست',
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array( 'px' => array( 'min' => 100, 'max' => 600 ) ),
				'default'     => array( 'unit' => 'px', 'size' => 260 ),
				'condition'   => array( 'type' => 'tax', 'style' => 'list' ),
			)
		);

		$repeater->add_control(
			'switch_keys',
			array(
				'label'     => 'کدام کلیدها',
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'default'   => array( 'stock', 'sale' ),
				'options'   => Ardal_Archive::switch_labels(),
				'condition' => array( 'type' => 'switch' ),
			)
		);

		$repeater->add_control(
			'stock_label',
			array(
				'label'     => 'متن کلید «موجود»',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'فقط کالاهای موجود',
				'condition' => array( 'type' => 'switch' ),
			)
		);

		$repeater->add_control(
			'sale_label',
			array(
				'label'     => 'متن کلید «تخفیف‌دار»',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'کالاهای تخفیف‌دار',
				'condition' => array( 'type' => 'switch' ),
			)
		);

		$repeater->add_control(
			'featured_label',
			array(
				'label'     => 'متن کلید «ویژه»',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'محصولات ویژه',
				'condition' => array( 'type' => 'switch' ),
			)
		);

		$repeater->add_control(
			'price_step',
			array(
				'label'       => 'گام اسلایدر قیمت',
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'default'     => 0,
				'description' => 'صفر یعنی خودکار.',
				'condition'   => array( 'type' => 'price' ),
			)
		);

		$repeater->add_control(
			'price_inputs',
			array(
				'label'        => 'نمایش عددها زیر اسلایدر',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'type' => 'price' ),
			)
		);

		$repeater->add_control(
			'search_placeholder',
			array(
				'label'     => 'متن راهنمای جست‌وجو',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'نام کالا را بنویسید…',
				'condition' => array( 'type' => 'search' ),
			)
		);

		$repeater->add_control(
			'rating_min',
			array(
				'label'     => 'کمترین امتیاز در فهرست',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 5,
				'default'   => 2,
				'condition' => array( 'type' => 'rating' ),
			)
		);

		$this->add_control(
			'boxes',
			array(
				'label'       => 'باکس‌ها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ label || type }}}',
				'default'     => array(
					array(
						'type'               => 'search',
						'label'              => 'جست‌وجو',
						'open'               => 'yes',
						'search_placeholder' => 'نام کالا را بنویسید…',
					),
					array(
						'type'     => 'tax',
						'taxonomy' => 'product_cat',
						'label'    => 'دسته‌بندی',
						'open'     => 'yes',
						'style'    => 'list',
						'counts'   => 'yes',
					),
					array(
						'type'        => 'switch',
						'label'       => 'فیلتر سریع',
						'open'        => 'yes',
						'switch_keys' => array( 'stock', 'sale' ),
					),
					array(
						'type'         => 'price',
						'label'        => 'بازه قیمت',
						'open'         => 'yes',
						'price_inputs' => 'yes',
					),
					array(
						'type'       => 'rating',
						'label'      => 'امتیاز',
						'open'       => '',
						'rating_min' => 2,
					),
				),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ رفتار */

		$this->start_controls_section(
			'section_behaviour',
			array( 'label' => 'رفتار و چیدمان' )
		);

		$this->add_control(
			'panel_title',
			array(
				'label'   => 'عنوان بالای ستون',
				'type'    => Controls_Manager::TEXT,
				'default' => 'فیلتر محصولات',
			)
		);

		$this->add_control(
			'show_panel_title',
			array(
				'label'        => 'نمایش عنوان در دسکتاپ',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'boxed',
			array(
				'label'        => 'قاب سفید دور ستون',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'sticky',
			array(
				'label'        => 'چسبیدن ستون هنگام اسکرول',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'sticky_top',
			array(
				'label'      => 'فاصله از بالا',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 220 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 20 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-fil' => '--ard-fil-top:{{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'sticky' => 'yes' ),
			)
		);

		$this->add_control(
			'mobile_mode',
			array(
				'label'       => 'نمایش در موبایل',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'drawer',
				'options'     => array(
					'drawer' => 'کشوی پایین‌آمدنی (با دکمه نوار ابزار)',
					'inline' => 'همیشه باز، داخل صفحه',
				),
				'label_block' => true,
			)
		);

		$this->add_control(
			'fab',
			array(
				'label'        => 'دکمه شناور فیلتر در موبایل',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'description'  => 'اگر ویجت «نوار مرتب‌سازی» را در صفحه ندارید، این را روشن کنید.',
				'condition'    => array( 'mobile_mode' => 'drawer' ),
			)
		);

		$this->add_control(
			'ajax',
			array(
				'label'        => 'بارگذاری بدون رفرش صفحه (ایجکس)',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => 'بله',
				'label_off'    => 'خیر',
				'return_value' => 'yes',
				'description'  => 'فیلتر، مرتب‌سازی و صفحه‌بندی بدون بارگذاری دوبارهٔ صفحه اعمال می‌شوند. آدرس صفحه باز هم عوض می‌شود، پس دکمهٔ بازگشت مرورگر و اشتراک‌گذاری لینک سر جایشان‌اند.',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'ajax_regions',
			array(
				'label'       => 'سلکتور ناحیه‌های اضافه',
				'type'        => Controls_Manager::TEXT,
				'default'     => '.elementor-widget-loop-grid',
				'label_block' => true,
				'description' => 'ناحیه‌هایی بیرون از ویجت‌های اردال که باید تازه شوند؛ مثلاً «لوپ گرید» المنتور. با ویرگول جدا کنید.',
				'condition'   => array( 'ajax' => 'yes' ),
			)
		);

		$this->add_control(
			'ajax_scroll',
			array(
				'label'        => 'اسکرول به بالای نتایج',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'بعد از هر بارگذاری، اگر فهرست بالاتر از دید کاربر باشد صفحه به ابتدای آن می‌رود.',
				'condition'    => array( 'ajax' => 'yes' ),
			)
		);

		$this->add_control(
			'auto_submit',
			array(
				'label'        => 'اعمال خودکار با هر تغییر',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'description'  => 'بدون دکمه «اعمال»؛ هر تیک بلافاصله صفحه را بارگذاری می‌کند.',
			)
		);

		$this->add_control(
			'apply_text',
			array(
				'label'     => 'متن دکمه اعمال',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'اعمال فیلتر',
				'condition' => array( 'auto_submit!' => 'yes' ),
			)
		);

		$this->add_control(
			'clear_text',
			array(
				'label'   => 'متن دکمه پاک‌کردن',
				'type'    => Controls_Manager::TEXT,
				'default' => 'پاک کردن',
			)
		);

		$this->add_control(
			'dirty_text',
			array(
				'label'     => 'پیام «هنوز اعمال نشده»',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'تغییرها هنوز اعمال نشده‌اند.',
				'condition' => array( 'auto_submit!' => 'yes' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ استایل */

		$this->start_controls_section(
			'section_style_fil',
			array(
				'label' => 'رنگ‌ها',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'accent',
			array(
				'label'     => 'رنگ تیک و کلیدها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-fil' => '--ard-fil-accent:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'head_color',
			array(
				'label'     => 'رنگ عنوان باکس‌ها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-fil__sec-title, {{WRAPPER}} .ard-fil__card-title' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'head_typo',
				'label'    => 'قلم عنوان باکس',
				'selector' => '{{WRAPPER}} .ard-fil__sec-title',
			)
		);

		$this->add_control(
			'opt_color',
			array(
				'label'     => 'رنگ گزینه‌ها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-fil__opt' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'opt_typo',
				'label'    => 'قلم گزینه‌ها',
				'selector' => '{{WRAPPER}} .ard-fil__opt',
			)
		);

		$this->add_control(
			'apply_bg',
			array(
				'label'     => 'زمینه دکمه اعمال',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-fil__apply' => 'background:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'apply_color',
			array(
				'label'     => 'متن دکمه اعمال',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-fil__apply' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'divider_color',
			array(
				'label'     => 'رنگ خط جداکننده',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-fil' => '--ard-fil-line:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->add_box_controls( '.ard-fil--boxed .ard-fil__inner', 'fil_box', 'قاب ستون', array( 'top' => 8, 'right' => 20, 'bottom' => 14, 'left' => 20 ) );
	}

	/* --------------------------------------------------------------------- */
	/* رندر                                                                    */
	/* --------------------------------------------------------------------- */

	protected function render() {
		if ( $this->require_woo() ) {
			return;
		}

		$s      = $this->get_settings_for_display();
		$boxes  = is_array( $s['boxes'] ) ? $s['boxes'] : array();
		$active = Ardal_Archive::active_count();

		$classes = array( 'ard-fil', 'ard-fil--' . ( 'inline' === $s['mobile_mode'] ? 'inline' : 'drawer' ) );

		if ( 'yes' === $s['boxed'] ) {
			$classes[] = 'ard-fil--boxed';
		}

		if ( 'yes' === $s['sticky'] ) {
			$classes[] = 'ard-fil--sticky';
		}

		printf(
			'<div class="%s" id="ard-filters" data-ard-filters%s%s>',
			esc_attr( implode( ' ', $classes ) ),
			'yes' === $s['auto_submit'] ? ' data-ard-auto="1"' : '',
			$this->ajax_attrs( $s ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- در همان تابع escape شده.
		);

		echo '<div class="ard-fil__inner">';

		// دستگیره و سربرگ فقط در حالت کشویی موبایل دیده می‌شوند.
		echo '<span class="ard-fil__grip" aria-hidden="true"></span>';

		echo '<div class="ard-fil__head">';
		printf(
			'<span class="ard-fil__head-title">%s%s</span>',
			Ardal_Archive::icon( 'filter', 18 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_html( $s['panel_title'] )
		);
		printf(
			'<button type="button" class="ard-fil__close" data-ard-arch-close aria-label="بستن">%s</button>',
			Ardal_Archive::icon( 'close', 18, 2 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
		echo '</div>';

		printf(
			'<form class="ard-fil__form" method="get" action="%s" data-ard-fil-form data-ard-region="filters">',
			esc_url( Ardal_Archive::base_url() )
		);

		// باکس‌ها اول ساخته می‌شوند تا بشود دید کدام پارامترها فیلد دارند و
		// کدام‌ها باید به‌شکل فیلد مخفی حفظ شوند.
		ob_start();

		foreach ( $boxes as $box ) {
			$this->render_box( $box, $s );
		}

		$fields = ob_get_clean();

		$this->render_hidden_fields( $fields );

		if ( 'yes' === $s['show_panel_title'] && '' !== trim( (string) $s['panel_title'] ) ) {
			printf(
				'<div class="ard-fil__card-head"><span class="ard-fil__card-title">%s</span>%s</div>',
				esc_html( $s['panel_title'] ),
				$active
					? '<span class="ard-fil__card-count">' . esc_html( Ardal_Helpers::fa_digits( $active ) ) . '</span>'
					: ''
			);
		}

		echo $fields; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- در همین کلاس escape شده.

		$this->render_actions( $s, $active );

		echo '</form></div></div>';

		Ardal_Archive::render_scrim();

		if ( 'yes' === $s['fab'] && 'drawer' === $s['mobile_mode'] ) {
			printf(
				'<button type="button" class="ard-fil__fab" data-ard-arch-open="filters" aria-controls="ard-filters">%s<span>%s</span>%s</button>',
				Ardal_Archive::icon( 'filter', 18 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				esc_html( $s['panel_title'] ),
				$active ? '<span class="ard-fil__fab-badge">' . esc_html( Ardal_Helpers::fa_digits( $active ) ) . '</span>' : ''
			);
		}
	}

	/**
	 * پارامترهایی که فرم نباید گمشان کند.
	 *
	 * سه دسته‌اند: مرتب‌سازی، پارامترهای خودِ آدرس پایه (در پیوندهای یکتای
	 * غیرزیبا)، و فیلترهای فعالی که باکسشان در همین ویجت نیست — بدون این، رفتن
	 * سراغ ستون فیلتر، فیلترهای دیگرِ صفحه را پاک می‌کرد.
	 *
	 * @param string $fields HTML باکس‌هایی که همین حالا ساخته شدند.
	 */
	protected function render_hidden_fields( $fields = '' ) {
		$keep = array();

		// در پیوندهای یکتای غیرزیبا، فرم GET کوئری‌استرینگِ action را دور می‌ریزد.
		$query = wp_parse_url( Ardal_Archive::base_url(), PHP_URL_QUERY );

		if ( $query ) {
			$parsed = array();
			wp_parse_str( $query, $parsed );

			foreach ( $parsed as $key => $value ) {
				if ( is_scalar( $value ) ) {
					$keep[ $key ] = (string) $value;
				}
			}
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current = wp_unslash( $_GET );
		$current = is_array( $current ) ? $current : array();

		foreach ( $current as $key => $value ) {
			$key = sanitize_key( $key );

			$ours = 0 === strpos( $key, Ardal_Archive::PREFIX )
				|| in_array( $key, array( 'orderby', 'min_price', 'max_price' ), true );

			if ( ! $ours ) {
				continue;
			}

			// اگر خودِ فرم برای این پارامتر فیلد دارد، نسخه‌ی مخفی اضافی است.
			if ( false !== strpos( $fields, 'name="' . $key . '"' ) || false !== strpos( $fields, 'name="' . $key . '[]"' ) ) {
				continue;
			}

			$keep[ $key ] = $value;
		}

		foreach ( $keep as $key => $value ) {
			foreach ( (array) $value as $one ) {
				if ( ! is_scalar( $one ) ) {
					continue;
				}

				printf(
					'<input type="hidden" name="%s" value="%s">',
					esc_attr( is_array( $value ) ? $key . '[]' : $key ),
					esc_attr( sanitize_text_field( (string) $one ) )
				);
			}
		}
	}

	/**
	 * یک باکس فیلتر.
	 *
	 * @param array $box
	 * @param array $s
	 */
	protected function render_box( $box, $s ) {
		switch ( $box['type'] ) {
			case 'search':
				$this->render_search( $box );
				break;

			case 'price':
				$this->render_price( $box );
				break;

			case 'switch':
				$this->render_switches( $box );
				break;

			case 'rating':
				$this->render_rating( $box );
				break;

			default:
				$this->render_tax( $box );
		}
	}

	/**
	 * باز و بسته‌ی یک آکاردئون.
	 *
	 * @param array  $box
	 * @param string $title
	 * @param int    $chosen تعداد انتخاب‌های فعال.
	 */
	protected function open_section( $box, $title, $chosen = 0 ) {
		printf(
			'<details class="ard-fil__sec"%s>',
			'yes' === ( $box['open'] ?? '' ) || $chosen ? ' open' : ''
		);

		echo '<summary class="ard-fil__sec-head">';
		printf( '<span class="ard-fil__sec-title">%s', esc_html( $title ) );
		printf(
			'<span class="ard-fil__pill" data-ard-fil-pill%s>%s</span>',
			$chosen ? '' : ' hidden',
			$chosen ? esc_html( Ardal_Helpers::fa_digits( $chosen ) ) : ''
		);
		echo '</span>';
		echo Ardal_Archive::icon( 'chev-d', 18, 1.8 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</summary><div class="ard-fil__body">';
	}

	/**
	 * بستن آکاردئون.
	 */
	protected function close_section() {
		echo '</div></details>';
	}

	/**
	 * باکس جست‌وجو.
	 *
	 * @param array $box
	 */
	protected function render_search( $box ) {
		$title = '' !== trim( (string) $box['label'] ) ? $box['label'] : 'جست‌وجو';
		$value = Ardal_Archive::param( Ardal_Archive::PREFIX . 's' );

		$this->open_section( $box, $title, '' !== $value ? 1 : 0 );

		printf(
			'<label class="ard-fil__search">%s<input type="search" name="%ss" value="%s" placeholder="%s"></label>',
			Ardal_Archive::icon( 'search', 17 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_attr( Ardal_Archive::PREFIX ),
			esc_attr( $value ),
			esc_attr( $box['search_placeholder'] )
		);

		$this->close_section();
	}

	/**
	 * باکس تاکسونومی.
	 *
	 * @param array $box
	 */
	protected function render_tax( $box ) {
		$taxonomy = isset( $box['taxonomy'] ) ? $box['taxonomy'] : 'product_cat';

		if ( ! taxonomy_exists( $taxonomy ) ) {
			return;
		}

		$options = Ardal_Archive::terms_for(
			$taxonomy,
			array(
				'limit'      => (int) $box['limit'],
				'hide_empty' => 'yes' === $box['hide_empty'],
				'orderby'    => $box['terms_order'],
				'parent'     => 'yes' === $box['only_children'] ? 0 : '',
			)
		);

		if ( ! $options ) {
			return;
		}

		$object = get_taxonomy( $taxonomy );
		$title  = '' !== trim( (string) $box['label'] )
			? $box['label']
			: ( $object ? $object->labels->singular_name : $taxonomy );

		$key    = Ardal_Archive::key_for_taxonomy( $taxonomy );
		$name   = Ardal_Archive::PREFIX . $key . '[]';
		$chosen = 0;

		foreach ( $options as $option ) {
			$chosen += $option['checked'] ? 1 : 0;
		}

		$this->open_section( $box, $title, $chosen );

		if ( 'yes' === $box['searchable'] && 'list' === $box['style'] ) {
			printf(
				'<label class="ard-fil__find">%s<input type="text" data-ard-fil-find placeholder="%s"></label>',
				Ardal_Archive::icon( 'search', 15 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				esc_attr( 'جست‌وجو در ' . $title )
			);
		}

		$height = ( 'list' === $box['style'] && ! empty( $box['list_height']['size'] ) )
			? ' style="max-height:' . (int) $box['list_height']['size'] . 'px"'
			: '';

		printf(
			'<ul class="ard-fil__opts ard-fil__opts--%s"%s>',
			esc_attr( $box['style'] ),
			$height // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);

		foreach ( $options as $option ) {
			$this->render_option( $option, $name, $box );
		}

		echo '</ul>';

		$this->close_section();
	}

	/**
	 * یک گزینه‌ی تاکسونومی.
	 *
	 * @param array  $option
	 * @param string $name
	 * @param array  $box
	 */
	protected function render_option( $option, $name, $box ) {
		$input = sprintf(
			'<input type="checkbox" name="%s" value="%s"%s>',
			esc_attr( $name ),
			esc_attr( $option['slug'] ),
			$option['checked'] ? ' checked' : ''
		);

		echo '<li>';
		printf(
			'<label class="ard-fil__opt%s" data-ard-fil-text="%s">',
			$option['checked'] ? ' is-on' : '',
			esc_attr( $option['label'] )
		);

		echo $input; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( 'swatch' === $box['style'] ) {
			printf(
				'<span class="ard-fil__swatch" style="background:%s" aria-hidden="true"></span>',
				esc_attr( Ardal_Helpers::guess_color( $option['label'] ) )
			);
		} elseif ( 'list' === $box['style'] ) {
			printf(
				'<span class="ard-fil__box" aria-hidden="true">%s</span>',
				Ardal_Archive::icon( 'check', 12, 3 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		printf( '<span class="ard-fil__opt-label">%s</span>', esc_html( $option['label'] ) );

		if ( 'yes' === $box['counts'] && $option['count'] > 0 ) {
			printf(
				'<span class="ard-fil__opt-count">%s</span>',
				esc_html( Ardal_Helpers::fa_digits( $option['count'] ) )
			);
		}

		echo '</label></li>';
	}

	/**
	 * باکس کلیدهای سریع.
	 *
	 * @param array $box
	 */
	protected function render_switches( $box ) {
		$keys = array_filter( (array) $box['switch_keys'] );

		if ( ! $keys ) {
			return;
		}

		$labels = array(
			'stock'    => $box['stock_label'],
			'sale'     => $box['sale_label'],
			'featured' => $box['featured_label'],
		);

		$title  = '' !== trim( (string) $box['label'] ) ? $box['label'] : 'فیلتر سریع';
		$chosen = 0;

		foreach ( $keys as $key ) {
			$chosen += Ardal_Archive::is_on( $key ) ? 1 : 0;
		}

		$this->open_section( $box, $title, $chosen );

		echo '<div class="ard-fil__switches">';

		foreach ( $keys as $key ) {
			$label = isset( $labels[ $key ] ) && '' !== trim( (string) $labels[ $key ] )
				? $labels[ $key ]
				: ( Ardal_Archive::switch_labels()[ $key ] ?? $key );

			printf(
				'<label class="ard-fil__switch"><span class="ard-fil__switch-label">%s</span><input type="checkbox" name="%s%s" value="1"%s><span class="ard-fil__track" aria-hidden="true"><span class="ard-fil__dot"></span></span></label>',
				esc_html( $label ),
				esc_attr( Ardal_Archive::PREFIX ),
				esc_attr( $key ),
				Ardal_Archive::is_on( $key ) ? ' checked' : ''
			);
		}

		echo '</div>';

		$this->close_section();
	}

	/**
	 * باکس بازه قیمت.
	 *
	 * @param array $box
	 */
	protected function render_price( $box ) {
		$bounds = Ardal_Archive::price_bounds();
		$min    = (int) $bounds['min'];
		$max    = (int) $bounds['max'];

		if ( $max <= $min ) {
			return;
		}

		$step = (int) $box['price_step'];

		if ( $step < 1 ) {
			// گام خودکار: صد پله روی کل بازه، گرد شده به عدد رُند.
			$step = max( 1000, (int) ( round( ( $max - $min ) / 100 / 1000 ) * 1000 ) );
		}

		$current_min = Ardal_Archive::param( 'min_price' );
		$current_max = Ardal_Archive::param( 'max_price' );
		$low         = '' !== $current_min ? max( $min, (int) $current_min ) : $min;
		$high        = '' !== $current_max ? min( $max, (int) $current_max ) : $max;

		$title  = '' !== trim( (string) $box['label'] ) ? $box['label'] : 'بازه قیمت';
		$chosen = ( '' !== $current_min || '' !== $current_max ) ? 1 : 0;

		$this->open_section( $box, $title, $chosen );

		printf(
			'<div class="ard-fil__range" data-ard-range data-bound-min="%d" data-bound-max="%d">',
			$min,
			$max
		);

		echo '<span class="ard-fil__range-track" aria-hidden="true"></span>';
		echo '<span class="ard-fil__range-fill" data-ard-range-fill aria-hidden="true"></span>';

		printf(
			'<input type="range" class="ard-fil__range-input" name="min_price" data-ard-range-min min="%1$d" max="%2$d" step="%3$d" value="%4$d" aria-label="کمترین قیمت">',
			$min,
			$max,
			$step,
			$low
		);

		printf(
			'<input type="range" class="ard-fil__range-input" name="max_price" data-ard-range-max min="%1$d" max="%2$d" step="%3$d" value="%4$d" aria-label="بیشترین قیمت">',
			$min,
			$max,
			$step,
			$high
		);

		echo '</div>';

		if ( 'yes' === $box['price_inputs'] ) {
			printf(
				'<div class="ard-fil__range-values"><span data-ard-range-out="min">%s</span><span class="ard-fil__range-sep">تا</span><span data-ard-range-out="max">%s</span><span class="ard-fil__range-unit">%s</span></div>',
				esc_html( Ardal_Helpers::fa_digits( number_format_i18n( $low ) ) ),
				esc_html( Ardal_Helpers::fa_digits( number_format_i18n( $high ) ) ),
				Ardal_Currency::unit() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		$this->close_section();
	}

	/**
	 * باکس امتیاز.
	 *
	 * @param array $box
	 */
	protected function render_rating( $box ) {
		$title  = '' !== trim( (string) $box['label'] ) ? $box['label'] : 'امتیاز';
		$lowest = max( 1, min( 5, (int) $box['rating_min'] ) );
		$chosen = Ardal_Archive::ratings();

		$this->open_section( $box, $title, count( $chosen ) );

		echo '<ul class="ard-fil__opts ard-fil__opts--rating">';

		for ( $star = 5; $star >= $lowest; $star-- ) {
			$on = in_array( $star, $chosen, true );

			echo '<li>';
			printf( '<label class="ard-fil__opt%s">', $on ? ' is-on' : '' );
			printf(
				'<input type="checkbox" name="%srating[]" value="%d"%s>',
				esc_attr( Ardal_Archive::PREFIX ),
				$star,
				$on ? ' checked' : ''
			);
			printf(
				'<span class="ard-fil__box" aria-hidden="true">%s</span>',
				Ardal_Archive::icon( 'check', 12, 3 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);

			echo '<span class="ard-fil__stars" aria-hidden="true">';

			for ( $i = 1; $i <= 5; $i++ ) {
				echo Ardal_Archive::star( $i <= $star, 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			echo '</span>';

			printf(
				'<span class="ard-fil__opt-label">%s ستاره به بالا</span>',
				esc_html( Ardal_Helpers::fa_digits( $star ) )
			);

			echo '</label></li>';
		}

		echo '</ul>';

		$this->close_section();
	}

	/**
	 * نوار دکمه‌های پایین فرم.
	 *
	 * @param array $s
	 * @param int   $active
	 */
	protected function render_actions( $s, $active ) {
		echo '<div class="ard-fil__actions" data-ard-fil-actions>';

		if ( 'yes' !== $s['auto_submit'] && '' !== trim( (string) $s['dirty_text'] ) ) {
			printf(
				'<p class="ard-fil__dirty" data-ard-fil-dirty role="status">%s%s</p>',
				Ardal_Archive::icon( 'refresh', 15 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				esc_html( $s['dirty_text'] )
			);
		}

		echo '<div class="ard-fil__actions-row">';

		if ( '' !== trim( (string) $s['clear_text'] ) ) {
			printf(
				'<a class="ard-fil__clear" href="%s">%s</a>',
				esc_url( Ardal_Archive::base_url() ),
				esc_html( $s['clear_text'] )
			);
		}

		if ( 'yes' !== $s['auto_submit'] ) {
			printf(
				'<button type="submit" class="ard-fil__apply"><span>%s</span><span class="ard-fil__apply-count" data-ard-fil-count hidden></span></button>',
				esc_html( $s['apply_text'] )
			);
		}

		echo '</div>';

		if ( $active ) {
			printf(
				'<span class="ard-fil__active-note">%s فیلتر فعال است</span>',
				esc_html( Ardal_Helpers::fa_digits( $active ) )
			);
		}

		echo '</div>';
	}
}
