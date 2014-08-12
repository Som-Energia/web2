<?php

$output = $position = $type = $el_class = $style = $margin_top = $margin_bottom = '';
extract(shortcode_atts(array(
    'position' => 'full_width',
    'type' => 'single_line',
    'margin_top' => '',
    'margin_bottom' => '',
    'el_class' => ''
), $atts));

if(strlen($margin_bottom) || strlen($margin_top)){
    $style .= ' style="';
    if(strlen($margin_top)){
        $style .= 'margin-top:'.((int)$margin_top).'px;';
    }
    if(strlen($margin_bottom)){
        $style .= 'margin-bottom:'.((int)$margin_bottom).'px;';
    }
    $style .= '"';
}

$css_classes = $this->getExtraClass($el_class) . $this->getExtraClass($position) . $this->getExtraClass($type);

$output .= '<div class="a13-separator'.$css_classes.'"'.$style.'><div></div><br class="clear" /></div>'."\n";

echo $output;