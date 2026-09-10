<?php
/**
 * تنظیمات پنل کاربری — منوی پنل، بخش‌های داشبورد و علاقه‌مندی‌ها.
 *
 * جدا از پالت رنگی نگه داشته شده چون جنسش فرق دارد: پالت یک فهرست تخت از
 * رنگ‌هاست و اینجا ساختار دارد (هر آیتم منو سه فیلد: نمایش، عنوان، ترتیب).
 * هر دو در یک فرم و با یک دکمهٔ ذخیره ثبت می‌شوند.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Account_Settings {

	const OPTION = 'ardal_account';

	/**
	 * آیتم‌های منوی پنل: اسلاگ => برچسب پیش‌فرض و ترتیب پیش‌فرض.
	 *
	 * اسلاگ‌ها همان اندپوینت‌های ووکامرس‌اند؛ دو تای اول مال خود اردال است.
	 *
	 * @return array
	 */
	public static function menu_defaults() {
		return array(
			'dashboard'        => array( 'label' => 'پیشخوان', 'order' => 10, 'on' => '1' ),
			'orders'           => array( 'label' => 'سفارش‌های من', 'order' => 20, 'on' => '1' ),
			'wishlist'         => array( 'label' => 'علاقه‌مندی‌ها', 'order' => 30, 'on' => '1' ),
			'edit-address'     => array( 'label' => 'آدرس‌های من', 'order' => 40, 'on' => '1' ),
			'edit-account'     => array( 'label' => 'اطلاعات حساب', 'order' => 50, 'on' => '1' ),
			'reviews'          => array( 'label' => 'نظرات من', 'order' => 60, 'on' => '1' ),
			/*
			 * این دو پیش‌فرض روشن‌اند چون پیش از این تنظیمات هم — هر وقت
			 * ووکامرس می‌ساختشان — در منو بودند؛ خاموش‌کردنشان با به‌روزرسانی،
			 * حذفِ ناخواستهٔ دو صفحه از پنلِ سایت‌های موجود بود.
			 */
			'downloads'        => array( 'label' => 'دانلودها', 'order' => 70, 'on' => '1' ),
			'payment-methods'  => array( 'label' => 'روش‌های پرداخت', 'order' => 80, 'on' => '1' ),
		);
	}

	/**
	 * بخش‌های صفحهٔ پیشخوان: کلید => برچسب و توضیح.
	 *
	 * @return array
	 */
	public static function dashboard_sections() {
		return array(
			'welcome' => array( 'label' => 'کارت خوش‌آمد', 'desc' => 'نام کاربر، خلاصهٔ وضعیت و دکمه‌های میان‌بر' ),
			'stats'   => array( 'label' => 'کارت‌های آمار', 'desc' => 'تعداد سفارش، مبلغ خرید، علاقه‌مندی و نظرات' ),
			'alert'   => array( 'label' => 'نوار هشدار', 'desc' => 'یادآوری تکمیل اطلاعات حساب' ),
			'track'   => array( 'label' => 'پیگیری سفارش جاری', 'desc' => 'مراحل آماده‌سازی و ارسال آخرین سفارش' ),
			'chart'   => array( 'label' => 'نمودار خرید ۶ ماه', 'desc' => 'میله‌های مجموع خرید ماه‌های گذشته' ),
			'coupon'  => array( 'label' => 'کد تخفیف اختصاصی', 'desc' => 'کارت کد تخفیف با دکمهٔ کپی' ),
			'suggest' => array( 'label' => 'پیشنهاد بر اساس خرید', 'desc' => 'محصولاتی از دسته‌هایی که کاربر بیشتر خریده' ),
		);
	}

	/**
	 * فیلدهای ساده (غیر از منو و بخش‌ها).
	 *
	 * @return array
	 */
	public static function schema() {
		return array(
			'suggest_count'     => array(
				'label'   => 'تعداد پیشنهاد در پیشخوان',
				'type'    => 'number',
				'default' => '4',
				'desc'    => 'چند محصول در کارت «بر اساس خریدهای تو» نمایش داده شود.',
				'group'   => 'dashboard',
			),
			'orders_per_page'   => array(
				'label'   => 'تعداد سفارش در هر صفحه',
				'type'    => 'number',
				'default' => '10',
				'desc'    => 'در صفحهٔ «سفارش‌های من».',
				'group'   => 'dashboard',
			),
			'wishlist_title'    => array(
				'label'   => 'عنوان صفحه علاقه‌مندی‌ها',
				'type'    => 'text',
				'default' => 'علاقه‌مندی‌ها',
				'desc'    => 'هم عنوان بالای صفحه و هم نام آیتم منو از همین‌جا می‌آید.',
				'group'   => 'wishlist',
			),
			'wishlist_columns'  => array(
				'label'   => 'تعداد ستون شبکه علاقه‌مندی',
				'type'    => 'number',
				'default' => '4',
				'desc'    => 'در موبایل خودکار کمتر می‌شود.',
				'group'   => 'wishlist',
			),
			'wishlist_add_all'  => array(
				'label'   => 'دکمه «افزودن همه به سبد»',
				'type'    => 'checkbox',
				'default' => '1',
				'desc'    => 'وقتی دست‌کم یک کالای موجود در فهرست باشد نمایش داده می‌شود.',
				'group'   => 'wishlist',
			),
			'wishlist_empty_title' => array(
				'label'   => 'عنوان حالت خالی',
				'type'    => 'text',
				'default' => 'هنوز کالایی ذخیره نکرده‌ای',
				'desc'    => '',
				'group'   => 'wishlist',
			),
			'wishlist_empty_text'  => array(
				'label'   => 'توضیح حالت خالی',
				'type'    => 'text',
				'default' => 'با زدن آیکون قلب روی هر محصول، آن را اینجا نگه دار تا بعداً سراغش بیایی.',
				'desc'    => '',
				'group'   => 'wishlist',
			),
			'wishlist_empty_btn'   => array(
				'label'   => 'متن دکمه حالت خالی',
				'type'    => 'text',
				'default' => 'دیدن محصول‌ها',
				'desc'    => 'خالی بگذارید تا دکمه حذف شود.',
				'group'   => 'wishlist',
			),
		);
	}

	/**
	 * عنوان کارت‌های تب.
	 *
	 * @return array
	 */
	public static function groups() {
		return array(
			'dashboard' => 'پیشخوان',
			'wishlist'  => 'علاقه‌مندی‌ها',
		);
	}

	/* --------------------------------------------------------------------- */
	/* خواندن                                                                 */
	/* --------------------------------------------------------------------- */

	/**
	 * مقادیر پیش‌فرض کامل.
	 *
	 * @return array
	 */
	public static function defaults() {
		$out = array();

		foreach ( self::menu_defaults() as $slug => $item ) {
			$out[ 'menu_' . $slug ]           = $item['on'];
			$out[ 'menu_' . $slug . '_label' ] = $item['label'];
			$out[ 'menu_' . $slug . '_order' ] = (string) $item['order'];
		}

		foreach ( array_keys( self::dashboard_sections() ) as $key ) {
			$out[ 'dash_' . $key ] = '1';
		}

		foreach ( self::schema() as $key => $field ) {
			$out[ $key ] = $field['default'];
		}

		return $out;
	}

	/**
	 * مقادیر ذخیره‌شده به‌علاوهٔ پیش‌فرض‌ها.
	 *
	 * @return array
	 */
	public static function get_all() {
		// بدون کش استاتیک: get_option خودش در حافظه کش می‌شود و کشِ دوم فقط
		// باعث می‌شد مقدارِ تازه‌ذخیره‌شده در همان درخواست دیده نشود.
		$saved    = get_option( self::OPTION, array() );
		$saved    = is_array( $saved ) ? $saved : array();
		$defaults = self::defaults();

		return array_merge( $defaults, array_intersect_key( $saved, $defaults ) );
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public static function get( $key ) {
		$all = self::get_all();

		return isset( $all[ $key ] ) ? (string) $all[ $key ] : '';
	}

	/**
	 * آیا یک بخش پیشخوان روشن است.
	 *
	 * @param string $key
	 * @return bool
	 */
	public static function section_on( $key ) {
		return '1' === self::get( 'dash_' . $key );
	}

	/**
	 * آیتم‌های منوی پنل، مرتب‌شده و فقط آن‌هایی که روشن‌اند.
	 *
	 * @return array اسلاگ => عنوان.
	 */
	public static function menu() {
		$all  = self::get_all();
		$rows = array();

		foreach ( array_keys( self::menu_defaults() ) as $slug ) {
			if ( '1' !== ( $all[ 'menu_' . $slug ] ?? '0' ) ) {
				continue;
			}

			$label = trim( (string) ( $all[ 'menu_' . $slug . '_label' ] ?? '' ) );

			$rows[] = array(
				'slug'  => $slug,
				'label' => '' !== $label ? $label : self::menu_defaults()[ $slug ]['label'],
				'order' => (int) ( $all[ 'menu_' . $slug . '_order' ] ?? 0 ),
			);
		}

		// ترتیب برابر که شد، ترتیب تعریف حفظ می‌شود تا جابه‌جایی تصادفی نباشد.
		usort(
			$rows,
			static function ( $a, $b ) {
				return $a['order'] === $b['order'] ? 0 : ( $a['order'] < $b['order'] ? -1 : 1 );
			}
		);

		$out = array();

		foreach ( $rows as $row ) {
			$out[ $row['slug'] ] = $row['label'];
		}

		return $out;
	}

	/**
	 * آیا این آیتم منو روشن است.
	 *
	 * @param string $slug
	 * @return bool
	 */
	public static function menu_on( $slug ) {
		return '1' === self::get( 'menu_' . $slug );
	}

	/* --------------------------------------------------------------------- */
	/* ذخیره                                                                  */
	/* --------------------------------------------------------------------- */

	/**
	 * پاک‌سازی ورودی فرم.
	 *
	 * @param mixed $input
	 * @return array
	 */
	public static function sanitize( $input ) {
		$input    = is_array( $input ) ? $input : array();
		$defaults = self::defaults();
		$clean    = array();

		foreach ( self::menu_defaults() as $slug => $item ) {
			$clean[ 'menu_' . $slug ] = empty( $input[ 'menu_' . $slug ] ) ? '0' : '1';

			$label = isset( $input[ 'menu_' . $slug . '_label' ] )
				? sanitize_text_field( $input[ 'menu_' . $slug . '_label' ] )
				: '';

			$clean[ 'menu_' . $slug . '_label' ] = '' !== $label ? $label : $item['label'];

			$order = isset( $input[ 'menu_' . $slug . '_order' ] ) ? trim( (string) $input[ 'menu_' . $slug . '_order' ] ) : '';

			$clean[ 'menu_' . $slug . '_order' ] = ( '' === $order || ! is_numeric( $order ) )
				? (string) $item['order']
				: (string) absint( $order );
		}

		foreach ( array_keys( self::dashboard_sections() ) as $key ) {
			$clean[ 'dash_' . $key ] = empty( $input[ 'dash_' . $key ] ) ? '0' : '1';
		}

		foreach ( self::schema() as $key => $field ) {
			switch ( $field['type'] ) {
				case 'checkbox':
					$clean[ $key ] = empty( $input[ $key ] ) ? '0' : '1';
					break;

				case 'number':
					$raw = isset( $input[ $key ] ) ? trim( (string) $input[ $key ] ) : '';

					$clean[ $key ] = ( '' === $raw || ! is_numeric( $raw ) || absint( $raw ) < 1 )
						? $defaults[ $key ]
						: (string) absint( $raw );
					break;

				default:
					$value = isset( $input[ $key ] ) ? sanitize_text_field( $input[ $key ] ) : '';

					// متن خالی برای دکمه معنا دارد («دکمه را نشان نده»)، برای بقیه نه.
					$clean[ $key ] = ( '' === $value && 'wishlist_empty_btn' !== $key )
						? $defaults[ $key ]
						: $value;
			}
		}

		return $clean;
	}
}
