<?php

namespace WP_Plugin_Name\Shortcodes;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( TK_Request::class ) ) {
	/**
	 * The functionality shared between the admin and public-facing areas of the plugin.
	 *
	 * Useful for things like utilities or hooking into something that affects both back-end and front-end.
	 */
	final class TK_Request extends Shortcode {
		/**
		 * An array of all the shortcode's possible attributes and their default values.
		 *
		 * @return array
		 */
		public function get_defaults(): array {
			return [
				'parameter' => '', // Required
				'default'   => '', // The default value to return if the parameter is not present.
				'escape'    => 'true', // 'false' to NOT pass the result through `esc_html()` (scary - don't trust it) - but 'false' is the only way to get a non-string result, which may be what you are wanting.
			];
		}

		/**
		 * Get the specified parameter from $_REQUEST ($_GET then $_POST).
		 *
		 * @link https://secure.php.net/manual/reserved.variables.request.php About $_REQUEST
		 *
		 * @see  filter_input() We could have used this, but there were a number of things to workaround, particularly
		 *                      when manually changing _GET or _POST or modifying _GET during a _POST request.
		 *
		 * @param array  $atts    The shortcode attributes.
		 * @param string $content The value from using an enclosing (not self-closing) shortcode.
		 *
		 * @return mixed The value of the query parameter, if any.
		 */
		public function process_shortcode( array $atts = [], string $content = '' ) {
			$atts = $this->get_atts( $atts );

			$atts['parameter'] = urlencode( $atts['parameter'] );

			$param = $atts['parameter'];

			// bad request
			if ( '' === $param ) {
				return $this->get_error_message( 'Missing required "parameter" argument' );
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
				if ( 'false' === $atts['escape'] ) {
					// WARNING: Full, untrusted HTML is allowed!
					return $result;
				} else {
					if ( is_array( $result ) ) {
						$result = array_map( 'esc_html', $result );
					} else {
						$result = esc_html( $result );
					}

					return $result;
				}
			} else {
				return $atts['default'];
			}
		}
	}
}
