<?php

$output = $el_class = $align = $image = $img_size = $img_link = $img_link_target = $img_link_large = $img_html = $img_style = $title = $css_animation = $border_radius = '';

extract(shortcode_atts(array(
    'title' => '',
    'image' => '',
    'align' => 'left',
    'img_size'  => 'thumbnail',
    'img_link_large' => false,
    'img_link' => '',
    'img_link_target' => '_self',
    'el_class' => '',
    'css_animation' => '',
    'border_radius' => 0,
), $atts));

$img_id = preg_replace('/[^\d]/', '', $image);
$img = wp_get_attachment_image_src( $img_id, $img_size );

if($border_radius !== ''){
    $img_style = ' style="border-radius:'.(int)$border_radius.'px;"';
}

if ( $img === false ){
    $img_html = '<img src="http://placekitten.com/g/400/300"'.$img_style.' /> <small>'.__('This is image placeholder, edit your page to replace it.', 'js_composer').'</small>';
}
else{
    $img_html = '<img src="'.$img[0].'" width="'.$img[1].'" height="'.$img[2].'" alt=""'.$img_style.' />';
}

$link_to = '';
if ($img_link_large==true) {
    $link_to = wp_get_attachment_image_src( $img_id, 'large');
    $link_to = $link_to[0];
    $el_class .= 'prettyphoto'; //lightbox
}
else if (!empty($img_link)) {
    $link_to = $img_link;
}

$el_class = $this->getExtraClass($el_class);

$a_class = '';
if ( $el_class != '' ) {
    $tmp_class = explode(" ", strtolower($el_class));
    $tmp_class = str_replace(".", "", $tmp_class);
    if ( in_array("prettyphoto", $tmp_class) ) {
        wp_enqueue_script( 'prettyphoto' );
        wp_enqueue_style( 'prettyphoto' );
        $a_class = ' class="prettyphoto"';
        $el_class = str_ireplace(" prettyphoto", "", $el_class);
    }
}


if(!preg_match('/^(https?\:\/\/|\/\/)/', $link_to) && $link_to!='') $link_to = 'http://'.$link_to;
$image_string = !empty($link_to) ? '<a'.$a_class.' href="'.$link_to.'"'.($img_link_target!='_self' ? ' target="'.$img_link_target.'"' : '').'>'.$img_html.'</a>' : $img_html;

$css_class =  apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_single_image wpb_content_element'.$el_class, $this->settings['base']);
$css_class .= $this->getCSSAnimation($css_animation);

$output .= "\n\t".'<div class="'.$css_class.'">';
$output .= "\n\t\t".'<div class="wpb_wrapper" style="text-align:'.$align.';">';
$output .= "\n\t\t\t".wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_singleimage_heading'));
$output .= "\n\t\t\t".$image_string;
$output .= "\n\t\t".'</div> '.$this->endBlockComment('.wpb_wrapper');
$output .= "\n\t".'</div> '.$this->endBlockComment('.wpb_single_image');

echo $output;