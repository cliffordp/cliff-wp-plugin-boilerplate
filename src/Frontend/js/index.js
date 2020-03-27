/**
 * All of the code for your public-facing JavaScript belongs in this directory, split into as many JS files as you wish,
 * such as just this one or feel free to add additional JS files.
 *
 * All *.js files in this directory get included in a build process.
 * The single/concatenated .js file gets enqueued from src/Frontend/class-Assets.php.
 */
(function ( frontend, $ ) {
	$( document ).ready( () => {
		// @TODO This is an example console.log(). Remove for production.
		console.log( 'hello from Frontend. jQuery $ is working.' );
	} );
})( window.frontend = window.frontend || {}, jQuery );