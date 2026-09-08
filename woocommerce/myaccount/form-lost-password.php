<?php
/**
 * فرم بازیابی رمز عبور.
 *
 * جایگزین myaccount/form-lost-password.php ووکامرس.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_lost_password_form' );

Ardal_Account::auth_open(
	'بازیابی رمز عبور',
	'نام کاربری یا ایمیلت را بنویس تا لینک ساخت رمز تازه برایت فرستاده شود.'
);
?>

<div class="ard-ac__auth-cards">
	<div class="ard-ac__card">
		<form method="post" class="woocommerce-ResetPassword lost_reset_password ard-ac__auth-form">
			<p class="ard-ac__field">
				<label for="user_login">نام کاربری یا ایمیل <span class="ard-ac__req">*</span></label>
				<input
					type="text"
					class="ard-ac__input woocommerce-Input woocommerce-Input--text input-text"
					name="user_login"
					id="user_login"
					autocomplete="username"
					required
					aria-required="true"
				>
			</p>

			<?php do_action( 'woocommerce_lostpassword_form' ); ?>

			<input type="hidden" name="wc_reset_password" value="true">
			<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

			<button type="submit" class="ard-ac__btn ard-ac__btn--primary ard-ac__btn--block woocommerce-Button button" value="بازیابی رمز عبور">فرستادن لینک بازیابی</button>

			<a class="ard-ac__link ard-ac__auth-alt" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">بازگشت به صفحه ورود</a>
		</form>
	</div>
</div>

<?php
Ardal_Account::auth_close();

do_action( 'woocommerce_after_lost_password_form' );
