<?php

namespace WP_Plugin_Name\Admin;

use WP_Plugin_Name as NS;
use WP_Plugin_Name\Common\Common as Common;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Admin' ) ) {
	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the admin-specific stylesheet and JavaScript.
	 */
	class Admin {

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
		 * Register the stylesheets for the admin area.
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

		/**
		 * Add Settings link within Plugins List page.
		 *
		 * @param $links
		 *
		 * @return array
		 */
		public function add_action_links( $links ) {
			$mylinks = [
				'<a href="' . esc_url( $this->get_main_settings_page_url() ) . '">' . $this->get_settings_word() . '</a>',
			];

			return array_merge( $mylinks, $links );
		}

		/**
		 * The plugin's Settings page URL.
		 *
		 * @return string
		 */
		private function get_main_settings_page_url() {
			$url = 'options-general.php?page=' . $this->get_settings_page_slug();

			return admin_url( $url );
		}

		/**
		 * The plugin's Settings page slug.
		 *
		 * @return string
		 */
		private function get_settings_page_slug() {
			return $this->common->plugin_text_domain . '-settings';
		}

		/**
		 * The translatable "Settings" text.
		 *
		 * @return string
		 */
		private function get_settings_word() {
			return esc_html__( 'Settings', $this->common->plugin_text_domain );
		}

		/**
		 * Add the Settings page to the wp-admin menu.
		 */
		public function add_plugin_admin_menu() {
			add_options_page(
				NS\wp_plugin_name_get_plugin_display_name(),
				NS\wp_plugin_name_get_plugin_display_name(),
				$this->common->required_capability(),
				$this->get_settings_page_slug(),
				[ $this, 'settings_page' ]
			);
		}

		/**
		 * Outputs HTML for the plugin's Settings page.
		 */
		public function settings_page() {
			if ( ! current_user_can( $this->common->required_capability() ) ) {
				wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', $this->common->plugin_text_domain ) );
			}

			$link_to_customizer_panel = $this->common->get_link_to_customizer_panel();

			?>
			<div class="wrap">
				<h1><?php echo NS\wp_plugin_name_get_plugin_display_name() . ' ' . $this->get_settings_word();
					?></h1>

				<p><?php esc_html_e( "This plugin uses the WordPress Customizer to set its options.", $this->common->plugin_text_domain ); ?></p>
				<p><?php esc_html_e( "Click the button below to be taken directly to this plugin's section within the WordPress Customizer.", $this->common->plugin_text_domain ); ?></p>
				<p>
					<?php esc_html_e( "TODO: Add more text here", $this->common->plugin_text_domain ); ?>
				</p>
				<p>
					<a href="<?php echo esc_url( $link_to_customizer_panel ); ?>"
					   class="button-primary">
						<?php esc_html_e( 'Edit Plugin Settings in WP Customizer', $this->common->plugin_text_domain ) ?>
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