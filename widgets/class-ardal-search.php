<?php
/**
 * ویجت جست‌وجوی زندهٔ محصول.
 *
 * کادر جست‌وجو با پنل پیشنهاد که همان‌جا نتیجه‌ها را نشان می‌دهد. مارک‌آپ
 * نتیجه‌ها را سرور می‌سازد (Ardal_Search) تا کارت‌ها یک‌جا نگهداری شوند.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Search extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-search';
	}

	public function get_title() {
		return 'اردال — جست‌وجوی زنده';
	}

	public function get_icon() {
		return 'eicon-site-search';
	}

	public function get_keywords() {
		return array( 'اردال', 'جستجو', 'جست‌وجو', 'زنده', 'ایجکس', 'search', 'live', 'ajax' );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-search' );
	}

	public function get_script_depends() {
		return array( 'ardal-search' );
	}

	protected function register_controls() {

		/* ------------------------------------------------ کادر */

		$this->start_controls_section(
			'section_box',
			array( 'label' => 'کادر جست‌وجو' )
		);

		$this->add_control(
			'placeholder',
			array(
				'label'       => 'متن راهنمای داخل کادر',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'نام کالا یا برند را بنویسید…',
				'label_block' => true,
			)
		);

		$this->add_control(
			'show_icon',
			array(
				'label'        => 'ذره‌بین داخل کادر',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_button',
			array(
				'label'        => 'دکمه جست‌وجو',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'       => 'متن دکمه',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'جست‌وجو',
				'description' => 'خالی = فقط آیکون ذره‌بین.',
				'condition'   => array( 'show_button' => 'yes' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ رفتار */

		$this->start_controls_section(
			'section_behavior',
			array( 'label' => 'رفتار' )
		);

		$this->add_control(
			'min_chars',
			array(
				'label'       => 'کمینه تعداد حرف',
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'max'         => 10,
				'default'     => 3,
				'description' => 'زیر این تعداد، درخواستی به سرور نمی‌رود.',
			)
		);

		$this->add_control(
			'delay',
			array(
				'label'       => 'مکث پیش از جست‌وجو',
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 2000,
				'step'        => 50,
				'default'     => 300,
				'description' => 'به میلی‌ثانیه. تایپِ پشت‌سرهم را یک درخواست می‌کند.',
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'   => 'تعداد نتیجه در پنل',
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 30,
				'default' => 8,
			)
		);

		$this->add_control(
			'hint_text',
			array(
				'label'       => 'راهنمای پیش از تایپ',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => 'خالی = از روی «کمینه تعداد حرف» ساخته می‌شود.',
			)
		);

		$this->add_control(
			'empty_text',
			array(
				'label'       => 'متن وقتی چیزی پیدا نشد',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'چیزی پیدا نشد.',
				'label_block' => true,
			)
		);

		$this->add_control(
			'show_more',
			array(
				'label'        => 'لینک «دیدن همه»',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'more_text',
			array(
				'label'     => 'متن لینک',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'دیدن همهٔ نتیجه‌ها',
				'condition' => array( 'show_more' => 'yes' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ بخش‌های نتیجه */

		$this->start_controls_section(
			'section_card',
			array( 'label' => 'بخش‌های هر نتیجه' )
		);

		foreach ( $this->parts() as $key => $part ) {
			$this->add_control(
				'card_' . $key,
				array(
					'label'        => $part['label'],
					'type'         => Controls_Manager::SWITCHER,
					'default'      => $part['on'] ? 'yes' : '',
					'return_value' => 'yes',
				)
			);
		}

		$this->end_controls_section();

		/* ------------------------------------------------ ظاهر */

		$this->start_controls_section(
			'section_style',
			array(
				'label' => 'ظاهر',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'panel_width',
			array(
				'label'   => 'پهنای پنل',
				'type'    => Controls_Manager::SELECT,
				'default' => 'input',
				'options' => array(
					'input'  => 'به اندازهٔ کادر',
					'wide'   => 'عریض‌تر از کادر',
					'custom' => 'اندازهٔ دلخواه',
				),
			)
		);

		$this->add_responsive_control(
			'panel_width_custom',
			array(
				'label'      => 'اندازه دلخواه پنل',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vw' ),
				'range'      => array(
					'px' => array( 'min' => 240, 'max' => 900 ),
					'vw' => array( 'min' => 30, 'max' => 100 ),
				),
				'condition'  => array( 'panel_width' => 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-se' => '--ard-se-panel-w:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rows',
			array(
				'label'       => 'ردیف‌های دیده‌شده پیش از اسکرول',
				'type'        => Controls_Manager::SLIDER,
				'range'       => array( 'px' => array( 'min' => 2, 'max' => 12, 'step' => 1 ) ),
				'default'     => array( 'size' => 5 ),
				'selectors'   => array(
					'{{WRAPPER}} .ard-se' => '--ard-se-rows:{{SIZE}};',
				),
				'description' => 'بیش از این تعداد، پنل اسکرول می‌شود.',
			)
		);

		$this->add_control(
			'box_bg',
			array(
				'label'     => 'زمینه کادر',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se' => '--ard-se-box-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'box_line',
			array(
				'label'     => 'رنگ حاشیه کادر',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se' => '--ard-se-line:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'box_radius',
			array(
				'label'      => 'گردی کادر',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-se' => '--ard-se-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'box_height',
			array(
				'label'      => 'ارتفاع کادر',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 34, 'max' => 72 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-se' => '--ard-se-h:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'btn_bg',
			array(
				'label'     => 'زمینه دکمه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se' => '--ard-se-btn-bg:{{VALUE}};' ),
				'condition' => array( 'show_button' => 'yes' ),
			)
		);

		$this->add_control(
			'btn_ink',
			array(
				'label'     => 'رنگ متن دکمه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-se' => '--ard-se-btn-ink:{{VALUE}};' ),
				'condition' => array( 'show_button' => 'yes' ),
			)
		);

		$this->add_control(
			'panel_bg',
			array(
				'label'     => 'زمینه پنل',
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array( '{{WRAPPER}} .ard-se' => '--ard-se-panel-bg:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typo',
				'label'    => 'قلم عنوان نتیجه‌ها',
				'selector' => '{{WRAPPER}} .ard-se__title',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		if ( $this->require_woo() ) {
			return;
		}

		$s   = $this->get_settings_for_display();
		$uid = 'ard-se-' . $this->get_id();
		$min = max( 1, min( 10, (int) $s['min_chars'] ) );

		$hint = trim( (string) $s['hint_text'] );

		if ( '' === $hint ) {
			$hint = sprintf( 'برای جست‌وجو دست‌کم %s حرف بنویسید.', Ardal_Helpers::fa_digits( $min ) );
		}

		// همان چیزهایی که ایجکس برای ساختن کارت لازم دارد.
		$payload = array(
			'limit'      => max( 1, min( 30, (int) $s['limit'] ) ),
			'more'       => 'yes' === $s['show_more'] ? 'yes' : 'no',
			'more_text'  => $s['more_text'],
			'empty_text' => $s['empty_text'],
		);

		foreach ( array_keys( $this->parts() ) as $key ) {
			$payload[ $key ] = 'yes' === $s[ 'card_' . $key ] ? 'yes' : 'no';
		}

		$classes = array_filter(
			array(
				'ard-se',
				'ard-se--w-' . $s['panel_width'],
				'yes' === $s['show_button'] ? 'ard-se--has-btn' : '',
			)
		);
		?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
			data-ard-search
			data-min="<?php echo esc_attr( $min ); ?>"
			data-delay="<?php echo esc_attr( max( 0, min( 2000, (int) $s['delay'] ) ) ); ?>"
			data-args="<?php echo esc_attr( wp_json_encode( $payload ) ); ?>">

			<form class="ard-se__form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="hidden" name="post_type" value="product" />

				<div class="ard-se__box">
					<?php if ( 'yes' === $s['show_icon'] ) : ?>
						<span class="ard-se__icon" aria-hidden="true"><?php echo Ardal_Helpers::icon( 'search', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php endif; ?>

					<input type="search" class="ard-se__input" name="s"
						id="<?php echo esc_attr( $uid ); ?>"
						placeholder="<?php echo esc_attr( $s['placeholder'] ); ?>"
						autocomplete="off" role="combobox" aria-expanded="false"
						aria-controls="<?php echo esc_attr( $uid ); ?>-panel"
						aria-label="جست‌وجوی محصول" />

					<button type="button" class="ard-se__clear" data-ard-se-clear hidden aria-label="پاک کردن">&times;</button>
				</div>

				<?php if ( 'yes' === $s['show_button'] ) : ?>
					<button type="submit" class="ard-se__btn">
						<?php if ( '' !== trim( (string) $s['button_text'] ) ) : ?>
							<span class="ard-se__btn-text"><?php echo esc_html( $s['button_text'] ); ?></span>
						<?php else : ?>
							<span class="ard-se__btn-icon" aria-hidden="true"><?php echo Ardal_Helpers::icon( 'search', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span class="ard-sr-only">جست‌وجو</span>
						<?php endif; ?>
					</button>
				<?php endif; ?>
			</form>

			<div class="ard-se__panel" id="<?php echo esc_attr( $uid ); ?>-panel" role="listbox" hidden aria-live="polite">
				<div class="ard-se__hint"><?php echo esc_html( $hint ); ?></div>
			</div>
		</div>
		<?php
	}

	/**
	 * بخش‌های اختیاری هر نتیجه.
	 *
	 * @return array
	 */
	private function parts() {
		return array(
			'image'    => array( 'label' => 'تصویر', 'on' => true ),
			'price'    => array( 'label' => 'قیمت', 'on' => true ),
			'discount' => array( 'label' => 'درصد تخفیف', 'on' => true ),
			'subtitle' => array( 'label' => 'زیرعنوان', 'on' => false ),
			'rating'   => array( 'label' => 'امتیاز', 'on' => false ),
			'stock'    => array( 'label' => 'وضعیت موجودی', 'on' => false ),
		);
	}
}
