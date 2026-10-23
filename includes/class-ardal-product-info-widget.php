<?php
/**
 * پایهٔ مشترک ویجت‌های «توضیحات محصول» و «مشخصات محصول (باکس جمع‌شونده)».
 *
 * هر دو ویجت داخل یک باکس نرم با گوشهٔ گرد می‌نشینند که بسته شروع می‌شود و
 * انتهایش محو است، با دکمهٔ «مشاهده بیشتر»؛ اگر محتوا از قبل جا شود دکمه خودش
 * پنهان می‌شود. وقتی محصول چیزی برای نمایش ندارد، به‌جای جای خالی یک خط توضیح
 * چاپ می‌شود.
 *
 * همهٔ ظاهر پیش‌فرض در ardal-product-info.css متغیر CSS است و کنترل‌های استایل
 * فقط همان متغیرها را بازنویسی می‌کنند؛ ویجتِ دست‌نخورده عین طرح است.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

abstract class Ardal_Product_Info_Widget extends Ardal_Widget_Base {

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-product-info' );
	}

	public function get_script_depends() {
		return array( 'ardal-product-info' );
	}

	/* ------------------------------------------------ کنترل‌های مشترک محتوا */

	/**
	 * عنوان اختیاری بالای باکس.
	 */
	protected function add_title_control() {
		$this->add_control(
			'title',
			array(
				'label'       => 'عنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'خالی = بدون عنوان',
			)
		);

		$this->add_tag_control( 'title_tag', 'تگ عنوان', 'h3', array( 'condition' => array( 'title!' => '' ) ) );
	}

	/**
	 * بخش باز و بسته شدن. اندازهٔ حالت بسته مخصوص هر ویجت است (ارتفاع برای
	 * متن، تعداد ردیف برای مشخصات) و از بیرون اضافه می‌شود.
	 *
	 * @param callable $size_controls
	 */
	protected function add_collapse_section( callable $size_controls ) {
		$this->start_controls_section(
			'section_collapse',
			array( 'label' => 'باز و بسته شدن' )
		);

		$this->add_control(
			'collapse',
			array(
				'label'       => 'حالت جمع‌شده',
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => 'اگر محتوا کوتاه باشد، دکمه خودبه‌خود پنهان می‌شود.',
			)
		);

		$size_controls();

		$this->add_control(
			'more_text',
			array(
				'label'     => 'متن دکمه باز کردن',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'مشاهده بیشتر',
				'condition' => array( 'collapse' => 'yes' ),
			)
		);

		$this->add_control(
			'less_text',
			array(
				'label'     => 'متن دکمه بستن',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'مشاهده کمتر',
				'condition' => array( 'collapse' => 'yes' ),
			)
		);

		$this->add_control(
			'show_icon',
			array(
				'label'     => 'آیکن فلش',
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array( 'collapse' => 'yes' ),
			)
		);

		$this->add_control(
			'show_fade',
			array(
				'label'     => 'محو شدن انتهای محتوا',
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array( 'collapse' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * متن نبود محتوا.
	 *
	 * @param string $default
	 */
	protected function add_empty_control( $default ) {
		$this->add_control(
			'empty_text',
			array(
				'label'       => 'متن نبود محتوا',
				'type'        => Controls_Manager::TEXT,
				'default'     => $default,
				'label_block' => true,
				'description' => 'وقتی محصول محتوایی برای نمایش ندارد. خالی = ویجت اصلاً نمایش داده نشود.',
				'separator'   => 'before',
			)
		);
	}

	/* ------------------------------------------------ بخش‌های مشترک استایل */

	/**
	 * باکس: پس‌زمینه (رنگ محو شدن هم از آن می‌آید)، فاصله، گوشه، حاشیه، سایه.
	 */
	protected function add_box_style_section() {
		$this->start_controls_section(
			'section_style_box',
			array(
				'label' => 'باکس',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'box_bg',
			array(
				'label'       => 'رنگ پس‌زمینه',
				'type'        => Controls_Manager::COLOR,
				'description' => 'رنگ محو شدن انتهای محتوا هم از همین گرفته می‌شود.',
				'selectors'   => array( '{{WRAPPER}} .ard-pi' => '--ard-pi-bg: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'      => 'فاصله داخلی',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .ard-pi' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'box_radius',
			array(
				'label'      => 'گردی گوشه',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array( '{{WRAPPER}} .ard-pi' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'box_border',
				'selector' => '{{WRAPPER}} .ard-pi',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .ard-pi',
			)
		);

		$this->add_control(
			'fade_color',
			array(
				'label'       => 'رنگ محو شدن',
				'type'        => Controls_Manager::COLOR,
				'description' => 'خالی = هم‌رنگ پس‌زمینه باکس.',
				'separator'   => 'before',
				'selectors'   => array( '{{WRAPPER}} .ard-pi' => '--ard-pi-fade: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'fade_height',
			array(
				'label'      => 'ارتفاع محو شدن',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 200 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pi' => '--ard-pi-fade-h: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * عنوان اختیاری.
	 */
	protected function add_title_style_section() {
		$this->start_controls_section(
			'section_style_title',
			array(
				'label'     => 'عنوان',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'title!' => '' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typo',
				'selector' => '{{WRAPPER}} .ard-pi__title',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => 'رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pi__title' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'title_gap',
			array(
				'label'      => 'فاصله تا محتوا',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pi__title' => 'margin-bottom: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * دکمهٔ «مشاهده بیشتر»: چینش، تایپوگرافی، رنگ‌ها، آیکن، فاصله‌ها.
	 */
	protected function add_toggle_style_section() {
		$this->start_controls_section(
			'section_style_toggle',
			array(
				'label'     => 'دکمه مشاهده بیشتر',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'collapse' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'toggle_align',
			array(
				'label'     => 'چینش',
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array( 'title' => 'ابتدا', 'icon' => 'eicon-text-align-right' ),
					'center'     => array( 'title' => 'وسط', 'icon' => 'eicon-text-align-center' ),
					'flex-end'   => array( 'title' => 'انتها', 'icon' => 'eicon-text-align-left' ),
				),
				'selectors' => array( '{{WRAPPER}} .ard-pi__foot' => 'justify-content: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'toggle_typo',
				'selector' => '{{WRAPPER}} .ard-pi .ard-pi__toggle',
			)
		);

		$this->start_controls_tabs( 'toggle_tabs' );

		$this->start_controls_tab( 'toggle_normal', array( 'label' => 'عادی' ) );

		$this->add_control(
			'toggle_color',
			array(
				'label'     => 'رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pi' => '--ard-pi-accent: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'toggle_bg',
			array(
				'label'     => 'پس‌زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pi' => '--ard-pi-toggle-bg: {{VALUE}};' ),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'toggle_hover', array( 'label' => 'هاور' ) );

		$this->add_control(
			'toggle_color_hover',
			array(
				'label'     => 'رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pi' => '--ard-pi-accent-hover: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'toggle_bg_hover',
			array(
				'label'     => 'پس‌زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pi' => '--ard-pi-toggle-bg-hover: {{VALUE}};' ),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'toggle_icon_size',
			array(
				'label'      => 'اندازه آیکن',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 8, 'max' => 32 ) ),
				'separator'  => 'before',
				'selectors'  => array( '{{WRAPPER}} .ard-pi .ard-pi__toggle svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'show_icon' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'toggle_icon_gap',
			array(
				'label'      => 'فاصله آیکن و متن',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 24 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pi .ard-pi__toggle' => 'gap: {{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'show_icon' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'toggle_padding',
			array(
				'label'      => 'فاصله داخلی',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array( '{{WRAPPER}} .ard-pi .ard-pi__toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'toggle_radius',
			array(
				'label'      => 'گردی گوشه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pi .ard-pi__toggle' => 'border-radius: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'toggle_spacing',
			array(
				'label'      => 'فاصله از محتوا',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-pi__foot' => 'margin-top: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * خط نبود محتوا.
	 */
	protected function add_empty_style_section() {
		$this->start_controls_section(
			'section_style_empty',
			array(
				'label'     => 'متن نبود محتوا',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'empty_text!' => '' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'empty_typo',
				'selector' => '{{WRAPPER}} .ard-pi__empty',
			)
		);

		$this->add_control(
			'empty_color',
			array(
				'label'     => 'رنگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-pi__empty' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'empty_align',
			array(
				'label'     => 'چینش',
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'right'  => array( 'title' => 'راست', 'icon' => 'eicon-text-align-right' ),
					'center' => array( 'title' => 'وسط', 'icon' => 'eicon-text-align-center' ),
					'left'   => array( 'title' => 'چپ', 'icon' => 'eicon-text-align-left' ),
				),
				'selectors' => array( '{{WRAPPER}} .ard-pi__empty' => 'text-align: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();
	}

	/* ------------------------------------------------ خروجی */

	/**
	 * پوستهٔ جمع‌شونده دور HTML داخلی ویجت.
	 *
	 * @param array  $s        تنظیمات.
	 * @param string $modifier desc|specs
	 * @param string $inner    HTML داخلی که خود ویجت escape کرده.
	 * @param array  $data     صفت‌های data-* برای جاوااسکریپت.
	 */
	protected function render_shell( $s, $modifier, $inner, $data = array() ) {
		$collapse = 'yes' === $s['collapse'];
		$clip_id  = 'ard-pi-' . $this->get_id();

		$classes = array( 'ard-pi', 'ard-pi--' . $modifier );

		if ( $collapse ) {
			$classes[] = 'is-collapsible';
		}

		if ( $collapse && 'yes' === $s['show_fade'] ) {
			$classes[] = 'has-fade';
		}

		$attrs = '';

		foreach ( $data as $key => $value ) {
			$attrs .= ' data-' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
		}
		?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" dir="rtl"<?php echo $collapse ? ' data-ard-pi' : ''; ?><?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- بالا escape شده. ?>>
			<?php $this->render_box_title( $s ); ?>

			<div class="ard-pi__clip" id="<?php echo esc_attr( $clip_id ); ?>">
				<div class="ard-pi__inner">
					<?php echo $inner; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خود ویجت escape کرده. ?>
				</div>
			</div>

			<?php if ( $collapse ) : ?>
				<div class="ard-pi__foot">
					<button type="button" class="ard-pi__toggle" aria-expanded="false" aria-controls="<?php echo esc_attr( $clip_id ); ?>"
						data-more="<?php echo esc_attr( $s['more_text'] ); ?>" data-less="<?php echo esc_attr( $s['less_text'] ); ?>">
						<span class="ard-pi__toggle-text"><?php echo esc_html( $s['more_text'] ); ?></span>
						<?php if ( 'yes' === $s['show_icon'] ) : ?>
							<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M6 9l6 6 6-6"/></svg>
						<?php endif; ?>
					</button>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * باکس «محتوایی نیست» (بدون جمع‌شدن و دکمه).
	 *
	 * نامش با render_empty پایهٔ اردال فرق دارد: آن یکی فقط در ویرایشگر پیام
	 * می‌دهد، این یکی برای بازدیدکننده هم دیده می‌شود.
	 *
	 * @param array  $s
	 * @param string $modifier
	 */
	protected function render_empty_box( $s, $modifier ) {
		if ( '' === trim( (string) $s['empty_text'] ) ) {
			return;
		}
		?>
		<div class="ard-pi ard-pi--<?php echo esc_attr( $modifier ); ?> is-empty" dir="rtl">
			<?php $this->render_box_title( $s ); ?>
			<p class="ard-pi__empty"><?php echo esc_html( $s['empty_text'] ); ?></p>
		</div>
		<?php
	}

	/**
	 * @param array $s
	 */
	protected function render_box_title( $s ) {
		if ( '' === trim( (string) $s['title'] ) ) {
			return;
		}

		$tag = self::tag( $s['title_tag'], 'h3' );

		printf( '<%1$s class="ard-pi__title">%2$s</%1$s>', esc_html( $tag ), esc_html( $s['title'] ) );
	}
}
