<?php
vc_remove_param('cms_fancybox_single', 'title');
vc_remove_param('cms_fancybox_single', 'description');
vc_remove_param('cms_fancybox_single', 'content_align');
vc_remove_param('cms_fancybox_single', 'description_item');
vc_remove_param('cms_fancybox_single', 'button_text');
vc_remove_param('cms_fancybox_single', 'button_link');
vc_remove_param('cms_fancybox_single', 'button_type');
vc_remove_param('cms_fancybox_single', 'cms_template');

vc_add_param("cms_fancybox_single", array(
    'type' => 'img',
    'heading' => esc_html__( 'Fancy Style', 'acumec' ),
    'value' => array(
        'style-2' => get_template_directory_uri().'/vc_params/layouts/fancy-style2.png',

    ),
    'param_name' => 'fancy_style',
    "admin_label" => true,
    'description' => esc_html__( 'Select fancybox style', 'acumec' ),
    "group" => esc_html__("Layout", 'acumec'),
    'weight' => 1
));
vc_add_param("cms_fancybox_single", array(
    "type" => "textfield",
    "heading" => esc_html__("Extra Class",'acumec'),
    "param_name" => "class",
    "value" => "",
    "description" => "",
    "group" => esc_html__("Layout", 'acumec'),
    'weight' => 1
));
vc_add_param("cms_fancybox_single", array(
    'type' => 'dropdown',
    'heading' => esc_html__( 'Fancy Options Type', 'acumec' ),
    'value' => array(
        esc_html__( 'Icon', 'acumec' ) => 'option_icon',
        esc_html__( 'Image', 'acumec' ) => 'option_image',
    ),
    'param_name' => 'option_type',
    'dependency' => array(
        'element' => 'fancy_style',
        'value' => array(
            'style-2',
        ),
    ),
    "group" => esc_html__("Fancy Settings", 'acumec')
));
vc_add_param("cms_fancybox_single", array(
    'type' => 'dropdown',
	'heading' => esc_html__( 'Icon library', 'acumec' ),
	'value' => array(
		esc_html__( 'Font Awesome', 'acumec' ) => 'fontawesome',
        esc_html__( 'Flat Icon', 'acumec' ) => 'flaticon',
        esc_html__( 'Food Font 2', 'acumec' ) => 'foodfont2',
		esc_html__( 'Open Iconic', 'acumec' ) => 'openiconic',
		esc_html__( 'Typicons', 'acumec' ) => 'typicons',
		esc_html__( 'Entypo', 'acumec' ) => 'entypo',
		esc_html__( 'Linecons', 'acumec' ) => 'linecons',
		esc_html__( 'P7 Stroke', 'acumec' ) => 'pe7stroke',
		esc_html__( 'RT Icon', 'acumec' ) => 'rticon',
	),
	'param_name' => 'icon_type',
	'description' => esc_html__( 'Select icon library.', 'acumec' ),
    'dependency' => array(
        'element' => 'option_type',
        'value' => array(
            'option_icon',
        ),
    ),
	"group" => esc_html__("Fancy Icon Settings", 'acumec')
));

vc_add_param("cms_fancybox_single", array(
    'type'       => 'iconpicker',
    'heading'    => esc_html__( 'Icon Item', 'acumec' ),
    'param_name' => 'icon_flaticon',
    'value'      => '',
    'settings'   => array(
        'emptyIcon'    => true, 
        'type'         => 'flaticon',
        'iconsPerPage' => 200,  
	),
	'dependency' => array(
        'element' => 'icon_type',
        'value'   => 'flaticon',
	),
	'description' => esc_html__( 'Select icon from library.', 'acumec' ),
    "group" => esc_html__("Fancy Settings", 'acumec')
)); 
vc_add_param("cms_fancybox_single",array(
        'type' => 'iconpicker',
        'heading' => esc_html__( 'Icon Item', 'acumec' ),
        'param_name' => 'icon_foodfont2',
        'value' => '',
        'settings' => array(
            'emptyIcon' => true,  
            'type' => 'foodfont2',
            'iconsPerPage' => 200,  
        ),
        'dependency' => array(
            'element' => 'icon_type',
            'value' => 'foodfont2',
        ),
        'description' => esc_html__( 'Select icon from library.', 'acumec' ),
        "group" => esc_html__("Fancy Settings", 'acumec')
));

vc_add_param("cms_fancybox_single", array(
    "type" => "attach_image",
    "heading" => esc_html__("Image Item",'acumec'),
    "param_name" => "image",
    'dependency' => array(
        'element' => 'option_type',
        'value' => array(
            'option_image',
        ),
    ),
    "group" => esc_html__("Fancy Settings", 'acumec')
));

vc_add_param("cms_fancybox_single", array(
	"type" => "textfield",
    "heading" => esc_html__("Title Item",'acumec'),
    "param_name" => "title_item",
    "value" => "",
    "admin_label" => true,
    "description" => esc_html__("Title Of Item",'acumec'),
    "group" => esc_html__("Option", 'acumec')
));

vc_add_param("cms_fancybox_single", array(
	"type" => "textarea_html",
    "heading" => esc_html__("Content Item",'acumec'),
    "param_name" => "content",
    "value" => "",
    'dependency' => array(
        'element' => 'fancy_style',
        'value' => array(
            'style-2',
        ),
    ),
    "group" => esc_html__("Option", 'acumec')
));

vc_add_param("cms_fancybox_single", array(
	'type' => 'vc_link',
    'heading' => esc_html__( 'URL (Link)', 'acumec' ),
    'param_name' => 'link',
    'description' => esc_html__( 'Add link to button.', 'acumec' ),
    'dependency' => array(
        'element' => 'fancy_style',
        'value' => array(
            'style-2',
        ),
    ),
    'group' => esc_html__("Option", 'acumec'),
));

vc_add_param("cms_fancybox_single", array(
    "type" => "textfield",
    "class" => "",
    "heading" => esc_html__("Icon Font Size", 'acumec'),
    "param_name" => "icon_size",
    "value" => "",
    'dependency' => array(
        'element' => 'option_type',
        'value' => array('option_icon'),
    ),
    'group' => esc_html__("Fancy Settings", 'acumec'),
));

vc_add_param("cms_fancybox_single", array(
    "type" => "colorpicker",
    "class" => "",
    "heading" => esc_html__("Background Icon Color (For Icon be selected)", 'acumec'),
    "param_name" => "bg_icon",
    "value" => "",
    'dependency' => array(
        'element' => 'option_type',
        'value' => array('option_icon'),
    ),
    'group' => esc_html__("Fancy Settings", 'acumec'),
));
vc_add_param("cms_fancybox_single", array(
    "type" => "colorpicker",
    "class" => "",
    "heading" => esc_html__("Icon Color (For Icon be selected)", 'acumec'),
    "param_name" => "color_icon",
    "value" => "",
    'dependency' => array(
        'element' => 'option_type',
        'value' => array('option_icon'),
    ),
    'group' => esc_html__("Fancy Settings", 'acumec'),
));

vc_add_param("cms_fancybox_single", array(
    "type" => "colorpicker",
    "class" => "",
    "heading" => esc_html__("Text Color", 'acumec'),
    "param_name" => "text_color",
    "value" => "",
    'dependency' => array(
        'element' => 'fancy_style',
        'value' => array(
            'style-2',
        ),
    ),
    'group' => esc_html__("Option", 'acumec'),
));
vc_add_param("cms_fancybox_single", array(
    "type" => "textfield",
    "class" => "",
    "heading" => esc_html__("Title Font Size", 'acumec'),
    "param_name" => "title_size",
    "value" => "",
    'group' => esc_html__("Option", 'acumec'),
));
vc_add_param("cms_fancybox_single", array(
    "type" => "textfield",
    "class" => "",
    "heading" => esc_html__("Title Line Height", 'acumec'),
    "param_name" => "title_height",
    "value" => "",
    'group' => esc_html__("Option", 'acumec'),
));
vc_add_param("cms_fancybox_single", array(
    "type" => "textfield",
    "class" => "",
    "heading" => esc_html__("Content Font Size", 'acumec'),
    "param_name" => "content_size",
    "value" => "",
    'group' => esc_html__("Option", 'acumec'),
));
vc_add_param("cms_fancybox_single", array(
    "type" => "textfield",
    "class" => "",
    "heading" => esc_html__("Content Line Height", 'acumec'),
    "param_name" => "content_height",
    "value" => "",
    'group' => esc_html__("Option", 'acumec'),
));
vc_add_param("cms_fancybox_single", array(
    "type" => "textfield",
    "class" => "",
    "heading" => esc_html__("Content Font style", 'acumec'),
    "param_name" => "content_style",
    "value" => "",
    'group' => esc_html__("Option", 'acumec'),
)); 
vc_add_param('cms_fancybox_single', array(
    'type' => 'checkbox',
    'heading' => esc_html__("Border", 'acumec'),
    'param_name' => 'border',
    'value' => array(
            'Yes' => true
        ),
    'std' => false,
    'dependency' => array(
        'element' => 'fancy_style',
        'value' => array(
            'style-2',
        ),
    ),
    'group' => esc_html__("Option", 'acumec'),
)); 


vc_add_param("cms_fancybox_single", array(
    'type' => 'dropdown',
    'heading' => esc_html__( 'Animation', 'acumec' ),
    'param_name' => 'animation_effect',
    'std' => '',
    'description' => esc_html__( 'Animations  for grid', 'acumec' ),
    'value' =>  array(
        esc_html__( 'None', 'acumec' ) => '',
        esc_html__( 'fadeIn', 'acumec' ) => 'wow fadeIn',
        esc_html__( 'FadeInUp', 'acumec' ) => 'wow fadeInUp',
        esc_html__( 'BounceInUp', 'acumec' ) => 'wow bounceInUp',
        esc_html__( 'BounceInDown', 'acumec' ) => 'wow bounceInDown',
        esc_html__( 'BounceInLeft', 'acumec' ) => 'wow bounceInLeft',
        esc_html__( 'BounceInRight', 'acumec' ) => 'wow bounceInRight',  
     ),
     "group" => esc_html__("Animation", 'acumec'),
     'weight' => 1
));
vc_add_param("cms_fancybox_single", array(
    "type" => "dropdown",
    "class" => "",
    "heading" => esc_html__("Data duration", 'acumec'),
    "param_name" => "data_wow_duration",
    "value" =>  array(
        'None'  => '',
        '1s'    => '1s',
        '2s'    => '2s',
        '3s'    => '3s',
        '4s'    => '4s',
        '5s'    => '5s',
        '6s'    => '6s',
    ),
    "group" => esc_html__("Animation", 'acumec'),
    'weight' => 1
));
vc_add_param("cms_fancybox_single", array(
    "type" => "dropdown",
    "class" => "",
    "heading" => esc_html__("Data delay", 'acumec'),
    "param_name" => "data_wow_delay",
    "value" =>  array(
        'None'  => '',
        '0.2s'    => '0.2s',
        '0.4s'    => '0.4s',
        '0.6s'    => '0.6s',
        '0.8s'    => '0.8s',
    ),
    "group" => esc_html__("Animation", 'acumec'),
    'weight' => 1
));
vc_add_param("cms_fancybox_single", array(
	'type' => 'css_editor',
    'heading' => esc_html__( 'CSS box', 'acumec' ),
    'param_name' => 'css',
    'group' => esc_html__( 'Design Options', 'acumec' ),
));
 