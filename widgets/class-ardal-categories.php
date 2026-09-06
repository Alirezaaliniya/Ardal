<?php
/**
 * ویجت دسته‌بندی‌های دایره‌ای.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Categories extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-categories';
	}

	public function get_title() {
		return 'اردال — دسته‌بندی‌ها';
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_keywords() {
		return array( 'اردال', 'دسته‌بندی', 'دسته', 'categories' );
	}

	protected function register_controls() {
		$this->add_heading_controls( 'خرید بر اساس دسته‌بندی', 'accent' );

		$this->start_controls_section(
			'section_content',
			array( 'label' => 'دسته‌ها' )
		);

		$this->add_control(
			'source',
			array(
				'label'   => 'منبع',
				'type'    => Controls_Manager::SELECT,
				'default' => 'manual',
				'options' => Ardal_Helpers::has_woo()
					? array(
						'manual' => 'دستی',
						'woo'    => 'دسته‌های محصول ووکامرس',
					)
					: array( 'manual' => 'دستی (ووکامرس فعال نیست)' ),
			)
		);

		$this->add_control(
			'woo_limit',
			array(
				'label'     => 'تعداد',
				'type'      => Controls_Manager::NUMBER,
				'default'   => 8,
				'min'       => 1,
				'max'       => 24,
				'condition' => array( 'source' => 'woo' ),
			)
		);

		$this->add_control(
			'woo_parent_only',
			array(
				'label'        => 'فقط دسته‌های اصلی',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'source' => 'woo' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			array(
				'label'       => 'نام دسته',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'کفش ورزشی',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'count',
			array(
				'label'       => 'تعداد کالا',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'مثلاً ۱٫۲۴۰',
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
				'label'       => 'دسته‌ها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'condition'   => array( 'source' => 'manual' ),
				'default'     => array(
					array( 'title' => 'کفش ورزشی', 'count' => '۱٫۲۴۰' ),
					array( 'title' => 'پوشاک', 'count' => '۹۸۶' ),
					array( 'title' => 'بدنسازی', 'count' => '۷۴۲' ),
					array( 'title' => 'شنا و آب', 'count' => '۴۱۸' ),
					array( 'title' => 'دوچرخه', 'count' => '۳۶۵' ),
					array( 'title' => 'ورزش تیمی', 'count' => '۵۲۰' ),
					array( 'title' => 'کوهنوردی', 'count' => '۴۷۳' ),
					array( 'title' => 'لوازم جانبی', 'count' => '۱٫۱۰۵' ),
				),
			)
		);

		$this->add_control(
			'show_count',
			array(
				'label'        => 'نمایش تعداد کالا',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
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
				'label'   => 'سبک حلقه',
				'type'    => Controls_Manager::SELECT,
				'default' => 'tint',
				'options' => array(
					'tint'     => 'زمینه ملایم با حاشیه (دیزاین ۱a)',
					'gradient' => 'حلقه گرادیانی رنگی (دیزاین ۱b)',
				),
			)
		);

		$this->add_responsive_control(
			'size',
			array(
				'label'          => 'قطر دایره',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 56, 'max' => 180 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 126 ),
				'tablet_default' => array( 'unit' => 'px', 'size' => 100 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 74 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-cat__ring' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => 'تعداد ستون',
				'type'           => Controls_Manager::NUMBER,
				'min'            => 2,
				'max'            => 10,
				'default'        => 8,
				'tablet_default' => 5,
				'mobile_default' => 4,
				'selectors'      => array(
					'{{WRAPPER}} .ard-cats' => 'grid-template-columns:repeat({{VALUE}},minmax(0,1fr));',
				),
			)
		);

		$this->add_responsive_control(
			'scroll_mobile',
			array(
				'label'        => 'اسکرول افقی در موبایل',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'description'  => 'مطابق نسخه موبایل دیزاین، دسته‌ها به‌جای شبکه در یک ردیف قابل اسکرول قرار می‌گیرند.',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = $this->get_entries( $s );

		if ( empty( $items ) ) {
			$this->render_empty( 'هیچ دسته‌ای برای نمایش نیست.' );

			return;
		}

		$classes = array( 'ard-cats', 'ard-cats--' . $s['variant'] );

		if ( 'yes' === $s['scroll_mobile'] ) {
			$classes[] = 'ard-cats--scroll';
		}

		$this->render_heading();
		?>
		<ul class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<?php foreach ( $items as $i => $item ) : ?>
				<li class="ard-cat" style="--ard-cat-i:<?php echo (int) ( $i % 3 ); ?>">
					<?php $tag = ! empty( $item['link']['url'] ) ? 'a' : 'div'; ?>
					<<?php echo esc_html( $tag ); ?> class="ard-cat__inner"<?php echo 'a' === $tag ? Ardal_Helpers::link_attrs( $item['link'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<span class="ard-cat__ring">
							<span class="ard-cat__disc">
								<?php echo Ardal_Helpers::image( $item['image'], $item['title'], $item['title'], 'thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
						</span>
						<span class="ard-cat__title"><?php echo esc_html( $item['title'] ); ?></span>
						<?php if ( 'yes' === $s['show_count'] && '' !== trim( (string) $item['count'] ) ) : ?>
							<span class="ard-cat__count"><?php echo esc_html( Ardal_Helpers::fa_digits( $item['count'] ) . ' کالا' ); ?></span>
						<?php endif; ?>
					</<?php echo esc_html( $tag ); ?>>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}

	/**
	 * @param array $s
	 * @return array
	 */
	private function get_entries( $s ) {
		if ( 'woo' !== $s['source'] || ! Ardal_Helpers::has_woo() ) {
			return is_array( $s['items'] ) ? $s['items'] : array();
		}

		$args = array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'number'     => max( 1, (int) $s['woo_limit'] ),
			'orderby'    => 'count',
			'order'      => 'DESC',
		);

		if ( 'yes' === $s['woo_parent_only'] ) {
			$args['parent'] = 0;
		}

		$terms = get_terms( $args );

		if ( is_wp_error( $terms ) ) {
			return array();
		}

		$out = array();

		foreach ( $terms as $term ) {
			$thumb_id = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
			$src      = $thumb_id ? wp_get_attachment_image_src( $thumb_id, 'thumbnail' ) : false;

			$out[] = array(
				'title' => $term->name,
				'count' => (string) $term->count,
				'image' => array( 'url' => $src ? $src[0] : '' ),
				'link'  => array( 'url' => get_term_link( $term ) ),
			);
		}

		return $out;
	}
}
