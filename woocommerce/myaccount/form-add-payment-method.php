<?php
/**
 * افزودن روش پرداخت.
 *
 * جایگزین myaccount/form-add-payment-method.php ووکامرس. فیلدهای درگاه‌ها را
 * خود ووکامرس چاپ می‌کند؛ اینجا فقط قاب و دکمه‌ها به سبک پنل درآمده‌اند.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;

$ard_gateways = WC()->payment_gateways->get_available_payment_gateways();
$ard_back     = wc_get_account_endpoint_url( 'payment-methods' );
?>
<div class="ard-ac__stack">

	<div class="ard-ac__card ard-ac__detail-head">
		<a class="ard-ac__back" href="<?php echo esc_url( $ard_back ); ?>" aria-label="بازگشت به روش‌های پرداخت">
			<?php echo Ardal_Account::icon( 'back', 17 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>
		<div class="ard-ac__detail-text">
			<h2 class="ard-ac__ptitle">افزودن روش پرداخت</h2>
			<p class="ard-ac__csub">روش پرداختی که اینجا ذخیره کنی، در تسویه حساب‌های بعدی پیشنهاد می‌شود.</p>
		</div>
	</div>

	<?php if ( $ard_gateways ) : ?>
		<div class="ard-ac__card">
			<form id="add_payment_method" method="post">
				<div id="payment" class="woocommerce-Payment ard-ac__gateways">
					<ul class="woocommerce-PaymentMethods payment_methods methods" aria-label="روش‌های پرداخت">
						<?php
						// درگاه پیش‌فرض همان اولی است، مثل تمپلیت خود ووکامرس.
						if ( count( $ard_gateways ) ) {
							current( $ard_gateways )->set_current();
						}

						foreach ( $ard_gateways as $ard_gateway ) :
							?>
							<li class="ard-ac__gateway woocommerce-PaymentMethod woocommerce-PaymentMethod--<?php echo esc_attr( $ard_gateway->id ); ?> payment_method_<?php echo esc_attr( $ard_gateway->id ); ?>">
								<label for="payment_method_<?php echo esc_attr( $ard_gateway->id ); ?>" class="ard-ac__gateway-head">
									<input
										id="payment_method_<?php echo esc_attr( $ard_gateway->id ); ?>"
										type="radio"
										class="input-radio"
										name="payment_method"
										value="<?php echo esc_attr( $ard_gateway->id ); ?>"
										<?php checked( $ard_gateway->chosen, true ); ?>
									>
									<span class="ard-ac__gateway-title"><?php echo wp_kses_post( $ard_gateway->get_title() ); ?></span>
									<span class="ard-ac__gateway-icon"><?php echo wp_kses_post( $ard_gateway->get_icon() ); ?></span>
								</label>

								<?php if ( $ard_gateway->has_fields() || $ard_gateway->get_description() ) : ?>
									<div class="woocommerce-PaymentBox woocommerce-PaymentBox--<?php echo esc_attr( $ard_gateway->id ); ?> payment_box payment_method_<?php echo esc_attr( $ard_gateway->id ); ?>" style="display:none">
										<?php $ard_gateway->payment_fields(); ?>
									</div>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>

					<?php do_action( 'woocommerce_add_payment_method_form_bottom' ); ?>

					<div class="ard-ac__form-actions">
						<?php wp_nonce_field( 'woocommerce-add-payment-method', 'woocommerce-add-payment-method-nonce' ); ?>
						<button
							type="submit"
							class="ard-ac__btn ard-ac__btn--primary woocommerce-Button woocommerce-Button--alt button alt"
							id="place_order"
							value="افزودن روش پرداخت"
						>افزودن روش پرداخت</button>
						<a class="ard-ac__btn ard-ac__btn--ghost" href="<?php echo esc_url( $ard_back ); ?>">انصراف</a>
						<input type="hidden" name="woocommerce_add_payment_method" id="woocommerce_add_payment_method" value="1">
					</div>
				</div>
			</form>
		</div>
	<?php else : ?>
		<div class="ard-ac__card ard-ac__empty">
			<span class="ard-ac__empty-icon"><?php echo Ardal_Account::icon( 'wallet', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<h3 class="ard-ac__ctitle">درگاهی برای ذخیره کردن نیست</h3>
			<p class="ard-ac__csub">روش پرداخت تازه فقط هنگام تسویه حساب اضافه می‌شود. اگر کمک لازم داشتی با پشتیبانی تماس بگیر.</p>
			<a class="ard-ac__btn ard-ac__btn--ghost" href="<?php echo esc_url( $ard_back ); ?>">بازگشت</a>
		</div>
	<?php endif; ?>
</div>
