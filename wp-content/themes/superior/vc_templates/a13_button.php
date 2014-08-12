<?php

$output = $align = $border_color = $text_color = $bg_color = $size = $icon = $target = $href = $el_class = $a_class = $title = $style = '';
extract(shortcode_atts(array(
    'border_color' => '',
    'bg_color' => '',
    'text_color' => '',
    'size' => '',
    'icon' => '',
    'target' => '_self',
    'href' => '#',
    'align' => 'left',
    'el_class' => '',
    'title' => __('Text on the button', "js_composer"),
), $atts));

if($border_color != ''){
    $style .= 'border-color:'.$border_color.';';
}
if($text_color != ''){
    $style .= 'color:'.$text_color.';';
}
if($bg_color != ''){
    $style .= 'background-color:'.$bg_color.';';
}

if ( $el_class != '' ) {
    $tmp_class = explode(" ", strtolower($el_class));
    $tmp_class = str_replace(".", "", $tmp_class);
    if ( in_array("prettyphoto", $tmp_class) ) {
        wp_enqueue_script( 'prettyphoto' );
        wp_enqueue_style( 'prettyphoto' );
        $a_class .= ' prettyphoto';
        $el_class = str_ireplace("prettyphoto", "", $el_class);
    }
    if ( in_array("pull-right", $tmp_class) && $href != '' ) { $a_class .= ' pull-right'; $el_class = str_ireplace("pull-right", "", $el_class); }
    if ( in_array("pull-left", $tmp_class) && $href != '' ) { $a_class .= ' pull-left'; $el_class = str_ireplace("pull-left", "", $el_class); }
}

if ( $target == 'same' || $target == '_self' ) { $target = ''; }
$target = ( $target != '' ) ? ' target="'.$target.'"' : '';

$i_icon = ( $icon != '' ) ? ' <i class="fa '.$icon.'"> </i>' : '';
$el_class = $this->getExtraClass($el_class);

$css_class = $size.$el_class.$a_class;
$output .= '<span>'.$i_icon.$title.'</span>';
$output = '<a class="a13-sc-button '.$css_class.'" title="'.$title.'" href="'.$href.'"'.$target.' style="'.$style.'">' . $output . '</a>';
$output = '<div class="a13-sc-button_wrapper" style="text-align:'.$align.';">'.$output.'</div>';

echo $output  . "\n";
