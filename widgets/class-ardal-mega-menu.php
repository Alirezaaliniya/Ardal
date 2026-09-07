<?php
/**
 * ویجت مگا منو — منوی وردپرس را داینامیک نمایش می‌دهد.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Ardal_Widget_Mega_Menu extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-mega-menu';
	}

	public function get_title() {
		return 'اردال — مگا منو';
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_keywords() {
		return array( 'اردال', 'مگا منو', 'منو', 'ناوبری', 'mega', 'menu', 'nav' );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-mega-menu' );
	}

	public function get_script_depends() {
		return array( 'ardal', 'ardal-mega-menu' );
	}

	/**
	 * نقطهٔ شکست موبایل: کلید => (برچسب، عرض بر حسب پیکسل).
	 *
	 * @return array
	 */
	private function breakpoints() {
		return array(
			'md' => array( 'زیر ۷۶۸ پیکسل (فقط موبایل)', 768 ),
			'lg' => array( 'زیر ۱۰۲۴ پیکسل (موبایل و تبلت)', 1024 ),
			'xl' => array( 'زیر ۱۲۰۰ پیکسل (تبلت بزرگ هم)', 1200 ),
		);
	}

	protected function register_controls() {
		$menus = Ardal_Mega_Menu::menu_options();

		$this->start_controls_section(
			'section_content',
			array( 'label' => 'منو' )
		);

		if ( empty( $menus ) ) {
			$this->add_control(
				'no_menu',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf(
						'هنوز هیچ منویی ساخته نشده است. از <a href="%s" target="_blank">نمایش ← فهرست‌ها</a> یک منو بسازید، سپس همین‌جا انتخابش کنید.',
						esc_url( admin_url( 'nav-menus.php' ) )
					),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				)
			);
		} else {
			$this->add_control(
				'menu',
				array(
					'label'       => 'انتخاب منو',
					'type'        => Controls_Manager::SELECT,
					'options'     => $menus,
					'default'     => array_key_first( $menus ),
					'label_block' => true,
					'description' => sprintf(
						'ساختار و تنظیمات مگا منو در <a href="%s" target="_blank">نمایش ← فهرست‌ها</a> انجام می‌شود؛ هر آیتم آنجا بخش «تنظیمات اردال» دارد.',
						esc_url( admin_url( 'nav-menus.php' ) )
					),
				)
			);
		}

		$this->add_control(
			'aria_label',
			array(
				'label'   => 'نام منو برای صفحه‌خوان',
				'type'    => Controls_Manager::TEXT,
				'default' => 'منوی اصلی',
			)
		);

		$this->add_control(
			'show_all',
			array(
				'label'        => 'دکمه «همه دسته‌بندی‌ها»',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'all_text',
			array(
				'label'     => 'متن دکمه',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'همه دسته‌بندی‌ها',
				'condition' => array( 'show_all' => 'yes' ),
			)
		);

		$this->add_control(
			'all_link',
			array(
				'label'     => 'لینک دکمه',
				'type'      => Controls_Manager::URL,
				'default'   => array( 'url' => '' ),
				'condition' => array( 'show_all' => 'yes' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_mobile',
			array( 'label' => 'نمایش در موبایل' )
		);

		$options = array();

		foreach ( $this->breakpoints() as $key => $data ) {
			$options[ $key ] = $data[0];
		}

		$this->add_control(
			'breakpoint',
			array(
				'label'       => 'از چه عرضی به منوی کشویی تبدیل شود',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'lg',
				'options'     => $options,
				'label_block' => true,
			)
		);

		$this->add_control(
			'mobile_style',
			array(
				'label'       => 'حالت باز شدن منو',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'wave',
				'options'     => array(
					'wave'   => 'پردهٔ موجی تمام‌صفحه',
					'drawer' => 'کشوی کناری ساده',
				),
				'label_block' => true,
				'description' => 'در حالت موجی، منو با یک پردهٔ سه‌لایه تمام صفحه را می‌پوشاند و دکمهٔ همبرگری به ضربدر تبدیل می‌شود.',
			)
		);

		$this->add_control(
			'burger_text',
			array(
				'label'       => 'متن کنار آیکون همبرگری',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'دسته‌بندی‌ها',
				'description' => 'خالی بگذارید تا فقط آیکون نمایش داده شود.',
			)
		);

		$this->add_control(
			'drawer_title',
			array(
				'label'   => 'عنوان بالای کشو',
				'type'    => Controls_Manager::TEXT,
				'default' => 'منوی فروشگاه',
			)
		);

		$this->add_control(
			'drawer_side',
			array(
				'label'     => 'کشو از کدام سمت باز شود',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'start',
				'options'   => array(
					'start' => 'راست (شروع متن)',
					'end'   => 'چپ (پایان متن)',
				),
				'condition' => array( 'mobile_style' => 'drawer' ),
			)
		);

		$this->add_control(
			'drawer_width',
			array(
				'label'      => 'عرض کشو',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array( 'min' => 240, 'max' => 480 ),
					'%'  => array( 'min' => 60, 'max' => 100 ),
				),
				'default'    => array( 'unit' => 'px', 'size' => 320 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-mm' => '--ard-mm-drawer-w: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'mobile_style' => 'drawer' ),
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
				'label'   => 'سبک نوار',
				'type'    => Controls_Manager::SELECT,
				'default' => 'bar',
				'options' => array(
					'bar'   => 'نوار با پس‌زمینه و خط زیرین',
					'plain' => 'ساده و بدون حاشیه',
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => 'چیدمان آیتم‌ها',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'start',
				'options'   => array(
					'start'   => 'ابتدای نوار',
					'center'  => 'وسط',
					'between' => 'پخش در کل عرض',
				),
				'selectors' => array(
					'{{WRAPPER}} .ard-mm__bar-inner' => '--ard-mm-justify: {{VALUE}};',
				),
				'selectors_dictionary' => array(
					'start'   => 'flex-start',
					'center'  => 'center',
					'between' => 'space-between',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => 'فاصلهٔ آیتم‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 6, 'max' => 48 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 22 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-mm' => '--ard-mm-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'bar_bg',
			array(
				'label'     => 'رنگ پس‌زمینه نوار',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-mm' => '--ard-mm-bar-bg: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => 'رنگ متن آیتم‌ها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-mm' => '--ard-mm-link: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_hover',
			array(
				'label'     => 'رنگ آیتم فعال / هاور',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-mm' => '--ard-mm-link-active: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'panel_bg',
			array(
				'label'     => 'رنگ پس‌زمینه پنل و کشو',
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .ard-mm' => '--ard-mm-panel-bg: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'wave_heading',
			array(
				'label'     => 'لایه‌های پردهٔ موجی',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array( 'mobile_style' => 'wave' ),
			)
		);

		$this->add_control(
			'wave_1',
			array(
				'label'     => 'لایه اول (زیرین)',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-mm' => '--ard-mm-wave-1: {{VALUE}};',
				),
				'condition' => array( 'mobile_style' => 'wave' ),
			)
		);

		$this->add_control(
			'wave_2',
			array(
				'label'     => 'لایه دوم (میانی)',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-mm' => '--ard-mm-wave-2: {{VALUE}};',
				),
				'condition' => array( 'mobile_style' => 'wave' ),
			)
		);

		$this->add_control(
			'wave_3',
			array(
				'label'       => 'لایه سوم (پس‌زمینهٔ منو)',
				'type'        => Controls_Manager::COLOR,
				'description' => 'متن منو روی همین لایه می‌نشیند.',
				'selectors'   => array(
					'{{WRAPPER}} .ard-mm' => '--ard-mm-wave-3: {{VALUE}};',
				),
				'condition'   => array( 'mobile_style' => 'wave' ),
			)
		);

		$this->add_responsive_control(
			'panel_pad',
			array(
				'label'      => 'فاصلهٔ داخلی پنل',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 10, 'max' => 48 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 22 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-mm' => '--ard-mm-panel-pad: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * خطوط دکمهٔ همبرگری متحرک (سه خط + دو خط ضربدر).
	 * هم روی دکمهٔ نوار و هم روی دکمهٔ بستنِ داخل پرده استفاده می‌شود.
	 *
	 * @return string
	 */
	private function burger_lines() {
		$out = '<span class="ard-mm__lines" aria-hidden="true">';

		foreach ( array( '01', '02', '03', 'cross01', 'cross02' ) as $line ) {
			$out .= sprintf(
				'<span class="ard-mm__line ard-mm__line--%1$s"><span class="ard-mm__line-in ard-mm__line-in--%1$s"></span></span>',
				esc_attr( $line )
			);
		}

		return $out . '</span>';
	}

	/**
	 * پردهٔ موجی: سه مسیر SVG که جاوااسکریپت شکل‌شان را کادر به کادر می‌سازد.
	 *
	 * @return string
	 */
	private function wave_overlay( $classes ) {
		$path = '<path class="ard-mm__wave-path" d="M 0 100 C 50 100 50 100 100 100 V 100 H 0"></path>';

		return sprintf(
			'<svg class="%s" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true" focusable="false" data-ard-mm-wave>%s</svg>',
			esc_attr( implode( ' ', $classes ) ),
			str_repeat( $path, 3 )
		);
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$menu = isset( $s['menu'] ) ? (string) $s['menu'] : '';

		if ( ! Ardal_Mega_Menu::menu_exists( $menu ) ) {
			$this->render_empty( 'ابتدا از «نمایش ← فهرست‌ها» یک منو بسازید و سپس در تنظیمات این ویجت انتخابش کنید.' );

			return;
		}

		$breakpoints = $this->breakpoints();
		$bp_key      = isset( $breakpoints[ $s['breakpoint'] ] ) ? $s['breakpoint'] : 'lg';
		$bp_px       = $breakpoints[ $bp_key ][1];

		$uid    = 'ard-mm-' . $this->get_id();
		$burger = trim( (string) $s['burger_text'] );
		$title  = trim( (string) $s['drawer_title'] );
		$wave   = 'drawer' !== $s['mobile_style'];
		$side   = 'end' === $s['drawer_side'] ? 'end' : 'start';

		$root_cls = array( 'ard-mm', 'ard-mm--' . $s['variant'], 'ard-mm--bp-' . $bp_key );
		$nav_cls  = array( 'ard-mm__nav', 'ard-mm__nav--bp-' . $bp_key );

		if ( $wave ) {
			$root_cls[] = 'ard-mm--wave';
			$nav_cls[]  = 'ard-mm__nav--wave';
		} else {
			$root_cls[] = 'ard-mm--drawer';
			$nav_cls[]  = 'ard-mm__nav--drawer';
			$nav_cls[]  = 'ard-mm__nav--' . $side;
		}
		?>
		<nav
			class="<?php echo esc_attr( implode( ' ', $root_cls ) ); ?>"
			aria-label="<?php echo esc_attr( '' !== trim( (string) $s['aria_label'] ) ? $s['aria_label'] : 'منوی اصلی' ); ?>"
			data-ard-mega-menu
			data-breakpoint="<?php echo esc_attr( $bp_px ); ?>"
		>
			<div class="ard-mm__bar">
				<div class="ard-wrap ard-mm__bar-inner">
					<button
						type="button"
						class="ard-mm__burger<?php echo $wave ? ' ard-mm__burger--wave' : ''; ?>"
						aria-expanded="false"
						aria-controls="<?php echo esc_attr( $uid ); ?>"
						data-ard-mm-burger
					>
						<?php
						echo $wave
							? $this->burger_lines() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خروجی ثابت و escape‌شده.
							: Ardal_Helpers::icon( 'menu', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
						<?php if ( '' !== $burger ) : ?>
							<span class="ard-mm__burger-text"><?php echo esc_html( $burger ); ?></span>
						<?php else : ?>
							<span class="ard-sr">باز کردن منو</span>
						<?php endif; ?>
					</button>

					<?php
					if ( 'yes' === $s['show_all'] && '' !== trim( (string) $s['all_text'] ) ) :
						$all_tag = Ardal_Helpers::link_tag( $s['all_link'] );
						?>
						<<?php echo esc_html( $all_tag ); ?> class="ard-mm__all"<?php echo Ardal_Helpers::link_attrs( $s['all_link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
							<?php echo Ardal_Helpers::icon( 'grid', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php echo esc_html( $s['all_text'] ); ?>
						</<?php echo esc_html( $all_tag ); ?>>
					<?php endif; ?>

					<div
						class="<?php echo esc_attr( implode( ' ', $nav_cls ) ); ?>"
						id="<?php echo esc_attr( $uid ); ?>"
						data-ard-mm-nav
					>
						<div class="ard-mm__nav-inner">
							<div class="ard-mm__drawer-head">
								<span class="ard-mm__drawer-title"><?php echo esc_html( '' !== $title ? $title : 'منو' ); ?></span>
								<button
									type="button"
									class="ard-mm__close<?php echo $wave ? ' ard-mm__close--wave' : ''; ?>"
									aria-label="بستن منو"
									data-ard-mm-close
								>
									<?php if ( $wave ) : ?>
										<?php echo $this->burger_lines(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<?php else : ?>
										<span aria-hidden="true">&times;</span>
									<?php endif; ?>
								</button>
							</div>

							<?php Ardal_Mega_Menu::render_menu( $menu ); ?>
						</div>
					</div>
				</div>
			</div>

			<?php if ( $wave ) : ?>
				<?php echo $this->wave_overlay( array( 'ard-mm__wave', 'ard-mm__wave--bp-' . $bp_key ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<div class="ard-mm__scrim ard-mm__scrim--bp-<?php echo esc_attr( $bp_key ); ?>" data-ard-mm-scrim hidden></div>
			<?php endif; ?>
		</nav>
		<?php
	}
}
