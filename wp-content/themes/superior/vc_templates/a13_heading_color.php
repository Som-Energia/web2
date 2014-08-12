<?php

$output = $title = $title_align = $title_size = $text_color = $bg_color = $el_class = $h1_style = $span_style = $bold = $font_weight = '';
extract(shortcode_atts(array(
    'title' => __("Title", "a13_shortcodes"),
    'title_align' => 'align_center',
    'title_size' => 'h1',
    'bold' => false,
    'text_color' => false,
    'bg_color' => false,
    'el_class' => ''
), $atts));

$css_classes = $this->getExtraClass($el_class) . $this->getExtraClass($title_align);

if($bold === false){
    $font_weight = 'font-weight:normal;';
}

if($text_color !== false && strlen($text_color)){
    $h1_style = 'style="color:'.$text_color.';"';
}

if($bg_color !== false && strlen($bg_color)){
    $span_style = 'style="background-color:'.$bg_color.';'.$font_weight.'"';
}

$output .= '<'.$title_size.' class="a13-heading-color'.$css_classes.'" '.$h1_style.'><span '.$span_style.'>'.$title.'</span></'.$title_size.'>'."\n";

echo $output;