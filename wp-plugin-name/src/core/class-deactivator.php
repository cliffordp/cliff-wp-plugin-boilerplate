<?php

namespace WP_Plugin_Name\Core;

// If this file is called directly, abort.
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