/**
 * All of the code for your admin's Settings Page belongs in this file (import from other files in this directory).
 *
 * The single/concatenated .js file gets enqueued from src/Admin/class-Assets.php.
 */

/**
 * Internal dependencies.
 */
import domReady from '@wordpress/dom-ready';
import Header from './header.js';
import Footer from './footer.js';

/**
 * WordPress dependencies.
 */
const App = () => {
	return (
		<>
			<Header />
			<Footer />
		</>
	);
};

domReady( function() {
	wp.element.render(
		<App />,
		document.getElementById( 'settings-page' )
	);
} );