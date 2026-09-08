<?php
/**
 * روش‌های پرداخت ذخیره‌شده.
 *
 * جایگزین myaccount/payment-methods.php ووکامرس.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;

$ard_methods = wc_get_customer_saved_methods_list( get_current_user_id() );
$ard_has     = (bool) $ard_methods;
$ard_labels  = wc_get_account_payment_methods_types();
$ard_count   = 0;

foreach ( $ard_methods as $ard_group ) {
	$ard_count += count( $ard_group );
}

do_action( 'woocommerce_before_account_payment_methods', $ard_has );
?>
<div class="ard-ac__stack">

	<div class="ard-ac__card ard-ac__chead ard-ac__chead--card">
		<div>
			<h2 class="ard-ac__ptitle">روش‌های پرداخت</h2>
			<p class="ard-ac__csub">
				<?php echo esc_html( Ardal_Helpers::fa_digits( $ard_count ) ); ?> روش پرداخت ذخیره شده ·
				برای خریدهای بعدی سریع‌تر
			</p>
		</div>

		<?php if ( WC()->payment_gateways->get_available_payment_gateways() ) : ?>
			<a class="ard-ac__btn ard-ac__btn--primary" href="<?php echo esc_url( wc_get_endpoint_url( 'add-payment-method' ) ); ?>">
				<?php echo Ardal_Account::icon( 'plus', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				افزودن روش پرداخت
			</a>
		<?php endif; ?>
	</div>

	<?php if ( ! $ard_has ) : ?>
		<div class="ard-ac__card ard-ac__empty">
			<span class="ard-ac__empty-icon"><?php echo Ardal_Account::icon( 'wallet', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<h3 class="ard-ac__ctitle">روش پرداختی ذخیره نشده</h3>
			<p class="ard-ac__csub">اگر درگاه پرداختت ذخیرهٔ کارت را پشتیبانی کند، اینجا می‌توانی آن را نگه داری.</p>
		</div>
	<?php else : ?>
		<div class="ard-ac__grid ard-ac__grid--2">
			<?php foreach ( $ard_methods as $ard_type => $ard_group ) : ?>
				<?php foreach ( $ard_group as $ard_method ) : ?>
					<?php
					$ard_default = ! empty( $ard_method['is_default'] );
					$ard_brand   = isset( $ard_method['method']['brand'] ) ? wc_get_credit_card_type_label( $ard_method['method']['brand'] ) : '';
					$ard_last4   = isset( $ard_method['method']['last4'] ) ? $ard_method['method']['last4'] : '';

					if ( '' === $ard_brand ) {
						$ard_brand = isset( $ard_labels[ $ard_type ] ) ? $ard_labels[ $ard_type ] : $ard_type;
					}
					?>
					<div class="ard-ac__card ard-ac__pay<?php echo $ard_default ? ' is-default' : ''; ?>">
						<div class="ard-ac__addr-head">
							<span class="ard-ac__addr-icon"><?php echo Ardal_Account::icon( 'wallet', 17 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<h3 class="ard-ac__addr-title"><?php echo esc_html( $ard_brand ); ?></h3>
							<?php if ( $ard_default ) : ?>
								<span class="ard-ac__badge ard-ac__badge--green">پیش‌فرض</span>
							<?php endif; ?>
						</div>

						<?php if ( '' !== $ard_last4 ) : ?>
							<div class="ard-ac__pay-number" dir="ltr">•••• •••• •••• <?php echo esc_html( Ardal_Helpers::fa_digits( $ard_last4 ) ); ?></div>
						<?php endif; ?>

						<?php if ( ! empty( $ard_method['expires'] ) && '-' !== $ard_method['expires'] ) : ?>
							<div class="ard-ac__addr-meta">انقضا: <?php echo esc_html( Ardal_Helpers::fa_digits( $ard_method['expires'] ) ); ?></div>
						<?php endif; ?>

						<?php if ( ! empty( $ard_method['actions'] ) ) : ?>
							<div class="ard-ac__addr-actions">
								<?php foreach ( $ard_method['actions'] as $ard_key => $ard_action ) : ?>
									<a
										class="ard-ac__btn ard-ac__btn--sm ard-ac__btn--<?php echo 'delete' === $ard_key ? 'danger' : 'ghost'; ?> <?php echo esc_attr( sanitize_html_class( $ard_key ) ); ?>"
										href="<?php echo esc_url( $ard_action['url'] ); ?>"
									><?php echo esc_html( $ard_action['name'] ); ?></a>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
<?php
do_action( 'woocommerce_after_account_payment_methods', $ard_has );
