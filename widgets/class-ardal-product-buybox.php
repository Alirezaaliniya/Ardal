<?php
/**
 * ویجت باکس خرید و قیمت محصول (قیمت، تایمر، تعداد، دکمه خرید، ضمانت‌ها).
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Product_Buybox extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-product-buybox';
	}

	public function get_title() {
		return 'اردال — باکس خرید و قیمت';
	}

	public function get_icon() {
		return 'eicon-cart';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'خرید', 'قیمت', 'سبد', 'تایمر', 'buy', 'cart', 'price' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_price',
			array( 'label' => 'قیمت و تخفیف' )
		);

		$this->add_control( 'price', array(
			'label' => 'قیمت فروش', 'type' => Controls_Manager::TEXT, 'default' => '۲۹۱۷۰۰۰',
		) );

		$this->add_control( 'old_price', array(
			'label' => 'قیمت قبلی', 'type' => Controls_Manager::TEXT, 'default' => '۴۲۹۰۰۰۰',
		) );

		$this->add_control( 'discount_percent', array(
			'label' => 'درصد تخفیف', 'type' => Controls_Manager::TEXT, 'default' => '۳۲٪',
		) );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_countdown',
			array( 'label' => 'تایمر شمارش معکوس' )
		);

		$this->add_control( 'show_countdown', array(
			'label' => 'نمایش تایمر', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes',
		) );

		$this->add_control( 'countdown_hours', array(
			'label' => 'ساعت باقی‌مانده', 'type' => Controls_Manager::NUMBER, 'default' => 5.3,
			'condition' => array( 'show_countdown' => 'yes' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_stock',
			array( 'label' => 'موجودی و تعداد' )
		);

		$this->add_control( 'stock_remaining', array(
			'label' => 'تعداد باقیمانده', 'type' => Controls_Manager::NUMBER, 'default' => 14,
		) );

		$this->add_control( 'stock_total', array(
			'label' => 'موجودی اولیه', 'type' => Controls_Manager::NUMBER, 'default' => 40,
		) );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_action',
			array( 'label' => 'دکمه خرید' )
		);

		$this->add_control( 'add_to_cart_text', array(
			'label' => 'متن دکمه', 'type' => Controls_Manager::TEXT, 'default' => 'افزودن به سبد خرید',
		) );

		$this->add_control( 'add_to_cart_url', array(
			'label' => 'لینک دکمه', 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#' ),
		) );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_guards',
			array( 'label' => 'ضمانت‌ها و خدمات' )
		);

		$rep_guards = new Repeater();
		$rep_guards->add_control( 'text', array(
			'label' => 'عنوان', 'type' => Controls_Manager::TEXT, 'default' => 'ارسال از ۲ ساعت آینده',
		) );

		$this->add_control( 'guards', array(
			'label'       => 'لیست ضمانت‌ها',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep_guards->get_controls(),
			'title_field' => '{{{ text }}}',
			'default'     => array(
				array( 'text' => 'ارسال از ۲ ساعت آینده' ),
				array( 'text' => '۷ روز ضمانت بازگشت کالا' ),
				array( 'text' => 'پرداخت در محل و اقساطی' ),
				array( 'text' => 'بسته‌بندی ضدضربه' ),
			),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$price_clean = Ardal_Helpers::price( $s['price'] );
		$old_clean   = Ardal_Helpers::price( $s['old_price'] );
		$discount    = ! empty( $s['discount_percent'] ) ? Ardal_Helpers::fa_digits( $s['discount_percent'] ) : Ardal_Helpers::discount( $old_clean, $price_clean );
		$guards      = is_array( $s['guards'] ) ? $s['guards'] : array();
		$stock_rem   = (int) $s['stock_remaining'];
		$stock_tot   = max( 1, (int) $s['stock_total'] );
		$stock_pct   = max( 6, min( 100, (int) round( ( 1 - ( $stock_rem / $stock_tot ) ) * 100 ) ) );
		?>
		<div class="ard-prod-buybox" data-ard-buybox>
			<!-- قیمت -->
			<div class="ard-prod-buybox__price-row">
				<?php if ( '' !== $discount ) : ?>
					<span class="ard-badge ard-badge--sale ard-prod-buybox__badge"><?php echo esc_html( $discount ); ?></span>
				<?php endif; ?>
				<?php if ( '' !== $old_clean ) : ?>
					<del class="ard-prod-buybox__old"><?php echo esc_html( $old_clean ); ?></del>
				<?php endif; ?>
			</div>

			<div class="ard-prod-buybox__price-main">
				<span class="ard-prod-buybox__amount"><?php echo esc_html( $price_clean ); ?></span>
				<span class="ard-prod-buybox__unit"><?php echo Ardal_Currency::unit(); // phpcs:ignore ?></span>
			</div>

			<!-- تایمر -->
			<?php if ( 'yes' === $s['show_countdown'] ) : ?>
				<?php $seconds = (int) round( (float) $s['countdown_hours'] * 3600 ); ?>
				<div class="ard-prod-countdown" data-ard-countdown data-seconds="<?php echo esc_attr( $seconds ); ?>">
					<div class="ard-prod-countdown__box">
						<div class="ard-prod-countdown__val" data-ard-clock-h>۰۵</div>
						<div class="ard-prod-countdown__lbl">ساعت</div>
					</div>
					<div class="ard-prod-countdown__box">
						<div class="ard-prod-countdown__val" data-ard-clock-m>۱۸</div>
						<div class="ard-prod-countdown__lbl">دقیقه</div>
					</div>
					<div class="ard-prod-countdown__box">
						<div class="ard-prod-countdown__val" data-ard-clock-s>۴۰</div>
						<div class="ard-prod-countdown__lbl">ثانیه</div>
					</div>
				</div>
			<?php endif; ?>

			<!-- نوار موجودی -->
			<div class="ard-prod-stock">
				<div class="ard-prod-stock__track">
					<div class="ard-prod-stock__fill" style="width: <?php echo esc_attr( $stock_pct ); ?>%"></div>
				</div>
				<div class="ard-prod-stock__note">فقط <?php echo esc_html( Ardal_Helpers::fa_digits( $stock_rem ) ); ?> عدد در انبار باقی مانده</div>
			</div>

			<!-- تعداد -->
			<div class="ard-prod-qty" data-ard-qty-wrapper>
				<span class="ard-prod-qty__label">تعداد</span>
				<div class="ard-prod-qty__stepper">
					<button type="button" class="ard-prod-qty__btn" data-ard-qty-dec aria-label="کاهش">−</button>
					<span class="ard-prod-qty__val" data-ard-qty-display>۱</span>
					<button type="button" class="ard-prod-qty__btn" data-ard-qty-inc aria-label="افزایش">+</button>
				</div>
			</div>

			<!-- دکمه خرید -->
			<div class="ard-prod-buybox__action">
				<a class="ard-prod-buybox__btn"<?php echo Ardal_Helpers::link_attrs( $s['add_to_cart_url'] ); // phpcs:ignore ?> data-ard-add-to-cart>
					<span class="ard-prod-buybox__btn-text"><?php echo esc_html( $s['add_to_cart_text'] ); ?></span>
				</a>
			</div>

			<a href="#" class="ard-prod-buybox__wishlist-link">افزودن به لیست علاقه‌مندی‌ها</a>

			<!-- ضمانت‌ها -->
			<?php if ( ! empty( $guards ) ) : ?>
				<div class="ard-prod-guards">
					<?php foreach ( $guards as $g ) : ?>
						<div class="ard-prod-guard">
							<span class="ard-prod-guard__icon">✓</span>
							<span class="ard-prod-guard__text"><?php echo esc_html( $g['text'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
