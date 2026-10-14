<?php
/**
 * کیف پول کاربر — اندپوینت اختصاصی اردال.
 *
 * کارت موجودی، فرم شارژ (بسته تا کاربر بخواهد)، خلاصهٔ کش‌بگ فعال،
 * تاریخچهٔ کش‌بگ و همهٔ تراکنش‌ها. خودِ اجزا مال Ardal_Wallet_Render‌اند تا
 * با ویجت‌های المنتور یکی بمانند.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Ardal_Wallet' ) || ! Ardal_Wallet::is_enabled() ) {
	echo '<div class="ard-ac__card ard-ac__empty"><p class="ard-ac__csub">کیف پول در حال حاضر غیرفعال است.</p></div>';

	return;
}

$ard_user    = get_current_user_id();
$ard_balance = Ardal_Wallet::balance( $ard_user );
$ard_topup   = Ardal_Wallet::on( 'topup_enabled' );
$ard_active  = Ardal_Wallet::active_cashback( $ard_user );
$ard_cb_left = 0.0;
$ard_cb_soon = '';

foreach ( $ard_active as $ard_row ) {
	$ard_cb_left += (float) $ard_row['amount'] - (float) $ard_row['used'];

	if ( '' === $ard_cb_soon && ! empty( $ard_row['expires_at'] ) ) {
		$ard_cb_soon = $ard_row['expires_at'];
	}
}

$ard_has_cb = Ardal_Wallet::count( $ard_user, array( 'types' => array( Ardal_Wallet::TYPE_CASHBACK ) ) ) > 0;
?>
<div class="ard-ac__stack ard-wl-account">

	<div class="ard-ac__card ard-wl-hero">
		<div class="ard-wl-hero__art"><?php echo Ardal_Wallet::art_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>

		<div class="ard-wl-hero__main">
			<span class="ard-wl-hero__cap">
				<?php echo Ardal_Account::icon( 'wallet', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				موجودی کیف پول
			</span>
			<strong class="ard-wl-hero__amount" data-ard-wallet-balance><?php echo Ardal_Wallet::money( $ard_balance ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>

			<?php if ( $ard_cb_left > 0 ) : ?>
				<span class="ard-wl-hero__sub">
					<?php echo Ardal_Wallet::money( $ard_cb_left ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> از این موجودی کش‌بگ است
					<?php if ( '' !== $ard_cb_soon ) : ?>
						· نزدیک‌ترین انقضا <?php echo esc_html( Ardal_Wallet::date( $ard_cb_soon ) ); ?>
					<?php endif; ?>
				</span>
			<?php endif; ?>
		</div>

		<?php if ( $ard_topup ) : ?>
			<button type="button" class="ard-ac__btn ard-ac__btn--accent ard-wl-hero__btn" data-ard-wallet-toggle aria-expanded="false" aria-controls="ard-wl-topup">
				<?php echo Ardal_Account::icon( 'plus', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				افزایش اعتبار
			</button>
		<?php endif; ?>
	</div>

	<?php if ( $ard_topup ) : ?>
		<div class="ard-ac__card ard-wl-account__topup" id="ard-wl-topup" hidden>
			<?php
			echo Ardal_Wallet_Render::card( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				array(
					'anchor'       => 'ard-wl-account-card',
					'show_art'     => 'no',
					'show_balance' => 'no',
					'class'        => 'ard-wl-card--flat',
				)
			);
			?>
		</div>
	<?php endif; ?>

	<?php if ( $ard_has_cb ) : ?>
		<div class="ard-ac__card">
			<?php
			echo Ardal_Wallet_Render::transactions( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				array(
					'mode'     => 'cashback',
					'style'    => 'table',
					'title'    => 'تاریخچهٔ کش‌بگ',
					'per_page' => 5,
					'anchor'   => 'ard-wl-cashback',
				)
			);
			?>
		</div>
	<?php endif; ?>

	<div class="ard-ac__card">
		<?php
		echo Ardal_Wallet_Render::transactions( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			array(
				'mode'     => 'transactions',
				'style'    => 'list',
				'title'    => 'تراکنش‌های اخیر',
				'per_page' => 10,
				'anchor'   => 'ard-wl-transactions',
				'empty'    => 'هنوز تراکنشی ثبت نشده است. با شارژ کیف پول یا دریافت کش‌بگ، اینجا پر می‌شود.',
			)
		);
		?>
	</div>
</div>
