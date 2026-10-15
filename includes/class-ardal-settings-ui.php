<?php
/**
 * صفحهٔ تنظیمات اردال — رابط کاربری تازه (طبق دیزاین docs/Ardal Settings).
 *
 * داده‌ها از همان اسکیماهای قبلی می‌آیند (پالت، پنل کاربری، علاقه‌مندی، کیف
 * پول) و نام همهٔ فیلدها دست‌نخورده است؛ این کلاس فقط چیدمان و ظاهر را
 * می‌سازد. همهٔ تب‌ها یک فرم options.php هستند و با یک «ذخیره» ثبت می‌شوند.
 *
 * رفتارهای سمت کاربر (ردیابی تغییرات، نوار ذخیره، جست‌وجو، وابستگی‌ها،
 * جابه‌جایی منو، پیامک آزمایشی، پیش‌نمایش زنده) در assets/admin/admin.js است.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Settings_UI {

	/* --------------------------------------------------------------------- */
	/* نمایش فیلدها: واحد، گام، جهت و...                                      */
	/* --------------------------------------------------------------------- */

	/**
	 * جزئیات نمایشی که در اسکیماها نیست.
	 *
	 * @return array کلید فیلد => تنظیمات.
	 */
	private static function presentation() {
		return array(
			'radius'               => array( 'unit' => 'پیکسل' ),
			'container'            => array( 'unit' => 'پیکسل', 'step' => 10 ),
			'toman_size'           => array( 'unit' => '٪', 'step' => 10 ),
			'archive_per_page'     => array( 'unit' => 'محصول', 'min' => 0 ),
			'suggest_count'        => array( 'unit' => 'محصول', 'min' => 1 ),
			'orders_per_page'      => array( 'unit' => 'سفارش', 'min' => 1 ),
			'max'                  => array( 'unit' => 'کالا', 'min' => 1, 'step' => 10 ),
			'wishlist_columns'     => array( 'unit' => 'ستون', 'min' => 1 ),
			'max_percent'          => array( 'unit' => '٪', 'min' => 1, 'max' => 100, 'step' => 5 ),
			'cashback_percent'     => array( 'unit' => '٪', 'min' => 0, 'max' => 100, 'step' => 0.5 ),
			'cashback_days'        => array( 'unit' => 'روز', 'min' => 0 ),
			'product_subtitle_key' => array( 'dir' => 'ltr' ),
			'account_support_phone' => array( 'dir' => 'ltr', 'ph' => '021-91004500' ),
			'quick_amounts'        => array( 'dir' => 'ltr' ),
			'topup_note'           => array( 'empty_hide' => true ),
			'wishlist_empty_btn'   => array( 'empty_hide' => true ),
		);
	}

	/**
	 * یک فیلد استاندارد.
	 *
	 * @param string $option  نام آپشن (مثلاً ardal_wallet).
	 * @param string $key
	 * @param string $type    toggle | text | textarea | number | money | select | icon | color | image
	 * @param string $label
	 * @param mixed  $value
	 * @param array  $extra   help, default, options, min, max, step, unit, dir, ph, tokens, empty_hide, show_when, risk
	 * @return array
	 */
	private static function field( $option, $key, $type, $label, $value, $extra = array() ) {
		$pres = self::presentation();
		$f    = array_merge(
			array(
				'key'   => $key,
				'name'  => $option . '[' . $key . ']',
				'type'  => $type,
				'label' => $label,
				'value' => (string) $value,
				'help'  => '',
			),
			isset( $pres[ $key ] ) ? $pres[ $key ] : array(),
			array_filter(
				$extra,
				static function ( $v ) {
					return null !== $v && '' !== $v;
				}
			)
		);

		// متن‌های نمایشی با نام برند؛ مقدار فیلد دست نمی‌خورد.
		$f['label'] = Ardal_Brand::text( $f['label'] );
		$f['help']  = Ardal_Brand::text( (string) $f['help'] );

		if ( ! empty( $f['show_when'] ) && false === strpos( $f['show_when'], '[' ) ) {
			$f['show_when'] = $option . '[' . $f['show_when'] . ']';
		}

		return $f;
	}

	/**
	 * نوع اسکیما به نوع رابط.
	 *
	 * @param string $type
	 * @return string
	 */
	private static function ui_type( $type ) {
		return 'checkbox' === $type ? 'toggle' : $type;
	}

	/**
	 * کارت‌های اسکیمای «ویژگی‌ها» (علاقه‌مندی و کیف پول) به فیلدهای رابط.
	 *
	 * @param array  $schema_fields
	 * @param string $default_option wishlist | wallet
	 * @return array
	 */
	private static function feature_fields( $schema_fields, $default_option ) {
		$out = array();

		foreach ( $schema_fields as $key => $s ) {
			$opt = isset( $s['option'] ) ? $s['option'] : $default_option;

			switch ( $opt ) {
				case 'palette':
					$option = Ardal_Palette::OPTION;
					$value  = Ardal_Palette::get( $key );
					break;

				case 'account':
					$option = Ardal_Account_Settings::OPTION;
					$value  = Ardal_Account_Settings::get( $key );
					break;

				case 'wallet':
					$option = Ardal_Wallet::OPTION;
					$value  = Ardal_Wallet::option( $key );
					break;

				default:
					$option = Ardal_Wishlist::OPTION;
					$value  = Ardal_Wishlist::option( $key );
			}

			$out[] = self::field(
				$option,
				$key,
				self::ui_type( $s['type'] ),
				$s['label'],
				$value,
				array(
					'help'      => isset( $s['desc'] ) ? $s['desc'] : '',
					'options'   => isset( $s['options'] ) ? $s['options'] : null,
					'min'       => isset( $s['min'] ) ? $s['min'] : null,
					'max'       => isset( $s['max'] ) ? $s['max'] : null,
					'step'      => isset( $s['step'] ) ? $s['step'] : null,
					'dir'       => ! empty( $s['ltr'] ) ? 'ltr' : null,
					'show_when' => isset( $s['show'] ) ? $s['show'] : null,
				)
			);
		}

		return $out;
	}

	/* --------------------------------------------------------------------- */
	/* تب‌ها و کارت‌ها                                                        */
	/* --------------------------------------------------------------------- */

	/**
	 * @return array
	 */
	public static function tabs() {
		$tabs = array(
			array(
				'id'    => 'palette',
				'title' => 'پالت و ظاهر',
				'lede'  => 'رنگ‌های زیر روی همه ویجت‌های اردال در المنتور اعمال می‌شوند. با تغییر هر رنگ، پیش‌نمایش کنار صفحه بلافاصله به‌روز می‌شود.',
				'cards' => self::palette_cards(),
			),
			array(
				'id'    => 'account',
				'title' => 'پنل کاربری',
				'lede'  => 'صفحه‌های پنل کاربری، ترتیب و نامشان، و بخش‌های صفحهٔ پیشخوان از همین‌جا کنترل می‌شوند.',
				'cards' => self::account_cards(),
			),
			array(
				'id'    => 'wishlist',
				'title' => 'علاقه‌مندی',
				'lede'  => 'رفتار دکمهٔ قلب، پیام‌ها و صفحهٔ علاقه‌مندی‌ها. ظاهر دکمه و فهرست در خود ویجت‌های المنتور تنظیم می‌شود.',
				'cards' => self::wishlist_cards(),
			),
			array(
				'id'    => 'wallet',
				'title' => 'کیف پول',
				'lede'  => 'کیف پول، شارژ آنلاین، پرداخت در تسویه، کش‌بگ، برداشت و پیامک.',
				'cards' => Ardal_Helpers::has_woo() ? self::wallet_cards() : array(),
			),
		);

		// عنوان‌ها و توضیح‌های کارت‌ها هم با نام برند.
		foreach ( $tabs as &$tab ) {
			$tab['lede'] = Ardal_Brand::text( $tab['lede'] );

			foreach ( $tab['cards'] as &$card ) {
				foreach ( array( 'title', 'short', 'sub', 'info', 'off' ) as $k ) {
					if ( isset( $card[ $k ] ) ) {
						$card[ $k ] = Ardal_Brand::text( $card[ $k ] );
					}
				}
			}

			unset( $card );
		}

		unset( $tab );

		return $tabs;
	}

	/**
	 * @return array
	 */
	private static function palette_cards() {
		$opt    = Ardal_Palette::OPTION;
		$values = Ardal_Palette::get_all();
		$brand  = array();

		foreach ( Ardal_Palette::extras_schema() as $key => $s ) {
			if ( isset( $s['group'] ) && 'brand' === $s['group'] ) {
				$brand[] = self::field( $opt, $key, 'text', $s['label'], $values[ $key ], array( 'help' => $s['desc'], 'ph' => $s['default'] ) );
			}
		}

		$cards = array(
			array(
				'id'     => 'brand',
				'title'  => 'نام و برند افزونه',
				'short'  => 'نام و برند',
				'info'   => 'افزونه را با نام خودتان نشان دهید تا برای همین سایت ساخته‌شده به نظر برسد. نام‌های فنی (پوشه، شورت‌کدها، کلاس‌ها) عوض نمی‌شوند تا صفحه‌های ساخته‌شده سالم بمانند.',
				'fields' => $brand,
			),
			array(
				'id'   => 'presets',
				'title' => 'پالت‌های آماده',
				'kind' => 'presets',
				'sub'  => 'با یک کلیک همه رنگ‌ها را جایگزین کنید. تا وقتی «ذخیره تغییرات» را نزنید چیزی ثبت نمی‌شود.',
			),
		);

		foreach ( Ardal_Palette::groups() as $group => $title ) {
			$fields = array();

			foreach ( Ardal_Palette::schema() as $key => $s ) {
				if ( $s['group'] === $group ) {
					$fields[] = self::field( $opt, $key, 'color', $s['label'], $values[ $key ], array( 'help' => $s['desc'], 'default' => $s['default'] ) );
				}
			}

			$cards[] = array( 'id' => 'colors_' . $group, 'title' => $title, 'fields' => $fields );
		}

		$extras = Ardal_Palette::extras_schema();

		foreach ( Ardal_Palette::extras_groups() as $group => $title ) {
			// پشتیبانی پنل در تب پنل کاربری است و برند بالای همین تب؛ هر name فقط یک جا.
			if ( 'account' === $group || 'brand' === $group ) {
				continue;
			}

			$fields = array();

			foreach ( $extras as $key => $s ) {
				if ( ( isset( $s['group'] ) ? $s['group'] : 'type' ) !== $group ) {
					continue;
				}

				$fields[] = self::field(
					$opt,
					$key,
					self::ui_type( $s['type'] ),
					$s['label'],
					$values[ $key ],
					array(
						'help'    => $s['desc'],
						'default' => $s['default'],
						'risk'    => 0 === strpos( $key, 'disable_wc_' ) ? true : null,
					)
				);
			}

			if ( ! $fields ) {
				continue;
			}

			$card = array( 'id' => 'extras_' . $group, 'title' => $title, 'fields' => $fields );

			if ( 'woocommerce_assets' === $group ) {
				$card['advanced'] = true;
				$card['short']    = 'فایل‌های ووکامرس';
				$card['sub']      = 'هر گزینه یکی از فایل‌های CSS یا JavaScript خود ووکامرس را از صفحه‌ها حذف می‌کند. فقط وقتی روشن کنید که قالب یا ویجت‌های اردال جایگزین آن را دارند.';
			}

			$cards[] = $card;
		}

		return $cards;
	}

	/**
	 * @return array
	 */
	private static function account_cards() {
		$opt    = Ardal_Account_Settings::OPTION;
		$values = Ardal_Account_Settings::get_all();
		$texts  = Ardal_Account_Settings::dashboard_texts();
		$icons  = Ardal_Account::icon_options();
		$shorts = array(
			'welcome' => 'کارت خوش‌آمد',
			'stats'   => 'کارت‌های آمار',
			'alert'   => 'نوار هشدار',
			'track'   => 'پیگیری سفارش',
			'chart'   => 'نمودار خرید',
			'coupon'  => 'کد تخفیف',
			'suggest' => 'پیشنهاد محصول',
		);

		$rows = array();

		foreach ( Ardal_Account_Settings::menu_defaults() as $slug => $m ) {
			$rows[] = array(
				'slug'    => $slug,
				'default' => $m['label'],
				'on'      => '1' === (string) $values[ 'menu_' . $slug ],
				'label'   => (string) $values[ 'menu_' . $slug . '_label' ],
				'order'   => (int) $values[ 'menu_' . $slug . '_order' ],
				'names'   => array(
					'on'    => $opt . '[menu_' . $slug . ']',
					'label' => $opt . '[menu_' . $slug . '_label]',
					'order' => $opt . '[menu_' . $slug . '_order]',
				),
			);
		}

		usort(
			$rows,
			static function ( $a, $b ) {
				return $a['order'] - $b['order'];
			}
		);

		$cards = array(
			array(
				'id'    => 'menu',
				'title' => 'منوی پنل کاربری',
				'short' => 'منوی پنل',
				'kind'  => 'menu',
				'sub'   => 'برای تغییر ترتیب ردیف‌ها را بکشید یا از دکمه‌های بالا/پایین استفاده کنید.',
				'info'  => 'صفحه‌ای که ووکامرس نساخته باشد حتی با روشن‌بودن کلید هم در منو نمایش داده نمی‌شود.',
				'rows'  => $rows,
			),
		);

		foreach ( Ardal_Account_Settings::dashboard_sections() as $sec => $s ) {
			$fields = array();

			foreach ( $texts as $key => $t ) {
				if ( $t['section'] !== $sec ) {
					continue;
				}

				$type     = 'icon' === $t['type'] ? 'icon' : $t['type'];
				$fields[] = self::field(
					$opt,
					$key,
					$type,
					$t['label'],
					$values[ $key ],
					array(
						'help'       => isset( $t['desc'] ) ? $t['desc'] : '',
						'tokens'     => isset( $t['tokens'] ) ? $t['tokens'] : null,
						'empty_hide' => ! empty( $t['blank'] ) ? true : null,
						'options'    => 'icon' === $type ? $icons : null,
						'ph'         => $t['default'],
					)
				);
			}

			$cards[] = array(
				'id'     => 'dash_' . $sec,
				'title'  => 'پیشخوان — ' . $s['label'],
				'short'  => $shorts[ $sec ] ?? $s['label'],
				'sub'    => $s['desc'],
				'master' => self::field( $opt, 'dash_' . $sec, 'toggle', 'نمایش در پیشخوان', $values[ 'dash_' . $sec ] ),
				'off'    => 'این باکس در پیشخوان کاربر رندر نمی‌شود. برای ویرایش متن‌هایش روشنش کنید.',
				'fields' => $fields,
			);
		}

		$counts = array();

		foreach ( Ardal_Account_Settings::schema() as $key => $s ) {
			if ( 'dashboard' === $s['group'] ) {
				$counts[] = self::field( $opt, $key, self::ui_type( $s['type'] ), $s['label'], $values[ $key ], array( 'help' => $s['desc'] ) );
			}
		}

		$cards[] = array( 'id' => 'dashboard_counts', 'title' => 'پیشخوان — تعدادها', 'short' => 'تعدادها', 'fields' => $counts );

		$p   = Ardal_Palette::OPTION;
		$pv  = Ardal_Palette::get_all();
		$pex = Ardal_Palette::extras_schema();

		$cards[] = array(
			'id'     => 'support',
			'title'  => 'کارت پشتیبانی پنل',
			'short'  => 'کارت پشتیبانی',
			'sub'    => 'در ستون کناری پنل کاربری و کشوی موبایل',
			'fields' => array(
				self::field( $p, 'account_support_phone', 'text', $pex['account_support_phone']['label'], $pv['account_support_phone'], array( 'help' => $pex['account_support_phone']['desc'] ) ),
				self::field( $p, 'account_support_hours', 'text', $pex['account_support_hours']['label'], $pv['account_support_hours'], array( 'help' => $pex['account_support_hours']['desc'] ) ),
			),
		);

		return $cards;
	}

	/**
	 * @return array
	 */
	private static function wishlist_cards() {
		$schema = Ardal_Features_Settings::wishlist_schema();

		return array(
			array(
				'id'     => 'status',
				'title'  => $schema['status']['title'],
				'info'   => $schema['status']['hint'],
				'fields' => self::feature_fields( $schema['status']['fields'], 'wishlist' ),
			),
			array(
				'id'     => 'texts',
				'title'  => $schema['texts']['title'],
				'fields' => self::feature_fields( $schema['texts']['fields'], 'wishlist' ),
			),
			array(
				'id'     => 'page',
				'title'  => $schema['page']['title'],
				'short'  => 'صفحهٔ علاقه‌مندی',
				'info'   => $schema['page']['hint'],
				'fields' => self::feature_fields( $schema['page']['fields'], 'wishlist' ),
			),
			array(
				'id'    => 'shortcodes',
				'title' => 'شورت‌کدها و ویجت‌ها',
				'short' => 'شورت‌کدها',
				'kind'  => 'ref',
				'refs'  => array(
					'[ardal_wishlist_button]' => 'دکمهٔ قلب محصول جاری (یا id="12" برای محصول دلخواه، text="yes" برای نمایش متن).',
					'[ardal_wishlist]'        => 'فهرست علاقه‌مندی‌های کاربر در هر صفحه‌ای.',
					'[ardal_wishlist_count]'  => 'آیکون قلب با شمارنده برای هدر (hide_zero="no" برای نمایش صفر).',
				),
			),
		);
	}

	/**
	 * @return array
	 */
	private static function wallet_cards() {
		$schema = Ardal_Features_Settings::wallet_schema();
		$master = static function ( $fields, $key ) {
			foreach ( $fields as $i => $f ) {
				if ( $f['key'] === $key ) {
					return array( $f, array_values( array_diff_key( $fields, array( $i => 1 ) ) ) );
				}
			}

			return array( null, $fields );
		};

		$cards = array(
			array(
				'id'     => 'status',
				'title'  => $schema['status']['title'],
				'fields' => self::feature_fields( $schema['status']['fields'], 'wallet' ),
			),
			array(
				'id'     => 'topup',
				'title'  => $schema['topup']['title'],
				'info'   => $schema['topup']['hint'],
				'fields' => self::feature_fields( $schema['topup']['fields'], 'wallet' ),
			),
			array(
				'id'     => 'checkout',
				'title'  => $schema['checkout']['title'],
				'short'  => 'پرداخت در تسویه',
				'fields' => self::feature_fields( $schema['checkout']['fields'], 'wallet' ),
			),
		);

		list( $m, $rest ) = $master( self::feature_fields( $schema['cashback']['fields'], 'wallet' ), 'cashback' );
		$cards[]          = array(
			'id'     => 'cashback',
			'title'  => $schema['cashback']['title'],
			'info'   => $schema['cashback']['hint'],
			'master' => $m,
			'off'    => 'کش‌بگ خاموش است؛ تنظیماتش تا روشن‌شدن پنهان می‌ماند و مقدارها حفظ می‌شوند.',
			'fields' => $rest,
		);

		list( $m, $rest ) = $master( self::feature_fields( $schema['withdraw']['fields'], 'wallet' ), 'withdraw_enabled' );
		$cards[]          = array(
			'id'     => 'withdraw',
			'title'  => $schema['withdraw']['title'],
			'short'  => 'برداشت',
			'info'   => $schema['withdraw']['hint'],
			'master' => $m,
			'off'    => 'برداشت خاموش است؛ دکمهٔ «برداشت» در صفحهٔ کیف پول نمایش داده نمی‌شود.',
			'fields' => $rest,
		);

		// پیامک: هر رویداد یک ردیف تاشو با چهار فیلد.
		$opt    = Ardal_Wallet::OPTION;
		$tokens = array_keys( Ardal_Wallet_Sms::variables() );
		$events = array();

		foreach ( Ardal_Wallet_Sms::events() as $ev => $ev_label ) {
			$row    = isset( $schema['sms']['fields'][ 'sms_' . $ev ] ) ? $schema['sms']['fields'][ 'sms_' . $ev ] : array( 'label' => $ev_label );
			$wd     = 0 === strpos( $ev, 'withdraw' );
			$events[] = array(
				'event'   => $ev,
				'label'   => $row['label'],
				'on'      => self::field( $opt, 'sms_' . $ev, 'toggle', $row['label'], Ardal_Wallet::option( 'sms_' . $ev ) ),
				'pattern' => self::field( $opt, 'sms_' . $ev . '_pattern', 'text', 'کد پترن', Ardal_Wallet::option( 'sms_' . $ev . '_pattern' ), array( 'dir' => 'ltr', 'ph' => 'مثلاً 284512 — خالی = متن آزاد' ) ),
				'vars'    => self::field( $opt, 'sms_' . $ev . '_vars', 'text', 'متغیرهای پترن', Ardal_Wallet::option( 'sms_' . $ev . '_vars' ), array( 'dir' => 'ltr' ) ),
				'text'    => self::field( $opt, 'sms_' . $ev . '_text', 'textarea', 'متن آزاد', Ardal_Wallet::option( 'sms_' . $ev . '_text' ) ),
				'tokens'  => array_values(
					array_filter(
						$tokens,
						static function ( $t ) use ( $wd ) {
							return $wd || ! in_array( $t, array( '{ref}', '{reason}', '{iban}' ), true );
						}
					)
				),
			);
		}

		$cards[] = array(
			'id'     => 'sms',
			'title'  => $schema['sms']['title'],
			'short'  => 'پیامک',
			'kind'   => 'sms',
			'info'   => $schema['sms']['hint'],
			'master' => self::field( $opt, 'sms_enabled', 'toggle', 'پیامک کیف پول فعال است', Ardal_Wallet::option( 'sms_enabled' ) ),
			'off'    => 'پیامک کیف پول خاموش است. ارسال در پس‌زمینه انجام می‌شود تا بازگشت از درگاه پرداخت و شارژ گروهی کند نشود.',
			'events' => $events,
		);

		$cards[] = array(
			'id'    => 'shortcodes',
			'title' => 'شورت‌کدها و ویجت‌ها',
			'short' => 'شورت‌کدها',
			'kind'  => 'ref',
			'refs'  => array(
				'[ardal_wallet]'              => 'کارت موجودی و شارژ کیف پول (quick="50000,100000" art="no" button="شارژ").',
				'[ardal_wallet_balance]'      => 'فقط عدد موجودی با لینک به کیف پول (before="موجودی:" link="no").',
				'[ardal_wallet_transactions]' => 'تراکنش‌های کاربر (style="table" per_page="20" hide_cashback="yes").',
				'[ardal_wallet_cashback]'     => 'تاریخچهٔ کش‌بگ با وضعیت و تاریخ انقضا.',
				'[ardal_wallet_withdraw]'     => 'فرم برداشت از کیف پول و فهرست درخواست‌های کاربر.',
			),
		);

		return $cards;
	}

	/* --------------------------------------------------------------------- */
	/* رندر                                                                   */
	/* --------------------------------------------------------------------- */

	/**
	 * @param array $presets
	 */
	public static function render( $presets ) {
		$tabs = self::tabs();

		// پیام «تنظیمات ذخیره شد» بعد از بازگشت از options.php.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$saved = isset( $_GET['settings-updated'] ) && 'true' === $_GET['settings-updated'];
		?>
		<div class="ards" dir="rtl" lang="fa" data-ards<?php echo $saved ? ' data-ards-saved' : ''; ?>>
			<header class="ards-head">
				<div class="ards-head__top">
					<div class="ards-brand">
						<span class="ards-brand__mark" aria-hidden="true"><?php echo esc_html( Ardal_Brand::mark() ); ?></span>
						<span>
							<h1 class="ards-brand__title"><?php echo esc_html( 'تنظیمات ' . Ardal_Brand::name() ); ?></h1>
							<span class="ards-brand__sub">نسخهٔ <?php echo esc_html( Ardal_Helpers::fa_digits( ARDAL_VERSION, true ) ); ?> · ویجت‌های المنتور و ابزارهای فروشگاه</span>
						</span>
					</div>

					<div class="ards-search">
						<label for="ards-search" class="ards-sr">جستجو در تنظیمات</label>
						<input id="ards-search" type="search" autocomplete="off" placeholder="جستجو در همهٔ تنظیمات…   ( / )" data-ards-search />
						<div class="ards-search__list" role="listbox" hidden data-ards-results></div>
					</div>

					<div class="ards-status" aria-live="polite" data-ards-status>
						<span class="ards-status__dot"></span>
						<span data-ards-status-text>همهٔ تغییرات ذخیره شده</span>
					</div>
				</div>

				<div class="ards-tabs" role="tablist" aria-label="بخش‌های تنظیمات">
					<?php foreach ( $tabs as $i => $tab ) : ?>
						<button type="button" role="tab" class="ards-tab" id="ards-tab-<?php echo esc_attr( $tab['id'] ); ?>" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" aria-controls="ards-panel-<?php echo esc_attr( $tab['id'] ); ?>" tabindex="<?php echo 0 === $i ? '0' : '-1'; ?>" data-ards-tab="<?php echo esc_attr( $tab['id'] ); ?>">
							<span><?php echo esc_html( $tab['title'] ); ?></span>
							<span class="ards-tab__count" title="تغییر ذخیره نشده" hidden></span>
						</button>
					<?php endforeach; ?>
				</div>
			</header>

			<form method="post" action="options.php" id="ardal-form" class="ards-form" data-ards-form novalidate>
				<?php settings_fields( Ardal_Settings::GROUP ); ?>

				<?php foreach ( $tabs as $i => $tab ) : ?>
					<section class="ards-panel" id="ards-panel-<?php echo esc_attr( $tab['id'] ); ?>" role="tabpanel" aria-labelledby="ards-tab-<?php echo esc_attr( $tab['id'] ); ?>" data-ards-panel="<?php echo esc_attr( $tab['id'] ); ?>" data-title="<?php echo esc_attr( $tab['title'] ); ?>"<?php echo 0 === $i ? '' : ' hidden'; ?>>
						<div class="ards-intro">
							<h2 class="ards-intro__title"><?php echo esc_html( $tab['title'] ); ?></h2>
							<p class="ards-intro__lede"><?php echo esc_html( $tab['lede'] ); ?></p>
						</div>

						<?php
						if ( 'wallet' === $tab['id'] ) {
							if ( ! Ardal_Helpers::has_woo() ) {
								echo '<div class="ards-warn" role="status"><strong>کیف پول به ووکامرس نیاز دارد</strong>برای استفاده از کیف پول، شارژ آنلاین و کش‌بگ، افزونهٔ ووکامرس را نصب و فعال کنید.</div>';
								echo '</section>';
								continue;
							}

							self::render_wallet_strip();
						}
						?>

						<div class="ards-layout<?php echo 'palette' === $tab['id'] ? ' ards-layout--aside' : ''; ?>">
							<nav class="ards-subnav" aria-label="بخش‌های این صفحه">
								<?php foreach ( $tab['cards'] as $card ) : ?>
									<?php if ( ! empty( $card['advanced'] ) ) : ?>
										<div class="ards-subnav__div">پیشرفته</div>
									<?php endif; ?>
									<button type="button" class="ards-subnav__item" data-ards-jump="<?php echo esc_attr( self::anchor( $tab, $card ) ); ?>">
										<?php if ( ! empty( $card['master'] ) ) : ?>
											<span class="ards-subnav__state" data-ards-state="<?php echo esc_attr( $card['master']['name'] ); ?>"></span>
										<?php endif; ?>
										<span class="ards-subnav__label"><?php echo esc_html( self::short( $card ) ); ?></span>
										<span class="ards-dot" hidden></span>
									</button>
								<?php endforeach; ?>
							</nav>

							<div class="ards-jump">
								<label for="ards-jump-<?php echo esc_attr( $tab['id'] ); ?>">پرش به بخش</label>
								<select id="ards-jump-<?php echo esc_attr( $tab['id'] ); ?>" data-ards-jump-select>
									<?php foreach ( $tab['cards'] as $card ) : ?>
										<option value="<?php echo esc_attr( self::anchor( $tab, $card ) ); ?>"><?php echo esc_html( self::short( $card ) ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>

							<div class="ards-cards">
								<?php
								foreach ( $tab['cards'] as $card ) {
									self::render_card( $tab, $card, $presets );
								}
								?>
							</div>

							<?php
							if ( 'palette' === $tab['id'] ) {
								self::render_palette_aside();
							}
							?>
						</div>
					</section>
				<?php endforeach; ?>
			</form>

			<div class="ards-bar" role="region" aria-label="ذخیرهٔ تغییرات" data-ards-bar>
				<span class="ards-bar__dot"></span>
				<span class="ards-bar__text" data-ards-bar-text></span>
				<span class="ards-bar__tabs" data-ards-bar-tabs></span>
				<button type="button" class="ards-bar__revert" data-ards-revert>بازگردانی</button>
				<button type="submit" form="ardal-form" class="ards-bar__save" data-ards-save>ذخیره تغییرات</button>
			</div>

			<div class="ards-toast" role="status" aria-live="polite" hidden data-ards-toast>
				<span class="ards-toast__icon">✓</span><span data-ards-toast-text></span>
			</div>

			<div class="ards-modal" hidden data-ards-confirm>
				<div class="ards-modal__box" role="dialog" aria-modal="true" aria-labelledby="ards-confirm-title">
					<h3 id="ards-confirm-title">بازگشت به پالت دیزاین؟</h3>
					<p>همهٔ رنگ‌ها به مقدار پیش‌فرض دیزاین برمی‌گردند. تا «ذخیره تغییرات» را نزنید چیزی ثبت نمی‌شود.</p>
					<div class="ards-modal__actions">
						<button type="button" class="ards-btn" data-ards-confirm-no>انصراف</button>
						<button type="button" class="ards-btn ards-btn--danger" data-ards-confirm-yes>بازگردانی رنگ‌ها</button>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * @param array $tab
	 * @param array $card
	 * @return string
	 */
	private static function anchor( $tab, $card ) {
		return 'card-' . $tab['id'] . '-' . $card['id'];
	}

	/**
	 * @param array $card
	 * @return string
	 */
	private static function short( $card ) {
		return isset( $card['short'] ) ? $card['short'] : $card['title'];
	}

	/**
	 * نوار وضعیت امکانات کیف پول و لینک گزارش.
	 */
	private static function render_wallet_strip() {
		$features = array(
			'enabled'          => array( 'کیف پول', 'status' ),
			'topup_enabled'    => array( 'شارژ آنلاین', 'topup' ),
			'checkout'         => array( 'پرداخت در تسویه', 'checkout' ),
			'cashback'         => array( 'کش‌بگ', 'cashback' ),
			'withdraw_enabled' => array( 'برداشت', 'withdraw' ),
			'sms_enabled'      => array( 'پیامک', 'sms' ),
		);
		?>
		<div class="ards-strip">
			<div class="ards-strip__pills">
				<span class="ards-strip__cap">وضعیت امکانات:</span>
				<?php foreach ( $features as $key => $f ) : ?>
					<button type="button" class="ards-pill" data-ards-jump="card-wallet-<?php echo esc_attr( $f[1] ); ?>" data-ards-pill="<?php echo esc_attr( Ardal_Wallet::OPTION . '[' . $key . ']' ); ?>" data-label="<?php echo esc_attr( $f[0] ); ?>">
						<span class="ards-pill__dot"></span><span class="ards-pill__text"><?php echo esc_html( $f[0] ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>
			<a class="ards-strip__link" href="<?php echo esc_url( Ardal_Wallet_Report::url() ); ?>">
				<span><strong>گزارش کیف پول</strong><span>ابزارهای شارژ دستی و گروهی</span></span>
				<span aria-hidden="true">←</span>
			</a>
		</div>
		<?php
	}

	/**
	 * @param array $tab
	 * @param array $card
	 * @param array $presets
	 */
	private static function render_card( $tab, $card, $presets ) {
		$master = isset( $card['master'] ) ? $card['master'] : null;
		$on     = ! $master || '1' === $master['value'];
		$adv    = ! empty( $card['advanced'] );
		$class  = 'ards-card' . ( $adv ? ' ards-card--adv' : '' ) . ( $on ? '' : ' is-off' );
		$id     = self::anchor( $tab, $card );
		?>
		<?php if ( $adv ) : ?>
			<div class="ards-advsep"><span>پیشرفته</span></div>
		<?php endif; ?>
		<section class="<?php echo esc_attr( $class ); ?>" id="<?php echo esc_attr( $id ); ?>" aria-labelledby="<?php echo esc_attr( $id ); ?>-h" data-ards-card data-title="<?php echo esc_attr( $card['title'] ); ?>" data-short="<?php echo esc_attr( self::short( $card ) ); ?>"<?php echo $adv ? ' data-ards-adv-card' : ''; ?>>
			<div class="ards-card__head">
				<div class="ards-card__titles">
					<div class="ards-card__tl">
						<h3 id="<?php echo esc_attr( $id ); ?>-h"><?php echo esc_html( $card['title'] ); ?></h3>
						<?php if ( $adv ) : ?>
							<span class="ards-badge ards-badge--warn">ممکن است ظاهر فروشگاه را تغییر دهد</span>
							<span class="ards-badge ards-badge--risk" hidden data-ards-adv-on></span>
						<?php endif; ?>
						<span class="ards-dot" hidden></span>
					</div>
					<?php if ( ! empty( $card['sub'] ) ) : ?>
						<p class="ards-card__sub"><?php echo esc_html( $card['sub'] ); ?></p>
					<?php endif; ?>
				</div>

				<?php if ( $master ) : ?>
					<label class="ards-master">
						<span><?php echo esc_html( $master['label'] ); ?></span>
						<?php self::switch_input( $master, ' data-ards-master' ); ?>
					</label>
				<?php endif; ?>

				<?php if ( $adv ) : ?>
					<button type="button" class="ards-advbtn" aria-expanded="false" data-ards-adv data-count="<?php echo esc_attr( count( $card['fields'] ) ); ?>">نمایش <?php echo esc_html( Ardal_Helpers::fa_digits( count( $card['fields'] ), true ) ); ?> گزینه</button>
				<?php endif; ?>
			</div>

			<?php if ( $master ) : ?>
				<div class="ards-card__off"><?php echo esc_html( $card['off'] ); ?></div>
			<?php endif; ?>

			<div class="ards-card__body"<?php echo $adv ? ' hidden' : ''; ?> data-ards-body>
				<?php if ( ! empty( $card['info'] ) ) : ?>
					<div class="ards-info"><span class="ards-info__i" aria-hidden="true">i</span><span><?php echo esc_html( $card['info'] ); ?></span></div>
				<?php endif; ?>

				<?php
				switch ( isset( $card['kind'] ) ? $card['kind'] : '' ) {
					case 'presets':
						self::render_presets( $presets );
						break;

					case 'menu':
						self::render_menu( $card['rows'] );
						break;

					case 'sms':
						self::render_sms( $card['events'] );
						break;

					case 'ref':
						self::render_refs( $card['refs'] );
						break;

					default:
						echo '<div class="ards-grid">';
						foreach ( $card['fields'] as $field ) {
							self::render_field( $field );
						}
						echo '</div>';
				}
				?>
			</div>
		</section>
		<?php
	}

	/**
	 * چک‌باکس به شکل سوییچ.
	 *
	 * @param array  $f
	 * @param string $attrs
	 * @param string $id
	 */
	private static function switch_input( $f, $attrs = '', $id = '' ) {
		printf(
			'<span class="ards-sw%5$s"><input type="checkbox" role="switch" class="ards-sw__in" id="%1$s" name="%2$s" value="1"%3$s%4$s /><span class="ards-sw__knob" aria-hidden="true"></span></span>',
			esc_attr( '' !== $id ? $id : self::fid( $f['name'] ) ),
			esc_attr( $f['name'] ),
			checked( '1', $f['value'], false ),
			$attrs, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- ویژگی‌های ثابت.
			! empty( $f['risk'] ) ? ' ards-sw--risk' : ''
		);
	}

	/**
	 * @param string $name
	 * @return string
	 */
	private static function fid( $name ) {
		return 'f_' . preg_replace( '/[^a-z0-9_]/i', '_', $name );
	}

	/**
	 * ویژگی‌های مشترک دیوِ هر فیلد.
	 *
	 * @param array  $f
	 * @param string $class
	 * @return string
	 */
	private static function wrap_attrs( $f, $class ) {
		$attrs = sprintf(
			' class="ards-f ards-f--%1$s%2$s" id="w_%3$s" data-ards-field data-label="%4$s"',
			esc_attr( $class ),
			! empty( $f['risk'] ) ? ' ards-f--risk' : '',
			esc_attr( self::fid( $f['name'] ) ),
			esc_attr( $f['label'] )
		);

		if ( ! empty( $f['show_when'] ) ) {
			$attrs .= ' data-show-when="' . esc_attr( $f['show_when'] ) . '"';
		}

		return $attrs;
	}

	/**
	 * @param array $f
	 */
	private static function help( $f ) {
		if ( '' !== (string) $f['help'] ) {
			echo '<p class="ards-help" title="' . esc_attr( $f['help'] ) . '">' . esc_html( $f['help'] ) . '</p>';
		}
	}

	/**
	 * @param array  $f
	 * @param string $extra HTML کنار برچسب.
	 */
	private static function label_row( $f, $extra = '' ) {
		printf(
			'<div class="ards-f__lr"><label for="%1$s" class="ards-f__label">%2$s</label><span class="ards-dot" hidden></span>%3$s</div>',
			esc_attr( self::fid( $f['name'] ) ),
			esc_html( $f['label'] ),
			$extra // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- ثابت.
		);
	}

	/**
	 * @param array $f
	 */
	private static function render_field( $f ) {
		$id  = self::fid( $f['name'] );
		$dir = isset( $f['dir'] ) ? $f['dir'] : 'rtl';

		switch ( $f['type'] ) {
			case 'toggle':
				?>
				<div<?php echo self::wrap_attrs( $f, 'toggle' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<div class="ards-f__main">
						<div class="ards-f__lr"><label for="<?php echo esc_attr( $id ); ?>" class="ards-f__label"><?php echo esc_html( $f['label'] ); ?></label><span class="ards-dot" hidden></span></div>
						<?php self::help( $f ); ?>
					</div>
					<?php self::switch_input( $f ); ?>
				</div>
				<?php
				break;

			case 'textarea':
				?>
				<div<?php echo self::wrap_attrs( $f, 'area' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php self::label_row( $f ); ?>
					<textarea id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $f['name'] ); ?>" rows="2" class="ards-in ards-in--area" placeholder="<?php echo esc_attr( $f['ph'] ?? '' ); ?>"><?php echo esc_textarea( $f['value'] ); ?></textarea>
					<?php self::tokens( $f ); ?>
					<?php self::help( $f ); ?>
				</div>
				<?php
				break;

			case 'number':
				?>
				<div<?php echo self::wrap_attrs( $f, 'number' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php self::label_row( $f ); ?>
					<div class="ards-step">
						<button type="button" class="ards-step__btn" aria-label="افزایش" data-ards-step="1">+</button>
						<input id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $f['name'] ); ?>" type="text" dir="ltr" inputmode="decimal" autocomplete="off" value="<?php echo esc_attr( $f['value'] ); ?>" data-ards-num<?php echo isset( $f['min'] ) ? ' data-min="' . esc_attr( $f['min'] ) . '"' : ''; ?><?php echo isset( $f['max'] ) ? ' data-max="' . esc_attr( $f['max'] ) . '"' : ''; ?> data-step="<?php echo esc_attr( $f['step'] ?? 1 ); ?>" />
						<?php if ( ! empty( $f['unit'] ) ) : ?>
							<span class="ards-step__unit"><?php echo esc_html( $f['unit'] ); ?></span>
						<?php endif; ?>
						<button type="button" class="ards-step__btn" aria-label="کاهش" data-ards-step="-1">−</button>
					</div>
					<p class="ards-err" role="alert" hidden></p>
					<?php self::help( $f ); ?>
				</div>
				<?php
				break;

			case 'money':
				?>
				<div<?php echo self::wrap_attrs( $f, 'money' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php self::label_row( $f ); ?>
					<div class="ards-money">
						<span class="ards-money__unit">تومان</span>
						<input id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $f['name'] ); ?>" type="text" dir="ltr" inputmode="numeric" autocomplete="off" value="<?php echo esc_attr( $f['value'] ); ?>" data-ards-money data-zero="<?php echo 0 === strpos( (string) $f['help'], 'صفر' ) ? '1' : ''; ?>" />
					</div>
					<span class="ards-words" data-ards-words></span>
					<?php self::help( $f ); ?>
				</div>
				<?php
				break;

			case 'select':
				?>
				<div<?php echo self::wrap_attrs( $f, 'select' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<div class="ards-f__lr"><span class="ards-f__label" id="<?php echo esc_attr( $id ); ?>-l"><?php echo esc_html( $f['label'] ); ?></span><span class="ards-dot" hidden></span></div>
					<div class="ards-radios" role="radiogroup" aria-labelledby="<?php echo esc_attr( $id ); ?>-l">
						<?php foreach ( (array) $f['options'] as $k => $l ) : ?>
							<label class="ards-radio">
								<input type="radio" name="<?php echo esc_attr( $f['name'] ); ?>" value="<?php echo esc_attr( $k ); ?>" <?php checked( (string) $k, (string) $f['value'] ); ?> />
								<span class="ards-radio__dot" aria-hidden="true"></span>
								<span><?php echo esc_html( $l ); ?></span>
							</label>
						<?php endforeach; ?>
					</div>
					<?php if ( '' !== (string) $f['help'] ) : ?>
						<p class="ards-help ards-help--full"><?php echo esc_html( $f['help'] ); ?></p>
					<?php endif; ?>
				</div>
				<?php
				break;

			case 'icon':
				?>
				<div<?php echo self::wrap_attrs( $f, 'icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php self::label_row( $f ); ?>
					<div class="ards-iconsel">
						<span class="ards-iconsel__pv" title="پیش‌نمایش آیکون" data-ards-icon-pv><?php echo Ardal_Account::icon( Ardal_Account_Settings::icon_name( $f['key'] ), 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $f['name'] ); ?>" class="ards-in" data-ards-icon>
							<?php foreach ( (array) $f['options'] as $k => $l ) : ?>
								<option value="<?php echo esc_attr( $k ); ?>" <?php selected( (string) $k, (string) $f['value'] ); ?>><?php echo esc_html( $l ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<?php
				break;

			case 'color':
				$hex = preg_match( '/^#[0-9a-f]{6}$/i', $f['value'] ) ? strtolower( $f['value'] ) : '#000000';
				?>
				<div<?php echo self::wrap_attrs( $f, 'color' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> title="<?php echo esc_attr( $f['help'] ); ?>" data-default="<?php echo esc_attr( strtoupper( $f['default'] ) ); ?>">
					<span class="ards-swatch" style="background:<?php echo esc_attr( $f['value'] ); ?>" data-ards-swatch>
						<input type="color" value="<?php echo esc_attr( $hex ); ?>" aria-label="<?php echo esc_attr( 'انتخاب رنگ ' . $f['label'] ); ?>" data-ards-picker />
					</span>
					<div class="ards-f__main">
						<div class="ards-f__lr"><label for="<?php echo esc_attr( $id ); ?>" class="ards-f__label"><?php echo esc_html( $f['label'] ); ?></label><span class="ards-dot" hidden></span></div>
						<div class="ards-help ards-help--line"><?php echo esc_html( $f['help'] ); ?></div>
					</div>
					<input id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $f['name'] ); ?>" type="text" dir="ltr" maxlength="7" class="ards-hex" value="<?php echo esc_attr( strtoupper( $f['value'] ) ); ?>" data-ards-hex data-key="<?php echo esc_attr( $f['key'] ); ?>" />
					<button type="button" class="ards-reset" title="بازگشت به پیش‌فرض" aria-label="بازگشت به رنگ پیش‌فرض" hidden data-ards-color-reset>↺</button>
				</div>
				<?php
				break;

			case 'image':
				?>
				<div<?php echo self::wrap_attrs( $f, 'image' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> data-ards-media>
					<div class="ards-f__lr"><span class="ards-f__label"><?php echo esc_html( $f['label'] ); ?></span><span class="ards-dot" hidden></span></div>
					<input type="hidden" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $f['name'] ); ?>" value="<?php echo esc_attr( $f['value'] ); ?>" data-ards-media-input />
					<button type="button" class="ards-media__empty" data-ards-media-pick<?php echo '' !== $f['value'] ? ' hidden' : ''; ?>>
						<span class="ards-media__ph"></span>
						<span><strong>انتخاب از کتابخانهٔ رسانه</strong><span><?php echo esc_html( $f['help'] ); ?></span></span>
					</button>
					<div class="ards-media__has"<?php echo '' === $f['value'] ? ' hidden' : ''; ?>>
						<img class="ards-media__img" src="<?php echo esc_url( $f['value'] ); ?>" alt="" data-ards-media-img />
						<span class="ards-media__url" dir="ltr" data-ards-media-url><?php echo esc_html( $f['value'] ); ?></span>
						<button type="button" class="ards-btn ards-btn--sm" data-ards-media-pick>تغییر</button>
						<button type="button" class="ards-btn ards-btn--sm ards-btn--dangerline" data-ards-media-clear>حذف</button>
					</div>
				</div>
				<?php
				break;

			default:
				$badge = ! empty( $f['empty_hide'] ) ? '<span class="ards-badge ards-badge--muted" hidden data-ards-empty-badge>در سایت نمایش داده نمی‌شود</span>' : '';
				$ph    = ! empty( $f['empty_hide'] ) ? 'خالی = نمایش داده نشود' : ( $f['ph'] ?? '' );
				?>
				<div<?php echo self::wrap_attrs( $f, 'text' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php self::label_row( $f, $badge ); ?>
					<input id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $f['name'] ); ?>" type="text" class="ards-in" dir="<?php echo esc_attr( $dir ); ?>" value="<?php echo esc_attr( $f['value'] ); ?>" placeholder="<?php echo esc_attr( $ph ); ?>"<?php echo ! empty( $f['empty_hide'] ) ? ' data-ards-empty-hide' : ''; ?> />
					<?php self::tokens( $f ); ?>
					<?php self::help( $f ); ?>
				</div>
				<?php
		}
	}

	/**
	 * چیپ‌های جای‌گذار زیر یک فیلد.
	 *
	 * @param array $f
	 */
	private static function tokens( $f ) {
		if ( empty( $f['tokens'] ) || ! preg_match_all( '/\{[a-z_]+\}/', $f['tokens'], $m ) ) {
			return;
		}

		echo '<div class="ards-tokens">';

		foreach ( array_unique( $m[0] ) as $t ) {
			printf( '<button type="button" class="ards-token" dir="ltr" title="درج در متن" data-ards-token="%1$s" data-target="%2$s">%1$s</button>', esc_attr( $t ), esc_attr( self::fid( $f['name'] ) ) );
		}

		echo '</div>';
	}

	/**
	 * @param array $presets
	 */
	private static function render_presets( $presets ) {
		?>
		<div class="ards-presets">
			<?php foreach ( $presets as $p ) : ?>
				<button type="button" class="ards-preset" aria-pressed="false" data-ards-preset="<?php echo esc_attr( $p['id'] ); ?>">
					<span class="ards-preset__sw">
						<?php foreach ( $p['swatch'] as $hex ) : ?>
							<span style="background:<?php echo esc_attr( $hex ); ?>"></span>
						<?php endforeach; ?>
					</span>
					<span class="ards-preset__name"><?php echo esc_html( $p['name'] ); ?><span class="ards-preset__on">فعال</span></span>
				</button>
			<?php endforeach; ?>
		</div>
		<div class="ards-presets__foot">
			<button type="button" class="ards-btn ards-btn--soft-danger" data-ards-reset-palette>بازگشت به پالت دیزاین</button>
		</div>
		<?php
	}

	/**
	 * @param array $rows
	 */
	private static function render_menu( $rows ) {
		$last = count( $rows ) - 1;
		?>
		<div class="ards-menu" role="list" aria-label="آیتم‌های منوی پنل کاربری" data-ards-menu>
			<div class="ards-menu__head" aria-hidden="true"><span></span><span>نمایش</span><span>عنوان در منو</span><span>ترتیب</span></div>
			<?php foreach ( $rows as $i => $r ) : ?>
				<div class="ards-menu__row" role="listitem" draggable="true" data-ards-menu-row data-default="<?php echo esc_attr( $r['default'] ); ?>">
					<span class="ards-menu__grip" aria-hidden="true" title="برای جابه‌جایی بکشید">⋮⋮</span>
					<?php self::switch_input( array( 'name' => $r['names']['on'], 'value' => $r['on'] ? '1' : '0' ), ' aria-label="' . esc_attr( 'نمایش «' . $r['default'] . '» در منو' ) . '" data-ards-menu-on' ); ?>
					<div class="ards-menu__label">
						<input type="text" class="ards-in ards-in--sm" name="<?php echo esc_attr( $r['names']['label'] ); ?>" value="<?php echo esc_attr( $r['label'] ); ?>" placeholder="<?php echo esc_attr( $r['default'] ); ?>" aria-label="<?php echo esc_attr( 'عنوان «' . $r['default'] . '» در منو' ); ?>" data-ards-menu-label />
						<span class="ards-menu__def" hidden>پیش‌فرض: <?php echo esc_html( $r['default'] ); ?></span>
					</div>
					<input type="hidden" name="<?php echo esc_attr( $r['names']['order'] ); ?>" value="<?php echo esc_attr( $r['order'] ); ?>" data-ards-menu-order />
					<span class="ards-menu__ord" title="مقدار فیلد ترتیب" data-ards-menu-ord><?php echo esc_html( Ardal_Helpers::fa_digits( $r['order'], true ) ); ?></span>
					<span class="ards-menu__mv">
						<button type="button" aria-label="<?php echo esc_attr( 'انتقال «' . $r['default'] . '» به بالا' ); ?>" data-ards-menu-move="-1"<?php disabled( 0 === $i ); ?>>▲</button>
						<button type="button" aria-label="<?php echo esc_attr( 'انتقال «' . $r['default'] . '» به پایین' ); ?>" data-ards-menu-move="1"<?php disabled( $last === $i ); ?>>▼</button>
					</span>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * @param array $events
	 */
	private static function render_sms( $events ) {
		self::nias_banner();
		?>
		<div class="ards-sms">
			<?php foreach ( $events as $i => $e ) : ?>
				<?php
				$open  = 0 === $i;
				$pid   = self::fid( $e['pattern']['name'] );
				$vid   = self::fid( $e['vars']['name'] );
				$tid   = self::fid( $e['text']['name'] );
				?>
				<div class="ards-ev<?php echo $open ? ' is-open' : ''; ?>" id="sms-<?php echo esc_attr( $e['event'] ); ?>" data-ards-ev="<?php echo esc_attr( $e['event'] ); ?>" data-label="<?php echo esc_attr( 'پیامک ' . $e['label'] ); ?>">
					<div class="ards-ev__head">
						<button type="button" class="ards-ev__open" aria-expanded="<?php echo $open ? 'true' : 'false'; ?>" data-ards-ev-open>
							<span class="ards-ev__chev" aria-hidden="true"></span>
							<span class="ards-ev__label"><?php echo esc_html( $e['label'] ); ?></span>
							<span class="ards-ev__mode" data-ards-ev-mode></span>
						</button>
						<?php self::switch_input( $e['on'], ' aria-label="' . esc_attr( $e['label'] ) . '" data-ards-ev-on' ); ?>
					</div>
					<div class="ards-ev__body"<?php echo $open ? '' : ' hidden'; ?> data-ards-ev-body>
						<div class="ards-f ards-f--text" id="w_<?php echo esc_attr( $pid ); ?>" data-ards-field data-label="<?php echo esc_attr( 'کد پترن — ' . $e['label'] ); ?>">
							<div class="ards-f__lr"><label for="<?php echo esc_attr( $pid ); ?>" class="ards-f__label">کد پترن</label><span class="ards-dot" hidden></span><span class="ards-badge ards-badge--ok" hidden data-ards-uses="pattern">در حال استفاده</span></div>
							<input id="<?php echo esc_attr( $pid ); ?>" name="<?php echo esc_attr( $e['pattern']['name'] ); ?>" type="text" dir="ltr" class="ards-in ards-in--mono" value="<?php echo esc_attr( $e['pattern']['value'] ); ?>" placeholder="<?php echo esc_attr( $e['pattern']['ph'] ); ?>" data-ards-ev-pattern />
						</div>
						<div class="ards-f ards-f--text" id="w_<?php echo esc_attr( $vid ); ?>" data-ards-field data-label="<?php echo esc_attr( 'متغیرهای پترن — ' . $e['label'] ); ?>">
							<div class="ards-f__lr"><label for="<?php echo esc_attr( $vid ); ?>" class="ards-f__label">متغیرهای پترن</label><span class="ards-dot" hidden></span></div>
							<input id="<?php echo esc_attr( $vid ); ?>" name="<?php echo esc_attr( $e['vars']['name'] ); ?>" type="text" dir="ltr" class="ards-in ards-in--mono" value="<?php echo esc_attr( $e['vars']['value'] ); ?>" />
							<p class="ards-help ards-help--full">به ترتیب پترن پنل و با کاما؛ «نام:{جای‌گذار}» برای درگاه‌های نام‌دار یا فقط «{جای‌گذار}».</p>
						</div>
						<div class="ards-f ards-f--area" id="w_<?php echo esc_attr( $tid ); ?>" data-ards-field data-label="<?php echo esc_attr( 'متن آزاد — ' . $e['label'] ); ?>">
							<div class="ards-f__lr"><label for="<?php echo esc_attr( $tid ); ?>" class="ards-f__label">متن آزاد</label><span class="ards-dot" hidden></span><span class="ards-badge ards-badge--ok" hidden data-ards-uses="text">در حال استفاده</span><span class="ards-ev__count" data-ards-ev-count></span></div>
							<textarea id="<?php echo esc_attr( $tid ); ?>" name="<?php echo esc_attr( $e['text']['name'] ); ?>" rows="3" class="ards-in ards-in--area" data-ards-ev-text><?php echo esc_textarea( $e['text']['value'] ); ?></textarea>
							<div class="ards-tokens">
								<span class="ards-tokens__cap">درج جای‌گذار:</span>
								<?php foreach ( $e['tokens'] as $t ) : ?>
									<button type="button" class="ards-token" dir="ltr" data-ards-token="<?php echo esc_attr( $t ); ?>" data-target="<?php echo esc_attr( $tid ); ?>" data-alt="<?php echo esc_attr( $vid ); ?>"><?php echo esc_html( $t ); ?></button>
								<?php endforeach; ?>
							</div>
						</div>
						<div class="ards-smstest" data-ards-sms-test="<?php echo esc_attr( $e['event'] ); ?>">
							<label for="sms_phone_<?php echo esc_attr( $e['event'] ); ?>">پیامک آزمایشی به</label>
							<input id="sms_phone_<?php echo esc_attr( $e['event'] ); ?>" type="tel" dir="ltr" class="ards-in ards-in--sm" placeholder="09121234567" data-ards-sms-phone />
							<button type="button" class="ards-btn ards-btn--outline" data-ards-sms-send><span class="ards-spin" hidden></span><span data-ards-sms-label>ارسال پیامک آزمایشی</span></button>
							<span class="ards-smstest__out" role="status" data-ards-sms-out></span>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * وضعیت اتصال به نیاس لاگین.
	 */
	private static function nias_banner() {
		if ( ! Ardal_Wallet_Sms::available() ) {
			echo '<div class="ards-nias ards-nias--bad" role="status"><span class="ards-nias__dot"></span><span>نیاس لاگین نسخهٔ ۱.۴ یا بالاتر نصب و فعال نیست؛ تا وقتی فعال نشود پیامک کیف پول فرستاده نمی‌شود.</span></div>';

			return;
		}

		$gateway = Ardal_Wallet_Sms::gateway();

		if ( ! Ardal_Wallet_Sms::configured() || ! $gateway ) {
			printf(
				'<div class="ards-nias ards-nias--warn" role="status"><span class="ards-nias__dot"></span><span>نیاس لاگین فعال است ولی درگاه پیامکی انتخاب نشده.</span><a href="%s">تنظیمات نیاس لاگین</a></div>',
				esc_url( admin_url( 'admin.php?page=niasloginsignup' ) )
			);

			return;
		}

		printf(
			'<div class="ards-nias ards-nias--ok" role="status"><span class="ards-nias__dot"></span><span>درگاه فعال در نیاس: %1$s — %2$s</span></div>',
			esc_html( $gateway['name'] ),
			esc_html( $gateway['supports_text'] ? 'هم پترن و هم متن آزاد' : 'فقط پترن؛ برای هر رویداد کد پترن را وارد کنید' )
		);
	}

	/**
	 * @param array $refs کد => توضیح.
	 */
	private static function render_refs( $refs ) {
		echo '<div class="ards-refs">';

		foreach ( $refs as $code => $desc ) {
			printf(
				'<div class="ards-ref"><code dir="ltr">%1$s</code><span>%2$s</span><button type="button" class="ards-btn ards-btn--sm" data-ards-copy="%1$s">کپی</button></div>',
				esc_html( $code ),
				esc_html( $desc )
			);
		}

		echo '</div>';
	}

	/**
	 * ستون پیش‌نمایش زنده و فهرست ویجت‌ها در تب پالت.
	 */
	private static function render_palette_aside() {
		$widgets = class_exists( 'Ardal_Elementor' ) ? Ardal_Elementor::widget_titles() : array();
		?>
		<aside class="ards-aside" aria-label="پیش‌نمایش">
			<div class="ards-box">
				<div class="ards-box__head"><strong>پیش‌نمایش زنده</strong><span>همزمان با تغییرات</span></div>
				<div class="ards-pv" data-ards-preview>
					<div class="ards-pv__top">ارسال رایگان برای خریدهای بالای <span data-pv-num="2">2</span> میلیون تومان</div>
					<div class="ards-pv__hero">
						<strong>حراج پاییزه</strong>
						<span>تا <span data-pv-num="40">40</span>٪ تخفیف پوشاک و کفش ورزشی</span>
						<span class="ards-pv__cta">خرید کنید</span>
					</div>
					<div class="ards-pv__grid">
						<div class="ards-pv__card">
							<div class="ards-pv__shot">product shot<span class="ards-pv__off"><span data-pv-num="25">25</span>٪</span></div>
							<span class="ards-pv__stars">★★★★☆</span>
							<span class="ards-pv__name">کفش رانینگ مدل آیرو</span>
							<span class="ards-pv__old" data-pv-num="2,520,000">2,520,000</span>
							<div class="ards-pv__row">
								<span class="ards-pv__price"><span data-pv-num="1,890,000">1,890,000</span> <span class="ards-pv__word" data-pv-word>تومان</span><span class="ards-pv__sym" data-pv-sym hidden><?php echo Ardal_Currency::symbol(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></span>
								<span class="ards-pv__fab">+</span>
							</div>
						</div>
						<div class="ards-pv__deal">
							<strong>پیشنهاد ویژه</strong>
							<div class="ards-pv__clock"><span data-pv-num="02">02</span><span data-pv-num="14">14</span><span data-pv-num="38">38</span></div>
							<span class="ards-pv__soft">ساک ورزشی <span data-pv-num="35">35</span> لیتری</span>
							<div class="ards-pv__bar"><span></span></div>
							<span class="ards-pv__soft"><span data-pv-num="64">64</span>٪ فروخته شده</span>
							<span class="ards-pv__see">مشاهده</span>
						</div>
					</div>
					<div class="ards-pv__chips"><span>دویدن</span><span>کوهنوردی</span><span>بدنسازی</span></div>
					<div class="ards-pv__foot"><span>درباره ما · تماس · قوانین</span><span>اسپرت‌لند</span></div>
				</div>
			</div>

			<div class="ards-box ards-box--pad">
				<div class="ards-box__row"><strong><?php echo esc_html( 'ویجت‌های ' . Ardal_Brand::name() . ' در المنتور' ); ?></strong><span data-ards-wcount><?php echo esc_html( Ardal_Helpers::fa_digits( count( $widgets ), true ) ); ?> ویجت</span></div>
				<input type="search" class="ards-in ards-in--sm" aria-label="فیلتر ویجت‌ها" placeholder="فیلتر ویجت‌ها…" data-ards-wfilter />
				<div class="ards-wlist" data-ards-wlist>
					<?php foreach ( $widgets as $title ) : ?>
						<span><?php echo esc_html( $title ); ?></span>
					<?php endforeach; ?>
					<?php if ( ! $widgets ) : ?>
						<em>المنتور فعال نیست، بنابراین ویجت‌ها ثبت نشده‌اند.</em>
					<?php endif; ?>
				</div>
			</div>
		</aside>
		<?php
	}
}
