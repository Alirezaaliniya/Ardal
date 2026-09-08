<?php
/**
 * باکس جمع سفارش در سبد خرید.
 *
 * جایگزین cart/cart-totals.php ووکامرس.
 *
 * ردیف‌ها داخل یک جدول واقعی می‌مانند چون قلاب‌های ووکامرس (و افزونه‌هایی که
 * به آن‌ها وصل‌اند) `<tr>` چاپ می‌کنند و مرورگر `<tr>` بیرون از جدول را موقع
 * پارس دور می‌ریزد. ظاهر کارتی را CSS می‌سازد.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="ard-shop__summary cart_totals <?php echo WC()->customer->has_calculated_shipping() ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<h2 class="ard-shop__summary-title">جمع سفارش</h2>

	<table class="ard-shop__rows shop_table">
		<tbody>
			<tr class="cart-subtotal">
				<th>مبلغ کالاها</th>
				<td data-title="مبلغ کالاها"><?php wc_cart_totals_subtotal_html(); ?></td>
			</tr>

			<?php foreach ( WC()->cart->get_coupons() as $ard_code => $ard_coupon ) : ?>
				<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $ard_code ) ); ?>">
					<th><?php wc_cart_totals_coupon_label( $ard_coupon ); ?></th>
					<td class="ard-shop__row-val--off" data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $ard_coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $ard_coupon ); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
				<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
				<?php wc_cart_totals_shipping_html(); ?>
				<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
			<?php endif; ?>

			<?php foreach ( WC()->cart->get_fees() as $ard_fee ) : ?>
				<tr class="fee">
					<th><?php echo esc_html( $ard_fee->name ); ?></th>
					<td data-title="<?php echo esc_attr( $ard_fee->name ); ?>"><?php wc_cart_totals_fee_html( $ard_fee ); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php
			if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) :
				$ard_taxable = WC()->cart->get_cart_contents_taxes();

				if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) :
					foreach ( WC()->cart->get_tax_totals() as $ard_code => $ard_tax ) :
						?>
						<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $ard_code ) ); ?>">
							<th><?php echo esc_html( $ard_tax->label ); ?></th>
							<td data-title="<?php echo esc_attr( $ard_tax->label ); ?>"><?php echo wp_kses_post( $ard_tax->formatted_amount ); ?></td>
						</tr>
						<?php
					endforeach;
				elseif ( ! empty( $ard_taxable ) ) :
					?>
					<tr class="tax-total">
						<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
						<td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
					</tr>
					<?php
				endif;
			endif;
			?>

			<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

			<tr class="order-total">
				<th>مبلغ قابل پرداخت</th>
				<td data-title="مبلغ قابل پرداخت"><?php wc_cart_totals_order_total_html(); ?></td>
			</tr>

			<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
		</tbody>
	</table>

	<div class="ard-shop__cta wc-proceed-to-checkout">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>

	<a class="ard-shop__back" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">بازگشت به فروشگاه</a>

	<ul class="ard-shop__guards">
		<?php foreach ( Ardal_Shop::guards() as $ard_guard ) : ?>
			<li>
				<span class="ard-shop__guard-tick" aria-hidden="true">
					<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"></path></svg>
				</span>
				<?php echo esc_html( $ard_guard ); ?>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>
</div>
