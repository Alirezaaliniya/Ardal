<?php
/**
 * تب‌های «علاقه‌مندی» و «کیف پول» در صفحهٔ تنظیمات اردال.
 *
 * هر دو داخل همان فرم صفحهٔ تنظیمات‌اند و با یک «ذخیره تغییرات» ثبت
 * می‌شوند. فیلدها از یک طرح (schema) ساخته می‌شوند تا ظاهرشان با بقیهٔ
 * تب‌های اردال یکی باشد و افزودن گزینهٔ تازه فقط یک ردیف آرایه باشد.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Features_Settings {

	/* --------------------------------------------------------------------- */
	/* طرح فیلدها                                                             */
	/* --------------------------------------------------------------------- */

	/**
	 * کارت‌های تب علاقه‌مندی.
	 *
	 * option: کدام آپشن (wishlist | palette | account). show: فقط وقتی کلید
	 * دیگری از همان آپشن روشن است دیده شود.
	 *
	 * @return array
	 */
	public static function wishlist_schema() {
		return array(
			'status'   => array(
				'title'  => 'وضعیت و رفتار',
				'hint'   => 'علاقه‌مندی برای کاربر واردشده همیشه روی حسابش ذخیره می‌شود و هر کلیک همان لحظه ثبت می‌شود.',
				'fields' => array(
					'product_wishlist' => array( 'option' => 'palette', 'type' => 'checkbox', 'label' => 'علاقه‌مندی فعال است', 'desc' => 'دکمهٔ قلب در کارت محصول، گالری و باکس خرید، صفحهٔ «علاقه‌مندی‌ها» در پنل و ویجت‌های علاقه‌مندی. با خاموش‌کردن همه‌جا حذف می‌شود.' ),
					'guest_mode'       => array(
						'type'    => 'select',
						'label'   => 'کاربر مهمان (واردنشده)',
						'desc'    => 'در حالت «ذخیره در مرورگر»، فهرست مهمان بعد از ورود یک بار با حسابش ادغام می‌شود.',
						'options' => array(
							'local' => 'ذخیره در مرورگر خودش',
							'login' => 'پیام «اول وارد شو» با دکمهٔ ورود',
						),
					),
					'merge_on_login'   => array( 'type' => 'checkbox', 'label' => 'ادغام فهرست مهمان بعد از ورود', 'desc' => 'محصول‌هایی که پیش از ورود قلب خورده‌اند به حساب اضافه شوند.' ),
					'max'              => array( 'type' => 'number', 'label' => 'بیشترین تعداد قابل ذخیره', 'desc' => 'از این بیشتر، پیام «فهرست پر است» نمایش داده می‌شود.', 'min' => 1 ),
					'hide_unstock'     => array( 'type' => 'checkbox', 'label' => 'کالاهای ناموجود در فهرست نشان داده نشوند', 'desc' => 'در فهرست می‌مانند و با موجود شدن دوباره دیده می‌شوند.' ),
				),
			),
			'texts'    => array(
				'title'  => 'متن‌ها و پیام‌ها',
				'fields' => array(
					'toast'       => array( 'type' => 'checkbox', 'label' => 'پیام شناور بعد از افزودن و حذف', 'desc' => 'پیام ورود و خطا همیشه نمایش داده می‌شود.' ),
					'text_add'    => array( 'type' => 'text', 'label' => 'متن دکمه (افزودن)', 'desc' => 'برچسب و راهنمای دکمهٔ قلب.' ),
					'text_remove' => array( 'type' => 'text', 'label' => 'متن دکمه (حذف)' ),
					'msg_added'   => array( 'type' => 'text', 'label' => 'پیام افزودن', 'show' => 'toast' ),
					'msg_removed' => array( 'type' => 'text', 'label' => 'پیام حذف', 'show' => 'toast' ),
					'view_text'   => array( 'type' => 'text', 'label' => 'دکمهٔ «مشاهدهٔ فهرست» در پیام', 'show' => 'toast' ),
					'msg_login'   => array( 'type' => 'text', 'label' => 'پیام نیاز به ورود' ),
					'login_text'  => array( 'type' => 'text', 'label' => 'متن دکمهٔ ورود' ),
					'msg_full'    => array( 'type' => 'text', 'label' => 'پیام پر بودن فهرست' ),
				),
			),
			'page'     => array(
				'title'  => 'صفحهٔ علاقه‌مندی در پنل کاربری',
				'hint'   => 'نام آیتم منو و ترتیبش در تب «پنل کاربری» تنظیم می‌شود.',
				'fields' => array(
					'wishlist_title'       => array( 'option' => 'account', 'type' => 'text', 'label' => 'عنوان صفحه' ),
					'wishlist_columns'     => array( 'option' => 'account', 'type' => 'number', 'label' => 'تعداد ستون', 'desc' => 'در موبایل خودکار کمتر می‌شود.', 'min' => 1 ),
					'wishlist_add_all'     => array( 'option' => 'account', 'type' => 'checkbox', 'label' => 'دکمهٔ «افزودن همه به سبد»', 'desc' => 'وقتی دست‌کم یک کالای موجود در فهرست باشد.' ),
					'wishlist_empty_title' => array( 'option' => 'account', 'type' => 'text', 'label' => 'عنوان حالت خالی' ),
					'wishlist_empty_text'  => array( 'option' => 'account', 'type' => 'text', 'label' => 'توضیح حالت خالی' ),
					'wishlist_empty_btn'   => array( 'option' => 'account', 'type' => 'text', 'label' => 'متن دکمهٔ حالت خالی', 'desc' => 'خالی بگذارید تا دکمه حذف شود.' ),
				),
			),
		);
	}

	/**
	 * کارت‌های تب کیف پول.
	 *
	 * @return array
	 */
	public static function wallet_schema() {
		return array(
			'status'   => array(
				'title'  => 'وضعیت کلی',
				'fields' => array(
					'enabled' => array( 'type' => 'checkbox', 'label' => 'کیف پول فعال است', 'desc' => 'صفحهٔ کیف پول در پنل، پرداخت در تسویه، کش‌بگ، گزارش و ویجت‌ها. خاموش‌کردن موجودی کسی را پاک نمی‌کند.' ),
					'title'   => array( 'type' => 'text', 'label' => 'عنوان صفحهٔ کیف پول', 'desc' => 'عنوان بالای صفحه در پنل کاربری. نام آیتم منو در تب «پنل کاربری» است.' ),
				),
			),
			'topup'    => array(
				'title'  => 'افزایش اعتبار',
				'hint'   => 'شارژ با ساخت یک سفارش ووکامرس انجام می‌شود، پس همهٔ درگاه‌های فعال فروشگاه بدون تنظیم اضافه کار می‌کنند. اعتبار فقط بعد از پرداخت موفق اضافه می‌شود.',
				'fields' => array(
					'topup_enabled' => array( 'type' => 'checkbox', 'label' => 'کاربر بتواند کیف پولش را شارژ کند' ),
					'min_topup'     => array( 'type' => 'money', 'label' => 'کمترین مبلغ شارژ (تومان)', 'show' => 'topup_enabled' ),
					'max_topup'     => array( 'type' => 'money', 'label' => 'بیشترین مبلغ شارژ (تومان)', 'desc' => 'صفر یعنی بی‌سقف.', 'show' => 'topup_enabled' ),
					'quick_amounts' => array( 'type' => 'text', 'label' => 'مبلغ‌های پیشنهادی', 'desc' => 'با کاما جدا کنید؛ مثلاً 100000,200000,500000', 'show' => 'topup_enabled' ),
					'short_amounts' => array( 'type' => 'checkbox', 'label' => 'نمایش کوتاه مبلغ‌ها («۱۰۰ هزار تومان»)', 'show' => 'topup_enabled' ),
					'topup_note'    => array( 'type' => 'text', 'label' => 'متن راهنمای زیر کارت', 'desc' => 'خالی بگذارید تا نمایش داده نشود.' ),
					'hero_image'    => array( 'type' => 'image', 'label' => 'تصویر کیف پول', 'desc' => 'خالی بماند، طرح پیش‌فرض با رنگ‌های پالت نمایش داده می‌شود.' ),
				),
			),
			'checkout' => array(
				'title'  => 'پرداخت از کیف پول در تسویه حساب',
				'fields' => array(
					'checkout'        => array( 'type' => 'checkbox', 'label' => 'پرداخت از کیف پول در تسویه حساب', 'desc' => 'گزینهٔ «پرداخت از کیف پول» داخل باکس شیوهٔ پرداخت.' ),
					'partial'         => array( 'type' => 'checkbox', 'label' => 'پرداخت جزئی مجاز است', 'desc' => 'خاموش باشد، کیف پول فقط وقتی قابل استفاده است که کل مبلغ را بپوشاند.', 'show' => 'checkout' ),
					'max_percent'     => array( 'type' => 'number', 'label' => 'حداکثر درصد قابل پرداخت با کیف پول', 'desc' => 'از ۱ تا ۱۰۰. مثلاً ۵۰ یعنی نیمی از مبلغ سفارش.', 'min' => 1, 'max' => 100, 'show' => 'checkout' ),
					'auto_apply'      => array( 'type' => 'checkbox', 'label' => 'تیک کیف پول از اول زده باشد', 'show' => 'checkout' ),
					'checkout_label'  => array( 'type' => 'text', 'label' => 'عنوان ردیف در تسویه و فاکتور', 'show' => 'checkout' ),
					'refund_cancel'   => array( 'type' => 'checkbox', 'label' => 'بازگشت اعتبار با لغو سفارش', 'desc' => 'شامل لغو خودکار سفارش‌های پرداخت‌نشده هم می‌شود.' ),
					'refund_refunded' => array( 'type' => 'checkbox', 'label' => 'بازگشت اعتبار با مسترد شدن سفارش' ),
				),
			),
			'cashback' => array(
				'title'  => 'کش‌بگ',
				'hint'   => 'بخشی از مبلغ هر خرید به کیف پول مشتری برمی‌گردد. مبلغ از خود سفارش و پس از کسر تخفیف حساب می‌شود؛ سفارش شارژ کیف پول کش‌بگ ندارد.',
				'fields' => array(
					'cashback'           => array( 'type' => 'checkbox', 'label' => 'کش‌بگ خودکار فعال است' ),
					'cashback_percent'   => array( 'type' => 'number', 'label' => 'درصد کش‌بگ', 'step' => '0.1', 'min' => 0, 'max' => 100, 'show' => 'cashback' ),
					'cashback_max'       => array( 'type' => 'money', 'label' => 'سقف کش‌بگ هر سفارش (تومان)', 'desc' => 'صفر یعنی بی‌سقف.', 'show' => 'cashback' ),
					'cashback_min_total' => array( 'type' => 'money', 'label' => 'حداقل مبلغ سفارش (تومان)', 'show' => 'cashback' ),
					'cashback_status'    => array(
						'type'    => 'select',
						'label'   => 'زمان واریز کش‌بگ',
						'show'    => 'cashback',
						'options' => array(
							'completed'  => 'وقتی سفارش «تکمیل‌شده» شد',
							'processing' => 'وقتی سفارش «در حال انجام» شد',
						),
					),
					'cashback_days'      => array( 'type' => 'number', 'label' => 'مهلت استفاده (روز)', 'desc' => 'صفر یعنی بدون انقضا. کش‌بگ منقضی‌شده هر روز خودکار از موجودی کسر می‌شود.', 'min' => 0, 'show' => 'cashback' ),
					'cashback_on_wallet' => array( 'type' => 'checkbox', 'label' => 'به بخشی که با کیف پول پرداخت شده هم کش‌بگ تعلق بگیرد', 'show' => 'cashback' ),
				),
			),
			'sms'      => array(
				'title'  => 'پیامک (از درگاه نیاس لاگین)',
				'nias'   => true,
				'hint'   => 'اردال درگاه پیامکی خودش را ندارد؛ پیامک‌ها با همان درگاه و حسابی می‌روند که در تنظیمات نیاس لاگین انتخاب شده و در «گزارش پیام‌ها»ی نیاس هم ثبت می‌شوند. کد پترن پر باشد با پترن ارسال می‌شود، خالی باشد با متن آزاد (بیشتر پنل‌ها متن آزاد را فقط از خط اختصاصی می‌فرستند).',
				'fields' => array(
					'sms_enabled'          => array( 'type' => 'checkbox', 'label' => 'پیامک کیف پول فعال است', 'desc' => 'ارسال در پس‌زمینه انجام می‌شود تا بازگشت از درگاه پرداخت و شارژ گروهی کند نشود.' ),
					'sms_topup'               => array( 'type' => 'checkbox', 'label' => 'بعد از شارژ موفق توسط خود کاربر', 'show' => 'sms_enabled', 'group' => 'topup' ),
					'sms_topup_pattern'       => array( 'type' => 'text', 'label' => 'کد پترن', 'desc' => 'خالی بماند، متن آزاد زیر فرستاده می‌شود.', 'show' => 'sms_topup', 'ltr' => true, 'test' => 'topup' ),
					'sms_topup_vars'          => array( 'type' => 'text', 'label' => 'متغیرهای پترن', 'desc' => 'به ترتیب پترن پنل و با کاما: «نام:{جای‌گذار}» برای درگاه‌های نام‌دار (کاوه‌نگار، SMS.ir) یا فقط «{جای‌گذار}» برای درگاه‌های ترتیبی. جای‌گذارها: {name} {amount} {amount_raw} {balance} {balance_raw} {site}', 'show' => 'sms_topup', 'ltr' => true ),
					'sms_topup_text'          => array( 'type' => 'textarea', 'label' => 'متن آزاد', 'desc' => 'جای‌گذارها: {name} {amount} {amount_raw} {balance} {balance_raw} {site}', 'show' => 'sms_topup' ),
					'sms_admin'               => array( 'type' => 'checkbox', 'label' => 'بعد از شارژ دستی یا گروهی توسط مدیر', 'show' => 'sms_enabled', 'group' => 'admin' ),
					'sms_admin_pattern'       => array( 'type' => 'text', 'label' => 'کد پترن', 'desc' => 'خالی بماند، متن آزاد زیر فرستاده می‌شود.', 'show' => 'sms_admin', 'ltr' => true, 'test' => 'admin' ),
					'sms_admin_vars'          => array( 'type' => 'text', 'label' => 'متغیرهای پترن', 'desc' => 'به ترتیب پترن پنل و با کاما: «نام:{جای‌گذار}» برای درگاه‌های نام‌دار (کاوه‌نگار، SMS.ir) یا فقط «{جای‌گذار}» برای درگاه‌های ترتیبی. جای‌گذارها: {name} {amount} {amount_raw} {balance} {balance_raw} {site}', 'show' => 'sms_admin', 'ltr' => true ),
					'sms_admin_text'          => array( 'type' => 'textarea', 'label' => 'متن آزاد', 'desc' => 'جای‌گذارها: {name} {amount} {amount_raw} {balance} {balance_raw} {site}', 'show' => 'sms_admin' ),
					'sms_cashback'            => array( 'type' => 'checkbox', 'label' => 'بعد از واریز کش‌بگ خرید', 'show' => 'sms_enabled', 'group' => 'cashback' ),
					'sms_cashback_pattern'    => array( 'type' => 'text', 'label' => 'کد پترن', 'desc' => 'خالی بماند، متن آزاد زیر فرستاده می‌شود.', 'show' => 'sms_cashback', 'ltr' => true, 'test' => 'cashback' ),
					'sms_cashback_vars'       => array( 'type' => 'text', 'label' => 'متغیرهای پترن', 'desc' => 'به ترتیب پترن پنل و با کاما: «نام:{جای‌گذار}» برای درگاه‌های نام‌دار (کاوه‌نگار، SMS.ir) یا فقط «{جای‌گذار}» برای درگاه‌های ترتیبی. جای‌گذارها: {name} {amount} {amount_raw} {balance} {balance_raw} {site}', 'show' => 'sms_cashback', 'ltr' => true ),
					'sms_cashback_text'       => array( 'type' => 'textarea', 'label' => 'متن آزاد', 'desc' => 'جای‌گذارها: {name} {amount} {amount_raw} {balance} {balance_raw} {site}', 'show' => 'sms_cashback' ),
				),
			),
		);
	}

	/* --------------------------------------------------------------------- */
	/* رندر                                                                   */
	/* --------------------------------------------------------------------- */

	/**
	 * نام فیلد و مقدار فعلی، بسته به آپشن.
	 *
	 * @param string $option
	 * @param string $key
	 * @return array name و value.
	 */
	private static function bind( $option, $key ) {
		switch ( $option ) {
			case 'palette':
				return array( Ardal_Palette::OPTION . '[' . $key . ']', Ardal_Palette::get( $key ) );

			case 'account':
				return array( Ardal_Account_Settings::OPTION . '[' . $key . ']', Ardal_Account_Settings::get( $key ) );

			case 'wallet':
				return array( Ardal_Wallet::OPTION . '[' . $key . ']', Ardal_Wallet::option( $key ) );

			default:
				return array( Ardal_Wishlist::OPTION . '[' . $key . ']', Ardal_Wishlist::option( $key ) );
		}
	}

	/**
	 * یک کارت تنظیمات.
	 *
	 * @param string $prefix  پیشوند شناسه‌ها.
	 * @param string $default آپشن پیش‌فرض فیلدها.
	 * @param array  $card
	 */
	private static function render_card( $prefix, $default, $card ) {
		?>
		<div class="ardal-card">
			<h2><?php echo esc_html( $card['title'] ); ?></h2>
			<?php if ( ! empty( $card['hint'] ) ) : ?>
				<p class="ardal-hint"><?php echo esc_html( $card['hint'] ); ?></p>
			<?php endif; ?>

			<?php
			if ( ! empty( $card['nias'] ) ) {
				self::render_nias_status();
			}
			?>

			<div class="ardal-fields ardal-fields--stack">
				<?php
				foreach ( $card['fields'] as $key => $field ) {
					self::render_field( $prefix, isset( $field['option'] ) ? $field['option'] : $default, $key, $field );
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * @param string $prefix
	 * @param string $option
	 * @param string $key
	 * @param array  $field
	 */
	private static function render_field( $prefix, $option, $key, $field ) {
		list( $name, $value ) = self::bind( $option, $key );

		$id    = 'ardal-' . $prefix . '-' . $key;
		$desc  = isset( $field['desc'] ) ? $field['desc'] : '';
		$type  = $field['type'];
		$show  = '';

		if ( ! empty( $field['show'] ) ) {
			list( $show_name ) = self::bind( $option, $field['show'] );
			$show              = ' data-ardal-show="' . esc_attr( $show_name ) . '"';
		}
		?>
		<div class="ardal-field ardal-field--<?php echo esc_attr( 'money' === $type || 'image' === $type ? 'text' : $type ); ?>"<?php echo $show; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- بالا escape شده. ?>>
			<?php if ( 'checkbox' === $type ) : ?>
				<label for="<?php echo esc_attr( $id ); ?>" class="ardal-check">
					<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="1" <?php checked( '1', $value ); ?> />
					<span>
						<span class="ardal-field__label"><?php echo esc_html( $field['label'] ); ?></span>
						<?php if ( '' !== $desc ) : ?>
							<span class="ardal-field__desc"><?php echo esc_html( $desc ); ?></span>
						<?php endif; ?>
					</span>
				</label>
			<?php else : ?>
				<label for="<?php echo esc_attr( $id ); ?>">
					<span class="ardal-field__label"><?php echo esc_html( $field['label'] ); ?></span>
					<?php if ( '' !== $desc ) : ?>
						<span class="ardal-field__desc"><?php echo esc_html( $desc ); ?></span>
					<?php endif; ?>
				</label>

				<?php if ( 'select' === $type ) : ?>
					<select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>">
						<?php foreach ( $field['options'] as $opt_key => $opt_label ) : ?>
							<option value="<?php echo esc_attr( $opt_key ); ?>" <?php selected( (string) $opt_key, (string) $value ); ?>><?php echo esc_html( $opt_label ); ?></option>
						<?php endforeach; ?>
					</select>
				<?php elseif ( 'textarea' === $type ) : ?>
					<textarea id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" rows="3" class="large-text"<?php echo ! empty( $field['ltr'] ) ? ' dir="ltr"' : ''; ?>><?php echo esc_textarea( $value ); ?></textarea>
				<?php elseif ( 'image' === $type ) : ?>
					<span class="ardal-media">
						<input type="url" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" class="regular-text" dir="ltr" data-ardal-media-input />
						<button type="button" class="button" data-ardal-media-pick>انتخاب تصویر</button>
						<button type="button" class="button-link" data-ardal-media-clear>حذف</button>
					</span>
				<?php else : ?>
					<input
						type="<?php echo 'number' === $type ? 'number' : 'text'; ?>"
						id="<?php echo esc_attr( $id ); ?>"
						name="<?php echo esc_attr( $name ); ?>"
						value="<?php echo esc_attr( $value ); ?>"
						class="regular-text"
						<?php echo 'money' === $type ? 'inputmode="numeric"' : ''; ?>
						<?php echo isset( $field['min'] ) ? 'min="' . esc_attr( $field['min'] ) . '"' : ''; ?>
						<?php echo isset( $field['max'] ) ? 'max="' . esc_attr( $field['max'] ) . '"' : ''; ?>
						<?php echo isset( $field['step'] ) ? 'step="' . esc_attr( $field['step'] ) . '"' : ''; ?>
						<?php echo ! empty( $field['ltr'] ) ? 'dir="ltr"' : ''; ?>
					/>
				<?php endif; ?>

				<?php if ( ! empty( $field['test'] ) && Ardal_Wallet_Sms::available() ) : ?>
					<span class="ardal-sms-test" data-ardal-sms-test="<?php echo esc_attr( $field['test'] ); ?>">
						<input type="tel" class="regular-text" placeholder="شمارهٔ موبایل برای آزمایش" dir="ltr" data-ardal-sms-phone />
						<button type="button" class="button" data-ardal-sms-send>ارسال پیامک آزمایشی</button>
						<span class="ardal-sms-test__out" data-ardal-sms-out role="status"></span>
					</span>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * وضعیت اتصال به نیاس لاگین بالای کارت پیامک.
	 */
	private static function render_nias_status() {
		if ( ! Ardal_Wallet_Sms::available() ) {
			echo '<div class="ardal-nias ardal-nias--bad">نیاس لاگین نسخهٔ ۱.۴ یا بالاتر نصب و فعال نیست؛ تا وقتی فعال نشود پیامک کیف پول فرستاده نمی‌شود. توابع ارسال پیامک برای افزونه‌های دیگر از همین نسخه به نیاس اضافه شده‌اند.</div>';

			return;
		}

		$gateway = Ardal_Wallet_Sms::gateway();

		if ( ! Ardal_Wallet_Sms::configured() || ! $gateway ) {
			printf(
				'<div class="ardal-nias ardal-nias--bad">نیاس لاگین فعال است ولی درگاه پیامکی در تنظیماتش انتخاب نشده. <a href="%s">تنظیمات نیاس لاگین ←</a></div>',
				esc_url( admin_url( 'admin.php?page=niasloginsignup' ) )
			);

			return;
		}

		printf(
			'<div class="ardal-nias ardal-nias--ok">درگاه فعال در نیاس: <strong>%1$s</strong> — %2$s</div>',
			esc_html( $gateway['name'] ),
			esc_html( $gateway['supports_text'] ? 'هم پترن و هم متن آزاد را پشتیبانی می‌کند.' : 'فقط پترن؛ برای هر رویداد کد پترن را وارد کنید.' )
		);

		wp_localize_script(
			'ardal-admin',
			'ardalSmsTest',
			array(
				'ajax'  => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'ardal-wallet-sms-test' ),
				'name'  => Ardal_Wallet::OPTION,
			)
		);
	}

	/**
	 * فهرست شورت‌کدها برای کارت راهنما.
	 *
	 * @param array $rows کد => توضیح.
	 */
	private static function render_shortcodes( $rows ) {
		?>
		<div class="ardal-card">
			<h2>شورت‌کدها و ویجت‌ها</h2>
			<p class="ardal-hint">در المنتور ویجت‌های همین بخش در دستهٔ «اردال — فروشگاه» هستند. در ویرایشگرهای دیگر از شورت‌کدها استفاده کنید.</p>
			<table class="ardal-table ardal-table--codes">
				<tbody>
					<?php foreach ( $rows as $code => $desc ) : ?>
						<tr>
							<td><code dir="ltr"><?php echo esc_html( $code ); ?></code></td>
							<td><?php echo esc_html( $desc ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	public static function render_wishlist_tab() {
		?>
		<p class="ardal-lede">
			رفتار دکمهٔ قلب، پیام‌ها و صفحهٔ علاقه‌مندی‌ها. ظاهر دکمه و فهرست در خودِ ویجت‌های المنتور تنظیم می‌شود و رنگ‌ها از پالت اردال می‌آیند.
		</p>

		<div class="ardal-layout ardal-layout--wide">
			<div class="ardal-main">
				<?php
				foreach ( self::wishlist_schema() as $card ) {
					self::render_card( 'wish', 'wishlist', $card );
				}

				self::render_shortcodes(
					array(
						'[ardal_wishlist_button]'          => 'دکمهٔ قلب محصول جاری (یا id="12" برای محصول دلخواه، text="yes" برای نمایش متن).',
						'[ardal_wishlist]'                 => 'فهرست علاقه‌مندی‌های کاربر در هر صفحه‌ای.',
						'[ardal_wishlist_count]'           => 'آیکون قلب با شمارنده برای هدر (hide_zero="no" برای نمایش صفر).',
					)
				);
				?>

				<?php submit_button( 'ذخیره تغییرات' ); ?>
			</div>
		</div>
		<?php
	}

	public static function render_wallet_tab() {
		if ( ! Ardal_Helpers::has_woo() ) {
			echo '<div class="ardal-card"><p class="ardal-hint">کیف پول به ووکامرس نیاز دارد و ووکامرس فعال نیست.</p></div>';

			return;
		}
		?>
		<p class="ardal-lede">
			کیف پول، شارژ آنلاین، پرداخت در تسویه، کش‌بگ و پیامک.
			<a href="<?php echo esc_url( Ardal_Wallet_Report::url() ); ?>">گزارش کیف پول و ابزارهای شارژ دستی و گروهی ←</a>
		</p>

		<div class="ardal-layout ardal-layout--wide">
			<div class="ardal-main">
				<?php
				foreach ( self::wallet_schema() as $card ) {
					self::render_card( 'wallet', 'wallet', $card );
				}

				self::render_shortcodes(
					array(
						'[ardal_wallet]'                    => 'کارت موجودی و شارژ کیف پول (quick="50000,100000" art="no" button="شارژ").',
						'[ardal_wallet_balance]'            => 'فقط عدد موجودی با لینک به کیف پول (before="موجودی:" link="no").',
						'[ardal_wallet_transactions]'       => 'تراکنش‌های کاربر (style="table" per_page="20" hide_cashback="yes").',
						'[ardal_wallet_cashback]'           => 'تاریخچهٔ کش‌بگ با وضعیت و تاریخ انقضا.',
					)
				);
				?>

				<?php submit_button( 'ذخیره تغییرات' ); ?>
			</div>
		</div>
		<?php
	}
}
