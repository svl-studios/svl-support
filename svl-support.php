<?php
/**
 * SVL Support
 *
 * Plugin Name:         SVL Support
 * Description:         Support data post type.
 * Author:              Kevin Provance d/b/a SVL Studios
 * Author URI:          https://www.svlstudios.com
 * Requires at least:   5.3
 * Tested up to:        5.8
 * Requires PHP:        7.4
 * Version:             1.0.0
 * Text Domain:         svl-support
 * License:             GPL3+
 * License URI:         http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package             SVL Support
 * @author              SVL Studios <support@svlstudios.com>
 * @copyright           2021, SVL Studios
 */

defined( 'ABSPATH' ) || exit;

// Require the main plugin class.
require_once plugin_dir_path( __FILE__ ) . 'class-svl-support-core.php';

// Register hooks that are fired when the plugin is activated and deactivated, respectively.
register_activation_hook( __FILE__, array( 'SVL_Support_Core', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'SVL_Support_Core', 'deactivate' ) );

// Set the plugin version.
SVL_Support_Core::$version = '1.0.0';

// Set this to true to work with non-minfied assets (JS, CSS).
SVL_Support_Core::$dev_mode = false;

// Create the instance.
SVL_Support_Core::instance();
