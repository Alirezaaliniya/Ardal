<?php
/**
 * پیامک کیف پول، از راه درگاه نیاس لاگین.
 *
 * اردال درگاه پیامکی خودش را ندارد: درگاه، کلید API، شماره خط و حساب پنل همه
 * از تنظیمات نیاس لاگین خوانده می‌شوند (توابع عمومی nias_sms_* از نسخهٔ ۱.۴).
 * اگر مدیر درگاه را در نیاس عوض کند، پیامک‌های اردال بدون هیچ تغییری با
 * درگاه تازه می‌روند و در «گزارش پیام‌ها»ی نیاس هم ثبت می‌شوند.
 *
 * هر رویداد (شارژ کاربر، شارژ مدیر، کش‌بگ) یا با پترن پنل می‌رود — وقتی کد
 * پترن پر باشد — یا با متن آزاد. بیشتر پنل‌های ایرانی از خط خدماتی فقط پترن
 * را می‌فرستند.
 *
 * ارسال در پس‌زمینه است (Action Scheduler ووکامرس یا WP-Cron)، همان توصیهٔ
 * مستندات نیاس: پیامک یک درخواست شبکه است و نباید بازگشت از درگاه پرداخت یا
 * شارژ گروهی هزار نفره را چند ثانیه به ازای هر نفر معطل کند.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Wallet_Sms {

	const EVENT_TOPUP    = 'topup';
	const EVENT_ADMIN    = 'admin';
	const EVENT_CASHBACK = 'cashback';

	/**
	 * هوک ارسال پس‌زمینه.
	 */
	const HOOK = 'ardal_wallet_sms_deliver';

	public static function init() {
		add_action( self::HOOK, array( __CLASS__, 'deliver' ) );
		add_action( 'wp_ajax_ardal_wallet_sms_test', array( __CLASS__, 'ajax_test' ) );
	}

	/**
	 * @return array رویداد => برچسب.
	 */
	public static function events() {
		return array(
			self::EVENT_TOPUP    => 'شارژ کیف پول توسط کاربر',
			self::EVENT_ADMIN    => 'شارژ دستی یا گروهی توسط مدیر',
			self::EVENT_CASHBACK => 'واریز کش‌بگ خرید',
		);
	}

	/**
	 * @return array جای‌گذار => توضیح.
	 */
	public static function variables() {
		return array(
			'{name}'        => 'نام کاربر',
			'{amount}'      => 'مبلغ با واحد',
			'{amount_raw}'  => 'مبلغ فقط با رقم (برای پترن)',
			'{balance}'     => 'موجودی با واحد',
			'{balance_raw}' => 'موجودی فقط با رقم (برای پترن)',
			'{site}'        => 'نام سایت',
		);
	}

	/* --------------------------------------------------------------------- */
	/* وضعیت                                                                  */
	/* --------------------------------------------------------------------- */

	/**
	 * آیا نیاس لاگین با توابع عمومی پیامک (نسخهٔ ۱.۴ به بعد) فعال است؟
	 *
	 * @return bool
	 */
	public static function available() {
		return function_exists( 'nias_sms_send_pattern' ) && function_exists( 'nias_sms_send_text' );
	}

	/**
	 * نیاس هست و درگاهی در تنظیماتش انتخاب شده.
	 *
	 * @return bool
	 */
	public static function configured() {
		return self::available() && function_exists( 'nias_sms_is_configured' ) && nias_sms_is_configured();
	}

	/**
	 * درگاه انتخاب‌شدهٔ نیاس، بدون هیچ کلید یا رمزی.
	 *
	 * @return array|null id، name، supports_text.
	 */
	public static function gateway() {
		return function_exists( 'nias_sms_gateway' ) ? nias_sms_gateway() : null;
	}

	/**
	 * @param string $event
	 * @return bool
	 */
	public static function is_enabled( $event = '' ) {
		if ( ! Ardal_Wallet::on( 'sms_enabled' ) || ! self::available() ) {
			return false;
		}

		return isset( self::events()[ $event ] ) ? Ardal_Wallet::on( 'sms_' . $event ) : true;
	}

	/**
	 * شمارهٔ موبایل کاربر.
	 *
	 * اول شمارهٔ حساب در نیاس (همانی که کاربر با آن وارد می‌شود)، بعد شمارهٔ
	 * صورتحساب ووکامرس برای حساب‌هایی که هنوز شمارهٔ ثبت‌شده ندارند.
	 *
	 * @param int $user_id
	 * @return string شکل استاندارد 09xxxxxxxxx یا خالی.
	 */
	public static function phone( $user_id ) {
		$phone = function_exists( 'nias_user_mobile' ) ? nias_user_mobile( (int) $user_id ) : '';

		if ( '' === $phone ) {
			$phone = (string) get_user_meta( (int) $user_id, 'billing_phone', true );
		}

		/**
		 * شمارهٔ موبایل کاربر برای پیامک کیف پول.
		 *
		 * @param string $phone
		 * @param int    $user_id
		 */
		$phone = (string) apply_filters( 'ardal_wallet_sms_phone', $phone, (int) $user_id );

		return function_exists( 'nias_normalize_mobile' ) ? nias_normalize_mobile( $phone ) : trim( $phone );
	}

	/* --------------------------------------------------------------------- */
	/* ارسال                                                                  */
	/* --------------------------------------------------------------------- */

	/**
	 * پیامک یک رویداد را در صف می‌گذارد.
	 *
	 * موجودی همین حالا خوانده می‌شود، نه موقع ارسال؛ پیامک باید موجودیِ بعد از
	 * همین تراکنش را بگوید، حتی اگر تا اجرای صف تراکنش دیگری آمده باشد.
	 *
	 * @param int    $user_id
	 * @param float  $amount
	 * @param string $event
	 * @return bool آیا در صف رفت (یا فرستاده شد)؟
	 */
	public static function send( $user_id, $amount, $event = self::EVENT_TOPUP ) {
		if ( ! self::is_enabled( $event ) || ! get_userdata( (int) $user_id ) || '' === self::phone( $user_id ) ) {
			return false;
		}

		$job = array(
			'user_id' => (int) $user_id,
			'amount'  => (float) $amount,
			'event'   => (string) $event,
			'balance' => Ardal_Wallet::balance( (int) $user_id, false ),
		);

		/**
		 * ارسال همان لحظه به‌جای صف پس‌زمینه.
		 *
		 * @param bool  $sync
		 * @param array $job
		 */
		if ( apply_filters( 'ardal_wallet_sms_sync', false, $job ) ) {
			return true === self::deliver( $job );
		}

		if ( function_exists( 'as_enqueue_async_action' ) ) {
			as_enqueue_async_action( self::HOOK, array( $job ), 'ardal' );

			return true;
		}

		// کلید یکتا تا دو پیامک هم‌مبلغ در یک ثانیه با هم ادغام نشوند.
		$job['uid'] = wp_generate_password( 8, false );

		return false !== wp_schedule_single_event( time(), self::HOOK, array( $job ) );
	}

	/**
	 * ارسال واقعی؛ از صف پس‌زمینه صدا زده می‌شود.
	 *
	 * @param array $job user_id، amount، event، balance.
	 * @return true|WP_Error
	 */
	public static function deliver( $job ) {
		$job = wp_parse_args( (array) $job, array( 'user_id' => 0, 'amount' => 0, 'event' => self::EVENT_TOPUP, 'balance' => null ) );

		if ( ! self::is_enabled( $job['event'] ) ) {
			return new WP_Error( 'ardal_sms_off', 'پیامک این رویداد خاموش است یا نیاس لاگین فعال نیست.' );
		}

		$user = get_userdata( (int) $job['user_id'] );

		if ( ! $user ) {
			return new WP_Error( 'ardal_sms_user', 'کاربر پیدا نشد.' );
		}

		$vars = self::vars( $user, (float) $job['amount'], null === $job['balance'] ? null : (float) $job['balance'] );

		return self::dispatch( self::phone( $user->ID ), $job['event'], $vars, $user->ID );
	}

	/**
	 * مقدار جای‌گذارها.
	 *
	 * @param WP_User    $user
	 * @param float      $amount
	 * @param float|null $balance
	 * @return array
	 */
	private static function vars( $user, $amount, $balance = null ) {
		$balance = null === $balance ? Ardal_Wallet::balance( $user->ID, false ) : $balance;

		return array(
			'{name}'        => $user->display_name ? $user->display_name : $user->user_login,
			'{amount}'      => self::money_text( $amount ),
			'{amount_raw}'  => (string) (int) round( $amount ),
			'{balance}'     => self::money_text( $balance ),
			'{balance_raw}' => (string) (int) round( $balance ),
			'{site}'        => wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ),
		);
	}

	/**
	 * مبلغ برای متن پیامک: «۳۰٬۰۰۰ تومان» با ارقام تنظیمات اردال؛ قالب
	 * wc_price در متن ساده واحد را پیش از عدد و با ارقام لاتین می‌گذاشت.
	 *
	 * @param float $amount
	 * @return string
	 */
	private static function money_text( $amount ) {
		return Ardal_Helpers::price( Ardal_Wallet::round( $amount ) ) . ' تومان';
	}

	/**
	 * فرستادن با نیاس: پترن اگر کدش پر است، وگرنه متن آزاد.
	 *
	 * @param string $phone
	 * @param string $event
	 * @param array  $vars
	 * @param int    $user_id
	 * @return true|WP_Error
	 */
	private static function dispatch( $phone, $event, $vars, $user_id = 0 ) {
		if ( ! self::available() ) {
			return new WP_Error( 'ardal_sms_nias', 'نیاس لاگین (نسخهٔ ۱.۴ یا بالاتر) فعال نیست.' );
		}

		if ( '' === (string) $phone ) {
			return new WP_Error( 'ardal_sms_phone', 'کاربر شمارهٔ موبایل ندارد.' );
		}

		$key     = 'sms_' . $event;
		$pattern = trim( Ardal_Wallet::option( $key . '_pattern' ) );
		$args    = array(
			'label'   => 'کیف پول اردال — ' . ( self::events()[ $event ] ?? $event ),
			'user_id' => (int) $user_id,
		);

		if ( '' !== $pattern ) {
			$result = nias_sms_send_pattern( $phone, $pattern, self::pattern_params( Ardal_Wallet::option( $key . '_vars' ), $vars ), $args );
		} else {
			$text = trim( strtr( Ardal_Wallet::option( $key . '_text' ), $vars ) );

			/**
			 * متن نهایی پیامک.
			 *
			 * @param string $text
			 * @param int    $user_id
			 * @param array  $vars
			 * @param string $event
			 */
			$text = (string) apply_filters( 'ardal_wallet_sms_text', $text, (int) $user_id, $vars, $event );

			if ( '' === $text ) {
				return new WP_Error( 'ardal_sms_empty', 'متن پیامک خالی است.' );
			}

			$result = nias_sms_send_text( $phone, $text, $args );
		}

		/**
		 * پس از هر ارسال پیامک کیف پول.
		 *
		 * @param true|WP_Error $result
		 * @param string        $phone
		 * @param array         $context user_id، event، vars.
		 */
		do_action( 'ardal_wallet_sms', $result, $phone, array( 'user_id' => (int) $user_id, 'event' => $event, 'vars' => $vars ) );

		return $result;
	}

	/**
	 * متغیرهای پترن از رشتهٔ تنظیمات؛ همان قرارداد نیاس.
	 *
	 * «name:{var}» متغیر نام‌دار می‌سازد (کاوه‌نگار، SMS.ir) و «{var}» تنها
	 * متغیر ترتیبی (آموت، MSGWay). ترتیب همان ترتیب نوشته‌شده است، پس با هر
	 * دو نوع درگاه کار می‌کند.
	 *
	 * @param string $spec
	 * @param array  $vars
	 * @return array
	 */
	public static function pattern_params( $spec, $vars ) {
		$params = array();

		foreach ( (array) preg_split( '/[,،]+/u', (string) $spec, -1, PREG_SPLIT_NO_EMPTY ) as $token ) {
			$token = trim( $token );

			if ( '' === $token ) {
				continue;
			}

			if ( false !== strpos( $token, ':' ) ) {
				list( $name, $tpl ) = array_map( 'trim', explode( ':', $token, 2 ) );

				if ( '' !== $name ) {
					$params[ $name ] = strtr( $tpl, $vars );
				}

				continue;
			}

			$params[] = strtr( $token, $vars );
		}

		return $params;
	}

	/* --------------------------------------------------------------------- */
	/* ارسال آزمایشی از تنظیمات                                               */
	/* --------------------------------------------------------------------- */

	/**
	 * پیامک آزمایشی یک رویداد به شمارهٔ دلخواه، همان لحظه و بدون صف؛ با
	 * تنظیماتی که هنوز در فرم است (پیش از ذخیره).
	 */
	public static function ajax_test() {
		check_ajax_referer( 'ardal-wallet-sms-test', 'nonce' );

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json_error( array( 'message' => 'دسترسی لازم را ندارید.' ), 403 );
		}

		if ( ! self::available() ) {
			wp_send_json_error( array( 'message' => 'نیاس لاگین نسخهٔ ۱.۴ یا بالاتر فعال نیست.' ) );
		}

		$event = isset( $_POST['event'] ) ? sanitize_key( wp_unslash( $_POST['event'] ) ) : self::EVENT_TOPUP;
		$event = isset( self::events()[ $event ] ) ? $event : self::EVENT_TOPUP;
		$phone = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
		$phone = function_exists( 'nias_normalize_mobile' ) ? nias_normalize_mobile( $phone ) : $phone;

		if ( '' === $phone ) {
			wp_send_json_error( array( 'message' => 'شمارهٔ موبایل معتبر نیست.' ) );
		}

		// مقادیر فعلی فرم روی همین درخواست می‌نشینند تا مدیر پیش از ذخیره امتحان کند.
		$key  = 'sms_' . $event;
		$form = array();

		foreach ( array( '_pattern', '_vars', '_text' ) as $suffix ) {
			if ( isset( $_POST[ $suffix ] ) ) {
				$form[ $key . $suffix ] = 'text' === ltrim( $suffix, '_' )
					? sanitize_textarea_field( wp_unslash( $_POST[ $suffix ] ) )
					: sanitize_text_field( wp_unslash( $_POST[ $suffix ] ) );
			}
		}

		$filter = static function ( $value ) use ( $form ) {
			return array_merge( is_array( $value ) ? $value : array(), $form );
		};

		add_filter( 'option_' . Ardal_Wallet::OPTION, $filter );

		$user   = wp_get_current_user();
		$result = self::dispatch( $phone, $event, self::vars( $user, 100000, 250000 ), $user->ID );

		remove_filter( 'option_' . Ardal_Wallet::OPTION, $filter );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => 'ارسال نشد: ' . $result->get_error_message() ) );
		}

		wp_send_json_success( array( 'message' => 'پیامک آزمایشی فرستاده شد. نتیجه در «گزارش پیام‌ها»ی نیاس هم ثبت شده است.' ) );
	}
}
