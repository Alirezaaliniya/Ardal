<?php
/** Dynamic WordPress comments for single post templates. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

class Ardal_Widget_Post_Comments extends Ardal_Widget_Base {
	public function get_name() { return 'ardal-post-comments'; }
	public function get_title() { return 'اردال — نظرات نوشته/محصول'; }
	public function get_icon() { return 'eicon-comments'; }
	public function get_keywords() { return array( 'اردال', 'نظرات', 'دیدگاه', 'کامنت', 'comments' ); }
	public function get_script_depends() { return array( 'ardal', 'ardal-post-comments' ); }

	protected function register_controls() {
		$this->start_controls_section( 'content', array( 'label' => 'محتوا و عملکرد' ) );
		$this->add_control( 'heading', array( 'label' => 'عنوان بخش', 'type' => Controls_Manager::TEXT, 'default' => 'نظرات', 'label_block' => true ) );
		$this->add_control( 'show_count', array( 'label' => 'نمایش تعداد دیدگاه‌ها', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ) );
		$this->add_control( 'limit', array( 'label' => 'حداکثر دیدگاه اصلی', 'type' => Controls_Manager::NUMBER, 'default' => 10, 'min' => 1, 'max' => 100 ) );
		$this->add_control( 'order', array( 'label' => 'ترتیب دیدگاه‌ها', 'type' => Controls_Manager::SELECT, 'default' => 'ASC', 'options' => array( 'ASC' => 'قدیمی‌تر اول', 'DESC' => 'جدیدتر اول' ) ) );
		$this->add_control( 'reply_depth', array( 'label' => 'حداکثر سطح پاسخ', 'type' => Controls_Manager::NUMBER, 'default' => 3, 'min' => 0, 'max' => 8, 'description' => 'صفر یعنی فقط دیدگاه‌های اصلی نمایش داده شوند.' ) );
		$this->add_control( 'show_avatar', array( 'label' => 'نمایش تصویر کاربر', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ) );
		$this->add_control( 'avatar_size', array( 'label' => 'اندازه تصویر کاربر (px)', 'type' => Controls_Manager::NUMBER, 'default' => 64, 'min' => 24, 'max' => 160, 'condition' => array( 'show_avatar' => 'yes' ) ) );
		$this->add_control( 'show_date', array( 'label' => 'نمایش تاریخ', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ) );
		$this->add_control( 'date_format', array( 'label' => 'قالب تاریخ', 'type' => Controls_Manager::SELECT, 'default' => 'relative', 'options' => array( 'relative' => 'زمان گذشته (۳ ساعت پیش)', 'date' => 'تاریخ شمسی' ), 'condition' => array( 'show_date' => 'yes' ) ) );
		$this->add_control( 'enable_reply', array( 'label' => 'فعال‌کردن پاسخ به دیدگاه', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ) );
		$this->add_control( 'enable_reactions', array( 'label' => 'فعال‌کردن پسند/نپسند', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ) );
		$this->add_control( 'enable_share', array( 'label' => 'فعال‌کردن اشتراک‌گذاری', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ) );
		$this->add_control( 'allow_guest', array( 'label' => 'اجازه ارسال برای مهمان', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ) );
		$this->add_control( 'show_phone', array( 'label' => 'نمایش فیلد شماره تماس', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes', 'condition' => array( 'allow_guest' => 'yes' ) ) );
		$this->add_control( 'show_email', array( 'label' => 'نمایش ایمیل مهمان', 'type' => Controls_Manager::SWITCHER, 'default' => '', 'return_value' => 'yes', 'condition' => array( 'allow_guest' => 'yes' ) ) );
		$this->add_control( 'require_email', array( 'type' => Controls_Manager::HIDDEN, 'default' => get_option( 'require_name_email' ) ? 'yes' : '' ) );
		$this->add_control( 'form_title', array( 'label' => 'عنوان فرم', 'type' => Controls_Manager::TEXT, 'default' => 'ثبت نظر' ) );
		$this->add_control( 'submit_text', array( 'label' => 'متن دکمه ارسال', 'type' => Controls_Manager::TEXT, 'default' => 'ارسال' ) );
		$this->add_control( 'empty_text', array( 'label' => 'متن حالت بدون دیدگاه', 'type' => Controls_Manager::TEXT, 'default' => 'هنوز دیدگاهی ثبت نشده است.' ) );
		$this->add_control( 'closed_text', array( 'label' => 'متن بسته بودن دیدگاه‌ها', 'type' => Controls_Manager::TEXT, 'default' => 'ثبت دیدگاه برای این نوشته بسته است.' ) );
		$this->add_control( 'show_verified', array( 'label' => 'نشان خریدار تأییدشده در محصولات', 'type' => Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ) );
		$this->add_control( 'title_tag', array( 'label' => 'تگ عنوان', 'type' => Controls_Manager::SELECT, 'default' => 'h3', 'options' => array( 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'div' => 'DIV' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'visual_assets', array( 'label' => 'پس‌زمینه و ماسک خمیده' ) );
		$this->add_control( 'background_image', array( 'label' => 'تصویر پس‌زمینه کلی', 'type' => Controls_Manager::MEDIA, 'default' => array() ) );
		$this->add_control( 'comment_background', array( 'label' => 'پس‌زمینه کارت دیدگاه', 'type' => Controls_Manager::MEDIA, 'default' => array() ) );
		$this->add_control( 'comment_mask', array( 'label' => 'ماسک کارت دیدگاه (PNG/SVG)', 'type' => Controls_Manager::MEDIA, 'default' => array() ) );
		$this->add_control( 'author_background', array( 'label' => 'پس‌زمینه ناحیه نام کاربر', 'type' => Controls_Manager::MEDIA, 'default' => array() ) );
		$this->add_control( 'author_mask', array( 'label' => 'ماسک ناحیه نام کاربر (PNG/SVG)', 'type' => Controls_Manager::MEDIA, 'default' => array() ) );
		$this->add_control( 'form_background', array( 'label' => 'پس‌زمینه فرم', 'type' => Controls_Manager::MEDIA, 'default' => array() ) );
		$this->add_control( 'form_mask', array( 'label' => 'ماسک فرم (PNG/SVG)', 'type' => Controls_Manager::MEDIA, 'default' => array() ) );
		$this->add_control( 'mask_help', array( 'type' => Controls_Manager::RAW_HTML, 'raw' => 'برای ماسک بهتر است PNG شفاف یا SVG با سطح برش‌خورده انتخاب کنید. تصویر با اندازه ناحیه تطبیق داده می‌شود.', 'content_classes' => 'elementor-panel-alert elementor-panel-alert-info' ) );
		$this->end_controls_section();

		$this->start_controls_section( 'style_box', array( 'label' => 'بخش و عنوان', 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'box_bg', array( 'label' => 'رنگ زمینه', 'type' => Controls_Manager::COLOR, 'default' => '#0b0b0d', 'selectors' => array( '{{WRAPPER}} .ard-comments' => 'background-color:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Border::get_type(), array( 'name' => 'box_border', 'selector' => '{{WRAPPER}} .ard-comments' ) );
		$this->add_responsive_control( 'box_radius', array( 'label' => 'گردی بخش', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', '%' ), 'default' => array( 'top' => 26, 'right' => 26, 'bottom' => 26, 'left' => 26, 'unit' => 'px', 'isLinked' => true ), 'selectors' => array( '{{WRAPPER}} .ard-comments' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), array( 'name' => 'box_shadow', 'selector' => '{{WRAPPER}} .ard-comments' ) );
		$this->add_responsive_control( 'box_padding', array( 'label' => 'فاصله داخلی', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', 'em' ), 'default' => array( 'top' => 24, 'right' => 24, 'bottom' => 24, 'left' => 24, 'unit' => 'px', 'isLinked' => false ), 'selectors' => array( '{{WRAPPER}} .ard-comments' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->add_control( 'heading_color', array( 'label' => 'رنگ عنوان', 'type' => Controls_Manager::COLOR, 'default' => '#f5f5f8', 'selectors' => array( '{{WRAPPER}} .ard-comments__heading' => 'color:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'heading_typography', 'selector' => '{{WRAPPER}} .ard-comments__heading' ) );
		$this->end_controls_section();

		$this->start_controls_section( 'style_cards', array( 'label' => 'دیدگاه‌ها و اطلاعات کاربر', 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'card_bg', array( 'label' => 'رنگ کارت', 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => array( '{{WRAPPER}} .ard-comments__card' => 'background-color:{{VALUE}};' ) ) );
		$this->add_control( 'author_color', array( 'label' => 'رنگ نام', 'type' => Controls_Manager::COLOR, 'default' => '#24242a', 'selectors' => array( '{{WRAPPER}} .ard-comments__author' => 'color:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'author_typography', 'selector' => '{{WRAPPER}} .ard-comments__author' ) );
		$this->add_control( 'text_color', array( 'label' => 'رنگ متن دیدگاه', 'type' => Controls_Manager::COLOR, 'default' => '#77777e', 'selectors' => array( '{{WRAPPER}} .ard-comments__text' => 'color:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'text_typography', 'selector' => '{{WRAPPER}} .ard-comments__text' ) );
		$this->add_control( 'meta_color', array( 'label' => 'رنگ تاریخ و پاسخ', 'type' => Controls_Manager::COLOR, 'default' => '#77777e', 'selectors' => array( '{{WRAPPER}} .ard-comments__meta, {{WRAPPER}} .ard-comments__reply' => 'color:{{VALUE}};' ) );
		$this->add_control( 'accent_color', array( 'label' => 'رنگ اصلی و آیکون فعال', 'type' => Controls_Manager::COLOR, 'default' => '#f20d5b', 'selectors' => array( '{{WRAPPER}} .ard-comments__reaction.is-active, {{WRAPPER}} .ard-comments__submit, {{WRAPPER}} .ard-comments__icon-accent, {{WRAPPER}} .ard-comments__rating-stars span, {{WRAPPER}} .ard-comments__rating-display' => 'color:{{VALUE}};' ) ) );
		$this->add_responsive_control( 'card_gap', array( 'label' => 'فاصله دیدگاه‌ها', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 4, 'max' => 60 ) ), 'default' => array( 'unit' => 'px', 'size' => 24 ), 'selectors' => array( '{{WRAPPER}} .ard-comments__list' => 'gap:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'card_radius', array( 'label' => 'گردی کارت', 'type' => Controls_Manager::DIMENSIONS, 'size_units' => array( 'px', '%' ), 'default' => array( 'top' => 24, 'right' => 24, 'bottom' => 24, 'left' => 24, 'unit' => 'px', 'isLinked' => true ), 'selectors' => array( '{{WRAPPER}} .ard-comments__card' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'avatar_size_style', array( 'label' => 'قطر آواتار', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px' ), 'range' => array( 'px' => array( 'min' => 24, 'max' => 160 ) ), 'default' => array( 'unit' => 'px', 'size' => 64 ), 'selectors' => array( '{{WRAPPER}} .ard-comments__avatar, {{WRAPPER}} .ard-comments__avatar img' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};' ) ) );
		$this->add_responsive_control( 'avatar_radius', array( 'label' => 'گردی آواتار', 'type' => Controls_Manager::SLIDER, 'size_units' => array( 'px', '%' ), 'range' => array( 'px' => array( 'min' => 0, 'max' => 80 ), '%' => array( 'min' => 0, 'max' => 50 ) ), 'default' => array( 'unit' => 'px', 'size' => 50 ), 'selectors' => array( '{{WRAPPER}} .ard-comments__avatar, {{WRAPPER}} .ard-comments__avatar img' => 'border-radius:{{SIZE}}{{UNIT}};' ) ) );
		$this->end_controls_section();

		$this->start_controls_section( 'style_form', array( 'label' => 'فرم و دکمه ارسال', 'tab' => Controls_Manager::TAB_STYLE ) );
		$this->add_control( 'form_bg_color', array( 'label' => 'رنگ زمینه فرم', 'type' => Controls_Manager::COLOR, 'default' => '#f1f2f8', 'selectors' => array( '{{WRAPPER}} .ard-comments__form-wrap' => 'background-color:{{VALUE}};' ) ) );
		$this->add_control( 'form_text_color', array( 'label' => 'رنگ متن و برچسب', 'type' => Controls_Manager::COLOR, 'default' => '#34343a', 'selectors' => array( '{{WRAPPER}} .ard-comments__form-wrap, {{WRAPPER}} .ard-comments__form-title' => 'color:{{VALUE}};' ) ) );
		$this->add_control( 'input_bg', array( 'label' => 'زمینه فیلدها', 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => array( '{{WRAPPER}} .ard-comments__form input, {{WRAPPER}} .ard-comments__form textarea' => 'background-color:{{VALUE}};' ) ) );
		$this->add_control( 'input_color', array( 'label' => 'رنگ متن فیلدها', 'type' => Controls_Manager::COLOR, 'default' => '#34343a', 'selectors' => array( '{{WRAPPER}} .ard-comments__form input, {{WRAPPER}} .ard-comments__form textarea' => 'color:{{VALUE}};' ) ) );
		$this->add_group_control( Group_Control_Typography::get_type(), array( 'name' => 'input_typography', 'selector' => '{{WRAPPER}} .ard-comments__form input, {{WRAPPER}} .ard-comments__form textarea' ) );
		$this->add_control( 'button_bg', array( 'label' => 'زمینه دکمه', 'type' => Controls_Manager::COLOR, 'default' => '#f20d5b', 'selectors' => array( '{{WRAPPER}} .ard-comments__submit' => 'background-color:{{VALUE}};' ) ) );
		$this->add_control( 'button_color', array( 'label' => 'رنگ متن دکمه', 'type' => Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => array( '{{WRAPPER}} .ard-comments__submit' => 'color:{{VALUE}};' ) ) );
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
		$post_id = is_singular( array( 'post', 'product' ) ) ? (int) get_queried_object_id() : (int) get_the_ID();
		$post_type = $post_id ? get_post_type( $post_id ) : '';
		if ( ! $post_id || ! in_array( $post_type, array( 'post', 'product' ), true ) ) {
			$this->render_empty( 'این ویجت را در قالب نوشته یا محصول تکی قرار دهید.' );
			return;
		}
		$is_product = 'product' === $post_type;
		$limit = max( 1, min( 100, (int) $s['limit'] ) );
		$order = 'DESC' === $s['order'] ? 'DESC' : 'ASC';
		$all = get_comments( array( 'post_id' => $post_id, 'status' => 'approve', 'orderby' => 'comment_date_gmt', 'order' => $order, 'number' => 0, 'hierarchical' => false ) );
		if ( $is_product ) {
			$all = array_values( array_filter( $all, static function ( $comment ) { return in_array( $comment->comment_type, array( 'review', 'comment', '' ), true ); } ) );
		}
		$tree = array();
		foreach ( $all as $comment ) { $tree[ (int) $comment->comment_parent ][] = $comment; }
		$roots = isset( $tree[0] ) ? array_slice( $tree[0], 0, $limit ) : array();
		$product = $is_product && function_exists( 'wc_get_product' ) ? wc_get_product( $post_id ) : false;
		$total = $product ? $product->get_review_count() : get_comments_number( $post_id );
		$ratings_enabled = $is_product && function_exists( 'wc_review_ratings_enabled' ) && wc_review_ratings_enabled();
		$rating_required = $ratings_enabled && function_exists( 'wc_review_ratings_required' ) && wc_review_ratings_required();
		$verified_only = $is_product && 'yes' === get_option( 'woocommerce_review_rating_verification_required' );
		$guest_allowed = 'yes' === $s['allow_guest'] && ! get_option( 'comment_registration' ) && ! $verified_only;
		$can_review_product = ! $verified_only || ( is_user_logged_in() && function_exists( 'wc_customer_bought_product' ) && wc_customer_bought_product( wp_get_current_user()->user_email, get_current_user_id(), $post_id ) );
		$style = $this->asset_vars( $s );
		$id = 'ard-comments-' . $this->get_id();
		$tag = in_array( $s['title_tag'], array( 'h2', 'h3', 'h4', 'div' ), true ) ? $s['title_tag'] : 'h3';
		?>
		<section id="<?php echo esc_attr( $id ); ?>" class="ard-comments<?php echo $is_product ? ' ard-comments--product' : ''; ?>" data-ard-comments data-ajax="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( Ardal_Comments::NONCE_ACTION ) ); ?>" data-post-id="<?php echo esc_attr( $post_id ); ?>" style="<?php echo esc_attr( $style ); ?>">
			<header class="ard-comments__header"><span class="ard-comments__header-icon" aria-hidden="true"><?php echo Ardal_Helpers::icon( 'mail', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><<?php echo esc_html( $tag ); ?> class="ard-comments__heading"><?php echo esc_html( $s['heading'] ); ?></<?php echo esc_html( $tag ); ?>><?php if ( 'yes' === $s['show_count'] ) : ?><span class="ard-comments__count"><?php echo esc_html( number_format_i18n( $total ) ); ?></span><?php endif; ?></header>
			<div class="ard-comments__rule"></div>
			<?php if ( $roots ) : ?><ol class="ard-comments__list"><?php foreach ( $roots as $comment ) { $this->render_comment( $comment, $tree, 0, (int) $s['reply_depth'], $s ); } ?></ol><?php else : ?><p class="ard-comments__empty"><?php echo esc_html( $s['empty_text'] ); ?></p><?php endif; ?>
			<?php if ( comments_open( $post_id ) && ( $guest_allowed || ( is_user_logged_in() && $can_review_product ) ) ) : ?>
				<div class="ard-comments__form-wrap" style="<?php echo esc_attr( $this->asset_vars( $s, 'form' ) ); ?>">
					<div class="ard-comments__form-heading"><span class="ard-comments__form-icon" aria-hidden="true"><?php echo Ardal_Helpers::icon( 'mail', 26 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><h3 class="ard-comments__form-title"><?php echo esc_html( $s['form_title'] ); ?></h3></div>
					<div class="ard-comments__replying" data-replying hidden><span></span><button type="button" data-cancel-reply>لغو پاسخ</button></div>
					<form class="ard-comments__form" data-comment-form>
						<input type="hidden" name="comment_post_ID" value="<?php echo esc_attr( $post_id ); ?>">
						<?php if ( ! is_user_logged_in() ) : ?><div class="ard-comments__fields">
							<label><span>نام</span><input name="first_name" type="text" autocomplete="given-name" placeholder="نام" required></label>
							<label><span>نام خانوادگی</span><input name="last_name" type="text" autocomplete="family-name" placeholder="نام خانوادگی" required></label>
							<?php if ( 'yes' === $s['show_phone'] ) : ?><label><span>شماره تماس</span><input name="phone" type="tel" autocomplete="tel" placeholder="شماره تماس"></label><?php endif; ?>
							<?php if ( 'yes' === $s['show_email'] || get_option( 'require_name_email' ) ) : ?><label><span>ایمیل</span><input name="email" type="email" autocomplete="email" placeholder="ایمیل" <?php echo get_option( 'require_name_email' ) ? 'required' : ''; ?>></label><?php endif; ?>
						</div><?php endif; ?>
						<?php if ( $ratings_enabled ) : ?><fieldset class="ard-comments__rating" data-product-rating><legend>امتیاز شما<?php echo $rating_required ? ' <span aria-hidden="true">*</span>' : ''; ?></legend><div class="ard-comments__rating-stars" dir="rtl"><?php for ( $star = 5; $star >= 1; $star-- ) : ?><label><input type="radio" name="rating" value="<?php echo esc_attr( $star ); ?>" <?php echo $rating_required ? 'required' : ''; ?>><span aria-label="<?php echo esc_attr( $star . ' از ۵ ستاره' ); ?>">★</span></label><?php endfor; ?></div></fieldset><?php endif; ?>
						<label class="ard-comments__message"><span>پیام</span><textarea name="content" rows="5" placeholder="پیام خود را بنویسید..." required></textarea></label>
						<div class="ard-comments__honeypot" aria-hidden="true"><label>Website<input name="website" type="text" tabindex="-1" autocomplete="off"></label></div>
						<div class="ard-comments__form-footer"><button class="ard-comments__submit" type="submit"><?php echo esc_html( $s['submit_text'] ); ?><span aria-hidden="true">←</span></button><span class="ard-comments__status" data-form-status role="status" aria-live="polite"></span></div>
					</form>
				</div>
			<?php elseif ( ! comments_open( $post_id ) ) : ?><p class="ard-comments__closed"><?php echo esc_html( $s['closed_text'] ); ?></p><?php elseif ( $verified_only && ! $can_review_product ) : ?><p class="ard-comments__closed">برای ثبت نظر باید با حساب خریدارِ تأییدشده وارد شوید.</p><?php else : ?><p class="ard-comments__closed">برای ثبت دیدگاه وارد حساب کاربری خود شوید.</p><?php endif; ?>
		</section>
		<?php
	}

	private function render_comment( $comment, $tree, $depth, $max_depth, $s ) {
		$comment_url = get_comment_link( $comment );
		$date = 'relative' === $s['date_format'] ? human_time_diff( get_comment_time( 'U', true, false, $comment ), current_time( 'timestamp', true ) ) . ' پیش' : Ardal_Jalali::format( get_comment_time( 'U', true, false, $comment ), 'Y/m/d' );
		$votes = get_comment_meta( $comment->comment_ID, '_ardal_comment_votes', true ); $votes = is_array( $votes ) ? $votes : array();
		$likes = count( array_filter( $votes, static function ( $v ) { return 'like' === $v; } ) ); $dislikes = count( array_filter( $votes, static function ( $v ) { return 'dislike' === $v; } ) );
		$avatar = get_avatar( $comment, max( 24, min( 160, (int) $s['avatar_size'] ) ), '', '', array( 'class' => 'ard-comments__avatar-image' ) );
		$rating = (int) get_comment_meta( $comment->comment_ID, 'rating', true );
		$is_product_review = 'product' === get_post_type( $comment->comment_post_ID );
		?>
		<li class="ard-comments__item" id="comment-<?php echo esc_attr( $comment->comment_ID ); ?>"><article class="ard-comments__card" data-comment-id="<?php echo esc_attr( $comment->comment_ID ); ?>" style="<?php echo esc_attr( $this->asset_vars( $s, 'comment' ) ); ?>">
			<div class="ard-comments__identity" style="<?php echo esc_attr( $this->asset_vars( $s, 'author' ) ); ?>"><?php if ( 'yes' === $s['show_avatar'] ) : ?><span class="ard-comments__avatar"><?php echo $avatar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><?php endif; ?><span class="ard-comments__author-wrap"><strong class="ard-comments__author"><?php echo esc_html( get_comment_author( $comment ) ); ?></strong><?php if ( $is_product_review && 'yes' === $s['show_verified'] && function_exists( 'wc_review_is_from_verified_owner' ) && wc_review_is_from_verified_owner( $comment->comment_ID ) ) : ?><span class="ard-comments__verified">خریدار تأییدشده</span><?php endif; ?></span></div>
			<div class="ard-comments__body"><?php if ( $is_product_review && $rating > 0 ) : ?><div class="ard-comments__rating-display" aria-label="امتیاز <?php echo esc_attr( $rating ); ?> از ۵"><span><?php echo esc_html( str_repeat( '★', $rating ) . str_repeat( '☆', 5 - $rating ) ); ?></span></div><?php endif; ?><div class="ard-comments__text"><?php comment_text( $comment ); ?></div><div class="ard-comments__actions">
				<?php if ( 'yes' === $s['show_date'] ) : ?><span class="ard-comments__meta"><span aria-hidden="true">◷</span><?php echo esc_html( $date ); ?></span><?php endif; ?>
				<?php if ( 'yes' === $s['enable_reply'] && $depth < $max_depth && comments_open( $comment->comment_post_ID ) ) : ?><button class="ard-comments__reply" type="button" data-reply-to="<?php echo esc_attr( $comment->comment_ID ); ?>" data-author="<?php echo esc_attr( get_comment_author( $comment ) ); ?>">پاسخ</button><?php endif; ?>
				<?php if ( 'yes' === $s['enable_reactions'] ) : ?><div class="ard-comments__reactions"><button class="ard-comments__reaction" type="button" data-reaction="like" aria-label="پسندیدن"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 10v11H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3Zm0 0 5-8a3 3 0 0 1 2 3l-1 5h6a3 3 0 0 1 3 3l-2 7a3 3 0 0 1-3 2H7"/></svg><b data-vote-count="likes"><?php echo esc_html( $likes ); ?></b></button><button class="ard-comments__reaction" type="button" data-reaction="dislike" aria-label="نپسندیدن"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 14V3H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h3Zm0 0 5 8a3 3 0 0 0 2-3l-1-5h6a3 3 0 0 0 3-3l-2-7a3 3 0 0 0-3-2H7"/></svg><b data-vote-count="dislikes"><?php echo esc_html( $dislikes ); ?></b></button></div><?php endif; ?>
				<?php if ( 'yes' === $s['enable_share'] ) : ?><button class="ard-comments__share" type="button" data-share-url="<?php echo esc_url( $comment_url ); ?>" data-share-title="<?php echo esc_attr( get_comment_author( $comment ) . ' — ' . get_the_title( $comment->comment_post_ID ) ); ?>" aria-label="اشتراک‌گذاری دیدگاه"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m22 2-7 20-4-9-9-4 20-7ZM22 2 11 13"/></svg></button><?php endif; ?>
			</div></div>
		</article><?php if ( $depth < $max_depth && ! empty( $tree[ (int) $comment->comment_ID ] ) ) : ?><ol class="ard-comments__children"><?php foreach ( $tree[ (int) $comment->comment_ID ] as $child ) { $this->render_comment( $child, $tree, $depth + 1, $max_depth, $s ); } ?></ol><?php endif; ?></li>
		<?php
	}

	private function asset_vars( $s, $target = 'box' ) {
		$vars = array();
		if ( 'box' === $target && ! empty( $s['background_image']['url'] ) ) { $vars[] = '--ard-comments-bg:url("' . esc_url( $s['background_image']['url'] ) . '")'; }
		$background_key = 'form' === $target ? 'form_background' : ( 'author' === $target ? 'author_background' : 'comment_background' );
		$mask_key = 'form' === $target ? 'form_mask' : ( 'author' === $target ? 'author_mask' : 'comment_mask' );
		if ( ! empty( $s[ $background_key ]['url'] ) ) { $vars[] = '--ard-comments-surface:url("' . esc_url( $s[ $background_key ]['url'] ) . '")'; }
		if ( ! empty( $s[ $mask_key ]['url'] ) ) { $url = esc_url( $s[ $mask_key ]['url'] ); $vars[] = '--ard-comments-mask:url("' . $url . '")'; $vars[] = '-webkit-mask-image:url("' . $url . '")'; $vars[] = 'mask-image:url("' . $url . '")'; }
		return implode( ';', $vars );
	}
}
