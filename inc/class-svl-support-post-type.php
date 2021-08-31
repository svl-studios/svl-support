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
			//add_action( 'init', array( $this, 'register' ) );
			add_action( 'save_post', array( $this, 'save_meta' ), 1, 2 );
			add_filter( 'manage_svl_support_posts_columns', array( $this, 'edit_column' ) );
			add_action( 'manage_svl_support_posts_custom_column', array( $this, 'custom_column' ), 10, 2 );

			$this->register();
		}

		/**
		 * Add custom columns.
		 *
		 * @param array $columns Column array.
		 */
		public function edit_column( array $columns ): array {
			$columns['status'] = __( 'Status', 'svl-support' );
			$columns['id']     = __( 'ID', 'svl-support' );

			return $columns;
		}

		/**
		 * Add data to custom column.
		 *
		 * @param string     $column  Column name.
		 * @param string|int $post_id Post ID.
		 */
		public function custom_column( string $column, $post_id ) {
			if ( 'status' === $column ) {
				$value = get_post_meta( $post_id, 'status', true );

				if ( '' === $value ) {
					$value = esc_html__( 'Open', 'svl-support' );
				}

				echo esc_html( ucfirst( $value ) );
			}

			if ( 'id' === $column ) {
				echo esc_html( gmdate( 'Y-m-d' ) . '-' . $post_id );
			}
		}

		/**
		 * Register post type.
		 */
		public function register() {
			register_post_type(
				'svl_support',
				array(
					'labels'               => array(
						'name'               => esc_html__( 'Support', 'svl-support' ),
						'singular_name'      => esc_html__( 'Support', 'svl-support' ),
						'search_items'       => esc_html__( 'Search Support Items', 'svl-support' ),
						'all_items'          => esc_html__( 'Support', 'svl-support' ),
						'parent_item'        => esc_html__( 'Parent Support Item', 'svl-support' ),
						'edit_item'          => esc_html__( 'Edit Support Item', 'svl-support' ),
						'update_item'        => esc_html__( 'Update Support Item', 'svl-support' ),
						'add_new_item'       => esc_html__( 'Add New Support Item', 'svl-support' ),
						'not_found'          => esc_html__( 'No support items found.', 'svl-support' ),
						'not_found_in_trash' => esc_html__( 'No support items found in trash.', 'svl-support' ),
					),
					'public'               => true,
					'menu_icon'            => 'dashicons-tickets-alt',
					'query_var'            => false,
					'exclude_from_search'  => true,
					'publicly_queryable'   => true,
					'show_in_nav_menus'    => false,
					'show_ui'              => true,
					'hierarchical'         => false,
					'menu_position'        => 18,
					'supports'             => array( 'title', 'editor' ),
					'register_meta_box_cb' => array( $this, 'add_metabox' ),
					'map_meta_cap'         => true,
					'rewrite'              => false,
				)
			);
		}

		/**
		 * Adds a metabox to the right side of the screen under the Publish box.
		 */
		public function add_metabox() {
			add_meta_box(
				'svl_status',
				'Support Data Status',
				array( $this, 'status' ),
				'svl_support',
				'side'
			);
		}

		/**
		 * Save the metabox data.
		 *
		 * @param string|int $post_id Post ID.
		 * @param WP_Post    $post    Post object.
		 *
		 * @return int|string|void
		 */
		public function save_meta( $post_id, WP_Post $post ) {

			// Return if the user doesn't have edit permissions.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}

			// Verify this came from the screen and with proper authorization,
			// because save_post can be triggered at other times.
			if ( ! isset( $_POST['status'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['svl_status_field'] ?? '' ) ), basename( __FILE__ ) ) ) {
				return $post_id;
			}

			// Now that we're authenticated, time to save the data.
			// This sanitizes the data from the field and saves it into an array $events_meta.
			$status_meta['status'] = sanitize_text_field( wp_unslash( $_POST['status'] ) );

			// Cycle through the $events_meta array.
			// Note, in this example we just have one item, but this is helpful if you have multiple.
			foreach ( $status_meta as $key => $value ) {

				// Don't store custom data twice.
				if ( 'revision' === $post->post_type ) {
					return;
				}

				if ( get_post_meta( $post_id, $key ) ) {

					// If the custom field already has a value, update it.
					update_post_meta( $post_id, $key, $value );
				} else {

					// If the custom field doesn't have a value, add it.
					add_post_meta( $post_id, $key, $value );
				}

				if ( ! $value ) {

					// Delete the meta key if there's no value.
					delete_post_meta( $post_id, $key );
				}
			}
		}

		/**
		 * Output the HTML for the metabox.
		 */
		public function status() {
			global $post;

			// Nonce field to validate form request came from current site.
			wp_nonce_field( basename( __FILE__ ), 'svl_status_field' );

			// Get the location data if it's already been entered.
			$location = get_post_meta( $post->ID, 'status', true );

			// Output the field.
			echo '<select id="svl-support-status" name="status" value="' . esc_textarea( $location ) . '" class="widefat">';
			echo '<option value="open" ' . selected( $location, 'open', false ) . '>' . esc_html__( 'Open', 'svl-support' ) . '</option>';
			echo '<option value="closed" ' . selected( $location, 'closed', false ) . '>' . esc_html__( 'Closed', 'svl-support' ) . '</option>';
			echo '</select>';

		}
	}

	// Create the class.
	new SVL_Support_Post_Type();
}
