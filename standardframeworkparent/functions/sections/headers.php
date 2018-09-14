<?php

function pgsf_wp_head() {
	?>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<title><?php wp_title(); ?></title>
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head();
}

function pgsf_the_byu_header() {
	?>

	<byu-header id="byu-ribbon" home-url="<?php echo esc_url( get_Site_url() ); ?>" <?php echo (esc_html( get_field( 'select_header_width', 'byu_options' ) ) == "full_width") ? "full-width" : "" ; ?>>
	<?php
	//display the requested type of header
	$header_type = get_field( 'select_type_of_header', 'byu_options' );
	if ( 'header_only' === $header_type ) {
	?>
        <span slot="site-title"><a href="<?php echo esc_url( get_Site_url() ); ?>"><?php echo esc_html( get_field( 'site_title', 'byu_options' ) ); ?></a></span>
	<?php
	} elseif ( 'header_with_sub-title' === $header_type ) {
	?>
        <span slot="site-title"><a href="<?php echo esc_url( get_Site_url() ); ?>"><?php echo esc_html( get_field( 'site_title', 'byu_options' ) ); ?></a></span>
        <span slot="site-title" class="subtitle"><a href="<?php echo esc_url( get_Site_url() ); ?>"><?php echo esc_html( get_field( 'sub-title_or_super-title_text', 'byu_options' ) ); ?></a></span>
	<?php
	} elseif ( 'header_with_super-title' === $header_type ) {
	?>
        <span slot="site-title" class="subtitle"><a href="<?php echo esc_url( get_Site_url() ); ?>"><?php echo esc_html( get_field( 'sub-title_or_super-title_text', 'byu_options' ) ); ?></a></span>
        <span slot="site-title"><a href="<?php echo esc_url( get_Site_url() ); ?>"><?php echo esc_html( get_field( 'site_title', 'byu_options' ) ); ?></a></span>
	<?php
	} elseif ( 'header_with_ italicized_sub-title' === $header_type ) {
	?>
		<span slot="site-title"><?php echo esc_html( get_field( 'site_title', 'byu_options' ) ); ?></span>
	<span slot="site-title" class="subtitle" style="font-style: italic"><?php echo esc_html( get_field( 'sub-title_or_super-title_text', 'byu_options' ) ); ?></span>
	<?php
	} elseif ( 'header_with_ italicized_super-title' === $header_type ) {
	?>
		<span slot="site-title"><?php echo esc_html( get_field( 'site_title', 'byu_options' ) ); ?></span>
	<span slot="site-title" class="subtitle" style="font-style: italic"><?php echo esc_html( get_field( 'sub-title_or_super-title_text', 'byu_options' ) ); ?></span>
	<?php
	} else {
		//This sections should never be reached but if it is, display just a site title
	?>
		<span slot="site-title"><?php echo $$header_type ;
		echo esc_html( get_field( 'site_title', 'byu_options' ) ); ?></span>
	<?php
	}
	if ( get_field( 'display_login', 'byu_options' ) ) {
		?>
		<byu-user-info slot="user">
			<?php if ( ! is_user_logged_in() ) {?>
		<a slot="login" href="<?php echo wp_login_url(); ?> ">Sign In</a>
			<?php } else {
				//get current User info
				$current_user = wp_get_current_user();
				//retrieve BYU
				$user_name = $current_user->user_firstname; ?>
		<a slot="logout" href="<?php echo wp_logout_url(); ?> ">Sign Out</a>
			<span slot="user-name"><?php echo esc_html($user_name); ?></span>
		<?php }	?>
	</byu-user-info>
			<?php
		}
		if ( get_field( 'display_search', 'byu_options' ) ) {
			pgsf_the_byu_search_page();
			?>
			<byu-search slot="search" onsearch="searchButtonTrigger">
                <form class="byu-ribbon__search" action="<?php echo esc_url( get_site_url() ); ?>/search/" method="get">
                    <input id="search-results" class="byu-ribbon__search__text-box text-area" type="text" placeholder="Search" name="q">
                    <input class="byu-ribbon__search__submit" type="submit" name="submit">
                </form>
                <script>
                    function searchButtonTrigger() {
                        jQuery(document).ready(function(){
                            $('.byu-ribbon__search__submit').click();
                        });
                    }
                </script>
			</byu-search>
			<?php
		}
		?>


		<script>
          function wpSearch(value) {
            document.querySelector('.byu-ribbon__search__submit').click();
          }
		</script>

		<?php pgsf_the_byu_navigation(); ?>

	</byu-header>
	<?php
}

function pgsf_the_byu_search_page() {
	/**
	 * A function used to programmatically create a post in WordPress. The slug, author ID, and title
	 * are defined within the context of the function.
	 *
	 * @returns -1 if the post was never created, -2 if a post with the same title exists, or the ID
	 *          of the post if successful.
	 */

	// Initialize the page ID to -1. This indicates no action has been taken.
	$post_id = -1;

	// Setup the author, slug, and title for the post
	$author_id = 1;
	$slug = 'search';
	$title = 'Search';

	// If the page doesn't already exist, then create it
	if ( null == get_page_by_title( $title ) ) {

		// Set the post ID so that we know the post was created successfully
		$post_id = wp_insert_post(
			array(
				'comment_status'	=> 'closed',
				'ping_status'		=> 'closed',
				'post_author'		=> $author_id,
				'post_name'		=> $slug,
				'post_title'		=> $title,
				'post_status'		=> 'publish',
				'post_type'		=> 'page',
			)
		);

		// Otherwise, we'll stop
	} else {

		// Arbitrarily use -2 to indicate that the page with the title already exists
		$post_id = -2;

	} // End if().

}
add_filter( 'after_setup_theme', 'pgsf_the_byu_search_page' );


function pgsf_the_byu_navigation() {

	if ( get_field( 'display_byu_header_menu', 'byu_options' ) ) { ?>
		<byu-menu slot="nav">
			<?php
            $has_ad = get_field( 'display_ad', 'byu_options' );
			if ($has_ad) {
				while ( have_rows( 'header_ad', 'byu_options' )) { the_row();
					$ad_image = get_sub_field( 'image' );
					$ad_link  = get_sub_field( 'ad_link' );
					// Output the image ?>
                    <div class="byu_ribbon_ad_wrap">
                        <a class="subnav-add-wrap" href="<?php echo esc_url( $ad_link ) ?>" target="blank">
                            <img nopin="nopin" src="<?php echo esc_url( $ad_image ); ?>">
                        </a>
                    </div>
					<?php
				}
			}
			?>
			<?php // loop through the rows of data
			while ( have_rows( 'byu_header_menu', 'byu_options' ) ) { the_row();

				// check current row layout
				if ( 'menu_link' === get_row_layout() ) {

				    // Prep link information
					$link_title = get_sub_field( 'text' );
					$link_url = '';

					// Check the link type
					if ( 'internal' === get_sub_field( 'type' ) ) {
						$link_url = get_site_url() . get_sub_field( 'relative_url' );
					} elseif ( 'external' === get_sub_field( 'type' ) ) {
						$link_url = get_sub_field( 'url' );
					}

					// Output the link ?>
					<a href=" <?php echo esc_url( $link_url ); ?>"><?php echo esc_html( $link_title ); ?></a>
					<?php
				}
				else if ('menu_hover' === get_row_layout() ) {
				    $hover_title = get_sub_field( 'text' );
				    $hover_post_type = get_sub_field( 'post_type' );
				    ?>
					<a class="pull_sub_menu" href="#"><?php echo esc_html($hover_title) ?></a>
			  		<ul class="custom_sub_menu">
			  					<?php $args = array(
						'post_type' => $hover_post_type,
						'order' => 'ASC',
					); ?>
			  					<?php $groups = new WP_Query( $args ); ?>
			  					<?php if ( $groups->have_posts() ) : ?>

						<?php while ( $groups->have_posts() ) : $groups->the_post(); ?>
							<?php if ( get_the_ID() != 1831 ) : ?>
                                <li class="sub_menu_item xlg-col-2">
                                    <a href="<?php the_permalink(); ?>" class="sub_menu_card"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'full' );} ?><div class="sub_menu_card_txt"><?php echo get_the_title(); ?></div></a>
                                </li><!-- END //// custom_sub_menu_item -->
							<?php endif; ?>

						<?php endwhile; ?>

						<?php wp_reset_postdata(); ?>
					<?php endif; ?>
                    </ul>
                <?php
                }
			}
            if ($has_ad) {
		            // Output the image ?>
                    <div class="byu_ribbon_ad_wrap">
                        <a class="subnav-add-wrap" href="<?php echo esc_url( $ad_link ) ?>" target="blank">
                            <img nopin="nopin" src="<?php echo esc_url( $ad_image ); ?>">
                        </a>
                    </div>
		            <?php
            }
			?>
		</byu-menu>
	<?php }
}
