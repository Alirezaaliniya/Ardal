<?php
/**
 * ویجت گالری تصاویر محصول (تصویر اصلی + بندانگشتی‌ها + برچسب‌ها).
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
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'گالری', 'تصویر', 'محصول', 'gallery', 'image' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_gallery',
			array( 'label' => 'تصاویر گالری' )
		);

		$this->add_control(
			'main_image',
			array(
				'label'   => 'تصویر اصلی',
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => '' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			array(
				'label'   => 'تصویر',
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => '' ),
			)
		);

		$repeater->add_control(
			'is_video',
			array(
				'label'        => 'ویدیو است؟',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$repeater->add_control(
			'label',
			array(
				'label'   => 'برچسب (اختیاری)',
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'thumbs',
			array(
				'label'       => 'بندانگشتی‌ها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ label ? label : "تصویر" }}}',
				'default'     => array(
					array( 'label' => 'تصویر ۱' ),
					array( 'label' => 'تصویر ۲' ),
					array( 'label' => 'تصویر ۳' ),
					array( 'label' => 'تصویر ۴' ),
					array( 'label' => 'ویدیو', 'is_video' => 'yes' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_badges',
			array( 'label' => 'برچسب‌ها و دکمه‌ها' )
		);

		$this->add_control(
			'badge_discount',
			array(
				'label'   => 'برچسب تخفیف',
				'type'    => Controls_Manager::TEXT,
				'default' => '۳۲٪ تخفیف',
			)
		);

		$this->add_control(
			'badge_guarantee',
			array(
				'label'   => 'برچسب اصالت',
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
			)
		);

		$this->add_control(
			'show_compare',
			array(
				'label'        => 'دکمه مقایسه',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'zoom_hint',
			array(
				'label'   => 'متن راهنمای بزرگ‌نمایی',
				'type'    => Controls_Manager::TEXT,
				'default' => 'برای بزرگ‌نمایی کلیک کنید',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$thumbs  = is_array( $s['thumbs'] ) ? $s['thumbs'] : array();
		?>
		<div class="ard-prod-gallery" data-ard-gallery>
			<div class="ard-prod-gallery__main">
				<div class="ard-prod-gallery__main-wrap">
					<?php if ( ! empty( $s['main_image']['url'] ) ) : ?>
						<img class="ard-prod-gallery__active-img" src="<?php echo esc_url( $s['main_image']['url'] ); ?>" alt="تصویر محصول">
					<?php else : ?>
						<div class="ard-prod-gallery__placeholder">[ تصویر اصلی — ۱۲۰۰×۱۲۰۰ ]</div>
					<?php endif; ?>
				</div>

				<!-- برچسب‌ها -->
				<div class="ard-prod-gallery__badges">
					<?php if ( ! empty( $s['badge_discount'] ) ) : ?>
						<span class="ard-badge ard-badge--sale ard-prod-gallery__badge"><?php echo esc_html( $s['badge_discount'] ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty( $s['badge_guarantee'] ) ) : ?>
						<span class="ard-badge ard-badge--rank ard-prod-gallery__badge ard-prod-gallery__badge--guarantee"><?php echo esc_html( $s['badge_guarantee'] ); ?></span>
					<?php endif; ?>
				</div>

				<!-- دکمه‌ها -->
				<div class="ard-prod-gallery__actions">
					<?php if ( 'yes' === $s['show_wishlist'] ) : ?>
						<button type="button" class="ard-prod-gallery__btn" data-ard-wishlist aria-label="علاقه‌مندی">♡</button>
					<?php endif; ?>
					<?php if ( 'yes' === $s['show_compare'] ) : ?>
						<button type="button" class="ard-prod-gallery__btn" data-ard-compare aria-label="مقایسه">⇄</button>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $s['zoom_hint'] ) ) : ?>
					<div class="ard-prod-gallery__zoom-hint"><?php echo esc_html( $s['zoom_hint'] ); ?></div>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $thumbs ) ) : ?>
				<div class="ard-prod-gallery__thumbs">
					<?php foreach ( $thumbs as $idx => $item ) : ?>
						<?php
						$is_active = 0 === $idx;
						$is_vid    = 'yes' === ( isset( $item['is_video'] ) ? $item['is_video'] : '' );
						?>
						<button type="button" class="ard-prod-gallery__thumb<?php echo $is_active ? ' is-active' : ''; ?><?php echo $is_vid ? ' ard-prod-gallery__thumb--video' : ''; ?>" data-thumb-index="<?php echo esc_attr( $idx ); ?>" data-img-url="<?php echo ! empty( $item['image']['url'] ) ? esc_url( $item['image']['url'] ) : ''; ?>">
							<?php if ( ! empty( $item['image']['url'] ) ) : ?>
								<img src="<?php echo esc_url( $item['image']['url'] ); ?>" alt="بندانگشتی <?php echo esc_attr( $idx + 1 ); ?>">
							<?php else : ?>
								<span><?php echo $is_vid ? 'ویدیو' : '۴۰۰×۴۰۰'; ?></span>
							<?php endif; ?>
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
