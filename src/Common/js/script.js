'use strict';

/**
 * This is the way to make `$` usable as jQuery in other JS files.
 *
 * @link https://github.com/parcel-bundler/parcel/issues/2724#issuecomment-528201533
 */
const jquery = require( 'jquery' );
$ = window.$ = window.jQuery = jquery;

$( document ).ready( () => {
	// @TODO This is an example console.log(). Remove for production.
	console.log( 'hello from Common. jQuery $ is working.' );
} );