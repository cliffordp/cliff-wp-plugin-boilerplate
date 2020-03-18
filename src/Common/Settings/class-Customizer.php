<?php

namespace WP_Plugin_Name\Common\Settings;

use WP_Customize_Setting;
use WP_Plugin_Name\Plugin_Data as Plugin_Data;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Customizer::class ) ) {
	/**
	 * Everything related to setting, getting, and sanitizing plugin settings/options via Customizer.
	 */
	class Customizer {

		/**
		 * Common's Choices instance.
		 *
		 * @var Choices
		 */
		public $choices;

		/**
		 * Initialize the class and set its properties.
		 */
		public function __construct() {
			$this->choices = new Choices();
		}

		/**
		 * Get the "deep link" to this plugin's panel within the Customizer options.
		 *
		 * @return string
		 */
		public function get_link_to_customizer_panel(): string {
			// Disallow generating a Customizer link if we are already in the Customizer because they will not be permitted to work anyway (cursor disabled) by the Customizer.
			if ( is_customize_preview() ) {
				return '';
			}

			// add flag in the Customizer url so we know we're in this plugin's Customizer Section
			$link_to_customizer_panel = add_query_arg( Plugin_Data::plugin_text_domain_underscores(), 'true', wp_customize_url() );

			// auto-open the panel
			$link_to_customizer_panel = add_query_arg( 'autofocus[panel]', $this->customizer_panel_id(), $link_to_customizer_panel );

			return $link_to_customizer_panel;
		}

		/**
		 * Customizer Panel ID.
		 *
		 * @return string
		 */
		public function customizer_panel_id(): string {
			return Plugin_Data::plugin_text_domain_underscores() . '_panel';
		}

		/**
		 * @todo: Example: Sanitize callback for Customizer: Social Networks.
		 *
		 * Check if what the user selected is valid. If yes, return it, else return setting's default.
		 *
		 * @param array|string         $value   Value that is passed by the Customizer.
		 * @param WP_Customize_Setting $setting The setting object.
		 *
		 * @return string|array
		 */
		public function sanitize_social_networks( $value, WP_Customize_Setting $setting ) {
			return $this->choices->sanitize_social_networks( $value, $setting->default );
		}

		/**
		 * @todo: Example: Sanitize callback for Customizer: Post Types.
		 *
		 * Check if what the user selected is valid. If yes, return it, else return setting's default.
		 *
		 * @see \WP_Plugin_Name\Common\Settings\Choices::sanitize_post_types()
		 *
		 * @param array|string         $value   When used as a Customizer callback, will be a JSON string.
		 * @param WP_Customize_Setting $setting The setting object.
		 *
		 * @return array|string
		 */
		public function sanitize_post_types( $value, WP_Customize_Setting $setting ) {
			return $this->choices->sanitize_post_types( $value, $setting->default );
		}
	}
}