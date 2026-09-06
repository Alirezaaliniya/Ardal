<?php
/**
 * ویجت خبرنامه.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Ardal_Widget_Newsletter extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-newsletter';
	}

	public function get_title() {
		return 'اردال — خبرنامه';
	}

	public function get_icon() {
		return 'eicon-email-field';
	}

	public function get_keywords() {
		return array( 'اردال', 'خبرنامه', 'ایمیل', 'عضویت', 'newsletter' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'محتوا' )
		);

		$this->add_control(
			'title',
			array(
				'label'   => 'عنوان',
				'type'    => Controls_Manager::TEXT,
				'default' => 'از تخفیف‌ها باخبر شوید',
			)
		);

		$this->add_control(
			'subtitle',
			array(
				'label'       => 'توضیح',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'هفته‌ای یک ایمیل، فقط پیشنهادهای واقعی',
				'label_block' => true,
			)
		);

		$this->add_control(
			'placeholder',
			array(
				'label'   => 'متن راهنمای فیلد',
				'type'    => Controls_Manager::TEXT,
				'default' => 'ایمیل یا شماره موبایل',
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => 'متن دکمه',
				'type'    => Controls_Manager::TEXT,
				'default' => 'عضویت',
			)
		);

		$this->add_control(
			'field_type',
			array(
				'label'   => 'نوع فیلد',
				'type'    => Controls_Manager::SELECT,
				'default' => 'email',
				'options' => array(
					'email' => 'ایمیل',
					'tel'   => 'شماره موبایل',
					'text'  => 'متن آزاد',
				),
			)
		);

		$this->add_control(
			'field_name',
			array(
				'label'       => 'نام فیلد (name)',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'email',
				'description' => 'باید با نامی که فرم مقصد انتظار دارد یکی باشد.',
			)
		);

		$this->add_control(
			'action_url',
			array(
				'label'       => 'آدرس ارسال فرم',
				'type'        => Controls_Manager::URL,
				'default'     => array( 'url' => '' ),
				'description' => 'آدرس سرویس خبرنامه (میلرلایت، نجوا، ووکامرس و …). اگر خالی باشد فرم فقط نمایشی است.',
			)
		);

		$this->add_control(
			'note',
			array(
				'label'       => 'یادداشت زیر فرم',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
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
				'default' => 'soft',
				'options' => array(
					'soft' => 'نوار روشن با خط بالا (دیزاین ۱a)',
					'dark' => 'باکس سرمه‌ای گرد (دیزاین ۱b)',
				),
			)
		);

		$this->add_responsive_control(
			'layout',
			array(
				'label'          => 'چیدمان',
				'type'           => Controls_Manager::SELECT,
				'default'        => 'row',
				'mobile_default' => 'column',
				'options'        => array(
					'row'    => 'متن و فرم کنار هم',
					'column' => 'متن بالا، فرم پایین',
				),
				'selectors_dictionary' => array(
					'row'    => 'row;align-items:center',
					'column' => 'column;align-items:stretch',
				),
				'selectors'      => array(
					'{{WRAPPER}} .ard-news__inner' => 'flex-direction:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$action = ! empty( $s['action_url']['url'] ) ? $s['action_url']['url'] : '';
		$name   = sanitize_key( $s['field_name'] );
		$name   = '' !== $name ? $name : 'email';
		$id     = 'ard-news-' . $this->get_id();
		?>
		<section class="ard-news ard-news--<?php echo esc_attr( $s['variant'] ); ?>">
			<div class="ard-news__inner">
				<div class="ard-news__copy">
					<h2 class="ard-news__title"><?php echo esc_html( $s['title'] ); ?></h2>
					<?php if ( '' !== trim( (string) $s['subtitle'] ) ) : ?>
						<p class="ard-news__sub"><?php echo esc_html( $s['subtitle'] ); ?></p>
					<?php endif; ?>
				</div>

				<form
					class="ard-news__form"
					<?php echo '' !== $action ? ' action="' . esc_url( $action ) . '" method="post"' : ' onsubmit="return false"'; ?>
				>
					<label class="ard-sr" for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $s['placeholder'] ); ?></label>
					<input
						type="<?php echo esc_attr( $s['field_type'] ); ?>"
						id="<?php echo esc_attr( $id ); ?>"
						name="<?php echo esc_attr( $name ); ?>"
						class="ard-news__input"
						placeholder="<?php echo esc_attr( $s['placeholder'] ); ?>"
						required
					/>
					<button type="submit" class="ard-news__btn"><?php echo esc_html( $s['button_text'] ); ?></button>
				</form>
			</div>

			<?php if ( '' !== trim( (string) $s['note'] ) ) : ?>
				<p class="ard-news__note"><?php echo esc_html( $s['note'] ); ?></p>
			<?php endif; ?>
		</section>
		<?php
	}
}
