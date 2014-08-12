<?php
global $apollo13;

$blog_variant = $apollo13->get_option('blog', 'blog_variant');

//depending on blog variant we display this part in different order
if($blog_variant === 'variant_1' || $blog_variant === 'variant_3'){
    echo '<h2 class="post-title"><a href="'. esc_url(get_permalink()) . '">' . get_the_title() . '</a></h2>';

    a13_post_meta();

    a13_top_image_video( true );
}
else{
    a13_top_image_video( true );

    echo '<h2 class="post-title"><a href="'. esc_url(get_permalink()) . '">' . get_the_title() . '</a></h2>';

    a13_post_meta();
}


?>

<div class="real-content">

    <?php
        if($apollo13->get_option('blog', 'excerpt_type') == 'auto' || is_search()){
            if(strpos($post->post_content, '<!--more-->')){
                the_content(__fe('Read more' ));
            }
            else{
                the_excerpt();
            }
        }
        //manual post cutting
        else{
            the_content(__fe('Read more' ));
        }
    ?>
    <div class="clear"></div>
</div>

