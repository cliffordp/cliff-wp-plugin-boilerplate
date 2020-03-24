<?php

namespace WpPluginName\Common\Utilities;

use Stringy\Stringy as Stringy;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Strings::class ) ) {
	/**
	 * Make dealing with strings easier.
	 *
	 * Don't add new methods that duplicate functionality already possible via Stringy.
	 */
	class Strings {

		/**
		 * Wrapper for the Stringy library.
		 *
		 * Must cast result to `(string)` or use `->toString()` as the last chained method to get the actual string value.
		 * Example:
		 * $file_name = 'admin.css';
		 * $file_name = ( new Strings() )->stringy($file_name)->removeRight('.min.css' )->removeRight('.css')->toString();
		 * Result: 'admin'.
		 *
		 * @link https://github.com/voku/Stringy#oo-and-chaining How-to's and all available functions.
		 *
		 * @param mixed $stringy
		 *
		 * @return Stringy
		 */
		public function stringy( $stringy ) {
			return Stringy::create( $stringy );
		}

	}
}
