<?php
/** Internal render adapter. Never stored in Elementor documents. @package Ardal */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ardal_Cache_Widget extends \Elementor\Widget_Base {
	public function get_name() {
		return 'ardal-cache-proxy';
	}

	public function get_title() {
		return 'Ardal Render Cache';
	}

	public function show_in_panel() {
		return false;
	}

	public function print_element() {
		$settings = $this->get_data( 'settings' );
		if ( isset( $settings['original'], $settings['document'] ) ) {
			// Let Elementor cache a deferred fragment, never a visitor's final HTML.
			if ( apply_filters( 'elementor/element/should_render_shortcode', false ) ) {
				$payload = base64_encode( wp_json_encode( $settings ) );
				$signature = hash_hmac( 'sha256', $payload, wp_salt( 'auth' ) );
				echo '[ardal-render-fragment data="' . esc_attr( $payload ) . '" signature="' . esc_attr( $signature ) . '"]';
				return;
			}
			Ardal_Render_Cache::print_original( $settings['original'], $settings['document'] );
		}
	}
}
