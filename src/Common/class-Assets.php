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
			return Plugin_Data::plugin_dir_url() . 'build/';
		}

		/**
		 * Get the base *path* to our assets directory, with a trailing slash.
		 *
		 * @return string
		 */
		public static function get_assets_path_base(): string {
			return Plugin_Data::plugin_dir_path() . 'build/';
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
		 * Enqueue our stylesheet from the 'build' directory with a prefixed handle.
		 *
		 * @param string $file_name Name of file that exists in the 'build' directory, without the file extension.
		 *                          Examples: 'frontend' or 'admin-settings'.
		 * @param string $handle    Will fallback to $file_name before getting prefixed.
		 * @param array  $deps
		 * @param string $media
		 */
		public function enqueue_style(
			string $file_name,
			string $handle = '',
			array $deps = [],
			string $media = 'all'
		): void {
			if ( empty( $handle ) ) {
				$handle = $file_name;
			}

			wp_enqueue_style(
				self::get_asset_handle( $handle ),
				$this->get_file_url( $file_name, 'css' ),
				$deps,
				Plugin_Data::plugin_version(),
				$media
			);
		}

		/**
		 * Enqueue our script from the 'build' directory with a prefixed handle and including the PHP file from 'build'.
		 *
		 * @link https://github.com/WordPress/gutenberg/blob/master/packages/dependency-extraction-webpack-plugin/README.md#wordpress
		 *
		 * @param string $file_name Name of file that exists in the 'build' directory, without the file extension.
		 *                          Examples: 'frontend' or 'admin-settings'.
		 * @param string $handle    Will fallback to $file_name before getting prefixed.
		 * @param array  $deps      This function will auto-include the 'build' directory's PHP file as a dependency.
		 * @param bool   $in_footer
		 */
		public function enqueue_script(
			string $file_name,
			string $handle = '',
			array $deps = [],
			bool $in_footer = true
		): void {
			if ( empty( $handle ) ) {
				$handle = $file_name;
			}

			$build_info = $this->get_build_script_asset_info( $file_name );

			wp_enqueue_script(
				self::get_asset_handle( $handle ),
				$this->get_file_url( $file_name, 'js' ),
				array_merge( $deps, $build_info['dependencies'] ),
				$build_info['version'],
				$in_footer
			);
		}

		/**
		 * Get the PHP file from the 'build' directory, if it exists, else fallback to the plugin's version number.
		 *
		 * @param string $file_name
		 *
		 * @return array
		 */
		private function get_build_script_asset_info( string $file_name ): array {
			// Only do this if you choose to have a single entry point that bundles all your files, which this boilerplate does not do by default.
			// $script_asset_path = $this->get_asset_php_path( $file_name );

			if (
				! empty( $script_asset_path )
				&& file_exists( $script_asset_path )
			) {
				$script_asset_info = require( $script_asset_path );
			} else {
				$script_asset_info = [
					'dependencies' => [],
					'version'      => Plugin_Data::plugin_version(), // Could do: filemtime( $file_path ),
				];
			}

			return $script_asset_info;
		}

		/**
		 * Given an asset's file name (e.g. 'frontend') and file extension (e.g. '.css'), get its full URL from the
		 * 'build' directory. Does not check if file exists.
		 *
		 * @param string $file_name
		 * @param string $file_extension
		 *
		 * @return string
		 */
		private function get_file_url(
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
		 * the 'build' directory. Does not check if file exists.
		 *
		 * @param string $file_name
		 * @param string $file_extension
		 *
		 * @return string
		 */
		private function get_file_path(
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

		/**
		 * Given a file name, get the accompanying `___.asset.php` file's path.
		 *
		 * Only applicable for JavaScript files, since they need to enqueue the defined dependency(ies).
		 *
		 * @param string $file_name
		 *
		 * @return string
		 */
		private function get_asset_php_path( string $file_name ) {
			return sprintf(
				'%s%s.asset.php',
				self::get_assets_path_base(),
				$file_name
			);
		}

	}
}