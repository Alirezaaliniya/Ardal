<?php
/**
 * ویجت نوار اطلاعات محصول: امتیاز، پرسش و پاسخ، کد کالا و وضعیت موجودی.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Ardal_Widget_Product_Meta extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-product-meta';
	}

	public function get_title() {
		return 'اردال — نوار اطلاعات محصول';
	}

	public function get_icon() {
		return 'eicon-info-circle-o';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'امتیاز', 'کد کالا', 'موجودی', 'rating', 'sku', 'stock' );
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
				'description' => 'حالت خودکار امتیاز، تعداد نظر، کد کالا و موجودی را از خود محصول می‌خواند.',
			)
		);

		$this->add_control(
			'show_rating',
			array(
				'label'        => 'نمایش امتیاز',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_sku',
			array(
				'label'        => 'نمایش کد کالا',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_stock',
			array(
				'label'        => 'نمایش وضعیت موجودی',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'qna_text',
			array(
				'label'       => 'متن پرسش و پاسخ',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => 'مثلاً «۲۱ پرسش و پاسخ». خالی بگذارید تا نمایش داده نشود.',
			)
		);

		$this->add_control(
			'qna_link',
			array(
				'label'       => 'لینک پرسش و پاسخ',
				'type'        => Controls_Manager::URL,
				'placeholder' => '#qna',
				'condition'   => array( 'qna_text!' => '' ),
			)
		);

		$this->add_control(
			'sku_label',
			array(
				'label'     => 'برچسب کد کالا',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'کد کالا:',
				'condition' => array( 'show_sku' => 'yes' ),
			)
		);

		$this->add_control(
			'rating',
			array(
				'label'     => 'امتیاز',
				'type'      => Controls_Manager::TEXT,
				'default'   => '4.6',
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'reviews_count',
			array(
				'label'     => 'تعداد نظر',
				'type'      => Controls_Manager::TEXT,
				'default'   => '48',
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'sku',
			array(
				'label'     => 'کد کالا',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'SPL-94207',
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'stock_status',
			array(
				'label'     => 'وضعیت موجودی',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'موجود در انبار',
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

		$this->add_responsive_control(
			'font_size',
			array(
				'label'          => 'اندازه متن',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 9, 'max' => 18 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 13 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 12 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-meta' => 'font-size:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'          => 'فاصله بین آیتم‌ها',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 4, 'max' => 40 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 18 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 10 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-meta' => 'gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => 'رنگ متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-prod-meta' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'stars_color',
			array(
				'label'     => 'رنگ ستاره‌ها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-prod-meta__stars .ard-stars' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => 'رنگ پرسش و پاسخ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-prod-meta__item--link' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'divider',
			array(
				'label'        => 'خط جداکننده پایین',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'prefix_class' => 'ard-meta-divider-',
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

		$show_rating = 'yes' === $s['show_rating'] && '' !== $data['rating'];
		$show_qna    = '' !== trim( (string) $s['qna_text'] );
		$show_sku    = 'yes' === $s['show_sku'] && '' !== $data['sku'];
		$show_stock  = 'yes' === $s['show_stock'] && '' !== $data['stock_status'];

		if ( ! $show_rating && ! $show_qna && ! $show_sku && ! $show_stock ) {
			$this->render_empty( 'این محصول امتیاز، کد کالا یا وضعیت موجودی برای نمایش ندارد.' );

			return;
		}

		$qna_url = isset( $s['qna_link']['url'] ) ? $s['qna_link']['url'] : '';
		?>
		<div class="ard-prod-meta">
			<?php if ( $show_rating ) : ?>
				<div class="ard-prod-meta__rate">
					<span class="ard-prod-meta__stars"><?php echo Ardal_Helpers::stars( $data['rating_value'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="ard-prod-meta__score"><?php echo esc_html( $data['rating'] ); ?></span>
					<?php if ( '' !== $data['reviews_count'] ) : ?>
						<span class="ard-prod-meta__count">(<?php echo esc_html( $data['reviews_count'] ); ?> نظر)</span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $show_qna ) : ?>
				<?php if ( '' !== $qna_url ) : ?>
					<a class="ard-prod-meta__item ard-prod-meta__item--link"<?php echo Ardal_Helpers::link_attrs( $s['qna_link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( Ardal_Helpers::fa_digits( $s['qna_text'] ) ); ?></a>
				<?php else : ?>
					<div class="ard-prod-meta__item ard-prod-meta__item--link"><?php echo esc_html( Ardal_Helpers::fa_digits( $s['qna_text'] ) ); ?></div>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( $show_sku ) : ?>
				<div class="ard-prod-meta__item"><?php echo esc_html( trim( $s['sku_label'] . ' ' . Ardal_Helpers::fa_digits( $data['sku'] ) ) ); ?></div>
			<?php endif; ?>

			<?php if ( $show_stock ) : ?>
				<div class="ard-prod-meta__item ard-prod-meta__item--stock<?php echo $data['in_stock'] ? '' : ' is-out'; ?>">
					<span class="ard-prod-meta__stock-dot"></span>
					<span><?php echo esc_html( $data['stock_status'] ); ?></span>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * داده‌ها از محصول جاری.
	 *
	 * @return array|null
	 */
	private function auto_data() {
		$product = Ardal_Product::current();

		if ( ! $product ) {
			return null;
		}

		$rating = (float) $product->get_average_rating();
		// برچسب «نظر» همهٔ دیدگاه‌ها را می‌شمارد، نه فقط دیدگاه‌های امتیازدار.
		$count = (int) $product->get_review_count();

		return array(
			'rating'        => $rating > 0 ? Ardal_Helpers::fa_digits( number_format( $rating, 1 ) ) : '',
			'rating_value'  => $rating,
			'reviews_count' => $count > 0 ? Ardal_Helpers::fa_digits( $count ) : '',
			'sku'           => (string) $product->get_sku(),
			'stock_status'  => $product->is_in_stock() ? 'موجود در انبار' : 'ناموجود',
			'in_stock'      => $product->is_in_stock(),
		);
	}

	/**
	 * داده‌ها از مقادیر دستی پنل.
	 *
	 * @param array $s
	 * @return array
	 */
	private function manual_data( $s ) {
		return array(
			'rating'        => Ardal_Helpers::fa_digits( $s['rating'] ),
			'rating_value'  => (float) Ardal_Helpers::en_digits( $s['rating'] ),
			'reviews_count' => Ardal_Helpers::fa_digits( $s['reviews_count'] ),
			'sku'           => (string) $s['sku'],
			'stock_status'  => (string) $s['stock_status'],
			'in_stock'      => true,
		);
	}
}
