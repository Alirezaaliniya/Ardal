<?php
/**
 * پنل کاربری ووکامرس — اندپوینت‌ها، بارگذاری تمپلیت‌ها و دادهٔ صفحه‌ها.
 *
 * تمپلیت‌ها در پوشهٔ woocommerce/myaccount افزونه هستند و با فیلتر
 * woocommerce_locate_template جای تمپلیت‌های پیش‌فرض می‌نشینند؛ اگر قالب سایت
 * همان فایل را داشته باشد، اولویت با قالب می‌ماند.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Account {

	/**
	 * کلید متای علاقه‌مندی‌های کاربر.
	 */
	const WISHLIST_META = '_ardal_wishlist';

	/**
	 * نسخهٔ اندپوینت‌ها؛ با تغییرش قوانین بازنویسی یک بار تازه می‌شوند.
	 */
	const ENDPOINT_VERSION = '1';

	/**
	 * اندپوینت‌های افزوده‌شده به پنل: اسلاگ => عنوان.
	 *
	 * @return array
	 */
	public static function endpoints() {
		return array(
			'wishlist' => 'علاقه‌مندی‌ها',
			'reviews'  => 'نظرات من',
		);
	}

	public static function init() {
		// ووکامرس ممکن است بعد از اردال لود شود، پس بررسی فعال بودنش تا
		// plugins_loaded عقب می‌افتد؛ این هوک هنوز پیش از init است.
		add_action( 'plugins_loaded', array( __CLASS__, 'boot' ), 20 );
	}

	public static function boot() {
		if ( ! Ardal_Helpers::has_woo() ) {
			return;
		}

		add_action( 'init', array( __CLASS__, 'add_endpoints' ) );
		add_filter( 'woocommerce_get_query_vars', array( __CLASS__, 'query_vars' ) );
		add_filter( 'woocommerce_account_menu_items', array( __CLASS__, 'menu_items' ) );
		add_filter( 'woocommerce_endpoint_wishlist_title', array( __CLASS__, 'wishlist_title' ) );
		add_filter( 'woocommerce_endpoint_reviews_title', array( __CLASS__, 'reviews_title' ) );

		add_action( 'woocommerce_account_wishlist_endpoint', array( __CLASS__, 'render_wishlist' ) );
		add_action( 'woocommerce_account_reviews_endpoint', array( __CLASS__, 'render_reviews' ) );

		add_filter( 'woocommerce_locate_template', array( __CLASS__, 'locate_template' ), 10, 3 );
		add_filter( 'body_class', array( __CLASS__, 'body_class' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ), 20 );

		add_action( 'wp_ajax_ardal_wishlist', array( __CLASS__, 'ajax_wishlist' ) );

		Ardal_Account_Ajax::init();
		add_action( 'wp_loaded', array( __CLASS__, 'handle_reorder' ), 30 );
		add_action( 'woocommerce_save_account_details', array( __CLASS__, 'save_profile_fields' ) );
	}

	/**
	 * فیلدهای اضافهٔ فرم «اطلاعات حساب» مطابق دیزاین پنل.
	 *
	 * @return array
	 */
	public static function profile_fields() {
		return (array) apply_filters(
			'ardal_account_profile_fields',
			array(
				'_ardal_national_code' => array(
					'label'       => 'کد ملی',
					'type'        => 'text',
					'placeholder' => 'ده رقم بدون خط تیره',
				),
				'_ardal_birthdate'     => array(
					'label'       => 'تاریخ تولد',
					'type'        => 'text',
					'placeholder' => 'مثلاً ۱۲ آبان ۱۳۷۱',
				),
				'_ardal_gender'        => array(
					'label'   => 'جنسیت',
					'type'    => 'select',
					'options' => array(
						''       => 'انتخاب کنید',
						'male'   => 'مرد',
						'female' => 'زن',
						'none'   => 'ترجیح می‌دهم نگویم',
					),
				),
				'_ardal_sports'        => array(
					'label'       => 'رشته ورزشی مورد علاقه',
					'type'        => 'text',
					'placeholder' => 'مثلاً دویدن، فیتنس',
					'wide'        => true,
				),
			)
		);
	}

	/**
	 * ذخیرهٔ شمارهٔ موبایل و فیلدهای اضافه. نانس را خود ووکامرس پیش از این
	 * هوک بررسی کرده است.
	 *
	 * @param int $user_id
	 */
	public static function save_profile_fields( $user_id ) {
		// phpcs:disable WordPress.Security.NonceVerification.Missing -- ووکامرس در save_account_details نانس را بررسی کرده است.
		if ( isset( $_POST['ardal_billing_phone'] ) ) {
			update_user_meta( $user_id, 'billing_phone', wc_clean( wp_unslash( $_POST['ardal_billing_phone'] ) ) );
		}

		foreach ( self::profile_fields() as $key => $field ) {
			if ( ! isset( $_POST[ $key ] ) ) {
				continue;
			}

			$value = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );

			if ( 'select' === $field['type'] && ! isset( $field['options'][ $value ] ) ) {
				continue;
			}

			if ( '' === $value ) {
				delete_user_meta( $user_id, $key );

				continue;
			}

			update_user_meta( $user_id, $key, $value );
		}
		// phpcs:enable WordPress.Security.NonceVerification.Missing
	}

	/**
	 * «خرید دوباره»: همهٔ کالاهای یک سفارش قبلی را دوباره به سبد می‌ریزد و به
	 * سبد خرید می‌برد. کالاهایی که دیگر موجود نیستند رد می‌شوند و پیام می‌گیرند.
	 */
	public static function handle_reorder() {
		if ( empty( $_GET['ardal-reorder'] ) || is_admin() ) {
			return;
		}

		$order_id = absint( $_GET['ardal-reorder'] );

		if ( ! check_admin_referer( 'ardal-reorder-' . $order_id ) ) {
			return;
		}

		$order = wc_get_order( $order_id );

		if ( ! $order || get_current_user_id() !== $order->get_customer_id() || ! WC()->cart ) {
			return;
		}

		$added   = 0;
		$skipped = 0;

		foreach ( $order->get_items() as $item ) {
			$product = $item->get_product();

			if ( ! $product || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
				$skipped++;

				continue;
			}

			$result = WC()->cart->add_to_cart(
				$item->get_product_id(),
				$item->get_quantity(),
				$item->get_variation_id(),
				method_exists( $item, 'get_variation' ) ? (array) $item->get_variation() : array()
			);

			if ( $result ) {
				$added++;
			} else {
				$skipped++;
			}
		}

		if ( $added > 0 ) {
			wc_add_notice(
				sprintf( '%s کالا از سفارش قبلی به سبد اضافه شد.', Ardal_Helpers::fa_digits( $added ) ),
				'success'
			);
		}

		if ( $skipped > 0 ) {
			wc_add_notice(
				sprintf( '%s کالا دیگر قابل خرید نیست و اضافه نشد.', Ardal_Helpers::fa_digits( $skipped ) ),
				'notice'
			);
		}

		wp_safe_redirect( wc_get_cart_url() );
		exit;
	}

	/* ============================================================
	   اندپوینت‌ها و منو
	   ============================================================ */

	public static function add_endpoints() {
		foreach ( array_keys( self::endpoints() ) as $slug ) {
			add_rewrite_endpoint( $slug, EP_ROOT | EP_PAGES );
		}

		// فقط یک بار بعد از افزوده شدن اندپوینت‌ها، نه در هر بارگذاری صفحه.
		if ( self::ENDPOINT_VERSION !== get_option( 'ardal_account_endpoints' ) ) {
			flush_rewrite_rules( false );
			update_option( 'ardal_account_endpoints', self::ENDPOINT_VERSION );
		}
	}

	/**
	 * @param array $vars
	 * @return array
	 */
	public static function query_vars( $vars ) {
		foreach ( array_keys( self::endpoints() ) as $slug ) {
			$vars[ $slug ] = $slug;
		}

		return $vars;
	}

	/**
	 * ترتیب منو مطابق دیزاین: داشبورد، سفارش‌ها، علاقه‌مندی‌ها، آدرس‌ها،
	 * اطلاعات حساب، نظرات. آیتم‌های افزونه‌های دیگر بعد از این‌ها می‌مانند و
	 * «خروج» از فهرست حذف می‌شود چون در تمپلیت جدا رندر می‌شود.
	 *
	 * @param array $items
	 * @return array
	 */
	public static function menu_items( $items ) {
		unset( $items['customer-logout'] );

		/*
		 * فهرست، ترتیب و عنوان‌ها از «تنظیمات اردال ← پنل کاربری» می‌آید. آیتم
		 * خاموش حذف می‌شود و آیتمی که ووکامرس اصلاً نساخته (مثلاً دانلودها وقتی
		 * محصول دانلودی نیست) هم اضافه نمی‌شود.
		 */
		$wanted = Ardal_Account_Settings::menu();
		$known  = array_keys( Ardal_Account_Settings::menu_defaults() );
		$sorted = array();

		foreach ( $wanted as $slug => $label ) {
			// اندپوینت‌های خود اردال را ووکامرس در $items ندارد.
			$ours = array_key_exists( $slug, self::endpoints() );

			if ( $ours || isset( $items[ $slug ] ) ) {
				$sorted[ $slug ] = $label;
			}

			unset( $items[ $slug ] );
		}

		// آیتم‌هایی که ادمین خاموششان کرده نباید از بقیهٔ فهرست برگردند.
		foreach ( $known as $slug ) {
			unset( $items[ $slug ] );
		}

		// هر آیتمی که افزونهٔ دیگری اضافه کرده، دست‌نخورده ته فهرست می‌ماند.
		return array_merge( $sorted, $items );
	}

	public static function wishlist_title() {
		return Ardal_Account_Settings::get( 'wishlist_title' );
	}

	public static function reviews_title() {
		return 'نظرات من';
	}

	public static function render_wishlist() {
		wc_get_template( 'myaccount/wishlist.php' );
	}

	public static function render_reviews() {
		wc_get_template( 'myaccount/reviews.php' );
	}

	/**
	 * تمپلیت‌های پنل از پوشهٔ افزونه خوانده می‌شوند، مگر اینکه قالب سایت
	 * نسخهٔ خودش را داشته باشد.
	 *
	 * @param string $template
	 * @param string $name
	 * @param string $path
	 * @return string
	 */
	public static function locate_template( $template, $name, $path ) {
		if ( 0 !== strpos( $name, 'myaccount/' ) ) {
			return $template;
		}

		// اگر قالب سایت این تمپلیت را اورراید کرده، دست نمی‌زنیم.
		if ( $template && 0 !== strpos( $template, WC()->plugin_path() ) ) {
			return $template;
		}

		$file = ARDAL_PATH . 'woocommerce/' . $name;

		return file_exists( $file ) ? $file : $template;
	}

	/**
	 * @param array $classes
	 * @return array
	 */
	public static function body_class( $classes ) {
		if ( self::is_account_page() ) {
			$classes[] = 'ardal-account';
		}

		return $classes;
	}

	/**
	 * @return bool
	 */
	public static function is_account_page() {
		return function_exists( 'is_account_page' ) && is_account_page();
	}

	public static function enqueue() {
		if ( ! self::is_account_page() ) {
			return;
		}

		wp_enqueue_style( 'ardal' );
		wp_enqueue_style( 'ardal-account' );
		wp_enqueue_script( 'ardal' );
		wp_enqueue_script( 'ardal-account' );

		wp_localize_script(
			'ardal-account',
			'ardalAccount',
			array(
				'ajax'       => admin_url( 'admin-ajax.php' ),
				'nonce'      => wp_create_nonce( 'ardal-account' ),
				'loggedIn'   => is_user_logged_in(),
				// ناوبری ایجکسی هر لینکی را که زیر این نشانی باشد می‌گیرد؛
				// همین باعث می‌شود آیتم افزونه‌های دیگر هم خودکار کار کند.
				'accountUrl' => wc_get_page_permalink( 'myaccount' ),
				'logoutUrl'  => wc_logout_url(),
				'siteName'   => get_bloginfo( 'name' ),
			)
		);
	}

	/* ============================================================
	   آیکون‌ها — همان مسیرهای دیزاین
	   ============================================================ */

	/**
	 * @param string $name
	 * @param int    $size
	 * @param string $class
	 * @return string
	 */
	public static function icon( $name, $size = 18, $class = '' ) {
		$paths = array(
			'dash'    => 'M4 11l8-6 8 6v8a1 1 0 0 1-1 1h-4v-6H9v6H5a1 1 0 0 1-1-1z',
			'orders'  => 'M6 4h12l1 16H5zM9 8h6',
			'fav'     => 'M12 20s-7-4.4-7-9a4 4 0 0 1 7-2.6A4 4 0 0 1 19 11c0 4.6-7 9-7 9z',
			'addr'    => 'M12 21s7-6 7-11a7 7 0 1 0-14 0c0 5 7 11 7 11zM12 12.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z',
			'user'    => 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM4.5 20c1.2-3.4 4-5 7.5-5s6.3 1.6 7.5 5',
			'review'  => 'M20 15a2 2 0 0 1-2 2H8l-4 4V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2z',
			'wallet'  => 'M3 8a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2zM16 12.5h3',
			'box'     => 'M4 8l8-4 8 4v8l-8 4-8-4zM4 8l8 4 8-4M12 12v8',
			'cart'    => 'M4 5h2l2.4 9.5h9.2L20 8H7M9 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2zM17 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2z',
			'chart'   => 'M4 20V10M10 20V4M16 20v-7M22 20H2',
			'ticket'  => 'M4 8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2 2 2 0 0 0 0 4 2 2 0 0 1-2 2H6a2 2 0 0 1-2-2 2 2 0 0 0 0-4z',
			'logout'  => 'M14 8V5H5v14h9v-3M10 12h10m0 0l-3-3m3 3l-3 3',
			'bell'    => 'M18 16V11a6 6 0 1 0-12 0v5l-1.5 2.5h15zM10 19a2 2 0 0 0 4 0',
			'truck'   => 'M3 16V7h11v9M14 10h4l3 3v3h-7M6.5 19a1.8 1.8 0 1 0 0-3.6 1.8 1.8 0 0 0 0 3.6zM17.5 19a1.8 1.8 0 1 0 0-3.6 1.8 1.8 0 0 0 0 3.6z',
			'clock'   => 'M12 8v4l3 2',
			'check'   => 'M5 13l4 4L19 7',
			'plus'    => 'M12 5v14M5 12h14',
			'back'    => 'M14 6l6 6-6 6',
			'chevron' => 'M14 6l-6 6 6 6',
			'close'   => 'M6 6l12 12M18 6L6 18',
			'work'    => 'M4 20V8l8-4 8 4v12M9 20v-6h6v6',
			'thumb'   => 'M7 20V9l4-6 1 1v5h6l1 1-2 10z',
			'down'    => 'M12 4v12m0 0l-4-4m4 4l4-4M5 20h14',
		);

		if ( ! isset( $paths[ $name ] ) ) {
			$name = 'dash';
		}

		return sprintf(
			'<svg class="ard-ac__i%1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="%3$s"></path></svg>',
			'' !== $class ? ' ' . esc_attr( $class ) : '',
			(int) $size,
			esc_attr( $paths[ $name ] )
		);
	}

	/**
	 * آیکون هر آیتم منو.
	 *
	 * @param string $key
	 * @return string
	 */
	public static function menu_icon( $key ) {
		$map = array(
			'dashboard'       => 'dash',
			'orders'          => 'orders',
			'wishlist'        => 'fav',
			'edit-address'    => 'addr',
			'edit-account'    => 'user',
			'reviews'         => 'review',
			'downloads'       => 'box',
			'payment-methods' => 'wallet',
			'customer-logout' => 'logout',
		);

		return isset( $map[ $key ] ) ? $map[ $key ] : 'dash';
	}

	/* ============================================================
	   پوستهٔ صفحه‌های بیرون از حساب (ورود، بازیابی رمز)
	   ============================================================ */

	/**
	 * نشان برند بالای پنل: لوگوی سایت اگر تنظیم شده باشد، وگرنه نشان پیش‌فرض.
	 *
	 * عمداً به‌جای the_custom_logo() خود تصویر چاپ می‌شود؛ آن تابع یک تگ <a>
	 * می‌سازد و اینجا داخل لینک برند قرار می‌گیرد. لینک تودرتو HTML نامعتبر
	 * است و مرورگر موقع پارس، لینک داخلی را از دل والدش بیرون می‌کشد — که هم
	 * ساختار را می‌شکند و هم قاعدهٔ اندازهٔ تصویر را بی‌اثر می‌کند.
	 *
	 * @return string
	 */
	public static function brand_mark() {
		$logo_id = (int) get_theme_mod( 'custom_logo' );

		if ( $logo_id > 0 ) {
			$image = wp_get_attachment_image(
				$logo_id,
				'full',
				false,
				array(
					'class' => 'ard-ac__brand-img',
					'alt'   => get_bloginfo( 'name' ),
				)
			);

			if ( $image ) {
				return $image;
			}
		}

		return '<span class="ard-ac__brand-mark">'
			. '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">'
			. '<path d="M4 14c3-6 13-6 16 0"></path><path d="M7 19c2-3 8-3 10 0"></path>'
			. '</svg></span>';
	}

	/**
	 * وقتی کاربر وارد نشده، ووکامرس پوستهٔ my-account.php را رندر نمی‌کند؛ پس
	 * این صفحه‌ها قاب خودشان را دارند.
	 *
	 * @param string $title
	 * @param string $subtitle
	 */
	public static function auth_open( $title, $subtitle = '' ) {
		?>
		<div class="ard-ac ard-ac--auth">
			<div class="ard-ac__auth">
				<a class="ard-ac__auth-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php echo self::brand_mark(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خروجی wp_get_attachment_image یا SVG ثابت. ?>
					<span class="ard-ac__brand-text">
						<span class="ard-ac__brand-name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
						<span class="ard-ac__brand-sub">پنل کاربری</span>
					</span>
				</a>

				<div class="ard-ac__auth-head">
					<h1 class="ard-ac__auth-title"><?php echo esc_html( $title ); ?></h1>
					<?php if ( '' !== $subtitle ) : ?>
						<p class="ard-ac__auth-sub"><?php echo esc_html( $subtitle ); ?></p>
					<?php endif; ?>
				</div>
		<?php
	}

	public static function auth_close() {
		?>
				<a class="ard-ac__auth-back" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>">بازگشت به فروشگاه</a>
			</div>
		</div>
		<?php
	}

	/* ============================================================
	   کاربر
	   ============================================================ */

	/**
	 * نوع آدرس‌های حساب: صورتحساب و — اگر ارسال فعال باشد — تحویل.
	 *
	 * ووکامرس تابع آماده‌ای برای این کار ندارد و خود تمپلیت my-address.php
	 * همین فیلتر را می‌سازد؛ پس از همان مسیر می‌رویم تا افزونه‌هایی که به این
	 * فیلتر وصل‌اند هم دیده شوند.
	 *
	 * @return array
	 */
	public static function address_types() {
		$customer_id = get_current_user_id();

		$types = ! wc_ship_to_billing_address_only() && wc_shipping_enabled()
			? array(
				'billing'  => 'آدرس صورتحساب',
				'shipping' => 'آدرس تحویل سفارش',
			)
			: array( 'billing' => 'آدرس صورتحساب' );

		return (array) apply_filters( 'woocommerce_my_account_get_addresses', $types, $customer_id );
	}

	/**
	 * تعداد آدرس‌های تکمیل‌شده.
	 *
	 * @return int
	 */
	public static function filled_addresses() {
		$count = 0;

		foreach ( array_keys( self::address_types() ) as $type ) {
			if ( wc_get_account_formatted_address( $type ) ) {
				$count++;
			}
		}

		return $count;
	}

	/**
	 * نام نمایشی کاربر: نام و نام خانوادگی، وگرنه نام نمایشی حساب.
	 *
	 * @return string
	 */
	public static function name() {
		$user = wp_get_current_user();
		$name = trim( $user->first_name . ' ' . $user->last_name );

		return '' !== $name ? $name : $user->display_name;
	}

	/**
	 * حرف اول نام و نام خانوادگی برای آواتار متنی («ر.م»).
	 *
	 * @return string
	 */
	public static function initials() {
		$parts = preg_split( '/\s+/u', self::name(), -1, PREG_SPLIT_NO_EMPTY );

		if ( empty( $parts ) ) {
			return '؟';
		}

		$first = function_exists( 'mb_substr' ) ? mb_substr( $parts[0], 0, 1, 'UTF-8' ) : substr( $parts[0], 0, 1 );

		if ( count( $parts ) < 2 ) {
			return $first;
		}

		$last = end( $parts );
		$last = function_exists( 'mb_substr' ) ? mb_substr( $last, 0, 1, 'UTF-8' ) : substr( $last, 0, 1 );

		return $first . '.' . $last;
	}

	/**
	 * شمارهٔ تماس کاربر از آدرس صورتحساب.
	 *
	 * @return string
	 */
	public static function phone() {
		$phone = get_user_meta( get_current_user_id(), 'billing_phone', true );

		return '' !== $phone ? Ardal_Helpers::fa_digits( $phone ) : '';
	}

	/* ============================================================
	   آمار و نمودار
	   ============================================================ */

	/**
	 * چهار کارت آمار بالای داشبورد.
	 *
	 * @return array
	 */
	public static function stats() {
		$user_id = get_current_user_id();
		$orders  = (int) wc_get_customer_order_count( $user_id );
		$spent   = (float) wc_get_customer_total_spent( $user_id );
		$reviews = self::review_stats();

		// مبلغ کل به میلیون، چون عدد خام روی کارت خوانا نیست.
		$millions = $spent >= 1000000
			? number_format( $spent / 1000000, 1, '٫', '' )
			: number_format( $spent / 1000000, 2, '٫', '' );

		return array(
			array(
				'value' => Ardal_Helpers::fa_digits( $orders ),
				'unit'  => 'سفارش',
				'label' => 'مجموع سفارش‌ها',
				'icon'  => 'box',
				'delta' => self::recent_order_delta(),
			),
			array(
				'value' => Ardal_Helpers::fa_digits( $millions ),
				'unit'  => 'میلیون',
				'label' => 'مبلغ کل خرید',
				'icon'  => 'chart',
				'delta' => '',
			),
			array(
				'value' => Ardal_Helpers::fa_digits( count( self::wishlist_ids() ) ),
				'unit'  => 'کالا',
				'label' => 'علاقه‌مندی‌ها',
				'icon'  => 'fav',
				'delta' => '',
			),
			array(
				'value' => Ardal_Helpers::fa_digits( $reviews['approved'] ),
				'unit'  => 'دیدگاه',
				'label' => 'نظرات ثبت شده',
				'icon'  => 'review',
				'delta' => '',
			),
		);
	}

	/**
	 * تعداد سفارش‌های ۳۰ روز گذشته، به شکل «+۳».
	 *
	 * @return string
	 */
	private static function recent_order_delta() {
		$count = count(
			self::get_orders(
				array(
					'limit'        => 20,
					'date_created' => '>' . ( time() - 30 * DAY_IN_SECONDS ),
				)
			)
		);

		return $count > 0 ? '+' . Ardal_Helpers::fa_digits( $count ) : '';
	}

	/**
	 * سفارش‌های کاربر جاری.
	 *
	 * @param array $args
	 * @return array
	 */
	public static function get_orders( $args = array() ) {
		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			return array();
		}

		return wc_get_orders(
			array_merge(
				array(
					'customer_id' => $user_id,
					'limit'       => 10,
					'orderby'     => 'date',
					'order'       => 'DESC',
				),
				$args
			)
		);
	}

	/**
	 * مجموع خرید شش ماه شمسی گذشته برای نمودار میله‌ای.
	 *
	 * @return array
	 */
	public static function chart() {
		$orders = self::get_orders(
			array(
				'limit'        => -1,
				'status'       => wc_get_is_paid_statuses(),
				'date_created' => '>' . ( time() - 190 * DAY_IN_SECONDS ),
			)
		);

		$now    = Ardal_Jalali::now();
		$months = array();

		// سطل‌ها با حساب ماه شمسی ساخته می‌شوند، نه با عقب بردن تاریخ میلادی:
		// اول مهر، «یک ماه قبلِ» اول سپتامبر هنوز شهریور است و سفارش‌های همین
		// ماه در هیچ سطلی نمی‌افتادند.
		list( $jy, $jm ) = array_map( 'intval', explode( '-', Ardal_Jalali::month_key( $now ) ) );

		// شش ماه شمسی اخیر، از قدیمی به جدید.
		for ( $i = 5; $i >= 0; $i-- ) {
			$month = $jm - $i;
			$year  = $jy;

			while ( $month < 1 ) {
				$month += 12;
				$year--;
			}

			$months[ sprintf( '%04d-%02d', $year, $month ) ] = array(
				'month' => Ardal_Jalali::month_label( $month ),
				'total' => 0.0,
			);
		}

		foreach ( $orders as $order ) {
			$date = $order->get_date_created();

			if ( ! $date ) {
				continue;
			}

			$key = Ardal_Jalali::month_key( $date->getTimestamp() );

			if ( isset( $months[ $key ] ) ) {
				$months[ $key ]['total'] += (float) $order->get_total();
			}
		}

		$max = 0.0;

		foreach ( $months as $month ) {
			$max = max( $max, $month['total'] );
		}

		$out  = array();
		$last = count( $months ) - 1;
		$i    = 0;

		foreach ( $months as $month ) {
			$value = $month['total'] / 1000000;

			$out[] = array(
				'month'   => $month['month'],
				'value'   => Ardal_Helpers::fa_digits( number_format( $value, 1, '٫', '' ) ),
				'height'  => $max > 0 ? max( 4, (int) round( $month['total'] / $max * 100 ) ) : 4,
				'current' => $i === $last,
			);

			$i++;
		}

		return $out;
	}

	/* ============================================================
	   سفارش‌ها
	   ============================================================ */

	/**
	 * وضعیت‌هایی که یعنی مرسوله در راه است.
	 *
	 * @return array
	 */
	public static function shipping_statuses() {
		return (array) apply_filters( 'ardal_account_shipping_statuses', array( 'shipped', 'wc-shipped', 'in-transit' ) );
	}

	/**
	 * سفارشی که همین حالا در جریان است — برای کارت رهگیری داشبورد.
	 *
	 * @return WC_Order|null
	 */
	public static function active_order() {
		$statuses = array_merge( array( 'processing', 'on-hold' ), self::shipping_statuses() );
		$orders   = self::get_orders(
			array(
				'limit'  => 1,
				'status' => $statuses,
			)
		);

		return $orders ? $orders[0] : null;
	}

	/**
	 * سفارش در انتظار پرداخت.
	 *
	 * @return WC_Order|null
	 */
	public static function unpaid_order() {
		$orders = self::get_orders(
			array(
				'limit'  => 1,
				'status' => array( 'pending', 'failed' ),
			)
		);

		return $orders ? $orders[0] : null;
	}

	/**
	 * چهار مرحلهٔ رهگیری سفارش با تاریخ هرکدام.
	 *
	 * @param WC_Order $order
	 * @return array
	 */
	public static function order_steps( $order ) {
		$status  = $order->get_status();
		$created = $order->get_date_created();
		$paid    = $order->get_date_paid();
		$done    = 1;

		if ( in_array( $status, array( 'processing', 'on-hold' ), true ) ) {
			$done = 2;
		} elseif ( in_array( $status, self::shipping_statuses(), true ) ) {
			$done = 3;
		} elseif ( 'completed' === $status ) {
			$done = 4;
		}

		$modified = $order->get_date_modified();
		$labels   = array(
			array( 'ثبت سفارش', $created ),
			array( 'آماده‌سازی', $paid ? $paid : $created ),
			array( 'تحویل به پست', $modified ),
			array( 'تحویل به شما', $modified ),
		);

		$steps = array();

		foreach ( $labels as $index => $item ) {
			$step_done = $index < $done;

			$steps[] = array(
				'label' => $item[0],
				'time'  => $step_done && $item[1] ? Ardal_Jalali::format( $item[1]->getTimestamp(), 'no_year' ) : '',
				'done'  => $step_done,
			);
		}

		return $steps;
	}

	/**
	 * کد رهگیری مرسوله. کلید متا با فیلتر قابل تغییر است تا با افزونه‌های
	 * رهگیری مرسوله هم کار کند.
	 *
	 * @param WC_Order $order
	 * @return string
	 */
	public static function tracking_code( $order ) {
		$keys = (array) apply_filters(
			'ardal_account_tracking_meta_keys',
			array( '_ardal_tracking_code', '_tracking_number', 'tracking_code', '_postal_code_tracking' )
		);

		foreach ( $keys as $key ) {
			$value = $order->get_meta( $key );

			if ( is_string( $value ) && '' !== trim( $value ) ) {
				return Ardal_Helpers::fa_digits( trim( $value ) );
			}
		}

		return '';
	}

	/**
	 * خط زمانی سفارش از یادداشت‌های مشتری، جدیدترین بالا.
	 *
	 * @param WC_Order $order
	 * @return array
	 */
	public static function order_timeline( $order ) {
		$notes = wc_get_order_notes(
			array(
				'order_id' => $order->get_id(),
				'type'     => 'customer',
				'orderby'  => 'date_created',
				'order'    => 'DESC',
			)
		);

		$out = array();

		foreach ( $notes as $note ) {
			$stamp = strtotime( $note->date_created );

			$out[] = array(
				'title' => wp_trim_words( wp_strip_all_tags( $note->content ), 9, '…' ),
				'desc'  => wp_strip_all_tags( $note->content ),
				'time'  => Ardal_Jalali::format( $stamp, 'no_year' ) . ' ' . Ardal_Helpers::fa_digits( gmdate( 'H:i', $stamp ) ),
			);
		}

		// اگر فروشنده یادداشتی نگذاشته باشد، دست‌کم ثبت و پرداخت سفارش را نشان بده.
		if ( empty( $out ) ) {
			$paid = $order->get_date_paid();

			if ( $paid ) {
				$out[] = array(
					'title' => 'پرداخت موفق سفارش',
					'desc'  => sprintf(
						'پرداخت %s %s از طریق %s انجام شد.',
						Ardal_Helpers::price( $order->get_total() ),
						'تومان',
						$order->get_payment_method_title() ? $order->get_payment_method_title() : 'درگاه بانکی'
					),
					'time'  => Ardal_Jalali::format( $paid->getTimestamp(), 'no_year' ) . ' ' . Ardal_Helpers::fa_digits( gmdate( 'H:i', $paid->getTimestamp() ) ),
				);
			}

			$created = $order->get_date_created();

			if ( $created ) {
				$out[] = array(
					'title' => 'ثبت سفارش',
					'desc'  => 'سفارش شما ثبت شد و در صف بررسی قرار گرفت.',
					'time'  => Ardal_Jalali::format( $created->getTimestamp(), 'no_year' ) . ' ' . Ardal_Helpers::fa_digits( gmdate( 'H:i', $created->getTimestamp() ) ),
				);
			}
		}

		return $out;
	}

	/**
	 * رنگ‌بندی وضعیت سفارش مطابق دیزاین.
	 *
	 * @param string $status
	 * @return string کلید tone برای کلاس CSS.
	 */
	public static function status_tone( $status ) {
		if ( in_array( $status, self::shipping_statuses(), true ) || 'processing' === $status ) {
			return 'green';
		}

		if ( in_array( $status, array( 'pending', 'on-hold', 'failed' ), true ) ) {
			return 'amber';
		}

		if ( in_array( $status, array( 'refunded', 'cancelled' ), true ) ) {
			return 'red';
		}

		return 'grey';
	}

	/**
	 * دکمهٔ اصلی هر کارت سفارش.
	 *
	 * @param WC_Order $order
	 * @return array|null
	 */
	public static function order_action( $order ) {
		$status = $order->get_status();

		if ( in_array( $status, array( 'pending', 'failed' ), true ) ) {
			return array(
				'label' => 'پرداخت سفارش',
				'url'   => $order->get_checkout_payment_url(),
				'tone'  => 'amber',
			);
		}

		if ( in_array( $status, self::shipping_statuses(), true ) || 'processing' === $status ) {
			return array(
				'label' => 'رهگیری مرسوله',
				'url'   => $order->get_view_order_url(),
				'tone'  => 'green',
			);
		}

		if ( 'completed' === $status ) {
			return array(
				'label' => 'خرید دوباره',
				'url'   => self::reorder_url( $order ),
				'tone'  => 'soft',
			);
		}

		return array(
			'label' => 'جزئیات سفارش',
			'url'   => $order->get_view_order_url(),
			'tone'  => 'soft',
		);
	}

	/**
	 * لینک «خرید دوباره» — همهٔ کالاهای سفارش را دوباره به سبد می‌ریزد.
	 *
	 * @param WC_Order $order
	 * @return string
	 */
	public static function reorder_url( $order ) {
		return wp_nonce_url(
			add_query_arg( 'ardal-reorder', $order->get_id(), wc_get_cart_url() ),
			'ardal-reorder-' . $order->get_id()
		);
	}

	/**
	 * تب‌های صفحهٔ سفارش‌ها با تعداد هرکدام.
	 *
	 * @param string $current
	 * @return array
	 */
	public static function order_tabs( $current ) {
		$defs = array(
			'all'       => array( 'همه', array() ),
			'unpaid'    => array( 'در انتظار پرداخت', array( 'pending', 'failed' ) ),
			'shipping'  => array( 'در حال ارسال', array_merge( array( 'processing' ), self::shipping_statuses() ) ),
			'completed' => array( 'تحویل شده', array( 'completed' ) ),
			'returned'  => array( 'مرجوعی', array( 'refunded', 'cancelled' ) ),
		);

		$base = wc_get_account_endpoint_url( 'orders' );
		$out  = array();

		foreach ( $defs as $key => $def ) {
			$out[] = array(
				'key'    => $key,
				'label'  => $def[0],
				'count'  => Ardal_Helpers::fa_digits( self::count_orders( $def[1] ) ),
				'url'    => 'all' === $key ? $base : add_query_arg( 'status', $key, $base ),
				'active' => $key === $current,
			);
		}

		return $out;
	}

	/**
	 * @param array $statuses خالی یعنی همهٔ وضعیت‌های قابل نمایش.
	 * @return int
	 */
	public static function count_orders( $statuses = array() ) {
		// تب‌های صفحهٔ سفارش‌ها و بج‌های منو چند بار همین را می‌پرسند.
		static $cache = array();

		$key = md5( wp_json_encode( $statuses ) );

		if ( isset( $cache[ $key ] ) ) {
			return $cache[ $key ];
		}

		$args = array(
			'limit'  => -1,
			'return' => 'ids',
		);

		if ( ! empty( $statuses ) ) {
			$args['status'] = $statuses;
		} else {
			$args['status'] = array_keys( wc_get_order_statuses() );
		}

		$cache[ $key ] = count( self::get_orders( $args ) );

		return $cache[ $key ];
	}

	/**
	 * وضعیت‌های متناظر با تب انتخاب‌شده.
	 *
	 * @param string $tab
	 * @return array
	 */
	public static function tab_statuses( $tab ) {
		$map = array(
			'unpaid'    => array( 'pending', 'failed' ),
			'shipping'  => array_merge( array( 'processing' ), self::shipping_statuses() ),
			'completed' => array( 'completed' ),
			'returned'  => array( 'refunded', 'cancelled' ),
		);

		return isset( $map[ $tab ] ) ? $map[ $tab ] : array_keys( wc_get_order_statuses() );
	}

	/* ============================================================
	   کد تخفیف اختصاصی
	   ============================================================ */

	/**
	 * کوپن مخصوص همین کاربر (کوپنی که ایمیلش روی آن محدود شده).
	 *
	 * @return array|null
	 */
	public static function personal_coupon() {
		$user = wp_get_current_user();

		if ( ! $user || ! $user->user_email ) {
			return null;
		}

		$ids = get_posts(
			array(
				'post_type'      => 'shop_coupon',
				'post_status'    => 'publish',
				'posts_per_page' => 5,
				'fields'         => 'ids',
				'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'     => 'customer_email',
						'value'   => $user->user_email,
						'compare' => 'LIKE',
					),
				),
			)
		);

		foreach ( $ids as $id ) {
			$coupon = new WC_Coupon( $id );

			if ( ! $coupon->get_id() || $coupon->get_date_expires() && $coupon->get_date_expires()->getTimestamp() < time() ) {
				continue;
			}

			$expires = $coupon->get_date_expires();
			$amount  = $coupon->get_amount();
			$limit   = $coupon->get_maximum_amount();

			$desc = 'percent' === $coupon->get_discount_type()
				? sprintf( '%s٪ تخفیف', Ardal_Helpers::fa_digits( (int) $amount ) )
				: sprintf( '%s تومان تخفیف', Ardal_Helpers::price( $amount ) );

			if ( $limit > 0 && 'percent' === $coupon->get_discount_type() ) {
				$desc .= sprintf( ' تا سقف %s تومان', Ardal_Helpers::price( $limit ) );
			}

			return array(
				'code'    => strtoupper( $coupon->get_code() ),
				'desc'    => $desc,
				'expires' => $expires ? 'اعتبار تا ' . Ardal_Jalali::format( $expires->getTimestamp() ) : '',
			);
		}

		return null;
	}

	/* ============================================================
	   پیشنهاد بر اساس خرید
	   ============================================================ */

	/**
	 * محصولات پیشنهادی از دسته‌های خریدهای قبلی.
	 *
	 * @param int $limit
	 * @return array آرایه‌ای از WC_Product.
	 */
	public static function suggestions( $limit = 4 ) {
		$orders     = self::get_orders( array( 'limit' => 5 ) );
		$categories = array();
		$bought     = array();

		foreach ( $orders as $order ) {
			foreach ( $order->get_items() as $item ) {
				$product_id = $item->get_product_id();

				if ( ! $product_id ) {
					continue;
				}

				$bought[] = $product_id;
				$terms    = wc_get_product_term_ids( $product_id, 'product_cat' );

				if ( $terms ) {
					$categories = array_merge( $categories, $terms );
				}
			}
		}

		$args = array(
			'status'  => 'publish',
			'limit'   => $limit,
			'exclude' => array_unique( $bought ),
			'orderby' => 'popularity',
		);

		if ( ! empty( $categories ) ) {
			$args['category'] = array();

			foreach ( array_unique( $categories ) as $term_id ) {
				$term = get_term( $term_id, 'product_cat' );

				if ( $term && ! is_wp_error( $term ) ) {
					$args['category'][] = $term->slug;
				}
			}
		}

		$products = wc_get_products( $args );

		// اگر تاریخچهٔ خرید کافی نبود، پرفروش‌ها را نشان بده.
		if ( count( $products ) < $limit ) {
			$products = wc_get_products(
				array(
					'status'  => 'publish',
					'limit'   => $limit,
					'orderby' => 'popularity',
				)
			);
		}

		return $products;
	}

	/* ============================================================
	   علاقه‌مندی‌ها
	   ============================================================ */

	/**
	 * شناسهٔ محصولات علاقه‌مندی کاربر.
	 *
	 * @return array
	 */
	public static function wishlist_ids() {
		$ids = get_user_meta( get_current_user_id(), self::WISHLIST_META, true );

		return is_array( $ids ) ? array_values( array_unique( array_map( 'absint', $ids ) ) ) : array();
	}

	/**
	 * @param array $ids
	 */
	public static function save_wishlist( $ids ) {
		$ids = array_values( array_unique( array_filter( array_map( 'absint', (array) $ids ) ) ) );

		update_user_meta( get_current_user_id(), self::WISHLIST_META, $ids );
	}

	/**
	 * محصولات علاقه‌مندی که هنوز منتشرند.
	 *
	 * @return array
	 */
	public static function wishlist_products() {
		$out = array();

		foreach ( self::wishlist_ids() as $id ) {
			$product = wc_get_product( $id );

			if ( $product && 'publish' === $product->get_status() ) {
				$out[] = $product;
			}
		}

		return $out;
	}

	/**
	 * افزودن، حذف و همگام‌سازی علاقه‌مندی‌ها.
	 *
	 * فهرست سمت مرورگر (localStorage) که ویجت‌های محصول می‌نویسند با فهرست
	 * سرور ادغام می‌شود تا کاربرانی که قبلاً چیزی ذخیره کرده‌اند آن را از دست
	 * ندهند.
	 */
	public static function ajax_wishlist() {
		check_ajax_referer( 'ardal-account', 'nonce' );

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => 'برای ذخیرهٔ علاقه‌مندی‌ها وارد حساب شوید.' ), 403 );
		}

		$action  = isset( $_POST['op'] ) ? sanitize_key( wp_unslash( $_POST['op'] ) ) : 'sync';
		$current = self::wishlist_ids();

		if ( 'add' === $action || 'remove' === $action ) {
			$id = isset( $_POST['product'] ) ? absint( $_POST['product'] ) : 0;

			if ( ! $id ) {
				wp_send_json_error( array( 'message' => 'شناسهٔ محصول نامعتبر است.' ), 400 );
			}

			$current = 'add' === $action
				? array_merge( $current, array( $id ) )
				: array_diff( $current, array( $id ) );
		} else {
			$incoming = isset( $_POST['ids'] ) ? (array) wp_unslash( $_POST['ids'] ) : array();
			$incoming = array_map( 'absint', $incoming );
			$current  = array_merge( $current, array_filter( $incoming ) );
		}

		self::save_wishlist( $current );

		$ids = self::wishlist_ids();

		wp_send_json_success(
			array(
				'ids'   => $ids,
				'count' => count( $ids ),
			)
		);
	}

	/* ============================================================
	   نظرات
	   ============================================================ */

	/**
	 * دیدگاه‌های کاربر روی محصولات.
	 *
	 * @return array آرایه‌ای از WP_Comment.
	 */
	public static function reviews() {
		static $cache = null;

		if ( null !== $cache ) {
			return $cache;
		}

		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			$cache = array();

			return $cache;
		}

		$cache = get_comments(
			array(
				'user_id'     => $user_id,
				'post_type'   => 'product',
				'status'      => 'all',
				'orderby'     => 'comment_date_gmt',
				'order'       => 'DESC',
				'number'      => 30,
				'hierarchical' => false,
			)
		);

		return $cache;
	}

	/**
	 * شمار دیدگاه‌های منتشرشده، در انتظار تایید و میانگین امتیاز.
	 *
	 * @return array
	 */
	public static function review_stats() {
		$approved = 0;
		$pending  = 0;
		$sum      = 0;
		$rated    = 0;

		foreach ( self::reviews() as $review ) {
			if ( '1' === (string) $review->comment_approved ) {
				$approved++;
			} else {
				$pending++;
			}

			$rating = (int) get_comment_meta( $review->comment_ID, 'rating', true );

			if ( $rating > 0 ) {
				$sum += $rating;
				$rated++;
			}
		}

		return array(
			'approved' => $approved,
			'pending'  => $pending,
			'average'  => $rated > 0 ? number_format( $sum / $rated, 1, '٫', '' ) : '—',
		);
	}

	/**
	 * کالایی که تحویل داده شده ولی کاربر هنوز دربارهٔ آن دیدگاه ننوشته.
	 *
	 * @return array|null
	 */
	public static function awaiting_review() {
		$orders   = self::get_orders(
			array(
				'limit'  => 5,
				'status' => array( 'completed' ),
			)
		);
		$reviewed = array();

		foreach ( self::reviews() as $review ) {
			$reviewed[] = (int) $review->comment_post_ID;
		}

		foreach ( $orders as $order ) {
			$date = $order->get_date_completed();

			foreach ( $order->get_items() as $item ) {
				$product_id = $item->get_product_id();

				if ( ! $product_id || in_array( $product_id, $reviewed, true ) ) {
					continue;
				}

				$product = wc_get_product( $product_id );

				if ( ! $product ) {
					continue;
				}

				$days = $date ? (int) floor( ( time() - $date->getTimestamp() ) / DAY_IN_SECONDS ) : 0;

				return array(
					'product' => $product,
					'note'    => $days > 0
						? sprintf( '%s روز از تحویل گذشته — تجربه‌ات را برای دیگران بنویس', Ardal_Helpers::fa_digits( $days ) )
						: 'تجربه‌ات از این کالا را برای دیگران بنویس',
				);
			}
		}

		return null;
	}

	/* ============================================================
	   ابزارهای نمایش
	   ============================================================ */

	/**
	 * تصویر بندانگشتی محصول یا جای‌گاه خالی.
	 *
	 * @param WC_Product|null $product
	 * @param string          $size
	 * @return string
	 */
	public static function thumb( $product, $size = 'woocommerce_thumbnail' ) {
		if ( $product && $product->get_image_id() ) {
			return wp_get_attachment_image( $product->get_image_id(), $size, false, array( 'loading' => 'lazy', 'alt' => '' ) );
		}

		return '<span class="ard-ac__thumb-empty">تصویر</span>';
	}

	/**
	 * خلاصهٔ کالاهای یک سفارش برای عنوان کارت.
	 *
	 * @param WC_Order $order
	 * @return string
	 */
	public static function order_title( $order ) {
		$items = $order->get_items();

		if ( empty( $items ) ) {
			return 'بدون کالا';
		}

		$first = reset( $items );
		$title = $first->get_name();
		$rest  = count( $items ) - 1;

		return $rest > 0
			? sprintf( '%s و %s کالای دیگر', $title, Ardal_Helpers::fa_digits( $rest ) )
			: $title;
	}

	/**
	 * تعداد کل اقلام سفارش.
	 *
	 * @param WC_Order $order
	 * @return int
	 */
	public static function order_item_count( $order ) {
		$count = 0;

		foreach ( $order->get_items() as $item ) {
			$count += (int) $item->get_quantity();
		}

		return $count;
	}
}
