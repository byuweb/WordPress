<?php


function pgsf_the_accordion( $items, $options = array() ) {
	echo pgsf_get_the_accordion( $items, $options );
}

function pgsf_get_the_accordion( $items, $options = array() ) {
	$accordion_classes = 'accordion';

	// Assign the correct skin class, defaults to none
	if ( 1 === $options['skin'] ) {
		$accordion_classes .= ' accordion--skin-1';
	}
	// Assign the correct selctiontype class, defaults to any
	if ( $options['select'] === 'any' ) {
		$accordion_classes .= ' accordion--select-any';
	}
	if ( $options['select'] === 'one' ) {
		$accordion_classes .= ' accordion--select-one';
	}

	// Icon options
	$icon_options = array(
		'shape' => 'circle',
		'extra_classes' => 'accordion__item__main-toggle accordion__item__toggle icon--std-two-state icon--no-click',
	);
	$icon_arrow_version = 2;

	// Begin the output of the accordion
	$html = '<div class="' . esc_attr( $accordion_classes ) . '">';

		// Output the items along with all of their respective data.
		// Note that the toggle will only be output if there is toggle data for the item.
	foreach ( $items as $item ) {
		// Begin each item and item__title-bar
		if ( '' === $item['content'] ) {
			$html .= '<div class="accordion__item accordion__no-content">';
		} else {
			$html .= '<div class="accordion__item accordion__item--closed">';
		}
		$html .= '<div class="accordion__item__title-bar">';
			// Output the correct toggle
		if ( $item['toggle'] === null ) {
			if ( 1 === $options['skin'] ) {
				$html .= get_the_icon__arrow( $icon_options, $icon_arrow_version );
			}
		} else {
			$html .= '<div class="accordion__item__main-toggle accordion__item__toggle">' . $item['toggle'] . '</div>';
		}

			// output the correct title
		if ( $item['title'] !== null  && '' !== $item['content'] ) {
			$html .= '<div class="accordion__item__title-area">' . $item['title'] . '</div>';
		} elseif ( '' === $item['content'] ) {
			$html .= '<div class="accordion__item__title-area no-content">' . $item['title'] . '</div>';
		}
			// End the item__title-bar and beging the item__content
			$html .= '</div>';
			$html .= '<div class="accordion__item__content">';
		if ( $item['content'] !== null ) {
			$html .= $item['content'];
		}
			// End the item__content and item
			$html .= '</div>';
			$html .= '</div>';
	}
	// End the accordion
	$html .= '</div>';
	return $html;
}

/**
 * This function will enqueue the basic functionality of the pgsf accordion. The intention is that this be called
 * by the child theme, and only when it is needed so as to avoid inclusion when unnecessary.
 */
function pgsf_use_accordion() {
	$theme_directory = get_template_directory_uri();
	wp_enqueue_style( 'pgsf_accordion-css', $theme_directory . '/css/tools/accordion.css' );
	wp_enqueue_script( 'pgsf_accordion-js', $theme_directory . '/js/tools/accordion.js', array( 'jquery' ), '1.0.0', true );
}

/**
 * This function will enqueue the basic functionality of the pgsf accordion skins. The desired skin must be specified.
 * The intention is that this be called by the child theme, and only when it is needed so as to avoid inclusion when
 * unnecessary. Note that while this loads the skin files, the appropriate skin class must be added to the accordion
 * element for the correct styling to occur.
 *
 * @param Integer $skinID The integer ID of the desired skin
 */
function pgsf_use_accordion_skin( $skinID ) {
	if ( in_array( 1, $skinID ) ) {
		$theme_directory = get_template_directory_uri();
		pgsf_use_icon();
		wp_enqueue_style( 'pgsf_accordion_skin-css', $theme_directory . '/css/tools/accordion-skin.css' );
		wp_enqueue_script( 'pgsf_accordion_skin-js', $theme_directory . '/js/tools/accordion-skin.js', array( 'jquery' ), '1.0.0', true );
		add_action( 'wp_head', 'pgsf_js_tool_reference_object__add_accordion_skin_1', 15 );
	}
}
