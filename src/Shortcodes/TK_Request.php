<?php

namespace WpPluginName\Shortcodes;

use WpPluginName\Common\Utilities\Http;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( TK_Request::class ) ) {
	/**
	 * Create `[tk_request]` to get a URL query parameter.
	 *
	 * TODO: This is just a demo shortcode. Remove this class unless you want to keep this little utility.
	 *
	 * @see \WpPluginName\Shortcodes\Manage::$shortcode_classes
	 */
	final class TK_Request extends Shortcode {
		/**
		 * @inheritDoc
		 */
		public function get_defaults(): array {
			return [
				'parameter'   => '', // Required
				'default'     => '', // The default value to return if the parameter is not present.
				'escape_with' => 'esc_html', // '' to NOT pass the result through `esc_html()` (scary - don't trust it) - but it is the only way to get a non-string result, which may be what you are wanting.
			];
		}

		/**
		 * @inheritDoc
		 *
		 * @see \WpPluginName\Common\Utilities\Http::get_request_param()
		 *
		 * @return mixed The value of the query parameter, if any.
		 */
		public function process_shortcode(
			array $atts = [],
			string $content = ''
		) {
			$atts = $this->get_atts( $atts );

			$atts['parameter'] = urlencode( $atts['parameter'] );

			// bad request
			if ( '' === $atts['parameter'] ) {
				return $this->get_error_message( 'Missing required "parameter" argument' );
			}

			$result = ( new Http() )->get_request_param( $atts['parameter'], $atts['escape_with'] );

			if ( null === $result ) {
				$result = $atts['default'];
			}

			return $result;
		}
	}
}
