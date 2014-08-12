<?php
/**
 * The Template for displaying all single posts.
 *
 */

global $apollo13;
get_header(); ?>

<?php the_post(); ?>

<?php a13_title_bar(); ?>

<article id="content" class="clearfix">

    <?php a13_header_tools() ?>

    <div id="col-mask">

        <div id="post-<?php the_ID(); ?>" <?php post_class('post-content'); ?>>
            <?php
                echo '<h2 class="post-title">'.get_the_title().'</h2>';
                a13_post_meta();
                a13_top_image_video();
            ?>

            <div class="real-content">
                <?php the_content(); ?>

                <div class="clear"></div>

                <?php
                    wp_link_pages( array(
                        'before' => '<div id="page-links">'.__fe('Pages: '),
                        'after'  => '</div>')
                    );
                ?>
            </div>


            <?php if($apollo13->get_option( 'blog', 'author_info' ) == 'on'): ?>
            <div class="about-author clearfix">
                <h3 class="title widget-title"><span><?php _fe('About the author'); ?></span></h3>
                <?php $author_ID = get_the_author_meta( 'ID' );
                    echo '<a href="'.get_author_posts_url($author_ID).'" class="avatar">'.get_avatar( $author_ID, 50 ).'</a>';
                ?>
                <div class="author-inside">

                    <div class="author-description">
                        <?php
                            echo '<strong class="author-name">'.get_the_author();
                            $u_url = get_the_author_meta( 'user_url' );
                            if( ! empty( $u_url ) ){
                                echo '<a href="' . esc_url($u_url) . '" class="url">(' . $u_url . ')</a>';
                            }
                            echo '</strong> - ';
                            the_author_meta( 'description' );
                         ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>


            <?php a13_similar_posts(); ?>

            <?php comments_template( '', true ); ?>
        </div>



        <?php get_sidebar(); ?>

    </div>

</article>

<?php get_footer(); ?>
