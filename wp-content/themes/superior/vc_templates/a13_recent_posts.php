<?php
$title = $grid_columns_count = $grid_teasers_count = $grid_link_target = '';
$grid_template = $grid_layout_mode = $grid_taxomonies = $grid_categories = $posts_in = $posts_not_in = '';
$el_class = $orderby = $order  = $isotope_item = '';
//global $a13_sc_post_grid_type;
//echo $a13_sc_post_grid_type;
extract(shortcode_atts(array(
    'title' => '',
    'grid_columns_count' => 4,
    'grid_teasers_count' => 8,
    'grid_link_target' => '_self',
    'grid_template' => 'grid', //grid, carousel
    'grid_layout_mode' => 'fitRows',
    'posts_in' => '',
    'posts_not_in' => '',
    'grid_categories' => '',
    'orderby' => NULL,
    'order' => 'DESC',
    'el_class' => '',
), $atts));

if ( $grid_template == 'grid' || $grid_template == 'filtered_grid') {
    wp_enqueue_style('isotope-css');
    wp_enqueue_script( 'isotope' );
    $isotope_item = 'isotope-item ';
} else if ( $grid_template == 'carousel' ) {
    wp_enqueue_script( 'jcarousellite' );
    $isotope_item = '';
}

$output = '';

$el_class = $this->getExtraClass( $el_class );
$li_span_class = wpb_translateColumnsCountToSpanClass( $grid_columns_count );


$query_args = array();

$not_in = array();
if ( $posts_not_in != '' ) {
    $posts_not_in = str_ireplace(" ", "", $posts_not_in);
    $not_in = explode(",", $posts_not_in);
}

$link_target = $grid_link_target=='_blank' ? ' target="_blank"' : '';

//exclude current post/page from query
if ( $posts_in == '' ) {
    global $post;
    array_push($not_in, $post->ID);
}
else if ( $posts_in != '' ) {
    $posts_in = str_ireplace(" ", "", $posts_in);
    $query_args['post__in'] = explode(",", $posts_in);
}
if ( $posts_in == '' || $posts_not_in != '' ) {
    $query_args['post__not_in'] = $not_in;
}

// Post teasers count
if ( $grid_teasers_count != '' && !is_numeric($grid_teasers_count) ) $grid_teasers_count = -1;
if ( $grid_teasers_count != '' && is_numeric($grid_teasers_count) ) $query_args['posts_per_page'] = $grid_teasers_count;




// Post types, taxonomies, and other things that depend on type
$post_type = empty($a13_sc_post_grid_type) ? 'post' : $a13_sc_post_grid_type;
$taxonomies = array();

if($post_type === 'work'){
    array_push($taxonomies, 'genre');
}
elseif($post_type === 'gallery'){
    array_push($taxonomies, 'kind');
}

//thumb size to use
$thumb_size = a13_teaser_thumb_size('apollo-post-brick', $grid_columns_count); //size we will use in most cases

$query_args['post_type'] = $post_type;





// Narrow by categories
if ( $grid_categories != '' ) {
    $grid_categories = explode(",", $grid_categories);
    $gc = array();
    foreach ( $grid_categories as $grid_cat ) {
        array_push($gc, $grid_cat);
    }
    $gc = implode(",", $gc);
    ////http://snipplr.com/view/17434/wordpress-get-category-slug/
    $query_args['category_name'] = $gc;

    $taxonomies = get_taxonomies('', 'object');
    $query_args['tax_query'] = array('relation' => 'OR');
    foreach ( $taxonomies as $t ) {
        if ( in_array($t->object_type[0], (array)$post_type) ) {
            $query_args['tax_query'][] = array(
                'taxonomy' => $t->name,//$t->name,//'portfolio_category',
                'terms' => $grid_categories,
                'field' => 'slug',
            );
        }
    }
}

// Order posts
if ( $orderby != NULL ) {
    $query_args['orderby'] = $orderby;
}
$query_args['order'] = $order;

// Run query
$my_query = new WP_Query($query_args);

$teasers = '';
$teaser_categories = Array();
if($grid_template == 'filtered_grid' && empty($grid_taxomonies)) {
    $taxonomies = get_object_taxonomies(!empty($query_args['post_type']) ? $query_args['post_type'] : get_post_types(array('public' => false, 'name' => 'attachment'), 'names', 'NOT'));
}

$posts_Ids = array();


while ( $my_query->have_posts() ) {
    $my_query->the_post();
    $post_id = $my_query->post->ID;
    $posts_Ids[] = $post_id;


    $categories_css = '';
    if( $grid_template == 'filtered_grid' ) {
        /** @var $post_categories get list of categories */
        // $post_categories = get_the_category($my_query->post->ID);
        $post_categories = wp_get_object_terms($post_id, array_values($taxonomies));
        if(!is_wp_error($post_categories)) {
            foreach($post_categories as $cat) {
                if(!in_array($cat->term_id, $teaser_categories)) {
                    $teaser_categories[] = $cat->term_id;
                }
                $categories_css .= ' grid-cat-'.$cat->term_id;
            }
        }

    }

    $post_title = get_the_title($post_id);

    $post_thumbnail = wpb_getImageBySize(array( 'post_id' => $post_id, 'thumb_size' => $thumb_size ));

    $thumbnail = $post_thumbnail['thumbnail'];

    // Link logic
    $link_image_start   = '<a class="link_image" href="'.get_permalink($post_id).'"'.$link_target.' title="'.sprintf( esc_attr__( 'Permalink to %s', 'js_composer' ), the_title_attribute( 'echo=0' ) ).'">';
    $link_title_start   = '<a class="link_title" href="'.get_permalink($post_id).'"'.$link_target.' title="'.sprintf( esc_attr__( 'Permalink to %s', 'js_composer' ), the_title_attribute( 'echo=0' ) ).'">';
    $link_image_end     = '<em></em></a>';
    $link_title_end     = '</a>';


    //ITEM HTML
    if($post_type==='post'){
        $content = apply_filters('the_excerpt', get_the_excerpt());
        $content = wpautop($content);

        $teasers .= '<li class="archive-item ready '.$isotope_item.apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $li_span_class, 'vc_teaser_grid_li').$categories_css.'">';
        if ( $thumbnail ) {
            $teasers .= '<div class="item-image post-media">' . $link_image_start . $thumbnail . $link_image_end .'</div>';
        }
        //title
        $teasers .= '<h2 class="post-title">' . $link_title_start . $post_title . $link_title_end . '</h2>';
        //post meta
        $teasers .=
            '<div class="post-meta">'
                .'<time class="entry-date" datetime="'.get_the_date( 'c' ).'">'.get_the_date().'</time>'
                .'/ '
                .'<span class="comments"><a href="' . esc_url(get_comments_link()) . '" title="'
                . sprintf(__fe( '%d Comment(s)' ), get_comments_number()) . '">'
                . sprintf(__fe( '%d Comment(s)' ), get_comments_number()) . '</a></span>'
                .'</div>';

        //content
        $teasers .= '<div class="entry-content">' . $content . '</div>';
        $teasers .= '</li> ';
    }
    //works || galleries
    else{
        $alt_link       = get_post_meta($post_id, '_alt_link', true);
        $url            = (strlen($alt_link) > 0)? $alt_link : get_permalink();
        $post_subtitle  = get_post_meta($post_id, '_subtitle', true);
        if(strlen($post_subtitle)){
            $post_subtitle = '<small>'.$post_subtitle.'</small>';
        }

        $teasers .= '<li class="g-item ready '.$isotope_item.apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $li_span_class, 'vc_teaser_grid_li').$categories_css.'">';
        $teasers .= '<a class="g-link" href="'.esc_url($url).'" id="album-' . $post_id . '">';
        $teasers .= '<i>'.$thumbnail.'</i>';
        $teasers .= '<em class="cov"><span><strong>'.$post_title.'</strong>'.$post_subtitle.'</span></em>';
        $teasers .= '</a>';
        //like plugin
//            if( function_exists('dot_irecommendthis') ){
//                dot_irecommendthis();
//            }
        $teasers .= '</li>';
    }


} // endwhile loop
wp_reset_query();

if( $grid_template == 'filtered_grid' && $teasers && !empty($teaser_categories)) {
    /*
    $categories_list = wp_list_categories(array(
        'orderby' => 'name',
        'walker' => new Teaser_Grid_Category_Walker(),
        'include' => implode(',', $teaser_categories),
        'show_option_none'   => __('No categories', 'js_composer'),
        'echo' => false
    ));
    */
    $categories_array = get_terms(array_values($taxonomies), array(
        'orderby' => 'name',
        'include' => implode(',', $teaser_categories)
    ));

    $categories_list_output = '<ul class="categories_filter genre-filter open static-block"><li class="label">'.__( 'Filter','a13_shortcodes' ).'</li>';
    $categories_list_output .= '<li class="active"><a href="#" data-filter="*">' . __('All', 'js_composer') . '</a></li>';
    if(!is_wp_error($categories_array)) {
        foreach($categories_array as $cat) {
            $categories_list_output .= '<li><a href="#" data-filter=".grid-cat-'.$cat->term_id.'">' . esc_attr($cat->name) . '</a></li>';
        }
    }
    $categories_list_output.= '</ul><div class="clearfix"></div>';
} else {
    $categories_list_output = '';
}

if ( $teasers ) {
    $teasers =
        '<div class="teaser_grid_container '.($post_type === 'post'? 'variant_masonry' : 'variant_3 classic').'">'
            .$categories_list_output
            .'<ul class="wpb_thumbnails wpb_thumbnails-fluid clearfix" data-layout-mode="'.$grid_layout_mode.'">'
            . $teasers
            .'</ul>'
            .'</div>';
}
else { $teasers = __("Nothing found." , "js_composer"); }

$grid_class = 'wpb_'.$grid_template . ' columns_count_'.$grid_columns_count . ' grid_layout-'.$grid_layout_mode . ' grid_template-'.$grid_template;
$css_class =  'wpb_teaser_grid wpb_content_element '.$grid_class.$el_class;

$output .= "\n\t".'<div class="'.$css_class.'">';
$output .= "\n\t\t".'<div class="wpb_wrapper">';

if(strlen($title) || $grid_template === 'carousel'){
    $output .=
        '<div class="title-and-nav clearfix">';
    if ( $grid_template === 'carousel' ){
        $home_link = '';
        if($post_type === 'post'){
            $pfp = get_option( 'page_for_posts');
            $home_link = ( $pfp !== '0')? get_permalink(get_option( 'page_for_posts')) : home_url();
            $home_link = '<a href="'.$home_link.'" class="whole-list fa fa-th-large"></a>';
        }

        $output .=
            '<nav>'
                .'<a href="#" class="prev fa fa-chevron-left"></a>'
                .$home_link
                .'<a href="#" class="next fa fa-chevron-right"></a>'
                .'</nav>';
    }
    if(strlen($title)){
        $output .=
            '<h3 class="title"><span>'.$title.'</span></h3>';
    }

    $output .=
        '</div>';
}

$output .= $teasers;
$output .= "\n\t\t".'</div> ';
$output .= "\n\t".'</div> ';

echo $output;
