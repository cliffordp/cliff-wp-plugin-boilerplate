<?php

namespace WP_Plugin_Name\Admin\Settings;

use WP_Plugin_Name\Plugin_Data as Plugin_Data;
use WP_Plugin_Name\Common\Settings as Common_Settings;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Fields::class ) ) {
	/**
	 * The heading area for the admin screen.
	 */
	class Fields {

		/**
		 * The prefix for each of our settings.
		 *
		 * @var string
		 */
		private $prefix ='';

		/**
		 * Initialize the class and set its properties.
		 */
		public function __construct() {
			$this->prefix = Plugin_Data::plugin_text_domain_underscores();
		}

		public function register_settings() {
			register_setting(
				$this->prefix,
				$this->prefix . '[google_map_block_api_key]',
				[
					'type'              => 'string',
					'description'       => __( 'Google Map API key for the Google Maps Gutenberg Block.', 'textdomain' ),
					'sanitize_callback' => 'sanitize_text_field',
					'show_in_rest'      => true,
					'default'           => '',
				]
			);

			register_setting(
				$this->prefix,
				$this->prefix . '[blocks_settings_default_block]',
				[
					'type'              => 'boolean',
					'description'       => __( 'Make Section block your default block for Pages?', '@TODO' ),
					'sanitize_callback' => 'rest_sanitize_boolean',
					'show_in_rest'      => true,
					'default'           => true,
				]
			);

			register_setting(
				$this->prefix,
				$this->prefix . '[blocks_settings_global_defaults]',
				[
					'type'              => 'string',
					'description'       => __( 'Global defaults for Gutenberg Blocks.', 'textdomain' ),
					'sanitize_callback' => 'sanitize_text_field',
					'show_in_rest'      => true,
					'default'           => '',
				]
			);
		}

	}
}
