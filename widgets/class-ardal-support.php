<?php
/**
 * ویجت دکمهٔ شناور پشتیبانی.
 *
 * یک دکمهٔ ثابت در گوشهٔ صفحه که با کلیک، پنلی کوچک در همان گوشه باز می‌کند و
 * راه‌های ارتباطی (واتساپ، تلگرام، تماس، ایمیل و…) را نشان می‌دهد.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

class Ardal_Widget_Support extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-support';
	}

	public function get_title() {
		return 'اردال — دکمه شناور پشتیبانی';
	}

	public function get_icon() {
		return 'eicon-headphones';
	}

	public function get_keywords() {
		return array( 'اردال', 'پشتیبانی', 'واتساپ', 'تلگرام', 'تماس', 'شناور', 'support', 'whatsapp', 'chat' );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-support' );
	}

	public function get_script_depends() {
		return array( 'ardal-support' );
	}

	protected function register_controls() {

		/* ------------------------------------------------ راه‌های ارتباطی */

		$this->start_controls_section(
			'section_items',
			array( 'label' => 'راه‌های ارتباطی' )
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'icon',
			array(
				'label'   => 'آیکون',
				'type'    => Controls_Manager::SELECT,
				'default' => 'chat',
				'options' => self::icon_options(),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'       => 'عنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'راه ارتباطی',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'subtitle',
			array(
				'label'       => 'زیرعنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'type',
			array(
				'label'   => 'نوع مقصد',
				'type'    => Controls_Manager::SELECT,
				'default' => 'whatsapp',
				'options' => array(
					'whatsapp'  => 'واتساپ (شماره)',
					'telegram'  => 'تلگرام (نام کاربری)',
					'instagram' => 'اینستاگرام (نام کاربری)',
					'phone'     => 'تماس تلفنی (شماره)',
					'sms'       => 'پیامک (شماره)',
					'email'     => 'ایمیل',
					'url'       => 'نشانی دلخواه',
					'none'      => 'بدون پیوند (فقط متن)',
				),
			)
		);

		$repeater->add_control(
			'value',
			array(
				'label'       => 'مقدار',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'placeholder' => '09121234567',
				'description' => 'شماره، نام کاربری، ایمیل یا نشانی. اگر نشانی کامل بنویسید همان استفاده می‌شود.',
				'condition'   => array( 'type!' => 'none' ),
			)
		);

		$repeater->add_control(
			'badge',
			array(
				'label'       => 'نشان کوچک',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'پاسخ فوری',
			)
		);

		$repeater->add_control(
			'color',
			array(
				'label'       => 'رنگ آیکون',
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'description' => 'خالی = رنگ پیش‌فرض همان سرویس.',
			)
		);

		$repeater->add_control(
			'blank',
			array(
				'label'   => 'باز شدن در زبانه تازه',
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => array(
					'auto'  => 'خودکار (فقط پیوندهای بیرونی)',
					'blank' => 'همیشه',
					'self'  => 'هرگز',
				),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => 'آیتم‌ها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'icon'     => 'whatsapp',
						'title'    => 'واتساپ',
						'subtitle' => 'پاسخ‌گویی سریع، همین حالا',
						'type'     => 'whatsapp',
						'value'    => '',
					),
					array(
						'icon'     => 'telegram',
						'title'    => 'تلگرام',
						'subtitle' => 'ارسال پیام و پیگیری سفارش',
						'type'     => 'telegram',
						'value'    => '',
					),
					array(
						'icon'     => 'phone',
						'title'    => 'تماس تلفنی',
						'subtitle' => 'شنبه تا پنج‌شنبه، ۹ تا ۱۸',
						'type'     => 'phone',
						'value'    => '',
					),
				),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ دکمه */

		$this->start_controls_section(
			'section_launcher',
			array( 'label' => 'دکمه شناور' )
		);

		$this->add_control(
			'position',
			array(
				'label'   => 'گوشه',
				'type'    => Controls_Manager::SELECT,
				'default' => 'bottom-start',
				'options' => array(
					'bottom-start' => 'پایین، سمت شروع (راست)',
					'bottom-end'   => 'پایین، سمت پایان (چپ)',
					'top-start'    => 'بالا، سمت شروع (راست)',
					'top-end'      => 'بالا، سمت پایان (چپ)',
				),
			)
		);

		$this->add_responsive_control(
			'offset_side',
			array(
				'label'      => 'فاصله از کنار',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 120 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-sp' => '--ard-sp-side:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'offset_edge',
			array(
				'label'      => 'فاصله از بالا یا پایین',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 200 ) ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-sp' => '--ard-sp-edge:{{SIZE}}{{UNIT}};',
				),
				'description' => 'اگر نوار پایین موبایل دارید، این عدد را بالاتر بگذارید تا روی هم نیفتند.',
			)
		);

		$this->add_control(
			'z_index',
			array(
				'label'     => 'لایه (z-index)',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 99999,
				'default'   => 9998,
				'selectors' => array(
					'{{WRAPPER}} .ard-sp' => '--ard-sp-z:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'launcher_icon',
			array(
				'label'   => 'آیکون دکمه',
				'type'    => Controls_Manager::SELECT,
				'default' => 'headset',
				'options' => self::icon_options(),
			)
		);

		$this->add_control(
			'launcher_label',
			array(
				'label'       => 'برچسب کنار دکمه',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'پشتیبانی',
				'label_block' => true,
			)
		);

		$this->add_control(
			'label_mode',
			array(
				'label'     => 'نمایش برچسب',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'hover',
				'options'   => array(
					'hover'  => 'فقط هنگام اشاره',
					'always' => 'همیشه',
					'none'   => 'هرگز',
				),
				'condition' => array( 'launcher_label!' => '' ),
			)
		);

		$this->add_control(
			'badge',
			array(
				'label'       => 'نشان روی دکمه',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => '۱',
			)
		);

		$this->add_control(
			'pulse',
			array(
				'label'        => 'حلقه تپنده دور دکمه',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ پنل */

		$this->start_controls_section(
			'section_panel',
			array( 'label' => 'پنل' )
		);

		$this->add_control(
			'title',
			array(
				'label'       => 'عنوان پنل',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'پشتیبانی فروشگاه',
				'label_block' => true,
			)
		);

		$this->add_control(
			'subtitle',
			array(
				'label'       => 'زیرعنوان',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'برای راهنمایی خرید در خدمت شماییم',
				'label_block' => true,
			)
		);

		$this->add_control(
			'show_head_icon',
			array(
				'label'        => 'آیکون سربرگ',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'head_icon',
			array(
				'label'     => 'آیکون سربرگ',
				'type'      => Controls_Manager::SELECT,
				'default'   => 'support',
				'options'   => self::icon_options(),
				'condition' => array( 'show_head_icon' => 'yes' ),
			)
		);

		$this->add_control(
			'show_status',
			array(
				'label'        => 'نشان «آنلاین»',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'status_text',
			array(
				'label'     => 'متن وضعیت',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'هم‌اکنون آنلاین هستیم',
				'condition' => array( 'show_status' => 'yes' ),
			)
		);

		$this->add_control(
			'footer',
			array(
				'label'       => 'پانوشت پنل',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'default'     => '',
				'label_block' => true,
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ رفتار */

		$this->start_controls_section(
			'section_behavior',
			array( 'label' => 'رفتار' )
		);

		$this->add_control(
			'animation',
			array(
				'label'   => 'انیمیشن باز شدن',
				'type'    => Controls_Manager::SELECT,
				'default' => 'flip',
				'options' => array(
					'flip' => 'چرخش سه‌بعدی',
					'pop'  => 'بزرگ‌شدن',
					'tilt' => 'کج‌شدن',
					'none' => 'بدون انیمیشن',
				),
			)
		);

		$this->add_control(
			'backdrop',
			array(
				'label'        => 'پردهٔ تیره پشت پنل',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'فقط روی موبایل دیده می‌شود.',
			)
		);

		$this->add_control(
			'close_outside',
			array(
				'label'        => 'بستن با کلیک بیرون',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'auto_open',
			array(
				'label'       => 'باز شدن خودکار پس از',
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 120,
				'default'     => 0,
				'description' => 'به ثانیه. صفر = هرگز. نخستین اسکرول کاربر آن را لغو می‌کند تا مزاحم نشود.',
			)
		);

		$this->add_control(
			'remember_close',
			array(
				'label'        => 'بستنِ کاربر به یاد بماند',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'تا پایان همین نشست دیگر خودکار باز نمی‌شود.',
				'condition'    => array( 'auto_open!' => 0 ),
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
			'btn_bg',
			array(
				'label'     => 'زمینه دکمه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-sp' => '--ard-sp-btn-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'btn_ink',
			array(
				'label'     => 'رنگ آیکون دکمه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-sp' => '--ard-sp-btn-ink:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'btn_size',
			array(
				'label'      => 'اندازه دکمه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 40, 'max' => 90 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-sp' => '--ard-sp-btn:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'panel_bg',
			array(
				'label'     => 'زمینه پنل',
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array( '{{WRAPPER}} .ard-sp' => '--ard-sp-panel-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'head_bg',
			array(
				'label'     => 'زمینه سربرگ پنل',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-sp' => '--ard-sp-head-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'head_ink',
			array(
				'label'     => 'رنگ متن سربرگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-sp' => '--ard-sp-head-ink:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'item_ink',
			array(
				'label'     => 'رنگ متن آیتم‌ها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-sp' => '--ard-sp-item-ink:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'panel_w',
			array(
				'label'      => 'پهنای پنل',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 220, 'max' => 420 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-sp' => '--ard-sp-panel-w:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'radius',
			array(
				'label'      => 'گردی پنل',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 32 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-sp' => '--ard-sp-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'item_typo',
				'label'    => 'قلم آیتم‌ها',
				'selector' => '{{WRAPPER}} .ard-sp__item-title',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = $this->items( $s );

		if ( ! $items ) {
			$this->render_empty( 'هیچ آیتمی عنوان ندارد، پس در سایت چیزی نمایش داده نمی‌شود. از بخش «راه‌های ارتباطی» دست‌کم یک آیتم با عنوان اضافه کنید.' );

			return;
		}

		$uid      = 'ard-sp-' . $this->get_id();
		$panel_id = $uid . '-panel';
		$editor   = \Elementor\Plugin::$instance->editor->is_edit_mode();

		$config = array(
			'closeOutside'  => 'yes' === $s['close_outside'],
			'autoOpen'      => max( 0, (int) $s['auto_open'] ),
			'rememberClose' => 'yes' === $s['remember_close'],
			'key'           => $uid,
		);

		$label = trim( (string) $s['launcher_label'] );

		$classes = array_filter(
			array(
				'ard-sp',
				'ard-sp--' . $s['position'],
				'ard-sp--anim-' . $s['animation'],
				'yes' === $s['pulse'] ? 'ard-sp--pulse' : '',
				'yes' === $s['backdrop'] ? 'ard-sp--backdrop' : '',
				'ard-sp--label-' . ( '' !== $label ? $s['label_mode'] : 'none' ),
				// در ویرایشگر پنل جای ثابت می‌گیرد تا بشود چیدمانش را دید.
				$editor ? 'ard-sp--editor' : '',
			)
		);
		?>
		<?php if ( $editor ) : ?>
			<?php $this->editor_notice( $items ); ?>
		<?php endif; ?>

		<div id="<?php echo esc_attr( $uid ); ?>"
			class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
			data-ard-support="<?php echo esc_attr( wp_json_encode( $config ) ); ?>">

			<div class="ard-sp__backdrop" data-ard-sp-close></div>

			<div id="<?php echo esc_attr( $panel_id ); ?>"
				class="ard-sp__panel"
				role="dialog"
				aria-modal="false"
				aria-hidden="true"
				aria-labelledby="<?php echo esc_attr( $uid ); ?>-title">

				<div class="ard-sp__head">
					<?php if ( 'yes' === $s['show_head_icon'] ) : ?>
						<span class="ard-sp__head-icon" aria-hidden="true"><?php $this->icon( $s['head_icon'] ); ?></span>
					<?php endif; ?>

					<div class="ard-sp__head-text">
						<?php if ( '' !== trim( (string) $s['title'] ) ) : ?>
							<strong class="ard-sp__title" id="<?php echo esc_attr( $uid ); ?>-title"><?php echo esc_html( $s['title'] ); ?></strong>
						<?php endif; ?>

						<?php if ( '' !== trim( (string) $s['subtitle'] ) ) : ?>
							<span class="ard-sp__subtitle"><?php echo esc_html( $s['subtitle'] ); ?></span>
						<?php endif; ?>

						<?php if ( 'yes' === $s['show_status'] && '' !== trim( (string) $s['status_text'] ) ) : ?>
							<span class="ard-sp__status"><i aria-hidden="true"></i><?php echo esc_html( $s['status_text'] ); ?></span>
						<?php endif; ?>
					</div>

					<button type="button" class="ard-sp__close" data-ard-sp-close aria-label="بستن پنل پشتیبانی"><?php $this->icon( 'close' ); ?></button>
				</div>

				<ul class="ard-sp__items">
					<?php foreach ( $items as $index => $item ) : ?>
						<li class="ard-sp__item-wrap" style="--ard-sp-i:<?php echo (int) $index; ?>">
							<?php
							// ردیفِ بدون مقصد، به‌جای پیوند مرده، یک span ساده می‌شود.
							$tag = $item['static'] ? 'span' : 'a';
							?>
							<<?php echo esc_html( $tag ); ?>
								class="ard-sp__item<?php echo $item['static'] ? ' ard-sp__item--static' : ''; ?>"
								style="--ard-sp-item:<?php echo esc_attr( $item['color'] ); ?>"
								<?php if ( ! $item['static'] ) : ?>
									href="<?php echo esc_url( $item['href'] ); ?>"
									<?php echo $item['blank'] ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
								<?php endif; ?>>

								<span class="ard-sp__item-icon" aria-hidden="true"><?php $this->icon( $item['icon'] ); ?></span>

								<span class="ard-sp__item-text">
									<span class="ard-sp__item-title"><?php echo esc_html( $item['title'] ); ?></span>

									<?php if ( '' !== $item['subtitle'] ) : ?>
										<span class="ard-sp__item-sub"><?php echo esc_html( $item['subtitle'] ); ?></span>
									<?php endif; ?>
								</span>

								<?php if ( '' !== $item['badge'] ) : ?>
									<span class="ard-sp__item-badge"><?php echo esc_html( $item['badge'] ); ?></span>
								<?php endif; ?>

								<?php if ( ! $item['static'] ) : ?>
									<span class="ard-sp__item-arrow" aria-hidden="true"><?php $this->icon( 'chevron' ); ?></span>
								<?php endif; ?>
							</<?php echo esc_html( $tag ); ?>>
						</li>
					<?php endforeach; ?>
				</ul>

				<?php if ( '' !== trim( (string) $s['footer'] ) ) : ?>
					<div class="ard-sp__foot"><?php echo wp_kses_post( $s['footer'] ); ?></div>
				<?php endif; ?>
			</div>

			<button type="button"
				class="ard-sp__launcher"
				aria-expanded="false"
				aria-controls="<?php echo esc_attr( $panel_id ); ?>"
				aria-label="<?php echo esc_attr( '' !== $label ? $label : 'پشتیبانی' ); ?>">

				<span class="ard-sp__ring" aria-hidden="true"></span>
				<span class="ard-sp__icon ard-sp__icon--open" aria-hidden="true"><?php $this->icon( $s['launcher_icon'] ); ?></span>
				<span class="ard-sp__icon ard-sp__icon--close" aria-hidden="true"><?php $this->icon( 'close' ); ?></span>

				<?php if ( '' !== trim( (string) $s['badge'] ) ) : ?>
					<span class="ard-sp__badge"><?php echo esc_html( $s['badge'] ); ?></span>
				<?php endif; ?>
			</button>

			<?php if ( '' !== $label && 'none' !== $s['label_mode'] ) : ?>
				<span class="ard-sp__tip" aria-hidden="true"><?php echo esc_html( $label ); ?></span>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * کادر راهنما در ویرایشگر.
	 *
	 * دکمه شناور است و ممکن است پشت چیز دیگری بیفتد؛ این کادر جای ثابتی است
	 * که بشود ویجت را انتخاب و جابه‌جا کرد، و جای گفتن این است که کدام آیتم
	 * هنوز مقصد ندارد.
	 *
	 * @param array $items
	 */
	private function editor_notice( $items ) {
		$missing = array();

		foreach ( $items as $item ) {
			if ( $item['static'] && 'none' !== $item['type'] ) {
				$missing[] = $item['title'];
			}
		}

		$message = $missing
			? sprintf(
				'این آیتم‌ها هنوز مقصد ندارند و در سایت فقط یک ردیف بدون پیوند می‌شوند: %s — فیلد «مقدار» هرکدام را با شماره، نام کاربری یا نشانی پر کنید.',
				implode( '، ', $missing )
			)
			: 'خود دکمه در گوشهٔ همین پیش‌نمایش شناور است. این کادر فقط در ویرایشگر دیده می‌شود تا ویجت قابل انتخاب و جابه‌جایی باشد.';
		?>
		<div class="ard-sp-notice<?php echo $missing ? ' ard-sp-notice--warn' : ''; ?>">
			<span class="ard-sp-notice__icon" aria-hidden="true"><?php $this->icon( $missing ? 'question' : 'headset' ); ?></span>
			<div>
				<strong>دکمه شناور پشتیبانی</strong>
				<p><?php echo esc_html( $message ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * آیتم‌های پاک‌شده.
	 *
	 * آیتمِ بدون مقصد حذف نمی‌شود، فقط «ایستا» می‌شود: یک ردیف بدون پیوند.
	 * اگر حذفش می‌کردیم و همهٔ آیتم‌ها مقدار نداشتند، کاربر در ویرایشگر کادر
	 * راهنما می‌دید و در سایت هیچ — بی‌آنکه علتش پیدا باشد.
	 *
	 * @param array $s
	 * @return array
	 */
	private function items( $s ) {
		$out = array();

		foreach ( (array) $s['items'] as $row ) {
			$title = isset( $row['title'] ) ? trim( (string) $row['title'] ) : '';

			if ( '' === $title ) {
				continue;
			}

			$type  = isset( $row['type'] ) ? (string) $row['type'] : 'url';
			$href  = 'none' === $type ? '' : self::href( $type, isset( $row['value'] ) ? $row['value'] : '' );
			$icon  = ! empty( $row['icon'] ) ? (string) $row['icon'] : 'chat';
			$color = isset( $row['color'] ) && '' !== trim( (string) $row['color'] ) ? $row['color'] : self::icon_color( $icon );

			$external = (bool) preg_match( '~^https?://~i', $href );
			$target   = isset( $row['blank'] ) ? (string) $row['blank'] : 'auto';

			$out[] = array(
				'title'    => $title,
				'subtitle' => isset( $row['subtitle'] ) ? trim( (string) $row['subtitle'] ) : '',
				'href'     => $href,
				'static'   => '' === $href,
				'type'     => $type,
				'icon'     => $icon,
				'color'    => $color,
				'badge'    => isset( $row['badge'] ) ? trim( (string) $row['badge'] ) : '',
				'blank'    => 'blank' === $target || ( 'auto' === $target && $external ),
			);
		}

		return $out;
	}

	/**
	 * ساخت نشانی از روی نوع و مقدار.
	 *
	 * @param string $type
	 * @param string $value
	 * @return string
	 */
	public static function href( $type, $value ) {
		$value = trim( (string) $value );

		if ( '' === $value ) {
			return '';
		}

		// اگر خودِ مقدار یک نشانی کامل است، دست‌نخورده برمی‌گردد.
		if ( preg_match( '~^(https?:|tel:|sms:|mailto:|#|/)~i', $value ) ) {
			return $value;
		}

		// شماره‌ها ممکن است با ارقام فارسی نوشته شده باشند.
		$digits = self::latin_digits( $value );

		switch ( $type ) {
			case 'whatsapp':
				$num = preg_replace( '/[^0-9]/', '', $digits );
				// شمارهٔ ایرانیِ با صفر، برای واتساپ باید با کد کشور برود.
				$num = preg_replace( '/^0/', '98', (string) $num );

				return $num ? 'https://wa.me/' . $num : '';

			case 'telegram':
				return 'https://t.me/' . ltrim( $value, '@' );

			case 'instagram':
				return 'https://instagram.com/' . ltrim( $value, '@' );

			case 'phone':
				$num = preg_replace( '/[^0-9+]/', '', $digits );

				return $num ? 'tel:' . $num : '';

			case 'sms':
				$num = preg_replace( '/[^0-9+]/', '', $digits );

				return $num ? 'sms:' . $num : '';

			case 'email':
				return is_email( $value ) ? 'mailto:' . $value : '';
		}

		return $value;
	}

	/**
	 * ارقام فارسی و عربی به لاتین.
	 *
	 * @param string $text
	 * @return string
	 */
	private static function latin_digits( $text ) {
		$fa = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
		$ar = array( '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' );
		$en = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );

		return str_replace( array_merge( $fa, $ar ), array_merge( $en, $en ), (string) $text );
	}

	/**
	 * رنگ پیش‌فرض هر آیکون — رنگ شناخته‌شدهٔ خود سرویس.
	 *
	 * @param string $slug
	 * @return string
	 */
	private static function icon_color( $slug ) {
		$map = array(
			'whatsapp'  => '#25d366',
			'telegram'  => '#29a9eb',
			'instagram' => '#e1306c',
			'phone'     => '#0f766e',
			'email'     => '#7c5cbf',
			'sms'       => '#f0932b',
			'ticket'    => '#2d98da',
			'question'  => '#7d7277',
			'location'  => '#eb3b5a',
			'clock'     => '#20bf6b',
			'cart'      => '#d4af37',
		);

		return isset( $map[ $slug ] ) ? $map[ $slug ] : '#0f766e';
	}

	/**
	 * آیکون‌های موجود، برای سلکت.
	 *
	 * @return array
	 */
	private static function icon_options() {
		return array(
			'headset'   => 'هدست پشتیبانی',
			'chat'      => 'حباب گفت‌وگو',
			'support'   => 'پشتیبان',
			'whatsapp'  => 'واتساپ',
			'telegram'  => 'تلگرام',
			'instagram' => 'اینستاگرام',
			'phone'     => 'تماس تلفنی',
			'email'     => 'ایمیل',
			'sms'       => 'پیامک',
			'ticket'    => 'تیکت / فرم',
			'question'  => 'پرسش‌های متداول',
			'location'  => 'آدرس و نقشه',
			'clock'     => 'ساعت کاری',
			'cart'      => 'پیگیری سفارش',
			'user'      => 'مشاوره خرید',
		);
	}

	/**
	 * چاپ آیکون.
	 *
	 * همه با currentColor رنگ می‌گیرند تا رنگ‌دهی از CSS بیاید. نشان‌های
	 * برندها پرشده‌اند و بقیه خطی، چون شکل شناخته‌شدهٔ خودشان همین است.
	 *
	 * @param string $slug
	 */
	private function icon( $slug ) {
		$stroke = '<svg class="ard-sp-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%s</svg>';
		$fill   = '<svg class="ard-sp-svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">%s</svg>';

		$filled = array(
			'whatsapp' => '<path d="M12 2.2a9.75 9.75 0 0 0-8.35 14.8L2.2 21.8l4.95-1.4A9.75 9.75 0 1 0 12 2.2Zm0 17.7a7.9 7.9 0 0 1-4.05-1.1l-.3-.18-2.94.83.84-2.86-.2-.3A7.95 7.95 0 1 1 12 19.9Z"/><path d="M16.5 14.1c-.25-.13-1.46-.72-1.68-.8-.23-.08-.39-.12-.56.12-.16.25-.63.8-.78.97-.14.16-.28.18-.53.06-.25-.13-1.05-.39-2-1.23-.74-.66-1.24-1.47-1.38-1.71-.15-.25-.02-.38.11-.5.11-.11.25-.29.37-.44.12-.15.16-.25.25-.42.08-.16.04-.31-.02-.44-.06-.12-.56-1.34-.77-1.84-.2-.48-.4-.41-.56-.42h-.47c-.16 0-.43.06-.65.3-.23.25-.86.84-.86 2.04s.88 2.37 1 2.53c.13.17 1.74 2.65 4.2 3.72.59.25 1.05.4 1.4.52.6.18 1.13.16 1.56.1.48-.07 1.46-.6 1.67-1.17.2-.58.2-1.07.14-1.18-.06-.1-.22-.16-.47-.29Z"/>',
			'telegram' => '<path d="M21.7 4.35 2.95 11.6c-1.13.44-1.12 1.07-.2 1.35l4.62 1.44 1.78 5.46c.22.6.4.83.83.83.33 0 .48-.15.66-.33l2.22-2.16 4.62 3.41c.85.47 1.46.23 1.67-.79l3.02-14.23c.31-1.25-.48-1.81-1.47-1.23ZM8.9 14.02l10.02-6.32c.5-.3.96-.14.58.19l-8.58 7.74-.33 3.56-1.69-5.17Z"/>',
		);

		$lined = array(
			'chat'      => '<path d="M20.5 11.8c0 4.1-3.8 7.4-8.5 7.4-1 0-2-.15-2.9-.43L4 21l1.4-3.8C4.2 15.8 3.5 13.9 3.5 11.8c0-4.1 3.8-7.4 8.5-7.4s8.5 3.3 8.5 7.4Z"/><path d="M8.6 11.8h.01M12 11.8h.01M15.4 11.8h.01"/>',
			'support'   => '<path d="M4.5 13.5v-1.6a7.5 7.5 0 0 1 15 0v1.6"/><rect x="2.6" y="12.4" width="3.6" height="5.4" rx="1.6"/><rect x="17.8" y="12.4" width="3.6" height="5.4" rx="1.6"/><path d="M19.6 17.8v.9a3 3 0 0 1-3 3H13"/>',
			'instagram' => '<rect x="3.2" y="3.2" width="17.6" height="17.6" rx="5.2"/><circle cx="12" cy="12" r="3.9"/><path d="M17.1 6.9h.01"/>',
			'phone'     => '<path d="M21 16.6v2.6a1.8 1.8 0 0 1-2 1.8 17.6 17.6 0 0 1-7.7-2.7 17.3 17.3 0 0 1-5.3-5.3A17.6 17.6 0 0 1 3.3 5.2 1.8 1.8 0 0 1 5.1 3.2h2.6a1.8 1.8 0 0 1 1.8 1.6c.1.9.3 1.7.6 2.5a1.8 1.8 0 0 1-.4 1.9l-1.1 1.1a14 14 0 0 0 5.3 5.3l1.1-1.1a1.8 1.8 0 0 1 1.9-.4c.8.3 1.6.5 2.5.6a1.8 1.8 0 0 1 1.6 1.9Z"/>',
			'email'     => '<rect x="2.8" y="4.6" width="18.4" height="14.8" rx="2.6"/><path d="m3.6 7 7.2 5a2 2 0 0 0 2.4 0l7.2-5"/>',
			'sms'       => '<path d="M21 12.2a7.7 7.7 0 0 1-8.3 7.6L8 21.6l.7-2.6A7.6 7.6 0 0 1 3 12.2C3 8 7 4.6 12 4.6s9 3.4 9 7.6Z"/><path d="M8.8 12.2h.01M12 12.2h.01M15.2 12.2h.01"/>',
			'ticket'    => '<path d="M3 9.4V7.2a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2.2a2.6 2.6 0 0 0 0 5.2v2.2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2.2a2.6 2.6 0 0 0 0-5.2Z"/><path d="M14.5 5.2v2.1M14.5 10.9v2.2M14.5 16.7v2.1"/>',
			'question'  => '<circle cx="12" cy="12" r="9.2"/><path d="M9.6 9.4a2.5 2.5 0 0 1 4.9.6c0 1.7-2.5 2.5-2.5 2.5"/><path d="M12 16.6h.01"/>',
			'location'  => '<path d="M20 10.4c0 5.4-6.2 10.3-7.6 11.3a.7.7 0 0 1-.8 0C10.2 20.7 4 15.8 4 10.4a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10.2" r="2.9"/>',
			'clock'     => '<circle cx="12" cy="12" r="9.2"/><path d="M12 6.8V12l3.4 2"/>',
			'cart'      => '<path d="M2.8 3.6h2.1a1.6 1.6 0 0 1 1.55 1.25L7 7l1.55 7.1a2 2 0 0 0 1.95 1.6h6.7a2 2 0 0 0 1.95-1.5L20.6 7Z"/><circle cx="10.4" cy="19.6" r="1.4"/><circle cx="17.6" cy="19.6" r="1.4"/>',
			'user'      => '<circle cx="12" cy="8.1" r="3.9"/><path d="M4.6 20.4a7.4 7.4 0 0 1 14.8 0"/>',
			'close'     => '<path d="M6.4 6.4 17.6 17.6M17.6 6.4 6.4 17.6"/>',
			// جهت فلش را CSS با چرخش تنظیم می‌کند، پس یک شکل کافی است.
			'chevron'   => '<path d="M14.4 6.6 8.8 12l5.6 5.4"/>',
			'headset'   => '<path d="M4.4 14.4v-2.2a7.6 7.6 0 0 1 15.2 0v2.2"/><rect x="2.6" y="12.6" width="3.9" height="6.2" rx="1.7"/><rect x="17.5" y="12.6" width="3.9" height="6.2" rx="1.7"/><path d="M19.4 18.8v.6a2.8 2.8 0 0 1-2.8 2.8h-2.2"/>',
		);

		if ( isset( $filled[ $slug ] ) ) {
			printf( $fill, $filled[ $slug ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خروجی ثابت و امن.

			return;
		}

		printf( $stroke, isset( $lined[ $slug ] ) ? $lined[ $slug ] : $lined['headset'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- خروجی ثابت و امن.
	}
}
