<?php

namespace WP_Plugin_Name\Frontend;

use WP_Plugin_Name\Common\Common as Common;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Frontend' ) ) {
	/**
	 * The public-facing functionality of the plugin.
	 *
	 * Enqueues the public-facing stylesheet and JavaScript and other front-end stuff.
	 */
	class Frontend {

		/**
		 * Get the Common instance.
		 *
		 * @var Common
		 */
		private $common;

		/**
		 * Initialize the class and set its properties, with Common as a dependency.
		 *
		 * @param Common
		 */
		public function __construct( Common $common ) {
			$this->common = $common;
		}

		/**
		 * Register the stylesheets for the public-facing side of the site.
		 */
		public function enqueue_styles() {
			/**
			 * An instance of this class should be passed to the run() function
			 * defined in Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */

			wp_enqueue_style( $this->common->plugin_text_domain, plugin_dir_url( __FILE__ ) . 'css/wp-plugin-name-frontend.css', [], $this->common->version, 'all' );
		}

		/**
		 * Register the JavaScript for the public-facing side of the site.
		 */
		public function enqueue_scripts() {
			/**
			 * An instance of this class should be passed to the run() function
			 * defined in Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */

			wp_enqueue_script( $this->common->plugin_text_domain, plugin_dir_url( __FILE__ ) . 'js/wp-plugin-name-frontend.js', [ 'jquery' ], $this->common->version, false );
		}
	}
}