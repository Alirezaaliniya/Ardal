<?php
/**
 * سبد خرید و تسویه حساب — بارگذاری تمپلیت‌ها و دادهٔ کمکی صفحه‌ها.
 *
 * تمپلیت‌ها در پوشهٔ woocommerce/{cart,checkout} افزونه هستند و مثل پنل کاربری
 * با فیلتر woocommerce_locate_template جای تمپلیت‌های پیش‌فرض می‌نشینند.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Shop {

	/**
	 * کلید متای «فاکتور رسمی» روی سفارش.
	 */
	const INVOICE_META = '_ardal_official_invoice';

	public static function init() {
		// ووکامرس ممکن است بعد از اردال لود شود.
		add_action( 'plugins_loaded', array( __CLASS__, 'boot' ), 20 );
	}

	public static function boot() {
		if ( ! Ardal_Helpers::has_woo() ) {
			return;
		}

		add_filter( 'woocommerce_locate_template', array( __CLASS__, 'locate_template' ), 10, 2 );
		add_filter( 'body_class', array( __CLASS__, 'body_class' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ), 20 );

		// بخش «زمان و شیوه ارسال» در ستون اصلی است، پس باید خودش هم با تغییر
		// آدرس تازه شود؛ ووکامرس فقط جدول بازبینی و باکس پرداخت را تازه می‌کند.
		add_filter( 'woocommerce_update_order_review_fragments', array( __CLASS__, 'shipping_fragment' ) );

		add_filter( 'woocommerce_checkout_fields', array( __CLASS__, 'invoice_field' ) );
		add_action( 'woocommerce_checkout_create_order', array( __CLASS__, 'save_invoice_field' ), 10, 2 );
		add_action( 'woocommerce_admin_order_data_after_billing_address', array( __CLASS__, 'admin_invoice_note' ) );
	}

	/**
	 * @param string $template
	 * @param string $name
	 * @return string
	 */
	public static function locate_template( $template, $name ) {
		if ( 0 !== strpos( $name, 'cart/' ) && 0 !== strpos( $name, 'checkout/' ) ) {
			return $template;
		}

		// اگر قالب سایت این تمپلیت را اورراید کرده، دست نمی‌زنیم.
		if ( $template && 0 !== strpos( $template, WC()->plugin_path() ) ) {
			return $template;
		}

		$file = ARDAL_PATH . 'woocommerce/' . $name;

		return file_exists( $file ) ? $file : $template;
	}

	/**
	 * @return bool
	 */
	public static function is_shop_flow() {
		return function_exists( 'is_cart' ) && ( is_cart() || is_checkout() );
	}

	/**
	 * @param array $classes
	 * @return array
	 */
	public static function body_class( $classes ) {
		if ( self::is_shop_flow() ) {
			$classes[] = 'ardal-shop-flow';
		}

		return $classes;
	}

	public static function enqueue() {
		if ( ! self::is_shop_flow() ) {
			return;
		}

		wp_enqueue_style( 'ardal' );
		wp_enqueue_style( 'ardal-shop' );
		wp_enqueue_script( 'ardal' );
		wp_enqueue_script( 'ardal-shop' );

		wp_localize_script(
			'ardal-shop',
			'ardalShop',
			array(
				'ajax'  => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'ardal-account' ),
			)
		);
	}

	/* ============================================================
	   مراحل خرید
	   ============================================================ */

	/**
	 * سه مرحلهٔ بالای صفحه.
	 *
	 * @param string $current cart | checkout | done
	 * @return array
	 */
	public static function steps( $current = 'cart' ) {
		$defs  = array(
			'cart'     => 'سبد خرید',
			'checkout' => 'اطلاعات و ارسال',
			'done'     => 'پرداخت',
		);
		$order = array_keys( $defs );
		$index = array_search( $current, $order, true );
		$index = false === $index ? 0 : $index;
		$out   = array();
		$i     = 0;

		foreach ( $defs as $key => $label ) {
			$out[] = array(
				'number' => Ardal_Helpers::fa_digits( $i + 1 ),
				'label'  => $label,
				'state'  => $i < $index ? 'done' : ( $i === $index ? 'current' : 'next' ),
			);

			$i++;
		}

		return $out;
	}

	/* ============================================================
	   ارسال رایگان
	   ============================================================ */

	/**
	 * وضعیت ارسال رایگان: آیا الان شامل می‌شود و اگر نه چقدر مانده.
	 *
	 * @return array|null
	 */
	public static function free_shipping() {
		if ( ! WC()->cart || ! WC()->cart->needs_shipping() ) {
			return null;
		}

		$packages = WC()->shipping() ? WC()->shipping()->get_packages() : array();
		$target   = 0.0;

		foreach ( $packages as $package ) {
			// نرخ ارسال رایگانی که همین حالا در دسترس است یعنی شرطش برقرار شده.
			foreach ( $package['rates'] as $rate ) {
				if ( 'free_shipping' === $rate->get_method_id() ) {
					return array(
						'reached' => true,
						'left'    => 0.0,
					);
				}
			}

			$zone = function_exists( 'wc_get_shipping_zone' ) ? wc_get_shipping_zone( $package ) : null;

			if ( ! $zone ) {
				continue;
			}

			foreach ( $zone->get_shipping_methods( true ) as $method ) {
				if ( 'free_shipping' !== $method->id || $method->min_amount <= 0 ) {
					continue;
				}

				if ( ! in_array( $method->requires, array( 'min_amount', 'either', 'both' ), true ) ) {
					continue;
				}

				$amount = (float) $method->min_amount;
				$target = $target > 0 ? min( $target, $amount ) : $amount;
			}
		}

		if ( $target <= 0 ) {
			return null;
		}

		$spent = (float) WC()->cart->get_subtotal() - (float) WC()->cart->get_discount_total();
		$left  = max( 0, $target - $spent );

		return array(
			'reached' => $left <= 0,
			'left'    => $left,
			'target'  => $target,
			'percent' => $target > 0 ? min( 100, (int) round( $spent / $target * 100 ) ) : 0,
		);
	}

	/* ============================================================
	   تضمین‌های کنار باکس پرداخت
	   ============================================================ */

	/**
	 * @return array
	 */
	public static function guards() {
		return (array) apply_filters(
			'ardal_shop_guards',
			array(
				'ضمانت اصالت و سلامت کالا',
				'پرداخت امن از درگاه بانکی',
				'۷ روز مهلت بازگشت کالا',
			)
		);
	}

	/* ============================================================
	   شیوهٔ ارسال در ستون اصلی
	   ============================================================ */

	/**
	 * بسته‌های ارسال به‌همراه نرخ‌هایشان.
	 *
	 * @return array
	 */
	public static function shipping_packages() {
		if ( ! WC()->cart || ! WC()->cart->needs_shipping() || ! WC()->cart->show_shipping() ) {
			return array();
		}

		return WC()->shipping()->get_packages();
	}

	/**
	 * رندر بلوک شیوهٔ ارسال. هم در تمپلیت تسویه صدا زده می‌شود و هم موقع
	 * به‌روزرسانی ایجکسی سفارش.
	 */
	public static function render_shipping_block() {
		$packages = self::shipping_packages();
		?>
		<div class="ard-ck__ship" data-ard-ship>
			<?php if ( empty( $packages ) ) : ?>
				<p class="ard-ck__ship-empty">برای دیدن روش‌های ارسال، آدرس تحویل را کامل کن.</p>
			<?php else : ?>
				<?php
				foreach ( $packages as $index => $package ) :
					$available = $package['rates'];
					$chosen    = isset( WC()->session->chosen_shipping_methods[ $index ] ) ? WC()->session->chosen_shipping_methods[ $index ] : '';
					?>
					<?php if ( empty( $available ) ) : ?>
						<p class="ard-ck__ship-empty">
							<?php echo wp_kses_post( apply_filters( 'woocommerce_no_shipping_available_html', 'روشی برای ارسال به این آدرس پیدا نشد. لطفاً آدرس دیگری انتخاب کن یا با پشتیبانی تماس بگیر.' ) ); ?>
						</p>
					<?php else : ?>
						<?php foreach ( $available as $method ) : ?>
							<?php
							$id    = 'ard-ship-' . $index . '-' . sanitize_title( $method->get_id() );
							$is_on = $method->get_id() === $chosen;
							?>
							<label class="ard-ck__opt<?php echo $is_on ? ' is-on' : ''; ?>" for="<?php echo esc_attr( $id ); ?>">
								<input
									type="radio"
									name="shipping_method[<?php echo esc_attr( $index ); ?>]"
									id="<?php echo esc_attr( $id ); ?>"
									value="<?php echo esc_attr( $method->get_id() ); ?>"
									class="shipping_method"
									<?php checked( $is_on, true ); ?>
								>
								<span class="ard-ck__opt-dot" aria-hidden="true"></span>
								<span class="ard-ck__opt-text">
									<span class="ard-ck__opt-title"><?php echo wp_kses_post( $method->get_label() ); ?></span>
									<?php if ( $method->get_meta_data() ) : ?>
										<span class="ard-ck__opt-note">
											<?php
											foreach ( $method->get_meta_data() as $key => $value ) {
												echo esc_html( $key . ': ' . $value . ' ' );
											}
											?>
										</span>
									<?php endif; ?>
								</span>
								<span class="ard-ck__opt-price">
									<?php
									if ( $method->get_cost() > 0 ) {
										echo wp_kses_post( wc_price( (float) $method->get_cost() + (float) $method->get_shipping_tax() ) );
									} else {
										echo '<span class="ard-ck__free">رایگان</span>';
									}
									?>
								</span>
							</label>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php endforeach; ?>

				<?php do_action( 'woocommerce_after_shipping_rate_ardal' ); ?>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * @param array $fragments
	 * @return array
	 */
	public static function shipping_fragment( $fragments ) {
		ob_start();
		self::render_shipping_block();
		$fragments['.ard-ck__ship'] = ob_get_clean();

		return $fragments;
	}

	/* ============================================================
	   فاکتور رسمی
	   ============================================================ */

	/**
	 * @param array $fields
	 * @return array
	 */
	public static function invoice_field( $fields ) {
		$fields['order']['ardal_official_invoice'] = array(
			'type'     => 'checkbox',
			'label'    => 'فاکتور رسمی می‌خواهم',
			'required' => false,
			'class'    => array( 'form-row-wide', 'ard-ck__invoice' ),
			'priority' => 200,
		);

		return $fields;
	}

	/**
	 * @param WC_Order $order
	 * @param array    $data
	 */
	public static function save_invoice_field( $order, $data ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- ووکامرس نانس تسویه را پیش از این بررسی کرده.
		if ( ! empty( $_POST['ardal_official_invoice'] ) ) {
			$order->update_meta_data( self::INVOICE_META, 'yes' );
		}
	}

	/**
	 * نمایش درخواست فاکتور رسمی در صفحهٔ سفارش پیشخوان.
	 *
	 * @param WC_Order $order
	 */
	public static function admin_invoice_note( $order ) {
		if ( 'yes' !== $order->get_meta( self::INVOICE_META ) ) {
			return;
		}

		echo '<p><strong>فاکتور رسمی:</strong> مشتری درخواست فاکتور رسمی کرده است.</p>';
	}
}
