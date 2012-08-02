<!doctype html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<title>
<?php if ( is_category() ) {
            echo 'Category Archive for &quot;'; single_cat_title(); echo '&quot; | '; bloginfo( 'name' );
        } elseif ( is_tag() ) {
            echo 'Tag Archive for &quot;'; single_tag_title(); echo '&quot; | '; bloginfo( 'name' );
        } elseif ( is_archive() ) {
            wp_title(''); echo ' Archive | '; bloginfo( 'name' );
        } elseif ( is_search() ) {
            echo 'Search for &quot;'.wp_specialchars($s).'&quot; | '; bloginfo( 'name' );
        } elseif ( is_home() ) {
            bloginfo( 'name' ); echo ' | '; bloginfo( 'description' );
        }  elseif ( is_404() ) {
            echo 'Error 404 Not Found | '; bloginfo( 'name' );
        } elseif ( is_single() ) {
            wp_title('');
        } else {
            echo wp_title( ' | ', false, 'right' ); bloginfo( 'name' );
        } ?>
</title>
<meta name="description" content="<?php wp_title(''); echo ' | '; bloginfo( 'description' ); ?>" />
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width"/>
<link rel="icon" href="<?php bloginfo('template_url'); ?>/template/img/favicon.ico" type="image/x-icon" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ); ?>" href="<?php bloginfo( 'rss2_url' ); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo( 'name' ); ?>" href="<?php bloginfo( 'atom_url' ); ?>" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'template_url' ); ?>/template/css/style.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'template_url' ); ?>/template/css/plugins/socialmedia.css" />

<script>
    var webRoot = '<?php echo bloginfo('template_url'); ?>';
</script>

<?php /* this is used by many Wordpress features and for plugins to work proporly */ ?>
<?php wp_enqueue_script("modernizr", get_stylesheet_directory_uri() . "/template/js/libs/modernizr-2.0-basic.min.js"); ?>
<?php wp_deregister_script( 'jquery' ); ?>
<?php wp_enqueue_script("jquery", "http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"); /* Loads jQuery if it hasn't been loaded already */ ?>
<?php wp_enqueue_script("template_script", get_stylesheet_directory_uri() . "/template/js/script.js"); ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header id="main-header">
<div id="header-top" class="wrapper">
	<div id="logo"> 
		<a href="http://www.byu.edu/" class="byu"><img src="<?php bloginfo( 'template_url' ); ?>/template/img/byu-logo.gif" alt="BYU Logo" /></a>
	</div>
<a href="<?php echo home_url( '/' ); ?>" id="site-name"><?php echo get_bloginfo( 'name' ); ?></a>
<div id="search-container">
	<?php get_search_form();?>
</div>

<?php wp_nav_menu( array( 'theme_location' => 'secondary-menu', 'walker'=> new Byu_Secondary_Nav_Walker(), 'container' => 'nav', 'container_id' => 'secondary-nav' ) ); ?>
</header>

<?php wp_nav_menu( array( 'theme_location' => 'main-menu', 'walker'=> new Byu_Main_Nav_Walker(), 'container' => 'nav', 'container_id' => 'primary-nav' ) ); ?>
