<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<?php pgsf_wp_head(); ?>
</head>

<body <?php body_class(); ?> >

	<header class="page-header">
		<?php pgsf_the_byu_header(); ?>
	</header>

	<main class="page-content">
