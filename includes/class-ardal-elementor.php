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

	const CATEGORY = 'ardal';

	/**
	 * نگاشت اسلاگ فایل به نام کلاس.
	 *
	 * @return array
	 */
	public static function widgets() {
		return array(
			'topbar'        => 'Ardal_Widget_Topbar',
			'header'        => 'Ardal_Widget_Header',
			'nav'           => 'Ardal_Widget_Nav',
			'hero'          => 'Ardal_Widget_Hero',
			'deal'          => 'Ardal_Widget_Deal',
			'services'      => 'Ardal_Widget_Services',
			'categories'    => 'Ardal_Widget_Categories',
			'products'      => 'Ardal_Widget_Products',
			'product-card'  => 'Ardal_Widget_Product_Card',
			'sale-festival' => 'Ardal_Widget_Sale_Festival',
			'banners'       => 'Ardal_Widget_Banners',
			'bestsellers'   => 'Ardal_Widget_Bestsellers',
			'brands'        => 'Ardal_Widget_Brands',
			'reviews'       => 'Ardal_Widget_Reviews',
			'posts'         => 'Ardal_Widget_Posts',
			'newsletter'    => 'Ardal_Widget_Newsletter',
			'footer'        => 'Ardal_Widget_Footer',
			'mobile-nav'    => 'Ardal_Widget_Mobile_Nav',
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
	}

	/**
	 * @param \Elementor\Widgets_Manager $manager
	 */
	public function register_widgets( $manager ) {
		// در این نقطه المنتور Widget_Base را require کرده است.
		require_once ARDAL_PATH . 'includes/class-ardal-widget-base.php';

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
