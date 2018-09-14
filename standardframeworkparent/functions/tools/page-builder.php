<?php

/**
 * Displays all the sections as defined within sf_tools page builder
 */
function the_sf_tools_page() {
	// Verify that the page_builder feature tool is selected
	if ( false === can_build_sf_tools_page() ) {
		return;
	}

	// Keep track of the row number for developers reference
	$row_num = 0;
	while ( have_rows('custom_page_editor') ) { the_row();
		$row_num++;

		$current_row_layout = get_row_layout();
		$custom_section_name = '';

		// Allow for custom sections to be inserted into the page
		if ( 'insert_custom_section' === $current_row_layout ) {
			$custom_section_name = sanitize_title( get_sub_field( 'section_name' ) );
		}

		// $current_row_layout is modifiable, and $row_num is just for reference
		$current_row_layout = apply_filters( 'sf_tools_page_builder_row_layout', $current_row_layout, $row_num, $custom_section_name );

		// User should return '' from previous filter (if the current row should be skipped)
		if ( '' === $current_row_layout ) {
			continue;
		}

		// If the $current_row_layout is still 'insert_custom_section', call the action
		if ( 'insert_custom_section' === $current_row_layout ) {
			do_action( 'sf_tools_page_builder_custom_section', $custom_section_name );
			continue;
		}

		// Display the body_copy grid feature
		if ( 'body_copy' === $current_row_layout ) {

			// Output the HTML and continue
			$args = array(
				'is_sub_field' => true
			);
			the_body_copy_section( $args );
			continue;
		} 



	}
	reset_rows();
}

/**
 * Checks whether the page has the page_builder tool taxonomy selected
 */
function can_build_sf_tools_page() {
	$using_page_builder = false;

	// Search through the provided tools to add the needed grid_features
	$sf_tools = wp_get_post_terms( get_the_ID(), 'sf_tools' );
	foreach ($sf_tools as $sf_tool) {
		if ( 'page-builder' === $sf_tool->slug ) {
			$using_page_builder = true;
		}
	}

	return $using_page_builder;
}


/**************/
/** EXAMPLES **/
/**************/

// Sample functions for modifying the display of the built it sf_tools page_builder sections
/*add_filter( 'sf_tools_page_builder_row_layout', 'custom_page_builder_filter', 10, 3 );
function custom_page_builder_filter( $current_row_layout, $row_num, $custom_section_name ) {

	// Choose to skip over a row in certain conditions
	if ( 3 === $row_num && 'awesome' === $custom_section_name && 'insert_custom_section' === $current_row_layout ) {
		return '';
	}

	// Return normal if we didn't find anything worth changing
	return $current_row_layout;
}*/

/**************/
/** EXAMPLES **/
/**************/

// Sample functions for inserting your own custom sections into the sf_tools page_builder
/*add_action( 'sf_tools_page_builder_custom_section', 'custom_section_splitter', 10, 1 );
function custom_section_splitter( $custom_section_name ) {

	if ( 'dope' === $custom_section_name ) {
		custom_section__dope();
		return;
	}

	if ( 'awesome' === $custom_section_name ) {
		custom_section__awesome();
		return;
	}
}

function custom_section__dope() {
	?>
	<section>
		<div class="container">
			<h2>This section is Dope!</h2>
		</div>
	</section>
	<?php	
}

function custom_section__awesome() {
	?>
	<section>
		<div class="container">
			<h2>This section is Awesome!</h2>
		</div>
	</section>
	<?php	
}*/
