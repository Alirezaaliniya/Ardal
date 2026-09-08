<?php
/**
 * علاقه‌مندی‌های کاربر — اندپوینت اختصاصی اردال.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;

$ard_products = Ardal_Account::wishlist_products();
$ard_stock    = 0;

foreach ( $ard_products as $ard_product ) {
	if ( $ard_product->is_in_stock() ) {
		$ard_stock++;
	}
}
?>
<div class="ard-ac__stack" data-ard-ac-wishlist>

	<div class="ard-ac__card ard-ac__chead ard-ac__chead--card">
		<div>
			<h2 class="ard-ac__ptitle">علاقه‌مندی‌ها</h2>
			<p class="ard-ac__csub">
				<?php echo esc_html( Ardal_Helpers::fa_digits( count( $ard_products ) ) ); ?> کالا ·
				<?php echo esc_html( Ardal_Helpers::fa_digits( $ard_stock ) ); ?> کالا موجود است
			</p>
		</div>

		<?php if ( $ard_stock > 0 ) : ?>
			<button type="button" class="ard-ac__btn ard-ac__btn--accent" data-ard-ac-add-all>افزودن همه به سبد</button>
		<?php endif; ?>
	</div>

	<?php if ( empty( $ard_products ) ) : ?>
		<div class="ard-ac__card ard-ac__empty">
			<span class="ard-ac__empty-icon"><?php echo Ardal_Account::icon( 'fav', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<h3 class="ard-ac__ctitle">هنوز کالایی ذخیره نکرده‌ای</h3>
			<p class="ard-ac__csub">با زدن آیکون قلب روی هر محصول، آن را اینجا نگه دار تا بعداً سراغش بیایی.</p>
			<a class="ard-ac__btn ard-ac__btn--primary" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>">دیدن محصول‌ها</a>
		</div>
	<?php else : ?>
		<div class="ard-ac__grid ard-ac__grid--4">
			<?php foreach ( $ard_products as $ard_product ) : ?>
				<?php
				$ard_id     = $ard_product->get_id();
				$ard_avail  = $ard_product->is_in_stock();
				$ard_rating = (float) $ard_product->get_average_rating();
				$ard_count  = (int) $ard_product->get_review_count();
				$ard_brand  = '';
				$ard_terms  = get_the_terms( $ard_id, 'product_brand' );

				if ( $ard_terms && ! is_wp_error( $ard_terms ) ) {
					$ard_brand = $ard_terms[0]->name;
				}

				$ard_tag  = '';
				$ard_tone = '';

				if ( ! $ard_avail ) {
					$ard_tag  = 'ناموجود';
					$ard_tone = 'grey';
				} elseif ( $ard_product->is_on_sale() ) {
					$ard_tag  = Ardal_Helpers::discount(
						Ardal_Helpers::price( $ard_product->get_regular_price() ),
						Ardal_Helpers::price( $ard_product->get_price() )
					);
					$ard_tone = 'red';
				}
				?>
				<div class="ard-ac__card ard-ac__fav" data-ard-ac-fav="<?php echo esc_attr( $ard_id ); ?>">
					<button
						type="button"
						class="ard-ac__fav-remove"
						data-ard-ac-unfav="<?php echo esc_attr( $ard_id ); ?>"
						aria-label="حذف <?php echo esc_attr( $ard_product->get_name() ); ?> از علاقه‌مندی‌ها"
					>
						<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
							<path d="M12 20s-7-4.4-7-9a4 4 0 0 1 7-2.6A4 4 0 0 1 19 11c0 4.6-7 9-7 9z"></path>
						</svg>
					</button>

					<?php if ( '' !== $ard_tag ) : ?>
						<span class="ard-ac__fav-tag ard-ac__badge--<?php echo esc_attr( $ard_tone ); ?>"><?php echo esc_html( $ard_tag ); ?></span>
					<?php endif; ?>

					<a class="ard-ac__fav-media" href="<?php echo esc_url( $ard_product->get_permalink() ); ?>">
						<?php echo Ardal_Account::thumb( $ard_product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>

					<?php if ( '' !== $ard_brand ) : ?>
						<span class="ard-ac__fav-brand"><?php echo esc_html( $ard_brand ); ?></span>
					<?php endif; ?>

					<a class="ard-ac__fav-name" href="<?php echo esc_url( $ard_product->get_permalink() ); ?>"><?php echo esc_html( $ard_product->get_name() ); ?></a>

					<?php if ( $ard_rating > 0 ) : ?>
						<span class="ard-ac__fav-rate">
							<svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 3l2.6 5.6 6 .8-4.4 4.2 1.1 6-5.3-3-5.3 3 1.1-6L3.4 9.4l6-.8z"></path></svg>
							<span class="ard-ac__fav-rate-value"><?php echo esc_html( Ardal_Helpers::fa_digits( number_format( $ard_rating, 1, '٫', '' ) ) ); ?></span>
							<?php if ( $ard_count > 0 ) : ?>
								<span class="ard-ac__fav-rate-count">(<?php echo esc_html( Ardal_Helpers::fa_digits( $ard_count ) ); ?> نظر)</span>
							<?php endif; ?>
						</span>
					<?php endif; ?>

					<div class="ard-ac__fav-foot">
						<span class="ard-ac__fav-price"><?php echo wp_kses_post( $ard_product->get_price_html() ); ?></span>

						<?php if ( $ard_avail && $ard_product->is_purchasable() ) : ?>
							<?php
							echo Ardal_Helpers::woocommerce_add_to_cart_link( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خروجی ووکامرس.
								$ard_product,
								array( 'ard-ac__btn', 'ard-ac__btn--accent', 'ard-ac__btn--sm' ),
								'افزودن به سبد'
							);
							?>
						<?php else : ?>
							<a class="ard-ac__btn ard-ac__btn--soft ard-ac__btn--sm" href="<?php echo esc_url( $ard_product->get_permalink() ); ?>">مشاهده محصول</a>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
