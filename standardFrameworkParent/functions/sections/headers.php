<?php
/**
 * This document contains the markup for various byu ribbons obtainable via function calls
 *
 */





/*********************** Taken from ribbons.php ************************/


/**
 * This document contains the markup for various byu ribbons obtainable via function calls
 *
 */

function pgsf_get_byu_navigation() { 



		if ( get_field( 'display_header_sub-navigation', 'parent_options' ) ) { ?>
			<byu-menu slot="nav"> 

				<?php // loop through the rows of data
			 	while ( have_rows( 'sub-navigation', 'parent_options' ) ) { the_row();

					// check current row layout
					if ( 'link_information' === get_row_layout() ) {

						$link_title = get_sub_field( 'title' );
						$link_url = get_sub_field( 'url' ); ?>

						<li><a href=" <?php echo esc_url( $link_url ); ?>"><?php echo esc_html( $link_title ); ?></a></li>
					
					<?php }
				} ?>
			</byu-menu>
		<?php }
}




/*
function pgsf_get_byu_subnav() {
	?>
	<nav class='byu-ribbon__subnav'>
		<div class='menu-icon fa fa-bars lg-hidden xlg-hidden' aria-hidden="true"></div>
		<div class='subnav'>
			<div class='subnav--title'>
				<span>Speeches Menu</span>
				<button class='subnav--exit-button' type='exit-button'>X</button>
			</div>
			<div class ='subnav-menu'>
				<?php
					$GLOBALS['pre_get_posts'] = false;
					$args = array( 'pagename' => 'speeches' );
					$query = new WP_Query( $args );

				if ( $query->have_posts() ) {
						$query->the_post();
					if ( have_rows( 'nav_tab' ) ) {
							$total_rows = count( get_field( 'nav_tab' ) );
							$count = 0;
						while ( have_rows( 'nav_tab' ) ) {
								the_row();
							if ( $count < $total_rows - 1 ) {
									?>
									<a class='subnav-menu__item' href='<?php the_sub_field( 'tab_url' ); ?>'>
										<span><?php the_sub_field( 'tab_name' ); ?></span>
									</a>
									<?php
							} else {
									?>
									<a class='subnav-menu__item--last' href='<?php the_sub_field( 'tab_url' ); ?>'>
										<span><?php the_sub_field( 'tab_name' ); ?></span>
									</a>
									<?php
							}
								$count++;
						}
					}
						// Restore original Post Data
						wp_reset_postdata();
				}
					$GLOBALS['pre_get_posts'] = null; ?>
			</div>
		</div>

		<form class="byu-ribbon__search__subnav lg-hidden xlg-hidden" action="<?php echo esc_url( get_site_url() ); ?>/google-cse" method="get">
			<input class="byu-ribbon__search__submit__subnav" type="submit" value="&#xf002;" name="submit">
			<input class="byu-ribbon__search__text-box__subnav text-area" placeholder="Search Speeches" name="q">
		</form>
		<div class='subnav__items sm-hidden med-hidden mlg-hidden'>
			<?php
				$GLOBALS['pre_get_posts'] = false;
				$args = array( 'pagename' => 'speeches' );
				$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
					$query->the_post();
				if ( have_rows( 'nav_tab' ) ) {
					while ( have_rows( 'nav_tab' ) ) {
							the_row(); ?>
							<a class='subnav__item' href='<?php the_sub_field( 'tab_url' ); ?>'>
								<span><?php the_sub_field( 'tab_name' );?></span>
							</a>
							<?php
					}
				}
					// Restore original Post Data
					wp_reset_postdata();
			}
				$GLOBALS['pre_get_posts'] = null;?>
		</div>
	</nav>
	<?php
}
*/

/* BYU Ribbon Search Overlay (AJAX)
–––––––––––––––––––––––––––––––––––––––––––––––––– */
/*
add_action( 'wp_ajax_nopriv_pgsf_overlaySearch', 'pgsf_overlay_search' ); // Non logged in users
add_action( 'wp_ajax_pgsf_overlaySearch', 'pgsf_overlay_search' ); // logged in users
function pgsf_overlay_search() {
	?>

	<div class="overlay overlay--dark overlay--fullscreen overlay-search">
		<span class="overlay__exit"><i class="fa fa-close"></i></span>
		<form class="overlay-search__form" action="<?php echo esc_url( get_site_url() ); ?>/google-cse" method="get">
			<input class="overlay-search__text-box text-area text-area__black" type="text" placeholder="Search <?php echo esc_html( get_bloginfo( 'name' ) ); ?>" name="q">
			<input class="overlay-search__submit button button__turquoise" type="submit" value="SEARCH" name="submit">
		</form>
	</div>

	<?php
	die();
}
*/


/* Breadcrumbs
–––––––––––––––––––––––––––––––––––––––––––––––––– */
/*function pgsf_get_bread_crumbs() {
	// No breadcrumbs on the front page
	if ( ! is_front_page() ) {
		?>
				<section>
					<?php if ( function_exists( 'yoast_breadcrumb' ) ) {
						yoast_breadcrumb( '<p id="breadcrumbs" class="breadcrumb-copy">','</p>' );
}
					?>
				</section>
			<?php }
}
*/


























