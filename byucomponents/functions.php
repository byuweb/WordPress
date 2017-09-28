<?php
/* Table of Contents
––––––––––––––––––––––––––––––––––––––––––––––––––
- pgsf_enqueue_parent_admin_scripts()
- pgsf_enqueue_byu_web_components()
- Include theme Libraries
- Include ACF fields for child theme
- Add ACF options page
*/

/**
 * Enqueue JS
 * These are the parent theme specific js files that are only to be used in the dashboard.
 */
add_action( 'admin_enqueue_scripts', 'pgsf_enqueue_parent_admin_scripts' );
function pgsf_enqueue_parent_admin_scripts() {}

/**
 * Includes for the BYU components
 */
add_action( 'wp_enqueue_scripts', 'pgsf_enqueue_byu_web_components' );
function pgsf_enqueue_byu_web_components() {
	$theme_directory = get_template_directory_uri();

	wp_enqueue_style( 'BYU_core-fonts', 'https://cloud.typography.com/75214/7683772/css/fonts.css' );
	wp_enqueue_script( 'BYU_core-components-js', 'https://cdn.byu.edu/byu-theme-components/latest/byu-theme-components.min.js' );
	wp_enqueue_style( 'BYU_core-components-css', 'https://cdn.byu.edu/byu-theme-components/latest/byu-theme-components.min.css' );
	wp_enqueue_style( 'BYU_fa-css', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
	wp_enqueue_style( 'BYU_core-components-extra-css', $theme_directory . '/css/components.css' );
}

/**
 * Include theme Libraries
 */
include_once dirname( __FILE__ ) . '/functions/sections/headers.php';
include_once dirname( __FILE__ ) . '/functions/sections/footers.php';

/**
 * Include the php export of the acf fields for child theme
 */
if ( get_template() !== get_stylesheet() ) {
	include_once dirname( __FILE__ ) . '/acf-json/parent_acf_exports.php';
}

/**
 * Add ACF options page
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
