/**
 * WordPress dependencies.
 */
const { __ } = wp.i18n;

const Footer = () => {
	return (
		<footer className="footer">
			<div className="container">
				{ __( 'Footer text via JS.' ) }
			</div>
		</footer>
	);
};

export default Footer;