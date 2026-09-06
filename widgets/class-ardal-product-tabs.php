<?php
/**
 * ویجت تب‌های جامع صفحه محصول (مشخصات فنی، توضیحات، نظرات کاربران، پرسش و پاسخ).
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Ardal_Widget_Product_Tabs extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-product-tabs';
	}

	public function get_title() {
		return 'اردال — تب‌های جامع محصول';
	}

	public function get_icon() {
		return 'eicon-tabs';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'تب', 'مشخصات', 'توضیحات', 'نظرات', 'پرسش', 'tabs', 'reviews', 'specs' );
	}

	protected function register_controls() {
		// ==================== بخش ۱: تب مشخصات فنی ====================
		$this->start_controls_section(
			'section_specs',
			array( 'label' => 'تب مشخصات فنی' )
		);

		$this->add_control(
			'tab_specs_title',
			array(
				'label'   => 'عنوان تب',
				'type'    => Controls_Manager::TEXT,
				'default' => 'مشخصات فنی',
			)
		);

		$repeater_specs = new Repeater();
		$repeater_specs->add_control(
			'key',
			array(
				'label'   => 'ویژگی',
				'type'    => Controls_Manager::TEXT,
				'default' => 'جنس رویه',
			)
		);
		$repeater_specs->add_control(
			'val',
			array(
				'label'   => 'مقدار',
				'type'    => Controls_Manager::TEXT,
				'default' => 'مش مهندسی‌شده',
			)
		);

		$this->add_control(
			'specs_items',
			array(
				'label'       => 'لیست مشخصات فنی',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater_specs->get_controls(),
				'title_field' => '{{{ key }}}: {{{ val }}}',
				'default'     => array(
					array( 'key' => 'برند', 'val' => 'پرو-فیت (Pro-Fit)' ),
					array( 'key' => 'جنس رویه', 'val' => 'مش مهندسی‌شده' ),
					array( 'key' => 'جنس زیره', 'val' => 'لاستیک ترموپلاستیک' ),
					array( 'key' => 'نوع بند', 'val' => 'بندی' ),
					array( 'key' => 'کاربری', 'val' => 'دویدن، تردمیل، تمرین سبک' ),
					array( 'key' => 'وزن (سایز ۴۲)', 'val' => '۲۴۵ گرم' ),
					array( 'key' => 'افت پاشنه', 'val' => '۸ میلی‌متر' ),
					array( 'key' => 'فناوری میان‌کفی', 'val' => 'فوم AeroCell' ),
					array( 'key' => 'ساخت', 'val' => 'ویتنام' ),
					array( 'key' => 'گارانتی', 'val' => '۱۸ ماه اصالت و سلامت کالا' ),
				),
			)
		);

		$this->end_controls_section();

		// ==================== بخش ۲: تب توضیحات ====================
		$this->start_controls_section(
			'section_desc',
			array( 'label' => 'تب توضیحات و نقد و بررسی' )
		);

		$this->add_control(
			'tab_desc_title',
			array(
				'label'   => 'عنوان تب',
				'type'    => Controls_Manager::TEXT,
				'default' => 'توضیحات',
			)
		);

		$this->add_control(
			'desc_heading',
			array(
				'label'   => 'تیتر توضیحات',
				'type'    => Controls_Manager::TEXT,
				'default' => 'درباره Aero X2',
			)
		);

		$this->add_control(
			'desc_content',
			array(
				'label'   => 'متن توضیحات',
				'type'    => Controls_Manager::WYSIWYG,
				'default' => '<p>Aero X2 برای دویدن روی آسفالت و تردمیل طراحی شده است. فوم میان‌کفی با بازگشت انرژی بالا، ضربه‌های پاشنه را جذب می‌کند و در کیلومترهای پایانی مسیر، فشار روی مچ و زانو را کاهش می‌دهد.</p><p>رویه مش مهندسی‌شده هوای بیشتری عبور می‌دهد و وزن هر لنگه در سایز ۴۲ فقط ۲۴۵ گرم است. زبانه و پاشنه پدگذاری‌شده، پا را بدون فشار در جای خود نگه می‌دارد و رویه بدون درز، احتمال زدگی در مسافت‌های طولانی را کم می‌کند.</p><p>زیره لاستیکی با الگوی آجدار چندجهته، چسبندگی روی سطح خیس را حفظ می‌کند. برای دوهای روزانه ۵ تا ۲۱ کیلومتر و تمرین‌های سبک باشگاهی مناسب است.</p>',
			)
		);

		$this->add_control(
			'desc_image_1',
			array(
				'label'   => 'عکس ویژه ۱ توضیحات',
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => '' ),
			)
		);

		$this->add_control(
			'desc_image_1_label',
			array(
				'label'   => 'متن عکس ۱ (در صورت نبود تصویر)',
				'type'    => Controls_Manager::TEXT,
				'default' => '[ عکس جزئیات زیره ۱۰۰۰×۷۰۰ ]',
			)
		);

		$this->add_control(
			'desc_image_2',
			array(
				'label'   => 'عکس ویژه ۲ توضیحات',
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => '' ),
			)
		);

		$this->add_control(
			'desc_image_2_label',
			array(
				'label'   => 'متن عکس ۲ (در صورت نبود تصویر)',
				'type'    => Controls_Manager::TEXT,
				'default' => '[ عکس رویه مش ۱۰۰۰×۵۰۰ ]',
			)
		);

		$this->end_controls_section();

		// ==================== بخش ۳: تب نظرات کاربران ====================
		$this->start_controls_section(
			'section_reviews',
			array( 'label' => 'تب نظرات کاربران' )
		);

		$this->add_control(
			'tab_reviews_title',
			array(
				'label'   => 'عنوان تب',
				'type'    => Controls_Manager::TEXT,
				'default' => 'نظرات کاربران (۴۸)',
			)
		);

		$this->add_control(
			'review_overall_score',
			array(
				'label'   => 'میانگین امتیاز',
				'type'    => Controls_Manager::TEXT,
				'default' => '۴٫۶',
			)
		);

		$this->add_control(
			'review_overall_note',
			array(
				'label'   => 'زیرعنوان امتیاز',
				'type'    => Controls_Manager::TEXT,
				'default' => 'از ۵ · ۴۸ نظر',
			)
		);

		$repeater_bars = new Repeater();
		$repeater_bars->add_control(
			'label',
			array(
				'label'   => 'ستاره',
				'type'    => Controls_Manager::TEXT,
				'default' => '۵ ★',
			)
		);
		$repeater_bars->add_control(
			'percent',
			array(
				'label'   => 'درصد نوار',
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 100,
				'default' => 72,
			)
		);
		$repeater_bars->add_control(
			'count',
			array(
				'label'   => 'تعداد نظرات',
				'type'    => Controls_Manager::TEXT,
				'default' => '۳۴',
			)
		);

		$this->add_control(
			'rating_bars',
			array(
				'label'       => 'تفکیک امتیازها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater_bars->get_controls(),
				'title_field' => '{{{ label }}}: {{{ count }}} نظر ({{{ percent }}}%)',
				'default'     => array(
					array( 'label' => '۵ ★', 'percent' => 72, 'count' => '۳۴' ),
					array( 'label' => '۴ ★', 'percent' => 17, 'count' => '۸' ),
					array( 'label' => '۳ ★', 'percent' => 8, 'count' => '۴' ),
					array( 'label' => '۲ ★', 'percent' => 4, 'count' => '۲' ),
					array( 'label' => '۱ ★', 'percent' => 0, 'count' => '۰' ),
				),
			)
		);

		$this->add_control(
			'btn_add_review',
			array(
				'label'   => 'متن دکمه ثبت نظر',
				'type'    => Controls_Manager::TEXT,
				'default' => 'ثبت نظر شما',
			)
		);

		$repeater_reviews = new Repeater();
		$repeater_reviews->add_control(
			'initial',
			array(
				'label'   => 'حرف اول نام (آواتار)',
				'type'    => Controls_Manager::TEXT,
				'default' => 'م',
			)
		);
		$repeater_reviews->add_control(
			'author',
			array(
				'label'   => 'نام کاربر',
				'type'    => Controls_Manager::TEXT,
				'default' => 'مهدی ر.',
			)
		);
		$repeater_reviews->add_control(
			'stars',
			array(
				'label'   => 'ستاره‌ها',
				'type'    => Controls_Manager::TEXT,
				'default' => '★★★★★',
			)
		);
		$repeater_reviews->add_control(
			'date',
			array(
				'label'   => 'تاریخ',
				'type'    => Controls_Manager::TEXT,
				'default' => '۳ روز پیش',
			)
		);
		$repeater_reviews->add_control(
			'body',
			array(
				'label'   => 'متن نظر',
				'type'    => Controls_Manager::TEXTAREA,
				'default' => 'دو هفته با این کفش دویدم، هفته‌ای حدود ۳۰ کیلومتر. کفی نرم است ولی زیر پاشنه خالی نمی‌کند. سایز ۴۲ کمی بزرگ‌تر از حد معمول بود، یک سایز کوچک‌تر بگیرید.',
			)
		);
		$repeater_reviews->add_control(
			'helpful_count',
			array(
				'label'   => 'تعداد مفید بود',
				'type'    => Controls_Manager::TEXT,
				'default' => '۱۲',
			)
		);

		$this->add_control(
			'reviews_list',
			array(
				'label'       => 'لیست نظرات',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater_reviews->get_controls(),
				'title_field' => '{{{ author }}} ({{{ stars }}})',
				'default'     => array(
					array(
						'initial'       => 'م',
						'author'        => 'مهدی ر.',
						'stars'         => '★★★★★',
						'date'          => '۳ روز پیش',
						'body'          => 'دو هفته با این کفش دویدم، هفته‌ای حدود ۳۰ کیلومتر. کفی نرم است ولی زیر پاشنه خالی نمی‌کند. سایز ۴۲ کمی بزرگ‌تر از حد معمول بود، یک سایز کوچک‌تر بگیرید.',
						'helpful_count' => '۱۲',
					),
					array(
						'initial'       => 'ن',
						'author'        => 'نگار ک.',
						'stars'         => '★★★★☆',
						'date'          => '۱ هفته پیش',
						'body'          => 'وزنش واقعاً کم است و روی تردمیل صدا نمی‌دهد. تنها ایرادش این بود که رنگ سفید زود کثیف می‌شود.',
						'helpful_count' => '۷',
					),
					array(
						'initial'       => 'ا',
						'author'        => 'امیر ص.',
						'stars'         => '★★★★☆',
						'date'          => '۲ هفته پیش',
						'body'          => 'ارسال یک روزه بود و بسته‌بندی سالم رسید. برای مسیر آسفالت خوب است، روی خاکی چسبندگی متوسطی دارد.',
						'helpful_count' => '۵',
					),
				),
			)
		);

		$this->end_controls_section();

		// ==================== بخش ۴: تب پرسش و پاسخ ====================
		$this->start_controls_section(
			'section_qna',
			array( 'label' => 'تب پرسش و پاسخ' )
		);

		$this->add_control(
			'tab_qna_title',
			array(
				'label'   => 'عنوان تب',
				'type'    => Controls_Manager::TEXT,
				'default' => 'پرسش و پاسخ',
			)
		);

		$repeater_qna = new Repeater();
		$repeater_qna->add_control(
			'question',
			array(
				'label'   => 'پرسش',
				'type'    => Controls_Manager::TEXT,
				'default' => 'برای پای پهن مناسب است؟',
			)
		);
		$repeater_qna->add_control(
			'answer',
			array(
				'label'   => 'پاسخ',
				'type'    => Controls_Manager::TEXTAREA,
				'default' => 'رویه مش کشسان است و برای پای پهن با انتخاب نیم سایز بزرگ‌تر مشکلی ایجاد نمی‌کند.',
			)
		);

		$this->add_control(
			'qna_list',
			array(
				'label'       => 'لیست پرسش و پاسخ‌ها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater_qna->get_controls(),
				'title_field' => '{{{ question }}}',
				'default'     => array(
					array(
						'question' => 'برای پای پهن مناسب است؟',
						'answer'   => 'رویه مش کشسان است و برای پای پهن با انتخاب نیم سایز بزرگ‌تر مشکلی ایجاد نمی‌کند.',
					),
					array(
						'question' => 'کفی قابل تعویض است؟',
						'answer'   => 'بله، کفی داخلی چسب‌ندارد و می‌توانید آن را با کفی طبی جایگزین کنید.',
					),
					array(
						'question' => 'امکان تحویل در همان روز در تهران وجود دارد؟',
						'answer'   => 'برای سفارش‌های ثبت‌شده تا ساعت ۱۲ ظهر، ارسال همان روز در تهران انجام می‌شود.',
					),
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$specs   = is_array( $s['specs_items'] ) ? $s['specs_items'] : array();
		$bars    = is_array( $s['rating_bars'] ) ? $s['rating_bars'] : array();
		$reviews = is_array( $s['reviews_list'] ) ? $s['reviews_list'] : array();
		$qna     = is_array( $s['qna_list'] ) ? $s['qna_list'] : array();

		$tabs = array(
			array( 'id' => 'specs', 'title' => $s['tab_specs_title'] ),
			array( 'id' => 'desc', 'title' => $s['tab_desc_title'] ),
			array( 'id' => 'reviews', 'title' => $s['tab_reviews_title'] ),
			array( 'id' => 'qna', 'title' => $s['tab_qna_title'] ),
		);
		?>
		<div class="ard-ptabs" data-ard-product-tabs>
			
			<!-- نوار سربرگ تب‌ها -->
			<div class="ard-ptabs__nav" role="tablist">
				<?php foreach ( $tabs as $idx => $t ) : ?>
					<button type="button" class="ard-ptabs__btn<?php echo 0 === $idx ? ' is-active' : ''; ?>" role="tab" data-tab-target="ard-tab-<?php echo esc_attr( $t['id'] ); ?>" id="ard-tab-btn-<?php echo esc_attr( $t['id'] ); ?>" aria-selected="<?php echo 0 === $idx ? 'true' : 'false'; ?>">
						<?php echo esc_html( $t['title'] ); ?>
					</button>
				<?php endforeach; ?>
			</div>

			<!-- پنل‌های محتوا -->
			<div class="ard-ptabs__panels">
				
				<!-- ==================== تب ۱: مشخصات فنی ==================== -->
				<div class="ard-ptabs__panel is-active" id="ard-tab-specs" role="tabpanel">
					<?php if ( ! empty( $specs ) ) : ?>
						<div class="ard-ptabs-specs">
							<?php foreach ( $specs as $idx => $sp ) : ?>
								<div class="ard-ptabs-specs__item<?php echo 0 === ( $idx % 2 ) ? ' ard-ptabs-specs__item--even' : ''; ?>">
									<span class="ard-ptabs-specs__key"><?php echo esc_html( $sp['key'] ); ?></span>
									<span class="ard-ptabs-specs__val"><?php echo esc_html( $sp['val'] ); ?></span>
								</div>
							<?php endforeach; ?>
						</div>
					<?php else : ?>
						<p>مشخصات فنی ثبت نشده است.</p>
					<?php endif; ?>
				</div>

				<!-- ==================== تب ۲: توضیحات ==================== -->
				<div class="ard-ptabs__panel" id="ard-tab-desc" role="tabpanel">
					<div class="ard-ptabs-desc">
						<div class="ard-ptabs-desc__text">
							<?php if ( ! empty( $s['desc_heading'] ) ) : ?>
								<h3 class="ard-ptabs-desc__title"><?php echo esc_html( $s['desc_heading'] ); ?></h3>
							<?php endif; ?>
							<div class="ard-ptabs-desc__body">
								<?php echo wp_kses_post( $s['desc_content'] ); ?>
							</div>
						</div>

						<div class="ard-ptabs-desc__media">
							<div class="ard-ptabs-desc__img-box">
								<?php if ( ! empty( $s['desc_image_1']['url'] ) ) : ?>
									<img src="<?php echo esc_url( $s['desc_image_1']['url'] ); ?>" alt="<?php echo esc_attr( $s['desc_heading'] ); ?>">
								<?php else : ?>
									<span><?php echo esc_html( $s['desc_image_1_label'] ); ?></span>
								<?php endif; ?>
							</div>
							<div class="ard-ptabs-desc__img-box ard-ptabs-desc__img-box--sm">
								<?php if ( ! empty( $s['desc_image_2']['url'] ) ) : ?>
									<img src="<?php echo esc_url( $s['desc_image_2']['url'] ); ?>" alt="<?php echo esc_attr( $s['desc_heading'] ); ?>">
								<?php else : ?>
									<span><?php echo esc_html( $s['desc_image_2_label'] ); ?></span>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>

				<!-- ==================== تب ۳: نظرات ==================== -->
				<div class="ard-ptabs__panel" id="ard-tab-reviews" role="tabpanel">
					<div class="ard-ptabs-reviews">
						
						<!-- سایدبار خلاصه امتیازها -->
						<div class="ard-ptabs-reviews__summary">
							<div class="ard-ptabs-reviews__score-wrap">
								<span class="ard-ptabs-reviews__score"><?php echo esc_html( $s['review_overall_score'] ); ?></span>
								<span class="ard-ptabs-reviews__note"><?php echo esc_html( $s['review_overall_note'] ); ?></span>
							</div>
							<div class="ard-ptabs-reviews__stars">★★★★☆</div>

							<?php if ( ! empty( $bars ) ) : ?>
								<div class="ard-ptabs-reviews__bars">
									<?php foreach ( $bars as $b ) : ?>
										<div class="ard-ptabs-reviews__bar-row">
											<span class="ard-ptabs-reviews__bar-label"><?php echo esc_html( $b['label'] ); ?></span>
											<span class="ard-ptabs-reviews__bar-track">
												<span class="ard-ptabs-reviews__bar-fill" style="width: <?php echo esc_attr( (float) $b['percent'] ); ?>%"></span>
											</span>
											<span class="ard-ptabs-reviews__bar-count"><?php echo esc_html( Ardal_Helpers::fa_digits( $b['count'] ) ); ?></span>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

							<button type="button" class="ard-ptabs-reviews__btn"><?php echo esc_html( $s['btn_add_review'] ); ?></button>
						</div>

						<!-- لیست دیدگاه‌ها -->
						<div class="ard-ptabs-reviews__list">
							<?php if ( ! empty( $reviews ) ) : ?>
								<?php foreach ( $reviews as $rev ) : ?>
									<div class="ard-review-card">
										<div class="ard-review-card__header">
											<span class="ard-review-card__avatar"><?php echo esc_html( $rev['initial'] ); ?></span>
											<span class="ard-review-card__author"><?php echo esc_html( $rev['author'] ); ?></span>
											<span class="ard-review-card__stars"><?php echo esc_html( $rev['stars'] ); ?></span>
											<span class="ard-review-card__date"><?php echo esc_html( $rev['date'] ); ?></span>
										</div>
										<div class="ard-review-card__body"><?php echo esc_html( $rev['body'] ); ?></div>
										<div class="ard-review-card__footer">
											<button type="button" class="ard-review-card__vote">مفید بود (<?php echo esc_html( Ardal_Helpers::fa_digits( $rev['helpful_count'] ) ); ?>)</button>
											<button type="button" class="ard-review-card__vote">مفید نبود</button>
										</div>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>

					</div>
				</div>

				<!-- ==================== تب ۴: پرسش و پاسخ ==================== -->
				<div class="ard-ptabs__panel" id="ard-tab-qna" role="tabpanel">
					<div class="ard-ptabs-qna">
						<?php if ( ! empty( $qna ) ) : ?>
							<div class="ard-ptabs-qna__list">
								<?php foreach ( $qna as $q ) : ?>
									<div class="ard-qna-card">
										<div class="ard-qna-card__question">
											<span class="ard-qna-card__badge ard-qna-card__badge--q">س</span>
											<span class="ard-qna-card__text"><?php echo esc_html( $q['question'] ); ?></span>
										</div>
										<div class="ard-qna-card__answer">
											<span class="ard-qna-card__badge ard-qna-card__badge--a">پ</span>
											<span class="ard-qna-card__text"><?php echo esc_html( $q['answer'] ); ?></span>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>

			</div>
		</div>
		<?php
	}
}
