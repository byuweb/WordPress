<?php

/**
 * Outputs general circled icon element. Possible options are as follows.
 * array(
 * 		'disabled' => boolean, 				(default false)
 * 		'outlined' => boolean, 				(default true)
 * 		'shape' => ('circle'|'square'), 	(default 'square')
 * 		'turn_clockwise' => boolean, 		(default true, Note: false is counterclockwise)
 * 		'turn' => ('0'|'90'|'180'|'270'), 	(default '0')
 * 		'images' => array(
 * 			array(
 * 				'url' => String,
 * 				'alt' => String
 * 			),
 * 			array(
 * 				'url' => String,
 * 				'alt' => String
 * 			)
 * 		),									(default null)
 * 		'disabled_image' => array(
 * 			'url' => String,
 * 			'alt' => String
 * 		),									(default null)
 * 		'extra_classes' => String,
 * 		'tool_tip' => String 				(default null)
 * )
 *
 * @param array $options The available options to set
 * @return html of the icon
 *
 */
function get_the_icon( $options = []) {
	// Handle default settings
	if ( ! isset( $options['disabled'] ) || gettype( $options['disabled'] ) !== 'boolean' ) {
		$options['disabled'] = false;
	}
	if ( ! isset( $options['outlined'] ) || gettype( $options['outlined'] ) !== 'boolean' ) {
		$options['outlined'] = true;
	}
	if ( ! isset( $options['shape'] ) || gettype( $options['shape'] ) !== 'string' || ( $options['shape'] !== 'circle' && $options['shape'] !== 'square' ) ) {
		$options['shape'] = 'square';
	}
	if ( ! isset( $options['turn_clockwise'] ) || gettype( $options['turn_clockwise'] ) !== 'boolean' ) {
		$options['turn_clockwise'] = true;
	}
	if ( ! isset( $options['turn'] ) || gettype( $options['turn'] !== 'string' ) || ( $options['turn'] !== '0' && $options['turn'] !== '90' && $options['turn'] !== '180' && $options['turn'] !== '270' ) ) {
		$options['turn'] = '0';
	}
	if ( ! isset( $options['extra_classes'] ) || gettype( $options['extra_classes'] ) !== 'string' ) {
		$options['extra_classes'] = '';
	}
	if ( ! isset( $options['tool_tip'] ) || gettype( $options['tool_tip'] ) !== 'string' || $options['tool_tip'] === '' ) {
		$options['tool_tip'] = null;
	}

	// Handle default image settings
	if ( ! isset( $options['disabled_image'] ) || gettype( $options['disabled_image'] ) !== 'array' || ! isset( $options['disabled_image']['url'] ) ) {
		$options['disabled_image'] = null;
	}
	if ( ! isset( $options['images'] ) || gettype( $options['images'] ) !== 'array' || sizeof( $options['images'] ) === 0 ) {
		$options['images'] = null;
	}

	// Create classes string
	$icon_classes = 'icon icon--state-one js-icon ';
	if ( $options['disabled'] === true ) {
		$icon_classes .= ' icon--disabled';
	}
	if ( $options['outlined'] === true ) {
		$icon_classes .= ' icon--outlined';
	}
	if ( $options['shape'] === 'circle' ) {
		$icon_classes .= ' icon--circle';
	} else if ( $options['outlined'] === 'square' ) {
		$icon_classes .= ' icon--square';
	}
	if ( $options['turn_clockwise'] === true ) {
		$icon_classes .= ' icon--turn-clockwise';
	} else {
		$icon_classes .= ' icon--turn-cclockwise';
	}
	if ( $options['turn'] === '90' ) {
		$icon_classes .= ' icon--turn-90';
	} else if ( $options['turn'] === '180' ) {
		$icon_classes .= ' icon--turn-180';
	} else if ( $options['turn'] === '270' ) {
		$icon_classes .= ' icon--turn-270';
	}
	if ( $options['extra_classes'] !== '' ) {
		$icon_classes = $icon_classes . ' ' . $options['extra_classes'];
	}

	// Create image class string
	$disable_image_classes = 'icon__image icon__image--disabled';
	$image_classes = 'icon__image ';

	// Create the icon
	$html = '<div class="' . esc_attr( $icon_classes ) . '" >';

		// Disabled icon
		if ( $options['disabled_image'] !== null ) {
			$html .= '<img class="' . esc_attr( $disable_image_classes ) . '" src="' . esc_url( $options['disabled_image']['url'] ) . '" alt="' . esc_attr( $options['disabled_image']['alt'] ) . '">';
		}

		// Other images
		for ( $i = 1; $i <= sizeof( $options['images'] ); $i++ ) {
			$image = $options['images'][ $i - 1 ];
			if ( gettype( $image['url'] ) === 'string' ) {
				$html .= '<img class="' . esc_attr( $image_classes ) . ' icon__image' . $i . '" src="' . esc_url( $image['url'] ) . '" alt="' . esc_attr( $image['alt'] ) . '">';
			} 
		}

		// Tool Tip
		if ( null !== $options['tool_tip'] ) {
			$html .= '<div class="tool-tip tool-tip--circle-link">';
				$html .= '<span class="tool-tip__text">' . esc_html( $options['tool_tip'] ) . '</span>';
			$html .= '</div>';
		}

	$html .= '</div>';
	return $html;
}

/**
 * outputs general circled icon element.
 *
 * @param array $options The available options to set
 * @return none
 *
 */
function the_icon( $options = []) {
	echo get_the_icon( $options );
}

/**
 * outputs circled icon element with the arrow icon
 *
 * @param array $options The options set for displaying the icon
 * @return none
 *
 */
function the_icon__arrow( $options, $arrow_version, $image_alts ) {
	echo get_the_icon__arrow( $options, $arrow_version, $image_alts );
}

/**
 * outputs circled icon element with the arrow icon
 *
 * @param array $options The options set for displaying the icon
 * @return html of the icon
 *
 */
function get_the_icon__arrow( $options, $arrow_version, $image_alts = false ) {
	// Default to arrow version 1 if bad input
	if ( gettype( $arrow_version ) !== 'integer' || $arrow_version < 1 || $arrow_version > 2) {
		$arrow_version = 1;
	}

	// Defaults
	if ( gettype( $image_alts ) !== 'array' ) {
		$image_alts = array(
			'More',
			'More not Available'
		);
	}

	// Setup images
	if ( $arrow_version === 1 ) {
		$options['images'] = array(
			array(
				'url' => get_template_directory_uri() . '/images/elements/icon/icon__arrow--blue.svg',
				'alt' => $image_alts[0]
			),
			array(
				'url' => get_template_directory_uri() . '/images/elements/icon/icon__arrow--medium-light-grey.svg',
				'alt' => $image_alts[1]
			)
		);
	} else if ( $arrow_version === 2 ) {
		$options['images'] = array(
			array(
				'url' => get_template_directory_uri() . '/images/elements/icon/icon__arrow2--blue.svg',
				'alt' => $image_alts[0]
			),
			array(
				'url' => get_template_directory_uri() . '/images/elements/icon/icon__arrow2--white.svg',
				'alt' => $image_alts[1]
			)
		);
	}

	return get_the_icon( $options );
}
