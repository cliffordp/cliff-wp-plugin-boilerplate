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
		 * Register the JavaScript for the public-facing side of the site.
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
