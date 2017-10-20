<?php
/*
*	For multiple areas (small, med, lg, etc), just have the same .top_list class and then call this in a loop and save the results to an array
*	Set the key of each top to small, med, lg, etc and then set the object to the returned json
*	set all that to json and then save it.
*
*
	{
		small:  {
					prefix:
					min_bp_width:
					max_bp_width:
					col_num:
					gutt_width:
					cwu: [pixels, percent]
					cont_width:
					cwmu: [pixels, percent]
					cont_width_mod:
					cont_max_width:
					nested: {
								json
							}
				},
		med: 	{
					json
				},
		lg: 	{
					json
				}
	}

*/

/**
 * Add the grid breakpoint option page
 */
add_action( 'admin_menu', 'generate_grid_menu' );
function generate_grid_menu() {
	$page_title = 'Generate Grid Settings';
	$menu_title = 'Generate Grid';
	$capability = 'manage_options';
	$menu_slug = 'generate-grid';
	$function = 'generate_grid_breakpoints_option_page';
	add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function );
}

/**
 * Output the html for the grid breakpoint option page
 */
function generate_grid_breakpoints_option_page() {
	$grid_features = get_grid_features();
	if ( false === $grid_features ) {
		echo '<h2 style="color:red;">Error accessing filesystem</h2>';
	}

	?>
	<style>
	.feature_bp ul {
		position: relative;
	}
	ul.top_list ul {
		margin-left: 20px;
		margin-top: 10px;
	}
	.tab_feature_options {
		display: none;
	}
	.tab_feature_options.default_tab {
		display: block;
	}
	.tab_feature_options.selected {
		display: block;
	}

	.checked_list__display_toggle {
		position: absolute;
		width: 10px;
		height: 10px;
	    left: -33px;
	    top: -24px;
	    cursor: pointer;
	    text-align: center;
	    line-height: 8px;
	    font-size: 18px;
	    box-sizing: border-box;
	}
	.checked_list__display_toggle:hover {
		color: red;
	}
	.display_toggle__closed > li {
		display: none;
	}
	.top_list > li {
		display: block;
	}
	.top_list > .checked_list__display_toggle {
		display: none;
	}
	</style>


	<h2>Standard Framework Custom Grid Builder</h2>

	<?php
	// Create the tabs for the different options pages
	?>
	<div id="grid_tab_links">
		<?php display_features_available_from_parent(); ?>
		<button class="tab_link" data-select="main_grid_options">Main Grid Options</button>
		<?php
		if ( $grid_features ) {
			foreach ( $grid_features as $key => $value ) { ?>
				<button class="tab_link" data-select="feature_options__<?php echo $key; ?>"><?php echo $value; ?></button>
			<?php
			}
		} ?>
	</div>

	<hr>
	<hr>

	<?php
	// Create the breakpoints option page
	?>
	<div id="grid-options-pages">
		<div id="main_grid_options" class="tab_feature_options default_tab">

			<div>
				New Feature Name: <input id="new_feature_name" type="text" value="New Feature Name" name="new_feature_name"></br>
				New Feature Slug: <input id="new_feature_slug" type="text" value="new_feature_name" name="new_feature_slug"><?php update_to_feature_style_sheets_available_button(); ?></br>
				<button class="new_grid_feature_button">Create New Feature</button>
			</div>
			<hr>
			<hr>
			<div class="controls">
				<hr>
				<input class="save_lib" type="button"  value="Save Settings">
				<input class="clear_settings" type="button" value="Clear Breakpoint Settings"></br>
			</div>
			<hr>
			<hr>
			<div class="break_points">
				<?php the_breakpoint_settings_form(); ?>
			</div>

			<input class="add_new_breakpoint" type="button" value="Add New Breakpoint"></br>

		</div>


		<?php
		// Create the grid features pages
		if ( $grid_features ) {
			foreach ( $grid_features as $feature => $feature_name ) {
				the_grid_feature_full_html_page( $feature, $feature_name );
			} 
		} ?>
	</div>

	<?php
}

function display_features_available_from_parent() {
	if ( get_template() !== get_stylesheet() ) { ?>

		<div>
			<span>Features currently in parent theme</span><br>
			<ul>
				<?php 

				// The following code was taken from wordpress codex for doing file system access
				$access_type = get_filesystem_method();
				if ( 'direct' === $access_type ) {
					// you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL
					$creds = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, array() );

					// initialize the API, any problems and we exit
					if ( ! WP_Filesystem( $creds ) ) {
						return;
					}

					// make the filesystem access global
					global $wp_filesystem;

					$features_file_path =  get_template_directory() . '/grid-exports/default-grid-features.json';
					$features = (array) json_decode( stripcslashes( $wp_filesystem->get_contents( $features_file_path ) ) );

					foreach ( $features as $feature_slug => $feature_name ) {
						echo '<li>' . $feature_slug . ' : ' . $feature_name . '</li>';
					}

				} else {
					error_log( 'Doesn\'t have permissions' );
					/* don't have direct write access. Prompt user with our notice */
					add_action( 'admin_notices', 'file_access_no_permission' );
				}

				?>
				
			</ul>
		</div>

	<?php
	}
}

/**
 * Outputs a button if there is an update available
 */
function update_to_feature_style_sheets_available_button() {
	$need_to_update = false;

	// The following code was taken from wordpress codex for doing file system access
	$access_type = get_filesystem_method();
	if ( 'direct' === $access_type ) {
		// you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL
		$creds = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, array() );

		// initialize the API, any problems and we exit
		if ( ! WP_Filesystem( $creds ) ) {
			return;
		}

		// make the filesystem access global
		global $wp_filesystem;

		// Get the current version of the features
		if ( 'custom' === get_field( 'grid_selection', 'byu_options' ) ) {
			// Check custom feature versions
			$update_custom = array();
			if ( file_exists( get_stylesheet_directory() . '/grid-exports/grid-features-update.json' ) ) {
				$update_custom = (array) json_decode( stripcslashes( $wp_filesystem->get_contents( get_stylesheet_directory() . '/grid-exports/grid-features-update.json' ) ) );
			} else {
				$update_custom['version'] = 1;
			}
			$current_custom_version = intval( get_option( 'grid-features-update__custom' ) );
			if ( false === $current_custom_version || $current_custom_version < $update_custom['version'] ) {
				update_option( 'grid-features-update__custom', $update_custom['version'] );
				$need_to_update = true;
			}

		} elseif ( 'default' === get_field( 'grid_selection', 'byu_options' ) ) {
			// Check default feature versions
			$update_default = array();
			if ( file_exists( get_stylesheet_directory() . '/grid-exports/default-grid-features-update.json' ) ) {
				$update_default = (array) json_decode( stripcslashes( $wp_filesystem->get_contents( get_stylesheet_directory() . '/grid-exports/default-grid-features-update.json' ) ) );
			} else {
				$update_default['version'] = 1;
			}
			$current_default_version = intval( get_option( 'grid-features-update__default' ) );
			if ( false === $current_default_version || $current_default_version < $update_default['version'] ) {
				update_option( 'grid-features-update__default', $update_default['version'] );
				$need_to_update = true;
			}

			// Check parent default feature versions
			$update_default_parent = array();
			if ( file_exists( get_template_directory() . '/grid-exports/default-grid-features-update.json' ) ) {
				$update_default_parent = (array) json_decode( stripcslashes( $wp_filesystem->get_contents( get_template_directory() . '/grid-exports/default-grid-features-update.json' ) ) );
			} else {
				$update_default_parent['version'] = 1;
			}
			$current_parent_default_version = intval( get_option( 'grid-features-update__parent_default' ) );
			if ( false === $current_parent_default_version || $current_parent_default_version < $update_default_parent['version'] ) {
				update_option( 'grid-features-update__parent_default', $update_default_parent['version'] );
				$need_to_update = true;
			}
		}

	} else {
		error_log( 'Doesn\'t have permissions' );
		/* don't have direct write access. Prompt user with our notice */
		add_action( 'admin_notices', 'file_access_no_permission' );
	}

	// Our updating is simply delete all the css files, and as they are needed, they will be created again (with the updated feature settings)
	if ( true === $need_to_update ) {
		$wp_filesystem->delete( get_stylesheet_directory() . '/grid-exports/css/', true );
	}
}


/**************************************************************************************************************************************
***************************************************************************************************************************************
*************************************** Functions for saving/retrieving grid data ************************************************
***************************************************************************************************************************************
***************************************************************************************************************************************/

/**
 * Compares an array of jsons (in the form that represents a grid_feature's settings) and returns,
 * the combination of them, using the first object's breakpoint settings.
 *
 * @param $json_one {Array[String]} jsons of the form grid_feature_object
 *
 * @return {String} The json combination of the specified grid-feature objects
 */
function consolidate_json_array_to_single_json( $json_files = array() ) {
	// return false if no files recieved
	if ( 0 === count( $json_files ) ) {
		return false;
	} 

	// Return the json recieved, if only 1
	if ( 1 === count( $json_files ) ) {
		return $json_files[0];
	}
	
	// Proceed to combine all json files
	$first_json = $json_files[0];
	for ( $i = 1; $i < count( $json_files ); $i++ ) {
		$first_json = compare_jsons( $first_json, $json_files[ $i ] );
	}

	// Return combined json
	return $first_json;
}

/**
 * Compares two jsons (in the form that represents a grid_feature's settings) and returns,
 * the combination of the two, using the first object's breakpoint settings.
 *
 * @param $json_one {String} json of grid_feature object 1
 * @param $json_two {String} json of grid_feature object 2
 *
 * @return {String} The json combination of the specified grid-feature objects
 */
function compare_jsons( $json_one, $json_two ) {
	// Placeholder
	$object_final = new stdClass();

	// Retrieve php arrays from the jsons
	$object_one = (array) json_decode( stripcslashes( $json_one ) );
	$object_two = (array) json_decode( stripcslashes( $json_two ) );

	// Combine objects into one
	$object_final = recursive_compare( $object_one, $object_two );

	// Return json representation
	return json_encode( $object_final );
}

/**
 * Compares two php objects (in the form that represents a grid_feature's settings) and returns,
 * the combination of the two, using the first object's breakpoint settings.
 *
 * @param $object_one {Object}
 * @param $object_two {Object}
 * @param $in_nested {Boolean} whether the recursion is currently within the 'nested' checklist
 *
 * @return {Object} The combination of the specified grid-feature objects
 */
function recursive_compare( $object_one, $object_two, $in_nested = false ) {
	// Setup obj for this level of recursion
	$object_final = new stdClass();

	// Ensure the objects are arrays for consistent value access method
	$object_one = (array) $object_one;
	$object_two = (array) $object_two;

	// Iterate over object_one
	foreach ( $object_one as $key => $value ) {

		// In obj1 and NOT in obj2
		if ( ! in_array( $key, array_keys( $object_two ) ) ) {
			//add it to the final object
			$object_final->$key = $value;
			$changed = true;
			continue;
		}

		// Now we know that the key exists in both obj1 and obj2

		// If the value of $key in both obj1 and obj2 is an object, we need further comparison
		if ( is_object($value) && is_object($object_two[$key]) ) {
			if ( 'nested' === $key || true === $in_nested ) {
				$object_final->$key = recursive_compare( $value, $object_two[$key], true );
			} else {
				$object_final->$key = recursive_compare( $value, $object_two[$key] );
			}
			continue;
		}
		
		/* If we are not in 'nested', and at least one of the values is not an object, we must logically
		be dealing with the breakpoint settings. We always use the breakpoint settings of the first object. */
		if ( false === $in_nested ) {
			$object_final->$key = $value;

		} else {
			// Logic based on being inside the 'nested' checklist object

			// Only the value in obj1 is an object, we take the value of obj1
			if ( is_object($value) ) {
				$object_final->$key = $value;
				continue;
			}

			// Only the value in obj2 is an object, we take the value of obj2
			if ( is_object($object_two[$key]) ) {
				$object_final->$key = $object_two[$key];
				continue;
			}

			// If either are true, we set the value to be true (only possible for an endpoint)
			if ( 'true' === $value || 'true' === $object_two[$key]) {
				$object_final->$key = 'true';
				continue;
			}

			// If they are the same, use that value (Should account for 'false' and '' (empty string))
			if ( $value === $object_two[$key] ) {
				$object_final->$key = $value;
				continue;
			}

			error_log('This should never print out. Error in grid-options for combining two json objects "recursive_compare()"');
		}

	}

	// Iterate over object_two
	foreach ( $object_two as $key => $value ) {

		// In obj2 and NOT in obj1
		if ( ! in_array( $key, array_keys( $object_one ) ) ) {
			//add it to the final object
			$object_final->$key = $value;
			$changed = true;
			
		}
	}

	return $object_final;
}

/**
 * Retrieve the json representation of the grid breakpoint settings
 *
 * @return {JSON|bool(false)} The json representation, or false if some error occurred
 */
//add_action( 'wp_ajax_get_grid_features', 'get_grid_features' );
function get_grid_features() {

	$access_type = get_filesystem_method();
	if ( 'direct' === $access_type ) {

		// you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL
		$creds = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, array() );

		// initialize the API, any problems and we exit
		if ( ! WP_Filesystem( $creds ) ) {

			return false;
		}

		global $wp_filesystem;

		// User chosen stylesheet
		if ( 'custom' === get_field( 'grid_selection', 'byu_options' ) ) {

			// Retrieve the current features
			$json_grid_features = $wp_filesystem->get_contents( get_stylesheet_directory() . '/grid-exports/grid-features.json' );
			$grid_features = (array) json_decode( stripcslashes( $json_grid_features ) );

			return $grid_features; // No settings saved to file

		} elseif ( 'default' === get_field( 'grid_selection', 'byu_options' ) ) { // Use default standard framework grid

			// Retrieve the current features
			$json_grid_features = $wp_filesystem->get_contents( get_stylesheet_directory() . '/grid-exports/default-grid-features.json' );
			$grid_features = (array) json_decode( stripcslashes( $json_grid_features ) );

			return $grid_features; // No settings saved to file
		}
	} else {
		error_log( 'Doesn\'t have permissions' );
		/* don't have direct write access. Prompt user with our notice */
		add_action( 'admin_notices', 'write_file_no_permission' );
	}

	// If for any reason we did not return JSON, return false
	return false;
}
/**
 * Retrieve the json representation of the grid breakpoint settings
 *
 * @return {JSON|bool(false)} The json representation, or false if some error occurred
 */
//add_action( 'wp_ajax_get_grid_feature_settings', 'get_grid_feature_settings' );
function get_grid_feature_settings( $feature ) {

	$access_type = get_filesystem_method();
	if ( 'direct' === $access_type ) {

		// you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL
		$creds = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, array() );

		// initialize the API, any problems and we exit
		if ( ! WP_Filesystem( $creds ) ) {

			return false;
		}

		global $wp_filesystem;

		// User chosen stylesheet
		if ( 'custom' === get_field( 'grid_selection', 'byu_options' ) ) {

			// Retrieve the current features
			$json_grid_features = $wp_filesystem->get_contents( get_stylesheet_directory() . '/grid-exports/grid-feature__' . $feature . '.json' );
			if ( false === $json_grid_features ) {
				return false;
			}
			$grid_features = (array) json_decode( stripcslashes( $json_grid_features ) );

			return $grid_features;

		} elseif ( 'default' === get_field( 'grid_selection', 'byu_options' ) ) { // Use default standard framework grid

			// Retrieve the current features
			$json_grid_features = $wp_filesystem->get_contents( get_stylesheet_directory() . '/grid-exports/default-grid-feature__' . $feature . '.json' );
			if ( false === $json_grid_features ) {
				return false;
			}
			$grid_features = (array) json_decode( stripcslashes( $json_grid_features ) );

			return $grid_features;
		}
	} else {
		error_log( 'Doesn\'t have permissions' );
		/* don't have direct write access. Prompt user with our notice */
		add_action( 'admin_notices', 'write_file_no_permission' );
	}

	// If for any reason we did not return JSON, return false
	return false;
}

/**
 * Saves breakpoint settings to database and json file. Creates template part for the grid-overlay. Creates the new css file based on
 * newly saved breakpoint settings.
 */
add_action( 'wp_ajax_add_new_grid_feature', 'add_new_grid_feature' );
function add_new_grid_feature() {

	// Retrieve new feature information
	$new_feature_name = $_POST['new_feature_name'];
	$new_feature_slug = $_POST['new_feature_slug'];

	// If 'none' is chosen for the grid type, don't attempt to load any grid files
	if ( 'none' === get_field( 'grid_selection', 'byu_options' ) ) {
		echo 'false';
		wp_die();
	}

	// The following code was taken from wordpress codex for doing file system access
	$access_type = get_filesystem_method();
	if ( 'direct' === $access_type ) {
		/* you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL */
		$creds = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, array() );

		// initialize the API, any problems and we exit
		if ( ! WP_Filesystem( $creds ) ) {
			echo 'false';
			wp_die();
		}

		// make the filesystem access global
		global $wp_filesystem;

		// Create a directory to store generated files
		$wp_filesystem->mkdir( get_stylesheet_directory() . '/grid-exports/' );

		// Save data in files
		if ( 'default' === get_field( 'grid_selection', 'byu_options' ) ) {
			// Retrieve the current features
			$json_grid_features = $wp_filesystem->get_contents( get_stylesheet_directory() . '/grid-exports/default-grid-features.json' );
			$grid_features = (array) json_decode( stripcslashes( $json_grid_features ) );

			// Check if the feature slug already exists
			if (array_key_exists($new_feature_slug, $grid_features)) {
				echo 'false';
				wp_die();
			}

			// Add the new feature and save the file
			$grid_features[$new_feature_slug] = $new_feature_name;
			$json_grid_features = json_encode($grid_features);
			$wp_filesystem->put_contents( get_stylesheet_directory() . '/grid-exports/default-grid-features.json', $json_grid_features, FS_CHMOD_FILE );

			// Create a json file for the new feature
			$json_breakpoints = $wp_filesystem->get_contents( get_stylesheet_directory() . '/grid-exports/default-grid-breakpoints.json' );
			$new_json_feature_file_name = get_stylesheet_directory() . '/grid-exports/default-grid-feature__' . $new_feature_slug . '.json';
			$wp_filesystem->put_contents( $new_json_feature_file_name, $json_breakpoints, FS_CHMOD_FILE );

		} elseif ( 'custom' === get_field( 'grid_selection', 'byu_options' ) ) {
			// Retrieve the current features
			$json_grid_features = $wp_filesystem->get_contents( get_stylesheet_directory() . '/grid-exports/grid-features.json' );
			$grid_features = (array) json_decode( stripcslashes( $json_grid_features ) );

			// Check if the feature slug already exists
			if (array_key_exists($new_feature_slug, $grid_features)) {
				echo 'false';
				wp_die();
			}

			// Add the new feature and save the file
			$grid_features[$new_feature_slug] = $new_feature_name;
			$json_grid_features = json_encode($grid_features);
			$wp_filesystem->put_contents( get_stylesheet_directory() . '/grid-exports/grid-features.json', $json_grid_features, FS_CHMOD_FILE );

			// Create a json file for the new feature
			$json_breakpoints = $wp_filesystem->get_contents( get_stylesheet_directory() . '/grid-exports/grid-breakpoints.json' );
			$new_json_feature_file_name = get_stylesheet_directory() . '/grid-exports/grid-feature__' . $new_feature_slug . '.json';;
			$wp_filesystem->put_contents( $new_json_feature_file_name, $json_breakpoints, FS_CHMOD_FILE );
		}

	} else {
		error_log( 'Doesn\'t have permissions' );
		/* don't have direct write access. Prompt user with our notice */
		add_action( 'admin_notices', 'file_access_no_permission' );
		echo 'false';
		wp_die();
	}

	the_grid_feature_full_html_page( $new_feature_slug, $new_feature_name );
	wp_die();
}

/**
 * Saves breakpoint settings to database and json file. Creates template part for the grid-overlay. Creates the new css file based on
 * newly saved breakpoint settings.
 */
add_action( 'wp_ajax_save_grid_feature_settings', 'save_grid_feature_settings' );
function save_grid_feature_settings() {

	$feature_settings_json = $_POST['json'];
	$feature_slug = $_POST['feature_slug'];

	// If 'none' is chosen for the grid type, don't attempt to load any grid files
	if ( 'none' === get_field( 'grid_selection', 'byu_options' ) ) {
		wp_die();
	}

	// The following code was taken from wordpress codex for doing file system access
	$access_type = get_filesystem_method();
	if ( 'direct' === $access_type ) {
		/* you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL */
		$creds = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, array() );

		// initialize the API, any problems and we exit
		if ( ! WP_Filesystem( $creds ) ) {
			wp_die();
		}

		// make the filesystem access global
		global $wp_filesystem;

		// Create a directory to store generated files
		$wp_filesystem->mkdir( get_stylesheet_directory() . '/grid-exports/' );


		$update = array();
		$update_file_name = get_stylesheet_directory() . '/grid-exports/default-grid-features-update.json';
		if ( 'custom' === get_field( 'grid_selection', 'byu_options' ) ) {
			$update_file_name = get_stylesheet_directory() . '/grid-exports/grid-features-update.json';
		}
		if ( ! file_exists( $update_file_name ) ) {
			$update['version'] = 1;
		} else {
			$update = (array) json_decode( stripcslashes( $wp_filesystem->get_contents( $update_file_name ) ) );
			$update['version'] += 1;
		}
		$update_json = json_encode( $update );

		// Save data in files
		if ( 'default' === get_field( 'grid_selection', 'byu_options' ) ) {
			$wp_filesystem->put_contents( get_stylesheet_directory() . '/grid-exports/default-grid-feature__' . $feature_slug . '.json', $feature_settings_json, FS_CHMOD_FILE );
			$wp_filesystem->put_contents( $update_file_name, $update_json, FS_CHMOD_FILE );
		} elseif ( 'custom' === get_field( 'grid_selection', 'byu_options' ) ) {
			$wp_filesystem->put_contents( get_stylesheet_directory() . '/grid-exports/grid-feature__' . $feature_slug . '.json', $feature_settings_json, FS_CHMOD_FILE );
			$wp_filesystem->put_contents( $update_file_name, $update_json, FS_CHMOD_FILE );
		}

	} else {
		error_log( 'Doesn\'t have permissions' );
		/* don't have direct write access. Prompt user with our notice */
		add_action( 'admin_notices', 'file_access_no_permission' );
	}

	wp_die();
}

/**
 * Returns the html required to display the grid-overlay developer tool.
 *
 * @param {JSON} $json The json representation of the break-point settings
 * @return {String} The string representation of the html
 */
add_action( 'wp_ajax_get_grid_overlay_html', 'get_grid_overlay_html' );
function get_grid_overlay_html( $json ) {

	$breakpoints = (array) json_decode( stripcslashes( $json ) );

	$largest_col_number = 0;
	$col_classes = '';
	foreach ( $breakpoints as $prefix => $breakpoint ) {
		if ( isset( $breakpoint->col_number ) && $breakpoint->col_number > $largest_col_number ) {
			$largest_col_number = $breakpoint->col_number;
		}
		$col_classes .= $prefix . '-col-1 ';
	}
	$html = '<div class="show-grid">';
		$html .= '<div class="container">';
			$html .= '<div class="row">';

	for ( $col = 0; $col < $largest_col_number; $col++ ) {
					$html .= '<div class="' . $col_classes . '"><div class="show-grid__column"></div></div>';
	}

			$html .= '</div>';
		$html .= '</div>';
	$html .= '</div>';

	return $html;
}

/**
 * Saves breakpoint settings to database and json file. Creates template part for the grid-overlay. Creates the new css file based on
 * newly saved breakpoint settings.
 */
add_action( 'wp_ajax_clear_grid_breakpoints_settings', 'clear_grid_breakpoints_settings' );
function clear_grid_breakpoints_settings() {

	// If 'none' is chosen for the grid type, don't attempt to load any grid files
	if ( 'none' === get_field( 'grid_selection', 'byu_options' ) ) {
		wp_die();
	} elseif ( 'default' === get_field( 'grid_selection', 'byu_options' ) ) {
		update_option( 'default-grid-breakpoints', '' );
	} elseif ( 'custom' === get_field( 'grid_selection', 'byu_options' ) ) {
		update_option( 'grid-breakpoints', '' );
	}

	// The following code was taken from wordpress codex for doing file system access
	$access_type = get_filesystem_method();
	if ( 'direct' === $access_type ) {
		/* you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL */
		$creds = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, array() );

		// initialize the API, any problems and we exit
		if ( ! WP_Filesystem( $creds ) ) {
			wp_die();
		}

		// make the filesystem access global
		global $wp_filesystem;

		// Delete appropriate files
		if ( 'default' === get_field( 'grid_selection', 'byu_options' ) && get_template() !== get_stylesheet() ) {
			$wp_filesystem->delete( get_stylesheet_directory() . '/grid-exports/default-grid-breakpoints.json' );
			$wp_filesystem->delete( get_stylesheet_directory() . '/grid-exports/default-grid-overlay.php' );
		} elseif ( 'custom' === get_field( 'grid_selection', 'byu_options' ) ) {
			$wp_filesystem->delete( get_stylesheet_directory() . '/grid-exports/grid-breakpoints.json' );
			$wp_filesystem->delete( get_stylesheet_directory() . '/grid-exports/grid-overlay.php' );
		}

	} else {
		error_log( 'Doesn\'t have permissions' );
		/* don't have direct write access. Prompt user with our notice */
		add_action( 'admin_notices', 'file_access_no_permission' );
	}

	wp_die();

}

/**
 * Saves breakpoint settings to database and json file. Creates template part for the grid-overlay. Creates the new css file based on
 * newly saved breakpoint settings.
 */
add_action( 'wp_ajax_save_grid_breakpoints_settings', 'save_grid_breakpoints_settings' );
function save_grid_breakpoints_settings() {

	$breakpoint_settings_json = $_POST['json'];
	$breakpoint_settings = (array) json_decode( stripcslashes( $breakpoint_settings_json ) );
	//var_dump($breakpoint_settings_json);
	//var_dump($breakpoint_settings);


	// If 'none' is chosen for the grid type, don't attempt to load any grid files
	if ( 'none' === get_field( 'grid_selection', 'byu_options' ) ) {
		wp_die();
	} elseif ( 'default' === get_field( 'grid_selection', 'byu_options' ) ) {
		update_option( 'default-grid-breakpoints', $breakpoint_settings_json );
	} elseif ( 'custom' === get_field( 'grid_selection', 'byu_options' ) ) {
		update_option( 'grid-breakpoints', $breakpoint_settings_json );
	}

	// The following code was taken from wordpress codex for doing file system access
	$access_type = get_filesystem_method();
	if ( 'direct' === $access_type ) {
		/* you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL */
		$creds = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, array() );

		// initialize the API, any problems and we exit
		if ( ! WP_Filesystem( $creds ) ) {
			wp_die();
		}

		// make the filesystem access global
		global $wp_filesystem;

		// Create a directory to store generated files
		$wp_filesystem->mkdir( get_stylesheet_directory() . '/grid-exports/' );

		// Save data in files
		$grid_overlay_html = get_grid_overlay_html( $breakpoint_settings_json ); // Generate template for development grid overlay
		//$grid_css = get_grid_css( $breakpoint_settings_json ); // Generate Grid Stylesheets
		if ( 'default' === get_field( 'grid_selection', 'byu_options' ) ) {
			// Update the breakpoints JSON
			$wp_filesystem->put_contents( get_stylesheet_directory() . '/grid-exports/default-grid-breakpoints.json', $breakpoint_settings_json, FS_CHMOD_FILE );

			// Update the breakpoint settings of all the default features
			$json_features = (array) json_decode( stripcslashes( $wp_filesystem->get_contents( get_stylesheet_directory() . '/grid-exports/default-grid-features.json' ) ) );
			foreach ( $json_features as $feature_slug => $feature_name ) {

				// Retrieve current feature settings from file
				$feature_file_name = get_stylesheet_directory() . '/grid-exports/default-grid-feature__' . $feature_slug . '.json';
				$json_feature = (array) json_decode( stripcslashes( $wp_filesystem->get_contents( $feature_file_name ) ) );

				// Update each breakpoint in the feature
				foreach ($breakpoint_settings as $breakpoint_prefix => $breakpoint_info_object) {

					// Store the nested settings
					$feature_nested_settings = $json_feature[$breakpoint_prefix]->nested;

					// Replace the breakpoint settings and restore the nested settings
					$json_feature[$breakpoint_prefix] = $breakpoint_info_object;
					$json_feature[$breakpoint_prefix]->nested = $feature_nested_settings;

				}

				// Save changes to file
				$wp_filesystem->put_contents( $feature_file_name, json_encode( $json_feature ), FS_CHMOD_FILE );
			}

			// Update the overlay grid columns template
			$wp_filesystem->put_contents( get_stylesheet_directory() . '/grid-exports/default-grid-overlay.php', $grid_overlay_html, FS_CHMOD_FILE );

		} elseif ( 'custom' === get_field( 'grid_selection', 'byu_options' ) ) {
			// Update the breakpoints JSON
			$wp_filesystem->put_contents( get_stylesheet_directory() . '/grid-exports/grid-breakpoints.json', $breakpoint_settings_json, FS_CHMOD_FILE );

			// Update the breakpoint settings of all the custom features
			$json_features = (array) json_decode( stripcslashes( $wp_filesystem->get_contents( get_stylesheet_directory() . '/grid-exports/grid-features.json' ) ) );
			foreach ( $json_features as $feature_slug => $feature_name ) {

				// Retrieve current feature settings from file
				$feature_file_name = get_stylesheet_directory() . '/grid-exports/grid-feature__' . $feature_slug . '.json';
				$json_feature = (array) json_decode( stripcslashes( $wp_filesystem->get_contents( $feature_file_name ) ) );

				// Update each breakpoint in the feature
				foreach ($breakpoint_settings as $breakpoint_prefix => $breakpoint_info_object) {

					// Store the nested settings
					$feature_nested_settings = $json_feature[$breakpoint_prefix]->nested;

					// Replace the breakpoint settings and restore the nested settings
					$json_feature[$breakpoint_prefix] = $breakpoint_info_object;
					$json_feature[$breakpoint_prefix]->nested = $feature_nested_settings;

				}

				// Save changes to file
				$wp_filesystem->put_contents( $feature_file_name, json_encode( $json_feature ), FS_CHMOD_FILE );
			}

			// Update the overlay grid columns template
			$wp_filesystem->put_contents( get_stylesheet_directory() . '/grid-exports/grid-overlay.php', $grid_overlay_html, FS_CHMOD_FILE );
		}

	} else {
		error_log( 'Doesn\'t have permissions' );
		/* don't have direct write access. Prompt user with our notice */
		add_action( 'admin_notices', 'file_access_no_permission' );
	}

	wp_die();
}

/**
 * Needs to request credentials for server to write the file w/ proper permissions, I think
 */
function file_access_no_permission() {}

/**
 * Retrieves the html form for the breakpoint settings.
 *
 * @return {String} The string representation of the html form
 */
add_action( 'wp_ajax_get_breakpoint_settings_form', 'get_breakpoint_settings_form' );
function get_breakpoint_settings_form() {

	$json = get_breakpoint_settings();
	if ( false !== $json && '' !== $json ) {
		return get_grid_breakpoints_settings_form( $json );
	}

	return '';
}

/**
 * Outputs the html form for the breakpoint settings.
 */
add_action( 'wp_ajax_the_breakpoint_settings_form', 'the_breakpoint_settings_form' );
function the_breakpoint_settings_form() {
	echo get_breakpoint_settings_form();
}

/**
 * Retrieves the html form for the breakpoint settings.
 *
 * @return {String} The string representation of the html form
 */
add_action( 'wp_ajax_get_grid_feature_settings_form', 'get_grid_feature_settings_form' );
function get_grid_feature_settings_form( $feature ) {

	$feature_settings = get_grid_feature_settings( $feature );
	if ( false !== $feature_settings && '' !== $feature_settings ) {
		return get_grid_feature_settings_checklist( $feature_settings );
	}

	// If no informatino was found return a new checklist base
	return '';
}

/**
 * Outputs the html form for the breakpoint settings.
 */
add_action( 'wp_ajax_the_grid_feature_settings_form', 'the_grid_feature_settings_form' );
function the_grid_feature_settings_form( $feature ) {
	echo get_grid_feature_settings_form( $feature );
}

/**
 * Retrieve the json representation of the grid breakpoint settings
 *
 * @return {JSON|bool(false)} The json representation, or false if some error occurred
 */
add_action( 'wp_ajax_get_breakpoint_settings', 'get_breakpoint_settings' );
function get_breakpoint_settings() {

	// If 'none' is chosen for the grid type, don't attempt to load any grid files
	if ( 'none' === get_field( 'grid_selection', 'byu_options' ) ) {
		return false;
	}

	$access_type = get_filesystem_method();
	if ( 'direct' === $access_type ) {

		// you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL
		$creds = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, array() );

		// initialize the API, any problems and we exit
		if ( ! WP_Filesystem( $creds ) ) {

			return false;
		}

		global $wp_filesystem;

		// User chosen stylesheet
		if ( 'custom' === get_field( 'grid_selection', 'byu_options' ) ) {

			// Retrieve the settings saved to file
			$json = $wp_filesystem->get_contents( get_stylesheet_directory() . '/grid-exports/grid-breakpoints.json' );
			if ( $json && ! empty( (array) json_decode( stripcslashes( $json ) ) ) ) {
				return $json; // Return the json saved to file
			}

			// Since no settings saved to file, retrieve the settings saved to database
			$json = get_option( 'grid-breakpoints' );
			if ( $json && ! empty( (array) json_decode( stripcslashes( $json ) ) ) ) {
				return $json; // Return the json saved to database
			}

			return false; // No settings in database or file

		} elseif ( 'default' === get_field( 'grid_selection', 'byu_options' ) ) { // Use default standard framework grid

			// Retrieve the settings in default parent file specific to the current child theme
			$json = $wp_filesystem->get_contents( get_stylesheet_directory() . '/grid-exports/default-grid-breakpoints.json' );
			if ( $json && ! empty( (array) json_decode( stripcslashes( $json ) ) ) ) {
				return $json; // Return the json saved to file
			}

			// Since no settings saved to file, retrieve the settings saved to database
			$json = get_option( 'default-grid-breakpoints' );
			if ( $json && ! empty( (array) json_decode( stripcslashes( $json ) ) ) ) {
				return $json; // Return the json saved to database
			}

			// Retrieve the settings in default parent file
			$json = $wp_filesystem->get_contents( get_template_directory() . '/grid-exports/default-grid-breakpoints.json' );
			if ( $json && ! empty( (array) json_decode( stripcslashes( $json ) ) ) ) {
				return $json; // Return the json saved to database
			}

			return false; // No settings in database or files
		}
	} else {
		error_log( 'Doesn\'t have permissions' );
		/* don't have direct write access. Prompt user with our notice */
		add_action( 'admin_notices', 'write_file_no_permission' );
	}

	// If for any reason we did not return JSON, return false
	return false;
}

/**
 * Get the grid breakpoints settings form with previously saved settings shown and editable.
 *
 * @param {JSON} $json The json representation of the current breakpoint settings
 * @return {String} The string representation of the html form
 */
function get_grid_breakpoints_settings_form( $json ) {

	$arr = json_decode( stripcslashes( $json ) );

	$html = '';

	// Loop through each breakpoint
	foreach ( $arr as $name => $data ) {

		$html .= '<div class="bp">';
		$html .= 'Breakpoint Prefix (all lowercase, letters only): <input class="prefix" value="' . $name . '"> </br>';
		$nested = '';

		//now iterate through the elements in that sub array
		foreach ( $data as $input => $value ) {
			if ( 'nested' !== $input ) {
				$html .= get_grid_option( $name, $input, $value );
			} else {
				//$nested = get_nested_grid_checklist_options( $value );
			}
		}

		//$html .= '<h3>Checklist</h3>';
		//$html .= $nested;
		$html .= '<input class="remove_breakpoint" type="button" value="Remove Breakpoint"> </br>';
		$html .= '<hr>';

		$html .= '</div>';
	}

	return $html;
}

/**
 * Get the grid breakpoints settings form with previously saved settings shown and editable.
 *
 * @param {JSON} $breakpoint_settings_json The json representation of the current breakpoint settings
 * @return {String} The string representation of the html form
 */
function get_grid_feature_settings_checklist( $feature_settings ) {

	$html = '';

	// Loop through each breakpoint
	foreach ( $feature_settings as $name => $data ) {

		// now iterate through the elements in that sub array
		$nested = '';
		$number_columns = '';
		foreach ( $data as $input => $value ) {
			if ( 'nested' === $input ) {
				$nested = get_nested_grid_checklist_options( $value );
			} elseif ( 'col_number' === $input ) {
				$number_columns = $value;
			}
		}

		$html .= '<div class="feature_bp" data-breakpoint-slug="' . $name . '" data-breakpoint-cols="' . $number_columns . '">';
		$html .= '<h2>Breakpoint: ' . $name . '</h2>';


		$html .= $nested . '</ul>';
		$html .= '<hr>';

		$html .= '</div>';
	}

	return $html;
}

/**
 * Recursive function to generate the proper checklist structure from the array
 *
 * @param {array} $arr Nested options
 * @return {String} The string representation of the html nested checklists
 */
function get_nested_grid_checklist_options( $arr ) {
	$checklist = (array) $arr;

	$html = '';

	foreach ( $checklist as $key => $val ) {

		if ( '' === $checklist[ $key ] ) {
			// It's the start of an ul
			$html .= "<ul class='" . $key . " display_toggle__closed' name='" . $key . "'><div class='checked_list__display_toggle'>-</div>";

		} elseif ( 'true' === $checklist[ $key ] ) {
			// It's a checked input box
			$html .= "<li><input class='" . $key . "' name='" . $key . "' type='checkbox' checked>" . get_nested_grid_checklist_label( $key ) . "</input></li>";

		} elseif ( 'false' === $checklist[ $key ] ) {
			// It's an unchecked input box
			$html .= "<li><input class='" . $key . "' name='" . $key . "' type='checkbox'>" . get_nested_grid_checklist_label( $key ) . "</input></li>";

		} elseif ( 'object' === gettype( $checklist[ $key ] ) ) {
			//This means that it is has a child list that needs to be recursed on
			$html .= "<li><input class='" . $key . "' name='" . $key . "' type='checkbox' checked>" . get_nested_grid_checklist_label( $key ) . "</input>";
			$html .= get_nested_grid_checklist_options( $checklist[ $key ] );
			$html .= "</ul></li>";
		}
	}

	return $html;
}


/**
 * Label look up for more readable names on Checklist
 *
 * @return {String} The label
 */
function get_nested_grid_checklist_label( $key ) {
	if ( 'top-element_widths' === $key ) {
		return 'Element Widths';

	} elseif ( 'top-offset_right' === $key ) {
		return 'Offset Right';

	} elseif ( 'top-offset_left' === $key ) {
		return 'Offset Left';

	} elseif ( 'top-nested_grid' === $key ) {
		return 'Nested Grid Elements';

	} elseif ( 'standardCol' === $key ) {
		return 'Standard Column';

	} elseif ( 'plusHalfCol' === $key ) {
		return 'Plus Half Column';

	} elseif ( 'plus1Gutter' === $key ) {
		return 'Plus 1 Gutter';

	} elseif ( 'plus2Gutter' === $key ) {
		return 'Plus 2 Gutters';

	} elseif ( 'plus1Gutter1Inner' === $key ) {
		return 'Plus 1 Gutter and 1 Inner';

	} elseif ( 'plus0Gutter1Inner' === $key ) {
		return 'Plus 0 Gutters and 1 inner';

	} elseif ( 'childEleWidth' === $key ) {
		return 'CHILD ELEMENT WIDTHS';

	} elseif ( 'childOffRight' === $key ) {
		return 'CHILD OFFSET RIGHT';

	} elseif ( 'childOffLeft' === $key ) {
		return 'CHILD OFFSET LEFT';

	} elseif ( 'childRevOffRight' === $key ) {
		return 'CHILD REVERSE OFFSET RIGHT';

	} elseif ( 'childRevOffLeft' === $key ) {
		return 'CHILD REVERSE OFFSET LEFT';

	} elseif ( false !== strpos( $key, 'parentColumn' ) ) {
		$ColNumber = str_replace( 'parentColumn', '', $key );
		return 'Parent Column Width: ' . $ColNumber;
	} elseif ( false !== strpos( $key, 'childColumn' ) ) {
		$ColNumber = str_replace( 'childColumn', '', $key );
		return 'Child Column Width: ' . $ColNumber;
	}
	return 'NONE: ' . $key;
}

/**
 * Retrieves the html of the given grid breakpoint option
 *
 * @param {String} $prefix The prefix (name) of the breakpoint
 * @param {String} $input The option class
 * @param {String} $value The current value of the option
 * @return {String} The string representation of the html for the specified option
 */
function get_grid_option( $prefix, $input, $value ) {

	//make switch statement that takes in class and returns the proper label text
	switch ( $input ) {
		case 'min_width':
			return 'Min Breakpoint width (px): <input class="' . $input . '" value="' . $value . '"> </br>';
			break;
		case 'max_width';
			return 'Max Breakpoint width (px): <input class="' . $input . '" value="' . $value . '"> </br>';
			break;
		case 'col_number':
			return 'Number of Columns <input class="' . $input . '" value="' . $value . '"> </br>';
			break;
		case 'gutter_width';
			return 'Gutter Width (px): <input class="' . $input . '" value="' . $value . '"> </br>';
			break;
		case 'container_width_units_pixels':
			$is_checked = '';
			if ( $value ) { $is_checked = 'checked'; }
			return 'Container Width Units: </br> Pixels <input class="' . $input . '" name=' . $prefix . '"-cwu" type="radio" ' . $is_checked . '> </br>';
			break;
		case 'container_width_units_percent';
			error_log( $value );
			$is_checked = '';
			if ( $value ) { $is_checked = 'checked'; }
			return 'Percent <input class="' . $input . '" name=' . $prefix . '"-cwu" type="radio" ' . $is_checked . '> </br>';
			break;
		case 'container_width':
			return 'Container Width: <input class="' . $input . '" value="' . $value . '"> </br>';
			break;
		case 'container_width_modification_units_pixels';
			$is_checked = '';
			if ( $value ) { $is_checked = 'checked'; }
			return 'Container Width Modification Units: </br> Pixels <input class="' . $input . '" name=' . $prefix . '"-cwmu" type="radio" ' . $is_checked . '> </br>';
			break;
		case 'container_width_modification_units_percent':
			$is_checked = '';
			if ( $value ) { $is_checked = 'checked'; }
			return 'Percent <input class="' . $input . '" name=' . $prefix . '"-cwmu" type="radio" ' . $is_checked . '> </br>';
			break;
		case 'container_width_modification';
			return 'Container Width Modification: <input class="' . $input . '" value="' . $value . '"> </br>';
			break;
		case 'container_max_width':
			return 'Container Max Width (px): <input class="' . $input . '" value="' . $value . '"> </br>';
			break;
	}
}

/**
 * Retrieve HTML for a new checklist for a breakpoint class selection for a feature
 *
 * @return {String} The HTML of the checklist
 */
function get_base_checklist_html() {
	$html = '<ul class="top_list" name="top_list">';
		$html .= '<li><input class="top-element_widths" name="top-element_widths" type="checkbox">Element Widths</input></li>';
		$html .= '<li><input class="top-offset_right" name="top-offset_right" type="checkbox">Offset Right</input></li>';
		$html .= '<li><input class="top-offset_left" name="top-offset_left" type="checkbox">Offset Left</input></li>';
		$html .= '<li><input class="top-nested_grid" name="top-nested_grid" type="checkbox">Nested Grid Elements</input></li>';
	$html .= '</ul> ';

	return $html;
}

function get_grid_feature_full_html_page( $feature_slug, $feature_name ) {
	$html = '';

	// Create the grid features page
	$html .= '<div id="feature_options__' . $feature_slug . '" class="tab_feature_options">';
		$html .= '<button data-feature-slug="' . $feature_slug . '" class="save_feature_settings_button">Save Feature Settings</button>';
		$html .= '<h2>' . $feature_name . '</h2>';
		$html .= get_grid_feature_settings_form( $feature_slug );
	$html .= '</div>';

	return $html;
}

function the_grid_feature_full_html_page( $feature_slug, $feature_name ) {
	echo get_grid_feature_full_html_page( $feature_slug, $feature_name );
}
