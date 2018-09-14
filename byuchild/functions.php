<?php

/* Enqueue Child Theme Scripts and Styles
–––––––––––––––––––––––––––––––––––––––––––––––––– */
add_action( 'wp_enqueue_scripts', 'start_enqueue_scripts' );
function start_enqueue_scripts() {
	$theme_directory = get_bloginfo( 'stylesheet_directory' );
}

add_action( 'wp_enqueue_scripts', 'start_enqueue_styles' );
function start_enqueue_styles() {
	$theme_directory = get_bloginfo( 'stylesheet_directory' );
}
