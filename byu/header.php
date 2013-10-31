<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package byu-responsive
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<link rel="stylesheet" href="//cloud.typography.com/75214/740862/css/fonts.css" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/base.css" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/responsive.css" media="all and (min-width:16em)" />

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>
	
	<header id="main-header" role="banner">
      <div id="header-top" class="wrapper">
        <div id="logo">
          <h2><a href="http://www.byu.edu/" class="byu">Brigham Young University</a></h2>
        </div>
        <!-- This should link to your organization's home page-->
        <h1><a id="site-name" href="#" title="Change this link to your organization's home page">Primary Organization</a></h1><a href="http://home.byu.edu/home/cas" class="sign-in button">Sign in</a>
      </div>
    </header>
	
    <div id="search-menu">
      <div id="search-container" role="search">
		<?php get_search_form(); ?>
       <!-- <form id="basic-search" action="http://home.byu.edu/home/search" role="form">
          <label for="search" class="visuallyhidden">Search</label>
          <input id="search" type="search" name="search">
          <input id="search-submit" type="submit" value="Search">
        </form>
		-->
      </div>
	  <a href="#primary-nav" class="menu-button">Menu</a>
    </div>
	
	<div class="nav-container">
		<!-- Primary Nav-->
		<nav id="primary-nav" role="navigation">
			<?php wp_nav_menu(); ?>

		</nav>
		
		<!-- Secondary Nav-->
		<nav id="secondary-nav" role="navigation">

		</nav>
		
</header>


		
		
		
		
	</div>
	


	<div id="content" role="main" class="wrapper clearfix ">
