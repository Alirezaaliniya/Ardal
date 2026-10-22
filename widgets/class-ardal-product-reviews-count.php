<?php
/**
 * ویجت تعداد نظرات محصول، با لینک به بخش نظرات.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ARDAL_PATH . 'includes/trait-ardal-product-stat.php';

use Elementor\Controls_Manager;

class Ardal_Widget_Product_Reviews_Count extends Ardal_Widget_Base {

	use Ardal_Product_Stat;

	public function get_name() {
		return 'ardal-product-reviews-count';
	}

	public function get_title() {
		return 'اردال — تعداد نظرات محصول';
	}

	public function get_icon() {
		return 'eicon-comments';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'نظر', 'نظرات', 'دیدگاه', 'comments', 'reviews', 'count' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'محتوا' )
		);

		$this->add_control(
			'source',
			array(
				'label'   => 'منبع',
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => array(
					'auto'   => 'خودکار از محصول جاری',
					'manual' => 'دستی',
				),
			)
		);

		$this->add_control(
			'count',
			array(
				'label'     => 'تعداد',
				'type'      => Controls_Manager::NUMBER,
				'default'   => 2,
				'min'       => 0,
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'count_what',
			array(
				'label'       => 'شمارش',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'reviews',
				'options'     => array(
					'reviews' => 'نظرهای تأییدشده (مثل ووکامرس)',
					'all'     => 'همهٔ دیدگاه‌های تأییدشده با پاسخ‌ها',
				),
				'description' => 'حالت اول همان عددی است که ووکامرس روی تب نظرات نشان می‌دهد.',
				'condition'   => array( 'source' => 'auto' ),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'       => 'متن',
				'type'        => Controls_Manager::TEXT,
				'default'     => '{n} نظرات',
				'description' => '{n} جای عدد می‌نشیند؛ مثلاً «{n} دیدگاه».',
			)
		);

		$this->add_control(
			'empty',
			array(
				'label'     => 'بدون نظر',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'text',
				'separator' => 'before',
				'options'   => array(
					'text' => 'نمایش متن جایگزین',
					'zero' => 'نمایش صفر',
					'hide' => 'پنهان شود',
				),
			)
		);

		$this->add_control(
			'empty_text',
			array(
				'label'       => 'متن جایگزین',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'ثبت اولین نظر',
				'description' => 'وقتی کلیک به بخش نظرات می‌برد، دعوت به نوشتن نظر بهتر از «۰ نظر» است.',
				'condition'   => array( 'empty' => 'text' ),
			)
		);

		$this->add_stat_common_controls();

		$this->end_controls_section();

		$this->add_stat_style_controls();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( 'manual' === $s['source'] ) {
			$count = max( 0, (int) $s['count'] );
		} else {
			$product = Ardal_Product::current();

			if ( ! $product ) {
				$this->render_empty( 'محصولی پیدا نشد. این ویجت را روی قالب تک‌محصول بگذارید یا حالت دستی را انتخاب کنید.' );

				return;
			}

			$count = 'all' === $s['count_what']
				? (int) get_comments_number( $product->get_id() )
				: (int) $product->get_review_count();
		}

		$text  = (string) $s['text'];
		$class = '';

		if ( 0 === $count ) {
			if ( 'hide' === $s['empty'] ) {
				$this->render_empty( 'این محصول هنوز نظری ندارد و ویجت پنهان است.' );

				return;
			}

			$class = 'is-empty';

			if ( 'text' === $s['empty'] ) {
				$text = (string) $s['empty_text'];
			}
		}

		$this->render_stat(
			$s,
			'reviews',
			'chat',
			$text,
			Ardal_Helpers::fa_digits( $count ),
			array( 'class' => $class )
		);
	}
}
