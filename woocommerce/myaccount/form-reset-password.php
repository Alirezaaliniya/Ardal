<?php
/**
 * فرم ساخت رمز عبور تازه.
 *
 * جایگزین myaccount/form-reset-password.php ووکامرس.
 *
 * @package Ardal
 *
 * @var array $args کلید و نام کاربری بازیابی، از خود ووکامرس.
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_reset_password_form' );

// ووکامرس این دو را هم به‌صورت متغیر مستقل و هم داخل $args می‌فرستد.
$ard_key   = isset( $args['key'] ) ? $args['key'] : ( isset( $key ) ? $key : '' );
$ard_login = isset( $args['login'] ) ? $args['login'] : ( isset( $login ) ? $login : '' );

Ardal_Account::auth_open( 'رمز عبور تازه', 'رمز تازه‌ات را دو بار بنویس تا مطمئن شویم درست وارد شده.' );
?>

<div class="ard-ac__auth-cards">
	<div class="ard-ac__card">
		<form method="post" class="woocommerce-ResetPassword lost_reset_password ard-ac__auth-form">
			<p class="ard-ac__field">
				<label for="password_1">رمز عبور جدید <span class="ard-ac__req">*</span></label>
				<input
					type="password"
					class="ard-ac__input woocommerce-Input woocommerce-Input--text input-text"
					name="password_1"
					id="password_1"
					autocomplete="new-password"
					required
					aria-required="true"
				>
			</p>

			<p class="ard-ac__field">
				<label for="password_2">تکرار رمز عبور جدید <span class="ard-ac__req">*</span></label>
				<input
					type="password"
					class="ard-ac__input woocommerce-Input woocommerce-Input--text input-text"
					name="password_2"
					id="password_2"
					autocomplete="new-password"
					required
					aria-required="true"
				>
			</p>

			<input type="hidden" name="reset_key" value="<?php echo esc_attr( $ard_key ); ?>">
			<input type="hidden" name="reset_login" value="<?php echo esc_attr( $ard_login ); ?>">
			<input type="hidden" name="wc_reset_password" value="true">

			<?php do_action( 'woocommerce_resetpassword_form' ); ?>
			<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>

			<button type="submit" class="ard-ac__btn ard-ac__btn--primary ard-ac__btn--block woocommerce-Button button" value="ذخیره">ذخیره رمز تازه</button>
		</form>
	</div>
</div>

<?php
Ardal_Account::auth_close();

do_action( 'woocommerce_after_reset_password_form' );
