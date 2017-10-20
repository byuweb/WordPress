<?php

function pgsf_the_card( $items, $options = array() ) {
	echo pgsf_get_the_card( $items, $options );
}


function pgsf_get_the_card( $items, $options = array() ) {
	$card_classes = 'card';
	// Assign the correct skin class, defaults to none
	error_log(print_r($options, true));
	if ( 1 === $options['skin'] ) {
		$card_skin = ' card--skin-1';
	}
	if( $options['width'] ){
		$width = ' ' . $options['width'];
		$card_classes .= $width;
	}
	if( $options['border'] ){
		$border = ' ' . 'border';
	}

	// Begin the output of the card

	$html = '';

	foreach ( $items as $item ) {
		$html .= '<div class="' . esc_attr( $card_classes ) . '">';
		if($options['border']){ $html .= '<div class="card__inner card__inner--border' .  $card_skin .'">'; }
		else{ $html .= '<div class="card__inner' .  $card_skin .'">';  }
		$html .= '<a class="card__inner-link' .  $card_skin .'" href="' . $item['link'] . '"></a>';
		// Begin each item and item__title-bar
		if ( $item['image-url'] ) {
			$html .= '<div class="card__inner--image-wrap' .  $card_skin .'">';
			$html .= '<a class="card__inner--image-wrap-link' .  $card_skin .'" href="' . esc_attr( $item['link'] ) . '">';
			$html .= '<img class="card__inner--image' .  $card_skin .'" src ="' . $item['image-url'] . '" ></a>';
			$html .= '</div>';
		}
		$html .= '<div class="card__inner--meta' .  $card_skin .'">';
		if ( $item['title'] ) {
			$html .= '<h3 class="card__inner--meta card__inner--meta-title' .  $card_skin .'">';
			$html .= '<a href="' . $item['link'] . '">' . $item['title'] . '</a></h3>';
		}
		if ($item['author']) {
			if( $item['author-link']){
				$html .= '<a href="' . $item['author-link'] . '" class="card__inner--meta card__inner--meta-author' .  $card_skin .'">' . $item['author'] . '</a>';
			} else{
				$html .= '<span class="card__inner--meta card__inner--meta-author' .  $card_skin .'">' . $item['author'] . '</span>';
			}
		}
		if( $item['subtext'] ){
			$html .= '<p class="card__inner--meta card__inner--meta-subtext' .  $card_skin .'">' . $item['subtext'] . '</p>';
		}

		// End card Meta
		$html .= '</div>';
		// End card Inner
		$html .= '</div>';
		// End the card
		$html .= '</div>';
	}
	return $html;
}

/**
 * This function will enqueue the basic functionality of the pgsf card. The intention is that this be called
 * by the child theme, and only when it is needed so as to avoid inclusion when unnecessary.
 */
function pgsf_use_card() {
	$theme_directory = get_template_directory_uri();
	wp_enqueue_style( 'PGSF_card-css', 			$theme_directory . '/css/tools/card.css' );
}

/**
 * This function will enqueue the basic functionality of the pgsf card skins. The desired skin must be specified.
 * The intention is that this be called by the child theme, and only when it is needed so as to avoid inclusion when
 * unnecessary. Note that while this loads the skin files, the appropriate skin class must be added to the card
 * element for the correct styling to occur.
 *
 * @param Integer $skinID The integer ID of the desired skin
 */
function pgsf_use_card_skin( $skinID ) {
	if ( in_array( 1, $skinID ) ) {
		$theme_directory = get_template_directory_uri();
		wp_enqueue_style( 'PGSF_card-skin-css', 			$theme_directory . '/css/tools/card-skin.css' );
	}
}
