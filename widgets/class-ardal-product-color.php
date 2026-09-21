<?php
/**
 * ویجت انتخاب رنگ محصول.
 *
 * چون این ویجت از باکس خرید و ویجت سایز جداست، انتخاب کاربر روی خودِ صفحه
 * منتشر می‌شود تا بقیهٔ ویجت‌ها هم از آن باخبر شوند.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Product_Color extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-product-color';
	}

	public function get_title() {
		return 'اردال — انتخاب رنگ محصول';
	}

	public function get_icon() {
		return 'eicon-paint-brush';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'رنگ', 'متغیر', 'color', 'variation' );
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
				'description' => 'حالت خودکار گزینه‌ها را از ویژگی رنگِ محصول می‌خواند و گزینه‌های ناموجود را غیرفعال می‌کند.',
			)
		);

		$this->add_control(
			'color_attribute',
			array(
				'label'       => 'نام ویژگی رنگ',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'رنگ',
				'description' => 'نام یا اسلاگ ویژگی محصول. مثلاً «رنگ» یا «pa_color».',
				'condition'   => array( 'source' => 'auto' ),
			)
		);

		$this->add_control(
			'label',
			array(
				'label'   => 'برچسب',
				'type'    => Controls_Manager::TEXT,
				'default' => 'رنگ',
			)
		);

		$this->add_control(
			'show_selected',
			array(
				'label'        => 'نمایش نام رنگ انتخاب‌شده کنار برچسب',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'color_name',
			array(
				'label'   => 'نام رنگ',
				'type'    => Controls_Manager::TEXT,
				'default' => 'مشکی / لیمویی',
			)
		);

		$repeater->add_control(
			'color_hex',
			array(
				'label'   => 'کد رنگ',
				'type'    => Controls_Manager::COLOR,
				'default' => '#20303F',
			)
		);

		$this->add_control(
			'colors',
			array(
				'label'       => 'رنگ‌ها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ color_name }}}',
				'condition'   => array( 'source' => 'manual' ),
				'default'     => array(
					array( 'color_name' => 'مشکی / لیمویی', 'color_hex' => '#20303F' ),
					array( 'color_name' => 'سفید / آبی', 'color_hex' => '#E8EEF5' ),
					array( 'color_name' => 'سرمه‌ای', 'color_hex' => '#0B3C8C' ),
					array( 'color_name' => 'خاکستری', 'color_hex' => '#8C98A4' ),
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

		$this->add_responsive_control(
			'gap',
			array(
				'label'          => 'فاصله بین گزینه‌ها',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 4, 'max' => 24 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 10 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 8 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-prod-colors' => 'gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'swatch_size',
			array(
				'label'      => 'اندازه نمونه‌رنگ',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 10, 'max' => 32 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 18 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-prod-color__swatch' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'active_color',
			array(
				'label'     => 'رنگ گزینه انتخاب‌شده',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-prod-color.is-active' => 'border-color:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'names_visible',
			array(
				'label'                => 'نمایش نام رنگ روی گزینه',
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'inline',
				'mobile_default'       => 'none',
				'options'              => array(
					'inline' => 'نمایش',
					'none'   => 'فقط نمونه‌رنگ',
				),
				'selectors'            => array(
					'{{WRAPPER}} .ard-prod-color__name' => 'display:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( 'manual' === $s['source'] ) {
			$options = array();

			foreach ( (array) $s['colors'] as $c ) {
				if ( '' === trim( (string) $c['color_name'] ) ) {
					continue;
				}

				$options[] = array(
					'label'     => $c['color_name'],
					'hex'       => $c['color_hex'],
					'available' => true,
				);
			}
		} else {
			$product = Ardal_Product::current();

			if ( ! $product ) {
				$this->render_empty( 'محصولی پیدا نشد. این ویجت را روی قالب تک‌محصول بگذارید یا حالت دستی را انتخاب کنید.' );

				return;
			}

			$variants = Ardal_Product::variation_options( $product, $s['color_attribute'] );
			$options  = $variants['options'];
			$attr_key = isset( $variants['key'] ) ? $variants['key'] : '';
			$preset   = Ardal_Product::default_variation( $product );
			$chosen   = ( $attr_key && isset( $preset[ $attr_key ] ) ) ? $preset[ $attr_key ] : '';

			foreach ( $options as $i => $option ) {
				$options[ $i ]['hex'] = Ardal_Helpers::guess_color( $option['label'] );
			}
		}

		if ( empty( $options ) ) {
			$this->render_empty( sprintf( 'این محصول ویژگی «%s» ندارد. ویژگی را به محصول اضافه کنید یا حالت دستی را انتخاب کنید.', $s['color_attribute'] ) );

			return;
		}

		$chosen = isset( $chosen ) ? $chosen : '';
		$first  = '';

		// گزینهٔ از پیش انتخاب‌شده، وگرنه اولین گزینهٔ موجود.
		foreach ( $options as $option ) {
			if ( '' !== $chosen && isset( $option['slug'] ) && $option['slug'] === $chosen ) {
				$first = $option['label'];

				break;
			}
		}

		if ( '' === $first ) {
			foreach ( $options as $option ) {
				if ( ! empty( $option['available'] ) ) {
					$first  = $option['label'];
					$chosen = isset( $option['slug'] ) ? $option['slug'] : '';

					break;
				}
			}
		}

		// کلید ویژگی به جاوااسکریپت می‌رسد تا تطبیق واریاسیون به نام ثابت
		// attribute_pa_color گره نخورد؛ فروشگاه‌ها ویژگی‌شان را هرجور می‌نامند.
		$attr_key = isset( $attr_key ) ? $attr_key : '';
		?>
		<div
			class="ard-prod-variants ard-prod-variants--colors"
			data-ard-color-group
			data-attr="<?php echo esc_attr( $attr_key ); ?>"
		>
			<div class="ard-prod-variants__header">
				<span class="ard-prod-variants__label"><?php echo esc_html( $s['label'] ); ?>:</span>
				<?php if ( 'yes' === $s['show_selected'] ) : ?>
					<span class="ard-prod-variants__selected" data-ard-color-target><?php echo esc_html( $first ); ?></span>
				<?php endif; ?>
			</div>
			<div class="ard-prod-colors">
				<?php
				$active_set = false;

				// همان تور ایمنیِ ویجت سایز: اسلاگِ نایافته به «اولین موجود» برمی‌گردد.
				$found = false;

				foreach ( $options as $c ) {
					if ( '' !== $chosen && isset( $c['slug'] ) && $c['slug'] === $chosen && ! empty( $c['available'] ) ) {
						$found = true;

						break;
					}
				}

				if ( ! $found ) {
					$chosen = '';
				}

				foreach ( $options as $c ) :
					$available = ! empty( $c['available'] );
					$slug      = isset( $c['slug'] ) ? $c['slug'] : '';
					$active    = ! $active_set && $available && ( '' === $chosen || $slug === $chosen );

					if ( $active ) {
						$active_set = true;
					}
					?>
					<button
						type="button"
						class="ard-prod-color<?php echo $active ? ' is-active' : ''; ?><?php echo $available ? '' : ' is-disabled'; ?>"
						data-color-name="<?php echo esc_attr( $c['label'] ); ?>"
						data-color-slug="<?php echo esc_attr( isset( $c['slug'] ) ? $c['slug'] : '' ); ?>"
						style="--swatch-color:<?php echo esc_attr( $c['hex'] ); ?>"
						<?php echo $available ? '' : ' disabled aria-disabled="true"'; ?>
					>
						<span class="ard-prod-color__swatch"></span>
						<span class="ard-prod-color__name"><?php echo esc_html( $c['label'] ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
