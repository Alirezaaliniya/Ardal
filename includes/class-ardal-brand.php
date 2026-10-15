<?php
/**
 * برند اختصاصی (وایت‌لیبل): نام نمایشی افزونه در همه‌جا.
 *
 * مدیر نام دلخواه می‌گذارد (مثلاً نام فروشگاه) و هرجا «اردال» به مدیر یا
 * کاربر نشان داده می‌شود همان نام می‌آید: منو و صفحهٔ تنظیمات پیشخوان،
 * فهرست افزونه‌ها، دسته‌ها و عنوان ویجت‌ها و کنترل‌های المنتور، پیام‌های
 * مدیر و برچسب پیامک‌ها در گزارش نیاس.
 *
 * نام‌های فنی (پوشه، کلاس‌ها، کلید آپشن‌ها، شورت‌کدها و کلاس‌های CSS) عوض
 * نمی‌شوند؛ عوض کردنشان تنظیمات و صفحه‌های ساخته‌شده را می‌شکست.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Brand {

	/**
	 * نام اصلی که در متن‌ها جایگزین می‌شود.
	 */
	const ORIGINAL = 'اردال';

	public static function init() {
		add_filter( 'all_plugins', array( __CLASS__, 'plugin_list' ) );
	}

	/**
	 * نام نمایشی.
	 *
	 * @return string
	 */
	public static function name() {
		$name = trim( (string) Ardal_Palette::get( 'brand_name' ) );

		return '' !== $name ? $name : self::ORIGINAL;
	}

	/**
	 * نام عوض شده است؟
	 *
	 * @return bool
	 */
	public static function custom() {
		return self::ORIGINAL !== self::name();
	}

	/**
	 * «اردال» در یک متن نمایشی با نام برند.
	 *
	 * @param string $text
	 * @return string
	 */
	public static function text( $text ) {
		if ( ! is_string( $text ) || ! self::custom() || false === strpos( $text, self::ORIGINAL ) ) {
			return $text;
		}

		return str_replace( self::ORIGINAL, self::name(), $text );
	}

	/**
	 * حرف اول نام برای نشان مربعی صفحهٔ تنظیمات.
	 *
	 * @return string
	 */
	public static function mark() {
		return mb_substr( self::name(), 0, 1 );
	}

	/**
	 * کنترل المنتور: برچسب، توضیح، متن خام و مقدار پیش‌فرض متنی.
	 *
	 * @param array $args
	 * @return array
	 */
	public static function control_args( $args ) {
		if ( ! self::custom() ) {
			return $args;
		}

		foreach ( array( 'label', 'description', 'raw', 'placeholder', 'title' ) as $key ) {
			if ( isset( $args[ $key ] ) && is_string( $args[ $key ] ) ) {
				$args[ $key ] = self::text( $args[ $key ] );
			}
		}

		if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
			foreach ( $args['options'] as $k => $v ) {
				if ( is_string( $v ) ) {
					$args['options'][ $k ] = self::text( $v );
				}
			}
		}

		return $args;
	}

	/**
	 * نام، توضیح و سازنده در صفحهٔ «افزونه‌ها».
	 *
	 * @param array $plugins
	 * @return array
	 */
	public static function plugin_list( $plugins ) {
		$file = plugin_basename( ARDAL_FILE );

		if ( ! isset( $plugins[ $file ] ) ) {
			return $plugins;
		}

		$p      = $plugins[ $file ];
		$desc   = trim( (string) Ardal_Palette::get( 'brand_desc' ) );
		$author = trim( (string) Ardal_Palette::get( 'brand_author' ) );

		if ( self::custom() ) {
			$p['Name']  = self::text( $p['Name'] );
			$p['Title'] = self::text( $p['Title'] );
		}

		if ( '' !== $desc ) {
			$p['Description'] = $desc;
		}

		if ( '' !== $author ) {
			$p['Author']     = $author;
			$p['AuthorName'] = $author;
			$p['AuthorURI']  = '';
			$p['PluginURI']  = '';
		}

		$plugins[ $file ] = $p;

		return $plugins;
	}
}
