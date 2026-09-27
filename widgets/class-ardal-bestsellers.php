<?php
/**
 * ویجت پرفروش‌ترین‌ها — کارت فشرده با رتبه.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Bestsellers extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-bestsellers';
	}

	public function get_title() {
		return 'اردال — پرفروش‌ترین‌ها';
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_keywords() {
		return array( 'اردال', 'پرفروش', 'رتبه', 'bestseller', 'top' );
	}

	protected function register_controls() {
		$this->add_heading_controls( 'پرفروش‌ترین‌های ماه', 'primary' );

		$this->start_controls_section(
			'section_content',
			array( 'label' => 'محصولات' )
		);

		$this->add_source_controls( 6 );

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			array(
				'label'       => 'نام محصول',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'شارژر سریع پاورلوپ',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'image',
			array(
				'label' => 'تصویر',
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$repeater->add_control(
			'price',
			array(
				'label'   => 'قیمت',
				'type'    => Controls_Manager::TEXT,
				'default' => '۲۹۰٫۰۰۰',
			)
		);

		$repeater->add_control(
			'rating',
			array(
				'label'   => 'امتیاز',
				'type'    => Controls_Manager::SLIDER,
				'range'   => array( 'px' => array( 'min' => 0, 'max' => 5, 'step' => 0.5 ) ),
				'default' => array( 'size' => 4 ),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'   => 'لینک',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => 'محصولات',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'condition'   => array( 'source' => 'manual' ),
				'default'     => array(
					array( 'title' => 'شارژر سریع پاورلوپ', 'price' => '۲۹۰٫۰۰۰' ),
					array( 'title' => 'ماگ سرامیکی', 'price' => '۲۱۰٫۰۰۰' ),
					array( 'title' => 'کفش راحتی گراند', 'price' => '۲٫۴۵۰٫۰۰۰' ),
					array( 'title' => 'دستکش چرم طبیعی', 'price' => '۴۸۰٫۰۰۰' ),
					array( 'title' => 'قمقمه ۱ لیتری', 'price' => '۱۸۵٫۰۰۰' ),
					array( 'title' => 'ساک دستی دوکاره', 'price' => '۹۵۰٫۰۰۰' ),
				),
			)
		);

		$this->add_control(
			'show_rank',
			array(
				'label'        => 'نمایش شماره رتبه',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_rating',
			array(
				'label'        => 'نمایش امتیاز',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_tag_control( 'item_tag', 'تگ نام محصول', 'h3' );

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
				'label'   => 'سبک',
				'type'    => Controls_Manager::SELECT,
				'default' => 'framed',
				'options' => array(
					'framed' => 'کارت حاشیه‌دار (دیزاین ۱a)',
					'soft'   => 'کارت با زمینه ملایم (دیزاین ۱b)',
				),
			)
		);

		$this->add_media_fit_control( '.ard-best__media img' );

		$this->add_control(
			'layout',
			array(
				'label'   => 'چیدمان',
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => array(
					'grid'   => 'شبکه‌ای',
					'scroll' => 'اسکرول افقی (مناسب موبایل)',
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => 'تعداد ستون',
				'type'           => Controls_Manager::NUMBER,
				'min'            => 2,
				'max'            => 8,
				'default'        => 6,
				'tablet_default' => 4,
				'mobile_default' => 2,
				'condition'      => array( 'layout' => 'grid' ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-best' => 'grid-template-columns:repeat({{VALUE}},minmax(0,1fr));',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = $this->get_product_items( $s );

		if ( empty( $items ) ) {
			$this->render_empty( 'محصولی برای نمایش وجود ندارد.' );

			return;
		}

		$this->render_heading();
		?>
		<div class="ard-best ard-best--<?php echo esc_attr( $s['variant'] ); ?> ard-best--<?php echo esc_attr( $s['layout'] ); ?>">
			<?php
			foreach ( $items as $i => $item ) :
				$title  = isset( $item['title'] ) ? $item['title'] : '';
				$price  = Ardal_Helpers::price( isset( $item['price'] ) ? $item['price'] : '' );
				$link   = isset( $item['link'] ) ? $item['link'] : array( 'url' => '' );
				$rating = isset( $item['rating'] ) ? $item['rating'] : 0;

				if ( is_array( $rating ) ) {
					$rating = isset( $rating['size'] ) ? (float) $rating['size'] : 0;
				}
				?>
				<article class="ard-best__card">
					<?php if ( 'soft' === $s['variant'] ) : ?>
						<div class="ard-best__meta">
							<?php if ( 'yes' === $s['show_rank'] ) : ?>
								<span class="ard-best__rank"><?php echo esc_html( '#' . Ardal_Helpers::fa_digits( $i + 1 ) ); ?></span>
							<?php endif; ?>
							<?php if ( 'yes' === $s['show_rating'] && $rating > 0 ) : ?>
								<?php echo Ardal_Helpers::stars( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<div class="ard-best__media">
						<?php echo Ardal_Helpers::image( isset( $item['image'] ) ? $item['image'] : array(), '۵۰۰×۵۰۰', $title, 'medium' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php if ( 'framed' === $s['variant'] && 'yes' === $s['show_rank'] ) : ?>
							<span class="ard-badge ard-badge--rank"><?php echo esc_html( '#' . Ardal_Helpers::fa_digits( $i + 1 ) ); ?></span>
						<?php endif; ?>
					</div>

					<?php $ard_item_tag = self::tag( $s['item_tag'], 'h3' ); ?>
					<<?php echo esc_html( $ard_item_tag ); ?> class="ard-best__title">
						<?php if ( ! empty( $link['url'] ) ) : ?>
							<a<?php echo Ardal_Helpers::link_attrs( $link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $title ); ?></a>
						<?php else : ?>
							<?php echo esc_html( $title ); ?>
						<?php endif; ?>
					</<?php echo esc_html( $ard_item_tag ); ?>>

					<?php if ( 'framed' === $s['variant'] && 'yes' === $s['show_rating'] && $rating > 0 ) : ?>
						<div class="ard-best__stars"><?php echo Ardal_Helpers::stars( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php endif; ?>

					<?php if ( '' !== $price ) : ?>
						<div class="ard-best__price"><?php echo esc_html( $price ); ?> <?php echo Ardal_Currency::unit(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
