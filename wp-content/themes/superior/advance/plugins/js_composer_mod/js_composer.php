<?php

//templates dir
vc_set_template_dir(A13_TPL_DIR.'/vc_templates/');

//add file with classes for our own shortcodes(extensions to Visual Composer)
require_once ('shortcodes_classes.php');


/*
 * Adds Shortcodes to Visual Composer
 */
function a13_sc_add_to_visual_composer(){
    require_once ('config.php');
}
add_action( 'init', 'a13_sc_add_to_visual_composer', 20 );



//builder CSS
function js_composer_admin_css() {
    wp_enqueue_style( 'js_composer_admin', A13_TPL_CSS . '/js_composer_admin.css' );
}

add_action( 'admin_enqueue_scripts', 'js_composer_admin_css', 20);


if(!function_exists('a13_teaser_thumb_size')){
    function a13_teaser_thumb_size($size_to_check, $columns){
        $thumb_size = 'thumbnail';
        if($columns <= 2){
            $thumb_size = 'large';
        }
        else{
            if(in_array($size_to_check, get_intermediate_image_sizes())){
                $thumb_size = $size_to_check;
            }
        }
        return $thumb_size;
    }
}