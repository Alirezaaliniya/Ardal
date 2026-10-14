<?php
/**
 * علاقه‌مندی‌ها: ذخیره، ایجکس، تنظیمات و شورت‌کدها.
 *
 * منبع حقیقت برای کاربرِ واردشده متای کاربر است و هر کلیک قلب همان لحظه به
 * سرور می‌رود. قبلاً دکمه‌ها فقط localStorage را عوض می‌کردند و سرور فقط
 * «ادغام» می‌کرد؛ نتیجه این بود که محصولی که کاربر از روی کارت برداشته بود،
 * در بازدید بعدی پنل دوباره برمی‌گشت.
 *
 * برای مهمان دو حالت هست (از تنظیمات):
 *   - local: فهرست در مرورگر می‌ماند و بعد از ورود یک بار با حساب ادغام می‌شود.
 *   - login: زدن قلب پیام «اول وارد شو» با لینک ورود می‌دهد.
 *
 * فهرست همیشه «تازه‌ترین اول» نگه داشته می‌شود.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Wishlist {

	const OPTION = 'ardal_wishlist';

	/**
	 * همان کلید قبلی پنل کاربری، تا فهرست کاربران فعلی از دست نرود.
	 */
	const META_KEY = '_ardal_wishlist';

	public static function init() {
		add_action( 'wp_ajax_ardal_wishlist_toggle', array( __CLASS__, 'ajax_toggle' ) );
		add_action( 'wp_ajax_nopriv_ardal_wishlist_toggle', array( __CLASS__, 'ajax_toggle' ) );
		add_action( 'wp_ajax_ardal_wishlist_merge', array( __CLASS__, 'ajax_merge' ) );

		add_shortcode( 'ardal_wishlist_button', array( __CLASS__, 'shortcode_button' ) );
		add_shortcode( 'ardal_wishlist', array( __CLASS__, 'shortcode_list' ) );
		add_shortcode( 'ardal_wishlist_count', array( __CLASS__, 'shortcode_count' ) );
	}

	/* --------------------------------------------------------------------- */
	/* تنظیمات                                                                */
	/* --------------------------------------------------------------------- */

	/**
	 * @return array
	 */
	public static function defaults() {
		return array(
			'guest_mode'     => 'local',
			'merge_on_login' => '1',
			'max'            => '200',
			'toast'          => '1',
			'text_add'       => 'افزودن به علاقه‌مندی‌ها',
			'text_remove'    => 'حذف از علاقه‌مندی‌ها',
			'msg_added'      => 'به علاقه‌مندی‌ها اضافه شد.',
			'msg_removed'    => 'از علاقه‌مندی‌ها حذف شد.',
			'msg_login'      => 'برای ذخیرهٔ علاقه‌مندی‌ها ابتدا وارد حساب شو.',
			'msg_full'       => 'فهرست علاقه‌مندی‌ها پر است؛ چند مورد قدیمی را حذف کن.',
			'login_text'     => 'ورود به حساب',
			'view_text'      => 'مشاهدهٔ فهرست',
			'hide_unstock'   => '0',
		);
	}

	/**
	 * @return array
	 */
	public static function settings() {
		$saved = get_option( self::OPTION, array() );
		$saved = is_array( $saved ) ? $saved : array();

		return array_merge( self::defaults(), array_intersect_key( $saved, self::defaults() ) );
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public static function option( $key ) {
		$all = self::settings();

		return isset( $all[ $key ] ) ? (string) $all[ $key ] : '';
	}

	/**
	 * کلید اصلی روشن/خاموش همان کلید قدیمی پالت است تا سایت‌های موجود
	 * با به‌روزرسانی تغییری نبینند.
	 *
	 * @return bool
	 */
	public static function enabled() {
		return '1' === Ardal_Palette::get( 'product_wishlist' );
	}

	/**
	 * @param mixed $raw
	 * @return array
	 */
	public static function sanitize( $raw ) {
		/*
		 * فرم بدون این تب ارسال شده (مثلاً ووکامرس غیرفعال بوده و تب رندر
		 * نشده)؛ بدون این، همهٔ کلیدها خاموش ذخیره می‌شدند.
		 */
		if ( ! is_array( $raw ) || ! $raw ) {
			return self::settings();
		}

		$defaults = self::defaults();
		$out      = array();

		foreach ( array( 'merge_on_login', 'toast', 'hide_unstock' ) as $key ) {
			$out[ $key ] = empty( $raw[ $key ] ) ? '0' : '1';
		}

		$out['guest_mode'] = isset( $raw['guest_mode'] ) && 'login' === $raw['guest_mode'] ? 'login' : 'local';
		$out['max']        = (string) max( 1, min( 1000, absint( $raw['max'] ?? $defaults['max'] ) ) );

		foreach ( array( 'text_add', 'text_remove', 'msg_added', 'msg_removed', 'msg_login', 'msg_full', 'login_text', 'view_text' ) as $key ) {
			$value       = isset( $raw[ $key ] ) ? sanitize_text_field( $raw[ $key ] ) : '';
			$out[ $key ] = '' !== $value ? $value : $defaults[ $key ];
		}

		return $out;
	}

	/* --------------------------------------------------------------------- */
	/* خواندن و نوشتن                                                         */
	/* --------------------------------------------------------------------- */

	/**
	 * @return int
	 */
	public static function max() {
		return max( 1, (int) apply_filters( 'ardal_wishlist_max', (int) self::option( 'max' ) ) );
	}

	/**
	 * شناسه‌ها، تازه‌ترین اول.
	 *
	 * @param int $user_id ۰ = کاربر جاری.
	 * @return int[]
	 */
	public static function ids( $user_id = 0 ) {
		$user_id = $user_id ? (int) $user_id : get_current_user_id();

		if ( ! $user_id ) {
			return array();
		}

		$raw = get_user_meta( $user_id, self::META_KEY, true );

		return array_values( array_unique( array_filter( array_map( 'absint', is_array( $raw ) ? $raw : array() ) ) ) );
	}

	/**
	 * @param int[] $ids
	 * @param int   $user_id
	 */
	public static function save( $ids, $user_id = 0 ) {
		$user_id = $user_id ? (int) $user_id : get_current_user_id();

		if ( ! $user_id ) {
			return;
		}

		$ids = array_values( array_unique( array_filter( array_map( 'absint', (array) $ids ) ) ) );

		update_user_meta( $user_id, self::META_KEY, array_slice( $ids, 0, self::max() ) );
	}

	/**
	 * @param int $product_id
	 * @param int $user_id
	 * @return bool
	 */
	public static function has( $product_id, $user_id = 0 ) {
		return in_array( absint( $product_id ), self::ids( $user_id ), true );
	}

	/**
	 * افزودن یا حذف.
	 *
	 * @param int    $product_id
	 * @param string $want add | remove | toggle
	 * @param int    $user_id
	 * @return string|WP_Error added | removed
	 */
	public static function set( $product_id, $want = 'toggle', $user_id = 0 ) {
		$product_id = absint( $product_id );
		$user_id    = $user_id ? (int) $user_id : get_current_user_id();
		$ids        = self::ids( $user_id );
		$index      = array_search( $product_id, $ids, true );
		$on         = false !== $index;

		if ( 'toggle' === $want ) {
			$want = $on ? 'remove' : 'add';
		}

		if ( 'remove' === $want ) {
			if ( $on ) {
				unset( $ids[ $index ] );
			}

			$state = 'removed';
		} else {
			if ( ! $on && count( $ids ) >= self::max() ) {
				return new WP_Error( 'full', self::option( 'msg_full' ) );
			}

			if ( $on ) {
				unset( $ids[ $index ] );
			}

			array_unshift( $ids, $product_id );
			$state = 'added';
		}

		self::save( $ids, $user_id );

		/**
		 * پس از تغییر فهرست علاقه‌مندی.
		 *
		 * @param int    $product_id
		 * @param string $state added | removed
		 * @param int    $user_id
		 */
		do_action( 'ardal_wishlist_changed', $product_id, $state, $user_id );

		return $state;
	}

	/**
	 * محصولات منتشرشده، به همان ترتیب.
	 *
	 * @param int $user_id
	 * @return WC_Product[]
	 */
	public static function products( $user_id = 0 ) {
		$out = array();

		foreach ( self::ids( $user_id ) as $id ) {
			$product = wc_get_product( $id );

			if ( ! $product instanceof WC_Product || 'publish' !== $product->get_status() ) {
				continue;
			}

			if ( '1' === self::option( 'hide_unstock' ) && ! $product->is_in_stock() ) {
				continue;
			}

			$out[] = $product;
		}

		return $out;
	}

	/**
	 * نشانی صفحهٔ علاقه‌مندی‌های پنل.
	 *
	 * @return string
	 */
	public static function page_url() {
		return Ardal_Helpers::has_woo() ? wc_get_endpoint_url( 'wishlist', '', wc_get_page_permalink( 'myaccount' ) ) : home_url( '/' );
	}

	/**
	 * نشانی ورود با بازگشت به همین صفحه.
	 *
	 * @return string
	 */
	public static function login_url() {
		return Ardal_Helpers::has_woo() ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
	}

	/**
	 * دادهٔ جاوااسکریپت؛ روی هندل ardal می‌نشیند که همه‌جا بار می‌شود.
	 *
	 * فهرست کاربر واردشده هم چاپ می‌شود تا قلب‌ها بدون درخواست اضافه درست
	 * رنگ بگیرند. افزونه‌های کش صفحه برای کاربر واردشده کش نمی‌سازند، پس
	 * فهرست کسی به دیگری نمی‌رسد.
	 *
	 * @return array
	 */
	public static function js_config() {
		$logged = is_user_logged_in();

		return array(
			'enabled'  => self::enabled(),
			'ajax'     => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'ardal-wishlist' ),
			'loggedIn' => $logged,
			'ids'      => $logged && self::enabled() ? array_map( 'strval', self::ids() ) : null,
			'guest'    => self::option( 'guest_mode' ),
			'merge'    => '1' === self::option( 'merge_on_login' ),
			'max'      => self::max(),
			'toast'    => '1' === self::option( 'toast' ),
			'loginUrl' => self::login_url(),
			'pageUrl'  => self::page_url(),
			'i18n'     => array(
				'add'     => self::option( 'text_add' ),
				'remove'  => self::option( 'text_remove' ),
				'added'   => self::option( 'msg_added' ),
				'removed' => self::option( 'msg_removed' ),
				'login'   => self::option( 'msg_login' ),
				'full'    => self::option( 'msg_full' ),
				'loginBtn' => self::option( 'login_text' ),
				'view'    => self::option( 'view_text' ),
				'error'   => 'ثبت علاقه‌مندی ممکن نشد. دوباره تلاش کن.',
			),
		);
	}

	/* --------------------------------------------------------------------- */
	/* ایجکس                                                                  */
	/* --------------------------------------------------------------------- */

	public static function ajax_toggle() {
		check_ajax_referer( 'ardal-wishlist', 'nonce' );

		if ( ! self::enabled() ) {
			wp_send_json_error( array( 'code' => 'off', 'message' => 'علاقه‌مندی غیرفعال است.' ), 403 );
		}

		// nopriv هم ثبت شده تا نشست منقضی به‌جای «0» پیام درست بگیرد.
		if ( ! is_user_logged_in() ) {
			wp_send_json_error(
				array(
					'code'    => 'logged_out',
					'message' => self::option( 'msg_login' ),
					'login'   => self::login_url(),
				),
				401
			);
		}

		$product_id = isset( $_POST['product'] ) ? absint( $_POST['product'] ) : 0;
		$want       = isset( $_POST['want'] ) ? sanitize_key( wp_unslash( $_POST['want'] ) ) : 'toggle';
		$want       = in_array( $want, array( 'add', 'remove', 'toggle' ), true ) ? $want : 'toggle';
		$product    = $product_id ? wc_get_product( $product_id ) : null;

		// حذف محصولی که دیگر وجود ندارد هم باید ممکن باشد.
		if ( ! $product && 'remove' !== $want ) {
			wp_send_json_error( array( 'code' => 'not_found', 'message' => 'این محصول پیدا نشد.' ), 404 );
		}

		$state = self::set( $product_id, $want );

		if ( is_wp_error( $state ) ) {
			wp_send_json_error( array( 'code' => $state->get_error_code(), 'message' => $state->get_error_message() ), 400 );
		}

		$ids = self::ids();

		wp_send_json_success(
			array(
				'state'   => $state,
				'active'  => 'added' === $state,
				'product' => $product_id,
				'ids'     => array_map( 'strval', $ids ),
				'count'   => count( $ids ),
			)
		);
	}

	/**
	 * ادغام فهرست مهمان با حساب، یک بار بعد از ورود.
	 */
	public static function ajax_merge() {
		check_ajax_referer( 'ardal-wishlist', 'nonce' );

		$incoming = isset( $_POST['ids'] ) ? array_map( 'absint', (array) wp_unslash( $_POST['ids'] ) ) : array();
		$valid    = array();

		foreach ( array_filter( $incoming ) as $id ) {
			$product = wc_get_product( $id );

			if ( $product && 'publish' === $product->get_status() ) {
				$valid[] = $id;
			}
		}

		// موارد مهمان جلو می‌آیند، چون تازه‌ترند.
		self::save( array_merge( $valid, self::ids() ) );

		$ids = self::ids();

		wp_send_json_success(
			array(
				'ids'   => array_map( 'strval', $ids ),
				'count' => count( $ids ),
			)
		);
	}

	/* --------------------------------------------------------------------- */
	/* خروجی                                                                  */
	/* --------------------------------------------------------------------- */

	/**
	 * آیکون قلب؛ پر شدنش با CSS است.
	 *
	 * @param int $size
	 * @return string
	 */
	public static function heart( $size = 22 ) {
		return sprintf(
			'<svg class="ard-wish__icon" width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12 20s-7-4.4-7-9a4 4 0 0 1 7-2.6A4 4 0 0 1 19 11c0 4.6-7 9-7 9z"></path></svg>',
			(int) $size
		);
	}

	/**
	 * دکمهٔ علاقه‌مندی؛ رفتارش با همان data-ard-wishlist سراسری است.
	 *
	 * @param array $args product، label، label_on، class، size، show_text.
	 * @return string
	 */
	public static function button( $args = array() ) {
		$a = wp_parse_args(
			$args,
			array(
				'product'   => 0,
				'label'     => '',
				'label_on'  => '',
				'class'     => '',
				'size'      => 22,
				'show_text' => false,
			)
		);

		$product = self::resolve_product( $a['product'] );

		if ( ! $product || ! self::enabled() ) {
			return '';
		}

		$off = '' !== $a['label'] ? $a['label'] : self::option( 'text_add' );
		$on  = '' !== $a['label_on'] ? $a['label_on'] : ( $a['show_text'] ? self::option( 'text_remove' ) : $off );
		$is  = is_user_logged_in() && self::has( $product->get_id() );

		$classes = trim( 'ard-wish' . ( $a['show_text'] ? ' ard-wish--text' : '' ) . ( $is ? ' is-active' : '' ) . ' ' . $a['class'] );

		return sprintf(
			'<button type="button" class="%1$s" data-ard-wishlist data-product-id="%2$d" data-label-off="%3$s" data-label-on="%4$s" aria-pressed="%5$s" aria-label="%6$s" title="%6$s">%7$s%8$s</button>',
			esc_attr( $classes ),
			(int) $product->get_id(),
			esc_attr( $off ),
			esc_attr( $on ),
			$is ? 'true' : 'false',
			esc_attr( $is ? $on : $off ),
			self::heart( (int) $a['size'] ),
			$a['show_text'] ? '<span class="ard-wish__text" data-ard-wish-text>' . esc_html( $is ? $on : $off ) . '</span>' : ''
		);
	}

	/**
	 * @param mixed $id
	 * @return WC_Product|null
	 */
	public static function resolve_product( $id ) {
		if ( ! Ardal_Helpers::has_woo() ) {
			return null;
		}

		$id = absint( $id );

		if ( ! $id ) {
			global $product;

			if ( $product instanceof WC_Product ) {
				return $product;
			}

			$post_id = get_the_ID();
			$id      = ( $post_id && 'product' === get_post_type( $post_id ) ) ? (int) $post_id : 0;
		}

		$found = $id ? wc_get_product( $id ) : null;

		return $found instanceof WC_Product ? $found : null;
	}

	/**
	 * شمارندهٔ علاقه‌مندی (برای هدر)؛ ardal.js عددش را زنده نگه می‌دارد.
	 *
	 * @param array $args link، icon، hide_zero، class.
	 * @return string
	 */
	public static function counter( $args = array() ) {
		$a = wp_parse_args(
			$args,
			array(
				'link'      => 'yes',
				'icon'      => 'yes',
				'label'     => '',
				'hide_zero' => 'yes',
				'class'     => '',
				'size'      => 22,
			)
		);

		if ( ! self::enabled() ) {
			return '';
		}

		$count = is_user_logged_in() ? count( self::ids() ) : 0;
		$tag   = 'yes' === $a['link'] ? 'a' : 'span';
		$href  = 'a' === $tag ? ' href="' . esc_url( self::page_url() ) . '"' : '';

		return sprintf(
			'<%1$s class="ard-wish-count %2$s"%3$s aria-label="%4$s">%5$s%6$s<span class="ard-wish-count__badge" data-ard-wishlist-count%7$s%8$s>%9$s</span></%1$s>',
			$tag,
			esc_attr( $a['class'] ),
			$href,
			esc_attr( '' !== $a['label'] ? $a['label'] : 'علاقه‌مندی‌ها' ),
			'yes' === $a['icon'] ? '<span class="ard-wish-count__icon">' . self::heart( (int) $a['size'] ) . '</span>' : '',
			'' !== $a['label'] ? '<span class="ard-wish-count__label">' . esc_html( $a['label'] ) . '</span>' : '',
			'yes' === $a['hide_zero'] ? ' data-hide-zero' : '',
			( 'yes' === $a['hide_zero'] && $count < 1 ) ? ' hidden' : '',
			esc_html( Ardal_Helpers::fa_digits( $count ) )
		);
	}

	/* --------------------------------------------------------------------- */
	/* شورت‌کدها                                                              */
	/* --------------------------------------------------------------------- */

	/**
	 * [ardal_wishlist_button id="12" text="yes"]
	 *
	 * @param array $atts
	 * @return string
	 */
	public static function shortcode_button( $atts ) {
		$atts = shortcode_atts(
			array(
				'id'       => 0,
				'text'     => 'no',
				'label'    => '',
				'label_on' => '',
				'size'     => 22,
			),
			$atts,
			'ardal_wishlist_button'
		);

		wp_enqueue_style( 'ardal-wishlist' );

		return self::button(
			array(
				'product'   => $atts['id'],
				'label'     => $atts['label'],
				'label_on'  => $atts['label_on'],
				'size'      => $atts['size'],
				'show_text' => 'yes' === $atts['text'],
			)
		);
	}

	/**
	 * [ardal_wishlist_count]
	 *
	 * @param array $atts
	 * @return string
	 */
	public static function shortcode_count( $atts ) {
		wp_enqueue_style( 'ardal-wishlist' );

		return self::counter( shortcode_atts( array( 'link' => 'yes', 'icon' => 'yes', 'label' => '', 'hide_zero' => 'yes' ), $atts, 'ardal_wishlist_count' ) );
	}

	/**
	 * [ardal_wishlist] — همان صفحهٔ علاقه‌مندی پنل، جای دلخواه.
	 *
	 * @return string
	 */
	public static function shortcode_list() {
		if ( ! Ardal_Helpers::has_woo() ) {
			return '';
		}

		wp_enqueue_style( 'ardal-wishlist' );
		Ardal_Account::enqueue_assets();

		ob_start();

		echo '<div class="ard-ac ard-ac--embed">';

		if ( is_user_logged_in() ) {
			wc_get_template( 'myaccount/wishlist.php' );
		} else {
			printf(
				'<div class="ard-ac__card ard-ac__empty"><span class="ard-ac__empty-icon">%1$s</span><p class="ard-ac__csub">%2$s</p><a class="ard-ac__btn ard-ac__btn--primary" href="%3$s">%4$s</a></div>',
				Ardal_Account::icon( 'fav', 22 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				esc_html( self::option( 'msg_login' ) ),
				esc_url( self::login_url() ),
				esc_html( self::option( 'login_text' ) )
			);
		}

		echo '</div>';

		return (string) ob_get_clean();
	}
}
