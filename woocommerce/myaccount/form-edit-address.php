<?php
/**
 * فرم ویرایش آدرس.
 *
 * جایگزین myaccount/form-edit-address.php ووکامرس؛ فیلدها همان فیلدهای
 * ووکامرس‌اند و فقط قالب‌بندی پنل روی‌شان می‌نشیند.
 *
 * @package Ardal
 *
 * @var string $load_address نوع آدرس (billing یا shipping).
 * @var array  $address      فیلدهای آدرس.
 * @var string $page_title   عنوان صفحه.
 */

defined( 'ABSPATH' ) || exit;

$ard_back = wc_get_account_endpoint_url( 'edit-address' );
?>
<div class="ard-ac__stack">

	<form method="post" class="ard-ac__form">
		<div class="ard-ac__card ard-ac__detail-head">
			<a class="ard-ac__back" href="<?php echo esc_url( $ard_back ); ?>" aria-label="بازگشت به آدرس‌ها">
				<?php echo Ardal_Account::icon( 'back', 17 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
			<div class="ard-ac__detail-text">
				<h2 class="ard-ac__ptitle">
					<?php echo esc_html( 'shipping' === $load_address ? 'آدرس تحویل سفارش' : 'آدرس صورتحساب' ); ?>
				</h2>
				<p class="ard-ac__csub">این آدرس هنگام تسویه حساب به‌صورت پیش‌فرض پر می‌شود.</p>
			</div>
		</div>

		<div class="ard-ac__card">
			<?php do_action( 'woocommerce_before_edit_address_form_' . $load_address ); ?>

			<div class="ard-ac__fields ard-ac__fields--woo">
				<?php
				foreach ( $address as $ard_key => $ard_field ) {
					woocommerce_form_field( $ard_key, $ard_field, wc_get_post_data_by_key( $ard_key, $ard_field['value'] ) );
				}
				?>
			</div>

			<?php do_action( 'woocommerce_after_edit_address_form_' . $load_address ); ?>

			<div class="ard-ac__form-actions">
				<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
				<button type="submit" class="ard-ac__btn ard-ac__btn--primary" name="save_address" value="ذخیره آدرس">ذخیره آدرس</button>
				<a class="ard-ac__btn ard-ac__btn--ghost" href="<?php echo esc_url( $ard_back ); ?>">انصراف</a>
				<input type="hidden" name="action" value="edit_address">
			</div>
		</div>
	</form>
</div>
