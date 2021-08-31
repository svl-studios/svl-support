<?php
/**
 * AJAX Functions
 *
 * @package     SVL Support
 * @author      SVL Studios
 * @copyright   Copyright (c) 2021, SVL Studios
 * @link        https://www.svlstudios.com
 * @since       SVL Support 1.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'SVL_Support_AJAX' ) ) {

	/**
	 * Class SVL_Support_AJAX
	 */
	class SVL_Support_AJAX {

		/**
		 * SVL_Support_AJAX constructor.
		 */
		public function __construct() {
			add_action( 'wp_ajax_svl_support_create_nonce', array( $this, 'nonce' ) );
			add_action( 'wp_ajax_nopriv_svl_support_create_nonce', array( $this, 'nonce' ) );

			add_action( 'wp_ajax_svl_support_create_report', array( $this, 'create_report' ) );
			add_action( 'wp_ajax_nopriv_svl_support_create_report', array( $this, 'create_report' ) );
		}


		/**
		 * Inset support data as post.
		 */
		public function create_report() {
			if ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'redux_support_token' ) ) { // phpcs:ignore
				$opt_name = sanitize_text_field( wp_unslash( $_POST['opt_name'] ?? '' ) );
				$product  = sanitize_text_field( wp_unslash( $_POST['product'] ?? '' ) );

				$post_id = wp_insert_post(
					array(
						'post_type'      => 'svl_support',
						'post_title'     => gmdate( 'Y-m-d' ) . ': ' . $product . ' - ' . $opt_name,
						'post_content'   => sanitize_text_field( wp_unslash( $_POST['data'] ?? '' ) ),
						'post_status'    => 'private',
						'comment_status' => 'closed',
						'ping_status'    => 'closed',
					)
				);

				echo esc_html( gmdate( 'Y-m-d' ) . '-' . $post_id );
			}

			die();
		}

		/**
		 * Return nonce the server will recognize.
		 */
		public function nonce() {
			if ( isset( $_POST['nonce'] ) ) { // phpcs:ignore
				$key = sanitize_text_field( wp_unslash( $_POST['nonce'] ?? '' ) ); // phpcs:ignore

				echo sanitize_key( wp_unslash( wp_create_nonce( $key ) ) );
			}

			die();
		}
	}

	// Create the class.
	new SVL_Support_AJAX();
}
