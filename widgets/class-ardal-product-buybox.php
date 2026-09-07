<?php
/**
 * ویجت باکس خرید محصول: قیمت، شمارش معکوس تخفیف، موجودی، تعداد و افزودن به سبد.
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
		return 'اردال — باکس خرید محصول';
	}

	public function get_icon() {
		return 'eicon-product-add-to-cart';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'خرید', 'قیمت', 'سبد', 'buy', 'price', 'cart' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'قیمت' )
		);

		$this->add_control(
			'source',
			array(
				'label'       => 'منبع',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'auto',
				'options'     => array(
					'auto'   => 'خودکار از محصول جاری',
					'manual' => 'دستی',
				),
				'description' => 'حالت خودکار قیمت، تخفیف، تاریخ پایان حراج و موجودی را از خود محصول می‌خواند.',
			)
		);

		$this->add_control(
			'price',
			array(
				'label'     => 'قیمت',
				'type'      => Controls_Manager::TEXT,
				'default'   => '۲٫۹۱۷٫۰۰۰',
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'old_price',
			array(
				'label'     => 'قیمت قبل',
				'type'      => Controls_Manager::TEXT,
				'default'   => '۴٫۲۹۰٫۰۰۰',
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'discount',
			array(
				'label'       => 'درصد تخفیف',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'خالی = محاسبه خودکار',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_timer',
			array( 'label' => 'شمارش معکوس' )
		);

		$this->add_control(
			'show_timer',
			array(
				'label'        => 'نمایش شمارنده',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'timer_mode',
			array(
				'label'       => 'مبنای شمارنده',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'sale',
				'options'     => array(
					'sale'  => 'تاریخ پایان حراج محصول',
					'daily' => 'تا پایان امروز',
					'date'  => 'تا یک تاریخ مشخص',
				),
				'description' => 'اگر محصول تاریخ پایان حراج نداشته باشد، شمارنده نمایش داده نمی‌شود.',
				'condition'   => array( 'show_timer' => 'yes' ),
			)
		);

		$this->add_control(
			'end_date',
			array(
				'label'     => 'تاریخ پایان',
				'type'      => Controls_Manager::DATE_TIME,
				'default'   => gmdate( 'Y-m-d H:i', current_time( 'timestamp' ) + DAY_IN_SECONDS ), // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
				'condition' => array(
					'show_timer' => 'yes',
					'timer_mode' => 'date',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_stock',
			array( 'label' => 'موجودی' )
		);

		$this->add_control(
			'show_stock',
			array(
				'label'        => 'نمایش نوار موجودی',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'stock_template',
			array(
				'label'       => 'متن زیر نوار',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'فقط {qty} عدد در انبار باقی مانده',
				'label_block' => true,
				'description' => 'می‌توانید از {qty} و {total} استفاده کنید.',
				'condition'   => array( 'show_stock' => 'yes' ),
			)
		);

		$this->add_control(
			'stock_qty',
			array(
				'label'     => 'تعداد موجودی',
				'type'      => Controls_Manager::NUMBER,
				'default'   => 14,
				'min'       => 0,
				'condition' => array(
					'source'     => 'manual',
					'show_stock' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart',
			array( 'label' => 'خرید' )
		);

		$this->add_control(
			'show_qty',
			array(
				'label'        => 'انتخابگر تعداد',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'qty_label',
			array(
				'label'     => 'برچسب تعداد',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'تعداد',
				'condition' => array( 'show_qty' => 'yes' ),
			)
		);

		$this->add_control(
			'cart_label',
			array(
				'label'   => 'متن دکمه خرید',
				'type'    => Controls_Manager::TEXT,
				'default' => 'افزودن به سبد خرید',
			)
		);

		$this->add_control(
			'out_of_stock_label',
			array(
				'label'   => 'متن دکمه هنگام ناموجودی',
				'type'    => Controls_Manager::TEXT,
				'default' => 'ناموجود',
			)
		);

		$this->add_control(
			'wishlist_text',
			array(
				'label'   => 'متن لینک علاقه‌مندی',
				'type'    => Controls_Manager::TEXT,
				'default' => 'افزودن به لیست علاقه‌مندی‌ها',
			)
		);

		$this->add_control(
			'wishlist_text_on',
			array(
				'label'     => 'متن لینک پس از افزودن',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'حذف از لیست علاقه‌مندی‌ها',
				'condition' => array( 'wishlist_text!' => '' ),
			)
		);

		$guard = new Repeater();

		$guard->add_control(
			'text',
			array(
				'label'       => 'متن',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'ارسال از ۲ ساعت آینده',
				'label_block' => true,
			)
		);

		$this->add_control(
			'guards',
			array(
				'label'       => 'تضمین‌ها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $guard->get_controls(),
				'title_field' => '{{{ text }}}',
				'default'     => array(
					array( 'text' => 'ارسال از ۲ ساعت آینده' ),
					array( 'text' => '۷ روز ضمانت بازگشت کالا' ),
					array( 'text' => 'پرداخت در محل و اقساطی' ),
					array( 'text' => 'بسته‌بندی ضدضربه' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => 'ظاهر',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'price_size',
			array(
				'label'          => 'اندازه قیمت',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 16, 'max' => 44 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 26 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 22 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-buybox__price-main' => 'font-size:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'          => 'فاصله داخلی باکس',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 8, 'max' => 36 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 18 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 14 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-buybox' => 'padding:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'sticky_top',
			array(
				'label'       => 'چسبیدن هنگام اسکرول (فاصله از بالا)',
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array( 'px' => array( 'min' => 0, 'max' => 200 ) ),
				'default'     => array( 'unit' => 'px', 'size' => 0 ),
				'description' => 'صفر یعنی نچسبد. در موبایل معمولاً بهتر است صفر بماند.',
				'selectors'   => array(
					'{{WRAPPER}} .ard-prod-buybox' => 'position:sticky;top:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$data = 'manual' === $s['source'] ? $this->manual_data( $s ) : $this->auto_data( $s );

		if ( null === $data ) {
			$this->render_empty( 'محصولی پیدا نشد. این ویجت را روی قالب تک‌محصول بگذارید یا حالت دستی را انتخاب کنید.' );

			return;
		}

		$discount = '' !== trim( (string) $s['discount'] )
			? Ardal_Helpers::fa_digits( $s['discount'] )
			: Ardal_Helpers::discount( $data['old_price'], $data['price'] );
		?>
		<div class="ard-prod-buybox" data-ard-buybox>

			<?php if ( '' !== $discount || '' !== $data['old_price'] ) : ?>
				<div class="ard-prod-buybox__price-row">
					<?php if ( '' !== $discount ) : ?>
						<span class="ard-prod-buybox__badge"><?php echo esc_html( $discount ); ?></span>
					<?php endif; ?>
					<?php if ( '' !== $data['old_price'] ) : ?>
						<del class="ard-prod-buybox__old"><?php echo esc_html( $data['old_price'] ); ?></del>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( '' !== $data['price'] ) : ?>
				<div class="ard-prod-buybox__price-main">
					<?php echo esc_html( $data['price'] ); ?>
					<span class="ard-prod-buybox__unit"><?php echo Ardal_Currency::unit(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</div>
			<?php endif; ?>

			<?php if ( 'yes' === $s['show_timer'] && $data['seconds_left'] > 0 ) : ?>
				<div
					class="ard-clock ard-prod-countdown"
					data-ard-countdown
					data-remaining="<?php echo esc_attr( $data['seconds_left'] ); ?>"
					data-mode="<?php echo esc_attr( 'daily' === $s['timer_mode'] ? 'daily' : 'date' ); ?>"
					data-days="0"
				>
					<?php
					foreach ( array( 'hours' => 'ساعت', 'minutes' => 'دقیقه', 'seconds' => 'ثانیه' ) as $unit => $label ) :
						$value = $this->clock_part( $data['seconds_left'], $unit );
						?>
						<div class="ard-clock__cell ard-prod-countdown__box">
							<span class="ard-clock__num ard-prod-countdown__val" data-unit="<?php echo esc_attr( $unit ); ?>"><?php echo esc_html( Ardal_Helpers::fa_digits( $value ) ); ?></span>
							<span class="ard-clock__label ard-prod-countdown__lbl"><?php echo esc_html( $label ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( 'yes' === $s['show_stock'] && ! empty( $data['stock']['show'] ) ) : ?>
				<div class="ard-prod-stock">
					<div class="ard-prod-stock__track ard-progress" role="progressbar" aria-valuenow="<?php echo esc_attr( $data['stock']['percent'] ); ?>" aria-valuemin="0" aria-valuemax="100">
						<span class="ard-prod-stock__fill" style="width:<?php echo esc_attr( $data['stock']['percent'] ); ?>%"></span>
					</div>
					<?php if ( '' !== trim( (string) $s['stock_template'] ) ) : ?>
						<div class="ard-prod-stock__note"><?php echo esc_html( $this->stock_note( $s, $data['stock'] ) ); ?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( 'yes' === $s['show_qty'] && $data['purchasable'] ) : ?>
				<div class="ard-prod-qty">
					<span class="ard-prod-qty__label"><?php echo esc_html( $s['qty_label'] ); ?></span>
					<div class="ard-prod-qty__stepper">
						<button type="button" class="ard-prod-qty__btn" data-ard-qty-dec aria-label="کاهش تعداد">−</button>
						<span class="ard-prod-qty__val" data-ard-qty-display data-max="<?php echo esc_attr( $data['max_qty'] ); ?>">۱</span>
						<button type="button" class="ard-prod-qty__btn" data-ard-qty-inc aria-label="افزایش تعداد">+</button>
					</div>
				</div>
			<?php endif; ?>

			<?php $this->render_cart_button( $s, $data ); ?>

			<?php if ( '' !== trim( (string) $s['wishlist_text'] ) && $data['product_id'] > 0 && Ardal_Product::wishlist_on() ) : ?>
				<?php $wish_on = '' !== trim( (string) $s['wishlist_text_on'] ) ? $s['wishlist_text_on'] : $s['wishlist_text']; ?>
				<button type="button" class="ard-prod-buybox__wishlist-link"
					data-ard-wishlist
					data-ard-list-text
					data-product-id="<?php echo esc_attr( $data['product_id'] ); ?>"
					data-label-off="<?php echo esc_attr( $s['wishlist_text'] ); ?>"
					data-label-on="<?php echo esc_attr( $wish_on ); ?>"
					aria-pressed="false"><?php echo esc_html( $s['wishlist_text'] ); ?></button>
			<?php endif; ?>

			<?php if ( ! empty( $s['guards'] ) ) : ?>
				<div class="ard-prod-guards">
					<?php
					foreach ( (array) $s['guards'] as $guard ) :
						if ( '' === trim( (string) $guard['text'] ) ) {
							continue;
						}
						?>
						<div class="ard-prod-guard">
							<span class="ard-prod-guard__icon">✓</span>
							<span><?php echo esc_html( $guard['text'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * دکمهٔ افزودن به سبد.
	 *
	 * روی محصول واقعی، کلاس‌ها و data-attribute های ووکامرس چاپ می‌شوند تا
	 * افزودن بدون رفرش کار کند. محصول متغیر یا ناموجود به صفحهٔ محصول می‌رود.
	 *
	 * @param array $s
	 * @param array $data
	 */
	private function render_cart_button( $s, $data ) {
		if ( ! $data['purchasable'] ) {
			printf(
				'<span class="ard-prod-buybox__btn is-disabled" aria-disabled="true">%s</span>',
				esc_html( $s['out_of_stock_label'] )
			);

			return;
		}

		$classes = array( 'ard-prod-buybox__btn' );
		$attrs   = '';

		if ( $data['product_id'] > 0 ) {
			$product = wc_get_product( $data['product_id'] );

			if ( $product ) {
				echo Ardal_Helpers::woocommerce_add_to_cart_link(
					$product,
					$classes,
					$s['cart_label'],
					array( 'data-ard-add-to-cart' => 'true' )
				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce API output.

				return;
			}

			$classes[] = 'add_to_cart_button';

			if ( $data['ajax_add'] && ! Ardal_Assets::is_woocommerce_asset_disabled( 'disable_wc_add_to_cart_js' ) ) {
				$classes[] = 'ajax_add_to_cart';
				wp_enqueue_script( 'wc-add-to-cart' );
			}

			$attrs = sprintf(
				' data-product_id="%d" data-quantity="1" rel="nofollow"',
				$data['product_id']
			);
		}

		printf(
			'<a class="%s" href="%s"%s data-ard-add-to-cart>%s</a>',
			esc_attr( implode( ' ', $classes ) ),
			esc_url( $data['add_url'] ),
			$attrs, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- از sprintf با %d ساخته شده.
			esc_html( $s['cart_label'] )
		);
	}

	/**
	 * @param int    $seconds
	 * @param string $unit
	 * @return string
	 */
	private function clock_part( $seconds, $unit ) {
		$seconds = max( 0, (int) $seconds );

		switch ( $unit ) {
			case 'hours':
				$value = (int) floor( $seconds / HOUR_IN_SECONDS );
				break;

			case 'minutes':
				$value = (int) floor( $seconds / MINUTE_IN_SECONDS ) % 60;
				break;

			default:
				$value = $seconds % 60;
		}

		return str_pad( (string) $value, 2, '0', STR_PAD_LEFT );
	}

	/**
	 * @param array $s
	 * @param array $stock
	 * @return string
	 */
	private function stock_note( $s, $stock ) {
		return str_replace(
			array( '{qty}', '{total}' ),
			array( Ardal_Helpers::fa_digits( $stock['qty'] ), Ardal_Helpers::fa_digits( $stock['total'] ) ),
			(string) $s['stock_template']
		);
	}

	/**
	 * @param array $s
	 * @return array|null
	 */
	private function auto_data( $s ) {
		$product = Ardal_Product::current();

		if ( ! $product ) {
			return null;
		}

		$regular = $product->get_regular_price();
		$active  = $product->get_price();

		return array(
			'price'        => $active ? Ardal_Helpers::price( $active ) : '',
			'old_price'    => ( $product->is_on_sale() && $regular ) ? Ardal_Helpers::price( $regular ) : '',
			'seconds_left' => $this->seconds_left( $s, $product ),
			// وقتی محصول مدیریت موجودی ندارد، عددِ باقی‌مانده ساختگی است و
			// نمایش «فقط N عدد باقی مانده» گمراه‌کننده می‌شود.
			'stock'        => array_merge(
				Ardal_Product::stock( $product ),
				array( 'show' => $product->managing_stock() )
			),
			'purchasable'  => $product->is_purchasable() && $product->is_in_stock(),
			'product_id'   => $product->get_id(),
			'ajax_add'     => $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock(),
			'add_url'      => $product->add_to_cart_url(),
			'max_qty'      => $product->managing_stock() && $product->get_stock_quantity() > 0
				? (int) $product->get_stock_quantity()
				: 99,
		);
	}

	/**
	 * @param array $s
	 * @return array
	 */
	private function manual_data( $s ) {
		$qty = max( 0, (int) $s['stock_qty'] );

		return array(
			'price'        => Ardal_Helpers::price( $s['price'] ),
			'old_price'    => Ardal_Helpers::price( $s['old_price'] ),
			'seconds_left' => $this->seconds_left( $s, null ),
			'stock'        => array(
				'qty'     => $qty,
				'total'   => max( $qty, 40 ),
				'percent' => (int) max( 0, min( 100, round( ( 1 - ( $qty / max( 1, max( $qty, 40 ) ) ) ) * 100 ) ) ),
				'show'    => true,
			),
			'purchasable'  => true,
			'product_id'   => 0,
			'ajax_add'     => false,
			'add_url'      => '#',
			'max_qty'      => 99,
		);
	}

	/**
	 * ثانیهٔ باقی‌مانده تا پایان تخفیف.
	 *
	 * @param array           $s
	 * @param WC_Product|null $product
	 * @return int
	 */
	private function seconds_left( $s, $product ) {
		if ( 'yes' !== $s['show_timer'] ) {
			return 0;
		}

		$now = (int) current_time( 'timestamp' ); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested

		switch ( $s['timer_mode'] ) {
			case 'daily':
				return max( 0, strtotime( 'tomorrow', $now ) - $now );

			case 'date':
				$end = ! empty( $s['end_date'] ) ? strtotime( $s['end_date'] ) : 0;

				return $end ? max( 0, $end - $now ) : 0;

			default:
				return $product ? Ardal_Product::sale_seconds_left( $product ) : 0;
		}
	}
}
