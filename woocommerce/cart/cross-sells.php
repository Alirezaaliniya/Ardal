<?php
/**
 * «تکمیل خرید شما» — پیشنهادهای مکمل زیر سبد خرید.
 *
 * جایگزین cart/cross-sells.php ووکامرس.
 *
 * @package Ardal
 *
 * @var array $cross_sells
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $cross_sells ) ) {
	return;
}
?>
<section class="ard-cart__addons">
	<h2 class="ard-shop__section-title">
		<span class="ard-shop__section-rule" aria-hidden="true"></span>
		تکمیل خرید شما
	</h2>

	<div class="ard-cart__addons-grid">
		<?php foreach ( $cross_sells as $ard_product ) : ?>
			<div class="ard-cart__addon">
				<a class="ard-cart__addon-media" href="<?php echo esc_url( $ard_product->get_permalink() ); ?>">
					<?php echo $ard_product->get_image( 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>

				<div class="ard-cart__addon-text">
					<a class="ard-cart__addon-name" href="<?php echo esc_url( $ard_product->get_permalink() ); ?>">
						<?php echo esc_html( $ard_product->get_name() ); ?>
					</a>
					<div class="ard-cart__addon-price"><?php echo wp_kses_post( $ard_product->get_price_html() ); ?></div>
				</div>

				<?php if ( $ard_product->is_purchasable() && $ard_product->is_in_stock() ) : ?>
					<?php
					echo Ardal_Helpers::woocommerce_add_to_cart_link( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خروجی ووکامرس.
						$ard_product,
						array( 'ard-cart__addon-add' ),
						'+',
						array( 'aria-label' => 'افزودن ' . $ard_product->get_name() . ' به سبد خرید' )
					);
					?>
				<?php else : ?>
					<a class="ard-cart__addon-add" href="<?php echo esc_url( $ard_product->get_permalink() ); ?>" aria-label="مشاهده <?php echo esc_attr( $ard_product->get_name() ); ?>">←</a>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
