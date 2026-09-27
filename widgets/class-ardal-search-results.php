<?php
/**
 * ویجت نتیجه‌های جست‌وجو.
 *
 * شبکهٔ کامل نتیجه‌ها برای صفحهٔ جست‌وجو، با دکمهٔ «موارد بیشتر». عبارت را
 * از ?s= همان صفحه می‌گیرد، مگر اینکه دستی نوشته شود.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

class Ardal_Widget_Search_Results extends Ardal_Widget_Base {

	public function get_name() {
		return 'ardal-search-results';
	}

	public function get_title() {
		return 'اردال — نتیجه‌های جست‌وجو';
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_keywords() {
		return array( 'اردال', 'جستجو', 'نتیجه', 'search', 'results' );
	}

	public function get_style_depends() {
		return array( 'ardal', 'ardal-search' );
	}

	public function get_script_depends() {
		return array( 'ardal-search-results' );
	}

	protected function register_controls() {

		/* ------------------------------------------------ محتوا */

		$this->start_controls_section(
			'section_content',
			array( 'label' => 'نتیجه‌ها' )
		);

		$this->add_control(
			'term',
			array(
				'label'       => 'عبارت',
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => 'خالی = عبارتِ همین صفحه (‎?s=). برای پیش‌نمایش در ویرایشگر می‌توانید چیزی بنویسید.',
			)
		);

		$this->add_control(
			'per_page',
			array(
				'label'   => 'تعداد در هر بار',
				'type'    => Controls_Manager::NUMBER,
				'min'     => 2,
				'max'     => 48,
				'default' => 12,
			)
		);

		$this->add_control(
			'show_head',
			array(
				'label'        => 'سربرگ شمارش',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'head_text',
			array(
				'label'       => 'متن سربرگ',
				'type'        => Controls_Manager::TEXT,
				'default'     => '%1$s نتیجه برای «%2$s»',
				'label_block' => true,
				'description' => '%1$s جای تعداد و %2$s جای عبارت می‌نشیند.',
				'condition'   => array( 'show_head' => 'yes' ),
			)
		);

		$this->add_control(
			'empty_text',
			array(
				'label'       => 'متن وقتی چیزی پیدا نشد',
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'default'     => 'برای این عبارت چیزی پیدا نشد. املای واژه را بررسی کنید یا عبارت کوتاه‌تری بنویسید.',
				'label_block' => true,
			)
		);

		$this->add_control(
			'show_more',
			array(
				'label'        => 'دکمه «موارد بیشتر»',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'more_text',
			array(
				'label'     => 'متن دکمه',
				'type'      => Controls_Manager::TEXT,
				'default'   => 'موارد بیشتر',
				'condition' => array( 'show_more' => 'yes' ),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ کارت */

		$this->start_controls_section(
			'section_card',
			array( 'label' => 'کارت محصول' )
		);

		$this->add_tag_control( 'card_title_tag', 'تگ عنوان کارت', 'h3' );

		$this->add_control(
			'card_lines',
			array(
				'label'      => 'تعداد خط عنوان',
				'type'       => Controls_Manager::SLIDER,
				'range'      => array( 'px' => array( 'min' => 1, 'max' => 3, 'step' => 1 ) ),
				'default'    => array( 'size' => 2 ),
				'selectors'  => array(
					'{{WRAPPER}} .ard-pcard' => '--ard-pcard-lines:{{SIZE}};',
				),
			)
		);

		$this->add_media_fit_control( '{{WRAPPER}} .ard-pcard__img' );

		$this->add_control(
			'show_rating',
			array(
				'label'        => 'امتیاز',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_add',
			array(
				'label'        => 'دکمه افزودن به سبد',
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------ چیدمان */

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => 'چیدمان',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'cols',
			array(
				'label'     => 'ستون‌ها — دسکتاپ',
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 1, 'max' => 6, 'step' => 1 ) ),
				'default'   => array( 'size' => 4 ),
				'selectors' => array( '{{WRAPPER}} .ard-sr' => '--ard-sr-cols:{{SIZE}};' ),
			)
		);

		$this->add_control(
			'cols_tablet',
			array(
				'label'     => 'ستون‌ها — تبلت',
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 1, 'max' => 5, 'step' => 1 ) ),
				'default'   => array( 'size' => 3 ),
				'selectors' => array( '{{WRAPPER}} .ard-sr' => '--ard-sr-cols-t:{{SIZE}};' ),
			)
		);

		$this->add_control(
			'cols_mobile',
			array(
				'label'     => 'ستون‌ها — موبایل',
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'min' => 1, 'max' => 3, 'step' => 1 ) ),
				'default'   => array( 'size' => 2 ),
				'selectors' => array( '{{WRAPPER}} .ard-sr' => '--ard-sr-cols-m:{{SIZE}};' ),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => 'فاصله',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 4, 'max' => 40 ) ),
				'selectors'  => array( '{{WRAPPER}} .ard-sr' => '--ard-sr-gap:{{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		if ( $this->require_woo() ) {
			return;
		}

		$s    = $this->get_settings_for_display();
		$term = $this->term( $s );

		if ( '' === $term ) {
			$this->render_empty( 'هنوز عبارتی برای جست‌وجو نیست. این ویجت عبارت را از نشانی صفحه (‎?s=) می‌خواند؛ برای دیدن پیش‌نمایش، فیلد «عبارت» را پر کنید.' );

			return;
		}

		$per   = max( 2, min( 48, (int) $s['per_page'] ) );
		$page  = $this->page_number();
		$found = Ardal_Search::page( $term, $per, ( $page - 1 ) * $per );
		$total = (int) $found['total'];
		$shown = $page * $per;

		$opts = array(
			'title_tag'   => $s['card_title_tag'],
			'show_rating' => 'yes' === $s['show_rating'],
			'show_add'    => 'yes' === $s['show_add'],
			// شبکه‌ای از کارت‌هاست، پس ردیف‌های خالی باید هم‌تراز بمانند.
			'reserve_old' => true,
		);
		?>
		<div class="ard-sr"
			data-ard-search-results
			data-term="<?php echo esc_attr( $term ); ?>"
			data-per="<?php echo esc_attr( $per ); ?>"
			data-total="<?php echo esc_attr( $total ); ?>"
			data-opts="<?php echo esc_attr( wp_json_encode( $opts ) ); ?>">

			<?php if ( 'yes' === $s['show_head'] && $total > 0 ) : ?>
				<p class="ard-sr__head">
					<?php
					printf(
						// متن از تنظیمات می‌آید، پس هر دو جایگزین جدا فرار داده می‌شوند.
						esc_html( (string) $s['head_text'] ),
						'<b>' . esc_html( Ardal_Helpers::fa_digits( $total ) ) . '</b>',
						'<b>' . esc_html( $term ) . '</b>'
					);
					?>
				</p>
			<?php endif; ?>

			<?php if ( ! $found['products'] ) : ?>
				<p class="ard-sr__empty"><?php echo esc_html( $s['empty_text'] ); ?></p>
			<?php else : ?>
				<div class="ard-sr__grid" data-ard-sr-grid>
					<?php foreach ( $found['products'] as $product ) : ?>
						<?php $this->render_product_card( Ardal_Helpers::product_to_card( $product ), $opts ); ?>
					<?php endforeach; ?>
				</div>

				<?php if ( 'yes' === $s['show_more'] && $total > $shown ) : ?>
					<?php
					/*
					 * پیوند است، نه دکمه.
					 *
					 * بدون جاوااسکریپت کاربر به صفحهٔ بعد می‌رود و نتیجه‌ها را
					 * می‌بیند؛ با جاوااسکریپت همان نشانی fetch می‌شود و شبکهٔ
					 * همان صفحه به پایینِ این یکی می‌چسبد. این‌طور مارک‌آپ کارت
					 * فقط یک‌جا ساخته می‌شود و نسخهٔ دومی برای ایجکس نمی‌ماند
					 * که از این یکی عقب بیفتد.
					 */
					?>
					<a class="ard-sr__more" data-ard-sr-more href="<?php echo esc_url( $this->page_url( $page + 1 ) ); ?>"><?php echo esc_html( $s['more_text'] ); ?></a>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * شمارهٔ صفحهٔ جاری نتیجه‌ها.
	 *
	 * از پارامتر خودمان می‌آید نه paged، تا با صفحه‌بندی خودِ وردپرس روی همان
	 * صفحه قاطی نشود.
	 *
	 * @return int
	 */
	private function page_number() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- خواندن شمارهٔ صفحه، نه یک کنش.
		$page = isset( $_GET['ard_page'] ) ? absint( wp_unslash( $_GET['ard_page'] ) ) : 1;

		return max( 1, $page );
	}

	/**
	 * نشانی همین صفحه با شمارهٔ صفحهٔ دیگر.
	 *
	 * @param int $page
	 * @return string
	 */
	private function page_url( $page ) {
		$base = remove_query_arg( 'ard_page' );

		return add_query_arg( 'ard_page', max( 1, (int) $page ), $base );
	}

	/**
	 * عبارت جست‌وجو: تنظیمات، وگرنه نشانی صفحه.
	 *
	 * @param array $s
	 * @return string
	 */
	private function term( $s ) {
		$given = trim( (string) $s['term'] );

		if ( '' !== $given ) {
			return $given;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- خواندن عبارتِ جست‌وجوی صفحه، نه یک کنش.
		if ( isset( $_GET['s'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return trim( sanitize_text_field( wp_unslash( $_GET['s'] ) ) );
		}

		return trim( (string) get_search_query() );
	}
}
