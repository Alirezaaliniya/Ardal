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

		$this->add_control(
			'woo_hide_empty',
			array(
				'label'        => 'دسته‌های بدون محصول نمایش داده نشوند',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'source' => 'woo' ),
			)
		);

		$this->add_control(
			'woo_exclude',
			array(
				'label'       => 'دسته‌هایی که نمایش داده نشوند',
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->cat_options(),
				'default'     => array(),
				'label_block' => true,
				'description' => 'دستهٔ پیش‌فرض ووکامرس («دسته‌بندی نشده») همیشه کنار گذاشته می‌شود و لازم نیست اینجا انتخابش کنید.',
				'condition'   => array( 'source' => 'woo' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			array(
				'label'       => 'نام دسته',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'کفش',
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
					array( 'title' => 'کفش', 'count' => '۱٫۲۴۰' ),
					array( 'title' => 'پوشاک', 'count' => '۹۸۶' ),
					array( 'title' => 'دیجیتال', 'count' => '۷۴۲' ),
					array( 'title' => 'خانه و آشپزخانه', 'count' => '۴۱۸' ),
					array( 'title' => 'زیبایی و سلامت', 'count' => '۳۶۵' ),
					array( 'title' => 'اسباب‌بازی', 'count' => '۵۲۰' ),
					array( 'title' => 'کتاب و لوازم‌التحریر', 'count' => '۴۷۳' ),
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

		$this->add_tag_control(
			'item_tag',
			'تگ نام دسته',
			'span',
			array( 'separator' => 'before' )
		);

		$this->end_controls_section();

		$this->add_slider_controls();

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
				// فلش‌های اسلایدر روی همین قطر وسط‌چین می‌شوند، پس مقدار را
				// روی رَپِر هم می‌گذاریم تا با تغییر اندازه جابه‌جا نمانند.
				'selectors'      => array(
					'{{WRAPPER}} .ard-cat__ring'    => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ard-cats-slider' => '--ard-cat-ring:{{SIZE}}{{UNIT}};',
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
				'condition'      => array( 'layout' => 'grid' ),
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
				'condition'    => array( 'layout' => 'grid' ),
			)
		);

		$this->end_controls_section();

		$this->add_slider_style_controls();
	}

	/* --------------------------------------------------------------------- */
	/* اسلایدر                                                                 */
	/* --------------------------------------------------------------------- */

	private function add_slider_controls() {
		$this->start_controls_section(
			'section_slider',
			array( 'label' => 'چیدمان و اسلایدر' )
		);

		$this->add_control(
			'layout',
			array(
				'label'       => 'نوع چیدمان',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'grid',
				'options'     => array(
					'grid'   => 'شبکه (چند ردیف)',
					'slider' => 'اسلایدر (یک ردیف، با کشیدن)',
				),
				'label_block' => true,
			)
		);

		$this->add_responsive_control(
			'per_view',
			array(
				'label'          => 'تعداد در هر نما',
				'type'           => Controls_Manager::NUMBER,
				'min'            => 1,
				'max'            => 12,
				'default'        => 7,
				'tablet_default' => 5,
				'mobile_default' => 3,
				'selectors'      => array(
					'{{WRAPPER}} .ard-cats--slider' => '--ard-per:{{VALUE}};',
				),
				'condition'      => array( 'layout' => 'slider' ),
			)
		);

		$this->add_responsive_control(
			'slider_gap',
			array(
				'label'      => 'فاصله بین دسته‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 48 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-cats--slider' => '--ard-gap:{{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'layout' => 'slider' ),
			)
		);

		$this->add_control(
			'slider_arrows',
			array(
				'label'        => 'فلش‌های کناری',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'در موبایل نمایش داده نمی‌شوند؛ آنجا کشیدن با انگشت کافی است.',
				'condition'    => array( 'layout' => 'slider' ),
			)
		);

		$this->add_control(
			'slider_dots',
			array(
				'label'        => 'نقطه‌های پایین',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'layout' => 'slider' ),
			)
		);

		$this->add_control(
			'slider_autoplay',
			array(
				'label'        => 'چرخش خودکار',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'condition'    => array( 'layout' => 'slider' ),
			)
		);

		$this->add_control(
			'slider_interval',
			array(
				'label'     => 'فاصله چرخش (ثانیه)',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 2,
				'max'       => 30,
				'default'   => 5,
				'condition' => array(
					'layout'          => 'slider',
					'slider_autoplay' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	private function add_slider_style_controls() {
		$this->start_controls_section(
			'section_style_slider',
			array(
				'label'     => 'فلش‌ها و نقطه‌ها',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'layout' => 'slider' ),
			)
		);

		$this->add_control(
			'arrow_color',
			array(
				'label'     => 'رنگ آیکون فلش',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-cats__arrow' => 'color:{{VALUE}};',
				),
				'condition' => array( 'slider_arrows' => 'yes' ),
			)
		);

		$this->add_control(
			'arrow_bg',
			array(
				'label'     => 'زمینه فلش',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-cats__arrow' => 'background:{{VALUE}};',
				),
				'condition' => array( 'slider_arrows' => 'yes' ),
			)
		);

		$this->add_control(
			'arrow_border',
			array(
				'label'     => 'حاشیه فلش',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-cats__arrow' => 'border-color:{{VALUE}};',
				),
				'condition' => array( 'slider_arrows' => 'yes' ),
			)
		);

		$this->add_control(
			'arrow_size',
			array(
				'label'      => 'اندازه فلش',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 24, 'max' => 60 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-cats__arrow' => 'inline-size:{{SIZE}}{{UNIT}};block-size:{{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'slider_arrows' => 'yes' ),
			)
		);

		$this->add_control(
			'dot_color',
			array(
				'label'     => 'رنگ نقطه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-cats__dot' => 'background:{{VALUE}};',
				),
				'condition' => array( 'slider_dots' => 'yes' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'dot_active',
			array(
				'label'     => 'رنگ نقطه فعال',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-cats__dot.is-active' => 'background:{{VALUE}};',
				),
				'condition' => array( 'slider_dots' => 'yes' ),
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

		$slider  = 'slider' === $s['layout'];
		$classes = array( 'ard-cats', 'ard-cats--' . $s['variant'] );

		if ( $slider ) {
			$classes[] = 'ard-cats--slider';
		} elseif ( 'yes' === $s['scroll_mobile'] ) {
			$classes[] = 'ard-cats--scroll';
		}

		$item_tag = self::tag( $s['item_tag'], 'span' );
		$interval = $slider && 'yes' === $s['slider_autoplay'] ? max( 2, (int) $s['slider_interval'] ) * 1000 : 0;
		$arrows   = $slider && 'yes' === $s['slider_arrows'] && count( $items ) > 1;

		$this->render_heading();

		if ( $slider ) :
			?>
			<div class="ard-cats-slider">
				<?php if ( $arrows ) : ?>
					<button type="button" class="ard-cats__arrow ard-cats__arrow--prev" data-ard-prev aria-label="قبلی">
						<?php echo Ardal_Helpers::icon( 'chevron-right', 17 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
					<button type="button" class="ard-cats__arrow ard-cats__arrow--next" data-ard-next aria-label="بعدی">
						<?php echo Ardal_Helpers::icon( 'chevron-left', 17 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
				<?php endif; ?>

				<div class="ard-cats-slider__car" data-ard-carousel data-interval="<?php echo esc_attr( $interval ); ?>">
			<?php
		endif;
		?>
		<ul class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"<?php echo $slider ? ' data-ard-viewport' : ''; ?>>
			<?php foreach ( $items as $i => $item ) : ?>
				<li class="ard-cat" style="--ard-cat-i:<?php echo (int) ( $i % 3 ); ?>">
					<?php $tag = ! empty( $item['link']['url'] ) ? 'a' : 'div'; ?>
					<<?php echo esc_html( $tag ); ?> class="ard-cat__inner"<?php echo 'a' === $tag ? Ardal_Helpers::link_attrs( $item['link'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<span class="ard-cat__ring">
							<span class="ard-cat__disc">
								<?php echo Ardal_Helpers::image( $item['image'], $item['title'], $item['title'], 'thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
						</span>
						<<?php echo esc_html( $item_tag ); ?> class="ard-cat__title"><?php echo esc_html( $item['title'] ); ?></<?php echo esc_html( $item_tag ); ?>>
						<?php if ( 'yes' === $s['show_count'] && '' !== trim( (string) $item['count'] ) ) : ?>
							<span class="ard-cat__count"><?php echo esc_html( Ardal_Helpers::fa_digits( $item['count'] ) . ' کالا' ); ?></span>
						<?php endif; ?>
					</<?php echo esc_html( $tag ); ?>>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
		if ( $slider ) :
			?>
					<?php if ( 'yes' === $s['slider_dots'] && count( $items ) > 1 ) : ?>
						<div class="ard-cats__dots" data-ard-dots="ard-cats__dot" role="tablist" aria-label="انتخاب صفحه"></div>
					<?php endif; ?>
				</div>
			</div>
			<?php
		endif;
	}

	/**
	 * شناسهٔ دسته‌هایی که نباید بیایند.
	 *
	 * دستهٔ پیش‌فرض ووکامرس همیشه کنار می‌رود؛ تعریفش در
	 * Ardal_Archive::excluded_terms() است تا همه‌جای افزونه یکی باشد.
	 *
	 * @param array $s
	 * @return array
	 */
	private function excluded_cats( $s ) {
		// فهرست پایه یک جا نگه داشته شده تا ریل، فیلتر و اینجا یک‌جور رفتار کنند.
		$out = Ardal_Archive::excluded_terms( 'product_cat' );

		foreach ( (array) ( $s['woo_exclude'] ?? array() ) as $id ) {
			$out[] = (int) $id;
		}

		return array_values( array_filter( array_unique( $out ) ) );
	}

	/**
	 * فهرست دسته‌ها برای سلکت تنظیمات.
	 *
	 * @return array
	 */
	private function cat_options() {
		if ( ! Ardal_Helpers::has_woo() ) {
			return array();
		}

		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'number'     => 200,
			)
		);

		if ( is_wp_error( $terms ) ) {
			return array();
		}

		$out = array();

		foreach ( $terms as $term ) {
			$out[ $term->term_id ] = $term->name;
		}

		return $out;
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
			'hide_empty' => 'yes' === $s['woo_hide_empty'],
			'number'     => max( 1, (int) $s['woo_limit'] ),
			'orderby'    => 'count',
			'order'      => 'DESC',
			'exclude'    => $this->excluded_cats( $s ),
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
