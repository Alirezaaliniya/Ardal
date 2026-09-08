<?php
/**
 * جزئیات یک سفارش.
 *
 * جایگزین myaccount/view-order.php ووکامرس.
 *
 * @package Ardal
 *
 * @var int $order_id شناسهٔ سفارش، از خود ووکامرس.
 */

defined( 'ABSPATH' ) || exit;

$ard_order = wc_get_order( $order_id );

if ( ! $ard_order ) {
	return;
}

$ard_tone     = Ardal_Account::status_tone( $ard_order->get_status() );
$ard_created  = $ard_order->get_date_created();
$ard_timeline = Ardal_Account::order_timeline( $ard_order );
$ard_code     = Ardal_Account::tracking_code( $ard_order );
$ard_invoice  = apply_filters( 'ardal_account_invoice_url', '', $ard_order );
$ard_address  = $ard_order->get_formatted_shipping_address();
$ard_address  = $ard_address ? $ard_address : $ard_order->get_formatted_billing_address();

$ard_meta = array();

if ( $ard_created ) {
	$ard_meta[] = 'ثبت شده در ' . Ardal_Jalali::format( $ard_created->getTimestamp(), 'with_time' );
}

if ( $ard_order->get_payment_method_title() ) {
	$ard_meta[] = $ard_order->get_payment_method_title();
}
?>
<div class="ard-ac__stack">

	<div class="ard-ac__card ard-ac__detail-head">
		<a class="ard-ac__back" href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" aria-label="بازگشت به سفارش‌ها">
			<?php echo Ardal_Account::icon( 'back', 17 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>

		<div class="ard-ac__detail-text">
			<div class="ard-ac__detail-title">
				<h2 class="ard-ac__ptitle">سفارش <?php echo esc_html( Ardal_Helpers::fa_digits( $ard_order->get_order_number() ) ); ?></h2>
				<span class="ard-ac__badge ard-ac__badge--<?php echo esc_attr( $ard_tone ); ?>">
					<?php echo esc_html( wc_get_order_status_name( $ard_order->get_status() ) ); ?>
				</span>
			</div>
			<?php if ( ! empty( $ard_meta ) ) : ?>
				<p class="ard-ac__csub"><?php echo esc_html( implode( ' · ', $ard_meta ) ); ?></p>
			<?php endif; ?>
		</div>

		<div class="ard-ac__detail-actions">
			<?php if ( '' !== $ard_invoice ) : ?>
				<a class="ard-ac__btn ard-ac__btn--ghost" href="<?php echo esc_url( $ard_invoice ); ?>">دانلود فاکتور</a>
			<?php else : ?>
				<button type="button" class="ard-ac__btn ard-ac__btn--ghost" data-ard-ac-print>دانلود فاکتور</button>
			<?php endif; ?>

			<a class="ard-ac__btn ard-ac__btn--primary" href="<?php echo esc_url( Ardal_Account::reorder_url( $ard_order ) ); ?>">خرید دوباره</a>
		</div>
	</div>

	<div class="ard-ac__cols ard-ac__cols--detail">

		<div class="ard-ac__stack">

			<div class="ard-ac__card">
				<div class="ard-ac__chead">
					<h3 class="ard-ac__ctitle">رهگیری مرسوله</h3>
					<?php if ( '' !== $ard_code ) : ?>
						<span class="ard-ac__tracking">
							<span class="ard-ac__tracking-label">کد رهگیری</span>
							<span class="ard-ac__tracking-code"><?php echo esc_html( $ard_code ); ?></span>
						</span>
					<?php endif; ?>
				</div>

				<ol class="ard-ac__timeline">
					<?php foreach ( $ard_timeline as $ard_i => $ard_event ) : ?>
						<li class="ard-ac__event<?php echo 0 === $ard_i ? ' is-latest' : ''; ?>">
							<span class="ard-ac__event-rail" aria-hidden="true">
								<span class="ard-ac__event-dot"></span>
								<span class="ard-ac__event-line"></span>
							</span>
							<span class="ard-ac__event-body">
								<span class="ard-ac__event-title"><?php echo esc_html( $ard_event['title'] ); ?></span>
								<span class="ard-ac__event-desc"><?php echo esc_html( $ard_event['desc'] ); ?></span>
							</span>
							<span class="ard-ac__event-time"><?php echo esc_html( $ard_event['time'] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ol>
			</div>

			<div class="ard-ac__card">
				<h3 class="ard-ac__ctitle ard-ac__ctitle--mb">کالاهای این سفارش</h3>

				<div class="ard-ac__items">
					<?php foreach ( $ard_order->get_items() as $ard_item ) : ?>
						<?php
						$ard_product = $ard_item->get_product();
						$ard_regular = $ard_product ? (float) $ard_product->get_regular_price() * $ard_item->get_quantity() : 0;
						$ard_paid    = (float) $ard_item->get_total();
						?>
						<div class="ard-ac__item">
							<span class="ard-ac__thumb ard-ac__thumb--md"><?php echo Ardal_Account::thumb( $ard_product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>

							<span class="ard-ac__item-text">
								<span class="ard-ac__item-name">
									<?php if ( $ard_product && $ard_product->is_visible() ) : ?>
										<a href="<?php echo esc_url( $ard_product->get_permalink() ); ?>"><?php echo esc_html( $ard_item->get_name() ); ?></a>
									<?php else : ?>
										<?php echo esc_html( $ard_item->get_name() ); ?>
									<?php endif; ?>
								</span>

								<span class="ard-ac__item-meta">
									<?php foreach ( $ard_item->get_formatted_meta_data() as $ard_im ) : ?>
										<span><?php echo esc_html( wp_strip_all_tags( $ard_im->display_key ) ); ?>: <?php echo esc_html( wp_strip_all_tags( $ard_im->display_value ) ); ?></span>
									<?php endforeach; ?>
									<span>تعداد: <?php echo esc_html( Ardal_Helpers::fa_digits( $ard_item->get_quantity() ) ); ?></span>
								</span>
							</span>

							<span class="ard-ac__item-price">
								<span class="ard-ac__item-now"><?php echo esc_html( Ardal_Helpers::price( $ard_paid ) ); ?></span>
								<?php if ( $ard_regular > $ard_paid ) : ?>
									<del class="ard-ac__item-old"><?php echo esc_html( Ardal_Helpers::price( $ard_regular ) ); ?></del>
								<?php endif; ?>
							</span>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<?php do_action( 'woocommerce_order_details_after_order_table', $ard_order ); ?>
		</div>

		<div class="ard-ac__stack">

			<div class="ard-ac__card">
				<h3 class="ard-ac__ctitle ard-ac__ctitle--mb">خلاصه پرداخت</h3>

				<div class="ard-ac__summary">
					<div class="ard-ac__summary-row">
						<span>مبلغ کالاها</span>
						<span class="ard-ac__summary-val"><?php echo esc_html( Ardal_Helpers::price( $ard_order->get_subtotal() ) ); ?></span>
					</div>

					<?php if ( $ard_order->get_total_discount() > 0 ) : ?>
						<div class="ard-ac__summary-row">
							<span>تخفیف<?php echo $ard_order->get_coupon_codes() ? ' (' . esc_html( implode( '، ', $ard_order->get_coupon_codes() ) ) . ')' : ''; ?></span>
							<span class="ard-ac__summary-val ard-ac__summary-val--off">−<?php echo esc_html( Ardal_Helpers::price( $ard_order->get_total_discount() ) ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( (float) $ard_order->get_shipping_total() > 0 ) : ?>
						<div class="ard-ac__summary-row">
							<span>هزینه ارسال</span>
							<span class="ard-ac__summary-val"><?php echo esc_html( Ardal_Helpers::price( $ard_order->get_shipping_total() ) ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( (float) $ard_order->get_total_tax() > 0 ) : ?>
						<div class="ard-ac__summary-row">
							<span>مالیات</span>
							<span class="ard-ac__summary-val"><?php echo esc_html( Ardal_Helpers::price( $ard_order->get_total_tax() ) ); ?></span>
						</div>
					<?php endif; ?>
				</div>

				<div class="ard-ac__summary-total">
					<span><?php echo $ard_order->is_paid() ? 'مبلغ پرداخت شده' : 'مبلغ قابل پرداخت'; ?></span>
					<strong><?php echo esc_html( Ardal_Helpers::price( $ard_order->get_total() ) ); ?></strong>
				</div>
			</div>

			<?php if ( $ard_address ) : ?>
				<div class="ard-ac__card">
					<h3 class="ard-ac__ctitle ard-ac__ctitle--mb">آدرس تحویل</h3>

					<div class="ard-ac__addr-name">
						<?php echo esc_html( trim( $ard_order->get_formatted_shipping_full_name() ) ? $ard_order->get_formatted_shipping_full_name() : $ard_order->get_formatted_billing_full_name() ); ?>
					</div>
					<address class="ard-ac__addr-line"><?php echo wp_kses_post( $ard_address ); ?></address>

					<?php
					$ard_bits = array_filter(
						array(
							$ard_order->get_shipping_postcode() ? 'کدپستی ' . Ardal_Helpers::fa_digits( $ard_order->get_shipping_postcode() ) : '',
							$ard_order->get_billing_phone() ? Ardal_Helpers::fa_digits( $ard_order->get_billing_phone() ) : '',
						)
					);
					?>
					<?php if ( ! empty( $ard_bits ) ) : ?>
						<p class="ard-ac__addr-meta"><?php echo esc_html( implode( ' · ', $ard_bits ) ); ?></p>
					<?php endif; ?>

					<?php if ( $ard_order->get_shipping_method() ) : ?>
						<div class="ard-ac__ship">
							<span class="ard-ac__ship-icon"><?php echo Ardal_Account::icon( 'truck', 17 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span class="ard-ac__ship-text">
								<span class="ard-ac__ship-name"><?php echo esc_html( $ard_order->get_shipping_method() ); ?></span>
								<?php
								$ard_done = $ard_order->get_date_completed();

								if ( $ard_done ) :
									?>
									<span class="ard-ac__ship-note">تحویل شده در <?php echo esc_html( Ardal_Jalali::format( $ard_done->getTimestamp(), 'no_year' ) ); ?></span>
								<?php endif; ?>
							</span>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $ard_order->needs_payment() ) : ?>
				<a class="ard-ac__btn ard-ac__btn--amber ard-ac__btn--block" href="<?php echo esc_url( $ard_order->get_checkout_payment_url() ); ?>">پرداخت سفارش</a>
			<?php endif; ?>
		</div>
	</div>
</div>
