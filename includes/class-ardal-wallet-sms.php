<?php
/**
 * پیامک کیف پول: خبر شارژ و کش‌بگ به کاربر.
 *
 * اردال پنل پیامکی ندارد و نباید داشته باشد؛ هر سایت با پنل خودش کار
 * می‌کند. پس اینجا فقط «متن» و «زمان» تعیین می‌شود و ارسال از دو راه است:
 *
 *   ۱. اکشن ardal_wallet_sms — برای افزونه یا کد اختصاصی که خودش می‌فرستد.
 *   ۲. وب‌سرویس عمومی در تنظیمات — نشانی پنل، بدون کدنویسی.
 *
 * هیچ‌کدام تنظیم نشده باشد، چیزی ارسال نمی‌شود و خطایی هم نیست.
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
	 * @param string $event
	 * @return bool
	 */
	public static function is_enabled( $event = '' ) {
		if ( ! Ardal_Wallet::on( 'sms_enabled' ) ) {
			return false;
		}

		$map = array(
			self::EVENT_TOPUP    => 'sms_topup',
			self::EVENT_ADMIN    => 'sms_admin',
			self::EVENT_CASHBACK => 'sms_cashback',
		);

		return isset( $map[ $event ] ) ? Ardal_Wallet::on( $map[ $event ] ) : true;
	}

	/**
	 * شمارهٔ موبایل کاربر: شمارهٔ صورتحساب ووکامرس، بعد متاهای رایج
	 * افزونه‌های ورود با موبایل.
	 *
	 * @param int $user_id
	 * @return string
	 */
	public static function phone( $user_id ) {
		$phone = '';

		foreach ( array( 'billing_phone', 'mobile', 'phone_number', 'digits_phone', 'user_phone', 'mobile_number' ) as $key ) {
			$value = (string) get_user_meta( (int) $user_id, $key, true );

			if ( '' !== trim( $value ) ) {
				$phone = $value;
				break;
			}
		}

		/**
		 * شمارهٔ موبایل کاربر برای پیامک کیف پول.
		 *
		 * @param string $phone
		 * @param int    $user_id
		 */
		$phone = (string) apply_filters( 'ardal_wallet_sms_phone', $phone, (int) $user_id );

		return (string) preg_replace( '/[^0-9+]/', '', Ardal_Wallet::latin_digits( $phone ) );
	}

	/**
	 * @param string $template
	 * @param array  $vars
	 * @return string
	 */
	public static function render( $template, $vars ) {
		$replace = array();

		foreach ( $vars as $key => $value ) {
			$replace[ '{' . $key . '}' ] = (string) $value;
		}

		return trim( strtr( (string) $template, $replace ) );
	}

	/**
	 * @param int    $user_id
	 * @param float  $amount
	 * @param string $event
	 * @return bool آیا پیامکی بیرون رفت؟
	 */
	public static function send( $user_id, $amount, $event = self::EVENT_TOPUP ) {
		if ( ! self::is_enabled( $event ) ) {
			return false;
		}

		$user = get_userdata( (int) $user_id );

		if ( ! $user ) {
			return false;
		}

		$phone = self::phone( $user->ID );

		if ( '' === $phone ) {
			return false;
		}

		$vars = array(
			'name'    => $user->display_name ? $user->display_name : $user->user_login,
			'amount'  => Ardal_Wallet::plain_money( $amount ),
			'balance' => Ardal_Wallet::plain_money( Ardal_Wallet::balance( $user->ID, false ) ),
			'site'    => wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ),
			'phone'   => $phone,
			'event'   => $event,
		);

		/**
		 * متن نهایی پیامک.
		 *
		 * @param string $text
		 * @param int    $user_id
		 * @param array  $vars
		 * @param string $event
		 */
		$text = (string) apply_filters( 'ardal_wallet_sms_text', self::render( Ardal_Wallet::option( 'sms_text' ), $vars ), $user->ID, $vars, $event );

		if ( '' === trim( $text ) ) {
			return false;
		}

		/**
		 * نقطهٔ اتصال هر پنل پیامکی.
		 *
		 * @param string $phone
		 * @param string $text
		 * @param array  $context user_id، amount، event، vars.
		 */
		do_action(
			'ardal_wallet_sms',
			$phone,
			$text,
			array(
				'user_id' => $user->ID,
				'amount'  => (float) $amount,
				'event'   => $event,
				'vars'    => $vars,
			)
		);

		self::webhook( $phone, $text, $vars );

		return true;
	}

	/**
	 * ارسال از وب‌سرویس تنظیمات. نشانی و بدنه همان متغیرهای متن را
	 * می‌پذیرند، به‌علاوهٔ {text}.
	 *
	 * @param string $phone
	 * @param string $text
	 * @param array  $vars
	 * @return string کد پاسخ یا off/error.
	 */
	private static function webhook( $phone, $text, $vars ) {
		$url = trim( Ardal_Wallet::option( 'sms_url' ) );

		if ( '' === $url ) {
			return 'off';
		}

		$vars['text']  = $text;
		$vars['phone'] = $phone;

		// داخل نشانی همه‌چیز باید انکد شود، وگرنه کوئری‌استرینگ می‌شکند.
		$encoded = array_map( 'rawurlencode', array_map( 'strval', $vars ) );
		$method  = 'POST' === Ardal_Wallet::option( 'sms_method' ) ? 'POST' : 'GET';
		$args    = array(
			'timeout'     => 15,
			'method'      => $method,
			'redirection' => 2,
		);

		if ( 'POST' === $method ) {
			$body = trim( Ardal_Wallet::option( 'sms_body' ) );

			if ( '' !== $body ) {
				if ( in_array( $body[0], array( '{', '[' ), true ) ) {
					// داخل JSON مقدارها باید escape شوند، نه انکد نشانی.
					$json = array();

					foreach ( $vars as $key => $value ) {
						$json[ $key ] = substr( wp_json_encode( (string) $value ), 1, -1 );
					}

					$args['headers'] = array( 'Content-Type' => 'application/json; charset=utf-8' );
					$args['body']    = self::render( $body, $json );
				} else {
					parse_str( self::render( $body, $encoded ), $fields );
					$args['body'] = $fields;
				}
			}
		}

		$response = wp_remote_request( self::render( $url, $encoded ), $args );

		return is_wp_error( $response ) ? 'error' : (string) wp_remote_retrieve_response_code( $response );
	}
}
