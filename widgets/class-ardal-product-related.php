<?php
/**
 * ویجت محصولات مرتبط صفحه محصول.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Ardal_Widget_Product_Related extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-product-related';
	}

	public function get_title() {
		return 'اردال — محصولات مرتبط';
	}

	public function get_icon() {
		return 'eicon-products';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'مرتبط', 'مشابه', 'محصول', 'related', 'upsell' );
	}

	protected function register_controls() {
		$this->add_heading_controls( 'محصولات مرتبط', 'accent' );

		$this->start_controls_section(
			'section_content',
			array( 'label' => 'محصولات' )
		);

		$this->add_control(
			'relation',
			array(
				'label'       => 'نوع ارتباط',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'related',
				'options'     => array(
					'related'   => 'مرتبط (هم‌دسته و هم‌برچسب)',
					'upsells'   => 'بیش‌فروش (Upsells)',
					'crosssell' => 'مکمل (Cross-sells)',
					'category'  => 'هم‌دسته با این محصول',
				),
				'description' => 'همه بر اساس محصولی که کاربر در حال دیدن آن است محاسبه می‌شوند.',
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'   => 'تعداد',
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 16,
				'default' => 5,
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => 'ترتیب',
				'type'    => Controls_Manager::SELECT,
				'default' => 'rand',
				'options' => array(
					'rand'  => 'تصادفی',
					'date'  => 'جدیدترین',
					'price' => 'ارزان‌ترین',
					'sales' => 'پرفروش‌ترین',
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

		$this->add_control(
			'variant',
			array(
				'label'   => 'سبک کارت',
				'type'    => Controls_Manager::SELECT,
				'default' => 'framed',
				'options' => array(
					'framed' => 'کارت حاشیه‌دار با دکمه گرد سبز',
					'plain'  => 'بدون حاشیه با دکمه «افزودن به سبد»',
					'reveal' => 'دکمه آشکارشونده با هاور',
				),
			)
		);

		$this->add_media_fit_control( '.ard-pcard__media img' );

		$this->add_control(
			'link_mode',
			array(
				'label'   => 'ناحیه قابل کلیک',
				'type'    => Controls_Manager::SELECT,
				'default' => 'box',
				'options' => array(
					'button' => 'فقط عنوان و دکمه',
					'image'  => 'عنوان، تصویر و دکمه',
					'box'    => 'کل کارت به محصول لینک شود',
				),
			)
		);

		$this->add_control(
			'button_action',
			array(
				'label'   => 'کلیک روی دکمه',
				'type'    => Controls_Manager::SELECT,
				'default' => 'add',
				'options' => array(
					'add'  => 'افزودن به سبد خرید',
					'link' => 'رفتن به صفحه محصول',
				),
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

		$this->add_tag_control( 'card_title_tag', 'تگ نام محصول', 'h3' );

		$this->add_control(
			'button_text',
			array(
				'label'     => 'متن دکمه',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'افزودن به سبد',
				'condition' => array( 'variant' => array( 'plain', 'reveal' ) ),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => 'تعداد ستون',
				'type'           => Controls_Manager::NUMBER,
				'min'            => 1,
				'max'            => 6,
				'default'        => 5,
				'tablet_default' => 3,
				'mobile_default' => 2,
				'selectors'      => array(
					'{{WRAPPER}} .ard-pgrid' => 'grid-template-columns:repeat({{VALUE}},minmax(0,1fr));',
				),
			)
		);

		$this->add_responsive_control(
			'image_height',
			array(
				'label'          => 'ارتفاع تصویر',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 80, 'max' => 320 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 168 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 120 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-pcard__media' => 'height:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->add_reveal_controls();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = $this->get_related_items( $s );

		if ( empty( $items ) ) {
			$this->render_empty( 'محصول مرتبطی پیدا نشد. این ویجت را روی قالب تک‌محصول بگذارید.' );

			return;
		}

		$framed = 'framed' === $s['variant'];

		/*
		 * سبک آشکارشونده دکمهٔ متنی دارد (که با هاور بالا می‌آید)، نه دکمهٔ
		 * گرد؛ و درصد تخفیفش کنار قیمت می‌نشیند نه گوشهٔ تصویر.
		 */
		$reveal = 'reveal' === $s['variant'];

		$this->render_heading();

		echo '<div class="ard-pgrid ard-pgrid--' . esc_attr( $s['variant'] ) . '">';

		// سطرِ رزروِ «قیمت قبل» فقط وقتی معنا دارد که کسی در فهرست تخفیف داشته باشد.
		$reserve_old  = false;
		$reserve_rate = false;

		foreach ( $items as $item ) {
			if ( isset( $item['old_price'] ) && '' !== trim( (string) $item['old_price'] ) ) {
				$reserve_old = true;
			}

			if ( isset( $item['rating'] ) && (float) $item['rating'] > 0 ) {
				$reserve_rate = true;
			}
		}

		foreach ( $items as $item ) {
			echo '<div class="ard-pgrid__cell">';

			$this->render_product_card(
				$item,
				array(
					'variant'       => $s['variant'],
					'link_mode'     => $s['link_mode'],
					'button_action' => $s['button_action'],
					'show_rating'   => 'yes' === $s['show_rating'],
					'show_fab'      => $framed,
					'show_button'   => ! $framed,
					'off_inline'    => $reveal,
					'off_stack'     => $reveal && 'yes' === $s['reveal_off_stack'],
					'icon_html'     => $reveal ? $this->card_button_icon( $s ) : '',
					'currency' => $reveal ? $s['reveal_currency'] : 'default',
					'button_text'   => $s['button_text'],
					'placeholder'   => '۸۰۰×۸۰۰',
					'title_tag'     => $s['card_title_tag'],
					'reserve_old'   => $reserve_old,
					'reserve_rate'  => $reserve_rate,
				)
			);

			echo '</div>';
		}

		echo '</div>';
	}

	/**
	 * محصولات مرتبط با محصول جاری.
	 *
	 * @param array $s
	 * @return array
	 */
	private function get_related_items( $s ) {
		$product = Ardal_Product::current();

		if ( ! $product ) {
			return array();
		}

		$limit = max( 1, (int) $s['limit'] );
		$ids   = array();

		switch ( $s['relation'] ) {
			case 'upsells':
				$ids = $product->get_upsell_ids();
				break;

			case 'crosssell':
				$ids = $product->get_cross_sell_ids();
				break;

			case 'category':
				$ids = $this->same_category_ids( $product, $limit );
				break;

			default:
				$ids = wc_get_related_products( $product->get_id(), $limit, array( $product->get_id() ) );
		}

		$ids = array_values( array_diff( array_map( 'intval', (array) $ids ), array( $product->get_id() ) ) );

		if ( empty( $ids ) ) {
			return array();
		}

		$ids = array_slice( $ids, 0, $limit );

		$query = array(
			'include' => $ids,
			'limit'   => $limit,
			'status'  => 'publish',
			'return'  => 'objects',
		);

		switch ( $s['orderby'] ) {
			case 'date':
				$query['orderby'] = 'date';
				$query['order']   = 'DESC';
				break;

			case 'price':
				$query['orderby'] = 'meta_value_num';
				$query['meta_key'] = '_price'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				$query['order']   = 'ASC';
				break;

			case 'sales':
				$query['orderby'] = 'meta_value_num';
				$query['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				$query['order']   = 'DESC';
				break;

			default:
				$query['orderby'] = 'rand';
		}

		$out = array();

		foreach ( wc_get_products( $query ) as $related ) {
			$out[] = Ardal_Helpers::product_to_card( $related );
		}

		return $out;
	}

	/**
	 * محصولات هم‌دسته.
	 *
	 * @param WC_Product $product
	 * @param int        $limit
	 * @return array
	 */
	private function same_category_ids( $product, $limit ) {
		$terms = wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'slugs' ) );

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return array();
		}

		return wc_get_products(
			array(
				'category' => $terms,
				'limit'    => $limit + 1,
				'status'   => 'publish',
				'exclude'  => array( $product->get_id() ),
				'return'   => 'ids',
			)
		);
	}
}
