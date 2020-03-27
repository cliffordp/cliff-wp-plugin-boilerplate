/*global wp, settingsData */

/**
 * WordPress dependencies.
 */
const {
	Button,
	Dashicon,
} = wp.components;

const { _x } = wp.i18n;

const Header = () => {
	return (
		<header className="container bg-white relative shadow p-4">
			<h1 className="title">
				{`${settingsData.pluginInfo.name} ${settingsData.pluginInfo.settingsWord}`}
			</h1>
			<div className='flex'>
				<div className="m-2">
					<img
						src={settingsData.imagesBaseUrl + 'fake-logo.png'} // Source: https://unsplash.com/photos/2LowviVHZ-E
						alt={_x( 'Logo', 'logo alt text' )}
						title={_x( 'Logo', 'logo title text' )}
					/>
					<span
						className="version text"
						title={_x( `Version: ${settingsData.pluginInfo.version}`, 'version title text' )}
					>
						{_x( `Version: ${settingsData.pluginInfo.version}`, 'version text' )}
			</span>

				</div>
				<div className="max-w-md p-4">
					<p className='font-bold sm:text-lg'>
						{_x( 'Hi! Thanks for installing my plugin.', 'heading welcome' )}
					</p>
					<p className='pt-2 sm:text-xl'>
						{_x( 'With this plugin, you can now implement that thingy, this thang, and four other many lorem ipsum stuffs. Scroll down to get things setup just how you want them.', 'heading about' )}
					</p>
				</div>
			</div>
			<div>
				<Button
					isDefault
					target="_blank"
					href="https://wordpress.org/support/"
				>
					<Dashicon icon="editor-help" />
					{_x( 'Ask a question', 'button text' )}
				</Button>
			</div>
			<div className='mt-1'>
				<Button
					isPrimary
					href={`${settingsData.pluginInfo.customizerPanelUrl}`}
				>
					{_x( 'Direct link to this plugin\'s Customizer panel', 'button text' )}
				</Button>
			</div>
		</header>
	);
};

export default Header;