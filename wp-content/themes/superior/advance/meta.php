<?php
/*
 * id - must be
 *
 * TYPES:
 * +fieldset
 * radio
 * +wp_dropdown_albums
 * +input
 * +textarea
 * +color
 * +upload
 * +select
 * slider
 * social
 * +switch
 * !multi-upload
 * !hidden
 * !mover
 * !adder
 * +end-switch
 *
 * */

	function apollo13_metaboxes_post(){
		$meta = array(
			array(
				'name' => '',
				'type' => 'fieldset',
			),
            array(
                'name' => __be( 'Alternative Link' ),
                'desc' => __be('If you fill this then when selecting post from Blog(post list), it will lead to this link instead of opening post.'),
                'id' => 'alt_link',
                'default' => '',
                'type' => 'input',
            ),
            array(
                'name' => __be( 'Post media' ),
                'desc' =>__be( 'Choose between Image and Video/Audio. For image use Featured Image Option' ),
                'id' => 'image_or_video',
                'default' => 'post_image',
                'options' => array(
                    'post_image' => __be( 'Image' ),
                    'post_video' => __be( 'Video/Audio' ),
//                    'post_slides' => __be( 'Album slider' )
                ),
                'switch' => true,
                'type' => 'select',
            ),
            array(
                'name' => 'post_image',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Image behavior' ),
                'desc' => '',
                'id' => 'image_stretch',
                'default' => 'align-center',
                'options' => array(
                    'align-left'   => __be( 'Align left' ),
                    'align-right'  => __be( 'Align right' ),
                    'align-center' => __be( 'Center' ),
                    'stretch-full' => __be( 'Take full space' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Image size' ),
                'desc' => '',
                'id' => 'image_size',
                'default' => 'auto',
                'options' => array(
                    'auto'      => __be( 'Automatic' ),
                    'medium'    => __be( 'Medium' ),
                    'big'       => __be( 'Big' ),
                    'original'  => __be( 'Original' ),
                ),
                'type' => 'select',
            ),
            array(
                /*'name' => 'post_image',*/
                'type' => 'switch-group-end'
            ),
            array(
                'name' => 'post_video',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Link to video/audio' ),
                'desc' => __be('Insert here link to your audio/video file or upload it. You can also add video from youtube or viemo by pasting here link to movie.'),
                'id' => 'post_video',
                'default' => '',
                'type' => 'upload',
                'button_text' => __be('Upload media file'),
                'media_button_text' => __be('Insert media file'),
                'media_type' => 'audio,video',
            ),
            array(
                'name' => __be( 'Video behavior' ),
                'desc' => '',
                'id' => 'video_align',
                'default' => 'stretch-full',
                'options' => array(
                    'align-left'   => __be( 'Align left' ),
//                    'align-right'  => __be( 'Align right' ),
                    'align-center' => __be( 'Center' ),
                    'stretch-full' => __be( 'Take full space' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Video size' ),
                'desc' => '',
                'id' => 'video_size',
                'default' => 'medium',
                'options' => array(
                    'medium'    => __be( 'Medium' ),
                    'big'       => __be( 'Big' ),
                ),
                'type' => 'select',
            ),
            array(
                /*'name' => 'post_video',*/
                'type' => 'switch-group-end'
            ),
            /*array(
                'name' => 'post_slides',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Select gallery or work items set' ),
                'desc' => '',
                'id' => 'post_slides',
                'default' => '0',
                'pre_options' => array(
                    '0' => __be( 'None' )
                ),
                'type' => 'wp_dropdown_albums',
            ),
            array(*/
                /*'name' => 'post_slides',*/
                /*'type' => 'switch-group-end'
            ),*/
            array(
                /*'id' => 'image_or_video',  just for readability */
                'type' => 'end-switch',
            ),
            array(
                'name' => __be( 'Sidebar' ),
                'desc' => __be( 'If turned off, content will take full width.' ),
                'id' => 'widget_area',
                'global_value' => 'G',
                'default' => 'G',
                'parent_option' => array('blog', 'post_sidebar'),
                'options' => array(
                    'G'             => __be( 'Global settings' ),
                    'left-sidebar'  => __be( 'Left' ),
                    'right-sidebar' => __be( 'Right' ),
                    'off'           => __be( 'Off' ),
                ),
                'type' => 'select',
            ),
		);
		
		return $meta;
	}
	
	function apollo13_metaboxes_page(){
        global $apollo13;
        $sidebars = array(
            'default' => __be( 'Default for pages' ),
        );
        $custom_sidebars = unserialize($apollo13->get_option( 'sidebars', 'custom_sidebars' ));
        $sidebars_count = count($custom_sidebars);
        if(is_array($custom_sidebars) && $sidebars_count > 0){
            foreach($custom_sidebars as $sidebar){
                $sidebars[$sidebar['id']] = $sidebar['name'];
            }
        }

		$meta = array(
			array(
				'name' => '',
				'type' => 'fieldset'
			),
            array(
                'name' => __be( 'Full width elements' ),
                'desc' =>__be( 'If you will use builder and want to use full width elements, then it is recommended that you turn on this option. It will deactivate sidebars for this page.' ),
                'id' => 'full_width_elements',
                'default' => 'off',
                'type' => 'radio',
                'options' => array(
                    'on' => __be( 'Turn it on' ),
                    'off'    => __be( 'Off' ),
                ),
            ),
            array(
                'name' => __be( 'Content padding' ),
                'desc' =>__be( 'Enable or disable top and bottom padding of content. It is helpful in achieving some neat layout effects:-).' ),
                'id' => 'content_padding',
                'default' => 'both',
                'type' => 'select',
                'options' => array(
                    'both' => __be( 'Both on' ),
                    'top' => __be( 'Only top' ),
                    'bottom' => __be( 'Only bottom' ),
                    'off'    => __be( 'Both off' ),
                ),
            ),
            array(
                'name' => __be( 'Title bar look' ),
                'desc' =>__be( 'You can use global settings or overwrite them here' ),
                'id' => 'title_bar_settings',
                'default' => 'global',
                'type' => 'radio',
                'options' => array(
                    'global' => __be( 'Global settings' ),
                    'custom' => __be( 'Use custom settings' ),
                    'off'    => __be( 'Turn it off' ),
                ),
                'switch' => true,
            ),
            array(
                'name' => 'custom',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Background image file' ),
                'desc' => '',
                'id' => 'title_bar_image',
                'default' => '',
                'button_text' => __be('Upload Image'),
                'type' => 'upload'
            ),
            array(
                'name' => __be( 'How to fit background image' ),
                'desc' => __be( 'In Internet Explorer 8 and lower whatever you will choose(except <em>repeat</em>) it will look like "Just center"' ),
                'id' => 'title_bar_image_fit',
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
                'id' => 'title_bar_bg_color',
                'default' => '',
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
                'default' => '20px',
                'unit' => 'px',
                'min' => 0,
                'max' => 200,
                'type' => 'slider'
            ),
            array(
                /*'name' => 'custom',*/
                'type' => 'switch-group-end'
            ),
            array(
                /*'id' => 'title_bar_settings',  just for readability */
                'type' => 'end-switch',
            ),
            array(
                'name' => __be( 'Subtitle or button link' ),
                'desc' => '',
                'id' => 'subtitle_or_button',
                'default' => 'off',
                'type' => 'radio',
                'options' => array(
                    'subtitle'  => __be( 'Subtitle' ),
                    'button'     => __be( 'Button' ),
                    'off'       => __be( 'Nothing' )
                ),
                'switch' => true,
            ),
            array(
                'name' => 'subtitle',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Subtitle' ),
                'desc' => __be( 'You can use HTML here.' ),
                'id' => 'subtitle',
                'default' => '',
                'type' => 'input'
            ),
            array(
                /*'name' => 'subtitle',*/
                'type' => 'switch-group-end'
            ),
            array(
                'name' => 'button',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'URL' ),
                'desc' => '',
                'id' => 'button_url',
                'default' => '',
                'placeholder' => 'http://xxx.org',
                'type' => 'input'
            ),
            array(
                'name' => __be( 'Text on button' ),
                'desc' => '',
                'id' => 'button_text',
                'default' => '',
                'type' => 'input'
            ),
            array(
                'name' => __be( 'Open in new tab' ),
                'desc' => '',
                'id' => 'new_tab',
                'default' => '1',
                'type' => 'radio',
                'options' => array(
                    '1' => __be( 'Yes' ),
                    '0' => __be( 'No' ),
                ),
            ),
            array(
                /*'name' => 'button',*/
                'type' => 'switch-group-end'
            ),
            array(
                /*'id' => 'subtitle_or_button',  just for readability */
                'type' => 'end-switch',
            ),

            array(
                'name' => __be( 'Header tools' ),
                'desc' =>__be( 'Header tools are: breadcrumbs and search form.' ),
                'id' => 'header_tools',
                'default' => 'on',
                'type' => 'radio',
                'options' => array(
                    'on' => __be( 'On' ),
                    'off'    => __be( 'Turn it off' ),
                ),
            ),
            array(
                'name' => __be( 'Menu icon' ),
                'desc' => __be( 'It works only for first level menu items.' ) .' '. __be( ' You can see available icons at <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a>. You have to use code of icon that looks like <code>fa-coffee</code>. To add more icons separate them with white space.' ),
                'id' => 'menu_icon',
                'default' => '',
                'placeholder' => 'fa-phone',
                'type' => 'input'
            ),
            array(
                'name' => __be( 'Post media' ),
                'desc' =>__be( 'Choose between Image and Video/Audio. For image use Featured Image Option' ),
                'id' => 'image_or_video',
                'default' => 'post_image',
                'options' => array(
                    'post_image' => __be( 'Image' ),
                    'post_video' => __be( 'Video/Audio' ),
//                    'post_slides' => __be( 'Album slider' )
                ),
                'switch' => true,
                'type' => 'select',
            ),
            array(
                'name' => 'post_image',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Image behavior' ),
                'desc' => '',
                'id' => 'image_stretch',
                'default' => 'align-center',
                'options' => array(
                    'align-left'   => __be( 'Align left' ),
                    'align-right'  => __be( 'Align right' ),
                    'align-center' => __be( 'Center' ),
                    'stretch-full' => __be( 'Take full space' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Image size' ),
                'desc' => '',
                'id' => 'image_size',
                'default' => 'auto',
                'options' => array(
                    'auto'      => __be( 'Automatic' ),
                    'medium'    => __be( 'Medium' ),
                    'big'       => __be( 'Big' ),
                    'original'  => __be( 'Original' ),
                ),
                'type' => 'select',
            ),
            array(
                /*'name' => 'post_image',*/
                'type' => 'switch-group-end'
            ),
            array(
                'name' => 'post_video',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Link to video/audio' ),
                'desc' => __be('Insert here link to your audio/video file or upload it. You can also add video from youtube or viemo by pasting here link to movie.'),
                'id' => 'post_video',
                'default' => '',
                'type' => 'upload',
                'button_text' => __be('Upload media file'),
                'media_button_text' => __be('Insert media file'),
                'media_type' => 'audio,video',
            ),
            array(
                'name' => __be( 'Video behavior' ),
                'desc' => '',
                'id' => 'video_align',
                'default' => 'stretch-full',
                'options' => array(
                    'align-left'   => __be( 'Align left' ),
//                    'align-right'  => __be( 'Align right' ),
                    'align-center' => __be( 'Center' ),
                    'stretch-full' => __be( 'Take full space' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Video size' ),
                'desc' => '',
                'id' => 'video_size',
                'default' => 'medium',
                'options' => array(
                    'medium'    => __be( 'Medium' ),
                    'big'       => __be( 'Big' ),
                ),
                'type' => 'select',
            ),
            array(
                /*'name' => 'post_video',*/
                'type' => 'switch-group-end'
            ),
            array(
                /*'id' => 'image_or_video',  just for readability */
                'type' => 'end-switch',
            ),
            array(
                'name' => __be( 'Sidebar' ),
                'desc' => __be( 'If turned off, content will take full width.' ),
                'id' => 'widget_area',
                'global_value' => 'G',
                'default' => 'G',
                'parent_option' => array('appearance', 'page_sidebar'),
                'options' => array(
                    'G'                         => __be( 'Global settings' ),
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
                'name' => __be( 'Sidebar to show' ),
                'desc' => '',
                'id' => 'sidebar_to_show',
                'default' => 'default',
                'parent_option' => array('appearance', 'page_sidebar'),
                'options' => $sidebars,
                'type' => 'select',
            ),
		);
		
		return $meta;
	}
	
	function apollo13_metaboxes_cpt_work(){
		$meta = array(
			array(
				'name' => '',
				'type' => 'fieldset'
			),
            array(
                'name' => __be( 'Title bar look' ),
                'desc' =>__be( 'You can use global settings or overwrite them here' ),
                'id' => 'title_bar_settings',
                'default' => 'global',
                'type' => 'radio',
                'options' => array(
                    'global' => __be( 'Global settings' ),
                    'custom' => __be( 'Use custom settings' ),
                    'off'    => __be( 'Turn it off' ),
                ),
                'switch' => true,
            ),
            array(
                'name' => 'custom',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Background image file' ),
                'desc' => '',
                'id' => 'title_bar_image',
                'default' => '',
                'button_text' => __be('Upload Image'),
                'type' => 'upload'
            ),
            array(
                'name' => __be( 'How to fit background image' ),
                'desc' => __be( 'In Internet Explorer 8 and lower whatever you will choose(except <em>repeat</em>) it will look like "Just center"' ),
                'id' => 'title_bar_image_fit',
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
                'id' => 'title_bar_bg_color',
                'default' => '',
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
                'default' => '20px',
                'unit' => 'px',
                'min' => 0,
                'max' => 200,
                'type' => 'slider'
            ),
            array(
                /*'name' => 'custom',*/
                'type' => 'switch-group-end'
            ),
            array(
                /*'id' => 'title_bar_settings',  just for readability */
                'type' => 'end-switch',
            ),
            array(
                'name' => __be( 'Subtitle' ),
                'desc' => __be( 'You can use HTML here.' ),
                'id' => 'subtitle',
                'default' => '',
                'type' => 'input'
            ),

            array(
                'name' => __be( 'Header tools' ),
                'desc' =>__be( 'Header tools are: breadcrumbs and search form.' ),
                'id' => 'header_tools',
                'default' => 'on',
                'type' => 'radio',
                'options' => array(
                    'on' => __be( 'On' ),
                    'off'    => __be( 'Turn it off' ),
                ),
            ),
            array(
                'name' => __be( 'Menu icon' ),
                'desc' => __be( 'It works only for first level menu items.' ) .' '. __be( ' You can see available icons at <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a>. You have to use code of icon that looks like <code>fa-coffee</code>. To add more icons separate them with white space.' ),
                'id' => 'menu_icon',
                'default' => '',
                'placeholder' => 'fa-phone',
                'type' => 'input'
            ),
            array(
                'name' => __be( 'Alternative Link' ),
                'desc' => __be('If you fill this then clicking in your work on works list will not lead to single work page but to link from this field.'),
                'id' => 'alt_link',
                'default' => '',
                'type' => 'input',
            ),
            array(
                'name' => __be( 'Items order' ),
                'desc' => __be( 'It will display your images/videos from first to last, or another way.' ),
                'id' => 'order',
                'default' => 'ASC',
                'options' => array(
                    'ASC' => __be( 'First on list, first displayed' ),
                    'DESC' => __be( 'First on list, last displayed' ),
                    'random' => __be( 'Random' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Internet address' ),
                'desc' => '',
                'id' => 'www',
                'default' => '',
                'placeholder' => 'http://link-to-somewhere.com',
                'type' => 'input'
            ),
            array(
                'name' => __be( 'Custom info 1' ),
                'desc' => __be('If empty it won\'t be displayed. Use pattern <b>Field name: Field value</b>. Colon(:) is most important to get full result.'),
                'id' => 'custom_1',
                'default' => '',
                'placeholder' => 'Label: value',
                'type' => 'input'
            ),
            array(
                'name' => __be( 'Custom info 2' ),
                'desc' => __be('If empty it won\'t be displayed. Use pattern <b>Field name: Field value</b>. Colon(:) is most important to get full result.'),
                'id' => 'custom_2',
                'default' => '',
                'placeholder' => 'Label: value',
                'type' => 'input'
            ),
            array(
                'name' => __be( 'Custom info 3' ),
                'desc' => __be('If empty it won\'t be displayed. Use pattern <b>Field name: Field value</b>. Colon(:) is most important to get full result.'),
                'id' => 'custom_3',
                'default' => '',
                'placeholder' => 'Label: value',
                'type' => 'input'
            ),
            array(
                'name' => __be( 'Custom info 4' ),
                'desc' => __be('If empty it won\'t be displayed. Use pattern <b>Field name: Field value</b>. Colon(:) is most important to get full result.'),
                'id' => 'custom_4',
                'default' => '',
                'placeholder' => 'Label: value',
                'type' => 'input'
            ),
            array(
                'name' => __be( 'Custom info 5' ),
                'desc' => __be('If empty it won\'t be displayed. Use pattern <b>Field name: Field value</b>. Colon(:) is most important to get full result.'),
                'id' => 'custom_5',
                'default' => '',
                'placeholder' => 'Label: value',
                'type' => 'input'
            ),
            array(
                'name' => __be( 'Description content position' ),
                'desc' => __be( 'Where will text appear relative to images and videos.' ),
                'id' => 'content_position',
                'default' => 'under',
                'options' => array(
                    'left'  => __be( 'Left' ),
                    'right' => __be( 'Right' ),
                    'under' => __be( 'Under images/videos' )
                ),
                'switch' => true,
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Present media in:' ),
                'desc' => '',
                'id' => 'theme',
                'default' => 'scroller',
                'options' => array(
                    'scroller'      => __be( 'Scroller' ),
                    'slider'        => __be( 'Slider' ),
                    'full_photos'   => __be('Full width photos')
                ),
                'switch' => true,
                'type' => 'select',
            ),
            array(
                'name' => 'slider',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Fit images' ),
                'desc' => __be( 'How will images fit area. <strong>Fit when needed</strong> is best for small images, that shouldn\'t be stretched to bigger sizes, only to smaller(to keep them visible).' ),
                'id' => 'fit_variant',
                'default' => '0',
                'options' => array(
                    '0' => __be( 'Fit always' ),
                    '1' => __be( 'Fit landscape' ),
                    '2' => __be( 'Fit portrait' ),
                    '3' => __be( 'Fit when needed' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Autoplay' ),
                'desc' => __be( 'If autoplay is on, slider items will start sliding on page load' ),
                'id' => 'autoplay',
                'default' => 'G',
                'global_value' => 'G',
                'parent_option' => array('cpt_work', 'autoplay'),
                'options' => array(
                    'G' => __be( 'Global settings' ),
                    '1' => __be( 'Enable' ),
                    '0' => __be( 'Disable' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Transition type' ),
                'desc' => __be( 'Animation between slides.' ),
                'id' => 'transition',
                'default' => '-1',
                'global_value' => '-1',
                'parent_option' => array('cpt_work', 'transition_type'),
                'options' => array(
                    '-1' => __be( 'Global settings' ),
                    '0' => __be( 'None' ),
                    '1' => __be( 'Fade' ),
                    '2' => __be( 'Carousel' ),
                ),
                'type' => 'select',
            ),
            array(
                /*'name' => 'slider',*/
                'type' => 'switch-group-end'
            ),
            array(
                /*'id' => 'theme',  just for readability */
                'type' => 'end-switch',
            ),
		);
		
		return $meta;
	}

	function apollo13_metaboxes_cpt_gallery(){
		$meta = array(
			array(
				'name' => '',
				'type' => 'fieldset'
			),array(
                'name' => __be( 'Title bar look' ),
                'desc' =>__be( 'You can use global settings or overwrite them here' ),
                'id' => 'title_bar_settings',
                'default' => 'global',
                'type' => 'radio',
                'options' => array(
                    'global' => __be( 'Global settings' ),
                    'custom' => __be( 'Use custom settings' ),
                    'off'    => __be( 'Turn it off' ),
                ),
                'switch' => true,
            ),
            array(
                'name' => 'custom',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Background image file' ),
                'desc' => '',
                'id' => 'title_bar_image',
                'default' => '',
                'button_text' => __be('Upload Image'),
                'type' => 'upload'
            ),
            array(
                'name' => __be( 'How to fit background image' ),
                'desc' => __be( 'In Internet Explorer 8 and lower whatever you will choose(except <em>repeat</em>) it will look like "Just center"' ),
                'id' => 'title_bar_image_fit',
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
                'id' => 'title_bar_bg_color',
                'default' => '',
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
                'default' => '20px',
                'unit' => 'px',
                'min' => 0,
                'max' => 200,
                'type' => 'slider'
            ),
            array(
                /*'name' => 'custom',*/
                'type' => 'switch-group-end'
            ),
            array(
                /*'id' => 'title_bar_settings',  just for readability */
                'type' => 'end-switch',
            ),
            array(
                'name' => __be( 'Subtitle' ),
                'desc' => __be( 'You can use HTML here.' ),
                'id' => 'subtitle',
                'default' => '',
                'type' => 'input'
            ),

            array(
                'name' => __be( 'Header tools' ),
                'desc' =>__be( 'Header tools are: breadcrumbs and search form.' ),
                'id' => 'header_tools',
                'default' => 'on',
                'type' => 'radio',
                'options' => array(
                    'on' => __be( 'On' ),
                    'off'    => __be( 'Turn it off' ),
                ),
            ),
            array(
                'name' => __be( 'Menu icon' ),
                'desc' => __be( 'It works only for first level menu items.' ) .' '. __be( ' You can see available icons at <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a>. You have to use code of icon that looks like <code>fa-coffee</code>. To add more icons separate them with white space.' ),
                'id' => 'menu_icon',
                'default' => '',
                'placeholder' => 'fa-phone',
                'type' => 'input'
            ),
            array(
                'name' => __be( 'Alternative Link' ),
                'desc' => __be('If you fill this then clicking in your gallery on galleries list will not lead to single gallery page but to link from this field.'),
                'id' => 'alt_link',
                'default' => '',
                'type' => 'input',
            ),
            array(
                'name' => __be( 'Items order' ),
                'desc' => __be( 'It will display your images/videos from first to last, or another way.' ),
                'id' => 'order',
                'default' => 'ASC',
                'options' => array(
                    'ASC' => __be( 'First on list, first displayed' ),
                    'DESC' => __be( 'First on list, last displayed' ),
                    'random' => __be( 'Random' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Theme' ),
                'desc' => '',
                'id' => 'theme',
                'default' => 'bricks',
                'options' => array(
                    'slider' => __be( 'Slider' ),
                    'bricks' => __be( 'Bricks' ),
                ),
                'switch' => true,
                'type' => 'select',
            ),
            array(
                'name' => 'slider',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Fit images' ),
                'desc' => __be( 'How will images fit area. <strong>Fit when needed</strong> is best for small images, that shouldn\'t be stretched to bigger sizes, only to smaller(to keep them visible).' ),
                'id' => 'fit_variant',
                'default' => '0',
                'options' => array(
                    '0' => __be( 'Fit always' ),
                    '1' => __be( 'Fit landscape' ),
                    '2' => __be( 'Fit portrait' ),
                    '3' => __be( 'Fit when needed' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Autoplay' ),
                'desc' => __be( 'If autoplay is on, slider items will start sliding on page load' ),
                'id' => 'autoplay',
                'default' => 'G',
                'global_value' => 'G',
                'parent_option' => array('cpt_gallery', 'autoplay'),
                'options' => array(
                    'G' => __be( 'Global settings' ),
                    '1' => __be( 'Enable' ),
                    '0' => __be( 'Disable' ),
                ),
                'type' => 'select',
            ),
            array(
                'name' => __be( 'Transition type' ),
                'desc' => __be( 'Animation between slides.' ),
                'id' => 'transition',
                'default' => '-1',
                'global_value' => '-1',
                'parent_option' => array('cpt_gallery', 'transition_type'),
                'options' => array(
                    '-1' => __be( 'Global settings' ),
                    '0' => __be( 'None' ),
                    '1' => __be( 'Fade' ),
                    '2' => __be( 'Carousel' ),
                ),
                'type' => 'select',
            ),
            array(
                /*'name' => 'slider',*/
                'type' => 'switch-group-end'
            ),
            array(
                'name' => 'bricks',
                'type' => 'switch-group'
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
                'name' => __be( 'Items displayed Full width' ),
                'desc' => __be( 'Part of layout with items will be displayed on full width. In other case items will be displayed in standard layout "box".' ),
                'id' => 'full_width',
                'default' => 'on',
                'options' => array(
                    'on' => __be( 'Enable' ),
                    'off' => __be( 'Disable' ),
                ),
                'type' => 'radio',
            ),
            array(
                /*'name' => 'bricks',*/
                'type' => 'switch-group-end'
            ),
            array(
                /*'id' => 'theme',  just for readability */
                'type' => 'end-switch',
            ),
		);

		return $meta;
	}

	function apollo13_metaboxes_cpt_images(){
		$meta = array(
			array(
				'name' => '',
				'type' => 'fieldset'
			),
			array(
				'name' => __be( 'Multi upload' ),
				'desc' => '',
				'id' => 'multi-upload',
				'type' => 'multi-upload-WP3.5',
			),
			array(
				'name' => '',
				'type' => 'fieldset',
				'additive' => true,
                'for_thumbs' => true,
				'id' => 'images_n_videos',
                'default' => '[]', //empty JSON
			),
            array(
                'name' => __be( 'Choose image or video' ),
                'desc' =>__be( 'Choose between Image or Video' ),
                'id' => 'item_type',
                'default' => 'image',
                'type' => 'radio',
                'switch' => true,
                'options' => array(
                    'image' => __be( 'Image' ),
                    'video' => __be( 'Video' )
                ),
            ),
            array(
                'name' => 'image',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Attachment id' ),
                'desc' => '',
                'id' => 'attachment_id',
                'default' => '',
                'type' => 'hidden'
            ),
            array(
                'name' => __be( 'Thumb input' ),
                'desc' => '',
                'id' => 'item_image_thumb',
                'default' => '',
                'type' => 'hidden'
            ),
            array(
                'name' => __be( 'Upload image' ),
                'desc' => '',
                'id' => 'item_image',
                'default' => '',
                'button_text' => __be('Upload Image'),
                'for_thumb' => true,
                'type' => 'upload'
            ),
            array(
                'name' => __be( 'Link' ),
                'desc' => __be('Alternative link'),
                'id' => 'item_link',
                'default' => '',
                'type' => 'input',
            ),
            array(
                'name' => __be( 'Color under photo' ),
                'desc' => __be( 'Leave empty to use album default.' ) . ' ' . __be( 'Use valid CSS <code>color</code> property values( <code>green, #33FF99, rgb(255,128,0)</code> ), or get your color with color picker tool. Left empty to use default theme value.' ),
                'id' => 'bg_color',
                'default' => '',
                'type' => 'color'
            ),
            array(
                /*'name' => 'image',*/
                'type' => 'switch-group-end'
            ),
            array(
                'name' => 'video',
                'type' => 'switch-group'
            ),
            array(
                'name' => __be( 'Link to video' ),
                'desc' => '',
                'id' => 'item_video',
                'default' => '',
                'input_class' => 'for-thumb-title-video',
                'type' => 'input'
            ),
            array(
                'name' => __be( 'Video Thumb' ),
                'desc' => __be( 'Displayed instead of video placeholder in some cases. If none, placeholder will be used(for youtube movies default thumbnail will show).' ),
                'id' => 'item_video_thumb',
                'default' => '',
                'button_text' => __be('Upload Image'),
                'type' => 'upload'
            ),
            array(
                'name' => __be( 'Autoplay video' ),
                'desc' => '',
                'id' => 'item_video_autoplay',
                'default' => '0',
                'options' => array(
                    '1'  => __be( 'On' ),
                    '0' => __be( 'Off' ),
                ),
                'type' => 'select',
            ),
            array(
                /*'name' => 'video',*/
                'type' => 'switch-group-end'
            ),
            array(
                /*'id' => 'image_or_video',  just for readability */
                'type' => 'end-switch',
            ),
            array(
                'name' => __be( 'Title' ),
                'desc' => '',
                'id' => 'item_title',
                'default' => '',
                'input_class' => 'for-thumb-title',
                'type' => 'input'
            ),
            array(
                'name' => __be( 'Description' ),
                'desc' => '',
                'id' => 'item_desc',
                'default' => '',
                'type' => 'textarea',
            ),
			array(
				'name' => __be( 'Add next image or video' ),
				'desc' => '',
				'default' => '1',
				'type' => 'adder',
			),
		);
		
		return $meta;
	}