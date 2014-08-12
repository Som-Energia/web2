<?php
/**
Template Name: Sitemap
 * The template for displaying 404 pages (Not Found) and Sitemap.
 *
 */

global $empty_error_msg;
$not_styled_page = is_404() || isset($empty_error_msg);
$title = '';


/* 404 or empty archive/search*/
if($not_styled_page){
    define('A13_NO_STYLED_PAGE', true);
    $title = isset($empty_error_msg)? $empty_error_msg : __fe( 'Error 404' );
}
//sitemap
else{
    $title = get_the_title();
}
get_header(); ?>

<?php a13_title_bar($title); ?>

<article id="content" class="clearfix">

    <?php a13_header_tools(); ?>

    <div id="col-mask">

        <div class="post-content">
            <?php $not_styled_page? false : a13_top_image_video(); ?>

            <div class="real-content">
                <?php $not_styled_page? false : the_content(); ?>

                <div class="clear"></div>

                <?php get_template_part( 'no-content'); ?>

                <?php
                wp_link_pages( array(
                        'before' => '<div id="page-links">'.__fe('Pages: '),
                        'after'  => '</div>')
                );
                ?>
            </div>

        </div>

        <?php get_sidebar(); ?>

    </div>

</article>

<?php get_footer(); ?>