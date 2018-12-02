<?php

namespace WP_Plugin_Name\Admin;

use WP_Plugin_Name\Common\Common as Common;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link  https://www.example.com/
 * @since 1.0.0
 */
class Admin {

	/**
	 * Get the Common instance.
	 *
	 * @var Common
	 */
	private $common;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct() {
		$this->common = Common::get_instance();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->common->plugin_text_domain, plugin_dir_url( __FILE__ ) . 'css/wp-plugin-name-admin.css', [], $this->common->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/*
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->common->plugin_text_domain, plugin_dir_url( __FILE__ ) . 'js/wp-plugin-name-admin.js', [ 'jquery' ], $this->common->version, false );
	}
}