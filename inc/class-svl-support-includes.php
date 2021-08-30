<?php
/**
 * Core Include Functions
 *
 * @package     SVL Support
 * @author      SVL Studios
 * @copyright   Copyright (c) 2021, SVL Studios
 * @link        https://www.svlstudios.com
 * @since       SVL Support 1.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'SVL_Support_Includes' ) ) {

	/**
	 * Class SVL_Support_Includes
	 */
	class SVL_Support_Includes {

		/**
		 * SVL_Support_Includes constructor.
		 */
		public function __construct() {

			// Enqueue scripts for the admin screen.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}

		/**
		 * Enqueue scripts for admin panels.
		 */
		public function admin_enqueue_scripts() {
			$min = '';

			// Set the min string if dev mode is active.
			if ( false === SVL_Support_Core::$dev_mode ) {
				$min = '.min';
			}

			// Plugin specific JavaScript.
			wp_enqueue_script(
				'svl-support',
				SVL_Support_Core::$url . 'assets/js/svl-support' . $min . '.js',
				array( 'jquery' ),
				SVL_Support_Core::$version,
				true
			);

			// Inject localized data for AJAX calls.
			wp_localize_script(
				'svl-support',
				'svlSupport',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ), // URL to the admin-ajax.php.
					'nonce'   => wp_create_nonce( 'svl_support_nonce' ), // Generate nonce for security.
				)
			);

			// Plugin specific stylesheet.
			wp_enqueue_style(
				'svl-support',
				SVL_Support_Core::$url . 'assets/css/svl-support' . $min . '.css',
				array(),
				SVL_Support_Core::$version
			);
		}
	}

	/**
	 * Initiate class on theme_setup hook.
	 *
	 * @noinspection PhpUnused*/
	function svl_support_init_load_modules() {
		global $svl_support_includes;

		$svl_support_includes = new SVL_Support_Includes();
	}

	add_action( 'setup_theme', 'svl_support_init_load_modules' );
}
