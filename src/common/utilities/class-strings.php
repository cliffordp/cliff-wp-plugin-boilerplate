<?php

namespace WP_Plugin_Name\Common\Utilities;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Strings::class ) ) {
	/**
	 * The functionality shared between the admin and public-facing areas of the plugin.
	 *
	 * Useful for things like utilities or hooking into something that affects both back-end and front-end.
	 */
	class Strings {

		/**
		 * Check if one string ends with another string.
		 *
		 * @param $subject
		 * @param $search_for
		 *
		 * @return bool True if subject ends with searched string, else false.
		 */
		public function string_ends_with( $subject, $search_for ) {
			if (
				! is_string( $subject )
				|| ! is_string( $search_for )
			) {
				return false;
			}

			$subject_length = strlen( $subject );

			$search_for_length = strlen( $search_for );

			if ( $search_for_length > $subject_length ) {
				return false;
			}

			return substr_compare( $subject, $search_for, $subject_length - $search_for_length, $search_for_length ) === 0;
		}

		/**
		 * Get the string between two strings.
		 *
		 * Will return the first match between start and end.
		 * Example: this is my [tag]dog[/tag]
		 * If searching from 'g' to 'g', will return ']do'.
		 *
		 * @param string $subject
		 * @param string $start
		 * @param string $end If omitted and $start is found, will return from $start through end of string.
		 *
		 * @return string
		 */
		public function get_string_between_two_strings( $subject, $start, $end = '' ) {
			if (
				! is_string( $subject )
				|| ! is_string( $start )
				|| ! is_string( $end )
			) {
				return '';
			}

			$r = explode( $start, $subject );

			if ( isset( $r[1] ) ) {
				if ( '' !== $end ) {
					$r = explode( $end, $r[1] );

					return $r[0];
				} else {
					return $r[1];
				}
			}

			return '';
		}

	}
}