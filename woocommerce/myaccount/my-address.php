<?php
/**
 * آدرس‌های کاربر.
 *
 * جایگزین myaccount/my-address.php ووکامرس.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;

$ard_customer_id = get_current_user_id();

$ard_types = wc_get_account_address_types(); // billing => عنوان، shipping => عنوان.

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$ard_labels = array(
		'billing'  => 'آدرس صورتحساب',
		'shipping' => 'آدرس تحویل سفارش',
	);
} else {
	$ard_types  = array( 'billing' => $ard_types['billing'] );
	$ard_labels = array( 'billing' => 'آدرس صورتحساب' );
}

$ard_icons = array(
	'billing'  => 'wallet',
	'shipping' => 'addr',
);

$ard_filled = 0;

foreach ( array_keys( $ard_types ) as $ard_type ) {
	if ( wc_get_account_formatted_address( $ard_type ) ) {
		$ard_filled++;
	}
}
?>
<div class="ard-ac__stack">

	<div class="ard-ac__card ard-ac__chead ard-ac__chead--card">
		<div>
			<h2 class="ard-ac__ptitle">آدرس‌های من</h2>
			<p class="ard-ac__csub">
				<?php echo esc_html( Ardal_Helpers::fa_digits( $ard_filled ) ); ?> از
				<?php echo esc_html( Ardal_Helpers::fa_digits( count( $ard_types ) ) ); ?> آدرس تکمیل شده ·
				این آدرس‌ها هنگام تسویه حساب پیشنهاد می‌شوند
			</p>
		</div>
	</div>

	<div class="ard-ac__grid ard-ac__grid--2">
		<?php foreach ( $ard_types as $ard_type => $ard_title ) : ?>
			<?php
			$ard_address = wc_get_account_formatted_address( $ard_type );
			$ard_edit    = wc_get_endpoint_url( 'edit-address', $ard_type );
			$ard_name    = trim(
				get_user_meta( $ard_customer_id, $ard_type . '_first_name', true ) . ' ' .
				get_user_meta( $ard_customer_id, $ard_type . '_last_name', true )
			);
			$ard_zip     = get_user_meta( $ard_customer_id, $ard_type . '_postcode', true );
			?>
			<div class="ard-ac__card ard-ac__addr<?php echo $ard_address ? ' is-set' : ''; ?>">
				<div class="ard-ac__addr-head">
					<span class="ard-ac__addr-icon">
						<?php echo Ardal_Account::icon( isset( $ard_icons[ $ard_type ] ) ? $ard_icons[ $ard_type ] : 'addr', 17 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<h3 class="ard-ac__addr-title"><?php echo esc_html( isset( $ard_labels[ $ard_type ] ) ? $ard_labels[ $ard_type ] : $ard_title ); ?></h3>
					<?php if ( 'shipping' === $ard_type && $ard_address ) : ?>
						<span class="ard-ac__badge ard-ac__badge--green">پیش‌فرض ارسال</span>
					<?php endif; ?>
				</div>

				<?php if ( $ard_address ) : ?>
					<address class="ard-ac__addr-line"><?php echo wp_kses_post( $ard_address ); ?></address>

					<div class="ard-ac__addr-facts">
						<?php if ( '' !== $ard_name ) : ?>
							<span>گیرنده: <strong><?php echo esc_html( $ard_name ); ?></strong></span>
						<?php endif; ?>
						<?php if ( '' !== $ard_zip ) : ?>
							<span>کدپستی: <strong><?php echo esc_html( Ardal_Helpers::fa_digits( $ard_zip ) ); ?></strong></span>
						<?php endif; ?>
					</div>
				<?php else : ?>
					<p class="ard-ac__addr-empty">هنوز این آدرس را وارد نکرده‌ای. با تکمیلش، تسویه حساب سریع‌تر انجام می‌شود.</p>
				<?php endif; ?>

				<div class="ard-ac__addr-actions">
					<a class="ard-ac__btn ard-ac__btn--<?php echo $ard_address ? 'ghost' : 'primary'; ?> ard-ac__btn--sm" href="<?php echo esc_url( $ard_edit ); ?>">
						<?php echo $ard_address ? 'ویرایش آدرس' : 'افزودن آدرس'; ?>
					</a>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
