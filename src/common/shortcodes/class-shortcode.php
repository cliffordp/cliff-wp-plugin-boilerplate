<?php

namespace WP_Plugin_Name\Shortcodes;

use WP_Plugin_Name as NS;

/**
 * The scaffolding for creating a new shortcode.
 *
 * @see \WP_Plugin_Name\Common\Common::$shortcode_classes Manually add your child class name here to get it to load.
 */
abstract class Shortcode {

	/**
	 * Get this plugin's text domain.
	 *
	 * @return string
	 */
	public function get_text_domain() {
		return NS\PLUGIN_TEXT_DOMAIN;
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
	 * Get the shortcode tag.
	 *
	 * If `$this->tag` exists, use it, else it will be created dynamically from this class' name.
	 *
	 * @return string
	 * @see \WP_Plugin_Name\Shortcodes\Shortcode::build_tag_from_class_name()
	 *
	 * @see sanitize_key()
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

		$tag = apply_filters( __CLASS__ . '::' . __FUNCTION__, $tag );

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
	 * Logic for the shortcode.
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @see shortcode_atts()
	 */
	abstract public function process_shortcode( $atts = [], $content = '' );

}