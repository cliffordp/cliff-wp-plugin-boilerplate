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
		 * Enqueue the stylesheets for the public-facing side of the site.
		 */
		public function enqueue_styles(): void {
			$this->common_assets->enqueue_style( 'frontend' );
		}

		/**
		 * Enqueue the scripts for the public-facing side of the site.
		 */
		public function enqueue_scripts(): void {
			$this->common_assets->enqueue_script(
				'frontend',
				'',
				[ 'jquery' ]
			);
		}
	}
}
