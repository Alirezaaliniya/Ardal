<?php
/**
 * صفحهٔ «سفارش ثبت شد».
 *
 * جایگزین checkout/thankyou.php ووکامرس. در فایل‌های دیزاین صفحهٔ جداگانه‌ای
 * برای این مرحله نبود، پس با همان زبان بصری سبد و تسویه ساخته شده.
 *
 * @package Ardal
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="ard-shop ard-ck">

	<?php if ( $order ) : ?>

		<?php if ( ! ( class_exists( 'Ardal_Wallet_Checkout' ) && Ardal_Wallet_Checkout::is_topup_order( $order ) ) ) : ?>
		<div class="ard-ck__steps-bar">
			<ol class="ard-ck__steps">
				<?php foreach ( Ardal_Shop::steps( 'done' ) as $ard_step ) : ?>
					<li class="ard-ck__step is-<?php echo esc_attr( $ard_step['state'] ); ?>">
						<span class="ard-ck__step-num"><?php echo esc_html( $ard_step['number'] ); ?></span>
						<span class="ard-ck__step-label"><?php echo esc_html( $ard_step['label'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
		<?php endif; ?>

		<?php if ( class_exists( 'Ardal_Wallet_Checkout' ) && Ardal_Wallet_Checkout::is_topup_order( $order ) && ! $order->has_status( 'failed' ) ) : ?>

			<?php
			// شارژ کیف پول هم سفارش ووکامرس است ولی «سفارش» نیست؛ شمارهٔ سفارش و
			// «پیگیری سفارش» برای کسی که فقط اعتبار خریده معنایی ندارد.
			Ardal_Wallet_Render::topup_done( $order );
			?>

			<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
			<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

		<?php elseif ( $order->has_status( 'failed' ) ) : ?>

			<div class="ard-shop__empty ard-shop__empty--failed">
				<span class="ard-shop__empty-icon ard-shop__empty-icon--red" aria-hidden="true">
					<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"></path></svg>
				</span>
				<h1 class="ard-shop__title">پرداخت انجام نشد</h1>
				<p class="ard-shop__empty-note">پرداخت این سفارش کامل نشد. می‌توانی دوباره تلاش کنی؛ کالاها هنوز برایت رزرو هستند.</p>

				<div class="ard-ck__done-actions">
					<a class="ard-shop__btn ard-shop__btn--accent" href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>">تلاش دوباره برای پرداخت</a>
					<?php if ( is_user_logged_in() ) : ?>
						<a class="ard-shop__btn ard-shop__btn--ghost" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">پنل کاربری</a>
					<?php endif; ?>
				</div>
			</div>

		<?php else : ?>

			<div class="ard-ck__done">
				<span class="ard-ck__done-tick" aria-hidden="true">
					<svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"></path></svg>
				</span>

				<h1 class="ard-shop__title">
					<?php echo esc_html( apply_filters( 'woocommerce_thankyou_order_received_text', 'سفارشت ثبت شد، ممنون!', $order ) ); ?>
				</h1>
				<p class="ard-ck__done-note">جزئیات سفارش برایت ایمیل شد و وضعیتش را در پنل کاربری دنبال می‌کنی.</p>

				<ul class="ard-ck__facts">
					<li>
						<span class="ard-ck__fact-key">شماره سفارش</span>
						<span class="ard-ck__fact-val"><?php echo esc_html( Ardal_Helpers::fa_digits( $order->get_order_number() ) ); ?></span>
					</li>
					<li>
						<span class="ard-ck__fact-key">تاریخ</span>
						<span class="ard-ck__fact-val">
							<?php echo esc_html( $order->get_date_created() ? Ardal_Jalali::format( $order->get_date_created()->getTimestamp() ) : '—' ); ?>
						</span>
					</li>
					<li>
						<span class="ard-ck__fact-key">مبلغ</span>
						<span class="ard-ck__fact-val"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></span>
					</li>
					<li>
						<span class="ard-ck__fact-key">روش پرداخت</span>
						<span class="ard-ck__fact-val"><?php echo esc_html( $order->get_payment_method_title() ? $order->get_payment_method_title() : '—' ); ?></span>
					</li>
				</ul>

				<div class="ard-ck__done-actions">
					<?php if ( is_user_logged_in() ) : ?>
						<a class="ard-shop__btn ard-shop__btn--primary" href="<?php echo esc_url( $order->get_view_order_url() ); ?>">پیگیری سفارش</a>
					<?php endif; ?>
					<a class="ard-shop__btn ard-shop__btn--ghost" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">ادامه خرید</a>
				</div>
			</div>

			<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
			<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

		<?php endif; ?>

	<?php else : ?>

		<div class="ard-ck__done">
			<h1 class="ard-shop__title">
				<?php echo esc_html( apply_filters( 'woocommerce_thankyou_order_received_text', 'سفارشت ثبت شد، ممنون!', null ) ); ?>
			</h1>
		</div>

	<?php endif; ?>
</div>
