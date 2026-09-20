<?php
/**
 * ویجت بنرهای تبلیغاتی.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Banners extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-banners';
	}

	public function get_title() {
		return 'اردال — بنرهای تبلیغاتی';
	}

	public function get_icon() {
		return 'eicon-image-box';
	}

	public function get_keywords() {
		return array( 'اردال', 'بنر', 'تبلیغات', 'banner', 'promo' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'بنرها' )
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'eyebrow',
			array(
				'label'       => 'متن کوچک بالا',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'       => 'عنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'تجهیزات شنا و آب',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'text',
			array(
				'label'       => 'توضیح',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'default'     => 'عینک، کلاه، مایو و لوازم نجات غریق',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'btn_text',
			array(
				'label'   => 'متن دکمه',
				'type'    => Controls_Manager::TEXT,
				'default' => 'مشاهده',
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

		$repeater->add_control(
			'image',
			array(
				'label' => 'تصویر بنر',
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$repeater->add_control(
			'tone',
			array(
				'label'   => 'رنگ‌بندی',
				'type'    => Controls_Manager::SELECT,
				'default' => 'blue',
				'options' => array(
					'blue'    => 'آبی',
					'green'   => 'سبز',
					'neutral' => 'خنثی (خاکستری روشن)',
				),
			)
		);

		$repeater->add_control(
			'span',
			array(
				'label'   => 'عرض بنر',
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => array(
					'1' => 'یک ستون',
					'2' => 'دو ستون (عریض)',
				),
			)
		);

		$repeater->add_control(
			'no_image',
			array(
				'label'        => 'بدون تصویر (فقط متن)',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => 'بنرها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'title' => 'تجهیزات شنا و آب',
						'text'  => 'عینک، کلاه، مایو و لوازم نجات غریق',
						'tone'  => 'blue',
					),
					array(
						'title' => 'دوی حرفه‌ای',
						'text'  => 'کفش، ساعت ورزشی و پوشاک ریکاوری',
						'tone'  => 'green',
					),
				),
			)
		);

		$this->add_tag_control( 'title_tag', 'تگ عنوان بنر', 'h3' );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => 'ظاهر',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => 'تعداد ستون',
				'type'           => Controls_Manager::NUMBER,
				'min'            => 1,
				'max'            => 4,
				'default'        => 2,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'selectors'      => array(
					'{{WRAPPER}} .ard-banners' => 'grid-template-columns:repeat({{VALUE}},minmax(0,1fr));',
				),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'          => 'ارتفاع بنر',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 120, 'max' => 400 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 170 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 0 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-banner' => 'min-height:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = is_array( $s['items'] ) ? $s['items'] : array();

		if ( empty( $items ) ) {
			$this->render_empty( 'حداقل یک بنر اضافه کنید.' );

			return;
		}

		$ard_tag = self::tag( $s['title_tag'], 'h3' );
		?>
		<div class="ard-banners">
			<?php foreach ( $items as $item ) : ?>
				<div
					class="ard-banner ard-banner--<?php echo esc_attr( $item['tone'] ); ?>"
					style="--ard-banner-span:<?php echo (int) $item['span']; ?>"
				>
					<div class="ard-banner__copy">
						<?php if ( '' !== trim( (string) $item['eyebrow'] ) ) : ?>
							<span class="ard-banner__eyebrow"><?php echo esc_html( $item['eyebrow'] ); ?></span>
						<?php endif; ?>

						<<?php echo esc_html( $ard_tag ); ?> class="ard-banner__title"><?php echo esc_html( $item['title'] ); ?></<?php echo esc_html( $ard_tag ); ?>>

						<?php if ( '' !== trim( (string) $item['text'] ) ) : ?>
							<p class="ard-banner__text"><?php echo esc_html( $item['text'] ); ?></p>
						<?php endif; ?>

						<?php
						if ( '' !== trim( (string) $item['btn_text'] ) ) :
							$btn_tag = Ardal_Helpers::link_tag( $item['link'] );
							?>
							<<?php echo esc_html( $btn_tag ); ?> class="ard-banner__btn"<?php echo Ardal_Helpers::link_attrs( $item['link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $item['btn_text'] ); ?></<?php echo esc_html( $btn_tag ); ?>>
						<?php endif; ?>
					</div>

					<?php if ( 'yes' !== $item['no_image'] ) : ?>
						<div class="ard-banner__media">
							<?php echo Ardal_Helpers::image( $item['image'], 'بنر', $item['title'], 'medium_large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
