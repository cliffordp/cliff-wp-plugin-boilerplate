<?php

namespace WP_Plugin_Name\Common;

use WP_Plugin_Name\Plugin_Data as Plugin_Data;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Common::class ) ) {
	/**
	 * The functionality shared between the admin and public-facing areas of the plugin.
	 *
	 * Useful for things that affect both back-end and front-end.
	 */
	class Common {

		/**
		 * Determine if we are in a request of the specified type.
		 *
		 * Note that 'frontend' is true if Ajax.
		 *
		 * @link https://github.com/woocommerce/woocommerce/blob/4.0.1/includes/class-woocommerce.php#L289-L306 Inspiration.
		 *
		 * @param string $type admin, ajax, cron, rest, or frontend.
		 *
		 * @return bool|void True if is the type passed. False if not. Void if passed an unsupported type.
		 */
		public function current_request_is( string $type ): ?bool {
			switch ( $type ) {
				case 'admin':
					return is_admin();
				case 'ajax':
					return wp_doing_ajax();
				case 'cron':
					return wp_doing_cron();
				case 'rest':
					return $this->is_rest_api_request();
				case 'frontend':
					return (
							! is_admin()
							|| wp_doing_ajax()
						)
						&& ! wp_doing_cron()
						&& ! $this->is_rest_api_request();
			}
		}

		/**
		 * Returns true if the request is a non-legacy REST API request.
		 *
		 * Legacy REST requests should still run some extra code for backwards compatibility.
		 *
		 * @todo : replace this function once core WP function is available: https://core.trac.wordpress.org/ticket/42061.
		 *
		 * @link https://github.com/woocommerce/woocommerce/blob/4.0.1/includes/class-woocommerce.php#L269-L287 Similar source.
		 *
		 * @return bool
		 */
		private function is_rest_api_request() {
			if ( empty( $_SERVER['REQUEST_URI'] ) ) {
				return false;
			}

			$rest_prefix = trailingslashit( rest_get_url_prefix() );

			return ( false !== strpos( $_SERVER['REQUEST_URI'], $rest_prefix ) );
		}

		/**
		 * Capability required to access the settings, be shown error messages, etc.
		 *
		 * By default, 'customize' is mapped to 'edit_theme_options' (Administrator).
		 *
		 * @link  https://developer.wordpress.org/themes/customize-api/advanced-usage/
		 *
		 * @return string
		 */
		public function required_capability(): string {
			return apply_filters( Plugin_Data::plugin_text_domain_underscores() . '_required_capability', 'customize' );
		}

		/**
		 * Get the output's wrapper class.
		 *
		 * Used by the Customizer to add the quick edit pencil icon within the previewer.
		 *
		 * @return string
		 */
		public function get_wrapper_class(): string {
			$class = Plugin_Data::plugin_text_domain_underscores() . '-wrapper';

			return esc_attr( $class );
		}
	}
}
