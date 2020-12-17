<?php

declare( strict_types=1 );

namespace WpPluginName\Common\Utilities;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Arrays::class ) ) {
	class Arrays {

		/**
		 * Get all the values from a single or multi-dimensional array.
		 *
		 * Non-numeric array keys will be preserved but its value may be overwrittern, as per usual with merging arrays.
		 *
		 * @link https://gist.github.com/SeanCannon/6585889#gistcomment-2823537 Thanks to this collective effort.
		 *
		 * @param array $array
		 *
		 * @return array
		 */
		public function flatten_array( array $array ): array {
			$result = [];

			if ( ! is_array( $array ) ) {
				$array = func_get_args();
			}

			foreach ( $array as $key => $value ) {
				if ( is_array( $value ) ) {
					$result = array_merge( $result, $this->flatten_array( $value ) );
				} else {
					$result = array_merge( $result, [ $key => $value ] );
				}
			}

			return $result;
		}

		/**
		 * Sanitize an array (or comma-separated string) of values, only allowing values that are in the array
		 * of allowable values.
		 *
		 * Does not support multidimensional arrays but could be altered to.
		 *
		 * @param array|string $values Array or JSON string.
		 * @param array        $allowables
		 *
		 * @return string|array If original value was a string (assumed JSON), outputs as JSON string, else array.
		 */
		public function sanitize_multiple_values( $values, array $allowables ) {
			$orig_values = $values;

			// handle booleans, such as single checkbox value
			if ( is_bool( $values ) ) {
				return $values;
			}

			if ( is_string( $orig_values ) ) {
				$values = json_decode( $values, true );
			}

			if ( ! is_array( $allowables ) ) {
				// return as same type that came in
				if ( is_string( $orig_values ) ) {
					return '';
				} else {
					return [];
				}
			}

			if ( ! is_array( $values ) ) {
				$values_array = (array) explode( ',', $values );
			} else {
				$values_array = (array) $values;
			}

			$result = [];

			foreach ( $values_array as $k => $v ) {
				if ( array_key_exists( $k, $allowables ) ) {

					// stick with JSON encoded format for booleans instead of 1/0 from PHP
					if ( is_bool( $v ) ) {
						if ( $v ) {
							$v = 'true';
						} else {
							$v = 'false';
						}
					}

					$result[$k] = sanitize_text_field( $v );
				}
			}

			// return as same type that came in
			if ( is_string( $orig_values ) ) {
				$result = json_encode( $result, true );
			}

			return $result;
		}

		/**
		 * Given an array having integer keys, get the maximum key.
		 *
		 * Will ignore any non-integer keys, such as numeric strings and floats.
		 *
		 * @param array $array
		 *
		 * @return int|false Maximum integer key (could be zero) if the array had at least one integer key, else false.
		 */
		public function get_max_int_key( array $array ) {
			$keys = array_keys( $array );

			$keys = array_filter( $keys, 'is_int' );

			// None of the array keys were integers.
			if ( empty( $keys ) ) {
				return false;
			}

			return (int) max( $keys );
		}

	}
}
