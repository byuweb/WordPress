<?php

function pgsf_wp_head() { ?>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<title><?php wp_title(); ?></title>
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	
	<?php wp_head();
}

function pgsf_the_byu_header() { ?>

	<byu-header>

		<span slot="site-title"> <?php echo esc_html( get_field( 'site_title', 'byu_options' ) ); ?></span>

		<byu-search slot="search" onsearch="wpSearch">
		    <form class="byu-ribbon__search" action="https://brand-dev.byu.edu/google-cse" method="get" _lpchecked="1">
				<input class="byu-ribbon__search__text-box sm-hidden med-hidden mlg-hidden text-area" placeholder="Search" name="q">
				<input class="byu-ribbon__search__submit sm-hidden med-hidden mlg-hidden" type="submit" value="" name="submit">
		    </form>
		</byu-search>

	    <script>
	      function wpSearch(value) {
	        document.querySelector('.byu-ribbon__search__submit').click();
	      }
	    </script>

		<?php pgsf_the_byu_navigation(); ?>

	</byu-header>
	<?php
}

function pgsf_the_byu_navigation() { 

	if ( get_field( 'display_byu_header_menu', 'byu_options' ) ) { ?>
		<byu-menu slot="nav"> 

			<?php // loop through the rows of data
		 	while ( have_rows( 'byu_header_menu', 'byu_options' ) ) { the_row();

				// check current row layout
				if ( 'menu_link' === get_row_layout() ) {

					// Prep link information
					$link_title = get_sub_field( 'text' );
					$link_url = '';

					// Check the link type
					if( 'internal' === get_sub_field( 'type' ) ) {
						$link_url = get_site_url() . get_sub_field( 'relative_url' );
					} else if ( 'external' === get_sub_field( 'type' ) ) {
						$link_url = get_sub_field( 'url' );
					}

					// Output the link ?>
					<a href=" <?php echo esc_url( $link_url ); ?>"><?php echo esc_html( $link_title ); ?></a>
				<?php
				}
			} ?>

		</byu-menu>
	<?php }
}
