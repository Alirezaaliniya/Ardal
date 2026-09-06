<?php
/**
 * ویجت منوی دسته‌بندی‌ها.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Nav extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-nav';
	}

	public function get_title() {
		return 'اردال — منوی دسته‌بندی‌ها';
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_keywords() {
		return array( 'اردال', 'منو', 'ناوبری', 'دسته‌بندی', 'nav', 'menu' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'محتوا' )
		);

		$this->add_control(
			'show_mega_button',
			array(
				'label'        => 'دکمه «همه دسته‌بندی‌ها»',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'mega_text',
			array(
				'label'     => 'متن دکمه',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'همه دسته‌بندی‌ها',
				'condition' => array( 'show_mega_button' => 'yes' ),
			)
		);

		$this->add_control(
			'mega_link',
			array(
				'label'     => 'لینک دکمه',
				'type'      => Controls_Manager::URL,
				'default'   => array( 'url' => '' ),
				'condition' => array( 'show_mega_button' => 'yes' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			array(
				'label'       => 'عنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'دسته‌بندی',
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
			'state',
			array(
				'label'   => 'حالت',
				'type'    => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => array(
					'normal'  => 'عادی',
					'current' => 'صفحه فعلی (پررنگ)',
					'sale'    => 'حراج (قرمز)',
				),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => 'آیتم‌های منو',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ text }}}',
				'default'     => array(
					array( 'text' => 'صفحه اصلی', 'state' => 'current' ),
					array( 'text' => 'کفش ورزشی' ),
					array( 'text' => 'پوشاک' ),
					array( 'text' => 'بدنسازی' ),
					array( 'text' => 'شنا و آب' ),
					array( 'text' => 'دوچرخه' ),
					array( 'text' => 'ورزش‌های تیمی' ),
					array( 'text' => 'حراج ویژه', 'state' => 'sale' ),
				),
			)
		);

		$this->add_control(
			'menu_id',
			array(
				'label'       => 'شناسه CSS منو',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'ard-main-nav',
				'description' => 'همین مقدار را در فیلد «شناسه منوی همبرگری» ویجت هدر وارد کنید.',
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
				'default' => 'bar',
				'options' => array(
					'bar'   => 'نوار جداگانه با خط زیرین (دیزاین ۱a)',
					'plain' => 'ساده و بدون حاشیه (دیزاین ۱b)',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = is_array( $s['items'] ) ? $s['items'] : array();
		$id    = trim( (string) $s['menu_id'] );
		?>
		<nav
			class="ard-nav ard-nav--<?php echo esc_attr( $s['variant'] ); ?>"
			<?php echo '' !== $id ? ' id="' . esc_attr( $id ) . '"' : ''; ?>
			aria-label="منوی اصلی"
		>
			<div class="ard-wrap ard-nav__inner">
				<?php
				if ( 'yes' === $s['show_mega_button'] ) :
					$mega_tag = Ardal_Helpers::link_tag( $s['mega_link'] );
					?>
					<<?php echo esc_html( $mega_tag ); ?> class="ard-nav__mega"<?php echo Ardal_Helpers::link_attrs( $s['mega_link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php echo Ardal_Helpers::icon( 'menu', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php echo esc_html( $s['mega_text'] ); ?>
					</<?php echo esc_html( $mega_tag ); ?>>
				<?php endif; ?>

				<ul class="ard-nav__list">
					<?php
					foreach ( $items as $item ) :
						if ( '' === trim( (string) $item['text'] ) ) {
							continue;
						}
						?>
						<li class="ard-nav__item ard-nav__item--<?php echo esc_attr( $item['state'] ); ?>">
							<?php if ( ! empty( $item['link']['url'] ) ) : ?>
								<a<?php echo Ardal_Helpers::link_attrs( $item['link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $item['text'] ); ?></a>
							<?php else : ?>
								<span><?php echo esc_html( $item['text'] ); ?></span>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</nav>
		<?php
	}
}
