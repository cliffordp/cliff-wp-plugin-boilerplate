<?php

declare( strict_types=1 );

namespace WpPluginName_Tests_Support\Factories\Post;

use WpPluginName_Tests_Support\Factories\RemapPostMeta;
use Exception;

class CreateNew extends \WP_UnitTest_Factory_For_Post {

	/**
	 * Creates a Post post in the database.
	 *
	 * @param array $args    An array of values to override the default arguments. The post slug (i.e. `post_name`) is
	 *                       required, and there needs to be a JSON file with matching slug at _data/remap/post_meta.
	 *                       Keep in mind `tax_input` and `meta_input` to bake in terms and custom fields.
	 *
	 * @throws \Exception
	 * @return int|\WP_Error The generated Post ID.
	 */
	public function create_object( $args = [] ) {
		if ( empty( $args['post_name'] ) ) {
			throw new Exception( 'This factory requires passing a post slug into the arguments so it knows which JSON file to load from _data/remap/post_meta.' );
		}

		$args['post_type']   = 'post';
		$args['post_status'] = 'publish';

		$meta_input = ( new RemapPostMeta() )->get_meta_array( $args['post_name'] );

		$unique_id = uniqid( 'test_' . $args['post_type'], true );

		$defaults = [
			'post_title' => "Factory: {$unique_id}",
			'meta_input' => isset( $args['meta_input'] ) ? array_merge( $meta_input, $args['meta_input'] ) : $meta_input,
		];

		unset( $args['meta_input'] );

		$args = array_merge( $defaults, $args );

		$post_id = parent::create_object( $args );

		return $post_id;
	}

}