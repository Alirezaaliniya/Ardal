<?php
/**
 * درگاه پرداختی — قالب الانو: نکات پیش از پرداخت کنار فهرست درگاه‌ها.
 *
 * ریشه همان #payment.woocommerce-checkout-payment است چون ووکامرس با هر
 * به‌روزرسانی آن را از نو می‌سازد. دکمهٔ ثبت سفارش در ستون کناری است.
 *
 * @package Ardal
 *
 * @var array $available_gateways
 */

defined( 'ABSPATH' ) || exit;

if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}

$ard_notes_title = trim( (string) Ardal_Shop_Elano::opt( 'notes_title' ) );
$ard_notes_text  = trim( (string) Ardal_Shop_Elano::opt( 'notes_text' ) );
?>
<div id="payment" class="woocommerce-checkout-payment ard-elc__panel ard-elc__pay<?php echo '' === $ard_notes_title && '' === $ard_notes_text ? ' is-solo' : ''; ?>">
	<?php if ( '' !== $ard_notes_title || '' !== $ard_notes_text ) : ?>
		<div class="ard-elc__pay-notes">
			<?php if ( '' !== $ard_notes_title ) : ?>
				<strong class="ard-elc__pay-title"><?php echo esc_html( $ard_notes_title ); ?></strong>
			<?php endif; ?>
			<?php if ( '' !== $ard_notes_text ) : ?>
				<p><?php echo nl2br( esc_html( $ard_notes_text ) ); ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="ard-elc__pay-list">
		<?php
		// کیف پول اردال و هر چیز دیگری که بالای درگاه‌ها می‌نشیند.
		do_action( 'ardal_checkout_payment_top' );
		?>

		<?php if ( WC()->cart && WC()->cart->needs_payment() ) : ?>
			<ul class="wc_payment_methods payment_methods methods ard-elc__gateways">
				<?php
				if ( ! empty( $available_gateways ) ) {
					foreach ( $available_gateways as $gateway ) {
						wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
					}
				} else {
					echo '<li class="ard-elc__note">' . esc_html(
						apply_filters(
							'woocommerce_no_available_payment_methods_message',
							WC()->customer->get_billing_country()
								? 'روش پرداختی در دسترس نیست. لطفاً با پشتیبانی تماس بگیرید.'
								: 'برای دیدن روش‌های پرداخت، اطلاعات را کامل کنید.'
						)
					) . '</li>';
				}
				?>
			</ul>
		<?php else : ?>
			<p class="ard-elc__note">این سفارش نیازی به پرداخت آنلاین ندارد.</p>
		<?php endif; ?>
	</div>

	<?php do_action( 'woocommerce_review_order_after_payment' ); ?>
</div>
