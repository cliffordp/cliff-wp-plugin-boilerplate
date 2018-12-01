<?php

namespace WP_Plugin_Name\Frontend;

use WP_Plugin_Name\Common as Common;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing functionality of the plugin.
 *
 * Enqueues the public-facing stylesheet and JavaScript and other front-end stuff.
 *
 * @link  https://www.example.com/
 * @since 1.0.0
 */
class Frontend {

	/**
	 * The text domain of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_text_domain The text domain of this plugin.
	 */
	private $plugin_text_domain;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Get the Common instance.
	 *
	 * @var \WP_Plugin_Name\Common\Common
	 */
	private $common;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct() {
		$this->plugin_text_domain = \WP_Plugin_Name\PLUGIN_TEXT_DOMAIN;
		$this->version            = \WP_Plugin_Name\PLUGIN_VERSION;
		$this->common             = \WP_Plugin_Name\Common\Common::get_instance();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
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

		wp_enqueue_style( $this->plugin_text_domain, plugin_dir_url( __FILE__ ) . 'css/wp-plugin-name-frontend.css', [], $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
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

		wp_enqueue_script( $this->plugin_text_domain, plugin_dir_url( __FILE__ ) . 'js/wp-plugin-name-frontend.js', [ 'jquery' ], $this->version, false );
	}
}