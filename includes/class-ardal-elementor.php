<?php
/**
 * ثبت دسته و ویجت‌های المنتور.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Elementor {

	const CATEGORY         = 'ardal';
	const CATEGORY_PRODUCT = 'ardal-product';
	const CATEGORY_ARCHIVE = 'ardal-archive';

	/**
	 * نگاشت اسلاگ فایل به نام کلاس.
	 *
	 * @return array
	 */
	public static function widgets() {
		return array(
			'topbar'              => 'Ardal_Widget_Topbar',
			'header'              => 'Ardal_Widget_Header',
			'nav'                 => 'Ardal_Widget_Nav',
			'mega-menu'           => 'Ardal_Widget_Mega_Menu',
			'hero'                => 'Ardal_Widget_Hero',
			'deal'                => 'Ardal_Widget_Deal',
			'services'            => 'Ardal_Widget_Services',
			'categories'          => 'Ardal_Widget_Categories',
			'products'            => 'Ardal_Widget_Products',
			'product-card'        => 'Ardal_Widget_Product_Card',
			'sale-festival'       => 'Ardal_Widget_Sale_Festival',
			'banners'             => 'Ardal_Widget_Banners',
			'bestsellers'         => 'Ardal_Widget_Bestsellers',
			'brands'              => 'Ardal_Widget_Brands',
			'reviews'             => 'Ardal_Widget_Reviews',
			'posts'               => 'Ardal_Widget_Posts',
			'newsletter'          => 'Ardal_Widget_Newsletter',
			'footer'              => 'Ardal_Widget_Footer',
			'mobile-nav'          => 'Ardal_Widget_Mobile_Nav',
			'product-breadcrumb'  => 'Ardal_Widget_Product_Breadcrumb',
			'product-gallery'     => 'Ardal_Widget_Product_Gallery',
			// ناحیهٔ اطلاعات محصول به ازای هر بخش یک ویجت مستقل دارد تا جایگاه
			// هرکدام جداگانه در المنتور تعیین شود.
			'product-title'       => 'Ardal_Widget_Product_Title',
			'product-brand'       => 'Ardal_Widget_Product_Brand',
			'product-meta'        => 'Ardal_Widget_Product_Meta',
			'product-color'       => 'Ardal_Widget_Product_Color',
			'product-size'        => 'Ardal_Widget_Product_Size',
			'product-specs'       => 'Ardal_Widget_Product_Specs',
			'product-price-date'  => 'Ardal_Widget_Product_Price_Date',
			'product-buybox'      => 'Ardal_Widget_Product_Buybox',
			'product-tabs'        => 'Ardal_Widget_Product_Tabs',
			'product-related'     => 'Ardal_Widget_Product_Related',
			'product-sticky-bar'  => 'Ardal_Widget_Product_Sticky_Bar',
			// آرشیو فروشگاه
			'archive-rail'        => 'Ardal_Widget_Archive_Rail',
			'archive-filters'     => 'Ardal_Widget_Archive_Filters',
			'archive-chips'       => 'Ardal_Widget_Archive_Chips',
			'archive-toolbar'     => 'Ardal_Widget_Archive_Toolbar',
			'archive-products'    => 'Ardal_Widget_Archive_Products',
			'archive-description' => 'Ardal_Widget_Archive_Description',
		);
	}

	/**
	 * عنوان‌های فارسی ویجت‌ها — برای نمایش در صفحه تنظیمات.
	 *
	 * @return array
	 */
	public static function widget_titles() {
		return array(
			'نوار اعلان بالای صفحه',
			'هدر فروشگاه (لوگو، جستجو، سبد خرید)',
			'منوی دسته‌بندی‌ها',
			'مگا منو (از فهرست‌های وردپرس)',
			'اسلایدر هیرو',
			'پیشنهاد ویژه با شمارش معکوس',
			'خدمات و مزیت‌ها',
			'دسته‌بندی‌های دایره‌ای',
			'شبکه محصولات',
			'کارت محصول (برای حلقه‌های المنتور)',
			'جشنواره حراج',
			'بنرهای تبلیغاتی',
			'پرفروش‌ترین‌ها',
			'برندها',
			'نظر مشتریان',
			'مطالب وبلاگ',
			'خبرنامه',
			'فوتر فروشگاه',
			'نوار پایین موبایل',
			'مسیر صفحه (Breadcrumb)',
			'گالری تصاویر محصول',
			'اطلاعات و مشخصات محصول',
			'باکس خرید و قیمت',
			'تب‌های جامع محصول',
			'محصولات مرتبط',
			'نوار چسبان خرید محصول',
			'ریل دسته‌بندی آرشیو',
			'فیلتر محصولات (ستون کناری)',
			'فیلترهای فعال',
			'نوار مرتب‌سازی آرشیو',
			'شبکه محصولات آرشیو',
			'توضیحات دسته',
		);
	}

	public function __construct() {
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
	}

	/**
	 * @param \Elementor\Elements_Manager $manager
	 */
	public function register_category( $manager ) {
		$manager->add_category(
			self::CATEGORY,
			array(
				'title' => 'اردال — فروشگاه ورزشی',
				'icon'  => 'eicon-woocommerce',
			)
		);

		$manager->add_category(
			self::CATEGORY_PRODUCT,
			array(
				'title' => 'اردال — صفحه محصول',
				'icon'  => 'eicon-single-product',
			)
		);

		$manager->add_category(
			self::CATEGORY_ARCHIVE,
			array(
				'title' => 'اردال — آرشیو فروشگاه',
				'icon'  => 'eicon-product-categories',
			)
		);
	}

	/**
	 * @param \Elementor\Widgets_Manager $manager
	 */
	public function register_widgets( $manager ) {
		// در این نقطه المنتور Widget_Base را require کرده است.
		require_once ARDAL_PATH . 'includes/class-ardal-widget-base.php';
		require_once ARDAL_PATH . 'includes/class-ardal-archive-widget-base.php';

		foreach ( self::widgets() as $slug => $class ) {
			$file = ARDAL_PATH . 'widgets/class-ardal-' . $slug . '.php';

			if ( ! file_exists( $file ) ) {
				continue;
			}

			require_once $file;

			if ( class_exists( $class ) ) {
				$manager->register( new $class() );
			}
		}
	}
}

