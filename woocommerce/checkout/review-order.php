<?php
/**
 * باکس «سفارش شما» در تسویه حساب.
 *
 * جایگزین checkout/review-order.php ووکامرس.
 *
 * دو نکتهٔ ساختاری که نباید عوض شوند:
 *
 * ۱. ریشهٔ فایل باید دقیقاً یک عنصر با کلاس
 *    `woocommerce-checkout-review-order-table` باشد، چون ووکامرس موقع تغییر
 *    آدرس یا روش ارسال همین عنصر را با خروجی تازه replace می‌کند.
 * ۲. ردیف‌های سفارش داخل یک جدول واقعی می‌مانند، چون قلاب‌های
 *    `woocommerce_review_order_*` و افزونه‌های وصل به آن‌ها `<tr>` چاپ می‌کنند
 *    و مرورگر `<tr>` بیرون از جدول را موقع پارس دور می‌ریزد.
 *
 * دکمهٔ ثبت سفارش هم — برخلاف تمپلیت پیش‌فرض که آن را داخل باکس پرداخت
 * می‌گذارد — اینجاست، چون در دیزاین زیر جمع سفارش می‌نشیند. قلاب‌های مربوط به
 * دکمه با آن جابه‌جا شده‌اند تا درگاه‌ها همچنان بتوانند دکمه را عوض کنند.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;

$ard_shipping = (float) WC()->cart->get_shipping_total();
?>
<div class="woocommerce-checkout-review-order-table ard-shop__summary ard-ck__summary">

	<div class="ard-ck__summary-head">
		<h2 class="ard-shop__summary-title">سفارش شما</h2>
		<a class="ard-ck__edit" href="<?php echo esc_url( wc_get_cart_url() ); ?>">ویرایش سبد</a>
	</div>

	<table class="ard-ck__table shop_table">
		<tbody class="ard-ck__lines">
			<?php
			do_action( 'woocommerce_review_order_before_cart_contents' );

			foreach ( WC()->cart->get_cart() as $ard_key => $ard_item ) {
				$ard_product = apply_filters( 'woocommerce_cart_item_product', $ard_item['data'], $ard_item, $ard_key );

				if ( ! $ard_product || ! $ard_product->exists() || $ard_item['quantity'] <= 0 || ! apply_filters( 'woocommerce_checkout_cart_item_visible', true, $ard_item, $ard_key ) ) {
					continue;
				}

				$ard_meta = wc_get_formatted_cart_item_data( $ard_item, true );
				?>
				<tr class="ard-ck__line <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $ard_item, $ard_key ) ); ?>">
					<td class="ard-ck__line-media"><?php echo $ard_product->get_image( 'woocommerce_gallery_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>

					<td class="ard-ck__line-text">
						<span class="ard-ck__line-name">
							<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $ard_product->get_name(), $ard_item, $ard_key ) ); ?>
						</span>
						<span class="ard-ck__line-meta">
							<?php echo esc_html( Ardal_Helpers::fa_digits( $ard_item['quantity'] ) ); ?> عدد
							<?php echo '' !== $ard_meta ? ' · ' . esc_html( wp_strip_all_tags( $ard_meta ) ) : ''; ?>
						</span>
					</td>

					<td class="ard-ck__line-price">
						<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $ard_product, $ard_item['quantity'] ), $ard_item, $ard_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</td>
				</tr>
				<?php
			}

			do_action( 'woocommerce_review_order_after_cart_contents' );
			?>
		</tbody>

		<tfoot class="ard-shop__rows">
			<tr class="cart-subtotal">
				<th>مبلغ کالاها</th>
				<td><?php wc_cart_totals_subtotal_html(); ?></td>
			</tr>

			<?php foreach ( WC()->cart->get_coupons() as $ard_code => $ard_coupon ) : ?>
				<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $ard_code ) ); ?>">
					<th><?php wc_cart_totals_coupon_label( $ard_coupon ); ?></th>
					<td class="ard-shop__row-val--off"><?php wc_cart_totals_coupon_html( $ard_coupon ); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
				<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

				<?php
				// خود انتخاب روش ارسال در ستون اصلی است؛ اینجا فقط هزینه‌اش
				// نشان داده می‌شود تا رادیوها دوتایی نشوند.
				?>
				<tr class="ard-shop__row--shipcost">
					<th>هزینه ارسال</th>
					<td>
						<?php
						if ( $ard_shipping > 0 ) {
							echo wp_kses_post( wc_price( $ard_shipping + (float) WC()->cart->get_shipping_tax() ) );
						} else {
							echo '<span class="ard-ck__free">رایگان</span>';
						}
						?>
					</td>
				</tr>

				<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
			<?php endif; ?>

			<?php foreach ( WC()->cart->get_fees() as $ard_fee ) : ?>
				<tr class="fee">
					<th><?php echo esc_html( $ard_fee->name ); ?></th>
					<td><?php wc_cart_totals_fee_html( $ard_fee ); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php
			if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) :
				if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) :
					foreach ( WC()->cart->get_tax_totals() as $ard_code => $ard_tax ) :
						?>
						<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $ard_code ) ); ?>">
							<th><?php echo esc_html( $ard_tax->label ); ?></th>
							<td><?php echo wp_kses_post( $ard_tax->formatted_amount ); ?></td>
						</tr>
						<?php
					endforeach;
				else :
					?>
					<tr class="tax-total">
						<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
						<td><?php wc_cart_totals_taxes_total_html(); ?></td>
					</tr>
					<?php
				endif;
			endif;
			?>

			<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

			<tr class="order-total">
				<th>مبلغ قابل پرداخت</th>
				<td><?php wc_cart_totals_order_total_html(); ?></td>
			</tr>

			<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
		</tfoot>
	</table>

	<div class="form-row place-order ard-ck__place">
		<noscript>
			مرورگرت جاوااسکریپت را اجرا نمی‌کند. اول سفارش را بازبینی کن و بعد دکمهٔ زیر را بزن.
			<br>
			<button type="submit" class="ard-shop__btn ard-shop__btn--ghost" name="woocommerce_checkout_update_totals" value="بازبینی سفارش">بازبینی سفارش</button>
		</noscript>

		<?php wc_get_template( 'checkout/terms.php' ); ?>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

		<?php
		echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			'woocommerce_order_button_html',
			'<button type="submit" class="ard-shop__btn ard-shop__btn--accent ard-shop__btn--block button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( 'ثبت سفارش و پرداخت' ) . '" data-value="' . esc_attr( 'ثبت سفارش و پرداخت' ) . '">ثبت سفارش و پرداخت</button>'
		);
		?>

		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
	</div>

	<p class="ard-ck__terms-note">
		با ثبت سفارش، شرایط استفاده و رویهٔ مرجوعی کالا در <?php echo esc_html( get_bloginfo( 'name' ) ); ?> را می‌پذیرم.
	</p>
</div>
