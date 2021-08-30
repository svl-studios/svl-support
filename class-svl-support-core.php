<?php
/**
 * Core Loader Class
 *
 * @package     Trial Kev
 * @author      SVL Studios
 * @copyright   Copyright (c) 2021, SVL Studios
 * @link        https://www.svlstudios.com
 * @since       Trial Kev 1.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'SVL_Support_Core' ) ) {

	/**
	 * Class Requite_Core
	 */
	class SVL_Support_Core {

		/**
		 * Class instance.
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Plugin version.
		 *
		 * @var stringt
		 */
		public static $version = '';

		/**
		 * Plugin directory.
		 *
		 * @var string
		 */
		public static $dir = '';

		/**
		 * Plugin URL.
		 *
		 * @var string
		 */
		public static $url = '';

		/**
		 * Sets dev_mode for minified or non minified assets.
		 *
		 * @var bool
		 */
		public static $dev_mode = false;

		/**
		 * Create class instance.
		 *
		 * @return SVL_Support_Core
		 */
		public static function instance(): ?SVL_Support_Core {
			if ( ! self::$instance ) {
				self::$instance = new self();

				// Set the plugin directory.
				self::$dir = plugin_dir_path( __FILE__ );

				// Set the plugin URL.
				self::$url = plugin_dir_url( __FILE__ );

				// Run inclues.
				self::$instance->includes();

				// Set hooks.
				self::$instance->hooks();
			}

			return self::$instance;
		}

		/**
		 * Include files.
		 */
		public function includes() {
			require_once self::$dir . 'inc/class-svl-support-includes.php';
			require_once self::$dir . 'inc/class-svl-support-post-type.php';

			// Load textdomain.
			load_plugin_textdomain( 'svl-support', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Run hooks.
		 */
		private function hooks() {

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( 'svl/support/plugin/hooks', $this );
		}

		/**
		 * Plugin activate hook.
		 *
		 * @param bool $network_wide Is network wide.
		 */
		public static function activate( bool $network_wide ) {
			// Nothing to do here.
		}

		/**
		 * Plugin deacvtivate hook.
		 *
		 * @param bool $network_wide Is network wide.
		 */
		public static function deactivate( bool $network_wide ) {
			// Nothing to do here.
		}
	}
}
