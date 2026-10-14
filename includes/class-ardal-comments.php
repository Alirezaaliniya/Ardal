<?php
/**
 * Front-end comment submission and reactions for Ardal's post comments widget.
 *
 * @package Ardal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Ardal_Comments {
	const NONCE_ACTION = 'ardal_post_comments';

	public static function init() {
		add_action( 'wp_ajax_ardal_comment_submit', array( __CLASS__, 'submit' ) );
		add_action( 'wp_ajax_nopriv_ardal_comment_submit', array( __CLASS__, 'submit' ) );
		add_action( 'wp_ajax_ardal_comment_react', array( __CLASS__, 'react' ) );
		add_action( 'wp_ajax_nopriv_ardal_comment_react', array( __CLASS__, 'react' ) );
	}

	public static function submit() {
		check_ajax_referer( self::NONCE_ACTION, 'nonce' );
		if ( get_option( 'comment_registration' ) && ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => 'برای ثبت دیدگاه وارد حساب کاربری خود شوید.' ), 403 );
		}
		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
		$post    = get_post( $post_id );
		if ( ! $post || 'post' !== $post->post_type || ( 'publish' !== $post->post_status && ! current_user_can( 'read_post', $post_id ) ) || ! comments_open( $post_id ) ) {
			wp_send_json_error( array( 'message' => 'ارسال دیدگاه برای این نوشته ممکن نیست.' ), 400 );
		}
		if ( ! empty( $_POST['website'] ) ) {
			wp_send_json_error( array( 'message' => 'درخواست معتبر نیست.' ), 400 );
		}

		$parent_id = isset( $_POST['parent_id'] ) ? absint( $_POST['parent_id'] ) : 0;
		if ( $parent_id ) {
			$parent = get_comment( $parent_id );
			if ( ! $parent || (int) $parent->comment_post_ID !== $post_id || '1' !== $parent->comment_approved ) {
				wp_send_json_error( array( 'message' => 'دیدگاه انتخاب‌شده برای پاسخ معتبر نیست.' ), 400 );
			}
		}

		$user = wp_get_current_user();
		$name = trim( sanitize_text_field( ( isset( $_POST['first_name'] ) ? wp_unslash( $_POST['first_name'] ) : '' ) . ' ' . ( isset( $_POST['last_name'] ) ? wp_unslash( $_POST['last_name'] ) : '' ) ) );
		if ( is_user_logged_in() ) {
			$name  = $user->display_name;
			$email = $user->user_email;
		} else {
			$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
			if ( empty( $name ) ) {
				wp_send_json_error( array( 'message' => 'نام و نام خانوادگی را وارد کنید.' ), 400 );
			}
			if ( get_option( 'require_name_email' ) && ( empty( $email ) || ! is_email( $email ) ) ) {
				wp_send_json_error( array( 'message' => 'ایمیل معتبر وارد کنید.' ), 400 );
			}
			if ( '' !== $email && ! is_email( $email ) ) {
				wp_send_json_error( array( 'message' => 'ایمیل معتبر وارد کنید.' ), 400 );
			}
		}

		$content = isset( $_POST['content'] ) ? trim( wp_kses_post( wp_unslash( $_POST['content'] ) ) ) : '';
		if ( '' === trim( wp_strip_all_tags( $content ) ) ) {
			wp_send_json_error( array( 'message' => 'متن دیدگاه را بنویسید.' ), 400 );
		}
		$phone = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
		$data  = array(
			'comment_post_ID'      => $post_id,
			'comment_parent'       => $parent_id,
			'comment_author'       => $name,
			'comment_author_email' => $email,
			'comment_author_url'   => '',
			'comment_content'      => $content,
			'comment_type'         => '',
			'user_id'              => get_current_user_id(),
			'comment_author_IP'    => isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '',
			'comment_agent'        => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '',
		);
		$comment_id = wp_new_comment( wp_slash( $data ), true );
		if ( is_wp_error( $comment_id ) ) {
			wp_send_json_error( array( 'message' => $comment_id->get_error_message() ), 400 );
		}
		if ( '' !== $phone ) {
			update_comment_meta( $comment_id, 'ardal_author_phone', $phone );
		}
		$comment = get_comment( $comment_id );
		$message = '1' === $comment->comment_approved ? 'دیدگاه شما ثبت شد.' : 'دیدگاه شما ثبت شد و پس از تأیید نمایش داده می‌شود.';
		wp_send_json_success( array( 'message' => $message, 'approved' => '1' === $comment->comment_approved ) );
	}

	public static function react() {
		check_ajax_referer( self::NONCE_ACTION, 'nonce' );
		$comment_id = isset( $_POST['comment_id'] ) ? absint( $_POST['comment_id'] ) : 0;
		$comment    = get_comment( $comment_id );
		$type       = isset( $_POST['type'] ) ? sanitize_key( wp_unslash( $_POST['type'] ) ) : '';
		$visitor    = isset( $_POST['visitor'] ) ? sanitize_text_field( wp_unslash( $_POST['visitor'] ) ) : '';
		if ( ! $comment || '1' !== $comment->comment_approved || ! in_array( $type, array( 'like', 'dislike' ), true ) || ( ! is_user_logged_in() && ! preg_match( '/^[a-zA-Z0-9_-]{20,80}$/', $visitor ) ) ) {
			wp_send_json_error( array( 'message' => 'درخواست معتبر نیست.' ), 400 );
		}
		$identity = is_user_logged_in() ? 'user:' . get_current_user_id() : 'visitor:' . $visitor;
		$key      = hash_hmac( 'sha256', $identity, wp_salt( 'auth' ) );
		$votes    = get_comment_meta( $comment_id, '_ardal_comment_votes', true );
		$votes    = is_array( $votes ) ? $votes : array();
		$previous = isset( $votes[ $key ] ) ? $votes[ $key ] : '';
		if ( $previous === $type ) {
			unset( $votes[ $key ] );
			$active = '';
		} else {
			$votes[ $key ] = $type;
			$active        = $type;
		}
		update_comment_meta( $comment_id, '_ardal_comment_votes', $votes );
		$likes    = count( array_filter( $votes, static function ( $vote ) { return 'like' === $vote; } ) );
		$dislikes = count( array_filter( $votes, static function ( $vote ) { return 'dislike' === $vote; } ) );
		wp_send_json_success( array( 'likes' => $likes, 'dislikes' => $dislikes, 'active' => $active ) );
	}
}
