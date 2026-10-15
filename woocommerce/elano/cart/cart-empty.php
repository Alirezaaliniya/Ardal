<?php
/**
 * سبد خالی — قالب الانو.
 *
 * کلاس wc-empty-cart-message روی ریشه است تا وقتی آخرین کالا با ایجکس حذف
 * می‌شود، اسکریپت سبد ووکامرس همین بخش را جایگزین کند.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;

// پیام پیش‌فرض ووکامرس جایش را به همین کارت می‌دهد؛ بقیهٔ قلاب‌ها اجرا می‌شوند.
$ard_had_msg = remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );
do_action( 'woocommerce_cart_is_empty' );

if ( $ard_had_msg ) {
	add_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );
}
?>
<div class="wc-empty-cart-message ard-elc__wrap ard-elc__wrap--empty">
	<div class="ard-elc__empty cart-empty">
		<?php echo Ardal_Shop_Elano::hero( 'cart-large', Ardal_Shop_Elano::opt( 'cart_title' ), Ardal_Shop_Elano::opt( 'cart_sub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="ard-elc__card ard-elc__card--empty"><div class="ard-elc__card-in">
			<strong class="ard-elc__empty-title"><?php echo esc_html( Ardal_Shop_Elano::opt( 'empty_title' ) ); ?></strong>
			<?php if ( '' !== trim( (string) Ardal_Shop_Elano::opt( 'empty_text' ) ) ) : ?>
				<p class="ard-elc__note"><?php echo esc_html( Ardal_Shop_Elano::opt( 'empty_text' ) ); ?></p>
			<?php endif; ?>
			<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
				<a class="ard-elc__btn return-to-shop" href="<?php echo esc_url( Ardal_Shop_Elano::shop_url() ); ?>"><?php echo esc_html( Ardal_Shop_Elano::opt( 'empty_btn' ) ); ?></a>
			<?php endif; ?>
		</div></div>
	</div>
</div>
