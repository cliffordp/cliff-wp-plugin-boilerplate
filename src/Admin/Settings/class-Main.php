<?php

namespace WP_Plugin_Name\Admin\Settings;

use WP_Plugin_Name\Common\Assets as Common_Assets;
use WP_Plugin_Name\Common\Settings\Choices;
use WP_Plugin_Name\Plugin_Data as Plugin_Data;
use WP_Plugin_Name\Common\Common as Common;
use WP_Plugin_Name\Common\Settings\Main as Common_Settings;
use WP_Screen;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Main::class ) ) {
	/**
	 * The admin-specific settings.
	 */
	class Main {

		/**
		 * The Common instance.
		 *
		 * @var Common
		 */
		public $common;

		/**
		 * @var Common_Assets
		 */
		var $common_assets;

		/**
		 * Get the Settings instance from Common.
		 *
		 * @var Common_Settings
		 */
		private $settings;

		/**
		 * Initialize the class and set its properties.
		 */
		public function __construct() {
			$this->common        = new Common();
			$this->common_assets = new Common_Assets();
			$this->settings      = new Common_Settings();
		}

		/**
		 * Add Settings link within Plugins List page.
		 *
		 * @param array $links
		 *
		 * @return array
		 */
		public function customize_action_links( array $links ): array {
			$link_to_settings_page = sprintf(
				'<a href="%s">%s</a>',
				esc_url( $this->settings->get_main_settings_page_url() ),
				$this->settings->get_settings_word()
			);

			$custom_action_links = [
				$link_to_settings_page,
			];

			return array_merge( $custom_action_links, $links );
		}

		/**
		 * Add the Settings page to the wp-admin menu.
		 */
		public function add_plugin_admin_menu(): void {
			$hook_suffix = add_options_page(
				Plugin_Data::get_plugin_display_name(),
				Plugin_Data::get_plugin_display_name(),
				$this->common->required_capability(),
				$this->settings->get_settings_page_slug(),
				[ $this, 'settings_page' ]
			);

			// Empty if insufficient permissions. We don't want our data put to page source in that case (but the action would not fire successfully anyway).
			if ( ! empty( $hook_suffix ) ) {
				add_action( "admin_print_scripts-{$hook_suffix}", [ $this, 'enqueue_settings_page_assets' ] );
			}
		}

		/**
		 * Register the JavaScript for the admin Settings Page area.
		 */
		public function enqueue_settings_page_assets() {
			// CSS for our Settings Page.
			$this->common_assets->enqueue_style(
				'admin-settings',
				'',
				[ 'wp-components' ]
			);

			// JS for our Settings Page.
			$this->common_assets->enqueue_script(
				'admin-settings',
				'',
				[
					'wp-api',
					'wp-i18n',
					'wp-components',
					'wp-element',
				]
			);

			$choices = new Choices();

			wp_localize_script(
				Common_Assets::get_asset_handle( 'admin-settings' ),
				'settingsData', // Only loads when on the page so shouldn't be a conflicting name.
				[
					// The CSS ID into which our React app inserts its content.
					'entryId'       => Plugin_Data::plugin_text_domain(),
					// Helpful for things like generating the <h1>.
					'pluginInfo'    => [
						'name'    => Plugin_Data::get_plugin_display_name(),
						'version' => Plugin_Data::plugin_version(),
					],
					// The root location where we store images specific to the Admin area.
					'imagesBaseUrl' => Plugin_Data::plugin_dir_url() . 'src/Admin/images/',
					'optionsInfo'   => [
						/**
						 * The option prefix, in case we want to do any filtering for just our stuff.
						 *
						 * @see \WP_Plugin_Name\Common\Settings\Main::get_option_prefix()
						 */
						'prefix'  => $this->settings->get_option_prefix(),
						// The list of each of our option names, regardless of 'show_in_rest'.
						'allKeys' => $this->settings->get_all_prefixed_options(),
					],
					'choicesFor'    => [
						'myRadio' => $choices->get_choices_post_types( 'RadioControl' ),
					],
				]
			);

		}

		/**
		 * Get the settings page ID, which is added as a body.class and is the $hook_suffix passed to 'admin_enqueue_scripts'.
		 *
		 * If you add your page as a submenu to anything other than "Settings", such as to be a top-level menu or
		 * submenu of a Custom Post Type, you'll need to edit the hard-coded part of this function.
		 *
		 * @see \get_plugin_page_hookname()
		 *
		 * @return string
		 */
		public function get_settings_page_id(): string {
			return 'settings_page_' . $this->settings->get_settings_page_slug();
		}

		/**
		 * Detect if we are on our Settings Page.
		 *
		 * @return bool
		 */
		public function is_our_settings_page(): bool {
			$current_screen = get_current_screen();

			if (
				$current_screen instanceof WP_Screen
				&& $this->get_settings_page_id() === $current_screen->base
			) {
				return true;
			}

			return false;
		}

		/**
		 * Outputs HTML for the plugin's Settings page.
		 */
		public function settings_page(): void {
			if ( ! current_user_can( $this->common->required_capability() ) ) {
				wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', Plugin_Data::plugin_text_domain() ) );
			}

			printf(
				'<div class="wrap" id="%s"></div>',
				Plugin_Data::plugin_text_domain()
			);
		}

	}
}
