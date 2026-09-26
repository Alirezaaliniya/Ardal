<?php
/**
 * ویجت برچسب‌های نوشته: چیپ‌های تاکسونومیِ نوشتهٔ جاری.
 *
 * روی هر تاکسونومی‌ای کار می‌کند (برچسب، دسته، یا تاکسونومی سفارشی)، پس هم
 * زیر مقالهٔ وبلاگ می‌نشیند و هم زیر محصول.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Post_Tags extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-post-tags';
	}

	public function get_title() {
		return 'اردال — برچسب‌های نوشته';
	}

	public function get_icon() {
		return 'eicon-tags';
	}

	public function get_keywords() {
		return array( 'اردال', 'برچسب', 'تگ', 'دسته', 'tags', 'terms' );
	}

	protected function register_controls() {

		/* ------------------------------------------------ محتوا */

		$this->start_controls_section(
			'section_content',
			array( 'label' => 'برچسب‌ها' )
		);

		$this->add_control(
			'taxonomy',
			array(
				'label'       => 'تاکسونومی',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'post_tag',
				'options'     => $this->taxonomy_options(),
				'label_block' => true,
			)
		);

		$this->add_control(
			'post_id',
			array(
				'label'       => 'شناسه نوشته',
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'default'     => 0,
				'description' => 'خالی یا صفر = نوشتهٔ جاری.',
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'       => 'حداکثر تعداد',
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 50,
				'default'     => 0,
				'description' => 'صفر = همه.',
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => 'ترتیب',
				'type'    => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => array(
					'name'  => 'الفبا',
					'count' => 'تعداد نوشته (نزولی)',
					'none'  => 'همان ترتیب خود نوشته',
				),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => 'عنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'برچسب‌ها',
				'label_block' => true,
				'description' => 'خالی = بدون عنوان.',
			)
		);

		$this->add_tag_control(
			'title_tag',
			'تگ عنوان',
			'h3',
			array( 'condition' => array( 'title!' => '' ) )
		);

		$this->add_control(
			'link',
			array(
				'label'        => 'چیپ‌ها لینک باشند',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_count',
			array(
				'label'        => 'نمایش تعداد نوشته‌ها',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'empty_text',
			array(
				'label'       => 'متن وقتی برچسبی نیست',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => 'خالی = کل ناحیه چاپ نشود.',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ ظاهر */

		$this->start_controls_section(
			'section_style',
			array(
				'label' => 'ظاهر',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'   => 'چیدمان',
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => array(
					'flex-start' => array( 'title' => 'شروع', 'icon' => 'eicon-text-align-right' ),
					'center'     => array( 'title' => 'وسط', 'icon' => 'eicon-text-align-center' ),
					'flex-end'   => array( 'title' => 'پایان', 'icon' => 'eicon-text-align-left' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .ard-ptags__list' => 'justify-content:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'chip_bg',
			array(
				'label'     => 'زمینه چیپ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-ptags' => '--ard-ptag-bg:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'chip_color',
			array(
				'label'     => 'رنگ متن چیپ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-ptags' => '--ard-ptag-ink:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'chip_border',
			array(
				'label'     => 'رنگ حاشیه چیپ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-ptags' => '--ard-ptag-line:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'chip_bg_hover',
			array(
				'label'     => 'زمینه چیپ در هاور',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-ptags' => '--ard-ptag-bg-hover:{{VALUE}};',
				),
				'condition' => array( 'link' => 'yes' ),
			)
		);

		$this->add_control(
			'chip_color_hover',
			array(
				'label'     => 'رنگ متن در هاور',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-ptags' => '--ard-ptag-ink-hover:{{VALUE}};',
				),
				'condition' => array( 'link' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => 'فاصله چیپ‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 2, 'max' => 24 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-ptags__list' => 'gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'chip_radius',
			array(
				'label'      => 'گردی چیپ',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 30 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-ptags' => '--ard-ptag-radius:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'chip_typo',
				'label'    => 'قلم چیپ',
				'selector' => '{{WRAPPER}} .ard-ptags__chip',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$terms = $this->terms( $s );
		$empty = trim( (string) $s['empty_text'] );

		// بدون برچسب و بدون متن جایگزین، عنوانِ تنها بی‌معناست.
		if ( ! $terms && '' === $empty ) {
			$this->render_empty( 'این نوشته برچسبی ندارد.' );

			return;
		}

		$link = 'yes' === $s['link'];
		?>
		<section class="ard-ptags">
			<?php if ( '' !== trim( (string) $s['title'] ) ) : ?>
				<?php $tag = self::tag( $s['title_tag'], 'h3' ); ?>
				<<?php echo esc_html( $tag ); ?> class="ard-ptags__title"><?php echo esc_html( $s['title'] ); ?></<?php echo esc_html( $tag ); ?>>
			<?php endif; ?>

			<?php if ( ! $terms ) : ?>
				<p class="ard-ptags__empty"><?php echo esc_html( $empty ); ?></p>
			<?php else : ?>
				<ul class="ard-ptags__list">
					<?php foreach ( $terms as $term ) : ?>
						<?php
						$url = '';

						if ( $link ) {
							$url = get_term_link( $term );
							$url = is_wp_error( $url ) ? '' : (string) $url;
						}

						$chip = '' !== $url ? 'a' : 'span';
						?>
						<li class="ard-ptags__item">
							<<?php echo esc_html( $chip ); ?> class="ard-ptags__chip"<?php echo '' !== $url ? ' href="' . esc_url( $url ) . '" rel="tag"' : ''; ?>>
								<span class="ard-ptags__name"><?php echo esc_html( $term->name ); ?></span>

								<?php if ( 'yes' === $s['show_count'] ) : ?>
									<span class="ard-ptags__count"><?php echo esc_html( Ardal_Helpers::fa_digits( (int) $term->count ) ); ?></span>
								<?php endif; ?>
							</<?php echo esc_html( $chip ); ?>>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</section>
		<?php
	}

	/**
	 * تاکسونومی‌های عمومی، برای سلکت تنظیمات.
	 *
	 * @return array
	 */
	private function taxonomy_options() {
		$out = array();

		foreach ( get_taxonomies( array( 'public' => true ), 'objects' ) as $taxonomy ) {
			if ( 'product_visibility' === $taxonomy->name ) {
				continue;
			}

			$out[ $taxonomy->name ] = $taxonomy->labels->singular_name
				? $taxonomy->labels->singular_name
				: $taxonomy->name;
		}

		return $out ? $out : array( 'post_tag' => 'برچسب' );
	}

	/**
	 * ترم‌های نوشته، مرتب و محدودشده.
	 *
	 * @param array $s
	 * @return WP_Term[]
	 */
	private function terms( $s ) {
		$post_id = (int) $s['post_id'] > 0 ? (int) $s['post_id'] : (int) get_the_ID();

		if ( $post_id < 1 ) {
			return array();
		}

		$taxonomy = sanitize_key( (string) $s['taxonomy'] );

		if ( '' === $taxonomy || ! taxonomy_exists( $taxonomy ) ) {
			$taxonomy = 'post_tag';
		}

		$terms = get_the_terms( $post_id, $taxonomy );

		if ( is_wp_error( $terms ) || ! is_array( $terms ) || ! $terms ) {
			return array();
		}

		// «بدون دسته‌بندی» همان‌جور که از آرشیو حذف شده، اینجا هم نباید بیاید.
		$skip = class_exists( 'Ardal_Archive' ) ? Ardal_Archive::excluded_terms( $taxonomy ) : array();

		if ( $skip ) {
			$terms = array_values(
				array_filter(
					$terms,
					static function ( $term ) use ( $skip ) {
						return ! in_array( (int) $term->term_id, $skip, true );
					}
				)
			);

			if ( ! $terms ) {
				return array();
			}
		}

		if ( 'count' === $s['orderby'] ) {
			usort(
				$terms,
				static function ( $a, $b ) {
					return (int) $b->count <=> (int) $a->count;
				}
			);
		} elseif ( 'name' === $s['orderby'] ) {
			usort(
				$terms,
				static function ( $a, $b ) {
					// مقایسهٔ فارسی با strcmp درست درنمی‌آید و strcoll به لوکال وابسته است.
					return strnatcasecmp( (string) $a->name, (string) $b->name );
				}
			);
		}

		$limit = max( 0, (int) $s['limit'] );

		return ( $limit > 0 && count( $terms ) > $limit ) ? array_slice( $terms, 0, $limit ) : $terms;
	}
}
