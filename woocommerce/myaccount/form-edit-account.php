<?php
/**
 * اطلاعات حساب کاربری.
 *
 * جایگزین myaccount/form-edit-account.php ووکامرس.
 *
 * @package Ardal
 *
 * @var WP_User $user کاربر جاری، از خود ووکامرس.
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' );

$ard_user   = $user;
$ard_id     = $ard_user->ID;
$ard_since  = strtotime( $ard_user->user_registered );
$ard_orders = (int) wc_get_customer_order_count( $ard_id );
$ard_addr   = Ardal_Account::filled_addresses();

$ard_facts = array(
	'عضو از ' . Ardal_Jalali::format( $ard_since ),
	Ardal_Helpers::fa_digits( $ard_orders ) . ' سفارش',
	Ardal_Helpers::fa_digits( $ard_addr ) . ' آدرس ثبت شده',
);

$ard_extras = Ardal_Account::profile_fields();
?>
<div class="ard-ac__stack">

	<div class="ard-ac__card ard-ac__profile">
		<span class="ard-ac__profile-avatar"><?php echo esc_html( Ardal_Account::initials() ); ?></span>
		<div class="ard-ac__profile-text">
			<h2 class="ard-ac__ptitle"><?php echo esc_html( Ardal_Account::name() ); ?></h2>
			<p class="ard-ac__csub"><?php echo esc_html( implode( ' · ', $ard_facts ) ); ?></p>
		</div>
		<a class="ard-ac__btn ard-ac__btn--ghost" href="https://gravatar.com/" target="_blank" rel="noopener">تغییر تصویر</a>
	</div>

	<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >
		<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

		<div class="ard-ac__card">
			<h3 class="ard-ac__ctitle ard-ac__ctitle--mb">اطلاعات حساب</h3>

			<div class="ard-ac__fields">
				<p class="ard-ac__field">
					<label for="account_first_name">نام</label>
					<input type="text" class="ard-ac__input" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $ard_user->first_name ); ?>">
				</p>

				<p class="ard-ac__field">
					<label for="account_last_name">نام خانوادگی</label>
					<input type="text" class="ard-ac__input" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $ard_user->last_name ); ?>">
				</p>

				<p class="ard-ac__field">
					<label for="ardal_billing_phone">شماره موبایل</label>
					<input type="tel" class="ard-ac__input" name="ardal_billing_phone" id="ardal_billing_phone" autocomplete="tel" dir="ltr" value="<?php echo esc_attr( get_user_meta( $ard_id, 'billing_phone', true ) ); ?>">
				</p>

				<p class="ard-ac__field">
					<label for="account_email">ایمیل</label>
					<input type="email" class="ard-ac__input" name="account_email" id="account_email" autocomplete="email" dir="ltr" value="<?php echo esc_attr( $ard_user->user_email ); ?>">
				</p>

				<?php foreach ( $ard_extras as $ard_key => $ard_field ) : ?>
					<p class="ard-ac__field<?php echo ! empty( $ard_field['wide'] ) ? ' ard-ac__field--wide' : ''; ?>">
						<label for="<?php echo esc_attr( $ard_key ); ?>"><?php echo esc_html( $ard_field['label'] ); ?></label>

						<?php if ( 'select' === $ard_field['type'] ) : ?>
							<select class="ard-ac__input" name="<?php echo esc_attr( $ard_key ); ?>" id="<?php echo esc_attr( $ard_key ); ?>">
								<?php foreach ( $ard_field['options'] as $ard_val => $ard_label ) : ?>
									<option value="<?php echo esc_attr( $ard_val ); ?>" <?php selected( $ard_val, get_user_meta( $ard_id, $ard_key, true ) ); ?>>
										<?php echo esc_html( $ard_label ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						<?php else : ?>
							<input
								type="text"
								class="ard-ac__input"
								name="<?php echo esc_attr( $ard_key ); ?>"
								id="<?php echo esc_attr( $ard_key ); ?>"
								value="<?php echo esc_attr( get_user_meta( $ard_id, $ard_key, true ) ); ?>"
								<?php echo ! empty( $ard_field['placeholder'] ) ? ' placeholder="' . esc_attr( $ard_field['placeholder'] ) . '"' : ''; ?>
							>
						<?php endif; ?>
					</p>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="ard-ac__card">
			<h3 class="ard-ac__ctitle">تغییر رمز عبور</h3>
			<p class="ard-ac__csub ard-ac__csub--mb">اگر نمی‌خواهی رمز عبورت عوض شود، این سه فیلد را خالی بگذار.</p>

			<div class="ard-ac__fields">
				<p class="ard-ac__field">
					<label for="password_current">رمز عبور فعلی</label>
					<input type="password" class="ard-ac__input" name="password_current" id="password_current" autocomplete="off">
				</p>

				<p class="ard-ac__field">
					<label for="password_1">رمز عبور جدید</label>
					<input type="password" class="ard-ac__input" name="password_1" id="password_1" autocomplete="off">
				</p>

				<p class="ard-ac__field">
					<label for="password_2">تکرار رمز عبور جدید</label>
					<input type="password" class="ard-ac__input" name="password_2" id="password_2" autocomplete="off">
				</p>
			</div>

			<?php do_action( 'woocommerce_edit_account_form' ); ?>

			<div class="ard-ac__form-actions">
				<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
				<?php // ووکامرس «نام نمایشی» را فیلد اجباری می‌داند؛ دیزاین آن را نشان نمی‌دهد، پس مقدار فعلی دست‌نخورده ارسال می‌شود. ?>
				<input type="hidden" name="account_display_name" value="<?php echo esc_attr( $ard_user->display_name ); ?>">
				<button type="submit" class="ard-ac__btn ard-ac__btn--primary" name="save_account_details" value="ذخیره تغییرات">ذخیره تغییرات</button>
				<a class="ard-ac__btn ard-ac__btn--ghost" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">انصراف</a>
				<input type="hidden" name="action" value="save_account_details">
			</div>
		</div>

		<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
	</form>
</div>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
