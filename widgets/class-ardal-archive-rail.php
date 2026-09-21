<?php
/**
 * ویجت ریل دسته‌بندی آرشیو — دایره‌های تصویری بالای فهرست محصولات.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Archive_Rail extends Ardal_Archive_Widget_Base {

	public function get_name() {
		return 'ardal-archive-rail';
	}

	public function get_title() {
		return 'اردال — ریل دسته‌بندی آرشیو';
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	protected function register_controls() {

		/* ------------------------------------------------ محتوا */

		$this->start_controls_section(
			'section_rail',
			array( 'label' => 'دسته‌ها' )
		);

		$this->add_archive_title_controls( 'دسته‌بندی محصولات' );

		$this->add_control(
			'taxonomy',
			array(
				'label'   => 'تاکسونومی',
				'type'    => Controls_Manager::SELECT,
				'default' => 'product_cat',
				'options' => Ardal_Archive::taxonomy_options(),
			)
		);

		$this->add_control(
			'source',
			array(
				'label'       => 'کدام ترم‌ها؟',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'auto',
				'options'     => array(
					'auto'     => 'هوشمند (زیرمجموعه‌های دسته جاری، وگرنه دسته‌های اصلی)',
					'top'      => 'فقط ترم‌های سطح اول',
					'children' => 'زیرمجموعه‌های یک دسته مشخص',
					'all'      => 'همه ترم‌ها',
					'manual'   => 'انتخاب دستی',
				),
				'label_block' => true,
			)
		);

		$this->add_control(
			'parent',
			array(
				'label'       => 'دسته والد',
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->term_options(),
				'label_block' => true,
				'condition'   => array( 'source' => 'children' ),
			)
		);

		$this->add_control(
			'terms',
			array(
				'label'       => 'ترم‌ها',
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->term_options(),
				'default'     => array(),
				'label_block' => true,
				'description' => 'ترتیب انتخاب، ترتیب نمایش است.',
				'condition'   => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'     => 'حداکثر تعداد',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 40,
				'default'   => 12,
				'condition' => array( 'source!' => 'manual' ),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'     => 'ترتیب',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'name',
				'options'   => array(
					'name'  => 'الفبا',
					'count' => 'تعداد محصول',
					'order' => 'ترتیب دلخواه (menu_order)',
					'id'    => 'شناسه',
				),
				'condition' => array( 'source!' => 'manual' ),
			)
		);

		$this->add_control(
			'hide_empty',
			array(
				'label'        => 'پنهان‌کردن ترم‌های بدون محصول',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => 'بله',
				'label_off'    => 'خیر',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'dynamic',
			array(
				'label'        => 'هم‌گام با نتیجهٔ صفحه',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => 'بله',
				'label_off'    => 'خیر',
				'return_value' => 'yes',
				'description'  => 'فقط ترم‌هایی می‌مانند که در نتیجهٔ همین صفحه (با فیلترها و جست‌وجوی فعلی) محصول دارند، و تعدادها هم همان نتیجه را نشان می‌دهند. اگر چیزی نماند، کل ریل چاپ نمی‌شود.',
			)
		);

		$this->add_control(
			'dynamic_self',
			array(
				'label'       => 'انتخاب خود این ریل هم لحاظ شود',
				'type'        => Controls_Manager::SWITCHER,
				'default'     => '',
				'return_value' => 'yes',
				'description' => 'پیش‌فرض خاموش است: وقتی کاربر یک دسته را انتخاب می‌کند، بقیهٔ دسته‌ها باید سر جایشان بمانند تا بشود بینشان جابه‌جا شد. روشن که باشد، ریل هم مثل بقیهٔ فیلترها جمع می‌شود.',
				'condition'   => array( 'dynamic' => 'yes' ),
			)
		);

		$this->add_control(
			'show_count',
			array(
				'label'        => 'نمایش تعداد محصول',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_all',
			array(
				'label'        => 'افزودن آیتم «همه»',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'description'  => 'یک دایره در ابتدای ریل که به کل فروشگاه لینک می‌شود.',
			)
		);

		$this->add_control(
			'all_label',
			array(
				'label'     => 'متن آیتم «همه»',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'همه محصولات',
				'condition' => array( 'show_all' => 'yes' ),
			)
		);

		$this->add_control(
			'fallback',
			array(
				'label'       => 'تصویر پیش‌فرض',
				'type'        => Controls_Manager::MEDIA,
				'description' => 'وقتی ترم تصویر ندارد.',
			)
		);

		$this->add_control(
			'link_mode',
			array(
				'label'       => 'رفتار کلیک',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'term',
				'options'     => array(
					'term'   => 'رفتن به آرشیو همان ترم',
					'filter' => 'اعمال به‌عنوان فیلتر روی همین صفحه',
				),
				'label_block' => true,
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ چیدمان */

		$this->start_controls_section(
			'section_rail_layout',
			array( 'label' => 'چیدمان' )
		);

		$this->add_control(
			'boxed',
			array(
				'label'        => 'قاب سفید دور ریل',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => 'بله',
				'label_off'    => 'خیر',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'shape',
			array(
				'label'   => 'شکل تصویر',
				'type'    => Controls_Manager::SELECT,
				'default' => 'circle',
				'options' => array(
					'circle'   => 'دایره',
					'squircle' => 'مربع گردشده',
				),
			)
		);

		$this->add_control(
			'ring',
			array(
				'label'   => 'حلقه دور تصویر',
				'type'    => Controls_Manager::SELECT,
				'default' => 'gradient',
				'options' => array(
					'gradient' => 'گرادیان برند',
					'solid'    => 'یک‌رنگ',
					'soft'     => 'زمینه ملایم',
					'none'     => 'بدون حلقه',
				),
			)
		);

		$this->add_control(
			'arrows',
			array(
				'label'        => 'دکمه‌های چپ و راست',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_responsive_control(
			'size',
			array(
				'label'      => 'اندازه دایره',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 48, 'max' => 180 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 112 ),
				'tablet_default' => array( 'unit' => 'px', 'size' => 86 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 74 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-rail' => '--ard-rail-size:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => 'فاصله بین آیتم‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 4, 'max' => 60 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 26 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 12 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-rail__track' => 'gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'ring_width',
			array(
				'label'      => 'ضخامت حلقه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 12 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 4 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-rail' => '--ard-rail-ring:{{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'ring!' => 'none' ),
			)
		);

		$this->add_control(
			'inner_width',
			array(
				'label'      => 'ضخامت حاشیه سفید داخلی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 14 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 6 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-rail' => '--ard-rail-inner:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ استایل */

		$this->add_archive_title_style();

		$this->start_controls_section(
			'section_style_rail',
			array(
				'label' => 'آیتم‌ها',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'ring_from',
			array(
				'label'     => 'رنگ حلقه (شروع)',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-rail' => '--ard-rail-from:{{VALUE}};',
				),
				'condition' => array( 'ring!' => 'none' ),
			)
		);

		$this->add_control(
			'ring_to',
			array(
				'label'     => 'رنگ حلقه (پایان)',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-rail' => '--ard-rail-to:{{VALUE}};',
				),
				'condition' => array( 'ring' => 'gradient' ),
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => 'رنگ نام دسته',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-rail__label' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'label_active',
			array(
				'label'     => 'رنگ دسته جاری',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-rail__item.is-current .ard-rail__label' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typo',
				'selector' => '{{WRAPPER}} .ard-rail__label',
			)
		);

		$this->add_control(
			'count_color',
			array(
				'label'     => 'رنگ تعداد',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-rail__count' => 'color:{{VALUE}};',
				),
				'condition' => array( 'show_count' => 'yes' ),
			)
		);

		$this->add_control(
			'arrow_bg',
			array(
				'label'     => 'زمینه دکمه‌های حرکت',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-rail__nav' => 'background:{{VALUE}};',
				),
				'condition' => array( 'arrows' => 'yes' ),
			)
		);

		$this->add_control(
			'arrow_color',
			array(
				'label'     => 'رنگ آیکون دکمه‌ها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-rail__nav' => 'color:{{VALUE}};',
				),
				'condition' => array( 'arrows' => 'yes' ),
			)
		);

		$this->end_controls_section();

		$this->add_box_controls( '.ard-rail--boxed', 'rail_box', 'قاب', array( 'top' => 22, 'right' => 24, 'bottom' => 20, 'left' => 24 ) );
	}

	/**
	 * گزینه‌های ترم برای کنترل‌های SELECT2.
	 *
	 * @return array
	 */
	protected function term_options() {
		if ( ! Ardal_Helpers::has_woo() ) {
			return array();
		}

		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'number'     => 300,
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

	/**
	 * ترم‌های نهایی ریل.
	 *
	 * @param array $s
	 * @return WP_Term[]
	 */
	protected function rail_terms( $s ) {
		$taxonomy = taxonomy_exists( $s['taxonomy'] ) ? $s['taxonomy'] : 'product_cat';
		$limit    = max( 1, (int) $s['limit'] );

		$dynamic = 'yes' === $s['dynamic'];

		$args = array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => 'yes' === $s['hide_empty'],
			/*
			 * در حالت هم‌گام، سقف بعد از غربال اعمال می‌شود نه قبلش؛ وگرنه
			 * ممکن بود ۱۲ ترم اول همه بی‌محصول باشند و ریل خالی بماند در حالی
			 * که ترم‌های بعدی محصول داشتند.
			 */
			'number'     => $dynamic ? 0 : $limit,
		);

		switch ( $s['orderby'] ) {
			case 'count':
				$args['orderby'] = 'count';
				$args['order']   = 'DESC';
				break;

			case 'order':
				$args['orderby'] = 'menu_order';
				break;

			case 'id':
				$args['orderby'] = 'term_id';
				break;

			default:
				$args['orderby'] = 'name';
		}

		switch ( $s['source'] ) {
			case 'manual':
				$ids = array_filter( array_map( 'absint', (array) $s['terms'] ) );

				if ( ! $ids ) {
					return array();
				}

				$args['include'] = $ids;
				$args['orderby'] = 'include';
				$args['number']  = 0;
				unset( $args['order'] );
				break;

			case 'children':
				$args['parent'] = (int) $s['parent'];
				break;

			case 'top':
				$args['parent'] = 0;
				break;

			case 'all':
				break;

			default:
				// هوشمند: داخل یک دسته، زیرمجموعه‌هایش؛ بیرون از آن، سطح اول.
				$current = get_queried_object();
				$parent  = ( $current instanceof WP_Term && $current->taxonomy === $taxonomy ) ? (int) $current->term_id : 0;
				$kids    = get_terms( array_merge( $args, array( 'parent' => $parent, 'fields' => 'ids' ) ) );

				$args['parent'] = ( ! is_wp_error( $kids ) && $kids ) ? $parent : 0;
		}

		$terms = get_terms( $args );

		if ( is_wp_error( $terms ) || ! $terms ) {
			return array();
		}

		if ( ! $dynamic ) {
			return $terms;
		}

		$terms = $this->narrow_to_query( $terms, $taxonomy, $s );

		// انتخاب دستی سقف ندارد؛ بقیه دارند.
		return 'manual' === $s['source'] ? $terms : array_slice( $terms, 0, $limit );
	}

	/**
	 * ترم‌ها را به آن‌هایی که در نتیجهٔ جاری محصول دارند محدود می‌کند.
	 *
	 * تعداد هر ترم هم با همان شمارش جایگزین می‌شود، وگرنه روی دایره عددی
	 * می‌نشست که با چیزی که کاربر بعد از کلیک می‌بیند نمی‌خواند.
	 *
	 * @param WP_Term[] $terms
	 * @param string    $taxonomy
	 * @param array     $s
	 * @return WP_Term[]
	 */
	protected function narrow_to_query( $terms, $taxonomy, $s ) {
		$counts = Ardal_Archive::counts_for( $taxonomy, 'yes' !== $s['dynamic_self'] );
		$out    = array();

		foreach ( $terms as $term ) {
			$count = isset( $counts[ $term->term_id ] ) ? (int) $counts[ $term->term_id ] : 0;

			if ( $count < 1 ) {
				continue;
			}

			// کلون می‌گیریم تا کش ترم‌های وردپرس با عدد صفحه‌ایِ ما آلوده نشود.
			$copy        = clone $term;
			$copy->count = $count;
			$out[]       = $copy;
		}

		return $out;
	}

	/**
	 * تصویر یک ترم.
	 *
	 * @param WP_Term $term
	 * @param array   $s
	 * @return string
	 */
	protected function term_image( $term, $s ) {
		$id = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );

		if ( $id ) {
			$src = wp_get_attachment_image_src( $id, 'woocommerce_thumbnail' );

			if ( $src ) {
				return $src[0];
			}
		}

		if ( ! empty( $s['fallback']['url'] ) ) {
			return $s['fallback']['url'];
		}

		return Ardal_Helpers::has_woo() ? wc_placeholder_img_src( 'woocommerce_thumbnail' ) : '';
	}

	/**
	 * لینک یک ترم بسته به رفتار کلیک.
	 *
	 * @param WP_Term $term
	 * @param array   $s
	 * @return string
	 */
	protected function term_link( $term, $s ) {
		if ( 'filter' === $s['link_mode'] ) {
			$key = Ardal_Archive::key_for_taxonomy( $term->taxonomy );

			return Ardal_Archive::url_with( array( Ardal_Archive::PREFIX . $key => array( $term->slug ) ) );
		}

		$link = get_term_link( $term );

		return is_wp_error( $link ) ? '#' : $link;
	}

	protected function render() {
		if ( $this->require_woo() ) {
			return;
		}

		$s     = $this->get_settings_for_display();
		$terms = $this->rail_terms( $s );

		/*
		 * در حالت هم‌گام، خالی بودن یعنی «این صفحه دسته‌ای برای نشان دادن
		 * ندارد» — پس حتی آیتم «همه» هم چاپ نمی‌شود؛ یک ریل تک‌دایره‌ای زیر
		 * نتیجهٔ خالی، فقط جای خالی درست می‌کند.
		 */
		if ( ! $terms && ( 'yes' === $s['dynamic'] || 'yes' !== $s['show_all'] ) ) {
			$this->render_empty( 'ترمی برای نمایش پیدا نشد؛ منبع یا تاکسونومی را بررسی کنید.' );

			return;
		}

		$current = get_queried_object();
		$current = $current instanceof WP_Term ? (int) $current->term_id : 0;
		$chosen  = Ardal_Archive::selected( Ardal_Archive::key_for_taxonomy( $s['taxonomy'] ) );

		$classes = array(
			'ard-rail',
			'ard-rail--' . ( 'squircle' === $s['shape'] ? 'squircle' : 'circle' ),
			'ard-rail--ring-' . $s['ring'],
		);

		if ( 'yes' === $s['boxed'] ) {
			$classes[] = 'ard-rail--boxed';
		}

		printf( '<div class="%s">', esc_attr( implode( ' ', $classes ) ) );

		$this->render_archive_title( $s );

		echo '<div class="ard-rail__row">';

		if ( 'yes' === $s['arrows'] ) {
			printf(
				'<button type="button" class="ard-rail__nav ard-rail__nav--prev" data-ard-rail="1" aria-label="قبلی">%s</button>',
				Ardal_Archive::icon( 'chev-r', 18, 2 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		echo '<ul class="ard-rail__track" data-ard-rail-track>';

		if ( 'yes' === $s['show_all'] ) {
			$this->render_item(
				array(
					'label'   => $s['all_label'],
					'url'     => Ardal_Archive::base_url(),
					'image'   => ! empty( $s['fallback']['url'] ) ? $s['fallback']['url'] : '',
					'count'   => 0,
					'current' => ! $current && ! $chosen,
					'icon'    => true,
				),
				$s
			);
		}

		foreach ( $terms as $term ) {
			$this->render_item(
				array(
					'label'   => $term->name,
					'url'     => $this->term_link( $term, $s ),
					'image'   => $this->term_image( $term, $s ),
					'count'   => (int) $term->count,
					'current' => ( $current === (int) $term->term_id ) || in_array( $term->slug, $chosen, true ),
					'icon'    => false,
				),
				$s
			);
		}

		echo '</ul>';

		if ( 'yes' === $s['arrows'] ) {
			printf(
				'<button type="button" class="ard-rail__nav ard-rail__nav--next" data-ard-rail="-1" aria-label="بعدی">%s</button>',
				Ardal_Archive::icon( 'chev-l', 18, 2 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		echo '</div></div>';
	}

	/**
	 * یک آیتم ریل.
	 *
	 * @param array $item
	 * @param array $s
	 */
	protected function render_item( $item, $s ) {
		printf( '<li class="ard-rail__item%s">', $item['current'] ? ' is-current' : '' );
		printf( '<a href="%s">', esc_url( $item['url'] ) );

		echo '<span class="ard-rail__ring">';

		if ( '' !== $item['image'] ) {
			printf(
				'<span class="ard-rail__img" style="background-image:url(%s)"></span>',
				esc_url( $item['image'] )
			);
		} else {
			printf(
				'<span class="ard-rail__img ard-rail__img--icon">%s</span>',
				Ardal_Archive::icon( 'spark', 26, 1.6 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		echo '</span>';

		printf( '<span class="ard-rail__label">%s</span>', esc_html( $item['label'] ) );

		if ( 'yes' === $s['show_count'] && $item['count'] > 0 ) {
			printf(
				'<span class="ard-rail__count">%s کالا</span>',
				esc_html( Ardal_Helpers::fa_digits( $item['count'] ) )
			);
		}

		echo '</a></li>';
	}
}
