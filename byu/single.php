<?php get_header(); ?>
<?php 
if ( is_active_sidebar( 'sidebar-left' ) && ( is_active_sidebar( 'sidebar-right' ) ))
$sidebar = "two-sidebars";
else if (is_active_sidebar('sidebar-left') || (is_active_sidebar('sidebar-right')) )
$sidebar = "one-sidebar";
else
$sidebar ='';

if (is_active_sidebar('sidebar-left') && !is_active_sidebar('sidebar-right'))
$align = "omega";
?>
<div id="content" class="wrapper <?php echo $sidebar?>">	
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	
	<h1><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
	<?php edit_post_link('<p>Edit this entry</p>'); ?>
	<?php get_sidebar('left'); ?>
	
	<div id="main-content" class="<?php echo $align?>">
	<?php //if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>

			<article>
				<?php /*?><h1><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
<?php */?>				
				<?php // edit_post_link('<small>Edit this entry</small>','',''); ?>
				<?php if ( has_post_thumbnail() ) { /* loades the post's featured thumbnail, requires Wordpress 3.0+ */ echo '<div class="featured-thumbnail">'; the_post_thumbnail(); echo '</div>'; } ?>
				<div class="post-content">
					<?php the_content(); ?>
					<?php wp_link_pages('before=<div class="pagination">&after=</div>'); ?>
				</div><!--.post-content-->
			<article>

			<div id="post-meta">
				<p>
					Posted on <?php the_time('F j, Y'); ?> at <?php the_time() ?>
				</p>
				<p>
					Categories: <?php the_category(', ') ?>
					<br />
					<?php the_tags('Tags: ', ', ', ' '); ?>
				</p>
			</div><!--#post-meta-->

		</div><!-- #post-## -->

		<div class="newer-older">
			<div class="older">
				<p>
					<?php previous_post_link('%link', '&laquo; Previous post') ?>
				</p>
			</div><!--.older-->
			<div class="newer">
				<p>
					<?php next_post_link('%link', 'Next Post &raquo;') ?>
				</p>
			</div><!--.older-->
		</div><!--.newer-older-->

		<?php //comments_template( '', true ); ?>

	<?php endwhile; /* end loop */ ?>
    
   </div><!--#main-content-->

		<?php  get_sidebar('right'); ?> 

</div><!--#content-->

<?php get_footer(); ?>