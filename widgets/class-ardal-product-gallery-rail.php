<?php
/**
 * ویجت گالری محصول با ستون (یا ردیف) تصاویر کوچک.
 *
 * یک ساختار HTML، دو چیدمان که با کنترل «چیدمان» برای هر دستگاه جدا انتخاب
 * می‌شود:
 *
 *  - ستون کناری (پیش‌فرض دسکتاپ): تصاویر کوچک در یک ستون کنار تصویر اصلی و
 *    هم‌قد آن، با دکمه‌های بالا/پایین؛
 *  - ردیف پایین (پیش‌فرض موبایل): N تصویر کوچک زیر تصویر اصلی، دکمه‌ها وسط زیرشان.
 *
 * چیدمان به‌صورت CSS (grid area و چند متغیر) از سلکتورهای واکنش‌گرای المنتور
 * نوشته می‌شود تا با breakpointهای خود سایت بخواند؛ جاوااسکریپت از
 * --ard-pg-axis می‌فهمد ردیف تصاویر کوچک کدام طرف اسکرول می‌شود.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Ardal_Widget_Product_Gallery_Rail extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-product-gallery-rail';
	}

	public function get_title() {
		return 'اردال — گالری محصول (ستون تصاویر کوچک)';
	}

	public function get_icon() {
		return 'eicon-product-images';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'گالری', 'تصاویر', 'محصول', 'product', 'gallery', 'images' );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-gallery-rail' );
	}

	public function get_script_depends() {
		return array( 'ardal-gallery-rail' );
	}

	/**
	 * قالب‌های ظاهری؛ هرکدام کلاس `ard-pg--tpl-{key}` اضافه می‌کند.
	 *
	 * @return array<string,string>
	 */
	protected static function templates() {
		return array(
			'1' => 'قالب ۱ — حالت فعلی',
		);
	}

	/**
	 * CSS هر چیدمان، به کلید مقدار کنترل «چیدمان».
	 *
	 * @return array<string,string>
	 */
	protected static function layout_css() {
		$side = '--ard-pg-axis: y; --ard-pg-rail-pos: absolute; --ard-pg-rail-dir: column; --ard-pg-rail-wrap: nowrap;'
			. ' --ard-pg-track-dir: column; --ard-pg-track-order: 0; --ard-pg-track-flex: 1 1 0; --ard-pg-thumb-basis: auto;'
			. ' --ard-pg-prev-rot: 180deg; --ard-pg-next-rot: 0deg;';

		return array(
			// گرید راست‌به‌چپ: ستون اول سمت راست است.
			'side-start' => $side . ' grid-template-columns: var(--ard-pg-thumb-w) minmax(0, 1fr); grid-template-areas: "thumbs main";',
			'side-end'   => $side . ' grid-template-columns: minmax(0, 1fr) var(--ard-pg-thumb-w); grid-template-areas: "main thumbs";',
			'bottom'     => '--ard-pg-axis: x; --ard-pg-rail-pos: static; --ard-pg-rail-dir: row; --ard-pg-rail-wrap: wrap;'
				. ' --ard-pg-track-dir: row; --ard-pg-track-order: -1; --ard-pg-track-flex: 0 0 100%;'
				. ' --ard-pg-thumb-basis: calc((100% - (var(--ard-pg-count) - 1) * var(--ard-pg-thumb-gap)) / var(--ard-pg-count));'
				. ' --ard-pg-prev-rot: -90deg; --ard-pg-next-rot: 90deg;'
				. ' grid-template-columns: minmax(0, 1fr); grid-template-areas: "main" "thumbs";',
		);
	}

	protected function register_controls() {

		$ratios = array(
			'1 / 1'  => '1:1',
			'8 / 7'  => '8:7',
			'4 / 3'  => '4:3',
			'5 / 3'  => '5:3',
			'3 / 2'  => '3:2',
			'16 / 9' => '16:9',
			'4 / 5'  => '4:5',
			'3 / 4'  => '3:4',
		);

		$this->start_controls_section(
			'section_content',
			array(
				'label' => 'گالری',
			)
		);

		$this->add_control(
			'notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => 'تصویر شاخص + گالری محصول جاری را نمایش می‌دهد. در ویرایشگر، آخرین محصول برای پیش‌نمایش استفاده می‌شود.',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'template',
			array(
				'label'   => 'قالب',
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => self::templates(),
			)
		);

		$this->add_responsive_control(
			'layout',
			array(
				'label'                => 'چیدمان تصاویر کوچک',
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'side-start' => array( 'title' => 'ستون کناری (راست)', 'icon' => 'eicon-h-align-right' ),
					'side-end'   => array( 'title' => 'ستون کناری (چپ)', 'icon' => 'eicon-h-align-left' ),
					'bottom'     => array( 'title' => 'ردیف پایین', 'icon' => 'eicon-v-align-bottom' ),
				),
				'default'              => 'side-start',
				'mobile_default'       => 'bottom',
				'toggle'               => false,
				'description'          => 'برای دسکتاپ و موبایل جداگانه تنظیم کنید (آیکن نمایشگر کنار عنوان).',
				'selectors_dictionary' => self::layout_css(),
				// فقط وقتی تصویر کوچک هست؛ وگرنه ستون خالی‌شان کنار تصویر تکی جا می‌گرفت.
				'selectors'            => array( '{{WRAPPER}} .ard-pg.has-thumbs' => '{{VALUE}}' ),
			)
		);

		$this->add_responsive_control(
			'thumb_count',
			array(
				'label'       => 'تعداد تصویر کوچک در ردیف',
				'type'        => Controls_Manager::NUMBER,
				'min'         => 2,
				'max'         => 8,
				'default'     => 3,
				'description' => 'فقط در چیدمان «ردیف پایین».',
				'selectors'   => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-count: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'thumb_width',
			array(
				'label'       => 'عرض ستون تصاویر کوچک',
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', '%' ),
				'range'       => array(
					'px' => array( 'min' => 40, 'max' => 200 ),
					'%'  => array( 'min' => 8, 'max' => 40 ),
				),
				'description' => 'فقط در چیدمان «ستون کناری».',
				'selectors'   => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-thumb-w: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'main_ratio',
			array(
				'label'          => 'نسبت تصویر اصلی',
				'type'           => Controls_Manager::SELECT,
				'options'        => array( 'auto' => 'اندازه واقعی تصویر' ) + $ratios,
				'default'        => '8 / 7',
				'mobile_default' => '4 / 3',
				'separator'      => 'before',
				'selectors'      => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-main-ratio: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'main_fit',
			array(
				'label'     => 'نمایش تصویر اصلی',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => array(
					'cover'   => 'پر کردن کادر (برش)',
					'contain' => 'کامل در کادر',
				),
				'selectors' => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-fit: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'thumb_ratio',
			array(
				'label'          => 'نسبت تصاویر کوچک',
				'type'           => Controls_Manager::SELECT,
				'options'        => $ratios,
				'default'        => '1 / 1',
				'mobile_default' => '5 / 3',
				'selectors'      => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-thumb-ratio: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'image_size',
			array(
				'label'   => 'اندازه فایل تصویر اصلی',
				'type'    => Controls_Manager::SELECT,
				'default' => 'large',
				'options' => array(
					'medium_large' => 'medium_large',
					'large'        => 'large',
					'full'         => 'اصلی',
				),
			)
		);

		$this->add_control(
			'show_arrows',
			array(
				'label'     => 'دکمه‌های قبلی / بعدی',
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'loop',
			array(
				'label'     => 'چرخش از آخر به اول',
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array( 'show_arrows' => 'yes' ),
			)
		);

		$this->add_control(
			'swipe',
			array(
				'label'   => 'کشیدن انگشت روی تصویر اصلی',
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'lightbox',
			array(
				'label'       => 'بزرگ‌نمایی با کلیک (لایت‌باکس)',
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => 'از لایت‌باکس خود المنتور استفاده می‌کند.',
			)
		);

		$this->end_controls_section();

		// ---------------------------------------------------------------- فاصله‌ها
		$this->start_controls_section(
			'section_style_layout',
			array(
				'label' => 'فاصله‌ها',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => 'فاصله تصویر اصلی و تصاویر کوچک',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-gap: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'thumb_gap',
			array(
				'label'      => 'فاصله بین تصاویر کوچک',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-thumb-gap: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'arrow_gap',
			array(
				'label'      => 'فاصله دکمه‌ها تا تصاویر کوچک',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-arrow-gap: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		// ---------------------------------------------------------------- تصویر اصلی
		$this->start_controls_section(
			'section_style_main',
			array(
				'label' => 'تصویر اصلی',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'main_bg',
			array(
				'label'     => 'رنگ پس‌زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-main-bg: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'main_radius',
			array(
				'label'      => 'گردی گوشه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-main-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'main_padding',
			array(
				'label'      => 'فاصله داخلی',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pg__main' => 'padding: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'main_border',
				'selector' => '{{WRAPPER}} .ard-pg__main',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'main_shadow',
				'selector' => '{{WRAPPER}} .ard-pg__main',
			)
		);

		$this->end_controls_section();

		// ---------------------------------------------------------------- تصاویر کوچک
		$this->start_controls_section(
			'section_style_thumbs',
			array(
				'label' => 'تصاویر کوچک',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'thumb_bg',
			array(
				'label'     => 'رنگ پس‌زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-thumb-bg: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'thumb_radius',
			array(
				'label'      => 'گردی گوشه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-thumb-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'thumb_border_color',
			array(
				'label'     => 'رنگ حاشیه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-line: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'thumb_active_color',
			array(
				'label'     => 'رنگ حاشیه تصویر فعال',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-active: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'thumb_border_width',
			array(
				'label'      => 'ضخامت حاشیه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 4, 'step' => 0.5 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-thumb-bw: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'thumb_opacity',
			array(
				'label'     => 'شفافیت تصاویر غیرفعال',
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 0.2, 'max' => 1, 'step' => 0.05 ) ),
				'selectors' => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-thumb-dim: {{SIZE}};' ),
			)
		);

		$this->add_control(
			'thumb_fit',
			array(
				'label'     => 'نمایش تصاویر کوچک',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => array(
					'cover'   => 'پر کردن کادر (برش)',
					'contain' => 'کامل در کادر',
				),
				'selectors' => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-thumb-fit: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		// ---------------------------------------------------------------- دکمه‌ها
		$this->start_controls_section(
			'section_style_arrows',
			array(
				'label'     => 'دکمه‌های قبلی / بعدی',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_arrows' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'arrow_size',
			array(
				'label'      => 'اندازه دکمه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 18, 'max' => 64 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-arrow: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'arrow_icon',
			array(
				'label'      => 'اندازه آیکن',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 8, 'max' => 32 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-arrow-icon: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'arrow_radius',
			array(
				'label'      => 'گردی گوشه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 32 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-arrow-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->start_controls_tabs( 'arrow_tabs' );

		$this->start_controls_tab( 'arrow_normal', array( 'label' => 'عادی' ) );
		$this->add_control(
			'arrow_color',
			array(
				'label'     => 'رنگ آیکن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-arrow-color: {{VALUE}};' ),
			)
		);
		$this->add_control(
			'arrow_bg',
			array(
				'label'     => 'پس‌زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-arrow-bg: {{VALUE}};' ),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab( 'arrow_hover', array( 'label' => 'هاور' ) );
		$this->add_control(
			'arrow_color_hover',
			array(
				'label'     => 'رنگ آیکن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-arrow-color-hover: {{VALUE}};' ),
			)
		);
		$this->add_control(
			'arrow_bg_hover',
			array(
				'label'     => 'پس‌زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pg' => '--ard-pg-arrow-bg-hover: {{VALUE}};' ),
			)
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'arrow_shadow',
				'selector'  => '{{WRAPPER}} .ard-pg .ard-pg__arrow',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$product = Ardal_Product::current();

		if ( ! $product ) {
			return;
		}

		$size = in_array( $s['image_size'], array( 'medium_large', 'large', 'full' ), true ) ? $s['image_size'] : 'large';
		$ids  = array_values( array_unique( array_filter( array_merge(
			array( (int) $product->get_image_id() ),
			array_map( 'intval', $product->get_gallery_image_ids() )
		) ) ) );

		$items = array();
		foreach ( $ids as $id ) {
			$src = wp_get_attachment_image_src( $id, $size );
			if ( ! $src ) {
				continue;
			}
			$alt     = trim( (string) get_post_meta( $id, '_wp_attachment_image_alt', true ) );
			$items[] = array(
				'src'    => $src[0],
				'w'      => $src[1],
				'h'      => $src[2],
				'srcset' => (string) wp_get_attachment_image_srcset( $id, $size ),
				'sizes'  => (string) wp_get_attachment_image_sizes( $id, $size ),
				// «medium» برش نخورده؛ بندانگشتی ووکامرس مربعِ بریده است.
				'thumb'  => wp_get_attachment_image_url( $id, 'medium' ) ?: $src[0],
				'full'   => wp_get_attachment_image_url( $id, 'full' ) ?: $src[0],
				'alt'    => '' !== $alt ? $alt : $product->get_name(),
			);
		}

		$tpl   = array_key_exists( (string) $s['template'], self::templates() ) ? (string) $s['template'] : '1';
		$multi = count( $items ) > 1;
		$uid   = 'ard-pg-' . $this->get_id();

		$classes = array( 'ard-pg', 'ard-pg--tpl-' . $tpl, $multi ? 'has-thumbs' : 'has-single' );
		$first   = $items ? $items[0] : null;
		$chevron = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M6 9l6 6 6-6"/></svg>';
		?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" dir="rtl" data-ard-pg
			data-loop="<?php echo 'yes' === $s['loop'] ? '1' : '0'; ?>"
			data-swipe="<?php echo 'yes' === $s['swipe'] ? '1' : '0'; ?>">

			<div class="ard-pg__main">
				<?php if ( $first ) : ?>
					<a class="ard-pg__main-link" href="<?php echo esc_url( $first['full'] ); ?>" data-elementor-open-lightbox="no"<?php echo 'yes' === $s['lightbox'] ? ' data-ard-pg-zoom' : ' tabindex="-1"'; ?>>
						<img class="ard-pg__main-img" src="<?php echo esc_url( $first['src'] ); ?>"
							<?php if ( $first['srcset'] ) : ?>srcset="<?php echo esc_attr( $first['srcset'] ); ?>" sizes="<?php echo esc_attr( $first['sizes'] ); ?>"<?php endif; ?>
							width="<?php echo (int) $first['w']; ?>" height="<?php echo (int) $first['h']; ?>"
							alt="<?php echo esc_attr( $first['alt'] ); ?>" fetchpriority="high" decoding="async" />
					</a>
				<?php else : ?>
					<?php echo wc_placeholder_img( $size, array( 'class' => 'ard-pg__main-img' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endif; ?>
			</div>

			<?php if ( $multi ) : ?>
				<div class="ard-pg__thumbs">
					<div class="ard-pg__rail">
						<?php if ( 'yes' === $s['show_arrows'] ) : ?>
							<button type="button" class="ard-pg__arrow ard-pg__arrow--prev" data-ard-pg-prev aria-label="تصویر قبلی"><?php echo $chevron; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
						<?php endif; ?>

						<div class="ard-pg__track" id="<?php echo esc_attr( $uid ); ?>">
							<?php foreach ( $items as $i => $item ) : ?>
								<button type="button" class="ard-pg__thumb<?php echo 0 === $i ? ' is-active' : ''; ?>"
									data-ard-pg-thumb
									data-src="<?php echo esc_url( $item['src'] ); ?>"
									data-srcset="<?php echo esc_attr( $item['srcset'] ); ?>"
									data-sizes="<?php echo esc_attr( $item['sizes'] ); ?>"
									data-full="<?php echo esc_url( $item['full'] ); ?>"
									data-alt="<?php echo esc_attr( $item['alt'] ); ?>"
									aria-label="<?php echo esc_attr( 'تصویر ' . Ardal_Helpers::fa_digits( $i + 1 ) ); ?>"
									<?php echo 0 === $i ? 'aria-current="true"' : ''; ?>>
									<img src="<?php echo esc_url( $item['thumb'] ); ?>" alt="" loading="lazy" decoding="async" />
								</button>
							<?php endforeach; ?>
						</div>

						<?php if ( 'yes' === $s['show_arrows'] ) : ?>
							<button type="button" class="ard-pg__arrow ard-pg__arrow--next" data-ard-pg-next aria-label="تصویر بعدی"><?php echo $chevron; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $items && 'yes' === $s['lightbox'] ) : ?>
				<div class="ard-pg__lb" hidden>
					<?php foreach ( $items as $item ) : ?>
						<a href="<?php echo esc_url( $item['full'] ); ?>" data-elementor-open-lightbox="yes"
							data-elementor-lightbox-slideshow="<?php echo esc_attr( $uid ); ?>"
							data-elementor-lightbox-title="<?php echo esc_attr( $item['alt'] ); ?>"></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
