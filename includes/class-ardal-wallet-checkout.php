<?php
/**
 * اتصال کیف پول به ووکامرس: پرداخت در تسویه، افزایش اعتبار، کش‌بگ و بازگشت.
 *
 * مبلغ کیف پول به‌صورت «کارمزد منفی» با شناسهٔ ثابت به سبد اضافه می‌شود؛ این
 * تنها روشی است که هم با تسویهٔ کلاسیک، هم با بلاک‌ها و هم با همهٔ درگاه‌ها
 * بدون دستکاری کار می‌کند. کسر واقعی موقع ثبت سفارش و زیر قفل انجام می‌شود.
 *
 * ردیف کارمزد با متای خودش شناخته می‌شود، نه با برچسبش؛ وگرنه مدیری که متن
 * «پرداخت از کیف پول» را عوض کند، بازگشت وجه سفارش‌های قبلی را می‌شکند.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Wallet_Checkout {

	/* متای سفارش */
	const META_AMOUNT   = '_ardal_wallet_amount';
	const META_CHARGED  = '_ardal_wallet_charged';
	const META_REFUNDED = '_ardal_wallet_refunded';
	const META_CASHBACK = '_ardal_wallet_cashback';
	const META_TOPUP    = '_ardal_wallet_topup';
	const META_TOPUP_OK = '_ardal_wallet_topup_done';

	/* متای ردیف کارمزد */
	const META_FEE = '_ardal_wallet_fee';

	/**
	 * شناسهٔ کارمزد در سبد و نام فیلد چک‌باکس.
	 */
	const FEE_ID = 'ardal-wallet';

	const FIELD = 'ardal_wallet_apply';

	const SESSION_KEY = 'ardal_wallet_apply';

	public static function init() {
		if ( ! Ardal_Wallet::is_enabled() ) {
			return;
		}

		/* افزایش اعتبار */
		add_action( 'wp_ajax_ardal_wallet_topup', array( __CLASS__, 'ajax_topup' ) );
		add_action( 'wp_ajax_nopriv_ardal_wallet_topup', array( __CLASS__, 'ajax_topup' ) );

		add_action( 'woocommerce_payment_complete', array( __CLASS__, 'complete_topup' ) );
		add_action( 'woocommerce_order_status_processing', array( __CLASS__, 'complete_topup' ) );
		add_action( 'woocommerce_order_status_completed', array( __CLASS__, 'complete_topup' ) );
		add_action( 'woocommerce_order_status_failed', array( __CLASS__, 'fail_topup' ) );
		add_action( 'woocommerce_order_status_cancelled', array( __CLASS__, 'fail_topup' ) );

		// سفارش شارژ چیزی برای ارسال ندارد؛ با پرداخت مستقیم «تکمیل» شود.
		add_filter( 'woocommerce_order_needs_processing', array( __CLASS__, 'topup_needs_processing' ), 10, 2 );

		/* کش‌بگ و بازگشت، مستقل از استفاده در تسویه */
		add_action( 'woocommerce_order_status_' . Ardal_Wallet::option( 'cashback_status' ), array( __CLASS__, 'grant_cashback' ) );
		add_action( 'woocommerce_order_status_cancelled', array( __CLASS__, 'refund_order' ) );
		add_action( 'woocommerce_order_status_refunded', array( __CLASS__, 'refund_order' ) );

		if ( ! self::checkout_active() ) {
			return;
		}

		add_action( 'woocommerce_cart_calculate_fees', array( __CLASS__, 'calculate_fees' ), 50 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ), 25 );

		// باکس داخل #payment است که ووکامرس با هر به‌روزرسانی از نو می‌سازد؛
		// پس متن «تا X پرداخت می‌شود» همیشه با سبد فعلی هم‌خوان است.
		add_action( 'ardal_checkout_payment_top', array( __CLASS__, 'render_box' ) );
		add_action( 'woocommerce_review_order_before_payment', array( __CLASS__, 'render_box_fallback' ) );

		add_action( 'woocommerce_checkout_create_order_fee_item', array( __CLASS__, 'tag_fee_item' ), 10, 4 );
		add_action( 'woocommerce_checkout_create_order', array( __CLASS__, 'stamp_order' ), 20 );
		add_action( 'woocommerce_checkout_order_processed', array( __CLASS__, 'charge_order' ), 5 );
		add_action( 'woocommerce_store_api_checkout_order_processed', array( __CLASS__, 'charge_order' ), 5 );
	}

	/**
	 * فایل‌های باکس کیف پول از ابتدا در سر صفحهٔ تسویه باشند، نه دیر و در
	 * پانویس؛ وگرنه باکس لحظه‌ای بی‌استایل دیده می‌شد.
	 */
	public static function enqueue() {
		if ( function_exists( 'is_checkout' ) && is_checkout() && is_user_logged_in() ) {
			Ardal_Wallet_Render::assets();
		}
	}

	/**
	 * @return bool
	 */
	public static function checkout_active() {
		return Ardal_Wallet::is_enabled() && Ardal_Wallet::on( 'checkout' );
	}

	/**
	 * @return string
	 */
	public static function label() {
		$label = Ardal_Wallet::option( 'checkout_label' );

		return '' !== $label ? $label : 'پرداخت از کیف پول';
	}

	/* --------------------------------------------------------------------- */
	/* محاسبه                                                                 */
	/* --------------------------------------------------------------------- */

	/**
	 * مبلغ قابل پرداخت سبد پیش از کسر کیف پول.
	 *
	 * @return float
	 */
	public static function cart_payable() {
		$cart = WC()->cart;

		if ( ! $cart ) {
			return 0.0;
		}

		$total = (float) $cart->get_cart_contents_total()
			+ (float) $cart->get_cart_contents_tax()
			+ (float) $cart->get_shipping_total()
			+ (float) $cart->get_shipping_tax();

		foreach ( $cart->get_fees() as $fee ) {
			if ( self::FEE_ID !== $fee->id ) {
				$total += (float) $fee->total + (float) ( $fee->tax ?? 0 );
			}
		}

		return max( 0, Ardal_Wallet::round( $total ) );
	}

	/**
	 * بیشترین مبلغی که همین حالا از کیف پول قابل استفاده است.
	 *
	 * @param int $user_id
	 * @return float
	 */
	public static function applicable( $user_id = 0 ) {
		if ( ! self::checkout_active() ) {
			return 0.0;
		}

		$user_id = $user_id ? (int) $user_id : get_current_user_id();

		if ( ! $user_id ) {
			return 0.0;
		}

		$balance = Ardal_Wallet::balance( $user_id );
		$payable = self::cart_payable();

		if ( $balance <= 0 || $payable <= 0 ) {
			return 0.0;
		}

		$percent = max( 1, min( 100, (int) Ardal_Wallet::option( 'max_percent' ) ) );
		$amount  = Ardal_Wallet::round( min( $balance, $payable * $percent / 100, $payable ) );

		// بدون پرداخت جزئی، کیف پول فقط وقتی به کار می‌آید که کل فاکتور را بپوشاند.
		if ( ! Ardal_Wallet::on( 'partial' ) && $amount + 0.0001 < $payable ) {
			return 0.0;
		}

		/**
		 * مبلغ قابل استفاده از کیف پول در سبد جاری.
		 *
		 * @param float $amount
		 * @param int   $user_id
		 */
		return max( 0, (float) apply_filters( 'ardal_wallet_applicable', $amount, $user_id ) );
	}

	/**
	 * آیا کاربر خواسته کیف پول اعمال شود؟
	 *
	 * @return bool
	 */
	public static function wants_wallet() {
		if ( ! WC()->session ) {
			return false;
		}

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- نانس را خود ووکامرس در update_order_review و process_checkout بررسی می‌کند.
		if ( isset( $_POST['post_data'] ) ) {
			parse_str( wp_unslash( $_POST['post_data'] ), $data ); // phpcs:ignore WordPress.Security.ValidationSanitization.InputNotSanitized

			$value = ! empty( $data[ self::FIELD ] ) ? 'yes' : 'no';
			WC()->session->set( self::SESSION_KEY, $value );

			return 'yes' === $value;
		}

		if ( isset( $_POST['woocommerce_checkout_place_order'] ) || isset( $_POST['billing_email'] ) || isset( $_POST['payment_method'] ) ) {
			$value = ! empty( $_POST[ self::FIELD ] ) ? 'yes' : 'no';
			WC()->session->set( self::SESSION_KEY, $value );

			return 'yes' === $value;
		}
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$stored = WC()->session->get( self::SESSION_KEY );

		if ( null === $stored || '' === $stored ) {
			return Ardal_Wallet::on( 'auto_apply' );
		}

		return 'yes' === $stored;
	}

	/* --------------------------------------------------------------------- */
	/* سبد و تسویه                                                            */
	/* --------------------------------------------------------------------- */

	/**
	 * @param WC_Cart $cart
	 */
	public static function calculate_fees( $cart ) {
		if ( is_admin() && ! wp_doing_ajax() ) {
			return;
		}

		if ( ! is_user_logged_in() || ! self::wants_wallet() ) {
			return;
		}

		$amount = self::applicable();

		if ( $amount <= 0 ) {
			return;
		}

		$cart->fees_api()->add_fee(
			array(
				'id'      => self::FEE_ID,
				'name'    => self::label(),
				'amount'  => -$amount,
				'taxable' => false,
			)
		);
	}

	/**
	 * آیا تمپلیت پرداخت همان تمپلیت اردال است (که قلاب داخلی را دارد)؟
	 *
	 * @return bool
	 */
	private static function ardal_template() {
		$file = wc_locate_template( 'checkout/payment.php' );

		return $file && 0 === strpos( wp_normalize_path( $file ), wp_normalize_path( ARDAL_PATH ) );
	}

	/**
	 * وقتی قالب سایت تمپلیت پرداخت خودش را دارد، باکس روی قلاب استاندارد
	 * ووکامرس می‌نشیند.
	 */
	public static function render_box_fallback() {
		if ( ! self::ardal_template() ) {
			self::render_box();
		}
	}

	/**
	 * باکس «پرداخت از کیف پول» در تسویه.
	 */
	public static function render_box() {
		if ( ! is_user_logged_in() ) {
			return;
		}

		$balance = Ardal_Wallet::balance();

		if ( $balance <= 0 ) {
			return;
		}

		Ardal_Wallet_Render::assets();

		$amount  = self::applicable();
		$checked = $amount > 0 && self::wants_wallet();
		$payable = self::cart_payable();
		?>
		<div class="ard-wl-pay<?php echo $checked ? ' is-on' : ''; ?>" data-ard-wallet-pay>
			<label class="ard-wl-pay__row">
				<input type="checkbox" class="ard-wl-pay__input" name="<?php echo esc_attr( self::FIELD ); ?>" value="1" <?php checked( $checked ); ?> <?php disabled( $amount <= 0 ); ?> />
				<span class="ard-wl-pay__box" aria-hidden="true">
					<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"></path></svg>
				</span>
				<span class="ard-wl-pay__icon" aria-hidden="true"><?php echo Ardal_Account::icon( 'wallet', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="ard-wl-pay__text">
					<span class="ard-wl-pay__title"><?php echo esc_html( self::label() ); ?></span>
					<span class="ard-wl-pay__balance">موجودی: <?php echo Ardal_Wallet::money( $balance ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</span>
			</label>

			<?php if ( $amount > 0 ) : ?>
				<p class="ard-wl-pay__note">
					<?php if ( $amount + 0.0001 < $payable ) : ?>
						<?php echo Ardal_Wallet::money( $amount ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> از کیف پول کسر می‌شود و باقی‌مانده را با درگاه می‌پردازی.
					<?php else : ?>
						کل مبلغ این سفارش از کیف پول پرداخت می‌شود.
					<?php endif; ?>
				</p>
			<?php elseif ( ! Ardal_Wallet::on( 'partial' ) ) : ?>
				<p class="ard-wl-pay__note ard-wl-pay__note--off">کیف پول فقط وقتی قابل استفاده است که کل مبلغ سفارش را پوشش دهد.</p>
			<?php endif; ?>
		</div>
		<?php
	}

	/* --------------------------------------------------------------------- */
	/* ثبت سفارش                                                              */
	/* --------------------------------------------------------------------- */

	/**
	 * ردیف کارمزد کیف پول در سفارش علامت می‌خورد.
	 *
	 * @param WC_Order_Item_Fee $item
	 * @param string            $fee_key
	 * @param object            $fee
	 * @param WC_Order          $order
	 */
	public static function tag_fee_item( $item, $fee_key, $fee, $order ) {
		if ( isset( $fee->id ) && self::FEE_ID === $fee->id ) {
			$item->add_meta_data( self::META_FEE, 'yes', true );
		}
	}

	/**
	 * مبلغ کیف پول روی سفارش، پیش از ذخیره.
	 *
	 * @param WC_Order $order
	 */
	public static function stamp_order( $order ) {
		$amount = self::amount_from_fees( $order );

		if ( $amount > 0 ) {
			$order->update_meta_data( self::META_AMOUNT, $amount );
		}
	}

	/**
	 * مبلغ کیف پول از ردیف کارمزد سفارش.
	 *
	 * @param WC_Order $order
	 * @return float
	 */
	public static function amount_from_fees( $order ) {
		foreach ( $order->get_items( 'fee' ) as $item ) {
			if ( 'yes' === $item->get_meta( self::META_FEE ) ) {
				return abs( (float) $item->get_total() );
			}
		}

		return 0.0;
	}

	/**
	 * کسر از کیف پول پس از ثبت سفارش.
	 *
	 * اگر موجودی بین نمایش سبد و ثبت سفارش کم شده باشد (مثلاً سفارش هم‌زمان
	 * دیگری)، ردیف تخفیف کیف پول از سفارش برداشته و جمع دوباره حساب می‌شود؛
	 * درگاه پس از این قلاب صدا زده می‌شود، پس مشتری مبلغ کامل را می‌پردازد و
	 * چیزی مجانی نمی‌گیرد.
	 *
	 * @param int|WC_Order $order_id
	 */
	public static function charge_order( $order_id ) {
		$order = $order_id instanceof WC_Order ? $order_id : wc_get_order( $order_id );

		if ( ! $order || 'yes' === $order->get_meta( self::META_CHARGED ) ) {
			return;
		}

		$amount = (float) $order->get_meta( self::META_AMOUNT );

		if ( $amount <= 0 ) {
			$amount = self::amount_from_fees( $order );
		}

		$user_id = (int) $order->get_customer_id();

		if ( $amount <= 0 ) {
			return;
		}

		$charged = $user_id
			? Ardal_Wallet::debit(
				$user_id,
				$amount,
				array(
					'type'     => Ardal_Wallet::TYPE_ORDER,
					'order_id' => $order->get_id(),
					'note'     => self::label(),
				)
			)
			: new WP_Error( 'ardal_wallet_guest', 'سفارش مهمان کیف پول ندارد.' );

		if ( is_wp_error( $charged ) ) {
			foreach ( $order->get_items( 'fee' ) as $item_id => $item ) {
				if ( 'yes' === $item->get_meta( self::META_FEE ) ) {
					$order->remove_item( $item_id );
				}
			}

			$order->delete_meta_data( self::META_AMOUNT );
			$order->calculate_totals( false );
			$order->add_order_note( 'کسر از کیف پول انجام نشد (' . $charged->get_error_message() . ')؛ تخفیف کیف پول از سفارش برداشته شد.' );
			$order->save();

			if ( function_exists( 'wc_add_notice' ) && ! is_admin() ) {
				wc_add_notice( 'موجودی کیف پول تغییر کرده بود؛ مبلغ کامل سفارش را از درگاه پرداخت کن.', 'notice' );
			}

			return;
		}

		$order->update_meta_data( self::META_AMOUNT, $amount );
		$order->update_meta_data( self::META_CHARGED, 'yes' );
		$order->add_order_note( sprintf( 'مبلغ %s از کیف پول کسر شد.', Ardal_Wallet::plain_money( $amount ) ) );
		$order->save();

		if ( WC()->session ) {
			WC()->session->set( self::SESSION_KEY, null );
		}
	}

	/**
	 * بازگرداندن اعتبار هنگام لغو یا مرجوعی.
	 *
	 * @param int $order_id
	 */
	public static function refund_order( $order_id ) {
		$order = wc_get_order( $order_id );

		if ( ! $order || 'yes' === $order->get_meta( self::META_REFUNDED ) || 'yes' !== $order->get_meta( self::META_CHARGED ) ) {
			return;
		}

		$key = $order->has_status( 'cancelled' ) ? 'refund_cancel' : 'refund_refunded';

		if ( ! Ardal_Wallet::on( $key ) ) {
			return;
		}

		$amount  = (float) $order->get_meta( self::META_AMOUNT );
		$user_id = (int) $order->get_customer_id();

		if ( ! $user_id || $amount <= 0 ) {
			return;
		}

		$result = Ardal_Wallet::credit(
			$user_id,
			$amount,
			array(
				'type'     => Ardal_Wallet::TYPE_REFUND,
				'order_id' => $order->get_id(),
				'note'     => 'بازگشت اعتبار سفارش',
			)
		);

		if ( is_wp_error( $result ) ) {
			$order->add_order_note( 'بازگشت اعتبار به کیف پول انجام نشد: ' . $result->get_error_message() );

			return;
		}

		$order->update_meta_data( self::META_REFUNDED, 'yes' );
		$order->add_order_note( sprintf( 'مبلغ %s به کیف پول مشتری بازگشت.', Ardal_Wallet::plain_money( $amount ) ) );
		$order->save();
	}

	/* --------------------------------------------------------------------- */
	/* کش‌بگ                                                                  */
	/* --------------------------------------------------------------------- */

	/**
	 * کش‌بگی که به سفارش تعلق می‌گیرد (برای ثبت و هم برای برآورد).
	 *
	 * @param WC_Order $order
	 * @return float
	 */
	public static function cashback_for( $order ) {
		if ( ! Ardal_Wallet::on( 'cashback' ) || ! $order instanceof WC_Order ) {
			return 0.0;
		}

		if ( self::is_topup_order( $order ) || ! $order->get_customer_id() ) {
			return 0.0;
		}

		$total = (float) $order->get_total();
		$used  = (float) $order->get_meta( self::META_AMOUNT );

		// کف سفارش روی مبلغ واقعی خرید سنجیده می‌شود، با بخشِ کیف پول.
		if ( $total + $used < (float) Ardal_Wallet::option( 'cashback_min_total' ) ) {
			return 0.0;
		}

		// get_total() خودش پس از کسر کیف پول است؛ اگر کش‌بگ به بخش کیف پول هم
		// تعلق بگیرد، همان بخش برگردانده می‌شود.
		$base = Ardal_Wallet::on( 'cashback_on_wallet' ) ? $total + $used : $total;

		$amount = Ardal_Wallet::round( max( 0, $base ) * (float) Ardal_Wallet::option( 'cashback_percent' ) / 100 );
		$max    = (float) Ardal_Wallet::option( 'cashback_max' );

		if ( $max > 0 ) {
			$amount = min( $amount, $max );
		}

		/**
		 * مبلغ کش‌بگ یک سفارش.
		 *
		 * @param float    $amount
		 * @param WC_Order $order
		 */
		return max( 0, (float) apply_filters( 'ardal_wallet_cashback_amount', $amount, $order ) );
	}

	/**
	 * @param int $order_id
	 */
	public static function grant_cashback( $order_id ) {
		$order = wc_get_order( $order_id );

		if ( ! $order || (float) $order->get_meta( self::META_CASHBACK ) > 0 ) {
			return;
		}

		$amount = self::cashback_for( $order );

		if ( $amount <= 0 ) {
			return;
		}

		$result = Ardal_Wallet::add_cashback( (int) $order->get_customer_id(), $amount, $order->get_id() );

		if ( is_wp_error( $result ) ) {
			return;
		}

		$order->update_meta_data( self::META_CASHBACK, $amount );
		$order->add_order_note( sprintf( 'کش‌بگ %s به کیف پول مشتری اضافه شد.', Ardal_Wallet::plain_money( $amount ) ) );
		$order->save();

		Ardal_Wallet_Sms::send( (int) $order->get_customer_id(), $amount, Ardal_Wallet_Sms::EVENT_CASHBACK );
	}

	/* --------------------------------------------------------------------- */
	/* افزایش اعتبار                                                          */
	/* --------------------------------------------------------------------- */

	public static function ajax_topup() {
		check_ajax_referer( 'ardal-wallet', 'nonce' );

		if ( ! is_user_logged_in() ) {
			wp_send_json_error(
				array(
					'code'    => 'logged_out',
					'message' => 'برای افزایش اعتبار ابتدا وارد حساب شو.',
					'login'   => wc_get_page_permalink( 'myaccount' ),
				),
				401
			);
		}

		if ( ! Ardal_Wallet::on( 'topup_enabled' ) ) {
			wp_send_json_error( array( 'message' => 'افزایش اعتبار در حال حاضر غیرفعال است.' ), 403 );
		}

		$amount = Ardal_Wallet::round( Ardal_Wallet::to_number( isset( $_POST['amount'] ) ? wp_unslash( $_POST['amount'] ) : '' ) ); // phpcs:ignore WordPress.Security.ValidationSanitization.InputNotSanitized -- to_number پاک‌سازی می‌کند.
		$min    = (float) Ardal_Wallet::option( 'min_topup' );
		$max    = (float) Ardal_Wallet::option( 'max_topup' );

		if ( $amount <= 0 ) {
			wp_send_json_error( array( 'message' => 'مبلغ افزایش اعتبار را وارد کن.' ), 400 );
		}

		if ( $min > 0 && $amount < $min ) {
			wp_send_json_error( array( 'message' => sprintf( 'کمترین مبلغ افزایش اعتبار %s است.', Ardal_Wallet::plain_money( $min ) ) ), 400 );
		}

		if ( $max > 0 && $amount > $max ) {
			wp_send_json_error( array( 'message' => sprintf( 'بیشترین مبلغ افزایش اعتبار %s است.', Ardal_Wallet::plain_money( $max ) ) ), 400 );
		}

		$order = self::create_topup_order( get_current_user_id(), $amount );

		if ( is_wp_error( $order ) ) {
			wp_send_json_error( array( 'message' => $order->get_error_message() ), 500 );
		}

		wp_send_json_success(
			array(
				'order'    => $order->get_id(),
				'redirect' => $order->get_checkout_payment_url(),
			)
		);
	}

	/**
	 * سفارشِ فقط-کارمزد برای افزایش اعتبار.
	 *
	 * این‌طور همهٔ درگاه‌های فروشگاه بدون تنظیم اضافه کار می‌کنند و «محصول
	 * کیف پول» لازم نیست. نشانی صورتحساب از حساب مشتری کپی می‌شود چون بیشتر
	 * درگاه‌های ایرانی شمارهٔ موبایل یا ایمیل را می‌خواهند.
	 *
	 * @param int   $user_id
	 * @param float $amount
	 * @return WC_Order|WP_Error
	 */
	public static function create_topup_order( $user_id, $amount ) {
		$order = wc_create_order(
			array(
				'customer_id' => (int) $user_id,
				'created_via' => 'ardal_wallet',
			)
		);

		if ( is_wp_error( $order ) ) {
			return $order;
		}

		$customer = new WC_Customer( (int) $user_id );

		foreach ( array( 'first_name', 'last_name', 'company', 'address_1', 'address_2', 'city', 'state', 'postcode', 'country', 'email', 'phone' ) as $field ) {
			$getter = 'get_billing_' . $field;
			$setter = 'set_billing_' . $field;

			if ( is_callable( array( $customer, $getter ) ) && is_callable( array( $order, $setter ) ) ) {
				$order->$setter( $customer->$getter() );
			}
		}

		if ( ! $order->get_billing_email() ) {
			$user = get_userdata( (int) $user_id );
			$order->set_billing_email( $user ? $user->user_email : '' );
		}

		$fee = new WC_Order_Item_Fee();
		$fee->set_name( 'افزایش اعتبار کیف پول' );
		$fee->set_amount( (string) $amount );
		$fee->set_total( (string) $amount );
		$fee->set_tax_status( 'none' );
		$fee->set_tax_class( '' );

		$order->add_item( $fee );
		$order->update_meta_data( self::META_TOPUP, $amount );
		$order->calculate_totals( false );
		$order->save();

		$tx = Ardal_Wallet::credit(
			$user_id,
			$amount,
			array(
				'type'     => Ardal_Wallet::TYPE_TOPUP,
				'status'   => Ardal_Wallet::STATUS_PENDING,
				'order_id' => $order->get_id(),
				'note'     => 'افزایش اعتبار',
			)
		);

		if ( is_wp_error( $tx ) ) {
			$order->delete( true );

			return $tx;
		}

		return $order;
	}

	/**
	 * @param bool     $needs
	 * @param WC_Order $order
	 * @return bool
	 */
	public static function topup_needs_processing( $needs, $order ) {
		return self::is_topup_order( $order ) ? false : $needs;
	}

	/**
	 * پرداخت موفق شارژ.
	 *
	 * @param int $order_id
	 */
	public static function complete_topup( $order_id ) {
		$order = wc_get_order( $order_id );

		if ( ! self::is_topup_order( $order ) || 'yes' === $order->get_meta( self::META_TOPUP_OK ) ) {
			return;
		}

		// پیش از هر کاری علامت می‌خورد تا هوک‌های پشت‌سرهم (payment_complete و
		// تغییر وضعیت) دو بار شارژ نکنند.
		$order->update_meta_data( self::META_TOPUP_OK, 'yes' );
		$order->save();

		$amount = self::topup_amount( $order );
		$tx     = Ardal_Wallet::find_by_order( $order->get_id(), Ardal_Wallet::TYPE_TOPUP );

		if ( $tx && Ardal_Wallet::STATUS_SUCCESS !== $tx['status'] ) {
			Ardal_Wallet::set_status( $tx['id'], Ardal_Wallet::STATUS_SUCCESS );
		} elseif ( ! $tx ) {
			// تراکنش در انتظار نیست (مثلاً سفارش دستی)؛ مستقیم اعتبار داده می‌شود.
			Ardal_Wallet::credit(
				(int) $order->get_customer_id(),
				$amount,
				array(
					'type'     => Ardal_Wallet::TYPE_TOPUP,
					'order_id' => $order->get_id(),
					'note'     => 'افزایش اعتبار',
				)
			);
		}

		$order->add_order_note( sprintf( 'کیف پول مشتری %s شارژ شد.', Ardal_Wallet::plain_money( $amount ) ) );

		Ardal_Wallet_Sms::send( (int) $order->get_customer_id(), $amount, Ardal_Wallet_Sms::EVENT_TOPUP );
	}

	/**
	 * @param int $order_id
	 */
	public static function fail_topup( $order_id ) {
		$order = wc_get_order( $order_id );

		if ( ! self::is_topup_order( $order ) || 'yes' === $order->get_meta( self::META_TOPUP_OK ) ) {
			return;
		}

		$tx = Ardal_Wallet::find_by_order( $order->get_id(), Ardal_Wallet::TYPE_TOPUP );

		if ( $tx && Ardal_Wallet::STATUS_PENDING === $tx['status'] ) {
			Ardal_Wallet::set_status( $tx['id'], Ardal_Wallet::STATUS_FAILED );
		}
	}

	/**
	 * @param WC_Order|null $order
	 * @return bool
	 */
	public static function is_topup_order( $order ) {
		return $order instanceof WC_Order && (float) $order->get_meta( self::META_TOPUP ) > 0;
	}

	/**
	 * @param WC_Order|null $order
	 * @return float
	 */
	public static function topup_amount( $order ) {
		return $order instanceof WC_Order ? (float) $order->get_meta( self::META_TOPUP ) : 0.0;
	}
}
