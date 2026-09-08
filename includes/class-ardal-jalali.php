<?php
/**
 * تبدیل و قالب‌بندی تاریخ شمسی.
 *
 * عمداً به افزونهٔ تاریخ شمسی یا اکستنشن intl وابسته نیست تا روی هر نصبی کار
 * کند؛ اگر افزونهٔ تاریخ شمسی فعال باشد هم تداخلی ایجاد نمی‌کند چون خودمان
 * تبدیل می‌کنیم.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Jalali {

	/**
	 * نام ماه‌های شمسی.
	 *
	 * @var array
	 */
	private static $months = array(
		1  => 'فروردین',
		2  => 'اردیبهشت',
		3  => 'خرداد',
		4  => 'تیر',
		5  => 'مرداد',
		6  => 'شهریور',
		7  => 'مهر',
		8  => 'آبان',
		9  => 'آذر',
		10 => 'دی',
		11 => 'بهمن',
		12 => 'اسفند',
	);

	/**
	 * نام روزهای هفته به ترتیب `date( 'w' )` یعنی از یکشنبه.
	 *
	 * @var array
	 */
	private static $weekdays = array(
		'یکشنبه',
		'دوشنبه',
		'سه‌شنبه',
		'چهارشنبه',
		'پنجشنبه',
		'جمعه',
		'شنبه',
	);

	/**
	 * قالب‌های آمادهٔ نمایش، برای انتخاب در ویجت.
	 *
	 * @return array
	 */
	public static function formats() {
		return array(
			'long'     => 'روز ماه سال — ۱۴ شهریور ۱۴۰۵',
			'weekday'  => 'روز هفته + تاریخ — شنبه ۱۴ شهریور ۱۴۰۵',
			'numeric'  => 'عددی — ۱۴۰۵/۰۶/۱۴',
			'no_year'  => 'بدون سال — ۱۴ شهریور',
			'with_time' => 'تاریخ و ساعت — ۱۴ شهریور ۱۴۰۵، ساعت ۱۵:۳۰',
		);
	}

	/**
	 * تبدیل تاریخ میلادی به شمسی.
	 *
	 * @param int $gy سال میلادی.
	 * @param int $gm ماه میلادی.
	 * @param int $gd روز میلادی.
	 * @return array آرایهٔ [سال، ماه، روز] شمسی.
	 */
	public static function to_jalali( $gy, $gm, $gd ) {
		$g_days_in_month = array( 0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334 );

		$gy2 = ( $gm > 2 ) ? ( $gy + 1 ) : $gy;

		$days = 355666
			+ ( 365 * $gy )
			+ intdiv( $gy2 + 3, 4 )
			- intdiv( $gy2 + 99, 100 )
			+ intdiv( $gy2 + 399, 400 )
			+ $gd
			+ $g_days_in_month[ $gm - 1 ];

		$jy    = -1595 + ( 33 * intdiv( $days, 12053 ) );
		$days %= 12053;

		$jy   += 4 * intdiv( $days, 1461 );
		$days %= 1461;

		if ( $days > 365 ) {
			$jy  += intdiv( $days - 1, 365 );
			$days = ( $days - 1 ) % 365;
		}

		if ( $days < 186 ) {
			$jm = 1 + intdiv( $days, 31 );
			$jd = 1 + ( $days % 31 );
		} else {
			$jm = 7 + intdiv( $days - 186, 30 );
			$jd = 1 + ( ( $days - 186 ) % 30 );
		}

		return array( $jy, $jm, $jd );
	}

	/**
	 * تاریخ شمسیِ آماده نمایش.
	 *
	 * @param int    $timestamp زمان به وقت محلی سایت.
	 * @param string $format    یکی از کلیدهای formats().
	 * @return string
	 */
	public static function format( $timestamp, $format = 'long' ) {
		$timestamp = (int) $timestamp;

		$gy = (int) gmdate( 'Y', $timestamp );
		$gm = (int) gmdate( 'n', $timestamp );
		$gd = (int) gmdate( 'j', $timestamp );

		list( $jy, $jm, $jd ) = self::to_jalali( $gy, $gm, $gd );

		$month   = isset( self::$months[ $jm ] ) ? self::$months[ $jm ] : '';
		$weekday = self::$weekdays[ (int) gmdate( 'w', $timestamp ) ];

		switch ( $format ) {
			case 'numeric':
				$out = sprintf( '%04d/%02d/%02d', $jy, $jm, $jd );
				break;

			case 'weekday':
				$out = sprintf( '%s %d %s %d', $weekday, $jd, $month, $jy );
				break;

			case 'no_year':
				$out = sprintf( '%d %s', $jd, $month );
				break;

			case 'with_time':
				$out = sprintf( '%d %s %d، ساعت %s', $jd, $month, $jy, gmdate( 'H:i', $timestamp ) );
				break;

			case 'long':
			default:
				$out = sprintf( '%d %s %d', $jd, $month, $jy );
				break;
		}

		return Ardal_Helpers::fa_digits( $out );
	}

	/**
	 * نام ماه شمسی از روی شمارهٔ آن — برای برچسب نمودارها.
	 *
	 * @param int $month شمارهٔ ماه ۱ تا ۱۲.
	 * @return string
	 */
	public static function month_label( $month ) {
		$month = (int) $month;

		return isset( self::$months[ $month ] ) ? self::$months[ $month ] : '';
	}

	/**
	 * کلید یکتای «سال-ماه» شمسی، برای گروه‌بندی داده‌ها بر حسب ماه.
	 *
	 * @param int $timestamp
	 * @return string
	 */
	public static function month_key( $timestamp ) {
		list( $jy, $jm, ) = self::to_jalali(
			(int) gmdate( 'Y', $timestamp ),
			(int) gmdate( 'n', $timestamp ),
			(int) gmdate( 'j', $timestamp )
		);

		return sprintf( '%04d-%02d', $jy, $jm );
	}

	/**
	 * زمان محلی سایت. `current_time` منطقهٔ زمانی وردپرس را اعمال می‌کند، پس
	 * بقیهٔ محاسبات با gmdate انجام می‌شود تا آفست دوباره اضافه نشود.
	 *
	 * @return int
	 */
	public static function now() {
		return (int) current_time( 'timestamp' ); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
	}
}
