<?php

namespace WP_Plugin_Name\Frontend;

use WP_Plugin_Name\Common\Assets as Common_Assets;
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
		 * @var Common_Assets
		 */
		var $common_assets;

		public function __construct() {
			$this->common_assets = new Common_Assets();
		}

		/**
		 * Register and enqueue the stylesheets for the public-facing side of the site.
		 *
		 * Must register before we enqueue!
		 */
		public function enqueue_styles(): void {
			$file_name = 'frontend';

			$registered = $this->common_assets->register_style( $file_name );

			if ( $registered ) {
				$this->common_assets->enqueue_style( $file_name );
			}
		}

		/**
		 * Register and enqueue the scripts for the public-facing side of the site.
		 *
		 * Must register before we enqueue!
		 */
		public function enqueue_scripts(): void {
			global $wp_scripts;
			$file_name = 'frontend';

			$registered = $this->common_assets->register_script(
				$file_name,
				'',
				[ 'jquery' ]
			);

			if ( $registered ) {
				$this->common_assets->enqueue_script( $file_name );
			}
		}
	}
}
