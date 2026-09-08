<?php
/**
 * سبد خرید — کارت اقلام، کد تخفیف و باکس جمع سفارش.
 *
 * جایگزین cart/cart.php ووکامرس. همهٔ قلاب‌ها و نام فیلدهای ووکامرس سر جای
 * خودشان هستند تا افزونه‌های دیگر و به‌روزرسانی سبد کار کنند.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );

$ard_free = Ardal_Shop::free_shipping();
?>
<div class="ard-shop" data-ard-shop>

	<div class="ard-shop__head">
		<h1 class="ard-shop__title">سبد خرید</h1>
		<span class="ard-shop__count">
			<?php echo esc_html( Ardal_Helpers::fa_digits( WC()->cart->get_cart_contents_count() ) ); ?> کالا در سبد
		</span>
	</div>

	<div class="ard-shop__cols">

		<div class="ard-shop__main">
			<form class="woocommerce-cart-form ard-cart__form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
				<?php do_action( 'woocommerce_before_cart_table' ); ?>

				<?php if ( $ard_free ) : ?>
					<div class="ard-cart__free<?php echo $ard_free['reached'] ? ' is-reached' : ''; ?>">
						<span class="ard-cart__free-tick" aria-hidden="true">
							<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"></path></svg>
						</span>
						<?php if ( $ard_free['reached'] ) : ?>
							<span>سفارش شما شامل ارسال رایگان است.</span>
						<?php else : ?>
							<span>
								<?php
								printf(
									'%s تومان دیگر بخر تا ارسالت رایگان شود.',
									esc_html( Ardal_Helpers::price( $ard_free['left'] ) )
								);
								?>
							</span>
							<span class="ard-cart__free-bar" aria-hidden="true">
								<span style="width:<?php echo esc_attr( $ard_free['percent'] ); ?>%"></span>
							</span>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php
				// اسکلت جدول عمداً نگه داشته شده: قلاب‌های ووکامرس (و افزونه‌های
				// وصل به آن‌ها) <tr> چاپ می‌کنند و مرورگر <tr> بیرون از جدول را
				// موقع پارس دور می‌ریزد. ظاهر کارتی را CSS می‌سازد، نه مارک‌آپ.
				?>
				<table class="ard-cart__items shop_table cart woocommerce-cart-form__contents">
					<tbody>
					<?php do_action( 'woocommerce_before_cart_contents' ); ?>

					<?php
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 || ! apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							continue;
						}

						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						$ard_regular       = (float) $_product->get_regular_price() * $cart_item['quantity'];
						$ard_now           = (float) $_product->get_price() * $cart_item['quantity'];
						$ard_off           = $ard_regular > $ard_now ? (int) round( ( $ard_regular - $ard_now ) / $ard_regular * 100 ) : 0;
						?>
						<tr class="ard-cart__item woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

							<td class="ard-cart__media">
								<?php
								$ard_thumb = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

								if ( $product_permalink ) {
									printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $ard_thumb ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								} else {
									echo $ard_thumb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								}
								?>
							</td>

							<td class="ard-cart__info">
								<div class="ard-cart__name">
									<?php
									if ( $product_permalink ) {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
									} else {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
									}

									do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );
									?>
								</div>

								<div class="ard-cart__meta">
									<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<?php if ( $_product->get_sku() ) : ?>
										<span>کد: <?php echo esc_html( Ardal_Helpers::fa_digits( $_product->get_sku() ) ); ?></span>
									<?php endif; ?>
								</div>

								<div class="ard-cart__stock<?php echo $_product->is_in_stock() ? '' : ' is-out'; ?>">
									<span class="ard-cart__dot" aria-hidden="true"></span>
									<?php
									if ( $_product->is_in_stock() ) {
										$ard_qty = $_product->get_stock_quantity();

										echo esc_html(
											$_product->managing_stock() && $ard_qty && $ard_qty <= 5
												? 'تنها ' . Ardal_Helpers::fa_digits( $ard_qty ) . ' عدد باقی مانده'
												: 'موجود در انبار'
										);
									} else {
										echo 'ناموجود';
									}
									?>
								</div>

								<div class="ard-cart__actions">
									<?php
									echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="ard-cart__remove remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">حذف</a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											esc_attr( sprintf( 'حذف %s از سبد خرید', $_product->get_name() ) ),
											esc_attr( $product_id ),
											esc_attr( $_product->get_sku() )
										),
										$cart_item_key
									);
									?>

									<?php if ( is_user_logged_in() ) : ?>
										<button
											type="button"
											class="ard-cart__save"
											data-ard-save-later="<?php echo esc_attr( $product_id ); ?>"
											data-remove="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>"
										>انتقال به علاقه‌مندی‌ها</button>
									<?php endif; ?>
								</div>
							</td>

							<td class="ard-cart__side">
								<div class="ard-cart__prices">
									<?php if ( $ard_off > 0 ) : ?>
										<div class="ard-cart__off-row">
											<span class="ard-cart__off"><?php echo esc_html( Ardal_Helpers::fa_digits( $ard_off ) ); ?>٪</span>
											<del class="ard-cart__old"><?php echo esc_html( Ardal_Helpers::price( $ard_regular ) ); ?></del>
										</div>
									<?php endif; ?>

									<div class="ard-cart__price">
										<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
								</div>

								<?php
								// ورودی تعداد همان ورودی ووکامرس است؛ دکمه‌های کم و زیاد فقط
								// مقدارش را عوض می‌کنند و فرم را می‌فرستند.
								$ard_quantity = woocommerce_quantity_input(
									array(
										'input_name'   => "cart[{$cart_item_key}][qty]",
										'input_value'  => $cart_item['quantity'],
										'max_value'    => $_product->get_max_purchase_quantity(),
										'min_value'    => '0',
										'product_name' => $_product->get_name(),
										'classes'      => array( 'ard-cart__qty-input', 'qty' ),
									),
									$_product,
									false
								);

								if ( $_product->is_sold_individually() ) {
									echo apply_filters( 'woocommerce_cart_item_quantity', $ard_quantity, $cart_item_key, $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								} else {
									?>
									<div class="ard-cart__qty" data-ard-qty>
										<button type="button" class="ard-cart__qty-btn" data-ard-qty-down aria-label="کم کردن تعداد">−</button>
										<?php echo apply_filters( 'woocommerce_cart_item_quantity', $ard_quantity, $cart_item_key, $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										<button type="button" class="ard-cart__qty-btn" data-ard-qty-up aria-label="زیاد کردن تعداد">+</button>
									</div>
									<?php
								}
								?>
							</td>
						</tr>
						<?php
					}
					?>

					<?php do_action( 'woocommerce_cart_contents' ); ?>

					<tr class="ard-cart__actions-row">
						<td colspan="3">
							<?php if ( wc_coupons_enabled() ) : ?>
								<div class="ard-cart__coupon">
									<span class="ard-cart__coupon-title">کد تخفیف دارید؟</span>
									<label for="coupon_code" class="ard-sr">کد تخفیف</label>
									<input type="text" name="coupon_code" class="ard-cart__coupon-input input-text" id="coupon_code" value="" placeholder="کد تخفیف را وارد کنید">
									<button type="submit" class="ard-shop__btn ard-shop__btn--primary" name="apply_coupon" value="اعمال کد">اعمال کد</button>
									<?php do_action( 'woocommerce_cart_coupon' ); ?>

									<?php if ( WC()->cart->get_applied_coupons() ) : ?>
										<div class="ard-cart__coupon-on">
											<?php foreach ( WC()->cart->get_applied_coupons() as $ard_code ) : ?>
												<span class="ard-cart__coupon-chip">
													<?php echo esc_html( wc_cart_totals_coupon_label( $ard_code, false ) ); ?>
													<a href="<?php echo esc_url( add_query_arg( 'remove_coupon', rawurlencode( $ard_code ), wc_get_cart_url() ) ); ?>" aria-label="حذف کد تخفیف">×</a>
												</span>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>

							<div class="ard-cart__update">
								<button type="submit" class="ard-shop__btn ard-shop__btn--ghost" name="update_cart" value="به‌روزرسانی سبد">به‌روزرسانی سبد</button>
								<?php do_action( 'woocommerce_cart_actions' ); ?>
								<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
							</div>
						</td>
					</tr>

					<?php do_action( 'woocommerce_after_cart_contents' ); ?>
					</tbody>
				</table>

				<?php do_action( 'woocommerce_after_cart_table' ); ?>
			</form>
		</div>

		<div class="ard-shop__side">
			<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
			<?php woocommerce_cart_totals(); ?>
		</div>
	</div>

	<?php
	// پیشنهاد تکمیل خرید. بقیهٔ چیزهایی که به این قلاب وصل‌اند هم اینجا می‌آیند.
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
	do_action( 'woocommerce_cart_collaterals' );
	?>
</div>
<?php
do_action( 'woocommerce_after_cart' );
