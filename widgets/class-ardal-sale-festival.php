<?php
/**
 * ویجت جشنواره حراج — نوار سرمه‌ای با شمارنده و محصولات.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Sale_Festival extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-sale-festival';
	}

	public function get_title() {
		return 'اردال — جشنواره حراج';
	}

	public function get_icon() {
		return 'eicon-price-table';
	}

	public function get_keywords() {
		return array( 'اردال', 'حراج', 'جشنواره', 'تخفیف', 'sale', 'campaign' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_head',
			array( 'label' => 'سربرگ جشنواره' )
		);

		$this->add_control(
			'eyebrow',
			array(
				'label'     => 'متن کوچک بالای عنوان',
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array( 'variant' => 'wide' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => 'عنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'جشنواره تخفیف پایان فصل',
				'label_block' => true,
			)
		);

		$this->add_tag_control( 'title_tag', 'تگ عنوان جشنواره', 'h2' );
		$this->add_tag_control( 'item_tag', 'تگ نام محصول', 'h3' );

		$this->add_control(
			'badge',
			array(
				'label'   => 'برچسب سبز',
				'type'    => Controls_Manager::TEXT,
				'default' => 'تا ۵۰٪ تخفیف',
			)
		);

		$this->add_control(
			'timer_label',
			array(
				'label'   => 'متن کنار شمارنده',
				'type'    => Controls_Manager::TEXT,
				'default' => 'پایان جشنواره تا',
			)
		);

		$this->add_control(
			'end_date',
			array(
				'label'       => 'تاریخ پایان',
				'type'        => Controls_Manager::DATE_TIME,
				'default'     => gmdate( 'Y-m-d H:i', current_time( 'timestamp' ) + 3 * DAY_IN_SECONDS ), // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
				'description' => 'برای مخفی کردن شمارنده، این فیلد را خالی بگذارید.',
			)
		);

		$this->add_control(
			'show_days',
			array(
				'label'        => 'نمایش خانه «روز»',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			array( 'label' => 'محصولات' )
		);

		$this->add_source_controls( 4 );

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			array(
				'label'       => 'نام محصول',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'default'     => 'دوچرخه کوهستان ۲۷٫۵ اینچ ۲۱ سرعته',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'image',
			array(
				'label' => 'تصویر',
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$repeater->add_control(
			'price',
			array(
				'label'   => 'قیمت',
				'type'    => Controls_Manager::TEXT,
				'default' => '۹٫۴۵۰٫۰۰۰',
			)
		);

		$repeater->add_control(
			'old_price',
			array(
				'label'   => 'قیمت قبل',
				'type'    => Controls_Manager::TEXT,
				'default' => '۱۳٫۵۰۰٫۰۰۰',
			)
		);

		$repeater->add_control(
			'off',
			array(
				'label'       => 'درصد تخفیف',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'خالی = محاسبه خودکار',
			)
		);

		$repeater->add_control(
			'bar',
			array(
				'label'   => 'درصد فروش‌رفته',
				'type'    => Controls_Manager::SLIDER,
				'range'   => array( '%' => array( 'min' => 0, 'max' => 100 ) ),
				'default' => array( 'unit' => '%', 'size' => 72 ),
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

		$this->add_control(
			'items',
			array(
				'label'       => 'محصولات جشنواره',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'condition'   => array( 'source' => 'manual' ),
				'default'     => array(
					array(
						'title'     => 'دوچرخه کوهستان ۲۷٫۵ اینچ ۲۱ سرعته',
						'price'     => '۹٫۴۵۰٫۰۰۰',
						'old_price' => '۱۳٫۵۰۰٫۰۰۰',
						'bar'       => array( 'unit' => '%', 'size' => 72 ),
					),
					array(
						'title'     => 'کوله کوهنوردی ۴۵ لیتری ضدآب',
						'price'     => '۱٫۸۹۰٫۰۰۰',
						'old_price' => '۲٫۹۰۰٫۰۰۰',
						'bar'       => array( 'unit' => '%', 'size' => 45 ),
					),
					array(
						'title'     => 'تشک یوگا TPE دولایه ۸ میلی‌متر',
						'price'     => '۴۹۰٫۰۰۰',
						'old_price' => '۸۵۰٫۰۰۰',
						'bar'       => array( 'unit' => '%', 'size' => 88 ),
					),
					array(
						'title'     => 'کش مقاومتی ست ۵ عددی با دستگیره',
						'price'     => '۳۲۰٫۰۰۰',
						'old_price' => '۵۹۰٫۰۰۰',
						'bar'       => array( 'unit' => '%', 'size' => 60 ),
					),
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
			'variant',
			array(
				'label'   => 'سبک',
				'type'    => Controls_Manager::SELECT,
				'default' => 'boxed',
				'options' => array(
					'boxed' => 'باکس سرمه‌ای گرد با کارت‌های افقی (دیزاین ۱a)',
					'wide'  => 'نوار تمام‌عرض گرادیانی با کارت‌های عمودی (دیزاین ۱b)',
				),
			)
		);

		$this->add_media_fit_control( '.ard-fest__media img' );

		$this->add_control(
			'show_bars',
			array(
				'label'        => 'نمایش نوار فروش هر محصول',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'variant' => 'wide' ),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => 'تعداد ستون',
				'type'           => Controls_Manager::NUMBER,
				'min'            => 1,
				'max'            => 6,
				'default'        => 4,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'selectors'      => array(
					'{{WRAPPER}} .ard-fest__grid' => 'grid-template-columns:repeat({{VALUE}},minmax(0,1fr));',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = $this->get_product_items( $s );
		$wide  = 'wide' === $s['variant'];
		?>
		<section class="ard-fest ard-fest--<?php echo esc_attr( $s['variant'] ); ?>">
			<div class="ard-fest__inner">

				<div class="ard-fest__head">
					<div class="ard-fest__titles">
						<?php if ( $wide && '' !== trim( (string) $s['eyebrow'] ) ) : ?>
							<span class="ard-fest__eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></span>
						<?php endif; ?>
						<<?php echo esc_html( self::tag( $s['title_tag'], 'h2' ) ); ?> class="ard-fest__title"><?php echo esc_html( $s['title'] ); ?></<?php echo esc_html( self::tag( $s['title_tag'], 'h2' ) ); ?>>
					</div>

					<?php if ( '' !== trim( (string) $s['badge'] ) ) : ?>
						<span class="ard-fest__badge"><?php echo esc_html( $s['badge'] ); ?></span>
					<?php endif; ?>

					<?php if ( '' !== trim( (string) $s['end_date'] ) ) : ?>
						<div class="ard-fest__timer">
							<?php if ( '' !== trim( (string) $s['timer_label'] ) ) : ?>
								<span class="ard-fest__timer-label"><?php echo esc_html( $s['timer_label'] ); ?></span>
							<?php endif; ?>
							<?php $this->render_clock( $s ); ?>
						</div>
					<?php endif; ?>
				</div>

				<?php if ( empty( $items ) ) : ?>
					<?php $this->render_empty( 'محصولی برای جشنواره تعریف نشده است.' ); ?>
				<?php else : ?>
					<div class="ard-fest__grid">
						<?php foreach ( $items as $item ) : ?>
							<?php $this->render_item( $item, $s, $wide ); ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

			</div>
		</section>
		<?php
	}

	/**
	 * @param array $item
	 * @param array $s
	 * @param bool  $wide
	 */
	private function render_item( $item, $s, $wide ) {
		$title = isset( $item['title'] ) ? $item['title'] : '';
		$price = Ardal_Helpers::price( isset( $item['price'] ) ? $item['price'] : '' );
		$old   = Ardal_Helpers::price( isset( $item['old_price'] ) ? $item['old_price'] : '' );
		$off   = ! empty( $item['off'] )
			? Ardal_Helpers::fa_digits( $item['off'] )
			: Ardal_Helpers::discount( $old, $price );
		$link  = isset( $item['link'] ) ? $item['link'] : array( 'url' => '' );

		$bar = isset( $item['bar'] ) ? $item['bar'] : array();
		$bar = is_array( $bar ) && isset( $bar['size'] ) ? (int) $bar['size'] : 0;
		?>
		<article class="ard-fest__card">
			<div class="ard-fest__media">
				<?php echo Ardal_Helpers::image( isset( $item['image'] ) ? $item['image'] : array(), $wide ? '۵۰۰×۵۰۰' : '۴۰۰×۴۰۰', $title, 'medium' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php if ( $wide && '' !== $off ) : ?>
					<span class="ard-badge ard-badge--sale"><?php echo esc_html( $off ); ?></span>
				<?php endif; ?>
			</div>

			<div class="ard-fest__body">
				<?php $ard_name_tag = self::tag( $s['item_tag'], 'h3' ); ?>
				<<?php echo esc_html( $ard_name_tag ); ?> class="ard-fest__name">
					<?php if ( ! empty( $link['url'] ) ) : ?>
						<a<?php echo Ardal_Helpers::link_attrs( $link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $title ); ?></a>
					<?php else : ?>
						<?php echo esc_html( $title ); ?>
					<?php endif; ?>
				</<?php echo esc_html( $ard_name_tag ); ?>>

				<div class="ard-fest__prices">
					<?php if ( ! $wide && '' !== $off ) : ?>
						<span class="ard-badge ard-badge--sale"><?php echo esc_html( $off ); ?></span>
					<?php endif; ?>
					<?php if ( '' !== $old ) : ?>
						<del><?php echo esc_html( $old ); ?></del>
					<?php endif; ?>
					<?php if ( '' !== $price ) : ?>
						<span class="ard-fest__price"><?php echo esc_html( $price ); ?> <?php echo Ardal_Currency::unit(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php endif; ?>
				</div>

				<?php if ( $wide && 'yes' === $s['show_bars'] && $bar > 0 ) : ?>
					<div class="ard-progress" role="progressbar" aria-valuenow="<?php echo esc_attr( $bar ); ?>" aria-valuemin="0" aria-valuemax="100">
						<span style="width:<?php echo esc_attr( min( 100, $bar ) ); ?>%"></span>
					</div>
				<?php endif; ?>
			</div>
		</article>
		<?php
	}

	/**
	 * @param array $s
	 */
	private function render_clock( $s ) {
		$now       = (int) current_time( 'timestamp' ); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
		$end       = strtotime( (string) $s['end_date'] );
		$remaining = $end ? max( 0, $end - $now ) : 0;
		$with_days = 'yes' === $s['show_days'];

		$units = array(
			'days'    => 'روز',
			'hours'   => 'ساعت',
			'minutes' => 'دقیقه',
			'seconds' => 'ثانیه',
		);

		if ( ! $with_days ) {
			unset( $units['days'] );
		}

		$days  = $with_days ? (int) floor( $remaining / DAY_IN_SECONDS ) : 0;
		$hours = (int) floor( ( $remaining - $days * DAY_IN_SECONDS ) / HOUR_IN_SECONDS );

		$values = array(
			'days'    => str_pad( (string) $days, 2, '0', STR_PAD_LEFT ),
			'hours'   => str_pad( (string) $hours, 2, '0', STR_PAD_LEFT ),
			'minutes' => str_pad( (string) ( (int) floor( $remaining / MINUTE_IN_SECONDS ) % 60 ), 2, '0', STR_PAD_LEFT ),
			'seconds' => str_pad( (string) ( $remaining % 60 ), 2, '0', STR_PAD_LEFT ),
		);
		?>
		<div
			class="ard-clock ard-clock--onDark"
			data-ard-countdown
			data-remaining="<?php echo esc_attr( $remaining ); ?>"
			data-mode="date"
			data-days="<?php echo $with_days ? '1' : '0'; ?>"
		>
			<?php foreach ( $units as $key => $label ) : ?>
				<div class="ard-clock__cell">
					<span class="ard-clock__num" data-unit="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( Ardal_Helpers::fa_digits( $values[ $key ] ) ); ?></span>
					<span class="ard-clock__label"><?php echo esc_html( $label ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
