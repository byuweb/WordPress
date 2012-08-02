<?php
	class byu_footer_walker extends Walker_Nav_Menu
	{
		  function start_el(&$output, $item, $depth, $args)
		  {
			   global $wp_query;
			   global $wp_query;

			   if($depth != 0) {
				   $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
				   $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
				   $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
				   $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

					$item_output = $args->before;
					$item_output .= '<a'. $attributes .'>';
					$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID );
					$item_output .= $args->link_after;
					$item_output .= '</a>';
					$item_output .= $args->after;
				}
				else {
					$item_output = '<h2>' . $item->title . '</h2>';
				}

				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
			}
	}