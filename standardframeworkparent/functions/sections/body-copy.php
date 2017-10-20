<?php
/* Table of Contents
––––––––––––––––––––––––––––––––––––––––––––––––––
- handle_body_copy_defaults()
- the_body_copy()
- get_body_copy()
- the_body_copy_with_extras()
- get_body_copy_with_extras()
- the_body_copy_section()
- get_body_copy_section()
- prep_body_copy_for_extra_insertion()
- include_extra_body_cody_features()
*/

/**
 * Sets all defaults of the array expected by the 'get' 'body_copy' family of functions
 *
 * @param {Array} $args The options for retrieving the body copy.
 *
 * i.e.
 * $args = array(
 * 		'is_sub_field' => bool (Defaults to false)
 * );
 * 
 * @return {Array}
 */
function handle_body_copy_defaults( $args = array() ) {
	if ( ! isset( $args['is_sub_field'] ) ) {
		$args['is_sub_field'] = false;
	}

	return $args;
}

/**
 * Outputs the body copy as saved in the Body Copy tool.
 *
 * @param {Array} $args The options for retrieving the body copy.
 */
function the_body_copy( $args = array() ) {
	echo get_body_copy( $args );
}

/**
 * Returns the body copy as saved in the Body Copy tool.
 *
 * @param {Array} $args The options for retrieving the body copy.
 * @return {String|Bool(false)} The Body Copy or false if not attainable.
 */
function get_body_copy( $args = array() ) {
	$args = handle_body_copy_defaults( $args );

	if ( false === $args['is_sub_field'] ) {
		if ( 'html' === get_field( 'body_copy_entry_type' ) ) {
			return get_field( 'html_body_copy' );
		} elseif ( 'wysiwyg' === get_field( 'body_copy_entry_type' ) || null === get_field( 'body_copy_entry_type' ) ) {
			return get_field( 'wysiwyg_body_copy' );
		}
	} else {
		if ( 'html' === get_sub_field( 'body_copy_entry_type' ) ) {
			return get_sub_field( 'html_body_copy' );
		} elseif ( 'wysiwyg' === get_sub_field( 'body_copy_entry_type' ) || null === get_sub_field( 'body_copy_entry_type' ) ) {
			return get_sub_field( 'wysiwyg_body_copy' );
		}
	}

	return false;
}

/**
 * Outputs the body copy with associated 'extras' as saved in the Body Copy tool.
 *
 * @param {Array} $args The options for retrieving the body copy.
 */
function the_body_copy_with_extras( $args = array() ) {
	echo get_body_copy_with_extras( $args );
}


/**
 * Returns the body copy with associated 'extras' as saved in the Body Copy tool.
 *
 * @param {Array} $args The options for retrieving the body copy.
 * @return {String|Bool(false)} The Body Copy or false if not attainable.
 */
function get_body_copy_with_extras( $args = array() ) {
	$args = handle_body_copy_defaults( $args );

	$body_copy = get_body_copy( $args );

	// If bad, bail
	if ( false === $body_copy ) {
		return false;
	}

	// Retrieve the body_copy_style_type
	$body_copy_style_type = '';
	if ( false === $args['is_sub_field'] ) {
		if ( null === get_field( 'body_copy_style_type' ) ) {
			$body_copy_style_type = 'standard_center';
		} else {
			$body_copy_style_type = get_field( 'body_copy_style_type' );
		}
	} else {
		if ( null === get_sub_field( 'body_copy_style_type' ) ) {
			$body_copy_style_type = 'standard_center';
		} else {
			$body_copy_style_type = get_sub_field( 'body_copy_style_type' );
		}
	}

	// retrieve a formatted body_copy_array
	$body_copy_array = prep_body_copy_for_extra_insertion( $body_copy );

    // Check if there are 'extras' that are to be placed within the body copy
	$body_copy_array = include_extra_body_cody_features( $body_copy_array, $body_copy_style_type );

	// TODO -- add a filter here allowing modification in the child theme

	// Stringify the body_copy_array
	$body_copy_with_extras = stringify_body_copy_with_extras( $body_copy_array );

	return $body_copy_with_extras; 
}

/**
 * Outputs the section of body copy.
 *
 * @param {Array} $args The options for retrieving the body copy.
 */
function the_body_copy_section( $args = array() ) {
	echo get_body_copy_section( $args );
}

/**
 * Retrieves the section of body copy html.
 *
 * @param {Array} $args The options for retrieving the body copy.
 * @return {String|Bool(false)} The Body Copy Section or false if not attainable.
 */
function get_body_copy_section( $args = array() ) {
	$args = handle_body_copy_defaults( $args );

	$body_copy_with_extras = get_body_copy_with_extras( $args );

	// If bad, bail
	if ( false === $body_copy_with_extras ) {
		return false;
	}

	// Prepare beginnings and ends to the section
	$start_section = '<section><div class="container">';
	$start_body_copy_wrap = '<div class="body-copy__wrap standard-body-copy xlg-col-8 xlg-offset-left-col-2 xlg-offset-right-col-2 
		lg-col-8 lg-offset-left-col-2 lg-offset-right-col-2 
		mlg-col-4 mlg-offset-left-col-1 mlg-offset-right-col-1
		med-col-6
		sm-col-4
		ssm-col-4">';

	$end_body_copy_wrap = '</div>';
	$end_section = '</div></section>';

	// Connect the section together and return it
	$whole_body_copy = $start_section . $start_body_copy_wrap . $body_copy_with_extras . $end_body_copy_wrap . $end_section;

	return $whole_body_copy;
}

/**
 * Preps Body Copy into array form for adding extras in their designated locations.
 * 
 * @param {String} $text_string The Body Copy
 * @return {Array[Integer][Map]} The Body Copy separated by newlines
 */
function prep_body_copy_for_extra_insertion( $text_string ) {

	// Separate paragraphs from paragraphs
    $body_copy = str_replace( "</p>\n<p>", "</p>\r\n<p>", $text_string );

    while ( strpos( $body_copy, "</p>\n<p>" ) ) {
        $body_copy = str_replace( "</p>\n<p>", "</p>\r\n<p>", $body_copy );
    }

    // Take care of some header possibilities
    // TODO -- improve this with regex?
	$tags = array('p','h1','h2','h3','h4','h5','h6','ul','ol');
    foreach ( $tags as $tag1 ) {
    	foreach ( $tags as $tag2 ) {
		    while ( strpos( $body_copy, "</{$tag1}>\n<{$tag2}" ) ) {
			    $body_copy = str_replace( "</{$tag1}>\n<{$tag2}", "</{$tag1}>\r\n<{$tag2}", $body_copy );
		    }
	    }
    }

	// grab the text for the body copy and parse it into an array getting rid of extra spaces and placing paragraphs within <p> tags
    $body_copy = str_replace( "\r\n\r\n", "\r\n", $body_copy );
    while ( strpos( $body_copy, "\r\n\r\n" ) ) {
		$body_copy = str_replace( "\r\n\r\n", "\r\n", $body_copy );
	}
    $body_copy = explode( "\r\n", $body_copy );
    $body_copy_array = array();
    for ( $it = 0; $it < sizeof( $body_copy ); $it++ ) {
        $body_copy_array[$it]['paragraph_text'] = $body_copy[$it];
        $body_copy_array[$it]['extras'] = array();
    }

    // shift all paragraphs 1 and make room for extras added before any text
    array_unshift( $body_copy_array, array(
    	'paragraph_text' => '',
    	'extras' => array()
    ) );
    return $body_copy_array;
}

/**
 *
 */
function include_extra_body_cody_features( $body_copy_array, $body_copy_style_type ) {

	// Loop through to add every body_extra
    while ( have_rows('body_extras') ) { the_row();

	    $vertical_position = get_sub_field('vertical_position');
	    $starting_classes = get_extra_context_classes( $body_copy_array, $vertical_position + 1);

	    // Normalize the location to the last element in the array
	    if ( $vertical_position > sizeof( $body_copy_array ) ) {
	        $vertical_position = sizeof( $body_copy_array );
	    }

    	$extra = array();

    	if ( 'image' === get_row_layout() ) {
	        $extra['extra_type'] = 'image';
	        $extra['image'] = get_sub_field( 'image' );
	        $extra['classes'] = '';
	        $starting_classes .= ' xlg-no-padding lg-no-padding mlg-no-padding med-no-padding sm-no-padding ssm-no-padding';

	        $extra['classes'] = get_body_extra_classes( $body_copy_style_type, $starting_classes );

    	} elseif ( 'youtube_video' === get_row_layout() ) {
		    $extra['extra_type'] = 'youtube_video';
		    $extra['video_code'] = get_sub_field( 'youtube_video_code' );
		    $extra['classes'] = '';
		    $starting_classes .= ' xlg-no-padding lg-no-padding mlg-no-padding med-no-padding sm-no-padding ssm-no-padding';

		    $extra['classes'] = get_body_extra_classes( $body_copy_style_type, $starting_classes );
	    } elseif ( 'code_block' === get_row_layout() ) {
		    $extra['extra_type'] = 'code_block';
		    $extra['code'] = get_code_block_inners( get_sub_field( 'code_block_markup' ) );
		    $extra['classes'] = '';
		    $starting_classes .= ' xlg-no-padding lg-no-padding mlg-no-padding med-no-padding sm-no-padding ssm-no-padding';

		    $extra['classes'] = get_body_extra_classes( $body_copy_style_type, $starting_classes );
	    } elseif ( 'pull_quote' === get_row_layout() ) {
		    $extra['extra_type'] = 'pull_quote';
		    $extra['quote_text'] = get_sub_field('quote_text');
		    $extra['quote_author'] = get_sub_field('quote_author');
		    $extra['classes'] = '';
		    $starting_classes .= ' xlg-no-padding lg-no-padding mlg-no-padding med-no-padding sm-no-padding ssm-no-padding';

		    $extra['classes'] = get_body_extra_classes( $body_copy_style_type, $starting_classes );
	    }

	    // Prepare and return the body_copy_array with the new extra information added to the structure
	    if ( ! isset( $body_copy_array[$vertical_position] ) ) {
	    	$body_copy_array[$vertical_position] = array();
	    }
	    if ( ! isset( $body_copy_array[$vertical_position]['extras'] ) ) {
	    	$body_copy_array[$vertical_position]['extras'] = array();
	    }

	    array_push( $body_copy_array[$vertical_position]['extras'], $extra );
    }

    return $body_copy_array;
}

function get_code_block_inners( $code ) {
	$inners = '';

	// Normalize the line spacings
	$code_lines = explode( "\r\n", $code );

	// Generate markup for lines numbers and code lines
	$line_numbers_markup = '';
	$code_lines_markup = '';
	$line_number = 1;
	foreach ( $code_lines as $code_line ) {
		if ( "" === $code_line ) {
			$code_line = "&nbsp;";
		}

		// line numbers markup
		$line_numbers_markup .= '<span class="line-' . $line_number . '">' . $line_number . '</span>';

		// code lines markup
		$spaces_to_add = "";
		while ( 0 === strpos( $code_line, " " ) || 0 === strpos( $code_line, "\t" ) ) {
			if ( 0 === strpos( $code_line, " " ) ) {
				$spaces_to_add .= "&nbsp;";
				$code_line = substr( $code_line, 1 );
			}
			if ( 0 === strpos( $code_line, "\t" ) ) {
				$spaces_to_add .= "&nbsp;&nbsp;&nbsp;&nbsp;";
				$code_line = substr( $code_line, 1 );
			}
		}

		$code_lines_markup .= '<span class="line-' . $line_number . '" data-number="' . $line_number . '">' . $spaces_to_add . esc_html( $code_line ) . '</span>';

		// update line number
		$line_number++;
	}

	// Combine markup and return it
	$inners = '<div class="code__line-numbers">' . $line_numbers_markup . '</div><div class="code__code-lines">' . $code_lines_markup . '</div>';

	return $inners;
}

function get_extra_context_classes( $body_copy_array, $element_id_to_align_with ) {
	$class = '';

	// Verify that the variables to accessed are set and of the correct type
	if ( isset( $body_copy_array[$element_id_to_align_with] ) && isset( $body_copy_array[$element_id_to_align_with]['paragraph_text'] ) ) {
		// Check the Paragraph after
		$paragraph_after = $body_copy_array[$element_id_to_align_with]['paragraph_text'];
		if ( is_string( $paragraph_after ) ) {
			// Determine the type of the element after the extra element
			if ( 0 === stripos($paragraph_after, "<h1") ) {
				$class .= ' extra-with-h1';
			}
			if ( 0 === stripos($paragraph_after, "<h2") ) {
				$class .= ' extra-with-h2';
			}
			if ( 0 === stripos($paragraph_after, "<h3") ) {
				$class .= ' extra-with-h3';
			}
			if ( 0 === stripos($paragraph_after, "<h4") ) {
				$class .= ' extra-with-h4';
			}
			if ( 0 === stripos($paragraph_after, "<h5") ) {
				$class .= ' extra-with-h5';
			}
			if ( 0 === stripos($paragraph_after, "<h6") ) {
				$class .= ' extra-with-h6';
			}
			if ( 0 === stripos($paragraph_after, "<p") ) {
				$class .= ' extra-with-p';
			}
		}
	}

	// Verify that the variables to accessed are set and of the correct type
	if ( isset( $body_copy_array[$element_id_to_align_with - 1] ) && isset( $body_copy_array[$element_id_to_align_with - 1]['paragraph_text'] ) ) {
		// Check the Paragraph after
		$paragraph_before = $body_copy_array[$element_id_to_align_with - 1]['paragraph_text'];
		if ( is_string( $paragraph_before ) ) {
			// Determine the type of the element after the extra element
			if ( 0 === stripos($paragraph_before, "<h1") ) {
				$class .= ' extra-after-h1';
			}
			if ( 0 === stripos($paragraph_before, "<h2") ) {
				$class .= ' extra-after-h2';
			}
			if ( 0 === stripos($paragraph_before, "<h3") ) {
				$class .= ' extra-after-h3';
			}
			if ( 0 === stripos($paragraph_before, "<h4") ) {
				$class .= ' extra-after-h4';
			}
			if ( 0 === stripos($paragraph_before, "<h5") ) {
				$class .= ' extra-after-h5';
			}
			if ( 0 === stripos($paragraph_before, "<h6") ) {
				$class .= ' extra-after-h6';
			}
			if ( 0 === stripos($paragraph_before, "<p") ) {
				$class .= ' extra-after-p';
			}
		}
	}

	return $class;
}

/**
 *
 */
function stringify_body_copy_with_extras( $body_copy_array_paragraphs ) {
	$string = '';

	// Iterate through all the paragraphs
	foreach ( $body_copy_array_paragraphs as $paragraph ) {
		$string .= $paragraph['paragraph_text'];

		// Iterate through all the extras assigned to come after the paragraph
		foreach ( $paragraph['extras'] as $extra ) {

			if ( 'image' === $extra['extra_type'] ) {
				$string .= '<figure class="' . $extra['classes'] . '">' . '<img src="' . $extra['image']['url'] . '" alt="' . $extra['image']['url'] . '"><figcaption>' . $extra['image']['caption'] . '</figcaption></figure>';
			}

			if ( 'youtube_video' === $extra['extra_type'] ) {
				$string .= '<iframe class="is_youtube_video ' . $extra['classes'] . '" src="https://www.youtube.com/embed/' . $extra['video_code'] . '" frameborder="0" allowfullscreen></iframe>';
			}

			if ( 'code_block' === $extra['extra_type'] ) {
				$string .= '<code class="' . $extra['classes'] . '" >' . $extra['code'] . '</code>';
			}

			if ( 'pull_quote' === $extra['extra_type'] ) {
				$string .= '<article class="pull-quote ' . $extra['classes'] . '" ><div class="pull-quote__inner-wrap"><q>' . $extra['quote_text'] . '</q><span>' . $extra['quote_author'] . '</span></div></article>';
			}

		}
	}

	return $string;
}

/**
 *
 */
function get_body_extra_classes( $body_copy_style_type, $classes = '' ) {
	$classes .= ' body-copy-extra';

	if ( 'standard_center' === $body_copy_style_type ) {

		$position = get_sub_field('formatting_method');
		$found_format_method = false;
		$preset_choice = '';

		// set the preset choice according to the formatting method
		if ( 'preset' === get_sub_field('formatting_method') || null === get_sub_field('formatting_method') ) {
			$found_format_method = true;
			$preset_choice = get_sub_field( 'standard_center_presets' );
		}

		if ( 'preset_extended' === get_sub_field('formatting_method') ) {
			$found_format_method = true;
			$preset_choice = get_sub_field( 'standard_center_presets_extended' );
		}

		// Handle the Presets
		if ( true === $found_format_method ) {

			if ( 'text_width' === $preset_choice ) {
				$classes .= ' xlg-no-float xlg-parent-width
					lg-no-float lg-parent-width
					mlg-no-float mlg-parent-width
					med-no-float med-parent-width
					sm-no-float sm-parent-width
					ssm-no-float ssm-parent-width';
			} elseif ( 'large_inset_right' === $preset_choice ) {
				$classes .= ' xlg-offset-left-col-0-gutter-1 xlg-offset-right xlg-col-5-gutter-1-inner-1 
					lg-offset-left-col-0-gutter-1 lg-offset-right lg-col-5-gutter-1-inner-1 
					mlg-offset-left-col-0-gutter-1 mlg-offset-right mlg-col-2-gutter-1-inner-1 
					med-offset-left-col-0-gutter-1 med-offset-right med-col-3-gutter-1-inner-1 
					sm-offset-left-col-0-gutter-1 sm-col-2-gutter-1-inner-1 
					ssm-no-float ssm-parent-width';
			} elseif ( 'large_outset_right_1' === $preset_choice ) {
				$classes .= ' xlg-offset-left-col-0-gutter-1 xlg-reverse-offset-right-col-1-gutter-1 xlg-col-6 
					lg-offset-left-col-0-gutter-1 lg-reverse-offset-right-col-1-gutter-1 lg-col-6 
					mlg-offset-left-col-0-gutter-1 mlg-reverse-offset-right-col-1 mlg-col-2-gutter-1-inner-1 
					med-offset-left-col-0-gutter-1 med-offset-right med-col-3-gutter-1-inner-1 
					sm-offset-left-col-0-gutter-1 sm-col-2-gutter-1-inner-1 
					ssm-no-float ssm-parent-width';
			} elseif ( 'large_outset_right_2' === $preset_choice ) {
				$classes .= ' xlg-offset-left-col-0-gutter-1 xlg-reverse-offset-right-col-2-gutter-1 xlg-col-6 
					lg-offset-left-col-0-gutter-1 lg-reverse-offset-right-col-2-gutter-1 lg-col-6
					mlg-offset-left-col-0-gutter-1 mlg-reverse-offset-right-col-1 mlg-col-2-gutter-1-inner-1 
					med-offset-left-col-0-gutter-1 med-offset-right med-col-3-gutter-1-inner-1 
					sm-offset-left-col-0-gutter-1 sm-col-2-gutter-1-inner-1 
					ssm-no-float ssm-parent-width';
			} elseif ( 'large_inset_left' === $preset_choice ) {
				$classes .= ' xlg-offset-right-col-0-gutter-1 xlg-offset-right-to-margin xlg-col-5-gutter-1-inner-1 
					lg-offset-right-col-0-gutter-1 lg-offset-right-to-margin lg-col-5-gutter-1-inner-1 
					mlg-offset-right-col-0-gutter-1 mlg-offset-right-to-margin mlg-col-2-gutter-1-inner-1 
					med-offset-right-col-0-gutter-1 med-offset-right-to-margin med-col-3-gutter-1-inner-1 
					sm-offset-right-col-0-gutter-1 sm-offset-right-to-margin sm-col-2-gutter-1-inner-1 
					ssm-no-float ssm-parent-width';
			} elseif ( 'large_outset_left_1' === $preset_choice ) {
				$classes .= ' xlg-offset-right-col-0-gutter-1 xlg-reverse-offset-left-col-1-gutter-1 xlg-offset-right-to-margin xlg-col-6 
					lg-offset-right-col-0-gutter-1 lg-offset-right-to-margin lg-reverse-offset-left-col-1-gutter-1 lg-col-6
					mlg-offset-right-col-0-gutter-1 mlg-offset-right-to-margin mlg-reverse-offset-left-col-1 mlg-col-2-gutter-1-inner-1 
					med-offset-right-col-0-gutter-1 med-offset-right-to-margin med-offset-left med-col-3-gutter-1-inner-1 
					sm-offset-right-col-0-gutter-1 sm-offset-right-to-margin sm-col-2-gutter-1-inner-1 
					ssm-no-float ssm-parent-width';
			} elseif ( 'large_outset_left_2' === $preset_choice ) {
				$classes .= ' xlg-offset-right-col-0-gutter-1 xlg-reverse-offset-left-col-2-gutter-1 xlg-offset-right-to-margin  xlg-col-6 
					lg-offset-right-col-0-gutter-1 lg-offset-right-to-margin lg-reverse-offset-left-col-2-gutter-1 lg-col-6
					mlg-offset-right-col-0-gutter-1 mlg-offset-right-to-margin mlg-reverse-offset-left-col-1 mlg-col-2-gutter-1-inner-1 
					med-offset-right-col-0-gutter-1 med-offset-right-to-margin med-offset-left med-col-3-gutter-1-inner-1 
					sm-offset-right-col-0-gutter-1 sm-offset-right-to-margin sm-col-2-gutter-1-inner-1 
					ssm-no-float ssm-parent-width';
			} elseif ( 'medium_inset_right' === $preset_choice ) {
				$classes .= ' xlg-offset-left-col-0-gutter-1 xlg-offset-right xlg-col-3-gutter-1-inner-1
					lg-offset-left-col-0-gutter-1 lg-offset-right lg-col-3-gutter-1-inner-1
					mlg-offset-left-col-0-gutter-1 mlg-offset-right mlg-col-1-gutter-1-inner-1
					med-offset-left-col-0-gutter-1 med-offset-right med-col-2-gutter-1-inner-1
					sm-offset-left-col-0-gutter-1 sm-col-1-gutter-1-inner-1
					ssm-no-float ssm-parent-width';
			} elseif ( 'medium_outset_right_1' === $preset_choice ) {
				$classes .= ' xlg-offset-left-col-0-gutter-1 xlg-reverse-offset-right-col-1-gutter-1 xlg-col-4
					lg-offset-left-col-0-gutter-1 lg-reverse-offset-right-col-1-gutter-1 lg-col-4
					mlg-offset-left-col-0-gutter-1 mlg-reverse-offset-right-col-1 mlg-col-1-gutter-1-inner-1
					med-offset-left-col-0-gutter-1 med-reverse-offset-right med-col-2-gutter-1-inner-1
					sm-offset-left-col-0-gutter-1 sm-col-1-gutter-1-inner-1
					ssm-no-float ssm-parent-width';
			} elseif ( 'medium_outset_right_2' === $preset_choice ) {
				$classes .= ' xlg-offset-left-col-0-gutter-1 xlg-reverse-offset-right-col-2-gutter-1 xlg-col-4
					lg-offset-left-col-0-gutter-1 lg-reverse-offset-right-col-2-gutter-1 lg-col-4
					mlg-offset-left-col-0-gutter-1 mlg-reverse-offset-right-col-1 mlg-col-1-gutter-1-inner-1
					med-offset-left-col-0-gutter-1 med-reverse-offset-right med-col-2-gutter-1-inner-1
					sm-offset-left-col-0-gutter-1 sm-col-1-gutter-1-inner-1
					ssm-no-float ssm-parent-width';
			} elseif ( 'medium_inset_left' === $preset_choice ) {
				$classes .= ' xlg-offset-right-col-0-gutter-1 xlg-offset-right-to-margin xlg-col-3-gutter-1-inner-1
					lg-offset-right-col-0-gutter-1 lg-offset-right-to-margin lg-col-3-gutter-1-inner-1
					mlg-offset-right-col-0-gutter-1 mlg-offset-right-to-margin mlg-col-1-gutter-1-inner-1
					med-offset-right-col-0-gutter-1 med-offset-right-to-margin med-col-2-gutter-1-inner-1
					sm-offset-right-col-0-gutter-1 sm-offset-right-to-margin sm-col-1-gutter-1-inner-1
					ssm-no-float ssm-parent-width';
			} elseif ( 'medium_outset_left_1' === $preset_choice ) {
				$classes .= ' xlg-offset-right-col-0-gutter-1 xlg-offset-right-to-margin xlg-reverse-offset-left-col-1-gutter-1 xlg-col-4
					lg-offset-right-col-0-gutter-1 lg-offset-right-to-margin lg-reverse-offset-left-col-1-gutter-1 lg-col-4
					mlg-offset-right-col-0-gutter-1 mlg-offset-right-to-margin mlg-reverse-offset-left-col-1 mlg-col-1-gutter-1-inner-1
					med-offset-right-col-0-gutter-1 med-offset-right-to-margin med-col-2-gutter-1-inner-1
					sm-offset-right-col-0-gutter-1 sm-offset-right-to-margin sm-col-1-gutter-1-inner-1
					ssm-no-float ssm-parent-width';
			} elseif ( 'medium_outset_left_2' === $preset_choice ) {
				$classes .= ' xlg-offset-right-col-0-gutter-1 xlg-offset-right-to-margin xlg-reverse-offset-left-col-2-gutter-1 xlg-col-4
					lg-offset-right-col-0-gutter-1 lg-offset-right-to-margin lg-reverse-offset-left-col-2-gutter-1 lg-col-4
					mlg-offset-right-col-0-gutter-1 mlg-offset-right-to-margin mlg-reverse-offset-left-col-1 mlg-col-1-gutter-1-inner-1
					med-offset-right-col-0-gutter-1 med-offset-right-to-margin med-col-2-gutter-1-inner-1
					sm-offset-right-col-0-gutter-1 sm-offset-right-to-margin sm-col-1-gutter-1-inner-1
					ssm-no-float ssm-parent-width';
			} elseif ( 'small_inset_right' === $preset_choice ) {
				$classes .= ' xlg-offset-left-col-0-gutter-1 xlg-offset-right xlg-col-1-gutter-1-inner-1
					lg-offset-left-col-0-gutter-1 lg-offset-right lg-col-1-gutter-1-inner-1
					mlg-offset-left-col-0-gutter-1 mlg-offset-right mlg-col-1-gutter-1-inner-1
					med-offset-left-col-0-gutter-1 med-offset-right med-col-2-gutter-1-inner-1
					sm-offset-left-col-0-gutter-1 sm-col-1-gutter-1-inner-1
					ssm-offset-left-col-0-gutter-1 ssm-offset-right ssm-col-1-gutter-1-inner-1';
			} elseif ( 'small_outset_right_1' === $preset_choice ) {
				$classes .= ' xlg-offset-left-col-0-gutter-1 xlg-reverse-offset-right-col-1-gutter-1 xlg-col-2
					lg-offset-left-col-0-gutter-1 lg-reverse-offset-right-col-1-gutter-1 lg-col-2
					mlg-offset-left-col-0-gutter-1 mlg-reverse-offset-right-col-1 mlg-col-1-gutter-1-inner-1
					med-offset-left-col-0-gutter-1 med-reverse-offset-right med-col-2-gutter-1-inner-1
					sm-offset-left-col-0-gutter-1 sm-col-1-gutter-1-inner-1
					ssm-offset-left-col-0-gutter-1 ssm-offset-right ssm-col-1-gutter-1-inner-1';
			} elseif ( 'small_outset_right_2' === $preset_choice ) {
				$classes .= ' xlg-offset-left-col-0-gutter-1 xlg-reverse-offset-right-col-2-gutter-1 xlg-col-2
					lg-offset-left-col-0-gutter-1 lg-reverse-offset-right-col-2-gutter-1 lg-col-2
					mlg-offset-left-col-0-gutter-1 mlg-reverse-offset-right-col-1 mlg-col-1-gutter-1-inner-1
					med-offset-left-col-0-gutter-1 med-reverse-offset-right med-col-2-gutter-1-inner-1
					sm-offset-left-col-0-gutter-1 sm-col-1-gutter-1-inner-1
					ssm-offset-left-col-0-gutter-1 ssm-offset-right ssm-col-1-gutter-1-inner-1';
			} elseif ( 'small_inset_left' === $preset_choice ) {
				$classes .= ' xlg-offset-right-col-0-gutter-1 xlg-offset-right-to-margin xlg-col-1-gutter-1-inner-1
					lg-offset-right-col-0-gutter-1 lg-offset-right-to-margin lg-col-1-gutter-1-inner-1
					mlg-offset-right-col-0-gutter-1 mlg-offset-right-to-margin mlg-col-1-gutter-1-inner-1
					med-offset-right-col-0-gutter-1 med-offset-right-to-margin med-col-2-gutter-1-inner-1
					sm-offset-right-col-0-gutter-1 sm-offset-right-to-margin sm-col-1-gutter-1-inner-1
					ssm-offset-right-col-0-gutter-1 ssm-offset-right-to-margin ssm-col-1-gutter-1-inner-1';
			} elseif ( 'small_outset_left_1' === $preset_choice ) {
				$classes .= ' xlg-offset-right-col-0-gutter-1 xlg-offset-right-to-margin xlg-reverse-offset-left-col-1-gutter-1 xlg-col-2
					lg-offset-right-col-0-gutter-1 lg-offset-right-to-margin lg-reverse-offset-left-col-1-gutter-1 lg-col-2
					mlg-offset-right-col-0-gutter-1 mlg-offset-right-to-margin mlg-reverse-offset-left-col-1 mlg-col-1-gutter-1-inner-1
					med-offset-right-col-0-gutter-1 med-offset-right-to-margin med-col-2-gutter-1-inner-1
					sm-offset-right-col-0-gutter-1 sm-offset-right-to-margin sm-col-1-gutter-1-inner-1
					ssm-offset-right-col-0-gutter-1 ssm-offset-right-to-margin ssm-col-1-gutter-1-inner-1';
			} elseif ( 'small_outset_left_2' === $preset_choice ) {
				$classes .= ' xlg-offset-right-col-0-gutter-1 xlg-offset-right-to-margin xlg-reverse-offset-left-col-2-gutter-1 xlg-col-2
					lg-offset-right-col-0-gutter-1 lg-offset-right-to-margin lg-reverse-offset-left-col-2-gutter-1 lg-col-2
					mlg-offset-right-col-0-gutter-1 mlg-offset-right-to-margin mlg-reverse-offset-left-col-1 mlg-col-1-gutter-1-inner-1
					med-offset-right-col-0-gutter-1 med-offset-right-to-margin med-col-2-gutter-1-inner-1
					sm-offset-right-col-0-gutter-1 sm-offset-right-to-margin sm-col-1-gutter-1-inner-1
					ssm-offset-right-col-0-gutter-1 ssm-offset-right-to-margin ssm-col-1-gutter-1-inner-1';
			} elseif ( 'tiny_inset_right' === $preset_choice ) {
				$classes .= ' xlg-offset-left-col-0-gutter-1 xlg-offset-right xlg-col-0-gutter-1-inner-1
					lg-offset-left-col-0-gutter-1 lg-offset-right lg-col-0-gutter-1-inner-1
					mlg-offset-left-col-0-gutter-1 mlg-offset-right mlg-col-0-gutter-1-inner-1
					med-offset-left-col-0-gutter-1 med-offset-right med-col-0-gutter-1-inner-1
					sm-offset-left-col-0-gutter-1 sm-col-0-gutter-1-inner-1
					ssm-offset-left-col-0-gutter-1 ssm-offset-right ssm-col-0-gutter-1-inner-1';
			} elseif ( 'tiny_outset_right_1' === $preset_choice ) {
				$classes .= ' xlg-offset-left-col-0-gutter-1 xlg-reverse-offset-right-col-1-gutter-1 xlg-col-1
					lg-offset-left-col-0-gutter-1 lg-reverse-offset-right-col-1-gutter-1 lg-col-1
					mlg-offset-left-col-0-gutter-1 mlg-reverse-offset-right-col-1 mlg-col-0-gutter-1-inner-1
					med-offset-left-col-0-gutter-1 med-reverse-offset-right med-col-0-gutter-1-inner-1
					sm-offset-left-col-0-gutter-1 sm-col-0-gutter-1-inner-1
					ssm-offset-left-col-0-gutter-1 ssm-offset-right ssm-col-0-gutter-1-inner-1';
			} elseif ( 'tiny_outset_right_2' === $preset_choice ) {
				$classes .= ' xlg-offset-left-col-0-gutter-1 xlg-reverse-offset-right-col-2-gutter-1 xlg-col-1
					lg-offset-left-col-0-gutter-1 lg-reverse-offset-right-col-2-gutter-1 lg-col-1
					mlg-offset-left-col-0-gutter-1 mlg-reverse-offset-right-col-1 mlg-col-0-gutter-1-inner-1
					med-offset-left-col-0-gutter-1 med-reverse-offset-right med-col-0-gutter-1-inner-1
					sm-offset-left-col-0-gutter-1 sm-col-0-gutter-1-inner-1
					ssm-offset-left-col-0-gutter-1 ssm-offset-right ssm-col-0-gutter-1-inner-1';
			} elseif ( 'tiny_inset_left' === $preset_choice ) {
				$classes .= ' xlg-offset-right-col-0-gutter-1 xlg-offset-right-to-margin xlg-col-0-gutter-1-inner-1
					lg-offset-right-col-0-gutter-1 lg-offset-right-to-margin lg-col-0-gutter-1-inner-1
					mlg-offset-right-col-0-gutter-1 mlg-offset-right-to-margin mlg-col-0-gutter-1-inner-1
					med-offset-right-col-0-gutter-1 med-offset-right-to-margin med-col-0-gutter-1-inner-1
					sm-offset-right-col-0-gutter-1 sm-offset-right-to-margin sm-col-0-gutter-1-inner-1
					ssm-offset-right-col-0-gutter-1 ssm-offset-right-to-margin ssm-col-0-gutter-1-inner-1';
			} elseif ( 'tiny_outset_left_1' === $preset_choice ) {
				$classes .= ' xlg-offset-right-col-0-gutter-1 xlg-offset-right-to-margin xlg-reverse-offset-left-col-1-gutter-1 xlg-col-1
					lg-offset-right-col-0-gutter-1 lg-offset-right-to-margin lg-reverse-offset-left-col-1-gutter-1 lg-col-1
					mlg-offset-right-col-0-gutter-1 mlg-offset-right-to-margin mlg-reverse-offset-left-col-1 mlg-col-0-gutter-1-inner-1
					med-offset-right-col-0-gutter-1 med-offset-right-to-margin med-col-0-gutter-1-inner-1
					sm-offset-right-col-0-gutter-1 sm-offset-right-to-margin sm-col-0-gutter-1-inner-1
					ssm-offset-right-col-0-gutter-1 ssm-offset-right-to-margin ssm-col-0-gutter-1-inner-1';
			} elseif ( 'tiny_outset_left_2' === $preset_choice ) {
				$classes .= ' xlg-offset-right-col-0-gutter-1 xlg-offset-right-to-margin xlg-reverse-offset-left-col-2-gutter-1 xlg-col-1
					lg-offset-right-col-0-gutter-1 lg-offset-right-to-margin lg-reverse-offset-left-col-2-gutter-1 lg-col-1
					mlg-offset-right-col-0-gutter-1 mlg-offset-right-to-margin mlg-reverse-offset-left-col-1 mlg-col-0-gutter-1-inner-1
					med-offset-right-col-0-gutter-1 med-offset-right-to-margin med-col-0-gutter-1-inner-1
					sm-offset-right-col-0-gutter-1 sm-offset-right-to-margin sm-col-0-gutter-1-inner-1
					ssm-offset-right-col-0-gutter-1 ssm-offset-right-to-margin ssm-col-0-gutter-1-inner-1';
			}
		}

	} elseif ( 'standard_left' === $body_copy_style_type ) {

	}

	return $classes;
}























