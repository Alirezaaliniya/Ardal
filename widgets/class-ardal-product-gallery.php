<?php
/**
 * ویجت گالری تصاویر محصول.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Product_Gallery extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-product-gallery';
	}

	public function get_title() {
		return 'اردال — گالری تصاویر محصول';
	}

	public function get_icon() {
		return 'eicon-product-images';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'گالری', 'تصویر', 'محصول', 'gallery', 'product' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'تصاویر' )
		);

		$this->add_control(
			'source',
			array(
				'label'       => 'منبع',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'auto',
				'options'     => array(
					'auto'   => 'خودکار از محصول جاری',
					'manual' => 'دستی',
				),
				'description' => 'حالت خودکار، تصویر شاخص و گالری خودِ محصول را می‌خواند.',
			)
		);

		$this->add_control(
			'main_image',
			array(
				'label'     => 'تصویر اصلی',
				'type'      => Controls_Manager::MEDIA,
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			array(
				'label' => 'تصویر',
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$repeater->add_control(
			'is_video',
			array(
				'label'        => 'این مورد ویدیو است',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'thumbs',
			array(
				'label'     => 'بندانگشتی‌ها',
				'type'      => Controls_Manager::REPEATER,
				'fields'    => $repeater->get_controls(),
				'condition' => array( 'source' => 'manual' ),
				'default'   => array( array(), array(), array(), array() ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_overlay',
			array( 'label' => 'برچسب‌ها و دکمه‌ها' )
		);

		$this->add_control(
			'auto_discount',
			array(
				'label'        => 'برچسب تخفیف خودکار',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'درصد تخفیف از قیمت محصول حساب می‌شود.',
				'condition'    => array( 'source' => 'auto' ),
			)
		);

		$this->add_control(
			'badge_discount',
			array(
				'label'       => 'برچسب تخفیف',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'مثلاً ۳۲٪ تخفیف',
				'description' => 'در حالت خودکار، اگر خالی باشد از خود محصول حساب می‌شود.',
			)
		);

		$this->add_control(
			'badge_guarantee',
			array(
				'label'   => 'برچسب دوم',
				'type'    => Controls_Manager::TEXT,
				'default' => 'اصل و اورجینال',
			)
		);

		$this->add_control(
			'show_wishlist',
			array(
				'label'        => 'دکمه علاقه‌مندی',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => Ardal_Product::wishlist_on()
					? ''
					: 'علاقه‌مندی در تنظیمات اردال خاموش است، پس این دکمه نمایش داده نمی‌شود.',
			)
		);

		$this->add_control(
			'show_compare',
			array(
				'label'        => 'دکمه مقایسه',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => Ardal_Product::compare_on()
					? ''
					: 'مقایسه در تنظیمات اردال خاموش است، پس این دکمه نمایش داده نمی‌شود.',
			)
		);

		$this->add_control(
			'wishlist_page',
			array(
				'label'       => 'صفحه لیست علاقه‌مندی‌ها',
				'type'        => Controls_Manager::URL,
				'default'     => array( 'url' => '' ),
				'description' => 'اختیاری. اگر پر شود، بعد از افزودن، لینک «مشاهده لیست» نمایش داده می‌شود.',
				'condition'   => array( 'show_wishlist' => 'yes' ),
			)
		);

		$this->add_control(
			'compare_page',
			array(
				'label'     => 'صفحه مقایسه',
				'type'      => Controls_Manager::URL,
				'default'   => array( 'url' => '' ),
				'condition' => array( 'show_compare' => 'yes' ),
			)
		);

		$this->add_control(
			'zoom_hint',
			array(
				'label'       => 'متن راهنما روی تصویر',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'برای بزرگ‌نمایی کلیک کنید',
				'description' => 'خالی بگذارید تا نمایش داده نشود.',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_zoom',
			array( 'label' => 'بزرگ‌نمایی و اسلایدر' )
		);

		$this->add_control(
			'zoom',
			array(
				'label'        => 'ذره‌بین روی هاور',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'فقط روی دستگاه‌هایی که واقعاً موس دارند فعال می‌شود؛ در موبایل خودبه‌خود غیرفعال است.',
			)
		);

		$this->add_control(
			'zoom_factor',
			array(
				'label'     => 'میزان بزرگ‌نمایی',
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 1.5, 'max' => 5, 'step' => 0.1 ) ),
				'default'   => array( 'size' => 2.5 ),
				'condition' => array( 'zoom' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'lens_size',
			array(
				'label'          => 'قطر ذره‌بین',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 90, 'max' => 320 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 180 ),
				'condition'      => array( 'zoom' => 'yes' ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-gallery__lens' => 'inline-size:{{SIZE}}{{UNIT}};block-size:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'lightbox',
			array(
				'label'        => 'لایت‌باکس با کلیک',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'با کلیک روی تصویر، نمای تمام‌صفحه با امکان کشیدن انگشت باز می‌شود.',
			)
		);

		$this->add_control(
			'show_arrows',
			array(
				'label'        => 'فلش‌های اسلایدر',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_dots',
			array(
				'label'        => 'نقطه‌های اسلایدر',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_thumbs',
			array(
				'label'        => 'نوار بندانگشتی',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'thumbs_position',
			array(
				'label'     => 'چیدمان گالری',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'horizontal',
				'options'   => array(
					'horizontal' => 'افقی زیر تصویر',
					'vertical'   => 'عمودی کنار تصویر',
				),
				'condition' => array( 'show_thumbs' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'vertical_thumbs_width',
			array(
				'label'          => 'عرض نوار عمودی',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%' ),
				'range'          => array( 'px' => array( 'min' => 64, 'max' => 180 ), '%' => array( 'min' => 12, 'max' => 40 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 112 ),
				'tablet_default' => array( 'unit' => 'px', 'size' => 94 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 76 ),
				'condition'      => array( 'show_thumbs' => 'yes', 'thumbs_position' => 'vertical' ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-gallery' => '--ard-thumb-rail-width:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'thumb_arrows',
			array(
				'label'        => 'فلش‌های نوار بندانگشتی',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'show_thumbs' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'thumbs_per_view',
			array(
				'label'          => 'تعداد بندانگشتی در نما',
				'type'           => Controls_Manager::SLIDER,
				'range'          => array( 'px' => array( 'min' => 2, 'max' => 10, 'step' => 1 ) ),
				'default'        => array( 'size' => 5 ),
				'tablet_default' => array( 'size' => 5 ),
				'mobile_default' => array( 'size' => 4 ),
				'condition'      => array( 'show_thumbs' => 'yes' ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-gallery__thumbs' => '--ard-thumbs:{{SIZE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_frame',
			array(
				'label' => 'پس‌زمینه کادر تصویر',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'frame_bg',
			array(
				'label'                => 'پس‌زمینه',
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'pattern',
				'options'              => array(
					'pattern' => 'راه‌راه (پیش‌فرض دیزاین)',
					'solid'   => 'رنگ ساده',
					'none'    => 'بدون پس‌زمینه',
				),
				'selectors_dictionary' => array(
					'pattern' => 'repeating-linear-gradient(45deg, var(--ard-frame-line, var(--ard-surface)) 0 var(--ard-frame-size, 10px), var(--ard-frame-base, var(--ard-base)) var(--ard-frame-size, 10px) calc(var(--ard-frame-size, 10px) * 2))',
					'solid'   => 'var(--ard-frame-solid, var(--ard-base))',
					'none'    => 'transparent',
				),
				'selectors'            => array(
					// روی ریشه می‌نشیند تا بندانگشتی‌ها هم بتوانند همین را بخوانند.
					'{{WRAPPER}} .ard-prod-gallery' => '--ard-frame-bg:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'frame_line_color',
			array(
				'label'     => 'رنگ خطوط',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-prod-gallery' => '--ard-frame-line:{{VALUE}};',
				),
				'condition' => array( 'frame_bg' => 'pattern' ),
			)
		);

		$this->add_control(
			'frame_base_color',
			array(
				'label'     => 'رنگ زمینه بین خطوط',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-prod-gallery' => '--ard-frame-base:{{VALUE}};',
				),
				'condition' => array( 'frame_bg' => 'pattern' ),
			)
		);

		$this->add_responsive_control(
			'frame_line_size',
			array(
				'label'      => 'ضخامت خطوط',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 2, 'max' => 40 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 10 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-prod-gallery' => '--ard-frame-size:{{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'frame_bg' => 'pattern' ),
			)
		);

		$this->add_control(
			'frame_solid_color',
			array(
				'label'     => 'رنگ پس‌زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-prod-gallery' => '--ard-frame-solid:{{VALUE}};',
				),
				'condition' => array( 'frame_bg' => 'solid' ),
			)
		);

		$this->add_control(
			'frame_border_color',
			array(
				'label'     => 'رنگ کادر دور',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-prod-gallery__main' => 'border-color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'frame_border_off',
			array(
				'label'        => 'حذف کادر دور',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'selectors'    => array(
					'{{WRAPPER}} .ard-prod-gallery__main' => 'border-color:transparent;',
				),
			)
		);

		$this->add_control(
			'thumb_bg_follow',
			array(
				'label'        => 'همین پس‌زمینه روی بندانگشتی‌ها هم اعمال شود',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'prefix_class' => 'ard-thumb-bg-',
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

		$this->add_responsive_control(
			'thumb_columns',
			array(
				'label'          => 'حداکثر بندانگشتی در هر ردیف',
				'type'           => Controls_Manager::NUMBER,
				'min'            => 2,
				'max'            => 8,
				'default'        => 5,
				'tablet_default' => 5,
				'mobile_default' => 4,
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-gallery__thumbs' => '--ard-thumbs:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'thumb_height',
			array(
				'label'          => 'ارتفاع بندانگشتی',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 44, 'max' => 140 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 88 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 62 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-gallery__thumb' => 'height:{{SIZE}}{{UNIT}};--ard-vertical-thumb-height:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'main_ratio',
			array(
				'label'          => 'نسبت تصویر اصلی',
				'type'           => Controls_Manager::SELECT,
				'default'        => '1 / 1',
				'mobile_default' => '4 / 3',
				'options'        => array(
					'1 / 1' => 'مربع',
					'4 / 3' => '۴:۳',
					'3 / 4' => '۳:۴ (عمودی)',
					'16 / 9' => '۱۶:۹',
				),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-gallery__main' => 'aspect-ratio:{{VALUE}};',
				),
			)
		);

		/*
		 * پیش‌فرض contain است، نه cover: عکس محصول نباید بریده شود. برای
		 * گالری‌هایی که تصویر فضاسازی‌شده دارند، cover انتخاب‌شدنی است.
		 */
		// سلکتور با نام اسلاید، تا از قانون‌های دوکلاسیِ قالب سنگین‌تر بماند.
		$this->add_media_fit_control( '.ard-prod-gallery__slide .ard-prod-gallery__active-img', 'contain' );

		$this->add_control(
			'thumb_fit',
			array(
				'label'       => 'نحوه جاگرفتن تصویر بندانگشتی',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'cover',
				'options'     => array(
					'cover'   => 'قاب را پر کند (cover)',
					'contain' => 'کامل دیده شود (contain)',
				),
				'label_block' => true,
				'selectors'   => array(
					'{{WRAPPER}} .ard-prod-gallery__thumb img' => 'object-fit:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$images = 'manual' === $s['source'] ? $this->manual_images( $s ) : $this->auto_images();
		$badge  = $this->discount_badge( $s );
		$thumbs_position = isset( $s['thumbs_position'] ) ? $s['thumbs_position'] : 'horizontal';

		$product    = Ardal_Product::current();
		$product_id = $product ? $product->get_id() : 0;

		if ( empty( $images ) ) {
			$images[] = array(
				'full'  => '',
				'thumb' => '',
				'alt'   => '',
				'video' => false,
			);
		}

		$multi  = count( $images ) > 1;
		$factor = isset( $s['zoom_factor']['size'] ) ? (float) $s['zoom_factor']['size'] : 2.5;
		?>
		<div
			class="ard-prod-gallery"
			data-ard-gallery
			data-thumbs-position="<?php echo esc_attr( $thumbs_position ); ?>"
			data-zoom="<?php echo esc_attr( 'yes' === $s['zoom'] ? 'yes' : 'no' ); ?>"
			data-zoom-factor="<?php echo esc_attr( $factor ); ?>"
			data-lightbox="<?php echo esc_attr( 'yes' === $s['lightbox'] ? 'yes' : 'no' ); ?>"
		>
			<div class="ard-prod-gallery__main">
				<div class="ard-prod-gallery__track" data-ard-gallery-track>
					<?php foreach ( $images as $idx => $image ) : ?>
						<figure class="ard-prod-gallery__slide">
							<?php if ( '' !== $image['full'] ) : ?>
								<img
									class="ard-prod-gallery__active-img"
									src="<?php echo esc_url( $image['full'] ); ?>"
									data-full="<?php echo esc_url( isset( $image['large'] ) && '' !== $image['large'] ? $image['large'] : $image['full'] ); ?>"
									alt="<?php echo esc_attr( $image['alt'] ); ?>"
									<?php echo 0 === $idx ? '' : ' loading="lazy" decoding="async"'; ?>
								/>
							<?php else : ?>
								<div class="ard-prod-gallery__placeholder">[ تصویر <?php echo esc_html( Ardal_Helpers::fa_digits( $idx + 1 ) ); ?> — ۱۲۰۰×۱۲۰۰ ]</div>
							<?php endif; ?>
						</figure>
					<?php endforeach; ?>
				</div>

				<div class="ard-prod-gallery__badges">
					<?php if ( '' !== $badge ) : ?>
						<span class="ard-badge ard-badge--sale ard-prod-gallery__badge"><?php echo esc_html( $badge ); ?></span>
					<?php endif; ?>
					<?php if ( '' !== trim( (string) $s['badge_guarantee'] ) ) : ?>
						<span class="ard-badge ard-badge--rank ard-prod-gallery__badge ard-prod-gallery__badge--guarantee"><?php echo esc_html( $s['badge_guarantee'] ); ?></span>
					<?php endif; ?>
				</div>

				<div class="ard-prod-gallery__actions">
					<?php
					// کلید سراسری تنظیمات افزونه بر سوییچ خود ویجت مقدم است.
					if ( 'yes' === $s['show_wishlist'] && Ardal_Product::wishlist_on() ) {
						$this->render_action_button( 'wishlist', $product_id, $s );
					}

					if ( 'yes' === $s['show_compare'] && Ardal_Product::compare_on() ) {
						$this->render_action_button( 'compare', $product_id, $s );
					}
					?>
				</div>

				<?php if ( $multi && 'yes' === $s['show_arrows'] ) : ?>
					<button type="button" class="ard-prod-gallery__arrow ard-prod-gallery__arrow--prev" data-ard-gallery-prev aria-label="تصویر قبلی">
						<?php echo Ardal_Helpers::icon( 'chevron-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
					<button type="button" class="ard-prod-gallery__arrow ard-prod-gallery__arrow--next" data-ard-gallery-next aria-label="تصویر بعدی">
						<?php echo Ardal_Helpers::icon( 'chevron-left', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
				<?php endif; ?>

				<?php if ( '' !== trim( (string) $s['zoom_hint'] ) && '' !== $images[0]['full'] ) : ?>
					<div class="ard-prod-gallery__zoom-hint"><?php echo esc_html( $s['zoom_hint'] ); ?></div>
				<?php endif; ?>
			</div>

			<?php if ( $multi && 'yes' === $s['show_dots'] ) : ?>
				<div class="ard-prod-gallery__dots" data-ard-gallery-dots></div>
			<?php endif; ?>

			<?php if ( $multi && 'yes' === $s['show_thumbs'] ) : ?>
				<div class="ard-prod-gallery__thumbs-wrap">
				<?php if ( 'yes' === $s['thumb_arrows'] ) : ?>
					<button type="button" class="ard-prod-gallery__thumb-arrow ard-prod-gallery__thumb-arrow--prev" data-ard-thumbs-prev aria-label="بندانگشتی‌های قبلی">
						<?php echo Ardal_Helpers::icon( 'vertical' === $thumbs_position ? 'chevron-up' : 'chevron-right', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
				<?php endif; ?>
				<div class="ard-prod-gallery__thumbs" role="tablist" aria-label="انتخاب تصویر" data-ard-gallery-thumbs>
					<?php foreach ( $images as $idx => $image ) : ?>
						<button
							type="button"
							class="ard-prod-gallery__thumb<?php echo 0 === $idx ? ' is-active' : ''; ?><?php echo $image['video'] ? ' ard-prod-gallery__thumb--video' : ''; ?>"
							role="tab"
							aria-selected="<?php echo 0 === $idx ? 'true' : 'false'; ?>"
							data-thumb-index="<?php echo esc_attr( $idx ); ?>"
							aria-label="<?php echo esc_attr( 'نمایش تصویر ' . Ardal_Helpers::fa_digits( $idx + 1 ) ); ?>"
						>
							<?php if ( '' !== $image['thumb'] ) : ?>
								<img src="<?php echo esc_url( $image['thumb'] ); ?>" alt="" loading="lazy" decoding="async" />
							<?php else : ?>
								<span class="ard-ph"><?php echo esc_html( $image['video'] ? 'ویدیو' : '۴۰۰×۴۰۰' ); ?></span>
							<?php endif; ?>
						</button>
					<?php endforeach; ?>
				</div>
				<?php if ( 'yes' === $s['thumb_arrows'] ) : ?>
					<button type="button" class="ard-prod-gallery__thumb-arrow ard-prod-gallery__thumb-arrow--next" data-ard-thumbs-next aria-label="بندانگشتی‌های بعدی">
						<?php echo Ardal_Helpers::icon( 'vertical' === $thumbs_position ? 'chevron-down' : 'chevron-left', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
				<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * دکمهٔ علاقه‌مندی یا مقایسه.
	 *
	 * شناسهٔ محصول لازم است تا جاوااسکریپت بتواند وضعیت را ذخیره کند؛ بدون آن
	 * دکمه پنهان می‌شود، چون دکمه‌ای که کاری نمی‌کند بدتر از نبودنش است.
	 *
	 * @param string $kind wishlist یا compare.
	 * @param int    $product_id
	 * @param array  $s
	 */
	private function render_action_button( $kind, $product_id, $s ) {
		$config = array(
			'wishlist' => array(
				'icon'    => 'heart',
				'off'     => 'افزودن به علاقه‌مندی‌ها',
				'on'      => 'حذف از علاقه‌مندی‌ها',
				'page'    => 'wishlist_page',
			),
			'compare'  => array(
				'icon'    => 'refresh',
				'off'     => 'افزودن به مقایسه',
				'on'      => 'حذف از مقایسه',
				'page'    => 'compare_page',
			),
		);

		$c    = $config[ $kind ];
		$page = isset( $s[ $c['page'] ]['url'] ) ? $s[ $c['page'] ]['url'] : '';
		?>
		<button
			type="button"
			class="ard-prod-gallery__btn ard-prod-gallery__btn--<?php echo esc_attr( $kind ); ?>"
			data-ard-<?php echo esc_attr( $kind ); ?>
			data-product-id="<?php echo esc_attr( $product_id ); ?>"
			data-label-off="<?php echo esc_attr( $c['off'] ); ?>"
			data-label-on="<?php echo esc_attr( $c['on'] ); ?>"
			aria-pressed="false"
			aria-label="<?php echo esc_attr( $c['off'] ); ?>"
			title="<?php echo esc_attr( $c['off'] ); ?>"
		><?php echo Ardal_Helpers::icon( $c['icon'], 17 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>

		<?php if ( '' !== $page ) : ?>
			<a class="ard-prod-gallery__btn-link" href="<?php echo esc_url( $page ); ?>"><?php echo esc_html( 'wishlist' === $kind ? 'مشاهده لیست' : 'مشاهده مقایسه' ); ?></a>
		<?php endif; ?>
		<?php
	}

	/**
	 * تصاویر از خود محصول.
	 *
	 * @return array
	 */
	private function auto_images() {
		$product = Ardal_Product::current();

		if ( ! $product ) {
			return array();
		}

		$out = array();

		foreach ( Ardal_Product::gallery( $product ) as $image ) {
			$image['video'] = false;
			$out[]          = $image;
		}

		return $out;
	}

	/**
	 * @param array $s
	 * @return array
	 */
	private function manual_images( $s ) {
		$out = array();

		if ( ! empty( $s['main_image']['url'] ) ) {
			$out[] = array(
				'full'  => $s['main_image']['url'],
				'thumb' => $s['main_image']['url'],
				'alt'   => '',
				'video' => false,
			);
		}

		foreach ( (array) $s['thumbs'] as $item ) {
			$url = isset( $item['image']['url'] ) ? $item['image']['url'] : '';

			$out[] = array(
				'full'  => $url,
				'thumb' => $url,
				'alt'   => '',
				'video' => 'yes' === ( isset( $item['is_video'] ) ? $item['is_video'] : '' ),
			);
		}

		return $out;
	}

	/**
	 * متن برچسب تخفیف.
	 *
	 * @param array $s
	 * @return string
	 */
	private function discount_badge( $s ) {
		$manual = trim( (string) $s['badge_discount'] );

		if ( '' !== $manual ) {
			return Ardal_Helpers::fa_digits( $manual );
		}

		if ( 'auto' !== $s['source'] || 'yes' !== $s['auto_discount'] ) {
			return '';
		}

		$product = Ardal_Product::current();

		if ( ! $product || ! $product->is_on_sale() ) {
			return '';
		}

		$off = Ardal_Helpers::discount( $product->get_regular_price(), $product->get_price() );

		return '' === $off ? '' : $off . ' تخفیف';
	}
}
