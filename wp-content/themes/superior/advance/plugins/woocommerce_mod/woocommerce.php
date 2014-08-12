<?php
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);


add_action( 'init', 'a13_woocommerce_remove_wc_breadcrumbs' );
add_action('woocommerce_before_main_content', 'a13_woocommerce_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'a13_woocommerce_theme_wrapper_end', 10);


//remove WC bredcrumbs
function a13_woocommerce_remove_wc_breadcrumbs() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}

//start html of woocommerce templates
function a13_woocommerce_theme_wrapper_start() {
    add_filter( 'woocommerce_show_page_title', '__return_false');
    a13_title_bar();
    $post_ID = 0;

    $no_property_page = a13_is_no_property_page();
    if(!$no_property_page){ //not search page without results
        $post_ID = get_the_ID();
    }
    ?>

<article id="content" class="clearfix">
    <?php a13_header_tools(); ?>

    <div id="col-mask">

        <div id="post-<?php echo $post_ID; ?>" <?php
            if($no_property_page){
                echo 'class="post-content"'; //be sure to put that class
            }
            else{
                post_class('post-content'); //normally display all classes
            }?>>
            <div class="real-content">
<?php
}

//end html of woocommerce templates
function a13_woocommerce_theme_wrapper_end() {
?>
                <div class="clear"></div>
            </div>
        </div>
        <?php get_sidebar(); ?>
    </div>
</article>
<?php
}