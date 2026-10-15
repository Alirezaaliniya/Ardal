<?php
/**
 * قالب «الانو» برای سبد خرید، تسویه حساب و صفحهٔ تشکر.
 *
 * ویجت‌های المنتور خودِ شورت‌کد ووکامرس را اجرا می‌کنند؛ این کلاس فقط تا وقتی
 * ویجت در حال رندر است تمپلیت‌های پوشهٔ woocommerce/elano را جای تمپلیت‌های
 * پیش‌فرض می‌گذارد. این‌طور کوپن، ارسال، درگاه‌ها، کیف پول و هر افزونهٔ وصل
 * به قلاب‌های ووکامرس همان‌طور کار می‌کنند و فقط ظاهر عوض می‌شود.
 *
 * درخواست‌های ایجکسی تسویه (تغییر آدرس، روش ارسال، تیک کیف پول) از صفحهٔ
 * المنتور رد نمی‌شوند؛ برای همین فرم تسویه یک فیلد مخفی ardal_skin دارد و
 * تنظیمات متنی لازم هم کنارش می‌آید تا بخش‌های تازه‌شده همان پوسته را بگیرند.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Shop_Elano {

	const SKIN = 'elano';

	/**
	 * متای زمان تحویل انتخاب‌شده روی سفارش (Y-m-d).
	 */
	const DELIVERY_META = '_ardal_delivery_date';

	/**
	 * در حال رندر ویجت؟
	 *
	 * @var bool
	 */
	private static $rendering = false;

	/**
	 * تنظیمات ویجت جاری.
	 *
	 * @var array
	 */
	private static $s = array();

	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'boot' ), 21 );
	}

	public static function boot() {
		if ( ! Ardal_Helpers::has_woo() ) {
			return;
		}

		// بعد از Ardal_Shop (اولویت ۱۰) تا در حالت الانو حرف آخر را بزند.
		add_filter( 'woocommerce_locate_template', array( __CLASS__, 'locate_template' ), 20, 2 );
		add_filter( 'woocommerce_update_order_review_fragments', array( __CLASS__, 'fragments' ), 20 );

		add_action( 'woocommerce_after_checkout_validation', array( __CLASS__, 'validate_delivery' ), 10, 2 );
		add_action( 'woocommerce_checkout_create_order', array( __CLASS__, 'save_delivery' ), 10, 2 );
		add_filter( 'woocommerce_get_order_item_totals', array( __CLASS__, 'delivery_row' ), 10, 2 );
		add_action( 'woocommerce_admin_order_data_after_shipping_address', array( __CLASS__, 'admin_delivery' ) );
	}

	/* --------------------------------------------------------------------- */
	/* تنظیمات و وضعیت                                                        */
	/* --------------------------------------------------------------------- */

	/**
	 * @return array
	 */
	public static function defaults() {
		return array(
			// سبد
			'cart_title'      => 'سبد خرید',
			'cart_sub'        => 'Cart',
			'coupon_ph'       => 'کد تخفیف',
			'coupon_btn'      => 'اعمال تخفیف',
			'pay_btn'         => 'اقدام به پرداخت',
			'back_btn'        => 'بازگشت به فروشگاه',
			'back_url'        => '',
			'sub_label'       => 'مبلغ جز',
			'off_label'       => 'تخفیف',
			'total_label'     => 'جمع کل',
			'empty_title'     => 'سبد خرید شما خالی است',
			'empty_text'      => 'هنوز کالایی انتخاب نکرده‌اید. از فروشگاه شروع کنید؛ هرچه بپسندید همین‌جا جمع می‌شود.',
			'empty_btn'       => 'رفتن به فروشگاه',
			'show_free'       => 'yes',
			'show_off_badge'  => 'yes',
			// تسویه
			'billing_title'   => 'جزییات صورت حساب',
			'ship_title'      => 'شیوه ارسال',
			'delivery'        => 'yes',
			'delivery_title'  => 'زمان تحویل',
			'delivery_days'   => 7,
			'delivery_start'  => 1,
			'delivery_skip'   => array( '5' ),
			'delivery_req'    => 'yes',
			'pay_title'       => 'درگاه پرداختی',
			'notes_title'     => 'نکاتی که قبل از پرداخت باید رعایت کنید',
			'notes_text'      => 'پیش از نهایی کردن پرداخت، اطلاعات سفارش، تعداد محصولات، مبلغ نهایی، آدرس و شماره تماس خود را با دقت بررسی کنید. پس از اطمینان از صحت اطلاعات، پرداخت را انجام دهید تا فرایند ثبت و پردازش سفارش بدون مشکل انجام شود.',
			'place_btn'       => 'اقدام به پرداخت',
			'mobile_summary'  => 'last',
			// تشکر
			'thanks_title'    => 'تشکر',
			'thanks_sub'      => 'Thank you',
			'thanks_btn'      => 'بازگشت',
			'thanks_url'      => '',
			'thanks_track'    => 'yes',
			'thanks_details'  => '',
		);
	}

	/**
	 * کلیدهایی که در درخواست‌های ایجکسی تسویه لازم‌اند (فیلد مخفی فرم).
	 *
	 * @return array
	 */
	private static function ajax_keys() {
		return array( 'pay_title', 'notes_title', 'notes_text', 'place_btn', 'sub_label', 'off_label', 'total_label', 'ship_title' );
	}

	/**
	 * شروع رندر ویجت.
	 *
	 * @param array $settings
	 */
	public static function begin( $settings = array() ) {
		self::$rendering = true;
		self::$s         = array_merge( self::defaults(), array_filter( (array) $settings, static function ( $v ) {
			return null !== $v && '' !== $v;
		} ) );
	}

	public static function end() {
		self::$rendering = false;
	}

	/**
	 * پوستهٔ الانو برای این درخواست فعال است؟
	 *
	 * @return bool
	 */
	public static function active() {
		if ( self::$rendering ) {
			return true;
		}

		$posted = self::posted();

		if ( isset( $posted['ardal_skin'] ) && self::SKIN === $posted['ardal_skin'] ) {
			if ( ! self::$s ) {
				self::$s = self::defaults();

				$opts = isset( $posted['ardal_elano'] ) ? json_decode( (string) $posted['ardal_elano'], true ) : null;

				foreach ( self::ajax_keys() as $key ) {
					if ( is_array( $opts ) && isset( $opts[ $key ] ) && is_string( $opts[ $key ] ) ) {
						self::$s[ $key ] = sanitize_textarea_field( $opts[ $key ] );
					}
				}
			}

			return true;
		}

		return false;
	}

	/**
	 * فیلدهای فرم تسویه در درخواست ایجکسی: update_order_review آن‌ها را در
	 * post_data می‌فرستد و ثبت سفارش مستقیم در $_POST.
	 *
	 * @return array
	 */
	private static function posted() {
		static $cache = null;

		if ( null !== $cache ) {
			return $cache;
		}

		$cache = array();

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- فقط تشخیص پوسته؛ ووکامرس خودش نانس را بررسی می‌کند.
		if ( ! empty( $_POST['post_data'] ) && is_string( $_POST['post_data'] ) ) {
			parse_str( wp_unslash( $_POST['post_data'] ), $cache ); // phpcs:ignore WordPress.Security.ValidationSanitization.InputNotSanitized
		} elseif ( isset( $_POST['ardal_skin'] ) ) {
			$cache = array(
				'ardal_skin'  => sanitize_key( wp_unslash( $_POST['ardal_skin'] ) ),
				'ardal_elano' => isset( $_POST['ardal_elano'] ) ? wp_unslash( $_POST['ardal_elano'] ) : '', // phpcs:ignore WordPress.Security.ValidationSanitization.InputNotSanitized
			);
		}
		// phpcs:enable

		return is_array( $cache ) ? $cache : array();
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public static function opt( $key ) {
		$all = self::$s ? self::$s : self::defaults();

		return isset( $all[ $key ] ) ? $all[ $key ] : '';
	}

	/**
	 * فیلدهای مخفی فرم تسویه.
	 *
	 * @return string
	 */
	public static function hidden_fields() {
		$opts = array();

		foreach ( self::ajax_keys() as $key ) {
			$opts[ $key ] = (string) self::opt( $key );
		}

		return '<input type="hidden" name="ardal_skin" value="' . esc_attr( self::SKIN ) . '" />'
			. '<input type="hidden" name="ardal_elano" value="' . esc_attr( wp_json_encode( $opts ) ) . '" />';
	}

	/**
	 * @param string $template
	 * @param string $name
	 * @return string
	 */
	public static function locate_template( $template, $name ) {
		if ( ! self::active() ) {
			return $template;
		}

		$file = ARDAL_PATH . 'woocommerce/elano/' . $name;

		return file_exists( $file ) ? $file : $template;
	}

	/**
	 * بلوک ارسال الانو هم با هر به‌روزرسانی تسویه تازه شود.
	 *
	 * @param array $fragments
	 * @return array
	 */
	public static function fragments( $fragments ) {
		if ( self::active() ) {
			ob_start();
			self::shipping_block();
			$fragments['.ard-elc__ship'] = ob_get_clean();
		}

		return $fragments;
	}

	/* --------------------------------------------------------------------- */
	/* ابزار نمایش                                                            */
	/* --------------------------------------------------------------------- */

	/**
	 * مبلغ با واحد پول اردال.
	 *
	 * @param float  $amount
	 * @param string $unit icon | text | none
	 * @return string
	 */
	public static function money( $amount, $unit = 'icon' ) {
		$num = '<span class="ard-elc__num">' . esc_html( Ardal_Helpers::price( round( (float) $amount, wc_get_price_decimals() ) ) ) . '</span>';

		if ( 'none' === $unit ) {
			return $num;
		}

		return $num . ' ' . ( 'text' === $unit ? '<span class="ard-elc__unit">تومان</span>' : Ardal_Currency::unit() );
	}

	/**
	 * @param string $name
	 * @param int    $size
	 * @return string
	 */
	public static function icon( $name, $size = 20 ) {
		$paths = array(
			'trash'  => array( 1.5, '<path d="M20.5 6h-17"/><path d="M18.833 8.5l-.46 6.9c-.177 2.654-.265 3.981-1.13 4.79c-.865.81-2.196.81-4.856.81h-.774c-2.66 0-3.991 0-4.856-.81c-.865-.809-.954-2.136-1.13-4.79l-.46-6.9"/><path d="M9.5 11l.5 5M14.5 11l-.5 5"/><path d="M6.5 6h.11a2 2 0 0 0 1.83-1.32l.13-.39c.08-.25.13-.37.18-.48a1.5 1.5 0 0 1 1.1-.79c.11-.02.24-.02.5-.02h3.3c.26 0 .39 0 .5.02a1.5 1.5 0 0 1 1.1.79c.05.11.1.23.18.48l.13.39A2 2 0 0 0 17.39 6h.11"/>' ),
			'plus'   => array( 1.8, '<path d="M12 6v12M6 12h12"/>' ),
			'minus'  => array( 1.8, '<path d="M6 12h12"/>' ),
			'coupon' => array( 1.5, '<path d="M14.005 4h-4.01c-3.78 0-5.67 0-6.845 1.172c-.81.806-1.061 1.951-1.14 3.817c-.015.37-.023.556.046.679c.07.123.345.278.897.586a1.999 1.999 0 0 1 0 3.492c-.552.309-.828.463-.897.586s-.061.308-.045.678c.078 1.867.33 3.012 1.139 3.818C4.324 20 6.214 20 9.995 20h4.01c3.78 0 5.67 0 6.845-1.172c.81-.806 1.061-1.951 1.14-3.817c.015-.37.023-.556-.046-.679c-.07-.123-.345-.277-.897-.586a1.999 1.999 0 0 1 0-3.492c.552-.308.828-.463.897-.586s.061-.308.045-.679c-.078-1.866-.33-3.01-1.139-3.817C19.676 4 17.786 4 14.005 4Z"/><path d="M9 15l6-6"/><circle cx="14.5" cy="14.5" r=".6" fill="currentColor"/><circle cx="9.5" cy="9.5" r=".6" fill="currentColor"/>' ),
			'cart'   => array( 1.6, '<path d="M2 3h1.8c.9 0 1.6.7 1.7 1.6l.9 9.1c.1 1 .9 1.8 1.9 1.8h9.6c.9 0 1.7-.6 1.9-1.5l1.3-5.6c.3-1.2-.6-2.4-1.9-2.4H6"/><circle cx="8.5" cy="19.5" r="1.5"/><circle cx="17.5" cy="19.5" r="1.5"/>' ),
			'check'  => array( 1.5, '<circle cx="12" cy="12" r="10"/><path d="M8.5 12.5l2 2l5-5"/>' ),
			'badge'  => array( 1.5, '<path d="M12 2.5l2.2 1.6 2.7-.1.9 2.6 2.2 1.6-.8 2.6.8 2.6-2.2 1.6-.9 2.6-2.7-.1L12 21.5l-2.2-1.6-2.7.1-.9-2.6-2.2-1.6.8-2.6-.8-2.6 2.2-1.6.9-2.6 2.7.1z"/><path d="M8.8 12.2l2.1 2.1 4.3-4.4"/>' ),
			'back'   => array( 2, '<path d="M15 19.92L8.48 13.4c-.77-.77-.77-2.03 0-2.8L15 4.08"/>' ),
			'close'  => array( 2, '<path d="M6 6l12 12M18 6L6 18"/>' ),
		);

		// سبد خرید سربرگ: همان آیکون توپر دیزاین.
		if ( 'cart-large' === $name ) {
			return '<svg class="ard-elc__i" width="' . (int) $size . '" height="' . (int) round( $size * 0.95 ) . '" viewBox="0 0 34.167 32.5" fill="none" aria-hidden="true" focusable="false"><path d="M 1.665 0.071 C 1.014 -0.158 0.3 0.184 0.071 0.836 C -0.158 1.487 0.184 2.201 0.836 2.43 L 1.271 2.583 C 2.384 2.974 3.115 3.233 3.653 3.497 C 4.158 3.745 4.381 3.946 4.528 4.161 C 4.678 4.381 4.795 4.683 4.86 5.289 C 4.929 5.922 4.93 6.748 4.93 7.981 L 4.93 12.517 C 4.93 14.938 4.953 16.683 5.182 18.017 C 5.425 19.441 5.912 20.46 6.823 21.421 C 7.813 22.465 9.067 22.919 10.561 23.131 C 11.991 23.334 13.808 23.334 16.051 23.334 L 25.06 23.334 C 26.296 23.334 27.336 23.334 28.178 23.231 C 29.071 23.121 29.885 22.883 30.596 22.303 C 31.307 21.724 31.705 20.975 31.992 20.122 C 32.263 19.319 32.473 18.3 32.723 17.089 L 33.571 12.974 L 33.572 12.966 L 33.59 12.879 C 33.864 11.504 34.095 10.348 34.153 9.418 C 34.213 8.441 34.102 7.477 33.465 6.65 C 33.074 6.141 32.523 5.853 32.022 5.677 C 31.511 5.497 30.934 5.393 30.351 5.328 C 29.206 5.2 27.815 5.2 26.45 5.2 L 7.363 5.2 C 7.358 5.139 7.352 5.079 7.346 5.02 C 7.256 4.192 7.062 3.438 6.592 2.75 C 6.118 2.057 5.489 1.613 4.754 1.253 C 4.068 0.916 3.195 0.609 2.167 0.248 L 1.665 0.071 Z M 7.43 7.7 L 26.398 7.7 C 27.826 7.7 29.082 7.702 30.073 7.813 C 30.566 7.868 30.934 7.944 31.193 8.035 C 31.405 8.11 31.477 8.171 31.487 8.179 C 31.488 8.18 31.487 8.179 31.487 8.179 C 31.589 8.315 31.701 8.561 31.657 9.264 C 31.612 10.003 31.418 10.99 31.122 12.473 L 31.121 12.477 L 30.289 16.51 C 30.021 17.815 29.84 18.682 29.623 19.324 C 29.418 19.933 29.225 20.195 29.017 20.365 C 28.808 20.535 28.512 20.671 27.874 20.749 C 27.202 20.831 26.316 20.834 24.984 20.834 L 16.146 20.834 C 13.785 20.834 12.146 20.831 10.912 20.656 C 9.721 20.487 9.091 20.18 8.637 19.701 C 8.103 19.138 7.816 18.587 7.646 17.595 C 7.46 16.513 7.43 14.999 7.43 12.517 L 7.43 7.7 Z" fill="currentColor" fill-rule="evenodd"/><path d="M 10.417 32.5 C 8.346 32.5 6.667 30.821 6.667 28.75 C 6.667 26.679 8.346 25 10.417 25 C 12.488 25 14.167 26.679 14.167 28.75 C 14.167 30.821 12.488 32.5 10.417 32.5 Z M 9.167 28.75 C 9.167 29.441 9.727 30 10.417 30 C 11.107 30 11.667 29.441 11.667 28.75 C 11.667 28.06 11.107 27.5 10.417 27.5 C 9.727 27.5 9.167 28.06 9.167 28.75 Z" fill="currentColor" fill-rule="evenodd"/><path d="M 25.417 32.5 C 23.346 32.5 21.667 30.822 21.667 28.75 C 21.667 26.679 23.346 25 25.417 25 C 27.488 25 29.167 26.679 29.167 28.75 C 29.167 30.822 27.488 32.5 25.417 32.5 Z M 24.167 28.75 C 24.167 29.441 24.727 30 25.417 30 C 26.107 30 26.667 29.441 26.667 28.75 C 26.667 28.06 26.107 27.5 25.417 27.5 C 24.727 27.5 24.167 28.06 24.167 28.75 Z" fill="currentColor" fill-rule="evenodd"/></svg>';
		}

		if ( ! isset( $paths[ $name ] ) ) {
			return '';
		}

		return sprintf(
			'<svg class="ard-elc__i" width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="%2$s" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
			(int) $size,
			esc_attr( $paths[ $name ][0] ),
			$paths[ $name ][1]
		);
	}

	/**
	 * دکمهٔ اصلی گرادیانی با فلش.
	 *
	 * @param string $label
	 * @param string $href خالی = button.
	 * @param string $attrs
	 * @param string $class
	 * @return string
	 */
	public static function cta( $label, $href = '', $attrs = '', $class = '' ) {
		$inner = '<span>' . esc_html( $label ) . '</span>' . self::icon( 'back', 12 );
		$class = trim( 'ard-elc__cta ' . $class );

		return '' !== $href
			? sprintf( '<a class="%s" href="%s"%s>%s</a>', esc_attr( $class ), esc_url( $href ), $attrs, $inner )
			: sprintf( '<button type="submit" class="%s"%s>%s</button>', esc_attr( $class ), $attrs, $inner );
	}

	/**
	 * سربرگ صورتی با آیکون و دو خط عنوان (همان شکل پنل الانو).
	 *
	 * @param string $icon
	 * @param string $title
	 * @param string $sub
	 * @param string $extra HTML اضافه روی سربرگ.
	 * @return string
	 */
	public static function hero( $icon, $title, $sub, $extra = '' ) {
		// شکل بریدگی وسط با ماسک CSS است تا در هر عرضی (ستون کناری یا تمام‌عرض موبایل) ثابت بماند.
		return '<div class="ard-elc__hero">'
			. '<span class="ard-elc__hero-bg" aria-hidden="true"></span>'
			. $extra
			. '<span class="ard-elc__badge"><span class="ard-elc__badge-icon">' . self::icon( $icon, 'cart-large' === $icon ? 34 : 30 ) . '</span>'
			. '<span class="ard-elc__badge-text"><span>' . esc_html( $title ) . '</span>' . ( '' !== $sub ? '<span>' . esc_html( $sub ) . '</span>' : '' ) . '</span></span>'
			. '</div>';
	}

	/**
	 * سربرگ هر بخش ستون اصلی (خط زیر و گوشهٔ گرد، مثل دیزاین).
	 *
	 * @param string $title
	 * @return string
	 */
	public static function section_head( $title ) {
		return '<div class="ard-elc__shead"><h2 class="ard-elc__h2">' . esc_html( $title ) . '</h2></div>';
	}

	/**
	 * نشانی «بازگشت به فروشگاه».
	 *
	 * @return string
	 */
	public static function shop_url() {
		$url = trim( (string) self::opt( 'back_url' ) );

		return '' !== $url ? $url : apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) );
	}

	/**
	 * قیمت واحد و درصد تخفیف یک ردیف سبد، با تنظیم نمایش مالیات ووکامرس.
	 *
	 * @param WC_Product $product
	 * @return array price، regular، off.
	 */
	public static function item_prices( $product ) {
		$price   = (float) wc_get_price_to_display( $product );
		$regular = (float) wc_get_price_to_display( $product, array( 'price' => $product->get_regular_price() ) );
		$regular = $regular > 0 ? $regular : $price;

		return array(
			'price'   => $price,
			'regular' => $regular,
			'off'     => $regular > $price && $regular > 0 ? (int) round( ( $regular - $price ) / $regular * 100 ) : 0,
		);
	}

	/**
	 * ردیف‌های جمع سفارش: مبلغ جز (قیمت پیش از حراج)، تخفیف (حراج + کوپن)،
	 * هزینه‌ها و جمع کل.
	 *
	 * @param bool $with_shipping
	 * @return array label، value (HTML)، kind.
	 */
	public static function summary_rows( $with_shipping = true ) {
		$cart    = WC()->cart;
		$regular = 0.0;
		$sale    = 0.0;

		foreach ( $cart->get_cart() as $item ) {
			$p = isset( $item['data'] ) ? $item['data'] : null;

			if ( ! $p || ! $p->exists() ) {
				continue;
			}

			$prices   = self::item_prices( $p );
			$regular += $prices['regular'] * $item['quantity'];
			$sale    += ( $prices['regular'] - $prices['price'] ) * $item['quantity'];
		}

		$tax_in   = $cart->display_prices_including_tax();
		$coupons  = (float) $cart->get_discount_total() + ( $tax_in ? (float) $cart->get_discount_tax() : 0 );
		$discount = max( 0, $sale ) + $coupons;
		$rows     = array();

		$rows[] = array( 'label' => self::opt( 'sub_label' ), 'value' => self::money( $regular, 'text' ), 'kind' => 'sub' );

		if ( $discount > 0 ) {
			$rows[] = array( 'label' => self::opt( 'off_label' ), 'value' => self::money( $discount, 'text' ), 'kind' => 'off' );
		}

		// در سبد فقط وقتی آدرس محاسبه شده؛ در تسویه همیشه (سازندهٔ ردیف‌ها تعیین می‌کند).
		if ( $with_shipping && $cart->needs_shipping() && $cart->show_shipping() && WC()->session && WC()->session->get( 'chosen_shipping_methods' ) ) {
			$ship   = (float) $cart->get_shipping_total() + ( $tax_in ? (float) $cart->get_shipping_tax() : 0 );
			$rows[] = array(
				'label' => 'هزینه ارسال',
				'value' => $ship > 0 ? self::money( $ship, 'text' ) : '<span class="ard-elc__free">رایگان</span>',
				'kind'  => 'ship',
			);
		}

		foreach ( $cart->get_fees() as $fee ) {
			$amount = (float) $fee->total + ( $tax_in ? (float) $fee->tax : 0 );
			$rows[] = array(
				'label' => $fee->name,
				'value' => ( $amount < 0 ? '−' : '' ) . self::money( abs( $amount ), 'text' ),
				'kind'  => $amount < 0 ? 'off' : 'fee',
			);
		}

		if ( wc_tax_enabled() && ! $tax_in ) {
			foreach ( $cart->get_tax_totals() as $tax ) {
				$rows[] = array( 'label' => $tax->label, 'value' => wp_kses_post( $tax->formatted_amount ), 'kind' => 'tax' );
			}
		}

		$rows[] = array( 'label' => self::opt( 'total_label' ), 'value' => self::money( $cart->get_total( 'edit' ), 'text' ), 'kind' => 'total' );

		return $rows;
	}

	/**
	 * @param array $rows
	 */
	public static function render_rows( $rows ) {
		foreach ( $rows as $row ) {
			printf(
				'<div class="ard-elc__row ard-elc__row--%1$s"><span class="ard-elc__row-label">%2$s</span><span class="ard-elc__row-val">%3$s</span></div>',
				esc_attr( $row['kind'] ),
				esc_html( $row['label'] ),
				$row['value'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خروجی money یا wc.
			);
		}
	}

	/* --------------------------------------------------------------------- */
	/* ارسال و زمان تحویل                                                     */
	/* --------------------------------------------------------------------- */

	/**
	 * روش‌های ارسال در ستون اصلی تسویه.
	 */
	public static function shipping_block() {
		$packages = class_exists( 'Ardal_Shop' ) ? Ardal_Shop::shipping_packages() : array();
		?>
		<div class="ard-elc__ship" data-ard-elc-ship>
			<?php if ( ! $packages ) : ?>
				<p class="ard-elc__note">برای دیدن روش‌های ارسال، آدرس را کامل کنید.</p>
			<?php else : ?>
				<?php foreach ( $packages as $index => $package ) : ?>
					<?php
					$rates  = $package['rates'];
					$chosen = isset( WC()->session->chosen_shipping_methods[ $index ] ) ? WC()->session->chosen_shipping_methods[ $index ] : '';
					?>
					<?php if ( ! $rates ) : ?>
						<p class="ard-elc__note"><?php echo wp_kses_post( apply_filters( 'woocommerce_no_shipping_available_html', 'روشی برای ارسال به این آدرس پیدا نشد.' ) ); ?></p>
					<?php else : ?>
						<div class="ard-elc__opts">
							<?php foreach ( $rates as $rate ) : ?>
								<?php
								$id = 'ard-elc-ship-' . $index . '-' . sanitize_title( $rate->get_id() );
								$on = $rate->get_id() === $chosen || 1 === count( $rates );
								$cost = (float) $rate->get_cost() + ( WC()->cart->display_prices_including_tax() ? (float) $rate->get_shipping_tax() : 0 );
								?>
								<label class="ard-elc__opt<?php echo $on ? ' is-on' : ''; ?>" for="<?php echo esc_attr( $id ); ?>" data-ard-opt>
									<input type="radio" class="shipping_method" name="shipping_method[<?php echo esc_attr( $index ); ?>]" id="<?php echo esc_attr( $id ); ?>" data-index="<?php echo esc_attr( $index ); ?>" value="<?php echo esc_attr( $rate->get_id() ); ?>" <?php checked( $on ); ?> />
									<span class="ard-elc__opt-main">
										<span class="ard-elc__dot" aria-hidden="true"><?php echo self::icon( 'check', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
										<span><?php echo esc_html( $rate->get_label() ); ?></span>
									</span>
									<span class="ard-elc__opt-side"><?php echo $cost > 0 ? self::money( $cost, 'text' ) : '<span class="ard-elc__free">رایگان</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								</label>
								<?php do_action( 'woocommerce_after_shipping_rate', $rate, $index ); ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * روزهای قابل انتخاب برای تحویل.
	 *
	 * @return array Y-m-d => array( day, date ).
	 */
	public static function delivery_days() {
		$count = max( 1, min( 30, (int) self::opt( 'delivery_days' ) ) );
		$start = max( 0, min( 30, (int) self::opt( 'delivery_start' ) ) );
		$skip  = array_map( 'strval', (array) self::opt( 'delivery_skip' ) );
		$names = array( 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه', 'شنبه' );
		$now   = Ardal_Jalali::now();
		$out   = array();

		for ( $i = $start; count( $out ) < $count && $i < $start + 60; $i++ ) {
			$ts = $now + $i * DAY_IN_SECONDS;
			$w  = (int) gmdate( 'w', $ts );

			if ( in_array( (string) $w, $skip, true ) ) {
				continue;
			}

			$out[ gmdate( 'Y-m-d', $ts ) ] = array(
				'day'  => $names[ $w ],
				'date' => Ardal_Jalali::format( $ts, 'numeric' ),
			);
		}

		return $out;
	}

	/**
	 * انتخاب زمان تحویل در تسویه.
	 */
	public static function delivery_block() {
		$days = self::delivery_days();

		if ( ! $days ) {
			return;
		}

		$first = '';
		?>
		<div class="ard-elc__days" role="radiogroup" aria-label="<?php echo esc_attr( self::opt( 'delivery_title' ) ); ?>">
			<input type="hidden" name="ardal_delivery_on" value="<?php echo 'yes' === self::opt( 'delivery_req' ) ? 'required' : '1'; ?>" />
			<?php foreach ( $days as $value => $day ) : ?>
				<?php $first = '' === $first ? $value : $first; ?>
				<label class="ard-elc__day" data-ard-opt>
					<input type="radio" name="ardal_delivery" value="<?php echo esc_attr( $value ); ?>" />
					<span class="ard-elc__day-name"><?php echo esc_html( $day['day'] ); ?></span>
					<span class="ard-elc__day-date"><?php echo esc_html( $day['date'] ); ?></span>
				</label>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * @param array    $data
	 * @param WP_Error $errors
	 */
	public static function validate_delivery( $data, $errors ) {
		// phpcs:disable WordPress.Security.NonceVerification.Missing -- ووکامرس نانس را بررسی کرده.
		if ( empty( $_POST['ardal_delivery_on'] ) ) {
			return;
		}

		$value = isset( $_POST['ardal_delivery'] ) ? sanitize_text_field( wp_unslash( $_POST['ardal_delivery'] ) ) : '';
		$req   = 'required' === sanitize_key( wp_unslash( $_POST['ardal_delivery_on'] ) );
		// phpcs:enable

		if ( '' === $value ) {
			if ( $req ) {
				$errors->add( 'ardal_delivery', 'زمان تحویل را انتخاب کنید.' );
			}

			return;
		}

		if ( ! self::valid_date( $value ) ) {
			$errors->add( 'ardal_delivery', 'زمان تحویل انتخاب‌شده معتبر نیست؛ دوباره انتخاب کنید.' );
		}
	}

	/**
	 * تاریخ Y-m-d، از امروز تا ۶۰ روز بعد.
	 *
	 * @param string $value
	 * @return bool
	 */
	private static function valid_date( $value ) {
		if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value ) ) {
			return false;
		}

		$ts    = strtotime( $value . ' 00:00:00 UTC' );
		$today = strtotime( gmdate( 'Y-m-d', Ardal_Jalali::now() ) . ' 00:00:00 UTC' );

		return $ts && $ts >= $today && $ts <= $today + 60 * DAY_IN_SECONDS;
	}

	/**
	 * @param WC_Order $order
	 * @param array    $data
	 */
	public static function save_delivery( $order, $data ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$value = isset( $_POST['ardal_delivery'] ) ? sanitize_text_field( wp_unslash( $_POST['ardal_delivery'] ) ) : '';

		if ( '' !== $value && self::valid_date( $value ) ) {
			$order->update_meta_data( self::DELIVERY_META, $value );
		}
	}

	/**
	 * زمان تحویل خوانا برای یک سفارش.
	 *
	 * @param WC_Order $order
	 * @return string
	 */
	public static function delivery_label( $order ) {
		$value = $order ? (string) $order->get_meta( self::DELIVERY_META ) : '';

		if ( '' === $value ) {
			return '';
		}

		return Ardal_Jalali::format( strtotime( $value . ' 12:00:00 UTC' ), 'weekday' );
	}

	/**
	 * ردیف «زمان تحویل» در جمع سفارش: صفحهٔ تشکر، ایمیل‌ها و جزئیات سفارش.
	 *
	 * @param array    $rows
	 * @param WC_Order $order
	 * @return array
	 */
	public static function delivery_row( $rows, $order ) {
		$label = self::delivery_label( $order );

		if ( '' !== $label ) {
			$rows['ardal_delivery'] = array(
				'label' => 'زمان تحویل:',
				'value' => esc_html( $label ),
			);
		}

		return $rows;
	}

	/**
	 * @param WC_Order $order
	 */
	public static function admin_delivery( $order ) {
		$label = self::delivery_label( $order );

		if ( '' !== $label ) {
			echo '<p><strong>زمان تحویل:</strong> ' . esc_html( $label ) . '</p>';
		}
	}

	/* --------------------------------------------------------------------- */
	/* سفارش صفحهٔ تشکر                                                        */
	/* --------------------------------------------------------------------- */

	/**
	 * سفارش صفحهٔ تشکر: از اندپوینت order-received با کلید سفارش؛ در ویرایشگر
	 * المنتور آخرین سفارش سایت برای پیش‌نمایش.
	 *
	 * @param bool $preview
	 * @return WC_Order|null
	 */
	public static function thanks_order( $preview = false ) {
		global $wp;

		$id = 0;

		if ( isset( $wp->query_vars['order-received'] ) ) {
			$id = absint( $wp->query_vars['order-received'] );
		} elseif ( isset( $_GET['order-received'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$id = absint( $_GET['order-received'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		if ( $id ) {
			$order = wc_get_order( $id );
			$key   = isset( $_GET['key'] ) ? wc_clean( wp_unslash( $_GET['key'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			// همان شرط ووکامرس: بدون کلید درست، سفارش کس دیگری نشان داده نمی‌شود.
			if ( $order && hash_equals( $order->get_order_key(), (string) $key ) ) {
				return $order;
			}

			return null;
		}

		if ( $preview ) {
			$orders = wc_get_orders( array( 'limit' => 1, 'orderby' => 'date', 'order' => 'DESC', 'type' => 'shop_order' ) );

			return $orders ? $orders[0] : null;
		}

		return null;
	}

	/**
	 * محتوای صفحهٔ تشکر (هم ویجت تشکر و هم تمپلیت thankyou.php الانو).
	 *
	 * @param WC_Order|null $order
	 * @param bool          $hooks قلاب‌های woocommerce_thankyou اجرا شوند؟
	 */
	public static function render_thanks( $order, $hooks = true ) {
		if ( ! $order ) {
			echo '<div class="ard-elc__thanks"><div class="ard-elc__card ard-elc__card--thanks"><div class="ard-elc__card-in"><p class="ard-elc__note">' . esc_html( apply_filters( 'woocommerce_thankyou_order_received_text', 'سفارش شما ثبت شد. سپاس از خرید شما!', null ) ) . '</p></div></div></div>';

			return;
		}

		if ( class_exists( 'Ardal_Wallet_Checkout' ) && Ardal_Wallet_Checkout::is_topup_order( $order ) && ! $order->has_status( 'failed' ) ) {
			Ardal_Wallet_Render::topup_done( $order );

			if ( $hooks ) {
				do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() );
				do_action( 'woocommerce_thankyou', $order->get_id() );
			}

			return;
		}

		$failed = $order->has_status( 'failed' );
		$items  = $order->get_items();
		$first  = $items ? reset( $items ) : null;
		$prod   = $first ? $first->get_product() : null;
		$image  = $prod ? wp_get_attachment_image_url( $prod->get_image_id(), 'woocommerce_single' ) : '';
		$meta   = $first ? wp_strip_all_tags( wc_display_item_meta( $first, array( 'echo' => false, 'separator' => ' · ', 'before' => '', 'after' => '', 'label_before' => '', 'label_after' => ': ' ) ) ) : '';
		$more   = count( $items ) - 1;
		$date   = $order->get_date_created();
		// «پرداخت شده» فقط وقتی واقعاً پولی رسیده؛ پرداخت در محل هم «در حال انجام» است ولی هنوز پرداخت نشده.
		$status = $failed ? 'پرداخت نشده' : ( $order->get_date_paid() ? 'پرداخت شده' : wc_get_order_status_name( $order->get_status() ) );
		$back   = trim( (string) self::opt( 'thanks_url' ) );
		$back   = '' !== $back ? $back : home_url( '/' );

		$extra  = '';

		if ( $first ) {
			$extra .= '<div class="ard-elc__th-info">'
				. '<span class="ard-elc__th-name">' . esc_html( $first->get_name() ) . '</span>'
				. ( '' !== trim( $meta ) ? '<span class="ard-elc__th-meta">' . esc_html( Ardal_Helpers::fa_digits( $meta ) ) . '</span>' : '<span class="ard-elc__th-meta">' . esc_html( Ardal_Helpers::fa_digits( $first->get_quantity() ) ) . ' عدد</span>' )
				. ( $more > 0 ? '<span class="ard-elc__th-meta">و ' . esc_html( Ardal_Helpers::fa_digits( $more ) ) . ' کالای دیگر</span>' : '' )
				. '<span class="ard-elc__th-price">' . self::money( $order->get_item_total( $first, true, true ) * $first->get_quantity(), 'text' ) . '</span>'
				. '</div>';
		}

		if ( $image ) {
			$extra .= '<span class="ard-elc__th-img"><img src="' . esc_url( $image ) . '" alt="" loading="lazy" decoding="async" /></span>';
		}

		$rows = array(
			array( 'شماره سفارش', esc_html( Ardal_Helpers::fa_digits( $order->get_order_number() ) ), '' ),
			array( 'مبلغ کل سفارش', self::money( $order->get_total(), 'none' ), 'تومان' ),
			array( 'روش پرداخت', esc_html( $order->get_payment_method_title() ? $order->get_payment_method_title() : '—' ), '' ),
			array( 'تاریخ سفارش', esc_html( $date ? Ardal_Jalali::format( $date->getTimestamp() + (int) $date->getOffset(), 'numeric' ) : '—' ), '' ),
		);

		$delivery = self::delivery_label( $order );

		if ( '' !== $delivery ) {
			$rows[] = array( 'زمان تحویل', esc_html( $delivery ), '' );
		}

		$rows[] = array( 'وضعیت', '<span class="ard-elc__status' . ( $failed ? ' is-bad' : '' ) . '">' . esc_html( $status ) . '</span>', '' );
		?>
		<div class="ard-elc__thanks<?php echo $failed ? ' is-failed' : ''; ?>">
			<?php echo self::hero( $failed ? 'close' : 'badge', $failed ? 'پرداخت ناموفق' : self::opt( 'thanks_title' ), $failed ? '' : self::opt( 'thanks_sub' ), $extra ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

			<div class="ard-elc__card ard-elc__card--thanks"><div class="ard-elc__card-in">
				<?php if ( $failed ) : ?>
					<p class="ard-elc__note ard-elc__note--bad">پرداخت این سفارش کامل نشد. می‌توانید دوباره تلاش کنید؛ کالاها هنوز برایتان رزرو هستند.</p>
				<?php endif; ?>

				<?php foreach ( $rows as $row ) : ?>
					<div class="ard-elc__row">
						<span class="ard-elc__row-label"><?php echo esc_html( $row[0] ); ?></span>
						<span class="ard-elc__row-val"><?php echo $row[1]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo '' !== $row[2] ? ' <span class="ard-elc__unit ard-elc__unit--pink">' . esc_html( $row[2] ) . '</span>' : ''; ?></span>
					</div>
				<?php endforeach; ?>

				<div class="ard-elc__th-actions">
					<?php if ( $failed ) : ?>
						<a class="ard-elc__btn" href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>">تلاش دوباره برای پرداخت</a>
					<?php else : ?>
						<a class="ard-elc__btn" href="<?php echo esc_url( $back ); ?>"><?php echo esc_html( self::opt( 'thanks_btn' ) ); ?></a>
					<?php endif; ?>

					<?php if ( 'yes' === self::opt( 'thanks_track' ) && is_user_logged_in() && (int) $order->get_customer_id() === get_current_user_id() ) : ?>
						<a class="ard-elc__link" href="<?php echo esc_url( $order->get_view_order_url() ); ?>">پیگیری سفارش در پنل کاربری</a>
					<?php endif; ?>
				</div>
			</div></div>
		</div>
		<?php
		if ( $hooks && ! $failed ) {
			echo '<div class="ard-elc__th-extra">';

			// جدول جزئیات پیش‌فرض ووکامرس فقط اگر در ویجت روشن باشد؛ ردیف‌های بالا خلاصه‌اش را دارند.
			$details = 'yes' !== self::opt( 'thanks_details' ) && remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );

			do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() );
			do_action( 'woocommerce_thankyou', $order->get_id() );

			if ( $details ) {
				add_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
			}

			echo '</div>';
		}
	}

	/**
	 * ریشهٔ مشترک ویجت‌ها: کلاس‌ها و متغیرهای رنگ.
	 *
	 * @param string $page cart | checkout | thanks
	 * @return string
	 */
	public static function root_open( $page ) {
		$class = 'ard-elc ard-elc--' . $page;

		if ( 'checkout' === $page && 'first' === self::opt( 'mobile_summary' ) ) {
			$class .= ' ard-elc--sum-first';
		}

		return '<div class="' . esc_attr( $class ) . '" dir="rtl" data-ard-shop data-ard-elc>';
	}
}
