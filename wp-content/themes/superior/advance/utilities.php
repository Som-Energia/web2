<?php

/*
 * For getting URL of current page
 */
if(!function_exists('a13_current_url')){
    function a13_current_url(){
        global $wp;

        //no permalinks
        if($wp->request === NULL){
            $current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
        }
        else{
            $current_url = trailingslashit(home_url(add_query_arg(array(),$wp->request)));
        }

        return $current_url;
    }
}



/*
 * Filter that change default permalinks for posts and custom post types
 */
if(!function_exists('a13_custom_permalink')){
    function a13_custom_permalink($url, $post, $leavename){
        $custom_link_types = array('post', A13_CUSTOM_POST_TYPE_WORK, A13_CUSTOM_POST_TYPE_GALLERY);
        if ( in_array($post->post_type, $custom_link_types) ) {
            $custom_url = get_post_meta($post->ID,'_alt_link', true);
            //use custom link if available
            if(strlen($custom_url)){
                return $custom_url;
            }
            return $url;
        }
        return $url;
    }
}


/*
 * Check if current page is type of Posts list
 */
if(!function_exists('a13_is_post_list')){
    function a13_is_post_list(){
        return is_home() || (is_archive() && !defined('A13_WORKS_LIST_PAGE')) || is_search();
    }
}


/*
 * Check if current page is subpage
 */
if(!function_exists('a13_is_subpage')){
    function a13_is_subpage() {
        global $post;                              // load details about this page

        if ( is_page() && $post->post_parent ) {   // test to see if the page has a parent
            return $post->post_parent;             // return the ID of the parent post

        } else {                                   // there is no parent so ...
            return false;                          // ... the answer to the question is false
        }
    }
}


/*
 * Title META tag
 */
if(!function_exists('a13_wp_title')){
    function a13_wp_title( $title, $sep ) {
        global $paged, $page;

        if ( is_feed() )
            return $title;

        // Add the site name.
        $title .= get_bloginfo( 'name' );

        // Add the site description for the home/front page.
        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) )
            $title = "$title $sep $site_description";

        // Add a page number if necessary.
        if ( $paged >= 2 || $page >= 2 )
            $title = "$title $sep " . sprintf( __fe( 'Page %s' ), max( $paged, $page ) );

        return $title;
    }
}


/*
 * Serves all post meta: author, date, comments, tags, categories
 */
if(!function_exists('a13_post_meta')){
    function a13_post_meta() {
        global $apollo13;

        $single     = is_single();
        $post_list  = a13_is_post_list();
        $work       = get_post_type() == A13_CUSTOM_POST_TYPE_WORK;
        $return     = '';

        //return date
        if(
            ($single && $apollo13->get_option('blog', 'post_date') == 'on')
            ||
            ($post_list && $apollo13->get_option('blog', 'blog_date') == 'on')
        ){
            $return = a13_posted_on();
        }

        //return author
        if(
            ($single && $apollo13->get_option('blog', 'post_author') == 'on')
            ||
            ($post_list && $apollo13->get_option('blog', 'blog_author') == 'on')
        ){
            $return .= a13_posted_by_author();
        }

        //return categories
        if(
            ($single && $apollo13->get_option('blog', 'post_cats') == 'on')
            ||
            ($post_list && $apollo13->get_option('blog', 'blog_cats') == 'on')
        ){
            $return .= a13_post_categories().' ';
        }

        //return tags
        if(
            ($single && $apollo13->get_option('blog', 'post_tags') == 'on')
            ||
            ($post_list && $apollo13->get_option('blog', 'blog_tags') == 'on')
        ){
            $return .= a13_post_tags();
        }

        //return taxonomy for works
        if($work){
            $return .= a13_cpt_work_posted_in('');
        }

        //return comments number
        if(
            ($single && $apollo13->get_option('blog', 'post_comments') == 'on')
            ||
            (a13_is_post_list() && $apollo13->get_option('blog', 'blog_comments') == 'on')
        ){
            $return .= '/ '.a13_post_comments();
        }

        if(strlen($return)){
            echo '<div class="post-meta">'.$return.'</div>';
        }
    }
}


/*
 * Date of post
 */
if(!function_exists('a13_posted_on')){
    function a13_posted_on( $intro_text = true ) {
        return $intro_text ?
            sprintf( __fe('Posted on <time class="entry-date" datetime="%1$s">%2$s</time> '), get_the_date( 'c' ), get_the_date())
            :
            '<time class="entry-date" datetime="'.get_the_date( 'c' ).'">'.get_the_date().'</time>';
    }
}


/*
 * Author of post
 */
if(!function_exists('a13_posted_by_author')){
    function a13_posted_by_author() {
        return
            sprintf( __fe( 'by <a class="author" href="%1$s" title="%2$s">%3$s</a> ' ),
                esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) )),
                sprintf( esc_attr( __fe( 'View all posts by %s' ) ), get_the_author() ),
                get_the_author()
            );
    }
}


/*
 * comments & edit links
 */
if(!function_exists('a13_post_comments')){
    function a13_post_comments() {
        return '<span class="comments"><a href="' . esc_url(get_comments_link()) . '" title="'
            . sprintf(__fe( '%d Comment(s)' ), get_comments_number()) . '">'
            . sprintf(__fe( '%d Comment(s)' ), get_comments_number()) . '</a></span>';
    }
}


/*
 * Post tags
 */
if(!function_exists('a13_post_tags')){
    function a13_post_tags() {
        $tags = '';
        $tag_list = get_the_tag_list( '',', ' );
        if ( $tag_list ) {
            $tags = sprintf( __fe('<span class="tags">tagged %s</span> '), $tag_list );
        }

        return $tags;
    }
}


/*
 * Categories that post was posted in
 */
if(!function_exists('a13_post_categories')){
    function a13_post_categories( ) {
        $cats = '';
        $cat_list = get_the_category_list(', ');
        if ( $cat_list ) {
            $cats = sprintf( __fe('<span class="cats">in %s</span> '), $cat_list );
        }

        return $cats;
    }
}


/*
 * Categories and Tags that post was posted in
 */
if(!function_exists('a13_posted_in')){
    function a13_posted_in() {
        global $apollo13;

        $return = '';
        $single = is_single();
        $post_list = a13_is_post_list();
        $work = get_post_type() == A13_CUSTOM_POST_TYPE_WORK;

        //return categories
        if(
            ($single && $apollo13->get_option('blog', 'post_cats') == 'on')
            ||
            ($post_list && $apollo13->get_option('blog', 'blog_cats') == 'on')
        ){
            $return .= a13_post_categories().' ';
        }

        //return tags
        if(
            ($single && $apollo13->get_option('blog', 'post_tags') == 'on')
            ||
            ($post_list && $apollo13->get_option('blog', 'blog_tags') == 'on')
        ){
            $return .= a13_post_tags();
        }

        //return taxonomy for works
        if($work){
            $return .= a13_cpt_work_posted_in(', ');
        }

        if(strlen(trim($return))) //trim if only space is present(page type in search result for example)
            $return = '<span class="posted-in">'.$return.'</span>';

        return $return;
    }
}


/*
* Return subtitle for page/post
*/
if(!function_exists('a13_subtitle')){
    function a13_subtitle($tag = 'h2') {
        $s = get_post_meta(get_the_ID(), '_subtitle', true);
        if(strlen($s))
            $s = '<'.$tag.'>'.$s.'</'.$tag.'>';

        return $s;
    }
}


/*
* Modify password form
*/
if(!function_exists('a13_custom_password_form')){
    function a13_custom_password_form($content) {
        //copy of function
        //get_the_password_form()
        //from \wp-includes\post-template.php ~1222
        //with small changes
//        $post = get_post();

        $output = '
        <form class="password-form"  action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
            <p>' . __fe("This post is password protected. To view it please enter your password below:") . '</p>
            <p class="inputs"><input name="post_password" type="password" size="20" /><input type="submit" name="Submit" value="' . esc_attr(__fe("Submit")) . '" /></p>
        </form>
        ';

        return $output;
    }
}


/*
* Checks if current page has active sidebar
 * returns false if there is no active sidebar,
 * if there is active sidebar it returns its name
*/
if(!function_exists('a13_has_active_sidebar')){
    function a13_has_active_sidebar() {
        global $apollo13;
        $test = '';
        $shop = a13_is_woocommerce();

        if(!$shop && (is_home() || is_archive() || is_search() || defined('A13_NO_STYLED_PAGE') )){
            $test = 'blog-widget-area';
        }
        elseif(defined('A13_WORK_PAGE')){}
        elseif(!$shop &&  is_single() ){
            $test = 'post-widget-area';
        }
        elseif($shop || is_page() ){
            $test = 'page-widget-area';
            $meta_id = $shop? woocommerce_get_page_id( 'shop' ) : get_the_ID();
            $custom_sidebar = $apollo13->get_meta('_sidebar_to_show', $meta_id);
            if(strlen($custom_sidebar) && $custom_sidebar !== 'default'){
                $test = $custom_sidebar;
            }

            //if has children nav and it is activated then sidebar is active
            $sidebar_meta = $apollo13->get_meta('_widget_area', $meta_id);
            if(strrchr($sidebar_meta, 'nav') && a13_page_menu(true)){
                return $test;
            }
        }

        if( is_active_sidebar($test)){
            return $test;
        }
        else{
            return false;
        }
    }
}


/*
* Get classes for body element
*/
if(!function_exists('a13_body_classes')){
    function a13_body_classes( $classes ) {
        global $apollo13, $wp_version;

        //WP version above or equal 3.8
        if(version_compare( $wp_version, '3.8', '>=')){
            $classes[] = 'wp-3_8';
        }

        //adding woocommerce class so mini cart will look good
        //if this will produce any issues then we will need to remove it
        //and style mini cart independent
        if(a13_is_woocommerce_activated()){
            $classes[] = 'woocommerce';
        }

        //forms validation
        $classes[] = ($apollo13->get_option( 'advanced', 'apollo_validation' ) == 'on')? A13_VALIDATION_CLASS : '';

        //layout style
        $classes[] = 'layout-'.$apollo13->get_option( 'appearance', 'layout_style' );
        //background fit
        $classes[] = $apollo13->get_option( 'appearance', 'body_image_fit' );

        //protected album
        if(defined('A13_PAGE_PROTECTED')){
            $classes[] = 'password-protected';
        }

        if(defined('A13_WORK_PAGE')){
        }

        if(defined('A13_WORKS_LIST_PAGE')){
            $classes[] = 'works-list-page';
        }

        if(defined('A13_GALLERIES_LIST_PAGE')){
            $classes[] = 'galleries-list-page';
        }

        if(is_page_template('contact.php') || is_page_template('contact2.php')){
            if(is_page_template('contact2.php')){
                $classes[] = 'map-in-content';
            }
            $classes[] = 'contact-page';
        }

        //page with posts list
        if(a13_is_post_list() && !defined('A13_NO_STYLED_PAGE'))
            $classes[] = 'posts-list';

        //no results page
        if(defined('A13_NO_STYLED_PAGE'))
            $classes[] = 'no-results';

        return $classes;
    }
}


/*
* Get classes for mid element
*/
if(!function_exists('a13_get_mid_classes')){
    function a13_get_mid_classes() {
        global $apollo13;

        //mid classes for type of layout align and widget area display(on/off)
        $mid_classes = '';
        //404 error page, no-result page, etc.
        $is_empty_page = defined('A13_NO_STYLED_PAGE');

        $page_type = a13_what_page_type_is_it();
//        var_dump($page_type);
        $page = $page_type['page'];
        $shop = a13_is_woocommerce();

        //check if there is active sidebar for current page
        $force_full_width = false;
        if( $page_type['cpt_list'] || //it is page, so it can gain page sidebar
            $page_type['cpt']       || //it doesn't have sidebar
            a13_has_active_sidebar() === false
        ){
            $force_full_width = true;
        }

        function __inner_a13_set_full_width(&$mid_classes){
            global $content_width;
            define('A13_FULL_WIDTH', true); /* so we don't have to check again in sidebar.php */
            $mid_classes .= ' no-sidebars';
            //content width
            $content_width = 1080;
        }
        function __inner_a13_set_sidebar_class(&$mid_classes, $sidebar){
            if(($sidebar == 'off')){
                __inner_a13_set_full_width($mid_classes);
            }
            else{
                $mid_classes .= ' '.$sidebar;
            }
        }

        /*
         * content padding classes
         * */
        if($page){
            $padding = $apollo13->get_meta('_content_padding');
            if($padding === 'top'){
                $mid_classes .= ' no-bottom-space';
            }
            elseif($padding === 'bottom'){
                $mid_classes .= ' no-top-space';
            }
            elseif($padding === 'off'){
                $mid_classes .= ' no-top-space no-bottom-space';
            }
        }

        /*
         * sidebar classes
         * */
        if($page && ($apollo13->get_meta('_full_width_elements') === 'on')){
            __inner_a13_set_full_width($mid_classes);
            $mid_classes .= ' full-width-elements';
        }
        elseif($force_full_width){
            __inner_a13_set_full_width($mid_classes);
        }
        //blog | attachment | or empty page
        elseif(!$shop && ($page_type['home'] || $page_type['attachment'] || $is_empty_page)){
            __inner_a13_set_sidebar_class($mid_classes, $apollo13->get_option('blog', 'blog_sidebar'));
        }
        //archive | search
        elseif(!$shop && ($page_type['archive'] || $page_type['search'])){
            __inner_a13_set_sidebar_class($mid_classes, $apollo13->get_option('blog', 'archive_sidebar'));
        }
        //single post
        elseif(!$shop && ($page_type['single'])){
            __inner_a13_set_sidebar_class($mid_classes, $apollo13->get_meta('_widget_area'));
        }
        //single page
        elseif($page || $shop){
            //special treatment cause of children menu option
            $meta_id = $shop? woocommerce_get_page_id( 'shop' ) : get_the_ID();
            $sidebar = $apollo13->get_meta('_widget_area', $meta_id);
            if(strrchr($sidebar, 'left')){
                $sidebar = 'left-sidebar';
            }
            elseif(strrchr($sidebar, 'right')){
                $sidebar = 'right-sidebar';
            }
            __inner_a13_set_sidebar_class($mid_classes, $sidebar);
        }

        return $mid_classes;
    }
}


/*
 * Prints HTML for breadcrumbs and search form
 */
if(!function_exists('a13_header_tools')){
    function a13_header_tools() {
        global $apollo13;

        $page_type = a13_what_page_type_is_it();

        //id from where
        $meta_id = false;

        if(a13_is_woocommerce()){
            $meta_id = woocommerce_get_page_id( 'shop' );
        }
        elseif($page_type['single_not_post']){
            $meta_id = get_the_ID();
        }
        elseif(($page_type['blog_type'] || $page_type['single']) && get_option( 'page_for_posts') !== '0'){
            $meta_id = get_option( 'page_for_posts');
        }

        //is it OFF?
//        var_dump($meta_id, $page_type,); //robimy breadcrumbsy
        if(!is_search() && !is_404()){ //fast fix for notices
            if($apollo13->get_meta('_header_tools', $meta_id) === 'off'){
                return;
            }
        }

        if(function_exists('bcn_display')){
            echo '<div class="header-tools">';

            if(function_exists('bcn_display')){
                echo '<div class="breadcrumbs">';
                bcn_display();
                echo '</div>';
            }

            echo get_search_form();

            echo '</div>';
        }
    }
}

/*
 * Prints side menu for static pages that has parents or children
 */
if(!function_exists('a13_page_menu')){
    function a13_page_menu($only_check = false) {
        global $post;

        $there_is_menu = false;

        $has_children_args = array(
            'post_parent' => $post->ID,
            'post_status' => 'publish',
            'post_type' => 'any',
        );

        $list_pages_params = array(
            'child_of'      => $post->post_parent,
            'sort_column'   => 'menu_order',
            'depth'         => 0,
            'title_li'      => '',
            'walker'        => new A13_list_pages_walker
        );

        if(a13_is_subpage()){
            if($only_check){ return true; }
            $there_is_menu = true;
        }
        elseif(get_children( $has_children_args )){
            if($only_check){ return true; }
            $list_pages_params['child_of'] = $post->ID;
            $there_is_menu = true;
        }

        //display menu
        if($there_is_menu){
            echo '<div class="widget a13_page_menu widget_nav_menu">
                    <ul>';

                wp_list_pages($list_pages_params);

            echo '</ul>
                </div>';
        }
        return false;
    }
}


/*
 * Gets logo image and check for HIGH DPI cookie
 */
if(!function_exists('a13_header_logo_image')){
    function a13_header_logo_image() {
        global $apollo13;
        $style = $temp ="";
        $src = $apollo13->get_option( 'appearance', 'logo_image' );
        $screen_type = isset($_COOKIE["a13_screen_size"])? $_COOKIE["a13_screen_size"] : 'normal';

        if($screen_type === 'high'){
            $temp = $apollo13->get_option( 'appearance', 'logo_image_high_dpi' );
            if(strlen($temp)){
                $src = $temp;
                $style = explode('|', $apollo13->get_option( 'appearance', 'logo_image_high_dpi_sizes' ));
                //we compare dimensions to set one to auto, so on mobile it will shrink proper
                $width = ceil(intval($style[0])/2);
                $height = ceil(intval($style[1])/2);
                if($width>$height){
                    $height = 'auto';
                    $width .= 'px';
                }
                else{
                    $width = 'auto';
                    $height .= 'px';
                }

                //we prepare inline style
                $style = 'width:'.$width.';height:'.$height.';';
            }
        }
        echo '<img src="'.esc_url($src).'" style="'.$style.'" alt="'. esc_attr( get_bloginfo( 'name', 'display' ) ).'" />';
    }
}


/*
 * Prints favicon
 */
if(!function_exists('a13_favicon')){
    function a13_favicon() {
        global $apollo13;
        $fav_icon = $apollo13->get_option( 'appearance', 'favicon' );
        if(!empty($fav_icon))
            echo '<link rel="shortcut icon" href="'.esc_url($fav_icon).'" />';
    }
}


/*
 * Prints search form with custom id for each displayed form one one page
 */
if(!function_exists('a13_search_form')){
    function a13_search_form() {
        static $search_id = 1;
        $helper_search = get_search_query() == '' ? true : false;
        $field_search = '<input' .
            ' placeholder="' . esc_attr(__fe('Search' )) . '" ' .
            'type="search" name="s" id="s' . $search_id . '" value="' .
            esc_attr( $helper_search ? '' : get_search_query() ) .
            '" />';

        $form = '
                <form class="search-form" role="search" method="get" action="' . home_url( '/' ) . '" >
                    <fieldset class="semantic">
                        ' . $field_search . '
                        <input type="submit" id="searchsubmit' . $search_id . '" title="'. esc_attr( __fe('Search' ) ) .'" value=" " />
                    </fieldset>
                </form>';

        //next call will have different ID
        $search_id++;
        return $form;
    }
}



/*
 * Header search form
 */
if(!function_exists('a13_header_search')){
    function a13_header_search() {
        echo
            '<div class="search-container">'.
                '<div class="search">'.
                    '<span class="fa fa-search open action"></span>';
                    get_search_form();
        echo
                    '<span class="fa fa-times close action"></span>'.
                '</div>'.
            '</div>';
    }
}



/*
 * Header top bar
 */
if(!function_exists('a13_header_top_bar')){
    function a13_header_top_bar() {
        global $apollo13, $woocommerce;
        $color      = $apollo13->get_option( 'appearance', 'top_bar_color' );
        $text       = $apollo13->get_option( 'appearance', 'top_bar_text' );
        $socials    = $apollo13->get_option( 'appearance', 'top_bar_socials' ) === 'on';
        ?>
        <div class="top-bar-container <?php echo $color; ?>">
        
            <div class="top-bar">
		<div id="menuaqui">
		<div id="situaciomenu-somenergia">
	
		<?php
                    
                        wp_nav_menu( array(
                                'menu_class'      => 'situaciomenu-somenergia',
                                'theme_location'  => 'situaciomenu' )
                        );
                    
                   ?>
</div></div>
                <?php if (strlen($text)){ echo '<div class="contact-text">'.$text.'</div>'; } ?>
		<div id="menudestacat"><?php
                    
                        wp_nav_menu( array(
                                'menu_class'      => 'menudestacat-somenergia',
                                'theme_location'  => 'menudestacat' )
                        );
                    
                   ?></div>
                <?php if ($socials) { echo a13_social_icons(); } ?>
		<div id="contentidiomas">
		<div id="selector"><?php do_action('icl_language_selector'); ?></div>
		<div id="menuidiomas-somenergia"><?php
                                wp_nav_menu( array(
                                'menu_class'      => 'menuidiomas-somenergia',
                                'theme_location'  => 'menuidiomas' )
                        );
                   ?></div>
		</div>
                <?php
                if (a13_is_woocommerce_activated()) {
                    echo '<div id="wc-header-cart" class="wc-header-cart">';
                        echo '<div class="inside">';
                            echo '<a href="'.$woocommerce->cart->get_cart_url().'" class="cart-link"><i class="fa fa-shopping-cart"></i><span>'.__fe('My cart').'</span></a>';
                            echo '<div class="mini-cart">';
                                echo '<div class="widget_shopping_cart_content">';
                                    woocommerce_mini_cart();
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        <?php
    }
}



/*
 * Sets the post excerpt length to 30 words.
 */
if(!function_exists('a13_excerpt_length')){
    function a13_excerpt_length( $length ) {
        return 30;
    }
}


/*
* This filter is used by wp_trim_excerpt() function.
* By default it set to echo '[...]' more string at the end of the excerpt.
*/
if(!function_exists('a13_new_excerpt_more')){
    function a13_new_excerpt_more($more) {
        global $post;
        return '<p><a class="more-link" href="'. esc_url(get_permalink($post->ID)) . '">' . __fe('Read more' ) . '</a></p>';
    }
}


/*
* Make excerpt for comments
* used in widgets
*/
if(!function_exists('a13_get_comment_excerpt')){
    function a13_get_comment_excerpt($comment_ID = 0, $num_words = 20) {
        $comment = get_comment( $comment_ID );
        $comment_text = strip_tags($comment->comment_content);
        $blah = explode(' ', $comment_text);
        if (count($blah) > $num_words) {
            $k = $num_words;
            $use_dotdotdot = 1;
        } else {
            $k = count($blah);
            $use_dotdotdot = 0;
        }
        $excerpt = '';
        for ($i=0; $i<$k; $i++) {
            $excerpt .= $blah[$i] . ' ';
        }
        $excerpt .= ($use_dotdotdot) ? '[...]' : '';
        return apply_filters('get_comment_excerpt', $excerpt);
    }
}


/*
* It replaces WP default action while closing children comments block
* Useful to save your nerves
*/
if(!function_exists('a13_comment_end')){
    function a13_comment_end( $comment, $args, $depth ) {
        echo '</div>';
        return;
    }
}


/*
* Changes default comment template
* Closing </div> for this block is produced by comment_end()
* It is strange, I know :-)
*/
if(!function_exists('a13_comment')){
    function a13_comment( $comment, $args, $depth ) {
        $GLOBALS['comment'] = $comment;

        switch ( $comment->comment_type ) :
            case '' :
                ?>
                    <div <?php comment_class( 'comment-block' ); ?> id="comment-<?php comment_ID(); ?>">

                        <a class="avatar" href="<?php esc_url(get_comment_author_url()); ?>" title=""><?php echo get_avatar( $comment, 50 ) ; ?></a>
                        <div class="comment-inside">
                            <div class="comment-info">
                                <span class="author"><?php comment_author_link(); ?></span>
                                <?php
                                    printf( '<a class="time" href="%1$s"><time datetime="%2$s">%3$s</time></a>',
                                        esc_url( get_comment_link( $comment->comment_ID ) ),
                                        esc_attr(get_comment_time( 'c' )),
                                        /* translators: 1: date, 2: time */
                                        sprintf( __fe( '%1$s at %2$s' ), get_comment_date(), get_comment_time() )
                                    );
                                    comment_reply_link( array_merge( $args, array( 'before' => ' <span class="sepa">/</span> ', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
                                ?>
                            </div>
                            <div class="comment-text">
                                <?php if ( $comment->comment_approved == '0' ) : ?>
                                <p><em class="comment-awaiting-moderation"><?php _fe( 'Your comment is awaiting moderation.' ); ?></em></p>
                                <?php endif; ?>
                                <?php comment_text(); ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                    <?php
                break;
            case 'pingback'  :
            case 'trackback' :
                ?>
            <div <?php comment_class( 'comment-block' ); ?> id="comment-<?php comment_ID(); ?>">
                <div class="comment-inside clearfix">
                    <p><?php _fe( 'Pingback:' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( '(' . __fe( 'Edit' ) . ')', ' ' ); ?></p>
                </div>
                    <?php
                break;
        endswitch;
    }
}


/*
* Displays menu button
*/
if(!function_exists('a13_menu_button')){
    function a13_menu_button(){
        global $apollo13;
        $on = $apollo13->get_option( 'appearance', 'menu_button' ) === 'on';
        $url = $apollo13->get_option( 'appearance', 'menu_button_url' );
        $text = $apollo13->get_option( 'appearance', 'menu_button_text' );
        $new_tab = $apollo13->get_option( 'appearance', 'menu_button_new_tab' ) === '1'? ' target="_blank"' : '';

        if($on && (strlen($url) || strlen($text))){
            echo '<div class="menu-button-container"><a href="'.esc_url( $url ).'" class="menu-button a13-button"'.$new_tab.'>'.$text.'</a></div>';
        }
    }
}


/*
* Displays header menu
*/
if(!function_exists('a13_header_menu')){
    function a13_header_menu(){
        /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.
         * The menu assiged to the primary position is the one used.
         * If none is assigned, the menu with the lowest ID is used.
         */
        if ( has_nav_menu( 'header-menu' ) ):
            wp_nav_menu( array(
                    'container'       => false,
                    'link_before'     => '<span>',
                    'link_after'      => '</span>',
                    'menu_class'      => 'top-menu',
                    'theme_location'  => 'header-menu',
                    'walker'          => new A13_menu_walker)
            );
        else:
            echo '<ul class="top-menu">';
            wp_list_pages(
                array(
                    'link_before'     => '<span>',
                    'link_after'      => '</span>',
                    'title_li' 		  => ''
                )
            );
            echo '</ul>';
        endif;
    }
}

class A13_menu_walker extends Walker_Nav_Menu {

    /**
     * @see Walker::start_el()
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item Menu item data object.
     * @param int $depth Depth of menu item. Used for padding.
     * @param object $args
     * @param int $id Menu item ID.
     */
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        static $mega_menu = false;
        static $mega_menu_counter = 0;
        static $mm_columns = 1;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $value = '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        //mega_menu
        $dont_link = false;
        if($depth === 0){
            if($item->a13_mega_menu === '1'){
                $mega_menu_counter = 0;
                $mega_menu = true;
                $mm_columns = $item->a13_mm_columns;
                $classes[] = 'mega-menu';
                $classes[] = 'mm_columns_'.$item->a13_mm_columns;
            }
            else{
                $mega_menu = false;
                $classes[] = 'normal-menu';
            }
        }
        if($depth === 1 && $mega_menu){
            if($mega_menu_counter % $mm_columns === 0){
                $classes[] = 'mm_new_row';
            }
            if($item->a13_mm_remove_item === '1'){
                $classes[] = 'mm_dont_show';
            }
            if($item->a13_mm_unclickable === '1'){
                $dont_link = true;
            }

            $mega_menu_counter++;
        }

        //checks if this element is parent element
        $is_parent = (bool)array_search('menu-parent-item', $classes);

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';



        $output .= $indent . '<li' . $id . $value . $class_names .'>';

        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

        $item_output = $args->before;
        $item_output .= $dont_link? '<span class="title">' : '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= $is_parent? '<i class="arr fa '.($depth === 0? 'fa-angle-down' : 'fa-angle-right').'"></i>' : '';
        $item_output .= $dont_link? '</span>' : '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

class A13_widget_menu_walker extends Walker_Nav_Menu {

    /**
     * @see Walker::start_el()
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item Menu item data object.
     * @param int $depth Depth of menu item. Used for padding.
     * @param object $args
     * @param int $id Menu item ID.
     */
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $value = '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $menu_icon = $depth === 0? trim(get_post_meta($item->object_id, '_menu_icon', true)) : '';
        //checking for icons
        if(strlen($menu_icon)){
            $icons = explode(' ', $menu_icon);
            $menu_icon = '';
            foreach($icons as $icon){
                if(strlen($icon))
                    $menu_icon .= '<i class="fa '.esc_attr( $icon ).'"></i>';
            }
        }

        $output .= $indent . '<li' . $id . $value . $class_names .'>';

        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= strlen($menu_icon)? '<span class="m_icon">'.$menu_icon.'</span>' : '';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

class A13_list_pages_walker extends Walker_Page {
    /**
     * @see Walker::start_el()
     * @since 2.1.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $page Page data object.
     * @param int $depth Depth of page. Used for padding.
     * @param int $current_page Page ID.
     * @param array $args
     */
    function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
        if ( $depth )
            $indent = str_repeat("\t", $depth);
        else
            $indent = '';

        extract($args, EXTR_SKIP);
        $css_class = array('page_item', 'page-item-'.$page->ID);
        if ( !empty($current_page) ) {
            $_current_page = get_post( $current_page );
            if ( in_array( $page->ID, $_current_page->ancestors ) )
                $css_class[] = 'current_page_ancestor';
            if ( $page->ID == $current_page )
                $css_class[] = 'current_page_item';
            elseif ( $_current_page && $page->ID == $_current_page->post_parent )
                $css_class[] = 'current_page_parent';
        } elseif ( $page->ID == get_option('page_for_posts') ) {
            $css_class[] = 'current_page_parent';
        }

        $css_class = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );

        $menu_icon = $depth === 0? trim(get_post_meta($page->ID, '_menu_icon', true)) : '';
        //checking for icons
        if(strlen($menu_icon)){
            $icons = explode(' ', $menu_icon);
            $menu_icon = '';
            foreach($icons as $icon){
                if(strlen($icon))
                    $menu_icon .= '<i class="fa '.esc_attr( $icon ).'"></i>';
            }
        }

        $output .= $indent . '<li class="' . $css_class . '"><a href="' . get_permalink($page->ID) . '">'
                .(strlen($menu_icon)? '<span class="m_icon">'.$menu_icon.'</span>' : '')
                .'<em class="icon-angle-right fa fa-angle-right"></em>'
                . $link_before . apply_filters( 'the_title', $page->post_title, $page->ID ) . $link_after . '</a>';

        //$show_date & $link_before are from extract function running
        if ( !empty($show_date) ) {
            if ( 'modified' == $show_date )
                $time = $page->post_modified;
            else
                $time = $page->post_date;

            $output .= " " . mysql2date($date_format, $time);
        }
    }
}


//adds menu-parent-item class to parent elements in menu
if(!function_exists('a13_add_menu_parent_class')){
    function a13_add_menu_parent_class( $items ) {

        $parents = array();
        foreach ( $items as $item ) {
            if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
                $parents[] = $item->menu_item_parent;
            }
        }

        foreach ( $items as $item ) {
            if ( in_array( $item->ID, $parents ) ) {
                $item->classes[] = 'menu-parent-item';
            }
        }

        return $items;
    }
}


//compare positions, used only in social widget
function a13_cmp_socials_positions($a, $b){
    if ($a['pos'] == $b['pos']) {
        return 0;
    }
    return ($a['pos'] < $b['pos']) ? -1 : 1;
}

/*
* Return HTML for social icons
*/
if(!function_exists('a13_social_icons')){
    function a13_social_icons(){
        global $apollo13;

        $socials = (array)$apollo13->get_option( 'socials', 'social_services' );
        uasort($socials, "a13_cmp_socials_positions");
        $soc_html = '';
        $has_active = false;
        $protocols = wp_allowed_protocols();
        $protocols[] = 'skype';

        foreach( $socials as $id => $value ){
            if( ! empty($value['value']) ){
                $soc_html .= '<a target="_blank" href="' . esc_url($value['value'], $protocols) . '" title="' . esc_attr(__fe( 'Follow us on ' ) . $apollo13->all_theme_options[ 'socials' ][ 'social_services' ][ $id ]['name']) . '" class="a13_soc-'.$id.'"></a>';
                $has_active = true;
            }
        }

        if($has_active){
            $soc_html = '<div class="socials">'.$soc_html.'</div>';
        }

        return $soc_html;
    }
}

/*
* Prints similar posts to current post/work
*/
if(!function_exists('a13_similar_posts')){
    function a13_similar_posts(){
        global $apollo13, $post;

        $is_work = defined('A13_WORK_PAGE');
        $widget_title = __fe('Similar Posts');

        //if deactivated then we have nothing to do here
        if(($is_work && $apollo13->get_option( 'cpt_work', 'posts_widget' ) !== 'on')
            ||
            (!$is_work && $apollo13->get_option( 'blog', 'posts_widget' ) !== 'on')){
            return;
        }

        if($is_work){
            $__search = wp_get_post_terms(get_the_ID(), A13_CPT_WORK_TAXONOMY, array("fields" => "slugs"));
            $widget_title = __fe('Similar Works');
        }
        else{
            $__search = wp_get_post_tags($post->ID);
            $search_string = 'tags__in';
            //if no tags try categories
            if( !count($__search) ){
                $__search = wp_get_post_categories($post->ID);
                $search_string = 'category__in';
            }
        }

        if ( count($__search) ) {
            //search query
            if($is_work){
                $r = new WP_Query(
                    array(
                        'post_type' => A13_CUSTOM_POST_TYPE_WORK,
                        'tax_query' => array(
                            array(
                                'taxonomy' => A13_CPT_WORK_TAXONOMY,
                                'field' => 'slug',
                                'terms' => $__search,
                                'operator' => 'IN'
                            )
                        ),
                        'post__not_in' => array($post->ID),
                        'posts_per_page' => 4,
                        'no_found_rows' => true,
                        'post_status' => 'publish',
                        'ignore_sticky_posts' => true)
                );
            }
            else{
                $r = new WP_Query(
                    array(
                        $search_string => $__search,
                        'post__not_in' => array($post->ID),
                        'posts_per_page' => 3,
                        'no_found_rows' => true,
                        'post_status' => 'publish',
                        'ignore_sticky_posts' => true)
                );
            }

            if ($r->have_posts()) :
                echo '
                            <div class="in_post_widget clearfix'.($is_work? ' variant_3 classic' : '').'">
                                <h3 class="title widget-title"><span>'.$widget_title.'</span></h3>
                                <div class="widget-inside">';

                while ($r->have_posts()) : $r->the_post();
                    if($is_work){
                        $post_title     = get_the_title();
                        $post_id        = get_the_ID();
                        $post_subtitle  = get_post_meta($post_id, '_subtitle', true);
//                        $image_size     = defined('A13_FULL_WIDTH')? 'work-cover-big' : 'work-cover-mid' ;
                        $image_size     = 'work-cover-mid';
                        $thumbnail      = a13_make_post_image( get_the_ID(), $image_size );

                        if(strlen($post_subtitle)){
                            $post_subtitle = '<small>'.$post_subtitle.'</small>';
                        }

                        echo '
                            <div class="g-item ready">
                                <a class="g-link" href="'.esc_url(get_permalink()).'" id="album-' . $post_id . '">'
                                    .'<i>'.$thumbnail.'</i>'
                                    .'<em class="cov"><span><strong>'.$post_title.'</strong>'.$post_subtitle.'</span></em>'
                                .'</a>';

                        //like plugin
                        if( function_exists('dot_irecommendthis') ){
                            dot_irecommendthis();
                        }

                        echo '</div>';
                    }
                    else{
                        $page_title = get_the_title();
                        $class = ''; //empty for easily commenting out
                        $image_size = 'apollo-post-brick' ;
                        $img = a13_make_post_image( get_the_ID(), $image_size );

                        echo '<div class="item'.$class.'">';

                        if(strlen($img)){
                            echo '
                                        <div class="item-image post-media">
                                            <a href="' . get_permalink() . '" title="' . esc_attr($page_title) . '">' . $img . '<em></em></a>
                                        </div>';
                        }

                        echo
                            '<a class="post-title" href="' . get_permalink() . '" title="' . esc_attr($page_title) . '">' . $page_title . '</a>'
                            .'<div class="post-meta">'.a13_posted_on().'</div>'
//                            .'<a class="comments" href="' . get_comments_link() . '" title="' . get_comments_number() . ' ' . __fe( 'comment(s)' ). '">'.get_comments_number().' '.__fe( 'comment(s)' ).'</a>'
                        ;

                        echo '</div>';
                    }

                endwhile;

                echo '</div></div>';

                // Reset the global $the_post as this query will have stomped on it
                wp_reset_postdata();

            endif;
        }
    }
}



/*
* Function that return featured image or video for post
*/
if(!function_exists('a13_top_image_video')){
    function a13_top_image_video($link_it = false, $width = 'auto', $height = 0){
        global $apollo13;

        $is_post = is_single();
        $is_page = is_page();
        $is_post_list = a13_is_post_list();

        //check if media should be displayed
        if(
            ($is_post && $apollo13->get_option('blog', 'post_media') == 'off')
            ||
            ($is_post_list && $apollo13->get_option('blog', 'blog_media') == 'off')
        )
            return;

        $post_id =  get_the_ID();
        $img_or_vid = get_post_meta($post_id, '_image_or_video', true);
        $blog_variant = $apollo13->get_option('blog', 'blog_variant');
        $align = '';
        $size = '';

        if( empty( $img_or_vid ) || $img_or_vid == 'post_image' ){
            $thumb_size = 'apollo-post-thumb';

            if($is_post || $is_page){
                $align = ' '.get_post_meta($post_id, '_image_stretch', true);
                $size = get_post_meta($post_id, '_image_size', true);

                if($size == 'big') $thumb_size = 'apollo-post-thumb-big';
                elseif($size == 'original') $thumb_size = 'full';
                elseif($size == 'auto'){
                    if( defined('A13_FULL_WIDTH') || $apollo13->get_meta( '_widget_area' ) == 'off'){
                        $thumb_size = 'apollo-post-thumb-big';
                    }
                }
            }
            elseif($is_post_list){
                if($blog_variant === 'variant_short_list'){
                    $thumb_size = 'apollo-post-short_list';
                }
                elseif($blog_variant === 'variant_masonry'){
                    $thumb_size = 'apollo-post-masonry_blog';
                }
            }

            $img = a13_make_post_image($post_id, $thumb_size);

            if( !empty( $img ) ){
                if($link_it){
                    $img = '<a href="'.esc_url(get_permalink()).'">'.$img.'<em></em></a>';
                }
                ?>
                <div class="item-image post-media<?php echo $align; ?>">
                    <?php echo $img; ?>
                </div>
                <?php
            }
        }

        elseif( $img_or_vid == 'post_video' ){
            if($width === 'auto'){
                $width = 600;
            }

            if($is_post || $is_page){
                $align = ' '.get_post_meta($post_id, '_video_align', true);
                $size = get_post_meta($post_id, '_video_size', true);
                if($size == 'big') $width = 960;
            }

            $src = get_post_meta($post_id, '_post_video', true);
            if( !empty( $src ) ){
                ?>
                <div class="item-video post-media width-<?php echo $size.$align; ?>">
                    <?php
                    if( $height == 0){
                        $height = ceil((9/16) * $width);
                    }

                    $v_code = wp_oembed_get($src, array(
                        'width' => $width,
                        'height' => $height
                        )
                    );

                    //if no code, try theme function
                    if($v_code === false){
                        echo a13_get_movie($src, $width, $height);
                    }
                    else{
                        echo $v_code;
                    }
                    ?>
                </div>
                <?php
            }
        }
    }
}


/*
 * Pagination for blog pages
 */
if(!function_exists('a13_blog_nav')){
    function a13_blog_nav() {
        //if WP Painate plugin is installed and active
        if(function_exists('wp_paginate')) {
            wp_paginate();
        }
        //theme pagination
        else{
            global $paged, $wp_query;
            //safe copy for operations
            $c_paged = $paged;

            $max_page = $wp_query->max_num_pages;

            if ( $max_page > 1 ) : ?>
                <div id="posts-nav" class="navigation">
                    <?php
                    echo '<span class="nav-previous">';
                    previous_posts_link( __fe( 'Previous' ) );
                    echo '</span>';

                    //if first page
                    if($c_paged === 0){
                        $c_paged = 1;
                    }
                    for($page = 1; $page <= $max_page; $page++){
                        if($page == $c_paged)
                            echo '<span class="current">'.$page.'</span>';
                        else
                            echo '<a href="'.esc_url(get_pagenum_link($page)).'" title="'.esc_attr($page).'">'.$page.'</a>';
                    }

                    echo '<span class="nav-next">';
                    next_posts_link( __fe( 'Next' ) );
                    echo '</span>';
                    ?>
                </div>
            <?php endif;
        }
    }
}


/*
 * Returns array with type of current page
 */
if(!function_exists('a13_what_page_type_is_it')){
    function a13_what_page_type_is_it() {
        static $types;

        if ( empty( $types ) ) {
            $types = array(
                'page'          => is_page(),
                'work'          => defined('A13_WORK_PAGE'),
                'gallery'       => defined('A13_GALLERY_PAGE'),
                'home'          => is_home(),
                'front_page'    => is_front_page(),
                'archive'       => is_archive(),
                'search'        => is_search(),
                'single'        => is_single(),
                'attachment'    => is_attachment(),
                'works_list'    => defined('A13_WORKS_LIST_PAGE'),
                'galleries_list'=> defined('A13_GALLERIES_LIST_PAGE')
            );

            $types['single_not_post']   = $types['page'] || $types['work'] || $types['gallery'];
            $types['blog_type']         = $types['home'] || $types['archive'] || $types['search'];
            $types['cpt']               = $types['work'] || $types['gallery'];
            $types['cpt_list']          = $types['works_list'] || $types['galleries_list'];
        }

        return $types;
    }
}


/*
 * If page is empty search result or 404 it is no property page
 */
if(!function_exists('a13_is_no_property_page')){
    function a13_is_no_property_page() {
        global $post;

        return !is_object($post);
    }
}


/*
 * Adding class for compatibility with Wp-paginate plugin + infinite scroll configuration
 */
if(!function_exists('a13_next_posts_link_class')){
    function a13_next_posts_link_class() {
        return 'class="next"';
    }
}

if(!function_exists('a13_prev_posts_link_class')){
    function a13_prev_posts_link_class() {
        return 'class="prev"';
    }
}


/*
 * Filter and RSS for blog
 */
if(!function_exists('a13_title_bar')){
    function a13_title_bar($title = '') {
        global $apollo13;

        $page_type = a13_what_page_type_is_it();
        $home = $page_type['home'];
        $single = $page_type['single'];
        $shop = a13_is_woocommerce();

        //id from where
        $meta_id = false;
        if($shop){
            $meta_id = woocommerce_get_page_id( 'shop' );
        }
        elseif($page_type['single_not_post']){
            $meta_id = get_the_ID();
        }
        elseif(($page_type['blog_type'] || $single) && get_option( 'page_for_posts') !== '0'){
            $meta_id = get_option( 'page_for_posts');
        }

        //is it OFF?
        if(!is_search() && !is_404()){ //fast fix for notices
            if($apollo13->get_meta('_title_bar_settings', $meta_id) === 'off'){
                return;
            }
        }

        //get styles for current header bar and return fit method
        $fit = a13_title_bar_look($meta_id);
        ?>
    <header id="begin-of-content" class="header-bar <?php echo esc_attr($fit); ?>">
        <div class="in">
        <?php
            $rss = ($apollo13->get_option( 'blog', 'info_bar_rss' ) == 'on');

            //use passed $title
            if(!empty( $title )){
                //empty
            }
            //works or galleries
            elseif ( $page_type['cpt'] ){
                $title = get_the_title();
            }
            //blog post
            elseif ( $home || ($single && !$page_type['attachment']) ){
                if(get_option('page_for_posts') === '0'){
                    $title = __fe('Blog');
                }
                else{
                    $title = get_the_title(get_option('page_for_posts'));
                }
            }
            //pages
            else{
                $title = get_the_title();
            }

            echo '<h1 class="page-title">';
            //woocommerce page
            if($shop){
                woocommerce_page_title();
            }
            else{
                echo $title;
            }
            echo '</h1>';


            //what else to display
            if($single && !$shop){
                a13_post_nav();
            }
            //works/galleries list page type
            elseif($page_type['cpt_list']){
                if($page_type['galleries_list']){
                    $filter_place       = $apollo13->get_option('cpt_gallery', 'filter_place');
                }
                else{
                    $filter_place       = $apollo13->get_option('cpt_work', 'filter_place');
                }

                if($filter_place === 'title'){
                    get_template_part( 'parts/genre-filter' );
                }
            }
            elseif(!$shop && $home && $rss){
                echo '<div class="tools"><a href="'.esc_url(get_bloginfo('rss2_url')).'" class="fa fa-rss" title="'.__fe('RSS').'"></a></div>';
            }
            //page or blog not use RSS icon
            elseif($page_type['page'] || ($home && !$rss) || $shop){
                $what = $apollo13->get_meta('_subtitle_or_button', $meta_id);

                if($what === 'subtitle'){
                    $subtitle = $apollo13->get_meta('_subtitle', $meta_id);
                    if(strlen($subtitle)){
                        echo '<h2>'.$subtitle.'</h2>';
                    }
                }
                elseif($what === 'button'){
                    $url = $apollo13->get_meta('_button_url', $meta_id);
                    $text = $apollo13->get_meta('_button_text', $meta_id);
                    $new_tab = $apollo13->get_meta('_new_tab', $meta_id) === '1'? ' target="_blank"' : '';

                    if(strlen($url)){
                        echo '<a href="'.esc_url( $url ).'" class="a13-button"'.$new_tab.'>'.$text.'</a>';
                    }
                }
            }
        ?>
        </div>
    </header>
    <?php
    }
}


/*
 * Prints HTML for background image
 */
if(!function_exists('a13_title_bar_look')){
    function a13_title_bar_look($id) {
        global $apollo13;

        if($id && get_post_meta($id, '_title_bar_settings', true) == 'custom'){
            $bg_image       = get_post_meta($id, '_title_bar_image', true);
            $bg_color       = get_post_meta($id, '_title_bar_bg_color', true);
            $title_color    = get_post_meta($id, '_title_bar_title_color', true);
            $fit            = get_post_meta($id, '_title_bar_image_fit', true);
            $space          = get_post_meta($id, '_title_bar_space_width', true);
        }
        //global setting
        else{
            $bg_image       = $apollo13->get_option( 'appearance', 'title_bar_image' );
            $bg_color       = $apollo13->get_option( 'appearance', 'title_bar_bg_color' );
            $title_color    = $apollo13->get_option( 'appearance', 'title_bar_title_color' );
            $fit            = $apollo13->get_option( 'appearance', 'title_bar_image_fit' );
            $space          = $apollo13->get_option( 'appearance', 'title_bar_space_width' );
        }

        $css = '
            .header-bar{
                background-image:url('.esc_url($bg_image).');
                background-color:'.$bg_color.';
            }
            .header-bar .in{
                padding-top:'.$space.';
                padding-bottom:'.$space.';
            }
            .header-bar h1{
                color:'.$title_color.';
            }
            ';

        echo "<style type='text/css'>\n";
        echo "$css\n";
        echo "</style>\n";

        return $fit;
    }
}


/*
 * Filter and RSS for blog
 */
if(!function_exists('a13_post_nav')){
    function a13_post_nav() {
        global $apollo13;
        $show_back_btn = true;
        $href = '';

        if( defined( 'A13_WORK_PAGE' )){
            $works_id = $apollo13->get_option( 'cpt_work', 'cpt_work_page' );
            $title = __fe( 'Back to Works list');
            if($works_id !== '0'){
                $href = get_permalink($works_id);
            }
            //works list as front page
            elseif($apollo13->get_option( 'settings', 'fp_variant' ) == 'works_list'){
                $href = home_url( '/' );
            }
            else{
                $show_back_btn = false;
            }
        }
        elseif( defined( 'A13_GALLERY_PAGE' )){
            $works_id = $apollo13->get_option( 'cpt_gallery', 'cpt_gallery_page' );
            $title = __fe( 'Back to Galleries list');
            if($works_id !== '0'){
                $href = get_permalink($works_id);
            }
            //galleries list as front page
            elseif($apollo13->get_option( 'settings', 'fp_variant' ) == 'galleries_list'){
                $href = home_url( '/' );
            }
            else{
                $show_back_btn = false;
            }
        }
        else{
            $href = (get_option( 'page_for_posts') !== '0')? get_permalink(get_option( 'page_for_posts')) : home_url();
            $title = __fe( 'Back to Blog' );
        }

        echo '<div class="tools">';
            next_post_link( '<span class="prev">%link</span>','' );
            echo $show_back_btn? '<a href="'.esc_url($href).'" title="'.esc_attr($title).'" class="to-blog fa fa-th-large"></a>' : '';
            previous_post_link( '<span class="next">%link</span>','' );
        echo '</div>';
    }
}


/*
 * Making images
 */
if(!function_exists('a13_make_post_image')){
    function a13_make_post_image( $post_id, $thumb_size){
        if(empty($post_id)){
            $post_id = get_the_ID();
        }
        if ( has_post_thumbnail($post_id) ) {
            return get_the_post_thumbnail($post_id, $thumb_size );
        }

        return false;
    }
}


/*
 * Detection of type of movie
 * returns array(type, video_id)
 */
if(!function_exists('a13_detect_movie')){
    function a13_detect_movie($src){
        //used to check if it is audio file
        $parts = pathinfo($src);
        $ext = isset($parts['extension'])? strtolower($parts['extension']) : false;

        //http://www.youtube.com/watch?v=e8Z0YTWDFXI
        if (preg_match("/(youtube\.com\/watch\?)?v=([a-zA-Z0-9\-_]+)&?/s", $src, $matches)){
            $type = 'youtube';
            $video_id = $matches[2];
        }
        //http://youtu.be/e8Z0YTWDFXI
        elseif (preg_match("/(https?:\/\/youtu\.be\/)([a-zA-Z0-9\-_]+)&?/s", $src, $matches)){
            $type = 'youtube';
            $video_id = $matches[2];
        }
        // regexp $src http://vimeo.com/16998178
        elseif (preg_match("/(vimeo\.com\/)([0-9]+)/s", $src, $matches)){
            $type = 'vimeo';
            $video_id = $matches[2];
        }
        elseif(strlen($ext) && in_array($ext, array('mp3', 'ogg', 'm4a'))){
            $type = 'audio';
            $video_id = $src;
        }
        else{
            $type = 'html5';
            $video_id = $src;
        }

        return array(
            'type' => $type,
            'video_id' => $video_id
        );
    }
}


/*
 * Returns movie thumb(for youtube, vimeo)
 */
if(!function_exists('a13_get_movie_thumb_src')){
    function a13_get_movie_thumb_src( $video_res, $thumb = '' ){
        if(!empty($thumb)){
            return $thumb;
        }

        $type = $video_res['type'];
        $v_id = $video_res['video_id'];

        if ( $type == 'youtube' ){
            return 'http://img.youtube.com/vi/'.$v_id.'/hqdefault.jpg';
        }
        elseif ( $type == 'vimeo' ){
            return A13_TPL_GFX . '/holders/vimeo.jpg';
        }
        elseif ( $type == 'html5' ){
            return A13_TPL_GFX . '/holders/video.jpg';
        }

        return false;
    }
}


/*
 * Returns movie link to insert it in iframe
 */
if(!function_exists('a13_get_movie_link')){
    function a13_get_movie_link( $video_res, $params = array()){
        $type = $video_res['type'];
        $v_id = $video_res['video_id'];

        if ( $type == 'youtube' ){
            return 'http://www.youtube.com/embed/'.$v_id.'?enablejsapi=1&amp;controls=1&amp;fs=1&amp;hd=1&amp;loop=0&amp;rel=0&amp;showinfo=1&amp;showsearch=0&amp;wmode=transparent';
        }
        elseif ( $type == 'vimeo' ){
            return 'http://player.vimeo.com/video/'.$v_id.'?api=1&amp;title=1&amp;loop=0';
        }
        else{
            return A13_TPL_ADV . '/inc/videojs/player.php?src=' . $v_id . '&amp;w=' . $params['width'] . '&amp;h=' . $params['height'] . '&amp;poster=' . $params['poster'];
        }
    }
}


/*
 * Returns movie iframe or link to movie
 */
if(!function_exists('a13_get_movie')){
    function a13_get_movie( $src, $width = 295, $height = 0 ){
        if( $height == 0){
            $height = ceil((9/16) * $width);
        }

        $video_res  = a13_detect_movie($src);
        $type       = $video_res['type'];
        if($type == 'audio'){
            return a13_get_audio_player($video_res['video_id']);
        }
        else{
            $link       = a13_get_movie_link($video_res, array( 'width' => $width, 'height' => $height, 'poster' => "" ));

            return '<iframe data-vid-id="'.$video_res['video_id'].'" id="crazy'.$type . mt_rand() . '" style="height: ' . $height . 'px; width: ' . $width . 'px; border: none;" src="' . esc_url($link) . '" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
        }
    }
}


/*
 * Returns audio player HTML
 */
if(!function_exists('a13_get_audio_player')){
    function a13_get_audio_player($src){
        static $player_count = 0;

        //add script for audio
        wp_enqueue_script('apollo13-audio');
        wp_enqueue_style('audio-css');


        $parts = pathinfo($src);
        $ext = isset($parts['extension'])? strtolower($parts['extension']) : false;
        $setMedia = $ext.':"'.$src.'"';

        $html = '
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function(){
	jQuery("#jquery_jplayer_'.$player_count.'").jPlayer({
		ready: function (event) {
			jQuery(this).jPlayer("setMedia", {'.$setMedia.'});
		},
		play: function() { // To avoid both jPlayers playing together.
			jQuery(this).jPlayer("pauseOthers");
		},
		swfPath: "'.A13_TPL_JS.'/audio/",
		supplied: "'.$ext.'",
		wmode: "window",
		cssSelectorAncestor: "#jp_container_'.$player_count.'"
	});
});
//]]>
</script>


		<div id="jp_container_'.$player_count.'" class="jp-audio">
            <div id="jquery_jplayer_'.$player_count.'" class="jp-jplayer"></div>
			<div class="jp-type-single">
				<div class="jp-gui jp-interface">
					<ul class="jp-controls">
						<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
						<li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
						<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
						<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
						<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
						<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
					</ul>
					<div class="jp-progress">
						<div class="jp-seek-bar">
							<div class="jp-play-bar"></div>

						</div>
					</div>
					<div class="jp-volume-bar">
						<div class="jp-volume-bar-value"></div>
					</div>
					<div class="jp-current-time"></div>
					<div class="jp-duration"></div>
					<ul class="jp-toggles">
						<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
						<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
					</ul>
				</div>
				<div class="jp-no-solution">
					<span>Update Required</span>
					To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
				</div>
			</div>
		</div>';

        $player_count++;

        return $html;
    }
}


/**
 * ADDING THUMBNAIL TO RSS
 */
if(!function_exists('a13_rss_post_thumbnail')){
    function a13_rss_post_thumbnail($content) {
        global $post;
        if(has_post_thumbnail($post->ID)) {
            $content = '<p>' . get_the_post_thumbnail($post->ID, 'medium') .
                '</p>' . get_the_excerpt();
        }
        else
            $content = get_the_excerpt();

        return $content;
    }
}


/**
 * IS WOOCOMMERCE ACTIVATED
 */
if(!function_exists('a13_is_woocommerce_activated')){
    function a13_is_woocommerce_activated() {
        return class_exists( 'woocommerce' );
    }
}


/**
 * IS CURRENT PAGE ONE OF WOOCOMERCE
 */
if(!function_exists('a13_is_woocommerce')){
    function a13_is_woocommerce() {
        return (a13_is_woocommerce_activated() && (is_woocommerce() || is_cart() || is_account_page() || is_checkout() || is_order_received_page()));
    }
}



/**
 * WOOCOMMERCE INITIAL IMAGE SIZES
 */
if(!function_exists('a13_woocommerce_image_dimensions')){
    function a13_woocommerce_image_dimensions() {
        /**
         * Define image sizes
         */
        $catalog = array(
            'width' 	=> '450',	// px
            'height'	=> '450',	// px
            'crop'		=> 1 		// true
        );

        $single = array(
            'width' 	=> '600',	// px
            'height'	=> '600',	// px
            'crop'		=> 1 		// true
        );

        $thumbnail = array(
            'width' 	=> '110',	// px
            'height'	=> '110',	// px
            'crop'		=> 1 		// true
        );

        // Image sizes
        update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
        update_option( 'shop_single_image_size', $single ); 		// Single product image
        update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
    }
}





/**
 * FILTER HOOKS
 */
add_filter( 'post_link', 'a13_custom_permalink', 10, 3 );
add_filter( 'post_type_link', 'a13_custom_permalink', 10, 3 );
add_filter( 'wp_title', 'a13_wp_title', 10, 2 );
add_filter( 'body_class', 'a13_body_classes' );
add_filter( 'get_search_form','a13_search_form' );
add_filter( 'excerpt_length', 'a13_excerpt_length' );
add_filter( 'wp_nav_menu_objects', 'a13_add_menu_parent_class' );
//add_filter( 'excerpt_more', 'a13_new_excerpt_more' );
add_filter( 'the_password_form', 'a13_custom_password_form');
add_filter( 'the_excerpt_rss', 'a13_rss_post_thumbnail');
add_filter( 'the_content_feed', 'a13_rss_post_thumbnail');
add_filter( 'next_posts_link_attributes', 'a13_next_posts_link_class' );
add_filter( 'previous_posts_link_attributes', 'a13_prev_posts_link_class' );

global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ){
    add_action( 'init', 'a13_woocommerce_image_dimensions', 1 );
}