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
		 * Register the stylesheets for every admin area.
		 */
		public function enqueue_styles(): void {
			$this->common_assets->enqueue_style( 'admin' );
		}

		/**
		 * Register the JavaScript for every admin area.
		 */
		public function enqueue_scripts(): void {
			$this->common_assets->enqueue_script(
				'admin',
				'',
				[ 'jquery' ]
			);
		}
	}
}