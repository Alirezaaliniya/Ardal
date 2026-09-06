<?php
/**
 * ویجت برندها.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Brands extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-brands';
	}

	public function get_title() {
		return 'اردال — برندها';
	}

	public function get_icon() {
		return 'eicon-logo';
	}

	public function get_keywords() {
		return array( 'اردال', 'برند', 'لوگو', 'brands', 'logos' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_head',
			array( 'label' => 'عنوان' )
		);

		$this->add_control(
			'title',
			array(
				'label'   => 'عنوان',
				'type'    => Controls_Manager::TEXT,
				'default' => 'برندهای موجود',
			)
		);

		$this->add_control(
			'more_text',
			array(
				'label'   => 'متن لینک انتها',
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'more_link',
			array(
				'label'   => 'لینک انتها',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			array( 'label' => 'برندها' )
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			array(
				'label'       => 'نام برند',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'برند',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'logo',
			array(
				'label' => 'لوگو',
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
				'label'       => 'برندها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array( 'title' => 'برند ۱' ),
					array( 'title' => 'برند ۲' ),
					array( 'title' => 'برند ۳' ),
					array( 'title' => 'برند ۴' ),
					array( 'title' => 'برند ۵' ),
					array( 'title' => 'برند ۶' ),
					array( 'title' => 'برند ۷' ),
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
				'default' => 'box',
				'options' => array(
					'box'  => 'کارت مستطیل حاشیه‌دار (دیزاین ۱a)',
					'pill' => 'کپسول با زمینه ملایم (دیزاین ۱b)',
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => 'تعداد ستون',
				'type'           => Controls_Manager::NUMBER,
				'min'            => 2,
				'max'            => 10,
				'default'        => 7,
				'tablet_default' => 4,
				'mobile_default' => 3,
				'selectors'      => array(
					'{{WRAPPER}} .ard-brands__grid' => 'grid-template-columns:repeat({{VALUE}},minmax(0,1fr));',
				),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'          => 'ارتفاع',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 40, 'max' => 140 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 72 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 56 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-brand' => 'height:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = is_array( $s['items'] ) ? $s['items'] : array();

		if ( empty( $items ) ) {
			$this->render_empty( 'حداقل یک برند اضافه کنید.' );

			return;
		}
		?>
		<section class="ard-brands ard-brands--<?php echo esc_attr( $s['variant'] ); ?>">
			<?php if ( '' !== trim( (string) $s['title'] ) ) : ?>
				<div class="ard-brands__head">
					<h2 class="ard-brands__title"><?php echo esc_html( $s['title'] ); ?></h2>
					<span class="ard-brands__line" aria-hidden="true"></span>
					<?php if ( '' !== trim( (string) $s['more_text'] ) ) : ?>
						<a class="ard-brands__more"<?php echo Ardal_Helpers::link_attrs( $s['more_link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $s['more_text'] ); ?></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="ard-brands__grid">
				<?php
				foreach ( $items as $item ) :
					$tag = ! empty( $item['link']['url'] ) ? 'a' : 'div';
					?>
					<<?php echo esc_html( $tag ); ?> class="ard-brand"<?php echo 'a' === $tag ? Ardal_Helpers::link_attrs( $item['link'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php if ( ! empty( $item['logo']['url'] ) && false === strpos( $item['logo']['url'], 'elementor/assets/images/placeholder' ) ) : ?>
							<img src="<?php echo esc_url( $item['logo']['url'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" loading="lazy" decoding="async" />
						<?php else : ?>
							<span class="ard-brand__name"><?php echo esc_html( '[ ' . $item['title'] . ' ]' ); ?></span>
						<?php endif; ?>
					</<?php echo esc_html( $tag ); ?>>
				<?php endforeach; ?>
			</div>
		</section>
		<?php
	}
}
