<?php
	class byu_main_nav_walker extends Walker_Nav_Menu
	{		
		private $featured_links;
		private $normal_links;
		private $highlights;
		private $last_item;
		private $column;
		
		/**
		 * @see Walker::start_lvl()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int $depth Depth of page. Used for padding.
		 */
		function start_lvl(&$output, $depth) {
		}

		/**
		 * @see Walker::end_lvl()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int $depth Depth of page. Used for padding.
		 */
		function end_lvl(&$output, $depth) {
		}
		
		function start_el(&$output, $item, $depth, $args) {
			if( $depth == 0 ) {// Top level items can't be highlights, featured
				$this->featured_links = '';
				$this->normal_links = '';
				$this->highlights = '';
				$this->last_item = null;
				$this->column = '';	
				$output .= '<li><a href="' . esc_attr( $item->url ) . '">' . esc_attr( $item->title ) . '</a>';
			} 
			else {				
				
				if( !$item->byu_sublink &&  ( $this->last_item != null && $this->last_item->byu_sublink ) ) {
					if ( $this->last_item->byu_featured )
						$this->featured_links .= '</div>';
					else if ( !$this->last_item->byu_highlight )
						$this->normal_links .= '</div>';
				}
			
				if( $item->byu_highlight ) {
					$this->highlights .= '<div class="highlight">';
					$this->highlights .= '<a href="' . $item->url . '"><img src="' . $item->byu_image . '" alt="'. $item->byu_image_alt .'"/></a>';
					$this->highlights .= '<p><a href="'. $item->url .'">'.$item->title.'</a></p>';
					$this->highlights .= '<p>'.$item->byu_description .'</p>';
					$this->highlights .= '</div>';
				}
				else if( $item->byu_featured ) {
					/*?>if( $item->byu_sublink  && ( $this->last_item == null || !$this->last_item->byu_sublink ) ) {
						$this->featured_links .= '<div class="sublinks">';
					}<?php */
					$this->featured_links .= '<li><a href="' . esc_attr( $item->url ) . '">' . esc_attr( $item->title ) . '</a></li>';
				}
				else {
					//if( $item->byu_sublink  && ( $this->last_item == null || !$this->last_item->byu_sublink ) ) {
					if ($depth ==2)
						$this->normal_links .= '<ul class="sublinks">';
						
					if( !$item->byu_right_column )
						$this->normal_links .= '<li><a href="' . esc_attr( $item->url ) . '">' . esc_attr( $item->title ) . '</a></li>';
					else
						$this->column .= '<li><a href="' . esc_attr( $item->url ) . '">' . esc_attr( $item->title ) . '</a></li>';
					
					if ($depth ==2)
						$this->normal_links .= '</ul>';	
						
				}			
			}
			
			$this->last_item = $item;
		}
		
		/**
		 * @see Walker::end_el()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item Page data object. Not used.
		 * @param int $depth Depth of page. Not Used.
		 */
		function end_el(&$output, $item, $depth) {
			if( $depth == 0 ) {
			
				if( $this->last_item != null && $this->last_item->byu_sublink ) {
					if ( $this->last_item->byu_featured )
						$this->featured_links .= '</div>';
					else if ( !$this->last_item->byu_highlight )
						$this->normal_links .= '</div>';
				}
			
				if( $this->normal_links != '' || $this->featured_links != '' || $this->highlights != '' ) {

					$output .= '<div class="' . ($this->highlights != '' ? 'mega' : 'sub') . '">';
					$output .= '<ul class="links' . ($this->column != '' ? ' double' : '') . '">';
				
					if( $this->featured_links != '' )
						$output .= '<li class="featured"><ul>' . $this->featured_links . '</ul></li><hr/>';
					
					if( $this->normal_links != '' ) {
						if( $this->column == '')
							$output .= $this->normal_links;
						else {
							$output .= '<div class="left">' . $this->normal_links . '</div>';
							$output .= '<div class="left">' . $this->column . '</div>';
						}
					}
					
					$output .= '</ul>'; //Closes the Links ul
					
					if( $this->highlights != '' ) {
						$output .= $this->highlights;
					}
					
					$output .= '</div>'; //Closes the Mega Div
				}
				
				$output .= '</li>'; //Closes the top level Li
			}
		}
	}