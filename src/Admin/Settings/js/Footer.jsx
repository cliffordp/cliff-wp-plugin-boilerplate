/*global wp */

/**
 * WordPress dependencies.
 */
const { Button } = wp.components;

const { _x } = wp.i18n;

const Footer = () => {
	return (
		<footer className="footer">
			<div>
				<h2>{_x( 'Got a question for us?', 'info section heading' )}</h2>

				<p>{_x( 'Translatable footer text via JS.', 'dummy text in footer, just for testing' )}</p>

				<p>{_x( 'We would love to hear from you.', 'info section paragraph' )}</p>

				<Button
					isDefault
					isLarge
					target="_blank"
					href="https://wordpress.org/support/plugin/cliff-wp-plugin-boilerplate"
				>
					{_x( 'Ask a question', 'button text for external support link' )}
				</Button>

				<Button
					isDefault
					isLarge
					target="_blank"
					href="https://wordpress.org/support/plugin/cliff-wp-plugin-boilerplate/reviews/?rate=5#new-post"
				>
					{_x( 'Leave a review', 'button text for online review' )}
				</Button>
			</div>

		</footer>
	);
};

export default Footer;