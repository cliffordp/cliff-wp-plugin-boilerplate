<?php

declare( strict_types=1 );

namespace WpPluginName\Common\Settings;

use WpPluginName\Common\Utilities as Utils;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Choices::class ) ) {
	/**
	 * Everything related to setting, getting, and sanitizing plugin settings/options.
	 *
	 * @link https://divpusher.com/blog/wordpress-customizer-sanitization-examples/ Good list of sanitizer functions.
	 */
	class Choices {

		/**
		 * Sanitize a Google Maps API key.
		 *
		 * Example: AzpySIaA-FOsrXICdAhscyuiqTZUDwrbv-l4pAh
		 *
		 * @see sanitize_key() This function without strtolower().
		 *
		 * @param string $value
		 * @param string $default
		 *
		 * @return string
		 */
		public function sanitize_google_maps_api_key(
			string $value,
			string $default = ''
		): string {
			$result = preg_replace( '/[^a-zA-Z0-9_\-]/', '', $value );

			if ( empty( $result ) ) {
				return $default;
			}

			return $result;
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
					'name'  => esc_html__( 'Facebook', 'cliff-wp-plugin-boilerplate' ),
					'color' => '#3b5998',
				],
				[
					'key'   => 'twitter',
					'name'  => esc_html__( 'Twitter', 'cliff-wp-plugin-boilerplate' ),
					'color' => '#00aced',
				],
				[
					'key'   => 'pinterest',
					'name'  => esc_html__( 'Pinterest', 'cliff-wp-plugin-boilerplate' ),
					'color' => '#BD081C',
				],
				[
					'key'   => 'linkedin',
					'name'  => esc_html__( 'LinkedIn', 'cliff-wp-plugin-boilerplate' ),
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
				$result = $array[ $network ];
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
		 * @param array|string $value   Value that is passed by the Customizer.
		 * @param array|string $default The default value.
		 *
		 * @return string|array
		 */
		public function sanitize_social_networks( $value, $default = [] ) {
			$result = ( new Utils\Arrays() )->sanitize_multiple_values(
				$value,
				$this->get_choices_social_networks()
			);

			if ( empty( $result ) ) {
				return $default;
			}

			return $result;
		}

		/**
		 * Get the Post Types options.
		 *
		 * @param bool $for_picking If false, will work with Customizer.
		 *                          If true, will work for React radio/select input.
		 *
		 * @return array
		 */
		public function get_choices_post_types(
			bool $for_picking = false
		): array {
			$result = [];

			foreach ( ( new Utils\Posts() )->get_public_post_types() as $type ) {
				if ( $for_picking ) {
					$result[] = [
						'label' => $type->label,
						'value' => $type->name,
					];
				} else {
					// name is the registered name and label is what the user sees.
					$result[ $type->name ] = $type->label;
				}
			}

			return $result;
		}

		/**
		 * @todo: Example: Sanitize callback for Customizer: Post Types.
		 *
		 * Check if what the user selected is valid. If yes, return it, else return setting's default.
		 *
		 * @param array|string $value   When used as a Customizer callback, will be a JSON string.
		 * @param array|string $default The default value.
		 *
		 * @return array|string
		 */
		public function sanitize_post_types(
			$value,
			$default = []
		) {
			$result = ( new Utils\Arrays() )->sanitize_multiple_values(
				$value,
				( new Utils\Posts() )->get_public_post_types()
			);

			if ( empty( $result ) ) {
				return $default;
			}

			return $default;
		}

		/**
		 * Get an array of options for one through ten.
		 *
		 * @param bool $for_picking If true, will output for React radio/select input.
		 *
		 * @return array
		 */
		public function get_1_through_10(
			bool $for_picking = false
		): array {
			$choices = [
				1  => 'One',
				2  => 'Two',
				3  => 'Three',
				4  => 'Four',
				5  => 'Five',
				6  => 'Six',
				7  => 'Seven',
				8  => 'Eight',
				9  => 'Nine',
				10 => 'Ten',
			];

			$result = [];

			foreach ( $choices as $key => $value ) {
				if ( $for_picking ) {
					$result[] = [
						'label'    => $value,
						'value'    => $key,
						'disabled' => ( 6 === $key ) ? true : false, // let's always disable Six
					];
				} else {
					$result[ $key ] = $value;
				}
			}

			return $result;
		}
	}
}
