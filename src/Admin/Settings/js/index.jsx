/**
 * All of the code for your admin's Settings Page belongs in this file (import from other files in this directory).
 *
 * The single/concatenated .js file gets enqueued from src/Admin/class-Assets.php.
 */

/**
 * Internal dependencies.
 */
import ReactNotification from 'react-notifications-component';
import domReady from '@wordpress/dom-ready';
import Header from './Header.jsx';
import Main from './Main.jsx';
import Footer from './Footer.jsx';

/**
 * WordPress dependencies.
 */
const App = () => {
	return (
		<>
			<ReactNotification/>
			<Header />
			<Main />
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