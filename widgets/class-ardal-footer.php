<?php
/**
 * ویجت فوتر فروشگاه.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Footer extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-footer';
	}

	public function get_title() {
		return 'اردال — فوتر فروشگاه';
	}

	public function get_icon() {
		return 'eicon-footer';
	}

	public function get_keywords() {
		return array( 'اردال', 'فوتر', 'پاورقی', 'footer' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_brand',
			array( 'label' => 'ستون برند' )
		);

		$this->add_tag_control( 'coltitle_tag', 'تگ عنوان ستون‌ها', 'h3' );

		$this->add_control(
			'logo',
			array(
				'label'   => 'لوگو',
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => ARDAL_URL . 'assets/img/logo.png' ),
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
			'about',
			array(
				'label'       => 'توضیح / نشانی',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => "تهران، خیابان ولیعصر، پلاک ۱۲۴\n۰۲۱-۹۱۰۰۲۲۳۳ — هر روز ۹ تا ۲۱",
				'label_block' => true,
				'description' => 'هر خط جدید در خروجی هم به خط جدید تبدیل می‌شود.',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_columns',
			array( 'label' => 'ستون‌های لینک' )
		);

		$link_repeater = new Repeater();

		$link_repeater->add_control(
			'title',
			array(
				'label'       => 'عنوان ستون',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'دسترسی سریع',
				'label_block' => true,
			)
		);

		$link_repeater->add_control(
			'links',
			array(
				'label'       => 'لینک‌ها',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 5,
				'default'     => "درباره ما\nتماس با ما\nسوالات متداول\nفرصت‌های شغلی",
				'label_block' => true,
				'description' => 'هر خط یک لینک. برای آدرس‌دهی از قالب «متن | آدرس» استفاده کنید.',
			)
		);

		$this->add_control(
			'columns',
			array(
				'label'       => 'ستون‌ها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $link_repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'title' => 'دسترسی سریع',
						'links' => "درباره ما\nتماس با ما\nسوالات متداول\nفرصت‌های شغلی",
					),
					array(
						'title' => 'خدمات مشتریان',
						'links' => "شرایط ارسال\nبازگشت کالا\nضمانت اصالت\nپیگیری سفارش",
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_badges',
			array( 'label' => 'نمادها و مجوزها' )
		);

		$this->add_control(
			'badges_title',
			array(
				'label'   => 'عنوان ستون',
				'type'    => Controls_Manager::TEXT,
				'default' => 'مجوزها',
			)
		);

		$badge_repeater = new Repeater();

		$badge_repeater->add_control(
			'label',
			array(
				'label'   => 'برچسب',
				'type'    => Controls_Manager::TEXT,
				'default' => 'نماد',
			)
		);

		$badge_repeater->add_control(
			'image',
			array(
				'label' => 'تصویر نماد',
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$badge_repeater->add_control(
			'link',
			array(
				'label'   => 'لینک',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '' ),
			)
		);

		$this->add_control(
			'badges',
			array(
				'label'       => 'نمادها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $badge_repeater->get_controls(),
				'title_field' => '{{{ label }}}',
				'default'     => array(
					array( 'label' => 'نماد' ),
					array( 'label' => 'ساماندهی' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_bottom',
			array( 'label' => 'کپی‌رایت' )
		);

		$this->add_control(
			'copyright',
			array(
				'label'       => 'متن کپی‌رایت',
				'type'        => Controls_Manager::TEXT,
				'default'     => '© ۱۴۰۵ فروشگاه اسپرت‌لند — تمامی حقوق محفوظ است',
				'label_block' => true,
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
				'label'   => 'سبک',
				'type'    => Controls_Manager::SELECT,
				'default' => 'dark',
				'options' => array(
					'dark'  => 'زمینه سرمه‌ای (دیزاین ۱a)',
					'light' => 'زمینه سفید با خط بالا (دیزاین ۱b)',
				),
			)
		);

		$this->add_responsive_control(
			'grid',
			array(
				'label'          => 'الگوی ستون‌ها',
				'type'           => Controls_Manager::TEXT,
				'default'        => '1.3fr 1fr 1fr 1fr',
				'tablet_default' => '1fr 1fr',
				'mobile_default' => '1fr 1fr',
				'description'    => 'مقدار grid-template-columns.',
				'selectors'      => array(
					'{{WRAPPER}} .ard-footer__grid' => 'grid-template-columns:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$ard_tag = self::tag( $s['coltitle_tag'], 'h3' );
		?>
		<footer class="ard-footer ard-footer--<?php echo esc_attr( $s['variant'] ); ?>">
			<div class="ard-footer__grid">

				<div class="ard-footer__col ard-footer__col--brand">
					<div class="ard-footer__brand">
						<?php if ( ! empty( $s['logo']['url'] ) ) : ?>
							<img src="<?php echo esc_url( $s['logo']['url'] ); ?>" alt="<?php echo esc_attr( $s['brand_name'] ); ?>" width="44" height="44" loading="lazy" decoding="async" />
						<?php endif; ?>
						<span class="ard-footer__name"><?php echo esc_html( $s['brand_name'] ); ?></span>
					</div>

					<?php if ( '' !== trim( (string) $s['about'] ) ) : ?>
						<p class="ard-footer__about"><?php echo nl2br( esc_html( $s['about'] ) ); ?></p>
					<?php endif; ?>
				</div>

				<?php foreach ( (array) $s['columns'] as $column ) : ?>
					<div class="ard-footer__col">
						<<?php echo esc_html( $ard_tag ); ?> class="ard-footer__coltitle"><?php echo esc_html( $column['title'] ); ?></<?php echo esc_html( $ard_tag ); ?>>
						<ul class="ard-footer__links">
							<?php foreach ( $this->parse_links( $column['links'] ) as $link ) : ?>
								<li>
									<?php if ( '' !== $link['url'] ) : ?>
										<a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['text'] ); ?></a>
									<?php else : ?>
										<span><?php echo esc_html( $link['text'] ); ?></span>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endforeach; ?>

				<?php if ( ! empty( $s['badges'] ) ) : ?>
					<div class="ard-footer__col">
						<?php if ( '' !== trim( (string) $s['badges_title'] ) ) : ?>
							<<?php echo esc_html( $ard_tag ); ?> class="ard-footer__coltitle"><?php echo esc_html( $s['badges_title'] ); ?></<?php echo esc_html( $ard_tag ); ?>>
						<?php endif; ?>

						<div class="ard-footer__badges">
							<?php
							foreach ( (array) $s['badges'] as $badge ) :
								$tag = ! empty( $badge['link']['url'] ) ? 'a' : 'div';
								?>
								<<?php echo esc_html( $tag ); ?> class="ard-footer__badge"<?php echo 'a' === $tag ? Ardal_Helpers::link_attrs( $badge['link'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
									<?php echo Ardal_Helpers::image( $badge['image'], $badge['label'], $badge['label'], 'thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</<?php echo esc_html( $tag ); ?>>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

			</div>

			<?php if ( '' !== trim( (string) $s['copyright'] ) ) : ?>
				<div class="ard-footer__bottom"><?php echo esc_html( $s['copyright'] ); ?></div>
			<?php endif; ?>
		</footer>
		<?php
	}

	/**
	 * تبدیل متن چندخطی به لیست لینک.
	 *
	 * @param string $raw
	 * @return array
	 */
	private function parse_links( $raw ) {
		$out = array();

		foreach ( preg_split( '/\r\n|\r|\n/', (string) $raw ) as $line ) {
			$line = trim( $line );

			if ( '' === $line ) {
				continue;
			}

			$parts = array_map( 'trim', explode( '|', $line, 2 ) );

			$out[] = array(
				'text' => $parts[0],
				'url'  => isset( $parts[1] ) ? $parts[1] : '',
			);
		}

		return $out;
	}
}
