/**
 * External dependencies.
 */
import { store } from 'react-notifications-component';
import 'react-notifications-component/dist/theme.css';

/**
 * WordPress dependencies.
 */

const {
	BaseControl,
	Button,
	ExternalLink,
	PanelBody,
	PanelRow,
	Placeholder,
	Spinner,
	ToggleControl,
} = wp.components;

const {
	Fragment,
	useEffect,
	useRef,
	useState,
} = wp.element;

const { _x } = wp.i18n;

const Main = () => {
	const [ isAPILoaded, setAPILoaded ] = useState( false );
	const [ isAPISaving, setAPISaving ] = useState( false );
	const [ notification, setNotification ] = useState( null );
	const [ cssModule, setCSSModule ] = useState( false );
	const [ blocksAnimation, setBlocksAnimation ] = useState( false );
	const [ isDefaultSection, setDefaultSection ] = useState( true );
	const [ googleMapsAPI, setGoogleMapsAPI ] = useState( '' );
	const [ isLoggingData, setLoggingData ] = useState( 'no' );

	const settingsRef = useRef( null );
	const notificationDOMRef = useRef( null );

	const changeOptions = ( option, state, value ) => {
		const model = new wp.api.models.Settings( {
			// eslint-disable-next-line camelcase
			[ option ]: value,
		} );

		const save = model.save();

		setAPISaving( true );

		addNotification( _x( 'Updating settings…', 'notification' ), 'info' );

		save.success( ( response, status ) => {
			store.removeNotification( notification );

			if ( 'success' === status ) {

				setOptions( state, response[ option ] );

				setTimeout( () => {
					addNotification( _x( 'Settings saved.', 'notification' ), 'success' );
					setAPISaving( false );
				}, 800 );
			}

			if ( 'error' === status ) {
				setTimeout( () => {
					addNotification( _x( 'An unknown error occurred.', 'notification' ), 'danger' );
					setAPISaving( false );
				}, 800 );
			}

			settingsRef.current.fetch();
		} );

		save.error( ( response, status ) => {
			store.removeNotification( notification );

			setTimeout( () => {
				addNotification( response.responseJSON.message ? response.responseJSON.message : _x( 'An unknown error occurred.', 'notification' ), 'danger' );
				setAPISaving( false );
			}, 800 );
		} );
	};

	const setOptions = ( option, value ) => {
		switch ( option ) {
			case 'cssModule':
				setCSSModule( value );
				break;
			case 'blocksAnimation':
				setBlocksAnimation( value );
				break;
			case 'isDefaultSection':
				setDefaultSection( value );
				break;
			case 'googleMapsAPI':
				setGoogleMapsAPI( value );
				break;
			case 'isLoggingData':
				setLoggingData( value );
				break;
		}
	};

	const addNotification = ( message, type ) => {
		const notification = store.addNotification( {
			message,
			type,
			insert: 'top',
			container: 'bottom-left',
			isMobile: true,
			dismiss: {
				duration: 2000,
				showIcon: true,
			},
			dismissable: {
				click: true,
				touch: true,
			},
		} );

		setNotification( notification );
	};

	useEffect( () => {
		wp.api.loadPromise.then( () => {
			settingsRef.current = new wp.api.models.Settings();

			if ( false === isAPILoaded ) {
				settingsRef.current.fetch().then( response => {
					setCSSModule( Boolean( response.themeisle_blocks_settings_css_module ) );
					setBlocksAnimation( Boolean( response.themeisle_blocks_settings_blocks_animation ) );
					setDefaultSection( Boolean( response.themeisle_blocks_settings_default_block ) );
					setGoogleMapsAPI( response.themeisle_google_map_block_api_key );
					setLoggingData( response.otter_blocks_logger_flag );
					setAPILoaded( true );
				} );
			}
		} );
	}, [] );

	if ( ! isAPILoaded ) {
		return (
			<Placeholder>
				<Spinner />
			</Placeholder>
		);
	}

	return (
		<Fragment>
			<div className="otter-main">
				<PanelBody
					title={_x( 'Modules', 'TODO' )}
				>
					<PanelRow>
						<ToggleControl
							label={_x( 'Enable Custom CSS Module', 'TODO' )}
							help={_x( 'Custom CSS module allows to add custom CSS to each block in Block Editor.', 'TODO' )}
							checked={cssModule}
							onChange={() => changeOptions( 'themeisle_blocks_settings_css_module', 'cssModule', ! cssModule )}
						/>
					</PanelRow>

					<PanelRow>
						<ToggleControl
							label={_x( 'Enable Blocks Animation Module', 'TODO' )}
							help={_x( 'Blocks Animation module allows to add CSS animations to each block in Block Editor.', 'TODO' )}
							checked={blocksAnimation}
							onChange={() => changeOptions( 'themeisle_blocks_settings_blocks_animation', 'blocksAnimation', ! blocksAnimation )}
						/>
					</PanelRow>
				</PanelBody>

				<PanelBody
					title={_x( 'Section', 'TODO' )}
				>
					<PanelRow>
						<ToggleControl
							label={_x( 'Make Section your default block for Pages', 'TODO' )}
							help={_x( 'Everytime you create a new page, Section block will be appended there by default.', 'TODO' )}
							checked={isDefaultSection}
							onChange={() => changeOptions( 'themeisle_blocks_settings_default_block', 'isDefaultSection', ! isDefaultSection )}
						/>
					</PanelRow>
				</PanelBody>

				<PanelBody
					title={_x( 'Maps', 'TODO' )}
				>
					<PanelRow>
						<BaseControl
							label={_x( 'Google Maps API', 'TODO' )}
							help={_x( 'In order to use Google Maps block, you need to use Google Maps and Places API.', 'TODO' )}
							id="otter-options-google-map-api"
							className="otter-text-field"
						>
							<input
								type="text"
								id="otter-options-google-map-api"
								value={googleMapsAPI}
								placeholder={_x( 'Google Maps API Key', 'TODO' )}
								disabled={isAPISaving}
								onChange={e => setGoogleMapsAPI( e.target.value )}
							/>

							<div className="otter-text-field-button-group">
								<Button
									isPrimary
									isLarge
									disabled={isAPISaving}
									onClick={() => changeOptions( 'themeisle_google_map_block_api_key', 'googleMapsAPI', googleMapsAPI )}
								>
									{_x( 'Save', 'TODO' )}
								</Button>

								<ExternalLink
									href="https://developers.google.com/maps/documentation/javascript/get-api-key"
									className="otter-step-five"
								>
									{_x( 'Get API Key', 'TODO' )}
								</ExternalLink>
							</div>
						</BaseControl>
					</PanelRow>
				</PanelBody>

				<PanelBody
					title={_x( 'Other', 'TODO' )}
				>
					<PanelRow>
						<ToggleControl
							label={_x( 'Anonymous Data Tracking.', 'TODO' )}
							help={_x( 'Become a contributor by opting in to our anonymous data tracking. We guarantee no sensitive data is collected.', 'TODO' )}
							checked={'yes' === isLoggingData ? true : false}
							onChange={() => changeOptions( 'otter_blocks_logger_flag', 'isLoggingData', ('yes' === isLoggingData ? 'no' : 'yes') )}
						/>
					</PanelRow>
				</PanelBody>

			</div>
		</Fragment>
	);
};

export default Main;