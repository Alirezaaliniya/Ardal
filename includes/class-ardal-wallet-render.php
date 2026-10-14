<?php
/**
 * خروجی سمت کاربر کیف پول: کارت افزایش اعتبار، موجودی، فهرست تراکنش‌ها و
 * کش‌بگ، و صفحهٔ «شارژ انجام شد».
 *
 * یک جا ساخته می‌شود و سه جا استفاده: شورت‌کدها، ویجت‌های المنتور و صفحهٔ
 * کیف پول پنل کاربری. ویجت‌ها فقط پارامتر می‌دهند و استایل را با متغیرهای
 * CSS عوض می‌کنند؛ مارک‌آپ یکی است.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Wallet_Render {

	public static function init() {
		add_shortcode( 'ardal_wallet', array( __CLASS__, 'shortcode_card' ) );
		add_shortcode( 'ardal_wallet_balance', array( __CLASS__, 'shortcode_balance' ) );
		add_shortcode( 'ardal_wallet_transactions', array( __CLASS__, 'shortcode_transactions' ) );
		add_shortcode( 'ardal_wallet_cashback', array( __CLASS__, 'shortcode_cashback' ) );
	}

	/**
	 * فایل‌ها و دادهٔ جاوااسکریپت؛ چند بار صدا زدن بی‌ضرر است.
	 */
	public static function assets() {
		static $done = false;

		wp_enqueue_style( 'ardal' );
		wp_enqueue_style( 'ardal-wallet' );
		wp_enqueue_script( 'ardal' );
		wp_enqueue_script( 'ardal-wallet' );

		if ( $done ) {
			return;
		}

		$done = true;

		wp_localize_script(
			'ardal-wallet',
			'ardalWallet',
			array(
				'ajax'     => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'ardal-wallet' ),
				'loggedIn' => is_user_logged_in(),
				'loginUrl' => wc_get_page_permalink( 'myaccount' ),
				'min'      => (float) Ardal_Wallet::option( 'min_topup' ),
				'max'      => (float) Ardal_Wallet::option( 'max_topup' ),
				'faDigits' => '1' === Ardal_Palette::get( 'persian_digits' ),
				'i18n'     => array(
					'loggedOut' => 'برای افزایش اعتبار ابتدا وارد حساب شو.',
					'login'     => 'ورود به حساب',
					'empty'     => 'مبلغ افزایش اعتبار را وارد کن.',
					'min'       => 'کمترین مبلغ افزایش اعتبار %s تومان است.',
					'max'       => 'بیشترین مبلغ افزایش اعتبار %s تومان است.',
					'sending'   => 'در حال انتقال به درگاه پرداخت…',
					'error'     => 'اتصال به درگاه ممکن نشد. دوباره تلاش کن.',
				),
			)
		);
	}

	/**
	 * نشانی ورود با بازگشت به همین صفحه.
	 *
	 * @return string
	 */
	private static function login_url() {
		$back = home_url( add_query_arg( array() ) );

		return add_query_arg( 'redirect_to', rawurlencode( $back ), wc_get_page_permalink( 'myaccount' ) );
	}

	/* --------------------------------------------------------------------- */
	/* کارت افزایش اعتبار                                                     */
	/* --------------------------------------------------------------------- */

	/**
	 * @return array
	 */
	public static function card_defaults() {
		return array(
			'anchor'       => '',
			'title'        => 'موجودی کیف پول',
			'show_art'     => 'yes',
			'show_balance' => 'yes',
			'show_quick'   => 'yes',
			'show_limits'  => 'yes',
			'show_note'    => 'yes',
			'quick'        => '',
			'placeholder'  => 'مبلغ دلخواه را وارد کن',
			'button'       => 'افزایش اعتبار',
			'note'         => '',
			'login_text'   => 'برای دیدن و شارژ کیف پول وارد حساب کاربری شو.',
			'login_button' => 'ورود به حساب',
			'disabled'     => 'افزایش اعتبار در حال حاضر غیرفعال است.',
			'class'        => '',
		);
	}

	/**
	 * کارت کیف پول.
	 *
	 * @param array $args
	 * @return string
	 */
	public static function card( $args = array() ) {
		if ( ! Ardal_Wallet::is_enabled() ) {
			return '';
		}

		$a = wp_parse_args( $args, self::card_defaults() );

		self::assets();

		$logged  = is_user_logged_in();
		$topup   = Ardal_Wallet::on( 'topup_enabled' );
		$note    = '' !== trim( (string) $a['note'] ) ? $a['note'] : Ardal_Wallet::option( 'topup_note' );
		$quick   = Ardal_Wallet::parse_amounts( '' !== trim( (string) $a['quick'] ) ? $a['quick'] : Ardal_Wallet::option( 'quick_amounts' ) );
		$min     = (float) Ardal_Wallet::option( 'min_topup' );
		$max     = (float) Ardal_Wallet::option( 'max_topup' );
		$anchor  = '' !== $a['anchor'] ? sanitize_html_class( $a['anchor'] ) : 'ard-wl-' . wp_unique_id();
		$classes = trim( 'ard-wl-card ' . $a['class'] );

		ob_start();
		?>
		<section id="<?php echo esc_attr( $anchor ); ?>" class="<?php echo esc_attr( $classes ); ?>" data-ard-wallet>
			<div class="ard-wl-card__head">
				<?php if ( 'yes' === $a['show_art'] ) : ?>
					<div class="ard-wl-card__art"><?php echo Ardal_Wallet::art_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG ثابت یا تصویر escape‌شده. ?></div>
				<?php endif; ?>

				<?php if ( 'yes' === $a['show_balance'] ) : ?>
					<div class="ard-wl-card__balance">
						<span class="ard-wl-card__cap">
							<span class="ard-wl-card__cap-icon" aria-hidden="true"><?php echo Ardal_Account::icon( 'wallet', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<?php echo esc_html( $a['title'] ); ?>
						</span>
						<strong class="ard-wl-card__amount" data-ard-wallet-balance><?php echo $logged ? Ardal_Wallet::money( Ardal_Wallet::balance() ) : '—'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( ! $logged ) : ?>
				<div class="ard-wl-card__login">
					<span><?php echo esc_html( $a['login_text'] ); ?></span>
					<a class="ard-wl-btn ard-wl-btn--primary" href="<?php echo esc_url( self::login_url() ); ?>"><?php echo esc_html( $a['login_button'] ); ?></a>
				</div>
			<?php elseif ( ! $topup ) : ?>
				<?php if ( '' !== trim( (string) $a['disabled'] ) ) : ?>
					<p class="ard-wl-card__off"><?php echo esc_html( $a['disabled'] ); ?></p>
				<?php endif; ?>
			<?php else : ?>
				<?php if ( 'yes' === $a['show_quick'] && $quick ) : ?>
					<div class="ard-wl-card__chips" role="group" aria-label="مبلغ‌های پیشنهادی">
						<?php foreach ( $quick as $amount ) : ?>
							<button type="button" class="ard-wl-chip" data-ard-wallet-quick="<?php echo esc_attr( $amount ); ?>" aria-pressed="false"><?php echo esc_html( Ardal_Wallet::short_money( $amount ) ); ?></button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="ard-wl-card__form">
					<label class="ard-wl-sr" for="<?php echo esc_attr( $anchor ); ?>-amount"><?php echo esc_html( $a['placeholder'] ); ?></label>
					<span class="ard-wl-card__field">
						<input
							type="text"
							class="ard-wl-card__input"
							id="<?php echo esc_attr( $anchor ); ?>-amount"
							data-ard-wallet-input
							inputmode="numeric"
							autocomplete="off"
							placeholder="<?php echo esc_attr( $a['placeholder'] ); ?>"
						/>
						<span class="ard-wl-card__unit" aria-hidden="true">تومان</span>
					</span>
					<button type="button" class="ard-wl-btn ard-wl-btn--accent ard-wl-card__submit" data-ard-wallet-submit><?php echo esc_html( $a['button'] ); ?></button>
				</div>

				<?php if ( 'yes' === $a['show_limits'] && ( $min > 0 || $max > 0 ) ) : ?>
					<p class="ard-wl-card__limits">
						<?php
						$parts = array();

						if ( $min > 0 ) {
							$parts[] = 'حداقل ' . Ardal_Wallet::short_money( $min );
						}

						if ( $max > 0 ) {
							$parts[] = 'حداکثر ' . Ardal_Wallet::short_money( $max );
						}

						echo esc_html( implode( ' · ', $parts ) );
						?>
					</p>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( 'yes' === $a['show_note'] && '' !== trim( (string) $note ) ) : ?>
				<p class="ard-wl-card__note"><?php echo esc_html( $note ); ?></p>
			<?php endif; ?>
		</section>
		<?php

		return (string) ob_get_clean();
	}

	/**
	 * موجودی تنها، برای هدر یا متن.
	 *
	 * @param array $args before، after، link، icon، guest.
	 * @return string
	 */
	public static function balance( $args = array() ) {
		$a = wp_parse_args(
			$args,
			array(
				'before' => '',
				'after'  => '',
				'link'   => 'yes',
				'icon'   => 'yes',
				'guest'  => '',
				'class'  => '',
			)
		);

		if ( ! Ardal_Wallet::is_enabled() ) {
			return '';
		}

		if ( ! is_user_logged_in() ) {
			return '' !== $a['guest']
				? '<span class="ard-wl-balance ard-wl-balance--guest ' . esc_attr( $a['class'] ) . '">' . esc_html( $a['guest'] ) . '</span>'
				: '';
		}

		wp_enqueue_style( 'ardal-wallet' );

		$tag   = 'yes' === $a['link'] ? 'a' : 'span';
		$href  = 'a' === $tag ? ' href="' . esc_url( Ardal_Wallet::account_url() ) . '"' : '';
		$inner = ( 'yes' === $a['icon'] ? '<span class="ard-wl-balance__icon" aria-hidden="true">' . Ardal_Account::icon( 'wallet', 18 ) . '</span>' : '' )
			. '<span class="ard-wl-balance__text">'
			. ( '' !== $a['before'] ? '<span class="ard-wl-balance__label">' . esc_html( $a['before'] ) . '</span>' : '' )
			. '<span class="ard-wl-balance__value" data-ard-wallet-balance>' . Ardal_Wallet::money( Ardal_Wallet::balance() ) . '</span>'
			. ( '' !== $a['after'] ? '<span class="ard-wl-balance__label">' . esc_html( $a['after'] ) . '</span>' : '' )
			. '</span>';

		return sprintf( '<%1$s class="ard-wl-balance %2$s"%3$s>%4$s</%1$s>', $tag, esc_attr( $a['class'] ), $href, $inner );
	}

	/* --------------------------------------------------------------------- */
	/* تراکنش‌ها و کش‌بگ                                                       */
	/* --------------------------------------------------------------------- */

	/**
	 * @return array
	 */
	public static function list_defaults() {
		return array(
			'mode'          => 'transactions', // transactions | cashback
			'style'         => 'list',         // list | table
			'title'         => '',
			'per_page'      => 10,
			'hide_cashback' => 'no',
			'types'         => array(),
			'empty'         => 'هنوز تراکنشی ثبت نشده است.',
			'page_key'      => '',
			'anchor'        => '',
			'pager'         => 'yes',
			'class'         => '',
		);
	}

	/**
	 * فهرست تراکنش‌ها یا تاریخچهٔ کش‌بگ.
	 *
	 * @param array $args
	 * @return string
	 */
	public static function transactions( $args = array() ) {
		if ( ! Ardal_Wallet::is_enabled() || ! is_user_logged_in() ) {
			return '';
		}

		$a        = wp_parse_args( $args, self::list_defaults() );
		$cashback = 'cashback' === $a['mode'];
		$key      = '' !== $a['page_key'] ? sanitize_key( $a['page_key'] ) : ( $cashback ? 'ard_cbpage' : 'ard_txpage' );
		$anchor   = '' !== $a['anchor'] ? sanitize_html_class( $a['anchor'] ) : ( $cashback ? 'ard-wl-cashback' : 'ard-wl-transactions' );
		$per_page = max( 1, (int) $a['per_page'] );
		$page     = isset( $_GET[ $key ] ) ? max( 1, absint( $_GET[ $key ] ) ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$user_id  = get_current_user_id();
		$query    = array();

		if ( $cashback ) {
			$query['types'] = array( Ardal_Wallet::TYPE_CASHBACK );
		} else {
			$query['types'] = array_filter( (array) $a['types'] );

			if ( 'yes' === $a['hide_cashback'] ) {
				$query['exclude_types'] = array( Ardal_Wallet::TYPE_CASHBACK );
			}
		}

		// موجودی یک بار خوانده می‌شود تا کش‌بگ‌های منقضی پیش از نمایش کسر شوند.
		Ardal_Wallet::balance( $user_id );

		$total = Ardal_Wallet::count( $user_id, $query );
		$rows  = Ardal_Wallet::transactions( $user_id, $query + array( 'limit' => $per_page, 'offset' => ( $page - 1 ) * $per_page ) );

		self::assets();

		ob_start();
		?>
		<section id="<?php echo esc_attr( $anchor ); ?>" class="ard-wl-tx ard-wl-tx--<?php echo esc_attr( $a['style'] ); ?> <?php echo esc_attr( $a['class'] ); ?>">
			<?php if ( '' !== trim( (string) $a['title'] ) ) : ?>
				<div class="ard-wl-tx__head">
					<h3 class="ard-wl-tx__title"><?php echo esc_html( $a['title'] ); ?></h3>
					<?php if ( $total > 0 ) : ?>
						<span class="ard-wl-tx__count"><?php echo esc_html( Ardal_Helpers::fa_digits( $total ) ); ?> مورد</span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( ! $rows ) : ?>
				<p class="ard-wl-tx__empty"><?php echo esc_html( $a['empty'] ); ?></p>
			<?php elseif ( 'table' === $a['style'] ) : ?>
				<?php self::table( $rows, $cashback ); ?>
			<?php else : ?>
				<ul class="ard-wl-tx__list">
					<?php foreach ( $rows as $tx ) : ?>
						<?php self::list_row( $tx, $cashback ); ?>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<?php
			if ( 'yes' === $a['pager'] ) {
				self::pager( $total, $per_page, $page, $key, $anchor );
			}
			?>
		</section>
		<?php

		return (string) ob_get_clean();
	}

	/**
	 * آیکون هر نوع تراکنش.
	 *
	 * @param string $type
	 * @return string
	 */
	private static function type_icon( $type ) {
		$map = array(
			Ardal_Wallet::TYPE_TOPUP    => 'plus',
			Ardal_Wallet::TYPE_ORDER    => 'cart',
			Ardal_Wallet::TYPE_REFUND   => 'back',
			Ardal_Wallet::TYPE_CASHBACK => 'ticket',
			Ardal_Wallet::TYPE_EXPIRE   => 'clock',
			Ardal_Wallet::TYPE_ADMIN    => 'user',
		);

		return $map[ $type ] ?? 'wallet';
	}

	/**
	 * @param array $tx
	 * @param bool  $cashback
	 */
	private static function list_row( $tx, $cashback ) {
		$in     = $tx['is_credit'];
		$status = Ardal_Wallet::status_label( $tx['status'] );
		$state  = $cashback ? Ardal_Wallet::cashback_state( $tx ) : null;
		?>
		<li class="ard-wl-tx__row">
			<span class="ard-wl-tx__dot ard-wl-tx__dot--<?php echo $in ? 'in' : 'out'; ?>" aria-hidden="true"><?php echo Ardal_Account::icon( self::type_icon( $tx['type'] ), 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>

			<span class="ard-wl-tx__text">
				<span class="ard-wl-tx__label"><?php echo esc_html( '' !== $tx['note'] && $cashback ? $tx['note'] : Ardal_Wallet::type_label( $tx ) ); ?></span>
				<span class="ard-wl-tx__meta">
					<?php echo esc_html( Ardal_Wallet::date( $tx['created_at'], ! $cashback ) ); ?>

					<?php if ( $cashback ) : ?>
						<span class="ard-wl-pill ard-wl-pill--<?php echo esc_attr( $state['key'] ); ?>"><?php echo esc_html( $state['label'] ); ?></span>
						<?php if ( 'active' === $state['key'] && $tx['expires_at'] ) : ?>
							<span class="ard-wl-tx__exp">انقضا: <?php echo esc_html( Ardal_Wallet::date( $tx['expires_at'] ) ); ?></span>
						<?php endif; ?>
						<?php if ( $tx['used'] > 0 && 'used' !== $state['key'] ) : ?>
							<span class="ard-wl-tx__exp">استفاده‌شده: <?php echo Ardal_Wallet::money( $tx['used'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<?php endif; ?>
					<?php elseif ( Ardal_Wallet::STATUS_SUCCESS !== $tx['status'] ) : ?>
						<span class="ard-wl-pill ard-wl-pill--<?php echo esc_attr( $status['key'] ); ?>"><?php echo esc_html( $status['label'] ); ?></span>
					<?php endif; ?>
				</span>
			</span>

			<span class="ard-wl-tx__amount ard-wl-tx__amount--<?php echo $in ? 'in' : 'out'; ?>">
				<span class="ard-wl-tx__sign" aria-hidden="true"><?php echo $in ? '+' : '−'; ?></span><?php echo Ardal_Wallet::money( $tx['abs'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</span>
		</li>
		<?php
	}

	/**
	 * @param array $rows
	 * @param bool  $cashback
	 */
	private static function table( $rows, $cashback ) {
		?>
		<div class="ard-wl-tx__scroll">
			<table class="ard-wl-tx__table">
				<thead>
					<tr>
						<th>شناسه</th>
						<?php if ( $cashback ) : ?>
							<th>مبلغ کش‌بگ</th>
							<th>استفاده‌شده</th>
							<th>وضعیت</th>
							<th>تاریخ</th>
							<th>انقضا</th>
						<?php else : ?>
							<th>نوع</th>
							<th>مبلغ</th>
							<th>تاریخ</th>
							<th>وضعیت</th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $rows as $tx ) : ?>
						<tr>
							<td data-label="شناسه"><?php echo esc_html( Ardal_Helpers::fa_digits( $tx['id'] ) ); ?></td>
							<?php if ( $cashback ) : ?>
								<?php $state = Ardal_Wallet::cashback_state( $tx ); ?>
								<td data-label="مبلغ کش‌بگ"><?php echo Ardal_Wallet::money( $tx['abs'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
								<td data-label="استفاده‌شده"><?php echo Ardal_Wallet::money( $tx['used'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
								<td data-label="وضعیت"><span class="ard-wl-pill ard-wl-pill--<?php echo esc_attr( $state['key'] ); ?>"><?php echo esc_html( $state['label'] ); ?></span></td>
								<td data-label="تاریخ"><?php echo esc_html( Ardal_Wallet::date( $tx['created_at'] ) ); ?></td>
								<td data-label="انقضا"><?php echo esc_html( $tx['expires_at'] ? Ardal_Wallet::date( $tx['expires_at'] ) : 'بدون انقضا' ); ?></td>
							<?php else : ?>
								<?php $status = Ardal_Wallet::status_label( $tx['status'] ); ?>
								<td data-label="نوع"><?php echo esc_html( Ardal_Wallet::type_label( $tx ) ); ?></td>
								<td data-label="مبلغ" class="ard-wl-tx__amount ard-wl-tx__amount--<?php echo $tx['is_credit'] ? 'in' : 'out'; ?>"><span class="ard-wl-tx__sign" aria-hidden="true"><?php echo $tx['is_credit'] ? '+' : '−'; ?></span><?php echo Ardal_Wallet::money( $tx['abs'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
								<td data-label="تاریخ"><?php echo esc_html( Ardal_Wallet::date( $tx['created_at'], true ) ); ?></td>
								<td data-label="وضعیت"><span class="ard-wl-pill ard-wl-pill--<?php echo esc_attr( $status['key'] ); ?>"><?php echo esc_html( $status['label'] ); ?></span></td>
							<?php endif; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * صفحه‌بندی؛ لینک‌ها زیر نشانی پنل‌اند و ناوبری ایجکسی پنل هم آن‌ها را
	 * می‌گیرد.
	 *
	 * @param int    $total
	 * @param int    $per_page
	 * @param int    $page
	 * @param string $key
	 * @param string $anchor
	 */
	private static function pager( $total, $per_page, $page, $key, $anchor ) {
		$pages = (int) ceil( $total / max( 1, $per_page ) );

		if ( $pages < 2 ) {
			return;
		}
		?>
		<nav class="ard-wl-pager" aria-label="صفحه‌بندی">
			<?php for ( $i = 1; $i <= $pages; $i++ ) : ?>
				<?php if ( $pages > 7 && $i > 2 && $i < $pages - 1 && abs( $i - $page ) > 1 ) : ?>
					<?php if ( 3 === $i || $pages - 2 === $i ) : ?>
						<span class="ard-wl-pager__gap">…</span>
					<?php endif; ?>
					<?php continue; ?>
				<?php endif; ?>

				<?php if ( $i === $page ) : ?>
					<span class="ard-wl-pager__item is-active" aria-current="page"><?php echo esc_html( Ardal_Helpers::fa_digits( $i ) ); ?></span>
				<?php else : ?>
					<a class="ard-wl-pager__item" href="<?php echo esc_url( add_query_arg( $key, $i ) . '#' . $anchor ); ?>"><?php echo esc_html( Ardal_Helpers::fa_digits( $i ) ); ?></a>
				<?php endif; ?>
			<?php endfor; ?>
		</nav>
		<?php
	}

	/* --------------------------------------------------------------------- */
	/* صفحهٔ «شارژ انجام شد»                                                  */
	/* --------------------------------------------------------------------- */

	/**
	 * محتوای صفحهٔ تشکر برای سفارش شارژ.
	 *
	 * @param WC_Order $order
	 */
	public static function topup_done( $order ) {
		self::assets();

		$amount = Ardal_Wallet_Checkout::topup_amount( $order );
		$paid   = $order->is_paid() || 'yes' === $order->get_meta( Ardal_Wallet_Checkout::META_TOPUP_OK );
		?>
		<div class="ard-ck__done ard-wl-done">
			<span class="ard-ck__done-tick<?php echo $paid ? '' : ' ard-wl-done__wait'; ?>" aria-hidden="true">
				<?php echo Ardal_Account::icon( $paid ? 'check' : 'clock', 30 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</span>

			<h1 class="ard-shop__title"><?php echo esc_html( $paid ? 'کیف پولت با موفقیت شارژ شد' : 'درخواست شارژ کیف پول ثبت شد' ); ?></h1>

			<?php if ( ! $paid ) : ?>
				<p class="ard-ck__done-note">به‌محض تأیید پرداخت، اعتبار به کیف پولت اضافه می‌شود.</p>
			<?php endif; ?>

			<ul class="ard-ck__facts">
				<li>
					<span class="ard-ck__fact-key">مبلغ شارژ</span>
					<span class="ard-ck__fact-val"><?php echo Ardal_Wallet::money( $amount ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</li>
				<?php if ( $paid && get_current_user_id() === (int) $order->get_customer_id() ) : ?>
					<li>
						<span class="ard-ck__fact-key">موجودی فعلی</span>
						<span class="ard-ck__fact-val"><?php echo Ardal_Wallet::money( Ardal_Wallet::balance() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</li>
				<?php endif; ?>
				<li>
					<span class="ard-ck__fact-key">شمارهٔ پیگیری</span>
					<span class="ard-ck__fact-val"><?php echo esc_html( Ardal_Helpers::fa_digits( $order->get_order_number() ) ); ?></span>
				</li>
			</ul>

			<div class="ard-ck__done-actions">
				<?php if ( is_user_logged_in() ) : ?>
					<a class="ard-shop__btn ard-shop__btn--primary" href="<?php echo esc_url( Ardal_Wallet::account_url() ); ?>">مشاهدهٔ کیف پول</a>
				<?php endif; ?>
				<a class="ard-shop__btn ard-shop__btn--ghost" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">بازگشت به فروشگاه</a>
			</div>
		</div>
		<?php
	}

	/* --------------------------------------------------------------------- */
	/* شورت‌کدها                                                              */
	/* --------------------------------------------------------------------- */

	/**
	 * [ardal_wallet quick="50000,100000" art="no" button="شارژ"]
	 *
	 * @param array $atts
	 * @return string
	 */
	public static function shortcode_card( $atts ) {
		$atts = shortcode_atts(
			array(
				'title'       => '',
				'art'         => 'yes',
				'quick'       => '',
				'button'      => '',
				'placeholder' => '',
				'note'        => '',
				'limits'      => 'yes',
			),
			$atts,
			'ardal_wallet'
		);

		$args = array(
			'show_art'    => 'no' === $atts['art'] ? 'no' : 'yes',
			'show_limits' => 'no' === $atts['limits'] ? 'no' : 'yes',
			'quick'       => $atts['quick'],
			'note'        => $atts['note'],
		);

		foreach ( array( 'title', 'button', 'placeholder' ) as $key ) {
			if ( '' !== $atts[ $key ] ) {
				$args[ $key ] = $atts[ $key ];
			}
		}

		return self::card( $args );
	}

	/**
	 * [ardal_wallet_balance before="موجودی:" link="no"]
	 *
	 * @param array $atts
	 * @return string
	 */
	public static function shortcode_balance( $atts ) {
		return self::balance( shortcode_atts( array( 'before' => '', 'after' => '', 'link' => 'yes', 'icon' => 'yes', 'guest' => '' ), $atts, 'ardal_wallet_balance' ) );
	}

	/**
	 * [ardal_wallet_transactions style="table" per_page="20" hide_cashback="yes"]
	 *
	 * @param array $atts
	 * @return string
	 */
	public static function shortcode_transactions( $atts ) {
		$atts = shortcode_atts(
			array(
				'title'         => 'تراکنش‌های کیف پول',
				'style'         => 'list',
				'per_page'      => 10,
				'hide_cashback' => 'no',
				'empty'         => 'هنوز تراکنشی ثبت نشده است.',
			),
			$atts,
			'ardal_wallet_transactions'
		);

		$atts['mode']  = 'transactions';
		$atts['style'] = 'table' === $atts['style'] ? 'table' : 'list';

		return self::transactions( $atts );
	}

	/**
	 * [ardal_wallet_cashback style="table"]
	 *
	 * @param array $atts
	 * @return string
	 */
	public static function shortcode_cashback( $atts ) {
		$atts = shortcode_atts(
			array(
				'title'    => 'تاریخچهٔ کش‌بگ',
				'style'    => 'table',
				'per_page' => 10,
				'empty'    => 'هنوز کش‌بگی دریافت نکرده‌ای.',
			),
			$atts,
			'ardal_wallet_cashback'
		);

		$atts['mode']  = 'cashback';
		$atts['style'] = 'list' === $atts['style'] ? 'list' : 'table';

		return self::transactions( $atts );
	}
}
