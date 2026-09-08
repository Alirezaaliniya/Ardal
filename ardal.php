<?php
/**
 * Plugin Name:       اردال — ویجت‌های فروشگاه ورزشی
 * Plugin URI:        https://caspiansport.local/
 * Description:       مجموعه ویجت‌های المنتور برای ساخت صفحه اصلی فروشگاه ورزشی، به‌همراه صفحه تنظیمات پالت رنگی سراسری.
 * Version:           1.0.0
 * Author:            alireza aliniya
 * Text Domain:       ardal
 * Domain Path:       /languages
 * Requires PHP:      7.4
 * Requires Plugins:  elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ARDAL_VERSION', '1.0.0' );
define( 'ARDAL_FILE', __FILE__ );
define( 'ARDAL_PATH', plugin_dir_path( __FILE__ ) );
define( 'ARDAL_URL', plugin_dir_url( __FILE__ ) );
define( 'ARDAL_MIN_ELEMENTOR', '3.5.0' );
define( 'ARDAL_MIN_PHP', '7.4' );

require_once ARDAL_PATH . 'includes/class-ardal-palette.php';
require_once ARDAL_PATH . 'includes/class-ardal-currency.php';
require_once ARDAL_PATH . 'includes/class-ardal-settings.php';
// هلپرها و لایهٔ دادهٔ محصول در پیشخوان هم لازم‌اند (فیلد عنوان انگلیسی)،
// نه فقط موقع ثبت ویجت‌های المنتور.
require_once ARDAL_PATH . 'includes/class-ardal-helpers.php';
require_once ARDAL_PATH . 'includes/class-ardal-jalali.php';
require_once ARDAL_PATH . 'includes/class-ardal-product.php';
require_once ARDAL_PATH . 'includes/class-ardal-product-fields.php';
require_once ARDAL_PATH . 'includes/class-ardal-mega-menu.php';
require_once ARDAL_PATH . 'includes/class-ardal-account.php';
require_once ARDAL_PATH . 'includes/class-ardal-shop.php';
require_once ARDAL_PATH . 'includes/class-ardal-assets.php';
require_once ARDAL_PATH . 'includes/class-ardal.php';

/**
 * @return Ardal
 */
function ardal() {
	return Ardal::instance();
}

ardal();
