<?php
/**
 * داشبورد پنل کاربری.
 *
 * جایگزین myaccount/dashboard.php ووکامرس.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;

$ard_user   = wp_get_current_user();
$ard_first  = $ard_user->first_name ? $ard_user->first_name : $ard_user->display_name;
$ard_stats  = Ardal_Account::stats();
$ard_unpaid = Ardal_Account::unpaid_order();
$ard_active = Ardal_Account::active_order();
$ard_chart  = Ardal_Account::chart();
$ard_coupon = Ardal_Account::personal_coupon();
$ard_wish   = Ardal_Account::wishlist_products();

// جملهٔ خوش‌آمد از چیزهایی ساخته می‌شود که واقعاً وجود دارند.
$ard_lines = array();

if ( $ard_active ) {
	$ard_lines[] = Ardal_Account_Settings::text( 'welcome_line_ship' );
}

if ( $ard_unpaid ) {
	$ard_lines[] = Ardal_Account_Settings::text( 'welcome_line_pay' );
}

$ard_in_stock = 0;

foreach ( $ard_wish as $ard_product ) {
	if ( $ard_product->is_in_stock() ) {
		$ard_in_stock++;
	}
}

if ( $ard_in_stock > 0 ) {
	$ard_lines[] = Ardal_Account_Settings::text(
		'welcome_line_wish',
		array( '{count}' => Ardal_Helpers::fa_digits( $ard_in_stock ) )
	);
}

// جمله‌های خالی کنار گذاشته می‌شوند تا «، ، » پشت هم نیفتد.
$ard_lines = array_filter( array_map( 'trim', $ard_lines ) );

$ard_summary = empty( $ard_lines )
	? Ardal_Account_Settings::text( 'welcome_sub' )
	: implode( '، ', $ard_lines ) . '.';

$ard_downloads = wc_get_customer_available_downloads( get_current_user_id() );
?>
<div class="ard-ac__stack">

	<?php if ( Ardal_Account_Settings::section_on( 'welcome' ) ) : ?>
	<?php
	$ard_w_title = Ardal_Account_Settings::text( 'welcome_title', array( '{name}' => $ard_first ) );
	$ard_w_btn   = Ardal_Account_Settings::text( 'welcome_btn' );

	// دکمهٔ دوم بسته به این‌که کاربر فایل دانلودی دارد یا نه، مقصد و متنش فرق می‌کند.
	$ard_w_btn2 = ! empty( $ard_downloads )
		? array( Ardal_Account_Settings::text( 'welcome_btn_down' ), wc_get_account_endpoint_url( 'downloads' ) )
		: array( Ardal_Account_Settings::text( 'welcome_btn_orders' ), wc_get_account_endpoint_url( 'orders' ) );
	?>
	<div class="ard-ac__card ard-ac__welcome">
		<div class="ard-ac__welcome-text">
			<?php if ( '' !== trim( $ard_w_title ) ) : ?>
				<h2 class="ard-ac__welcome-title"><?php echo esc_html( $ard_w_title ); ?></h2>
			<?php endif; ?>
			<?php if ( '' !== trim( $ard_summary ) ) : ?>
				<p class="ard-ac__welcome-sub"><?php echo esc_html( $ard_summary ); ?></p>
			<?php endif; ?>
		</div>
		<div class="ard-ac__welcome-actions">
			<?php if ( '' !== trim( $ard_w_btn ) ) : ?>
				<a class="ard-ac__btn ard-ac__btn--primary" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php echo esc_html( $ard_w_btn ); ?></a>
			<?php endif; ?>
			<?php if ( '' !== trim( $ard_w_btn2[0] ) ) : ?>
				<a class="ard-ac__btn ard-ac__btn--ghost" href="<?php echo esc_url( $ard_w_btn2[1] ); ?>"><?php echo esc_html( $ard_w_btn2[0] ); ?></a>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>

	<?php if ( Ardal_Account_Settings::section_on( 'stats' ) ) : ?>
	<div class="ard-ac__stats">
		<?php foreach ( $ard_stats as $ard_stat ) : ?>
			<div class="ard-ac__card ard-ac__stat">
				<div class="ard-ac__stat-top">
					<span class="ard-ac__stat-icon"><?php echo Ardal_Account::icon( $ard_stat['icon'], 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php if ( '' !== $ard_stat['delta'] ) : ?>
						<span class="ard-ac__stat-delta"><?php echo esc_html( $ard_stat['delta'] ); ?></span>
					<?php endif; ?>
				</div>
				<div class="ard-ac__stat-value">
					<?php echo esc_html( $ard_stat['value'] ); ?>
					<?php if ( '' !== trim( $ard_stat['unit'] ) ) : ?>
						<span class="ard-ac__stat-unit"><?php echo esc_html( $ard_stat['unit'] ); ?></span>
					<?php endif; ?>
				</div>
				<div class="ard-ac__stat-label"><?php echo esc_html( $ard_stat['label'] ); ?></div>
			</div>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>

	<?php if ( $ard_unpaid && Ardal_Account_Settings::section_on( 'alert' ) ) : ?>
		<div class="ard-ac__alert">
			<span class="ard-ac__alert-icon">
				<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
					<circle cx="12" cy="12" r="8"></circle><path d="M12 8v4l3 2"></path>
				</svg>
			</span>
			<?php
			$ard_a_tokens = array(
				'{number}' => Ardal_Helpers::fa_digits( $ard_unpaid->get_order_number() ),
				'{count}'  => Ardal_Helpers::fa_digits( Ardal_Account::order_item_count( $ard_unpaid ) ),
				'{total}'  => Ardal_Helpers::price( $ard_unpaid->get_total() ),
			);

			$ard_a_note = Ardal_Account_Settings::text( 'alert_note', $ard_a_tokens );
			$ard_a_btn  = Ardal_Account_Settings::text( 'alert_btn' );
			?>
			<div class="ard-ac__alert-text">
				<div class="ard-ac__alert-title">
					<?php echo esc_html( Ardal_Account_Settings::text( 'alert_title', $ard_a_tokens ) ); ?>
				</div>
				<?php if ( '' !== trim( $ard_a_note ) ) : ?>
					<div class="ard-ac__alert-note"><?php echo esc_html( $ard_a_note ); ?></div>
				<?php endif; ?>
			</div>
			<?php if ( '' !== trim( $ard_a_btn ) ) : ?>
				<a class="ard-ac__btn ard-ac__btn--amber" href="<?php echo esc_url( $ard_unpaid->get_checkout_payment_url() ); ?>"><?php echo esc_html( $ard_a_btn ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="ard-ac__cols">

		<?php if ( $ard_active && Ardal_Account_Settings::section_on( 'track' ) ) : ?>
			<?php
			$ard_steps    = Ardal_Account::order_steps( $ard_active );
			$ard_shipping = $ard_active->get_shipping_method();
			$ard_code     = Ardal_Account::tracking_code( $ard_active );
			$ard_meta     = array_filter(
				array(
					'کد ' . Ardal_Helpers::fa_digits( $ard_active->get_order_number() ),
					$ard_shipping,
					'' !== $ard_code ? 'مرسوله ' . $ard_code : '',
				)
			);
			?>
			<?php $ard_t_btn = Ardal_Account_Settings::text( 'track_btn' ); ?>
			<div class="ard-ac__card ard-ac__track">
				<div class="ard-ac__chead">
					<div>
						<h3 class="ard-ac__ctitle"><?php echo esc_html( Ardal_Account_Settings::text( 'track_title' ) ); ?></h3>
						<p class="ard-ac__csub"><?php echo esc_html( implode( ' · ', $ard_meta ) ); ?></p>
					</div>
					<?php if ( '' !== trim( $ard_t_btn ) ) : ?>
						<a class="ard-ac__btn ard-ac__btn--soft" href="<?php echo esc_url( $ard_active->get_view_order_url() ); ?>"><?php echo esc_html( $ard_t_btn ); ?></a>
					<?php endif; ?>
				</div>

				<ol class="ard-ac__steps">
					<?php foreach ( $ard_steps as $ard_step ) : ?>
						<li class="ard-ac__step<?php echo $ard_step['done'] ? ' is-done' : ''; ?>">
							<span class="ard-ac__step-bar"><span></span></span>
							<span class="ard-ac__step-dot">
								<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" aria-hidden="true"><path d="M5 13l4 4L19 7"></path></svg>
							</span>
							<span class="ard-ac__step-label"><?php echo esc_html( $ard_step['label'] ); ?></span>
							<span class="ard-ac__step-time"><?php echo esc_html( $ard_step['time'] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ol>

				<div class="ard-ac__track-items">
					<?php foreach ( $ard_active->get_items() as $ard_item ) : ?>
						<?php
						$ard_product = $ard_item->get_product();
						$ard_bits    = array();

						foreach ( $ard_item->get_formatted_meta_data() as $ard_im ) {
							$ard_bits[] = wp_strip_all_tags( $ard_im->display_value );
						}

						$ard_bits[] = Ardal_Helpers::fa_digits( $ard_item->get_quantity() ) . ' عدد';
						?>
						<div class="ard-ac__line">
							<span class="ard-ac__thumb ard-ac__thumb--sm"><?php echo Ardal_Account::thumb( $ard_product, 'woocommerce_gallery_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span class="ard-ac__line-text">
								<span class="ard-ac__line-name"><?php echo esc_html( $ard_item->get_name() ); ?></span>
								<span class="ard-ac__line-meta"><?php echo esc_html( implode( ' · ', $ard_bits ) ); ?></span>
							</span>
							<span class="ard-ac__line-price">
								<?php echo esc_html( Ardal_Helpers::price( $ard_item->get_total() ) ); ?>
								<span class="ard-ac__unit">تومان</span>
							</span>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php else : ?>
			<?php
			$ard_e_title = Ardal_Account_Settings::text( 'track_empty_title' );
			$ard_e_text  = Ardal_Account_Settings::text( 'track_empty_text' );
			$ard_e_btn   = Ardal_Account_Settings::text( 'track_empty_btn' );
			?>
			<div class="ard-ac__card ard-ac__track ard-ac__empty">
				<span class="ard-ac__empty-icon"><?php echo Ardal_Account::icon( Ardal_Account_Settings::icon_name( 'track_empty_icon' ), 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php if ( '' !== trim( $ard_e_title ) ) : ?>
					<h3 class="ard-ac__ctitle"><?php echo esc_html( $ard_e_title ); ?></h3>
				<?php endif; ?>
				<?php if ( '' !== trim( $ard_e_text ) ) : ?>
					<p class="ard-ac__csub"><?php echo esc_html( $ard_e_text ); ?></p>
				<?php endif; ?>
				<?php if ( '' !== trim( $ard_e_btn ) ) : ?>
					<a class="ard-ac__btn ard-ac__btn--primary" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php echo esc_html( $ard_e_btn ); ?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="ard-ac__stack">
			<?php if ( Ardal_Account_Settings::section_on( 'chart' ) ) : ?>
			<div class="ard-ac__card">
				<?php $ard_c_note = Ardal_Account_Settings::text( 'chart_note' ); ?>
				<div class="ard-ac__chead">
					<h3 class="ard-ac__ctitle"><?php echo esc_html( Ardal_Account_Settings::text( 'chart_title' ) ); ?></h3>
					<?php if ( '' !== trim( $ard_c_note ) ) : ?>
						<span class="ard-ac__chead-note"><?php echo esc_html( $ard_c_note ); ?></span>
					<?php endif; ?>
				</div>
				<div class="ard-ac__chart">
					<?php foreach ( $ard_chart as $ard_bar ) : ?>
						<div class="ard-ac__bar-wrap">
							<span class="ard-ac__bar-value"><?php echo esc_html( $ard_bar['value'] ); ?></span>
							<span
								class="ard-ac__bar<?php echo $ard_bar['current'] ? ' is-current' : ''; ?>"
								style="height:<?php echo esc_attr( $ard_bar['height'] ); ?>%"
							></span>
							<span class="ard-ac__bar-month"><?php echo esc_html( $ard_bar['month'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>

			<?php if ( $ard_coupon && Ardal_Account_Settings::section_on( 'coupon' ) ) : ?>
				<div class="ard-ac__card">
					<h3 class="ard-ac__ctitle ard-ac__ctitle--mb"><?php echo esc_html( Ardal_Account_Settings::text( 'coupon_title' ) ); ?></h3>
					<div class="ard-ac__coupon">
						<div class="ard-ac__coupon-text">
							<div class="ard-ac__coupon-code"><?php echo esc_html( $ard_coupon['code'] ); ?></div>
							<div class="ard-ac__coupon-desc"><?php echo esc_html( $ard_coupon['desc'] ); ?></div>
						</div>
						<button
							type="button"
							class="ard-ac__btn ard-ac__btn--accent"
							data-ard-ac-copy="<?php echo esc_attr( $ard_coupon['code'] ); ?>"
						><?php echo esc_html( Ardal_Account_Settings::text( 'coupon_btn' ) ); ?></button>
					</div>
					<?php if ( '' !== $ard_coupon['expires'] ) : ?>
						<p class="ard-ac__coupon-expiry"><?php echo esc_html( $ard_coupon['expires'] ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php
	$ard_suggest = Ardal_Account_Settings::section_on( 'suggest' )
		? Ardal_Account::suggestions( max( 1, (int) Ardal_Account_Settings::get( 'suggest_count' ) ) )
		: array();

	if ( ! empty( $ard_suggest ) ) :
		?>
		<div class="ard-ac__card">
			<?php
			$ard_s_sub  = Ardal_Account_Settings::text( 'suggest_sub' );
			$ard_s_link = Ardal_Account_Settings::text( 'suggest_link' );
			?>
			<div class="ard-ac__chead">
				<div>
					<h3 class="ard-ac__ctitle"><?php echo esc_html( Ardal_Account_Settings::text( 'suggest_title' ) ); ?></h3>
					<?php if ( '' !== trim( $ard_s_sub ) ) : ?>
						<p class="ard-ac__csub"><?php echo esc_html( $ard_s_sub ); ?></p>
					<?php endif; ?>
				</div>
				<?php if ( '' !== trim( $ard_s_link ) ) : ?>
					<a class="ard-ac__link" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php echo esc_html( $ard_s_link ); ?></a>
				<?php endif; ?>
			</div>

			<div class="ard-ac__grid ard-ac__grid--4">
				<?php foreach ( $ard_suggest as $ard_product ) : ?>
					<a class="ard-ac__mini" href="<?php echo esc_url( $ard_product->get_permalink() ); ?>">
						<span class="ard-ac__mini-media"><?php echo Ardal_Account::thumb( $ard_product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span class="ard-ac__mini-name"><?php echo esc_html( $ard_product->get_name() ); ?></span>
						<span class="ard-ac__mini-foot">
							<span class="ard-ac__mini-price"><?php echo wp_kses_post( $ard_product->get_price_html() ); ?></span>
							<span class="ard-ac__mini-plus"><?php echo Ardal_Account::icon( 'plus', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
</div>
