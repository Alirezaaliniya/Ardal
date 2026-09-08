<?php
/**
 * ورود و ثبت‌نام.
 *
 * جایگزین myaccount/form-login.php ووکامرس. قلاب‌های ووکامرس همه سر جای‌شان
 * هستند تا افزونه‌های ورود با پیامک هم کار کنند.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_customer_login_form' );

$ard_register = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
$ard_username = isset( $_POST['username'] ) && is_string( $_POST['username'] ) ? wp_unslash( $_POST['username'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- فقط پر کردن دوبارهٔ فیلد؛ پایین‌تر escape می‌شود.
$ard_email    = isset( $_POST['email'] ) && is_string( $_POST['email'] ) ? wp_unslash( $_POST['email'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

Ardal_Account::auth_open(
	'ورود به حساب',
	$ard_register ? 'وارد شو یا در چند ثانیه حساب بساز.' : 'برای دیدن سفارش‌ها و علاقه‌مندی‌ها وارد شو.'
);
?>

<div class="ard-ac__auth-cards<?php echo $ard_register ? ' ard-ac__auth-cards--two' : ''; ?>" id="customer_login">

	<div class="ard-ac__card">
		<h2 class="ard-ac__ctitle ard-ac__ctitle--mb">ورود</h2>

		<form class="woocommerce-form woocommerce-form-login login ard-ac__auth-form" method="post" novalidate>
			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<p class="ard-ac__field">
				<label for="username">نام کاربری یا ایمیل <span class="ard-ac__req">*</span></label>
				<input
					type="text"
					class="ard-ac__input woocommerce-Input woocommerce-Input--text input-text"
					name="username"
					id="username"
					autocomplete="username"
					value="<?php echo esc_attr( $ard_username ); ?>"
					required
					aria-required="true"
				>
			</p>

			<p class="ard-ac__field">
				<label for="password">رمز عبور <span class="ard-ac__req">*</span></label>
				<input
					type="password"
					class="ard-ac__input woocommerce-Input woocommerce-Input--text input-text"
					name="password"
					id="password"
					autocomplete="current-password"
					required
					aria-required="true"
				>
			</p>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<div class="ard-ac__auth-row">
				<label class="ard-ac__check woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever">
					<span>مرا به خاطر بسپار</span>
				</label>

				<a class="ard-ac__link woocommerce-LostPassword" href="<?php echo esc_url( wp_lostpassword_url() ); ?>">رمز عبورت را فراموش کرده‌ای؟</a>
			</div>

			<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>

			<button
				type="submit"
				class="ard-ac__btn ard-ac__btn--primary ard-ac__btn--block woocommerce-button button woocommerce-form-login__submit"
				name="login"
				value="ورود"
			>ورود به حساب</button>

			<?php do_action( 'woocommerce_login_form_end' ); ?>
		</form>
	</div>

	<?php if ( $ard_register ) : ?>
		<div class="ard-ac__card">
			<h2 class="ard-ac__ctitle">ثبت‌نام</h2>
			<p class="ard-ac__csub ard-ac__csub--mb">با ساختن حساب، سفارش‌ها و آدرس‌هایت برای خریدهای بعدی ذخیره می‌شوند.</p>

			<form method="post" class="woocommerce-form woocommerce-form-register register ard-ac__auth-form" <?php do_action( 'woocommerce_register_form_tag' ); ?> >
				<?php do_action( 'woocommerce_register_form_start' ); ?>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
					<p class="ard-ac__field">
						<label for="reg_username">نام کاربری <span class="ard-ac__req">*</span></label>
						<input
							type="text"
							class="ard-ac__input woocommerce-Input woocommerce-Input--text input-text"
							name="username"
							id="reg_username"
							autocomplete="username"
							value="<?php echo esc_attr( $ard_username ); ?>"
							required
							aria-required="true"
						>
					</p>
				<?php endif; ?>

				<p class="ard-ac__field">
					<label for="reg_email">ایمیل <span class="ard-ac__req">*</span></label>
					<input
						type="email"
						class="ard-ac__input woocommerce-Input woocommerce-Input--text input-text"
						name="email"
						id="reg_email"
						autocomplete="email"
						dir="ltr"
						value="<?php echo esc_attr( $ard_email ); ?>"
						required
						aria-required="true"
					>
				</p>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
					<p class="ard-ac__field">
						<label for="reg_password">رمز عبور <span class="ard-ac__req">*</span></label>
						<input
							type="password"
							class="ard-ac__input woocommerce-Input woocommerce-Input--text input-text"
							name="password"
							id="reg_password"
							autocomplete="new-password"
							required
							aria-required="true"
						>
					</p>
				<?php else : ?>
					<p class="ard-ac__csub">لینک ساخت رمز عبور به ایمیلت فرستاده می‌شود.</p>
				<?php endif; ?>

				<?php do_action( 'woocommerce_register_form' ); ?>

				<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>

				<button
					type="submit"
					class="ard-ac__btn ard-ac__btn--accent ard-ac__btn--block woocommerce-Button woocommerce-button button woocommerce-form-register__submit"
					name="register"
					value="ثبت‌نام"
				>ساخت حساب</button>

				<?php do_action( 'woocommerce_register_form_end' ); ?>
			</form>
		</div>
	<?php endif; ?>
</div>

<?php
Ardal_Account::auth_close();

do_action( 'woocommerce_after_customer_login_form' );
