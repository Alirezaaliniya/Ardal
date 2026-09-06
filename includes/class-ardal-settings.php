<?php
/**
 * صفحه تنظیمات پالت رنگی در پیشخوان وردپرس.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Settings {

	const PAGE  = 'ardal-settings';
	const GROUP = 'ardal_settings_group';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'register' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( ARDAL_FILE ), array( $this, 'action_links' ) );
	}

	/**
	 * @param array $links
	 * @return array
	 */
	public function action_links( $links ) {
		$url = admin_url( 'admin.php?page=' . self::PAGE );

		array_unshift( $links, '<a href="' . esc_url( $url ) . '">تنظیمات</a>' );

		return $links;
	}

	public function add_menu() {
		add_menu_page(
			'تنظیمات اردال',
			'اردال',
			'manage_options',
			self::PAGE,
			array( $this, 'render_page' ),
			'dashicons-art',
			59
		);
	}

	public function register() {
		register_setting(
			self::GROUP,
			Ardal_Palette::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( 'Ardal_Palette', 'sanitize' ),
				'default'           => Ardal_Palette::defaults(),
			)
		);
	}

	/**
	 * @param string $hook
	 */
	public function assets( $hook ) {
		if ( 'toplevel_page_' . self::PAGE !== $hook ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		wp_enqueue_style(
			'ardal-admin',
			ARDAL_URL . 'assets/admin/admin.css',
			array( 'wp-color-picker' ),
			ARDAL_VERSION
		);

		// همان متغیرهای سمت کاربر، تا پیش‌نمایش پیش از اجرای جاوااسکریپت هم درست باشد.
		wp_add_inline_style( 'ardal-admin', Ardal_Palette::css_vars() );

		wp_enqueue_script(
			'ardal-admin',
			ARDAL_URL . 'assets/admin/admin.js',
			array( 'jquery', 'wp-color-picker' ),
			ARDAL_VERSION,
			true
		);

		wp_localize_script(
			'ardal-admin',
			'ardalAdmin',
			array(
				'defaults'  => Ardal_Palette::defaults(),
				'optionKey' => Ardal_Palette::OPTION,
				'presets'   => $this->presets(),
				'confirm'   => 'همه رنگ‌ها به مقادیر پیش‌فرض دیزاین برگردند؟',
			)
		);
	}

	/**
	 * پالت‌های آماده. اولی دقیقاً همان دیزاین اصلی است.
	 *
	 * @return array
	 */
	public function presets() {
		$design = Ardal_Palette::defaults();

		return array(
			array(
				'id'     => 'design',
				'name'   => 'پیش‌فرض دیزاین (اسپرت‌لند)',
				'swatch' => array( $design['primary_dark'], $design['primary'], $design['accent'], $design['sale'] ),
				'values' => $design,
			),
			array(
				'id'     => 'ember',
				'name'   => 'نارنجی ورزشی',
				'swatch' => array( '#1F2A44', '#E8590C', '#FFB020', '#D6336C' ),
				'values' => array(
					'primary_dark'      => '#1F2A44',
					'primary'           => '#E8590C',
					'primary_light'     => '#FF922B',
					'accent'            => '#FFB020',
					'accent_dark'       => '#E09000',
					'accent_deep'       => '#8A5700',
					'sale'              => '#D6336C',
					'star'              => '#F0A500',
					'tint_blue'         => '#FFF1E6',
					'tint_blue_border'  => '#FFD8BC',
					'tint_green'        => '#FFF7E0',
					'tint_green_border' => '#FFE39B',
					'on_dark'           => '#FFE0CC',
				),
			),
			array(
				'id'     => 'teal',
				'name'   => 'فیروزه‌ای آرام',
				'swatch' => array( '#0B3B3C', '#0E7C86', '#4CC9A7', '#E4572E' ),
				'values' => array(
					'primary_dark'      => '#0B3B3C',
					'primary'           => '#0E7C86',
					'primary_light'     => '#2BB3C0',
					'accent'            => '#4CC9A7',
					'accent_dark'       => '#2FA184',
					'accent_deep'       => '#1B6B57',
					'sale'              => '#E4572E',
					'star'              => '#F2B705',
					'tint_blue'         => '#E4F5F7',
					'tint_blue_border'  => '#BFE6EB',
					'tint_green'        => '#E6F8F1',
					'tint_green_border' => '#BEE9DA',
					'on_dark'           => '#C8ECEF',
				),
			),
			array(
				'id'     => 'ink',
				'name'   => 'مشکی و طلایی',
				'swatch' => array( '#111418', '#2B2F36', '#C9A227', '#B3261E' ),
				'values' => array(
					'primary_dark'      => '#111418',
					'primary'           => '#2B2F36',
					'primary_light'     => '#4A515C',
					'accent'            => '#C9A227',
					'accent_dark'       => '#A8871B',
					'accent_deep'       => '#6E5A12',
					'sale'              => '#B3261E',
					'star'              => '#C9A227',
					'tint_blue'         => '#F1F2F4',
					'tint_blue_border'  => '#DCDEE3',
					'tint_green'        => '#FAF4E0',
					'tint_green_border' => '#EADFBB',
					'on_dark'           => '#D9DCE1',
				),
			),
		);
	}

	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$values  = Ardal_Palette::get_all();
		$schema  = Ardal_Palette::schema();
		$groups  = Ardal_Palette::groups();
		$extras  = Ardal_Palette::extras_schema();
		$opt     = Ardal_Palette::OPTION;
		$presets = $this->presets();
		?>
		<div class="wrap ardal-wrap" dir="rtl">
			<h1 class="ardal-title">
				<span class="ardal-title__mark"></span>
				تنظیمات پالت رنگی اردال
			</h1>
			<p class="ardal-lede">
				رنگ‌های زیر روی همه ویجت‌های اردال در المنتور اعمال می‌شوند. مقادیر پیش‌فرض دقیقاً همان پالت
				دیزاین «فروشگاه ورزشی» است؛ با تغییر هر رنگ، پیش‌نمایش کنار صفحه بلافاصله به‌روز می‌شود.
			</p>

			<form method="post" action="options.php" id="ardal-form">
				<?php settings_fields( self::GROUP ); ?>

				<div class="ardal-layout">
					<div class="ardal-main">

						<div class="ardal-card">
							<h2>پالت‌های آماده</h2>
							<p class="ardal-hint">با یک کلیک همه رنگ‌ها را جایگزین کنید. تا وقتی «ذخیره تغییرات» را نزنید چیزی ثبت نمی‌شود.</p>
							<div class="ardal-presets">
								<?php foreach ( $presets as $preset ) : ?>
									<button type="button" class="ardal-preset" data-preset="<?php echo esc_attr( $preset['id'] ); ?>">
										<span class="ardal-preset__swatches">
											<?php foreach ( $preset['swatch'] as $hex ) : ?>
												<i style="background:<?php echo esc_attr( $hex ); ?>"></i>
											<?php endforeach; ?>
										</span>
										<span class="ardal-preset__name"><?php echo esc_html( $preset['name'] ); ?></span>
									</button>
								<?php endforeach; ?>
							</div>
						</div>

						<?php foreach ( $groups as $group_key => $group_label ) : ?>
							<div class="ardal-card">
								<h2><?php echo esc_html( $group_label ); ?></h2>
								<div class="ardal-fields">
									<?php
									foreach ( $schema as $key => $field ) :
										if ( $field['group'] !== $group_key ) {
											continue;
										}
										$id = 'ardal-field-' . $key;
										?>
										<div class="ardal-field">
											<label for="<?php echo esc_attr( $id ); ?>">
												<span class="ardal-field__label"><?php echo esc_html( $field['label'] ); ?></span>
												<span class="ardal-field__desc"><?php echo esc_html( $field['desc'] ); ?></span>
											</label>
											<input
												type="text"
												id="<?php echo esc_attr( $id ); ?>"
												class="ardal-color"
												name="<?php echo esc_attr( $opt . '[' . $key . ']' ); ?>"
												value="<?php echo esc_attr( $values[ $key ] ); ?>"
												data-key="<?php echo esc_attr( $key ); ?>"
												data-default-color="<?php echo esc_attr( $field['default'] ); ?>"
											/>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endforeach; ?>

						<div class="ardal-card">
							<h2>تایپوگرافی و ابعاد</h2>
							<div class="ardal-fields ardal-fields--stack">
								<?php
								foreach ( $extras as $key => $field ) :
									$id   = 'ardal-field-' . $key;
									$name = $opt . '[' . $key . ']';
									?>
									<div class="ardal-field ardal-field--<?php echo esc_attr( $field['type'] ); ?>">
										<?php if ( 'checkbox' === $field['type'] ) : ?>
											<label for="<?php echo esc_attr( $id ); ?>" class="ardal-check">
												<input
													type="checkbox"
													id="<?php echo esc_attr( $id ); ?>"
													name="<?php echo esc_attr( $name ); ?>"
													value="1"
													<?php checked( '1', $values[ $key ] ); ?>
												/>
												<span>
													<span class="ardal-field__label"><?php echo esc_html( $field['label'] ); ?></span>
													<span class="ardal-field__desc"><?php echo esc_html( $field['desc'] ); ?></span>
												</span>
											</label>
										<?php else : ?>
											<label for="<?php echo esc_attr( $id ); ?>">
												<span class="ardal-field__label"><?php echo esc_html( $field['label'] ); ?></span>
												<span class="ardal-field__desc"><?php echo esc_html( $field['desc'] ); ?></span>
											</label>
											<input
												type="<?php echo esc_attr( $field['type'] ); ?>"
												id="<?php echo esc_attr( $id ); ?>"
												name="<?php echo esc_attr( $name ); ?>"
												value="<?php echo esc_attr( $values[ $key ] ); ?>"
												class="regular-text"
												data-key="<?php echo esc_attr( $key ); ?>"
											/>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>
						</div>

						<p class="ardal-actions">
							<?php submit_button( 'ذخیره تغییرات', 'primary', 'submit', false ); ?>
							<button type="button" class="button button-secondary" id="ardal-reset">بازگشت به پالت دیزاین</button>
						</p>
					</div>

					<aside class="ardal-side">
						<div class="ardal-card ardal-preview-card">
							<h2>پیش‌نمایش زنده</h2>
							<div class="ardal-preview" id="ardal-preview">
								<div class="ardal-pv-topbar">ارسال رایگان برای خرید بالای ۲٫۵۰۰٫۰۰۰ تومان</div>
								<div class="ardal-pv-hero">
									<span class="ardal-pv-tag">کالکشن پاییز</span>
									<strong>کفش دویدن حرفه‌ای</strong>
									<span class="ardal-pv-btn">مشاهده محصولات</span>
								</div>
								<div class="ardal-pv-card">
									<div class="ardal-pv-thumb">۸۰۰×۸۰۰<i class="ardal-pv-off">۱۸٪</i></div>
									<div class="ardal-pv-name">کفش دویدن ران‌فلکس ایر پرو</div>
									<div class="ardal-pv-stars">★★★★☆</div>
									<div class="ardal-pv-old">۵٫۹۰۰٫۰۰۰</div>
									<div class="ardal-pv-price">
										۴٫۸۵۰٫۰۰۰
										<span class="ard-currency ard-currency--text" data-toman="text">تومان</span>
										<span class="ard-currency ard-currency--icon" data-toman="icon" hidden><?php echo Ardal_Currency::symbol(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
									</div>
									<span class="ardal-pv-fab">+</span>
								</div>
								<div class="ardal-pv-deal">
									<div class="ardal-pv-deal-title">پیشنهاد ویژه امروز</div>
									<div class="ardal-pv-clock"><i>۰۸</i><i>۴۲</i><i>۱۵</i></div>
									<div class="ardal-pv-bar"><span></span></div>
								</div>
								<div class="ardal-pv-footer">© ۱۴۰۵ فروشگاه — تمامی حقوق محفوظ است</div>
							</div>
						</div>

						<div class="ardal-card">
							<h2>ویجت‌های در دسترس</h2>
							<p class="ardal-hint">در ویرایشگر المنتور، دسته «اردال — فروشگاه ورزشی».</p>
							<ol class="ardal-widget-list">
								<?php
								$titles = class_exists( 'Ardal_Elementor' ) ? Ardal_Elementor::widget_titles() : array();

								foreach ( $titles as $title ) :
									?>
									<li><?php echo esc_html( $title ); ?></li>
								<?php endforeach; ?>
							</ol>
							<?php if ( empty( $titles ) ) : ?>
								<p class="ardal-hint">المنتور فعال نیست، بنابراین ویجت‌ها ثبت نشده‌اند.</p>
							<?php endif; ?>
						</div>
					</aside>
				</div>
			</form>
		</div>
		<?php
	}
}
