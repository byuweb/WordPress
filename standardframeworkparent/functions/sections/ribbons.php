<?php
/**
 * This document contains the markup for various byu ribbons obtainable via function calls
 *
 */

function pgsf_get_byu_ribbon_1() {
	?>

	<div id="byu-ribbon" class="byu-ribbon-1">
		<div class="container">
			<a class="byu-ribbon-1__logo" href="http://byu.edu"></a>
		</div>
	</div>

	<?php
}


function pgsf_get_byu_ribbon_2() {
	?>

	<div id="byu-ribbon" class="byu-ribbon-2">
		<div class="container">
			<a class="byu-ribbon-2__logo" href="http://byu.edu"></a>
			<a class="byu-ribbon-2__site-title sm-hidden" href="<?php echo esc_url( get_site_url() ); ?>"><?php echo 'Speeches' ?></a>
			<div class="byu-ribbon-2__right-objects-container">
				<nav class="byu-ribbon__nav-menu nav-menu">
					<a class="byu-ribbon__nav-menu__menu-icon">Menu</a>
					<?php do_action( 'pgsf_byu_ribbon__menu' ); ?>
				</nav>
				<form class="byu-ribbon__search" action="<?php echo esc_url( get_site_url() ); ?>/google-cse" method="get">
					<input class="byu-ribbon__search__text-box sm-hidden med-hidden mlg-hidden text-area" type="text" placeholder="Search <?php echo esc_html( get_bloginfo( 'name' ) ); ?>" name="q">
					<input class="byu-ribbon__search__submit" type="submit" value="&#xf002;" name="submit">
				</form>
			</div>
		</div>
	</div>

	<?php
}

function pgsf_get_byu_ribbon_3() {
	?>
<div id="byu-ribbon" class="byu-ribbon-3">
		<div class="container">
			<a class="byu-ribbon-3__logo" href="<?php echo esc_url( get_site_url() ); ?>"></a>
			<a class="byu-ribbon-3__site-title" href="<?php echo esc_url( get_site_url() ); ?>"><?php echo esc_html( str_replace("BYU ", "", get_bloginfo( 'name' )) ); ?></a>
			<div class="byu-ribbon-3__right-objects-container">
				<form class="byu-ribbon__search" action="<?php echo esc_url( get_site_url() ); ?>/search" method="get">
					<input class="byu-ribbon__search__text-box sm-hidden med-hidden mlg-hidden text-area" placeholder="Search" name="q">
					<input class="byu-ribbon__search__submit sm-hidden med-hidden mlg-hidden" type="submit" value="&#xf002;" name="submit">
				</form>
			</div>
		</div>
	</div>
	<?php
}

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
							?>
							<a class='subnav-menu__item' href='<?php the_sub_field( 'tab_url' ); ?>'>
								<span><?php the_sub_field( 'tab_name' ); ?></span>
							</a>
							<?php
						}
						?>
						<a class='subnav-menu__item--last' href='https://deseretbook.com/bookshelf-plus?utm_source=byuspeeches&utm_medium=online&utm_campaign=plus&utm_content=sponsorship' target="_blank">
							<img src='<?php echo esc_url( get_site_url() ); ?>/wp-content/themes/standardframeworkparent/images/deseretbook_header.png'>
						</a>
						<?php
					}

						wp_reset_postdata();
				}
					$GLOBALS['pre_get_posts'] = null; ?>
			</div>
		</div>

		<form class="byu-ribbon__search__subnav lg-hidden xlg-hidden" action="<?php echo esc_url( get_site_url() ); ?>/search" method="get">
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
					wp_reset_postdata();
			}
				$GLOBALS['pre_get_posts'] = null;?>
				<div class='byu_ribbon_deseret_book_wrap'>
					<a class="subnav-add-wrap" href='https://deseretbook.com/bookshelf-plus?utm_source=byuspeeches&utm_medium=online&utm_campaign=plus&utm_content=sponsorship' target="blank">
						<img src="<?php echo esc_url( get_site_url() ); ?>/wp-content/themes/standardframeworkparent/images/deseretbook_header.png">
					</a>
				</div>
		</div>
	</nav>
	<?php
}


/* BYU Ribbon Search Overlay (AJAX)
–––––––––––––––––––––––––––––––––––––––––––––––––– */
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


/* Breadcrumbs
–––––––––––––––––––––––––––––––––––––––––––––––––– */
function pgsf_get_bread_crumbs() {
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
