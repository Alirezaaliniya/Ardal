<?php
/**
 * برداشت از کیف پول: درخواست کاربر، بررسی مدیر و واریز به حساب بانکی.
 *
 * جریان کار:
 *   ۱. کاربر مبلغ و شمارهٔ شبا را می‌فرستد. همان لحظه مبلغ با یک تراکنش
 *      «برداشت» از موجودی کسر می‌شود؛ یعنی اعتبار «نگه داشته» می‌شود و تا
 *      تعیین تکلیف، کاربر نمی‌تواند همان پول را در خرید هم خرج کند.
 *   ۲. مدیر در گزارش کیف پول درخواست را «پرداخت شد» (با کد پیگیری بانک) یا
 *      «رد شد» (با دلیل) می‌کند. رد شدن، و لغو درخواست توسط خود کاربر، مبلغ
 *      را با تراکنش «بازگشت برداشت» به کیف پول برمی‌گرداند.
 *
 * کش‌بگ اعتبار هدیه است، نه پول واریزی کاربر؛ به‌طور پیش‌فرض قابل برداشت
 * نیست و برداشت هم آن را «مصرف» نمی‌کند (دفتر کش‌بگ دست‌نخورده می‌ماند).
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Wallet_Withdraw {

	const STATUS_PENDING  = 'pending';
	const STATUS_PAID     = 'paid';
	const STATUS_REJECTED = 'rejected';
	const STATUS_CANCELED = 'canceled';

	/**
	 * آخرین شبای کاربر، برای پر شدن خودکار فرم بعدی.
	 */
	const META_IBAN = '_ardal_wallet_iban';

	public static function init() {
		add_action( 'wp_ajax_ardal_wallet_withdraw', array( __CLASS__, 'ajax_request' ) );
		add_action( 'wp_ajax_ardal_wallet_withdraw_cancel', array( __CLASS__, 'ajax_cancel' ) );
		add_action( 'admin_post_ardal_wallet_withdraw_process', array( __CLASS__, 'handle_process' ) );
		add_shortcode( 'ardal_wallet_withdraw', array( __CLASS__, 'shortcode' ) );
	}

	/**
	 * [ardal_wallet_withdraw] — فرم برداشت و فهرست درخواست‌های کاربر.
	 *
	 * @return string
	 */
	public static function shortcode() {
		return self::form() . self::user_requests();
	}

	/**
	 * @return string
	 */
	public static function table() {
		global $wpdb;

		return $wpdb->prefix . 'ardal_wallet_withdrawals';
	}

	/**
	 * جدول درخواست‌ها؛ از Ardal_Wallet::install صدا زده می‌شود.
	 *
	 * @return string دستور CREATE برای dbDelta.
	 */
	public static function schema() {
		global $wpdb;

		$table   = self::table();
		$collate = $wpdb->get_charset_collate();

		return "CREATE TABLE {$table} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			user_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
			amount DECIMAL(20,4) NOT NULL DEFAULT 0,
			iban VARCHAR(34) NOT NULL DEFAULT '',
			holder VARCHAR(120) NOT NULL DEFAULT '',
			status VARCHAR(16) NOT NULL DEFAULT 'pending',
			tx_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
			back_tx_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
			reference VARCHAR(64) NOT NULL DEFAULT '',
			admin_note VARCHAR(255) NOT NULL DEFAULT '',
			processed_by BIGINT UNSIGNED NOT NULL DEFAULT 0,
			created_at DATETIME NOT NULL DEFAULT '1970-01-01 00:00:00',
			processed_at DATETIME NULL DEFAULT NULL,
			PRIMARY KEY  (id),
			KEY user_status (user_id, status),
			KEY status_created (status, created_at)
		) {$collate};";
	}

	/**
	 * @return bool
	 */
	public static function enabled() {
		return Ardal_Wallet::is_enabled() && Ardal_Wallet::on( 'withdraw_enabled' );
	}

	/**
	 * @return array وضعیت => برچسب.
	 */
	public static function status_names() {
		return array(
			self::STATUS_PENDING  => 'در انتظار بررسی',
			self::STATUS_PAID     => 'پرداخت شد',
			self::STATUS_REJECTED => 'رد شد',
			self::STATUS_CANCELED => 'لغو شد',
		);
	}

	/* --------------------------------------------------------------------- */
	/* شبا                                                                    */
	/* --------------------------------------------------------------------- */

	/**
	 * شبای ورودی به شکل استاندارد «IR» و ۲۴ رقم؛ ارقام فارسی، فاصله و خط تیره
	 * پذیرفته می‌شوند و «IR» هم اگر نیامده باشد اضافه می‌شود.
	 *
	 * @param string $raw
	 * @return string
	 */
	public static function normalize_iban( $raw ) {
		$value = strtoupper( Ardal_Wallet::latin_digits( (string) $raw ) );
		$value = preg_replace( '/[^0-9A-Z]/', '', $value );

		if ( preg_match( '/^\d{24}$/', $value ) ) {
			$value = 'IR' . $value;
		}

		return $value;
	}

	/**
	 * شبای ایرانی معتبر است؟ (قالب و رقم کنترل ISO 13616، مود ۹۷)
	 *
	 * @param string $iban شکل استاندارد.
	 * @return bool
	 */
	public static function valid_iban( $iban ) {
		if ( ! preg_match( '/^IR\d{24}$/', $iban ) ) {
			return false;
		}

		// چهار نویسهٔ اول به انتها می‌رود و حروف به عدد (I=18، R=27).
		$moved = substr( $iban, 4 ) . '1827' . substr( $iban, 2, 2 );
		$rest  = 0;

		foreach ( str_split( $moved, 7 ) as $chunk ) {
			$rest = (int) ( ( $rest . $chunk ) % 97 );
		}

		return 1 === $rest;
	}

	/**
	 * شبا برای نمایش: چهارتایی جدا، با چند رقم پنهان در فهرست‌ها.
	 *
	 * @param string $iban
	 * @param bool   $mask
	 * @return string
	 */
	public static function format_iban( $iban, $mask = false ) {
		$chars = str_split( (string) $iban );
		$last  = count( $chars ) - 4;

		// پنهان‌سازی روی آرایهٔ نویسه‌ها؛ chunk_split بایتی، «•» چندبایتی را می‌شکست.
		if ( $mask && count( $chars ) > 10 ) {
			foreach ( $chars as $i => $char ) {
				if ( $i >= 6 && $i < $last ) {
					$chars[ $i ] = '•';
				}
			}
		}

		return implode( ' ', array_map( 'implode', array_chunk( $chars, 4 ) ) );
	}

	/* --------------------------------------------------------------------- */
	/* مبلغ قابل برداشت                                                       */
	/* --------------------------------------------------------------------- */

	/**
	 * کش‌بگ فعالِ مصرف‌نشده.
	 *
	 * @param int $user_id
	 * @return float
	 */
	public static function cashback_left( $user_id ) {
		$left = 0.0;

		foreach ( Ardal_Wallet::active_cashback( $user_id ) as $row ) {
			$left += (float) $row['amount'] - (float) $row['used'];
		}

		return Ardal_Wallet::round( $left );
	}

	/**
	 * بیشترین مبلغی که همین حالا قابل برداشت است.
	 *
	 * @param int $user_id
	 * @return float
	 */
	public static function withdrawable( $user_id = 0 ) {
		$user_id = $user_id ? (int) $user_id : get_current_user_id();
		$balance = Ardal_Wallet::balance( $user_id );

		if ( ! Ardal_Wallet::on( 'withdraw_cashback' ) ) {
			$balance -= self::cashback_left( $user_id );
		}

		$max = (float) Ardal_Wallet::option( 'max_withdraw' );

		if ( $max > 0 ) {
			$balance = min( $balance, $max );
		}

		return max( 0, Ardal_Wallet::round( $balance ) );
	}

	/* --------------------------------------------------------------------- */
	/* خواندن                                                                 */
	/* --------------------------------------------------------------------- */

	/**
	 * @param int $id
	 * @return array|null
	 */
	public static function get( $id ) {
		global $wpdb;

		$table = self::table();

		$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d", (int) $id ), ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		return $row ? self::normalize( $row ) : null;
	}

	/**
	 * @param array $row
	 * @return array
	 */
	private static function normalize( $row ) {
		$row['id']           = (int) $row['id'];
		$row['user_id']      = (int) $row['user_id'];
		$row['amount']       = (float) $row['amount'];
		$row['tx_id']        = (int) $row['tx_id'];
		$row['back_tx_id']   = (int) $row['back_tx_id'];
		$row['processed_by'] = (int) $row['processed_by'];

		return $row;
	}

	/**
	 * درخواست‌های یک کاربر، تازه‌ترین اول.
	 *
	 * @param int    $user_id
	 * @param string $status خالی = همه.
	 * @param int    $limit
	 * @return array
	 */
	public static function for_user( $user_id, $status = '', $limit = 10 ) {
		global $wpdb;

		$table = self::table();
		$where = $wpdb->prepare( 'user_id = %d', (int) $user_id );

		if ( '' !== $status ) {
			$where .= $wpdb->prepare( ' AND status = %s', $status );
		}

		$rows = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table} WHERE {$where} ORDER BY id DESC LIMIT %d", max( 1, (int) $limit ) ), ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared

		return array_map( array( __CLASS__, 'normalize' ), (array) $rows );
	}

	/**
	 * فهرست مدیریت.
	 *
	 * @param string $status
	 * @param int    $page
	 * @param int    $per
	 * @return array rows، total.
	 */
	public static function admin_list( $status = self::STATUS_PENDING, $page = 1, $per = 20 ) {
		global $wpdb;

		$table = self::table();
		$where = '' !== $status ? $wpdb->prepare( 'status = %s', $status ) : '1=1';

		// phpcs:disable WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared
		$total = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table} WHERE {$where}" );
		$rows  = $wpdb->get_results(
			$wpdb->prepare( "SELECT * FROM {$table} WHERE {$where} ORDER BY id DESC LIMIT %d OFFSET %d", $per, ( max( 1, $page ) - 1 ) * $per ),
			ARRAY_A
		);
		// phpcs:enable

		return array(
			'rows'  => array_map( array( __CLASS__, 'normalize' ), (array) $rows ),
			'total' => $total,
		);
	}

	/**
	 * تعداد درخواست‌های در انتظار؛ برای نشان منوی پیشخوان.
	 *
	 * @return int
	 */
	public static function pending_count() {
		global $wpdb;

		$table = self::table();

		return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE status = %s", self::STATUS_PENDING ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	}

	/* --------------------------------------------------------------------- */
	/* ثبت، لغو، بررسی                                                        */
	/* --------------------------------------------------------------------- */

	/**
	 * ثبت درخواست برداشت و نگه‌داشتن مبلغ.
	 *
	 * @param int    $user_id
	 * @param float  $amount
	 * @param string $iban
	 * @return int|WP_Error شناسهٔ درخواست.
	 */
	public static function request( $user_id, $amount, $iban ) {
		global $wpdb;

		if ( ! self::enabled() ) {
			return new WP_Error( 'off', 'برداشت از کیف پول در حال حاضر فعال نیست.' );
		}

		$user_id = (int) $user_id;
		$amount  = Ardal_Wallet::round( $amount );
		$iban    = self::normalize_iban( $iban );
		$min     = (float) Ardal_Wallet::option( 'min_withdraw' );

		if ( $amount <= 0 ) {
			return new WP_Error( 'amount', 'مبلغ برداشت را وارد کن.' );
		}

		if ( $min > 0 && $amount < $min ) {
			return new WP_Error( 'min', sprintf( 'کمترین مبلغ برداشت %s است.', Ardal_Wallet::plain_money( $min ) ) );
		}

		if ( ! self::valid_iban( $iban ) ) {
			return new WP_Error( 'iban', 'شمارهٔ شبا معتبر نیست؛ «IR» و ۲۴ رقم را کامل و درست وارد کن.' );
		}

		// یک درخواست باز در هر زمان؛ وگرنه مدیر با چند ردیف هم‌پوشان سروکار داشت.
		if ( self::for_user( $user_id, self::STATUS_PENDING, 1 ) ) {
			return new WP_Error( 'pending', 'یک درخواست برداشت در انتظار بررسی داری؛ تا تعیین تکلیف آن، درخواست تازه ثبت نمی‌شود.' );
		}

		$available = self::withdrawable( $user_id );

		if ( $amount > $available + 0.0001 ) {
			$max = (float) Ardal_Wallet::option( 'max_withdraw' );

			return new WP_Error(
				'low',
				$max > 0 && $amount > $max
					? sprintf( 'بیشترین مبلغ هر برداشت %s است.', Ardal_Wallet::plain_money( $max ) )
					: sprintf( 'مبلغ قابل برداشت تو %s است.', Ardal_Wallet::plain_money( $available ) )
			);
		}

		$user   = get_userdata( $user_id );
		$holder = $user ? trim( $user->first_name . ' ' . $user->last_name ) : '';

		$ok = $wpdb->insert( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			self::table(),
			array(
				'user_id'    => $user_id,
				'amount'     => $amount,
				'iban'       => $iban,
				'holder'     => mb_substr( '' !== $holder ? $holder : ( $user ? $user->display_name : '' ), 0, 120 ),
				'status'     => self::STATUS_PENDING,
				'created_at' => current_time( 'mysql' ),
			),
			array( '%d', '%f', '%s', '%s', '%s', '%s' )
		);

		if ( ! $ok ) {
			return new WP_Error( 'db', 'ثبت درخواست ممکن نشد. دوباره تلاش کن.' );
		}

		$id = (int) $wpdb->insert_id;

		// کسر زیر قفل کیف پول؛ اگر موجودی در این فاصله کم شده باشد، درخواست برمی‌گردد.
		$tx = Ardal_Wallet::debit(
			$user_id,
			$amount,
			array(
				'type'          => Ardal_Wallet::TYPE_WITHDRAW,
				'ref_id'        => $id,
				'note'          => sprintf( 'درخواست برداشت شمارهٔ %d', $id ),
				'keep_cashback' => true,
			)
		);

		if ( is_wp_error( $tx ) ) {
			$wpdb->delete( self::table(), array( 'id' => $id ), array( '%d' ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

			return $tx;
		}

		$wpdb->update( self::table(), array( 'tx_id' => (int) $tx ), array( 'id' => $id ), array( '%d' ), array( '%d' ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

		update_user_meta( $user_id, self::META_IBAN, $iban );

		self::notify_admin( $id );

		/**
		 * پس از ثبت درخواست برداشت.
		 *
		 * @param int $id
		 * @param int $user_id
		 */
		do_action( 'ardal_wallet_withdraw_requested', $id, $user_id );

		return $id;
	}

	/**
	 * بستن یک درخواست در انتظار. «پرداخت شد» مبلغ را نهایی می‌کند؛ «رد شد» و
	 * «لغو شد» آن را به کیف پول برمی‌گردانند.
	 *
	 * تغییر وضعیت با UPDATE شرطی (فقط اگر هنوز در انتظار است) انجام می‌شود تا
	 * دو کلیک هم‌زمان مدیر، یا لغو کاربر هم‌زمان با تأیید مدیر، مبلغ را دو بار
	 * برنگرداند.
	 *
	 * @param int    $id
	 * @param string $status paid | rejected | canceled
	 * @param array  $args   reference، note، by.
	 * @return true|WP_Error
	 */
	public static function close( $id, $status, $args = array() ) {
		global $wpdb;

		if ( ! in_array( $status, array( self::STATUS_PAID, self::STATUS_REJECTED, self::STATUS_CANCELED ), true ) ) {
			return new WP_Error( 'status', 'وضعیت نامعتبر است.' );
		}

		$row = self::get( $id );

		if ( ! $row ) {
			return new WP_Error( 'missing', 'درخواست پیدا نشد.' );
		}

		$args = wp_parse_args( $args, array( 'reference' => '', 'note' => '', 'by' => get_current_user_id() ) );

		$claimed = $wpdb->update( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			self::table(),
			array(
				'status'       => $status,
				'reference'    => mb_substr( sanitize_text_field( $args['reference'] ), 0, 64 ),
				'admin_note'   => mb_substr( sanitize_text_field( $args['note'] ), 0, 250 ),
				'processed_by' => (int) $args['by'],
				'processed_at' => current_time( 'mysql' ),
			),
			array(
				'id'     => (int) $id,
				'status' => self::STATUS_PENDING,
			),
			array( '%s', '%s', '%s', '%d', '%s' ),
			array( '%d', '%s' )
		);

		if ( ! $claimed ) {
			return new WP_Error( 'closed', 'این درخواست پیش‌تر بررسی شده است.' );
		}

		if ( self::STATUS_PAID !== $status && $row['tx_id'] ) {
			$back = Ardal_Wallet::credit(
				$row['user_id'],
				$row['amount'],
				array(
					'type'   => Ardal_Wallet::TYPE_WITHDRAW_BACK,
					'ref_id' => $row['id'],
					'note'   => self::STATUS_CANCELED === $status
						? sprintf( 'لغو درخواست برداشت شمارهٔ %d', $row['id'] )
						: sprintf( 'بازگشت درخواست برداشت ردشدهٔ شمارهٔ %d', $row['id'] ),
				)
			);

			if ( ! is_wp_error( $back ) ) {
				$wpdb->update( self::table(), array( 'back_tx_id' => (int) $back ), array( 'id' => (int) $id ), array( '%d' ), array( '%d' ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			}
		}

		$extra = array(
			'{iban}'   => self::format_iban( $row['iban'], true ),
			'{ref}'    => sanitize_text_field( $args['reference'] ),
			'{reason}' => sanitize_text_field( $args['note'] ),
		);

		if ( self::STATUS_PAID === $status ) {
			Ardal_Wallet_Sms::send( $row['user_id'], $row['amount'], Ardal_Wallet_Sms::EVENT_WITHDRAW_PAID, $extra );
		} elseif ( self::STATUS_REJECTED === $status ) {
			Ardal_Wallet_Sms::send( $row['user_id'], $row['amount'], Ardal_Wallet_Sms::EVENT_WITHDRAW_REJECTED, $extra );
		}

		/**
		 * پس از بسته شدن درخواست برداشت.
		 *
		 * @param int    $id
		 * @param string $status
		 * @param array  $row
		 */
		do_action( 'ardal_wallet_withdraw_closed', $id, $status, $row );

		return true;
	}

	/**
	 * ایمیل به مدیر سایت برای درخواست تازه.
	 *
	 * @param int $id
	 */
	private static function notify_admin( $id ) {
		if ( ! Ardal_Wallet::on( 'withdraw_notify' ) ) {
			return;
		}

		$row  = self::get( $id );
		$user = $row ? get_userdata( $row['user_id'] ) : null;

		if ( ! $row || ! $user ) {
			return;
		}

		$lines = array(
			sprintf( 'درخواست برداشت تازه از کیف پول — شمارهٔ %d', $id ),
			'',
			'کاربر: ' . $user->display_name . ' (' . $user->user_email . ')',
			'مبلغ: ' . Ardal_Wallet::plain_money( $row['amount'] ),
			'شبا: ' . self::format_iban( $row['iban'] ),
			'',
			'بررسی: ' . Ardal_Wallet_Report::url( array( 'w_status' => self::STATUS_PENDING ) ) . '#ardal-withdraw',
		);

		wp_mail( get_option( 'admin_email' ), sprintf( '[%s] درخواست برداشت از کیف پول', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) ), implode( "\n", $lines ) );
	}

	/* --------------------------------------------------------------------- */
	/* ایجکس کاربر                                                            */
	/* --------------------------------------------------------------------- */

	public static function ajax_request() {
		check_ajax_referer( 'ardal-wallet', 'nonce' );

		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => 'برای برداشت ابتدا وارد حساب شو.' ), 401 );
		}

		$amount = Ardal_Wallet::to_number( isset( $_POST['amount'] ) ? wp_unslash( $_POST['amount'] ) : '' ); // phpcs:ignore WordPress.Security.ValidationSanitization.InputNotSanitized -- to_number پاک‌سازی می‌کند.
		$iban   = isset( $_POST['iban'] ) ? sanitize_text_field( wp_unslash( $_POST['iban'] ) ) : '';
		$result = self::request( get_current_user_id(), $amount, $iban );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'code' => $result->get_error_code(), 'message' => $result->get_error_message() ), 400 );
		}

		wp_send_json_success(
			array(
				'id'       => $result,
				'message'  => 'درخواست برداشت ثبت شد و مبلغ تا بررسی از موجودی کسر شد.',
				'redirect' => Ardal_Wallet::account_url(),
			)
		);
	}

	public static function ajax_cancel() {
		check_ajax_referer( 'ardal-wallet', 'nonce' );

		$id  = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
		$row = $id ? self::get( $id ) : null;

		if ( ! $row || get_current_user_id() !== $row['user_id'] ) {
			wp_send_json_error( array( 'message' => 'درخواست پیدا نشد.' ), 404 );
		}

		$result = self::close( $id, self::STATUS_CANCELED, array( 'by' => get_current_user_id() ) );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ), 400 );
		}

		wp_send_json_success( array( 'message' => 'درخواست لغو شد و مبلغ به کیف پولت برگشت.' ) );
	}

	/* --------------------------------------------------------------------- */
	/* پیشخوان                                                                */
	/* --------------------------------------------------------------------- */

	public static function handle_process() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( 'دسترسی لازم را ندارید.', '', array( 'response' => 403 ) );
		}

		check_admin_referer( 'ardal_wallet_withdraw_process' );

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- بالا بررسی شد.
		$id     = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
		$op     = isset( $_POST['op'] ) ? sanitize_key( wp_unslash( $_POST['op'] ) ) : '';
		$ref    = isset( $_POST['reference'] ) ? sanitize_text_field( wp_unslash( $_POST['reference'] ) ) : '';
		$note   = isset( $_POST['note'] ) ? sanitize_text_field( wp_unslash( $_POST['note'] ) ) : '';
		$back   = isset( $_POST['back'] ) ? esc_url_raw( wp_unslash( $_POST['back'] ) ) : '';
		// phpcs:enable

		$back   = wp_validate_redirect( $back, Ardal_Wallet_Report::url() );
		$status = 'paid' === $op ? self::STATUS_PAID : ( 'reject' === $op ? self::STATUS_REJECTED : '' );
		$result = '' === $status
			? new WP_Error( 'op', '' )
			: self::close( $id, $status, array( 'reference' => $ref, 'note' => $note ) );

		wp_safe_redirect( add_query_arg( 'ardal_done', is_wp_error( $result ) ? 'withdraw_fail' : 'withdraw_' . $op, $back ) . '#ardal-withdraw' );
		exit;
	}

	/**
	 * بخش «درخواست‌های برداشت» در صفحهٔ گزارش کیف پول.
	 */
	public static function render_admin() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$status = isset( $_GET['w_status'] ) ? sanitize_key( wp_unslash( $_GET['w_status'] ) ) : self::STATUS_PENDING;
		$page   = isset( $_GET['w_paged'] ) ? max( 1, absint( $_GET['w_paged'] ) ) : 1;
		// phpcs:enable

		$status = 'all' === $status || isset( self::status_names()[ $status ] ) ? $status : self::STATUS_PENDING;
		$list   = self::admin_list( 'all' === $status ? '' : $status, $page, 20 );
		$pages  = (int) ceil( $list['total'] / 20 );
		$back   = remove_query_arg( array( 'ardal_done' ) );
		?>
		<div class="ardal-wr__box" id="ardal-withdraw">
			<div class="ardal-wr__box-head">
				<strong>درخواست‌های برداشت</strong>
				<span class="ardal-wr__count">
					<?php echo esc_html( Ardal_Helpers::fa_digits( self::pending_count() ) ); ?> درخواست در انتظار
					<?php if ( ! self::enabled() ) : ?>
						· برداشت در تنظیمات خاموش است
					<?php endif; ?>
				</span>
			</div>

			<div class="ardal-wr__chips">
				<?php foreach ( array( 'all' => 'همه' ) + self::status_names() as $key => $label ) : ?>
					<a class="ardal-wr__chip<?php echo $key === $status ? ' is-on' : ''; ?>" href="<?php echo esc_url( add_query_arg( array( 'w_status' => $key, 'w_paged' => false ) ) . '#ardal-withdraw' ); ?>"><?php echo esc_html( $label ); ?></a>
				<?php endforeach; ?>
			</div>

			<?php if ( ! $list['rows'] ) : ?>
				<p class="ardal-wr__hint">درخواستی در این وضعیت نیست.</p>
			<?php else : ?>
				<div class="ardal-wr__scroll">
					<table class="widefat striped ardal-wr__table">
						<thead>
							<tr>
								<th>شماره</th>
								<th>کاربر</th>
								<th>مبلغ</th>
								<th>شبا و صاحب حساب</th>
								<th>تاریخ</th>
								<th>وضعیت</th>
								<th>بررسی</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $list['rows'] as $row ) : ?>
								<?php $user = get_userdata( $row['user_id'] ); ?>
								<tr>
									<td><?php echo esc_html( $row['id'] ); ?></td>
									<td>
										<?php if ( $user ) : ?>
											<a href="<?php echo esc_url( get_edit_user_link( $user->ID ) ); ?>"><?php echo esc_html( $user->display_name ); ?></a>
											<br /><small>موجودی: <?php echo wp_kses_post( wc_price( Ardal_Wallet::balance( $user->ID, false ) ) ); ?></small>
										<?php else : ?>
											—
										<?php endif; ?>
									</td>
									<td><strong><?php echo wp_kses_post( wc_price( $row['amount'] ) ); ?></strong></td>
									<td>
										<code dir="ltr" class="ardal-wr__iban"><?php echo esc_html( self::format_iban( $row['iban'] ) ); ?></code>
										<?php if ( '' !== $row['holder'] ) : ?>
											<br /><small><?php echo esc_html( $row['holder'] ); ?></small>
										<?php endif; ?>
									</td>
									<td><?php echo esc_html( Ardal_Wallet::date( $row['created_at'], true ) ); ?></td>
									<td>
										<span class="ardal-wl-pill ardal-wl-pill--<?php echo esc_attr( self::STATUS_PAID === $row['status'] ? 'success' : ( self::STATUS_PENDING === $row['status'] ? 'pending' : 'failed' ) ); ?>"><?php echo esc_html( self::status_names()[ $row['status'] ] ?? $row['status'] ); ?></span>
										<?php if ( '' !== $row['reference'] ) : ?>
											<br /><small>پیگیری: <?php echo esc_html( $row['reference'] ); ?></small>
										<?php endif; ?>
										<?php if ( '' !== $row['admin_note'] ) : ?>
											<br /><small><?php echo esc_html( $row['admin_note'] ); ?></small>
										<?php endif; ?>
									</td>
									<td>
										<?php if ( self::STATUS_PENDING === $row['status'] ) : ?>
											<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="ardal-wr__wform">
												<input type="hidden" name="action" value="ardal_wallet_withdraw_process" />
												<input type="hidden" name="id" value="<?php echo esc_attr( $row['id'] ); ?>" />
												<input type="hidden" name="back" value="<?php echo esc_attr( $back ); ?>" />
												<?php wp_nonce_field( 'ardal_wallet_withdraw_process' ); ?>
												<input type="text" name="reference" placeholder="کد پیگیری واریز" />
												<input type="text" name="note" placeholder="دلیل رد (برای رد)" />
												<span class="ardal-wr__wform-actions">
													<button type="submit" name="op" value="paid" class="button button-primary" data-ardal-confirm-click="مبلغ به حساب کاربر واریز شده است؟">پرداخت شد</button>
													<button type="submit" name="op" value="reject" class="button" data-ardal-confirm-click="درخواست رد شود و مبلغ به کیف پول کاربر برگردد؟">رد</button>
												</span>
											</form>
										<?php else : ?>
											<small><?php echo esc_html( $row['processed_at'] ? Ardal_Wallet::date( $row['processed_at'], true ) : '—' ); ?></small>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

				<?php if ( $pages > 1 ) : ?>
					<div class="ardal-wr__pager tablenav">
						<?php
						echo wp_kses_post(
							paginate_links(
								array(
									'base'      => add_query_arg( 'w_paged', '%#%' ) . '#ardal-withdraw',
									'format'    => '',
									'current'   => $page,
									'total'     => $pages,
									'prev_text' => '‹',
									'next_text' => '›',
								)
							)
						);
						?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php
	}

	/* --------------------------------------------------------------------- */
	/* فرم سمت کاربر (مشترک: قالب اردال، الانو و ویجت)                        */
	/* --------------------------------------------------------------------- */

	/**
	 * فرم برداشت؛ رفتارش با ardal-wallet.js است.
	 *
	 * @param array $args class، button، field_class، input_class، cta (HTML دکمه).
	 * @return string
	 */
	public static function form( $args = array() ) {
		if ( ! self::enabled() || ! is_user_logged_in() ) {
			return '';
		}

		$a = wp_parse_args(
			$args,
			array(
				'class'       => 'ard-wl-wd',
				'field_class' => 'ard-wl-wd__field',
				'input_class' => 'ard-wl-card__input',
				'button'      => 'برداشت',
				'cta'           => '',
				'show_balance'  => true,
				'balance_class' => 'ard-wl-wd__balance',
				'notice_class'  => 'ard-wl-wd__notice',
				'hint_class'    => 'ard-wl-wd__hint',
				'after'         => '',
			)
		);

		Ardal_Wallet_Render::assets();

		$user_id   = get_current_user_id();
		$available = self::withdrawable( $user_id );
		$cashback  = Ardal_Wallet::on( 'withdraw_cashback' ) ? 0.0 : self::cashback_left( $user_id );
		$pending   = self::for_user( $user_id, self::STATUS_PENDING, 1 );
		$min       = (float) Ardal_Wallet::option( 'min_withdraw' );
		$iban      = (string) get_user_meta( $user_id, self::META_IBAN, true );

		ob_start();
		?>
		<div class="<?php echo esc_attr( $a['class'] ); ?>" data-ard-withdraw>
			<?php if ( $a['show_balance'] ) : ?>
				<div class="<?php echo esc_attr( $a['balance_class'] ); ?>">
					<span>موجودی کیف پول</span>
					<strong data-ard-wallet-balance><?php echo Ardal_Wallet::money( Ardal_Wallet::balance( $user_id ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					<?php if ( $cashback > 0 ) : ?>
						<small>قابل برداشت: <?php echo Ardal_Wallet::money( $available ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> (کش‌بگ برداشت‌شدنی نیست)</small>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $pending ) : ?>
				<p class="<?php echo esc_attr( $a['notice_class'] ); ?>">یک درخواست برداشت <?php echo Ardal_Wallet::money( $pending[0]['amount'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> در انتظار بررسی داری؛ تا تعیین تکلیف آن، درخواست تازه ثبت نمی‌شود.</p>
			<?php elseif ( $available <= 0 || ( $min > 0 && $available < $min ) ) : ?>
				<p class="<?php echo esc_attr( $a['notice_class'] ); ?>">موجودی قابل برداشت برای ثبت درخواست کافی نیست<?php echo $min > 0 ? esc_html( '؛ کمترین مبلغ برداشت ' . Ardal_Wallet::plain_money( $min ) . ' است' ) : ''; ?>.</p>
			<?php else : ?>
				<label class="<?php echo esc_attr( $a['field_class'] ); ?>">
					<span>مبلغ برداشت (تومان)</span>
					<input type="text" class="<?php echo esc_attr( $a['input_class'] ); ?>" data-ard-withdraw-amount inputmode="numeric" autocomplete="off" placeholder="<?php echo esc_attr( 'تا ' . Ardal_Helpers::price( $available ) . ' تومان' ); ?>" />
				</label>

				<label class="<?php echo esc_attr( $a['field_class'] ); ?>">
					<span>شماره شبا</span>
					<input type="text" class="<?php echo esc_attr( $a['input_class'] ); ?>" data-ard-withdraw-iban dir="ltr" autocomplete="off" maxlength="40" value="<?php echo esc_attr( "" !== $iban ? self::format_iban( $iban ) : "" ); ?>" placeholder="مثلا IR 602521500000055588900001" />
				</label>

				<?php if ( '' !== trim( Ardal_Wallet::option( 'withdraw_note' ) ) ) : ?>
					<p class="<?php echo esc_attr( $a['hint_class'] ); ?>"><?php echo esc_html( Ardal_Wallet::option( 'withdraw_note' ) ); ?></p>
				<?php endif; ?>

				<?php
				echo '' !== $a['cta'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					? $a['cta']
					: '<button type="button" class="ard-wl-btn ard-wl-btn--accent" data-ard-withdraw-submit>' . esc_html( $a['button'] ) . '</button>';
				?>
			<?php endif; ?>

			<?php echo $a['after']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML خود افزونه. ?>
		</div>
		<?php

		return (string) ob_get_clean();
	}

	/**
	 * وضعیت درخواست برداشتِ پشت یک تراکنش «برداشت»، برای ستون وضعیت تاریخچه.
	 * خود تراکنش همان لحظه موفق ثبت می‌شود؛ کاربر باید وضعیت واریز را ببیند.
	 *
	 * @param array $tx تراکنش normalize‌شده.
	 * @return array|null key (success|pending|failed)، label.
	 */
	public static function tx_status( $tx ) {
		if ( Ardal_Wallet::TYPE_WITHDRAW !== $tx['type'] || empty( $tx['ref_id'] ) ) {
			return null;
		}

		$row = self::get( (int) $tx['ref_id'] );

		if ( ! $row ) {
			return null;
		}

		$keys = array(
			self::STATUS_PENDING  => 'pending',
			self::STATUS_PAID     => 'success',
			self::STATUS_REJECTED => 'failed',
			self::STATUS_CANCELED => 'failed',
		);

		return array(
			'key'   => $keys[ $row['status'] ] ?? 'pending',
			'label' => self::status_names()[ $row['status'] ] ?? $row['status'],
		);
	}

	/**
	 * فهرست درخواست‌های اخیر کاربر با دکمهٔ لغو برای در انتظارها.
	 *
	 * @param string $class
	 * @return string
	 */
	public static function user_requests( $class = 'ard-wl-wdl' ) {
		if ( ! self::enabled() || ! is_user_logged_in() ) {
			return '';
		}

		$rows = self::for_user( get_current_user_id(), '', 5 );

		if ( ! $rows ) {
			return '';
		}

		ob_start();
		?>
		<div class="<?php echo esc_attr( $class ); ?>">
			<?php foreach ( $rows as $row ) : ?>
				<div class="ard-wl-wdl__row" data-ard-withdraw-row="<?php echo esc_attr( $row['id'] ); ?>">
					<span class="ard-wl-wdl__main">
						<strong>برداشت <?php echo Ardal_Wallet::money( $row['amount'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
						<small dir="ltr"><?php echo esc_html( self::format_iban( $row['iban'], true ) ); ?></small>
					</span>
					<span class="ard-wl-wdl__meta">
						<span class="ard-wl-pill ard-wl-pill--<?php echo esc_attr( self::STATUS_PAID === $row['status'] ? 'success' : ( self::STATUS_PENDING === $row['status'] ? 'pending' : 'failed' ) ); ?>"><?php echo esc_html( self::status_names()[ $row['status'] ] ?? $row['status'] ); ?></span>
						<small><?php echo esc_html( Ardal_Wallet::date( $row['created_at'] ) ); ?></small>
						<?php if ( '' !== $row['reference'] ) : ?>
							<small>پیگیری: <?php echo esc_html( $row['reference'] ); ?></small>
						<?php endif; ?>
						<?php if ( '' !== $row['admin_note'] && self::STATUS_REJECTED === $row['status'] ) : ?>
							<small><?php echo esc_html( $row['admin_note'] ); ?></small>
						<?php endif; ?>
					</span>
					<?php if ( self::STATUS_PENDING === $row['status'] ) : ?>
						<button type="button" class="ard-wl-wdl__cancel" data-ard-withdraw-cancel="<?php echo esc_attr( $row['id'] ); ?>">لغو درخواست</button>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php

		return (string) ob_get_clean();
	}
}
