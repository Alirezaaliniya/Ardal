<?php
/**
 * جمع سفارش در ستون کناری سبد — قالب الانو.
 *
 * ریشه باید .cart_totals بماند؛ اسکریپت سبد ووکامرس بعد از هر به‌روزرسانی
 * همین عنصر را با نسخهٔ تازه جایگزین می‌کند.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;

$ard_ship = WC()->customer && WC()->customer->has_calculated_shipping();
?>
<div class="cart_totals ard-elc__totals <?php echo $ard_ship ? 'calculated_shipping' : ''; ?>">
	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<div class="ard-elc__rows">
		<?php Ardal_Shop_Elano::render_rows( Ardal_Shop_Elano::summary_rows( $ard_ship ) ); ?>
	</div>

	<table class="ard-elc__hooks shop_table" role="presentation">
		<tbody>
			<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>
			<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
		</tbody>
	</table>

	<div class="wc-proceed-to-checkout ard-elc__proceed">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>
</div>
