<?php
/**
 * Meta box config file
 */
if (! class_exists('MetaFramework')) {
    return;
}

$args = array(
    // TYPICAL -> Change these values as you need/desire
    'opt_name' => apply_filters('opt_meta', 'opt_meta_options'),
    // Set a different name for your global variable other than the opt_name
    'dev_mode' => false,
    // Allow you to start the panel in an expanded way initially.
    'open_expanded' => false,
    // Disable the save warning when a user changes a field
    'disable_save_warn' => true,
    // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
    'save_defaults' => false,

    'output' => false,
    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
    'output_tag' => false,
    // Show the time the page took to load, etc
    'update_notice' => false,
    // 'disable_google_fonts_link' => true, // Disable this in case you want to create your own google fonts loader
    'admin_bar' => false,
    // Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
    'allow_sub_menu' => false,
    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
    'customizer' => false,
    // What to print by the field's title if the value shown is default. Suggested: *
    'show_import_export' => false,
    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
    'use_cdn' => false,
    // save meta to multiple keys.
    'meta_mode' => 'multiple'
);

// -> Set Option To Panel.
MetaFramework::setArgs($args);

add_action('admin_init', 'acumec_meta_boxs');

MetaFramework::init();

function acumec_meta_boxs()
{

    /** page options */
    MetaFramework::setMetabox(array(
        'id' => '_page_main_options',
        'label' => esc_html__('Page Setting', 'acumec'),
        'post_type' => 'page',
        'context' => 'advanced',
        'priority' => 'default',
        'open_expanded' => false,
        'sections' => array(
            array(
                'title' => esc_html__('General', 'acumec'),
                'id' => 'tab-page-general',
                'icon' => 'el el-credit-card',
                'fields' => array(
                    array(
                        'title'     => esc_html__('Boxed Layout', 'acumec'),
                        'subtitle'  => esc_html__('make your site is boxed?', 'acumec'),
                        'id'        => 'opt_general_layout',
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
                        'required'  => array( 'opt_general_layout', '=', 1),
                    ), 
                    array(
                        'title'             => esc_html__('Body Background', 'acumec'),
                        'id'                => 'opt_general_background',
                        'type'              => 'background',
                        'preview'           => false,
                        'required'  => array( 'opt_general_layout', '=', 1),
                    ),  
                    array(
                        'title'             => esc_html__('Content Background', 'acumec'),
                        'id'                => 'opt_content_background',
                        'type'              => 'background',
                        'preview'           => false,
                        'required'  => array( 'opt_general_layout', '=', 1),
                    ),   
                )
            ), 

            /* header */
            array(
                'title' => esc_html__('Header', 'acumec'),
                'id' => 'tab-page-header',
                'icon' => 'el el-credit-card',
                'fields' => array(
                    array(
                        'title'     => esc_html__('Enable', 'acumec'),
                        'id'        => 'enable_header',
                        'type'      => 'switch',
                        'default'   => true,
                    ),
                    array(
                        'id' => 'header_layout',
                        'title' => esc_html__('Layouts', 'acumec'),
                        'type' => 'image_select',
                        'options' => array(
                            ''        => get_template_directory_uri() . '/assets/images/header/h-default.png',
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
                        'required'  => array(   array('header_layout', '=', array('layout2','layout3'))
                                            ),
                    ),
                    array(
                        'subtitle'          => esc_html__('enable header middle full width.', 'acumec'),
                        'id'                => 'header_middle_full_width',
                        'type'              => 'switch',
                        'title'             => esc_html__('Header Middle Full Width', 'acumec'),
                        'default'           => false,  
                        'required'  => array(   array('header_layout', '=', array('default')),
                                            ),
                    ),
                    array(
                        'subtitle'          => esc_html__('enable transparent mode for menu.', 'acumec'),
                        'id'                => 'menu_transparent',
                        'type'              => 'switch',
                        'title'             => esc_html__('Transparent Header', 'acumec'),
                        'default'           => false,  
                        'required'  => array( 'header_layout', '=', array('default','layout2','layout3')),
                    ),  
                    array(
                        'subtitle'          => esc_html__('Border Bottom.', 'acumec'),
                        'id'                => 'border_bottom',
                        'type'              => 'switch',
                        'title'             => esc_html__('Border Bottom Header', 'acumec'),
                        'default'           => false,
                        'required'  => array( 'header_layout', '=', array('default','layout2','layout3')),
                    ),
                    array(
                        'id' => 'header_menu',
                        'type' => 'select',
                        'title' => esc_html__('Select Menu', 'acumec'),
                        'subtitle' => esc_html__('custom menu for current page', 'acumec'),
                        'options' => acumec_get_nav_menu(),
                        'default' => '',
                        'required'  => array( 'enable_header', '=', 1),
                    ),
                )
            ),

            /* Header Top */
            array(
                'title' => esc_html__('Header Top', 'acumec'),
                'id' => 'tab-page-header-top',
                'icon' => 'el el-credit-card',
                'fields' => array(     
                    array(
                        'title' => esc_html__('Note: Not apply for header layout default.', 'acumec'),
                        'id'   => 'hdt-panel',
                        'type' => 'info',
                        'style' => 'warning',
                        'required'  => array( 'header_layout', '=','' ), 
                    ),
                    array(
                        'title'     => esc_html__('Enable', 'acumec'),
                        'id'        => 'enable_header_top',
                        'type'      => 'switch',
                        'default'   => true,
                        'required'  =>   array('header_layout', '=', array('default','layout2','layout3') ),
                    ),
                ),
            ),

            /* Page Title */
            array(
                'title' => esc_html__('Page Title & BC', 'acumec'),
                'id' => 'tab-page-title-bc',
                'icon' => 'el el-map-marker',
                'fields' => array(
                    array(
                        'id' => 'page_title_enable',
                        'type' => 'switch',
                        'title' => esc_html__('Enable Page Title', 'acumec'),
                        'subtitle' => esc_html__('On /Off page title on this page.', 'acumec'),
                         'default' => true 
                    ),                   
                    array(
                        'id' => 'page_title_layout',
                        'title' => esc_html__('Layouts', 'acumec'),
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
                        'required'  => array('page_title_enable', '=', 1),
                    ),

                    array(
                        'id'                => 'page_title_background_color',
                        'type'              => 'color_rgba',
                        'title'             => esc_html__( 'Page Title Background color', 'acumec' ),
                        'required'  =>   array( 'single_page_title_layout', '=', array('2','3','4','5','6','7')),
                    ),
                    array(
                        'title'             => esc_html__('Background image', 'acumec'),
                        'subtitle'          => esc_html__('Page title background image.', 'acumec'),
                        'id'                => 'page_title_background_image',
                        'type'              => 'background',
                        'preview'           => true,
                        'background-color'  => false,
                        'required'  =>   array( 'single_page_title_layout', '=', array('2','3','4','5','6','7')),
                    ),
                    array(
                        'id' => 'page_title_text',
                        'type' => 'text',
                        'title' => esc_html__('Custom Title', 'acumec'),
                        'subtitle' => esc_html__('Custom current page title.', 'acumec'),
                        'required'  => array( 'page_title_enable', '=', 1),
                    )
                )
            ),

            /* Content */
            array(
                'title' => esc_html__('Content', 'acumec'),
                'id' => 'tab-content',
                'icon' => 'el el-pencil',
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
                    ),
                    
                )
            ),

            /* Styling */
            array(
                'title' => esc_html__('Styling', 'acumec'),
                'id' => 'tab-styling',
                'icon' => 'el-icon-adjust',
                'fields' => array(
                    array(
                        'id'       => 'primary_color_style',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Primary Color Style', 'acumec' ),
                        'subtitle' => esc_html__( 'Select primary color style', 'acumec' ),
                        'options'  => array(
                            '' => esc_html__('Default','acumec'),
                            'primary_color' => esc_html__('Primary Color', 'acumec' ),
                            'primary_color1' => esc_html__('Primary Color 1', 'acumec' ),
                            'primary_color2' => esc_html__('Primary Color 2', 'acumec' ),
                        ),
                        'required' => array('enable_footer','=',1)
                    ),                 
                )
            ),

            /* Footer */
            array(
                'title' => esc_html__('Footer ', 'acumec'),
                'id' => 'tab-page-title-bc',
                'icon' => 'el el-map-marker',
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
                        'default'    => '',
                        'options'  => array(
                            ''        => '',
                            'layout1' => esc_html__('Default', 'acumec' ),
                            'layout2' => esc_html__('Style 2', 'acumec' ),
                        ),

                        'required' => array('enable_footer','=',1)
                    ),
                    array(
                        'id' => 'enable_client_footer',
                        'type' => 'switch',
                        'title' => esc_html__('Enable Client Logo Footer', 'acumec'),
                        'subtitle' => esc_html__('On /Off client logo footer on this page .', 'acumec'),
                        'default' => true,
                        'required'  => array( 'enable_footer', '=', 1), 
                    ),
                    array(
                        'id' => 'enable_footer_top',
                        'type' => 'switch',
                        'title' => esc_html__('Enable Footer Top', 'acumec'),
                        'subtitle' => esc_html__('On /Off footer top on this page .', 'acumec'),
                        'default' => true,
                        'required'  => array( 'enable_footer', '=', 1),
                    ),
                    array(
                        'id' => 'enable_footer_bottom',
                        'type' => 'switch',
                        'title' => esc_html__('Enable Footer Bottom', 'acumec'),
                        'subtitle' => esc_html__('On /Off footer bottom on this page .', 'acumec'),
                        'default' => false,
                        'required'  => array( 'enable_footer', '=', 1),
                    ),
                )
            ),

            /* Revo Slide */
            array(
                'title' => esc_html__('Revolution Setting', 'acumec'),
                'id' => 'tab-revo',
                'icon' => 'el el-pencil',
                'fields' => array(
                    array(
                        'id' => 'header_revo',
                        'type' => 'select',
                        'title' => esc_html__('Select Revolution Slide', 'acumec'),
                        'subtitle' => esc_html__('custom revolution slide for current page', 'acumec'),
                        'options' => acumec_get_list_rev_slider(),
                        'default' => '',
                    ), 
                    
                )
            ),

        )           
    ));

     /** Project options */
    MetaFramework::setMetabox(array(
        'id' => '_page_project_format_options',
        'label' => esc_html__('Project Option', 'acumec'),
        'post_type' => 'project',
        'context' => 'advanced',
        'priority' => 'default',
        'open_expanded' => true,
        'sections' => array(
            array(
                'title' => '',
                'id' => 'color-Color',
                'icon' => 'el el-laptop',
                'fields' => array(
                    array(
                        'id' => 'project_description',
                        'type' => 'textarea',
                        'title' => esc_html__('Project Description', 'acumec'),
                    ),
                    array(
                        'id' => 'project_client_title',
                        'type' => 'text',
                        'title' => esc_html__('Client Title', 'acumec'),
                        'subtitle' => esc_html__('Project client title', 'acumec'),
                    ),
                    array(
                        'id' => 'project_client_image',
                        'type' => 'media',
                        'url'  => true,
                        'title' => esc_html__('Client Avatar', 'acumec'),
                        'subtitle' => esc_html__('Project client avatar', 'acumec'),
                    ),
                    array(
                        'id' => 'project_client_name',
                        'type' => 'text',
                        'title' => esc_html__('Client Name', 'acumec'),
                        'subtitle' => esc_html__('Project client name', 'acumec'),
                    ),
                    array(
                        'id' => 'project_client_position',
                        'type' => 'text',
                        'title' => esc_html__('Client Position', 'acumec'),
                        'subtitle' => esc_html__('Project client position', 'acumec'),
                    ),

                    array(
                        'id' => 'project_link_demo',
                        'type' => 'text',
                        'title' => esc_html__('Project Link Demo', 'acumec'),
                    ),
                    array(
                        'id' => 'project_link_launch',
                        'type' => 'text',
                        'title' => esc_html__('Project Link Launch', 'acumec'),
                    ),
                    array(
                        'id' => 'project_link_title',
                        'type' => 'text',
                        'title' => esc_html__('Project Link Title', 'acumec'),
                        'default' => esc_html__('LAUNCH PROJECT','acumec')
                    ),
                    array(
                        'id' => 'project_category',
                        'type' => 'text',
                        'title' => esc_html__('Project Category', 'acumec'),
                    ),
                    array(
                        'id' => 'project_start_date',
                        'type' => 'text',
                        'title' => esc_html__('Start Date', 'acumec'),
                        'subtitle' => esc_html__('Project Start Date', 'acumec'),
                    ),
                    array(
                        'id' => 'project_end_date',
                        'type' => 'text',
                        'title' => esc_html__('End Date', 'acumec'),
                        'subtitle' => esc_html__('Project End Date', 'acumec'),
                    ),
                )
            ),
             
        )
    ));

    /** Team options */
    MetaFramework::setMetabox(array(
        'id' => '_page_team_format_options',
        'label' => esc_html__('Team Option', 'acumec'),
        'post_type' => 'team',
        'context' => 'advanced',
        'priority' => 'default',
        'open_expanded' => true,
        'sections' => array(
            array(
                'title' => '',
                'id' => 'color-Color',
                'icon' => 'el el-laptop',
                'fields' => array(
                    array(
                        'id' => 'team_position',
                        'type' => 'text',
                        'title' => esc_html__('Team Position', 'acumec'),
                        'subtitle' => esc_html__('team position ( Manager, Developer, ... )', 'acumec'),
                    ),
                    array(
                        'id' => 'team_description',
                        'type' => 'textarea',
                        'title' => esc_html__('Team Description', 'acumec'),
                    ),                   
                    array(
                        'id' => 'team_email',
                        'type' => 'text',
                        'title' => esc_html__('Email Address', 'acumec'),
                    ),
                    array(
                        'id' => 'team_phone',
                        'type' => 'text',
                        'title' => esc_html__('Phone No', 'acumec'),
                    ),
                    array(
                        'id' => 'team_location',
                        'type' => 'text',
                        'title' => esc_html__('Location', 'acumec'),
                    ),
                    array(
                        'id' => 'team_link_title',
                        'type' => 'text',
                        'title' => esc_html__('Button Title', 'acumec'),
                    ),
                    array(
                        'id' => 'team_link',
                        'type' => 'text',
                        'title' => esc_html__('Button Link', 'acumec'),
                    ), 
                    array(
                        'id' => 'team_link_icon',
                        'type' => 'text',
                        'title' => esc_html__('Button Icon', 'acumec'),
                        'subtitle' => esc_html__('Icon fontawesome ( fa fa-facebook, fa fa-twitter, ... )', 'acumec'),
                    ), 
                    array(
                        'title'     => esc_html__('Show  Social', 'acumec'),
                        'id'        => 'social_enable',
                        'type'      => 'switch',
                        'default'   => true
                    ),
                    array(
                        'id' => 'team_social_icon_1',
                        'type' => 'text',
                        'title' => esc_html__('Icon Social 1', 'acumec'),
                        'subtitle' => esc_html__('Icon fontawesome ( fa fa-facebook, fa fa-twitter, ... )', 'acumec'),
                        'required'  => array( 'social_enable', '=', 1),
                    ),
                    array(
                        'id' => 'team_social_1',
                        'type' => 'text',
                        'title' => esc_html__('Social 1', 'acumec'),
                        'subtitle' => esc_html__('Link social', 'acumec'),
                        'required'  => array( 'social_enable', '=', 1),
                    ),
                    array(
                        'id' => 'team_social_icon_2',
                        'type' => 'text',
                        'title' => esc_html__('Icon Social 2', 'acumec'),
                        'subtitle' => esc_html__('Icon fontawesome ( fa fa-facebook, fa fa-twitter, ... )', 'acumec'),
                        'required'  => array( 'social_enable', '=', 1),
                    ),
                    array(
                        'id' => 'team_social_2',
                        'type' => 'text',
                        'title' => esc_html__('Social 2', 'acumec'),
                        'subtitle' => esc_html__('Link social', 'acumec'),
                        'required'  => array( 'social_enable', '=', 1),
                    ),
                    array(
                        'id' => 'team_social_icon_3',
                        'type' => 'text',
                        'title' => esc_html__('Icon Social 3', 'acumec'),
                        'subtitle' => esc_html__('Icon fontawesome ( fa fa-facebook, fa fa-twitter, ... )', 'acumec'),
                        'required'  => array( 'social_enable', '=', 1),
                    ),
                    array(
                        'id' => 'team_social_3',
                        'type' => 'text',
                        'title' => esc_html__('Social 3', 'acumec'),
                        'subtitle' => esc_html__('Link social', 'acumec'),
                        'required'  => array( 'social_enable', '=', 1),
                    ),
                    array(
                        'id' => 'team_social_icon_4',
                        'type' => 'text',
                        'title' => esc_html__('Icon Social 4', 'acumec'),
                        'subtitle' => esc_html__('Icon fontawesome ( fa fa-facebook, fa fa-twitter, ... )', 'acumec'),
                        'required'  => array( 'social_enable', '=', 1),
                    ),
                    array(
                        'id' => 'team_social_4',
                        'type' => 'text',
                        'title' => esc_html__('Social 4', 'acumec'),
                        'subtitle' => esc_html__('Link social', 'acumec'),
                        'required'  => array( 'social_enable', '=', 1),
                    ),
                )
            ),
             
        )
    ));

    /** Service options */
    MetaFramework::setMetabox(array(
        'id' => '_page_services_format_options',
        'label' => esc_html__('Service Option', 'acumec'),
        'post_type' => 'Service',
        'context' => 'advanced',
        'priority' => 'default',
        'open_expanded' => true,
        'sections' => array(
            array(
                'title' => '',
                'id' => 'color-Color',
                'icon' => 'el el-laptop',
                'fields' => array(
                    array(
                        'id' => 'service_type',
                        'type' => 'text',
                        'title' => esc_html__('Service Type', 'acumec'),
                        'subtitle' => esc_html__('Service type ( Business Service, ... )', 'acumec'),
                    ),
                    array(
                        'id' => 'service_image',
                        'type' => 'media',
                        'url'  => true,
                        'title' => esc_html__('Service Image', 'acumec'),
                    ),    
                    array(
                        'id'                => 'service_background',
                        'type'              => 'color_rgba',
                        'title'             => esc_html__( 'Service Background', 'acumec' ),
                    ), 
                                    
                    array(
                        'title'     => esc_html__('Show List', 'acumec'),
                        'id'        => 'list_enable',
                        'type'      => 'switch',
                        'default'   => true
                    ),
                    array(
                        'id' => 'service_item_1',
                        'type' => 'text',
                        'title' => esc_html__('List Item 1', 'acumec'),
                        'required'  => array( 'list_enable', '=', 1),
                    ),
                    array(
                        'id' => 'service_item_2',
                        'type' => 'text',
                        'title' => esc_html__('List Item 2', 'acumec'),
                        'required'  => array( 'list_enable', '=', 1),
                    ),
                    array(
                        'id' => 'service_item_3',
                        'type' => 'text',
                        'title' => esc_html__('List Item 3', 'acumec'),
                        'required'  => array( 'list_enable', '=', 1),
                    ),
                    array(
                        'id' => 'service_item_4',
                        'type' => 'text',
                        'title' => esc_html__('List Item 4', 'acumec'),
                        'required'  => array( 'list_enable', '=', 1),
                    ),
                    array(
                        'id' => 'service_item_5',
                        'type' => 'text',
                        'title' => esc_html__('List Item 5', 'acumec'),
                        'required'  => array( 'list_enable', '=', 1),
                    ),
                )
            ),
             
        )
    ));

    /** Case Studies options */
    MetaFramework::setMetabox(array(
        'id' => '_page_case_studies_format_options',
        'label' => esc_html__('Case Studies Option', 'acumec'),
        'post_type' => 'case_studies',
        'context' => 'advanced',
        'priority' => 'default',
        'open_expanded' => true,
        'sections' => array(
            array(
                'title' => '',
                'id' => 'color-Color',
                'icon' => 'el el-laptop',
                'fields' => array(
                    array(
                        'id' => 'case_subttile',
                        'type' => 'text',
                        'title' => esc_html__('Subtitle', 'acumec'),
                    ),
                )
            ),
             
        )
    ));
    /** post options */
    MetaFramework::setMetabox(array(
        'id' => '_page_post_format_options',
        'label' => esc_html__('Post Format', 'acumec'),
        'post_type' => 'post',
        'context' => 'advanced',
        'priority' => 'default',
        'open_expanded' => true,
        'sections' => array(
            array(
                'title' => '',
                'id' => 'color-Color',
                'icon' => 'el el-laptop',
                'fields' => array(
                    array(
                        'id' => 'opt-subtitle',
                        'type' => 'text',
                        'title' => esc_html__('Sub Title', 'acumec'),
                    ),
                    array(
                        'id' => 'opt-video-type',
                        'type' => 'select',
                        'title' => esc_html__('Select Video Type', 'acumec'),
                        'subtitle' => esc_html__('Local video, Youtube, Vimeo', 'acumec'),
                        'options' => array(
                            'local' => esc_html__('Upload', 'acumec'),
                            'youtube' => esc_html__('Youtube', 'acumec'),
                            'vimeo' => esc_html__('Vimeo', 'acumec'),
                        )
                    ),
                    array(
                        'id' => 'otp-video-local',
                        'type' => 'media',
                        'url' => true,
                        'mode' => false,
                        'title' => esc_html__('Local Video', 'acumec'),
                        'subtitle' => esc_html__('Upload video media using the WordPress native uploader', 'acumec'),
                        'required' => array('opt-video-type', '=', 'local')
                    ),
                    array(
                        'id' => 'opt-video-youtube',
                        'type' => 'text',
                        'title' => esc_html__('Youtube', 'acumec'),
                        'subtitle' => esc_html__('Load video from Youtube.', 'acumec'),
                        'placeholder' => esc_html__('https://youtu.be/iNJdPyoqt8U', 'acumec'),
                        'required' => array('opt-video-type', '=', 'youtube')
                    ),
                    array(
                        'id' => 'opt-video-vimeo',
                        'type' => 'text',
                        'title' => esc_html__('Vimeo', 'acumec'),
                        'subtitle' => esc_html__('Load video from Vimeo.', 'acumec'),
                        'placeholder' => esc_html__('https://vimeo.com/155673893', 'acumec'),
                        'required' => array('opt-video-type', '=', 'vimeo')
                    ),
                    array(
                        'id' => 'otp-video-thumb',
                        'type' => 'media',
                        'url' => true,
                        'mode' => false,
                        'title' => esc_html__('Video Thumb', 'acumec'),
                        'subtitle' => esc_html__('Upload thumb media using the WordPress native uploader', 'acumec'),
                        'required' => array('opt-video-type', '=', 'local')
                    ),
                    array(
                        'id' => 'otp-audio',
                        'type' => 'media',
                        'url' => true,
                        'mode' => false,
                        'title' => esc_html__('Audio Media', 'acumec'),
                        'subtitle' => esc_html__('Upload audio media using the WordPress native uploader', 'acumec'),
                    ),
                    array(
                        'id' => 'opt-gallery',
                        'type' => 'gallery',
                        'title' => esc_html__('Add/Edit Gallery', 'acumec'),
                        'subtitle' => esc_html__('Create a new Gallery by selecting existing or uploading new images using the WordPress native uploader', 'acumec'),
                    ),
                    array(
                        'id' => 'opt-quote-title',
                        'type' => 'text',
                        'title' => esc_html__('Quote Title', 'acumec'),
                        'subtitle' => esc_html__('Quote title or quote name...', 'acumec'),
                    ),
                    array(
                        'id' => 'opt-quote-sub-title',
                        'type' => 'text',
                        'title' => esc_html__('Quote Sub Title', 'acumec'),
                        'subtitle' => esc_html__('Quote sub title or quote position...', 'acumec'),
                    ),
                    array(
                        'id' => 'opt-quote-content',
                        'type' => 'textarea',
                        'title' => esc_html__('Quote Content', 'acumec'),
                    ),
                      array(
                        'id' => 'opt-status',
                        'type' => 'media',
                        'url' => true,
                        'mode' => false,
                        'title' => esc_html__('Add/Edit Status image', 'acumec'),
                        'subtitle' => esc_html__('uploading new images using the WordPress native uploader', 'acumec'),
                    ),
                )
            ),
        )
    ));
}
