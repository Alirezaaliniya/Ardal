<?php
/**
 * یک درگاه پرداخت — قالب الانو.
 *
 * کلاس‌ها و شناسه‌ها همان‌های ووکامرس‌اند تا اسکریپت تسویه باکس توضیح هر
 * درگاه را باز و بسته کند. لوگوی درگاه از get_icon خودش می‌آید؛ اگر نداشت،
 * حرف اول نامش.
 *
 * @package Ardal
 *
 * @var WC_Payment_Gateway $gateway
 */

defined( 'ABSPATH' ) || exit;

$ard_icon  = $gateway->get_icon();
$ard_title = wp_strip_all_tags( $gateway->get_title() );
?>
<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?> ard-elc__gateway">
	<label class="ard-elc__opt<?php echo $gateway->chosen ? ' is-on' : ''; ?>" for="payment_method_<?php echo esc_attr( $gateway->id ); ?>" data-ard-opt>
		<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />
		<span class="ard-elc__opt-main">
			<span class="ard-elc__dot" aria-hidden="true"><?php echo Ardal_Shop_Elano::icon( 'check', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<span><?php echo wp_kses_post( $gateway->get_title() ); ?></span>
		</span>
		<span class="ard-elc__logo">
			<?php if ( $ard_icon ) : ?>
				<?php echo wp_kses_post( $ard_icon ); ?>
			<?php else : ?>
				<span class="ard-elc__logo-letter" aria-hidden="true"><?php echo esc_html( mb_substr( $ard_title, 0, 1 ) ); ?></span>
			<?php endif; ?>
		</span>
	</label>

	<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?> ard-elc__gateway-box" <?php echo ! $gateway->chosen ? 'style="display:none;"' : ''; ?>>
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</li>
