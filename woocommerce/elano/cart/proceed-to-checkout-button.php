<?php
/**
 * دکمهٔ «اقدام به پرداخت» با لایه‌های پشت سرش — قالب الانو.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="ard-elc__stack">
	<span class="ard-elc__stack-layer" aria-hidden="true"></span>
	<span class="ard-elc__stack-layer" aria-hidden="true"></span>
	<span class="ard-elc__stack-layer" aria-hidden="true"></span>
	<span class="ard-elc__stack-layer" aria-hidden="true"></span>
	<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="checkout-button wc-forward ard-elc__stack-btn">
		<span><?php echo esc_html( Ardal_Shop_Elano::opt( 'pay_btn' ) ); ?></span><?php echo Ardal_Shop_Elano::icon( 'back', 12 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</a>
</div>
