<?php
/**
 * ویجت فهرست ناحیه‌های صفحه.
 *
 * فقط فهرستی از «متن + لینک» است؛ شناسهٔ هر ناحیه را خود کاربر در «پیشرفته ←
 * CSS ID» همان بخش المنتور می‌گذارد و اینجا #id نوشته می‌شود. چسباندن فهرست
 * (sticky) کار این ویجت نیست و با گزینهٔ خود المنتور انجام می‌شود؛ کار ما
 * اسکرول نرم و فعال‌شدن آیتم هنگام رسیدن به ناحیه است.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

class Ardal_Widget_Toc extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-toc';
	}

	public function get_title() {
		return 'اردال — فهرست ناحیه‌ها';
	}

	public function get_icon() {
		return 'eicon-table-of-contents';
	}

	public function get_keywords() {
		return array( 'اردال', 'فهرست', 'ناوبری', 'لنگر', 'اسکرول', 'toc', 'anchor' );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-toc' );
	}

	public function get_script_depends() {
		return array( 'ardal-toc' );
	}

	protected function register_controls() {

		/* ------------------------------------------------ آیتم‌ها */

		$this->start_controls_section(
			'section_items',
			array( 'label' => 'آیتم‌های فهرست' )
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			array(
				'label'       => 'متن',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'بخش تازه',
				'placeholder' => 'مثلاً: مشخصات محصول',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => 'لینک ناحیه',
				'type'        => Controls_Manager::URL,
				'placeholder' => '#specs',
				'default'     => array( 'url' => '' ),
				'options'     => array( 'is_external', 'nofollow' ),
				'label_block' => true,
				'description' => 'شناسهٔ همان بخش با #؛ شناسه را در «پیشرفته ← CSS ID» خودِ بخش بگذارید. اگر ناحیه در صفحه نبود، آیتمش هم نمایش داده نمی‌شود.',
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => 'آیتم‌ها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ text }}}',
				'default'     => array(
					array( 'text' => 'معرفی', 'link' => array( 'url' => '#intro' ) ),
					array( 'text' => 'مشخصات', 'link' => array( 'url' => '#specs' ) ),
					array( 'text' => 'نظرها', 'link' => array( 'url' => '#reviews' ) ),
				),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => 'عنوان بالای فهرست',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'در این صفحه',
				'label_block' => true,
				'description' => 'خالی = بدون عنوان.',
			)
		);

		$this->add_tag_control(
			'title_tag',
			'تگ عنوان',
			'p',
			array( 'condition' => array( 'title!' => '' ) )
		);

		$this->add_control(
			'aria_label',
			array(
				'label'       => 'برچسب دسترس‌پذیری',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'فهرست بخش‌های این صفحه',
				'label_block' => true,
				'description' => 'متنی که صفحه‌خوان برای این ناوبری می‌خواند.',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ رفتار */

		$this->start_controls_section(
			'section_behavior',
			array( 'label' => 'رفتار' )
		);

		$this->add_control(
			'layout',
			array(
				'label'   => 'چیدمان',
				'type'    => Controls_Manager::SELECT,
				'default' => 'vertical',
				'options' => array(
					'vertical'   => 'عمودی (زیر هم)',
					'horizontal' => 'افقی (کنار هم، با اسکرول)',
				),
			)
		);

		$this->add_control(
			'spy',
			array(
				'label'        => 'آیتم با رسیدن به ناحیه فعال شود',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'smooth',
			array(
				'label'        => 'اسکرول نرم هنگام کلیک',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'offset',
			array(
				'label'       => 'فاصله از بالای صفحه',
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 500,
				'step'        => 5,
				'default'     => 90,
				'description' => 'به پیکسل. اگر هدر چسبان دارید ارتفاعش را بنویسید تا عنوان بخش زیر هدر پنهان نشود. نوار مدیریت وردپرس خودکار حساب می‌شود.',
			)
		);

		$this->add_control(
			'numbers',
			array(
				'label'        => 'شماره ابتدای هر آیتم',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ ظاهر */

		$this->start_controls_section(
			'section_style',
			array(
				'label' => 'ظاهر فهرست',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'item_typo',
				'label'    => 'قلم آیتم‌ها',
				'selector' => '{{WRAPPER}} .ard-toc__link',
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => 'فاصله آیتم‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-toc' => '--ard-toc-gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'pad_y',
			array(
				'label'      => 'فاصله داخلی عمودی آیتم',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-toc' => '--ard-toc-pad-y:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'pad_x',
			array(
				'label'      => 'فاصله داخلی افقی آیتم',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-toc' => '--ard-toc-pad-x:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'radius',
			array(
				'label'      => 'گردی گوشه آیتم',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-toc' => '--ard-toc-radius:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'max_height',
			array(
				'label'       => 'بیشترین ارتفاع فهرست',
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', 'vh' ),
				'range'       => array(
					'px' => array( 'min' => 100, 'max' => 900 ),
					'vh' => array( 'min' => 20, 'max' => 100 ),
				),
				'description' => 'برای فهرست عمودی بلند در حالت چسبان؛ خالی = بدون محدودیت.',
				'condition'   => array( 'layout' => 'vertical' ),
				'selectors'   => array(
					'{{WRAPPER}} .ard-toc' => '--ard-toc-max:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ رنگ‌ها */

		$this->start_controls_section(
			'section_colors',
			array(
				'label' => 'رنگ‌ها',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'color_tabs' );

		$this->start_controls_tab( 'color_normal', array( 'label' => 'عادی' ) );

		$this->add_control(
			'ink',
			array(
				'label'     => 'رنگ متن',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-toc' => '--ard-toc-ink:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'item_bg',
			array(
				'label'     => 'پس‌زمینه آیتم',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-toc' => '--ard-toc-bg:{{VALUE}};' ),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'color_hover', array( 'label' => 'شناور' ) );

		$this->add_control(
			'ink_hover',
			array(
				'label'     => 'رنگ متن هنگام اشاره',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-toc' => '--ard-toc-ink-hover:{{VALUE}};' ),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'color_active', array( 'label' => 'فعال' ) );

		$this->add_control(
			'active_ink',
			array(
				'label'     => 'رنگ متن آیتم فعال',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-toc' => '--ard-toc-active:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'active_bg',
			array(
				'label'     => 'پس‌زمینه آیتم فعال',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-toc' => '--ard-toc-active-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'marker',
			array(
				'label'     => 'رنگ نوار نشانه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-toc' => '--ard-toc-marker:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'marker_w',
			array(
				'label'      => 'ضخامت نوار نشانه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 10 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-toc' => '--ard-toc-marker-w:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'title_color',
			array(
				'label'     => 'رنگ عنوان فهرست',
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array( '{{WRAPPER}} .ard-toc' => '--ard-toc-title:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typo',
				'label'    => 'قلم عنوان فهرست',
				'selector' => '{{WRAPPER}} .ard-toc__title',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = $this->items( $s );

		if ( ! $items ) {
			$this->render_empty( 'هنوز آیتمی در فهرست نیست. از بخش «آیتم‌های فهرست» متن و لینک هر ناحیه را اضافه کنید؛ لینک همان شناسهٔ بخش است، مثل ‎#specs.' );

			return;
		}

		$horizontal = 'horizontal' === $s['layout'];
		$numbers    = 'yes' === $s['numbers'];
		$label      = '' !== trim( (string) $s['aria_label'] ) ? $s['aria_label'] : 'فهرست بخش‌های این صفحه';

		$classes = array( 'ard-toc', $horizontal ? 'ard-toc--h' : 'ard-toc--v' );

		if ( $numbers ) {
			$classes[] = 'ard-toc--numbered';
		}

		/*
		 * داخل ویرایشگر، شناسهٔ بخش‌ها هنوز گذاشته نشده و اسکریپت نباید آیتم‌ها
		 * را پنهان کند؛ وگرنه ویجت خودش را ناپدید می‌کند و دیگر قابل انتخاب
		 * نیست. پیش‌نمایش المنتور یک iframe جداست و از جاوااسکریپت تشخیصش
		 * مطمئن نیست، پس همین‌جا سمت سرور تصمیم می‌گیریم.
		 */
		$editor = \Elementor\Plugin::$instance->editor->is_edit_mode()
			|| \Elementor\Plugin::$instance->preview->is_preview_mode();
		?>
		<nav class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
			data-ard-toc
			data-offset="<?php echo esc_attr( max( 0, (int) $s['offset'] ) ); ?>"
			data-spy="<?php echo 'yes' === $s['spy'] ? '1' : '0'; ?>"
			data-smooth="<?php echo 'yes' === $s['smooth'] ? '1' : '0'; ?>"
			<?php echo $editor ? 'data-editor="1"' : ''; ?>
			aria-label="<?php echo esc_attr( $label ); ?>">

			<?php if ( '' !== trim( (string) $s['title'] ) ) : ?>
				<?php $tag = self::tag( $s['title_tag'], 'p' ); ?>
				<<?php echo esc_html( $tag ); ?> class="ard-toc__title"><?php echo esc_html( $s['title'] ); ?></<?php echo esc_html( $tag ); ?>>
			<?php endif; ?>

			<ul class="ard-toc__list" data-ard-toc-list>
				<?php foreach ( $items as $i => $item ) : ?>
					<li class="ard-toc__item">
						<a class="ard-toc__link" href="<?php echo esc_url( $item['url'] ); ?>" data-ard-toc-link
							<?php echo $item['external'] ? ' target="_blank"' : ''; ?>
							<?php echo $item['rel'] ? ' rel="' . esc_attr( $item['rel'] ) . '"' : ''; ?>>

							<?php if ( $numbers ) : ?>
								<span class="ard-toc__num" aria-hidden="true"><?php echo esc_html( Ardal_Helpers::fa_digits( $i + 1 ) ); ?></span>
							<?php endif; ?>

							<span class="ard-toc__text"><?php echo esc_html( $item['text'] ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>
		<?php
	}

	/**
	 * آیتم‌های تکرارشونده، پاک‌شده.
	 *
	 * آیتم بدون متن چیزی برای نشان دادن ندارد و آیتم بدون لینک جایی نمی‌برد،
	 * پس هر دو کنار گذاشته می‌شوند.
	 *
	 * @param array $s
	 * @return array
	 */
	private function items( $s ) {
		$out = array();

		foreach ( (array) $s['items'] as $row ) {
			$text = isset( $row['text'] ) ? trim( (string) $row['text'] ) : '';
			$link = isset( $row['link'] ) && is_array( $row['link'] ) ? $row['link'] : array();
			$url  = isset( $link['url'] ) ? trim( (string) $link['url'] ) : '';

			if ( '' === $text || '' === $url ) {
				continue;
			}

			$rel = array();

			if ( ! empty( $link['is_external'] ) ) {
				$rel[] = 'noopener';
			}

			if ( ! empty( $link['nofollow'] ) ) {
				$rel[] = 'nofollow';
			}

			$out[] = array(
				'text'     => $text,
				'url'      => $url,
				'external' => ! empty( $link['is_external'] ),
				'rel'      => implode( ' ', $rel ),
			);
		}

		return $out;
	}
}
