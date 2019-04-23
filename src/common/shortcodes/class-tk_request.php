<?php

namespace WP_Plugin_Name\Shortcodes;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Plugin_Name\Shortcodes\TK_Request' ) ) {
	/**
	 * The functionality shared between the admin and public-facing areas of the plugin.
	 *
	 * Useful for things like utilities or hooking into something that affects both back-end and front-end.
	 */
	class TK_Request extends Shortcode {

		/**
		 * Get the specified parameter from $_REQUEST ($_GET then $_POST).
		 *
		 * @link https://secure.php.net/manual/reserved.variables.request.php About $_REQUEST
		 *
		 * @see  filter_input() Although we could have gone this way, there were a number of things to workaround,
		 *                     particularly when manually changing _GET or _POST or modifying _GET during a _POST request.
		 *
		 * @param array|string $atts    If using the shortcode, this will be an array. If using PHP function, array or string.
		 * @param array|string $default The default value to return if the parameter is not present.
		 * @param bool         $escape  True to pass the result through `esc_html()`. False to allow the raw value (don't
		 *                              trust it), but false is the only way to get an array result.
		 *
		 * @return mixed The value of the query parameter, if any.
		 */
		public function process_shortcode( $atts = [], $default = '', $escape = true ) {
			// Protect against passing a string value, such as if used directly via PHP function instead of as a shortcode.
			if ( is_string( $atts ) ) {
				$atts = [ 'parameter' => $atts ];
			}

			$atts['parameter'] = urlencode( $atts['parameter'] );

			$defaults = [
				'parameter' => '',
			];

			$atts = shortcode_atts( $defaults, $atts, $this->get_tag() );

			$param = $atts['parameter'];

			// bad request
			if ( empty( $param ) ) {
				return '';
			}

			// If a GET request, ignore POST.
			if ( 'GET' === $_SERVER['REQUEST_METHOD'] ) {
				if ( isset( $_GET[$param] ) ) {
					$result = $_GET[$param];
				}
			}

			// If not explicitly GET, check POST first, then GET, just like REQUEST does.
			if ( ! isset( $result ) ) {
				if ( isset( $_POST[$param] ) ) {
					$result = $_POST[$param];
				}

				if ( ! isset( $result ) ) {
					if ( isset( $_GET[$param] ) ) {
						$result = $_GET[$param];
					}
				}
			}

			if ( isset( $result ) ) {
				if ( $escape ) {
					if ( is_array( $result ) ) {
						$result = array_map( 'esc_html', $result );
					} else {
						$result = esc_html( $result );
					}

					return $result;
				} else {
					// WARNING: Full, untrusted HTML is allowed!
					return $result;
				}
			} else {
				return $default;
			}
		}
	}
}