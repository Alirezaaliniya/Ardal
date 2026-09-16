<?php
/**
 * پوستهٔ پنل کاربری — نوار بالا، ستون کناری و ناحیهٔ محتوا.
 *
 * جایگزین myaccount/my-account.php ووکامرس. خودِ مارک‌آپ در
 * Ardal_Account::render_panel() است تا ویجت المنتور هم از همان بخواند و دو
 * نسخه از هم فاصله نگیرند.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;

Ardal_Account::render_panel();
