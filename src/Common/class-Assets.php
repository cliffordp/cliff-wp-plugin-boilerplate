<?php

namespace WP_Plugin_Name\Common;

use WP_Plugin_Name\Plugin_Data as Plugin_Data;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Assets::class ) ) {
	/**
	 * Enqueues the assets that should load at all times (Admin and Frontend).
	 */
	class Assets {

		/**
		 * Register the stylesheets that load all the time.
		 */
		public function enqueue_styles(): void {
			$ext = 'min.css';

			if (
				defined( 'SCRIPT_DEBUG' )
				&& SCRIPT_DEBUG
			) {
				$ext = 'css';
			}

			wp_enqueue_style( Plugin_Data::plugin_text_domain(), plugin_dir_url( __FILE__ ) . "css/style.$ext", [], Plugin_Data::plugin_version(), 'all' );
		}

		/**
		 * Register the JavaScript that should load all the time.
		 */
		public function enqueue_scripts(): void {
			$ext = 'min.js';

			if (
				defined( 'SCRIPT_DEBUG' )
				&& SCRIPT_DEBUG
			) {
				$ext = 'js';
			}

			wp_enqueue_script( Plugin_Data::plugin_text_domain(), plugin_dir_url( __FILE__ ) . "js/script.$ext", [ 'jquery' ], Plugin_Data::plugin_version(), false );
		}
	}
}
