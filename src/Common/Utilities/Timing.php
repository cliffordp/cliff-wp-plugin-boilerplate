<?php

namespace WpPluginName\Common\Utilities;

use DateTimeImmutable;
use DateTimeZone;
use Exception;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Timing::class ) ) {
	class Timing {

		/**
		 * Get the time zone string from WordPress General Settings, with America/Chicago as the fallback.
		 *
		 * @link https://www.php.net/manual/timezones.php Ideally, your WordPress time zone is already one of these.
		 *                                                If not, at least make sure that your fallback is.
		 *
		 * @see  timezone_identifiers_list()
		 *
		 * @param string $fallback The valid PHP time zone string to use if WordPress is not set to a valid one.
		 *
		 * @return string
		 */
		public function get_php_time_zone_string_from_wp( string $fallback = 'America/Chicago' ): string {
			$time_zone = get_option( 'timezone_string' ); // could return NULL

			if ( ! in_array( $time_zone, timezone_identifiers_list() ) ) {
				$time_zone = $fallback;
			}

			return $time_zone;
		}

		/**
		 * Get the PHP DateTimeImmutable() object for the current time in the time zone from WordPress settings.
		 *
		 * If WordPress setting is not a valid PHP time zone, fallback to Chicago (Central Time).
		 *
		 * @return DateTimeImmutable|false
		 */
		public function get_current_time_wp_tz_date_object() {
			$time_zone = $this->get_php_time_zone_string_from_wp();

			try {
				$now = new DateTimeImmutable( 'now', new DateTimeZone( $time_zone ) );
			}
			catch ( Exception $exception ) {
				$now = false;
			}

			return $now;
		}

		/**
		 * Given a timestamp from UTC time (such as from Toolset Types), get its DateTimeImmutable(),
		 * then convert it to our site's time zone, then return it in the specified format (string),
		 * or as the DateTimeImmutable() result if no format is specified.
		 *
		 * @link https://secure.php.net/manual/function.date.php Allowed date() formats.
		 *
		 * @param int    $utc_timestamp An integer timestamp in UTC time zone.
		 * @param string $format        A valid PHP date() format.
		 *
		 * @return DateTimeImmutable|string|bool
		 */
		public function get_datetime_from_utc_timestamp( int $utc_timestamp, string $format = '' ) {
			if (
				empty( $utc_timestamp )
				|| ! is_int( $utc_timestamp )
			) {
				return '';
			}

			try {
				$utc_datetime = ( new DateTimeImmutable() )->setTimezone( new DateTimeZone( 'UTC' ) )->setTimestamp( $utc_timestamp );
			}
			catch ( Exception $e ) {
				return '';
			}

			$result = $utc_datetime->setTimezone( new DateTimeZone( $this->get_php_time_zone_string_from_wp() ) );

			if ( ! empty( $format ) ) {
				$result = $result->format( $format ); // could return false
			}

			return $result;
		}

		/**
		 * Get the PHP DateTimeImmutable() object for the current time in the time zone from WordPress settings.
		 *
		 * If WordPress setting is not a valid PHP time zone, fallback to Europe/Amsterdam.
		 *
		 * @return DateTimeImmutable|bool
		 */
		public function get_current_datetime_wp_tz() {
			try {
				return new DateTimeImmutable( 'now', new DateTimeZone( $this->get_php_time_zone_string_from_wp() ) );
			}
			catch ( Exception $e ) {
				return false;
			}
		}

		/**
		 * Get the start or end time of a DateTimeImmutable object, as a DateTimeImmutable object or in a given format.
		 *
		 * @param DateTimeImmutable $datetime
		 * @param bool              $start
		 * @param bool              $format
		 *
		 * @return bool|DateTimeImmutable|string
		 */
		public function get_start_end_of_day_from_datetime( DateTimeImmutable $datetime, bool $start = true, bool $format = false ) {
			if ( ! $datetime instanceof DateTimeImmutable ) {
				return false;
			}

			$new = $datetime->format( 'Y-m-d' );

			if ( empty( $start ) ) {
				$new .= ' 23:59:59';
			} else {
				$new .= ' 00:00:00';
			}

			try {
				$result = new DateTimeImmutable( $new, $datetime->getTimezone() );
			}
			catch ( Exception $e ) {
				return false;
			}

			if ( ! empty( $format ) ) {
				$result = $result->format( $format );
			}

			return $result;
		}

		/**
		 * Get the difference (in minutes) that End DateTimeImmutable is after Start DateTimeImmutable.
		 *
		 * @param DateTimeImmutable $start_datetime
		 * @param DateTimeImmutable $end_datetime
		 *
		 * @return false|float|int Number of minutes (int|float). False if both are not DateTimeImmutable or if End is before Start.
		 */
		public function get_minutes_diff_between_two_datetimes( DateTimeImmutable $start_datetime, DateTimeImmutable $end_datetime ) {
			if (
				! $start_datetime instanceof DateTimeImmutable
				|| ! $end_datetime instanceof DateTimeImmutable
				|| $start_datetime > $end_datetime
			) {
				return false;
			}

			$diff = $start_datetime->diff( $end_datetime );

			// There is always 60 seconds in a minute and 60 minutes in an hour, but there isn't always 24 hours in a day (due to DST).
			// https://stackoverflow.com/a/23675286/893907
			try {
				$total_seconds = ( new DateTimeImmutable() )->setTimeStamp( 0 )->add( $diff )->getTimeStamp();
			}
			catch ( Exception $e ) {
				return false;
			}

			$total_minutes = $total_seconds / 60;

			return $total_minutes;
		}

		/**
		 * Get the minutes duration difference between two strings in 24 hour time format (no date or seconds allowed).
		 *
		 * @param string $start_24_format
		 * @param string $end_24_format
		 *
		 * @return false|float|int Numeric amount. False if not valid 24 hour time format (##:##) or End is before Start.
		 */
		public function get_minutes_diff_between_two_times_same_day( string $start_24_format, string $end_24_format ) {
			if (
				false === $this->is_valid_24_hour_format_time_string( $start_24_format )
				|| false === $this->is_valid_24_hour_format_time_string( $end_24_format )
			) {
				return false;
			}

			$time_zone = $this->get_php_time_zone_string_from_wp();
			$shared    = 'January 1, 1970 ';

			$start = $shared . $start_24_format;
			$end   = $shared . $end_24_format;

			try {
				$start_datetime = new DateTimeImmutable( $start, new DateTimeZone( $time_zone ) );
			}
			catch ( Exception $e ) {
				return false;
			}

			try {
				$end_datetime = new DateTimeImmutable( $end, new DateTimeZone( $time_zone ) );
			}
			catch ( Exception $e ) {
				return false;
			}

			$total_minutes = $this->get_minutes_diff_between_two_datetimes( $start_datetime, $end_datetime );

			if ( empty( $total_minutes ) ) {
				return false;
			}

			return $total_minutes;
		}

		/**
		 * Determine if a string is in a 24 hour time format, such as 00:00, 17:30, or 23:59.
		 *
		 * @link https://regex101.com/r/YPkRtl/1 Regex test for ##:## format.
		 * @link https://regex101.com/r/x4wE80/1 Regex test for ##:##:## format.
		 *
		 * @param string $time_string
		 * @param bool   $allow_seconds False matches ##:## but not ##:##:##. True allows the third ":##" as optional.
		 *
		 * @return bool
		 */
		public function is_valid_24_hour_format_time_string( string $time_string, bool $allow_seconds = false ): bool {
			if ( ! is_string( $time_string ) ) {
				return false;
			}

			if ( empty( $allow_seconds ) ) {
				return (bool) preg_match( '/^(([0-1][0-9]|2[0-3]):[0-5][0-9])$/', $time_string );
			} else {
				return (bool) preg_match( '/^(([0-1][0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?)$/', $time_string );
			}
		}
	}
}
