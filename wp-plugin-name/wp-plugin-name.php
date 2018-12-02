<?php
/**
 * The plugin bootstrap file
 *
 * https://github.com/cliffordp/cliff-wp-plugin-boilerplate#plugin-structure
 * Plugin structure if you want to include your own classes, or third-party libraries:
 * wp-plugin-name/src/admin - admin-specific functionality
 * wp-plugin-name/src/core - plugin core to register hooks, load files etc
 * wp-plugin-name/src/frontend - public-facing functionality
 * wp-plugin-name/src/common - functionality shared between the admin area and the public-facing parts
 * wp-plugin-name/src/libraries - libraries that the plugin may use that aren't able to be included via Composer
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.example.com/
 * @since             1.0.0
 * @package           WP_Plugin_Name
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Plugin Boilerplate
 * Plugin URI:        https://www.example.com/wp-plugin-name-uri/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Your Name or Your Company
 * Author URI:        https://www.example.com/
 * License:           GPL version 3 or any later version
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       wp-plugin-name
 * Domain Path:       /languages
 */

namespace WP_Plugin_Name;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Constants
 */
// `WP_Plugin_Name\` is defined
define( __NAMESPACE__ . '\NS', __NAMESPACE__ . '\\' );

// `WP_Plugin_Name\PLUGIN_TEXT_DOMAIN` is defined
define( NS . 'PLUGIN_TEXT_DOMAIN', 'wp-plugin-name' ); // Must match the plugin's directory and its main PHP filename

define( NS . 'PLUGIN_VERSION', '1.0.0' ); // TODO: Keep current

define( NS . 'PLUGIN_NAME_DIR', plugin_dir_path( __FILE__ ) );

define( NS . 'PLUGIN_NAME_URL', plugin_dir_url( __FILE__ ) );

define( NS . 'PLUGIN_BASENAME', plugin_basename( __FILE__ ) );



/**
 * Autoloading, via Composer.
 *
 * @link https://getcomposer.org/doc/01-basic-usage.md#autoloading
 */
require_once( __DIR__ . '/vendor/autoload.php' );

/**
 * Register Activation and Deactivation Hooks
 * This action is documented in src/core/class-activator.php
 */

register_activation_hook( __FILE__, [ NS . 'Core\Activator', 'activate' ] );

/**
 * The code that runs during plugin deactivation.
 * This action is documented src/core/class-deactivator.php
 */

register_deactivation_hook( __FILE__, [ NS . 'Core\Deactivator', 'deactivate' ] );


/**
 * Plugin Singleton Container
 *
 * Maintains a single copy of the plugin app object
 *
 * @since 1.0.0
 */
class WP_Plugin_Name {

	/**
	 * The required version of PHP.
	 *
	 * @since 1.0.0
	 */
	private $min_php = '5.6.0';

	/**
	 * The list of required plugins, as passed to is_plugin_active()
	 *
	 * @since 1.0.0
	 */
	private $required_plugins = [
		// 'gravityforms/gravityforms.php',
		// 'types/wpcf.php',
		// 'woocommerce/woocommerce.php',
	];

	/**
	 * The plugin that is missing, if any.
	 *
	 * @since 1.0.0
	 */
	private $missing_plugin = '';

	/**
	 * Check if we have everything that is required.
	 *
	 * @return bool
	 */
	public function is_ready() {
		$success = true;

		if ( version_compare( PHP_VERSION, $this->min_php, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'notice_old_php_version' ] );
			$success = false;
		}

		if ( $success ) {
			$success = $this->has_required_plugins();
		}

		return $success;
	}

	/**
	 * Checks if all of the required plugins are active.
	 *
	 * If not all are, the first one detected missing will display an admin error notice.
	 *
	 * @see is_plugin_active()
	 * @see current_user_can()
	 *
	 * @return bool
	 */
	private function has_required_plugins() {
		// The file in which is_plugin_active() is located.
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		// Required to use current_user_can()
		require_once( ABSPATH . 'wp-includes/pluggable.php' );

		$result = true;

		foreach ( $this->required_plugins as $plugin ) {
			if ( empty( $result ) ) {
				break;
			}

			$this->missing_plugin = $plugin;

			$result = is_plugin_active( $plugin );
		}

		if (
			empty( $result )
			&& current_user_can( 'activate_plugins' )
		) {
			add_action( 'admin_notices', [ $this, 'notice_missing_required_plugin' ] );
		}

		return $result;
	}

	/**
	 * Output a message about a required plugin missing, and link to Plugins page.
	 */
	public function notice_old_php_version() {
		$message = sprintf(
			__( '%1$s requires at least PHP version %2$s in order to work.', PLUGIN_TEXT_DOMAIN ),
			'<strong>' . wp_plugin_name_get_plugin_display_name() . '</strong>',
			'<strong>' . $this->min_php . '</strong>'
		);

		$this->do_admin_notice( $message );
	}

	/**
	 * Output a wp-admin notice.
	 *
	 * @param        $message
	 * @param string $type
	 */
	public function do_admin_notice( $message, $type = 'error' ) {
		$class = sprintf( '%s %s', $type, sanitize_html_class( PLUGIN_TEXT_DOMAIN ) );

		printf( '<div class="%s"><p>%s</p></div>', $class, $message );
	}

	/**
	 * Output a message about a required plugin missing, and link to Plugins page.
	 */
	public function notice_missing_required_plugin() {
		$admin_link = '';

		$current_screen = get_current_screen();

		if (
			empty( $current_screen->base )
			|| 'plugins' !== $current_screen->base
		) {
			$admin_link = sprintf( ' <a href="%1$s">%1$s</a>', admin_url( 'plugins.php' ) );
		}

		$message = sprintf(
			__( 'The %1$s plugin requires the %2$s plugin to be active in order to work.%3$s', PLUGIN_TEXT_DOMAIN ),
			'<strong>' . wp_plugin_name_get_plugin_display_name() . '</strong>',
			'<strong>' . $this->missing_plugin . '</strong>',
			$admin_link
		);

		$this->do_admin_notice( $message );
	}
}

/**
 * Get the plugin's display name.
 *
 * Useful for headings, for example.
 *
 * @return string
 */
function wp_plugin_name_get_plugin_display_name() {
	return esc_html_x( 'WordPress Plugin Boilerplate', 'Plugin name for display', PLUGIN_TEXT_DOMAIN );
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks, then kicking off the plugin from this point in the file
 * does not affect the page life cycle.
 *
 * Also returns copy of the app object so 3rd party developers can interact with the plugin's hooks contained within.
 *
 * @return false|WP_Plugin_Name
 **/
function wp_plugin_name_init() {
	$plugin = new WP_Plugin_Name();

	if ( $plugin->is_ready() ) {
		$core = new Core\Init();
		$core->run();
		return $plugin;
	} else {
		return false;
	}
}

wp_plugin_name_init();