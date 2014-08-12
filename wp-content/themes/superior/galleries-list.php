<?php

define( 'A13_GALLERIES_LIST_PAGE', true );
get_header();

?>

<?php
    /**
     * The loop that displays items.
     *
     */

    global $wp_query, $paged, $apollo13;

    //settings
    $genre_template     = defined('A13_GENRE_TEMPLATE');
    $variant            = $apollo13->get_option('cpt_gallery', 'galleries_variant');
    $filter_place       = $apollo13->get_option('cpt_gallery', 'filter_place');
    $title_value        = $apollo13->get_option('cpt_gallery', 'galleries_list_title');
    $full_width         = $apollo13->get_option('cpt_gallery', 'full_width') === 'on';
    $original_query = $wp_query;
    $offset = -1;
    $paged = 0;
    $per_page = -1;


    $args = array(
        'posts_per_page'      => $per_page,
        'offset'              => $offset,
        'post_type'           => A13_CUSTOM_POST_TYPE_GALLERY,
        'post_status'         => 'publish',
        'ignore_sticky_posts' => true,
    );

    if($genre_template === true){
        $term_slug = get_query_var('term');
        if( ! empty( $term_slug ) ){
            $args[A13_CPT_GALLERY_TAXONOMY] = $term_slug;
            $term_name = get_term_by( 'slug', $term_slug, A13_CPT_GALLERY_TAXONOMY);
            $title_value = sprintf( __fe('%1$s : %2$s' ), $title_value, $term_obj->name );
        }
    }

    a13_title_bar($title_value);

?>

<article id="content" class="clearfix<?php echo $full_width ? ' full-width' : ''; ?>">
    <?php a13_header_tools(); ?>

    <div id="col-mask">

    <?php
    //make query for galleries
    $wp_query = new WP_Query( $args );

    /* If there are no posts to display, such as an empty archive page */
    if ( ! have_posts() ) :
        ?>

        <div class="real-content empty-blog">
            <?php
            echo '<p>'.__fe( 'Apologies, but no results were found for the requested archive.' ).'</p>';
            get_template_part( 'no-content');
            ?>
        </div>
        <?php
    /* If there ARE some posts */
    elseif ($wp_query->have_posts()) :
        //classes and other attributes
        $classes = $variant;
        $no_resize_param = '';
        if($variant === 'variant_image'){
            //class with info about resizing items
            //needed cause of type of hover effect
            $temp = $apollo13->get_option('cpt_gallery', 'brick_height' );
            // if 0 then height if fluid
            if(!($temp === '0px' || $temp === '0')){
                $no_resize_param = ' data-no-resize="true"';
            }

            $classes .= ' '.$apollo13->get_option('cpt_gallery', 'gl_hover_type' );
            $classes .= $apollo13->get_option('cpt_gallery', 'gl_hover_zoom' ) == 'on'? ' hov-zoom' : '';
        }
        else{
            $classes .= ' classic';
        }

        echo '<div class="galleries-list-container '.$classes.'">';

        //filter
        if($filter_place === 'above'){
            get_template_part( 'parts/genre-filter' );
        }

        echo '<div id="a13-galleries"'.$no_resize_param.'>';

        while ( have_posts() ) :
            the_post();

            //get album genres
            $terms = wp_get_post_terms(get_the_ID(), A13_CPT_GALLERY_TAXONOMY, array("fields" => "all"));
            $pre = 'data-genre-';
            $suf = '="1" ';
            $genre_string = '';

            //image size
            $image_size_string = 'gallery-cover-custom';
            if      ($variant === 'variant_1'){ $image_size_string = 'work-cover-big'; }
            elseif  ($variant === 'variant_2'){ $image_size_string = 'work-cover-mid'; }
            elseif  ($variant === 'variant_3'){ $image_size_string = 'work-cover-small'; }

            //get all genres that item belongs to
            if( count( $terms ) ):
                foreach($terms as $term) {
                    $genre_string .= $pre.$term->slug.$suf;
                }
            endif;

            echo '<div class="g-item" ' . $genre_string . '>';
            echo '<a class="g-link" href="'.esc_url(get_permalink()).'" id="album-' . get_the_ID() . '">';
            echo '<i>'.a13_make_work_image($post->ID, $image_size_string ).'</i>';
            echo '<em class="cov"><span><strong>'.get_the_title().'</strong>'.a13_subtitle('small').'</span></em>';
            echo '</a>';
            //like plugin
            if( function_exists('dot_irecommendthis') ){
                dot_irecommendthis();
            };
            echo '</div>';
        endwhile;

        echo '  </div>';//#a13-works
        echo '</div>';//.galleries-list-container
    endif;


    //restore previous query
    $wp_query = $original_query;
    wp_reset_postdata();
    ?>
    </div>
</article>


<?php get_footer(); ?>