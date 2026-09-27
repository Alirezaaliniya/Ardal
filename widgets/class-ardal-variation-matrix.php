<?php
/**
 * ویجت ماتریس واریاسیون‌ها.
 *
 * فرقش با «جدول واریاسیون‌ها» در شکل خواندن است: آنجا هر واریاسیون یک ردیف
 * است، اینجا یک ویژگی روی ردیف‌ها می‌نشیند و ویژگی دیگر روی ستون‌ها و خانهٔ
 * برخوردشان می‌گوید آن ترکیب موجود است، ناموجود، یا اصلاً ساخته نشده. برای
 * «کدام سایزِ این رنگ هست؟» یک نگاه کافی است.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Variation_Matrix extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-variation-matrix';
	}

	public function get_title() {
		return 'اردال — ماتریس واریاسیون‌ها';
	}

	public function get_icon() {
		return 'eicon-table';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'ماتریس', 'واریاسیون', 'موجودی', 'رنگ', 'سایز', 'matrix', 'variation' );
	}

	protected function register_controls() {

		/* ------------------------------------------------ محتوا */

		$this->start_controls_section(
			'section_content',
			array( 'label' => 'ماتریس' )
		);

		$this->add_control(
			'source',
			array(
				'label'   => 'محصول',
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => array(
					'auto'   => 'محصول صفحهٔ جاری',
					'manual' => 'یک محصول مشخص',
				),
			)
		);

		$this->add_control(
			'product_id',
			array(
				'label'     => 'شناسه محصول',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'default'   => 0,
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => 'عنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'موجودی ترکیب‌ها',
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
			'row_attr',
			array(
				'label'       => 'ویژگی ردیف‌ها',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'placeholder' => 'pa_color',
				'description' => 'نام ویژگی (مثلاً pa_color). خالی = نخستین ویژگیِ واریاسیون‌ساز.',
			)
		);

		$this->add_control(
			'col_attr',
			array(
				'label'       => 'ویژگی ستون‌ها',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'placeholder' => 'pa_size',
				'description' => 'خالی = دومین ویژگیِ واریاسیون‌ساز.',
			)
		);

		$this->add_control(
			'row_label',
			array(
				'label'       => 'عنوان ستون نخست',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => 'خالی = نام همان ویژگی.',
			)
		);

		$this->add_control(
			'cols_source',
			array(
				'label'       => 'ستون‌ها شامل',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'product',
				'options'     => array(
					'product' => 'فقط مقدارهای همین محصول',
					'all'     => 'همهٔ مقدارهای تعریف‌شدهٔ ویژگی',
				),
				'description' => '«همه» ستون‌های ارائه‌نشده را هم نشان می‌دهد تا معلوم شود چه چیزی موجود نیست.',
			)
		);

		$this->add_control(
			'rows_source',
			array(
				'label'   => 'ردیف‌ها شامل',
				'type'    => Controls_Manager::SELECT,
				'default' => 'product',
				'options' => array(
					'product' => 'فقط مقدارهای همین محصول',
					'all'     => 'همهٔ مقدارهای تعریف‌شدهٔ ویژگی',
				),
			)
		);

		$this->add_control(
			'show_legend',
			array(
				'label'        => 'راهنمای رنگ‌ها',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_filters',
			array(
				'label'        => 'دکمه‌های فیلتر موجودی',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_prices',
			array(
				'label'        => 'قیمت داخل خانه‌های موجود',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'note',
			array(
				'label'       => 'یادداشت زیر جدول',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'default'     => '',
				'label_block' => true,
				'description' => 'خالی = بدون یادداشت.',
			)
		);

		$this->add_control(
			'empty_text',
			array(
				'label'       => 'متن وقتی ترکیبی نیست',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => 'خالی = کل ناحیه چاپ نشود.',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ برچسب‌ها */

		$this->start_controls_section(
			'section_labels',
			array( 'label' => 'واژه‌ها' )
		);

		foreach ( $this->states() as $state => $label ) {
			$this->add_control(
				'word_' . $state,
				array(
					'label'   => 'واژهٔ «' . $label . '»',
					'type'    => Controls_Manager::TEXT,
					'default' => $label,
				)
			);
		}

		$this->add_control(
			'word_all',
			array(
				'label'     => 'دکمهٔ «همه»',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'همه',
				'condition' => array( 'show_filters' => 'yes' ),
			)
		);

		$this->add_control(
			'word_no_match',
			array(
				'label'     => 'پیام وقتی فیلتر چیزی نمی‌یابد',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'با این فیلتر چیزی برای نمایش نیست.',
				'condition' => array( 'show_filters' => 'yes' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ ظاهر */

		$this->start_controls_section(
			'section_style',
			array(
				'label' => 'ظاهر',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'head_bg',
			array(
				'label'     => 'زمینه سرستون‌ها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-mtx' => '--ard-mtx-head-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'head_color',
			array(
				'label'     => 'رنگ متن سرستون‌ها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-mtx' => '--ard-mtx-head-ink:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'line',
			array(
				'label'     => 'رنگ خطوط',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-mtx' => '--ard-mtx-line:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'in_color',
			array(
				'label'     => 'رنگ «موجود»',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-mtx' => '--ard-mtx-in:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'out_color',
			array(
				'label'     => 'رنگ «ناموجود»',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-mtx' => '--ard-mtx-out:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'none_color',
			array(
				'label'     => 'رنگ «ارائه نشده»',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-mtx' => '--ard-mtx-none:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => 'رنگ قیمت',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-mtx' => '--ard-mtx-price:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'radius',
			array(
				'label'      => 'گردی قاب',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 30 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-mtx' => '--ard-mtx-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'cell_pad',
			array(
				'label'      => 'فاصله داخلی خانه‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 2, 'max' => 24 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-mtx' => '--ard-mtx-pad:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cell_typo',
				'label'    => 'قلم جدول',
				'selector' => '{{WRAPPER}} .ard-mtx__table',
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

		if ( ! $product ) {
			$this->render_empty( 'محصولی برای این ماتریس پیدا نشد. یا ویجت را در قالب صفحهٔ محصول بگذارید یا شناسهٔ محصول را دستی بنویسید.' );

			return;
		}

		$data  = $this->matrix( $product, $s );
		$empty = trim( (string) $s['empty_text'] );

		if ( ! $data['rows'] || ! $data['cols'] ) {
			if ( '' === $empty ) {
				$this->render_empty( 'این محصول دو ویژگیِ واریاسیون‌ساز ندارد، پس ماتریسی برای ساختن نیست. برای محصول‌های تک‌ویژگی از ویجت «جدول واریاسیون‌ها» استفاده کنید.' );

				return;
			}
			?>
			<p class="ard-mtx__empty"><?php echo esc_html( $empty ); ?></p>
			<?php

			return;
		}

		$words   = $this->words( $s );
		$filters = 'yes' === $s['show_filters'];
		$prices  = 'yes' === $s['show_prices'];
		$head    = '' !== trim( (string) $s['row_label'] ) ? $s['row_label'] : $data['row_label'];
		$note    = trim( (string) $s['note'] );
		?>
		<section class="ard-mtx" data-ard-matrix>

			<?php if ( '' !== trim( (string) $s['title'] ) ) : ?>
				<?php $tag = self::tag( $s['title_tag'], 'h3' ); ?>
				<<?php echo esc_html( $tag ); ?> class="ard-mtx__title"><?php echo esc_html( $s['title'] ); ?></<?php echo esc_html( $tag ); ?>>
			<?php endif; ?>

			<?php if ( 'yes' === $s['show_legend'] || $filters ) : ?>
				<div class="ard-mtx__bar">
					<?php if ( 'yes' === $s['show_legend'] ) : ?>
						<div class="ard-mtx__legend">
							<?php foreach ( array_keys( $this->states() ) as $state ) : ?>
								<span class="ard-mtx__legend-item is-<?php echo esc_attr( $state ); ?>">
									<?php $this->state_icon( $state ); ?>
									<?php echo esc_html( $words[ $state ] ); ?>
								</span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<?php if ( $filters ) : ?>
						<div class="ard-mtx__filters" role="group" aria-label="نمایش بر پایهٔ موجودی">
							<button type="button" class="ard-mtx__filter is-active" data-ard-matrix-filter="all" aria-pressed="true"><?php echo esc_html( $words['all'] ); ?></button>
							<button type="button" class="ard-mtx__filter" data-ard-matrix-filter="in" aria-pressed="false"><?php echo esc_html( 'فقط ' . $words['in'] ); ?></button>
							<button type="button" class="ard-mtx__filter" data-ard-matrix-filter="out" aria-pressed="false"><?php echo esc_html( 'فقط ' . $words['out'] ); ?></button>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="ard-mtx__scroll">
				<table class="ard-mtx__table">
					<thead>
						<tr>
							<th scope="col" class="ard-mtx__row-head"><?php echo esc_html( $head ); ?></th>

							<?php foreach ( $data['cols'] as $col ) : ?>
								<th scope="col" class="ard-mtx__col"><?php echo esc_html( $col['label'] ); ?></th>
							<?php endforeach; ?>
						</tr>
					</thead>

					<tbody>
						<?php foreach ( $data['rows'] as $row ) : ?>
							<?php
							$states = wp_list_pluck( $row['cells'], 'state' );
							?>
							<tr class="ard-mtx__row"
								data-has-in="<?php echo in_array( 'in', $states, true ) ? '1' : '0'; ?>"
								data-has-out="<?php echo in_array( 'out', $states, true ) ? '1' : '0'; ?>">

								<th scope="row" class="ard-mtx__name" data-label="<?php echo esc_attr( $head ); ?>"><?php echo esc_html( $row['label'] ); ?></th>

								<?php foreach ( $row['cells'] as $cell ) : ?>
									<td class="ard-mtx__cell is-<?php echo esc_attr( $cell['state'] ); ?>"
										data-state="<?php echo esc_attr( $cell['state'] ); ?>"
										data-label="<?php echo esc_attr( $cell['col'] ); ?>">
										<span class="ard-mtx__box">
											<?php $this->state_icon( $cell['state'] ); ?>

											<span class="ard-mtx__text">
												<span class="ard-mtx__state"><?php echo esc_html( $words[ $cell['state'] ] ); ?></span>

												<?php if ( $prices && 'in' === $cell['state'] && $cell['price'] > 0 ) : ?>
													<span class="ard-mtx__price">
														<?php echo esc_html( Ardal_Helpers::price( $cell['price'] ) ); ?>
														<?php echo Ardal_Currency::unit(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خروجی ثابت و امن. ?>
													</span>
												<?php endif; ?>
											</span>
										</span>
									</td>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<?php if ( $filters ) : ?>
					<p class="ard-mtx__none" data-ard-matrix-empty hidden><?php echo esc_html( $words['no_match'] ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( '' !== $note ) : ?>
				<p class="ard-mtx__note"><?php echo esc_html( $note ); ?></p>
			<?php endif; ?>
		</section>
		<?php
	}

	/**
	 * سه حالت خانه و نام پیش‌فرضشان.
	 *
	 * @return array
	 */
	private function states() {
		return array(
			'in'   => 'موجود',
			'out'  => 'ناموجود',
			'none' => 'ارائه نشده',
		);
	}

	/**
	 * واژه‌های نهایی، با جایگزین‌های کاربر.
	 *
	 * @param array $s
	 * @return array
	 */
	private function words( $s ) {
		$out = array();

		foreach ( $this->states() as $state => $fallback ) {
			$given         = trim( (string) $s[ 'word_' . $state ] );
			$out[ $state ] = '' !== $given ? $given : $fallback;
		}

		$out['all']      = isset( $s['word_all'] ) && '' !== trim( (string) $s['word_all'] ) ? $s['word_all'] : 'همه';
		$out['no_match'] = isset( $s['word_no_match'] ) && '' !== trim( (string) $s['word_no_match'] )
			? $s['word_no_match']
			: 'با این فیلتر چیزی برای نمایش نیست.';

		return $out;
	}

	/**
	 * نشانِ حالت خانه. سه شکل متفاوت، نه فقط سه رنگ — تا برای کسی که رنگ را
	 * تشخیص نمی‌دهد هم خوانا بماند.
	 *
	 * @param string $state
	 */
	private function state_icon( $state ) {
		$paths = array(
			'in'   => '<path d="M7.5 12.4l3 3 6-6.4" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>',
			'out'  => '<path d="M8.6 8.6l6.8 6.8M15.4 8.6l-6.8 6.8" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/>',
			'none' => '<path d="M8 12h8" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"/>',
		);

		printf(
			'<svg class="ard-mtx__icon" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="10" fill="currentColor"/>%s</svg>',
			isset( $paths[ $state ] ) ? $paths[ $state ] : $paths['none'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}

	/**
	 * ساخت ماتریس.
	 *
	 * @param WC_Product $product
	 * @param array      $s
	 * @return array{rows:array, cols:array, row_label:string}
	 */
	private function matrix( $product, $s ) {
		$empty = array( 'rows' => array(), 'cols' => array(), 'row_label' => '' );

		if ( ! $product->is_type( 'variable' ) ) {
			return $empty;
		}

		$attributes = $this->variation_attributes( $product );

		if ( count( $attributes ) < 2 ) {
			return $empty;
		}

		$row = $this->pick( $attributes, $s['row_attr'], 0 );
		$col = $this->pick( $attributes, $s['col_attr'], 1 );

		// اگر کاربر هر دو را یکی نوشت، ستون را به ویژگی دیگری ببر.
		if ( $row['key'] === $col['key'] ) {
			foreach ( $attributes as $one ) {
				if ( $one['key'] !== $row['key'] ) {
					$col = $one;
					break;
				}
			}
		}

		if ( $row['key'] === $col['key'] ) {
			return $empty;
		}

		$variations = Ardal_Product::variation_map( $product );

		/*
		 * جدولِ جست‌وجو: «اسلاگ ردیف + اسلاگ ستون» → واریاسیون.
		 *
		 * واریاسیونی که مقدار یکی از این دو ویژگی را خالی گذاشته یعنی «هر
		 * مقداری»؛ آن را روی همهٔ مقدارهای آن محور پخش می‌کنیم، وگرنه خانه‌های
		 * درستی خالی می‌مانند.
		 */
		$cells = array();

		$row_values = $this->axis_values( $product, $row, $variations, $s['rows_source'] );
		$col_values = $this->axis_values( $product, $col, $variations, $s['cols_source'] );

		foreach ( $variations as $variation ) {
			$r_slug = isset( $variation['attrs'][ $row['key'] ] ) ? $variation['attrs'][ $row['key'] ] : '';
			$c_slug = isset( $variation['attrs'][ $col['key'] ] ) ? $variation['attrs'][ $col['key'] ] : '';

			$r_list = '' !== $r_slug ? array( $r_slug ) : array_keys( $row_values );
			$c_list = '' !== $c_slug ? array( $c_slug ) : array_keys( $col_values );

			foreach ( $r_list as $r ) {
				foreach ( $c_list as $c ) {
					$key   = $r . '|' . $c;
					$state = ( $variation['stock'] && $variation['buy'] ) ? 'in' : 'out';

					// یک ترکیبِ موجود بر ناموجود می‌چربد؛ همین‌طور قیمتِ کمتر.
					if ( isset( $cells[ $key ] ) && 'in' === $cells[ $key ]['state'] ) {
						if ( 'in' !== $state || $variation['price'] >= $cells[ $key ]['price'] ) {
							continue;
						}
					}

					$cells[ $key ] = array(
						'state' => $state,
						'price' => (float) $variation['price'],
					);
				}
			}
		}

		$cols = array();

		foreach ( $col_values as $slug => $label ) {
			$cols[] = array( 'slug' => $slug, 'label' => $label );
		}

		$rows = array();

		foreach ( $row_values as $slug => $label ) {
			$line = array( 'label' => $label, 'cells' => array() );

			foreach ( $cols as $one ) {
				$key  = $slug . '|' . $one['slug'];
				$cell = isset( $cells[ $key ] ) ? $cells[ $key ] : array( 'state' => 'none', 'price' => 0 );

				$line['cells'][] = array(
					'col'   => $one['label'],
					'state' => $cell['state'],
					'price' => $cell['price'],
				);
			}

			$rows[] = $line;
		}

		return array(
			'rows'      => $rows,
			'cols'      => $cols,
			'row_label' => $row['label'],
		);
	}

	/**
	 * ویژگی‌های واریاسیون‌ساز محصول.
	 *
	 * @param WC_Product $product
	 * @return array
	 */
	private function variation_attributes( $product ) {
		$out = array();

		foreach ( $product->get_attributes() as $attribute ) {
			if ( ! $attribute instanceof WC_Product_Attribute || ! $attribute->get_variation() ) {
				continue;
			}

			$name = $attribute->get_name();

			$out[] = array(
				'key'       => 'attribute_' . sanitize_title( $name ),
				'name'      => $name,
				'label'     => wc_attribute_label( $name, $product ),
				'taxonomy'  => $attribute->is_taxonomy() ? $name : '',
				'attribute' => $attribute,
			);
		}

		return $out;
	}

	/**
	 * انتخاب یک محور: نام دلخواه کاربر، وگرنه ویژگیِ شمارهٔ n.
	 *
	 * @param array  $attributes
	 * @param string $wanted
	 * @param int    $fallback_index
	 * @return array
	 */
	private function pick( $attributes, $wanted, $fallback_index ) {
		$wanted = trim( (string) $wanted );

		if ( '' !== $wanted ) {
			foreach ( $attributes as $one ) {
				if ( $one['name'] === $wanted || $one['key'] === $wanted || $one['key'] === 'attribute_' . sanitize_title( $wanted ) ) {
					return $one;
				}
			}
		}

		return isset( $attributes[ $fallback_index ] ) ? $attributes[ $fallback_index ] : $attributes[0];
	}

	/**
	 * مقدارهای یک محور: اسلاگ → نام نمایشی.
	 *
	 * @param WC_Product $product
	 * @param array      $axis
	 * @param array      $variations
	 * @param string     $source product | all
	 * @return array
	 */
	private function axis_values( $product, $axis, $variations, $source ) {
		$out = array();

		if ( 'all' === $source ) {
			// همهٔ مقدارهایی که روی خود محصول برای این ویژگی تعریف شده‌اند.
			foreach ( (array) $axis['attribute']->get_options() as $option ) {
				if ( '' !== $axis['taxonomy'] ) {
					$term = get_term( (int) $option, $axis['taxonomy'] );

					if ( $term && ! is_wp_error( $term ) ) {
						$out[ $term->slug ] = $term->name;
					}

					continue;
				}

				$out[ sanitize_title( $option ) ] = $option;
			}
		}

		// مقدارهایی که واقعاً در واریاسیون‌ها آمده‌اند — همیشه باید باشند.
		foreach ( $variations as $variation ) {
			$slug = isset( $variation['attrs'][ $axis['key'] ] ) ? $variation['attrs'][ $axis['key'] ] : '';

			if ( '' === $slug || isset( $out[ $slug ] ) ) {
				continue;
			}

			$out[ $slug ] = $this->value_label( $slug, $axis['taxonomy'] );
		}

		return $out;
	}

	/**
	 * نام خوانای یک مقدار؛ برای ویژگی تاکسونومی‌دار از نام ترم.
	 *
	 * @param string $slug
	 * @param string $taxonomy
	 * @return string
	 */
	private function value_label( $slug, $taxonomy ) {
		if ( '' === $taxonomy ) {
			return $slug;
		}

		$term = get_term_by( 'slug', $slug, $taxonomy );

		return ( $term && ! is_wp_error( $term ) ) ? $term->name : $slug;
	}
}
