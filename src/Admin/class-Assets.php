<?php

namespace WP_Plugin_Name\Admin;

use WP_Plugin_Name\Common\Assets as Common_Assets;
use WP_Plugin_Name\Plugin_Data as Plugin_Data;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Assets::class ) ) {
	/**
	 * Enqueues the global admin assets.
	 *
	 * Settings Page adds additional.
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
		 * Register and enqueue the stylesheets for every admin area.
		 *
		 * Must register before we enqueue!
		 */
		public function enqueue_styles(): void {
			$file_name = 'admin';

			$registered = $this->common_assets->register_style( $file_name );

			if ( $registered ) {
				$this->common_assets->enqueue_style( $file_name );
			}
		}

		/**
		 * Register and enqueue the JavaScript for every admin area.
		 *
		 * Must register before we enqueue!
		 */
		public function enqueue_scripts(): void {
			$file_name = 'admin';

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