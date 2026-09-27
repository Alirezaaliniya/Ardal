<?php
/**
 * ویجت هدر فروشگاه: لوگو، جستجو، حساب کاربری و سبد خرید.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Ardal_Widget_Header extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-header';
	}

	public function get_title() {
		return 'اردال — هدر فروشگاه';
	}

	public function get_icon() {
		return 'eicon-header';
	}

	public function get_keywords() {
		return array( 'اردال', 'هدر', 'لوگو', 'جستجو', 'سبد خرید', 'header' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_brand',
			array( 'label' => 'برند' )
		);

		$this->add_control(
			'logo',
			array(
				'label'   => 'لوگو',
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => ARDAL_URL . 'assets/img/logo.png' ),
			)
		);

		$this->add_control(
			'logo_size',
			array(
				'label'      => 'اندازه لوگو',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 28, 'max' => 96 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 52 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-header__logo img' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'brand_name',
			array(
				'label'   => 'نام فروشگاه',
				'type'    => Controls_Manager::TEXT,
				'default' => 'اسپرت‌لند',
			)
		);

		$this->add_control(
			'brand_tagline',
			array(
				'label'   => 'شعار زیر نام',
				'type'    => Controls_Manager::TEXT,
				'default' => 'فروشگاه تخصصی آنلاین',
			)
		);

		$this->add_control(
			'brand_link',
			array(
				'label'   => 'لینک لوگو',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => home_url( '/' ) ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_search',
			array( 'label' => 'جستجو' )
		);

		$this->add_control(
			'show_search',
			array(
				'label'        => 'نمایش جعبه جستجو',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'search_placeholder',
			array(
				'label'       => 'متن راهنمای جستجو',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'جستجوی محصول، برند یا دسته‌بندی…',
				'label_block' => true,
				'condition'   => array( 'show_search' => 'yes' ),
			)
		);

		$this->add_control(
			'search_button',
			array(
				'label'     => 'متن دکمه جستجو',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'جستجو',
				'condition' => array( 'show_search' => 'yes' ),
			)
		);

		$this->add_control(
			'search_scope',
			array(
				'label'     => 'دامنه جستجو',
				'type'      => Controls_Manager::SELECT,
				'default'   => Ardal_Helpers::has_woo() ? 'product' : 'all',
				'options'   => array(
					'all'     => 'کل سایت',
					'product' => 'فقط محصولات',
				),
				'condition' => array( 'show_search' => 'yes' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_actions',
			array( 'label' => 'حساب و سبد خرید' )
		);

		$this->add_control(
			'show_account',
			array(
				'label'        => 'نمایش ورود / ثبت‌نام',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'account_text',
			array(
				'label'     => 'متن حساب کاربری',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'ورود / ثبت‌نام',
				'condition' => array( 'show_account' => 'yes' ),
			)
		);

		$this->add_control(
			'account_link',
			array(
				'label'     => 'لینک حساب کاربری',
				'type'      => Controls_Manager::URL,
				'default'   => array( 'url' => Ardal_Helpers::has_woo() && function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url() ),
				'condition' => array( 'show_account' => 'yes' ),
			)
		);

		$this->add_control(
			'show_cart',
			array(
				'label'        => 'نمایش سبد خرید',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'cart_text',
			array(
				'label'     => 'متن سبد خرید',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'سبد خرید',
				'condition' => array( 'show_cart' => 'yes' ),
			)
		);

		$this->add_control(
			'cart_count_source',
			array(
				'label'       => 'تعداد آیتم سبد',
				'type'        => Controls_Manager::SELECT,
				'default'     => Ardal_Helpers::has_woo() ? 'woo' : 'manual',
				'options'     => array(
					'woo'    => 'زنده از ووکامرس',
					'manual' => 'عدد ثابت (برای پیش‌نمایش)',
				),
				'condition'   => array( 'show_cart' => 'yes' ),
				'description' => Ardal_Helpers::has_woo() ? '' : 'ووکامرس فعال نیست؛ فقط حالت ثابت کار می‌کند.',
			)
		);

		$this->add_control(
			'cart_count',
			array(
				'label'     => 'عدد سبد',
				'type'      => Controls_Manager::NUMBER,
				'default'   => 2,
				'min'       => 0,
				'condition' => array(
					'show_cart'         => 'yes',
					'cart_count_source' => 'manual',
				),
			)
		);

		$this->add_control(
			'cart_link',
			array(
				'label'     => 'لینک سبد خرید',
				'type'      => Controls_Manager::URL,
				'default'   => array( 'url' => Ardal_Helpers::has_woo() && function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '#' ),
				'condition' => array( 'show_cart' => 'yes' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_mobile',
			array( 'label' => 'موبایل' )
		);

		$this->add_control(
			'mobile_menu_target',
			array(
				'label'       => 'شناسه منوی همبرگری',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => 'اگر ویجت «منوی دسته‌بندی‌ها» را در همین صفحه دارید، شناسه CSS آن را وارد کنید تا دکمه همبرگری بازش کند (بدون #).',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$count = $this->cart_count( $s );
		?>
		<header class="ard-header">
			<div class="ard-wrap ard-header__inner">

				<button
					type="button"
					class="ard-header__burger"
					aria-label="باز کردن منو"
					<?php echo '' !== trim( (string) $s['mobile_menu_target'] ) ? ' data-ard-toggle="' . esc_attr( $s['mobile_menu_target'] ) . '"' : ''; ?>
				>
					<span></span><span></span><span></span>
				</button>

				<a class="ard-header__logo"<?php echo Ardal_Helpers::link_attrs( $s['brand_link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php if ( ! empty( $s['logo']['url'] ) ) : ?>
						<img
							src="<?php echo esc_url( $s['logo']['url'] ); ?>"
							alt="<?php echo esc_attr( $s['brand_name'] ); ?>"
							width="52"
							height="52"
						/>
					<?php endif; ?>
					<span class="ard-header__brand">
						<span class="ard-header__name"><?php echo esc_html( $s['brand_name'] ); ?></span>
						<?php if ( '' !== trim( (string) $s['brand_tagline'] ) ) : ?>
							<span class="ard-header__tagline"><?php echo esc_html( $s['brand_tagline'] ); ?></span>
						<?php endif; ?>
					</span>
				</a>

				<?php if ( 'yes' === $s['show_search'] ) : ?>
					<form class="ard-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php echo Ardal_Helpers::icon( 'search', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<input
							type="search"
							name="s"
							class="ard-search__input"
							placeholder="<?php echo esc_attr( $s['search_placeholder'] ); ?>"
							aria-label="<?php echo esc_attr( $s['search_placeholder'] ); ?>"
						/>
						<?php if ( 'product' === $s['search_scope'] ) : ?>
							<input type="hidden" name="post_type" value="product" />
						<?php endif; ?>
						<?php if ( '' !== trim( (string) $s['search_button'] ) ) : ?>
							<button type="submit" class="ard-search__btn"><?php echo esc_html( $s['search_button'] ); ?></button>
						<?php endif; ?>
					</form>
				<?php endif; ?>

				<div class="ard-header__actions">
					<?php if ( 'yes' === $s['show_account'] ) : ?>
						<a class="ard-header__account"<?php echo Ardal_Helpers::link_attrs( $s['account_link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
							<?php echo Ardal_Helpers::icon( 'user', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span><?php echo esc_html( $s['account_text'] ); ?></span>
						</a>
					<?php endif; ?>

					<?php if ( 'yes' === $s['show_cart'] ) : ?>
						<a class="ard-header__cart"<?php echo Ardal_Helpers::link_attrs( $s['cart_link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
							<?php echo Ardal_Helpers::icon( 'cart', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span class="ard-header__cart-text"><?php echo esc_html( $s['cart_text'] ); ?></span>
							<span class="ard-header__cart-count"><?php echo esc_html( Ardal_Helpers::fa_digits( $count ) ); ?></span>
						</a>
					<?php endif; ?>
				</div>

			</div>
		</header>
		<?php
	}

	/**
	 * @param array $s
	 * @return int
	 */
	private function cart_count( $s ) {
		if ( 'woo' === $s['cart_count_source'] && Ardal_Helpers::has_woo() && function_exists( 'WC' ) && WC()->cart ) {
			return (int) WC()->cart->get_cart_contents_count();
		}

		return (int) $s['cart_count'];
	}
}
