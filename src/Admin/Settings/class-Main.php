<?php

namespace WP_Plugin_Name\Admin\Settings;

use WP_Plugin_Name\Plugin_Data as Plugin_Data;
use WP_Plugin_Name\Common\Settings as Common_Settings;
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
		 * Get the Settings instance from Common.
		 *
		 * @var Common_Settings
		 */
		private $settings;

		/**
		 * Initialize the class and set its properties.
		 */
		public function __construct() {
			$this->settings = new Common_Settings();
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
				$this->settings->common->required_capability(),
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
			wp_enqueue_style(
				Plugin_Data::get_asset_handle( 'admin-settings' ),
				Plugin_Data::get_assets_url_base() . 'admin-settings.css',
				[
					'wp-components',
				],
				Plugin_Data::plugin_version(),
				'all'
			);

			// JS for our Settings Page.
			wp_enqueue_script(
				Plugin_Data::get_asset_handle( 'admin-settings' ),
				Plugin_Data::get_assets_url_base() . 'admin-settings.js',
				[
					'wp-i18n',
					'wp-api',
					'wp-components',
					'wp-element',
				],
				Plugin_Data::plugin_version(),
				true
			);

			wp_localize_script(
				Plugin_Data::get_asset_handle( 'admin-settings' ),
				'initialData', // Only loads when on the page so shouldn't be a conflicting name.
				[
					'pluginInfo' => [
						'name' => Plugin_Data::get_plugin_display_name(),
						'version' => Plugin_Data::plugin_version(),
					],
					'imagesUrl'     => Plugin_Data::plugin_dir_url() . 'src/Admin/images/',
					'opts'          => $this->settings->get_all_options(),
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
			if ( ! current_user_can( $this->settings->common->required_capability() ) ) {
				wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', Plugin_Data::plugin_text_domain() ) );
			}

			$link_to_customizer_panel = $this->settings->get_link_to_customizer_panel();

			?>
			<div class="wrap" id="settings-page">
				<?php printf( '<h1>%s</h1>', Plugin_Data::get_plugin_display_name() . ' ' . $this->settings->get_settings_word() ); ?>

				<p><?php esc_html_e( "This plugin uses the WordPress Customizer to set its options.", Plugin_Data::plugin_text_domain() ); ?></p>
				<p><?php esc_html_e( "Click the button below to be taken directly to this plugin's section within the WordPress Customizer.", Plugin_Data::plugin_text_domain() ); ?></p>
				<p>
					<?php esc_html_e( "TODO: Add more text here", Plugin_Data::plugin_text_domain() ); ?>
				</p>
				<p>
					<a href="<?php echo esc_url( $link_to_customizer_panel ); ?>"
					   class="button-primary">
						<?php esc_html_e( 'Edit Plugin Settings in WP Customizer', Plugin_Data::plugin_text_domain() ) ?>
					</a>
				</p>
				<br><br>
				<?php
				?>
			</div>
			<?php
		}

	}
}
