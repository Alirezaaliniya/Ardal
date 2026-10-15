<?php
/**
 * ستون کناری تسویه (کالاها، جمع سفارش، دکمهٔ پرداخت) — قالب الانو.
 *
 * ریشه باید یک عنصر با کلاس woocommerce-checkout-review-order-table بماند؛
 * ووکامرس با هر تغییر آدرس، روش ارسال یا تیک کیف پول همین را جایگزین می‌کند.
 * دکمهٔ ثبت سفارش هم مثل دیزاین اینجاست، با همهٔ قلاب‌های اطرافش.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-checkout-review-order-table ard-elc__review">

	<table class="ard-elc__hooks shop_table" role="presentation"><tbody><?php do_action( 'woocommerce_review_order_before_cart_contents' ); ?></tbody></table>

	<div class="ard-elc__minis">
		<?php
		foreach ( WC()->cart->get_cart() as $ard_key => $ard_item ) {
			$ard_product = apply_filters( 'woocommerce_cart_item_product', $ard_item['data'], $ard_item, $ard_key );

			if ( ! $ard_product || ! $ard_product->exists() || $ard_item['quantity'] <= 0 || ! apply_filters( 'woocommerce_checkout_cart_item_visible', true, $ard_item, $ard_key ) ) {
				continue;
			}

			$ard_prices = Ardal_Shop_Elano::item_prices( $ard_product );
			?>
			<div class="ard-elc__mini <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $ard_item, $ard_key ) ); ?>">
				<span class="ard-elc__mini-media"><?php echo $ard_product->get_image( 'woocommerce_gallery_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="ard-elc__mini-body">
					<span class="ard-elc__mini-name">
						<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $ard_product->get_name(), $ard_item, $ard_key ) ); ?>
					</span>
					<span class="ard-elc__mini-row">
						<span class="ard-elc__mini-qty" title="تعداد"><?php echo esc_html( Ardal_Helpers::fa_digits( $ard_item['quantity'] ) ); ?></span>
						<span class="ard-elc__price ard-elc__price--sm">
							<?php if ( $ard_prices['off'] > 0 && 'yes' === Ardal_Shop_Elano::opt( 'show_off_badge' ) ) : ?>
								<span class="ard-elc__off"><?php echo esc_html( Ardal_Helpers::fa_digits( $ard_prices['off'] ) ); ?>٪</span>
							<?php endif; ?>
							<span class="ard-elc__price-num"><?php echo Ardal_Shop_Elano::money( $ard_prices['price'], 'vertical' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</span>
					</span>
				</span>
			</div>
			<?php
		}
		?>
	</div>

	<table class="ard-elc__hooks shop_table" role="presentation"><tbody><?php do_action( 'woocommerce_review_order_after_cart_contents' ); ?></tbody></table>

	<div class="ard-elc__rows">
		<?php Ardal_Shop_Elano::render_rows( Ardal_Shop_Elano::summary_rows( true ) ); ?>
	</div>

	<table class="ard-elc__hooks shop_table" role="presentation">
		<tbody>
			<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
			<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
		</tbody>
	</table>

	<div class="form-row place-order ard-elc__place">
		<noscript>
			مرورگر شما جاوااسکریپت را اجرا نمی‌کند؛ پیش از ثبت، سفارش را بازبینی کنید.
			<button type="submit" class="button" name="woocommerce_checkout_update_totals" value="بازبینی سفارش">بازبینی سفارش</button>
		</noscript>

		<?php wc_get_template( 'checkout/terms.php' ); ?>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

		<div class="ard-elc__stack">
			<span class="ard-elc__stack-layer" aria-hidden="true"></span>
			<span class="ard-elc__stack-layer" aria-hidden="true"></span>
			<span class="ard-elc__stack-layer" aria-hidden="true"></span>
			<span class="ard-elc__stack-layer" aria-hidden="true"></span>
			<?php
			$ard_label = Ardal_Shop_Elano::opt( 'place_btn' );

			echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'woocommerce_order_button_html',
				'<button type="submit" class="button alt ard-elc__stack-btn" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $ard_label ) . '" data-value="' . esc_attr( $ard_label ) . '"><span>' . esc_html( $ard_label ) . '</span>' . Ardal_Shop_Elano::icon( 'back', 12 ) . '</button>'
			);
			?>
		</div>

		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
	</div>
</div>
