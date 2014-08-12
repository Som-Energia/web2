<?php
    global $post;
    //uses post content as title, and title as author name
    echo '<h2 class="post-title">'.$post->post_content.'</h2>';
    echo '<span class="cite-author">&mdash; '.get_the_title().'</span>';
