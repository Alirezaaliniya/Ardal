<?php
/**
 * ویجت خدمات و مزیت‌ها (ارسال سریع، ضمانت اصالت و …).
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Services extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-services';
	}

	public function get_title() {
		return 'اردال — خدمات و مزیت‌ها';
	}

	public function get_icon() {
		return 'eicon-icon-box';
	}

	public function get_keywords() {
		return array( 'اردال', 'خدمات', 'مزیت', 'ارسال', 'ضمانت', 'services', 'features' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'آیتم‌ها' )
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'icon',
			array(
				'label'   => 'آیکون',
				'type'    => Controls_Manager::SELECT,
				'default' => 'truck',
				'options' => Ardal_Helpers::icon_options(),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'       => 'عنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'ارسال سریع',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'subtitle',
			array(
				'label'       => 'توضیح',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'تهران ۲۴ ساعته',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'tone',
			array(
				'label'   => 'رنگ آیکون',
				'type'    => Controls_Manager::SELECT,
				'default' => 'blue',
				'options' => array(
					'blue'  => 'آبی',
					'green' => 'سبز',
				),
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
				'label'       => 'خدمات',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array( 'icon' => 'truck', 'title' => 'ارسال سریع', 'subtitle' => 'تهران ۲۴ ساعته' ),
					array( 'icon' => 'shield', 'title' => 'ضمانت اصالت', 'subtitle' => 'کالای اورجینال' ),
					array( 'icon' => 'refresh', 'title' => 'بازگشت ۷ روزه', 'subtitle' => 'بدون قید و شرط' ),
					array( 'icon' => 'wallet', 'title' => 'پرداخت اقساطی', 'subtitle' => 'تا ۱۲ ماه' ),
				),
			)
		);

		$this->add_tag_control( 'title_tag', 'تگ عنوان هر مزیت', 'span' );

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
				'default' => 'cards',
				'options' => array(
					'cards'  => 'کارت‌های جدا با آیکون کنار متن (دیزاین ۱a)',
					'strip'  => 'نوار یکپارچه با آیکون بالای متن (دیزاین ۱b)',
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => 'تعداد ستون',
				'type'           => Controls_Manager::SELECT,
				'default'        => '4',
				'tablet_default' => '2',
				'mobile_default' => '2',
				'options'        => array(
					'1' => '۱',
					'2' => '۲',
					'3' => '۳',
					'4' => '۴',
				),
				'selectors'      => array(
					'{{WRAPPER}} .ard-services' => 'grid-template-columns:repeat({{VALUE}},minmax(0,1fr));',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = is_array( $s['items'] ) ? $s['items'] : array();

		if ( empty( $items ) ) {
			$this->render_empty( 'حداقل یک خدمت اضافه کنید.' );

			return;
		}

		$ard_tag = self::tag( $s['title_tag'], 'span' );
		?>
		<ul class="ard-services ard-services--<?php echo esc_attr( $s['variant'] ); ?>">
			<?php foreach ( $items as $item ) : ?>
				<li class="ard-service ard-service--<?php echo esc_attr( $item['tone'] ); ?>">
					<?php $tag = ! empty( $item['link']['url'] ) ? 'a' : 'div'; ?>
					<<?php echo esc_html( $tag ); ?> class="ard-service__inner"<?php echo 'a' === $tag ? Ardal_Helpers::link_attrs( $item['link'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<span class="ard-service__icon"><?php echo Ardal_Helpers::icon( $item['icon'], 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span class="ard-service__text">
							<<?php echo esc_html( $ard_tag ); ?> class="ard-service__title"><?php echo esc_html( $item['title'] ); ?></<?php echo esc_html( $ard_tag ); ?>>
							<?php if ( '' !== trim( (string) $item['subtitle'] ) ) : ?>
								<span class="ard-service__sub"><?php echo esc_html( $item['subtitle'] ); ?></span>
							<?php endif; ?>
						</span>
					</<?php echo esc_html( $tag ); ?>>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}
}
