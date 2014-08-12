<?php
$block = $block_data[0];
$settings = $block_data[1];
?>
<?php if($block === 'title'): ?>
<h2 class="post-title">
    <?php echo !empty($settings[0]) && $settings[0]!='no_link' ? $this->getLinked($post, $post->title, $settings[0], 'link_title') : $post->title ?>
</h2>
<?php elseif($block === 'image' && !empty($post->thumbnail)): ?>
<div class="item-image post-media">
    <?php echo !empty($settings[0]) && $settings[0]!='no_link' ? $this->getLinked($post, $post->thumbnail.'<em></em>', $settings[0], 'link_image') : $post->thumbnail ?>
</div>
<?php elseif($block === 'text'): ?>
<div class="entry-content">
    <?php echo !empty($settings[0]) && $settings[0]==='text' ?  $post->content : $post->excerpt; ?>
</div>
<?php elseif($block === 'date_comments'):
$comments = sprintf(__fe( '%d Comment(s)' ), get_comments_number($post->id)) ;
    ?>
<div class="post-meta">
    <time class="entry-date" datetime="<?php echo $post->post_date_c; ?>"><?php echo $post->post_date; ?></time>
    /
    <span class="comments"><a href="<?php esc_url(get_comments_link($post->id)); ?>" title="<?php echo $comments; ?>"><?php echo $comments; ?></a></span>
</div>
<?php elseif($block === 'link'): ?>
<a href="<?php echo $post->link ?>" class="vc_read_more" title="<?php echo esc_attr(sprintf(__( 'Permalink to %s', "js_composer" ), $post->title_attribute)); ?>"<?php echo $this->link_target ?>><?php _e('Read more...', "js_composer") ?></a>
<?php endif; ?>