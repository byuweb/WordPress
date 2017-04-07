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
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<title><?php wp_title(); ?></title>
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">


	<meta name="viewport" content="width=device-width, initial-scale1.0">
	<script async src="https://cdn.byu.edu/2017-core-components/latest/2017-core-components.min.js"></script>
	  <!-- This is the Light Font Package - Vitesse and Gotham only. -->
	<link rel="stylesheet" href="//cloud.typography.com/75214/7683772/css/fonts.css">
	<link rel="stylesheet" href="//cdn.byu.edu/2017-core-components/latest/2017-core-components.css">

<style type="text/css">
	html,body{
		width: 100vw;
		min-width:100vw;
		max-width: 100vw; 
		margin: 0;
	}
  .byu-ribbon__search__submit {
    display: none !important;
  }
  
  .byu-ribbon__search .byu-ribbon__search__text-box {
    padding: 5px 10px;
    border: none;
    box-sizing: border-box;
  }
  
  .mobile-view .byu-ribbon__search .byu-ribbon__search__text-box {
    padding: 5px 10px;
    width: 100%;
    height: 35px;
    border: none;
    border-bottom: 1px solid #c5c5c5;
}

button#search-button {
    background-color: var(--byu-search-color,#767676);
    border: 1px solid var(--byu-search-color,#767676);
    color: #fff;
    width: 30px;
    height: 34px !important;
}

</style>





	<?php wp_head(); ?>
</head>




<body <?php body_class(); ?> >


<!-- HEADER -->
<header class="page-header">
<byu-header>


	<span slot="site-title"> <?php echo esc_html( get_field( 'site_title', 'parent_options' ) ); ?></span>

  <byu-search slot="search" onsearch="wpSearch">
    <form class="byu-ribbon__search" action="https://brand-dev.byu.edu/google-cse" method="get" _lpchecked="1">
      <input class="byu-ribbon__search__text-box sm-hidden med-hidden mlg-hidden text-area" placeholder="Search" name="q">
      <input class="byu-ribbon__search__submit sm-hidden med-hidden mlg-hidden" type="submit" value="" name="submit">
    </form>
  </byu-search>


  </byu-search>

    <script>
      function wpSearch(value) {
        document.querySelector('.byu-ribbon__search__submit').click();
      }
    </script>


	<?php pgsf_get_byu_navigation(); ?>

</byu-header>

</header> <!-- END HEADER -->

<div id="content" class="site-content">







