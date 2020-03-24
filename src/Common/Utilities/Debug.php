<?php

declare( strict_types=1 );

namespace WpPluginName\Common\Utilities;

use WpPluginName\PluginData as PluginData;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Debug::class ) ) {
	/**
	 * The functionality shared between the admin and public-facing areas of the plugin.
	 *
	 * Useful for things like utilities or hooking into something that affects both back-end and front-end.
	 */
	class Debug {

		/**
		 * Write to the PHP error log and optionally send an email with the same message.
		 *
		 * Will not write to debug log file if either WP_DEBUG or WP_DEBUG_LOG are false but will still send the email,
		 * if applicable.
		 *
		 * @see wp_debug_mode() Just for reference how error logging is setup.
		 *
		 * @param string|array|object $log   The message or data to pass along.
		 * @param string              $email Either a single email address, a comma-separated list of email addresses,
		 *                                   or 'admin' to send to the admin email address.
		 */
		function output_to_log( $log = '', string $email = '' ): void {
			if (
				is_array( $log )
				|| is_object( $log )
			) {
				$message = print_r( $log, true );
			} else {
				$message = $log;
			}

			$trace = debug_backtrace();

			$who_called_me = '';

			if ( ! empty( $trace[1]['class'] ) ) {
				$who_called_me .= $trace[1]['class'] . '::';
			}

			if ( ! empty( $trace[1]['function'] ) ) {
				$who_called_me .= $trace[1]['function'] . '(): ';
			}

			$message = sprintf(
			// translators: 1: plugin display name, 2: name of debug function, 3: PHP End of Line character, 4: name of function triggering debug function, 5: debug log message
				esc_html__( '%1$s - Message from %2$s():%3$s%4$s%5$s', 'cliff-wp-plugin-boilerplate' ),
				PluginData::get_plugin_display_name(),
				__FUNCTION__,
				PHP_EOL,
				$who_called_me,
				$message
			);

			if ( 'admin' === $email ) {
				$email = get_bloginfo( 'admin_email' );
			}

			if ( ! empty( $email ) ) {
				// Prefix the email's message with the server's current time, since email send or receive may be delayed
				$email_message = sprintf( '[%s] %s', date( 'c' ), $message );

				$subject = sprintf( '%s - %s()', get_home_url(), __FUNCTION__ );

				// wp_mail() will convert comma-separated email string into an array for us
				$mail_sent = wp_mail( $email, $subject, $email_message );

				if ( $mail_sent ) {
					$message = esc_html_x( 'Email sent.', 'Successfully emailed from output_to_log()', 'cliff-wp-plugin-boilerplate' ) . ' ' . $message;
				} else {
					$message = esc_html_x( 'Email attempted but failed.', 'Unsuccessfully emailed from output_to_log()', 'cliff-wp-plugin-boilerplate' ) . ' ' . $message;
				}
			}

			error_log( $message );
		}
	}
}
