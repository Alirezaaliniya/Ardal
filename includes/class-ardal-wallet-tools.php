<?php
/**
 * ابزارهای مدیریتی کیف پول: تغییر دستی یک کاربر، شارژ گروهی و کش‌بگ دستی
 * سفارش‌های گذشته.
 *
 * هر سه از صفحهٔ گزارش اجرا می‌شوند و هر عمل برای هر کاربر تراکنش جدا
 * می‌گیرد، پس سابقهٔ کیف پول همیشه نشان می‌دهد اعتبار از کجا آمده.
 *
 * دو کار گروهی پیش از اجرا «برآورد» نشان می‌دهند؛ شارژ گروهی برگشت‌پذیر
 * نیست و مدیر باید اول تعداد و جمع را ببیند.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Wallet_Tools {

	/**
	 * سقف کاربر در یک شارژ گروهی؛ جلوی تایم‌اوت را می‌گیرد.
	 */
	const MAX_USERS = 2000;

	/**
	 * سقف سفارش در یک بررسی/ثبت کش‌بگ دستی.
	 */
	const MAX_SCAN = 200;

	/**
	 * متای سفارش: کش‌بگ از پیشخوان داده شده.
	 */
	const META_MANUAL = '_ardal_wallet_cashback_manual';

	public static function init() {
		add_action( 'admin_post_ardal_wallet_adjust', array( __CLASS__, 'handle_adjust' ) );
		add_action( 'admin_post_ardal_wallet_bulk', array( __CLASS__, 'handle_bulk' ) );
		add_action( 'admin_post_ardal_wallet_grant', array( __CLASS__, 'handle_grant' ) );
	}

	/* --------------------------------------------------------------------- */
	/* تغییر دستی یک کاربر                                                    */
	/* --------------------------------------------------------------------- */

	/**
	 * افزودن، کسر، تنظیم یا کش‌بگ دستی.
	 *
	 * @param int    $user_id
	 * @param string $op     add | subtract | set | cashback
	 * @param float  $amount
	 * @param string $note
	 * @param bool   $sms
	 * @return true|WP_Error
	 */
	public static function adjust( $user_id, $op, $amount, $note = '', $sms = true ) {
		$user_id = (int) $user_id;
		$amount  = Ardal_Wallet::round( $amount );

		if ( ! $user_id || ! get_userdata( $user_id ) ) {
			return new WP_Error( 'invalid', 'کاربر معتبر نیست.' );
		}

		if ( 'cashback' === $op ) {
			if ( $amount <= 0 ) {
				return new WP_Error( 'invalid', 'مبلغ معتبر نیست.' );
			}

			$result = Ardal_Wallet::add_cashback( $user_id, $amount, 0, '' !== $note ? $note : 'کش‌بگ دستی' );

			if ( ! is_wp_error( $result ) && $sms ) {
				Ardal_Wallet_Sms::send( $user_id, $amount, Ardal_Wallet_Sms::EVENT_ADMIN );
			}

			return is_wp_error( $result ) ? $result : true;
		}

		if ( 'set' === $op ) {
			if ( $amount < 0 ) {
				return new WP_Error( 'invalid', 'مبلغ معتبر نیست.' );
			}

			$diff   = Ardal_Wallet::round( $amount - Ardal_Wallet::balance( $user_id ) );
			$op     = $diff >= 0 ? 'add' : 'subtract';
			$amount = abs( $diff );

			if ( $amount <= 0 ) {
				return true;
			}
		}

		if ( $amount <= 0 ) {
			return new WP_Error( 'invalid', 'مبلغ معتبر نیست.' );
		}

		$args = array(
			'type' => Ardal_Wallet::TYPE_ADMIN,
			'note' => '' !== $note ? $note : 'تغییر توسط مدیر',
		);

		$result = 'subtract' === $op
			? Ardal_Wallet::debit( $user_id, $amount, $args )
			: Ardal_Wallet::credit( $user_id, $amount, $args );

		// فقط افزایش خبر دارد؛ کسر کردن پیامک ندارد.
		if ( ! is_wp_error( $result ) && 'subtract' !== $op && $sms ) {
			Ardal_Wallet_Sms::send( $user_id, $amount, Ardal_Wallet_Sms::EVENT_ADMIN );
		}

		return is_wp_error( $result ) ? $result : true;
	}

	public static function handle_adjust() {
		self::guard( 'ardal_wallet_adjust' );

		$back  = self::back_url();
		$query = isset( $_POST['user_query'] ) ? sanitize_text_field( wp_unslash( $_POST['user_query'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- guard() نانس را بررسی کرده است.
		$ids   = '' !== $query ? array_values( array_filter( Ardal_Wallet_Report::resolve_users( $query ), 'get_userdata' ) ) : array();

		if ( count( $ids ) > 1 ) {
			self::redirect( $back, 'many' );
		}

		$user_id = $ids ? (int) $ids[0] : 0;
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		$op     = isset( $_POST['op'] ) ? sanitize_key( wp_unslash( $_POST['op'] ) ) : 'add';
		$note   = isset( $_POST['note'] ) ? sanitize_text_field( wp_unslash( $_POST['note'] ) ) : '';
		$amount = Ardal_Wallet::to_number( isset( $_POST['amount'] ) ? wp_unslash( $_POST['amount'] ) : '' ); // phpcs:ignore WordPress.Security.ValidationSanitization.InputNotSanitized
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		if ( ! in_array( $op, array( 'add', 'subtract', 'set', 'cashback' ), true ) ) {
			self::redirect( $back, 'invalid' );
		}

		$result = self::adjust( $user_id, $op, $amount, $note );
		$back   = $user_id ? add_query_arg( 'user', $user_id, $back ) : $back;

		if ( is_wp_error( $result ) ) {
			self::redirect( $back, 'ardal_wallet_low' === $result->get_error_code() ? 'low' : 'invalid' );
		}

		self::redirect( $back, 'cashback' === $op ? 'cashback' : 'adjusted' );
	}

	/* --------------------------------------------------------------------- */
	/* شارژ گروهی                                                             */
	/* --------------------------------------------------------------------- */

	/**
	 * @return array
	 */
	public static function targets() {
		return array(
			'list'  => 'فهرست کاربران (دستی)',
			'role'  => 'همهٔ کاربران یک نقش',
			'buyer' => 'هر کسی که دست‌کم یک خرید تکمیل‌شده دارد',
			'all'   => 'همهٔ کاربران سایت',
		);
	}

	/**
	 * شناسهٔ کاربرهای هدف.
	 *
	 * @param string $target
	 * @param string $list
	 * @param string $role
	 * @return int[]
	 */
	public static function resolve( $target, $list = '', $role = '' ) {
		global $wpdb;

		$ids = array();

		if ( 'list' === $target ) {
			foreach ( (array) preg_split( '/[\s,،;]+/u', (string) $list ) as $part ) {
				$part = trim( (string) $part );

				if ( '' === $part ) {
					continue;
				}

				if ( is_numeric( Ardal_Wallet::latin_digits( $part ) ) ) {
					$ids[] = (int) Ardal_Wallet::latin_digits( $part );
					continue;
				}

				$user = is_email( $part ) ? get_user_by( 'email', $part ) : get_user_by( 'login', $part );
				$user = $user ? $user : get_user_by( 'slug', $part );

				if ( $user ) {
					$ids[] = (int) $user->ID;
				}
			}
		} elseif ( 'role' === $target && '' !== $role ) {
			$ids = get_users( array( 'role' => $role, 'fields' => 'ID', 'number' => self::MAX_USERS ) );
		} elseif ( 'buyer' === $target ) {
			$orders = wc_get_orders(
				array(
					'status' => array( 'wc-completed' ),
					// سقف دارد تا روی فروشگاه‌های بزرگ حافظه تمام نشود.
					'limit'  => 5000,
					'return' => 'ids',
					'type'   => 'shop_order',
				)
			);

			foreach ( (array) $orders as $order_id ) {
				$order = wc_get_order( $order_id );

				if ( $order && $order->get_customer_id() && ! Ardal_Wallet_Checkout::is_topup_order( $order ) ) {
					$ids[ $order->get_customer_id() ] = (int) $order->get_customer_id();
				}

				if ( count( $ids ) >= self::MAX_USERS ) {
					break;
				}
			}
		} elseif ( 'all' === $target ) {
			$ids = get_users( array( 'fields' => 'ID', 'number' => self::MAX_USERS ) );
		}

		$ids = array_values( array_unique( array_filter( array_map( 'absint', (array) $ids ) ) ) );
		$ids = array_values( array_filter( $ids, 'get_userdata' ) );

		return array_slice( $ids, 0, self::MAX_USERS );
	}

	public static function handle_bulk() {
		self::guard( 'ardal_wallet_bulk' );

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- guard() نانس را بررسی کرده است.
		$target  = isset( $_POST['target'] ) ? sanitize_key( wp_unslash( $_POST['target'] ) ) : 'list';
		$role    = isset( $_POST['role'] ) ? sanitize_key( wp_unslash( $_POST['role'] ) ) : '';
		$list    = isset( $_POST['users'] ) ? sanitize_textarea_field( wp_unslash( $_POST['users'] ) ) : '';
		$op      = isset( $_POST['op'] ) ? sanitize_key( wp_unslash( $_POST['op'] ) ) : 'add';
		$note    = isset( $_POST['note'] ) ? sanitize_text_field( wp_unslash( $_POST['note'] ) ) : '';
		$sms     = ! empty( $_POST['sms'] );
		$confirm = ! empty( $_POST['confirm'] );
		$amount  = Ardal_Wallet::round( Ardal_Wallet::to_number( isset( $_POST['amount'] ) ? wp_unslash( $_POST['amount'] ) : '' ) ); // phpcs:ignore WordPress.Security.ValidationSanitization.InputNotSanitized
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$back = Ardal_Wallet_Report::url( array( 'range' => 'all' ) );

		if ( ! isset( self::targets()[ $target ] ) || ! in_array( $op, array( 'add', 'cashback' ), true ) || $amount <= 0 ) {
			self::redirect( $back, 'bulk_invalid' );
		}

		$ids = self::resolve( $target, $list, $role );

		if ( ! $ids ) {
			self::redirect( $back, 'bulk_none' );
		}

		if ( ! $confirm ) {
			self::redirect(
				$back,
				'bulk_preview',
				array(
					'n'   => count( $ids ),
					'sum' => $amount * count( $ids ),
				)
			);
		}

		$note = '' !== $note ? $note : 'شارژ گروهی توسط مدیر';
		$done = 0;
		$fail = 0;
		$sent = 0;

		foreach ( $ids as $user_id ) {
			$result = 'cashback' === $op
				? Ardal_Wallet::add_cashback( $user_id, $amount, 0, $note )
				: Ardal_Wallet::credit( $user_id, $amount, array( 'type' => Ardal_Wallet::TYPE_ADMIN, 'note' => $note ) );

			if ( is_wp_error( $result ) ) {
				++$fail;
				continue;
			}

			++$done;

			if ( $sms && Ardal_Wallet_Sms::send( $user_id, $amount, Ardal_Wallet_Sms::EVENT_ADMIN ) ) {
				++$sent;
			}
		}

		self::redirect(
			$back,
			'bulk_done',
			array(
				'n'    => $done,
				'fail' => $fail,
				'sms'  => $sent,
				'sum'  => $amount * $done,
			)
		);
	}

	/* --------------------------------------------------------------------- */
	/* کش‌بگ سفارش‌های گذشته                                                   */
	/* --------------------------------------------------------------------- */

	/**
	 * @return array
	 */
	public static function grant_modes() {
		return array(
			'settings' => 'طبق تنظیمات کش‌بگ',
			'percent'  => 'درصدی از مبلغ سفارش',
			'fixed'    => 'مبلغ ثابت برای هر سفارش',
		);
	}

	/**
	 * @return array کلید بدون wc- => نام.
	 */
	public static function order_statuses() {
		$out = array();

		foreach ( wc_get_order_statuses() as $key => $label ) {
			$out[ substr( $key, 3 ) ] = $label;
		}

		return $out;
	}

	/**
	 * پارامترهای ابزار از نشانی (فقط خواندن؛ ثبت جداگانه نانس دارد).
	 *
	 * @return array
	 */
	public static function grant_query() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$get = wp_unslash( $_GET );
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		$statuses = isset( $get['g_status'] ) ? (array) $get['g_status'] : array();
		$statuses = array_values( array_intersect( array_map( 'sanitize_key', $statuses ), array_keys( self::order_statuses() ) ) );

		if ( ! $statuses ) {
			$statuses = array( Ardal_Wallet::option( 'cashback_status' ) );
		}

		$mode = isset( $get['g_mode'] ) ? sanitize_key( $get['g_mode'] ) : 'settings';

		return array(
			'from'     => isset( $get['g_from'] ) ? Ardal_Wallet_Report::clean_date( $get['g_from'] ) : '',
			'to'       => isset( $get['g_to'] ) ? Ardal_Wallet_Report::clean_date( $get['g_to'] ) : '',
			'user'     => isset( $get['g_user'] ) ? sanitize_text_field( $get['g_user'] ) : '',
			'statuses' => $statuses,
			'min'      => isset( $get['g_min'] ) ? max( 0, Ardal_Wallet::to_number( $get['g_min'] ) ) : 0,
			'mode'     => isset( self::grant_modes()[ $mode ] ) ? $mode : 'settings',
			'value'    => isset( $get['g_value'] ) ? max( 0, Ardal_Wallet::to_number( $get['g_value'] ) ) : 0,
			'skip'     => empty( $get['g_all'] ),
			'scan'     => ! empty( $get['g_scan'] ),
		);
	}

	/**
	 * سفارش‌های بازه با مبلغ پیشنهادی هر کدام.
	 *
	 * @param array $q
	 * @return array|WP_Error
	 */
	public static function grant_scan( $q ) {
		$args = array(
			'limit'    => self::MAX_SCAN,
			'paginate' => true,
			'status'   => $q['statuses'],
			'orderby'  => 'date',
			'order'    => 'DESC',
			'type'     => 'shop_order',
		);

		if ( '' !== $q['from'] && '' !== $q['to'] ) {
			$args['date_created'] = $q['from'] . '...' . $q['to'];
		} elseif ( '' !== $q['from'] ) {
			$args['date_created'] = '>=' . $q['from'];
		} elseif ( '' !== $q['to'] ) {
			$args['date_created'] = '<=' . $q['to'];
		}

		if ( '' !== $q['user'] ) {
			$ids = array_values( array_filter( Ardal_Wallet_Report::resolve_users( $q['user'] ), 'get_userdata' ) );

			if ( ! $ids ) {
				return new WP_Error( 'user', 'کاربری با این عبارت پیدا نشد.' );
			}

			if ( count( $ids ) > 1 ) {
				return new WP_Error( 'many', 'چند کاربر با این عبارت پیدا شد؛ شناسه یا ایمیل دقیق را وارد کنید.' );
			}

			$args['customer_id'] = $ids[0];
		}

		$result = wc_get_orders( $args );
		$orders = isset( $result->orders ) ? $result->orders : array();
		$rows   = array();

		foreach ( $orders as $order ) {
			if ( ! $order instanceof WC_Order || ( $q['min'] > 0 && (float) $order->get_total() < $q['min'] ) ) {
				continue;
			}

			$row = self::grant_row( $order, $q );

			if ( $q['skip'] && ! $row['can'] ) {
				continue;
			}

			$rows[] = $row;
		}

		return array(
			'rows'  => $rows,
			'total' => isset( $result->total ) ? (int) $result->total : count( $orders ),
			'shown' => count( $orders ),
		);
	}

	/**
	 * @param WC_Order $order
	 * @param array    $q
	 * @return array
	 */
	private static function grant_row( $order, $q ) {
		$user_id = (int) $order->get_customer_id();
		$granted = (float) $order->get_meta( Ardal_Wallet_Checkout::META_CASHBACK );
		$amount  = self::grant_plan( $order, $q );
		$reason  = '';

		if ( ! $user_id ) {
			$reason = 'سفارش مهمان؛ حساب کاربری ندارد.';
		} elseif ( $granted > 0 ) {
			$reason = 'قبلاً کش‌بگ گرفته است.';
		} elseif ( Ardal_Wallet_Checkout::is_topup_order( $order ) ) {
			$reason = 'سفارش افزایش اعتبار است.';
		} elseif ( $amount <= 0 ) {
			$reason = 'settings' === $q['mode'] ? 'طبق تنظیمات، کش‌بگی به این سفارش نمی‌رسد.' : 'مبلغ محاسبه‌شده صفر است.';
		}

		return array(
			'id'      => $order->get_id(),
			'number'  => $order->get_order_number(),
			'date'    => $order->get_date_created() ? $order->get_date_created()->date( 'Y-m-d H:i:s' ) : '',
			'status'  => $order->get_status(),
			'total'   => (float) $order->get_total(),
			'user_id' => $user_id,
			'granted' => $granted,
			'amount'  => $amount,
			'can'     => '' === $reason,
			'reason'  => $reason,
		);
	}

	/**
	 * @param WC_Order $order
	 * @param array    $q
	 * @return float
	 */
	public static function grant_plan( $order, $q ) {
		if ( 'fixed' === $q['mode'] ) {
			return Ardal_Wallet::round( $q['value'] );
		}

		if ( 'percent' === $q['mode'] ) {
			return Ardal_Wallet::round( (float) $order->get_total() * min( 100, $q['value'] ) / 100 );
		}

		// حالت «طبق تنظیمات» حتی وقتی کش‌بگ خودکار خاموش است هم عدد می‌دهد؛
		// مدیر عمداً همین را خواسته.
		$was = Ardal_Wallet::option( 'cashback' );

		if ( '1' !== $was ) {
			add_filter( 'option_' . Ardal_Wallet::OPTION, array( __CLASS__, 'force_cashback_on' ) );
		}

		$amount = Ardal_Wallet_Checkout::cashback_for( $order );

		remove_filter( 'option_' . Ardal_Wallet::OPTION, array( __CLASS__, 'force_cashback_on' ) );

		return Ardal_Wallet::round( $amount );
	}

	/**
	 * @param mixed $value
	 * @return array
	 */
	public static function force_cashback_on( $value ) {
		$value             = is_array( $value ) ? $value : array();
		$value['cashback'] = '1';

		return $value;
	}

	/**
	 * کش‌بگ یک سفارش؛ همان نوع و انقضای کش‌بگ خودکار، و فقط یک بار.
	 *
	 * @param WC_Order|int $order
	 * @param float        $amount
	 * @return true|WP_Error
	 */
	public static function grant( $order, $amount ) {
		$order = is_numeric( $order ) ? wc_get_order( $order ) : $order;

		if ( ! $order instanceof WC_Order ) {
			return new WP_Error( 'order', 'سفارش پیدا نشد.' );
		}

		$user_id = (int) $order->get_customer_id();
		$amount  = Ardal_Wallet::round( $amount );

		if ( ! $user_id || ! get_userdata( $user_id ) ) {
			return new WP_Error( 'guest', 'این سفارش حساب کاربری ندارد.' );
		}

		if ( (float) $order->get_meta( Ardal_Wallet_Checkout::META_CASHBACK ) > 0 ) {
			return new WP_Error( 'done', 'برای این سفارش قبلاً کش‌بگ ثبت شده است.' );
		}

		if ( $amount <= 0 ) {
			return new WP_Error( 'amount', 'مبلغ معتبر نیست.' );
		}

		$tx = Ardal_Wallet::add_cashback( $user_id, $amount, $order->get_id(), sprintf( 'کش‌بگ دستی سفارش %s', $order->get_order_number() ) );

		if ( is_wp_error( $tx ) ) {
			return $tx;
		}

		$order->update_meta_data( Ardal_Wallet_Checkout::META_CASHBACK, $amount );
		$order->update_meta_data( self::META_MANUAL, current_time( 'mysql' ) );
		$order->add_order_note( sprintf( 'کش‌بگ %s از پیشخوان به کیف پول مشتری اضافه شد.', Ardal_Wallet::plain_money( $amount ) ) );
		$order->save();

		return true;
	}

	public static function handle_grant() {
		self::guard( 'ardal_wallet_grant' );

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- guard() نانس را بررسی کرده است.
		$back    = self::back_url();
		$ids     = isset( $_POST['orders'] ) ? array_map( 'absint', (array) wp_unslash( $_POST['orders'] ) ) : array();
		$amounts = isset( $_POST['amount'] ) ? (array) wp_unslash( $_POST['amount'] ) : array(); // phpcs:ignore WordPress.Security.ValidationSanitization.InputNotSanitized -- هر مقدار با to_number پاک می‌شود.
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$ids = array_values( array_unique( array_filter( $ids ) ) );

		if ( ! $ids ) {
			self::redirect( $back, 'grant_none' );
		}

		$left = max( 0, count( $ids ) - self::MAX_SCAN );
		$ok   = 0;
		$fail = 0;
		$sum  = 0.0;

		foreach ( array_slice( $ids, 0, self::MAX_SCAN ) as $id ) {
			$amount = Ardal_Wallet::to_number( isset( $amounts[ $id ] ) ? $amounts[ $id ] : '' );
			$result = self::grant( $id, $amount );

			if ( is_wp_error( $result ) ) {
				++$fail;
				continue;
			}

			++$ok;
			$sum += $amount;
		}

		self::redirect(
			$back,
			'grant_done',
			array(
				'n'    => $ok,
				'fail' => $fail,
				'left' => $left,
				'sum'  => $sum,
			)
		);
	}

	/* --------------------------------------------------------------------- */
	/* کمکی                                                                   */
	/* --------------------------------------------------------------------- */

	/**
	 * دسترسی و نانس.
	 *
	 * @param string $action
	 */
	private static function guard( $action ) {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( 'دسترسی لازم را ندارید.', '', array( 'response' => 403 ) );
		}

		check_admin_referer( $action );
	}

	/**
	 * نشانی بازگشت؛ فقط به صفحهٔ گزارش خودمان.
	 *
	 * @return string
	 */
	private static function back_url() {
		$back = isset( $_POST['back'] ) ? esc_url_raw( wp_unslash( $_POST['back'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

		return wp_validate_redirect( $back, Ardal_Wallet_Report::url( array( 'range' => 'all' ) ) );
	}

	/**
	 * @param string $back
	 * @param string $done
	 * @param array  $more
	 */
	private static function redirect( $back, $done, $more = array() ) {
		wp_safe_redirect( add_query_arg( array_merge( array( 'ardal_done' => $done ), $more ), $back ) );
		exit;
	}

	/**
	 * پیام نتیجهٔ همهٔ ابزارها در بالای گزارش.
	 */
	public static function notice() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$done = isset( $_GET['ardal_done'] ) ? sanitize_key( wp_unslash( $_GET['ardal_done'] ) ) : '';
		$n    = isset( $_GET['n'] ) ? absint( $_GET['n'] ) : 0;
		$fail = isset( $_GET['fail'] ) ? absint( $_GET['fail'] ) : 0;
		$sms  = isset( $_GET['sms'] ) ? absint( $_GET['sms'] ) : 0;
		$left = isset( $_GET['left'] ) ? absint( $_GET['left'] ) : 0;
		$sum  = isset( $_GET['sum'] ) ? (float) $_GET['sum'] : 0.0;
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		if ( '' === $done ) {
			return;
		}

		$fa    = static function ( $value ) {
			return Ardal_Helpers::fa_digits( number_format_i18n( $value ) );
		};
		$money = Ardal_Wallet::plain_money( $sum );
		$map   = array(
			'adjusted'     => array( 'success', 'موجودی کاربر تغییر کرد و تراکنش ثبت شد.' ),
			'cashback'     => array( 'success', 'کش‌بگ به کیف پول کاربر اضافه شد.' ),
			'low'          => array( 'error', 'موجودی کاربر برای این کسر کافی نیست.' ),
			'invalid'      => array( 'error', 'کاربر یا مبلغ معتبر نیست.' ),
			'many'         => array( 'error', 'چند کاربر با این عبارت پیدا شد؛ شناسه یا ایمیل دقیق را وارد کنید.' ),
			'bulk_invalid' => array( 'error', 'مبلغ یا نوع انتخاب معتبر نیست.' ),
			'bulk_none'    => array( 'error', 'هیچ کاربری با این انتخاب پیدا نشد.' ),
			'bulk_preview' => array( 'warning', sprintf( 'برآورد: %s کاربر، مجموع %s. برای اجرا همان فرم را با تیک «تأیید می‌کنم» دوباره بفرستید.', $fa( $n ), $money ) ),
			'grant_none'   => array( 'warning', 'هیچ سفارشی انتخاب نشده بود.' ),
		);

		if ( 'bulk_done' === $done ) {
			$text = sprintf( 'کیف پول %s کاربر شارژ شد؛ مجموع %s.', $fa( $n ), $money );
			$text .= $sms ? sprintf( ' پیامک %s نفر در صف ارسال نیاس قرار گرفت.', $fa( $sms ) ) : '';
			$text .= $fail ? sprintf( ' %s مورد ناموفق بود.', $fa( $fail ) ) : '';
			$map[ $done ] = array( $fail ? 'warning' : 'success', $text );
		}

		if ( 'grant_done' === $done ) {
			$text = sprintf( 'کش‌بگ %s سفارش ثبت شد؛ جمعاً %s.', $fa( $n ), $money );
			$text .= $fail ? sprintf( ' %s سفارش ثبت نشد (تکراری، مهمان یا مبلغ نامعتبر).', $fa( $fail ) ) : '';
			$text .= $left ? sprintf( ' %s سفارش به‌خاطر سقف هر بار پردازش نشد؛ دوباره بررسی کنید.', $fa( $left ) ) : '';
			$map[ $done ] = array( $n ? 'success' : 'warning', $text );
		}

		if ( ! isset( $map[ $done ] ) ) {
			return;
		}

		printf(
			'<div class="notice notice-%1$s is-dismissible"><p>%2$s</p></div>',
			esc_attr( $map[ $done ][0] ),
			esc_html( $map[ $done ][1] )
		);
	}
}
