<?php

namespace WP_Plugin_Name\Core;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Deactivator' ) ) {
	/**
	 * Fired during plugin deactivation
	 *
	 * This class defines all code necessary to run during the plugin's deactivation.
	 **/
	class Deactivator {

		/**
		 * Short Description.
		 *
		 * Long Description.
		 */
		public static function deactivate() {
		}
	}
}