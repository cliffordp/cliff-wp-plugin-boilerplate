<?php

namespace WpPluginName\Common\Utilities;

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
		 * Get the Post ID from a passed integer, a passed WP_Post object, or the current post.
		 *
		 * Helper function for getting Post ID. Accepts `null` or a Post ID. Zero will return false.
		 * If attempting to detect $post object and it is not found, returns `false` to avoid a PHP Notice.
		 *
		 * @param null|int|WP_Post $candidate  Post ID or object, `null` to get the ID of the global post object.
		 * @param string|array     $post_types If post is not of one of these post types, will return false.
		 *
		 * @return int|false The verified Post ID. False if post does not exist or is not of correct type.
		 */
		public function post_id_helper( $candidate = null, $post_types = [] ) {
			$candidate_post = get_post( $candidate );

			// Check if post exists at all.
			if ( ! $candidate_post instanceof WP_Post ) {
				return false;
			}

			// Check if the found post is of the correct type.
			if ( empty( $post_types ) ) {
				return $candidate_post->ID;
			} else {
				$post_types = (array) $post_types;

				if ( in_array( $candidate_post->post_type, $post_types, true ) ) {
					return $candidate_post->ID;
				} else {
					return false;
				}
			}
		}

		/**
		 * Get all of the "Post" post type's Post IDs that the currently logged-in user has authored.
		 *
		 * @return false|WP_Post[]|int[] False if user is not logged-in. Array of post objects or post IDs
		 *                               (or empty array) if user is logged-in.
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
				'author'         => $current_user->ID,
			];

			return ( new WP_Query( $args ) )->get_posts();
		}

		/**
		 * Get all of the "Post" post type's Post IDs.
		 *
		 * @return WP_Post[]|int[] Array of post objects or post IDs (or empty array).
		 */
		public function get_all_post_ids(): array {
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
		public function get_public_post_types(): array {
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
