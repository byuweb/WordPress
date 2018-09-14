<?php
/* Table of Contents
––––––––––––––––––––––––––––––––––––––––––––––––––
- pgsf_enqueue_parent_scripts_and_styles()
- pgsf_enqueue_parent_admin_scripts()
- pgsf_enqueue_byu_web_components()
- pgsf_use_icon()
- Including php libraries
- Add sf_tools taxonomy
- Add Site Options page
*/

/**
 * Enqueue JS
 *
 * These are the parent theme specific js files
 *
 * @return None
 */
add_action( 'wp_enqueue_scripts', 'pgsf_enqueue_parent_scripts_and_styles' );
function pgsf_enqueue_parent_scripts_and_styles() {
	$theme_directory = get_template_directory_uri();

	/** Tools */
	wp_enqueue_script( 'PGSF_breakpoint-image-swap-js', $theme_directory . '/js/breakpoint-image-swap.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'PGSF_responsive-youtube-iframes-js', $theme_directory . '/js/responsive-youtube-iframes.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'PGSF_email-popup-js', $theme_directory . '/js/email-popup.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'PGSF_body-copy-js', $theme_directory . '/js/sections/body-copy.js', array( 'jquery' ), '1.0.0', true );

	wp_enqueue_style( 'PGSF_normalize', $theme_directory . '/css/normalize.css' );
	wp_enqueue_style( 'PGSF_base', $theme_directory . '/css/base.css' );

	/** Elements */
	wp_enqueue_style( 'PGSF_buttons-css', 			$theme_directory . '/css/elements/buttons.css' );

	/** Blocks */
	wp_enqueue_style( 'PGSF_menu-css', 				$theme_directory . '/css/blocks/menu.css' );
	wp_enqueue_style( 'PGSF_bread-crumbs-css', 		$theme_directory . '/css/blocks/bread-crumbs.css' );
}

/**
 * Enqueue JS
 *
 * These are the parent theme specific js files that are only to be used in the dashboard.
 *
 * @return None
 */
add_action( 'admin_enqueue_scripts', 'pgsf_enqueue_parent_admin_scripts' );
function pgsf_enqueue_parent_admin_scripts() {
	$theme_directory = get_template_directory_uri();

	/** Grid */
	wp_enqueue_script( 'PGSF_grid-options-js', $theme_directory . '/js/grid/grid-options.js', array( 'jquery' ), '1.0.0', true );

	/** Tools */
	wp_enqueue_script( 'PGSF_conversion__js-to-php', $theme_directory . '/js/conversion--js-to-php.js', array( 'jquery' ), '1.0.0', true );
}

/**
 * Includes for the BYU components
 */
add_action( 'wp_enqueue_scripts', 'pgsf_enqueue_byu_web_components' );
function pgsf_enqueue_byu_web_components() {
	$theme_directory = get_template_directory_uri();
	//wp_enqueue_style( 'BYU_core-fonts', 'https://cloud.typography.com/75214/7683772/css/fonts.css' );
	wp_enqueue_script( 'BYU_core-components-js', 'https://cdn.byu.edu/2017-core-components/latest/2017-core-components.min.js' );
	wp_enqueue_style( 'BYU_core-components-css', 'https://cdn.byu.edu/2017-core-components/latest/2017-core-components.css' );
	wp_enqueue_style( 'BYU_fa-css', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
	wp_enqueue_style( 'BYU_core-components-extra-css', $theme_directory . '/css/components.css' );
}









/*add_filter('grid_feature_includes', 'my_function_and_stuff', 10, 1);
function my_function_and_stuff( $features ) {

	//array_push( $features, 'my_new_feature_slug' );
	//var_dump(print_r('WHATHATAHSDKLFJ'));
	//var_dump(print_r( $features));

	return $features;
}*/

// TODO
add_action( 'wp_enqueue_scripts', 'pgsf_include_grid_feature_styles' );
function pgsf_include_grid_feature_styles() {

	// Get features included in dashboard for this page
	$features = get_used_page_features();

	// Allow Developer to include or modify the $features beforing enqueueing the styles
	$features = apply_filters( 'grid_feature_includes', $features );

	// Alphabetize and remove duplicates from the array of features for consistency
	$features = array_unique( $features );
	sort( $features );

	// ie. slug1_slug2_slug3
	$feature_names_concat = '';
	for ( $i = 0; $i < count( $features ); $i++ ) {
		if ( $i > 0 ) {
			$feature_names_concat .= '_';
		}
		$feature_names_concat .= $features[ $i ];
	}

	// Account for grid type (display) or (custom)
	if ( 'default' === get_field( 'grid_selection', 'byu_options' ) ) {
		$feature_names_concat = 'default-' . $feature_names_concat;
	}

	// Create $file_name
	$file_name = $feature_names_concat . '.css'; // ie. slug1_slug2_slug3.css


	// Create the file if it has not been created; Enqueue the css
	if ( true === file_exists( get_stylesheet_directory() . '/grid-exports/css/' . $file_name) || true === create_grid_features_style_file( $features, $file_name ) ) {
		wp_enqueue_style( $feature_names_concat . '-css', get_stylesheet_directory_uri() . '/grid-exports/css/' . $file_name );
	}

}

// TODO
function get_used_page_features() {
	// Default to include the base top-level classes
	$features_array = array('base');
	$search_custom_page_editor = false;

	// Search through the provided tools to add the needed grid_features
	$sf_tools = wp_get_post_terms( get_the_ID(), 'sf_tools' );
	foreach ($sf_tools as $sf_tool) {
		if ( 'body-copy' === $sf_tool->slug ) {
			array_push( $features_array, 'body_copy' );
		} elseif ( 'standard-left-body-copy' === $sf_tool->slug ) {
			array_push( $features_array, 'standard_left_body_copy' );
		} elseif ( 'page-builder' === $sf_tool->slug ) {
			$search_custom_page_editor = true;
		}
	}

	// If we have nothing further to search, return here
	if ( false === $search_custom_page_editor ) {
		return $features_array;
	}

	// Search through the page_builder
	while ( have_rows('custom_page_editor') ) { the_row();

		// Display the body_copy grid feature
		if ( 'body_copy' === get_row_layout() ) {
			array_push( $features_array, 'body_copy' );
			continue;
		} 

	}
	reset_rows();

	return $features_array;
}

// TODO
function create_grid_features_style_file( $features, $file_name ) {
	// determine if default or not
	$feature_file_prefix = 'grid-feature';
	if ( 'default' === get_field( 'grid_selection', 'byu_options' ) ) {
		$feature_file_prefix = 'default-' . $feature_file_prefix;
	}

	// get all json as $json_features_array
	$json_features_array = array();

	// Foreach $features
	foreach ( $features as $feature ) {
		// if ! json in child,
		if ( ! file_exists( get_stylesheet_directory() . '/grid-exports/' . $feature_file_prefix . '__' . $feature . '.json' ) ) {
			//check parent
			if ( ! file_exists( get_template_directory() . '/grid-exports/' . $feature_file_prefix . '__' . $feature . '.json' ) ) {
				//if not found return false
				return false;
			} else {
				// else store json from parent in $json_features_array
				array_push( $json_features_array, file_get_contents( get_template_directory() . '/grid-exports/' . $feature_file_prefix . '__' . $feature . '.json' ) );
			}
		} else {
			// else store json from child in $json_features_array
			array_push( $json_features_array, file_get_contents( get_stylesheet_directory() . '/grid-exports/' . $feature_file_prefix . '__' . $feature . '.json' ) );
		}
	}


	// Iterate through all pairs of feature json files to combine them to one
	$master_json = consolidate_json_array_to_single_json( $json_features_array );

	// If error when working with json, return now
	if ( ! $master_json ) {
		return false;
	}
	$php_master_object = (array) json_decode( stripcslashes( $master_json ) );

	$new_styles = get_grid_css( $php_master_object );

	// Access the filesystem to add
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	$access_type = get_filesystem_method();
	if ( 'direct' === $access_type ) {
		// you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL
		$creds = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, false, array() );

		// initialize the API, any problems and we exit
		if ( ! WP_Filesystem( $creds ) ) {
			return false;
		}

		// make the filesystem access global
		global $wp_filesystem;

		// Create a directory to store generated files
		$wp_filesystem->mkdir( get_stylesheet_directory() . '/grid-exports/css/' );

		// Attempt to add the file! :)
		if ( 'default' === get_field( 'grid_selection', 'byu_options' ) || 'custom' === get_field( 'grid_selection', 'byu_options' ) ) {
			$wp_filesystem->put_contents( get_stylesheet_directory() . '/grid-exports/css/' . $file_name, $new_styles, FS_CHMOD_FILE );
		} else {
			return false;
		}
	} else {
		error_log( 'Doesn\'t have permissions' );
		add_action( 'admin_notices', 'file_access_no_permission' );
		return false;
	}

	// return the success!
	return true;
}









/**
 * Include the files for the accordion tool
 */
function pgsf_use_icon() {
	$theme_directory = get_template_directory_uri();
	wp_enqueue_style( 'pgsf_icon-css', $theme_directory . '/css/elements/icon.css' );
	wp_enqueue_script( 'pgsf_icon-js', $theme_directory . '/js/elements/icon.js', array( 'jquery' ), '1.0.0', true );
}


// Include Libraries
include_once dirname( __FILE__ ) . '/functions/sections/headers.php';
include_once dirname( __FILE__ ) . '/functions/sections/footers.php';
include_once dirname( __FILE__ ) . '/functions/sections/body-copy.php';

include_once dirname( __FILE__ ) . '/functions/elements/icon.php';

include_once dirname( __FILE__ ) . '/functions/tools/page-builder.php';
include_once dirname( __FILE__ ) . '/functions/tools/accordion.php';
include_once dirname( __FILE__ ) . '/functions/tools/tools-js-reference-object.php';

include_once dirname( __FILE__ ) . '/functions/tools/card.php';

// Include grid options
include_once dirname( __FILE__ ) . '/functions/grid/grid-options.php';
include_once dirname( __FILE__ ) . '/functions/grid/grid-options-generate.php';
include_once dirname( __FILE__ ) . '/functions/grid/grid-includes.php';

// Include the php export of the acf fields only if there is an active child theme
if ( get_template() !== get_stylesheet() ) {
	include_once dirname( __FILE__ ) . '/acf-json/parent_acf_exports.php';
}

/**
 * Registers taxonomy 'sf_tools'
 */
add_action( 'init', 'add_cpt_speech_taxonomies__sf_tools', 0 );
function add_cpt_speech_taxonomies__sf_tools() {

	$single_uppercase = 'SF Tool';
	$plural_uppercase = 'SF Tools';

	$single_lowercase = 'sf tool';
	$plural_lowercase = 'sf tools';

	$labels = array(
		'name'                       => _x( $single_uppercase, 'taxonomy general name' ),
		'singular_name'              => _x( $single_lowercase, 'taxonomy singular name' ),
		'search_items'               => __( 'Search ' . $plural_uppercase ),
		'popular_items'              => __( 'Popular ' . $plural_uppercase ),
		'all_items'                  => __( 'All ' . $plural_uppercase ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit ' . $single_uppercase ),
		'update_item'                => __( 'Update ' . $single_uppercase ),
		'add_new_item'               => __( 'Add New ' . $single_uppercase ),
		'new_item_name'              => __( 'New ' . $single_uppercase . ' Name' ),
		'separate_items_with_commas' => __( 'Separate ' . $plural_uppercase . ' with commas' ),
		'add_or_remove_items'        => __( 'Add or remove ' . $single_lowercase ),
		'choose_from_most_used'      => __( 'Choose from the most used ' . $plural_lowercase ),
		'not_found'                  => __( 'No ' . $plural_lowercase . ' found.' ),
		'menu_name'                  => __( $single_uppercase ),
	);
	$args = array(
		'hierarchical'          => true, // This being true makes this taxonomy behave like a category, with a heirarchy, false is a like a tag
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'sf_tools' ),
		'public'				=> false,
	);
	$types = array( 'post', 'page' ); // A list of the CPT's that are to use this taxonomy
	register_taxonomy( 'sf_tools', $types, $args );
	wp_insert_term(
		'Page Builder',
		'sf_tools',
		array(
			'description' => 'Create a full page with access to all the sf_tools for your sections'
		)
	);
	wp_insert_term(
		'Body Copy',
		'sf_tools',
		array(
			'description' => 'Fanciful body copy editing'
		)
	);
}

/**
 * Add the acf options page
 */
acf_add_options_page( array(

	/* (string) The title displayed on the options page. Required. */
	'page_title' => 'Options',

	/* (string) The title displayed in the wp-admin sidebar. Defaults to page_title */
	'menu_title' => 'Site Options',

	/* (string) The slug name to refer to this menu by (should be unique for this menu).
	Defaults to a url friendly version of menu_slug */
	'menu_slug' => 'site_options',

	/* (string) The capability required for this menu to be displayed to the user. Defaults to edit_posts.
	Read more about capability here: http://codex.wordpress.org/Roles_and_Capabilities */
	'capability' => 'edit_posts',

	/* (int|string) The position in the menu order this menu should appear.
	WARNING: if two menu items use the same position attribute, one of the items may be overwritten so that only one item displays!
	Risk of conflict can be reduced by using decimal instead of integer values, e.g. '63.3' instead of 63 (must use quotes).
	Defaults to bottom of utility menu items */
	'position' => false,

	/* (string) The icon class for this menu. Defaults to default WordPress gear.
	Read more about dashicons here: https://developer.wordpress.org/resource/dashicons/ */
	'icon_url' => false,

	/* (boolean) If set to true, this options page will redirect to the first child page (if a child page exists).
	If set to false, this parent page will appear alongside any child pages. Defaults to true */
	'redirect' => true,

	/* (int|string) The '$post_id' to save/load data to/from. Can be set to a numeric post ID (123), or a string ('user_2').
	Defaults to 'options'. Added in v5.2.7 */
	'post_id' => 'byu_options',

	/* (boolean)  Whether to load the option (values saved from this options page) when WordPress starts up.
	Defaults to false. Added in v5.2.8. */
	'autoload' => false,

) );
