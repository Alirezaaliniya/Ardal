<?php
/**
 * ستون کناری پنل و کشوی موبایل.
 *
 * جایگزین myaccount/navigation.php ووکامرس.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;

/**
 * @since 2.6.0
 */
do_action( 'woocommerce_before_account_navigation' );

$ard_items    = wc_get_account_menu_items();
$ard_name     = Ardal_Account::name();
$ard_initials = Ardal_Account::initials();
$ard_phone    = Ardal_Account::phone();
$ard_logout   = wc_logout_url();
$ard_support  = array(
	'phone' => trim( (string) Ardal_Palette::get( 'account_support_phone' ) ),
	'hours' => trim( (string) Ardal_Palette::get( 'account_support_hours' ) ),
);

// یادداشت زیر هر آیتم در کشوی موبایل.
$ard_notes = array(
	'dashboard'    => 'خلاصه حساب و سفارش فعال',
	'orders'       => 'پیگیری و فاکتور سفارش‌ها',
	'wishlist'     => 'کالاهای ذخیره‌شده',
	'edit-address' => 'آدرس‌های تحویل سفارش',
	'edit-account' => 'ویرایش مشخصات و رمز عبور',
	'reviews'      => 'دیدگاه‌های ثبت‌شده',
);

$ard_badges = array(
	'orders'   => Ardal_Account::count_orders(
		array_merge( array( 'pending', 'processing' ), Ardal_Account::shipping_statuses() )
	),
	'wishlist' => count( Ardal_Account::wishlist_ids() ),
	'reviews'  => Ardal_Account::review_stats()['pending'],
);

/**
 * آیا آیتم منو صفحهٔ جاری است. تشخیص را به خود ووکامرس می‌سپاریم تا حالت‌های
 * ویژه (داشبورد بدون اندپوینت، و جزئیات سفارش که زیرمجموعهٔ «سفارش‌های من»
 * است) همان‌طور که در پیشخوان تعریف شده رفتار کنند.
 *
 * @param string $key
 * @return bool
 */
$ard_is_current = function ( $key ) {
	return false !== strpos( wc_get_account_menu_item_classes( $key ), 'is-active' );
};
?>
<div class="ard-ac__side">

	<div class="ard-ac__card ard-ac__nav-card">
		<div class="ard-ac__user">
			<span class="ard-ac__user-avatar"><?php echo esc_html( $ard_initials ); ?></span>
			<span class="ard-ac__user-text">
				<span class="ard-ac__user-name"><?php echo esc_html( $ard_name ); ?></span>
				<?php if ( '' !== $ard_phone ) : ?>
					<span class="ard-ac__user-phone"><?php echo esc_html( $ard_phone ); ?></span>
				<?php endif; ?>
			</span>
		</div>

		<nav class="ard-ac__nav" aria-label="منوی پنل کاربری">
			<?php foreach ( $ard_items as $ard_key => $ard_label ) : ?>
				<?php
				$ard_on    = $ard_is_current( $ard_key );
				$ard_badge = isset( $ard_badges[ $ard_key ] ) ? (int) $ard_badges[ $ard_key ] : 0;
				?>
				<a
					class="ard-ac__nav-item<?php echo $ard_on ? ' is-active' : ''; ?>"
					href="<?php echo esc_url( wc_get_account_endpoint_url( $ard_key ) ); ?>"
					data-ard-endpoint="<?php echo esc_attr( $ard_key ); ?>"
					<?php echo $ard_on ? ' aria-current="page"' : ''; ?>
				>
					<?php echo Ardal_Account::icon( Ardal_Account::menu_icon( $ard_key ), 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span class="ard-ac__nav-label"><?php echo esc_html( $ard_label ); ?></span>
					<?php if ( $ard_badge > 0 ) : ?>
						<span class="ard-ac__nav-badge"><?php echo esc_html( Ardal_Helpers::fa_digits( $ard_badge ) ); ?></span>
					<?php endif; ?>
				</a>
			<?php endforeach; ?>
		</nav>

		<div class="ard-ac__logout-wrap">
			<a class="ard-ac__logout" href="<?php echo esc_url( $ard_logout ); ?>">
				<?php echo Ardal_Account::icon( 'logout', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span>خروج از حساب</span>
			</a>
		</div>
	</div>

	<?php if ( '' !== $ard_support['phone'] || '' !== $ard_support['hours'] ) : ?>
		<div class="ard-ac__support">
			<span class="ard-ac__support-blob" aria-hidden="true"></span>
			<div class="ard-ac__support-body">
				<div class="ard-ac__support-title">پشتیبانی <?php echo esc_html( get_bloginfo( 'name' ) ); ?></div>
				<?php if ( '' !== $ard_support['hours'] ) : ?>
					<p class="ard-ac__support-note"><?php echo esc_html( $ard_support['hours'] ); ?></p>
				<?php endif; ?>
				<?php if ( '' !== $ard_support['phone'] ) : ?>
					<a class="ard-ac__support-phone" href="tel:<?php echo esc_attr( Ardal_Helpers::en_digits( $ard_support['phone'] ) ); ?>">
						<?php echo esc_html( Ardal_Helpers::fa_digits( $ard_support['phone'] ) ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</div>

<div class="ard-ac__scrim" data-ard-ac-scrim hidden></div>

<aside class="ard-ac__drawer" id="ard-ac-drawer" data-ard-ac-drawer aria-label="منوی پنل کاربری">
	<div class="ard-ac__drawer-head">
		<span class="ard-ac__drawer-avatar"><?php echo esc_html( $ard_initials ); ?></span>
		<span class="ard-ac__drawer-text">
			<span class="ard-ac__drawer-name"><?php echo esc_html( $ard_name ); ?></span>
			<?php if ( '' !== $ard_phone ) : ?>
				<span class="ard-ac__drawer-phone"><?php echo esc_html( $ard_phone ); ?></span>
			<?php endif; ?>
		</span>
		<button type="button" class="ard-ac__drawer-close" aria-label="بستن منو" data-ard-ac-close>
			<?php echo Ardal_Account::icon( 'close', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</button>
	</div>

	<div class="ard-ac__drawer-body">
		<?php foreach ( $ard_items as $ard_key => $ard_label ) : ?>
			<?php
			$ard_on    = $ard_is_current( $ard_key );
			$ard_badge = isset( $ard_badges[ $ard_key ] ) ? (int) $ard_badges[ $ard_key ] : 0;
			$ard_note  = isset( $ard_notes[ $ard_key ] ) ? $ard_notes[ $ard_key ] : '';
			?>
			<a
				class="ard-ac__drawer-item<?php echo $ard_on ? ' is-active' : ''; ?>"
				href="<?php echo esc_url( wc_get_account_endpoint_url( $ard_key ) ); ?>"
				data-ard-endpoint="<?php echo esc_attr( $ard_key ); ?>"
			>
				<span class="ard-ac__drawer-icon">
					<?php echo Ardal_Account::icon( Ardal_Account::menu_icon( $ard_key ), 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
				<span class="ard-ac__drawer-item-text">
					<span class="ard-ac__drawer-item-label"><?php echo esc_html( $ard_label ); ?></span>
					<?php if ( '' !== $ard_note ) : ?>
						<span class="ard-ac__drawer-item-note"><?php echo esc_html( $ard_note ); ?></span>
					<?php endif; ?>
				</span>
				<?php if ( $ard_badge > 0 ) : ?>
					<span class="ard-ac__drawer-badge"><?php echo esc_html( Ardal_Helpers::fa_digits( $ard_badge ) ); ?></span>
				<?php endif; ?>
				<?php echo Ardal_Account::icon( 'chevron', 16, 'ard-ac__drawer-arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
		<?php endforeach; ?>
	</div>

	<div class="ard-ac__drawer-foot">
		<?php if ( '' !== $ard_support['phone'] ) : ?>
			<a class="ard-ac__drawer-support" href="tel:<?php echo esc_attr( Ardal_Helpers::en_digits( $ard_support['phone'] ) ); ?>">
				<?php echo Ardal_Account::icon( 'ticket', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="ard-ac__drawer-item-text">
					<span class="ard-ac__drawer-item-label">پشتیبانی <?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
					<span class="ard-ac__drawer-item-note"><?php echo esc_html( Ardal_Helpers::fa_digits( $ard_support['phone'] ) ); ?></span>
				</span>
			</a>
		<?php endif; ?>

		<a class="ard-ac__drawer-logout" href="<?php echo esc_url( $ard_logout ); ?>">
			<?php echo Ardal_Account::icon( 'logout', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<span>خروج از حساب</span>
		</a>
	</div>
</aside>

<?php
/**
 * @since 2.6.0
 */
do_action( 'woocommerce_after_account_navigation' );
