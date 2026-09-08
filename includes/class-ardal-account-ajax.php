<?php
/**
 * بارگذاری ایجکسی صفحه‌های پنل کاربری.
 *
 * هیچ‌جای این فایل نام اندپوینت‌ها هاردکد نشده است. مسیر درخواستی به همان
 * شکلی که خود ووکامرس مسیر واقعی را می‌خواند تبدیل به query var می‌شود و بعد
 * `woocommerce_account_content` صدا زده می‌شود؛ یعنی هر اندپوینتی که افزونه‌ای
 * ثبت کند — چه از راه فیلتر `woocommerce_get_query_vars` و چه فقط با اکشن
 * `woocommerce_account_{endpoint}_endpoint` — بدون تغییر کد اینجا ایجکسی
 * بارگذاری می‌شود.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Account_Ajax {

	const ACTION = 'ardal_account_page';

	public static function init() {
		add_action( 'wp_ajax_' . self::ACTION, array( __CLASS__, 'handle' ) );
	}

	/* ============================================================
	   تبدیل نشانی به اندپوینت
	   ============================================================ */

	/**
	 * نگاشت «اسلاگ داخل نشانی» به «کلید اندپوینت».
	 *
	 * ووکامرس اجازه می‌دهد اسلاگ هر اندپوینت در تنظیمات عوض شود (مثلاً
	 * `orders` به `سفارش‌ها`)، ولی اکشن رندر همیشه با کلید داخلی ساخته می‌شود.
	 *
	 * @return array slug => key
	 */
	private static function endpoint_map() {
		$map = array();

		if ( ! WC()->query ) {
			return $map;
		}

		foreach ( WC()->query->get_query_vars() as $key => $slug ) {
			if ( '' !== $slug ) {
				$map[ $slug ] = $key;
			}
		}

		return $map;
	}

	/**
	 * نشانی را به اندپوینت و مقدارش تبدیل می‌کند.
	 *
	 * @param string $url
	 * @return array|null آرایهٔ key/slug/value/query یا null اگر نشانی مال پنل نباشد.
	 */
	public static function resolve( $url ) {
		$base = wc_get_page_permalink( 'myaccount' );

		if ( ! $url || ! $base ) {
			return null;
		}

		$base_parts = wp_parse_url( $base );
		$url_parts  = wp_parse_url( $url );

		if ( ! $url_parts || ! $base_parts ) {
			return null;
		}

		// نشانی باید روی همین سایت و زیر صفحهٔ حساب کاربری باشد.
		if ( ! empty( $url_parts['host'] ) && ! empty( $base_parts['host'] ) && $url_parts['host'] !== $base_parts['host'] ) {
			return null;
		}

		$base_path = isset( $base_parts['path'] ) ? $base_parts['path'] : '/';
		$url_path  = isset( $url_parts['path'] ) ? $url_parts['path'] : '/';

		if ( 0 !== strpos( $url_path, $base_path ) ) {
			return null;
		}

		$query = array();

		if ( ! empty( $url_parts['query'] ) ) {
			parse_str( $url_parts['query'], $query );

			// این‌ها به $_GET می‌روند، پس همین‌جا پاک‌سازی می‌شوند.
			foreach ( $query as $key => $value ) {
				$query[ $key ] = is_scalar( $value ) ? sanitize_text_field( (string) $value ) : '';
			}
		}

		$map  = self::endpoint_map();
		$rest = trim( substr( $url_path, strlen( $base_path ) ), '/' );

		$slug  = '';
		$value = '';

		if ( '' !== $rest ) {
			$parts = explode( '/', $rest );
			$slug  = array_shift( $parts );
			$value = $parts ? implode( '/', $parts ) : '';
		} else {
			// بدون پیوند یکتا، اندپوینت به شکل پارامتر پرس‌وجو می‌آید.
			foreach ( array_keys( $map ) as $candidate ) {
				if ( isset( $query[ $candidate ] ) ) {
					$slug  = $candidate;
					$value = (string) $query[ $candidate ];
					break;
				}
			}
		}

		$slug  = sanitize_title( rawurldecode( $slug ) );
		$value = sanitize_text_field( rawurldecode( $value ) );

		if ( '' === $slug ) {
			// داشبورد.
			return array(
				'key'   => '',
				'slug'  => '',
				'value' => '',
				'query' => $query,
			);
		}

		$key = isset( $map[ $slug ] ) ? $map[ $slug ] : '';

		// اندپوینتی که در نگاشت نیست ولی اکشن رندر دارد هم پذیرفته می‌شود؛
		// بعضی افزونه‌ها اندپوینت را بدون فیلتر query vars ثبت می‌کنند.
		if ( '' === $key && has_action( 'woocommerce_account_' . $slug . '_endpoint' ) ) {
			$key = $slug;
		}

		if ( '' === $key ) {
			return null;
		}

		return array(
			'key'   => $key,
			'slug'  => $slug,
			'value' => $value,
			'query' => $query,
		);
	}

	/* ============================================================
	   آماده‌سازی و بازگرداندن وضعیت درخواست
	   ============================================================ */

	/**
	 * وانمود می‌کند درخواست، همان صفحهٔ حساب کاربری است تا شرط‌هایی مثل
	 * is_account_page() و is_wc_endpoint_url() درست جواب بدهند.
	 *
	 * @param array $route
	 * @return array وضعیت قبلی برای بازگرداندن.
	 */
	private static function setup_query( $route ) {
		global $wp, $wp_query, $post;

		$previous = array(
			'query_vars' => $wp->query_vars,
			'wp_query'   => $wp_query,
			'post'       => $post,
			'get'        => $_GET, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		);

		$wp->query_vars = array();

		if ( '' !== $route['key'] ) {
			$wp->query_vars[ $route['key'] ] = $route['value'];

			// بعضی کدها اسلاگ را می‌خوانند، نه کلید را.
			if ( $route['slug'] !== $route['key'] ) {
				$wp->query_vars[ $route['slug'] ] = $route['value'];
			}
		}

		// پارامترهای پرس‌وجوی نشانی (مثل status یا صفحه‌بندی) هم باید در دسترس
		// باشند؛ تمپلیت‌ها و افزونه‌ها آن‌ها را از $_GET می‌خوانند.
		$_GET = array_merge( $_GET, $route['query'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$page_id = wc_get_page_id( 'myaccount' );
		$page    = $page_id > 0 ? get_post( $page_id ) : null;

		if ( $page ) {
			$fake                       = new WP_Query();
			$fake->is_page              = true;
			$fake->is_singular          = true;
			$fake->queried_object       = $page;
			$fake->queried_object_id    = $page->ID;
			$fake->post                 = $page;
			$fake->posts                = array( $page );
			$fake->post_count           = 1;
			$fake->found_posts          = 1;
			$fake->query_vars['page_id'] = $page->ID;

			$wp_query = $fake; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			$post     = $page; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}

		return $previous;
	}

	/**
	 * @param array $previous
	 */
	private static function restore_query( $previous ) {
		global $wp, $wp_query, $post;

		$wp->query_vars = $previous['query_vars'];
		$wp_query       = $previous['wp_query']; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$post           = $previous['post']; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$_GET           = $previous['get']; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}

	/* ============================================================
	   فایل‌های تازه‌ای که اندپوینت لازم دارد
	   ============================================================ */

	/**
	 * تگ استایل‌ها و اسکریپت‌هایی که موقع رندر این اندپوینت صف شده‌اند.
	 *
	 * ساخت تگ‌ها را به خود وردپرس می‌سپاریم تا نسخه، وابستگی‌ها و داده‌های
	 * inline (چیزی که wp_localize_script می‌سازد) هم درست بیایند. سمت مرورگر،
	 * هر هندلی که از قبل در صفحه باشد رد می‌شود.
	 *
	 * @param array $styles_before
	 * @param array $scripts_before
	 * @return array
	 */
	private static function new_assets( $styles_before, $scripts_before ) {
		$styles  = array_values( array_diff( wp_styles()->queue, $styles_before ) );
		$scripts = array_values( array_diff( wp_scripts()->queue, $scripts_before ) );

		$out = array(
			'styles'  => '',
			'scripts' => '',
		);

		if ( $styles ) {
			ob_start();
			wp_styles()->do_items( $styles );
			$out['styles'] = ob_get_clean();
		}

		if ( $scripts ) {
			ob_start();
			wp_scripts()->do_items( $scripts );
			$out['scripts'] = ob_get_clean();
		}

		return $out;
	}

	/* ============================================================
	   پاسخ
	   ============================================================ */

	/**
	 * عنوان صفحه برای تب مرورگر.
	 *
	 * @param array $route
	 * @return string
	 */
	private static function title( $route ) {
		if ( '' === $route['key'] ) {
			return 'پنل کاربری';
		}

		$title = WC()->query ? WC()->query->get_endpoint_title( $route['key'] ) : '';

		if ( '' !== $title ) {
			return $title;
		}

		// اندپوینت‌های بدون عنوان: از نام همان آیتم در منو استفاده می‌کنیم.
		$items = wc_get_account_menu_items();

		return isset( $items[ $route['key'] ] ) ? $items[ $route['key'] ] : 'پنل کاربری';
	}

	/**
	 * کلید آیتم‌هایی از منو که برای این صفحه فعال حساب می‌شوند.
	 *
	 * تشخیص را به خود ووکامرس می‌سپاریم (همان تابعی که تمپلیت ناوبری هم از آن
	 * استفاده می‌کند)، پس «جزئیات سفارش» زیر «سفارش‌های من» فعال می‌ماند و
	 * آیتم افزونه‌ها هم بدون کد اضافه درست کار می‌کنند. این تابع باید وقتی صدا
	 * زده شود که query var های صفحه ساخته شده‌اند.
	 *
	 * @return array
	 */
	private static function active_items() {
		$active = array();

		foreach ( array_keys( wc_get_account_menu_items() ) as $key ) {
			if ( false !== strpos( wc_get_account_menu_item_classes( $key ), 'is-active' ) ) {
				$active[] = $key;
			}
		}

		return $active;
	}

	/**
	 * عددهای کنار آیتم‌های منو، تا بعد از هر بارگذاری تازه شوند.
	 *
	 * @return array
	 */
	private static function badges() {
		$reviews = Ardal_Account::review_stats();

		return array(
			'orders'   => Ardal_Account::count_orders(
				array_merge( array( 'pending', 'processing' ), Ardal_Account::shipping_statuses() )
			),
			'wishlist' => count( Ardal_Account::wishlist_ids() ),
			'reviews'  => (int) $reviews['pending'],
		);
	}

	public static function handle() {
		check_ajax_referer( 'ardal-account', 'nonce' );

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'reload' => true ), 403 );
		}

		$url   = isset( $_POST['url'] ) ? esc_url_raw( wp_unslash( $_POST['url'] ) ) : '';
		$route = self::resolve( $url );

		if ( null === $route ) {
			// نشانی مال پنل نیست یا اندپوینتش را نمی‌شناسیم: مرورگر خودش برود.
			wp_send_json_error( array( 'reload' => true ), 400 );
		}

		$styles_before  = wp_styles()->queue;
		$scripts_before = wp_scripts()->queue;

		$previous = self::setup_query( $route );

		ob_start();

		if ( function_exists( 'wc_print_notices' ) ) {
			wc_print_notices();
		}

		do_action( 'woocommerce_account_content' );

		$html   = ob_get_clean();
		$title  = self::title( $route );
		$active = self::active_items();
		$assets = self::new_assets( $styles_before, $scripts_before );
		$badges = self::badges();

		self::restore_query( $previous );

		$fa = array();

		foreach ( $badges as $key => $count ) {
			$fa[ $key ] = $count > 0 ? Ardal_Helpers::fa_digits( $count ) : '';
		}

		wp_send_json_success(
			array(
				'html'     => $html,
				'title'    => $title,
				'url'      => $url,
				'endpoint' => $route['key'],
				'active'   => $active,
				'badges'   => $fa,
				'assets'   => $assets,
			)
		);
	}
}
