<?php
/**
 * ردیف روش ارسال داخل باکس جمع سفارش.
 *
 * جایگزین cart/cart-shipping.php ووکامرس. ساختار `<tr>` عمداً حفظ شده تا
 * قلاب `woocommerce_after_shipping_rate` و افزونه‌های وصل به آن در همان
 * جایگاهی که انتظار دارند رندر شوند؛ ظاهرش را CSS عوض می‌کند.
 *
 * @package Ardal
 *
 * @var array  $available_methods
 * @var string $chosen_method
 * @var int    $index
 * @var string $package_name
 * @var bool   $show_package_details
 * @var string $package_details
 * @var bool   $show_shipping_calculator
 * @var bool   $has_calculated_shipping
 * @var string $formatted_destination
 */

defined( 'ABSPATH' ) || exit;
?>
<tr class="woocommerce-shipping-totals shipping ard-shop__row--ship">
	<th><?php echo wp_kses_post( $package_name ); ?></th>

	<td data-title="<?php echo esc_attr( $package_name ); ?>">
		<?php if ( $available_methods ) : ?>
			<ul class="ard-shop__ship-list woocommerce-shipping-methods" id="shipping_method_<?php echo esc_attr( $index ); ?>">
				<?php foreach ( $available_methods as $method ) : ?>
					<li>
						<?php
						if ( 1 < count( $available_methods ) ) {
							printf(
								'<input type="radio" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" %4$s>',
								absint( $index ),
								esc_attr( sanitize_title( $method->id ) ),
								esc_attr( $method->id ),
								checked( $method->id, $chosen_method, false )
							);
						} else {
							printf(
								'<input type="hidden" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method">',
								absint( $index ),
								esc_attr( sanitize_title( $method->id ) ),
								esc_attr( $method->id )
							);
						}

						printf(
							'<label for="shipping_method_%1$d_%2$s">%3$s</label>',
							absint( $index ),
							esc_attr( sanitize_title( $method->id ) ),
							wc_cart_totals_shipping_method_label( $method ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						);

						do_action( 'woocommerce_after_shipping_rate', $method, $index );
						?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php elseif ( ! $has_calculated_shipping || ! $formatted_destination ) : ?>
			<span class="ard-shop__row-note">پس از وارد کردن آدرس، هزینه ارسال محاسبه می‌شود.</span>
		<?php else : ?>
			<span class="ard-shop__row-note">
				<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_no_shipping_available_html', 'روشی برای ارسال به این آدرس پیدا نشد.' ) ); ?>
			</span>
		<?php endif; ?>

		<?php if ( $show_package_details ) : ?>
			<span class="ard-shop__row-note"><?php echo esc_html( $package_details ); ?></span>
		<?php endif; ?>

		<?php if ( $show_shipping_calculator ) : ?>
			<span class="ard-shop__calc"><?php woocommerce_shipping_calculator(); ?></span>
		<?php endif; ?>
	</td>
</tr>
