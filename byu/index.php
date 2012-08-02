<?php get_header(); ?>
<div id="content" class="wrapper one-sidebar clearfix">
    <?php if (!dynamic_sidebar('Alert')) : ?>
        <!--Wigitized 'Alert' for the home page -->
    <?php endif; ?>
    <div id="main-content">
        <div class="post-content">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <h1><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="bookmark">
                            <?php the_title(); ?>
                        </a></h1>
                    <?php if (has_post_thumbnail()) { /* loades the post's featured thumbnail, requires Wordpress 3.0+ */
                        echo '<div class="featured-thumbnail">';
                        the_post_thumbnail();
                        echo '</div>';
                    } ?>
                    <!--<div class="post-content">-->
                            <?php the_content(__('Read more')); ?>
                    <!--</div>-->
                    <div class="post-meta">
                        <p>
                            <?php the_time('F j, Y'); ?>
        <?php the_time() ?>
                        </p>
                        <p>
                    <?php if ($post->comment_status == 'open') comments_popup_link('No Comments', '1 Comment', '% Comments'); ?>
                        </p>
                    </div>
                    <!--.postMeta-->

    <?php endwhile;
else: ?>
            </div><!--post-content-->

            <div class="no-results">
                <p>There are no results</p>
            </div><!--noResults-->

                    <?php endif; ?>

        <nav class="oldernewer">
            <div class="older">
                <p>
<?php next_posts_link('&laquo; Older Entries') ?>
                </p>
            </div>
            <!--.older-->

            <div class="newer">
                <p>
<?php previous_posts_link('Newer Entries &raquo;') ?>
                </p>
            </div><!--.older--> 

        </nav><!--.oldernewer--> 

    </div> 

</div><!--#main-content-->
<br/>
<?php get_sidebar('home'); ?>

</div><!--#content-->
<?php get_footer(); ?>

