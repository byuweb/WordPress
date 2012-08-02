<?php get_header(); 
//global $query_string;
// set the $paged variable
//$page = (get_query_var('paged')) ? get_query_var('paged') : 1; 
//var_dump( $query_string );
//$args = array( 'post_type' => array( 'post', 'photo_downloadable', 'photo_soundslides', 'photo_gallery' ), 'posts_per_page' => 5, 'offset' => 5 * ($page - 1), 'paged' => 1, 'is_paged' => true );
//$args = array_merge( $args, $wp_query->query );
//query_posts( $args );

?>
<div id="content" class="wrapper">
	<h1><?php printf( __( 'Category Archives: %s' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h1>
	<?php echo category_description(); /* displays the category's description from the Wordpress admin */ ?>
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post-single">
			<h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<?php if ( has_post_thumbnail() ) { /* loades the post's featured thumbnail, requires Wordpress 3.0+ */ echo '<div class="featured-thumbnail">'; the_post_thumbnail(); echo '</div>'; } ?>
	
			<p>Written on <?php the_time('F j, Y'); ?> at <?php the_time() ?>, by <?php the_author_posts_link() ?></p>
			<div class="post-excerpt">
				<?php the_excerpt(); /* the excerpt is loaded to help avoid duplicate content issues */ ?>
			</div>
	
			<div class="post-meta">
				<p>
					Categories: <?php the_category(', ') ?>
					<br />
					<?php if (the_tags('Tags: ', ', ', ' ')); ?>
				</p>
			</div><!--.postMeta-->
		</div><!--.post-single-->
	<?php endwhile; else: ?>
		<div class="no-results">
			<p><strong>There has been an error.</strong></p>
			<p>We apologize for any inconvenience, please <a href="<?php bloginfo('url'); ?>/" title="<?php bloginfo('description'); ?>">return to the home page</a> or use the search form below.</p>
			<?php get_search_form(); /* outputs the default Wordpress search form */ ?>
		</div><!--noResults-->
	<?php endif; ?>
		
	<nav class="oldernewer">
		<div class="older">
			<p>
				<?php next_posts_link('&laquo; Older Entries') ?>
			</p>
		</div><!--.older-->
		<div class="newer">
			<p>
				<?php previous_posts_link('Newer Entries &raquo;') ?>
			</p>
		</div><!--.older-->
	</nav><!--.oldernewer-->
	
</div><!--#content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>