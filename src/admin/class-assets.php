<?php

namespace WP_Plugin_Name\Admin;

use WP_Plugin_Name\Common\Common as Common;

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
		 * The Common instance.
		 *
		 * @var Common
		 */
		private $common;

		/**
		 * Initialize the class and set its properties.
		 */
		public function __construct() {
			$this->common = new Common();
		}

		/**
		 * Register the stylesheets for the admin area.
		 */
		public function enqueue_styles() {
			wp_enqueue_style( $this->common->plugin_text_domain(), plugin_dir_url( __FILE__ ) . 'css/style.css', [], $this->common->plugin_version(), 'all' );
		}

		/**
		 * Register the JavaScript for the admin area.
		 */
		public function enqueue_scripts() {
			wp_enqueue_script( $this->common->plugin_text_domain(), plugin_dir_url( __FILE__ ) . 'js/script.js', [ 'jquery' ], $this->common->plugin_version(), false );
		}
	}
}