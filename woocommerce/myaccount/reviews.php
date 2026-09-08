<?php
/**
 * دیدگاه‌های کاربر روی محصولات — اندپوینت اختصاصی اردال.
 *
 * @package Ardal
 */

defined( 'ABSPATH' ) || exit;

$ard_reviews = Ardal_Account::reviews();
$ard_stats   = Ardal_Account::review_stats();
$ard_waiting = Ardal_Account::awaiting_review();
?>
<div class="ard-ac__stack">

	<div class="ard-ac__card ard-ac__reviews-head">
		<div class="ard-ac__reviews-intro">
			<h2 class="ard-ac__ptitle">نظرات و دیدگاه‌های من</h2>
			<p class="ard-ac__csub">
				<?php echo esc_html( Ardal_Helpers::fa_digits( $ard_stats['approved'] ) ); ?> دیدگاه منتشر شده
				<?php if ( $ard_stats['pending'] > 0 ) : ?>
					· <?php echo esc_html( Ardal_Helpers::fa_digits( $ard_stats['pending'] ) ); ?> در انتظار تایید
				<?php endif; ?>
			</p>
		</div>

		<div class="ard-ac__reviews-stats">
			<div class="ard-ac__rstat">
				<span class="ard-ac__rstat-value"><?php echo esc_html( Ardal_Helpers::fa_digits( $ard_stats['approved'] ) ); ?></span>
				<span class="ard-ac__rstat-label">منتشر شده</span>
			</div>
			<span class="ard-ac__rstat-sep" aria-hidden="true"></span>
			<div class="ard-ac__rstat">
				<span class="ard-ac__rstat-value ard-ac__rstat-value--amber"><?php echo esc_html( Ardal_Helpers::fa_digits( $ard_stats['pending'] ) ); ?></span>
				<span class="ard-ac__rstat-label">در بررسی</span>
			</div>
			<span class="ard-ac__rstat-sep" aria-hidden="true"></span>
			<div class="ard-ac__rstat">
				<span class="ard-ac__rstat-value ard-ac__rstat-value--green"><?php echo esc_html( Ardal_Helpers::fa_digits( $ard_stats['average'] ) ); ?></span>
				<span class="ard-ac__rstat-label">میانگین امتیاز</span>
			</div>
		</div>
	</div>

	<?php if ( $ard_waiting ) : ?>
		<div class="ard-ac__prompt">
			<span class="ard-ac__thumb ard-ac__thumb--sm"><?php echo Ardal_Account::thumb( $ard_waiting['product'], 'woocommerce_gallery_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<div class="ard-ac__prompt-text">
				<div class="ard-ac__prompt-title"><?php echo esc_html( $ard_waiting['product']->get_name() ); ?></div>
				<div class="ard-ac__prompt-note"><?php echo esc_html( $ard_waiting['note'] ); ?></div>
			</div>
			<a class="ard-ac__btn ard-ac__btn--primary" href="<?php echo esc_url( $ard_waiting['product']->get_permalink() . '#respond' ); ?>">ثبت دیدگاه</a>
		</div>
	<?php endif; ?>

	<?php if ( empty( $ard_reviews ) ) : ?>
		<div class="ard-ac__card ard-ac__empty">
			<span class="ard-ac__empty-icon"><?php echo Ardal_Account::icon( 'review', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<h3 class="ard-ac__ctitle">هنوز دیدگاهی ننوشته‌ای</h3>
			<p class="ard-ac__csub">تجربه‌ات از کالاهایی که خریده‌ای، به بقیه برای انتخاب کمک می‌کند.</p>
		</div>
	<?php endif; ?>

	<?php foreach ( $ard_reviews as $ard_review ) : ?>
		<?php
		$ard_product  = wc_get_product( $ard_review->comment_post_ID );
		$ard_rating   = (int) get_comment_meta( $ard_review->comment_ID, 'rating', true );
		$ard_approved = '1' === (string) $ard_review->comment_approved;
		$ard_stamp    = strtotime( $ard_review->comment_date_gmt . ' UTC' );
		?>
		<div class="ard-ac__card ard-ac__review">
			<span class="ard-ac__thumb ard-ac__thumb--md"><?php echo Ardal_Account::thumb( $ard_product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>

			<div class="ard-ac__review-body">
				<div class="ard-ac__review-head">
					<span class="ard-ac__review-product">
						<?php if ( $ard_product ) : ?>
							<a href="<?php echo esc_url( get_permalink( $ard_review->comment_post_ID ) ); ?>"><?php echo esc_html( $ard_product->get_name() ); ?></a>
						<?php else : ?>
							<?php echo esc_html( get_the_title( $ard_review->comment_post_ID ) ); ?>
						<?php endif; ?>
					</span>
					<span class="ard-ac__badge ard-ac__badge--<?php echo $ard_approved ? 'green' : 'amber'; ?>">
						<?php echo $ard_approved ? 'منتشر شده' : 'در انتظار تایید'; ?>
					</span>
				</div>

				<div class="ard-ac__review-meta">
					<?php if ( $ard_rating > 0 ) : ?>
						<span class="ard-ac__stars" aria-label="<?php echo esc_attr( sprintf( 'امتیاز %d از ۵', $ard_rating ) ); ?>">
							<?php for ( $ard_i = 1; $ard_i <= 5; $ard_i++ ) : ?>
								<svg class="<?php echo $ard_i <= $ard_rating ? 'is-on' : ''; ?>" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
									<path d="M12 3l2.6 5.6 6 .8-4.4 4.2 1.1 6-5.3-3-5.3 3 1.1-6L3.4 9.4l6-.8z"></path>
								</svg>
							<?php endfor; ?>
						</span>
					<?php endif; ?>
					<span class="ard-ac__review-date"><?php echo esc_html( Ardal_Jalali::format( $ard_stamp ) ); ?></span>
				</div>

				<div class="ard-ac__review-text"><?php echo esc_html( wp_strip_all_tags( $ard_review->comment_content ) ); ?></div>

				<?php if ( $ard_product ) : ?>
					<div class="ard-ac__review-actions">
						<a class="ard-ac__link" href="<?php echo esc_url( get_comment_link( $ard_review ) ); ?>">دیدن روی صفحه محصول</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>
