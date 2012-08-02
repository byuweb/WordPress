<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">    
	<input type="text" placeholder="Search <?php echo get_bloginfo( 'name' ); ?>" value="" name="s" id="search" />
	<input type="image" src="<?php echo get_stylesheet_directory_uri() . '/template/img/search-button.png' ?>" id="search-button" value="Search" />    
</form>