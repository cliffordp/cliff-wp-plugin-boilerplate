<?php

namespace WpPluginName\Common\Settings;

use WpPluginName\PluginData;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Main::class ) ) {
	/**
	 * Everything related to setting, getting, and sanitizing plugin settings/options.
	 */
	class Main {

		/**
		 * Use text domain with underscores as our options prefix.
		 *
		 * @var string
		 */
		private $prefix;

		/**
		 * The Common Choices instance.
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
		 * Get the option prefix.
		 *
		 * @see get_prefixed_option_key()
		 *
		 * @return string
		 */
		public function get_option_prefix(): string {
			// Just a way to identify where the prefix ends and the unique key starts.
			$delimiter = '__';

			if ( empty( $this->prefix ) ) {
				$this->prefix = PluginData::plugin_text_domain_underscores();
			}

			return $this->prefix . $delimiter;
		}

		/**
		 * Get the full option name, consistently prefixed, in a format that will work as a JavaScript object
		 * key (which is why hyphens get converted to underscores).
		 *
		 * @param string $key
		 *
		 * @return string
		 */
		public function get_prefixed_option_key( string $key ): string {
			$key = sanitize_key( $this->get_option_prefix() . $key );

			return str_replace( '-', '_', $key );
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
			return PluginData::plugin_text_domain() . '-settings';
		}

		/**
		 * The translatable "Settings" text.
		 *
		 * @return string
		 */
		public function get_settings_word(): string {
			return esc_html__( 'Settings', 'cliff-wp-plugin-boilerplate' );
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
			if ( isset( $all_options[ $key ] ) ) {
				return $all_options[ $key ];
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
			$plugin_options = get_option( PluginData::plugin_text_domain_underscores() );

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
			return delete_option( PluginData::plugin_text_domain_underscores() );
		}

		/**
		 * Get all the option keys with this plugin's prefix.
		 *
		 * @param bool $only_if_show_in_rest Whether or not to exclude if 'show_in_rest' is false.
		 *
		 * @return array
		 */
		public function get_all_prefixed_options( bool $only_if_show_in_rest = true ): array {
			$prefix = $this->get_option_prefix();

			$all_settings = get_registered_settings();

			$result = [];

			foreach ( $all_settings as $key => $value ) {
				if ( 0 === strpos( $key, $prefix ) ) {
					if (
						$only_if_show_in_rest
						&& empty( $all_settings[ $key ]['show_in_rest'] )
					) {
						continue;
					}

					$result[] = $key; // we only want the option keys, not all their arguments
				}
			}

			return $result;
		}

		/**
		 * Register our settings so we can use the WordPress REST API to get/set them via React.
		 *
		 * @link https://developer.wordpress.org/reference/functions/register_setting/
		 * @link https://developer.wordpress.org/rest-api/reference/settings/
		 * @link https://make.wordpress.org/core/2016/10/26/registering-your-settings-in-wordpress-4-7/
		 * @link https://make.wordpress.org/core/2019/10/03/wp-5-3-supports-object-and-array-meta-types-in-the-rest-api/
		 */
		public function register_settings() {
			register_setting(
				$this->get_option_prefix(),
				$this->get_prefixed_option_key( 'my_toggle' ),
				[
					'type'              => 'boolean',
					'default'           => false,
					'sanitize_callback' => 'rest_sanitize_boolean',
					'show_in_rest'      => true,
				]
			);

			register_setting(
				$this->get_option_prefix(),
				$this->get_prefixed_option_key( 'my_textinput' ),
				[
					'type'              => 'string',
					'sanitize_callback' => [ $this->choices, 'sanitize_google_maps_api_key' ],
					'default'           => '',
					'show_in_rest'      => true,
				]
			);

			/**
			 * The "show_in_rest" > "schema" > "enum" validation is the same concept as "sanitize_callback" logic but
			 * that's not the correct way to register a setting to be used by the REST API (e.g. wp-admin's React
			 * Settings page). If someone tries to set a value other than these white-listed options (or our validation
			 * is misconfigured, such as using "sanitize_callback"), the API will return a 400 error due to
			 * "invalid parameter(s)". If we set "show_in_rest" to "true" for a "string" setting without adding "schema",
			 * it will work and have some sanitizing/escaping by default (so script tags won't be allowed at least),
			 * but it'll allow saving unexpected values to the database as long as it's still a string, for example.
			 *
			 * @see \WP_REST_Request::has_valid_params() Where the validation happens.
			 */
			register_setting(
				$this->get_option_prefix(),
				$this->get_prefixed_option_key( 'my_radio' ),
				[
					'type'         => 'string',
					'default'      => '',
					'show_in_rest' => [
						'schema' => [
							'enum' => array_keys( $this->choices->get_choices_post_types() ),
						],
					],
				]
			);

		}

	}
}
