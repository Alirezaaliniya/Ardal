<?php
/**
 * باکس شیوهٔ پرداخت.
 *
 * جایگزین checkout/payment.php ووکامرس. ریشه باید همان
 * `.woocommerce-checkout-payment` بماند چون ووکامرس این عنصر را با ایجکس
 * جایگزین می‌کند. دکمهٔ ثبت سفارش اینجا نیست و به باکس «سفارش شما» منتقل
 * شده — مطابق دیزاین.
 *
 * @package Ardal
 *
 * @var array $available_gateways
 * @var bool  $order_button_text
 */

defined( 'ABSPATH' ) || exit;

if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}
?>
<div id="payment" class="woocommerce-checkout-payment ard-ck__pay">
	<?php if ( WC()->cart && WC()->cart->needs_payment() ) : ?>
		<ul class="wc_payment_methods payment_methods methods ard-ck__gateways" aria-label="روش‌های پرداخت">
			<?php
			if ( ! empty( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway ) {
					wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
				}
			} else {
				echo '<li class="ard-ck__pay-empty">';
				echo esc_html(
					apply_filters(
						'woocommerce_no_available_payment_methods_message',
						WC()->customer->get_billing_country()
							? 'روش پرداختی در دسترس نیست. لطفاً با پشتیبانی تماس بگیر.'
							: 'برای دیدن روش‌های پرداخت، اطلاعات بالا را کامل کن.'
					)
				);
				echo '</li>';
			}
			?>
		</ul>
	<?php else : ?>
		<p class="ard-ck__pay-empty">این سفارش نیازی به پرداخت آنلاین ندارد.</p>
	<?php endif; ?>

	<?php do_action( 'woocommerce_review_order_after_payment' ); ?>
</div>
