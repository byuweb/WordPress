<?php

/* Create Javascript Grid Overlay
–––––––––––––––––––––––––––––––––––––––––––––––––– */
add_action( 'wp_ajax_get_grid_css', 'get_grid_css' );
function get_grid_css( $breakpoints ) {
	
	$s = get_grid_css__universal();

	foreach ( $breakpoints as $prefix => $breakpoint ) {

		$total_cols = $breakpoint->col_number;
		$window_width_prefix = $prefix;
		$padding_value = ($breakpoint->gutter_width / 12); // In rems
		$min_width = $breakpoint->min_width;
		$max_width = $breakpoint->max_width;

		// Begin media query
		$s .= "\n\n@media";

		// Add min_width qualifier to media query
		if ( '' !== $min_width ) {
			$s .= ' (min-width: ' . $min_width . 'px)';
		}

		// Add max_width qualifier to media query
		if ( '' !== $max_width ) {
			// Add 'and' only if we already added min_width qualifier
			if ( '' !== $min_width ) {
				$s .= ' and';
			}
			$s .= ' (max-width: ' . $max_width . 'px)';
		}

		// Begin media query inclusion
		$s .= " {\n";

		// Get standard top-level grid css
		$s .= get_grid_css__standard( $prefix, $breakpoint );

		// Get nested grid css
		/* Set the CSS rules for the Child grid classes (allows grid children to use the grid sizing) */

		$nested_info = (array) $breakpoint->nested;
		// Only include nesting if included in settings
		if ( 'false' !== $nested_info['top-nested_grid'] ) {

			for($outer_col_size = 0; $outer_col_size <= $total_cols; $outer_col_size++) {
				
				// Test to see if this parent col size should be included
				$string_outer_col_size = (string) $outer_col_size;
				$col_size_name = 'parentColumn' . $string_outer_col_size;
				$col_sizes = (array) $nested_info['top-nested_grid'];
				if ( 'false' === $col_sizes[$col_size_name] || null === $col_sizes[$col_size_name]) {
					continue;
				}

				// Proceed
				for($inner_col_size = 0; $inner_col_size <= $total_cols; $inner_col_size++) {
					$s .= get_grid_css__nested( $prefix, $breakpoint, $outer_col_size, $inner_col_size );
				}
			}

		}

		// End media query inclusion
		$s .= "}\n\n";

	}

	return $s;
}

/**
 * Retrieves the grid settings referring to all breakpoints.
 *
 * @return {String} The string representatino of the universal grid css
 */
add_action( 'wp_ajax_get_grid_css__universal', 'get_grid_css__universal' );
function get_grid_css__universal() {
	$s = '';

	/* Grid Overlay */
	$s .= ".show-grid {
	display: none;
	position: fixed;
	top: 0;
	bottom: 0;
	width: 100%;
	z-index: 9999999;
}\n";

	$s .= ".show-grid .container {
	padding-top: 0;
	padding-bottom: 0;
}\n";

	$s .= ".show-grid [class*='col-'] {
	border-right: 1px solid #ccc;
}\n";

	$s .= ".show-grid [class*='col-']:first-child {
	border-left: 1px solid #ccc;
}\n";

	$s .= ".show-grid.on {
	display: block;
}\n";

	$s .= ".show-grid__column {
	height: 3000px;
	background: rgba(255,0,0,0.05);
}\n";

	/* General Grid Settings */
	$s .= ".container { 
	position: relative;
	margin: 0 auto;
	box-sizing: border-box;
	overflow: hidden;
}\n";

	$s .= "[class*='col-'] { 
	float: left;
	width: 100%;
	box-sizing: border-box;
}\n";

	$s .= ".row { 
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
}\n";

	return $s;
}


/**
 *
 */
function get_grid_css__standard( $prefix, $breakpoint ) {
	$s = '';

	// Set local variables
	$total_cols = $breakpoint->col_number;
	$window_width_prefix = $prefix;
	$padding_value = ($breakpoint->gutter_width / 12); // In rems
	$min_width = $breakpoint->min_width;
	$max_width = $breakpoint->max_width;

	$container_width = get_grid_container_width(
		$breakpoint->container_width_units_pixels,
		$breakpoint->container_width,
		$breakpoint->container_width_modification_units_pixels,
		$breakpoint->container_width_modification
	);
	$container_max_width = $breakpoint->container_max_width . 'px';
	if ( ! isset( $breakpoint->container_max_width ) || '' === $breakpoint->container_max_width ) {
		$container_max_width = ' initial';
	}

	$positioning_types = array(
		array(
			'css_class_part' => 'col',
			'css_attribute_name' => 'width',
		),
		array(
			'css_class_part' => 'offset-left-col',
			'css_attribute_name' => 'margin-left',
		),
		array(
			'css_class_part' => 'offset-right-col',
			'css_attribute_name' => 'margin-right',
		),
	);

	// Set booleans for css export inclusion
	$nested_info = (array) $breakpoint->nested;
	if ( 'false' === $nested_info['top-element_widths'] || false === $nested_info['top-element_widths'] ) {
		$positioning_types[0]['include'] = false;
	} else {
		$positioning_types[0]['include'] = (array) $nested_info['top-element_widths'];
	}
	if ( 'false' === $nested_info['top-offset_left'] || false === $nested_info['top-offset_left'] ) {
		$positioning_types[1]['include'] = false;
	} else {
		$positioning_types[1]['include'] = (array) $nested_info['top-offset_left'];
	}
	if ( 'false' === $nested_info['top-offset_right'] || false === $nested_info['top-offset_right'] ) {
		$positioning_types[2]['include'] = false;
	} else {
		$positioning_types[2]['include'] = (array) $nested_info['top-offset_right'];
	}

	// General breakpoint settings
	$s .= "\t.container { \n\t\twidth: $container_width;\n\t\tmax-width:$container_max_width; }\n";
	$s .= "\t.$window_width_prefix-no-padding { padding-right: 0 !important; padding-left: 0 !important; }\n";
	$s .= "\t.$window_width_prefix-hidden { display: none !important; }\n";
	$s .= "\t.$window_width_prefix-standard-padding { padding: 0 " . $padding_value . "rem !important; }\n";
	$s .= "\t[class*='col-'] { padding: 0 " . $padding_value . "rem; }\n";
	$s .= "\t[class*='offset-right'] { float: right; }\n";
	$s .= "\t.$window_width_prefix-offset-right-to-margin { float: left; }\n";
	$s .= "\t.$window_width_prefix-no-float { float: initial; }\n\n";

	// Set the CSS rules for the base grid classes
	$sub_col_types = get_framework_sub_col_types();
	for ( $outer_col_size = 0; $outer_col_size <= $total_cols; $outer_col_size++ ) {

		$s .= "\n\t/*_____________________________________________*/";
		$s .= "\n\t/*_______ Column Size: $outer_col_size */";
		$s .= "\n\t/*_______ Left Offset Size: $outer_col_size */";
		$s .= "\n\t/*_______ Right Offset Size: $outer_col_size */";
		$s .= "\n\t/*_____________________________________________*/\n";

		foreach ( $positioning_types as $positioning_type ) {
			// Leave if not included
			if ( false === $positioning_type['include'] ) {
				continue;
			}

			// Proceed
			$pos_type__css_class_part = $positioning_type['css_class_part'];
			$pos_type__css_attribute_name = $positioning_type['css_attribute_name'];

			foreach ( $sub_col_types as $name => $outer_sub_col_type ) {
				// Leave if not included
				if ( 'std' === $name && 'false' === $positioning_type['include']['standardCol'] ) {
					continue;
				} elseif ( 'p5' === $name && 'false' === $positioning_type['include']['plusHalfCol'] ) {
					continue;
				} elseif ( 'gutter-0-inner-1' === $name && 'false' === $positioning_type['include']['plus0Gutter1Inner'] ) {
					continue;
				} elseif ( 'gutter-1' === $name && 'false' === $positioning_type['include']['plus1Gutter'] ) {
					continue;
				} elseif ( 'gutter-1-inner-1' === $name && 'false' === $positioning_type['include']['plus1Gutter1Inner'] ) {
					continue;
				} elseif ( 'gutter-2' === $name && 'false' === $positioning_type['include']['plus2Gutter'] ) {
					continue;
				}

				// Proceed
				$outer_sub_col_class = $outer_sub_col_type['class_slug'];

				$base_one_col_width = (1 / $total_cols) * 100;
				$width_for_col_size = PGSF_container__width($base_one_col_width, $outer_col_size, $padding_value, $name);

				// Append new css declaration statement
				$s .= "\t.$window_width_prefix-$pos_type__css_class_part-$outer_col_size$outer_sub_col_class\t { $pos_type__css_attribute_name: $width_for_col_size; }\n";
			}

			$s .= "\n";
		}
	}

	return $s;
}

/**
 *
 */
function get_grid_css__nested( $prefix, $breakpoint, $outer_col_size, $inner_col_size ) {

	$s = '';

	// Set local variables
	$total_cols = $breakpoint->col_number;
	$window_width_prefix = $prefix;
	$padding_value = ($breakpoint->gutter_width / 12); // In rems
	$min_width = $breakpoint->min_width;
	$max_width = $breakpoint->max_width;

	$container_width = get_grid_container_width(
		$breakpoint->container_width_units_pixels,
		$breakpoint->container_width,
		$breakpoint->container_width_modification_units_pixels,
		$breakpoint->container_width_modification
	);
	$container_max_width = $breakpoint->container_max_width . 'px';
	if ( ! isset( $breakpoint->container_max_width ) || '' === $breakpoint->container_max_width ) {
		$container_max_width = ' initial';
	}

	$positioning_types = array(
		array(
			'title' => 'Size',
			'css_class_part' => 'col',
			'css_attribute_name' => 'width',
			'css_attribute_negation' => '1'
		),
		array(
			'title' => 'Left Offset',
			'css_class_part' => 'offset-left-col',
			'css_attribute_name' => 'margin-left',
			'css_attribute_negation' => '1'
		),
		array(
			'title' => 'Right Offset',
			'css_class_part' => 'offset-right-col',
			'css_attribute_name' => 'margin-right',
			'css_attribute_negation' => '1'
		),
		array(
			'title' => 'Reverse Left Offset',
			'css_class_part' => 'reverse-offset-left-col',
			'css_attribute_name' => 'margin-left',
			'css_attribute_negation' => '-1'
		),
		array(
			'title' => 'Reverse Right Offset',
			'css_class_part' => 'reverse-offset-right-col',
			'css_attribute_name' => 'margin-right',
			'css_attribute_negation' => '-1'
		),
	);

	// Nested inner-outer col size combo comment
	$s .= "\n\t/*______________________________________________________________________________________________*/";
	$s .= "\n\t/*_______ From Parent Col Size: $outer_col_size _____ To Child Col Size: $inner_col_size */";
	$s .= "\n\t/*_______ From Parent Col Size: $outer_col_size _____ To Child Left Offset Size: $inner_col_size */";
	$s .= "\n\t/*_______ From Parent Col Size: $outer_col_size _____ To Child Right Offset Size: $inner_col_size */";
	$s .= "\n\t/*_______ From Parent Col Size: $outer_col_size _____ To Child Reverse Left Offset Size: $inner_col_size */";
	$s .= "\n\t/*_______ From Parent Col Size: $outer_col_size _____ To Child Reverse Right Offset Size: $inner_col_size */";
	$s .= "\n\t/*______________________________________________________________________________________________*/\n";

	$sub_col_types = get_framework_sub_col_types();
	foreach ( $positioning_types as $positioning_type ) {

		$pos_type__title = $positioning_type['title'];
		$pos_type__css_class_part = $positioning_type['css_class_part'];
		$pos_type__css_attribute_name = $positioning_type['css_attribute_name'];
		$pos_type__css_attribute_negation = $positioning_type['css_attribute_negation'];

		// Nested classes
		$s .= "\n\t/*_______ $pos_type__title */\n";
		// For every parent column width
		foreach ( $sub_col_types as $parent_prefix => $outer_sub_col_type ) {
			$outer_sub_col_class = $outer_sub_col_type['class_slug'];

			// For every child column width
			foreach ( $sub_col_types as $child_prefix => $inner_sub_col_type ) {
				$inner_sub_col_class = $inner_sub_col_type['class_slug'];

				// Compute the conversion (with and without padding on parent) to 1 column
				$to_one_col = PGSF_container__to_1_col_width_convert($padding_value, $outer_col_size, true, $parent_prefix);
				$to_one_col__no_padding = PGSF_container__to_1_col_width_convert($padding_value, $outer_col_size, false, $parent_prefix);

				// Compute the conversion from 1 column to the desired size
				$to_final_col = PGSF_element__to_final_col_width_convert( $padding_value, $inner_col_size, $child_prefix);

				if ( true === can_include_nested_grid_statement( $breakpoint, $outer_col_size, $parent_prefix, $pos_type__title, $inner_col_size, $child_prefix ) ) {
					$s .= ".$window_width_prefix-col-$outer_col_size$outer_sub_col_class > .$window_width_prefix-$pos_type__css_class_part-$inner_col_size$inner_sub_col_class { $pos_type__css_attribute_name: calc( $pos_type__css_attribute_negation * ($to_one_col * $to_final_col)); }\t";
					$s .= ".$window_width_prefix-no-padding.$window_width_prefix-col-$outer_col_size$outer_sub_col_class > .$window_width_prefix-$pos_type__css_class_part-$inner_col_size$inner_sub_col_class { $pos_type__css_attribute_name: calc( $pos_type__css_attribute_negation * ($to_one_col__no_padding * $to_final_col)); }\n";
				}
			}
		}

		$s .= "\n";
	}

	return $s;
}

function can_include_nested_grid_statement( $breakpoint_info, $parent_col_width = 'NOPE', $parent_col_width_mod_prefix = 'NOPE', $child_declaration_type = 'NOPE', $child_col_width = 'NOPE', $child_col_width_mod_prefix = 'NOPE' ) {

	// Set booleans for css export inclusion
	$nested_info = (array) $breakpoint_info->nested;
	$nested_grid_info = (array) $nested_info['top-nested_grid'];
	$nested_parent_col_size_name = 'parentColumn' . $parent_col_width;
	$nested_grid_parent_col_info = $nested_grid_info[$nested_parent_col_size_name];

	// Check if parent col width size is selected
	if ( 'false' === $nested_grid_parent_col_info ) {
		return false;
	}
	$nested_grid_parent_col_info = (array) $nested_grid_parent_col_info; // convert obj to array

	if ( 'NOPE' === $parent_col_width_mod_prefix ) { // Allow use to check only 1 value
		return true;
	}

	// Check if parent col width modificatino is selected
	$parent_col_mod_info = '';
	if ( 'std' === $parent_col_width_mod_prefix ) {
		if ( 'false' === $nested_grid_parent_col_info['standardCol'] ) {
			return false;
		}
		$parent_col_mod_info = (array) $nested_grid_parent_col_info['standardCol'];

	} elseif ( 'p5' === $parent_col_width_mod_prefix ) {
		if ( 'false' === $nested_grid_parent_col_info['plusHalfCol'] ) {
			return false;
		}
		$parent_col_mod_info = (array) $nested_grid_parent_col_info['plusHalfCol'];

	} elseif ( 'gutter-0-inner-1' === $parent_col_width_mod_prefix ) {
		if ( 'false' === $nested_grid_parent_col_info['plus0Gutter1Inner'] ) {
			return false;
		}
		$parent_col_mod_info = (array) $nested_grid_parent_col_info['plus0Gutter1Inner'];

	} elseif ( 'gutter-1' === $parent_col_width_mod_prefix ) {
		if ( 'false' === $nested_grid_parent_col_info['plus1Gutter'] ) {
			return false;
		}
		$parent_col_mod_info = (array) $nested_grid_parent_col_info['plus1Gutter'];

	} elseif ( 'gutter-1-inner-1' === $parent_col_width_mod_prefix ) {
		if ( 'false' === $nested_grid_parent_col_info['plus1Gutter1Inner'] ) {
			return false;
		}
		$parent_col_mod_info = (array) $nested_grid_parent_col_info['plus1Gutter1Inner'];

	} elseif ( 'gutter-2' === $parent_col_width_mod_prefix ) {
		if ( 'false' === $nested_grid_parent_col_info['plus2Gutter'] ) {
			return false;
		}
		$parent_col_mod_info = (array) $nested_grid_parent_col_info['plus2Gutter'];
	}

	if ( 'NOPE' === $child_declaration_type ) { // Allow use to check only 2 values
		return true;
	}
	
	// Check if child_declaration type is selected
	$child_type_info = '';
	if ( 'Size' === $child_declaration_type ) {
		if ( 'false' === $parent_col_mod_info['childEleWidth'] ) {
			return false;
		}
		$child_type_info = (array) $parent_col_mod_info['childEleWidth'];

	} elseif ( 'Left Offset' === $child_declaration_type ) {
		if ( 'false' === $parent_col_mod_info['childOffLeft'] ) {
			return false;
		}
		$child_type_info = (array) $parent_col_mod_info['childOffLeft'];

	} elseif ( 'Right Offset' === $child_declaration_type ) {
		if ( 'false' === $parent_col_mod_info['childOffRight'] ) {
			return false;
		}
		$child_type_info = (array) $parent_col_mod_info['childOffRight'];

	} elseif ( 'Reverse Left Offset' === $child_declaration_type ) {
		if ( 'false' === $parent_col_mod_info['childRevOffLeft'] ) {
			return false;
		}
		$child_type_info = (array) $parent_col_mod_info['childRevOffLeft'];

	} elseif ( 'Reverse Right Offset' === $child_declaration_type ) {
		if ( 'false' === $parent_col_mod_info['childRevOffRight'] ) {
			return false;
		}
		$child_type_info = (array) $parent_col_mod_info['childRevOffRight'];
	}

	if ( 'NOPE' === $child_col_width ) { // Allow use to check only 3 values
		return true;
	}

	// Check if child col width is selected
	$temp_name = "childColumn$child_col_width";
	$child_col_size_info = $child_type_info[$temp_name];

	if ( 'false' === $child_col_size_info ) {
		return false;
	}
	$child_col_size_info = (array) $child_col_size_info; // convert obj to array

	if ( 'NOPE' === $child_col_width_mod_prefix ) { // Allow use to check only 4 values
		return true;
	}

	// Check if child col width mod is selected
	if ( 'std' === $child_col_width_mod_prefix ) {
		if ( 'false' === $child_col_size_info['standardCol'] ) {
			return false;
		}

	} elseif ( 'p5' === $child_col_width_mod_prefix ) {
		if ( 'false' === $child_col_size_info['plusHalfCol'] ) {
			return false;
		}

	} elseif ( 'gutter-0-inner-1' === $child_col_width_mod_prefix ) {
		if ( 'false' === $child_col_size_info['plus0Gutter1Inner'] ) {
			return false;
		}

	} elseif ( 'gutter-1' === $child_col_width_mod_prefix ) {
		if ( 'false' === $child_col_size_info['plus1Gutter'] ) {
			return false;
		}

	} elseif ( 'gutter-1-inner-1' === $child_col_width_mod_prefix ) {
		if ( 'false' === $child_col_size_info['plus1Gutter1Inner'] ) {
			return false;
		}

	} elseif ( 'gutter-2' === $child_col_width_mod_prefix ) {
		if ( 'false' === $child_col_size_info['plus2Gutter'] ) {
			return false;
		}
	}

	return true;
}

function get_grid_container_width( $use_px, $container_width, $use_px_offset, $container_offset_width ) {
	$width_string = 'calc(';

	$container_width_postfix = '';
	if ( 'true' === $use_px ) {
		$container_width_postfix = 'rem';
		$container_width = $container_width / 12;
	} else {
		$container_width_postfix = '%';
	}
	$width_string .= grid_prep_number_for_css_equation( $container_width, $container_width_postfix, false );

	$container_offset_width_postfix = '';
	if ( 'true' === $use_px_offset ) {
		$container_offset_width_postfix = 'rem';
		$container_offset_width = $container_offset_width / 12;
	} else {
		$container_offset_width_postfix = '%';
	}
	$width_string .= grid_prep_number_for_css_equation( $container_offset_width, $container_offset_width_postfix, true );

	$width_string .= ')';
	return $width_string;
}

function grid_prep_number_for_css_equation( $number, $postfix, $separate_sign = true ) {
	$string = '';
	// If a bad number is sent in
	if ( ! isset( $number ) || '' === $number ) {
		$number = 0;
	}
	// If there should be extra spacing
	if ( $separate_sign ) {
		if ( strpos( $number, '-' ) > -1 ) {
			$string = str_replace( '-', ' - ', $number );
		} else {
			$string = ' + ' . $number;
		}
	} else {
		$string .= $number;
	}
	$string .= $postfix;
	return $string;
}

/* Sub Column Types information retrieval
–––––––––––––––––––––––––––––––––––––––––––––––––– */
function get_framework_sub_col_types( $name = '', $attribute = '' ) {
	$sub_col_types = array(
		'std' => array(
			'function_slug' => 'std',
			'class_slug' => '',
			'column_difference' => 0,
			'gutter_difference' => 0,
			'nest__to_one_col_width__padding_count' => 2,
			'nest__to_one_col_width__size_offset' => 0,
			'nest__to_full_width__size_offset' => 0,
			'nest__to_full_width__padding_count' => 0
		),
		'p5' => array(
			'function_slug' => 'p5',
			'class_slug' => 'p5',
			'column_difference' => .5,
			'gutter_difference' => 0,
			'nest__to_one_col_width__padding_count' => 2,
			'nest__to_one_col_width__size_offset' => .5,
			'nest__to_full_width__size_offset' => .5,
			'nest__to_full_width__padding_count' => 0
		),
		'gutter-0-inner-1' => array(
			'function_slug' => 'gutter_0_inner_1',
			'class_slug' => '-gutter-0-inner-1',
			'column_difference' => 1,
			'gutter_difference' => -2,
			'nest__to_one_col_width__padding_count' => 4,
			'nest__to_one_col_width__size_offset' => 1,
			'nest__to_full_width__size_offset' => 1,
			'nest__to_full_width__padding_count' => -2
		),
		'gutter-1' => array(
			'function_slug' => 'gutter_1',
			'class_slug' => '-gutter-1',
			'column_difference' => 0,
			'gutter_difference' => 1,
			'nest__to_one_col_width__padding_count' => 1,
			'nest__to_one_col_width__size_offset' => 0,
			'nest__to_full_width__size_offset' => 0,
			'nest__to_full_width__padding_count' => 1
		),
		'gutter-1-inner-1' => array(
			'function_slug' => 'gutter_1_inner_1',
			'class_slug' => '-gutter-1-inner-1',
			'column_difference' => 1,
			'gutter_difference' => -1,
			'nest__to_one_col_width__padding_count' => 3,
			'nest__to_one_col_width__size_offset' => 1,
			'nest__to_full_width__size_offset' => 1,
			'nest__to_full_width__padding_count' => -1
		),
		'gutter-2' => array(
			'function_slug' => 'gutter_2',
			'class_slug' => '-gutter-2',
			'column_difference' => 0,
			'gutter_difference' => 2,
			'nest__to_one_col_width__padding_count' => 0,
			'nest__to_one_col_width__size_offset' => 0,
			'nest__to_full_width__size_offset' => 0,
			'nest__to_full_width__padding_count' => 2
		),
	);

	if ( '' !== $name ) {
		if ( '' !== $attribute ) {
			return $sub_col_types[$name][$attribute];
		}
		return $sub_col_types[$name];
	}

	return $sub_col_types;
}

function PGSF_container__to_1_col_width_convert( $padding_value, $outer_col_size, $using_padding, $parent_prefix ) {
	$make_full_width_offset = grid_prep_number_for_css_equation( get_framework_sub_col_types( $parent_prefix, 'nest__to_one_col_width__padding_count' ) * $padding_value, 'rem' );
	$not_using_padding_reset = grid_prep_number_for_css_equation( -2 * $padding_value, 'rem' ); // doesnt change
	$column_division = $outer_col_size + get_framework_sub_col_types( $parent_prefix, 'nest__to_one_col_width__size_offset' );
	if ( $using_padding ) {
		$width_statement = "((100% $make_full_width_offset) / $column_division)";
	} else {
		$width_statement = "((100% $make_full_width_offset $not_using_padding_reset) / $column_division)";
	}
	return $width_statement;
}

function PGSF_element__to_final_col_width_convert( $padding_value, $inner_col_size, $child_prefix) {
	$column_multiplication = $inner_col_size + get_framework_sub_col_types( $child_prefix, 'nest__to_full_width__size_offset' );
	$final_width_offset = grid_prep_number_for_css_equation( get_framework_sub_col_types( $child_prefix, 'nest__to_full_width__padding_count' ) * $padding_value, 'rem' );
	$width_statement = "$column_multiplication $final_width_offset";
	return $width_statement;
}

function PGSF_container__width( $base_one_col_width, $num_columns, $padding_value, $prefix ) {
	$padding = grid_prep_number_for_css_equation( get_framework_sub_col_types( $prefix, 'gutter_difference' ) * $padding_value, 'rem' );
	$columns = $num_columns + get_framework_sub_col_types( $prefix, 'column_difference' );
	$css_width = 'calc(' . $base_one_col_width . '% * ' . $columns . ' ' . $padding . ')';
	return $css_width;
}
