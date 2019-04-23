<?php

namespace WP_Plugin_Name\Core;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Plugin_Name\Core\Activator' ) ) {
	/**
	 * Fired during plugin activation
	 *
	 * This class defines all code necessary to run during the plugin's activation.
	 **/
	class Activator {

		/**
		 * Short Description.
		 *
		 * Long Description.
		 */
		public static function activate() {
		}
	}
}