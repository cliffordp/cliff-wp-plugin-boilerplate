<?php

declare( strict_types=1 );

namespace WpPluginName_Tests_Support\Factories;

use function PHPUnit\Framework\assertIsArray;

class RemapPostMeta {

	public function get_meta_array( string $name ): array {
		$file = $this->build_full_path_to_remap_file( $name );

		$array = $this->json_to_array( $file );

		$array = $this->post_meta_raw_json_array_to_meta_input_array( $array );

		return $array;
	}

	/**
	 * Convert the array of post meta from WP-CLI's JSON export to the format required to insert post into WP database.
	 *
	 * @see wp_insert_post()
	 *
	 * @param array $json_array
	 *
	 * @return array
	 */
	private function post_meta_raw_json_array_to_meta_input_array( array $json_array ): array {
		$meta_input_array = [];

		foreach ( $json_array as $array_item ) {
			if (
				! is_array( $array_item )
				|| ! isset( $array_item['meta_key'] )
				|| ! isset( $array_item['meta_value'] )
			) {
				continue;
			}
			$k = $array_item['meta_key'];

			$meta_input_array[ $k ] = $array_item['meta_value'];
		}

		return $meta_input_array;
	}

	/**
	 * Returns the absolute path to a remap file. Does not check if file exists.
	 *
	 * @param string $name The name of the file excluding `.json` file extension.
	 *
	 * @return string The absolute path to the target file.
	 */
	private function build_full_path_to_remap_file( string $name ): string {
		$partial_path = sprintf( 'remap/post_meta/%s.json', $name );

		return codecept_data_dir( $partial_path );
	}

	/**
	 * Convert any JSON file to an array.
	 *
	 * @param $file
	 *
	 * @return array
	 */
	private function json_to_array( $file ) {
		if ( ! is_readable( $file ) ) {
			return [];
		}

		// Don't `stripslashes()` because HTML with quotes will cause JSON to not parse.
		$json = json_decode( file_get_contents( $file ), true );

		if ( ! is_array( $json ) ) {
			assertIsArray( $json, 'JSON file was not successfully converted to an array.' );
			$json = [];
		}

		return $json;
	}

}
