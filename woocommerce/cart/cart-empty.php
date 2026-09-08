<?php
/**
 * سبد خرید خالی.
 *
 * جایگزین cart/cart-empty.php ووکامرس.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_cart_is_empty' );
?>
<div class="ard-shop">
	<div class="ard-shop__empty">
		<span class="ard-shop__empty-icon" aria-hidden="true">
			<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
				<path d="M4 5h2l2.4 9.5h9.2L20 8H7M9 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2zM17 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"></path>
			</svg>
		</span>
		<h1 class="ard-shop__title">سبد خریدت خالی است</h1>
		<p class="ard-shop__empty-note">هنوز کالایی انتخاب نکرده‌ای. از فروشگاه شروع کن؛ هرچه بپسندی همین‌جا جمع می‌شود.</p>
		<a class="ard-shop__btn ard-shop__btn--accent" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">رفتن به فروشگاه</a>
	</div>
</div>
