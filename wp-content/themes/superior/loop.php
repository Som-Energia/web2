<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 */
global $apollo13;
?>


<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php
if ( ! have_posts() ):
    global $empty_error_msg;
    $empty_error_msg = true; //set to anything
    ?>
    <div class="real-content empty-blog">
    <?php
    echo '<p>'.__fe( 'Apologies, but no results were found for the requested archive.' ).'</p>';
    get_template_part( 'no-content');
    ?>
    </div>
    <?php

else:
    echo '<div id="only-posts-here">'; /* needed in case of masonry variant*/

    while ( have_posts() ) : the_post(); ?>

        <div id="post-<?php the_ID(); ?>" <?php post_class('archive-item'); ?>>

            <?php get_template_part( 'content', get_post_format() ); ?>

		</div>

    <?php endwhile;

    echo '</div>'; /* needed in case of masonry variant*/

endif;