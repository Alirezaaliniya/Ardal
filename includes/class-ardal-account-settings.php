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
	 * مقادیری که فقط برای همین درخواست روی تنظیمات سایت می‌نشینند.
	 *
	 * ویجت المنتور پیش از چاپ پنل پرش می‌کند و بعدش پاکش می‌کند، تا یک صفحه
	 * بتواند متن و آیکون خودش را داشته باشد بدون این‌که تنظیمات سایت عوض شود.
	 * کلید نبودن یعنی «همان تنظیمات سایت».
	 *
	 * @var array
	 */
	private static $overrides = array();

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
			'wallet'           => array( 'label' => 'کیف پول', 'order' => 35, 'on' => '1' ),
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
	 * متن‌ها و آیکون‌های باکس‌های پیشخوان.
	 *
	 * هر ردیف به یک بخش (کلید dashboard_sections) وصل است تا هم در صفحهٔ
	 * تنظیمات و هم در ویجت المنتور زیر همان باکس گروه شود.
	 *
	 * `tokens` جای‌گذارهای متن‌اند: کاربر جمله را هرجور خواست می‌چیند و موقع
	 * چاپ مقدار واقعی جایشان می‌نشیند؛ این‌طوری ترتیب پارامترها در کد گیر
	 * نمی‌افتد.
	 *
	 * `blank => true` یعنی خالی گذاشتن معنا دارد (معمولاً «این را نشان نده»)،
	 * پس با پیش‌فرض پر نمی‌شود.
	 *
	 * @return array
	 */
	public static function dashboard_texts() {
		return array(

			/* --------------------------------------- کارت خوش‌آمد */

			'welcome_title'      => array(
				'label'   => 'عنوان خوش‌آمد',
				'type'    => 'text',
				'default' => 'سلام {name}، خوش برگشتی',
				'tokens'  => '{name}',
				'section' => 'welcome',
			),
			'welcome_sub'        => array(
				'label'   => 'توضیح، وقتی خبری نیست',
				'type'    => 'textarea',
				'default' => 'اینجا خلاصهٔ سفارش‌ها، علاقه‌مندی‌ها و اطلاعات حسابت را می‌بینی.',
				'desc'    => 'وقتی نه سفارشی در جریان است و نه علاقه‌مندی موجودی.',
				'section' => 'welcome',
			),
			'welcome_line_ship'  => array(
				'label'   => 'جملهٔ «سفارش در حال ارسال»',
				'type'    => 'text',
				'default' => 'یک سفارش در حال ارسال داری',
				'desc'    => 'این جمله‌ها با «، » به هم می‌چسبند و توضیح خوش‌آمد را می‌سازند.',
				'section' => 'welcome',
			),
			'welcome_line_pay'   => array(
				'label'   => 'جملهٔ «در انتظار پرداخت»',
				'type'    => 'text',
				'default' => 'یک سفارش منتظر پرداخت است',
				'section' => 'welcome',
			),
			'welcome_line_wish'  => array(
				'label'   => 'جملهٔ «علاقه‌مندی موجود»',
				'type'    => 'text',
				'default' => '{count} کالای علاقه‌مندی‌ات موجود است',
				'tokens'  => '{count}',
				'section' => 'welcome',
			),
			'welcome_btn'        => array(
				'label'   => 'دکمهٔ اصلی',
				'type'    => 'text',
				'default' => 'ادامه خرید',
				'desc'    => 'به صفحهٔ فروشگاه می‌رود. خالی = بدون دکمه.',
				'blank'   => true,
				'section' => 'welcome',
			),
			'welcome_btn_down'   => array(
				'label'   => 'دکمهٔ دوم، وقتی فایل دانلودی دارد',
				'type'    => 'text',
				'default' => 'فایل‌های دانلودی',
				'blank'   => true,
				'section' => 'welcome',
			),
			'welcome_btn_orders' => array(
				'label'   => 'دکمهٔ دوم، در حالت عادی',
				'type'    => 'text',
				'default' => 'سفارش‌های من',
				'blank'   => true,
				'section' => 'welcome',
			),

			/* --------------------------------------- کارت‌های آمار */

			'stat_orders_label'  => array(
				'label'   => 'آمار ۱ — عنوان',
				'type'    => 'text',
				'default' => 'مجموع سفارش‌ها',
				'section' => 'stats',
			),
			'stat_orders_unit'   => array(
				'label'   => 'آمار ۱ — واحد',
				'type'    => 'text',
				'default' => 'سفارش',
				'blank'   => true,
				'section' => 'stats',
			),
			'stat_orders_icon'   => array(
				'label'   => 'آمار ۱ — آیکون',
				'type'    => 'icon',
				'default' => 'box',
				'section' => 'stats',
			),
			'stat_spent_label'   => array(
				'label'   => 'آمار ۲ — عنوان',
				'type'    => 'text',
				'default' => 'مبلغ کل خرید',
				'section' => 'stats',
			),
			'stat_spent_unit'    => array(
				'label'   => 'آمار ۲ — واحد',
				'type'    => 'text',
				'default' => 'میلیون',
				'blank'   => true,
				'section' => 'stats',
			),
			'stat_spent_icon'    => array(
				'label'   => 'آمار ۲ — آیکون',
				'type'    => 'icon',
				'default' => 'chart',
				'section' => 'stats',
			),
			'stat_wish_label'    => array(
				'label'   => 'آمار ۳ — عنوان',
				'type'    => 'text',
				'default' => 'علاقه‌مندی‌ها',
				'section' => 'stats',
			),
			'stat_wish_unit'     => array(
				'label'   => 'آمار ۳ — واحد',
				'type'    => 'text',
				'default' => 'کالا',
				'blank'   => true,
				'section' => 'stats',
			),
			'stat_wish_icon'     => array(
				'label'   => 'آمار ۳ — آیکون',
				'type'    => 'icon',
				'default' => 'fav',
				'section' => 'stats',
			),
			'stat_review_label'  => array(
				'label'   => 'آمار ۴ — عنوان',
				'type'    => 'text',
				'default' => 'نظرات ثبت شده',
				'section' => 'stats',
			),
			'stat_review_unit'   => array(
				'label'   => 'آمار ۴ — واحد',
				'type'    => 'text',
				'default' => 'دیدگاه',
				'blank'   => true,
				'section' => 'stats',
			),
			'stat_review_icon'   => array(
				'label'   => 'آمار ۴ — آیکون',
				'type'    => 'icon',
				'default' => 'review',
				'section' => 'stats',
			),

			/* --------------------------------------- نوار هشدار */

			'alert_title'        => array(
				'label'   => 'عنوان هشدار پرداخت',
				'type'    => 'text',
				'default' => 'سفارش {number} در انتظار پرداخت',
				'tokens'  => '{number}',
				'section' => 'alert',
			),
			'alert_note'         => array(
				'label'   => 'خط دوم هشدار',
				'type'    => 'text',
				'default' => '{count} کالا · {total} تومان',
				'tokens'  => '{count} و {total}',
				'section' => 'alert',
			),
			'alert_btn'          => array(
				'label'   => 'متن دکمهٔ پرداخت',
				'type'    => 'text',
				'default' => 'پرداخت سفارش',
				'blank'   => true,
				'section' => 'alert',
			),

			/* --------------------------------------- پیگیری سفارش */

			'track_title'        => array(
				'label'   => 'عنوان کارت پیگیری',
				'type'    => 'text',
				'default' => 'سفارش در حال ارسال',
				'section' => 'track',
			),
			'track_btn'          => array(
				'label'   => 'متن دکمهٔ جزئیات',
				'type'    => 'text',
				'default' => 'جزئیات و رهگیری',
				'blank'   => true,
				'section' => 'track',
			),
			'track_empty_icon'   => array(
				'label'   => 'آیکون حالت خالی',
				'type'    => 'icon',
				'default' => 'truck',
				'section' => 'track',
			),
			'track_empty_title'  => array(
				'label'   => 'عنوان حالت خالی',
				'type'    => 'text',
				'default' => 'سفارشی در راه نیست',
				'section' => 'track',
			),
			'track_empty_text'   => array(
				'label'   => 'توضیح حالت خالی',
				'type'    => 'textarea',
				'default' => 'هر سفارشی که ثبت کنی، مراحل آماده‌سازی و ارسالش را همین‌جا می‌بینی.',
				'section' => 'track',
			),
			'track_empty_btn'    => array(
				'label'   => 'دکمهٔ حالت خالی',
				'type'    => 'text',
				'default' => 'شروع خرید',
				'blank'   => true,
				'section' => 'track',
			),

			/* --------------------------------------- نمودار */

			'chart_title'        => array(
				'label'   => 'عنوان نمودار',
				'type'    => 'text',
				'default' => 'خرید ۶ ماه گذشته',
				'section' => 'chart',
			),
			'chart_note'         => array(
				'label'   => 'یادداشت کنار عنوان',
				'type'    => 'text',
				'default' => 'میلیون تومان',
				'blank'   => true,
				'section' => 'chart',
			),

			/* --------------------------------------- کد تخفیف */

			'coupon_title'       => array(
				'label'   => 'عنوان کارت کد تخفیف',
				'type'    => 'text',
				'default' => 'کد تخفیف اختصاصی',
				'section' => 'coupon',
			),
			'coupon_btn'         => array(
				'label'   => 'متن دکمهٔ کپی',
				'type'    => 'text',
				'default' => 'کپی',
				'section' => 'coupon',
			),

			/* --------------------------------------- پیشنهادها */

			'suggest_title'      => array(
				'label'   => 'عنوان کارت پیشنهاد',
				'type'    => 'text',
				'default' => 'بر اساس خریدهای تو',
				'section' => 'suggest',
			),
			'suggest_sub'        => array(
				'label'   => 'زیرعنوان',
				'type'    => 'text',
				'default' => 'انتخاب‌هایی از دسته‌هایی که بیشتر سراغشان رفته‌ای',
				'blank'   => true,
				'section' => 'suggest',
			),
			'suggest_link'       => array(
				'label'   => 'متن لینک گوشه',
				'type'    => 'text',
				'default' => 'مشاهده همه',
				'blank'   => true,
				'section' => 'suggest',
			),
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

		foreach ( self::dashboard_texts() as $key => $field ) {
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
		if ( isset( self::$overrides[ $key ] ) ) {
			return (string) self::$overrides[ $key ];
		}

		$all = self::get_all();

		return isset( $all[ $key ] ) ? (string) $all[ $key ] : '';
	}

	/**
	 * متنِ یک باکس پیشخوان، با جای‌گذارها پر شده.
	 *
	 * @param string $key    کلید dashboard_texts.
	 * @param array  $tokens نگاشت جای‌گذار به مقدار، مثل array( '{name}' => 'علی' ).
	 * @return string
	 */
	public static function text( $key, $tokens = array() ) {
		$value = self::get( $key );

		return empty( $tokens ) ? $value : strtr( $value, $tokens );
	}

	/**
	 * نام آیکون یک باکس؛ اگر مقدار ذخیره‌شده بی‌معنا باشد پیش‌فرض برمی‌گردد.
	 *
	 * @param string $key
	 * @return string
	 */
	public static function icon_name( $key ) {
		$fields = self::dashboard_texts();
		$value  = trim( self::get( $key ) );

		if ( '' !== $value && isset( Ardal_Account::icon_options()[ $value ] ) ) {
			return $value;
		}

		return isset( $fields[ $key ] ) ? $fields[ $key ]['default'] : 'dash';
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

	/* --------------------------------------------------------------------- */
	/* بازنویسی موقت (ویجت المنتور)                                           */
	/* --------------------------------------------------------------------- */

	/**
	 * فقط کلیدهایی که مقدار دارند می‌نشینند؛ رشتهٔ خالی یعنی «دست نزن».
	 *
	 * دو استثنا: کلیدهایی که خالی‌بودنشان معنا دارد (blank)، و وقتی صدازننده
	 * صریحاً می‌گوید خالی را هم بپذیر. دومی مال ویجت المنتور است: آنجا کاربر
	 * کلید «متن‌های این باکس» را روشن کرده، پس پاک‌کردن یک فیلد یعنی «نشانش
	 * نده»، نه «از تنظیمات سایت بخوان».
	 *
	 * @param array $values
	 * @param bool  $allow_blank
	 */
	public static function set_overrides( $values, $allow_blank = false ) {
		$texts = self::dashboard_texts();

		foreach ( (array) $values as $key => $value ) {
			if ( null === $value ) {
				continue;
			}

			$value = (string) $value;
			$blank = $allow_blank || ! empty( $texts[ $key ]['blank'] );

			if ( '' === $value && ! $blank ) {
				continue;
			}

			self::$overrides[ $key ] = $value;
		}
	}

	public static function clear_overrides() {
		self::$overrides = array();
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

		foreach ( self::dashboard_texts() as $key => $field ) {
			$value = isset( $input[ $key ] ) ? sanitize_text_field( $input[ $key ] ) : '';

			// خالی گذاشتنِ چیزی که blank ندارد یعنی «برگرد به پیش‌فرض».
			$clean[ $key ] = ( '' === $value && empty( $field['blank'] ) )
				? $defaults[ $key ]
				: $value;
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
