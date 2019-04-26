<?php

namespace WP_Plugin_Name\Admin;

use WP_Plugin_Name as NS;
use WP_Plugin_Name\Common\Settings as Common_Settings;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Settings::class ) ) {
	/**
	 * The admin-specific settings.
	 */
	class Settings {

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
		 * @param $links
		 *
		 * @return array
		 */
		public function add_action_links( $links ) {
			$mylinks = [
				'<a href="' . esc_url( $this->settings->get_main_settings_page_url() ) . '">' . $this->settings->get_settings_word() . '</a>',
			];

			return array_merge( $mylinks, $links );
		}

		/**
		 * Add the Settings page to the wp-admin menu.
		 */
		public function add_plugin_admin_menu() {
			add_options_page(
				NS\get_plugin_display_name(),
				NS\get_plugin_display_name(),
				$this->settings->common->required_capability(),
				$this->settings->get_settings_page_slug(),
				[ $this, 'settings_page' ]
			);
		}

		/**
		 * Outputs HTML for the plugin's Settings page.
		 */
		public function settings_page() {
			if ( ! current_user_can( $this->settings->common->required_capability() ) ) {
				wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', $this->settings->common->plugin_text_domain() ) );
			}

			$link_to_customizer_panel = $this->settings->get_link_to_customizer_panel();

			?>
			<div class="wrap">
				<h1><?php echo NS\get_plugin_display_name() . ' ' . $this->settings->get_settings_word();
					?></h1>

				<p><?php esc_html_e( "This plugin uses the WordPress Customizer to set its options.", $this->settings->common->plugin_text_domain() ); ?></p>
				<p><?php esc_html_e( "Click the button below to be taken directly to this plugin's section within the WordPress Customizer.", $this->settings->common->plugin_text_domain() ); ?></p>
				<p>
					<?php esc_html_e( "TODO: Add more text here", $this->settings->common->plugin_text_domain() ); ?>
				</p>
				<p>
					<a href="<?php echo esc_url( $link_to_customizer_panel ); ?>"
					   class="button-primary">
						<?php esc_html_e( 'Edit Plugin Settings in WP Customizer', $this->settings->common->plugin_text_domain() ) ?>
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