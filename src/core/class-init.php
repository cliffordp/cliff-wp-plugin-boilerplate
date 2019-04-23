<?php

namespace WP_Plugin_Name\Core;

use WP_Plugin_Name as NS;
use WP_Plugin_Name\Admin as Admin;
use WP_Plugin_Name\Common as Common;
use WP_Plugin_Name\Customizer as Customizer;
use WP_Plugin_Name\Frontend as Frontend;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Init::class ) ) {
	/**
	 * The core plugin class.
	 * Defines internationalization, admin-specific hooks, and public-facing site hooks.
	 */
	class Init {

		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @var      Loader $loader Maintains and registers all hooks for the plugin.
		 */
		protected $loader;

		/**
		 * The unique identifier of this plugin.
		 *
		 * @var      string $plugin_base_name The string used to uniquely identify this plugin.
		 */
		protected $plugin_basename;

		/**
		 * The current version of the plugin.
		 *
		 * @var      string $version The current version of the plugin.
		 */
		protected $version;

		/**
		 * The text domain of the plugin.
		 *
		 * @var      string $version The current version of the plugin.
		 */
		protected $plugin_text_domain;

		/**
		 * Initialize and define the core functionality of the plugin.
		 */
		public function __construct() {
			$this->version            = NS\PLUGIN_VERSION;
			$this->plugin_basename    = NS\PLUGIN_BASENAME;
			$this->plugin_text_domain = NS\PLUGIN_TEXT_DOMAIN;

			$this->load_dependencies();
			$this->set_locale();
			$this->define_common_hooks();
			$this->define_customizer_hooks();
			$this->define_admin_hooks();
			$this->define_public_hooks();
		}

		/**
		 * Loads the following required dependencies for this plugin.
		 *
		 * - Loader - Orchestrates the hooks of the plugin.
		 * - Internationalization_I18n - Defines internationalization functionality.
		 * - Admin - Defines all hooks for the admin area.
		 * - Frontend - Defines all hooks for the public side of the site.
		 */
		private function load_dependencies() {
			$this->loader = new Loader();
		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the Internationalization_I18n class in order to set the domain and to register the hook
		 * with WordPress.
		 */
		private function set_locale() {
			$plugin_i18n = new Internationalization_I18n( $this->plugin_text_domain );

			$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
		}

		/**
		 * Get Common so we can insert it into each class that depends on it.
		 *
		 * @return Common\Common
		 */
		private function get_common() {
			return new Common\Common();
		}

		/**
		 * Register all of the hooks related to both the admin area and the
		 * public-facing functionality of the plugin.
		 */
		private function define_common_hooks() {
			$plugin_common = $this->get_common();

			$this->register_shortcodes();

			// Example: $this->loader->add_filter( 'gform_currencies', $plugin_common, 'gf_currency_usd_whole_dollars', 50 );
		}

		/**
		 * Register all of the shortcodes.
		 */
		private function register_shortcodes() {
			$plugin_common = $this->get_common();

			// Register all of the shortcode classes
			$shortcode_namespace = 'WP_Plugin_Name\\Shortcodes\\';

			foreach ( $plugin_common->shortcode_classes as $shortcode_class ) {
				$shortcode_class = $shortcode_namespace . $shortcode_class;
				if (
					! class_exists( $shortcode_class )
					|| ! is_subclass_of( $shortcode_class, $shortcode_namespace . 'Shortcode' )
				) {
					continue;
				}

				$shortcode = new $shortcode_class;

				add_shortcode( $shortcode->get_tag(), [ $shortcode, 'process_shortcode' ] );
			}
		}

		/**
		 * Register all of the hooks related to the WordPress Customizer.
		 *
		 * Customizer must not be within Admin or Frontend or else it won't load properly.
		 * We could have included in Common, since it is the same loading logic, but we separate it out for sanity.
		 */
		private function define_customizer_hooks() {
			$plugin_common = $this->get_common();

			$plugin_customizer = new Customizer\Customizer( $plugin_common );

			$this->loader->add_action( 'customize_register', $plugin_customizer, 'customizer_options' );
		}

		/**
		 * Register all of the hooks related to the admin area functionality of the plugin.
		 * Also works during Ajax.
		 */
		private function define_admin_hooks() {
			if ( ! is_admin() ) {
				return;
			}

			$plugin_common = $this->get_common();

			$assets = new Admin\Assets( $plugin_common );

			// Enqueue plugin's admin assets
			$this->loader->add_action( 'admin_enqueue_scripts', $assets, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $assets, 'enqueue_scripts' );

			$settings = new Admin\Settings( $plugin_common );

			// Plugin action links
			$this->loader->add_filter( 'plugin_action_links_' . $this->plugin_basename, $settings, 'add_action_links' );

			// Admin menu
			$this->loader->add_action( 'admin_menu', $settings, 'add_plugin_admin_menu' );
		}

		/**
		 * Register all of the hooks related to the public-facing functionality of the plugin.
		 * Also works during Ajax.
		 */
		private function define_public_hooks() {
			if (
				is_admin()
				&& ! wp_doing_ajax()
			) {
				return;
			}

			$plugin_common = $this->get_common();

			$assets = new Frontend\Assets( $plugin_common );

			// Enqueue plugin's front-end assets
			$this->loader->add_action( 'wp_enqueue_scripts', $assets, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $assets, 'enqueue_scripts' );
		}

		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @return    string    The version number of the plugin.
		 */
		public function get_version() {
			return $this->version;
		}

		/**
		 * Retrieve the text domain of the plugin.
		 *
		 * @return    string    The text domain of the plugin.
		 */
		public function get_plugin_text_domain() {
			return $this->plugin_text_domain;
		}

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 */
		public function run() {
			$this->loader->run();
		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @return    Loader    Orchestrates the hooks of the plugin.
		 */
		public function get_loader() {
			return $this->loader;
		}
	}
}