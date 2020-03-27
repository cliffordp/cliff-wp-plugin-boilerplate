/*global wp, settingsData */

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
	Dashicon,
	ExternalLink,
	PanelBody,
	PanelRow,
	Placeholder,
	RadioControl,
	SelectControl,
	Spinner,
	TabPanel,
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
	const [ myToggle, setMyToggle ] = useState( false );
	const [ myTextInput, setMyTextInput ] = useState( '' );
	const [ myRadio, setMyRadio ] = useState( '' );
	const [ myMultiSelect, setMyMultiSelect ] = useState( [] );

	// Disable the multi-select while awaiting save to complete.
	const getDaysOptions = (
		disabled,
	) => {
		let choices = settingsData.choicesFor.my_multi_select;

		if ( disabled ) {
			return choices.map( choice => ({
					...choice,
					'disabled': true,
				}),
			);
		}

		return choices;
	};

	const setOptions = (
		option,
		value,
	) => {
		switch ( option ) {
			case 'myToggle':
				setMyToggle( value );
				break;
			case 'myTextInput':
				setMyTextInput( value );
				break;
			case 'myRadio':
				setMyRadio( value );
				break;
			case 'myMultiSelect':
				setMyMultiSelect( value );
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

		/**
		 * In case API response never comes back (if PHP is terminated) or takes unexpectedly long (if paused during a
		 * PHP breakpoint), still clear the API Saving flag so components aren't disabled forever (avoid page reload if
		 * API failed). The timeout is greater than 1000 (updating notification) + 800 (saved notification).
		 */
		setTimeout( () => {
			setAPISaving( false );
		}, 5000 );


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

				// Avoid showing success message when stuff really didn't happen as expected.
				if( null === response[ option ] ) {
					status = 'error';
				}

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
					let params;
					let msg;

					if ( response.responseJSON.data.params ) {
						params = response.responseJSON.data.params;
						msg = params [ Object.keys( params )[ 0 ] ];
					} else {
						msg = response.responseJSON.message;
					}

					addNotification(
						msg
							? msg
							: _x( 'An unknown error occurred.', 'notification' ),
						'danger',
					);
					setAPISaving( false );
				}, 1500 ); // Longer than success' to allow reading the error message.
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
					setMyToggle( Boolean( response[ settingsData.optionsInfo.prefix + 'my_toggle' ] ) );
					setMyTextInput( response[ settingsData.optionsInfo.prefix + 'my_textinput' ] );
					setMyRadio( response[ settingsData.optionsInfo.prefix + 'my_radio' ] );
					setMyMultiSelect( response[ settingsData.optionsInfo.prefix + 'my_multi_select' ] );
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
			<div className="main container">
				<TabPanel className="tabs"
						  initialTabName="tab1"
						  tabs={[
							  {
								  name: 'tab1',
								  title: <Fragment><Dashicon icon="admin-settings" /> Tab 1</Fragment>,
								  className: `tab-one`,
							  },
							  {
								  name: 'tab2',
								  title: <Fragment><Dashicon icon="external" /> Tab 2</Fragment>,
								  className: `tab-two`,
							  },
						  ]}
				>
					{
						( tab ) => {
							if ( 'tab1' === tab.name ) {
								return (
									<Fragment>
										<PanelBody
											title={_x( 'A section with a toggle', 'panel title' )}
										>
											<PanelRow>
												<ToggleControl
													label={_x( 'My Toggle', 'toggle input label' )}
													help={'The help text for this control.'}
													checked={myToggle}
													onChange={() => changeOptions(
														settingsData.optionsInfo.prefix + 'my_toggle',
														'myToggle',
														! myToggle,
													)}
												/>
											</PanelRow>
										</PanelBody>

										<PanelBody
											title={_x( 'Another section', 'panel title' )}
										>
											<div>
												<p>Another thing goes here.</p>
											</div>
										</PanelBody>
									</Fragment>
								);
							} else if ( 'tab2' === tab.name ) {
								return (
									<Fragment>
										<PanelBody
											title={_x( 'APIs & Posts', 'panel title' )}
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
													<div>
														<ExternalLink
															href="https://developers.google.com/maps/documentation/javascript/get-api-key"
														>
															{_x( 'Get API Key', 'external link' )}
														</ExternalLink>
													</div>
												</BaseControl>

												<RadioControl
													label={_x( 'My Radio', 'input label' )}
													help={_x( 'Pick one of these… and only one. (FYI: They are the public post types.)', 'input help' )}
													selected={myRadio}
													options={settingsData.choicesFor.my_radio}
													onChange={( myRadio ) => changeOptions(
														settingsData.optionsInfo.prefix + 'my_radio',
														'myRadio',
														myRadio,
													)}
												/>

												<SelectControl
													multiple
													label={_x( 'Multi-select component', 'input label' )}
													help={_x( 'Which one(s) do you want? Notice it disables while awaiting the save to complete. Nifty! Plus, #6 is always disabled.', 'input help' )}
													value={myMultiSelect} // We need to make sure we start as an array, not as `null`, or else the component won't load at all.
													options={getDaysOptions( isAPISaving )}
													onChange={( myMultiSelect ) => changeOptions(
														settingsData.optionsInfo.prefix + 'my_multi_select',
														'myMultiSelect',
														myMultiSelect,
													)}
												/>
											</PanelRow>
										</PanelBody>
									</Fragment>
								);
							}
						}
					}
				</TabPanel>
			</div>
		</Fragment>
	);
};

export default Main;