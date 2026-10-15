<?php
/**
 * تسویه حساب — نوار مراحل، ستون اطلاعات و باکس سفارش.
 *
 * جایگزین checkout/form-checkout.php ووکامرس.
 *
 * ترتیب کارت‌ها (آدرس، ارسال، پرداخت، توضیحات) با ویژگی order در CSS ساخته
 * می‌شود، نه با جابه‌جایی مارک‌آپ؛ این‌طور فیلدهای خود ووکامرس دست‌نخورده
 * می‌مانند و افزونه‌هایی که به آن‌ها وصل‌اند سر جای‌شان کار می‌کنند.
 *
 * @package Ardal
 *
 * @var WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

// اگر ثبت‌نام لازم است و کاربر وارد نشده، ووکامرس اجازهٔ تسویه نمی‌دهد.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', 'برای تکمیل خرید باید وارد حسابت شوی.' ) );

	return;
}

$ard_support = trim( (string) Ardal_Palette::get( 'account_support_phone' ) );
$ard_hours   = trim( (string) Ardal_Palette::get( 'account_support_hours' ) );
?>
<div class="ard-shop ard-ck" data-ard-shop>

	<div class="ard-ck__steps-bar">
		<ol class="ard-ck__steps">
			<?php foreach ( Ardal_Shop::steps( 'checkout' ) as $ard_step ) : ?>
				<li class="ard-ck__step is-<?php echo esc_attr( $ard_step['state'] ); ?>">
					<span class="ard-ck__step-num"><?php echo esc_html( $ard_step['number'] ); ?></span>
					<span class="ard-ck__step-label"><?php echo esc_html( $ard_step['label'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ol>

		<span class="ard-ck__ssl">
			<span class="ard-ck__ssl-box" aria-hidden="true"></span>
			اتصال امن SSL
		</span>
	</div>

	<form name="checkout" method="post" class="checkout woocommerce-checkout ard-ck__form" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" aria-label="تسویه حساب">

		<div class="ard-shop__cols">

			<div class="ard-shop__main ard-ck__main">
				<?php if ( $checkout->get_checkout_fields() ) : ?>
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
				<?php endif; ?>

				<?php if ( WC()->cart->needs_shipping() ) : ?>
					<section class="ard-ck__card ard-ck__card--ship">
						<h2 class="ard-shop__section-title">
							<span class="ard-shop__section-rule" aria-hidden="true"></span>
							زمان و شیوه ارسال
						</h2>
						<?php Ardal_Shop::render_shipping_block(); ?>
					</section>
				<?php endif; ?>

				<section class="ard-ck__card ard-ck__card--pay">
					<h2 class="ard-shop__section-title">
						<span class="ard-shop__section-rule" aria-hidden="true"></span>
						شیوه پرداخت
					</h2>
					<?php woocommerce_checkout_payment(); ?>
				</section>
			</div>

			<div class="ard-shop__side ard-ck__side">
				<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

				<div id="order_review" class="woocommerce-checkout-review-order">
					<?php woocommerce_order_review(); // همان تابعی که ووکامرس روی woocommerce_checkout_order_review گذاشته. ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

				<ul class="ard-shop__guards ard-shop__guards--card">
					<?php foreach ( Ardal_Shop::guards() as $ard_guard ) : ?>
						<li>
							<span class="ard-shop__guard-tick" aria-hidden="true">
								<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"></path></svg>
							</span>
							<?php echo esc_html( $ard_guard ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</form>

	<?php if ( '' !== $ard_support ) : ?>
		<aside class="ard-ck__support">
			<span class="ard-ck__support-stripes" aria-hidden="true"></span>
			<span class="ard-ck__support-title">پشتیبانی سفارش‌ها</span>
			<span class="ard-ck__support-note">
				<?php echo esc_html( $ard_hours ); ?>
				<?php echo '' !== $ard_hours ? ' · ' : ''; ?>
				<?php echo esc_html( Ardal_Helpers::fa_digits( $ard_support ) ); ?>
			</span>
			<a class="ard-shop__btn ard-shop__btn--accent" href="tel:<?php echo esc_attr( Ardal_Helpers::en_digits( $ard_support ) ); ?>">تماس با پشتیبانی</a>
		</aside>
	<?php endif; ?>
</div>
<?php
do_action( 'woocommerce_after_checkout_form', $checkout );
