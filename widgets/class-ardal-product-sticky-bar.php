<?php
/**
 * ویجت نوار چسبان خرید پایین صفحه محصول (Sticky Add to Cart Bar).
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Ardal_Widget_Product_Sticky_Bar extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-product-sticky-bar';
	}

	public function get_title() {
		return 'اردال — نوار چسبان خرید محصول';
	}

	public function get_icon() {
		return 'eicon-bottom-floating-bar';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'نوار چسبان', 'خرید سریع', 'قیمت', 'sticky', 'cart', 'bar' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_sticky_content',
			array( 'label' => 'محتوای نوار چسبان' )
		);

		$this->add_control(
			'image',
			array(
				'label'   => 'تصویر کوچک محصول',
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => '' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => 'عنوان محصول',
				'type'    => Controls_Manager::TEXT,
				'default' => 'کفش دویدن پرو-فیت مدل Aero X2 مردانه',
			)
		);

		$this->add_control(
			'variant_info',
			array(
				'label'   => 'جزئیات متغیر (رنگ / سایز)',
				'type'    => Controls_Manager::TEXT,
				'default' => 'مشکی / لیمویی · سایز ۴۲',
			)
		);

		$this->add_control(
			'price',
			array(
				'label'   => 'قیمت فروش (تومان)',
				'type'    => Controls_Manager::TEXT,
				'default' => '۲۹۱۷۰۰۰',
			)
		);

		$this->add_control(
			'old_price',
			array(
				'label'   => 'قیمت قبلی (تومان)',
				'type'    => Controls_Manager::TEXT,
				'default' => '۴۲۹۰۰۰۰',
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => 'متن دکمه',
				'type'    => Controls_Manager::TEXT,
				'default' => 'افزودن به سبد خرید',
			)
		);

		$this->add_control(
			'button_link',
			array(
				'label'   => 'لینک دکمه',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '#' ),
			)
		);

		$this->add_control(
			'show_on_scroll_only',
			array(
				'label'        => 'نمایش فقط هنگام اسکرول به پایین',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$price_clean = Ardal_Helpers::price( $s['price'] );
		$old_clean   = Ardal_Helpers::price( $s['old_price'] );
		$scroll_only = 'yes' === $s['show_on_scroll_only'];
		?>
		<div class="ard-prod-sticky-bar<?php echo $scroll_only ? ' ard-prod-sticky-bar--scroll' : ''; ?>" data-ard-sticky-bar>
			<div class="ard-prod-sticky-bar__inner">
				
				<!-- بندانگشتی کوچک -->
				<div class="ard-prod-sticky-bar__media">
					<?php if ( ! empty( $s['image']['url'] ) ) : ?>
						<img src="<?php echo esc_url( $s['image']['url'] ); ?>" alt="<?php echo esc_attr( $s['title'] ); ?>">
					<?php else : ?>
						<div class="ard-prod-sticky-bar__placeholder"></div>
					<?php endif; ?>
				</div>

				<!-- اطلاعات محصول -->
				<div class="ard-prod-sticky-bar__info">
					<div class="ard-prod-sticky-bar__title"><?php echo esc_html( $s['title'] ); ?></div>
					<?php if ( ! empty( $s['variant_info'] ) ) : ?>
						<div class="ard-prod-sticky-bar__variant" data-ard-sticky-variant><?php echo esc_html( $s['variant_info'] ); ?></div>
					<?php endif; ?>
				</div>

				<!-- قیمت و دکمه کنش -->
				<div class="ard-prod-sticky-bar__action-group">
					<div class="ard-prod-sticky-bar__prices">
						<?php if ( '' !== $old_clean ) : ?>
							<del class="ard-prod-sticky-bar__old"><?php echo esc_html( $old_clean ); ?></del>
						<?php endif; ?>
						<div class="ard-prod-sticky-bar__price">
							<span><?php echo esc_html( $price_clean ); ?></span>
							<span class="ard-prod-sticky-bar__unit"><?php echo Ardal_Currency::unit(); // phpcs:ignore ?></span>
						</div>
					</div>

					<a class="ard-prod-sticky-bar__btn"<?php echo Ardal_Helpers::link_attrs( $s['button_link'] ); // phpcs:ignore ?> data-ard-add-to-cart>
						<span><?php echo esc_html( $s['button_text'] ); ?></span>
					</a>
				</div>

			</div>
		</div>
		<?php
	}
}
