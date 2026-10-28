<?php
/**
 * مگا منو — تنظیمات آیتم‌های «نمایش ← فهرست‌ها» و رندر آن‌ها.
 *
 * منو در خود وردپرس ساخته و تنظیم می‌شود؛ ویجت المنتور فقط همان منو را
 * به‌صورت داینامیک صدا می‌زند.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Mega_Menu {

	/**
	 * متای در حافظه برای آیتم‌های مجازی داینامیک منو.
	 *
	 * @var array
	 */
	public static $virtual_meta = array();

	/**
	 * پیشوند کلید متای آیتم‌های منو.
	 */
	const META_PREFIX = '_ardal_mm_';

	/**
	 * فیلدهای افزوده‌شده به هر آیتم منو: کلید => نوع پاک‌سازی.
	 *
	 * @return array
	 */
	public static function fields() {
		return array(
			'mega'               => 'switch',
			'cols'               => 'int',
			'icon'               => 'key',
			'icon_img'           => 'int',
			'badge'              => 'text',
			'badge_tone'         => 'key',
			'desc'               => 'text',
			'highlight'          => 'switch',
			'image'              => 'int',
			'image_url'          => 'url',
			'dynamic_enable'     => 'switch',
			'dynamic_tax'        => 'key',
			'dynamic_parent'     => 'signed_int',
			'dynamic_depth'      => 'int_allow_zero',
			'dynamic_hide_empty' => 'switch',
			'dynamic_orderby'    => 'key',
			'dynamic_count'      => 'switch',
			'dynamic_limit'      => 'int',
		);
	}

	/**
	 * رنگ‌های برچسب آیتم.
	 *
	 * @return array
	 */
	public static function badge_tones() {
		return array(
			'accent'  => 'سبز',
			'sale'    => 'قرمز',
			'primary' => 'آبی',
		);
	}

	public static function init() {
		add_action( 'wp_nav_menu_item_custom_fields', array( __CLASS__, 'render_fields' ), 10, 3 );
		add_action( 'wp_update_nav_menu_item', array( __CLASS__, 'save_fields' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_assets' ) );
		add_filter( 'wp_nav_menu_objects', array( __CLASS__, 'inject_dynamic_taxonomy_items' ), 20, 2 );
		add_action( 'admin_init', array( __CLASS__, 'add_nav_menu_meta_boxes' ) );
		add_action( 'wp_ajax_ardal_add_taxonomy_tree_to_menu', array( __CLASS__, 'ajax_add_taxonomy_tree' ) );
	}

	/**
	 * مقدار یک فیلد اردال روی آیتم منو.
	 *
	 * @param int    $item_id
	 * @param string $key
	 * @return string
	 */
	public static function get( $item_id, $key ) {
		$item_id = (int) $item_id;

		if ( $item_id < 0 && isset( self::$virtual_meta[ $item_id ][ $key ] ) ) {
			return (string) self::$virtual_meta[ $item_id ][ $key ];
		}

		return (string) get_post_meta( $item_id, self::META_PREFIX . $key, true );
	}

	/**
	 * @param string $hook
	 */
	public static function admin_assets( $hook ) {
		if ( 'nav-menus.php' !== $hook ) {
			return;
		}

		wp_enqueue_media();

		wp_enqueue_style(
			'ardal-mega-menu-admin',
			ARDAL_URL . 'assets/admin/mega-menu.css',
			array(),
			ARDAL_VERSION
		);

		wp_enqueue_script(
			'ardal-mega-menu-admin',
			ARDAL_URL . 'assets/admin/mega-menu.js',
			array( 'jquery', 'jquery-ui-sortable' ),
			ARDAL_VERSION,
			true
		);

		$max_vars = (int) ini_get( 'max_input_vars' );
		if ( $max_vars <= 0 ) {
			$max_vars = 1000;
		}

		wp_localize_script(
			'ardal-mega-menu-admin',
			'ardalMegaMenuData',
			array(
				'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
				'nonce'        => wp_create_nonce( 'ardal-menu-admin-nonce' ),
				'maxInputVars' => $max_vars,
				'i18n'         => array(
					'bulkToolbarTitle' => Ardal_Brand::text( 'ابزارهای سازمان‌دهی دسته‌جمعی فهرست (اردال)' ),
					'selectedCount'    => 'مورد انتخاب شده',
					'selectAll'        => 'انتخاب همه',
					'deselectAll'      => 'لغو انتخاب',
					'selectSubtree'    => 'انتخاب زیرشاخه‌ها',
					'moveUnder'        => 'انتقال به زیر آیتم...',
					'applyMove'        => 'انتقال گروهی',
					'indent'           => 'یک سطح به داخل (راست)',
					'outdent'          => 'یک سطح به بیرون (چپ)',
					'deleteSelected'   => 'حذف موارد انتخابی',
					'confirmDelete'    => 'آیا از حذف %s آیتم انتخاب‌شده از فهرست مطمئن هستید؟',
					'noSelection'      => 'لطفاً ابتدا حداقل یک آیتم را با چک‌باکس انتخاب کنید.',
					'movedSuccess'     => '%s آیتم با موفقیت به زیر «%s» منتقل شدند.',
					'rootItem'         => 'سطح اصلی فهرست (بدون والد)',
					'varsSafe'         => 'مصرف فیلدها: %s از %s (وضعیت امن)',
					'varsWarning'      => 'هشدار: فیلدهای منو (%s از %s) به سقف سرور نزدیک است! برای دسته‌های پرشمار از «تزریق خودکار» استفاده کنید.',
					'addingTree'       => 'در حال افزودن شاخه به فهرست...',
					'treeSuccess'      => '%s دسته‌بندی با موفقیت و با حفظ کامل ساختار درختی افزوده شدند.',
				),
			)
		);
	}

	/**
	 * فیلدهای اردال زیر هر آیتم منو در پیشخوان.
	 *
	 * @param int    $item_id
	 * @param object $item
	 * @param int    $depth
	 */
	public static function render_fields( $item_id, $item, $depth ) {
		$item_id = (int) $item_id;
		$values  = array();

		foreach ( self::fields() as $key => $type ) {
			$values[ $key ] = self::get( $item_id, $key );
		}

		$image_id  = (int) $values['image'];
		$image_src = $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : '';
		$name      = 'ardal_mm[' . $item_id . ']';
		$id        = 'ardal-mm-' . $item_id;
		?>
		<div class="ardal-mm-fields" data-ardal-mm-fields>
			<?php wp_nonce_field( 'ardal-mm-save', 'ardal-mm-nonce', false ); ?>

			<p class="ardal-mm-head">تنظیمات اردال (مگا منو)</p>

			<?php if ( 0 === (int) $depth ) : ?>
				<p class="ardal-mm-row ardal-mm-row--check">
					<label for="<?php echo esc_attr( $id ); ?>-mega">
						<input
							type="checkbox"
							id="<?php echo esc_attr( $id ); ?>-mega"
							name="<?php echo esc_attr( $name ); ?>[mega]"
							value="1"
							data-ardal-mm-mega
							<?php checked( '1', $values['mega'] ); ?>
						>
						زیرمنو به‌صورت مگا منوی چندستونی نمایش داده شود
					</label>
				</p>

				<p class="ardal-mm-row ardal-mm-row--mega">
					<label for="<?php echo esc_attr( $id ); ?>-cols">تعداد ستون‌های پنل</label>
					<select id="<?php echo esc_attr( $id ); ?>-cols" name="<?php echo esc_attr( $name ); ?>[cols]">
						<?php foreach ( array( 2, 3, 4, 5 ) as $col ) : ?>
							<option value="<?php echo esc_attr( $col ); ?>" <?php selected( (string) $col, '' !== $values['cols'] ? $values['cols'] : '4' ); ?>>
								<?php echo esc_html( Ardal_Helpers::fa_digits( $col ) ); ?> ستون
							</option>
						<?php endforeach; ?>
					</select>
				</p>
			<?php endif; ?>

			<p class="ardal-mm-row">
				<label for="<?php echo esc_attr( $id ); ?>-icon">آیکون</label>
				<select id="<?php echo esc_attr( $id ); ?>-icon" name="<?php echo esc_attr( $name ); ?>[icon]">
					<option value="">بدون آیکون</option>
					<?php foreach ( Ardal_Helpers::icon_options() as $key => $label ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $values['icon'] ); ?>>
							<?php echo esc_html( $label ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>

			<?php
			$icon_img_id  = (int) $values['icon_img'];
			$icon_img_src = $icon_img_id ? wp_get_attachment_image_url( $icon_img_id, 'thumbnail' ) : '';

			// SVG معمولاً اندازهٔ میانی ندارد؛ نشانی اصلی فایل جایگزین می‌شود.
			if ( $icon_img_id && ! $icon_img_src ) {
				$icon_img_src = wp_get_attachment_url( $icon_img_id );
			}
			?>
			<div class="ardal-mm-row ardal-mm-media ardal-mm-media--icon" data-ardal-mm-media>
				<label>یا آیکون دلخواه (SVG یا PNG)</label>

				<span class="ardal-mm-media__preview" data-ardal-mm-preview>
					<?php if ( $icon_img_src ) : ?>
						<img src="<?php echo esc_url( $icon_img_src ); ?>" alt="">
					<?php endif; ?>
				</span>

				<input
					type="hidden"
					name="<?php echo esc_attr( $name ); ?>[icon_img]"
					value="<?php echo esc_attr( $icon_img_id ? $icon_img_id : '' ); ?>"
					data-ardal-mm-image
				>

				<span class="ardal-mm-media__actions">
					<button type="button" class="button" data-ardal-mm-pick>انتخاب آیکون</button>
					<button type="button" class="button-link" data-ardal-mm-clear>حذف</button>
				</span>
				<span class="description">اگر انتخاب شود، جای آیکون بالا می‌نشیند. در «منوی دسته‌بندی اردال» آیکون تک‌رنگ با رنگ متن رنگ می‌گیرد.</span>
			</div>

			<p class="ardal-mm-row">
				<label for="<?php echo esc_attr( $id ); ?>-desc">توضیح کوتاه</label>
				<input
					type="text"
					id="<?php echo esc_attr( $id ); ?>-desc"
					name="<?php echo esc_attr( $name ); ?>[desc]"
					value="<?php echo esc_attr( $values['desc'] ); ?>"
					placeholder="زیر عنوان آیتم نمایش داده می‌شود"
				>
			</p>

			<p class="ardal-mm-row ardal-mm-row--split">
				<span>
					<label for="<?php echo esc_attr( $id ); ?>-badge">برچسب</label>
					<input
						type="text"
						id="<?php echo esc_attr( $id ); ?>-badge"
						name="<?php echo esc_attr( $name ); ?>[badge]"
						value="<?php echo esc_attr( $values['badge'] ); ?>"
						placeholder="جدید"
					>
				</span>
				<span>
					<label for="<?php echo esc_attr( $id ); ?>-badge-tone">رنگ برچسب</label>
					<select id="<?php echo esc_attr( $id ); ?>-badge-tone" name="<?php echo esc_attr( $name ); ?>[badge_tone]">
						<?php foreach ( self::badge_tones() as $key => $label ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, '' !== $values['badge_tone'] ? $values['badge_tone'] : 'accent' ); ?>>
								<?php echo esc_html( $label ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</span>
			</p>

			<p class="ardal-mm-row ardal-mm-row--check">
				<label for="<?php echo esc_attr( $id ); ?>-highlight">
					<input
						type="checkbox"
						id="<?php echo esc_attr( $id ); ?>-highlight"
						name="<?php echo esc_attr( $name ); ?>[highlight]"
						value="1"
						<?php checked( '1', $values['highlight'] ); ?>
					>
					متن ویژه (رنگ حراج)
				</label>
			</p>

			<div class="ardal-mm-row ardal-mm-media" data-ardal-mm-media>
				<label>بنر تبلیغاتی پنل</label>

				<span class="ardal-mm-media__preview" data-ardal-mm-preview>
					<?php if ( $image_src ) : ?>
						<img src="<?php echo esc_url( $image_src ); ?>" alt="">
					<?php endif; ?>
				</span>

				<input
					type="hidden"
					name="<?php echo esc_attr( $name ); ?>[image]"
					value="<?php echo esc_attr( $image_id ); ?>"
					data-ardal-mm-image
				>

				<span class="ardal-mm-media__actions">
					<button type="button" class="button" data-ardal-mm-pick>انتخاب تصویر</button>
					<button type="button" class="button-link" data-ardal-mm-clear>حذف</button>
				</span>

				<input
					type="text"
					class="ardal-mm-media__url"
					name="<?php echo esc_attr( $name ); ?>[image_url]"
					value="<?php echo esc_attr( $values['image_url'] ); ?>"
					placeholder="لینک بنر (اختیاری — پیش‌فرض: لینک همین آیتم)"
				>
			</div>

			<!-- بخش تزریق خودکار زیردسته‌ها (داینامیک) -->
			<div class="ardal-mm-dynamic-box" data-ardal-mm-dyn-box>
				<p class="ardal-mm-row ardal-mm-row--check ardal-mm-dyn-toggle">
					<label for="<?php echo esc_attr( $id ); ?>-dynamic-enable">
						<input
							type="checkbox"
							id="<?php echo esc_attr( $id ); ?>-dynamic-enable"
							name="<?php echo esc_attr( $name ); ?>[dynamic_enable]"
							value="1"
							data-ardal-mm-dynamic
							<?php checked( '1', $values['dynamic_enable'] ); ?>
						>
						<span class="ardal-mm-dyn-title">تزریق خودکار زیردسته‌ها به این آیتم (داینامیک)</span>
					</label>
					<span class="ardal-mm-dyn-badge">بدون نیاز به ساخت دستی</span>
				</p>

				<div class="ardal-mm-dyn-options" data-ardal-mm-dyn-options>
					<p class="ardal-mm-dyn-notice">
						با فعال بودن این گزینه، تمام زیردسته‌ها به‌صورت خودکار، لحظه‌ای و درختی از فروشگاه در این آیتم لود می‌شوند (کاملاً سازگار با مگامنو و بدون خطر محدودیت max_input_vars سرور).
					</p>

					<p class="ardal-mm-row">
						<label for="<?php echo esc_attr( $id ); ?>-dynamic-tax">تاکسونومی منبع</label>
						<select id="<?php echo esc_attr( $id ); ?>-dynamic-tax" name="<?php echo esc_attr( $name ); ?>[dynamic_tax]">
							<option value="product_cat" <?php selected( 'product_cat', '' !== $values['dynamic_tax'] ? $values['dynamic_tax'] : 'product_cat' ); ?>>
								دسته‌بندی محصولات (ووکامرس)
							</option>
							<option value="category" <?php selected( 'category', $values['dynamic_tax'] ); ?>>
								دسته‌بندی مقالات (نوشته‌ها)
							</option>
							<?php if ( taxonomy_exists( 'product_tag' ) ) : ?>
								<option value="product_tag" <?php selected( 'product_tag', $values['dynamic_tax'] ); ?>>
									برچسب‌های محصولات
								</option>
							<?php endif; ?>
						</select>
					</p>

					<p class="ardal-mm-row">
						<label for="<?php echo esc_attr( $id ); ?>-dynamic-parent">مبدأ زیردسته‌ها (دسته والد)</label>
						<select id="<?php echo esc_attr( $id ); ?>-dynamic-parent" name="<?php echo esc_attr( $name ); ?>[dynamic_parent]">
							<option value="0" <?php selected( '0', '' !== $values['dynamic_parent'] ? $values['dynamic_parent'] : '0' ); ?>>
								تمام دسته‌های سطح اول (ریشه)
							</option>
							<option value="-1" <?php selected( '-1', $values['dynamic_parent'] ); ?>>
								زیردسته‌های مستقیم همین آیتم (در صورتی که آیتم دسته باشد)
							</option>
							<?php
							$product_cats = self::get_term_tree_list( 'product_cat' );
							if ( ! empty( $product_cats ) ) :
							?>
								<optgroup label="دسته‌های محصولات ووکامرس">
									<?php foreach ( $product_cats as $pc ) : ?>
										<option value="<?php echo esc_attr( $pc['id'] ); ?>" <?php selected( (string) $pc['id'], $values['dynamic_parent'] ); ?>>
											<?php echo esc_html( str_repeat( '— ', $pc['level'] ) . $pc['name'] . ( $pc['count'] ? " ({$pc['count']})" : '' ) ); ?>
										</option>
									<?php endforeach; ?>
								</optgroup>
							<?php endif; ?>
						</select>
					</p>

					<div class="ardal-mm-row ardal-mm-row--split">
						<span>
							<label for="<?php echo esc_attr( $id ); ?>-dynamic-depth">عمق سلسله‌مراتب</label>
							<select id="<?php echo esc_attr( $id ); ?>-dynamic-depth" name="<?php echo esc_attr( $name ); ?>[dynamic_depth]">
								<option value="1" <?php selected( '1', '' !== $values['dynamic_depth'] ? $values['dynamic_depth'] : '1' ); ?>>
									۱ سطح (فقط فرزندان مستقیم)
								</option>
								<option value="2" <?php selected( '2', $values['dynamic_depth'] ); ?>>
									۲ سطح (فرزندان و نوه‌ها — عالی برای مگامنو)
								</option>
								<option value="0" <?php selected( '0', $values['dynamic_depth'] ); ?>>
									نامحدود (تمام زیرلایه‌ها)
								</option>
							</select>
						</span>

						<span>
							<label for="<?php echo esc_attr( $id ); ?>-dynamic-orderby">مرتب‌سازی</label>
							<select id="<?php echo esc_attr( $id ); ?>-dynamic-orderby" name="<?php echo esc_attr( $name ); ?>[dynamic_orderby]">
								<option value="name" <?php selected( 'name', '' !== $values['dynamic_orderby'] ? $values['dynamic_orderby'] : 'name' ); ?>>
									بر اساس نام (الفبا)
								</option>
								<option value="term_order" <?php selected( 'term_order', $values['dynamic_orderby'] ); ?>>
									ترتیب دستی چیدمان دسته‌ها
								</option>
								<option value="count" <?php selected( 'count', $values['dynamic_orderby'] ); ?>>
									بیشترین تعداد کالا
								</option>
							</select>
						</span>
					</div>

					<div class="ardal-mm-row ardal-mm-row--split">
						<span class="ardal-mm-row--check">
							<label for="<?php echo esc_attr( $id ); ?>-dynamic-count">
								<input
									type="checkbox"
									id="<?php echo esc_attr( $id ); ?>-dynamic-count"
									name="<?php echo esc_attr( $name ); ?>[dynamic_count]"
									value="1"
									<?php checked( '1', '' !== $values['dynamic_count'] ? $values['dynamic_count'] : '1' ); ?>
								>
								نمایش تعداد کالا در بج
							</label>
						</span>

						<span class="ardal-mm-row--check">
							<label for="<?php echo esc_attr( $id ); ?>-dynamic-hide-empty">
								<input
									type="checkbox"
									id="<?php echo esc_attr( $id ); ?>-dynamic-hide-empty"
									name="<?php echo esc_attr( $name ); ?>[dynamic_hide_empty]"
									value="1"
									<?php checked( '1', $values['dynamic_hide_empty'] ); ?>
								>
								پنهان کردن دسته‌های بدون کالا
							</label>
						</span>
					</div>

					<p class="ardal-mm-row">
						<label for="<?php echo esc_attr( $id ); ?>-dynamic-limit">حداکثر تعداد دسته‌های سطح اول</label>
						<input
							type="number"
							min="1"
							max="100"
							id="<?php echo esc_attr( $id ); ?>-dynamic-limit"
							name="<?php echo esc_attr( $name ); ?>[dynamic_limit]"
							value="<?php echo esc_attr( $values['dynamic_limit'] ); ?>"
							placeholder="مثال: 10 (خالی = بدون محدودیت)"
						>
					</p>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * ذخیرهٔ فیلدها هنگام ذخیرهٔ منو.
	 *
	 * @param int $menu_id
	 * @param int $item_id
	 */
	public static function save_fields( $menu_id, $item_id ) {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		// این هوک در مسیرهای دیگری (مثل افزودن آیتم با ایجکس) هم اجرا می‌شود؛
		// آنجا فیلدهای ما در POST نیستند و نباید مقدار قبلی پاک شود.
		if ( ! isset( $_POST['ardal-mm-nonce'], $_POST['ardal_mm'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['ardal-mm-nonce'] ) ), 'ardal-mm-save' ) ) {
			return;
		}

		$item_id = (int) $item_id;
		$all     = wp_unslash( $_POST['ardal_mm'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- تک‌تک فیلدها پایین‌تر پاک‌سازی می‌شوند.

		if ( ! is_array( $all ) || ! isset( $all[ $item_id ] ) || ! is_array( $all[ $item_id ] ) ) {
			return;
		}

		$posted = $all[ $item_id ];

		foreach ( self::fields() as $key => $type ) {
			$value = self::sanitize_field( isset( $posted[ $key ] ) ? $posted[ $key ] : '', $type );
			$meta  = self::META_PREFIX . $key;

			if ( '' === $value ) {
				delete_post_meta( $item_id, $meta );

				continue;
			}

			update_post_meta( $item_id, $meta, $value );
		}
	}

	/**
	 * @param mixed  $value
	 * @param string $type
	 * @return string
	 */
	private static function sanitize_field( $value, $type ) {
		if ( is_array( $value ) ) {
			return '';
		}

		$value = (string) $value;

		switch ( $type ) {
			case 'switch':
				return '1' === $value ? '1' : '';

			case 'int':
				$value = (int) $value;

				return $value > 0 ? (string) $value : '';

			case 'int_allow_zero':
				return is_numeric( $value ) && (int) $value >= 0 ? (string) (int) $value : '';

			case 'signed_int':
				return is_numeric( $value ) ? (string) (int) $value : '';

			case 'key':
				return sanitize_key( $value );

			case 'url':
				return esc_url_raw( trim( $value ) );

			default:
				return sanitize_text_field( $value );
		}
	}

	/**
	 * تزریق خودکار زیردسته‌های تاکسونومی به درخت منو.
	 *
	 * @param array $items
	 * @param object $args
	 * @return array
	 */
	public static function inject_dynamic_taxonomy_items( $items, $args = null ) {
		if ( is_admin() && ! wp_doing_ajax() ) {
			return $items;
		}

		if ( empty( $items ) || ! is_array( $items ) ) {
			return $items;
		}

		$new_items          = array();
		$virtual_id_counter = -10000;

		$queried_object  = get_queried_object();
		$current_term_id = ( $queried_object instanceof WP_Term ) ? (int) $queried_object->term_id : 0;

		foreach ( $items as $item ) {
			$new_items[] = $item;

			$dyn_enable = self::get( $item->ID, 'dynamic_enable' );
			if ( '1' !== $dyn_enable ) {
				continue;
			}

			$tax = self::get( $item->ID, 'dynamic_tax' );
			if ( empty( $tax ) || ! taxonomy_exists( $tax ) ) {
				$tax = taxonomy_exists( 'product_cat' ) ? 'product_cat' : 'category';
			}

			$parent_setting = self::get( $item->ID, 'dynamic_parent' );
			$parent_term_id = 0;

			if ( '-1' === $parent_setting || 'self' === $parent_setting ) {
				if ( 'taxonomy' === $item->type && (int) $item->object_id > 0 ) {
					$parent_term_id = (int) $item->object_id;
				}
			} elseif ( '' !== $parent_setting ) {
				$parent_term_id = (int) $parent_setting;
			}

			$depth_setting = self::get( $item->ID, 'dynamic_depth' );
			$max_depth     = '' !== $depth_setting ? (int) $depth_setting : 1;
			$hide_empty    = '1' === self::get( $item->ID, 'dynamic_hide_empty' );
			$orderby       = self::get( $item->ID, 'dynamic_orderby' );
			$orderby       = ! empty( $orderby ) ? $orderby : 'name';
			$show_count    = '1' === self::get( $item->ID, 'dynamic_count' );
			$limit         = (int) self::get( $item->ID, 'dynamic_limit' );

			$dynamic_tree = self::get_hierarchy_terms( $tax, $parent_term_id, $max_depth, $hide_empty, $orderby, $limit );

			if ( empty( $dynamic_tree ) ) {
				continue;
			}

			$term_virtual_map = array();

			foreach ( $dynamic_tree as $entry ) {
				$term    = $entry['term'];
				$t_depth = $entry['depth'];
				$p_term  = $entry['parent'];

				$v_id = --$virtual_id_counter;
				$term_virtual_map[ $term->term_id ] = $v_id;

				$menu_parent_id = ( 0 === $t_depth || ! isset( $term_virtual_map[ $p_term ] ) )
					? $item->ID
					: $term_virtual_map[ $p_term ];

				$link = get_term_link( $term, $tax );
				if ( is_wp_error( $link ) ) {
					$link = '';
				}

				$is_current = ( $current_term_id === (int) $term->term_id );

				$classes = array(
					'menu-item',
					'menu-item-type-taxonomy',
					'menu-item-object-' . esc_attr( $tax ),
					'ardal-dynamic-term',
				);

				if ( $is_current ) {
					$classes[] = 'current-menu-item';
				}

				$v_item = (object) array(
					'ID'                    => $v_id,
					'db_id'                 => $v_id,
					'title'                 => $term->name,
					'url'                   => $link,
					'menu_order'            => count( $new_items ) + 1,
					'menu_item_parent'      => $menu_parent_id,
					'type'                  => 'taxonomy',
					'type_label'            => get_taxonomy( $tax )->labels->singular_name,
					'object'                => $tax,
					'object_id'             => $term->term_id,
					'classes'               => $classes,
					'target'                => '',
					'attr_title'            => '',
					'description'           => (string) $term->description,
					'xfn'                   => '',
					'current'               => $is_current,
					'current_item_ancestor' => false,
					'current_item_parent'   => false,
				);

				if ( $show_count && $term->count > 0 ) {
					self::$virtual_meta[ $v_id ] = array(
						'badge'      => (string) $term->count,
						'badge_tone' => 'accent',
					);
				}

				$new_items[] = $v_item;
			}
		}

		return $new_items;
	}

	/**
	 * استخراج ساختار درختی ترم‌های یک تاکسونومی بر اساس عمق و والد.
	 *
	 * @param string $taxonomy
	 * @param int    $parent_term_id
	 * @param int    $max_depth (0 = unlimited, 1 = direct children, 2 = 2 levels)
	 * @param bool   $hide_empty
	 * @param string $orderby
	 * @param int    $limit
	 * @return array
	 */
	public static function get_hierarchy_terms( $taxonomy, $parent_term_id = 0, $max_depth = 1, $hide_empty = false, $orderby = 'name', $limit = 0 ) {
		$args = array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => $hide_empty,
		);

		if ( 'count' === $orderby ) {
			$args['orderby'] = 'count';
			$args['order']   = 'DESC';
		} elseif ( 'term_order' === $orderby && taxonomy_exists( 'product_cat' ) && 'product_cat' === $taxonomy ) {
			$args['orderby'] = 'menu_order';
			$args['order']   = 'ASC';
		} else {
			$args['orderby'] = 'name';
			$args['order']   = 'ASC';
		}

		$all_terms = get_terms( $args );
		if ( empty( $all_terms ) || is_wp_error( $all_terms ) ) {
			return array();
		}

		$by_parent = array();
		foreach ( $all_terms as $t ) {
			$p = (int) $t->parent;
			if ( ! isset( $by_parent[ $p ] ) ) {
				$by_parent[ $p ] = array();
			}
			$by_parent[ $p ][] = $t;
		}

		$result = array();
		self::flatten_term_tree( $by_parent, $parent_term_id, 0, $max_depth, $limit, $result );

		return $result;
	}

	private static function flatten_term_tree( &$by_parent, $current_parent, $current_depth, $max_depth, $limit, &$result ) {
		if ( ! isset( $by_parent[ $current_parent ] ) ) {
			return;
		}

		$terms = $by_parent[ $current_parent ];

		if ( 0 === $current_depth && $limit > 0 && count( $terms ) > $limit ) {
			$terms = array_slice( $terms, 0, $limit );
		}

		foreach ( $terms as $term ) {
			$result[] = array(
				'term'   => $term,
				'depth'  => $current_depth,
				'parent' => $current_parent,
			);

			if ( 0 === $max_depth || ( $current_depth + 1 ) < $max_depth ) {
				self::flatten_term_tree( $by_parent, (int) $term->term_id, $current_depth + 1, $max_depth, 0, $result );
			}
		}
	}

	/**
	 * دریافت لیست گزینه‌های دسته‌ها با تورفتگی درختی برای منوی کشویی.
	 *
	 * @param string $taxonomy
	 * @return array
	 */
	public static function get_term_tree_list( $taxonomy = 'product_cat' ) {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			return array();
		}

		$terms = get_terms( array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
		) );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return array();
		}

		$by_parent = array();
		foreach ( $terms as $t ) {
			$by_parent[ (int) $t->parent ][] = $t;
		}

		$list = array();
		self::recurse_term_list( $by_parent, 0, 0, $list );

		return $list;
	}

	private static function recurse_term_list( &$by_parent, $parent, $level, &$list ) {
		if ( ! isset( $by_parent[ $parent ] ) ) {
			return;
		}

		foreach ( $by_parent[ $parent ] as $t ) {
			$list[] = array(
				'id'     => (int) $t->term_id,
				'name'   => $t->name,
				'level'  => $level,
				'count'  => (int) $t->count,
				'parent' => (int) $t->parent,
			);

			self::recurse_term_list( $by_parent, (int) $t->term_id, $level + 1, $list );
		}
	}

	/**
	 * ثبت متاباکس اختصاصی «افزودن هوشمند دسته‌بندی‌ها» در صفحه فهرست‌ها.
	 */
	public static function add_nav_menu_meta_boxes() {
		add_meta_box(
			'ardal-taxonomy-tree-importer',
			Ardal_Brand::text( 'افزودن هوشمند دسته‌بندی‌ها (اردال)' ),
			array( __CLASS__, 'render_taxonomy_importer_metabox' ),
			'nav-menus',
			'side',
			'default'
		);
	}

	/**
	 * رندر محتوای متاباکس درون‌ریزی درختی دسته‌ها.
	 */
	public static function render_taxonomy_importer_metabox() {
		$product_cats = self::get_term_tree_list( 'product_cat' );
		?>
		<div id="ardal-taxonomy-importer-box" class="ardal-tax-box" data-ardal-tax-importer>
			<p class="ardal-tax-intro">
				افزودن یکجا و شاخه‌ای دسته‌بندی‌های محصولات به فهرست، همراه با تمام زیردسته‌ها و چینش خودکار والد و فرزند.
			</p>

			<div class="ardal-tax-toolbar">
				<input type="search" class="ardal-tax-search" placeholder="جستجو در دسته‌ها..." data-ardal-tax-search>
				<div class="ardal-tax-actions-quick">
					<button type="button" class="button-link" data-ardal-tax-select-all>انتخاب همه</button>
					<span class="sep">|</span>
					<button type="button" class="button-link" data-ardal-tax-deselect-all>لغو</button>
				</div>
			</div>

			<div class="ardal-tax-tree-wrap">
				<?php if ( empty( $product_cats ) ) : ?>
					<p class="ardal-empty">هیچ دسته‌بندی محصولی یافت نشد.</p>
				<?php else : ?>
					<ul class="ardal-tax-tree" data-ardal-tax-tree>
						<?php foreach ( $product_cats as $cat ) : ?>
							<li class="ardal-tax-node" data-level="<?php echo esc_attr( $cat['level'] ); ?>" data-term-id="<?php echo esc_attr( $cat['id'] ); ?>" data-parent="<?php echo esc_attr( $cat['parent'] ); ?>">
								<div class="ardal-tax-row" style="padding-right: <?php echo esc_attr( $cat['level'] * 16 + 6 ); ?>px;">
									<label>
										<input type="checkbox" value="<?php echo esc_attr( $cat['id'] ); ?>" data-cat-name="<?php echo esc_attr( $cat['name'] ); ?>">
										<span class="ardal-tax-name"><?php echo esc_html( $cat['name'] ); ?></span>
									</label>
									<?php if ( $cat['count'] > 0 ) : ?>
										<span class="ardal-tax-count" title="<?php echo esc_attr( $cat['count'] ); ?> محصول">
											<?php echo esc_html( Ardal_Helpers::fa_digits( $cat['count'] ) ); ?>
										</span>
									<?php endif; ?>
									<button type="button" class="button-link ardal-add-branch-btn" title="افزودن این دسته به همراه تمام زیرمجموعه‌هایش" data-ardal-add-branch="<?php echo esc_attr( $cat['id'] ); ?>">
										+ شاخه کامل
									</button>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<div class="ardal-tax-footer">
				<div class="ardal-tax-target-wrap">
					<label for="ardal-import-target-select">افزودن به عنوان زیرمجموعهٔ آیتم:</label>
					<select id="ardal-import-target-select" class="ardal-import-target-select" data-ardal-import-target>
						<option value="0">سطح اصلی فهرست (بدون والد — انتهای منو)</option>
					</select>
				</div>

				<label class="ardal-tax-hierarchy-opt">
					<input type="checkbox" checked data-ardal-keep-hierarchy value="1">
					حفظ خودکار ساختار درختی در منو
				</label>

				<div class="ardal-tax-submit-row">
					<button type="button" class="button button-primary" data-ardal-import-selected>
						افزودن انتخاب‌شده‌ها به فهرست
					</button>
					<span class="spinner" data-ardal-import-spinner></span>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * ایجکس افزودن درختی دسته‌بندی‌ها به فهرست.
	 */
	public static function ajax_add_taxonomy_tree() {
		check_ajax_referer( 'ardal-menu-admin-nonce', 'nonce' );

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_send_json_error( array( 'message' => 'دسترسی غیرمجاز.' ) );
		}

		$menu_id          = isset( $_POST['menu_id'] ) ? (int) $_POST['menu_id'] : 0;
		$taxonomy         = isset( $_POST['taxonomy'] ) ? sanitize_key( $_POST['taxonomy'] ) : 'product_cat';
		$term_ids         = isset( $_POST['term_ids'] ) ? array_map( 'intval', (array) $_POST['term_ids'] ) : array();
		$branch_id        = isset( $_POST['branch_id'] ) ? (int) $_POST['branch_id'] : 0;
		$hierarchy        = ! empty( $_POST['keep_hierarchy'] );
		$target_parent_id = isset( $_POST['target_menu_item_id'] ) ? (int) $_POST['target_menu_item_id'] : 0;

		if ( ! $menu_id || ! is_nav_menu( $menu_id ) ) {
			wp_send_json_error( array( 'message' => 'شناسه فهرست نامعتبر است.' ) );
		}

		if ( $branch_id > 0 ) {
			// اگر شاخه کامل انتخاب شده، خودش و همه اعقابش را جمع‌آوری می‌کنیم
			$all_branch_terms = self::get_hierarchy_terms( $taxonomy, (int) get_term( $branch_id, $taxonomy )->parent, 0, false );
			// پیدا کردن خود ترم و فرزندانش
			$term_ids = array();
			$branch_term = get_term( $branch_id, $taxonomy );
			if ( $branch_term && ! is_wp_error( $branch_term ) ) {
				$term_ids[] = (int) $branch_term->term_id;
				$child_terms = self::get_hierarchy_terms( $taxonomy, $branch_id, 0, false );
				foreach ( $child_terms as $ct ) {
					$term_ids[] = (int) $ct['term']->term_id;
				}
			}
		}

		if ( empty( $term_ids ) ) {
			wp_send_json_error( array( 'message' => 'هیچ دسته‌ای برای افزودن انتخاب نشده است.' ) );
		}

		// مرتب‌سازی ترم‌ها به ترتیب سلسله‌مراتب برای حفظ درست والد و فرزند
		$terms = get_terms( array(
			'taxonomy'   => $taxonomy,
			'include'    => $term_ids,
			'hide_empty' => false,
		) );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			wp_send_json_error( array( 'message' => 'دسته‌های مورد نظر یافت نشدند.' ) );
		}

		// چیدمان ترم‌ها از بالا به پایین
		$by_id = array();
		foreach ( $terms as $t ) {
			$by_id[ $t->term_id ] = $t;
		}

		// مپ term_id => nav_menu_item_id ایجاد شده
		$term_to_menu_id = array();
		$created_ids     = array();

		// ایجاد آیتم‌ها
		foreach ( $terms as $term ) {
			// اگر والد ترم در مجموعه فعلی وارد شده باشد، زیرمجموعه آن می‌شود؛
			// در غیر این صورت (سطح اول این دسته از ایمپورت)، والدش همان $target_parent_id کاربر است.
			$parent_menu_item_id = $target_parent_id;

			if ( $hierarchy && $term->parent > 0 && isset( $term_to_menu_id[ $term->parent ] ) ) {
				$parent_menu_item_id = $term_to_menu_id[ $term->parent ];
			}

			$item_data = array(
				'menu-item-object-id'   => $term->term_id,
				'menu-item-object'      => $taxonomy,
				'menu-item-parent-id'   => $parent_menu_item_id,
				'menu-item-position'    => 0,
				'menu-item-type'        => 'taxonomy',
				'menu-item-title'       => $term->name,
				'menu-item-url'         => get_term_link( $term ),
				'menu-item-description' => (string) $term->description,
				'menu-item-status'      => 'publish',
			);

			$menu_item_id = wp_update_nav_menu_item( $menu_id, 0, $item_data );

			if ( ! is_wp_error( $menu_item_id ) && $menu_item_id > 0 ) {
				$term_to_menu_id[ $term->term_id ] = $menu_item_id;
				$created_ids[]                      = $menu_item_id;
			}
		}

		if ( empty( $created_ids ) ) {
			wp_send_json_error( array( 'message' => 'خطا در ثبت آیتم‌ها در فهرست.' ) );
		}

		// ساخت مارک‌آپ HTML ویرایشگر با استفاده از Walker_Nav_Menu_Edit
		require_once ABSPATH . 'wp-admin/includes/nav-menu.php';

		$menu_items = array();
		foreach ( $created_ids as $id ) {
			$post_obj = get_post( $id );
			if ( $post_obj ) {
				$menu_items[] = wp_setup_nav_menu_item( $post_obj );
			}
		}

		$walker = new Walker_Nav_Menu_Edit();
		$html   = walk_nav_menu_tree( $menu_items, 0, (object) array( 'walker' => $walker ) );

		wp_send_json_success( array(
			'html'             => $html,
			'count'            => count( $created_ids ),
			'target_parent_id' => $target_parent_id,
		) );
	}

	/**
	 * فهرست منوهای ثبت‌شده برای کنترل SELECT المنتور.
	 *
	 * @return array
	 */
	public static function menu_options() {
		$menus   = wp_get_nav_menus();
		$options = array();

		if ( is_wp_error( $menus ) || empty( $menus ) ) {
			return $options;
		}

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	/**
	 * آیا این منو آیتمی دارد؟ برای پیام راهنمای ویجت.
	 *
	 * @param string $menu اسلاگ منو.
	 * @return bool
	 */
	public static function menu_exists( $menu ) {
		if ( '' === $menu ) {
			return false;
		}

		$object = wp_get_nav_menu_object( $menu );

		return $object && ! is_wp_error( $object );
	}

	/**
	 * رندر منو با واکر مگا منو.
	 *
	 * @param string $menu اسلاگ منو.
	 * @param array  $args آرگومان‌های اضافه برای wp_nav_menu.
	 */
	public static function render_menu( $menu, $args = array() ) {
		wp_nav_menu(
			array_merge(
				array(
					'menu'        => $menu,
					'container'   => false,
					'menu_class'  => 'ard-mm__list',
					'items_wrap'  => '<ul class="%2$s" data-ard-mm-list>%3$s</ul>',
					'depth'       => 4,
					'fallback_cb' => false,
					'walker'      => new Ardal_Mega_Menu_Walker(),
				),
				$args
			)
		);
	}
}

/**
 * واکر منو — مارک‌آپ مگا منوی دسکتاپ و کشوی موبایل (یک DOM مشترک).
 */
class Ardal_Mega_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * آیا در حال رندر زیرمنوی یک آیتم مگا هستیم.
	 *
	 * @var bool
	 */
	private $in_mega = false;

	/**
	 * دادهٔ آیتم مگای جاری — برای ستون‌بندی و بنر انتهای پنل.
	 *
	 * @var array
	 */
	private $mega = array();

	/**
	 * شمارهٔ آیتم سطح اول — برای پلکانی‌شدن ورود آیتم‌ها در پردهٔ موبایل.
	 *
	 * @var int
	 */
	private $top_index = 0;

	/**
	 * @param object $item
	 * @return bool
	 */
	private function has_children( $item ) {
		return in_array( 'menu-item-has-children', (array) $item->classes, true );
	}

	/**
	 * محتوای داخل لینک: آیکون، عنوان، توضیح و برچسب.
	 *
	 * @param object $item
	 * @param int    $icon_size
	 * @return string
	 */
	private function label( $item, $icon_size = 17 ) {
		$icon  = Ardal_Mega_Menu::get( $item->ID, 'icon' );
		$badge = Ardal_Mega_Menu::get( $item->ID, 'badge' );
		$tone  = Ardal_Mega_Menu::get( $item->ID, 'badge_tone' );
		$desc  = Ardal_Mega_Menu::get( $item->ID, 'desc' );
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$out = '';
		$img = (int) Ardal_Mega_Menu::get( $item->ID, 'icon_img' );
		$src = $img ? wp_get_attachment_url( $img ) : '';

		if ( $src ) {
			$out .= sprintf(
				'<span class="ard-mm__icon"><img src="%1$s" alt="" width="%2$d" height="%2$d" loading="lazy" style="width:%2$dpx;height:%2$dpx;object-fit:contain"></span>',
				esc_url( $src ),
				(int) $icon_size
			);
		} elseif ( '' !== $icon ) {
			$out .= '<span class="ard-mm__icon">' . Ardal_Helpers::icon( $icon, $icon_size ) . '</span>';
		}

		$out .= '<span class="ard-mm__text">' . esc_html( $title );

		if ( '' !== $desc ) {
			$out .= '<span class="ard-mm__desc">' . esc_html( $desc ) . '</span>';
		}

		$out .= '</span>';

		if ( '' !== $badge ) {
			$out .= sprintf(
				'<span class="ard-mm__badge ard-mm__badge--%s">%s</span>',
				esc_attr( '' !== $tone ? $tone : 'accent' ),
				esc_html( Ardal_Helpers::fa_digits( $badge ) )
			);
		}

		return $out;
	}

	/**
	 * ویژگی‌های تگ لینک آیتم.
	 *
	 * @param object $item
	 * @param string $class
	 * @return string
	 */
	private function link_attrs( $item, $class ) {
		$atts = ' class="' . esc_attr( $class ) . '"';
		$url  = ! empty( $item->url ) ? (string) $item->url : '';

		// آیتم بدون مقصد (یا با «#») نباید لینک واقعی باشد؛ فقط عنوان ستون است.
		if ( '' !== $url && '#' !== $url ) {
			$atts .= ' href="' . esc_url( $url ) . '"';
		}

		if ( ! empty( $item->target ) ) {
			$atts .= ' target="' . esc_attr( $item->target ) . '" rel="noopener"';
		}

		if ( ! empty( $item->xfn ) ) {
			$atts .= ' rel="' . esc_attr( $item->xfn ) . '"';
		}

		if ( ! empty( $item->attr_title ) ) {
			$atts .= ' title="' . esc_attr( $item->attr_title ) . '"';
		}

		return $atts;
	}

	/**
	 * دکمهٔ باز/بستن زیرمنو — روی دسکتاپ فلش و روی موبایل کلید آکاردئون.
	 *
	 * @param object $item
	 * @return string
	 */
	private function toggle( $item ) {
		return sprintf(
			'<button type="button" class="ard-mm__toggle" aria-expanded="false" aria-label="%s" data-ard-mm-toggle>%s</button>',
			esc_attr( 'باز و بسته کردن زیرمنوی ' . wp_strip_all_tags( $item->title ) ),
			Ardal_Helpers::icon( 'chevron', 16 )
		);
	}

	/**
	 * @param string   $output
	 * @param int      $depth
	 * @param stdClass $args
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 === $depth ) {
			if ( $this->in_mega ) {
				$cols = isset( $this->mega['cols'] ) ? (int) $this->mega['cols'] : 4;

				$output .= '<div class="ard-mm__panel ard-mm__panel--mega" data-ard-mm-panel>'
					. '<div class="ard-mm__panel-inner">'
					. '<ul class="ard-mm__cols ard-mm__cols--' . esc_attr( $cols ) . '">';

				return;
			}

			$output .= '<div class="ard-mm__panel ard-mm__panel--drop" data-ard-mm-panel>'
				. '<div class="ard-mm__panel-inner">'
				. '<ul class="ard-mm__drop">';

			return;
		}

		if ( $this->in_mega ) {
			$output .= '<ul class="ard-mm__sub">';

			return;
		}

		// زیرمنوی تودرتوی حالت کشویی هم پنل خودش را دارد تا مثل بقیه باز و بسته شود.
		$output .= '<div class="ard-mm__panel ard-mm__panel--drop" data-ard-mm-panel>'
			. '<div class="ard-mm__panel-inner">'
			. '<ul class="ard-mm__drop ard-mm__drop--nested">';
	}

	/**
	 * @param string   $output
	 * @param int      $depth
	 * @param stdClass $args
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</ul>';

		// ستون‌های داخل پنل مگا پوششی ندارند؛ بقیهٔ سطح‌ها داخل پنل‌اند.
		if ( $depth > 0 && $this->in_mega ) {
			return;
		}

		if ( 0 === $depth && $this->in_mega ) {
			$output .= $this->promo();
		}

		$output .= '</div></div>';
	}

	/**
	 * بنر تبلیغاتی کنار ستون‌های پنل مگا.
	 *
	 * @return string
	 */
	private function promo() {
		if ( empty( $this->mega['image'] ) ) {
			return '';
		}

		$image = wp_get_attachment_image(
			(int) $this->mega['image'],
			'medium_large',
			false,
			array(
				'class'   => 'ard-mm__promo-img',
				'loading' => 'lazy',
				'alt'     => '',
			)
		);

		if ( ! $image ) {
			return '';
		}

		$url = ! empty( $this->mega['image_url'] ) ? $this->mega['image_url'] : $this->mega['url'];

		if ( '' === $url || '#' === $url ) {
			return '<div class="ard-mm__promo">' . $image . '</div>';
		}

		return '<a class="ard-mm__promo" href="' . esc_url( $url ) . '">' . $image . '</a>';
	}

	/**
	 * @param string   $output
	 * @param object   $item
	 * @param int      $depth
	 * @param stdClass $args
	 * @param int      $id
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes  = array_filter( (array) $item->classes );
		$children = $this->has_children( $item );
		$mega     = 0 === $depth && '1' === Ardal_Mega_Menu::get( $item->ID, 'mega' );

		if ( 0 === $depth ) {
			$this->in_mega = $mega;
			$this->mega    = $mega
				? array(
					'cols'      => (int) Ardal_Mega_Menu::get( $item->ID, 'cols' ),
					'image'     => (int) Ardal_Mega_Menu::get( $item->ID, 'image' ),
					'image_url' => Ardal_Mega_Menu::get( $item->ID, 'image_url' ),
					'url'       => (string) $item->url,
				)
				: array();

			if ( $mega && $this->mega['cols'] < 2 ) {
				$this->mega['cols'] = 4;
			}
		}

		if ( '1' === Ardal_Mega_Menu::get( $item->ID, 'highlight' ) ) {
			$classes[] = 'is-highlight';
		}

		// ستون‌های پنل مگا ساختاری متفاوت از آیتم‌های معمولی دارند.
		if ( 1 === $depth && $this->in_mega ) {
			$classes[] = 'ard-mm__col';

			$output .= '<li class="' . esc_attr( implode( ' ', $classes ) ) . '">'
				. '<a' . $this->link_attrs( $item, 'ard-mm__col-title' ) . '>' . $this->label( $item, 16 ) . '</a>';

			return;
		}

		if ( $depth > 0 ) {
			$classes[] = $this->in_mega ? 'ard-mm__subitem' : 'ard-mm__dropitem';

			$output .= '<li class="' . esc_attr( implode( ' ', $classes ) ) . '"'
				. ( $children && ! $this->in_mega ? ' data-ard-mm-item' : '' ) . '>'
				. '<div class="ard-mm__row">'
				. '<a' . $this->link_attrs( $item, 'ard-mm__sublink' ) . '>' . $this->label( $item, 15 ) . '</a>';

			if ( $children && ! $this->in_mega ) {
				$output .= $this->toggle( $item );
			}

			$output .= '</div>';

			return;
		}

		$classes[] = 'ard-mm__item';

		if ( $children ) {
			$classes[] = $mega ? 'ard-mm__item--mega' : 'ard-mm__item--drop';
			$classes[] = 'ard-mm__item--parent';
		} elseif ( $mega && $this->mega['image'] ) {
			$classes[] = 'ard-mm__item--mega';
			$classes[] = 'ard-mm__item--parent';
		}

		$has_panel = $children || ( $mega && ! empty( $this->mega['image'] ) );

		$output .= '<li class="' . esc_attr( implode( ' ', $classes ) ) . '"'
			. ' style="--ard-mm-i:' . (int) $this->top_index++ . '"'
			. ( $has_panel ? ' data-ard-mm-item' : '' ) . '>'
			. '<div class="ard-mm__row">'
			. '<a' . $this->link_attrs( $item, 'ard-mm__link' ) . '>' . $this->label( $item ) . '</a>';

		if ( $has_panel ) {
			$output .= $this->toggle( $item );
		}

		$output .= '</div>';
	}

	/**
	 * @param string   $output
	 * @param object   $item
	 * @param int      $depth
	 * @param stdClass $args
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		// آیتم مگای بدون زیرمنو ولی دارای بنر: پنلی که فقط بنر را نشان می‌دهد.
		if ( 0 === $depth && $this->in_mega && ! $this->has_children( $item ) ) {
			$promo = $this->promo();

			if ( '' !== $promo ) {
				$output .= '<div class="ard-mm__panel ard-mm__panel--mega" data-ard-mm-panel>'
					. '<div class="ard-mm__panel-inner">' . $promo . '</div></div>';
			}
		}

		$output .= '</li>';

		if ( 0 === $depth ) {
			$this->in_mega = false;
			$this->mega    = array();
		}
	}
}
