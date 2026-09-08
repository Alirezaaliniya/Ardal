<?php
/**
 * فایل‌های دانلودی کاربر.
 *
 * جایگزین myaccount/downloads.php ووکامرس. به‌جای جدول پیش‌فرض، کارت‌های پنل
 * رندر می‌شوند؛ قلاب‌های before/after سر جای‌شان هستند ولی
 * `woocommerce_available_downloads` (که جدول پیش‌فرض را می‌ساخت) صدا زده
 * نمی‌شود، چون خودمان فهرست را می‌سازیم.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;

$ard_downloads = WC()->customer->get_downloadable_products();
$ard_has       = (bool) $ard_downloads;

do_action( 'woocommerce_before_account_downloads', $ard_has );
?>
<div class="ard-ac__stack">

	<div class="ard-ac__card ard-ac__chead ard-ac__chead--card">
		<div>
			<h2 class="ard-ac__ptitle">فایل‌های دانلودی</h2>
			<p class="ard-ac__csub">
				<?php echo esc_html( Ardal_Helpers::fa_digits( count( $ard_downloads ) ) ); ?> فایل در دسترس توست
			</p>
		</div>
	</div>

	<?php if ( ! $ard_has ) : ?>
		<div class="ard-ac__card ard-ac__empty">
			<span class="ard-ac__empty-icon"><?php echo Ardal_Account::icon( 'box', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<h3 class="ard-ac__ctitle">فایلی برای دانلود نداری</h3>
			<p class="ard-ac__csub">اگر محصول دانلودی بخری، فایل‌هایش همین‌جا برای همیشه در دسترس می‌ماند.</p>
			<a class="ard-ac__btn ard-ac__btn--primary" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>">دیدن محصول‌ها</a>
		</div>
	<?php else : ?>
		<?php do_action( 'woocommerce_before_available_downloads' ); ?>

		<?php foreach ( $ard_downloads as $ard_download ) : ?>
			<?php
			$ard_product = isset( $ard_download['product_id'] ) ? wc_get_product( $ard_download['product_id'] ) : null;
			$ard_left    = isset( $ard_download['downloads_remaining'] ) ? $ard_download['downloads_remaining'] : '';
			$ard_expires = ! empty( $ard_download['access_expires'] ) ? strtotime( $ard_download['access_expires'] ) : 0;

			$ard_facts = array();

			if ( '' !== $ard_left && null !== $ard_left ) {
				$ard_facts[] = Ardal_Helpers::fa_digits( $ard_left ) . ' بار دانلود باقی مانده';
			} else {
				$ard_facts[] = 'بدون محدودیت دفعات';
			}

			$ard_facts[] = $ard_expires
				? 'تا ' . Ardal_Jalali::format( $ard_expires )
				: 'بدون تاریخ انقضا';
			?>
			<div class="ard-ac__card ard-ac__download">
				<span class="ard-ac__thumb ard-ac__thumb--md"><?php echo Ardal_Account::thumb( $ard_product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>

				<div class="ard-ac__download-text">
					<div class="ard-ac__download-name">
						<?php echo esc_html( $ard_download['download_name'] ); ?>
					</div>
					<div class="ard-ac__download-meta">
						<?php if ( ! empty( $ard_download['product_name'] ) && $ard_download['product_name'] !== $ard_download['download_name'] ) : ?>
							<span><?php echo esc_html( $ard_download['product_name'] ); ?></span>
						<?php endif; ?>
						<?php foreach ( $ard_facts as $ard_fact ) : ?>
							<span><?php echo esc_html( $ard_fact ); ?></span>
						<?php endforeach; ?>
					</div>
				</div>

				<a class="ard-ac__btn ard-ac__btn--primary" href="<?php echo esc_url( $ard_download['download_url'] ); ?>">
					<?php echo Ardal_Account::icon( 'down', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					دانلود
				</a>
			</div>
		<?php endforeach; ?>

		<?php do_action( 'woocommerce_after_available_downloads' ); ?>
	<?php endif; ?>
</div>
<?php
do_action( 'woocommerce_after_account_downloads', $ard_has );
