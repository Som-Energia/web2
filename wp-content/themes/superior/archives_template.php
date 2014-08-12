<?php
/**
Template Name: Archives
 *
 */
get_header(); ?>
<?php if ( have_posts() ) : the_post(); ?>

<?php a13_title_bar(); ?>

<article id="content">

    <?php a13_header_tools(); ?>

    <div id="col-mask">
        <div id="post-<?php the_ID(); ?>" <?php post_class('post-content'); ?>>

            <?php a13_top_image_video(); ?>

            <div class="real-content">

                <?php
                //page content
                the_content();

                ?>
                <div class="clear"></div>

                <?php
                wp_link_pages( array(
                        'before' => '<div id="page-links">'.__fe('Pages: '),
                        'after'  => '</div>')
                );
                ?>

                <div class="left50">
                    <h3><?php printf( __fe('Latest %d posts'), 50); ?></h3>
                    <ul class="styled">
                    <?php
                        wp_get_archives(array(
                        'type'            => 'postbypost',
                        'limit'           => 50,
                        ));
                    ?>
                    </ul>
                </div>

                <div class="right50">
                    <h3><?php _fe('By months'); ?></h3>
                    <ul class="styled">
                    <?php
                        wp_get_archives(array(
                        'type'            => 'monthly',
                        'show_post_count' => true,
                        ));
                    ?>
                    </ul>

                    <h3><?php printf( __fe( 'Top %d categories' ), 10); ?></h3>
                    <ul class="styled">
                    <?php
                        wp_list_categories(array(
                            'orderby'            => 'count',
                            'order'              => 'DESC',
                            'show_count'         => 1,
                            'number'             => 10,
                            'title_li'           => ''
                        ));
                    ?>
                    </ul>

                    <h3><?php printf( __fe( 'Top %d tags' ), 10); ?></h3>
                    <ul class="styled">
                    <?php
                        $tags = get_tags(array(
                            'orderby'            => 'count',
                            'order'              => 'DESC',
                            'number'             => 10,
                            'title_li'           => ''
                        ));
                        foreach ($tags as $tag){
                            $tag_link = get_tag_link($tag->term_id);
                            echo '<li><a href="'.esc_url($tag_link).'" title="'.esc_attr(sprintf( '%s Tag', $tag->name)).'" class="'.esc_attr($tag->slug).'">'.$tag->name.'</a> ('.$tag->count.')</li>';
                        }
                    ?>
                    </ul>
                </div>

                <div class="clear"></div>

            </div>

        </div>

        <?php get_sidebar(); ?>

    </div>

</article>

<?php endif; ?>

<?php get_footer(); ?>
