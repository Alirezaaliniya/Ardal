<?php
/**
 * فیلدهای اردال روی صفحهٔ ویرایش محصول ووکامرس.
 *
 * تنظیمات افزونه فقط تعیین می‌کند که زیرعنوان لاتین نمایش داده شود یا نه؛ خودِ
 * متن باید جایی وارد شود. این کلاس همان فیلد ورودی را می‌سازد تا نیازی به
 * افزونهٔ فیلد سفارشی نباشد.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Product_Fields {

	public static function init() {
		add_action( 'woocommerce_product_options_general_product_data', array( __CLASS__, 'render_field' ) );
		add_action( 'woocommerce_admin_process_product_object', array( __CLASS__, 'save_field' ) );

		// ویرایش سریع در فهرست محصولات.
		add_action( 'quick_edit_custom_box', array( __CLASS__, 'render_quick_edit' ), 10, 2 );
		add_action( 'manage_product_posts_custom_column', array( __CLASS__, 'print_hidden_value' ), 20, 2 );
		add_action( 'admin_footer-edit.php', array( __CLASS__, 'quick_edit_script' ) );
	}

	/**
	 * کلید متایی که زیرعنوان در آن ذخیره می‌شود.
	 *
	 * @return string
	 */
	private static function meta_key() {
		$key = trim( (string) Ardal_Palette::get( 'product_subtitle_key' ) );

		return '' !== $key ? $key : '_ardal_subtitle';
	}

	/**
	 * فیلد زیرعنوان در تب «عمومی» اطلاعات محصول.
	 */
	public static function render_field() {
		global $post;

		// این تابع فقط در صفحه‌های پیشخوان ووکامرس لود شده است؛ اگر هوک از جای
		// دیگری صدا زده شود نباید خطای مرگبار بدهد.
		if ( ! function_exists( 'woocommerce_wp_text_input' ) || ! $post ) {
			return;
		}

		$key     = self::meta_key();
		$enabled = Ardal_Product::subtitle_on();

		echo '<div class="options_group">';

		woocommerce_wp_text_input(
			array(
				'id'                => 'ardal_product_subtitle',
				'name'              => 'ardal_product_subtitle',
				'value'             => (string) get_post_meta( $post->ID, $key, true ),
				'label'             => Ardal_Brand::text( 'عنوان انگلیسی (اردال)' ),
				'placeholder'       => 'Pro-Fit Aero X2 Running Shoes — Men',
				'desc_tip'          => false,
				'description'       => $enabled
					? sprintf( 'زیر نام محصول نمایش داده می‌شود. در متای «%s» ذخیره می‌شود.', esc_html( $key ) )
					: Ardal_Brand::text( 'برای دیده‌شدن، گزینهٔ «نمایش عنوان انگلیسی محصول» را در تنظیمات اردال روشن کنید.' ),
				'custom_attributes' => array( 'dir' => 'ltr' ),
			)
		);

		echo '</div>';
	}

	/**
	 * ذخیرهٔ مقدار همراه خود محصول.
	 *
	 * @param \WC_Product $product
	 */
	public static function save_field( $product ) {
		// این هوک بعد از بررسی nonce خود ووکامرس اجرا می‌شود.
		if ( ! isset( $_POST['ardal_product_subtitle'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			return;
		}

		$value = sanitize_text_field( wp_unslash( $_POST['ardal_product_subtitle'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$key   = self::meta_key();

		if ( '' === $value ) {
			// متای خالی نگه نمی‌داریم تا محصول‌ها بی‌دلیل ردیف متا نگیرند.
			delete_post_meta( $product->get_id(), $key );

			return;
		}

		update_post_meta( $product->get_id(), $key, $value );
	}

	/**
	 * فیلد در ویرایش سریع.
	 *
	 * @param string $column
	 * @param string $post_type
	 */
	public static function render_quick_edit( $column, $post_type ) {
		if ( 'product' !== $post_type || 'name' !== $column ) {
			return;
		}
		?>
		<fieldset class="inline-edit-col-left">
			<div class="inline-edit-col">
				<label>
					<span class="title">عنوان انگلیسی</span>
					<span class="input-text-wrap">
						<input type="text" name="ardal_product_subtitle" class="ardal-subtitle" value="" dir="ltr" />
					</span>
				</label>
			</div>
		</fieldset>
		<?php
	}

	/**
	 * مقدار فعلی را در ستون فهرست پنهان می‌کند تا ویرایش سریع بتواند بخواندش.
	 *
	 * @param string $column
	 * @param int    $post_id
	 */
	public static function print_hidden_value( $column, $post_id ) {
		if ( 'name' !== $column ) {
			return;
		}

		printf(
			'<div class="hidden" id="ardal-subtitle-%d">%s</div>',
			(int) $post_id,
			esc_html( (string) get_post_meta( $post_id, self::meta_key(), true ) )
		);
	}

	/**
	 * ویرایش سریع مقدار فعلی را خودش پر نمی‌کند؛ آن را از سلول پنهانِ همان
	 * ردیف برمی‌داریم. آن‌قدر کوچک است که فایل دارایی جدا نمی‌خواهد.
	 */
	public static function quick_edit_script() {
		$screen = get_current_screen();

		if ( ! $screen || 'edit-product' !== $screen->id ) {
			return;
		}
		?>
		<script>
		( function () {
			if ( ! window.inlineEditPost || ! window.jQuery ) {
				return;
			}

			var original = window.inlineEditPost.edit;

			window.inlineEditPost.edit = function ( id ) {
				var out = original.apply( this, arguments );
				var postId = typeof id === 'object' ? this.getId( id ) : id;

				if ( ! postId ) {
					return out;
				}

				var source = document.getElementById( 'ardal-subtitle-' + postId );
				var field = document.querySelector( '#edit-' + postId + ' input.ardal-subtitle' );

				if ( source && field ) {
					field.value = source.textContent.trim();
				}

				return out;
			};
		}() );
		</script>
		<?php
	}
}
