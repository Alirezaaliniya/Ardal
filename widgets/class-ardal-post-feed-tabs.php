<?php
/**
 * Dynamic post tabs for single post templates.
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
use Elementor\Icons_Manager;
use Elementor\Repeater;

class Ardal_Widget_Post_Feed_Tabs extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-post-feed-tabs';
	}

	public function get_title() {
		return 'اردال — تب‌های نوشته‌ها';
	}

	public function get_icon() {
		return 'eicon-tabs';
	}

	public function get_keywords() {
		return array( 'اردال', 'تب نوشته', 'ترند', 'تازه', 'پرنظر', 'post tabs', 'trending posts' );
	}

	public function get_script_depends() {
		return array( 'ardal', 'ardal-post-feed-tabs' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_content', array( 'label' => 'تب‌ها و نوشته‌ها' ) );

		$repeater = new Repeater();
		$repeater->add_control( 'title', array( 'label' => 'عنوان تب', 'type' => Controls_Manager::TEXT, 'default' => 'ترند روز', 'label_block' => true ) );
		$repeater->add_control(
			'icon',
			array(
				'label'   => 'آیکون آماده',
				'type'    => Controls_Manager::SELECT,
				'default' => 'star',
				'options' => Ardal_Helpers::icon_options(),
			)
		);
		$repeater->add_control( 'custom_icon', array( 'label' => 'یا آیکون دلخواه', 'type' => Controls_Manager::ICONS, 'default' => array(), 'recommended' => array( 'fa-solid' ) ) );
		$repeater->add_control(
			'source',
			array(
				'label'   => 'معیار نوشته‌ها',
				'type'    => Controls_Manager::SELECT,
				'default' => 'popular',
				'options' => array(
					'popular'  => 'ترند / پربازخورد',
					'latest'   => 'تازه‌ترین نوشته‌ها',
					'comments' => 'بیشترین دیدگاه',
				),
			)
		);
		$repeater->add_control(
			'period',
			array(
				'label'   => 'محدوده زمانی',
				'type'    => Controls_Manager::SELECT,
				'default' => 'day',
				'options' => array(
					'all'   => 'همه زمان‌ها',
					'day'   => '۲۴ ساعت اخیر',
					'week'  => '۷ روز اخیر',
					'month' => '۳۰ روز اخیر',
				),
				'condition' => array( 'source!' => 'latest' ),
			)
		);
		$repeater->add_control(
			'categories',
			array(
				'label'       => 'محدود به دسته‌ها',
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => $this->category_options(),
				'default'     => array(),
			)
		);

		$this->add_control(
			'tabs',
			array(
				'label'       => 'تب‌ها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array( 'title' => 'ترند روز', 'icon' => 'star', 'source' => 'popular', 'period' => 'day' ),
					array( 'title' => 'تازه‌ها', 'icon' => 'clock', 'source' => 'latest', 'period' => 'all' ),
					array( 'title' => 'نظرها', 'icon' => 'mail', 'source' => 'comments', 'period' => 'all' ),
				),
			)
		);
		$this->add_control(
			'limit',
			array(
				'label'       => 'حداکثر نوشته در هر تب',
				'type'        => Controls_Manager::NUMBER,
				'default'     => 4,
				'min'         => 1,
				'max'         => 30,
				'description' => 'تعداد پست‌های هر تب را تنظیم کنید.',
			)
		);
		$this->add_control( 'exclude_current', array( 'label' => 'حذف نوشته جاری از فهرست', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ) );
		$this->add_control(
			'popular_by',
			array(
				'label'       => 'محاسبه ترند با',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'comment_count',
				'options'     => array( 'comment_count' => 'تعداد دیدگاه‌ها', 'views' => 'متای بازدید' ),
				'description' => 'وردپرس بازدید را به‌طور پیش‌فرض ثبت نمی‌کند. برای ترند بر اساس بازدید، کلید متای افزونه آمارگیر را وارد کنید.',
			)
		);
		$this->add_control(
			'views_meta_key',
			array(
				'label'       => 'کلید متای تعداد بازدید',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'post_views_count',
				'label_block' => true,
				'condition'   => array( 'popular_by' => 'views' ),
			)
		);
		$this->add_control( 'show_image', array( 'label' => 'نمایش تصویر شاخص', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ) );
		$this->add_control( 'show_time', array( 'label' => 'نمایش زمان انتشار', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ) );
		$this->add_control( 'empty_text', array( 'label' => 'متن فهرست خالی', 'type' => Controls_Manager::TEXT, 'default' => 'نوشته‌ای برای نمایش وجود ندارد.', 'label_block' => true ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_style_box', array( 'label' => 'کادر و فاصله‌ها', 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'box_bg', array( 'label' => 'پس‌زمینه', 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => array( '{{WRAPPER}} .ard-post-tabs' => 'background-color:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Border::get_type(), array( 'name' => 'box_border', 'selector' => '{{WRAPPER}} .ard-post-tabs' ) );
		$this->add_responsive_control( 'box_radius', array( 'label' => 'گردی کادر', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', '%' ), 'default' => array( 'top' => 30, 'right' => 30, 'bottom' => 30, 'left' => 30, 'unit' => 'px', 'isLinked' => true ), 'selectors' => array( '{{WRAPPER}} .ard-post-tabs' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), array( 'name' => 'box_shadow', 'selector' => '{{WRAPPER}} .ard-post-tabs' ) );
		$this->add_responsive_control( 'box_padding', array( 'label' => 'فاصله داخلی کادر', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'em' ), 'default' => array( 'top' => 20, 'right' => 20, 'bottom' => 20, 'left' => 20, 'unit' => 'px', 'isLinked' => false ), 'selectors' => array( '{{WRAPPER}} .ard-post-tabs' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_style_tabs', array( 'label' => 'نوار تب‌ها', 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_responsive_control( 'tabs_gap', array( 'label' => 'فاصله تب‌ها', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 0, 'max' => 40 ) ), 'default' => array( 'unit' => 'px', 'size' => 10 ), 'selectors' => array( '{{WRAPPER}} .ard-post-tabs__nav' => 'column-gap:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_control( 'tab_color', array( 'label' => 'رنگ تب عادی', 'type' => Controls_Manager::COLOR, 'default' => '#35353b', 'selectors' => array( '{{WRAPPER}} .ard-post-tabs__button' => 'color:{{VALUE}};' ) ) );
		$this->add_control( 'tab_active_color', array( 'label' => 'رنگ تب فعال', 'type' => Controls_Manager::COLOR, 'default' => '#f20d5b', 'selectors' => array( '{{WRAPPER}} .ard-post-tabs__button.is-active' => 'color:{{VALUE}};' ) ) );
		$this->add_control( 'tab_active_bg', array( 'label' => 'پس‌زمینه تب فعال', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-post-tabs__button.is-active' => 'background-color:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'tab_typography', 'selector' => '{{WRAPPER}} .ard-post-tabs__button' ) );
		$this->add_control( 'tab_icon_color', array( 'label' => 'رنگ آیکون عادی', 'type' => Controls_Manager::COLOR, 'default' => '#3b3b41', 'selectors' => array( '{{WRAPPER}} .ard-post-tabs__icon' => 'color:{{VALUE}};' ) ) );
		$this->add_control( 'tab_icon_active_color', array( 'label' => 'رنگ آیکون فعال', 'type' => Controls_Manager::COLOR, 'default' => '#f20d5b', 'selectors' => array( '{{WRAPPER}} .ard-post-tabs__button.is-active .ard-post-tabs__icon' => 'color:{{VALUE}};' ) ) );
		$this->add_responsive_control( 'tab_icon_size', array( 'label' => 'اندازه آیکون تب', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 12, 'max' => 36 ) ), 'default' => array( 'unit' => 'px', 'size' => 24 ), 'selectors' => array( '{{WRAPPER}} .ard-post-tabs__icon > svg' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};', '{{WRAPPER}} .ard-post-tabs__icon > i' => 'font-size:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_control( 'tab_line_color', array( 'label' => 'رنگ خط تب فعال', 'type' => Controls_Manager::COLOR, 'default' => '#55555b', 'selectors' => array( '{{WRAPPER}} .ard-post-tabs__button.is-active::after' => 'background-color:{{VALUE}};' ) ) );
		$this->add_responsive_control( 'tab_line_height', array( 'label' => 'ضخامت خط فعال', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 1, 'max' => 8 ) ), 'default' => array( 'unit' => 'px', 'size' => 2 ), 'selectors' => array( '{{WRAPPER}} .ard-post-tabs__button.is-active::after' => 'height:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_control( 'tabs_divider_color', array( 'label' => 'رنگ خط جداکننده', 'type' => Controls_Manager::COLOR, 'default' => '#dedee3', 'selectors' => array( '{{WRAPPER}} .ard-post-tabs__nav' => 'border-block-end-color:{{VALUE}};' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_style_posts', array( 'label' => 'کارت نوشته', 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_responsive_control( 'post_gap', array( 'label' => 'فاصله نوشته‌ها', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 0, 'max' => 48 ) ), 'default' => array( 'unit' => 'px', 'size' => 24 ), 'selectors' => array( '{{WRAPPER}} .ard-post-tabs__list' => 'gap:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_control( 'post_title_color', array( 'label' => 'رنگ عنوان نوشته', 'type' => Controls_Manager::COLOR, 'default' => '#35353b', 'selectors' => array( '{{WRAPPER}} .ard-post-tabs__title' => 'color:{{VALUE}};' ) ) );
		$this->add_control( 'post_title_hover', array( 'label' => 'رنگ عنوان در هاور', 'type' => Controls_Manager::COLOR, 'default' => '#f20d5b', 'selectors' => array( '{{WRAPPER}} .ard-post-tabs__link:hover .ard-post-tabs__title' => 'color:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'post_title_typography', 'selector' => '{{WRAPPER}} .ard-post-tabs__title' ) );
		$this->add_control( 'post_meta_color', array( 'label' => 'رنگ زمان انتشار', 'type' => Controls_Manager::COLOR, 'default' => '#35353b', 'selectors' => array( '{{WRAPPER}} .ard-post-tabs__meta' => 'color:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'post_meta_typography', 'selector' => '{{WRAPPER}} .ard-post-tabs__meta' ) );
		$this->add_control( 'post_clock_color', array( 'label' => 'رنگ آیکون زمان', 'type' => Controls_Manager::COLOR, 'default' => '#35353b', 'selectors' => array( '{{WRAPPER}} .ard-post-tabs__clock' => 'color:{{VALUE}};' ) ) );
		$this->add_responsive_control( 'image_size', array( 'label' => 'اندازه تصویر', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 60, 'max' => 240 ) ), 'default' => array( 'unit' => 'px', 'size' => 112 ), 'selectors' => array( '{{WRAPPER}} .ard-post-tabs__media' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'image_radius', array( 'label' => 'گردی تصویر', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', '%' ), 'default' => array( 'top' => 28, 'right' => 28, 'bottom' => 28, 'left' => 28, 'unit' => 'px', 'isLinked' => true ), 'selectors' => array( '{{WRAPPER}} .ard-post-tabs__media' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$tabs     = isset( $settings['tabs'] ) && is_array( $settings['tabs'] ) ? $settings['tabs'] : array();
		$tabs     = array_values( array_filter( $tabs, static function ( $tab ) { return ! empty( $tab['title'] ); } ) );
		if ( empty( $tabs ) ) {
			$this->render_empty( 'حداقل یک تب اضافه کنید.' );
			return;
		}

		$id          = 'ard-post-tabs-' . $this->get_id();
		$current_id  = is_singular( 'post' ) ? (int) get_queried_object_id() : (int) get_the_ID();
		$limit       = max( 1, min( 30, (int) $settings['limit'] ) );
		$exclude     = 'yes' === $settings['exclude_current'] && $current_id > 0 ? $current_id : 0;
		$empty_text  = isset( $settings['empty_text'] ) ? (string) $settings['empty_text'] : 'نوشته‌ای برای نمایش وجود ندارد.';
		?>
		<div id="<?php echo esc_attr( $id ); ?>" class="ard-post-tabs" data-ard-post-tabs>
			<div class="ard-post-tabs__nav" role="tablist" aria-label="فهرست نوشته‌ها">
				<?php foreach ( $tabs as $index => $tab ) :
					$title = isset( $tab['title'] ) ? trim( (string) $tab['title'] ) : '';
					if ( '' === $title ) {
						continue;
					}
					$active = 0 === $index;
					$tab_id = $id . '-tab-' . $index;
					$panel_id = $id . '-panel-' . $index;
					?>
					<button class="ard-post-tabs__button<?php echo $active ? ' is-active' : ''; ?>" type="button" role="tab" id="<?php echo esc_attr( $tab_id ); ?>" aria-controls="<?php echo esc_attr( $panel_id ); ?>" aria-selected="<?php echo $active ? 'true' : 'false'; ?>" tabindex="<?php echo $active ? '0' : '-1'; ?>" data-ard-tab="<?php echo esc_attr( $index ); ?>">
						<span class="ard-post-tabs__label"><?php echo esc_html( $title ); ?></span>
						<span class="ard-post-tabs__icon" aria-hidden="true">
							<?php if ( ! empty( $tab['custom_icon']['value'] ) ) : ?>
								<?php Icons_Manager::render_icon( $tab['custom_icon'], array( 'aria-hidden' => 'true' ) ); ?>
							<?php else : ?>
								<?php echo Ardal_Helpers::icon( isset( $tab['icon'] ) ? $tab['icon'] : 'star', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php endif; ?>
						</span>
					</button>
				<?php endforeach; ?>
			</div>
			<div class="ard-post-tabs__panels">
				<?php foreach ( $tabs as $index => $tab ) :
					$title = isset( $tab['title'] ) ? trim( (string) $tab['title'] ) : '';
					if ( '' === $title ) {
						continue;
					}
					$active   = 0 === $index;
					$tab_id   = $id . '-tab-' . $index;
					$panel_id = $id . '-panel-' . $index;
					$posts    = $this->query_posts( $tab, $limit, $exclude, $settings );
					?>
					<div class="ard-post-tabs__panel<?php echo $active ? ' is-active' : ''; ?>" role="tabpanel" id="<?php echo esc_attr( $panel_id ); ?>" aria-labelledby="<?php echo esc_attr( $tab_id ); ?>" data-ard-panel="<?php echo esc_attr( $index ); ?>" <?php echo $active ? '' : 'hidden'; ?>>
						<?php if ( $posts ) : ?>
							<div class="ard-post-tabs__list">
								<?php foreach ( $posts as $post ) : ?>
									<?php $this->render_post( $post, $settings ); ?>
								<?php endforeach; ?>
							</div>
						<?php else : ?>
							<p class="ard-post-tabs__empty"><?php echo esc_html( $empty_text ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * @param array $tab Tab settings.
	 * @param int   $limit Max posts.
	 * @param int   $exclude Current post ID to exclude.
	 * @param array $settings Widget settings.
	 * @return WP_Post[]
	 */
	private function query_posts( $tab, $limit, $exclude, $settings ) {
		$source = isset( $tab['source'] ) ? $tab['source'] : 'latest';
		$args   = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => $limit,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'orderby'             => 'date',
			'order'               => 'DESC',
		);
		if ( $exclude ) {
			$args['post__not_in'] = array( $exclude );
		}
		$categories = isset( $tab['categories'] ) ? array_filter( array_map( 'absint', (array) $tab['categories'] ) ) : array();
		if ( $categories ) {
			$args['category__in'] = $categories;
		}
		if ( 'comments' === $source || ( 'popular' === $source && 'views' !== $settings['popular_by'] ) ) {
			$args['orderby'] = 'comment_count';
		}
		if ( 'popular' === $source && 'views' === $settings['popular_by'] && ! empty( $settings['views_meta_key'] ) ) {
			$args['meta_key'] = sanitize_key( $settings['views_meta_key'] );
			$args['orderby']  = 'meta_value_num';
		}
		$period = isset( $tab['period'] ) ? $tab['period'] : 'all';
		$days   = array( 'day' => 1, 'week' => 7, 'month' => 30 );
		if ( 'latest' !== $source && isset( $days[ $period ] ) ) {
			$cutoff             = current_time( 'timestamp' ) - ( $days[ $period ] * DAY_IN_SECONDS );
			$args['date_query'] = array( array( 'after' => wp_date( 'Y-m-d H:i:s', $cutoff ), 'inclusive' => true ) );
		}

		$query = new WP_Query( $args );
		$posts = $query->posts;
		if ( ! $posts && 'popular' === $source && isset( $args['meta_key'] ) ) {
			unset( $args['meta_key'] );
			$args['orderby'] = 'comment_count';
			$query           = new WP_Query( $args );
			$posts           = $query->posts;
		}
		return $posts;
	}

	/**
	 * @param WP_Post $post Post object.
	 * @param array   $settings Widget settings.
	 */
	private function render_post( $post, $settings ) {
		$title = get_the_title( $post );
		$url   = get_permalink( $post );
		?>
		<article class="ard-post-tabs__item">
			<a class="ard-post-tabs__link" href="<?php echo esc_url( $url ); ?>">
				<?php if ( 'yes' === $settings['show_image'] ) : ?>
					<span class="ard-post-tabs__media">
						<?php echo Ardal_Helpers::image( array( 'url' => get_the_post_thumbnail_url( $post, 'medium' ) ), '', $title, 'medium' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
				<?php endif; ?>
				<span class="ard-post-tabs__body">
					<span class="ard-post-tabs__title"><?php echo esc_html( $title ); ?></span>
					<?php if ( 'yes' === $settings['show_time'] ) : ?>
						<span class="ard-post-tabs__meta">
							<?php echo Ardal_Helpers::icon( 'clock', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span><?php echo esc_html( Ardal_Helpers::fa_digits( human_time_diff( (int) get_post_time( 'U', true, $post ), (int) current_time( 'timestamp', true ) ) . ' پیش' ) ); ?></span>
						</span>
					<?php endif; ?>
				</span>
			</a>
		</article>
		<?php
	}

	/**
	 * @return array
	 */
	private function category_options() {
		$terms = get_terms( array( 'taxonomy' => 'category', 'hide_empty' => false, 'number' => 500, 'orderby' => 'name', 'order' => 'ASC' ) );
		$out   = array();
		if ( is_wp_error( $terms ) || ! is_array( $terms ) ) {
			return $out;
		}
		foreach ( $terms as $term ) {
			$out[ $term->term_id ] = $term->name;
		}
		return $out;
	}
}
