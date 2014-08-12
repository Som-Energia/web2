<?php
add_action('wp_ajax_a13_font_details', 'a13_font_details');

function a13_font_details() {
    $searched_font = $_POST['font'];
    $google_fonts = json_decode(file_get_contents( A13_TPL_ADV_DIR . '/inc/google-font-json' ));

    $font = '';
    $found = false;

    foreach( $google_fonts->items as $font ) {
        if($font->family == $searched_font){
            $found = true;
            break;
        }

    }

    echo  json_encode($found? $font :  false);

    die(); // this is required to return a proper result
}

add_action('wp_ajax_a13_remove_custom_sidebar', 'a13_remove_custom_sidebar');

function a13_remove_custom_sidebar() {
    global $apollo13;
    $sidebar_to_remove = $_POST['sidebar'];

    $found = false;

    //get current sidebars
    $custom_sidebars = unserialize($apollo13->get_option( 'sidebars', 'custom_sidebars' ));
    $sidebars_count = count($custom_sidebars);
    if(is_array($custom_sidebars) && $sidebars_count > 0){
        //search for sidebar to delete
        foreach($custom_sidebars as $key => $sidebar){
            if($sidebar_to_remove === $sidebar['id']){
                $found = $key;
                break;
            }
        }

        //if sidebar was found
        if($found !== false){
            //delete it
            unset($custom_sidebars[$found]);
        }

        //update theme options
        $options_name = 'sidebars';
        $apollo13->set_option( $options_name, 'custom_sidebars', serialize($custom_sidebars));
        $apollo13->update_options($options_name, true);
    }

    echo  json_encode(true);

    die(); // this is required to return a proper result
}