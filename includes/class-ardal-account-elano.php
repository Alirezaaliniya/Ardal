<?php
/**
 * قالب «الانو» برای پنل کاربری — پیاده‌سازی دیزاین Panel Elano.
 *
 * ویجت المنتوری پنل کاربری وقتی روی قالب «الانو» باشد این کلاس را صدا می‌زند.
 * پوسته (ستون کناری با سربرگ صورتی و دکمهٔ پیشخوان در بریدگی) و صفحه‌های
 * اصلی — پیشخوان، سفارش‌ها، جزئیات سفارش، علاقه‌مندی‌ها، کیف پول و شارژ،
 * اطلاعات حساب — مطابق دیزاین ساخته شده‌اند. هر اندپوینت دیگری (آدرس‌ها،
 * نظرات، دانلودها یا صفحه‌ای که افزونهٔ دیگری اضافه کرده) داخل همان قاب با
 * خروجی معمول خودش نمایش داده می‌شود تا چیزی از پنل جا نماند.
 *
 * دادهٔ همه‌چیز واقعی است: منو از «تنظیمات اردال ← پنل کاربری»، سفارش‌ها از
 * ووکامرس، علاقه‌مندی‌ها و کیف پول از بخش‌های خود اردال.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Account_Elano {

	/**
	 * تنظیمات ویجت برای همین رندر.
	 *
	 * @var array
	 */
	private static $s = array();

	/**
	 * گروه‌های وضعیت سفارش، همان چهار تب دیزاین.
	 *
	 * @return array کلید => array( برچسب پیش‌فرض، وضعیت‌ها، آیکون ).
	 */
	public static function order_groups() {
		return array(
			'current'   => array( 'محصولات جاری', array_merge( array( 'pending', 'on-hold', 'processing' ), Ardal_Account::shipping_statuses() ), 'cart' ),
			'delivered' => array( 'محصولات تحویل شده', array( 'completed' ), 'check' ),
			'returned'  => array( 'محصولات مرجوع شده', array( 'refunded' ), 'cross' ),
			'cancelled' => array( 'محصولات لغو شده', array( 'cancelled', 'failed' ), 'cross' ),
		);
	}

	/**
	 * پیش‌فرض متن‌ها و تصویرها؛ ویجت هرکدام را می‌تواند بازنویسی کند.
	 *
	 * @return array
	 */
	public static function defaults() {
		return array(
			'dash_title'      => 'پیشخوان',
			'dash_sub'        => 'Dashboard',
			'saved_title'     => 'ذخیره شده ها',
			'saved_all'       => 'همه محصولات',
			'saved_per_page'  => 4,
			'grid_per_page'   => 12,
			'orders_per_page' => 6,
			'tx_per_page'     => 10,
			'tab_current'     => 'محصولات جاری',
			'tab_delivered'   => 'محصولات تحویل شده',
			'tab_returned'    => 'محصولات مرجوع شده',
			'tab_cancelled'   => 'محصولات لغو شده',
			'empty_orders'    => 'سبد سفارشات شما خالی است',
			'empty_saved'     => 'شما هیچ محصولی را ذخیره نکرده اید',
			'empty_wishlist'  => 'شما هیچ محصولی را به علاقه مندی خود اضافه نکرده اید',
			'shop_btn'        => 'رفتن به فروشگاه',
			'img_orders'      => '',
			'img_saved'       => '',
			'img_wishlist'    => '',
			'img_wallet'      => '',
			'show_logout'     => 'yes',
			'logout_label'    => 'خروج',
			'preview'         => '',
		);
	}

	/**
	 * @param string $key
	 * @return string
	 */
	private static function opt( $key ) {
		$value = isset( self::$s[ $key ] ) ? self::$s[ $key ] : '';

		if ( is_array( $value ) ) {
			$value = isset( $value['url'] ) ? $value['url'] : '';
		}

		$value = trim( (string) $value );

		return '' !== $value ? $value : (string) self::defaults()[ $key ];
	}

	/**
	 * تصویر حالت خالی؛ اگر ویجت تصویر نداده، همان تصویرهای دیزاین.
	 *
	 * @param string $key orders | saved | wishlist | wallet
	 * @return string
	 */
	private static function image( $key ) {
		$custom = self::opt( 'img_' . $key );
		$files  = array(
			'orders'   => 'empty-orders.webp',
			'saved'    => 'empty-saved.webp',
			'wishlist' => 'empty-wishlist.webp',
			'wallet'   => 'wallet.webp',
		);

		return '' !== $custom ? $custom : ARDAL_URL . 'assets/img/elano/' . $files[ $key ];
	}

	/**
	 * مبلغ با قالب اردال: ارقام تنظیمات و واحد «تومان» یا نماد آن.
	 *
	 * @param float $amount
	 * @param bool  $plain متن ساده (برای ردیف‌های جزئیات).
	 * @return string
	 */
	private static function money( $amount, $plain = false ) {
		$num = Ardal_Helpers::price( round( (float) $amount, wc_get_price_decimals() ) );

		return $plain
			? $num . ' تومان'
			: '<span class="ard-el__money">' . esc_html( $num ) . ' ' . Ardal_Currency::unit() . '</span>';
	}

	/* --------------------------------------------------------------------- */
	/* آیکون‌ها — همان مسیرهای دیزاین                                          */
	/* --------------------------------------------------------------------- */

	/**
	 * @param string $name
	 * @param int    $size
	 * @return string
	 */
	public static function icon( $name, $size = 20 ) {
		$paths = array(
			'chevron' => array( '0 0 24 24', 2, '<path d="M15 19.92L8.48 13.4c-.77-.77-.77-2.03 0-2.8L15 4.08"/>' ),
			'grid'    => array( '0 0 40 40', 1.6, '<circle cx="14" cy="14" r="4.2" fill="currentColor"/><circle cx="26" cy="14" r="4.2" fill="currentColor"/><circle cx="14" cy="26" r="4.2" fill="currentColor"/><circle cx="26" cy="26" r="4.2" fill="currentColor"/>' ),
			'cart'    => array( '0 0 40 40', 2, '<path d="M3.333 5h1.6c.95 0 1.77.67 1.96 1.6l3.2 15.7c.2.93 1.02 1.6 1.97 1.6h15.73c.86 0 1.62-.55 1.9-1.37l4.17-12.2c.44-1.3-.53-2.66-1.9-2.66H8"/><circle cx="12.5" cy="32.5" r="2.2"/><circle cx="27.5" cy="32.5" r="2.2"/>' ),
			'check'   => array( '0 0 40 40', 2, '<path d="M3.333 5h1.6c.95 0 1.77.67 1.96 1.6l3.2 15.7c.2.93 1.02 1.6 1.97 1.6h15.73c.86 0 1.62-.55 1.9-1.37l4.17-12.2c.44-1.3-.53-2.66-1.9-2.66H8"/><circle cx="12.5" cy="32.5" r="2.2"/><circle cx="27.5" cy="32.5" r="2.2"/><path d="M18.333 15.8l2.2 2.2 4.467-4.467"/>' ),
			'cross'   => array( '0 0 40 40', 2, '<path d="M3.333 5h1.6c.95 0 1.77.67 1.96 1.6l3.2 15.7c.2.93 1.02 1.6 1.97 1.6h15.73c.86 0 1.62-.55 1.9-1.37l4.17-12.2c.44-1.3-.53-2.66-1.9-2.66H8"/><circle cx="12.5" cy="32.5" r="2.2"/><circle cx="27.5" cy="32.5" r="2.2"/><path d="M19.167 13.333l5 5m0-5l-5 5"/>' ),
			'saved'   => array( '0 0 20 20', 1.5, '<path d="M4.167 4.167c0-.92.746-1.667 1.666-1.667h8.334c.92 0 1.666.746 1.666 1.667V17.5L10 13.75 4.167 17.5z"/>' ),
			'heart'   => array( '0 0 20 20', 1.5, '<path d="M10 17.083S1.667 12.6 1.667 7.25C1.667 4.8 3.55 2.737 5.9 2.737c1.75 0 3.25 1 4.1 2.45.85-1.45 2.35-2.45 4.1-2.45 2.35 0 4.233 2.063 4.233 4.513 0 5.35-8.333 9.833-8.333 9.833z"/>' ),
			'print'   => array( '0 0 20 20', 1.25, '<path d="M5 15H3.333a1.667 1.667 0 0 1-1.666-1.667V6.667A1.667 1.667 0 0 1 3.333 5h13.334a1.667 1.667 0 0 1 1.666 1.667v6.666A1.667 1.667 0 0 1 16.667 15H15M5 5V3.333c0-.92.746-1.666 1.667-1.666h6.666c.92 0 1.667.746 1.667 1.666V5M5 11.667h10v5c0 .92-.746 1.666-1.667 1.666H6.667c-.92 0-1.667-.746-1.667-1.666zM7.5 14.167h5"/>' ),
			'plus'    => array( '0 0 24 24', 1.5, '<circle cx="12" cy="12" r="10"/><path d="M12 8v8M8 12h8"/>' ),
			'wallet'  => array( '0 0 24 24', 1.5, '<rect x="2.5" y="5" width="19" height="15" rx="3"/><path d="M21.5 10h-4a2.5 2.5 0 0 0 0 5h4"/>' ),
			'user'    => array( '0 0 24 24', 1.5, '<circle cx="12" cy="8" r="4"/><path d="M4.5 20c1.2-3.4 4-5 7.5-5s6.3 1.6 7.5 5"/>' ),
			'coin'    => array( '0 0 24 24', 1.5, '<path d="M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20zM14.5 9.5c0-1.1-1.12-2-2.5-2s-2.5.9-2.5 2 1.12 1.75 2.5 2 2.5.9 2.5 2-1.12 2-2.5 2-2.5-.9-2.5-2M12 6v1.5M12 16.5V18"/>' ),
			'card'    => array( '0 0 24 24', 1.5, '<path d="M2 8.5h20M6 16.5h2M10.5 16.5h4M6.44 3.5h11.11C21.11 3.5 22 4.38 22 7.89v8.21c0 3.51-.89 4.39-4.44 4.39H6.44C2.89 20.5 2 19.62 2 16.11V7.89C2 4.38 2.89 3.5 6.44 3.5z"/>' ),
			'gift'    => array( '0 0 24 24', 1.5, '<path d="M20 12v9H4v-9M2 7h20v5H2zM12 21V7M12 7H7.5a2.5 2.5 0 1 1 0-5C11 2 12 7 12 7zM12 7h4.5a2.5 2.5 0 1 0 0-5C13 2 12 7 12 7z"/>' ),
			'again'   => array( '0 0 24 24', 1.6, '<path d="M3 12a9 9 0 0 1 15.5-6.2L21 8M21 3v5h-5M21 12a9 9 0 0 1-15.5 6.2L3 16M3 21v-5h5"/>' ),
			'pay'     => array( '0 0 24 24', 1.6, '<path d="M2 8.5h20M6.44 3.5h11.11C21.11 3.5 22 4.38 22 7.89v8.21c0 3.51-.89 4.39-4.44 4.39H6.44C2.89 20.5 2 19.62 2 16.11V7.89C2 4.38 2.89 3.5 6.44 3.5zM6 16.5h4"/>' ),
			'menu'    => array( '0 0 24 24', 1.8, '<path d="M4 7h16M4 12h16M4 17h10"/>' ),
			'close'   => array( '0 0 24 24', 1.8, '<path d="M6 6l12 12M18 6L6 18"/>' ),
		);

		$p = isset( $paths[ $name ] ) ? $paths[ $name ] : $paths['chevron'];

		return sprintf(
			'<svg class="ard-el__i ard-el__i--%1$s" width="%2$d" height="%2$d" viewBox="%3$s" fill="none" stroke="currentColor" stroke-width="%4$s" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%5$s</svg>',
			esc_attr( $name ),
			(int) $size,
			esc_attr( $p[0] ),
			esc_attr( $p[1] ),
			$p[2]
		);
	}

	/**
	 * صفحهٔ جاری سربرگ بخش با عنوان خودش دارد؛ آن‌وقت عنوان بالای صفحه تکراری است.
	 *
	 * @var bool
	 */
	private static $headed = false;

	/**
	 * نشان صورتی کنار عنوان هر بخش (همان شکل قطره‌ای دیزاین).
	 *
	 * @param string $icon
	 * @param string $title
	 * @param string $sub
	 * @param string $actions HTML آماده.
	 */
	private static function section_head( $icon, $title, $sub = '', $actions = '' ) {
		self::$headed = true;
		?>
		<div class="ard-el__shead">
			<div class="ard-el__shead-main">
				<span class="ard-el__badge" aria-hidden="true">
					<svg width="78" height="55" viewBox="0 0 78 55"><path d="M 49.407 0 C 44.382 0 39.65 2.36 36.627 6.374 L 19.302 29.374 C 11.361 39.917 18.883 55 32.083 55 L 62 55 C 70.837 55 78 47.837 78 39 L 78 16 C 78 7.163 70.837 0 62 0 Z"/></svg>
					<?php echo self::icon( $icon, 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
				<div class="ard-el__shead-text">
					<h2 class="ard-el__h2"><?php echo esc_html( $title ); ?></h2>
					<?php if ( '' !== $sub ) : ?>
						<span class="ard-el__shead-sub"><?php echo esc_html( $sub ); ?></span>
					<?php endif; ?>
				</div>
			</div>
			<?php echo $actions; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- سازنده‌ها escape کرده‌اند. ?>
		</div>
		<?php
	}

	/**
	 * دکمهٔ اصلی گرادیانی (پرینت، ذخیره، رفتن به درگاه).
	 *
	 * @param string $label
	 * @param string $href   خالی یعنی button.
	 * @param string $icon
	 * @param string $attrs  ویژگی‌های اضافهٔ escape‌شده.
	 * @return string
	 */
	private static function cta( $label, $href = '', $icon = 'chevron', $attrs = '' ) {
		$inner = '<span>' . esc_html( $label ) . '</span>' . self::icon( $icon, 'chevron' === $icon ? 12 : 20 );

		return '' !== $href
			? sprintf( '<a class="ard-el__cta" href="%s"%s>%s</a>', esc_url( $href ), $attrs, $inner )
			: sprintf( '<button type="button" class="ard-el__cta"%s>%s</button>', $attrs, $inner );
	}

	/* --------------------------------------------------------------------- */
	/* پوسته                                                                  */
	/* --------------------------------------------------------------------- */

	/**
	 * اندپوینت جاری و مقدارش (برای view-order شمارهٔ سفارش).
	 *
	 * @return array key، value.
	 */
	private static function route() {
		$key   = WC()->query ? (string) WC()->query->get_current_endpoint() : '';
		$value = '';

		if ( '' !== $key ) {
			$vars  = WC()->query->get_query_vars();
			$slug  = isset( $vars[ $key ] ) ? $vars[ $key ] : $key;
			$value = (string) get_query_var( $slug );
		}

		// در ویرایشگر المنتور هیچ اندپوینتی باز نیست؛ ویجت صفحهٔ دلخواه را نشان می‌دهد.
		$preview = (string) ( self::$s['preview'] ?? '' );

		if ( '' === $key && '' !== $preview && class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$key = 'dashboard' === $preview ? '' : $preview;

			if ( 'view-order' === $key ) {
				$last  = Ardal_Account::get_orders( array( 'limit' => 1, 'return' => 'ids' ) );
				$value = $last ? (string) $last[0] : '';
				$key   = $value ? 'view-order' : 'orders';
			}
		}

		return array(
			'key'   => $key,
			'value' => $value,
		);
	}

	/**
	 * کل پنل.
	 *
	 * @param array $settings تنظیمات ویجت.
	 */
	public static function render( $settings = array() ) {
		self::$s = (array) $settings;

		wp_enqueue_style( 'ardal-elano' );
		wp_enqueue_script( 'ardal-elano' );

		if ( class_exists( 'Ardal_Wallet' ) && Ardal_Wallet::is_enabled() ) {
			Ardal_Wallet_Render::assets();
		}

		if ( class_exists( 'Ardal_Wishlist' ) ) {
			wp_enqueue_style( 'ardal-wishlist' );
		}

		$route  = self::route();
		$active = 'view-order' === $route['key'] ? 'orders' : $route['key'];
		$title  = self::page_title( $route['key'] );
		?>
		<div class="ard-el" dir="rtl" data-ard-elano>
			<div class="ard-el__wrap">

				<aside class="ard-el__side">
					<div class="ard-el__hero" aria-hidden="true">
						<svg viewBox="0 0 314 171" preserveAspectRatio="none"><defs><linearGradient id="ard-el-hero-g" x1="0" y1="0" x2="1" y2="1"><stop offset="0.012" class="ard-el__g1"/><stop offset="1" class="ard-el__g2"/></linearGradient></defs><path fill="url(#ard-el-hero-g)" d="M 0 40 C 0 17.909 17.909 0 40 0 L 274 0 C 296.091 0 314 17.909 314 40 L 314 131 C 314 153.091 296.091 171 274 171 L 236.5 171 C 214.409 171 196.5 153.091 196.5 131 L 196.5 111.5 C 196.5 100.454 187.546 91.5 176.5 91.5 L 157.115 91.5 L 144 91.5 C 132.954 91.5 124 100.454 124 111.5 L 124 131 C 124 153.091 106.091 171 84 171 L 40 171 C 17.909 171 0 153.091 0 131 L 0 40 Z"/></svg>
						<span class="ard-el__hero-user">
							<?php echo get_avatar( get_current_user_id(), 44, '', '', array( 'class' => 'ard-el__hero-avatar' ) ); ?>
							<span class="ard-el__hero-name"><?php echo esc_html( Ardal_Account::name() ); ?></span>
						</span>
					</div>

					<a class="ard-el__dash<?php echo '' === $active ? ' is-active' : ''; ?>" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">
						<span class="ard-el__dash-icon"><?php echo self::icon( 'grid', 40 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span class="ard-el__dash-text">
							<span><?php echo esc_html( self::opt( 'dash_title' ) ); ?></span>
							<span><?php echo esc_html( self::opt( 'dash_sub' ) ); ?></span>
						</span>
					</a>

					<div class="ard-el__navbox">
						<svg class="ard-el__notch" viewBox="0 0 308 24" preserveAspectRatio="none" aria-hidden="true"><path d="M 105.473 0 C 114.126 0 121.14 7.014 121.14 15.667 C 121.14 19.993 124.647 23.5 128.973 23.5 L 187.855 23.5 C 192.181 23.5 195.688 19.993 195.688 15.667 C 195.688 7.014 202.702 0 211.355 0 Z"/></svg>

						<button type="button" class="ard-el__menu-toggle" aria-expanded="false" aria-controls="ard-el-nav" data-ard-el-toggle>
							<?php echo self::icon( 'menu', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span>منوی حساب کاربری</span>
						</button>

						<nav class="ard-el__nav" id="ard-el-nav" aria-label="منوی حساب کاربری" data-ard-el-nav>
							<?php // سربرگ پنل کشویی؛ فقط در موبایل دیده می‌شود. ?>
							<div class="ard-el__oc-head">
								<span class="ard-el__oc-user">
									<?php echo get_avatar( get_current_user_id(), 40, '', '', array( 'class' => 'ard-el__oc-avatar' ) ); ?>
									<span><?php echo esc_html( Ardal_Account::name() ); ?></span>
								</span>
								<button type="button" class="ard-el__oc-close" data-ard-el-close aria-label="بستن منو">
									<?php echo self::icon( 'close', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</button>
							</div>

							<a class="ard-el__item ard-el__oc-dash<?php echo '' === $active ? ' is-active' : ''; ?>" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><span><?php echo esc_html( self::opt( 'dash_title' ) ); ?></span><?php echo self::icon( 'chevron', 12 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>

							<?php self::render_menu( $active ); ?>
						</nav>
						<div class="ard-el__backdrop" data-ard-el-close aria-hidden="true"></div>
					</div>
				</aside>

				<main class="ard-el__main">
					<?php
					/*
					 * صفحه اول ساخته می‌شود تا معلوم شود سربرگ بخش دارد یا نه؛ اگر
					 * دارد، عنوان بالای صفحه حذف می‌شود تا تیتر دو بار نیاید. پیشخوان
					 * استثناست: سربرگش «ذخیره‌شده‌ها»ست، نه خود پیشخوان.
					 */
					self::$headed = false;
					ob_start();
					self::render_screen( $route );
					$screen = (string) ob_get_clean();
					?>
					<?php if ( ! self::$headed || '' === $route['key'] ) : ?>
						<div class="ard-el__titlebar">
							<h1 class="ard-el__h1"><?php echo esc_html( $title ); ?></h1>
						</div>
					<?php endif; ?>

					<div class="ard-el__notices"><?php wc_print_notices(); ?></div>

					<div class="ard-el__screen">
						<?php echo $screen; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خروجی خود صفحه‌ها. ?>
					</div>
				</main>
			</div>
		</div>
		<?php
	}

	/**
	 * عنوان بالای صفحه.
	 *
	 * @param string $key
	 * @return string
	 */
	private static function page_title( $key ) {
		if ( '' === $key ) {
			return self::opt( 'dash_title' );
		}

		// جزئیات سفارش زیرمجموعهٔ «سفارش‌ها»ست؛ عنوان خودش در سربرگ بخش است.
		if ( 'view-order' === $key ) {
			$key = 'orders';
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( 'wallet' === $key && isset( $_GET['ard_el'] ) && 'deposit' === $_GET['ard_el'] ) {
			return 'افزایش موجودی';
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( 'wallet' === $key && isset( $_GET['ard_el'] ) && 'withdraw' === $_GET['ard_el'] && Ardal_Wallet_Withdraw::enabled() ) {
			return 'برداشت';
		}

		$items = wc_get_account_menu_items();

		if ( isset( $items[ $key ] ) ) {
			return wp_strip_all_tags( $items[ $key ] );
		}

		$title = WC()->query ? WC()->query->get_endpoint_title( $key ) : '';

		return '' !== $title ? wp_strip_all_tags( $title ) : self::opt( 'dash_title' );
	}

	/**
	 * منوی کناری: همان آیتم‌ها و ترتیب تنظیمات پنل اردال.
	 *
	 * @param string $active
	 */
	private static function render_menu( $active ) {
		foreach ( wc_get_account_menu_items() as $key => $label ) {
			if ( 'dashboard' === $key || 'customer-logout' === $key ) {
				continue;
			}

			printf(
				'<a class="ard-el__item%1$s" href="%2$s"%3$s><span>%4$s</span>%5$s</a>',
				$key === $active ? ' is-active' : '',
				esc_url( wc_get_account_endpoint_url( $key ) ),
				$key === $active ? ' aria-current="page"' : '',
				esc_html( wp_strip_all_tags( $label ) ),
				self::icon( 'chevron', 12 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		if ( 'yes' === ( self::$s['show_logout'] ?? 'yes' ) ) {
			printf(
				'<a class="ard-el__item ard-el__item--logout" href="%1$s"><span>%2$s</span>%3$s</a>',
				esc_url( wc_logout_url() ),
				esc_html( self::opt( 'logout_label' ) ),
				self::icon( 'chevron', 12 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}
	}

	/**
	 * @param array $route
	 */
	private static function render_screen( $route ) {
		switch ( $route['key'] ) {
			case '':
				self::screen_dashboard();
				break;

			case 'orders':
				self::screen_orders();
				break;

			case 'view-order':
				self::screen_order( absint( $route['value'] ) );
				break;

			case 'wishlist':
				self::screen_wishlist();
				break;

			case 'wallet':
				self::screen_wallet();
				break;

			case 'edit-account':
				self::screen_profile();
				break;

			default:
				self::screen_generic( $route );
		}
	}

	/* --------------------------------------------------------------------- */
	/* پیشخوان                                                                */
	/* --------------------------------------------------------------------- */

	private static function screen_dashboard() {
		self::status_tabs( '' );

		$saved = class_exists( 'Ardal_Wishlist' ) && Ardal_Wishlist::enabled();

		if ( ! $saved ) {
			return;
		}

		$products = Ardal_Wishlist::products();
		$per      = max( 1, (int) self::opt( 'saved_per_page' ) );
		$page     = self::page_arg( 'ard_wp' );
		$pages    = (int) ceil( count( $products ) / $per );
		$page     = min( $page, max( 1, $pages ) );

		$action = sprintf(
			'<a class="ard-el__ghost" href="%s">%s</a>',
			esc_url( wc_get_account_endpoint_url( 'wishlist' ) ),
			esc_html( self::opt( 'saved_all' ) )
		);

		self::section_head( 'saved', self::opt( 'saved_title' ), '', $products ? $action : '' );

		if ( ! $products ) {
			self::empty_state( 'saved', self::opt( 'empty_saved' ) );

			return;
		}

		self::product_grid( array_slice( $products, ( $page - 1 ) * $per, $per ), 'saved' );
		self::pager( $pages, $page, 'ard_wp' );
	}

	/**
	 * چهار تب وضعیت سفارش با شکل موجی دیزاین.
	 *
	 * @param string $current
	 */
	private static function status_tabs( $current ) {
		$base = wc_get_account_endpoint_url( 'orders' );
		?>
		<div class="ard-el__tabs" role="list">
			<?php foreach ( self::order_groups() as $key => $group ) : ?>
				<?php
				$count = Ardal_Account::count_orders( $group[1] );
				$label = self::opt( 'tab_' . $key );
				?>
				<a class="ard-el__tab<?php echo $key === $current ? ' is-active' : ''; ?>" role="listitem" href="<?php echo esc_url( add_query_arg( 'ard_tab', $key, $base ) ); ?>"<?php echo $key === $current ? ' aria-current="true"' : ''; ?>>
					<svg class="ard-el__tab-bg" viewBox="0 0 202 112" aria-hidden="true"><path d="M 0 77.781 C 0 48.007 35.391 32.437 57.34 52.554 L 64.284 58.918 C 71.359 65.403 80.608 69 90.205 69 L 111.795 69 C 121.392 69 130.641 65.403 137.716 58.918 C 162.323 36.365 202 53.821 202 87.2 L 202 96.667 C 202 105.135 195.135 112 186.667 112 L 34.219 112 C 15.321 112 0 96.679 0 77.781 Z"/></svg>
					<span class="ard-el__tab-body">
						<span class="ard-el__tab-icon">
							<?php echo self::icon( $group[2], 40 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php if ( $count > 0 ) : ?>
								<span class="ard-el__tab-count"><?php echo esc_html( Ardal_Helpers::fa_digits( $count ) ); ?></span>
							<?php endif; ?>
						</span>
						<span class="ard-el__tab-label"><?php echo esc_html( $label ); ?></span>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * شبکهٔ کارت محصول (ذخیره‌شده/علاقه‌مندی).
	 *
	 * @param WC_Product[] $products
	 * @param string       $kind saved | wishlist
	 */
	private static function product_grid( $products, $kind ) {
		?>
		<div class="ard-el__grid" data-ard-wishlist-grid>
			<?php foreach ( $products as $product ) : ?>
				<?php self::product_card( $product, $kind ); ?>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * @param WC_Product $product
	 * @param string     $kind
	 */
	private static function product_card( $product, $kind ) {
		$id = (int) $product->get_id();
		?>
		<article class="ard-el__pcard" data-ard-wishlist-item="<?php echo esc_attr( $id ); ?>">
			<a class="ard-el__pcard-media" href="<?php echo esc_url( $product->get_permalink() ); ?>" tabindex="-1">
				<?php echo Ardal_Account::thumb( $product, 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
			<span class="ard-el__pcard-title"><a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></span>
			<div class="ard-el__pcard-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
			<div class="ard-el__pcard-foot">
				<?php
				if ( Ardal_Wishlist::enabled() ) {
					echo Ardal_Wishlist::button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						array(
							'product' => $id,
							'class'   => 'ard-el__mark ard-el__mark--' . $kind,
							'size'    => 20,
						)
					);
				}

				if ( $product->is_purchasable() && $product->is_in_stock() ) {
					echo Ardal_Helpers::woocommerce_add_to_cart_link( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						$product,
						array( 'ard-el__add' ),
						$product->is_type( 'simple' ) ? 'افزودن به سبد خرید' : $product->add_to_cart_text(),
						array(),
						self::icon( 'plus', 16 )
					);
				} else {
					printf( '<a class="ard-el__add ard-el__add--off" href="%s">ناموجود</a>', esc_url( $product->get_permalink() ) );
				}
				?>
			</div>
		</article>
		<?php
	}

	/* --------------------------------------------------------------------- */
	/* حالت خالی                                                              */
	/* --------------------------------------------------------------------- */

	/**
	 * @param string $image orders | saved | wishlist
	 * @param string $text
	 * @param bool   $hidden برای فهرستی که با برداشتن آخرین کارت خالی می‌شود.
	 */
	private static function empty_state( $image, $text, $hidden = false ) {
		?>
		<div class="ard-el__empty" data-ard-wishlist-empty<?php echo $hidden ? ' hidden' : ''; ?>>
			<div class="ard-el__empty-art">
				<svg viewBox="0 0 559 554" aria-hidden="true"><path class="ard-el__empty-a" d="M 0 40 C 0 17.909 17.909 0 40 0 L 519 0 C 541.091 0 559 17.909 559 40 L 559 449.684 C 559 477.22 531.826 496.522 505.826 487.453 L 26.826 320.376 C 10.762 314.773 0 299.621 0 282.607 L 0 40 Z"/><path class="ard-el__empty-b" d="M 0 382 C 0 352 28 332 56 342 L 537 515 C 566 526 560 554 530 554 L 40 554 C 17.909 554 0 536.091 0 514 Z"/></svg>
				<img class="ard-el__empty-img" src="<?php echo esc_url( self::image( $image ) ); ?>" alt="" loading="lazy" decoding="async" />
			</div>
			<div class="ard-el__empty-side">
				<p class="ard-el__empty-text"><?php echo esc_html( $text ); ?></p>
				<?php echo self::cta( self::opt( 'shop_btn' ), wc_get_page_permalink( 'shop' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
		<?php
	}

	/* --------------------------------------------------------------------- */
	/* سفارش‌ها                                                                */
	/* --------------------------------------------------------------------- */

	private static function screen_orders() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$tab    = isset( $_GET['ard_tab'] ) ? sanitize_key( wp_unslash( $_GET['ard_tab'] ) ) : '';
		$groups = self::order_groups();
		$tab    = isset( $groups[ $tab ] ) ? $tab : '';

		self::status_tabs( $tab );

		$statuses = '' !== $tab ? $groups[ $tab ][1] : array_keys( wc_get_order_statuses() );
		$per      = max( 1, (int) self::opt( 'orders_per_page' ) );
		$page     = self::page_arg( 'ard_op' );
		$result   = wc_get_orders(
			array(
				'customer_id' => get_current_user_id(),
				'status'      => $statuses,
				'limit'       => $per,
				'page'        => $page,
				'paginate'    => true,
				'orderby'     => 'date',
				'order'       => 'DESC',
				'type'        => 'shop_order',
			)
		);

		$orders = isset( $result->orders ) ? $result->orders : array();
		$total  = isset( $result->total ) ? (int) $result->total : 0;
		$title  = '' !== $tab ? str_replace( 'محصولات', 'سفارشات', self::opt( 'tab_' . $tab ) ) : 'همه سفارشات';

		// سفارش‌های شارژ کیف پول «سفارش» نیستند؛ در فهرست خرید نمی‌آیند.
		$orders = array_filter(
			$orders,
			static function ( $order ) {
				return ! ( class_exists( 'Ardal_Wallet_Checkout' ) && Ardal_Wallet_Checkout::is_topup_order( $order ) );
			}
		);

		self::section_head( 'cart', $title, sprintf( 'سفارش %s', Ardal_Helpers::fa_digits( $total ) ) );

		if ( ! $orders ) {
			self::empty_state( 'orders', self::opt( 'empty_orders' ) );

			return;
		}

		echo '<div class="ard-el__orders">';

		foreach ( $orders as $order ) {
			self::order_card( $order );
		}

		echo '</div>';

		self::pager( (int) ceil( $total / $per ), $page, 'ard_op' );
	}

	/**
	 * درصد تخفیف سفارش نسبت به جمع کالاها.
	 *
	 * @param WC_Order $order
	 * @return string
	 */
	private static function order_off( $order ) {
		$subtotal = (float) $order->get_subtotal();
		$discount = (float) $order->get_discount_total();

		if ( $subtotal <= 0 || $discount <= 0 ) {
			return '';
		}

		return Ardal_Helpers::fa_digits( (int) round( $discount / $subtotal * 100 ) ) . '٪';
	}

	/**
	 * @param WC_Order $order
	 * @return WC_Product|null
	 */
	private static function first_product( $order ) {
		foreach ( $order->get_items() as $item ) {
			$product = $item->get_product();

			if ( $product ) {
				return $product;
			}
		}

		return null;
	}

	/**
	 * @param WC_Order $order
	 */
	private static function order_card( $order ) {
		$url     = $order->get_view_order_url();
		$created = $order->get_date_created();
		$ship    = $order->get_shipping_method();
		$off     = self::order_off( $order );
		$status  = $order->get_status();
		?>
		<article class="ard-el__ocard">
			<a class="ard-el__ocard-media" href="<?php echo esc_url( $url ); ?>" tabindex="-1">
				<?php echo Ardal_Account::thumb( self::first_product( $order ), 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
			<div class="ard-el__ocard-body">
				<div class="ard-el__ocard-meta">
					<span>تاریخ ثبت : <?php echo esc_html( $created ? Ardal_Jalali::format( $created->getOffsetTimestamp(), 'with_time' ) : '—' ); ?></span>
					<span>نوع ارسال : <?php echo esc_html( '' !== $ship ? $ship : '—' ); ?></span>
					<span>شماره سفارش : <?php echo esc_html( Ardal_Helpers::fa_digits( $order->get_order_number() ) ); ?></span>
				</div>
				<span class="ard-el__ocard-title"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( Ardal_Account::order_title( $order ) ); ?></a></span>
				<div class="ard-el__ocard-foot">
					<div class="ard-el__price">
						<?php if ( '' !== $off ) : ?>
							<span class="ard-el__off"><?php echo esc_html( $off ); ?></span>
						<?php endif; ?>
						<span class="ard-el__price-num"><?php echo self::money( (float) $order->get_total() - (float) $order->get_total_refunded() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</div>
					<span class="ard-el__status ard-el__status--<?php echo esc_attr( Ardal_Account::status_tone( $status ) ); ?>"><?php echo esc_html( wc_get_order_status_name( $status ) ); ?></span>
					<a class="ard-el__square" href="<?php echo esc_url( add_query_arg( 'ard_print', '1', $url ) ); ?>" title="پرینت" aria-label="پرینت سفارش <?php echo esc_attr( $order->get_order_number() ); ?>"><?php echo self::icon( 'print', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
				</div>
			</div>
		</article>
		<?php
	}

	/**
	 * جزئیات سفارش.
	 *
	 * @param int $order_id
	 */
	private static function screen_order( $order_id ) {
		$order = $order_id ? wc_get_order( $order_id ) : null;

		if ( ! $order || ! current_user_can( 'view_order', $order_id ) ) {
			self::section_head( 'cart', 'جزییات سفارش' );
			echo '<p class="ard-el__note">این سفارش پیدا نشد یا مال حساب شما نیست.</p>';

			return;
		}

		$actions = '<div class="ard-el__head-actions">';

		if ( $order->needs_payment() ) {
			$actions .= sprintf( '<a class="ard-el__ghost" href="%s">پرداخت سفارش</a>', esc_url( $order->get_checkout_payment_url() ) );
		}

		if ( $order->has_status( array( 'completed', 'cancelled', 'refunded' ) ) ) {
			$actions .= sprintf( '<a class="ard-el__ghost" href="%s">خرید دوباره</a>', esc_url( Ardal_Account::reorder_url( $order ) ) );
		}

		$actions .= self::cta( 'پرینت', '', 'print', ' data-ard-el-print' ) . '</div>';

		self::section_head( 'cart', sprintf( 'جزییات سفارش %s', Ardal_Helpers::fa_digits( $order->get_order_number() ) ), '', $actions );

		$created  = $order->get_date_created();
		$address  = $order->get_formatted_shipping_address() ? $order->get_formatted_shipping_address() : $order->get_formatted_billing_address();
		$shipping = (float) $order->get_shipping_total() + (float) $order->get_shipping_tax();
		$rows     = array(
			'تاریخ ثبت'      => $created ? Ardal_Jalali::format( $created->getOffsetTimestamp(), 'with_time' ) : '—',
			'وضعیت'          => wc_get_order_status_name( $order->get_status() ),
			'شماره سفارش'    => Ardal_Helpers::fa_digits( $order->get_order_number() ),
			'نوع ارسال'      => '' !== $order->get_shipping_method() ? $order->get_shipping_method() : '—',
			'روش پرداخت'     => '' !== $order->get_payment_method_title() ? $order->get_payment_method_title() : '—',
			'کد رهگیری'      => Ardal_Account::tracking_code( $order ),
			'گیرنده'         => trim( $order->get_formatted_shipping_full_name() ) ? $order->get_formatted_shipping_full_name() : $order->get_formatted_billing_full_name(),
			'شماره تماس'     => Ardal_Helpers::fa_digits( $order->get_billing_phone() ),
			'هزینه ارسال'    => $shipping > 0 ? self::money( $shipping, true ) : 'رایگان',
			'تخفیف'          => (float) $order->get_discount_total() > 0 ? self::money( $order->get_discount_total(), true ) : '',
			'مبلغ کل'        => self::money( $order->get_total(), true ),
			'مبلغ مسترد شده' => (float) $order->get_total_refunded() > 0 ? self::money( $order->get_total_refunded(), true ) : '',
			'آدرس'           => wp_strip_all_tags( str_replace( array( '<br/>', '<br>', '<br />' ), '، ', (string) $address ) ),
			'توضیحات سفارش' => $order->get_customer_note(),
		);
		?>
		<article class="ard-el__detail">
			<?php foreach ( $rows as $label => $value ) : ?>
				<?php
				if ( '' === trim( (string) $value ) ) {
					continue;
				}
				?>
				<span class="ard-el__detail-row<?php echo in_array( $label, array( 'آدرس', 'توضیحات سفارش' ), true ) ? ' ard-el__detail-row--wide' : ''; ?>">
					<span class="ard-el__detail-key"><?php echo esc_html( $label ); ?> :</span>
					<span class="ard-el__detail-val"><?php echo esc_html( $value ); ?></span>
				</span>
			<?php endforeach; ?>
		</article>

		<div class="ard-el__items">
			<?php foreach ( $order->get_items() as $item ) : ?>
				<?php $product = $item->get_product(); ?>
				<article class="ard-el__item-card">
					<div class="ard-el__item-media"><?php echo Ardal_Account::thumb( $product, 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<div class="ard-el__item-body">
						<span class="ard-el__item-name">
							<?php if ( $product && $product->is_visible() ) : ?>
								<a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $item->get_name() ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $item->get_name() ); ?>
							<?php endif; ?>
						</span>
						<?php $meta = wc_display_item_meta( $item, array( 'echo' => false, 'separator' => ' · ', 'before' => '', 'after' => '' ) ); ?>
						<?php if ( '' !== trim( wp_strip_all_tags( $meta ) ) ) : ?>
							<span class="ard-el__item-meta"><?php echo wp_kses_post( $meta ); ?></span>
						<?php endif; ?>
						<span class="ard-el__item-qty"><?php echo esc_html( Ardal_Helpers::fa_digits( $item->get_quantity() ) ); ?> عدد</span>
					</div>
					<span class="ard-el__item-total"><?php echo self::money( $order->get_line_subtotal( $item, wc_prices_include_tax() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</article>
			<?php endforeach; ?>
		</div>

		<?php
		// افزونه‌هایی که زیر جدول سفارش چیزی اضافه می‌کنند (رهگیری، فاکتور) جا نمانند.
		do_action( 'woocommerce_order_details_after_order_table', $order );
	}

	/* --------------------------------------------------------------------- */
	/* علاقه‌مندی                                                              */
	/* --------------------------------------------------------------------- */

	private static function screen_wishlist() {
		if ( ! class_exists( 'Ardal_Wishlist' ) || ! Ardal_Wishlist::enabled() ) {
			echo '<p class="ard-el__note">علاقه‌مندی غیرفعال است.</p>';

			return;
		}

		$products = Ardal_Wishlist::products();
		$per      = max( 1, (int) self::opt( 'grid_per_page' ) );
		$page     = self::page_arg( 'ard_wp' );
		$pages    = (int) ceil( count( $products ) / $per );
		$page     = min( $page, max( 1, $pages ) );
		$title    = wp_strip_all_tags( (string) ( wc_get_account_menu_items()['wishlist'] ?? 'علاقمندی ها' ) );

		echo '<div class="ard-el__list" data-ard-wishlist-list>';

		self::section_head( 'heart', $title, $products ? sprintf( '%s کالا', Ardal_Helpers::fa_digits( count( $products ) ) ) : '' );

		if ( $products ) {
			self::product_grid( array_slice( $products, ( $page - 1 ) * $per, $per ), 'wishlist' );
		}

		// حالت خالی از اول در صفحه هست تا با برداشتن آخرین کارت، بی‌بارگذاری دیده شود.
		self::empty_state( 'wishlist', self::opt( 'empty_wishlist' ), (bool) $products );

		echo '</div>';

		self::pager( $pages, $page, 'ard_wp' );
	}

	/* --------------------------------------------------------------------- */
	/* کیف پول                                                                */
	/* --------------------------------------------------------------------- */

	private static function screen_wallet() {
		if ( ! class_exists( 'Ardal_Wallet' ) || ! Ardal_Wallet::is_enabled() ) {
			echo '<p class="ard-el__note">کیف پول در حال حاضر غیرفعال است.</p>';

			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['ard_el'] ) && 'deposit' === $_GET['ard_el'] && Ardal_Wallet::on( 'topup_enabled' ) ) {
			self::screen_deposit();

			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['ard_el'] ) && 'withdraw' === $_GET['ard_el'] && Ardal_Wallet_Withdraw::enabled() ) {
			self::screen_withdraw();

			return;
		}

		$user_id  = get_current_user_id();
		$balance  = Ardal_Wallet::balance( $user_id );
		$deposit  = add_query_arg( 'ard_el', 'deposit', Ardal_Wallet::account_url() );
		$withdraw = Ardal_Wallet_Withdraw::enabled() ? add_query_arg( 'ard_el', 'withdraw', Ardal_Wallet::account_url() ) : '';

		self::section_head( 'wallet', Ardal_Wallet::option( 'title' ), '', self::cta( 'پرینت', '', 'print', ' data-ard-el-print' ) );
		?>
		<section class="ard-el__balance">
			<div class="ard-el__balance-main">
				<span class="ard-el__balance-cap">موجودی کیف پول</span>
				<strong class="ard-el__balance-num" data-ard-wallet-balance><?php echo Ardal_Wallet::money( $balance ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				<?php if ( Ardal_Wallet::on( 'topup_enabled' ) || '' !== $withdraw ) : ?>
					<div class="ard-el__balance-actions">
						<?php if ( Ardal_Wallet::on( 'topup_enabled' ) ) : ?>
							<a class="ard-el__btn" href="<?php echo esc_url( $deposit ); ?>">افزایش موجودی</a>
						<?php endif; ?>
						<?php if ( '' !== $withdraw ) : ?>
							<a class="ard-el__btn ard-el__btn--line" href="<?php echo esc_url( $withdraw ); ?>">برداشت</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
			<img class="ard-el__balance-img" src="<?php echo esc_url( self::image( 'wallet' ) ); ?>" alt="" loading="lazy" decoding="async" />
		</section>

		<div class="ard-el__stats">
			<?php foreach ( self::wallet_stats( $user_id ) as $stat ) : ?>
				<div class="ard-el__stat">
					<div class="ard-el__stat-head">
						<span class="ard-el__stat-icon"><?php echo self::icon( $stat['icon'], 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span class="ard-el__stat-label"><?php echo esc_html( $stat['label'] ); ?></span>
					</div>
					<strong class="ard-el__stat-value"><?php echo Ardal_Wallet::money( $stat['value'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</div>
			<?php endforeach; ?>
		</div>

		<?php
		self::wallet_history( $user_id );
	}

	/**
	 * کارت‌های آمار: مجموع واریز، پرداخت و کش‌بگ.
	 *
	 * @param int $user_id
	 * @return array
	 */
	private static function wallet_stats( $user_id ) {
		global $wpdb;

		$table = Ardal_Wallet::table();
		$rows  = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$wpdb->prepare(
				"SELECT type, SUM( CASE WHEN amount > 0 THEN amount ELSE 0 END ) AS cr, SUM( CASE WHEN amount < 0 THEN -amount ELSE 0 END ) AS dr
				FROM {$table} WHERE user_id = %d AND status IN ('success','expired') GROUP BY type",
				$user_id
			),
			ARRAY_A
		);

		$by = array();

		foreach ( (array) $rows as $row ) {
			$by[ $row['type'] ] = $row;
		}

		$sum = static function ( $types, $col ) use ( $by ) {
			$total = 0.0;

			foreach ( $types as $type ) {
				$total += isset( $by[ $type ] ) ? (float) $by[ $type ][ $col ] : 0.0;
			}

			return $total;
		};

		$out = array(
			array(
				'label' => 'مجموع واریز',
				'icon'  => 'coin',
				'value' => $sum( array( Ardal_Wallet::TYPE_TOPUP, Ardal_Wallet::TYPE_ADMIN ), 'cr' ),
			),
			array(
				'label' => 'مجموع پرداخت',
				'icon'  => 'card',
				'value' => $sum( array( Ardal_Wallet::TYPE_ORDER ), 'dr' ),
			),
			array(
				'label' => 'برگشت وجه',
				'icon'  => 'again',
				'value' => $sum( array( Ardal_Wallet::TYPE_REFUND ), 'cr' ),
			),
		);

		if ( Ardal_Wallet::on( 'cashback' ) || isset( $by[ Ardal_Wallet::TYPE_CASHBACK ] ) ) {
			$out[] = array(
				'label' => 'مجموع کش‌بگ',
				'icon'  => 'gift',
				'value' => $sum( array( Ardal_Wallet::TYPE_CASHBACK ), 'cr' ),
			);
		}

		return $out;
	}

	/**
	 * جدول تاریخچه با بازهٔ شش‌ماهه/سه‌ماهه/ماهانه.
	 *
	 * @param int $user_id
	 */
	private static function wallet_history( $user_id ) {
		global $wpdb;

		$ranges = array(
			'180' => 'شش ماهه',
			'90'  => 'سه ماهه',
			'30'  => 'ماهانه',
		);

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$range = isset( $_GET['ard_range'] ) ? sanitize_key( wp_unslash( $_GET['ard_range'] ) ) : '180';
		$range = isset( $ranges[ $range ] ) ? $range : '180';
		$from  = gmdate( 'Y-m-d 00:00:00', current_time( 'timestamp' ) - ( (int) $range - 1 ) * DAY_IN_SECONDS ); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
		$per   = max( 1, (int) self::opt( 'tx_per_page' ) );
		$page  = self::page_arg( 'ard_tp' );
		$table = Ardal_Wallet::table();

		// phpcs:disable WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		$total = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE user_id = %d AND created_at >= %s", $user_id, $from ) );
		$rows  = $wpdb->get_results(
			$wpdb->prepare( "SELECT * FROM {$table} WHERE user_id = %d AND created_at >= %s ORDER BY id DESC LIMIT %d OFFSET %d", $user_id, $from, $per, ( $page - 1 ) * $per ),
			ARRAY_A
		);
		// phpcs:enable

		$base = remove_query_arg( array( 'ard_tp' ) );
		?>
		<section class="ard-el__history" id="ard-el-history">
			<div class="ard-el__history-head">
				<h3 class="ard-el__h3">تاریخچه تراکنش‌ها</h3>
				<div class="ard-el__seg" role="group" aria-label="بازهٔ زمانی">
					<?php foreach ( $ranges as $key => $label ) : ?>
						<a class="ard-el__seg-item<?php echo (string) $key === $range ? ' is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'ard_range', $key, $base ) . '#ard-el-history' ); ?>"><?php echo esc_html( $label ); ?></a>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( ! $rows ) : ?>
				<p class="ard-el__note">در این بازه تراکنشی ثبت نشده است.</p>
			<?php else : ?>
				<div class="ard-el__table-wrap">
					<table class="ard-el__table">
						<thead><tr><th>شرح تراکنش</th><th>تاریخ</th><th>مبلغ</th><th>نوع تراکنش</th><th>وضعیت</th></tr></thead>
						<tbody>
							<?php foreach ( $rows as $raw ) : ?>
								<?php
								$tx     = Ardal_Wallet::normalize( $raw );
								$status = Ardal_Wallet_Withdraw::tx_status( $tx );
								$status = $status ? $status : Ardal_Wallet::status_label( $tx['status'] );
								?>
								<tr>
									<td data-label="شرح تراکنش"><?php echo esc_html( '' !== $tx['note'] ? $tx['note'] : Ardal_Wallet::type_label( $tx ) ); ?></td>
									<td data-label="تاریخ"><?php echo esc_html( Ardal_Jalali::format( (int) strtotime( $tx['created_at'] . ' UTC' ), 'numeric' ) ); ?></td>
									<td data-label="مبلغ"><?php echo Ardal_Wallet::money( $tx['abs'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
									<td data-label="نوع تراکنش" class="ard-el__kind ard-el__kind--<?php echo $tx['is_credit'] ? 'in' : 'out'; ?>"><?php echo esc_html( self::tx_kind( $tx ) ); ?></td>
									<td data-label="وضعیت"><span class="ard-el__pill ard-el__pill--<?php echo esc_attr( $status['key'] ); ?>"><?php echo esc_html( $status['label'] ); ?></span></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<?php self::pager( (int) ceil( $total / $per ), $page, 'ard_tp', '#ard-el-history' ); ?>
			<?php endif; ?>
		</section>
		<?php
	}

	/**
	 * نوع تراکنش به زبان دیزاین: واریز، پرداخت، برگشت وجه.
	 *
	 * @param array $tx
	 * @return string
	 */
	private static function tx_kind( $tx ) {
		$map = array(
			Ardal_Wallet::TYPE_TOPUP    => 'واریز',
			Ardal_Wallet::TYPE_ORDER    => 'پرداخت',
			Ardal_Wallet::TYPE_REFUND   => 'برگشت وجه',
			Ardal_Wallet::TYPE_CASHBACK => 'کش‌بگ',
			Ardal_Wallet::TYPE_EXPIRE   => 'انقضای کش‌بگ',
			Ardal_Wallet::TYPE_WITHDRAW => 'برداشت',
			Ardal_Wallet::TYPE_WITHDRAW_BACK => 'بازگشت برداشت',
		);

		if ( isset( $map[ $tx['type'] ] ) ) {
			return $map[ $tx['type'] ];
		}

		return $tx['is_credit'] ? 'واریز' : 'برداشت';
	}

	/**
	 * فرم افزایش موجودی؛ همان مسیر ایجکس شارژ کیف پول اردال (ardal-wallet.js).
	 */
	private static function screen_deposit() {
		$quick = Ardal_Wallet::parse_amounts( Ardal_Wallet::option( 'quick_amounts' ) );
		$min   = (float) Ardal_Wallet::option( 'min_topup' );
		$max   = (float) Ardal_Wallet::option( 'max_topup' );
		?>
		<div class="ard-el__formbox">
			<div class="ard-el__wform" data-ard-wallet>
				<div class="ard-el__wform-balance">
					<span>موجودی کیف پول</span>
					<strong data-ard-wallet-balance><?php echo Ardal_Wallet::money( Ardal_Wallet::balance() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</div>

				<label class="ard-el__field">
					<span>مبلغ (تومان)</span>
					<input type="text" class="ard-el__input" data-ard-wallet-input inputmode="numeric" autocomplete="off" placeholder="<?php echo esc_attr( 'مثلا ' . Ardal_Helpers::price( $quick ? $quick[0] : 50000 ) . ' تومان' ); ?>" />
				</label>

				<?php if ( $quick ) : ?>
					<div class="ard-el__chips" role="group" aria-label="مبلغ‌های پیشنهادی">
						<?php foreach ( $quick as $amount ) : ?>
							<button type="button" class="ard-el__chip" data-ard-wallet-quick="<?php echo esc_attr( $amount ); ?>" aria-pressed="false"><?php echo esc_html( Ardal_Wallet::short_money( $amount ) ); ?></button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( $min > 0 || $max > 0 ) : ?>
					<p class="ard-el__hint">
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

				<?php echo self::cta( 'رفتن به درگاه', '', 'chevron', ' data-ard-wallet-submit' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

				<a class="ard-el__back" href="<?php echo esc_url( Ardal_Wallet::account_url() ); ?>">بازگشت به کیف پول</a>
			</div>
		</div>
		<?php
	}

	/**
	 * فرم برداشت (دیزاین Wallet 3): موجودی، مبلغ، شبا، دکمهٔ برداشت؛ زیرش
	 * درخواست‌های اخیر با امکان لغو درخواست در انتظار.
	 */
	private static function screen_withdraw() {
		$back = '<a class="ard-el__back" href="' . esc_url( Ardal_Wallet::account_url() ) . '">بازگشت به کیف پول</a>';
		?>
		<div class="ard-el__formbox">
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo Ardal_Wallet_Withdraw::form(
				array(
					'class'         => 'ard-el__wform',
					'field_class'   => 'ard-el__field',
					'input_class'   => 'ard-el__input',
					'balance_class' => 'ard-el__wform-balance',
					'notice_class'  => 'ard-el__note ard-el__note--box',
					'hint_class'    => 'ard-el__hint',
					'cta'           => self::cta( 'برداشت', '', 'chevron', ' data-ard-withdraw-submit' ),
					'after'         => $back,
				)
			);
			?>
		</div>
		<?php
		$list = Ardal_Wallet_Withdraw::user_requests( 'ard-el__wdl' );

		if ( '' !== $list ) {
			echo '<section class="ard-el__history"><h3 class="ard-el__h3">درخواست‌های برداشت</h3>' . $list . '</section>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/* --------------------------------------------------------------------- */
	/* اطلاعات حساب                                                          */
	/* --------------------------------------------------------------------- */

	/**
	 * فرم اطلاعات حساب؛ همان فرم ووکامرس (save_account_details) با پوستهٔ الانو.
	 */
	private static function screen_profile() {
		$user  = wp_get_current_user();
		$phone = (string) get_user_meta( $user->ID, 'billing_phone', true );

		$action = '';

		if ( class_exists( 'Ardal_Wishlist' ) && Ardal_Wishlist::enabled() ) {
			$action = sprintf(
				'<a class="ard-el__square" href="%s" title="%s" aria-label="%2$s">%s</a>',
				esc_url( wc_get_account_endpoint_url( 'wishlist' ) ),
				esc_attr( self::opt( 'saved_title' ) ),
				self::icon( 'saved', 20 )
			);
		}
		?>
		<div class="ard-el__profile-head">
			<span class="ard-el__profile-user">
				<?php echo get_avatar( $user->ID, 60, '', '', array( 'class' => 'ard-el__avatar' ) ); ?>
				<span class="ard-el__profile-name"><?php echo esc_html( Ardal_Account::name() ); ?></span>
			</span>
			<?php echo $action; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>

		<form class="ard-el__formbox ard-el__profile" method="post" action="" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?>>
			<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

			<div class="ard-el__fields">
				<?php
				self::field( 'account_first_name', 'نام', 'text', $user->first_name, 'نام خود را وارد کنید.', 'given-name', true );
				self::field( 'account_last_name', 'نام خانوادگی', 'text', $user->last_name, 'نام خانوادگی خود را وارد کنید.', 'family-name', true );
				self::field( 'ardal_billing_phone', 'شماره همراه', 'tel', $phone, 'شماره همراه خود را وارد کنید.', 'tel' );
				self::field( 'account_email', 'ایمیل', 'email', $user->user_email, 'ایمیل خود را وارد کنید.', 'email', true );
				self::field( 'password_current', 'رمز عبور فعلی', 'password', '', 'برای تغییر رمز، رمز فعلی را وارد کنید.', 'current-password' );
				self::field( 'password_1', 'رمز عبور جدید', 'password', '', 'خالی بماند رمز تغییر نمی‌کند.', 'new-password' );
				self::field( 'password_2', 'تکرار رمز عبور', 'password', '', 'رمز عبور جدید خود را تکرار کنید.', 'new-password' );

				foreach ( Ardal_Account::profile_fields() as $key => $def ) {
					$value = (string) get_user_meta( $user->ID, $key, true );

					if ( 'select' === $def['type'] ) {
						?>
						<label class="ard-el__field">
							<span><?php echo esc_html( $def['label'] ); ?></span>
							<select class="ard-el__input" name="<?php echo esc_attr( $key ); ?>">
								<?php foreach ( $def['options'] as $opt_key => $opt_label ) : ?>
									<option value="<?php echo esc_attr( $opt_key ); ?>" <?php selected( $opt_key, $value ); ?>><?php echo esc_html( $opt_label ); ?></option>
								<?php endforeach; ?>
							</select>
						</label>
						<?php
						continue;
					}

					self::field( $key, $def['label'], 'text', $value, isset( $def['placeholder'] ) ? $def['placeholder'] : '' );
				}
				?>
			</div>

			<?php do_action( 'woocommerce_edit_account_form' ); ?>

			<?php // ووکامرس نام نمایشی را اجباری می‌داند؛ همان مقدار فعلی فرستاده می‌شود. ?>
			<input type="hidden" name="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" />
			<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
			<input type="hidden" name="action" value="save_account_details" />

			<button type="submit" class="ard-el__cta" name="save_account_details" value="ذخیره"><span>ذخیره</span><?php echo self::icon( 'chevron', 12 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>

			<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
		</form>
		<?php
	}

	/**
	 * @param string $name
	 * @param string $label
	 * @param string $type
	 * @param string $value
	 * @param string $placeholder
	 * @param string $autocomplete
	 * @param bool   $required
	 */
	private static function field( $name, $label, $type, $value, $placeholder = '', $autocomplete = '', $required = false ) {
		?>
		<label class="ard-el__field">
			<span><?php echo esc_html( $label ); ?><?php echo $required ? ' <em class="ard-el__req" aria-hidden="true">*</em>' : ''; ?></span>
			<input
				class="ard-el__input"
				type="<?php echo esc_attr( $type ); ?>"
				name="<?php echo esc_attr( $name ); ?>"
				value="<?php echo esc_attr( $value ); ?>"
				placeholder="<?php echo esc_attr( $placeholder ); ?>"
				<?php echo '' !== $autocomplete ? 'autocomplete="' . esc_attr( $autocomplete ) . '"' : ''; ?>
				<?php echo 'tel' === $type ? 'inputmode="tel" dir="ltr"' : ''; ?>
				<?php echo 'email' === $type ? 'dir="ltr"' : ''; ?>
				<?php echo $required ? 'required' : ''; ?>
			/>
		</label>
		<?php
	}

	/* --------------------------------------------------------------------- */
	/* اندپوینت‌های دیگر                                                     */
	/* --------------------------------------------------------------------- */

	/**
	 * آدرس‌ها، نظرات، دانلودها و صفحه‌هایی که افزونه‌های دیگر اضافه کرده‌اند،
	 * با خروجی خودشان داخل کارت الانو.
	 *
	 * @param array $route
	 */
	private static function screen_generic( $route ) {
		$icons = array(
			'edit-address'    => 'user',
			'payment-methods' => 'card',
			'reviews'         => 'saved',
		);

		self::section_head( isset( $icons[ $route['key'] ] ) ? $icons[ $route['key'] ] : 'grid', self::page_title( $route['key'] ) );

		echo '<div class="ard-el__generic"><div class="ard-ac ard-ac--embed">';

		if ( has_action( 'woocommerce_account_' . $route['key'] . '_endpoint' ) ) {
			do_action( 'woocommerce_account_' . $route['key'] . '_endpoint', $route['value'] );
		} else {
			do_action( 'woocommerce_account_content' );
		}

		echo '</div></div>';
	}

	/* --------------------------------------------------------------------- */
	/* صفحه‌بندی                                                              */
	/* --------------------------------------------------------------------- */

	/**
	 * @param string $key
	 * @return int
	 */
	private static function page_arg( $key ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return isset( $_GET[ $key ] ) ? max( 1, absint( $_GET[ $key ] ) ) : 1;
	}

	/**
	 * صفحه‌بندی دیزاین: سه صفحهٔ اول، «...» و صفحهٔ آخر.
	 *
	 * @param int    $pages
	 * @param int    $page
	 * @param string $key
	 * @param string $anchor
	 */
	private static function pager( $pages, $page, $key, $anchor = '' ) {
		if ( $pages < 2 ) {
			return;
		}

		$show = array_unique( array_filter( array( 1, 2, 3, $page - 1, $page, $page + 1, $pages ), static function ( $n ) use ( $pages ) {
			return $n >= 1 && $n <= $pages;
		} ) );
		sort( $show );

		echo '<nav class="ard-el__pager" aria-label="صفحه‌بندی">';

		$prev = 0;

		foreach ( $show as $n ) {
			if ( $prev && $n - $prev > 1 ) {
				echo '<span class="ard-el__pager-gap">...</span>';
			}

			printf(
				'<a class="ard-el__pager-item%1$s" href="%2$s"%3$s>%4$s</a>',
				$n === $page ? ' is-active' : '',
				esc_url( add_query_arg( $key, $n ) . $anchor ),
				$n === $page ? ' aria-current="page"' : '',
				esc_html( Ardal_Helpers::fa_digits( $n ) )
			);

			$prev = $n;
		}

		echo '</nav>';
	}
}
