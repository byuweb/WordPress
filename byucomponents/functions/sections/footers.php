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
		if ( have_rows( 'column_rows' ) ) {

			while ( have_rows( 'column_rows' ) ) {
				$row_info = the_row();

				if ( 'social_media' === get_row_layout() ) {

					$links_info = $row_info[ array_keys( $row_info )[1] ];
					pgsf_the_byu_footer__column__social_media( $links_info );

				} elseif ( 'action_button' === get_row_layout() ) {

					pgsf_the_byu_footer__column__action_button();

				} elseif ( 'link' === get_row_layout() ) {

					pgsf_the_byu_footer__column__link();

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
			$url = get_sub_field( 'url' );

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
			} ?>
			<a slot="<?php echo esc_attr( $slot ); ?>" href="<?php echo esc_attr( $url ); ?>"></a>
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
	if ( 'internal' === get_sub_field( 'type' ) ) {
		$link_url = get_site_url() . get_sub_field( 'relative_url' );
	} elseif ( 'external' === get_sub_field( 'type' ) ) {
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
	if ( 'internal' === get_sub_field( 'type' ) ) {
		$link_url = get_site_url() . get_sub_field( 'relative_url' );
	} elseif ( 'external' === get_sub_field( 'type' ) ) {
		$link_url = get_sub_field( 'url' );
	} ?>

	<a href=" <?php echo esc_url( $link_url ); ?>"><?php echo esc_html( $link_title ); ?></a>
	<br>

	<?php
}
function pgsf_the_byu_footer__column__info_text() { ?>

	<p><?php echo get_sub_field( 'text' ); ?></p>

	<?php
}
