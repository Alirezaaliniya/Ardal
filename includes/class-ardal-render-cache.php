<?php
/** Public Elementor fragment cache. @package Ardal */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Render_Cache {
	const OPTION = 'ardal_render_cache';
	const REVISION = 'ardal_render_cache_revision';
	private static $rendering = 0;
	private static $invalidated = false;
	private static $stats = array( 'hit' => 0, 'miss' => 0, 'skip' => 0 );
	private static $skipped = array();

	public static function init() {
		add_shortcode( 'ardal-render-fragment', array( __CLASS__, 'shortcode' ) );
		add_action( 'elementor/widgets/register', array( __CLASS__, 'register_widget' ) );
		add_filter( 'elementor/frontend/builder_content_data', array( __CLASS__, 'prepare' ), 100, 2 );
		add_action( 'admin_post_ardal_render_cache_clear', array( __CLASS__, 'clear_action' ) );
		foreach ( array( 'save_post', 'deleted_post', 'set_object_terms', 'created_term', 'edited_term', 'delete_term', 'comment_post', 'edit_comment', 'deleted_comment', 'transition_comment_status', 'woocommerce_product_set_stock', 'woocommerce_variation_set_stock', 'woocommerce_scheduled_sales', 'elementor/core/files/clear_cache', 'switch_theme', 'activated_plugin', 'deactivated_plugin', 'upgrader_process_complete' ) as $hook ) {
			add_action( $hook, array( __CLASS__, 'invalidate' ), 100, 0 );
		}
		foreach ( array( 'added_post_meta', 'updated_post_meta', 'deleted_post_meta' ) as $hook ) {
			add_action( $hook, array( __CLASS__, 'meta_changed' ), 100, 4 );
		}
		add_action( 'updated_option', array( __CLASS__, 'option_changed' ), 100, 1 );
		add_action( 'added_option', array( __CLASS__, 'option_changed' ), 100, 1 );
		add_action( 'deleted_option', array( __CLASS__, 'option_changed' ), 100, 1 );
		add_action( 'shutdown', array( __CLASS__, 'report' ) );
		add_action( 'shutdown', array( __CLASS__, 'finish_invalidation' ), PHP_INT_MAX );
	}

	public static function shortcode( $atts ) {
		$payload = $atts['data'] ?? '';
		$signature = $atts['signature'] ?? '';
		if ( ! is_string( $payload ) || ! is_string( $signature ) || ! hash_equals( hash_hmac( 'sha256', $payload, wp_salt( 'auth' ) ), $signature ) ) {
			return '';
		}
		$settings = json_decode( base64_decode( $payload, true ), true );
		if ( ! is_array( $settings ) || empty( $settings['original'] ) || empty( $settings['document'] ) ) {
			return '';
		}
		ob_start();
		self::print_original( $settings['original'], absint( $settings['document'] ) );
		return ob_get_clean();
	}

	public static function defaults() {
		return array( 'enabled' => '0', 'ttl' => 15, 'types' => "loop-grid\nardal-posts\nardal-latest-posts", 'exclude_pages' => '', 'exclude_widgets' => '' );
	}

	public static function settings() {
		return wp_parse_args( (array) get_option( self::OPTION, array() ), self::defaults() );
	}

	public static function sanitize( $input ) {
		$input = is_array( $input ) ? $input : array();
		return array(
			'enabled' => empty( $input['enabled'] ) ? '0' : '1',
			'ttl' => max( 1, min( 60, absint( $input['ttl'] ?? 15 ) ) ),
			'types' => implode( "\n", array_unique( array_filter( array_map( 'sanitize_key', preg_split( '/[\s,]+/', (string) ( $input['types'] ?? '' ) ) ) ) ) ),
			'exclude_pages' => implode( ',', wp_parse_id_list( $input['exclude_pages'] ?? '' ) ),
			'exclude_widgets' => implode( ',', array_filter( array_map( 'sanitize_key', preg_split( '/[\s,]+/', (string) ( $input['exclude_widgets'] ?? '' ) ) ) ) ),
		);
	}

	public static function invalidate() {
		if ( ! self::$invalidated ) {
			update_option( self::REVISION, wp_generate_uuid4(), false );
			self::$invalidated = true;
		}
	}

	public static function finish_invalidation() {
		if ( self::$invalidated ) {
			update_option( self::REVISION, wp_generate_uuid4(), false );
		}
	}

	public static function meta_changed( $meta_id, $post_id, $key ) {
		if ( ! in_array( $key, array( '_edit_lock', '_edit_last', '_elementor_element_cache', '_elementor_css', '_elementor_page_assets' ), true ) ) {
			self::invalidate();
		}
	}

	public static function option_changed( $option ) {
		if ( 0 === strpos( $option, '_transient_' ) || 0 === strpos( $option, '_site_transient_' ) || self::REVISION === $option || 'ardal_render_cache_report' === $option ) {
			return;
		}
		$relevant = preg_match( '/^(ardal_|theme_mods_|woocommerce_|elementor_(?!pro_license))/', $option )
			|| in_array( $option, array( 'active_plugins', 'stylesheet', 'template', 'home', 'siteurl', 'permalink_structure', 'blogname', 'blogdescription', 'date_format', 'time_format', 'timezone_string', 'gmt_offset', 'WPLANG' ), true );
		if ( ! apply_filters( 'ardal/render_cache/invalidate_option', (bool) $relevant, $option ) ) {
			return;
		}
		self::invalidate();
		if ( self::OPTION === $option ) {
			self::clear_elementor_documents();
		}
	}

	public static function clear_elementor_documents() {
		// Deferred fragments belong to this plugin. Remove them when it is disabled
		// or its configuration changes, including on sites with native element caching.
		delete_post_meta_by_key( '_elementor_element_cache' );
	}

	public static function clear_action() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'دسترسی کافی ندارید.', 'ardal' ), '', array( 'response' => 403 ) );
		}
		check_admin_referer( 'ardal_render_cache_clear' );
		self::invalidate();
		wp_safe_redirect( admin_url( 'admin.php?page=ardal-settings#optimization' ) );
		exit;
	}

	private static function eligible_request() {
		if ( is_admin() || is_user_logged_in() || wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'DONOTCACHEPAGE' ) && DONOTCACHEPAGE ) ) {
			return false;
		}
		if ( 'GET' !== ( $_SERVER['REQUEST_METHOD'] ?? 'GET' ) || ! empty( $_GET ) || is_preview() || is_customize_preview() || is_search() || is_feed() || post_password_required() ) {
			return false;
		}
		if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
			return false;
		}
		foreach ( array_keys( $_COOKIE ) as $cookie ) {
			if ( preg_match( '/woocommerce|wp_woocommerce_session|wordpress_|comment_author|currency|wishlist/i', $cookie ) ) {
				return false;
			}
		}
		$plugin = \Elementor\Plugin::$instance;
		return ! $plugin->editor->is_edit_mode() && ! $plugin->preview->is_preview_mode();
	}

	/** Only reviewed public widgets and tags can be shared between visitors. */
	private static function safe_data( $data, $depth = 0 ) {
		if ( $depth > 8 ) {
			return false;
		}
		$name = $data['widgetType'] ?? '';
		$allowed = array( '', 'heading', 'image', 'text-editor', 'button', 'divider', 'spacer', 'icon', 'icon-box', 'image-box', 'loop-grid', 'theme-post-title', 'theme-post-featured-image', 'woocommerce-product-title', 'woocommerce-product-price', 'ardal-posts', 'ardal-latest-posts' );
		$allowed = apply_filters( 'ardal/render_cache/public_widgets', $allowed );
		if ( ! in_array( $name, $allowed, true ) ) {
			return false;
		}
		$settings = $data['settings'] ?? array();
		$raw = wp_json_encode( $settings );
		if ( preg_match( '/nonce|password|user[_-]|current-user|display_condition|visibility|shortcode|avoid_duplicates":"yes/i', $raw ) ) {
			return false;
		}
		$dynamic = $settings['__dynamic__'] ?? array();
		foreach ( $dynamic as $tag ) {
			preg_match( '/name="([^"]+)"/', $tag, $match );
			if ( empty( $match[1] ) || ! in_array( $match[1], array( 'post-title', 'post-url', 'post-featured-image', 'post-date', 'post-terms', 'woocommerce-product-title-tag', 'woocommerce-product-price-tag' ), true ) ) {
				return false;
			}
		}
		// User-entered shortcode content must retain its normal execution path.
		$plain = $settings;
		unset( $plain['__dynamic__'] );
		if ( preg_match( '/\[[a-zA-Z][a-zA-Z0-9_-]*(?:\s|\])/', wp_json_encode( $plain ) ) ) {
			return false;
		}
		if ( 'loop-grid' === $name ) {
			$template = absint( $settings['template_id'] ?? 0 );
			if ( ! $template || ! empty( $settings['post_query_query_id'] ) || ( ! empty( $settings['pagination_type'] ) && 'none' !== $settings['pagination_type'] ) ) {
				return false;
			}
			foreach ( $settings['alternate_templates'] ?? array() as $alternate ) {
				if ( ! empty( $alternate['template_id'] ) ) {
					return false;
				}
			}
			$items = json_decode( get_post_meta( $template, '_elementor_data', true ), true );
			if ( ! is_array( $items ) ) {
				return false;
			}
			foreach ( $items as $item ) {
				if ( ! self::safe_data( $item, $depth + 1 ) ) {
					return false;
				}
			}
		}
		foreach ( $data['elements'] ?? array() as $child ) {
			if ( ! self::safe_data( $child, $depth + 1 ) ) {
				return false;
			}
		}
		return true;
	}

	public static function register_widget( $manager ) {
		require_once ARDAL_PATH . 'includes/class-ardal-cache-widget.php';
		$manager->register( new Ardal_Cache_Widget() );
	}

	public static function prepare( $items, $document_id ) {
		$settings = self::settings();
		if ( self::$rendering || '1' !== $settings['enabled'] || ! self::eligible_request() || in_array( get_queried_object_id(), wp_parse_id_list( $settings['exclude_pages'] ), true ) ) {
			return $items;
		}
		$types = preg_split( '/\s+/', $settings['types'] );
		foreach ( $items as &$item ) {
			if ( in_array( $item['id'], preg_split( '/[\s,]+/', $settings['exclude_widgets'] ), true ) ) {
				continue;
			}
			if ( in_array( $item['widgetType'] ?? '', $types, true ) ) {
				if ( self::safe_data( $item ) ) {
					$item = array( 'id' => $item['id'], 'elType' => 'widget', 'widgetType' => 'ardal-cache-proxy', 'settings' => array( 'original' => $item, 'document' => $document_id ) );
				} else {
					self::$stats['skip']++;
					self::$skipped[ $item['widgetType'] . ':' . $item['id'] ] = 'نوع ویجت، شرط نمایش، شورت‌کد یا تنظیمات حلقه برای کش عمومی تأیید نشده است.';
				}
			} elseif ( ! empty( $item['elements'] ) ) {
				$item['elements'] = self::prepare( $item['elements'], $document_id );
			}
		}
		return $items;
	}

	private static function dependencies() {
		// Clone dependencies too: inline data can be appended to existing handles.
		return unserialize( serialize( array( 'scripts' => wp_scripts(), 'styles' => wp_styles() ) ) );
	}

	private static function asset_changes( $before ) {
		$out = array();
		foreach ( array( 'scripts' => wp_scripts(), 'styles' => wp_styles() ) as $kind => $registry ) {
			$out[ $kind ] = array( 'queue' => array_values( array_diff( $registry->queue, $before[ $kind ]->queue ) ), 'registered' => array() );
			foreach ( $registry->registered as $handle => $dependency ) {
				if ( ! isset( $before[ $kind ]->registered[ $handle ] ) || $dependency != $before[ $kind ]->registered[ $handle ] ) {
					$out[ $kind ]['registered'][ $handle ] = $dependency;
				}
			}
		}
		return $out;
	}

	private static function restore_assets( $assets ) {
		foreach ( array( 'scripts' => wp_scripts(), 'styles' => wp_styles() ) as $kind => $registry ) {
			foreach ( $assets[ $kind ]['registered'] as $handle => $dependency ) {
				if ( ! isset( $registry->registered[ $handle ] ) ) {
					$registry->registered[ $handle ] = $dependency;
				} else {
					foreach ( array( 'before', 'after' ) as $position ) {
						$existing = (array) $registry->get_data( $handle, $position );
						$stored = (array) ( $dependency->extra[ $position ] ?? array() );
						$registry->add_data( $handle, $position, array_values( array_unique( array_merge( $existing, $stored ) ) ) );
					}
					foreach ( $dependency->extra as $name => $value ) {
						if ( false === $registry->get_data( $handle, $name ) ) {
							$registry->add_data( $handle, $name, $value );
						}
					}
				}
			}
			$registry->enqueue( $assets[ $kind ]['queue'] );
		}
	}

	public static function print_original( $data, $document_id ) {
		$settings = self::settings();
		$types = preg_split( '/\s+/', $settings['types'] );
		if ( '1' !== $settings['enabled'] || ! self::eligible_request() || ! in_array( $data['widgetType'] ?? '', $types, true ) || in_array( $data['id'], preg_split( '/[\s,]+/', $settings['exclude_widgets'] ), true ) || in_array( get_queried_object_id(), wp_parse_id_list( $settings['exclude_pages'] ), true ) || ! self::safe_data( $data ) ) {
			$widget = \Elementor\Plugin::$instance->elements_manager->create_element_instance( $data );
			if ( $widget ) {
				self::$rendering++;
				try {
					$widget->print_element();
				} finally {
					self::$rendering--;
				}
			}
			return;
		}
		$revision = get_option( self::REVISION, 'initial' );
		$context = array( $revision, ARDAL_VERSION, defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '', $document_id, get_queried_object_id(), get_the_ID(), $_SERVER['REQUEST_URI'] ?? '', determine_locale(), function_exists( 'get_woocommerce_currency' ) ? get_woocommerce_currency() : '', $data );
		$key = 'ardal_fragment_' . md5( wp_json_encode( $context ) );
		$cached = get_transient( $key );
		if ( is_array( $cached ) && isset( $cached['html'], $cached['assets'] ) ) {
			self::$stats['hit']++;
			self::restore_assets( $cached['assets'] );
			echo $cached['html']; // Generated by the original widget, already escaped by Elementor.
			return;
		}
		self::$stats['miss']++;
		$before = self::dependencies();
		$level = ob_get_level();
		self::$rendering++;
		ob_start();
		try {
			$widget = \Elementor\Plugin::$instance->elements_manager->create_element_instance( $data );
			if ( $widget ) {
				$widget->print_element();
			}
			$html = ob_get_clean();
		} finally {
			self::$rendering--;
			while ( ob_get_level() > $level ) {
				ob_end_clean();
			}
		}
		$assets = self::asset_changes( $before );
		$payload = array( 'html' => $html, 'assets' => $assets );
		if ( '' !== $html && strlen( serialize( $payload ) ) < 1048576 && ! preg_match( '/nonce|<form\b|type=["\']password|wp_woocommerce_session/i', $html . wp_json_encode( $assets ) ) && $revision === get_option( self::REVISION, 'initial' ) ) {
			set_transient( $key, $payload, (int) $settings['ttl'] * MINUTE_IN_SECONDS );
		}
		echo $html; // Generated by the original widget.
	}

	public static function report() {
		if ( array_sum( self::$stats ) ) {
			set_transient( 'ardal_render_cache_report', array( 'time' => time(), 'stats' => self::$stats, 'skipped' => array_slice( self::$skipped, 0, 20, true ) ), HOUR_IN_SECONDS );
		}
	}

	public static function render_status() {
		$report = get_transient( 'ardal_render_cache_report' );
		echo '<p>کش فقط برای درخواست عمومی مهمان اجرا می‌شود. فرم‌ها، حساب کاربری، درخواست‌های تعاملی و ویجت‌های بررسی‌نشده از کش مشترک کنار گذاشته می‌شوند.</p>';
		if ( $report ) {
			printf( '<p>آخرین درخواست بررسی‌شده — استفاده از کش: %d، ساخت خروجی: %d، کنارگذاشته‌شده: %d</p>', (int) $report['stats']['hit'], (int) $report['stats']['miss'], (int) $report['stats']['skip'] );
			foreach ( $report['skipped'] ?? array() as $widget => $reason ) {
				echo '<p><code>' . esc_html( $widget ) . '</code> — ' . esc_html( $reason ) . '</p>';
			}
		}
		$url = wp_nonce_url( admin_url( 'admin-post.php?action=ardal_render_cache_clear' ), 'ardal_render_cache_clear' );
		echo '<p><a class="button" href="' . esc_url( $url ) . '">پاک‌سازی کش رندر</a></p>';
	}
}
