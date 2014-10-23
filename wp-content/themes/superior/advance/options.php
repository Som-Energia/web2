<?php

/*
 * TYPES:
 * fieldset
 * radio
 * wp_dropdown_galleries
 * input
 * textarea
 * color
 * upload
 * select
 * slider
 * social
 *
 * */

	function apollo13_settings_options(){

		$opt = array(
            array(
                'name' => __be( 'Front page' ),
                'type' => 'fieldset',
                'default' => 1,
                'id' => 'fieldset_front_page',
                'help' => '#!/what_to_show_on_front_page'
            ),
            array(
                'name' => __be( 'What to show on front page?' ),
                'desc' => __be( 'If you choose <strong>Page</strong> then make sure that in Settings->Reading->Front page displays'
                                . ' you selected <strong>A static page</strong>, that you wish to use.<br />' ),

                'id' => 'fp_variant',
                'default' => 'page',
                'options' => array(
                    'page'          => __be( 'Page' ),
                    'blog'          => __be( 'Blog' ),
                    'works_list'    => __be( 'Works list' ),
                    'galleries_list'    => __be( 'Galleries list' ),
                    'gallery'       => __be( 'Selected gallery' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Select gallery to use as front page' ),
                'desc' => '',
                'id' => 'fp_gallery',
                'default' => '',
                'type' => 'wp_dropdown_galleries',
            ),


			array(
				'name' => __be( 'Google Analytics' ),
				'type' => 'fieldset',
                'default' => 1,
                'id' => 'fieldset_google_anal'
			),
			array(
				'name' => __be( 'Enter code here from GA here:' ),
                'desc' => __be( 'Enter whole code that beggins with <code>&lt;script type="text/javascript"&gt;</code> and ends with <code>&lt;/script&gt;</code>.' ),
				'id' => 'ga_code',
				'default' => '',
				'type' => 'textarea',
			),
		);
		
		return $opt;
	}

	function apollo13_appearance_options(){
        $cursors = array();
        $dir = A13_TPL_GFX_DIR.'/cursors';
        if( is_dir( $dir ) ) {
            //The GLOB_BRACE flag is not available on some non GNU systems, like Solaris. So we use merge:-)
            foreach ( (array)glob($dir.'/*.png') as $file ){
                $cursors[ basename($file) ] = basename($file);
            }
        }

		$opt = array(
            array(
                'name' => __be( 'Main appearance settings' ),
                'type' => 'fieldset',
                'default' => 0,
                'id' => 'fieldset_main_app_settings',
                'help' => '#!/main_appearance_settings'
            ),
            array(
                'name' => __be( 'Layout style' ),
                'desc' => __be( 'It affects layout in wide resolutions.' ),
                'id' => 'layout_style',
                'default' => 'wide',
                'options' => array(
                    'narrow' => __be( 'Narrow' ),
                    'wide' => __be( 'Wide' ),
                ),
                'type' => 'radio',
            ),
//            array(
//                'name' => __be( 'Color scheme' ),
//                'desc' => '',
//                'id' => 'color_scheme',
//                'default' => 'light',
//                'options' => array(
//                    'light' => __be( 'Light' ),
//                    'dark'  => __be( 'Dark' ),
//                ),
//                'type' => 'radio',
//            ),
            array(
                'name' => __be( 'Favicon' ),
                'desc' =>__be( 'Enter an URL or upload an image for favicon. It will appear in adress bar or on tab in browser. Image should be square (16x16px or 32x32px). Paste the full URL (include <code>http://</code>).' ),
                'id' => 'favicon',
                'default' => get_template_directory_uri().'/images/favicon.png',
                'button_text' => __be('Upload/Select Image'),
                'media_button_text' => __be('Insert favicon'),
                'type' => 'upload'
            ),
            array(
                'name' => __be( 'Custom background image' ),
                'desc' =>__be( 'Enter an URL or upload an image for background. Paste the full URL (include <code>http://</code>).' ),
                'id' => 'body_image',
                'default' => '',
                'button_text' => __be('Upload/Select Image'),
                'type' => 'upload'
            ),
            array(
                'name' => __be( 'How to fit background image' ),
                'desc' => __be( 'In Internet Explorer 8 and lower whatever you will choose(except <em>repeat</em>) it will look like "Just center"' ),
                'id' => 'body_image_fit',
                'default' => 'cover',
                'options' => array(
                    'cover'     => __be( 'Cover' ),
                    'contain'   => __be( 'Contain' ),
                    'fitV'      => __be( 'Fit Vertically' ),
                    'fitH'      => __be( 'Fit Horizontally' ),
                    'center'    => __be( 'Just center' ),
                    'repeat'    => __be( 'Repeat' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Background color' ),
                'desc' => __be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'body_bg_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Background attachment' ),
                'desc' => '',
                'id' => 'body_bg_attachment',
                'default' => 'fixed',
                'options' => array(
                    'fixed' => __be( 'fixed' ),
                    'scroll' => __be( 'scroll' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Mouse cursor' ),
                'desc' => '',
                'id' => 'custom_cursor',
                'default' => 'select',
                'options' => array(
                    'default' => __be( 'Use normal' ),
                    'select'  => __be( 'Use one of predefined' ),
                    'custom' => __be( 'Use custom' ),
                ),
                'type' => 'radio',
                'switch' => true,
            ),
            array(
                'name' => 'select',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Cursors' ),
                'desc' => '',
                'id' => 'cursor_select',
                'default' => 'empty_black_white.png',
                'options' => $cursors,
                'type' => 'select',
            ),
            array(
                /*'name' => 'select',*/
                'type' => 'switch-group-end'
            ),
            array(
                'name' => 'custom',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Custom cursor image' ),
                'desc' =>__be( 'Enter an URL or upload an image for cursor. Paste the full URL (include <code>http://</code>).' ),
                'id' => 'cursor_image',
                'default' => get_template_directory_uri().'/images/cursors/cursor.png',
                'button_text' => __be('Upload/Select Image'),
                'media_button_text' => __be('Insert cursor'),
                'type' => 'upload'
            ),
            array(
                /*'name' => 'custom',*/
                'type' => 'switch-group-end'
            ),
            array(
                /*'id' => 'custom_cursor',  just for readability */
                'type' => 'end-switch',
            ),

            array(
                'name' => __be( 'Links color' ),
                'desc' =>__be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'links_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Links color hover' ),
                'desc' =>__be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'links_color_hover',
                'default' => '',
                'type' => 'color'
            ),



            array(
                'name' => __be( 'Buttons colors' ),
                'type' => 'fieldset',
                'default' => 0,
                'id' => 'fieldset_buttons_settings',
            ),
            array(
                'name' => __be( 'Button color' ),
                'desc' => __be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'button_bg_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Button text color' ),
                'desc' => __be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'button_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Button hover color' ),
                'desc' => __be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'button_hover_bg_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Button hover text color' ),
                'desc' => __be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'button_hover_color',
                'default' => '',
                'type' => 'color'
            ),



            array(
                'name' => __be( 'Customize Logo' ),
                'type' => 'fieldset',
                'default' => 0,
                'id' => 'fieldset_logo_settings',
                'help' => '#!/customize_logo'
            ),
            array(
                'name' => __be( 'Logo type' ),
                'desc' => '',
                'id' => 'logo_type',
                'default' => 'image',
                'options' => array(
                    'image' => __be( 'Image' ),
                    'text' => __be( 'Text' ),
                ),
                'type' => 'radio',
                'switch' => true,
            ),
            array(
                'name' => 'image',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Logo image' ),
                'desc' => __be( 'Enter an URL or upload an image for logo. Paste the full URL (include <code>http://</code>).' ),
                'id' => 'logo_image',
                'default' => get_template_directory_uri().'/images/logo.png',
                'button_text' => __be('Upload/Select Image'),
                'media_button_text' => __be('Insert logo'),
                'type' => 'upload'
            ),
            array(
                'name' => __be( 'Logo image for HIGH DPI screen' ),
                'desc' => __be( 'For example Retina(iPhone/iPad) screen is HIGH DPI.' ).' '. __be( 'Enter an URL or upload an image for logo. Paste the full URL (include <code>http://</code>).' ),
                'id' => 'logo_image_high_dpi',
                'default' => '',//get_template_directory_uri().'/images/logo@2x.png',
                'button_text' => __be('Upload/Select Image'),
                'media_button_text' => __be('Insert logo'),
                'type' => 'upload'
            ),
            array(
                'name' => 'HIGH DPI logo sizes',
                'desc' => '',
                'id' => 'logo_image_high_dpi_sizes',
                'default' => '',
                'type' => 'hidden'
            ),
//            array(
//                'name' => __be( 'High DPI logo size in %' ),
//                'desc' =>__be( 'It is used for high DPI devices(like Retina in iPad/iPhone). By shrinking image it looks sharp on these devices. Read more in documentation.' ),
//                'id' => 'logo_shrink',
//                'default' => '75%',
//                'unit' => '%',
//                'min' => 50,
//                'max' => 100,
//                'type' => 'slider'
//            ),
            array(
                /*'name' => 'image',*/
                'type' => 'switch-group-end'
            ),
            array(
                'name' => 'text',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Text in your logo' ),
                'desc' => '',
                'id' => 'logo_text',
                'default' => 'Superior',
                'type' => 'input'
            ),
            array(
                'name' => __be( 'Logo text color' ),
                'desc' =>__be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'logo_color',
                'default' => '#000000',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Logo font size' ),
                'desc' =>__be( 'Use slider to set proper font size.' ),
                'id' => 'logo_font_size',
                'default' => '26px',
                'unit' => 'px',
                'type' => 'slider'
            ),
            array(
                /*'name' => 'text',*/
                'type' => 'switch-group-end'
            ),
            array(
                /*'id' => 'logo_type',  just for readability */
                'type' => 'end-switch',
            ),


            array(
                'name' => __be( 'Static pages' ),
                'type' => 'fieldset',
                'default' => 0,
                'id' => 'fieldset_pages'
            ),
            array(
                'name' => __be( 'Page sidebar' ),
                'desc' => __be( 'It affects look of pages. You can change it in each page settings.' ),
                'id' => 'page_sidebar',
                'default' => 'off',
                'options' => array(
                    'left-sidebar'              => __be( 'Sidebar on the left' ),
                    'left-sidebar_and_nav'      => __be( 'Children Navigation + sidebar on the left' ),
                    'left-nav'                  => __be( 'Only children Navigation on the left' ),
                    'right-sidebar'             => __be( 'Sidebar on the right' ),
                    'right-sidebar_and_nav'     => __be( 'Children Navigation + sidebar on the right' ),
                    'right-nav'                 => __be( 'Only children Navigation on the right' ),
                    'off'                       => __be( 'Off' ),
                ),
                'type' => 'select',
            ),


            array(
                'name' => __be( 'Header top bar' ),
                'type' => 'fieldset',
                'default' => 0,
                'id' => 'fieldset_header_top_bar'
            ),
            array(
                'name' => __be( 'Top bar of header' ),
                'desc' => '',
                'id' => 'header_top_bar',
                'default' => 'on',
                'options' => array(
                    'on' => __be( 'Enable' ),
                    'off' => __be( 'Disable' ),
                ),
                'type' => 'radio',
                'switch' => true,
            ),
            array(
                'name' => 'on',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Color' ),
                'desc' => '',
                'id' => 'top_bar_color',
                'default' => 'dark',
                'options' => array(
                    'dark-theme' => __be( 'Dark' ),
                    'light-theme' => __be( 'Light' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Text' ),
                'desc' => __be( 'You can use HTML here.' ),
                'id' => 'top_bar_text',
                'default' => '',
                'type' => 'textarea',
            ),
            array(
                'name' => __be( 'Theme social icons' ),
                'desc' => '',
                'id' => 'top_bar_socials',
                'default' => 'on',
                'options' => array(
                    'on' => __be( 'Enable' ),
                    'off' => __be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                /*'name' => 'on',*/
                'type' => 'switch-group-end'
            ),
            array(
                /*'id' => 'header_top_bar',  just for readability */
                'type' => 'end-switch',
            ),


            array(
                'name' => __be( 'Customize header' ),
                'type' => 'fieldset',
                'default' => 0,
                'id' => 'fieldset_header_app'
            ),
            array(
                'name' => __be( 'Header style' ),
                'desc' => __be( 'In centered style logo takes separate row in wide resolution.' ),
                'id' => 'header_style',
                'default' => 'normal',
                'options' => array(
                    'normal' => __be( 'Normal' ),
                    'centered' => __be( 'Centered' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Fixed header' ),
                'desc' => __be( 'If enabled, header will remain fixed while scrolling page.' ),
                'id' => 'fixed_header',
                'default' => 'on',
                'options' => array(
                    'on' => __be( 'Enable' ),
                    'off' => __be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Background color' ),
                'desc' =>__be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'header_bg_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Menu main links/"Mega menu group" font size' ),
                'desc' =>__be( 'Use slider to set proper font size.' ),
                'id' => 'menu_font_size',
                'default' => '13px',
                'unit' => 'px',
                'min' => 10,
                'max' => 30,
                'type' => 'slider'
            ),
            array(
                'name' => __be( 'Menu links color' ),
                'desc' =>__be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'menu_color',
                'default' => '#585858',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Menu links hover/active color' ),
                'desc' =>__be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'menu_hover_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Menu separator color' ),
                'desc' =>__be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'menu_sep_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Menu hover background color' ),
                'desc' =>__be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'menu_hover_bg_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Submenu links font size' ),
                'desc' =>__be( 'Use slider to set proper font size.' ),
                'id' => 'submenu_font_size',
                'default' => '12px',
                'unit' => 'px',
                'type' => 'slider'
            ),
            array(
                'name' => __be( 'Submenu links color' ),
                'desc' =>__be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'submenu_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Submenu links hover/active color' ),
                'desc' =>__be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'submenu_hover_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Submenu hover/active background color' ),
                'desc' =>__be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'submenu_hover_bg_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Menu font weight' ),
                'desc' => '',
                'id' => 'menu_weight',
                'default' => 'bold',
                'options' => array(
                    'normal' => __be( 'Normal' ),
                    'bold' => __be( 'Bold' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Menu text transform' ),
                'desc' => '',
                'id' => 'menu_transform',
                'default' => 'none',
                'options' => array(
                    'none' => __be( 'None' ),
                    'uppercase' => __be( 'Uppercase' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Switch on/off header search form' ),
                'desc' => '',
                'id' => 'header_search',
                'default' => 'on',
                'options' => array(
                    'on' => __be( 'Enable' ),
                    'off' => __be( 'Disable' ),
                ),
                'type' => 'radio',
            ),


            array(
                'name' => __be( 'Button near main menu' ),
                'type' => 'fieldset',
                'default' => 0,
                'id' => 'fieldset_menu_button'
            ),
            array(
                'name' => __be( 'Turn on/off this button' ),
                'desc' => '',
                'id' => 'menu_button',
                'default' => 'on',
                'options' => array(
                    'on' => __be( 'On' ),
                    'off' => __be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'URL' ),
                'desc' => '',
                'id' => 'menu_button_url',
                'default' => '',
                'placeholder' => 'http://themeforest.net/user/apollo13',
                'type' => 'input'
            ),
            array(
                'name' => __be( 'Text on button' ),
                'desc' => '',
                'id' => 'menu_button_text',
                'default' => 'Buy it!',
                'type' => 'input'
            ),
            array(
                'name' => __be( 'Open in new tab' ),
                'desc' => '',
                'id' => 'menu_button_new_tab',
                'default' => '1',
                'type' => 'radio',
                'options' => array(
                    '1' => __be( 'Yes' ),
                    '0' => __be( 'No' ),
                ),
            ),
            array(
                'name' => __be( 'Show button in width below 680px' ),
                'desc' => '',
                'id' => 'menu_button_show_under_680',
                'default' => '1',
                'type' => 'radio',
                'options' => array(
                    '1' => __be( 'Yes' ),
                    '0' => __be( 'No' ),
                ),
            ),
            array(
                'name' => __be( 'Button color' ),
                'desc' => __be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'menu_button_bg_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Button text color' ),
                'desc' => __be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'menu_button_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Button hover color' ),
                'desc' => __be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'menu_button_hover_bg_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Button hover text color' ),
                'desc' => __be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'menu_button_hover_color',
                'default' => '',
                'type' => 'color'
            ),


            array(
                'name' => __be( 'Customize title bar' ),
                'type' => 'fieldset',
                'default' => 0,
                'id' => 'fieldset_title_bar'
            ),
            array(
                'name' => __be( 'Custom background image' ),
                'desc' =>__be( 'Enter an URL or upload an image for background. Paste the full URL (include <code>http://</code>).' ),
                'id' => 'title_bar_image',
                'default' => get_template_directory_uri().'/images/subtlenet2.png',
                'button_text' => __be('Upload/Select Image'),
                'type' => 'upload'
            ),
            array(
                'name' => __be( 'How to fit background image' ),
                'desc' => __be( 'In Internet Explorer 8 and lower whatever you will choose(except <em>repeat</em>) it will look like "Just center"' ),
                'id' => 'title_bar_image_fit',
                'default' => 'repeat',
                'options' => array(
                    'cover'     => __be( 'Cover' ),
                    'contain'   => __be( 'Contain' ),
                    'fitV'      => __be( 'Fit Vertically' ),
                    'fitH'      => __be( 'Fit Horizontally' ),
                    'center'    => __be( 'Just center' ),
                    'repeat'    => __be( 'Repeat' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Background color' ),
                'desc' => __be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'title_bar_bg_color',
                'default' => '#e9e9e5',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Title color' ),
                'desc' => __be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'title_bar_title_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Space in top and bottom' ),
                'desc' => __be( 'Use slider to set proper size.' ),
                'id' => 'title_bar_space_width',
                'default' => '25px',
                'unit' => 'px',
                'min' => 0,
                'max' => 200,
                'type' => 'slider'
            ),


            array(
                'name' => __be( 'Customize footer' ),
                'type' => 'fieldset',
                'default' => 0,
                'id' => 'fieldset_footer_app'
            ),
            array(
                'name' => __be( 'Background color' ),
                'desc' =>__be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'footer_bg_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Widget title font color' ),
                'desc' =>__be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'footer_widget_title_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Font color' ),
                'desc' =>__be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'footer_font_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Font size' ),
                'desc' =>__be( 'Use slider to set proper font size.' ),
                'id' => 'footer_font_size',
                'default' => '12px',
                'unit' => 'px',
                'type' => 'slider'
            ),
            array(
                'name' => __be( 'Links color' ),
                'desc' =>__be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'footer_link_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Links color hover' ),
                'desc' =>__be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'footer_link_hover_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Footer text' ),
                'desc' => __be( 'You can use HTML here.' ),
                'id' => 'footer_text',
                'default' => '&copy; 2013 Superior Theme. Proudly made by <a target="_blank" href="http://themeforest.net/user/apollo13/portfolio">Apollo13</a>
Powered by <a target="_blank" href="http://wordpress.org/">WordPress&trade;</a>. Code is a poetry',
                'type' => 'textarea',
            ),
            array(
                'name' => __be( 'Image in foot text' ),
                'desc' => __be( 'It will be aligned left in footer text area.' ),
                'id' => 'footer_text_image',
                'default' => get_template_directory_uri().'/images/icon3.png',
                'button_text' => __be('Upload/Select Image'),
                'media_button_text' => __be('Insert image'),
                'type' => 'upload'
            ),
		);
		
		return $opt;
	}

    function apollo13_fonts_options(){
        $classic_fonts = array(
            'default'           => __be( 'Defined in CSS' ),
            'arial'             => __be( 'Arial' ),
            'calibri'           => __be( 'Calibri' ),
            'cambria'           => __be( 'Cambria' ),
            'georgia'           => __be( 'Georgia' ),
            'tahoma'            => __be( 'Tahoma' ),
            'times new roman'   => __be( 'Times new roman' ),
        );

        $opt = array(
            array(
                'name' => __be( 'Fonts settings' ),
                'type' => 'fieldset',
                'default' => 1,
                'id' => 'fieldset_fonts',
                'help' => '#!/fonts_settings'
            ),
            array(
                'name' => __be( 'Font for top nav menu, interactive elements, short labels, etc.:' ),
                'desc' => __be( 'If you choose <strong>classic font</strong> then remember that this setting depends on fonts installed on client device.<br />'.
                    'If you choose <strong>google font</strong> then remember to choose needed variants and subsets. Read more in documentation.<br />'.
                    'For preview google font is loaded with variants regular and 700, and all available subsets.'),
                'id' => 'nav_menu_fonts',
                'default' => 'Open Sans:regular,italic,600,600italic',
                'options' => $classic_fonts,
                'type' => 'font',
            ),
            array(
                'name' => __be( 'Font for Titles/Headings:' ),
                'desc' => __be( 'If you choose <strong>classic font</strong> then remember that this setting depends on fonts installed on client device.<br />'.
                    'If you choose <strong>google font</strong> then remember to choose needed variants and subsets. Read more in documentation.<br />'.
                    'For preview google font is loaded with variants regular and 700, and all available subsets.'),
                'id' => 'titles_fonts',
                'default' => 'Lato:regular,italic,700,700italic',
                'options' => $classic_fonts,
                'type' => 'font',
            ),
            array(
                'name' => __be( 'Font for normal(content) text:' ),
                'desc' => __be( 'If you choose <strong>classic font</strong> then remember that this setting depends on fonts installed on client device.<br />'.
                    'If you choose <strong>google font</strong> then remember to choose needed variants and subsets. Read more in documentation.<br />'.
                    'For preview google font is loaded with variants regular and 700, and all available subsets.'),
                'id' => 'normal_fonts',
                'default' => 'Lato:regular,italic,700,700italic',
                'options' => $classic_fonts,
                'type' => 'font',
            ),

            array(
                'name' => __be( 'Headings styles' ),
                'type' => 'fieldset',
                'default' => 0,
                'id' => 'fieldset_headings_styles',
            ),
            array(
                'name' => __be( 'Headings/Titles color' ),
                'desc' =>__be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'headings_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Headings/Titles color hover' ),
                'desc' =>__be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'headings_color_hover',
                'default' => '#1ABC9C',
                'type' => 'color'
            ),
            array(
                'name' => __be( 'Headings/Titles font weight' ),
                'desc' => '',
                'id' => 'headings_weight',
                'default' => 'bold',
                'options' => array(
                    'normal' => __be( 'Normal' ),
                    'bold' => __be( 'Bold' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Headings/Titles text transform' ),
                'desc' => '',
                'id' => 'headings_transform',
                'default' => 'none',
                'options' => array(
                    'none' => __be( 'None' ),
                    'uppercase' => __be( 'Uppercase' ),
                ),
                'type' => 'radio',
            ),
        );

        return $opt;
    }

    function apollo13_blog_options(){

        $opt = array(
            array(
                'name' => __be( 'Blog, Search &amp; Archives appearance' ),
                'type' => 'fieldset',
                'default' => 1,
                'id' => 'fieldset_blog',
                'help' => '#!/blog_menu'
            ),
            array(
                'name' => __be( 'Type of post excerpts' ),
                'desc' => __be( 'In Manual mode excerpts are used only if you add more tag (&lt;!--more--&gt;).<br />' .
                    'In Automatic mode if you won\'t provide more tag or explicit excerpt, content of post will be cut automatic.<br />' .
                    'This setting only concerns blog list, archive list, search results. <br />' .
                    'Read more in <strong>Adding New Posts/Pages -&gt; Posts list / blog</strong> section in documentation.' ),
                'id' => 'excerpt_type',
                'default' => 'auto',
                'options' => array(
                    'auto'      => __be( 'Automatic' ),
                    'manual'    => __be( 'Manual' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Blog appearance variant:' ),
                'desc' => __be( 'It affects look of main blog page.' ),
                'id' => 'blog_variant',
                'default' => 'variant_2',
                'options' => array(
                    'variant_1'             => __be( 'Classic 1: First title, next post media' ),
                    'variant_2'             => __be( 'Classic 2: First post media, next title' ),
                    'variant_3'             => __be( 'Classic 3: Everything centered' ),
                    'variant_short_list'    => __be( 'Short list' ),
                    'variant_masonry'       => __be( 'Masonry bricks' ),
                ),
                'type' => 'select',
                'switch' => true,
            ),
            array(
                'name' => 'variant_masonry',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Brick width' ),
                'desc' =>__be( 'Use slider to set proper size. Width set here will be dynamically changed up to 120% to stretch bricks to fill space.' ),
                'id' => 'brick_width',
                'default' => '480px',
                'unit' => 'px',
                'min' => 200,
                'max' => 600,
                'type' => 'slider'
            ),
            array(
                'name' => __be( 'Brick margin' ),
                'desc' =>__be( 'Use slider to set proper size.' ),
                'id' => 'brick_margin',
                'default' => '30px',
                'unit' => 'px',
                'min' => 0,
                'max' => 100,
                'type' => 'slider'
            ),
            array(
                /*'name' => 'variant_masonry',*/
                'type' => 'switch-group-end'
            ),
            array(
                /*'id' => 'blog_variant',  just for readability */
                'type' => 'end-switch',
            ),
            array(
                'name' => __be( 'Blog sidebar' ),
                'desc' => __be( 'It affects look of main blog page.' ),
                'id' => 'blog_sidebar',
                'default' => 'right-sidebar',
                'options' => array(
                    'left-sidebar'  => __be( 'Left' ),
                    'right-sidebar' => __be( 'Right' ),
                    'off'           => __be( 'Off' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Archive sidebar' ),
                'desc' => __be( 'It affects look of search and archive pages.' ),
                'id' => 'archive_sidebar',
                'default' => 'right-sidebar',
                'options' => array(
                    'left-sidebar'  => __be( 'Left' ),
                    'right-sidebar' => __be( 'Right' ),
                    'off'           => __be( 'Off' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'RSS icon' ),
                'desc' => '',
                'id' => 'info_bar_rss',
                'default' => 'on',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Display post Media' ),
                'desc' => __be( 'You can set to not display post media(featured image/video/slider) inside of post brick.' ),
                'id' => 'blog_media',
                'default' => 'on',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Post meta: Date' ),
                'desc' => '',
                'id' => 'blog_date',
                'default' => 'on',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Post meta: Author' ),
                'desc' => '',
                'id' => 'blog_author',
                'default' => 'on',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Post meta: Comments number' ),
                'desc' => '',
                'id' => 'blog_comments',
                'default' => 'on',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Post meta: Tags' ),
                'desc' => '',
                'id' => 'blog_tags',
                'default' => 'on',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Post meta: Categories' ),
                'desc' => '',
                'id' => 'blog_cats',
                'default' => 'on',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
                'type' => 'radio',
            ),

            //------------------------------------
            array(
                'name' => __be( 'Post appearance' ),
                'type' => 'fieldset',
                'default' => 0,
                'id' => 'fieldset_post'
            ),
            array(
                'name' => __be( 'Post sidebar' ),
                'desc' => __be( 'It affects look of posts. You can change it in each post.' ),
                'id' => 'post_sidebar',
                'default' => 'right-sidebar',
                'options' => array(
                    'left-sidebar'  => __be( 'Left' ),
                    'right-sidebar' => __be( 'Right' ),
                    'off'           => __be( 'Off' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Display post Media' ),
                'desc' => __be( 'You can set to not display post media(featured image/video/slider) inside of post.' ),
                'id' => 'post_media',
                'default' => 'on',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Author info in post' ),
                'desc' => __be( 'Will show information about author below post content. Not displayed in blog post list.' ),
                'id' => 'author_info',
                'default' => 'on',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Related posts under content of post' ),
                'desc' => __be( 'Will show up to 3 related posts to current post.' ),
                'id' => 'posts_widget',
                'default' => 'on',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Post meta: Date' ),
                'desc' => '',
                'id' => 'post_date',
                'default' => 'on',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Post meta: Author' ),
                'desc' => '',
                'id' => 'post_author',
                'default' => 'on',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Post meta: Comments number' ),
                'desc' => '',
                'id' => 'post_comments',
                'default' => 'on',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Post meta: Tags' ),
                'desc' => '',
                'id' => 'post_tags',
                'default' => 'on',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Post meta: Categories' ),
                'desc' => '',
                'id' => 'post_cats',
                'default' => 'on',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
                'type' => 'radio',
            ),
        );

        return $opt;
    }
	
	function apollo13_cpt_work_options(){
			
		$opt = array(
			array(
				'name' => __be( 'Works main settings' ),
				'type' => 'fieldset',
                'default' => 1,
                'id' => 'fieldset_works',
                'help' => '#!/works_menu'
			),
			array(
				'name' => __be( 'Work slug name' ),
				'desc' => __be( 'Don\'t change this if you don\'t have to. Remember that if you use nice permalinks(eg. <code>yoursite.com/page-about-me</code>, <code>yoursite.com/album/damn-empty/</code>) then <strong>NONE of your static pages</strong> should have same slug as this, or pagination will break and other problems may appear.' ),
				'id' => 'cpt_post_type_work',
				'default' => 'work',
				'type' => 'input',
			),
            array(
                'name' => __be( 'Related works under content of work' ),
                'desc' => __be( 'Will show up to 3 related works to current work.' ),
                'id' => 'posts_widget',
                'default' => 'on',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Comments' ),
                'desc' => '',
                'id' => 'comments',
                'default' => 'on',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Show work genres in single view' ),
                'desc' => '',
                'id' => 'genres',
                'default' => 'on',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
                'type' => 'radio',
            ),


			array(
				'name' => __be( 'Works list page settings' ),
				'type' => 'fieldset',
                'default' => 1,
                'id' => 'fieldset_works_list',
                'help' => '#!/works_menu'
			),
			array(
				'name' => __be( 'Works main page' ),
				'desc' => __be( 'Select page that is your Works list page. It will make working some features. If you setup Work list as your front page then you don\'t have to set any page here.' ),
				'id' => 'cpt_work_page',
				'default' => 0,
				'type' => 'wp_dropdown_pages',
			),
            array(
                'name' => __be( 'Work list page title' ),
                'desc' => '',
                'id' => 'works_list_title',
                'default' => __fe('Portfolio'),
                'type' => 'input',
            ),

            array(
                'name' => __be( 'Items displayed Full width' ),
                'desc' => __be( 'Part of layout with works items will be displayed on full width. In other case items will be displayed in standard layout "box".' ),
                'id' => 'full_width',
                'default' => 'on',
                'options' => array(
                    'on' => __be( 'Enable' ),
                    'off' => __be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Where to show category filter' ),
                'desc' => __be( 'If <strong>Above</strong> is selected, place it will be displayed depends on <strong>Works appearance variant</strong>' ),
                'id' => 'filter_place',
                'default' => 'title',
                'options' => array(
                    'title'             => __be( 'Title bar' ),
//                    'sidebar'           => __be( 'Sidebar' ),
                    'above'             => __be( 'Above' ),
                    'off'               => __be( "Don't show filter" ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Filter by default is' ),
                'desc' => '',
                'id' => 'filter_open',
                'default' => 'on',
                'options' => array(
                    'on' => __be( 'Open' ),
                    'off' => __be( 'Closed' ),
                ),
                'type' => 'radio',
            ),

            array(
                'name' => __be( 'Works appearance variant:' ),
                'desc' => __be( 'It affects look of works list page.' ),
                'id' => 'works_variant',
                'default' => 'variant_2',
                'options' => array(
                    'variant_1'             => __be( 'Classic 1: Big-sized images' ),
                    'variant_2'             => __be( 'Classic 2: Medium-sized images' ),
                    'variant_3'             => __be( 'Classic 3: Small-sized images' ),
                    'variant_image'         => __be( 'Image with "on hover title"' ),
                ),
                'type' => 'select',
                'switch' => true,
            ),
            array(
                'name' => 'variant_image',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Show works titles' ),
                'desc' => '',
                'id' => 'show_titles',
                'default' => 'on',
                'type' => 'radio',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
            ),
            array(
                'name' => __be( 'Hover Effect' ),
                'desc' => '',
                'id' => 'hover_type',
                'default' => 'cover-loop',
                'type' => 'radio',
                'options' => array(
                    'cover-loop' => __be( 'Cover' ),/* cause of CSS class collision */
                    'uncover' => __be( 'Uncover' )
                ),
            ),
            array(
                'name' => __be( 'Zoom on hover' ),
                'desc' => '',
                'id' => 'hover_zoom',
                'default' => 'on',
                'type' => 'radio',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
            ),
            array(
                'name' => __be( 'Cover width' ),
                'desc' =>__be( 'Use slider to set proper size.' ),
                'id' => 'brick_width',
                'default' => '420px',
                'unit' => 'px',
                'min' => 50,
                'max' => 600,
                'type' => 'slider'
            ),
            array(
                'name' => __be( 'Cover height' ),
                'desc' =>__be( 'Use slider to set proper size. If you want photo to not be cropped, set to 0.' ),
                'id' => 'brick_height',
                'default' => '320px',
                'unit' => 'px',
                'min' => 0,
                'max' => 600,
                'type' => 'slider'
            ),
            array(
                'name' => __be( 'Cover margin' ),
                'desc' =>__be( 'Use slider to set proper size.' ),
                'id' => 'brick_margin',
                'default' => '0',
                'unit' => 'px',
                'min' => 0,
                'max' => 100,
                'type' => 'slider'
            ),
            array(
                /*'name' => 'variant_image',*/
                'type' => 'switch-group-end'
            ),
            array(
                /*'id' => 'works_variant',  just for readability */
                'type' => 'end-switch',
            ),



            array(
                'name' => __be( 'Single work appearance(Scroller)' ),
                'type' => 'fieldset',
                'default' => 0,
                'id' => 'fieldset_work_app_sc'
            ),
            array(
                'name' => __be( 'Scroller height' ),
                'desc' => __be( 'Use slider to set proper size.' ) . ' ' . __be( 'Global for all works.' ),
                'id' => 'scroller_height',
                'default' => '500px',
                'unit' => 'px',
                'min' => 100,
                'max' => 700,
                'type' => 'slider'
            ),
            array(
                'name' => __be( 'Images size' ),
                'desc' => __be( 'You can use images that you have uploaded, or resized versions.' ),
                'id' => 'scroller_images',
                'default' => 'resized',
                'options' => array(
                    'full' => __be( 'Original' ),
                    'resized' => __be( 'Resized' ),
                ),
                'type' => 'radio',
            ),


            array(
                'name' => __be( 'Single work appearance(Slider)' ),
                'type' => 'fieldset',
                'default' => 0,
                'id' => 'fieldset_work_app_sl'
            ),
            array(
                'name' => __be( 'Slider height' ),
                'desc' =>__be( 'Use slider to set proper size.' ),
                'id' => 'slider_height',
                'default' => '400px',
                'unit' => 'px',
                'min' => 100,
                'max' => 700,
                'type' => 'slider'
            ),
            array(
                'name' => __be( 'Images size' ),
                'desc' => __be( 'You can use images that you have uploaded, or resized versions.' ),
                'id' => 'slider_images',
                'default' => 'full',
                'options' => array(
                    'full' => __be( 'Original' ),
                    'resized' => __be( 'Resized' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Autoplay' ),
                'desc' => __be( 'If autoplay is on, slider will run on page load. Global setting, but you can change this in each work.' ),
                'id' => 'autoplay',
                'default' => '1',
                'options' => array(
                    '1' => __be( 'Enable' ),
                    '0' => __be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Slide interval(ms)' ),
                'desc' =>__be( 'Time between slide transitions.' ) . ' ' . __be( 'Global for all works.' ),
                'id' => 'slide_interval',
                'default' => 7000,
                'unit' => '',
                'min' => 0,
                'max' => 15000,
                'type' => 'slider'
            ),
            array(
                'name' => __be( 'Transition type' ),
                'desc' =>__be( 'Animation between slides.' ),
                'id' => 'transition_type',
                'default' => '2',
                'options' => array(
                    '0' => __be( 'None' ),
                    '1' => __be( 'Fade' ),
                    '2' => __be( 'Carousel' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Transition speed(ms)' ),
                'desc' =>__be( 'Speed of transition.' ) . ' ' . __be( 'Global for all works.' ),
                'id' => 'transition_time',
                'default' => 600,
                'unit' => '',
                'min' => 0,
                'max' => 10000,
                'type' => 'slider'
            ),
            array(
                'name' => __be( 'Titles' ),
                'desc' =>__be( 'Show image/video titles.' ) . ' ' . __be( 'Global for all works.' ),
                'id' => 'titles',
                'default' => 'on',
                'options' => array(
                    'on' => __be( 'Enable' ),
                    'off' => __be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'List of slides(buttons)' ),
                'desc' => __be( 'Global for all works.' ),
                'id' => 'list',
                'default' => 'on',
                'options' => array(
                    'on' => __be( 'Enable' ),
                    'off' => __be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
		);
		
		return $opt;
	}

    function apollo13_cpt_gallery_options(){
        $opt = array(
            array(
                'name' => __be( 'Galleries list page settings' ),
                'type' => 'fieldset',
                'default' => 1,
                'id' => 'fieldset_galleries_list',
                'help' => '#!/galleries_menu'
            ),
            array(
                'name' => __be( 'Galleries main page' ),
                'desc' => __be( 'Select page that is your Galleries list page. It will make working some features. If you setup Galleries list as your front page then you don\'t have to set any page here.' ),
                'id' => 'cpt_gallery_page',
                'default' => 0,
                'type' => 'wp_dropdown_pages',
            ),
            array(
                'name' => __be( 'Galleries list page title' ),
                'desc' => '',
                'id' => 'galleries_list_title',
                'default' => __fe('Galleries'),
                'type' => 'input',
            ),
            array(
                'name' => __be( 'Items displayed Full width' ),
                'desc' => __be( 'Part of layout with galleries items will be displayed on full width. In other case items will be displayed in standard layout "box".' ),
                'id' => 'full_width',
                'default' => 'off',
                'options' => array(
                    'on' => __be( 'Enable' ),
                    'off' => __be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Where to show category filter' ),
                'desc' => __be( 'If <strong>Above</strong> is selected, place it will be displayed depends on <strong>Galleries appearance variant</strong>' ),
                'id' => 'filter_place',
                'default' => 'title',
                'options' => array(
                    'title'             => __be( 'Title bar' ),
//                    'sidebar'           => __be( 'Sidebar' ),
                    'above'             => __be( 'Above' ),
                    'off'               => __be( "Don't show filter" ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Filter by default is' ),
                'desc' => '',
                'id' => 'filter_open',
                'default' => 'on',
                'options' => array(
                    'on' => __be( 'Open' ),
                    'off' => __be( 'Closed' ),
                ),
                'type' => 'radio',
            ),

            array(
                'name' => __be( 'Galleries appearance variant:' ),
                'desc' => __be( 'It affects look of works list page.' ),
                'id' => 'galleries_variant',
                'default' => 'variant_image',
                'options' => array(
                    'variant_1'             => __be( 'Classic 1: Big-sized images' ),
                    'variant_2'             => __be( 'Classic 2: Medium-sized images' ),
                    'variant_3'             => __be( 'Classic 3: Small-sized images' ),
                    'variant_image'         => __be( 'Image with "on hover title"' ),
                ),
                'type' => 'select',
                'switch' => true,
            ),
            array(
                'name' => 'variant_image',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Show galleries titles' ),
                'desc' => '',
                'id' => 'show_titles',
                'default' => 'on',
                'type' => 'radio',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
            ),
            array(
                'name' => __be( 'Hover Effect' ),
                'desc' => '',
                'id' => 'gl_hover_type',
                'default' => 'cover-loop',
                'type' => 'radio',
                'options' => array(
                    'cover-loop' => __be( 'Cover' ),/* cause of CSS class collision */
                    'uncover' => __be( 'Uncover' )
                ),
            ),
            array(
                'name' => __be( 'Zoom on hover' ),
                'desc' => '',
                'id' => 'gl_hover_zoom',
                'default' => 'on',
                'type' => 'radio',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
            ),
            array(
                'name' => __be( 'Cover width' ),
                'desc' =>__be( 'Use slider to set proper size.' ),
                'id' => 'gl_brick_width',
                'default' => '420px',
                'unit' => 'px',
                'min' => 50,
                'max' => 600,
                'type' => 'slider'
            ),
            array(
                'name' => __be( 'Cover height' ),
                'desc' =>__be( 'Use slider to set proper size. If you want photo to not be cropped, set to 0.' ),
                'id' => 'gl_brick_height',
                'default' => '320px',
                'unit' => 'px',
                'min' => 0,
                'max' => 600,
                'type' => 'slider'
            ),
            array(
                'name' => __be( 'Cover margin' ),
                'desc' =>__be( 'Use slider to set proper size.' ),
                'id' => 'gl_brick_margin',
                'default' => '0',
                'unit' => 'px',
                'min' => 0,
                'max' => 100,
                'type' => 'slider'
            ),
            array(
                /*'name' => 'variant_image',*/
                'type' => 'switch-group-end'
            ),
            array(
                /*'id' => 'works_variant',  just for readability */
                'type' => 'end-switch',
            ),



            array(
                'name' => __be( 'Gallery appearance(Bricks theme)' ),
                'type' => 'fieldset',
                'default' => 1,
                'id' => 'fieldset_gallery_bricks_app',
                'help' => '#!/galleries_menu'
            ),
            array(
                'name' => __be( 'Zoom on hover' ),
                'desc' => '',
                'id' => 'hover_zoom',
                'default' => 'off',
                'type' => 'radio',
                'options' => array(
                    'on'    => __be( 'On' ),
                    'off'   => __be( 'Off' ),
                ),
            ),
            array(
                'name' => __be( 'Brick width' ),
                'desc' =>__be( 'Use slider to set proper size.' ) . ' ' . __be( 'Global for all galleries.' ),
                'id' => 'brick_width',
                'default' => '340px',
                'unit' => 'px',
                'min' => 50,
                'max' => 600,
                'type' => 'slider'
            ),
            array(
                'name' => __be( 'Brick height' ),
                'desc' =>__be( 'Use slider to set proper size. If you want photo to not be cropped, set to 0.' ) . ' ' . __be( 'Global for all galleries.' ),
                'id' => 'brick_height',
                'default' => '260px',
                'unit' => 'px',
                'min' => 0,
                'max' => 600,
                'type' => 'slider'
            ),
            array(
                'name' => __be( 'Brick margin' ),
                'desc' =>__be( 'Use slider to set proper size.' ) . ' ' . __be( 'Global for all galleries.' ),
                'id' => 'brick_margin',
                'default' => '0px',
                'unit' => 'px',
                'min' => 0,
                'max' => 100,
                'type' => 'slider'
            ),


            array(
                'name' => __be( 'Gallery appearance(Slider theme)' ),
                'type' => 'fieldset',
                'default' => 0,
                'id' => 'fieldset_gallery_slider'
            ),
            array(
                'name' => __be( 'Slider height' ),
                'desc' =>__be( 'Use slider to set proper size.' ),
                'id' => 'slider_height',
                'default' => '640px',
                'unit' => 'px',
                'min' => 100,
                'max' => 700,
                'type' => 'slider'
            ),
            array(
                'name' => __be( 'Images size' ),
                'desc' => __be( 'You can use images that you have uploaded, or resized versions.' ),
                'id' => 'slider_images',
                'default' => 'full',
                'options' => array(
                    'full' => __be( 'Original' ),
                    'resized' => __be( 'Resized' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Autoplay' ),
                'desc' => __be( 'If autoplay is on, slider will run on page load. Global setting, but you can change this in each gallery.' ),
                'id' => 'autoplay',
                'default' => '1',
                'options' => array(
                    '1' => __be( 'Enable' ),
                    '0' => __be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'Slide interval(ms)' ),
                'desc' =>__be( 'Time between slide transitions.' ) . ' ' . __be( 'Global for all galleries.' ),
                'id' => 'slide_interval',
                'default' => 7000,
                'unit' => '',
                'min' => 0,
                'max' => 15000,
                'type' => 'slider'
            ),
            array(
                'name' => __be( 'Transition type' ),
                'desc' =>__be( 'Animation between slides.' ),
                'id' => 'transition_type',
                'default' => '2',
                'options' => array(
                    '0' => __be( 'None' ),
                    '1' => __be( 'Fade' ),
                    '2' => __be( 'Carousel' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Transition speed(ms)' ),
                'desc' =>__be( 'Speed of transition.' ) . ' ' . __be( 'Global for all galleries.' ),
                'id' => 'transition_time',
                'default' => 1000,
                'unit' => '',
                'min' => 0,
                'max' => 10000,
                'type' => 'slider'
            ),
            array(
                'name' => __be( 'Titles and descriptions' ),
                'desc' =>__be( 'Show image/video titles and descriptions.' ) . ' ' . __be( 'Global for all galleries.' ),
                'id' => 'titles',
                'default' => 'on',
                'options' => array(
                    'on' => __be( 'Enable' ),
                    'off' => __be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                'name' => __be( 'List of slides(buttons).' ),
                'desc' => __be( 'Global for all galleries.' ),
                'id' => 'list',
                'default' => 'on',
                'options' => array(
                    'on' => __be( 'Enable' ),
                    'off' => __be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
		);

		return $opt;
	}

    function apollo13_sidebars_options(){

        $opt = array(
            array(
                'name' => __be( 'Add custom sidebars' ),
                'type' => 'fieldset',
                'default' => 1,
                'id' => 'fieldset_sidebars',
//                'help' => '#!/advanced_menu'
            ),
            array(
                'name' => __be( 'New sidebar name' ),
                'desc' => __be( 'Choose name for new sidebar and click <b>Save Changes</b> to add it.' ),
                'id' => 'custom_sidebars',
                'default' => '',
                'placeholder' => 'New sidebar name',
                'type' => 'sidebars',
            ),
        );

        return $opt;
    }

	function apollo13_socials_options(){
		$socials = array(
			'500px' => array(
                'name' => '500px',
                'value' => '',
				'pos'  => 0
            ),
			'aim' => array(
                'name' => 'Aim',
                'value' => '',
				'pos'  => 0
            ),
			'behance' => array(
                'name' => 'Behance',
                'value' => '',
				'pos'  => 0
            ),
			'blogger' => array(
                'name' => 'Blogger',
                'value' => '#',
				'pos'  => 0
            ),
			'delicious' => array(
                'name' => 'Delicious',
                'value' => '',
				'pos'  => 0
            ),
			'deviantart' => array(
                'name' => 'Deviantart',
                'value' => '',
				'pos'  => 0
            ),
			'digg' => array(
                'name' => 'Digg',
                'value' => '',
				'pos'  => 0
            ),
			'dribbble' => array(
                'name' => 'Dribbble',
                'value' => '',
				'pos'  => 0
            ),
			'evernote' => array(
                'name' => 'Evernote',
                'value' => '',
				'pos'  => 0
            ),
			'facebook' => array(
                'name' => 'Facebook',
                'value' => '#',
				'pos'  => 1
            ),
			'flickr' => array(
                'name' => 'Flickr',
                'value' => '',
				'pos'  => 0
            ),
			'forrst' => array(
                'name' => 'Forrst',
                'value' => '',
				'pos'  => 0
            ),
			'foursquare' => array(
                'name' => 'Foursquare',
                'value' => '',
				'pos'  => 0
            ),
			'github' => array(
                'name' => 'Github',
                'value' => '',
				'pos'  => 0
            ),
			'googleplus' => array(
                'name' => 'Google Plus',
                'value' => '',
				'pos'  => 0
            ),
			'instagram' => array(
                'name' => 'Instagram',
                'value' => '',
				'pos'  => 0
            ),
			'lastfm' => array(
                'name' => 'Lastfm',
                'value' => '',
				'pos'  => 0
            ),
			'linkedin' => array(
                'name' => 'Linkedin',
                'value' => '',
				'pos'  => 0
            ),
			'paypal' => array(
                'name' => 'Paypal',
                'value' => '',
				'pos'  => 0
            ),
			'pinterest' => array(
                'name' => 'Pinterest',
                'value' => '',
				'pos'  => 0
            ),
			'quora' => array(
                'name' => 'Quora',
                'value' => '',
				'pos'  => 0
            ),
			'rss' => array(
                'name' => 'RSS',
                'value' => '',
				'pos'  => 0
            ),
			'sharethis' => array(
                'name' => 'Sharethis',
                'value' => '',
				'pos'  => 0
            ),
			'skype' => array(
                'name' => 'Skype',
                'value' => '',
				'pos'  => 0
            ),
			'stumbleupon' => array(
                'name' => 'Stumbleupon',
                'value' => '#',
				'pos'  => 0
            ),
			'tumblr' => array(
                'name' => 'Tumblr',
                'value' => '',
				'pos'  => 0
            ),
			'twitter' => array(
                'name' => 'Twitter',
                'value' => '',
                'pos'  => 0
            ),
			'vimeo' => array(
                'name' => 'Vimeo',
                'value' => '',
                'pos'  => 0
            ),
			'wordpress' => array(
                'name' => 'Wordpress',
                'value' => '',
                'pos'  => 0
            ),
			'yahoo' => array(
                'name' => 'Yahoo',
                'value' => '',
                'pos'  => 0
            ),
			'youtube' => array(
                'name' => 'Youtube',
                'value' => '',
                'pos'  => 0
            ),
		);
	
		$opt = array(
//			array(
//				'name' => __be( 'Social settings' ),
//				'type' => 'fieldset',
//                'default' => 1,
//                'id' => 'fieldset_social',
//                'help' => '#!/social_menu'
//			),
//			array(
//				'name' => __be( 'Number of visible icons:' ),
//				'desc' => __be( 'Set to 0 to disable social icons in header. Setting to "4" is recommended' ),
//				'id' => 'social_number_of_visible',
//				'default' => '3',
//				'type' => 'input',
//			),


			array(
				'name' => __be( 'Social services' ),
				'type' => 'fieldset',
                'default' => 1,
                'id'   => 'sortable-socials'
			),
			array(
				'name' => __be( 'Social services' ),
				'desc' => __be( 'If you face problems with saving this options, then please remove <code>http://</code> from your social links.<br />It will be converted to proper links on front-end, so don\'t worry;-)' ),
				'id' => 'social_services',
				'default' => '',
				'type' => 'social',
				'options' => $socials
			),
		);
		
		return $opt;
	}

    function apollo13_contact_options(){
        $opt = array(
            array(
                'name' => __be( 'Map settings' ),
                'type' => 'fieldset',
                'default' => 1,
                'id' => 'fieldset_contact',
                'help' => '#!/contact_page_menu'
            ),
            array(
                'name' => __be( 'Contact page map' ),
                'desc' => '',
                'id' => 'contact_map',
                'default' => 'on',
                'options' => array(
                    'on' => __be( 'On' ),
                    'off' => __be( 'Off' ),
                ),
                'type' => 'radio',
                'switch' => true,
            ),
            array(
                'name' => 'on',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Google map drop area' ),
                'desc' => __be( 'Paste here your google map link(see documentation for more info), and everything will be filled automatically.' ),
                'id' => 'contact_drop_area',
                'default' => '',
                'type' => 'textarea',
            ),
            array(
                'name' => __be( 'Latitude, Longitude' ),
                'desc' => __be( 'Use format Latitude, Longitude (ex. 50.854817, 20.644566)' ),
                'id' => 'contact_ll',
                'default' => '',
                'placeholder' => '40.715241,-74.003026',
                'type' => 'input',
            ),
            array(
                'name' => __be( 'Map type' ),
                'desc' => '',
                'id' => 'contact_map_type',
                'default' => 'ROADMAP',
                'options' => array(
                    'ROADMAP' =>    __be( 'Road map' ),
                    'SATELLITE' =>  __be( 'Satellite' ),
                    'HYBRID' =>     __be( 'Hybrid' ),
                    'TERRAIN' =>    __be( 'Terrain' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Zoom level' ),
                'desc' =>__be( 'Use slider to set proper zoom level.' ),
                'id' => 'contact_zoom',
                'default' => '15',
                'unit' => '',
                'min' => 1,
                'max' => 19,
                'type' => 'slider'
            ),
            array(
                'name' => __be( 'Marker title' ),
                'desc' => __be( 'Will show while hovering mouse cursor over marker' ),
                'id' => 'contact_title',
                'default' => 'Superior Inc.',
                'type' => 'input',
            ),
            array(
                'name' => __be( 'Info window content' ),
                'desc' => __be( 'Will show up after clicking in marker' ),
                'id' => 'contact_content',
                'default' => '<strong>Superior Inc.</strong><br />
1299 N Tamiami Trl <br />
Columbus, OH 43232-4831',
                'type' => 'textarea',
            ),
            array(
                /*'name' => 'on',*/
                'type' => 'switch-group-end'
            ),
            array(
                /*'id' => 'contact_map',  just for readability */
                'type' => 'end-switch',
            ),
        );

        return $opt;
    }

	function apollo13_advanced_options(){

		$opt = array(
			array(
				'name' => __be( 'Miscellaneous settings' ),
				'type' => 'fieldset',
                'default' => 1,
                'id' => 'fieldset_misc',
                'help' => '#!/advanced_menu'
			),
			array(
				'name' => __be( 'Comments validation' ),
				'desc' => __be( 'If you wish to use some plugin for validation in <strong>comments form</strong> then you should turn off build in theme validation' ),
				'id' => 'apollo_validation',
				'default' => 'on',
				'options' => array(
                    'on' => __be( 'Enable' ),
                    'off' => __be( 'Disable' ),
				),
				'type' => 'radio',
			),
			array(
				'name' => __be( 'Theme lightbox' ),
				'desc' => __be( 'If you wish to use some other plugin/script for images and items switch it off.' ),
				'id' => 'apollo_lightbox',
				'default' => 'on',
				'options' => array(
                    'on' => __be( 'Enable' ),
                    'off' => __be( 'Disable' ),
				),
				'type' => 'radio',
			),
		);

		return $opt;
	}

    function apollo13_customize_options(){

		$opt = array(
            array(
                'name' => __be( 'Custom CSS' ),
                'type' => 'fieldset',
                'default' => 1,
                'id' => 'fieldset_custom_css',
                'help' => '#!/modification_of_theme_custom_css'
            ),
            array(
                'name' => __be( 'Custom CSS' ),
                'desc' => '',
                'id' => 'custom_css',
                'default' => '',
                'type' => 'textarea',
            ),
		);

		return $opt;
	}
