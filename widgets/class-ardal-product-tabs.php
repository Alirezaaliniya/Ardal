<?php
/**
 * ویجت تب‌های محصول: مشخصات، توضیحات، نظرات و پرسش و پاسخ.
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
		return 'اردال — تب‌های محصول';
	}

	public function get_icon() {
		return 'eicon-tabs';
	}

	public function get_categories() {
		return array( Ardal_Elementor::CATEGORY_PRODUCT );
	}

	public function get_keywords() {
		return array( 'اردال', 'تب', 'مشخصات', 'نظرات', 'tabs', 'reviews', 'specs' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'تب‌ها' )
		);

		$this->add_tag_control( 'panel_tag', 'تگ عنوان داخل تب', 'h3' );

		$this->add_control(
			'source',
			array(
				'label'       => 'منبع',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'auto',
				'options'     => array(
					'auto'   => 'خودکار از محصول جاری',
					'manual' => 'دستی',
				),
				'description' => 'حالت خودکار مشخصات، توضیحات و نظرات را از خود محصول می‌خواند. پرسش و پاسخ همیشه دستی است چون ووکامرس چنین بخشی ندارد.',
			)
		);

		foreach ( array(
			'specs'   => array( 'مشخصات فنی', 'yes' ),
			'desc'    => array( 'توضیحات', 'yes' ),
			'reviews' => array( 'نظرات کاربران', 'yes' ),
			'qna'     => array( 'پرسش و پاسخ', '' ),
		) as $key => $def ) {
			$this->add_control(
				'show_' . $key,
				array(
					'label'        => 'تب «' . $def[0] . '»',
					'type'         => Controls_Manager::SWITCHER,
					'default'      => $def[1],
					'return_value' => 'yes',
				)
			);

			$this->add_control(
				'label_' . $key,
				array(
					'label'     => 'عنوان تب',
					'type'      => Controls_Manager::TEXT,
					'default'   => $def[0],
					'condition' => array( 'show_' . $key => 'yes' ),
				)
			);
		}

		$this->add_control(
			'layout',
			array(
				'label'       => 'نحوه نمایش',
				'type'        => Controls_Manager::SELECT,
				'default'     => 'tabs',
				'options'     => array(
					'tabs'     => 'تب — هر بار یک بخش',
					'sections' => 'همه بخش‌ها زیر هم + نوار ناوبری',
				),
				'description' => 'در حالت دوم همه بخش‌ها با هم دیده می‌شوند و نوار عنوان‌ها نقش ناوبری دارد: با کلیک به بخش می‌رود و با اسکرول، بخش جاری در نوار پررنگ می‌شود.',
			)
		);

		$this->add_control(
			'nav_sticky',
			array(
				'label'        => 'نوار ناوبری چسبان بماند',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => array( 'layout' => 'sections' ),
			)
		);

		$this->add_control(
			'nav_offset',
			array(
				'label'       => 'فاصله از بالا هنگام چسبیدن',
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array( 'px' => array( 'min' => 0, 'max' => 200 ) ),
				'default'     => array( 'unit' => 'px', 'size' => 0 ),
				'description' => 'اگر هدر چسبان دارید، به اندازهٔ ارتفاع آن بگذارید.',
				'condition'   => array( 'layout' => 'sections', 'nav_sticky' => 'yes' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_specs',
			array(
				'label'     => 'مشخصات فنی',
				'condition' => array( 'show_specs' => 'yes' ),
			)
		);

		$spec = new Repeater();

		$spec->add_control(
			'spec_key',
			array(
				'label'   => 'عنوان',
				'type'    => Controls_Manager::TEXT,
				'default' => 'برند',
			)
		);

		$spec->add_control(
			'spec_value',
			array(
				'label'   => 'مقدار',
				'type'    => Controls_Manager::TEXT,
				'default' => 'پرو-فیت',
			)
		);

		$this->add_control(
			'specs',
			array(
				'label'       => 'مشخصات',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $spec->get_controls(),
				'title_field' => '{{{ spec_key }}}',
				'condition'   => array( 'source' => 'manual' ),
				'default'     => array(
					array( 'spec_key' => 'برند', 'spec_value' => 'پرو-فیت (Pro-Fit)' ),
					array( 'spec_key' => 'جنس رویه', 'spec_value' => 'مش مهندسی‌شده' ),
					array( 'spec_key' => 'جنس زیره', 'spec_value' => 'لاستیک ترموپلاستیک' ),
					array( 'spec_key' => 'کاربری', 'spec_value' => 'دویدن، تردمیل، تمرین سبک' ),
					array( 'spec_key' => 'گارانتی', 'spec_value' => '۱۸ ماه اصالت و سلامت کالا' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_desc',
			array(
				'label'     => 'توضیحات',
				'condition' => array( 'show_desc' => 'yes' ),
			)
		);

		$this->add_control(
			'desc_title',
			array(
				'label'       => 'عنوان بخش',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => 'مثلاً درباره این محصول',
				'description' => 'خالی بگذارید تا نمایش داده نشود.',
			)
		);

		$this->add_control(
			'desc_body',
			array(
				'label'     => 'متن',
				'type'      => Controls_Manager::WYSIWYG,
				'default'   => '<p>توضیحات محصول را اینجا بنویسید.</p>',
				'condition' => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'desc_image_1',
			array(
				'label' => 'تصویر کناری ۱',
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$this->add_control(
			'desc_image_2',
			array(
				'label' => 'تصویر کناری ۲',
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_reviews',
			array(
				'label'     => 'نظرات',
				'condition' => array( 'show_reviews' => 'yes' ),
			)
		);

		$this->add_control(
			'reviews_limit',
			array(
				'label'     => 'حداکثر تعداد نظر',
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 30,
				'default'   => 5,
				'condition' => array( 'source' => 'auto' ),
			)
		);

		$this->add_control(
			'review_btn_text',
			array(
				'label'   => 'متن دکمه ثبت نظر',
				'type'    => Controls_Manager::TEXT,
				'default' => 'ثبت نظر شما',
			)
		);

		$this->add_control(
			'show_review_form',
			array(
				'label'        => 'نمایش فرم ثبت نظر ووکامرس',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => 'فرم واقعی ووکامرس شامل امتیاز، نام، ایمیل و متن نظر است و نظر را برای همان محصول ثبت می‌کند.',
				'condition'    => array( 'source' => 'auto' ),
			)
		);

		$review = new Repeater();

		$review->add_control(
			'name',
			array(
				'label'   => 'نام',
				'type'    => Controls_Manager::TEXT,
				'default' => 'مهدی ر.',
			)
		);

		$review->add_control(
			'date',
			array(
				'label'   => 'تاریخ',
				'type'    => Controls_Manager::TEXT,
				'default' => '۳ روز پیش',
			)
		);

		$review->add_control(
			'rating',
			array(
				'label'   => 'امتیاز',
				'type'    => Controls_Manager::SLIDER,
				'range'   => array( 'px' => array( 'min' => 1, 'max' => 5 ) ),
				'default' => array( 'size' => 5 ),
			)
		);

		$review->add_control(
			'body',
			array(
				'label'   => 'متن نظر',
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 4,
				'default' => 'کیفیت ساخت خوب بود و سایزبندی درست درآمد.',
			)
		);

		$this->add_control(
			'reviews',
			array(
				'label'       => 'نظرها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $review->get_controls(),
				'title_field' => '{{{ name }}}',
				'condition'   => array( 'source' => 'manual' ),
				'default'     => array(
					array( 'name' => 'مهدی ر.', 'date' => '۳ روز پیش' ),
					array( 'name' => 'نگار ک.', 'date' => '۱ هفته پیش' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_qna',
			array(
				'label'     => 'پرسش و پاسخ',
				'condition' => array( 'show_qna' => 'yes' ),
			)
		);

		$qna = new Repeater();

		$qna->add_control(
			'question',
			array(
				'label'       => 'پرسش',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'default'     => 'برای پای پهن مناسب است؟',
				'label_block' => true,
			)
		);

		$qna->add_control(
			'answer',
			array(
				'label'       => 'پاسخ',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => 'بله، رویه کشسان است و مشکلی ایجاد نمی‌کند.',
				'label_block' => true,
			)
		);

		$this->add_control(
			'qna',
			array(
				'label'       => 'پرسش‌ها',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $qna->get_controls(),
				'title_field' => '{{{ question }}}',
				'default'     => array( array() ),
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

		$this->add_responsive_control(
			'specs_columns',
			array(
				'label'          => 'ستون‌های مشخصات',
				'type'           => Controls_Manager::NUMBER,
				'min'            => 1,
				'max'            => 3,
				'default'        => 2,
				'tablet_default' => 1,
				'mobile_default' => 1,
				'selectors'      => array(
					'{{WRAPPER}} .ard-ptabs-specs' => 'grid-template-columns:repeat({{VALUE}},minmax(0,1fr));',
				),
			)
		);

		$this->add_responsive_control(
			'tab_padding',
			array(
				'label'          => 'فاصله داخلی دکمه تب',
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'range'          => array( 'px' => array( 'min' => 6, 'max' => 34 ) ),
				'default'        => array( 'unit' => 'px', 'size' => 22 ),
				'mobile_default' => array( 'unit' => 'px', 'size' => 12 ),
				'selectors'      => array(
					'{{WRAPPER}} .ard-ptabs__btn' => 'padding-inline:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'nav_scroll',
			array(
				'label'          => 'نوار تب‌ها در فضای کم',
				'type'           => Controls_Manager::SELECT,
				'default'        => 'wrap',
				'mobile_default' => 'nowrap',
				'options'        => array(
					'wrap'   => 'به خط بعد برود',
					'nowrap' => 'یک خط با اسکرول افقی',
				),
				'selectors_dictionary' => array(
					'wrap'   => 'wrap;overflow-x:visible',
					'nowrap' => 'nowrap;overflow-x:auto',
				),
				'selectors'      => array(
					'{{WRAPPER}} .ard-ptabs__nav' => 'flex-wrap:{{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$auto    = 'auto' === $s['source'];
		$ard_tag = self::tag( $s['panel_tag'], 'h3' );

		$product = $auto ? Ardal_Product::current() : null;

		if ( $auto && ! $product ) {
			$this->render_empty( 'محصولی پیدا نشد. این ویجت را روی قالب تک‌محصول بگذارید یا حالت دستی را انتخاب کنید.' );

			return;
		}

		$panels = array();

		if ( 'yes' === $s['show_specs'] ) {
			$specs = $auto ? Ardal_Product::attributes( $product ) : $this->manual_specs( $s );

			if ( ! empty( $specs ) ) {
				$panels['specs'] = array(
					'label' => $s['label_specs'],
					'data'  => $specs,
				);
			}
		}

		if ( 'yes' === $s['show_desc'] ) {
			$body = $auto ? $this->auto_description( $product ) : $s['desc_body'];

			if ( '' !== trim( wp_strip_all_tags( (string) $body ) ) ) {
				$panels['desc'] = array(
					'label' => $s['label_desc'],
					'data'  => $body,
				);
			}
		}

		if ( 'yes' === $s['show_reviews'] ) {
			$reviews = $auto ? $this->auto_reviews( $product, (int) $s['reviews_limit'] ) : $this->manual_reviews( $s );

			$panels['reviews'] = array(
				'label' => $this->reviews_label( $s, $reviews ),
				'data'  => $reviews,
			);
		}

		if ( 'yes' === $s['show_qna'] ) {
			$qna = $this->manual_qna( $s );

			if ( ! empty( $qna ) ) {
				$panels['qna'] = array(
					'label' => $s['label_qna'],
					'data'  => $qna,
				);
			}
		}

		if ( empty( $panels ) ) {
			$this->render_empty( 'هیچ تبی برای نمایش نیست.' );

			return;
		}

		$uid      = 'ard-ptabs-' . $this->get_id();
		$sections = 'sections' === $s['layout'];
		$sticky   = $sections && 'yes' === $s['nav_sticky'];
		$offset   = isset( $s['nav_offset']['size'] ) ? (int) $s['nav_offset']['size'] : 0;

		$root_class = 'ard-ptabs' . ( $sections ? ' ard-ptabs--sections' : '' ) . ( $sticky ? ' ard-ptabs--sticky-nav' : '' );
		?>
		<div
			class="<?php echo esc_attr( $root_class ); ?>"
			data-ard-product-tabs
			data-layout="<?php echo esc_attr( $sections ? 'sections' : 'tabs' ); ?>"
			style="--ard-ptabs-offset:<?php echo esc_attr( $offset ); ?>px"
		>
			<div class="ard-ptabs__nav" role="<?php echo esc_attr( $sections ? 'navigation' : 'tablist' ); ?>"<?php echo $sections ? ' aria-label="بخش‌های محصول"' : ''; ?>>
				<?php
				$i = 0;

				foreach ( $panels as $key => $panel ) :
					?>
					<?php if ( $sections ) : ?>
						<a
							class="ard-ptabs__btn<?php echo 0 === $i ? ' is-active' : ''; ?>"
							href="#<?php echo esc_attr( $uid . '-panel-' . $key ); ?>"
							data-tab-target="<?php echo esc_attr( $key ); ?>"
						><?php echo esc_html( $panel['label'] ); ?></a>
					<?php else : ?>
						<button
							type="button"
							class="ard-ptabs__btn<?php echo 0 === $i ? ' is-active' : ''; ?>"
							role="tab"
							id="<?php echo esc_attr( $uid . '-tab-' . $key ); ?>"
							aria-controls="<?php echo esc_attr( $uid . '-panel-' . $key ); ?>"
							aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
							data-tab-target="<?php echo esc_attr( $key ); ?>"
						><?php echo esc_html( $panel['label'] ); ?></button>
					<?php endif; ?>
					<?php
					$i++;
				endforeach;
				?>
			</div>

			<div class="ard-ptabs__panels">
				<?php
				$i = 0;

				foreach ( $panels as $key => $panel ) :
					?>
					<div
						class="ard-ptabs__panel<?php echo ( $sections || 0 === $i ) ? ' is-active' : ''; ?>"
						<?php echo $sections ? '' : ' role="tabpanel" aria-labelledby="' . esc_attr( $uid . '-tab-' . $key ) . '"'; ?>
						id="<?php echo esc_attr( $uid . '-panel-' . $key ); ?>"
						data-tab-panel="<?php echo esc_attr( $key ); ?>"
						<?php echo ( ! $sections && 0 !== $i ) ? ' hidden' : ''; ?>
					>
						<?php if ( $sections ) : ?>
							<<?php echo esc_html( $ard_tag ); ?> class="ard-ptabs__panel-title"><?php echo esc_html( $panel['label'] ); ?></<?php echo esc_html( $ard_tag ); ?>>
						<?php endif; ?>
						<?php $this->render_panel( $key, $panel['data'], $s, $product ); ?>
					</div>
					<?php
					$i++;
				endforeach;
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * @param string          $key
	 * @param mixed           $data
	 * @param array           $s
	 * @param WC_Product|null $product
	 */
	private function render_panel( $key, $data, $s, $product ) {
		switch ( $key ) {
			case 'specs':
				echo '<div class="ard-ptabs-specs">';

				foreach ( $data as $idx => $spec ) {
					printf(
						'<div class="ard-ptabs-specs__item%s"><span class="ard-ptabs-specs__key">%s</span><span class="ard-ptabs-specs__val">%s</span></div>',
						0 === ( $idx % 2 ) ? ' ard-ptabs-specs__item--even' : '',
						esc_html( $spec['key'] ),
						esc_html( $spec['value'] )
					);
				}

				echo '</div>';
				break;

			case 'desc':
				$this->render_description( $data, $s );
				break;

			case 'reviews':
				$this->render_reviews( $data, $s, $product );
				break;

			case 'qna':
				echo '<div class="ard-ptabs-qna"><div class="ard-ptabs-qna__list">';

				foreach ( $data as $item ) {
					printf(
						'<div class="ard-qna-card">'
						. '<div class="ard-qna-card__question"><span class="ard-qna-card__badge ard-qna-card__badge--q">س</span><span class="ard-qna-card__text">%s</span></div>'
						. '<div class="ard-qna-card__answer"><span class="ard-qna-card__badge ard-qna-card__badge--a">پ</span><span class="ard-qna-card__text">%s</span></div>'
						. '</div>',
						esc_html( $item['question'] ),
						esc_html( $item['answer'] )
					);
				}

				echo '</div></div>';
				break;
		}
	}

	/**
	 * @param string $body
	 * @param array  $s
	 */
	private function render_description( $body, $s ) {
		$images = array();

		foreach ( array( 'desc_image_1', 'desc_image_2' ) as $field ) {
			if ( ! empty( $s[ $field ]['url'] ) && false === strpos( $s[ $field ]['url'], 'elementor/assets/images/placeholder' ) ) {
				$images[] = $s[ $field ]['url'];
			}
		}
		?>
		<div class="ard-ptabs-desc">
			<div class="ard-ptabs-desc__text">
				<?php if ( '' !== trim( (string) $s['desc_title'] ) ) : ?>
					<div class="ard-ptabs-desc__title"><?php echo esc_html( $s['desc_title'] ); ?></div>
				<?php endif; ?>
				<div class="ard-ptabs-desc__body"><?php echo wp_kses_post( $body ); ?></div>
			</div>

			<?php if ( ! empty( $images ) ) : ?>
				<div class="ard-ptabs-desc__media">
					<?php foreach ( $images as $idx => $url ) : ?>
						<div class="ard-ptabs-desc__img-box<?php echo $idx > 0 ? ' ard-ptabs-desc__img-box--sm' : ''; ?>">
							<img src="<?php echo esc_url( $url ); ?>" alt="" loading="lazy" decoding="async" />
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * @param array           $reviews
	 * @param array           $s
	 * @param WC_Product|null $product
	 */
	private function render_reviews( $reviews, $s, $product ) {
		$summary = $this->rating_summary( $product, $reviews );
		?>
		<div class="ard-ptabs-reviews">
			<div class="ard-ptabs-reviews__summary">
				<div class="ard-ptabs-reviews__score-wrap">
					<div class="ard-ptabs-reviews__score"><?php echo esc_html( Ardal_Helpers::fa_digits( number_format( $summary['average'], 1 ) ) ); ?></div>
					<?php $review_total = isset( $summary['reviews'] ) ? (int) $summary['reviews'] : (int) $summary['total']; ?>
					<div class="ard-ptabs-reviews__note">
						<span class="ard-ptabs-reviews__of">از ۵</span>
						<span class="ard-ptabs-reviews__reviews">بر پایه <?php echo esc_html( Ardal_Helpers::fa_digits( $review_total ) ); ?> نظر</span>
					</div>
				</div>
				<div class="ard-ptabs-reviews__stars"><?php echo Ardal_Helpers::stars( $summary['average'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>

				<div class="ard-ptabs-reviews__bars">
					<?php foreach ( array( 5, 4, 3, 2, 1 ) as $star ) : ?>
						<?php
						$count   = isset( $summary['counts'][ $star ] ) ? (int) $summary['counts'][ $star ] : 0;
						$percent = $summary['total'] > 0 ? round( ( $count / $summary['total'] ) * 100 ) : 0;
						?>
						<div class="ard-ptabs-reviews__bar-row">
							<span class="ard-ptabs-reviews__bar-label"><?php echo esc_html( Ardal_Helpers::fa_digits( $star ) ); ?> ★</span>
							<span class="ard-ptabs-reviews__bar-track"><span class="ard-ptabs-reviews__bar-fill" style="width:<?php echo esc_attr( $percent ); ?>%"></span></span>
							<span class="ard-ptabs-reviews__bar-count"><?php echo esc_html( Ardal_Helpers::fa_digits( $count ) ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>

				<?php if ( '' !== trim( (string) $s['review_btn_text'] ) ) : ?>
					<a class="ard-ptabs-reviews__btn" href="#respond"><?php echo esc_html( $s['review_btn_text'] ); ?></a>
				<?php endif; ?>
			</div>

			<div class="ard-ptabs-reviews__list">
				<?php if ( empty( $reviews ) ) : ?>
					<p class="ard-ptabs-reviews__note">هنوز نظری برای این محصول ثبت نشده است.</p>
				<?php endif; ?>

				<?php foreach ( $reviews as $review ) : ?>
					<div class="ard-review-card">
						<div class="ard-review-card__header">
							<span class="ard-review-card__avatar"><?php echo esc_html( mb_substr( $review['name'], 0, 1 ) ); ?></span>
							<span class="ard-review-card__author"><?php echo esc_html( $review['name'] ); ?></span>
							<span class="ard-review-card__stars"><?php echo Ardal_Helpers::stars( $review['rating'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span class="ard-review-card__date"><?php echo esc_html( $review['date'] ); ?></span>
						</div>
						<div class="ard-review-card__body"><?php echo esc_html( $review['body'] ); ?></div>
					</div>
				<?php endforeach; ?>

				<?php if ( 'yes' === $s['show_review_form'] ) : ?>
					<?php $this->render_review_form( $product ); ?>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render WooCommerce's review form for the current product.
	 *
	 * The official filter is intentionally preserved for review, anti-spam and
	 * purchase-verification extensions.
	 *
	 * @param WC_Product|null $product Product being reviewed.
	 */
	private function render_review_form( $product ) {
		if ( ! $product || ! comments_open( $product->get_id() ) ) {
			return;
		}

		if ( get_option( 'woocommerce_review_rating_verification_required' ) !== 'no' && ! wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) {
			echo '<p class="ard-ptabs-reviews__verification">' . esc_html__( 'Only logged in customers who have purchased this product may leave a review.', 'woocommerce' ) . '</p>';

			return;
		}

		$commenter = wp_get_current_commenter();
		$required  = (bool) get_option( 'require_name_email', 1 );
		$fields    = array();

		foreach ( array( 'author' => array( 'Name', 'text', 'name' ), 'email' => array( 'Email', 'email', 'email' ) ) as $key => $field ) {
			$value     = 'author' === $key ? $commenter['comment_author'] : $commenter['comment_author_email'];
			$fields[ $key ] = sprintf(
				'<p class="comment-form-%1$s"><label for="%1$s">%2$s%3$s</label><input id="%1$s" name="%1$s" type="%4$s" autocomplete="%5$s" value="%6$s"%7$s /></p>',
				esc_attr( $key ),
				esc_html__( $field[0], 'woocommerce' ),
				$required ? ' <span class="required">*</span>' : '',
				esc_attr( $field[1] ),
				esc_attr( $field[2] ),
				esc_attr( $value ),
				$required ? ' required' : ''
			);
		}

		$comment_field = '';

		if ( function_exists( 'wc_review_ratings_enabled' ) && wc_review_ratings_enabled() ) {
			Ardal_Assets::enqueue_monster_rating();
			$comment_field .= $this->monster_rating_field();
			$comment_field .= '<input type="hidden" name="ardal_allow_empty_rating" value="1" />';
		}

		$comment_field .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'woocommerce' ) . ' <span class="required">*</span></label><textarea id="comment" name="comment" rows="6" required></textarea></p>';

		echo '<div class="ard-ptabs-review-form" id="respond">';
		comment_form(
			apply_filters(
				'woocommerce_product_review_comment_form_args',
				array(
					'title_reply'         => esc_html__( 'Add a review', 'woocommerce' ),
					'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'woocommerce' ),
					'title_reply_before'  => '<span id="reply-title" class="comment-reply-title" role="heading" aria-level="3">',
					'title_reply_after'   => '</span>',
					'comment_notes_after' => '',
					'label_submit'        => esc_html__( 'Submit', 'woocommerce' ),
					'logged_in_as'        => '',
					'fields'              => $fields,
					'comment_field'       => $comment_field,
				)
			),
			$product->get_id()
		);
		echo '</div>';
	}

	/**
	 * Rating UI ported from the supplied Nias theme review module. Radio inputs
	 * retain WooCommerce's required `rating` field, while the SVG is decorative.
	 *
	 * @return string
	 */
	private function monster_rating_field() {
		$required = wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '';

		$stars = [
			1 => __( '۱ ستاره', 'ns-theme' ),
			2 => __( '۲ ستاره', 'ns-theme' ),
			3 => __( '۳ ستاره', 'ns-theme' ),
			4 => __( '۴ ستاره', 'ns-theme' ),
			5 => __( '۵ ستاره', 'ns-theme' ),
		];

		ob_start();
		?>
		<fieldset class="nias-theme-rate">
			<legend class="nias-theme-rate__legend"><?php esc_html_e( 'به این محصول چه امتیازی می‌دهید؟', 'ns-theme' ); ?><?php echo wp_kses_post( $required ); ?></legend>

			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="display:none">
				<symbol id="nias-rate-star" viewBox="0 0 48 45.7"><path d="M24 37.9L9.2 45.7 12 29.1 0 17.4 16.6 15 24 0l7.4 15L48 17.4 36 29.1l2.8 16.6z"/></symbol>
				<symbol id="nias-rate-no" viewBox="0 0 44 44"><path d="M22 0C9.9 0 0 9.9 0 22s9.9 22 22 22 22-9.9 22-22S34.1 0 22 0zm16 22c0 3.3-1 6.4-2.8 9L13 8.8C15.6 7 18.7 6 22 6c8.8 0 16 7.2 16 16zM6 22c0-3.3 1-6.4 2.8-9L31 35.2C28.4 37 25.3 38 22 38c-8.8 0-16-7.2-16-16z"/></symbol>
			</svg>

			<div class="nias-theme-rate__avatar" id="nias-rate-avatar" aria-hidden="true" style="opacity:0">
				<div>
					<svg xmlns="http://www.w3.org/2000/svg" focusable="false" viewBox="0 0 350 350" width="200">
						<defs>
							<clipPath id="eye-clip"><path id="eye-clippath" class="eye-path" fill="none" d="M211 76.91c-11.42.47-23.35.71-35.63.71s-24.21-.24-35.63-.71a39.82 39.82 0 00-4.37 18.18 40.25 40.25 0 001.16 9.57c12.39-.55 25.41-.85 38.84-.85s26.45.3 38.84.85a40.25 40.25 0 001.16-9.57A39.82 39.82 0 00211 76.91z"/></clipPath>
							<clipPath id="mouth-clip"><path id="mouth-clippath" class="mouth-path" fill="none" d="M147.32 165.64h56.12a8.94 8.94 0 018.94 8.94 33.06 33.06 0 01-33.06 33.06h-7.88a33.06 33.06 0 01-33.06-33.06 8.94 8.94 0 018.94-8.94z"/></clipPath>
						</defs>
						<circle cx="175" cy="175" r="175" fill="#F8EDFF"/>
						<g id="ear-l">
							<circle cx="66.93" cy="137.42" r="40" fill="#e7cafa" stroke="#62457a" stroke-linecap="round" stroke-width="5"/>
							<path fill="#e7cafa" stroke="#62457a" stroke-linecap="round" stroke-width="5" d="M64.55 123a14.39 14.39 0 000 28.78"/>
						</g>
						<g id="ear-r">
							<circle cx="283.07" cy="137.42" r="40" fill="#e7cafa" stroke="#62457a" stroke-linecap="round" stroke-width="5"/>
							<path fill="#e7cafa" stroke="#62457a" stroke-linecap="round" stroke-width="5" d="M285.45 123a14.39 14.39 0 010 28.78"/>
						</g>
						<rect id="earring" width="12" height="30" x="284.65" y="169.64" fill="#faf18f" stroke="#62457a" stroke-linecap="round" stroke-width="4.73" rx="6"/>
						<path id="horn-2" fill="#f0a6df" stroke="#62457a" stroke-linecap="round" stroke-width="5" d="M224.58 46.46A98.05 98.05 0 01229-1.46 3.94 3.94 0 00223-6a61.34 61.34 0 00-21.44 27.72c-.57 0-2.22 12.57 5.64 20a19.76 19.76 0 0017.38 4.74z"/>
						<path id="horn-1" fill="#f0a6df" stroke="#62457a" stroke-linecap="round" stroke-width="5" d="M122.5 46.46a97.93 97.93 0 00-4.41-47.92 3.94 3.94 0 016-4.53 61.32 61.32 0 0121.43 27.72c.57 0 2.22 12.57-5.64 20a19.76 19.76 0 01-17.38 4.73z"/>
						<path fill="#A45CE0" d="M275 118.7c0-54.6-44.7-101-99.3-101.3C120.1 17 75 61.9 75 117.3v44.5c0 46-9.6 91.1-27.8 132.7C79.1 328.7 124.6 350 175 350s95.9-21.3 127.8-55.5C284.6 253 275 207.8 275 161.9v-43.2z"/>
						<path fill="none" stroke="#62457A" stroke-linecap="round" stroke-linejoin="round" stroke-width="5" d="M302.8 294.5C284.6 253 275 207.8 275 161.9v-43.2c0-54.6-44.7-101-99.3-101.3C120.1 17 75 61.9 75 117.3v44.5c0 46-9.6 91.1-27.8 132.7"/>
						<path id="horn-5" fill="#f0a6df" stroke="#62457a" stroke-linecap="round" stroke-width="5" d="M169.31-24.43l-8.77 51.28a15.57 15.57 0 0026 0l-8.77-51.28a4.29 4.29 0 00-8.46 0z"/>
						<path id="horn-4" fill="#f0a6df" stroke="#62457a" stroke-linecap="round" stroke-width="5" d="M253.74 61.74c2.54-14.44 6.9-25.16 15.5-36.94a4 4 0 00-4.52-6c-11.41 3.88-26.8 15.66-34.48 25.2a15.39 15.39 0 004.39 16 24.18 24.18 0 0013.12 6.1 5.3 5.3 0 005.99-4.36z"/>
						<path id="horn-3" fill="#f0a6df" stroke="#62457a" stroke-linecap="round" stroke-width="5" d="M93.34 61.74c-2.54-14.44-6.9-25.16-15.5-36.94a4 4 0 014.52-6c11.41 3.88 26.8 15.66 34.48 25.2a15.39 15.39 0 01-4.39 16 24.18 24.18 0 01-13.12 6.1 5.3 5.3 0 01-5.99-4.36z"/>
						<g id="nose">
							<circle cx="168.38" cy="147.81" r="3.5" fill="#62457a"/>
							<circle cx="182.38" cy="147.81" r="3.5" fill="#62457a"/>
						</g>
						<path id="chin" fill="#a45ce0" stroke="#62457a" stroke-linecap="round" stroke-linejoin="round" stroke-width="5" d="M150.38 215.64a12.59 12.59 0 0011.87 8.36h1.26a12.59 12.59 0 0011.87-8.39 12.59 12.59 0 0011.87 8.39h1.26a12.59 12.59 0 0011.87-8.39"/>
						<path id="eye-bg" class="eye-path" fill="#fff" d="M211 76.91c-11.42.47-23.35.71-35.63.71s-24.21-.24-35.63-.71a39.82 39.82 0 00-4.37 18.18 40.25 40.25 0 001.16 9.57c12.39-.55 25.41-.85 38.84-.85s26.45.3 38.84.85a40.25 40.25 0 001.16-9.57A39.82 39.82 0 00211 76.91z"/>
						<g id="eye" clip-path="url(#eye-clip)">
							<g id="pupil">
								<circle cx="175.38" cy="95.09" r="25" fill="#ace4eb"/>
								<circle cx="175.38" cy="95.09" r="15" fill="#62457a"/>
								<circle cx="168.95" cy="84.37" r="4.29" fill="#fff"/>
							</g>
						</g>
						<path id="eye-outline" class="eye-path" fill="none" stroke="#62457a" stroke-linecap="round" stroke-linejoin="round" stroke-width="5" d="M211 76.91c-11.42.47-23.35.71-35.63.71s-24.21-.24-35.63-.71a39.82 39.82 0 00-4.37 18.18 40.25 40.25 0 001.16 9.57c12.39-.55 25.41-.85 38.84-.85s26.45.3 38.84.85a40.25 40.25 0 001.16-9.57A39.82 39.82 0 00211 76.91z"/>
						<path id="mouth-bg" class="mouth-path" fill="#775196" d="M179.32 207.64h-7.88a33 33 0 01-33.06-33.06 8.94 8.94 0 018.94-8.94h56.12a8.94 8.94 0 018.94 8.94 33 33 0 01-33.06 33.06z"/>
						<g id="mouth" clip-path="url(#mouth-clip)">
							<g id="tongue">
								<circle cx="175.61" cy="204.34" r="16" fill="#e3468a"/>
								<ellipse cx="175.61" cy="191.34" fill="#fff" opacity=".1" rx="6" ry="3"/>
							</g>
							<path id="tooth-bot" fill="#fff" d="M160.95 200.64h4a4 4 0 014 4v7h-12v-7a4 4 0 014-4z"/>
							<path id="tooth-top" fill="#fff" d="M177.95 161.64h16v9a4 4 0 01-4 4h-8a4 4 0 01-4-4v-9z"/>
						</g>
						<path id="mouth-outline" class="mouth-path" fill="none" stroke="#62457a" stroke-linecap="round" stroke-linejoin="round" stroke-width="5" d="M179.32 207.64h-7.88a33 33 0 01-33.06-33.06h0a8.94 8.94 0 018.94-8.94h56.12a8.94 8.94 0 018.94 8.94h0a33 33 0 01-33.06 33.06z"/>
					</svg>
				</div>
			</div>
			<div class="nias-theme-parent-rate" >
			<input checked type="radio" name="rating" id="nias-rate_0" value="" data-num="0">
			<label for="nias-rate_0" class="nias-theme-rate__radio nias-theme-rate__radio--none">
				<svg aria-hidden="true" focusable="false"><use xlink:href="#nias-rate-no"/></svg>
				<span role="presentation"><?php esc_html_e( 'بدون امتیاز', 'ns-theme' ); ?></span>
			</label>

			<?php foreach ( $stars as $value => $label ) : ?>
				<input type="radio" name="rating" id="nias-rate_<?php echo esc_attr( $value ); ?>" value="<?php echo esc_attr( $value ); ?>" data-num="<?php echo esc_attr( $value ); ?>">
				<label for="nias-rate_<?php echo esc_attr( $value ); ?>" class="nias-theme-rate__radio">
					<svg aria-hidden="true" focusable="false"><use xlink:href="#nias-rate-star"/></svg>
					<span role="presentation"><?php echo esc_html( $label ); ?></span>
				</label>
			<?php endforeach; ?>
			</div>
		</fieldset>
		<?php
		return ob_get_clean();
	}

	/**
	 * خلاصهٔ امتیاز: میانگین، تعداد کل و شمارش هر ستاره.
	 *
	 * @param WC_Product|null $product
	 * @param array           $reviews
	 * @return array
	 */
	private function rating_summary( $product, $reviews ) {
		if ( $product ) {
			$counts = $product->get_rating_counts();
			// `total` مخرج نمودار ستاره‌هاست پس باید شمارش امتیازها باشد، ولی
			// عددی که کنار «نظر» نوشته می‌شود باید با برچسب تب یکی باشد.
			$total = (int) $product->get_rating_count();

			return array(
				'average' => (float) $product->get_average_rating(),
				'total'   => $total,
				'reviews' => (int) $product->get_review_count(),
				'counts'  => is_array( $counts ) ? $counts : array(),
			);
		}

		$counts = array();
		$sum    = 0;

		foreach ( $reviews as $review ) {
			$star = max( 1, min( 5, (int) round( $review['rating'] ) ) );

			$counts[ $star ] = isset( $counts[ $star ] ) ? $counts[ $star ] + 1 : 1;
			$sum            += $star;
		}

		$total = count( $reviews );

		return array(
			'average' => $total > 0 ? $sum / $total : 0,
			'total'   => $total,
			'counts'  => $counts,
		);
	}

	/**
	 * توضیحات محصول (توضیح بلند، وگرنه توضیح کوتاه).
	 *
	 * @param WC_Product $product
	 * @return string
	 */
	private function auto_description( $product ) {
		$body = $product->get_description();

		if ( '' === trim( wp_strip_all_tags( $body ) ) ) {
			$body = $product->get_short_description();
		}

		return apply_filters( 'the_content', $body );
	}

	/**
	 * نظرهای واقعی ووکامرس.
	 *
	 * @param WC_Product $product
	 * @param int        $limit
	 * @return array
	 */
	private function auto_reviews( $product, $limit ) {
		$comments = get_comments(
			array(
				'post_id' => $product->get_id(),
				'status'  => 'approve',
				'type'    => 'review',
				'number'  => max( 1, $limit ),
			)
		);

		$out = array();

		foreach ( $comments as $comment ) {
			$out[] = array(
				'name'   => $comment->comment_author,
				'date'   => sprintf( '%s پیش', human_time_diff( strtotime( $comment->comment_date_gmt ) ) ),
				'rating' => (float) get_comment_meta( $comment->comment_ID, 'rating', true ),
				'body'   => wp_strip_all_tags( $comment->comment_content ),
			);
		}

		return $out;
	}

	/**
	 * @param array $s
	 * @return array
	 */
	private function manual_reviews( $s ) {
		$out = array();

		foreach ( (array) $s['reviews'] as $review ) {
			if ( '' === trim( (string) $review['name'] ) ) {
				continue;
			}

			$out[] = array(
				'name'   => $review['name'],
				'date'   => (string) $review['date'],
				'rating' => isset( $review['rating']['size'] ) ? (float) $review['rating']['size'] : 5,
				'body'   => (string) $review['body'],
			);
		}

		return $out;
	}

	/**
	 * @param array $s
	 * @return array
	 */
	private function manual_specs( $s ) {
		$out = array();

		foreach ( (array) $s['specs'] as $spec ) {
			if ( '' === trim( (string) $spec['spec_key'] ) ) {
				continue;
			}

			$out[] = array(
				'key'   => $spec['spec_key'],
				'value' => $spec['spec_value'],
			);
		}

		return $out;
	}

	/**
	 * @param array $s
	 * @return array
	 */
	private function manual_qna( $s ) {
		$out = array();

		foreach ( (array) $s['qna'] as $item ) {
			if ( '' === trim( (string) $item['question'] ) ) {
				continue;
			}

			$out[] = array(
				'question' => $item['question'],
				'answer'   => (string) $item['answer'],
			);
		}

		return $out;
	}

	/**
	 * عنوان تب نظرات، با تعداد.
	 *
	 * @param array $s
	 * @param array $reviews
	 * @return string
	 */
	private function reviews_label( $s, $reviews ) {
		$label = (string) $s['label_reviews'];
		$count = count( $reviews );

		return $count > 0 ? sprintf( '%s (%s)', $label, Ardal_Helpers::fa_digits( $count ) ) : $label;
	}
}
