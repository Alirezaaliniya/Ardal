<?php
/**
 * یک روش پرداخت.
 *
 * جایگزین checkout/payment-method.php ووکامرس. کلاس‌ها و شناسه‌ها همان‌های
 * ووکامرس‌اند تا اسکریپت تسویه بتواند باکس توضیح هر درگاه را باز و بسته کند.
 *
 * @package Ardal
 *
 * @var WC_Payment_Gateway $gateway
 */

defined( 'ABSPATH' ) || exit;
?>
<li class="ard-ck__gateway wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?>">
	<label class="ard-ck__opt<?php echo $gateway->chosen ? ' is-on' : ''; ?>" for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
		<input
			id="payment_method_<?php echo esc_attr( $gateway->id ); ?>"
			type="radio"
			class="input-radio"
			name="payment_method"
			value="<?php echo esc_attr( $gateway->id ); ?>"
			<?php checked( $gateway->chosen, true ); ?>
			data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>"
		>
		<span class="ard-ck__opt-dot" aria-hidden="true"></span>
		<span class="ard-ck__opt-text">
			<span class="ard-ck__opt-title"><?php echo wp_kses_post( $gateway->get_title() ); ?></span>
			<?php if ( $gateway->get_icon() ) : ?>
				<span class="ard-ck__opt-icon"><?php echo wp_kses_post( $gateway->get_icon() ); ?></span>
			<?php endif; ?>
		</span>
	</label>

	<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?> ard-ck__gateway-box" <?php echo ! $gateway->chosen ? 'style="display:none"' : ''; ?>>
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</li>
