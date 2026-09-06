<?php
/**
 * ویجت نوار پایین موبایل.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Mobile_Nav extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-mobile-nav';
	}

	public function get_title() {
		return 'اردال — نوار پایین موبایل';
	}

	public function get_icon() {
		return 'eicon-device-mobile';
	}

	public function get_keywords() {
		return array( 'اردال', 'موبایل', 'نوار پایین', 'mobile', 'bottom nav' );
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
				'default' => 'home',
				'options' => Ardal_Helpers::icon_options(),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'       => 'برچسب',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'خانه',
				'label_block' => true,
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
			'active',
			array(
				'label'        => 'آیتم فعال',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$repeater->add_control(
			'badge_source',
			array(
				'label'   => 'نشان عددی',
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'   => 'ندارد',
					'cart'   => 'تعداد سبد خرید ووکامرس',
					'manual' => 'عدد ثابت',
				),
			)
		);

		$repeater->add_control(
			'badge_number',
			array(
				'label'     => 'عدد',
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
					array( 'icon' => 'home', 'title' => 'خانه', 'active' => 'yes' ),
					array( 'icon' => 'grid', 'title' => 'دسته‌ها' ),
					array( 'icon' => 'cart', 'title' => 'سبد خرید', 'badge_source' => 'cart' ),
					array( 'icon' => 'user', 'title' => 'حساب من' ),
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
			'sticky',
			array(
				'label'        => 'چسبیده به پایین صفحه',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'breakpoint',
			array(
				'label'       => 'نمایش تا این عرض (px)',
				'type'        => Controls_Manager::NUMBER,
				'default'     => 767,
				'min'         => 0,
				'max'         => 1600,
				'description' => 'برای نمایش در همه اندازه‌ها، عدد بزرگی مثل ۹۹۹۹ بگذارید.',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = is_array( $s['items'] ) ? $s['items'] : array();

		if ( empty( $items ) ) {
			$this->render_empty( 'حداقل یک آیتم اضافه کنید.' );

			return;
		}

		$breakpoint = max( 0, (int) $s['breakpoint'] );
		$scope      = 'ard-mnav-' . $this->get_id();
		$classes    = array( 'ard-mnav', $scope );

		if ( 'yes' === $s['sticky'] ) {
			$classes[] = 'ard-mnav--sticky';
		}

		// نقطه شکست قابل تنظیم است، پس media query را همین‌جا و محدود به همین ویجت چاپ می‌کنیم.
		if ( 767 !== $breakpoint ) {
			printf(
				'<style>@media(max-width:767px){.%1$s{display:none}}@media(max-width:%2$dpx){.%1$s{display:grid}}</style>',
				esc_attr( $scope ),
				(int) $breakpoint
			);
		}
		?>
		<nav
			class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
			style="--ard-mnav-cols:<?php echo count( $items ); ?>"
			aria-label="ناوبری موبایل"
		>
			<?php
			foreach ( $items as $item ) :
				$badge = $this->badge_number( $item );
				$tag   = ! empty( $item['link']['url'] ) ? 'a' : 'div';
				?>
				<<?php echo esc_html( $tag ); ?>
					class="ard-mnav__item<?php echo 'yes' === $item['active'] ? ' is-active' : ''; ?>"
					<?php echo 'a' === $tag ? Ardal_Helpers::link_attrs( $item['link'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo 'yes' === $item['active'] ? ' aria-current="page"' : ''; ?>
				>
					<span class="ard-mnav__icon">
						<?php echo Ardal_Helpers::icon( $item['icon'], 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php if ( null !== $badge && $badge > 0 ) : ?>
							<span class="ard-mnav__badge"><?php echo esc_html( Ardal_Helpers::fa_digits( $badge ) ); ?></span>
						<?php endif; ?>
					</span>
					<span class="ard-mnav__label"><?php echo esc_html( $item['title'] ); ?></span>
				</<?php echo esc_html( $tag ); ?>>
			<?php endforeach; ?>
		</nav>
		<?php
	}

	/**
	 * @param array $item
	 * @return int|null
	 */
	private function badge_number( $item ) {
		if ( 'manual' === $item['badge_source'] ) {
			return (int) $item['badge_number'];
		}

		if ( 'cart' === $item['badge_source'] && Ardal_Helpers::has_woo() && function_exists( 'WC' ) && WC()->cart ) {
			return (int) WC()->cart->get_cart_contents_count();
		}

		return null;
	}
}
