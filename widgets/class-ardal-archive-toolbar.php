<?php
/**
 * ویجت نوار بالای فهرست محصولات — شمارش نتیجه، مرتب‌سازی و دکمه‌های موبایل.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Archive_Toolbar extends Ardal_Archive_Widget_Base {

	public function get_name() {
		return 'ardal-archive-toolbar';
	}

	public function get_title() {
		return 'اردال — نوار مرتب‌سازی آرشیو';
	}

	public function get_icon() {
		return 'eicon-menu-bar';
	}

	protected function register_controls() {

		/* ------------------------------------------------ شمارش */

		$this->start_controls_section(
			'section_count',
			array( 'label' => 'شمارش نتایج' )
		);

		$this->add_control(
			'show_count',
			array(
				'label'        => 'نمایش تعداد نتایج',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => 'بله',
				'label_off'    => 'خیر',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'count_template',
			array(
				'label'       => 'متن شمارش',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'نمایش {from} تا {to} از {total} کالا',
				'label_block' => true,
				'description' => 'کلیدها: {from}، {to}، {total}',
				'condition'   => array( 'show_count' => 'yes' ),
			)
		);

		$this->add_control(
			'count_empty',
			array(
				'label'       => 'متن نبودِ نتیجه',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'کالایی پیدا نشد',
				'label_block' => true,
				'condition'   => array( 'show_count' => 'yes' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ مرتب‌سازی */

		$this->start_controls_section(
			'section_sort',
			array( 'label' => 'مرتب‌سازی' )
		);

		$this->add_control(
			'show_sort',
			array(
				'label'        => 'نمایش مرتب‌سازی',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'sort_style',
			array(
				'label'     => 'شکل نمایش',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'links',
				'options'   => array(
					'links'  => 'لینک‌های کنارهم با خط زیر',
					'pills'  => 'دکمه‌های قرصی',
					'select' => 'منوی کشویی',
				),
				'condition' => array( 'show_sort' => 'yes' ),
			)
		);

		$this->add_control(
			'sort_keys',
			array(
				'label'       => 'گزینه‌های مرتب‌سازی',
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => Ardal_Archive::sort_labels(),
				'default'     => array( 'menu_order', 'popularity', 'date', 'price', 'price-desc' ),
				'label_block' => true,
				'description' => 'ترتیب انتخاب، ترتیب نمایش است. خالی یعنی همه.',
				'condition'   => array( 'show_sort' => 'yes' ),
			)
		);

		$this->add_control(
			'show_sort_cap',
			array(
				'label'        => 'نمایش برچسب «ترتیب»',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'show_sort' => 'yes', 'sort_style!' => 'select' ),
			)
		);

		$this->add_control(
			'sort_cap',
			array(
				'label'     => 'متن برچسب',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'ترتیب',
				'condition' => array( 'show_sort' => 'yes', 'show_sort_cap' => 'yes', 'sort_style!' => 'select' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ موبایل */

		$this->start_controls_section(
			'section_mobile',
			array( 'label' => 'موبایل' )
		);

		$this->add_control(
			'show_filter_btn',
			array(
				'label'        => 'دکمه بازکردن فیلترها',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'کشوی ویجت «فیلتر محصولات» را باز می‌کند.',
			)
		);

		$this->add_control(
			'filter_btn_text',
			array(
				'label'     => 'متن دکمه فیلتر',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'فیلتر محصولات',
				'condition' => array( 'show_filter_btn' => 'yes' ),
			)
		);

		$this->add_control(
			'show_sort_btn',
			array(
				'label'        => 'دکمه مرتب‌سازی (شیت پایینی)',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'sheet_title',
			array(
				'label'     => 'عنوان شیت مرتب‌سازی',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'مرتب‌سازی بر اساس',
				'condition' => array( 'show_sort_btn' => 'yes' ),
			)
		);

		$this->add_control(
			'ajax',
			array(
				'label'        => 'بارگذاری بدون رفرش صفحه (ایجکس)',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => 'بله',
				'label_off'    => 'خیر',
				'return_value' => 'yes',
				'description'  => 'مرتب‌سازی و صفحه‌بندی بدون بارگذاری دوبارهٔ صفحه اعمال می‌شوند.',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'ajax_regions',
			array(
				'label'       => 'سلکتور ناحیه‌های اضافه',
				'type'        => Controls_Manager::TEXT,
				'default'     => '.elementor-widget-loop-grid',
				'label_block' => true,
				'description' => 'ناحیه‌هایی بیرون از ویجت‌های اردال که باید تازه شوند؛ مثلاً «لوپ گرید» المنتور. با ویرگول جدا کنید.',
				'condition'   => array( 'ajax' => 'yes' ),
			)
		);

		$this->add_control(
			'ajax_scroll',
			array(
				'label'        => 'اسکرول به بالای نتایج',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'ajax' => 'yes' ),
			)
		);

		$this->add_control(
			'mobile_count',
			array(
				'label'        => 'نمایش شمارش در موبایل',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'show_count' => 'yes' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ استایل */

		$this->start_controls_section(
			'section_style_tool',
			array(
				'label' => 'نوار',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'boxed',
			array(
				'label'        => 'قاب سفید',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'count_color',
			array(
				'label'     => 'رنگ متن شمارش',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-tool__count' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'count_strong',
			array(
				'label'     => 'رنگ عددها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-tool__count b' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'count_typo',
				'label'    => 'قلم شمارش',
				'selector' => '{{WRAPPER}} .ard-tool__count',
			)
		);

		$this->add_control(
			'sort_color',
			array(
				'label'     => 'رنگ گزینه‌های مرتب‌سازی',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-tool__sort' => 'color:{{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sort_active',
			array(
				'label'     => 'رنگ گزینه فعال',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-tool' => '--ard-tool-active:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sort_line',
			array(
				'label'     => 'رنگ خط زیر گزینه فعال',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-tool' => '--ard-tool-line:{{VALUE}};',
				),
				'condition' => array( 'sort_style' => 'links' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sort_typo',
				'label'    => 'قلم مرتب‌سازی',
				'selector' => '{{WRAPPER}} .ard-tool__sort',
			)
		);

		$this->end_controls_section();

		$this->add_box_controls( '.ard-tool--boxed', 'tool_box', 'قاب نوار', array( 'top' => 14, 'right' => 22, 'bottom' => 14, 'left' => 22 ) );
	}

	protected function render() {
		if ( $this->require_woo() ) {
			return;
		}

		$s      = $this->get_settings_for_display();
		$stats  = Ardal_Archive::loop_stats();
		$active = Ardal_Archive::active_count();
		$sorts  = Ardal_Archive::sort_options( array_filter( (array) $s['sort_keys'] ) );

		$classes = array( 'ard-tool', 'ard-tool--' . $s['sort_style'] );

		if ( 'yes' === $s['boxed'] ) {
			$classes[] = 'ard-tool--boxed';
		}

		if ( 'yes' !== $s['mobile_count'] ) {
			$classes[] = 'ard-tool--hide-count-m';
		}

		printf(
			'<div class="%s" data-ard-region="toolbar"%s>',
			esc_attr( implode( ' ', $classes ) ),
			$this->ajax_attrs( $s ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- در همان تابع escape شده.
		);

		$this->render_mobile_bar( $s, $active );

		if ( 'yes' === $s['show_count'] ) {
			$this->render_count( $s, $stats );
		}

		if ( 'yes' === $s['show_sort'] && $sorts ) {
			$this->render_sorts( $s, $sorts );
		}

		echo '</div>';

		if ( 'yes' === $s['show_sort_btn'] && $sorts ) {
			$this->render_sheet( $s, $sorts );
		}
	}

	/**
	 * دکمه‌های موبایل.
	 *
	 * @param array $s
	 * @param int   $active
	 */
	protected function render_mobile_bar( $s, $active ) {
		if ( 'yes' !== $s['show_filter_btn'] && 'yes' !== $s['show_sort_btn'] ) {
			return;
		}

		echo '<div class="ard-tool__mobile">';

		if ( 'yes' === $s['show_filter_btn'] ) {
			printf(
				'<button type="button" class="ard-tool__mbtn ard-tool__mbtn--filter" data-ard-arch-open="filters" aria-controls="ard-filters" aria-expanded="false">%s<span>%s</span>%s</button>',
				Ardal_Archive::icon( 'filter', 18 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				esc_html( $s['filter_btn_text'] ),
				$active ? '<span class="ard-tool__badge">' . esc_html( Ardal_Helpers::fa_digits( $active ) ) . '</span>' : ''
			);
		}

		if ( 'yes' === $s['show_sort_btn'] ) {
			printf(
				'<button type="button" class="ard-tool__mbtn ard-tool__mbtn--sort" data-ard-arch-open="sort" aria-controls="ard-sort" aria-expanded="false" aria-label="مرتب‌سازی">%s</button>',
				Ardal_Archive::icon( 'sort', 18 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		echo '</div>';
	}

	/**
	 * متن شمارش نتایج.
	 *
	 * @param array $s
	 * @param array $stats
	 */
	protected function render_count( $s, $stats ) {
		echo '<p class="ard-tool__count woocommerce-result-count">';

		if ( $stats['total'] < 1 ) {
			echo esc_html( $s['count_empty'] );
			echo '</p>';

			return;
		}

		$text = strtr(
			(string) $s['count_template'],
			array(
				'{from}'  => '<b>' . esc_html( Ardal_Helpers::fa_digits( number_format_i18n( $stats['first'] ) ) ) . '</b>',
				'{to}'    => '<b>' . esc_html( Ardal_Helpers::fa_digits( number_format_i18n( $stats['last'] ) ) ) . '</b>',
				'{total}' => '<b>' . esc_html( Ardal_Helpers::fa_digits( number_format_i18n( $stats['total'] ) ) ) . '</b>',
			)
		);

		echo wp_kses( $text, array( 'b' => array(), 'span' => array( 'class' => array() ) ) );
		echo '</p>';
	}

	/**
	 * گزینه‌های مرتب‌سازی دسکتاپ.
	 *
	 * @param array $s
	 * @param array $sorts
	 */
	protected function render_sorts( $s, $sorts ) {
		echo '<div class="ard-tool__sorts">';

		if ( 'select' === $s['sort_style'] ) {
			echo '<label class="ard-tool__select">';
			printf( '<span>%s</span>', esc_html( $s['sort_cap'] ) );
			echo '<select data-ard-sort-select>';

			foreach ( $sorts as $option ) {
				printf(
					'<option value="%s"%s>%s</option>',
					esc_url( $option['url'] ),
					$option['current'] ? ' selected' : '',
					esc_html( $option['label'] )
				);
			}

			echo '</select></label></div>';

			return;
		}

		if ( 'yes' === $s['show_sort_cap'] && '' !== trim( (string) $s['sort_cap'] ) ) {
			printf(
				'<span class="ard-tool__sorts-cap">%s%s</span>',
				Ardal_Archive::icon( 'sort', 17 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				esc_html( $s['sort_cap'] )
			);
		}

		foreach ( $sorts as $option ) {
			printf(
				'<a class="ard-tool__sort%s" href="%s"%s>%s</a>',
				$option['current'] ? ' is-on' : '',
				esc_url( $option['url'] ),
				$option['current'] ? ' aria-current="true"' : '',
				esc_html( $option['label'] )
			);
		}

		echo '</div>';
	}

	/**
	 * شیت پایینی مرتب‌سازی در موبایل.
	 *
	 * @param array $s
	 * @param array $sorts
	 */
	protected function render_sheet( $s, $sorts ) {
		echo '<div class="ard-sheet" id="ard-sort" data-ard-sheet data-ard-region="sheet">';
		echo '<span class="ard-sheet__grip" aria-hidden="true"></span>';
		printf( '<span class="ard-sheet__title">%s</span>', esc_html( $s['sheet_title'] ) );
		echo '<ul class="ard-sheet__list">';

		foreach ( $sorts as $option ) {
			printf(
				'<li><a href="%s" class="%s"><span>%s</span>%s</a></li>',
				esc_url( $option['url'] ),
				$option['current'] ? 'is-on' : '',
				esc_html( $option['label'] ),
				$option['current'] ? Ardal_Archive::icon( 'check', 18, 2.4 ) : '' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}

		echo '</ul></div>';

		Ardal_Archive::render_scrim();
	}
}
