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
			'brand-rings'         => 'Ardal_Widget_Brand_Rings',
			'reviews'             => 'Ardal_Widget_Reviews',
			'posts'               => 'Ardal_Widget_Posts',
			'posts-slider'        => 'Ardal_Widget_Posts_Slider',
			'post-tags'           => 'Ardal_Widget_Post_Tags',
			'toc'                 => 'Ardal_Widget_Toc',
			'countdown'           => 'Ardal_Widget_Countdown',
			'support'             => 'Ardal_Widget_Support',
			'search'              => 'Ardal_Widget_Search',
			'search-results'      => 'Ardal_Widget_Search_Results',
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
			'product-variations'  => 'Ardal_Widget_Product_Variations',
			'variation-matrix'    => 'Ardal_Widget_Variation_Matrix',
			// آرشیو فروشگاه
			'archive-rail'        => 'Ardal_Widget_Archive_Rail',
			'archive-filters'     => 'Ardal_Widget_Archive_Filters',
			'archive-chips'       => 'Ardal_Widget_Archive_Chips',
			'archive-toolbar'     => 'Ardal_Widget_Archive_Toolbar',
			'archive-description' => 'Ardal_Widget_Archive_Description',
			// پنل کاربری
			'account'             => 'Ardal_Widget_Account',
		);
	}

	/**
	 * عنوان‌های فارسی ویجت‌ها — برای نمایش در صفحه تنظیمات.
	 *
	 * @return array
	 */
	public static function widget_titles() {
		/*
		 * کلید هر عنوان، اسلاگ همان ویجت است. قبلاً این فهرست فقط ترتیبی بود و
		 * وقتی ناحیهٔ اطلاعات محصول به چند ویجت شکسته شد، از ردیف بیست‌ودوم به
		 * بعد عنوان‌ها یکی‌درمیان جابه‌جا افتادند. با کلید، دیگر نمی‌لغزد.
		 */
		return array(
			'topbar'              => 'نوار اعلان بالای صفحه',
			'header'              => 'هدر فروشگاه (لوگو، جستجو، سبد خرید)',
			'nav'                 => 'منوی دسته‌بندی‌ها',
			'mega-menu'           => 'مگا منو (از فهرست‌های وردپرس)',
			'hero'                => 'اسلایدر هیرو',
			'deal'                => 'پیشنهاد ویژه با شمارش معکوس',
			'services'            => 'خدمات و مزیت‌ها',
			'categories'          => 'دسته‌بندی‌های دایره‌ای',
			'products'            => 'شبکه محصولات',
			'product-card'        => 'کارت محصول (برای حلقه‌های المنتور)',
			'sale-festival'       => 'جشنواره حراج',
			'banners'             => 'بنرهای تبلیغاتی',
			'bestsellers'         => 'پرفروش‌ترین‌ها',
			'brands'              => 'برندها',
			'brand-rings'         => 'حلقه برندها (شبکه، اسلایدر، مارکی)',
			'reviews'             => 'نظر مشتریان',
			'posts'               => 'مطالب وبلاگ',
			'posts-slider'        => 'اسلایدر مطالب',
			'post-tags'           => 'برچسب‌های نوشته',
			'toc'                 => 'فهرست ناحیه‌های صفحه',
			'countdown'           => 'شمارش معکوس',
			'support'             => 'دکمه شناور پشتیبانی',
			'search'              => 'جست‌وجوی زنده',
			'search-results'      => 'نتیجه‌های جست‌وجو',
			'newsletter'          => 'خبرنامه',
			'footer'              => 'فوتر فروشگاه',
			'mobile-nav'          => 'نوار پایین موبایل',
			'product-breadcrumb'  => 'مسیر صفحه محصول (Breadcrumb)',
			'product-gallery'     => 'گالری تصاویر محصول',
			'product-title'       => 'عنوان محصول',
			'product-brand'       => 'برند محصول',
			'product-meta'        => 'نوار اطلاعات محصول',
			'product-color'       => 'انتخاب رنگ محصول',
			'product-size'        => 'انتخاب سایز محصول',
			'product-specs'       => 'مشخصات کوتاه محصول',
			'product-price-date'  => 'تاریخ به‌روزرسانی قیمت',
			'product-buybox'      => 'باکس خرید و قیمت',
			'product-tabs'        => 'تب‌های جامع محصول',
			'product-related'     => 'محصولات مرتبط',
			'product-sticky-bar'  => 'نوار چسبان خرید محصول',
			'product-variations'  => 'جدول واریاسیون‌ها',
			'variation-matrix'    => 'ماتریس واریاسیون‌ها',
			'archive-rail'        => 'ریل دسته‌بندی آرشیو',
			'archive-filters'     => 'فیلتر محصولات (ستون کناری)',
			'archive-chips'       => 'فیلترهای فعال',
			'archive-toolbar'     => 'نوار مرتب‌سازی آرشیو',
			'archive-description' => 'توضیحات دسته',
			'account'             => 'پنل کاربری',
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
				'title' => 'اردال — فروشگاه',
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

