/**
 * All of the code for your admin-facing JavaScript belongs in this directory, split into as many JS files as you wish,
 * such as just this one or feel free to add additional JS files.
 *
 * All *.js files in this directory get included in a build process.
 * The single/concatenated .js file gets enqueued from src/Admin/class-Assets.php.
 */
(function ( admin, $ ) {
	$( document ).ready( () => {
		// @TODO This is an example console.log(). Remove for production.
		console.log( 'hello from Admin. jQuery $ is working.' );
	} );
})( window.admin = window.admin || {}, jQuery );