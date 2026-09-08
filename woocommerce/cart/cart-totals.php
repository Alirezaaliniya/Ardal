<?php
/**
 * باکس جمع سفارش در سبد خرید.
 *
 * جایگزین cart/cart-totals.php ووکامرس. جدول به کارت تبدیل شده ولی همهٔ
 * قلاب‌ها و توابع محاسبهٔ ووکامرس سر جای خودشان هستند.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="ard-shop__summary cart_totals <?php echo WC()->customer->has_calculated_shipping() ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<h2 class="ard-shop__summary-title">جمع سفارش</h2>

	<div class="ard-shop__rows">
		<div class="ard-shop__row cart-subtotal">
			<span class="ard-shop__row-key">مبلغ کالاها</span>
			<span class="ard-shop__row-val"><?php wc_cart_totals_subtotal_html(); ?></span>
		</div>

		<?php foreach ( WC()->cart->get_coupons() as $ard_code => $ard_coupon ) : ?>
			<div class="ard-shop__row cart-discount coupon-<?php echo esc_attr( sanitize_title( $ard_code ) ); ?>">
				<span class="ard-shop__row-key"><?php wc_cart_totals_coupon_label( $ard_coupon ); ?></span>
				<span class="ard-shop__row-val ard-shop__row-val--off"><?php wc_cart_totals_coupon_html( $ard_coupon ); ?></span>
			</div>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
			<?php wc_cart_totals_shipping_html(); ?>
			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $ard_fee ) : ?>
			<div class="ard-shop__row fee">
				<span class="ard-shop__row-key"><?php echo esc_html( $ard_fee->name ); ?></span>
				<span class="ard-shop__row-val"><?php wc_cart_totals_fee_html( $ard_fee ); ?></span>
			</div>
		<?php endforeach; ?>

		<?php
		if ( wc_tax_enabled() && WC()->cart->display_prices_including_tax() === false ) :
			$ard_taxable = WC()->cart->get_cart_contents_taxes();

			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) :
				foreach ( WC()->cart->get_tax_totals() as $ard_code => $ard_tax ) :
					?>
					<div class="ard-shop__row tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $ard_code ) ); ?>">
						<span class="ard-shop__row-key"><?php echo esc_html( $ard_tax->label ); ?></span>
						<span class="ard-shop__row-val"><?php echo wp_kses_post( $ard_tax->formatted_amount ); ?></span>
					</div>
					<?php
				endforeach;
			elseif ( ! empty( $ard_taxable ) ) :
				?>
				<div class="ard-shop__row tax-total">
					<span class="ard-shop__row-key"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
					<span class="ard-shop__row-val"><?php wc_cart_totals_taxes_total_html(); ?></span>
				</div>
				<?php
			endif;
		endif;
		?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>
	</div>

	<div class="ard-shop__total order-total">
		<span>مبلغ قابل پرداخت</span>
		<span class="ard-shop__total-val"><?php wc_cart_totals_order_total_html(); ?></span>
	</div>

	<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

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
