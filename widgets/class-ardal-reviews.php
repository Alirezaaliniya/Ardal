<?php
/**
 * ویجت نظر مشتریان.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Reviews extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-reviews';
	}

	public function get_title() {
		return 'اردال — نظر مشتریان';
	}

	public function get_icon() {
		return 'eicon-testimonial';
	}

	public function get_keywords() {
		return array( 'اردال', 'نظر', 'مشتری', 'دیدگاه', 'reviews', 'testimonial' );
	}

	protected function register_controls() {
		$this->add_heading_controls( 'نظر مشتریان', 'primary' );

		$this->start_controls_section(
			'section_content',
			array( 'label' => 'نظرها' )
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'name',
			array(
				'label'       => 'نام مشتری',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'مریم احمدی',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'text',
			array(
				'label'       => 'متن نظر',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 4,
				'default'     => 'سفارشم فردای همان روز رسید. کفش دقیقاً همان چیزی بود که در عکس‌ها دیده بودم و سایزبندی درست بود.',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'avatar',
			array(
				'label' => 'تصویر مشتری',
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$repeater->add_control(
			'rating',
			array(
				'label'   => 'امتیاز',
				'type'    => Controls_Manager::SLIDER,
				'range'   => array( 'px' => array( 'min' => 0, 'max' => 5, 'step' => 0.5 ) ),
				'default' => array( 'size' => 5 ),
			)
		);

		$repeater->add_control(
			'note',
			array(
				'label'   => 'یادداشت زیر نام',
				'type'    => Controls_Manager::TEXT,
				'default' => 'خرید تأییدشده',
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => 'نظرها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ name }}}',
				'default'     => array(
					array(
						'name' => 'مریم احمدی',
						'text' => 'سفارشم فردای همان روز رسید. کفش دقیقاً همان چیزی بود که در عکس‌ها دیده بودم و سایزبندی درست بود.',
					),
					array(
						'name' => 'حسین رضایی',
						'text' => 'یخچال را با نصب رایگان تحویل گرفتم. تیم پشتیبانی برای تنظیم دستگاه تماس گرفت و راهنمایی کرد.',
					),
					array(
						'name' => 'سارا موسوی',
						'text' => 'قیمت‌ها در جشنواره واقعاً پایین‌تر بود. دو بار خرید کردم و هر دو بار بسته‌بندی سالم بود.',
					),
				),
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
				'default' => 'row',
				'options' => array(
					'row'   => 'آواتار کنار متن، کارت حاشیه‌دار (دیزاین ۱a)',
					'stack' => 'ستاره بالا، آواتار پایین، زمینه ملایم (دیزاین ۱b)',
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
				'default'        => 1,
				'tablet_default' => 1,
				'mobile_default' => 1,
				'selectors'      => array(
					'{{WRAPPER}} .ard-reviews' => 'grid-template-columns:repeat({{VALUE}},minmax(0,1fr));',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = is_array( $s['items'] ) ? $s['items'] : array();

		if ( empty( $items ) ) {
			$this->render_empty( 'حداقل یک نظر اضافه کنید.' );

			return;
		}

		$stack = 'stack' === $s['variant'];

		$this->render_heading();
		?>
		<div class="ard-reviews ard-reviews--<?php echo esc_attr( $s['variant'] ); ?>">
			<?php
			foreach ( $items as $item ) :
				$rating = isset( $item['rating']['size'] ) ? (float) $item['rating']['size'] : 5;
				?>
				<figure class="ard-review">
					<?php if ( $stack ) : ?>
						<?php echo Ardal_Helpers::stars( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<blockquote class="ard-review__text"><?php echo esc_html( $item['text'] ); ?></blockquote>
					<?php endif; ?>

					<figcaption class="ard-review__person">
						<span class="ard-review__avatar">
							<?php if ( ! empty( $item['avatar']['url'] ) && false === strpos( $item['avatar']['url'], 'elementor/assets/images/placeholder' ) ) : ?>
								<img src="<?php echo esc_url( $item['avatar']['url'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>" loading="lazy" decoding="async" />
							<?php endif; ?>
						</span>

						<span class="ard-review__meta">
							<span class="ard-review__name-row">
								<span class="ard-review__name"><?php echo esc_html( $item['name'] ); ?></span>
								<?php if ( ! $stack ) : ?>
									<?php echo Ardal_Helpers::stars( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php endif; ?>
							</span>

							<?php if ( $stack && '' !== trim( (string) $item['note'] ) ) : ?>
								<span class="ard-review__note"><?php echo esc_html( $item['note'] ); ?></span>
							<?php endif; ?>

							<?php if ( ! $stack ) : ?>
								<blockquote class="ard-review__text"><?php echo esc_html( $item['text'] ); ?></blockquote>
							<?php endif; ?>
						</span>
					</figcaption>
				</figure>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
