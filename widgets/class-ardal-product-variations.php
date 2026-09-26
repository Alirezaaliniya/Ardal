<?php
/**
 * ویجت جدول واریاسیون‌ها: یک ردیف برای هر ترکیب، با قیمت و وضعیت موجودی.
 *
 * برای محصولی که چند رنگ/سایز/کیفیت دارد، خریدار می‌خواهد همه را کنار هم
 * ببیند و مقایسه کند — نه اینکه برای هر ترکیب یک بار انتخاب کند و قیمت را
 * بخواند. واریاسیون ناموجود هم در جدول می‌ماند (خط‌خورده)، چون «نداریم» هم
 * خودش یک اطلاعات است.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Product_Variations extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-product-variations';
	}

	public function get_title() {
		return 'اردال — جدول واریاسیون‌ها';
	}

	public function get_icon() {
		return 'eicon-table';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'جدول', 'واریاسیون', 'قیمت', 'موجودی', 'table', 'variation' );
	}

	protected function register_controls() {

		/* ------------------------------------------------ محتوا */

		$this->start_controls_section(
			'section_content',
			array( 'label' => 'جدول واریاسیون‌ها' )
		);

		$this->add_control(
			'source',
			array(
				'label'   => 'منبع',
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => array(
					'auto'   => 'خودکار از محصول جاری',
					'manual' => 'یک محصول مشخص',
				),
			)
		);

		$this->add_control(
			'product_id',
			array(
				'label'       => 'شناسه محصول',
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'description' => 'شناسهٔ محصول متغیر.',
				'condition'   => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => 'عنوان جدول',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => 'خالی = بدون عنوان.',
			)
		);

		$this->add_tag_control(
			'title_tag',
			'تگ عنوان',
			'h3',
			array( 'condition' => array( 'title!' => '' ) )
		);

		$this->add_control(
			'attributes',
			array(
				'label'       => 'ویژگی‌های ستون‌ها',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'placeholder' => 'pa_color,pa_size',
				'description' => 'با کاما. خالی = همهٔ ویژگی‌هایی که واریاسیون می‌سازند.',
			)
		);

		$this->add_control(
			'headers',
			array(
				'label'       => 'عنوان دلخواه ستون‌ها',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'placeholder' => 'رنگ,سایز',
				'description' => 'به همان ترتیب ستون‌ها، با کاما. خالی = نام خود ویژگی.',
			)
		);

		$this->add_control(
			'price_label',
			array(
				'label'   => 'عنوان ستون قیمت',
				'type'    => Controls_Manager::TEXT,
				'default' => 'قیمت',
			)
		);

		$this->add_control(
			'show_old',
			array(
				'label'        => 'نمایش قیمت پیش از تخفیف',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_off',
			array(
				'label'        => 'نمایش درصد تخفیف',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_stock',
			array(
				'label'        => 'ستون وضعیت موجودی',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'stock_label',
			array(
				'label'     => 'عنوان ستون وضعیت',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'وضعیت',
				'condition' => array( 'show_stock' => 'yes' ),
			)
		);

		$this->add_control(
			'in_text',
			array(
				'label'     => 'متن موجود',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'موجود',
				'condition' => array( 'show_stock' => 'yes' ),
			)
		);

		$this->add_control(
			'out_text',
			array(
				'label'     => 'متن ناموجود',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'ناموجود',
				'condition' => array( 'show_stock' => 'yes' ),
			)
		);

		$this->add_control(
			'hide_out',
			array(
				'label'        => 'واریاسیون‌های ناموجود نمایش داده نشوند',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'description'  => 'پیش‌فرض خاموش: دیدنِ «نداریم» هم برای خریدار اطلاعات است.',
			)
		);

		$this->add_control(
			'empty_text',
			array(
				'label'       => 'متن وقتی واریاسیونی نیست',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => 'خالی = کل جدول چاپ نشود.',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ ظاهر */

		$this->start_controls_section(
			'section_style',
			array(
				'label' => 'ظاهر جدول',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'head_bg',
			array(
				'label'     => 'زمینه سرستون‌ها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-vtable' => '--ard-vt-head:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'head_color',
			array(
				'label'     => 'رنگ متن سرستون‌ها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-vtable' => '--ard-vt-head-ink:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'line',
			array(
				'label'     => 'رنگ خطوط',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-vtable' => '--ard-vt-line:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'zebra',
			array(
				'label'     => 'زمینه ردیف‌های یک‌درمیان',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-vtable' => '--ard-vt-zebra:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => 'رنگ قیمت',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-vtable' => '--ard-vt-price:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'radius',
			array(
				'label'      => 'گردی گوشه‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 32 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-vtable' => '--ard-vt-radius:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'cell_padding',
			array(
				'label'      => 'فاصله داخلی خانه‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 4, 'max' => 28 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-vtable' => '--ard-vt-pad:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cell_typo',
				'label'    => 'قلم جدول',
				'selector' => '{{WRAPPER}} .ard-vtable__table',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		if ( $this->require_woo() ) {
			return;
		}

		$s       = $this->get_settings_for_display();
		$product = 'manual' === $s['source'] && (int) $s['product_id'] > 0
			? wc_get_product( (int) $s['product_id'] )
			: Ardal_Product::current();

		if ( ! $product instanceof WC_Product ) {
			$this->render_empty( 'محصولی پیدا نشد. این ویجت را روی قالب تک‌محصول بگذارید یا شناسهٔ محصول را بنویسید.' );

			return;
		}

		$columns = $this->columns( $product, $s );
		$rows    = $this->rows( $product, $columns, $s );

		if ( ! $rows ) {
			$empty = trim( (string) $s['empty_text'] );

			if ( '' === $empty ) {
				$this->render_empty( 'این محصول واریاسیون قابل نمایشی ندارد.' );

				return;
			}

			printf( '<p class="ard-vtable__empty">%s</p>', esc_html( $empty ) );

			return;
		}

		$show_stock = 'yes' === $s['show_stock'];
		$price_head = trim( (string) $s['price_label'] );

		// محصول ساده ستون ویژگی ندارد؛ نامش ستون اول می‌شود.
		$name_head = $columns ? '' : 'محصول';
		?>
		<div class="ard-vtable" data-product="<?php echo esc_attr( $product->get_id() ); ?>">
			<?php if ( '' !== trim( (string) $s['title'] ) ) : ?>
				<?php $tag = self::tag( $s['title_tag'], 'h3' ); ?>
				<<?php echo esc_html( $tag ); ?> class="ard-vtable__title"><?php echo esc_html( $s['title'] ); ?></<?php echo esc_html( $tag ); ?>>
			<?php endif; ?>

			<?php
			/*
			 * پوشش اسکرول‌دار: جدول چندستونه در موبایل جا نمی‌شود و بدون این،
			 * کل صفحه افقی اسکرول می‌خورد.
			 */
			?>
			<div class="ard-vtable__scroll">
				<table class="ard-vtable__table">
					<thead>
						<tr>
							<?php if ( '' !== $name_head ) : ?>
								<th scope="col"><?php echo esc_html( $name_head ); ?></th>
							<?php endif; ?>

							<?php foreach ( $columns as $column ) : ?>
								<th scope="col"><?php echo esc_html( $column['label'] ); ?></th>
							<?php endforeach; ?>

							<th scope="col" class="ard-vtable__price-col"><?php echo esc_html( $price_head ); ?></th>

							<?php if ( $show_stock ) : ?>
								<th scope="col" class="ard-vtable__stock-col"><?php echo esc_html( $s['stock_label'] ); ?></th>
							<?php endif; ?>
						</tr>
					</thead>

					<tbody>
						<?php foreach ( $rows as $row ) : ?>
							<tr class="ard-vtable__row<?php echo $row['in'] ? '' : ' is-out'; ?>">
								<?php if ( '' !== $name_head ) : ?>
									<td data-label="<?php echo esc_attr( $name_head ); ?>"><?php echo esc_html( $row['name'] ); ?></td>
								<?php endif; ?>

								<?php foreach ( $columns as $column ) : ?>
									<?php $cell = isset( $row['cells'][ $column['key'] ] ) ? $row['cells'][ $column['key'] ] : ''; ?>
									<td data-label="<?php echo esc_attr( $column['label'] ); ?>"><?php echo esc_html( '' !== $cell ? $cell : '—' ); ?></td>
								<?php endforeach; ?>

								<td class="ard-vtable__price" data-label="<?php echo esc_attr( $price_head ); ?>">
									<?php if ( 'yes' === $s['show_off'] && $row['off'] > 0 ) : ?>
										<span class="ard-vtable__off"><?php echo esc_html( Ardal_Helpers::fa_digits( $row['off'] ) . '٪' ); ?></span>
									<?php endif; ?>

									<?php if ( 'yes' === $s['show_old'] && $row['old'] > 0 ) : ?>
										<del class="ard-vtable__old"><?php echo esc_html( Ardal_Helpers::price( $row['old'] ) ); ?></del>
									<?php endif; ?>

									<span class="ard-vtable__now">
										<?php echo esc_html( Ardal_Helpers::price( $row['now'] ) ); ?>
										<?php echo Ardal_Currency::unit(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خروجی ثابت و امن. ?>
									</span>
								</td>

								<?php if ( $show_stock ) : ?>
									<td class="ard-vtable__stock" data-label="<?php echo esc_attr( $s['stock_label'] ); ?>">
										<span class="ard-vtable__tag<?php echo $row['in'] ? '' : ' is-out'; ?>">
											<?php echo esc_html( $row['in'] ? $s['in_text'] : $s['out_text'] ); ?>
										</span>
									</td>
								<?php endif; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	}

	/**
	 * ستون‌های جدول: هر ویژگیِ واریاسیون‌ساز یک ستون.
	 *
	 * @param WC_Product $product
	 * @param array      $s
	 * @return array هر آیتم: key و label.
	 */
	private function columns( $product, $s ) {
		$wanted = array_values( array_filter( array_map( 'trim', explode( ',', (string) $s['attributes'] ) ) ) );
		$out    = array();

		foreach ( $product->get_attributes() as $attribute ) {
			if ( ! $attribute instanceof WC_Product_Attribute || ! $attribute->get_variation() ) {
				continue;
			}

			$name = $attribute->get_name();
			$key  = 'attribute_' . sanitize_title( $name );

			// فهرست دلخواه، چه با نام ویژگی نوشته شده باشد چه با کلید کامل.
			if ( $wanted && ! in_array( $name, $wanted, true ) && ! in_array( $key, $wanted, true ) ) {
				continue;
			}

			$out[] = array(
				'key'      => $key,
				'label'    => wc_attribute_label( $name, $product ),
				'taxonomy' => $attribute->is_taxonomy() ? $name : '',
			);
		}

		// ترتیب و نام دلخواه ستون‌ها، به همان ترتیبی که کاربر نوشته.
		if ( $wanted ) {
			usort(
				$out,
				static function ( $a, $b ) use ( $wanted ) {
					$pos = static function ( $column ) use ( $wanted ) {
						foreach ( $wanted as $index => $one ) {
							if ( $column['key'] === 'attribute_' . sanitize_title( $one ) || $column['key'] === $one ) {
								return $index;
							}
						}

						return PHP_INT_MAX;
					};

					return $pos( $a ) <=> $pos( $b );
				}
			);
		}

		$headers = array_values( array_filter( array_map( 'trim', explode( ',', (string) $s['headers'] ) ) ) );

		foreach ( $out as $index => $column ) {
			if ( isset( $headers[ $index ] ) && '' !== $headers[ $index ] ) {
				$out[ $index ]['label'] = $headers[ $index ];
			}
		}

		return $out;
	}

	/**
	 * ردیف‌های جدول.
	 *
	 * @param WC_Product $product
	 * @param array      $columns
	 * @param array      $s
	 * @return array
	 */
	private function rows( $product, $columns, $s ) {
		$hide_out = 'yes' === $s['hide_out'];

		if ( ! $product->is_type( 'variable' ) ) {
			$now = (float) $product->get_price();

			if ( $now <= 0 || ( $hide_out && ! $product->is_in_stock() ) ) {
				return array();
			}

			$regular = (float) $product->get_regular_price();

			return array(
				array(
					'name'  => $product->get_name(),
					'cells' => array(),
					'now'   => $now,
					'old'   => $regular > $now ? $regular : 0,
					'off'   => $regular > $now ? (int) round( ( ( $regular - $now ) / $regular ) * 100 ) : 0,
					'in'    => $product->is_in_stock(),
				),
			);
		}

		$rows = array();

		foreach ( Ardal_Product::variation_map( $product ) as $variation ) {
			if ( $variation['price'] <= 0 ) {
				continue;
			}

			if ( $hide_out && ( ! $variation['stock'] || ! $variation['buy'] ) ) {
				continue;
			}

			$cells = array();

			foreach ( $columns as $column ) {
				$slug = isset( $variation['attrs'][ $column['key'] ] ) ? $variation['attrs'][ $column['key'] ] : '';

				$cells[ $column['key'] ] = $this->cell_label( $slug, $column['taxonomy'] );
			}

			$rows[] = array(
				'name'  => '',
				'cells' => $cells,
				'now'   => $variation['price'],
				'old'   => $variation['reg'] > $variation['price'] ? $variation['reg'] : 0,
				'off'   => (int) $variation['off'],
				'in'    => $variation['stock'] && $variation['buy'],
			);
		}

		return $rows;
	}

	/**
	 * نام خواندنیِ یک مقدار ویژگی.
	 *
	 * نقشهٔ واریاسیون اسلاگ می‌دهد؛ برای ویژگی تاکسونومی‌دار باید نام ترم را
	 * نشان داد، وگرنه در جدول «blue» می‌نشیند نه «آبی».
	 *
	 * @param string $slug
	 * @param string $taxonomy رشتهٔ خالی یعنی ویژگی سفارشی است.
	 * @return string
	 */
	private function cell_label( $slug, $taxonomy ) {
		if ( '' === $slug ) {
			return '';
		}

		if ( '' === $taxonomy ) {
			return $slug;
		}

		$term = get_term_by( 'slug', $slug, $taxonomy );

		return ( $term && ! is_wp_error( $term ) ) ? $term->name : $slug;
	}
}
