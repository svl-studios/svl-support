<?php
/**
 * Post Type Functions
 *
 * @package     SVL Support
 * @author      SVL Studios
 * @copyright   Copyright (c) 2021, SVL Studios
 * @link        https://www.svlstudios.com
 * @since       SVL Support 1.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'SVL_Support_Post_Type' ) ) {

	/**
	 * Class SVL_Support_Post_Type
	 */
	class SVL_Support_Post_Type {

		/**
		 * SVL_Support_Post_Type constructor.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'register' ) );
		}

		/**
		 * Register post type.
		 */
		public function register() {
			register_post_type(
				'svl-support',
				array(
					'labels'              => array(
						'name'          => esc_html__( 'Support', 'svl-support' ),
						'singular_name' => esc_html__( 'Support', 'svl-support' ),
						'search_items'  => esc_html__( 'Search Support Items', 'svl-support' ),
						'all_items'     => esc_html__( 'Support', 'svl-support' ),
						'parent_item'   => esc_html__( 'Parent Support Item', 'svl-support' ),
						'edit_item'     => esc_html__( 'Edit Support Item', 'svl-support' ),
						'update_item'   => esc_html__( 'Update Support Item', 'svl-support' ),
						'add_new_item'  => esc_html__( 'Add New Support Item', 'svl-support' ),
						'not_found'     => esc_html__( 'No support items found.', 'svl-support' ),
					),
					'public'              => true,
					'menu_icon'           => 'dashicons-tickets-alt',
					'query_var'           => false,
					'exclude_from_search' => true,
					'publicly_queryable'  => true,
					'show_in_nav_menus'   => false,
					'show_ui'             => true,
					'hierarchical'        => false,
					'menu_position'       => 18,
					'supports'            => array( 'title', 'editor' ),
				)
			);
		}
	}

	// Create the class.
	new SVL_Support_Post_Type();
}
