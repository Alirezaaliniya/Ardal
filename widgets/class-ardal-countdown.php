<?php
/**
 * ویجت شمارش معکوس مستقل.
 *
 * فرق آن با شمارندهٔ «پیشنهاد ویژه» این است که به هیچ اسلایدر و کارتی وصل
 * نیست و هرجای صفحه می‌نشیند. چهار منبع زمان دارد: تاریخ مشخص، چرخهٔ روزانه،
 * مدت ثابت از لحظهٔ بازدید، و تاریخ پایان تخفیفِ پرتخفیف‌ترین محصول فروشگاه.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Countdown extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-countdown';
	}

	public function get_title() {
		return 'اردال — شمارش معکوس';
	}

	public function get_icon() {
		return 'eicon-countdown';
	}

	public function get_keywords() {
		return array( 'اردال', 'شمارش', 'معکوس', 'تایمر', 'تخفیف', 'countdown', 'timer' );
	}

	protected function register_controls() {

		/* ------------------------------------------------ زمان */

		$this->start_controls_section(
			'section_time',
			array( 'label' => 'زمان' )
		);

		$this->add_control(
			'mode',
			array(
				'label'   => 'منبع زمان',
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'     => 'تا یک تاریخ مشخص',
					'daily'    => 'روزانه — تا پایان امروز و ریست خودکار',
					'duration' => 'مدت ثابت از لحظهٔ بازدید',
					'deal'     => 'تا پایان تخفیف پرتخفیف‌ترین محصول',
				),
			)
		);

		$this->add_control(
			'end_date',
			array(
				'label'       => 'تاریخ پایان',
				'type'        => Controls_Manager::DATE_TIME,
				'default'     => gmdate( 'Y-m-d H:i', current_time( 'timestamp' ) + DAY_IN_SECONDS ), // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
				'condition'   => array( 'mode' => 'date' ),
				'description' => 'زمان بر اساس منطقه زمانی سایت محاسبه می‌شود.',
			)
		);

		$this->add_control(
			'dur_days',
			array(
				'label'     => 'مدت — روز',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 365,
				'default'   => 0,
				/*
				 * این عددها دو جا به کار می‌آیند: حالت «مدت ثابت»، و وقتی در
				 * حالت «پرتخفیف‌ترین محصول» چیزی پیدا نشد و جایگزین هم مدت
				 * ثابت است.
				 */
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'mode',
							'operator' => '===',
							'value'    => 'duration',
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'mode',
									'operator' => '===',
									'value'    => 'deal',
								),
								array(
									'name'     => 'deal_fallback',
									'operator' => '===',
									'value'    => 'duration',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'dur_hours',
			array(
				'label'     => 'مدت — ساعت',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 23,
				'default'   => 12,
				/*
				 * این عددها دو جا به کار می‌آیند: حالت «مدت ثابت»، و وقتی در
				 * حالت «پرتخفیف‌ترین محصول» چیزی پیدا نشد و جایگزین هم مدت
				 * ثابت است.
				 */
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'mode',
							'operator' => '===',
							'value'    => 'duration',
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'mode',
									'operator' => '===',
									'value'    => 'deal',
								),
								array(
									'name'     => 'deal_fallback',
									'operator' => '===',
									'value'    => 'duration',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'dur_minutes',
			array(
				'label'     => 'مدت — دقیقه',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 59,
				'default'   => 0,
				/*
				 * این عددها دو جا به کار می‌آیند: حالت «مدت ثابت»، و وقتی در
				 * حالت «پرتخفیف‌ترین محصول» چیزی پیدا نشد و جایگزین هم مدت
				 * ثابت است.
				 */
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'mode',
							'operator' => '===',
							'value'    => 'duration',
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'mode',
									'operator' => '===',
									'value'    => 'deal',
								),
								array(
									'name'     => 'deal_fallback',
									'operator' => '===',
									'value'    => 'duration',
								),
							),
						),
					),
				),
			)
		);

		$this->add_control(
			'dur_keep',
			array(
				'label'        => 'مهلت برای هر بازدیدکننده ذخیره شود',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'mode' => 'duration' ),
				'description'  => 'روشن: مهلت هر کاربر یک‌بار تعیین می‌شود و با تازه‌کردن صفحه از نو شروع نمی‌شود. خاموش: هر بار بارگذاری، از اول.',
			)
		);

		$this->add_control(
			'deal_metric',
			array(
				'label'     => 'معیار «پرتخفیف‌ترین»',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'percent',
				'options'   => array(
					'percent' => 'بیشترین درصد تخفیف',
					'amount'  => 'بیشترین مبلغ تخفیف',
				),
				'condition' => array( 'mode' => 'deal' ),
			)
		);

		$this->add_control(
			'deal_cat',
			array(
				'label'       => 'فقط از این دسته',
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->category_options(),
				'default'     => '',
				'label_block' => true,
				'condition'   => array( 'mode' => 'deal' ),
				'description' => 'خالی = همهٔ فروشگاه.',
			)
		);

		$this->add_control(
			'deal_fallback',
			array(
				'label'       => 'اگر چنین محصولی نبود',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'hide',
				'options'     => array(
					'hide'     => 'ویجت را نشان نده',
					'duration' => 'به‌جایش مدت ثابت بشمار',
					'message'  => 'فقط پیام پایان را نشان بده',
				),
				'condition'   => array( 'mode' => 'deal' ),
				'description' => '«مدت ثابت» از همان عددهای بخش مدت استفاده می‌کند.',
			)
		);

		$this->add_control(
			'show_card',
			array(
				'label'        => 'کارت محصول هم نمایش داده شود',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'mode' => 'deal' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ سربرگ */

		$this->start_controls_section(
			'section_header',
			array( 'label' => 'سربرگ' )
		);

		$this->add_control(
			'title',
			array(
				'label'       => 'عنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'پیشنهاد شگفت‌انگیز',
				'label_block' => true,
				'description' => 'خالی = بدون عنوان.',
			)
		);

		$this->add_tag_control(
			'title_tag',
			'تگ عنوان',
			'h3',
			array( 'condition' => array( 'title!' => '' ) )
		);

		$this->add_control(
			'subtitle',
			array(
				'label'       => 'زیرعنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			)
		);

		$this->add_control(
			'show_icon',
			array(
				'label'        => 'نمایش آیکون',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'     => 'آیکون',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'clock',
				'options'   => Ardal_Helpers::icon_options(),
				'condition' => array( 'show_icon' => 'yes' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ خانه‌ها */

		$this->start_controls_section(
			'section_units',
			array( 'label' => 'خانه‌های شمارنده' )
		);

		foreach ( $this->units() as $key => $unit ) {
			$this->add_control(
				'show_' . $key,
				array(
					'label'        => 'نمایش «' . $unit . '»',
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'days' === $key ? '' : 'yes',
					'return_value' => 'yes',
				)
			);

			$this->add_control(
				'label_' . $key,
				array(
					'label'     => 'برچسب «' . $unit . '»',
					'type'      => Controls_Manager::TEXT,
					'default'   => $unit,
					'condition' => array( 'show_' . $key => 'yes' ),
				)
			);
		}

		$this->add_control(
			'show_sep',
			array(
				'label'        => 'دونقطه بین خانه‌ها',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_labels',
			array(
				'label'        => 'نمایش برچسب زیر عددها',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ پایان مهلت */

		$this->start_controls_section(
			'section_expire',
			array( 'label' => 'پایان مهلت' )
		);

		$this->add_control(
			'expire_action',
			array(
				'label'     => 'وقتی زمان تمام شد',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'message',
				'options'   => array(
					'message' => 'پیام پایان را نشان بده',
					'hide'    => 'کل ویجت پنهان شود',
					'keep'    => 'روی صفر بماند',
				),
				'condition' => array( 'mode!' => array( 'daily', 'duration' ) ),
			)
		);

		$this->add_control(
			'expire_message',
			array(
				'label'       => 'پیام پایان',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'default'     => 'مهلت این پیشنهاد به پایان رسید.',
				'label_block' => true,
				'condition'   => array( 'expire_action' => 'message' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ ظاهر */

		$this->start_controls_section(
			'section_style',
			array(
				'label' => 'ظاهر',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'box_bg',
			array(
				'label'     => 'زمینه کادر',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-cdown' => '--ard-cdown-bg:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'box_radius',
			array(
				'label'      => 'گردی کادر',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-cdown' => '--ard-cdown-radius:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_pad',
			array(
				'label'      => 'فاصله داخلی کادر',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 48 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-cdown' => '--ard-cdown-pad:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'align',
			array(
				'label'     => 'چیدمان',
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => array(
					'flex-start' => array( 'title' => 'شروع', 'icon' => 'eicon-text-align-right' ),
					'center'     => array( 'title' => 'وسط', 'icon' => 'eicon-text-align-center' ),
					'flex-end'   => array( 'title' => 'پایان', 'icon' => 'eicon-text-align-left' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .ard-cdown__clock' => 'justify-content:{{VALUE}};',
					'{{WRAPPER}} .ard-cdown__head'  => 'justify-content:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'clock_order',
			array(
				'label'        => 'ترتیب خانه‌ها',
				'type'         => Controls_Manager::SELECT,
				'default'      => 'ltr',
				'options'      => array(
					'ltr' => 'مثل ساعت (روز : ساعت : دقیقه : ثانیه از چپ)',
					'rtl' => 'راست‌به‌چپ (روز در سمت راست)',
				),
				'description'  => 'زمان در فارسی هم مثل عدد از چپ نوشته می‌شود («۱۲:۳۰»)؛ حالت پیش‌فرض همین را رعایت می‌کند.',
				'prefix_class' => 'ard-clock-order-',
			)
		);

		$this->add_control(
			'head_color',
			array(
				'label'     => 'رنگ عنوان و آیکون',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-cdown' => '--ard-cdown-title:{{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typo',
				'label'    => 'قلم عنوان',
				'selector' => '{{WRAPPER}} .ard-cdown__title',
			)
		);

		$this->add_control(
			'sub_color',
			array(
				'label'     => 'رنگ زیرعنوان',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-cdown' => '--ard-cdown-sub:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'cell_heading',
			array(
				'label'     => 'خانه‌های عدد',
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'cell_bg',
			array(
				'label'     => 'زمینه خانه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-cdown .ard-clock__cell' => 'background:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'num_color',
			array(
				'label'     => 'رنگ عدد',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-cdown .ard-clock__num' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => 'رنگ برچسب',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ard-cdown .ard-clock__label' => 'color:{{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'num_typo',
				'label'    => 'قلم عددها',
				'selector' => '{{WRAPPER}} .ard-cdown .ard-clock__num',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'label_typo',
				'label'     => 'قلم برچسب‌ها',
				'selector'  => '{{WRAPPER}} .ard-cdown .ard-clock__label',
				'condition' => array( 'show_labels' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'cell_pad',
			array(
				'label'      => 'فاصله داخلی خانه',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-cdown .ard-clock__cell' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'label_gap',
			array(
				'label'      => 'فاصله عدد تا برچسب',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 20 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-cdown .ard-clock__label' => 'margin-block-start:{{SIZE}}{{UNIT}};',
				),
				'condition'  => array( 'show_labels' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'cell_w',
			array(
				'label'      => 'کمینه پهنای خانه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 36, 'max' => 140 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-cdown .ard-clock__cell' => 'min-inline-size:{{SIZE}}{{UNIT}};flex:0 0 auto;',
				),
			)
		);

		$this->add_control(
			'cell_radius',
			array(
				'label'      => 'گردی خانه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 30 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-cdown .ard-clock__cell' => 'border-radius:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'cell_gap',
			array(
				'label'      => 'فاصله خانه‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 32 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-cdown .ard-clock' => 'gap:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$plan = $this->plan( $s );

		if ( ! $plan ) {
			// حالت «نشان نده»: در ویرایشگر باید چیزی بماند که بشود انتخابش کرد.
			$this->render_empty( 'همین حالا محصولی با تخفیفِ زمان‌دار در فروشگاه نیست، پس این ویجت در سایت نمایش داده نمی‌شود. برای چیدمان، «اگر چنین محصولی نبود» را روی مدت ثابت بگذارید.' );

			return;
		}

		if ( 'hidden' === $plan['state'] ) {
			// تاریخ گذشته و کاربر خواسته ویجت برود؛ در ویرایشگر جایش بماند.
			$this->render_empty( 'زمان این شمارنده گذشته است و در سایت نمایش داده نمی‌شود.' );

			return;
		}

		if ( 'expired' === $plan['state'] ) {
			?>
			<div class="ard-cdown ard-cdown--done">
				<p class="ard-cdown__msg"><?php echo esc_html( isset( $s['expire_message'] ) ? $s['expire_message'] : 'مهلت این پیشنهاد به پایان رسید.' ); ?></p>
			</div>
			<?php

			return;
		}

		$units   = $this->visible_units( $s );
		$values  = $this->split( $plan['remaining'], isset( $units['days'] ) );
		$sep     = 'yes' === $s['show_sep'];
		$labels  = 'yes' === $s['show_labels'];
		$expire  = in_array( $plan['mode'], array( 'daily', 'duration' ), true ) || ! isset( $s['expire_action'] )
			? 'keep'
			: $s['expire_action'];
		$product = isset( $plan['card'] ) ? $plan['card'] : null;
		$message = isset( $s['expire_message'] ) ? (string) $s['expire_message'] : '';
		?>
		<div class="ard-cdown" data-ard-cdown data-expire="<?php echo esc_attr( $expire ); ?>">

			<?php if ( '' !== trim( (string) $s['title'] ) || '' !== trim( (string) $s['subtitle'] ) ) : ?>
				<div class="ard-cdown__head">
					<?php if ( 'yes' === $s['show_icon'] ) : ?>
						<span class="ard-cdown__icon" aria-hidden="true">
							<?php echo Ardal_Helpers::icon( $s['icon'], 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
					<?php endif; ?>

					<div class="ard-cdown__titles">
						<?php if ( '' !== trim( (string) $s['title'] ) ) : ?>
							<?php $tag = self::tag( $s['title_tag'], 'h3' ); ?>
							<<?php echo esc_html( $tag ); ?> class="ard-cdown__title"><?php echo esc_html( $s['title'] ); ?></<?php echo esc_html( $tag ); ?>>
						<?php endif; ?>

						<?php if ( '' !== trim( (string) $s['subtitle'] ) ) : ?>
							<p class="ard-cdown__sub"><?php echo esc_html( $s['subtitle'] ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="ard-cdown__body">
				<div class="ard-cdown__clock">
					<div
						class="ard-clock"
						data-ard-countdown
						data-remaining="<?php echo esc_attr( $plan['remaining'] ); ?>"
						data-mode="<?php echo esc_attr( $plan['mode'] ); ?>"
						data-days="<?php echo isset( $units['days'] ) ? '1' : '0'; ?>"
						<?php if ( '' !== $plan['restart'] ) : ?>
							data-restart="<?php echo esc_attr( $plan['restart'] ); ?>"
						<?php endif; ?>
						<?php if ( '' !== $plan['keep'] ) : ?>
							data-keep="<?php echo esc_attr( $plan['keep'] ); ?>"
						<?php endif; ?>
						role="timer"
					>
						<?php $first = true; ?>
						<?php foreach ( $units as $key => $label ) : ?>
							<?php if ( ! $first && $sep ) : ?>
								<span class="ard-cdown__sep" aria-hidden="true">:</span>
							<?php endif; ?>
							<?php $first = false; ?>

							<div class="ard-clock__cell">
								<span class="ard-clock__num" data-unit="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( Ardal_Helpers::fa_digits( $values[ $key ] ) ); ?></span>

								<?php if ( $labels && '' !== $label ) : ?>
									<span class="ard-clock__label"><?php echo esc_html( $label ); ?></span>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<?php if ( $product ) : ?>
					<div class="ard-cdown__product">
						<?php
						$this->render_product_card(
							$product,
							array(
								// کارت تکی همسایه‌ای ندارد که بخواهد با آن هم‌تراز بماند.
								'reserve_old'  => false,
								'reserve_rate' => false,
							)
						);
						?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( 'message' === $expire ) : ?>
				<p class="ard-cdown__msg" hidden><?php echo esc_html( $message ); ?></p>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * نقشهٔ زمان: چه چیزی، از کی تا کی، و بعدش چه.
	 *
	 * خروجی null یعنی ویجت اصلاً نباید چاپ شود.
	 *
	 * @param array $s
	 * @return array|null
	 */
	private function plan( $s ) {
		$duration = $this->duration_seconds( $s );

		$base = array(
			'mode'      => $s['mode'],
			'remaining' => 0,
			'restart'   => '',
			'keep'      => '',
			'state'     => 'running',
		);

		if ( 'daily' === $s['mode'] ) {
			$now = (int) current_time( 'timestamp' ); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested

			return array_merge( $base, array( 'remaining' => max( 0, strtotime( 'tomorrow', $now ) - $now ) ) );
		}

		if ( 'duration' === $s['mode'] ) {
			return array_merge(
				$base,
				array(
					'remaining' => $duration,
					'restart'   => (string) $duration,
					/*
					 * کلید ذخیره از شناسهٔ همین ویجت می‌آید تا دو شمارندهٔ
					 * متفاوت در یک صفحه مهلت هم را پاک نکنند.
					 */
					'keep'      => ( isset( $s['dur_keep'] ) && 'yes' === $s['dur_keep'] ) ? 'ardal_cd_' . $this->get_id() : '',
				)
			);
		}

		if ( 'date' === $s['mode'] ) {
			$left = $this->seconds_until( $s['end_date'] );

			if ( $left < 1 ) {
				return array_merge( $base, array( 'state' => $this->expired_state( $s ) ) );
			}

			return array_merge( $base, array( 'remaining' => $left ) );
		}

		/* ---------------------------------------- حالت پرتخفیف‌ترین محصول */

		$deal = Ardal_Helpers::has_woo()
			? Ardal_Product::top_sale_deal( $s['deal_metric'], (int) $s['deal_cat'] )
			: null;

		if ( ! $deal ) {
			if ( 'duration' === $s['deal_fallback'] ) {
				return array_merge(
					$base,
					array(
						'mode'      => 'duration',
						'remaining' => $duration,
						'restart'   => (string) $duration,
					)
				);
			}

			if ( 'message' === $s['deal_fallback'] ) {
				return array_merge( $base, array( 'state' => 'expired' ) );
			}

			return null;
		}

		$left = max( 0, $deal['ends'] - time() );

		if ( $left < 1 ) {
			return array_merge( $base, array( 'state' => $this->expired_state( $s ) ) );
		}

		$plan = array_merge( $base, array( 'mode' => 'date', 'remaining' => $left ) );

		if ( 'yes' === $s['show_card'] ) {
			$product = wc_get_product( $deal['id'] );

			if ( $product ) {
				$plan['card'] = Ardal_Helpers::product_to_card( $product );
			}
		}

		return $plan;
	}

	/**
	 * وقتی زمان از قبل گذشته: یا پیام، یا اصلاً چاپ نشود، یا صفرِ ساکن.
	 *
	 * @param array $s
	 * @return string
	 */
	private function expired_state( $s ) {
		$action = isset( $s['expire_action'] ) ? $s['expire_action'] : 'message';

		return 'hide' === $action ? 'hidden' : ( 'message' === $action ? 'expired' : 'running' );
	}

	/**
	 * مدت ثابت به ثانیه. صفر بی‌معناست، پس به یک ساعت برمی‌گردد.
	 *
	 * @param array $s
	 * @return int
	 */
	private function duration_seconds( $s ) {
		$seconds = ( $this->opt( $s, 'dur_days' ) * DAY_IN_SECONDS )
			+ ( $this->opt( $s, 'dur_hours', 12 ) * HOUR_IN_SECONDS )
			+ ( $this->opt( $s, 'dur_minutes' ) * MINUTE_IN_SECONDS );

		return $seconds > 0 ? $seconds : HOUR_IN_SECONDS;
	}

	/**
	 * عدد یک تنظیم، حتی وقتی المنتور آن را نداده است.
	 *
	 * `get_settings_for_display()` کنترل‌هایی را که شرطشان برقرار نیست از
	 * آرایه بیرون می‌گذارد؛ پس هر تنظیمی که ممکن است خارج از شرط خودش خوانده
	 * شود باید از این راه بیاید، نه با ایندکس مستقیم.
	 *
	 * @param array  $s
	 * @param string $key
	 * @param int    $fallback
	 * @return int
	 */
	private function opt( $s, $key, $fallback = 0 ) {
		return isset( $s[ $key ] ) && '' !== $s[ $key ] ? (int) $s[ $key ] : (int) $fallback;
	}

	/**
	 * ثانیه تا یک تاریخِ تنظیمات، بر پایهٔ منطقهٔ زمانی سایت.
	 *
	 * @param string $date
	 * @return int
	 */
	private function seconds_until( $date ) {
		if ( '' === trim( (string) $date ) ) {
			return 0;
		}

		$end = strtotime( $date );
		$now = (int) current_time( 'timestamp' ); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested

		return $end ? max( 0, $end - $now ) : 0;
	}

	/**
	 * نام فارسی واحدها.
	 *
	 * @return array
	 */
	private function units() {
		return array(
			'days'    => 'روز',
			'hours'   => 'ساعت',
			'minutes' => 'دقیقه',
			'seconds' => 'ثانیه',
		);
	}

	/**
	 * واحدهای روشن، با برچسب کاربر.
	 *
	 * برچسبِ خالی همان‌طور خالی برمی‌گردد و جایگزینِ فارسی نمی‌گیرد: پاک‌کردن
	 * متن یعنی «این برچسب را نمی‌خواهم»، نه «مقدار پیش‌فرض را بگذار». خودِ
	 * چاپ‌کننده هم با دیدن رشتهٔ خالی، اصلاً عنصری نمی‌سازد تا جای خالی نماند.
	 *
	 * @param array $s
	 * @return array
	 */
	private function visible_units( $s ) {
		$out = array();

		foreach ( $this->units() as $key => $fallback ) {
			if ( 'yes' !== $s[ 'show_' . $key ] ) {
				continue;
			}

			$out[ $key ] = trim( (string) $s[ 'label_' . $key ] );
		}

		// همه خاموش: دست‌کم ساعت و دقیقه بماند، وگرنه شمارنده‌ای نمی‌ماند.
		return $out ? $out : array( 'hours' => 'ساعت', 'minutes' => 'دقیقه' );
	}

	/**
	 * شکستن ثانیه به خانه‌ها. وقتی «روز» خاموش است، روزها داخل ساعت می‌ریزند.
	 *
	 * @param int  $seconds
	 * @param bool $with_days
	 * @return array
	 */
	private function split( $seconds, $with_days ) {
		$seconds = max( 0, (int) $seconds );
		$days    = $with_days ? (int) floor( $seconds / DAY_IN_SECONDS ) : 0;
		$hours   = (int) floor( ( $seconds - $days * DAY_IN_SECONDS ) / HOUR_IN_SECONDS );

		return array(
			'days'    => str_pad( (string) $days, 2, '0', STR_PAD_LEFT ),
			'hours'   => str_pad( (string) $hours, 2, '0', STR_PAD_LEFT ),
			'minutes' => str_pad( (string) ( (int) floor( $seconds / MINUTE_IN_SECONDS ) % 60 ), 2, '0', STR_PAD_LEFT ),
			'seconds' => str_pad( (string) ( $seconds % 60 ), 2, '0', STR_PAD_LEFT ),
		);
	}

	/**
	 * دسته‌های محصول برای سلکت، بدون «بدون دسته‌بندی».
	 *
	 * @return array
	 */
	private function category_options() {
		$out = array( '' => 'همهٔ فروشگاه' );

		if ( ! taxonomy_exists( 'product_cat' ) ) {
			return $out;
		}

		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'exclude'    => class_exists( 'Ardal_Archive' ) ? Ardal_Archive::excluded_terms( 'product_cat' ) : array(),
			)
		);

		if ( is_wp_error( $terms ) ) {
			return $out;
		}

		foreach ( $terms as $term ) {
			$out[ $term->term_id ] = $term->name;
		}

		return $out;
	}
}
