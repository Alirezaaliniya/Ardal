<?php
/**
 * ویجت اطلاعات و مشخصات محصول (عنوان، برند، متا، رنگ، سایز، ویژگی‌ها).
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Product_Info extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-product-info';
	}

	public function get_title() {
		return 'اردال — اطلاعات و مشخصات محصول';
	}

	public function get_icon() {
		return 'eicon-info-circle-o';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'عنوان', 'برند', 'رنگ', 'سایز', 'مشخصات', 'info', 'product' );
	}

	protected function register_controls() {
		// ==================== عنوان و برند ====================
		$this->start_controls_section(
			'section_title',
			array( 'label' => 'عنوان و برند' )
		);

		$this->add_control( 'title', array(
			'label'       => 'عنوان فارسی',
			'type'        => Controls_Manager::TEXT,
			'default'     => 'کفش دویدن پرو-فیت مدل Aero X2 مردانه',
			'label_block' => true,
		) );

		$this->add_control( 'subtitle', array(
			'label'       => 'عنوان انگلیسی / زیرعنوان',
			'type'        => Controls_Manager::TEXT,
			'default'     => 'Pro-Fit Aero X2 Running Shoes — Men',
			'label_block' => true,
		) );

		$this->add_control( 'brand_name', array(
			'label'   => 'نام برند',
			'type'    => Controls_Manager::TEXT,
			'default' => 'برند پرو-فیت',
		) );

		$this->add_control( 'brand_logo', array(
			'label'   => 'لوگوی برند',
			'type'    => Controls_Manager::MEDIA,
			'default' => array( 'url' => '' ),
		) );

		$this->add_control( 'brand_link', array(
			'label'   => 'لینک محصولات برند',
			'type'    => Controls_Manager::URL,
			'default' => array( 'url' => '#' ),
		) );

		$this->end_controls_section();

		// ==================== متای محصول ====================
		$this->start_controls_section(
			'section_meta',
			array( 'label' => 'امتیاز و متا' )
		);

		$this->add_control( 'rating', array(
			'label'   => 'امتیاز (از ۵)',
			'type'    => Controls_Manager::NUMBER,
			'min'     => 1, 'max' => 5, 'step' => 0.1,
			'default' => 4.6,
		) );

		$this->add_control( 'reviews_count', array(
			'label' => 'تعداد دیدگاه‌ها', 'type' => Controls_Manager::NUMBER, 'default' => 48,
		) );

		$this->add_control( 'qna_count', array(
			'label' => 'تعداد پرسش و پاسخ', 'type' => Controls_Manager::NUMBER, 'default' => 21,
		) );

		$this->add_control( 'sku', array(
			'label' => 'کد کالا (SKU)', 'type' => Controls_Manager::TEXT, 'default' => 'SPL-۹۴۲۰۷',
		) );

		$this->add_control( 'stock_status', array(
			'label' => 'وضعیت موجودی', 'type' => Controls_Manager::TEXT, 'default' => 'موجود در انبار',
		) );

		$this->end_controls_section();

		// ==================== رنگ‌ها ====================
		$this->start_controls_section(
			'section_colors',
			array( 'label' => 'انتخاب رنگ' )
		);

		$rep_colors = new Repeater();
		$rep_colors->add_control( 'color_name', array(
			'label' => 'نام رنگ', 'type' => Controls_Manager::TEXT, 'default' => 'مشکی / لیمویی',
		) );
		$rep_colors->add_control( 'color_hex', array(
			'label' => 'کد رنگ', 'type' => Controls_Manager::COLOR, 'default' => '#20303f',
		) );

		$this->add_control( 'colors', array(
			'label'       => 'لیست رنگ‌ها',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep_colors->get_controls(),
			'title_field' => '{{{ color_name }}}',
			'default'     => array(
				array( 'color_name' => 'مشکی / لیمویی', 'color_hex' => '#20303f' ),
				array( 'color_name' => 'سفید / آبی', 'color_hex' => '#e8eef5' ),
				array( 'color_name' => 'سرمه‌ای', 'color_hex' => '#0B3C8C' ),
				array( 'color_name' => 'خاکستری', 'color_hex' => '#8c98a4' ),
			),
		) );

		$this->end_controls_section();

		// ==================== سایزها ====================
		$this->start_controls_section(
			'section_sizes',
			array( 'label' => 'انتخاب سایز' )
		);

		$rep_sizes = new Repeater();
		$rep_sizes->add_control( 'size_label', array(
			'label' => 'سایز', 'type' => Controls_Manager::TEXT, 'default' => '۴۲',
		) );
		$rep_sizes->add_control( 'is_available', array(
			'label' => 'موجود', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes',
		) );

		$this->add_control( 'sizes', array(
			'label'       => 'لیست سایزها',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep_sizes->get_controls(),
			'title_field' => '{{{ size_label }}}',
			'default'     => array(
				array( 'size_label' => '۴۰', 'is_available' => 'yes' ),
				array( 'size_label' => '۴۱', 'is_available' => 'yes' ),
				array( 'size_label' => '۴۲', 'is_available' => 'yes' ),
				array( 'size_label' => '۴۳', 'is_available' => 'yes' ),
				array( 'size_label' => '۴۴', 'is_available' => '' ),
				array( 'size_label' => '۴۵', 'is_available' => 'yes' ),
				array( 'size_label' => '۴۶', 'is_available' => '' ),
			),
		) );

		$this->add_control( 'size_guide_text', array(
			'label' => 'متن راهنمای سایز', 'type' => Controls_Manager::TEXT, 'default' => 'راهنمای انتخاب سایز',
		) );

		$this->add_control( 'size_guide_link', array(
			'label' => 'لینک راهنمای سایز', 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#' ),
		) );

		$this->end_controls_section();

		// ==================== ویژگی‌های سریع ====================
		$this->start_controls_section(
			'section_quick',
			array( 'label' => 'ویژگی‌های کلیدی' )
		);

		$rep_quick = new Repeater();
		$rep_quick->add_control( 'key', array(
			'label' => 'ویژگی', 'type' => Controls_Manager::TEXT, 'default' => 'برند',
		) );
		$rep_quick->add_control( 'val', array(
			'label' => 'مقدار', 'type' => Controls_Manager::TEXT, 'default' => 'پرو-فیت',
		) );

		$this->add_control( 'quick_specs', array(
			'label'       => 'ویژگی‌های سریع',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $rep_quick->get_controls(),
			'title_field' => '{{{ key }}}: {{{ val }}}',
			'default'     => array(
				array( 'key' => 'برند', 'val' => 'پرو-فیت' ),
				array( 'key' => 'جنس رویه', 'val' => 'مش مهندسی‌شده' ),
				array( 'key' => 'زیره', 'val' => 'لاستیک ضدسایش' ),
				array( 'key' => 'وزن', 'val' => '۲۴۵ گرم (سایز ۴۲)' ),
				array( 'key' => 'کاربری', 'val' => 'دویدن و تردمیل' ),
				array( 'key' => 'گارانتی', 'val' => '۱۸ ماه اصالت کالا' ),
			),
		) );

		$this->end_controls_section();
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$colors = is_array( $s['colors'] ) ? $s['colors'] : array();
		$sizes  = is_array( $s['sizes'] ) ? $s['sizes'] : array();
		$quick  = is_array( $s['quick_specs'] ) ? $s['quick_specs'] : array();
		$first  = ! empty( $colors[0]['color_name'] ) ? $colors[0]['color_name'] : '';
		?>
		<div class="ard-prod-info" data-ard-product-info>
			<!-- عنوان -->
			<h1 class="ard-prod-hero__title"><?php echo esc_html( $s['title'] ); ?></h1>
			<?php if ( ! empty( $s['subtitle'] ) ) : ?>
				<div class="ard-prod-hero__subtitle"><?php echo esc_html( $s['subtitle'] ); ?></div>
			<?php endif; ?>

			<!-- باکس برند -->
			<?php if ( ! empty( $s['brand_name'] ) ) : ?>
				<div class="ard-prod-brand">
					<div class="ard-prod-brand__logo">
						<?php if ( ! empty( $s['brand_logo']['url'] ) ) : ?>
							<img src="<?php echo esc_url( $s['brand_logo']['url'] ); ?>" alt="<?php echo esc_attr( $s['brand_name'] ); ?>">
						<?php else : ?>
							<span>لوگوی برند</span>
						<?php endif; ?>
					</div>
					<div class="ard-prod-brand__info">
						<div class="ard-prod-brand__name"><?php echo esc_html( $s['brand_name'] ); ?></div>
						<?php if ( ! empty( $s['brand_link']['url'] ) ) : ?>
							<a class="ard-prod-brand__link"<?php echo Ardal_Helpers::link_attrs( $s['brand_link'] ); // phpcs:ignore ?>>همه محصولات این برند ›</a>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

			<!-- متای محصول -->
			<div class="ard-prod-meta">
				<div class="ard-prod-meta__rate">
					<span class="ard-prod-meta__stars">★★★★☆</span>
					<span class="ard-prod-meta__score"><?php echo esc_html( Ardal_Helpers::fa_digits( $s['rating'] ) ); ?></span>
					<span class="ard-prod-meta__count">(<?php echo esc_html( Ardal_Helpers::fa_digits( $s['reviews_count'] ) ); ?> نظر)</span>
				</div>
				<div class="ard-prod-meta__item ard-prod-meta__item--link"><?php echo esc_html( Ardal_Helpers::fa_digits( $s['qna_count'] ) ); ?> پرسش و پاسخ</div>
				<div class="ard-prod-meta__item">کد کالا: <?php echo esc_html( $s['sku'] ); ?></div>
				<div class="ard-prod-meta__item ard-prod-meta__item--stock">
					<span class="ard-prod-meta__stock-dot"></span>
					<span><?php echo esc_html( $s['stock_status'] ); ?></span>
				</div>
			</div>

			<!-- انتخابگر رنگ -->
			<?php if ( ! empty( $colors ) ) : ?>
				<div class="ard-prod-variants ard-prod-variants--colors" data-ard-color-group>
					<div class="ard-prod-variants__header">
						<span class="ard-prod-variants__label">رنگ:</span>
						<span class="ard-prod-variants__selected" data-ard-color-target><?php echo esc_html( $first ); ?></span>
					</div>
					<div class="ard-prod-colors">
						<?php foreach ( $colors as $idx => $c ) : ?>
							<button type="button" class="ard-prod-color<?php echo 0 === $idx ? ' is-active' : ''; ?>" data-color-name="<?php echo esc_attr( $c['color_name'] ); ?>" style="--swatch-color: <?php echo esc_attr( $c['color_hex'] ); ?>">
								<span class="ard-prod-color__swatch"></span>
								<span class="ard-prod-color__name"><?php echo esc_html( $c['color_name'] ); ?></span>
							</button>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<!-- انتخابگر سایز -->
			<?php if ( ! empty( $sizes ) ) : ?>
				<div class="ard-prod-variants ard-prod-variants--sizes" data-ard-size-group>
					<div class="ard-prod-variants__header">
						<span class="ard-prod-variants__label">سایز:</span>
						<?php if ( ! empty( $s['size_guide_text'] ) ) : ?>
							<a class="ard-prod-variants__guide"<?php echo Ardal_Helpers::link_attrs( $s['size_guide_link'] ); // phpcs:ignore ?>><?php echo esc_html( $s['size_guide_text'] ); ?></a>
						<?php endif; ?>
					</div>
					<div class="ard-prod-sizes">
						<?php foreach ( $sizes as $idx => $sz ) : ?>
							<?php
							$is_ok     = 'yes' === ( isset( $sz['is_available'] ) ? $sz['is_available'] : 'yes' );
							$is_active = $is_ok && 2 === $idx;
							?>
							<button type="button" class="ard-prod-size<?php echo $is_active ? ' is-active' : ''; ?><?php echo ! $is_ok ? ' is-disabled' : ''; ?>" <?php echo ! $is_ok ? 'disabled' : ''; ?> data-size-val="<?php echo esc_attr( $sz['size_label'] ); ?>">
								<?php echo esc_html( Ardal_Helpers::fa_digits( $sz['size_label'] ) ); ?>
							</button>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<!-- ویژگی‌های سریع -->
			<?php if ( ! empty( $quick ) ) : ?>
				<div class="ard-prod-quick">
					<?php foreach ( $quick as $q ) : ?>
						<div class="ard-prod-quick__item">
							<span class="ard-prod-quick__dot"></span>
							<span class="ard-prod-quick__key"><?php echo esc_html( $q['key'] ); ?></span>
							<span class="ard-prod-quick__val"><?php echo esc_html( $q['val'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
