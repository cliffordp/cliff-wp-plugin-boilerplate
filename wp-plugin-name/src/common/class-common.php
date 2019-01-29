<?php

namespace WP_Plugin_Name\Common;

use DateTime;
use DateTimeZone;
use WP_Plugin_Name as NS;
use WP_Post;
use WP_Query;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The functionality shared between the admin and public-facing areas of the plugin.
 *
 * Useful for things like utilities or hooking into something that affects both back-end and front-end.
 *
 * @link  https://www.example.com/
 * @since 1.0.0
 */
class Common {

	/**
	 * Common's instance.
	 */
	private static $instance;

	/**
	 * The text domain of this plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string $plugin_text_domain The text domain of this plugin.
	 */
	public $plugin_text_domain;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string $version The current version of this plugin.
	 */
	public $version;

	/**
	 * Shortcodes to register.
	 *
	 * The shortcode tag must match the method name within Common (must be public and cannot be static).
	 *
	 * @since 1.0.0
	 */
	public $shortcodes = [
		'tk_request',
	];

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->plugin_text_domain = NS\PLUGIN_TEXT_DOMAIN;
		$this->version            = NS\PLUGIN_VERSION;
	}

	/**
	 * Get Common's instance.
	 *
	 * @link https://www.alainschlesser.com/singletons-shared-instances/ Maybe we could do better than using a singleton.
	 *
	 * @return Common
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Make it possible to unset/reset Common's instance.
	 */
	public function reset_instance() {
		self::$instance = null;
	}

	/**
	 * Get this plugin's text domain with underscores instead of hyphens.
	 *
	 * Useful for building dynamic hook names, class names, URLs, etc.
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
	 * @param string       $default The default value to return if the parameter is not present.
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
	 * @return DateTime|bool
	 */
	public function get_current_time_wp_tz_date_object() {
		$time_zone = get_option( 'timezone_string' );

		if ( ! in_array( $time_zone, timezone_identifiers_list() ) ) {
			$time_zone = 'America/Chicago';
		}

		return new DateTime( 'now', new DateTimeZone( $time_zone ) );
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
}