<?php
/**
 * سبد خرید — قالب الانو.
 *
 * کارت هر کالا، کد تخفیف و ستون کناری (سربرگ صورتی، جمع سفارش، اقدام به
 * پرداخت). نام فیلدها و کلاس‌های ووکامرس سر جایشان هستند تا اسکریپت سبد
 * (به‌روزرسانی ایجکسی تعداد، حذف کالا، کوپن) بدون تغییر کار کند.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );

$ard_free = 'yes' === Ardal_Shop_Elano::opt( 'show_free' ) && class_exists( 'Ardal_Shop' ) ? Ardal_Shop::free_shipping() : null;
?>
<div class="ard-elc__wrap">

	<main class="ard-elc__main">
		<form class="woocommerce-cart-form ard-elc__form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
			<?php do_action( 'woocommerce_before_cart_table' ); ?>

			<?php if ( $ard_free ) : ?>
				<div class="ard-elc__free-bar<?php echo $ard_free['reached'] ? ' is-reached' : ''; ?>">
					<?php if ( $ard_free['reached'] ) : ?>
						<span>سفارش شما شامل ارسال رایگان است.</span>
					<?php else : ?>
						<span><?php echo esc_html( sprintf( '%s تومان دیگر خرید کنید تا ارسال رایگان شود.', Ardal_Helpers::price( $ard_free['left'] ) ) ); ?></span>
						<span class="ard-elc__free-track" aria-hidden="true"><span style="inline-size:<?php echo esc_attr( $ard_free['percent'] ); ?>%"></span></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php
			// قلاب‌های ردیفی ووکامرس <tr> چاپ می‌کنند؛ داخل جدول بمانند تا مرورگر
			// دورشان نریزد. خود کارت‌ها div هستند.
			?>
			<table class="ard-elc__hooks shop_table cart" role="presentation"><tbody><?php do_action( 'woocommerce_before_cart_contents' ); ?></tbody></table>

			<div class="ard-elc__items woocommerce-cart-form__contents">
				<?php
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 || ! apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						continue;
					}

					$ard_link   = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					$ard_prices = Ardal_Shop_Elano::item_prices( $_product );
					$ard_thumb  = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_thumbnail' ), $cart_item, $cart_item_key );
					$ard_meta   = wc_get_formatted_cart_item_data( $cart_item, true );
					?>
					<div class="ard-elc__item woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
						<div class="ard-elc__media">
							<?php echo $ard_link ? '<a href="' . esc_url( $ard_link ) . '">' . $ard_thumb . '</a>' : $ard_thumb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>

						<div class="ard-elc__body">
							<div class="ard-elc__top">
								<div class="ard-elc__name">
									<?php
									$ard_name = $ard_link ? sprintf( '<a href="%s">%s</a>', esc_url( $ard_link ), $_product->get_name() ) : $_product->get_name();
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $ard_name, $cart_item, $cart_item_key ) );

									do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

									if ( '' !== trim( $ard_meta ) ) {
										echo '<span class="ard-elc__meta">' . esc_html( Ardal_Helpers::fa_digits( wp_strip_all_tags( $ard_meta ) ) ) . '</span>';
									}

									if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<span class="ard-elc__meta">در انتظار تأمین</span>', $product_id ) );
									}
									?>
								</div>

								<span class="product-remove">
									<?php
									echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="remove ard-elc__trash" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s">%s</a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											esc_attr( sprintf( 'حذف %s از سبد خرید', wp_strip_all_tags( $_product->get_name() ) ) ),
											esc_attr( $product_id ),
											esc_attr( $_product->get_sku() ),
											esc_attr( $cart_item_key ),
											Ardal_Shop_Elano::icon( 'trash', 20 )
										),
										$cart_item_key
									);
									?>
								</span>
							</div>

							<div class="ard-elc__bottom">
								<?php
								$ard_qty = woocommerce_quantity_input(
									array(
										'input_name'   => "cart[{$cart_item_key}][qty]",
										'input_value'  => $cart_item['quantity'],
										'max_value'    => $_product->get_max_purchase_quantity(),
										'min_value'    => '0',
										'product_name' => $_product->get_name(),
									),
									$_product,
									false
								);
								$ard_qty = apply_filters( 'woocommerce_cart_item_quantity', $ard_qty, $cart_item_key, $cart_item );
								?>
								<?php if ( $_product->is_sold_individually() ) : ?>
									<div class="ard-elc__qty is-single"><?php echo $ard_qty; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
								<?php else : ?>
									<div class="ard-elc__qty" data-ard-qty>
										<button type="button" class="ard-elc__qty-btn" data-ard-qty-up aria-label="افزایش تعداد"><?php echo Ardal_Shop_Elano::icon( 'plus', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
										<?php echo $ard_qty; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										<button type="button" class="ard-elc__qty-btn" data-ard-qty-down aria-label="کاهش تعداد"><?php echo Ardal_Shop_Elano::icon( 'minus', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
									</div>
								<?php endif; ?>

								<div class="ard-elc__price">
									<?php if ( $ard_prices['off'] > 0 && 'yes' === Ardal_Shop_Elano::opt( 'show_off_badge' ) ) : ?>
										<span class="ard-elc__off"><?php echo esc_html( Ardal_Helpers::fa_digits( $ard_prices['off'] ) ); ?>٪</span>
									<?php endif; ?>
									<span class="ard-elc__price-num"><?php echo Ardal_Shop_Elano::money( $ard_prices['price'], 'vertical' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				?>
			</div>

			<table class="ard-elc__hooks shop_table cart" role="presentation"><tbody><?php do_action( 'woocommerce_cart_contents' ); ?></tbody></table>

			<?php if ( wc_coupons_enabled() ) : ?>
				<div class="ard-elc__coupon">
					<label class="ard-elc__coupon-field" for="coupon_code">
						<?php echo Ardal_Shop_Elano::icon( 'coupon', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span class="screen-reader-text">کد تخفیف</span>
						<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php echo esc_attr( Ardal_Shop_Elano::opt( 'coupon_ph' ) ); ?>" autocomplete="off" />
					</label>
					<button type="submit" class="ard-elc__cta ard-elc__coupon-btn" name="apply_coupon" value="<?php echo esc_attr( Ardal_Shop_Elano::opt( 'coupon_btn' ) ); ?>">
						<span><?php echo esc_html( Ardal_Shop_Elano::opt( 'coupon_btn' ) ); ?></span><?php echo Ardal_Shop_Elano::icon( 'back', 12 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
					<?php do_action( 'woocommerce_cart_coupon' ); ?>
				</div>

				<?php if ( WC()->cart->get_applied_coupons() ) : ?>
					<div class="ard-elc__chips">
						<?php foreach ( WC()->cart->get_applied_coupons() as $ard_code ) : ?>
							<span class="ard-elc__chip">
								<?php echo esc_html( wc_cart_totals_coupon_label( $ard_code, false ) ); ?>
								<a class="woocommerce-remove-coupon" data-coupon="<?php echo esc_attr( $ard_code ); ?>" href="<?php echo esc_url( add_query_arg( 'remove_coupon', rawurlencode( $ard_code ), wc_get_cart_url() ) ); ?>" aria-label="حذف کد تخفیف"><?php echo Ardal_Shop_Elano::icon( 'close', 12 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
							</span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<div class="ard-elc__update">
				<button type="submit" class="button" name="update_cart" value="به‌روزرسانی سبد">به‌روزرسانی سبد</button>
				<?php do_action( 'woocommerce_cart_actions' ); ?>
				<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
			</div>

			<table class="ard-elc__hooks shop_table cart" role="presentation"><tbody><?php do_action( 'woocommerce_after_cart_contents' ); ?></tbody></table>

			<?php do_action( 'woocommerce_after_cart_table' ); ?>
		</form>
	</main>

	<aside class="ard-elc__side">
		<?php echo Ardal_Shop_Elano::hero( 'cart-large', Ardal_Shop_Elano::opt( 'cart_title' ), Ardal_Shop_Elano::opt( 'cart_sub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

		<div class="ard-elc__card">
			<div class="ard-elc__card-in">
				<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
				<?php woocommerce_cart_totals(); ?>
			</div>
		</div>

		<a class="ard-elc__cta ard-elc__cta--block ard-elc__cta--back" href="<?php echo esc_url( Ardal_Shop_Elano::shop_url() ); ?>">
			<span><?php echo esc_html( Ardal_Shop_Elano::opt( 'back_btn' ) ); ?></span><?php echo Ardal_Shop_Elano::icon( 'back', 12 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>
	</aside>
</div>

<?php
// بقیهٔ چیزهای وصل به این قلاب (مثل پیشنهاد تکمیل خرید)، به‌جز جمع سفارش که بالاست.
$ard_had_totals = remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
do_action( 'woocommerce_cart_collaterals' );

if ( $ard_had_totals ) {
	add_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
}

do_action( 'woocommerce_after_cart' );
