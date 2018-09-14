//Allows $ to be used instead of jQuery
jQuery(document).ready(function($) {

	/*
	*	This function attaches a click listener to all input boxes
	*	It then has a switch statement that checks through all the possible classes
	*	and calls a function which handles that particular case
	*/
	$(document).on('click', "input", function(event) {

		if (jQuery(this).hasClass('save_lib')) {
			saveBreakpoints();
		}
		else if (jQuery(this).hasClass('add_new_breakpoint')) {
			addBreakpoint($(this).parent());
		}
		else if (jQuery(this).hasClass('remove_breakpoint')) {
			removeBreakpoint(this);
		}
		else if (jQuery(this).hasClass('clear_settings')) {
			clearSettings(this);
		}else if (this.className == 'update') {
		  updateSettings(this);
		}
		else {
			var tempParent = $(this).parent().parent()[0];
			if (jQuery(tempParent).hasClass('top_list')) {

				if (jQuery(this).hasClass('top-nested_grid')) {
					var breakpoint_cols = $($(this).parents('.feature_bp')[0]).data('breakpoint-cols');
					topNestedGrid(this, breakpoint_cols);
				} else if (jQuery(this).hasClass('top-offset_left')) {
					mainNested(this);
				} else if (jQuery(this).hasClass('top-offset_right')) {
					mainNested(this);
				} else if (jQuery(this).hasClass('top-element_widths')) {
					mainNested(this);
				}

			} else if (jQuery(tempParent).hasClass('parent_columns')) {
				//parentColumWidth(this);
				parentSub(this);
			} else if (jQuery(tempParent).hasClass('parentElements')) {
				//parentSub(this);
			} else if (jQuery(tempParent).hasClass('parentSubList')) {
				parentSubSub(this);
			} else if (jQuery(tempParent).hasClass('childElements')) {
				var breakpoint_cols = $($(this).parents('.feature_bp')[0]).data('breakpoint-cols');
				childCols(this, breakpoint_cols)
				//childElements(this);
			} else if (jQuery(tempParent).hasClass('child_columns')) {
				childElements(this);
			}

		}
	});


	$(document).on('click', function(event) {
		if (event.target.className == 'tab_link') {
			change_feature_tabs(event, event.target);

		} else if (event.target.className == 'new_grid_feature_button') {
			addNewFeature(event, event.target);

		} else if (event.target.className == 'save_feature_settings_button') {
			saveFeature( jQuery(event.target).data('feature-slug') );

		} else if (event.target.className == 'checked_list__display_toggle') {
			toggleChecklistDisplay( event, event.target );

		}
	});

	function toggleChecklistDisplay(event, object) {
		var parentList = jQuery(object).parent('ul')[0];

		if (jQuery(parentList).hasClass('display_toggle__closed')) {
			jQuery(parentList).removeClass('display_toggle__closed')
			jQuery(object).html('+');
		} else {
			jQuery(parentList).addClass('display_toggle__closed')
			jQuery(object).html('-');
		}
	}

	function addNewFeature(event, object) {
		var new_feature_name = jQuery('#new_feature_name')[0].value;
		var new_feature_slug = jQuery('#new_feature_slug')[0].value;

		var arr = {
			'action': 'add_new_grid_feature',
			'new_feature_name': new_feature_name,
			'new_feature_slug': new_feature_slug,
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, arr, function(response) {
			console.log(response);
			if (response === 'false') {
				alert("an error occured while adding the new feature");
			} else {
				jQuery(jQuery('#grid-options-pages')[0]).append(response);
				jQuery(jQuery('#grid_tab_links')[0]).append('<button class="tab_link" data-select="feature_options__' + new_feature_slug + '">' + new_feature_name + '</button>');
				alert('feature added!');
			}
		});
	}

	/**
	 *
	 */
	function change_feature_tabs(event, object) {
	    var select_tab = jQuery(object).data('select');

	    jQuery('.tab_feature_options').each(function() {
	    	if (jQuery(this).hasClass('default_tab')) {
	    		jQuery(this).removeClass('default_tab');
	    	}
	    	if (this.id === select_tab) {
	    		jQuery(this).show();
	    	}
	    	else {
	    		jQuery(this).hide();
	    	}
	    })
	}

	/*
	*	Activated by button click, saves all the data to the database
	*/
	function clearSettings() {
		var arr = {
			'action': 'clear_grid_breakpoints_settings'
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, arr, function(response) {
			jQuery('body').append(response);
			alert("cleared settings, refresh page");
		});
	}

	/*
	*	Activated by button click, saves all the data to the database
	*/
	function saveBreakpoints() {

		var data = {}; // holds all the objects
		var breakpoints = $('#main_grid_options .bp');
		var breakpoint_prefixes = $('#main_grid_options .prefix');

		/*
		*	This loop builds the object for saving in the database
		*/
		for (var j = 0; j < breakpoints.length; j++) {
			var inner_data = {}; // holds each individual object
			inner_data['nested'] = {
				'top_list': '',
				'top-element_widths': 'false',
				'top-offset_right': 'false',
				'top-offset_left': 'false',
				'top-nested_grid': 'false'
			};

			/*
			*	This loop adds the input data to the object
			*/
			for (var i = 0; i < $(breakpoint_prefixes[j]).siblings('input').length;i++) {

				//I only want text and radio inputs, I don't want any buttons
				if ($(breakpoint_prefixes[j]).siblings('input')[i].type !== 'radio' &&  $(breakpoint_prefixes[j]).siblings('input')[i].type !== 'button') {
					inner_data[$(breakpoint_prefixes[j]).siblings('input')[i].className] = $(breakpoint_prefixes[j]).siblings('input')[i].value;
				}
				else if ($(breakpoint_prefixes[j]).siblings('input')[i].type === 'radio') {
					inner_data[$(breakpoint_prefixes[j]).siblings('input')[i].className] = $($(breakpoint_prefixes[j]).siblings('input')[i]).is(':checked');

				}
			}

			data [ breakpoint_prefixes[j].value ] = inner_data;
		}

		var arr = {
			'action': 'save_grid_breakpoints_settings',
			'json': JSON.stringify(data)
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, arr, function(response) {
			jQuery('body').append(response);
			alert("saved data");
		});
	}

	/*
	*	Activated by button click, saves all the data to the database
	*/
	function saveFeature( feature_slug ) {
		var topListsSelector = '#feature_options__' + feature_slug + ' .feature_bp > .top_list';

		var data = {}; //holds all the objects
		var lists = $(topListsSelector);
		var breakpoint_prefixes = $('#main_grid_options .prefix');

		/*
		*	This loop builds the object for saving in the database
		*/
		for (var j = 0; j < lists.length; j++) {
			var inner_data = {}; // holds each individual object
			var json = {}; // holds a checklist
			json = save_data($(lists[j]));
			inner_data['nested'] = json;

			/*
			*	This loop adds the input data to the object
			*/
			for (var i = 0; i < $(breakpoint_prefixes[j]).siblings('input').length;i++) {

				//I only want text and radio inputs, I don't want any buttons
				if ($(breakpoint_prefixes[j]).siblings('input')[i].type !== 'radio' &&  $(breakpoint_prefixes[j]).siblings('input')[i].type !== 'button') {
					inner_data[$(breakpoint_prefixes[j]).siblings('input')[i].className] = $(breakpoint_prefixes[j]).siblings('input')[i].value;
				}
				else if ($(breakpoint_prefixes[j]).siblings('input')[i].type === 'radio') {
					inner_data[$(breakpoint_prefixes[j]).siblings('input')[i].className] = $($(breakpoint_prefixes[j]).siblings('input')[i]).is(':checked');

				}
			}

			data [ breakpoint_prefixes[j].value ] = inner_data;
		}

		var arr = {
			'action': 'save_grid_feature_settings',
			'json': JSON.stringify(data),
			'feature_slug': feature_slug
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, arr, function(response) {
			jQuery('body').append(response);
			alert("saved data");
		});
	}

	function addBreakpoint(obj) {

		/*
		* Adds in new breakpoint
		*	+ lets a string span multiple lines
		*/
		var html = '<div class="bp"> '+
						'Breakpoint Prefix (all lowercase, letters only): <input class="prefix"> </br>'+
						'Min Breakpoint width (px): <input class="min_width"> </br>'+
						'Max Breakpoint width (px): <input class="max_width"> </br>'+
						'Number of Columns: <input class="col_number"> </br>'+
						'Gutter Width (px) <input class="gutter_width"> </br>'+
						'Container Width Units: </br>'+
						'Pixels <input class="container_width_units_pixels" name="cwu" type="radio"> </br>'+
						'Percent <input class="container_width_units_percent" name="cwu" type="radio"> </br>'+
						'Container Width: <input class="container_width"> </br>'+
						'Container Width Modification Units: </br>'+
						'Pixels <input class="container_width_modification_units_pixels" name="cwmu" type="radio"> </br>'+
						'Percent <input class="container_width_modification_units_percent" name="cwmu" type="radio"> </br>'+
						'Container Width Modification: <input class="container_width_modification"> </br>'+
						'Container Max Width (px): <input class="container_max_width"> </br>'+
						'</br>'+
						'<input class="remove_breakpoint" type="button" value="Remove Breakpoint"> '+
						'</br>'+
						'<hr>'+
					'</div>';

		$('.break_points').append(html);
	}

	/*
	*	Removes breakpoint and checklist when the remove breakpoint button is pressed
	*/
	function removeBreakpoint(obj) {
		$(obj).parent().remove();
	}

	/*
	*	Handles the parent sub click
	*/
	function mainNested(obj) {
		if (true === obj.checked) {
			$(obj).parent().append("<ul class='mainNested' name='mainNested'><div class='checked_list__display_toggle'>+</div><li><input class='mainSubs' name='standardCol' type='checkbox'>Standard Column</input></li><li><input class='plusHalf' name='plusHalfCol' type='checkbox'>Plus Half Column</input></li><li><input class='mainSubs' name='plus1Gutter' type='checkbox'>Plus 1 Gutter</input></li><li><input class='mainSubs' name='plus2Gutter' type='checkbox'>Plus 2 Gutters</input></li><li><input class='mainSubs' name='plus1Gutter1Inner' type='checkbox'>Plus 1 Gutter and 1 Inner</input></li><li><input class='mainSubs' name='plus0Gutter1Inner' type='checkbox'>Plus 0 Gutters and 1 inner</input></li></ul>");
		}
		else {
			$(obj).parent().find('.mainNested').remove();
		}
	}

	/*
	*	Handles nested grid element click
	*/
	function parentColumWidth(obj) {
		if (true === obj.checked) {
			$(obj).parent().append("<ul class='parentElements' name='parentElements'><div class='checked_list__display_toggle'>+</div><li><input class='parentSub' name='parentEleWidths' type='checkbox'>PARENT ELEMENT WIDTHS</input></li><li><input class='parentSub' name='parentOffRight' type='checkbox'>PARENT OFFSET RIGHT</input></li><li><input class='parentSub' name='parentOffLeft' type='checkbox'>PARENT OFFSET LEFT</input></li></ul>");
		}
		else {
			$(obj).parent().find('.parentElements').remove();
		}
	}

	/*
	*	Handles the first level of nesting click
	*	Also needs the number of columns to print passed in
	*/
	function topNestedGrid(obj, num) {
		if (true === obj.checked) {

			var html = '<ul class="parent_columns" data-num="'+ num + '"><div class="checked_list__display_toggle">+</div>';
			for (var count = 0; count <= num; count++) {

				html += '<li><input class="parentColumn" name="parentColumn' + count + '" type="checkbox">Parent Column Width: ' + count + '</input></li>';
			}
			html += '</ul>';
			$(obj).parent().append(html);
		}
		else {
			$(obj).parent().find('.parent_columns').remove();
		}
	}

	/*
	*	Handles the number of columns for child selections
	*/
	function childCols(obj, num) {
		if (true === obj.checked) {

			var html = '<ul class="child_columns" data-num="'+ num + '"><div class="checked_list__display_toggle">+</div>';
			for (var count = 0; count <= num; count++) {

				html += '<li><input class="childColumn" name="childColumn' + count + '" type="checkbox">Child Column Width: ' + count + '</input></li>';
			}
			html += '</ul>';
			$(obj).parent().append(html);
		}
		else {
			$(obj).parent().find('.child_columns').remove();
		}
	}

	/*
	*	Handles the parent sub click
	*/
	function parentSub(obj) {
		if (true === obj.checked) {
			$(obj).parent().append("<ul class='parentSubList' name='parentSubList'><div class='checked_list__display_toggle'>+</div><li><input class='parentSubSub' name='standardCol' type='checkbox'>Standard Column</input></li><li><input class='parentSubSub' name='plusHalfCol' type='checkbox'>Plus Half Column</input></li><li><input class='parentSubSub' name='plus1Gutter' type='checkbox'>Plus 1 Gutter</input></li><li><input class='parentSubSub' name='plus2Gutter' type='checkbox'>Plus 2 Gutters</input></li><li><input class='parentSubSub' name='plus1Gutter1Inner' type='checkbox'>Plus 1 Gutter and 1 Inner</input></li><li><input class='parentSubSub' name='plus0Gutter1Inner' type='checkbox'>Plus 0 Gutters and 1 inner</input></li></ul>");
		}
		else {
			$(obj).parent().find('.parentSubList').remove();
		}
	}

	/*
	*	Handles the parent sub sub click
	*/
	function parentSubSub(obj) {
		if (true === obj.checked) {
			$(obj).parent().append("<ul class='childElements' name='childElements'><div class='checked_list__display_toggle'>+</div><li><input class='childSub' name='childEleWidth' type='checkbox'>CHILD ELEMENT WIDTHS</input></li><li><input class='childSub' name='childOffRight' type='checkbox'>CHILD OFFSET RIGHT</input></li><li><input class='childSub' name='childOffLeft' type='checkbox'>CHILD OFFSET LEFT</input></li><li><input class='childSub' name='childRevOffRight' type='checkbox'>CHILD REVERSE OFFSET RIGHT</input></li><li><input class='childSub' name='childRevOffLeft' type='checkbox'>CHILD REVERSE OFFSET LEFT</input></li></ul>");
		}
		else {
			$(obj).parent().find('.childElements').remove();
		}

	}

	/*
	*	Handles the child sub click
	*/
	function childElements(obj) {
		if (true === obj.checked) {
			$(obj).parent().append("<ul class='childSubSub' name='childSubSub'><div class='checked_list__display_toggle'>+</div><li><input class='ChildSubs' name='standardCol' type='checkbox'>Standard Column</input></li><li><input class='ChildSubs' name='plusHalfCol' type='checkbox'>Plus Half Column</input></li><li><input class='ChildSubs' name='plus1Gutter' type='checkbox'>Plus 1 Gutter</input></li><li><input class='ChildSubs' name='plus2Gutter' type='checkbox'>Plus 2 Gutters</input></li><li><input class='ChildSubs' name='plus1Gutter1Inner' type='checkbox'>Plus 1 Gutter and 1 Inner</input></li><li><input class='ChildSubs' name='plus0Gutter1Inner' type='checkbox'>Plus 0 Gutters and 1 inner</input></li></ul>");
		}
		else {

			$(obj).parent().find('.childSubSub').remove();
		}
	}

	/*
	*	Recursively saves data from a checklist into array
	*	It looks for input boxes with a ul as a child to recurse on
	*	It saves any checked boxes as the key with their children below them
	*	Leaf nodes are saved as name = true
	*	Returns array
	*/
	function save_data(checklist) {

		var arr = {};
		var tempRemoveDisplayClass = false;
		if (jQuery(checklist[0]).hasClass('display_toggle__closed')) {
			tempRemoveDisplayClass = true;
			jQuery(checklist[0]).removeClass('display_toggle__closed')
		}
		arr[checklist[0].className] = "";
		if (tempRemoveDisplayClass) {
			tempRemoveDisplayClass = false;
			jQuery(checklist[0]).addClass('display_toggle__closed')
		}

		// Loop through all children of the passed in object
		for (var i = 0; i < checklist.children('li').length;i++) {

			// I use .length because the selector will always return something, but if nothing matched it'll be of length 0
			// Check for ul as child
			if ( $(checklist.children('li')[i]).has('ul').children('ul').length !== 0 ) {
				//take current input name as key and use recursive call as value (will be an object)
				arr[ $(checklist.children('li')[i]).children('input')[0].name ] = save_data($($(checklist.children('li')[i]).has('ul').children('ul')[0]));
			}
			else {
				// If the current input box is checked then add it to currect object with name as key and value as true
				if ($(checklist.children('li')[i]).children('input')[0].checked) {
					arr[$(checklist.children('li')[i]).children('input')[0].name] = 'true';
				}
				else {
					arr[$(checklist.children('li')[i]).children('input')[0].name] = 'false';
				}
			}
		}

		return arr;
	}

});
