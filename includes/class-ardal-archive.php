<?php
/**
 * موتور آرشیو فروشگاه — خواندن پارامترهای فیلتر از آدرس، اعمالشان روی کوئری اصلی
 * و در اختیار گذاشتن داده‌های مشترک (گزینه‌ها، شمارش‌ها، بازه قیمت، مرتب‌سازی)
 * برای ویجت‌های آرشیو.
 *
 * فیلترها عمداً بدون ایجکس‌اند: همه چیز پارامتر آدرس است و با یک فرم GET ساده
 * ارسال می‌شود. نتیجه این است که چند فیلتر هم‌زمان، صفحه‌بندی، مرتب‌سازی و آرشیو
 * دسته با هم جمع می‌شوند و آدرس نهایی قابل اشتراک‌گذاری و ایندکس است.
 *
 * پارامترها:
 *   ard_{key}[]                    → تاکسونومی‌های محصول (key = نام تاکسونومی بدون pa_/product_)
 *   ard_s                          → جست‌وجو داخل همین آرشیو
 *   ard_stock, ard_sale, ard_featured → کلیدهای یک‌ضربی
 *   ard_rating[]                   → امتیاز، هر عدد یعنی «همان امتیاز به بالا»
 *   min_price, max_price, orderby, paged → پارامترهای خود ووکامرس
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Archive {

	/**
	 * پیشوند پارامترهای این افزونه در آدرس.
	 */
	const PREFIX = 'ard_';

	/**
	 * کلیدهایی که معنای ثابت دارند و نباید با کلید تاکسونومی اشتباه شوند.
	 */
	const RESERVED = array( 's', 'stock', 'sale', 'featured', 'quick_term', 'rating', 'sort', 'view' );

	/**
	 * شناسه‌ای که در فیلد «Query ID» ویجت‌های المنتور نوشته می‌شود تا کوئری
	 * اختصاصی آن ویجت هم فیلترهای این افزونه را بگیرد.
	 */
	const QUERY_ID = 'ardal_filters';

	/**
	 * نگاشت «کلید آدرس → نام تاکسونومی»؛ یک‌بار ساخته می‌شود.
	 *
	 * @var array|null
	 */
	protected static $map = null;

	/**
	 * ثبت هوک‌ها.
	 */
	public static function init() {
		// اولویت ۲۰: ووکامرس روی ۱۰ کوئری محصول را می‌سازد، پس شرط‌های ما باید
		// روی همان چیزی که ساخته اضافه شود، نه پیش از آن.
		add_action( 'pre_get_posts', array( __CLASS__, 'filter_query' ), 20 );

		/*
		 * ویجت‌های کوئری‌دار المنتور (لوپ گرید، لوپ کاروسل، آرشیو پست‌ها) کوئری
		 * خودشان را می‌سازند نه کوئری اصلی را. اگر منبعشان «Current Query» باشد
		 * خود المنتور query_vars کوئری اصلی — که همین‌جا فیلتر شده — را کپی
		 * می‌کند و کاری لازم نیست؛ برای کوئری دلخواه، کافی است ادمین در فیلد
		 * «Query ID» همین شناسه را بنویسد.
		 */
		add_action( 'elementor/query/' . self::QUERY_ID, array( __CLASS__, 'filter_elementor_query' ), 10, 2 );

		foreach ( array( 'woocommerce_update_product', 'woocommerce_new_product', 'save_post_product', 'deleted_post' ) as $hook ) {
			add_action( $hook, array( __CLASS__, 'flush' ) );
		}
	}

	/* --------------------------------------------------------------------- */
	/* تاکسونومی‌ها                                                            */
	/* --------------------------------------------------------------------- */

	/**
	 * تاکسونومی‌های عمومی محصول، برای کنترل‌های المنتور.
	 *
	 * @return array نام تاکسونومی => برچسب فارسی.
	 */
	public static function taxonomy_options() {
		if ( ! Ardal_Helpers::has_woo() ) {
			return array();
		}

		$out = array();

		foreach ( get_object_taxonomies( 'product', 'objects' ) as $taxonomy ) {
			if ( ! $taxonomy->public || 'product_visibility' === $taxonomy->name ) {
				continue;
			}

			$out[ $taxonomy->name ] = $taxonomy->labels->singular_name
				? $taxonomy->labels->singular_name
				: $taxonomy->name;
		}

		return $out;
	}

	/**
	 * کلید کوتاه آدرس برای یک تاکسونومی: product_cat → cat، pa_color → color.
	 *
	 * @param string $taxonomy
	 * @return string
	 */
	public static function key_for_taxonomy( $taxonomy ) {
		$key = (string) $taxonomy;

		foreach ( array( 'product_', 'pa_' ) as $prefix ) {
			if ( 0 === strpos( $key, $prefix ) ) {
				$key = substr( $key, strlen( $prefix ) );
				break;
			}
		}

		$key = sanitize_key( $key );

		// کلید رزروشده یعنی معنی دیگری دارد؛ نام کامل تاکسونومی جایش می‌نشیند.
		if ( '' === $key || in_array( $key, self::RESERVED, true ) ) {
			$key = sanitize_key( $taxonomy );
		}

		return $key;
	}

	/**
	 * نگاشت کامل «کلید → تاکسونومی».
	 *
	 * @return array
	 */
	public static function key_map() {
		if ( null !== self::$map ) {
			return self::$map;
		}

		self::$map = array();

		if ( ! Ardal_Helpers::has_woo() ) {
			return self::$map;
		}

		foreach ( array_keys( self::taxonomy_options() ) as $taxonomy ) {
			$key = self::key_for_taxonomy( $taxonomy );

			// اولین صاحبِ کلید برنده است؛ بقیه با نام کامل ثبت می‌شوند.
			if ( isset( self::$map[ $key ] ) ) {
				$key = sanitize_key( $taxonomy );
			}

			self::$map[ $key ] = $taxonomy;
		}

		return self::$map;
	}

	/**
	 * تاکسونومی متناظر یک کلید آدرس.
	 *
	 * @param string $key
	 * @return string رشته خالی یعنی کلید شناخته‌شده نیست.
	 */
	public static function taxonomy_for_key( $key ) {
		$map = self::key_map();

		return isset( $map[ $key ] ) ? $map[ $key ] : '';
	}

	/* --------------------------------------------------------------------- */
	/* خواندن پارامترها                                                        */
	/* --------------------------------------------------------------------- */

	/**
	 * مقدارهای انتخاب‌شده‌ی یک پارامتر (هم آرایه و هم رشته‌ی کاماگذاری‌شده).
	 *
	 * @param string $key کلید کوتاه، بدون پیشوند.
	 * @return string[] اسلاگ‌ها.
	 */
	public static function selected( $key ) {
		$name = self::PREFIX . $key;

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$raw = isset( $_GET[ $name ] ) ? wp_unslash( $_GET[ $name ] ) : array();

		if ( is_string( $raw ) ) {
			$raw = explode( ',', $raw );
		}

		$out = array();

		foreach ( (array) $raw as $value ) {
			if ( ! is_scalar( $value ) ) {
				continue;
			}

			$value = sanitize_title( wp_strip_all_tags( (string) $value ) );

			if ( '' !== $value && ! in_array( $value, $out, true ) ) {
				$out[] = $value;
			}
		}

		return $out;
	}

	/**
	 * آیا یک کلید یک‌ضربی (سوییچ) روشن است.
	 *
	 * @param string $key
	 * @return bool
	 */
	public static function is_on( $key ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$value = isset( $_GET[ self::PREFIX . $key ] ) ? sanitize_text_field( wp_unslash( $_GET[ self::PREFIX . $key ] ) ) : '';

		return '' !== $value && '0' !== $value && 'no' !== $value;
	}

	/**
	 * یک پارامتر ساده (بدون پیشوند) از آدرس.
	 *
	 * @param string $key
	 * @return string
	 */
	public static function param( $key ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return isset( $_GET[ $key ] ) ? sanitize_text_field( wp_unslash( $_GET[ $key ] ) ) : '';
	}

	/**
	 * عبارت جست‌وجوی داخل آرشیو.
	 *
	 * @return string
	 */
	public static function search_term() {
		$term = self::param( self::PREFIX . 's' );

		return '' !== $term ? $term : self::param( 's' );
	}

	/**
	 * امتیازهای انتخاب‌شده؛ هر عدد یعنی «همان امتیاز به بالا».
	 *
	 * @return int[]
	 */
	public static function ratings() {
		$out = array();

		foreach ( self::selected( 'rating' ) as $value ) {
			$value = (int) $value;

			if ( $value >= 1 && $value <= 5 && ! in_array( $value, $out, true ) ) {
				$out[] = $value;
			}
		}

		sort( $out );

		return $out;
	}

	/* --------------------------------------------------------------------- */
	/* آدرس‌ها                                                                 */
	/* --------------------------------------------------------------------- */

	/**
	 * نشانی پایه‌ی آرشیو جاری، بدون هیچ پارامتری.
	 *
	 * @return string
	 */
	public static function base_url() {
		if ( Ardal_Helpers::has_woo() && is_product_taxonomy() ) {
			$term = get_queried_object();

			if ( $term instanceof WP_Term ) {
				$link = get_term_link( $term );

				if ( ! is_wp_error( $link ) ) {
					return $link;
				}
			}
		}

		$shop = Ardal_Helpers::has_woo() ? get_permalink( wc_get_page_id( 'shop' ) ) : '';

		if ( $shop ) {
			return $shop;
		}

		$current = get_permalink();

		return $current ? $current : home_url( '/' );
	}

	/**
	 * نشانی جاری با تغییر چند پارامتر (برای مرتب‌سازی، چیپ‌ها و صفحه‌بندی).
	 *
	 * مقدار null یعنی «این پارامتر حذف شود».
	 *
	 * @param array $args
	 * @return string
	 */
	public static function url_with( $args ) {
		$base = self::base_url();

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current = wp_unslash( $_GET );
		$current = is_array( $current ) ? $current : array();

		// شماره صفحه بعد از هر تغییر فیلتر بی‌معناست.
		unset( $current['paged'], $current['product-page'], $current['add-to-cart'] );

		$query = array_merge( $current, $args );

		foreach ( $query as $key => $value ) {
			if ( null === $value || '' === $value || array() === $value ) {
				unset( $query[ $key ] );
				continue;
			}

			// add_query_arg خودش کدگذاری می‌کند؛ اینجا فقط پاکسازی لازم است.
			$query[ $key ] = map_deep( $value, 'sanitize_text_field' );
		}

		return $query ? add_query_arg( $query, $base ) : $base;
	}

	/**
	 * نشانی جاری بدون یک مقدار مشخص از یک فیلتر چندگزینه‌ای.
	 *
	 * @param string $key   کلید کوتاه.
	 * @param string $value اسلاگی که باید برداشته شود؛ خالی یعنی کل فیلتر.
	 * @return string
	 */
	public static function url_dropping( $key, $value = '' ) {
		$name = self::PREFIX . $key;

		if ( '' === $value ) {
			return self::url_with( array( $name => null ) );
		}

		$left = array_values( array_diff( self::selected( $key ), array( $value ) ) );

		return self::url_with( array( $name => $left ? $left : null ) );
	}

	/* --------------------------------------------------------------------- */
	/* فیلترهای فعال                                                          */
	/* --------------------------------------------------------------------- */

	/**
	 * فهرست فیلترهای فعال، آماده‌ی نمایش به‌شکل چیپ.
	 *
	 * @return array هر آیتم: group، label، url (نشانی حذف همان فیلتر).
	 */
	public static function active_filters() {
		$out = array();

		foreach ( self::key_map() as $key => $taxonomy ) {
			$slugs = self::selected( $key );

			if ( ! $slugs ) {
				continue;
			}

			$label = get_taxonomy( $taxonomy );
			$label = ( $label && ! empty( $label->labels->singular_name ) ) ? $label->labels->singular_name : '';

			foreach ( $slugs as $slug ) {
				$term = get_term_by( 'slug', $slug, $taxonomy );

				$out[] = array(
					'group' => $label,
					'label' => ( $term instanceof WP_Term ) ? $term->name : $slug,
					'url'   => self::url_dropping( $key, $slug ),
				);
			}
		}

		$search = self::search_term();

		if ( '' !== $search ) {
			$out[] = array(
				'group' => 'جست‌وجو',
				'label' => $search,
				'url'   => self::url_with(
					array(
						self::PREFIX . 's' => null,
						's'                => null,
					)
				),
			);
		}

		$min = self::param( 'min_price' );
		$max = self::param( 'max_price' );

		if ( '' !== $min || '' !== $max ) {
			$bounds = self::price_bounds();
			$from   = '' !== $min ? (float) $min : $bounds['min'];
			$to     = '' !== $max ? (float) $max : $bounds['max'];

			$out[] = array(
				'group' => 'قیمت',
				'label' => sprintf(
					'%s تا %s',
					Ardal_Helpers::price( $from ),
					Ardal_Helpers::price( $to )
				),
				'url'   => self::url_with(
					array(
						'min_price' => null,
						'max_price' => null,
					)
				),
			);
		}

		$ratings = self::ratings();

		if ( $ratings ) {
			$out[] = array(
				'group' => 'امتیاز',
				'label' => sprintf( '%s ستاره به بالا', Ardal_Helpers::fa_digits( min( $ratings ) ) ),
				'url'   => self::url_with( array( self::PREFIX . 'rating' => null ) ),
			);
		}

		foreach ( self::switch_labels() as $key => $label ) {
			if ( 'quick_term' === $key ) {
				$value = self::param( self::PREFIX . 'quick_term' );
				$parts = explode( '|', $value, 2 );
				if ( 2 === count( $parts ) && taxonomy_exists( $parts[0] ) && is_object_in_taxonomy( 'product', $parts[0] ) ) {
					$term = get_term_by( 'slug', $parts[1], $parts[0] );
					if ( $term instanceof WP_Term ) {
						$taxonomy_object = get_taxonomy( $parts[0] );
						$out[] = array(
							'group' => $taxonomy_object && ! empty( $taxonomy_object->labels->singular_name ) ? $taxonomy_object->labels->singular_name : '',
							'label' => $term->name,
							'url'   => self::url_with( array( self::PREFIX . 'quick_term' => null ) ),
						);
					}
				}
				continue;
			}
			if ( ! self::is_on( $key ) ) {
				continue;
			}

			$out[] = array(
				'group' => '',
				'label' => $label,
				'url'   => self::url_with( array( self::PREFIX . $key => null ) ),
			);
		}

		return $out;
	}

	/**
	 * پرده‌ی پشت کشو و شیت.
	 *
	 * چند ویجت آرشیو می‌توانند در یک صفحه باشند، ولی پرده باید یکی بماند وگرنه
	 * دو لایه‌ی تیره روی هم می‌نشیند.
	 */
	public static function render_scrim() {
		static $done = false;

		if ( $done ) {
			return;
		}

		$done = true;

		echo '<div class="ard-arch-scrim" data-ard-arch-close aria-hidden="true"></div>';
	}

	/**
	 * برچسب پیش‌فرض کلیدهای یک‌ضربی.
	 *
	 * @return array
	 */
	public static function switch_labels() {
		return array(
			'stock'    => 'فقط کالاهای موجود',
			'sale'     => 'کالاهای تخفیف‌دار',
			'featured' => 'محصولات ویژه',
			'quick_term' => 'زود مصرف',
		);
	}

	/**
	 * شمار فیلترهای فعال (برای نشان روی دکمه‌ی موبایل).
	 *
	 * @return int
	 */
	public static function active_count() {
		return count( self::active_filters() );
	}

	/* --------------------------------------------------------------------- */
	/* اعمال روی کوئری                                                        */
	/* --------------------------------------------------------------------- */

	/**
	 * آیا این کوئری، آرشیو محصول است؟
	 *
	 * @param WP_Query $q
	 * @return bool
	 */
	protected static function is_product_query( $q ) {
		if ( ! Ardal_Helpers::has_woo() ) {
			return false;
		}

		return $q->is_post_type_archive( 'product' )
			|| $q->is_tax( get_object_taxonomies( 'product' ) )
			|| 'product_query' === $q->get( 'wc_query' );
	}

	/**
	 * افزودن شرط‌های فیلتر به کوئری اصلی آرشیو.
	 *
	 * قیمت، امتیاز، مرتب‌سازی و صفحه‌بندی دست خود ووکامرس است و اینجا لمس نمی‌شود.
	 *
	 * @param WP_Query $q
	 */
	public static function filter_query( $q ) {
		if ( is_admin() || ! $q->is_main_query() || ! self::is_product_query( $q ) ) {
			return;
		}

		/* --------------------------------------------- تعداد در هر صفحه */
		$per_page = (int) Ardal_Palette::get( 'archive_per_page' );

		if ( $per_page > 0 ) {
			$q->set( 'posts_per_page', $per_page );
		}

		self::apply_to_query( $q );
	}

	/**
	 * همان فیلترها، روی کوئری اختصاصی یک ویجت المنتور.
	 *
	 * تعداد در هر صفحه اینجا دست نمی‌خورد؛ آن را خود ویجت تعیین می‌کند.
	 *
	 * @param WP_Query $q
	 * @param mixed    $widget ویجت المنتور؛ اینجا لازم نیست ولی امضای هوک آن را می‌فرستد.
	 */
	public static function filter_elementor_query( $q, $widget = null ) {
		if ( is_admin() || ! $q instanceof WP_Query ) {
			return;
		}

		self::apply_to_query( $q );
	}

	/**
	 * ساخت و نشاندن شرط‌های فیلتر روی یک کوئری.
	 *
	 * عمومی است تا ویجت‌هایی که کوئری خودشان را می‌سازند هم بتوانند همان
	 * فیلترهای آدرس را رویش بنشانند. الگوی درست این است:
	 *
	 *     $q = new WP_Query();
	 *     $q->parse_query( $args );
	 *     Ardal_Archive::apply_to_query( $q );
	 *     $q->get_posts();
	 *
	 * یعنی پیش از اجرای کوئری، نه بعدش.
	 *
	 * @param WP_Query $q
	 * @param string   $skip_taxonomy تاکسونومی‌ای که فیلترش اعمال نشود (برای شمارش).
	 */
	public static function apply_to_query( $q, $skip_taxonomy = '' ) {
		$tax_query = (array) $q->get( 'tax_query' );

		// کوئری خالی گاهی رشته است؛ عضو غیرآرایه‌ای در tax_query چیزی جز شلوغی نیست.
		foreach ( $tax_query as $index => $clause ) {
			if ( 'relation' !== $index && ! is_array( $clause ) ) {
				unset( $tax_query[ $index ] );
			}
		}

		/* --------------------------------------------- تاکسونومی‌ها */
		foreach ( self::key_map() as $key => $taxonomy ) {
			$slugs = self::selected( $key );

			if ( ! $slugs || ! taxonomy_exists( $taxonomy ) ) {
				continue;
			}

			/*
			 * یک تاکسونومی می‌تواند از شمارش کنار گذاشته شود.
			 *
			 * ریل دسته‌ها برای شمردن محصولات هر دسته، فیلترِ خودِ دسته را
			 * نادیده می‌گیرد؛ وگرنه به‌محض انتخاب یک دسته، بقیه صفر می‌شدند و
			 * ریل — که کارش جابه‌جا شدن بین دسته‌هاست — خالی می‌ماند.
			 */
			if ( $skip_taxonomy && $skip_taxonomy === $taxonomy ) {
				continue;
			}

			// داخل یک فیلتر همیشه «یا»، بین فیلترها «و».
			$tax_query[] = array(
				'taxonomy'         => $taxonomy,
				'field'            => 'slug',
				'terms'            => $slugs,
				'operator'         => 'IN',
				'include_children' => true,
			);
		}

		/* --------------------------------------------- فقط موجود */
		if ( self::is_on( 'stock' ) ) {
			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'outofstock',
				'operator' => 'NOT IN',
			);
		}

		/* --------------------------------------------- فقط ویژه */
		if ( self::is_on( 'featured' ) ) {
			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => 'IN',
			);
		}

		/* --------------------------------------------- ÙÛŒÙ„ØªØ± Ù…Ø­ØµÙˆÙ„ Ø³ÙØ§Ø±Ø´ÛŒ */
		$quick_term = self::param( self::PREFIX . 'quick_term' );
		$quick_parts = explode( '|', $quick_term, 2 );
		if ( 2 === count( $quick_parts ) && taxonomy_exists( $quick_parts[0] ) && is_object_in_taxonomy( 'product', $quick_parts[0] ) ) {
			$quick = get_term_by( 'slug', $quick_parts[1], $quick_parts[0] );
			if ( $quick instanceof WP_Term ) {
				$tax_query[] = array(
					'taxonomy'         => $quick_parts[0],
					'field'            => 'slug',
					'terms'            => $quick->slug,
					'include_children' => true,
				);
			}
		}

		/* --------------------------------------------- امتیاز */
		$ratings = self::ratings();

		if ( $ratings ) {
			$visibility = wc_get_product_visibility_term_ids();
			$ids        = array();

			// «۴ ستاره به بالا» یعنی ۴ و ۵؛ پس از کمینه‌ی انتخاب‌شده تا ۵ باز می‌شود.
			for ( $star = min( $ratings ); $star <= 5; $star++ ) {
				if ( ! empty( $visibility[ 'rated-' . $star ] ) ) {
					$ids[] = (int) $visibility[ 'rated-' . $star ];
				}
			}

			$tax_query[] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $ids ? $ids : array( 0 ),
				'operator' => 'IN',
			);
		}

		if ( $tax_query ) {
			$q->set( 'tax_query', $tax_query );
		}

		/* --------------------------------------------- فقط تخفیف‌دار */
		if ( self::is_on( 'sale' ) ) {
			$on_sale = wc_get_product_ids_on_sale();
			$on_sale = $on_sale ? $on_sale : array( 0 );
			$current = array_filter( (array) $q->get( 'post__in' ) );

			// اگر کوئری از قبل محدود شده، اشتراک گرفته می‌شود نه جایگزینی.
			$post_in = $current ? array_intersect( $current, $on_sale ) : $on_sale;

			$q->set( 'post__in', $post_in ? array_values( $post_in ) : array( 0 ) );
		}

		/* --------------------------------------------- جست‌وجو داخل آرشیو */
		$search = self::param( self::PREFIX . 's' );

		if ( '' !== $search && '' === (string) $q->get( 's' ) ) {
			$q->set( 's', $search );
		}

		self::apply_price( $q );
	}

	/**
	 * بازهٔ قیمت روی کوئری‌های جانبی.
	 *
	 * ووکامرس فیلتر قیمت را با `posts_clauses` و فقط روی کوئری اصلی اعمال
	 * می‌کند. هر کوئری دیگری که ما می‌سازیم — شبکهٔ محصولاتِ «کوئری آرشیو
	 * جاری»، شمارش ترم‌های ریل، لوپ گرید — آن قلاب را به ارث نمی‌برد و تا
	 * امروز قیمت را نادیده می‌گرفت؛ یعنی اسلایدر قیمت روی آن‌ها بی‌اثر بود.
	 *
	 * روی کوئری اصلی دست نمی‌زنیم تا شرط دوباره و با پیاده‌سازی متفاوت اضافه
	 * نشود؛ آنجا کار خود ووکامرس دقیق‌تر است (محصول متغیر را از جدول lookup
	 * می‌خواند).
	 *
	 * @param WP_Query $q
	 */
	protected static function apply_price( $q ) {
		if ( method_exists( $q, 'is_main_query' ) && $q->is_main_query() ) {
			return;
		}

		$min = self::param( 'min_price' );
		$max = self::param( 'max_price' );

		if ( '' === $min && '' === $max ) {
			return;
		}

		$meta = (array) $q->get( 'meta_query' );

		$meta[] = array(
			'key'     => '_price',
			'type'    => 'NUMERIC',
			'compare' => 'BETWEEN',
			'value'   => array(
				'' === $min ? 0 : (float) $min,
				'' === $max ? PHP_FLOAT_MAX : (float) $max,
			),
		);

		$q->set( 'meta_query', $meta );
	}

	/* --------------------------------------------------------------------- */
	/* داده‌ی گزینه‌ها                                                          */
	/* --------------------------------------------------------------------- */

	/**
	 * ترم‌هایی که در هیچ فهرستِ نمایشی نباید بیایند.
	 *
	 * فعلاً فقط دستهٔ پیش‌فرض ووکامرس («دسته‌بندی نشده»): هر محصولی که دسته‌اش
	 * تعیین نشده باشد آنجا می‌افتد، پس در ریل، فیلتر یا ویترین دسته‌ها یک ردیف
	 * بی‌معناست. کنار `default_product_cat` اسلاگ `uncategorized` هم بررسی
	 * می‌شود، چون در سایت‌های مهاجرت‌شده آن آپشن گاهی به ترم دیگری اشاره
	 * می‌کند.
	 *
	 * @param string $taxonomy
	 * @return int[]
	 */
	public static function excluded_terms( $taxonomy = 'product_cat' ) {
		static $cache = array();

		if ( isset( $cache[ $taxonomy ] ) ) {
			return $cache[ $taxonomy ];
		}

		$out = array();

		// دستهٔ پیش‌فرض هر کدام کلید آپشن خودش را دارد.
		$default_option = array(
			'product_cat' => 'default_product_cat',
			'category'    => 'default_category',
		);

		if ( isset( $default_option[ $taxonomy ] ) ) {
			$out[] = (int) get_option( $default_option[ $taxonomy ] );

			/*
			 * اسلاگ را هم جدا نگاه می‌کنیم چون در سایت‌های مهاجرت‌داده‌شده
			 * «بدون دسته‌بندی» گاهی دیگر دستهٔ پیش‌فرض نیست ولی همچنان
			 * محصول‌های قدیمی به آن چسبیده‌اند.
			 */
			foreach ( array( 'uncategorized', 'دسته-بندی-نشده', 'دسته‌بندی-نشده' ) as $slug ) {
				$term = get_term_by( 'slug', $slug, $taxonomy );

				if ( $term ) {
					$out[] = (int) $term->term_id;
				}
			}
		}

		$out = array_values( array_filter( array_unique( array_map( 'absint', $out ) ) ) );

		/**
		 * ترم‌هایی که از فهرست‌های نمایشی کنار گذاشته می‌شوند.
		 *
		 * @since 2.9.0
		 * @param int[]  $out
		 * @param string $taxonomy
		 */
		$cache[ $taxonomy ] = (array) apply_filters( 'ardal_excluded_terms', $out, $taxonomy );

		return $cache[ $taxonomy ];
	}

	/**
	 * ترم‌های یک تاکسونومی، آماده‌ی نمایش در باکس فیلتر.
	 *
	 * @param string $taxonomy
	 * @param array  $args limit، parent، include، hide_empty، orderby.
	 * @return array هر آیتم: slug، label، count، checked، url.
	 */
	public static function terms_for( $taxonomy, $args = array() ) {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			return array();
		}

		$args = wp_parse_args(
			$args,
			array(
				'limit'      => 0,
				'parent'     => '',
				'include'    => array(),
				'hide_empty' => true,
				'orderby'    => 'name',
			)
		);

		$query = array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => (bool) $args['hide_empty'],
		);

		switch ( $args['orderby'] ) {
			case 'count':
				$query['orderby'] = 'count';
				$query['order']   = 'DESC';
				break;

			case 'order':
				$query['orderby'] = 'menu_order';
				break;

			case 'id':
				$query['orderby'] = 'term_id';
				break;

			default:
				$query['orderby'] = 'name';
		}

		$skip = self::excluded_terms( $taxonomy );

		if ( $args['include'] ) {
			// include و exclude با هم کار نمی‌کنند؛ پس پیش از کوئری کم می‌شوند.
			$include = array_diff( array_map( 'absint', (array) $args['include'] ), $skip );

			if ( ! $include ) {
				return array();
			}

			$query['include'] = array_values( $include );
			$query['orderby'] = 'include';
			unset( $query['order'] );
		} else {
			if ( '' !== $args['parent'] ) {
				$query['parent'] = (int) $args['parent'];
			}

			if ( $skip ) {
				$query['exclude'] = $skip;
			}
		}

		if ( $args['limit'] > 0 ) {
			$query['number'] = (int) $args['limit'];
		}

		$terms = get_terms( $query );

		if ( is_wp_error( $terms ) || ! $terms ) {
			return array();
		}

		$key     = self::key_for_taxonomy( $taxonomy );
		$chosen  = self::selected( $key );
		$options = array();

		foreach ( $terms as $term ) {
			$options[] = array(
				'slug'    => $term->slug,
				'label'   => $term->name,
				'count'   => (int) $term->count,
				'term_id' => (int) $term->term_id,
				'checked' => in_array( $term->slug, $chosen, true ),
			);
		}

		return $options;
	}

	/**
	 * تعداد محصولِ هر ترم، داخل همان چیزی که الان روی صفحه است.
	 *
	 * شمارشِ خودِ ترم (`$term->count`) کل فروشگاه را می‌شمارد و به فیلترها و
	 * صفحهٔ جاری کاری ندارد. اینجا کوئری واقعی آرشیو بازسازی می‌شود — زمینهٔ
	 * صفحه (ترم آرشیو، جست‌وجو) به‌علاوهٔ فیلترهای اردال — و محصول‌هایش شمرده
	 * می‌شوند.
	 *
	 * شمارش به بالادست هم سرایت می‌کند: محصولی که در یک زیردسته است، برای
	 * دستهٔ والدش هم شمرده می‌شود، وگرنه دسته‌های سطح اول همیشه صفر می‌شدند.
	 * هر محصول برای هر ترم یک بار حساب می‌شود، حتی اگر هم به والد و هم به
	 * فرزند وصل باشد.
	 *
	 * @param string $taxonomy
	 * @param bool   $skip_self آیا فیلترِ خود همین تاکسونومی نادیده گرفته شود.
	 * @return array شناسهٔ ترم => تعداد.
	 */
	public static function counts_for( $taxonomy, $skip_self = true ) {
		static $cache = array();

		if ( ! taxonomy_exists( $taxonomy ) || ! Ardal_Helpers::has_woo() ) {
			return array();
		}

		$memo = $taxonomy . ( $skip_self ? '|skip' : '' );

		if ( isset( $cache[ $memo ] ) ) {
			return $cache[ $memo ];
		}

		$args = array(
			'post_type'              => 'product',
			'post_status'            => 'publish',
			'fields'                 => 'ids',
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'tax_query'              => array(),
			/**
			 * سقف شمارش.
			 *
			 * یک کوئری شناسه‌ای است و برای فروشگاه‌های معمولی ارزان؛ اگر جایی
			 * سنگین شد، با همین فیلتر محدودش کنید.
			 *
			 * @since 2.9.0
			 * @param int $limit
			 */
			'posts_per_page'         => (int) apply_filters( 'ardal_counts_limit', -1 ),
		);

		// زمینهٔ صفحه: اگر روی آرشیو یک ترم هستیم، همان‌جا بماند.
		$object = get_queried_object();

		if ( $object instanceof WP_Term && taxonomy_exists( $object->taxonomy ) ) {
			$args['tax_query'][] = array(
				'taxonomy'         => $object->taxonomy,
				'field'            => 'term_id',
				'terms'            => (int) $object->term_id,
				'include_children' => true,
			);
		}

		if ( is_search() ) {
			$args['s'] = get_search_query();
		}

		$query = new WP_Query();
		$query->parse_query( $args );

		self::apply_to_query( $query, $skip_self ? $taxonomy : '' );

		$ids = $query->get_posts();

		if ( ! $ids ) {
			$cache[ $memo ] = array();

			return $cache[ $memo ];
		}

		$rows = wp_get_object_terms( $ids, $taxonomy, array( 'fields' => 'all_with_object_id' ) );

		if ( is_wp_error( $rows ) || ! $rows ) {
			$cache[ $memo ] = array();

			return $cache[ $memo ];
		}

		// به‌جای شمارنده، مجموعهٔ شناسه‌ها نگه داشته می‌شود تا تکراری‌ها یکی شوند.
		$sets = array();

		foreach ( $rows as $row ) {
			$term_id = (int) $row->term_id;
			$object_id = (int) $row->object_id;

			$sets[ $term_id ][ $object_id ] = true;

			foreach ( get_ancestors( $term_id, $taxonomy, 'taxonomy' ) as $ancestor ) {
				$sets[ (int) $ancestor ][ $object_id ] = true;
			}
		}

		$out  = array();
		$skip = self::excluded_terms( $taxonomy );

		foreach ( $sets as $term_id => $objects ) {
			if ( in_array( (int) $term_id, $skip, true ) ) {
				continue;
			}

			$out[ $term_id ] = count( $objects );
		}

		$cache[ $memo ] = $out;

		return $out;
	}

	/**
	 * کمینه و بیشینه‌ی قیمت فروشگاه.
	 *
	 * @return array min و max به‌صورت عدد صحیح.
	 */
	public static function price_bounds() {
		$cache = get_transient( 'ardal_price_bounds' );

		if ( is_array( $cache ) && isset( $cache['min'], $cache['max'] ) ) {
			return $cache;
		}

		global $wpdb;

		$min = 0;
		$max = 0;

		if ( Ardal_Helpers::has_woo() && ! empty( $wpdb->wc_product_meta_lookup ) ) {
			$row = $wpdb->get_row( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				"SELECT MIN( min_price ) AS low, MAX( max_price ) AS high
				FROM {$wpdb->wc_product_meta_lookup}
				WHERE min_price IS NOT NULL AND min_price > 0",
				ARRAY_A
			);

			$min = isset( $row['low'] ) ? (float) $row['low'] : 0;
			$max = isset( $row['high'] ) ? (float) $row['high'] : 0;
		}

		if ( $max <= $min ) {
			$row = $wpdb->get_row( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				"SELECT MIN( meta_value + 0 ) AS low, MAX( meta_value + 0 ) AS high
				FROM {$wpdb->postmeta} pm
				INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
				WHERE pm.meta_key = '_price' AND pm.meta_value != ''
				AND p.post_type IN ( 'product', 'product_variation' ) AND p.post_status = 'publish'",
				ARRAY_A
			);

			$min = isset( $row['low'] ) ? (float) $row['low'] : 0;
			$max = isset( $row['high'] ) ? (float) $row['high'] : 0;
		}

		if ( $max <= $min ) {
			$max = $min + 1000000;
		}

		$bounds = array(
			'min' => (int) floor( $min ),
			'max' => (int) ceil( $max ),
		);

		set_transient( 'ardal_price_bounds', $bounds, HOUR_IN_SECONDS );

		return $bounds;
	}

	/**
	 * پاک کردن کش‌های آرشیو.
	 */
	public static function flush() {
		delete_transient( 'ardal_price_bounds' );
	}

	/* --------------------------------------------------------------------- */
	/* مرتب‌سازی                                                               */
	/* --------------------------------------------------------------------- */

	/**
	 * فهرست کامل گزینه‌های مرتب‌سازی (برای کنترل المنتور).
	 *
	 * @return array
	 */
	public static function sort_labels() {
		return array(
			'menu_order' => 'پیش‌فرض',
			'popularity' => 'پرفروش‌ترین',
			'rating'     => 'بیشترین امتیاز',
			'date'       => 'جدیدترین',
			'price'      => 'ارزان‌ترین',
			'price-desc' => 'گران‌ترین',
		);
	}

	/**
	 * گزینه‌های مرتب‌سازی به‌شکل لینک؛ همان فهرست ووکامرس، فقط بدون select.
	 *
	 * @param array $allowed کلیدهای مجاز؛ خالی یعنی همه.
	 * @param array $labels  برچسب‌های جایگزین.
	 * @return array
	 */
	public static function sort_options( $allowed = array(), $labels = array() ) {
		$options = self::sort_labels();

		foreach ( $labels as $key => $label ) {
			if ( isset( $options[ $key ] ) && '' !== trim( (string) $label ) ) {
				$options[ $key ] = $label;
			}
		}

		if ( $allowed ) {
			$options = array_intersect_key( $options, array_flip( (array) $allowed ) );
		}

		$default = Ardal_Helpers::has_woo()
			? (string) apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) )
			: 'menu_order';

		$current = self::param( 'orderby' );
		$current = '' !== $current ? $current : $default;

		$out = array();

		foreach ( $options as $value => $label ) {
			$out[ $value ] = array(
				'value'   => $value,
				'label'   => $label,
				'current' => $value === $current,
				'url'     => self::url_with( array( 'orderby' => $value ) ),
			);
		}

		return $out;
	}

	/* --------------------------------------------------------------------- */
	/* حلقه محصولات                                                            */
	/* --------------------------------------------------------------------- */

	/**
	 * آمار حلقه‌ی جاری برای «نمایش ۱ تا ۱۲ از ۹۸ نتیجه».
	 *
	 * از ویژگی‌های حلقه‌ی ووکامرس می‌خواند و اگر تنظیم نشده بودند، از کوئری اصلی.
	 *
	 * @param WP_Query|null $query
	 * @return array total، per_page، current، first، last، pages.
	 */
	public static function loop_stats( $query = null ) {
		$total   = 0;
		$per     = 0;
		$current = 0;
		$pages   = 0;

		if ( Ardal_Helpers::has_woo() && function_exists( 'wc_get_loop_prop' ) ) {
			$total   = (int) wc_get_loop_prop( 'total' );
			$per     = (int) wc_get_loop_prop( 'per_page' );
			$current = (int) wc_get_loop_prop( 'current_page' );
			$pages   = (int) wc_get_loop_prop( 'total_pages' );
		}

		if ( ! $query instanceof WP_Query ) {
			$query = isset( $GLOBALS['wp_query'] ) ? $GLOBALS['wp_query'] : null;
		}

		if ( $query instanceof WP_Query ) {
			$total = $total ? $total : (int) $query->found_posts;
			$per   = $per ? $per : (int) $query->get( 'posts_per_page' );
			$pages = $pages ? $pages : (int) $query->max_num_pages;
		}

		$current = max( 1, $current, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
		$per     = $per > 0 ? $per : 12;

		return array(
			'total'    => $total,
			'per_page' => $per,
			'current'  => $current,
			'pages'    => max( 1, $pages ),
			'first'    => $total ? ( ( $current - 1 ) * $per ) + 1 : 0,
			'last'     => min( $total, $current * $per ),
		);
	}

	/* --------------------------------------------------------------------- */
	/* آیکون‌ها                                                                */
	/* --------------------------------------------------------------------- */

	/**
	 * آیکون‌های اختصاصی آرشیو که در مجموعه‌ی عمومی هلپر نیستند.
	 *
	 * @param string $name
	 * @param int    $size
	 * @param float  $width ضخامت خط.
	 * @return string
	 */
	public static function icon( $name, $size = 18, $width = 1.8 ) {
		$paths = array(
			'filter'  => '<path d="M4 6h16"/><path d="M7 12h10"/><path d="M10 18h4"/>',
			'sort'    => '<path d="M7 5v14"/><path d="M4 8l3-3 3 3"/><path d="M17 19V5"/><path d="M14 16l3 3 3-3"/>',
			'check'   => '<path d="M5 12.5l4.5 4.5L19 7"/>',
			'close'   => '<path d="M6 6l12 12"/><path d="M18 6L6 18"/>',
			'chev-d'  => '<path d="M6 9.5l6 6 6-6"/>',
			'chev-r'  => '<path d="M9.5 6.5L15 12l-5.5 5.5"/>',
			'chev-l'  => '<path d="M14.5 6.5L9 12l5.5 5.5"/>',
			'search'  => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/>',
			'empty'   => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-3-3"/>',
			'spark'   => '<path d="M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9z"/>',
			'heart'   => '<path d="M12 20s-7.3-4.5-7.3-9.4A4.2 4.2 0 0 1 12 8.2a4.2 4.2 0 0 1 7.3 2.4C19.3 15.5 12 20 12 20z"/>',
			'cart'    => '<path d="M3 4h2.2l2.3 11.2h10.2L20 7H6"/><circle cx="9.5" cy="19.5" r="1.4"/><circle cx="17" cy="19.5" r="1.4"/>',
			'refresh' => '<path d="M20 12a8 8 0 1 1-2.6-5.9"/><path d="M20 4.5V10h-5.5"/>',
		);

		if ( ! isset( $paths[ $name ] ) ) {
			$name = 'chev-d';
		}

		return sprintf(
			'<svg class="ard-ai ard-ai--%1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="%3$s" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%4$s</svg>',
			esc_attr( $name ),
			(int) $size,
			esc_attr( (string) $width ),
			$paths[ $name ]
		);
	}

	/**
	 * ستاره‌ی پر یا خالی برای امتیاز کارت و باکس فیلتر.
	 *
	 * @param bool $on
	 * @param int  $size
	 * @return string
	 */
	public static function star( $on, $size = 14 ) {
		return sprintf(
			'<svg class="ard-ai ard-ai--star%1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="%3$s" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12 4l2.4 4.9 5.4.8-3.9 3.8.9 5.4-4.8-2.5-4.8 2.5.9-5.4L4.2 9.7l5.4-.8z"/></svg>',
			$on ? ' is-on' : '',
			(int) $size,
			$on ? 'currentColor' : 'none'
		);
	}
}
