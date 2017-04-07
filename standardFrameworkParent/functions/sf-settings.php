<?php

/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
/* Manage Site Specific Settings GUI */
/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
/* –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––– */
add_action( 'admin_menu', 'google_plugin_menu' );
function google_plugin_menu() {
	$page_title = 'Google Settings Page';
	$menu_title = 'Google Settings';
	$capability = 'manage_options';
	$menu_slug = 'google-settings';
	$function = 'standardframeworkCustomSiteSettings';
	add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
}

function standardFrameworkCustomSiteSettings() {  

$search_production = 'ga_search_production';
$search_dev = 'ga_search_development';
$analytics_production = 'ga_analytics_production';
$analytics_dev = 'ga_analytics_development';

?>


	<h2>Standard Framework Site Specific Settings </h2>

<p> You can find these codes in the following areas: </p>
<p> <b>Google Search Code</b> <br>
<ul style="list-style-type:square">
<li>Please go to <a href='https://cse.google.com'>cse.google.com</a> and log into the google account the manages your search engines. 
<li>Once there, select the site you are currently working on. Once selected, you will be taken to a page with basic settings.  </li>

<li>About halfway down the page in the <b>Details</b> section you will see a button that says <b> Search Engine ID </b>.</li> 
<li>Select the button and copy and paste the snippet of characters into the corresponding textbox on your wordpress site for Google Search. This could be either development or production, depending on the site. </li>
<li>Make sure there are no trailing spaces.</li>
</ul>
<br>

 <p> <b>Google Analytics Code</b> <br>
 <ul style="list-style-type:square">
<li>Please go to <a href='https://analytics.google.com'>analytics.google.com</a> and log into the google account the manages your analytics.</li>
<li>Once there, select the site you are currently working on (move through the folders until you get to the title that has a world icon next to it).</li>
<li>Once selected, select the <b>Admin</b> menu in the top righthand corner.</li>
<li>From there, in the middle column select the Tracking Info tab. </li>
<li>A dropdown will open, and select <b>Tracking Code</b>. </li>
<li>Underneath <b>Tacking ID </b> is your google analytics tracking code. Copy and paste the snippet of characters into the corresponding textbox.</li>
<li> Make sure there are no trailing spaces.</li>
</ul><br>


 <p><b> ****** NOTE *******<br>
 Neither analytics nor search will work till these variables have been added to the site.
 </b>
 </p><br>

<form class="site_variable_customization_form" method="post" action="<?php echo $_SERVER['options-general.php?page=google-settings']; ?>">
		<div class="google_variable">
			<p>Google Search Code for Development (<b>alpha site</b>)</p>
			   			<textarea name="search_dev" cols="50" rows="1" id="event_desc"><?php echo get_site_option('ga_search_development');?></textarea><br>

			<p>Google Search Code for Production (<b>beta site</b>)</p>
			<textarea name="search_production" cols="50" rows="1" id="event_desc"><?php echo get_site_option('ga_search_production');?></textarea><br>

			<p>Google Analytics Code for Development (<b>beta site</b>)</p>
			<textarea name="analytics_dev" cols="50" rows="1" id="event_desc"><?php echo get_site_option('ga_analytics_development');?></textarea><br>

			<p>Google Analytics Code for Production (<b>live site</b>)</p>
			   <textarea name="analytics_production" cols="50" rows="1" id="event_desc"><?php echo get_site_option('ga_analytics_production');?></textarea><br><br>


			<input name="submit" type="submit"  name="set_variable" value="Save Snippets"><br>
		</div>
		<hr>
	</form>



<?php

// Save Search Development Code to Database


  if($_POST && isset($_POST['search_dev'])) {

    $info = $_POST['search_dev'];

    if(!$info) { } 

    else {


		$new_value = $_POST['search_dev'] ;

		if ( get_option( $search_dev ) !== false ) {

    		// The option already exists, so we just update it.
   			 update_option( $search_dev, $new_value );

		} else {

    		// The option hasn't been added yet. We'll add it with $autoload set to 'no'.
    		$deprecated = null;
   			 $autoload = 'no';
    		add_option( $search_dev, $new_value, $deprecated, $autoload );
		}

	}
  } 


// Save Search Production Code to Database

    if($_POST && isset($_POST['search_production'])) {

    $info = $_POST['search_production'];

    if(!$info) { } 

    else {


		$new_value = $_POST['search_production'] ;

		if ( get_option( $search_production ) !== false ) {

    		// The option already exists, so we just update it.
   			 update_option( $search_production, $new_value );

		} else {

    		// The option hasn't been added yet. We'll add it with $autoload set to 'no'.
    		$deprecated = null;
   			 $autoload = 'no';
    		add_option( $search_production, $new_value, $deprecated, $autoload );
		}

	}
  } 


// Save Analytics Development Code to Database

    if($_POST && isset($_POST['analytics_dev'])) {

    $info = $_POST['analytics_dev'];

    if(!$info) { } 

    else {


		$new_value = $_POST['analytics_dev'] ;

		if ( get_option( $analytics_dev ) !== false ) {

    		// The option already exists, so we just update it.
   			 update_option( $analytics_dev, $new_value );

		} else {

    		// The option hasn't been added yet. We'll add it with $autoload set to 'no'.
    		$deprecated = null;
   			 $autoload = 'no';
    		add_option( $analytics_dev, $new_value, $deprecated, $autoload );
		}

	}
  } 


// Save Analytics Production Code to Database

    if($_POST && isset($_POST['analytics_production'])) {

    $info = $_POST['analytics_production'];

    if(!$info) { } 

    else {


		$new_value = $_POST['analytics_production'] ;

		if ( get_option( $analytics_production ) !== false ) {

    		// The option already exists, so we just update it.
   			 update_option( $analytics_production, $new_value );

		} else {

    		// The option hasn't been added yet. We'll add it with $autoload set to 'no'.
    		$deprecated = null;
   			 $autoload = 'no';
    		add_option( $analytics_production, $new_value, $deprecated, $autoload );
		}

	}
  } 



}


