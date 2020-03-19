/**
 * All of the code for your admin's Settings Page belongs in this file (import from other files in this directory).
 *
 * The single/concatenated .js file gets enqueued from src/Admin/class-Assets.php.
 */

/**
 * Internal dependencies.
 */
import ReactNotification from 'react-notifications-component';
import Header from './Header.js';
import Main from './Main.js';
import Footer from './Footer.js';

const {
	Fragment
} = wp.element;

/**
 * WordPress dependencies.
 */
const App = () => {
	return (
		<Fragment>
			<ReactNotification />
			<Header />
			<Main />
			<Footer />
		</Fragment>
	);
};

wp.element.render(
	<App />,
	document.getElementById( settingsData.entryId ),
);