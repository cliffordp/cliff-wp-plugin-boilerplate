/**
 * All of the code for your admin's Settings Page belongs in this file (import from other files in this directory).
 *
 * The single/concatenated .js file gets enqueued from src/Admin/class-Assets.php.
 */

/*global wp, settingsData */

/**
 * Internal dependencies.
 */
import ReactNotification from 'react-notifications-component';
import Header from './Header.jsx';
import Main from './Main.jsx';
import Footer from './Footer.jsx';

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