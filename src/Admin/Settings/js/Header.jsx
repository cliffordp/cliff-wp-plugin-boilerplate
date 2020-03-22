/*global wp, settingsData */

/**
 * WordPress dependencies.
 */
const { Button, Dashicon } = wp.components;
const { _x } = wp.i18n;

const Header = () => {
	return (
		<header className="header">
			<div className="greet-header">
				<h1 className="greet-header-title">
					{_x( `${settingsData.pluginInfo.name} Settings`, "settings heading" )}
				</h1>
				<div className="greet-actions-right">
					<div className="greet-actions-right">
						<Button
							isDefault
							target="_blank"
							href="https://wordpress.org/support/"
						>
							<Dashicon icon="editor-help" />
							{_x( "Ask a question", "button help text" )}
						</Button>
					</div>
				</div>
			</div>
			<div className="container">
				<img
					src={settingsData.imagesBaseUrl + "fake-logo.png"} // Source: https://unsplash.com/photos/2LowviVHZ-E
					alt={_x( "Logo", "logo alt text" )}
					title={_x( "Logo", "logo title text" )}
				/>

				<span
					title={`Version: ${settingsData.pluginInfo.version}`}
					className="version"
				>
					{settingsData.pluginInfo.version}
				</span>
			</div>

			<Button isPrimary>
				Button Text Here
			</Button>
		</header>
	);
};

export default Header;