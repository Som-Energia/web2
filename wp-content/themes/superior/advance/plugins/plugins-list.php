<?php
return array(

    array(
        'name'     				=> 'Revolution Slider', // The plugin name
        'slug'     				=> 'revslider', // The plugin slug (typically the folder name)
        'source'   				=> A13_TPL_PLUGINS.'/revslider.zip', // The plugin source
        'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
        'version' 				=> '4.5.4', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
        'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
        'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
//                'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
    ),

    array(
        'name'     				=> 'LayerSlider WP', // The plugin name
        'slug'     				=> 'LayerSlider', // The plugin slug (typically the folder name)
        'source'   				=> A13_TPL_PLUGINS.'/layersliderwp.zip', // The plugin source
        'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
        'version' 				=> '5.1.1', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
        'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
        'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
    ),

    array(
        'name'     				=> 'Go - Responsive Pricing & Compare Tables', // The plugin name
        'slug'     				=> 'go_pricing', // The plugin slug (typically the folder name)
        'source'   				=> A13_TPL_PLUGINS.'/go_pricing.zip', // The plugin source
        'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
        'version' 				=> '2.4.2', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
        'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
        'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
    ),

    array(
        'name'     				=> 'Apollo13 Shortcodes', // The plugin name
        'slug'     				=> 'a13_shortcodes', // The plugin slug (typically the folder name)
        'source'   				=> A13_TPL_PLUGINS.'/a13_shortcodes.zip', // The plugin source
        'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
        'version' 				=> '1.0.7', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
        'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
        'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
    ),

    array(
        'name'     				=> 'WPBakery Visual Composer', // The plugin name
        'slug'     				=> 'js_composer', // The plugin slug (typically the folder name)
        'source'   				=> A13_TPL_PLUGINS.'/js_composer.zip', // The plugin source
        'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
        'version' 				=> '4.2.3', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
        'force_activation' 		=> true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
        'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
    ),

    // This is an example of how to include a plugin from the WordPress Plugin Repository
    array(
        'name' 	    => 'Contact Form 7',
        'slug' 		=> 'contact-form-7',
        'required'  => false,
        'version' 	=> '3.7.2'
    ),

    array(
        'name' 	    => 'Breadcrumb NavXT',
        'slug' 		=> 'breadcrumb-navxt',
        'required'  => false,
        'version' 	=> '5.0.1'
    ),

    array(
        'name' 	    => 'I Recommend This',
        'slug' 		=> 'i-recommend-this',
        'required'  => false,
        'version' 	=> '2.6.3'
    ),

);