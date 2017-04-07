<?php
/* Breakpoint information retrieval
–––––––––––––––––––––––––––––––––––––––––––––––––– */
function PGSF_get_framework_breakpoints() {

	// Retrieve the string of information for testing
	$fromDB = get_option( 'PGSF_grid_breakpoints' );
	$breakpoints = explode( '|', $fromDB );
	$breakpointSettings = array();
	for ( $i = 0; $i < sizeof( $breakpoints ); $i++ ) {
		$breakpointSettings[ $i ] = array();
		$breakpoints[ $i ] = explode( ';', $breakpoints[ $i ] );
		for ( $i2 = 0; $i2 < sizeof( $breakpoints[ $i ] ); $i2++ ) {
			$breakpoints[ $i ][ $i2 ] = explode( ':', $breakpoints[ $i ][ $i2 ] );
			if ( sizeof( $breakpoints[ $i ][ $i2 ] ) == 2 ) {
				$key = $breakpoints[ $i ][ $i2 ][0];
				$value = $breakpoints[ $i ][ $i2 ][1];

				$breakpointSettings[ $i ][ $key ] = $value;
			}
		}
	}
	//var_dump( $breakpoints );
	//var_dump( $breakpointSettings );

	/*$breakpoints = array(
		array(
			'prefix' => 'sm',
			'min-width-px' => '',
			'max-width-px' => '639', // Blank for no max
			'num-columns' => '1',
			'gutter-width-px' => '20',
			'container-width-use-px' => false, // true uses px false uses %
			'container-width' => '100',
			'container-width-modify-use-px' => false, // true uses px false uses %
			'container-width-modify' => '',
			'container-max-width' => '' // only accepts pixels
		),
		array(
			'prefix' => 'med',
			'min-width-px' => '640',
			'max-width-px' => '799', // Blank for no max
			'num-columns' => '6',
			'gutter-width-px' => '20',
			'container-width-use-px' => false, // true uses px false uses %
			'container-width' => '100',
			'container-width-modify-use-px' => false, // true uses px false uses %
			'container-width-modify' => '', 
			'container-max-width' => '' // only accepts pixels
		),
		array(
			'prefix' => 'mlg',
			'min-width-px' => '800',
			'max-width-px' => '1023', // Blank for no max
			'num-columns' => '6',
			'gutter-width-px' => '20',
			'container-width-use-px' => false, // true uses px false uses %
			'container-width' => '100',
			'container-width-modify-use-px' => false, // true uses px false uses %
			'container-width-modify' => '',
			'container-max-width' => '' // only accepts pixels
		),
		array(
			'prefix' => 'lg',
			'min-width-px' => '1024',
			'max-width-px' => '1439', // Blank for no max
			'num-columns' => '12',
			'gutter-width-px' => '11',
			'container-width-use-px' => false, // true uses px false uses %
			'container-width' => '100',
			'container-width-modify-use-px' => true, // true uses px false uses %
			'container-width-modify' => '-21',
			'container-max-width' => '1235' // only accepts pixels
		),
		array(
			'prefix' => 'xlg',
			'min-width-px' => '1440',
			'max-width-px' => '', // Blank for no max
			'num-columns' => '12',
			'gutter-width-px' => '18',
			'container-width-use-px' => false, // true uses px false uses %
			'container-width' => '100',
			'container-width-modify-use-px' => false, // true uses px false uses %
			'container-width-modify' => '',
			'container-max-width' => '1235' // only accepts pixels
		),
	);*/
	return $breakpointSettings;
}


/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
/* Create Stylesheets functionality */
/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */

/* Create Javascript Grid Overlay
–––––––––––––––––––––––––––––––––––––––––––––––––– */
function PGSF_add_grid_line_overylay() {
	$breakpoints = PGSF_get_framework_breakpoints();
	$largestColNumber = 0;
	$colClasses = '';
	foreach ( $breakpoints as $breakpoint ) {
		if ( isset( $breakpoint['num-columns'] ) && $breakpoint['num-columns'] > $largestColNumber) {
			$largestColNumber = $breakpoint['num-columns'];
		}
		if ( !isset( $breakpoint['prefix'] ) ) {
			$breakpoint['prefix'] = '';
		}
		$colClasses .= $breakpoint['prefix'] . '-col-1 ';
	}
	?>

	<div class="show-grid">
		<div class="container">
			<div class="row">
				<?php for ( $col = 0; $col < $largestColNumber; $col++ ) { ?>
					<div class="<?php echo $colClasses;?>"><div class="show-grid__column"></div></div>
				<?php } ?>
			</div>
		</div>
	</div>

	<?php
}

/*
DEPRECATED -- PGSF_prep_number_for_css_equation() should be used instead
*/
function PGSF_prep_rem_for_css_equation( $number ) {
	$string = '';
	if ( strpos( $number, '-' ) > -1 ) {
		$string = str_replace( '-', '- ', $number );
	} else {
		$string = '+ ' . $number;
	}
	$string .= 'rem';
	return $string;
}

function PGSF_prep_number_for_css_equation( $number, $postfix, $separateSign = true ) {
	$string = '';
	// If a bad number is sent in
	if ( !isset( $number ) || $number == '' ) {
		$number = 0;
	}
	// If there should be extra spacing
	if ( $separateSign ) {
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

/* standard Column Widths
–––––––––––––––––––––––––––––––––––––––––––––––––– */
function PGSF_std_container__1_col_width_convert( $padding_value, $outer_col_size, $using_padding ) {
	$make_full_width_offset = PGSF_prep_rem_for_css_equation( 2 * $padding_value );
	$not_using_padding_reset = PGSF_prep_rem_for_css_equation( -2 * $padding_value ); // doesnt change
	$column_division = $outer_col_size;
	if ( $using_padding ) {
		$width_statement = '((100% $make_full_width_offset) / $column_division)';
	} else {
		$width_statement = '((100% $make_full_width_offset $not_using_padding_reset) / $column_division)';
	}
	return $width_statement;
}

function PGSF_std_element__final_col_width_convert( $padding_value, $inner_col_size ) {
	$column_multiplication = $inner_col_size;
	$final_width_offset = PGSF_prep_rem_for_css_equation( 0 );
	$width_statement = '$column_multiplication $final_width_offset';
	return $width_statement;
}

function PGSF_std_container__width( $base_one_col_width, $num_columns, $padding_value ) {
	$padding = PGSF_prep_rem_for_css_equation( 0 * $padding_value );
	$columns = $num_columns;
	$css_width = 'calc(' . $base_one_col_width . '% * ' . $columns . ' ' . $padding . ')';
	return $css_width;
}

/* p5
–––––––––––––––––––––––––––––––––––––––––––––––––– */
function PGSF_p5_container__1_col_width_convert( $padding_value, $outer_col_size, $using_padding ) {
	$make_full_width_offset = PGSF_prep_rem_for_css_equation( 2 * $padding_value );
	$not_using_padding_reset = PGSF_prep_rem_for_css_equation( -2 * $padding_value ); // doesnt change
	$column_division = $outer_col_size + .5;
	if ( $using_padding ) {
		$width_statement = '((100% $make_full_width_offset) / $column_division)';
	} else {
		$width_statement = '((100% $make_full_width_offset $not_using_padding_reset) / $column_division)';
	}
	return $width_statement;
}

function PGSF_p5_element__final_col_width_convert( $padding_value, $inner_col_size ) {
	$column_multiplication = $inner_col_size + .5;
	$final_width_offset = PGSF_prep_rem_for_css_equation( 0 );
	$width_statement = '$column_multiplication $final_width_offset';
	return $width_statement;
}

function PGSF_p5_container__width( $base_one_col_width, $num_columns, $padding_value ) {
	$padding = PGSF_prep_rem_for_css_equation( 0 * $padding_value );
	$columns = $num_columns + .5;
	$css_width = 'calc(' . $base_one_col_width . '% * ' . $columns . ' ' . $padding . ')';
	return $css_width;
}

/* -gutter-0-inner-1 
–––––––––––––––––––––––––––––––––––––––––––––––––– */
function PGSF_gutter_0_inner_1_container__1_col_width_convert( $padding_value, $outer_col_size, $using_padding ) {
	$make_full_width_offset = PGSF_prep_rem_for_css_equation( 4 * $padding_value );
	$not_using_padding_reset = PGSF_prep_rem_for_css_equation( -2 * $padding_value ); // doesnt change
	$column_division = $outer_col_size + 1;
	if ( $using_padding ) {
		$width_statement = '((100% $make_full_width_offset) / $column_division)';
	} else {
		$width_statement = '((100% $make_full_width_offset $not_using_padding_reset) / $column_division)';
	}
	return $width_statement;
}

function PGSF_gutter_0_inner_1_element__final_col_width_convert( $padding_value, $inner_col_size ) {
	$column_multiplication = $inner_col_size + 1;
	$final_width_offset = PGSF_prep_rem_for_css_equation( -2 * $padding_value );
	$width_statement = '$column_multiplication $final_width_offset';
	return $width_statement;
}

function PGSF_gutter_0_inner_1_container__width( $base_one_col_width, $num_columns, $padding_value ) {
	$padding = PGSF_prep_rem_for_css_equation( -2 * $padding_value );
	$columns = $num_columns + 1;
	$css_width = 'calc(' . $base_one_col_width . '% * ' . $columns . ' ' . $padding . ')';
	return $css_width;
}

/* -gutter-1 
–––––––––––––––––––––––––––––––––––––––––––––––––– */
function PGSF_gutter_1_container__1_col_width_convert( $padding_value, $outer_col_size, $using_padding ) {
	$make_full_width_offset = PGSF_prep_rem_for_css_equation( $padding_value );
	$not_using_padding_reset = PGSF_prep_rem_for_css_equation( -2 * $padding_value ); // doesnt change
	$column_division = $outer_col_size;
	if ( $using_padding ) {
		$width_statement = '((100% $make_full_width_offset) / $column_division)';
	} else {
		$width_statement = '((100% $make_full_width_offset $not_using_padding_reset) / $column_division)';
	}
	return $width_statement;
}

function PGSF_gutter_1_element__final_col_width_convert( $padding_value, $inner_col_size ) {
	$column_multiplication = $inner_col_size;
	$final_width_offset = PGSF_prep_rem_for_css_equation( $padding_value );
	$width_statement = '$column_multiplication $final_width_offset';
	return $width_statement;
}

function PGSF_gutter_1_container__width( $base_one_col_width, $num_columns, $padding_value ) {
	$padding = PGSF_prep_rem_for_css_equation( $padding_value );
	$columns = $num_columns;
	$css_width = 'calc(' . $base_one_col_width . '% * ' . $columns . ' ' . $padding . ')';
	return $css_width;
}

/* -gutter-1-inner-1
–––––––––––––––––––––––––––––––––––––––––––––––––– */
function PGSF_gutter_1_inner_1_container__1_col_width_convert( $padding_value, $outer_col_size, $using_padding ) {
	$make_full_width_offset = PGSF_prep_rem_for_css_equation( 3 * $padding_value );
	$not_using_padding_reset = PGSF_prep_rem_for_css_equation( -2 * $padding_value ); // doesnt change
	$column_division = $outer_col_size + 1;
	if ( $using_padding ) {
		$width_statement = '((100% $make_full_width_offset) / $column_division)';
	} else {
		$width_statement = '((100% $make_full_width_offset $not_using_padding_reset) / $column_division)';
	}
	return $width_statement;
}

function PGSF_gutter_1_inner_1_element__final_col_width_convert( $padding_value, $inner_col_size ) {
	$column_multiplication = $inner_col_size + 1;
	$final_width_offset = PGSF_prep_rem_for_css_equation( -1 * $padding_value );
	$width_statement = '$column_multiplication $final_width_offset';
	return $width_statement;
}

function PGSF_gutter_1_inner_1_container__width( $base_one_col_width, $num_columns, $padding_value ) {
	$padding = PGSF_prep_rem_for_css_equation( -1 * $padding_value );
	$columns = $num_columns + 1;
	$css_width = 'calc(' . $base_one_col_width . '% * ' . $columns . ' ' . $padding . ')';
	return $css_width;
}

/* -gutter-2
–––––––––––––––––––––––––––––––––––––––––––––––––– */
function PGSF_gutter_2_container__1_col_width_convert( $padding_value, $outer_col_size, $using_padding ) {
	$make_full_width_offset = PGSF_prep_rem_for_css_equation( 0 );
	$not_using_padding_reset = PGSF_prep_rem_for_css_equation( -2 * $padding_value ); // doesnt change
	$column_division = $outer_col_size;
	if ( $using_padding ) {
		$width_statement = '((100% $make_full_width_offset) / $column_division)';
	} else {
		$width_statement = '((100% $make_full_width_offset $not_using_padding_reset) / $column_division)';
	}
	return $width_statement;
}

function PGSF_gutter_2_element__final_col_width_convert( $padding_value, $inner_col_size ) {
	$column_multiplication = $inner_col_size;
	$final_width_offset = PGSF_prep_rem_for_css_equation( 2 * $padding_value );
	$width_statement = '$column_multiplication $final_width_offset';
	return $width_statement;
}

function PGSF_gutter_2_container__width( $base_one_col_width, $num_columns, $padding_value ) {
	$padding = PGSF_prep_rem_for_css_equation( 2 * $padding_value );
	$columns = $num_columns;
	$css_width = 'calc(' . $base_one_col_width . '% * ' . $columns . ' ' . $padding . ')';
	return $css_width;
}
/* Sub Column Types information retrieval
–––––––––––––––––––––––––––––––––––––––––––––––––– */
function PGSF_get_framework_sub_col_types() {
	$sub_col_types = array(
		array(
			'name' => 'std',
			'function_slug' => 'std',
			'class_slug' => '',
		),
		array(
			'name' => 'p5',
			'function_slug' => 'p5',
			'class_slug' => 'p5',
		),
		array(
			'name' => 'gutter-0-inner-1',
			'function_slug' => 'gutter_0_inner_1',
			'class_slug' => '-gutter-0-inner-1',
		),
		array(
			'name' => 'gutter-1',
			'function_slug' => 'gutter_1',
			'class_slug' => '-gutter-1',
		),
		array(
			'name' => 'gutter-1-inner-1',
			'function_slug' => 'gutter_1_inner_1',
			'class_slug' => '-gutter-1-inner-1',
		),
		array(
			'name' => 'gutter-2',
			'function_slug' => 'gutter_2',
			'class_slug' => '-gutter-2',
		),
	);
	return $sub_col_types;
}

/* Container Width Calculation
–––––––––––––––––––––––––––––––––––––––––––––––––– */
function PGSF_get_container_width( $usePx, $containerWidth, $usePxOffset, $containerOffsetWidth ) {
	$widthString = 'calc(';

	$containerWidthPostfix = '';
	if($usePx == 'true') {
		$containerWidthPostfix = 'rem';
		$containerWidth = $containerWidth / 12;
	} else {
		$containerWidthPostfix = '%';
	}
	$widthString .= PGSF_prep_number_for_css_equation($containerWidth, $containerWidthPostfix, false);

	$containerOffsetWidthPostfix = '';
	if($usePxOffset == 'true') {
		$containerOffsetWidthPostfix = 'rem';
		$containerOffsetWidth = $containerOffsetWidth / 12;
	} else {
		$containerOffsetWidthPostfix = '%';
	}
	$widthString .= PGSF_prep_number_for_css_equation($containerOffsetWidth, $containerOffsetWidthPostfix, true);


	$widthString .= ')';
	return $widthString;
}

/* Grid Layout Factory
–––––––––––––––––––––––––––––––––––––––––––––––––– */
add_action( 'wp_ajax_PGSF_generate__grid_layout', 'PGSF_generate__grid_layout' );
function PGSF_generate__grid_layout() {
	$breakpoints = PGSF_get_framework_breakpoints();

	$s = "";

	/* General Grid Settings */
	$s .= "\t.container { 
		position: relative;
		margin: 0 auto;
		box-sizing: border-box;
	}\n";

	$s .= "\t[class*='col-'] { 
		float: left;
		width: 100%;
		box-sizing: border-box;
	}\n";

	$s .= "\t.row { 
		display: flex;
		flex-direction: row;
		flex-wrap: wrap;
	}\n";

	foreach($breakpoints as $breakpoint) {

		$totalCols = $breakpoint['num-columns'];
		$windowWidthPrefix = $breakpoint['prefix'];
		$padding_value = ($breakpoint['gutter-width-px'] / 12); // In rems
		$min_width = $breakpoint['min-width-px'];
		$max_width = $breakpoint['max-width-px'];

		$s .= "\n\n@media";
		if($min_width != '') {
			$s .= ' (min-width: ' . $min_width . 'px)';
		}
		if($max_width != '') {
			if($min_width != '') {
				$s .= ' and'; 
			}
			$s .= ' (max-width: ' . $max_width . 'px)';
		}
		$s .= " {\n";

		/* Set the basic classes for the breakpoint */
		$containerWidth = PGSF_get_container_width($breakpoint['container-width-use-px'], $breakpoint['container-width'], $breakpoint['container-width-modify-use-px'], $breakpoint['container-width-modify']);
		$containerMaxWidth = $breakpoint['container-max-width'] . 'px';
		if(!isset($breakpoint['container-max-width']) || $breakpoint['container-max-width'] == '') {
			$containerMaxWidth = ' initial';
		}
		$s .= "\t.container { \n\t\twidth: $containerWidth;\n\t\tmax-width:$containerMaxWidth; }\n";
		$s .= "\t.$windowWidthPrefix-no-padding { padding-right: 0 !important; padding-left: 0 !important; }\n";
		$s .= "\t.$windowWidthPrefix-hidden { display: none !important; }\n";
		$s .= "\t.$windowWidthPrefix-standard-padding { padding: 0 " . $padding_value . "rem !important; }\n";
		$s .= "\t[class*='col-'] { padding: 0 " . $padding_value . "rem; }\n";
		$s .= "\t[class*='offset-right'] { float: right; }\n\n";

		/* Set the CSS rules for the base grid classes */
		for($outerColSize = 0; $outerColSize <= $totalCols; $outerColSize++) {

			$s .= "\n\t/*_____________________________________________*/";
			$s .= "\n\t/*_______ Column Size: $outerColSize */";
			$s .= "\n\t/*_______ Left Offset Size: $outerColSize */";
			$s .= "\n\t/*_______ Right Offset Size: $outerColSize */";
			$s .= "\n\t/*_____________________________________________*/\n";

			$subColTypes = PGSF_get_framework_sub_col_types();
			foreach($subColTypes as $outerSubColType) {
				$outerSubColClass = $outerSubColType['class_slug'];

				$baseOneColWith = (1 / $totalCols) * 100;
				$findWidthFunc = "PGSF_" . $outerSubColType['function_slug'] . "_container__width";
				$widthForColSize = $findWidthFunc($baseOneColWith, $outerColSize, $padding_value);

				$s .= "\t.$windowWidthPrefix-col-$outerColSize$outerSubColClass\t { width: $widthForColSize; }\n";
			}

			$s .= "\n";

			$s .= "\n\t/*_______ Left Offset */\n";

			$subColTypes = PGSF_get_framework_sub_col_types();
			foreach($subColTypes as $outerSubColType) {
				$outerSubColClass = $outerSubColType['class_slug'];

				$baseOneColWith = (1 / $totalCols) * 100;
				$findWidthFunc = "PGSF_" . $outerSubColType['function_slug'] . "_container__width";
				$widthForColSize = $findWidthFunc($baseOneColWith, $outerColSize, $padding_value);

				$s .= "\t.$windowWidthPrefix-offset-left-col-$outerColSize$outerSubColClass\t { margin-left: $widthForColSize; }\n";
			}

			$s .= "\n";

			$s .= "\n\t/*_______ Right Offset */\n";

			$subColTypes = PGSF_get_framework_sub_col_types();
			foreach($subColTypes as $outerSubColType) {
				$outerSubColClass = $outerSubColType['class_slug'];

				$baseOneColWith = (1 / $totalCols) * 100;
				$findWidthFunc = "PGSF_" . $outerSubColType['function_slug'] . "_container__width";
				$widthForColSize = $findWidthFunc($baseOneColWith, $outerColSize, $padding_value);

				$s .= "\t.$windowWidthPrefix-offset-right-col-$outerColSize$outerSubColClass\t { margin-right: $widthForColSize; }\n";
			}

			$s .= "\n";
		}


		/* Set the CSS rules for the Child grid classes (allows grid children to use the grid sizing) */
		for($outerColSize = 0; $outerColSize <= $totalCols; $outerColSize++) {

			for($innerColSize = 0; $innerColSize <= $totalCols; $innerColSize++) {

				$s .= "\n\t/*______________________________________________________________________________________________*/";
				$s .= "\n\t/*_______ From Parent Col Size: $outerColSize _____ To Child Col Size: $innerColSize */";
				$s .= "\n\t/*_______ From Parent Col Size: $outerColSize _____ To Child Left Offset Size: $innerColSize */";
				$s .= "\n\t/*_______ From Parent Col Size: $outerColSize _____ To Child Right Offset Size: $innerColSize */";
				$s .= "\n\t/*_______ From Parent Col Size: $outerColSize _____ To Child Reverse Left Offset Size: $innerColSize */";
				$s .= "\n\t/*_______ From Parent Col Size: $outerColSize _____ To Child Reverse Right Offset Size: $innerColSize */";
				$s .= "\n\t/*______________________________________________________________________________________________*/\n";

				$s .= "\n\t/*_______ Size */\n";
				$subColTypes = PGSF_get_framework_sub_col_types();
				foreach($subColTypes as $outerSubColType) {
					$outerSubColClass = $outerSubColType['class_slug'];
					$convertFromFunc = "PGSF_" . $outerSubColType['function_slug'] . "_container__1_col_width_convert";

					foreach($subColTypes as $innerSubColType) {
						$innerSubColClass = $innerSubColType['class_slug'];
						$convertToFunc = "PGSF_" . $innerSubColType['function_slug'] . "_element__final_col_width_convert";

						$toOneCol = $convertFromFunc($padding_value, $outerColSize, true);
						$toFinalCol = $convertToFunc($padding_value, $innerColSize);
						$s .= ".$windowWidthPrefix-col-$outerColSize$outerSubColClass > .$windowWidthPrefix-col-$innerColSize$innerSubColClass { width: calc($toOneCol * $toFinalCol); }\t";
						$toOneCol = $convertFromFunc($padding_value, $outerColSize, false);
						$s .= ".$windowWidthPrefix-no-padding.$windowWidthPrefix-col-$outerColSize$outerSubColClass > .$windowWidthPrefix-col-$innerColSize$innerSubColClass { width: calc($toOneCol * $toFinalCol); }\n";
					}
					$s .= "\n";
				}

				$s .= "\n";

				$s .= "\n\t/*______________ Left Offset */\n";

				$subColTypes = PGSF_get_framework_sub_col_types();
				foreach($subColTypes as $outerSubColType) {
					$outerSubColClass = $outerSubColType['class_slug'];
					$convertFromFunc = "PGSF_" . $outerSubColType['function_slug'] . "_container__1_col_width_convert";

					foreach($subColTypes as $innerSubColType) {
						$innerSubColClass = $innerSubColType['class_slug'];
						$convertToFunc = "PGSF_" . $innerSubColType['function_slug'] . "_element__final_col_width_convert";

						$toOneCol = $convertFromFunc($padding_value, $outerColSize, true);
						$toFinalCol = $convertToFunc($padding_value, $innerColSize);
						$s .= ".$windowWidthPrefix-col-$outerColSize$outerSubColClass > .$windowWidthPrefix-offset-left-col-$innerColSize$innerSubColClass { margin-left: calc($toOneCol * $toFinalCol); }\t";
						$toOneCol = $convertFromFunc($padding_value, $outerColSize, false);
						$s .= ".$windowWidthPrefix-no-padding.$windowWidthPrefix-col-$outerColSize$outerSubColClass > .$windowWidthPrefix-offset-left-col-$innerColSize$innerSubColClass { margin-left: calc($toOneCol * $toFinalCol); }\n";
					}
					$s .= "\n";
				}

				$s .= "\n";

				$s .= "\n\t/*______________ Right Offset */\n";

				$subColTypes = PGSF_get_framework_sub_col_types();
				foreach($subColTypes as $outerSubColType) {
					$outerSubColClass = $outerSubColType['class_slug'];
					$convertFromFunc = "PGSF_" . $outerSubColType['function_slug'] . "_container__1_col_width_convert";

					foreach($subColTypes as $innerSubColType) {
						$innerSubColClass = $innerSubColType['class_slug'];
						$convertToFunc = "PGSF_" . $innerSubColType['function_slug'] . "_element__final_col_width_convert";

						$toOneCol = $convertFromFunc($padding_value, $outerColSize, true);
						$toFinalCol = $convertToFunc($padding_value, $innerColSize);
						$s .= ".$windowWidthPrefix-col-$outerColSize$outerSubColClass > .$windowWidthPrefix-offset-right-col-$innerColSize$innerSubColClass { margin-right: calc($toOneCol * $toFinalCol); }\t";
						$toOneCol = $convertFromFunc($padding_value, $outerColSize, false);
						$s .= ".$windowWidthPrefix-no-padding.$windowWidthPrefix-col-$outerColSize$outerSubColClass > .$windowWidthPrefix-offset-right-col-$innerColSize$innerSubColClass { margin-right: calc($toOneCol * $toFinalCol); }\n";
					}
					$s .= "\n";
				}

				$s .= "\n";

				$s .= "\n\t/*______________ Reverse Left Offset */\n";

				$subColTypes = PGSF_get_framework_sub_col_types();
				foreach($subColTypes as $outerSubColType) {
					$outerSubColClass = $outerSubColType['class_slug'];
					$convertFromFunc = "PGSF_" . $outerSubColType['function_slug'] . "_container__1_col_width_convert";

					foreach($subColTypes as $innerSubColType) {
						$innerSubColClass = $innerSubColType['class_slug'];
						$convertToFunc = "PGSF_" . $innerSubColType['function_slug'] . "_element__final_col_width_convert";

						$toOneCol = $convertFromFunc($padding_value, $outerColSize, true);
						$toFinalCol = $convertToFunc($padding_value, $innerColSize);
						$s .= ".$windowWidthPrefix-col-$outerColSize$outerSubColClass > .$windowWidthPrefix-reverse-offset-left-col-$innerColSize$innerSubColClass { margin-left: calc(-1 * ($toOneCol * $toFinalCol)); }\t";
						$toOneCol = $convertFromFunc($padding_value, $outerColSize, false);
						$s .= ".$windowWidthPrefix-no-padding.$windowWidthPrefix-col-$outerColSize$outerSubColClass > .$windowWidthPrefix-reverse-offset-left-col-$innerColSize$innerSubColClass { margin-left: calc(-1 * ($toOneCol * $toFinalCol)); }\n";
					}
					$s .= "\n";
				}

				$s .= "\n";

				$s .= "\n\t/*______________ Reverse Right Offset */\n";

				$subColTypes = PGSF_get_framework_sub_col_types();
				foreach($subColTypes as $outerSubColType) {
					$outerSubColClass = $outerSubColType['class_slug'];
					$convertFromFunc = "PGSF_" . $outerSubColType['function_slug'] . "_container__1_col_width_convert";

					foreach($subColTypes as $innerSubColType) {
						$innerSubColClass = $innerSubColType['class_slug'];
						$convertToFunc = "PGSF_" . $innerSubColType['function_slug'] . "_element__final_col_width_convert";

						$toOneCol = $convertFromFunc($padding_value, $outerColSize, true);
						$toFinalCol = $convertToFunc($padding_value, $innerColSize);
						$s .= ".$windowWidthPrefix-col-$outerColSize$outerSubColClass > .$windowWidthPrefix-reverse-offset-right-col-$innerColSize$innerSubColClass { margin-right: calc(-1 * ($toOneCol * $toFinalCol)); }\t";
						$toOneCol = $convertFromFunc($padding_value, $outerColSize, false);
						$s .= ".$windowWidthPrefix-no-padding.$windowWidthPrefix-col-$outerColSize$outerSubColClass > .$windowWidthPrefix-reverse-offset-right-col-$innerColSize$innerSubColClass { margin-right: calc(-1 * ($toOneCol * $toFinalCol)); }\n";
					}
					$s .= "\n";
				}

				$s .= "\n";


			}

			$s .= "\n\n";
		}

		$s .= "}\n\n";
	}

	/* Create and Savout the new version of the file
	–––––––––––––––––––––––––––––––––––––––––––––––––– */
	$newCSSfile = dirname(__FILE__) . "/PGSF_grid-layout.css";
	$a = fopen($newCSSfile, 'w');
	fwrite($a, $s);
	fclose($a);
	chmod($newCSSfile, 0775);
}






























/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
/* Manage Breakpoints GUI */
/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
add_action( 'admin_menu', 'my_plugin_menu' );
function my_plugin_menu() {
	$page_title = 'Standard Framework Settings';
	$menu_title = 'Standard Framework';
	$capability = 'manage_options';
	$menu_slug = 'standard-framework';
	$function = 'standardFrameworkSettingsPage';
	add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
}

function standardFrameworkSettingsPage() {
	?>

	<h2>Standard Framework Custom Grid Builder</h2>
	<div id="breakpoint_customization_forms">
		<?php PGSF_output_existing_grid_breakpoints_settings(); ?>
	</div>
	<input type="button" onclick="PGSF_addNewBreakpoint()" name="add-new-breakpoint" value="Add New Breakpoint">
	<input type="button" onclick="PGSF_saveBreakpoints()" name="save-breakpoints" value="Save Breakpoints">

	<hr>
	<hr>
	<hr>
	<input type="button" onclick="PGSF_generateGridStylesheets()" name="generate-grid-stylesheets" value="Generate Grid Stylesheets">
	<hr>
	<hr>
	<hr>
	<?php
	PGSF_display_standard_framework_class_library();
	PGSF_the_standard_framework_settings_js_functions();

}

function PGSF_output_existing_grid_breakpoints_settings() {
	$breakpoints = PGSF_get_framework_breakpoints();
	foreach($breakpoints as $breakpoint) {
		PGSF_output_existing_grid_breakpoint_settings($breakpoint);
	}
}

function PGSF_output_existing_grid_breakpoint_settings($settings) {
	?>

	<form class="breakpoint_customization_form">
		<div class="breakpoint">
			Breakpoint Prefix (All lowercase, letters only): 
			<input class="PGSF_prefix" type="text" name="breakpoint" value="<?php echo $settings['prefix']; ?>"><br>

			Min Breakpoint width (px): 
			<input class="PGSF_min-width-px" type="number" name="min-width-px" value="<?php echo $settings['min-width-px']; ?>"><br>

			Max Breakpoint width (px): 
			<input class="PGSF_max-width-px" type="number" name="min-width-px" value="<?php echo $settings['max-width-px']; ?>"><br>

			Number of Columns: 
			<input class="PGSF_num-columns" type="number" name="num-columns" value="<?php echo $settings['num-columns']; ?>"><br>

			Gutter Width (pxs): 
			<input class="PGSF_gutter-width-px" type="number" name="gutter-width-px" value="<?php echo $settings['gutter-width-px']; ?>"><br>

			Container Width Units:<br>
			<input class="PGSF_container-width-use-px" type="radio" name="container-width-use-px" value="pixel" <?php if($settings['container-width-use-px'] == 'true') { echo 'checked'; } ?> > Pixels<br>
			<input class="PGSF_container-width-use-percent" type="radio" name="container-width-use-px" value="percent" <?php if($settings['container-width-use-px'] != 'true') { echo 'checked'; } ?> > Percent<br>

			Container Width: 
			<input class="PGSF_container-width" type="number" name="container-width" value="<?php echo $settings['container-width']; ?>"><br>

			Container Width Modification Units:<br>
			<input class="PGSF_container-width-modify-use-px" type="radio" name="container-width-modify-use-px" value="pixel" <?php if($settings['container-width-modify-use-px'] == 'true') { echo 'checked'; } ?> > Pixels<br>
			<input class="PGSF_container-width-modify-use-percent" type="radio" name="container-width-modify-use-px" value="percent" <?php if($settings['container-width-modify-use-px'] != 'true') { echo 'checked'; } ?> > Percent<br>

			Container Width Modification: 
			<input class="PGSF_container-width-modify" type="number" name="container-width-modify" value="<?php echo $settings['container-width-modify']; ?>"><br>

			Container Max Width (px): 
			<input class="PGSF_container-max-width" type="number" name="container-max-width" value="<?php echo $settings['container-max-width']; ?>"><br>

			<input type="button" onclick="PGSF_removeBreakpoint(this)" name="remove-breakpoint" value="Remove Breakpoint"><br>
		</div>
		<hr>
	</form>

	<?php
}


add_action( 'wp_ajax_PGSF_get_breakpoint_input_markup', 'PGSF_get_breakpoint_input_markup' );
function PGSF_get_breakpoint_input_markup() {
	?>

	<form class="breakpoint_customization_form">
		<div class="breakpoint">
			Breakpoint Prefix (All lowercase, letters only): 
			<input class="PGSF_prefix" type="text" name="breakpoint"><br>

			Min Breakpoint width (px): 
			<input class="PGSF_min-width-px" type="number" name="min-width-px"><br>

			Max Breakpoint width (px): 
			<input class="PGSF_max-width-px" type="number" name="min-width-px"><br>

			Number of Columns: 
			<input class="PGSF_num-columns" type="number" name="num-columns"><br>

			Gutter Width (pxs): 
			<input class="PGSF_gutter-width-px" type="number" name="gutter-width-px"><br>

			Container Width Units:<br>
			<input class="PGSF_container-width-use-px" type="radio" name="container-width-use-px" value="pixel" checked> Pixels<br>
			<input class="PGSF_container-width-use-percent" type="radio" name="container-width-use-px" value="percent"> Percent<br>

			Container Width: 
			<input class="PGSF_container-width" type="number" name="container-width"><br>

			Container Width Modification Units:<br>
			<input class="PGSF_container-width-modify-use-px" type="radio" name="container-width-modify-use-px" value="pixel" checked> Pixels<br>
			<input class="PGSF_container-width-modify-use-percent" type="radio" name="container-width-modify-use-px" value="percent"> Percent<br>

			Container Width Modification: 
			<input class="PGSF_container-width-modify" type="number" name="container-width-modify"><br>

			Container Max Width (px): 
			<input class="PGSF_container-max-width" type="number" name="container-max-width"><br>

			<input type="button" onclick="PGSF_removeBreakpoint(this)" name="remove-breakpoint" value="Remove Breakpoint"><br>
		</div>
		<hr>
	</form>

	<?php
	wp_die();
}

/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
/* Saving Breakpoint class selections */
/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */

add_action( 'wp_ajax_PGSF_save_breakpoints', 'PGSF_save_breakpoints' );
function PGSF_save_breakpoints() {
	// Store the string of information
	$breakpointsString = $_POST['breakpoints'];
	update_option('PGSF_grid_breakpoints', $breakpointsString, '', 'no');

	// Initialize Library Selections
	/*$breakpoints = PGSF_get_framework_breakpoints();
	$librarySelection = array();
	foreach($breakpoints as $breakpoint) {
		$newPoint = array(
			'prefix' => $breakpoint['prefix'],
			'num_columns' => $breakpoint['num-columns'],
			'col_width' => array(),
			'offset_right' => array(),
			'offset_left' => array(),
			'nested_grid_elements' => array(),
		);
		for($i = 1; $i <= $breakpoint['num-columns']; $i++) {
			$newPoint['nested_grid_elements'][$i] = array(
				'child_col_width' => array(),
				'child_offset_right' => array(),
				'child_offset_left' => array(),
				'child_reverse_offset_right' => array(),
				'child_reverse_offset_left' => array(),
			);
		}
		array_push($librarySelection, $newPoint);
	}
	$serialized = json_encode($librarySelection);
	update_option('PGSF_grid_library_selection', $serialized, '', 'no');

	// Test getting serialized info out of database
	$deserialized = json_decode(get_option('PGSF_grid_library_selection'));
	echo get_option('PGSF_grid_library_selection');
	var_dump($deserialized);*/

	wp_die();
}

































/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
/* Javasript Functions */
/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */

function PGSF_the_standard_framework_settings_js_functions() {
	?>
	<script>
	$ = jQuery
	/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
	/* Manage Breakpoint options */
	/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
	function PGSF_addNewBreakpoint () {
		var data = {
			'action': 'PGSF_get_breakpoint_input_markup' // the name of the php function action to be called
		}

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function (response, status, xhr) {
			if(status == 'success') {
				$('#breakpoint_customization_forms').append(response)
			} else {
				console.log('error')
			}
		})
	}
	/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
	/* Saving Breakpoint selections */
	/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
	function PGSF_saveBreakpoints () {
		var stringifiedBreakpoints = PGSF_stringifyBreakpoints()
		var data = {
			'action': 'PGSF_save_breakpoints', // the name of the php function action to be called
			'breakpoints': stringifiedBreakpoints,
		}

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response, status, xhr) {
			if(status == "success") {
				alert("breakpoints saved");
				console.log(response);
			} else {
				console.log("error");
			}
		});
	}
	function PGSF_stringifyBreakpoints () {
		var breakpoints = 								$('.breakpoint_customization_form')
		var PGSF_prefix__s = 							$('.PGSF_prefix')
		var PGSF_min_width_px__s = 						$('.PGSF_min-width-px')
		var PGSF_max_width_px__s = 						$('.PGSF_max-width-px')
		var PGSF_num_columns__s = 						$('.PGSF_num-columns')
		var PGSF_gutter_width_px__s = 					$('.PGSF_gutter-width-px')
		var PGSF_container_width_use_px__s = 			$('.PGSF_container-width-use-px')
		var PGSF_container_width_use_percent__s = 		$('.PGSF_container-width-use-percent')
		var PGSF_container_width__s = 					$('.PGSF_container-width')
		var PGSF_container_width_modify_use_px__s = 	$('.PGSF_container-width-modify-use-px')
		var PGSF_container_width_modify_use_percent__s =$('.PGSF_container-width-modify-use-percent')
		var PGSF_container_width_modify__s = 			$('.PGSF_container-width-modify')
		var PGSF_container_max_width__s = 				$('.PGSF_container-max-width')

		var string = '';

		for(var i = 0; i < $(breakpoints).length; i++) {

			string += 'prefix:' + 					PGSF_prefix__s[i].value + ';'
			string += 'min-width-px:' + 			PGSF_min_width_px__s[i].value + ';'
			string += 'max-width-px:' + 			PGSF_max_width_px__s[i].value + ';'
			string += 'num-columns:' + 			PGSF_num_columns__s[i].value + ';'
			string += 'gutter-width-px:' + 		PGSF_gutter_width_px__s[i].value + ';'
			if($(PGSF_container_width_use_px__s[i]).is(':checked')) {
				string += 'container-width-use-px:true;'
			} else if($(PGSF_container_width_use_percent__s[i]).is(':checked')) {
				string += 'container-width-use-px:false;'
			}
			string += 'container-width:' + 		PGSF_container_width__s[i].value + ';'
			if($(PGSF_container_width_modify_use_px__s[i]).is(':checked')) {
				string += 'container-width-modify-use-px:true;'
			} else if($(PGSF_container_width_modify_use_percent__s[i]).is(':checked')) {
				string += 'container-width-modify-use-px:false;'
			}
			string += 'container-width-modify:' + 	PGSF_container_width_modify__s[i].value + ';'
			string += 'container-max-width:' + 	PGSF_container_max_width__s[i].value

			// If there is another breakpoint after, we separate with a bar
			if(i + 1 < $(breakpoints).length) {
				string += '|'
			}
		}

		// console.dir(breakpoints);
		return string;
	}
	function PGSF_removeBreakpoint (e) {
		$(e.parentNode.parentNode).remove()
	}
	/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
	/* Generate New Stylesheets */
	/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
	function PGSF_generateGridStylesheets () {
		var stringifiedBreakpoints = PGSF_stringifyBreakpoints()
		var data = {
			'action': 'PGSF_save_breakpoints', // the name of the php function action to be called
			'breakpoints': stringifiedBreakpoints
		}

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function (response, status, xhr) {
			if(status == 'success') {
				alert('breakpoints saved, now creating stylesheets')
				var data = {
					'action': 'PGSF_generate__grid_layout' // the name of the php function action to be called
				}

				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
				jQuery.post(ajaxurl, data, function (response, status, xhr) {
					if(status == 'success') {
						console.log(response)
						alert('new stylesheets generated')
					} else {
						console.log('error')
					}
				})
			} else {
				console.log('error')
			}
		})
	}

	/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
	/* Class Selections Navigation */
	/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
	function PGSF_lib__navigate_library (e, action, typeOfCols) {
		var target = e.target
		var parent = e.target.parentNode
		var sibling = e.target.nextElementSibling
		var columns = $(target).data('columns')
		if (!columns) {
			columns = 0
		}

		if ($(target).attr('checked')) {
			var data = {
				'action': action, // the name of the php function action to be called
				'columns': columns,
				'typeOfCols': typeOfCols
			}

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function (response, status, xhr) {
				if(status == 'success') {
					// Remove any extra dangling listings (from rapid clicking firing AJAX multiple times)
					while(e.target.nextElementSibling) {
						sibling.remove()
						sibling = e.target.nextElementSibling
					}
					// We append the new child listing
					$(parent).append(response)
					$(parent).removeClass('list-closed')
					$(parent).addClass('list-expanded')
				} else {
					console.log('error')
				}
			})
		} else {
			// Remove any extra dangling listings (from rapid clicking firing AJAX multiple times)
			while(e.target.nextElementSibling) {
				sibling.remove()
				sibling = e.target.nextElementSibling
			}
			$(parent).removeClass('list-expanded')
			$(parent).addClass('list-closed')
		}
	}
	$('.PGSF_lib__breakpoint').click(function (e) {
		var action = 'PGSF_lib__get_breakpoint_features'
		PGSF_lib__navigate_library(e, action)
	})
	function PGSF_lib__get_col_widths (e) {
		var action = 'PGSF_lib__get_breakpoint_feature__col_width'
		PGSF_lib__navigate_library(e, action)
	}
	function PGSF_lib__get_col_width_variations (e) {
		var action = 'PGSF_lib__get_breakpoint_feature__col_width_variation'
		PGSF_lib__navigate_library(e, action)
	}
	function PGSF_lib__get_nesting_options (e) {
		var action = 'PGSF_lib__get_breakpoint_feature__col_width'
		var typeOfCols = 'Parent';
		PGSF_lib__navigate_library(e, action, typeOfCols)
	}
	function PGSF_lib__get_nesting_features (e) {
		var action = 'PGSF_lib__get_nested_breakpoint_features'
		var typeOfCols = 'Child';
		PGSF_lib__navigate_library(e, action, typeOfCols)
	}
	function PGSF_lib__get_sublist_checkboxes (checkbox) {
		if (!checkbox) { return null }
		var sublist = []

		var firstCheckbox = PGSF_lib__find_first_sub_checkbox(checkbox)
		sublist.push(firstCheckbox)
		var nextCheckbox = PGSF_lib__find_next_sibling_checkbox(firstCheckbox)
		while (nextCheckbox) {
			sublist.push(nextCheckbox)
			nextCheckbox = PGSF_lib__find_next_sibling_checkbox(nextCheckbox)
		}
		return sublist
	}
	function PGSF_lib__find_first_sub_checkbox (checkbox) {
		if (!checkbox) { return null }
		var sublistSearch = checkbox
		while (sublistSearch.nextElementSibling) {
			optionSublist = sublistSearch.nextElementSibling
			// We have found the sublist for the input
			if ($(optionSublist).hasClass('PGSF_lib__options_list')) {
				if (optionSublist.firstElementChild) {
					optionSublistItem = optionSublist.firstElementChild
					// This is an actual declared list item
					if ($(optionSublistItem).hasClass('PGSF_lib__options_list_item')) {
						return PGSF_lib__find_li_child_checkbox(optionSublistItem)
					}
				}
				return null // There should only be one option sublist per checkbox. If the first one we found doesn't work, we return.
			}
		}
		return null
	}
	function PGSF_lib__find_next_sibling_checkbox (checkbox) {
		if (!checkbox) { return null }
		var siblingSearch = checkbox.parentNode
		while (siblingSearch.nextElementSibling) {
			siblingCheckbox = siblingSearch.nextElementSibling
			// We have found the sublist for the input
			if ($(siblingCheckbox).hasClass('PGSF_lib__options_list_item')) {
				return PGSF_lib__find_li_child_checkbox(siblingCheckbox)
			}
		}
		return null;
	}
	function PGSF_lib__find_li_child_checkbox (listItem) {
		if (!listItem) { return null }
		optionSublistItemCheckbox = listItem.firstElementChild
		if (optionSublistItemCheckbox.type == 'checkbox') {
			return optionSublistItemCheckbox
		}
		while (optionSublistItemCheckbox.nextElementSibling) {
			optionSublistItemCheckbox = optionSublistItemCheckbox.nextElementSibling
			if (optionSublistItemCheckbox.type == 'checkbox') {
				return optionSublistItemCheckbox
			}
		}
	}

	/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
	/* Saving Class selections */
	/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
	function PGSF_lib__save_selection () {
		var breakpoints = $('.PGSF_lib__breakpoint')
		var bpObjects = []
		for(var i = 0; i < breakpoints.length; i++) {
			bpObject = PGSF_lib__turn_checklist_into_js_object(breakpoints[i])
			bpObjects.push(bpObject)
		}

		console.dir(bpObjects)
		var json = pgsfConversionJSToPHP(bpObjects)
		console.log(json)

		var data = {
			'action': 'PGSF_lib__save_selection', // the name of the php function action to be called
			'json_selections': json
		}

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function (response, status, xhr) {
			if(status == 'success') {
				console.log(response)
				console.log('success')
			} else {
				console.log('error')
			}
		})

	}
	function PGSF_lib__turn_checklist_into_js_object (listStart) {
		if (!listStart) { return null }

		// Save all of the data into the javascript object
		var bpObject = $(listStart).data()
		if (!bpObject) {
			bpObject = {}
		}

		// Save sublist Options
		if ($(listStart).attr('checked') && $(listStart).hasClass('PGSF_lib__has_suboptions')) {
			var sublist = PGSF_lib__get_sublist_checkboxes(listStart)
			bpObject[listStart.name] = 'checked'

			console.log(sublist)
			console.log(sublist.length)
			for (var i2 = 0; i2 < sublist.length; i2++) {
				var checkbox = sublist[i2]
				console.log(checkbox)
				bpObject[checkbox.name] = PGSF_lib__turn_checklist_into_js_object(checkbox)
			}
			console.log('endedn')
			
		} else if ($(listStart).attr('checked')) {
			bpObject[listStart.name] = 'checked'
		} else {
			bpObject[listStart.name] = 'unchecked'
		}
		bpObject['name'] = listStart.name
		return bpObject
	}
	</script>

	<style>
	.PGSF_library ul ul {
		margin-left: 20px;
	}
	</style>

	<?php

}


add_action( 'wp_ajax_PGSF_lib__save_selection', 'PGSF_lib__save_selection' );
function PGSF_lib__save_selection() {
	$json = $_POST['json_selections'];
	$object = json_decode( stripslashes( $json ) );
	/** var_dump( $object ); */
	update_option( 'PGSF_grid_class_selections', $object, '', 'no' );

	wp_die();
}



















/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
/* Standard Framework Class Library */
/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
function PGSF_display_standard_framework_class_library() {
	$breakpoints = PGSF_get_framework_breakpoints();
	//update_option('PGSF_grid_class_selections', '', '', 'no');

	if ( sizeof( $breakpoints ) > 0 ) { ?>
		<input type="button" name="saveLibrarySelection" value="Save Library Selection" onclick="PGSF_lib__save_selection()"><br>
		<div class="PGSF_library">
			<?php PGSF_lib__get_breakpoints_selections( $breakpoints ); ?>
		</div>
	<?php }
}

add_action( 'wp_ajax_PGSF_lib__get_breakpoints_selections', 'PGSF_lib__get_breakpoints_selections' );
function PGSF_lib__get_breakpoints_selections( $breakpoints ) {

	$class_selection_breakpoints = json_decode( stripslashes( get_option( 'PGSF_grid_class_selections' ) ) );
	var_dump( $class_selection_breakpoints );
	if ( $class_selection_breakpoints == null ) {
		PGSF_lib__initialize_new_breakpoints_selections();
	} else {
		PGSF_lib__retrieve_breakpoints_selections( $class_selection_breakpoints );
	}

}

function PGSF_lib__initialize_new_breakpoints_selections() {

	?>

	<ul class="PGSF_lib__options_list PGSF_lib__breakpoints">

		<?php
		$breakpoints = PGSF_get_framework_breakpoints();
		foreach ( $breakpoints as $breakpoint ) {
			$name = $class_selection_breakpoint->__name;
			$prefix = $breakpoint['prefix'];
			$num_columns = $breakpoint['num-columns'];
			?>

			<li class="PGSF_lib__options_list_item">
				<input class="PGSF_lib__has_suboptions PGSF_lib__breakpoint" 
				type="checkbox"
				value="breakpoint__<?php echo esc_html( $prefix ); ?>"
				name="breakpoint__<?php echo esc_html( $prefix ); ?>"
				data-columns="<?php echo esc_html( $num_columns ); ?>"
				data-prefix="<?php echo esc_html( $prefix ); ?>"
				><?php echo esc_html( $prefix ); ?>
			</li>

		<?php } ?>

	</ul>

	<?php
}

function PGSF_lib__retrieve_breakpoints_selections( $class_selection_breakpoints ) {

	?>

	<ul class="PGSF_lib__options_list PGSF_lib__breakpoints">

		<?php foreach ( $class_selection_breakpoints as $class_selection_breakpoint ) {
			/** var_dump( $class_selection_breakpoint ); */
			$name = $class_selection_breakpoint->name;
			$prefix = $class_selection_breakpoint->prefix;
			$num_columns = $class_selection_breakpoint->columns;
			$isChecked = false;
			if ( $class_selection_breakpoint->$name == 'checked' ) {
				$isChecked = true;
			}
			?>

			<li class="PGSF_lib__options_list_item">
				<input class="PGSF_lib__has_suboptions PGSF_lib__breakpoint" 
				type="checkbox"
				value="breakpoint__<?php echo esc_html( $prefix ); ?>"
				name="breakpoint__<?php echo esc_html( $prefix ); ?>"
				data-columns="<?php echo esc_html( $num_columns ); ?>"
				data-prefix="<?php echo esc_html( $prefix ); ?>"
				<?php if ( $isChecked ) { echo esc_html( ' checked' ); } ?> ><?php echo esc_html( $prefix ); ?>
			</li>

		<?php } ?>

	</ul>

	<?php
}

function PGSF_lib__get_class_selections( $breakpoints ) {
	/**
	?>

	<ul class="PGSF_lib__options_list PGSF_lib__breakpoints">

		<?php foreach ($breakpoints as $breakpoint) {
			$prefix = $breakpoint['prefix'];
			$num_columns = $breakpoint['num-columns'];
			?>
			<li class="PGSF_lib__options_list_item">
				<input class="PGSF_lib__has_suboptions PGSF_lib__breakpoint" 
				type="checkbox"
				value="breakpoint__<?php echo $prefix; ?>"
				name="breakpoint__<?php echo $prefix; ?>"
				data-columns="<?php echo $num_columns; ?>"
				data-prefix="<?php echo $prefix; ?>"><?php echo $prefix; ?>
			</li>
		<?php } ?>

	</ul>

	<?php */
}


add_action( 'wp_ajax_PGSF_lib__get_breakpoint_features', 'PGSF_lib__get_breakpoint_features' );
function PGSF_lib__get_breakpoint_features() {

	$numCols = $_POST['columns'];
	?>

	<ul class="PGSF_lib__options_list PGSF_lib__breakpoint_features">
		<li class="PGSF_lib__options_list_item"><input class="PGSF_lib__has_suboptions PGSF_lib__breakpoint_feature__col_width" 			data-columns="<?php echo $numCols; ?>" onclick="PGSF_lib__get_col_widths(event)" 			type="checkbox" name="col_width" 			value="col_width">Element Widths</li>
		<li class="PGSF_lib__options_list_item"><input class="PGSF_lib__has_suboptions PGSF_lib__breakpoint_feature__offset_right" 			data-columns="<?php echo $numCols; ?>" onclick="PGSF_lib__get_col_widths(event)" 			type="checkbox" name="offset_right" 		value="offset_right">Offset Right</li>
		<li class="PGSF_lib__options_list_item"><input class="PGSF_lib__has_suboptions PGSF_lib__breakpoint_feature__offset_left" 			data-columns="<?php echo $numCols; ?>" onclick="PGSF_lib__get_col_widths(event)" 			type="checkbox" name="offset_left" 			value="offset_left">Offset Left</li>
		<li class="PGSF_lib__options_list_item"><input class="PGSF_lib__has_suboptions PGSF_lib__breakpoint_feature__nested_grid_elements" 	data-columns="<?php echo $numCols; ?>" onclick="PGSF_lib__get_nesting_options(event)" 	type="checkbox" name="nested_grid_elements" value="nested_grid_elements">Nested Grid Elements</li>
	</ul>

	<?php

	wp_die();
}

/*
	med
		Element Widths
		Offset Right
		Offset Left
		Nested Grid Elements
			Parent Column Width: 1
			Parent Column Width: 2
				CHILD ELEMENTS WIDTHS
				CHILD OFFSET RIGHT
				CHILD OFFSET LEFT
				CHILD REVERSE OFFSET RIGHT
				CHILD REVERSE OFFSET LEFT
			Parent Column Width: 3
			Parent Column Width: 4
			Parent Column Width: 5
			Parent Column Width: 6





*/
add_action( 'wp_ajax_PGSF_lib__get_nested_breakpoint_features', 'PGSF_lib__get_nested_breakpoint_features' );
function PGSF_lib__get_nested_breakpoint_features() {

	$numCols = $_POST['columns'];
	$typeOfCols = $_POST['typeOfCols'];
	?>

	<ul class="PGSF_lib__options_list PGSF_lib__breakpoint_features">
		<li class="PGSF_lib__options_list_item"><input class="PGSF_lib__has_suboptions PGSF_lib__breakpoint_feature__col_width" 			data-columns="<?php echo $numCols; ?>" onclick="PGSF_lib__get_col_widths(event)" 			type="checkbox" name="col_width" 			value="col_width"><?php echo $typeOfCols . ' ';?>Element Widths</li>
		<li class="PGSF_lib__options_list_item"><input class="PGSF_lib__has_suboptions PGSF_lib__breakpoint_feature__offset_right" 			data-columns="<?php echo $numCols; ?>" onclick="PGSF_lib__get_col_widths(event)" 			type="checkbox" name="offset_right" 		value="offset_right"><?php echo $typeOfCols . ' ';?>Offset Right</li>
		<li class="PGSF_lib__options_list_item"><input class="PGSF_lib__has_suboptions PGSF_lib__breakpoint_feature__offset_left" 			data-columns="<?php echo $numCols; ?>" onclick="PGSF_lib__get_col_widths(event)" 			type="checkbox" name="offset_left" 			value="offset_left"><?php echo $typeOfCols . ' ';?>Offset Left</li>
		<li class="PGSF_lib__options_list_item"><input class="PGSF_lib__has_suboptions PGSF_lib__breakpoint_feature__reverse_offset_right" 	data-columns="<?php echo $numCols; ?>" onclick="PGSF_lib__get_col_widths(event)" 			type="checkbox" name="reverse_offset_right" value="reverse_offset_right"><?php echo $typeOfCols . ' ';?> Reverse Offset Right</li>
		<li class="PGSF_lib__options_list_item"><input class="PGSF_lib__has_suboptions PGSF_lib__breakpoint_feature__reverse_offset_left" 	data-columns="<?php echo $numCols; ?>" onclick="PGSF_lib__get_col_widths(event)" 			type="checkbox" name="reverse_offset_left" 	value="reverse_offset_left"><?php echo $typeOfCols . ' ';?> Reverse Offset Left</li>
	</ul>

	<?php

	wp_die();
}

add_action( 'wp_ajax_PGSF_lib__get_breakpoint_feature__col_width', 'PGSF_lib__get_breakpoint_feature__col_width' );
function PGSF_lib__get_breakpoint_feature__col_width() {

	$numCols = $_POST['columns'];
	$typeOfCols = $_POST['typeOfCols'];

	$extraClasses = '';
	$onclickFunction = 'PGSF_lib__get_col_width_variations(event)';
	if($typeOfCols == 'Parent') {
		$extraClasses .= ' breakpoint_parent';
		$onclickFunction = 'PGSF_lib__get_nesting_features(event)';
	}
	else if($typeOfCols == 'Child') {
		$extraClasses .= ' breakpoint_child';
	}
	?>

	<ul class="PGSF_lib__options_list PGSF_lib__breakpoint_feature__col_widths">

		<?php for($i = 1; $i <= $numCols; $i++) { ?>
			<li class="PGSF_lib__options_list_item"><input class="PGSF_lib__has_suboptions PGSF_lib__breakpoint_feature__col_width__<?php echo $i; ?> <?php echo $extraClasses?>"
				onclick="<?php echo $onclickFunction; ?>"
				type="checkbox"
				name="col_width__<?php echo $i; ?>"
				value="col_width__<?php echo $i; ?>"
				data-columns="<?php echo $numCols; ?>"><?php echo $typeOfCols . ' ';?>Column Width: <?php echo $i; ?></li>
		<?php } ?>

	</ul>

	<?php

	wp_die();
}


add_action( 'wp_ajax_PGSF_lib__get_breakpoint_feature__col_width_variation', 'PGSF_lib__get_breakpoint_feature__col_width_variation' );
function PGSF_lib__get_breakpoint_feature__col_width_variation() {

	?>

	<ul class="PGSF_lib__options_list PGSF_lib__col_width_variations">
		<li class="PGSF_lib__options_list_item"><input class="PGSF_lib__col_width_variation__std" 				type="checkbox" name="col_width_variation__std" 				value="col_width_variation__std">Standard Column</li>
		<li class="PGSF_lib__options_list_item"><input class="PGSF_lib__col_width_variation__p5" 				type="checkbox" name="col_width_variation__p5" 					value="col_width_variation__p5">Plus Half Column</li>
		<li class="PGSF_lib__options_list_item"><input class="PGSF_lib__col_width_variation__gutter_1" 			type="checkbox" name="col_width_variation__gutter_1" 			value="col_width_variation__gutter_1">Plus 1 Gutter</li>
		<li class="PGSF_lib__options_list_item"><input class="PGSF_lib__col_width_variation__gutter_2" 			type="checkbox" name="col_width_variation__gutter_2" 			value="col_width_variation__gutter_2">Plus 2 Gutters</li>
		<li class="PGSF_lib__options_list_item"><input class="PGSF_lib__col_width_variation__gutter_1_inner_1" 	type="checkbox" name="col_width_variation__gutter_1_inner_1" 	value="col_width_variation__gutter_1_inner_1">Plus 1 Gutter and 1 Inner</li>
		<li class="PGSF_lib__options_list_item"><input class="PGSF_lib__col_width_variation__gutter_0_inner_1" 	type="checkbox" name="col_width_variation__gutter_0_inner_1" 	value="col_width_variation__gutter_0_inner_1">Plus 0 Gutters and 1 Inner</li>
	</ul>

	<?php

	wp_die();
}































