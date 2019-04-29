<?php

namespace WP_Plugin_Name\Common;

use WP_Customize_Setting;
use WP_Plugin_Name\Common\Common as Common;
use WP_Plugin_Name\Common\Utilities as Utils;
use WP_Plugin_Name\Plugin_Data as Plugin_Data;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Settings::class ) ) {
	/**
	 * Everything related to setting, getting, and sanitizing plugin settings/options.
	 */
	class Settings {

		/**
		 * The Common instance.
		 *
		 * @var Common
		 */
		public $common;

		/**
		 * Initialize the class and set its properties.
		 */
		public function __construct() {
			$this->common = new Common();
		}

		/**
		 * The plugin's Settings page URL.
		 *
		 * @return string
		 */
		public function get_main_settings_page_url(): string {
			$url = 'options-general.php?page=' . $this->get_settings_page_slug();

			return admin_url( $url );
		}

		/**
		 * The plugin's Settings page slug.
		 *
		 * @return string
		 */
		public function get_settings_page_slug(): string {
			return Plugin_Data::plugin_text_domain() . '-settings';
		}

		/**
		 * The translatable "Settings" text.
		 *
		 * @return string
		 */
		public function get_settings_word(): string {
			return esc_html__( 'Settings', Plugin_Data::plugin_text_domain() );
		}

		/**
		 * Get a single option from the database, as a string, with an optional fallback value.
		 *
		 * @param string $key
		 * @param string $default
		 *
		 * @return string
		 */
		public function get_option_as_string( string $key, string $default = '' ): string {
			$result = $this->get_option( $key, $default );

			return $result;
		}

		/**
		 * Get the raw value of a single option from the database with an optional fallback value.
		 *
		 * @param string $key
		 * @param mixed  $default
		 *
		 * @return mixed
		 */
		public function get_option( string $key, $default = '' ) {
			$all_options = $this->get_all_options();

			// Cannot use empty() because an unchecked checkbox is boolean false, for example.
			if ( isset( $all_options[$key] ) ) {
				return $all_options[$key];
			} else {
				return $default;
			}
		}

		/**
		 * Get all of the saved options from the database.
		 *
		 * @return array
		 */
		public function get_all_options(): array {
			$plugin_options = get_option( Plugin_Data::plugin_text_domain_underscores() );

			if ( ! empty( $plugin_options ) ) {
				return (array) $plugin_options;
			} else {
				return [];
			}
		}

		/**
		 * Get a single option from the database as an array with an optional fallback value.
		 *
		 * @todo Is array_keys() really what we want here?
		 *
		 * @param string $key
		 * @param mixed  $default
		 *
		 * @return array
		 */
		public function get_option_as_array( string $key, $default = '' ): array {
			$result = $this->get_option( $key, $default );

			if ( is_string( $result ) ) {
				$result = json_decode( $result, true );
			}

			$result = (array) $result;

			$result = array_keys( $result );

			return $result;
		}

		/**
		 * Delete all of the saved options from the database.
		 *
		 * @see delete_option()
		 *
		 * @return bool
		 */
		public function delete_all_options(): bool {
			return delete_option( Plugin_Data::plugin_text_domain_underscores() );
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
		 * @todo: Example: Get data about all social networks.
		 *
		 * @param string $retrieve The relevant data to retrieve about each social network.
		 *
		 * @return array
		 */
		public function get_social_networks_data( $retrieve = '' ): array {
			$networks = [
				[
					'key'   => 'facebook',
					'name'  => esc_html__( 'Facebook', Plugin_Data::plugin_text_domain() ),
					'color' => '#3b5998',
				],
				[
					'key'   => 'twitter',
					'name'  => esc_html__( 'Twitter', Plugin_Data::plugin_text_domain() ),
					'color' => '#00aced',
				],
				[
					'key'   => 'pinterest',
					'name'  => esc_html__( 'Pinterest', Plugin_Data::plugin_text_domain() ),
					'color' => '#BD081C',
				],
				[
					'key'   => 'linkedin',
					'name'  => esc_html__( 'LinkedIn', Plugin_Data::plugin_text_domain() ),
					'color' => '#007bb6',
				],
			];

			if ( ! array_key_exists( $retrieve, $networks[0] ) ) {
				return [];
			}

			$result = wp_list_pluck( $networks, $retrieve, 'key' );

			return $result;
		}

		/**
		 * @todo: Example: Get the allowed social networks options.
		 *
		 * @return array
		 */
		public function get_choices_social_networks(): array {
			return $this->get_social_networks_data( 'name' );
		}

		/**
		 * @todo: Example: Get a social network's nice name.
		 *
		 * @param string $network
		 *
		 * @return string
		 */
		public function get_social_network_nice_name( string $network ): string {
			$array = $this->get_choices_social_networks();

			if ( array_key_exists( $network, $array ) ) {
				$result = $array[$network];
			} else {
				$result = '';
			}

			return $result;
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
			$result = ( new Utils\Arrays() )->sanitize_multiple_values( $value, $this->get_choices_social_networks() );

			if ( ! empty( $result ) ) {
				return $value;
			} else {
				return $setting->default;
			}
		}

		/**
		 * @todo: Example: Sanitize callback for Customizer: Post Types.
		 *
		 * Check if what the user selected is valid. If yes, return it, else return setting's default.
		 *
		 * @param array|string         $value   When used as a Customizer callback, will be a JSON string.
		 * @param WP_Customize_Setting $setting The setting object.
		 *
		 * @return array|string
		 */
		public function sanitize_post_types( $value, WP_Customize_Setting $setting ) {
			$result = ( new Utils\Arrays() )->sanitize_multiple_values( $value, ( new Utils\Posts() )->get_public_post_types() );

			if ( ! empty( $result ) ) {
				return $value;
			} else {
				return $setting->default;
			}
		}
	}
}