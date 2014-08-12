<?php
$output = $el_class = $css_animation = $block_classes = $style = $data_attr = '';
$bg_color = $bg_image = $bg_position = $bg_repeat = $bg_cover = $font_color = $side_padding = '';
$padding = $full_width = $full_width_content = $parallax = $parallax_speed = $margin_bottom = '';

extract(shortcode_atts(array(
	'el_class'          => '',
	'bg_color'          => '',
	'bg_image'          => '',
	'bg_position'       => '',
	'bg_repeat'         => '',
	'bg_cover'          => '',
    'font_color'        => '',
	'padding'           => '',
	'side_padding'      => '',
    'margin_bottom'     => '',
    'full_width'        => '',
    'full_width_content'=> '',
	'parallax'          => '',
	'parallax_speed'    => '0.5',
    'css_animation'         => '',
), $atts));
wp_enqueue_style( 'js_composer_front' );
wp_enqueue_script( 'wpb_composer_front_js' );
wp_enqueue_style('js_composer_custom_css');

$el_class = $this->getExtraClass($el_class);

$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_row '.get_row_css_class().$el_class, $this->settings['base']);
$css_class .= $this->getCSSAnimation($css_animation);

if ( strlen($font_color) ) {
    $style .= 'color:'.$font_color.';';
}
if ( strlen($bg_color) ) {
    $style .= 'background-color:'.$bg_color.';';
}
if ( strlen($bg_image) ) {
    $image = wp_get_attachment_image_src( $bg_image, 'full' );
    $image = $image[0];
    $style .= 'background-image:url('.esc_url($image).');';
}
if ( strlen($bg_position) ) {
    $values = str_split($bg_position);
    $translation = array(
        'c' => 'center',
        'l' => 'left',
        'r' => 'right',
        't' => 'top',
        'b' => 'bottom',
    );

    $style .= 'background-position:'.$translation[$values[0]].' '.$translation[$values[1]].';';
}
if ( strlen($bg_repeat) ) {
    $style .= 'background-repeat: ' . $bg_repeat.';';
}
if ( $full_width === '1' ) {
    $block_classes .= $this->getExtraClass('a13_full-row');
    if($full_width_content === '1' ) {
        $block_classes .= $this->getExtraClass('a13_full-content');
    }
}
if ( strlen($padding) ) {
    $style .= 'padding-top:'.((int)$padding).'px;padding-bottom:'.((int)$padding).'px;';
}
if ( strlen($side_padding) ) {
    $style .= 'padding-left:'.((int)$side_padding).'px;padding-right:'.((int)$side_padding).'px;';
}

if ( strlen($margin_bottom) ) {
    $style .= 'margin-bottom:'.((int)$margin_bottom).'px;';
}

if ( $bg_cover === '1' ) {
    $style .= 'background-size:cover;';
}

if ( $parallax === '1' ) {
    wp_enqueue_script( 'stellar' );
//    $style .= 'background-attachment:fixed;';
    $block_classes .= $this->getExtraClass('with-parallax');
    $data_attr = ' data-stellar-background-ratio="'.((float)$parallax_speed).'"';
}

if ( strlen($style) ) {
    $block_classes .= $el_class;
    $style = ' style="'.esc_attr($style) . '"';
    $output .= '<div class="a13_row_container'.esc_attr($block_classes).'"'.$data_attr.$style.'>';
}


$output .= '<div class="'.$css_class.'">';
$output .= wpb_js_remove_wpautop($content);
$output .= '</div>'.$this->endBlockComment('row');

if ( strlen($style) ) {
    $output .= '</div>';
}

echo $output;