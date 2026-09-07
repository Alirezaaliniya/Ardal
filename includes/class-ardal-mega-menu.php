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
			'mega'       => 'switch',
			'cols'       => 'int',
			'icon'       => 'key',
			'badge'      => 'text',
			'badge_tone' => 'key',
			'desc'       => 'text',
			'highlight'  => 'switch',
			'image'      => 'int',
			'image_url'  => 'url',
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
	}

	/**
	 * مقدار یک فیلد اردال روی آیتم منو.
	 *
	 * @param int    $item_id
	 * @param string $key
	 * @return string
	 */
	public static function get( $item_id, $key ) {
		return (string) get_post_meta( (int) $item_id, self::META_PREFIX . $key, true );
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
			array( 'jquery' ),
			ARDAL_VERSION,
			true
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

			case 'key':
				return sanitize_key( $value );

			case 'url':
				return esc_url_raw( trim( $value ) );

			default:
				return sanitize_text_field( $value );
		}
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

		if ( '' !== $icon ) {
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
