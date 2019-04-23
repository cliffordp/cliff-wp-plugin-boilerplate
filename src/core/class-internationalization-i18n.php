<?php

namespace WP_Plugin_Name\Core;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Plugin_Name\Core\Internationalization_I18n' ) ) {
	/**
	 * Define the internationalization functionality.
	 *
	 * Loads and defines the internationalization files for this plugin
	 * so that it is ready for translation.
	 */
	class Internationalization_I18n {

		/**
		 * The text domain of the plugin.
		 *
		 * @var      string $text_domain The text domain of the plugin.
		 */
		private $text_domain;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @param      string $plugin_text_domain The text domain of this plugin.
		 */
		public function __construct( $plugin_text_domain ) {
			$this->text_domain = $plugin_text_domain;
		}

		/**
		 * Load the plugin text domain for translation.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain(
				$this->text_domain,
				false,
				dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
			);
		}
	}
}