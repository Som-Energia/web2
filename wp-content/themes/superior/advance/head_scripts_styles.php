<?php

/*
 * Frontend theme scripts
 */
if(!function_exists('a13_theme_scripts')){
    function a13_theme_scripts($special_pass = false){
        global $apollo13;

        if((is_admin() || 'wp-login.php' == basename($_SERVER['PHP_SELF'])) && !$special_pass){
            return;
        }

        /* We add some JavaScript to pages with the comment form
          * to support sites with threaded comments (when in use).
          */
        if ( is_singular() && get_option( 'thread_comments' ) ){
            wp_enqueue_script( 'comment-reply' );
        }

        $script_depends = array( 'apollo13-plugins' );

        //plugins used in theme (cheat sheet)
        wp_register_script('apollo13-plugins', A13_TPL_JS . '/plugins.js',
            array('jquery'), //depends
            A13_THEME_VER, //version number
            true //in footer
        );


        //APOLLO Slider
        wp_register_script( 'a13-slider', A13_TPL_JS . '/a13-slider.js', array('jquery'), A13_THEME_VER, true);

        //jquery cookie
//        wp_register_script( 'jquery-cookie', A13_TPL_JS . '/jquery.cookie.js', array('jquery'), '1.4', true);
//        array_push($script_depends, 'jquery-cookie'); //not using right now

        //Parallax
        wp_register_script( 'stellar', A13_TPL_JS . '/jquery.stellar.min.js', array('jquery'), '0.6.2', true);

        //masonry
        wp_register_script( 'a13-masonry', A13_TPL_JS . '/jquery.masonry-with-resize-plugin.min.js', array('jquery'), '2.5', true);

        //Jackbox lightbox
        wp_register_script( 'jackbox', A13_TPL_JS . '/jackbox/js/jackbox-packed.min.js', array('jquery'), A13_THEME_VER, true);

        //for audio posts and bg music
        wp_register_script('apollo13-audio', A13_TPL_JS . '/audio/jquery.jplayer.min.js',array('jquery'), '2.2.0', true );

        //style select inputs
        wp_register_script('dropkick', A13_TPL_JS . '/jquery.dropkick-min.js',array('jquery'), '1.4', true );
        array_push($script_depends, 'dropkick');

        $is_gallery         = defined('A13_GALLERY_PAGE');
        $is_work            = defined('A13_WORK_PAGE');
        $is_works_list      = defined('A13_WORKS_LIST_PAGE');
        $is_gallery_list    = defined('A13_GALLERIES_LIST_PAGE');

        //add masonry if needed
        if(
            (a13_is_post_list() && $apollo13->get_option('blog', 'blog_variant') === 'variant_masonry')
        ||  ($is_gallery && $apollo13->get_meta('_theme') == 'bricks')
        ||  ($is_works_list)
        ||  ($is_gallery_list)
        ){
            array_push($script_depends, 'a13-masonry');
        }

        if(
            ($is_gallery && $apollo13->get_meta('_theme') == 'slider')
        ||  ($is_work && $apollo13->get_meta('_theme') == 'slider')

        ){
            array_push($script_depends, 'a13-slider');
        }

        //lightbox
        if($apollo13->get_option( 'advanced', 'apollo_lightbox' ) == 'on'){
            array_push($script_depends, 'jackbox');
        }

        //options passed to JS
        $apollo_params = a13_js_parameters();
        //hand written scripts for theme
        wp_enqueue_script('apollo13-scripts', A13_TPL_JS . '/script.js', $script_depends, A13_THEME_VER, true );
        //transfer options
        wp_localize_script( 'apollo13-plugins', 'ApolloParams', $apollo_params );

        //remove admin bar margin in html element
//        remove_action('wp_head', '_admin_bar_bump_cb');
    }
}

if(!function_exists('a13_js_parameters')){
    function a13_js_parameters(){
        global $apollo13;

        $params = array(
            /* GLOBAL OPTIONS */
            'ajaxurl'           => admin_url('admin-ajax.php'),
            'jsurl'             => A13_TPL_JS,
            'defimgurl'         => A13_TPL_GFX . '/holders/photo.jpg',
            'validation_class'  => A13_VALIDATION_CLASS,
            'load_more'         => __fe('Load more'),
            'hd_logo'           => $apollo13->get_option( 'appearance', 'logo_image_high_dpi' ),
            'hd_logo_size'      => $apollo13->get_option( 'appearance', 'logo_image_high_dpi_sizes' ),
        );

        $is_works_list      = defined('A13_WORKS_LIST_PAGE');
        $is_gallery_list    = defined('A13_GALLERIES_LIST_PAGE');

        if(defined('A13_WORK_PAGE') ){
            if($apollo13->get_meta('_theme') == 'slider'){
                $params['fit_variant']          = $apollo13->get_meta( '_fit_variant' );
                $params['autoplay']             = $apollo13->get_meta( '_autoplay' );
                $params['transition']           = $apollo13->get_meta( '_transition' );
                $params['transition_speed']     = $apollo13->get_option( 'cpt_work', 'transition_time' );
                $params['slide_interval']       = $apollo13->get_option( 'cpt_work', 'slide_interval' );
            }
        }
        elseif( $is_works_list || $is_gallery_list ){
            if($is_works_list){
                $variant = $apollo13->get_option('cpt_work', 'works_variant');
            }
            else{
                $variant = $apollo13->get_option('cpt_gallery', 'galleries_variant');
            }
            if($is_works_list && $variant === 'variant_image'){
                $params['brick_height']         = $apollo13->get_option( 'cpt_work', 'brick_height' );
                $params['brick_width']          = $apollo13->get_option( 'cpt_work', 'brick_width' );
                $params['brick_margin']         = $apollo13->get_option( 'cpt_work', 'brick_margin' );
            }
            elseif($is_gallery_list && $variant === 'variant_image'){
                $params['brick_width']          = $apollo13->get_option( 'cpt_gallery', 'gl_brick_width' );
                $params['brick_height']         = $apollo13->get_option( 'cpt_gallery', 'gl_brick_height' );
                $params['brick_margin']         = $apollo13->get_option( 'cpt_gallery', 'gl_brick_margin' );
            }
            elseif($variant === 'variant_1'){
                $params['brick_width']          = 530;
                $params['brick_margin']         = 20;
            }
            elseif($variant === 'variant_2'){
                $params['brick_width']          = 346;
                $params['brick_margin']         = 20;
            }
            elseif($variant === 'variant_3'){
                $params['brick_width']          = 255;
                $params['brick_margin']         = 20;
            }
        }
        elseif( defined('A13_GALLERY_PAGE') ){
            if($apollo13->get_meta('_theme') == 'slider'){
                $params['fit_variant']          = $apollo13->get_meta( '_fit_variant' );
                $params['autoplay']             = $apollo13->get_meta( '_autoplay' );
                $params['transition']           = $apollo13->get_meta( '_transition' );
                $params['transition_speed']     = $apollo13->get_option( 'cpt_gallery', 'transition_time' );
                $params['slide_interval']       = $apollo13->get_option( 'cpt_gallery', 'slide_interval' );
            }
            elseif($apollo13->get_meta('_theme') == 'bricks'){
                $params['brick_width']          = $apollo13->get_option( 'cpt_gallery', 'brick_width' );
                $params['brick_height']         = $apollo13->get_option( 'cpt_gallery', 'brick_height' );
                $params['brick_margin']         = $apollo13->get_option( 'cpt_gallery', 'brick_margin' );
            }
        }
        //blog or archive
        elseif(a13_is_post_list()){
            $params['brick_width']          = $apollo13->get_option( 'blog', 'brick_width' );
            $params['brick_margin']         = $apollo13->get_option( 'blog', 'brick_margin' );
            $params['per_page']             = get_option( 'posts_per_page' );
        }

        //options transferred to js files
        return $params;
    }
}

/*
 * Adds CSS files to theme
 */
if(!function_exists('a13_theme_styles')){
    function a13_theme_styles($special_pass = false){
        if((is_admin() || 'wp-login.php' == basename($_SERVER['PHP_SELF'])) && !$special_pass){
            return;
        }

        global $apollo13;

        $user_css_depends = array('main-style');

        wp_register_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.css', false, '4.1.0');
        wp_register_style( 'main-style', A13_TPL_URI . '/style.css', array('font-awesome'), A13_THEME_VER);

        //for audio post format support
        wp_register_style('audio-css', A13_TPL_CSS . '/pink.flag/jplayer.pink.flag.css', false, A13_THEME_VER);

        //style select inputs
        wp_register_style('dropkick', A13_TPL_CSS . '/dropkick.css', false, '1.4');
        wp_enqueue_style('dropkick');

        wp_register_style('jackbox', A13_TPL_JS . '/jackbox/css/jackbox.min.css', false, A13_THEME_VER);
        wp_enqueue_style('jackbox');

        wp_register_style('user-css', $apollo13->user_css_name(true), $user_css_depends, A13_THEME_VER);
        wp_enqueue_style('user-css');

        wp_register_style( 'a13_sc_style', A13_TPL_CSS . '/a13_sc.css', array('js_composer_front'), A13_THEME_VER);
        wp_enqueue_style('a13_sc_style');
    }
}

/*
 * adds google analytics code and google fonts (cause JSON is not easily passed by wp_localize_script
 */
if(!function_exists('a13_theme_head')){
    function  a13_theme_head(){
        if(is_admin() || 'wp-login.php' == basename($_SERVER['PHP_SELF'])){
            return;
        }

        global $apollo13;

        //WEB FONTS LOADING
        $fonts = array( 'families' => array());
        //check if classic or google font is selected
        //colon in name = google font
        $temp = $apollo13->get_option('fonts', 'normal_fonts');
        (strpos($temp, ':') !== false)? array_push($fonts['families'], $temp) : false;
        $temp = $apollo13->get_option('fonts', 'titles_fonts');
        (strpos($temp, ':') !== false)? array_push($fonts['families'], $temp) : false;
        $temp = $apollo13->get_option('fonts', 'nav_menu_fonts');
        (strpos($temp, ':') !== false)? array_push($fonts['families'], $temp) : false;

        if(sizeof($fonts['families'])):
            $fonts = json_encode($fonts);
    ?>

    <script type="text/javascript">
        WebFontConfig = {
            google: <?php echo $fonts; ?>,
            active: function() {
                //tell listeners that fonts are loaded
                jQuery(document.body).trigger('webfontsloaded');
            }
        };
        (function() {
            var wf = document.createElement('script');
            wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
                    '://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js';
            wf.type = 'text/javascript';
            wf.async = 'true';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(wf, s);
        })();
    </script>

    <?php
        endif;

        //Google Analytics Tracking Code
        echo $apollo13->get_option( 'settings', 'ga_code' );
    }
}

add_action( 'wp_enqueue_scripts', 'a13_theme_scripts', 26 ); //put it later then woocommerce
add_action( 'wp_enqueue_scripts', 'a13_theme_styles', 26 ); //put it later then woocommerce
