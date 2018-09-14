<?php
/**
 * Outputs the byu footer according to the ACF field inputs on the dashboard found in Site Options tab
 *
 */
function pgsf_the_byu_footer() { ?>

	<byu-footer class= "nocontent">
		<?php

		if ( get_field( 'byu_footer_navigation', 'byu_options' ) ) { ?>

			<?php // loop through the rows of data
			while ( have_rows( 'byu_footer_columns', 'byu_options' ) ) { the_row();
				if ( 'column_content' === get_row_layout() ) {
					pgsf_the_byu_footer__column();
				} 
			}
		} ?>

	</byu-footer>	
	<?php
} 
function pgsf_the_byu_footer__column() { ?>
	<byu-footer-column>
		<span slot="header"><?php echo esc_html( get_sub_field( 'column_header' ) ); ?></span>

		<?php // check current row layout
		if ( have_rows( 'column_rows' ) ){

			while ( have_rows( 'column_rows' ) ) {
				$row_info = the_row(); 

				if ( 'social_media' === get_row_layout() ) { 

					$links_info = $row_info[ array_keys( $row_info )[1] ];
					pgsf_the_byu_footer__column__social_media($links_info);

				} elseif ( 'action_button' === get_row_layout() ) {

					pgsf_the_byu_footer__column__action_button();

				} elseif ( 'link' === get_row_layout() ) {

					pgsf_the_byu_footer__column__link();

				} elseif ( 'telephone_link' === get_row_layout() ) {

				    pgsf_the_byu_footer__column__telephone__link();

				} elseif ( 'email_link' === get_row_layout() ) {

					pgsf_the_byu_footer__column__email__link();

				} elseif ( 'info_text' === get_row_layout() ) {

					pgsf_the_byu_footer__column__info_text();

				} 
			}
		} ?>

	</byu-footer-column>
	<?php
}
function pgsf_the_byu_footer__column__social_media( $links_info ) { ?>
	<byu-social-media-links>
		<?php
		while ( have_rows( 'social_media_links' ) ) { the_row(); 
			$slot = '';
			$url = get_sub_field('url');

			if ( 'twitter' === get_row_layout() ) { 
				$slot = 'twitter';
			} elseif ( 'facebook' === get_row_layout() ) {
				$slot = 'facebook';
			} elseif ( 'linkedin' === get_row_layout() ) {
				$slot = 'linkedin';
			} elseif ( 'youtube' === get_row_layout() ) {
				$slot = 'youtube';
			} elseif ( 'google_plus' === get_row_layout() ) {
				$slot = 'googleplus';
			} elseif ( 'instagram' === get_row_layout() ) {
				$slot = 'instagram';
			} elseif ( 'pinterest' === get_row_layout() ) {
			    $slot = 'pinterest';
            } elseif ( 'rss' === get_row_layout() ) {
				$slot = 'rss';
			} elseif ( 'podcast' === get_row_layout() ) {
				$slot = 'podcast';
			}
			?>
			<a class="<?php echo esc_attr( $slot ); ?>" href="<?php echo esc_attr( $url ); ?>"></a>
			<?php
		} ?>
	</byu-social-media-links>
	<?php
}
function pgsf_the_byu_footer__column__action_button() {

	// Prep link information
	$link_title = get_sub_field( 'button_label' );
	$link_url = get_sub_field( 'button_link' ); 

	// Check the link type
	if( 'internal' === get_sub_field( 'type' ) ) {
		$link_url = get_site_url() . get_sub_field( 'relative_url' );
	} else if ( 'external' === get_sub_field( 'type' ) ) {
		$link_url = get_sub_field( 'url' );
	} ?>

	<byu-footer-action-button>
		<a href="<?php echo esc_url( $link_url ); ?>" slot="actiontext"><?php echo esc_html( $link_title ); ?></a>
	</byu-footer-action-button>

	<?php
}
function pgsf_the_byu_footer__column__link() {
	// Prep link information
	$link_title = get_sub_field( 'text' );
	$link_url = '';

	// Check the link type
	if( 'internal' === get_sub_field( 'type' ) ) {
		$link_url = get_site_url() . get_sub_field( 'relative_url' );
	} else if ( 'external' === get_sub_field( 'type' ) ) {
		$link_url = get_sub_field( 'url' );
	} ?>

	<p><a href=" <?php echo esc_url( $link_url ); ?>"><?php echo esc_html( $link_title ); ?></a></p>


	<?php
}
function pgsf_the_byu_footer__column__telephone__link() {
	// Prep link information
	$phone_number = get_sub_field( 'text' );
	$phone_link = 'tel:' . get_sub_field('phone_number');
    ?>

	<p><a href=" <?php echo esc_url( $phone_link ); ?>"><?php echo esc_html( $phone_number ); ?></a></p>


	<?php
}
function pgsf_the_byu_footer__column__email__link() {
	// Prep link information
	$link_title = get_sub_field( 'text' );
	$email_link = 'mailto:' . get_sub_field('email');
	?>

    <p><a href=" <?php echo esc_url( $email_link ); ?>"><?php echo esc_html( $link_title ); ?></a></p>


	<?php
}
function pgsf_the_byu_footer__column__info_text() { ?>

	<p><?php echo get_sub_field( 'text' ); ?></p>

	<?php
}

/**
 * Injects the necessary footer scripts for analytics and search. Called from footer.php
 */
function pgsf_the_analytics_scripts() {
	
}
function pgsf_the_search_scripts() {
	$url = get_site_url(); ?>

	<?php
	if (false !== strpos($url,'beta')) { ?>

		<!-- Google analytics Development code-->
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
		  ga('create', '<?php echo get_site_option('ga_analytics_development');?>', 'auto');
		  ga('send', 'pageview');
		</script>

	 <?php
	 } elseif ( false === strpos( $url,'beta' ) && false === strpos( $url, 'alpha' ) && false === strpos( $url,'localhost' ) ) { ?>

		<!-- Google analytics Production code-->
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
		  ga('create', '<?php echo get_site_option('ga_analytics_production');?>', 'auto');
		  ga('send', 'pageview');
		</script>

	<?php
	}

	if (false !== strpos($url,'localhost')  || false !== strpos($url,'alpha')) { ?>

		<!-- Google Search Development code-->
		<script>
		  (function() {
		    var cx = '<?php echo get_site_option('ga_search_development');?>';
		    var gcse = document.createElement('script');
		    gcse.type = 'text/javascript';
		    gcse.async = true;
		    gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
		    var s = document.getElementsByTagName('script')[0];
		    s.parentNode.insertBefore(gcse, s);
		  })();
		</script>

	<?php 
	} else { ?>

		<!-- Google Search Production code-->
		<script>
		  (function() {
		    var cx = '<?php echo get_site_option('ga_search_production');?>';
		    var gcse = document.createElement('script');
		    gcse.type = 'text/javascript';
		    gcse.async = true;
		    gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
		    var s = document.getElementsByTagName('script')[0];
		    s.parentNode.insertBefore(gcse, s);
		  })();
		</script>

	<?php
	}
}