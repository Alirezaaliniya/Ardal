<?php
/**
 * ویجت نوار چسبان خرید محصول.
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
		return 'اردال — نوار چسبان خرید';
	}

	public function get_icon() {
		return 'eicon-footer';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'نوار', 'چسبان', 'خرید', 'sticky', 'cart' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'محتوا' )
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
				'description' => 'حالت خودکار تصویر، نام، قیمت و لینک خرید را از خود محصول می‌خواند.',
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => 'نام محصول',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'کفش دویدن پرو-فیت مدل Aero X2 مردانه',
				'label_block' => true,
				'condition'   => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'     => 'تصویر',
				'type'      => Controls_Manager::MEDIA,
				'condition' => array( 'source' => 'manual' ),
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

		$this->add_tag_control( 'title_tag', 'تگ نام محصول', 'div' );

		$this->add_control(
			'show_variant',
			array(
				'label'        => 'نمایش رنگ و سایز انتخاب‌شده',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'با انتخاب رنگ یا سایز در ویجت اطلاعات محصول، همین‌جا به‌روز می‌شود.',
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
				'label'     => 'لینک دکمه',
				'type'      => Controls_Manager::URL,
				'default'   => array( 'url' => '' ),
				'condition' => array( 'source' => 'manual' ),
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

		$this->add_control(
			'show_on_scroll_only',
			array(
				'label'        => 'فقط بعد از اسکرول نمایش داده شود',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_responsive_control(
			'hide_media',
			array(
				'label'          => 'نمایش تصویر',
				'type'           => Controls_Manager::SELECT,
				'default'        => 'flex',
				'mobile_default' => 'none',
				'options'        => array(
					'flex' => 'نمایش',
					'none' => 'مخفی',
				),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-sticky-bar__media' => 'display:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'hide_title',
			array(
				'label'          => 'نمایش نام محصول',
				'type'           => Controls_Manager::SELECT,
				'default'        => 'block',
				'mobile_default' => 'none',
				'options'        => array(
					'block' => 'نمایش',
					'none'  => 'مخفی',
				),
				'description'    => 'در موبایل معمولاً فقط قیمت و دکمه لازم است.',
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-sticky-bar__info' => 'display:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'bar_padding',
			array(
				'label'          => 'فاصله داخلی',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 6, 'max' => 30 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 14 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 10 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-sticky-bar' => 'padding-block:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'btn_grow',
			array(
				'label'          => 'دکمه تمام‌عرض',
				'type'           => Controls_Manager::SELECT,
				'default'        => '0',
				'mobile_default' => '1',
				'options'        => array(
					'0' => 'خیر',
					'1' => 'بله',
				),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-sticky-bar__btn' => 'flex-grow:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$data = 'manual' === $s['source'] ? $this->manual_data( $s ) : $this->auto_data();

		if ( null === $data ) {
			$this->render_empty( 'محصولی پیدا نشد. این ویجت را روی قالب تک‌محصول بگذارید یا حالت دستی را انتخاب کنید.' );

			return;
		}

		$ard_tag     = self::tag( $s['title_tag'], 'div' );
		$scroll_only = 'yes' === $s['show_on_scroll_only'];
		$classes     = array( 'ard-prod-sticky-bar' );

		if ( $scroll_only ) {
			$classes[] = 'ard-prod-sticky-bar--scroll';
		}
		?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-ard-sticky-bar>
			<div class="ard-prod-sticky-bar__inner">

				<div class="ard-prod-sticky-bar__media">
					<?php if ( '' !== $data['image'] ) : ?>
						<img src="<?php echo esc_url( $data['image'] ); ?>" alt="<?php echo esc_attr( $data['title'] ); ?>" loading="lazy" decoding="async" />
					<?php else : ?>
						<div class="ard-prod-sticky-bar__placeholder"></div>
					<?php endif; ?>
				</div>

				<div class="ard-prod-sticky-bar__info">
					<<?php echo esc_html( $ard_tag ); ?> class="ard-prod-sticky-bar__title"><?php echo esc_html( $data['title'] ); ?></<?php echo esc_html( $ard_tag ); ?>>
					<?php if ( 'yes' === $s['show_variant'] ) : ?>
						<div class="ard-prod-sticky-bar__variant" data-ard-sticky-variant></div>
					<?php endif; ?>
				</div>

				<div class="ard-prod-sticky-bar__action-group">
					<div class="ard-prod-sticky-bar__prices">
						<?php if ( '' !== $data['old_price'] ) : ?>
							<del class="ard-prod-sticky-bar__old"><?php echo esc_html( $data['old_price'] ); ?></del>
						<?php endif; ?>
						<?php if ( '' !== $data['price'] ) : ?>
							<div class="ard-prod-sticky-bar__price">
								<span><?php echo esc_html( $data['price'] ); ?></span>
								<span class="ard-prod-sticky-bar__unit"><?php echo Ardal_Currency::unit(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							</div>
						<?php endif; ?>
					</div>

					<?php $this->render_button( $s, $data ); ?>
				</div>

			</div>
		</div>
		<?php
	}

	/**
	 * @param array $s
	 * @param array $data
	 */
	private function render_button( $s, $data ) {
		$classes = array( 'ard-prod-sticky-bar__btn' );
		$attrs   = '';

		if ( $data['product_id'] > 0 && $data['purchasable'] ) {
			$product = wc_get_product( $data['product_id'] );

			if ( $product ) {
				echo Ardal_Helpers::woocommerce_add_to_cart_link(
					$product,
					$classes,
					$s['button_text'],
					array( 'data-ard-add-to-cart' => 'true' )
				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce API output.

				return;
			}

			$classes[] = 'add_to_cart_button';

			if ( $data['ajax_add'] && ! Ardal_Assets::is_woocommerce_asset_disabled( 'disable_wc_add_to_cart_js' ) ) {
				$classes[] = 'ajax_add_to_cart';
				wp_enqueue_script( 'wc-add-to-cart' );
			}

			$attrs = sprintf( ' data-product_id="%d" data-quantity="1" rel="nofollow"', $data['product_id'] );
		}

		printf(
			'<a class="%s" href="%s"%s data-ard-add-to-cart><span>%s</span></a>',
			esc_attr( implode( ' ', $classes ) ),
			esc_url( '' !== $data['add_url'] ? $data['add_url'] : '#' ),
			$attrs, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- با %d ساخته شده.
			esc_html( $s['button_text'] )
		);
	}

	/**
	 * @return array|null
	 */
	private function auto_data() {
		$product = Ardal_Product::current();

		if ( ! $product ) {
			return null;
		}

		$regular = $product->get_regular_price();
		$active  = $product->get_price();
		$src     = wp_get_attachment_image_src( $product->get_image_id(), 'woocommerce_gallery_thumbnail' );

		return array(
			'title'       => $product->get_name(),
			'image'       => $src ? $src[0] : '',
			'price'       => $active ? Ardal_Helpers::price( $active ) : '',
			'old_price'   => ( $product->is_on_sale() && $regular ) ? Ardal_Helpers::price( $regular ) : '',
			'add_url'     => $product->add_to_cart_url(),
			'product_id'  => $product->get_id(),
			'purchasable' => $product->is_purchasable() && $product->is_in_stock(),
			'ajax_add'    => $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock(),
		);
	}

	/**
	 * @param array $s
	 * @return array
	 */
	private function manual_data( $s ) {
		return array(
			'title'       => (string) $s['title'],
			'image'       => isset( $s['image']['url'] ) ? $s['image']['url'] : '',
			'price'       => Ardal_Helpers::price( $s['price'] ),
			'old_price'   => Ardal_Helpers::price( $s['old_price'] ),
			'add_url'     => isset( $s['button_link']['url'] ) ? $s['button_link']['url'] : '',
			'product_id'  => 0,
			'purchasable' => true,
			'ajax_add'    => false,
		);
	}
}
