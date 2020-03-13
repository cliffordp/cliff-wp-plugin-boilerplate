<?php

namespace WP_Plugin_Name\Common\Utilities;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Http::class ) ) {

	/**
	 * Things related to HTTP, like $_GET, $_PUT, $_REQUEST, and such.
	 */
	class Http {
	}

}
