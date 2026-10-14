<?php
/**
 * کیف پول: جدول تراکنش‌ها، موجودی، کش‌بگ و تنظیمات.
 *
 * موجودی روی متای کاربر نگه داشته می‌شود تا خواندنش ارزان باشد، ولی منبع
 * حقیقت جدول تراکنش‌هاست: هر تغییر موجودی دقیقاً یک ردیف دارد و ستون balance
 * موجودیِ بعد از همان ردیف است.
 *
 * هر نوشتن روی موجودی زیر قفل همان کاربر (GET_LOCK مای‌اس‌کیوال) انجام می‌شود.
 * بدون قفل، دو درخواست هم‌زمان (مثلاً دو پرداخت پشت‌سرهم) هر دو موجودی قدیم
 * را می‌خواندند و یک اعتبار دو بار خرج می‌شد.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Wallet {

	/**
	 * نسخهٔ ساختار جدول؛ با تغییر ستون‌ها باید بالا برود.
	 */
	const DB_VERSION = '1.0.0';

	const DB_OPTION = 'ardal_wallet_db_version';

	/**
	 * کلید تنظیمات.
	 */
	const OPTION = 'ardal_wallet';

	/**
	 * متای موجودی کاربر.
	 */
	const META_BALANCE = '_ardal_wallet_balance';

	/**
	 * رویداد روزانهٔ انقضای کش‌بگ.
	 */
	const CRON = 'ardal_wallet_daily';

	/* انواع تراکنش */
	const TYPE_TOPUP    = 'topup';
	const TYPE_ORDER    = 'order_pay';
	const TYPE_REFUND   = 'refund';
	const TYPE_CASHBACK = 'cashback';
	const TYPE_EXPIRE   = 'cashback_expire';
	const TYPE_ADMIN    = 'admin';

	/* وضعیت‌ها */
	const STATUS_SUCCESS = 'success';
	const STATUS_PENDING = 'pending';
	const STATUS_FAILED  = 'failed';
	const STATUS_EXPIRED = 'expired';

	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'boot' ), 20 );
	}

	public static function boot() {
		if ( ! Ardal_Helpers::has_woo() ) {
			return;
		}

		add_action( 'init', array( __CLASS__, 'maybe_install' ), 5 );
		add_action( self::CRON, array( __CLASS__, 'run_daily' ) );

		Ardal_Wallet_Checkout::init();
		Ardal_Wallet_Admin::init();
		Ardal_Wallet_Report::init();
		Ardal_Wallet_Tools::init();
		Ardal_Wallet_Render::init();
	}

	/**
	 * نام کامل جدول.
	 *
	 * @return string
	 */
	public static function table() {
		global $wpdb;

		return $wpdb->prefix . 'ardal_wallet_tx';
	}

	/* --------------------------------------------------------------------- */
	/* نصب                                                                    */
	/* --------------------------------------------------------------------- */

	/**
	 * ساخت جدول در صورت نبود یا قدیمی بودن، و زمان‌بندی کار روزانه.
	 *
	 * روی init و نه فقط admin_init: سایتی که افزونه را با FTP به‌روز می‌کند
	 * ممکن است تا مدتی پیشخوان را باز نکند و اولین پرداخت سمت کاربر، جدولی
	 * پیدا نکند.
	 */
	public static function maybe_install() {
		if ( get_option( self::DB_OPTION ) !== self::DB_VERSION ) {
			self::install();
		}

		if ( ! wp_next_scheduled( self::CRON ) ) {
			wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', self::CRON );
		}
	}

	public static function install() {
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$table   = self::table();
		$collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			user_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
			amount DECIMAL(20,4) NOT NULL DEFAULT 0,
			balance DECIMAL(20,4) NOT NULL DEFAULT 0,
			used DECIMAL(20,4) NOT NULL DEFAULT 0,
			type VARCHAR(32) NOT NULL DEFAULT '',
			status VARCHAR(16) NOT NULL DEFAULT 'success',
			order_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
			ref_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
			note VARCHAR(255) NOT NULL DEFAULT '',
			expires_at DATETIME NULL DEFAULT NULL,
			created_at DATETIME NOT NULL DEFAULT '1970-01-01 00:00:00',
			PRIMARY KEY  (id),
			KEY user_type (user_id, type),
			KEY user_created (user_id, created_at),
			KEY order_id (order_id),
			KEY type_status (type, status)
		) {$collate};";

		dbDelta( $sql );

		update_option( self::DB_OPTION, self::DB_VERSION );
	}

	/**
	 * برداشتن کار زمان‌بندی‌شده هنگام غیرفعال‌سازی افزونه.
	 */
	public static function deactivate() {
		wp_clear_scheduled_hook( self::CRON );
	}

	/**
	 * کار روزانه: کش‌بگ‌های منقضی کسر می‌شوند، حتی برای کاربری که سر نزده.
	 *
	 * بدون این، موجودی کاربری که مدت‌ها نیامده در گزارش «اعتبار در دست
	 * کاربران» بیشتر از واقع دیده می‌شد.
	 */
	public static function run_daily() {
		global $wpdb;

		$table = self::table();

		$users = $wpdb->get_col( // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$wpdb->prepare(
				"SELECT DISTINCT user_id FROM {$table}
				WHERE type = %s AND status = %s AND used < amount
				AND expires_at IS NOT NULL AND expires_at <= %s
				LIMIT 500",
				self::TYPE_CASHBACK,
				self::STATUS_SUCCESS,
				current_time( 'mysql' )
			)
		);

		foreach ( (array) $users as $user_id ) {
			self::expire_stale( (int) $user_id );
		}
	}

	/* --------------------------------------------------------------------- */
	/* تنظیمات                                                                */
	/* --------------------------------------------------------------------- */

	/**
	 * @return array
	 */
	public static function defaults() {
		return array(
			'enabled'            => '1',
			'title'              => 'کیف پول',

			/* افزایش اعتبار */
			'topup_enabled'      => '1',
			'min_topup'          => '10000',
			'max_topup'          => '50000000',
			'quick_amounts'      => '100000,200000,500000',
			'short_amounts'      => '1',
			'topup_note'         => 'با هر خرید، بخشی از مبلغ به‌صورت کش‌بگ به کیف پولت برمی‌گردد.',
			'hero_image'         => '',

			/* تسویه حساب */
			'checkout'           => '1',
			'partial'            => '1',
			'max_percent'        => '100',
			'auto_apply'         => '0',
			'checkout_label'     => 'پرداخت از کیف پول',
			'refund_cancel'      => '1',
			'refund_refunded'    => '1',

			/* کش‌بگ */
			'cashback'           => '0',
			'cashback_percent'   => '5',
			'cashback_max'       => '0',
			'cashback_min_total' => '0',
			'cashback_status'    => 'completed',
			'cashback_days'      => '30',
			'cashback_on_wallet' => '0',

			/* پیامک */
			'sms_enabled'        => '0',
			'sms_topup'          => '1',
			'sms_admin'          => '1',
			'sms_cashback'       => '0',
			'sms_text'           => "کیف پول شما به مبلغ {amount} شارژ شد.\nموجودی فعلی: {balance}\n{site}",
			'sms_url'            => '',
			'sms_method'         => 'GET',
			'sms_body'           => '',
		);
	}

	/**
	 * کلیدهای روشن/خاموش.
	 *
	 * @return array
	 */
	public static function switches() {
		return array(
			'enabled',
			'topup_enabled',
			'short_amounts',
			'checkout',
			'partial',
			'auto_apply',
			'refund_cancel',
			'refund_refunded',
			'cashback',
			'cashback_on_wallet',
			'sms_enabled',
			'sms_topup',
			'sms_admin',
			'sms_cashback',
		);
	}

	/**
	 * @return array
	 */
	public static function settings() {
		$saved = get_option( self::OPTION, array() );
		$saved = is_array( $saved ) ? $saved : array();

		return array_merge( self::defaults(), array_intersect_key( $saved, self::defaults() ) );
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public static function option( $key ) {
		$all = self::settings();

		return isset( $all[ $key ] ) ? (string) $all[ $key ] : '';
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public static function on( $key ) {
		return '1' === self::option( $key );
	}

	/**
	 * کیف پول روشن است و ووکامرس هم هست.
	 *
	 * @return bool
	 */
	public static function is_enabled() {
		return Ardal_Helpers::has_woo() && self::on( 'enabled' );
	}

	/**
	 * پاکسازی تنظیمات ارسالی از فرم.
	 *
	 * @param mixed $raw
	 * @return array
	 */
	public static function sanitize( $raw ) {
		/*
		 * فرم بدون این تب ارسال شده (مثلاً ووکامرس غیرفعال بوده و تب رندر
		 * نشده)؛ بدون این، همهٔ کلیدها خاموش ذخیره می‌شدند.
		 */
		if ( ! is_array( $raw ) || ! $raw ) {
			return self::settings();
		}

		$defaults = self::defaults();
		$out      = array();

		foreach ( self::switches() as $key ) {
			$out[ $key ] = empty( $raw[ $key ] ) ? '0' : '1';
		}

		// مدیر ممکن است مبلغ را با ارقام فارسی یا جداکنندهٔ هزارگان وارد کند.
		$number = static function ( $key ) use ( $raw, $defaults ) {
			$value = isset( $raw[ $key ] ) ? self::to_number( $raw[ $key ] ) : (float) $defaults[ $key ];

			return (string) max( 0, $value );
		};

		$out['min_topup']          = $number( 'min_topup' );
		$out['max_topup']          = $number( 'max_topup' );
		$out['cashback_max']       = $number( 'cashback_max' );
		$out['cashback_min_total'] = $number( 'cashback_min_total' );
		$out['cashback_percent']   = (string) min( 100, (float) $number( 'cashback_percent' ) );
		$out['max_percent']        = (string) max( 1, min( 100, absint( $raw['max_percent'] ?? $defaults['max_percent'] ) ) );
		$out['cashback_days']      = (string) absint( $raw['cashback_days'] ?? $defaults['cashback_days'] );

		if ( (float) $out['max_topup'] > 0 && (float) $out['max_topup'] < (float) $out['min_topup'] ) {
			$out['max_topup'] = $out['min_topup'];
		}

		$status                 = sanitize_key( $raw['cashback_status'] ?? $defaults['cashback_status'] );
		$out['cashback_status'] = in_array( $status, array( 'processing', 'completed' ), true ) ? $status : 'completed';

		$out['quick_amounts'] = implode( ',', self::parse_amounts( $raw['quick_amounts'] ?? $defaults['quick_amounts'] ) );

		foreach ( array( 'title', 'topup_note', 'checkout_label' ) as $key ) {
			$value       = isset( $raw[ $key ] ) ? sanitize_text_field( $raw[ $key ] ) : '';
			$out[ $key ] = ( '' === $value && 'topup_note' !== $key ) ? $defaults[ $key ] : $value;
		}

		$out['hero_image'] = isset( $raw['hero_image'] ) ? esc_url_raw( trim( (string) $raw['hero_image'] ) ) : '';

		$out['sms_text'] = isset( $raw['sms_text'] ) ? sanitize_textarea_field( $raw['sms_text'] ) : $defaults['sms_text'];
		$out['sms_url']  = isset( $raw['sms_url'] ) ? esc_url_raw( trim( (string) $raw['sms_url'] ) ) : '';
		$out['sms_body'] = isset( $raw['sms_body'] ) ? sanitize_textarea_field( $raw['sms_body'] ) : '';

		$method            = strtoupper( sanitize_key( $raw['sms_method'] ?? 'GET' ) );
		$out['sms_method'] = in_array( $method, array( 'GET', 'POST' ), true ) ? $method : 'GET';

		return $out;
	}

	/* --------------------------------------------------------------------- */
	/* اعداد                                                                  */
	/* --------------------------------------------------------------------- */

	/**
	 * ارقام فارسی/عربی به لاتین و حذف جداکننده‌ها.
	 *
	 * @param string $value
	 * @return string
	 */
	public static function latin_digits( $value ) {
		$map = array(
			'۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
			'۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
			'٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
			'٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
			'٬' => '', ',' => '', '،' => '', '٫' => '.', ' ' => '',
		);

		return strtr( (string) $value, $map );
	}

	/**
	 * ورودی کاربر یا مدیر به عدد.
	 *
	 * @param mixed $value
	 * @return float
	 */
	public static function to_number( $value ) {
		$value = self::latin_digits( sanitize_text_field( (string) $value ) );

		return (float) wc_format_decimal( preg_replace( '/[^0-9.\-]/', '', $value ) );
	}

	/**
	 * «۱۰۰۰,۲۰۰۰» به آرایهٔ مرتب.
	 *
	 * @param string $raw
	 * @return float[]
	 */
	public static function parse_amounts( $raw ) {
		$parts = preg_split( '/[،,\n]+/u', (string) $raw );
		$out   = array();

		foreach ( (array) $parts as $part ) {
			$value = (float) self::to_number( $part );

			if ( $value > 0 && ! in_array( $value, $out, true ) ) {
				$out[] = $value;
			}
		}

		sort( $out );

		return $out;
	}

	/**
	 * @param float $amount
	 * @return float
	 */
	public static function round( $amount ) {
		return round( (float) $amount, wc_get_price_decimals() );
	}

	/* --------------------------------------------------------------------- */
	/* قفل                                                                    */
	/* --------------------------------------------------------------------- */

	/**
	 * @param int $user_id
	 * @return string
	 */
	private static function lock_name( $user_id ) {
		global $wpdb;

		// نام قفل در کل سرور مای‌اس‌کیوال یکتاست؛ پیشوند پایگاه داده تداخل
		// چند سایتِ روی یک سرور را برمی‌دارد.
		return substr( $wpdb->dbname . '.' . $wpdb->prefix . 'ardal_wallet_' . (int) $user_id, 0, 64 );
	}

	/**
	 * @param int $user_id
	 * @return bool
	 */
	private static function lock( $user_id ) {
		global $wpdb;

		return '1' === (string) $wpdb->get_var( $wpdb->prepare( 'SELECT GET_LOCK(%s, %d)', self::lock_name( $user_id ), 10 ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	}

	/**
	 * @param int $user_id
	 */
	private static function unlock( $user_id ) {
		global $wpdb;

		$wpdb->query( $wpdb->prepare( 'SELECT RELEASE_LOCK(%s)', self::lock_name( $user_id ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	}

	/* --------------------------------------------------------------------- */
	/* موجودی                                                                 */
	/* --------------------------------------------------------------------- */

	/**
	 * موجودی کاربر.
	 *
	 * @param int  $user_id ۰ = کاربر جاری.
	 * @param bool $expire  کش‌بگ‌های منقضی پیش از خواندن کسر شوند؟
	 * @return float
	 */
	public static function balance( $user_id = 0, $expire = true ) {
		$user_id = $user_id ? (int) $user_id : get_current_user_id();

		if ( ! $user_id ) {
			return 0.0;
		}

		if ( $expire ) {
			self::expire_stale( $user_id );
		}

		return self::round( get_user_meta( $user_id, self::META_BALANCE, true ) );
	}

	/**
	 * موجودی تازه از خود پایگاه داده؛ زیر قفل استفاده می‌شود.
	 *
	 * کش متای کاربر در همان درخواست ممکن است مقداری را نگه داشته باشد که
	 * درخواست دیگری پیش از گرفتن قفل عوض کرده؛ پس اول پاک می‌شود.
	 *
	 * @param int $user_id
	 * @return float
	 */
	private static function fresh_balance( $user_id ) {
		wp_cache_delete( (int) $user_id, 'user_meta' );

		return self::round( get_user_meta( (int) $user_id, self::META_BALANCE, true ) );
	}

	/**
	 * @param float $amount
	 * @param int   $user_id
	 * @return bool
	 */
	public static function can_afford( $amount, $user_id = 0 ) {
		return self::balance( $user_id ) + 0.0001 >= (float) $amount;
	}

	/* --------------------------------------------------------------------- */
	/* ثبت تراکنش                                                             */
	/* --------------------------------------------------------------------- */

	/**
	 * افزودن اعتبار.
	 *
	 * @param int   $user_id
	 * @param float $amount مثبت.
	 * @param array $args   type، status، order_id، ref_id، note، expires_at.
	 * @return int|WP_Error شناسهٔ تراکنش.
	 */
	public static function credit( $user_id, $amount, $args = array() ) {
		return self::record( $user_id, abs( (float) $amount ), $args );
	}

	/**
	 * کسر اعتبار؛ کافی نبودن موجودی زیر قفل بررسی می‌شود، نه پیش از آن.
	 *
	 * @param int   $user_id
	 * @param float $amount مثبت (بدهکار ثبت می‌شود).
	 * @param array $args
	 * @return int|WP_Error
	 */
	public static function debit( $user_id, $amount, $args = array() ) {
		self::expire_stale( (int) $user_id );

		return self::record( $user_id, -abs( (float) $amount ), $args, true );
	}

	/**
	 * ثبت ردیف تراکنش و به‌روزرسانی موجودی، زیر قفل کاربر.
	 *
	 * @param int   $user_id
	 * @param float $amount        علامت‌دار.
	 * @param array $args
	 * @param bool  $require_funds منفی شدن موجودی مجاز نیست.
	 * @return int|WP_Error
	 */
	private static function record( $user_id, $amount, $args = array(), $require_funds = false ) {
		global $wpdb;

		$user_id = (int) $user_id;

		if ( ! $user_id || ! get_userdata( $user_id ) ) {
			return new WP_Error( 'ardal_wallet_user', 'کاربر معتبر نیست.' );
		}

		$args = wp_parse_args(
			$args,
			array(
				'type'       => self::TYPE_ADMIN,
				'status'     => self::STATUS_SUCCESS,
				'order_id'   => 0,
				'ref_id'     => 0,
				'note'       => '',
				'expires_at' => null,
			)
		);

		$amount = self::round( $amount );

		if ( 0.0 === (float) $amount ) {
			return new WP_Error( 'ardal_wallet_zero', 'مبلغ صفر است.' );
		}

		if ( ! self::lock( $user_id ) ) {
			return new WP_Error( 'ardal_wallet_busy', 'کیف پول در حال به‌روزرسانی است؛ چند لحظه بعد دوباره تلاش کنید.' );
		}

		$pending = self::STATUS_SUCCESS !== $args['status'];
		$balance = self::fresh_balance( $user_id );

		if ( ! $pending ) {
			if ( $require_funds && $amount < 0 && $balance + 0.0001 < abs( $amount ) ) {
				self::unlock( $user_id );

				return new WP_Error( 'ardal_wallet_low', 'موجودی کیف پول کافی نیست.' );
			}

			$balance = self::round( $balance + $amount );
		}

		$ok = $wpdb->insert( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			self::table(),
			array(
				'user_id'    => $user_id,
				'amount'     => $amount,
				'balance'    => $balance,
				'used'       => 0,
				'type'       => sanitize_key( $args['type'] ),
				'status'     => sanitize_key( $args['status'] ),
				'order_id'   => absint( $args['order_id'] ),
				'ref_id'     => absint( $args['ref_id'] ),
				'note'       => mb_substr( sanitize_text_field( $args['note'] ), 0, 250 ),
				'expires_at' => $args['expires_at'],
				'created_at' => current_time( 'mysql' ),
			),
			array( '%d', '%f', '%f', '%f', '%s', '%s', '%d', '%d', '%s', '%s', '%s' )
		);

		if ( ! $ok ) {
			self::unlock( $user_id );

			return new WP_Error( 'ardal_wallet_db', 'ثبت تراکنش ممکن نشد.' );
		}

		$id = (int) $wpdb->insert_id;

		if ( ! $pending ) {
			update_user_meta( $user_id, self::META_BALANCE, $balance );

			// خرج کردن اعتبار، کش‌بگ‌های قدیمی‌تر را «مصرف‌شده» علامت می‌زند.
			if ( $amount < 0 && self::TYPE_EXPIRE !== $args['type'] ) {
				self::consume_cashback( $user_id, abs( $amount ) );
			}
		}

		self::unlock( $user_id );

		/**
		 * پس از ثبت تراکنش کیف پول.
		 *
		 * @param int   $id      شناسهٔ تراکنش.
		 * @param int   $user_id کاربر.
		 * @param float $amount  مبلغ علامت‌دار.
		 * @param array $args    پارامترها.
		 */
		do_action( 'ardal_wallet_transaction', $id, $user_id, $amount, $args );

		return $id;
	}

	/**
	 * تغییر وضعیت یک تراکنش (مثلاً شارژ در انتظار، بعد از بازگشت از درگاه).
	 *
	 * @param int    $id
	 * @param string $status
	 * @return bool
	 */
	public static function set_status( $id, $status ) {
		global $wpdb;

		$row = self::get( $id );

		if ( ! $row || $row['status'] === $status ) {
			return false;
		}

		if ( ! self::lock( $row['user_id'] ) ) {
			return false;
		}

		$was     = self::STATUS_SUCCESS === $row['status'];
		$is      = self::STATUS_SUCCESS === $status;
		$balance = self::fresh_balance( $row['user_id'] );

		if ( ! $was && $is ) {
			$balance = self::round( $balance + $row['amount'] );
		} elseif ( $was && ! $is ) {
			$balance = self::round( $balance - $row['amount'] );
		}

		$wpdb->update( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			self::table(),
			array(
				'status'  => sanitize_key( $status ),
				'balance' => $balance,
			),
			array( 'id' => (int) $id ),
			array( '%s', '%f' ),
			array( '%d' )
		);

		update_user_meta( $row['user_id'], self::META_BALANCE, $balance );

		self::unlock( $row['user_id'] );

		return true;
	}

	/* --------------------------------------------------------------------- */
	/* کش‌بگ                                                                  */
	/* --------------------------------------------------------------------- */

	/**
	 * ثبت کش‌بگ؛ انقضا از تنظیمات.
	 *
	 * @param int    $user_id
	 * @param float  $amount
	 * @param int    $order_id
	 * @param string $note خالی = یادداشت پیش‌فرض.
	 * @return int|WP_Error
	 */
	public static function add_cashback( $user_id, $amount, $order_id = 0, $note = '' ) {
		$days    = (int) self::option( 'cashback_days' );
		$expires = null;

		if ( $days > 0 ) {
			$expires = gmdate( 'Y-m-d H:i:s', current_time( 'timestamp' ) + $days * DAY_IN_SECONDS ); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
		}

		if ( '' === trim( (string) $note ) ) {
			$note = $order_id ? sprintf( 'کش‌بگ سفارش %s', $order_id ) : 'کش‌بگ';
		}

		return self::credit(
			$user_id,
			$amount,
			array(
				'type'       => self::TYPE_CASHBACK,
				'order_id'   => $order_id,
				'expires_at' => $expires,
				'note'       => $note,
			)
		);
	}

	/**
	 * کش‌بگ‌های فعال، زودترین انقضا اول.
	 *
	 * @param int $user_id
	 * @return array
	 */
	public static function active_cashback( $user_id ) {
		global $wpdb;

		$table = self::table();

		$rows = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$wpdb->prepare(
				"SELECT * FROM {$table}
				WHERE user_id = %d AND type = %s AND status = %s
				AND used < amount
				AND ( expires_at IS NULL OR expires_at > %s )
				ORDER BY ( expires_at IS NULL ), expires_at ASC, id ASC",
				(int) $user_id,
				self::TYPE_CASHBACK,
				self::STATUS_SUCCESS,
				current_time( 'mysql' )
			),
			ARRAY_A
		);

		return is_array( $rows ) ? $rows : array();
	}

	/**
	 * علامت‌گذاری مصرف کش‌بگ هنگام خرج اعتبار (زودترین انقضا اول).
	 *
	 * فقط دفترِ «چقدر از کش‌بگ خرج شد» است، برای ستون «مبلغ استفاده‌شده»؛
	 * موجودی جداگانه حساب شده است. منطقی است که اول کش‌بگی خرج شود که زودتر
	 * منقضی می‌شود، تا کاربر کمتر از دست بدهد.
	 *
	 * @param int   $user_id
	 * @param float $amount
	 */
	private static function consume_cashback( $user_id, $amount ) {
		global $wpdb;

		$left = (float) $amount;

		foreach ( self::active_cashback( $user_id ) as $row ) {
			if ( $left <= 0 ) {
				break;
			}

			$take = min( (float) $row['amount'] - (float) $row['used'], $left );

			$wpdb->update( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				self::table(),
				array( 'used' => (float) $row['used'] + $take ),
				array( 'id' => (int) $row['id'] ),
				array( '%f' ),
				array( '%d' )
			);

			$left -= $take;
		}
	}

	/**
	 * کسر کش‌بگ‌های منقضی از موجودی؛ هر ردیف فقط یک بار.
	 *
	 * @param int $user_id
	 */
	public static function expire_stale( $user_id ) {
		global $wpdb;

		$user_id = (int) $user_id;

		if ( ! $user_id ) {
			return;
		}

		$table = self::table();

		$rows = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$wpdb->prepare(
				"SELECT * FROM {$table}
				WHERE user_id = %d AND type = %s AND status = %s
				AND used < amount AND expires_at IS NOT NULL AND expires_at <= %s",
				$user_id,
				self::TYPE_CASHBACK,
				self::STATUS_SUCCESS,
				current_time( 'mysql' )
			),
			ARRAY_A
		);

		foreach ( (array) $rows as $row ) {
			$remaining = self::round( (float) $row['amount'] - (float) $row['used'] );

			/*
			 * اول خودِ ردیف علامت می‌خورد، فقط اگر هنوز علامت نخورده باشد؛ این
			 * UPDATE شرطی جلوی کسر دوباره را می‌گیرد وقتی دو درخواست هم‌زمان
			 * به همین ردیف رسیده باشند.
			 */
			$claimed = $wpdb->query( // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$wpdb->prepare(
					"UPDATE {$table} SET used = amount, status = %s WHERE id = %d AND status = %s",
					self::STATUS_EXPIRED,
					(int) $row['id'],
					self::STATUS_SUCCESS
				)
			);

			if ( ! $claimed || $remaining <= 0 ) {
				continue;
			}

			// موجودی ممکن است در این فاصله خرج شده باشد؛ بیشتر از موجودی کسر نمی‌شود.
			$take = min( $remaining, max( 0, self::fresh_balance( $user_id ) ) );

			if ( $take > 0 ) {
				self::record(
					$user_id,
					-$take,
					array(
						'type'   => self::TYPE_EXPIRE,
						'ref_id' => (int) $row['id'],
						'note'   => 'انقضای کش‌بگ',
					)
				);
			}
		}
	}

	/* --------------------------------------------------------------------- */
	/* خواندن تراکنش‌ها                                                       */
	/* --------------------------------------------------------------------- */

	/**
	 * @param int $id
	 * @return array|null
	 */
	public static function get( $id ) {
		global $wpdb;

		$table = self::table();

		$row = $wpdb->get_row( // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d", (int) $id ),
			ARRAY_A
		);

		return $row ? self::normalize( $row ) : null;
	}

	/**
	 * تازه‌ترین تراکنش یک نوع روی یک سفارش.
	 *
	 * @param int    $order_id
	 * @param string $type
	 * @return array|null
	 */
	public static function find_by_order( $order_id, $type ) {
		global $wpdb;

		$table = self::table();

		$row = $wpdb->get_row( // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$wpdb->prepare(
				"SELECT * FROM {$table} WHERE order_id = %d AND type = %s ORDER BY id DESC LIMIT 1",
				(int) $order_id,
				sanitize_key( $type )
			),
			ARRAY_A
		);

		return $row ? self::normalize( $row ) : null;
	}

	/**
	 * @param int   $user_id
	 * @param array $args limit، offset، types، exclude_types.
	 * @return array
	 */
	public static function transactions( $user_id, $args = array() ) {
		global $wpdb;

		$args = wp_parse_args(
			$args,
			array(
				'limit'         => 10,
				'offset'        => 0,
				'types'         => array(),
				'exclude_types' => array(),
			)
		);

		$table = self::table();
		$where = $wpdb->prepare( 'user_id = %d', (int) $user_id ) . self::type_clause( $args['types'], $args['exclude_types'] );

		$rows = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared
			$wpdb->prepare(
				"SELECT * FROM {$table} WHERE {$where} ORDER BY id DESC LIMIT %d OFFSET %d",
				max( 1, (int) $args['limit'] ),
				max( 0, (int) $args['offset'] )
			),
			ARRAY_A
		);

		return array_map( array( __CLASS__, 'normalize' ), is_array( $rows ) ? $rows : array() );
	}

	/**
	 * @param int   $user_id
	 * @param array $args types، exclude_types.
	 * @return int
	 */
	public static function count( $user_id, $args = array() ) {
		global $wpdb;

		$args  = wp_parse_args( $args, array( 'types' => array(), 'exclude_types' => array() ) );
		$table = self::table();
		$where = $wpdb->prepare( 'user_id = %d', (int) $user_id ) . self::type_clause( $args['types'], $args['exclude_types'] );

		return (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table} WHERE {$where}" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared
	}

	/**
	 * شرط نوع تراکنش؛ فقط کلیدهای شناخته‌شده پذیرفته می‌شوند.
	 *
	 * @param array $types
	 * @param array $exclude
	 * @return string
	 */
	private static function type_clause( $types, $exclude ) {
		$known = array_keys( self::type_names() );
		$sql   = '';

		$types = array_values( array_intersect( array_map( 'sanitize_key', (array) $types ), $known ) );

		if ( $types ) {
			$sql .= " AND type IN ('" . implode( "','", $types ) . "')";
		}

		$exclude = array_values( array_intersect( array_map( 'sanitize_key', (array) $exclude ), $known ) );

		if ( $exclude ) {
			$sql .= " AND type NOT IN ('" . implode( "','", $exclude ) . "')";
		}

		return $sql;
	}

	/**
	 * ردیف خام به آرایهٔ آمادهٔ نمایش.
	 *
	 * @param array $row
	 * @return array
	 */
	public static function normalize( $row ) {
		$amount = (float) $row['amount'];

		return array(
			'id'         => (int) $row['id'],
			'user_id'    => (int) $row['user_id'],
			'amount'     => $amount,
			'abs'        => abs( $amount ),
			'is_credit'  => $amount >= 0,
			'balance'    => (float) $row['balance'],
			'used'       => (float) $row['used'],
			'remaining'  => max( 0, $amount - (float) $row['used'] ),
			'type'       => (string) $row['type'],
			'status'     => (string) $row['status'],
			'order_id'   => (int) $row['order_id'],
			'ref_id'     => (int) $row['ref_id'],
			'note'       => (string) $row['note'],
			'expires_at' => $row['expires_at'] ? (string) $row['expires_at'] : '',
			'created_at' => (string) $row['created_at'],
		);
	}

	/* --------------------------------------------------------------------- */
	/* برچسب‌ها و قالب‌بندی                                                    */
	/* --------------------------------------------------------------------- */

	/**
	 * @return array
	 */
	public static function type_names() {
		return array(
			self::TYPE_TOPUP    => 'افزایش اعتبار',
			self::TYPE_ORDER    => 'پرداخت سفارش',
			self::TYPE_CASHBACK => 'کش‌بگ خرید',
			self::TYPE_REFUND   => 'بازگشت وجه',
			self::TYPE_EXPIRE   => 'انقضای کش‌بگ',
			self::TYPE_ADMIN    => 'تغییر توسط مدیر',
		);
	}

	/**
	 * @return array
	 */
	public static function status_names() {
		return array(
			self::STATUS_SUCCESS => 'موفق',
			self::STATUS_PENDING => 'در انتظار',
			self::STATUS_FAILED  => 'ناموفق',
			self::STATUS_EXPIRED => 'منقضی',
		);
	}

	/**
	 * برچسب فارسی تراکنش، با شمارهٔ سفارش.
	 *
	 * @param array $tx
	 * @return string
	 */
	public static function type_label( $tx ) {
		$names = self::type_names();
		$label = $names[ $tx['type'] ] ?? 'تراکنش';

		if ( ! empty( $tx['order_id'] ) ) {
			$label .= ' — سفارش ' . Ardal_Helpers::fa_digits( $tx['order_id'] );
		}

		return $label;
	}

	/**
	 * @param string $status
	 * @return array key و label.
	 */
	public static function status_label( $status ) {
		$names = self::status_names();

		return array(
			'key'   => $status,
			'label' => $names[ $status ] ?? $status,
		);
	}

	/**
	 * وضعیت یک ردیف کش‌بگ: فعال / استفاده‌شده / منقضی.
	 *
	 * @param array $tx
	 * @return array
	 */
	public static function cashback_state( $tx ) {
		if ( self::STATUS_EXPIRED === $tx['status'] ) {
			return array( 'key' => 'expired', 'label' => 'منقضی شده' );
		}

		if ( $tx['remaining'] <= 0 ) {
			return array( 'key' => 'used', 'label' => 'استفاده شده' );
		}

		if ( $tx['expires_at'] && strtotime( $tx['expires_at'] ) <= current_time( 'timestamp' ) ) { // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
			return array( 'key' => 'expired', 'label' => 'منقضی شده' );
		}

		return array( 'key' => 'active', 'label' => 'فعال' );
	}

	/**
	 * مبلغ برای سمت کاربر: همان قالب قیمت‌های اردال (ارقام فارسی و واحد
	 * تومان یا نماد آن طبق تنظیمات پالت).
	 *
	 * @param float $amount
	 * @return string HTML امن.
	 */
	public static function money( $amount ) {
		return '<span class="ard-wl-money"><span class="ard-wl-money__num">'
			. esc_html( Ardal_Helpers::price( self::round( $amount ) ) )
			. '</span> ' . Ardal_Currency::unit() . '</span>';
	}

	/**
	 * مبلغ بدون HTML؛ برای یادداشت سفارش، پیامک و CSV.
	 *
	 * @param float $amount
	 * @return string
	 */
	public static function plain_money( $amount ) {
		return trim( html_entity_decode( wp_strip_all_tags( wc_price( (float) $amount ) ), ENT_QUOTES, 'UTF-8' ) );
	}

	/**
	 * مبلغ کوتاه برای دکمه‌های میان‌بر: «۱۰۰ هزار تومان».
	 *
	 * @param float $amount
	 * @return string متن ساده.
	 */
	public static function short_money( $amount ) {
		$amount = (float) $amount;

		if ( self::on( 'short_amounts' ) ) {
			if ( $amount >= 1000000 && 0.0 === fmod( $amount, 1000000 ) ) {
				return Ardal_Helpers::fa_digits( number_format( $amount / 1000000 ) ) . ' میلیون تومان';
			}

			if ( $amount >= 1000 && 0.0 === fmod( $amount, 1000 ) ) {
				return Ardal_Helpers::fa_digits( number_format( $amount / 1000 ) ) . ' هزار تومان';
			}
		}

		return Ardal_Helpers::price( $amount ) . ' تومان';
	}

	/**
	 * تاریخ شمسی تراکنش.
	 *
	 * زمان‌ها با current_time('mysql') ثبت شده‌اند، یعنی به وقت محلی سایت؛
	 * Ardal_Jalali هم همین را انتظار دارد.
	 *
	 * @param string $mysql
	 * @param bool   $time
	 * @return string
	 */
	public static function date( $mysql, $time = false ) {
		$timestamp = $mysql ? strtotime( $mysql . ' UTC' ) : 0;

		if ( ! $timestamp ) {
			return '—';
		}

		return Ardal_Jalali::format( $timestamp, $time ? 'with_time' : 'long' );
	}

	/**
	 * نشانی صفحهٔ کیف پول در پنل کاربری.
	 *
	 * @return string
	 */
	public static function account_url() {
		return wc_get_endpoint_url( 'wallet', '', wc_get_page_permalink( 'myaccount' ) );
	}

	/**
	 * تصویر کیف پول: تصویر تنظیمات یا طرح پیش‌فرض درون‌خطی.
	 *
	 * @param string $class
	 * @return string
	 */
	public static function art_html( $class = 'ard-wl-art' ) {
		$image = trim( self::option( 'hero_image' ) );

		if ( '' !== $image ) {
			return sprintf( '<img class="%s" src="%s" alt="" loading="lazy" decoding="async" />', esc_attr( $class ), esc_url( $image ) );
		}

		// رنگ‌ها از پالت می‌آیند تا تصویر با رنگ‌بندی سایت بخواند.
		return '<svg class="' . esc_attr( $class ) . '" width="120" height="104" viewBox="0 0 120 104" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">'
			. '<rect x="26" y="10" width="30" height="22" rx="3" fill="var(--ard-tint-green-border,#cfe3a8)" transform="rotate(-8 41 21)"/>'
			. '<rect x="48" y="4" width="30" height="22" rx="3" fill="var(--ard-tint-green,#e6f0c6)" transform="rotate(6 63 15)"/>'
			. '<rect x="8" y="30" width="86" height="52" rx="9" fill="var(--ard-primary,#1d4ed8)"/>'
			. '<rect x="8" y="30" width="86" height="14" rx="7" fill="var(--ard-primary-dark,#0b2a5b)"/>'
			. '<rect x="14" y="40" width="34" height="20" rx="4" fill="#eef3f7"/>'
			. '<rect x="18" y="45" width="16" height="4" rx="2" fill="#9fb6c8"/>'
			. '<rect x="18" y="52" width="24" height="4" rx="2" fill="#c6d5e0"/>'
			. '<circle cx="86" cy="60" r="9" fill="var(--ard-accent,#f2b23e)"/>'
			. '<circle cx="86" cy="60" r="5" fill="#fff" fill-opacity=".45"/>'
			. '<ellipse cx="88" cy="86" rx="19" ry="7" fill="var(--ard-star,#f2b23e)"/>'
			. '<ellipse cx="88" cy="80" rx="19" ry="7" fill="var(--ard-star,#f7c85a)"/>'
			. '<ellipse cx="88" cy="80" rx="11" ry="4" fill="#fff" fill-opacity=".4"/>'
			. '</svg>';
	}
}
