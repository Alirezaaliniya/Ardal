<?php
/**
 * پالت رنگی سراسری — تنها منبع حقیقت برای توکن‌های رنگ و تایپوگرافی.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Palette {

	const OPTION = 'ardal_palette';

	/**
	 * مقادیر پیش‌فرض، دقیقاً برگرفته از دیزاین «فروشگاه ورزشی».
	 *
	 * @return array
	 */
	public static function schema() {
		return array(
			'primary_dark'      => array(
				'label'   => 'سرمه‌ای اصلی',
				'default' => '#0B3C8C',
				'desc'    => 'رنگ برند: هدر، فوتر، قیمت‌ها و نوار اعلان',
				'group'   => 'brand',
			),
			'primary'           => array(
				'label'   => 'آبی اصلی',
				'default' => '#0B62CE',
				'desc'    => 'دکمه‌ها، لینک‌ها و عناصر تعاملی',
				'group'   => 'brand',
			),
			'primary_light'     => array(
				'label'   => 'آبی روشن',
				'default' => '#2E9BF0',
				'desc'    => 'انتهای گرادیان‌ها و حالت‌های روشن',
				'group'   => 'brand',
			),
			'accent'            => array(
				'label'   => 'سبز تاکیدی',
				'default' => '#7DC61E',
				'desc'    => 'دکمه افزودن به سبد، نشانگرها و نوار پیشرفت',
				'group'   => 'brand',
			),
			'accent_dark'       => array(
				'label'   => 'سبز تیره',
				'default' => '#5FA315',
				'desc'    => 'حالت هاور سبز و آیکون‌ها',
				'group'   => 'brand',
			),
			'accent_deep'       => array(
				'label'   => 'سبز متن',
				'default' => '#3F7A0C',
				'desc'    => 'عنوان‌های روی زمینه سبز روشن',
				'group'   => 'brand',
			),
			'toman'             => array(
				'label'   => 'رنگ نماد تومان',
				'default' => '#003061',
				'desc'    => 'وقتی «نماد تومان» روشن باشد، رنگ آیکون کنار قیمت‌ها',
				'group'   => 'brand',
			),
			'sale'              => array(
				'label'   => 'قرمز حراج',
				'default' => '#C0392B',
				'desc'    => 'برچسب تخفیف و درصدها',
				'group'   => 'state',
			),
			'star'              => array(
				'label'   => 'زرد امتیاز',
				'default' => '#F0A500',
				'desc'    => 'ستاره‌های امتیاز محصولات',
				'group'   => 'state',
			),
			'text'              => array(
				'label'   => 'متن اصلی',
				'default' => '#20303F',
				'desc'    => 'عنوان‌ها و متن‌های پررنگ',
				'group'   => 'text',
			),
			'text_body'         => array(
				'label'   => 'متن منو',
				'default' => '#3D4A58',
				'desc'    => 'آیتم‌های منو و متن‌های میانی',
				'group'   => 'text',
			),
			'text_soft'         => array(
				'label'   => 'متن ثانویه',
				'default' => '#5D6B7A',
				'desc'    => 'توضیحات و پاراگراف‌ها',
				'group'   => 'text',
			),
			'muted'             => array(
				'label'   => 'متن کم‌رنگ',
				'default' => '#9AA7B4',
				'desc'    => 'قیمت خط‌خورده، تاریخ و placeholder',
				'group'   => 'text',
			),
			'on_dark'           => array(
				'label'   => 'متن روی سرمه‌ای',
				'default' => '#CFE4FF',
				'desc'    => 'متن فوتر و نوار اعلان بالای صفحه',
				'group'   => 'text',
			),
			'border'            => array(
				'label'   => 'حاشیه کارت',
				'default' => '#E9EEF4',
				'desc'    => 'دور کارت محصول و باکس‌ها',
				'group'   => 'surface',
			),
			'border_soft'       => array(
				'label'   => 'حاشیه ملایم',
				'default' => '#EEF1F5',
				'desc'    => 'خط جداکننده بخش‌ها',
				'group'   => 'surface',
			),
			'surface'           => array(
				'label'   => 'سطح خنثی',
				'default' => '#F4F7FA',
				'desc'    => 'زمینه جستجو و چیپ‌ها',
				'group'   => 'surface',
			),
			'surface_alt'       => array(
				'label'   => 'سطح خنثی دوم',
				'default' => '#F6F9FC',
				'desc'    => 'زمینه بخش خبرنامه و کارت‌های ساده',
				'group'   => 'surface',
			),
			'tint_blue'         => array(
				'label'   => 'آبی پس‌زمینه',
				'default' => '#EAF4FF',
				'desc'    => 'حلقه دسته‌بندی و آیکون‌های آبی',
				'group'   => 'surface',
			),
			'tint_blue_border'  => array(
				'label'   => 'آبی حاشیه',
				'default' => '#CFE2F8',
				'desc'    => 'حاشیه عناصر آبی روشن',
				'group'   => 'surface',
			),
			'tint_green'        => array(
				'label'   => 'سبز پس‌زمینه',
				'default' => '#F1F8E6',
				'desc'    => 'باکس پیشنهاد ویژه و سبد خرید',
				'group'   => 'surface',
			),
			'tint_green_border' => array(
				'label'   => 'سبز حاشیه',
				'default' => '#DBEEC2',
				'desc'    => 'حاشیه عناصر سبز روشن',
				'group'   => 'surface',
			),
			'base'              => array(
				'label'   => 'زمینه سفید',
				'default' => '#FFFFFF',
				'desc'    => 'زمینه کارت‌ها و صفحه',
				'group'   => 'surface',
			),
		);
	}

	/**
	 * گروه‌بندی فیلدها در صفحه تنظیمات.
	 *
	 * @return array
	 */
	public static function groups() {
		return array(
			'brand'   => 'رنگ‌های برند',
			'state'   => 'رنگ‌های وضعیت',
			'text'    => 'متن',
			'surface' => 'سطوح و حاشیه‌ها',
		);
	}

	/**
	 * عنوان کارت‌های تنظیمات غیررنگی.
	 *
	 * @return array
	 */
	public static function extras_groups() {
		return array(
			'type'    => 'تایپوگرافی و ابعاد',
			'money'   => 'واحد پول و اعداد',
			'product' => 'صفحه محصول',
			'account' => 'پنل کاربری',
			'woocommerce_assets' => 'فایل‌های ووکامرس',
		);
	}

	/**
	 * تنظیمات غیررنگی.
	 *
	 * @return array
	 */
	public static function extras_schema() {
		return array(
			'radius'           => array(
				'label'   => 'گردی گوشه‌ها',
				'default' => '16',
				'type'    => 'number',
				'desc'    => 'مبنای گردی کارت‌ها بر حسب پیکسل؛ بقیه اندازه‌ها نسبت به آن محاسبه می‌شوند.',
				'group'   => 'type',
			),
			'container'        => array(
				'label'   => 'حداکثر عرض محتوا',
				'default' => '1440',
				'type'    => 'number',
				'desc'    => 'عرض کانتینر بخش‌ها بر حسب پیکسل. دیزاین برای ۱۴۴۰ پیکسل طراحی شده است.',
				'group'   => 'type',
			),
			'toman_symbol'     => array(
				'label'   => 'تغییر ساختار تومان',
				'default' => '0',
				'type'    => 'checkbox',
				'desc'    => 'به‌جای نوشتن «تومان» کنار قیمت‌ها، نماد رسمی تومان نمایش داده می‌شود.',
				'group'   => 'money',
			),
			'toman_woo'        => array(
				'label'   => 'اعمال روی قیمت‌های خود ووکامرس',
				'default' => '0',
				'type'    => 'checkbox',
				'desc'    => 'علاوه بر ویجت‌های اردال، روی قیمت محصول در فروشگاه، آرشیو، صفحه محصول، سبد خرید و تسویه هم اعمال می‌شود. ایمیل‌ها و پیشخوان دست‌نخورده می‌مانند.',
				'group'   => 'money',
			),
			'toman_size'       => array(
				'label'   => 'اندازه نماد تومان',
				'default' => '170',
				'type'    => 'number',
				'desc'    => 'درصدِ اندازهٔ قلمِ قیمت. نماد تومان دوطبقه است، برای همین از خودِ رقم‌ها بلندتر تنظیم می‌شود.',
				'group'   => 'money',
			),
			'persian_digits'   => array(
				'label'   => 'تبدیل خودکار اعداد به فارسی',
				'default' => '1',
				'type'    => 'checkbox',
				'desc'    => 'قیمت‌ها، شمارنده‌ها و تعدادها با ارقام فارسی نمایش داده می‌شوند.',
				'group'   => 'money',
			),
			'product_subtitle' => array(
				'label'   => 'نمایش عنوان انگلیسی محصول',
				'default' => '0',
				'type'    => 'checkbox',
				'desc'    => 'زیرعنوان لاتین زیر نام محصول. متن آن از فیلد سفارشی محصول یا از تنظیم خود ویجت خوانده می‌شود.',
				'group'   => 'product',
			),
			'product_subtitle_key' => array(
				'label'   => 'کلید فیلد سفارشی عنوان انگلیسی',
				'default' => '_ardal_subtitle',
				'type'    => 'text',
				'desc'    => 'نام متای محصول که عنوان انگلیسی از آن خوانده می‌شود. اگر خالی یا موجود نبود، از متن ویجت استفاده می‌شود.',
				'group'   => 'product',
			),
			'account_support_phone' => array(
				'label'   => 'شماره پشتیبانی',
				'default' => '',
				'type'    => 'text',
				'desc'    => 'در کارت پشتیبانی ستون کناری پنل کاربری و کشوی موبایل نمایش داده می‌شود. خالی بگذارید تا دکمهٔ تماس حذف شود.',
				'group'   => 'account',
			),
			'account_support_hours' => array(
				'label'   => 'ساعت پاسخگویی',
				'default' => 'هر روز از ۹ صبح تا ۹ شب پاسخگوی شما هستیم.',
				'type'    => 'text',
				'desc'    => 'متن کوتاه زیر عنوان کارت پشتیبانی در پنل کاربری.',
				'group'   => 'account',
			),
			'product_wishlist' => array(
				'label'   => 'فعال‌سازی علاقه‌مندی',
				'default' => '1',
				'type'    => 'checkbox',
				'desc'    => 'دکمهٔ علاقه‌مندی در گالری، کارت محصول و باکس خرید. با خاموش‌کردن، همه‌جا حذف می‌شود.',
				'group'   => 'product',
			),
			'product_compare'  => array(
				'label'   => 'فعال‌سازی مقایسه',
				'default' => '1',
				'type'    => 'checkbox',
				'desc'    => 'دکمهٔ مقایسه در گالری و کارت محصول. با خاموش‌کردن، همه‌جا حذف می‌شود.',
				'group'   => 'product',
			),
			'disable_wc_general_css' => array(
				'label'   => 'غیرفعال‌سازی CSS اصلی ووکامرس (woocommerce-general)',
				'default' => '0',
				'type'    => 'checkbox',
				'desc'    => 'استایل‌های عمومی دکمه‌ها، پیام‌ها و جدول‌های ووکامرس. فعال‌سازی این گزینه ممکن است ظاهر فروشگاه، سبد و تسویه‌حساب را تغییر دهد.',
				'group'   => 'woocommerce_assets',
			),
			'disable_wc_layout_css' => array(
				'label'   => 'غیرفعال‌سازی CSS چیدمان ووکامرس (woocommerce-layout)',
				'default' => '0',
				'type'    => 'checkbox',
				'desc'    => 'چیدمان پیش‌فرض فروشگاه و صفحه محصول، شامل ستون‌ها و ساختار محتوا. فقط وقتی قالب/Elementor جایگزین کامل چیدمان است فعال کنید.',
				'group'   => 'woocommerce_assets',
			),
			'disable_wc_smallscreen_css' => array(
				'label'   => 'غیرفعال‌سازی CSS واکنش‌گرا ووکامرس (woocommerce-smallscreen)',
				'default' => '0',
				'type'    => 'checkbox',
				'desc'    => 'قواعد واکنش‌گرا برای نمایش ووکامرس در موبایل و صفحه‌های کوچک. ممکن است ظاهر فروشگاه در موبایل را تحت تأثیر قرار دهد.',
				'group'   => 'woocommerce_assets',
			),
			'disable_wc_add_to_cart_js' => array(
				'label'   => 'غیرفعال‌سازی JavaScript افزودن ایجکس به سبد (wc-add-to-cart)',
				'default' => '0',
				'type'    => 'checkbox',
				'desc'    => 'افزودن محصول از لیست/کارت بدون رفرش صفحه را خاموش می‌کند؛ دکمه‌ها با رفتن به لینک محصول یا سبد کار می‌کنند.',
				'group'   => 'woocommerce_assets',
			),
			'disable_wc_cart_fragments_js' => array(
				'label'   => 'غیرفعال‌سازی JavaScript به‌روزرسانی مینی‌سبد (wc-cart-fragments)',
				'default' => '0',
				'type'    => 'checkbox',
				'desc'    => 'شمارنده و محتوای مینی‌سبد پس از افزودن کالا خودکار به‌روزرسانی نمی‌شود؛ برای کاهش درخواست‌های Ajax مناسب است.',
				'group'   => 'woocommerce_assets',
			),
			'disable_wc_single_product_js' => array(
				'label'   => 'غیرفعال‌سازی JavaScript صفحه محصول (wc-single-product)',
				'default' => '0',
				'type'    => 'checkbox',
				'desc'    => 'رفتار گالری استاندارد ووکامرس و وابستگی‌های آن را خاموش می‌کند. اگر از گالری سفارشی اردال استفاده می‌کنید می‌تواند مفید باشد.',
				'group'   => 'woocommerce_assets',
			),
			'disable_wc_variation_js' => array(
				'label'   => 'غیرفعال‌سازی JavaScript محصول متغیر (wc-add-to-cart-variation)',
				'default' => '0',
				'type'    => 'checkbox',
				'desc'    => 'انتخاب ویژگی‌ها، قیمت و موجودی محصول متغیر در فرم استاندارد ووکامرس را غیرفعال می‌کند. برای محصولات متغیر معمولاً خاموش بماند.',
				'group'   => 'woocommerce_assets',
			),
		);
	}

	/**
	 * مقادیر ذخیره‌شده به‌علاوه پیش‌فرض‌ها.
	 *
	 * @return array
	 */
	public static function get_all() {
		$saved    = get_option( self::OPTION, array() );
		$saved    = is_array( $saved ) ? $saved : array();
		$defaults = self::defaults();

		$merged = array_merge( $defaults, array_intersect_key( $saved, $defaults ) );

		foreach ( $merged as $key => $value ) {
			if ( '' === $value && isset( $defaults[ $key ] ) ) {
				$merged[ $key ] = $defaults[ $key ];
			}
		}

		return $merged;
	}

	/**
	 * @return array
	 */
	public static function defaults() {
		$out = array();

		foreach ( self::schema() as $key => $field ) {
			$out[ $key ] = $field['default'];
		}

		foreach ( self::extras_schema() as $key => $field ) {
			$out[ $key ] = $field['default'];
		}

		return $out;
	}

	/**
	 * @param string $key
	 * @return string
	 */
	public static function get( $key ) {
		$all = self::get_all();

		return isset( $all[ $key ] ) ? $all[ $key ] : '';
	}

	/**
	 * تبدیل hex به رشته «r, g, b» برای استفاده در rgba().
	 *
	 * @param string $hex
	 * @return string
	 */
	public static function to_rgb( $hex ) {
		$hex = ltrim( (string) $hex, '#' );

		if ( 3 === strlen( $hex ) ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}

		if ( 6 !== strlen( $hex ) || ! ctype_xdigit( $hex ) ) {
			return '0, 0, 0';
		}

		return sprintf(
			'%d, %d, %d',
			hexdec( substr( $hex, 0, 2 ) ),
			hexdec( substr( $hex, 2, 2 ) ),
			hexdec( substr( $hex, 4, 2 ) )
		);
	}

	/**
	 * روشن یا تیره کردن یک رنگ برای حالت هاور.
	 *
	 * @param string $hex     رنگ مبدأ.
	 * @param int    $percent مثبت = روشن‌تر، منفی = تیره‌تر.
	 * @return string
	 */
	public static function shade( $hex, $percent ) {
		$rgb = array_map( 'intval', explode( ',', self::to_rgb( $hex ) ) );

		foreach ( $rgb as $i => $channel ) {
			$target    = $percent > 0 ? 255 : 0;
			$rgb[ $i ] = (int) round( $channel + ( $target - $channel ) * ( abs( $percent ) / 100 ) );
			$rgb[ $i ] = max( 0, min( 255, $rgb[ $i ] ) );
		}

		return sprintf( '#%02X%02X%02X', $rgb[0], $rgb[1], $rgb[2] );
	}

	/**
	 * تولید بلوک متغیرهای CSS از پالت جاری.
	 *
	 * @return string
	 */
	public static function css_vars() {
		$p     = self::get_all();
		$lines = array();

		foreach ( array_keys( self::schema() ) as $key ) {
			$hex  = $p[ $key ];
			$name = str_replace( '_', '-', $key );

			$lines[] = sprintf( '--ard-%s:%s;', $name, $hex );
			$lines[] = sprintf( '--ard-%s-rgb:%s;', $name, self::to_rgb( $hex ) );
		}

		// مشتق‌های محاسبه‌شده تا کاربر مجبور نباشد هر سایه را دستی تنظیم کند.
		$lines[] = sprintf( '--ard-primary-hover:%s;', self::shade( $p['primary'], -22 ) );
		$lines[] = sprintf( '--ard-accent-hover:%s;', self::shade( $p['accent'], 14 ) );
		$lines[] = sprintf( '--ard-accent-ink:%s;', self::shade( $p['accent_dark'], -62 ) );
		$lines[] = sprintf( '--ard-sale-soft:%s;', self::shade( $p['sale'], 88 ) );
		$lines[] = sprintf(
			'--ard-hero-gradient:linear-gradient(135deg,%s,%s 60%%,%s);',
			$p['primary_dark'],
			self::shade( $p['primary'], 8 ),
			$p['primary_light']
		);
		$lines[] = sprintf(
			'--ard-hero-gradient-m:linear-gradient(150deg,%s,%s 65%%,%s);',
			$p['primary_dark'],
			self::shade( $p['primary'], 8 ),
			$p['primary_light']
		);

		$radius = max( 0, (int) $p['radius'] );

		$lines[] = sprintf( '--ard-radius:%dpx;', $radius );
		$lines[] = sprintf( '--ard-radius-sm:%dpx;', (int) round( $radius * 0.7 ) );
		$lines[] = sprintf( '--ard-radius-lg:%dpx;', (int) round( $radius * 1.15 ) );
		$lines[] = sprintf( '--ard-radius-xl:%dpx;', (int) round( $radius * 1.4 ) );
		$lines[] = sprintf( '--ard-radius-pill:%dpx;', max( 20, (int) round( $radius * 1.6 ) ) );
		$lines[] = sprintf( '--ard-container:%dpx;', max( 320, (int) $p['container'] ) );

		$toman = max( 40, min( 400, (int) $p['toman_size'] ) );

		$lines[] = sprintf( '--ard-toman-size:%.2fem;', $toman / 100 );

		// Typography is inherited from the active theme / Elementor Site Settings.
		$lines[] = '--ard-font:inherit;';

		return ':root{' . implode( '', $lines ) . '}';
	}

	/**
	 * پاک‌سازی ورودی صفحه تنظیمات.
	 *
	 * @param mixed $input
	 * @return array
	 */
	public static function sanitize( $input ) {
		$defaults = self::defaults();
		$clean    = array();
		$input    = is_array( $input ) ? $input : array();

		foreach ( self::schema() as $key => $field ) {
			$value = isset( $input[ $key ] ) ? sanitize_hex_color( $input[ $key ] ) : '';

			$clean[ $key ] = $value ? strtoupper( $value ) : $field['default'];
		}

		foreach ( self::extras_schema() as $key => $field ) {
			switch ( $field['type'] ) {
				case 'checkbox':
					$clean[ $key ] = empty( $input[ $key ] ) ? '0' : '1';
					break;

				case 'number':
					$raw = isset( $input[ $key ] ) ? trim( (string) $input[ $key ] ) : '';

					if ( '' === $raw || ! is_numeric( $raw ) ) {
						$clean[ $key ] = $field['default'];
						break;
					}

					$number = absint( $raw );

					// صفر فقط برای radius معنا دارد (گوشه‌های تیز)؛ برای عرض کانتینر نه.
					$clean[ $key ] = ( 0 === $number && 'radius' !== $key )
						? $field['default']
						: (string) $number;
					break;

				default:
					$clean[ $key ] = isset( $input[ $key ] )
						? sanitize_text_field( $input[ $key ] )
						: $field['default'];
			}
		}

		return array_merge( $defaults, $clean );
	}
}
