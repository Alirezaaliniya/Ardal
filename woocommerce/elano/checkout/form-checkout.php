<?php
/**
 * تسویه حساب — قالب الانو.
 *
 * ستون اصلی: جزییات صورت حساب، زمان تحویل، شیوهٔ ارسال و درگاه پرداختی.
 * ستون کناری: سربرگ صورتی، کالاها، جمع سفارش و دکمهٔ پرداخت (همان
 * #order_review ووکامرس که با هر تغییر ایجکسی تازه می‌شود).
 *
 * @package Ardal
 *
 * @var WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo '<p class="ard-elc__note">' . esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', 'برای تکمیل خرید باید وارد حساب خود شوید.' ) ) . '</p>';

	return;
}
?>
<form name="checkout" method="post" class="checkout woocommerce-checkout ard-elc__checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" aria-label="تسویه حساب">
	<?php echo Ardal_Shop_Elano::hidden_fields(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

	<div class="ard-elc__wrap">
		<main class="ard-elc__main">
			<?php if ( $checkout->get_checkout_fields() ) : ?>
				<?php echo Ardal_Shop_Elano::section_head( Ardal_Shop_Elano::opt( 'billing_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

				<div class="ard-elc__panel ard-elc__fields">
					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

					<div class="col2-set" id="customer_details">
						<div class="col-1">
							<?php do_action( 'woocommerce_checkout_billing' ); ?>
						</div>
						<div class="col-2">
							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</div>
					</div>

					<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( WC()->cart->needs_shipping() ) : ?>
				<?php if ( 'yes' === Ardal_Shop_Elano::opt( 'delivery' ) ) : ?>
					<?php echo Ardal_Shop_Elano::section_head( Ardal_Shop_Elano::opt( 'delivery_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php Ardal_Shop_Elano::delivery_block(); ?>
				<?php endif; ?>

				<?php echo Ardal_Shop_Elano::section_head( Ardal_Shop_Elano::opt( 'ship_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php Ardal_Shop_Elano::shipping_block(); ?>
			<?php endif; ?>

			<?php echo Ardal_Shop_Elano::section_head( Ardal_Shop_Elano::opt( 'pay_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php do_action( 'woocommerce_checkout_payment' ); ?>
		</main>

		<aside class="ard-elc__side">
			<?php echo Ardal_Shop_Elano::hero( 'cart-large', Ardal_Shop_Elano::opt( 'cart_title' ), Ardal_Shop_Elano::opt( 'cart_sub' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

			<div class="ard-elc__card">
				<div class="ard-elc__card-in">
					<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
					<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

					<div id="order_review" class="woocommerce-checkout-review-order">
						<?php do_action( 'woocommerce_order_review' ); ?>
					</div>

					<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
				</div>
			</div>

			<a class="ard-elc__cta ard-elc__cta--block ard-elc__cta--back" href="<?php echo esc_url( Ardal_Shop_Elano::shop_url() ); ?>">
				<span><?php echo esc_html( Ardal_Shop_Elano::opt( 'back_btn' ) ); ?></span><?php echo Ardal_Shop_Elano::icon( 'back', 12 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		</aside>
	</div>
</form>
<?php
do_action( 'woocommerce_after_checkout_form', $checkout );
