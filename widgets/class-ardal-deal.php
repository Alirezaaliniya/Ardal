<?php
/**
 * ویجت پیشنهاد ویژه — کروسل محصولات تخفیف‌دار با شمارش معکوس.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Deal extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-deal';
	}

	public function get_title() {
		return 'اردال — پیشنهاد ویژه';
	}

	public function get_icon() {
		return 'eicon-countdown';
	}

	public function get_keywords() {
		return array( 'اردال', 'پیشنهاد', 'شمارش معکوس', 'تخفیف', 'کروسل', 'deal', 'countdown', 'carousel' );
	}

	/**
	 * کلاس روی wrapper خود ویجت.
	 *
	 * برای «هم‌ارتفاع با ستون» فقط block-size روی خود باکس کافی نیست؛ wrapperهای
	 * المنتور بینشان ارتفاع auto دارند و زنجیره قطع می‌شود، پس اینجا هم کلاس لازم است.
	 *
	 * این متد را المنتور در get_initial_config() روی نمونه‌ای بدون داده هم صدا می‌زند،
	 * بنابراین اینجا نباید سراغ تنظیمات رفت (get_settings_for_display روی داده خالی
	 * خطا می‌دهد). کلاس همیشه چاپ می‌شود و تصمیمِ کشیده‌شدن به کلاس ard-deal--stretch
	 * سپرده شده که در render() به‌صورت شرطی صادر می‌شود؛ بدون آن، استایل host بی‌اثر است.
	 *
	 * @return string
	 */
	protected function get_html_wrapper_class() {
		return parent::get_html_wrapper_class() . ' ard-deal-host';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_head',
			array( 'label' => 'سربرگ' )
		);

		$this->add_control(
			'label',
			array(
				'label'   => 'عنوان باکس',
				'type'    => Controls_Manager::TEXT,
				'default' => 'پیشنهاد ویژه امروز',
			)
		);

		$this->add_control(
			'meta',
			array(
				'label'       => 'متن گوشه',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'مثلاً ۱۲ کالا',
				'description' => 'خالی بگذارید تا به‌جایش تعداد محصولات همین ویجت نوشته شود.',
			)
		);

		$this->add_control(
			'show_meta',
			array(
				'label'        => 'نمایش متن گوشه',
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

		$this->add_source_controls( 6 );

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			array(
				'label'       => 'نام محصول',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'default'     => 'تردمیل خانگی پرو-فیت مدل T۹۰۰ تاشو',
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
				'label'   => 'قیمت با تخفیف',
				'type'    => Controls_Manager::TEXT,
				'default' => '۱۹٫۶۵۰٫۰۰۰',
			)
		);

		$repeater->add_control(
			'old_price',
			array(
				'label'   => 'قیمت قبل',
				'type'    => Controls_Manager::TEXT,
				'default' => '۲۸٫۹۰۰٫۰۰۰',
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
			'sold',
			array(
				'label'   => 'تعداد فروش‌رفته',
				'type'    => Controls_Manager::NUMBER,
				'default' => 8,
				'min'     => 0,
			)
		);

		$repeater->add_control(
			'total',
			array(
				'label'   => 'کل موجودی',
				'type'    => Controls_Manager::NUMBER,
				'default' => 25,
				'min'     => 1,
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'   => 'لینک محصول',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => 'محصولات',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'condition'   => array( 'source' => 'manual' ),
				'default'     => array(
					array(
						'title'     => 'تردمیل خانگی پرو-فیت مدل T۹۰۰ تاشو',
						'price'     => '۱۹٫۶۵۰٫۰۰۰',
						'old_price' => '۲۸٫۹۰۰٫۰۰۰',
						'sold'      => 8,
						'total'     => 25,
					),
					array(
						'title'     => 'دوچرخه ثابت مغناطیسی اسپین بایک',
						'price'     => '۸٫۹۰۰٫۰۰۰',
						'old_price' => '۱۲٫۴۰۰٫۰۰۰',
						'sold'      => 14,
						'total'     => 20,
					),
					array(
						'title'     => 'ست دمبل هالتر ۳۰ کیلوگرمی روکش‌دار',
						'price'     => '۳٫۲۵۰٫۰۰۰',
						'old_price' => '۴٫۶۰۰٫۰۰۰',
						'sold'      => 5,
						'total'     => 30,
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_carousel',
			array( 'label' => 'کروسل' )
		);

		$this->add_responsive_control(
			'per_view',
			array(
				'label'          => 'تعداد نمایش هم‌زمان',
				'type'           => Controls_Manager::NUMBER,
				'min'            => 1,
				'max'            => 6,
				'default'        => 1,
				'tablet_default' => 2,
				'mobile_default' => 2,
				'description'    => 'مثلاً دسکتاپ ۱ و موبایل ۲.',
				'selectors'      => array(
					'{{WRAPPER}} .ard-deal__viewport' => '--ard-per:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => 'فاصله بین اسلایدها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 12 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-deal__viewport' => '--ard-gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'show_dots',
			array(
				'label'        => 'نمایش نقطه‌ها',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_arrows',
			array(
				'label'        => 'نمایش فلش‌ها در سربرگ',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => 'چرخش خودکار',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'interval',
			array(
				'label'     => 'فاصله چرخش (ثانیه)',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 2,
				'max'       => 30,
				'default'   => 6,
				'condition' => array( 'autoplay' => 'yes' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_timer',
			array( 'label' => 'شمارش معکوس' )
		);

		$this->add_control(
			'timer_mode',
			array(
				'label'   => 'نوع شمارنده',
				'type'    => Controls_Manager::SELECT,
				'default' => 'daily',
				'options' => array(
					'daily' => 'روزانه — تا پایان امروز و ریست خودکار',
					'date'  => 'تا یک تاریخ مشخص',
					'none'  => 'بدون شمارنده',
				),
			)
		);

		$this->add_control(
			'end_date',
			array(
				'label'       => 'تاریخ پایان',
				'type'        => Controls_Manager::DATE_TIME,
				'default'     => gmdate( 'Y-m-d H:i', current_time( 'timestamp' ) + DAY_IN_SECONDS ), // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
				'condition'   => array( 'timer_mode' => 'date' ),
				'description' => 'زمان بر اساس منطقه زمانی سایت محاسبه می‌شود.',
			)
		);

		$this->add_control(
			'show_days',
			array(
				'label'        => 'نمایش خانه «روز»',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'condition'    => array( 'timer_mode' => 'date' ),
			)
		);

		$this->add_control(
			'clock_position',
			array(
				'label'     => 'جای شمارنده',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'bottom',
				'options'   => array(
					'bottom' => 'پایین باکس',
					'top'    => 'زیر عنوان',
				),
				'condition' => array( 'timer_mode!' => 'none' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_stock',
			array( 'label' => 'موجودی' )
		);

		$this->add_control(
			'show_stock',
			array(
				'label'        => 'نمایش نوار موجودی',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'stock_template',
			array(
				'label'       => 'متن زیر نوار',
				'type'        => Controls_Manager::TEXT,
				'default'     => '{sold} عدد از {total} عدد فروش رفته',
				'label_block' => true,
				'description' => 'می‌توانید از {sold}، {total} و {left} استفاده کنید.',
				'condition'   => array( 'show_stock' => 'yes' ),
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
				'label'   => 'چیدمان',
				'type'    => Controls_Manager::SELECT,
				'default' => 'card',
				'options' => array(
					'card' => 'کارت عمودی سبز (ستون کنار اسلایدر)',
					'bar'  => 'نوار افقی سفید (زیر هیرو)',
				),
			)
		);

		$this->add_control(
			'stretch',
			array(
				'label'        => 'هم‌ارتفاع با ستون',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'باکس دقیقاً به اندازهٔ ستون کنارش (مثلاً اسلایدر) کشیده می‌شود و تصویر فضای باقی‌مانده را پر می‌کند.',
				'condition'    => array( 'variant' => 'card' ),
			)
		);

		$this->add_responsive_control(
			'media_min',
			array(
				'label'          => 'حداقل ارتفاع تصویر',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 60, 'max' => 260 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 110 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 90 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-deal__media' => '--ard-deal-media:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_lines',
			array(
				'label'     => 'حداکثر خطوط عنوان',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 4,
				'default'   => 2,
				'selectors' => array(
					'{{WRAPPER}} .ard-deal__title' => '-webkit-line-clamp:{{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'padding',
			array(
				'label'      => 'فاصله داخلی باکس',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 6, 'max' => 32 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 14 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-deal' => '--ard-deal-pad:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = $this->get_deal_items( $s );

		if ( empty( $items ) ) {
			$this->render_empty( 'محصولی برای پیشنهاد ویژه تعریف نشده است.' );

			return;
		}

		$classes = array( 'ard-deal', 'ard-deal--' . $s['variant'] );

		if ( 'card' === $s['variant'] && 'yes' === $s['stretch'] ) {
			$classes[] = 'ard-deal--stretch';
		}

		$has_clock = 'none' !== $s['timer_mode'];
		$interval  = 'yes' === $s['autoplay'] ? max( 2, (int) $s['interval'] ) * 1000 : 0;
		?>
		<section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">

			<div class="ard-deal__head">
				<h2 class="ard-deal__label"><?php echo esc_html( $s['label'] ); ?></h2>

				<?php if ( 'yes' === $s['show_meta'] ) : ?>
					<span class="ard-deal__meta"><?php echo esc_html( $this->meta_text( $s, count( $items ) ) ); ?></span>
				<?php endif; ?>

				<?php if ( 'yes' === $s['show_arrows'] && count( $items ) > 1 ) : ?>
					<div class="ard-deal__nav">
						<button type="button" class="ard-deal__arrow" data-ard-prev aria-label="قبلی">
							<?php echo Ardal_Helpers::icon( 'chevron-right', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</button>
						<button type="button" class="ard-deal__arrow" data-ard-next aria-label="بعدی">
							<?php echo Ardal_Helpers::icon( 'chevron-left', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</button>
					</div>
				<?php endif; ?>
			</div>

			<?php
			if ( $has_clock && 'top' === $s['clock_position'] ) {
				$this->render_clock( $s );
			}
			?>

			<div
				class="ard-deal__carousel"
				data-ard-carousel
				data-interval="<?php echo esc_attr( $interval ); ?>"
			>
				<div class="ard-deal__viewport" data-ard-viewport>
					<?php foreach ( $items as $item ) : ?>
						<?php $this->render_item( $item, $s ); ?>
					<?php endforeach; ?>
				</div>

				<?php if ( 'yes' === $s['show_dots'] && count( $items ) > 1 ) : ?>
					<div class="ard-deal__dots" data-ard-dots role="tablist" aria-label="انتخاب محصول"></div>
				<?php endif; ?>
			</div>

			<?php
			if ( $has_clock && 'bottom' === $s['clock_position'] ) {
				$this->render_clock( $s );
			}
			?>
		</section>
		<?php
	}

	/**
	 * یک اسلاید محصول.
	 *
	 * @param array $item
	 * @param array $s
	 */
	private function render_item( $item, $s ) {
		$title = isset( $item['title'] ) ? $item['title'] : '';
		$price = Ardal_Helpers::price( isset( $item['price'] ) ? $item['price'] : '' );
		$old   = Ardal_Helpers::price( isset( $item['old_price'] ) ? $item['old_price'] : '' );
		$off   = ! empty( $item['off'] )
			? Ardal_Helpers::fa_digits( $item['off'] )
			: Ardal_Helpers::discount( $old, $price );
		$link  = isset( $item['link'] ) ? $item['link'] : array( 'url' => '' );

		$sold  = isset( $item['sold'] ) ? max( 0, (int) $item['sold'] ) : 0;
		$total = isset( $item['total'] ) ? max( 1, (int) $item['total'] ) : 1;
		$pct   = min( 100, (int) round( ( $sold / $total ) * 100 ) );
		?>
		<article class="ard-deal__item">
			<div class="ard-deal__media">
				<?php echo Ardal_Helpers::image( isset( $item['image'] ) ? $item['image'] : array(), '۴۰۰×۴۰۰', $title, 'medium' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php if ( '' !== $off ) : ?>
					<span class="ard-badge ard-badge--sale"><?php echo esc_html( $off ); ?></span>
				<?php endif; ?>
			</div>

			<div class="ard-deal__info">
				<h3 class="ard-deal__title">
					<?php if ( ! empty( $link['url'] ) ) : ?>
						<a<?php echo Ardal_Helpers::link_attrs( $link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $title ); ?></a>
					<?php else : ?>
						<?php echo esc_html( $title ); ?>
					<?php endif; ?>
				</h3>

				<div class="ard-deal__prices">
					<?php if ( '' !== $old ) : ?>
						<del class="ard-deal__old"><?php echo esc_html( $old ); ?></del>
					<?php endif; ?>
					<?php if ( '' !== $price ) : ?>
						<span class="ard-deal__price"><?php echo esc_html( $price ); ?> <?php echo Ardal_Currency::unit(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( 'yes' === $s['show_stock'] ) : ?>
				<div class="ard-deal__stock">
					<div class="ard-progress" role="progressbar" aria-valuenow="<?php echo esc_attr( $pct ); ?>" aria-valuemin="0" aria-valuemax="100">
						<span style="width:<?php echo esc_attr( $pct ); ?>%"></span>
					</div>
					<p class="ard-deal__stock-text"><?php echo esc_html( $this->stock_text( $s, $sold, $total ) ); ?></p>
				</div>
			<?php endif; ?>
		</article>
		<?php
	}

	/**
	 * @param array $s
	 * @param int   $count
	 * @return string
	 */
	private function meta_text( $s, $count ) {
		$meta = trim( (string) $s['meta'] );

		if ( '' !== $meta ) {
			return $meta;
		}

		return Ardal_Helpers::fa_digits( $count ) . ' کالا';
	}

	/**
	 * خانه‌های شمارنده.
	 *
	 * @param array $s
	 */
	private function render_clock( $s ) {
		$remaining = $this->remaining_seconds( $s );
		$units     = array(
			'days'    => 'روز',
			'hours'   => 'ساعت',
			'minutes' => 'دقیقه',
			'seconds' => 'ثانیه',
		);

		$show_days = 'date' === $s['timer_mode'] && 'yes' === $s['show_days'];

		if ( ! $show_days ) {
			unset( $units['days'] );
		}

		$values = $this->split_time( $remaining, $show_days );
		?>
		<div
			class="ard-clock ard-deal__clock"
			data-ard-countdown
			data-remaining="<?php echo esc_attr( $remaining ); ?>"
			data-mode="<?php echo esc_attr( $s['timer_mode'] ); ?>"
			data-days="<?php echo $show_days ? '1' : '0'; ?>"
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

	/**
	 * @param int  $seconds
	 * @param bool $with_days
	 * @return array
	 */
	private function split_time( $seconds, $with_days ) {
		$seconds = max( 0, (int) $seconds );

		$days  = $with_days ? (int) floor( $seconds / DAY_IN_SECONDS ) : 0;
		$hours = (int) floor( ( $seconds - $days * DAY_IN_SECONDS ) / HOUR_IN_SECONDS );

		return array(
			'days'    => str_pad( (string) $days, 2, '0', STR_PAD_LEFT ),
			'hours'   => str_pad( (string) $hours, 2, '0', STR_PAD_LEFT ),
			'minutes' => str_pad( (string) ( (int) floor( $seconds / MINUTE_IN_SECONDS ) % 60 ), 2, '0', STR_PAD_LEFT ),
			'seconds' => str_pad( (string) ( $seconds % 60 ), 2, '0', STR_PAD_LEFT ),
		);
	}

	/**
	 * @param array $s
	 * @return int
	 */
	private function remaining_seconds( $s ) {
		$now = (int) current_time( 'timestamp' ); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested

		if ( 'daily' === $s['timer_mode'] ) {
			return max( 0, strtotime( 'tomorrow', $now ) - $now );
		}

		if ( 'date' === $s['timer_mode'] && ! empty( $s['end_date'] ) ) {
			$end = strtotime( $s['end_date'] );

			return $end ? max( 0, $end - $now ) : 0;
		}

		return 0;
	}

	/**
	 * @param array $s
	 * @param int   $sold
	 * @param int   $total
	 * @return string
	 */
	private function stock_text( $s, $sold, $total ) {
		return str_replace(
			array( '{sold}', '{total}', '{left}' ),
			array(
				Ardal_Helpers::fa_digits( $sold ),
				Ardal_Helpers::fa_digits( $total ),
				Ardal_Helpers::fa_digits( max( 0, $total - $sold ) ),
			),
			(string) $s['stock_template']
		);
	}

	/**
	 * آیتم‌ها با منبع دستی یا ووکامرس، به‌علاوهٔ داده‌های موجودی.
	 *
	 * @param array $s
	 * @return array
	 */
	private function get_deal_items( $s ) {
		$items = $this->get_product_items( $s );

		// محصولات ووکامرس فیلد فروش‌رفته/کل ندارند؛ از آمار فروش و موجودی می‌سازیمشان.
		foreach ( $items as $i => $item ) {
			if ( ! isset( $item['sold'] ) ) {
				$items[ $i ]['sold'] = isset( $item['wc_sold'] ) ? (int) $item['wc_sold'] : 0;
			}

			if ( ! isset( $item['total'] ) ) {
				$stock = isset( $item['wc_stock'] ) ? (int) $item['wc_stock'] : 0;
				$sold  = (int) $items[ $i ]['sold'];

				$items[ $i ]['total'] = $stock > 0 ? $sold + $stock : max( 1, $sold );
			}
		}

		return $items;
	}
}
