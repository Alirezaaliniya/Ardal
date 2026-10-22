<?php
/**
 * ویجت ویژگی‌های کلیدی محصول: چند کارت کوچک «عنوان / مقدار».
 *
 * همهٔ ویژگی‌ها نمایش داده نمی‌شوند؛ به ترتیب خود محصول جلو می‌رود و فقط
 * آن‌هایی برداشته می‌شوند که مقدارشان کوتاه است، تا کارت‌ها هم‌اندازه بمانند.
 * فهرست کامل جایش در تب مشخصات است.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

class Ardal_Widget_Product_Features extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-product-features';
	}

	public function get_title() {
		return 'اردال — ویژگی‌های کلیدی محصول';
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'ویژگی', 'مشخصات', 'attribute', 'features', 'specs' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'محتوا' )
		);

		$this->add_control(
			'title',
			array(
				'label'       => 'عنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'ویژگی های محصول :',
				'label_block' => true,
				'description' => 'خالی بماند، عنوانی نمایش داده نمی‌شود.',
			)
		);

		$this->add_tag_control( 'title_tag', 'تگ عنوان', 'h3' );

		$this->add_control(
			'source',
			array(
				'label'     => 'منبع',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'auto',
				'separator' => 'before',
				'options'   => array(
					'auto'   => 'خودکار از ویژگی‌های محصول',
					'manual' => 'دستی',
				),
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'   => 'تعداد',
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 12,
			)
		);

		$this->add_control(
			'max_len',
			array(
				'label'       => 'حداکثر طول مقدار',
				'type'        => Controls_Manager::NUMBER,
				'default'     => 20,
				'min'         => 0,
				'max'         => 200,
				'description' => 'به حرف. ویژگی‌ای که مقدارش بلندتر است رد می‌شود و نوبت به بعدی می‌رسد. صفر یعنی بدون محدودیت.',
				'condition'   => array( 'source' => 'auto' ),
			)
		);

		$this->add_control(
			'priority',
			array(
				'label'       => 'ویژگی‌های اولویت‌دار',
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'نوع پوست، بافت محصول',
				'description' => 'نام ویژگی‌ها، جدا با کاما. این‌ها (اگر کوتاه باشند) اول می‌آیند و بقیه به ترتیب خود محصول.',
				'condition'   => array( 'source' => 'auto' ),
			)
		);

		$this->add_control(
			'exclude',
			array(
				'label'       => 'ویژگی‌هایی که نمایش داده نشوند',
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'رنگ، سایز',
				'condition'   => array( 'source' => 'auto' ),
			)
		);

		$this->add_control(
			'skip_variation',
			array(
				'label'        => 'رد کردن ویژگی‌های متغیر',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'description'  => 'ویژگی‌هایی مثل رنگ و سایز که خریدار انتخابشان می‌کند، اینجا معمولاً فهرست بلندی می‌شوند.',
				'condition'    => array( 'source' => 'auto' ),
			)
		);

		$this->add_control(
			'skip_hidden',
			array(
				'label'       => 'رد کردن ویژگی‌های پنهان',
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => 'ویژگی‌هایی که تیک «نمایش در صفحه محصول» را ندارند.',
				'condition'   => array( 'source' => 'auto' ),
			)
		);

		$this->add_control(
			'multi',
			array(
				'label'     => 'ویژگی چندمقداری',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'all',
				'options'   => array(
					'all'   => 'همهٔ مقدارها با ویرگول',
					'first' => 'فقط مقدار اول',
				),
				'condition' => array( 'source' => 'auto' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'key',
			array(
				'label'   => 'عنوان',
				'type'    => Controls_Manager::TEXT,
				'default' => 'نوع پوست',
			)
		);

		$repeater->add_control(
			'value',
			array(
				'label'   => 'مقدار',
				'type'    => Controls_Manager::TEXT,
				'default' => 'انواع پوست',
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => 'ویژگی‌ها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ key }}}',
				'default'     => array(
					array( 'key' => 'نوع پوست', 'value' => 'انواع پوست' ),
					array( 'key' => 'بافت محصول', 'value' => 'فلوروئید' ),
					array( 'key' => 'ناحیه استفاده', 'value' => 'صورت' ),
					array( 'key' => 'حساسیت', 'value' => 'بدون حساسیت' ),
				),
				'condition'   => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'more_text',
			array(
				'label'       => 'متن «همهٔ مشخصات»',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'مشاهده همه مشخصات',
				'separator'   => 'before',
				'description' => 'اگر پر شود، لینکی کنار عنوان می‌آید که تب مشخصات را باز می‌کند.',
			)
		);

		$this->end_controls_section();

		$this->register_style_controls();
	}

	private function register_style_controls() {
		// ---------------------------------------------------------------- چیدمان
		$this->start_controls_section(
			'section_style_grid',
			array(
				'label' => 'چیدمان',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => 'ستون‌ها',
				'type'           => Controls_Manager::SELECT,
				'default'        => '4',
				'tablet_default' => '4',
				'mobile_default' => '2',
				'options'        => array(
					'1' => '۱',
					'2' => '۲',
					'3' => '۳',
					'4' => '۴',
					'5' => '۵',
					'6' => '۶',
				),
				'selectors'      => array(
					'{{WRAPPER}} .ard-pfeat' => '--ard-pfeat-cols:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'grid_gap',
			array(
				'label'      => 'فاصلهٔ کارت‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pfeat' => '--ard-pfeat-gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'head_gap',
			array(
				'label'      => 'فاصلهٔ عنوان تا کارت‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pfeat' => '--ard-pfeat-head-gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// ---------------------------------------------------------------- عنوان
		$this->start_controls_section(
			'section_style_title',
			array(
				'label'     => 'عنوان',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'title!' => '' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typo',
				'selector' => '{{WRAPPER}} .ard-pfeat__title',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => 'رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pfeat__title' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'more_color',
			array(
				'label'     => 'رنگ لینک همهٔ مشخصات',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pfeat__more' => 'color:{{VALUE}};',
				),
				'condition' => array( 'more_text!' => '' ),
			)
		);

		$this->end_controls_section();

		// ---------------------------------------------------------------- کارت
		$this->start_controls_section(
			'section_style_card',
			array(
				'label' => 'کارت',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'card_align',
			array(
				'label'     => 'تراز متن',
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'right'  => array( 'title' => 'راست', 'icon' => 'eicon-text-align-right' ),
					'center' => array( 'title' => 'وسط', 'icon' => 'eicon-text-align-center' ),
					'left'   => array( 'title' => 'چپ', 'icon' => 'eicon-text-align-left' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .ard-pfeat__item' => 'text-align:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_bg',
			array(
				'label'     => 'پس‌زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pfeat' => '--ard-pfeat-bg:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_pad',
			array(
				'label'      => 'فاصلهٔ داخلی',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pfeat__item' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_inner_gap',
			array(
				'label'      => 'فاصلهٔ عنوان و مقدار',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pfeat' => '--ard-pfeat-inner-gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_min_h',
			array(
				'label'      => 'حداقل ارتفاع',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 200 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pfeat__item' => 'min-block-size:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_radius',
			array(
				'label'      => 'گردی گوشه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pfeat' => '--ard-pfeat-radius:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} .ard-pfeat__item',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_shadow',
				'selector' => '{{WRAPPER}} .ard-pfeat__item',
			)
		);

		$this->end_controls_section();

		// ---------------------------------------------------------------- متن کارت
		$this->start_controls_section(
			'section_style_text',
			array(
				'label' => 'عنوان و مقدار ویژگی',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'key_typo',
				'label'    => 'تایپوگرافی عنوان',
				'selector' => '{{WRAPPER}} .ard-pfeat__key',
			)
		);

		$this->add_control(
			'key_color',
			array(
				'label'     => 'رنگ عنوان',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pfeat__key' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'value_typo',
				'label'     => 'تایپوگرافی مقدار',
				'selector'  => '{{WRAPPER}} .ard-pfeat__value',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'value_color',
			array(
				'label'     => 'رنگ مقدار',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-pfeat__value' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'value_clamp',
			array(
				'label'        => 'مقدار فقط یک خط',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'description'  => 'متن بلندتر با «…» کوتاه می‌شود.',
				'prefix_class' => 'ard-pfeat-oneline-',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( 'manual' === $s['source'] ) {
			$items = $this->manual_items( $s );
		} else {
			$product = Ardal_Product::current();

			if ( ! $product ) {
				$this->render_empty( 'محصولی پیدا نشد. این ویجت را روی قالب تک‌محصول بگذارید یا حالت دستی را انتخاب کنید.' );

				return;
			}

			$items = $this->auto_items( $product, $s );
		}

		if ( empty( $items ) ) {
			$this->render_empty( 'ویژگی کوتاهی برای نمایش پیدا نشد. «حداکثر طول مقدار» را بیشتر کنید یا به محصول ویژگی بدهید.' );

			return;
		}

		$title = trim( (string) $s['title'] );
		$more  = trim( (string) $s['more_text'] );
		$tag   = self::tag( $s['title_tag'], 'h3' );
		?>
		<div class="ard-pfeat">
			<?php if ( '' !== $title || '' !== $more ) : ?>
				<div class="ard-pfeat__head">
					<?php if ( '' !== $title ) : ?>
						<<?php echo esc_html( $tag ); ?> class="ard-pfeat__title"><?php echo esc_html( $title ); ?></<?php echo esc_html( $tag ); ?>>
					<?php endif; ?>

					<?php if ( '' !== $more ) : ?>
						<a class="ard-pfeat__more" href="#specs" data-ard-jump="specs" data-offset="100"><?php echo esc_html( $more ); ?></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<dl class="ard-pfeat__grid">
				<?php foreach ( $items as $item ) : ?>
					<div class="ard-pfeat__item">
						<dt class="ard-pfeat__key"><?php echo esc_html( $item['key'] ); ?></dt>
						<dd class="ard-pfeat__value" title="<?php echo esc_attr( $item['value'] ); ?>"><?php echo esc_html( $item['value'] ); ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>
		</div>
		<?php
	}

	/**
	 * @param array $s
	 * @return array
	 */
	private function manual_items( $s ) {
		$out = array();

		foreach ( (array) $s['items'] as $row ) {
			$key   = trim( (string) $row['key'] );
			$value = trim( (string) $row['value'] );

			if ( '' === $key || '' === $value ) {
				continue;
			}

			$out[] = array( 'key' => $key, 'value' => Ardal_Helpers::fa_digits( $value ) );

			if ( count( $out ) >= max( 1, (int) $s['limit'] ) ) {
				break;
			}
		}

		return $out;
	}

	/**
	 * ویژگی‌های کوتاه محصول؛ اول اولویت‌دارها، بعد به ترتیب خود محصول.
	 *
	 * @param WC_Product $product
	 * @param array      $s
	 * @return array
	 */
	private function auto_items( $product, $s ) {
		$limit    = max( 1, (int) $s['limit'] );
		$max_len  = max( 0, (int) $s['max_len'] );
		$priority = self::names( $s['priority'] );
		$exclude  = self::names( $s['exclude'] );
		$rows     = array();

		foreach ( $product->get_attributes() as $attribute ) {
			if ( ! $attribute instanceof WC_Product_Attribute ) {
				continue;
			}

			if ( 'yes' === $s['skip_variation'] && $attribute->get_variation() ) {
				continue;
			}

			if ( 'yes' === $s['skip_hidden'] && ! $attribute->get_visible() ) {
				continue;
			}

			$name = wc_attribute_label( $attribute->get_name(), $product );

			if ( self::listed( $name, $attribute->get_name(), $exclude ) ) {
				continue;
			}

			if ( $attribute->is_taxonomy() ) {
				$terms  = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
				$values = is_wp_error( $terms ) ? array() : $terms;
			} else {
				$values = $attribute->get_options();
			}

			$values = array_values( array_filter( array_map( 'trim', (array) $values ), 'strlen' ) );

			if ( empty( $values ) ) {
				continue;
			}

			$value = 'first' === $s['multi'] ? $values[0] : implode( '، ', $values );

			if ( $max_len > 0 && mb_strlen( $value ) > $max_len ) {
				continue;
			}

			$rank = self::rank( $name, $attribute->get_name(), $priority );

			$rows[] = array(
				'key'   => $name,
				'value' => Ardal_Helpers::fa_digits( $value ),
				// ترتیب: جای نام در فهرست اولویت، بعد جای خود ویژگی در محصول.
				'sort'  => array( $rank, count( $rows ) ),
			);
		}

		usort(
			$rows,
			static function ( $a, $b ) {
				return $a['sort'] <=> $b['sort'];
			}
		);

		return array_slice( $rows, 0, $limit );
	}

	/**
	 * «الف، ب, ج» ← آرایهٔ نام‌ها.
	 *
	 * @param string $raw
	 * @return array
	 */
	private static function names( $raw ) {
		$parts = preg_split( '/[,،\n]+/u', (string) $raw );

		return array_values( array_filter( array_map( 'trim', (array) $parts ), 'strlen' ) );
	}

	/**
	 * نام ویژگی (برچسب یا اسلاگ، مثل «رنگ» یا pa_color) در فهرست هست؟
	 *
	 * @param string $label
	 * @param string $slug
	 * @param array  $list
	 * @return bool
	 */
	private static function listed( $label, $slug, $list ) {
		return PHP_INT_MAX !== self::rank( $label, $slug, $list );
	}

	/**
	 * @param string $label
	 * @param string $slug
	 * @param array  $list
	 * @return int جای نام در فهرست، یا PHP_INT_MAX.
	 */
	private static function rank( $label, $slug, $list ) {
		$slug = preg_replace( '/^pa_/', '', urldecode( (string) $slug ) );

		foreach ( $list as $i => $needle ) {
			$needle = preg_replace( '/^pa_/', '', $needle );

			if ( 0 === strcasecmp( $needle, $label ) || 0 === strcasecmp( $needle, $slug ) ) {
				return $i;
			}
		}

		return PHP_INT_MAX;
	}
}
