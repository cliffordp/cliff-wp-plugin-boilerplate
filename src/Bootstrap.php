<?php

namespace WpPluginName;

// Abort if this file is called directly.
use WpPluginName\Core as Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Bootstrap::class ) ) {
	/**
	 * The file that gets things going.
	 */
	class Bootstrap {
		/**
		 * The required parent/child theme.
		 *
		 * Remove 'child' or leave blank if not required.
		 * Parent = Stylesheet. Child = Template.
		 */
		private $required_theme = [
			'parent' => '', // stylesheet slug
			'child'  => '', // template slug
		];

		/**
		 * The list of required (and/or recommended) plugins, as passed to TGM Plugin Activation.
		 *
		 * These links may contain affiliate links to paid products, which may financially benefit the author of this
		 * framework or this plugin but do not add to the cost of the paid products. You are not required to use
		 * these links to obtain the listed products.
		 *
		 * @link http://tgmpluginactivation.com/
		 */
		private $required_plugins = [
			[
				'name'         => 'Gravity Forms',
				'slug'         => 'gravityforms',
				'source'       => 'external',
				'required'     => true,
				'external_url' => 'http://rocketgenius.pxf.io/c/1235520/445235/7938',
				'version'      => '2.4.8.9',
			],
			[
				'name'         => 'GravityView',
				'slug'         => 'gravityview',
				'source'       => 'external',
				'required'     => false,
				'external_url' => 'https://gravityview.co/?ref=332',
				'version'      => '2.3.1',
			],
			[
				'name'         => 'Toolset Types',
				'slug'         => 'types',
				'source'       => 'external',
				'required'     => true,
				'external_url' => 'https://toolset.com/?aid=5336&affiliate_key=Lsvk04DjJOhq',
				'version'      => '3.2.7',
			],
			[
				'name'         => 'Toolset Views',
				'slug'         => 'types',
				'source'       => 'external',
				'required'     => true,
				'external_url' => 'https://toolset.com/?aid=5336&affiliate_key=Lsvk04DjJOhq',
				'version'      => '2.8.0.1',
			],
			/*[
				'name'     => 'WooCommerce',
				'slug'     => 'woocommerce',
				'source'   => 'repo',
				'required' => true,
				'version'  => '3.6.1',
			],
			[
				'name'         => 'WooCommerce Bookings',
				'slug'         => 'woocommerce-bookings',
				'source'       => 'external',
				'required'     => true,
				'external_url' => 'https://woocommerce.com/products/woocommerce-bookings/?aff=11845',
				'version'      => '1.14.0',
			],*/
		];

		/**
		 * Begins execution of the plugin.
		 *
		 * Since everything within the plugin is registered via hooks, then kicking off the plugin from this point in the file
		 * does not affect the page life cycle.
		 *
		 * Also returns copy of the app object so 3rd party developers can interact with the plugin's hooks contained within.
		 *
		 * @return Bootstrap|null
		 */
		public function init(): ?self {
			$plugin = new self();

			if ( $plugin->is_ready() ) {
				$core = new Core\Init();
				$core->run();

				return $plugin;
			} else {
				return null;
			}
		}

		/**
		 * Register the required plugins.
		 *
		 * @link https://github.com/TGMPA/TGM-Plugin-Activation/blob/master/example.php How/What to put in here.
		 *
		 * The variables passed to the `tgmpa()` function should be:
		 * - an array of plugin arrays;
		 * - optionally a configuration array.
		 * If you are not changing anything in the configuration array, you can remove the array and remove the
		 * variable from the function call: `tgmpa( $plugins );`.
		 * In that case, the TGMPA default settings will be used.
		 *
		 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
		 */
		public function tgmpa_register_required_plugins(): void {
			/*
			 * Array of configuration settings. Amend each line as needed.
			 */
			$config = [
				'id'           => PluginData::plugin_text_domain(),      // Unique ID for hashing notices for multiple instances of TGMPA.
				'parent_slug'  => 'plugins.php',           // Parent menu slug.
				'capability'   => 'activate_plugins',      // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
				'has_notices'  => true,                    // Show admin notices or not.
				'dismissable'  => false,                   // If false, a user cannot dismiss the nag message.
				'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
				'is_automatic' => false,                   // Automatically activate plugins after installation or not.
				'message'      => '',                      // Message to output right before the plugins table.
				'strings'      => [
					'notice_can_install_required'    => _n_noop(
					// translators: 1: plugin name(s).
						PluginData::get_plugin_display_name() . ' requires the following plugin: %1$s.',
						PluginData::get_plugin_display_name() . ' requires the following plugins: %1$s.',
						'cliff-wp-plugin-boilerplate'
					),
					'notice_can_install_recommended' => _n_noop(
					// translators: 1: plugin name(s).
						PluginData::get_plugin_display_name() . ' recommends the following plugin: %1$s.',
						PluginData::get_plugin_display_name() . ' recommends the following plugins: %1$s.',
						'cliff-wp-plugin-boilerplate'
					),
					'notice_ask_to_update'           => _n_noop(
					// translators: 1: plugin name(s).
						'The following plugin needs to be updated to its latest version to ensure maximum compatibility with ' . PluginData::get_plugin_display_name() . ': %1$s.',
						'The following plugins need to be updated to their latest version to ensure maximum compatibility with ' . PluginData::get_plugin_display_name() . ': %1$s.',
						'cliff-wp-plugin-boilerplate'
					),
					// translators: 1: plugin name
					'plugin_needs_higher_version'    => __(
						'Plugin not activated. A higher version of %s is needed for this plugin. Please update the plugin.',
						'cliff-wp-plugin-boilerplate'
					),
					'nag_type'                       => 'error', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
				],
			];

			tgmpa( $this->required_plugins, $config );
		}

		/**
		 * Output a message about unsatisfactory version of PHP.
		 */
		public function notice_old_php_version(): void {
			$help_link = sprintf( '<a href="%1$s" target="_blank">%1$s</a>', 'https://wordpress.org/about/requirements/' );

			$message = sprintf(
				// translators: 1: plugin display name, 2: required minimum PHP version, 3: current PHP version, help link
				__( '%1$s requires at least PHP version %2$s in order to work. You have version %3$s. Please see %4$s for more information.', 'cliff-wp-plugin-boilerplate' ),
				'<strong>' . PluginData::get_plugin_display_name() . '</strong>',
				'<strong>' . PluginData::required_min_php_version() . '</strong>',
				'<strong>' . PHP_VERSION . '</strong>',
				$help_link
			);

			$this->do_admin_notice( $message );
		}

		/**
		 * Output a wp-admin notice.
		 *
		 * @param string $message
		 * @param string $type
		 */
		public function do_admin_notice( string $message, string $type = 'error' ): void {
			$class = sprintf( '%s %s', $type, sanitize_html_class( PluginData::plugin_text_domain() ) );

			printf( '<div class="%s"><p>%s</p></div>', $class, $message );
		}

		/**
		 * Output a message about the required theme missing, and link to Themes page.
		 */
		public function notice_missing_required_theme(): void {
			$admin_link = '';

			$current_screen = get_current_screen();

			if (
				empty( $current_screen->base )
				|| 'themes' !== $current_screen->base
			) {
				$admin_link = sprintf( ' <a href="%1$s">%1$s</a>', admin_url( 'themes.php' ) );
			}


			// Check Parent
			if ( ! empty( $this->required_theme['parent'] ) ) {
				$parent_theme = wp_get_theme( $this->required_theme['parent'] );

				if (
					$parent_theme->exists()
					&& ! empty( $parent_theme->get( 'Name' ) )
				) {
					$parent_name = $parent_theme->get( 'Name' );
				} else {
					$parent_name = $this->required_theme['parent'];
				}
			}

			// Check Child
			if ( ! empty( $this->required_theme['child'] ) ) {
				$child_theme = wp_get_theme( $this->required_theme['child'] );

				if (
					$child_theme->exists()
					&& ! empty( $child_theme->get( 'Name' ) )
				) {
					$child_name = $child_theme->get( 'Name' );
				} else {
					$child_name = $this->required_theme['child'];
				}
			}

			if ( ! empty( $this->required_theme['child'] ) ) {
				$child_message = sprintf(
					// translators: 1: child theme name
					__( ' and %1$s child theme ', 'cliff-wp-plugin-boilerplate' ),
					'<strong>' . $child_name . '</strong>'
				);
			}

			$message = sprintf(
				// translators: 1: plugin display name, 2: parent theme name, 3: child theme name message, 4: wp-admin link to themes, if applicable
				__( 'The %1$s plugin requires the %2$s parent theme%3$sin order to work.%4$s', 'cliff-wp-plugin-boilerplate' ),
				'<strong>' . PluginData::get_plugin_display_name() . '</strong>',
				'<strong>' . $parent_name . '</strong>',
				$child_message,
				$admin_link
			);

			$this->do_admin_notice( $message );
		}

		/**
		 * Check if we have everything that is required.
		 *
		 * @return bool
		 */
		public function is_ready(): bool {
			$success = true;

			if ( version_compare( PHP_VERSION, PluginData::required_min_php_version(), '<' ) ) {
				add_action( 'admin_notices', [ $this, 'notice_old_php_version' ] );
				$success = false;
			}

			if ( $success ) {
				$success = $this->required_plugins_are_active();
			}

			// Plugins check passed so now check theme
			if ( $success ) {
				$success = $this->required_theme_is_active();

				if ( ! $success ) {
					// Admin notices for required plugins will be handled via TGM Plugin Activation, but not for the theme

					// Required to use current_user_can()
					require_once( ABSPATH . 'wp-includes/pluggable.php' );

					if ( current_user_can( 'switch_themes' ) ) {
						add_action( 'admin_notices', [ $this, 'notice_missing_required_theme' ] );
					}
				}
			}

			add_action( 'tgmpa_register', [ $this, 'tgmpa_register_required_plugins' ] );

			return $success;
		}

		/**
		 * Checks if all of the required plugins are active.
		 *
		 * @see  is_plugin_active()
		 *
		 * @link https://github.com/TGMPA/TGM-Plugin-Activation/issues/760 This method won't be required if this gets added.
		 *
		 * @return bool
		 * @return string Either file path for plugin if installed, or just the plugin slug.
		 */
		private function required_plugins_are_active(): bool {
			// The file in which is_plugin_active() is located.
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			$result = true;

			foreach ( $this->required_plugins as $required_plugin ) {
				if ( empty( $result ) ) {
					break;
				}

				// Only check plugins that are *required*, not the ones that are just *recommended*.
				if ( empty( $required_plugin['required'] ) ) {
					continue;
				}

				// Check if active
				$basename = $this->get_plugin_basename_from_slug( $required_plugin['slug'] );

				$active = is_plugin_active( $basename );

				if ( ! $active ) {
					$result = false;
					break;
				}

				// Is active so check sufficient version
				if ( empty( $required_plugin['version'] ) ) {
					continue;
				}

				$plugin_data = get_plugin_data( PluginData::all_plugins_dir() . $basename );

				if (
					empty( $plugin_data['Version'] )
					|| version_compare( $required_plugin['version'], $plugin_data['Version'], '>' )
				) {
					$result = false;
				}
			}

			return $result;
		}

		/**
		 * Get the file path of the plugin file from the plugin slug, if the plugin is installed.
		 *
		 * @see TGM_Plugin_Activation::_get_plugin_basename_from_slug()
		 *
		 * @param string $slug Plugin slug (typically folder name) as provided by the developer.
		 *
		 * @return string Either file path for plugin directory, or just the plugin file slug.
		 */
		private function get_plugin_basename_from_slug( string $slug ): string {
			$keys = array_keys( get_plugins() );

			foreach ( $keys as $key ) {
				if ( preg_match( '|^' . $slug . '/|', $key ) ) {
					return $key;
				}
			}

			return $slug;
		}

		/**
		 * Check if the required parent theme and/or child theme is active.
		 *
		 * @return bool True if no requirements set or they are met. False if requirements exist and are not met.
		 */
		private function required_theme_is_active(): bool {
			$current_theme = wp_get_theme();

			// Check Parent
			if ( ! empty( $this->required_theme['parent'] ) ) {
				if (
					empty( $current_theme->get_template() )
					|| $this->required_theme['parent'] !== $current_theme->get_template()
				) {
					return false;
				}
			}

			// Check Child
			if ( ! empty( $this->required_theme['child'] ) ) {
				if (
					empty( $current_theme->get_template() )
					|| $this->required_theme['child'] !== $current_theme->get_stylesheet()
				) {
					return false;
				}
			}

			return true;
		}


	}
}
