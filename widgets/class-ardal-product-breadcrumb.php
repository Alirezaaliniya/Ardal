<?php
/**
 * ویجت مسیر صفحه (breadcrumb) محصول.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Product_Breadcrumb extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-product-breadcrumb';
	}

	public function get_title() {
		return 'اردال — مسیر صفحه محصول';
	}

	public function get_icon() {
		return 'eicon-navigation-horizontal';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'مسیر', 'بردکرامب', 'breadcrumb', 'product' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'محتوا' )
		);

		$this->add_control(
			'source',
			array(
				'label'       => 'منبع',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'auto',
				'options'     => array(
					'auto'   => 'خودکار از محصول جاری',
					'manual' => 'دستی',
				),
				'description' => 'حالت خودکار، دستهٔ محصول و نام آن را از خود محصول می‌خواند.',
			)
		);

		$this->add_control(
			'home_text',
			array(
				'label'     => 'متن اولین حلقه',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'صفحه اصلی',
				'condition' => array( 'source' => 'auto' ),
			)
		);

		$this->add_control(
			'show_current',
			array(
				'label'        => 'نمایش نام محصول در انتها',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'source' => 'auto' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			array(
				'label'       => 'عنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'صفحه اصلی',
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
			'is_current',
			array(
				'label'        => 'صفحه فعلی',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => 'حلقه‌ها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ text }}}',
				'condition'   => array( 'source' => 'manual' ),
				'default'     => array(
					array( 'text' => 'صفحه اصلی' ),
					array( 'text' => 'کفش' ),
					array( 'text' => 'کفش راحتی پرو-فیت مدل Aero X2', 'is_current' => 'yes' ),
				),
			)
		);

		$this->add_control(
			'separator',
			array(
				'label'   => 'جداکننده',
				'type'    => Controls_Manager::TEXT,
				'default' => '›',
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

		$this->add_responsive_control(
			'font_size',
			array(
				'label'          => 'اندازه متن',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 9, 'max' => 18 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 12 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 11 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-breadcrumb' => 'font-size:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'padding',
			array(
				'label'      => 'فاصله داخلی',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-breadcrumb' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'wrap_mode',
			array(
				'label'          => 'در فضای کم',
				'type'           => Controls_Manager::SELECT,
				'default'        => 'wrap',
				'mobile_default' => 'scroll',
				'options'        => array(
					'wrap'   => 'به خط بعد برود',
					'scroll' => 'یک خط با اسکرول افقی',
				),
				'selectors_dictionary' => array(
					'wrap'   => 'wrap;overflow-x:visible',
					'scroll' => 'nowrap;overflow-x:auto',
				),
				'selectors'      => array(
					'{{WRAPPER}} .ard-breadcrumb' => 'flex-wrap:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = 'manual' === $s['source'] ? $this->manual_items( $s ) : $this->auto_items( $s );

		if ( empty( $items ) ) {
			$this->render_empty( 'مسیری برای نمایش نیست. این ویجت را روی قالب تک‌محصول بگذارید یا حالت دستی را انتخاب کنید.' );

			return;
		}
		?>
		<nav class="ard-breadcrumb" aria-label="مسیر صفحه">
			<?php foreach ( $items as $idx => $item ) : ?>
				<?php if ( $idx > 0 ) : ?>
					<span class="ard-breadcrumb__sep" aria-hidden="true"><?php echo esc_html( $s['separator'] ); ?></span>
				<?php endif; ?>

				<?php if ( '' !== $item['url'] && ! $item['current'] ) : ?>
					<a class="ard-breadcrumb__item" href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a>
				<?php else : ?>
					<span
						class="ard-breadcrumb__item<?php echo $item['current'] ? ' ard-breadcrumb__item--current' : ''; ?>"
						<?php echo $item['current'] ? ' aria-current="page"' : ''; ?>
					><?php echo esc_html( $item['text'] ); ?></span>
				<?php endif; ?>
			<?php endforeach; ?>
		</nav>
		<?php
	}

	/**
	 * @param array $s
	 * @return array
	 */
	private function auto_items( $s ) {
		$product = Ardal_Product::current();

		if ( ! $product ) {
			return array();
		}

		$trail = Ardal_Product::breadcrumb( $product );
		$out   = array();
		$last  = count( $trail ) - 1;

		foreach ( $trail as $i => $crumb ) {
			if ( $i === $last && 'yes' !== $s['show_current'] ) {
				continue;
			}

			$text = ( 0 === $i && '' !== trim( (string) $s['home_text'] ) ) ? $s['home_text'] : $crumb['text'];

			$out[] = array(
				'text'    => $text,
				'url'     => $crumb['url'],
				'current' => $i === $last,
			);
		}

		return $out;
	}

	/**
	 * @param array $s
	 * @return array
	 */
	private function manual_items( $s ) {
		$out = array();

		foreach ( (array) $s['items'] as $item ) {
			if ( '' === trim( (string) $item['text'] ) ) {
				continue;
			}

			$out[] = array(
				'text'    => $item['text'],
				'url'     => isset( $item['link']['url'] ) ? $item['link']['url'] : '',
				'current' => 'yes' === ( isset( $item['is_current'] ) ? $item['is_current'] : '' ),
			);
		}

		return $out;
	}
}
