<?php

/**
 * Enqueue JS and CSS for grid
 *
 * These are the parent theme specific js files
 *
 * @return None
 */
add_action( 'wp_enqueue_scripts', 'pgsf_enqueue_grid_scripts' );
function pgsf_enqueue_grid_scripts() {

	if ( true === pgsf_can_include_grid_overlay() ) {
		wp_enqueue_script( 'PGSF_grid-overlay-js', get_template_directory_uri() . '/js/grid/grid-overlay.js', array( 'jquery' ), '1.0.0', true );
	}
}

/**
 * Add the Grid overlay accessed by pressing 'g' to localhosts and '-alpha' sites.
 * Includes the needed js file, and outputs the html into the footer.
 *
 * @return None
 */
function pgsf_add_grid_view() {
	if ( true === pgsf_can_include_grid_overlay() ) {
		if ( true === pgsf_can_include_grid_type( 'default' ) ) {
			get_template_part( 'grid-exports/default-grid-overlay' );
		}
		if ( true === pgsf_can_include_grid_type( 'custom' ) ) {
			get_template_part( 'grid-exports/grid-overlay' );
		}
	}
}
add_action( 'wp_footer', 'pgsf_add_grid_view' );

/**
 * Check whether on this domain we can include the grid overlay developer tool
 *
 * @return {boolean} Whether the grid overlay can be included
 */
function pgsf_can_include_grid_overlay() {
	// If 'None' for grid is chosen we default to not include any grid overlay
	if ( 'none' === get_field( 'grid_selection', 'byu_options' ) ) {
		return false;
	}

	// Retrieve acf selection of 'all' or 'choose' for domains
	$where_to_use_grid_overlay = get_field( 'where_to_use_grid_overlay', 'byu_options' );

	// Determine if we can include the grid overlay
	if ( 'all' === $where_to_use_grid_overlay || null === $where_to_use_grid_overlay ) {
		// Default to include the grid overlay on every domain
		return true;

	} elseif ( 'choose' === $where_to_use_grid_overlay ) {
		// Check to see if one of the domains is contained inside the current root site url
		$url = get_site_url();
		while ( have_rows( 'grid_overlay_domains', 'byu_options' ) ) { the_row();
			if ( false !== strpos( $url, get_sub_field( 'domain' ) ) ) {
				reset_rows(); // Allow the next filter, action, or function to use these rows from the beginning
				return true;
			}
		}
	}

	// If no true conditions met, default to false
	return false;
}

/**
 * Checks whether a specified grid type can be included
 *
 * @return {boolean}
 */
function pgsf_can_include_grid_type( $type ) {
	// Only possible to be true if of three types
	if ( 'none' !== $type && 'custom' !== $type && 'default' !== $type ) {
		return false;
	}

	// Retrieve the dashboard selected grid type
	$grid_selection = get_field( 'grid_selection', 'byu_options' );

	// If the selection is the same as the type in question it can be included
	if ( $grid_selection === $type ) {
		return true;
	}

	// We can include the default template if no grid_selection has been made yet
	if ( 'default' === $type && null === $grid_selection ) {
		return true;
	}

	// If no true conditions are found
	return false;
}
