<?php
/**
 * جست‌وجوی محصول: یکدست‌سازی فارسی، نامزدیابی، رتبه‌بندی و نقطهٔ ایجکس.
 *
 * جست‌وجوی خود وردپرس روی فارسی خوب کار نمی‌کند: «آنجلز» و «انجلز»، «كفش» با
 * کافِ عربی و «کفش» با کافِ فارسی، «۱۰۰» و «100» — همه برای آن سه چیز جدا
 * هستند. اینجا هم عبارت کاربر و هم ستون دیتابیس با یک جدولِ مشترک یکدست
 * می‌شوند تا همه یکی شمرده شوند.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Search {

	/**
	 * نام اکشن ایجکس و نانس.
	 */
	const ACTION = 'ardal_search';

	/**
	 * سقف نامزد برای پنل زندهٔ کوچک.
	 */
	const CANDIDATES = 80;

	/**
	 * سقف نامزد برای صفحهٔ کامل نتیجه‌ها.
	 */
	const PAGE_CANDIDATES = 200;

	/**
	 * گزینهٔ شمارش عبارت‌های جست‌وجوشده (برای «جست‌وجوی پرمخاطب»).
	 */
	const POPULAR_OPTION = 'ardal_search_popular';

	/**
	 * بیشترین تعداد عبارتی که نگه داشته می‌شود؛ بیش از آن، کم‌شمارترها می‌روند.
	 */
	const POPULAR_CAP = 300;

	public static function init() {
		add_action( 'wp_ajax_' . self::ACTION, array( __CLASS__, 'ajax' ) );
		add_action( 'wp_ajax_nopriv_' . self::ACTION, array( __CLASS__, 'ajax' ) );
		add_action( 'wp_ajax_' . self::ACTION . '_log', array( __CLASS__, 'ajax_log' ) );
		add_action( 'wp_ajax_nopriv_' . self::ACTION . '_log', array( __CLASS__, 'ajax_log' ) );
		add_action( 'template_redirect', array( __CLASS__, 'track_search_page' ) );
	}

	/* --------------------------------------------------------------------- */
	/* جست‌وجوهای پرمخاطب                                                     */
	/* --------------------------------------------------------------------- */

	/**
	 * شکل نمایشیِ یکدستِ یک عبارت: فاصله‌های اضافه و نیم‌فاصله‌های تکراری حذف.
	 *
	 * کلید شمارش، شکل یکدست‌شده (normalize) است تا «كرم» و «کرم» یکی شمرده
	 * شوند؛ ولی برای نمایش، اولین شکلی که کاربر نوشته نگه داشته می‌شود.
	 *
	 * @param string $term
	 * @return string
	 */
	private static function clean_term( $term ) {
		$term = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( (string) $term ) ) );

		$length = function_exists( 'mb_strlen' ) ? mb_strlen( $term ) : strlen( $term );

		return ( $length < 2 || $length > 60 ) ? '' : $term;
	}

	/**
	 * یک بار شمردنِ یک عبارت.
	 *
	 * فقط عبارت‌هایی شمرده می‌شوند که دست‌کم یک محصول پیدا می‌کنند؛ وگرنه
	 * غلط‌های تایپی هم می‌توانستند به فهرست پرمخاطب راه پیدا کنند.
	 *
	 * @param string $term
	 */
	public static function log_term( $term ) {
		$term = self::clean_term( $term );

		if ( '' === $term || ! self::query( $term, 1 ) ) {
			return;
		}

		$key  = self::normalize( $term );
		$data = get_option( self::POPULAR_OPTION, array() );
		$data = is_array( $data ) ? $data : array();

		if ( isset( $data[ $key ] ) ) {
			$data[ $key ]['n'] = (int) $data[ $key ]['n'] + 1;
		} else {
			$data[ $key ] = array( 't' => $term, 'n' => 1 );
		}

		$data[ $key ]['at'] = time();

		if ( count( $data ) > self::POPULAR_CAP ) {
			uasort(
				$data,
				static function ( $a, $b ) {
					return (int) $b['n'] - (int) $a['n'];
				}
			);

			$data = array_slice( $data, 0, (int) ( self::POPULAR_CAP * 0.7 ), true );
		}

		update_option( self::POPULAR_OPTION, $data, false );
	}

	/**
	 * پرتکرارترین عبارت‌ها.
	 *
	 * @param int $limit
	 * @param int $min_count کمترین دفعه‌ای که باید جست‌وجو شده باشد.
	 * @return string[]
	 */
	public static function popular( $limit = 8, $min_count = 1 ) {
		$data = get_option( self::POPULAR_OPTION, array() );

		if ( ! is_array( $data ) || ! $data ) {
			return array();
		}

		uasort(
			$data,
			static function ( $a, $b ) {
				return (int) $b['n'] - (int) $a['n'] ?: (int) $b['at'] - (int) $a['at'];
			}
		);

		$out = array();

		foreach ( $data as $row ) {
			if ( (int) $row['n'] < $min_count ) {
				break;
			}

			$out[] = (string) $row['t'];

			if ( count( $out ) >= $limit ) {
				break;
			}
		}

		return $out;
	}

	/**
	 * شمردن جست‌وجویی که به صفحهٔ نتیجه‌ها رسیده (اینتر، دکمه، تاریخچه، چیپ).
	 */
	public static function track_search_page() {
		if ( is_admin() || ! is_search() || is_paged() ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$type = isset( $_GET['post_type'] ) ? sanitize_key( wp_unslash( $_GET['post_type'] ) ) : '';

		if ( 'product' !== $type ) {
			return;
		}

		self::log_term( get_search_query( false ) );
	}

	/**
	 * شمردن جست‌وجویی که با کلیک روی یک نتیجهٔ پنل زنده تمام شده؛ این‌ها هیچ‌وقت
	 * به صفحهٔ نتیجه‌ها نمی‌رسند.
	 */
	public static function ajax_log() {
		check_ajax_referer( self::ACTION, 'nonce' );

		$term = isset( $_POST['term'] ) ? sanitize_text_field( wp_unslash( $_POST['term'] ) ) : '';

		self::log_term( $term );

		wp_send_json_success();
	}

	/* --------------------------------------------------------------------- */
	/* یکدست‌سازی                                                             */
	/* --------------------------------------------------------------------- */

	/**
	 * یکدست‌کردن یک متن برای مقایسه.
	 *
	 * @param string $text
	 * @return string
	 */
	public static function normalize( $text ) {
		$text = wp_strip_all_tags( (string) $text );
		$text = strtr( $text, self::replacements() );

		// هر اعرابی که در جدول نیامده باشد.
		$text = (string) preg_replace( '/[\x{064B}-\x{065F}\x{0670}]/u', '', $text );
		$text = (string) preg_replace( '/\s+/u', ' ', $text );
		$text = trim( $text );

		return function_exists( 'mb_strtolower' ) ? mb_strtolower( $text, 'UTF-8' ) : strtolower( $text );
	}

	/**
	 * جدول یکدست‌سازی.
	 *
	 * یک‌جا نگهداری می‌شود چون هم PHP از آن استفاده می‌کند و هم SQL؛ اگر دو
	 * نسخه می‌داشتند از هم می‌افتادند.
	 *
	 * @return array
	 */
	private static function replacements() {
		return array(
			/* حروف هم‌ارز */
			'ي' => 'ی',
			'ك' => 'ک',
			'ة' => 'ه',
			'أ' => 'ا',
			'إ' => 'ا',
			'آ' => 'ا',
			'ؤ' => 'و',
			'ئ' => 'ی',

			/* نیم‌فاصله و کشیده */
			'‌' => ' ',
			'ـ' => '',

			/* اعراب */
			'ً' => '',
			'ٌ' => '',
			'ٍ' => '',
			'َ' => '',
			'ُ' => '',
			'ِ' => '',
			'ّ' => '',
			'ْ' => '',
			'ٓ' => '', // مد ترکیبی؛ «آ» گاهی «ا» + این ذخیره شده و LIKE پیدایش نمی‌کرد.
			'ٔ' => '',
			'ٕ' => '',
			'ٰ' => '',

			/* ارقام فارسی و عربی، تا «۱۰۰» و «100» یکی شوند */
			'۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
			'۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
			'٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
			'٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
		);
	}

	/**
	 * همان یکدست‌سازی، ولی روی یک ستون در SQL.
	 *
	 * چرا لازم است: عبارت کاربر یکدست می‌شود ولی LIKE روی متنِ خامِ دیتابیس
	 * اجرا می‌شود. اگر عنوان «آنجلز» باشد و کاربر «انجلز» بنویسد هرگز پیدا
	 * نمی‌شد. راه دیگر ساختن فهرستی از «نگارش‌های جایگزین عبارت» بود که تعداد
	 * ترکیب‌هایش نمایی می‌شود؛ یکدست‌کردن خودِ ستون هم کامل است و هم یک شرط
	 * بیشتر ندارد.
	 *
	 * @param string $column
	 * @return string
	 */
	private static function normalize_sql( $column ) {
		$expr = $column;

		foreach ( self::replacements() as $from => $to ) {
			$expr = sprintf( "REPLACE(%s,'%s','%s')", $expr, $from, $to );
		}

		return 'LOWER(' . $expr . ')';
	}

	/* --------------------------------------------------------------------- */
	/* جست‌وجو                                                                */
	/* --------------------------------------------------------------------- */

	/**
	 * چند محصول منطبق، مرتب‌شده.
	 *
	 * @param string $term
	 * @param int    $limit
	 * @return WC_Product[]
	 */
	public static function query( $term, $limit = 8 ) {
		$found = self::page( $term, $limit, 0, self::CANDIDATES );

		return $found['products'];
	}

	/**
	 * یک صفحه از نتیجه‌ها، همراه تعداد کل منطبق‌ها.
	 *
	 * @param string   $term
	 * @param int      $per_page
	 * @param int      $offset
	 * @param int|null $cap سقف نامزدها.
	 * @return array{products:WC_Product[], total:int}
	 */
	public static function page( $term, $per_page = 12, $offset = 0, $cap = null ) {
		$empty = array( 'products' => array(), 'total' => 0 );

		if ( ! function_exists( 'wc_get_product' ) ) {
			return $empty;
		}

		$term = self::normalize( $term );

		if ( '' === $term ) {
			return $empty;
		}

		$ids = self::candidates( $term, null === $cap ? self::PAGE_CANDIDATES : (int) $cap );

		if ( ! $ids ) {
			return $empty;
		}

		$rows = self::ranked( $ids, $term );

		if ( ! $rows ) {
			return $empty;
		}

		$slice = array_slice( $rows, max( 0, (int) $offset ), max( 1, (int) $per_page ) );

		return array(
			'products' => array_map(
				static function ( $row ) {
					return $row['product'];
				},
				$slice
			),
			'total'    => count( $rows ),
		);
	}

	/**
	 * شناسهٔ همهٔ محصولات منطبق با یک عبارت، با همان یکدست‌سازی فارسی.
	 *
	 * برای جست‌وجوی داخل فیلتر آرشیو: جست‌وجوی خود وردپرس بایت‌به‌بایت مقایسه
	 * می‌کند و «آدیداس» تایپ‌شده را با عنوانی که «آ» را دو کاراکتری ذخیره کرده
	 * (ا + مد) یکی نمی‌داند.
	 *
	 * @param string $term عبارت خام کاربر.
	 * @param int    $cap
	 * @return int[]
	 */
	public static function matching_ids( $term, $cap = 2000 ) {
		$term = self::normalize( $term );

		return '' === $term ? array() : self::candidates( $term, $cap );
	}

	/**
	 * نامزدهای خام از دیتابیس، بدون رتبه‌بندی.
	 *
	 * سه کوئری کوچک، نه یک کوئری با EXISTS روی postmeta: آن‌یکی به ازای هر
	 * محصول یک‌بار اجرا می‌شود، ولی کوئری جدا یک‌بار از ایندکس meta_key
	 * می‌خواند. رتبه‌بندی بعداً در PHP انجام می‌شود، پس ترتیبِ اینجا فقط وقتی
	 * مهم است که تعداد از سقف بگذرد.
	 *
	 * @param string $term عبارتِ یکدست‌شده.
	 * @param int    $cap
	 * @return int[]
	 */
	private static function candidates( $term, $cap ) {
		global $wpdb;

		$like = '%' . $wpdb->esc_like( $term ) . '%';
		$cap  = max( 1, (int) $cap );
		$ids  = array();

		// ۱) عنوان و خلاصه — همان‌جا یکدست می‌شوند.
		$sql = "
			SELECT p.ID
			FROM {$wpdb->posts} p
			WHERE p.post_type = 'product'
				AND p.post_status = 'publish'
				AND ( " . self::normalize_sql( 'p.post_title' ) . ' LIKE %s'
					. ' OR ' . self::normalize_sql( 'p.post_excerpt' ) . " LIKE %s )
			ORDER BY p.ID DESC
			LIMIT %d
		";

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- ساختار ثابت است و مقادیر با prepare بسته می‌شوند.
		$ids = array_merge( $ids, (array) $wpdb->get_col( $wpdb->prepare( $sql, array( $like, $like, $cap ) ) ) );

		/*
		 * ۲) SKU. لاتین است، پس یکدست‌سازی فارسی رویش معنا ندارد و LIKE ساده
		 * سریع‌تر است. جوین به posts برای این است که واریاسیون‌ها (که SKU جدا
		 * دارند) به‌جای محصول وارد نتیجه نشوند.
		 */
		$meta_sql = "
			SELECT p.ID
			FROM {$wpdb->postmeta} m
			INNER JOIN {$wpdb->posts} p ON p.ID = m.post_id
			WHERE m.meta_key = '_sku'
				AND m.meta_value LIKE %s
				AND p.post_type = 'product'
				AND p.post_status = 'publish'
			LIMIT %d
		";

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- ساختار ثابت است و مقادیر با prepare بسته می‌شوند.
		$ids = array_merge( $ids, (array) $wpdb->get_col( $wpdb->prepare( $meta_sql, array( $like, $cap ) ) ) );

		$ids = array_values( array_unique( array_map( 'absint', $ids ) ) );

		return array_slice( array_filter( $ids ), 0, $cap );
	}

	/**
	 * رتبه‌بندی نامزدها.
	 *
	 * @param int[]  $ids
	 * @param string $term عبارتِ یکدست‌شده.
	 * @return array
	 */
	private static function ranked( $ids, $term ) {
		$skip = class_exists( 'Ardal_Archive' ) ? Ardal_Archive::excluded_terms( 'product_cat' ) : array();
		$rows = array();

		foreach ( $ids as $id ) {
			$product = wc_get_product( (int) $id );

			if ( ! $product instanceof WC_Product || ! $product->is_visible() ) {
				continue;
			}

			// «بدون دسته‌بندی» از بقیهٔ فهرست‌ها حذف شده؛ اینجا هم نباید بیاید.
			if ( $skip ) {
				$cats = array_map( 'intval', (array) wp_get_post_terms( (int) $id, 'product_cat', array( 'fields' => 'ids' ) ) );

				if ( $cats && ! array_diff( $cats, $skip ) ) {
					continue;
				}
			}

			$score = self::score( $product, $term );

			if ( $score <= 0 ) {
				continue;
			}

			$rows[] = array(
				'product' => $product,
				'score'   => $score,
				'sales'   => (int) $product->get_total_sales(),
				'stock'   => $product->is_in_stock() ? 1 : 0,
			);
		}

		/*
		 * ترتیب: اول موجود بودن، بعد پلهٔ تطبیق، بعد فروش.
		 *
		 * ناموجودها ته فهرست می‌روند حتی با تطبیق دقیق — چیزی که نمی‌شود خرید
		 * نباید جای نتیجهٔ قابل‌خرید را بگیرد.
		 */
		usort(
			$rows,
			static function ( $a, $b ) {
				if ( $a['stock'] !== $b['stock'] ) {
					return $b['stock'] <=> $a['stock'];
				}

				if ( $a['score'] !== $b['score'] ) {
					return $b['score'] <=> $a['score'];
				}

				if ( $a['sales'] !== $b['sales'] ) {
					return $b['sales'] <=> $a['sales'];
				}

				return strcmp( $a['product']->get_name(), $b['product']->get_name() );
			}
		);

		return $rows;
	}

	/**
	 * پلهٔ تطبیق یک محصول با عبارت. صفر یعنی اصلاً منطبق نیست.
	 *
	 * @param WC_Product $product
	 * @param string     $term عبارتِ یکدست‌شده.
	 * @return int
	 */
	private static function score( $product, $term ) {
		$name = self::normalize( $product->get_name() );

		if ( $name === $term ) {
			return 100;
		}

		$sub = class_exists( 'Ardal_Product' ) ? self::normalize( Ardal_Product::subtitle( $product ) ) : '';

		if ( '' !== $sub && $sub === $term ) {
			return 95;
		}

		$sku = self::normalize( $product->get_sku() );

		if ( '' !== $sku && $sku === $term ) {
			return 90;
		}

		if ( 0 === strpos( $name, $term ) ) {
			return 80;
		}

		if ( '' !== $sub && 0 === strpos( $sub, $term ) ) {
			return 75;
		}

		// واژهٔ کامل داخل عنوان — از «کفش» در «کفشدوزک» ارزشمندتر است.
		if ( preg_match( '/(^|\s)' . preg_quote( $term, '/' ) . '(\s|$)/u', $name ) ) {
			return 70;
		}

		if ( false !== strpos( $name, $term ) ) {
			return 60;
		}

		if ( '' !== $sub && false !== strpos( $sub, $term ) ) {
			return 50;
		}

		if ( '' !== $sku && false !== strpos( $sku, $term ) ) {
			return 40;
		}

		if ( false !== strpos( self::normalize( $product->get_short_description() ), $term ) ) {
			return 20;
		}

		return 0;
	}

	/* --------------------------------------------------------------------- */
	/* ایجکس                                                                  */
	/* --------------------------------------------------------------------- */

	/**
	 * نشانی صفحهٔ نتیجه‌های کامل.
	 *
	 * @param string $term
	 * @return string
	 */
	public static function results_url( $term ) {
		return add_query_arg(
			array(
				's'         => rawurlencode( $term ),
				'post_type' => 'product',
			),
			home_url( '/' )
		);
	}

	/**
	 * نقطهٔ ایجکس: HTML پنل نتیجه‌ها را برمی‌گرداند.
	 *
	 * مارک‌آپ عمداً در PHP ساخته می‌شود تا کارت جست‌وجو یک‌جا نگهداری شود و
	 * جاوااسکریپت فقط تزریق کند.
	 */
	public static function ajax() {
		check_ajax_referer( self::ACTION, 'nonce' );

		$term = isset( $_POST['term'] ) ? sanitize_text_field( wp_unslash( $_POST['term'] ) ) : '';

		if ( '' === trim( $term ) ) {
			wp_send_json_success( array( 'html' => '', 'count' => 0 ) );
		}

		$raw  = isset( $_POST['args'] ) && is_array( $_POST['args'] ) ? wp_unslash( $_POST['args'] ) : array(); // phpcs:ignore WordPress.Security.ValidationSanitization.InputNotSanitized -- پایین تک‌تک پاک می‌شوند.
		$args = self::panel_args( $raw );

		$products = self::query( $term, $args['limit'] );

		wp_send_json_success(
			array(
				'html'  => self::panel_html( $products, $term, $args ),
				'count' => count( $products ),
			)
		);
	}

	/**
	 * پاک‌سازی پارامترهایی که از مرورگر می‌آیند.
	 *
	 * هرچه از ایجکس می‌رسد ورودی کاربر است، حتی اگر ما خودمان نوشته باشیمش؛
	 * پس هیچ‌کدام مستقیم به خروجی نمی‌روند.
	 *
	 * @param array $raw
	 * @return array
	 */
	private static function panel_args( $raw ) {
		$out = array(
			'limit'      => 8,
			'image'      => 'yes',
			'subtitle'   => 'no',
			'price'      => 'yes',
			'discount'   => 'yes',
			'rating'     => 'no',
			'stock'      => 'no',
			'more'       => 'yes',
			'more_text'  => 'دیدن همهٔ نتیجه‌ها',
			'empty_text' => 'چیزی پیدا نشد.',
		);

		foreach ( array( 'image', 'subtitle', 'price', 'discount', 'rating', 'stock', 'more' ) as $key ) {
			if ( isset( $raw[ $key ] ) ) {
				$out[ $key ] = 'yes' === $raw[ $key ] ? 'yes' : 'no';
			}
		}

		if ( isset( $raw['limit'] ) ) {
			$out['limit'] = max( 1, min( 30, absint( $raw['limit'] ) ) );
		}

		foreach ( array( 'more_text', 'empty_text' ) as $key ) {
			if ( isset( $raw[ $key ] ) && '' !== trim( (string) $raw[ $key ] ) ) {
				$out[ $key ] = sanitize_text_field( (string) $raw[ $key ] );
			}
		}

		return $out;
	}

	/**
	 * HTML کل پنل نتیجه.
	 *
	 * @param WC_Product[] $products
	 * @param string       $term عبارت خام کاربر.
	 * @param array        $args
	 * @return string
	 */
	public static function panel_html( $products, $term, $args ) {
		ob_start();

		if ( ! $products ) {
			?>
			<div class="ard-se__empty">
				<span class="ard-se__empty-icon" aria-hidden="true"><?php echo Ardal_Helpers::icon( 'search', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span><?php echo esc_html( $args['empty_text'] ); ?></span>
			</div>
			<?php

			return (string) ob_get_clean();
		}
		?>
		<div class="ard-se__list">
			<?php foreach ( $products as $product ) : ?>
				<?php self::panel_card( $product, $args ); ?>
			<?php endforeach; ?>
		</div>

		<?php
		/*
		 * لینک ساده به صفحهٔ جست‌وجو؛ جاوااسکریپت جلویش را نمی‌گیرد. پنل فقط
		 * چند نتیجهٔ اول را نشان می‌دهد و ادامه‌اش کارِ همان صفحه است.
		 */
		?>
		<?php if ( 'yes' === $args['more'] ) : ?>
			<a class="ard-se__more" href="<?php echo esc_url( self::results_url( $term ) ); ?>">
				<?php echo esc_html( $args['more_text'] ); ?>
				<span class="ard-se__more-arrow" aria-hidden="true">&rsaquo;</span>
			</a>
		<?php endif; ?>
		<?php

		return (string) ob_get_clean();
	}

	/**
	 * یک ردیف نتیجه در پنل. هر بخشش با پارامتر خاموش می‌شود.
	 *
	 * @param WC_Product $product
	 * @param array      $args
	 */
	private static function panel_card( $product, $args ) {
		$out = ! $product->is_in_stock();
		$sub = 'yes' === $args['subtitle'] && class_exists( 'Ardal_Product' ) ? Ardal_Product::subtitle( $product ) : '';
		$off = self::discount( $product );

		/*
		 * قیمت از همان راهی می‌آید که کارت‌های اردال می‌سازند، نه از
		 * get_price_html() ووکامرس: آن یکی برای محصول متغیر بازهٔ قیمت
		 * می‌دهد (یا هیچ)، ارقامش لاتین است و واحد پول اردال را نمی‌شناسد.
		 */
		$row  = Ardal_Helpers::product_to_card( $product );
		$card = array(
			'price' => isset( $row['price'] ) ? (string) $row['price'] : '',
			'old'   => isset( $row['old_price'] ) ? (string) $row['old_price'] : '',
		);
		?>
		<a class="ard-se__card<?php echo $out ? ' is-out' : ''; ?>"
			href="<?php echo esc_url( $product->get_permalink() ); ?>"
			role="option" tabindex="-1" data-ard-se-item>

			<?php if ( 'yes' === $args['image'] ) : ?>
				<span class="ard-se__thumb">
					<?php echo $product->get_image( 'woocommerce_thumbnail', array( 'loading' => 'lazy', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

					<?php if ( 'yes' === $args['discount'] && $off > 0 ) : ?>
						<span class="ard-se__off"><?php echo esc_html( Ardal_Helpers::fa_digits( $off ) . '٪' ); ?></span>
					<?php endif; ?>
				</span>
			<?php endif; ?>

			<span class="ard-se__body">
				<span class="ard-se__title"><?php echo esc_html( $product->get_name() ); ?></span>

				<?php if ( '' !== $sub ) : ?>
					<span class="ard-se__sub"><?php echo esc_html( $sub ); ?></span>
				<?php endif; ?>

				<?php if ( 'yes' === $args['rating'] ) : ?>
					<?php $rating = round( (float) $product->get_average_rating(), 1 ); ?>
					<?php if ( $rating > 0 ) : ?>
						<span class="ard-se__rate"><span aria-hidden="true">★</span><?php echo esc_html( Ardal_Helpers::fa_digits( $rating ) ); ?></span>
					<?php endif; ?>
				<?php endif; ?>

				<?php if ( 'yes' === $args['stock'] ) : ?>
					<span class="ard-se__stock<?php echo $out ? ' is-out' : ''; ?>"><?php echo esc_html( $out ? 'ناموجود' : 'موجود' ); ?></span>
				<?php endif; ?>
			</span>

			<?php if ( 'yes' === $args['price'] && '' !== $card['price'] ) : ?>
				<span class="ard-se__price">
					<?php if ( '' !== $card['old'] ) : ?>
						<del class="ard-se__old"><?php echo esc_html( $card['old'] ); ?></del>
					<?php endif; ?>

					<span class="ard-se__now">
						<?php echo esc_html( $card['price'] ); ?>
						<?php echo Ardal_Currency::unit(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خروجی ثابت و امن. ?>
					</span>
				</span>
			<?php endif; ?>
		</a>
		<?php
	}

	/**
	 * درصد تخفیف محصول؛ برای محصول متغیر، همان «بهترین معامله»ای که کارت‌ها
	 * نشان می‌دهند.
	 *
	 * @param WC_Product $product
	 * @return int
	 */
	private static function discount( $product ) {
		if ( $product->is_type( 'variable' ) && class_exists( 'Ardal_Product' ) ) {
			$deal = Ardal_Product::best_deal( $product );

			return $deal ? (int) $deal['off'] : 0;
		}

		$regular = (float) $product->get_regular_price();
		$price   = (float) $product->get_price();

		if ( $regular <= 0 || $price <= 0 || $price >= $regular ) {
			return 0;
		}

		return (int) round( ( ( $regular - $price ) / $regular ) * 100 );
	}
}
