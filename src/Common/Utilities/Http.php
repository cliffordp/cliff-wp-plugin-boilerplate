<?php

declare( strict_types=1 );

namespace WpPluginName\Common\Utilities;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Http::class ) ) {

	/**
	 * Things related to HTTP, like $_GET, $_PUT, $_REQUEST, and such.
	 */
	class Http {

		/**
		 * Get the value of a $_REQUEST parameter, protecting against not existing and allowing custom escaping.
		 *
		 * @link https://www.php.net/manual/reserved.variables.request.php About $_REQUEST.
		 * @link https://developer.wordpress.org/plugins/security/securing-output/
		 * @link https://developer.wordpress.org/themes/theme-security/data-sanitization-escaping/
		 *
		 * @see  filter_input() We could have used this, but there were a number of things to workaround, particularly
		 *                      when manually changing _GET or _POST or modifying _GET during a _POST request.
		 *
		 * @param string $param       The parameter for which to get the value, if it exists.
		 * @param string $escape_with The function with which to escape each value, such as `absint()`. If set to
		 *                            something that is `empty()`, like `''`, it will return raw, untrusted HTML!
		 *                            If set to something not `empty()`, like but does not exist as a function, it will
		 *                            fallback to at least still use `esc_html()`.
		 *
		 * @return mixed|null Null if the parameter did not exist, else the result (string or array).
		 */
		public function get_request_param(
			string $param,
			string $escape_with = 'esc_html'
		) {
			$result = null;

			// If a GET request, ignore POST.
			if ( 'GET' === $_SERVER['REQUEST_METHOD'] ) {
				if ( isset( $_GET[ $param ] ) ) {
					$result = $_GET[ $param ];
				}
			}

			// If not explicitly GET, check POST first, then GET, just like REQUEST does.
			if ( ! isset( $result ) ) {
				if ( isset( $_POST[ $param ] ) ) {
					$result = $_POST[ $param ];
				}

				if ( ! isset( $result ) ) {
					if ( isset( $_GET[ $param ] ) ) {
						$result = $_GET[ $param ];
					}
				}
			}

			// Get the escaping function to use, if set and exists, defaulting to esc_html().
			if ( empty( $escape_with ) ) {
				$esc_func = '';
			} else if ( function_exists( $escape_with ) ) {
				$esc_func = $escape_with;
			} else {
				$esc_func = 'esc_html';
			}

			// Get the result, escaped or not.
			if ( isset( $result ) ) {
				if ( empty( $esc_func ) ) {
					// WARNING: Full, untrusted HTML is allowed!
					return $result;
				} else {
					if ( is_array( $result ) ) {
						$result = array_map( $esc_func, $result );
					} else {
						$result = call_user_func( $esc_func, $result );
					}

					return $result;
				}
			}

			return $result;
		}
	}

}
