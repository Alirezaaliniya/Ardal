<?php
/**
 * ویجت قیمت محصول.
 *
 * قیمت فعلی با واحد عمودی (یا آیکونی که خود کاربر بارگذاری می‌کند) و در کنارش
 * نشان تخفیف و قیمت خط‌خورده. برای محصول متغیر با انتخاب رنگ/سایز یا فرم
 * واریاسیون ووکامرس به‌روز می‌شود.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;

class Ardal_Widget_Product_Price extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-product-price';
	}

	public function get_title() {
		return 'اردال — قیمت محصول';
	}

	public function get_icon() {
		return 'eicon-product-price';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'قیمت', 'تخفیف', 'تومان', 'price', 'sale' );
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
				'description' => 'در حالت خودکار، محصول متغیر با بیشترین تخفیف باز می‌شود و با تغییر واریاسیون به‌روز می‌شود.',
			)
		);

		$this->add_control(
			'price',
			array(
				'label'     => 'قیمت فعلی',
				'type'      => Controls_Manager::TEXT,
				'default'   => '2160000',
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'old_price',
			array(
				'label'     => 'قیمت قبل',
				'type'      => Controls_Manager::TEXT,
				'default'   => '3190000',
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'        => 'چیدمان',
				'type'         => Controls_Manager::SELECT,
				'default'      => 'side',
				'options'      => array(
					'side'  => 'تخفیف کنار قیمت',
					'stack' => 'تخفیف بالای قیمت',
					'below' => 'تخفیف زیر قیمت',
				),
				'prefix_class' => 'ard-price--',
			)
		);

		$this->add_control(
			'deal_first',
			array(
				'label'        => 'تخفیف در ابتدای ردیف',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'description'  => 'در چیدمان «کنار قیمت»: روشن یعنی تخفیف سمت راست و قیمت سمت چپ.',
				'prefix_class' => 'ard-price-dealfirst-',
				'condition'    => array( 'layout' => 'side' ),
			)
		);

		$this->add_control(
			'show_badge',
			array(
				'label'   => 'نشان درصد تخفیف',
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'badge_format',
			array(
				'label'       => 'قالب نشان',
				'type'        => Controls_Manager::TEXT,
				'default'     => '{p}%',
				'description' => '{p} جای عدد درصد می‌نشیند؛ مثلاً «{p}٪» یا «-{p}%».',
				'condition'   => array( 'show_badge' => 'yes' ),
			)
		);

		$this->add_control(
			'show_old',
			array(
				'label'   => 'قیمت خط‌خورده',
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'heading_currency',
			array(
				'label'     => 'واحد پول',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'currency',
			array(
				'label'   => 'نمایش واحد',
				'type'    => Controls_Manager::SELECT,
				'default' => 'vertical',
				'options' => array(
					'vertical' => 'نماد عمودی تومان',
					'symbol'   => 'نماد افقی تومان',
					'text'     => 'متن «تومان»',
					'icon'     => 'آیکون یا SVG دلخواه',
					'image'    => 'تصویر دلخواه',
					'none'     => 'بدون واحد',
				),
			)
		);

		$this->add_control(
			'currency_icon',
			array(
				'label'       => 'آیکون واحد',
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'description' => 'از «Upload SVG» فایل SVG خودتان را بارگذاری کنید؛ رنگش از تب استایل می‌آید (اگر fill آن currentColor باشد).',
				'condition'   => array( 'currency' => 'icon' ),
			)
		);

		$this->add_control(
			'currency_image',
			array(
				'label'     => 'تصویر واحد',
				'type'      => Controls_Manager::MEDIA,
				'condition' => array( 'currency' => 'image' ),
			)
		);

		$this->add_control(
			'currency_text',
			array(
				'label'     => 'متن واحد',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'تومان',
				'condition' => array( 'currency' => 'text' ),
			)
		);

		$this->add_control(
			'old_currency',
			array(
				'label'   => 'واحد کنار قیمت قبل',
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'hide_out',
			array(
				'label'        => 'کم‌رنگ برای ناموجود',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'separator'    => 'before',
				'prefix_class' => 'ard-price-dimout-',
			)
		);

		$this->end_controls_section();

		$this->register_style_controls();
	}

	private function register_style_controls() {
		// ---------------------------------------------------------------- کلی
		$this->start_controls_section(
			'section_style_box',
			array(
				'label' => 'چیدمان',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'justify',
			array(
				'label'     => 'جای افقی',
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start'    => array( 'title' => 'ابتدا', 'icon' => 'eicon-justify-start-h' ),
					'center'        => array( 'title' => 'وسط', 'icon' => 'eicon-justify-center-h' ),
					'flex-end'      => array( 'title' => 'انتها', 'icon' => 'eicon-justify-end-h' ),
					'space-between' => array( 'title' => 'دو طرف', 'icon' => 'eicon-justify-space-between-h' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .ard-price' => '--ard-price-justify:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'valign',
			array(
				'label'     => 'جای عمودی',
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array( 'title' => 'بالا', 'icon' => 'eicon-v-align-top' ),
					'center'     => array( 'title' => 'وسط', 'icon' => 'eicon-v-align-middle' ),
					'flex-end'   => array( 'title' => 'پایین', 'icon' => 'eicon-v-align-bottom' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .ard-price' => '--ard-price-align:{{VALUE}};',
				),
				'condition' => array( 'layout' => 'side' ),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => 'فاصلهٔ قیمت و تخفیف',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-price' => '--ard-price-gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'deal_gap',
			array(
				'label'      => 'فاصلهٔ نشان و قیمت قبل',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 30 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-price' => '--ard-price-deal-gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'deal_dir',
			array(
				'label'     => 'نشان و قیمت قبل',
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''       => 'پیش‌فرض چیدمان',
					'column' => 'زیر هم',
					'row'    => 'کنار هم',
				),
				'selectors' => array(
					'{{WRAPPER}} .ard-price' => '--ard-price-deal-dir:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'deal_align',
			array(
				'label'     => 'تراز بخش تخفیف',
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array( 'title' => 'ابتدا', 'icon' => 'eicon-text-align-right' ),
					'center'     => array( 'title' => 'وسط', 'icon' => 'eicon-text-align-center' ),
					'flex-end'   => array( 'title' => 'انتها', 'icon' => 'eicon-text-align-left' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .ard-price' => '--ard-price-deal-align:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// ---------------------------------------------------------------- قیمت فعلی
		$this->start_controls_section(
			'section_style_price',
			array(
				'label' => 'قیمت فعلی',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typo',
				'selector' => '{{WRAPPER}} .ard-price__num',
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => 'رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-price' => '--ard-price-ink:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'unit_gap',
			array(
				'label'      => 'فاصلهٔ عدد و واحد',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 30 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-price' => '--ard-price-unit-gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// ---------------------------------------------------------------- واحد پول
		$this->start_controls_section(
			'section_style_unit',
			array(
				'label'     => 'واحد پول',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'currency!' => 'none' ),
			)
		);

		$this->add_responsive_control(
			'unit_size',
			array(
				'label'       => 'ارتفاع',
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', 'em' ),
				'range'       => array(
					'px' => array( 'min' => 8, 'max' => 80 ),
					'em' => array( 'min' => 0.3, 'max' => 3, 'step' => 0.05 ),
				),
				'description' => 'برای متن، اندازهٔ قلم است.',
				'selectors'   => array(
					'{{WRAPPER}} .ard-price' => '--ard-price-unit-h:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'unit_color',
			array(
				'label'     => 'رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-price' => '--ard-price-unit-ink:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'unit_valign',
			array(
				'label'     => 'جای عمودی کنار عدد',
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array( 'title' => 'بالا', 'icon' => 'eicon-v-align-top' ),
					'center'     => array( 'title' => 'وسط', 'icon' => 'eicon-v-align-middle' ),
					'flex-end'   => array( 'title' => 'پایین', 'icon' => 'eicon-v-align-bottom' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .ard-price' => '--ard-price-unit-align:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'unit_offset',
			array(
				'label'      => 'جابه‌جایی عمودی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => -20, 'max' => 20 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-price' => '--ard-price-unit-y:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'unit_typo',
				'label'     => 'تایپوگرافی متن',
				'selector'  => '{{WRAPPER}} .ard-price__unit--text',
				'condition' => array( 'currency' => 'text' ),
			)
		);

		$this->end_controls_section();

		// ---------------------------------------------------------------- قیمت قبل
		$this->start_controls_section(
			'section_style_old',
			array(
				'label'     => 'قیمت قبل',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_old' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'old_typo',
				'selector' => '{{WRAPPER}} .ard-price__old',
			)
		);

		$this->add_control(
			'old_color',
			array(
				'label'     => 'رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-price' => '--ard-price-old-ink:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'old_line',
			array(
				'label'     => 'رنگ خط',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-price' => '--ard-price-old-line:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'old_line_w',
			array(
				'label'      => 'ضخامت خط',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 4, 'step' => 0.5 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-price' => '--ard-price-old-line-w:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// ---------------------------------------------------------------- نشان تخفیف
		$this->start_controls_section(
			'section_style_badge',
			array(
				'label'     => 'نشان تخفیف',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_badge' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'badge_typo',
				'selector' => '{{WRAPPER}} .ard-price__badge',
			)
		);

		$this->add_control(
			'badge_bg',
			array(
				'label'     => 'پس‌زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-price' => '--ard-price-badge-bg:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'badge_color',
			array(
				'label'     => 'رنگ متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-price' => '--ard-price-badge-ink:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'badge_pad',
			array(
				'label'      => 'فاصلهٔ داخلی',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-price__badge' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'badge_radius',
			array(
				'label'      => 'گردی گوشه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-price' => '--ard-price-badge-r:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'badge_border',
				'selector' => '{{WRAPPER}} .ard-price__badge',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$data = 'manual' === $s['source'] ? $this->manual_data( $s ) : $this->auto_data();

		if ( ! $data ) {
			$this->render_empty( 'محصولی پیدا نشد. این ویجت را روی قالب تک‌محصول بگذارید یا حالت دستی را انتخاب کنید.' );

			return;
		}

		if ( '' === $data['price'] ) {
			$this->render_empty( 'این محصول قیمت ندارد.' );

			return;
		}

		$format   = '' !== trim( (string) $s['badge_format'] ) ? (string) $s['badge_format'] : '{p}%';
		$has_deal = $data['off'] > 0;
		$unit     = $this->unit_html( $s );
		$attrs    = '';

		if ( ! empty( $data['variations'] ) ) {
			$attrs .= ' data-is-variable="true" data-variations="' . esc_attr( wp_json_encode( $data['variations'] ) ) . '"';
		}

		$classes = 'ard-price' . ( $has_deal ? ' has-deal' : '' ) . ( $data['in_stock'] ? '' : ' is-out' );
		?>
		<div class="<?php echo esc_attr( $classes ); ?>" data-ard-price data-badge="<?php echo esc_attr( $format ); ?>"<?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- بالا escape شده. ?>>
			<?php if ( 'yes' === $s['show_badge'] || 'yes' === $s['show_old'] ) : ?>
				<div class="ard-price__deal"<?php echo $has_deal ? '' : ' hidden'; ?>>
					<?php if ( 'yes' === $s['show_badge'] ) : ?>
						<span class="ard-price__badge"><?php echo esc_html( $this->badge_text( $format, $data['off'] ) ); ?></span>
					<?php endif; ?>

					<?php if ( 'yes' === $s['show_old'] ) : ?>
						<span class="ard-price__old-wrap">
							<del class="ard-price__old"><?php echo esc_html( $data['old'] ); ?></del>
							<?php
							if ( 'yes' === $s['old_currency'] ) {
								echo $unit; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- در unit_html escape شده.
							}
							?>
						</span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="ard-price__now">
				<span class="ard-price__num"><?php echo esc_html( $data['price'] ); ?></span>
				<?php echo $unit; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- در unit_html escape شده. ?>
			</div>
		</div>
		<?php
	}

	/**
	 * متن نشان تخفیف با ارقام هماهنگ با تنظیم سراسری.
	 *
	 * @param string $format
	 * @param int    $off
	 * @return string
	 */
	private function badge_text( $format, $off ) {
		return str_replace( '{p}', Ardal_Helpers::fa_digits( (int) $off ), $format );
	}

	/**
	 * واحد پول بر اساس انتخاب ویجت.
	 *
	 * @param array $s
	 * @return string
	 */
	private function unit_html( $s ) {
		switch ( $s['currency'] ) {
			case 'none':
				return '';

			case 'text':
				return '<span class="ard-price__unit ard-price__unit--text">' . esc_html( $s['currency_text'] ) . '</span>';

			case 'symbol':
				return '<span class="ard-price__unit ard-price__unit--symbol">' . Ardal_Currency::symbol() . '</span>';

			case 'icon':
				if ( ! empty( $s['currency_icon']['value'] ) ) {
					ob_start();
					Icons_Manager::render_icon( $s['currency_icon'], array( 'aria-hidden' => 'true' ) );
					$icon = ob_get_clean();

					return '<span class="ard-price__unit ard-price__unit--icon" role="img" aria-label="' . esc_attr( $this->currency_label() ) . '">' . $icon . '</span>';
				}
				break;

			case 'image':
				if ( ! empty( $s['currency_image']['url'] ) ) {
					return '<span class="ard-price__unit ard-price__unit--image"><img src="' . esc_url( $s['currency_image']['url'] ) . '" alt="' . esc_attr( $this->currency_label() ) . '" decoding="async"></span>';
				}
				break;
		}

		// پیش‌فرض و حالت آیکونِ هنوز بارگذاری‌نشده: نماد عمودی.
		return '<span class="ard-price__unit ard-price__unit--vertical">' . Ardal_Currency::symbol( 'slim' ) . '</span>';
	}

	/**
	 * متن جایگزین واحد برای صفحه‌خوان.
	 *
	 * @return string
	 */
	private function currency_label() {
		if ( ! function_exists( 'get_woocommerce_currency' ) || 'IRT' === get_woocommerce_currency() ) {
			return 'تومان';
		}

		return html_entity_decode( get_woocommerce_currency_symbol(), ENT_QUOTES, 'UTF-8' );
	}

	/**
	 * @param array $s
	 * @return array
	 */
	private function manual_data( $s ) {
		$price = Ardal_Helpers::en_digits( (string) $s['price'] );
		$old   = Ardal_Helpers::en_digits( (string) $s['old_price'] );
		$off   = Ardal_Helpers::discount( $old, $price );

		return array(
			'price'      => Ardal_Helpers::price( $price ),
			'old'        => '' !== $off ? Ardal_Helpers::price( $old ) : '',
			'off'        => (int) Ardal_Helpers::en_digits( $off ),
			'in_stock'   => true,
			'variations' => array(),
		);
	}

	/**
	 * همان محاسبهٔ باکس خرید: محصول متغیر با پرتخفیف‌ترین واریاسیون.
	 *
	 * @return array|null
	 */
	private function auto_data() {
		$product = Ardal_Product::current();

		if ( ! $product ) {
			return null;
		}

		$regular     = $product->get_regular_price();
		$active      = $product->get_price();
		$is_variable = $product->is_type( 'variable' );

		if ( $is_variable ) {
			$deal = Ardal_Product::best_deal( $product );

			if ( $deal ) {
				$active  = $deal['price'];
				$regular = $deal['regular'];
			}
		}

		$has_old = $regular && (float) $regular > (float) $active && (float) $active > 0;

		return array(
			'price'      => '' !== (string) $active ? Ardal_Helpers::price( $active ) : '',
			'old'        => $has_old ? Ardal_Helpers::price( $regular ) : '',
			'off'        => $has_old ? (int) round( ( ( (float) $regular - (float) $active ) / (float) $regular ) * 100 ) : 0,
			'in_stock'   => $product->is_in_stock(),
			'variations' => $is_variable ? Ardal_Product::variation_map( $product ) : array(),
		);
	}
}
