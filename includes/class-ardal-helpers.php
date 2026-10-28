<?php
/**
 * توابع کمکی مشترک بین ویجت‌ها.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Helpers {

	/**
	 * تبدیل ارقام لاتین به فارسی و جداکننده هزارگان به «٫».
	 *
	 * @param string|int|float $value
	 * @param bool|null        $force اگر null باشد از تنظیمات پالت پیروی می‌کند.
	 * @return string
	 */
	public static function fa_digits( $value, $force = null ) {
		$value = (string) $value;

		$enabled = ( null === $force )
			? ( '1' === Ardal_Palette::get( 'persian_digits' ) )
			: (bool) $force;

		if ( ! $enabled ) {
			return $value;
		}

		$latin   = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ',' );
		$persian = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٫' );

		return str_replace( $latin, $persian, $value );
	}

	/**
	 * قالب‌بندی عدد به‌صورت قیمت با جداکننده هزارگان فارسی.
	 *
	 * @param string|int|float $number
	 * @return string
	 */
	public static function price( $number ) {
		if ( '' === $number || null === $number ) {
			return '';
		}

		// اگر کاربر خودش رشته آماده (مثلاً «۴٫۸۵۰٫۰۰۰») وارد کرده، دست نخورده برگردد.
		if ( ! is_numeric( $number ) ) {
			return self::fa_digits( $number );
		}

		return self::fa_digits( number_format( (float) $number, 0, '.', ',' ) );
	}

	/**
	 * ساخت تگ لینک با ویژگی‌های امن.
	 *
	 * @param array  $link  آرایه لینک المنتور.
	 * @param string $extra کلاس‌های اضافه.
	 * @return string ویژگی‌های آماده برای درج در تگ a.
	 */
	public static function link_attrs( $link, $extra = '' ) {
		$url = isset( $link['url'] ) ? $link['url'] : '';

		if ( '' === $url ) {
			return '';
		}

		$attrs = ' href="' . esc_url( $url ) . '"';

		if ( ! empty( $link['is_external'] ) ) {
			$attrs .= ' target="_blank"';
		}

		if ( ! empty( $link['nofollow'] ) ) {
			$attrs .= ' rel="nofollow"';
		}

		if ( '' !== $extra ) {
			$attrs .= ' class="' . esc_attr( $extra ) . '"';
		}

		return $attrs;
	}

	/**
	 * تگ مناسب برای عنصری که ممکن است لینک داشته باشد یا نداشته باشد.
	 * بدون آدرس، تگ a معنای پیوند ندارد و با صفحه‌کلید هم قابل تمرکز نیست.
	 *
	 * @param array  $link
	 * @param string $fallback
	 * @return string
	 */
	public static function link_tag( $link, $fallback = 'span' ) {
		return ! empty( $link['url'] ) ? 'a' : $fallback;
	}

	/**
	 * خروجی تصویر یا جای‌گاه خالی (placeholder) هم‌شکل دیزاین.
	 *
	 * @param array  $image       آرایه تصویر المنتور.
	 * @param string $placeholder متن جای‌گاه، مثلاً «۸۰۰×۸۰۰».
	 * @param string $alt         متن جایگزین.
	 * @param string $size        اندازه تصویر وردپرس.
	 * @return string
	 */
	public static function image( $image, $placeholder = '', $alt = '', $size = 'large' ) {
		$url = '';

		if ( ! empty( $image['id'] ) ) {
			$src = wp_get_attachment_image_src( (int) $image['id'], $size );
			$url = $src ? $src[0] : '';
		}

		if ( '' === $url && ! empty( $image['url'] ) ) {
			$url = $image['url'];
		}

		// آدرس placeholder خود المنتور را تصویر واقعی حساب نمی‌کنیم.
		if ( '' !== $url && false !== strpos( $url, 'elementor/assets/images/placeholder' ) ) {
			$url = '';
		}

		if ( '' === $url ) {
			return '<span class="ard-ph">' . esc_html( self::fa_digits( $placeholder ) ) . '</span>';
		}

		return sprintf(
			'<img src="%s" alt="%s" loading="lazy" decoding="async" />',
			esc_url( $url ),
			esc_attr( $alt )
		);
	}

	/**
	 * ستاره‌های امتیاز.
	 *
	 * @param float $rating از ۰ تا ۵.
	 * @return string
	 */
	public static function stars( $rating ) {
		$rating = max( 0, min( 5, (float) $rating ) );
		$full   = (int) floor( $rating );
		$half   = ( $rating - $full ) >= 0.5 ? 1 : 0;
		$empty  = 5 - $full - $half;

		return '<span class="ard-stars" role="img" aria-label="' . esc_attr( self::fa_digits( $rating ) . ' از ۵' ) . '">'
			. str_repeat( '★', $full )
			// ستارهٔ نیمه کلاس خودش را می‌گیرد تا CSS نیمی از آن را پر کند؛
			// قبلاً به‌شکل ستارهٔ کامل درمی‌آمد و مثلاً ۴٫۶ پنج ستارهٔ پر می‌شد.
			. ( $half ? '<span class="ard-stars__half">★</span>' : '' )
			. str_repeat( '☆', max( 0, $empty ) )
			. '</span>';
	}

	/**
	 * درصد تخفیف بین قیمت قدیم و جدید.
	 *
	 * @param string $old
	 * @param string $new
	 * @return string رشته مثل «۱۸٪» یا خالی.
	 */
	public static function discount( $old, $new ) {
		$old_n = (float) preg_replace( '/[^0-9.]/', '', self::en_digits( (string) $old ) );
		$new_n = (float) preg_replace( '/[^0-9.]/', '', self::en_digits( (string) $new ) );

		if ( $old_n <= 0 || $new_n <= 0 || $new_n >= $old_n ) {
			return '';
		}

		$percent = (int) round( ( ( $old_n - $new_n ) / $old_n ) * 100 );

		return self::fa_digits( $percent ) . '٪';
	}

	/**
	 * تبدیل ارقام فارسی/عربی به لاتین.
	 *
	 * @param string $value
	 * @return string
	 */
	public static function en_digits( $value ) {
		$fa = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٫', '،' );
		$ar = array( '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' );
		$en = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '', '' );

		$value = str_replace( $fa, $en, (string) $value );

		return str_replace( $ar, array_slice( $en, 0, 10 ), $value );
	}

	/**
	 * آیکون SVG داخلی — بدون وابستگی به کتابخانه بیرونی.
	 *
	 * @param string $name
	 * @param int    $size
	 * @return string
	 */
	public static function icon( $name, $size = 20 ) {
		$paths = array(
			'search'   => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/>',
			'user'     => '<circle cx="12" cy="8" r="3.6"/><path d="M4.5 20c.9-3.8 3.9-5.6 7.5-5.6S18.6 16.2 19.5 20"/>',
			'cart'     => '<path d="M3 4h2.2l2.3 11.2h10.2L20 7H6"/><circle cx="9.5" cy="19.5" r="1.4"/><circle cx="17" cy="19.5" r="1.4"/>',
			'heart'    => '<path d="M12 20s-7.3-4.5-7.3-9.4A4.2 4.2 0 0 1 12 8.2a4.2 4.2 0 0 1 7.3 2.4C19.3 15.5 12 20 12 20z"/>',
			'menu'     => '<path d="M4 7h16"/><path d="M4 12h16"/><path d="M4 17h10"/>',
			'grid'     => '<rect x="4" y="4" width="7" height="7" rx="1.6"/><rect x="13" y="4" width="7" height="7" rx="1.6"/><rect x="4" y="13" width="7" height="7" rx="1.6"/><rect x="13" y="13" width="7" height="7" rx="1.6"/>',
			'home'     => '<path d="M4 11l8-6.5 8 6.5"/><path d="M6.5 10v9.5h11V10"/>',
			'truck'    => '<rect x="2.5" y="7" width="11" height="9" rx="1.6"/><path d="M13.5 10.5H17l3 3V16h-6.5"/><circle cx="7" cy="18" r="1.6"/><circle cx="17" cy="18" r="1.6"/>',
			'shield'   => '<path d="M12 3.5l7 2.6v5.4c0 4.3-3 7.4-7 9-4-1.6-7-4.7-7-9V6.1z"/><path d="M9 12l2.2 2.2L15.3 10"/>',
			'refresh'  => '<path d="M20 12a8 8 0 1 1-2.6-5.9"/><path d="M20 4.5V10h-5.5"/>',
			'wallet'   => '<rect x="3.5" y="6" width="17" height="12.5" rx="2.4"/><path d="M3.5 10h17"/><circle cx="16.5" cy="14" r="1.2"/>',
			'chevron'       => '<path d="M14.5 6.5L9 12l5.5 5.5"/>',
			'chevron-left'  => '<path d="M14.5 6.5L9 12l5.5 5.5"/>',
			'chevron-right' => '<path d="M9.5 6.5L15 12l-5.5 5.5"/>',
			'chevron-up'    => '<path d="M6.5 14.5L12 9l5.5 5.5"/>',
			'chevron-down'  => '<path d="M6.5 9.5L12 15l5.5-5.5"/>',
			'play'     => '<path d="M9.5 7.5l7 4.5-7 4.5z"/>',
			'phone'    => '<path d="M6 4h3l1.6 4-2 1.4a11 11 0 0 0 5.6 5.6l1.4-2 4 1.6v3a1.6 1.6 0 0 1-1.8 1.6C11.3 18.6 5.4 12.7 4.4 5.8A1.6 1.6 0 0 1 6 4z"/>',
			'mail'     => '<rect x="3" y="5.5" width="18" height="13" rx="2.2"/><path d="M3.6 7l8.4 6 8.4-6"/>',
			'location' => '<path d="M12 21s6.5-5.6 6.5-10.2A6.5 6.5 0 0 0 5.5 10.8C5.5 15.4 12 21 12 21z"/><circle cx="12" cy="10.6" r="2.3"/>',
			'star'     => '<path d="M12 4l2.4 4.9 5.4.8-3.9 3.8.9 5.4-4.8-2.5-4.8 2.5.9-5.4L4.2 9.7l5.4-.8z"/>',
			'clock'    => '<circle cx="12" cy="12" r="8"/><path d="M12 7.5V12l3 1.8"/>',
			'calendar' => '<rect x="3.5" y="5" width="17" height="15.5" rx="2.6"/><path d="M3.5 9.6h17"/><path d="M8 3.5v3M16 3.5v3"/>',
			'tag'      => '<path d="M4 11.5V4.5h7L20 13.5 13 20.5z"/><circle cx="8" cy="8" r="1.3"/>',
			'plus'     => '<path d="M12 6v12"/><path d="M6 12h12"/>',
			'bike'     => '<circle cx="6" cy="16.5" r="3.5"/><circle cx="18" cy="16.5" r="3.5"/><path d="M6 16.5l4.5-8H15l3 8"/><path d="M9 8.5h4"/>',
			'sparkles' => '<path d="M15 3l1.3 3.2L19.5 7.5l-3.2 1.3L15 12l-1.3-3.2L10.5 7.5l3.2-1.3z"/><path d="M7.5 11l1 2.5 2.5 1-2.5 1-1 2.5-1-2.5-2.5-1 2.5-1z"/><path d="M16.5 15.5l.6 1.4 1.4.6-1.4.6-.6 1.4-.6-1.4-1.4-.6 1.4-.6z"/>',
			'hair'     => '<path d="M12 7.5l1.3 2.7 2.9.4-2.1 2 .5 2.9L12 14.1l-2.6 1.4.5-2.9-2.1-2 2.9-.4z"/><path d="M12 2.5v1.8M12 19.7v1.8M2.5 12h1.8M19.7 12h1.8M5.3 5.3l1.3 1.3M17.4 17.4l1.3 1.3M5.3 18.7l1.3-1.3M17.4 6.6l1.3-1.3"/>',
			'bath'     => '<path d="M3.5 12.5h17v2a5 5 0 0 1-5 5h-7a5 5 0 0 1-5-5z"/><path d="M6 12.5V6a2.5 2.5 0 0 1 4.8-1"/><path d="M10.8 5l2 1.2"/><path d="M7 19.5l-1 1.5M17 19.5l1 1.5"/>',
			'perfume'  => '<path d="M9 8.5h6"/><path d="M10 8.5V6h4v2.5"/><path d="M12 6V4h3"/><circle cx="18" cy="4" r="1.2"/><path d="M7.5 11a4.5 4.5 0 0 1 4.5-2.5A4.5 4.5 0 0 1 16.5 11v7a2 2 0 0 1-2 2h-5a2 2 0 0 1-2-2z"/>',
			'jar'      => '<rect x="8" y="3" width="8" height="3" rx="1"/><path d="M7 6h10l1.5 3v9.5a2 2 0 0 1-2 2h-9a2 2 0 0 1-2-2V9z"/><path d="M5.5 10.5h13M5.5 17h13"/><path d="M12 12.2v3.2M10.4 13.8h3.2"/>',
		);

		if ( ! isset( $paths[ $name ] ) ) {
			$name = 'grid';
		}

		return sprintf(
			'<svg class="ard-i ard-i--%1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
			esc_attr( $name ),
			(int) $size,
			$paths[ $name ]
		);
	}

	/**
	 * فهرست آیکون‌های موجود برای کنترل SELECT در المنتور.
	 *
	 * @return array
	 */
	public static function icon_options() {
		return array(
			'truck'    => 'کامیون (ارسال)',
			'shield'   => 'سپر (ضمانت)',
			'refresh'  => 'بازگشت',
			'wallet'   => 'کیف پول (پرداخت)',
			'clock'    => 'ساعت',
			'calendar' => 'تقویم',
			'tag'      => 'برچسب تخفیف',
			'star'     => 'ستاره',
			'heart'    => 'قلب',
			'cart'     => 'سبد خرید',
			'user'     => 'کاربر',
			'phone'    => 'تلفن',
			'mail'     => 'ایمیل',
			'location' => 'موقعیت مکانی',
			'bike'     => 'دوچرخه',
			'grid'     => 'شبکه',
			'home'     => 'خانه',
			'search'   => 'جستجو',
			'sparkles' => 'درخشش (آرایشی)',
			'hair'     => 'ستارهٔ پرتو (مو)',
			'bath'     => 'وان (بهداشت و حمام)',
			'perfume'  => 'شیشهٔ عطر',
			'jar'      => 'قوطی (مکمل، کرم)',
		);
	}

	/**
	 * آیا ووکامرس فعال است؟
	 *
	 * @return bool
	 */
	public static function has_woo() {
		return class_exists( 'WooCommerce' );
	}

	/**
	 * Render a WooCommerce loop add-to-cart link through WooCommerce's public API.
	 *
	 * @param WC_Product $product Product instance.
	 * @param array      $classes Widget-specific classes.
	 * @param string     $text Link text.
	 * @param array      $attributes Widget-specific attributes.
	 * @param string     $icon نشانِ SVG که پیش از متن می‌نشیند؛ از قبل امن شده.
	 * @return string
	 */
	public static function woocommerce_add_to_cart_link( $product, $classes = array(), $text = '', $attributes = array(), $icon = '' ) {
		if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
			return '';
		}

		$button_classes = array_merge( (array) $classes, array( 'button', 'product_type_' . $product->get_type() ) );

		if ( $product->is_purchasable() && $product->is_in_stock() ) {
			$button_classes[] = 'add_to_cart_button';
		}

		if ( $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() && ! Ardal_Assets::is_woocommerce_asset_disabled( 'disable_wc_add_to_cart_js' ) ) {
			$button_classes[] = 'ajax_add_to_cart';
			wp_enqueue_script( 'wc-add-to-cart' );
		}

		$args = apply_filters(
			'woocommerce_loop_add_to_cart_args',
			array(
				'quantity'   => 1,
				'class'      => implode( ' ', array_unique( array_filter( $button_classes ) ) ),
				'attributes' => array_merge(
					array(
						'data-product_id'  => $product->get_id(),
						'data-product_sku' => $product->get_sku(),
						'data-quantity'    => 1,
						'aria-label'       => $product->add_to_cart_description(),
						'rel'              => 'nofollow',
					),
					(array) $attributes
				),
			),
			$product
		);

		// WooCommerce and extensions use the filtered quantity as the source of truth.
		if ( ! isset( $args['attributes']['data-quantity'] ) ) {
			$args['attributes']['data-quantity'] = isset( $args['quantity'] ) ? $args['quantity'] : 1;
		}

		/*
		 * آیکون به‌صورت عنصر واقعی داخل دکمه می‌رود، نه با ::before در CSS.
		 *
		 * متنِ دکمه دست‌نخورده می‌ماند (textContent یک <svg> خالی است)، پس
		 * افزونه‌هایی که روی دکمهٔ استاندارد لوپ ووکامرس حساب باز کرده‌اند
		 * همان چیزی را می‌خوانند که قبلاً می‌خواندند.
		 */
		$link = sprintf(
			'<a href="%1$s" %2$s class="%3$s">%4$s%5$s</a>',
			esc_url( $product->add_to_cart_url() ),
			wc_implode_html_attributes( isset( $args['attributes'] ) ? $args['attributes'] : array() ),
			esc_attr( isset( $args['class'] ) ? $args['class'] : '' ),
			self::svg( $icon ),
			esc_html( '' !== $text ? $text : $product->add_to_cart_text() )
		);

		return apply_filters( 'woocommerce_loop_add_to_cart_link', $link, $product, $args );
	}

	/**
	 * پاک‌سازی یک قطعه SVG برای چاپ امن.
	 *
	 * فهرست مجازِ wp_kses تگ‌های SVG را نمی‌شناسد و همه را دور می‌ریزد؛ پس
	 * همین‌جا یک فهرست کوچک و بسته تعریف می‌شود. هرچه در آن نباشد حذف است.
	 *
	 * @param string $svg
	 * @return string
	 */
	public static function svg( $svg ) {
		if ( '' === trim( (string) $svg ) ) {
			return '';
		}

		$common = array(
			'class' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true,
			'stroke-linecap' => true, 'stroke-linejoin' => true, 'opacity' => true,
			'transform' => true, 'aria-hidden' => true, 'focusable' => true,
		);

		return wp_kses(
			$svg,
			array(
				'svg'      => array_merge( $common, array( 'viewbox' => true, 'width' => true, 'height' => true, 'xmlns' => true, 'role' => true ) ),
				'g'        => $common,
				'path'     => array_merge( $common, array( 'd' => true ) ),
				'circle'   => array_merge( $common, array( 'cx' => true, 'cy' => true, 'r' => true ) ),
				'rect'     => array_merge( $common, array( 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'ry' => true ) ),
				'line'     => array_merge( $common, array( 'x1' => true, 'y1' => true, 'x2' => true, 'y2' => true ) ),
				'polyline' => array_merge( $common, array( 'points' => true ) ),
				'polygon'  => array_merge( $common, array( 'points' => true ) ),
				'i'        => array( 'class' => true, 'aria-hidden' => true ),
				'span'     => array( 'class' => true, 'aria-hidden' => true ),
			)
		);
	}

	/**
	 * واکشی محصولات ووکامرس و تبدیل به ساختار مشترک کارت محصول.
	 *
	 * @param array $args
	 * @return array
	 */
	public static function query_products( $args ) {
		if ( ! self::has_woo() ) {
			return array();
		}

		$defaults = array(
			'limit'    => 5,
			'source'   => 'recent',
			'category' => '',
			'orderby'  => 'date',
		);

		$args = wp_parse_args( $args, $defaults );

		$query = array(
			'status'   => 'publish',
			'limit'    => max( 1, (int) $args['limit'] ),
			'orderby'  => 'date',
			'order'    => 'DESC',
			'return'   => 'objects',
			'paginate' => false,
		);

		if ( ! empty( $args['category'] ) ) {
			$query['category'] = array_filter( array_map( 'trim', explode( ',', $args['category'] ) ) );
		}

		switch ( $args['source'] ) {
			case 'sale':
				$query['include'] = wc_get_product_ids_on_sale();

				if ( empty( $query['include'] ) ) {
					return array();
				}
				break;

			case 'best':
				$query['orderby']  = 'meta_value_num';
				$query['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				break;

			case 'featured':
				$query['featured'] = true;
				break;

			case 'rating':
				$query['orderby']  = 'meta_value_num';
				$query['meta_key'] = '_wc_average_rating'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				break;
		}

		$products = wc_get_products( $query );
		$out      = array();

		foreach ( $products as $product ) {
			$out[] = self::product_to_card( $product );
		}

		return $out;
	}

	/**
	 * همان واکشی محصولات، ولی با WP_Query تا فیلترهای آدرس (اردال) هم رویش بنشیند.
	 *
	 * wc_get_products آرگومان‌های خودش را دارد و tax_query دلخواه را قبول نمی‌کند،
	 * برای همین اینجا مسیر WP_Query انتخاب شده تا Ardal_Archive بتواند دقیقاً
	 * همان شرط‌هایی را بگذارد که روی آرشیو می‌گذارد.
	 *
	 * @param array $args source، limit، category (اسلاگ‌ها با کاما)، filters.
	 * @return array آرایهٔ کارت‌ها.
	 */
	public static function query_products_filtered( $args ) {
		if ( ! self::has_woo() ) {
			return array();
		}

		$args = wp_parse_args(
			$args,
			array(
				'source'   => 'recent',
				'limit'    => 12,
				'category' => '',
				'filters'  => true,
			)
		);

		$query = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => max( 1, (int) $args['limit'] ),
			'no_found_rows'  => true,
			'tax_query'      => array(),
		);

		$categories = array_filter( array_map( 'trim', explode( ',', (string) $args['category'] ) ) );

		if ( $categories ) {
			$query['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $categories,
			);
		}

		switch ( $args['source'] ) {
			case 'best':
				$query['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				$query['orderby']  = 'meta_value_num';
				$query['order']    = 'DESC';
				break;

			case 'sale':
				$ids              = wc_get_product_ids_on_sale();
				$query['post__in'] = $ids ? $ids : array( 0 );
				break;

			case 'featured':
				$query['tax_query'][] = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
				);
				break;

			case 'rating':
				$query['meta_key'] = '_wc_average_rating'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				$query['orderby']  = 'meta_value_num';
				$query['order']    = 'DESC';
				break;

			default:
				$query['orderby'] = 'date';
				$query['order']   = 'DESC';
		}

		// parse_query کوئری را نمی‌زند؛ پس هنوز جا هست که فیلترها بنشینند.
		$wp_query = new WP_Query();
		$wp_query->parse_query( $query );

		if ( $args['filters'] && class_exists( 'Ardal_Archive' ) ) {
			Ardal_Archive::apply_to_query( $wp_query );
		}

		$wp_query->get_posts();

		return self::cards_from_posts( $wp_query->posts );
	}

	/**
	 * تبدیل فهرست پست به کارت محصول.
	 *
	 * @param array $posts آرایهٔ WP_Post یا شناسه.
	 * @param int   $limit صفر یعنی همه.
	 * @return array
	 */
	public static function cards_from_posts( $posts, $limit = 0 ) {
		if ( ! self::has_woo() || ! $posts ) {
			return array();
		}

		if ( $limit > 0 ) {
			$posts = array_slice( $posts, 0, $limit );
		}

		$out = array();

		foreach ( $posts as $post ) {
			$product = wc_get_product( $post );

			if ( $product && $product->is_visible() ) {
				$out[] = self::product_to_card( $product );
			}
		}

		return $out;
	}

	/**
	 * تبدیل یک محصول ووکامرس به آرایه‌ای که کارت محصول می‌فهمد.
	 *
	 * @param WC_Product $product
	 * @return array
	 */
	public static function product_to_card( $product ) {
		$regular = $product->get_regular_price();
		$active  = $product->get_price();
		$image   = wp_get_attachment_image_src( $product->get_image_id(), 'woocommerce_thumbnail' );

		/*
		 * محصول متغیر: قیمتِ واریاسیونی نشان داده می‌شود که بیشترین تخفیف را
		 * دارد، نه ارزان‌ترین.
		 *
		 * get_regular_price() روی محصول متغیر رشتهٔ خالی برمی‌گرداند و
		 * get_price() کمترین قیمت را می‌دهد؛ نتیجه‌اش کارتی بود که نشانِ تخفیف
		 * داشت ولی قیمت خط‌خورده نداشت، یا تخفیفش به قیمتی اشاره می‌کرد که
		 * مالِ واریاسیون دیگری بود. حالا هر دو عدد از یک واریاسیون می‌آیند.
		 */
		if ( $product->is_type( 'variable' ) && class_exists( 'Ardal_Product' ) ) {
			$deal = Ardal_Product::best_deal( $product );

			if ( $deal ) {
				$active  = $deal['price'];
				$regular = $deal['regular'];
			}
		}

		$off = '';

		if ( $product->is_on_sale() && $regular && $active && (float) $regular > (float) $active ) {
			$off = self::discount( $regular, $active );
		}

		return array(
			'title'     => $product->get_name(),
			'price'     => $active ? self::price( $active ) : '',
			'old_price' => ( $product->is_on_sale() && $regular && (float) $regular > (float) $active ) ? self::price( $regular ) : '',
			'off'       => $off,
			'rating'    => (float) $product->get_average_rating(),
			// برچسب «نظر» همهٔ دیدگاه‌ها را می‌شمارد، نه فقط دیدگاه‌های امتیازدار.
			'count'     => (int) $product->get_review_count(),
			'link'      => array( 'url' => get_permalink( $product->get_id() ) ),
			'image'     => array( 'url' => $image ? $image[0] : '' ),
			'badge'     => '',
			'add_url'   => array( 'url' => $product->add_to_cart_url() ),
			// برای نوار موجودی ویجت پیشنهاد ویژه.
			'product_id' => $product->get_id(),
			// ایجکس فقط وقتی معنا دارد که محصول واقعاً بشود مستقیم به سبد اضافه شود.
			'ajax_add'  => $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock(),
			'wc_sold'   => (int) $product->get_total_sales(),
			'wc_stock'  => $product->managing_stock() ? (int) $product->get_stock_quantity() : 0,
			// فیلدهای کارتِ سبک آرشیو؛ کارت اردال به آن‌ها کاری ندارد.
			'image_id'   => (int) $product->get_image_id(),
			'in_stock'   => $product->is_in_stock(),
			'on_sale'    => $product->is_on_sale(),
			'cat'        => self::first_term_name( $product->get_id() ),
			'subtitle'   => class_exists( 'Ardal_Product' ) ? Ardal_Product::subtitle( $product ) : '',
			'wc_classes' => implode( ' ', wc_get_product_class( '', $product ) ),
		);
	}

	/**
	 * نام اولین دستهٔ محصول — برای برچسب بالای عنوان کارت.
	 *
	 * @param int    $product_id
	 * @param string $taxonomy
	 * @return string
	 */
	public static function first_term_name( $product_id, $taxonomy = 'product_cat' ) {
		$terms = get_the_terms( $product_id, $taxonomy );

		if ( ! $terms || is_wp_error( $terms ) ) {
			return '';
		}

		$first = reset( $terms );

		return $first instanceof WP_Term ? $first->name : '';
	}

	/**
	 * فهرست دسته‌های محصول برای کنترل SELECT2.
	 *
	 * @return array
	 */
	public static function product_category_options() {
		if ( ! self::has_woo() ) {
			return array();
		}

		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'number'     => 200,
			)
		);

		$out = array();

		if ( is_wp_error( $terms ) ) {
			return $out;
		}

		foreach ( $terms as $term ) {
			$out[ $term->slug ] = $term->name;
		}

		return $out;
	}

	/**
	 * حدس کد رنگ از نام رنگ فارسی، برای نمونه‌رنگ کنار گزینه.
	 *
	 * ویژگی‌های ووکامرس کد رنگ ندارند، پس نام‌های رایج نگاشت می‌شوند و بقیه
	 * یک خاکستری خنثی می‌گیرند.
	 *
	 * @param string $label
	 * @return string
	 */
	public static function guess_color( $label ) {
		$map = array(
			'مشکی'   => '#20303F',
			'سیاه'   => '#20303F',
			'سفید'   => '#F2F5F8',
			'قرمز'   => '#C0392B',
			'آبی'    => '#0B62CE',
			'سرمه'   => '#0B3C8C',
			'نیلی'   => '#0B3C8C',
			'سبز'    => '#5FA315',
			'زرد'    => '#F0C300',
			'نارنجی' => '#E8590C',
			'صورتی'  => '#E5679B',
			'بنفش'   => '#7048E8',
			'قهوه'   => '#8B5E34',
			'خاکستری' => '#8C98A4',
			'طوسی'   => '#8C98A4',
			'نقره'   => '#C7CED6',
			'طلایی'  => '#C9A227',
			'کرم'    => '#EFE3CC',
			'بژ'     => '#E3D5BE',
			'فیروزه' => '#2BB3C0',
		);

		foreach ( $map as $needle => $hex ) {
			if ( false !== mb_stripos( $label, $needle ) ) {
				return $hex;
			}
		}

		return '#C7CED6';
	}
}
