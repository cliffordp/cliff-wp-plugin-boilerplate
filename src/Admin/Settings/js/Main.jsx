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
	RadioControl,
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
	// The data handling.
	const [ isAPILoaded, setAPILoaded ] = useState( false );
	const [ isAPISaving, setAPISaving ] = useState( false );
	const [ notification, setNotification ] = useState( null );
	const settingsRef = useRef( null );

	// To process each one of our Settings fields, which is required in React when using "controlled inputs".
	const [ myCheckbox, setMyCheckbox ] = useState( false );
	const [ myTextInput, setMyTextInput ] = useState( '' );
	const [ myRadio, setMyRadio ] = useState( '' );

	const setOptions = (
		option,
		value,
	) => {
		switch ( option ) {
			case 'myCheckbox':
				setMyCheckbox( value );
				break;
			case 'myTextInput':
				setMyTextInput( value );
				break;
			case 'myRadio':
				setMyRadio( value );
				break;
		}
	};

	const changeOptions = (
		option,
		state,
		value,
	) => {
		const model = new wp.api.models.Settings( {
			[ option ]: value,
		} );

		const save = model.save();

		setAPISaving( true );

		addNotification(
			_x( 'Updating settings…', 'notification' ),
			'info',
			1000,
		);

		save.success(
			(
				response,
				status,
			) => {
				store.removeNotification( notification );

				if ( 'success' === status ) {
					setOptions( state, response[ option ] );

					setTimeout( () => {
						addNotification(
							_x( 'Settings saved.', 'notification' ),
							'success',
						);
						setAPISaving( false );
					}, 800 );
				}

				if ( 'error' === status ) {
					setTimeout( () => {
						addNotification(
							_x( 'An unknown error occurred.', 'notification' ),
							'danger',
						);
						setAPISaving( false );
					}, 800 );
				}

				settingsRef.current.fetch();
			} );

		save.error(
			(
				response,
				status,
			) => {
				store.removeNotification( notification );

				setTimeout( () => {
					addNotification(
						response.responseJSON.message
							? response.responseJSON.message
							: _x( 'An unknown error occurred.', 'notification' ),
						'danger',
					);
					setAPISaving( false );
				}, 800 );
			} );
	};

	const addNotification = (
		message,
		type,
		theDuration = 2500,
	) => {
		const notification = store.addNotification( {
			message,
			type,
			insert: 'top',
			container: 'bottom-left',
			isMobile: true,
			dismiss: {
				duration: theDuration,
				showIcon: true,
			},
		} );

		setNotification( notification );
	};

	useEffect( () => {
		wp.api.loadPromise.then( () => {
			settingsRef.current = new wp.api.models.Settings();

			if ( false === isAPILoaded ) {
				settingsRef.current.fetch().then( response => {
					// 'response' is the result from the Settings API containing all the settings exposed via REST API, plus some general site info.
					setAPILoaded( true );
					setMyCheckbox( Boolean( response[ settingsData.optionsInfo.prefix + 'my_checkbox' ] ) );
					setMyTextInput( response[ settingsData.optionsInfo.prefix + 'my_textinput' ] );
					setMyRadio( response[ settingsData.optionsInfo.prefix + 'my_radio' ] );
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
			<div className="main">
				<PanelBody
					title={_x( 'Modules', 'panel title' )}
					className="this-thing"
				>
					<PanelRow>
						<ToggleControl
							label={_x( 'My Checkbox', 'toggle input label' )}
							help={'The help text for this control.'}
							checked={myCheckbox}
							onChange={() => changeOptions(
								settingsData.optionsInfo.prefix + 'my_checkbox',
								'myCheckbox',
								! myCheckbox,
							)}
						/>
					</PanelRow>
				</PanelBody>

				<PanelBody
					title={_x( 'APIs', 'panel title' )}
				>
					<PanelRow>
						<BaseControl
							label={_x( 'A text input', 'text input label' )}
							help={'Allows lowercase, uppercase, underscores, and hyphens.'}
						>
							<input
								type="text"
								value={myTextInput}
								placeholder={_x( 'abc_ABC-123', 'text input placeholder' )}
								disabled={isAPISaving}
								onChange={e => setMyTextInput( e.target.value )}
								onKeyPress={event => {
									if ( event.key === 'Enter' ) {
										document.getElementById( 'forMyTextInput' ).click();
									}
								}}
							/>

							<Button
								id={'forMyTextInput'}
								isPrimary
								isLarge
								disabled={isAPISaving}
								onClick={() => changeOptions(
									settingsData.optionsInfo.prefix + 'my_textinput',
									'myTextInput',
									myTextInput,
								)}
							>
								{_x( 'Save', 'button text' )}
							</Button>

							<ExternalLink
								href="https://developers.google.com/maps/documentation/javascript/get-api-key"
							>
								{_x( 'Get API Key', 'external link' )}
							</ExternalLink>
						</BaseControl>

						<RadioControl
							label={_x( 'My Radio', 'radio input label' )}
							help={_x( 'Pick one of these… and only one. (FYI: They are the public post types.)', 'radio input help' )}
							selected={myRadio}
							options={settingsData.choicesFor.myRadio}
							onChange={( myRadio ) => changeOptions(
								settingsData.optionsInfo.prefix + 'my_radio',
								'myRadio',
								myRadio,
							)}
						/>
					</PanelRow>
				</PanelBody>

				<PanelBody>
					<h2>{_x( 'Got a question for us?', 'info section heading' )}</h2>

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
				</PanelBody>
			</div>
		</Fragment>
	);
};

export default Main;