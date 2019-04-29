<?php

namespace WP_Plugin_Name\Common\Utilities;

use DateTime;
use DateTimeZone;
use Exception;
use WP_Customize_Setting;
use WP_Plugin_Name as NS;
use WP_Plugin_Name\Customizer\Customizer as Customizer;
use WP_Post;
use WP_Query;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Posts::class ) ) {
	/**
	 * The functionality shared between the admin and public-facing areas of the plugin.
	 *
	 * Useful for things like utilities or hooking into something that affects both back-end and front-end.
	 */
	class Posts {

		/**
		 * Get the Post ID from the current page or from a passed integer or WP_Post object.
		 *
		 * Helper function for getting Post ID. Accepts null or a Post ID. If no $post object exists, returns false.
		 *
		 * @param null|int|WP_Post $post
		 *
		 * @return int|false
		 */
		public function post_id_helper( $post = null ) {
			if (
				! is_null( $post )
				&& is_numeric( $post )
				&& absint( $post ) > 0
			) {
				return (int) $post;
			} elseif (
				is_object( $post )
				&& ! empty( $post->ID )
			) {
				return (int) $post->ID;
			} else {
				if (
					! empty( $GLOBALS['post'] )
					&& $GLOBALS['post'] instanceof WP_Post
				) {
					return get_the_ID();
				} else {
					return false;
				}
			}
		}

		/**
		 * Get all of the "Post" post type's Post IDs that the currently logged-in user has authored.
		 *
		 * @return false|array False if user is not logged-in. Array (may be empty) if user is logged-in.
		 */
		public function get_all_current_author_post_ids() {
			$current_user = wp_get_current_user();

			// User is not logged-in
			if ( empty( $current_user ) ) {
				return false;
			}

			$args = [
				'fields'         => 'ids',
				'posts_per_page' => - 1,
				'post_type'      => 'post',
				'author'         => $current_user->ID
			];

			return ( new WP_Query( $args ) )->get_posts();
		}

		/**
		 * Get all of the "Post" post type's Post IDs.
		 *
		 * @return array
		 */
		public function get_all_post_ids() {
			$args = [
				'fields'         => 'ids',
				'posts_per_page' => - 1,
				'post_type'      => 'post',
			];

			return ( new WP_Query( $args ) )->get_posts();
		}

		/**
		 * Get the public Post Types, alpha-sorted by their labels.
		 *
		 * @see get_post_types()
		 *
		 * @return array
		 */
		public function get_public_post_types() {
			$result = get_post_types( [ 'public' => true ], 'object' );

			uasort(
				$result, function ( $a, $b ) {
				return strcmp( $a->label, $b->label );
			}
			);

			return $result;
		}
	}
}