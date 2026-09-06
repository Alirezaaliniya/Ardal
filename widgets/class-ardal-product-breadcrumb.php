<?php
/**
 * ویجت مسیر صفحه (Breadcrumb) صفحه محصول.
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
		return 'اردال — مسیر صفحه (Breadcrumb)';
	}

	public function get_icon() {
		return 'eicon-yoast';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'مسیر', 'بردکرامب', 'breadcrumb', 'محصول' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'آیتم‌های مسیر' )
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			array(
				'label'       => 'متن',
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
				'label'        => 'صفحه فعلی (بولد)',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => 'آیتم‌های مسیر',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ text }}}',
				'default'     => array(
					array( 'text' => 'صفحه اصلی', 'link' => array( 'url' => '/' ) ),
					array( 'text' => 'کفش ورزشی', 'link' => array( 'url' => '#' ) ),
					array( 'text' => 'کفش دویدن', 'link' => array( 'url' => '#' ) ),
					array( 'text' => 'کفش دویدن پرو-فیت مدل Aero X2', 'is_current' => 'yes' ),
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
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = is_array( $s['items'] ) ? $s['items'] : array();
		$sep   = $s['separator'];

		if ( empty( $items ) ) {
			return;
		}
		?>
		<nav class="ard-breadcrumb" aria-label="مسیر صفحه">
			<?php foreach ( $items as $idx => $item ) : ?>
				<?php if ( $idx > 0 ) : ?>
					<span class="ard-breadcrumb__sep"><?php echo esc_html( $sep ); ?></span>
				<?php endif; ?>

				<?php
				$is_current = 'yes' === ( isset( $item['is_current'] ) ? $item['is_current'] : '' );
				$has_link   = ! empty( $item['link']['url'] ) && ! $is_current;
				?>

				<?php if ( $has_link ) : ?>
					<a class="ard-breadcrumb__item"<?php echo Ardal_Helpers::link_attrs( $item['link'] ); // phpcs:ignore ?>><?php echo esc_html( $item['text'] ); ?></a>
				<?php else : ?>
					<span class="ard-breadcrumb__item<?php echo $is_current ? ' ard-breadcrumb__item--current' : ''; ?>"><?php echo esc_html( $item['text'] ); ?></span>
				<?php endif; ?>
			<?php endforeach; ?>
		</nav>
		<?php
	}
}
