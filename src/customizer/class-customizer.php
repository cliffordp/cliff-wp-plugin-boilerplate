<?php

namespace WP_Plugin_Name\Customizer;

use WP_Customize_Manager;
use WP_Plugin_Name\Common\Settings as Settings;
use WP_Plugin_Name\Common\Utilities as Utils;
use WP_Plugin_Name\Plugin_Data as Plugin_Data;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Customizer::class ) ) {
	/**
	 * Setup the WordPress Customizer functionality.
	 *
	 * Reusable utility functions (e.g. get all public post types), master/canonical arrays of data, getters, and
	 * sanitize-type functions should be in Common.
	 * Option lists for Customizer controls should be in this class, and it is advisable to prefix such functions
	 * with `get_choices...()`.
	 */
	class Customizer {

		/**
		 * Get the Settings instance from Common.
		 *
		 * @var Settings
		 */
		private $settings;

		/**
		 * Initialize the class and set its properties.
		 */
		public function __construct() {
			$this->settings = new Settings();
		}

		/**
		 * Add plugin options to Customizer.
		 *
		 * @link https://developer.wordpress.org/themes/customize-api/
		 *
		 * @param WP_Customize_Manager $wp_customize
		 */
		public function customizer_options( WP_Customize_Manager $wp_customize ): void {
			/**
			 * Add edit shortcut links (pencil icon wherever output when viewing in Customizer Preview).
			 *
			 * @link https://developer.wordpress.org/themes/customize-api/tools-for-improved-user-experience/#selective-refresh-fast-accurate-updates
			 */
			$wp_customize->selective_refresh->add_partial(
				$this->customizer_edit_shortcut_setting(),
				[
					'selector'            => '.' . $this->settings->common->get_wrapper_class(),
					'container_inclusive' => true,
					'render_callback'     => function () {
						// purposefully not set because the setting is dynamic
						// will just refresh it all, which is what we want anyway
					},
				]
			);

			// Add our custom panel, within which all our sections should be added.
			$wp_customize->add_panel(
				$this->settings->customizer_panel_id(),
				[
					'title'       => Plugin_Data::get_plugin_display_name(),
					'description' => esc_html__( 'Plugin options and settings', Plugin_Data::plugin_text_domain() ) . $this->settings->get_link_to_customizer_panel(),
				]
			);

			// Add our Customizer Section(s) within our custom panel.
			$wp_customize->add_section(
				$this->get_section_id( 'example' ),
				[
					'title'       => esc_html__( 'Example Section', Plugin_Data::plugin_text_domain() ),
					'description' => esc_html__( 'Example Section description.', Plugin_Data::plugin_text_domain() ),
					'panel'       => $this->settings->customizer_panel_id(),
				]
			);

			// Add setting(s) to our section(s)
			$this->add_setting_social_networks( $wp_customize, 'example', 'social_networks' );
			$this->add_setting_post_types( $wp_customize, 'example', 'post_types' );
		}

		/**
		 * The Customizer setting that the edit shortcut (pencil icon) should take user to.
		 *
		 * @return string
		 */
		public function customizer_edit_shortcut_setting(): string {
			/**
			 * @todo: Example setting: Sortable checkbox list of social networks. Must choose a setting to go to, not a section or panel.
			 */
			$setting = Plugin_Data::plugin_text_domain_underscores() . '[social_networks]';

			return apply_filters( Plugin_Data::plugin_text_domain_underscores() . '_' . __FUNCTION__, $setting );
		}

		/**
		 * Get the full ID of a Customizer section, given its unique slug.
		 *
		 * This keeps our section names namespaced but easily accessible within code via a single string.
		 *
		 * @param string $slug
		 *
		 * @return string
		 */
		private function get_section_id( string $slug = '' ): string {
			$slug = sanitize_key( $slug );

			if ( empty( $slug ) ) {
				return '';
			} else {
				return Plugin_Data::plugin_text_domain_underscores() . '_section_' . $slug;
			}
		}

		/**
		 * @todo: Example: Add setting for Social Networks. Notice this one has multiple sortable checkboxes.
		 *
		 * @param WP_Customize_Manager $wp_customize
		 * @param string               $section_slug The section this setting should be added to.
		 * @param string               $setting_slug This setting's unique slug.
		 */
		private function add_setting_social_networks( WP_Customize_Manager $wp_customize, string $section_slug, string $setting_slug ): void {
			$wp_customize->add_setting(
				$this->get_setting_id( $setting_slug ),
				[
					'type'              => 'option',
					'default'           => json_encode( $this->settings->get_choices_social_networks() ), // Select all by default
					'sanitize_callback' => [ $this->settings, 'sanitize_social_networks' ],
				]
			);

			$wp_customize->add_control(
				new Sortable_Checkboxes_Control(
					$wp_customize,
					Plugin_Data::plugin_text_domain_underscores() . '_' . $setting_slug . '_control',
					[
						'label'       => esc_html__( 'Social Network(s)', Plugin_Data::plugin_text_domain() ),
						'description' => esc_html__( 'Checked ones will output; unchecked ones will not. Drag and drop to set your preferred display order.', Plugin_Data::plugin_text_domain() ),
						'section'     => $this->get_section_id( $section_slug ),
						'settings'    => $this->get_setting_id( $setting_slug ),
						'choices'     => $this->settings->get_choices_social_networks(),
					]
				)
			);
		}

		/**
		 * Get the full ID of a Customizer setting, given its unique slug.
		 *
		 * This keeps our setting names namespaced but easily accessible within code via a single string.
		 *
		 * @param string $slug
		 *
		 * @return string
		 */
		private function get_setting_id( string $slug = '' ): string {
			$slug = sanitize_key( $slug );

			if ( empty( $slug ) ) {
				return '';
			} else {
				return Plugin_Data::plugin_text_domain_underscores() . '[' . $slug . ']';
			}
		}

		/**
		 * @todo: Example: Add setting for Post Types. Notice this one has multiple (but not sortable) Checkboxes, due to 'input_attrs'.
		 *
		 * @param WP_Customize_Manager $wp_customize
		 * @param string               $section_slug The section this setting should be added to.
		 * @param string               $setting_slug This setting's unique slug.
		 */
		private function add_setting_post_types( WP_Customize_Manager $wp_customize, string $section_slug, string $setting_slug ): void {
			$wp_customize->add_setting(
				$this->get_setting_id( $setting_slug ), [
					'type'              => 'option',
					'default'           => '',
					'sanitize_callback' => [ $this->settings, 'sanitize_post_types' ],
				]
			);

			$wp_customize->add_control(
				new Sortable_Checkboxes_Control(
					$wp_customize,
					Plugin_Data::plugin_text_domain_underscores() . '_' . $setting_slug . '_control',
					[
						'label'       => esc_html__( 'Post Type(s)', Plugin_Data::plugin_text_domain() ),
						'description' => esc_html__( 'Which Post Types should be enabled?', Plugin_Data::plugin_text_domain() ),
						'section'     => $this->get_section_id( $section_slug ),
						'settings'    => $this->get_setting_id( $setting_slug ),
						'choices'     => $this->get_choices_post_types(),
						'input_attrs' => [
							'data-disable_sortable' => 'true',
						],
					]
				)
			);
		}

		/**
		 * Get the Post Types options.
		 *
		 * @return array
		 */
		public function get_choices_post_types(): array {
			$result = [];

			foreach ( ( new Utils\Posts() )->get_public_post_types() as $type ) {
				// name is the registered name and label is what the user sees.
				$result[$type->name] = $type->label;
			}

			return $result;
		}
	}
}