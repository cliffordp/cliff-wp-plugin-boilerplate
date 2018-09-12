<?php

namespace WP_Plugin_Name\Inc\Common;

/**
 * The functionality shared between the admin and public-facing areas
 * of the plugin.
 *
 * Useful for things like utilities or hooking into something that
 * affects both back-end and front-end.
 * Everything should be 'public static...' unless only useful as a hook within \WP_Plugin_Name\Inc\Core\Init::define_common_hooks()
 *
 * @link       http://example.com/
 * @since      1.0.0
 *
 * @author     Your Name or Your Company
 */
class Common {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string $plugin_name The ID of this plugin.
	 */
	public static $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string $version The current version of this plugin.
	 */
	public static $version;

	/**
	 * The text domain of this plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array $plugin_text_domain The text domain of this plugin.
	 */
	public static $plugin_text_domain;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since       1.0.0
	 *
	 * @param       string $plugin_name        The name of this plugin.
	 * @param       string $version            The version of this plugin.
	 * @param       string $plugin_text_domain The text domain of this plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin_text_domain ) {
		self::$plugin_name        = $plugin_name;
		self::$version            = $version;
		self::$plugin_text_domain = $plugin_text_domain;
	}

	/**
	 * Get the Post ID from the current page or from a passed integer or WP_Post object.
	 *
	 * Helper function for getting Post ID. Accepts null or a Post ID. If no $post object exists, returns false.
	 *
	 * @param null|int|/WP_Post $post
	 *
	 * @return int|false
	 */
	public static function post_id_helper( $post = null ) {
		if (
			! is_null( $post )
			&& is_numeric( $post ) > 0
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
				&& $GLOBALS['post'] instanceof \WP_Post
			) {
				return get_the_ID();
			} else {
				return false;
			}
		}
	}

	/**
	 * Get all of the "Post" post type's Post IDs that the currently logged-in user has authored.
	 *
	 * @return false|array False if user is not logged-in. Array (may be empty) if user is logged-in.
	 */
	public static function get_all_current_author_post_ids() {
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

		$result = new \WP_Query( $args );

		return $result->posts;
	}

	/**
	 * Get all of the "Post" post type's Post IDs.
	 *
	 * @return array
	 */
	public static function get_all_post_ids() {
		$args = [
			'fields'         => 'ids',
			'posts_per_page' => - 1,
			'post_type'      => 'post',
		];

		$result = new \WP_Query( $args );

		return $result->posts;
	}

	/**
	 * Get the PHP DateTime() object for the current time in the time zone from WordPress settings.
	 *
	 * If WordPress setting is not a valid PHP time zone, fallback to Chicago (Central Time).
	 *
	 * @return \DateTime|bool
	 */
	public static function get_current_time_wp_tz_date_object() {
		$time_zone = get_option( 'timezone_string' );

		if ( ! in_array( $time_zone, timezone_identifiers_list() ) ) {
			$time_zone = 'America/Chicago';
		}

		return new \DateTime( 'now', new \DateTimeZone( $time_zone ) );
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
	public static function round_up( $value, $places = 0 ) {
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
	public static function round_up_to_next( $value = 0, $interval = 0 ) {
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
}
