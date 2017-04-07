<?php
/**
 * This document contains the markup for various byu ribbons obtainable via function calls
 *
 */

function pgsf_get_byu_footer() {

	if ( get_field( 'custom_footer_navigation', 'parent_options' ) ) { ?>

		<?php // loop through the rows of data

			while ( have_rows( 'footer_columns', 'parent_options' ) ) { the_row();
				if ( 'column_content' === get_row_layout() ){ ?>

					<byu-footer-column>
					<span slot="header"><?php echo esc_html( get_sub_field( 'column_header' ) ); ?></span>


					<?php // check current row layout

					if ( get_sub_field('footer_column') ){
						 	$footer_column_info = get_sub_field('footer_column');
						 	$i = -1;

						while ( have_rows( 'footer_column' ) ) { the_row(); $i++;


							if ( 'social_media' === get_row_layout() ) { 
								$links = $footer_column_info[$i]["social_media_link"];
								?>
								   <byu-social-media-links>
								<?php foreach ( $links as $link ) { 

									if($link["acf_fc_layout"] === 'twitter'){ ?>
										<a slot="twitter" href="<?php esc_html( get_sub_field('twitter') ); ?>"></a>

									<?php } elseif ( $link["acf_fc_layout"] === 'facebook' ){ ?>
										<a slot="facebook" href="<?php esc_html( get_sub_field('facebook') ); ?>"></a>

									<?php } elseif ( $link["acf_fc_layout"] === 'linkedin' ){ ?>
										<a slot="linkedin" href="<?php esc_html( get_sub_field('linkedin') ); ?>"></a>

									<?php } elseif ( $link["acf_fc_layout"] === 'youtube' ) { ?>
										<a slot="youtube" href="<?php esc_html( get_sub_field('youtube') ); ?>"></a>

									<?php } elseif ( $link["acf_fc_layout"] === 'google_plus' ) { ?>
										<a slot="googleplus" href="<?php esc_html( get_sub_field('googleplus') ); ?>"></a>

									<?php } elseif ( $link["acf_fc_layout"] === 'instagram' ) { ?>
										<a slot="instagram" href="<?php esc_html( get_sub_field('instagram') ); ?>"></a>

									<?php } ?>
								<?php } ?>
							      </byu-social-media-links>
							<?php } elseif ( 'action_button' === get_row_layout() ) {

								$link_title = get_sub_field( 'button_label' );
								$link_url = get_sub_field( 'button_link' ); ?>

						    	<byu-footer-action-button>
	      							<a href="<?php echo esc_url( $link_url ); ?>" slot="actiontext"><?php echo esc_html( $link_title ); ?></a>
								</byu-footer-action-button>

							<?php } elseif ( 'link' === get_row_layout() ) {

									$link_title = get_sub_field( 'link_text' );
									$link_url = get_sub_field( 'link_url' ); ?>

									<a href=" <?php echo esc_url( $link_url ); ?>"><?php echo esc_html( $link_title ); ?></a>
									<br />
						
							<?php } elseif ( 'info_text' === get_row_layout() ) {

									$link_title = 'Info Text';
									$link_url = 'https://google.com'; ?>

									<p><?php echo get_sub_field( 'text' ); ?></p>
						
							<?php } 
						}
					} ?>

					</byu-footer-column>
				<?php } 
			}?>


	<?php } 

} 







function pgsf_get_byu_footer__col_4__follow() {

	$social_media_choices = array(
		'organization' => 'BYU',
		'facebook' => array(
			'display' => false,
			'follow_link' => '',
		),
		'twitter' => array(
			'display' => false,
			'follow_link' => '',
		),
		'youtube' => array(
			'display' => false,
			'follow_link' => '',
		),
		'pinterest' => array(
			'display' => false,
			'follow_link' => '',
		),
		'instagram' => array(
			'display' => false,
			'follow_link' => '',
		),
		'google-plus' => array(
			'display' => false,
			'follow_link' => '',
		),
		'linkedin' => array(
			'display' => false,
			'follow_link' => '',
		),
		'reddit' => array(
			'display' => false,
			'follow_link' => '',
		),
		'tumblr' => array(
			'display' => false,
			'follow_link' => '',
		),
		'podcast' => array(
			'display' => false,
			'follow_link' => '',
		),
		'rss' => array(
			'display' => false,
			'follow_link' => '',
		),
	);

	if ( is_front_page() || ! has_filter( 'pgsf_byu-footer__col-4__follow' ) ) {
		$social_media_choices = array(
			'organization' => 'BYU Speeches',
			'facebook' => array(
				'display' => true,
				'follow_link' => 'https://www.facebook.com/byuspeeches/',
			),
			'twitter' => array(
				'display' => true,
				'follow_link' => 'https://twitter.com/BYUspeeches/',
			),
			'pinterest' => array(
				'display' => true,
				'follow_link' => 'https://www.pinterest.com/byuspeeches/',
			),
			'instagram' => array(
				'display' => true,
				'follow_link' => 'https://www.instagram.com/byuspeeches/',
			),
		);
	} else {
		$social_media_choices = apply_filters( 'pgsf_byu-footer__col-4__follow', $social_media_choices );
	}?>

	<span id="byu-footer__extra-link__follow" class="byu-footer__extra-link no-link">Follow <?php echo esc_html( $social_media_choices['organization'] ); ?></span>
	<ul class='social-media__icons'>
		<?php
		if ( isset( $social_media_choices['facebook'] ) && $social_media_choices['facebook']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['facebook']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>'; }
		if ( isset( $social_media_choices['twitter'] ) && $social_media_choices['twitter']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['twitter']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>'; }
		if ( isset( $social_media_choices['youtube'] ) && $social_media_choices['youtube']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['youtube']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>'; }
		if ( isset( $social_media_choices['pinterest'] ) && $social_media_choices['pinterest']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['pinterest']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>'; }
		if ( isset( $social_media_choices['instagram'] ) && $social_media_choices['instagram']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['instagram']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>'; }
		if ( isset( $social_media_choices['google-plus'] ) && $social_media_choices['google-plus']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['google-plus']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>'; }
		if ( isset( $social_media_choices['reddit'] ) && $social_media_choices['reddit']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['reddit']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-reddit" aria-hidden="true"></i></a></li>'; }
		if ( isset( $social_media_choices['tumblr'] ) && $social_media_choices['tumblr']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['tumblr']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-tumblr" aria-hidden="true"></i></a></li>'; }
		if ( isset( $social_media_choices['linkedin'] ) && $social_media_choices['linkedin']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['linkedin']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>'; }
		if ( isset( $social_media_choices['podcast'] ) && $social_media_choices['podcast']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['podcast']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-podcast" aria-hidden="true"></i></a></li>'; }
		if ( isset( $social_media_choices['rss'] ) && $social_media_choices['rss']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['rss']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-rss" aria-hidden="true"></i></a></li>'; }
		?>
	</ul>
	<?php
}


function pgsf_get_byu_footer__col_4__subscribe() {

	$social_media_choices = array(
		'organization' => 'BYU',
		'facebook' => array(
			'display' => false,
			'follow_link' => '',
		),
		'twitter' => array(
			'display' => false,
			'follow_link' => '',
		),
		'youtube' => array(
			'display' => false,
			'follow_link' => '',
		),
		'pinterest' => array(
			'display' => false,
			'follow_link' => '',
		),
		'instagram' => array(
			'display' => false,
			'follow_link' => '',
		),
		'google-plus' => array(
			'display' => false,
			'follow_link' => '',
		),
		'linkedin' => array(
			'display' => false,
			'follow_link' => '',
		),
		'reddit' => array(
			'display' => false,
			'follow_link' => '',
		),
		'tumblr' => array(
			'display' => false,
			'follow_link' => '',
		),
		'podcast' => array(
			'display' => false,
			'follow_link' => '',
		),
		'rss' => array(
			'display' => false,
			'follow_link' => '',
		),
	);

	if ( is_front_page() || ! has_filter( 'pgsf_byu-footer__col-4__subscribe' ) ) {
		$social_media_choices = array(
			'youtube' => array(
				'display' => true,
				'follow_link' => 'https://www.youtube.com/user/BYUSpeeches',
			),
			'podcast' => array(
				'display' => true,
				'follow_link' => 'https://itunes.apple.com/us/podcast/byu-speeches/id993043203?mt=2',
			),
			'rss' => array(
				'display' => true,
				'follow_link' => 'https://speeches.byu.edu/talks/feed/byu-speeches/',
			),
		);
	} else {
		$social_media_choices = apply_filters( 'pgsf_byu-footer__col-4__subscribe', $social_media_choices );
	}?>

	<span id="byu-footer__extra-link__subscribe" class="byu-footer__extra-link no-link">Subscribe</span>
	<ul class='social-media__icons'>
		<?php
		if ( isset( $social_media_choices['facebook'] ) && $social_media_choices['facebook']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['facebook']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-facebook" aria-hidden="true"><div class="tool-tip"><span class="tool-tip__text">Share</span></div></i></a></li>'; }
		if ( isset( $social_media_choices['twitter'] ) && $social_media_choices['twitter']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['twitter']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>'; }
		if ( isset( $social_media_choices['youtube'] ) && $social_media_choices['youtube']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['youtube']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>'; }
		if ( isset( $social_media_choices['pinterest'] ) && $social_media_choices['pinterest']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['pinterest']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>'; }
		if ( isset( $social_media_choices['instagram'] ) && $social_media_choices['instagram']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['instagram']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>'; }
		if ( isset( $social_media_choices['google-plus'] ) && $social_media_choices['google-plus']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['google-plus']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>'; }
		if ( isset( $social_media_choices['reddit'] ) && $social_media_choices['reddit']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['reddit']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-reddit" aria-hidden="true"></i></a></li>'; }
		if ( isset( $social_media_choices['tumblr'] ) && $social_media_choices['tumblr']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['tumblr']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-tumblr" aria-hidden="true"></i></a></li>'; }
		if ( isset( $social_media_choices['linkedin'] ) && $social_media_choices['linkedin']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['linkedin']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>'; }
		if ( isset( $social_media_choices['podcast'] ) && $social_media_choices['podcast']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['podcast']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-podcast" aria-hidden="true"><div class="tool-tip tool-tip--white"><span class="tool-tip__text">Podcasts</span></div></i></a></li>'; }
		if ( isset( $social_media_choices['rss'] ) && $social_media_choices['rss']['display'] ) { echo '<li><a href="' . esc_url( $social_media_choices['rss']['follow_link'] ) . '" target="new" rel="external"><i class="fa fa-rss" aria-hidden="true"><div class="tool-tip tool-tip--white"><span class="tool-tip__text">RSS&#160;Feed</span></div></i></a></li>'; }
		?>
	</ul>

	<?php
}
















