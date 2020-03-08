/**
 * WordPress dependencies.
 */
const { Button, PanelBody, Placeholder, SelectControl, ServerSideRender, TextControl, TextareaControl, ToggleControl } = wp.components;
const { __ } = wp.i18n;

const Headers = () => {
	return (
		<header className="header">
			<div className='container'>
				<div className="logo">
					IMG markup here.
				</div>
			</div>

			<Button isPrimary>
				Button Text Here
			</Button>
		</header>
	);
};

export default Headers;