<?php

namespace WP_Plugin_Name;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Plugin_Data::class ) ) {
	/**
	 * The basic information about this plugin, like its texts (text domain and display name) and file locations.
	 */
	class Plugin_Data {

		/**
		 * Get this plugin's version.
		 *
		 * @TODO Keep current with readme.txt header and changelog + plugin header.
		 *
		 * @return string
		 */
		public static function plugin_version() {
			return '1.0.0';
		}

		/**
		 * Get this plugin's required minimum version of PHP.
		 *
		 * Should match composer.json's `"require": { "php":...`
		 *
		 * @link https://wordpress.org/about/requirements/
		 * @link https://en.wikipedia.org/wiki/PHP#Release_history
		 *
		 * @return string
		 */
		public static function required_min_php_version() {
			return '7.1.0';
		}

		/**
		 * Get this plugin's text domain.
		 *
		 * Must match the plugin's main directory and its main PHP filename.
		 *
		 * @return string
		 */
		public static function plugin_text_domain() {
			return 'cliff-wp-plugin-boilerplate';
		}

		/**
		 * Get this plugin's text domain with underscores instead of hyphens.
		 *
		 * Used for saving options. Also useful for building namespaced hook names, class names, URLs, etc.
		 *
		 * @return string 'wp_plugin_name'
		 */
		public static function plugin_text_domain_underscores() {
			return str_replace( '-', '_', self::plugin_text_domain() );
		}

		/**
		 * Get the plugin's display name.
		 *
		 * Useful for headings, for example.
		 *
		 * @return string
		 */
		public static function get_plugin_display_name() {
			return esc_html_x( 'WordPress Plugin Boilerplate', 'Plugin name for display', self::plugin_text_domain() );
		}

		/**
		 * Get this plugin's directory path, relative to this file's location.
		 *
		 * This file should be in `/src` and we want one level above.
		 * Example: /app/public/wp-content/plugins/cliff-wp-plugin-boilerplate/
		 *
		 * @return string
		 */
		public static function plugin_dir_path() {
			return trailingslashit( realpath( __DIR__ . DIRECTORY_SEPARATOR . '..' ) );
		}

		/**
		 * Get this plugin's directory URL.
		 *
		 * Example: https://example.com/wp-content/plugins/cliff-wp-plugin-boilerplate/
		 *
		 * @return string
		 */
		public static function plugin_dir_url() {
			return plugin_dir_url( self::main_plugin_file() );
		}

		/**
		 * Get this plugin's basename.
		 *
		 * @return string 'cliff-wp-plugin-boilerplate/cliff-wp-plugin-boilerplate.php'
		 */
		public static function plugin_basename() {
			return plugin_basename( self::main_plugin_file() );
		}

		/**
		 * Get this plugin's directory relative to this file's location.
		 *
		 * This file should be in `/src` and we want two levels above.
		 * Example: /app/public/wp-content/plugins/
		 *
		 * @return string
		 */
		public static function all_plugins_dir() {
			return trailingslashit( realpath( self::plugin_dir_path() . '..' ) );
		}

		/**
		 * Get this plugin's main plugin file.
		 *
		 * WARNING: Assumes the file exists - so don't make an epic fail!!!
		 *
		 * @return string
		 */
		private static function main_plugin_file() {
			return self::plugin_dir_path() . self::plugin_text_domain() . '.php';
		}

	}
}