<?php
$title = $title_align = $title_size = $el_class = $bold = $title_style = $type = '';
extract(shortcode_atts(array(
    'title' => __("Title", "js_composer"),
    'title_align' => 'separator_align_center',
    'title_size' => 'h1',
    'bold' => false,
    'type' => 'single-line',
    'el_class' => ''
), $atts));

$el_class = $this->getExtraClass($el_class) . $this->getExtraClass($title_align) . $this->getExtraClass($type) ;
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_content_element '.$title_align.$el_class, $this->settings['base']);

if($bold === false){
    $title_style = ' style="font-weight:normal;"';
}

$output = '<'.$title_size.$title_style.'><span>'.$title.'</span></'.$title_size.'>'."\n";
$output = '<div class="a13-separator '.$css_class.'">'.$output.'<div></div></div>'.$this->endBlockComment('separator')."\n";

echo $output;