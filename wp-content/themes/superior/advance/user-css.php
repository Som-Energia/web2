<?php
/* Generates user css based on settings in admin panel */
function apollo13_make_css_rule($property, $value, $format = false){
    if ( $value !== '' &&  $value !== 'default' ){
        //make fallback for rgba colors by providing rgb color
        if(strpos($property,'color') !== false && strpos($value,'rgba') !== false){
            //break rgba to numbers
            $chunks = a13_break_rgba($value);
            $fallback_color = "rgb($chunks[1], $chunks[2], $chunks[3])";

            return
                $property . ': ' . $fallback_color . "; " .
                $property . ': ' . $value . ';';
        }

        //format for some properties
        if( $format !== false ){
            return $property . ': ' . sprintf($format, $value) . ';';
        }

        return $property . ': ' . $value . ";";
    }
    else{
        return '';
    }
}

/* only for background color
 * it gives RGBA possibility to IE 8
*/
function apollo13_ie_color($property, $value){
    if(strpos($value,'rgba') !== false){
        //break rgba to numbers
        $chunks = a13_break_rgba($value);

        $ie_color = a13_rgba2hex($chunks[1], $chunks[2], $chunks[3], $chunks[4]);

        $css =
            'background-color: transparent; ' .
                'filter:progid:DXImageTransform.Microsoft.gradient(startColorStr='.$ie_color.',endColorStr='.$ie_color.'); ' .
                'zoom: 1;';

        return $css;
    }
    else{
        return apollo13_make_css_rule($property, $value);
    }
}

function a13_rgba2hex( $r, $g, $b, $a ){
    return sprintf( '#%02s%02s%02s%02s', dechex( 255 * $a ), dechex( $r ), dechex( $g ), dechex( $b ) );
}

function a13_break_rgba( $rgba ){
    $chunks = array();
    preg_match("/\(\s*(\d+),\s*(\d+),\s*(\d+),\s*(\d+\.?\d*)\s*\)/", $rgba, $chunks);
    return $chunks;
}


/*
 * body part
 */
//$color_scheme               = $this->theme_options[ 'appearance' ][ 'color_scheme' ];
$body_bg_color              = apollo13_make_css_rule( 'background-color', $this->theme_options[ 'appearance' ][ 'body_bg_color' ]);
$body_bg_color_val          = $this->theme_options[ 'appearance' ][ 'body_bg_color' ];
$body_image                 = apollo13_make_css_rule( 'background-image', $this->theme_options[ 'appearance' ][ 'body_image' ], 'url(%s)');
$body_bg_attachment         = apollo13_make_css_rule( 'background-attachment', $this->theme_options[ 'appearance' ][ 'body_bg_attachment' ]);
$headings_color             = apollo13_make_css_rule( 'color', $this->theme_options[ 'fonts' ][ 'headings_color' ]);
$headings_color_hover       = apollo13_make_css_rule( 'color', $this->theme_options[ 'fonts' ][ 'headings_color_hover' ]);
$headings_weight            = apollo13_make_css_rule( 'font-weight', $this->theme_options[ 'fonts' ][ 'headings_weight' ]);
$headings_transform         = apollo13_make_css_rule( 'text-transform', $this->theme_options[ 'fonts' ][ 'headings_transform' ]);
$links_color                = apollo13_make_css_rule( 'color', $this->theme_options[ 'appearance' ][ 'links_color' ]);
$links_color_hover          = apollo13_make_css_rule( 'color', $this->theme_options[ 'appearance' ][ 'links_color_hover' ]);
$cursor_css                 = 'cursor: default;';
$custom_cursor              = $this->theme_options[ 'appearance' ][ 'custom_cursor' ];
if( $custom_cursor  === 'custom' ){
    $cursor_css             = apollo13_make_css_rule( 'cursor', $this->theme_options[ 'appearance' ][ 'cursor_image' ], 'url("%s"), default');
}
elseif( $custom_cursor === 'select' ){
    $cursor = $this->theme_options[ 'appearance' ][ 'cursor_select' ];
    $cursor_css             = apollo13_make_css_rule( 'cursor', A13_TPL_GFX.'/cursors/'.$cursor , 'url("%s"), default');
}
$button_color      = apollo13_make_css_rule( 'color', $this->theme_options[ 'appearance' ][ 'button_color' ]);
$button_bg_color   = apollo13_make_css_rule( 'background-color', $this->theme_options[ 'appearance' ][ 'button_bg_color' ]);
$button_h_color    = apollo13_make_css_rule( 'color', $this->theme_options[ 'appearance' ][ 'button_hover_color' ]);
$button_h_bg_color = apollo13_make_css_rule( 'background-color', $this->theme_options[ 'appearance' ][ 'button_hover_bg_color' ]);

/*
 *  header part
 */
$header_bg_color        = apollo13_make_css_rule( 'background-color', $this->theme_options[ 'appearance' ][ 'header_bg_color' ]);
//$header_bg_color_ie     = apollo13_ie_color     ( 'background-color', $this->theme_options[ 'appearance' ][ 'header_bg_color' ]);
//.lt-ie9 #header{
//    $header_bg_color_ie
//}
$logo_color             = apollo13_make_css_rule( 'color', $this->theme_options[ 'appearance' ][ 'logo_color' ]);
$logo_font_size         = apollo13_make_css_rule( 'font-size', $this->theme_options[ 'appearance' ][ 'logo_font_size' ]);
//$logo_shrink            = apollo13_make_css_rule( 'max-width', $this->theme_options[ 'appearance' ][ 'logo_shrink' ]);
$menu_sep_color         = apollo13_make_css_rule( 'border-color', $this->theme_options[ 'appearance' ][ 'menu_sep_color' ]);
$menu_sep_top_color     = apollo13_make_css_rule( 'border-top-color', $this->theme_options[ 'appearance' ][ 'menu_sep_color' ]);
$menu_sep_bottom_color  = apollo13_make_css_rule( 'border-bottom-color', $this->theme_options[ 'appearance' ][ 'menu_sep_color' ]);
$menu_weight            = apollo13_make_css_rule( 'font-weight', $this->theme_options[ 'appearance' ][ 'menu_weight' ]);
$menu_transform         = apollo13_make_css_rule( 'text-transform', $this->theme_options[ 'appearance' ][ 'menu_transform' ]);
$menu_font_size         = apollo13_make_css_rule( 'font-size', $this->theme_options[ 'appearance' ][ 'menu_font_size' ]);
$menu_color             = apollo13_make_css_rule( 'color', $this->theme_options[ 'appearance' ][ 'menu_color' ]);
$menu_hover_color       = apollo13_make_css_rule( 'color', $this->theme_options[ 'appearance' ][ 'menu_hover_color' ]);
$menu_hover_bg_color    = apollo13_make_css_rule( 'background-color', $this->theme_options[ 'appearance' ][ 'menu_hover_bg_color' ]);
$submenu_hover_bg_color = apollo13_make_css_rule( 'background-color', $this->theme_options[ 'appearance' ][ 'submenu_hover_bg_color' ]);
$submenu_font_size      = apollo13_make_css_rule( 'font-size', $this->theme_options[ 'appearance' ][ 'submenu_font_size' ]);
$submenu_color          = apollo13_make_css_rule( 'color', $this->theme_options[ 'appearance' ][ 'submenu_color' ]);
$submenu_hover_color    = apollo13_make_css_rule( 'color', $this->theme_options[ 'appearance' ][ 'submenu_hover_color' ]);

$menu_button_color      = apollo13_make_css_rule( 'color', $this->theme_options[ 'appearance' ][ 'menu_button_color' ]);
$menu_button_bg_color   = apollo13_make_css_rule( 'background-color', $this->theme_options[ 'appearance' ][ 'menu_button_bg_color' ]);
$menu_button_h_color    = apollo13_make_css_rule( 'color', $this->theme_options[ 'appearance' ][ 'menu_button_hover_color' ]);
$menu_button_h_bg_color = apollo13_make_css_rule( 'background-color', $this->theme_options[ 'appearance' ][ 'menu_button_hover_bg_color' ]);
$menu_button_show_680   = $this->theme_options[ 'appearance' ][ 'menu_button_show_under_680' ] === '0'? 'display: none;' : '';


/*
 *  footer
 */
$footer_font_size       = apollo13_make_css_rule( 'font-size', $this->theme_options[ 'appearance' ][ 'footer_font_size' ]);
$footer_font_color      = apollo13_make_css_rule( 'color', $this->theme_options[ 'appearance' ][ 'footer_font_color' ]);
$footer_widget_color    = apollo13_make_css_rule( 'color', $this->theme_options[ 'appearance' ][ 'footer_widget_title_color' ]);
$footer_link_color      = apollo13_make_css_rule( 'color', $this->theme_options[ 'appearance' ][ 'footer_link_color' ]);
$footer_link_hover_color= apollo13_make_css_rule( 'color', $this->theme_options[ 'appearance' ][ 'footer_link_hover_color' ]);
$footer_bg_color        = apollo13_make_css_rule( 'background-color', $this->theme_options[ 'appearance' ][ 'footer_bg_color' ]);



/*
 *  blog
 */
$blog_brick_width       = apollo13_make_css_rule( 'width', $this->theme_options[ 'blog' ][ 'brick_width' ]);
$margin = $this->theme_options[ 'blog' ][ 'brick_margin' ];
$margin_half = ((int)$margin)/2;
$blog_brick_margin_b = apollo13_make_css_rule( 'margin-bottom', $margin);
$blog_brick_margin_r = apollo13_make_css_rule( 'margin-right', $margin_half, '%fpx' );
$blog_brick_margin_l = apollo13_make_css_rule( 'margin-left', $margin_half, '%fpx' );
//$blog_brick_padd_t   = apollo13_make_css_rule( 'padding-top', $margin);
$blog_brick_padd_r   = apollo13_make_css_rule( 'padding-right', $margin_half, '%fpx' );
$blog_brick_padd_l   = apollo13_make_css_rule( 'padding-left', $margin_half, '%fpx' );



/*
 *  Galleries list
 */
$galleries_brick_width    = apollo13_make_css_rule( 'width', $this->theme_options[ 'cpt_gallery' ][ 'gl_brick_width' ]);
$temp = $this->theme_options[ 'cpt_gallery' ][ 'gl_brick_height' ];
// if 0 then height if fluid
if($temp === '0px' || $temp === '0'){
    $galleries_brick_height = 'height: auto;';
    $galleries_brick_img_height = '';
}
else{
    $galleries_brick_height = apollo13_make_css_rule( 'height', $temp);
    $galleries_brick_img_height = $galleries_brick_height;
}

$margin = $this->theme_options[ 'cpt_gallery' ][ 'gl_brick_margin' ];
$margin_half = ((int)$margin)/2;
$galleries_brick_margin_b = apollo13_make_css_rule( 'margin-bottom', $margin);
$galleries_brick_margin_r = apollo13_make_css_rule( 'margin-right', $margin_half, '%fpx' );
$galleries_brick_margin_l = apollo13_make_css_rule( 'margin-left', $margin_half, '%fpx' );
$galleries_brick_padd_t   = apollo13_make_css_rule( 'padding-top', $margin);
$galleries_brick_padd_r   = apollo13_make_css_rule( 'padding-right', $margin_half, '%fpx' );
$galleries_brick_padd_l   = apollo13_make_css_rule( 'padding-left', $margin_half, '%fpx' );
$galleries_titles         = ($this->theme_options[ 'cpt_gallery' ][ 'show_titles' ] == 'off')? 'display: none !important;' : '';


/*
 *  Gallery
 */
$gallery_brick_width    = apollo13_make_css_rule( 'width', $this->theme_options[ 'cpt_gallery' ][ 'brick_width' ]);
$temp = $this->theme_options[ 'cpt_gallery' ][ 'brick_height' ];
// if 0 then height if fluid
if($temp === '0px' || $temp === '0'){
    $gallery_brick_height = 'height: auto;';
    $gallery_brick_img_height = '';
}
else{
    $gallery_brick_height = apollo13_make_css_rule( 'height', $temp);
    $gallery_brick_img_height = $gallery_brick_height;
}

$margin = $this->theme_options[ 'cpt_gallery' ][ 'brick_margin' ];
$margin_half = ((int)$margin)/2;
$gallery_brick_margin_b = apollo13_make_css_rule( 'margin-bottom', $margin);
$gallery_brick_margin_r = apollo13_make_css_rule( 'margin-right', $margin_half, '%fpx' );
$gallery_brick_margin_l = apollo13_make_css_rule( 'margin-left', $margin_half, '%fpx' );
$gallery_brick_padd_t   = apollo13_make_css_rule( 'padding-top', $margin);
$gallery_brick_padd_r   = apollo13_make_css_rule( 'padding-right', $margin_half, '%fpx' );
$gallery_brick_padd_l   = apollo13_make_css_rule( 'padding-left', $margin_half, '%fpx' );
$gallery_caption        = ($this->theme_options[ 'cpt_gallery' ][ 'titles' ] == 'off')? 'display: none !important;' : '';
$gallery_slide_list     = ($this->theme_options[ 'cpt_gallery' ][ 'list' ] == 'off')? 'display: none !important;' : '';

$gallery_slider_h       = apollo13_make_css_rule( 'height', $this->theme_options[ 'cpt_gallery' ][ 'slider_height' ]);


/*
 *  Works list
 */
$works_brick_width    = apollo13_make_css_rule( 'width', $this->theme_options[ 'cpt_work' ][ 'brick_width' ]);
$temp = $this->theme_options[ 'cpt_work' ][ 'brick_height' ];
// if 0 then height if fluid
if($temp === '0px' || $temp === '0'){
    $works_brick_height = 'height: auto;';
    $works_brick_img_height = '';
}
else{
    $works_brick_height = apollo13_make_css_rule( 'height', $temp);
    $works_brick_img_height = $works_brick_height;
}

$margin = $this->theme_options[ 'cpt_work' ][ 'brick_margin' ];
$margin_half = ((int)$margin)/2;
$works_brick_margin_b = apollo13_make_css_rule( 'margin-bottom', $margin);
$works_brick_margin_r = apollo13_make_css_rule( 'margin-right', $margin_half, '%fpx' );
$works_brick_margin_l = apollo13_make_css_rule( 'margin-left', $margin_half, '%fpx' );
$works_brick_padd_t   = apollo13_make_css_rule( 'padding-top', $margin);
$works_brick_padd_r   = apollo13_make_css_rule( 'padding-right', $margin_half, '%fpx' );
$works_brick_padd_l   = apollo13_make_css_rule( 'padding-left', $margin_half, '%fpx' );
$works_titles         = ($this->theme_options[ 'cpt_work' ][ 'show_titles' ] == 'off')? 'display: none !important;' : '';

//single work
$work_scroller_h      = apollo13_make_css_rule( 'height', $this->theme_options[ 'cpt_work' ][ 'scroller_height' ]);
$work_slider_h        = apollo13_make_css_rule( 'height', $this->theme_options[ 'cpt_work' ][ 'slider_height' ]);
$work_caption         = ($this->theme_options[ 'cpt_work' ][ 'titles' ] == 'off')? 'display: none !important;' : '';
$work_slide_list      = ($this->theme_options[ 'cpt_work' ][ 'list' ] == 'off')? 'display: none !important;' : '';



/*
 *  fonts
 */
$temp               = explode(':', $this->theme_options[ 'fonts' ][ 'normal_fonts' ]);
$normal_fonts       = ($temp[0] === 'default')? '' : apollo13_make_css_rule( 'font-family', $temp[0], '%s, sans-serif' );
$temp               = explode(':', $this->theme_options[ 'fonts' ][ 'titles_fonts' ]);
$titles_fonts       = ($temp[0] === 'default')? '' : apollo13_make_css_rule( 'font-family', $temp[0], '%s, sans-serif' );
$temp               = explode(':', $this->theme_options[ 'fonts' ][ 'nav_menu_fonts' ]);
$nav_menu_font      = ($temp[0] === 'default')? '' : apollo13_make_css_rule( 'font-family', $temp[0], '%s, sans-serif' );


$custom_CSS = $this->theme_options[ 'customize' ][ 'custom_css' ];

/**********************************
 * START OF CSS
 **********************************/
$user_css = '';
//if($color_scheme  == 'dark'){
//    $user_css .= file_get_contents(A13_TPL_CSS_DIR . '/schemes/header_black.css');
//}

$user_css .= "
/* ==================
   GLOBAL
   ==================*/
body{
    $body_image
    $body_bg_attachment
    $cursor_css
}
body,
.widget .title > span,
.wp-paginate .title,
.wp-paginate .gap,
.navigation a,
.widget-title span,
.title-and-nav .title span,
.title-and-nav nav,
#reply-title span,
#cancel-comment-reply-link{
	$body_bg_color
}
.post-media.item-image:after{
    border-top-color: $body_bg_color_val;
}
.variant_2 .post-media.item-image:after,
.variant_masonry .post-media.item-image:after,
.in_post_widget .post-media.item-image:after{
    border-bottom-color: $body_bg_color_val;
}
.variant_short_list .post-media.item-image:after{
    border-right-color: $body_bg_color_val;
}
a{
    $links_color
}
a:hover{
    $links_color_hover
}
h1,h2,h3,h4,h5,h6,
h1 a,h2 a,h3 a,h4 a,h5 a, h6 a,
.page-title,
.widget .title{
    $headings_color
    $titles_fonts
    $headings_weight
    $headings_transform
}
h1 a:hover,h2 a:hover,h3 a:hover,h4 a:hover,h5 a:hover,h6 a:hover,
.post .post-title a:hover, .post a.post-title:hover{
    $headings_color_hover
}

input[type=\"submit\"],
a.a13-button,
a.more-link,
a.project-site,
a.dot-irecommendthis:hover,
a.dot-irecommendthis.active,
.woocommerce a.button,
.woocommerce button.button,
.woocommerce input.button,
.woocommerce #respond input#submit,
.woocommerce #content input.button{
    $button_color
    $button_bg_color
}
input[type=\"submit\"]:hover,
a.a13-button:hover,
a.more-link:hover,
a.project-site:hover,
.woocommerce a.button:hover,
.woocommerce button.button:hover,
.woocommerce input.button:hover,
.woocommerce #respond input#submit:hover,
.woocommerce #content input.button:hover{
    $button_h_color
    $button_h_bg_color
}


/* ==================
   FONTS
   ==================*/
/* All things font(menu, interactive elements, labels).
 * Not used for content text and titles
 */
body,
.a13-button,
input[type=\"submit\"]{
	$nav_menu_font
}

/* Text content font */
.real-content,
.foot-text,
.post-meta,
.navigation,
.widget .post-title,
.widget .content,
div.textwidget,
div.widget_rss li,
div.about-author,
div.comments-area,
.contact-form,
input[type=\"text\"],
input[type=\"search\"],
input[type=\"password\"],
input[type=\"email\"],
input[type=\"url\"],
input[type=\"tel\"],
input[type=\"number\"],
input[type=\"range\"],
input[type=\"date\"],
textarea,
select{
    $normal_fonts
}


/* ==================
   HEADER
   ==================*/
#header,
#access.touch .menu-container{
    $header_bg_color
}
#logo{
	$logo_color
    $logo_font_size
}
#access h3.assistive-text{
    $menu_color
    $menu_font_size
}
#access.touch .top-menu > li a,
.mega-menu > ul > li{
    $menu_sep_color
}
#access.touch .top-menu{
    $menu_sep_bottom_color
}
.top-menu li a,
.top-menu .mega-menu span.title,
.top-menu .mega-menu > ul > li > a{
    $menu_font_size
}
.top-menu li a{
    $menu_color
    $menu_weight
    $menu_transform
}
.top-menu li:hover > a,
.top-menu li.hovered > a,
.top-menu li.current-menu-item > a,
.top-menu li.current-menu-ancestor  > a{
    $menu_hover_color
}
.top-menu li li a{
    $menu_sep_top_color
    $submenu_font_size
    $submenu_color
}
.top-menu li li:hover > a,
.top-menu li li.hovered > a,
.top-menu li li.current-menu-item > a,
.top-menu li li.current-menu-ancestor > a{
    $submenu_hover_color
}
.top-menu li:hover,
.top-menu li.hovered,
.top-menu li.current-menu-item,
.top-menu li.current-menu-ancestor,
.top-menu ul{
    $menu_hover_bg_color
}
.top-menu li li:hover > a,
.top-menu li li.hovered > a,
.top-menu li li.current-menu-item > a,
.top-menu li li.current-menu-ancestor > a{
    $submenu_hover_bg_color
}

.menu-button-container a.a13-button{
    $menu_button_color
    $menu_button_bg_color
}
.menu-button-container a.a13-button:hover{
    $menu_button_h_color
    $menu_button_h_bg_color
}


/* ==================
   FOOTER
   ==================*/
#footer{
    $footer_bg_color
}
#footer .widget h3.title, #footer .widget h3.title a {
    $footer_widget_color
}
.footer-items{
    $footer_font_size
    $footer_font_color
}
#footer a{
    $footer_link_color
}
#footer a:hover{
    $footer_link_hover_color
}


/* ==================
   GALLERIES LIST
   ==================*/
.variant_image #a13-galleries{
     $galleries_brick_padd_t
     $galleries_brick_padd_l
     $galleries_brick_padd_r
}
.variant_image #a13-galleries .g-item{
     $galleries_brick_margin_b
     $galleries_brick_margin_r
     $galleries_brick_margin_l
     $galleries_brick_height
     $galleries_brick_width
}
.variant_image #a13-galleries .g-link i{
    $galleries_brick_img_height
}
.variant_image #a13-galleries .g-link .cov span{
    $galleries_titles
}


/* ==================
   SINGLE GALLERY
   ==================*/
#a13-gallery{
     $gallery_brick_padd_t
     $gallery_brick_padd_l
     $gallery_brick_padd_r
}
#a13-gallery .g-link{
     $gallery_brick_margin_b
     $gallery_brick_margin_r
     $gallery_brick_margin_l
     $gallery_brick_height
     $gallery_brick_width
}
#a13-gallery .g-link i{
    $gallery_brick_img_height
}
.single-gallery #a13-slider-caption{
    $gallery_caption
}
.single-gallery #slide-list{
    $gallery_slide_list
}
.single-gallery .in-post-slider{
    $gallery_slider_h
}


/* ==================
   WORKS LIST
   ==================*/
.variant_image #a13-works{
     $works_brick_padd_t
     $works_brick_padd_l
     $works_brick_padd_r
}
.variant_image #a13-works .g-item{
     $works_brick_margin_b
     $works_brick_margin_r
     $works_brick_margin_l
     $works_brick_height
     $works_brick_width
}
.variant_image #a13-works .g-link i{
    $works_brick_img_height
}
.variant_image #a13-works .g-link .cov span{
    $works_titles
}


/* ==================
   SINGLE WORK
   ==================*/
.single-work #a13-scroll-pan{
    $work_scroller_h
}
.single-work .in-post-slider{
    $work_slider_h
}
.single-work #a13-slider-caption{
    $work_caption
}
.single-work #slide-list{
    $work_slide_list
}

   
/* ==================
   BLOG
   ==================*/
.variant_masonry #only-posts-here{
    $blog_brick_padd_l
    $blog_brick_padd_r
}
.variant_masonry .archive-item{
    $blog_brick_width
    $blog_brick_margin_b
    $blog_brick_margin_r
    $blog_brick_margin_l
}


/* ==================
   RESPONSIVE
   ==================*/
@media only screen and (max-width: 680px) {
    .menu-button-container{
        $header_bg_color
        $menu_button_show_680
    }
}
/*@media print,
(-o-min-device-pixel-ratio: 5/4),
(-webkit-min-device-pixel-ratio: 1.25),
(min-resolution: 120dpi) {
}*/

/* ==================
   CUSTOM CSS
   ==================*/
".stripslashes($custom_CSS)."
";

return $user_css;
