<?php

namespace WpPluginName\Core;

use WpPluginName\Admin as Admin;
use WpPluginName\Common as Common;
use WpPluginName\Customizer as Customizer;
use WpPluginName\Frontend as Frontend;
use WpPluginName\Shortcodes as Shortcodes;
use WpPluginName\PluginData as PluginData;

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
		 * Initialize and define the core functionality of the plugin.
		 */
		public function __construct() {
			$this->load_dependencies();
			$this->set_locale();
			$this->define_common_hooks();
			$this->define_customizer_hooks();
			$this->define_admin_hooks();
			$this->define_public_hooks();
			$this->register_shortcodes();
		}

		/**
		 * Loads the following required dependencies for this plugin.
		 *
		 * - Loader - Orchestrates the hooks of the plugin.
		 * - I18n - Defines internationalization functionality.
		 * - Admin - Defines all hooks for the admin area.
		 * - Frontend - Defines all hooks for the public side of the site.
		 */
		private function load_dependencies(): void {
			$this->loader = new Loader();
		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the I18n class in order to set the domain and to register the hook
		 * with WordPress.
		 */
		private function set_locale(): void {
			$i18n = new I18n();

			$this->loader->add_action( 'plugins_loaded', $i18n, 'load_plugin_textdomain' );
		}

		/**
		 * Register all of the hooks related to both the admin area and the public-facing functionality of the plugin.
		 */
		private function define_common_hooks(): void {
			// $common = new Common\Common();
			// Example: $this->loader->add_filter( 'gform_currencies', $common, 'gf_currency_usd_whole_dollars', 50 );

			// Settings Fields must not be behind an `is_admin()` check, since it's too late.
			$settings = new Common\Settings\Main();

			/**
			 * If we don't use the 'rest_api_init' hook, things like getting public post types won't include custom
			 * post types, even if 'show_in_rest' is true. This is also why registering the settings cannot be called
			 * only if is_admin().
			 */
			$this->loader->add_action( 'admin_init', $settings, 'register_settings' );
			$this->loader->add_action( 'rest_api_init', $settings, 'register_settings' );
		}

		/**
		 * Register all of the hooks related to the WordPress Customizer.
		 *
		 * Customizer must not be within Admin or Frontend or else it won't load properly.
		 * We could have included in Common, since it is the same loading logic, but we separate it out for sanity.
		 */
		private function define_customizer_hooks(): void {
			$http = new Common\Utilities\Http();

			// Avoid REST and Cron.
			if (
				! $http->current_request_is( 'admin' )
				&& ! $http->current_request_is( 'frontend' )
			) {
				return;
			}

			$customizer = new Customizer\Customizer();

			$this->loader->add_action( 'customize_register', $customizer, 'customizer_options' );
		}

		/**
		 * Register all of the hooks related to the admin area functionality of the plugin.
		 * Also works during Ajax.
		 */
		private function define_admin_hooks(): void {
			$http = new Common\Utilities\Http();

			if ( ! $http->current_request_is( 'admin' ) ) {
				return;
			}

			$assets = new Admin\Assets();

			// Enqueue plugin's admin assets
			$this->loader->add_action( 'admin_enqueue_scripts', $assets, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $assets, 'enqueue_scripts' );

			$settings = new Admin\Settings\Main();

			// Plugin action links
			$this->loader->add_filter(
				'plugin_action_links_' . PluginData::plugin_basename(),
				$settings,
				'customize_action_links'
			);

			// Admin menu
			$this->loader->add_action( 'admin_menu', $settings, 'add_plugin_admin_menu' );
		}

		/**
		 * Register all of the hooks related to the public-facing (is not admin or is Ajax) functionality of the plugin.
		 */
		private function define_public_hooks(): void {
			$http = new Common\Utilities\Http();

			if ( ! $http->current_request_is( 'frontend' ) ) {
				return;
			}

			$assets = new Frontend\Assets();

			// Enqueue plugin's front-end assets
			$this->loader->add_action( 'wp_enqueue_scripts', $assets, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $assets, 'enqueue_scripts' );
		}

		/**
		 * Register all of the shortcodes.
		 */
		private function register_shortcodes(): void {
			( new Shortcodes\Manage() )->register_all_shortcodes();
		}

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 */
		public function run(): void {
			$this->loader->run();
		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @return Loader Orchestrates the hooks of the plugin.
		 */
		public function get_loader(): Loader {
			return $this->loader;
		}
	}
}
