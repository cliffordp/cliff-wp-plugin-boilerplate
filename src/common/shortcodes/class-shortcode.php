<?php

namespace WP_Plugin_Name\Shortcodes;

use WP_Plugin_Name as NS;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The scaffolding for creating a new shortcode.
 *
 * @see \WP_Plugin_Name\Shortcodes\Manage_Shortcodes::$shortcode_classes Manually add your child class name here to get it to load.
 */
abstract class Shortcode {

	/**
	 * Register the shortcode to WordPress.
	 *
	 * @see add_shortcode()
	 */
	public function register() {
		$shortcode = new static();

		add_shortcode( $this->get_tag(), [ $shortcode, 'init_shortcode' ] );
	}

	/**
	 * Get the shortcode tag.
	 *
	 * If `$this->tag` exists, use it, else it will be created dynamically from this class' name.
	 * All tags force hyphens to underscores.
	 *
	 * @see sanitize_key()
	 *
	 * @return string
	 */
	public function get_tag() {
		if (
			! empty( $this->tag )
			&& is_string( $this->tag )
		) {
			$tag = $this->tag;
		} else {
			$tag = $this->build_tag_from_class_name();
		}

		$tag = apply_filters( static::class . '::' . __FUNCTION__, $tag );

		$tag = str_replace( '-', '_', $tag );

		return sanitize_key( $tag );
	}

	/**
	 * Get the shortcode tag based on this class' name.
	 *
	 * @return string
	 */
	private function build_tag_from_class_name() {
		$tag = str_replace( __NAMESPACE__, '', static::class );
		$tag = str_replace( '\\', '', $tag );

		return strtolower( $tag );
	}

	/**
	 * Get this plugin's version.
	 *
	 * @return string
	 */
	public function get_version() {
		return NS\PLUGIN_VERSION;
	}

	/**
	 * Get the error message text allowed to be displayed to the user.
	 *
	 * @param string $fallback The text to display to an unprivileged user instead of the error message.
	 *
	 * @return string
	 */
	public function get_error_message( $cause = '', $fallback = '' ) {
		if ( current_user_can( $this->required_capability() ) ) {
			$message = $this->get_error_message_to_user_with_cap( $cause );
		} else {
			$message = $fallback;
		}

		return $message;
	}

	/**
	 * Capability required to access the settings, be shown error messages, etc.
	 *
	 * By default, 'customize' is mapped to 'edit_theme_options' (Administrator).
	 *
	 * @link https://developer.wordpress.org/themes/customize-api/advanced-usage/
	 */
	public function required_capability() {
		return apply_filters( $this->get_tag() . '_required_capability', 'customize' );
	}

	/**
	 * Get the error message text that a privileged user should see.
	 *
	 * @param string $cause The reason this error is displayed. Will go through `esc_html()`.
	 *
	 * @return string
	 */
	public function get_error_message_to_user_with_cap( $cause = '' ) {
		if (
			! is_string( $cause )
			|| '' === $cause
		) {
			$cause = esc_html_x( 'Unspecified', 'Default error cause text for [' . $this->get_tag() . ']', $this->get_text_domain() );
		}

		$message = sprintf(
			esc_html_x(
				'Your attempt to use the `%s` shortcode resulted in an error because: %s. Please reference the documentation or inspect the code and try again. (Message only shown to users with the `%s` capability.)',
				'Shortcode error message for [' . $this->get_tag() . ']',
				$this->get_text_domain()
			),
			$this->get_tag(),
			$cause,
			$this->required_capability()
		);

		$message = sprintf( '<p class="%s-shortcode-error shortcode-%s">%s</p>', esc_attr( $this->get_text_domain() ), esc_attr( $this->get_tag() ), $message );

		return $message;
	}

	/**
	 * Get this plugin's text domain.
	 *
	 * @return string
	 */
	public function get_text_domain() {
		return NS\PLUGIN_TEXT_DOMAIN;
	}

	/**
	 * Logic for the shortcode.
	 *
	 * @param array  $atts    The raw attributes from the shortcode.
	 * @param string $content The raw value from using an enclosing (not self-closing) shortcode.
	 */
	public function init_shortcode( $atts = [], $content = '' ) {
		return $this->process_shortcode( $this->get_atts( $atts ), $content );
	}

	/**
	 * Logic for the shortcode.
	 *
	 * @see shortcode_atts()
	 *
	 * @param array  $atts    The processed shortcode attributes after merging with defaults via `shortcode_atts()`.
	 * @param string $content The raw value from using an enclosing (not self-closing) shortcode.
	 */
	abstract public function process_shortcode( $atts = [], $content = '' );

	/**
	 * Get and process the attributes.
	 *
	 * @see shortcode_atts()
	 *
	 * @param array $atts
	 *
	 * @return array
	 */
	public function get_atts( $atts = [] ) {
		return shortcode_atts( $this->get_defaults(), $atts, $this->get_tag() );
	}

	/**
	 * An array of all the shortcode's possible attributes and their default values.
	 *
	 * @return array
	 */
	abstract public function get_defaults();
}