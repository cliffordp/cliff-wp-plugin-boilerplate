/**
 * WordPress dependencies.
 */
const { Button } = wp.components;

const { _x } = wp.i18n;

const Footer = () => {
	return (
		<footer className="footer">
			<div className="container">
				{_x( 'Translatable footer text via JS.', 'dummy text in footer, just for testing' )}

				<Button
					isDefault
					isLarge
					target="_blank"
					href="https://wordpress.org/support/view/plugin-reviews/YOUR-PLUGIN-SLUG?rate=5#postform
"
				>
					{_x( 'Share your review', 'link anchor text' )}
				</Button>

			</div>
		</footer>
	);
};

export default Footer;