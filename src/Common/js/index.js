/**
 * Code in this file will be ran in both Admin and Frontend contexts, prior to either's own JS.
 *
 * This file itself
 */

/**
 * TODO: Remove this comment and everything BELOW it if there isn't any JS to share between Admin and Frontend.
 */
alert( 'Common: Parcel build worked :) This is here to annoy you so you change things to your own. Work the TODOs ;o)' );

(function ( common, $ ) {
	$( document ).ready( () => {
		console.log( 'Common: jQuery $ is working.' );
	} );
})( window.common = window.common || {}, jQuery );