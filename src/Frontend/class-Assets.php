<?php

namespace WP_Plugin_Name\Frontend;

use WP_Plugin_Name\Plugin_Data as Plugin_Data;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Assets::class ) ) {
	/**
	 * Enqueues the public-facing assets.
	 */
	class Assets {

		/**
		 * Register the stylesheets for the public-facing side of the site.
		 */
		public function enqueue_styles(): void {
			wp_enqueue_style(
				Plugin_Data::get_asset_handle( 'frontend' ),
				Plugin_Data::get_assets_url_base() . 'frontend.css',
				[],
				Plugin_Data::plugin_version(),
				'all'
			);
		}

		/**
		 * Register the JavaScript for the public-facing side of the site.
		 */
		public function enqueue_scripts(): void {
			wp_enqueue_script(
				Plugin_Data::get_asset_handle( 'frontend' ),
				Plugin_Data::get_assets_url_base() . 'frontend.js',
				[ 'jquery' ],
				Plugin_Data::plugin_version(),
				false
			);
		}
	}
}
