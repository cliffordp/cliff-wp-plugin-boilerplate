<?php

namespace WpPluginName\Core;

use WpPluginName\PluginData as PluginData;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( I18n::class ) ) {
	/**
	 * Define the internationalization functionality.
	 *
	 * Loads and defines the internationalization files for this plugin so that it is ready for translation.
	 */
	class I18n {

		/**
		 * Load the plugin text domain for translation.
		 *
		 * @link https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#loading-text-domain
		 *
		 * TODO: Remove if WordPress.org will provide the translation files.
		 */
		public function load_plugin_textdomain(): void {
			load_plugin_textdomain(
				PluginData::plugin_text_domain(),
				false,
				dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
			);
		}
	}
}
