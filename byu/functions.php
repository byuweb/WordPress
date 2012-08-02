<?php
	require_once ( get_template_directory() . '/byu_footer_walker.php' );
	require_once ( get_template_directory() . '/byu_main_nav_walker.php' );				
	require_once ( get_template_directory() . '/byu_secondary_nav_walker.php' );			
	
	// Custom size for frontpage images
	if ( function_exists( 'add_theme_support' ) ) {
		add_theme_support( 'post-thumbnails' );
			set_post_thumbnail_size( 620, 620 ); // default Post Thumbnail dimensions   
	}
	//if ( function_exists( 'add_image_size' ) ) {
	//	add_image_size( 'homepage-thumb', 620, 999 );
	//}
		
	// Custom gallery template
	add_filter( 'post_gallery', 'byu_post_gallery', 20, 2 );
	
	function byu_post_gallery( $output, $attr ) {
	
		if( isset($output) )
			return $output;
	
		if( !(isset($attr['link']) && 'file' == $attr['link']) )
			return '';
	
		global $post, $wp_locale;
		static $instance = 0;
		$instance++;
	
		// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
		if ( isset( $attr['orderby'] ) ) {
			$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
			if ( !$attr['orderby'] )
				unset( $attr['orderby'] );
		}

		extract(shortcode_atts(array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post->ID,
			'itemtag'    => 'dl',
			'icontag'    => 'dt',
			'captiontag' => 'dd',
			'columns'    => 3,
			'size'       => 'thumbnail',
			'include'    => '',
			'exclude'    => '',
		), $attr));

		$id = intval($id);
		if ( 'RAND' == $order )
			$orderby = 'none';

		if ( !empty($include) ) {
			$include = preg_replace( '/[^0-9,]+/', '', $include );
			$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif ( !empty($exclude) ) {
			$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
			$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		} else {
			$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		}

		if ( empty($attachments) )
			return '';

		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment )
				$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
			return $output;
		}

		$itemtag = tag_escape($itemtag);
		$captiontag = tag_escape($captiontag);
		$columns = intval($columns);
		$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
		$float = is_rtl() ? 'right' : 'left';

		$selector = "gallery-{$instance}";

		$gallery_style = $gallery_div = '';
		if ( apply_filters( 'use_default_gallery_style', true ) )
			$gallery_style = "
			<style type='text/css'>
				#{$selector} {
					margin: auto;
				}
				#{$selector} .gallery-item {
					float: {$float};
					margin-top: 10px;
					text-align: center;
					width: {$itemwidth}%;
				}
				#{$selector} img {
					border: 2px solid #cfcfcf;
				}
				#{$selector} .gallery-caption {
					margin-left: 0;
				}
			</style>
			<!-- see gallery_shortcode() in wp-includes/media.php -->";
		$size_class = sanitize_html_class( $size );
		$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
		$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

		$i = 0;
		foreach ( $attachments as $id => $attachment ) {		
			//var_dump($attachment);
			$img = wp_get_attachment_image_src( $id, 'large' );
			$link = '<a href="' . $img[0] . '" class="shutterset" title="' . $attachment->post_title . '">' . wp_get_attachment_image( $attachment->ID, 'thumbnail' ) . '</a>';			
			print "whats going on? Line 117 in functions.php";
			$output .= "<{$itemtag} class='gallery-item'>";
			$output .= "
				<{$icontag} class='gallery-icon'>
					$link
				</{$icontag}>";
			if ( $captiontag && trim($attachment->post_excerpt) ) {
				$output .= "
					<{$captiontag} class='wp-caption-text gallery-caption'>
					" . wptexturize($attachment->post_excerpt) . "
					</{$captiontag}>";
			}
			$output .= "</{$itemtag}>";
			if ( $columns > 0 && ++$i % $columns == 0 )
				$output .= '<br style="clear: both" />';
		}

		$output .= "
				<br style='clear: both;' />
			</div>\n";

		return $output;
	}
	
	// Custom image sizes
	add_filter( 'intermediate_image_sizes', 'byu_intermediate_image_sizes' );
	add_filter( 'attachment_fields_to_edit', 'byu_attachment_fields', 11, 2 );
	
	function byu_intermediate_image_sizes( $sizes ) {
		$sizes[] = "byu_menu";
		update_option("byu_menu_size_w", 200);
		update_option("byu_menu_size_h", 133);
		update_option("byu_menu_crop", 1);
		return $sizes;
	}
	
	function byu_attachment_fields( $form_fields, $post ) {
		
		$size = 'byu_menu';
		$name = 'Menu Highlight';
		
		$downsize = image_downsize($post->ID, $size);

		// is this size selectable?
		$enabled = true;
		$css_id = "image-size-{$size}-{$post->ID}";
		// if this size is the default but that's not available, don't select it		
		$checked = isset( $_GET['byu_menu'] );

		$html = "<div class='image-size-item'><input type='radio' ".( $enabled ? '' : "disabled='disabled'")."name='attachments[$post->ID][image-size]' id='{$css_id}' value='{$size}'".( $checked ? " checked='checked'" : '') ." />";

		$html .= "<label for='{$css_id}'>" . __($name). "</label>";
		// only show the dimensions if that choice is available
		if ( $enabled )
			$html .= " <label for='{$css_id}' class='help'>" . sprintf( __("(%d&nbsp;&times;&nbsp;%d)"), $downsize[1], $downsize[2] ). "</label>";

		$html .= '</div>';

        $form_fields['image-size']['html'] .= $html;
        return $form_fields;
	}
	
	// Custom fields for mega menu
	add_filter( 'wp_setup_nav_menu_item', 'byu_setup_menu_item' );
	add_filter( 'wp_edit_nav_menu_walker', 'byu_menu' );
	add_filter( 'wp_update_nav_menu_item', 'byu_update_nav_menu_item', 10, 3 );
	
	function byu_setup_menu_item( $menu_item ) {
		
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_script( 'byu-menu-image-upload', get_template_directory_uri() . '/byu-menu-image-upload.js', array( 'jquery','media-upload','thickbox' ) );
		$menu_item->byu_featured = empty( $menu_item->byu_featured ) ? get_post_meta( $menu_item->ID, '_menu_item_byu_featured', true ) : $menu_item->byu_featured;
		$menu_item->byu_right_column = empty( $menu_item->byu_right_column ) ? get_post_meta( $menu_item->ID, '_menu_item_byu_right_column', true ) : $menu_item->byu_right_column;
		$menu_item->byu_highlight = empty( $menu_item->byu_highlight ) ? get_post_meta( $menu_item->ID, '_menu_item_byu_highlight', true ) : $menu_item->byu_highlight;
		$menu_item->byu_image = empty( $menu_item->byu_image ) ? get_post_meta( $menu_item->ID, '_menu_item_byu_image', true ) : $menu_item->byu_image;
		$menu_item->byu_image_alt = empty( $menu_item->byu_image_alt ) ? get_post_meta( $menu_item->ID, '_menu_item_byu_image_alt', true ) : $menu_item->byu_image_alt;
		$menu_item->byu_description = empty( $menu_item->byu_description ) ? get_post_meta( $menu_item->ID, '_menu_item_byu_description', true ) : $menu_item->byu_description;
		
		return $menu_item;
	}
	
	function byu_update_nav_menu_item( $menu_id, $menu_item_db_id, $args ) {
		
		update_post_meta( $menu_item_db_id, '_menu_item_byu_featured', ( empty( $_POST[ 'menu-item-byu-featured' ][ $menu_item_db_id ] ) ? '0' : '1' ) );
		update_post_meta( $menu_item_db_id, '_menu_item_byu_right_column', ( empty( $_POST[ 'menu-item-byu-right-column' ][ $menu_item_db_id ] ) ? '0' : '1' ) );
		update_post_meta( $menu_item_db_id, '_menu_item_byu_highlight', ( empty( $_POST[ 'menu-item-byu-highlight' ][ $menu_item_db_id ] ) ? '0' : '1' ) );
		update_post_meta( $menu_item_db_id, '_menu_item_byu_image', ( empty( $_POST[ 'menu-item-byu-image' ][ $menu_item_db_id ] ) ? '' : $_POST[ 'menu-item-byu-image' ][ $menu_item_db_id ] ) );
		update_post_meta( $menu_item_db_id, '_menu_item_byu_image_alt', ( empty( $_POST[ 'menu-item-byu-image-alt' ][ $menu_item_db_id ] ) ? '' : $_POST[ 'menu-item-byu-image-alt' ][ $menu_item_db_id ] ));
		update_post_meta( $menu_item_db_id, '_menu_item_byu_description', ( empty( $_POST[ 'menu-item-byu-description' ][ $menu_item_db_id ] ) ? '' : $_POST[ 'menu-item-byu-description' ][ $menu_item_db_id ] ) );
		
		return $menu_id;
	}
	
	function byu_menu() {		
		require_once ( get_template_directory() . '/byu_nav_menu_edit.php' );
		return 'Byu_Nav_Menu_Edit';
	}

	// enables wigitized sidebars
	if ( function_exists('register_sidebar') )
	// The Alert Widget
	// Location: displayed on the top of the home page, right after the header, right before the loop, within the contend area
	register_sidebar(array(
		'name'=>'Alert',
		'id' => 'alert',
		'before_widget' => '<div class="widget-area widget-alert"><ul>',
		'after_widget' => '</ul></div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	));
	// Home Sidebar Widget
	// Location: the sidebar on the index page
	register_sidebar(array(
		'name'=>'Home Sidebar',
		'id' => 'home-sidebar',
		'before_widget' => '<div class="widget-area widget-sidebar">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	// Left Sidebar Widget
	register_sidebar(array(
		'name'=>'Sidebar Left',
		'id' => 'sidebar-left',
		'before_widget' => '',
		'after_widget' => '<br/>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	// Right Sidebar Widget
	register_sidebar(array(
		'name'=>'Sidebar Right',
		'id' => 'sidebar-right',
		'before_widget' => '',
		'after_widget' => '<br/>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	
	register_sidebar( array(
		'name' => 'Footer Area One',
		'id' => 'footer-sidebar-1',
		'description' => 'Location: at the top of the footer, above the copyright',
		'before_widget' => '<aside>',
		'after_widget' => "</aside>",
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	) );

	register_sidebar( array(
		'name' => 'Footer Area Two',
		'id' => 'footer-sidebar-2',
		'description' => 'An optional widget area for your site footer',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	) );

	register_sidebar( array(
		'name' => 'Footer Area Three',
		'id' => 'footer-sidebar-3',
		'description' => 'An optional widget area for your site footer',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	) );
	
	register_sidebar( array(
		'name' => 'Footer Area Four',
		'id' => 'footer-sidebar-4',
		'description' => 'An optional widget area for your site footer',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	) );
	
	register_sidebar( array(
		'name' => 'Footer Area Five',
		'id' => 'footer-sidebar-5',
		'description' => 'An optional widget area for your site footer',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	) );

	// custom menu support
	add_theme_support( 'menus' );
	if ( function_exists( 'register_nav_menus' ) ) {
	  	register_nav_menus(
	  		array(
			  'main-menu' => 'Main Menu',
	  		  'secondary-menu' => 'Secondary Menu',
	  		  'footer-menu' => 'Footer Menu',
	  		  'sidebar-menu' => 'Sidebar Menu',
	  		  'logged-in-menu' => 'Logged In Menu'
	  		)
	  	);
	}

	// custom background support
	add_custom_background();

	// custom header image support
	//define('NO_HEADER_TEXT', true );
	//define('HEADER_IMAGE', '%s/images/default-header.png'); // %s is the template dir uri
	//define('HEADER_IMAGE_WIDTH', 1068); // use width and height appropriate for your theme
	//define('HEADER_IMAGE_HEIGHT', 300);
	// gets included in the admin header
	function admin_header_style() {
	    ?><style type="text/css">
	        #headimg {
	            width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
	            height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	        }
	    </style><?php
	}
	
	// removes detailed login error information for security
	add_filter('login_errors',create_function('$a', "return null;"));
	
	// Removes Trackbacks from the comment count
	add_filter('get_comments_number', 'comment_count', 0);
	function comment_count( $count ) {
		if ( ! is_admin() ) {
			global $id;
			$comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
			return count($comments_by_type['comment']);
		} else {
			return $count;
		}
	}
	
	// custom excerpt ellipses for 2.9+
	function custom_excerpt_more($more) {
		return 'Read More &raquo;';
	}
	add_filter('excerpt_more', 'custom_excerpt_more');
	// no more jumping for read more link
	function no_more_jumping($post) {
		if( is_object( $post ) )
			return '<a href="'.get_permalink($post->ID).'" class="read-more">'.'Continue Reading'.'</a>';
		return '';
	}
	add_filter('excerpt_more', 'no_more_jumping');
	
	// category id in body and post class
	function category_id_class($classes) {
		global $post;
		foreach((get_the_category($post->ID)) as $category)
			$classes [] = 'cat-' . $category->cat_ID . '-id';
			return $classes;
	}
	add_filter('post_class', 'category_id_class');
	add_filter('body_class', 'category_id_class');
?>