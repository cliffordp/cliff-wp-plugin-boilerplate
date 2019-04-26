<?php

namespace WP_Plugin_Name\Common;

use WP_Plugin_Name as NS;

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
		 * Get this plugin's text domain.
		 *
		 * @return string
		 */
		public function plugin_version() {
			return NS\PLUGIN_VERSION;
		}

		/**
		 * Get this plugin's text domain.
		 *
		 * @return string
		 */
		public function plugin_text_domain() {
			return NS\PLUGIN_TEXT_DOMAIN;
		}

		/**
		 * Get this plugin's text domain with underscores instead of hyphens.
		 *
		 * Used for saving options. Also useful for building namespaced hook names, class names, URLs, etc.
		 *
		 * @return string 'wp_plugin_name'
		 */
		public function plugin_text_domain_underscores() {
			return str_replace( '-', '_', $this->plugin_text_domain() );
		}

		/**
		 * Capability required to access the settings, be shown error messages, etc.
		 *
		 * By default, 'customize' is mapped to 'edit_theme_options' (Administrator).
		 *
		 * @link  https://developer.wordpress.org/themes/customize-api/advanced-usage/
		 */
		public function required_capability() {
			return apply_filters( $this->plugin_text_domain_underscores() . '_required_capability', 'customize' );
		}

		/**
		 * Get the output's wrapper class.
		 *
		 * Used by the Customizer to add the quick edit pencil icon within the previewer.
		 *
		 * @return string
		 */
		public function get_wrapper_class() {
			$class = $this->plugin_text_domain_underscores() . '-wrapper';

			return (string) esc_attr( $class );
		}
	}
}