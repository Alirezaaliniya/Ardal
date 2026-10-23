<?php
/**
 * ویجت فرم ثبت نظر و امتیازدهی با طرح کارتی.
 *
 * فرم مستقل — جدا از ویجت فهرست نظرات طرح کارتی. برای کارکرد دکمهٔ پاسخ،
 * ویجت فهرست نظرات طرح کارتی را هم در همان صفحه قرار دهید.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Product_Reviews_Form_Card extends Ardal_Widget_Base {

	/** مسیر ستاره در کادر ۲۴×۲۴. */
	const STAR = 'M12 2.8l2.75 5.6 6.15.9-4.45 4.33 1.05 6.12L12 16.86 6.5 19.75l1.05-6.12L3.1 9.3l6.15-.9z';

	public function get_name() {
		return 'ardal-product-reviews-form-card';
	}

	public function get_title() {
		return 'اردال — فرم ثبت نظر و امتیازدهی (طرح کارتی)';
	}

	public function get_icon() {
		return 'eicon-edit';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT, Ardal_Elementor::CATEGORY );
	}

	public function get_keywords() {
		return array( 'اردال', 'نظرات', 'فرم', 'امتیاز', 'ستاره', 'review', 'form', 'rating' );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-product-reviews' );
	}

	public function get_script_depends() {
		return array( 'ardal', 'ardal-post-comments' );
	}

	protected function register_controls() {
		$this->register_form_controls();
		$this->register_rating_controls();
		$this->register_style_controls();
	}

	private function register_form_controls() {
		$this->start_controls_section(
			'section_form',
			array( 'label' => 'فرم ثبت نظر' )
		);

		$this->add_control(
			'show_header',
			array(
				'label'        => 'نمایش ناحیه عنوان',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'کل ناحیه عنوان (آیکون، متن و خطوط) را پنهان می‌کند، نه فقط متن را.',
			)
		);

		$this->add_control(
			'form_heading',
			array(
				'label'       => 'عنوان فرم',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'ثبت نظرات',
				'label_block' => true,
				'condition'   => array( 'show_header' => 'yes' ),
			)
		);

		$this->add_tag_control(
			'title_tag',
			'تگ عنوان',
			'h3',
			array( 'condition' => array( 'show_header' => 'yes' ) )
		);

		$this->add_control(
			'allow_guest',
			array(
				'label'       => 'ارسال برای مهمان',
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => 'اگر وردپرس ثبت‌نام را برای دیدگاه الزامی کرده باشد، این گزینه بی‌اثر است.',
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'name_label',
			array(
				'label'     => 'برچسب نام',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'نام خانوادگی',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_phone',
			array(
				'label'   => 'فیلد شماره تماس',
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'phone_label',
			array(
				'label'     => 'برچسب شماره تماس',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'شماره تماس',
				'condition' => array( 'show_phone' => 'yes' ),
			)
		);

		$this->add_control(
			'show_email',
			array(
				'label'       => 'فیلد ایمیل',
				'type'        => Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => 'اگر در «تنظیمات › گفت‌وگو» نام و ایمیل الزامی باشد، این فیلد خودکار نمایش داده می‌شود.',
			)
		);

		$this->add_control(
			'message_label',
			array(
				'label'     => 'برچسب پیام',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'پیام',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'message_placeholder',
			array(
				'label'   => 'متن راهنمای پیام',
				'type'    => Controls_Manager::TEXT,
				'default' => 'پیام خود را بنویسید...',
			)
		);

		$this->add_control(
			'submit_text',
			array(
				'label'     => 'متن دکمه',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'ارسال',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'closed_text',
			array(
				'label'       => 'متن بسته بودن نظرات',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'ثبت نظر برای این مورد بسته است.',
				'label_block' => true,
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'login_text',
			array(
				'label'       => 'متن نیاز به ورود',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'برای ثبت نظر وارد حساب کاربری خود شوید.',
				'label_block' => true,
			)
		);

		$this->end_controls_section();
	}

	private function register_rating_controls() {
		$this->start_controls_section(
			'section_rating',
			array( 'label' => 'امتیاز ستاره‌ای' )
		);

		$this->add_control(
			'enable_rating',
			array(
				'label'       => 'امتیازدهی ستاره‌ای',
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'description' => 'انتخاب ستاره در فرم. اگر در تنظیمات ووکامرس «امتیاز اجباری» روشن باشد، انتخاب ستاره همیشه نمایش داده می‌شود.',
			)
		);

		$this->add_control(
			'rating_label',
			array(
				'label'     => 'برچسب امتیاز در فرم',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'امتیاز شما',
				'condition' => array( 'enable_rating' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	private function register_style_controls() {
		$this->start_controls_section(
			'style_head',
			array(
				'label'     => 'سربرگ',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_header' => 'yes' ),
			)
		);

		$this->add_control(
			'head_line',
			array(
				'label'     => 'رنگ خط سربرگ',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-line:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'head_title_color',
			array(
				'label'     => 'رنگ عنوان',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-head-title:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'head_title_typo',
				'selector' => '{{WRAPPER}} .ard-rv__title',
			)
		);

		$this->add_control(
			'badge_bg',
			array(
				'label'     => 'زمینهٔ آیکون',
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-badge-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'badge_color',
			array(
				'label'     => 'رنگ آیکون',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-badge-color:{{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_form',
			array(
				'label' => 'فرم',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'form_bg',
			array(
				'label'     => 'زمینهٔ فرم',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-form-bg:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'form_padding',
			array(
				'label'      => 'فاصلهٔ داخلی فرم',
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array( '{{WRAPPER}} .ard-rv__form-wrap' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => 'رنگ برچسب‌ها',
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-label:{{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typo',
				'label'    => 'تایپوگرافی برچسب',
				'selector' => '{{WRAPPER}} .ard-rv__field > span, {{WRAPPER}} .ard-rv__rate legend',
			)
		);

		$this->add_control(
			'input_bg',
			array(
				'label'     => 'زمینهٔ فیلدها',
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-input-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'input_color',
			array(
				'label'     => 'رنگ متن فیلدها',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-input:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'button_bg',
			array(
				'label'     => 'زمینهٔ دکمه',
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-button-bg:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => 'رنگ متن دکمه',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-button:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'button_width',
			array(
				'label'      => 'عرض دکمه',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array( 'min' => 100, 'max' => 400 ),
					'%'  => array( 'min' => 20, 'max' => 100 ),
				),
				'selectors'  => array( '{{WRAPPER}} .ard-rv__submit' => 'width:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_stars',
			array(
				'label'     => 'ستاره‌ها',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'enable_rating' => 'yes' ),
			)
		);

		$this->add_control(
			'like_color',
			array(
				'label'       => 'رنگ اصلی',
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array( '{{WRAPPER}} .ard-rv' => '--rv-accent:{{VALUE}};' ),
				'description' => 'رنگ دکمهٔ ارسال و ستاره‌ها.',
			)
		);

		$this->add_control(
			'star_color',
			array(
				'label'     => 'رنگ ستارهٔ پر',
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-star:{{VALUE}};' ),
			)
		);

		$this->add_control(
			'star_empty',
			array(
				'label'     => 'رنگ ستارهٔ خالی',
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .ard-rv' => '--rv-star-empty:{{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'star_input_size',
			array(
				'label'      => 'اندازهٔ ستاره در فرم',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 16, 'max' => 48 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-rv' => '--rv-star-input:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$post_id = $this->post_id();

		if ( ! $post_id ) {
			$this->render_empty( 'این ویجت را در قالب نوشته یا محصول تکی قرار دهید.' );
			return;
		}

		$is_product = 'product' === get_post_type( $post_id );

		$rating_supported = ! $is_product || ( function_exists( 'wc_review_ratings_enabled' ) && wc_review_ratings_enabled() );
		$rating_required   = $is_product && $rating_supported && function_exists( 'wc_review_ratings_required' ) && wc_review_ratings_required();
		$rating_on         = $rating_supported && 'yes' === $s['enable_rating'];
		$show_rating       = $rating_supported && ( $rating_on || $rating_required );

		$classes = array( 'ard-rv', 'ard-rv--form-only' );
		if ( 'yes' !== $s['show_header'] ) {
			$classes[] = 'ard-rv--no-form-head';
		}

		$uid = 'ard-rv-form-' . $this->get_id();
		$tag = self::tag( $s['title_tag'], 'h3' );
		?>
		<section id="<?php echo esc_attr( $uid ); ?>" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-ard-comments data-ard-comments-role="form" data-ajax="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( Ardal_Comments::NONCE_ACTION ) ); ?>" data-post-id="<?php echo esc_attr( $post_id ); ?>">
			<?php if ( 'yes' === $s['show_header'] ) : ?>
				<div class="ard-rv__form-head">
					<span class="ard-rv__badge" aria-hidden="true"><?php echo self::chat_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<<?php echo esc_html( $tag ); ?> class="ard-rv__title"><?php echo esc_html( $s['form_heading'] ); ?></<?php echo esc_html( $tag ); ?>>
				</div>
			<?php endif; ?>

			<?php $this->render_form_body( $s, $post_id, $is_product, $show_rating, $rating_required ); ?>
		</section>
		<?php
	}

	/**
	 * @param array $s
	 * @param int   $post_id
	 * @param bool  $is_product
	 * @param bool  $show_rating
	 * @param bool  $rating_required
	 */
	private function render_form_body( $s, $post_id, $is_product, $show_rating, $rating_required ) {
		$verified_only = $is_product && 'yes' === get_option( 'woocommerce_review_rating_verification_required' );
		$guest_allowed  = 'yes' === $s['allow_guest'] && ! get_option( 'comment_registration' ) && ! $verified_only;
		$logged_in      = is_user_logged_in();
		$can_review     = ! $verified_only || ( $logged_in && function_exists( 'wc_customer_bought_product' ) && wc_customer_bought_product( wp_get_current_user()->user_email, get_current_user_id(), $post_id ) );
		$email_needed    = (bool) get_option( 'require_name_email' );

		if ( ! comments_open( $post_id ) ) {
			$notice = $s['closed_text'];
		} elseif ( $logged_in ? ! $can_review : ! $guest_allowed ) {
			$notice = $verified_only ? 'برای ثبت نظر باید با حساب خریدارِ تأییدشدهٔ این محصول وارد شوید.' : $s['login_text'];
		} else {
			$notice = '';
		}
		?>
		<div class="ard-rv__form-wrap">
			<?php if ( '' !== $notice ) : ?>
				<p class="ard-rv__notice">
					<?php echo esc_html( $notice ); ?>
					<?php if ( ! $logged_in && comments_open( $post_id ) ) : ?>
						<a href="<?php echo esc_url( wp_login_url( get_permalink( $post_id ) ) ); ?>">ورود</a>
					<?php endif; ?>
				</p>
			<?php else : ?>
				<div class="ard-rv__replying" data-replying hidden><span></span><button type="button" data-cancel-reply>لغو پاسخ</button></div>

				<form class="ard-rv__form" data-comment-form>
					<?php if ( $logged_in ) : ?>
						<p class="ard-rv__as">ارسال با نام <strong><?php echo esc_html( wp_get_current_user()->display_name ); ?></strong></p>
					<?php endif; ?>

					<div class="ard-rv__fields">
						<?php if ( ! $logged_in ) : ?>
							<label class="ard-rv__field">
								<span><?php echo esc_html( $s['name_label'] ); ?></span>
								<input name="first_name" type="text" autocomplete="name" maxlength="80" placeholder="<?php echo esc_attr( $s['name_label'] ); ?>" required>
							</label>
						<?php endif; ?>

						<?php if ( 'yes' === $s['show_phone'] ) : ?>
							<label class="ard-rv__field">
								<span><?php echo esc_html( $s['phone_label'] ); ?></span>
								<input name="phone" type="tel" inputmode="tel" autocomplete="tel" maxlength="20" dir="ltr" placeholder="<?php echo esc_attr( $s['phone_label'] ); ?>">
							</label>
						<?php endif; ?>

						<?php if ( ! $logged_in && ( 'yes' === $s['show_email'] || $email_needed ) ) : ?>
							<label class="ard-rv__field">
								<span>ایمیل</span>
								<input name="email" type="email" autocomplete="email" dir="ltr" placeholder="ایمیل"<?php echo $email_needed ? ' required' : ''; ?>>
							</label>
						<?php endif; ?>
					</div>

					<?php if ( $show_rating ) : ?>
						<fieldset class="ard-rv__rate">
							<legend><?php echo esc_html( $s['rating_label'] ); ?><?php echo $rating_required ? ' <span class="ard-rv__req" aria-hidden="true">*</span>' : ''; ?></legend>
							<div class="ard-rv__rate-stars">
								<?php for ( $star = 5; $star >= 1; $star-- ) : ?>
									<label>
										<input type="radio" name="rating" value="<?php echo esc_attr( $star ); ?>"<?php echo $rating_required ? ' required' : ''; ?>>
										<svg viewBox="0 0 24 24" aria-hidden="true"><path d="<?php echo esc_attr( self::STAR ); ?>"/></svg>
										<span class="screen-reader-text"><?php echo esc_html( number_format_i18n( $star ) . ' از ۵ ستاره' ); ?></span>
									</label>
								<?php endfor; ?>
							</div>
						</fieldset>
					<?php endif; ?>

					<label class="ard-rv__field ard-rv__field--message">
						<span><?php echo esc_html( $s['message_label'] ); ?></span>
						<textarea name="content" rows="4" maxlength="5000" placeholder="<?php echo esc_attr( $s['message_placeholder'] ); ?>" required></textarea>
					</label>

					<div class="ard-rv__honeypot" aria-hidden="true"><label>Website<input name="website" type="text" tabindex="-1" autocomplete="off"></label></div>

					<div class="ard-rv__footer">
						<button class="ard-rv__submit" type="submit">
							<span><?php echo esc_html( $s['submit_text'] ); ?></span>
							<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 12H5m0 0 6-6m-6 6 6 6"/></svg>
						</button>
						<span class="ard-rv__status" data-form-status role="status" aria-live="polite"></span>
					</div>
				</form>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * شناسهٔ نوشته یا محصولی که نظرهایش نمایش داده می‌شود.
	 *
	 * @return int
	 */
	private function post_id() {
		$id = is_singular() ? (int) get_queried_object_id() : (int) get_the_ID();

		if ( $id && in_array( get_post_type( $id ), array( 'post', 'product' ), true ) ) {
			return $id;
		}

		$product = Ardal_Product::is_editor() ? Ardal_Product::current() : null;

		return $product ? (int) $product->get_id() : 0;
	}

	/**
	 * @return string
	 */
	private static function chat_icon() {
		return '<svg viewBox="0 0 24 24"><path d="M12 21a9 9 0 1 0-7.9-4.7c.2.4.3.9.1 1.4L3.5 20.5l2.8-.6c.5-.1 1 0 1.4.2A9 9 0 0 0 12 21Z"/><path d="M8.5 10h7M8.5 14h4.5"/></svg>';
	}
}
