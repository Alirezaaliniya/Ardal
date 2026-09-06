<?php
/**
 * ویجت مطالب وبلاگ.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Posts extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-posts';
	}

	public function get_title() {
		return 'اردال — مطالب وبلاگ';
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	public function get_keywords() {
		return array( 'اردال', 'وبلاگ', 'مقاله', 'مطلب', 'blog', 'posts' );
	}

	protected function register_controls() {
		$this->add_heading_controls( 'از وبلاگ ورزشی', 'accent' );

		$this->start_controls_section(
			'section_content',
			array( 'label' => 'مطالب' )
		);

		$this->add_control(
			'source',
			array(
				'label'   => 'منبع',
				'type'    => Controls_Manager::SELECT,
				'default' => 'wp',
				'options' => array(
					'wp'     => 'آخرین نوشته‌های وردپرس',
					'manual' => 'دستی',
				),
			)
		);

		$this->add_control(
			'wp_limit',
			array(
				'label'     => 'تعداد',
				'type'      => Controls_Manager::NUMBER,
				'default'   => 3,
				'min'       => 1,
				'max'       => 12,
				'condition' => array( 'source' => 'wp' ),
			)
		);

		$this->add_control(
			'wp_category',
			array(
				'label'       => 'محدود به دسته',
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => $this->category_options(),
				'default'     => array(),
				'condition'   => array( 'source' => 'wp' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			array(
				'label'       => 'عنوان مطلب',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'default'     => 'چطور کفش دویدن مناسب پای خود را انتخاب کنیم؟',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'date',
			array(
				'label'   => 'تاریخ',
				'type'    => Controls_Manager::TEXT,
				'default' => '۱۲ شهریور',
			)
		);

		$repeater->add_control(
			'image',
			array(
				'label' => 'تصویر شاخص',
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
				'label'       => 'مطالب',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'condition'   => array( 'source' => 'manual' ),
				'default'     => array(
					array( 'title' => 'چطور کفش دویدن مناسب پای خود را انتخاب کنیم؟', 'date' => '۱۲ شهریور' ),
					array( 'title' => 'برنامه تمرینی ۴ هفته‌ای برای شروع بدنسازی', 'date' => '۸ شهریور' ),
					array( 'title' => 'نگهداری از لوازم شنا؛ ۶ نکته کاربردی', 'date' => '۲ شهریور' ),
				),
			)
		);

		$this->add_control(
			'read_time',
			array(
				'label'       => 'متن زمان مطالعه',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'مثلاً ۵ دقیقه مطالعه',
				'description' => 'اگر پر شود، کنار تاریخ نمایش داده می‌شود.',
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
				'label'   => 'سبک',
				'type'    => Controls_Manager::SELECT,
				'default' => 'card',
				'options' => array(
					'card' => 'کارت عمودی با تصویر بالا',
					'row'  => 'تصویر کنار عنوان (افقی)',
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => 'تعداد ستون',
				'type'           => Controls_Manager::NUMBER,
				'min'            => 1,
				'max'            => 4,
				'default'        => 3,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'selectors'      => array(
					'{{WRAPPER}} .ard-posts' => 'grid-template-columns:repeat({{VALUE}},minmax(0,1fr));',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = $this->get_entries( $s );

		if ( empty( $items ) ) {
			$this->render_empty( 'مطلبی برای نمایش وجود ندارد.' );

			return;
		}

		$this->render_heading();
		?>
		<div class="ard-posts ard-posts--<?php echo esc_attr( $s['variant'] ); ?>">
			<?php foreach ( $items as $item ) : ?>
				<article class="ard-post">
					<div class="ard-post__media">
						<?php echo Ardal_Helpers::image( $item['image'], '۶۴۰×۴۲۰', $item['title'], 'medium_large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>

					<div class="ard-post__body">
						<h3 class="ard-post__title">
							<?php if ( ! empty( $item['link']['url'] ) ) : ?>
								<a<?php echo Ardal_Helpers::link_attrs( $item['link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $item['title'] ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $item['title'] ); ?>
							<?php endif; ?>
						</h3>

						<?php
						$meta = array();

						if ( '' !== trim( (string) $item['date'] ) ) {
							$meta[] = $item['date'];
						}

						if ( '' !== trim( (string) $s['read_time'] ) ) {
							$meta[] = $s['read_time'];
						}

						if ( ! empty( $meta ) ) :
							?>
							<p class="ard-post__meta"><?php echo esc_html( Ardal_Helpers::fa_digits( implode( ' · ', $meta ) ) ); ?></p>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * @param array $s
	 * @return array
	 */
	private function get_entries( $s ) {
		if ( 'manual' === $s['source'] ) {
			return is_array( $s['items'] ) ? $s['items'] : array();
		}

		$args = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => max( 1, (int) $s['wp_limit'] ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);

		$cats = array_filter( array_map( 'absint', (array) $s['wp_category'] ) );

		if ( ! empty( $cats ) ) {
			$args['category__in'] = $cats;
		}

		$query = new WP_Query( $args );
		$out   = array();

		foreach ( $query->posts as $post ) {
			$thumb = get_the_post_thumbnail_url( $post, 'medium_large' );

			$out[] = array(
				'title' => get_the_title( $post ),
				'date'  => get_the_date( 'j F', $post ),
				'image' => array( 'url' => $thumb ? $thumb : '' ),
				'link'  => array( 'url' => get_permalink( $post ) ),
			);
		}

		wp_reset_postdata();

		return $out;
	}

	/**
	 * @return array
	 */
	private function category_options() {
		$terms = get_terms(
			array(
				'taxonomy'   => 'category',
				'hide_empty' => false,
				'number'     => 200,
			)
		);

		$out = array();

		if ( is_wp_error( $terms ) ) {
			return $out;
		}

		foreach ( $terms as $term ) {
			$out[ $term->term_id ] = $term->name;
		}

		return $out;
	}
}
