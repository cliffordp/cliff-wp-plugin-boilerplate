<?php

namespace WP_Plugin_Name\Admin;

use WP_Plugin_Name\Plugin_Data as Plugin_Data;
use WP_Plugin_Name\Admin\Settings\Main as Settings;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Assets::class ) ) {
	/**
	 * Enqueues the admin-specific assets.
	 */
	class Assets {

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @param string $hook_suffix The current admin page.
		 */
		public function enqueue_styles( $hook_suffix ): void {
			// All Admin screens.
			wp_enqueue_style(
				Plugin_Data::get_asset_handle( 'admin-global' ),
				Plugin_Data::get_assets_url_base() . 'admin.css',
				[],
				Plugin_Data::plugin_version(),
				'all'
			);

			// If we're on our own Settings page.
			if ( ( new Settings() )->get_settings_page_id() === $hook_suffix ) {
				wp_enqueue_style(
					Plugin_Data::get_asset_handle( 'admin-settings' ),
					Plugin_Data::get_assets_url_base() . 'admin-settings.css',
					[
						'wp-components',
					],
					Plugin_Data::plugin_version(),
					'all'
				);
			}
		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @param string $hook_suffix The current admin page.
		 */
		public function enqueue_scripts( $hook_suffix ): void {
			// All Admin screens.
			wp_enqueue_script(
				Plugin_Data::get_asset_handle( 'admin-global' ),
				Plugin_Data::get_assets_url_base() . 'admin.js',
				[
					'jquery'
				],
				Plugin_Data::plugin_version(),
				true
			);

			// If we're on our own Settings page.
			if ( ( new Settings() )->get_settings_page_id() === $hook_suffix ) {
				wp_enqueue_script(
					Plugin_Data::get_asset_handle( 'admin-settings' ),
					Plugin_Data::get_assets_url_base() . 'admin-settings.js',
					[
						'wp-i18n',
						'wp-api',
						'wp-components',
						'wp-element',
					],
					Plugin_Data::plugin_version(),
					true
				);
			}
		}
	}
}