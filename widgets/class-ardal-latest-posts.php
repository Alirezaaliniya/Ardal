<?php
/**
 * Latest posts list for single post layouts.
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

class Ardal_Widget_Latest_Posts extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-latest-posts';
	}

	public function get_title() {
		return 'اردال — آخرین مقالات';
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_keywords() {
		return array( 'اردال', 'آخرین مقالات', 'آخرین بررسی‌ها', 'نوشته‌های جدید', 'latest posts', 'recent articles' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_content', array( 'label' => 'محتوا' ) );
		$this->add_control( 'title', array( 'label' => 'عنوان فهرست', 'type' => Controls_Manager::TEXT, 'default' => 'آخرین بررسی‌ها', 'label_block' => true ) );
		$this->add_tag_control( 'title_tag', 'تگ عنوان فهرست', 'h3', array( 'condition' => array( 'title!' => '' ) ) );
		$this->add_control( 'icon', array( 'label' => 'آیکون عنوان', 'type' => Controls_Manager::SELECT, 'default' => 'grid', 'options' => Ardal_Helpers::icon_options() ) );
		$this->add_control( 'custom_icon', array( 'label' => 'یا آیکون دلخواه', 'type' => Controls_Manager::ICONS, 'default' => array(), 'recommended' => array( 'fa-solid' ) ) );
		$this->add_control( 'limit', array( 'label' => 'تعداد مقاله‌ها', 'type' => Controls_Manager::NUMBER, 'default' => 3, 'min' => 1, 'max' => 30, 'description' => 'تعداد نوشته‌های جدیدی که نمایش داده می‌شوند.' ) );
		$this->add_control( 'categories', array( 'label' => 'محدود به دسته‌ها', 'type' => Controls_Manager::SELECT2, 'multiple' => true, 'label_block' => true, 'options' => $this->category_options(), 'default' => array(), 'description' => 'خالی = آخرین نوشته‌ها از همه دسته‌ها.' ) );
		$this->add_control( 'exclude_current', array( 'label' => 'حذف نوشته جاری', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes', 'description' => 'برای استفاده در قالب نوشته تکی روشن بماند.' ) );
		$this->add_control( 'show_image', array( 'label' => 'نمایش تصویر شاخص', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ) );
		$this->add_control( 'show_date', array( 'label' => 'نمایش تاریخ انتشار', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ) );
		$this->add_control(
			'date_format',
			array(
				'label'     => 'قالب تاریخ شمسی',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'numeric',
				'options'   => Ardal_Jalali::formats(),
				'condition' => array( 'show_date' => 'yes' ),
			)
		);
		$this->add_control( 'empty_text', array( 'label' => 'متن فهرست خالی', 'type' => Controls_Manager::TEXT, 'default' => 'مقاله‌ای برای نمایش وجود ندارد.', 'label_block' => true ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_style_box', array( 'label' => 'کادر ویجت', 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'box_bg', array( 'label' => 'رنگ پس‌زمینه', 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => array( '{{WRAPPER}} .ard-latest-posts' => 'background-color:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Border::get_type(), array( 'name' => 'box_border', 'selector' => '{{WRAPPER}} .ard-latest-posts' ) );
		$this->add_responsive_control( 'box_radius', array( 'label' => 'گردی گوشه‌ها', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', '%' ), 'default' => array( 'top' => 30, 'right' => 30, 'bottom' => 30, 'left' => 30, 'unit' => 'px', 'isLinked' => true ), 'selectors' => array( '{{WRAPPER}} .ard-latest-posts' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), array( 'name' => 'box_shadow', 'selector' => '{{WRAPPER}} .ard-latest-posts' ) );
		$this->add_responsive_control( 'box_padding', array( 'label' => 'فاصله داخلی', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'em' ), 'default' => array( 'top' => 20, 'right' => 20, 'bottom' => 20, 'left' => 20, 'unit' => 'px', 'isLinked' => false ), 'selectors' => array( '{{WRAPPER}} .ard-latest-posts' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_style_heading', array( 'label' => 'عنوان و جداکننده', 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'heading_color', array( 'label' => 'رنگ عنوان', 'type' => Controls_Manager::COLOR, 'default' => '#34343a', 'selectors' => array( '{{WRAPPER}} .ard-latest-posts__heading' => 'color:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'heading_typography', 'selector' => '{{WRAPPER}} .ard-latest-posts__heading-text' ) );
		$this->add_control( 'heading_icon_color', array( 'label' => 'رنگ آیکون عنوان', 'type' => Controls_Manager::COLOR, 'default' => '#34343a', 'selectors' => array( '{{WRAPPER}} .ard-latest-posts__heading-icon' => 'color:{{VALUE}};' ) ) );
		$this->add_responsive_control( 'heading_icon_size', array( 'label' => 'اندازه آیکون عنوان', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 12, 'max' => 40 ) ), 'default' => array( 'unit' => 'px', 'size' => 24 ), 'selectors' => array( '{{WRAPPER}} .ard-latest-posts__heading-icon > svg' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};', '{{WRAPPER}} .ard-latest-posts__heading-icon > i' => 'font-size:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'heading_gap', array( 'label' => 'فاصله عنوان و آیکون', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 0, 'max' => 24 ) ), 'default' => array( 'unit' => 'px', 'size' => 10 ), 'selectors' => array( '{{WRAPPER}} .ard-latest-posts__heading' => 'gap:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'divider_gap', array( 'label' => 'فاصله جداکننده', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 0, 'max' => 40 ) ), 'default' => array( 'unit' => 'px', 'size' => 18 ), 'selectors' => array( '{{WRAPPER}} .ard-latest-posts__divider' => 'margin-block:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_control( 'divider_color', array( 'label' => 'رنگ خط جداکننده', 'type' => Controls_Manager::COLOR, 'default' => '#d4d4d8', 'selectors' => array( '{{WRAPPER}} .ard-latest-posts__divider' => 'border-block-start-color:{{VALUE}};' ) ) );
		$this->add_responsive_control( 'divider_width', array( 'label' => 'ضخامت خط', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 1, 'max' => 6 ) ), 'default' => array( 'unit' => 'px', 'size' => 1 ), 'selectors' => array( '{{WRAPPER}} .ard-latest-posts__divider' => 'border-block-start-width:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_control( 'accent_color', array( 'label' => 'رنگ خط کوتاه', 'type' => Controls_Manager::COLOR, 'default' => '#55555b', 'selectors' => array( '{{WRAPPER}} .ard-latest-posts__divider::after' => 'background-color:{{VALUE}};' ) ) );
		$this->add_responsive_control( 'accent_width', array( 'label' => 'طول خط کوتاه', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px', '%' ), 'range' => array( 'px' => array( 'min' => 20, 'max' => 180 ), '%' => array( 'min' => 10, 'max' => 60 ) ), 'default' => array( 'unit' => 'px', 'size' => 100 ), 'selectors' => array( '{{WRAPPER}} .ard-latest-posts__divider::after' => 'width:{{SIZE}}{{UNIT}};' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_style_items', array( 'label' => 'مقاله‌ها', 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_responsive_control( 'items_gap', array( 'label' => 'فاصله مقاله‌ها', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 0, 'max' => 60 ) ), 'default' => array( 'unit' => 'px', 'size' => 32 ), 'selectors' => array( '{{WRAPPER}} .ard-latest-posts__list' => 'gap:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'item_gap', array( 'label' => 'فاصله تصویر و متن', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 0, 'max' => 40 ) ), 'default' => array( 'unit' => 'px', 'size' => 16 ), 'selectors' => array( '{{WRAPPER}} .ard-latest-posts__link' => 'gap:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_control( 'title_color', array( 'label' => 'رنگ عنوان مقاله', 'type' => Controls_Manager::COLOR, 'default' => '#34343a', 'selectors' => array( '{{WRAPPER}} .ard-latest-posts__title' => 'color:{{VALUE}};' ) ) );
		$this->add_control( 'title_hover_color', array( 'label' => 'رنگ عنوان در هاور', 'type' => Controls_Manager::COLOR, 'default' => '#f20d5b', 'selectors' => array( '{{WRAPPER}} .ard-latest-posts__link:hover .ard-latest-posts__title' => 'color:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'post_title_typography', 'selector' => '{{WRAPPER}} .ard-latest-posts__title' ) );
		$this->add_control( 'date_color', array( 'label' => 'رنگ تاریخ', 'type' => Controls_Manager::COLOR, 'default' => '#34343a', 'selectors' => array( '{{WRAPPER}} .ard-latest-posts__date' => 'color:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'date_typography', 'selector' => '{{WRAPPER}} .ard-latest-posts__date' ) );
		$this->add_control( 'date_icon_color', array( 'label' => 'رنگ آیکون تاریخ', 'type' => Controls_Manager::COLOR, 'default' => '#34343a', 'selectors' => array( '{{WRAPPER}} .ard-latest-posts__date-icon' => 'color:{{VALUE}};' ) ) );
		$this->add_responsive_control( 'date_icon_size', array( 'label' => 'اندازه آیکون تاریخ', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 10, 'max' => 32 ) ), 'default' => array( 'unit' => 'px', 'size' => 20 ), 'selectors' => array( '{{WRAPPER}} .ard-latest-posts__date-icon' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'image_width', array( 'label' => 'عرض تصویر', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px', '%' ), 'range' => array( 'px' => array( 'min' => 60, 'max' => 260 ), '%' => array( 'min' => 20, 'max' => 50 ) ), 'default' => array( 'unit' => 'px', 'size' => 160 ), 'selectors' => array( '{{WRAPPER}} .ard-latest-posts__media' => 'width:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'image_height', array( 'label' => 'ارتفاع تصویر', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 60, 'max' => 260 ) ), 'default' => array( 'unit' => 'px', 'size' => 180 ), 'selectors' => array( '{{WRAPPER}} .ard-latest-posts__media' => 'height:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'image_radius', array( 'label' => 'گردی تصویر', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', '%' ), 'default' => array( 'top' => 28, 'right' => 28, 'bottom' => 28, 'left' => 28, 'unit' => 'px', 'isLinked' => true ), 'selectors' => array( '{{WRAPPER}} .ard-latest-posts__media' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->add_control( 'image_fit', array( 'label' => 'نحوه نمایش تصویر', 'type' => Controls_Manager::SELECT, 'default' => 'cover', 'options' => array( 'cover' => 'پرکردن قاب', 'contain' => 'نمایش کامل' ), 'selectors' => array( '{{WRAPPER}} .ard-latest-posts__media img' => 'object-fit:{{VALUE}};' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$limit    = max( 1, min( 30, (int) $settings['limit'] ) );
		$current  = is_singular( 'post' ) ? (int) get_queried_object_id() : (int) get_the_ID();
		$args     = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => $limit,
			'orderby'             => 'date',
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);
		if ( 'yes' === $settings['exclude_current'] && $current > 0 ) {
			$args['post__not_in'] = array( $current );
		}
		$categories = isset( $settings['categories'] ) ? array_filter( array_map( 'absint', (array) $settings['categories'] ) ) : array();
		if ( $categories ) {
			$args['category__in'] = $categories;
		}
		$query = new WP_Query( $args );
		$posts = $query->posts;
		$title = isset( $settings['title'] ) ? trim( (string) $settings['title'] ) : '';
		$tag   = self::tag( isset( $settings['title_tag'] ) ? $settings['title_tag'] : 'h3', 'h3' );
		?>
		<section class="ard-latest-posts" aria-label="<?php echo esc_attr( '' !== $title ? $title : 'آخرین مقالات' ); ?>">
			<?php if ( '' !== $title ) : ?>
				<div class="ard-latest-posts__heading">
					<span class="ard-latest-posts__heading-icon" aria-hidden="true">
						<?php if ( ! empty( $settings['custom_icon']['value'] ) ) : ?>
							<?php Icons_Manager::render_icon( $settings['custom_icon'], array( 'aria-hidden' => 'true' ) ); ?>
						<?php else : ?>
							<?php echo Ardal_Helpers::icon( $settings['icon'], 24 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php endif; ?>
					</span>
					<<?php echo esc_html( $tag ); ?> class="ard-latest-posts__heading-text"><?php echo esc_html( $title ); ?></<?php echo esc_html( $tag ); ?>>
				</div>
				<div class="ard-latest-posts__divider"><span></span></div>
			<?php endif; ?>
			<?php if ( $posts ) : ?>
				<div class="ard-latest-posts__list">
					<?php foreach ( $posts as $post ) : ?>
						<article class="ard-latest-posts__item">
							<a class="ard-latest-posts__link" href="<?php echo esc_url( get_permalink( $post ) ); ?>">
								<?php if ( 'yes' === $settings['show_image'] ) : ?>
									<span class="ard-latest-posts__media">
										<?php echo Ardal_Helpers::image( array( 'url' => get_the_post_thumbnail_url( $post, 'medium_large' ) ), '', get_the_title( $post ), 'medium_large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</span>
								<?php endif; ?>
								<span class="ard-latest-posts__body">
									<span class="ard-latest-posts__title"><?php echo esc_html( get_the_title( $post ) ); ?></span>
									<?php if ( 'yes' === $settings['show_date'] ) : ?>
										<span class="ard-latest-posts__date">
											<span class="ard-latest-posts__date-icon"><?php echo Ardal_Helpers::icon( 'clock', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
											<span><?php echo esc_html( Ardal_Jalali::format( (int) get_post_time( 'U', true, $post ), $settings['date_format'] ) ); ?></span>
										</span>
									<?php endif; ?>
								</span>
							</a>
						</article>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<p class="ard-latest-posts__empty"><?php echo esc_html( $settings['empty_text'] ); ?></p>
			<?php endif; ?>
		</section>
		<?php
	}

	/**
	 * @return array
	 */
	private function category_options() {
		$terms = get_terms( array( 'taxonomy' => 'category', 'hide_empty' => false, 'number' => 500, 'orderby' => 'name', 'order' => 'ASC' ) );
		$options = array();
		if ( is_wp_error( $terms ) || ! is_array( $terms ) ) {
			return $options;
		}
		foreach ( $terms as $term ) {
			$options[ $term->term_id ] = $term->name;
		}
		return $options;
	}
}
