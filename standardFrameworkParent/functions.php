<?php
/* Table of Contents
––––––––––––––––––––––––––––––––––––––––––––––––––
- pgsf_enqueue_parent_scripts()
- pgsf_enqueue_parent_admin_scripts()
- pgsf_enqueue_parent_styles()
- pgsf_get_parent_stylesheet_directory()
- Including php libraries
- pgsf_add_grid_view()
*/

/**
 * Enqueue JS
 * 
 * These are the parent theme specific js files
 *
 * @return None
 */
add_action( 'wp_enqueue_scripts', 'pgsf_enqueue_parent_scripts' );
function pgsf_enqueue_parent_scripts() {
	$theme_directory = pgsf_get_parent_stylesheet_directory();

	/** Fonts */

	/** Grid */
	// Script to show grid overlay added by pgsf_add_grid_view()

	/** Elements */

	/** Blocks */

	/** Sections */

	/** Pages */

	/** Tools */

	/** Other(unclassified) */
	wp_enqueue_script( 'PGSF_breakpoint-image-swap', $theme_directory . '/js/breakpoint-image-swap.js', array( 'jquery' ), '1.0.0', true );

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
	$theme_directory = pgsf_get_parent_stylesheet_directory();

	/** Tools */
	wp_enqueue_script( 'PGSF_conversion__js-to-php', $theme_directory . '/js/conversion--js-to-php.js', array( 'jquery' ), '1.0.0', true );
}

/**
 * Enqueue CSS
 * 
 * These are the parent theme specific css files
 *
 * @return None
 */
add_action( 'wp_enqueue_scripts', 'pgsf_enqueue_parent_styles' );
function pgsf_enqueue_parent_styles() {
	$theme_directory = pgsf_get_parent_stylesheet_directory();

	/** Fonts */
	wp_enqueue_style( 'PGSF_font-awesome-css', 		$theme_directory . '/fonts/fa/font-awesome.css' );

	/** (1) Include Font Awesome, (2) use MIT css normalization, (3) set chosen base settings on html elements */
	wp_enqueue_style( 'PGSF_normalize-css', 		$theme_directory . '/css/normalize.css' );
	wp_enqueue_style( 'PGSF_base-css', 				$theme_directory . '/css/base.css' );

	/** Grid */
	wp_enqueue_style( 'PGSF_grid-layout-css', 		$theme_directory . '/css/grid/grid-layout.css' );
	wp_enqueue_style( 'PGSF_grid-layout2-css', 		$theme_directory . '/css/grid/grid-layout2.css' ); // TODO - This styling should be programmatically created and absorbed into 'grid-layout'

	/** Elements */
	wp_enqueue_style( 'PGSF_buttons-css', 			$theme_directory . '/css/elements/buttons.css' );

	/** Blocks */
	wp_enqueue_style( 'PGSF_menu-css', 				$theme_directory . '/css/blocks/menu.css' );
	wp_enqueue_style( 'PGSF_bread-crumbs-css', 		$theme_directory . '/css/blocks/bread-crumbs.css' );

	/** Sections */
	wp_enqueue_style( 'PGSF_header-css', 			$theme_directory . '/css/sections/header.css' );
	wp_enqueue_style( 'PGSF_byu-ribbon-css', 		$theme_directory . '/css/sections/ribbon.css' );
	wp_enqueue_style( 'PGSF_byu-footer-css', 		$theme_directory . '/css/sections/footer.css' );

	/** Pages */

}


/**
 * Retrieves the directory of this theme, regardless of whether it is used as a parent theme or by itself.
 * 
 * Typically when you want to retrieve the directory of an active theme you call "get_bloginfo( 'stylesheet_directory' );"
 * which returns the directory of the current active theme. However, when there is a child theme activated, it will return
 * the directory of the child theme, not the parent theme. This function allows both the child and the parent to retrieve
 * the directory of the parent theme.
 *
 * @return String the directory of the parent theme
 */
function pgsf_get_parent_stylesheet_directory() {
	$theme_directory_array = explode( '/', get_bloginfo( 'stylesheet_directory' ) );
	$child_theme_name = $theme_directory_array[ sizeof( $theme_directory_array ) - 1 ];

	$parent_directory_array = explode( '/', dirname( __FILE__ ) );
	$parent_theme_name = $parent_directory_array[ sizeof( $parent_directory_array ) - 1 ];

	$theme_directory = get_bloginfo( 'stylesheet_directory' );
	$parent_theme_directory = str_replace( $child_theme_name, $parent_theme_name, $theme_directory );

	return $parent_theme_directory;
}

// Include Libraries
//include_once dirname( __FILE__ ) . '/functions/sections/ribbons.php';
include_once dirname( __FILE__ ) . '/functions/sections/footers.php';
include_once dirname( __FILE__ ) . '/functions/sections/headers.php';

// Include grid CSS file generators
include_once dirname( __FILE__ ) . '/functions/grid/generate-grid-layout.php';
include_once dirname( __FILE__ ) . '/functions/sf-settings.php';

/**
 * Add the Grid overlay accessed by pressing 'g' to localhosts and '-alpha' sites.
 * Includes the needed js file, and outputs the html into the footer.
 *
 * @return None
 */
function pgsf_add_grid_view() {
	$current_site = get_site_url();

	if(false !== strpos($current_site, 'localhost')) {
		add_action( 'wp_enqueue_scripts', function() {
			$theme_directory = pgsf_get_parent_stylesheet_directory();
			wp_enqueue_script( 'PGSF_grid-overlay', $theme_directory . '/js/grid/grid-overlay.js', array( 'jquery' ), '1.0.0', true );
		});
		add_action( 'wp_footer', 'PGSF_add_grid_line_overylay' );
	}
}
pgsf_add_grid_view();



$args = array(
	
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
	'post_id' => 'parent_options',
	
	/* (boolean)  Whether to load the option (values saved from this options page) when WordPress starts up. 
	Defaults to false. Added in v5.2.8. */
	'autoload' => false,
	
);
acf_add_options_page( $args );


