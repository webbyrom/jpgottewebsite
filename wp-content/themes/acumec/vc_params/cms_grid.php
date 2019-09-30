<?php
 
$params = array( 

    array(
        'type' => 'checkbox',
        'heading' => esc_html__("Show Read more", 'acumec'),
        'param_name' => 'show_readmore',
        'value' => array(
            'Yes' => true
        ),
        'std' => true,
        'template' => array('cms_grid.php'),
    ),
    array(
        'type' => 'checkbox',
        'heading' => esc_html__("Show Load More", 'acumec'),
        'param_name' => 'show_more',
        'value' => array(
            'Yes' => true
        ),
        'std' => false,
    ),
    array(
        'type'          => 'textfield',
        'heading'       => esc_html__("Image Size", 'acumec'),
        'param_name'    => 'image_size',
        'description'   => esc_html__('Image size ( 770x458, 1170x700,...)','acumec'),
        'value'         => '',
        'template'      => array('cms_grid--blog2.php'),
    ),
    array(
        "type" => "textfield",
        "heading" => esc_html__("Image Size",'acumec'),
        "param_name" => "images_size",
        "value" => "",
        "description" => esc_html__("Image size for each item separate by comma (500x350,450x400,...)",'acumec'),
        "template"      => array('cms_grid--projects.php') ,
    ),
    array(
        'type' => 'checkbox',
        'heading' => esc_html__("Show button full image", 'acumec'),
        'param_name' => 'show_btn_full',
        'value' => array(
            'Yes' => true
        ),
        'std' => true,
        "template"      => array('cms_grid--projects.php')
    ), 
    array(
        'type' => 'checkbox',
        'heading' => esc_html__("Show button link", 'acumec'),
        'param_name' => 'show_btn_link',
        'value' => array(
            'Yes' => true
        ),
        'std' => true,
        "template"      => array('cms_grid--projects.php')
    ), 
    array(
        "type" => "textfield",
        "heading" => esc_html__("Spacing Image (px)",'acumec'),
        "param_name" => "bottom",
        "value" => "",
        "description" => "",
        "template"      => array('cms_grid--projects.php') ,
    ),
    array(
        "type"       => "colorpicker",
        "class"      => "",
        "heading"    => esc_html__("Post Archive Color", 'acumec'),
        "param_name" => "archive_color",
        "value"      => "",
        'template' => array('cms_grid--case_studies.php'),
    ),
    array(
        "type" => "textfield",
        "heading" => esc_html__("Font Size Description",'acumec'),
        "param_name" => "f_size",
        "value" => "",
        "template"      => array('cms_grid--service3.php') ,
    ),
    array(
        "type" => "textfield",
        "heading" => esc_html__("Line Height Description",'acumec'),
        "param_name" => "l_height",
        "value" => "",
        "template"      => array('cms_grid--service3.php') ,
    ),
    array(
        'type' => 'checkbox',
        'heading' => esc_html__("Show Button", 'acumec'),
        'param_name' => 'show_button',
        'value' => array(
            'Yes' => true
        ),
        'std' => true,
        'template' => array('cms_grid--team1.php','cms_grid--team2.php'),
    ), 
    array(
        "type"       => "colorpicker",
        "heading"    => esc_html__("Background Ovelay Color", 'acumec'),
        "param_name" => "bg_overlay",
        "value"      => "",
        'template' => array('cms_grid--team1.php','cms_grid--team2.php', 'cms_grid--projects.php'),
    ),
    array(
        'type' => 'checkbox',
        'heading' => esc_html__("Show Categories", 'acumec'),
        'param_name' => 'show_categories',
        'value' => array(
            'Yes' => true
        ),
        'std' => false,
        'template' => array('cms_grid--case_studies.php','cms_grid--blog2.php'),
    ),

    array(
        'type' => 'checkbox',
        'heading' => esc_html__("Show Description", 'acumec'),
        'param_name' => 'show_description',
        'value' => array(
            'Yes' => true
        ),
        'std' => false,
        'template' => array('cms_grid--case_studies.php'),
    ), 
    array(
        "type"       => "colorpicker",
        "heading"    => esc_html__("Text Color", 'acumec'),
        "param_name" => "title_color",
        "value"      => "",
        'template' => array('cms_grid--team1.php','cms_grid--team2.php'),
    ),
    array(
        "type"       => "colorpicker",
        "heading"    => esc_html__("Text Color", 'acumec'),
        "param_name" => "t_color",
        "value"      => "",
        'template' => array('cms_grid--projects.php'),
    ),
    array(
        "type"       => "colorpicker",
        "heading"    => esc_html__("Background button color", 'acumec'),
        "param_name" => "bg_button",
        "value"      => "",
        'template' => array('cms_grid--projects.php'),
    ),
    array(
        "type"       => "colorpicker",
        "heading"    => esc_html__("Button color", 'acumec'),
        "param_name" => "tcolor_button",
        "value"      => "",
        'template' => array('cms_grid--projects.php'),
    ),
    array(
        'type' => 'checkbox',
        'heading' => esc_html__("Show Socials", 'acumec'),
        'param_name' => 'show_social',
        'value' => array(
            'Yes' => true
        ),
        'std' => false,
        'template' => array('cms_grid--team1.php','cms_grid--team2.php'),
    ), 
    array(
        "type"       => "colorpicker",
        "heading"    => esc_html__("Background Color Socials", 'acumec'),
        "param_name" => "bg_social",
        "value"      => "",
        'dependency' => array(
            'element' => 'show_social',
            'value' => array(
                '1',
            ),
        ),
        'template' => array('cms_grid--team1.php','cms_grid--team2.php'),
    ),
    array(
        "type"       => "colorpicker",
        "heading"    => esc_html__("Color Socials", 'acumec'),
        "param_name" => "color_social",
        "value"      => "",
        'dependency' => array(
            'element' => 'show_social',
            'value' => array(
                '1',
            ),
        ),
        'template' => array('cms_grid--team1.php','cms_grid--team2.php'),
    ),
    array(
        'type' => 'checkbox',
        'heading' => esc_html__("Show Email Address", 'acumec'),
        'param_name' => 'show_email',
        'value' => array(
            'Yes' => true
        ),
        'std' => false,
        'template' => array('cms_grid--team1.php','cms_grid--team2.php'),
    ), 
    array(
        'type' => 'checkbox',
        'heading' => esc_html__("Show Phone No", 'acumec'),
        'param_name' => 'show_phone',
        'value' => array(
            'Yes' => true
        ),
        'std' => false,
        'template' => array('cms_grid--team1.php','cms_grid--team2.php'),
    ), 
    array(
        'type' => 'checkbox',
        'heading' => esc_html__("Show Location", 'acumec'),
        'param_name' => 'show_location',
        'value' => array(
            'Yes' => true
        ),
        'std' => false,
        'template' => array('cms_grid--team1.php','cms_grid--team2.php'),
    ), 
    

    array(
        'type'          => 'textfield',
        'heading'       => esc_html__("Padding", 'acumec'),
        'param_name'    => 'padding_grid',
        'value'         => '',
        'template'      => array('cms_grid--service3.php'),
    ),
    array(
        "type"       => "colorpicker",
        "heading"    => esc_html__("Color", 'acumec'),
        "param_name" => "tcolor",
        "value"      => "",
        'template'   => array('cms_grid--service1.php','cms_grid--service2.php','cms_grid--service3.php'),
    ),
    array(
        "type"       => "colorpicker",
        "heading"    => esc_html__("Color Content", 'acumec'),
        "param_name" => "ccolor",
        "value"      => "",
        'template'   => array('cms_grid--service2.php'),
    ),
    array(
        "type"       => "colorpicker",
        "heading"    => esc_html__("Background Color", 'acumec'),
        "param_name" => "bg_color",
        "value"      => "",
        'template'   => array('cms_grid--service3.php'),
    ),
    array(
        "type"       => "colorpicker",
        "heading"    => esc_html__("Background Color Hover", 'acumec'),
        "param_name" => "tcolor_hover",
        "value"      => "",
        'template'   => array('cms_grid--service1.php'),
    ),
    array(
        'type'          => 'textfield',
        'heading'       => esc_html__("Border Radius", 'acumec'),
        'param_name'    => 'radius',
        'value'         => '',
        'template'      => array('cms_grid--team1.php','cms_grid--team2.php','cms_grid--service1.php','cms_grid--service3.php'),
    ),

    array(
        'type'          => 'checkbox',
        'heading'       => esc_html__("Show Description", 'acumec'),
        'param_name'    => 'show_des',
        'value'         => array(
            'Yes' => true
        ),
        'std'           => false,
        'template'      => array('cms_grid.php','cms_grid--blog2.php','cms_grid--service1.php','cms_grid--service2.php','cms_grid--service3.php'),
    ),
    array(
        'type' => 'textfield',
        'heading' => esc_html__("Word Number Excerpt", 'acumec'),
        'param_name' => 'word_number',
        'value' => '',
        
    ),
    array(
        'type' => 'checkbox',
        'heading' => esc_html__("Show Service List", 'acumec'),
        'param_name' => 'show_list',
        'value' => array(
            'Yes' => true
        ),
        'std' => false,
        'template' => array('cms_grid--service2.php'),
    ),
    array(
        'type' => 'checkbox',
        'heading' => esc_html__("Show Pagination", 'acumec'),
        'param_name' => 'show_pagination',
        'value' => array(
            'Yes' => true
        ),
        'std' => false,
    ),
    array(
        "type" => "textfield",
        "heading" => esc_html__("Spacing Top Pagination (px)",'acumec'),
        "param_name" => "spacing_pagination",
        "value" => "",
        "description" => "",
        "template"      => array('cms_grid--projects.php') ,
    ),
);
vc_add_param("cms_grid", array(
    "type" => "dropdown",
    "heading" => esc_html__("Columns MD Devices",'acumec'),
    "param_name" => "col_md",
    "edit_field_class" => "vc_col-sm-3 vc_column",
    "value" => array(1,2,3,4,5,6,12),
    "std" => 3,
    "group" => esc_html__("Grid Settings", 'acumec')
));
vc_add_param("cms_grid", array(
    "type" => "dropdown",
    "heading" => esc_html__("Columns LG Devices",'acumec'),
    "param_name" => "col_lg",
    "edit_field_class" => "vc_col-sm-3 vc_column",
    "value" => array(1,2,3,4,5,6,12),
    "std" => 4,
    "group" => esc_html__("Grid Settings", 'acumec')
));
