<?php
/**
 * صفحهٔ تشکر — قالب الانو (وقتی ویجت تسویهٔ الانو روی اندپوینت order-received است).
 *
 * @package Ardal
 *
 * @var WC_Order|false $order
 */

defined( 'ABSPATH' ) || exit;

Ardal_Shop_Elano::render_thanks( $order ? $order : null, true );
