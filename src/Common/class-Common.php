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
