<?php
/**
 * کلاس پایه ویجت‌های اردال — کنترل‌ها و رندرهای مشترک.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

abstract class Ardal_Widget_Base extends Widget_Base {

	/**
	 * @return array
	 */
	public function get_categories() {
		return array( 'ardal' );
	}

	/**
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'ardal' );
	}

	/**
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'ardal' );
	}

	/**
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-blockquote';
	}

	/**
	 * @return array
	 */
	public function get_custom_help_url() {
		return '';
	}

	/**
	 * بخش «عنوان بخش» که در چند ویجت تکرار می‌شود.
	 *
	 * @param string $default_title
	 * @param string $accent پیش‌فرض رنگ خط کنار عنوان: accent یا primary.
	 */
	protected function add_heading_controls( $default_title, $accent = 'accent' ) {
		$this->start_controls_section(
			'section_heading',
			array( 'label' => 'عنوان بخش' )
		);

		$this->add_control(
			'show_heading',
			array(
				'label'        => 'نمایش عنوان',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => 'بله',
				'label_off'    => 'خیر',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'     => 'عنوان',
				'type'      => Controls_Manager::TEXT,
				'default'   => $default_title,
				'condition' => array( 'show_heading' => 'yes' ),
			)
		);

		$this->add_control(
			'subheading',
			array(
				'label'       => 'زیرعنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => 'اختیاری — زیر عنوان اصلی نمایش داده می‌شود.',
				'condition'   => array( 'show_heading' => 'yes' ),
			)
		);

		$this->add_control(
			'heading_rule',
			array(
				'label'     => 'خط کنار عنوان',
				'type'      => Controls_Manager::SELECT,
				'default'   => $accent,
				'options'   => array(
					'accent'  => 'سبز',
					'primary' => 'آبی',
					'none'    => 'بدون خط',
				),
				'condition' => array( 'show_heading' => 'yes' ),
			)
		);

		$this->add_control(
			'heading_align',
			array(
				'label'     => 'چیدمان عنوان',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'start',
				'options'   => array(
					'start'  => 'راست‌چین',
					'center' => 'وسط‌چین',
				),
				'condition' => array( 'show_heading' => 'yes' ),
			)
		);

		$this->add_control(
			'more_text',
			array(
				'label'     => 'متن لینک «مشاهده همه»',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'مشاهده همه',
				'condition' => array(
					'show_heading'  => 'yes',
					'heading_align' => 'start',
				),
			)
		);

		$this->add_control(
			'more_link',
			array(
				'label'     => 'لینک «مشاهده همه»',
				'type'      => Controls_Manager::URL,
				'default'   => array( 'url' => '' ),
				'condition' => array(
					'show_heading'  => 'yes',
					'heading_align' => 'start',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * رندر عنوان بخش.
	 */
	protected function render_heading() {
		$s = $this->get_settings_for_display();

		if ( 'yes' !== $s['show_heading'] || '' === trim( (string) $s['heading'] ) ) {
			return;
		}

		$center = 'center' === $s['heading_align'];
		$rule   = $s['heading_rule'];

		printf( '<div class="ard-shead%s">', $center ? ' ard-shead--center' : '' );

		if ( ! $center && 'none' !== $rule ) {
			printf( '<span class="ard-shead__rule ard-shead__rule--%s"></span>', esc_attr( $rule ) );
		}

		echo '<div class="ard-shead__text">';
		printf( '<h2 class="ard-shead__title">%s</h2>', esc_html( $s['heading'] ) );

		if ( '' !== trim( (string) $s['subheading'] ) ) {
			printf( '<p class="ard-shead__sub">%s</p>', esc_html( $s['subheading'] ) );
		}

		echo '</div>';

		if ( ! $center && ! empty( $s['more_link']['url'] ) && '' !== trim( (string) $s['more_text'] ) ) {
			// آیکون SVG به‌جای کاراکتر «›»: آن کاراکتر bidi-mirrored است و
			// جهتش را از راست‌چینی متن می‌گیرد، که قابل اتکا نیست.
			printf(
				'<a class="ard-shead__more"%s>%s %s</a>',
				Ardal_Helpers::link_attrs( $s['more_link'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خروجی داخل تابع escape شده.
				esc_html( $s['more_text'] ),
				Ardal_Helpers::icon( 'chevron-left', 15 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		echo '</div>';
	}

	/**
	 * کنترل‌های منبع داده محصول (دستی یا ووکامرس).
	 *
	 * @param int  $default_limit
	 * @param bool $with_archive آیا گزینهٔ «کوئری آرشیو جاری» و سازگاری با
	 *                           فیلترهای اردال هم اضافه شود.
	 */
	protected function add_source_controls( $default_limit = 5, $with_archive = false ) {
		$options = array( 'manual' => 'دستی (وارد کردن در لیست زیر)' );

		if ( Ardal_Helpers::has_woo() ) {
			if ( $with_archive ) {
				$options['archive'] = 'کوئری آرشیو جاری (با فیلترهای اردال)';
			}

			$options['recent']   = 'ووکامرس — جدیدترین';
			$options['best']     = 'ووکامرس — پرفروش‌ترین';
			$options['sale']     = 'ووکامرس — تخفیف‌دار';
			$options['featured'] = 'ووکامرس — ویژه';
			$options['rating']   = 'ووکامرس — پرامتیازترین';
		} else {
			$options['manual'] = 'دستی (ووکامرس فعال نیست)';
		}

		$this->add_control(
			'source',
			array(
				'label'       => 'منبع محصولات',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'manual',
				'options'     => $options,
				'label_block' => true,
			)
		);

		if ( ! Ardal_Helpers::has_woo() ) {
			return;
		}

		if ( $with_archive ) {
			$this->add_control(
				'archive_note',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => 'در این حالت، محصولات از همان کوئری صفحهٔ فروشگاه/دسته خوانده می‌شوند؛ یعنی فیلترها، جست‌وجو و مرتب‌سازی ویجت‌های آرشیو روی این شبکه هم اثر می‌گذارند.',
					'content_classes' => 'elementor-descriptor',
					'condition'       => array( 'source' => 'archive' ),
				)
			);

			$this->add_control(
				'archive_limit',
				array(
					'label'       => 'تعداد کل محصولات',
					'type'        => Controls_Manager::NUMBER,
					'min'         => 0,
					'max'         => 96,
					'default'     => 0,
					'description' => 'صفر یعنی هرچه در صفحهٔ جاری آرشیو هست.',
					'condition'   => array( 'source' => 'archive' ),
				)
			);
		}

		$this->add_control(
			'wc_category',
			array(
				'label'       => 'محدود به دسته‌بندی',
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => Ardal_Helpers::product_category_options(),
				'default'     => array(),
				'label_block' => true,
				'condition'   => array( 'source!' => array( 'manual', 'archive' ) ),
			)
		);

		$this->add_control(
			'wc_limit',
			array(
				'label'     => 'تعداد کل محصولات',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 48,
				'default'   => $default_limit,
				'condition' => array( 'source!' => array( 'manual', 'archive' ) ),
			)
		);

		if ( ! $with_archive ) {
			return;
		}

		$this->add_control(
			'respect_filters',
			array(
				'label'        => 'اعمال فیلترهای اردال روی همین کوئری',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => 'بله',
				'label_off'    => 'خیر',
				'return_value' => 'yes',
				'description'  => 'کوئری خودِ ویجت دست‌نخورده می‌ماند، فقط فیلترهای فعالِ آدرس رویش اعمال می‌شوند.',
				'condition'    => array( 'source!' => array( 'manual', 'archive' ) ),
			)
		);
	}

	/**
	 * محصولات صفحهٔ آرشیو جاری.
	 *
	 * روی خودِ آرشیو، کوئری اصلی خوانده می‌شود (پس صفحه‌بندی و فیلترها همان‌اند)؛
	 * بیرون از آرشیو — مثلاً پیش‌نمایش ویرایشگر — یک کوئری نمونه با همان فیلترها.
	 *
	 * @param array $settings
	 * @return array
	 */
	protected function get_archive_items( $settings ) {
		$limit = isset( $settings['archive_limit'] ) ? (int) $settings['archive_limit'] : 0;
		$main  = isset( $GLOBALS['wp_query'] ) ? $GLOBALS['wp_query'] : null;

		if ( $main instanceof WP_Query && $main->posts ) {
			$is_archive = $main->is_post_type_archive( 'product' )
				|| $main->is_tax( get_object_taxonomies( 'product' ) )
				|| 'product_query' === $main->get( 'wc_query' );

			if ( $is_archive ) {
				return Ardal_Helpers::cards_from_posts( $main->posts, $limit );
			}
		}

		return Ardal_Helpers::query_products_filtered(
			array(
				'source'  => 'recent',
				'limit'   => $limit > 0 ? $limit : 12,
				'filters' => true,
			)
		);
	}

	/**
	 * لیست نهایی آیتم‌های محصول با توجه به منبع انتخاب‌شده.
	 *
	 * @param array  $settings
	 * @param string $repeater_key
	 * @return array
	 */
	protected function get_product_items( $settings, $repeater_key = 'items' ) {
		$source = isset( $settings['source'] ) ? $settings['source'] : 'manual';

		if ( 'manual' === $source || ! Ardal_Helpers::has_woo() ) {
			return isset( $settings[ $repeater_key ] ) && is_array( $settings[ $repeater_key ] )
				? $settings[ $repeater_key ]
				: array();
		}

		if ( 'archive' === $source ) {
			return $this->get_archive_items( $settings );
		}

		$categories = isset( $settings['wc_category'] ) ? (array) $settings['wc_category'] : array();
		$limit      = isset( $settings['wc_limit'] ) ? (int) $settings['wc_limit'] : 5;

		// مسیر فیلتردار WP_Query است، چون wc_get_products شرط‌های دلخواه tax را
		// نمی‌پذیرد. تا وقتی فیلتری خواسته نشده، همان مسیر سبک قبلی می‌ماند.
		if ( 'yes' === ( $settings['respect_filters'] ?? '' ) ) {
			return Ardal_Helpers::query_products_filtered(
				array(
					'source'   => $source,
					'limit'    => $limit,
					'category' => implode( ',', $categories ),
					'filters'  => true,
				)
			);
		}

		return Ardal_Helpers::query_products(
			array(
				'source'   => $source,
				'limit'    => $limit,
				'category' => implode( ',', $categories ),
			)
		);
	}

	/**
	 * کارت محصول استاندارد (سبک دیزاین ۱a — با دکمه گرد سبز).
	 *
	 * @param array $item
	 * @param array $opts
	 */
	protected function render_product_card( $item, $opts = array() ) {
		$opts = wp_parse_args(
			$opts,
			array(
				'variant'     => 'framed',
				'show_rating' => true,
				'show_off'    => true,
				'show_fab'    => true,
				'show_button' => false,
				'button_text' => 'افزودن به سبد',
				'placeholder' => '۸۰۰×۸۰۰',
				'image_h'     => '',
				'notch'       => null,
				'link_mode'   => 'button',
				'button_action' => 'add',
			)
		);

		// بریدگی گوشه فقط وقتی معنا دارد که دکمهٔ گرد روی همان گوشه بنشیند.
		if ( null === $opts['notch'] ) {
			$opts['notch'] = 'framed' === $opts['variant'] && $opts['show_fab'];
		}

		$title  = isset( $item['title'] ) ? $item['title'] : '';
		$price  = isset( $item['price'] ) ? Ardal_Helpers::price( $item['price'] ) : '';
		$old    = isset( $item['old_price'] ) ? Ardal_Helpers::price( $item['old_price'] ) : '';
		$off    = isset( $item['off'] ) && '' !== $item['off']
			? Ardal_Helpers::fa_digits( $item['off'] )
			: Ardal_Helpers::discount( $old, $price );
		$link   = isset( $item['link'] ) ? $item['link'] : array( 'url' => '' );
		$rating = isset( $item['rating'] ) ? (float) $item['rating'] : 0;
		$count  = isset( $item['count'] ) ? (int) $item['count'] : 0;
		$add    = isset( $item['add_url'] ) ? $item['add_url'] : array( 'url' => '' );

		$style = '' !== $opts['image_h'] ? ' style="--ard-card-img-h:' . esc_attr( $opts['image_h'] ) . 'px"' : '';

		$classes = array( 'ard-pcard', 'ard-pcard--' . $opts['variant'] );

		if ( $opts['notch'] ) {
			$classes[] = 'ard-pcard--notch';
		}

		// «کل کارت لینک» با لینکِ کشیدهٔ روی عنوان ساخته می‌شود، نه با تگ a دور کل
		// کارت؛ چون دکمهٔ داخل کارت خودش لینک است و a تودرتو HTML نامعتبر می‌سازد.
		$box_link = 'box' === $opts['link_mode'] && ! empty( $link['url'] );

		if ( $box_link ) {
			$classes[] = 'ard-pcard--boxlink';
		}

		printf( '<article class="%s"%s>', esc_attr( implode( ' ', $classes ) ), $style ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<div class="ard-pcard__box">';

		echo '<div class="ard-pcard__media">';
		echo Ardal_Helpers::image( isset( $item['image'] ) ? $item['image'] : array(), $opts['placeholder'], $title, 'medium_large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $opts['show_off'] && '' !== $off ) {
			printf( '<span class="ard-badge ard-badge--sale">%s</span>', esc_html( $off ) );
		}

		if ( ! empty( $item['badge'] ) ) {
			printf( '<span class="ard-badge ard-badge--rank">%s</span>', esc_html( Ardal_Helpers::fa_digits( $item['badge'] ) ) );
		}

		echo '</div>';

		if ( '' !== $title || $box_link ) {
			if ( ! empty( $link['url'] ) ) {
				printf(
					'<h3 class="ard-pcard__title"><a%s>%s</a></h3>',
					Ardal_Helpers::link_attrs( $link ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					esc_html( $title )
				);
			} else {
				printf( '<h3 class="ard-pcard__title">%s</h3>', esc_html( $title ) );
			}
		}

		if ( $opts['show_rating'] && $rating > 0 ) {
			echo '<div class="ard-pcard__rate">';
			echo Ardal_Helpers::stars( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			if ( $count > 0 ) {
				printf( '<span>(%s)</span>', esc_html( Ardal_Helpers::fa_digits( $count ) ) );
			}

			echo '</div>';
		}

		echo '<div class="ard-pcard__prices">';

		if ( '' !== $old ) {
			printf( '<del class="ard-pcard__old">%s</del>', esc_html( $old ) );
		}

		if ( '' !== $price ) {
			printf(
				'<div class="ard-pcard__price">%s %s</div>',
				esc_html( $price ),
				Ardal_Currency::unit() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خروجی ثابت و امن.
			);
		}

		echo '</div>';

		if ( $opts['show_button'] ) {
			$this->render_card_action( $item, $opts, 'ard-pcard__btn', esc_html( $opts['button_text'] ), '' );
		}

		echo '</div>';

		if ( $opts['show_fab'] ) {
			$label = 'add' === $opts['button_action']
				? 'افزودن ' . $title . ' به سبد خرید'
				: 'مشاهده ' . $title;

			$this->render_card_action( $item, $opts, 'ard-pcard__fab', '<span aria-hidden="true">+</span>', $label );
		}

		echo '</article>';
	}

	/**
	 * دکمهٔ کنش کارت (دکمه متنی یا دکمهٔ گرد).
	 *
	 * بسته به button_action یا به سبد اضافه می‌کند یا به صفحهٔ محصول می‌برد.
	 * برای محصول ووکامرس، کلاس‌ها و data-attribute های خود ووکامرس هم چاپ می‌شوند
	 * تا افزودن به سبد بدون رفرش صفحه (ایجکس) کار کند؛ اگر اسکریپت ووکامرس در آن
	 * صفحه نباشد، href معمولی جواب می‌دهد و فقط صفحه رفرش می‌شود.
	 *
	 * @param array  $item
	 * @param array  $opts
	 * @param string $class
	 * @param string $inner  HTML داخل دکمه (از قبل escape شده).
	 * @param string $label  متن aria-label، خالی یعنی لازم نیست.
	 */
	private function render_card_action( $item, $opts, $class, $inner, $label ) {
		$link = isset( $item['link'] ) ? $item['link'] : array( 'url' => '' );
		$add  = isset( $item['add_url'] ) ? $item['add_url'] : array( 'url' => '' );

		$to_cart = 'add' === $opts['button_action'] && ! empty( $add['url'] );
		$target  = $to_cart ? $add : $link;

		$attrs   = Ardal_Helpers::link_attrs( $target );
		$classes = array( $class );

		if ( $to_cart && ! empty( $item['product_id'] ) ) {
			$product = wc_get_product( (int) $item['product_id'] );

			if ( $product ) {
				echo Ardal_Helpers::woocommerce_add_to_cart_link(
					$product,
					$classes,
					wp_strip_all_tags( $inner ),
					array( 'data-ard-add-to-cart' => '' !== $label ? $label : 'true' )
				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce API output.

				return;
			}

			$classes[] = 'add_to_cart_button';

			if ( ! empty( $item['ajax_add'] ) && ! Ardal_Assets::is_woocommerce_asset_disabled( 'disable_wc_add_to_cart_js' ) ) {
				$classes[] = 'ajax_add_to_cart';

				// اسکریپت ووکامرس در فوتر ثبت شده؛ همین‌جا صدا زدنش کافی است.
				wp_enqueue_script( 'wc-add-to-cart' );
			}

			$attrs .= sprintf(
				' data-product_id="%d" data-quantity="1" rel="nofollow"',
				(int) $item['product_id']
			);
		}

		printf(
			'<%1$s class="%2$s"%3$s%4$s>%5$s</%1$s>',
			esc_html( Ardal_Helpers::link_tag( $target ) ),
			esc_attr( implode( ' ', $classes ) ),
			$attrs, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- داخل link_attrs escape شده.
			'' !== $label ? ' aria-label="' . esc_attr( $label ) . '"' : '',
			$inner // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- از قبل escape شده.
		);
	}

	/**
	 * پیام راهنما وقتی هیچ آیتمی تنظیم نشده — فقط در ویرایشگر.
	 *
	 * @param string $message
	 * @return bool آیا پیام نمایش داده شد.
	 */
	protected function render_empty( $message ) {
		if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			return true;
		}

		printf( '<div class="ard-empty">%s</div>', esc_html( $message ) );

		return true;
	}
}
