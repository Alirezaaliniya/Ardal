<?php
/**
 * پیام «ایمیل بازیابی فرستاده شد».
 *
 * جایگزین myaccount/lost-password-confirmation.php ووکامرس.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;

Ardal_Account::auth_open( 'ایمیل بازیابی فرستاده شد' );

do_action( 'woocommerce_before_lost_password_confirmation_message' );
?>

<div class="ard-ac__auth-cards">
	<div class="ard-ac__card ard-ac__empty">
		<span class="ard-ac__empty-icon ard-ac__empty-icon--green">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 13l4 4L19 7"></path></svg>
		</span>
		<h2 class="ard-ac__ctitle">ایمیل بازیابی برایت فرستاده شد</h2>
		<p class="ard-ac__csub">
			<?php
			echo esc_html(
				apply_filters(
					'woocommerce_lost_password_confirmation_message',
					'ایمیل بازیابی رمز عبور به نشانی ثبت‌شده در حسابت فرستاده شد؛ ممکن است چند دقیقه طول بکشد تا برسد. پیش از تلاش دوباره، دست‌کم ۱۰ دقیقه صبر کن.'
				)
			);
			?>
		</p>
		<a class="ard-ac__btn ard-ac__btn--primary" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">بازگشت به صفحه ورود</a>
	</div>
</div>

<?php
do_action( 'woocommerce_after_lost_password_confirmation_message' );

Ardal_Account::auth_close();
