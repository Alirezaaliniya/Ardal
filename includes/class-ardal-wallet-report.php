<?php
/**
 * گزارش کیف پول در پیشخوان: فیلترها، کارت‌های خلاصه، نمودار روزانه،
 * تفکیک نوع، کاربران فعال، جدول تراکنش‌ها، خروجی CSV و ابزارهای مدیریتی.
 *
 * همهٔ اعداد از جدول تراکنش‌ها می‌آیند (منبع حقیقت)؛ متای موجودی فقط برای
 * «اعتبار در دست کاربران» خوانده می‌شود.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Wallet_Report {

	const SLUG = 'ardal-wallet-report';

	const PER_PAGE = 30;

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ), 20 );
		add_action( 'admin_post_ardal_wallet_export', array( __CLASS__, 'export_csv' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'assets' ) );
	}

	public static function register_menu() {
		if ( ! Ardal_Wallet::is_enabled() ) {
			return;
		}

		add_submenu_page(
			Ardal_Settings::PAGE,
			'گزارش کیف پول',
			'گزارش کیف پول',
			'manage_woocommerce',
			self::SLUG,
			array( __CLASS__, 'render_page' )
		);
	}

	/**
	 * @param string $hook
	 */
	public static function assets( $hook ) {
		if ( false === strpos( (string) $hook, self::SLUG ) ) {
			return;
		}

		$ver = static function ( $rel ) {
			return ARDAL_VERSION . '.' . (int) @filemtime( ARDAL_PATH . $rel ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		};

		wp_enqueue_style( 'ardal-wallet-admin', ARDAL_URL . 'assets/admin/wallet.css', array(), $ver( 'assets/admin/wallet.css' ) );
		wp_add_inline_style( 'ardal-wallet-admin', Ardal_Palette::css_vars() );
		wp_enqueue_script( 'ardal-wallet-admin', ARDAL_URL . 'assets/admin/wallet.js', array(), $ver( 'assets/admin/wallet.js' ), true );
	}

	/**
	 * @param array $args
	 * @return string
	 */
	public static function url( $args = array() ) {
		return add_query_arg( array_merge( array( 'page' => self::SLUG ), $args ), admin_url( 'admin.php' ) );
	}

	/**
	 * نشانی ویرایش سفارش، سازگار با HPOS.
	 *
	 * @param int $order_id
	 * @return string
	 */
	public static function order_url( $order_id ) {
		$order = wc_get_order( $order_id );

		return $order ? $order->get_edit_order_url() : admin_url( 'post.php?post=' . absint( $order_id ) . '&action=edit' );
	}

	/* --------------------------------------------------------------------- */
	/* فیلترها                                                                */
	/* --------------------------------------------------------------------- */

	/**
	 * @return array
	 */
	public static function ranges() {
		return array(
			'today' => 'امروز',
			'7'     => '۷ روز اخیر',
			'30'    => '۳۰ روز اخیر',
			'90'    => '۹۰ روز اخیر',
			'365'   => 'یک سال اخیر',
			'all'   => 'همه',
		);
	}

	/**
	 * @param string $value
	 * @return string Y-m-d یا خالی.
	 */
	public static function clean_date( $value ) {
		$value = trim( Ardal_Wallet::latin_digits( sanitize_text_field( (string) $value ) ) );

		return preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value ) ? $value : '';
	}

	/**
	 * فیلترها از نشانی (فقط خواندن).
	 *
	 * @return array
	 */
	public static function filters() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$get = wp_unslash( $_GET );
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		$range = isset( $get['range'] ) ? sanitize_key( $get['range'] ) : '30';
		$range = ( isset( self::ranges()[ $range ] ) || 'custom' === $range ) ? $range : '30';

		$types    = array_values( array_intersect( array_map( 'sanitize_key', (array) ( $get['type'] ?? array() ) ), array_keys( Ardal_Wallet::type_names() ) ) );
		$statuses = array_values( array_intersect( array_map( 'sanitize_key', (array) ( $get['status'] ?? array() ) ), array_keys( Ardal_Wallet::status_names() ) ) );

		$orderby = isset( $get['orderby'] ) ? sanitize_key( $get['orderby'] ) : 'id';
		$orderby = in_array( $orderby, array( 'id', 'amount', 'created_at', 'user_id' ), true ) ? $orderby : 'id';

		$number = static function ( $key ) use ( $get ) {
			return isset( $get[ $key ] ) && '' !== trim( (string) $get[ $key ] ) ? Ardal_Wallet::to_number( $get[ $key ] ) : null;
		};

		return array(
			'range'    => $range,
			'from'     => isset( $get['from'] ) ? self::clean_date( $get['from'] ) : '',
			'to'       => isset( $get['to'] ) ? self::clean_date( $get['to'] ) : '',
			'user'     => isset( $get['user'] ) ? sanitize_text_field( $get['user'] ) : '',
			'order'    => isset( $get['order_id'] ) ? absint( $get['order_id'] ) : 0,
			'types'    => $types,
			'statuses' => $statuses,
			'min'      => $number( 'min' ),
			'max'      => $number( 'max' ),
			'search'   => isset( $get['s'] ) ? sanitize_text_field( $get['s'] ) : '',
			'orderby'  => $orderby,
			'dir'      => isset( $get['dir'] ) && 'asc' === strtolower( (string) $get['dir'] ) ? 'ASC' : 'DESC',
			'paged'    => isset( $get['paged'] ) ? max( 1, absint( $get['paged'] ) ) : 1,
			'per_page' => isset( $get['per_page'] ) ? max( 5, min( 200, absint( $get['per_page'] ) ) ) : self::PER_PAGE,
		);
	}

	/**
	 * بازهٔ نهایی [شروع، پایان] به شکل MySQL.
	 *
	 * @param array $f
	 * @return array
	 */
	public static function period( $f ) {
		$now = current_time( 'timestamp' ); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested

		if ( 'custom' === $f['range'] || $f['from'] || $f['to'] ) {
			return array(
				$f['from'] ? $f['from'] . ' 00:00:00' : '',
				$f['to'] ? $f['to'] . ' 23:59:59' : '',
			);
		}

		if ( 'all' === $f['range'] ) {
			return array( '', '' );
		}

		if ( 'today' === $f['range'] ) {
			return array( gmdate( 'Y-m-d 00:00:00', $now ), gmdate( 'Y-m-d 23:59:59', $now ) );
		}

		$days = max( 1, (int) $f['range'] );

		return array(
			gmdate( 'Y-m-d 00:00:00', $now - ( $days - 1 ) * DAY_IN_SECONDS ),
			gmdate( 'Y-m-d 23:59:59', $now ),
		);
	}

	/**
	 * شرط SQL از فیلترها. همهٔ ورودی‌ها یا prepare شده‌اند یا از فهرست سفید
	 * آمده‌اند.
	 *
	 * @param array $f
	 * @return string
	 */
	public static function where( $f ) {
		global $wpdb;

		$where = array( '1=1' );

		list( $from, $to ) = self::period( $f );

		if ( $from ) {
			$where[] = $wpdb->prepare( 'created_at >= %s', $from );
		}

		if ( $to ) {
			$where[] = $wpdb->prepare( 'created_at <= %s', $to );
		}

		if ( '' !== $f['user'] ) {
			$ids = self::resolve_users( $f['user'] );

			if ( ! $ids ) {
				return '1=0';
			}

			$where[] = 'user_id IN (' . implode( ',', array_map( 'absint', $ids ) ) . ')';
		}

		if ( $f['order'] ) {
			$where[] = $wpdb->prepare( 'order_id = %d', $f['order'] );
		}

		if ( $f['types'] ) {
			$where[] = "type IN ('" . implode( "','", $f['types'] ) . "')";
		}

		if ( $f['statuses'] ) {
			$where[] = "status IN ('" . implode( "','", $f['statuses'] ) . "')";
		}

		if ( null !== $f['min'] ) {
			$where[] = $wpdb->prepare( 'ABS(amount) >= %f', $f['min'] );
		}

		if ( null !== $f['max'] ) {
			$where[] = $wpdb->prepare( 'ABS(amount) <= %f', $f['max'] );
		}

		if ( '' !== $f['search'] ) {
			$where[] = $wpdb->prepare( 'note LIKE %s', '%' . $wpdb->esc_like( $f['search'] ) . '%' );
		}

		return implode( ' AND ', $where );
	}

	/**
	 * «کاربر» به فهرست شناسه: شناسه، نام کاربری، ایمیل، نام یا موبایل.
	 *
	 * @param string $value
	 * @return int[]
	 */
	public static function resolve_users( $value ) {
		$value = trim( (string) $value );
		$latin = Ardal_Wallet::latin_digits( $value );

		if ( '' === $value ) {
			return array();
		}

		if ( ctype_digit( $latin ) && strlen( $latin ) < 9 ) {
			return array( (int) $latin );
		}

		$users = get_users(
			array(
				'search'         => '*' . $value . '*',
				'search_columns' => array( 'user_login', 'user_email', 'user_nicename', 'display_name' ),
				'fields'         => 'ID',
				'number'         => 100,
			)
		);

		// شمارهٔ موبایل در متای صورتحساب است، نه در ستون‌های جدول کاربران.
		if ( ! $users && ctype_digit( ltrim( $latin, '+' ) ) ) {
			$users = get_users(
				array(
					'meta_key'     => 'billing_phone', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
					'meta_value'   => $latin, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
					'meta_compare' => 'LIKE',
					'fields'       => 'ID',
					'number'       => 100,
				)
			);
		}

		return array_map( 'absint', (array) $users );
	}

	/* --------------------------------------------------------------------- */
	/* پرس‌وجو                                                                */
	/* --------------------------------------------------------------------- */

	/**
	 * @param array $f
	 * @return array
	 */
	public static function rows( $f ) {
		global $wpdb;

		$table  = Ardal_Wallet::table();
		$where  = self::where( $f );
		$limit  = (int) $f['per_page'];
		$offset = max( 0, ( $f['paged'] - 1 ) * $limit );

		// شرط از قبل prepare شده و ممکن است % داشته باشد؛ دوباره prepare نمی‌شود.
		// orderby و dir هم از فهرست سفید آمده‌اند.
		$rows = $wpdb->get_results( "SELECT * FROM {$table} WHERE {$where} ORDER BY {$f['orderby']} {$f['dir']}, id DESC LIMIT {$limit} OFFSET {$offset}", ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared

		return array_map( array( 'Ardal_Wallet', 'normalize' ), is_array( $rows ) ? $rows : array() );
	}

	/**
	 * @param array $f
	 * @return int
	 */
	public static function total( $f ) {
		global $wpdb;

		$table = Ardal_Wallet::table();
		$where = self::where( $f );

		return (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table} WHERE {$where}" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared
	}

	/**
	 * جمع‌بندی ورودی/خروجی به تفکیک نوع.
	 *
	 * @param array $f
	 * @return array
	 */
	public static function summary( $f ) {
		global $wpdb;

		$table = Ardal_Wallet::table();
		$where = self::where( $f );

		// phpcs:disable WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared
		$rows = $wpdb->get_results(
			"SELECT type,
				SUM( CASE WHEN amount > 0 THEN amount ELSE 0 END ) AS credit,
				SUM( CASE WHEN amount < 0 THEN -amount ELSE 0 END ) AS debit,
				COUNT(*) AS cnt
			FROM {$table} WHERE {$where} AND status IN ('success','expired') GROUP BY type",
			ARRAY_A
		);

		$out = array(
			'by_type' => array(),
			'credit'  => 0.0,
			'debit'   => 0.0,
			'count'   => 0,
		);

		foreach ( (array) $rows as $row ) {
			$out['by_type'][ $row['type'] ] = array(
				'credit' => (float) $row['credit'],
				'debit'  => (float) $row['debit'],
				'count'  => (int) $row['cnt'],
			);

			$out['credit'] += (float) $row['credit'];
			$out['debit']  += (float) $row['debit'];
			$out['count']  += (int) $row['cnt'];
		}

		$out['net']     = $out['credit'] - $out['debit'];
		$out['users']   = (int) $wpdb->get_var( "SELECT COUNT( DISTINCT user_id ) FROM {$table} WHERE {$where}" );
		$out['pending'] = (float) $wpdb->get_var( "SELECT COALESCE( SUM( amount ), 0 ) FROM {$table} WHERE {$where} AND status = 'pending'" );
		$out['failed']  = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table} WHERE {$where} AND status = 'failed'" );
		// phpcs:enable

		return $out;
	}

	/**
	 * @param array  $summary
	 * @param string $type
	 * @param string $key
	 * @return float
	 */
	public static function pick( $summary, $type, $key ) {
		return isset( $summary['by_type'][ $type ][ $key ] ) ? (float) $summary['by_type'][ $type ][ $key ] : 0.0;
	}

	/**
	 * اعتبار در دست کاربران (بدهی فروشگاه)، مستقل از فیلتر.
	 *
	 * @return float
	 */
	public static function liability() {
		global $wpdb;

		return (float) $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$wpdb->prepare( "SELECT COALESCE( SUM( meta_value + 0 ), 0 ) FROM {$wpdb->usermeta} WHERE meta_key = %s", Ardal_Wallet::META_BALANCE )
		);
	}

	/**
	 * روند روزانه.
	 *
	 * @param array $f
	 * @param int   $limit
	 * @return array
	 */
	public static function daily( $f, $limit = 45 ) {
		global $wpdb;

		$table = Ardal_Wallet::table();
		$where = self::where( $f );
		$limit = max( 1, (int) $limit );

		$rows = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared
			"SELECT DATE( created_at ) AS day,
				SUM( CASE WHEN amount > 0 THEN amount ELSE 0 END ) AS credit,
				SUM( CASE WHEN amount < 0 THEN -amount ELSE 0 END ) AS debit
			FROM {$table} WHERE {$where} AND status IN ('success','expired')
			GROUP BY DATE( created_at ) ORDER BY day DESC LIMIT {$limit}",
			ARRAY_A
		);

		return array_reverse( is_array( $rows ) ? $rows : array() );
	}

	/**
	 * @param array $f
	 * @param int   $limit
	 * @return array
	 */
	public static function top_users( $f, $limit = 10 ) {
		global $wpdb;

		$table = Ardal_Wallet::table();
		$where = self::where( $f );
		$limit = max( 1, (int) $limit );

		$rows = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQL.NotPrepared
			"SELECT user_id,
				SUM( CASE WHEN amount > 0 THEN amount ELSE 0 END ) AS credit,
				SUM( CASE WHEN amount < 0 THEN -amount ELSE 0 END ) AS debit,
				COUNT(*) AS cnt
			FROM {$table} WHERE {$where} AND status = 'success'
			GROUP BY user_id ORDER BY debit DESC, credit DESC LIMIT {$limit}",
			ARRAY_A
		);

		return is_array( $rows ) ? $rows : array();
	}

	/* --------------------------------------------------------------------- */
	/* صفحه                                                                   */
	/* --------------------------------------------------------------------- */

	public static function render_page() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( 'دسترسی لازم را ندارید.' );
		}

		$f       = self::filters();
		$summary = self::summary( $f );
		$total   = self::total( $f );
		$pages   = max( 1, (int) ceil( $total / $f['per_page'] ) );
		?>
		<div class="wrap ardal-wr" dir="rtl">
			<h1 class="ardal-wr__title">
				<span class="ardal-wr__mark" aria-hidden="true"></span>
				گزارش کیف پول
				<a class="page-title-action" href="<?php echo esc_url( admin_url( 'admin.php?page=' . Ardal_Settings::PAGE . '#wallet' ) ); ?>">تنظیمات کیف پول</a>
			</h1>

			<?php Ardal_Wallet_Tools::notice(); ?>
			<?php self::render_filters( $f ); ?>
			<?php self::render_cards( $summary ); ?>
			<?php self::render_chart( self::daily( $f ) ); ?>

			<div class="ardal-wr__cols">
				<?php self::render_breakdown( $summary ); ?>
				<?php self::render_top_users( self::top_users( $f ) ); ?>
			</div>

			<?php self::render_table( self::rows( $f ), $f, $total, $pages ); ?>

			<h2 class="ardal-wr__section">ابزارها</h2>
			<?php self::render_manage( $f ); ?>
			<?php self::render_bulk(); ?>
			<?php self::render_grant(); ?>
		</div>
		<?php
	}

	/**
	 * @param array $f
	 */
	private static function render_filters( $f ) {
		?>
		<form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>" class="ardal-wr__box ardal-wr__filters">
			<input type="hidden" name="page" value="<?php echo esc_attr( self::SLUG ); ?>" />

			<div class="ardal-wr__chips">
				<?php foreach ( self::ranges() as $key => $label ) : ?>
					<a class="ardal-wr__chip<?php echo (string) $key === $f['range'] && ! $f['from'] && ! $f['to'] ? ' is-on' : ''; ?>" href="<?php echo esc_url( add_query_arg( array( 'range' => $key, 'from' => false, 'to' => false, 'paged' => false ) ) ); ?>"><?php echo esc_html( $label ); ?></a>
				<?php endforeach; ?>
			</div>

			<div class="ardal-wr__grid">
				<label class="ardal-wr__field"><span>از تاریخ (میلادی)</span><input type="date" name="from" value="<?php echo esc_attr( $f['from'] ); ?>" /></label>
				<label class="ardal-wr__field"><span>تا تاریخ</span><input type="date" name="to" value="<?php echo esc_attr( $f['to'] ); ?>" /></label>
				<label class="ardal-wr__field"><span>کاربر</span><input type="text" name="user" value="<?php echo esc_attr( $f['user'] ); ?>" placeholder="شناسه، ایمیل، نام کاربری یا موبایل" /></label>
				<label class="ardal-wr__field"><span>شماره سفارش</span><input type="number" min="0" name="order_id" value="<?php echo $f['order'] ? esc_attr( $f['order'] ) : ''; ?>" /></label>
				<label class="ardal-wr__field"><span>کمترین مبلغ</span><input type="text" name="min" value="<?php echo null !== $f['min'] ? esc_attr( $f['min'] ) : ''; ?>" inputmode="numeric" /></label>
				<label class="ardal-wr__field"><span>بیشترین مبلغ</span><input type="text" name="max" value="<?php echo null !== $f['max'] ? esc_attr( $f['max'] ) : ''; ?>" inputmode="numeric" /></label>
				<label class="ardal-wr__field"><span>جست‌وجو در توضیح</span><input type="search" name="s" value="<?php echo esc_attr( $f['search'] ); ?>" /></label>
				<label class="ardal-wr__field"><span>ردیف در هر صفحه</span><input type="number" min="5" max="200" step="5" name="per_page" value="<?php echo esc_attr( $f['per_page'] ); ?>" /></label>
			</div>

			<div class="ardal-wr__grid ardal-wr__grid--2">
				<div class="ardal-wr__field">
					<span>نوع تراکنش</span>
					<div class="ardal-wr__checks">
						<?php foreach ( Ardal_Wallet::type_names() as $key => $label ) : ?>
							<label><input type="checkbox" name="type[]" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( $key, $f['types'], true ) ); ?> /> <?php echo esc_html( $label ); ?></label>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="ardal-wr__field">
					<span>وضعیت</span>
					<div class="ardal-wr__checks">
						<?php foreach ( Ardal_Wallet::status_names() as $key => $label ) : ?>
							<label><input type="checkbox" name="status[]" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( $key, $f['statuses'], true ) ); ?> /> <?php echo esc_html( $label ); ?></label>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<input type="hidden" name="range" value="<?php echo esc_attr( ( $f['from'] || $f['to'] ) ? 'custom' : $f['range'] ); ?>" />

			<div class="ardal-wr__actions">
				<button type="submit" class="button button-primary">اعمال فیلتر</button>
				<a class="button" href="<?php echo esc_url( self::url() ); ?>">پاک کردن</a>
				<a class="button" href="<?php echo esc_url( self::export_url( $f ) ); ?>">خروجی CSV</a>
			</div>
		</form>
		<?php
	}

	/**
	 * @param array $summary
	 */
	private static function render_cards( $summary ) {
		$fa    = static function ( $value ) {
			return Ardal_Helpers::fa_digits( number_format_i18n( $value ) );
		};
		$cards = array(
			array( 'topup', 'افزایش اعتبار', self::pick( $summary, Ardal_Wallet::TYPE_TOPUP, 'credit' ), $fa( self::pick( $summary, Ardal_Wallet::TYPE_TOPUP, 'count' ) ) . ' تراکنش' ),
			array( 'spent', 'مصرف در سفارش‌ها', self::pick( $summary, Ardal_Wallet::TYPE_ORDER, 'debit' ), $fa( self::pick( $summary, Ardal_Wallet::TYPE_ORDER, 'count' ) ) . ' سفارش' ),
			array( 'cashback', 'کش‌بگ پرداخت‌شده', self::pick( $summary, Ardal_Wallet::TYPE_CASHBACK, 'credit' ), 'منقضی‌شده: ' . Ardal_Wallet::plain_money( self::pick( $summary, Ardal_Wallet::TYPE_EXPIRE, 'debit' ) ) ),
			array( 'refund', 'بازگشت وجه', self::pick( $summary, Ardal_Wallet::TYPE_REFUND, 'credit' ), 'تغییر مدیر: ' . Ardal_Wallet::plain_money( self::pick( $summary, Ardal_Wallet::TYPE_ADMIN, 'credit' ) - self::pick( $summary, Ardal_Wallet::TYPE_ADMIN, 'debit' ) ) ),
			array( 'net', 'خالص بازه', $summary['net'], $fa( $summary['count'] ) . ' تراکنش · ' . $fa( $summary['users'] ) . ' کاربر' ),
			array( 'liability', 'اعتبار در دست کاربران', self::liability(), 'در انتظار: ' . Ardal_Wallet::plain_money( $summary['pending'] ) . ' · ناموفق: ' . $fa( $summary['failed'] ) ),
		);
		?>
		<div class="ardal-wr__cards">
			<?php foreach ( $cards as $card ) : ?>
				<div class="ardal-wr__card ardal-wr__card--<?php echo esc_attr( $card[0] ); ?>">
					<span class="ardal-wr__card-label"><?php echo esc_html( $card[1] ); ?></span>
					<span class="ardal-wr__card-value"><?php echo wp_kses_post( wc_price( $card[2] ) ); ?></span>
					<span class="ardal-wr__card-meta"><?php echo esc_html( $card[3] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * نمودار میله‌ای روزانه، بدون کتابخانهٔ بیرونی.
	 *
	 * @param array $days
	 */
	private static function render_chart( $days ) {
		$max = 0.0;

		foreach ( $days as $day ) {
			$max = max( $max, (float) $day['credit'], (float) $day['debit'] );
		}

		if ( ! $days || $max <= 0 ) {
			return;
		}
		?>
		<div class="ardal-wr__box">
			<div class="ardal-wr__box-head">
				<strong>روند روزانه</strong>
				<span class="ardal-wr__legend">
					<i class="ardal-wr__key ardal-wr__key--in"></i>واریز
					<i class="ardal-wr__key ardal-wr__key--out"></i>برداشت
				</span>
			</div>

			<div class="ardal-wr__chart">
				<?php foreach ( $days as $day ) : ?>
					<?php
					$title = sprintf(
						'%s — واریز %s، برداشت %s',
						Ardal_Wallet::date( $day['day'] ),
						Ardal_Wallet::plain_money( $day['credit'] ),
						Ardal_Wallet::plain_money( $day['debit'] )
					);
					?>
					<span class="ardal-wr__bar" title="<?php echo esc_attr( $title ); ?>">
						<i class="ardal-wr__bar-in" style="height:<?php echo esc_attr( round( (float) $day['credit'] / $max * 100, 2 ) ); ?>%"></i>
						<i class="ardal-wr__bar-out" style="height:<?php echo esc_attr( round( (float) $day['debit'] / $max * 100, 2 ) ); ?>%"></i>
					</span>
				<?php endforeach; ?>
			</div>

			<div class="ardal-wr__chart-foot">
				<span><?php echo esc_html( Ardal_Wallet::date( $days[0]['day'] ) ); ?></span>
				<span><?php echo esc_html( Ardal_Wallet::date( $days[ count( $days ) - 1 ]['day'] ) ); ?></span>
			</div>
		</div>
		<?php
	}

	/**
	 * @param array $summary
	 */
	private static function render_breakdown( $summary ) {
		?>
		<div class="ardal-wr__box">
			<div class="ardal-wr__box-head"><strong>تفکیک بر اساس نوع</strong></div>
			<table class="widefat striped ardal-wr__mini">
				<thead><tr><th>نوع</th><th>واریز</th><th>برداشت</th><th>تعداد</th></tr></thead>
				<tbody>
					<?php foreach ( Ardal_Wallet::type_names() as $key => $label ) : ?>
						<tr>
							<td><?php echo esc_html( $label ); ?></td>
							<td><?php echo wp_kses_post( wc_price( self::pick( $summary, $key, 'credit' ) ) ); ?></td>
							<td><?php echo wp_kses_post( wc_price( self::pick( $summary, $key, 'debit' ) ) ); ?></td>
							<td><?php echo esc_html( Ardal_Helpers::fa_digits( (int) self::pick( $summary, $key, 'count' ) ) ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * @param array $rows
	 */
	private static function render_top_users( $rows ) {
		?>
		<div class="ardal-wr__box">
			<div class="ardal-wr__box-head"><strong>کاربران فعال بازه</strong></div>
			<table class="widefat striped ardal-wr__mini">
				<thead><tr><th>کاربر</th><th>واریز</th><th>برداشت</th><th>موجودی</th></tr></thead>
				<tbody>
					<?php if ( ! $rows ) : ?>
						<tr><td colspan="4">داده‌ای در این بازه نیست.</td></tr>
					<?php endif; ?>
					<?php foreach ( $rows as $row ) : ?>
						<tr>
							<td><?php self::user_cell( (int) $row['user_id'] ); ?></td>
							<td><?php echo wp_kses_post( wc_price( (float) $row['credit'] ) ); ?></td>
							<td><?php echo wp_kses_post( wc_price( (float) $row['debit'] ) ); ?></td>
							<td><?php echo wp_kses_post( wc_price( Ardal_Wallet::balance( (int) $row['user_id'], false ) ) ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * @param array $rows
	 * @param array $f
	 * @param int   $total
	 * @param int   $pages
	 */
	private static function render_table( $rows, $f, $total, $pages ) {
		?>
		<div class="ardal-wr__box">
			<div class="ardal-wr__box-head">
				<strong>تراکنش‌ها</strong>
				<span class="ardal-wr__count"><?php echo esc_html( Ardal_Helpers::fa_digits( number_format_i18n( $total ) ) ); ?> ردیف</span>
			</div>

			<div class="ardal-wr__scroll">
				<table class="widefat striped ardal-wr__table">
					<thead>
						<tr>
							<th><?php echo wp_kses_post( self::sort_link( 'id', 'شناسه', $f ) ); ?></th>
							<th><?php echo wp_kses_post( self::sort_link( 'user_id', 'کاربر', $f ) ); ?></th>
							<th>نوع</th>
							<th><?php echo wp_kses_post( self::sort_link( 'amount', 'مبلغ', $f ) ); ?></th>
							<th>موجودی پس از تراکنش</th>
							<th>سفارش</th>
							<th>وضعیت</th>
							<th>توضیح</th>
							<th><?php echo wp_kses_post( self::sort_link( 'created_at', 'تاریخ', $f ) ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if ( ! $rows ) : ?>
							<tr><td colspan="9">تراکنشی با این فیلترها پیدا نشد.</td></tr>
						<?php endif; ?>

						<?php foreach ( $rows as $tx ) : ?>
							<?php $status = Ardal_Wallet::status_label( $tx['status'] ); ?>
							<tr>
								<td><?php echo esc_html( $tx['id'] ); ?></td>
								<td><?php self::user_cell( $tx['user_id'] ); ?></td>
								<td><?php echo esc_html( Ardal_Wallet::type_names()[ $tx['type'] ] ?? $tx['type'] ); ?></td>
								<td class="ardal-wl-amt ardal-wl-amt--<?php echo $tx['is_credit'] ? 'in' : 'out'; ?>"><?php echo esc_html( $tx['is_credit'] ? '+' : '−' ); ?><?php echo wp_kses_post( wc_price( $tx['abs'] ) ); ?></td>
								<td><?php echo wp_kses_post( wc_price( $tx['balance'] ) ); ?></td>
								<td>
									<?php if ( $tx['order_id'] ) : ?>
										<a href="<?php echo esc_url( self::order_url( $tx['order_id'] ) ); ?>">#<?php echo esc_html( $tx['order_id'] ); ?></a>
									<?php else : ?>
										—
									<?php endif; ?>
								</td>
								<td><span class="ardal-wl-pill ardal-wl-pill--<?php echo esc_attr( $status['key'] ); ?>"><?php echo esc_html( $status['label'] ); ?></span></td>
								<td class="ardal-wr__note"><?php echo esc_html( '' !== $tx['note'] ? $tx['note'] : '—' ); ?></td>
								<td><?php echo esc_html( Ardal_Wallet::date( $tx['created_at'], true ) ); ?></td>
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
								'base'      => add_query_arg( 'paged', '%#%' ),
								'format'    => '',
								'current'   => $f['paged'],
								'total'     => $pages,
								'prev_text' => '‹',
								'next_text' => '›',
							)
						)
					);
					?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * @param int $user_id
	 */
	private static function user_cell( $user_id ) {
		$user = $user_id ? get_userdata( $user_id ) : null;

		if ( ! $user ) {
			echo '—';

			return;
		}

		printf(
			'<a href="%1$s">%2$s</a> <a class="ardal-wr__filter-link" href="%3$s" title="فقط تراکنش‌های این کاربر">⌕</a>',
			esc_url( get_edit_user_link( $user_id ) ),
			esc_html( $user->display_name ? $user->display_name : $user->user_login ),
			esc_url( self::url( array( 'user' => $user_id, 'range' => 'all' ) ) )
		);
	}

	/**
	 * @param string $key
	 * @param string $label
	 * @param array  $f
	 * @return string
	 */
	private static function sort_link( $key, $label, $f ) {
		$is  = $f['orderby'] === $key;
		$dir = ( $is && 'DESC' === $f['dir'] ) ? 'asc' : 'desc';

		return sprintf(
			'<a href="%1$s">%2$s%3$s</a>',
			esc_url( add_query_arg( array( 'orderby' => $key, 'dir' => $dir, 'paged' => false ) ) ),
			esc_html( $label ),
			$is ? ( 'DESC' === $f['dir'] ? ' ↓' : ' ↑' ) : ''
		);
	}

	/* --------------------------------------------------------------------- */
	/* ابزارها                                                                */
	/* --------------------------------------------------------------------- */

	/**
	 * اعتبار دستی یک کاربر؛ اگر فیلتر روی یک کاربر باشد از پیش پر می‌شود.
	 *
	 * @param array $f
	 */
	private static function render_manage( $f ) {
		$user = null;

		if ( '' !== $f['user'] ) {
			$ids  = self::resolve_users( $f['user'] );
			$user = 1 === count( $ids ) ? get_userdata( $ids[0] ) : null;
		}
		?>
		<div class="ardal-wr__box">
			<div class="ardal-wr__box-head">
				<strong>اعتبار دستی یک کاربر</strong>
				<?php if ( $user ) : ?>
					<span class="ardal-wr__count">موجودی <?php echo esc_html( $user->display_name ? $user->display_name : $user->user_login ); ?>: <?php echo wp_kses_post( wc_price( Ardal_Wallet::balance( $user->ID ) ) ); ?></span>
				<?php endif; ?>
			</div>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="ardal-wr__grid">
				<input type="hidden" name="action" value="ardal_wallet_adjust" />
				<input type="hidden" name="back" value="<?php echo esc_attr( self::url( array( 'range' => 'all' ) ) ); ?>" />
				<?php wp_nonce_field( 'ardal_wallet_adjust' ); ?>

				<label class="ardal-wr__field"><span>کاربر</span><input type="text" name="user_query" value="<?php echo esc_attr( $user ? (string) $user->ID : $f['user'] ); ?>" placeholder="شناسه، ایمیل، نام کاربری یا موبایل" required /></label>
				<label class="ardal-wr__field">
					<span>عملیات</span>
					<select name="op">
						<option value="add">افزودن اعتبار</option>
						<option value="subtract">کسر از اعتبار</option>
						<option value="set">تنظیم روی مقدار</option>
						<option value="cashback">کش‌بگ (با انقضای تنظیمات)</option>
					</select>
				</label>
				<label class="ardal-wr__field"><span>مبلغ</span><input type="text" name="amount" value="" placeholder="0" inputmode="numeric" required data-ardal-money /></label>
				<label class="ardal-wr__field"><span>توضیح</span><input type="text" name="note" value="" /></label>
				<div class="ardal-wr__field ardal-wr__field--action"><span>&nbsp;</span><button type="submit" class="button button-primary">ثبت</button></div>
			</form>

			<p class="ardal-wr__hint">کش‌بگ دستی مثل کش‌بگ خودکار ثبت می‌شود (همان نوع و همان انقضا)؛ «افزودن» و «کسر» به‌نام تغییر مدیر ثبت می‌شوند و انقضا ندارند.</p>
		</div>
		<?php
	}

	private static function render_bulk() {
		$roles = wp_roles()->get_names();
		$sms   = Ardal_Wallet_Sms::is_enabled( Ardal_Wallet_Sms::EVENT_ADMIN );
		?>
		<div class="ardal-wr__box">
			<div class="ardal-wr__box-head">
				<strong>شارژ گروهی کیف پول</strong>
				<span class="ardal-wr__count">اول برآورد، بعد تأیید</span>
			</div>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="ardal-wr__grid" data-ardal-bulk>
				<input type="hidden" name="action" value="ardal_wallet_bulk" />
				<?php wp_nonce_field( 'ardal_wallet_bulk' ); ?>

				<label class="ardal-wr__field">
					<span>مخاطب</span>
					<select name="target" data-ardal-bulk-target>
						<?php foreach ( Ardal_Wallet_Tools::targets() as $key => $label ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>

				<label class="ardal-wr__field" data-ardal-bulk-show="role">
					<span>نقش</span>
					<select name="role">
						<?php foreach ( $roles as $key => $label ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( 'customer', $key ); ?>><?php echo esc_html( translate_user_role( $label ) ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>

				<label class="ardal-wr__field">
					<span>عملیات</span>
					<select name="op">
						<option value="add">افزودن اعتبار</option>
						<option value="cashback">کش‌بگ (با انقضای تنظیمات)</option>
					</select>
				</label>

				<label class="ardal-wr__field"><span>مبلغ برای هر نفر</span><input type="text" name="amount" value="" placeholder="0" inputmode="numeric" required data-ardal-money /></label>

				<label class="ardal-wr__field ardal-wr__field--wide" data-ardal-bulk-show="list">
					<span>فهرست کاربران</span>
					<textarea name="users" rows="2" placeholder="شناسه، ایمیل یا نام کاربری — با کاما یا خط تازه"></textarea>
				</label>

				<label class="ardal-wr__field ardal-wr__field--wide"><span>توضیح تراکنش</span><input type="text" name="note" value="" placeholder="مثلاً: هدیهٔ نوروزی" /></label>

				<label class="ardal-wr__check">
					<input type="checkbox" name="sms" value="1" <?php disabled( ! $sms ); ?> />
					<span>پیامک اطلاع‌رسانی هم فرستاده شود<?php echo $sms ? '' : ' <em>(پیامکِ «شارژ توسط مدیر» در تنظیمات خاموش است)</em>'; ?></span>
				</label>

				<label class="ardal-wr__check">
					<input type="checkbox" name="confirm" value="1" />
					<span>تأیید می‌کنم — این کار برگشت‌پذیر نیست</span>
				</label>

				<div class="ardal-wr__field ardal-wr__field--action"><span>&nbsp;</span><button type="submit" class="button button-primary">برآورد / اجرا</button></div>
			</form>
		</div>
		<?php
	}

	private static function render_grant() {
		$q        = Ardal_Wallet_Tools::grant_query();
		$statuses = Ardal_Wallet_Tools::order_statuses();
		?>
		<div class="ardal-wr__box" id="ardal-grant">
			<div class="ardal-wr__box-head">
				<strong>کش‌بگ سفارش‌های گذشته</strong>
				<span class="ardal-wr__count">
					تنظیمات فعلی: <?php echo esc_html( Ardal_Helpers::fa_digits( (float) Ardal_Wallet::option( 'cashback_percent' ) ) ); ?>٪ روی سفارش‌های «<?php echo esc_html( $statuses[ Ardal_Wallet::option( 'cashback_status' ) ] ?? Ardal_Wallet::option( 'cashback_status' ) ); ?>»
				</span>
			</div>

			<form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>#ardal-grant">
				<input type="hidden" name="page" value="<?php echo esc_attr( self::SLUG ); ?>" />
				<input type="hidden" name="g_scan" value="1" />

				<div class="ardal-wr__grid">
					<label class="ardal-wr__field"><span>سفارش‌ها از تاریخ</span><input type="date" name="g_from" value="<?php echo esc_attr( $q['from'] ); ?>" /></label>
					<label class="ardal-wr__field"><span>تا تاریخ</span><input type="date" name="g_to" value="<?php echo esc_attr( $q['to'] ); ?>" /></label>
					<label class="ardal-wr__field"><span>کاربر (اختیاری)</span><input type="text" name="g_user" value="<?php echo esc_attr( $q['user'] ); ?>" /></label>
					<label class="ardal-wr__field"><span>حداقل مبلغ سفارش</span><input type="text" name="g_min" value="<?php echo $q['min'] ? esc_attr( $q['min'] ) : ''; ?>" placeholder="0" /></label>
					<label class="ardal-wr__field">
						<span>مبلغ کش‌بگ</span>
						<select name="g_mode">
							<?php foreach ( Ardal_Wallet_Tools::grant_modes() as $key => $label ) : ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $q['mode'] ); ?>><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
					</label>
					<label class="ardal-wr__field"><span>درصد یا مبلغ (حالت دستی)</span><input type="text" name="g_value" value="<?php echo $q['value'] ? esc_attr( $q['value'] ) : ''; ?>" placeholder="10" /></label>
				</div>

				<div class="ardal-wr__field ardal-wr__field--wide">
					<span>وضعیت سفارش‌ها</span>
					<div class="ardal-wr__checks">
						<?php foreach ( $statuses as $key => $label ) : ?>
							<label><input type="checkbox" name="g_status[]" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( $key, $q['statuses'], true ) ); ?> /> <?php echo esc_html( $label ); ?></label>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="ardal-wr__actions">
					<label class="ardal-wr__check"><input type="checkbox" name="g_all" value="1" <?php checked( ! $q['skip'] ); ?> /> <span>سفارش‌های نامناسب (مهمان یا کش‌بگ‌گرفته) هم در فهرست باشند</span></label>
					<button type="submit" class="button button-secondary">بررسی سفارش‌ها</button>
				</div>
			</form>

			<?php
			if ( ! $q['scan'] ) {
				echo '<p class="ardal-wr__hint">بازه و شرط‌ها را انتخاب کنید و «بررسی سفارش‌ها» را بزنید؛ فهرست سفارش‌ها با مبلغ پیشنهادی نشان داده می‌شود و پیش از ثبت، هر مبلغ قابل ویرایش است.</p></div>';

				return;
			}

			$data = Ardal_Wallet_Tools::grant_scan( $q );

			if ( is_wp_error( $data ) ) {
				echo '<p class="ardal-wr__hint ardal-wr__hint--bad">' . esc_html( $data->get_error_message() ) . '</p></div>';

				return;
			}

			self::render_grant_results( $q, $data );
			?>
		</div>
		<?php
	}

	/**
	 * @param array $q
	 * @param array $data
	 */
	private static function render_grant_results( $q, $data ) {
		$rows  = $data['rows'];
		$ready = array_filter(
			$rows,
			static function ( $row ) {
				return $row['can'];
			}
		);

		$back = remove_query_arg( array( 'ardal_done', 'n', 'fail', 'left', 'sum' ) );
		?>
		<div class="ardal-wr__scan-head">
			<strong><?php echo esc_html( sprintf( '%s سفارش آمادهٔ کش‌بگ از %s ردیف', Ardal_Helpers::fa_digits( count( $ready ) ), Ardal_Helpers::fa_digits( count( $rows ) ) ) ); ?></strong>
			<span class="ardal-wr__count">جمع انتخاب‌شده: <b data-ardal-sum-target><?php echo wp_kses_post( wc_price( array_sum( wp_list_pluck( $ready, 'amount' ) ) ) ); ?></b></span>
		</div>

		<?php if ( $data['total'] > $data['shown'] ) : ?>
			<p class="ardal-wr__hint ardal-wr__hint--bad">
				<?php echo esc_html( sprintf( 'در این بازه %s سفارش هست و فقط %s تای تازه‌ترین بررسی شد. بعد از ثبت این دسته دوباره بررسی کنید یا بازه را کوچک‌تر بگیرید.', Ardal_Helpers::fa_digits( $data['total'] ), Ardal_Helpers::fa_digits( $data['shown'] ) ) ); ?>
			</p>
		<?php endif; ?>

		<?php if ( ! $rows ) : ?>
			<p class="ardal-wr__hint">سفارشی با این شرط‌ها پیدا نشد.</p>
			<?php return; ?>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-ardal-confirm="کش‌بگ سفارش‌های انتخاب‌شده به کیف پول مشتریان اضافه می‌شود. ادامه می‌دهید؟">
			<input type="hidden" name="action" value="ardal_wallet_grant" />
			<input type="hidden" name="back" value="<?php echo esc_attr( $back ); ?>" />
			<?php wp_nonce_field( 'ardal_wallet_grant' ); ?>

			<div class="ardal-wr__scroll">
				<table class="widefat striped ardal-wr__table">
					<thead>
						<tr>
							<th class="check-column"><input type="checkbox" data-ardal-check-all checked /></th>
							<th>سفارش</th>
							<th>تاریخ</th>
							<th>مشتری</th>
							<th>مبلغ سفارش</th>
							<th>کش‌بگ</th>
							<th>وضعیت</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $rows as $row ) : ?>
							<?php $user = $row['user_id'] ? get_userdata( $row['user_id'] ) : null; ?>
							<tr class="<?php echo $row['can'] ? '' : 'ardal-wr__row--off'; ?>">
								<td class="check-column">
									<?php if ( $row['can'] ) : ?>
										<input type="checkbox" name="orders[]" value="<?php echo esc_attr( $row['id'] ); ?>" data-ardal-check checked />
									<?php endif; ?>
								</td>
								<td><a href="<?php echo esc_url( self::order_url( $row['id'] ) ); ?>">#<?php echo esc_html( $row['number'] ); ?></a></td>
								<td><?php echo esc_html( Ardal_Wallet::date( $row['date'] ) ); ?></td>
								<td><?php echo $user ? esc_html( $user->display_name ? $user->display_name : $user->user_login ) : '—'; ?></td>
								<td><?php echo wp_kses_post( wc_price( $row['total'] ) ); ?></td>
								<td>
									<?php if ( $row['can'] ) : ?>
										<input type="text" class="ardal-wr__amount-input" name="amount[<?php echo esc_attr( $row['id'] ); ?>]" value="<?php echo esc_attr( $row['amount'] ); ?>" data-ardal-sum inputmode="numeric" />
									<?php elseif ( $row['granted'] > 0 ) : ?>
										<span class="ardal-wl-amt ardal-wl-amt--in">+<?php echo wp_kses_post( wc_price( $row['granted'] ) ); ?></span>
									<?php else : ?>
										—
									<?php endif; ?>
								</td>
								<td class="ardal-wr__note"><?php echo esc_html( $row['can'] ? wc_get_order_status_name( $row['status'] ) : $row['reason'] ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<?php if ( $ready ) : ?>
				<p class="ardal-wr__actions">
					<button type="submit" class="button button-primary">ثبت کش‌بگ سفارش‌های انتخاب‌شده</button>
					<span class="ardal-wr__hint">انقضا همان تنظیمات کیف پول است و هر سفارش فقط یک بار کش‌بگ می‌گیرد.</span>
				</p>
			<?php endif; ?>
		</form>
		<?php
	}

	/* --------------------------------------------------------------------- */
	/* خروجی CSV                                                              */
	/* --------------------------------------------------------------------- */

	/**
	 * @param array $f
	 * @return string
	 */
	public static function export_url( $f ) {
		return add_query_arg(
			array(
				'action'   => 'ardal_wallet_export',
				'range'    => $f['range'],
				'from'     => $f['from'],
				'to'       => $f['to'],
				'user'     => $f['user'],
				'order_id' => $f['order'],
				's'        => $f['search'],
				'min'      => null !== $f['min'] ? $f['min'] : '',
				'max'      => null !== $f['max'] ? $f['max'] : '',
				'type'     => $f['types'],
				'status'   => $f['statuses'],
				'_wpnonce' => wp_create_nonce( 'ardal_wallet_export' ),
			),
			admin_url( 'admin-post.php' )
		);
	}

	/**
	 * خانهٔ CSV امن: متنی که با = + - @ شروع شود در اکسل فرمول اجرا می‌کند،
	 * و یادداشت و نام کاربری را کاربر/مدیر نوشته است.
	 *
	 * @param mixed $value
	 * @return string
	 */
	private static function cell( $value ) {
		$value = (string) $value;

		if ( '' !== $value && in_array( $value[0], array( '=', '+', '-', '@', "\t", "\r" ), true ) && ! is_numeric( $value ) ) {
			$value = "'" . $value;
		}

		return $value;
	}

	public static function export_csv() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( 'دسترسی لازم را ندارید.' );
		}

		check_admin_referer( 'ardal_wallet_export' );

		$f             = self::filters();
		$f['paged']    = 1;
		$f['per_page'] = 200;

		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=ardal-wallet-' . gmdate( 'Ymd-His' ) . '.csv' );

		$out = fopen( 'php://output', 'w' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen

		// BOM تا اکسل فارسی را درست باز کند.
		fwrite( $out, "\xEF\xBB\xBF" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fwrite

		fputcsv( $out, array( 'شناسه', 'کاربر', 'ایمیل', 'نوع', 'مبلغ', 'موجودی پس از تراکنش', 'سفارش', 'وضعیت', 'توضیح', 'تاریخ', 'تاریخ شمسی' ) );

		// صفحه‌به‌صفحه تا خروجی بزرگ حافظه را پر نکند؛ سقف ۲۰هزار ردیف.
		for ( $page = 1; $page <= 100; $page++ ) {
			$f['paged'] = $page;
			$rows       = self::rows( $f );

			foreach ( $rows as $tx ) {
				$user   = get_userdata( $tx['user_id'] );
				$status = Ardal_Wallet::status_label( $tx['status'] );

				fputcsv(
					$out,
					array_map(
						array( __CLASS__, 'cell' ),
						array(
							$tx['id'],
							$user ? $user->user_login : $tx['user_id'],
							$user ? $user->user_email : '',
							Ardal_Wallet::type_names()[ $tx['type'] ] ?? $tx['type'],
							$tx['amount'],
							$tx['balance'],
							$tx['order_id'] ? $tx['order_id'] : '',
							$status['label'],
							$tx['note'],
							$tx['created_at'],
							Ardal_Wallet::date( $tx['created_at'], true ),
						)
					)
				);
			}

			if ( count( $rows ) < $f['per_page'] ) {
				break;
			}
		}

		fclose( $out ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		exit;
	}
}
