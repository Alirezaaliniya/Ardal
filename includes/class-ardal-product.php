<?php
/**
 * دسترسی به محصول جاری برای ویجت‌های صفحه محصول.
 *
 * همهٔ ویجت‌های صفحه محصول داده‌شان را از اینجا می‌گیرند تا در قالب تک‌محصول
 * المنتور، روی هر محصولی همان محصول نمایش داده شود.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Product {

	/**
	 * کش بر اساس شناسهٔ پست جاری، نه یک مقدار واحد؛ وگرنه در حلقه یا هر جا که
	 * بیش از یک محصول در یک ریکوئست رندر شود، محصول اول برای همه برمی‌گشت.
	 *
	 * @var array<int|string, WC_Product|null>
	 */
	private static $cache = array();

	/**
	 * محصول جاری.
	 *
	 * داخل ویرایشگر المنتور (که هنوز محصول واقعی‌ای در کار نیست) آخرین محصول
	 * منتشرشده برگردانده می‌شود تا طراح، ساختار واقعی را ببیند نه جای‌گاه خالی.
	 *
	 * @return WC_Product|null
	 */
	public static function current() {
		$id  = (int) get_the_ID();
		$key = $id > 0 ? $id : 'none';

		if ( array_key_exists( $key, self::$cache ) ) {
			return self::$cache[ $key ];
		}

		self::$cache[ $key ] = null;

		if ( ! Ardal_Helpers::has_woo() || ! function_exists( 'wc_get_product' ) ) {
			return null;
		}

		if ( $id > 0 && 'product' === get_post_type( $id ) ) {
			$product = wc_get_product( $id );

			if ( $product ) {
				self::$cache[ $key ] = $product;

				return $product;
			}
		}

		if ( self::is_editor() ) {
			$latest = wc_get_products(
				array(
					'limit'   => 1,
					'status'  => 'publish',
					'orderby' => 'date',
					'order'   => 'DESC',
				)
			);

			if ( ! empty( $latest ) ) {
				self::$cache[ $key ] = $latest[0];
			}
		}

		return self::$cache[ $key ];
	}

	/**
	 * آیا داخل ویرایشگر یا پیش‌نمایش المنتور هستیم؟
	 *
	 * @return bool
	 */
	public static function is_editor() {
		if ( ! class_exists( '\Elementor\Plugin' ) || ! isset( \Elementor\Plugin::$instance ) ) {
			return false;
		}

		$plugin = \Elementor\Plugin::$instance;

		if ( isset( $plugin->editor ) && $plugin->editor->is_edit_mode() ) {
			return true;
		}

		return isset( $plugin->preview ) && $plugin->preview->is_preview_mode();
	}

	/**
	 * تصاویر گالری: تصویر شاخص به‌علاوهٔ گالری محصول.
	 *
	 * @param WC_Product $product
	 * @param string     $size
	 * @return array آرایه‌ای از array{full:string, thumb:string, alt:string}
	 */
	/**
	 * آیا قابلیت علاقه‌مندی از تنظیمات افزونه روشن است؟
	 *
	 * @return bool
	 */
	public static function wishlist_on() {
		return '1' === Ardal_Palette::get( 'product_wishlist' );
	}

	/**
	 * آیا قابلیت مقایسه از تنظیمات افزونه روشن است؟
	 *
	 * @return bool
	 */
	public static function compare_on() {
		return '1' === Ardal_Palette::get( 'product_compare' );
	}

	/**
	 * آیا نمایش عنوان انگلیسی از تنظیمات افزونه روشن است؟
	 *
	 * @return bool
	 */
	public static function subtitle_on() {
		return '1' === Ardal_Palette::get( 'product_subtitle' );
	}

	/**
	 * عنوان انگلیسی محصول.
	 *
	 * اولویت با فیلد سفارشیِ خود محصول است تا هر محصول زیرعنوان خودش را
	 * داشته باشد؛ اگر نبود، متنی که در ویجت نوشته شده استفاده می‌شود.
	 *
	 * @param \WC_Product|null $product
	 * @param string           $fallback
	 * @return string
	 */
	public static function subtitle( $product, $fallback = '' ) {
		if ( ! self::subtitle_on() ) {
			return '';
		}

		$key = trim( (string) Ardal_Palette::get( 'product_subtitle_key' ) );

		if ( $product && '' !== $key ) {
			$meta = get_post_meta( $product->get_id(), $key, true );

			if ( '' !== trim( (string) $meta ) ) {
				return trim( (string) $meta );
			}
		}

		return trim( (string) $fallback );
	}

	public static function gallery( $product, $size = 'woocommerce_single' ) {
		$ids = array();

		if ( $product->get_image_id() ) {
			$ids[] = (int) $product->get_image_id();
		}

		foreach ( $product->get_gallery_image_ids() as $gid ) {
			$ids[] = (int) $gid;
		}

		$ids = array_values( array_unique( array_filter( $ids ) ) );
		$out = array();

		foreach ( $ids as $id ) {
			$full  = wp_get_attachment_image_src( $id, $size );
			$thumb = wp_get_attachment_image_src( $id, 'woocommerce_thumbnail' );
			// نسخهٔ اصلی برای لایت‌باکس و ذره‌بین لازم است؛ سایز
			// woocommerce_single در بعضی سایت‌ها خیلی کوچک تنظیم شده.
			$orig = wp_get_attachment_image_src( $id, 'full' );

			if ( ! $full ) {
				continue;
			}

			$out[] = array(
				'full'  => $full[0],
				'thumb' => $thumb ? $thumb[0] : $full[0],
				'large' => $orig ? $orig[0] : $full[0],
				'width' => isset( $full[1] ) ? (int) $full[1] : 0,
				'alt'   => (string) get_post_meta( $id, '_wp_attachment_image_alt', true ),
			);
		}

		return $out;
	}

	/**
	 * مشخصات محصول از ویژگی‌های ووکامرس.
	 *
	 * @param WC_Product $product
	 * @param int        $limit        صفر یعنی بدون محدودیت.
	 * @param bool       $visible_only فقط ویژگی‌هایی که تیک «نمایش در صفحه محصول» دارند.
	 * @return array آرایه‌ای از array{key:string, value:string}
	 */
	public static function attributes( $product, $limit = 0, $visible_only = false ) {
		$out = array();

		foreach ( $product->get_attributes() as $attribute ) {
			if ( ! $attribute instanceof WC_Product_Attribute ) {
				continue;
			}

			if ( $visible_only && ! $attribute->get_visible() ) {
				continue;
			}

			$name = wc_attribute_label( $attribute->get_name(), $product );

			if ( $attribute->is_taxonomy() ) {
				$terms  = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
				$values = is_wp_error( $terms ) ? array() : $terms;
			} else {
				$values = $attribute->get_options();
			}

			$values = array_filter( array_map( 'trim', (array) $values ) );

			if ( empty( $values ) ) {
				continue;
			}

			$out[] = array(
				'key'   => $name,
				'value' => implode( '، ', $values ),
			);

			if ( $limit > 0 && count( $out ) >= $limit ) {
				break;
			}
		}

		return $out;
	}

	/**
	 * گزینه‌های یک ویژگی که برای انتخاب (رنگ/سایز) به کار می‌رود.
	 *
	 * برای محصول متغیر، گزینه‌های ناموجود هم مشخص می‌شوند تا مثل دیزاین
	 * خط‌خورده نمایش داده شوند.
	 *
	 * @param WC_Product $product
	 * @param string     $needle بخشی از نام ویژگی، مثلاً «رنگ» یا «سایز».
	 * @return array{label:string, options:array}
	 */
	public static function variation_options( $product, $needle ) {
		$empty = array(
			'label'   => '',
			'options' => array(),
		);

		if ( '' === trim( $needle ) ) {
			return $empty;
		}

		/*
		 * دو فهرست جدا:
		 *   $exists    → این مقدار اصلاً واریاسیونی دارد یا نه
		 *   $available → واریاسیونی دارد که هم خریدنی باشد هم موجود
		 *
		 * تفاوتشان همان چیزی است که کاربر می‌بیند: مقداری که وجود دارد ولی
		 * همه‌جا ناموجود است باید خط‌خورده و غیرفعال دیده شود، نه اینکه از
		 * فهرست حذف شود؛ حذف کردنش این توهم را می‌سازد که فروشگاه آن رنگ یا
		 * سایز را اصلاً ندارد.
		 */
		$exists    = array();
		$available = array();

		if ( $product->is_type( 'variable' ) ) {
			foreach ( $product->get_available_variations() as $variation ) {
				$in_stock = ! empty( $variation['is_in_stock'] ) && ! empty( $variation['is_purchasable'] );

				foreach ( $variation['attributes'] as $attr_key => $attr_value ) {
					if ( '' === $attr_value ) {
						continue;
					}

					$exists[ $attr_key ][] = $attr_value;

					if ( $in_stock ) {
						$available[ $attr_key ][] = $attr_value;
					}
				}
			}
		}

		foreach ( $product->get_attributes() as $attribute ) {
			if ( ! $attribute instanceof WC_Product_Attribute ) {
				continue;
			}

			$label = wc_attribute_label( $attribute->get_name(), $product );

			if ( false === mb_stripos( $label, $needle ) && false === mb_stripos( $attribute->get_name(), $needle ) ) {
				continue;
			}

			$key  = 'attribute_' . sanitize_title( $attribute->get_name() );
			$have = isset( $available[ $key ] ) ? $available[ $key ] : null;
			$any  = isset( $exists[ $key ] ) ? $exists[ $key ] : null;

			$options = array();

			if ( $attribute->is_taxonomy() ) {
				$terms = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );

				if ( is_wp_error( $terms ) ) {
					$terms = array();
				}

				foreach ( $terms as $term ) {
					$options[] = array(
						'label'     => $term->name,
						'slug'      => $term->slug,
						'available' => null === $have || in_array( $term->slug, $have, true ),
						// «وجود دارد ولی ناموجود است» از «اصلاً نیست» جدا می‌ماند.
						'exists'    => null === $any || in_array( $term->slug, $any, true ),
					);
				}
			} else {
				foreach ( $attribute->get_options() as $option ) {
					$option = trim( $option );

					if ( '' === $option ) {
						continue;
					}

					$options[] = array(
						'label'     => $option,
						'slug'      => sanitize_title( $option ),
						'available' => null === $have || in_array( $option, $have, true ),
						'exists'    => null === $any || in_array( $option, $any, true ),
					);
				}
			}

			if ( empty( $options ) ) {
				continue;
			}

			return array(
				'label'   => $label,
				'options' => $options,
				'key'     => $key,
			);
		}

		return $empty;
	}

	/**
	 * واریاسیون‌ها، خلاصه‌شده برای جاوااسکریپت.
	 *
	 * خروجیِ خام `get_available_variations()` برای هر واریاسیون یک آرایهٔ بزرگ
	 * (با مارک‌آپ تصویر و توضیحات) می‌سازد؛ روی محصولی با ۱۶ واریاسیون این
	 * یعنی ده‌ها کیلوبایت JSON داخل یک صفت HTML. اینجا فقط چیزی می‌ماند که
	 * برای عوض کردن قیمت، تخفیف و وضعیت دکمه لازم است.
	 *
	 * @param WC_Product $product
	 * @return array
	 */
	public static function variation_map( $product ) {
		if ( ! $product instanceof WC_Product || ! $product->is_type( 'variable' ) ) {
			return array();
		}

		$out = array();

		foreach ( $product->get_available_variations() as $variation ) {
			$price   = (float) $variation['display_price'];
			$regular = (float) $variation['display_regular_price'];

			$out[] = array(
				'id'    => (int) $variation['variation_id'],
				// فقط ویژگی‌هایی که مقدار دارند؛ خالی یعنی «هر مقداری».
				'attrs' => array_filter( (array) $variation['attributes'], 'strlen' ),
				'price' => $price,
				'reg'   => $regular,
				'off'   => ( $regular > $price && $regular > 0 ) ? (int) round( ( ( $regular - $price ) / $regular ) * 100 ) : 0,
				'stock' => ! empty( $variation['is_in_stock'] ),
				'buy'   => ! empty( $variation['is_purchasable'] ),
				'max'   => isset( $variation['max_qty'] ) && '' !== $variation['max_qty'] ? (int) $variation['max_qty'] : 0,
			);
		}

		return $out;
	}

	/**
	 * واریاسیونی که صفحه با آن باز می‌شود: کلید ویژگی → اسلاگ.
	 *
	 * اول بهترین تخفیف، وگرنه اولین ترکیبِ موجود. دلیلش هم کاربردی است و هم
	 * از سر سازگاری:
	 *
	 *   - کارت محصول قیمت و تخفیفِ همان «بهترین معامله» را نشان می‌دهد، پس
	 *     صفحه هم باید روی همان باز شود؛ وگرنه کاربر روی «۲۰٪ تخفیف» کلیک
	 *     می‌کند و صفحه‌ای می‌بیند که تخفیف ندارد.
	 *   - انتخابِ کورِ «اولین گزینهٔ هر ویژگی» ترکیبی می‌ساخت که ممکن بود
	 *     ناموجود باشد، و آن‌وقت بقیهٔ رنگ‌ها هم به‌غلط خط‌خورده می‌شدند.
	 *
	 * @param WC_Product $product
	 * @return array
	 */
	public static function default_variation( $product ) {
		$deal = self::best_deal( $product );

		if ( $deal ) {
			foreach ( self::variation_map( $product ) as $variation ) {
				if ( $variation['id'] === $deal['id'] ) {
					return $variation['attrs'];
				}
			}
		}

		foreach ( self::variation_map( $product ) as $variation ) {
			if ( $variation['stock'] && $variation['buy'] ) {
				return $variation['attrs'];
			}
		}

		return array();
	}

	/**
	 * بهترین معاملهٔ یک محصول متغیر: واریاسیونی که بیشترین درصد تخفیف را دارد.
	 *
	 * کارت محصول یک قیمت بیشتر جا ندارد، و نشان دادن «کمترین قیمت» (کاری که
	 * ووکامرس می‌کند) با نشانِ تخفیف نمی‌خواند؛ ممکن است ارزان‌ترین واریاسیون
	 * اصلاً تخفیف نداشته باشد. پس همان واریاسیونی را نشان می‌دهیم که نشانِ
	 * تخفیف به آن اشاره می‌کند.
	 *
	 * فقط واریاسیون‌های موجود حساب می‌شوند؛ تخفیفِ چیزی که نمی‌شود خرید،
	 * تبلیغِ بیهوده است.
	 *
	 * @param WC_Product $product
	 * @return array|null price، regular و off — یا null اگر تخفیفی نباشد.
	 */
	public static function best_deal( $product ) {
		$best = null;

		foreach ( self::variation_map( $product ) as $variation ) {
			if ( ! $variation['stock'] || ! $variation['buy'] || $variation['off'] < 1 ) {
				continue;
			}

			if ( null === $best || $variation['off'] > $best['off'] ) {
				$best = $variation;
			}
		}

		if ( null === $best ) {
			return null;
		}

		return array(
			'price'   => $best['price'],
			'regular' => $best['reg'],
			'off'     => $best['off'],
			'id'      => $best['id'],
		);
	}

	/**
	 * نام برند محصول، اگر تاکسونومی برند وجود داشته باشد.
	 *
	 * @param WC_Product $product
	 * @return array{name:string, link:string, logo:string}
	 */
	public static function brand( $product ) {
		$empty = array(
			'name' => '',
			'link' => '',
			'logo' => '',
		);

		foreach ( array( 'product_brand', 'pwb-brand', 'yith_product_brand', 'brand' ) as $taxonomy ) {
			if ( ! taxonomy_exists( $taxonomy ) ) {
				continue;
			}

			$terms = wp_get_post_terms( $product->get_id(), $taxonomy );

			if ( is_wp_error( $terms ) || empty( $terms ) ) {
				continue;
			}

			$term = $terms[0];
			$logo = '';

			foreach ( array( 'thumbnail_id', 'brand_image', 'pwb_brand_image' ) as $meta ) {
				$id = (int) get_term_meta( $term->term_id, $meta, true );

				if ( $id ) {
					$src  = wp_get_attachment_image_src( $id, 'medium' );
					$logo = $src ? $src[0] : '';
					break;
				}
			}

			$link = get_term_link( $term );

			return array(
				'name' => $term->name,
				'link' => is_wp_error( $link ) ? '' : $link,
				'logo' => $logo,
			);
		}

		return $empty;
	}

	/**
	 * مسیر صفحه: خانه ← دسته‌ها ← محصول.
	 *
	 * @param WC_Product $product
	 * @return array آرایه‌ای از array{text:string, url:string}
	 */
	public static function breadcrumb( $product ) {
		$trail = array(
			array(
				'text' => 'صفحه اصلی',
				'url'  => home_url( '/' ),
			),
		);

		$terms = wp_get_post_terms( $product->get_id(), 'product_cat' );

		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			// عمیق‌ترین دسته انتخاب و زنجیرهٔ والدینش ساخته می‌شود.
			$deepest = $terms[0];

			foreach ( $terms as $term ) {
				if ( count( get_ancestors( $term->term_id, 'product_cat' ) ) > count( get_ancestors( $deepest->term_id, 'product_cat' ) ) ) {
					$deepest = $term;
				}
			}

			$chain = array_reverse( get_ancestors( $deepest->term_id, 'product_cat' ) );
			$chain[] = $deepest->term_id;

			foreach ( $chain as $term_id ) {
				$term = get_term( $term_id, 'product_cat' );

				if ( ! $term || is_wp_error( $term ) ) {
					continue;
				}

				$link = get_term_link( $term );

				$trail[] = array(
					'text' => $term->name,
					'url'  => is_wp_error( $link ) ? '' : $link,
				);
			}
		}

		$trail[] = array(
			'text' => $product->get_name(),
			'url'  => '',
		);

		return $trail;
	}

	/**
	 * زمان پایان تخفیف محصول (ثانیهٔ باقی‌مانده)، صفر یعنی ندارد.
	 *
	 * @param WC_Product $product
	 * @return int
	 */
	public static function sale_seconds_left( $product ) {
		$to = $product->get_date_on_sale_to();

		if ( ! $to ) {
			return 0;
		}

		$left = $to->getTimestamp() - (int) current_time( 'timestamp', true ); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested

		return max( 0, (int) $left );
	}

	/**
	 * وضعیت موجودی برای نوار پیشرفت.
	 *
	 * @param WC_Product $product
	 * @param int        $assumed_total وقتی مدیریت موجودی خاموش است.
	 * @return array{qty:int, total:int, percent:int, managing:bool}
	 */
	public static function stock( $product, $assumed_total = 40 ) {
		$managing = $product->managing_stock();
		$qty      = $managing ? (int) $product->get_stock_quantity() : 0;
		$sold     = (int) $product->get_total_sales();

		if ( $managing && $qty > 0 ) {
			$total = max( $qty, $qty + $sold );
		} else {
			$total = max( 1, (int) $assumed_total );
			$qty   = $product->is_in_stock() ? $total : 0;
		}

		return array(
			'qty'      => $qty,
			'total'    => $total,
			// درصد «فروش‌رفته» تا نوار مثل دیزاین پر شود.
			'percent'  => (int) max( 0, min( 100, round( ( 1 - ( $qty / max( 1, $total ) ) ) * 100 ) ) ),
			'managing' => $managing,
		);
	}

	/**
	 * محصولی که بیشترین تخفیفِ زمان‌دار را دارد.
	 *
	 * «زمان‌دار» یعنی برای تخفیفش تاریخ پایان گذاشته شده باشد؛ بدون آن، شمارش
	 * معکوس عددی برای شمردن ندارد. واریاسیون‌ها هم دیده می‌شوند ولی محصول مادر
	 * برگردانده می‌شود، چون کارت و لینک به آن می‌خورد.
	 *
	 * نتیجه یک ربع کش می‌شود. کوئری مستقیم است چون WP_Query راهی برای «متای
	 * تاریخ پایان در آینده، روی محصول یا واریاسیونش» ندارد.
	 *
	 * @param string $metric   percent = بیشترین درصد، amount = بیشترین مبلغ.
	 * @param int    $category فقط این دستهٔ محصول؛ صفر یعنی همه.
	 * @return array|null
	 */
	public static function top_sale_deal( $metric = 'percent', $category = 0 ) {
		if ( ! function_exists( 'wc_get_product' ) ) {
			return null;
		}

		$metric   = 'amount' === $metric ? 'amount' : 'percent';
		$category = absint( $category );
		$key      = 'ardal_top_deal_' . $metric . '_' . $category;
		$cached   = get_transient( $key );

		// کش خالی هم معنادار است: یعنی همین اخیراً گشتیم و چیزی نبود.
		if ( is_array( $cached ) ) {
			if ( ! $cached ) {
				return null;
			}

			if ( ! empty( $cached['ends'] ) && $cached['ends'] > time() ) {
				return $cached;
			}
		}

		global $wpdb;

		$now = time();

		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT p.ID, p.post_type, p.post_parent,
					m_to.meta_value   AS date_to,
					m_from.meta_value AS date_from,
					m_reg.meta_value  AS reg_price,
					m_sale.meta_value AS sale_price
				FROM {$wpdb->posts} p
				INNER JOIN {$wpdb->postmeta} m_to
					ON p.ID = m_to.post_id AND m_to.meta_key = '_sale_price_dates_to'
				LEFT JOIN {$wpdb->postmeta} m_from
					ON p.ID = m_from.post_id AND m_from.meta_key = '_sale_price_dates_from'
				LEFT JOIN {$wpdb->postmeta} m_reg
					ON p.ID = m_reg.post_id AND m_reg.meta_key = '_regular_price'
				LEFT JOIN {$wpdb->postmeta} m_sale
					ON p.ID = m_sale.post_id AND m_sale.meta_key = '_sale_price'
				WHERE p.post_status = 'publish'
					AND p.post_type IN ('product', 'product_variation')
					AND CAST(m_to.meta_value AS UNSIGNED) > %d",
				$now
			)
		); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

		$best = null;

		foreach ( (array) $rows as $row ) {
			$from = (int) $row->date_from;

			// تخفیفی که هنوز شروع نشده، تخفیف نیست.
			if ( $from > 0 && $from > $now ) {
				continue;
			}

			$reg  = (float) $row->reg_price;
			$sale = (float) $row->sale_price;

			if ( $reg <= 0 || $sale <= 0 || $sale >= $reg ) {
				continue;
			}

			$owner = ( 'product_variation' === $row->post_type && $row->post_parent > 0 )
				? (int) $row->post_parent
				: (int) $row->ID;

			if ( $category > 0 && ! has_term( $category, 'product_cat', $owner ) ) {
				continue;
			}

			$product = wc_get_product( $owner );

			if ( ! $product || ! $product->is_visible() ) {
				continue;
			}

			// «بدون دسته‌بندی» از بقیهٔ فهرست‌ها حذف شده؛ اینجا هم نباید سر دربیاورد.
			$skip = class_exists( 'Ardal_Archive' ) ? Ardal_Archive::excluded_terms( 'product_cat' ) : array();
			$cats = array_map( 'intval', (array) wp_get_post_terms( $owner, 'product_cat', array( 'fields' => 'ids' ) ) );

			if ( $skip && $cats && ! array_diff( $cats, $skip ) ) {
				continue;
			}

			$row_data = array(
				'id'      => $owner,
				'ends'    => (int) $row->date_to,
				'price'   => $sale,
				'regular' => $reg,
				'diff'    => $reg - $sale,
				'off'     => (int) round( ( ( $reg - $sale ) / $reg ) * 100 ),
			);

			if ( null === $best || self::deal_beats( $row_data, $best, $metric ) ) {
				$best = $row_data;
			}
		}

		if ( null === $best ) {
			set_transient( $key, array(), 10 * MINUTE_IN_SECONDS );

			return null;
		}

		set_transient( $key, $best, 15 * MINUTE_IN_SECONDS );

		return $best;
	}

	/**
	 * آیا پیشنهاد «الف» از «ب» بهتر است؟ در تساوی، آنکه زودتر تمام می‌شود.
	 *
	 * @param array  $a
	 * @param array  $b
	 * @param string $metric
	 * @return bool
	 */
	private static function deal_beats( $a, $b, $metric ) {
		$field = 'amount' === $metric ? 'diff' : 'off';

		if ( abs( $a[ $field ] - $b[ $field ] ) < 0.001 ) {
			return $a['ends'] < $b['ends'];
		}

		return $a[ $field ] > $b[ $field ];
	}
}
