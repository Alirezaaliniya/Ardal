<?php
/**
 * ویجت اسلایدر هیرو.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Hero extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-hero';
	}

	public function get_title() {
		return 'اردال — اسلایدر هیرو';
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_keywords() {
		return array( 'اردال', 'اسلایدر', 'هیرو', 'بنر', 'hero', 'slider' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_slides',
			array( 'label' => 'اسلایدها' )
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'tag',
			array(
				'label'       => 'برچسب بالای عنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'کالکشن پاییز ۱۴۰۵',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'       => 'عنوان',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'default'     => 'کفش دویدن حرفه‌ای، با ضمانت اصالت',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'subtitle',
			array(
				'label'       => 'توضیح',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => 'بیش از ۴۰۰ مدل کفش ریسینگ و ترینینگ از برندهای معتبر، با امکان تست ۷ روزه و بازگشت رایگان.',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'image',
			array(
				'label' => 'تصویر اسلاید',
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$repeater->add_control(
			'btn_text',
			array(
				'label'   => 'متن دکمه اصلی',
				'type'    => Controls_Manager::TEXT,
				'default' => 'مشاهده محصولات',
			)
		);

		$repeater->add_control(
			'btn_link',
			array(
				'label'   => 'لینک دکمه اصلی',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '' ),
			)
		);

		$repeater->add_control(
			'btn2_text',
			array(
				'label'   => 'متن دکمه دوم',
				'type'    => Controls_Manager::TEXT,
				'default' => 'راهنمای خرید',
			)
		);

		$repeater->add_control(
			'btn2_link',
			array(
				'label'   => 'لینک دکمه دوم',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '' ),
			)
		);

		$this->add_control(
			'slides',
			array(
				'label'       => 'اسلایدها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'tag'      => 'کالکشن پاییز ۱۴۰۵',
						'title'    => 'کفش دویدن حرفه‌ای، با ضمانت اصالت',
						'subtitle' => 'بیش از ۴۰۰ مدل کفش ریسینگ و ترینینگ از برندهای معتبر، با امکان تست ۷ روزه و بازگشت رایگان.',
					),
					array(
						'tag'      => 'تجهیزات باشگاه خانگی',
						'title'    => 'باشگاه‌تان را در خانه بسازید',
						'subtitle' => 'تردمیل، دوچرخه ثابت، دمبل و بنچ — با نصب رایگان در تهران و پرداخت اقساطی.',
					),
					array(
						'tag'      => 'فصل شنا',
						'title'    => 'لوازم شنا و ورزش‌های آبی',
						'subtitle' => 'عینک، کلاه، مایو و تجهیزات نجات غریق با تخفیف ویژه شروع فصل.',
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_behaviour',
			array( 'label' => 'رفتار' )
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => 'چرخش خودکار',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'interval',
			array(
				'label'       => 'فاصله چرخش (ثانیه)',
				'type'        => Controls_Manager::NUMBER,
				'min'         => 2,
				'max'         => 30,
				'default'     => 5,
				'condition'   => array( 'autoplay' => 'yes' ),
			)
		);

		$this->add_control(
			'show_arrows',
			array(
				'label'        => 'نمایش فلش‌ها',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
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
				'default' => 'gradient',
				'options' => array(
					'gradient' => 'گرادیان سرمه‌ای با متن سفید (دیزاین ۱a)',
					'soft'     => 'زمینه روشن با عنوان سرمه‌ای (دیزاین ۱b)',
				),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'      => 'ارتفاع',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 240, 'max' => 720 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 392 ),
				'tablet_default' => array( 'unit' => 'px', 'size' => 340 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 0 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-hero__stage' => 'min-height:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$slides = is_array( $s['slides'] ) ? $s['slides'] : array();

		if ( empty( $slides ) ) {
			$this->render_empty( 'حداقل یک اسلاید اضافه کنید.' );

			return;
		}

		$interval = 'yes' === $s['autoplay'] ? max( 2, (int) $s['interval'] ) * 1000 : 0;
		?>
		<section
			class="ard-hero ard-hero--<?php echo esc_attr( $s['variant'] ); ?>"
			data-ard-slider
			data-interval="<?php echo esc_attr( $interval ); ?>"
			aria-roledescription="carousel"
		>
			<div class="ard-hero__stage">
				<?php foreach ( $slides as $i => $slide ) : ?>
					<div
						class="ard-hero__slide<?php echo 0 === $i ? ' is-active' : ''; ?>"
						role="group"
						aria-roledescription="slide"
						aria-label="<?php echo esc_attr( sprintf( 'اسلاید %s از %s', Ardal_Helpers::fa_digits( $i + 1 ), Ardal_Helpers::fa_digits( count( $slides ) ) ) ); ?>"
						<?php echo 0 === $i ? '' : ' aria-hidden="true"'; ?>
					>
						<div class="ard-hero__copy">
							<?php if ( '' !== trim( (string) $slide['tag'] ) ) : ?>
								<span class="ard-hero__tag"><?php echo esc_html( $slide['tag'] ); ?></span>
							<?php endif; ?>

							<h2 class="ard-hero__title"><?php echo esc_html( $slide['title'] ); ?></h2>

							<?php if ( '' !== trim( (string) $slide['subtitle'] ) ) : ?>
								<p class="ard-hero__sub"><?php echo esc_html( $slide['subtitle'] ); ?></p>
							<?php endif; ?>

							<div class="ard-hero__btns">
								<?php
								if ( '' !== trim( (string) $slide['btn_text'] ) ) :
									$tag = Ardal_Helpers::link_tag( $slide['btn_link'] );
									?>
									<<?php echo esc_html( $tag ); ?> class="ard-btn ard-btn--accent"<?php echo Ardal_Helpers::link_attrs( $slide['btn_link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $slide['btn_text'] ); ?></<?php echo esc_html( $tag ); ?>>
								<?php endif; ?>

								<?php
								if ( '' !== trim( (string) $slide['btn2_text'] ) ) :
									$tag2 = Ardal_Helpers::link_tag( $slide['btn2_link'] );
									?>
									<<?php echo esc_html( $tag2 ); ?> class="ard-btn ard-btn--ghost"<?php echo Ardal_Helpers::link_attrs( $slide['btn2_link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $slide['btn2_text'] ); ?></<?php echo esc_html( $tag2 ); ?>>
								<?php endif; ?>
							</div>
						</div>

						<div class="ard-hero__media">
							<?php echo Ardal_Helpers::image( $slide['image'], '[ تصویر شاخص اسلاید ۱۲۸۰×۹۶۰ ]', $slide['title'], 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( count( $slides ) > 1 ) : ?>
				<div class="ard-hero__dots" role="tablist" aria-label="انتخاب اسلاید">
					<?php foreach ( $slides as $i => $slide ) : ?>
						<button
							type="button"
							class="ard-hero__dot<?php echo 0 === $i ? ' is-active' : ''; ?>"
							data-ard-goto="<?php echo esc_attr( $i ); ?>"
							role="tab"
							aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
							aria-label="<?php echo esc_attr( 'اسلاید ' . Ardal_Helpers::fa_digits( $i + 1 ) ); ?>"
						></button>
					<?php endforeach; ?>
				</div>

				<?php if ( 'yes' === $s['show_arrows'] ) : ?>
					<button type="button" class="ard-hero__arrow ard-hero__arrow--prev" data-ard-prev aria-label="اسلاید قبلی">
						<?php echo Ardal_Helpers::icon( 'chevron-right', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
					<button type="button" class="ard-hero__arrow ard-hero__arrow--next" data-ard-next aria-label="اسلاید بعدی">
						<?php echo Ardal_Helpers::icon( 'chevron-left', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>
				<?php endif; ?>
			<?php endif; ?>
		</section>
		<?php
	}
}
