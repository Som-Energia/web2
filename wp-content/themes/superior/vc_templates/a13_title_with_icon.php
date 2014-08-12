<?php

$title = $title_size = $title_align = $el_class = $bold = $text_color = $title_style = $icon = '';
extract(shortcode_atts(array(
    'title' => __("Title", "a13_shortcodes"),
    'title_size' => 'h1',
    'title_align' => 'left',
    'icon' => '',
    'bold' => false,
    'text_color' => false,
    'el_class' => ''
), $atts));

$css_classes = $this->getExtraClass($el_class);

if($bold === false){
    $title_style = 'font-weight:normal;';
}
if($text_color !== false && strlen($text_color)){
    $title_style .= 'color:'.$text_color.';';
}

$icon = ( $icon != '' ) ? '<i class="fa '.$icon.'"> </i>' : '';

$output = '<div class="a13_title_with_icon" style="text-align:'.$title_align.';"><'.$title_size.' style="'.$title_style.'">'.$icon.$title.'</'.$title_size.'></div>'."\n";

echo $output;