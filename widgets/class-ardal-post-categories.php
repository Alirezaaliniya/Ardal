<?php
/**
 * Post categories list widget.
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

class Ardal_Widget_Post_Categories extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-post-categories';
	}

	public function get_title() {
		return 'اردال — دسته‌بندی نوشته‌ها';
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_keywords() {
		return array( 'اردال', 'دسته‌بندی نوشته', 'دسته‌های وبلاگ', 'post categories', 'category list' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_content', array( 'label' => 'محتوا' ) );
		$this->add_control(
			'title',
			array(
				'label'       => 'عنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'دسته‌بندی مقالات',
				'label_block' => true,
			)
		);
		$this->add_tag_control( 'title_tag', 'تگ عنوان', 'h3', array( 'condition' => array( 'title!' => '' ) ) );
		$this->add_control(
			'icon',
			array(
				'label'   => 'آیکون عنوان',
				'type'    => Controls_Manager::SELECT,
				'default' => 'tag',
				'options' => Ardal_Helpers::icon_options(),
			)
		);
		$this->add_control(
			'custom_icon',
			array(
				'label'       => 'آیکون دلخواه عنوان',
				'type'        => Controls_Manager::ICONS,
				'default'     => array(),
				'recommended' => array( 'fa-solid' ),
			)
		);
		$this->add_control(
			'limit',
			array(
				'label'       => 'تعداد دسته‌های قابل نمایش',
				'type'        => Controls_Manager::NUMBER,
			'default'     => 0,
				'min'         => 0,
				'max'         => 500,
				'description' => 'صفر یعنی نمایش همه دسته‌ها.',
			)
		);
		$this->add_control(
			'show_children',
			array(
				'label'        => 'نمایش زیر‌دسته‌ها',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'label_on'     => 'بله',
				'label_off'    => 'خیر',
			)
		);
		$this->add_control(
			'depth',
			array(
				'label'       => 'عمق نمایش دسته‌ها',
				'type'        => Controls_Manager::NUMBER,
				'default'     => 3,
				'min'         => 1,
				'max'         => 10,
				'description' => 'سطح ۱ فقط دسته‌های اصلی است؛ سطح ۲ زیر‌دسته‌های آن‌ها را هم اضافه می‌کند.',
				'condition'   => array( 'show_children' => 'yes' ),
			)
		);
		$this->add_control(
			'orderby',
			array(
				'label'   => 'مرتب‌سازی بر اساس',
				'type'    => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => array(
					'name'    => 'نام دسته',
					'count'   => 'تعداد نوشته‌ها',
				'term_id' => 'شناسه دسته',
				),
			)
		);
		$this->add_control(
			'order',
			array(
				'label'   => 'ترتیب',
				'type'    => Controls_Manager::SELECT,
				'default' => 'ASC',
				'options' => array( 'ASC' => 'صعودی', 'DESC' => 'نزولی' ),
			)
		);
		$this->add_control(
			'parent',
			array(
				'label'       => 'دسته والد',
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->category_options(),
				'label_block' => true,
				'description' => 'خالی = شروع از دسته‌های اصلی؛ با انتخاب یک دسته، فهرست از زیر‌دسته‌های همان دسته شروع می‌شود.',
			)
		);
		$this->add_control(
			'hide_empty',
			array(
				'label'        => 'پنهان‌کردن دسته‌های بدون نوشته',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);
		$this->add_control(
			'show_count',
			array(
				'label'        => 'نمایش تعداد نوشته‌ها',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);
		$this->add_control(
			'show_arrow',
			array(
				'label'        => 'نمایش فلش کنار دسته',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);
		$this->add_control(
			'empty_text',
			array(
				'label'       => 'متن جایگزین در نبود دسته',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => 'خالی = نمایش‌ندادن ویجت.',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section( 'section_style_box', array( 'label' => 'کادر ویجت', 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'box_bg', array( 'label' => 'پس‌زمینه', 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => array( '{{WRAPPER}} .ard-pcats' => 'background-color:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Border::get_type(), array( 'name' => 'box_border', 'selector' => '{{WRAPPER}} .ard-pcats' ) );
		$this->add_responsive_control(
			'box_radius',
			array(
				'label' => 'گردی گوشه‌ها', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', '%' ),
				'default' => array( 'top' => 20, 'right' => 20, 'bottom' => 20, 'left' => 20, 'unit' => 'px', 'isLinked' => true ),
				'selectors' => array( '{{WRAPPER}} .ard-pcats' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), array( 'name' => 'box_shadow', 'selector' => '{{WRAPPER}} .ard-pcats' ) );
		$this->add_responsive_control(
			'box_padding',
			array(
				'label' => 'فاصله داخلی', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'em' ),
				'default' => array( 'top' => 14, 'right' => 16, 'bottom' => 14, 'left' => 16, 'unit' => 'px', 'isLinked' => false ),
				'selectors' => array( '{{WRAPPER}} .ard-pcats' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section( 'section_style_title', array( 'label' => 'عنوان و جداکننده', 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'title_color', array( 'label' => 'رنگ عنوان', 'type' => Controls_Manager::COLOR, 'default' => '#24242a', 'selectors' => array( '{{WRAPPER}} .ard-pcats__title' => 'color:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'title_typography', 'selector' => '{{WRAPPER}} .ard-pcats__title' ) );
		$this->add_control( 'title_icon_color', array( 'label' => 'رنگ آیکون عنوان', 'type' => Controls_Manager::COLOR, 'default' => '#55555b', 'selectors' => array( '{{WRAPPER}} .ard-pcats__title-icon' => 'color:{{VALUE}};' ) ) );
		$this->add_responsive_control( 'title_icon_size', array( 'label' => 'اندازه آیکون عنوان', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 12, 'max' => 40 ) ), 'default' => array( 'unit' => 'px', 'size' => 20 ), 'selectors' => array( '{{WRAPPER}} .ard-pcats__title-icon > svg' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};', '{{WRAPPER}} .ard-pcats__title-icon > i' => 'font-size:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'title_gap', array( 'label' => 'فاصله عنوان تا آیکون', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 0, 'max' => 24 ) ), 'default' => array( 'unit' => 'px', 'size' => 8 ), 'selectors' => array( '{{WRAPPER}} .ard-pcats__heading' => 'gap:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'divider_gap', array( 'label' => 'فاصله جداکننده تا فهرست', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 0, 'max' => 40 ) ), 'default' => array( 'unit' => 'px', 'size' => 12 ), 'selectors' => array( '{{WRAPPER}} .ard-pcats__divider' => 'margin-block:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_control( 'divider_color', array( 'label' => 'رنگ جداکننده', 'type' => Controls_Manager::COLOR, 'default' => '#d7d7da', 'selectors' => array( '{{WRAPPER}} .ard-pcats__divider' => 'border-color:{{VALUE}};' ) ) );
		$this->add_responsive_control( 'divider_width', array( 'label' => 'ضخامت جداکننده', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 1, 'max' => 6 ) ), 'default' => array( 'unit' => 'px', 'size' => 1 ), 'selectors' => array( '{{WRAPPER}} .ard-pcats__divider' => 'border-block-start-width:{{SIZE}}{{UNIT}};' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_style_rows', array( 'label' => 'ردیف دسته‌ها', 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_responsive_control( 'row_gap', array( 'label' => 'فاصله بین ردیف‌ها', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 0, 'max' => 32 ) ), 'default' => array( 'unit' => 'px', 'size' => 8 ), 'selectors' => array( '{{WRAPPER}} .ard-pcats__list' => 'gap:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'nested_gap', array( 'label' => 'فاصله بین زیر‌دسته‌ها', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 0, 'max' => 32 ) ), 'default' => array( 'unit' => 'px', 'size' => 6 ), 'selectors' => array( '{{WRAPPER}} .ard-pcats__children' => 'gap:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'row_padding', array( 'label' => 'فاصله داخلی ردیف', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'em' ), 'default' => array( 'top' => 4, 'right' => 0, 'bottom' => 4, 'left' => 0, 'unit' => 'px', 'isLinked' => false ), 'selectors' => array( '{{WRAPPER}} .ard-pcats__link' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->add_control( 'row_bg_hover', array( 'label' => 'پس‌زمینه ردیف در هاور', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-pcats__link:hover' => 'background-color:{{VALUE}};' ) ) );
		$this->add_responsive_control( 'row_radius', array( 'label' => 'گردی ردیف', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 0, 'max' => 24 ) ), 'default' => array( 'unit' => 'px', 'size' => 8 ), 'selectors' => array( '{{WRAPPER}} .ard-pcats__link' => 'border-radius:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_control( 'row_color', array( 'label' => 'رنگ نام دسته', 'type' => Controls_Manager::COLOR, 'default' => '#29292f', 'selectors' => array( '{{WRAPPER}} .ard-pcats__name' => 'color:{{VALUE}};' ) ) );
		$this->add_control( 'row_color_hover', array( 'label' => 'رنگ نام در هاور', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-pcats__link:hover .ard-pcats__name' => 'color:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'row_typography', 'selector' => '{{WRAPPER}} .ard-pcats__name' ) );
		$this->add_control( 'arrow_color', array( 'label' => 'رنگ فلش', 'type' => Controls_Manager::COLOR, 'default' => '#44444a', 'selectors' => array( '{{WRAPPER}} .ard-pcats__arrow' => 'color:{{VALUE}};' ) ) );
		$this->add_responsive_control( 'arrow_size', array( 'label' => 'اندازه فلش', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 8, 'max' => 24 ) ), 'default' => array( 'unit' => 'px', 'size' => 14 ), 'selectors' => array( '{{WRAPPER}} .ard-pcats__arrow' => 'font-size:{{SIZE}}{{UNIT}};' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'section_style_count', array( 'label' => 'نشان تعداد', 'tab' => Controls_Manager::TAB_STYLE, 'condition' => array( 'show_count' => 'yes' ) ) );
		$this->add_control( 'count_bg', array( 'label' => 'پس‌زمینه نشان', 'type' => Controls_Manager::COLOR, 'default' => '#f20d5b', 'selectors' => array( '{{WRAPPER}} .ard-pcats__count' => 'background-color:{{VALUE}};' ) ) );
		$this->add_control( 'count_color', array( 'label' => 'رنگ عدد', 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => array( '{{WRAPPER}} .ard-pcats__count' => 'color:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'count_typography', 'selector' => '{{WRAPPER}} .ard-pcats__count' ) );
		$this->add_responsive_control( 'count_padding', array( 'label' => 'فاصله داخلی نشان', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'em' ), 'default' => array( 'top' => 2, 'right' => 8, 'bottom' => 2, 'left' => 8, 'unit' => 'px', 'isLinked' => false ), 'selectors' => array( '{{WRAPPER}} .ard-pcats__count' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'count_radius', array( 'label' => 'گردی نشان', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 0, 'max' => 30 ) ), 'default' => array( 'unit' => 'px', 'size' => 20 ), 'selectors' => array( '{{WRAPPER}} .ard-pcats__count' => 'border-radius:{{SIZE}}{{UNIT}};' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$limit    = isset( $settings['limit'] ) ? max( 0, (int) $settings['limit'] ) : 0;
		$parent   = isset( $settings['parent'] ) ? absint( $settings['parent'] ) : 0;
		$args     = array(
			'taxonomy'   => 'category',
			'hide_empty' => false,
			'orderby'    => in_array( $settings['orderby'], array( 'name', 'count', 'term_id' ), true ) ? $settings['orderby'] : 'name',
			'order'      => 'DESC' === $settings['order'] ? 'DESC' : 'ASC',
			'number'     => 0,
		);
		$terms = get_terms( $args );
		if ( is_wp_error( $terms ) || ! is_array( $terms ) ) {
			$terms = array();
		}

		$title = isset( $settings['title'] ) ? trim( (string) $settings['title'] ) : '';
		$empty = isset( $settings['empty_text'] ) ? trim( (string) $settings['empty_text'] ) : '';
		if ( ! $terms && '' === $empty && '' === $title ) {
			return;
		}
		if ( ! $terms && '' === $empty ) {
			$this->render_empty( 'هنوز دسته‌ای برای نمایش وجود ندارد.' );
			return;
		}

		$show_children = isset( $settings['show_children'] ) && 'yes' === $settings['show_children'];
		$depth         = $show_children ? max( 1, min( 10, (int) $settings['depth'] ) ) : 1;
		$terms_by_parent = array();
		foreach ( $terms as $term ) {
			$terms_by_parent[ (int) $term->parent ][] = $term;
		}
		$tree = $this->category_tree( $parent, $terms_by_parent, $depth, isset( $settings['hide_empty'] ) && 'yes' === $settings['hide_empty'] );
		if ( ! $tree && '' === $empty ) {
			$this->render_empty( 'برای این انتخاب دسته‌ای جهت نمایش پیدا نشد.' );
			return;
		}

		$current_id = is_category() ? (int) get_queried_object_id() : 0;
		$heading_tag = self::tag( isset( $settings['title_tag'] ) ? $settings['title_tag'] : 'h3', 'h3' );
		?>
		<section class="ard-pcats" aria-label="<?php echo esc_attr( '' !== $title ? $title : 'دسته‌بندی نوشته‌ها' ); ?>">
			<?php if ( '' !== $title ) : ?>
				<div class="ard-pcats__heading">
					<span class="ard-pcats__title-icon">
						<?php if ( ! empty( $settings['custom_icon']['value'] ) ) : ?>
							<?php Icons_Manager::render_icon( $settings['custom_icon'], array( 'aria-hidden' => 'true' ) ); ?>
						<?php else : ?>
							<?php echo Ardal_Helpers::icon( isset( $settings['icon'] ) ? $settings['icon'] : 'tag', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php endif; ?>
					</span>
					<<?php echo esc_html( $heading_tag ); ?> class="ard-pcats__title"><?php echo esc_html( $title ); ?></<?php echo esc_html( $heading_tag ); ?>>
				</div>
				<hr class="ard-pcats__divider" />
			<?php endif; ?>
			<?php if ( ! $tree ) : ?>
				<p class="ard-pcats__empty"><?php echo esc_html( $empty ); ?></p>
			<?php else : ?>
				<?php $shown = 0; ?>
				<ul class="ard-pcats__list">
					<?php echo $this->render_category_tree( $tree, $limit, $shown, $current_id, $settings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</ul>
			<?php endif; ?>
		</section>
		<?php
	}

	/**
	 * Build a category tree up to the requested number of visible levels.
	 *
	 * @param int   $parent_id Parent term ID.
	 * @param array $terms_by_parent Terms indexed by parent ID.
	 * @param int   $depth Remaining visible levels.
	 * @param bool  $hide_empty Hide terms without directly assigned posts.
	 * @return array
	 */
	private function category_tree( $parent_id, $terms_by_parent, $depth, $hide_empty ) {
		if ( $depth < 1 || empty( $terms_by_parent[ $parent_id ] ) ) {
			return array();
		}

		$nodes = array();
		foreach ( $terms_by_parent[ $parent_id ] as $term ) {
			$children = $this->category_tree( (int) $term->term_id, $terms_by_parent, $depth - 1, $hide_empty );
			if ( $hide_empty && 0 === (int) $term->count ) {
				$nodes = array_merge( $nodes, $children );
				continue;
			}
			$nodes[] = array(
				'term'     => $term,
				'children' => $children,
			);
		}
		return $nodes;
	}

	/**
	 * Render nested category rows while enforcing the total item limit.
	 *
	 * @param array $nodes Tree nodes.
	 * @param int   $limit Maximum total items; zero means unlimited.
	 * @param int   $shown Number already rendered, passed by reference.
	 * @param int   $current_id Current category ID.
	 * @param array $settings Widget settings.
	 * @return string
	 */
	private function render_category_tree( $nodes, $limit, &$shown, $current_id, $settings ) {
		$html = '';
		foreach ( $nodes as $node ) {
			if ( $limit > 0 && $shown >= $limit ) {
				break;
			}
			$term      = $node['term'];
			$url       = get_term_link( $term );
			if ( is_wp_error( $url ) ) {
				continue;
			}
			$is_current = (int) $term->term_id === $current_id;
			$shown++;
			$html .= '<li class="ard-pcats__item">';
			$html .= '<a class="ard-pcats__link' . ( $is_current ? ' is-current' : '' ) . '" href="' . esc_url( $url ) . '"' . ( $is_current ? ' aria-current="page"' : '' ) . '>';
			$html .= '<span class="ard-pcats__label">';
			if ( 'yes' === $settings['show_arrow'] ) {
				$html .= '<span class="ard-pcats__arrow" aria-hidden="true">‹</span>';
			}
			$html .= '<span class="ard-pcats__name">' . esc_html( $term->name ) . '</span></span>';
			if ( 'yes' === $settings['show_count'] ) {
				$html .= '<span class="ard-pcats__count">' . esc_html( Ardal_Helpers::fa_digits( (int) $term->count ) ) . '</span>';
			}
			$html .= '</a>';
			$children = isset( $node['children'] ) ? $node['children'] : array();
			if ( $children && ( 0 === (int) $limit || $shown < $limit ) ) {
				$html .= '<ul class="ard-pcats__children">' . $this->render_category_tree( $children, $limit, $shown, $current_id, $settings ) . '</ul>';
			}
			$html .= '</li>';
		}
		return $html;
	}

	/**
	 * @return array
	 */
	private function category_options() {
		$options = array();
		$terms   = get_terms( array( 'taxonomy' => 'category', 'hide_empty' => false, 'number' => 500, 'orderby' => 'name', 'order' => 'ASC' ) );
		if ( is_wp_error( $terms ) || ! is_array( $terms ) ) {
			return $options;
		}
		foreach ( $terms as $term ) {
			$options[ $term->term_id ] = str_repeat( '— ', (int) $term->parent ? 1 : 0 ) . $term->name;
		}
		return $options;
	}
}
