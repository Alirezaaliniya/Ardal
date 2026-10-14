<?php
/**
 * ویجت فهرست علاقه‌مندی‌ها — کارت‌های محصول کاربر، با همان کارت ویجت محصولات.
 *
 * با برداشتن قلب، کارت همان لحظه از فهرست می‌رود (ardal.js). در ویرایشگر
 * اگر فهرست مدیر خالی باشد، چند محصول نمونه نشان داده می‌شود تا ظاهر قابل
 * تنظیم باشد.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Wishlist extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-wishlist';
	}

	public function get_title() {
		return 'اردال — فهرست علاقه‌مندی‌ها';
	}

	public function get_icon() {
		return 'eicon-heart';
	}

	public function get_keywords() {
		return array( 'اردال', 'علاقه‌مندی', 'فهرست', 'wishlist', 'favorites' );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-wishlist' );
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_content', array( 'label' => 'فهرست' ) );

		$this->add_control(
			'title',
			array(
				'label'   => 'عنوان',
				'type'    => Controls_Manager::TEXT,
				'default' => 'علاقه‌مندی‌های من',
			)
		);

		$this->add_tag_control( 'title_tag', 'تگ عنوان', 'h2' );

		$this->add_control(
			'show_count',
			array(
				'label'        => 'نمایش تعداد کنار عنوان',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'limit',
			array(
				'label'       => 'حداکثر تعداد',
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'default'     => 0,
				'description' => 'صفر یعنی همه. اگر محدود باشد، دکمهٔ «مشاهدهٔ همه» به صفحهٔ علاقه‌مندی پنل لینک می‌شود.',
			)
		);

		$this->add_control(
			'more_text',
			array(
				'label'     => 'متن «مشاهدهٔ همه»',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'مشاهدهٔ همهٔ علاقه‌مندی‌ها',
				'condition' => array( 'limit!' => 0 ),
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
				'tablet_default' => 3,
				'mobile_default' => 2,
				'selectors'      => array( '{{WRAPPER}} .ard-wish-list' => '--ard-wish-cols:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => 'فاصله کارت‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 48 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-wish-list' => '--ard-wish-gap:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section( 'section_card', array( 'label' => 'کارت محصول' ) );

		$this->add_control(
			'variant',
			array(
				'label'   => 'سبک کارت',
				'type'    => Controls_Manager::SELECT,
				'default' => 'framed',
				'options' => array(
					'framed' => 'کارت حاشیه‌دار با دکمه گرد',
					'plain'  => 'بدون حاشیه با دکمه «افزودن به سبد»',
				),
			)
		);

		foreach ( array(
			'show_rating' => array( 'امتیاز', 'yes' ),
			'show_off'    => array( 'درصد تخفیف', 'yes' ),
			'show_fab'    => array( 'دکمهٔ گرد افزودن به سبد', 'yes' ),
		) as $key => $row ) {
			$this->add_control(
				$key,
				array(
					'label'        => $row[0],
					'type'         => Controls_Manager::SWITCHER,
					'default'      => $row[1],
					'return_value' => 'yes',
				)
			);
		}

		$this->add_control(
			'button_text',
			array(
				'label'     => 'متن دکمهٔ سبد',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'افزودن به سبد',
				'condition' => array( 'variant' => 'plain' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section( 'section_empty', array( 'label' => 'حالت خالی و مهمان' ) );

		$this->add_control(
			'empty_title',
			array(
				'label'   => 'عنوان حالت خالی',
				'type'    => Controls_Manager::TEXT,
				'default' => 'هنوز کالایی ذخیره نکرده‌ای',
			)
		);

		$this->add_control(
			'empty_text',
			array(
				'label'   => 'توضیح حالت خالی',
				'type'    => Controls_Manager::TEXTAREA,
				'default' => 'با زدن آیکون قلب روی هر محصول، آن را اینجا نگه دار تا بعداً سراغش بیایی.',
			)
		);

		$this->add_control(
			'empty_btn',
			array(
				'label'       => 'متن دکمه',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'دیدن محصول‌ها',
				'description' => 'خالی بگذارید تا دکمه حذف شود. لینک دکمه صفحهٔ فروشگاه است.',
			)
		);

		$this->add_control(
			'guest_text',
			array(
				'label'       => 'پیام برای مهمان',
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'description' => 'فقط وقتی تنظیمات مهمان روی «اول وارد شو» است. خالی بماند، پیام تنظیمات اردال نمایش داده می‌شود.',
				'separator'   => 'before',
			)
		);

		$this->end_controls_section();

		/* ---------------- استایل ---------------- */

		$this->start_controls_section(
			'section_style_head',
			array(
				'label' => 'عنوان',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'title_typo', 'selector' => '{{WRAPPER}} .ard-wish-list__title' ) );

		$this->add_control(
			'title_color',
			array(
				'label'     => 'رنگ عنوان',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-wish-list__title' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'count_color',
			array(
				'label'     => 'رنگ شمارنده',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-wish-list__count' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'head_gap',
			array(
				'label'      => 'فاصله تا کارت‌ها',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-wish-list__head' => 'margin-block-end:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_heart',
			array(
				'label' => 'قلب روی کارت',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'heart_size',
			array(
				'label'      => 'اندازه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 24, 'max' => 60 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-wish-list__cell > .ard-wish' => '--ard-wish-size:{{SIZE}}{{UNIT}};--ard-wish-icon:calc({{SIZE}}{{UNIT}} / 2);' ),
			)
		);

		$this->add_control(
			'heart_color',
			array(
				'label'     => 'رنگ قلب',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-wish-list__cell > .ard-wish' => '--ard-wish-on:{{VALUE}};--ard-wish-on-border:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'heart_bg',
			array(
				'label'     => 'زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-wish-list__cell > .ard-wish' => '--ard-wish-on-bg:{{VALUE}};--ard-wish-bg:{{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_empty',
			array(
				'label' => 'حالت خالی',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'empty_bg',
			array(
				'label'     => 'زمینه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-wish-list__empty' => 'background:{{VALUE}};' ),
			)
		);

		$this->add_group_control( Group_Control_Border::get_type(), array( 'name' => 'empty_border', 'selector' => '{{WRAPPER}} .ard-wish-list__empty' ) );

		$this->add_responsive_control(
			'empty_radius',
			array(
				'label'      => 'گردی گوشه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-wish-list__empty' => 'border-radius:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'empty_title_color',
			array(
				'label'     => 'رنگ عنوان',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-wish-list__empty-title' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'empty_text_color',
			array(
				'label'     => 'رنگ توضیح',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-wish-list__empty-text' => 'color:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'empty_btn_bg',
			array(
				'label'     => 'زمینه دکمه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-wish-list__btn' => 'background:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'empty_btn_color',
			array(
				'label'     => 'رنگ متن دکمه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-wish-list__btn' => 'color:{{VALUE}};' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		if ( ! Ardal_Helpers::has_woo() || ! Ardal_Wishlist::enabled() ) {
			$this->render_empty( 'علاقه‌مندی در تنظیمات اردال خاموش است.' );

			return;
		}

		$s       = $this->get_settings_for_display();
		$editor  = \Elementor\Plugin::$instance->editor->is_edit_mode();
		$logged  = is_user_logged_in();
		$limit   = max( 0, (int) $s['limit'] );
		$items   = $logged ? Ardal_Wishlist::products() : array();
		$total   = count( $items );
		$preview = false;

		if ( ! $items && $editor ) {
			$items   = wc_get_products( array( 'limit' => 4, 'status' => 'publish' ) );
			$preview = true;
		}

		if ( $limit > 0 ) {
			$items = array_slice( $items, 0, $limit );
		}

		// مهمان در حالت «ذخیره در مرورگر» فهرستی روی سرور ندارد؛ پیام ورود.
		$guest = ! $logged && ! $editor;
		$tag   = self::tag( $s['title_tag'], 'h2' );
		?>
		<section class="ard-wish-list" data-ard-wishlist-list>
			<?php if ( '' !== trim( (string) $s['title'] ) ) : ?>
				<div class="ard-wish-list__head">
					<<?php echo esc_html( $tag ); ?> class="ard-wish-list__title"><?php echo esc_html( $s['title'] ); ?></<?php echo esc_html( $tag ); ?>>
					<?php if ( 'yes' === $s['show_count'] && $logged ) : ?>
						<span class="ard-wish-list__count"><span data-ard-wishlist-total><?php echo esc_html( Ardal_Helpers::fa_digits( $total ) ); ?></span> کالا</span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $preview ) : ?>
				<p class="ard-empty">پیش‌نمایش ویرایشگر: فهرست علاقه‌مندی شما خالی است و چند محصول نمونه نمایش داده می‌شود.</p>
			<?php endif; ?>

			<?php if ( $guest ) : ?>
				<?php $this->render_state( $s, true ); ?>
			<?php else : ?>
				<div class="ard-wish-list__grid" data-ard-wishlist-grid<?php echo $items ? '' : ' hidden'; ?>>
					<?php foreach ( $items as $product ) : ?>
						<div class="ard-wish-list__cell" data-ard-wishlist-item="<?php echo esc_attr( $product->get_id() ); ?>">
							<?php
							echo Ardal_Wishlist::button( array( 'product' => $product->get_id(), 'size' => 17 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

							$this->render_product_card(
								Ardal_Helpers::product_to_card( $product ),
								array(
									'variant'     => 'plain' === $s['variant'] ? 'plain' : 'framed',
									'show_rating' => 'yes' === $s['show_rating'],
									'show_off'    => 'yes' === $s['show_off'],
									'show_fab'    => 'yes' === $s['show_fab'] && 'framed' === $s['variant'],
									'show_button' => 'plain' === $s['variant'],
									'button_text' => '' !== trim( (string) $s['button_text'] ) ? $s['button_text'] : 'افزودن به سبد',
								)
							);
							?>
						</div>
					<?php endforeach; ?>
				</div>

				<?php $this->render_state( $s, false, ! empty( $items ) ); ?>

				<?php if ( $limit > 0 && $total > $limit && '' !== trim( (string) $s['more_text'] ) ) : ?>
					<div class="ard-wish-list__more">
						<a class="ard-wish-list__btn" href="<?php echo esc_url( Ardal_Wishlist::page_url() ); ?>"><?php echo esc_html( $s['more_text'] ); ?></a>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</section>
		<?php
	}

	/**
	 * کادر خالی یا پیام ورود.
	 *
	 * @param array $s
	 * @param bool  $guest
	 * @param bool  $hidden
	 */
	private function render_state( $s, $guest, $hidden = false ) {
		$title = $guest ? '' : $s['empty_title'];
		$text  = $guest
			? ( '' !== trim( (string) $s['guest_text'] ) ? $s['guest_text'] : Ardal_Wishlist::option( 'msg_login' ) )
			: $s['empty_text'];
		$btn   = $guest ? Ardal_Wishlist::option( 'login_text' ) : $s['empty_btn'];
		$url   = $guest ? Ardal_Wishlist::login_url() : wc_get_page_permalink( 'shop' );
		?>
		<div class="ard-wish-list__empty" data-ard-wishlist-empty<?php echo $hidden ? ' hidden' : ''; ?>>
			<span class="ard-wish-list__empty-icon"><?php echo Ardal_Wishlist::heart( 26 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<?php if ( '' !== trim( (string) $title ) ) : ?>
				<p class="ard-wish-list__empty-title"><?php echo esc_html( $title ); ?></p>
			<?php endif; ?>
			<?php if ( '' !== trim( (string) $text ) ) : ?>
				<p class="ard-wish-list__empty-text"><?php echo esc_html( $text ); ?></p>
			<?php endif; ?>
			<?php if ( '' !== trim( (string) $btn ) ) : ?>
				<a class="ard-wish-list__btn" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $btn ); ?></a>
			<?php endif; ?>
		</div>
		<?php
	}
}
