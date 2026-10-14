<?php
/**
 * Elementor bottom navigation bar.
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
use Elementor\Icons_Manager;
use Elementor\Repeater;

class Ardal_Widget_Mobile_Nav extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-mobile-nav';
	}

	public function get_title() {
		return 'اردال — نوار پایین موبایل';
	}

	public function get_icon() {
		return 'eicon-navigation-horizontal';
	}

	public function get_keywords() {
		return array( 'اردال', 'نوار پایین', 'باتم بار', 'bottom bar', 'mobile navigation' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'آیتم‌های نوار' )
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'title',
			array(
				'label'       => 'عنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'خانه',
				'label_block' => true,
			)
		);
		$repeater->add_control(
			'icon',
			array(
				'label'   => 'آیکون آماده',
				'type'    => Controls_Manager::SELECT,
				'default' => 'home',
				'options' => Ardal_Helpers::icon_options(),
			)
		);
		$repeater->add_control(
			'custom_icon',
			array(
				'label'            => 'یا آیکون دلخواه',
				'type'             => Controls_Manager::ICONS,
				'default'          => array(),
				'recommended'      => array( 'fa-solid' ),
				'skin'             => 'inline',
				'label_block'      => false,
			)
		);
		$repeater->add_control(
			'link',
			array(
				'label'       => 'لینک صفحه',
				'type'        => Controls_Manager::URL,
				'default'     => array( 'url' => '' ),
				'placeholder' => 'https://example.com/page/',
			)
		);
		$repeater->add_control(
			'active_mode',
			array(
				'label'   => 'تشخیص آیتم فعال',
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => array(
					'auto'   => 'خودکار بر اساس آدرس صفحه',
					'manual' => 'انتخاب دستی',
				),
				'description' => 'در حالت خودکار، لینک آیتم با آدرس صفحه جاری مقایسه می‌شود. برای لینک‌های یکسان از حالت دستی استفاده کنید.',
			)
		);
		$repeater->add_control(
			'active',
			array(
				'label'        => 'این آیتم فعال باشد',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'condition'    => array( 'active_mode' => 'manual' ),
			)
		);
		$repeater->add_control(
			'badge_source',
			array(
				'label'   => 'نشان عددی',
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'   => 'بدون نشان',
					'cart'   => 'تعداد سبد خرید ووکامرس',
					'manual' => 'عدد دلخواه',
				),
			)
		);
		$repeater->add_control(
			'badge_number',
			array(
				'label'     => 'عدد نشان',
				'type'      => Controls_Manager::NUMBER,
				'default'   => 2,
				'min'       => 0,
				'condition' => array( 'badge_source' => 'manual' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => 'آیتم‌ها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array( 'icon' => 'home', 'title' => 'خانه', 'link' => array( 'url' => '' ) ),
					array( 'icon' => 'grid', 'title' => 'دسته‌بندی', 'link' => array( 'url' => '' ) ),
					array( 'icon' => 'cart', 'title' => 'سبدخرید', 'link' => array( 'url' => '' ), 'badge_source' => 'cart' ),
					array( 'icon' => 'user', 'title' => 'حساب کاربری', 'link' => array( 'url' => '' ) ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			array( 'label' => 'چیدمان و نمایش', 'tab' => Controls_Manager::TAB_CONTENT )
		);
		$this->add_control(
			'position',
			array(
				'label'   => 'جایگاه',
				'type'    => Controls_Manager::SELECT,
				'default' => 'fixed',
				'options' => array( 'fixed' => 'ثابت پایین صفحه', 'normal' => 'در محل ویجت' ),
			)
		);
		$this->add_control(
			'visibility',
			array(
				'label'   => 'نمایش در',
				'type'    => Controls_Manager::SELECT,
				'default' => 'mobile',
				'options' => array( 'mobile' => 'موبایل', 'tablet' => 'موبایل و تبلت', 'all' => 'همه دستگاه‌ها' ),
			)
		);
		$this->add_control(
			'breakpoint',
			array(
				'label'      => 'حداکثر عرض نمایش (px)',
				'type'       => Controls_Manager::NUMBER,
				'min'        => 0,
				'max'        => 1600,
				'default'    => 1023,
				'description' => 'برای نمایش روی همه اندازه‌ها، حالت «همه دستگاه‌ها» را انتخاب کنید.',
				'condition'  => array( 'visibility!' => 'all' ),
			)
		);
		$this->add_responsive_control(
			'bar_width',
			array(
				'label'      => 'حداکثر عرض نوار',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array( 'px' => array( 'min' => 240, 'max' => 1200 ), '%' => array( 'min' => 50, 'max' => 100 ) ),
				'default'    => array( 'unit' => '%', 'size' => 100 ),
				'selectors'  => array( '{{WRAPPER}} .ard-mnav' => 'max-width:{{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_responsive_control(
			'bar_bottom',
			array(
				'label'      => 'فاصله از پایین صفحه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 100 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 14 ),
				'selectors'  => array( '{{WRAPPER}} .ard-mnav--fixed' => 'inset-block-end:{{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'position' => 'fixed' ),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_bar',
			array( 'label' => 'استایل نوار', 'tab' => Controls_Manager::TAB_STYLE )
		);
		$this->add_control(
			'bar_bg',
			array( 'label' => 'رنگ پس‌زمینه', 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => array( '{{WRAPPER}} .ard-mnav' => 'background-color:{{VALUE}};' ) )
		);
		$this->add_group_control( Group_Control_Border::get_type(), array( 'name' => 'bar_border', 'selector' => '{{WRAPPER}} .ard-mnav' ) );
		$this->add_responsive_control(
			'bar_radius',
			array(
				'label' => 'گردی گوشه‌ها', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', '%' ),
				'default' => array( 'top' => 24, 'right' => 24, 'bottom' => 24, 'left' => 24, 'unit' => 'px', 'isLinked' => true ),
				'selectors' => array( '{{WRAPPER}} .ard-mnav' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), array( 'name' => 'bar_shadow', 'selector' => '{{WRAPPER}} .ard-mnav' ) );
		$this->add_responsive_control(
			'bar_padding',
			array(
				'label' => 'فاصله داخلی', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'em' ),
				'default' => array( 'top' => 11, 'right' => 8, 'bottom' => 11, 'left' => 8, 'unit' => 'px', 'isLinked' => false ),
				'selectors' => array( '{{WRAPPER}} .ard-mnav' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} calc({{BOTTOM}}{{UNIT}} + env(safe-area-inset-bottom,0px)) {{LEFT}}{{UNIT}};' ),
			)
		);
		$this->add_responsive_control(
			'item_gap',
			array(
				'label' => 'فاصله آیکون و عنوان', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ),
				'range' => array( 'px' => array( 'min' => 0, 'max' => 24 ) ), 'default' => array( 'unit' => 'px', 'size' => 5 ),
				'selectors' => array( '{{WRAPPER}} .ard-mnav__item' => 'gap:{{SIZE}}{{UNIT}};' ),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_items',
			array( 'label' => 'استایل آیتم‌ها', 'tab' => Controls_Manager::TAB_STYLE )
		);
		$this->add_control( 'item_color', array( 'label' => 'رنگ عادی', 'type' => Controls_Manager::COLOR, 'default' => '#737373', 'selectors' => array( '{{WRAPPER}} .ard-mnav__item' => 'color:{{VALUE}};' ) ) );
		$this->add_control( 'active_color', array( 'label' => 'رنگ فعال', 'type' => Controls_Manager::COLOR, 'default' => '#f20d5b', 'selectors' => array( '{{WRAPPER}} .ard-mnav__item.is-active' => 'color:{{VALUE}};' ) ) );
		$this->add_control( 'active_bg', array( 'label' => 'پس‌زمینه آیتم فعال', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-mnav__item.is-active' => 'background-color:{{VALUE}};' ) ) );
		$this->add_responsive_control(
			'item_radius',
			array(
				'label' => 'گردی پس‌زمینه آیتم', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ),
				'range' => array( 'px' => array( 'min' => 0, 'max' => 30 ) ), 'default' => array( 'unit' => 'px', 'size' => 12 ),
				'selectors' => array( '{{WRAPPER}} .ard-mnav__item' => 'border-radius:{{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_responsive_control(
			'item_padding',
			array(
				'label' => 'فاصله داخلی آیتم', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'em' ),
				'default' => array( 'top' => 2, 'right' => 2, 'bottom' => 2, 'left' => 2, 'unit' => 'px', 'isLinked' => true ),
				'selectors' => array( '{{WRAPPER}} .ard-mnav__item' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);
		$this->add_responsive_control(
			'icon_size',
			array(
				'label' => 'اندازه آیکون', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ),
				'range' => array( 'px' => array( 'min' => 12, 'max' => 48 ) ), 'default' => array( 'unit' => 'px', 'size' => 20 ),
				'selectors' => array( '{{WRAPPER}} .ard-mnav__icon > svg' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};', '{{WRAPPER}} .ard-mnav__icon > i' => 'font-size:{{SIZE}}{{UNIT}};' ),
			)
		);
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'label_typography', 'selector' => '{{WRAPPER}} .ard-mnav__label' ) );
		$this->add_control( 'label_color', array( 'label' => 'رنگ عنوان عادی', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-mnav__label' => 'color:{{VALUE}};' ) ) );
		$this->add_control( 'active_label_color', array( 'label' => 'رنگ عنوان فعال', 'type' => Controls_Manager::COLOR, 'selectors' => array( '{{WRAPPER}} .ard-mnav__item.is-active .ard-mnav__label' => 'color:{{VALUE}};' ) ) );
		$this->add_control( 'badge_bg', array( 'label' => 'پس‌زمینه نشان عددی', 'type' => Controls_Manager::COLOR, 'default' => '#f20d5b', 'selectors' => array( '{{WRAPPER}} .ard-mnav__badge' => 'background-color:{{VALUE}};' ) ) );
		$this->add_control( 'badge_color', array( 'label' => 'رنگ عدد نشان', 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => array( '{{WRAPPER}} .ard-mnav__badge' => 'color:{{VALUE}};' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = isset( $settings['items'] ) && is_array( $settings['items'] ) ? $settings['items'] : array();

		if ( empty( $items ) ) {
			$this->render_empty( 'حداقل یک آیتم به نوار اضافه کنید.' );
			return;
		}

		$visibility = isset( $settings['visibility'] ) ? $settings['visibility'] : 'mobile';
		$classes    = array( 'ard-mnav', 'ard-mnav--' . ( 'normal' === $settings['position'] ? 'normal' : 'fixed' ) );
		$classes[]  = 'ard-mnav--' . sanitize_html_class( $visibility );
		$scope      = 'ard-mnav-' . $this->get_id();
		$classes[]  = $scope;
		$breakpoint = isset( $settings['breakpoint'] ) ? max( 0, (int) $settings['breakpoint'] ) : 1023;

		if ( 'all' !== $visibility ) {
			$min_width = 'tablet' === $visibility ? $breakpoint + 1 : min( 768, $breakpoint + 1 );
			printf( '<style>@media(min-width:%1$dpx){.%2$s{display:none!important}}@media(max-width:%3$dpx){.%2$s{display:grid}}</style>', (int) $min_width, esc_attr( $scope ), (int) $breakpoint );
		} else {
			printf( '<style>.%s{display:grid}</style>', esc_attr( $scope ) );
		}
		?>
		<nav class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" style="--ard-mnav-cols:<?php echo esc_attr( count( $items ) ); ?>" aria-label="ناوبری اصلی">
			<?php foreach ( $items as $item ) :
				$title = isset( $item['title'] ) ? trim( (string) $item['title'] ) : '';
				$url   = isset( $item['link']['url'] ) ? trim( (string) $item['link']['url'] ) : '';
				if ( '' === $title ) {
					continue;
				}
				if ( '' === $url ) {
					$url = $this->default_item_url( $item );
					if ( '' !== $url ) {
						$item['link']['url'] = $url;
					}
				}
				$active = $this->is_item_active( $item, $url );
				$badge  = $this->badge_number( $item );
				$tag    = '' !== $url ? 'a' : 'span';
				?>
				<<?php echo esc_html( $tag ); ?> class="ard-mnav__item<?php echo $active ? ' is-active' : ''; ?>" <?php echo '' !== $url ? Ardal_Helpers::link_attrs( $item['link'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo $active ? 'aria-current="page"' : ''; ?>>
					<span class="ard-mnav__icon">
						<?php if ( ! empty( $item['custom_icon']['value'] ) ) : ?>
							<?php Icons_Manager::render_icon( $item['custom_icon'], array( 'aria-hidden' => 'true' ) ); ?>
						<?php else : ?>
							<?php echo Ardal_Helpers::icon( isset( $item['icon'] ) ? $item['icon'] : 'grid', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php endif; ?>
						<?php if ( null !== $badge && $badge > 0 ) : ?><span class="ard-mnav__badge"><?php echo esc_html( Ardal_Helpers::fa_digits( $badge ) ); ?></span><?php endif; ?>
					</span>
					<span class="ard-mnav__label"><?php echo esc_html( $title ); ?></span>
				</<?php echo esc_html( $tag ); ?>>
			<?php endforeach; ?>
		</nav>
		<?php
	}

	/**
	 * Supply useful destinations for the starter navigation items.
	 *
	 * @param array $item Item settings.
	 * @return string
	 */
	private function default_item_url( $item ) {
		$icon = isset( $item['icon'] ) ? $item['icon'] : '';
		switch ( $icon ) {
			case 'home':
				return home_url( '/' );
			case 'grid':
				return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : '';
			case 'cart':
				return function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '';
			case 'user':
				return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : '';
		}

		return '';
	}

	/**
	 * Match the current URL to a repeater item unless manual selection is enabled.
	 *
	 * @param array  $item Item settings.
	 * @param string $url Item URL.
	 * @return bool
	 */
	private function is_item_active( $item, $url ) {
		if ( isset( $item['active_mode'] ) && 'manual' === $item['active_mode'] ) {
			return isset( $item['active'] ) && 'yes' === $item['active'];
		}

		if ( '' === $url || is_admin() || ! function_exists( 'home_url' ) ) {
			return false;
		}

		$current = ( is_ssl() ? 'https://' : 'http://' ) . ( isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '' ) . ( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/' );
		$target  = wp_parse_url( $url );
		$now     = wp_parse_url( $current );
		if ( empty( $target['path'] ) || empty( $now['path'] ) ) {
			return untrailingslashit( $url ) === untrailingslashit( $current );
		}

		$target_host = isset( $target['host'] ) ? strtolower( $target['host'] ) : strtolower( (string) wp_parse_url( home_url(), PHP_URL_HOST ) );
		$current_host = isset( $now['host'] ) ? strtolower( $now['host'] ) : '';
		if ( '' !== $target_host && '' !== $current_host && $target_host !== $current_host ) {
			return false;
		}

		$target_path = untrailingslashit( rawurldecode( $target['path'] ) );
		$current_path = untrailingslashit( rawurldecode( $now['path'] ) );
		return $target_path === $current_path;
	}

	/**
	 * @param array $item Item settings.
	 * @return int|null
	 */
	private function badge_number( $item ) {
		$source = isset( $item['badge_source'] ) ? $item['badge_source'] : 'none';
		if ( 'manual' === $source ) {
			return isset( $item['badge_number'] ) ? (int) $item['badge_number'] : 0;
		}

		if ( 'cart' === $source && Ardal_Helpers::has_woo() && function_exists( 'WC' ) && WC()->cart ) {
			return (int) WC()->cart->get_cart_contents_count();
		}

		return null;
	}
}
