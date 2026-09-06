<?php
/**
 * ویجت پیشنهاد ویژه با شمارش معکوس و نوار موجودی.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

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
		return array( 'اردال', 'پیشنهاد', 'شمارش معکوس', 'تخفیف', 'deal', 'countdown' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'محصول' )
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
				'label'   => 'متن گوشه (تعداد کالا)',
				'type'    => Controls_Manager::TEXT,
				'default' => '۱۲ کالا',
			)
		);

		$this->add_control(
			'wc_product',
			array(
				'label'       => 'گرفتن اطلاعات از محصول ووکامرس',
				'type'        => Controls_Manager::NUMBER,
				'default'     => 0,
				'min'         => 0,
				'description' => 'شناسه محصول را وارد کنید تا عنوان، قیمت و تصویر خودکار پر شود. برای ورود دستی، صفر بگذارید.',
				'condition'   => Ardal_Helpers::has_woo() ? array() : array( 'never' => 'never' ),
			)
		);

		$this->add_control(
			'image',
			array(
				'label' => 'تصویر محصول',
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => 'نام محصول',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'default'     => 'تردمیل خانگی پرو-فیت مدل T۹۰۰ تاشو',
				'label_block' => true,
			)
		);

		$this->add_control(
			'old_price',
			array(
				'label'   => 'قیمت قبل',
				'type'    => Controls_Manager::TEXT,
				'default' => '۲۸٫۹۰۰٫۰۰۰',
			)
		);

		$this->add_control(
			'price',
			array(
				'label'   => 'قیمت با تخفیف',
				'type'    => Controls_Manager::TEXT,
				'default' => '۱۹٫۶۵۰٫۰۰۰',
			)
		);

		$this->add_control(
			'off',
			array(
				'label'       => 'درصد تخفیف',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'خالی = محاسبه خودکار',
			)
		);

		$this->add_control(
			'link',
			array(
				'label'   => 'لینک محصول',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '' ),
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
			'sold',
			array(
				'label'     => 'تعداد فروش‌رفته',
				'type'      => Controls_Manager::NUMBER,
				'default'   => 8,
				'min'       => 0,
				'condition' => array( 'show_stock' => 'yes' ),
			)
		);

		$this->add_control(
			'total',
			array(
				'label'     => 'کل موجودی',
				'type'      => Controls_Manager::NUMBER,
				'default'   => 25,
				'min'       => 1,
				'condition' => array( 'show_stock' => 'yes' ),
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

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$s = $this->maybe_fill_from_woo( $s );

		$price = Ardal_Helpers::price( $s['price'] );
		$old   = Ardal_Helpers::price( $s['old_price'] );
		$off   = '' !== trim( (string) $s['off'] )
			? Ardal_Helpers::fa_digits( $s['off'] )
			: Ardal_Helpers::discount( $old, $price );

		$sold  = max( 0, (int) $s['sold'] );
		$total = max( 1, (int) $s['total'] );
		$pct   = min( 100, (int) round( ( $sold / $total ) * 100 ) );
		?>
		<section class="ard-deal ard-deal--<?php echo esc_attr( $s['variant'] ); ?>">

			<?php if ( 'card' === $s['variant'] ) : ?>
				<div class="ard-deal__head">
					<h2 class="ard-deal__label"><?php echo esc_html( $s['label'] ); ?></h2>
					<?php if ( '' !== trim( (string) $s['meta'] ) ) : ?>
						<span class="ard-deal__meta"><?php echo esc_html( $s['meta'] ); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="ard-deal__body">
				<div class="ard-deal__media">
					<?php echo Ardal_Helpers::image( $s['image'], '۴۰۰×۴۰۰', $s['title'], 'medium' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>

				<div class="ard-deal__info">
					<?php if ( 'bar' === $s['variant'] ) : ?>
						<span class="ard-badge ard-badge--sale ard-deal__inline-badge">
							<?php echo esc_html( trim( $s['label'] . ( '' !== $off ? ' — ' . $off : '' ) ) ); ?>
						</span>
					<?php endif; ?>

					<h3 class="ard-deal__title">
						<?php if ( ! empty( $s['link']['url'] ) ) : ?>
							<a<?php echo Ardal_Helpers::link_attrs( $s['link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $s['title'] ); ?></a>
						<?php else : ?>
							<?php echo esc_html( $s['title'] ); ?>
						<?php endif; ?>
					</h3>

					<div class="ard-deal__prices">
						<?php if ( '' !== $off && 'bar' !== $s['variant'] ) : ?>
							<span class="ard-badge ard-badge--sale"><?php echo esc_html( $off ); ?></span>
						<?php endif; ?>
						<?php if ( '' !== $old ) : ?>
							<del class="ard-deal__old"><?php echo esc_html( $old ); ?></del>
						<?php endif; ?>
						<?php if ( '' !== $price ) : ?>
							<span class="ard-deal__price"><?php echo esc_html( $price ); ?> <small>تومان</small></span>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( 'none' !== $s['timer_mode'] ) : ?>
					<div class="ard-deal__timer">
						<?php $this->render_clock( $s ); ?>
					</div>
				<?php endif; ?>

				<?php if ( 'yes' === $s['show_stock'] ) : ?>
					<div class="ard-deal__stock">
						<?php if ( 'bar' === $s['variant'] ) : ?>
							<div class="ard-deal__stock-head">
								<span>فروش رفته</span>
								<strong><?php echo esc_html( Ardal_Helpers::fa_digits( $sold ) . ' از ' . Ardal_Helpers::fa_digits( $total ) ); ?></strong>
							</div>
						<?php endif; ?>

						<div class="ard-progress" role="progressbar" aria-valuenow="<?php echo esc_attr( $pct ); ?>" aria-valuemin="0" aria-valuemax="100">
							<span style="width:<?php echo esc_attr( $pct ); ?>%"></span>
						</div>

						<?php if ( 'card' === $s['variant'] ) : ?>
							<p class="ard-deal__stock-text"><?php echo esc_html( $this->stock_text( $s, $sold, $total ) ); ?></p>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}

	/**
	 * خانه‌های ساعت/دقیقه/ثانیه.
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
			class="ard-clock"
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
			$midnight = strtotime( 'tomorrow', $now );

			return max( 0, $midnight - $now );
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
		$text = (string) $s['stock_template'];

		return str_replace(
			array( '{sold}', '{total}', '{left}' ),
			array(
				Ardal_Helpers::fa_digits( $sold ),
				Ardal_Helpers::fa_digits( $total ),
				Ardal_Helpers::fa_digits( max( 0, $total - $sold ) ),
			),
			$text
		);
	}

	/**
	 * پر کردن فیلدها از یک محصول ووکامرس، اگر شناسه داده شده باشد.
	 *
	 * @param array $s
	 * @return array
	 */
	private function maybe_fill_from_woo( $s ) {
		$id = isset( $s['wc_product'] ) ? (int) $s['wc_product'] : 0;

		if ( $id <= 0 || ! Ardal_Helpers::has_woo() || ! function_exists( 'wc_get_product' ) ) {
			return $s;
		}

		$product = wc_get_product( $id );

		if ( ! $product ) {
			return $s;
		}

		$card = Ardal_Helpers::product_to_card( $product );

		$s['title']     = $card['title'];
		$s['price']     = $card['price'];
		$s['old_price'] = $card['old_price'];
		$s['off']       = $card['off'];
		$s['link']      = $card['link'];

		if ( empty( $s['image']['url'] ) || false !== strpos( (string) $s['image']['url'], 'placeholder' ) ) {
			$s['image'] = $card['image'];
		}

		if ( $product->managing_stock() ) {
			$stock = (int) $product->get_stock_quantity();

			if ( $stock > 0 ) {
				$s['total'] = max( $stock, (int) $s['sold'] + $stock );
			}
		}

		return $s;
	}
}
