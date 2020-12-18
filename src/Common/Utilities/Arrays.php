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
		 * Sanitize an array (or comma-separated string) of values, only allowing values that have array keys matching
		 * those of the allowable keys.
		 *
		 * Allowed values pass through sanitize_text_field().
		 * Does not support multidimensional arrays.
		 *
		 * @param array|string $values         Array or JSON string. Must be associative.
		 * @param array        $allowable_keys A non-associative array of allowed array keys.
		 *
		 * @return string|array If original value was a string (assumed JSON), outputs as JSON string, else array.
		 */
		public function sanitize_multiple_values( $values, array $allowable_keys ) {
			$orig_values = $values;

			// Handle booleans, such as single checkbox value.
			if ( is_bool( $values ) ) {
				return $values;
			}

			if ( is_string( $orig_values ) ) {
				$values = json_decode( $values, true );
			}

			if ( ! is_array( $values ) ) {
				$values = (array) explode( ',', $values );
			}

			$result = [];

			if (
				$this->is_associative_array( $values )
				&& ! $this->is_associative_array( $allowable_keys )
			) {
				// Prep for array_key_exists().
				$allowable_keys = array_flip( $allowable_keys );

				foreach ( $values as $k => $v ) {
					if ( array_key_exists( $k, $allowable_keys ) ) {
						// Stick with JSON encoded format for booleans instead of 1/0 from PHP.
						if ( is_bool( $v ) ) {
							if ( $v ) {
								$v = 'true';
							} else {
								$v = 'false';
							}
						}

						$result[ $k ] = sanitize_text_field( $v );
					}
				}
			}

			// return as same type that came in
			if ( is_string( $orig_values ) ) {
				$result = json_encode( $result );
			}

			return $result;
		}

		/**
		 * Whether an array has at least one key being a string.
		 *
		 * @param $array
		 *
		 * @return bool True if the array has at least one string key. False if all are integers.
		 */
		public function is_associative_array( array $array ): bool {
			foreach ( $array as $key => $value ) {
				if ( is_string( $key ) ) {
					return true;
				}
			}

			return false;
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

		/**
		 * Given an array, convert all numeric keys to integer keys, discarding all non-numeric keys and their values.
		 *
		 * @param array $array
		 *
		 * @return array Array having only integer keys (may be negative).
		 */
		public function filter_array_only_numeric_keys_as_int( array $array ): array {
			$result = [];

			foreach ( $array as $k => $v ) {
				if ( is_numeric( $k ) ) {
					$key            = (int) $k;
					$result[ $key ] = $v;
				}
			}

			return $result;
		}

		/**
		 * Get matching or next integer lookup value from numerically-indexed array.
		 *
		 * All non-numeric array keys get discarded, others get converted to integer (may be negative) then sorted, then
		 * the lookup is found, optionally returning its value.
		 *
		 * @link https://gist.github.com/pepijnolivier/09435a18030419c4d15dbcf1058d536e Adapted from.
		 * @link https://stackoverflow.com/questions/5464919/find-a-matching-or-closest-value-in-an-array#comment76281788_22375510 Adapted from.
		 * @link https://stackoverflow.com/a/22375510/893907 Adapted from.
		 *
		 * @param array $array  Array with numeric indexes (will convert all keys to integers).
		 * @param int   $lookup The integer (may be negative) to find as an exact match key or as the next key.
		 * @param bool  $value  Whether to return the value from the array instead of the key itself.
		 *
		 * @return int|null|mixed Integer if returning the key, null if the array had no numeric keys or lookup is
		 *                        greater than the largest key, else mixed if returning the value instead of the key.
		 */
		public function lookup_next_array_integer_key(
			array $array,
			int $lookup,
			bool $value = false
		) {
			$array = $this->filter_array_only_numeric_keys_as_int( $array );

			if ( empty( $array ) ) {
				return null;
			}

			$keys = array_keys( $array );

			$lookup = (int) $lookup;

			if ( $lookup > max( $keys ) ) {
				return null;
			}

			sort( $keys, SORT_NUMERIC );

			foreach ( $keys as $key ) {
				if ( $key >= $lookup ) {
					break;
				}
			}

			// Return the value from the determined array key.
			if ( $value ) {
				return $array[ $key ];
			}

			// Return the determined array key.
			return $key;
		}
	}
}
