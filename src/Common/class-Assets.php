<?php

namespace WP_Plugin_Name\Common;

use WP_Plugin_Name\Plugin_Data as Plugin_Data;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Assets::class ) ) {
	/**
	 * Register and/or enqueue styles and scripts.
	 *
	 * @todo Add ability to register, not just enqueue.
	 */
	class Assets {

		/**
		 * Get the base *URL* of our assets directory, with a trailing slash.
		 *
		 * @return string
		 */
		public static function get_assets_url_base(): string {
			return Plugin_Data::plugin_dir_url() . 'dist/';
		}

		/**
		 * Get the base *path* to our assets directory, with a trailing slash.
		 *
		 * @return string
		 */
		public static function get_assets_path_base(): string {
			return Plugin_Data::plugin_dir_path() . 'dist/';
		}

		/**
		 * Prefix a style/script handle with our text domain, to be consistent while being unique.
		 *
		 * We don't keep a list of each handle to prevent non-uniques, and we probably shouldn't, due to things like
		 * `wp_localize_script()` needing the same handle as the enqueue.
		 *
		 * @param string $handle
		 *
		 * @return string
		 */
		public static function get_asset_handle( string $handle ): string {
			return Plugin_Data::plugin_text_domain() . '-' . $handle;
		}

		/**
		 * Enqueue our stylesheet from the 'dist' directory with a prefixed handle.
		 *
		 * @param string $file_name Name of file that exists in the 'dist' directory, without the file extension.
		 *                          Examples: 'frontend' or 'admin-settings'.
		 * @param string $handle    Will fallback to $file_name before getting prefixed.
		 * @param string $version   Manually set if you wish, else will be the Plugin's version.
		 * @param array  $deps
		 * @param string $media
		 */
		public function enqueue_style(
			string $file_name,
			string $handle = '',
			array $deps = [],
			string $version = '',
			string $media = 'all'
		): void {
			if ( empty( $handle ) ) {
				$handle = $file_name;
			}

			wp_enqueue_style(
				self::get_asset_handle( $handle ),
				$this->get_file_url( $file_name, 'css' ),
				$deps,
				$this->get_version( $version ),
				$media
			);
		}

		/**
		 * Enqueue our script from the 'dist' directory with a prefixed handle.
		 *
		 * @param string $file_name Name of file that exists in the 'dist' directory, without the file extension.
		 *                          Examples: 'frontend' or 'admin-settings'.
		 * @param string $handle    Will fallback to $file_name before getting prefixed.
		 * @param array  $deps      This function will auto-include the 'dist' directory's PHP file as a dependency.
		 * @param string $version   Manually set if you wish, else will be the Plugin's version.
		 * @param bool   $in_footer
		 */
		public function enqueue_script(
			string $file_name,
			string $handle = '',
			array $deps = [],
			string $version = '',
			bool $in_footer = true
		): void {
			if ( empty( $handle ) ) {
				$handle = $file_name;
			}

			wp_enqueue_script(
				self::get_asset_handle( $handle ),
				$this->get_file_url( $file_name, 'js' ),
				$deps,
				$this->get_version( $version ),
				$in_footer
			);
		}

		/**
		 * Determine if SCRIPT_DEBUG is true or false.
		 *
		 * @return bool
		 */
		public function is_script_debug() {
			if (
				defined( 'SCRIPT_DEBUG' )
				&& SCRIPT_DEBUG
			) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Get the asset's version, based on SCRIPT_DEBUG (cache buster), or manually-set, or the plugin's version.
		 *
		 * @param string $version
		 *
		 * @return string
		 */
		private function get_version( string $version = '' ) {
			if ( $this->is_script_debug() ) {
				// Cache busting while debugging.
				$v = time();
			} elseif ( ! empty( $version ) ) {
				// Manually-set version.
				$v = $version;
			} else {
				// Plugin's version.
				$v = Plugin_Data::plugin_version();
			}

			return (string) $v;
		}

		/**
		 * Given an asset's file name (e.g. 'frontend') and file extension (e.g. '.css'), get its full URL from the
		 * 'dist' directory. Does not check if file exists.
		 *
		 * @param string $file_name
		 * @param string $file_extension
		 *
		 * @return string
		 */
		public function get_file_url(
			string $file_name,
			string $file_extension
		) {
			return sprintf(
				'%s%s.%s',
				self::get_assets_url_base(),
				$file_name,
				$file_extension
			);
		}

		/**
		 * Given an asset's file name (e.g. 'frontend') and file extension (e.g. '.css'), get its full file path from
		 * the 'dist' directory. Does not check if file exists.
		 *
		 * @param string $file_name
		 * @param string $file_extension
		 *
		 * @return string
		 */
		public function get_file_path(
			string $file_name,
			string $file_extension
		) {
			return sprintf(
				'%s%s.%s',
				self::get_assets_path_base(),
				$file_name,
				$file_extension
			);
		}

	}
}