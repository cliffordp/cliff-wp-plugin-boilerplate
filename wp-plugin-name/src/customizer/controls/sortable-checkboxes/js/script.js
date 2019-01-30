/**
 * `msboxes` is short for Multiple Sortable Checkboxes.
 */
(function ( msboxes, wp, $ ) {
	'use strict';

	/**
	 * Multiple, Sortable Checkboxes Customizer Control.
	 */
	$( window ).load( function () {
		// Bail if the customizer isn't initialized
		if (
			!wp
			|| !wp.customize
		) {
			return;
		}

		// The li that wraps this whole section of the customizer. Sort of like a fieldset.
		let el = $( '.customize-control-sortable_checkboxes' );

		$( el ).msboxesCheckboxGroup();
	} );

	$.fn.msboxesCheckboxGroup = function () {
		/**
		 * Create an HTML <select> menu for choosing some value related to this checkbox (if applicable).
		 *
		 * @param  {string}  type         What type of options we want: linkCategories, customMenu, etc...
		 * @param  {boolean} visible      Should the menu be visible?  If the checkbox is not checked, the select menu should not be visible.
		 * @param  {string}  hiddenValArr The JSON currently being stored for this fieldset.
		 * @return {string}  The HTML for a <select> menu.
		 */
		function dropdown( type, visible, hiddenValArr ) {
			// Create a select menu.
			let select = $( '<select>' ).addClass( 'msboxesCheckboxGroup-select' ).prop( 'data-type', type );

			// Maybe hide it.
			if ( !visible ) {
				$( select ).hide();
			}

			// TODO: Need to fix if we were to try <select>
			// Grab our php variables.
			let localize = msboxesCustomizeLocalize;

			// Grab the current value for this menu.
			let current = '';
			if ( hiddenValArr !== null ) {
				//Is the checkbox for this item checked?
				if ( hiddenValArr[ '###' + type + '###' ] !== null ) {
					// If so, determine which option is selected.
					current = hiddenValArr[ '###' + type + '###' ];
				}
			}

			// Get a list of <option>'s for the <select>.
			let choices = localize[ type ];

			// For each <option>...
			$( choices ).each( function ( index, value ) {
				// The value.
				let slug = value.slug;

				// The label.
				let name = value.name;

				// Build the <option>.
				let option = $( '<option/>', {
					html   : name,
					'value': slug,
				} );

				// Maybe make it sticky.
				if ( slug === current ) {
					$( option ).prop( 'selected', 'selected' );
				}

				// Add it to the select menu.
				$( option ).appendTo( select );

			} );

			return $( select );
		}

		/**
		 * A function to make a draggable checkbox input.
		 *
		 * @param  {string}  key          The name of the option controlled by this checkbox.
		 * @param  {string}  choicesArr   The JSON string of all the options. Used to lookup the label value from the key.
		 * @param  {string}  hiddenValArr The JSON currently being stored in this fieldset.
		 * @param  {string}  fieldsetID   The HTML ID for this fieldset.
		 * @param  {boolean} checked      Whether or not this checkbox should be checked.
		 * @return {string}  The HTML for a draggable checkbox input.
		 */
		function checkboxListItem( key, choicesArr, hiddenValArr, fieldsetID, checked ) {
			let out = '';

			// If the key is empty, skip it.
			if (
				key === ''
				|| !choicesArr.hasOwnProperty( key )
			) {
				return out;
			}

			let input_id = fieldsetID + '_' + key;

			// If we are doing an empty checkbox, make sure it's not already on the list.
			if ( !checked ) {
				// If there is actually some value in the hidden input...
				if ( hiddenValArr !== null ) {
					// If the checkbox for this item is already checked, skip it.
					if ( hiddenValArr[ key ] === true ) {
						return out;
						// Else, if it's a non empty string, skip it.
					} else if ( (typeof hiddenValArr[ key ] == 'string') && hiddenValArr[ key ] !== '' ) {
						return out;
					}
				}
			}

			// This might end up holding an HTML <select> menu.
			let select = '';

			// The might hold the current value of the select menu.
			let selected = false;

			// Does this key contain a magic hash code?
			if ( key.indexOf( '###' ) !== -1 ) {
				// If so, remove the magic hash codes in order to arrive at what type of <select> it is.
				let type = key.replace( new RegExp( '#', 'g' ), '' );

				// Grab the select menu. We'll append it later.
				select = dropdown( type, checked, hiddenValArr );

				// Grab the current value of the select menu.
				selected = $( select ).val();
			}

			// Create the checkbox.
			let input = $( '<input/>', {
				'id'   : input_id,
				'name' : key,
				'type' : 'checkbox',
				'value': key,
			} );

			// If the checkbox is checked and has a select menu, record the value of the select menu in a data attribute.
			if ( checked && selected ) {
				$( input ).prop( 'data-which', selected );
			}

			// Wrap a label around the input.
			let label = $( '<label/>', {
				html : choicesArr[ key ],
				'for': input_id,
			} );
			$( input ).prependTo( label );

			// Make a list item to wrap the whole darn thing.
			out = $( '<li/>' );

			// Add the label to the list item.
			$( label ).appendTo( out );

			// Add the select menu to the list item.
			$( select ).appendTo( out );

			return out;
		}

		/**
		 * Create the list of checkboxes.
		 *
		 * @param {string} fieldsetID The ID for this fieldset.
		 * @param {object} hidden     The input that stores the value of the checkboxes.
		 */
		function create( fieldsetID, hidden ) {
			// The JSON string in the hidden value.
			let hiddenVal = hidden.val();
			let hiddenValArr;

			// Grab the hidden value as an object.
			if ( typeof hiddenVal !== 'object' ) {
				hiddenValArr = $.parseJSON( hiddenVal );
			} else {
				hiddenValArr = hiddenVal;
			}

			/**
			 * The availabe options for this fieldset, stored in the DOM as a
			 * data attribute on the input that stores the JSON.
			 */
			let choices = hidden.data( 'choices' );
			let choicesArr = {};

			if ( 'undefined' === typeof choices ) {
				return;
			} else {
				choices = JSON.stringify( choices );
				choicesArr = JSON.parse( choices );
			}

			// Start a list to hold the checkboxes, which we'll draw shortly.
			let sortable = $( '<ul>' ).addClass( 'ui-sortable' ).insertBefore( hidden );

			// Make the checkboxes sortable.
			$( sortable ).sortable( {
				// Once an item is dragged and sorted, update the preview and save the JSON.
				stop: function ( event, ui ) {
					update( hidden );
				},
			} );

			// Loop through the current values and output checked checkboxes.
			if ( !$.isEmptyObject( hiddenValArr ) ) {
				// For each of the current values...
				$.each( hiddenValArr, function ( key, value ) {
					// Draw a checkbox with the dropdown menu visible.
					let listItem = checkboxListItem( key, choicesArr, hiddenValArr, fieldsetID, true );

					// Add it to the DOM and check the box.
					$( listItem ).appendTo( sortable ).find( 'input' ).prop( 'checked', true );
				} );
			}

			// For all the available choices on this fieldset...
			$.each( choicesArr, function ( key, value ) {
				// Draw a checkbox with the dropdown menu hidden.
				let listItem = checkboxListItem( key, choicesArr, hiddenValArr, fieldsetID, false );

				// Add it to the DOM.
				$( listItem ).appendTo( sortable ).find( 'input' );
			} );

			if ( true === hidden.data( 'disable_sortable' ) ) {
				sortable.sortable( 'disable' );
			}
		}

		/**
		 * Update the value for this fieldset, perhaps when a checkbox is checked or sorted.
		 *
		 * @param {object} hidden The hidden form field that stores the values for this fieldset.
		 */
		function update( hidden ) {
			let out = {};

			// Grab the name of the hidden field.
			let hiddenName = $( hidden ).attr( 'name' );

			// Grab all the checkboxes in this fieldset.
			let checkboxes = $( hidden ).closest( 'li' ).find( '[type="checkbox"]:checked' );

			// Grab all the select menus in this fieldset.
			let selects = $( hidden ).closest( 'li' ).find( 'select' );

			// For each checkbox...
			$( checkboxes ).each( function ( index, value ) {
				// Grab the value.
				let val = $( value ).val();

				// Let's see if this checkbox carries a select menu as well.
				let which = $( value ).prop( 'data-which' );

				// If it has a select menu, that acts as the value for the checkbox.
				if ( typeof which !== 'undefined' ) {
					out[ val ] = which;

					// If not, the checkbox is just boolean true.
				} else {
					out[ val ] = true;
				}
			} );

			// Turn the output into JSON.
			let outStr = JSON.stringify( out );

			// Update the hidden field.
			$( hidden ).val( outStr );

			// Show or hide the select menus based on whether the parent menu item is checked.
			$( selects ).each( function ( index, value ) {
				// Find out if the checkbox is checked. If so, reveal the select menu.
				let checkbox = $( value ).closest( 'li' ).find( '[type="checkbox"]' );
				if ( $( checkbox ).is( ':checked' ) ) {
					$( value ).slideDown();
					// Else, hide the select menu.
				} else {
					$( value ).slideUp();
				}

			} );

			hidden.trigger( 'change' );
		}

		/**
		 * The main part of the $ plugin that actually returns the selected items.
		 *
		 * @return {object} The items that this plugin applies to.
		 */
		return this.each( function () {
			let that = this;

			// The ID for this section of the customizer.
			let fieldsetID = $( that ).attr( 'id' );

			// The input that stores JSON as the checkboxes toggle and move.
			let hidden = $( that ).find( 'input.sortable_checkboxes-checkbox_group-hidden' );

			// Okay! Create the list of checkboxes!
			create( fieldsetID, hidden );

			// Whenever the checkboxes are checked, update the json and redraw the preview.
			$( that ).on( 'change', '[type="checkbox"]', function ( event ) {
				update( hidden );
			} );

			/**
			 * Whenever the dropdowns are selected, trigger a change on the corresponding checkbox.
			 */
			$( that ).on( 'change', 'select', function ( event ) {
				/**
				 * What type of dropdown is this? Maybe to get links by category, or get a custom menu?
				 */
				let type = $( this ).prop( 'data-type' );

				// Find the corresponding checkbox.
				let checkbox = $( this ).closest( 'li' ).find( '[type="checkbox"]:checked' );

				// Find out which <option> is selected.
				let selected = $( this ).val();

				// Trigger a change on that checkbox.
				$( checkbox ).prop( 'data-which', selected ).trigger( 'change' );
			} );

			return that;
		} );
	};
})( window.msboxes, window.wp, jQuery );