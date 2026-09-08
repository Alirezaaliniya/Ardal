<?php
/**
 * پوستهٔ پنل کاربری — نوار بالا، ستون کناری و ناحیهٔ محتوا.
 *
 * جایگزین myaccount/my-account.php ووکامرس.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;

$ard_name     = Ardal_Account::name();
$ard_initials = Ardal_Account::initials();
$ard_phone    = Ardal_Account::phone();
$ard_alerts   = Ardal_Account::count_orders(
	array_merge( array( 'pending', 'processing' ), Ardal_Account::shipping_statuses() )
);
?>
<div class="ard-ac" data-ard-account>

	<header class="ard-ac__topbar">
		<div class="ard-ac__topbar-inner">
			<a class="ard-ac__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php echo Ardal_Account::brand_mark(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خروجی wp_get_attachment_image یا SVG ثابت. ?>
				<span class="ard-ac__brand-text">
					<span class="ard-ac__brand-name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
					<span class="ard-ac__brand-sub">پنل کاربری</span>
				</span>
			</a>

			<div class="ard-ac__topbar-side">
				<a class="ard-ac__bell" href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" aria-label="سفارش‌های در جریان">
					<?php echo Ardal_Account::icon( 'bell', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php if ( $ard_alerts > 0 ) : ?>
						<span class="ard-ac__bell-badge"><?php echo esc_html( Ardal_Helpers::fa_digits( $ard_alerts ) ); ?></span>
					<?php endif; ?>
				</a>

				<a class="ard-ac__chip" href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-account' ) ); ?>">
					<span class="ard-ac__chip-avatar"><?php echo esc_html( $ard_initials ); ?></span>
					<span class="ard-ac__chip-text">
						<span class="ard-ac__chip-name"><?php echo esc_html( $ard_name ); ?></span>
						<?php if ( '' !== $ard_phone ) : ?>
							<span class="ard-ac__chip-phone"><?php echo esc_html( $ard_phone ); ?></span>
						<?php endif; ?>
					</span>
				</a>
			</div>
		</div>
	</header>

	<header class="ard-ac__mhead">
		<div class="ard-ac__mhead-inner">
			<button type="button" class="ard-ac__burger" aria-expanded="false" aria-controls="ard-ac-drawer" data-ard-ac-burger>
				<span aria-hidden="true"></span><span aria-hidden="true"></span><span aria-hidden="true"></span>
				<span class="ard-sr">باز کردن منوی پنل</span>
			</button>

			<span class="ard-ac__mhead-avatar"><?php echo esc_html( $ard_initials ); ?></span>

			<span class="ard-ac__mhead-text">
				<span class="ard-ac__mhead-name"><?php echo esc_html( $ard_name ); ?></span>
				<?php if ( '' !== $ard_phone ) : ?>
					<span class="ard-ac__mhead-phone"><?php echo esc_html( $ard_phone ); ?></span>
				<?php endif; ?>
			</span>

			<a class="ard-ac__mbell" href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" aria-label="سفارش‌های در جریان">
				<?php echo Ardal_Account::icon( 'bell', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php if ( $ard_alerts > 0 ) : ?>
					<span class="ard-ac__mbell-badge"><?php echo esc_html( Ardal_Helpers::fa_digits( $ard_alerts ) ); ?></span>
				<?php endif; ?>
			</a>
		</div>
	</header>

	<div class="ard-ac__body">
		<?php
		/**
		 * ستون کناری و کشوی موبایل — myaccount/navigation.php
		 *
		 * @since 2.6.0
		 */
		do_action( 'woocommerce_account_navigation' );
		?>

		<div class="ard-ac__content woocommerce-MyAccount-content">
			<?php
			/**
			 * محتوای اندپوینت جاری.
			 *
			 * @since 2.6.0
			 */
			do_action( 'woocommerce_account_content' );
			?>
		</div>
	</div>

	<?php
	// نوار پایین موبایل. اگر ویجت «نوار پایین موبایل» اردال در همین صفحه باشد،
	// CSS این یکی را پنهان می‌کند تا دو نوار روی هم نیفتد.
	$ard_tabs = array(
		array( 'خانه', 'dash', home_url( '/' ), false ),
		array( 'دسته‌ها', 'box', get_permalink( wc_get_page_id( 'shop' ) ), false ),
		array( 'سبد خرید', 'cart', wc_get_cart_url(), false ),
		array( 'علاقه‌مندی', 'fav', wc_get_account_endpoint_url( 'wishlist' ), false ),
		array( 'پنل من', 'user', wc_get_page_permalink( 'myaccount' ), true ),
	);
	?>
	<nav class="ard-ac__tabbar" aria-label="ناوبری موبایل">
		<?php foreach ( $ard_tabs as $ard_tab ) : ?>
			<a
				class="ard-ac__tab<?php echo $ard_tab[3] ? ' is-active' : ''; ?>"
				href="<?php echo esc_url( $ard_tab[2] ); ?>"
				<?php echo $ard_tab[3] ? ' aria-controls="ard-ac-drawer"' : ''; ?>
			>
				<?php echo Ardal_Account::icon( $ard_tab[1], 21 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span><?php echo esc_html( $ard_tab[0] ); ?></span>
			</a>
		<?php endforeach; ?>
	</nav>
</div>
