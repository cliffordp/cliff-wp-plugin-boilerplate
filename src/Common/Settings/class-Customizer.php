<?php

namespace WP_Plugin_Name\Common\Settings;

use WP_Customize_Setting;
use WP_Plugin_Name\Common\Common as Common;
use WP_Plugin_Name\Common\Utilities as Utils;
use WP_Plugin_Name\Plugin_Data as Plugin_Data;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Customizer::class ) ) {
	/**
	 * Everything related to setting, getting, and sanitizing plugin settings/options.
	 */
	class Customizer {

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
