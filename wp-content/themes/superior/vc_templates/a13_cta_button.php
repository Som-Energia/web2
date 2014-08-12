<?php

$output = $icon = $i_icon = $target = $href = $title = $call_text = $call_sub_text = $el_class = '';
extract(shortcode_atts(array(
    'icon' => '',
    'target' => '',
    'href' => '',
    'title' => __('Text on the button', "js_composer"),
    'call_text' => '',
    'call_sub_text' => '',
    'el_class' => ''
), $atts));

$el_class = $this->getExtraClass($el_class);

if ( $target == 'same' || $target == '_self' ) { $target = ''; }
if ( $target != '' ) { $target = ' target="'.$target.'"'; }

$icon = $this->getExtraClass($icon);
if($icon !== ''){
    $i_icon = ' <i class="icon fa '.$icon.'"> </i>';
}

$a_class = '';
if ( $el_class != '' ) {
    $tmp_class = explode(" ", $el_class);
    if ( in_array("prettyphoto", $tmp_class) ) {
        wp_enqueue_script( 'prettyphoto' );
        wp_enqueue_style( 'prettyphoto' );
        $a_class .= ' prettyphoto'; $el_class = str_ireplace("prettyphoto", "", $el_class);
    }
}

if ( $href != '' ) {
    $button = '<a class="a13-button wpb_button_a'.$a_class.'" href="'.$href.'"'.$target.'>'.$i_icon.$title.'</a>';
} else {
    $button = '';
    $el_class .= ' cta_no_button';
}
$css_class = 'a13_call_to_action '.$el_class;

$output .= '<div class="'.$css_class.'">'."\n";
$output .= "\n\t".'<div class="inside">'."\n";

$output .= $button;
$output .= '<h2 class="wpb_call_text">'.$call_text.'</h2>';

if(strlen($call_sub_text)){
    $output .= '<h3 class="wpb_call_sub_text">'.$call_sub_text.'</h3>';
}

$output .= "\n\t".'</div> '."\n";
$output .= '</div> '."\n";

echo $output;