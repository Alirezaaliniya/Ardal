<?php
/**
 * کلاس پایه ویجت‌های اردال — کنترل‌ها و رندرهای مشترک.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

abstract class Ardal_Widget_Base extends Widget_Base {

	/**
	 * کوئری آرشیوی که آخرین بار محصولات از آن خوانده شد.
	 *
	 * فقط وقتی پر است که ویجت روی کوئری اصلی نشسته باشد؛ همان شرطی که
	 * صفحه‌بندی هم به آن نیاز دارد.
	 *
	 * @var WP_Query|null
	 */
	protected $archive_query = null;

	/**
	 * @return array
	 */
	public function get_categories() {
		return array( 'ardal' );
	}

	/**
	 * عنوان ویجت در پنل المنتور با نام برند، و نام برند در کلیدواژه‌های
	 * جست‌وجو.
	 *
	 * @return array
	 */
	protected function get_initial_config() {
		$config = parent::get_initial_config();

		if ( Ardal_Brand::custom() ) {
			if ( isset( $config['title'] ) ) {
				$config['title'] = Ardal_Brand::text( $config['title'] );
			}

			$config['keywords'] = array_values(
				array_unique(
					array_map(
						array( 'Ardal_Brand', 'text' ),
						isset( $config['keywords'] ) ? (array) $config['keywords'] : array()
					)
				)
			);

			if ( ! in_array( Ardal_Brand::name(), $config['keywords'], true ) ) {
				$config['keywords'][] = Ardal_Brand::name();
			}
		}

		return $config;
	}

	/**
	 * برچسب و توضیح هر کنترل با نام برند (کنترل‌های واکنش‌گرا و گروهی هم
	 * از همین مسیر رد می‌شوند).
	 *
	 * @param string $id
	 * @param array  $args
	 * @param array  $options
	 * @return bool
	 */
	public function add_control( $id, array $args, $options = array() ) {
		return parent::add_control( $id, Ardal_Brand::control_args( $args ), $options );
	}

	/**
	 * @param string $section_id
	 * @param array  $args
	 */
	public function start_controls_section( $section_id, array $args = array() ) {
		parent::start_controls_section( $section_id, Ardal_Brand::control_args( $args ) );
	}

	/**
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'ardal' );
	}

	/**
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'ardal' );
	}

	/**
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-blockquote';
	}

	/**
	 * @return array
	 */
	public function get_custom_help_url() {
		return '';
	}

	/**
	 * بخش «عنوان بخش» که در چند ویجت تکرار می‌شود.
	 *
	 * @param string $default_title
	 * @param string $accent پیش‌فرض رنگ خط کنار عنوان: accent یا primary.
	 */
	/**
	 * تگ‌هایی که یک عنوان می‌تواند بگیرد.
	 *
	 * یک فهرست برای همهٔ ویجت‌ها، تا ساختار تیترهای صفحه (h1 یکی، بقیه
	 * زیرمجموعه) دست خود کاربر باشد و هر ویجت سلیقهٔ خودش را نداشته باشد.
	 *
	 * @return array
	 */
	public static function tag_options() {
		return array(
			'h1'   => 'H1',
			'h2'   => 'H2',
			'h3'   => 'H3',
			'h4'   => 'H4',
			'h5'   => 'H5',
			'h6'   => 'H6',
			'div'  => 'div (بدون وزن تیتر)',
			'p'    => 'p',
			'span' => 'span',
		);
	}

	/**
	 * نام تگ معتبر، وگرنه پیش‌فرض.
	 *
	 * جلوی تزریق از راه تنظیمات ذخیره‌شده را هم می‌گیرد، چون خروجی مستقیم
	 * داخل `<...>` چاپ می‌شود.
	 *
	 * @param string $value
	 * @param string $fallback
	 * @return string
	 */
	public static function tag( $value, $fallback = 'h2' ) {
		$value = is_string( $value ) ? $value : '';

		return isset( self::tag_options()[ $value ] ) ? $value : $fallback;
	}

	/**
	 * یک کنترل «تگ عنوان».
	 *
	 * @param string $name
	 * @param string $label
	 * @param string $default
	 * @param array  $args کلیدهای اضافه مثل condition و separator.
	 */
	protected function add_tag_control( $name, $label = 'تگ عنوان', $default = 'h2', $args = array() ) {
		$this->add_control(
			$name,
			array_merge(
				array(
					'label'       => $label,
					'type'        => Controls_Manager::SELECT,
					'default'     => $default,
					'options'     => self::tag_options(),
					'description' => 'فقط ساختار HTML را عوض می‌کند؛ ظاهر و اندازه از استایل می‌آید.',
				),
				$args
			)
		);
	}

	/**
	 * کنترل «نحوه جاگرفتن تصویر» (cover / contain).
	 *
	 * یک جا تعریف شده تا برچسب و گزینه‌ها در همهٔ ویجت‌ها یکی باشد و هر کارتی
	 * که تصویر دارد همین انتخاب را داشته باشد.
	 *
	 * @param string|array $selector سلکتور تصویر (یا چند سلکتور)، بدون {{WRAPPER}}.
	 * @param string       $default  cover یا contain.
	 * @param array        $args     کلیدهای اضافه مثل condition و separator.
	 */
	protected function add_media_fit_control( $selector, $default = 'cover', $args = array() ) {
		$parts = array();

		foreach ( (array) $selector as $one ) {
			$parts[] = '{{WRAPPER}} ' . $one;
		}

		$this->add_control(
			'media_fit',
			array_merge(
				array(
					'label'       => 'نحوه جاگرفتن تصویر',
					'type'        => Controls_Manager::SELECT,
					'default'     => $default,
					'options'     => array(
						'cover'   => 'قاب را پر کند (cover) — لبه‌ها بریده می‌شود',
						'contain' => 'کامل دیده شود (contain) — کنارش جا می‌ماند',
					),
					'label_block' => true,
					'selectors'   => array(
						implode( ', ', $parts ) => 'object-fit:{{VALUE}};',
					),
				),
				$args
			)
		);
	}

	protected function add_heading_controls( $default_title, $accent = 'accent' ) {
		$this->start_controls_section(
			'section_heading',
			array( 'label' => 'عنوان بخش' )
		);

		$this->add_control(
			'show_heading',
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
			'heading',
			array(
				'label'     => 'عنوان',
				'type'      => Controls_Manager::TEXT,
				'default'   => $default_title,
				'condition' => array( 'show_heading' => 'yes' ),
			)
		);

		$this->add_control(
			'subheading',
			array(
				'label'       => 'زیرعنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => 'اختیاری — زیر عنوان اصلی نمایش داده می‌شود.',
				'condition'   => array( 'show_heading' => 'yes' ),
			)
		);

		$this->add_tag_control(
			'heading_tag',
			'تگ عنوان',
			'h2',
			array( 'condition' => array( 'show_heading' => 'yes' ) )
		);

		$this->add_control(
			'heading_rule',
			array(
				'label'     => 'خط کنار عنوان',
				'type'      => Controls_Manager::SELECT,
				'default'   => $accent,
				'options'   => array(
					'accent'  => 'سبز',
					'primary' => 'آبی',
					'none'    => 'بدون خط',
				),
				'condition' => array( 'show_heading' => 'yes' ),
			)
		);

		$this->add_control(
			'heading_align',
			array(
				'label'     => 'چیدمان عنوان',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'start',
				'options'   => array(
					'start'  => 'راست‌چین',
					'center' => 'وسط‌چین',
				),
				'condition' => array( 'show_heading' => 'yes' ),
			)
		);

		$this->add_control(
			'more_text',
			array(
				'label'     => 'متن لینک «مشاهده همه»',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'مشاهده همه',
				'condition' => array(
					'show_heading'  => 'yes',
					'heading_align' => 'start',
				),
			)
		);

		$this->add_control(
			'more_link',
			array(
				'label'     => 'لینک «مشاهده همه»',
				'type'      => Controls_Manager::URL,
				'default'   => array( 'url' => '' ),
				'condition' => array(
					'show_heading'  => 'yes',
					'heading_align' => 'start',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * رندر عنوان بخش.
	 */
	protected function render_heading() {
		$s = $this->get_settings_for_display();

		if ( 'yes' !== $s['show_heading'] || '' === trim( (string) $s['heading'] ) ) {
			return;
		}

		$center = 'center' === $s['heading_align'];
		$rule   = $s['heading_rule'];

		printf( '<div class="ard-shead%s">', $center ? ' ard-shead--center' : '' );

		if ( ! $center && 'none' !== $rule ) {
			printf( '<span class="ard-shead__rule ard-shead__rule--%s"></span>', esc_attr( $rule ) );
		}

		$tag = self::tag( $s['heading_tag'] ?? 'h2', 'h2' );

		echo '<div class="ard-shead__text">';
		printf(
			'<%1$s class="ard-shead__title">%2$s</%1$s>',
			esc_html( $tag ),
			esc_html( $s['heading'] )
		);

		if ( '' !== trim( (string) $s['subheading'] ) ) {
			printf( '<p class="ard-shead__sub">%s</p>', esc_html( $s['subheading'] ) );
		}

		echo '</div>';

		if ( ! $center && ! empty( $s['more_link']['url'] ) && '' !== trim( (string) $s['more_text'] ) ) {
			// آیکون SVG به‌جای کاراکتر «›»: آن کاراکتر bidi-mirrored است و
			// جهتش را از راست‌چینی متن می‌گیرد، که قابل اتکا نیست.
			printf(
				'<a class="ard-shead__more"%s>%s %s</a>',
				Ardal_Helpers::link_attrs( $s['more_link'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خروجی داخل تابع escape شده.
				esc_html( $s['more_text'] ),
				Ardal_Helpers::icon( 'chevron-left', 15 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		echo '</div>';
	}

	/**
	 * کنترل‌های منبع داده محصول (دستی یا ووکامرس).
	 *
	 * @param int  $default_limit
	 * @param bool $with_archive آیا گزینهٔ «کوئری آرشیو جاری» و سازگاری با
	 *                           فیلترهای اردال هم اضافه شود.
	 */
	protected function add_source_controls( $default_limit = 5, $with_archive = false ) {
		$options = array( 'manual' => 'دستی (وارد کردن در لیست زیر)' );

		if ( Ardal_Helpers::has_woo() ) {
			if ( $with_archive ) {
				$options['archive'] = 'کوئری آرشیو جاری (با فیلترهای اردال)';
			}

			$options['recent']   = 'ووکامرس — جدیدترین';
			$options['best']     = 'ووکامرس — پرفروش‌ترین';
			$options['sale']     = 'ووکامرس — تخفیف‌دار';
			$options['featured'] = 'ووکامرس — ویژه';
			$options['rating']   = 'ووکامرس — پرامتیازترین';
		} else {
			$options['manual'] = 'دستی (ووکامرس فعال نیست)';
		}

		$this->add_control(
			'source',
			array(
				'label'       => 'منبع محصولات',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'manual',
				'options'     => $options,
				'label_block' => true,
			)
		);

		if ( ! Ardal_Helpers::has_woo() ) {
			return;
		}

		if ( $with_archive ) {
			$this->add_control(
				'archive_note',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => 'در این حالت، محصولات از همان کوئری صفحهٔ فروشگاه/دسته خوانده می‌شوند؛ یعنی فیلترها، جست‌وجو و مرتب‌سازی ویجت‌های آرشیو روی این شبکه هم اثر می‌گذارند.',
					'content_classes' => 'elementor-descriptor',
					'condition'       => array( 'source' => 'archive' ),
				)
			);

			$this->add_control(
				'archive_limit',
				array(
					'label'       => 'تعداد کل محصولات',
					'type'        => Controls_Manager::NUMBER,
					'min'         => 0,
					'max'         => 96,
					'default'     => 0,
					'description' => 'صفر یعنی هرچه در صفحهٔ جاری آرشیو هست.',
					'condition'   => array( 'source' => 'archive' ),
				)
			);
		}

		$this->add_control(
			'wc_category',
			array(
				'label'       => 'محدود به دسته‌بندی',
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => Ardal_Helpers::product_category_options(),
				'default'     => array(),
				'label_block' => true,
				'condition'   => array( 'source!' => array( 'manual', 'archive' ) ),
			)
		);

		$this->add_control(
			'wc_limit',
			array(
				'label'     => 'تعداد کل محصولات',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 48,
				'default'   => $default_limit,
				'condition' => array( 'source!' => array( 'manual', 'archive' ) ),
			)
		);

		if ( ! $with_archive ) {
			return;
		}

		$this->add_control(
			'respect_filters',
			array(
				'label'        => 'اعمال فیلترهای اردال روی همین کوئری',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => 'بله',
				'label_off'    => 'خیر',
				'return_value' => 'yes',
				'description'  => 'کوئری خودِ ویجت دست‌نخورده می‌ماند، فقط فیلترهای فعالِ آدرس رویش اعمال می‌شوند.',
				'condition'    => array( 'source!' => array( 'manual', 'archive' ) ),
			)
		);
	}

	/**
	 * محصولات صفحهٔ آرشیو جاری.
	 *
	 * روی خودِ آرشیو، کوئری اصلی خوانده می‌شود (پس صفحه‌بندی و فیلترها همان‌اند)؛
	 * بیرون از آرشیو — مثلاً پیش‌نمایش ویرایشگر — یک کوئری نمونه با همان فیلترها.
	 *
	 * @param array $settings
	 * @return array
	 */
	protected function get_archive_items( $settings ) {
		$limit = isset( $settings['archive_limit'] ) ? (int) $settings['archive_limit'] : 0;
		$main  = isset( $GLOBALS['wp_query'] ) ? $GLOBALS['wp_query'] : null;

		$this->archive_query = null;

		if ( $main instanceof WP_Query && $main->posts ) {
			$is_archive = $main->is_post_type_archive( 'product' )
				|| $main->is_tax( get_object_taxonomies( 'product' ) )
				|| 'product_query' === $main->get( 'wc_query' );

			if ( $is_archive ) {
				// فقط وقتی روی کوئری اصلی نشسته‌ایم صفحه‌بندی معنا دارد.
				$this->archive_query = $main;

				return Ardal_Helpers::cards_from_posts( $main->posts, $limit );
			}
		}

		return Ardal_Helpers::query_products_filtered(
			array(
				'source'  => 'recent',
				'limit'   => $limit > 0 ? $limit : 12,
				'filters' => true,
			)
		);
	}

	/**
	 * لیست نهایی آیتم‌های محصول با توجه به منبع انتخاب‌شده.
	 *
	 * @param array  $settings
	 * @param string $repeater_key
	 * @return array
	 */
	protected function get_product_items( $settings, $repeater_key = 'items' ) {
		$source = isset( $settings['source'] ) ? $settings['source'] : 'manual';

		if ( 'manual' === $source || ! Ardal_Helpers::has_woo() ) {
			return isset( $settings[ $repeater_key ] ) && is_array( $settings[ $repeater_key ] )
				? $settings[ $repeater_key ]
				: array();
		}

		if ( 'archive' === $source ) {
			return $this->get_archive_items( $settings );
		}

		$categories = isset( $settings['wc_category'] ) ? (array) $settings['wc_category'] : array();
		$limit      = isset( $settings['wc_limit'] ) ? (int) $settings['wc_limit'] : 5;

		// مسیر فیلتردار WP_Query است، چون wc_get_products شرط‌های دلخواه tax را
		// نمی‌پذیرد. تا وقتی فیلتری خواسته نشده، همان مسیر سبک قبلی می‌ماند.
		if ( 'yes' === ( $settings['respect_filters'] ?? '' ) ) {
			return Ardal_Helpers::query_products_filtered(
				array(
					'source'   => $source,
					'limit'    => $limit,
					'category' => implode( ',', $categories ),
					'filters'  => true,
				)
			);
		}

		return Ardal_Helpers::query_products(
			array(
				'source'   => $source,
				'limit'    => $limit,
				'category' => implode( ',', $categories ),
			)
		);
	}

	/**
	 * کارت محصول استاندارد (سبک دیزاین ۱a — با دکمه گرد سبز).
	 *
	 * @param array $item
	 * @param array $opts
	 */
	protected function render_product_card( $item, $opts = array() ) {
		$opts = wp_parse_args(
			$opts,
			array(
				'variant'     => 'framed',
				'show_rating' => true,
				'show_off'    => true,
				'show_fab'    => true,
				'show_button' => false,
				'button_text' => 'افزودن به سبد',
				'placeholder' => '۸۰۰×۸۰۰',
				'image_h'     => '',
				'notch'       => null,
				'link_mode'   => 'button',
				'button_action' => 'add',
				'title_tag'   => 'h3',
				/*
				 * آیا جای «قیمت قبل» رزرو شود؟
				 *
				 * صدازننده‌ای که کل فهرست را می‌بیند بهتر می‌داند: اگر هیچ
				 * محصولی تخفیف ندارد، آن سطرِ نامرئی فقط فضای خالی است.
				 */
				'reserve_old' => true,
				/*
				 * سطر امتیاز هم مثل «قیمت قبل»: فقط وقتی خالی چاپ می‌شود که
				 * کارت دیگری در همان شبکه امتیاز داشته باشد و بخواهیم دو کارت
				 * کنار هم یک‌جور دیده شوند.
				 */
				'reserve_rate' => true,
				/*
				 * نشانِ درصد کنار قیمتِ قبل بنشیند، نه گوشهٔ تصویر.
				 *
				 * سبک «آشکارشونده» جای خالیِ گوشهٔ تصویر را برای خودِ تصویر
				 * می‌خواهد و درصد را کنار قیمت خط‌خورده نشان می‌دهد.
				 */
				'off_inline' => false,
				/*
				 * نشان درصد و قیمتِ قبل روی هم بنشینند (نشان بالا، قیمت زیرش)
				 * یا کنار هم. دیزاینِ سبک «آشکارشونده» روی‌همِ آن‌ها را
				 * می‌خواهد.
				 */
				'off_stack'  => false,
				// نشانِ SVG داخل دکمهٔ متنی؛ خالی یعنی بدون آیکون.
				'icon_html'  => '',
				// سبک نماد واحد پول: default | slim | text.
				'currency'   => 'default',
			)
		);

		$title_tag = self::tag( $opts['title_tag'], 'h3' );

		// بریدگی گوشه فقط وقتی معنا دارد که دکمهٔ گرد روی همان گوشه بنشیند.
		if ( null === $opts['notch'] ) {
			$opts['notch'] = 'framed' === $opts['variant'] && $opts['show_fab'];
		}

		$title  = isset( $item['title'] ) ? $item['title'] : '';
		$price  = isset( $item['price'] ) ? Ardal_Helpers::price( $item['price'] ) : '';
		$old    = isset( $item['old_price'] ) ? Ardal_Helpers::price( $item['old_price'] ) : '';
		$off    = isset( $item['off'] ) && '' !== $item['off']
			? Ardal_Helpers::fa_digits( $item['off'] )
			: Ardal_Helpers::discount( $old, $price );
		$link   = isset( $item['link'] ) ? $item['link'] : array( 'url' => '' );
		$rating = isset( $item['rating'] ) ? (float) $item['rating'] : 0;
		$count  = isset( $item['count'] ) ? (int) $item['count'] : 0;
		$add    = isset( $item['add_url'] ) ? $item['add_url'] : array( 'url' => '' );

		$style = '' !== $opts['image_h'] ? ' style="--ard-card-img-h:' . esc_attr( $opts['image_h'] ) . 'px"' : '';

		$classes = array( 'ard-pcard', 'ard-pcard--' . $opts['variant'] );

		/*
		 * کارتِ ناموجود کم‌رنگ می‌شود.
		 *
		 * in_stock فقط وقتی معنا دارد که از ووکامرس آمده باشد؛ در حالت دستی
		 * این کلید نیست و کارت باید عادی بماند، نه اینکه همه‌شان خاکستری شوند.
		 */
		if ( isset( $item['in_stock'] ) && ! $item['in_stock'] ) {
			$classes[] = 'ard-pcard--oos';
		}

		if ( $opts['notch'] ) {
			$classes[] = 'ard-pcard--notch';
		}

		// «کل کارت لینک» با لینکِ کشیدهٔ روی عنوان ساخته می‌شود، نه با تگ a دور کل
		// کارت؛ چون دکمهٔ داخل کارت خودش لینک است و a تودرتو HTML نامعتبر می‌سازد.
		$box_link = 'box' === $opts['link_mode'] && ! empty( $link['url'] );

		if ( $box_link ) {
			$classes[] = 'ard-pcard--boxlink';
		}

		printf( '<article class="%s"%s>', esc_attr( implode( ' ', $classes ) ), $style ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<div class="ard-pcard__box">';

		echo '<div class="ard-pcard__media">';
		echo Ardal_Helpers::image( isset( $item['image'] ) ? $item['image'] : array(), $opts['placeholder'], $title, 'medium_large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $opts['show_off'] && '' !== $off ) {
			printf( '<span class="ard-badge ard-badge--sale">%s</span>', esc_html( $off ) );
		}

		if ( ! empty( $item['badge'] ) ) {
			printf( '<span class="ard-badge ard-badge--rank">%s</span>', esc_html( Ardal_Helpers::fa_digits( $item['badge'] ) ) );
		}

		echo '</div>';

		if ( '' !== $title || $box_link ) {
			/*
			 * متن عنوان داخل یک span می‌نشیند و کوتاه‌کردن چندخطی هم روی همان
			 * span است، نه روی خود عنوان یا لینک. دلیلش حالت «کل کارت لینک»
			 * است: آنجا لینکِ عنوان یک لایهٔ ::after روی کل کارت می‌کشد و اگر
			 * overflow: hidden روی عنوان یا لینک بنشیند، آن لایه بریده می‌شود
			 * و کلیکِ کارت از کار می‌افتد.
			 */
			if ( ! empty( $link['url'] ) ) {
				printf(
					'<%1$s class="ard-pcard__title"><a%2$s><span class="ard-pcard__title-text">%3$s</span></a></%1$s>',
					esc_html( $title_tag ),
					Ardal_Helpers::link_attrs( $link ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					esc_html( $title )
				);
			} else {
				printf(
					'<%1$s class="ard-pcard__title"><span class="ard-pcard__title-text">%2$s</span></%1$s>',
					esc_html( $title_tag ),
					esc_html( $title )
				);
			}
		}

		/*
		 * سطر امتیاز حتی بدون امتیاز هم چاپ می‌شود (خالی) تا جایش رزرو بماند؛
		 * وگرنه در کارتی که امتیاز ندارد، عنوان و قیمت بالا می‌پرند و کارت‌های
		 * یک ردیف ناهم‌تراز می‌شوند. ارتفاعِ رزرو در CSS است.
		 */
		if ( $opts['show_rating'] && ( $rating > 0 || $opts['reserve_rate'] ) ) {
			echo '<div class="ard-pcard__rate">';

			if ( $rating > 0 ) {
				echo Ardal_Helpers::stars( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				if ( $count > 0 ) {
					printf( '<span>(%s)</span>', esc_html( Ardal_Helpers::fa_digits( $count ) ) );
				}
			}

			echo '</div>';
		}

		/*
		 * سبک «آشکارشونده»: قیمت و دکمه داخل یک قاب می‌نشینند تا دکمه بتواند
		 * از پایین روی قیمت بیاید. بقیهٔ سبک‌ها همان ساختار قبلی را دارند،
		 * وگرنه CSS همه‌شان باید عوض می‌شد.
		 */
		$foot = 'reveal' === $opts['variant'];

		if ( $foot ) {
			echo '<div class="ard-pcard__foot">';
		}

		echo '<div class="ard-pcard__prices">';

		// در حالت روی‌هم، نشان و قیمتِ قبل یک ستون می‌شوند تا زیر هم بیفتند.
		$stack = $opts['off_stack'] && ( '' !== $old || ( $opts['off_inline'] && '' !== $off ) );

		if ( $stack ) {
			echo '<div class="ard-pcard__offbox">';

			if ( $opts['off_inline'] && '' !== $off ) {
				printf( '<span class="ard-pcard__off">%s</span>', esc_html( $off ) );
			}
		}

		if ( '' !== $old ) {
			printf( '<del class="ard-pcard__old">%s</del>', esc_html( $old ) );
		} elseif ( '' !== $price && $opts['reserve_old'] ) {
			// یک سطرِ نامرئی به‌جای قیمتِ قبل، تا خطِ قیمت با کارت‌های تخفیف‌دارِ
			// همان ردیف هم‌تراز بماند. اگر در کل فهرست تخفیفی نباشد، صدازننده
			// reserve_old را خاموش می‌کند و این سطر اصلاً چاپ نمی‌شود.
			echo '<del class="ard-pcard__old ard-pcard__old--empty" aria-hidden="true">&nbsp;</del>';
		}

		if ( $stack ) {
			echo '</div>';
		} elseif ( $opts['off_inline'] && '' !== $off ) {
			printf( '<span class="ard-pcard__off">%s</span>', esc_html( $off ) );
		}

		if ( '' !== $price ) {
			printf(
				'<div class="ard-pcard__price">%s %s</div>',
				esc_html( $price ),
				Ardal_Currency::unit( $opts['currency'] ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خروجی ثابت و امن.
			);
		}

		echo '</div>';

		if ( $opts['show_button'] ) {
			$this->render_card_action(
				$item,
				$opts,
				'ard-pcard__btn',
				Ardal_Helpers::svg( $opts['icon_html'] ) . '<span class="ard-pcard__btn-text">' . esc_html( $opts['button_text'] ) . '</span>',
				''
			);
		}

		if ( $foot ) {
			echo '</div>';
		}

		echo '</div>';

		if ( $opts['show_fab'] ) {
			$label = 'add' === $opts['button_action']
				? 'افزودن ' . $title . ' به سبد خرید'
				: 'مشاهده ' . $title;

			$this->render_card_action( $item, $opts, 'ard-pcard__fab', '<span aria-hidden="true">+</span>', $label );
		}

		echo '</article>';
	}

	/**
	 * دکمهٔ کنش کارت (دکمه متنی یا دکمهٔ گرد).
	 *
	 * بسته به button_action یا به سبد اضافه می‌کند یا به صفحهٔ محصول می‌برد.
	 * برای محصول ووکامرس، کلاس‌ها و data-attribute های خود ووکامرس هم چاپ می‌شوند
	 * تا افزودن به سبد بدون رفرش صفحه (ایجکس) کار کند؛ اگر اسکریپت ووکامرس در آن
	 * صفحه نباشد، href معمولی جواب می‌دهد و فقط صفحه رفرش می‌شود.
	 *
	 * @param array  $item
	 * @param array  $opts
	 * @param string $class
	 * @param string $inner  HTML داخل دکمه (از قبل escape شده).
	 * @param string $label  متن aria-label، خالی یعنی لازم نیست.
	 */
	protected function render_card_action( $item, $opts, $class, $inner, $label ) {
		$link = isset( $item['link'] ) ? $item['link'] : array( 'url' => '' );
		$add  = isset( $item['add_url'] ) ? $item['add_url'] : array( 'url' => '' );

		$to_cart = 'add' === $opts['button_action'] && ! empty( $add['url'] );
		$target  = $to_cart ? $add : $link;

		$attrs   = Ardal_Helpers::link_attrs( $target );
		$classes = array( $class );

		if ( $to_cart && ! empty( $item['product_id'] ) ) {
			$product = wc_get_product( (int) $item['product_id'] );

			if ( $product ) {
				echo Ardal_Helpers::woocommerce_add_to_cart_link(
					$product,
					$classes,
					wp_strip_all_tags( $inner ),
					array( 'data-ard-add-to-cart' => '' !== $label ? $label : 'true' ),
					isset( $opts['icon_html'] ) ? $opts['icon_html'] : ''
				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce API output.

				return;
			}

			$classes[] = 'add_to_cart_button';

			if ( ! empty( $item['ajax_add'] ) && ! Ardal_Assets::is_woocommerce_asset_disabled( 'disable_wc_add_to_cart_js' ) ) {
				$classes[] = 'ajax_add_to_cart';

				// اسکریپت ووکامرس در فوتر ثبت شده؛ همین‌جا صدا زدنش کافی است.
				wp_enqueue_script( 'wc-add-to-cart' );
			}

			$attrs .= sprintf(
				' data-product_id="%d" data-quantity="1" rel="nofollow"',
				(int) $item['product_id']
			);
		}

		printf(
			'<%1$s class="%2$s"%3$s%4$s>%5$s</%1$s>',
			esc_html( Ardal_Helpers::link_tag( $target ) ),
			esc_attr( implode( ' ', $classes ) ),
			$attrs, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- داخل link_attrs escape شده.
			'' !== $label ? ' aria-label="' . esc_attr( $label ) . '"' : '',
			$inner // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- از قبل escape شده.
		);
	}

	/**
	 * پیام راهنما وقتی هیچ آیتمی تنظیم نشده — فقط در ویرایشگر.
	 *
	 * @param string $message
	 * @return bool آیا پیام نمایش داده شد.
	 */
	/**
	 * کارت محصول سبک آرشیو (کارت سفید با نشان تخفیف و دکمهٔ علاقه‌مندی).
	 *
	 * ورودی‌اش همان آرایهٔ کارت است که render_product_card می‌گیرد، پس منبع دستی
	 * هم با این سبک کار می‌کند؛ فقط فیلدهایی که فقط از محصول ووکامرس درمی‌آیند
	 * (موجودی، دسته، علاقه‌مندی) در حالت دستی خالی می‌مانند و بخش‌شان چاپ نمی‌شود.
	 *
	 * @param array $item
	 * @param array $opts
	 */
	protected function render_archive_card( $item, $opts = array() ) {
		$opts = wp_parse_args(
			$opts,
			array(
				'image_ratio'       => '1',
				'show_badge'        => true,
				'badge_type'        => 'percent',
				'badge_text'        => 'حراج',
				'show_stock'        => true,
				'show_wishlist'     => true,
				'show_rating'       => true,
				'rating_style'      => 'both',
				'hide_empty_rating' => true,
				'show_cat'          => false,
				'show_subtitle'     => false,
				'title_tag'         => 'h3',
				'show_price'        => true,
				'show_old_price'    => true,
				'show_button'       => true,
				'button_style'      => 'solid',
				'button_text'       => 'افزودن به سبد',
				'button_action'     => 'add',
				'placeholder'       => '۸۰۰×۸۰۰',
				// در حلقهٔ ووکامرس li است؛ داخل شبکهٔ اردال، div (چون سلول شبکه
				// خودش لیست نیست و li بیرون از ul مارک‌آپ نامعتبر می‌سازد).
				'tag'               => 'li',
			)
		);

		$root = in_array( $opts['tag'], array( 'li', 'div', 'article' ), true ) ? $opts['tag'] : 'li';

		$title   = isset( $item['title'] ) ? (string) $item['title'] : '';
		$link    = isset( $item['link'] ) ? $item['link'] : array( 'url' => '' );
		$href    = ! empty( $link['url'] ) ? $link['url'] : '';
		$rating  = isset( $item['rating'] ) ? (float) $item['rating'] : 0;
		$count   = isset( $item['count'] ) ? (int) $item['count'] : 0;
		$id      = isset( $item['product_id'] ) ? (int) $item['product_id'] : 0;
		$in_cart = ! isset( $item['in_stock'] ) || $item['in_stock'];
		$tag     = self::tag( $opts['title_tag'], 'h3' );

		$classes = array( 'ard-acard' );

		if ( ! $in_cart ) {
			$classes[] = 'is-out';
		}

		if ( ! empty( $item['wc_classes'] ) ) {
			$classes[] = $item['wc_classes'];
		}

		printf(
			'<%s class="%s" style="--ard-acard-ratio:%s">',
			esc_html( $root ),
			esc_attr( implode( ' ', $classes ) ),
			esc_attr( $opts['image_ratio'] )
		);

		/* ------------------------------------------------- تصویر */
		echo '<div class="ard-acard__media">';

		$image = ! empty( $item['image_id'] )
			? wp_get_attachment_image( (int) $item['image_id'], 'woocommerce_thumbnail', false, array( 'loading' => 'lazy', 'alt' => $title ) )
			: Ardal_Helpers::image( isset( $item['image'] ) ? $item['image'] : array(), $opts['placeholder'], $title, 'medium_large' );

		if ( '' !== $href ) {
			printf(
				'<a class="ard-acard__media-link" href="%s" aria-label="%s">%s</a>',
				esc_url( $href ),
				esc_attr( $title ),
				$image // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خروجی وردپرس/هلپر.
			);
		} else {
			printf( '<span class="ard-acard__media-link">%s</span>', $image ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		if ( $opts['show_badge'] ) {
			$off  = isset( $item['off'] ) ? (string) $item['off'] : '';
			$text = 'text' === $opts['badge_type'] ? $opts['badge_text'] : $off;

			if ( '' !== $off && '' !== $text ) {
				printf( '<span class="ard-acard__badge">%s</span>', esc_html( $text ) );
			}
		}

		if ( $opts['show_stock'] && ! $in_cart ) {
			echo '<span class="ard-acard__flag">ناموجود</span>';
		}

		if ( $opts['show_wishlist'] && $id && Ardal_Product::wishlist_on() ) {
			printf(
				'<button type="button" class="ard-acard__wish" data-ard-wishlist data-product-id="%d" data-label-off="افزودن به علاقه‌مندی‌ها" data-label-on="حذف از علاقه‌مندی‌ها" aria-pressed="false" aria-label="افزودن به علاقه‌مندی‌ها">%s</button>',
				$id,
				Ardal_Helpers::icon( 'heart', 17 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		if ( $opts['show_button'] && 'fab' === $opts['button_style'] ) {
			$this->render_card_action( $item, $opts, 'ard-acard__fab', '<span aria-hidden="true">+</span>', 'افزودن ' . $title . ' به سبد خرید' );
		}

		echo '</div>';

		/* ------------------------------------------------- امتیاز و دسته */
		$show_rating = $opts['show_rating'] && ( $rating > 0 || ! $opts['hide_empty_rating'] );
		$cat         = ( $opts['show_cat'] && ! empty( $item['cat'] ) ) ? (string) $item['cat'] : '';

		/*
		 * تا وقتی یکی از این دو روشن است، سطرِ متا چاپ می‌شود — حتی خالی — تا
		 * جایش رزرو بماند و کارتِ بدون امتیاز، عنوان و قیمتش بالا نپرد.
		 * ارتفاعِ رزرو (min-height) در CSS است.
		 */
		if ( $opts['show_rating'] || $opts['show_cat'] ) {
			echo '<div class="ard-acard__meta">';

			if ( '' !== $cat ) {
				printf( '<span class="ard-acard__cat">%s</span>', esc_html( $cat ) );
			}

			if ( $show_rating ) {
				echo '<span class="ard-acard__rating" dir="ltr">';

				if ( 'num' !== $opts['rating_style'] ) {
					echo '<span class="ard-acard__stars">';

					for ( $i = 1; $i <= 5; $i++ ) {
						echo Ardal_Archive::star( $i <= round( $rating ), 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}

					echo '</span>';
				}

				if ( 'stars' !== $opts['rating_style'] ) {
					printf(
						'<span class="ard-acard__rate-num">%s</span>',
						esc_html( Ardal_Helpers::fa_digits( number_format_i18n( $rating, 1 ) ) )
					);
				}

				if ( $count > 0 ) {
					printf(
						// ard-sr مال خود افزونه است؛ screen-reader-text را قالب یا
						// ووکامرس تعریف می‌کند و آن دو ممکن است خاموش باشند.
						'<span class="ard-sr screen-reader-text">بر پایه %s نظر</span>',
						esc_html( Ardal_Helpers::fa_digits( number_format_i18n( $count ) ) )
					);
				}

				echo '</span>';
			}

			echo '</div>';
		}

		/* ------------------------------------------------- عنوان */
		printf( '<%s class="ard-acard__name woocommerce-loop-product__title">', esc_html( $tag ) );

		if ( '' !== $href ) {
			printf( '<a href="%s">', esc_url( $href ) );
		}

		printf( '<span class="ard-acard__name-fa">%s</span>', esc_html( $title ) );

		if ( $opts['show_subtitle'] && ! empty( $item['subtitle'] ) ) {
			printf( '<span class="ard-acard__name-en">%s</span>', esc_html( $item['subtitle'] ) );
		}

		if ( '' !== $href ) {
			echo '</a>';
		}

		printf( '</%s>', esc_html( $tag ) );

		/* ------------------------------------------------- قیمت */
		if ( $opts['show_price'] ) {
			$price = isset( $item['price'] ) ? Ardal_Helpers::price( $item['price'] ) : '';
			$old   = isset( $item['old_price'] ) ? Ardal_Helpers::price( $item['old_price'] ) : '';

			echo '<div class="ard-acard__prices">';

			if ( $opts['show_old_price'] && '' !== $old ) {
				printf( '<del class="ard-acard__old">%s</del>', esc_html( $old ) );
			}

			if ( '' !== $price ) {
				printf(
					'<span class="ard-acard__price">%s %s</span>',
					esc_html( $price ),
					Ardal_Currency::unit() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			} else {
				echo '<span class="ard-acard__price ard-acard__price--call">تماس بگیرید</span>';
			}

			echo '</div>';
		}

		/* ------------------------------------------------- دکمه */
		if ( $opts['show_button'] && 'fab' !== $opts['button_style'] ) {
			$this->render_card_action(
				$item,
				$opts,
				'ard-acard__btn ard-acard__btn--' . $opts['button_style'],
				esc_html( $opts['button_text'] ),
				''
			);
		}

		printf( '</%s>', esc_html( $root ) );
	}

	/**
	 * صفحه‌بندی حلقهٔ آرشیو.
	 *
	 * فقط وقتی معنا دارد که ویجت روی کوئری اصلی نشسته باشد؛ کوئری دلخواه
	 * صفحه‌بندی ندارد.
	 *
	 * @param WP_Query $query
	 * @param array    $opts mid و prev/next.
	 */
	protected function render_grid_pager( $query, $opts = array() ) {
		$opts  = wp_parse_args( $opts, array( 'mid' => 2 ) );
		$stats = Ardal_Archive::loop_stats( $query );

		if ( $stats['pages'] <= 1 ) {
			return;
		}

		$base = esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );

		echo '<nav class="ard-pager woocommerce-pagination" aria-label="صفحه‌بندی محصولات">';

		echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			array(
				'base'      => $base,
				'format'    => '',
				'add_args'  => false,
				'current'   => max( 1, $stats['current'] ),
				'total'     => $stats['pages'],
				'prev_text' => Ardal_Archive::icon( 'chev-r', 18, 2 ),
				'next_text' => Ardal_Archive::icon( 'chev-l', 18, 2 ),
				'type'      => 'list',
				'end_size'  => 1,
				'mid_size'  => max( 0, (int) $opts['mid'] ),
			)
		);

		echo '</nav>';
	}

	/**
	 * حالت «نتیجه‌ای نیست».
	 *
	 * @param array $opts title، text، button.
	 */
	protected function render_grid_empty( $opts = array() ) {
		$opts = wp_parse_args(
			$opts,
			array(
				'title'  => 'محصولی پیدا نشد.',
				'text'   => '',
				'button' => '',
			)
		);

		$active = class_exists( 'Ardal_Archive' ) ? Ardal_Archive::active_count() : 0;

		echo '<div class="ard-arch-empty woocommerce-info">';
		printf(
			'<span class="ard-arch-empty__art" aria-hidden="true">%s</span>',
			Ardal_Archive::icon( 'empty', 54, 1.2 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);

		if ( '' !== trim( (string) $opts['title'] ) ) {
			printf( '<p class="ard-arch-empty__title">%s</p>', esc_html( $opts['title'] ) );
		}

		if ( '' !== trim( (string) $opts['text'] ) ) {
			printf( '<p class="ard-arch-empty__text">%s</p>', esc_html( $opts['text'] ) );
		}

		if ( $active && '' !== trim( (string) $opts['button'] ) ) {
			printf(
				'<a class="ard-arch-empty__btn" href="%s">%s</a>',
				esc_url( Ardal_Archive::base_url() ),
				esc_html( $opts['button'] )
			);
		}

		echo '</div>';
	}

	/**
	 * پیام حالت ویرایش وقتی ووکامرس نیست.
	 *
	 * روی پایهٔ مشترک است نه فقط ویجت‌های آرشیو: هر ویجتی که به داده‌ی محصول
	 * دست می‌زند همین بررسی را لازم دارد.
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

	/**
	 * آیکون‌های آمادهٔ دکمهٔ کارت.
	 *
	 * @return array
	 */
	public static function card_icon_options() {
		return array(
			'bag-smile' => 'کیف خرید با لبخند',
			'bag'       => 'کیف خرید ساده',
			'cart'      => 'چرخ خرید',
			'plus'      => 'به‌علاوه',
			'arrow'     => 'فلش',
			'none'      => 'بدون آیکون',
		);
	}

	/**
	 * نشانِ SVG یکی از آیکون‌های آماده.
	 *
	 * @param string $name
	 * @return string
	 */
	public static function card_icon( $name ) {
		$paths = array(
			'bag-smile' => '<path d="M5.6 8.4h12.8l-1.1 11.3a2 2 0 0 1-2 1.8H8.7a2 2 0 0 1-2-1.8z"/><path d="M9 8.4V6.5a3 3 0 0 1 6 0v1.9"/><path d="M9.9 13.1a2.7 2.7 0 0 0 4.2 0"/>',
			'bag'       => '<path d="M5.6 8.4h12.8l-1.1 11.3a2 2 0 0 1-2 1.8H8.7a2 2 0 0 1-2-1.8z"/><path d="M9 8.4V6.5a3 3 0 0 1 6 0v1.9"/>',
			'cart'      => '<path d="M2.8 4h2.1l2.3 10.6a2 2 0 0 0 2 1.6h7a2 2 0 0 0 2-1.5L20 7H6"/><circle cx="10" cy="19.4" r="1.4"/><circle cx="17" cy="19.4" r="1.4"/>',
			'plus'      => '<path d="M12 6v12"/><path d="M6 12h12"/>',
			'arrow'     => '<path d="M19 12H5"/><path d="M11 6l-6 6 6 6"/>',
		);

		if ( ! isset( $paths[ $name ] ) ) {
			return '';
		}

		return sprintf(
			'<svg class="ard-pcard__btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%s</svg>',
			$paths[ $name ]
		);
	}

	/**
	 * نشانِ نهایی دکمه: انتخابِ آیکون‌پیکر المنتور، وگرنه آیکون آمادهٔ اردال.
	 *
	 * @param array $s تنظیمات ویجت.
	 * @return string
	 */
	protected function card_button_icon( $s ) {
		$pick = isset( $s['reveal_icon_custom'] ) ? $s['reveal_icon_custom'] : array();

		if ( ! empty( $pick['value'] ) && class_exists( '\Elementor\Icons_Manager' ) ) {
			ob_start();
			\Elementor\Icons_Manager::render_icon( $pick, array( 'aria-hidden' => 'true', 'class' => 'ard-pcard__btn-icon' ) );

			return (string) ob_get_clean();
		}

		return self::card_icon( isset( $s['reveal_icon'] ) ? $s['reveal_icon'] : 'bag-smile' );
	}

	/**
	 * بخش کامل تنظیمات سبک «دکمهٔ آشکارشونده».
	 *
	 * یک‌جا نگهداری می‌شود چون هر سه ویجتی که کارت محصول می‌سازند همین سبک را
	 * دارند؛ اگر هرکدام نسخهٔ خودش را داشت، با اولین تغییر از هم می‌افتادند.
	 */
	protected function add_reveal_controls() {
		$this->start_controls_section(
			'section_reveal',
			array(
				'label'     => 'سبک آشکارشونده',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'variant' => 'reveal' ),
			)
		);

		/* ---------------------------------------- دکمه */

		$this->add_control(
			'reveal_btn_heading',
			array( 'label' => 'دکمه', 'type' => Controls_Manager::HEADING )
		);

		$this->add_control(
			'reveal_icon',
			array(
				'label'   => 'آیکون',
				'type'    => Controls_Manager::SELECT,
				'default' => 'bag-smile',
				'options' => self::card_icon_options(),
			)
		);

		$this->add_control(
			'reveal_icon_custom',
			array(
				'label'       => 'یا آیکون دلخواه',
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'description' => 'اگر چیزی انتخاب کنید، جای آیکون بالا می‌نشیند.',
			)
		);

		$this->add_control(
			'reveal_icon_side',
			array(
				'label'        => 'آیکون سمت پایانِ متن',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'description'  => 'پیش‌فرض: آیکون پیش از متن (سمت راست). روشن: بعد از متن.',
				'prefix_class' => 'ard-reveal-icon-end-',
			)
		);

		$this->add_responsive_control(
			'reveal_icon_size',
			array(
				'label'      => 'اندازه آیکون',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 10, 'max' => 40 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 19 ),
				'selectors'  => array( '{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-icon-size:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'reveal_icon_gap',
			array(
				'label'      => 'فاصله آیکون از متن',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 24 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 8 ),
				'selectors'  => array( '{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-icon-gap:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'reveal_btn_h',
			array(
				'label'      => 'ارتفاع دکمه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 30, 'max' => 72 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 48 ),
				'selectors'  => array( '{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-foot:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'reveal_btn_radius',
			array(
				'label'      => 'گردی دکمه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array( 'min' => 0, 'max' => 40 ),
					'%'  => array( 'min' => 0, 'max' => 50 ),
				),
				'default'    => array( 'unit' => 'px', 'size' => 10 ),
				'selectors'  => array( '{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-btn-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'reveal_btn_gap',
			array(
				'label'      => 'فاصله دکمه از پایین کارت',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 4 ),
				'selectors'  => array( '{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-btn-gap:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'reveal_btn_typo',
				'label'    => 'قلم دکمه',
				'selector' => '{{WRAPPER}} .ard-pcard--reveal .ard-pcard__btn',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'reveal_btn_border',
				'label'    => 'حاشیه دکمه',
				'selector' => '{{WRAPPER}} .ard-pcard--reveal .ard-pcard__btn',
			)
		);

		$this->start_controls_tabs( 'reveal_btn_tabs' );

		$this->start_controls_tab( 'reveal_btn_normal', array( 'label' => 'عادی' ) );

		$this->add_control(
			'reveal_btn_bg',
			array(
				'label'     => 'زمینه',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F11763',
				'selectors' => array( '{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-btn-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'reveal_btn_ink',
			array(
				'label'     => 'رنگ متن و آیکون',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array( '{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-btn-ink:{{VALUE}};' ),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'reveal_btn_hover', array( 'label' => 'شناور' ) );

		$this->add_control(
			'reveal_btn_bg_hover',
			array(
				'label'     => 'زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-btn-bg-hover:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'reveal_btn_ink_hover',
			array(
				'label'     => 'رنگ متن و آیکون',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-btn-ink-hover:{{VALUE}};' ),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		/* ---------------------------------------- نشان درصد */

		$this->add_control(
			'reveal_off_heading',
			array( 'label' => 'نشان درصد و قیمت قبل', 'type' => Controls_Manager::HEADING, 'separator' => 'before' )
		);

		$this->add_control(
			'reveal_off_stack',
			array(
				'label'        => 'نشان بالای قیمتِ قبل بنشیند',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'خاموش: هر دو در یک خط کنار هم.',
			)
		);

		$this->add_control(
			'reveal_price_side',
			array(
				'label'        => 'جای قیمت',
				'type'         => Controls_Manager::SELECT,
				'default'      => 'end',
				'options'      => array(
					'end'   => 'قیمت سمت پایان، نشان سمت شروع (مطابق دیزاین)',
					'start' => 'قیمت سمت شروع، نشان سمت پایان',
				),
				'prefix_class' => 'ard-reveal-price-',
			)
		);

		$this->add_control(
			'reveal_off_bg',
			array(
				'label'     => 'زمینه نشان',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#E31336',
				'selectors' => array( '{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-off-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'reveal_off_ink',
			array(
				'label'     => 'رنگ متن نشان',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array( '{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-off-ink:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'reveal_off_radius',
			array(
				'label'      => 'گردی نشان',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 20 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 8 ),
				'selectors'  => array( '{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-off-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'reveal_off_typo',
				'label'    => 'قلم نشان',
				'selector' => '{{WRAPPER}} .ard-pcard--reveal .ard-pcard__off',
			)
		);

		$this->add_control(
			'reveal_old_ink',
			array(
				'label'     => 'رنگ قیمت قبل',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-old-ink:{{VALUE}};' ),
			)
		);

		/* ---------------------------------------- قیمت و کارت */

		$this->add_control(
			'reveal_card_heading',
			array( 'label' => 'قیمت و کارت', 'type' => Controls_Manager::HEADING, 'separator' => 'before' )
		);

		$this->add_control(
			'reveal_currency',
			array(
				'label'       => 'نماد واحد پول',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'slim',
				'options'     => array(
					'slim'    => 'نماد باریک (مطابق دیزاین)',
					'default' => 'نماد پیش‌فرض اردال',
					'text'    => 'واژهٔ «تومان»',
				),
				'description' => 'نماد باریک قدش با عدد قیمت می‌خواند و خط قیمت را باز نمی‌کند.',
			)
		);

		$this->add_responsive_control(
			'reveal_currency_size',
			array(
				'label'      => 'اندازه نماد',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array( 'min' => 8, 'max' => 40 ),
					'em' => array( 'min' => 0.5, 'max' => 3, 'step' => 0.05 ),
				),
				'selectors'  => array( '{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-toman-h:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'reveal_currency!' => 'text' ),
			)
		);

		$this->add_control(
			'reveal_price_ink',
			array(
				'label'     => 'رنگ قیمت',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-price-ink:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'reveal_card_bg',
			array(
				'label'     => 'زمینه کارت',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-card-bg:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'reveal_card_border',
				'label'    => 'حاشیه کارت',
				'selector' => '{{WRAPPER}} .ard-pcard--reveal .ard-pcard__box',
			)
		);

		$this->add_responsive_control(
			'reveal_card_radius',
			array(
				'label'      => 'گردی کارت',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 18 ),
				'selectors'  => array( '{{WRAPPER}} .ard-pcard--reveal' => '--ard-radius-xl:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'reveal_card_pad',
			array(
				'label'      => 'فاصله داخلی کارت',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 32 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 12 ),
				'selectors'  => array( '{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-pad:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'reveal_media_size',
			array(
				'label'        => 'اندازه تصویر',
				'type'         => Controls_Manager::SELECT,
				'default'      => 'fixed',
				'options'      => array(
					'fixed'   => 'ارتفاع ثابت (برش می‌خورد)',
					'natural' => 'به نسبت خودِ تصویر آپلودشده',
				),
				'description'  => '«نسبت خودِ تصویر» یعنی تصویر تمام پهنای کارت را می‌گیرد و قدش را نسبت اصلی عکس تعیین می‌کند؛ هیچ بخشی بریده نمی‌شود، ولی اگر عکس‌ها نسبت‌های متفاوتی داشته باشند کارت‌ها هم‌قد نمی‌مانند.',
				'prefix_class' => 'ard-reveal-media-',
			)
		);

		$this->add_control(
			'reveal_media_bg',
			array(
				'label'     => 'زمینه پنل تصویر',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-media-bg:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'reveal_media_radius',
			array(
				'label'      => 'گردی پنل تصویر',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 32 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 14 ),
				'selectors'  => array( '{{WRAPPER}} .ard-pcard--reveal' => '--ard-reveal-media-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render_empty( $message ) {
		if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			return true;
		}

		printf( '<div class="ard-empty">%s</div>', esc_html( Ardal_Brand::text( $message ) ) );

		return true;
	}
}
