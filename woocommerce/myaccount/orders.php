<?php
/**
 * فهرست سفارش‌ها با تب‌های وضعیت.
 *
 * جایگزین myaccount/orders.php ووکامرس.
 *
 * @package Ardal
 *
 * @var int $current_page شمارهٔ صفحهٔ جاری، از خود ووکامرس.
 */

defined( 'ABSPATH' ) || exit;

$ard_tab = isset( $_GET['status'] ) ? sanitize_key( wp_unslash( $_GET['status'] ) ) : 'all'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- فقط فیلتر نمایش.
$ard_page = isset( $current_page ) ? absint( $current_page ) : 1;
$ard_page = max( 1, $ard_page );

$ard_per_page = 6;
$ard_orders   = Ardal_Account::get_orders(
	array(
		'limit'    => $ard_per_page,
		'paginate' => true,
		'page'     => $ard_page,
		'status'   => Ardal_Account::tab_statuses( $ard_tab ),
	)
);

$ard_pages   = isset( $ard_orders->max_num_pages ) ? (int) $ard_orders->max_num_pages : 1;
$ard_list    = isset( $ard_orders->orders ) ? $ard_orders->orders : array();
$ard_all     = Ardal_Account::count_orders();
$ard_base = wc_get_account_endpoint_url( 'orders' );

/**
 * نشانی یک صفحه از همین تب. مسیر اندپوینت (.../orders/2/) اول ساخته می‌شود و
 * فیلتر وضعیت بعد از آن اضافه می‌شود، چون افزودن مسیر بعد از پارامتر پرس‌وجو
 * نشانی نامعتبر می‌سازد.
 *
 * @param int $page
 * @return string
 */
$ard_page_url = function ( $page ) use ( $ard_tab, $ard_base ) {
	$url = $page > 1 ? wc_get_endpoint_url( 'orders', $page, $ard_base ) : $ard_base;

	return 'all' === $ard_tab ? $url : add_query_arg( 'status', $ard_tab, $url );
};
?>
<div class="ard-ac__stack">

	<div class="ard-ac__card">
		<div class="ard-ac__chead">
			<h2 class="ard-ac__ptitle">سفارش‌های من</h2>
			<span class="ard-ac__chead-note"><?php echo esc_html( Ardal_Helpers::fa_digits( $ard_all ) ); ?> سفارش ثبت شده</span>
		</div>

		<div class="ard-ac__tabs">
			<?php foreach ( Ardal_Account::order_tabs( $ard_tab ) as $ard_item ) : ?>
				<a class="ard-ac__tabs-item<?php echo $ard_item['active'] ? ' is-active' : ''; ?>" href="<?php echo esc_url( $ard_item['url'] ); ?>">
					<?php echo esc_html( $ard_item['label'] ); ?>
					<span class="ard-ac__tabs-count"><?php echo esc_html( $ard_item['count'] ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>

	<?php if ( empty( $ard_list ) ) : ?>
		<div class="ard-ac__card ard-ac__empty">
			<span class="ard-ac__empty-icon"><?php echo Ardal_Account::icon( 'orders', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<h3 class="ard-ac__ctitle">سفارشی در این بخش نیست</h3>
			<p class="ard-ac__csub">هر سفارشی که ثبت کنی، با وضعیت و فاکتورش همین‌جا می‌آید.</p>
			<a class="ard-ac__btn ard-ac__btn--primary" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>">رفتن به فروشگاه</a>
		</div>
	<?php endif; ?>

	<?php foreach ( $ard_list as $ard_order ) : ?>
		<?php
		$ard_tone   = Ardal_Account::status_tone( $ard_order->get_status() );
		$ard_action = Ardal_Account::order_action( $ard_order );
		$ard_items  = $ard_order->get_items();
		$ard_date   = $ard_order->get_date_created();
		$ard_ship   = $ard_order->get_shipping_method();
		?>
		<div class="ard-ac__card ard-ac__order">
			<div class="ard-ac__order-head">
				<span class="ard-ac__badge ard-ac__badge--<?php echo esc_attr( $ard_tone ); ?>">
					<?php echo esc_html( wc_get_order_status_name( $ard_order->get_status() ) ); ?>
				</span>
				<span class="ard-ac__order-code"><?php echo esc_html( Ardal_Helpers::fa_digits( $ard_order->get_order_number() ) ); ?></span>

				<?php if ( $ard_date ) : ?>
					<span class="ard-ac__order-meta"><?php echo esc_html( Ardal_Jalali::format( $ard_date->getTimestamp() ) ); ?></span>
				<?php endif; ?>

				<?php if ( $ard_ship ) : ?>
					<span class="ard-ac__order-meta"><?php echo esc_html( $ard_ship ); ?></span>
				<?php endif; ?>

				<span class="ard-ac__order-side">
					<span class="ard-ac__order-total">
						<?php echo esc_html( Ardal_Helpers::price( $ard_order->get_total() ) ); ?>
						<span class="ard-ac__unit">تومان</span>
					</span>
					<a class="ard-ac__btn ard-ac__btn--ghost ard-ac__btn--sm" href="<?php echo esc_url( $ard_order->get_view_order_url() ); ?>">جزئیات</a>
				</span>
			</div>

			<div class="ard-ac__order-body">
				<span class="ard-ac__order-thumbs">
					<?php
					$ard_shown = 0;

					foreach ( $ard_items as $ard_item ) :
						if ( $ard_shown >= 3 ) {
							break;
						}

						$ard_shown++;
						?>
						<span class="ard-ac__thumb"><?php echo Ardal_Account::thumb( $ard_item->get_product(), 'woocommerce_gallery_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php endforeach; ?>

					<?php if ( count( $ard_items ) > 3 ) : ?>
						<span class="ard-ac__thumb ard-ac__thumb--more">+<?php echo esc_html( Ardal_Helpers::fa_digits( count( $ard_items ) - 3 ) ); ?></span>
					<?php endif; ?>
				</span>

				<span class="ard-ac__order-text">
					<span class="ard-ac__order-title"><?php echo esc_html( Ardal_Account::order_title( $ard_order ) ); ?></span>
					<span class="ard-ac__order-items">
						<?php echo esc_html( Ardal_Helpers::fa_digits( Ardal_Account::order_item_count( $ard_order ) ) ); ?> کالا
					</span>
				</span>

				<span class="ard-ac__order-actions">
					<a class="ard-ac__btn ard-ac__btn--<?php echo esc_attr( $ard_action['tone'] ); ?>" href="<?php echo esc_url( $ard_action['url'] ); ?>">
						<?php echo esc_html( $ard_action['label'] ); ?>
					</a>
					<a class="ard-ac__btn ard-ac__btn--soft" href="<?php echo esc_url( $ard_order->get_view_order_url() ); ?>">فاکتور</a>
				</span>
			</div>
		</div>
	<?php endforeach; ?>

	<?php if ( $ard_pages > 1 ) : ?>
		<nav class="ard-ac__pager" aria-label="صفحه‌بندی سفارش‌ها">
			<?php if ( $ard_page > 1 ) : ?>
				<a class="ard-ac__pager-item" href="<?php echo esc_url( $ard_page_url( $ard_page - 1 ) ); ?>">قبلی</a>
			<?php endif; ?>

			<?php for ( $ard_i = 1; $ard_i <= $ard_pages; $ard_i++ ) : ?>
				<a
					class="ard-ac__pager-item<?php echo $ard_i === $ard_page ? ' is-active' : ''; ?>"
					href="<?php echo esc_url( $ard_page_url( $ard_i ) ); ?>"
				><?php echo esc_html( Ardal_Helpers::fa_digits( $ard_i ) ); ?></a>
			<?php endfor; ?>

			<?php if ( $ard_page < $ard_pages ) : ?>
				<a class="ard-ac__pager-item" href="<?php echo esc_url( $ard_page_url( $ard_page + 1 ) ); ?>">بعدی</a>
			<?php endif; ?>
		</nav>
	<?php endif; ?>
</div>
