<?php

namespace WP_Plugin_Name\Common;

use DateTime;
use DateTimeZone;
use Exception;
use WP_Customize_Setting;
use WP_Plugin_Name as NS;
use WP_Plugin_Name\Customizer\Customizer as Customizer;
use WP_Post;
use WP_Query;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Common' ) ) {
	/**
	 * The functionality shared between the admin and public-facing areas of the plugin.
	 *
	 * Useful for things like utilities or hooking into something that affects both back-end and front-end.
	 */
	class Common {

		/**
		 * The text domain of this plugin.
		 *
		 * @var    string $plugin_text_domain The text domain of this plugin.
		 */
		public $plugin_text_domain;

		/**
		 * The version of this plugin.
		 *
		 * @var    string $version The current version of this plugin.
		 */
		public $version;

		/**
		 * Shortcodes to register.
		 *
		 * The shortcode tag must match the method name within Common (must be public and cannot be static).
		 */
		public $shortcodes = [
			'tk_request',
		];

		/**
		 * Initialize the class and set its properties.
		 */
		public function __construct() {
			$this->plugin_text_domain = NS\PLUGIN_TEXT_DOMAIN;
			$this->version            = NS\PLUGIN_VERSION;
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
		 * Get this plugin's text domain with underscores instead of hyphens.
		 *
		 * Used for saving options. Also useful for building namespaced hook names, class names, URLs, etc.
		 *
		 * @return string 'wp_plugin_name'
		 */
		public function plugin_text_domain_underscores() {
			return str_replace( '-', '_', $this->plugin_text_domain );
		}

		/**
		 * Get the Post ID from the current page or from a passed integer or WP_Post object.
		 *
		 * Helper function for getting Post ID. Accepts null or a Post ID. If no $post object exists, returns false.
		 *
		 * @param null|int|WP_Post $post
		 *
		 * @return int|false
		 */
		public function post_id_helper( $post = null ) {
			if (
				! is_null( $post )
				&& is_numeric( $post )
				&& absint( $post ) > 0
			) {
				return (int) $post;
			} elseif (
				is_object( $post )
				&& ! empty( $post->ID )
			) {
				return (int) $post->ID;
			} else {
				if (
					! empty( $GLOBALS['post'] )
					&& $GLOBALS['post'] instanceof WP_Post
				) {
					return get_the_ID();
				} else {
					return false;
				}
			}
		}

		/**
		 * Get the specified parameter from $_REQUEST ($_GET then $_POST).
		 *
		 * @link https://secure.php.net/manual/reserved.variables.request.php About $_REQUEST
		 *
		 * @see  filter_input() Although we could have gone this way, there were a number of things to workaround,
		 *                     particularly when manually changing _GET or _POST or modifying _GET during a _POST request.
		 *
		 * @param array|string $atts    If using the shortcode, this will be an array. If using PHP function, array or string.
		 * @param array|string $default The default value to return if the parameter is not present.
		 * @param bool         $escape  True to pass the result through `esc_html()`. False to allow the raw value (don't
		 *                              trust it), but false is the only way to get an array result.
		 *
		 * @return mixed The value of the query parameter, if any.
		 */
		public function tk_request( $atts, $default = '', $escape = true ) {
			// Protect against passing a string value, such as if used directly via PHP function instead of as a shortcode.
			if ( is_string( $atts ) ) {
				$atts = [ 'parameter' => $atts ];
			}

			$atts['parameter'] = urlencode( $atts['parameter'] );

			$defaults = [
				'parameter' => '',
			];

			$atts = shortcode_atts( $defaults, $atts, __FUNCTION__ );

			$param = $atts['parameter'];

			// bad request
			if ( empty( $param ) ) {
				return '';
			}

			// If a GET request, ignore POST.
			if ( 'GET' === $_SERVER['REQUEST_METHOD'] ) {
				if ( isset( $_GET[$param] ) ) {
					$result = $_GET[$param];
				}
			}

			// If not explicitly GET, check POST first, then GET, just like REQUEST does.
			if ( ! isset( $result ) ) {
				if ( isset( $_POST[$param] ) ) {
					$result = $_POST[$param];
				}

				if ( ! isset( $result ) ) {
					if ( isset( $_GET[$param] ) ) {
						$result = $_GET[$param];
					}
				}
			}

			if ( isset( $result ) ) {
				if ( $escape ) {
					return esc_html( $result );
				} else {
					// WARNING: Full, untrusted HTML is allowed!
					return $result;
				}
			} else {
				return $default;
			}
		}

		/**
		 * Get all of the "Post" post type's Post IDs that the currently logged-in user has authored.
		 *
		 * @return false|array False if user is not logged-in. Array (may be empty) if user is logged-in.
		 */
		public function get_all_current_author_post_ids() {
			$current_user = wp_get_current_user();

			// User is not logged-in
			if ( empty( $current_user ) ) {
				return false;
			}

			$args = [
				'fields'         => 'ids',
				'posts_per_page' => - 1,
				'post_type'      => 'post',
				'author'         => $current_user->ID
			];

			$result = new WP_Query( $args );

			return $result->posts;
		}

		/**
		 * Get all of the "Post" post type's Post IDs.
		 *
		 * @return array
		 */
		public function get_all_post_ids() {
			$args = [
				'fields'         => 'ids',
				'posts_per_page' => - 1,
				'post_type'      => 'post',
			];

			$result = new WP_Query( $args );

			return $result->posts;
		}

		/**
		 * Get the PHP DateTime() object for the current time in the time zone from WordPress settings.
		 *
		 * If WordPress setting is not a valid PHP time zone, fallback to Chicago (Central Time).
		 *
		 * @return DateTime|false
		 */
		public function get_current_time_wp_tz_date_object() {
			$time_zone = get_option( 'timezone_string' );

			if ( ! in_array( $time_zone, timezone_identifiers_list() ) ) {
				$time_zone = 'America/Chicago';
			}

			try {
				$now = new DateTime( 'now', new DateTimeZone( $time_zone ) );
			}
			catch ( Exception $exception ) {
				$now = false;
			}

			return $now;
		}

		/**
		 * Round a numeric value up to the nearest decimal, such as up to the nearest 10 cents (without currency symbol).
		 *
		 * @link http://php.net/manual/en/function.ceil.php#50448 Source of this code.
		 *
		 * @param     $value  A numeric value, whether a string, float, or integer. If zero, result will be zero regardless
		 *                    of $places.
		 * @param int $places The positive number of digits to round to, such as 1 for the nearest 10 cents or 2 for the
		 *                    nearest penny.
		 *
		 * @return float|int
		 */
		public function round_up( $value, $places = 0 ) {
			$value = (float) $value;

			// Avoid dividing by zero
			if ( empty( $value ) ) {
				return 0;
			}

			$places = absint( $places );

			$multiplier = pow( 10, $places );

			return ceil( $value * $multiplier ) / $multiplier;
		}

		/**
		 * Given a number, round up to the given integer interval.
		 *
		 * Useful to round up to the next 15 minutes, such as rounding 63 minutes up to 75 minutes (60 minutes + 15 minutes).
		 *
		 * @param int|float|string $value    The integer, float, or numeric string to round up to the next interval.
		 * @param int              $interval The interval to round up to, such as 15. Set to 1 to get the same thing as just
		 *                                   using ceil().
		 *
		 * @return int
		 */
		public function round_up_to_next( $value = 0, $interval = 0 ) {
			if (
				empty( $value )
				|| ! is_numeric( $value )
				|| ! is_int( $interval )
				|| 0 >= $interval
			) {
				return 0;
			}

			$result = $interval * ceil( $value / $interval );

			return (int) round( $result );
		}

		/**
		 * Get all the values from a single or multi-dimensional array.
		 *
		 * Non-numeric array keys will be preserved but its value may be overwrittern, as per usual with merging arrays.
		 *
		 * @param $array
		 *
		 * @link https://gist.github.com/SeanCannon/6585889#gistcomment-2823537 Thanks to this collective effort.
		 *
		 * @return array
		 */
		public function flatten_array( $array = null ) {
			$result = [];

			if ( ! is_array( $array ) ) {
				$array = func_get_args();
			}

			foreach ( $array as $key => $value ) {
				if ( is_array( $value ) ) {
					$result = array_merge( $result, $this->flatten_array( $value ) );
				} else {
					$result = array_merge( $result, [ $key => $value ] );
				}
			}

			return $result;
		}

		/**
		 * Get a single option from the database, as a string, with an optional fallback value.
		 *
		 * @param string $key
		 * @param string $default
		 *
		 * @return mixed
		 */
		public function get_option_as_string( $key, $default = '' ) {
			$result = $this->get_option( $key, $default );

			return (string) $result;
		}

		/**
		 * Get the raw value of a single option from the database with an optional fallback value.
		 *
		 * @param string $key
		 * @param string $default
		 *
		 * @return mixed
		 */
		public function get_option( $key, $default = '' ) {
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
		public function get_all_options() {
			$plugin_options = get_option( $this->plugin_text_domain_underscores() );

			if ( ! empty( $plugin_options ) ) {
				return (array) $plugin_options;
			} else {
				return [];
			}
		}

		/**
		 * Get a single option from the database, as an array, with an optional fallback value.
		 *
		 * @param string $key
		 * @param string $default
		 *
		 * @return array
		 */
		public function get_option_as_array( $key, $default = '' ) {
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
		 * @return bool
		 */
		public function delete_all_options() {
			return delete_option( $this->plugin_text_domain_underscores() );
		}

		/**
		 * Get the "deep link" to this plugin's panel within the Customizer options.
		 *
		 * @return string
		 */
		public function get_link_to_customizer_panel() {
			// Disallow generating a Customizer link if we are already in the Customizer because they will not be permitted to work anyway (cursor disabled) by the Customizer.
			if ( is_customize_preview() ) {
				return '';
			}

			// add flag in the Customizer url so we know we're in this plugin's Customizer Section
			$link_to_customizer_panel = add_query_arg( $this->plugin_text_domain_underscores(), 'true', wp_customize_url() );

			// auto-open the panel
			$link_to_customizer_panel = add_query_arg( 'autofocus[panel]', $this->customizer_panel_id(), $link_to_customizer_panel );

			return $link_to_customizer_panel;
		}

		/**
		 * Customizer Panel ID.
		 *
		 * @return string
		 */
		public function customizer_panel_id() {
			return $this->plugin_text_domain_underscores() . '_panel';
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

		/**
		 * @TODO: Example: Get data about all social networks.
		 *
		 * @param string $retrieve The relevant data to retrieve about each social network.
		 *
		 * @return array
		 */
		public function get_social_networks_data( $retrieve = '' ) {
			$networks = [
				[
					'key'   => 'facebook',
					'name'  => esc_html__( 'Facebook', $this->plugin_text_domain ),
					'color' => '#3b5998',
				],
				[
					'key'   => 'twitter',
					'name'  => esc_html__( 'Twitter', $this->plugin_text_domain ),
					'color' => '#00aced',
				],
				[
					'key'   => 'pinterest',
					'name'  => esc_html__( 'Pinterest', $this->plugin_text_domain ),
					'color' => '#BD081C',
				],
				[
					'key'   => 'linkedin',
					'name'  => esc_html__( 'LinkedIn', $this->plugin_text_domain ),
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
		 * @TODO: Example: Get a social network's nice name.
		 *
		 * @param string $network
		 *
		 * @return string
		 */
		public function get_social_network_nice_name( $network ) {
			$customizer = new Customizer( $this );

			$array = $customizer->get_choices_social_networks();

			if ( array_key_exists( $network, $array ) ) {
				$result = $array[$network];
			} else {
				$result = '';
			}

			return $result;
		}

		/**
		 * @TODO: Example: Sanitize callback for Customizer: Social Networks.
		 *
		 * Check if what the user selected is valid. If yes, return it, else return setting's default.
		 *
		 * @param array|string         $value   Value that is passed by the Customizer.
		 * @param WP_Customize_Setting $setting The setting object.
		 *
		 * @return string|array
		 */
		public function sanitize_social_networks( $value, $setting ) {
			$customizer = new Customizer( $this );

			$result = $this->sanitize_multiple_values( $value, $customizer->get_choices_social_networks() );

			if ( ! empty( $result ) ) {
				return $value;
			} else {
				return $setting->default;
			}
		}

		/**
		 * Sanitize an array (or comma-separated string) of values, only allowing values that are in the array
		 * of allowable values.
		 *
		 * Does not support multidimensional arrays but could be altered to.
		 *
		 * @param array|string $values
		 * @param array        $allowables
		 *
		 * @return string|array
		 */
		public function sanitize_multiple_values( $values, $allowables ) {
			$orig_values = $values;

			// handle booleans, such as single checkbox value
			if ( is_bool( $values ) ) {
				return $values;
			}

			if ( is_string( $orig_values ) ) {
				$values = json_decode( $values, true );
			}

			if ( ! is_array( $allowables ) ) {
				// return as same type that came in
				if ( is_string( $orig_values ) ) {
					return '';
				} else {
					return [];
				}
			}

			if ( ! is_array( $values ) ) {
				$values_array = (array) explode( ',', $values );
			} else {
				$values_array = (array) $values;
			}

			$result = [];

			foreach ( $values_array as $k => $v ) {
				if ( array_key_exists( $k, $allowables ) ) {

					// stick with JSON encoded format for booleans instead of 1/0 from PHP
					if ( is_bool( $v ) ) {
						if ( $v ) {
							$v = 'true';
						} else {
							$v = 'false';
						}
					}

					$result[$k] = sanitize_text_field( $v );
				}
			}

			// return as same type that came in
			if ( is_string( $orig_values ) ) {
				$result = json_encode( $result, true );
			}

			return $result;
		}

		/**
		 * @TODO: Example: Sanitize callback for Customizer: Post Types.
		 *
		 * Check if what the user selected is valid. If yes, return it, else return setting's default.
		 *
		 * @param array                $value   Value that is passed by the Customizer.
		 * @param WP_Customize_Setting $setting The setting object.
		 *
		 * @return string|array
		 */
		public function sanitize_post_types( $value, $setting ) {
			$result = $this->sanitize_multiple_values( $value, $this->get_public_post_types() );

			if ( ! empty( $result ) ) {
				return $value;
			} else {
				return $setting->default;
			}
		}

		/**
		 * Get the public Post Types, alpha-sorted by their labels.
		 *
		 * @see get_post_types()
		 *
		 * @return array
		 */
		public function get_public_post_types() {
			$result = get_post_types( [ 'public' => true ], 'object' );

			uasort(
				$result, function ( $a, $b ) {
				return strcmp( $a->label, $b->label );
			}
			);

			return $result;
		}

		/**
		 * Detect the current URL from the WP Request.
		 *
		 * @return string
		 */
		private function get_current_url() {
			global $wp;

			$current_url = home_url( add_query_arg( [], $wp->request ) );

			return $current_url;
		}


		/**
		 * Check if one string ends with another string.
		 *
		 * @param $subject
		 * @param $search_for
		 *
		 * @return bool True if subject ends with searched string, else false.
		 */
		public function string_ends_with( $subject, $search_for ) {
			if (
				! is_string( $subject )
				|| ! is_string( $search_for )
			) {
				return false;
			}

			$subject_length = strlen( $subject );

			$search_for_length = strlen( $search_for );

			if ( $search_for_length > $subject_length ) {
				return false;
			}

			return substr_compare( $subject, $search_for, $subject_length - $search_for_length, $search_for_length ) === 0;
		}
	}
}