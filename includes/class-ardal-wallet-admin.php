<?php
/**
 * کیف پول در پیشخوان: پروفایل کاربر، ستون فهرست کاربران و نمایش در سفارش.
 *
 * عددهای سفارش از متای خود سفارش خوانده می‌شوند تا با تراکنش‌های ثبت‌شده یکی
 * بمانند؛ تنها استثنا «برآورد کش‌بگ» است که تا رسیدن سفارش به وضعیت لازم
 * هنوز ثبت نشده.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Wallet_Admin {

	public static function init() {
		if ( ! Ardal_Wallet::is_enabled() ) {
			return;
		}

		add_action( 'show_user_profile', array( __CLASS__, 'render_profile' ) );
		add_action( 'edit_user_profile', array( __CLASS__, 'render_profile' ) );
		add_action( 'personal_options_update', array( __CLASS__, 'save_profile' ) );
		add_action( 'edit_user_profile_update', array( __CLASS__, 'save_profile' ) );

		add_filter( 'manage_users_columns', array( __CLASS__, 'user_column' ) );
		add_filter( 'manage_users_custom_column', array( __CLASS__, 'user_column_value' ), 10, 3 );

		add_action( 'woocommerce_admin_order_data_after_order_details', array( __CLASS__, 'render_order_box' ) );
		add_action( 'woocommerce_admin_order_totals_after_total', array( __CLASS__, 'render_order_totals' ) );

		// ستون فهرست سفارش‌ها؛ هم HPOS و هم جدول قدیمی.
		add_filter( 'manage_woocommerce_page_wc-orders_columns', array( __CLASS__, 'order_columns' ) );
		add_action( 'manage_woocommerce_page_wc-orders_custom_column', array( __CLASS__, 'order_column' ), 10, 2 );
		add_filter( 'manage_edit-shop_order_columns', array( __CLASS__, 'order_columns' ) );
		add_action( 'manage_shop_order_posts_custom_column', array( __CLASS__, 'order_column' ), 10, 2 );

		// فاکتور مشتری: صفحهٔ سفارش، ایمیل و پنل کاربری.
		add_filter( 'woocommerce_get_order_item_totals', array( __CLASS__, 'item_totals' ), 30, 2 );

		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'assets' ) );
	}

	/**
	 * @param string $hook
	 */
	public static function assets( $hook ) {
		$screens = array( 'profile.php', 'user-edit.php', 'users.php', 'post.php', 'edit.php', 'woocommerce_page_wc-orders' );

		if ( in_array( $hook, $screens, true ) ) {
			wp_enqueue_style( 'ardal-wallet-admin', ARDAL_URL . 'assets/admin/wallet.css', array(), ARDAL_VERSION . '.' . (int) @filemtime( ARDAL_PATH . 'assets/admin/wallet.css' ) ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		}
	}

	/* --------------------------------------------------------------------- */
	/* پروفایل کاربر                                                          */
	/* --------------------------------------------------------------------- */

	/**
	 * @param WP_User $user
	 */
	public static function render_profile( $user ) {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		$rows = Ardal_Wallet::transactions( $user->ID, array( 'limit' => 10 ) );
		?>
		<div class="ardal-wl-user" dir="rtl">
			<h2>کیف پول</h2>

			<table class="form-table" role="presentation">
				<tr>
					<th>موجودی فعلی</th>
					<td>
						<strong class="ardal-wl-user__balance"><?php echo wp_kses_post( wc_price( Ardal_Wallet::balance( $user->ID ) ) ); ?></strong>
						<a class="ardal-wl-user__link" href="<?php echo esc_url( Ardal_Wallet_Report::url( array( 'user' => $user->ID, 'range' => 'all' ) ) ); ?>">همهٔ تراکنش‌ها در گزارش</a>
					</td>
				</tr>
				<tr>
					<th><label for="ardal_wallet_adjust">تغییر دستی موجودی</label></th>
					<td>
						<select name="ardal_wallet_op">
							<option value="add">افزودن</option>
							<option value="subtract">کسر</option>
							<option value="set">تنظیم روی مقدار</option>
							<option value="cashback">کش‌بگ (با انقضای تنظیمات)</option>
						</select>
						<input type="text" name="ardal_wallet_adjust" id="ardal_wallet_adjust" value="" class="regular-text" style="max-width:180px" placeholder="0" inputmode="numeric" />
						<input type="text" name="ardal_wallet_note" value="" class="regular-text" placeholder="توضیح (اختیاری)" />
						<p class="description">خالی بماند تغییری انجام نمی‌شود. هر تغییر یک تراکنش ثبت می‌کند که کاربر هم آن را می‌بیند.</p>
						<?php wp_nonce_field( 'ardal_wallet_profile', 'ardal_wallet_nonce' ); ?>
					</td>
				</tr>
			</table>

			<?php if ( $rows ) : ?>
				<table class="widefat striped ardal-wl-user__log">
					<thead>
						<tr>
							<th>شناسه</th>
							<th>نوع</th>
							<th>مبلغ</th>
							<th>موجودی پس از تراکنش</th>
							<th>وضعیت</th>
							<th>تاریخ</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $rows as $tx ) : ?>
							<?php $status = Ardal_Wallet::status_label( $tx['status'] ); ?>
							<tr>
								<td><?php echo esc_html( $tx['id'] ); ?></td>
								<td><?php echo esc_html( Ardal_Wallet::type_label( $tx ) ); ?></td>
								<td class="ardal-wl-amt ardal-wl-amt--<?php echo $tx['is_credit'] ? 'in' : 'out'; ?>"><?php echo esc_html( $tx['is_credit'] ? '+' : '−' ); ?><?php echo wp_kses_post( wc_price( $tx['abs'] ) ); ?></td>
								<td><?php echo wp_kses_post( wc_price( $tx['balance'] ) ); ?></td>
								<td><span class="ardal-wl-pill ardal-wl-pill--<?php echo esc_attr( $status['key'] ); ?>"><?php echo esc_html( $status['label'] ); ?></span></td>
								<td><?php echo esc_html( Ardal_Wallet::date( $tx['created_at'], true ) ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * @param int $user_id
	 */
	public static function save_profile( $user_id ) {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		$nonce = isset( $_POST['ardal_wallet_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ardal_wallet_nonce'] ) ) : '';

		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'ardal_wallet_profile' ) ) {
			return;
		}

		$raw = isset( $_POST['ardal_wallet_adjust'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['ardal_wallet_adjust'] ) ) ) : '';

		if ( '' === $raw ) {
			return;
		}

		$op   = isset( $_POST['ardal_wallet_op'] ) ? sanitize_key( wp_unslash( $_POST['ardal_wallet_op'] ) ) : 'add';
		$note = isset( $_POST['ardal_wallet_note'] ) ? sanitize_text_field( wp_unslash( $_POST['ardal_wallet_note'] ) ) : '';

		Ardal_Wallet_Tools::adjust( $user_id, $op, Ardal_Wallet::to_number( $raw ), $note );
	}

	/**
	 * @param array $columns
	 * @return array
	 */
	public static function user_column( $columns ) {
		$columns['ardal_wallet'] = 'کیف پول';

		return $columns;
	}

	/**
	 * @param string $output
	 * @param string $column
	 * @param int    $user_id
	 * @return string
	 */
	public static function user_column_value( $output, $column, $user_id ) {
		if ( 'ardal_wallet' !== $column ) {
			return $output;
		}

		// در فهرست کاربران انقضا حساب نمی‌شود تا صفحه سنگین نشود.
		return wp_kses_post( wc_price( Ardal_Wallet::balance( $user_id, false ) ) );
	}

	/* --------------------------------------------------------------------- */
	/* سفارش                                                                  */
	/* --------------------------------------------------------------------- */

	/**
	 * خلاصهٔ کیف پول یک سفارش.
	 *
	 * @param WC_Order|int $order
	 * @return array|null
	 */
	public static function summary( $order ) {
		$order = is_numeric( $order ) ? wc_get_order( $order ) : $order;

		if ( ! $order instanceof WC_Order ) {
			return null;
		}

		$used     = (float) $order->get_meta( Ardal_Wallet_Checkout::META_AMOUNT );
		$cashback = (float) $order->get_meta( Ardal_Wallet_Checkout::META_CASHBACK );
		$topup    = Ardal_Wallet_Checkout::topup_amount( $order );
		$pending  = ( ! $cashback && ! $topup && ! $order->has_status( array( 'cancelled', 'refunded', 'failed' ) ) )
			? Ardal_Wallet_Checkout::cashback_for( $order )
			: 0.0;

		return array(
			'order'    => $order,
			'user_id'  => (int) $order->get_customer_id(),
			'used'     => $used,
			'charged'  => 'yes' === $order->get_meta( Ardal_Wallet_Checkout::META_CHARGED ),
			'refunded' => 'yes' === $order->get_meta( Ardal_Wallet_Checkout::META_REFUNDED ),
			'cashback' => $cashback,
			'pending'  => $pending,
			'topup'    => $topup,
			'has_any'  => $used > 0 || $cashback > 0 || $topup > 0 || $pending > 0,
		);
	}

	/**
	 * @param WC_Order $order
	 */
	public static function render_order_box( $order ) {
		$data = self::summary( $order );

		if ( ! $data ) {
			return;
		}

		$statuses = wc_get_order_statuses();
		$status   = Ardal_Wallet::option( 'cashback_status' );
		$status   = $statuses[ 'wc-' . $status ] ?? $status;
		?>
		<div class="ardal-wl-order">
			<div class="ardal-wl-order__cap">کیف پول</div>

			<?php if ( $data['topup'] > 0 ) : ?>
				<p class="ardal-wl-order__row"><span>سفارش افزایش اعتبار</span><strong><?php echo wp_kses_post( wc_price( $data['topup'] ) ); ?></strong></p>
			<?php endif; ?>

			<p class="ardal-wl-order__row">
				<span>پرداخت‌شده از کیف پول</span>
				<strong class="ardal-wl-amt--out"><?php echo wp_kses_post( wc_price( $data['used'] ) ); ?></strong>
			</p>

			<?php if ( $data['used'] > 0 ) : ?>
				<p class="ardal-wl-order__note">
					<?php
					if ( $data['refunded'] ) {
						echo 'این مبلغ به کیف پول مشتری بازگشته است.';
					} elseif ( $data['charged'] ) {
						echo 'از کیف پول کسر شده است.';
					} else {
						echo 'هنوز از کیف پول کسر نشده است.';
					}
					?>
				</p>
			<?php endif; ?>

			<?php if ( $data['cashback'] > 0 || $data['pending'] > 0 ) : ?>
				<p class="ardal-wl-order__row">
					<span>کش‌بگ این سفارش</span>
					<strong class="ardal-wl-amt--in"><?php echo wp_kses_post( wc_price( $data['cashback'] > 0 ? $data['cashback'] : $data['pending'] ) ); ?></strong>
				</p>
				<p class="ardal-wl-order__note">
					<?php
					echo $data['cashback'] > 0
						? 'به کیف پول مشتری اضافه شده است.'
						: esc_html( sprintf( 'برآورد؛ با رسیدن سفارش به وضعیت «%s» اضافه می‌شود.', $status ) );
					?>
				</p>
			<?php endif; ?>

			<?php if ( $data['user_id'] ) : ?>
				<p class="ardal-wl-order__row">
					<span>موجودی فعلی مشتری</span>
					<strong><?php echo wp_kses_post( wc_price( Ardal_Wallet::balance( $data['user_id'], false ) ) ); ?></strong>
				</p>
				<p class="ardal-wl-order__links">
					<a href="<?php echo esc_url( Ardal_Wallet_Report::url( array( 'order_id' => $data['order']->get_id(), 'range' => 'all' ) ) ); ?>">تراکنش‌های این سفارش</a>
					<a href="<?php echo esc_url( Ardal_Wallet_Report::url( array( 'user' => $data['user_id'], 'range' => 'all' ) ) ); ?>">گزارش این مشتری</a>
				</p>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * @param int $order_id
	 */
	public static function render_order_totals( $order_id ) {
		$data = self::summary( $order_id );

		if ( ! $data || ! $data['has_any'] ) {
			return;
		}

		if ( $data['used'] > 0 ) {
			printf(
				'<tr><td class="label">پرداخت از کیف پول:</td><td width="1%%"></td><td class="total">%s</td></tr>',
				wp_kses_post( wc_price( $data['used'] ) )
			);
		}

		if ( $data['cashback'] > 0 || $data['pending'] > 0 ) {
			printf(
				'<tr><td class="label">%s</td><td width="1%%"></td><td class="total">%s</td></tr>',
				esc_html( $data['cashback'] > 0 ? 'کش‌بگ پرداخت‌شده:' : 'کش‌بگ (برآورد):' ),
				wp_kses_post( wc_price( $data['cashback'] > 0 ? $data['cashback'] : $data['pending'] ) )
			);
		}
	}

	/**
	 * ستون «کیف پول» پیش از جمع کل.
	 *
	 * @param array $columns
	 * @return array
	 */
	public static function order_columns( $columns ) {
		$out = array();

		foreach ( (array) $columns as $key => $label ) {
			if ( 'order_total' === $key ) {
				$out['ardal_wallet'] = 'کیف پول';
			}

			$out[ $key ] = $label;
		}

		if ( ! isset( $out['ardal_wallet'] ) ) {
			$out['ardal_wallet'] = 'کیف پول';
		}

		return $out;
	}

	/**
	 * @param string       $column
	 * @param WC_Order|int $order
	 */
	public static function order_column( $column, $order ) {
		if ( 'ardal_wallet' !== $column ) {
			return;
		}

		$data = self::summary( $order );

		if ( ! $data || ! $data['has_any'] ) {
			echo '<span class="ardal-wl-dash">—</span>';

			return;
		}

		if ( $data['topup'] > 0 ) {
			echo '<span class="ardal-wl-tag">افزایش اعتبار</span>';
		}

		if ( $data['used'] > 0 ) {
			printf( '<span class="ardal-wl-tag ardal-wl-tag--out" title="پرداخت از کیف پول">−%s</span>', wp_kses_post( wc_price( $data['used'] ) ) );
		}

		$cashback = $data['cashback'] > 0 ? $data['cashback'] : $data['pending'];

		if ( $cashback > 0 ) {
			printf(
				'<span class="ardal-wl-tag ardal-wl-tag--in%1$s" title="%2$s">+%3$s</span>',
				$data['cashback'] > 0 ? '' : ' is-pending',
				esc_attr( $data['cashback'] > 0 ? 'کش‌بگ پرداخت‌شده' : 'کش‌بگ برآوردی' ),
				wp_kses_post( wc_price( $cashback ) )
			);
		}
	}

	/**
	 * ردیف کش‌بگ در فاکتور مشتری. مبلغ پرداخت‌شده از کیف پول خودش به‌شکل
	 * ردیف کاهنده در فاکتور هست؛ اینجا فقط چیزی می‌آید که مشتری «گرفته».
	 *
	 * @param array    $totals
	 * @param WC_Order $order
	 * @return array
	 */
	public static function item_totals( $totals, $order ) {
		$cashback = $order instanceof WC_Order ? (float) $order->get_meta( Ardal_Wallet_Checkout::META_CASHBACK ) : 0.0;

		if ( $cashback > 0 ) {
			$totals['ardal_cashback'] = array(
				'label' => 'کش‌بگ این سفارش:',
				'value' => wc_price( $cashback ),
			);
		}

		return $totals;
	}
}
