<?php
/**
 * ReduxFramework Sample Config File
 * For full documentation, please visit: http://docs.reduxframework.com/
 */
if (! class_exists('Redux')) {
    return;
}

// This line is only for altering the demo. Can be easily removed.
$opt_name = apply_filters('opt_name', 'opt_theme_options');
$theme = wp_get_theme(); // For use with some settings. Not necessary.
$args = array(
    // TYPICAL -> Change these values as you need/desire
    'opt_name' => $opt_name,
    // This is where your data is stored in the database and also becomes your global variable name.
    'display_name' => $theme->get('Name'),
    // Name that appears at the top of your panel
    'display_version' => $theme->get('Version'),
    // Version that appears at the top of your panel
    'menu_type' => 'menu',
    // Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
    'allow_sub_menu' => true,
    // Show the sections below the admin menu item or not
    'menu_title' => $theme->get('Name'),
    'page_title' => $theme->get('Name'),
    // You will need to generate a Google API key to use this feature.
    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
    'google_api_key' => '',
    // Set it you want google fonts to update weekly. A google_api_key value is required.
    'google_update_weekly' => false,
    // Must be defined to add google fonts to the typography module
    'async_typography' => false,
    // Use a asynchronous font on the front end or font string
    // 'disable_google_fonts_link' => true, // Disable this in case you want to create your own google fonts loader
    'admin_bar' => true,
    // Show the panel pages on the admin bar
    'admin_bar_icon' => 'dashicons-smiley',
    // Choose an icon for the admin bar menu
    'admin_bar_priority' => 50,
    // Choose an priority for the admin bar menu
    'global_variable' => '',
    // Set a different name for your global variable other than the opt_name
    'dev_mode' => false,
    // Show the time the page took to load, etc
    'update_notice' => true,
    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
    'customizer' => true,
    // Enable basic customizer support
    // 'open_expanded' => true, // Allow you to start the panel in an expanded way initially.
    'disable_save_warn' => true, // Disable the save warning when a user changes a field

    // OPTIONAL -> Give you extra features
    'page_priority' => null,
    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
    'page_parent' => 'themes.php',
    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
    'page_permissions' => 'manage_options',
    // Permissions needed to access the options panel.
    'menu_icon' => 'dashicons-dashboard',
    // Specify a custom URL to an icon
    'last_tab' => '',
    // Force your panel to always open to a specific tab (by id)
    'page_icon' => 'dashicons-smiley',
    // Icon displayed in the admin panel next to your menu_title
    'page_slug' => '',
    // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
    'save_defaults' => true,
    // On load save the defaults to DB before user clicks save or not
    'default_show' => false,
    // If true, shows the default value next to each field that is not the default value.
    'default_mark' => '',
    // What to print by the field's title if the value shown is default. Suggested: *
    'show_import_export' => true,
    // Shows the Import/Export panel when not used as a field.

    // CAREFUL -> These options are for advanced use only
    'transient_time' => 60 * MINUTE_IN_SECONDS,
    'output' => true,
    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
    'output_tag' => true,
    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
    // 'footer_credit' => '', // Disable the footer credit of Redux. Please leave if you can help it.

    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
    'database' => '',
    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
    'use_cdn' => true,
    // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

    // HINTS
    'hints' => array(
        'icon' => 'el el-question-sign',
        'icon_position' => 'right',
        'icon_color' => 'lightgray',
        'icon_size' => 'normal',
        'tip_style' => array(
            'color' => 'red',
            'shadow' => true,
            'rounded' => false,
            'style' => ''
        ),
        'tip_position' => array(
            'my' => 'top left',
            'at' => 'bottom right'
        ),
        'tip_effect' => array(
            'show' => array(
                'effect' => 'slide',
                'duration' => '500',
                'event' => 'mouseover'
            ),
            'hide' => array(
                'effect' => 'slide',
                'duration' => '500',
                'event' => 'click mouseleave'
            )
        )
    )
);

Redux::setArgs($opt_name, $args);

/**
 * General Options.
 */
Redux::setSection($opt_name, array(
    'title' => esc_html__('General', 'acumec'),
    'icon' => 'el-icon-adjust-alt',
    'fields' => array(
       array(
            'title'     => esc_html__('Boxed Layout', 'acumec'),
            'subtitle'  => esc_html__('make your site is boxed?', 'acumec'),
            'id'        => 'general_layout',
            'type'      => 'switch',
            'default'   => false
        ),
        array(
            'title'     => esc_html__('Boxed width', 'acumec'),
            'subtitle'  => esc_html__('This option just applied for screen larger than value you enter here!', 'acumec'),
            'id'        => 'body_width',
            'type'      => 'dimensions',
            'units'     => array('px'),
            'height'    => false,
            'default'   => array(
                'width' => '1240px',
                'units' => 'px'
            ),
            'required'  => array( 'general_layout', '=', 1),
        ),

        array(
            'title'             => esc_html__('Body Background', 'acumec'),
            'id'                => 'general_background',
            'type'              => 'background',
            'preview'           => false,
            'output'            => array( '.boxed-layout' ),
            'required'  => array( 'general_layout', '=', 1),
        ),
         array(
            'title'             => esc_html__('Content Background', 'acumec'),
            'id'                => 'content_background',
            'type'              => 'background',
            'preview'           => false,
            'output'            => array( '.boxed-layout .site-content' ),
            'required'  => array( 'general_layout', '=', 1),
        ),
        array(
            'subtitle'          => esc_html__('Enable back to top button.', 'acumec'),
            'id'                => 'general_back_to_top',
            'type'              => 'switch',
            'title'             => esc_html__('Back To Top', 'acumec'),
            'default'           => true,
        )
    )
));

/**
 * Header Options
 * 
 * @author Fox
 */
Redux::setSection($opt_name, array(
    'title' => esc_html__('Header', 'acumec'),
    'icon' => 'el-icon-credit-card',
    'fields' => array(
        array(
            'title'     => esc_html__('Enable', 'acumec'),
            'id'        => 'enable_header',
            'type'      => 'switch',
            'default'   => true,
        ),
        array(
            'id'                => 'header_layout',
            'title'             => esc_html__('Layouts', 'acumec'),
            'default'           => 'default',
            'type'              => 'image_select',
            'options'           => array(
                                'default' => get_template_directory_uri() . '/assets/images/header/header1.png',
                                'layout2' => get_template_directory_uri() . '/assets/images/header/header2.png',
                                'layout3' => get_template_directory_uri() . '/assets/images/header/header3.png',
            ),
            'required'  => array( 'enable_header', '=', 1)
        ),
        array(
            'subtitle'          => esc_html__('enable header full width.', 'acumec'),
            'id'                => 'menu_fullwidth',
            'type'              => 'switch',
            'title'             => esc_html__('Header Full Width', 'acumec'),
            'default'           => false,  
            'required'  =>  array(   array('header_layout', '=', array('layout2','layout3')),
                                    array('required'  => array( 'enable_header', '=', 1))
                                ),
        ),
        array(
            'subtitle'          => esc_html__('enable transparent mode for menu.', 'acumec'),
            'id'                => 'menu_transparent',
            'type'              => 'switch',
            'title'             => esc_html__('Transparent Header', 'acumec'),
            'default'           => false, 
            'required'  => array( 'enable_header', '=', 1) 
        ),      
        array(
            'subtitle'          => esc_html__('enable sticky mode for menu.', 'acumec'),
            'id'                => 'menu_sticky',
            'type'              => 'switch',
            'title'             => esc_html__('Sticky Header', 'acumec'),
            'default'           => false,  
            'required'  => array( 'enable_header', '=', 1)
        ),
        array(
            'subtitle'          => esc_html__('Border Bottom.', 'acumec'),
            'id'                => 'border_bottom',
            'type'              => 'switch',
            'title'             => esc_html__('Border Bottom Header', 'acumec'),
            'default'           => false,
            'required'  => array( 'enable_header', '=', 1)
        ),
        array(
            'id' => 'border_color',
            'type' => 'color',
            'title' => esc_html__('Border color', 'acumec'),
            'default' => 'transparent',
            'required'  =>  array( 'border_bottom', '=', 1),     
        ),  
    )
));

/* Logo */
Redux::setSection($opt_name, array(
    'icon' => 'el-icon-minus',
    'title' => esc_html__('Logo', 'acumec'),
    'subsection' => true,
    'fields' => array(
        array(
            'title'             => esc_html__('Select Logo', 'acumec'),
            'subtitle'          => esc_html__('Select an image file for your logo.', 'acumec'),
            'id'                => 'main_logo',
            'type'              => 'media',
            'url'               => true,
            'default'           => array(
                'url'=>get_template_directory_uri().'/assets/images/logo.png'
            ),
            'required'  => array('header_layout', '=',array('default')),
        ),
        array(
            'title'             => esc_html__('Select Logo', 'acumec'),
            'subtitle'          => esc_html__('Select an image file for your logo.', 'acumec'),
            'id'                => 'main_logo1',
            'type'              => 'media',
            'url'               => true,
            'default'           => array(
                'url'=>get_template_directory_uri().'/assets/images/logo_blue.png'
            ),
            'required'  => array('header_layout', '=','layout2'),
        ),        
        array(
            'title'             => esc_html__('Select Logo For Sticky Menu', 'acumec'),
            'subtitle'          => esc_html__('Select an image file for your logo.', 'acumec'),
            'id'                => 'sticky_logo',
            'type'              => 'media',
            'url'               => true,
            'default'           => array(
                'url'=>get_template_directory_uri().'/assets/images/logo_blue.png'
            ),
            'required'  => array( 
                            array( 'header_layout', '=','layout2'),
                            array('menu_sticky','=','1' ) 
                        ), 
        ),
        array(
            'title'             => esc_html__('Select Logo For Transparent', 'acumec'),
            'subtitle'          => esc_html__('Select an image file for logo transparent.', 'acumec'),
            'id'                => 'transparent_logo',
            'type'              => 'media',
            'url'               => true,
            'default'           => array(
                'url'=>get_template_directory_uri().'/assets/images/logo_white.png'
            ),
            'required'  =>  array(
                            array('header_layout', '=',array('default','layout2')),
                            array('menu_transparent','=','1' )
            ),
                              
        ),
        array(
            'title'             => esc_html__('Select Logo', 'acumec'),
            'subtitle'          => esc_html__('Select an image file for your logo.', 'acumec'),
            'id'                => 'main_logo2',
            'type'              => 'media',
            'url'               => true,
            'default'           => array(
                'url'=>get_template_directory_uri().'/assets/images/logo_white.png'
            ),
            'required'  => array('header_layout', '=','layout3'),
        ), 
        array(
            'title'             => esc_html__('Select Logo For Sticky Menu', 'acumec'),
            'subtitle'          => esc_html__('Select an image file for your logo.', 'acumec'),
            'id'                => 'sticky_logo1',
            'type'              => 'media',
            'url'               => true,
            'default'           => array(
                'url'=>get_template_directory_uri().'/assets/images/logo_white.png'
            ),
            'required'  => array( 
                            array( 'header_layout', '=','layout3'),
                            array('menu_sticky','=','1' ),
                        ), 
        ),
        array(
            'subtitle'          => esc_html__('Set max height for logo.', 'acumec'),
            'id'                => 'logo_max_height',
            'type'              => 'dimensions',
            'units'             => array('px'),
            'width'             => false,
            'default'           => '50',
            'title'             => esc_html__('Logo Max Height', 'acumec'),
        ),
    ),
));

/* Header top */
Redux::setSection($opt_name, array(
    'icon' => 'el-icon-minus',
    'title' => esc_html__('Header top', 'acumec'),
    'subsection' => true,
    'fields' => array(
        array(
            'title'     => esc_html__('Enable', 'acumec'),
            'id'        => 'enable_header_top',
            'type'      => 'switch',
            'default'   => false,
        ),
        array(
            'subtitle'          => esc_html__('Full width', 'acumec'),
            'id'                => 'header_top_full_width',
            'type'              => 'switch',
            'title'             => esc_html__('Full Width', 'acumec'),
            'default'           => false,
            'required'  => array( 'enable_header_top', '=', 1),
        ), 
        array(
            'title'             => esc_html__('Typography', 'acumec'),
            'subtitle'          => esc_html__('Header top typography.', 'acumec'),
            'id'                => 'header_top_typography',
            'type'              => 'typography',
            'google'            => true,
            'color'             => false,
            'output'            => array( '#header_top .header-top-right .block-right,#header_top .widget a, #header_top .cshero-header-cart-search a,#header-top .header-top-right .icon-header,#header_top p,#header_top h1,#header_top h2,#header_top h3,#header_top h4,#header_top h5,#header_top h6 ' ),
            'required'  => array( 'enable_header_top', '=', 1),
        ),
        array(
            'id'                => 'header_top_background',
            'type'              => 'color_rgba',
            'title'             => esc_html__( 'Background Color', 'acumec' ),
            'subtitle'          => esc_html__( 'Header top background color', 'acumec' ),
            'output'   => array(
                'background-color' => '.site-header .header-top.layout2:before'
            ),
            'required'  => array( 'enable_header_top', '=', 1),
        ),
        array(
            'title'             => esc_html__('Header Top Background Image', 'acumec'),
            'subtitle'          => esc_html__('Header top background image.', 'acumec'),
            'id'                => 'header_top_background_image',
            'type'              => 'background',
            'preview'           => false,
            'background-color'  => false,
            'output'            => array('#header_top'),
            'required'  => array( 'enable_header_top', '=', 1),
        ),
        array(
            'id'                => 'header_top_text_color',
            'type'              => 'color',
            'title'             => esc_html__( 'Text color', 'acumec' ),
            'output'            => '#header_top p, .header-top .widget, .header-top p, .header-top .widget_text ul li, #header_top h1,#header_top h2,#header_top h3,#header_top h4,#header_top h5,#header_top h6',
            'required'  => array( 'enable_header_top', '=', 1),
        ),
        array(
            'id'                => 'header_top_icon_color',
            'type'              => 'color',
            'title'             => esc_html__( 'Icon color', 'acumec' ),
            'output'            => '#header_top p i',
            'required'  => array( 'enable_header_top', '=', 1),
        ),
        array(
            'id'                => 'header_top_border_color',
            'type'              => 'color',
            'title'             => esc_html__( 'Border color', 'acumec' ),
            'output'            => array('background' => '#header_top .header-top-wrap .header-top-left .widget:before,#header_top .widget_authenticate .fs-link a + a:before'),
            'required'  => array( 'enable_header_top', '=', 1),
        ),
        array(
            'id'                => 'header_top_link_color',
            'type'              => 'link_color',
            'title'             => esc_html__( 'Links Color', 'acumec' ),
            'subtitle'          => esc_html__( 'Select links color in header top', 'acumec' ),
            'regular'           => true,
            'hover'             => true,
            'active'            => false,
            'visited'           => false,
            'output'            => array( '#header_top a, .header-top a, .header-top ul li a'),
            'required'  => array( 'enable_header_top', '=', 1),
        ), 
    ),
)); 

/* Header middle */
Redux::setSection($opt_name, array(
    'icon' => 'el-icon-minus',
    'title' => esc_html__('Header middle', 'acumec'),
    'subsection' => true,
    'fields' => array(
        array(
            'title' => esc_html__('Note: Not apply for this header layout.', 'acumec'),
            'id'   => 'hdt-panel2',
            'type' => 'info',
            'style' => 'warning',
            'required'  => array( 'header_layout', '=',array('layout2','layout3') ), 
        ),
        array(
            'subtitle'          => esc_html__('Full width', 'acumec'),
            'id'                => 'header_middle_full_width',
            'type'              => 'switch',
            'title'             => esc_html__('Full Width', 'acumec'),
            'default'           => false,
            'required'  => array( 'header_layout', '=', 'default'), 
        ), 
        array(
            'title'             => esc_html__('Typography', 'acumec'),
            'subtitle'          => esc_html__('Header middle typography.', 'acumec'),
            'id'                => 'header_middle_typography',
            'type'              => 'typography',
            'google'            => true,
            'color'             => false,
            'output'            => array( '#header_middle .header-middle-right .block-right,#header_middle .widget a, #header_middle .cshero-header-cart-search a,#header-middle .header-middle-right .icon-header,#header_middle p,#header_middle h1,#header_middle h2,#header_middle h3,#header_middle h4,#header_middle h5,#header_middle h6 ' ),
            'required'  => array( 'header_layout', '=', 'default'), 
        ),
        array(
            'id'                => 'header_middle_background',
            'type'              => 'color_rgba',
            'title'             => esc_html__( 'Background Color', 'acumec' ),
            'subtitle'          => esc_html__( 'Header middle background color', 'acumec' ),
            'output'   => array(
                'background-color' => '.site-header.header-default:before'
            ),
            'required'  => array( 'header_layout', '=', 'default'), 
        ),
        array(
            'title'             => esc_html__('Header middle Background Image', 'acumec'),
            'subtitle'          => esc_html__('Header middle background image.', 'acumec'),
            'id'                => 'header_middle_background_image',
            'type'              => 'background',
            'preview'           => false,
            'background-color'  => false,
            'output'            => array('.site-header.header-default'),
            'required'  => array( 'header_layout', '=', 'default'), 
        ),
        array(
            'id'                => 'header_middle_text_color',
            'type'              => 'color',
            'title'             => esc_html__( 'Text color', 'acumec' ),
            'output'            => '#header_middle p, .header-middle .widget, .header-middle p, .header-middle .widget_text ul li, #header_middle h1,#header_middle h2,#header_middle h3,#header_middle h4,#header_middle h5,#header_middle h6',
            'required'  => array( 'header_layout', '=', 'default'), 
        ),
        array(
            'id'                => 'header_middle_icon_color',
            'type'              => 'color',
            'title'             => esc_html__( 'Icon color', 'acumec' ),
            'output'            => '#header_middle p i',
            'required'  => array( 'header_layout', '=', 'default'), 
        ),
        array(
            'title'             => esc_html__('Icon Font size', 'acumec'),
            'subtitle'          => esc_html__('Header middle icon font size (px).', 'acumec'),
            'id'                => 'header_middle_icon_fontsize',
            'type'              => 'typography',
            'google'            => false,
            'color'             => false,
            'font-style'        => false,
            'font-family'       => false,
            'subsets'           => false,
            'line-height'       => false,
            'text-align'        => false,
            'font-weight'       => false,
            'font_family_clear' => false,
            'output'            => array( '#header_middle .block-left p.icon-header i' ),
            'required'  => array( 'header_layout', '=', 'default'), 
        ),
        array(
            'id'                => 'header_middle_border_color',
            'type'              => 'color',
            'title'             => esc_html__( 'Border color', 'acumec' ),
            'output'            => array('border-color' => '#header_middle .widget p'),
            'required'  => array( 'header_layout', '=', 'default'), 
        ),
        array(
            'id'                => 'header_middle_border_dimensions',
            'type'              => 'dimensions',
            'title'             => esc_html__( 'Width/Height Border', 'acumec' ),
            'output'            => array('#header_middle .widget .icon-header'),
            'required'  => array( 'header_layout', '=', 'default'), 
        ),
        array(
            'id'                => 'header_middle_link_color',
            'type'              => 'link_color',
            'title'             => esc_html__( 'Links Color', 'acumec' ),
            'subtitle'          => esc_html__( 'Select links color in header middle', 'acumec' ),
            'regular'           => true,
            'hover'             => true,
            'active'            => false,
            'visited'           => false,
            'output'            => array( '#header_middle a, .header-middle a, .header-middle ul li a'),
            'required'  => array( 'header_layout', '=', 'default'), 
        ), 
    ),

));

/* Download button */
Redux::setSection($opt_name, array(
    'icon' => 'el-icon-minus',
    'title' => esc_html__('Download Button', 'acumec'),
    'subsection' => true,
    'fields' => array(
        array(
            'title' => esc_html__('Note: Not apply for this header layout.', 'acumec'),
            'id'   => 'hdt-panel1',
            'type' => 'info',
            'style' => 'warning',
            'required'  => array( 'header_layout', '=', 'layout2'), 
        ),
        array(
            'subtitle'          => esc_html__('Enable download button for menu.', 'acumec'),
            'id'                => 'enable_download',
            'type'              => 'switch',
            'title'             => esc_html__('Download Button', 'acumec'),
            'default'           => false,  
            'required'  => array('header_layout','=','default')
        ),
        array(
            'id'       => 'select_file_type',
            'type'     => 'select',
            'title'    => esc_html__( 'Select File Upload Type', 'acumec' ),
            'subtitle' => esc_html__( 'Select file upload type.', 'acumec' ),
            'default'    => 1,
            'options'  => array(
                1 => esc_html__('Upload', 'acumec' ),
                2 => esc_html__('Link Download', 'acumec' ),
            ),
            'required'  => array('enable_download','=',1)
        ),
        array(
            'title'             => esc_html__('Download Tile ', 'acumec'),
            'id'                => 'download_title',
            'type'              => 'text',
            'default'           => esc_html__('DOWNLOAD BROCHURE','acumec'),
            'required'  => array('enable_download','=',1)
        ),
        array(
            'title'             => esc_html__('Select File', 'acumec'),
            'id'                => 'file_upload',
            'type'              => 'media',
            'url'               => true,
            'mode'              => false,
            'required'  => array('select_file_type', '=',1),
        ),
        array(
            'title'             => esc_html__('Link File Download', 'acumec'),
            'id'                => 'link_download',
            'type'              => 'text',
            'required'  => array('select_file_type', '=',2),
        ),
        array(
            'id'                => 'download_background_color',
            'type'              => 'color_rgba',
            'title'             => esc_html__( ' Download Background Color', 'acumec' ),
            'subtitle'          => esc_html__( 'Download background color', 'acumec' ),
            'output'   => array(
                'background-color' => '.site-header.header-default .header-main .header-main-right .header-main-right-wrap'
            ), 
            'required'  => array('enable_download','=',1)        
        ),
        array(
            'id'                => 'download_link_color',
            'type'              => 'link_color',
            'title'             => esc_html__( 'Download link Color', 'acumec' ),
            'subtitle'          => esc_html__( 'Select links color in download button', 'acumec' ),
            'regular'           => true,
            'hover'             => true,
            'active'            => false,
            'visited'           => false,
            'output'            => array( '.site-header.header-default .header-main .header-main-right a'),
            'required'  => array('enable_download','=',1)
        ),
    ),
));

/* Main menu*/
Redux::setSection($opt_name, array(
    'icon' => 'el-icon-minus',
    'title' => esc_html__('Main menu', 'acumec'),
    'subsection' => true,
    'fields' => array(
        array(
            'title'     => esc_html__('Padding Main Menu', 'acumec'),
            'subtitle'  => esc_html__('Choose padding for main menu', 'acumec'),
            'id'        => 'menu_padding',
            'type'      => 'spacing',
            'mode'      => 'padding',
            'units'     => array('px'),     
            'output'    => array('#masthead .main-navigation .menu-main-menu > li > a, #masthead .main-navigation .menu-main-menu > li .cs-menu-toggle, #masthead .header-main-right .widget_text a'),
            'required'  => array( 'header_layout', '=',array('default','layout2','layout3')), 
        ),
        array(
            'id'                => 'header_background_color',
            'type'              => 'color_rgba',
            'title'             => esc_html__( ' Main Menu Background Color', 'acumec' ),
            'subtitle'          => esc_html__( 'Main Menu background color', 'acumec' ),
            'output'   => array(
                'background-color' => '.header-default #cshero-header.header-main .header-main-left-menu:before,.header-layout2 #cshero-header.header-main:before,.site-header.header-layout3 .header-main:before '
            ),          
        ),
        array(
            'id'                => 'header_transparent_background_color',
            'type'              => 'color_rgba',
            'title'             => esc_html__( 'Transparent Main Menu Background Color', 'acumec' ),
            'subtitle'          => esc_html__( 'Transparent main menu  background color', 'acumec' ),
            'output'   => array(
                'background-color' => '#masthead.header-layout3.header-transparent .header-main:before,#masthead.header-default.header-transparent .header-main .header-main-left-menu:before,#masthead.header-layout2.header-transparent .header-main:before'
            ),            
        ),
        array(
            'id'                => 'header_sticky_background_color',
            'type'              => 'color_rgba',
            'title'             => esc_html__( 'Sticky Main Menu Background Color', 'acumec' ),
            'subtitle'          => esc_html__( 'Sticky main menu background color', 'acumec' ),
            'output'   => array(
                'background-color' => '.header-main.sticky-desktop.header-fixed:before,#cshero-header.header-fixed:before,#masthead.header-transparent .header-main.header-fixed:before, #masthead.header-layout2 .header-main.header-fixed:before'
            ),            
        ),
        array(
            'title'             => esc_html__('Typography', 'acumec'),
            'subtitle'          => esc_html__('Main Menu typography.', 'acumec'),
            'id'                => 'main_menu_typography',
            'type'              => 'typography',
            'google'            => true,
            'color'             => false,
            'text-align'        => false,
            'output'            => array( '#masthead.site-header .header-main .menu-main-menu > li > a, #masthead .main-navigation .menu-main-menu > li .cs-menu-toggle' )
        ),
        array(
            'id'                => 'header_link_color',
            'type'              => 'link_color',
            'title'             => esc_html__( 'Links Color', 'acumec' ),
            'subtitle'          => esc_html__( 'Select links color in header', 'acumec' ),
            'regular'           => true,
            'hover'             => true,
            'active'            => false,
            'visited'           => false,
            'output'            => array( '#cshero-header a, #header_middle a' ),
        ),
    )
)); 

/* Sub menu*/
Redux::setSection($opt_name, array(
    'icon' => 'el-icon-minus',
    'title' => esc_html__('Sub menu', 'acumec'),
    'subsection' => true,
    'fields' => array(
        array(
            'id'                => 'sub_menu_background',
            'type'              => 'color_rgba',
            'title'             => esc_html__( 'Background Color', 'acumec' ),
            'subtitle'          => esc_html__( 'Sub menu background color', 'acumec' ),
            'output'   => array(
                'background-color' => '#cshero-header-navigation .multicolumn.sub-menu:before, #cshero-header-navigation .standar-dropdown.sub-menu:before, #cshero-header-navigation .standar-dropdown.sub-menu ul.sub-menu:before'
            ),
        ),
        array(
            'title'             => esc_html__('Sub Menu Background Image', 'acumec'),
            'subtitle'          => esc_html__('Sub menu background image.', 'acumec'),
            'id'                => 'submenu_background_image',
            'type'              => 'background',
            'preview'           => false,
            'background-color'  => false,
            'output'            => array( '#cshero-header-navigation .multicolumn.sub-menu,#cshero-header-navigation .standar-dropdown.sub-menu, #cshero-header-navigation .standar-dropdown.sub-menu .ul.sub-menu' )
        ),
        array(
            'subtitle' => esc_html__('Background Sub menu Hover .', 'acumec'),
            'id' => 'menu_hover_color',
            'type' => 'color',
            'title' => esc_html__('Background Sub menu Hover', 'acumec'),
        ),
        array(
            'id'                => 'sub_menu_link_color',
            'type'              => 'link_color',
            'title'             => esc_html__( 'Links Color', 'acumec' ),
            'subtitle'          => esc_html__( 'Select links color in sub menu', 'acumec' ),
            'regular'           => true,
            'hover'             => true,
            'active'            => false,
            'visited'           => false,
            'output'            => array( '#cshero-header-navigation .sub-menu li a'),
        ),
    )
)); 

/* Menu Mobile*/
Redux::setSection($opt_name, array(
    'icon' => 'el-icon-minus',
    'title' => esc_html__('Menu Mobile', 'acumec'),
    'subsection' => true,
    'fields' => array(
        array(
            'title'     => esc_html__('Padding Main Menu', 'acumec'),
            'subtitle'  => esc_html__('Choose padding for main menu', 'acumec'),
            'id'        => 'menu_mobile_padding',
            'type'      => 'spacing',
            'mode'      => 'padding',
            'units'     => array('px'),     
            'output'    => array('#masthead.header-mobile .main-navigation .menu-main-menu > li a, #masthead.header-mobile .main-navigation .menu-main-menu > li .cs-menu-toggle'),
            'required'  => array( 'header_layout', '=',array('default','layout2','layout3')), 
        ),
        array(
            'id'                => 'header_mobile_background_color',
            'type'              => 'color_rgba',
            'title'             => esc_html__( ' Main Menu Background Color', 'acumec' ),
            'subtitle'          => esc_html__( 'Main Menu background color', 'acumec' ),
            'output'   => array(
                'background-color' => '#cshero-header-navigation:before'
            ),         
        ),
        array(
            'title'             => esc_html__('Main Menu Background Image', 'acumec'),
            'subtitle'          => esc_html__('Main Menu background image.', 'acumec'),
            'id'                => 'header_mobile_background_image',
            'type'              => 'background',
            'preview'           => false,
            'background-color'  => false,
            'output'            => array('#cshero-header-navigation' ),
        ),

        array(
            'title'             => esc_html__('Typography', 'acumec'),
            'subtitle'          => esc_html__('Main Menu typography.', 'acumec'),
            'id'                => 'main_menu_mobile_typography',
            'type'              => 'typography',
            'google'            => true,
            'color'             => false,
            'text-align'        => false,
            'output'            => array( '#masthead.site-header .header-main .menu-main-menu > li a, #masthead .main-navigation .menu-main-menu > li .cs-menu-toggle' )
        ),
        array(
            'id'                => 'header_mobile_link_color',
            'type'              => 'link_color',
            'title'             => esc_html__( 'Links Color', 'acumec' ),
            'subtitle'          => esc_html__( 'Select links color in header', 'acumec' ),
            'regular'           => true,
            'hover'             => true,
            'active'            => false,
            'visited'           => false,
            'output'            => array( '#cshero-header a' ),
        ),
    )
)); 

/**
 * Page Title
 *
 * @author Fox
 */
Redux::setSection($opt_name, array(
    'title' => esc_html__('Page Title & BC', 'acumec'),
    'icon' => 'el-icon-map-marker',
    'fields' => array(
        array(
            'id'                => 'page_title_background_color',
            'type'              => 'color_rgba',
            'title'             => esc_html__( 'Background color', 'acumec' ),
            'output'   => array(
                'background' => '.page-title .bg-overlay',
            ),
        ),   
        array(
            'title'             => esc_html__('Background image', 'acumec'),
            'subtitle'          => esc_html__('Page title background image.', 'acumec'),
            'id'                => 'page_title_background_image',
            'type'              => 'background',
            'preview'           => true,
            'background-color'  => false,
            'output'            => array( '.page-title' ),
        ),
        array(
            'id'                => 'page_title_layout', 
            'title'             => esc_html__('Layouts', 'acumec'),
            'subtitle'          => esc_html__('select a layout for page title', 'acumec'),
            'default'           => '2',
            'type'              => 'image_select',
            'options'           => array(
                                    '2' => get_template_directory_uri().'/assets/images/pagetitle/pt-s-1.png',
                                    '3' => get_template_directory_uri().'/assets/images/pagetitle/pt-s-2.png',
                                    '4' => get_template_directory_uri().'/assets/images/pagetitle/pt-s-3.png',
                                    '5' => get_template_directory_uri().'/assets/images/pagetitle/pt-s-4.png',
                                    '6' => get_template_directory_uri().'/assets/images/pagetitle/pt-s-5.png',
                                    '7' => get_template_directory_uri().'/assets/images/pagetitle/pt-s-6.png',
                                )
        ),
        array(
            'id'        => 'page_title_align',
            'title'     => esc_html__('Align', 'acumec'),
            'type'      => 'button_set',
            'options' => array(
                'text-left'     => esc_html__('Left','acumec'), 
                'text-center'     => esc_html__('Center','acumec'),
                'text-right'     => esc_html__('Right','acumec'), 
            ), 
            'default'   => 'text-center',
        ),
        array(
            'title'             => esc_html__('Typography', 'acumec'),
            'subtitle'          => esc_html__('Page title typography.', 'acumec'),
            'id'                => 'page_title_typography',
            'type'              => 'typography',
            'google'            => true,
            'output'            => array( '#page-title .page-title-text h1' )
        ),
        array(
            'subtitle'          => esc_html__('Set padding for Page Title.', 'acumec'),
            'id'                => 'page_title_height',
            'type'              => 'spacing',
            'mode'              => 'padding',
            'right'             => false,
            'left'              => false,
            'units'             => array('px'),
            'title'             => esc_html__('Page Title padding', 'acumec'),
            'output'            => array( '#page-title')
        ),
    )
));
/* Breadcrumb */
Redux::setSection($opt_name, array(
    'icon' => 'el-icon-random',
    'title' => esc_html__('Breadcrumb', 'acumec'),
    'subsection' => true,
    'fields' => array(
        array(
            'title'             => esc_html__('Typography', 'acumec'),
            'subtitle'          => esc_html__('Breadcrumb typography.', 'acumec'),
            'id'                => 'breadcrumb_typography',
            'type'              => 'typography',
            'google'            => true,
            'output'            => array( '#page-title .breadcrumb-text>span','#page-title .breadcrumb-text' )
        ),
        array(
            'id'                => 'breadcrumb_link_color',
            'type'              => 'link_color',
            'title'             => esc_html__( 'Link Color', 'acumec' ),
            'subtitle'          => esc_html__( 'Select link color in breadcrumb', 'acumec' ),
            'output'            => array( '#page-title .breadcrumb-text>span a' ),
        ),
    )
));

/**
 * Content
 *
 * css color.
 * @author Fox
 */
Redux::setSection($opt_name, array(
    'title' => esc_html__('Content', 'acumec'),
    'icon' => 'el-icon-pencil',
    'fields' => array(
        array(
            'title'     => esc_html__('Padding', 'acumec'),
            'subtitle'  => esc_html__('Choose padding for content tag', 'acumec'),
            'id'        => 'content_padding',
            'type'      => 'spacing',
            'mode'      => 'padding',
            'right' => false,
            'left' => false,
            'units'     => array('px'),     
            'output'    => array('.site-content')
        ),
    )
));

/* archive */
Redux::setSection($opt_name, array(
    'title' => esc_html__('Archive', 'acumec'),
    'icon' => 'el-icon-list',
    'subsection' => true,
    'fields' => array(
        array(
            'subtitle'          => esc_html__('Show Archive Meta.', 'acumec'),
            'id'                => 'archive_meta',
            'type'              => 'switch',
            'title'             => esc_html__('Show Archive Meta', 'acumec'),
            'default'           => true,
        ),
        array(
            'id'                => 'archive_layout',
            'title'             => esc_html__('Layouts', 'acumec'),
            'subtitle'          => esc_html__('select a layout for archive, search, index...', 'acumec'),
            'default'           => 'right',
            'type'              => 'image_select',
            'options'           => array(
                                    'left' => get_template_directory_uri().'/assets/images/content/right.png',
                                    'full' => get_template_directory_uri().'/assets/images/content/full.png',
                                    'right' => get_template_directory_uri().'/assets/images/content/left.png',
                                ),
        ),
        array(
            'subtitle'          => esc_html__('Show author.', 'acumec'),
            'id'                => 'archive_author',
            'type'              => 'switch',
            'title'             => esc_html__('Author', 'acumec'),
            'default'           => false,
            'required'  => array( 'archive_meta', '=', 1),
        ),
        array(
            'subtitle'          => esc_html__('Show date time.', 'acumec'),
            'id'                => 'archive_date',
            'type'              => 'switch',
            'title'             => esc_html__('Date', 'acumec'),
            'default'           => true,
            'required'  => array( 'archive_meta', '=', 1),
        ),
        array(
            'subtitle'          => esc_html__('Show categories.', 'acumec'),
            'id'                => 'archive_categories',
            'type'              => 'switch',
            'title'             => esc_html__('Categories', 'acumec'),
            'default'           => true,
            'required'  => array( 'archive_meta', '=', 1),
        ),       
        array(
            'subtitle'          => esc_html__('Show comment count.', 'acumec'),
            'id'                => 'archive_comment',
            'type'              => 'switch',
            'title'             => esc_html__('Comment', 'acumec'),
            'default'           => false,
            'required'  => array( 'archive_meta', '=', 1),
        ),
        array(
            'subtitle'          => esc_html__('Show tags.', 'acumec'),
            'id'                => 'archive_tag',
            'type'              => 'switch',
            'title'             => esc_html__('Tags', 'acumec'),
            'default'           => false,
            'required'  => array( 'archive_meta', '=', 1),
        ),

        array(
            'subtitle'          => esc_html__('Show Read More.', 'acumec'),
            'id'                => 'archive_readmore',
            'type'              => 'switch',
            'title'             => esc_html__('Read More', 'acumec'),
            'default'           => false,
            'required'  => array( 'archive_meta', '=', 1),
        ),
        array(
            'title' => esc_html__('Word Number Excerpt', 'acumec'),
            'id' => 'word_number',
            'type' => 'text',
            'default' => '20',
            'required'  => array( 'archive_meta', '=', 1),
        ),
    )
));
/*page*/
Redux::setSection($opt_name, array(
    'title' => esc_html__('Page', 'acumec'),
    'icon' => 'el-icon-list',
    'subsection' => true,
    'fields' => array(
        array(
            'subtitle'          => esc_html__('Show editor', 'acumec'),
            'id'                => 'page_show_frontend_editor',
            'type'              => 'switch',
            'title'             => esc_html__('Frontend editor', 'acumec'),
            'default'           => false,
        ),
      
    )
));
/* Single */
Redux::setSection($opt_name, array(
    'title' => esc_html__('Single', 'acumec'),
    'icon' => 'el-icon-file-edit',
    'subsection' => true,
    'fields' => array(
        array(
            'id'                => 'single_layout',
            'title'             => esc_html__('Blog Layouts', 'acumec'),
            'subtitle'          => esc_html__('select a layout for single...', 'acumec'),
            'default'           => 'right',
            'type'              => 'image_select',
            'options'           => array(
                                        'left' => get_template_directory_uri().'/assets/images/content/right.png',
                                        'full' => get_template_directory_uri().'/assets/images/content/full.png',
                                        'right' => get_template_directory_uri().'/assets/images/content/left.png',
                                    )
        ),
        array(
            'id'                => 'single_layout_project',
            'title'             => esc_html__('Project Layouts', 'acumec'),
            'subtitle'          => esc_html__('select a layout for single...', 'acumec'),
            'default'           => 'right',
            'type'              => 'image_select',
            'options'           => array(
                                        'left' => get_template_directory_uri().'/assets/images/content/right.png',
                                        'full' => get_template_directory_uri().'/assets/images/content/full.png',
                                        'right' => get_template_directory_uri().'/assets/images/content/left.png',
                                    )
        ),
        array(
            'id'                => 'single_layout_case_studies',
            'title'             => esc_html__('Case Study Layouts', 'acumec'),
            'subtitle'          => esc_html__('select a layout for single...', 'acumec'),
            'default'           => 'right',
            'type'              => 'image_select',
            'options'           => array(
                                        'left' => get_template_directory_uri().'/assets/images/content/right.png',
                                        'full' => get_template_directory_uri().'/assets/images/content/full.png',
                                        'right' => get_template_directory_uri().'/assets/images/content/left.png',
                                    )
        ),
        array(
            'id'                => 'single_layout_service',
            'title'             => esc_html__('Service Layouts', 'acumec'),
            'subtitle'          => esc_html__('select a layout for single...', 'acumec'),
            'default'           => 'right',
            'type'              => 'image_select',
            'options'           => array(
                                        'left' => get_template_directory_uri().'/assets/images/content/right.png',
                                        'full' => get_template_directory_uri().'/assets/images/content/full.png',
                                        'right' => get_template_directory_uri().'/assets/images/content/left.png',
                                    )
        ),
        array(
            'id'                => 'single_layout_team',
            'title'             => esc_html__('Team Layouts', 'acumec'),
            'subtitle'          => esc_html__('select a layout for single...', 'acumec'),
            'default'           => 'right',
            'type'              => 'image_select',
            'options'           => array(
                                        'left' => get_template_directory_uri().'/assets/images/content/right.png',
                                        'full' => get_template_directory_uri().'/assets/images/content/full.png',
                                        'right' => get_template_directory_uri().'/assets/images/content/left.png',
                                    )
        ),

        array(
            'id' => 'single_page_title_layout',
            'title' => esc_html__('Page Title Layouts', 'acumec'),
            'subtitle' => esc_html__('select a layout for page title', 'acumec'),
            'default' => '1',
            'type' => 'image_select', 
            'options' => array(
                '1'  => get_template_directory_uri().'/assets/images/pagetitle/pt-s-default.png',
                '2' => get_template_directory_uri().'/assets/images/pagetitle/pt-s-1.png',
                '3' => get_template_directory_uri().'/assets/images/pagetitle/pt-s-2.png',
                '4' => get_template_directory_uri().'/assets/images/pagetitle/pt-s-3.png',
                '5' => get_template_directory_uri().'/assets/images/pagetitle/pt-s-4.png',
                '6' => get_template_directory_uri().'/assets/images/pagetitle/pt-s-5.png',
                '7' => get_template_directory_uri().'/assets/images/pagetitle/pt-s-6.png',
            ),
        ),
        array(
            'id'                => 'single_page_title_background_color',
            'type'              => 'color_rgba',
            'title'             => esc_html__( 'Page Title Background color', 'acumec' ),
            'required'  => array( 'single_page_title_layout', '=', array('2','3','4','5','6','7')),
        ),
        array(
            'title'             => esc_html__('Background image', 'acumec'),
            'subtitle'          => esc_html__('Page title background image.', 'acumec'),
            'id'                => 'single_page_title_background_image',
            'type'              => 'background',
            'preview'           => true,
            'background-color'  => false,
            'required'  => array( 'single_page_title_layout', '=', array('2','3','4','5','6','7')),
        ),
        
        array(
            'subtitle'          => esc_html__('Show date time.', 'acumec'),
            'id'                => 'single_date',
            'type'              => 'switch',
            'title'             => esc_html__('Date', 'acumec'),
            'default'           => false,
        ),
        array(
            'subtitle'          => esc_html__('Show categories.', 'acumec'),
            'id'                => 'single_categories',
            'type'              => 'switch',
            'title'             => esc_html__('Categories', 'acumec'),
            'default'           => false,
        ),
        
        array(
            'subtitle'          => esc_html__('Show comment count.', 'acumec'),
            'id'                => 'single_comment',
            'type'              => 'switch',
            'title'             => esc_html__('Comment', 'acumec'),
            'default'           => false,
        ),        
        array(
            'subtitle'          => esc_html__('Show tags.', 'acumec'),
            'id'                => 'single_tag',
            'type'              => 'switch',
            'title'             => esc_html__('Tags', 'acumec'),
            'default'           => false,
        ),
         array(
            'subtitle'          => esc_html__('Show social sharing buttons.', 'acumec'),
            'id'                => 'single_social_share',
            'type'              => 'switch',
            'title'             => esc_html__('Share Buttons', 'acumec'),
            'default'           => false,
        ),
         array(
            'subtitle'          => esc_html__('Show author.', 'acumec'),
            'id'                => 'single_author',
            'type'              => 'switch',
            'title'             => esc_html__('Author', 'acumec'),
            'default'           => true,
        ),
        array(
            'id'                => 'single_author_background_color',
            'type'              => 'color_rgba',
            'title'             => esc_html__( 'Author Background color', 'acumec' ),
            'output'   => array(
                'background' => '.author-meta:before',
            ),
            'required'  => array( 'single_author', '=', 1),
        ), 
        array(
            'title'             => esc_html__('Author Background image', 'acumec'),
            'subtitle'          => esc_html__('Author background image.', 'acumec'),
            'id'                => 'single_author_background_image',
            'type'              => 'background',
            'preview'           => true,
            'background-color'  => false,
            'output'            => array( '.author-meta' ),
            'required'  => array( 'single_author', '=', 1),
        ),
        array(
            'id'                => 'single_author_color',
            'type'              => 'color_rgba',
            'title'             => esc_html__( 'Color Author', 'acumec' ),
            'output'   => array('.author-meta .item-content h4, .author-meta .item-content p.desc'),
            'required'  => array( 'single_author', '=', 1),
        ), 
        array(
            'subtitle'          => esc_html__('Show Post Related.', 'acumec'),
            'id'                => 'single_related',
            'type'              => 'switch',
            'title'             => esc_html__('Post Related', 'acumec'),
            'default'           => false,
        ),
        array(
            'subtitle'          => esc_html__('Show post previous/next.', 'acumec'),
            'id'                => 'single_post_nav',
            'type'              => 'switch',
            'title'             => esc_html__('Post Navigation', 'acumec'),
            'default'           => false,
        ),
    )
));

/**
 * Social
 *
 * @author Fox
 */
Redux::setSection($opt_name, array(
    'title' => esc_html__('Social Sharing', 'acumec'),
    'icon' => 'el-icon-share-alt',
    'fields' => array(
        array(
            'title' => esc_html__('Please enable "Share Buttons" in single tab.', 'acumec'),
            'id'   => 'hdt-panel',
            'type' => 'info',
            'style' => 'info',
            'required' => array('single_social_share','=',0),
        ),
        array(
            'id'=>'share-facebook',
            'type' => 'switch',
            'title' => esc_html__('Facebook Share', 'acumec'),
            'required' => array('single_social_share','=',1),
            'default' => true,
        ),
        array(
            'id'=>'share-twitter',
            'type' => 'switch',
            'title' => esc_html__('Twitter Share', 'acumec'),
            'required' => array('single_social_share','=',1),
            'default' => true,
        ),
        array(
            'id'=>'share-googleplus',
            'type' => 'switch',
            'title' => esc_html__('Google + Share', 'acumec'),
            'required' => array('single_social_share','=',1),
            'default' => true,  
        ),
        array(
            'id'=>'share-linkedin',
            'type' => 'switch',
            'title' => esc_html__('LinkedIn Share', 'acumec'),
            'required' => array('single_social_share','=',1),
            'default' => true,
        ),
        array(
            'id'=>'share-pinterest',
            'type' => 'switch',
            'title' => esc_html__('Pinterest Share', 'acumec'),
            'required' => array('single_social_share','=',1),
            'default' => false,
        ),
        array(
            'id'=>'share-tumblr',
            'type' => 'switch',
            'title' => esc_html__('Tumblr Share', 'acumec'),
            'required' => array('single_social_share','=',1),
            'default' => false,
        ),
        array(
            'id'=>'share-vk',
            'type' => 'switch',
            'title' => esc_html__('VK Share', 'acumec'),
            'required' => array('single_social_share','=',1),
            'default' => false,
        ),
        array(
            'id'=>'share-xing',
            'type' => 'switch',
            'title' => esc_html__('Xing Share', 'acumec'),
            'required' => array('single_social_share','=',1),
            'default' => false,
        ),
        array(
            'id'=>'share-reddit',
            'type' => 'switch',
            'title' => esc_html__('Reddit Share', 'acumec'),
            'required' => array('single_social_share','=',1),
            'default' => false,
        ),
    )
));

/**
 * 404 option
 * 
 * extra css for customer.
 * @author Fox
 */
Redux::setSection($opt_name, array(
    'title' => esc_html__('404', 'acumec'),
    'icon' => 'el el-exclamation-sign',
    'fields' => array(
        array(
            'title'             => esc_html__('404 image', 'acumec'),
            'id'                => '404_image',
            'type'              => 'media',
            'url'               => true,
        ),
        array(
            'title' => esc_html__('404 title', 'acumec'),
            'id' => 'page_404_title',
            'type' => 'text',
        ),
        array(
            'id' => 'page_404_message',
            'type' => 'textarea',
            'title' => esc_html__('Message', 'acumec'),
            'subtitle' => esc_html__('Add message notify output', 'acumec'),
        ),
        array(
            'subtitle'          => esc_html__('Show Search.', 'acumec'),
            'id'                => 'error_search',
            'type'              => 'switch',
            'title'             => esc_html__('Search', 'acumec'),
            'default'           => true,
        ),
        array(
            'title'             => esc_html__('Is display button?', 'acumec'),
            'id'                => 'page_404_button',
            'type'              => 'switch',
            'default'           => true,
        ), 
        array(
            'title'             => esc_html__('Link button', 'acumec'),
            'id'                => 'link_404_button',
            'type'              => 'text',
            'default'           => "#",
            'required'  => array( 'page_404_button', '=', 1),
        ),
    )
));

/**
 * Styling
 * 
 * css color.
 * @author Fox
 */
Redux::setSection($opt_name, array(
    'title' => esc_html__('Styling', 'acumec'),
    'icon' => 'el-icon-adjust',
    'fields' => array(
        array(
            'subtitle' => esc_html__('Set color default color.', 'acumec'),
            'id' => 'default_color',
            'type' => 'color',
            'title' => esc_html__('Default Color', 'acumec'),
            'default' => '#050508'
        ),
        array(
            'subtitle' => esc_html__('Set color main color.', 'acumec'),
            'id' => 'primary_color',
            'type' => 'color',
            'title' => esc_html__('Primary Color', 'acumec'),
            'default' => '#82d10c'
        ),
        array(
            'subtitle' => esc_html__('Set color main color.', 'acumec'),
            'id' => 'primary_color1',
            'type' => 'color',
            'title' => esc_html__('Primary Color 1', 'acumec'),
            'default' => '#4E81F3'
        ),
        array(
            'subtitle' => esc_html__('Set color main color.', 'acumec'),
            'id' => 'primary_color2',
            'type' => 'color',
            'title' => esc_html__('Primary Color 2', 'acumec'),
            'default' => '#FF5B29'
        ),
        array(
            'subtitle' => esc_html__('Set color secondary color.', 'acumec'),
            'id' => 'second_color',
            'type' => 'color',
            'title' => esc_html__('Secondary Color', 'acumec'),
            'default' => '#4e81f3'
        ),
        
        array(
            'id'       => 'link_color',
            'type'     => 'link_color',
            'title'    => esc_html__( 'Links Color', 'acumec' ),
            'subtitle' => esc_html__( 'Select Links Color Option', 'acumec' ),
            'regular'   => true,
            'hover'     => true,
            'active'    => false,
            'visited'   => false,
            'default'           => array(
                'regular'           => '#0081ff',
                'hover'             => '#82d10c',
            ),
            'output'   => array( 'a' ),
        ),

        array(
            'id'       => 'button_primary_color',
            'type'     => 'link_color',
            'title'    => esc_html__( 'Button Primary Color', 'acumec' ),
            'subtitle' => esc_html__( 'Select Button Primary Color Option', 'acumec' ),
            'regular'   => true,
            'hover'     => true,
            'active'    => false,
            'visited'   => false,
        ),  
        array(
            'id'       => 'button_primary_bg',
            'type'     => 'link_color',
            'title'    => esc_html__( 'Button Primary Background Color', 'acumec' ),
            'subtitle' => esc_html__( 'Select Button Background Primary Color Option', 'acumec' ),
            'regular'   => true,
            'hover'     => true,
            'active'    => false,
            'visited'   => false,
        ),
         array(
            'id'       => 'button_primary_border',
            'type'     => 'link_color',
            'title'    => esc_html__( 'Button Primary Border Color', 'acumec' ),
            'subtitle' => esc_html__( 'Select Button Primary Border Color Option', 'acumec' ),
            'regular'   => true,
            'hover'     => true,
            'active'    => false,
            'visited'   => false,
        ),
        array(
            'id'       => 'button_default_color',
            'type'     => 'link_color',
            'title'    => esc_html__( 'Button Default Color', 'acumec' ),
            'subtitle' => esc_html__( 'Select Button Default Color Option', 'acumec' ),
            'regular'   => true,
            'hover'     => true,
            'active'    => false,
            'visited'   => false,
        ),  
        array(
            'id'       => 'button_default_bg',
            'type'     => 'link_color',
            'title'    => esc_html__( 'Button Default Background Color', 'acumec' ),
            'subtitle' => esc_html__( 'Select Button Default Background Color Option', 'acumec' ),
            'regular'   => true,
            'hover'     => true,
            'active'    => false,
            'visited'   => false,
        ),
        array(
            'id'       => 'button_default_border',
            'type'     => 'link_color',
            'title'    => esc_html__( 'Button Default Border Color', 'acumec' ),
            'subtitle' => esc_html__( 'Select Button Default Border Color Option', 'acumec' ),
            'regular'   => true,
            'hover'     => true,
            'active'    => false,
            'visited'   => false,
        ),
        
    )
));

/**
 * Typography
 * 
 * @author Fox
 */
Redux::setSection($opt_name, array(
    'title' => esc_html__('Typography', 'acumec'),
    'icon' => 'el-icon-text-width',
    'fields' => array(
        array(
            'id' => 'font_body',
            'type' => 'typography',
            'title' => esc_html__('Body Font', 'acumec'),
            'google' => true,
            'font-backup' => true,
            'all_styles' => true,
            'output'  => array('body,body blockquote,body .footer-bottom'),
            'units' => 'px',
            'subtitle' => esc_html__('Typography option with each property can be called individually.', 'acumec')
        ),
        array(
            'id' => 'font_h1',
            'type' => 'typography',
            'title' => esc_html__('H1', 'acumec'),
            'google' => true,
            'font-backup' => true,
            'all_styles' => true,
            'output'  => array('h1'),
            'units' => 'px',
            'subtitle' => esc_html__('Typography option with each property can be called individually.', 'acumec')
        ),
        array(
            'id' => 'font_h2',
            'type' => 'typography',
            'title' => esc_html__('H2', 'acumec'),
            'google' => true,
            'font-backup' => true,
            'all_styles' => true,
            'output'  => array('h2'),
            'units' => 'px',
            'subtitle' => esc_html__('Typography option with each property can be called individually.', 'acumec')
        ),
        array(
            'id' => 'font_h3',
            'type' => 'typography',
            'title' => esc_html__('H3', 'acumec'),
            'google' => true,
            'font-backup' => true,
            'all_styles' => true,
            'output'  => array('h3,.site-content .template-cms_grid--menu h3.entry-title,.cms-counter-wraper .cms-counter-body .cms-counter-single h3'),
            'units' => 'px',
            'subtitle' => esc_html__('Typography option with each property can be called individually.', 'acumec')
        ),
        array(
            'id' => 'font_h4',
            'type' => 'typography',
            'title' => esc_html__('H4', 'acumec'),
            'google' => true,
            'font-backup' => true,
            'all_styles' => true,
            'output'  => array('h4'),
            'units' => 'px',
            'subtitle' => esc_html__('Typography option with each property can be called individually.', 'acumec')
        ),
        array(
            'id' => 'font_h5',
            'type' => 'typography',
            'title' => esc_html__('H5', 'acumec'),
            'google' => true,
            'font-backup' => true,
            'all_styles' => true,
            'output'  => array('h5'),
            'units' => 'px',
            'subtitle' => esc_html__('Typography option with each property can be called individually.', 'acumec')
        ),
        array(
            'id' => 'font_h6',
            'type' => 'typography',
            'title' => esc_html__('H6', 'acumec'),
            'google' => true,
            'font-backup' => true,
            'all_styles' => true,
            'output'  => array('h6'),
            'units' => 'px',
            'subtitle' => esc_html__('Typography option with each property can be called individually.', 'acumec')
        )
    )
));

/* extra font. */
$custom_font_1 = Redux::getOption($opt_name, 'google-font-selector-1');
$custom_font_1 = !empty($custom_font_1) ? explode(',', $custom_font_1) : array();

Redux::setSection($opt_name, array(
    'title' => esc_html__('Extra Fonts', 'acumec'),
    'icon' => 'el el-fontsize',
    'subsection' => true,
    'fields' => array(
        array(
            'id' => 'google-font-1',
            'type' => 'typography',
            'title' => esc_html__('Custom Font', 'acumec'),
            'google' => true,
            'font-backup' => true,
            'all_styles' => true,
            'output'  =>  $custom_font_1,
            'units' => 'px',
            'subtitle' => esc_html__('Typography option with each property can be called individually.', 'acumec'),
            'default' => array(
                'color' => '',
                'font-style' => '',
                'font-weight' => '',
                'font-family' => '',
                'google' => true,
                'font-size' => '',
                'line-height' => '',
                'text-align' => ''
            )
        ),
        array(
            'id' => 'google-font-selector-1',
            'type' => 'textarea',
            'title' => esc_html__('Selector 1', 'acumec'),
            'subtitle' => esc_html__('add html tags ID or class (body,a,.class,#id)', 'acumec'),
            'validate' => 'no_html',
            'default' => '',
        )
    )
));



/**
 * Footer
 *
 * @author Fox
 */
Redux::setSection($opt_name, array(
    'title' => esc_html__('Footer', 'acumec'),
    'icon' => 'el el-website',
    'fields' => array(
        array(
            'title'     => esc_html__('Enable Footer','acumec'),
            'subtitle'  => esc_html__('Enable footer','acumec'),
            'id'        => 'enable_footer',
            'type'      => 'switch',
            'default'   => true
        ),
        array(
            'id'       => 'footer-style',
            'type'     => 'select',
            'title'    => esc_html__( 'Footer Style', 'acumec' ),
            'subtitle' => esc_html__( 'Select footer style', 'acumec' ),
            'default'    => 'layout1',
            'options'  => array(
                'layout1' => esc_html__('Default', 'acumec' ),
                'layout2' => esc_html__('Style 2', 'acumec' ),
            ),
            'required' => array('enable_footer','=',1)
        ),
    )
));

/* Client footer. */
Redux::setSection($opt_name, array(
    'title' => esc_html__(' Client Logo Footer', 'acumec'),
    'icon' => 'el el-minus',
    'subsection' => true,
    'fields' => array(
        array(
            'title'     => esc_html__('Enable','acumec'),
            'subtitle'  => esc_html__('Enable client footer','acumec'),
            'id'        => 'enable_client_footer',
            'type'      => 'switch',
            'default'   => false
        ),
        array(
            'id'                => 'opt_client_logo_background_color',
            'type'              => 'color_rgba',
            'title'             => esc_html__( 'Background color', 'acumec' ),
            'output'   => array(
                'background-color' => '.client-logo-footer'
            ),
            'required'          => array('enable_client_footer','=',1)
        ),
          
        array(
            'title'             => esc_html__('Padding', 'acumec'),
            'subtitle'          => esc_html__('Client logo padding (top/bottom).', 'acumec'),
            'id'                => 'opt_client_logo_padding',
            'type'              => 'spacing',
            'mode'              => 'padding',
            'units'             => array('px'),
            'right'             => false,
            'left'              => false,
            'output'            => array( '.client-logo-footer' ),
            'required'          => array('enable_client_footer','=',1)
        ),
    )
));
/* footer top. */
Redux::setSection($opt_name, array(
    'title' => esc_html__('Footer Top', 'acumec'),
    'icon' => 'el el-minus',
    'subsection' => true,
    'fields' => array(
        array(
            'title'     => esc_html__('Enable','acumec'),
            'subtitle'  => esc_html__('Enable footer top','acumec'),
            'id'        => 'enable_footer_top',
            'type'      => 'switch',
            'default'   => false
        ),
        array(
            'title'     => esc_html__('Full width','acumec'),
            'subtitle'  => esc_html__('Enable full width footer top','acumec'),
            'id'        => 'enable_full_footer_top',
            'type'      => 'switch',
            'default'   => false
        ),
        array(
            'id'       => 'footer-top-column',
            'type'     => 'select',
            'title'    => esc_html__( 'Column', 'acumec' ),
            'subtitle' => esc_html__( 'Select Footer Column', 'acumec' ),
            'default'    => 4,
            'options'  => array(
                1 => esc_html__('1', 'acumec' ),
                2 => esc_html__('2', 'acumec' ),
                3 => esc_html__('3', 'acumec' ),
                4 => esc_html__('4', 'acumec' ),
                5 => esc_html__('5', 'acumec' ),
            ),
            'required'  => array( array('enable_footer_top','=',1),array('footer-style','=','layout1'))
        ), 
        array(
            'id'       => 'footer-top-column-layout2',
            'type'     => 'select',
            'title'    => esc_html__( 'Column', 'acumec' ),
            'subtitle' => esc_html__( 'Select Footer Column', 'acumec' ),
            'default'    => 4,
            'options'  => array(
                1 => esc_html__('1', 'acumec' ),
                2 => esc_html__('2', 'acumec' ),
                3 => esc_html__('3', 'acumec' ),
                4 => esc_html__('4', 'acumec' ),
            ),
            'required'  => array( array('enable_footer_top','=',1),
                                array('footer-style','=','layout2')
                        )
        ),       
        array(
            'id'                => 'footer_top_background_color',
            'type'              => 'color_rgba',
            'title'             => esc_html__( 'Background color', 'acumec' ),
            'output'   => array('background-color' => '#footer-top .bg-overlay'),
            'required'  => array('enable_footer_top','=',1)
        ),
        array(
            'title'             => esc_html__('Background image', 'acumec'),
            'subtitle'          => esc_html__('Footer top background image', 'acumec'),
            'id'                => 'footer_top_background_image',
            'type'              => 'background',
            'background-color'  => false,
            'output'            => array( '#footer-top' ),
            'required'  => array('enable_footer_top','=',1)
        ),
        array(
            'title'             => esc_html__('Padding', 'acumec'),
            'subtitle'          => esc_html__('Footer top padding (top/bottom).', 'acumec'),
            'id'                => 'footer_top_padding',
            'type'              => 'spacing',
            'mode'              => 'padding',
            'units'             => array('px'),
            'right'             => false,
            'left'              => false,
            'output'            => array( '#footer-top' ),
            'required'  => array('enable_footer_top','=',1)
        ),
         array(
            'subtitle' => esc_html__('Title color.', 'acumec'),
            'id' => 'footer_top_title_color',
            'type' => 'color',
            'title' => esc_html__('Title Color', 'acumec'),
            'output'    => array('#footer-top h3.wg-title'),
            'required'  => array('enable_footer_top','=',1)
        ),
        array(
            'subtitle' => esc_html__('Text color.', 'acumec'),
            'id' => 'footer_top_text_color',
            'type' => 'color',
            'title' => esc_html__('Text Color', 'acumec'),
            'output'    => array('#footer-top,#footer-top p,#footer-top .widget.widget_text,#footer-top table caption,#footer-top table th,#footer-top table td'),
            'required'  => array('enable_footer_top','=',1)
        ),
        array(
            'subtitle' => esc_html__('Icon color.', 'acumec'),
            'id' => 'footer_top_icon_color',
            'type' => 'color',
            'title' => esc_html__('Icon Color', 'acumec'),
            'output'    => array('.site-footer.layout2 .footer-top .widget.widget_cs_recent_post_v2 .entry-main .title-recent:before,.site-footer .info-box ul li span.icon-left'),
            'required'  => array(array('enable_footer_top','=',1),array('footer-style','=','layout2'))
        ),
        array(
            'id'       => 'footer_top_link_color',
            'type'     => 'link_color',
            'title'    => esc_html__( 'Links Color', 'acumec' ),
            'subtitle' => esc_html__( 'Select Links Color Option', 'acumec' ),
            'regular'   => true,
            'hover'     => true,
            'active'    => false,
            'visited'   => false,
            'output'   => array( '#footer-top a,.footer-top ul li a,#footer-top widget.widget_nav_menu ul li a,#footer-top table td a' ),
            'required'  => array('enable_footer_top','=',1)
        ),
    )
));

/* footer bottom. */
Redux::setSection($opt_name, array(
    'title' => esc_html__('Footer Bottom', 'acumec'),
    'icon' => 'el el-minus',
    'subsection' => true,
    'fields' => array(
        array(
            'title'     => esc_html__('Enable','acumec'),
            'subtitle'  => esc_html__('Enable footer Bottom','acumec'),
            'id'        => 'enable_footer_bottom',
            'type'      => 'switch',
            'default'   => false
        ),
        array(
            'title'             => esc_html__('Background', 'acumec'),
            'subtitle'          => esc_html__('Footer bottom background.', 'acumec'),
            'id'                => 'footer_bottom_background',
            'type'              => 'background',
            'output'            => array( '#footer-bottom' ),  
            'required'  => array('enable_footer_bottom','=',1) 
        ),
        array(
            'title'             => esc_html__('Padding', 'acumec'),
            'subtitle'          => esc_html__('Footer bottom padding (top/bottom).', 'acumec'),
            'id'                => 'footer_bottom_padding',
            'type'              => 'spacing',
            'mode'              => 'padding',
            'units'             => array('px'),
            'right'             => false,
            'left'              => false,
            'output'            => array( '#footer-bottom' ),
            'required'  => array('enable_footer_bottom','=',1) 
        ),
        array(
            'subtitle' => esc_html__('Title color.', 'acumec'),
            'id' => 'footer_bottom_title_color',
            'type' => 'color',
            'title' => esc_html__('Title Color', 'acumec'),
            'output'    => array('#footer-bottom .wg-title'),
            'required'  => array('enable_footer_bottom','=',1) 
        ),
        array(
            'subtitle' => esc_html__('Text color.', 'acumec'),
            'id' => 'footer_bottom_text_color',
            'type' => 'color',
            'title' => esc_html__('Text Color', 'acumec'),
            'output'    => array('#footer-bottom,#footer-bottom p,#footer-bottom #widget_text,#footer-bottom table caption,#footer-bottom table th,#footer-bottom table td'),
            'required'  => array('enable_footer_bottom','=',1) 
        ),
        array(
            'subtitle' => esc_html__('Border color.', 'acumec'),
            'id' => 'footer_bottom_border_color',
            'type' => 'color',
            'title' => esc_html__('Border Color', 'acumec'),
            'output'    => array('border-color'=>'.footer-bottom'),
            'required'  => array('enable_footer_bottom','=',1) 
        ),
        array(
            'id'       => 'footer_bottom_link_color',
            'type'     => 'link_color',
            'title'    => esc_html__( 'Links Color', 'acumec' ),
            'subtitle' => esc_html__( 'Select Links Color Option', 'acumec' ),
            'regular'   => true,
            'hover'     => true,
            'active'    => false,
            'visited'   => false,
            'output'   => array( '#footer-bottom a,#footer-bottom ul li a,#footer-bottom .widget_nav_menu ul li a,#footer-bottom table td a' ),
            'required'  => array('enable_footer_bottom','=',1) 
        ),
    )
));

/**
 * Shop option
 * 
 * extra css for customer.
 * @author Fox
 */
Redux::setSection($opt_name, array(
    'title' => esc_html__('Woocommerces', 'acumec'),
    'icon' => 'el el-shopping-cart',
    'fields' => array(
       
        array(
            'id'                => 'woo_loop_layout',
            'title'             => esc_html__('Shop catalog layout', 'acumec'),
            'subtitle'          => esc_html__('select a layout for catalog shop page', 'acumec'),
            'default'           => 'full',
            'type'              => 'image_select',
            'options'           => array(
                                        'left' => get_template_directory_uri().'/assets/images/content/right.png',
                                        'full' => get_template_directory_uri().'/assets/images/content/full.png',
                                        'right' => get_template_directory_uri().'/assets/images/content/left.png',
                                    )
        ),
        array(
            'subtitle' => esc_html__('Select catalog product column', 'acumec'),
            'id' => 'shop_columns',
            'type' => 'select',
            'title' => esc_html__('Products Columns', 'acumec'),
            'options'=>array(
                '2'=> esc_html__('2 Columns','acumec'),
                '3'=> esc_html__('3 Columns','acumec'),
            ),
            'default' => '3',
            'required'          => array( 'woo_loop_layout', '=', array('left','right') )
        ),
        array(
            'subtitle' => esc_html__('Select catalog product column', 'acumec'),
            'id' => 'shop_columns_full',
            'type' => 'select',
            'title' => esc_html__('Products Columns', 'acumec'),
            'options'=>array(
                '2'=> esc_html__('2 Columns','acumec'),
                '3'=> esc_html__('3 Columns','acumec'),
                '4'=> esc_html__('4 Columns','acumec'),
            ),
            'default' => '4',
            'required'          => array( 'woo_loop_layout', '=', 'full' )
        ),
        array(
            'subtitle' => esc_html__('Enter the number of products you want to show on catalog layout', 'acumec'),
            'id' => 'shop_products',
            'type' => 'text',
            'title' => esc_html__('Number Product Per Page', 'acumec'),
            'default' => '12',
        ),
        array(
            'id'                => 'shop_loop_product_bg',
            'type'              => 'color_rgba',
            'title'             => esc_html__( ' Product\'s background at archive pages', 'acumec' ),
            'default'   => array(
                'color'     => '#f5f5f5',
                'alpha'     => 1
            ), 
            'output'   => array(
                'background-color' => '.wc-loop-content-wrap'
            ),
        ),
        array(
            'id'                => 'price_bg',
            'type'              => 'color_rgba',
            'title'             => esc_html__( ' Price background', 'acumec' ),
            'output'   => array(
                'background-color' => '.price,.item-price'
            ),
        ),
        array(
            'id'                => 'price_color',
            'type'              => 'color_rgba',
            'title'             => esc_html__( ' Price Color', 'acumec' ), 
            'output'   => array(
                'color' => '.price,.item-price'
            ),
        ),
        array(
            'id'                => 'woo_single_layout',
            'title'             => esc_html__('Product single layout', 'acumec'),
            'subtitle'          => esc_html__('select a layout for single product page', 'acumec'),
            'default'           => 'full',
            'type'              => 'image_select',
            'options'           => array(
                                        'left' => get_template_directory_uri().'/assets/images/content/right.png',
                                        'full' => get_template_directory_uri().'/assets/images/content/full.png',
                                        'right' => get_template_directory_uri().'/assets/images/content/left.png',
                                    )
        ),
          
    )
));
/**
 * GutenBerg
 * 
 * Supported GutenBerg or Not
 * @author Chinh Duong Manh
 * @since 2.2
 */
Redux::setSection($opt_name, array(
    'title'  => esc_html__('Gutenberg', 'acumec'),
    'icon'   => 'el-icon-edit',
    'fields' => array(
        array(
            'id'        => 'gutenberg',
            'title'     => esc_html__('Gutenberg Editor', 'acumec'),
            'type'      => 'button_set',
            'options'   => array(
                ''  =>  esc_html__('Default','acumec'), 
                'disable'  =>  esc_html__('Disable','acumec'),
            ),
            'default'   => 'disable'
        )
    )
));
/**
 * Optimal Core
 * 
 * Optimal options for theme. optimal speed
 * @author Fox
 */
Redux::setSection($opt_name, array(
    'title' => esc_html__('Optimal Core', 'acumec'),
    'icon' => 'el-icon-idea',
    'fields' => array(
        array(
            'subtitle' => esc_html__('no minimize , generate css over time...', 'acumec'),
            'id' => 'dev_mode',
            'type' => 'switch',
            'title' => esc_html__('Dev Mode (not recommended)', 'acumec'),
            'default' => false
        )
    )
));